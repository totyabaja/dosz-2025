<?php

namespace App\Filament\Components\Event;

use App\Models\Event\CustomForm;
use App\Models\Event\Event;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Forms\Components\{Select, TextInput, Section, Textarea, Checkbox, Radio, Hidden};
use Illuminate\Support\Facades\Auth;

class ExtraForm
{
    public static function schema(?CustomForm $customForm): array
    {
        $content = $customForm->content;

        // Betöltjük az eddig mentett válaszokat
        $responses = $record->event_form_response->responses ?? [];

        $attribute_name = 'responses';

        $formComponents = [
            // TODO: valamiért nem látja
            Hidden::make('custom_form_id')
                ->default($custom_form->id ?? "dsd")
                //->dehydrated(false)
                ->afterStateHydrated(fn($set) => $set('custom_form_id', $customForm->id))
                ->required(),
        ];

        foreach ($content as $field) {
            $data = $field['data'];
            $type = $field['type'] ?? '';
            $fieldId = $data['id'];

            switch ($type) {
                case 'text_input':
                    $formComponents[] = TextInput::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses['id'] ?? null);
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = Select::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->options($options)
                        ->native(false)
                        ->multiple($data['multiple'] ?? false)
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->placeholder($data['placeholder'] ?? null)
                        ->default($responses['id'] ?? null);
                    break;

                case 'checkbox':
                    $formComponents[] = Checkbox::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses['id'] ?? null);
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = Radio::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->options($options)
                        ->required($data['required'] ?? false)
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses['id'] ?? null);
                    break;

                case 'textarea':
                    $formComponents[] = Textarea::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses['id'] ?? null);
                    break;
            }
        }

        return $formComponents;
    }
}
