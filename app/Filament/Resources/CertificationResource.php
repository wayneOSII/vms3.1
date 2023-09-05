<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificationResource\Pages;
use App\Filament\Resources\CertificationResource\RelationManagers;
use App\Models\Certification;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CertificationResource extends Resource
{
    protected static ?string $model = Certification::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = '出缺席管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make([
                    Forms\Components\TextInput::make('semester')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('student_id')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                ])
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('semester'),
                Tables\Columns\TextColumn::make('student_id'),
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Download Pdf')
                    ->icon('heroicon-o-document-download')
                    ->url(fn(Certification $record) => route('certification.pdf.download',$record))
                    ->openUrlInNewTab()
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListCertifications::route('/'),
            'create' => Pages\CreateCertification::route('/create'),
            'edit' => Pages\EditCertification::route('/{record}/edit'),
        ];
    }    
}
