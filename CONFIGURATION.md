## Configuration

### 1. config/mass_mailer.php
* `mailing_list`: Set your default mailing list address (e.g. You may key in the alias address of the mailing list which you have created in Mailgun)
* `admin_email`: In case of any errors arising from running the queued job, an email notification will be sent to the administrator.
* `queue_name`: Optionally you may give a name to the queue that the jobs for mail delivery will be sent to. You can prioritize which queue to process first [Read more on Laravel's Queue  documentation](https://laravel.com/docs/5.3/queues)

### 2. .env
* Make sure the value of `QUEUE_DRIVER` is NOT `sync` as this will disable all queues. Any values other than `sync` is acceptable, e.g. `QUEUE_DRIVER=database`
* Make sure all of your mail settings have values. (`MAIL_DRIVER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`)
* If you are using Mailgun, do provide your `MAILGUN_DOMAIN` and `MAILGUN_SECRET` as well.

### 3. config/mail.php
* Make sure to fill in your name and email address in the `Global "From" Address` section