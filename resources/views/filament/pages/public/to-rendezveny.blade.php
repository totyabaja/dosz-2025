@extends('filament.layouts.tok', compact('to_slug'))

@section('title', $rendezveny->name[session()->get('locale', 'hu')])

@section('content')

    <div class="max-w-none">

        <div class="relative flex flex-col sm:flex-row sm:items-start sm:gap-4">

            @if ($rendezveny->name[session()->get('locale', 'hu')] != '')
                <div class="flex-1 prose">
                    {!! $rendezveny->description[session()->get('locale', 'hu')] !!}
                </div>

                <div class="card sm:max-w-sm sm:order-first sm:mr-4">
                    <figure>
                        <img src="{{ $rendezveny->getFilamentAvatarUrl() }}"
                            alt="{{ $rendezveny->name[session()->get('locale', 'hu')] }}" />
                    </figure>
                    <div class="card-body">
                        <div class="p-3 w-72">
                            <span class="font-bold">Rendezvény ideje:</span><br>
                            {{ $rendezveny->event_start_date?->format('Y. m. d') }}
                            -
                            {{ $rendezveny->event_end_date?->format('Y. m. d.') }}
                        </div>
                        @if ($rendezveny)
                            <div class="p-3">
                                <span class="font-bold">Regisztráció ideje:</span><br>
                                {{ $rendezveny->event_registration_start_datetime?->format('Y.m.d. H:i') }}
                                -
                                {{ $rendezveny->event_registration_end_datetime?->format('Y.m.d. H:i') }}
                            </div>
                        @endif

                        <div class="card-actions">
                            <a class="btn btn-primary"
                                href="{{ route('filament.event.resources.event-registrations.create', $rendezveny->slug) }}">Regisztráció</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-4 alert alert-soft alert-warning" role="alert">
                    <span class="icon-[tabler--alert-triangle] size-6"></span>
                    <p><span class="text-lg font-semibold">Warning alert:</span> A tartalom nem létezik ezen a nyelven.
                    </p>
                </div>
            @endif

        </div>

        <div class="flex-1 prose">
            <h3>{{ __('site.fajlok') }}</h3>
            <ul>
                @foreach ($rendezveny->getEventDocuments() as $doc)
                    <li>
                        <a href="{{ $doc['url'] }}">{{ $doc['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
@endsection
