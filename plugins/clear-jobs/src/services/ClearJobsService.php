<?php

namespace lexishamilton\craftclearjobs\services;

use Yii\base\Component;
use GuzzleHttp;

class ClearJobsService extends Component
{
    private $apiUrl = '';
    private $plugin;

    public function __construct($config = [])
    {
        $this->plugin = \lexishamilton\craftclearjobs\Plugin::getInstance();
        $this->apiUrl = $this->plugin->settings->clearApiUrl;
        parent::__construct($config);
    }

    public function getJobs(): array {

        $jobs = \Craft::$app->cache->getOrSet('clearJobs', function(){
            return $this->_apiRequest();
        }, 60);

        return $jobs;
    }

    protected function _apiRequest(): array {

        // GET request
        $client = new GuzzleHttp\Client();
        $response = $client->request('GET', $this->apiUrl);
        $responseBody = json_decode($response->getBody(), true);

        if ($responseBody) {
            return $responseBody;
        } else {

            // @TODO: if empty, we wouldn't want to cache it.
            return [];
        }
    }
}