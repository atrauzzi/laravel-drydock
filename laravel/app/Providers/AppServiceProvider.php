<?php namespace App\Providers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SwiftMailerHandler;
use Swift_Message as SwiftMessage;


class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @param \Illuminate\Contracts\Logging\Log|\Illuminate\Log\Writer $log
	 * @param \Monolog\Handler\HandlerInterface|\Monolog\Handler\SwiftMailerHandler $swiftMailHandler
	 */
	public function boot(Log $log, HandlerInterface $swiftMailHandler) {

		$log->getMonolog()->pushHandler($swiftMailHandler);

	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {

		if($this->app->environment() == 'local')
			$this->app->singleton('Monolog\Handler\HandlerInterface', function () {
				return new SwiftMailerHandler(
					$this->app->make('Illuminate\Contracts\Mail\Mailer')->getSwiftMailer(),
					SwiftMessage
						::newInstance(null)
						->addTo('log@localhost')
						->addFrom('noreply@localhost', 'Laravel Drydock')
						->setContentType('text/html')
				);
			})
		;

	}

}
