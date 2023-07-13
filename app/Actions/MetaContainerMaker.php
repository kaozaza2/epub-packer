<?php

namespace App\Actions;

use App\Contracts\Actions\MetaContainerMaker as MetaContainerMakerContract;
use XMLWriter;

class MetaContainerMaker implements MetaContainerMakerContract
{
    public function make(): string
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');

        $writer->startDocument('1.0', 'UTF-8', 'yes');

        $writer->startElement('container');
        $writer->writeAttribute('version', '1.0');
        $writer->writeAttribute('xmlns', 'urn:oasis:names:tc:opendocument:xmlns:container');

        $writer->startElement('rootfiles');

        $writer->startElement('rootfile');
        $writer->writeAttribute('full-path', 'OEBPS/content.opf');
        $writer->writeAttribute('media-type', 'application/oebps-package+xml');
        $writer->endElement();

        $writer->endElement();

        $writer->endElement();

        return $writer->outputMemory();
    }
}
