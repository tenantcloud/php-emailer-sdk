<?php

namespace TenantCloud\Emailer\Tests\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

class EmailsTest extends TestCase
{
	use AssertsHelper;

	private string $mockUrl = 'tests/Mock/Lists/';

	private MockHttpClientHelper $mockHelper;

	private array $data;

	private array $history = [];

	protected function setUp(): void
	{
		parent::setUp();

		$this->mockHelper = new MockHttpClientHelper();
		$this->data = [
			'key1' => 'Key 1 value',
			'key2' => 'Key 2 value',
		];
	}

	public function testSendSuccess(): void
	{
		$response = Message::parseResponse(file_get_contents('tests/Mock/Emails/SendEmailSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->emails()->send($this->data);

		/** @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}

	public function testStoreFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'EOD'
				Client error: `POST public/emails` resulted in a `422 Unprocessable Entity` response:
				{"message":"The given data was invalid.","errors":{"receiver_email":["The receiver_email field is required."],"campaign_ (truncated...)
				EOD
		);

		$response = Message::parseResponse(file_get_contents('tests/Mock/Emails/SendEmailNotExistedContactFailure.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->emails()->send($this->data);
	}
}
