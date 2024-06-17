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
                    <form method="post">
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
                        <tr class="paybtngray">
                            <td><button type="button" onclick="window.history.go(-1); return false;" name="search" value="" class="btn btn-info buttongray"><i class="fa fa-chevron-left valign-middle"></i> <?php echo $this->lang->line('back'); ?> </button></td>
                            <td><button type="button" onclick="pay()" name="search"  value="" class="btn btn-info buttondarkgray"><?php echo $this->lang->line('pay_with_razorpay'); ?> <i class="fa  fa-chevron-right valign-middle"></i></button></td>
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
<script src="<?php echo base_url(); ?>backend/custom/jquery.min.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script> 
<script>
function pay() {
  var SITEURL = "<?php echo base_url() ?>";
  var totalAmount = <?php echo $total; ?>;
  var product_id = <?php echo $merchant_order_id; ?>;
  var options = {
  "key": "<?php echo $key_id; ?>",
  "amount": "<?php echo $total; ?>", // 2000 paise = INR 20
  "currency": "<?php echo $params['currency_name']; ?>",
"order_id": "<?php echo $order_id; ?>",
  "image": "<?php echo base_url(); ?>uploads/school_content/admin_small_logo/<?php $this->setting_model->getAdminsmalllogo(); ?>",
            "handler": function (response) {
                $.ajax({
                    url: SITEURL + 'students/online_course/razorpay/callback',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        razorpay_payment_id: response.razorpay_payment_id, totalAmount: totalAmount, product_id: product_id,
                    },
                    success: function (msg) {
                        window.location.assign(SITEURL + 'students/online_course/razorpay/success')
                    }
                });
            },
            "theme": {
                "color": "#528FF0"
            }
        };
        console.log(options);
        var rzp1 = new Razorpay(options);
        rzp1.open();
};
</script>
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
</script>