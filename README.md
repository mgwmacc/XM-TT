<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

# XM test task

## Some notes, please have a look:
 - Built with Laravel 10
 - Dependencies are in the composer file
 - Error messages are not localized due to absence of need
 - Emails are not sent to the Email provided. Instead 'Mailer' is set to "log" so
emails (their body, subject, additional data) are put to Laravel log file (/storage/logs/laravel.log).
In such a way we emulate mailing functionality without actual email sending. (Setting up proper mailing driver will change the behaviour).

### Running:
 - No Database needed
 - run 'composer install' command to get all the app dependencies.
 - run 'php artisan serve' command and the app should be available on http://127.0.0.1:8000.

