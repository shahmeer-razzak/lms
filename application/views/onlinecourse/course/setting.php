<?php $this->load->view('layout/course_css.php'); ?>
<div class="content-wrapper">   
    <section class="content-header">
        <h1> </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <?php 
            if (!$this->rbac->hasPrivilege('online_course_setting', 'can_view')) {
                access_denied();
            } 
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('s3_bucket_setting'); ?></h3>
                    </div>
					<div class="around10">
                            <?php if ($this->session->flashdata('msg_aws')) { ?>
                                <?php echo $this->session->flashdata('msg_aws') ?>
                            <?php } ?>
                    </div>
                    <div class="box-body">

                       
                        <form action="<?=site_url();?>onlinecourse/course/setting" method="post" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="api_key"> <?php echo $this->lang->line('access_key_id'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control col-md-7 col-xs-12" name="api_key" id="api_key" value="<?= set_value('api_key', isset($aws_setting->api_key)?$aws_setting->api_key:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('api_key'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="api_secret"> <?php echo $this->lang->line('secret_access_key'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="password" class="form-control col-md-7 col-xs-12" name="api_secret" id="api_secret" value="<?= set_value('api_secret', isset($aws_setting->api_secret)?$aws_setting->api_secret:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('api_secret'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bucket_name"> <?php echo $this->lang->line('bucket_name'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control col-md-7 col-xs-12" name="bucket_name" id="bucket_name" value="<?= set_value('bucket_name', isset($aws_setting->bucket_name)?$aws_setting->bucket_name:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('bucket_name'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="bucket_name"> <?php echo $this->lang->line('region'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control col-md-7 col-xs-12" name="region" id="region" value="<?= set_value('region', isset($aws_setting->region)?$aws_setting->region:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('region'); ?></span>
                                    </div>
                                </div>                            
                            </div>     
                   
                            <div class="box-footer">
                                <div class="col-md-6 col-sm-6 col-xs-6 col-md-offset-3">                              
                                    <button type="submit" name="setting_btn" value="aws" class="btn btn-info pull-left btnleftinfo-2"><?php echo $this->lang->line('save'); ?></button>                                                         
                                </div>                                 
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
        
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i><?php echo $this->lang->line('guest_user'); ?> </h3>
                    </div>
					<div class="around10">
                            <?php if ($this->session->flashdata('msg_course')) { ?>
                                <?php echo $this->session->flashdata('msg_course') ?>
                            <?php } ?>
                    </div>
                    <div class="box-body">
                        <form action="<?=site_url();?>onlinecourse/course/setting" method="post" class="form-horizontal form-label-left">
                            <div class="row">
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="api_key"><?php echo $this->lang->line('guest_user_prefix'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control col-md-7 col-xs-12" name="guest_prefix" id="guest_prefix" value="<?= set_value('guest_prefix', isset($course_setting->guest_prefix)?$course_setting->guest_prefix:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('guest_prefix'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="api_key"><?php echo $this->lang->line('guest_user_id_start_from'); ?><small class="req"> * </small></label>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <input type="text" class="form-control col-md-7 col-xs-12" name="guest_id_start_from" id="guest_id_start_from" value="<?= set_value('guest_id_start_from', isset($course_setting->guest_id_start_from)?$course_setting->guest_id_start_from:"");?>" autocomplete="off">
                                        <span class="text-danger"><?php echo form_error('guest_id_start_from'); ?></span>
                                    </div>
                                </div>                             
                            </div>                  
                            <div class="box-footer">
                                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">                              
                                    <button type="submit" name="setting_btn" value="course" class="btn btn-info pull-left btnleftinfo-2"><?php echo $this->lang->line('save'); ?></button>                                                         
                                </div>                                
                            </div>
                            <div class="pull-right"><?php echo $this->lang->line('version') . " " . $version; ?></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
