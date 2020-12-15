<?php

namespace Reneiw\Azuma\Contracts;

interface OAuthInterface
{

    public function setAccessTokenKey(string $headerKey);

    public function setAccessTokenValue(string $headerValue);

    public function useOAuth();
}
