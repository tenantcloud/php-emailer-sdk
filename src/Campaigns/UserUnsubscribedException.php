<?php

namespace TenantCloud\Emailer\Campaigns;

use RuntimeException;

class UserUnsubscribedException extends RuntimeException
{
	public const CODE = 'campaign.unsubscribed_user';
}
