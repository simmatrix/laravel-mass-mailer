<?php

namespace Simmatrix\MassMailer\Factories;

use \Simmatrix\MassMailer\ValueObjects\MassMailerParams;

class MassMailerFactory
{
    /**
     * To create the mailer class instance
     *
     * @return Object The intended instance to be created
     */
    public static function createMailer()
    {
        $mail_driver = ucfirst( camel_case( config('mail.driver') ) );
        $file_path = sprintf("%s/../Mailers/%sMailer.php", __DIR__, $mail_driver);
        $mailer_choice = file_exists( $file_path ) ? $mail_driver : 'Default';
        $class_name = config('mass_mailer.package_namespace') . 'Mailers\\' . $mailer_choice . 'Mailer';

        return self::create( $class_name );
    }

    /**
     * To create the mailing list class instance
     *
     * @return Object The intended instance to be created
     */
    public static function createMailingListManager()
    {
        $mail_driver = ucfirst( camel_case( config('mail.driver') ) );
        $file_path = sprintf("%s/../MailingListManager/%sMailingListManager.php", __DIR__, $mail_driver);
        $mailer_choice = file_exists( $file_path ) ? $mail_driver : 'Default';
        $class_name = config('mass_mailer.package_namespace') . 'MailingListManager\\' . $mailer_choice . 'MailingListManager';

        return self::create( $class_name );
    }

    /**
     * To create the report manager class instance
     *
     * @return Object The intended instance to be created
     */
    public static function createReportManager()
    {
        $mail_driver = ucfirst( camel_case( config('mail.driver') ) );
        $file_path = sprintf("%s/../ReportManager/%sReportManager.php", __DIR__, $mail_driver);
        $mailer_choice = file_exists( $file_path ) ? $mail_driver : 'Default';
        $class_name = config('mass_mailer.package_namespace') . 'ReportManager\\' . $mailer_choice . 'ReportManager';

        return self::create( $class_name );
    }

    /**
     * To create the presenter class instance that will serve all of the extra parameters needed in the blade template
     *
     * @return Object The intended instance to be created
     */
    public static function createPresenter( string $class_name, MassMailerParams $params ) 
    {
        return self::create( $class_name, ['params' => $params] );
    }

    /**
     * To create the attribute class instance
     * 
     * @param String $class_name
     * @param Array $key_values
     *
     * @return Object The intended instance to be created
     */
    public static function createAttribute( string $class_name, array $key_values = [] )
    {
        $instance = self::create( $class_name );

        foreach( $key_values as $key => $value ) {
            $instance -> $key = $value;
        }

        return $instance;
    }

    /**
     * To create the required class instance
     * 
     * @param String $class_name The class name of the intended instance to be created
     *
     * @return Object The intended instance to be created
     */
    private static function create( string $class_name, $arguments = NULL )
    {
        if ( strpos( $class_name, 'Abstract' ) === FALSE ) {
            $class = new \ReflectionClass( $class_name );
            return $arguments ? $class -> newInstanceArgs( $arguments ) : $class -> newInstance();
        }
    }
}