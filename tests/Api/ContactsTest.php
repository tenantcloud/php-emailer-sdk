<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ContactsTest
 * @package TenantCloud\Emailer\Tests\Api
 */
class ContactsTest extends TestCase
{
	/**
	 * @var string
	 */
	private $mockUrl = '../Mock/Contacts/';

	public function testStoreSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactSuccess.txt'));

		$this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testStoreEmailRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactEmailRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(str_contains($response, 'errors'));
		$this->assertTrue(str_contains($response, 'email'));
	}

	public function testStoreTimezoneRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactTimezoneRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(str_contains($response, 'errors'));
		$this->assertTrue(str_contains($response, 'timezone'));
	}

	public function testStoreCategoriesRequiredFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreContactCategoriesRequiredFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());

		$response = $response->getBody()->getContents();
		$this->assertTrue(str_contains($response, 'errors'));
		$this->assertTrue(str_contains($response, 'categories'));
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
}
