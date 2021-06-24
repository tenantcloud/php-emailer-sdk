<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
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
		$response = parse_response(file_get_contents('tests/Mock/Emails/SendEmailSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->emails()->send($this->data);

		self::assertEquals(Response::HTTP_CREATED, $response->getCode());
		self::assertEmpty($response->getData());

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}

	public function testStoreFailure(): void
	{
		$response = parse_response(file_get_contents('tests/Mock/Emails/SendEmailNotExistedContactFailure.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->lists()->store($this->data);

		self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getCode());
		self::assertEquals('The given data was invalid.', $response->getMessage());
		self::assertNotEmpty($response->getData());
		self::assertEquals('The receiver_email field is required.', Arr::get($response->getData(), 'errors.receiver_email.0'));
		self::assertEquals('The campaign_slug field is required.', Arr::get($response->getData(), 'errors.campaign_slug.0'));
	}
}
