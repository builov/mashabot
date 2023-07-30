<?php
use Builov\MashaBot\Db;
use Builov\MashaBot\Request;
use Builov\MashaBot\RequestProcessor;
use Builov\MashaBot\Response;

require 'vendor/autoload.php';
require 'config.php';

header('Content-Type: text/html; charset=utf-8');

$request = new Request();
if ($request->is_empty()) {
    exit;
}

//echo $request->text;

$rp = new RequestProcessor($request);
try {
    $response = $rp->process();
} catch (Exception $e) {
}

//var_dump($response->message->properties); exit;

try {
    $response->send();
} catch (Exception $e) {
}