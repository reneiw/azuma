<?php

namespace Reneiw\Azuma\Traits;

trait BasicAuthTrait
{
    private ?string $username = null;
    private ?string $password = null;

    protected function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->useBasicAuth();
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(string $password): self
    {
        $this->password = $password;
        $this->useBasicAuth();
        return $this;
    }

    public function useBasicAuth(array $data = []): self
    {
        if (!empty($data['username'])) {
            $this->setUsername($data['username']);
        }
        if (!empty($data['password'])) {
            $this->setPassword($data['password']);
        }
        $this->getOptions()['auth'] = [$this->getUsername(), $this->getPassword()];
        return $this;
    }
}
