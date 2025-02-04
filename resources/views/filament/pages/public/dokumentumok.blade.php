@extends('filament.layouts.app')

@section('title', __('resource.title.documents'))

@section('content')

    <div class="not-prose max-w-none">

        <div class="grid grid-cols-2 gap-4 xl:grid-cols-4 md:grid-cols-3">
            @if ($main_folder->parent)
                <div class="card">
                    <figure>
                        <x-filament::icon icon="fas-arrow-turn-up" class="w-20 mt-3 text-black" />
                    </figure>
                    <div class="card-body">
                        <div class="justify-center card-actions">
                            <a class="btn btn-primary"
                                href="{{ route('public.dokumentumok', ['folder' => $main_folder->parent->collection]) }}">Vissza</a>
                        </div>
                    </div>
                </div>
            @endif


            @foreach ($main_folder->folders as $folder)
                <div class="card">
                    <figure>
                        <x-filament::icon icon="fas-folder" class="w-20 mt-3 text-black" />
                    </figure>
                    <div class="card-body">
                        <h5 class="card-title mb-2.5">{{ $folder->name }}</h5>
                        <p class="mb-4">
                            {{ $folder->created_at }}

                        </p>
                        <div class="justify-center card-actions">
                            <a class="btn btn-primary"
                                href="{{ route('public.dokumentumok', ['folder' => $folder->collection]) }}">Megtekint</a>
                        </div>
                    </div>
                </div>
            @endforeach


            @foreach ($medium as $media)
                <div class="card">
                    <figure>
                        @switch($media->mime_type)
                            @case('image/jpeg')
                            @case('image/webp')

                            @case('image/png')
                                <img src="{{ $media->getFullUrl() }}" />
                            @break

                            @case('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                                <x-filament::icon icon="fas-file-excel" class="w-20 mt-3 text-black" />
                            @break

                            @default
                                <x-filament::icon icon="fas-file" class="w-20 mt-3 text-black" />
                        @endswitch
                    </figure>
                    <div class="card-body">
                        <h5 class="card-title mb-2.5">{{ $media->custom_properties['label-hu'] }}</h5>
                        <p class="mb-4">
                            {{ $media->created_at }}

                        </p>
                        <div class="justify-center card-actions">
                            <a download class="btn btn-primary" href="{{ $media->getFullUrl() }}">Letöltés</a>
                        </div>
                    </div>
                </div>
            @endforeach


        </div>
    </div>


@endsection
