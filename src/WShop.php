<?php

namespace Reneiw\Azuma;

use Reneiw\Azuma\Contracts\BearerTokenInterface;
use Reneiw\Azuma\Traits\BearerTokenTrait;

class WShop extends API implements BearerTokenInterface
{
    use BearerTokenTrait;

    public const HEADER_ACCESS_TOKEN = 'Authorization';

    public function __construct(array $data = [])
    {
        switch (true) {
            case !empty($data['bearerToken']):
                $this->useBearerToken($data);
                break;
            default:
                break;
        }
        parent::__construct($data);
    }
}
