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

class ExtraFormForm
{
    public static function schema(CustomForm $customForm, EventRegistration $event_reg, string $attribute_name): array
    {
        $content = $customForm->content;
        $responses = $event_reg->{$attribute_name} ?? '';


        $formComponents = [
            // TODO: valamiért nem látja
            Hidden::make('custom_form_id')
                ->default($customForm->id ?? '')
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
                        ->label($data['title'][session()->get('locale', 'hu')] ?? 'N/A')
                        ->placeholder($data['placeholder'][session()->get('locale', 'hu')] ?? '')
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'][session()->get('locale', 'hu')] ?? '')
                        ->helperText($data['helperText'][session()->get('locale', 'hu')] ?? '')
                        ->formatStateUsing(fn($operation, $state) => $operation == 'edit' ? ($responses[$data['id']] ?? '') : $state);
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->mapWithKeys(function ($option) {
                        return [$option['id'] => $option['value'][session()->get('locale', 'hu')] ?? ''];
                    })->toArray();

                    $formComponents[] = Select::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'][session()->get('locale', 'hu')] ?? 'N/A')
                        ->options($options)
                        ->native(false)
                        ->multiple($data['multiple'] ?? false)
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'][session()->get('locale', 'hu')] ?? '')
                        ->helperText($data['helperText'][session()->get('locale', 'hu')] ?? '')
                        ->placeholder($data['placeholder'][session()->get('locale', 'hu')] ?? '')
                        ->formatStateUsing(fn($operation, $state) => $operation == 'edit' ? ($responses[$data['id']] ?? '') : $state);
                    break;

                case 'checkbox':
                    $formComponents[] = Checkbox::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'][session()->get('locale', 'hu')] ?? 'N/A')
                        ->helperText($data['helperText'][session()->get('locale', 'hu')] ?? '')
                        ->formatStateUsing(fn($operation, $state) => $operation == 'edit' ? ($responses[$data['id']] ?? '') : $state);
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = Radio::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'][session()->get('locale', 'hu')] ?? 'N/A')
                        ->options($options)
                        ->required($data['required'] ?? false)
                        ->helperText($data['helperText'][session()->get('locale', 'hu')] ?? '')
                        ->formatStateUsing(fn($operation, $state) => $operation == 'edit' ? ($responses[$data['id']] ?? '') : $state);
                    break;

                case 'textarea':
                    $formComponents[] = Textarea::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'][session()->get('locale', 'hu')] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->required($data['required'] ?? false)
                        ->hint($data['hint'][session()->get('locale', 'hu')] ?? '')
                        ->helperText($data['helperText'][session()->get('locale', 'hu')] ?? '')
                        ->formatStateUsing(fn($operation, $state) => $operation == 'edit' ? ($responses[$data['id']] ?? '') : $state);
                    break;
            }
        }

        return $formComponents;
    }
}
