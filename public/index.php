<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
try {
  $client = new GuzzleHttp\Client();
  $res = $client->request('GET', 'http://3ev.org/dev-test-api/');
  $data = json_decode($res->getBody(), true);
  $error = '';

  //Sort the episodes
  array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);
} catch (\GuzzleHttp\Exception\RequestException $e) {
  $data = [];
  $error = 'Sorry, there was an error. Please try again by reloading the page.';
}

//Render the template
echo $twig->render('page.html', ["episodes" => $data, "error" => $error]);
