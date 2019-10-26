<?php
/**
 * Created by PhpStorm.
 * User: GASTOM
 * Date: 2018/11/12
 * Time: 下午 01:56
 */

namespace models;

use \publics\Model as Model;

class invoice extends Model
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = 'gt_invoice';
        $this->tableName2 = 'gt_order';
        $this->Identifier='52552046';
        $this->InvoiceKey='Dt8lyToo17X/XkXaQvihuA==';
        $this->aesKey='8F66591331E41008C2EAF749529AB120';
    }

    public function upData($whereData,$setData){
        $res = $this->update($whereData, $setData);
        return $res;
    }
    
    public function getData($id)
    {
        if (empty($id)) {
            return false;
        }
        $data = $this->DB->query('select * from ' . $this->tableName . ' where id = :id ', [':id' => $id])->fetch();
        return $data;
    }

    public function getWhereData($where)
    {
        $condparam = [];
        foreach ($where as $k => $v) {
            if (is_array($v)) {
                $whereSql[] = $k . ' in("' . implode('", "', $v ) . '")';
            } else {
                $whereSql[] = $k . ' = :' . $k;
                $condparam[':' . $k] = $v;
            }
        }
        $dbData = $this->DB->query('select * from ' . $this->tableName . ' where ' . implode(' and ', $whereSql), $condparam)->fetchAll();
        return $dbData;
    }
    //批次新增發票
    /*
        start=開始數字
        end=結束數字
        id=使用者編號
        limit=最大 Insert筆數
    */
    public function insertInoviceData($start,$end,$title,$id,$year,$month,$limit=500){
        $dataCount=$end-$start;//資料筆數
        $sliceCount=($dataCount/$limit);//批次量
        for($j=1;$j<=$sliceCount+1;$j++){
            $insertData=array();
            $insertData['cols']=array(
                'status','redemption_status','invoice_status','no','year',
                'month','order_no','rand','update_time','update_admin',
                'created_time','created_admin'  
            );
            $nowStart=(($j-1)* $limit)+$start;
            $nowEnd=($j* $limit)+$start;
            $nowEnd=($end>$nowEnd)?$nowEnd:$end+1;
            for($i=$nowStart;$i<$nowEnd;$i++){
                $insertValue=array();
                $insertValue['status'] = '1';
                $insertValue['redemption_status'] = '1';
                $insertValue['invoice_status'] = '1';
                $insertValue['no'] = strtoupper($title).str_pad($i,8,0,STR_PAD_LEFT);
                $insertValue['year'] = $year;
                $insertValue['month'] = $month;
                $insertValue['order_no'] = '';
                $insertValue['rand'] ='';
                $insertValue['update_time'] = 'UNIX_TIMESTAMP()';
                $insertValue['update_admin'] = $id;
                $insertValue['created_time'] = 'UNIX_TIMESTAMP()';
                $insertValue['created_admin'] = $id;
                $insertString='"'.implode('","',$insertValue).'"';
                $insertString=str_replace('"UNIX_TIMESTAMP()"','UNIX_TIMESTAMP()',$insertString);
                $insertData['value'][]=$insertString;
            }
            $this->insertsData($insertData);
        }
    }

    /**
     * 取得發票號並且更新發票狀態
     * $day => date('Ymd')
     */
    public function getInvoiceNumber($orderNo)
    {
        //確認發票數
        $month = round(bcdiv(date('m'), 2, 1));
        $updateTime = time();
        $string = '0123456789';
        $rand = '';
        for ($i = 0; $i < 4; $i++) {
            $rand .= $string[rand() % strlen($string)];
        }

        $sql = 'select receipt_no from '.$this->tableName2.' where no ="'.$orderNo.'"';
        $receiptData=$this->DB->query($sql)->fetch();
        if(!empty($receiptData) && !empty($receiptData['receipt_no'])){
            return $receiptData['receipt_no'];
        }
        $update = $this->DB->query('update ' . $this->tableName . ' set status = 2, rand = "' . $rand . '", order_no = "' . $orderNo . '", update_time = "' . $updateTime . '",  date='. strtotime(date('Ymd')) .' where status = 1 and year = "' . date('Y') . '" and month = "' . $month . '" and order_no="" order by id asc limit 1');
        if ($update) {
            $data = $this->DB->query('select no from ' . $this->tableName . ' where status = 2 and rand = "' . $rand . '" and order_no = "' . $orderNo . '" and update_time = "' . $updateTime . '"')->fetch();
            return $data['no'];
        }
        return false;
    }

    /**
     * 取得發票號並且更新發票狀態
     * $day => date('Ymd')
     */
    public function getInvoiceNumber2($orderNo,$day = '')
    {
        //確認發票數
        $day = !empty($day)?$day:date('Ymd');
        $dayTS = strtotime($day);

        $month = round(bcdiv(date('m',$dayTS), 2, 1));
        $updateTime = time();
        $string = '0123456789';
        $rand = '';
        for ($i = 0; $i < 4; $i++) {
            $rand .= $string[rand() % strlen($string)];
        }
        $update = $this->DB->query('update ' . $this->tableName . ' set status = 2, rand = "' . $rand . '", order_no = "' . $orderNo . '", update_time = "' . $updateTime . '",  date='. $dayTS .' where status = 1 and year = "' . date('Y') . '" and month = "' . $month . '" and order_no="" order by id asc limit 1');
        if ($update) {
            $data = $this->DB->query('select no from ' . $this->tableName . ' where status = 2 and rand = "' . $rand . '" and order_no = "' . $orderNo . '" and update_time = "' . $updateTime . '"')->fetch();
            return $data['no'];
        }
        return false;
    }

    //取得這個月未使用的發票數量
    public function getThisInvoiceEnble($month) {
        $where=array(
            'month'=>$month,
            'year'=>date('Y'),
            'status'=>'1',
            'order_no'=>'',
        );
        $invoice= $this->getWhereData($where);
        return (count($invoice)<1)?0:count($invoice);
    }
}
