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

        <div class="grid gap-4 mt-4 sm:grid-cols-2 md:grid-cols-4 xl:grid-cols-4">
            @forelse ($tos as $to)
                <a href="{{ route('public.to', ['to_slug' => $to->slug]) }}"
                    class="flex items-center justify-center hover:animate-pulse" aria-label="Icon Button">
                    <img src="{{ $to->getFilamentAvatarUrl() }}" alt="{{ $to->filament_name }}" />
                </a>
            @empty
                <p class="text-center col-span-full">Nincs találat</p>
            @endforelse
        </div>
    </div>
</div>
