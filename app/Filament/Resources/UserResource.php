<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\OurCompany;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

use Filament\Navigation\NavigationItem;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
  protected static ?string $navigationGroup='Setting';

  public static function shouldRegisterNavigation(): bool
  {
    return  auth()->user()->id==1;
  }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->unique(ignoreRecord: true)->required(),
                TextInput::make('email')->email()->unique(ignoreRecord: true)->required(),
                Forms\Components\Radio::make('is_admin')->options([1=>'yes',0=>'no'])->required(),
                TextInput::make('password')->required()->visibleOn('create'),
                Select::make('roles')
                    ->searchable()
                    ->multiple()
                    ->relationship('roles','name')
                    ->preload(),
              Select::make('permissions')
                ->searchable()
                ->multiple()
                ->relationship('permissions','name')
                ->preload(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table

            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name'),
                TextColumn::make('email'),
                Tables\Columns\IconColumn::make('is_admin')->boolean(),
                TextColumn::make('created_at'),
                TextColumn::make('updated_at'),

            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
