<p align="center">
    <img src="https://cloud.xm-cdn.com/static/xm/common/logos/XMLogo-2021_homepage.svg" alt="XM">
</p>

# XM test task

## Some notes, please have a look:
 - Built with Laravel 10.
 - Dependencies are in the composer file.
 - Error messages are not localized due to absence of need.
 - Emails are not sent to the Email provided. Instead, 'Mailer' is set to "log" option so
emails (their body, subject, additional data) are put to Laravel log file (/storage/logs/laravel.log).
In such a way we emulate mailing functionality without actual email sending. (Setting up proper mailing driver will change the behaviour).
 - Basic tests are in /tests/Feature.

### Running:
 - No Database needed.
 - run 'composer install' command to get all the app dependencies.
 - make .env file by copying .env.example
 - run 'php artisan key:generate' 
 - run 'php artisan serve' command and the app should be available on http://127.0.0.1:8000.

