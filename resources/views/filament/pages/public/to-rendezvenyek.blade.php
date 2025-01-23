@extends('filament.layouts.tok', compact('to_slug'))

@section('title', 'Rendezvények')

@section('content')

    <div class="not-prose max-w-none">

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
                            {{ $rendezveny->event_start_date }}

                        </p>
                        <div class="card-actions">
                            <a class="btn btn-primary"
                                href="{{ route('public.to.rendezveny', ['to_slug' => $to_slug, 'slug' => $rendezveny->slug]) }}">Tovább</a>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>


@endsection
