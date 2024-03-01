<?php

namespace App\Service\TextTerm\TextProvider;

class TwitterTweetTextProvider implements TextProviderInterface
{
    public function name(): string
    {
        return 'twitter_tweet';
    }

    public function url(): string
    {
        return '/dummy-url/for/tweet/1';
    }

    public function fetch(): string
    {
        return 'testing...';
    }
}
