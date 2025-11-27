<?php
/* 
composer require phpmailer/phpmailer
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

/**
 * ZohoMailer: A wrapper class for PHPMailer configured for Zoho Mail SMTP
 */
class ZohoMailer
{
    private $mailer;
    private $config;

    /**
     * Constructor - Initialize Zoho Mail SMTP configuration
     *
     * @param string $username Zoho email address
     * @param string $password Zoho password or App Password (if 2FA enabled)
     * @param bool $debug Enable SMTP debug output
     */
    public function __construct($username, $password, $debug = false)
    {
        $this->config = [
            'username' => $username,
            'password' => $password,
            'host' => 'smtp.zoho.in',        // India region
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
        $this->mailer->SMTPDebug  = $debug ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
    }

    /**
     * Set the sender (From) address
     */
    public function setFrom($email, $name = '')
    {
        $this->mailer->setFrom($email, $name);
        return $this;
    }

    /**
     * Add a recipient (To)
     */
    public function addTo($email, $name = '')
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    /**
     * Add a CC recipient
     */
    public function addCC($email, $name = '')
    {
        $this->mailer->addCC($email, $name);
        return $this;
    }

    /**
     * Add a BCC recipient
     */
    public function addBCC($email, $name = '')
    {
        $this->mailer->addBCC($email, $name);
        return $this;
    }

    /**
     * Add a file attachment
     */
    public function attachFile($filePath, $fileName = '')
    {
        if (!file_exists($filePath)) {
            throw new Exception("File not found: {$filePath}");
        }
        $this->mailer->addAttachment($filePath, $fileName);
        return $this;
    }

    /**
     * Attach template from file (loaded as body)
     *
     * @param string $templatePath Path to template file
     * @param array $variables Variables to replace in template (optional)
     */
    public function attachTemplate($templatePath, $variables = [])
    {
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: {$templatePath}");
        }
        $content = file_get_contents($templatePath);

        // Replace variables if provided (e.g., {{name}} => $variables['name'])
        foreach ($variables as $key => $value) {
            $content = str_replace("{{$key}}", $value, $content);
        }

        $this->mailer->Body = $content;
        return $this;
    }

    /**
     * Set email subject
     */
    public function setSubject($subject)
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    /**
     * Set email body (plain text or HTML)
     */
    public function setBody($body, $isHTML = true)
    {
        $this->mailer->isHTML($isHTML);
        $this->mailer->Body = $body;
        return $this;
    }

    /**
     * Set plain text alternative (for HTML emails)
     */
    public function setAltBody($altBody)
    {
        $this->mailer->AltBody = $altBody;
        return $this;
    }

    /**
     * Send the email
     *
     * @return bool True on success
     * @throws Exception on error
     */
    public function send()
    {
        return $this->mailer->send();
    }

    /**
     * Get last error message
     */
    public function getError()
    {
        return $this->mailer->ErrorInfo;
    }

    /**
     * Get the underlying PHPMailer instance for advanced usage
     */
    public function getMailer()
    {
        return $this->mailer;
    }
}

// ============================================================================
// USAGE EXAMPLES
// ============================================================================

try {
    // Initialize Zoho mailer
    $zoho = new ZohoMailer(
        'YOUR_FULL_EMAIL@zohomail.in',
        'YOUR_PASSWORD',  // Replace with your App Password if 2FA is enabled
        true  // Enable debug (set to false in production)
    );

    // Example email
    $zoho->setFrom('YOUR_FULL_EMAIL@zohomail.in', 'Test Sender')
        ->addTo('RECIPIENT_EMAIL@zohomail.com', 'Test Receiver')
        ->setSubject('Zoho SMTP Test')
        ->setBody('If you see this, Zoho SMTP works.')
        ->send();

    echo "✓ Email sent successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: {$e->getMessage()}\n";
}
