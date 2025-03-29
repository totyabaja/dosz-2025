<?php

namespace App\Filament\Admin\Resources\Document;

use App\Filament\Admin\Resources\Document\DocumentTemplateResource\Pages;
use App\Filament\Admin\Resources\Document\DocumentTemplateResource\RelationManagers;
use App\Models\Document\DocumentTemplate;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use FilamentTiptapEditor\TiptapEditor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\TemplateProcessor;

class DocumentTemplateResource extends Resource
{
    protected static ?string $model = DocumentTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\KeyValue::make('parameters')
                    ->label('Parameters')
                    ->reorderable(true)
                    ->columnStart(1)
                    ->hint('Pl.: ${name}, ${date}, ${total_price}')
                    // TODO
                    ->default([
                        '${today}' => now()->format('Y-m-d'),
                        '${day}' => now()->format('l'),
                    ]),
                Forms\Components\FileUpload::make('file_path')
                    ->disk('private')
                    ->directory('doc-templates')
                    ->label('Upload Template (DOCX)')
                    ->hint('A docx fájl, amibe a tartalmat generáljuk')
                    ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                    ->downloadable(true)
                    ->required(),
                TiptapEditor::make('content')
                    ->label('content')
                    ->profile('default')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                    ->disk('private')
                    ->directory('doc-images')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->visible(fn() => Auth::user()->isSuperAdmin()),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function previewPdf($record)
    {
        if (!$record->file_path) {
            return back()->with('error', 'No template file available.');
        }

        $templatePath = Storage::disk('private')->path($record->file_path);

        // Paraméterek betöltése
        $parameters = is_array($record->parameters) ? $record->parameters : json_decode($record->parameters, true);

        try {
            $filename = "template_" . now()->format('Y-m-d_h_i_s') . ".docx";
            $filePath = "bizo_temp/{$filename}";

            // Ellenőrizd, hogy a könyvtár létezik-e
            if (!Storage::disk('private')->exists('bizo_temp')) {
                Storage::disk('private')->makeDirectory('bizo_temp');
            }

            // **DOCX fájl generálása a TiptapEditor tartalommal**
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            // TiptapEditor HTML tartalom beillesztése a Word dokumentumba
            Html::addHtml($section, $record->content, false, false);

            // DOCX fájl mentése
            $tempDocxPath = Storage::disk('private')->path($filePath);

            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($tempDocxPath);

            $my_template = new TemplateProcessor($tempDocxPath);
            $my_template->setValues($parameters ?? []);

            $my_template->saveAs($tempDocxPath);

            return response()->download($tempDocxPath)->deleteFileAfterSend(true);
        } catch (Exception $e) {
            Notification::make()
                ->title("Hibaüzenet")
                ->body("Hiba a mentés során: " . $e->getMessage())
                ->danger()
                ->send();
        }
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
            'index' => Pages\ListDocumentTemplates::route('/'),
            'create' => Pages\CreateDocumentTemplate::route('/create'),
            'edit' => Pages\EditDocumentTemplate::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
