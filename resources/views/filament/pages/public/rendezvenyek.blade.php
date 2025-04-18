@extends('filament.layouts.app')

@section('title', 'Rendezvények')

@section('content')

    <div class="not-prose max-w-none">

        <div class="flex justify-end mb-3">
            <div class="relative sm:w-full md:w-[200px] flex items-center gap-2">
                <label class="whitespace-nowrap">Keresés</label>
                <input type="text" placeholder="Keresés..." class="w-full p-2 border rounded-md input input-floating peer"
                    wire:model.live.debounce.500ms='search' />
            </div>
        </div>


        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4 md:grid-cols-3">
            @foreach ($rendezvenyek as $rendezveny)
                <div class="card sm:max-w-sm">
                    <figure>
                        <img src="{{ $rendezveny->getFilamentAvatarUrl() }}"
                            alt="{{ $rendezveny->name[session()->get('locale', 'hu')] }}" />
                    </figure>
                    <div class="card-body">
                        <h5 class="card-title mb-2.5">{{ $rendezveny->name[session()->get('locale', 'hu')] }}</h5>
                        <p class="mb-4">
                            {{ $rendezveny->event_start_date ?? '' }}

                        </p>
                        <div class="card-actions">
                            <a class="btn btn-primary" href="{{ route('public.rendezveny', $rendezveny->slug) }}">Tovább</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex justify-center mt-4">
            <div class="p-2">
                {{ $rendezvenyek->onEachSide(5)->links() }}
            </div>
        </div>

    </div>


@endsection
