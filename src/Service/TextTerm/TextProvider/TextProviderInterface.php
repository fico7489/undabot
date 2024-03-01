<?php

namespace App\Service\TextTerm\TextProvider;

interface TextProviderInterface
{
    public function name(): string;

    public function url(): string;

    public function fetch(): string;
}
