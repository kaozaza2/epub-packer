<?php

namespace App\Contracts\Actions;

interface ZipPacker
{
    public function pack(string $title, array $images, array $options = []): string;
}
