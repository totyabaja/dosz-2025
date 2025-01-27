<x-filament-widgets::widget>
    <x-filament::section icon="fas-building-user">
        <x-slot name="heading">
            Munkamenet
        </x-slot>
        {{ auth()->user()->currentDepartment()?->filament_name ?? '' }}
    </x-filament::section>
</x-filament-widgets::widget>
