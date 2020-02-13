<?php
    $to = "info@chillibits.com";
    $from = $_REQUEST['email'];
    $name = $_REQUEST['name'];
    $subject = $_REQUEST['subject'];
    $message = $_REQUEST['message'];

    $headers = "From: $from";
	$headers = "From: $from\r\n";
	$headers .= "Reply-To: $from\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

	$body = "Nachricht von $name ($from)<br>";
    $body .= "Betreff: $subject<br>";
    $body .= "Message: $message<br>";

    $send = mail($to, "Neue Nachricht von ChilliBits Homepage - ".$from, $body, $headers);
    header("Location: ./index.php");
?>