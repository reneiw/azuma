<?php

namespace Reneiw\Azuma\Traits;

trait BearerTokenTrait
{
    protected ?string $bearerToken = null;

    protected function getBearerToken(): ?string
    {
        return $this->bearerToken;
    }

    public function setBearerToken(string $token): self
    {
        if (strncmp($token, 'Bearer ', 7) !== 0) {
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
        $this->setOptions(['headers' => [self::HEADER_ACCESS_TOKEN => $this->getBearerToken()]]);
        return $this;
    }
}
