<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\ContactContract;

class Contact implements ContactContract
{
	private string $url = 'contact';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function delete(array $data): void
	{
		$this->httpClient->delete($this->url, [
			'form_params' => $data,
		]);
	}
}
