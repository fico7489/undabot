<?php

namespace App\Tests\Controller;

use App\Tests\TestCase;

class TextTermV2ControllerTest extends TestCase
{
    public function testService(): void
    {
        $this->mockTextTermProvider();

        $response = $this->client->request('POST', '/api/v2/text_terms?include=text', [
            'headers' => [
                'Accept' => 'application/vnd.api+json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'term' => 'php',
            ],
        ]);

        $data = $response->toArray()['data'];
        $included = $response->toArray()['included'];

        $this->assertEquals('/api/text_terms/score', $data['id']);
        $this->assertEquals('TextTerm', $data['type']);
        $this->assertEquals(1, $data['attributes']['_id']);
        $this->assertEquals('php', $data['attributes']['term']);
        $this->assertEquals(0, $data['attributes']['score']);
        $this->assertEquals('Text', $data['relationships']['text']['data']['type']);
        $this->assertEquals('/api/texts/1', $data['relationships']['text']['data']['id']);

        $this->assertEquals(1, count($included));
        $this->assertEquals('/api/texts/1', $included[0]['id']);
        $this->assertEquals('Text', $included[0]['type']);
        $this->assertEquals(1, $included[0]['attributes']['_id']);
        $this->assertEquals('github_issue', $included[0]['attributes']['provider']);
        $this->assertEquals('test', $included[0]['attributes']['url']);
        $this->assertEquals('tester', $included[0]['attributes']['text']);
    }
}
