<?php

namespace App\Tests\Controller;

use App\Tests\TestCase;

class ProviderTextTermScoreControllerTest extends TestCase
{
    public function testService(): void
    {
        $this->mockTextTermProvider();

        $response = $this->client->request('GET', '/api/provider_text_terms/score?term=php&include=providerText', [
            'headers' => [
                'Accept' => 'application/vnd.api+json',
            ],
        ]);

        $data = $response->toArray()['data'];
        $included = $response->toArray()['included'];

        $this->assertEquals('/api/provider_text_terms/score', $data['id']);
        $this->assertEquals('ProviderTextTerm', $data['type']);
        $this->assertEquals(1, $data['attributes']['_id']);
        $this->assertEquals('php', $data['attributes']['term']);
        $this->assertEquals(0, $data['attributes']['score']);
        $this->assertEquals('ProviderText', $data['relationships']['providerText']['data']['type']);
        $this->assertEquals('/api/provider_texts/1', $data['relationships']['providerText']['data']['id']);

        $this->assertEquals(1, count($included));
        $this->assertEquals('/api/provider_texts/1', $included[0]['id']);
        $this->assertEquals('ProviderText', $included[0]['type']);
        $this->assertEquals(1, $included[0]['attributes']['_id']);
        $this->assertEquals('github_issue', $included[0]['attributes']['provider']);
        $this->assertEquals('test', $included[0]['attributes']['url']);
        $this->assertEquals('tester', $included[0]['attributes']['text']);
    }
}
