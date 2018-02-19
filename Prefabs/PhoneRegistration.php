<?php
require_once "Parser/index.php";

use SimplePCRE\RegExp;

class PhoneRegistration
{

    private $Phone_number;
    private $ch;
    private $url;
    private $html;
    private $regular;

    function __construct($url)
    {
        $this->url = $url;
        $this->regular = new RegExp();
        $this->ch = curl_init();

        curl_setopt($this->ch, CURLOPT_URL, $this->url);
        curl_setopt($this->ch, CURLOPT_HEADER, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($this->ch, CURLOPT_REFERER, null);
    }

    public function ReadSMS(){
        $sms_code = null;

        return $sms_code;
    }
    public function goTOPhoneSite()
    {
        $this->html = curl_exec($this->ch);
        if ($this->html == false) {
            die('Error: ' . curl_error($this->ch) . "\n");
        }
        curl_close($this->ch);

        $ArrayKeys = $this->createListPhone();
        $ArrayValue = $this->createValuePhone();

        return $this->array_combine_($ArrayKeys, $ArrayValue);
    }

    private function array_combine_($keys, $value)
    {
        $result = array();
        foreach ($keys as $i => $k) {
            $result[$k][] = $value[$i];
        }
        array_walk($result,create_function('&$v','$v = (count($v) == 1)?array_pop($v):$v;'));

        return $result;
    }

    private function createListPhone()
    {
        //$container = $this->regular->fetchSingleData($this->html, $this->regular->getTagContent('div', ['id' => 'content']));
        $result = $this->regular->fetchAllData($this->html, $this->regular->startTag('img', [
            'alt' => null,
            'title' => null,
            'class' => 'flag'
        ]));
        $result = $result['alt'];
        $returnedArray = [];

        for ($i = 0; $i < count($result) - 1; $i++)
            $returnedArray[] = $result[$i];

        return $returnedArray;
    }

    private function createValuePhone()
    {
        $container = $this->regular->fetchSingleData($this->html, $this->regular->getTagContent('div', [
            'id' => 'content'
        ]));
        $numbers = $this->regular->fetchAllData($container['div_content'], $this->regular->getTagContent('p', [
            'children' => $this->regular->getTagContent('a', ['href' => null], false)
        ]));
        $result = [];
        foreach ($numbers['a_content'] as $num) {
            if (in_array($num, $result))
                continue;
            else $result[] = $num;
        };
        return $result;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->Phone_number;
    }

    /**
     * @param mixed $Phone_number
     */
    public function setPhoneNumber($Phone_number)
    {
        $this->Phone_number = $Phone_number;
    }

    /**
     * @return resource
     */
    public function getCh()
    {
        return $this->ch;
    }

    /**
     * @param resource $ch
     */
    public function setCh($ch)
    {
        $this->ch = $ch;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param mixed $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }


}