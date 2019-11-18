<?php

namespace TenantCloud\Emailer\Api;

/**
 * Class Contact
 * @package TenantCloud\Emailer\Api
 */
class Contact extends Configuration
{
	/**
	 * @var string
	 */
	private $url =  'contact';

	/**
	 * @param array $data
	 * @return mixed
	 */
	public function delete(array $data)
	{
		return self::$client->destroy($this->url, $data);
	}
}
