<?php

namespace App\Filament\Resources\FinancialTransactionResource\Pages;

use App\Filament\Resources\FinancialTransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFinancialTransaction extends CreateRecord
{
    protected static string $resource = FinancialTransactionResource::class;
}
