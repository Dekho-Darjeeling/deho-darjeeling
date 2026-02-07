<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

/* ---------- BLOCK DIRECT ACCESS ---------- */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.html');
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
$purpose  = clean($_POST['purpose'] ?? '');
$message  = clean($_POST['message'] ?? '');

/* ---------- BOT PROTECTION ---------- */
if (!empty($_POST['bot-field'])) {
    exit;
}

/* ---------- FORM NAME ---------- */
$formName = $_POST['form-name'] ?? '';

/* ---------- DEFAULT EMAIL CONFIG ---------- */
$adminEmail = 'dekhodarj@gmail.com';
$replyEmail = 'dekhodarj@gmail.com';

/* ---------- FORM SWITCH ---------- */
switch ($formName) {

    /* ===== TOUR BOOKING ===== */
    case 'tour-booking':

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

    /* ===== TREKKING BOOKING ===== */
    case 'trekking-booking':

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

    /* ===== CONTACT FORM ===== */
    case 'contact-form':

        $adminSubject = 'New Contact Inquiry - Dekho Darjeeling';

        $adminBody = "
NEW CONTACT MESSAGE

Name: $name
Email: $email
Phone: $phone
Inquiry Type: $purpose

Message:
$message
";

        $detailsTable = "
            <tr><td><strong>Inquiry Type</strong></td><td>$purpose</td></tr>
            <tr><td><strong>Message</strong></td><td>$message</td></tr>
        ";
        break;

    default:
        header('Location: index.html?error=invalid');
        exit;
}

/* ---------- CUSTOMER AUTO-REPLY TEMPLATE ---------- */
$userSubject = 'We received your request – Dekho Darjeeling 🌄';

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
Thank you for contacting <strong>Dekho Darjeeling</strong>.
We have received your request and our team will reach out shortly.
</p>

<table width="100%" cellpadding="8" cellspacing="0" style="border:1px solid #ddd;background:#fafafa;margin:20px 0;">
'.$detailsTable.'
</table>

<p>
If you have any urgent queries, feel free to reply to this email.
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

/* ---------- SMTP SEND ---------- */
try {

    /* === ADMIN MAIL === */
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dekhodarj@gmail.com';
    $mail->Password   = 'bobyewcszxouawvh';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('dekhodarj@gmail.com', 'Dekho Darjeeling Website');
    $mail->addAddress($adminEmail);
    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);
    $mail->Subject = $adminSubject;
    $mail->Body    = nl2br($adminBody);
    $mail->send();

    /* === CUSTOMER AUTO-REPLY === */
    $userMail = new PHPMailer(true);
    $userMail->isSMTP();
    $userMail->Host       = 'smtp.gmail.com';
    $userMail->SMTPAuth   = true;
    $userMail->Username   = 'dekhodarj@gmail.com';
    $userMail->Password   = 'bobyewcszxouawvh';
    $userMail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $userMail->Port       = 587;

    $userMail->setFrom('dekhodarj@gmail.com', 'Dekho Darjeeling');
    $userMail->addAddress($email, $name);
    $userMail->addEmbeddedImage('frontend/assets/images/DekhoDarjeeling_PNG.png', 'logoimg');

    $userMail->isHTML(true);
    $userMail->Subject = $userSubject;
    $userMail->Body    = $userBody;
    $userMail->send();

    header('Location: thank-you.html');
    exit;

} catch (Exception $e) {
    echo "Mailer Error: " . $e->getMessage();
}
