<?php
namespace QeyWork\Tools\Mailer;

//require_once(dirname(__FILE__)."/class.phpmailer.php");

class Mailer
{
    private $phpMailer;
    private $config;

    public function __construct(MailerConfigurator $config) {
        $this->phpMailer = new PHPMailer(true);
        $this->phpMailer->PluginDir = dirname(__FILE__)."/";
        $this->phpMailer->CharSet	= "utf-8";
        $this->phpMailer->Encoding	= "base64";
        $config->configureMailer($this->phpMailer);

        $this->config = $config;
    }
    
    public function setFromAddress($address, $name = '') {
        if ($this->config->canFromAddressBeSpecified()) {
            $this->phpMailer->SetFrom($address, $name);
        } else {
            $this->phpMailer->AddReplyTo($address, $name);
        }
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
        $this->config->configureMailer($this->phpMailer);
        return true;
    }
}
