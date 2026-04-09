<?php
require_once 'includes/auth_check.php';
include 'includes/header.php';
include 'includes/navbar.php';
?>

<section class="attacks">
    <div class="container">
        <h2>🚨 Tentatives d'intrusion</h2>

        <table>
            <thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Adresse IP</th>
                    <th>Type</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-06-10 08:43</td>
                    <td>192.168.1.15</td>
                    <td>Brute Force</td>
                    <td>Bloqué</td>
                </tr>
                <tr>
                    <td>2025-06-09 21:20</td>
                    <td>45.33.32.156</td>
                    <td>Injection SQL</td>
                    <td>Alerté</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
