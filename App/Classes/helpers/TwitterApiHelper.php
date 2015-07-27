<?php
/**
 * Handles Twitter API calls
 */
include __DIR__.'/CurlHelper.php';

class TwitterApiHelper {

    public $curl_helper;

    private $consumer_key;
    private $consumer_secret;

    public $oauth_api;
    public $user_timeline_api;
    public $user_timeline_api_params;

    public function __construct() {
        $this->load_config();
        $this->curl_helper = new CurlHelper();
    }

    /**
     * Loads the config
     */
    public function load_config() {
        $configs = parse_ini_file(dirname(dirname(__DIR__)).'/Config/config.ini', TRUE);
        $this->consumer_key = $configs['twitter_app_credentials']['consumer_key'];
        $this->consumer_secret = $configs['twitter_app_credentials']['consumer_secret'];
        $this->oauth_api = $configs['twitter_api_info']['apponly_oauth_api'];
        $this->user_timeline_api = $configs['twitter_api_info']['user_timeline_api'];
        $this->user_timeline_api_params = $configs['user_timeline_api_params'];
    }

    /**
     * Calls Twitters oauth token
     *
     * @return token / False
     */
    public function getBearerToken() {
        $encoded_bearer_token_credential = base64_encode(urlencode($this->consumer_key) .":" . urlencode($this->consumer_secret));
        $headers = array('Authorization: Basic '.$encoded_bearer_token_credential,
                         'Content-Type: application/x-www-form-urlencoded; charset=UTF-8');
        $curl_options = array (
            CURLOPT_URL => $this->oauth_api,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => "grant_type=client_credentials",
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $response = $this->curl_helper->doCurl($curl_options);
        $data = json_decode($response);

        if ($data->token_type && $data->token_type == 'bearer') {
            return $data->access_token;
        }

        return false;
    }

    /**
     * Calls Twitter user_timeline API
     *
     * @return Object
     */
    public function getFeeds() {
        $token = $this->getBearerToken();
        if (!$token) {
            throw Exception("Unable to get bearer token.");
        }
        $url = $this->user_timeline_api.'?'.http_build_query($this->user_timeline_api_params);
        $headers = array ('Authorization: Bearer '.$token);
        $curl_options = array (
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );
        $response = $this->curl_helper->doCurl($curl_options);
        return json_decode($response);
    }
}
