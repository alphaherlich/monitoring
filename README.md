# 📊 Monitoring System – Gestion Santé & Supervision IT

## Description
Application web de monitoring combinant deux modules principaux :
- **Gestion hospitalière** : suivi des patients, admissions et revenus en temps réel
- **Supervision IT** : surveillance des équipements, applications, alertes et reporting

Le système offre un tableau de bord interactif permettant une vision globale de la santé d’un établissement et de son infrastructure technique.

---

## 🚀 Fonctionnalités

### 🏥 Module Santé (Hospitalier)
- Gestion et suivi des patients
- Suivi des admissions et sorties
- Analyse des revenus journaliers, hebdomadaires et mensuels
- Graphiques dynamiques des performances financières
- Export des données en PDF pour reporting

### 🖥️ Module Supervision (IT Monitoring)
- Supervision en temps réel des équipements et applications
- Détection d’état des serveurs et services
- Historique des performances et incidents
- Planification et suivi des tests automatiques
- Alertes et notifications (email en cas de problème)

---

## 📊 Tableau de bord
- Interface moderne et interactive
- Cartes statistiques (patients, revenus, état système)
- Graphiques dynamiques avec Chart.js
- Tables filtrables et recherchables
- Design responsive (PC, tablette)

---

## 🔐 Gestion des rôles
- **Administrateur** : accès complet au système
- **Médecin** : accès aux données patients
- **Secrétaire** : gestion des admissions et rendez-vous
- **Utilisateur standard** : accès limité selon permissions

---

## 🛠️ Technologies utilisées
- PHP (backend)
- MySQL (base de données)
- HTML / CSS (design intégré)
- JavaScript
- Chart.js (visualisation des données)

---

## 📁 Structure du projet

/includes → Fichiers réutilisables (auth, DB, navbar)
/user → Gestion des utilisateurs (CRUD)
/config_hosts.php → Liste des équipements supervisés
/dashboard.php → Tableau de bord principal
/monitor.php → Supervision système
/apps_status.php → État des applications
/send_report.php → Envoi de rapports email
/css → Styles intégrés
/script → Scripts JS et graphiques


---

## ⚙️ Installation locale

1. Installer XAMPP  
2. Copier le projet dans `htdocs`  
3. Importer la base de données MySQL via phpMyAdmin  
4. Configurer `includes/db.php`  
5. Lancer :  


 http://localhost/monitoring/dashboard.php

Accès en ligne via le site InfinityFree: herlich.free.nf




---

## ☁️ Déploiement
- Compatible avec InfinityFree
- Fonctionne avec MySQL distant
- Support des fonctionnalités PHP standards

---

## 📈 Objectif du projet
Ce projet a été conçu pour combiner :
- la **gestion des données médicales**
- la **supervision d’infrastructure informatique**

dans une seule plateforme centralisée de monitoring.

---

## 👨‍💻 Auteur
Herlich Ndoumbou

---

## 📌 Note
Projet personnel / éducatif de supervision et monitoring.
