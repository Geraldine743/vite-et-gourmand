# TraiteurApp - Vite & Gourmand

Application web sur-mesure pour "Vite & Gourmand", traiteur créateur d'instants gourmands à Bordeaux depuis 1999. Cette plateforme permet de présenter les menus (à venir), recueillir les avis clients et (à venir) gérer les commandes en ligne.

## Fonctionnalités

- **Design Mobile First :** Interface fluide et responsive adaptée à tous les écrans.
- **Charte graphique :** Utilisation des couleurs Terra Cotta, Bleu Nuit et Crème avec une typographie soignée (Montserrat & Open Sans).
- **Catalogue de menus :** Affichage dynamique des menus et plats proposés (à venir).
- **Preuve sociale :** Système d'avis clients paginé avec notes sur 5.
- **Informations dynamiques :** Horaires d'ouverture récupérés en temps réel depuis la base de données.
- **Authentification :** API sécurisée avec Laravel Sanctum.

## Stack Technique

### Frontend (SPA)

- **Framework :** Vue.js 3
- **Langage :** TypeScript
- **Tooling :** Vite
- **Routage :** Vue Router
- **Style :** SCSS

### Backend (API REST)

- **Framework :** Laravel (PHP)
- **Base de données :** MySQL
- **Authentification :** Laravel Sanctum

---

## Architecture SCSS & BEM

Le projet utilise une architecture SCSS moderne basée sur le système de modules (`@use`) et respecte strictement la convention de nommage **BEM (Block Element Modifier)** pour une maintenance aisée.

## Installation & Lancement (Guide pas-à-pas)

Si vous forkez ou clonez ce projet, voici les étapes exactes pour le lancer en local sur votre machine.

### 1. Prérequis

Assurez-vous d'avoir installé sur votre machine :

- **PHP** (>= 8.1) et **Composer**
- **Node.js** (>= 18) et **npm**
- Un serveur local avec **MySQL / MariaDB** (ex: XAMPP, WAMP, MAMP, ou Docker)

---

### 2. Cloner le projet

```bash
git clone https://github.com/Geraldine743/vite-et-gourmand.git
cd vite-et-gourmand

# Se placer dans le dossier backend
cd backend

# Installer les dépendances PHP
composer install

# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Lancer les migrations et les seeders
php artisan migrate --seed

# Créer le lien symbolique pour les images
php artisan storage:link

# Lancer le serveur de développement (Port 8000 par défaut)
php artisan serve

# Se placer dans le dossier frontend
cd frontend

# Installer les dépendances Node
npm install

# Copier le fichier d'environnement
cp .env.example .env

# Lancer le serveur de développement Vite (Port 5173 par défaut)
npm run dev

```
