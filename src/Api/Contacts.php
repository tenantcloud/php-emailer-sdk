<?php

namespace TenantCloud\Emailer\Api;

use Exception;
use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\ContactsContract;
use TenantCloud\Emailer\Response;

/**
 * Class Contacts
 */
class Contacts implements ContactsContract
{
	private string $url = 'contacts';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function store(array $data): Response
	{
		try {
			$response = $this->httpClient->post($this->url, [
				'form_params' => $data,
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	public function update(int $id, array $data): Response
	{
		try {
			$response = $this->httpClient->put("{$this->url}/{$id}", [
				'form_params' => $data,
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	public function delete(int $id): Response
	{
		try {
			$response = $this->client->delete("{$this->url}/{$id}");
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}
}
