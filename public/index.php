<?php
require_once '../vendor/autoload.php';

//Load Twig templating environment
$loader = new Twig_Loader_Filesystem('../templates/');
$twig = new Twig_Environment($loader, ['debug' => true]);

//Get the episodes from the API
$client = new GuzzleHttp\Client();
$res = $client->request('GET', 'http://3ev.org/dev-test-api/');
$data = json_decode($res->getBody(), true);

//Sort the episodes
function episode_sort($a, $b) {
  if ($a['season'] == $b['season']) {
    return ($a['episode'] < $b['episode']) ? -1 : 1;
  } else {
    return ($a['season'] < $b['season']) ? -1 : 1;
  }
}
usort($data, "episode_sort");

//Render the template
echo $twig->render('page.html', ["episodes" => $data]);
