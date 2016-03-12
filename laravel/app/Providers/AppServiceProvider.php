<?php namespace App\Providers;

use Illuminate\Contracts\Logging\Log;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap any application services.
	 *
	 * @param \Illuminate\Contracts\Logging\Log|\Illuminate\Log\Writer $log
	 * @param \Monolog\Handler\HandlerInterface|\Monolog\Handler\SwiftMailerHandler $swiftMailHandler
	 */
	public function boot(Log $log) {

	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register() {

	}

}
