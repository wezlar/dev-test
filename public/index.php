<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

$current_time = time();
$expire_time = 24 * 60 * 60;
$file_name = 'episodes-cache.txt';
$file_time = file_exists($file_name) ? filemtime($file_name) : $current_time - $expire_time;
if(file_exists($file_name) && ($current_time - $expire_time < $file_time)) {
  $data = json_decode(file_get_contents($file_name), true);
} else {
  //Get the episodes from the API
  try {
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', 'http://3ev.org/dev-test-api/');
    $data = json_decode($res->getBody(), true);
    file_put_contents($file_name, $res->getBody());
  
    $error = '';
    //Sort the episodes
    array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);
  } catch (\GuzzleHttp\Exception\RequestException $e) {
    $data = [];
    $error = 'Sorry, there was an error. Please try again by reloading the page.';
  }
}

//Render the template
echo $twig->render('page.html', ["episodes" => $data, "error" => $error]);

  
$error = '';