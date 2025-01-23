@extends('filament.layouts.app')

@section('title', app(\App\Settings\GeneralSettings::class)->brand_name)

@section('content')

    <div id="carouselExampleCaptions" class="relative" data-twe-carousel-init data-twe-carousel-slide>
        <div class="absolute bottom-0 left-0 right-0 z-[2] mx-[15%] mb-4 flex list-none justify-center p-0"
            data-twe-carousel-indicators>
            <button type="button" data-twe-target="#carouselExampleCaptions" data-twe-slide-to="0" data-twe-carousel-active
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-twe-target="#carouselExampleCaptions" data-twe-slide-to="1"
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                aria-label="Slide 2"></button>
            <button type="button" data-twe-target="#carouselExampleCaptions" data-twe-slide-to="2"
                class="mx-[3px] box-content h-[3px] w-[30px] flex-initial cursor-pointer border-0 border-y-[10px] border-solid border-transparent bg-white bg-clip-padding p-0 -indent-[999px] opacity-50 transition-opacity duration-[600ms] ease-[cubic-bezier(0.25,0.1,0.25,1.0)] motion-reduce:transition-none"
                aria-label="Slide 3"></button>
        </div>
        <div class="relative w-full overflow-hidden after:clear-both after:block after:content-['']">
            @foreach ($slides as $index => $slide)
                <div class="relative float-left -mr-[100%] w-full transition-transform duration-[600ms] ease-in-out motion-reduce:transition-none"
                    data-twe-carousel-item style="backface-visibility: hidden"
                    @if ($index === 0) data-twe-carousel-active @endif>
                    <img src="{{ $slide->getFilamentAvatarUrl() }}" class="block mx-auto w-full h-[500px] object-cover"
                        alt="Slide {{ $index + 1 }}" />
                    <div class="absolute inset-x-[15%] bottom-5 p-5 text-center text-white bg-black/70 rounded-lg">
                        <h5 class="mb-3 text-xl font-bold">{{ $slide->name[session()->get('locale', 'hu')] }}</h5>
                        <p class="hidden mb-3 md:block">
                            {{ $slide->short_description[session()->get('locale', 'hu')] }}
                        </p>
                        <p>
                            <a href="{{ route('public.hir', $slide->slug) }}" tag="Hír képe" class="btn btn-primary">
                                Elolvasom a hírt
                            </a>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <button
            class="absolute bottom-0 left-0 top-0 z-[1] flex w-[15%] items-center justify-center border-0 bg-none p-0 text-center text-white opacity-50 transition-opacity duration-150 ease-[cubic-bezier(0.25,0.1,0.25,1.0)] hover:text-white hover:no-underline hover:opacity-90 hover:outline-none focus:text-white focus:no-underline focus:opacity-90 focus:outline-none motion-reduce:transition-none"
            type="button" data-twe-target="#carouselExampleCaptions" data-twe-slide="prev">
            <span class="inline-block w-12 h-12">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#fff"
                    class="w-full h-full">
                    <path
                        d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"
                        stroke="#fff" stroke-width="2" />
                </svg>
            </span>
            <span
                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Previous</span>
        </button>

        <button
            class="absolute bottom-0 right-0 top-0 z-[1] flex w-[15%] items-center justify-center border-0 bg-none p-0 text-center text-white opacity-50 transition-opacity duration-150 ease-[cubic-bezier(0.25,0.1,0.25,1.0)] hover:text-white hover:no-underline hover:opacity-90 hover:outline-none focus:text-white focus:no-underline focus:opacity-90 focus:outline-none motion-reduce:transition-none"
            type="button" data-twe-target="#carouselExampleCaptions" data-twe-slide="next">
            <span class="inline-block w-12 h-12">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="#fff"
                    class="w-full h-full">
                    <path
                        d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"
                        stroke="#fff" stroke-width="2" />
                </svg>
            </span>
            <span
                class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Next</span>
        </button>

    </div>


    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const carousel = document.querySelector("#carouselExampleCaptions");
            const slides = carousel.querySelectorAll("[data-twe-carousel-item]");
            const nextButton = carousel.querySelector("[data-twe-slide='next']");
            const prevButton = carousel.querySelector("[data-twe-slide='prev']");
            let currentSlide = 0;

            // Funkció a diák megjelenítéséhez
            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.add("hidden"); // Minden diát elrejtünk
                    slide.classList.remove("block");
                    if (i === index) {
                        slide.classList.add("block"); // Csak az aktuális diát jelenítjük meg
                        slide.classList.remove("hidden");
                    }
                });
            }

            // Következő gomb eseménykezelő
            nextButton.addEventListener("click", () => {
                currentSlide = (currentSlide + 1) % slides.length; // Ugrás a következő diára
                showSlide(currentSlide);
            });

            // Előző gomb eseménykezelő
            prevButton.addEventListener("click", () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length; // Ugrás az előző diára
                showSlide(currentSlide);
            });

            // Alapértelmezett állapot beállítása (első dia megjelenítése)
            showSlide(currentSlide);
        });
    </script>
@endsection
