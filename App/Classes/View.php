<?php
/**
 * Handles View part of the code
 */
class View {

    /**
     * Renders response for AJAX request
     *
     * @return json
     */
    public function ajax_render($data) {
        print json_encode($data);
        exit;
    }
}
