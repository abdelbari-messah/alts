<?php
/*
 *  CONFIGURE EVERYTHING HERE
 */

// An email address that will receive a copy of the message.
$sendTo = 'frontenddeveloper2612@gmail.com';

// A sender address for outgoing mail.
$from = 'no-reply@example.com';

// Subject labels for the form.
$subjects = array(
    'partenariat_distribution' => 'Demande de partenariat / distribution',
    'devis_professionnel' => 'Demande de devis professionnel',
    'informations_produit_conformite' => 'Informations produit & conformité',
    'logistique_livraison' => 'Logistique & livraison',
    'reclamations_support' => 'Réclamations & support',
);

// Message that will be displayed when everything is OK :)
$okMessage = 'Contact form successfully submitted. Thank you, I will get back to you soon!';

// If something goes wrong, we will display this message.
$errorMessage = 'There was an error while submitting the form. Please try again later';

/*
 *  LET'S DO THE SENDING
 */

error_reporting(E_ALL & ~E_NOTICE);

function normalizeValue($value)
{
    return trim(str_replace(array("\r", "\n"), ' ', (string) $value));
}

try {
    if (count($_POST) === 0) {
        throw new \Exception('Form is empty');
    }

    $name = normalizeValue($_POST['name']);
    $email = normalizeValue($_POST['email']);
    $subjectKey = normalizeValue($_POST['subject']);
    $message = trim((string) $_POST['message']);

    if ($name === '' || $email === '' || $subjectKey === '' || $message === '') {
        throw new \Exception('Missing required fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new \Exception('Invalid email');
    }

    if (!isset($subjects[$subjectKey])) {
        throw new \Exception('Invalid subject');
    }

    $storageDir = dirname(__DIR__) . '/data';
    $storageFile = $storageDir . '/contact-submissions.csv';

    if (!is_dir($storageDir) && !mkdir($storageDir, 0755, true) && !is_dir($storageDir)) {
        throw new \Exception('Unable to create storage directory');
    }

    $row = array(
        date('Y-m-d H:i:s'),
        $_SERVER['REMOTE_ADDR'] ?? '',
        $name,
        $email,
        $subjects[$subjectKey],
        $message,
    );

    $handle = fopen($storageFile, 'ab');
    if ($handle === false) {
        throw new \Exception('Unable to open storage file');
    }

    if (flock($handle, LOCK_EX)) {
        fputcsv($handle, $row, ',', '"', '');
        fflush($handle);
        flock($handle, LOCK_UN);
    }

    fclose($handle);

    $emailText = "You have a new message from your contact form\n=============================\n";
    $emailText .= 'Name: ' . $name . "\n";
    $emailText .= 'Email: ' . $email . "\n";
    $emailText .= 'Subject: ' . $subjects[$subjectKey] . "\n";
    $emailText .= 'Message: ' . $message . "\n";

    $headers = array(
        'Content-Type: text/plain; charset="UTF-8";',
        'From: ' . $from,
        'Reply-To: ' . $email,
        'Return-Path: ' . $from,
    );

    @mail($sendTo, 'New message from contact form: ' . $subjects[$subjectKey], $emailText, implode("\n", $headers));

    $responseArray = array('type' => 'success', 'message' => $okMessage);
} catch (\Exception $e) {
    $responseArray = array('type' => 'danger', 'message' => $errorMessage);
}

if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    header('Content-Type: application/json');
    echo json_encode($responseArray);
} else {
    echo $responseArray['message'];
}
