<?php

namespace TenantCloud\Emailer\Api;

use Exception;
use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\CampaignsContract;
use TenantCloud\Emailer\Response;

class Campaigns implements CampaignsContract
{
	private string $url = 'public/campaigns';

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
}
