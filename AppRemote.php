<?php
include "vendor/autoload.php";

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class AppRemote extends Thread
{
    private $client;
    private $request;

    public function __construct()
    {
        $this->client = new Client();
        $this->request = new Request("GET","https://www.facebook.com/search/str/marina+kiev/keywords_users");
    }

    public function run()
    {

        $pormise = $this->client->sendAsync($this->request)->then(function ($response){
           echo "<h1> Ответ от сервера получен! </h1></br>" . $response->getBody();
        });

        $pormise->wait();
    }

}