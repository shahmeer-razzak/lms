<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('add_book'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('import_book', 'can_view')) {
                                ?>
                                <a class="btn btn-sm btn-primary" href="<?php echo base_url(); ?>admin/book/import" autocomplete="off"><i class="fa fa-plus"></i> <?php echo $this->lang->line('import_book'); ?></a> 
                            <?php }
                            ?>
                        </div>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <form id="form1" action="<?php echo site_url('admin/book/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body row">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php 
                                    echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg');
                                ?>
                            <?php } ?>
                            <?php
                            if (isset($error_message)) {
                                echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                            }
                            ?>      
                            <?php echo $this->customlib->getCSRF(); ?>                     
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?></label><small class="req"> *</small>
                                <input autofocus=""  id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>
                            
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('classification_number'); ?></label>
                                <input id="classification_no" name="classification_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('classification_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('classification_no'); ?></span>
                            </div>
                            
                             <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('sub_title'); ?></label>
                                <input id="sub_title" name="sub_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('sub_title'); ?>" />
                                <span class="text-danger"><?php echo form_error('sub_title'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_number'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text"  maxlength="13" class="form-control"  value="<?php echo set_value('isbn_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <input id="publish" name="publish" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publish'); ?>" />
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('edition'); ?></label>
                                <input id="edition" name="edition" placeholder="" type="text" class="form-control"  value="<?php echo set_value('edition'); ?>" />
                                <span class="text-danger"><?php echo form_error('edition'); ?></span>
                            </div>
                            
                            
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <input id="author" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author'); ?>" />
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>
                            
                          
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author_mark'); ?></label>
                                <input id="author_mark" name="author_mark" placeholder="" type="text" class="form-control" maxlength="3"  value="<?php echo set_value('author_mark'); ?>" />
                                <span class="text-danger"><?php echo form_error('author_mark'); ?></span>
                            </div>
                            
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('corporate_author'); ?></label>
                                <input id="corporate_author" name="corporate_author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('corporate_author'); ?>" />
                                <span class="text-danger"><?php echo form_error('corporate_author'); ?></span>
                            </div>
                            
                            
                            
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                <input id="subject" name="subject" placeholder="" type="text" class="form-control"  value="<?php echo set_value('subject'); ?>" />
                                <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('rack'); ?></label>
                                <input id="rack" name="rack" placeholder="" type="text" class="form-control"  value="<?php echo set_value('rack'); ?>" />
                                <span class="text-danger"><?php echo form_error('rack'); ?></span>
                            </div>
                          
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('place'); ?></label>
                                <input id="place" name="place" placeholder="" type="text" class="form-control"  value="<?php echo set_value('place'); ?>" />
                                <span class="text-danger"><?php echo form_error('place'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('accession_no'); ?></label>  <small class="req"> *</small>
                                <input id="accession_no" name="accession_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('accession_no'); ?>" />
                                <span class="text-danger"><?php echo form_error('accession_no'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publication_year'); ?></label>
                                <input id="publication_year" name="publication_year" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publication_year'); ?>" />
                                <span class="text-danger"><?php echo form_error('publication_year'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('volume'); ?></label>
                                <input id="volume" name="volume" placeholder="" type="text" class="form-control"  value="<?php echo set_value('volume'); ?>" />
                                <span class="text-danger"><?php echo form_error('volume'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('pages'); ?></label>
                                <input id="pages" name="pages" placeholder="" type="text" class="form-control"  value="<?php echo set_value('pages'); ?>" />
                                <span class="text-danger"><?php echo form_error('pages'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('item_type'); ?></label>
                                <select class="form-control"   id="item_type" name="item_type" value="<?php echo set_value('item_type'); ?>" >
  				<option value="Book">Book</option>
 				 <option value="CD">CD</option>
 				 <option value="Glob">Glob</option>
				</select>
                         
                                <span class="text-danger"><?php echo form_error('item_type'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_language'); ?></label>
                                <select class="form-control"   id="book_language" name="book_language" value="<?php echo set_value('book_language'); ?>" >
  				<option value="english">English</option>
 				 <option value="urdu">Urdu</option>
 				 <option value="Sindhi">Sindhi</option>
 				 <option value="Arabic">Arabic</option>
				</select>

                                <span class="text-danger"><?php echo form_error('book_language'); ?></span>
                            </div> 
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('vendor'); ?></label>
                                <input id="vendor" name="vendor" placeholder="" type="text" class="form-control"  value="<?php echo set_value('vendor'); ?>" />
                                <span class="text-danger"><?php echo form_error('vendor'); ?></span>
                            </div>
                            
                            
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="qty" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty'); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_price'); ?> (<?php echo $currency_symbol; ?>)</label>
                                <input id="perunitcost" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo set_value('perunitcost'); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('post_date'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', date($this->customlib->getSchoolDateFormat())); ?>" />
                                <span class="text-danger"><?php echo form_error('postdate'); ?></span>
                            </div>
                            
                        </div><!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div><!--/.col (right) -->
        </div>
        <div class="row">
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">
    $(document).ready(function () {
        $("#btnreset").click(function () {
            /* Single line Reset function executes on click of Reset Button */
            $("#form1")[0].reset();
        });

    });
</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });
    });
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>backend/dist/js/savemode.js"></script>
