<?php
function envoyerAlerte($sujet, $messageHtml) {
    $to = 'admin@example.com'; // Change avec ton vrai e-mail
    $headers = "From: monitoring@tonsite.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    return mail($to, $sujet, $messageHtml, $headers);
}
?>
