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

        $writer->startElement('head');

        $writer->startElement('meta');
        $writer->writeAttribute('charset', 'utf-8');
        $writer->endElement();

        $writer->startElement('meta');
        $writer->writeAttribute('name', 'viewport');
        $writer->writeAttribute('content', 'width=' . $width . ', height=' . $height);
        $writer->endElement();

        $writer->startElement('title');
        $writer->text($title);
        $writer->endElement();

        $writer->startElement('link');
        $writer->writeAttribute('href', 'css/style.css');
        $writer->writeAttribute('rel', 'stylesheet');
        $writer->writeAttribute('type', 'text/css');
        $writer->endElement();

        $writer->endElement();

        $writer->startElement('body');
        $writer->writeAttribute('id', "$id-body");
        $writer->writeAttribute('class', 'body');

        $writer->startElement('div');
        $writer->writeAttribute('id', "$id-container");
        $writer->writeAttribute('class', 'image-container');

        $writer->startElement('img');
        $writer->writeAttribute('id', "$id-img");
        $writer->writeAttribute('class', 'image-item');
        $writer->writeAttribute('src', $path);
        $writer->writeAttribute('alt', $title);
        $writer->endElement();

        $writer->endElement();

        $writer->endElement();

        $writer->endElement();

        return $writer->outputMemory();
    }
}
