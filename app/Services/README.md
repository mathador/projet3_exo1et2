# Services Layer

Cette couche contient la logique métier de l'application, séparée des composants de présentation (Livewire) et des modèles Eloquent.

## Structure

- `Notes/NoteService.php` : Gestion des notes (CRUD, filtrage par utilisateur)
- `Tags/TagService.php` : Gestion des tags (CRUD, validation d'unicité)

## Principes

- Les services encapsulent la logique métier
- Les services sont injectés via l'injection de dépendances Laravel
- Les services utilisent les modèles Eloquent pour la persistance
- Les services gèrent la sécurité (vérification de propriété, validation)

## Utilisation

Les services sont injectés automatiquement dans les composants Livewire via la méthode `boot()` :

```php
protected NoteService $noteService;

public function boot(NoteService $noteService)
{
    $this->noteService = $noteService;
}
```

