<?php

namespace TenantCloud\Emailer\Contracts;

interface ContactsContract
{
	public function store(array $data);

	public function update(int $id, array $data);

	/**
	 * @return mixed
	 */
	public function delete(int $id): void;
}
