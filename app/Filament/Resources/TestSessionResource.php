<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestSessionResource\Pages;
use App\Models\TestSession;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Collection;

class TestSessionResource extends Resource
{
    protected static ?string $model = TestSession::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Hasil Tes';
    protected static ?string $navigationLabel = 'Riwayat Tes Kraepelin';
    protected static ?int    $navigationSort  = 20;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'name')
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\TextInput::make('status')->disabled(),
                Forms\Components\TextInput::make('answered_count')->disabled(),
                Forms\Components\TextInput::make('correct_count')->disabled(),
                Forms\Components\TextInput::make('wrong_count')->disabled(),
                Forms\Components\Toggle::make('can_retake')
                    ->label('Izinkan peserta mengulang tes ini'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('started_at')
                    ->label('Mulai')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('test.name')
                    ->label('Tes')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'in_progress',
                        'success' => 'finished',
                    ]),
                Tables\Columns\TextColumn::make('answered_count')
                    ->label('Diisi')
                    ->alignRight(),
                Tables\Columns\TextColumn::make('correct_count')
                    ->label('Benar')
                    ->alignRight()
                    ->color('success'),
                Tables\Columns\TextColumn::make('wrong_count')
                    ->label('Salah')
                    ->alignRight()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('accuracy')
                    ->label('Akurasi')
                    ->alignRight()
                    ->sortable()
                    ->getStateUsing(function (TestSession $record) {
                        // Kalau bukan tes Kraepelin, pakai nilai lama saja
                        if ($record->test?->code !== 'KRAEPELIN') {
                            return $record->accuracy;
                        }

                        // Total kotak yang seharusnya dikerjakan
                        $totalTarget = $record->kraepelinAnswers()->count();   // contoh: 1350

                        if ($totalTarget === 0) {
                            return null;
                        }

                        // Jumlah jawaban benar
                        $totalCorrect = $record->kraepelinAnswers()
                            ->where('is_correct', 1)
                            ->count();

                        // Sama seperti di modal: dibulatkan ke persen terdekat
                        return (int) round(($totalCorrect / $totalTarget) * 100);
                    })
                    ->formatStateUsing(fn ($state) => $state !== null ? $state.'%' : '—'),
                Tables\Columns\IconColumn::make('can_retake')
                    ->label('Boleh ulang?')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test_id')
                    ->label('Jenis Tes')
                    ->relationship('test', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'in_progress' => 'Sedang berjalan',
                        'finished'    => 'Selesai',
                    ]),
            ])
            ->actions([
                Action::make('allowRetake')
                    ->label('Izinkan ulang tes')
                    ->requiresConfirmation()
                    ->visible(fn (TestSession $record) =>
                        $record->status === 'finished' && ! $record->can_retake
                    )
                    ->action(function (TestSession $record) {
                        $record->update(['can_retake' => true]);
                    }),

                // === DETAIL HASIL KRAEPELIN DALAM MODAL ===
                Action::make('viewKraepelinResult')
                    ->label('Detail hasil')
                    ->icon('heroicon-o-chart-bar')
                    ->color('primary')
                    ->visible(fn (TestSession $record) =>
                        $record->test?->code === 'KRAEPELIN'
                    )
                    ->modalHeading('Hasil Tes Kraepelin')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)                 
                    ->modalCancelActionLabel('Tutup')          
                    ->modalContent(function (TestSession $record) {
                        $perColumn = $record->kraepelinAnswers()
                            ->selectRaw('
                                column_index,
                                SUM(CASE WHEN user_answer IS NOT NULL THEN 1 ELSE 0 END) AS answered,
                                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END)        AS correct,
                                SUM(CASE WHEN is_correct = 0 THEN 1 ELSE 0 END)        AS wrong
                            ')
                            ->groupBy('column_index')
                            ->orderBy('column_index')
                            ->get();

                        return view('filament.modals.kraepelin-result', [
                            'record'    => $record,
                            'perColumn' => $perColumn,
                        ]);
                    }),
                Tables\Actions\EditAction::make()->label(''),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus riwayat tes')
                    ->modalSubheading('Yakin ingin menghapus riwayat tes ini? Tindakan ini tidak dapat dibatalkan.')
                    ->modalButton('Ya, hapus'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->defaultSort('started_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestSessions::route('/'),
            'create' => Pages\CreateTestSession::route('/create'),
            'edit'  => Pages\EditTestSession::route('/{record}/edit'),
        ];
    }
}
