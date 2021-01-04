<?php

namespace Reneiw\Azuma\Contracts;

interface BearerTokenInterface
{
    public function setBearerToken(string $token);

    public function useBearerToken(array $data = []);
}
