<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
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

	public function testStoreEmailRequiredFailure(): void
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactEmailRequiredFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->store($this->data);

		self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getCode());
		self::assertEquals('The given data was invalid.', $response->getMessage());
		self::assertEquals('The email field is required.', Arr::get($response->getData(), 'errors.email.0'));
	}

	public function testStoreTimezoneRequiredFailure(): void
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactTimezoneRequiredFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->store($this->data);

		self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getCode());
		self::assertEquals('The given data was invalid.', $response->getMessage());
		self::assertEquals('The timezone field is required.', Arr::get($response->getData(), 'errors.timezone.0'));
	}

	public function testStoreCategoriesRequiredFailure(): void
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactCategoriesRequiredFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->store($this->data);

		self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getCode());
		self::assertEquals('The given data was invalid.', $response->getMessage());
		self::assertEquals('The categories field is required.', Arr::get($response->getData(), 'errors.categories.0'));
	}

	public function testUpdateSuccess(): void
	{
		$id = 1;
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->update($id, $this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');

		self::assertEquals(Response::HTTP_OK, $response->getCode());
		self::assertNotEmpty($response->getData());
		self::assertEquals("contacts/{$id}", $request->getUri()->getPath());
	}

	public function testUpdateFailure(): void
	{
		$id = 1;
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateContactFailure.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->update($id, $this->data);

		self::assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getCode());
		self::assertEquals('The given data was invalid.', $response->getMessage());
		self::assertNotEmpty($response->getData());
	}

	public function testDeleteSuccess(): void
	{
		$id = 1;
		$response = parse_response(file_get_contents($this->mockUrl . 'DeleteContactSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->contacts()->delete($id);

		self::assertEquals(Response::HTTP_NO_CONTENT, $response->getCode());
		self::assertEquals([], $response->getData());
	}
}
