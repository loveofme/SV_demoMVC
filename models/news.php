<?php
/**
 * Created by PhpStorm.
 * User: GASTOM
 * Date: 2018/11/12
 * Time: 下午 01:56
 */

namespace models;

use \publics\Model as Model;

class news extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'gt_news';
    }

    public function getData($id)
    {
        if (empty($id)) {
            return false;
        }
        $data = $this->DB->query('select * from ' . $this->tableName . ' where id = :id ', [':id' => $id])->fetch();
        return $data;
    }
}