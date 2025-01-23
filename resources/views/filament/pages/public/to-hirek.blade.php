@extends('filament.layouts.tok', compact('to_slug'))

@section('title', 'Hírek')

@section('content')

    <div class="not-prose max-w-none">

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
                            <a class="btn btn-primary"
                                href="{{ route('public.to.hir', ['to_slug' => $to_slug, 'slug' => $hir->slug]) }}">Tovább</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>


@endsection
