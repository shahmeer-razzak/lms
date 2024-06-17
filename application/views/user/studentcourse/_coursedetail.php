<?php if (!empty($coursesList)) {
    $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
    $student_data    = $this->customlib->getLoggedInUserData();
    $student_img     = $student_data["image"];
    $free_course     = $coursesList['free_course'];
    $discount        = $coursesList['discount'];
    $price           = $coursesList['price'];
    $discount_price  = '';
    if ($discount != '0.00') {
        $discount_price = amountFormat($price - $price * $discount / 100, 2);
    }	
	
    ?>
    <div class="flex-row row row-eq"> 
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="whatyou coursebox-body mbDM15">
                <?php if (!empty($coursesList['course_url'])) { ?>
				<div class="coursebox mb0">	
                    
        			<?php	if ($coursesList['course_provider'] == "html5") {	?>   
        			<div class="course-video-height"> 		
        				<video id="videoPlayer" controls>
        					<source src="<?php echo $coursesList['course_url']; ?>" type="video/mp4">
        				</video>	
                    </div>        				
        			<?php	} elseif ($coursesList['course_provider'] == "youtube") {	?>    
                    <div class="course-video-height">        
        				<iframe width="100%" src="//www.youtube.com/embed/<?php echo $coursesList['video_id']; ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
                    </div>          				
                    <?php	} elseif ($coursesList['course_provider'] == "vimeo") { 	?>                        
                    <iframe src="<?php echo $coursesList['course_url']; ?>" width="600" height="400" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>       				
                    <?php	}elseif ($lesson['video_provider'] == "s3_bucket") { ?>       				
                    <video controls width="100%">
                        <source src="<?php echo $lesson['s3_url'] ?>">
                    </video>        				
                    <?php } ?>                     
				</div>				
                <?php } else {?>
                <div class="coursebox mb0">
                    <div class="coursebox-img">
                       <img src="<?php echo base_url(); ?>uploads/course/course_thumbnail/<?php echo $coursesList['course_thumbnail']; ?>" class="img-responsive">
                    </div>   
                </div>
                <?php }?>
            </div>
        </div><!--./col-lg-7-->
        <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 row-eq">
            <div class="whatyou coursebox-body relative">
                <div class="author-block-center text-center">                
                    <?php 
                    if ($role == 'student' || $role == 'parent'){
                        if (!empty($coursesList['image'])  ) {?>
                            <img class="img-circle" src="<?php echo base_url(); ?>uploads/staff_images/<?php echo $coursesList['image']; ?>" alt="User Image">
                        <?php } else {
                            if($coursesList['gender']=='Female'){
                                $file= "uploads/staff_images/default_female.jpg";
                            }else{
                                $file ="uploads/staff_images/default_male.jpg";
                            }
                        ?>
                            <img class="img-circle" src="<?php echo base_url(); ?><?php echo $file; ?>" alt="">
                        <?php } 
                    } ?>                    
                    
                    <?php if($role == 'student' || $role == 'parent'){ ?>
                        <span class="authornamebig"><?php echo $coursesList['staff_name'].' '.$coursesList['staff_surname']; ?> (<?php echo $coursesList['assign_employee_id'];?>)</span>
                    <?php }else{ ?>
                        <span class="authornamebig"><br></span>
                    <?php } ?>
                    
                    <?php if($role == 'student' || $role == 'parent'){ ?>
                    <span class="descriptionbig"><?php echo $this->lang->line('last_updated'); ?> <span> <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($coursesList['updated_date'])); ?> </span></span>
                    <?php } ?>
                    
                </div>
                <ul class="lessonsblock ptt10">
                    <?php if($role == 'student' || $role == 'parent'){ ?>
                    <li><i class="fa fa-list-alt"></i><?php echo $this->lang->line('class'); ?> - <?php echo $coursesList['class']; ?> (<?php echo rtrim($section, ", "); ?>)</li>
                    <?php } ?>
                    
                    <?php if ($lesson_count !='' && $lesson_count !='0') { ?>
                    <li>
                        <i class="fa fa-play-circle"></i><?php echo $this->lang->line('lesson') . " " . $lesson_count; ?>
                    </li>
                    <?php } ?>
                    <?php if ($quiz_count !='' && $quiz_count !='0') { ?>       
                    <li>
                        <i class="fa fa-question-circle"></i><?php echo $this->lang->line('quiz') . " " . $quiz_count; ?>
                    </li>
                    <?php } ?>
                    <?php if (!empty($total_hour_count) && $total_hour_count != '00:00:00') { ?>
                    <li>
                        <i class="fa fa-clock-o"></i><?php echo $total_hour_count ." ".$this->lang->line('hrs'); ?>
                    </li>
                    <?php } ?>
                    <li>					
                    
                    <?php if($paidstatus != '1'){ if ($free_course == '1' ) {
                        echo $this->lang->line('free');
                    } else {
                        if (!empty($discount_price)) {
                           echo $currency_symbol . ' ' . $discount_price;?>
                           <del><?php echo $currency_symbol . ' ' . amountFormat($price); ?></del>
                    <?php } else {
                           echo $currency_symbol . '' . amountFormat($price);?>
                    <?php }} } ?>

                    <?php if($paidstatus == '1'){ ?>                    
                    <?php if ($loginsession['role'] != 'parent') {  ?>
                            <button data-backdrop="static" data-id="<?php echo $coursesList['id']; ?>" class="btn btn-primary print_btn btn-xs valign-text-bottom"><i class="fa fa-print pr0"></i> <?php echo $this->lang->line('print_invoice'); ?></button>
                    <?php }else{}?>                 
                    <?php } ?> 
                    
                    </li>
                    <li>
                     <small>
                        <?php if($courserating !=0 || $courserating !=''){ ?>
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <span class="fa fa-star" <?php if ($courserating >= $i) { ?> style="color:orange;"<?php } ?>></span>
                            <?php } ?>  
        
                            <?php if($totalcourserating !=0){ echo ' ('.$totalcourserating.' '.$this->lang->line('rating').')'; } ?>
                        <?php } ?> 
                 
                        <?php 
                        
                            $course_id      =   $coursesList['id'] ;
                            $rate_this      =   $this->lang->line('rate_this'); 
                            
                            $rate_btn = '<span class="pull-right"><a class="ratethis" href="#addRatingModal" data-course-id='.$course_id.' data-toggle="modal" >'.$rate_this.'</a></span>';                
                            
                            if ($loginsession['role'] != 'parent') {  
                                if ($free_course == '1') { 
                                    echo $rate_btn; 
                                } else {  
                                    if($paidstatus == '1'){ 
                                        echo $rate_btn; 
                                    }else{ 
                                        if(!empty($courseprogresscount)){ 
                                            echo $rate_btn; 
                                        } 
                                    } 
                                } 
                            } 
                        ?>            

                    </small>
                </li>
                    <?php if($role == 'guest'){ ?>
                    <span class="descriptionbig"><?php echo $this->lang->line('last_updated'); ?> <span>
                    <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($coursesList['updated_date'])); ?> </span></span>
                    <?php } ?>
                </ul>
                
                <div class="coursebtnfull">                 
                <?php                 
                
                $start_lesson   =   $this->lang->line('start_lesson');
                $btn = '<a href="#" class="btn btn-add-full lesson_ID_detail" data-toggle="modal" data-target="#coursemodal" lesson-data='.$course_id.' >'.$start_lesson.'</a>';                
                
                if ($loginsession['role'] != 'parent') {  
                    if ($free_course == '1') { 
                        echo $btn; 
                    } else {  
                        if($paidstatus == '1'){ 
                            echo $btn; 
                        }else{ 
                            if(!empty($courseprogresscount)){ 
                                echo $btn; 
                            }else{ 
                                if(!empty($paymentgateway)){
                                     ?>                              

                                        <a href="<?php echo base_url(); ?>students/online_course/Course_payment/payment/<?php echo $coursesList['id']; ?>" class="btn btn-add-full"><?php echo $this->lang->line('buy_now'); ?> 
                                        
                                        <?php if ($free_course == '1') {
                                            echo $this->lang->line('free');
                                        } else {
                                            if (!empty($discount_price)) {
                                                echo $currency_symbol . ' ' . $discount_price;?>
                                                <del><?php echo $currency_symbol . ' ' . amountFormat($price); ?></del>
                                            <?php } else {
                                                echo $currency_symbol . '' . amountFormat($price);?>
                                            <?php }}?>   
                                        </a>
                                    <?php  } ?>
                                					
                            <?php } ?>
                        <?php } 
                    } ?>                   
                <?php } ?>
                    
                </div>   
            </div>    
        </div><!--./col-lg-5-->
     </div><!--./detailmodalbg-->  
 <div class="flex-row row mt10">     
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="coursecard whatyou">
            <h3 class="modal-title pb3 fontmedium"><?php echo $coursesList['title']; ?></h3>
            <p><?php echo $coursesList['description']; ?>.</p>
        </div>
    </div><!--./col-lg-9-->
    </div><!--./row-->

 <div class="row">     
