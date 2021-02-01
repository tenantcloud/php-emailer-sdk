<?php

namespace TenantCloud\Emailer\Api;

/**
 * Class Contact
 */
class Contact extends Configuration
{
	/** @var string */
	private $url = 'contact';

	/**
	 * @return mixed
	 */
	public function delete(array $data)
	{
		return self::$client->destroy($this->url, $data);
	}
}
