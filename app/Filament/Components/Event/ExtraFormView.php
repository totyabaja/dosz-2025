<?php

namespace App\Filament\Components\Event;

use App\Models\Event\CustomForm;
use App\Models\Event\Event;
use App\Models\Event\EventFormResponse;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Infolists\Components\{TextEntry};
use Illuminate\Support\Facades\Auth;

class ExtraFormView
{
    public static function schema(CustomForm $customForm, ?EventFormResponse $responses = null): array
    {
        $content = $customForm->content;
        $response = $responses->responses;

        // Betöltjük az eddig mentett válaszokat
        $attribute_name = 'responses';

        $formComponents = [];

        foreach ($content as $field) {
            $data = $field['data'];
            $type = $field['type'] ?? '';
            $fieldId = $data['id'];

            switch ($type) {
                case 'text_input':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->default($response[$data['id']] ?? null);
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->pluck('value', 'id')->toArray();
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->placeholder($data['placeholder'] ?? null)
                        ->default($options[$response[$data['id']]] ?? null);
                    break;

                case 'checkbox':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->default($response[$data['id']] ?? null);
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'id')->toArray();

                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->default($options[$response[$data['id']]] ?? null);
                    break;

                case 'textarea':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->default($response[$data['id']] ?? null);
                    break;
            }
        }

        return $formComponents;
    }
}
