<?php

namespace TenantCloud\Emailer\Contracts;

interface EmailsContract
{
	/**
	 * @return mixed
	 */
	public function send(array $data): void;
}
