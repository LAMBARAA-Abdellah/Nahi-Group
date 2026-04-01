<?php
// Configuration
$email_to = 'nahigroupmaroc@gmail.com';
$email_subject = 'Nouvelle demande de devis – Nahi Group Maroc';

// Vérifier la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Méthode non autorisée']);
    exit;
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
    echo json_encode(['error' => 'Champs requis manquants']);
    exit;
}

// Préparer le contenu de l'email
$email_body = "Nouvelle demande de devis\n";
$email_body .= "================================\n\n";
$email_body .= "Nom: " . htmlspecialchars($name) . "\n";
$email_body .= "Téléphone: " . htmlspecialchars($phone) . "\n";
$email_body .= "Email: " . htmlspecialchars($email) . "\n";
$email_body .= "Service: " . htmlspecialchars($service) . "\n";
$email_body .= "\nMessage:\n" . htmlspecialchars($message) . "\n";
$email_body .= "\n================================\n";
$email_body .= "Envoyé depuis le site: " . $_SERVER['HTTP_HOST'];

// Headers
$headers = "From: " . ($email ? htmlspecialchars($email) : 'noreply@nahigroupmaroc.com') . "\r\n";
$headers .= "Reply-To: " . ($email ? htmlspecialchars($email) : 'noreply@nahigroupmaroc.com') . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Envoyer l'email
$mail_sent = mail($email_to, $email_subject, $email_body, $headers);

if ($mail_sent) {
    http_response_code(200);
    echo json_encode(['success' => true, 'message' => 'Email envoyé avec succès']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur lors de l\'envoi']);
}
