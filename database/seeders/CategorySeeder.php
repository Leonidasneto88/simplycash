<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\User;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pega o primeiro usuário do sistema
        $user = \App\Models\User::where('email', 'leonidasneto88@gmail.com')->first();

        if (!$user) {
            $this->command->error('Usuário não encontrado! Verifique o e-mail no Seeder.');
            return;
        }

        $categories = [
            // RECEITAS (Income)
            ['name' => 'Salário', 'type' => 'income', 'icon' => 'wallet', 'color' => '#10b981'],
            ['name' => 'Investimentos', 'type' => 'income', 'icon' => 'trending-up', 'color' => '#3b82f6'],
            ['name' => 'Freelance', 'type' => 'income', 'icon' => 'briefcase', 'color' => '#06b6d4'],

            // DESPESAS (Expense)
            ['name' => 'Alimentação', 'type' => 'expense', 'icon' => 'utensils', 'color' => '#ef4444'],
            ['name' => 'Moradia', 'type' => 'expense', 'icon' => 'home', 'color' => '#f59e0b'],
            ['name' => 'Lazer', 'type' => 'expense', 'icon' => 'smile', 'color' => '#8b5cf6'],
            ['name' => 'Transporte', 'type' => 'expense', 'icon' => 'car', 'color' => '#64748b'],
            ['name' => 'Saúde', 'type' => 'expense', 'icon' => 'heart', 'color' => '#ec4899'],
        ];

        foreach ($categories as $category) {
            Category::create(array_merge($category, ['user_id' => $user->id]));
        }
    }
}
