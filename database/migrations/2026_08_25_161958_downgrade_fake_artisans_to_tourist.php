<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Les utilisateurs avec role=artisan mais AUCUNE ligne dans la table artisans
        // sont des faux artisans (inscrits avant le système de candidature).
        // On les rétrograde en visiteur pour éviter qu'ils n'accèdent à l'espace artisan.
        DB::table('users')
            ->where('role', 'artisan')
            ->whereNotIn('id', function ($q) {
                $q->select('user_id')->from('artisans')->whereNotNull('user_id');
            })
            ->update(['role' => 'tourist']);
    }

    public function down(): void
    {
        // Pas de rollback automatique : on ne peut pas savoir quel rôle restaurer
    }
};
