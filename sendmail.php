<?php
require "vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'includes/credentials.php';

function setupMailer()
{
    $mail = new PHPMailer(true);
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // Disable debug output
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = EMAIL;
        $mail->Password = PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;
        $mail->setFrom(EMAIL, 'Employee Management System');
        $mail->isHTML(true);
        return $mail;
    } catch (Exception $e) {
        error_log("Mailer setup failed: {$mail->ErrorInfo}");
    }
    return null;
}

function sendEmail($mail, $to, $subject, $body)
{
    try {
        $mail->clearAddresses(); // Clear previous recipients
        $mail->addAddress($to);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

function sendPasswordResetEmail($email, $resetToken)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }
    $resetLink = "https://yourwebsite.com/reset_password.php?token=$resetToken";
    $subject = "Password Reset Request";
    $body = "
        <p>Hello,</p>
        <p>We received a request to reset your password. You can reset it by clicking the link below:</p>
        <p><a href='$resetLink'>Reset Password</a></p>
        <p>If you did not request this, please ignore this email.</p>
        <p>Thank you!</p>
    ";
    return sendEmail($mail, $email, $subject, $body);
}

function sendLeaveApplicationEmail($supervisorEmail, $name, $from, $to, $type, $supervisorName)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }
    $redirectLink = "http://localhost/leave_portal/index.php";
    $subject = "$type Leave Application";
    $body = "
        <p>Hello $supervisorName,</p>
        <p>$name has applied for $type leave from $from to $to.</p>
        <p>Please log into the Leave Application Portal and review using</p><p><a href='$redirectLink'>Redirect Link</a></p>
        <p>Thank you.</p>
    ";
    return sendEmail($mail, $supervisorEmail, $subject, $body);
}

function sendLeaveNotification($employeeEmails, $employeeName, $fromDate, $toDate, $leaveType, $status)
{
    $mail = setupMailer();
    if (!$mail) {
        return false;
    }

    $subject = $status == 1 ? "Leave Approval Notification" : "Leave Recall Notification";
    $body = $status == 1 ? "
        <p>Hello,</p>
        <p>We are pleased to inform you that $employeeName's $leaveType leave from $fromDate to $toDate has been approved.</p>
        <p>Thank you.</p>
    " : "
        <p>Hello,</p>
        <p>We regret to inform you that $employeeName's $leaveType leave from $fromDate to $toDate has been recalled.</p>
        <p>Thank you.</p>
    ";
    $success = true;
    foreach ($employeeEmails as $email) {
        if (!sendEmail($mail, $email, $subject, $body)) {
            $success = false;
            error_log("Failed to send leave notification to: $email");
        }
    }
    return $success;
}
