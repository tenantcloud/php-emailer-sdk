<?php

namespace TenantCloud\Emailer\Contracts;

use TenantCloud\Emailer\Response;

interface EmailsContract
{
	public function send(array $data): Response;
}
