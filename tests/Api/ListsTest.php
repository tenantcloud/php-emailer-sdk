<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ListsTest
 * @package TenantCloud\Emailer\Tests\Api
 */
class ListsTest extends TestCase
{
	/**
	 * @var string
	 */
	private $mockUrl = 'tests/Mock/Lists/';

	public function testStoreSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreListSuccess.txt'));

		$this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testStoreFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'StoreListFailure.txt'));

		$this->assertEquals(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testUpdateSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateListSuccess.txt'));

		$this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testUpdateFailure()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'UpdateListSuccess.txt'));

		$this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
		$this->assertNotEmpty($response->getBody()->getContents());
	}

	public function testDeleteSuccess()
	{
		$response = parse_response(file_get_contents($this->mockUrl . 'DeleteListSuccess.txt'));

		$this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
		$this->assertEquals($response->getBody()->getContents(), '{}');
	}
}
