# Architecture du Projet - MVC + Services

## Structure des Dossiers

```
app/
├── Http/
│   └── Controllers/
│       ├── Auth/              # Contrôleurs d'authentification
│       │   └── VerifyEmailController.php
│       ├── Web/               # Contrôleurs pour les pages web
│       │   └── DashboardController.php
│       └── Controller.php     # Contrôleur de base
├── Livewire/                  # Composants Livewire (Présentation)
│   ├── Actions/
│   │   └── Logout.php
│   ├── Notes.php              # Utilise NoteService
│   └── TagForm.php            # Utilise TagService
├── Models/                    # Modèles Eloquent (Données)
│   ├── Note.php
│   ├── Tag.php
│   └── User.php
├── Services/                  # Couche de services (Logique métier)
│   ├── Notes/
│   │   └── NoteService.php
│   ├── Tags/
│   │   └── TagService.php
│   └── README.md
└── Providers/                 # Service Providers Laravel
    ├── AppServiceProvider.php
    └── VoltServiceProvider.php

routes/
├── web.php                    # Routes web principales
└── auth.php                   # Routes d'authentification

resources/views/
├── components/                # Composants Blade réutilisables
├── livewire/                 # Vues des composants Livewire
└── ...
```

## Séparation des Responsabilités

### 1. **Routes** (`routes/`)
- Définissent les points d'entrée de l'application
- Appliquent les middlewares (auth, verified, guest)
- Routent vers les composants Livewire ou les contrôleurs

### 2. **Contrôleurs** (`app/Http/Controllers/`)
- **Auth/** : Gestion de l'authentification (vérification email, etc.)
- **Web/** : Contrôleurs pour les pages web classiques (si nécessaire)
- Orchestrent les interactions entre routes et services/composants

### 3. **Composants Livewire** (`app/Livewire/`)
- **Responsabilité** : Présentation et interaction utilisateur
- **Ne contiennent plus** : Logique métier directe ou accès direct aux modèles
- **Utilisent** : Les services pour toute opération métier
- Exemples : `Notes.php`, `TagForm.php`

### 4. **Services** (`app/Services/`)
- **Responsabilité** : Logique métier pure
- **Encapsulent** : Les règles métier, validations complexes, sécurité
- **Utilisent** : Les modèles Eloquent pour la persistance
- **Sont injectés** : Via l'injection de dépendances Laravel

#### NoteService
- `getUserNotes()` : Récupère les notes de l'utilisateur authentifié
- `createNote()` : Crée une nouvelle note
- `deleteNote()` : Supprime une note (avec vérification de propriété)
- `userOwnsNote()` : Vérifie la propriété d'une note

#### TagService
- `getAllTags()` : Récupère tous les tags
- `createTag()` : Crée un nouveau tag (avec vérification d'unicité)
- `tagExists()` : Vérifie l'existence d'un tag
- `getTagById()` : Récupère un tag par son ID

### 5. **Modèles** (`app/Models/`)
- **Responsabilité** : Représentation des données et relations Eloquent
- **Contiennent** : Les relations, les attributs fillable, les casts
- **Ne contiennent pas** : La logique métier complexe

## Flux de Données

```
Requête HTTP
    ↓
Routes (web.php / auth.php)
    ↓
Middleware (auth, verified, etc.)
    ↓
Composant Livewire ou Contrôleur
    ↓
Service (logique métier)
    ↓
Modèle Eloquent
    ↓
Base de données
```

## Avantages de cette Architecture

1. **Séparation claire des responsabilités** : Chaque couche a un rôle précis
2. **Testabilité** : Les services peuvent être testés indépendamment
3. **Réutilisabilité** : Les services peuvent être utilisés par différents composants
4. **Maintenabilité** : La logique métier est centralisée dans les services
5. **Sécurité** : La vérification de propriété est gérée dans les services
6. **Évolutivité** : Facile d'ajouter de nouvelles fonctionnalités sans toucher aux composants

## Exemple d'Utilisation

### Avant (dans le composant Livewire)
```php
public function save()
{
    $this->validate();
    Note::create([
        'user_id' => Auth::id(),
        'tag_id' => $this->tag_id,
        'text' => $this->text,
    ]);
}
```

### Après (avec le service)
```php
public function boot(NoteService $noteService)
{
    $this->noteService = $noteService;
}

public function save()
{
    $this->validate();
    $this->noteService->createNote($this->text, $this->tag_id);
}
```

## Prochaines Étapes Possibles

1. Ajouter des **Repositories** si la logique de requêtes devient complexe
2. Créer des **DTOs** (Data Transfer Objects) pour les données complexes
3. Ajouter des **Events/Listeners** pour les actions importantes
4. Implémenter des **Policies** Laravel pour une autorisation plus fine
5. Créer des **Form Requests** pour la validation complexe

