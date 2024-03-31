<?php

namespace App\Services;

use JDecool\OllamaClient\Client;
use JDecool\OllamaClient\ClientBuilder;
use JDecool\OllamaClient\Client\Message;
use JDecool\OllamaClient\Client\Request\ChatRequest;

class OllamaService
{
    private Client $client;
    private string $model = 'openchat';

    public function __construct()
    {
        $builder = new ClientBuilder();
        $this->client = $builder->create();
    }

    private function createMessage($questions)
    {
        return new Message($questions['role'], $questions['content']);
    }

    public function ask(array $questions)
    {
        logger('ask', $questions);
        $messages = array_map([$this, 'createMessage'], $questions);
        $request = new ChatRequest($this->model, $messages);
        return $this->client->chatStream($request);
    }
}
