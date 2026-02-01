<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id', // Agora o banco vai reconhecer este campo
        'description',
        'amount',
        'type',
        'date',
    ];

    /**
     * Relacionamento: Uma transação pertence a um usuário.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Uma transação pertence a uma categoria.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}