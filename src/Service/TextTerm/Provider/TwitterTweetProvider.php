<?php

namespace App\Service\TextTerm\Provider;

class TwitterTweetProvider implements ProviderInterface
{
    public function getName(): string
    {
        throw new \Exception('Not implemented');
    }

    public function getUrl(): string
    {
        throw new \Exception('Not implemented');
    }

    public function fetchText(): string
    {
        throw new \Exception('Not implemented');
    }
}
