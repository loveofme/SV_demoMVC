<?php


namespace controllers\APIConsumer;

use \publics\APIConsumer AS APIConsumer;
use \models\getNews AS getNews;

class News extends APIConsumer
{
    public function getNews()
    {

        $gNews = new getNews();

        $data = $gNews->getNewsData();

        $res = array('c' => '200');

        if (count($data) == 0 || !$data) {
            $res['d'] = '';
        } else {
            $res['d'] = $data;
        }

        return $res;
    }
}
