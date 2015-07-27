<?php

include __DIR__.'/helpers/TwitterApiHelper.php';

class Model {

    protected $api;

    public function __construct() {
        $this->api_helper = new TwitterApiHelper();
    }

    public function getFeeds() {
        $feeds = $this->api_helper->getFeeds();
        $return_data = array();
        foreach ($feeds as $feed_item) {
            $return_data[] = array (
                'username' => $feed_item->user->name,
                'screen_name' => $feed_item->user->screen_name,
                'user_profile' => $feed_item->user->profile_image_url,
                'text' => $feed_item->text,
                'retweet_count' => $feed_item->retweet_count
            );
        }

        return $return_data;
    }
}
