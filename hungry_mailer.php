<?php

/**
 * Hungry Mail
 * 
 * @author Indranil Dasgupta
 * @version 0.4.2
 * @copyright Indranil Dasgupta, 2010
 * @package default
 */

//	Optional configuration.
//	Either set these here, or set them on the form..
//	
//	If they're set in the form, they will take precendence over these values,
//	so you can have multiple forms with the same mailer ;)
//	To set them within the form, use the following elements :
//	--- <input type="hidden" name="to" value="email@address.com">
//	--- <input type="hidden" name="subject" value="Subject goes here">
//	And that's that.

$to = ''; // The email address of the receiver, i.e. : you.
$subject = ''; // The subject message.

// End configuration.
	
// First we set the receiver
if(isset($_POST['to']) && $_POST['to'] != '') {
	if (!get_magic_quotes_gpc()) {
	    $to = addslashes($_POST['to']);
	} else {
	    $to = $_POST['to'];
	}
}
// And the subject
if(isset($_POST['subject']) && $_POST['subject'] != '') {
	if (!get_magic_quotes_gpc()) {
	    $subject = addslashes($_POST['subject']);
	} else {
	    $subject = $_POST['subject'];
	}
}

// Stuff we don't want
$unchecked = array('to','subject','submit');

foreach($_POST as $post_field => $post_value) {
	$c = 0;
	foreach($unchecked as $no_check) {
		if($post_field == $no_check) {
			$c = 1;
			break;
		}
	}
	if($c == 1)
		continue;
	// This is what we're sending!
	if (!get_magic_quotes_gpc()) {
		$mailer['field'][] = addslashes($post_field);
		$mailer['value'][] = addslashes($post_value);
	} else {
	    $mailer['field'][] = $post_field;
		$mailer['value'][] = $post_value;
	}
}

if(!isset($mailer)) {
	die('Naughty naughty.');
}

// Constructing the message.
$message = "";
$length = count($mailer['field']);
for($i = 0; $i < $length; $i++) {
	$message .= $mailer['field'][$i] . ' : ' . $mailer['value'][$i] . '\n';
}

if(mail($to, $subject, $message)) {
	echo 'Mail successfully sent. Thank you.';
} else {
	echo 'Unable to send mail. Please try again';
}