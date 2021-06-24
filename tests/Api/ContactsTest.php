<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

/**
 * Class ContactsTest
 */
class ContactsTest extends TestCase
{
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
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->store($this->data);

		self::assertEquals(Response::HTTP_CREATED, $response->getCode());
		self::assertNotEmpty($response->getData());

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}

	public function testStoreEmailRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactEmailRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(Str::contains($response, 'errors'));
		$this->assertTrue(Str::contains($response, 'email'));
	}

	public function testStoreTimezoneRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactTimezoneRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(Str::contains($response, 'errors'));
		$this->assertTrue(Str::contains($response, 'timezone'));
	}

	public function testStoreCategoriesRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactCategoriesRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(Str::contains($response, 'errors'));
		$this->assertTrue(Str::contains($response, 'categories'));
	}

	public function testUpdateSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateContactSuccess.txt'));

		$this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testUpdateFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateContactFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testDeleteSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'DeleteContactSuccess.txt'));

		$this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
		$this->assertEquals($response->getBody()->getContents(), '{}');
	}

	protected function assertRequestData(array $data, array $requestParams): void
	{
		foreach ($data as $key => $value) {
			self::assertEquals($value, $requestParams[$key]);
		}
	}
}
