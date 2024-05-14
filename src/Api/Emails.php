<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\EmailsContract;

class Emails implements EmailsContract
{
	private string $url = 'public/emails';

	public function __construct(private Client $httpClient) {}

	public function send(array $data): void
	{
		$this->httpClient->post($this->url, [
			'form_params' => $data,
		]);
	}
}
