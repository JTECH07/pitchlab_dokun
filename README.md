# ƉƆKUN — Patrimoine vivant de Porto-Novo

ƉƆKUN est une plateforme de découverte et de réservation d’expériences autour des savoir-faire traditionnels de Porto-Novo.

## Parcours disponibles

- Visiteurs, touristes, guides et partenaires : exploration des savoir-faire, carte, répertoire des artisans, catalogue d’expériences et suivi des réservations.
- Détenteurs de savoir-faire : espace « Mon atelier » pour traiter leurs propres demandes de réservation.
- Administrateurs : gestion des artisans, catégories, savoir-faire et réservations. Les routes `/admin` sont protégées par rôle.

Le paiement à l’atelier est immédiatement utilisable. L’option Mobile Money enregistre une pré-demande et attend la confirmation de l’artisan : une transaction réelle nécessite les identifiants et l’intégration d’un prestataire de paiement béninois.

## Installation

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Configurez ensuite la base de données dans `.env`, puis lancez :

```bash
php artisan migrate --seed
php artisan serve
```

## Tests

```bash
php artisan test
```

## Front-end

Les pages utilisent Tailwind via CDN pour rester légères et fonctionner immédiatement. Si vous souhaitez compiler les assets Vite du projet :

```bash
npm install --ignore-scripts
npm run build
```
