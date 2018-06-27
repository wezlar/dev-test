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
array_multisort(array_keys($data), SORT_ASC, SORT_STRING, $data);

// get list of unique seasons for filter
$seasons = array_unique(array_column($data, 'season'));

//Get filter
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

//Render the template
echo $twig->render('page.html', ["episodes" => $data, "seasons" => $seasons, "filter" => $filter]);
