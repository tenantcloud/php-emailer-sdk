<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\EmailsContract;

class Emails implements EmailsContract
{
	private string $url = 'public/emails';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function send(array $data): void
	{
		$this->httpClient->post($this->url, [
			'form_params' => $data,
		]);
	}
}
