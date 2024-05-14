<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\ContactsContract;

use function TenantCloud\GuzzleHelper\psr_response_to_json;

class Contacts implements ContactsContract
{
	private string $url = 'contacts';

	public function __construct(private Client $httpClient) {}

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
