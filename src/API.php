<?php

namespace Reneiw\Azuma;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Reneiw\Azuma\Exceptions\ClientException;
use Reneiw\Hiei\HieiMiddleware;
use Reneiw\Hiei\HTTPService;

abstract class API
{
    protected ?ClientInterface $client = null;
    protected ?HTTPService $APIServer = null;
    protected ?string $host = null;
    public const DEFAULT_OPTIONS = [
        'timeout' => 60,
        'connect_timeout' => 60,
        'read_timeout' => 60,
        'handler' => null,
        'headers' => [],
    ];

    protected array $apiOptions = [];
    protected array $httpServiceOptions = [];

    /**
     * API constructor.
     *
     * @param  array  $data
     *
     * @throws ClientException
     */
    public function __construct(array $data)
    {
        $this->setOptions(self::DEFAULT_OPTIONS);

        if (empty($data['host'])) {
            throw new ClientException('client host is empty.');
        }
        $this->setHost($data['host']);
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
        $client = new Client($this->getOptions());
        $this->setClient($client);
        $this->setAPIServer(new HTTPService($this->getClient(), $this->getHttpServiceOptions()));
    }

    public function getAPIServer(): ?HTTPService
    {
        return $this->APIServer;
    }

    public function getAPIServerOptions(): array
    {
        return $this->apiOptions;
    }

    public function getClient(): ?ClientInterface
    {
        return $this->client;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function getOptions(): array
    {
        return $this->apiOptions;
    }

    /** @noinspection PhpUnused */
    public function getHandler(): ?HandlerStack
    {
        return $this->apiOptions['handler'];
    }

    public function getHttpServiceOptions(): array
    {
        return $this->httpServiceOptions;
    }

    public function setAPIServer(HTTPService $APIService): self
    {
        $this->APIServer = $APIService;
        return $this;
    }

    /** @noinspection PhpUnused */
    public function setAPIServerOptions(array $options): self
    {
        $this->apiOptions = array_replace_recursive($this->apiOptions, $options);
        return $this;
    }

    public function setClient(ClientInterface $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function setHandler(?HandlerStack $stack = null)
    {
        $this->setOptions(['handler' => $stack]);
    }

    public function setOptions(array $data): self
    {
        $this->apiOptions = array_replace_recursive($this->apiOptions, $data);
        return $this;
    }

    public function setHost(string $host): self
    {
        $this->host = $host;
        $this->apiOptions = array_replace_recursive($this->apiOptions, ['base_uri' => "https://{$this->getHost()}"]);
        return $this;
    }

    public function setHttpServiceOptions(array $data): self
    {
        $this->httpServiceOptions = array_replace_recursive($this->httpServiceOptions, $data);
        return $this;
    }

    public function request(string $method, string $uri, array $params = null, array $headers = [], bool $sync = true)
    {
        return $this->getAPIServer()->request($method, $uri, $params, $headers, $sync);
    }
}
