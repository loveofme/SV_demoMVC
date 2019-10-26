<div class="row">
    <div class="col-lg-12 form-horizontal">
        <div class="panel panel-flat">
            <div class="panel-body">
                <form method="post" action='/Admin/News/update' id="send_form" name="send_form">
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">狀態</label>
                        <div class="col-lg-3">
                            <label class="radio-inline">
                                <input class="json" type="radio" id="status1" name="status" value="1" <?php echo ($data['status'] == 1)?"checked":''?>>啟用
                            </label>
                            <label class="radio-inline">
                                <input class="json" type="radio" id="status2" name="status" value="2" <?php echo ($data['status'] == 2)?"checked":''?>>停用
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">群族</label>
                        <div class="col-lg-3">
                            <select class="bootstrap-select json" data-error="<?php if(!empty($error_data['type'])):?>1<?php endif?>" id="type" name="type" data-width="100%">
                                <option value="">請選擇</option>
                                <option value="1" <?php echo ($data['type'] == 1)?"selected":''?>>一般用戶</option>
                                <option value="2" <?php echo ($data['type'] == 2)?"selected":''?>>商業用戶</option>
                                <option value="3" <?php echo ($data['type'] == 3)?"selected":''?>>瓦斯行</option>
                                <option value="4" <?php echo ($data['type'] == 4)?"selected":''?>>物流士</option>
                                <option value="99" <?php echo ($data['type'] == 99)?"selected":''?>>全部</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">標題</label>
                        <div class="col-lg-7">
                            <input class="form-control json <?php if(!empty($error_data['title'])):?>bg-danger<?php endif?>" type="text" id="title" name="title" value="<?php echo (isset($data['title']))?$data['title'] : ''?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">內容</label>
                        <div class="col-lg-7">
                            <textarea  maxlength="250" class="form-control json" id="content" name="content"><?php echo $data['content']?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <input type="hidden" id="id" name="id" class="json" value="<?php echo $data['id'];?>">
                            <button type="submit" id="sh_btn" class="btn btn-primary">修改<i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </form>
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
                <div id="error_msg" class="bg-danger-600 text-center">
                </div>
                <div id="success_msg" class="bg-blue-600 text-center">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirm_submit" class="btn btn-primary">確認</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function () {
        (function () {
            $('.bootstrap-select').selectpicker();

            if( $('#type').data('error') == 1 ) {
                $('#type').selectpicker('setStyle', 'btn-danger', 'add');
                $('#type').selectpicker('setStyle', 'btn-default', 'remove');
            }
            //標題
            $('#title').focus(function(){
                if($(this).hasClass('bg-danger'))
                {
                    $(this).removeClass('bg-danger');
                }
            });
            //內容
            $('#content').focus(function(){
                if($(this).hasClass('bg-danger'))
                {
                    $(this).removeClass('bg-danger');
                }
            });
            //送出表單
            $('#send_form').submit(function () {
                $('#sh_btn').prop('disabled',true);
                var error = 0;
                var form_data = form.getdata('#send_form', '.json');
                if(form_data['type'] == ''){
                    $('#type').selectpicker('setStyle', 'btn-default', 'remove');
                    $('#type').selectpicker('setStyle', 'btn-danger', 'add');
                    error++;
                }
                if(form_data['title'] == '')
                {
                    $('#title').addClass('bg-danger');
                    error++;
                }
                if(form_data['content'] == '')
                {
                    $('#content').addClass('bg-danger');
                    error++;
                }
                if( error > 0 ){
                    $('#sh_btn').prop('disabled',false);
                    return false;
                }
                return true;
            });

            <?php if(!empty($SYSTEM_ERROR)):?>
                //修改狀態
                $('.modal-body #success_msg, .modal-body #error_msg').html('').hide();
                $('#myModal .modal-title').html('錯誤警告');
                $('.modal-body #error_msg').html('<?php echo $SYSTEM_ERROR['message']?>').show();
                $('#myModal').modal();
                //送出修改
                $('#confirm_submit').unbind('click').click(function () {
                    window.location = '<?php echo $SYSTEM_ERROR['url']?>';
                });
            <?php endif?>
        }());
    });
</script>