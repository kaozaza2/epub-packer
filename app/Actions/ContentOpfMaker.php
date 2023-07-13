<?php

namespace App\Actions;

use App\Contracts\Actions\ContentOpfMaker as ContentOpfMakerContract;
use Illuminate\Support\Collection;
use XMLWriter;

class ContentOpfMaker implements ContentOpfMakerContract
{
    public function make(Collection|array $resources): string
    {
        $resources = Collection::wrap($resources);

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');

        $writer->startDocument('1.0', 'UTF-8', 'yes');
        $writer->startElement('package');
        $writer->writeAttribute('xmlns', 'http://www.idpf.org/2007/opf');
        $writer->writeAttribute('version', '3.0');
        $writer->writeAttribute('unique-identifier', 'bookid');
        $writer->writeAttribute(
            'prefix',
            implode(' ', [
                'rendition: http://www.idpf.org/vocab/rendition/#',
                'ibooks: http://vocabulary.itunes.apple.com/rdf/ibooks/vocabulary-extensions-1.0/'
            ])
        );

        $writer->startElement('metadata');
        $writer->writeAttribute('xmlns:dc', 'http://purl.org/dc/elements/1.1/');

        foreach ($resources->where('tag', 'metadata') as $m) {
            $writer->startElement($m['tname']);

            if (isset($m['id'])) {
                $writer->writeAttribute('id', $m['id']);
            }

            if (isset($m['name'])) {
                $writer->writeAttribute('name', $m['name']);
            }

            if (isset($m['content'])) {
                $writer->writeAttribute('content', $m['content']);
            }

            if (isset($m['property'])) {
                $writer->writeAttribute('property', $m['property']);
            }

            if (isset($m['text'])) {
                $writer->text($m['text']);
            }

            $writer->endElement();
        }

        $writer->endElement();

        $writer->startElement('manifest');

        foreach ($resources->where('tag', 'manifest') as $m) {
            $writer->startElement('item');
            $writer->writeAttribute('id', $m['id']);
            $writer->writeAttribute('href', $m['href']);
            $writer->writeAttribute('media-type', $m['media-type']);

            if (isset($m['properties'])) {
                $writer->writeAttribute('properties', $m['properties']);
            }

            $writer->endElement();
        }

        $writer->endElement();

        $writer->startElement('spine');

        foreach ($resources->where('tag', 'spine') as $m) {
            $writer->startElement('itemref');
            $writer->writeAttribute('idref', $m['idref']);

            if (isset($m['linear'])) {
                $writer->writeAttribute('linear', $m['linear']);
            }

            if (isset($m['properties'])) {
                $writer->writeAttribute('properties', $m['properties']);
            }

            $writer->endElement();
        }

        $writer->endElement();

        $writer->endElement();

        return $writer->outputMemory();
    }
}
