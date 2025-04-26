<?php

namespace App\Filament\Resources\QrLogResource\Pages;

use App\Filament\Resources\QrLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQrLogs extends ListRecords
{
    protected static string $resource = QrLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions since this is read-only
        ];
    }
} 