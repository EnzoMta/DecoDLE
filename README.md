# DecoDLE

Jeu quotidien inspiré de Wordle, centré sur des personnalités de la tech et du dev. Trois modes de jeu disponibles : **Classique**, **Emoji** et **Photo**.

Construit avec Laravel 13, Livewire 4 et Flux UI.

## Prérequis

- PHP 8.3+
- Composer
- Node.js 18+ et npm
- SQLite (inclus avec PHP) **ou** PostgreSQL 16+ (pour Docker)

## Lancement en local (SQLite)

```bash
# 1. Cloner le dépôt
git clone <url-du-repo>
cd DecoDLE

# 2. Installer les dépendances PHP et JS, générer la clé, migrer la BDD
composer run setup

# 3. Lancer le serveur de développement
composer run dev
```

L'application sera accessible sur [http://localhost:8000](http://localhost:8000).

> La commande `composer run setup` exécute dans l'ordre :
> `composer install` → copie du `.env` → `php artisan key:generate` → `php artisan migrate` → `npm install` → `npm run build`

---

## Lancement via Docker (PostgreSQL)

```bash
# 1. Copier et configurer le fichier d'environnement
cp .env.example .env
```

Modifier `.env` pour utiliser PostgreSQL :

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=decodle
DB_USERNAME=decodle
DB_PASSWORD=secret
```

```bash
# 2. Démarrer les conteneurs
docker compose up -d

# 3. Générer la clé d'application
docker compose exec app php artisan key:generate

# 4. Exécuter les migrations et les seeders
docker compose exec app php artisan migrate --seed
```

L'application sera accessible sur [http://localhost:8000](http://localhost:8000).

---

## Peupler la base de données

Les seeders créent les personnalités, les puzzles quotidiens et un compte admin.

```bash
# En local
php artisan db:seed

# Via Docker
docker compose exec app php artisan db:seed
```

---

## Modes de jeu

| Route           | Mode      | Description                                 |
| --------------- | --------- | ------------------------------------------- |
| `/game/classic` | Classique | Deviner la personnalité via des indices     |
| `/game/emoji`   | Emoji     | Deviner la personnalité via des emojis      |
| `/game/photo`   | Photo     | Deviner la personnalité via une photo floue |

---

## Administration

Le tableau de bord admin est accessible sur `/dashboard` pour les utilisateurs avec le rôle `admin`.

---

## Commandes utiles

```bash
# Lancer les tests
composer run test

# Linter (PHP CS Fixer via Pint)
composer run lint

# Vérifier le style sans modifier
composer run lint:check
```
