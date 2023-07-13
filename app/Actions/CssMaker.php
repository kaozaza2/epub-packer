<?php

namespace App\Actions;

use App\Contracts\Actions\CssMaker as CssMakerContract;

class CssMaker implements CssMakerContract
{
    public function make(array $options = []): string
    {
        $width = $options['width'] ?? '1800';
        $height = $options['height'] ?? '2700';

        return <<<EOT
        body, div, dl, dt, dd, h1, h2, h3, h4, h5, h6, p, pre, code, blockquote {
            margin: 0;
            padding: 0;
            border-width: 0;
            text-rendering: optimizeSpeed;
        }

        .image-container {
            position: absolute;
            top: 0;
            left: 0;
            width: {$width}px;
            height: {$height}px;
        }

        .image-item {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            min-width: 100%;
            width: 100%;
            object-fit: contain;
        }
        EOT;
    }
}
