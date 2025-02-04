<?php

namespace App\Filament\Components\Event;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use CodeWithDennis\SimpleAlert\Components\Infolists\SimpleAlert;
use Filament\Infolists\Components\{Fieldset, Grid, IconEntry, RepeatableEntry, Section, Tabs, TextEntry, ViewEntry};
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Whoops\Exception\Formatter;

class PublicationInfolist
{
    public static function schema(): array
    {
        return [
            RepeatableEntry::make('publications')
                ->label(__('resource.tabs.publications'))
                ->schema([
                    TextEntry::make('publication_type')
                        ->label(__('resource.components.publication_type'))
                        ->inlineLabel(),

                    RepeatableEntry::make('authors')
                        ->label(__('resource.components.authors'))
                        ->columns([
                            'default' => 1,
                            'md' => 3,
                        ])
                        ->schema([
                            TextEntry::make('name')
                                ->label(__('resource.components.name')),

                            TextEntry::make('affiliation')
                                ->label(__('resource.components.affiliation')),
                            TextEntry::make('email')
                                ->label(__('resource.components.email'))
                                ->visible(fn($state) => $state),
                        ]),

                    Grid::make([
                        'default' => 1,
                    ])->schema(fn($state) => [
                        TextEntry::make("abstract")
                            ->columnStart(1)
                            ->hiddenLabel()
                            ->formatStateUsing(fn($state) => sprintf(
                                '<span style="background-color: blue; color: white; padding: 2px 6px; border-radius: 4px; font-size: 12px; font-weight: bold;">%s</span> %s',
                                strtoupper($state['language']),
                                $state['title']
                            ))
                            ->html()
                            ->weight(FontWeight::Bold)
                            ->alignCenter()
                            ->size(30),
                        ViewEntry::make('abstract.abstract')
                            ->label(__('resource.components.abstract'))
                            ->view('filament.custom_layouts.tinyeditor_view')
                            ->columnSpanFull(),
                        TextEntry::make('abstract.keywords')
                            ->label(__('resource.components.keywords'))
                            ->weight(FontWeight::Bold),
                    ]),



                ]),
        ];
    }
}
