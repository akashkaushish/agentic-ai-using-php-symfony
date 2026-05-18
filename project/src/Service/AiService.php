<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AiService
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private string $openaiApiKey
    ) {}

    public function ask(string $prompt): string
    {
        $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->openaiApiKey,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
            ],
        ]);

        $data = $response->toArray(false);
        
        if (isset($data['error']['message'])) {
            return 'OpenAI error: ' . $data['error']['message'];
        }

        return $data['choices'][0]['message']['content'] ?? 'No response';
    }
}
