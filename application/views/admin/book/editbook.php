<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-book"></i> <?php //echo $this->lang->line('library'); ?> </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('edit_book'); ?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->

                    <form id="form1" action="<?php echo site_url('admin/book/edit/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                        <div class="box-body">
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
                            <input  type="hidden" name="id" value="<?php echo set_value('id', $editbook['id']); ?>" >
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_title'); ?> <small class="req"> *</small></label>
                                <input autofocus="" id="book_title" name="book_title" placeholder="" type="text" class="form-control"  value="<?php echo set_value('book_title', $editbook['book_title']); ?>" />
                                <span class="text-danger"><?php echo form_error('book_title'); ?></span>
                            </div>
                           
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('classification_number'); ?></label>
                                <input id="_no" name="classification_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('classification_no', $editbook['classification_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('classification_no'); ?></span>
                            </div>
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('sub_title'); ?></label>
                                <input id="sub_title" name="sub_title" placeholder="" type="text" class="form-control" maxlength="3"  value="<?php echo set_value('sub_title', $editbook['sub_title']); ?>" />
                                <span class="text-danger"><?php echo form_error('author_mark'); ?></span>
                            </div>
                            
                           
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('isbn_number'); ?></label>
                                <input id="isbn_no" name="isbn_no" placeholder="" type="text" class="form-control"  maxlength="13" value="<?php echo set_value('isbn_no', $editbook['isbn_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('isbn_no'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publisher'); ?></label>
                                <input id="amount" name="publish" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publish', $editbook['publish']); ?>" />
                                <span class="text-danger"><?php echo form_error('publish'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('edition'); ?></label>
                                <input id="amount" name="edition" placeholder="" type="text" class="form-control"  value="<?php echo set_value('edition', $editbook['edition']); ?>" />
                                <span class="text-danger"><?php echo form_error('edition'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author'); ?></label>
                                <input id="amount" name="author" placeholder="" type="text" class="form-control"  value="<?php echo set_value('author', $editbook['author']); ?>" />
                                <span class="text-danger"><?php echo form_error('author'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('author_mark'); ?></label>
                                <input id="author_mark" name="author_mark" placeholder="" type="text" class="form-control" maxlength="3"  value="<?php echo set_value('author_mark', $editbook['author_mark']); ?>" />
                                <span class="text-danger"><?php echo form_error('author_mark'); ?></span>
                            </div>
                            
                            
                           
                            
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('corporate_author'); ?></label>
                                <input id="corporate_author" name="corporate_author" placeholder="" type="text" class="form-control" maxlength="3"  value="<?php echo set_value('corporate_author', $editbook['corporate_author']); ?>" />
                                <span class="text-danger"><?php echo form_error('author_mark'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('subject'); ?></label>
                                <input id="subject" name="subject" placeholder="" type="text" class="form-control"  value="<?php echo set_value('subject', $editbook['subject']); ?>" />
                                <span class="text-danger"><?php echo form_error('subject'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('rack'); ?></label>
                                <input id="rack" name="rack" placeholder="" type="text" class="form-control"  value="<?php echo set_value('rack', $editbook['rack']); ?>" />
                                <span class="text-danger"><?php echo form_error('rack'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('place'); ?></label>
                                <input id="place" name="place" placeholder="" type="text" class="form-control"  value="<?php echo set_value('place', $editbook['place']); ?>" />
                                <span class="text-danger"><?php echo form_error('place'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('accession_no'); ?></label>
                                <input id="accession_no" name="accession_no" placeholder="" type="text" class="form-control"  value="<?php echo set_value('accession_no', $editbook['accession_no']); ?>" />
                                <span class="text-danger"><?php echo form_error('accession_no'); ?></span>
                            </div>
                            
                          
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('publication_year'); ?></label>
                                <input id="publication_year" name="publication_year" placeholder="" type="text" class="form-control"  value="<?php echo set_value('publication_year', $editbook['publication_year']); ?>" />
                                <span class="text-danger"><?php echo form_error('publication_year'); ?></span>
                            </div>
                            
                            
                             <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('volume'); ?></label>
                                <input id="volume" name="volume" placeholder="" type="text" class="form-control"  value="<?php echo set_value('volume', $editbook['volume']); ?>" />
                                <span class="text-danger"><?php echo form_error('volume'); ?></span>
                            </div>
                            
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('pages'); ?></label>
                                <input id="pages" name="pages" placeholder="" type="text" class="form-control"  value="<?php echo set_value('pages', $editbook['pages']); ?>" />
                                <span class="text-danger"><?php echo form_error('pages'); ?></span>
                            </div>
                            
                            <div class="form-group col-md-6">
    				<label for="item_type">Item Type</label>
   		 <select class="form-control" id="item_type" name="item_type">
       			 <option value="Book" <?php echo set_select('item_type', 'Book', 															($editbook['item_type'] == 'Book')); ?>>Book</option>
			<option value="CD" <?php echo set_select('item_type', 'CD', 	($editbook['item_type'] == 'CD')); ?>>CD</option>
        		<option value="Glob" <?php echo set_select('item_type', 'Glob', ($editbook['item_type'] == 'Glob')); ?>>Glob</option>
    		</select>
    			<span class="text-danger"><?php echo form_error('item_type'); ?></span>
				</div>

                          
                            <div class="form-group col-md-6">
    			<label for="book_language"><?php echo $this->lang->line('book_language'); ?></label>
   		<select class="form-control" id="book_language" name="book_language">
        		<option value="english" <?php echo set_select('book_language', 'english', ($editbook['book_language'] == 'english')); ?>>English</option>
        		<option value="urdu" <?php echo set_select('book_language', 'urdu', ($editbook['book_language'] == 'urdu')); ?>>Urdu</option>
        		<option value="Sindhi" <?php echo set_select('book_language', 'Sindhi', ($editbook['book_language'] == 'Sindhi')); ?>>Sindhi</option>
        		<option value="Arabic" <?php echo set_select('book_language', 'Arabic', ($editbook['book_language'] == 'Arabic')); ?>>Arabic</option>
    		</select>
   			<span class="text-danger"><?php echo form_error('book_language'); ?></span>
</div>
                            
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('vendor'); ?></label>
                                <input id="vendor" name="vendor" placeholder="" type="text" class="form-control"  value="<?php echo set_value('vendor', $editbook['vendor']); ?>" />
                                <span class="text-danger"><?php echo form_error('vendor'); ?></span>
                            </div>
                            
                            
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('qty'); ?></label>
                                <input id="amount" name="qty" placeholder="" type="text" class="form-control"  value="<?php echo set_value('qty', $editbook['qty']); ?>" />
                                <span class="text-danger"><?php echo form_error('qty'); ?></span>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('book_price'); ?> (<?php echo $currency_symbol; ?>)</label>
                                <input id="amount" name="perunitcost" placeholder="" type="text" class="form-control"  value="<?php echo convertBaseAmountCurrencyFormat($editbook['perunitcost']); ?>" />
                                <span class="text-danger"><?php echo form_error('perunitcost'); ?></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('post_date'); ?></label>
                                <input id="postdate" name="postdate"  placeholder="" type="text" class="form-control date"  value="<?php echo set_value('postdate', $this->customlib->dateformat($editbook['postdate'])); ?>" />
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
            <!-- left column -->
            <!-- right column -->
            <div class="col-md-12">
            </div><!--/.col (right) -->
        </div>   <!-- /.row -->
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

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
