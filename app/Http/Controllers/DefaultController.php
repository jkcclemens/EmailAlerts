<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class DefaultController extends BaseController {

    private $title = '';
    const BASE_TITLE = "EmailAlerts";

    public function __construct() {
        $this->shareTitle();
    }

    protected function setTitle($title) {
        $this->title = $title;
        $this->shareTitle();
    }

    private function shareTitle() {
        view()->share('title', $this->getTitle());
    }

    protected function getTitle() {
        return DefaultController::BASE_TITLE . (strlen($this->title) < 1 ? '' : ' :: ' . $this->title);
    }

}
