<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    protected static ?string $navigationGroup = 'Master Data';
    
    protected static ?string $navigationLabel = 'Kelola Pengguna';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengguna')
                    ->description('Kelola detail login dan hak akses pengguna.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('role')
                            ->label('Role / Peran')
                            ->options([
                                'admin' => 'Administrator',
                                'peserta' => 'Peserta Tes',
                            ])
                            ->required()
                            ->native(false),
                        
                        // Toggle Status Aktif
                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Akun Aktif')
                            ->helperText('Jika dimatikan, pengguna tidak akan bisa login.')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->label('Password Baru')
                            ->helperText('Kosongkan jika tidak ingin mengubah password.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar_url')
                    ->label('')
                    ->defaultImageUrl(fn ($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&color=7F9CF5&background=EBF4FF')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Pengguna')
                    ->description(fn (User $record): string => $record->email)
                    ->searchable(['name', 'email'])
                    ->sortable()
                    ->weight('bold'),

                // WARNA ROLE (Badge)
                Tables\Columns\TextColumn::make('role')
                    ->badge() // Membuat tampilan seperti label
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',   // Admin warna Merah/Danger (Supaya terlihat penting)
                        'peserta' => 'info',   // Peserta warna Biru/Info
                        default => 'gray',
                    })
                    ->icon(fn (string $state): ?string => match ($state) {
                        'admin' => 'heroicon-m-shield-check',
                        'peserta' => 'heroicon-m-user',
                        default => null,
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                // STATUS AKTIF (Icon & Warna)
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-no-symbol')
                    ->trueColor('success') // Hijau jika aktif
                    ->falseColor('danger') // Merah jika non-aktif
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->color('gray')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'peserta' => 'Peserta Tes',
                    ]),
                // Filter untuk melihat user yg aktif/non-aktif
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status Akun'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}