<?php

namespace App\Actions;

use App\Contracts\Actions\PageContentMaker as PageContentMakerContract;
use XMLWriter;

class PageContentMaker implements PageContentMakerContract
{
    public function make(string $id, string $title, string $width, string $height, string $path): string
    {
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
        $writer->writeAttribute('class', 'hltr');

        $writer->startElement('head');

        $writer->startElement('meta');
        $writer->writeAttribute('charset', 'utf-8');
        $writer->endElement();

        $writer->startElement('title');
        $writer->text($title);
        $writer->endElement();

        $writer->startElement('link');
        $writer->writeAttribute('rel', 'stylesheet');
        $writer->writeAttribute('type', 'text/css');
        $writer->writeAttribute('href', 'css/book-style.css');
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('body');
        $writer->writeAttribute('class', 'p-image');

        $writer->startElement('div');
        $writer->writeAttribute('class', 'main align-center');

        $writer->startElement('p');

        $writer->startElement('img');
        $writer->writeAttribute('class', 'fit');
        $writer->writeAttribute('src', $path);
        $writer->writeAttribute('alt', '');
        $writer->endElement();

        $writer->endElement();
        $writer->endElement();
        $writer->endElement();
        $writer->endElement();

        return $writer->outputMemory();
    }
}
