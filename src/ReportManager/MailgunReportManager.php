<?php

namespace Simmatrix\MassMailer\ReportManager;

use Simmatrix\MassMailer\Interfaces\MassMailerReportManagerInterface;
use Mailgun\Mailgun as MailgunCore;

class MailgunReportManager implements MassMailerReportManagerInterface
{
	/**
	 * To get the delivery report
	 *
	 * @return String A JSON response [{"bounced_count":0,"clicked_count":0,"complained_count":0,"created_at":"Wed, 05 Oct 2016 16:22:39 GMT","delivered_count":1,"dropped_count":0,"id":"e679b660a483f1262170f73f1477ec02","name":"[2016-10-05 16:22:37] Awesome Day Begins With Awesome Thoughts","opened_count":1,"submitted_count":2,"unsubscribed_count":0},{"bounced_count":0,"clicked_count":0,"complained_count":0,"created_at":"Thu, 16 Apr 2015 03:18:25 GMT","delivered_count":0,"dropped_count":0,"id":"20150416051824_hik2_n24f_fzjg","name":"[2015-04-16 05:18:24] Test Campaign","opened_count":0,"submitted_count":1,"unsubscribed_count":0},{"bounced_count":0,"clicked_count":0,"complained_count":0,"created_at":"Wed, 19 Mar 2014 10:11:04 GMT","delivered_count":1,"dropped_count":0,"id":"20140319181103_m479_v2qd_3dop","name":"[2014-03-19 18:11:03] Test Campaign","opened_count":1,"submitted_count":2,"unsubscribed_count":1}]
	 */
	public static function get()
	{
		$mail = new MailgunCore( env( 'MAILGUN_SECRET' ) );
        $response = $mail -> get( env('MAILGUN_DOMAIN') . '/campaigns' );
        return json_encode($response -> http_response_body -> items);
	}
}