<?php

namespace TenantCloud\Emailer\Tests\Api;

use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

class ContactTest extends TestCase
{
	use AssertsHelper;

	private MockHttpClientHelper $mockHelper;

	private array $data;

	private array $history = [];

	protected function setUp(): void
	{
		parent::setUp();

		$this->mockHelper = new MockHttpClientHelper();
		$this->data = [
			'email' => 'tenantcloud@tenantcloud.com',
		];
	}

	public function testDeleteSuccess(): void
	{
		$response = Message::parseResponse(file_get_contents('tests/Mock/Contact/DeleteContactSuccess.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contact()->delete($this->data);

		/** @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}
}
