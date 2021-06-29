<?php

namespace TenantCloud\Emailer;

use TenantCloud\Emailer\Api\Campaigns;
use TenantCloud\Emailer\Api\Contact;
use TenantCloud\Emailer\Api\Contacts;
use TenantCloud\Emailer\Api\Emails;
use TenantCloud\Emailer\Api\Lists;

interface ClientContract
{
	public function lists(): Lists;

	public function contact(): Contact;

	public function contacts(): Contacts;

	public function emails(): Emails;

	public function campaigns(): Campaigns;
}
