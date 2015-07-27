<?php

class View {

    public function ajax_render($data) {
        print json_encode($data);
        exit;
    }
}
