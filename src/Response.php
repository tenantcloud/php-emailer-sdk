<?php

namespace TenantCloud\Emailer;

use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class Response
 */
class Response
{
	/** @var int */
	private $statusCode;

	/** @var array */
	private $data;

	/**
	 * Response constructor.
	 *
	 * @param $response
	 */
	public function __construct($response)
	{
		$this->statusCode = $response->getStatusCode();
		$this->data = json_decode($response->getBody()->getContents(), true) ?? [];
	}

	public function isSuccessful(): bool
	{
		return $this->statusCode >= HttpResponse::HTTP_OK && $this->statusCode < HttpResponse::HTTP_MULTIPLE_CHOICES;
	}

	public function getCode(): int
	{
		return $this->statusCode;
	}

	public function getErrors(): ?array
	{
		return Arr::get($this->data, 'errors');
	}

	public function getMessage(): ?string
	{
		return Arr::get($this->data, 'message');
	}

	public function getData(): array
	{
		return $this->data;
	}

	public function getAll(): array
	{
		return [
			'data'   => $this->data,
			'status' => $this->statusCode,
		];
	}
}
