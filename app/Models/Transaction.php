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
        'category_id', 
        'description',
        'amount',
        'type',
        'date',
        'paid',
    ];

    protected $casts = [
        'paid' => 'boolean', // Garante que o Laravel entenda como verdadeiro/falso
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