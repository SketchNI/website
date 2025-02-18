<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

use function Laravel\Prompts\info;
use function Laravel\Prompts\select;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin';

    protected $description = 'Promote a user to a specific role.';

    public function handle(): void
    {
        $users = User::all()->mapWithKeys(fn ($user) => [
            $user->id => "$user->name ($user->email)",
        ])->toArray();

        $roles = Role::pluck('display_name', 'name');

        $role = select(
            label: 'What role should the user be?',
            options: $roles,
            default: 'member',
            hint: 'The role can be changed at any time.',
        );

        $user = select(
            label: "Which user should be $role?",
            options: $users,
        );

        $user = User::find($user)->syncRoles($role);

        $log_message = "$user->name is now a {$user->roles[0]->display_name}";
        windows_os() ? $this->info($log_message) : info($log_message);

        Log::info("[cmd/make:admin] $user->name is now a $role");
    }
}