<?php }?>
<?php if (!empty($sectionList)) { ?>
    <?php 
		$outcomes = json_decode($coursesList['outcomes']);
        $check_empty = '';
        if (array_filter($outcomes)) {
            $check_empty = $outcomes;
        } else {
            $check_empty = '';
        }
    ?>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <?php if (!empty($check_empty)){ ?>
        <div class="coursecard whatyou">
			<h3 class="fontmedium"><?php echo $this->lang->line('what_will_i_learn'); ?></h3>
			<?php $outcomes = json_decode($coursesList['outcomes']); ?>
			<ul class="whatlearn">
				<li>
					<?php foreach ($outcomes as $outcomes_value) {?>
					<?php echo $outcomes_value; ?>
					<?php }?>
				</li>
			</ul>
        </div><!--./coursecard-->
        <?php } ?>
        <div class="coursecard ptt10">
            <h4 class="fontmedium"><?php echo $this->lang->line('curriculum_for_this_course'); ?> </h4>
            <div class="panel-group faq mb10" id="accordionplus">
				<div class="panel panel-info">
				<?php $lessoncount=0; $quizcount=0; $sectioncount=0;
                foreach ($sectionList as $sectionList_key => $sectionList_value) {  $sectioncount = $sectioncount+1;?>
                <?php $sectionID = $sectionList_value->id;?>
					<div class="panel-heading" data-toggle="collapse" data-parent="#accordionplus" data-target="#<?php echo $sectionList_key; ?>" aria-expanded="true">
						<h4 class="panelh3 accordion-togglelpus"><?php echo "<b>".$this->lang->line('section').' '. $sectioncount.'</b>: '. $sectionList_value->section_title; ?><span class="mr0"><i class="fa fa-play-circle"></i><?php if (!empty($sectionList_value->total_lessons)) {echo $sectionList_value->total_lessons;}?> <?php echo $this->lang->line('lesson'); ?></span></h4>
					</div>
					<div id="<?php echo $sectionList_key; ?>" class="panel-collapse collapse in" aria-expanded="true">						
						<ul class="introlist">
                            <?php if (!empty($lessonquizdetail[$sectionID])) { ?>
                            <?php foreach ($lessonquizdetail[$sectionID] as $lessonquizdetail_value) {
                            if ($lessonquizdetail_value['type'] == 'lesson') { $lessoncount = $lessoncount+1; ?>
                            <?php if($lessonquizdetail_value['type'] !=''){ ?> 
                                <li><i class="fa fa-play-circle"></i><?php echo "<b>".$this->lang->line($lessonquizdetail_value['type'])." ".$lessoncount.": "."</b>". $lessonquizdetail_value['lesson_title']; ?><span><?php if($lessonquizdetail_value['lesson_type'] == 'video'){ echo $lessonquizdetail_value['duration']; } ?></span></li>
                            <?php } ?>                      
                            <?php }else{ $quizcount = $quizcount+1; ?>
                            <?php if($lessonquizdetail_value['type'] !=''){ ?>
                                <li><i class="fa fa-question-circle"></i><?php echo "<b>".$this->lang->line($lessonquizdetail_value['type'])." ".$quizcount.": "."</b>". $lessonquizdetail_value['quiz_title']; ?></li>
                            <?php } ?>                       
                            <?php } } }?>
                        </ul>						
					</div><!--#/collapseOne-->
					<?php }?>
				</div><!--./panel-info-->
            </div><!--./panel-group-->
        </div><!--./coursecard-->
    </div><!--./col-lg-8-->
  </div><!--./row-->  
<?php }?>

