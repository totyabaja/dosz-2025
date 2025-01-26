@extends('filament.layouts.app')

@section('title', __('resource.title.legal_aid'))

@php
    $doctoral_schools = \App\Models\Scientific\DoctoralSchool::orderBy('full_name')->get();
    $universities = \App\Models\Scientific\University::orderBy('full_name')->get();
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



    <!-- Form Modal -->
    <div id="form-modal" class="fixed inset-0 items-center justify-center hidden bg-black bg-opacity-50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
            <h2 class="mb-4 text-xl font-semibold">Jogsegélykérdés Küldése</h2>
            <form id="legal-aid-form" action="#" method="POST">
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium">Vezetéknév</label>
                    <input type="text" id="first_name" name="first_name" class="w-full p-2 border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium">Keresztnév</label>
                    <input type="text" id="last_name" name="last_name" class="w-full p-2 border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium">E-mail</label>
                    <input type="email" id="email" name="email" class="w-full p-2 border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <input type="checkbox" id="confirm_1" name="confirm_1">
                    <label for="confirm_1" class="text-sm">Elfogadom az Adatvédelmi Tájékoztatót</label>
                </div>
                <div class="mb-4">
                    <input type="checkbox" id="confirm_2" name="confirm_2">
                    <label for="confirm_2" class="text-sm">Hozzájárulok az adataim kezeléséhez</label>
                </div>
                <button type="submit"
                    class="w-full px-4 py-2 text-white bg-blue-500 rounded hover:bg-blue-600">Küldés</button>
            </form>
        </div>

    </div>
@endsection
