<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: X-Requested-With, Origin, Content-Type, X-Auth-Token, Authorization, Accept");
    header("Content-Type: application/json");

    $count = $_GET['count'];

    if($count>100) $count = 100;
    else if ($count<10) $count = 10;

    echo getCache("almeriacultiva", $count);
    
    function getCache($query, $num_tweets){
        $dir = '../../CACHED/DIR';
        $file = '../../CACHED/DIR/'.$query.'_tweets_'.$num_tweets.'.txt';
        
        // Get Current Time
        $now = date('Y-m-d H:i');

        // Check if Cache Exists
        if(!file_exists($file)){
            // If Not, Create Directory and First Cached Tweet
            if(!is_dir($dir)){
                mkdir($dir, 0777, true);
            }
            $fh = fopen($file, 'w'); 

            $fecha = $now;
            $newDate = date('Y-m-d H:i', strtotime($fecha, '-2 hour'));
            $tweetData = $newDate."\r";

            $fp = fopen($file, 'w');
            fwrite($fp, $tweetData);
            fclose($fp);
        }

        // Get Cached File Contents
        $contents = file_get_contents($file);
        $lines = preg_split('/\r/', $contents);

        $date1 = new DateTime($lines[0]);
        $date2 = new DateTime($now);
        $interval = $date1->diff($date2);
        $mins = $interval->i;

        // If More Than 10 minutes Has Passed
        if($mins>10){
            $json = getTweets($num_tweets);
                  
            $tweetData = $now."\r".$json;

            $fp = fopen($file, 'w');
            fseek($fp, 0, SEEK_END);
            fwrite($fp, $tweetData);
            fclose($fp);

            //Return New Tweet  
            $return = $json;
        }else{
            //Return Cached Tweet
            $return = json_encode($lines[1]);
        }
        
        return $return;
    }

    function getTweets($num_tweets){
        ini_set('display_errors', 1);
        require_once('TwitterAPIExchange.php');

        // Set access tokens here - see: https://dev.twitter.com/apps/
        $settings = array(
            'oauth_access_token' => "1156230160481423360-7G5hQ0Gd8h1SlkVLm6QKWEyKe3M6Cd",
            'oauth_access_token_secret' => "cBJYnN4jP6hN0ltNTZoAezyLamjG4Cmn03X48vZbzGpV9",
            'consumer_key' => "kEie9aY601EYlqmO60rOzsPwy",
            'consumer_secret' => "c4vsSSBKfjFTOKq2XKkrU9tDm6Psf02G4XEySfWzvbl0wcRxQH"
        );

        // https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-home_timeline 
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