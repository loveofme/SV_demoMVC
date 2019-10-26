<div class="row">
    <div class="col-lg-12 form-horizontal">
        <div class="panel panel-flat">
            <div class="panel-body">
                <form method="get" action='/Admin/News/index'>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">標題</label>
                        <div class="col-lg-2" id="gp01">
                            <input type="text" class="form-control" id="searchTitle" name="title" value="<?php echo (!empty($searchData['title']))? $searchData['title'] : ''?>">
                        </div>
                        <label class="control-label col-lg-1 text-right">狀態</label>
                        <div class="col-lg-2" id="gp01">
                            <select class="bootstrap-select" id="searchStatus" name="status" data-width="100%">
                                <option value="">全部</option>
                                <option value="1" <?php if(!empty($searchData['status']) && $searchData['status'] == 1) echo "selected" ?>>顯示</option>
                                <option value="2" <?php if(!empty($searchData['status']) && $searchData['status'] == 2) echo "selected" ?>>隱藏</option>
                            </select>
                        </div><label class="control-label col-lg-1 text-right">推播</label>
                        <div class="col-lg-2" id="gp01">
                            <select class="bootstrap-select" id="searchNotificationStatus" name="notificationStatus" data-width="100%">
                                <option value="">全部</option>
                                <option value="1" <?php if(!empty($searchData['notificationStatus']) && $searchData['notificationStatus'] == 1) echo "selected" ?>>推</option>
                                <option value="2" <?php if(!empty($searchData['notificationStatus']) && $searchData['notificationStatus'] == 2) echo "selected" ?>>未推</option>
                            </select>
                        </div><label class="control-label col-lg-1 text-right">群族</label>
                        <div class="col-lg-2" id="gp01">
                            <select class="bootstrap-select" id="searchType" name="type" data-width="100%">
                                <option value="">請選擇</option>
                                <option value="1" <?php if(!empty($searchData['type']) && $searchData['type'] == 1) echo "selected" ?>>一般用戶</option>
                                <option value="2" <?php if(!empty($searchData['type']) && $searchData['type'] == 2) echo "selected" ?>>商業用戶</option>
                                <option value="3" <?php if(!empty($searchData['type']) && $searchData['type'] == 3) echo "selected" ?>>瓦斯行</option>
                                <option value="4" <?php if(!empty($searchData['type']) && $searchData['type'] == 4) echo "selected" ?>>物流士</option>
                                <option value="99" <?php if(!empty($searchData['type']) && $searchData['type'] == 99) echo "selected" ?>>全部</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-right">
                            <button type="submit" id="sh_btn" class="btn btn-primary">搜尋<i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row" id="tb_game">
    <div class="col-lg-12 form-horizontal">
        <div class="panel border-top-primary panel-flat">
            <div class="panel-heading">
                <h5 class="panel-title">
                    <!--加權限設定-->
                    <?php if ($MainVerify['add']):?>
                        <button name="add" id="add_btn" type="button" class="btn bg-teal-400 btn-rounded">新增</button>
                    <?php endif;?>
                </h5>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table page_table">
                        <thead>
                        <tr>
                            <th class="bg-blue text-center" style="width: 8%">序列號</th>
                            <th class="bg-blue text-center" style="width: 8%">狀態</th>
                            <th class="bg-blue text-center" style="width: 8%">群族</th>
                            <th class="bg-blue text-center" style="width: 9%">標題</th>
                            <th class="bg-blue text-center" style="width: 32%">內容</th>
                            <th class="bg-blue text-center" style="width: 15%">更新時間</th>
                            <th class="bg-blue text-center" style="width: 20%">功能</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($data['list'])):?>
                            <?php foreach($data['list'] as $value):?>
                            <tr data-id="<?php echo $value['id'] ?>" data-status="<?php echo $value['status']?>" data-title="<?php echo $value['title'] ?>">
                                <td class="text-center"><?php echo $value['id'] ?></td>
                                <td class="text-center <?php echo ($value['status']==1)?'text-primary':'text-danger'?>"><?php echo $statusStr[$value['status']] ?>
                                    <br/>
                                    <span class="text-center <?php echo ($value['notification_status']==1)?'text-primary':'text-danger'?>"><?php echo $notification_statusStr[$value['notification_status']] ?></span>
                                </td>
                                <td class="text-center"><?php echo $typeStr[$value['type']] ?></td>
                                <td class="text-center tr_name"><?php echo $value['title'] ?></td>
                                <td class="text-center tr_name"><?php echo $value['content'] ?></td>
                                <td class="text-center">
                                    <?php echo date('Y-m-d H:i:s', $value['update_time']) ?><br/>
                                    <?php echo $createdAdmin[$value['update_admin']]??'系統' ?>
                                </td>
                                <td class="text-center">
                                    <!--加權限設定-->
                                    <?php if ($MainVerify['edit']):?>
                                        <button name="edit" class="btn bg-orange btn-rounded edit">修改</button>
                                        <?php if( $value['status'] == 1 ):?>
                                        <button name="status" class="btn btn-danger btn-rounded status">隱藏</button>
                                        <?php elseif( $value['status'] == 2 ):?>
                                        <button name="status" class="btn btn-primary btn-rounded status">顯示</button>
                                        <?php endif?>
                                    <?php endif;?>
                                    <button name="notification" class="btn btn-primary btn-rounded notification">推播</button>
                                </td>
                            </tr>
                            <?php endforeach;?>
                        <?php else:?>
                            <tr>
                                <td class="text-center" colspan="8">查無資料</td>
                            </tr>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>
                <?php if($data['page']['count'] > 0):?>
                <div class="datatable-footer">
                    <div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite"><?php echo $data['page']['star'] ?> 到 <?php echo $data['page']['end'] ?> 筆　共 <?php echo $data['page']['count'] ?> 筆</div>
                    <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                        <?php if($data['page']['nowPage'] == 1):?>
                        <a class="paginate_button previous disabled ">←</a>
                        <?php else:?>
                        <a class="paginate_button previous" href="<?php echo '/Admin/' . $active . '?' . $data['page']['url'] . '&page=' . ($data['page']['nowPage']-1) ?>">←</a>
                        <?php endif;?>
                        <span>
                            <?php for($i = $data['page']['starPage']; $i <= ($data['page']['starPage']+4); $i++ ):?>
                                    <?php if($i <= $data['page']['maxPage'] ):?>
                                        <a class="paginate_button <?php if($i == $data['page']['nowPage']):?>current<?php endif;?>" href="<?php echo '/Admin/' . $active . '?' . $data['page']['url'] . 'page=' . $i ?>"><?php echo $i ?></a>
                                    <?php endif;?>
                                <?php endfor;?>
                        </span>
                        <?php if($data['page']['nowPage'] == $data['page']['maxPage']):?>
                            <a class="paginate_button next disabled" >→</a>
                        <?php else:?>
                            <a class="paginate_button next" href="<?php echo '/Admin/' . $active . '?' . $data['page']['url'] . '&page=' . ($data['page']['nowPage']+1) ?>">→</a>
                        <?php endif;?>
                    </div>
                </div>
                <?php endif;?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="100" role="dialog" aria-labelledby="myModalLabel" data-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" style="top: 10%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                <div id="status">
                </div>
                <div id="error_msg" class="bg-danger-600 text-center">
                </div>
                <div id="success_msg" class="bg-blue-600 text-center">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirm_close" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" id="confirm_submit" class="btn btn-primary">送出</button>
                <button type="button" id="close" class="btn btn-default" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        (function () {
            $('.bootstrap-select').selectpicker();

            //新增資料
            $('#add_btn').click(function () {
                location.href = '/Admin/News/add';
            });

            //修改資料
            $('.edit').click(function () {
                location.href = '/Admin/News/edit?id=' + $(this).parents('tr').data('id');
            });

            //修改狀態
            $('.status').click(function () {
                $('.modal .modal-footer .btn').show();
                $('.modal .modal-footer #close').hide();
                $('.modal-body #success_msg, .modal-body #error_msg').html('').hide();
                var status = $(this).parents('tr').data('status');
                var id = $(this).parents('tr').data('id');
                var update_status;
                if (status == 1) {
                    $('#status').html('確定要隱藏該公告');
                    update_status = 2
                } else if (status == 2) {
                    $('#status').html('確定要顯示該公告');
                    update_status = 1
                }
                $('#myModal .modal-title').html('狀態');
                $('#status').show();
                $('#myModal').modal();
                //送出修改
                $('#confirm_submit').unbind('click').click(function () {
                    $('#sh_btn').prop('disabled',true);
                    $('.modal-body #info .bg-danger').removeClass('bg-danger');
                    $.ajax({
                        method: 'POST',
                        url: '/Admin/News/ajaxStatusEdit',
                        data: {'id': id, 'status': update_status},
                        dataType: 'json',
                        beforeSend: function () {
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function (request) {
                            if (request.status) {
                                location.reload();
                            }
                            else {
                                if (request.code == '999') {
                                    $('.modal-body #error_msg').html('權限不足').show();
                                    $('#sh_btn').prop('disabled', false);
                                } else {
                                    $('.modal-body #error_msg').html('更新失敗').show();
                                    $('#sh_btn').prop('disabled', false);
                                }
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function (xhr) {
                            $('.load_mask_show').removeClass('load_mask_show');
                            $('#sh_btn').prop('disabled',false);
                        }
                    });
                });
            });

            //發送推播
            $('.notification').click(function () {
                $('.modal .modal-footer .btn').show();
                $('.modal .modal-footer #close').hide();
                $('.modal-body #success_msg, .modal-body #error_msg').html('').hide();
                var title = $(this).parents('tr').data('title');
                var id = $(this).parents('tr').data('id');
                $('#status').html('確定要發送 ' + title + ' 公告的推播');
                $('#myModal .modal-title').html('推播');
                $('#status').show();
                $('#myModal').modal();
                //送出推播
                $('#confirm_submit').unbind('click').click(function () {
                    $('#sh_btn').prop('disabled',true);
                    $('.modal-body #info .bg-danger').removeClass('bg-danger');
                    $.ajax({
                        method: 'POST',
                        url: '/Admin/News/ajaxNotification',
                        data: {'id': id},
                        dataType: 'json',
                        beforeSend: function () {
                            $('.load_mask').addClass('load_mask_show');
                        },
                        success: function (request) {
                            $('.modal .modal-footer .btn').hide();
                            $('.modal .modal-footer #close').show();
                            $('#status').html('').hide();
                            if (request.status) {
                                $('#status').html('').hide();
                                $('.modal-body #success_msg').html(request.data.message).show();
                            } else {
                                $('.modal-body #error_msg').html(request.data.message).show();
                            }
                            $('.load_mask_show').removeClass('load_mask_show');
                        },
                        error: function (xhr) {
                            $('#status').html('').hide();
                            $('.load_mask_show').removeClass('load_mask_show');
                        }
                    });
                });
            });
        }());
    });
</script>