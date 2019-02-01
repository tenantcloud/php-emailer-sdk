<?php

namespace TenantCloud\Emailer\Api;

/**
 * Class Lists
 * @package TenantCloud\Emailer\Api
 */
class Lists extends Configuration
{
	/**
	 * @var string
	 */
	private $url =  'lists';

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function store(array $data)
	{
		return self::$client->store($this->url, $data);
	}

	/**
	 * @param int $id
	 * @param array $data
	 * @return mixed
	 */
	public function update(int $id, array $data)
	{
		return self::$client->update("$this->url/$id", $data);
	}

	/**
	 * @param int $id
	 * @return mixed
	 */
	public function delete(int $id)
	{
		return self::$client->destroy("$this->url/$id");
	}
}