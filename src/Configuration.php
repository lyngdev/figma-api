<?php

namespace LyngDev\FigmaAPI;

class Configuration
{
    public function __construct(
        public string $accessToken,
        public string $apiUrl = 'https://api.figma.com/'
    ){
        //TODO: Implement some OAuth 2 options
    }
}
