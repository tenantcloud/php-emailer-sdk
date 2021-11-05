<?php

namespace TenantCloud\Emailer\Contracts;

interface ContactsContract
{
	/**
	 * @return mixed
	 */
	public function store(array $data);

	/**
	 * @return mixed
	 */
	public function update(int $id, array $data);

	/**
	 * @return mixed
	 */
	public function delete(int $id): void;
}
