<?php

namespace App\Actions;

use App\Contracts\Actions\ContentOpfMaker as ContentOpfMakerContract;
use App\Contracts\Actions\MetaContainerMaker as MetaContainerMakerContract;
use App\Contracts\Actions\MimetypeMaker as MimetypeMakerContract;
use App\Contracts\Actions\PageContentMakerFactory as PageContentMakerFactoryContract;
use App\Contracts\Actions\TocMaker as TocMakerContract;
use App\Contracts\Actions\ZipPacker as ZipPackerContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class ZipPacker implements ZipPackerContract
{
    public function __construct(
        protected readonly MimetypeMakerContract           $mimetypeMaker,
        protected readonly MetaContainerMakerContract      $metaMaker,
        protected readonly PageContentMakerFactoryContract $pageMaker,
        protected readonly TocMakerContract                $tocMaker,
        protected readonly ContentOpfMakerContract         $contentMaker,
    )
    {
        // noop
    }

    public function pack(string $title, array $images, array $options = []): string
    {

        $zipName = Str::of($title)->slug()->append('.epub');
        $path = Storage::path($pp = Str::random() . '-' . $zipName);

        $zip = new ZipArchive();
        $zip->open($path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $zip->addFromString('mimetype', $this->mimetypeMaker->make());
        $zip->addFromString('META-INF/container.xml', $this->metaMaker->make());

        $resources = collect();

        foreach (Storage::disk('assets')->files('css') as $css) {
            $zip->addFromString(
                'OEBPS/css/' . pathinfo($css, PATHINFO_BASENAME),
                Storage::disk('assets')->get($css),
            );

            $resources->push([
                'id' => pathinfo($css, PATHINFO_FILENAME),
                'href' => 'css/' . pathinfo($css, PATHINFO_BASENAME),
                'media-type' => 'text/css',
                'tag' => 'manifest',
            ]);
        }

        collect($images)
            ->map(fn($image, $index) => [
                'path' => Storage::path($image),
                'name' => Str::of($index + 1)
                    ->append('.', pathinfo($image, PATHINFO_EXTENSION))
                    ->toString(),
            ])
            ->each(fn($image) => $zip->addFile($image['path'], 'OEBPS/image/' . $image['name']))
            ->each(function ($image, $index) use ($resources) {
                $resources->push([
                    'id' => 'image-file-' . ($index + 1),
                    'href' => 'image/' . $image['name'],
                    'media-type' => mime_content_type($image['path']),
                    'properties' => $index === 0 ? 'cover-image' : null,
                    'tag' => 'manifest',
                ]);

                if ($index === 0) {
                    $resources->push([
                        'name' => 'cover',
                        'content' => 'image-file-' . ($index + 1),
                        'tname' => 'meta',
                        'tag' => 'metadata',
                    ]);
                }
            })
            ->pipe(
                fn($collect) => collect($this->pageMaker->make(
                    $collect->pluck('name')->map(fn($p) => 'image/' . $p),
                    $options,
                )),
            )
            ->each(function ($page, $index) use ($resources) {
                $resources->push([
                    'id' => 'page-' . ($index + 1),
                    'href' => $page['name'],
                    'media-type' => 'application/xhtml+xml',
                    'tag' => 'manifest',
                ]);

                $resources->push([
                    'linear' => 'yes',
                    'idref' => 'page-' . ($index + 1),
                    'properties' => match (true) {
                        ($options['pager'] ?? 'no') === 'yes',
                            $index == 0 => 'rendition:page-spread-center',
                        $index % 2 == 0 => 'rendition:page-spread-right',
                        default => 'rendition:page-spread-left',
                    },
                    'tag' => 'spine',
                ]);
            })
            ->each(fn($page) => $zip->addFromString('OEBPS/' . $page['name'], $page['data']))
            ->pipe(fn($page) => $zip->addFromString('OEBPS/toc.xhtml', $this->tocMaker->make($title, $page->pluck('name')->toArray())));

        $resources->push(['tag' => 'metadata', 'tname' => 'dc:title', 'text' => $title]);

        $resources->push(['tag' => 'metadata', 'tname' => 'dc:language', 'text' => $options['language'] ?? 'en']);
        $resources->push(['tag' => 'metadata', 'tname' => 'dc:identifier', 'id' => 'bookid', 'text' => $options['identifier'] ?? Str::uuid()->getUrn()]);
        $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'name' => 'generator', 'content' => 'Adobe InDesign 16.4']);
        $resources->push(['tag' => 'metadata', 'tname' => 'dc:date', 'text' => now()->format('Y-m-d\TH:i:s\Z')]);
        $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'dcterms:modified', 'text' => now()->format('Y-m-d\TH:i:s\Z')]);

        $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:orientation', 'text' => 'auto']);
        $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:flow', 'text' => 'scrolled-continuous']);
        $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'name' => 'scroll-direction', 'content' => 'ttb']);
        if (($options['pager'] ?? 'no') === 'no') {
            $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:layout', 'text' => 'reflowable']);
            $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:spread', 'text' => 'auto']);
        } else {
            $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:layout', 'text' => 'pre-paginated']);
            $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'name' => 'fixed-layout', 'content' => 'true']);
            $resources->push(['tag' => 'metadata', 'tname' => 'meta', 'property' => 'rendition:spread', 'text' => 'none']);
        }

        if (isset($options['author'])) {
            $resources->push(['tag' => 'metadata', 'tname' => 'dc:creator', 'text' => $options['author']]);
        }

        if (isset($options['publisher'])) {
            $resources->push(['tag' => 'metadata', 'tname' => 'dc:publisher', 'text' => $options['publisher']]);
        }

        if (isset($options['subject'])) {
            $resources->push(['tag' => 'metadata', 'tname' => 'dc:subject', 'text' => $options['subject']]);
        }

        $resources->push(['tag' => 'manifest', 'id' => 'toc', 'href' => 'toc.xhtml', 'media-type' => 'application/xhtml+xml', 'properties' => 'nav']);

        $zip->addFromString('OEBPS/content.opf', $this->contentMaker->make($resources));

        $zip->close();

        return $pp;
    }
}
