<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
// Remova as importações manuais se estiverem causando conflito e use o caminho completo no método
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(\App\Models\Transaction::class);
    }

    public function categories(): HasMany
    {
        // Usar o caminho completo aqui ajuda o VS Code a indexar
        return $this->hasMany(\App\Models\Category::class);
    }
}