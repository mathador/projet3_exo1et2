# API REST - Notes & Tags

Cette application Laravel a été transformée en Web API REST complète avec authentification par tokens (Sanctum) et documentation Swagger.

## Installation

### 1. Installer les dépendances

```bash
composer install
```

### 2. Configurer l'environnement

Copiez le fichier `.env.example` vers `.env` et configurez votre base de données :

```env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 3. Exécuter les migrations

```bash
php artisan migrate
```

### 4. Générer la documentation Swagger

```bash
php artisan l5-swagger:generate
```

## Endpoints API

### Base URL
```
http://localhost:8000/api
```

### Endpoints publics

#### Health Check
```
GET /api/health
```
Vérifie la connexion à la base de données.

**Réponse 200:**
```json
{
  "status": "ok",
  "database": "connected",
  "timestamp": "2024-01-01T00:00:00.000000Z"
}
```

#### Inscription
```
POST /api/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Réponse 201:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

#### Connexion
```
POST /api/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}
```

**Réponse 200:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
}
```

### Endpoints protégés (nécessitent un token)

Tous les endpoints suivants nécessitent un header d'authentification :
```
Authorization: Bearer {token}
```

#### Déconnexion
```
POST /api/auth/logout
Authorization: Bearer {token}
```

#### Utilisateur actuel
```
GET /api/auth/user
Authorization: Bearer {token}
```

#### Notes

- **Liste des notes** : `GET /api/notes`
- **Créer une note** : `POST /api/notes`
  ```json
  {
    "text": "Ma nouvelle note",
    "tag_id": 1
  }
  ```
- **Afficher une note** : `GET /api/notes/{id}`
- **Modifier une note** : `PUT /api/notes/{id}`
- **Supprimer une note** : `DELETE /api/notes/{id}`

#### Tags

- **Liste des tags** : `GET /api/tags`
- **Créer un tag** : `POST /api/tags`
  ```json
  {
    "name": "Important"
  }
  ```
- **Afficher un tag** : `GET /api/tags/{id}`
- **Modifier un tag** : `PUT /api/tags/{id}`
- **Supprimer un tag** : `DELETE /api/tags/{id}`

## Documentation Swagger

Une fois l'application lancée, accédez à la documentation Swagger interactive :

```
http://localhost:8000/api/documentation
```

Pour régénérer la documentation après modification des annotations :

```bash
php artisan l5-swagger:generate
```

## Architecture

### Structure des dossiers

```
app/
├── Http/
│   └── Controllers/
│       └── Api/
│           ├── Auth/
│           │   └── AuthController.php
│           ├── HealthController.php
│           ├── NotesController.php
│           ├── SwaggerController.php
│           └── TagsController.php
├── Services/
│   ├── Notes/
│   │   └── NoteService.php
│   └── Tags/
│       └── TagService.php
└── Models/
    ├── Note.php
    ├── Tag.php
    └── User.php

routes/
└── api.php
```

### Authentification

L'API utilise **Laravel Sanctum** pour l'authentification par tokens. Chaque utilisateur peut générer plusieurs tokens d'accès.

### Services

La logique métier est encapsulée dans des services :
- `NoteService` : Gestion des notes (CRUD, filtrage par utilisateur)
- `TagService` : Gestion des tags (CRUD, validation d'unicité)

## Exemple d'utilisation avec cURL

### 1. S'inscrire
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Se connecter
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password123"
  }'
```

### 3. Créer une note
```bash
curl -X POST http://localhost:8000/api/notes \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer {token}" \
  -d '{
    "text": "Ma première note",
    "tag_id": 1
  }'
```

### 4. Lister les notes
```bash
curl -X GET http://localhost:8000/api/notes \
  -H "Authorization: Bearer {token}"
```

## Tests

Exécuter les tests :

```bash
php artisan test
```

## Notes importantes

- Les tokens Sanctum n'expirent pas par défaut. Vous pouvez configurer l'expiration dans `config/sanctum.php`.
- La base de données par défaut est SQLite (`database/database.sqlite`).
- Toutes les routes API sont préfixées par `/api`.
- La documentation Swagger est générée automatiquement à partir des annotations dans les contrôleurs.

