<?php

namespace TenantCloud\Emailer\Tests\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

/**
 * Class ContactsTest
 */
class ContactsTest extends TestCase
{
	use AssertsHelper;

	private string $mockUrl = 'tests/Mock/Contacts/';

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

	public function testStoreSuccess(): void
	{
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'StoreContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contacts()->store($this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}

	public function testStoreEmailRequiredFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'MM'
			Client error: `POST contacts` resulted in a `422 Unprocessable Entity` response:
			{"message":"The given data was invalid.","errors":{"email":["The email field is required."]}}
			MM
		);

		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'StoreContactEmailRequiredFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contacts()->store($this->data);
	}

	public function testUpdateSuccess(): void
	{
		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'UpdateContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contacts()->update($id, $this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');

		self::assertEquals("contacts/{$id}", $request->getUri()->getPath());
	}

	public function testUpdateFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'MM'
			Client error: `PUT contacts/1` resulted in a `422 Unprocessable Entity` response:
			{"message":"The given data was invalid.","errors":{"first_name":["The first name may not be greater than 30 characters." (truncated...)
			MM
		);

		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'UpdateContactFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contacts()->update($id, $this->data);
	}

	public function testDeleteSuccess(): void
	{
		$this->expectNotToPerformAssertions();

		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'DeleteContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->contacts()->delete($id);
	}
}
