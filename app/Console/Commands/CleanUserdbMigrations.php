<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class CleanUsersMigrations extends Command
{
    /**
     * Nom de la commande Artisan.
     */
    protected $signature = 'clean:users-migrations';

    /**
     * Description.
     */
    protected $description = 'Supprime les migrations inutiles liées à usersdb et aux colonnes supplémentaires de users';

    /**
     * Exécution.
     */
    public function handle()
    {
        $migrationPath = database_path('migrations');

        // Fichiers à supprimer
        $filesToDelete = [
            // Migrations liées à usersdb
            '2025_08_28_144202_create_usersdb_table.php',
            '2025_08_28_231041_add_status_to_usersdb_table.php',
            '2025_08_29_022547_add_registration_timestamp_to_usersdb_table.php',
            '2025_08_29_024651_remove_confirm_password_from_usersdb_table.php',

            // Migrations qui modifient users
            '2025_08_29_170500_add_fields_to_users_table.php',
            '2025_08_29_170626_add_surname_to_users_table.php',
            '2025_08_29_153941_create_groupes_table.php',
        ];

        foreach ($filesToDelete as $file) {
            $filePath = $migrationPath . DIRECTORY_SEPARATOR . $file;

            if (File::exists($filePath)) {
                File::delete($filePath);
                $this->info("✅ Supprimé : $file");

                // Supprimer aussi de la table migrations
                DB::table('migrations')->where('migration', 'like', pathinfo($file, PATHINFO_FILENAME) . '%')->delete();
                $this->info("🗑️ Nettoyé dans la table migrations : " . pathinfo($file, PATHINFO_FILENAME));
            } else {
                $this->warn("⚠️ Non trouvé : $file");
            }
        }

        $this->info("🎉 Nettoyage terminé avec succès !");
        return Command::SUCCESS;
    }
}
