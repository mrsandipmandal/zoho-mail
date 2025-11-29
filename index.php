<?php

require 'vendor/autoload.php';

use ZohoMail\ZohoMail;
use PHPMailer\PHPMailer\Exception;

// Example usage (commented). Replace credentials and uncomment to test.

/* try {
    $zoho = new ZohoMail(
        'YourZohoMail@zohomail.in',
        'YourZohoPassword',  // Replace with your App Password if 2FA is enabled
        true  // Enable debug (set to false in production)
    );

    // Example email
    $zoho->setFrom('YourZohoMail@zohomail.in', 'Test Sender')
        ->addTo('sender@gmail.com', 'Test Receiver')
        ->setSubject('Zoho SMTP Test')
        ->setBody('If you see this, Zoho SMTP works.')
        ->send();

    echo "✓ Email sent successfully!\n";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
} */


// If you want programmatic usage, create an instance of ZohoMail from \ZohoMail\ZohoMail
