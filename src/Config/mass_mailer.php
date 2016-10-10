<?php

    return [

        /*
        |--------------------------------------------------------------------------
        | Admin Email Address
        |--------------------------------------------------------------------------
        | 
        | Enter the email address you wished to be notified when there's an error in mass mail delivery        
        |
        */
        'admin_email' => '',

        /*
        |--------------------------------------------------------------------------
        | Queue Name (optional)
        |--------------------------------------------------------------------------
        | 
        | You may chose to specify a specific name for your queue, in which the jobs for mail delivery will be sent to.
        | This is especially useful if you wish to prioritize or segment how jobs are processed,
        | since Laravel queue worker allows you to specify which queues it should process by priority
        |
        | To run your job in your server: 
        |
        | If you have no interest in reading the output: 
        |       sudo nohup php artisan queue:work --daemon --queue=yourQueueName,default > /dev/null 2>&1 &
        | If you need to refer to the output: 
        |       sudo nohup php artisan queue:work --daemon --queue=yourQueueName,default > app/storage/logs/laravel.log &
        |
        */
        'queue_name' => '',    

        /*
        |--------------------------------------------------------------------------
        | Default Mailing List
        |--------------------------------------------------------------------------
        | 
        | This is the default mailing list name (e.g. it can represent the alias address in Mailgun )            
        |
        */
        'mailing_list' => 'massmailer',

        /*
        |--------------------------------------------------------------------------
        | Namespaces
        |--------------------------------------------------------------------------
        */
        'app_namespace' => 'App\\MassMailer\\',
        'package_namespace' => '\\Simmatrix\\MassMailer\\',

        /*
        |--------------------------------------------------------------------------
        | Attribute Path
        |--------------------------------------------------------------------------
        | 
        | The file path storing the Mass Mailer's Attribute class files that the user creates
        |
        */        
        'attribute_path' => 'MassMailer/Attributes',

        /*
        |--------------------------------------------------------------------------
        | Presenter Path
        |--------------------------------------------------------------------------
        | 
        | The file path storing the Mass Mailer's Presenter class files of which the purpose 
        | is to prepare and supply the parameters needed to be used in the blade view template
        |
        */
        'presenter_path' => 'MassMailer/Presenters',

        /*
        |--------------------------------------------------------------------------
        | Archive Directory
        |--------------------------------------------------------------------------
        | 
        | The directory that stores all the archive mass mails
        | This points to the /storage/app directory
        | Run `php artisan storage:link` (if you haven't) to create a symbolic link from public/storage to storage/app/public
        |
        */
        'archive_directory' => 'public/simmatrix/mass_mailer/mail_archive/',       

    ];