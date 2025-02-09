<div class="not-prose">
    <div class="max-w-none">
        <div class="flex justify-end mb-3">
            <div class="relative sm:w-full md:w-[200px]">
                Keresés
                <input type="text" placeholder="Keresés..."
                    class="w-full p-2 border rounded-md input input-floating peer"
                    wire:model.live.debounce.500ms='search' />
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mt-4 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4">
            @forelse ($universities as $university)
                <div class="overflow-hidden bg-white rounded-lg shadow-md sm:max-w-sm">
                    <figure class="w-full">
                        <img src="{{ $university->getFilamentAvatarUrl() }}" alt="{{ $university->filament_full_name }}"
                            class="object-cover w-full h-40" />
                    </figure>
                    <div class="p-4 text-center">
                        <h5 class="text-lg font-semibold">{{ $university->filament_full_name }}</h5>
                    </div>
                </div>
            @empty
                <p class="text-center col-span-full">Nincs találat</p>
            @endforelse
        </div>
    </div>
</div>
