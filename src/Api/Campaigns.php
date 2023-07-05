<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\CampaignsContract;

use function TenantCloud\GuzzleHelper\psr_response_to_json;

class Campaigns implements CampaignsContract
{
	private string $url = 'public/campaigns';

	private Client $httpClient;

	public function __construct(Client $httpClient)
	{
		$this->httpClient = $httpClient;
	}

	public function store(array $data)
	{
		$response = $this->httpClient->post($this->url, [
			'form_params' => $data,
		]);

		return psr_response_to_json($response);
	}
}
