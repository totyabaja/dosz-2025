@extends('filament.layouts.app')

@section('title', $hir->name[session()->get('locale', 'hu')])

@section('content')

    <div class="prose max-w-none">

        @if ($hir->name[session()->get('locale', 'hu')] != '')
            {!! $hir->description[session()->get('locale', 'hu')] !!}
        @else
            <div class="flex items-center gap-4 alert alert-soft alert-warning" role="alert">
                <span class="icon-[tabler--alert-triangle] size-6"></span>
                <p><span class="text-lg font-semibold">Warning alert:</span> A tartalom nem létezik ezen a nyelven.
                </p>
            </div>
        @endif

    </div>


@endsection
