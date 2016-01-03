<?php
/**
 * Created by PhpStorm.
 * User: dell
 * Date: 5/26/2015
 * Time: 1:08 PM
 */

namespace qeywork;


class MailerConfigurator {
    const MODE_SMTP = "SMTP";

    private $mode = "";
    private $fromAddressBeSpecified = true;

    private $host;
    private $port;
    private $username;
    private $password;

    private $allowedFromName;
    private $allowedFromAddress;

    public function useSmtp($host, $port, $username, $password)
    {
        $this->mode = self::MODE_SMTP;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function configureMailer(PHPMailer $mailer) {
        if ($this->mode == self::MODE_SMTP) {
            $mailer->IsSMTP();
            $mailer->SMTPAuth = true;
            $mailer->Host = $this->host;
            $mailer->Port = $this->port;
            $mailer->Username = $this->username;
            $mailer->Password = $this->password;
        }

        if (! empty($this->allowedFromAddress)) {
            $mailer->SetFrom($this->allowedFromAddress, $this->allowedFromName);
        }
    }

    public function setAllowedFrom($name, $address) {
        $this->fromAddressBeSpecified = false;
        $this->allowedFromName = $name;
        $this->allowedFromAddress = $address;
    }

    public function canFromAddressBeSpecified() {
        return $this->fromAddressBeSpecified;
    }
}