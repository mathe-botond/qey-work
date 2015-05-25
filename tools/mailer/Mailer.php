<?php
namespace qeywork;

//require_once(dirname(__FILE__)."/class.phpmailer.php");

class Mailer
{
    private $phpMailer;
    
    public function __construct() {
        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->PluginDir = dirname(__FILE__)."/";
        $this->phpMailer->CharSet	= "utf-8";
        $this->phpMailer->Encoding	= "base64";
    }
    
    public function setFromAddress($address, $name = '') {
        $this->phpMailer->SetFrom($address, $name);
    }
    
    public function addToAddress($address, $name = '') {
        $this->phpMailer->AddAddress($address, $name);
    }
    
    public function send($subject, $content, $files=array()) {
        $this->phpMailer->Subject = $subject;
        //$mail->Body = empty($content) ? "_" : $content;
        //$mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically
        $this->phpMailer->MsgHTML(empty($content) ? "<span></span>" : $content);
        foreach ($files as $fileName => $file) {
            $this->phpMailer->AddAttachment($file, $fileName);
        }
        
        $this->phpMailer->Send();
        
        $this->phpMailer->Clear();

        return true;
    }
}
