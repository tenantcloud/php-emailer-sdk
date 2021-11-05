<?php

namespace TenantCloud\Emailer\Contracts;

interface ContactContract
{
	public function delete(array $data): void;
}
