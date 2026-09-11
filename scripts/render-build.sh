#!/usr/bin/env bash
set -e

echo "=== [1/6] Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader --no-interaction

echo "=== [2/6] Installing JS dependencies & building assets ==="
npm install --ignore-scripts
npm run build

echo "=== [3/6] Running database migrations ==="
php artisan migrate --force

echo "=== [4/6] Seeding new categories ==="
php artisan tinker --execute="
use App\Models\Category;
use App\Models\SavoirFaire;
Category::firstOrCreate(['slug' => 'arts-feu-metal'], ['name' => 'Arts du Feu & Métal', 'description' => 'Forge, fonderie, orfèvrerie, dinanderie — le travail du bronze, du fer et de l\'or par les techniques ancestrales.']);
Category::firstOrCreate(['slug' => 'instruments-traditionnels'], ['name' => 'Instruments Traditionnels', 'description' => 'Fabrication de tambours, xylophones, harpes et instruments de cérémonie — la musique vivante du patrimoine Fon/Gun.']);
SavoirFaire::firstOrCreate(['slug' => 'forge-fonderie'], ['name' => 'Forge & Fonderie', 'category_id' => Category::where('slug','arts-feu-metal')->first()->id, 'description' => 'Taillage du fer et du bronze au feu de charbon.']);
SavoirFaire::firstOrCreate(['slug' => 'orfevrerie-bronze'], ['name' => 'Orfèvrerie & Bronze', 'category_id' => Category::where('slug','arts-feu-metal')->first()->id, 'description' => 'Travail de l\'or et du bronze pour bijoux et parures.']);
SavoirFaire::firstOrCreate(['slug' => 'dinanderie-cuivre'], ['name' => 'Dinanderie & Cuivre', 'category_id' => Category::where('slug','arts-feu-metal')->first()->id, 'description' => 'Façonnage de cloches et récipients en cuivre martelé.']);
SavoirFaire::firstOrCreate(['slug' => 'fabrication-tambours'], ['name' => 'Fabrication de Tambours', 'category_id' => Category::where('slug','instruments-traditionnels')->first()->id, 'description' => 'Sculpture et tension de peaux sur caisses en bois.']);
SavoirFaire::firstOrCreate(['slug' => 'xylophones-harpes'], ['name' => 'Xylophones & Harpes', 'category_id' => Category::where('slug','instruments-traditionnels')->first()->id, 'description' => 'Construction de xylophones et harpes traditionnels.']);
" 2>/dev/null || echo "Seeding skipped"

echo "=== [5/6] Clearing and caching config, routes & views ==="
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== [6/6] Build complete ==="
