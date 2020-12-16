<?php

namespace Reneiw\Azuma\Traits;

trait OAuthTrait
{
    private ?string $accessTokenKey = null;
    private ?string $accessTokenValue = null;

    public function getAccessTokenKey(): ?string
    {
        if (!$this->accessTokenKey) {
            $this->setAccessTokenKey();
        }
        return $this->accessTokenKey;
    }

    public function setAccessTokenKey(string $headerKey = null): self
    {
        $this->accessTokenKey = $headerKey ?: self::HEADER_ACCESS_TOKEN;
        $this->useOAuth();
        return $this;
    }

    protected function getAccessTokenValue(): ?string
    {
        return $this->accessTokenValue;
    }

    public function setAccessTokenValue(string $headerValue): self
    {
        $this->accessTokenValue = $headerValue;
        $this->useOAuth();
        return $this;
    }

    public function useOAuth(): self
    {
        $this->getOptions()['headers'] = array_replace($this->getOptions()['headers'], [$this->getAccessTokenKey() => $this->getAccessTokenValue()]);
        return $this;
    }
}
