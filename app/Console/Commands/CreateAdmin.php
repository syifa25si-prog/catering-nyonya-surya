<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'catering:admin';

    protected $description = 'Buat akun admin catering secara interaktif';

    public function handle(): int
    {
        $data = ['name' => $this->ask('Nama admin'), 'email' => $this->ask('Email admin'), 'username' => $this->ask('Username admin'), 'password' => $this->secret('Kata sandi (minimal 12 karakter)')];
        $validator = Validator::make($data, ['name' => 'required|string|max:100', 'email' => 'required|email|unique:users,email', 'username' => 'required|alpha_dash|max:50|unique:users,username', 'password' => 'required|string|min:12']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $message) {
                $this->error($message);
            }

            return self::FAILURE;
        }
        $user = new User($data);
        $user->role = 'admin';
        $user->save();
        $this->info('Akun admin berhasil dibuat.');

        return self::SUCCESS;
    }
}
