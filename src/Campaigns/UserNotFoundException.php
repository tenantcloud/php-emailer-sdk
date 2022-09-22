<?php

namespace TenantCloud\Emailer\Campaigns;

use RuntimeException;

class UserNotFoundException extends RuntimeException
{
	public const CODE = 'campaign.user_not_found';
}
