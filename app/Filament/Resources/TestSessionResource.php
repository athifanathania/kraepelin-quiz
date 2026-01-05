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
use App\Support\KraepelinTemplate;

class TestSessionResource extends Resource
{
    protected static ?string $model = TestSession::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationGroup = 'Hasil Tes';
    
    protected static ?string $navigationLabel = 'Riwayat Tes Kraepelin'; 
    protected static ?string $pluralModelLabel = 'Riwayat Tes Kraepelin';
    protected static ?string $modelLabel = 'Riwayat Tes'; 

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

                // Action 1: GRAFIK HASIL (Ringan, hanya load summary)
                Action::make('viewChart')
                    ->label('Grafik')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->color('info') // Warna biru
                    ->visible(fn (TestSession $record) => $record->test?->code === 'KRAEPELIN')
                    ->modalHeading('Grafik Stabilitas Kerja')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (TestSession $record) {
                        $perColumn = $record->kraepelinAnswers()
                            ->selectRaw('
                                column_index,
                                
                                /* Menghitung kotak yang SUDAH DIISI saja (tidak null) */
                                SUM(CASE WHEN user_answer IS NOT NULL THEN 1 ELSE 0 END) as answered,

                                /* Menghitung jawaban Benar */
                                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct,

                                /* Menghitung jawaban Salah (hanya jika sudah diisi) */
                                SUM(CASE WHEN is_correct = 0 AND user_answer IS NOT NULL THEN 1 ELSE 0 END) as wrong
                            ')
                            ->groupBy('column_index')
                            ->orderBy('column_index')
                            ->get();

                        return view('filament.modals.kraepelin-chart', [
                            'record'    => $record,
                            'perColumn' => $perColumn,
                        ]);
                    }),

                // Action 2: DETAIL VISUALISASI (Lebih detail, load per kotak)
                Action::make('viewVisual') // Sesuaikan nama action-nya jika beda
                    ->label('Detail Visual')
                    ->icon('heroicon-o-eye')
                    ->modalContent(function ($record) {
                        // 1. Ambil jawaban user menggunakan method answers()->get() agar tidak null
                        $answersByColumn = $record->answers()->get()->groupBy('column_index');

                        // 2. LOGIC INJECT ANGKA SOAL
                        foreach ($answersByColumn as $colIndex => $answers) {
                            // Ambil kunci jawaban asli dari Template
                            $sourceNumbers = KraepelinTemplate::getColumnChain((int)$colIndex);

                            foreach ($answers as $index => $ans) {
                                // Pastikan index ada di range soal
                                if (isset($sourceNumbers[$index]) && isset($sourceNumbers[$index + 1])) {
                                    $ans->bottom_number = $sourceNumbers[$index];     // Angka Bawah
                                    $ans->top_number    = $sourceNumbers[$index + 1]; // Angka Atas
                                } else {
                                    $ans->bottom_number = '?';
                                    $ans->top_number = '?';
                                }
                            }
                        }

                        // 3. Return View
                        return view('filament.modals.kraepelin-visual', [
                            'answersByColumn' => $answersByColumn,
                        ]);
                    })
                    ->modalWidth('7xl'),
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
