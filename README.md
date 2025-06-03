# 🎫 Games Ticket – Backend Symfony

API REST sécurisée pour la gestion de la billetterie des Jeux Olympiques.  
Permet aux utilisateurs de s’inscrire, réserver des billets, payer et recevoir un QR Code. Les administrateurs peuvent gérer les offres et consulter les ventes.

Projet réalisé dans le cadre de la formation Studi – Développement d'application web sécurisée.

---

## 🧰 Stack technique

- ⚙️ Backend : Symfony 6 (PHP), Doctrine ORM
- 🛡️ Sécurité : JSON Web Token (JWT), rôles utilisateurs
- 🗄️ Base de données : MySQL
- ☁️ Hébergement : Heroku (ClearDB pour MySQL)

---

## 📦 Installation du backend

### 🔧 Prérequis

- PHP >= 8.1
- Composer
- Symfony CLI
- MySQL

### 🚀 Étapes

```bash
git clone https://github.com/ZAGOUE/games-ticket-backend.git
cd games-ticket-backend
composer install
```

### ⚙️ Configuration `.env`

```env
DATABASE_URL="mysql://<user>:<password>@127.0.0.1:3306/<dbname>"
```

> ⚠️ Remplacez les valeurs par vos informations locales.

### 🔐 Clés JWT

```bash
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

Ajoutez dans `.env` :

```env
JWT_PASSPHRASE=<votre-passphrase-JWT>
```

### 🛠️ Base de données

```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

### ▶️ Lancer le serveur

```bash
symfony server:start
```

---

## 🔒 Sécurité

- Authentification par token JWT
- Rôles : `ROLE_USER`, `ROLE_ADMIN`, `ROLE_CONTROLLER`
- Aucune création d’admin depuis le frontend
- Vérification serveur-side des rôles et accès
- Validations côté API : mots de passe, accès, etc.

---

## 📡 Endpoints principaux (extraits)

### 🔐 Authentification
| Méthode | URL                                      | Description                                 |
|---------|-------------------------------------------|---------------------------------------------|
| POST    | /api/users/register                       | Inscription utilisateur                      |
| POST    | /api/login                                | Authentification (retourne un JWT)           |
| GET     | /api/me                                   | Infos de l'utilisateur connecté              |

### 🎟️ Offres
| Méthode | URL                    | Description                  |
|---------|-------------------------|------------------------------|
| GET     | /api/offers             | Liste des offres             |
| GET     | /api/offers/{id}        | Détail d'une offre           |
| POST    | /api/offers/            | Créer une offre (admin)      |
| PUT     | /api/offers/{id}        | Modifier une offre (admin)   |
| DELETE  | /api/offers/{id}        | Supprimer une offre (admin)  |

### 📦 Commandes
| Méthode | URL                                | Description                         |
|---------|-------------------------------------|-------------------------------------|
| POST    | /api/orders                         | Créer une commande                   |
| GET     | /api/orders                         | Voir ses commandes                   |
| GET     | /api/orders/{id}                    | Détail d’une commande                |
| GET     | /api/orders/all                     | Toutes les commandes (admin)        |
| POST    | /api/orders/{id}/pay                | Payer une commande                   |
| GET     | /api/orders/{id}/qrcode             | Générer un QR Code                   |
| GET     | /api/orders/{id}/download           | Télécharger le billet en PDF         |
| GET     | /api/orders/verify-ticket/{key}     | Vérifier un billet (contrôleur)      |

### 👤 Utilisateurs
| Méthode | URL                                | Description                         |
|---------|-------------------------------------|-------------------------------------|
| GET     | /api/users/{id}                     | Voir un utilisateur par ID          |
| GET     | /api/users/email/{email}            | Voir un utilisateur par email       |
| POST    | /api/users                          | Créer un utilisateur (admin)        |
| PUT     | /api/users/{id}                     | Modifier un utilisateur             |
| DELETE  | /api/users/{id}                     | Supprimer un utilisateur            |

### 📊 Statistiques
| Méthode | URL                                | Description                         |
|---------|-------------------------------------|-------------------------------------|
| GET     | /api/admin/stats/offers             | Ventes par offre (admin)            |
| GET     | /api/admin_logs                     | Logs administrateur (admin)         |

---

## 🧪 Tests

Lancement des tests avec PHPUnit :


```bash
php bin/phpunit
```

Base de test définie dans `.env.test`.

---

## ☁️ Déploiement sur Heroku (backend)

### 1. Créer une app Heroku

```bash
heroku login
heroku create games-ticket-backend
```

### 2. Ajouter ClearDB pour MySQL

```bash
heroku addons:create cleardb:ignite
```

Puis récupérer l’URL de la base :

```bash
heroku config:get CLEARDB_DATABASE_URL
```

Et la transformer au format Symfony :

```env
DATABASE_URL="mysql://<user>:<password>@<host>/<dbname>?reconnect=true"
```

### 3. Ajouter les variables d’environnement

```bash
heroku config:set JWT_PASSPHRASE="votre-passphrase"
heroku config:set DATABASE_URL="mysql://user:pass@host/dbname"
```

### 4. Déployer l’application

```bash
git push heroku main
```

### 5. Lancer les migrations

```bash
heroku run php bin/console doctrine:migrations:migrate
```

---
## 🧭 Structure Git du projet

Ce projet utilise une structure de branches claire :

| Branche | Description                     |
|---------|----------------------------------|
| `main`  | Branche de production (stable)   |
| `test`  | Branche de test/intégration      |

Les évolutions sont testées dans `test` avant d’être fusionnées dans `main`.

## 🧑‍💻 Auteur

Projet réalisé dans le cadre de la formation **Bachelor Développement d'applications Web** – Projet Games Ticket – 2025  
© STUDI – Komi AGOUZE
