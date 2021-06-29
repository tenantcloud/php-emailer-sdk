<?php

namespace TenantCloud\Emailer\Tests\Helpers;

trait AssertsHelper
{
	protected function assertRequestData(array $data, array $requestParams): void
	{
		foreach ($data as $key => $value) {
			self::assertEquals($value, $requestParams[$key]);
		}
	}
}
