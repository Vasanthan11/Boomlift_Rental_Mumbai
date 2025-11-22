<?php
// send-enquiry.php
// Handles enquiry form submissions for Boomlift Rentals Mumbai

// CONFIGURATION – CHANGE THIS IF NEEDED
$toEmail = "info@boomliftrentalsmumbai.com";  // Where enquiries will be sent
$fromName = "Boomlift Rentals Mumbai Website";
$redirectUrl = "thank-you.html";  // Optional — create this page or leave empty

// Helper sanitizing function
function sanitize($data)
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Only handle POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Match EXACT form field names:
    $name     = sanitize($_POST["name"] ?? "");
    $email    = sanitize($_POST["email"] ?? "");
    $phone    = sanitize($_POST["phone"] ?? "");
    $location = sanitize($_POST["location"] ?? "");
    $equipment = sanitize($_POST["equipment"] ?? "");

    // Validation
    $errors = [];
    if ($name === "") $errors[] = "Full Name is required.";
    if ($email === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid Email Address is required.";
    }
    if ($phone === "") $errors[] = "Contact Number is required.";
    if ($location === "") $errors[] = "Area / Location is required.";
    if ($equipment === "") $errors[] = "Please select an equipment type.";

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
Email: $email
Phone: $phone
Location: $location
Equipment: $equipment

Time: " . date("Y-m-d H:i:s");

    // Email Headers
    $headers = "From: $fromName <$toEmail>\r\n";
    $headers .= "Reply-To: $name <$email>\r\n";

    // Send Email
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
