<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

/* ---------- BLOCK DIRECT ACCESS ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: tours.html');
    exit;
}

/* ---------- CLEAN INPUT ---------- */
function clean($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/* ---------- COMMON FIELDS ---------- */
$name     = clean($_POST['name'] ?? '');
$email    = clean($_POST['email'] ?? '');
$phone    = clean($_POST['phone'] ?? '');
$date     = clean($_POST['date'] ?? '');
$persons  = clean($_POST['persons'] ?? '');
$notes    = clean($_POST['notes'] ?? 'None');

/* ---------- OPTIONAL ---------- */
$route    = clean($_POST['route'] ?? '');
$duration = clean($_POST['duration'] ?? '');
$package  = clean($_POST['package'] ?? '');
$pickup   = clean($_POST['pickup'] ?? 'Not provided');

/* ---------- BOT PROTECTION ---------- */
if (!empty($_POST['bot-field'])) {
    exit;
}

$formName = $_POST['form-name'] ?? '';

/* ---------- FORM SWITCH ---------- */
switch ($formName) {

    /* ===== TOUR ===== */
    case 'tour-booking':
        $adminEmail = 'roshnitravels2022@gmail.com';
        $replyEmail = 'roshnitravels2022@gmail.com';
        $ccEmail    = 'dekhodarj@gmail.com';

        $adminSubject = 'New Tour Booking - Dekho Darjeeling';
        $adminBody = "
NEW TOUR BOOKING

Name: $name
Email: $email
Phone: $phone
Package: $package
Date: $date
People: $persons
Pickup: $pickup
Notes: $notes
";

        $detailsTable = "
            <tr><td><strong>Package</strong></td><td>$package</td></tr>
            <tr><td><strong>Date</strong></td><td>$date</td></tr>
            <tr><td><strong>Travelers</strong></td><td>$persons</td></tr>
        ";
        break;

    /* ===== TREKKING ===== */
    case 'trekking-booking':
        $adminEmail = 'dekhodarj@gmail.com';
        $replyEmail = 'dekhodarj@gmail.com';

        $adminSubject = 'New Trekking Booking - Dekho Darjeeling';
        $adminBody = "
NEW TREKKING BOOKING

Name: $name
Email: $email
Phone: $phone
Route: $route
Duration: $duration
Date: $date
Participants: $persons
Notes: $notes
";

        $detailsTable = "
            <tr><td><strong>Route</strong></td><td>$route</td></tr>
            <tr><td><strong>Duration</strong></td><td>$duration</td></tr>
            <tr><td><strong>Date</strong></td><td>$date</td></tr>
            <tr><td><strong>Participants</strong></td><td>$persons</td></tr>
        ";
        break;

    default:
        header('Location: tours.html?error=invalid');
        exit;
}

/* ---------- HTML EMAIL TEMPLATE ---------- */
$userSubject = 'Booking Confirmation – Dekho Darjeeling 🌄';

$userBody = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<body style="margin:0;padding:0;background:#f4f6f8;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center" style="padding:30px 10px;">
<table width="600" style="background:#ffffff;border-radius:8px;overflow:hidden;">

<tr>
<td align="center" style="background:#1e5b3a;padding:25px;">
<img src="cid:logoimg" style="max-width:220px;" alt="Dekho Darjeeling">
</td>
</tr>

<tr>
<td style="padding:30px;color:#333;">
<h2>Dear '.$name.',</h2>

<p>
Thank you for choosing <strong>Dekho Darjeeling</strong>.
We have received your booking request successfully.
</p>

<table width="100%" cellpadding="8" cellspacing="0" style="border:1px solid #ddd;background:#fafafa;margin:20px 0;">
'.$detailsTable.'
</table>

<p>
Our team will contact you shortly to confirm the details and next steps.
</p>

<p style="margin-top:30px;">
Warm regards,<br>
<strong>Dekho Darjeeling Team</strong><br>
🌄 Explore the Queen of Hills
</p>
</td>
</tr>

<tr>
<td align="center" style="background:#f1f1f1;padding:15px;font-size:12px;color:#666;">
© '.date('Y').' Dekho Darjeeling. All rights reserved.
</td>
</tr>

</table>
</td>
</tr>
</table>
</body>
</html>
';

/* ---------- SEND MAIL ---------- */
$mail = new PHPMailer(true);

try {
    // SMTP
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dekhodarj@gmail.com';   // MAIN SMTP
    $mail->Password   = 'pqitlkouqatbwarz';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // FROM & REPLY
    $mail->setFrom('dekhodarj@gmail.com', 'Dekho Darjeeling');
    $mail->addReplyTo($replyEmail, 'Dekho Darjeeling');

    /* ---------- ADMIN MAIL ---------- */
$mail->addAddress($adminEmail);

/* CC copy for tour booking */
if ($formName === 'tour-booking') {
    $mail->addCC($ccEmail);
}

$mail->isHTML(false);
$mail->Subject = $adminSubject;
$mail->Body    = $adminBody;
$mail->send();


    /* ---------- CUSTOMER MAIL ---------- */
   $mail->clearAddresses();
$mail->clearCCs();
$mail->clearBCCs();

$mail->isHTML(true);
$mail->CharSet = 'UTF-8';


    $mail->addEmbeddedImage(__DIR__ . '/frontend/assets/images/logo.jpeg', 'logoimg');
    $mail->addAddress($email);
    $mail->Subject = $userSubject;
    $mail->Body    = $userBody;
    $mail->send();

    header('Location: thank-you.html');
    exit;

} catch (Exception $e) {
    error_log($mail->ErrorInfo);
    header('Location: tours.html?error=mail');
    exit;
}
