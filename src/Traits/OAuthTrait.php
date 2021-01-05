<?php

namespace Reneiw\Azuma\Traits;

trait OAuthTrait
{
    private ?string $accessTokenKey = null;
    private ?string $accessTokenValue = null;

    public function getAccessTokenKey(): ?string
    {
        return $this->accessTokenKey;
    }

    public function setAccessTokenKey(string $headerKey = null): self
    {
        $this->accessTokenKey = $headerKey;
        return $this;
    }

    protected function getAccessTokenValue(): ?string
    {
        return $this->accessTokenValue;
    }

    public function setAccessTokenValue(string $headerValue): self
    {
        $this->accessTokenValue = $headerValue;
        return $this;
    }

    public function useOAuth(array $data = []): self
    {
        $this->setAccessTokenKey(self::HEADER_ACCESS_TOKEN);
        if (!empty($data['token'])) {
            $this->setAccessTokenValue($data['token']);
        }
        $this->setOptions(['headers' => [$this->getAccessTokenKey() => $this->getAccessTokenValue()]]);
        return $this;
    }
}