<?php if(!empty($coursereview)){  ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12"><h4 class="fontmedium mb0"><?php echo $this->lang->line('reviews'); ?></h4></div>
    <div class="col-lg-12 col-md-12 col-sm-12">
        <div class="imgbottomtext">
        <?php foreach ($coursereview as $coursereview_value) { 
  
            if($coursereview_value['image'] !=''){
                if($coursereview_value['guest_id'] > 0){                    
                    $student_img = "uploads/guest_images/".$coursereview_value['image'];                                      
                }else{                   
                    $student_img = $coursereview_value['image'];                   
                }                
            }else{
                if ($coursereview_value['gender'] == 'Female') {
                    $student_img = "uploads/student_images/default_female.jpg";
                } elseif ($coursereview_value['gender'] == 'Male') {
                    $student_img = "uploads/student_images/default_male.jpg";
                }else{
                    $student_img = "uploads/student_images/no_image.png";  
                }                
            }
        ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover mb0">
                <tr>
                    <td width="5%"><img src="<?php echo base_url(); ?><?php echo $student_img; ?>" width="40" height="35"></td>
                    <td width="10%">
                        <div><?php echo $this->customlib->dateformat($coursereview_value['date']); ?></div>
                        <div><?php 
                                echo $coursereview_value['rating_provider_name']; 
                                if($coursereview_value['lastname'] != 'null'){
                                    echo ' '.$coursereview_value['lastname'];
                                };?>
                        </div>
                    </td>
                    <td width="85%">
                        <div>
                            <?php   if($coursereview_value['rating'] !=0 || $coursereview_value['rating'] !=''){ ?>
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <span class="fa fa-star disable" <?php if ($coursereview_value['rating'] >= $i) { ?> style="color:orange;"<?php } ?>></span>
                                <?php } ?>  
                            <?php } ?>
                        </div>
                        <div><?php echo $coursereview_value['review']; ?></div>
                    </td>         
                </tr>
            </table>
        </div> 
        <?php } ?>
        </div> 
    </div>
</div>
<?php } ?>