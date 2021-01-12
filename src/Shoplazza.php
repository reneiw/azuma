<?php

namespace Reneiw\Azuma;

use Reneiw\Azuma\Contracts\OAuthInterface;
use Reneiw\Azuma\Traits\OAuthTrait;

class Shoplazza extends API implements OAuthInterface
{
    use OAuthTrait;

    public const HEADER_ACCESS_TOKEN = 'Access-Token';

    public function __construct(array $data)
    {
        $this->useOAuth($data);
        parent::__construct($data);
    }
}
