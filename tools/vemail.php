<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');

include 'sendgrid-php/vendor/autoload.php';
#require_once 'sendgrid-php/sendgrid-php.php';

echo "Q".getenv('SENDGRID_API_KEY')."Q";
$email = new \SendGrid\Mail\Mail();
$email->setFrom("report@playgps.in", "Example User");
$email->setSubject("Sending with Twilio SendGrid is Fun");
$email->addTo("kctnandha08@gmail.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);
$sendgrid = new \SendGrid('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');
try {
    $response = $sendgrid->send($email);
    print $response->statusCode() . "\n";
    print_r($response->headers());
    print $response->body() . "\n";
} catch (Exception $e) {
    echo 'Caught exception: '. $e->getMessage() ."\n";
}

?>