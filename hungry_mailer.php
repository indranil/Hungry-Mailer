<?php

/**
 * Hungry Mail
 * 
 * @author Indranil Dasgupta
 * @version 0.7
 * @copyright Indranil Dasgupta, 2010
 * @package default
 */

//  Optional configuration.
//  Either set these here, or set them on the form..
//  
//  If they're set in the form, they will take precendence over these values,
//  so you can have multiple forms with the same mailer ;)
//  To set them within the form, use the following elements :
//  --- <input type="hidden" name="to" value="email@address.com">
//  --- <input type="hidden" name="subject" value="Subject goes here">
//  --- <input type="hidden" name="redirect" value="http://URL_TO_REDIRECT_ONCE SUCCESSFUL">
//  And that's that.

$to = ''; // The email address of the receiver, i.e. : you.
$subject = ''; // The subject message.
$redirect = ''; // The page where you will be sent after the form successfully submits.

// End configuration.

// ini_set('display_errors', '1');
// error_reporting(E_ALL);

// Function to make input names human readable
// Eg: first_name -> First name
function human_readable($value)
{
    // Lowercase entire word, Uppercase first letter, replace _ or - with ' '
    $out = strtolower($value);
    $out = ucwords($out);
    $out = preg_replace('/_|-/',' ', $out);
    return $out;
}
    
// First we set the receiver
if(isset($_POST['to']) && $_POST['to'] != '') {
    if (!get_magic_quotes_gpc()) {
        $to = addslashes($_POST['to']);
    } else {
        $to = $_POST['to'];
    }
}
// then, the subject
if(isset($_POST['subject']) && $_POST['subject'] != '') {
    if (!get_magic_quotes_gpc()) {
        $subject = addslashes($_POST['subject']);
    } else {
        $subject = $_POST['subject'];
    }
}
// and finally, the redirect
if(isset($_POST['redirect']) && $_POST['redirect'] != '') {
    if (!get_magic_quotes_gpc()) {
        $redirect = addslashes($_POST['redirect']);
    } else {
        $redirect = $_POST['redirect'];
    }
}

// Stuff we don't want
$unchecked = array('to','subject','redirect','submit');

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
    $message .= human_readable($mailer['field'][$i]) . ' : ' . $mailer['value'][$i] . "\n";
}

// Add the referrer to the email
$ref = $_SERVER['HTTP_REFERER'];
$message = "A new message was submitted at {$ref} :\n\n" . $message;

if(mail($to, $subject, $message)) {
    if($redirect != '') {
        header('Location: ' . $redirect);
    } else {
        echo 'Mail successfully sent. Thank you.';
    }
} else {
    echo 'Unable to send mail. Please try again';
}
