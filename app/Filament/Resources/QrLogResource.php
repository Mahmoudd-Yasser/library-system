<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QrLogResource\Pages;
use App\Models\qr_logs;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QrLogResource extends Resource
{
    protected static ?string $model = qr_logs::class;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static ?string $navigationLabel = 'QR Logs';

    protected static ?string $modelLabel = 'QR Log';

    protected static ?string $pluralModelLabel = 'QR Logs';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_in')
                    ->label('Check In')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->label('Check Out')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('check_in', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('library_status')
                    ->label('Library Status')
                    ->options([
                        'inside' => 'Still Inside',
                        'checked_out' => 'Checked Out',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return match ($data['value']) {
                            'inside' => $query->whereNull('check_out'),
                            'checked_out' => $query->whereNotNull('check_out'),
                            default => $query,
                        };
                    }),
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQrLogs::route('/'),
        ];
    }
} 