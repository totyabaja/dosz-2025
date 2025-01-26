<div>
    @if (session()->has('message'))
        <div class="mb-4 alert alert-soft alert-success" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <form wire:submit.prevent="submit">
        <div class="space-y-3">
            <!-- Név mező -->
            <div class="flex items-center space-x-3">
                <label class="input-group-text" for="jog_lastname">Név (opcionális)</label>
                <input type="text" wire:model.live.debounce.500ms="last_name" class="input grow"
                    placeholder="Vezetéknév" id="jog_lastname" />
                <input type="text" wire:model.live.debounce.500ms="first_name" class="input grow"
                    placeholder="Keresztnév" id="jog_firstname" />
            </div>
            @error('last_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
            @error('first_name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- E-mail mező -->
            <div class="flex items-center space-x-3">
                <label class="input-group-text" for="jog_email">E-mail (opcionális)</label>
                <input type="email" wire:model.live.debounce.500ms="email" class="input grow"
                    placeholder="cim@gmail.com" id="jog_email" />
            </div>
            @error('email')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- Egyetem választó -->
            <div class="flex items-center space-x-3">
                <label for="jog_university" class="input-group-text">Egyetem</label>
                <select wire:model.change="university_id" class="select grow" id="jog_university">
                    <option value="">Válassz</option>
                    @foreach ($universities as $university)
                        <option value="{{ $university->id }}">{{ $university->filament_full_name }}</option>
                    @endforeach
                </select>
            </div>
            @error('university_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- Doktori iskola választó -->
            <div class="flex items-center space-x-3">
                <label for="jog_doctoral_school" class="input-group-text">Doktori Iskola</label>
                <select wire:model.change="doctoral_school_id" class="select grow" id="jog_doctoral_school">
                    <option value="">Válassz</option>
                    @foreach ($doctoral_schools as $doctoral_school)
                        <option value="{{ $doctoral_school->id }}">{{ $doctoral_school->filament_full_name }}</option>
                    @endforeach
                </select>
            </div>
            @error('doctoral_school_id')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- Szövegmező -->
            <div class="flex flex-col">
                <label for="jog_question" class="input-group-text">Kérdés</label>
                <textarea wire:model.live.debounce.500ms="question" class="w-full textarea" id="jog_question" rows="4"
                    placeholder="Írd ide a kérdésed..."></textarea>
            </div>
            @error('question')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- Checkboxok -->
            <div class="flex items-start space-x-3">
                <input type="checkbox" wire:model.change="confirm_1" class="checkbox" id="jog_confirm_1">
                <label for="jog_confirm_1" class="text-sm">Elfogadom az Adatvédelmi Tájékoztatót.</label>
            </div>
            @error('confirm_1')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <div class="flex items-start space-x-3">
                <input type="checkbox" wire:model.change="confirm_2" class="checkbox" id="jog_confirm_2">
                <label for="jog_confirm_2" class="text-sm">Hozzájárulok az adataim kezeléséhez.</label>
            </div>
            @error('confirm_2')
                <span class="text-red-500">{{ $message }}</span>
            @enderror

            <!-- Küldés gomb -->
            <div class="mt-4">
                <button type="submit" class="w-full btn btn-primary">Mentés</button>
            </div>
        </div>
    </form>
</div>
