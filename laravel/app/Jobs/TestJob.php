<?php namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;

class TestJob extends Job implements ShouldQueue{

    protected $message;

    public function __construct($message) {
      $this->message = $message;
    }

    public function handle() {

        Cache::put('last-message', $this->message, 5);

    }

}
