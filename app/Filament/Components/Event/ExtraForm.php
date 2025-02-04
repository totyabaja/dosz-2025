<?php

namespace App\Filament\Components\Event;

use App\Models\Event\CustomForm;
use App\Models\Event\Event;
use App\Models\Event\EventFormResponse;
use App\Models\Event\EventRegistration;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Forms\Components\{Select, TextInput, Section, Textarea, Checkbox, Radio, Hidden};
use Illuminate\Support\Facades\Auth;

class ExtraForm
{
    public static function schema(CustomForm $customForm, ?EventRegistration $event_reg = null): array
    {
        $content = $customForm->content;
        $response = EventFormResponse::query()
            ->where('custom_form_id', $customForm->id)
            ->where('event_registration_id', $event_reg?->id ?? null)
            ->first()?->responses ?? null;

        // Betöltjük az eddig mentett válaszokat
        $attribute_name = 'event_form_response.responses';

        $formComponents = [
            // TODO: valamiért nem látja
            Hidden::make('custom_form_id')
                ->default($customForm->id ?? null)
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
                        ->formatStateUsing(fn() => $response[$data['id']] ?? null);
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->pluck('value', 'id')->toArray();
                    $formComponents[] = Select::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->options($options)
                        ->native(false)
                        ->multiple($data['multiple'] ?? false)
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->placeholder($data['placeholder'] ?? null)
                        ->formatStateUsing(fn() => $response[$data['id']] ?? null);
                    break;

                case 'checkbox':
                    $formComponents[] = Checkbox::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->formatStateUsing(fn() => $response[$data['id']] ?? null);
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = Radio::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->options($options)
                        ->required($data['required'] ?? false)
                        ->helperText($data['helperText'] ?? null)
                        ->formatStateUsing(fn() => $response[$data['id']] ?? null);
                    break;

                case 'textarea':
                    $formComponents[] = Textarea::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->formatStateUsing(fn() => $response[$data['id']] ?? null);
                    break;
            }
        }

        return $formComponents;
    }
}
