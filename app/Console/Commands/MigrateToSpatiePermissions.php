<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MigrateToSpatiePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:migrate-to-spatie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing users from old permission system to Spatie Permission';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration to Spatie Permission...');
        $this->newLine();

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->warn('No users found in database.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        $migrated = 0;
        $skipped = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                // Skip if user already has roles
                if ($user->roles->isNotEmpty()) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Determine role based on user_type or is_super_user
                $roleName = $this->determineRole($user);
                
                // Find the role
                $role = Role::where('name', $roleName)->first();
                
                if (!$role) {
                    $this->newLine();
                    $this->error("Role '{$roleName}' not found for user {$user->user_name}");
                    $errors++;
                    $bar->advance();
                    continue;
                }

                // Assign role to user
                $user->assignRole($role);
                $migrated++;

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Error migrating user {$user->user_name}: " . $e->getMessage());
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Summary
        $this->info('Migration Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['Migrated', $migrated],
                ['Skipped', $skipped],
                ['Errors', $errors],
                ['Total', $users->count()],
            ]
        );

        if ($migrated > 0) {
            $this->newLine();
            $this->info('✅ Migration completed successfully!');
            $this->info('You can now use Spatie Permission features.');
            $this->newLine();
            $this->info('Next steps:');
            $this->info('1. Test the new permission system');
            $this->info('2. Update your routes and controllers to use new permission names');
            $this->info('3. Run: php artisan permission:cache-reset');
        }

        return Command::SUCCESS;
    }

    /**
     * Determine role based on user attributes
     *
     * @param User $user
     * @return string
     */
    protected function determineRole(User $user): string
    {
        // Check if super user
        if ($user->is_super_user) {
            return 'Super Admin';
        }

        // Map user_type to role
        return match($user->user_type) {
            'admin' => 'Super Admin',
            'super_wiser' => 'Supervisor',
            'operator' => 'Operator',
            default => 'Operator', // Default to Operator
        };
    }
}
