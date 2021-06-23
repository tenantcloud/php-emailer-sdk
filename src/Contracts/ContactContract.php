<?php

namespace TenantCloud\Emailer\Contracts;

use TenantCloud\Emailer\Response;

interface ContactContract
{
	public function delete(array $data): Response;
}
