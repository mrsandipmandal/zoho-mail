# Zoho Mail SMTP PHPMailer

A clean, reusable PHP class wrapper for sending emails via Zoho Mail SMTP using PHPMailer. Perfect for applications requiring reliable email delivery with support for attachments, templates, and multiple recipients.

## Features

- ✅ **Zoho Mail Integration** — Configured for Zoho's India region (`smtp.zoho.in:587`)
- ✅ **Fluent Interface** — Chain methods for clean, readable code
- ✅ **File Attachments** — Attach files to emails easily
- ✅ **Template Support** — Load HTML/text templates with variable replacement
- ✅ **Multiple Recipients** — Add To, CC, and BCC recipients
- ✅ **Debug Mode** — Toggle SMTP debug output for troubleshooting
- ✅ **Error Handling** — Comprehensive exception handling and error messages
- ✅ **2FA Support** — Works with Zoho App Passwords for accounts with 2FA enabled

## Installation

### Prerequisites

- PHP 7.0 or higher
- Composer (for dependency management)
- A Zoho Mail account (free or paid)

### Step 1: Clone or Download

```bash
git clone https://github.com/mrsandipmandal/zoho-mail.git
cd zoho-mail
```

### Step 2: Install Dependencies

```bash
composer install
```

This will install PHPMailer 7.0+ from Composer.

### Install via Composer (recommended)

If you published the package to Packagist, install it into your project with Composer:

```bash
# stable release (preferred)
composer require php-zoho-mail/zoho-mail

```

If Packagist shows only a dev branch (no stable tag), require the dev branch explicitly:

```bash
composer require php-zoho-mail/zoho-mail:dev-master
# or use dev-main if your default branch is main
composer require php-zoho-mail/zoho-mail:dev-main
```

If you get a "minimum-stability" error, either add an explicit dev constraint (as above) or allow dev stability in your project's `composer.json`:

```json
{
    "minimum-stability": "dev",
    "prefer-stable": true
}
```

Recommendation: publish a stable tag (e.g., `v1.0.0`) in your package repository and update Packagist — then projects can require the package without dev constraints:

```bash
# in your package repo
# git tag -a v1.0.0 -m "v1.0.0"
# git push origin v1.0.0
```

### Step 3: Configure Credentials

Provide credentials when you instantiate the `ZohoMail` class (do not edit package files).

You can pass credentials directly to the constructor or load them from environment variables. Example:

```php
// prefer using Composer autoload and environment variables
require 'vendor/autoload.php';

use ZohoMail\ZohoMail;

$zoho = new ZohoMail(
    'your-email@zohomail.in',      // Your Zoho Mail address
    'your-app-password',           // Your Zoho password or App Password
    true                           // Debug mode (true for development, false for production)
);
```

## Getting Your Zoho Credentials

### Standard Password

If your Zoho account **does not have 2FA enabled**, use your regular account password.

### App Password (Recommended for 2FA)

If your Zoho account **has 2FA enabled**, you must create an App Password:

