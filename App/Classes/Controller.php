<?php

include __DIR__.'/Model.php';
include __DIR__.'/View.php';

class Controller {

    public $model;

    public function __construct() {
        $this->model = new Model();
        $this->view = new View();
    }

    public function getFeedsAction() {
        $this->view->ajax_render($this->model->getFeeds());
    }
}
