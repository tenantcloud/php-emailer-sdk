<?php

namespace TenantCloud\Emailer\Api;

use GuzzleHttp\Client;
use TenantCloud\Emailer\Contracts\CampaignsContract;

use function TenantCloud\GuzzleHelper\psr_response_to_json;

class Campaigns implements CampaignsContract
{
	private string $url = 'public/campaigns';

	public function __construct(private Client $httpClient) {}

	public function store(array $data)
	{
		$response = $this->httpClient->post($this->url, [
			'form_params' => $data,
		]);

		return psr_response_to_json($response);
	}
}
