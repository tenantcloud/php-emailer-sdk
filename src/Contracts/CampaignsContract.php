<?php

namespace TenantCloud\Emailer\Contracts;

use TenantCloud\Emailer\Response;

interface CampaignsContract
{
	public function store(array $data): Response;
}
