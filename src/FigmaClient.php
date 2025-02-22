<?php

namespace LyngDev\FigmaAPI;


use GuzzleHttp\Client;

class FigmaClient
{
    private Client $client;
    public function __construct(private readonly Configuration $configuration){
        $this->client = new Client();
    }

    public function getProjects(string $teamId){
        return $this->client->request('GET', $this->getUrl("/v1/teams/{$teamId}/projects"), [
            'headers' => $this->getAuthHeaders()
        ]);
    }

    public function getProjectFiles(string $projectId){
        return $this->client->request('GET', $this->getUrl("/v1/projects/{$projectId}/files"),[
            'headers' => $this->getAuthHeaders(),
        ]);
    }

    public function getUser(){
        return $this->client->request('GET', $this->getUrl('/v1/me'),[
            'headers' => $this->getAuthHeaders()
        ]);
    }

    public function getComments(string $key){
        return $this->client->request('GET', $this->getUrl("/v1/files/{$key}/comments"), [
            'headers' => $this->getAuthHeaders()
        ]);
    }

    public function getImage(string $key, array|string $ids, ImageFormat $imageFormat = ImageFormat::PNG, float $scale = 1.0, bool $svgOutlineText = true, bool $svgIncludeId = false){
        $url = $this->getUrl("/v1/images/{$key}");
        if(is_array($ids)){
            $ids = implode(',', $ids);
        }
        return $this->client->request('GET', $url, [
            'headers' => $this->getAuthHeaders(),
            'query' => [
                'ids' => $ids,
                'format' => $imageFormat->value,
                'scale' => max(0.01, min(4, $scale)),
                'svg_outline_text' => $svgOutlineText ? 'true' : 'false',
                'svg_include_id' => $svgIncludeId ? 'true' : 'false',
            ]
        ]);
    }

    public function getFileNodes(string $key){
        return $this->client->request('GET', $this->getUrl("/v1/files/{$key}/nodes"), [
            'headers' => $this->getAuthHeaders(),
        ]);
    }

    public function getFile(string $key){
        return $this->client->request('GET', $this->getUrl("/v1/files/{$key}"), [
            'headers' => $this->getAuthHeaders(),
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
