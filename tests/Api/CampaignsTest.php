<?php

namespace TenantCloud\Emailer\Tests\Api;

use function GuzzleHttp\Psr7\parse_response;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use TenantCloud\Emailer\Tests\Helpers\AssertsHelper;
use TenantCloud\Emailer\Tests\Helpers\MockHttpClientHelper;

class CampaignsTest extends TestCase
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
		$response = parse_response(file_get_contents('tests/Mock/Campaigns/StoreCampaignSuccess.txt'));

		$emailerClient = $this->mockHelper->makeEmailClientFromResponse($response, $this->history);
		$response = $emailerClient->campaigns()->store($this->data);

		self::assertEquals(Response::HTTP_CREATED, $response->getCode());
		self::assertNotEmpty($response->getData());

		/* @var Request $request */
		$request = Arr::get(Arr::first($this->history), 'request');
		$params = $this->mockHelper->parseRequest($request);

		$this->assertRequestData($this->data, $params);
	}
}
