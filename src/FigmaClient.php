<?php

namespace LyngDev\FigmaAPI;


use GuzzleHttp\Client;

class FigmaClient
{
    private Client $client;
    public function __construct(private readonly Configuration $configuration){
        $this->client = new Client();
    }

    public function getFiles(string $key){
        $path = '/v1/files/' . $key;
        $url = $this->getUrl($path);
        $headers = $this->getAuthHeaders();

        return $this->client->request('GET', $url, [
            'headers' => $headers,
        ]);
    }

    private function getUrl(string $path): string
    {
        $baseUrl = $this->getBaseUrl();
        if(str_starts_with($path, '/') && str_ends_with($baseUrl, '/')){
            $path = substr($path,1);
        }
        return $baseUrl . $path;
    }

    private function getBaseUrl(): string
    {
        return $this->configuration->apiUrl;
    }

    private function getAuthHeaders(): array
    {
        return [
            'X-Figma-Token' => $this->configuration->accessToken
        ];
    }
}
