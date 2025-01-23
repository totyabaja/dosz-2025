@extends('filament.layouts.app')

@section('title', __('title.tok'))

@section('content')
    <div class="prose max-w-none">

        <div class="grid grid-cols-2 gap-4 lg:grid-cols-5 md:grid-cols-4">
            @forelse ($tok as $to)
                <a href="{{ route('public.to', ['to_slug' => $to->slug]) }}"
                    class="flex items-center justify-center hover:animate-pulse" aria-label="Icon Button">
                    <img src="{{ $to->getFilamentAvatarUrl() }}" alt="{{ $to->name }}" />
                </a>

            @empty
                <div class="flex items-center gap-4 alert alert-soft alert-info" role="alert">
                    <span class="icon-[tabler--alert-triangle] size-6"></span>
                    <p><span class="text-lg font-semibold">Warning alert:</span> Nincs még feltöltve GYIK.
                    </p>
                </div>
            @endforelse
        </div>

    </div>
@endsection
