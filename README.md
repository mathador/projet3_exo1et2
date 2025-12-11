Si nginx est rouge dans herd (car port 80 utilisé par "system" dans windows, c'est IIS):
```bash
sc stop W3SVC
sc config W3SVC start= disabled
```

Ensuite si php artisan ne trouve pas de port,
```bash
where php
```
permet de savoir si le path tombe bien sur le php de Herd et pas de MAMP par exemple

Puis il faut générer une key:
```bash
php artisan key:generate
```


# Transformez l'architecture d'une application existante

# Plot

Renote is an application that allows user to take and store notes.
In renote, a user can:
- create notes
- visualize notes
- define relationship between the notes
- define tags
- and associate a tag to a note.

## Install

1. Install Laravel's Herd:
https://laravel.com/docs/12.x/installation#installation-using-herd

This will install Php, Composer and Laravel.

2. Install node v22

Install node version manager (MVN).
On Windows you can use this distribution:
https://github.com/coreybutler/nvm-windows#readme


3. Clone this project

4. il faut peut-être
```bash
composer install
```

et créer un .env

et migrate les data

```bash
php artisan migrate
```
Pour nettoyer:

```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
composer dump-autoload
```

4. Run 

```bash
npm i
```
and 
```bash
npm run dev
```

5. Start Herd

6. Access `http://monolithic-app.test` from your browser

You are setup!

Pour information voici un schéma MVC simplifié:

```mermaid
graph TD
    subgraph "View (Vues)"
        V1["dashboard.blade.php"]
        V2["notes.blade.php"]
        V3["tag-form.blade.php"]
    end

    subgraph "Controller (Contrôleurs)"
        C1["DashboardController.php"]
        C2["Api/NotesController.php"]
        C3["Api/TagsController.php"]
        LW1["Livewire/Notes.php"]
        LW2["Livewire/TagForm.php"]
        S1["Notes/NoteService.php"]
        S2["Tags/TagService.php"]
        AC["Api/*ApiClient.php"]
    end


    subgraph "Model (Modèles)"
        M1["Note.php"]
        M2["Tag.php"]
        M3["User.php"]
    end

    User([Utilisateur]) --> C1
    User --> LW1
    User --> LW2

    C1 --> V1
    
    LW1 --> V2
    LW1 -- "Appelle" --> AC
    LW2 --> V3
    LW2 -- "Appelle" --> AC

    AC -- "Appelle" --> C2
    AC -- "Appelle" --> C3

    C2 -- "Utilise" --> S1
    C3 -- "Utilise" --> S2

    S1 -- "Utilise" --> M1
    S2 -- "Utilise" --> M2

```