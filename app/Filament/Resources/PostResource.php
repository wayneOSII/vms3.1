<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use App\Models\Post;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PostResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PostResource\RelationManagers;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Grid;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = '公告管理';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->reactive()
                                    ->afterStateUpdated(function(Closure $set, $state) {
                                        $set('slug',Str::slug($state));
                                    }),
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255),
                                ]),
                        
                        Forms\Components\RichEditor::make('body')
                            ->required(),
                        Forms\Components\TextInput::make('meta_title'),
                        Forms\Components\TextInput::make('meta_description'),
                        Forms\Components\Toggle::make('active')
                            ->required(),
                        Forms\Components\DateTimePicker::make('published_at'),
                    ])->columnSpan(8),

                Card::make()
                    ->schema([
                        Forms\Components\FileUpload::make('thumbnail'),
                        Forms\Components\Select::make('category_id')
                            ->multiple()
                            ->relationship('categories', 'title')
                            ->required(),
                    ])->columnSpan(4),
                
            ])->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title'),
                // Tables\Columns\TextColumn::make('slug'),
                // Tables\Columns\TextColumn::make('body'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('user.name'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            // 'view' => Pages\ViewPost::route('/{record}'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }    
}
