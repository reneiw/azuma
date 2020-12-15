<?php

namespace Reneiw\Azuma\Contracts;

interface BasicAuthInterface
{
    public function setUsername(string $username);

    public function setPassword(string $password);

    public function useBasicAuth();
}
