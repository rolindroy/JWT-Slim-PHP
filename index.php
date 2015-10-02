<?php

define('ROOT_DIR', dirname(dirname(__FILE__)));

require_once ROOT_DIR . 'vendor/autoload.php';

use Slim\Middleware\HttpBasicAuthentication;

$app = new \Slim\Slim();
$isSecure = FALSE;

$app->add(new \Slim\Middleware\JwtAuthentication([
     "path"     => "/",
     "secret"   => "1dfg2gsd34gsdf56fg7bhrc534gdf4gvc35fdg4fg5",
     "secure"   => $isSecure,
     "rules"    => [
          new \Slim\Middleware\JwtAuthentication\RequestPathRule([
               "path"        => "/",
               "passthrough" => ["/hello", "/token"]
               ])
     ],
     "callback" => function ($options) use ($app) {
    $app->jwt = $options['decoded'];
},
     "error" => function ($arguments) use ($app) {
    $response["status"] = "error";
    $response["errorMessage"] = $arguments["message"];
    $app->response->write(json_encode($response));
}
]));

$app->add(new HttpBasicAuthentication([
     "path"   => "/token",
     "secure" => $isSecure,
     "users"  => [
          "demo" => "demo",
     ],
     "error"  => function ($arguments) use ($app) {
    $response["status"] = "error";
    $response["errorMessage"] = $arguments["message"];
    $app->response->write(json_encode($response));
}
]));



$app->get('/token', function () use ($app) {
    $payload = array(
         "iat"   => time(),
         "exp"   => time() + 300,
         "jti"   => "1dfg2gsd34gsdf56fg7bhrc534gdf4gvc35fdg4fg5",
         "scope" => ["read", "write"]
    );
    $key = \CywConfig::config('facebook.secretkey');
    $token['token'] = JWT::encode($payload, $key);
    $app->response()->write(json_encode($token));
});

//Hello
$app->get('/hello', function () use ($app) {
    $app->response()->write(json_encode("hello"));
});



$app->get('/', function () use ($app) {
    $app->response()->write(json_encode("Welcome"));
});


//GET INFO : here id is an argument
$app->get('/getInfo/:id', function ($id) use ($app) {
    /*
    * stmt;
    */
});

//PUT METHOD
$app->put('/data', function () use ($app) {
    $data = json_decode($app->request->getBody(), true);
    /*
    * stmt;
    */
});

//POST METHOD
$app->put('/set', function () use ($app) {
    $data = json_decode($app->request->getBody(), true);
    /*
    * stmt;
    */
});


$app->response()->header("Content-Type", "application/json");
$app->run();
               