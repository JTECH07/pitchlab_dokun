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

## Files d'attente (queues)

Les emails (vérification, mots de passe temporaires, notifications artisan/acteur, formulaire de contact) sont **asynchrones** et passent par la file `database`. En production, un worker doit tourner en permanence, sinon **aucun email ne part** :

```bash
php artisan queue:work
```

Il est recommandé de configurer [Supervisor](https://supervisord.org/) (ou systemd) pour maintenir ce process actif :

```ini
[program:php-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /chemin/vers/dokun/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
```

> **Attention** : les emails restent bloqués dans la table `jobs` tant qu'un worker n'est pas lancé.

## Variables d'environnement clés (.env)

| Variable | Rôle |
|----------|------|
| `QUEUE_CONNECTION=database` | File d'attente des emails |
| `MAIL_*` (Brevo SMTP) | Envoi des emails |
| `FEDAPAY_*` | Paiement mobile money (primary gateway) |
| `GEMINI_API_KEY` | Bridge vocabulaire / traduction IA (les réponses de secours sont utilisées si vide) |
