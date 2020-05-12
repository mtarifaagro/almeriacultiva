<?php

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
    header("Content-Type: application/json");

    $count = $_GET['count'];

    echo showTweets("almeriacultiva", $count);
    
    function showTweets($query,$num_tweets){

        ini_set('display_errors', 1);
        require_once('TwitterAPIExchange.php');

 
        /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
        $settings = array(
            'oauth_access_token' => "1156230160481423360-7G5hQ0Gd8h1SlkVLm6QKWEyKe3M6Cd",
            'oauth_access_token_secret' => "cBJYnN4jP6hN0ltNTZoAezyLamjG4Cmn03X48vZbzGpV9",
            'consumer_key' => "kEie9aY601EYlqmO60rOzsPwy",
            'consumer_secret' => "c4vsSSBKfjFTOKq2XKkrU9tDm6Psf02G4XEySfWzvbl0wcRxQH"
        );

        if($num_tweets>100) $num_tweets = 100;
        else if ($num_tweets<5) $num_tweets = 5;

        /** https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-home_timeline */
        $url = "https://api.twitter.com/1.1/statuses/home_timeline.json";
        $getfield = '&count='.$num_tweets.'&tweet_mode=extended';

        $requestMethod = 'GET';

        $twitter = new TwitterAPIExchange($settings);

        $json =  $twitter->setGetfield($getfield)
                     ->buildOauth($url, $requestMethod)
                     ->performRequest();

        return $json;
    }
?>