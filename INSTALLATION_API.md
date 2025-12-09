# Instructions d'installation - API REST

## Étapes d'installation

### 1. Installer les dépendances Composer

```bash
composer require laravel/sanctum
composer require darkaonline/l5-swagger
```

### 2. Publier les configurations (si nécessaire)

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
```

### 3. Exécuter les migrations

```bash
php artisan migrate
```

Cela créera la table `personal_access_tokens` nécessaire pour Sanctum.

### 4. Générer la documentation Swagger

```bash
php artisan l5-swagger:generate
```

### 5. Vérifier les routes API

```bash
php artisan route:list --path=api
```

Vous devriez voir toutes les routes API listées.

## Configuration Sanctum

Sanctum est déjà configuré dans `bootstrap/app.php`. Si vous avez besoin de modifier la configuration, vous pouvez publier le fichier de configuration :

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## Configuration Swagger

Le fichier de configuration Swagger est dans `config/l5-swagger.php`. 

Pour accéder à la documentation Swagger une fois l'application lancée :

```
http://localhost:8000/api/documentation
```

## Test de l'endpoint Health

Une fois l'application lancée, testez l'endpoint health :

```bash
curl http://localhost:8000/api/health
```

Vous devriez recevoir une réponse JSON indiquant que la base de données est connectée.

## Dépannage

### Les routes API ne s'affichent pas

1. Vérifiez que `routes/api.php` existe et contient les routes
2. Vérifiez que `bootstrap/app.php` inclut bien `api: __DIR__.'/../routes/api.php'`
3. Exécutez `php artisan config:clear` et `php artisan route:clear`

### Erreur "Class not found"

1. Exécutez `composer dump-autoload`
2. Vérifiez que tous les packages sont installés : `composer install`

### Erreur de migration

Si la table `personal_access_tokens` existe déjà, vous pouvez ignorer cette erreur ou supprimer la table et réexécuter les migrations.

