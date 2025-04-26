<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateBook extends CreateRecord
{
    protected static string $resource = BookResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        try {
            // Generate a unique identifier for the book
            $bookId = uniqid('book_', true);
            
            // For now, just store the ID as the QR code
            // We'll implement proper QR code generation later
            $data['qr_code'] = $bookId;
            
            return $data;
        } catch (\Exception $e) {
            // If anything fails, continue without QR code
            $data['qr_code'] = null;
            return $data;
        }
    }
} 