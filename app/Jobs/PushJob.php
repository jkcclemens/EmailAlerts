<?php namespace App\Jobs;

use Pushbullet\Pushbullet;

class PushJob extends Job {

    private $apiKey, $title;

    public function __construct($apiKey, $title) {
        $this->apiKey = $apiKey;
        $this->title = $title;
    }

    public function handle() {
        $push = new Pushbullet($this->apiKey);
        $push->allDevices()->pushNote($this->title, '');
    }

}
