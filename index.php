<?php
//threads
require_once "DataProvider.php";
require_once "DaraWorker.php";
require_once "Workers.php";
//Parser and component
require_once "Prefabs/Registrator.php";
require_once "Prefabs/FBRegistration.php";
require_once "Prefabs/FbParser.php";
//database
require_once "Controller/ConectDB.php";
require_once "Conf/configDatabase.php";

include "vendor/autoload.php";

use GuzzleHttp\Client;

//use GuzzleHttp\Psr7\Request;

/*$threads = 4;
$provider = new DataProvider();

$pool = new Pool($threads, 'DaraWorker', [$provider]);
$start = microtime(true);

$workers = $threads;
for ($i = 0; $i < $workers; $i++) {
    echo "For " . $i . "</br>";
    $pool->submit(new Workers());
}
echo "shutdown</br>";
$pool->shutdown();

printf("<h1>Done for %.2f second</h1>" . PHP_EOL, microtime(true) - $start);*/
/*$client = new Client();/*
$result = $client->request("GET", "https://www.facebook.com/search/str/marina+kiev/keywords_users",[
    'auth' => ["coocies12","X2yhsg3j7a9E3159"]
]);

$resultCode = $result->getStatusCode();
$htmlCode = $result->getBody();
echo $htmlCode;

echo "Code :" . $resultCode;*/

/*
ini_set('max_execution_time', 600);
function curl_get($host, $referer = null)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_USERAGENT, "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51");
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    $html = curl_exec($ch);
    echo curl_error($ch);
    curl_close($ch);
    return $html;
}

$name = urlencode("marina");
echo $name;
$lastname = urlencode("kiev");
echo $lastname;
//https://www.facebook.com/public/Marina/city/"City"-111227078906045/
https://www.facebook.com/public/Marina/city/Kiev-111227078906045/
echo urlencode("%D0%9A%D0%B8%D0%B5%D0%B2");
$urlHost = "https://www.facebook.com/search/str/" . $name . "/keywords_users?page=1";
echo $urlHost;
$urlReferer = "http://google.com";
$result = curl_get($urlHost, $urlReferer);*/
$isRegistrations = false;
$_BotFbParser = null;

try {

    $configurateDatabase = new configDatabase();

    $ConectDatabase = new ConectDB(
        $configurateDatabase->getHost(),
        $configurateDatabase->getPort(),
        $configurateDatabase->getUserName(),
        $configurateDatabase->getPassword(),
        $configurateDatabase->getDbName()
    );

    $ConectDatabase->ConnectionDB();

    /*
    $_BotFbParser = new FbParser();
    //$_BotFbParser->Test("https://www.facebook.com/profile.php?id=100010051332337&ref=br_rs");
    $isStart = OnStart($_BotFbParser);

    if ($isStart) {
        StartFindPeople(
            $_BotFbParser,
            array(
                "Павел",
                "Оля",
                "Юрий",
                "Анжелика",
                "Алла",
            ),
            array(
                "Москва",
                "Питер",
                "Киев",
                "Винница",
                "Пермь",
            ));
    }

    //while (true) {
    if ($isRegistrations) {
        $BotMailer = new Registrator();
        if ($BotMailer != null) {

            $BotMailer->GoToURL();
            $BotMailer->EnterRegistrationForm();

            if ($BotMailer->RegistrationCompleat()) {

                $BotFbRegistrator = new FBRegistration($BotMailer->ReturnDriver());

                if ($BotFbRegistrator != null) {

                    $BotFbRegistrator->navigateToRegistrationFB();
                    $BotFbRegistrator->EnterRegistrationForm();

                    if ($BotFbRegistrator->RegistrationComplite()) {
                        $ConfirmReg = $BotMailer->CoonfirmRegistration();
                        if ($ConfirmReg) {

                        } else {
                            throw new Exception("Registrations not Confirm!\n");
                        }

                    } else {
                        throw new Exception("Registrations Facebook not confirm!\n");
                    }
                }
            }
        }
    }
    //}*/


} catch (Exception $exception) {
    echo $exception->getMessage();
}

function OnStart($BotFbParser)
{
    $isLog = false;
    if (VerifiLog($BotFbParser) == false)
        $isLog = $BotFbParser->LogInFb();
    if ($isLog)
        return true;
    return false;
}

function StartFindPeople($bot, $listName = [], $listCity = [])
{
    //so more
    sleep(5);
    $bot->SearchPeople($listName, $listCity);
}

function VerifiLog($bot)
{
    if ($bot->GetIsLogIn())
        return true;
    return false;
}