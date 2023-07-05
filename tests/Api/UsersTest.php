<?php

namespace TenantCloud\Emailer\Tests\Api;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Message;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockApiKeyHttpClientHelper;

class UsersTest extends TestCase
{
	use AssertsHelper;

	private string $mockUrl = 'tests/Mock/Users/';

	private MockApiKeyHttpClientHelper $mockHelper;

	private array $data;

	private array $history = [];

	protected function setUp(): void
	{
		parent::setUp();

		$this->mockHelper = new MockApiKeyHttpClientHelper();
		$this->data = [
			'key1' => 'Key 1 value',
			'key2' => 'Key 2 value',
		];
	}

	public function testCreateSuccess(): void
	{
		$response = Message::parseResponse(file_get_contents('tests/Mock/Users/CreateUserSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->users()->store($this->data);

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);

		$this->assertSame(
			'{"api_key":"49f5f1a0-2d64-11ed-9749-a37d93b73fc8","mailing_lists":{"landlords":10000109,"tenants":10000110,"service_pros":10000111,"owners":10000112,"sub_admins":10000113,"basics":10000114,"starter":10000115,"starter_service_pro":10000116,"growth":10000117,"business":10000118,"basics_service_pro":10000119}}',
			json_encode($response)
		)
		;
	}

	public function testStoreFailure(): void
	{
		$this->expectException(RequestException::class);
		$this->expectExceptionMessage(
			<<<'MM'
				Client error: `POST public/users` resulted in a `422 Unprocessable Entity` response:
				{"message":"The given data was invalid.","errors":{"email":["The email field is required."]}}
				MM
		);

		$response = Message::parseResponse(file_get_contents('tests/Mock/Users/CreateUserValidationFailure.txt'));
		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$emailerClient->users()->store($this->data);
	}
}
