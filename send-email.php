<?php
// Security headers
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

// Configuration
$email_to = 'nahigroupmaroc@gmail.com';
$email_subject = 'Nouvelle demande de devis – Nahi Group Maroc';

// Vérifier la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Méthode non autorisée. Utilisez POST']));
}

// Récupérer et nettoyer les données
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$service = isset($_POST['service']) ? trim($_POST['service']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Valider les champs requis
if (empty($name) || empty($phone) || empty($service)) {
    http_response_code(400);
    die(json_encode(['error' => 'Champs requis manquants: nom, téléphone, service obligatoires']));
}

// Préparer le contenu de l'email
$email_body = "Nouvelle demande de devis\n";
$email_body .= "================================\n\n";
$email_body .= "Nom: " . $name . "\n";
$email_body .= "Téléphone: " . $phone . "\n";
$email_body .= "Email: " . $email . "\n";
$email_body .= "Service: " . $service . "\n";
$email_body .= "\nMessage:\n" . $message . "\n";
$email_body .= "\n================================\n";
$email_body .= "Envoyé depuis: " . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'nahigroupmaroc.com');

// Headers email
$mail_headers = "From: " . ($email ? $email : 'noreply@nahigroupmaroc.com') . "\r\n";
$mail_headers .= "Reply-To: " . ($email ? $email : 'noreply@nahigroupmaroc.com') . "\r\n";
$mail_headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envoyer l'email
try {
    $mail_sent = @mail($email_to, $email_subject, $email_body, $mail_headers);
    
    if ($mail_sent) {
        http_response_code(200);
        echo json_encode(['success' => true, 'message' => 'Email envoyé avec succès']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Impossible d\'envoyer l\'email']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Erreur serveur: ' . $e->getMessage()]);
}
    echo json_encode(['error' => 'Erreur lors de l\'envoi']);
}
