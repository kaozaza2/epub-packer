<?php

namespace App\Contracts\Actions;

use Illuminate\Support\Collection;

interface ContentOpfMaker
{
    public function make(Collection|array $resources): string;
}
