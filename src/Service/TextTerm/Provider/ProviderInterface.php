<?php

namespace App\Service\TextTerm\Provider;

interface ProviderInterface
{
    public function getName(): string;

    public function getUrl(): string;

    public function fetchText(): string;
}
