<?php 
$student_allover_rank=[];
$subject_rank = [];
//Bio Monthly
foreach ($result as $student_key => $student_value) {
  $total_max_marks=0;
$total_gain_marks=0;

foreach ($student_value['term']['exams'] as $student_exam_key => $student_exam_value) {
  foreach ($student_exam_value['subjects'] as $subject_key => $subject_value) {
            $subject_total=0;
            $subject_max_total=0;

foreach ($subject_value['exam_assessments'] as $assessment_key => $assessment_value) {
    $subject_total+=$assessment_value['marks'];
    $subject_max_total+=$assessment_value['maximum_marks'];

    $total_gain_marks+=$assessment_value['marks'];
    $total_max_marks+=$assessment_value['maximum_marks'];

}
       if (!array_key_exists($subject_key, $subject_rank)) {
            $subject_rank[$subject_key] = [];
        }

        $subject_rank[$subject_key][] = [
            'student_session_id' => $student_value['student_session_id'],
            'rank_percentage'    => $subject_total,
            'rank'=>0

        ];     
  
  }  

}

 $exam_percentage=getPercent($total_max_marks,$total_gain_marks);

  $student_allover_rank[$student_value['student_session_id']]=[
      'student_session_id'=>$student_value['student_session_id'],
      'firstname'=>$student_value['firstname'],
      'rank_percentage'=>$exam_percentage,
      'rank'=>0,
  ];
 
}

//-=====================start term calculation Rank=============

$rank_overall_percentage_keys = array_column($student_allover_rank, 'rank_percentage');

 array_multisort($rank_overall_percentage_keys, SORT_DESC, $student_allover_rank);

$term_rank_allover_list=unique_array($student_allover_rank, "rank_percentage");

foreach ($student_allover_rank as $term_rank_key => $term_rank_value) {
 
   $student_allover_rank[$term_rank_key]['rank']=array_search($term_rank_value['rank_percentage'],$term_rank_allover_list);
   
}

//-=====================end term calculation Rank=============

foreach ($subject_rank as $subject_term_key => $subject_term_value) {

    $rank_overall_subject = array_column($subject_rank[$subject_term_key], 'rank_percentage');

    array_multisort($rank_overall_subject, SORT_DESC,$subject_rank[$subject_term_key]);

    $subject_rank_allover_list=unique_array($subject_rank[$subject_term_key], "rank_percentage");

foreach ($subject_rank[$subject_term_key] as $subject_rank_key => $subject_rank_value) {

    $subject_rank[$subject_term_key][$subject_rank_key]['rank']=array_search($subject_rank_value['rank_percentage'],$subject_rank_allover_list);
    
}

}
             ?>

<?php 

$count_result=count($result);
$student_increment=0;

