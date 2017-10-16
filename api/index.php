<?php
require_once "../config.php";

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//
// SWITCH API
//

if (!$_GET['action']) return send(error_content('no action'));

switch ($_GET['action']) {

  case 'getsignature':
    if (!filter_var($_GET['appurl'] , FILTER_VALIDATE_URL)) return send(error_content('appurl should be a URL'));
    if (!isset($_GET['signuri'])) return send(error_content('signUri does not exist'));
    if (!isset($_GET['appid'])) return send(error_content('appid does not exist'));

    $appConfig = getAppConfig($_GET['appid']);
    if (!$appConfig) return send(error_content('appid does not exist'));

    require_once "./JSSDK.php";

    $jssdk = new JSSDK($appConfig['appid'], $appConfig['appsecret']);
    $signedUri = $jssdk->getSignPackage($_GET['appurl'], $_GET['signuri']);
    
    // $logger = fopen("../logs.txt", "a") or die("Unable to open file!");
    // $txt = time() . ' --> '. $_SERVER['HTTP_USER_AGENT']. ' --> '.json_encode(answer_content($signedUri)).'\r\n'.PHP_EOL.'\r\n'.PHP_EOL;
    // fwrite($logger, "\n". $txt);
    // fclose($logger);
    
    return send(answer_content($signedUri));
  break;



  case 'clean':
    if ($_GET['password'] !== $config['password']) return send(error_content('nope'));

    $appConfig = getAppConfig($_GET['appid']);
    if (!$appConfig) return send(error_content('appid does not exist'));

    print_r("=========== BEFORE<br>");
    print_r(json_decode(file_get_contents("./../keys/".$appConfig['appid']."_jsapi_ticket.json")));
    print_r(json_decode(file_get_contents("./../keys/".$appConfig['appid']."_access_token.json")));


    $fp = fopen("./../keys/".$appConfig['appid']."_jsapi_ticket.json", "w+");
    fwrite($fp, json_encode(''));
    fclose($fp);

    $fp = fopen("./../keys/".$appConfig['appid']."_access_token.json", "w+");
    fwrite($fp, json_encode(''));
    fclose($fp);

    print_r("<br>=========== AFTER<br>");
    print_r(json_decode(file_get_contents("./../keys/".$appConfig['appid']."_jsapi_ticket.json")));
    print_r(json_decode(file_get_contents("./../keys/".$appConfig['appid']."_access_token.json")));


  break;
  default:
    // return send(answer_content('nothing to do'));
}

//
// FUNCTIONS
//

function getAppConfig ($appId) {

  global $config;

  foreach ($config['apps'] as $appName => $appConfig) {
    if ($appConfig[0] === $_GET['appid']) $verifiedAppConfig = $appConfig;
  }

  return ($verifiedAppConfig) ? ['appid' => $verifiedAppConfig[0], 'appsecret' => $verifiedAppConfig[1]] : false;
}

function send ($content) {
  header("Access-Control-Allow-Origin: *");
  header('Content-type: application/json');
  print json_encode($content);
}

function error_content ($content) {
  return [
    'type' => 'error',
    'content' => $content,
  ];
}

function answer_content ($content) {
  return [
    'type' => 'ok',
    'content' => $content,
  ];
}

?>
