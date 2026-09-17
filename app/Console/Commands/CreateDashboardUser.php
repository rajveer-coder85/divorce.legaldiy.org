<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateDashboardUser extends Command
{
    protected $signature = 'dashboard:user {email : Login email address} {--name=LegalDIY Administrator : Display name}';
    protected $description = 'Create or update a secured LegalDIY dashboard user';

    public function handle(): int
    {
        $email = strtolower(trim((string) $this->argument('email')));
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) { $this->error('Enter a valid email address.'); return self::FAILURE; }
        $password = $this->secret('Password (minimum 12 characters)');
        $confirmation = $this->secret('Confirm password');
        if (strlen((string) $password) < 12 || $password !== $confirmation) { $this->error('Passwords must match and contain at least 12 characters.'); return self::FAILURE; }
        User::updateOrCreate(['email' => $email], ['name' => (string) $this->option('name'), 'password' => Hash::make($password)]);
        $this->info("Dashboard access is ready for {$email}.");
        return self::SUCCESS;
    }
}
