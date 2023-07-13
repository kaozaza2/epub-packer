<?php

namespace App\Actions;

use App\Contracts\Actions\TocMaker as TocMakerContract;
use Exception;
use XMLWriter;

class TocMaker implements TocMakerContract
{
    public function make(string $title, array $pages): string
    {
        if (empty($pages)) {
            throw new Exception('Pages array is empty.');
        }

        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');

        $writer->startDocument('1.0', 'UTF-8', 'no');
        $writer->startDtd('html');
        $writer->endDtd();

        $writer->startElement('html');
        $writer->writeAttribute('xmlns', 'http://www.w3.org/1999/xhtml');
        $writer->writeAttribute('xmlns:epub', 'http://www.idpf.org/2007/ops');

        $writer->startElement('head');

        $writer->startElement('title');
        $writer->text($title);
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('body');

        $writer->startElement('nav');
        $writer->writeAttribute('id', 'toc');
        $writer->writeAttribute('epub:type', 'toc');
        $writer->startElement('h1');
        $writer->text('Navigation');
        $writer->endElement();
        $writer->startElement('ol');
        $writer->startElement('li');
        $writer->startElement('a');
        $writer->writeAttribute('href', $pages[0]);
        $writer->text('Cover');
        $writer->endElement();
        $writer->endElement();
        $writer->endElement();
        $writer->endElement();

        $writer->startElement('nav');
        $writer->writeAttribute('epub:type', 'landmarks');
        $writer->writeAttribute('id', 'guide');
        $writer->startElement('h1');
        $writer->text('Guide');
        $writer->endElement();
        $writer->startElement('ol');
        $writer->startElement('li');
        $writer->startElement('a');
        $writer->writeAttribute('epub:type', 'cover');
        $writer->writeAttribute('href', $pages[0]);
        $writer->text('Cover');
        $writer->endElement();
        $writer->endElement();
        $writer->endElement();
        $writer->endElement();

        $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory();
    }
}
