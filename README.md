# Portal Logger

This package adds a middleware which can log incoming requests to the log server. Besides that it can also record application error logs.

## Installation

You can install the package via composer:

```bash
composer require kejaksaan-dev/portal-logger
```

## Usage

1. Setup required ENV for portal logger

	```php
	// in `.env`

	PORTAL_LOGGER_URL={URL_LOGGING_SERVER}
	PORTAL_LOGGER_APP_ID={APPID_LOGGING_SERVER}
	PORTAL_LOGGER_APP_KEY={APPKEY_LOGGING_SERVER}
	PORTAL_LOGGER_REQUEST_TIMEOUT=10
	PORTAL_LARAVEL_VERSION=10 //if laravel 9 then value is 9
	
	```

2. Add providers in config app

	```php
	// in `config/app.php`

	// ...

	'providers' => [
		//...
		KejaksaanDev\PortalLogger\PortalLoggerServiceProvider::class,
	]
	
	```

3. Publish the config file with:

	```bash
	php artisan vendor:publish --provider="KejaksaanDev\PortalLogger\PortalLoggerServiceProvider" --tag="portal-logger-config" 
	```

	This is the contents of the published config file:

	```php
	return [

		'logger' => [
			'url' => env('PORTAL_LOGGER_URL',
			'https://api.kejaksaanri.id/portal-logger'),
			'path' => env('PORTAL_LOGGER_PATH', '/api/v1/logs/create'),
			'app_id' => env('PORTAL_LOGGER_APP_ID', 'DEFAULT'),
			'default_status_code' => env('PORTAL_LOGGER_STATUS_CODE', '200'),
			'is_ajax_log' => env('PORTAL_LOGGER_IS_AJAX_LOG', false),
			'user_guard' => env('PORTAL_LOGGER_USER_GUARD', 'web'),
			'user_id' => env('PORTAL_LOGGER_USER_ID_VAR', 'id'),
			'user_satker_var' => env('PORTAL_LOGGER_USER_SATKER_VAR', 'satker_id'),
			'user_nip_var' => env('PORTAL_LOGGER_USER_NIP_VAR', 'nip'),
			'user_username_var' => env('PORTAL_LOGGER_USER_USERNAME_VAR', 'username'),
			'user_name_var' => env('PORTAL_LOGGER_USER_NAME_VAR', 'name'),
			'request_timeout' => env('PORTAL_LOGGER_REQUEST_TIMEOUT', 30),
			'request_async' => env('PORTAL_LOGGER_REQUEST_ASYNC', true),
			'cache_user' => env('PORTAL_LOGGER_CACHE_USER', 86400),
			'is_enable' => env('PORTAL_LOGGER_IS_ENABLE', true)
		],
		'http_logger' => [
			'channel' => env('PORTAL_HTTP_LOGGER_CHANNEL', 'portal-logger'),
			'level' => env('PORTAL_HTTP_LOGGER_LEVEL', 'info'),
			'log_profile' => \KejaksaanDev\PortalLogger\Logs\PortalLogProfile::class,
			'log_writer' => \KejaksaanDev\PortalLogger\Logs\PortalLogWriter::class,
		]
	];
	```

4. You need add middleware in middleware groups in Kernel

	```php
	// in `app/Http/Kernel.php`

	protected $middlewareGroups = [
	    'web' => [
			    // ...
			    \KejaksaanDev\PortalLogger\Http\Middleware\PortalLogger::class
		]
	];
	```
	
	
5. Add new channel in logging config
	```php
		// in `config/logging.php`
		return [
			'channels' => [
			
				// ...
				
				'portal-logger' => [
					'driver' => 'monolog',
					'level' => 'debug',
					'handler' => env('PORTAL_LARAVEL_VERSION', 10) >= 10 
							? KejaksaanDev\PortalLogger\PortalLoggerHandler::class 
							: KejaksaanDev\PortalLogger\PortalLoggerL10BellowHandler::class
				],
			]
		]
		```

6. Add report method in Exception Handler
	```php
		// in `app\Exceptions\Handler.php`
		
		use Illuminate\Support\Facades\Log;
		use KejaksaanDev\PortalLogger\Http\Traits\WriterLogger;
		use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
		use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
		use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
		use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
		use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
		
		//...
		
		public  function  report(Throwable  $e)
		{
			try {
				$statusCode = 500;
				$className = get_class($e);

				if ($e instanceof BadRequestHttpException) {
					$statusCode = 400;
				} else  if ($e instanceof MethodNotAllowedHttpException) {
					$statusCode = 405;
				} else  if ($e instanceof NotFoundHttpException) {
					$statusCode = 404;
				} else  if ($e instanceof UnauthorizedHttpException) {
					$statusCode = 401;
				}

				WriterLogger::writeLog(request(), (string) $statusCode, 'error', null, $className, $e->getMessage());
			}
			catch (\Exception  $ex) {
				Log::error("Failed log exception handler cause : {$ex->getMessage()}");
			}
		}
		```


## Credits

- [Spatie](https://github.com/spatie)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.