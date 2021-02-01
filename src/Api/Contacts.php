<?php

namespace TenantCloud\Emailer\Api;

/**
 * Class Contacts
 */
class Contacts extends Configuration
{
	/** @var string */
	private $url = 'contacts';

	/**
	 * @return mixed
	 */
	public function store(array $data)
	{
		return self::$client->store($this->url, $data);
	}

	/**
	 * @return mixed
	 */
	public function update(int $id, array $data)
	{
		return self::$client->update("{$this->url}/{$id}", $data);
	}

	/**
	 * @return mixed
	 */
	public function delete(int $id)
	{
		return self::$client->destroy("{$this->url}/{$id}");
	}
}
