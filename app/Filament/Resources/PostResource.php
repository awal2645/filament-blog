<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Widgets\CustomerOverview;
use App\Filament\Resources\PostResorceResource\Widgets\StatsOverview;
use App\Filament\Resources\PostResource\Pages;

use App\Models\Post;
use Closure;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\SelectFilter;



class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form->schema([
            
                Card::make()->schema([
                    Select::make('category_id')->relationship('category', 'name'),
                    TextInput::make('title')
                        ->reactive()
                        ->afterStateUpdated(function (Closure $set, $state) {
                            $set('slug', Str::slug($state));
                        }),
                    TextInput::make('slug')->required(),
                    SpatieMediaLibraryFileUpload::make('thumbnail')->collection('posts'),
                    RichEditor::make('content'),
                    Toggle::make('is_published')

                ]),
            
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('title')->searchable()->toggleable()->sortable()->limit(50),
                TextColumn::make('slug')->toggleable()->sortable()->limit(50),
                SpatieMediaLibraryImageColumn::make('thumbnail')->collection('posts')->toggleable(),
                BooleanColumn::make('is_published'),
            ])
            ->filters([
                Filter::make('Published')
                ->query(fn (Builder $query): Builder => $query->where('is_published', true)),
                Filter::make('Unpublished')
                ->query(fn (Builder $query): Builder => $query->where('is_published', false)),
                SelectFilter::make('category')->relationship('category', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }
    public static function getWidgets(): array
    {
        return [
            StatsOverview::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
