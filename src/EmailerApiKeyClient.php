<?php

namespace TenantCloud\Emailer;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Arr;
use TenantCloud\Emailer\Api\Users;
use TenantCloud\GuzzleHelper\DumpRequestBody\HeaderObfuscator;
use TenantCloud\GuzzleHelper\DumpRequestBody\JsonObfuscator;
use TenantCloud\GuzzleHelper\GuzzleMiddleware;

class EmailerApiKeyClient
{
	private Client $client;

	public function __construct(array $config, ?Client $client = null)
	{
		$url = Arr::get($config, 'url');
		$apiKey = Arr::get($config, 'api_key');

		if (!$url || !$apiKey) {
			throw new Exception("'url' and 'api_key' must be present in config array.");
		}

		$stack = HandlerStack::create();

		$stack->unshift(GuzzleMiddleware::fullErrorResponseBody());
		$stack->unshift(GuzzleMiddleware::dumpRequestBody([
			new JsonObfuscator([
				'email',
			]),
			new HeaderObfuscator(['api-key']),
		]));

		$this->client = $client ?? new Client([
			'base_uri' => $url,
			'headers'  => [
				'api-key' => $apiKey,
				'Accept'  => 'application/json',
			],
			'handler' => $stack,
		]);
	}

	public function users(): Users
	{
		return new Users($this->client);
	}
}
