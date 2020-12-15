<?php

namespace Reneiw\Azuma\Traits;

trait OAuthTrait
{
    protected ?string $accessTokenKey;
    protected ?string $accessTokenValue;

    public function getAccessTokenKey(): ?string
    {
        if (!$this->accessTokenKey) {
            $this->setAccessTokenKey();
        }
        return $this->accessTokenKey;
    }

    public function setAccessTokenKey(string $headerKey = null): OAuthTrait
    {
        $this->accessTokenKey = $headerKey ?: self::HEADER_ACCESS_TOKEN;
        return $this;
    }

    protected function getAccessTokenValue(): ?string
    {
        return $this->accessTokenValue;
    }

    public function setAccessTokenValue(string $headerValue): OAuthTrait
    {
        $this->accessTokenValue = $headerValue;
        return $this;
    }

    public function useOAuth(): OAuthTrait
    {
        $this->getOptions()['headers'] = array_replace($this->getOptions()['headers'], [$this->getAccessTokenKey() => $this->getAccessTokenValue()]);
        return $this;
    }

}
