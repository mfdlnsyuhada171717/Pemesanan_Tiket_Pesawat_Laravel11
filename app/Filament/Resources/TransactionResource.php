<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Umum')
                ->schema([
                    Forms\Components\TextInput::make('code'),
                    Forms\Components\Select::make('flight_id')
                    ->relationship('flight', 'flight_number'),
                    Forms\Components\Select::make('flight_class_id')
                    ->relationship('class', 'class_type'),
                ]),
                Forms\Components\Section::make('Informasi Penumpang')
                    ->schema([
                        Forms\Components\TextInput::make('number_of_passengers'),
                        Forms\Components\TextInput::make('name'),
                        Forms\Components\TextInput::make('email'),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\Section::make('Daftar Penumpang')
                    ->schema([
                        Forms\Components\Repeater::make('passenger')
                        ->relationship('passengers')
                        ->schema([
                            Forms\Components\TextInput::make('seat.name'),
                            Forms\Components\TextInput::make('name'),
                            Forms\Components\TextInput::make('date_of_birth'),
                            Forms\Components\TextInput::make('nationality'),
                        ])
                    ])
                        ]),
                        Forms\Components\Section::make('Pembayaran')
                        ->schema([
                            Forms\Components\TextInput::make('promo.code'),
                            Forms\Components\TextInput::make('promo.discount_type'),
                            Forms\Components\TextInput::make('promo.discount'),
                            Forms\Components\TextInput::make('payment_status'),
                            Forms\Components\TextInput::make('subtotal'),
                            Forms\Components\TextInput::make('grandtotal'),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code'),
                Tables\Columns\TextColumn::make('flight.flight_number'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('number_of_passengers'),
                Tables\Columns\TextColumn::make('promo.code'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('subtotal'),
                Tables\Columns\TextColumn::make('grandtotal'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
