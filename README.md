# Mass Mailer Package for Laravel

Sending mass mails in your Laravel applications with ease. This is a wrapper for different mail service providers.

> Currently supports the sending of mass mails using Laravel's default Mail facade and [Mailgun's Official SDK](https://github.com/mailgun/mailgun-php)

> It is recommended to use third party mail service such as Mailgun instead of depending on Laravel's default Mail facade because with Laravel's Mail facade, in order to protect the privacy of all subscribers, the field "BCC" is being used instead of "TO"

### Features Included
  - Sending mass emails with queue, without bogging down your app's web request speed
  - Good for apps that need to send mass mails using different Mailgun domains (able to pass in custom domain to overwrite default domain)
  - Privacy of subscribers are properly handled ( without having their emails being exposed to one another in the "TO" field )
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

In the same file, do fill in the section `Admin Email Address`, `Queue Name (optional)` as well.

Make sure that in your `.env` file, the value of `QUEUE_DRIVER` is NOT `sync` as this will disable all queues. Any values other than `sync` is acceptable
```
QUEUE_DRIVER=database
```

Also do make sure that you have properly setup your `config/mail.php`, particularly the section `Global "From" Address`, as this will be used by this package when blasting off your mass mails to your recipients
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

Do go through [Laravel's Documentation on Queues](https://laravel.com/docs/5.3/queues) to understand more on how to do initial setup for your development environment.

## General Flow

1. Get the attributes with `MassMailer::getAttributes()` and construct your frontend application User Interface
2. When user submit the form from frontend, the backend will receive the `$request` parameter
3. Feed the `$request` parameter into the function `MassMailer::getParams()`, which would churn out an object of `MassMailerParams` instance, that can be used in subsequent functions.
4. Feed the `MassMailerParams` object into the function `MassMailer::send()` to blast off the mass mails to your subscribers.

## Usage

### 1(A). Retrieving the Attributes

Firstly you would need to pull the JSON data to build up your frontend User Interface. Here's the sample [JSON Result](https://github.com/simmatrix/laravel-mass-mailer/blob/master/src/sample-attribute-data.json) returned from calling the following method.
```
return MassMailer::getAttributes();
```

### 2(B). Creating Your Custom Attributes

Default attributes that comes with the package are:
  - "Subject" Field
  - "Title" Field
  - "Sender Name" Field
  - "Sender Email" Field
  - "Recipient List" Field
  - "Message Content" Field
  - "Apply Template" Option
  - "Send to All Subscribers" Option

> The purpose of creating an attirbute to represent your HTML fields is so that it can be easily parsed and read by this package when it returned from your frontend application.

If you would like to add an additional field in your frontend application, then you can generate it using the artisan command as below.

```
php artisan make:mass-mailer-attribute YourCustomAttribute
```

### 2(A). Sending Mass Emails
Then when the frontend application pass back the request parameters to the backend, in your controller, you would first need to pass in that `$request` parameter into `MassMailer::getParams()`, which will generate a digestible object called `MassMailerParams` that can be fed into the function `MassMailer::send()`.

```
public function send(Request $request)
{
    MassMailer::send( MassMailer::getParams( $request ) );      
}
```

### 2(B). Sending Mass Emails (With custom parameters to overwrite default values)

If you are working on an application that needs to blast mass mails using different Mailgun domains, or to different mailing list, or if you have created your own blade view template and need to use it for the mass mail, you can pass in custom parameters. 

Currently supports the overwriting of 3 custom parameters, `mailingList`, `mailgunDomain`, and `presenterClassName`

```
public function send(Request $request)
{
	$custom_params = MassMailer::createCustomParams([
		'mailingList' => 'xxx@xxx.com',
		'mailgunDomain' => 'xxx@xxx.com',
		'presenterClassName' => App\MassMailer\Presenters\YourCustomPresenter::class
	]);
    MassMailer::send( MassMailer::getParams( $request ), $custom_params );      
}
```

### 3. Creating Your Custom Presenters

In the previous step it mentioned about passing in your own blade view template to be used for your mass mail design layout. So how would you be doing it? The answer is you would need to create a Presenter class.

This class holds all the parameters that you intended to pass them to your blade view template, and you can also specify the name of the blade view template which you have created. You can easily generate it using the artisan command as below.
```
php artisan make:mass-mailer-presenter YourCustomPresenter
```

3(A). Specify the name of your blade view template. For example if you have placed your newly created template in your app's `resources/views/vendor/simmatrix/mass-mailer/lorem.blade.php`, then in the `getTemplate()` function, you can write the name as:
```
public function getTemplate()
{
	return 'vendor.simmatrix.mass-mailer.lorem';
}
```

3(B). Start pumping in all of the custom parameters that you wish to pass it to your blade view template!
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

License
----
MIT