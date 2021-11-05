<?php

namespace TenantCloud\Emailer\Contracts;

interface CampaignsContract
{
	/**
	 * @return mixed
	 */
	public function store(array $data);
}
