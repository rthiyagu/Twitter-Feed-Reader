<?php
/**
 * Manages Curl Request
 */
class CurlHelper {

    public $curl_info;
    public $curl_error;

    /**
     * Do CURL request to API
     *
     * @return JSON
     */
    public function doCurl($options) {
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        $this->curl_info = curl_getinfo($ch);
        $this->curl_error = curl_error($ch);
        curl_close($ch);
        return $response;
    }
}
