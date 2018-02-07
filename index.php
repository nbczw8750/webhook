<?php
ini_set("display_errors", "On");
set_time_limit(0);
ini_set('open_basedir', '/');
$_config = include_once __DIR__."/config.php";
if (!isset($_GET['k']) && trim($_GET['k'])){
    echo "get miss param k";
    exit(0);
}
$k = trim($_GET['k']);
if ($k && isset($_config[$k])){
    $config = $_config[$k];
}else{
    echo "config non-existent";
    exit(0);
}
/* get user token and ip address */
$clientIp = $_SERVER['REMOTE_ADDR'];
$clientToken = false;
if (!$clientToken && isset($_SERVER["HTTP_X_HUB_SIGNATURE"])) {
    list($algo, $clientToken) = explode("=", $_SERVER["HTTP_X_HUB_SIGNATURE"], 2) + array("", "");
} elseif (isset($_SERVER["HTTP_X_GITLAB_TOKEN"])) {
    $clientToken = $_SERVER["HTTP_X_GITLAB_TOKEN"];
} elseif (isset($_GET["token"])) {
    $clientToken = $_GET["token"];
}

date_default_timezone_set("UTC");


//git push 时触发的json数据，可参考gitlab中web_hooks介绍
/* get json data */
$json = file_get_contents('php://input');
$data = json_decode($json, true);

//文件记录日志
/* create open log */
$fs = fopen('./webhook.log', 'a');
fwrite($fs, 'Request on ['.date("Y-m-d H:i:s").'] from ['.$clientIp.']'.PHP_EOL);

/* test token */
if (isset($config["access_token"])){
    if (isset($_SERVER["HTTP_X_HUB_SIGNATURE"]) ) {
        if ($clientToken !== hash_hmac($algo, $json, $config["access_token"])){
            echo "Github token error";
            fwrite($fs, "Invalid token [{$clientToken}]".PHP_EOL);
            exit(0);
        }
// Check for a GitLab token
    } elseif (isset($_SERVER["HTTP_X_GITLAB_TOKEN"]) ) {
        if ($clientToken !== $config["access_token"]){
            echo "GitLab token error";
            fwrite($fs, "Invalid token [{$clientToken}]".PHP_EOL);
            exit(0);
        }
// Check for a $_GET token
    } elseif (isset($_GET["token"])) {
        if ($clientToken !== $config["access_token"]){
            echo "get token error";
            fwrite($fs, "Invalid token [{$clientToken}]".PHP_EOL);
            exit(0);
        }
// if none of the above match, but a token exists, exit
    } else{
        echo "token empty";
        fwrite($fs, "Empty token [{$clientToken}]".PHP_EOL);
        exit(0);
    }
}
/* test ip*/
if (isset($config["access_ip"])){
    if ( ! in_array($clientIp, $config["access_ip"]))
    {
        echo "ip error";
        fwrite($fs, "Invalid ip [{$clientIp}]".PHP_EOL);
        exit(0);
    }
}




if (file_exists($config["dir"] . ".git") && is_dir($config["dir"])) {
    fwrite($fs, 'BRANCH: '.print_r($data["ref"], true).PHP_EOL);
    fwrite($fs, '======================================================================='.PHP_EOL);
    echo exec($config["sh_pull"],$result);
    fwrite($fs, 'RESULT: '.print_r($result,true) .PHP_EOL);
    $fs and fclose($fs);
}else{
    mkdir($config["dir"],0755,true);
    fwrite($fs, 'INIT: '.PHP_EOL);
    fwrite($fs, '======================================================================='.PHP_EOL);
    echo exec($config['sh_clone'],$result);
    fwrite($fs, 'RESULT: '.print_r($result,true) .PHP_EOL);
}
