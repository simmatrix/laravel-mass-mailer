# Mass Mailer Package for Laravel

Sending mass mails in your Laravel applications with ease. 

> This package integrates with [Bogardo Mailgun](https://github.com/Bogardo/Mailgun).

### Features Included
  - Sending mass emails to all or some of your subscribers
  - Pulling post-delivery report (e.g. number of clicks, opens, bounces, unsubscribes, complains, delivers, drops etc.)
  - Save and retrieve mass mail drafts
  - Retrieve the list of subscribers from 3rd party email service providers, such as Mailgun
  - Provide an API call for your frontend application to pull attributes (e.g. information on each of the HTML elements such as _Title_ field, _Message_ field etc.) for you to build up the User Interface. 
  - Parse all information coming from frontend application with ease (you only need to pass in the _$request_ object into a specific method that will parse and build up an appropriate object to be used within this package)

## Installation

Add this package to your project's `composer.json` file by running the following command

```
composer require simmatrix/laravel-mass-mailer
```

Add both of the service providers below to your `config/app.php` file, in the `providers` array.

```
Bogardo\Mailgun\MailgunServiceProvider::class,
Simmatrix\MassMailer\Providers\MassMailerServiceProvider::class,
```

Publish the config file and blade view template file to your application

```
php artisan vendor:publish --provider="Simmatrix\MassMailer\Providers\MassMailerServiceProvider"
```

## Configuration

Open `config/mass_mailer.php`, then set your default mailing list address (e.g. You may key in the alias address of the mailing list which you have created in Mailgun)

```
'mailing_list' => 'test',
```

## Usage

### Sending Mass Emails
In your controller, you would first need to pass in the `$request` parameter into `MassMailer::getParams()`, this will generate a digestible object that can be used in `MassMailer::send()`.

```
public function send(Request $request)
{
    MassMailer::send( MassMailer::getParams( $request ) );      
}
```

### Retrieving the Attributes

Here's the sample [JSON result](https://github.com/simmatrix/laravel-mass-mailer/blob/master/src/sample-attribute-endpoint-data.json) returned from calling the following method, which you can used to build up the User Interface of your frontend application.
```
return MassMailer::getAttributes();
```

### Getting Post-Delivery Report
Details such as the number of bounces, clicks, complains, deliveries, drops, opens, submits, unsubscribes can be obtained by calling this method.
```
return MassMailer::getReport();
```

### Saving Draft
You may save up the draft by passing the `$request` parameter into the `MassMailer::saveDraft()`.
```
MassMailer::saveDraft( $request );
```

### Retrieving Draft
You may retrieve all of the drafts by calling this method.
```
return MassMailer::getDrafts();
```
You may retrieve individual draft by passing an ID into the method below.
```
return MassMailer::getDraft( $id );
```

### Retrieving the Subscribers
You may retrieve all of the subscribers by calling this method.
```
return MassMailer::getSubscribers();
```

License
----
MIT