<?php

namespace Reneiw\Azuma\Contracts;

interface VerificationInterface
{
    public function setAPIKey(string $key);

    public function setAPISecretKey(string $key);
}
