<?php

namespace TenantCloud\Emailer\Tests\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use TenantCloud\Emailer\EmailerClient;

class MockHttpClientHelper
{
	public function makeMock(Response $response, array &$historyContainer = []): Client
	{
		$history = Middleware::history($historyContainer);
		$mock = new MockHandler([
			new Response($response->getStatusCode(), $response->getHeaders(), $response->getBody()->getContents()),
		]);

		$handlerStack = HandlerStack::create($mock);
		$handlerStack->push($history);

		return new Client(['handler' => $handlerStack]);
	}

	public function makeEmailClient(Client $mockClient): EmailerClient
	{
		return new EmailerClient([
			'url'         => 'https://test.com',
			'accessToken' => '12345',
		], $mockClient);
	}

	public function makeEmailClientFromResponse(Response $response, array &$historyContainer = []): EmailerClient
	{
		return $this->makeEmailClient($this->makeMock($response, $historyContainer));
	}

	public function parseRequest(Request $request): array
	{
		parse_str($request->getBody()->getContents(), $output);

		return $output;
	}
}
