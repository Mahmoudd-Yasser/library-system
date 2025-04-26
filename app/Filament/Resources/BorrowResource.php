<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowResource\Pages;
use App\Models\borrows;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BorrowResource extends Resource
{
    protected static ?string $model = borrows::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Borrow Logs';

    protected static ?string $modelLabel = 'Borrow Log';

    protected static ?string $pluralModelLabel = 'Borrow Logs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Book Title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Borrow Date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('return_date')
                    ->label('Return Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('borrow_date', 'desc')
            ->filters([
                Tables\Filters\Filter::make('borrow_date_range')
                    ->form([
                        Forms\Components\DatePicker::make('borrow_date_from')
                            ->label('Borrow Date From'),
                        Forms\Components\DatePicker::make('borrow_date_until')
                            ->label('Borrow Date Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['borrow_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('borrow_date', '>=', $date),
                            )
                            ->when(
                                $data['borrow_date_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('borrow_date', '<=', $date),
                            );
                    })
            ])
            ->actions([])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBorrows::route('/'),
        ];
    }
} 