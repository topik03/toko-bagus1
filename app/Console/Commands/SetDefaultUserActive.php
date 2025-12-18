<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetDefaultUserActive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:set-active-default
                            {--dry-run : Show what would be updated without actually updating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set default is_active for all existing users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        $this->info('Setting default is_active for users...');

        // Cek jika column is_active sudah ada
        if (!Schema::hasColumn('users', 'is_active')) {
            $this->error('Column is_active does not exist in users table!');
            $this->error('Run migration first: php artisan make:migration add_is_active_to_users_table');
            return 1;
        }

        // Hitung user yang is_active null atau tidak ada
        $nullUsers = User::whereNull('is_active')->count();
        $falseUsers = User::where('is_active', false)->count();
        $trueUsers = User::where('is_active', true)->count();

        $this->table(
            ['Status', 'Count'],
            [
                ['NULL', $nullUsers],
                ['False', $falseUsers],
                ['True', $trueUsers],
                ['Total', User::count()],
            ]
        );

        if ($dryRun) {
            $this->info('[DRY RUN] Would update ' . $nullUsers . ' users to is_active = true');
            return 0;
        }

        if ($this->confirm('Set is_active = true for ' . $nullUsers . ' users?', true)) {
            // Update user yang is_active null
            $updated = User::whereNull('is_active')->update(['is_active' => true]);

            $this->info('✅ Updated ' . $updated . ' users to is_active = true');

            // Juga set semua user ke aktif jika mau
            if ($this->confirm('Set ALL users to active? (including false ones)', false)) {
                $allUpdated = User::where('is_active', false)->update(['is_active' => true]);
                $this->info('✅ Updated additional ' . $allUpdated . ' users to active');
            }

            $this->info('🎉 Done! Total active users: ' . User::where('is_active', true)->count());
        } else {
            $this->info('Operation cancelled.');
        }

        return 0;
    }
}
