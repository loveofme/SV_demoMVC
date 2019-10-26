<?php

namespace models;

use \publics\Model as Model;

class getNews extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'gt_news';
    }

    public function getNewsData()
    {

        $redisData = $this->Redis->hgetall('news');

        if ($redisData) {
            $data = [];
            foreach ($redisData as $k => $v) {
                array_push($data, json_decode($v, true));
            }
        } else {
            $data = $this->sqlGetNews();
        }

        return $data;
    }

    private function sqlGetNews()
    {
        $dbData = $this->DB->query('select id,title,content,created_time from ' . $this->tableName . ' where status = 1 and type in (1,99) order by created_time desc', [])->fetchAll();
        return $dbData;
    }

    public function getTypeNewsData($type)
    {

        $dbData = $this->DB->query('select id,title,content,created_time from ' . $this->tableName . ' where status = 1 and type in ('.$type.',99)', [])->fetchAll();
        return $dbData;
    }

}