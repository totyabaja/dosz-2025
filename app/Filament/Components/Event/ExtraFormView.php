<?php

namespace App\Filament\Components\Event;

use App\Models\Event\CustomForm;
use App\Models\Event\Event;
use App\Models\Event\EventFormResponse;
use App\Models\Event\EventRegistration;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use

Filament\Infolists\Components\{TextEntry};
use Illuminate\Support\Facades\Auth;

class ExtraFormView
{
    public static function schema(CustomForm $customForm, ?array $responses = []): array
    {
        $content = $customForm->content;

        $formComponents = [];

        foreach ($content as $field) {
            $data = $field['data'];
            $type = $field['type'] ?? '';
            $fieldId = $data['id'];

            switch ($type) {
                case 'text_input':
                    $formComponents[] = TextEntry::make("{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->default($responses[$data['id']] ?? null);
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->pluck('value', 'id')->toArray();
                    $formComponents[] = TextEntry::make("{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->placeholder($data['placeholder'] ?? null)
                        ->default(isset($responses[$data['id']]) ? ($options[$responses[$data['id']]] ?? null) : null);

                    break;

                case 'checkbox':
                    $formComponents[] = TextEntry::make("{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses[$data['id']] ?? null);
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'id')->toArray();

                    $formComponents[] = TextEntry::make("{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->helperText($data['helperText'] ?? null)
                        ->default($options[$responses[$data['id']]] ?? null);
                    break;

                case 'textarea':
                    $formComponents[] = TextEntry::make("{$fieldId}")
                        ->label($data['title'] ?? 'N/A')
                        ->placeholder($data['placeholder'] ?? '')
                        ->hint($data['hint'] ?? null)
                        ->helperText($data['helperText'] ?? null)
                        ->default($responses[$data['id']] ?? null);
                    break;
            }
        }

        return $formComponents;
    }
}
