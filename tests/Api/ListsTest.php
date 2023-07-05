<?php

namespace TenantCloud\Emailer\Tests\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

class ListsTest extends TestCase
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

	public function testStoreSuccess(): void
	{
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'StoreListSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->lists()->store($this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}

	public function testStoreFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'MM'
				Client error: `POST lists` resulted in a `422 Unprocessable Entity` response:
				{"message":"The given data was invalid.","errors":{"name":["The name has already been taken."]}}
				MM
		);

		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'StoreListFailure.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->lists()->store($this->data);
	}

	public function testUpdateSuccess(): void
	{
		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'UpdateListSuccess.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->lists()->update($id, $this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');

		self::assertEquals("lists/{$id}", $request->getUri()->getPath());
	}

	public function testUpdateFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'MM'
				Client error: `PUT lists/1` resulted in a `422 Unprocessable Entity` response:
				{"message":"The given data was invalid.","errors":{"name":["The name may not be greater than 30 characters."]}}
				MM
		);

		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'UpdateListFailure.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->lists()->update($id, $this->data);
	}

	public function testDeleteSuccess(): void
	{
		$this->expectNotToPerformAssertions();

		$id = 1;
		$response = Message::parseResponse(file_get_contents($this->mockUrl . 'DeleteListSuccess.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->lists()->delete($id);
	}
}
