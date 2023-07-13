<?php

namespace App\Actions;

use App\Contracts\Actions\MimetypeMaker as MimetypeMakerContract;

class MimetypeMaker implements MimetypeMakerContract
{
    public function make(): string
    {
        return 'application/epub+zip';
    }
}
