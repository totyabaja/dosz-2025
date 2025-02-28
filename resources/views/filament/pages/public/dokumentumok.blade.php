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


            @foreach ($medium as $media)
                @if ($media->type == 'folder')
                    <div class="card">
                        <figure>
                            <x-filament::icon icon="fas-folder" class="w-20 mt-3 text-black" />
                        </figure>
                        <div class="card-body">
                            <h5 class="card-title mb-2.5 justify-center">{{ $media->name }}</h5>
                            <div class="justify-center card-actions">
                                <a class="btn btn-primary"
                                    href="{{ route('public.dokumentumok', ['folder' => $media->collection]) }}">Megtekint</a>
                            </div>
                        </div>
                    </div>
                @else
                    @php
                        $media = TotyaDev\TotyaDevMediaManager\Models\Media::find($media->id);
                    @endphp
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
                        <div class="flex flex-col items-center text-center card-body">
                            <h5 class="card-title mb-2.5">
                                {{ $media->custom_properties['title'][session()->get('locale', 'hu')] ?? 'N/A' }}</h5>
                            <div class="card-actions">
                                <a download class="btn btn-primary" href="{{ $media->getFullUrl() }}">Letöltés</a>
                            </div>
                        </div>

                    </div>
                @endif
            @endforeach

        </div>
    </div>


@endsection
