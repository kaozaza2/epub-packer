<?php

namespace App\Contracts\Actions;

interface CssMaker
{
    public function make(array $options = []): string;
}
