# Dine Safely API

REST API for the 
[dinesafely.org](https://dinesafely.org) app.

Built on the Lumen framework.

Uses Google Places API.

## Endpoints:

`GET` `/v1/places/nearby`- search places near a location

`GET` ```/v1/places/find``` - find places with a text query

`POST` `/v1/place/score` - queue a score

`PUT` `/v1/places/score/token/:token` - confirm and post a pending score

`GET` `/v1/cities` - auto-complete city names

## Implementation

This api starts from the baseline Lumen micro-framework https://lumen.laravel.com/docs/8.x

The primary changes from that baseline are listed below:

### Main code:

[app/Mail/ConfirmEmail.php](app/Mail/ConfirmEmail.php)  
[app/Mail/TestEmail.php](app/Mail/TestEmail.php)  
[app/Mail/ScoreReportEmail.php](app/Mail/ScoreReportEmail.php)  
[app/Constants/PlaceParamsConstants.php](app/Constants/PlaceParamsConstants.php)  
[app/Constants/ScoreConstants.php](app/Constants/ScoreConstants.php)  
[app/Providers/GooglePlacesApiServiceProvider.php](app/Providers/GooglePlacesApiServiceProvider.php)  
[app/Http/Middleware/CorsMiddleware.php](app/Http/Middleware/CorsMiddleware.php)  
[app/Http/Middleware/JsonRequestMiddleware.php](app/Http/Middleware/JsonRequestMiddleware.php)  
[app/Http/Controllers/Controller.php](app/Http/Controllers/Controller.php)  
[app/Http/Controllers/PlacesController.php](app/Http/Controllers/PlacesController.php)  
[app/Http/Controllers/CityController.php](app/Http/Controllers/CityController.php)  
[app/Http/Controllers/ScoreController.php](app/Http/Controllers/ScoreController.php)  
[app/Http/Controllers/EmailController.php](app/Http/Controllers/EmailController.php)  
[app/GooglePlacesApi/GooglePlacesApi.php](app/GooglePlacesApi/GooglePlacesApi.php)  

### Config:

[bootstrap/app.php](bootstrap/app.php)  
[routes/web.php](routes/web.php)  
[config/mail.php](config/mail.php)  
[phpunit.xml](phpunit.xml)  

### Email templates:

[resources/views/emails/score_report.blade.php](resources/views/emails/score_report.blade.php)  
[resources/views/emails/confirm.blade.php](resources/views/emails/confirm.blade.php)  
[resources/views/emails/confirm_plain.blade.php](resources/views/emails/confirm_plain.blade.php)  
[resources/views/emails/test_plain.blade.php](resources/views/emails/test_plain.blade.php)  
[resources/views/emails/score_report_plain.blade.php](resources/views/emails/score_report_plain.blade.php)
[resources/views/emails/test.blade.php](resources/views/emails/test.blade.php)  

### Tests

[tests/ScoresTest.php](tests/ScoresTest.php)  
[tests/EmailTest.php](tests/EmailTest.php)  

### Database

[db/bootstrap.sql](db/bootstrap.sql)  
[db/testdata.sql](db/testdata.sql)  
[db/cities.sql](db/cities.sql)  

### Configuration / getting started

In addition to the above an `.env`file is required but is not part of the repo for security reasons.
See the `.env-SAMPLE` file for the template of configuration required.

A Mysql database is required. Set up your test Mysql database and use the
`db/bootstrap.sql` script to initialize it. I don't use the Lumen / Laravel migrations - I think they are weird - I prefer pure SQL.
Configure the app to use your database using the `DB_*` settings of the `.env` file.

You will also need to populate the cities table from the `db/cities.sql` file, which contains US cities.
If you want to support cities outside the US, you will need to populate the cities table with data for those geographies. 

Run composer install:

```shell
composer install
```

Start the API:

```shell
php -S localhost:8000 -t public
```

Run tests:

```shell
./vendor/bin/phpunit
```

### Google Places API

The API uses the 
[Google Places API](https://developers.google.com/maps/documentation/places/web-service/overview)

The Google Places API interface is implemented in class `GooglePlacesApi` which is injected as a 
[Lumen Service Provider](https://lumen.laravel.com/docs/8.x/providers)

You will need to set up a Google Maps API key and configure that key in the `.env` settings.

Caching is used to minimize hits to the Google Places service.

### Emails

The API service uses email for validation in publishing scores.
Users provide an email address when posting their score and they receive an email with a one-time use link 
to complete their submission.

We use Sendgrid but you can configure any email service supported by the Lumen framework in the `.env` `MAIL_*` settings.

The email templates are in `resources/views/emails`.

You can set `DEBUG_EMAIL` in `.env` to test mail sending. Normally when posting a score, the
confirmation email is only sent if the environment is `production` but if
`DEBUG_EMAIL` is set, a confirmation email will be sent to that email address, regardless
of the email address of the user posting the score. This way you can test with whatever test email
addresses when posting, but the confirmation will always be sent to your `DEBUG_EMAIL` address.

See Lumen docs for more info: https://lumen.laravel.com/docs/8.x/mail
