export interface Role {
    id: number;
    libelle: string; 
}

export interface User {
    id: string;
    firstname: string;
    lastname: string;
    email: string;
    phone: string;
    address: string;
    city: string;
    postal_code: string;
    country: string;
    role_id: number;
    role: Role;
}

export interface TypePlat {
    id: number;
    libelle: string; 
}

export interface Allergene {
    id: number;
    libelle: string;
}

export interface Plat {
    id: number;
    titre_plat: string; 
    image?: string;
    type_plat_id: number;
    type_plat: TypePlat;
    allergenes: Allergene[];
}

export interface Regime {
    id: number;
    libelle: string; 
}

export interface Theme {
    id: number;
    libelle: string; 
}

export interface Menu {
    id: number;
    titre: string;
    description: string;
    prix_par_personne: number;
    stock: number;
    nb_personne_min: number;
    condition?: string;
    regime: Regime;
    theme: Theme;
    plats: Plat[]; 
}

export interface Avis {
    id: number;
    description: string;
    note: number;
    publie: boolean;
    user_id: number;
    created_at: string; 
    user: User; 
}

export interface StatutCommande {
    id: number;
    libelle: string;
}

export interface CommandeMenu {
    menu_id: number;
    commande_id: number;
    nb_personnes: number;       
    prix_unitaire_fixe: number; 
    prix_total_ligne: number;   
    created_at: string;
}

export type MenuDansCommande = Menu & {
    pivot: CommandeMenu;
};

export interface Commande {
    id: number;
    numero_commande: string; 
    statut_commande_id: number;
    user_id: number;
    date_commande: string;
    date_prestation: string;
    date_livraison: string;
    heure_livraison: string; 
    adresse_livraison: string;
    ville_livraison: string;
    code_postal_livraison: string;
    prix_livraison: number;
    total_commande: number;
    pret_materiel: boolean;
    retour_materiel: boolean;

    menus: MenuDansCommande[]; 
    statut_commande: StatutCommande; 
    user: User;
}

export interface Horaire {
    id: number;
    jour: string; 
    heure_ouverture: string; 
    heure_fermeture: string; 
}