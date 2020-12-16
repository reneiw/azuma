<?php

namespace Reneiw\Azuma\Traits;

use GuzzleHttp\Psr7\Uri;
use Reneiw\Azuma\Exceptions\AuthenticationException;
use Reneiw\Azuma\Exceptions\ClientException;

trait VerificationTrait
{
    private ?string $apiKey = null;
    private ?string $apiSecretKey = null;

    /**
     * @param  array  $params
     *
     * @return bool
     * @throws ClientException
     */
    public function verifyRequest(array $params): bool
    {
        if (empty($this->getAPISecretKey())) {
            throw new ClientException('API secret key is missing.');
        }

        if (array_key_exists('shop', $params)
            && array_key_exists('timestamp', $params)
            && array_key_exists('hmac', $params)
        ) {
            // Grab the HMAC, remove it from the params, then sort the params for hashing
            $hmac = $params['hmac'];
            unset($params['hmac']);

            ksort($params);

            // Encode and hash the params (without HMAC), add the API secret, and compare to the HMAC from params
            return $hmac === hash_hmac(
                'sha256',
                urldecode(http_build_query($params)),
                $this->getAPISecretKey()
            );
        }

        // Not valid
        return false;
    }

    /**
     * @param  string  $code
     * @param  string  $key
     *
     * @return array
     * @throws AuthenticationException
     * @throws ClientException
     */
    public function requestAccess(string $code, string $key = 'access_token'): array
    {
        if ($this->getApiSecretKey() === null || $this->getApiKey() === null) {
            // Key and secret required
            throw new ClientException('API key or secret is missing');
        }

        // Do request to grab the access token
        $data = [
            'client_id' => $this->getApiKey(),
            'client_secret' => $this->getApiSecretKey(),
            'code' => $code,
        ];


        $response = $this->getAPIServer()->request(
            'POST',
            '/admin/oauth/access_token',
            $data
        );

        if ($response['errors']) {
            throw new AuthenticationException('Failed to get access token.');
        } else {
            return $response['body'][$key];
        }
    }

    /**
     * @param $scopes
     * @param  string  $redirectUri
     * @param  string  $mode
     *
     * @return string
     * @throws ClientException
     */
    public function getAuthUrl($scopes, string $redirectUri, string $mode = 'offline'): string
    {
        if ($this->getApiKey() === null) {
            throw new ClientException('API key is missing');
        }

        if (is_array($scopes)) {
            $scopes = implode(',', $scopes);
        }

        $query = [
            'client_id' => $this->getApiKey(),
            'scope' => $scopes,
            'redirect_uri' => $redirectUri,
        ];
        if ($mode !== null && $mode !== 'offline') {
            $query['grant_options'] = [$mode];
        }

        return (string)$this
            ->getBaseUri()
            ->withPath('/admin/oauth/authorize')
            ->withQuery(
                preg_replace('/5B\d+5D/', '%5B%5D', http_build_query($query))
            );
    }

    public function setAPIKey(string $key): self
    {
        $this->apiKey = $key;
        return $this;
    }

    private function getAPIKey(): ?string
    {
        return $this->apiKey;
    }

    public function setAPISecretKey(string $key): self
    {
        $this->apiSecretKey = $key;
        return $this;
    }

    private function getAPISecretKey(): ?string
    {
        return $this->apiSecretKey;
    }

    /**
     * @return Uri
     * @throws ClientException
     */
    public function getBaseUri(): Uri
    {
        if ($this->getHost() === null) {
            // Shop is required
            throw new ClientException('Shopify domain missing for API calls');
        }

        return new Uri("https://{$this->getHost()}");
    }
}
