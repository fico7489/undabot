<?php

namespace App\Service\TextTerm\TextProvider;

use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GithubIssueTextProvider implements TextProviderInterface
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
    ) {
    }

    public function name(): string
    {
        return 'github_issue';
    }

    public function url(): string
    {
        // TODO convert to only url + token, add to service so that we can mock it

        $token = $this->parameterBag->get('app.score_text_fetcher.github.token');
        $username = $this->parameterBag->get('app.score_text_fetcher.github.username');
        $repo = $this->parameterBag->get('app.score_text_fetcher.github.repo');
        $issue_id = $this->parameterBag->get('app.score_text_fetcher.github.issue_id');

        return '/repos/'.$username.'/'.$repo.'/issues/'.$issue_id;
    }

    public function fetch(): string
    {
        return '1php rocks23123 erewfsd fas php sucksasda sphp adasd as dphphp sucksp adasda php adasd asdas da sd asD
AsD aphp suphp php rocksrockscphp rockskssd asd php rocksas php a rocks
123123123123phphp rocksp sucks123
999999php sphphp rocksphp rocksphp rocksphp rocksphp rocksp rocksucks99999php rocks999999
php 12213php rockssad ad asd

php sucks

done';

        $client = new Client([
            'base_uri' => 'https://api.github.com', 'headers' => [
                'Authorization' => 'Bearer '.$token,
            ],
        ]);

        $url = $this->url();
        $reponse = $client->get($url);
        $contents = $reponse->getBody()->getContents();
        $contents = json_decode($contents, true);

        $text = $contents['body'] ?? null;

        return $text;
    }
}
