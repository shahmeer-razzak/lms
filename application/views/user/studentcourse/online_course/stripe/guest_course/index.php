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
                              <form action="<?php echo base_url(); ?>students/online_course/stripe/guestcomplete" method="POST"> 
                                  <table class="table table-bordered table-hover mb0 paytable">
                                    <tr>
                                        <td width="40%" class="font-weight-bold">
                                            <?php echo $this->lang->line('course'); ?> 
                                        </td>
                                        <td width="40%" class="font-weight-bold text-right">
                                            <?php echo $this->lang->line('amount'); ?> 
                                        </td>
                                    </tr>

                                    <?php 
                                    $cartdata = $this->cart->contents();
                                    $cart_total = 0;
                                    foreach ($cartdata as  $value) {                                        
                                        $cart_total += $value['price'];
                                        ?>
                                    <tr>                                     
                                        <td width="40%">
                                            <?php echo $value['name']; ?>
                                        </td>
                                        <td class="text-right">
                                        <?php
                                        echo $currency_symbol;
                                        if(!empty($value['price'])){ echo amountFormat($value['price']); }else{echo '0.00';} ?>
                                        </td>                                    
                                    </tr>                                 
                                  
                                    <?php } ?>
                                    <tr>
                                        <th><?php echo $this->lang->line('total'); ?></th>
                                        <th class="text-right"><?php echo $currency_symbol.amountFormat($cart_total); ?></th>
                                    </tr>                                

                                    <input type="hidden" name="total_cart_amount" value="<?php echo $cart_total; ?>">

                                <tr class="paybtngray">   
                                  <td><button type="button" onclick="window.history.go(-1); return false;" name="search"  value="" class="btn btn-info buttongray"><i class="fa fa-chevron-left valign-middle"></i> <?php echo $this->lang->line('back')?></button></td>
                                  <td> 
                                     <script
                                        src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                        data-key="<?php echo $params['api_publishable_key']; ?>"
                                        data-amount="<?php echo convertBaseAmountCurrencyFormat($this->cart->total())*100; ?>"
                                        data-name="<?php echo $this->customlib->getSchoolName(); ?>"
                                        data-description=""
                                        data-image="<?php echo base_url('uploads/school_content/logo/' . $setting[0]['image']); ?>"
                                        data-locale="auto"
                                        data-zip-code="true"
                                        data-currency="<?php echo $currency_name; ?>"
                                        >
                                    </script>  
                                   </td>
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