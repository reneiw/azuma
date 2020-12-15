<?php

namespace Reneiw\Azuma;

use Reneiw\Azuma\Contracts\BasicAuthInterface;
use Reneiw\Azuma\Contracts\OAuthInterface;
use Reneiw\Azuma\Contracts\VerificationInterface;
use Reneiw\Azuma\Traits\BasicAuthTrait;
use Reneiw\Azuma\Traits\OAuthTrait;
use Reneiw\Azuma\Traits\VerificationTrait;

class Shopbase extends API implements BasicAuthInterface, OAuthInterface, VerificationInterface
{
    use BasicAuthTrait;
    use OAuthTrait;
    use VerificationTrait;

    public const HEADER_ACCESS_TOKEN = 'X-Shopbase-Access-Token';

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
