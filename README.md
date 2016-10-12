# Mass Mailer Package for Laravel

Sending mass mails in your Laravel applications with ease. This is a wrapper for different mail service providers.

> Currently supports the sending of mass mails using Laravel's default Mail facade and [Mailgun's Official SDK](https://github.com/mailgun/mailgun-php)

> It is recommended to use third party mail service such as Mailgun instead of depending on Laravel's default Mail facade because with Laravel's Mail facade, in order to protect the privacy of all subscribers, the field "BCC" is being used instead of "TO"

### Features Included
  - **Sending mass emails with queue**, without bogging down your app's web request speed
  - Good for apps that need to send mass mails using **multiple Mailgun domains** (able to pass in custom domain to overwrite default domain)
  - **Privacy** of subscribers are properly handled ( without having their emails being exposed to one another in the "TO" field )
  - Pulling **post-delivery report** (e.g. number of clicks, opens, bounces, unsubscribes, complains, delivers, drops etc.)
  - Save and retrieve mass mail **drafts**
  - Retrieve the **list of subscribers** from 3rd party email service providers, such as Mailgun
  - **Provide an API call for your frontend application to pull attributes** (e.g. information on each of the HTML elements such as _Title_ field, _Message_ field etc.) for you to build up the User Interface. 
  - **Parse all information coming from frontend application with ease** (you only need to pass in the _$request_ object into a specific method that will parse and build up an appropriate object to be used within this package)

## Installation

Add this package to your project's `composer.json` file by running the following command

```
composer require simmatrix/laravel-mass-mailer
```

Add both of the service providers below to your `config/app.php` file, in the `providers` array.

```
Simmatrix\MassMailer\Providers\MassMailerServiceProvider::class,
```

Publish the config file and blade view template file to your application

```
php artisan vendor:publish --provider="Simmatrix\MassMailer\Providers\MassMailerServiceProvider"
```

## Configuration

(1) Open `config/mass_mailer.php`, then set your default mailing list address (e.g. You may key in the alias address of the mailing list which you have created in Mailgun)

```
'mailing_list' => 'test',
```

In the same file, do key in the values for the keys `admin_email` and `queue_name` as well.

(2) Make sure that in your `.env` file, the value of `QUEUE_DRIVER` is NOT `sync` as this will disable all queues. Any values other than `sync` is acceptable
```
QUEUE_DRIVER=database
```
In the same file as well, do make sure your mail settings are properly setup
```
MAIL_DRIVER=xxx 
MAIL_HOST=xxx
MAIL_PORT=xxx
MAIL_USERNAME=xxx
MAIL_PASSWORD=xxx
MAIL_ENCRYPTION=TLS
```
If you are using Mailgun service provider, do provide your Mailgun information as well. Even if your app uses multiple Mailgun domains, you would need to provide one `MAILGUN_DOMAIN` as well (No worries, you can supply and overwrite different Mailgun domains later in your controller)
```
MAILGUN_DOMAIN=xxx@xxx.com
MAILGUN_SECRET=key-xxx
```

(3) Not to forget to properly setup your `config/mail.php` too, particularly the section `Global "From" Address`, as this will be used by this package when blasting off your mass mails to your recipients
```
'from' => [
    'address' => 'hello@example.com',
    'name' => 'Example',
],
```    

### Running Queue in your server

Run the following command to listen to and execute any incoming queued jobs
```
sudo nohup php artisan queue:work --daemon >> storage/logs/laravel.log &
```

Whenever you make changes to your code or deploy to your server, you would need to run
```
php artisan queue:restart
```

> Do go through [Laravel's Documentation on Queues](https://laravel.com/docs/5.3/queues) to understand more on how to do initial setup for your development environment. 

Here's a shortcut if you were to use the `database` as your queue driver. Make sure to run the following artisan command to migrate the required tables to handle your queued jobs.
```
php artisan queue:table
php artisan queue:failed-table
php artisan migrate
```

## General Flow

1. Get the attributes with `MassMailer::getAttributes()` and construct your frontend application User Interface
2. When user submit the form from frontend, the backend will receive the `$request` parameter
3. Feed the `$request` parameter into the function `MassMailer::getParams()`, which would churn out an object of `MassMailerParams` instance, that can be used in subsequent functions.
4. Feed the `MassMailerParams` object into the function `MassMailer::send()` to blast off the mass mails to your subscribers.

## Usage

### 1(i). Retrieving the Attributes

Firstly you would need to pull the JSON data to build up your frontend User Interface. Here's the sample [JSON Result](https://github.com/simmatrix/laravel-mass-mailer/blob/master/src/sample-attribute-data.json) returned from calling the following method.
```
return MassMailer::getAttributes();
```
Whereby each of the HTML field in your frontend is represented by an object, e.g. for the "Subject" field, the JSON object would look like this: 
```
{
  "Subject": {
    "label": "Subject",
    "name": "Subject",
    "params": false,
    "value": ""
  }
}
```
* `label`: A user-friendly label that can be easily understood by your end-users
* `params`: Any params that you wish to populate your HTML element (by default FALSE), e.g. returning an array of items for drop down list
* `value`: The default value to fill in your HTML element, your frontend app should also inject the user's input into this field
* `name`: The name of the attribute, your frontend application shouldn't modify this value at all cost, otherwise when it returned to the backend, this value wouldn't be able to be parsed and read properly.

### 1(ii). Creating Your Custom Attributes

These are the required attributes that comes with the package by default:
  - "Subject" Field
  - "Title" Field
  - "Sender Name" Field
  - "Sender Email" Field
  - "Recipient List" Field
  - "Message Content" Field
  - "Apply Template" Option
  - "Send to All Subscribers" Option

> The purpose of creating an attirbute to represent your HTML fields is so that it can be easily parsed and read by this package when it returned from your frontend application.

If you would like to add an additional HTML field in your frontend application, let's say adding a checkbox to allow the user to choose whether to include Instagram postings, then you would need to generate a new attribute using the artisan command as below.

```
php artisan make:mass-mailer-attribute InstagramPosting
```
After generating your new attribute, which would be located at `app/MassMailer/Attributes/` directory, proceed to open the file and add in some customization to suit your taste, such as changing the `label`, or adding `params`, or setting default `value` to your HTML field.
```
public function get()
{
    return parent::finalizeResult( $this, MassMailerAttributeParams::create([
        'label'  => 'Include Instagram Posting', 
        'params' => FALSE, 
        'value'  => FALSE,
    ]));
}
```
### 1(iii). [Advanced] Including Additional Data
Still using the same example above, let's say user tick on the checkbox as they want to include Instagram posting in the mass mail, when they submit the form back to the server, the backend will read the `getData()` function in your attribute file to call to the Instagram API to pull in nice images, which you can then loop through and display them in your blade view template!
```
public function getData()
{
    // Do all necessary steps to call to Instagram API to pull the postings, then return the result
    return $instagram_postings;
}
```

### 2(i). Sending Mass Emails
When the frontend application pass back the request parameters to the backend, in your controller, you would first need to pass in that `$request` parameter into `MassMailer::getParams()`, which will churn out a digestible object called `MassMailerParams` that can be fed into the function `MassMailer::send()`.

```
// YourController.php

public function send(Request $request)
{
    MassMailer::send( MassMailer::getParams( $request ) );      
}
```

### 2(ii). [Advanced] Sending Mass Emails (With custom mailer options to overwrite default config values)

If you are working on an application that needs to blast mass mails using different Mailgun domains, or to different mailing list, or if you have created your own blade view template and need to use it for the mass mail, you can pass in custom mailer options. 

Currently this supports the overwriting of 3 custom mailer options, namely
* `mailing_list`: Without providing this custom option, the package will read the data from the `mailing_list` key in your `app/config/mass_mailer.php` 
* `mailgun_domain`: Without providing this custom option, the package will read the data from the `MAILGUN_DOMAIN` key in your `.env` file
* `presenter`: If you create a new blade layout for your mass mail, then you would need to specify the name over here. By default it will use the presenter `Simmatrix\MassMailer\Presenters\DefaultMassMailerPresenter`, which uses the blade template in `resources/views/vendor/simatrix/mass-mailer/default.blade.php`.

```
// YourController.php

public function send(Request $request)
{
  $mailer_options = MassMailer::createMailerOptions([
    'mailing_list' => 'xxx',
    'mailgun_domain' => 'xxx@xxx.com',
    'presenter' => App\MassMailer\Presenters\YourCustomPresenter::class,
  ]);
    MassMailer::send( MassMailer::getParams( $request ), $mailer_options );      
}
```

### 3(i). Creating Your Custom Presenter

In the previous step it mentioned about passing in your own blade view template to be used for your mass mail design layout. So how would you be doing it? The answer is you would need to create a Presenter class.

> This class holds all the parameters that you intended to pass them to your blade view template, and you can also specify the name of the blade view template which you have created. 

You can easily generate it using the artisan command as below.
```
php artisan make:mass-mailer-presenter YourCustomPresenter
```

### 3(ii). Configuring Your Custom Presenter
Specify the name of your blade view template. For example if you have placed your newly created template in your app's `resources/views/vendor/simmatrix/mass-mailer/lorem.blade.php`, then in the `getTemplate()` function, you can write the name as:
```
public function getTemplate()
{
  return 'vendor.simmatrix.mass-mailer.lorem';
}
```

### 3(iii). Adding Data into Your Custom Presenter
Start pumping in all of the custom parameters that you wish to pass it to your blade view template!
```
private function setParameters( MassMailerParams $params )
  parent::setViewParameters([
    'lorem' => 'ipsum',
    'testing' => 'success'
  ]);
}
```

### 4. Getting Post-Delivery Report
Details such as the number of bounces, clicks, complains, deliveries, drops, opens, submits, unsubscribes can be obtained by calling this method.
```
return MassMailer::getReport();
```

### 5. Saving Draft
You may save up the draft by passing the `$request` parameter into the `MassMailer::saveDraft()`.
```
MassMailer::saveDraft( $request );
```

### 6. Retrieving Draft
You may retrieve all of the drafts by calling this method.
```
return MassMailer::getDrafts();
```
You may retrieve individual draft by passing an ID into the method below.
```
return MassMailer::getDraft( $id );
```

### 7. Retrieving the Subscribers
You may retrieve all of the subscribers by calling this method.
```
return MassMailer::getSubscribers();
```

Acknowledgements
----
Thanks [Chalcedonyt](https://github.com/chalcedonyt) for the feedback given and for the useful [Value Object](https://github.com/chalcedonyt/laravel-valueobject) Laravel package

License
----
MIT