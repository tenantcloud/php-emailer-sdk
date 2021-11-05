<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\ListsContract;
use function TenantCloud\GuzzleHelper\psr_response_to_json;

/**
 * Class Lists
 */
class Lists implements ListsContract
{
	private string $url = 'lists';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function store(array $data)
	{
		$response = $this->httpClient->post($this->url, [
			'form_params' => $data,
		]);

		return psr_response_to_json($response);
	}

	public function update(int $id, array $data)
	{
		$response = $this->httpClient->put("{$this->url}/{$id}", [
			'form_params' => $data,
		]);

		return psr_response_to_json($response);
	}

	public function delete(int $id): void
	{
		$this->httpClient->delete("{$this->url}/{$id}");
	}
}
