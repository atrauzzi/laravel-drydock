<?php namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
//
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class TestJob extends Job implements ShouldQueue {

	use InteractsWithQueue;


	protected $message;

	protected $email;

	public function __construct($message, $email) {
		$this->message = $message;
		$this->email = $email;
	}

	public function handle() {

		if($this->attempts() > 5)
			$this->delete();

		Mail::send('test_email', ['note' => $this->message], function (Message $message) {
			$message->from('laravel.drydock@localhost');
			$message->to($this->email, 'Development User');
			$message->subject('Test job completed!');
		});

		Cache::put('last-message', $this->message, 5);

	}

}
