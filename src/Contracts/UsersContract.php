<?php

namespace TenantCloud\Emailer\Contracts;

interface UsersContract
{
	public function store(array $data): array;
}
