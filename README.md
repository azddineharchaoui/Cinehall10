Thought for 1 second### CinéHall - Système de Réservation de Cinéma

## Aperçu

CinéHall est une application web complète de gestion et de réservation de billets de cinéma. Elle permet aux utilisateurs de parcourir les films à l'affiche, de consulter les horaires des séances, de réserver des sièges et de payer leurs billets en ligne. Le système offre également une interface d'administration pour gérer les films, les salles, les séances et les réservations.

## Fonctionnalités

### Pour les utilisateurs

- **Parcourir les films** : Consulter les films actuellement à l'affiche avec leurs détails (synopsis, durée, genre, etc.)
- **Consulter les séances** : Voir les horaires des séances disponibles pour chaque film
- **Réserver des sièges** : Sélectionner et réserver des sièges pour une séance spécifique
- **Paiement en ligne** : Payer les billets réservés via différentes méthodes de paiement
- **Gestion des tickets** : Consulter et télécharger les tickets après paiement
- **Historique des réservations** : Consulter l'historique des réservations et des paiements


### Pour les administrateurs

- **Gestion des films** : Ajouter, modifier et supprimer des films
- **Gestion des salles** : Configurer les salles de cinéma avec différents types (Normal, VIP) et dispositions de sièges
- **Gestion des séances** : Planifier des séances pour les films dans différentes salles
- **Gestion des réservations** : Consulter et gérer les réservations des utilisateurs
- **Rapports et statistiques** : Générer des rapports sur les ventes, l'occupation des salles, etc.


## Architecture technique

### Backend

- **Framework** : Laravel (PHP)
- **Base de données** : PostgreSQL
- **Authentification** : JWT (JSON Web Tokens)
- **API** : RESTful API documentée avec Swagger/OpenAPI


### Structure du projet

- **Models** : User, Movie, Theater, Seat, Session, Reservation, Payment, Ticket
- **Controllers** : Gestion des requêtes API
- **Repositories** : Couche d'abstraction pour l'accès aux données
- **Middleware** : Authentification, autorisation et validation des requêtes
- **Tests** : Tests unitaires et d'intégration avec PHPUnit


## Installation

### Prérequis

- PHP 8.1 ou supérieur
- Composer
- PostgreSQL
- Node.js et NPM (pour le frontend)


### Étapes d'installation

1. Cloner le dépôt


```shellscript
git clone https://github.com/votre-utilisateur/cinehall.git
cd cinehall
```

2. Installer les dépendances PHP


```shellscript
composer install
```

3. Configurer l'environnement


```shellscript
cp .env.example .env
php artisan key:generate
```

4. Configurer la base de données dans le fichier .env


```plaintext
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=cinehall
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe
```

5. Exécuter les migrations et les seeders


```shellscript
php artisan migrate
php artisan db:seed
```

6. Configurer JWT


```shellscript
php artisan jwt:secret
```

7. Démarrer le serveur de développement


```shellscript
php artisan serve
```

## API Endpoints

L'API de CinéHall est documentée avec Swagger/OpenAPI. Vous pouvez accéder à la documentation interactive à l'adresse `/api/documentation` après avoir démarré le serveur.

### Principaux endpoints

#### Authentification

- `POST /api/auth/register` : Inscription d'un nouvel utilisateur
- `POST /api/auth/login` : Connexion d'un utilisateur
- `POST /api/auth/logout` : Déconnexion d'un utilisateur
- `GET /api/auth/profile` : Récupération du profil de l'utilisateur connecté


#### Films

- `GET /api/movies` : Liste de tous les films
- `GET /api/movies/{id}` : Détails d'un film spécifique
- `POST /api/movies` : Ajout d'un nouveau film (admin)
- `PUT /api/movies/{id}` : Mise à jour d'un film (admin)
- `DELETE /api/movies/{id}` : Suppression d'un film (admin)


#### Salles

- `GET /api/theaters` : Liste de toutes les salles
- `GET /api/theaters/{id}` : Détails d'une salle spécifique
- `POST /api/theaters` : Ajout d'une nouvelle salle (admin)
- `PUT /api/theaters/{id}` : Mise à jour d'une salle (admin)
- `DELETE /api/theaters/{id}` : Suppression d'une salle (admin)


#### Séances

