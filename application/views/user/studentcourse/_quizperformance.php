<div class="quizwell">
	<div class="row">
		<div class="col-md-12">
			<div class="row">
				<div class="col-md-12">
					<label><?php echo $this->lang->line('overall_performance'); ?></label>
					<button type="button" onclick="afterprint(<?php echo $courseid; ?>)" class="closeblack">&times;</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="pagebg">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-4">
				<div class="form-group">
					<b><?php echo $this->lang->line('total_lesson'); ?>: </b>
					<span class="fontmedium mt-5"><?php echo $lesson_count; ?></span>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group mt20">
					<div class="col-md-10 col-sm-10 col-xs-10">
                      <div class="courssprogress">		
							<div class="progress-bar <?php if($course_progress < '100'){ ?> progress-bar-warning <?php }elseif($course_progress == '100'){ ?> progress-bar-info <?php } ?>" role="progressbar" aria-valuenow="45" id="progressbar<?= $courseid; ?>"  aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $course_progress;?>%">
							</div>				  
                      </div>					  
                    </div>
                    <div class="col-md-2 col-sm-2 col-xs-2 text-right">
						<span id="progressbarval<?= $courseid; ?>">
						<?php echo $course_progress; ?>%
						</span>
					</div>
				</div>  
			</div> 
			<div class="col-md-4">
				<div class="form-group pull-right">
					<b><?php echo $this->lang->line('total_quiz'); ?>: </b>
					<span class="fontmedium mt-5 "><?php echo $quiz_count; ?></span>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<div class="col-md-6">
				<div class="form-group">
					<b><?php echo $this->lang->line('completed_lesson'); ?>: </b>
					<span class="fontmedium mt-5 "><?php echo $completedlesson; ?></span>
			    </div>
			</div>
			<div class="col-md-6">
				<div class="form-group pull-right">
					<b><?php echo $this->lang->line('completed_quiz'); ?>: </b>
					<span class="fontmedium mt-5 "><?php echo $completedquiz; ?></span>
			    </div>
			</div>
		</div>
	</div>
	<hr>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<b> <?php echo $this->lang->line('quiz_performance'); ?></b>
		    </div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-8">
			<div class="scroll-area-fullheight">
				<?php
					$count = 0;
					if (!empty($quizperformancedata)) {  
				?>
				<?php foreach ($quizperformancedata as $quizperformancedata_value) {  $count++;
				  ?>
                	<div class="whatyou overflow-hidden mb10">
                	  <div class="row">
						<div class="col-md-4 col-lg-4 col-sm-4">
							<div class="form-group">
								<b><?php echo $this->lang->line('quiz'); ?>: <?php echo $count; ?></b>
								<div class="fontmedium mt-5"><?php echo $quizperformancedata_value['quiz_title']; ?></div>
							</div>                           
							<div class="anslistlineh">
								<b><?php echo $this->lang->line('total_question'); ?></b>
								<span>
									
									<?php echo $quizperformancedata_value['total_question']; ?>
								</span>
							</div>
							<div class="anslistlineh">
								<b><?php echo $this->lang->line('correct_answer'); ?></b>
								<span>
									<?php echo $quizperformancedata_value['correct_answer']; ?>
								</span>
							</div>
							<div class="anslistlineh">
								<b><?php echo $this->lang->line('wrong_answer'); ?></b>
								<span>
									<?php echo $quizperformancedata_value['wrong_answer']; ?>
								</span>
							</div>
							<div class="anslistlineh">
								<b><?php echo $this->lang->line('not_attempted'); ?></b>
								<span>
									<?php echo $quizperformancedata_value['not_answer']; ?>
								</span>
							</div>
						</div>
						<div class="col-md-4 col-lg-4 col-sm-4">
							<div class="form-group">
									
							 		<div class="box-body">
		                                <div class="chart-responsive">
		                                    <canvas id="doughnuts-chart<?php echo $quizperformancedata_value['course_quiz_id']; ?>"></canvas>
		                                </div>
		                            </div>
									
							</div>
						</div>
					</div>
				</div>
				<?php  } } ?>
			</div><!--./scroll-area-fullheight-->
		</div><!--./col-lg-8-->				
		<div class="col-md-4">
			<div class="scroll-area-fullheight">
			<?php if (!empty($quizperformancedata)) {  ?>
				<div class="box-body border-bottom">
                    <div class="chart-responsive">
                        <canvas id="horizontals-charts"></canvas>
                    </div>
                </div>
			<?php } ?>
			</div>
			
		</div>
	</div>
</div>

<script>
(function ($) {
  "use strict";

$(document).ready(function(){
	<?php
	if (!empty($quizperformancedata)) {
	 foreach ($quizperformancedata as $quizperformancedata_value) { 
		?>
	var quizid = "<?php echo $quizperformancedata_value['course_quiz_id']; ?>";
	  new Chart(document.getElementById("doughnuts-chart"+quizid), {
		    type: 'pie',
		    data: {
		      labels: ["<?php echo $this->lang->line('correct_answer'); ?>", "<?php echo $this->lang->line('wrong_answer'); ?>", "<?php echo $this->lang->line('not_attempted'); ?>"],
		      datasets: [{
		        backgroundColor: ["#52d726", "#f93939", "#c9cbcf"],
		        data: [<?php echo $quizperformancedata_value['correct_answer']; ?>, <?php echo $quizperformancedata_value['wrong_answer']; ?>, <?php echo $quizperformancedata_value['not_answer']; ?>]
		      }]
		    },
		    options: {
		      title: {
		        display: true,
		        text: "<?php echo $this->lang->line('quiz_performance'); ?>"
		      }
		    }
		});
	<?php  } } ?>
});

	var data = {
	  labels: [<?php foreach ($quizdata as $totalquiz_value) { ?>"<?php echo $totalquiz_value->quiz_title; ?>", <?php }?>],
	  datasets: [{
	    backgroundColor: ["#ff7300","#9c46d0","#7cdddd","#1baa2f","#97d9ff"],
	    borderWidth: 1,
	    data: [<?php foreach ($totalmarks as $totalmarks_value) {?>"<?php echo $totalmarks_value; ?>", <?php }?>],
	  }]
	};
	// invert the sign of each of the values.
	data.datasets[0].data.map((currentValue, index, array) => {
	  array[index] = currentValue * -1;
	});

	var options = {
	  scales: {
	    yAxes: [{
	      position: 'right' // right-align axis.
	    }],

	    xAxes: [{
	      id: "bar-x-axis1",
	      stacked: false,
	      ticks: {
	        callback: function(value, index, values) {
	          return value * -1; // invert the sign for tick labelling.
	        },
	        beginAtZero: true
	      }
	    }]
	  },
	  legend: { display: false },
	  title: {
	    display: true,
	    text: "<?php echo $this->lang->line('all_quiz_performance') . ' ' . '(%)'; ?>"
	  }
	};

	new Chart(document.getElementById("horizontals-charts"), {
	  type: 'horizontalBar',
	  data: data,
	  options: options
	});
})(jQuery);
</script>