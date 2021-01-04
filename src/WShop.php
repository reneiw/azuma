<?php

namespace Reneiw\Azuma;

use Reneiw\Azuma\Traits\OAuthTrait;

class WShop extends API
{
    use OAuthTrait;

    public const HEADER_ACCESS_TOKEN = 'Authorization';

    public function __construct(array $data = [])
    {
        switch (true) {
            case !empty($data['token']):
                $this->useOAuth($data);
                break;
            default:
                break;
        }
        parent::__construct($data);
    }
}
