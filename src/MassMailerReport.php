<?php

namespace Simmatrix\MassMailer;

use Simmatrix\MassMailer\Factories\MassMailerFactory;

class MassMailerReport
{
    /**
     * Showing a list of delivered mass mails, together with a preview link and the delivery statistics
     */
    public static function get()
    {
        $manager = MassMailerFactory::createReportManager();
        return $manager::get();
    }
}