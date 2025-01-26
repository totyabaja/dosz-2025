<?php

namespace App\Filament\Components\Event;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use

Filament\Forms\Components\{Select, TextInput, Section, Fieldset, Grid, Placeholder, Repeater, Radio, Tabs, TagsInput};
use Illuminate\Support\Facades\Auth;

class PublicationForm
{
    public static function schema(): array
    {
        return [
            Repeater::make('publications')
                ->label(__('resource.tabs.publications'))
                ->relationship()
                ->minItems(1)
                ->required()
                ->orderColumn('publication_order')
                ->reorderableWithDragAndDrop()
                ->reorderableWithButtons()
                ->schema([

                    SimpleAlert::make('tipus_danger')
                        ->danger()
                        ->description('Jelöld, hogy előadást, vagy poszter szeretnél.'),

                    Radio::make('publication_type')
                        ->label(__('resource.components.publication_type'))
                        ->inline()
                        ->options([
                            'poster' => 'Pószter',
                            'oral' => 'Előadás',
                        ])
                        ->required(),

                    Tabs::make()
                        ->persistTabInQueryString()
                        ->schema([
                            Tabs\Tab::make('Szerzők')
                                ->schema([
                                    Repeater::make('authors')
                                        ->label(__('resource.components.authors'))
                                        ->relationship()
                                        ->minItems(1)
                                        ->required()
                                        ->orderColumn('author_order')
                                        ->reorderableWithDragAndDrop()
                                        ->reorderableWithButtons()
                                        ->collapsible()
                                        ->schema([
                                            Fieldset::make()
                                                ->label(__('resource.components.name'))
                                                ->columns(5)
                                                ->schema([
                                                    Select::make('name.titulus')
                                                        ->label(__('resource.components.titulus'))
                                                        ->options([
                                                            '' => '-',
                                                            'dr.' => 'dr.',
                                                            'Dr.' => 'Dr. (PhD/DLA)',
                                                            'Prof.' => 'Prof. Dr. (PhD/DLA)',
                                                            'ifj.' => 'ifjabb',
                                                            'id.' => 'idősebb',
                                                            'öz.' => 'özvegy.',
                                                        ])
                                                        ->default('')
                                                        ->columnSpan(1)
                                                        ->nullable(),
                                                    TextInput::make('name.lastname')
                                                        ->label(__('resource.components.lastname'))
                                                        ->columnSpan(2)
                                                        ->required(),
                                                    TextInput::make('name.firstname')
                                                        ->label(__('resource.components.firstname'))
                                                        ->columnSpan(2)
                                                        ->required(),
                                                ]),
                                            TextInput::make('affiliation')
                                                ->label(__('resource.components.affiliation'))
                                                ->columnSpan(2)
                                                ->required(),
                                            TextInput::make('email')
                                                ->label(__('resource.components.email'))
                                                ->columnSpan(2)
                                                ->hidden(function ($component) {
                                                    $firstItemKey = array_key_first($component->getContainer()->getParentComponent()->getState());

                                                    return ! str_contains($component->getStatePath(), $firstItemKey);
                                                })
                                                ->required(function ($component) {
                                                    $firstItemKey = array_key_first($component->getContainer()->getParentComponent()->getState());

                                                    return str_contains($component->getStatePath(), $firstItemKey);
                                                }),
                                        ]),
                                ]),
                            Tabs\Tab::make('Absztrakt')
                                ->label(__('resource.tabs.abstract'))
                                ->schema([
                                    Section::make()
                                        ->hiddenLabel()
                                        ->relationship('abstract')
                                        ->schema([
                                            Select::make('language')
                                                ->label(__('resource.components.language'))
                                                ->options([
                                                    'hu' => 'magyar',
                                                    'en' => 'angol',
                                                ])
                                                ->default('hu')
                                                ->native(false)
                                                ->live()
                                                ->required(),
                                            TextInput::make("title")
                                                ->label(__('resource.components.title'))
                                                ->required(),
                                            TinyEditor::make('abstract')
                                                ->label(__('resource.components.abstract'))
                                                ->fileAttachmentsDisk('public')
                                                ->fileAttachmentsVisibility('public')
                                                ->fileAttachmentsDirectory('uploads') // TODO
                                                ->profile('simple')
                                                ->required(),
                                            // TODO !!!
                                            TagsInput::make('keywords')
                                                ->label(__('resource.components.keywords'))
                                                ->splitKeys(['Tab', ','])
                                                ->placeholder('Kulcsszavak megadása')
                                                ->reorderable()
                                                ->afterStateHydrated(function ($state, $set) {
                                                    if ($state) {
                                                        return;
                                                    }

                                                    $set('field_name', null);
                                                })
                                            //->required(),

                                        ]),
                                ]),
                        ]),

                ]),
        ];
    }
}
