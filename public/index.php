<?php
require "../vendor/autoload.php";
use RedBean_Facade as R;

/************************************* INCLUDE CONFIG ****************************************/
include($_SERVER['DOCUMENT_ROOT'] . "/../config.php");


/************************************* SETUP SLIM ****************************************/
$app = new \Slim\Slim([
    'templates.path' => "./"
]);

/************************************* SETUP REDBEAN ****************************************/
R::setup(
    "mysql:host=".$config['host']."; dbname=".$config['db'],
    $config['user'],
    $config['password']);

/************************************* GET / ****************************************/
$app->get('/', function () use ($app) {
    // check for id, if one exists, redirect
    $request = $app->request();
    $request_id = $request->get('id');
    if ($request_id !== null)
    {
        $app->redirect('/c/'.$request_id);
    }

    // code here
    $code = '{ "map" : [
    ] }';

    // include "select.php";
    $app->render("select.php", ['code' => $code]);

});



/************************************* GET /c ****************************************/
/************************************* GET /castles ****************************************/
$app->get('/:controller/', function ($action) use ($app) {
    // check for id, if one exists, redirect
    $request = $app->request();
    $request_id = $request->get('id');
    if ($request_id !== null)
    {
        $app->redirect('/c/'.$request_id);
    }

    $code = '{ "map" : [
    ] }';

    $app->render("select.php", ['code' => $code]);
})->conditions(array(':controller' => '(castles|c)'));



/************************************* GET /c/e2be188 ****************************************/
/************************************* GET /castles/e2be188 ****************************************/
$app->get('/:controller/(:id)', function ($controller, $id) use ($app) {  
    $code = '{ "map" : [
    ] }';

    $map = R::findOne('maps',' unique_id = ? ', [$id]);
    $message = "";
    $status = "";
    if ($map !== null)
    {
        $status = "success";
        $code = stripcslashes(htmlspecialchars($map->code));
    }
    else
    {
        $status = "fail";
        $message = "Sorry, I couldn't find that map!";
    }

    // include "select.php";
    $app->render("select.php", ['code' => $code, 'status' => $status,'message' => $message]);
})->conditions(array(':controller'));


/************************************* POST /castles/ ****************************************/
$app->post('/castles/', function() use ($app) {
    $req = $app->request();
    $paramValue = $req->params('code');
    if (!$paramValue)
    {
        die(json_encode(array('status' => 'error', 'message' => 
                'No map found to save!')));
    }

    $request = $app->request();
    $code = $request->params('code');

    // check to see if code already exists

    $result = R::findOne("maps", "code = '?'", [$code]);

    if ($result !== null)
    {
        $unique_id = $result->unique_id;
        die(json_encode(array('status' => 'success', 'message' => 
            'Saved! Share this link:', 'link' => "http://".$_SERVER['SERVER_NAME']."/c/$unique_id")));
    }

    // it's unique so create a unique id
    do
    {
        $unique_id = substr(md5(time()), -7);
        $result = R::findOne("maps", "unique_id = '?'", [$unique_id]);
    } while ($result !== null);

    // alright we've got the unique id, lets add the code and send back the message

    $map = R::dispense('maps');
    $map->unique_id = $unique_id;
    $map->code = $code;
    R::store($map);

    die(json_encode(array('status' => 'success', 'message' => 
            'Saved! Share this link:', 'link' => "http://".$_SERVER['SERVER_NAME']."/c/$unique_id")));
});

$app->run();