<?php

namespace TenantCloud\Emailer\Api;

use Exception;
use GuzzleHttp\Client;
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
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}
}
