<?php
require_once 'vendor/autoload.php';
use Searcher\Search;

$searcher = new Search();
$serched = $searcher->search('text.txt', 'Задача');

var_dump($serched);
