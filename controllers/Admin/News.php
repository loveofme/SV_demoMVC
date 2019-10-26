<?php
/**
 * Created by PhpStorm.
 * User: GASTOM
 * Date: 2018/12/5
 * Time: 下午 03:06
 */

namespace controllers\admin;

use \publics\Controller AS Controller;
use \publics\Notification AS Notification;
use \models\news AS newsM;

class News extends Controller
{
    //選單url高亮
    private $active = "News/index";

    public function index()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'read');
        if (!$verify) {
            $this->redirect('/Admin/Home/index');
        }

        $min_title = '最新消息';
        $where = [];
        $searchData = [];
        $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
        unset($_GET['page']);
        //標題
        if (!empty($_GET['title'])) {
            $where['title'] = ['value' => $_GET['title'], 'type' => 'like'];
            $searchData['title'] = $_GET['title'];
        }
        //狀態
        if (!empty($_GET['status'])) {
            $where['status'] = $_GET['status'];
            $searchData['status'] = $_GET['status'];
        }
        //推播
        if (!empty($_GET['notificationStatus'])) {
            $where['notification_status'] = $_GET['notificationStatus'];
            $searchData['notificationStatus'] = $_GET['notificationStatus'];
        }
        //群族
        if (!empty($_GET['type'])) {
            $where['type'] = $_GET['type'];
            $searchData['type'] = $_GET['type'];
        }
        $newsModle = new newsM;
        $dataList = $newsModle->getList(20, $page, $where);
        $createdAdmin = $newsModle->getCreatedAdmin();
        $this->setTitle($min_title)
            ->LoadView('news_index', [
                'active' => $this->active,
                'data' => $dataList,
                'searchData' => $searchData,
                'statusStr' => array('1' => '顯示', '2' => '隱藏'),
                'notification_statusStr' => array('1' => '已推', '2' => '未推'),
                'typeStr' => array('1' => '一般用戶', '2' => '商業用戶', '3' => '瓦斯行', '4' => '物流士', '99' => '全部'),
                'createdAdmin' => $createdAdmin
            ]);
    }

    public function add()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'add');
        if (!$verify) {
            $this->redirect('/Admin/Home/index');
        }

        $min_title = '最新消息-新增';
        $error_data = [];
        $old = [];
        if (!empty($_SESSION['NewsErrorData'])) {
            $error_data = $_SESSION['NewsErrorData'];
            unset($_SESSION['NewsErrorData']);
        }
        if (!empty($_SESSION['NewsPostData'])) {
            $old = $_SESSION['NewsPostData'];
            unset($_SESSION['NewsPostData']);
        }

        $this->setTitle($min_title)
            ->LoadView('news_add', [
                'active' => $this->active,
                'error_data' => $error_data,
                'old' => $old
            ]);
    }

    public function insert()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'add');
        if (!$verify) {
            $this->redirect('/Admin/Home/index');
        }

        $old = $_POST;
        if (empty($old['type'])) {
            $error_data['type'] = 1;
        }
        if (empty($old['title'])) {
            $error_data['title'] = 1;
        }
        if (empty($old['content'])) {
            $error_data['content'] = 1;
        }
        if (empty($error_data)) {
            $insert['status'] = $old['status'];
            $insert['notification_status'] = $old['notification_status'];
            $insert['type'] = $old['type'];
            $insert['title'] = $old['title'];
            $insert['content'] = $old['content'];
            $insert['update_time'] = 'UNIX_TIMESTAMP()';
            $insert['update_admin'] = $this->AUTHData['id'];
            $insert['created_time'] = 'UNIX_TIMESTAMP()';
            $insert['created_admin'] = $this->AUTHData['id'];
            if ($old['notification_status'] == 1) {
                $insert['send_time'] = 'UNIX_TIMESTAMP()';
                //發送推播
                $NotificationModel = new Notification('99', '1');
                $NotificationModel->setTitle($old['title'])
                    ->setMessage(nl2br($old['content']))
                    ->setType('1')
                    ->setToType($old['type'])
                    ->onesigalGroupPush();
            }
            $newsModel = new newsM;
            $newsModel->insert($insert);
            $this->redirect('/Admin/News/index');
        } else {
            $_SESSION['NewsErrorData'] = $error_data;
            $_SESSION['NewsPostData'] = $old;
            $this->redirect('/Admin/News/add');
        }
    }

    public function edit()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'edit');
        if (!$verify) {
            $this->redirect('/Admin/Home/index');
        }

        $editId = $_GET['id'];
        $min_title = '最新消息-修改';
        $data = [];
        $error_data = [];
        $old = [];
        $SYSTEM_ERROR = [];
        if (!empty($editId)) {
            if (!empty($_SESSION['NewsErrorData'])) {
                $error_data = $_SESSION['NewsErrorData'];
                unset($_SESSION['NewsErrorData']);
            }
            if (!empty($_SESSION['NewsPostData'])) {
                $old = $_SESSION['NewsPostData'];
                unset($_SESSION['NewsPostData']);
            }
            $newsModel = new newsM;
            $data = $newsModel->getData($editId);
        }
        if (empty($data)) {
            $SYSTEM_ERROR = array(
                'message' => '資料錯誤,請重新進入',
                'url' => '/Admin/News/index'
            );
        }
        $this->setTitle($min_title)
            ->LoadView('news_edit', [
                'active' => $this->active,
                'error_data' => $error_data,
                'data' => $old ? $old : $data,
                'SYSTEM_ERROR' => $SYSTEM_ERROR
            ]);
    }

    public function update()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'edit');
        if (!$verify) {
            $this->redirect('/Admin/Home/index');
        }

        $old = $_POST;
        if (empty($old['type'])) {
            $error_data['type'] = 1;
        }
        if (empty($old['title'])) {
            $error_data['title'] = 1;
        }
        if (empty($old['content'])) {
            $error_data['content'] = 1;
        }
        if (empty($error_data)) {
            $update['status'] = $old['status'];
            $update['type'] = $old['type'];
            $update['title'] = $old['title'];
            $update['content'] = nl2br($old['content']);
            $update['update_time'] = 'UNIX_TIMESTAMP()';
            $update['update_admin'] = $this->AUTHData['id'];
            $newsModel = new newsM;
            $newsModel->update(['id' => $old['id']], $update);
            $this->redirect('/Admin/News/index');
        } else {
            $_SESSION['NewsErrorData'] = $error_data;
            $_SESSION['NewsPostData'] = $old;
            $this->redirect('/Admin/News/edit');
        }
    }

    public function ajaxStatusEdit()
    {
        //權限驗證
        $verify = $this->verify($this->active, 'edit');
        if (!$verify) {
            $this->PrintJson([], '999', false);
        }

        $old = $_POST;
        if (empty($old['id']) || empty($old['status'])) {
            $this->PrintJson(array(
                'msg' => '資料錯誤'
            ), '200', false);
        } else {
            $newsModel = new newsM;
            $newsModel->update(['id' => $old['id']], ['status' => $old['status'], 'update_admin'=>$this->AUTHData['id'],'update_time'=>'UNIX_TIMESTAMP()']);
            $this->PrintJson([], '200', true);
        }
    }

    public function ajaxNotification()
    {
        $res = ['status' => true, 'data' => ['message' => '推播完成'], 'code' => '200'];
        if (empty($_POST['id'])) {
            $res['data'] = ['message' => '資料錯誤!'];
            $res['status'] = false;
        } else {
            $newModel = new newsM;
            $data = $newModel->getData($_POST['id']);
            if (!empty($data)) {
                //發送推播
                $NotificationModel = new Notification('99', '1');
                $NotificationModel->setTitle($data['title'])
                    ->setMessage(nl2br($data['content']))
                    ->setType('1')
                    ->setToType($data['type'])
                    ->onesigalGroupPush();
            } else {
                $res['data'] = ['message' => '查無資料'];
                $res['status'] = false;
            }
        }
        $this->PrintJson($res['data'], $res['code'], $res['status']);
    }
}