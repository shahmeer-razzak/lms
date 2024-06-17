<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Stripe extends Student_Controller {

    public $setting = "";
  
    function __construct() {
        parent::__construct();
		$this->load->model(array('course_model','coursesection_model','courselesson_model','studentcourse_model','coursequiz_model','course_payment_model','courseofflinepayment_model','coursereport_model','currency_model'));
        $this->load->library('course_stripe_payment');
        $this->setting = $this->setting_model->get();
        $this->load->library('course_mail_sms');
        $this->load->library('cart');
          $this->currency_name= $this->currency_model->get($this->session->userdata('student')['currency'])->short_name;
    }

    /*
    This is used to show payment detail page
    */
    public function index() {  
 
        $stripedetails = $this->paymentsetting_model->getActiveMethod();
        $data['params'] = $this->session->userdata('course_amount');
        $data['params']['api_publishable_key'] =$stripedetails->api_publishable_key;
        $data['setting'] = $this->setting;
        $data['currency_name']=$this->currency_name;
          
        $this->load->view('user/studentcourse/online_course/stripe/index', $data);
    }

    /*
    This is used to show success page status and payment gateway functionality
    */
    public function complete() {
        
        $params = $this->session->userdata('course_amount');

        $stripedetails = $this->paymentsetting_model->getActiveMethod();
        $stripeToken = $this->input->post('stripeToken');
        $stripeTokenType = $this->input->post('stripeTokenType');
        $stripeEmail = $this->input->post('stripeEmail');
        $data = $this->input->post();

        $data['currency'] = $this->currency_name; 
        $data['total']=($params['actual_amount']*100);
         
        $response = $this->course_stripe_payment->make_payment($data);

        if ($response->isSuccessful()) {
            $transactionid = $response->getTransactionReference();
            $response = $response->getData();
            if ($response['status'] == 'succeeded') {                
                
                $payment_data = array(
                    'date' => date('Y-m-d'),
                    'student_id' => $params['student_id'],
                    'guest_id' => $params['guest_id'],
                    'online_courses_id' => $params['courseid'],
                    'course_name' => $params['course_name'],
                    'actual_price' => $params['actual_amount'],
                    'paid_amount' => $params['total_amount'],
                    'payment_type' => 'Online',
                    'transaction_id' =>  $transactionid,
                    'note' => "Online course fees deposit through Stripe Txn ID: " . $transactionid,
                    'payment_mode' => 'Stripe',
                );

            $this->course_payment_model->add($payment_data);
            if(!empty($params['courseid'])) {

                $sender_details = array('email'=>$params['email'], 'courseid'=>$params['courseid'],'class' => $params['class'],  'class_section_id'=> $params['class_sections'], 'section'=> $params['section'], 'title' => $params['course_name'], 'price' => $params['total_amount'], 'discount' => $params['discount'], 'assign_teacher' => $params['staff'], 'purchase_date' => $this->customlib->dateformat(date('Y-m-d')));    

                if($params['student_id']!=""){
                    $this->course_mail_sms->purchasemail('online_course_purchase', $sender_details);
                }elseif($params['guest_id']!=""){
                    $this->course_mail_sms->purchasemail('online_course_purchase_for_guest_user', $sender_details);
                }

                redirect(base_url("students/online_course/course_payment/paymentsuccess"));       
            }else{
                redirect(base_url('students/online_course/course_payment/paymentfailed'));
            } 
            }
        } elseif ($response->isRedirect()) {
           $response->redirect();
        } else {
           redirect(base_url("students/online_course/course_payment/paymentfailed"));
        }
    }


    public function guest() {  

        $stripedetails = $this->paymentsetting_model->getActiveMethod();
        $data['params']['api_publishable_key'] =$stripedetails->api_publishable_key;
        $data['setting'] = $this->setting;
        $data['currency_name']=$this->currency_name;
        $this->load->view('user/studentcourse/online_course/stripe/guest_course/index', $data);
    }

    public function guestcomplete() {
        
        $cart_data = $this->session->userdata('cart_data');

        $stripedetails = $this->paymentsetting_model->getActiveMethod();
        $stripeToken = $this->input->post('stripeToken');
        $stripeTokenType = $this->input->post('stripeTokenType');
        $stripeEmail = $this->input->post('stripeEmail');
        $data = $this->input->post();

        $data['currency'] = $this->setting[0]['currency']; 
        $data['total']=($this->input->post('total_cart_amount')*100);
        
        $response = $this->course_stripe_payment->make_payment($data);

        if ($response->isSuccessful()) {
            $transactionid = $response->getTransactionReference();
            $response = $response->getData();
            if ($response['status'] == 'succeeded') {  

            foreach ($cart_data as $cart_data_value) {

                $payment_data = array(
                    'date' => date('Y-m-d'),
                    'guest_id' => $cart_data_value['guest_id'],
                    'online_courses_id' => $cart_data_value['id'],
                    'course_name' => $cart_data_value['name'],
                    'actual_price' => $cart_data_value['actual_amount'],
                    'paid_amount' => $cart_data_value['price'],
                    'payment_type' => 'Online',
                    'transaction_id' =>  $transactionid,
                    'note' => "Online course fees deposit through Stripe Txn ID: " . $transactionid,
                    'payment_mode' => 'Stripe',
                );
                $this->course_payment_model->add($payment_data);
                $sender_details = array('email'=>$cart_data_value['email'], 'courseid'=> $cart_data_value['id'],'class' => null,  'class_section_id'=> null, 'section'=> null, 'title' => $cart_data_value['name'], 'price' => $cart_data_value['price'], 'discount' => $cart_data_value['discount'], 'assign_teacher' => $cart_data_value['staff'], 'purchase_date' => $this->customlib->dateformat(date('Y-m-d')));

                $this->course_mail_sms->purchasemail('online_course_purchase_for_guest_user', $sender_details);
            }

            redirect(base_url("students/online_course/course_payment/paymentsuccess")); 
 
            }
        } elseif ($response->isRedirect()) {
          // $response->redirect();
        } else {
           //redirect(base_url("students/online_course/course_payment/paymentfailed"));
        }
    }
}