DROP TABLE IF EXISTS roles;
CREATE TABLE roles (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS statut_commandes;
CREATE TABLE statut_commandes (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS type_plats;
CREATE TABLE type_plats (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS themes;
CREATE TABLE themes (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS allergenes;
CREATE TABLE allergenes (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS regimes;
CREATE TABLE regimes (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    libelle VARCHAR(50) NOT NULL UNIQUE
);

DROP TABLE IF EXISTS horaires;
CREATE TABLE horaires (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    jour VARCHAR(50) NOT NULL,
    heure_ouverture TIME NOT NULL,
    heure_fermeture TIME NOT NULL
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    lastname VARCHAR(50) NOT NULL,
    firstname VARCHAR(50) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address VARCHAR(50) NOT NULL,
    city VARCHAR(50) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    country VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(50) NOT NULL,
    role_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);

DROP TABLE IF EXISTS plats;
CREATE TABLE plats (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    type_plat_id INTEGER UNSIGNED NOT NULL,
    titre_plat VARCHAR(50) NOT NULL,
    image VARCHAR(50) DEFAULT NULL,
    CONSTRAINT plats_type_plat_id_foreign FOREIGN KEY (type_plat_id) REFERENCES type_plats (id)
);

DROP TABLE IF EXISTS allergene_plats;
CREATE TABLE allergene_plats (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    plat_id INTEGER UNSIGNED NOT NULL,
    allergene_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT allergene_plats_plat_id_foreign FOREIGN KEY (plat_id) REFERENCES plats (id),
    CONSTRAINT allergene_plats_allergene_id_foreign FOREIGN KEY (allergene_id) REFERENCES allergenes (id)
);

DROP TABLE IF EXISTS menus;
CREATE TABLE menus (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    regime_id INTEGER UNSIGNED NOT NULL,
    theme_id INTEGER UNSIGNED NOT NULL,
    titre VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    nb_personne_min INTEGER UNSIGNED NOT NULL,
    prix_par_personne DECIMAL(8, 2) UNSIGNED NOT NULL,
    condition TEXT DEFAULT NULL,
    stock INTEGER UNSIGNED NOT NULL,
    CONSTRAINT menus_regime_id_foreign FOREIGN KEY (regime_id) REFERENCES regimes (id),
    CONSTRAINT menus_theme_id_foreign FOREIGN KEY (theme_id) REFERENCES themes (id)
);

DROP TABLE IF EXISTS plat_menus;
CREATE TABLE plat_menus (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    plat_id INTEGER UNSIGNED NOT NULL,
    menu_id INTEGER UNSIGNED NOT NULL,
    CONSTRAINT plat_menus_plat_id_foreign FOREIGN KEY (plat_id) REFERENCES plats (id),
    CONSTRAINT plat_menus_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES menus (id)
);

DROP TABLE IF EXISTS commandes;
CREATE TABLE commandes (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER UNSIGNED NOT NULL,
    statut_commande_id INTEGER UNSIGNED NOT NULL,
    numero_commande VARCHAR(255) NOT NULL UNIQUE,
    date_commande DATE NOT NULL,
    date_prestation DATE NOT NULL,
    date_livraison DATE NOT NULL,
    heure_livraison TIME NOT NULL,
    adresse_livraison VARCHAR(100) NOT NULL,
    ville_livraison VARCHAR(50) NOT NULL,
    code_postal_livraison VARCHAR(10) NOT NULL,
    prix_livraison DECIMAL(8, 2) UNSIGNED NOT NULL,
    total_commande DECIMAL(10, 2) UNSIGNED NOT NULL,
    pret_materiel TINYINT(1) NOT NULL DEFAULT 0,
    retour_materiel TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT commandes_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    CONSTRAINT commandes_statut_commande_id_foreign FOREIGN KEY (statut_commande_id) REFERENCES statut_commandes (id)
);

DROP TABLE IF EXISTS commande_menus;
CREATE TABLE commande_menus (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    commande_id INTEGER UNSIGNED NOT NULL,
    menu_id INTEGER UNSIGNED NOT NULL,
    nb_personnes INTEGER UNSIGNED NOT NULL,
    prix_unitaire_fixe DECIMAL(8, 2) NOT NULL,
    prix_total_ligne DECIMAL(8, 2) NOT NULL,
    CONSTRAINT commande_menus_commande_id_foreign FOREIGN KEY (commande_id) REFERENCES commandes (id) ON DELETE CASCADE,
    CONSTRAINT commande_menus_menu_id_foreign FOREIGN KEY (menu_id) REFERENCES menus (id) ON DELETE CASCADE
);

DROP TABLE IF EXISTS avis;
CREATE TABLE avis (
    id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_id INTEGER UNSIGNED NOT NULL,
    description TEXT NOT NULL,
    note INTEGER NOT NULL,
    publie TINYINT(1) NOT NULL DEFAULT 0,
    CONSTRAINT avis_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id)
);

INSERT INTO users 
(firstname, lastname, phone, address, city, postal_code, country,role_id, email, password) 
VALUES 
('Dupond', 'José', '0601020304', '1 rue du Code', 'Toulouse', '71000', 'France', 1, 'dupond.jose@example.com', 
'$2y$12$UnFauxHashTresLongGenereParBcrypt1234567890');

INSERT INTO users 
(firstname, lastname, phone, address, city, postal_code, country,role_id, email, password) 
VALUES 
('Dupont', 'Josette', '0689070605', '20 rue du Bug', 'Toulouse', '71000', 'France', 2, 'dupont.josette@example.com', 
'$2y$12$UnAutreHashGenereParBcryptPourJosette987654321');

-- 3. Création de Tintin Milou (Le Client, role_id = 3)
INSERT INTO users 
(firstname, lastname, phone, address, city, postal_code, country, role_id, email, password) 
VALUES 
('Milou', 'Tintin', '0678050403', '15 rue du Mystere', 'Toulouse', '71000', 'France', 3, 'milou.tintin@example.com', 
'$2y$12$EncoreUnHashBcryptPourLeMotDePasseDeMilou111');


INSERT INTO allergenes (libelle) VALUES 
('Gluten'),
('Crustacés'),
('Œufs'),
('Poissons'),
('Arachides'),
('Soja'),
('Lait'),
('Fruits à coque'),
('Céleri'),
('Moutarde'),
('Graines de sésame'),
('Anhydride sulfureux et sulfites'),
('Lupin'),
('Mollusques');

INSERT INTO regimes (libelle) VALUES 
('Végétarien'),
('Vegan'),
('Sans gluten'),
('Paleo'),
('Keto'),
('Flexitarien'),
('Sans lactose'),
('Diabétique'),
('Hypocalorique'),
('Sans sucre ajouté');

INSERT INTO themes (libelle) VALUES 
('Italien'),
('Mexicain'),
('Asiatique'),
('Français'),
('Indien'),
('Méditerranéen'),
('Végétarien'),
('Vegan'),
('Sans gluten'),
('Paleo');

INSERT INTO type_plats (libelle) VALUES 
('Entrée'),
('Plat principal'),
('Dessert');

INSERT INTO roles (libelle) VALUES 
('Admin'),
('employe'),
('utilisateur');

INSERT INTO statut_commandes (libelle) VALUES 
('acceptée'),
('en préparation'),
('En cours de livraison'),
('Livrée'),
('en attente du retour de matériel'),
('terminée'),
('Annulée');