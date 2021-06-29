<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use TenantCloud\Emailer\Contracts\ContactContract;
use TenantCloud\Emailer\Response;

/**
 * Class Contact
 */
class Contact implements ContactContract
{
	private string $url = 'contact';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function delete(array $data): Response
	{
		try {
			$response = $this->httpClient->delete($this->url, [
				'form_params' => $data,
			]);
		} catch (RequestException $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}
}
