# Mass Mailer Package for Laravel

Sending mass mails in your Laravel applications with ease. This is a wrapper for different mail service providers.

> Currently supports the sending of mass mails using Laravel's default Mail facade and [Mailgun's Official SDK](https://github.com/mailgun/mailgun-php)

> It is recommended to use third party mail service such as Mailgun instead of depending on Laravel's default Mail facade because with Laravel's Mail facade, in order to protect the privacy of all subscribers, the field "BCC" is being used instead of "TO"

### Features Included
  - **Sending mass emails with queue**: Without bogging down your app's web request speed
  - **Multiple Mailgun domains**: Good for apps that need to send mass mails using multiple Mailgun domains
  - **Privacy properly handled**: Subscribers' emails would not be exposed to one another
  - **Easily integrate with your frontend application**: You can pull JSON data, populate it, then pass it back to the backend to be processed 
  - **Post-delivery Report**: Easily fetch the number of clicks, opens, bounces, unsubscribes, complains, delivers, drops etc.
  - **Draft**: Save and retrieve mass mail drafts
  - **Subscriber Listings**: Retrieve the list of subscribers from 3rd party email service providers, such as Mailgun
   

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

[How to do basic config](https://github.com/simmatrix/laravel-mass-mailer/blob/master/CONFIGURATION.md)

### Running Queue in your server

[How to do basic setup](https://github.com/simmatrix/laravel-mass-mailer/blob/master/RUNNING_QUEUES.md)

## Usage

### 1(i). Sending the Attributes to the Frontend

> Attributes represents the HTML elements

Retrieve the key-value pairs using the command below, then this is what your frotnend application will receive: [Sample Response](https://github.com/simmatrix/laravel-mass-mailer/blob/master/src/sample-attribute-data.json)
```
return MassMailer::getAttributes();
```
Each HTML element in your frontend is represented by a key-value object.
```
{ "SenderEmail": "hello@example.com" }
```
> The purpose of creating an attirbute to represent your HTML element is so that it can be easily parsed when it returned from your frontend application.

### 1(ii). Creating Your Own Attributes

These are the required attributes that comes with the package _by default_:
  - "Subject" Field
  - "Title" Field
  - "Sender Name" Field
  - "Sender Email" Field
  - "Recipient List" Field
  - "Message Content" Field
  - "Apply Template" Option
  - "Send to All Subscribers" Option

To add additional fields:
```
php artisan make:mass-mailer-attribute YourNewFieldName
```
Generated file is located at `app/MassMailer/Attributes/` directory
### 1(iii). [Advanced] Fetching Additional Data
If you have some extra logic to add to your attribute, write it in the `getValue()` function within your attribute file.
```
public function getValue()
{
    // Do all necessary steps to call to Instagram API to pull the postings, then return the result
    return $instagram_postings;
}
```

### 1(iv). Reading the Value from the Attribute

You can easily read the user's input from the attribute with:
```
$has_instagram = MassMailerAttribute::getUserInput( $params, 'Instagram' )
```
If you did `Step 1(iii)` above, then you can easily get the value that you have retrieved using:
```
$instagram_posts = MassMailerAttribute::getInternallyPulledData( $params, 'Instagram' )
```

### 2(i). Sending Mass Emails
In your controller, pass in that `$request` parameter into `MassMailer::getParams()`, which will churn out a digestible object called `MassMailerParams` that can be fed into the function `MassMailer::send()`.

```
// YourController.php

public function send(Request $request)
{
    MassMailer::send( MassMailer::getParams( $request ) );      
}
```

### 2(ii). [Advanced] Sending Mass Emails

If you have created your own blade view template and need to use it for the mass mail, or if you need to blast mass mails using different Mailgun domains, or to different mailing list, you can pass in the **mailer options**. 

Currently this supports the overwriting of 3 custom mailer options, namely
* `presenter`: Specify the class name of the _Presenter_ that handles your new blade template here. 
* `mailing_list`: Without this, the backend will read the data from the `mailing_list` key in your `app/config/mass_mailer.php` 
* `mailgun_domain`: Without this, the backend will read the data from the `MAILGUN_DOMAIN` key in your `.env` file

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
You need this when you want to use your own nice newsletter layout design.
> This class holds all the parameters that you intended to pass them to your blade view template, and the name of the blade view template itself

To generate:
```
php artisan make:mass-mailer-presenter YourCustomPresenter
```
The console will prompt you to enter the name of your template. 
* **If you place your template file in**: `resources/views/vendor/simmatrix/mass-mailer/lorem.blade.php`
* **Then key in this into the console**: `vendor.simmatrix.mass-mailer.lorem`
* **A new presenter will be generated at**: `app/MassMailer/Presenters/YourCustomPresenter.php`

### 3(ii). Adding Data into Your Custom Presenter
Open the file and start writing those parameters that you wish to pass to your blade view template in the `setParameters()` function.
```
private function setParameters( MassMailerParams $params )
  parent::setViewParameters([
    'lorem' => 'ipsum',
    'testing' => 'success'
  ]);
}
```
In your newsletter HTML layout, you can display the value in such a way:
```
<span>{{ $lorem }}</span>
<div>{{ $testing }}</div>
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