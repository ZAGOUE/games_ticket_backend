# 🎫 Games Ticket – Réservation de billets pour les Jeux Olympiques

Plateforme web complète pour réserver des e-billets sécurisés pour les Jeux Olympiques.  
Les utilisateurs peuvent consulter les offres, s’inscrire, acheter un billet et télécharger un QR Code à usage unique.

Projet réalisé dans le cadre de la formation Studi – Développement d'application web sécurisée.

---

## 🧰 Stack technique

- ⚙️ Backend : Symfony 6 (PHP), JWT, Doctrine, MySQL
- 🌐 Frontend : React.js, Axios, React Router
- ☁️ Déploiement : Heroku (backend), Netlify (frontend)
- 🔐 Authentification : JSON Web Token (JWT), rôles utilisateurs

---

## 📦 Installation

### Backend
```bash
cd games-ticket-backend
composer install
# Configurer le fichier .env pour la connexion MySQL
php bin/console doctrine:migrations:migrate
symfony server:start
```

### Frontend
```bash
cd games-ticket-frontend
npm install
# Créer un fichier .env avec l'URL de l’API backend
npm start
```

---

## 🚀 Fonctionnalités

- 🔍 Visualiser les offres disponibles
- 🛒 Ajouter au panier
- 🧑 Créer un compte utilisateur sécurisé
- ✅ Valider et payer un billet (simulation)
- 🎟️ Générer un QR Code sécurisé (e-billet)
- 🔎 Scanner les billets via un contrôleur
- 📊 Espace admin pour gérer les offres et visualiser les ventes

---

## 🔒 Sécurité

- Authentification JWT (stockée côté client)
- Gestion des rôles : ROLE_USER, ROLE_ADMIN, ROLE_CONTROLLER
- Validation des e-billets par double clef
- Aucune création d’admin via le frontend

---

## 📝 Auteur

Projet réalisé par [Ton Prénom NOM] dans le cadre de la formation Studi.
