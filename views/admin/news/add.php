<div class="row">
    <div class="col-lg-12 form-horizontal">
        <div class="panel panel-flat">
            <div class="panel-body">
                <form method="post" action='/Admin/News/insert' id="send_form" name="send_form">
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">狀態</label>
                        <div class="col-lg-3">
                            <label class="radio-inline">
                                <input class="json" type="radio" id="status1" name="status" value="1" checked>顯示
                            </label>
                            <label class="radio-inline">
                                <input class="json" type="radio" id="status2" name="status" value="2">隱藏
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">推播</label>
                        <div class="col-lg-3">
                            <label class="radio-inline">
                                <input class="json" type="radio" id="notification_status1" name="notification_status" value="1">推
                            </label>
                            <label class="radio-inline">
                                <input class="json" type="radio" id="notification_status2" name="notification_status" value="2" checked>不推
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">群族</label>
                        <div class="col-lg-3">
                            <select class="bootstrap-select json" data-error="<?php if(!empty($error_data['type'])):?>1<?php endif?>" id="type" name="type" data-width="100%">
                                <option value="">請選擇</option>
                                <option value="1" <?php echo (isset($old['type']))?($old['type'] == 1)?"selected":'':''?>>一般用戶</option>
                                <option value="2" <?php echo (isset($old['type']))?($old['type'] == 2)?"selected":'':''?>>商業用戶</option>
                                <option value="3" <?php echo (isset($old['type']))?($old['type'] == 3)?"selected":'':''?>>瓦斯行</option>
                                <option value="4" <?php echo (isset($old['type']))?($old['type'] == 4)?"selected":'':''?>>物流士</option>
                                <option value="99" <?php echo (isset($old['type']))?($old['type'] == 99)?"selected":'':''?>>全部</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">標題</label>
                        <div class="col-lg-7">
                            <input class="form-control json <?php if(!empty($error_data['title'])):?>bg-danger<?php endif?>" type="text" id="title" name="title" value="<?php echo (isset($old['title']))?$old['title'] : ''?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-lg-1 text-right">內容</label>
                        <div class="col-lg-7">
                            <textarea  maxlength="250" class="form-control json <?php if(!empty($error_data['content'])):?>bg-danger<?php endif?>" id="content" name="content"><?php echo (isset($old['content']))?$old['content']:''?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit" id="sh_btn" class="btn btn-primary">新增<i class="icon-arrow-right14 position-right"></i></button>
                        </div>
                    </div>
                </form>
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
                }
                if( error > 0 ){
                    $('#sh_btn').prop('disabled',false);
                    return false;
                }
                return true;
            });
        }());
    });
</script>