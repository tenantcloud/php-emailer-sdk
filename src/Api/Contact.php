<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\ContactContract;

class Contact implements ContactContract
{
	private string $url = 'contact';

	public function __construct(private Client $httpClient) {}

	public function delete(array $data): void
	{
		$this->httpClient->delete($this->url, [
			'form_params' => $data,
		]);
	}
}
