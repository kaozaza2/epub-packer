<?php

namespace App\Contracts\Actions;

interface TocMaker
{
    public function make(string $title, array $pages): string;
}
