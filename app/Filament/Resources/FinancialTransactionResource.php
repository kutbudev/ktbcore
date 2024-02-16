<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FinancialTransactionResource\Pages;
use App\Filament\Resources\FinancialTransactionResource\RelationManagers;
use App\Models\Account;
use App\Models\FinancialTransaction;
use App\Models\TransactionCategory;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FinancialTransactionResource extends Resource
{
    protected static ?string $model = FinancialTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->prefix('₺')
                    ->maxValue(42949672.95),
                Forms\Components\RichEditor::make('description')
                    ->toolbarButtons([
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->required()
                    ->maxLength(255),

                Forms\Components\Select::make('type')
                    ->options([
                        'expense' => 'Expense',
                        'income' => 'Income',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('transaction_date')
                    ->timezone('Europe/Istanbul')
                    ->native(false)
                    ->required()
                    ->default(now()->format('Y-m-d H:i:s')), // Set default value to current date and time

                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\Select::make('account_id')
                    ->relationship('account', 'name')
                    ->required(),
                FileUpload::make('attachments')
                    ->directory('financial-transactions-attachments')
                    ->visibility('private')
                    ->multiple()
                    ->reorderable()
                    ->appendFiles()
                    ->openable()
                    ->downloadable()
                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png'])
            ]);


    }

    public static function table(Table $table): Table
    {

        // Fetch records from the Account and TransactionCategory models
        $accounts = Account::all()->pluck('name', 'id')->toArray();
        $categories = TransactionCategory::all()->pluck('name', 'id')->toArray();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('amount')
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('account.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'expense' => 'Expense',
                        'income' => 'Income',
                    ]),
                Tables\Filters\SelectFilter::make('account')
                    ->options($accounts),
                Tables\Filters\SelectFilter::make('category')
                    ->options($categories),



            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFinancialTransactions::route('/'),
            'create' => Pages\CreateFinancialTransaction::route('/create'),
            'edit' => Pages\EditFinancialTransaction::route('/{record}/edit'),
        ];
    }
}
