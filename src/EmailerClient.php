<?php

namespace TenantCloud\Emailer;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TenantCloud\Emailer\Api\Campaigns;
use TenantCloud\Emailer\Api\Contact;
use TenantCloud\Emailer\Api\Contacts;
use TenantCloud\Emailer\Api\Emails;
use TenantCloud\Emailer\Api\Lists;

/**
 * Class EmailerClient
 */
class EmailerClient implements ClientContract
{
	private Client $client;

	public function __construct(array $config, Client $client = null)
	{
		$url = Arr::get($config, 'url');
		$accessToken = Arr::get($config, 'accessToken');

		if (!$url || !$accessToken) {
			throw new Exception("'url' and 'accessToken' must be present in config array.");
		}

		$this->client = $client ?? new Client([
			'base_uri' => $url,
			'headers'  => [
				'Authorization' => 'Token ' . $accessToken,
				'Accept'        => 'application/json',
			],
		]);
	}

	/**
	 * @return mixed
	 */
	public function lists(): Lists
	{
		return new Lists($this->client);
	}

	/**
	 * @return mixed
	 */
	public function contacts(): Contacts
	{
		return new Contacts($this->client);
	}

	/**
	 * @return mixed
	 */
	public function contact(): Contact
	{
		return new Contact($this->client);
	}

	public function emails(): Emails
	{
		return new Emails($this->client);
	}

	public function campaigns(): Campaigns
	{
		return new Campaigns($this->client);
	}
}
