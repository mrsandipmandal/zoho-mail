<?php

namespace ZohoMail;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ZohoMail
{
    private $mailer;
    private $config;

    public function __construct($username, $password, $debug = false)
    {
        $this->config = [
            'username' => $username,
            'password' => $password,
            'host' => 'smtp.zoho.in',
            'port' => 587,
            'secure' => PHPMailer::ENCRYPTION_STARTTLS,
        ];

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host       = $this->config['host'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $this->config['username'];
        $this->mailer->Password   = $this->config['password'];
        $this->mailer->SMTPSecure = $this->config['secure'];
        $this->mailer->Port       = $this->config['port'];
        $this->mailer->SMTPDebug  = $debug ? \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER : \PHPMailer\PHPMailer\SMTP::DEBUG_OFF;
    }

    public function setFrom($email, $name = '')
    {
        $this->mailer->setFrom($email, $name);
        return $this;
    }

    public function addTo($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    public function addCC($email, $name = '')
    {
        $this->mailer->addCC($email, $name);
        return $this;
    }

    public function addBCC($email, $name = '')
    {
        $this->mailer->addBCC($email, $name);
        return $this;
    }

    public function attachFile($filePath, $fileName = '')
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: {$filePath}");
        }
        $this->mailer->addAttachment($filePath, $fileName);
        return $this;
    }

    public function attachTemplate($templatePath, $variables = [])
    {
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: {$templatePath}");
        }
        $content = file_get_contents($templatePath);
        foreach ($variables as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }
        $this->mailer->Body = $content;
        return $this;
    }

    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    public function setBody($body, $isHTML = true)
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Body = $body;
        return $this;
    }

    public function setAltBody($altBody)
    {
        $this->mailer->AltBody = $altBody;
        return $this;
    }

    public function send()
    {
        return $this->mailer->send();
    }

    public function getError()
    {
        return $this->mailer->ErrorInfo;
    }

    public function getMailer()
    {
        return $this->mailer;
    }
}
