<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentcourse extends Student_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('course_model', 'coursesection_model', 'courselesson_model', 'studentcourse_model', 'coursequiz_model', 'course_payment_model', 'courseofflinepayment_model', 'coursereport_model'));
        $this->current_classSection = $this->customlib->getStudentCurrentClsSection();
        $this->result               = $this->customlib->getLoggedInUserData();
        $this->load->library("aws3");
        $this->load->library("media_storage");
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library(array('enc_lib', 'cart', 'customlib'));
    }

    /*
    This is used to get student course list
     */
    public function index()
    {        
       
        $this->session->set_userdata('top_menu', 'user/studentcourse');

        $role         = $this->result["role"];
        // $data['role'] = $role;
        if ($role == 'student' || $role == 'parent') {
            $userid = $this->result["student_id"];

            $class_id           = $this->current_classSection->class_id;
            $data['class_id']   = $class_id;
            $section_id         = $this->current_classSection->section_id;
            $data['section_id'] = $section_id;

        } elseif ($role == 'guest') {
            $userid             = $this->result["guest_id"];
            $class_id           = "";
            $data['class_id']   = "";
            $section_id         = "";
            $data['section_id'] = "";

        }

        $data['userid'] = $userid;
        $courselist     = $this->studentcourse_model->courselist($class_id, $section_id);

        $data['paymentgateway'] = $this->paymentsetting_model->getActiveMethod();
        $new_courselist         = array();
        foreach ($courselist as $courselist_value) {

            $lessonquizcount = $this->studentcourse_model->lessonquizcountbycourseid($courselist_value['id'], $userid, $role);

            $courselist_value['total_lesson']     = $total_lesson     = $lessonquizcount['lessoncount'];
            $data['quiz_count']                   = $total_quiz                   = $lessonquizcount['quizcount'];
            $courselist_value['total_hour_count'] = $this->studentcourse_model->counthours($courselist_value['id']);
            $courselist_value['paidstatus']       = $this->courseofflinepayment_model->paidstatus($courselist_value['id'], $userid);
            $courseprogresscount                  = $lessonquizcount['courseprogresscount'];

            $total_quiz_lession = $total_lesson + $total_quiz;

            $course_progress = 0;
            if ($total_quiz_lession > 0) {
                $course_progress = (count($courseprogresscount) / $total_quiz_lession) * 100;
            }

           

            $courselist_value['course_progress'] = $course_progress;

            //  course rating start
            $courserating = $this->studentcourse_model->getcourserating($courselist_value['id']);

            $rating            = 0;
            $averagerating     = 0;
            $totalcourserating = 0;

            if (!empty($courserating)) {
                foreach ($courserating as $courserating_value) {
                    $rating = $rating + $courserating_value['rating'];
                }

                $averagerating = $rating / count($courserating);
            }

            $courselist_value['totalcourserating'] = count($courserating);
            $courselist_value['courserating']      = $averagerating;
            $new_courselist[]                      = $courselist_value;

        }
        $data['loginsession']   = $this->session->userdata('student');
        $data['new_courselist'] = $new_courselist; 

        $this->load->view('layout/student/header');
        $this->load->view('user/studentcourse/studentcourselist', $data);
        $this->load->view('layout/student/footer');
    }

    /*
    This is used to get start lesson list for student section
     */
    public function startlesson()
    {
        $role = $this->result["role"];

        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else {
            $userid = $this->result["guest_id"];
        }
        $courseID            = $this->input->post('coureseID');
        $data['paidstatus']  = $this->courseofflinepayment_model->paidstatus($courseID, $userid);
        $coursesList         = $this->course_model->singlecourselist($courseID);
        $data['coursesList'] = $coursesList;
        $sectionList         = $this->coursesection_model->getsectionbycourse($courseID);
        $data['sectionList'] = $sectionList;

        $lessonquizlist_array = array();
        if (!empty($sectionList)) {
            foreach ($sectionList as $sectionList_value) {
                $lessonquizlist_array[$sectionList_value->id] = $this->coursesection_model->lessonquizbysection($sectionList_value->id);
                foreach ($lessonquizlist_array[$sectionList_value->id] as $lesson_array) {
                    $lesson_id                  = $lesson_array['lesson_id'];
                    $lessonprogress[$lesson_id] = $this->studentcourse_model->getcourseprogress($courseID, $userid, $sectionList_value->id, 1, $lesson_id);
                }
                foreach ($lessonquizlist_array[$sectionList_value->id] as $quiz_array) {
                    $quiz_id                = $quiz_array['quiz_id'];
                    $quizprogress[$quiz_id] = $this->studentcourse_model->getcourseprogress($courseID, $userid, $sectionList_value->id, 2, $quiz_id);

                }
            }
            if (!empty($lessonprogress)) {
                $data['lessonprogress'] = $lessonprogress;
            }
            if (!empty($quizprogress)) {
                $data['quizprogress'] = $quizprogress;
            }

            if (!empty($lessonquizlist_array)) {
                $data['lessonquizdetail'] = $lessonquizlist_array;
            } else {
                $data['lessonquizdetail'] = '';
            }
        }
        $this->load->view('user/studentcourse/studentstartlesson', $data);
    }

    /*
    This is used to get start lesson video list for student section
     */
    public function getlessonvideo()
    {
        $data['sectionid'] = $this->input->post('sectionID');
        $lessonID          = $this->input->post('lessonID');
        $lesson            = $this->studentcourse_model->singlevideo($lessonID);
        if ($lesson['video_provider'] == "s3_bucket") {
            $lesson['s3_url'] = $this->aws3->generateUrl($lesson['video_id']);
        }
        $data['lesson'] = $lesson;
        $this->load->view('user/studentcourse/studentlessonvideo', $data);
    }

    /*
    This is used to get quiz question list from quiz for student section
     */
    public function quizinstruction()
    {
        $role         = $this->result["role"];
        $data['role'] = $role;
        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else {
            $userid = $this->result["guest_id"];
        }

        $courseid                = $this->input->post('courseid');
        $data['courseid']        = $courseid;
        $quizID                  = $this->input->post('quizID');
        $data['singlequizlist']  = $this->studentcourse_model->getsinglequiz($quizID);
        $questioncount           = $this->studentcourse_model->getquestioncount($quizID);
        $data['questioncount']   = $questioncount;
        $data['total_questions'] = count($this->studentcourse_model->getallquestion($quizID));
        $questionlist            = $this->studentcourse_model->getallquestion($quizID);
        if (!empty($questionlist)) {
            $data['questionlist'] = $questionlist[0];
        } else {
            $data['questionlist'] = '';
        }

        $answerlist = array();

        foreach ($questionlist as $questionlist_value) {
            $answerlist[$questionlist_value['id']] = $this->studentcourse_model->getanswer($quizID, $questionlist_value['id'], $userid);
        }

        if (!empty($answerlist)) {
            $data['answerlist'] = $answerlist;
        } else {
            $data['answerlist'] = '';
        }

        $resultstatus = $this->studentcourse_model->checkstatus($quizID, $userid);

        $totalmarks         = $this->quizgraph($courseid, $userid);
        $data['totalmarks'] = $totalmarks['totalmarks'];
        $data['totalquiz']  = $totalmarks['totalquiz'];
        $data['graphdata']  = $resultstatus;

        if (!empty($resultstatus['not_answer'])) {
            $data['not_attempted'] = $resultstatus['not_answer'];
        } else {
            $data['not_attempted'] = 0;
        }
        if (!empty($resultstatus['wrong_answer'])) {
            $data['wronganswer'] = $resultstatus['wrong_answer'];
        } else {
            $data['wronganswer'] = 0;
        }
        if (!empty($resultstatus['correct_answer'])) {
            $data['answercount'] = $resultstatus['correct_answer'];
        } else {
            $data['answercount'] = 0;
        }

        if (!empty($resultstatus)) {
            if ($resultstatus['status'] == 1) {
                $data['questionlist']  = $questionlist;
                $data['status']        = $resultstatus['status'];
                $data['quizid']        = $resultstatus['course_quiz_id'];
                $data['studentresult'] = $this->studentcourse_model->getresult($quizID, $userid);
                $data['questioncount'] = $this->studentcourse_model->getquestioncount($quizID);
                $data['options']       = array('option_1', 'option_2', 'option_3', 'option_4', 'option_5');

                $this->load->view('user/studentcourse/studentresult', $data);
            }
        } else {
            $this->load->view('user/studentcourse/_quizinstruction', $data);
        }
    }

    /*
    This is used to get single question list by quiz for student section
     */
    public function quizquestion()
    {         
        $role = $this->result["role"];
        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else if ($role == 'guest') {
            $userid = $this->result["guest_id"];
        }
        
        $data['courseid']           = $this->input->post('courseid');
        $quizID                     = $this->input->post('quizID');
        $questionID                 = $this->input->post('quizquestionID');
        $data['quizID']             = $quizID;
        $data['questionlist']       = $this->studentcourse_model->getallquestion($quizID);
        $data['total_questions']    = count($this->studentcourse_model->getallquestion($quizID));
        $data['singlequestionlist'] = $this->studentcourse_model->firstquestion($quizID, $questionID);
        $data['answerlist']         = $this->studentcourse_model->getpreviousquestiondetail($questionID, $quizID, $userid);
        $allanswerlist              = $this->studentcourse_model->getallanswer($quizID, $userid);

        // get button color of question
        $color                 = array();
        $result                = $this->color($quizID, $userid);
        $data['color']         = $result['color'];
        $data['allanswerlist'] = $allanswerlist;
        $this->load->view('user/studentcourse/_quizquestion', $data);
    }

    /*
    This is used to save quiz answer for student section
     */
    public function create()
    {
        $role = $this->result["role"];
        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else if ($role == 'guest') {
            $userid = $this->result["guest_id"];
        }

        $data['courseid'] = $this->input->post('courseid');
        $previousID       = $this->input->post('previousID');
        $quizID           = $this->input->post('quizID');
        $questionID       = $this->input->post('question_id');
        $answer1          = $this->input->post('answer_1');
        $answer2          = $this->input->post('answer_2');
        $answer3          = $this->input->post('answer_3');
        $answer4          = $this->input->post('answer_4');
        $answer5          = $this->input->post('answer_5');

        if (!empty($answer1)) {
            $answer1 = 'option_1';
        }
        if (!empty($answer2)) {
            $answer2 = 'option_2';
        }
        if (!empty($answer3)) {
            $answer3 = 'option_3';
        }
        if (!empty($answer4)) {
            $answer4 = 'option_4';
        }
        if (!empty($answer5)) {
            $answer5 = 'option_5';
        }

        $data['questionlist']    = $this->studentcourse_model->getallquestion($quizID);
        $data['total_questions'] = count($this->studentcourse_model->getallquestion($quizID));

        // get button color of question
        $color         = array();
        $result        = $this->color($quizID, $userid);
        $data['color'] = $result['color'];

        if (!empty($answer1) || !empty($answer2) || !empty($answer3) || !empty($answer4) || !empty($answer5)) {
            $correctAnswer = array($answer1, $answer2, $answer3, $answer4, $answer5);

            if (!empty($previousID)) {
                $previousdata               = $this->previousdata($previousID, $quizID, $userid);
                $data['singlequestionlist'] = $previousdata['singlequestionlist'];
                $data['answerlist']         = $previousdata['answerlist'];

                // get button color of question
                $color         = array();
                $result        = $this->color($quizID, $userid);
                $data['color'] = $result['color'];
            } else {
                $questionexist = $this->studentcourse_model->getpreviousquestiondetail($questionID, $quizID, $userid);
                if (!empty($questionexist)) {

                    $correctAnswer = array($answer1, $answer2, $answer3, $answer4, $answer5);

                    $updatedanswerlist          = $this->updatedanswer($questionexist['id'], $correctAnswer, $questionID, $quizID, $userid);
                    $data['singlequestionlist'] = $updatedanswerlist['singlequestionlist'];
                    $data['answerlist']         = $updatedanswerlist['answerlist'];

                    // get button color of question
                    $color         = array();
                    $result        = $this->color($quizID, $userid);
                    $data['color'] = $result['color'];
                } else {

                    $correctAnswer = array($answer1, $answer2, $answer3, $answer4, $answer5);
                    $addData       = array(

                        'course_quiz_id'          => $quizID,
                        'course_quiz_question_id' => $questionID,
                        'answer'                  => json_encode($correctAnswer),
                        'created_date'            => date('Y-m-d H:i:s'),
                    );

                    if ($role == 'student') {
                        
                        $addData['student_id'] = $userid;
                        $addData['guest_id']   = null;

                    } else if ($role == 'guest') {

                        $addData['guest_id']   = $userid;
                        $addData['student_id'] = null;

                    }

                    $this->studentcourse_model->addanswer($addData);

                    $singlequestionlist         = $this->studentcourse_model->getsinglequestion($quizID, $questionID);
                    $data['singlequestionlist'] = $singlequestionlist;
                    // get button color of question
                    $color         = array();
                    $result        = $this->color($quizID, $userid);
                    $data['color'] = $result['color'];
                }
            }

        } else {
            if (!empty($previousID)) {
                $previousdata               = $this->previousdata($previousID, $quizID, $userid);
                $data['singlequestionlist'] = $previousdata['singlequestionlist'];
                $data['answerlist']         = $previousdata['answerlist'];
                // get button color of question
                $color         = array();
                $result        = $this->color($quizID, $userid);
                $data['color'] = $result['color'];
            } else {
                $questionexist = $this->studentcourse_model->getpreviousquestiondetail($questionID, $quizID, $userid);
                if (!empty($questionexist)) {
                    $correctAnswer              = array($answer1, $answer2, $answer3, $answer4, $answer5);
                    $updatedanswerlist          = $this->updatedanswer($questionexist['id'], $correctAnswer, $questionID, $quizID, $userid);
                    $data['singlequestionlist'] = $updatedanswerlist['singlequestionlist'];
                    $data['answerlist']         = $updatedanswerlist['answerlist'];
                    // get button color of question
                    $color         = array();
                    $result        = $this->color($quizID, $userid);
                    $data['color'] = $result['color'];

                } else {
                    $correctAnswer = array($answer1, $answer2, $answer3, $answer4, $answer5);
                    $addData       = array(
                        'course_quiz_id'          => $quizID,
                        'course_quiz_question_id' => $questionID,
                        'answer'                  => json_encode($correctAnswer),
                        'created_date'            => date('Y-m-d H:i:s'),
                    );

                    if ($role == 'student') {

                        $addData['student_id'] = $userid;
                        $addData['guest_id']   = null;

                    } else if ($role == 'guest') {

                        $addData['guest_id']   = $userid;
                        $addData['student_id'] = null;
                    }

                    $this->studentcourse_model->addanswer($addData);
                    $singlequestionlist         = $this->studentcourse_model->getsinglequestion($quizID, $questionID);
                    $data['singlequestionlist'] = $singlequestionlist;
                    $answerlist                 = $this->studentcourse_model->getpreviousquestiondetail($questionID, $quizID, $userid);
                    $data['answerlist']         = $answerlist;
                    // get button color of question
                    $color         = array();
                    $result        = $this->color($quizID, $userid);
                    $data['color'] = $result['color'];
                }
            }
        }
        $this->load->view('user/studentcourse/_quizquestion', $data);
    }

    /*
    This is used to get course detail
     */
    public function coursedetail()
    {
        $role = $this->result["role"];
        if ($role == 'student' || $role == 'parent') {
            $userid = $this->result["student_id"];
        } else {
            $userid = $this->result["guest_id"];
        }

        $courseID = $this->input->post('courseID');

        $lessonquizcount      = $lessonquizcount      = $this->studentcourse_model->lessonquizcountbycourseid($courseID, $userid, $role);
        $data['lesson_count'] = $total_lesson = $lessonquizcount['lessoncount'];
        $data['quiz_count']   = $total_quiz   = $lessonquizcount['quizcount'];

        $data['total_hour_count'] = $this->studentcourse_model->counthours($courseID);
        $data['coursesList']      = $coursesList      = $this->course_model->singlecourselist($courseID);

        $data['paidstatus']          = $this->courseofflinepayment_model->paidstatus($courseID, $userid);
        $data['courseprogresscount'] = $lessonquizcount['courseprogresscount'];

        $sectionList = $this->coursesection_model->getsectionbycourse($courseID);

        if (!empty($coursesList)) {
            $viewcount['id']         = $courseID;
            $viewcount['view_count'] = $coursesList['view_count'] + 1;
            $this->course_model->add($viewcount);
        }

        $data['sectionList'] = $sectionList;

        $lessonquizlist_array = array();
        if (!empty($sectionList)) {
            foreach ($sectionList as $sectionList_value) {
                $lessonquizlist_array[$sectionList_value->id] = $this->coursesection_model->lessonquizbysection($sectionList_value->id);
            }
            $data['lessonquizdetail'] = $lessonquizlist_array;
        } else {
            $data['lessonquizdetail'] = '';
        }

        $courserating         = $this->studentcourse_model->getcourserating($courseID);
        $data['coursereview'] = $courserating;

        $rating            = 0;
        $averagerating     = 0;
        $totalcourserating = 0;

        if (!empty($courserating)) {
            foreach ($courserating as $courserating_value) {
                $rating = $rating + $courserating_value['rating'];
            }

            $averagerating = $rating / count($courserating);
        }

        $data['totalcourserating'] = count($courserating);
        $data['courserating']      = $averagerating;

        $data['loginsession']              = $this->session->userdata('student');
        $data['paymentgateway']            = $this->paymentsetting_model->getActiveMethod();
        $data['role']                      = $this->result["role"];

        $multipalsection = $this->course_model->multipalsection($courseID);
        $section         = "";
        $store_section   = array();
        foreach ($multipalsection as $multipalsection_value) {
            if (!in_array($multipalsection_value['section'], $store_section)) {
                $store_section[] = $multipalsection_value['section'];
                $section .= $multipalsection_value['section'] . ", ";
            }
        }
        $data['section'] = $section;

        $this->load->view('user/studentcourse/_coursedetail', $data);

    }

    /*
    This is used for purpose of download course in pdf, txt,.doc format
     */
    public function download($doc, $section_id, $lesson_id)
    {
        $this->load->helper('download');
        $filepath = "./uploads/course_content/" . $section_id . "/" . $lesson_id . "/" . $doc;
        $data     = file_get_contents($filepath);
        $name     = $doc;
        force_download($name, $data);
    }

    /*
    This is used to get result for single student
     */
    public function getresult()
    {

        $role = $this->result["role"];
        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else if ($role == 'guest') {
            $userid = $this->result["guest_id"];
        }

        $courseid         = $this->input->post('courseid');
        $data['courseid'] = $courseid;
        $status           = $this->input->post('status');
        $quizID           = $this->input->post('quizID');
        $questionID       = $this->input->post('question_id');
        $answer1          = $this->input->post('answer_1');
        $answer2          = $this->input->post('answer_2');
        $answer3          = $this->input->post('answer_3');
        $answer4          = $this->input->post('answer_4');
        $answer5          = $this->input->post('answer_5');

        if (!empty($answer1)) {
            $answer1 = 'option_1';
        }
        if (!empty($answer2)) {
            $answer2 = 'option_2';
        }
        if (!empty($answer3)) {
            $answer3 = 'option_3';
        }
        if (!empty($answer4)) {
            $answer4 = 'option_4';
        }
        if (!empty($answer5)) {
            $answer5 = 'option_5';
        }

        $questioncount         = $this->studentcourse_model->getquestioncount($quizID);
        $data['questioncount'] = $questioncount;

        $correctAnswer   = array($answer1, $answer2, $answer3, $answer4, $answer5);
        $options         = array('option_1', 'option_2', 'option_3', 'option_4', 'option_5');
        $data['options'] = $options;

        $addData = array(
            'course_quiz_id'          => $quizID,
            'course_quiz_question_id' => $questionID,
            'answer'                  => json_encode($correctAnswer),
            'created_date'            => date('Y-m-d H:i:s'),
        );

        if ($role == 'student') {
            $addData['student_id'] = $userid;
            $addData['guest_id']   = null;

        } else if ($role == 'guest') {
            $addData['guest_id']   = $userid;
            $addData['student_id'] = null;
        }

        $this->studentcourse_model->addanswer($addData);

        $resultData = array(

            'course_quiz_id' => $quizID,
            'status'         => $status,
            'created_date'   => date('Y-m-d H:i:s'),
        );

        if ($role == 'student') {

            $resultData['student_id'] = $userid;
            $resultData['guest_id']   = null;

        } else if ($role == 'guest') {

            $resultData['guest_id']   = $userid;
            $resultData['student_id'] = null;

        }

        $lastid        = $this->studentcourse_model->addresult($resultData);
        $studentresult = $this->studentcourse_model->getresult($quizID, $userid);

        $answercount   = array();
        $wronganswer   = array();
        $not_attempted = array();

        if (!empty($studentresult)) {
            foreach ($studentresult as $studentresult_value) {
                $result = '';
                if (!empty($studentresult_value['answer'])) {
                    $submit_answer = json_decode($studentresult_value['answer']);

                    foreach ($submit_answer as $key => $submit_answer_value) {

                        if (array_filter($submit_answer)) {
                            if (!empty($submit_answer_value)) {
                                $key = $key + 1;
                                if ($key == 1) {
                                    $result = "option_1,";
                                }if ($key == 2) {
                                    $result = $result . "option_2,";
                                }if ($key == 3) {
                                    $result = $result . "option_3,";
                                }if ($key == 4) {
                                    $result = $result . "option_4,";
                                }if ($key == 5) {
                                    $result = $result . "option_5";
                                }
                            }

                            $result;

                        } else {
                            $result = 'empty';
                        }
                    }
                    // $result = rtrim($result, ',');
                }

                if ($studentresult_value['correct_answer'] . ',' == $result) {                   
                    $answer_value = '1';
                    array_push($answercount, $answer_value);
                } elseif ($studentresult_value['correct_answer'] == $result) {                   
                    $answer_value = '1';
                    array_push($answercount, $answer_value);
                } elseif ($result == 'empty') {
                } else {
                    $wronganswer_value = '1';
                    array_push($wronganswer, $wronganswer_value);
                }
            }
        }
        

        $questionlist         = $this->studentcourse_model->getallquestion($quizID);
        $data['questionlist'] = $questionlist;
        $answerlist           = array();

        foreach ($questionlist as $questionlist_value) {
            $answerlist[$questionlist_value['id']] = $this->studentcourse_model->getanswer($quizID, $questionlist_value['id'], $userid);
        }
        if (!empty($answerlist)) {
            $data['answerlist'] = $answerlist;
        } else {
            $data['answerlist'] = '';
        }

        $answercount   = count($answercount);
        $wrong_answer  = count($wronganswer);
        $not_attempted = $questioncount['question_count'] - ($answercount + $wrong_answer);

        if (!empty($lastid)) {
            $updateData = array(
                'id'             => $lastid,
                'total_question' => $questioncount['question_count'],
                'correct_answer' => $answercount,
                'wrong_answer'   => $wrong_answer,
                'not_answer'     => $not_attempted,
            );

            $this->studentcourse_model->addresult($updateData);
        }

        $data['answercount']    = $answercount;
        $data['wronganswer']    = $wrong_answer;
        $data['not_attempted']  = $not_attempted;
        $data['quizid']         = $quizID;
        $data['status']         = '';
        $data['studentresult']  = $studentresult;
        $graphdata              = $this->studentcourse_model->checkstatus($quizID, $userid);
        $data['graphdata']      = $graphdata;
        $totalmarks             = $this->quizgraph($courseid, $userid);
        $data['totalmarks']     = $totalmarks['totalmarks'];
        $data['totalquiz']      = $totalmarks['totalquiz'];
        $data['singlequizlist'] = $this->studentcourse_model->getsinglequiz($quizID);
        $this->load->view('user/studentcourse/studentresult', $data);
    }

    /*
    This is used to delete previous record of student if he has given exam
     */
    public function reset()
    {
        $role = $this->result["role"];
        if ($role == 'student') {
            $userid = $this->result["student_id"];
        } else if ($role == 'guest') {
            $userid = $this->result["guest_id"];
        }

        $courseid               = $this->input->post('courseid');
        $data['courseid']       = $courseid;
        $quizID                 = $this->input->post('quizID');
        $data['singlequizlist'] = $this->studentcourse_model->getsinglequiz($quizID);
        $questionlist           = $this->studentcourse_model->getallquestion($quizID);
        if (!empty($questionlist)) {
            $data['questionlist'] = $questionlist[0];
        } else {
            $data['questionlist'] = '';
        }
        $data['notanswer'] = '';

        $data['questioncount'] = $this->studentcourse_model->getquestioncount($quizID);
        $totalmarks            = $this->quizgraph($courseid, $userid);
        $data['totalmarks']    = $totalmarks['totalmarks'];
        $data['totalquiz']     = $totalmarks['totalquiz'];
        $this->studentcourse_model->remove($quizID, $userid);
        $this->studentcourse_model->removeanswer($quizID, $userid);
        $this->load->view('user/studentcourse/_quizinstruction', $data);
    }

    /*
    This is used to get previous question data
     */
    public function previousdata($previousid, $quizid, $userid)
    {
        $data['answerlist']         = '';
        $data['singlequestionlist'] = '';

        $singlequestionlist         = $this->studentcourse_model->previousquestion($quizid, $previousid);
        $data['singlequestionlist'] = $singlequestionlist;

        $questionexist = $this->studentcourse_model->getpreviousquestiondetail($singlequestionlist['id'], $quizid, $userid);
        $id            = '';
        if (!empty($questionexist)) {
            $id = $questionexist['id'];
        }
        $answerlist         = $this->studentcourse_model->getpreviousanswer($id);
        $data['answerlist'] = $answerlist;
        return $data;
    }

    /*
    This is used to identify question is attempt or not
     */
    public function color($quizid, $userid)
    {
        $data['color'] = '';
        $allanswerlist = $this->studentcourse_model->getallanswer($quizid, $userid);
        foreach ($allanswerlist as $key => $allanswerlist_value) {
            $colors        = '';
            $question_id   = $allanswerlist_value['course_quiz_question_id'];
            $correctanswer = json_decode($allanswerlist_value['answer']);
            if (array_filter($correctanswer)) {
                $colors = 'alert-success';
            } else {
                $colors = 'alert-danger';
            }
            $color[$question_id] = $colors;
        }
        if (!empty($color)) {
            $data['color'] = $color;
        }
        return $data;
    }

    /*
    This is used to update answer of question by answer id
     */
    public function updatedanswer($id, $correctAnswer, $questionID, $quizID, $userid)
    {
        $updateData = array(
            'id'     => $id,
            'answer' => json_encode($correctAnswer),
        );
        $this->studentcourse_model->addanswer($updateData);
        $id                         = $id + 1;
        $singlequestionlist         = $this->studentcourse_model->getsinglequestion($quizID, $questionID);
        $data['singlequestionlist'] = $singlequestionlist;
        $answerlist                 = $this->studentcourse_model->getpreviousanswer($id);
        $data['answerlist']         = $answerlist;
        return $data;
    }

    /**
     * This function is used to mark a lesson as complete
     */
    public function markascomplete()
    {
        $student_id       = null;
        $guest_id         = null;
        $role             = $this->result["role"];
        $section_id       = $this->input->post("section_id");
        $result           = $this->course_model->coursebysection($section_id);
        $lesson_quiz_type = $this->input->post("lesson_quiz_type");
        $lesson_quiz_id   = $this->input->post("lesson_quiz_id");

        if ($role == 'student') {
            $student_id = $this->result["student_id"];
            $user_id    = $student_id;

        } else if ($role == 'guest') {
            $guest_id = $this->result["guest_id"];
            $user_id  = $guest_id;
        }

        if (!empty($result)) {
            $data = array(
                "student_id"        => $student_id,
                "guest_id"          => $guest_id,
                "lesson_quiz_id"    => $this->input->post("lesson_quiz_id"),
                "lesson_quiz_type"  => $this->input->post("lesson_quiz_type"),
                "course_section_id" => $this->input->post("section_id"),
                "course_id"         => $result['id'],
            );

            $is_completed = $this->studentcourse_model->getcourseprogress($result['id'], $user_id, $section_id, $lesson_quiz_type, $lesson_quiz_id);

            if (!empty($is_completed)) {
                $this->studentcourse_model->markAsComplete($data, 0);
            } else {
                $this->studentcourse_model->markascomplete($data, 1);
            }

        } else {
            print_r("not enrolled");
        }
    }

    /**
     * This function is used to get course progress
     */
    public function getcourseprogress()
    {
        $role = $this->result["role"];
        if ($role == 'student') {
            $student_id = $this->result["student_id"];
        } else if ($role == 'guest') {
            $student_id = $this->result["guest_id"];
        }
        $course_id = $this->input->post("course");

        $role                 = $this->result["role"];
        $lessonquizcount      = $lessonquizcount      = $this->studentcourse_model->lessonquizcountbycourseid($course_id, $student_id, $role);
        $data['lesson_count'] = $total_lesson = $lessonquizcount['lessoncount'];
        $data['quiz_count']   = $total_quiz   = $lessonquizcount['quizcount'];
        $courseprogresscount  = $lessonquizcount['courseprogresscount'];

        $total_quiz_lession = $total_lesson + $total_quiz;

        if ($total_quiz_lession > 0) {
            $progress = ((count($courseprogress) / $total_quiz_lession)) * 100;
        }
        $data["progress"] = intval($progress);
        echo json_encode($data);
    }

    /*
    This is used to get course list for datatable
     */
    public function getcourselist()
    {
        $role = $this->result["role"];
        if ($role == 'student' || $role == 'parent') {
            $userid             = $this->result["student_id"];
            $class_id           = $this->current_classSection->class_id;
            $section_id         = $this->current_classSection->section_id;
            $data['section_id'] = $section_id;
            $courselist      = $this->studentcourse_model->getcourselist($class_id, $section_id);
        } else if ($role == 'guest') {
            $userid     = $this->result["guest_id"];
            $class_id   = "";
            $section_id = "";
            $courselist      = $this->studentcourse_model->getguestcourselist($class_id, $section_id);
        }       
        
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();
        
        $new_courselist  = array();
        $m               = json_decode($courselist);
        $dt_data         = array();
        if (!empty($m->data)) {
            foreach ($m->data as $key => $value) {
                $lessonquizcount     = $lessonquizcount     = $this->studentcourse_model->lessonquizcountbycourseid($value->id, $userid, $role);
                $total_lesson        = $lessonquizcount['lessoncount'];
                $total_quiz          = $lessonquizcount['quizcount'];
                $courseprogresscount = $lessonquizcount['courseprogresscount'];
                $total_hour_count    = $this->studentcourse_model->counthours($value->id);
                $paidstatus          = $this->courseofflinepayment_model->paidstatus($value->id, $userid);
                $total_quiz_lession  = $total_lesson + $total_quiz;

                $course_progress = 0;
                if ($total_quiz_lession > 0) {
                    $course_progress = (count($courseprogresscount) / $total_quiz_lession) * 100;
                }
                $quiz_count    = $this->studentcourse_model->totalquizbycourse($value->id);
                $section_total = $this->coursesection_model->getsectioncount($value->id);

                $free_course = $value->free_course;
                $discount    = $value->discount;
                $price       = $value->price;

                if ($value->discount != '0.00') {
                    $discount = $value->price - (($value->price * $value->discount) / 100);
                }

                if (($value->free_course == 1) && ($value->price == '0.00')) {
                    $price    = $this->lang->line('free');
                    $discount = $this->lang->line('free');
                } elseif (($value->free_course == 1) && ($value->price != '0.00')) {
                    if ($value->price > '0.00') {
                        $courseprice = amountFormat($value->price);
                    } else {
                        $courseprice = $this->lang->line('free');
                    }
                    $price    = $courseprice;
                    $discount = $this->lang->line('free');
                } elseif (($value->price != '0.00') && ($value->discount != '0.00')) {
                    $discount = amountFormat($discount);
                    if ($value->price > '0.00') {
                        $courseprice = amountFormat($value->price);
                    } else {
                        $courseprice = '';
                    }
                    $price = $courseprice;
                } else {
                    $price    = amountFormat($value->price);
                    $discount = amountFormat($value->price);
                }

                $viewbtn = "<a  data-toggle='tab' onclick='loadcoursedetail(" . '"' . $value->id . '"' . "  )' class='btn btn-default btn-xs btn-add course_detail_id' data-id=" . $value->id . " data-backdrop='static' data-keyboard='false' data-toggle='modal' data-target='#course_detail_modal' title=" . $this->lang->line('course_detail') . "><i class='fa fa-reorder'></i></a>";

                $row   = array();
                $row[] = $value->title;
                if ($role == 'student') {
                    $row[] = $value->class . " (" . rtrim($value->section, ", ") . ")";
                }

                $row[]     = $section_total;
                $row[]     = $total_lesson;
                $row[]     = $total_quiz;
                $row[]     = $total_hour_count;
                $row[]     = $price;
                $row[]     = $discount;
                $row[]     = date($this->customlib->getSchoolDateFormat(), strtotime($value->updated_date));
                $row[]     = $viewbtn;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($m->draw),
            "recordsTotal"    => intval($m->recordsTotal),
            "recordsFiltered" => intval($m->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

    /*
    This is used to get quiz data for quiz progress graph
     */
    public function quizgraph($courseid, $userid)
    {
        $totalquiz          = $this->studentcourse_model->quizbycourse($courseid);
        $data['totalquiz']  = $totalquiz;
        $data['totalmarks'] = '';

        $totalmarks_array = array();
        foreach ($totalquiz as $totalquiz_value) {
            $totalmarks = $this->studentcourse_model->quizgraph($totalquiz_value->id, $userid);

            if (!empty($totalmarks['total_question']) and $totalmarks['total_question'] != 0) {
                $marks              = ($totalmarks['right_answer'] * 100) / $totalmarks['total_question'];
                $totalmarks_array[] = number_format((float) $marks, 2, '.', '');
            } else {
                $totalmarks_array[] = 0;
            }
        }

        if (!empty($totalmarks_array)) {
            $data['totalmarks'] = $totalmarks_array;
        }
        return $data;
    }

    /*
    This is used to get quiz list for quiz performance report
     */
    public function quizperformance()
    {
        $role = $this->result["role"];
        if ($role == 'guest') {
            $userid = $this->result["guest_id"];
        } else if ($role == 'student') {
            $userid = $this->result["student_id"];
        }

        $courseid         = $this->input->post('courseid');
        $data['courseid'] = $courseid;

        // for bar graph start
        $totalmarks         = $this->quizgraph($courseid, $userid);
        $data['totalmarks'] = $totalmarks['totalmarks'];
        $data['quizdata']   = $totalmarks['totalquiz'];
        $data['quizcount']  = count($totalmarks['totalquiz']);
        // end
        // quiz progress start
        $lessonquizcount = $this->studentcourse_model->lessonquizcountbycourseid($courseid, $userid, $role);

        $data['lesson_count'] = $total_lesson = $lessonquizcount['lessoncount'];
        $data['quiz_count']   = $total_quiz   = $lessonquizcount['quizcount'];
        $courseprogresscount  = $lessonquizcount['courseprogresscount'];
        $total_quiz_lession   = $total_lesson + $total_quiz;
        $course_progress      = 0;
        if ($total_quiz_lession > 0) {
            $course_progress = (count($courseprogresscount) / $total_quiz_lession) * 100;
        }
        $data['course_progress'] = intval($course_progress);
        // end
        // for completed status start

        $completedquiz = $this->studentcourse_model->completelessonquizbycourse($courseid, $userid, $role);
        if (!empty($completedquiz['quiz'])) {
            $quiz = $completedquiz['quiz'];
        } else {
            $quiz = 0;
        }

        if (!empty($completedquiz['lesson'])) {
            $lesson = $completedquiz['lesson'];
        } else {
            $lesson = 0;
        }

        $data['completedquiz']   = $quiz;
        $data['completedlesson'] = $lesson;
        // end

        $data['quizperformancedata'] = $this->studentcourse_model->quizstatusbycourseid($courseid, $userid, $role);
        $this->load->view('user/studentcourse/_quizperformance', $data);
    }

    /*
    This is used to print course payment detail or it is a invoice
     */
    public function printinvoice()
    {
        $data['role'] = $this->result["role"];
        if ($data['role'] == 'guest') {
            $userid = $this->result["guest_id"];
        } else if ($data['role'] == 'student') {
            $userid = $this->result["student_id"];
        }

        $courseid           = $this->input->post('courseid');
        $data['courselist'] = $this->courseofflinepayment_model->courseprint($courseid, $userid);

        $setting_result      = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('user/studentcourse/print/printinvoice', $data);
    }

    /*
    This is used to add rating
     */
    public function rating()
    {
        $this->form_validation->set_rules('review', $this->lang->line('review'), 'required');
        $this->form_validation->set_rules('rate', $this->lang->line('rating'), 'required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'review' => form_error('review'),
                'rate'   => form_error('rate'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $student_id = '';
            $guest_id   = '';

            $role = $this->result["role"];
            if ($role == 'student') {
                $student_id = $this->result["student_id"];
            } else {
                $guest_id = $this->result["guest_id"];
            }

            $courserating = $this->studentcourse_model->checkratingstatus($this->input->post('course_id'), $student_id, $guest_id);

            if ($courserating['count'] > 0) {
                $data['id']         = $courserating["id"];
                $data['student_id'] = $student_id;
                $data['guest_id']   = $guest_id;
                $data['course_id']  = $this->input->post('course_id');
                $data['review']     = $this->input->post('review');
                $data['rating']     = $this->input->post('rate');
                $data['date']       = date('Y-m-d');
                $this->studentcourse_model->rating($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            } else {
                $data['student_id'] = $student_id;
                $data['guest_id']   = $guest_id;
                $data['course_id']  = $this->input->post('course_id');
                $data['review']     = $this->input->post('review');
                $data['rating']     = $this->input->post('rate');
                $data['date']       = date('Y-m-d');
                $this->studentcourse_model->rating($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
        }
        echo json_encode($array);
    }

    /*
    This is used to check rating is already available for individual student, and also get rating detail by course and student
     */
    public function checkratingstatus()
    {
        $student_id = '';
        $guest_id   = '';

        $role = $this->result["role"];
        if ($role == 'student') {
            $student_id = $this->result["student_id"];
        } else {
            $guest_id = $this->result["guest_id"];
        }
        $courserating = $this->studentcourse_model->checkratingstatus($this->input->post('courseid'), $student_id, $guest_id);

        if (!empty($courserating)) {
            $array = array('status' => 'success', 'error' => '', 'review' => $courserating['review'], 'rating' => $courserating['rating'], 'message' => $this->lang->line('success_message'));
        } else {
            $array = array('status' => 'fail', 'error' => '', 'review' => '', 'rating' => $courserating['rating'], 'message' => $this->lang->line('something_went_wrong'));
        }
        echo json_encode($array);
    }

    public function get_processingpayment()
    {
        $id                     = $_POST['id'];
        $result                 = $this->course_payment_model->get_processingpayment($id);
        $currency_symbol        = $this->customlib->getSchoolCurrencyFormat();
        $result['payment_type'] = $this->lang->line(strtolower($result['payment_type']));
        $result['paid_amount']  = $currency_symbol . amountFormat($result['paid_amount']);
        $result['date']         = date($this->customlib->getSchoolDateFormat(), strtotime($result['date']));
        echo json_encode($result);
    }

    public function changeguestpass()
    {
        $this->load->view('layout/student/header');
        $this->load->view('user/studentcourse/change_password');
        $this->load->view('layout/student/footer');
    }

    public function updateguestpass()
    {
        $this->form_validation->set_rules('current_pass', 'Current password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {

            $sessionData = $this->session->userdata('student');

            $this->data['id']       = $sessionData['guest_id'];
            $this->data['username'] = $sessionData['username'];

            $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('enter_login_details') . '</div>');

            $this->load->view('layout/student/header');
            $this->load->view('user/studentcourse/change_password');
            $this->load->view('layout/student/footer');

        } else {
            $sessionData = $this->session->userdata('student');
            $data_array  = array(
                'current_pass' => $this->input->post('current_pass'),
                'user_id'      => $sessionData['guest_id'],
                'email'        => $sessionData['email'],
            );
            $newdata = array(
                'id'       => $sessionData['id'],
                'password' => $this->enc_lib->passHashEnc($this->input->post('new_pass')),
            );
            $query1 = $this->studentcourse_model->checkOldPass($data_array);

            if ($query1) {
                $query2 = $this->studentcourse_model->savenewpassword($newdata);
                if ($query2) {

                    $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('password_changed_successfully') . '</div>');
                    redirect('user/studentcourse/changeguestpass');
                }
            } else {

                $this->session->set_flashdata('msg', '<div class="alert alert-danger">' . $this->lang->line('invalid_current_password') . '</div>');
                redirect('user/studentcourse/changeguestpass');
            }
        }
    }

    public function profile()
    {
        $this->session->set_userdata('top_menu', 'user/guestprofile');
        $role                  = $this->result["role"];
        $guest_id              = $this->result["guest_id"];
        $data['guest_details'] = $this->studentcourse_model->read_user_information($guest_id);
        $this->load->view('layout/student/header');
        $this->load->view('user/studentcourse/profile', $data);
        $this->load->view('layout/student/footer');
    }

    public function editguestmodel()
    {
        $student_id            = $_POST['student_id'];
        $guest_details         = $this->studentcourse_model->read_user_information($student_id);
        $data['dob']           = date($this->customlib->dateformat($guest_details[0]->dob));
        $data['guest_details'] = $guest_details;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;

        $this->load->view('user/studentcourse/_editguestmodel', $data);
    }

    public function updateguestdata()
    {
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('photo', $this->lang->line('photo'), 'callback_handle_upload');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'name'  => form_error('name'),
                'photo' => form_error('photo'),

            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id  = $this->input->post("guest_id");
            $dob = $this->input->post("dob");
            if (!empty($dob)) {
                $guestdob = $this->customlib->dateFormatToYYYYMMDD($dob);

            } else {
                $guestdob = '';
            }

            $data = array(
                'id'         => $id,
                'mobileno'   => $this->input->post("mobile_number"),
                'gender'     => $this->input->post("gender"),
                'address'    => $this->input->post("address"),
                'guest_name' => $this->input->post("name"),
                'dob'        => $guestdob,
            );

            if (isset($_FILES["photo"]) && !empty($_FILES['photo']['name'])) {
                $uploaddir = './uploads/guest_images/';
                if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                    die("Error creating folder $uploaddir");
                }
                $fileInfo = pathinfo($_FILES["photo"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["photo"]["tmp_name"], $uploaddir . $img_name);
                $data['guest_image'] = $img_name;
            }

            $this->studentcourse_model->addguest($data);
            $msg   = $this->lang->line('update_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
        }

        echo json_encode($array);
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();

        if (isset($_FILES["photo"]) && !empty($_FILES['photo']['name'])) {
            $file_type = $_FILES["photo"]['type'];
            $file_size = $_FILES["photo"]["size"];
            $file_name = $_FILES["photo"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES['photo']['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'File Type Not Allowed');
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', 'Extension Not Allowed');
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($image_validate['upload_size'] / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', "File Type / Extension Error Uploading  Image");
                return false;
            }

            return true;
        }
        return true;
    }

    public function purchasehistory()
    {
        $this->session->set_userdata('top_menu', 'user/purchasehistory');
        $role                  = $this->result["role"];
        $guest_id              = $this->result["guest_id"];
        $data['guest_details'] = $this->studentcourse_model->read_user_information($guest_id);
        $this->load->view('layout/student/header');
        $this->load->view('user/studentcourse/purchasehistory', $data);
        $this->load->view('layout/student/footer');
    }

    public function guestpurchasehistory()
    {
        $guest_id   = $this->result["guest_id"];
        $coursedata = $this->studentcourse_model->guestpurchasehistory($guest_id);

        $coursedata = json_decode($coursedata);
        $dt_data    = array();
        if (!empty($coursedata->data)) {
            $doc = "";
            foreach ($coursedata->data as $key => $value) {

                $row   = array();
                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->date));
                $row[] = $value->title;
                $row[] = $this->lang->line($value->course_provider);
                $row[] = $this->lang->line(strtolower($value->payment_type));
                if ($value->payment_type == 'Online') {
                    $row[] = $value->payment_mode . ' (' . $this->lang->line('txn_id') . ' - ' . $value->transaction_id . ')';
                } else {
                    $row[] = $value->payment_mode;
                }

                $row[]     = amountFormat($value->paid_amount);
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw"            => intval($coursedata->draw),
            "recordsTotal"    => intval($coursedata->recordsTotal),
            "recordsFiltered" => intval($coursedata->recordsFiltered),
            "data"            => $dt_data,
        );
        echo json_encode($json_data);
    }

}
