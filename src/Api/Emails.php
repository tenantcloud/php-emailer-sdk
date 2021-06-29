<?php

namespace TenantCloud\Emailer\Api;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use TenantCloud\Emailer\Contracts\EmailsContract;
use TenantCloud\Emailer\Response;

class Emails implements EmailsContract
{
	private string $url = 'public/emails';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function send(array $data): Response
	{
		try {
			$response = $this->httpClient->post($this->url, [
				'form_params' => $data,
			]);
		} catch (RequestException $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}
}
