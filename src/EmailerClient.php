<?php

namespace TenantCloud\Emailer;

use Exception;
use GuzzleHttp\Client;
use TenantCloud\Emailer\Api\Configuration;

/**
 * Class EmailerClient
 * @package TenantCloud\Emailer
 */
class EmailerClient
{
	/**
	 * @var Client
	 */
	private $client;

	/**
	 * EmailerClient constructor.
	 * @param array $config
	 * @throws Exception
	 */
	public function __construct(array $config)
	{
		$url = array_get($config, 'url');
		$accessToken = array_get($config, 'accessToken');

		if (!$url || !$accessToken) {
			throw new Exception("'url' and 'accessToken' must be present in config array.");
		}

		$this->client = new Client([
			'base_uri' => $url,
			'headers' => [
				'Authorization' => 'Token ' . $accessToken,
				'Accept' => 'application/json'
			]
		]);

		Configuration::$client = $this;
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function lists()
	{
		return $this->makeApi('lists');
	}

	/**
	 * @return mixed
	 * @throws Exception
	 */
	public function contacts()
	{
		return $this->makeApi('contacts');
	}

	/**
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	private function makeApi(string $name)
	{
		$fileName = ucfirst($name);
		$class = '\\TenantCloud\\Emailer\\Api\\' . $fileName;

		if (!class_exists($class)) {
			throw new Exception("Class '$class' not found");
		}

		return new $class();
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return Response
	 * @throws Exception
	 */
	public function store(string $url, array $data): Response
	{
		try {
			$response = $this->client->post($url, [
				'form_params' => $data
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	/**
	 * @param string $url
	 * @param array $data
	 * @return Response
	 * @throws Exception
	 */
	public function update(string $url, array $data): Response
	{
		try {
			$response = $this->client->put($url, [
				'form_params' => $data
			]);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}

	/**
	 * @param string $url
	 * @return Response
	 * @throws Exception
	 */
	public function destroy(string $url): Response
	{
		try {
			$response = $this->client->delete($url);
		} catch (Exception $e) {
			$response = $e->getResponse();
		}

		return new Response($response);
	}
}