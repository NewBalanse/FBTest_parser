<?php

/**
 * Created by PhpStorm.
 * User: NewBalanse
 * Date: 15.02.2018
 * Time: 14:04
 *
 */
require_once "ComponentToParse/ComponentPeople.php";

class FbParser
{
    private $url = "https://www.facebook.com";//facebook url
    private $hostSelenium = null;
    private $driver = null;
    private $_islogIn = false;
    private $ArrayLinkPeople = array();

    function __construct($driver = null)
    {
        if ($driver != null) {
            $this->driver = $driver;
        } else {
            $this->hostSelenium = "http://localhost:4444/wd/hub";
            $this->driver = \Facebook\WebDriver\Remote\RemoteWebDriver::create(
                $this->hostSelenium,
                array(
                    'platform' => "WIN10",
                    'browserName' => "chrome",
                    'version' => "64",
                ), 0

            );
            // $this->driver->manage()->window()->maximize();

        }
    }

    private function WaitElementVisible($driver, $webDriverBy, $timeout = 10, $interval = 250, $message = "TIME_OUT")
    {
        $end = microtime(true) + $timeout;
        $last_exception = null;
        while ($end > microtime(true)) {
            try {
                $driver->wait($timeout)->until(
                    \Facebook\WebDriver\WebDriverExpectedCondition::visibilityOfElementLocated($webDriverBy)
                );
                return true;
            } catch (Exception $exception) {
                $last_exception = $exception;
            }
            usleep($interval * 1000);
        }
        if ($last_exception)
            throw $last_exception;
        throw new \Facebook\WebDriver\Exception\TimeOutException($message);

    }

