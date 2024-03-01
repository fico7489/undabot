<?php

namespace App\Service\TextTerm\Provider;

use GuzzleHttp\Client;

class GithubIssueProvider implements ProviderInterface
{
    public function __construct(
        private string $token,
        private string $url,
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
        $client = new Client([
            'base_uri' => 'https://api.github.com', 'headers' => [
                'Authorization' => 'Bearer '.$this->token,
            ],
        ]);

        $url = $this->getUrl();
        $response = $client->get($url);
        $contents = $response->getBody()->getContents();
        $contents = json_decode($contents, true);

        $text = $contents['body'] ?? null;

        return $text;
    }
}
