<?php

namespace Reneiw\Azuma\Traits;

trait BearerTokenTrait
{
    protected ?string $bearerToken;

    protected function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $token): self
    {
        if (!substr($token, 0, 7) === 'Bearer ') {
            $token = 'Bearer ' . $token;
        }
        $this->bearerToken = $token;
        return $this;
    }

    public function useBearerToken(array $data = []): self
    {
        if ($data['bearerToken'] ?? false) {
            $this->setBearerToken($data['bearerToken']);
        }
        $this->setOptions(['headers' => ['Authorization' => $this->getBearerToken()]]);
        return $this;
    }
}