foreach ($result as $student_key => $student_value) {
    $student_increment++;
  $total_max_marks=0;
$total_gain_marks=0;
  ?>

<!DOCTYPE html>
<html lang="en">
<head> 
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

</head>
<body>
<div style="width: 100%; margin: 0 auto;">
  <?php
          
if($template['header_image'] != ""){
  ?>
   
          <img width= "100%" max-width= "100%" src="<?php echo base_url("/uploads/cbseexam/template/header_image/". $template['header_image']) ?>" />
             
  <?php
}
           ?>
  <table cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td valign="top" style="padding-bottom: 0px; padding-top: 90; width: 100%; font-weight: bold; text-align: center; font-size:20px;">
            <?php echo $this->lang->line('report_card'); ?>
            </td>
          </tr> 
          <tr>
            <td valign="top" style="padding-bottom: 20px; padding-top: 2px; width: 100%;font-weight: bold; text-align: center; font-size:15px;">
             <?php echo $this->lang->line('academic_session'); ?> : <?php echo $current_setting['session'];?>
             
            </td>
          </tr>

        </table>
      </td>
    </tr> 
    <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr>
      <td valign="top">
        <table cellpadding="0" cellspacing="0" width="100%">
          <tr>
            <td valign="top" width="80%">
              <table cellpadding="0" cellspacing="0" width="100%">
             <tr>
                <?php 
                  if($template['is_admission_no']){
                    ?>
                  <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('admission_no'); ?>.</td>
                  <td valign="top">: <?php echo $student_value['admission_no']; ?></td>
                          <?php
                  }
                  ?>
               <?php 

                  if($template['is_roll_no']){
                    ?>
                  <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('roll_no'); ?></td>
                  <td valign="top">: <?php echo $student_value['roll_no']; ?></td>
                    <?php
                  }
                  ?>                

                </tr>
                <tr>
                  <?php 

                  if($template['is_name']){
                    ?>
                  <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('students_name'); ?></td>
                  <td valign="top">: <?php echo   $this->customlib->getFullName($student_value['firstname'], $student_value['middlename'], $student_value['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?></td>

                    <?php
}
 ?>
   <?php 
                  if($template['is_dob']){
                    ?>
                  <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('date_of_birth'); ?></td>
                  <td valign="top">: <?php echo $this->customlib->dateformat($student_value['dob']); ?></td>

                          <?php
                  }
                  ?>            
                </tr>
                <tr>
                    <?php 

                  if($template['is_father_name']){
                    ?>
                  <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('fathers_name'); ?></td>
                  <td valign="top">:  <?php echo $student_value['father_name']; ?> </td>

                          <?php
                  }
                  ?>
                                      <?php 

                  if($template['is_mother_name']){
                    ?>
    <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('mothers_name'); ?></td>
                  <td valign="top">: <?php echo $student_value['mother_name']; ?></td>
                          <?php
                  }
                  ?>                 

                </tr>
                <tr>
                                  
                    <?php 

                  if($template['is_class'] && $template['is_section']){
                    ?>
 <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('class_section'); ?></td>
                  <td valign="top">: <?php echo $student_value['class']." (".$student_value['section'].")"; ?></td>
                          <?php
                  }else if($template['is_class']){
                    ?>
 <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('class_section'); ?></td>
                  <td valign="top">: <?php echo $student_value['class']; ?></td>
                          <?php
                  }
                  else if($template['is_section']){
                    ?>
 <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('class_section'); ?></td>
                  <td valign="top">: <?php echo $student_value['section']; ?></td>
                          <?php
                  }
                  ?> 
                <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('school_name'); ?></td>
                  <td valign="top">: <?php echo $template['school_name']?></td>                
                 
                </tr>
                <tr><td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('exam_center'); ?></td>
                  <td valign="top">:  <?php echo $template['exam_center']?></td> 
                  
                <td valign="top" style="font-weight: bold; padding-bottom: 2px"><?php echo $this->lang->line('printing_date'); ?></td>
                <td valign="top">: <?php echo $this->customlib->dateformat($template['date']); ?></td>                
                         
                </tr>
              </table>
            </td>
            <?php 
                  if($template['is_photo']){
                    ?>
    
         <td valign="top" align="right" width="20%">
             <?php

                if (!empty($student_value["student_image"])) {
                $student_image=base_url() . $student_value["student_image"];
                } else {
                    if ($student_value['gender'] == 'Female') {
                        $student_image=base_url() . "uploads/student_images/default_female.jpg";
                    } elseif ($student_value['gender'] == 'Male') {
                        $student_image=base_url() . "uploads/student_images/default_male.jpg";
                    }
                }
                ?>
              <img src="<?php echo $student_image; ?>" width="85" height="100" style="border:1px solid #000">
            </td>
                          <?php
                  }
                  ?>
          </tr>
        </table>
      </td>
    </tr>
        </table>
      </td>
    </tr>
    <tr><td valign="top" style="height:10px"></td></tr>
    <tr>
      <td valign="top">
     <table cellpadding="0" cellspacing="0" width="100%" class="denifittable">
          <thead>         
          <tr>
            <td valign="middle" ><?php echo $this->lang->line('subject'); ?></td>
            <?php 

foreach ($student_value['term']['exams'] as $exam_key => $exam_value) {
  
reset($exam_value['subjects']);
$subject_first_key = key($exam_value['subjects']);
  foreach ($exam_value['subjects'][$subject_first_key]['exam_assessments'] as $subject_assesment_key => $subject_assesment_value) {
   
    ?> <td valign="middle" class="text-center">
      <?php 
      echo $subject_assesment_value['cbse_exam_assessment_type_name']."-"." (".$subject_assesment_value['cbse_exam_assessment_type_code'].")";
      echo "<br/>";
       echo $subject_assesment_value['maximum_marks'] ;
       ?></td><?php
  }
}
             ?>          
     
            <!--<td valign="middle" class="text-center"><?php echo $this->lang->line('total'); ?></td>          
            <td valign="middle" class="text-center"><?php echo $this->lang->line('grade'); ?></td>
            <td valign="middle" class="text-center"><?php echo $this->lang->line('rank'); ?></td>-->
          </tr>  
        </thead>
        <tbody>

               <?php 

foreach ($student_value['term']['exams'] as $student_exam_key => $student_exam_value) {
  foreach ($student_exam_value['subjects'] as $exam_key => $exam_value) {    
    ?>
<tr>
            <td valign="top"><?php echo $exam_value['subject_name']." (".$exam_value['subject_code'].")"; ?>
            </td>
            <?php 
            $subject_total=0;
            $subject_max_total=0;
foreach ($exam_value['exam_assessments'] as $assessment_key => $assessment_value) {
    $subject_total+=$assessment_value['marks'];
    $subject_max_total+=$assessment_value['maximum_marks'];
    $total_gain_marks+=$assessment_value['marks'];
    $total_max_marks+=$assessment_value['maximum_marks']; 
  ?>
    <td valign="top" class="text-center">
<?php 
 if(is_null($assessment_value['marks'])){
      echo "N/A";
    }else{
      echo ($assessment_value['is_absent']) ? $this->lang->line('abs') :$assessment_value['marks'];      
    }
 ?>
    </td>
  <?php
}
             ?>           
           <!-- <td valign="top" class="text-center"><?php echo $subject_total; ?></td>
            <td valign="top" class="text-center">
              <?php 
                $subject_percentage=getPercent($subject_max_total,$subject_total);
                echo  getGrade($exam,$subject_percentage);
              ?>              
            </td>
            <td valign="top" class="text-center">         
            
            <?php echo searchSubjectRank($student_value['subject_rank'],$exam_value['subject_id']); ?>

           </td>-->
           
          </tr>
    <?php  
  }

}
$exam_percentage=getPercent($total_max_marks,$total_gain_marks);

             ?>
          
        </tbody>
    </table>
      </td>
    </tr>
     <tr><td valign="top" style="height:0px"></td></tr>
    <tr>
      <td>
      <table  cellpadding="0" cellspacing="0" width="100%" class="denifittable">
        <tbody>
         <!--  <tr>
            <td><?php echo $this->lang->line('overall_marks'); ?> : <?php echo two_digit_float($total_gain_marks, 2)."/".$total_max_marks ?></td>
            <td><?php echo $this->lang->line('percentage'); ?> : <?php echo two_digit_float($exam_percentage, 2); ?></td>
            <td><?php echo $this->lang->line('grade'); ?> : <?php echo getGrade($exam,$exam_percentage) ?></td>
           <td><?php echo $this->lang->line('rank'); ?> : <?php echo $student_value['rank']; ?></td>           
          </tr>-->
        </tbody>
      </table>
    </td>
    </tr>
       <tr><td valign="top" style="height:20px"></td></tr>
       <tr>
      <td>
      <table  cellpadding="0" cellspacing="0" width="100%" class="denifittable" style="padding-bottom: 10px;">
<tbody>
<tr>
    <td valign="middle" class="text-center" rowspan="2"><b><?php echo $this->lang->line('attendance_overall'); ?></b></td>
    <td valign="middle" class="text-center"><b><?php echo $this->lang->line('total_working_days'); ?></b></td>
    <td valign="middle" class="text-center"><b><?php echo $this->lang->line('days_present'); ?></b></td>
    <td valign="middle" class="text-center"><b><?php echo $this->lang->line('attendance_percentage'); ?></b></td>
  </tr>
  <tr>    
    <td valign="middle" class="text-center"><?php echo $student_value['total_working_days']; ?></td>
    <td valign="middle" class="text-center"><?php echo $student_value['total_present_days']; ?></td>
    <td valign="middle" class="text-center"><?php echo getPercent($student_value['total_working_days'],$student_value['total_present_days']);?></td>
  </tr>
</tbody>
</table>
    </td>
    </tr>
    <tr>
    <tr>
      <td style="padding-bottom: 15px;display: block;">
<b><?php echo $this->lang->line('class_teacher_remark'); ?> :</b> <?php echo $student_value['remark']; ?>
    </td>
    </tr>
    </tr>
    <div  style="text-align: center;"><img src="data:image/png;base64,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ATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNREQI5eaqi25CgEhIASEgBAQAhpFQIycRpVTkhECQkAICAEhIARSEwExcqmp2pKrEBACQkAICAEhoFEExMhpVDklGSEgBISAEBACQiA1ERAjl5qqLbkKASEgBISAEBACGkVAjJxGlVOSEQJCQAgIASEgBFITATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNREQI5eaqi25CgEhIASEgBAQAhpFQIycRpVTkhECQkAICAEhIARSEwExcqmp2pKrEBACQkAICAEhoFEExMhpVDklGSEgBISAEBACQiA1ERAjl5qqLbkKASEgBISAEBACGkVAjJxGlVOSEQJCQAgIASEgBFITATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNREQI5eaqi25CgEhIASEgBAQAhpFQIycRpVTkhECQkAICAEhIARSEwExcqmp2pKrEBACQkAICAEhoFEExMhpVDklGSEgBISAEBACQiA1ERAjl5qqLbkKASEgBISAEBACGkVAjJxGlVOSEQJCQAgIASEgBFITATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNREQI5eaqi25CgEhIASEgBAQAhpFQIycRpVTkhECQkAICAEhIARSEwExcqmp2pKrEBACQkAICAEhoFEExMhpVDklGSEgBISAEBACQiA1ERAjl5qqLbkKASEgBISAEBACGkVAjJxGlVOSEQJCQAgIASEgBFITATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNREQI5eaqi25CgEhIASEgBAQAhpFQIycRpVTkhECQkAICAEhIARSEwExcqmp2pKrEBACQkAICAEhoFEExMhpVDklGSEgBISAEBACQiA1ERAjl5qqLbkKASEgBISAEBACGkVAjJxGlVOSEQJCQAgIASEgBFITATFyqanakqsQEAJCQAgIASGgUQTEyGlUOSUZISAEhIAQEAJCIDURECOXmqotuQoBISAEhIAQEAIaRUCMnEaVU5IRAkJACAgBISAEUhMBMXKpqdqSqxAQAkJACAgBIaBRBMTIaVQ5JRkhIASEgBAQAkIgNRH4Hw2H/I8epRGVAAAAAElFTkSuQmCC" style="width:580px;height:300px; padding-left: 100px;"></div>
    
    
    
    
    <tr><td valign="top" style="height:70px"></td></tr>
    <tr>
      <td valign="top" width="100%" align="center">
        <table cellpadding="0" cellspacing="0" width="100%" style="border-bottom:1px solid #999; margin-bottom:10px;">
         <tr>
            <td valign="top" width="32%" class="signature">
              <!--<img src="<?php echo base_url('uploads/cbseexam/template/left_sign/'.$template['left_sign']) ?>" width="100" height="50" style="padding-bottom: 5px;">-->
              <p class="fw-bold"><?php echo $this->lang->line('signature_of_class_teacher'); ?></p>
            </td>
             <td valign="top" width="32%" class="signature text-center">
              <!--<img src="<?php echo base_url('uploads/cbseexam/template/middle_sign/'.$template['middle_sign']) ?>" width="100" height="50" style="padding-bottom: 5px;">-->
              <p class="fw-bold"><?php echo $this->lang->line('signature_of_principal'); ?></p>
            </td>
            <td valign="top" width="32%" class="signature text-center">
              <!--<img src="<?php echo base_url('uploads/cbseexam/template/right_sign/'.$template['right_sign']) ?>" width="100" height="50" style="padding-bottom: 5px;">-->
              <p class="fw-bold"><?php echo $this->lang->line('signature_of_parent'); ?></p>
            </td>
          </tr>
        </table>
      </td>
    </tr>


<!--
    <tr>
    
            <td valign="top" style="padding-bottom: 5px; padding-top: 5px; width: 100%;font-weight: bold; text-align: center; font-size:15px;">
              <?php echo $this->lang->line('instruction'); ?>
            
            </td>
      </tr>
           

<?php 
$total_colspan=4;
foreach ($exam_term_exam_assessment as $assess_key => $assess_value) { 

 $term_colspan=count($assess_value['exam_total_assessments']);
$total_colspan+=$term_colspan+2;

}
 ?>              
              <td valign="top" colspan="<?php echo $total_colspan ?>">   
                    <?php echo $this->lang->line('grading_scale'); ?> : <?php 

            echo implode(', ', array_map(
            function($k)  {
                return $k->name." (".$k->maximum_percentage . "% - " . $k->minimum_percentage."%)";
            },
            ($exam_grades)
            
            )
        );

            ?>
             </td>          
          </tr>--> 
    <tr>
    <td valign="top" style="margin-bottom:5px; padding-top: 10px; line-height: normal;">
           <?php echo $template['content_footer']; ?>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
  <?php
  if($student_increment < $count_result){
  echo "<div style='page-break-after:always'></div>";
}
}
 ?>
 <?php 
 function getGrade($grade_array,$Percentage){

  if(!empty($grade_array->grades)){
    foreach ($grade_array->grades as $grade_key => $grade_value) {

      if($grade_value->minimum_percentage <= $Percentage){
 return $grade_value->name;
         break;
}elseif( ($grade_value->minimum_percentage >= $Percentage && $grade_value->maximum_percentage <= $Percentage)){

         return $grade_value->name;
         break;
      }

    }

  }
return "-";

 }

function searchSubjectRank( $array,$subject_id) {

  foreach ($array as $k => $val) {
 
      if ($k== $subject_id) {
          return $val;
      }
  }
  return null;
}

  ?>
