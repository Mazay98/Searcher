<?php
require_once 'vendor/autoload.php';
use Searcher\Search;


try {
    $searcher = new Search();
    $searcher->addSettings('settings.yaml');
    $searcher->setFile('text.txt');
} catch(Exception $exception){
    echo $exception;
}