    public
    function LogInFb($mail = "coocies12@gmail.com", $pass = "X2yhsg3j7a9E3159")
    {
        try {
            $isLoadingPage = false;
            try {
                $this->driver->navigate()->to($this->url);
                $isLoadingPage = $this->WaitElementVisible($this->driver,
                    \Facebook\WebDriver\WebDriverBy::cssSelector("#login_form > table > tbody > tr:nth-child(1) > td:nth-child(1) > label"));
            } catch (Exception $e) {
                echo $e;
                #$msg = $e->getMessage();
                #echo "_Exception || msg=[[\n$msg\n]]";
                exit;
            }
            if ($isLoadingPage == true) {

                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#email"))->sendKeys($mail);
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#pass"))->sendKeys($pass);

                $btn_LogIn = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#loginbutton"));

                echo $btn_LogIn->getAttribute("for");
                $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#" . $btn_LogIn->getAttribute("for")))->click();

                $Elemnt = $this->Search_id_ElemetVerifi();
                if ($this->WaitElementVisible($this->driver,
                    \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $Elemnt . " > div > div > div._2t-e > div:nth-child(1)"))
                ) {

                    return true;
                }

            }

            return false;
        } catch (Exception $exception) {
            #js_6 > div > div > div._2t-e > div:nth-child(1)
            $EmelentVerifi = $this->Search_id_ElemetVerifi();
            if ($this->WaitElementVisible($this->driver,
                \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $EmelentVerifi . " > div > div > div._2t-e > div:nth-child(1)"))
            ) {
                return true;
            } else
                throw new Exception("Log Error");
        }
    }

    private
    function Search_id_ElemetVerifi()
    {
        for ($i = 0; $i < 10; $i++) {
            try {
                if ($this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#js_" . $i))->getAttribute("role") == "banner") {
                    $Elemnt = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector("#js_" . $i))->getAttribute("id");
                    return $Elemnt;
                }
            } catch (Exception $e) {
                //echo "\nError element id";
                //echo "\nElement id: " . $i . "\n";
                continue;
            }
        }
        return null;
    }

    private
    function GoToUrlFind_PoplePlusCity($People_name, $City_name)
    {
        $FindsUrl = "https://www.facebook.com/search/str/" . $People_name . "+" . $City_name . "/keywords_users";
        $this->driver->navigate()->to($FindsUrl);

        $Element = $this->Search_id_ElemetVerifi();
        if ($this->WaitElementVisible($this->driver,
            \Facebook\WebDriver\WebDriverBy::cssSelector("#" . $Element . " > div > div > div._2t-e > div:nth-child(1)"))
        ) {
            return true;
        }
        return false;
    }

    private
    function GetInfoPeople($LinkPeople)
    {
        try {
            $link = substr($LinkPeople->getAttribute("href"), 0, -10);
            echo $link . "\n";
            array_push($this->ArrayLinkPeople, $link);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public
    function SearchPeople($ArrayPeople = [], $ArrayCity = [])
    {
        $countPeople = 0;
        $countCity = 0;
        while ($ArrayPeople[$countPeople] != null || $ArrayCity[$countCity] != null) {
            if ($this->GoToUrlFind_PoplePlusCity($ArrayPeople[$countPeople], $ArrayCity[$countCity])) {
                $this->ArrayLinkPeople = array();
                $Array_result = null;

                sleep(5);
                $this->driver->getKeyboard()->sendKeys(\Facebook\WebDriver\WebDriverKeys::ESCAPE);

                $Array_link_search_element = array(
                    "#BrowseResultsContainer > div:nth-child(",
                    "#u_ps_0_3_0_browse_result_below_fold > div > div:nth-child(",
                    "#fbBrowseScrollingPagerContainer"
                );
                $countLinkElement = -1;//at the using while -1;

                $TotalCount = 0;
                while (true) {

                    if ($countLinkElement < 2) {
                        $countLinkElement++;

                        for ($i = 1; $i <= 6; $i++) {
                            try {

                                $Selector = $Array_link_search_element[$countLinkElement] . $i . ")";
                                $Array_result = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($Selector));
                                $LinkPeople = $this->ReDivElement($Array_result);

                                if (!empty($LinkPeople)) {
                                    $this->GetInfoPeople($LinkPeople);
                                    sleep(2);
                                    $this->ScrollingPage();
                                    $TotalCount++;
                                }
                            } catch (Exception $exception) {
                                echo $exception->getCode();
                            }
                        }
                    } else if ($countLinkElement >= 2) {
                        $countLinkConkat = 0;

                        try {
                            while (true) {
                                $Link_Concat = $Array_link_search_element[2];
                                $Link_Concat .= $countLinkConkat;

                                if ($this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($Link_Concat))) {
                                    for ($i = 1; $i <= 6; $i++) {
                                        try {
                                            $Selector = $Link_Concat . " > div > div:nth-child(" . $i . ")";
                                            $Array_result = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($Selector));
                                            $LinkPeople = $this->ReDivElement($Array_result);
                                            if (!empty($LinkPeople)) {
                                                $this->GetInfoPeople($LinkPeople);
                                                sleep(2);
                                                $this->ScrollingPage();
                                                $TotalCount++;
                                            }

                                        } catch (Exception $exception) {
                                            echo "\n|| log Error ::: GET message\n\n";
                                            echo $exception->getMessage();
                                            throw new Exception("Error not search element sorry", 1945);
                                        }
                                    }
                                    $countLinkConkat++;
                                } else {
                                    throw new Exception("My exception !", 1945);
                                }
                            }
                        } catch (Exception $e) {
                            echo $e->getMessage();
                            break;
                        }
                    }
                }


                /*for ($i = 1; $i <= 6; $i++) {
                    try {

                        $Selector = $Array_link_search_element[$countLinkElement] . $i . ")";
                        $Array_result = $this->driver->findElement(\Facebook\WebDriver\WebDriverBy::cssSelector($Selector));
                        $LinkPeople = $this->ReDivElement($Array_result);

                        if (!empty($LinkPeople)) {
                            $this->GetInfoPeople($LinkPeople);
                            sleep(2);
                            $this->ScrollingPage();
                            $TotalCount++;
                        }
                    } catch (Exception $exception) {
                        echo $exception->getCode();
                    }
                }*/
            }
            if ($countCity != count($ArrayCity) - 1) {
                $countCity++;
                echo "\n\nTotal count element in array :: " . count($this->ArrayLinkPeople) . "\n\n";
                $this->Navigate_about_People($this->driver);
                break;

            } else {
                $countPeople++;
                $countCity = 0;
            }
        }
    }

    private function Navigate_about_People($driver)
    {
        foreach ($this->ArrayLinkPeople as $item) {
            try {
                $driver->navigate()->to($item);
                $AllElements_li = $driver->findElements(\Facebook\WebDriver\WebDriverBy::tagName("li"));
                sleep(5);
                $this->driver->getKeyboard()->sendKeys(\Facebook\WebDriver\WebDriverKeys::ESCAPE);
                foreach ($AllElements_li as $element) {
                    $data_tab_key = $element->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"));
                    if ($data_tab_key->getAttribute("data-tab-key") == "about") {
                        $data_tab_key->click();
                        if ($this->WaitElementVisible($driver, \Facebook\WebDriver\WebDriverBy::cssSelector("#medley_header_about")))
                            $this->Navigate_atThe_NavBar_People($driver);
                        else
                            echo "\nError wait complete\n";
                        break;
                    }
                }
            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }

    private function ReturnListAbout($driver, $ElementTagName, $attribute, $constant)
    {

        $UL_list = $driver->findElements(\Facebook\WebDriver\WebDriverBy::tagName($ElementTagName));
        foreach ($UL_list as $item) {
            $ech = $item->getAttribute($attribute);
            if (!empty($ech))
                if ($ech == $constant)
                    return $item;
        }
        return null;
    }

    private function Navigate_atThe_NavBar_People($driver)
    {
        try {
            sleep(3);
            $ALlElement_li = $driver->findElements(\Facebook\WebDriver\WebDriverBy::tagName("li"));
            foreach ($ALlElement_li as $item) {
                $Attribute_testId = $item->getAttribute("testid");
                if (!empty($Attribute_testId)) {
                    switch ($Attribute_testId) {
                        case "nav_overview":
                            //echo "\n1\n";
                            $StringResult = "";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);
                            $list_about = $this->ReturnListAbout($driver, "ul", "data-pnref", "about");
                            $ListLi = $list_about->findElements(\Facebook\WebDriver\WebDriverBy::tagName("li"));
                            foreach ($ListLi as $element) {
                                $ArrayReturnedDiv = $this->ReturnListAbout($driver, "div", "data-pnref", "overview");
                                $ul = $ArrayReturnedDiv->findElement(\Facebook\WebDriver\WebDriverBy::tagName("ul"));

                                foreach ($ul->findElements(\Facebook\WebDriver\WebDriverBy::tagName("li")) as $li) {
                                    $ElementDiv = $li->findElements(\Facebook\WebDriver\WebDriverBy::tagName("a"));

                                    foreach ($ElementDiv as $aTaf) {
                                        if (!empty($aTaf->getText()))
                                            $StringResult .= $aTaf->getText() . ";";

                                    }
                                }
                                break;
                            }
                            $StringResult = substr($driver->getTitle(), 4) . " : " . "\n" . $StringResult . "\n";
                            $fileName = "Data.txt";
                            file_put_contents($fileName, $StringResult, FILE_APPEND | LOCK_EX);
                            break;
                        case "nav_edu_work":
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);
                            try {
                                $ResultStr = "\n" .
                                    $this->ComponentParseGetInfoPreStart($driver,
                                        "div",
                                        "data-pnref",
                                        "work",
                                        "Работа")
                                    . "\n";

                                $ResultStr .= "\n" .
                                    $this->ComponentParseGetInfoPreStart($driver,
                                        "div",
                                        "data-pnref",
                                        "edu",
                                        "Образование")
                                    . "\n";
                                $fileName = "Data.txt";
                                file_put_contents($fileName, $ResultStr, FILE_APPEND | LOCK_EX);
                            } catch (Exception $exception) {
                                echo "\n" . $ResultStr . "\n";
                                echo "\n\nException Error fack!\n\t{" . $exception->getMessage() . "\n\t}\n";
                            }
                            break;
                        case "nav_places":
                            //echo "\n3\n";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);
                            try {
                                $ResultStr = "\n" .
                                    $this->ComponentParseGetInfoPreStart($driver,
                                        "div",
                                        "data-referrer",
                                        "pagelet_hometown",
                                        "Города")
                                    . "\n";
                                $fileName = "Data.txt";
                                file_put_contents($fileName, $ResultStr, FILE_APPEND | LOCK_EX);
                            } catch (Exception $exception) {
                                echo "\nNav_places\n";
                                echo $exception->getMessage();
                            }
                            break;
                        case "nav_contact_basic":
                            //echo "\n4\n";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);

                            break;
                        case "nav_all_relationships":
                            //echo "\n5\n";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);

                            break;
                        case "nav_about":
                            //echo "\n6\n";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);
                            $ResultStr = "\n" . $this->ComponentParseGetInfoPreStart($driver,
                                    "div",
                                    "data-referrer",
                                    "pagelet_bio",
                                    "О Себе") . "\n";
                            $ResultStr .= "\n" . $this->ComponentParseGetInfoPreStart($driver,
                                    "div",
                                    "data-referrer",
                                    "pagelet_nicknames",
                                    "Другие имена") . "\n";
                            $ResultStr .= "\n" . $this->ComponentParseGetInfoPreStart($driver,
                                    "div",
                                    "data-referrer",
                                    "pagelet_quotes",
                                    "Цитаты") . "\n";

                            $fileName = "Data.txt";
                            file_put_contents($fileName, $ResultStr, FILE_APPEND | LOCK_EX);
                            break;
                        case "nav_year_overviews":
                            //echo "\n7\n";
                            $item->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))->click();
                            sleep(3);
                            break;
                        default:
                            break;
                    }
                }
            }
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }

    private function ComponentParseGetInfoPreStart($driver, $ElementName, $Attribute, $Constant, $String)
    {
        try {
            $ResultStr = "";
            $DivCityElement = $this->ReturnListAbout($driver, $ElementName,
                $Attribute, $Constant);
            if ($DivCityElement !== null) {
                $ResultStr .= $String . ": \n";
                $ResultStr = $this->GetInfoWorkEdu($driver, $DivCityElement, $ResultStr);
            } else
                $ResultStr .= "Нет информации!";

            return $ResultStr;
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
        return null;
    }

    private function GetInfoWorkEdu($driver, $ElementParent, $ResultString, $_isContinue = false)
    {
        try {
            $ULElementWork = $ElementParent->findElements(\Facebook\WebDriver\WebDriverBy::tagName("ul"));

            foreach ($ULElementWork as $li) {
                $ElementDiv = $li->findElements(\Facebook\WebDriver\WebDriverBy::tagName("div"));
                foreach ($ElementDiv as $divElementWork) {
                    $TagDiv = $divElementWork->findElements(\Facebook\WebDriver\WebDriverBy::tagName("div"));
                    foreach ($TagDiv as $div) {

                        if (!empty($div->getText())) {
                            $ResultString .= $div->getText() . "\n";
                            break;
                        } else
                            echo "empty div";
                    }
                    break;
                }
            }
            return $ResultString;
        } catch (Exception $exception) {
            echo "\nGetInfoWorkEdu\n";
            echo $exception->getMessage();
        }

        return null;
    }

    private
    function ScrollingPage()
    {
        $this->driver->executeScript("window.scrollTo(0,document.body.scrollHeight);");
    }

    private
    function ReDivElement($result)
    {
        try {
            $tmp = $result->findElement(\Facebook\WebDriver\WebDriverBy::tagName("div"));
            if ($tmp->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"))) {
                $Result_element = $tmp->findElement(\Facebook\WebDriver\WebDriverBy::tagName("a"));
                return $Result_element;
            }
        } catch (Exception $exception) {
            throw new Exception("Unable locate element", 8080);
        }
        return null;
    }

    public
    function GetIsLogIn()
    {
        return false;
    }

}