- `GET /api/sessions` : Liste de toutes les séances
- `GET /api/sessions/{id}` : Détails d'une séance spécifique
- `GET /api/sessions/movie/{id}` : Séances pour un film spécifique
- `GET /api/sessions/{id}/available-seats` : Sièges disponibles pour une séance
- `POST /api/sessions` : Ajout d'une nouvelle séance (admin)
- `PUT /api/sessions/{id}` : Mise à jour d'une séance (admin)
- `DELETE /api/sessions/{id}` : Suppression d'une séance (admin)


#### Réservations

- `GET /api/reservations` : Liste des réservations de l'utilisateur
- `GET /api/reservations/{id}` : Détails d'une réservation spécifique
- `POST /api/reservations` : Création d'une nouvelle réservation
- `POST /api/reservations/{id}/cancel` : Annulation d'une réservation


#### Paiements

- `POST /api/payments` : Traitement d'un paiement
- `GET /api/payments` : Liste des paiements de l'utilisateur
- `GET /api/payments/reservation/{id}` : Paiement pour une réservation spécifique


#### Tickets

- `GET /api/tickets` : Liste des tickets de l'utilisateur
- `GET /api/tickets/{id}` : Détails d'un ticket spécifique
- `GET /api/tickets/reservation/{id}` : Tickets pour une réservation spécifique


## Fonctionnalités spéciales

### Réservation de sièges VIP

Le système gère intelligemment les sièges VIP, notamment pour les couples. Dans les salles VIP, les sièges sont configurés par paires et ne peuvent être réservés que par deux, garantissant ainsi que les couples puissent toujours s'asseoir ensemble.

### Expiration des réservations

Les réservations non payées expirent automatiquement après un délai défini (généralement 15 minutes), libérant ainsi les sièges pour d'autres utilisateurs.

### Génération de QR codes

Après le paiement, des tickets avec QR codes sont générés pour faciliter l'accès à la salle de cinéma.

## Tests

Le projet comprend une suite complète de tests unitaires et d'intégration pour garantir le bon fonctionnement de toutes les fonctionnalités.

Pour exécuter les tests :

```shellscript
php artisan test
```

Pour exécuter un test spécifique :

```shellscript
php artisan test --filter=AuthTest
```

## Structure des tests

Les tests sont organisés par entité et fonctionnalité :

- **AuthTest** : Tests d'authentification (inscription, connexion, déconnexion, profil)
- **MovieTest** : Tests de gestion des films
- **TheaterTest** : Tests de gestion des salles
- **SessionTest** : Tests de gestion des séances
- **ReservationTest** : Tests de gestion des réservations
- **PaymentTest** : Tests de gestion des paiements
- **TicketTest** : Tests de gestion des tickets
- **ReservationProcessTest** : Tests du processus complet de réservation


Chaque test vérifie les fonctionnalités CRUD (Create, Read, Update, Delete) et les règles métier associées à chaque entité.

## Modèle de données

### Utilisateurs (Users)

- id
- name
- email
- password
- role (user, admin)
- created_at
- updated_at


### Films (Movies)

- id
- title
- description
- duration
- genre
- release_date
- director
- image
- created_at
- updated_at


### Salles (Theaters)

- id
- name
- type (Normal, VIP)
- rows
- seats_per_row
- created_at
- updated_at


### Sièges (Seats)

- id
- theater_id
- row
- number
- type (regular, vip, couple)
- created_at
- updated_at


### Séances (Sessions)

- id
- movie_id
- theater_id
- start_time
- language
- type (Normal, 3D, IMAX)
- price
- created_at
- updated_at


### Réservations (Reservations)

- id
- user_id
- session_id
- status (pending, paid, cancelled, expired)
- expires_at
- total_price
- created_at
- updated_at


### Paiements (Payments)

- id
- user_id
- reservation_id
- amount
- payment_method
- transaction_id
- status
- created_at
- updated_at


### Tickets (Tickets)

- id
- user_id
- reservation_id
- seat_id
- qr_code
- created_at
- updated_at


## Contribution

Les contributions au projet sont les bienvenues. Veuillez suivre ces étapes pour contribuer :

1. Forker le dépôt
2. Créer une branche pour votre fonctionnalité (`git checkout -b feature/amazing-feature`)
3. Commiter vos changements (`git commit -m 'Add some amazing feature'`)
4. Pousser vers la branche (`git push origin feature/amazing-feature`)
5. Ouvrir une Pull Request


## Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.
