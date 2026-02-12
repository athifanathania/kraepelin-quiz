<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestSessionResource\Pages;
use App\Models\TestSession;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;

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
                // Field statistik di form ini opsional ditampilkan karena kita hitung ulang di tabel
                Forms\Components\TextInput::make('answered_count')->disabled()->label('Total Dijawab'),
                Forms\Components\TextInput::make('correct_count')->disabled()->label('Benar'),
                Forms\Components\TextInput::make('wrong_count')->disabled()->label('Salah'),
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
                    ->formatStateUsing(fn ($state) => 
                        $state->format('d-m-Y') . '<br><span class="text-gray-400 text-xs">' . $state->format('H:i') . '</span>'
                    )
                    ->html() 
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Peserta')
                    ->wrap()
                    ->extraAttributes(['style' => 'max-width: 140px;'])
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

                Tables\Columns\TextColumn::make('filled_real')
                    ->label('Diisi')
                    ->alignRight()
                    ->getStateUsing(function (TestSession $record) {
                        if ($record->test?->code !== 'KRAEPELIN') return $record->kraepelinAnswers()->count();
                        return $record->kraepelinAnswers()->whereNotNull('user_answer')->count();
                    }),

                Tables\Columns\TextColumn::make('correct_real')
                    ->label('Benar')
                    ->alignRight()
                    ->color('success')
                    ->getStateUsing(function (TestSession $record) {
                        if ($record->test?->code !== 'KRAEPELIN') return $record->correct_count;
                        return $record->kraepelinAnswers()->where('is_correct', 1)->count();
                    }),

                Tables\Columns\TextColumn::make('wrong_real')
                    ->label('Salah')
                    ->alignRight()
                    ->color('danger')
                    ->getStateUsing(function (TestSession $record) {
                        if ($record->test?->code !== 'KRAEPELIN') return $record->wrong_count;
                        return $record->kraepelinAnswers()
                            ->whereNotNull('user_answer')
                            ->where('is_correct', 0)
                            ->count();
                    }),

                Tables\Columns\TextColumn::make('accuracy_real')
                    ->label('Akurasi')
                    ->alignRight()
                    ->getStateUsing(function (TestSession $record) {
                        if ($record->test?->code !== 'KRAEPELIN') return $record->accuracy;
                        
                        $correct = $record->kraepelinAnswers()->where('is_correct', 1)->count();
                        $filled = $record->kraepelinAnswers()->whereNotNull('user_answer')->count();

                        if ($filled === 0) return null;
                        return (int) round(($correct / $filled) * 100);
                    })
                    ->formatStateUsing(fn ($state) => $state !== null ? $state.'%' : '—'),

                Tables\Columns\IconColumn::make('can_retake')
                    ->label('Ulang?')
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
                    ->label('Izinkan ulang')
                    ->requiresConfirmation()
                    ->visible(fn (TestSession $record) =>
                        $record->status === 'finished' && ! $record->can_retake
                    )
                    ->action(function (TestSession $record) {
                        $record->update(['can_retake' => true]);
                    }),

                Action::make('viewChart')
                    ->label('Grafik')
                    ->icon('heroicon-o-presentation-chart-line')
                    ->color('info')
                    ->visible(fn (TestSession $record) => $record->test?->code === 'KRAEPELIN')
                    ->modalHeading('Grafik Stabilitas Kerja')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (TestSession $record) {
                        // LOGIKA GRAFIK (Ini sudah benar menurut Anda, jadi kita pertahankan)
                        $perColumn = $record->kraepelinAnswers()
                            ->selectRaw('
                                column_index,
                                SUM(CASE WHEN user_answer IS NOT NULL THEN 1 ELSE 0 END) as answered,
                                SUM(CASE WHEN is_correct = 1 THEN 1 ELSE 0 END) as correct,
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

                Action::make('viewVisual')
                    ->label('Detail')
                    ->icon('heroicon-o-eye')
                    ->modalWidth('7xl')
                    ->modalSubmitAction(false) 
                    ->modalCancelActionLabel('Tutup')
                    ->modalContent(function (TestSession $record) { 
                        $answersByColumn = $record->kraepelinAnswers()
                            ->orderBy('column_index')
                            ->orderBy('row_index')
                            ->get()
                            ->groupBy('column_index');

                        return view('filament.modals.kraepelin-visual', [
                            'answersByColumn' => $answersByColumn,
                            'record'          => $record, 
                            'session'         => $record, 
                        ]);
                    }),
                    
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->requiresConfirmation(),
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