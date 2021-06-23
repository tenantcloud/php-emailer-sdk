<?php

namespace TenantCloud\Emailer\Contracts;

use TenantCloud\Emailer\Response;

interface ContactsContract
{
	public function store(array $data): Response;

	public function update(int $id, array $data): Response;

	public function delete(int $id): Response;
}
