@extends('filament.layouts.app')

@section('title', __('resource.title.legal_aid'))

@php
    $doctoral_schools = \App\Models\Scientific\DoctoralSchool::active()->get()->sortBy('filament_full_name');
    $universities = \App\Models\Scientific\University::active()->get()->sortBy('filament_full_name');
@endphp

@section('content')
    <div class="prose max-w-none">

        <!-- Gombok -->
        <div class="flex justify-center mb-4 space-x-4">
            <button type="button" class=" btn btn-primary" aria-haspopup="dialog" aria-expanded="false"
                aria-controls="jogsegely-page-1" data-overlay="#jogsegely-page-1">Jogsegélykérdés
                küldése</button>

            <a class="btn btn-primary" href="{{ route('public.gyik') }}">Gyakran ismételt kérdések</a>

            <a class="btn btn-primary" href="{{ route('public.alt_ker') }}">Általános kérdések a doktori
                képzésről</a>

        </div>

        <!-- Vonal -->
        <hr class="mb-6 border-gray-400">

        <!-- Leírás -->
        <div>
            <p class="mb-4">
                A Doktoranduszok Országos Szövetsége (DOSZ) immáron évek óta Jogsegélyszolgálatot üzemeltet, amelynek
                keretében a magyarországi felsőoktatási intézményben doktori képzéshez kapcsolódó jogi problémákra
                kaphatnak választ a leendő és már hallgatói jogviszonnyal rendelkező doktoranduszok, valamint
                doktorjelöltek.
            </p>
            <p>
                A Jogsegélyszolgáltatot jelen pillanatban egy pécsi ügyvédi iroda üzemelteti, amely nagy tapasztalattal
                rendelkezik a felsőoktatási jog, különösen pedig a doktori képzés területén.
            </p>
        </div>
    </div>

    <!-- Jogsegély Modal START -->
    <div id="jogsegely-page-1" class="hidden bg-black bg-opacity-50 overlay modal overlay-open:opacity-100 modal-middle"
        role="dialog" tabindex="-1">
        <div class="modal-dialog overlay-open:opacity-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Jogsegélykérdés küldése</h3>
                    <button type="button" class="absolute btn btn-text btn-circle btn-sm end-3 top-3" aria-label="Close"
                        data-overlay="#jogsegely-page-1">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
                <div class="modal-body">
                    A gyakran ismétlődő kérdésekből GYIK-et állítottunk össze. Érdemes a kérdésed előtt áttekintened.
                </div>
                <div class="justify-center modal-footer">
                    <button type="button" class="btn btn-secondary" aria-haspopup="dialog" aria-expanded="false"
                        aria-controls="jogsegely-page-2" data-overlay="#jogsegely-page-2">Igen, elolvastam már.</button>
                    <a type="button" class="btn btn-primary" href="{{ route('public.gyik') }}">Átfutom.</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Jogsegély Modal END -->

    <!-- Jogsegély Modal START -->
    <div id="jogsegely-page-2" class="hidden bg-black bg-opacity-50 overlay modal overlay-open:opacity-100 modal-middle"
        role="dialog" tabindex="-1">
        <div class="modal-dialog modal-dialog-lg overlay-open:opacity-100">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Jogsegélykérdés küldése</h3>
                    <button type="button" class="absolute btn btn-text btn-circle btn-sm end-3 top-3" aria-label="Close"
                        data-overlay="#jogsegely-page-2">
                        <span class="icon-[tabler--x] size-4"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <livewire:legal-aid-form />
                </div>
            </div>
        </div>
    </div>
    <!-- Jogsegély Modal END -->


@endsection
