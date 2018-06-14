<?php
require_once(APPPATH . "third_party/PHPMailer/class.phpmailer.php");
require_once(APPPATH . "third_party/PHPMailer/class.smtp.php");
class Send_email extends PHPMailer
{
    public function __construct()
    {
        parent::__construct();
        $this->CI =&get_instance();
    }
    
    public function _Send($subject, $message, $from, $to) {
        $this->CI->load->model('setting_model');
        $emails = $this->CI->setting_model->_Get_Setting('emails');
        $this->IsSMTP();
        $this->SMTPDebug = false;
        $this->SMTPAuth = true;
        $this->SMTPSecure = 'tls';
        $this->Host = "smtp.gmail.com";
        $this->Port = 587;
        $this->Username = $this->CI->setting_model->_Get_Setting('smtp_email');
        $this->Password = $this->CI->setting_model->_Get_Setting('smtp_password');
        $this->CharSet = 'UTF-8';
        $this->MsgHTML($message);
        $this->Subject = $subject;
        $this->SetFrom($from, $this->CI->setting_model->_Get_Setting('title'));
        $this->FromName = 'muonnha.com.vn';
        $this->AddReplyTo($from);
        $this->AddAddress($to);
        $this->IsHTML(true);
        
        if($this->Send()) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
} 
?>