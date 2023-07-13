<?php

namespace App\Actions;

use App\Contracts\Actions\PageContentMaker as PageContentMakerContract;
use App\Contracts\Actions\PageContentMakerFactory as PageContentMakerFactoryContract;
use Exception;
use Illuminate\Support\Collection;

class PageContentMakerFactory implements PageContentMakerFactoryContract
{
    public function __construct(
        protected readonly PageContentMakerContract $contentMaker,
    ) {
        // noop
    }

    public function make(Collection|array $images = [], array $options = []): array
    {
        if (! isset($options['title'])) {
            throw new Exception('Title is required');
        }

        $title = $options['title'];
        $width = $options['width'] ?? '1800';
        $height = $options['height'] ?? '2700';
        $id = $options['page-id'] ?? 'page';

        return Collection::wrap($images)
            ->map(fn ($path, $index) => [
                'name' => 'page-'.($index + 1).'.xhtml',
                'data' => $this->contentMaker->make(
                    $id.'-'.($index + 1),
                    $title,
                    $width,
                    $height,
                    'image/'.basename($path),
                ),
            ])->toArray();
    }
}
