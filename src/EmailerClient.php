<?php

namespace TenantCloud\Emailer;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use TenantCloud\Emailer\Api\Configuration;

/**
 * Class EmailerClient
 */
class EmailerClient
{
	/** @var Client */
	private $client;

	/**
	 * EmailerClient constructor.
	 */
	public function __construct(array $config)
	{
		$url = Arr::get($config, 'url');
		$accessToken = Arr::get($config, 'accessToken');

		if (!$url || !$accessToken) {
			throw new Exception("'url' and 'accessToken' must be present in config array.");
		}

		$this->client = new Client([
			'base_uri' => $url,
			'headers'  => [
				'Authorization' => 'Token ' . $accessToken,
				'Accept'        => 'application/json',
			],
		]);

		Configuration::$client = $this;
	}

	/**
	 * @return mixed
	 */
	public function lists()
	{
		return $this->makeApi('lists');
	}

	/**
	 * @return mixed
	 */
	public function contacts()
	{
		return $this->makeApi('contacts');
	}

	/**
	 * @return mixed
	 */
	public function contact()
	{
		return $this->makeApi('contact');
	}

	public function store(string $url, array $data): Response
	{
		try {
			$response = $this->client->post($url, [
				'form_params' => $data,
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	public function update(string $url, array $data): Response
	{
		try {
			$response = $this->client->put($url, [
				'form_params' => $data,
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	public function destroy(string $url, array $data = []): Response
	{
		try {
			$response = $this->client->delete($url, [
				'form_params' => $data,
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	/**
	 * @return mixed
	 */
	private function makeApi(string $name)
	{
		$fileName = ucfirst($name);
		$class = '\\TenantCloud\\Emailer\\Api\\' . $fileName;

		if (!class_exists($class)) {
			throw new Exception("Class '{$class}' not found");
		}

		return new $class();
	}
}
