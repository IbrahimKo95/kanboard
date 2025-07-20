<?php

require_once 'vendor/autoload.php';

// Charger l'application Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "🔧 Réparation de la base de données...\n";

try {
    // Vérifier si la table priorities existe
    if (!Schema::hasTable('priorities')) {
        echo "📋 Création de la table priorities...\n";
        
        Schema::create('priorities', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });
        
        echo "✅ Table priorities créée avec succès!\n";
    } else {
        echo "ℹ️  Table priorities existe déjà.\n";
    }
    
    // Vérifier si des priorités existent
    $count = DB::table('priorities')->count();
    if ($count === 0) {
        echo "📝 Ajout des priorités de base...\n";
        
        DB::table('priorities')->insert([
            ['name' => 'Urgente', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Importante', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Moyenne', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Faible', 'created_at' => now(), 'updated_at' => now()],
        ]);
        
        echo "✅ Priorités ajoutées avec succès!\n";
    } else {
        echo "ℹ️  Priorités existent déjà ($count trouvées).\n";
    }
    
    echo "\n🎉 Base de données réparée avec succès!\n";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
} 