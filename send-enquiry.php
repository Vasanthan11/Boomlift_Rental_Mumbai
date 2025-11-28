<?php
// send-enquiry.php
// Handles enquiry form submissions for Boomlift Rentals Mumbai

// CONFIGURATION
$toEmail = "info@boomliftrentalsmumbai.com";   // Receiving email
$fromName = "Boomlift Rentals Mumbai Website";
$redirectUrl = "thank-you.html";               // Redirect page after success

// Helper sanitizing function
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Handle only POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Form fields
    $name      = sanitize($_POST["name"] ?? "");
    $phone     = sanitize($_POST["phone"] ?? "");
    $email     = sanitize($_POST["email"] ?? "");
    $location  = sanitize($_POST["location"] ?? "");
    $equipment = sanitize($_POST["equipment"] ?? "");

    // Validation — ONLY Name & Phone required
    $errors = [];
    if ($name === "")  $errors[] = "Full Name is required.";
    if ($phone === "") $errors[] = "Contact Number is required.";

    // Return errors if any
    if (!empty($errors)) {
        http_response_code(400);
        echo "<h2>There was a problem:</h2><ul>";
        foreach ($errors as $e) echo "<li>$e</li>";
        echo "</ul><p><a href='javascript:history.back()'>← Go back</a></p>";
        exit;
    }

    // Email Subject + Body
    $subject = "New Rental Enquiry - Boomlift Rentals Mumbai";

    $body = "
New enquiry received:

Full Name: $name
Phone: $phone
Email: $email
Location: $location
Equipment: $equipment

Time: " . date("Y-m-d H:i:s");

    // Email headers
    $headers = "From: $fromName <" . $toEmail . ">\r\n";
    if ($email !== "") {
        $headers .= "Reply-To: $email\r\n";
    }

    // Send the email
    if (mail($toEmail, $subject, $body, $headers)) {
        if (!empty($redirectUrl)) {
            header("Location: $redirectUrl");
            exit;
        } else {
            echo "<h2>Thank You!</h2><p>Your enquiry has been received. We will contact you shortly.</p>";
        }
    } else {
        echo "<h2>Error!</h2><p>Sorry, your message could not be sent. Please try again or call us.</p>";
    }
} else {
    echo "Method Not Allowed";
}
