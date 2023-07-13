<?php

namespace App\Contracts\Actions;

interface PageContentMaker
{
    public function make(string $id, string $title, string $width, string $height, string $path): string;
}
