<?php

namespace App\Service\TextTerm\Provider;

use GuzzleHttp\Client;

class GithubIssueProvider implements ProviderInterface
{
    public function __construct(
        private readonly string $token,
        private readonly string $url,
    ) {
    }

    public function getName(): string
    {
        return 'github_issue';
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function fetchText(): string
    {
        exit('fetchText');
        $client = new Client([
            'base_uri' => 'https://api.github.com', 'headers' => [
                'Authorization' => 'Bearer '.$this->token,
            ],
        ]);

        $url = $this->getUrl();
        $reponse = $client->get($url);
        $contents = $reponse->getBody()->getContents();
        $contents = json_decode($contents, true);

        $text = $contents['body'] ?? null;

        return $text;
    }
}
