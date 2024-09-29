<?php
// Check if form is submitted
session_start();
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user inputs
    $name = filter_var(trim($_POST['name']), FILTER_SANITIZE_STRING);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = filter_var(trim($_POST['subject']), FILTER_SANITIZE_STRING);
    $message = filter_var(trim($_POST['message']), FILTER_SANITIZE_STRING);

    // Initialize error array
    $errors = [];

    // Validate name
    if (empty($name)) {
        $errors[] = "Name is required.";
    }

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    // Validate subject
    if (empty($subject)) {
        $errors[] = "Subject is required.";
    }

    // Validate message
    if (empty($message)) {
        $errors[] = "Message is required.";
    }

    // If there are no errors, send the email
    if (empty($errors)) {
        $to = "sisaybekele735@gmail.com"; // Replace with your own email address
        $headers = "From: $name <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        // Email subject
        $mail_subject = "Contact Form: " . $subject;

        // Email body
        $mail_body = "
            <html>
            <head>
                <title>$subject</title>
            </head>
            <body>
                <h2>New Contact Form Submission</h2>
                <p><strong>Name: </strong> $name </p>
                <p><strong>Email: </strong> $email </p>
                <p><strong>Subject: </strong> $subject </p>
                <p><strong>Message: </strong></p>
                <p>$message</p>
            </body>
            </html>
        ";

        // Send the email
        if (mail($to, $mail_subject, $mail_body, $headers)) {
            echo json_encode(['success' => 'Your message has been sent. Thank you!']);
        } else {
            echo json_encode(['error' => 'There was an error sending your message. Please try again later.']);
        }
    } else {
        // Return validation errors
        echo json_encode(['error' => implode(", ", $errors)]);
    }
} else {
    // Return an error if the form wasn't submitted via POST
    echo json_encode(['error' => 'Invalid request.']);
}
?>
