<?php

namespace Reneiw\Azuma\Traits;

trait BasicAuthTrait
{
    protected ?string $username;
    protected ?string $password;

    protected function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): BasicAuthTrait
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }


    public function setPassword(string $password): BasicAuthTrait
    {
        $this->password = $password;
        return $this;
    }

    public function useBasicAuth(): BasicAuthTrait
    {
        $this->options['auth'] = [$this->getUsername(), $this->getPassword()];
        return $this;
    }
}
