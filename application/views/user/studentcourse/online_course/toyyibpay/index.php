<?php
$currency_symbol = $this->session->userdata('student')['currency_symbol'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="theme-color" content="#424242" />
        <title><?php echo $this->customlib->getSchoolName(); ?></title>
        <link href="<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo();?>" rel="shortcut icon" type="image/x-icon">
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/font-awesome.min.css"> 
        <link rel="stylesheet" href="<?php echo base_url(); ?>backend/dist/css/style-main.css">
		    <script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
    </head>
    <body class="bg-light-gray">
        <div class="container">
            <div class="row"> 
                <div class="paddtop20">
                    <div class="col-md-8 col-md-offset-2 text-center">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>">
                    </div>
                    <div class="col-md-6 col-md-offset-3 mt20">
                        <div class="paymentbg pb0 paymentbg-width">
                            <div class="invtext"><?php echo $this->lang->line('course_purchase_details'); ?></div>
                            <div class="">
                              <form action="<?php echo base_url(); ?>students/online_course/toyyibpay/pay" method="post">
                                <div class="img-container">
                                 <img src="<?php echo base_url(); ?>/uploads/course/course_thumbnail/<?php echo $params['course_thumbnail']; ?>" class="img-responsive center-block">
                                </div> 
                                <table class="table table-bordered table-hover mb0 paytable">
                                  <tr>
                                    <td width="40%" class="font-weight-bold">
                                      <?php echo $this->lang->line('title'); ?> 
                                    </td>
                                    <td width="40%">
                                      <?php echo $params['course_name']; ?>
                                    </td>
                                  </tr>
                                   <tr>
                                    <td class="font-weight-bold">
                                      <?php echo $this->lang->line('description'); ?>
                                    </td>
                                    <td>
										<div id="less_desc" class=''>
											<?php echo implode(' ', array_slice(explode(' ', $params['description']), 0, 10))."\n"; ?>			
										</div> 
										<div class="hide" id="more_desc">
											<?php echo $params['description']; ?>
										</div>										
										<?php if (strlen($params['description']) > 350) {?>
											<a id="hideid" class="btnplusview" ><i class="fa fa-angle-down angle-fa"></i> <?php echo $this->lang->line('view_more'); ?></a>
											
											<a id="showid" class="btnplusview hide" ><i class="fa fa-angle-up angle-fa"></i> <?php echo $this->lang->line('view_less'); ?></a>
										<?php }?>										
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="font-weight-bold">
                                      <?php echo $this->lang->line('amount'); ?> 
                                    </td>
                                    <td>
                                       <?php
                                         echo $currency_symbol;
                                        if(!empty($params['total_amount'])){ echo amountFormat($params['total_amount']);}else{echo '0.00';} ?>
                                    </td>
                                  </tr>         
                                    <tr>
                                      <td class="font-weight-bold">
                                        <label><?php echo $this->lang->line('phone'); ?></label>
                                      </td>
                                      <td>
                                        <input type="text" name="phone" class="form-control" value="<?php echo $params['contact_no']; ?>"> <span class="alert-danger"><?php echo form_error('phone'); ?></span>
                                      </td>
                                    </tr>
                                    <tr>
                                      <td class="font-weight-bold">
                                        <label><?php echo $this->lang->line('email'); ?></label>
                                      </td>
                                      <td>
                                        <input type="email" name="email" class="form-control" value="<?php echo $params['email']; ?>"> <span class="alert-danger"><?php echo form_error('email'); ?></span>
                                      </td>
                                    </tr>
                                      <?php if(!empty($fee_processing)){
                                      ?>
                                       <tr>
                                      <td class="font-weight-bold">
                                         <label><?php echo $this->lang->line('processing_payment'); ?></label>
                                      </td>
                                      <td>
                                       <label class="inline"><?php echo $fee_processing['note']; ?></label><a onclick="delete_fee('<?php echo $fee_processing['id']; ?>')" title="delete processing fee" class="btn btn-defoult btn-sm mt-n2"><i class="fa fa-remove font15 inline"></i></a>
                                      </td>
                                    </tr>
                                      <?php
                                    }?>
                                    <tr class="paybtngray">
                                      <td><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info buttongray"><i class="fa fa-chevron-left valign-middle"></i> <?php echo $this->lang->line('back'); ?> </button></td>
                                      <td><button type="submit"  name="search"  value="" class="btn btn-info buttondarkgray"><?php echo $this->lang->line('pay_with_toyyibpay'); ?> <i class="fa fa-chevron-right valign-middle"></i></button></td>
                                  </tr>    
                                </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </body>
</html>
<script>
(function ($) {
  "use strict";
    $("#hideid").click(function(){
        $("#hideid").addClass('hide');
        $("#less_desc").addClass('hide');
        $("#showid").removeClass('hide');
        $("#more_desc").removeClass('hide');
    });

    $("#showid").click(function(){
        $("#hideid").removeClass('hide');
        $("#less_desc").removeClass('hide');
        $("#showid").addClass('hide');
		$("#more_desc").addClass('hide');
    });
})(jQuery);

function delete_fee(id) {

        var checkstr =  confirm('are you sure you want to delete this?');
        if(checkstr == true){
           $.ajax({
            url: "<?php echo site_url("students/online_course/course_payment/deleteBygateway_ins_id/") ?>"+id,
            type: "POST",
            dataType: 'json',

            success: function (res)
            {
window.location.reload(true);
            }
        });
        }else{
        return false;
        }
       

    }
</script>