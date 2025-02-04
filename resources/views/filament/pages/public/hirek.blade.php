@extends('filament.layouts.app')

@section('title', 'Hírek')

@section('content')

    <div class="not-prose max-w-none">

        <div class="max-w-none">
            <div class="flex justify-end">
                <div class="relative sm:w-full md:w-[200px]">
                    <input type="text" placeholder="Keresés..."
                        class="w-full p-2 border rounded-md input input-floating peer"
                        wire:model.live.debounce.500ms='search' />
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 xl:grid-cols-4 md:grid-cols-3">
                @foreach ($hirek as $hir)
                    <div class="card">
                        <figure>
                            <img src="{{ $hir->getFilamentAvatarUrl() }}"
                                alt="{{ $hir->name[session()->get('locale', 'hu')] }}" />
                        </figure>
                        <div class="card-body">
                            <h5 class="card-title mb-2.5">{{ $hir->name[session()->get('locale', 'hu')] }}</h5>
                            <p class="mb-4">
                                {{ $hir->short_description[session()->get('locale', 'hu')] }}

                            </p>
                            <div class="card-actions">
                                <a class="btn btn-primary" href="{{ route('public.hir', $hir->slug) }}">Tovább</a>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div>
                    {!! $hirek->links() !!}
                </div>

            </div>
        </div>


    @endsection
