<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Courselesson_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    /*
    This is used to add or edit lesson
    */
	public function addlesson($data)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('online_course_lesson', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On  online course lesson id " . $data['id'];
            $action    = "Update";
            $record_id = $id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('online_course_lesson', $data);
            $id        = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On online course lesson id " . $id;
            $action    = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);           
        }
        //======================Code End==============================

        $this->db->trans_complete(); # Completing transaction
        /* Optional */

        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            return $id;
        }
    }

    /*
    This is used to get single lesson
    */
    public function singlelessondetail($id) {
        $this->db->select('online_course_lesson.*');
        $this->db->from('online_course_lesson');
        $this->db->where('online_course_lesson.id',$id);
        $query = $this->db->get();
        return $query->row();
    }

    /*
    This is used to delete lesson
    */	
	public function remove($id)
    {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('online_course_lesson');
        $message   = DELETE_RECORD_CONSTANT . " On online course lesson id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }
    }
    
    /*
    This is used to validate video duration
    */
    public function validateduration($str)
    {
        $str = $this->input->post('lesson_duration');
        if (!empty($str)) {
            $str_arr = explode(":", $str);
            if (count($str_arr) == 3) {
                $hh = $str_arr[0];
                $mm = $str_arr[1];
                $ss = $str_arr[2];
                if (!is_numeric($hh) || !is_numeric($mm) || !is_numeric($ss)) {
                    $this->form_validation->set_message('check_exists', $this->lang->line('duration') . ' field is Not Numeric');
                    return false;
                } else if ((int) $hh == 00 && (int) $mm == 00 && (int) $ss == 00) {
                    $this->form_validation->set_message('check_exists', $this->lang->line('duration_should_be_greater'));
                    return false;
                } else if ((int) $hh > 99 || (int) $mm > 59 || (int) $ss > 59) {
                    $this->form_validation->set_message('check_exists', $this->lang->line('duration') . ' field is Invalid Time Format');
                    return false;
                } else if (mktime((int) $hh, (int) $mm, (int) $ss) === false) {
                    $this->form_validation->set_message('check_exists', $this->lang->line('duration') . ' field is Invalid Time Format');
                    return false;
                }
                return true;
            } else {
                $this->form_validation->set_message('check_exists', $this->lang->line('duration') . ' field is Inavlid Time Format');
                return false;
            }
            return true;
        } 
    }
}