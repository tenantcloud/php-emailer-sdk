<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\UsersContract;

use function TenantCloud\GuzzleHelper\psr_response_to_json;

class Users implements UsersContract
{
	private string $url = 'public/users';

	public function __construct(private Client $httpClient) {}

	public function store(array $data): array
	{
		$response = $this->httpClient->post($this->url, [
			'form_params' => $data,
		]);

		return psr_response_to_json($response);
	}
}
