<?php

namespace App\Contracts\Actions;

use Illuminate\Support\Collection;

interface PageContentMakerFactory
{
    public function make(Collection|array $images = [], array $options = []): array;
}
