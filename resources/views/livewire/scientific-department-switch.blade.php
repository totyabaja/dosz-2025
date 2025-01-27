<div>
    <x-filament::input.wrapper>
        <x-filament::input.select wire:model.change="selected_department">
            @foreach ($departments as $department)
                <option value="{{ $department->id }}">{{ $department->filament_name }}</option>
            @endforeach
        </x-filament::input.select>
    </x-filament::input.wrapper>
</div>
