<?php

namespace TenantCloud\Emailer;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class Response
 * @package TenantCloud\Emailer
 */
class Response
{
	/**
	 * @var int
	 */
	private $statusCode;

	/**
	 * @var array
	 */
	private $data;

	/**
	 * Response constructor.
	 * @param $response
	 */
	public function __construct($response)
	{
		$this->statusCode = $response->getStatusCode();
		$this->data = json_decode($response->getBody()->getContents(), true) ?? [];
	}

	/**
	 * @return bool
	 */
	public function isSuccessful(): bool
	{
		return $this->statusCode >= HttpResponse::HTTP_OK && $this->statusCode < HttpResponse::HTTP_MULTIPLE_CHOICES;
	}

	/**
	 * @return int
	 */
	public function getCode(): int
	{
		return $this->statusCode;
	}

	/**
	 * @return array|null
	 */
	public function getErrors(): ?array
	{
		return Arr::get($this->data, 'errors');
	}

	/**
	 * @return null|string
	 */
	public function getMessage(): ?string
	{
		return Arr::get($this->data, 'message');
	}

	/**
	 * @return array
	 */
	public function getData(): array
	{
		return $this->data;
	}

	/**
	 * @return array
	 */
	public function getAll(): array
	{
		return [
			'data' => $this->data,
			'status' => $this->statusCode
		];
	}
}