1. Log in to [https://accounts.zoho.com](https://accounts.zoho.com)
2. Navigate to **Security** → **App Passwords**
3. Select "Mail" from the application dropdown
4. Click **Generate**
5. Copy the generated password and paste it into the `index.php` script

## Usage

### Basic Email

```php
<?php
require 'vendor/autoload.php';

use ZohoMail\ZohoMail;

try {
    // Construct with (username, password/app-password, debug)
    $zoho = new ZohoMail('your-email@zohomail.in', 'your-app-password', false);

    $zoho->setFrom('your-email@zohomail.in', 'Your Name')
         ->addTo('recipient@example.com', 'Recipient Name')
         ->setSubject('Hello!')
         ->setBody('This is a test email.', true) // second param is isHTML (default true)
         ->send();

    echo "✓ Email sent successfully!";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}
```

### Email with Attachments

```php
$zoho->setFrom('your-email@zohomail.in', 'Sender')
    ->addTo('recipient@example.com')
    ->setSubject('Invoice')
    ->setBody('Please see the attached invoice.', false) // plain text body
    ->attachFile('/path/to/invoice.pdf', 'invoice.pdf')
    ->attachFile('/path/to/receipt.txt', 'receipt.txt')
    ->send();
```

### Email with Templates

Create a template file `templates/welcome.html`:

```html
<h1>Welcome, {{name}}!</h1>
<p>Your account {{email}} has been created successfully.</p>
<p>Click <a href="{{link}}">here</a> to get started.</p>
```

Use it in your code:

```php
$zoho->setFrom('your-email@zohomail.in', 'Team')
     ->addTo('newuser@example.com')
     ->setSubject('Welcome to Our Service!')
     ->attachTemplate('templates/welcome.html', [
         'name' => 'John Doe',
         'email' => 'john@example.com',
         'link' => 'https://example.com/activate'
     ])
     ->send();
```

### Multiple Recipients

```php
$zoho->setFrom('your-email@zohomail.in', 'Sender')
     ->addTo('recipient1@example.com', 'Recipient 1')
     ->addTo('recipient2@example.com', 'Recipient 2')
     ->addCC('cc@example.com', 'CC Person')
     ->addBCC('bcc@example.com', 'BCC Person')
     ->setSubject('Group Email')
     ->setBody('Message for everyone.')
     ->send();
```

### With Plain Text Alternative

```php
$zoho->setFrom('your-email@zohomail.in', 'Sender')
     ->addTo('recipient@example.com')
     ->setSubject('Newsletter')
     ->setBody('<h1>Hello</h1><p>HTML content here</p>', true)  // HTML
     ->setAltBody('Hello\n\nPlain text content here')           // Plain text fallback
     ->send();
```

## ZohoMail Class Reference

### Constructor

```php
new ZohoMail($username, $password, $debug = false)
```

- `$username` (string) — Zoho Mail email address
- `$password` (string) — Zoho password or App Password
- `$debug` (bool) — Enable SMTP debug output (default: `false`)

### Methods

#### Core Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `setFrom($email, $name)` | Set sender address | `$this` |
| `addTo($email, $name)` | Add recipient | `$this` |
| `addCC($email, $name)` | Add CC recipient | `$this` |
| `addBCC($email, $name)` | Add BCC recipient | `$this` |
| `setSubject($subject)` | Set email subject | `$this` |
| `setBody($body, $isHTML)` | Set email body | `$this` |
| `setAltBody($text)` | Set plain text alternative | `$this` |
| `send()` | Send the email | `bool` |

#### Attachment Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `attachFile($filePath, $fileName)` | Attach a file | `$this` |
| `attachTemplate($templatePath, $variables)` | Load and render template | `$this` |

#### Utility Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `getError()` | Get last error message | `string` |
| `getMailer()` | Get PHPMailer instance (advanced) | `PHPMailer` |

## Troubleshooting

### 535 Authentication Failed

**Cause:** Incorrect credentials or Zoho account configuration.

**Solutions:**
1. Verify your email address and password are correct
2. If you have 2FA enabled, generate and use an **App Password** instead
3. Confirm your Zoho account is not locked or requiring verification
4. Check that SMTP access is enabled in your Zoho account security settings

### Connection Timeout

**Cause:** Firewall or network blocking port 587.

**Solutions:**
1. Check if port 587 is open on your server/machine
2. Contact your ISP or hosting provider if blocked
3. Ask your network administrator for firewall access

### Certificate Verification Error

**Cause:** SSL certificate verification issues (rare).

**Solution (Development Only):**
Uncomment the certificate bypass in `index.php` (not recommended for production):

```php
$zoho->getMailer()->SMTPOptions = [
    'ssl' => [
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true,
    ]
];
```

### Template Variables Not Replacing

**Cause:** Variable syntax mismatch.

**Solution:** Ensure variables use double curly braces: `{{variableName}}`

```php
// Template file
Hello {{name}}, your email is {{email}}.

// PHP code
attachTemplate('path/to/template.html', [
    'name' => 'John',
    'email' => 'john@example.com'
])
```

## Configuration Details

The class is pre-configured for **Zoho Mail India region** (`smtp.zoho.in`). If you use a different region, edit the `$config` array in the `ZohoMail` constructor:

```php
// US region example
$this->config = [
    'host' => 'smtp.zoho.com',  // US endpoint
    'port' => 587,
    'secure' => PHPMailer::ENCRYPTION_STARTTLS,
];

// EU region example
$this->config = [
    'host' => 'smtp.zoho.eu',   // EU endpoint
    'port' => 587,
    'secure' => PHPMailer::ENCRYPTION_STARTTLS,
];
```

## Debug Mode

Enable debug output during development by setting the third parameter to `true`:

```php
$zoho = new ZohoMail('email@zohomail.in', 'password', true);
```

This will display detailed SMTP server communication. **Disable for production** (`false`) to avoid exposing sensitive information.

## Security Best Practices

1. **Never hardcode credentials** in production. Use environment variables:
   ```php
   $zoho = new ZohoMail(
       $_ENV['ZOHO_EMAIL'],
       $_ENV['ZOHO_PASSWORD'],
       $_ENV['ZOHO_DEBUG'] ?? false
   );
   ```

2. **Use App Passwords** if 2FA is enabled on your Zoho account

3. **Disable debug mode** in production

4. **Keep dependencies updated:**
   ```bash
   composer update
   ```

5. **Validate email addresses** before sending:
   ```php
   if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
       $zoho->addTo($email)->send();
   }
   ```

## Testing

Run a quick test from the command line:

```bash
php index.php
```

Or access via a web browser:

```
http://localhost:8000/index.php
```

(Make sure to start a PHP development server first: `php -S localhost:8000`)

## Environment Setup (Optional)

Create a `.env` file to store credentials securely:

```env
ZOHO_EMAIL=your-email@zohomail.in
ZOHO_PASSWORD=your-app-password
ZOHO_DEBUG=false
```

Then in your code:

```php
require 'vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

include 'index.php';

$zoho = new ZohoMail($_ENV['ZOHO_EMAIL'], $_ENV['ZOHO_PASSWORD'], $_ENV['ZOHO_DEBUG']);
```

(Install `vlucas/phpdotenv` via Composer for `.env` support)

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/my-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/my-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License. See LICENSE file for details.

## Support

- **PHPMailer Documentation:** [https://github.com/PHPMailer/PHPMailer](https://github.com/PHPMailer/PHPMailer)
- **Zoho Mail Help:** [https://www.zoho.com/mail/help/](https://www.zoho.com/mail/help/)
- **Issues:** Open an issue on GitHub with detailed information

## Changelog

### v1.0.0 (2025-11-27)
- Initial release
- ZohoMail class with fluent interface
- Support for Zoho Mail (India region)
- File attachments and template support
- Multiple recipient handling
- Comprehensive error handling

## Author

Created for reliable Zoho Mail SMTP integration in PHP applications.

---

**Happy emailing! 📧**
