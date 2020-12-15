<?php

namespace Reneiw\Azuma;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Reneiw\Azuma\Exceptions\ClientException;
use Reneiw\Hiei\HieiMiddleware;
use Reneiw\Hiei\HTTPService;
use Reneiw\Hiei\Middleware\ProxyGenerator;
use Reneiw\Hiei\Middleware\UserAgentGenerator;

abstract class API
{
    protected ?ClientInterface $client;
    protected ?HTTPService $APIServer;
    protected ?string $host;
    protected array $defaultOptions = [
        'timeout' => 60,
        'connect_timeout' => 60,
        'read_timeout' => 60,
        'handler' => null,
        'headers' => []
    ];

    protected array $apiDefaultOptions = [];

    /**
     * API constructor.
     *
     * @param  array  $data
     *
     */
    public function __construct(array $data)
    {
    }

    public function getAPIServer(): ?HTTPService
    {
        if (!$this->getAPIServer()) {
            $this->setAPIServer(new HTTPService($this->getClient(), $this->getAPIServerOptions()));
        }
        return $this->APIServer;
    }

    public function getAPIServerOptions(): array
    {
        return $this->apiDefaultOptions;
    }

    public function getClient(): ?ClientInterface
    {
        if (!$this->getClient()) {
            if (!$this->getHandler()) {
                $stack = HandlerStack::create();
                $stack->push(
                    HieiMiddleware::factory(
                        [
                            'max_retry_attempts' => 3,
                            'retry_on_status' => [429],
                        ]
                    )
                );
                $this->setHandler($stack);
            }
            $client = new Client($this->getOptions());
            $this->setClient($client);
        }
        return $this->client;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getOptions(): array
    {
        return $this->defaultOptions;
    }

    public function getHandler(): ?HandlerStack
    {
        return $this->defaultOptions['handler'];
    }

    public function setAPIServer(HTTPService $APIService): API
    {
        $this->APIServer = $APIService;
        return $this;
    }

    /** @noinspection PhpUnused */
    public function setAPIServerOptions(array $options): API
    {
        $this->apiDefaultOptions = array_replace($this->apiDefaultOptions, $options);
        return $this;
    }

    public function setClient(ClientInterface $client): API
    {
        $this->client = $client;
        return $this;
    }

    public function setHandler(?HandlerStack $stack = null)
    {
        $this->setOptions(['handler' => $stack]);
    }

    public function setOptions(array $data): API
    {
        $this->defaultOptions = array_replace($this->defaultOptions, $data);
        return $this;
    }

    public function setHost(string $host): API
    {
        $this->host = $host;
        $this->defaultOptions = array_replace($this->defaultOptions, ['base_uri' => "https://{$this->getHost()}"]);
        return $this;
    }

    public function request(string $method, string $uri, array $params = null, array $headers = [], bool $sync = true)
    {
        return $this->getAPIServer()->request($method, $uri, $params, $headers, $sync);
    }
}
