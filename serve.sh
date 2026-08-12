#!/bin/bash
# ============================================================
# Script de démarrage du serveur ƉƆKUN (développement local)
# Force le chargement de la libpq système (PostgreSQL 18)
# pour résoudre le conflit de version sur Fedora
# ============================================================

export LD_PRELOAD=/usr/lib64/libpq.so.5.18

echo "🟡 LD_PRELOAD = $LD_PRELOAD"
echo "🐘 Test de connexion PostgreSQL..."

php artisan db:show 2>&1 | head -10

echo ""
echo "🚀 Démarrage du serveur ƉƆKUN sur http://localhost:8000"
echo "   Appuyez sur Ctrl+C pour arrêter."
echo ""

php artisan serve "$@"
