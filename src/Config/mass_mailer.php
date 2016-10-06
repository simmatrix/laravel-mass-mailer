<?php

    return [
    
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
        'app_namespace' => '\\App\\MassMailer\\',
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