<?php

namespace App\Filament\Components\Event;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Models\Event\CustomForm;
use App\Models\Event\EventFormResponse;
use App\Models\Scientific\DoctoralSchool;
use App\Models\Scientific\University;
use CodeWithDennis\SimpleAlert\Components\Infolists\SimpleAlert;
use Filament\Infolists\Components\{Fieldset, IconEntry, RepeatableEntry, Section, Tabs, TextEntry, ViewEntry};
use Illuminate\Support\Facades\Auth;

class CustomFormInfolist
{
    public static function schema(?CustomForm $customForm, ?EventFormResponse $response): array
    {
        $content = $customForm->content;

        dd($response);
        $responses = $response->responses ?? [];

        $attribute_name = 'responses';

        $formComponents = [];

        foreach ($content as $field) {
            $data = $field['data'];
            $type = $field['type'] ?? '';
            $fieldId = $data['id'];

            switch ($type) {
                case 'text_input':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A');
                    break;

                case 'select':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A');
                    break;

                case 'checkbox':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A');
                    break;

                case 'radio':
                    $options = collect($data['options'] ?? [])->pluck('value', 'value')->toArray();
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A');
                    break;

                case 'textarea':
                    $formComponents[] = TextEntry::make("{$attribute_name}.{$fieldId}")
                        ->label($data['title'] ?? 'N/A');
                    break;
            }
        }

        return $formComponents;
    }
}
