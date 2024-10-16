<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_complet = $_POST['full-name'];
    $methode_paiement = $_POST['payment-method'];
    $email_to = "ton.email@exemple.com"; // Remplace par ton e-mail
    $screenshot = $_FILES['payment-screenshot'];

    // En-têtes de l'e-mail
    $subject = "Nouvelle inscription - MoMo Agroalimentaire";
    $message = "Nom complet : $nom_complet\n";
    $message .= "Mode de paiement : $methode_paiement\n";

    // En-têtes supplémentaires pour l'e-mail
    $headers = "From: no-reply@agroalimentaire.com";

    // Traitement de la capture d'écran
    if(isset($screenshot)) {
        $file_tmp = $screenshot['tmp_name'];
        $file_name = $screenshot['name'];
        $file_size = $screenshot['size'];
        $file_type = $screenshot['type'];

        $handle = fopen($file_tmp, "r");
        $content = fread($handle, $file_size);
        fclose($handle);

        // Préparer le fichier pour l'envoi
        $encoded_content = chunk_split(base64_encode($content));
        $boundary = md5("random");

        $headers .= "\r\nMIME-Version: 1.0\r\n"
                 . "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

        // Message de l'e-mail
        $message = "--{$boundary}\r\n"
                 . "Content-Type: text/plain; charset=\"UTF-8\"\r\n"
                 . "Content-Transfer-Encoding: 7bit\r\n\r\n"
                 . $message . "\r\n";

        // Joindre la capture d'écran
        $message .= "--{$boundary}\r\n"
                  . "Content-Type: {$file_type}; name=\"{$file_name}\"\r\n"
                  . "Content-Disposition: attachment; filename=\"{$file_name}\"\r\n"
                  . "Content-Transfer-Encoding: base64\r\n\r\n"
                  . $encoded_content . "\r\n"
                  . "--{$boundary}--\r\n";
    }

    // Envoyer l'e-mail
    mail($email_to, $subject, $message, $headers);

    // Rediriger vers WhatsApp après soumission
    header("Location: https://wa.me/message/DJNWKADUL744A1");
    exit();
}
?>
