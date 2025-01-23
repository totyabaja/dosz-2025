@extends('filament.layouts.app')

@section('title', $page->name[session()->get('locale', 'hu')])

@section('content')

    <div class="prose max-w-none">

        @if ($page->name[session()->get('locale', 'hu')] != '')
            @if ($page->livewire_component_top != '')
                @livewire('public-pages-extra.' . \Illuminate\Support\Str::kebab(Str::afterLast($page->livewire_component_top, '\\')))
            @endif
            {!! $page->description[session()->get('locale', 'hu')] !!}

            @if ($page->livewire_component_bottom != '')
                @livewire('public-pages-extra.' . \Illuminate\Support\Str::kebab(Str::afterLast($page->livewire_component_bottom, '\\')))
            @endif
        @else
            <div class="flex items-center gap-4 alert alert-soft alert-warning" role="alert">
                <span class="icon-[tabler--alert-triangle] size-6"></span>
                <p><span class="text-lg font-semibold">Warning alert:</span> A tartalom nem létezik ezen a nyelven.
                </p>
            </div>
        @endif

    </div>



@endsection
