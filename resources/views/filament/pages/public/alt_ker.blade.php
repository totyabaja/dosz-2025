@extends('filament.layouts.app')

@section('title', __('title.alt_ker'))

@section('content')
    <div class="prose max-w-none">

        <div class="divide-y accordion divide-neutral/20">
            @forelse ($faqs as $faq)
                <div class="accordion-item" id="delivery-icon">
                    <button class="inline-flex items-center justify-between accordion-toggle text-start"
                        aria-controls="delivery-icon-collapse" aria-expanded="false">
                        <span class="inline-flex items-center gap-x-4">
                            <span class="icon-[tabler--shopping-bag] text-base-content size-6"></span>
                            {{ $faq->question }}
                        </span>
                        <span
                            class="icon-[tabler--chevron-left] accordion-item-active:-rotate-90 text-base- size-4.5 shrink-0 transition-transform duration-300 rtl:-rotate-180"></span>
                    </button>
                    <div id="delivery-icon-collapse"
                        class="accordion-content hidden w-full overflow-hidden transition-[height] duration-300"
                        aria-labelledby="delivery-icon" role="region">
                        <div class="px-5 pb-4">
                            <p class="font-normal text-base-content/80">
                                {!! $faq->answer !!}
                            </p>
                        </div>
                    </div>
                </div>
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
