<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentCourseResource\Pages;
use App\Models\students;
use App\Models\books;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentCourseResource extends Resource
{
    protected static ?string $model = students::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'My Courses';
    protected static ?string $modelLabel = 'Student Course';
    // protected static ?string $navigationGroup = 'Academic';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('student_id')
                    ->label('Student ID')
                    ->required()
                    ->numeric()
                    ->unique(ignoreRecord: true),
                Forms\Components\Select::make('books')
                    ->label('Books')
                    ->relationship('books', 'title')
                    ->searchable()
                    ->preload()
                    ->multiple()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('author')
                            ->required()
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Student Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('books.title')
                    ->label('Enrolled Books')
                    ->listWithLineBreaks()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListStudentCourses::route('/'),
            'create' => Pages\CreateStudentCourse::route('/create'),
            'edit' => Pages\EditStudentCourse::route('/{record}/edit'),
        ];
    }
} 