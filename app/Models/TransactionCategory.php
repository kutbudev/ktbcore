<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionCategory extends Model
{
    public function financialTransactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class, 'category_id');
    }
}
