@php
    $settings = app(\App\Settings\GeneralSettings::class);

    $menuItems = \App\Models\Menu\PublicMenu::getTree('dosz-header-menu');
    //dd($menuItems);

    $userMenuItems = [
        (object) ['name' => 'Profilom', 'route' => 'filament.event.pages.my-profile'],
        //(object) ['name' => 'Rendezvényeim', 'route' => 'filament.admin.pages.my-events'],
        //(object) ['name' => 'Rendezvényeim', 'route' => 'filament.event.pages.dashboard'],
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ session()->get('locale', 'hu') }}" dir="ltr" class="min-h-screen fi" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? $settings->brand_name }}</title>
    <link rel="icon" href="{{ Storage::url($settings->site_favicon) }}">

    @vite('resources/css/app.css')
    @livewireStyles
</head>

<body class="min-h-screen antialiased font-normal fi-body fi-panel-public bg-gray-50 text-gray-950">
    <div class="flex flex-row-reverse w-full min-h-screen fi-layout overflow-x-clip">
        <div class="flex flex-col flex-1 w-screen mx-auto fi-main-ctn max-w-7xl">
            <!-- Felső sáv START-->
            <div class="top-0 z-50 hidden py-2 text-sm bg-gray-100 md:block">
                <div class="flex items-center justify-between px-4">
                    <span class="flex items-center space-x-6 text-gray-600">
                        <div class="flex items-center space-x-2">
                            Telefon:
                            <br>
                            +36 (30) 518 7958
                        </div>
                        <!-- Függőleges elválasztó -->
                        <div class="h-6 border-l border-gray-300"></div>
                        <div class="flex items-center space-x-2">
                            E-mail:
                            <br>
                            iroda@dosz.hu
                        </div>
                    </span>
                    <span class="flex items-center space-x-6 text-gray-600">


                        <div class="flex items-center space-x-2">
                            <a href="https://doktori.hu">DOKTORI.HU</a>
                        </div>
                        <!-- Függőleges elválasztó -->
                        <div class="h-6 border-l border-gray-300"></div>
                        <div class="flex items-center space-x-2">
                            <a href="https://m2.mtmt.hu">MTMT.HU</a>
                        </div>
                    </span>
                    {{-- dd(trans('resource.title.organization')) --}}
                    <span class="flex items-end space-x-6 text-gray-600">
                        <livewire:language-switch />
                    </span>
                </div>
            </div>
            <!-- Felső sáv END -->

            <!-- Menü sáv START-->
            <div x-data="{ open: false }" class="sticky top-0 z-40 bg-white">
                <!-- Navigáció -->
                <nav class="flex items-center justify-between w-full gap-2 shadow navbar rounded-box">
                    <!-- Bal oldali logó -->
                    <div class="navbar-start max-md:w-1/4">
                        <a class="text-xl font-semibold no-underline link text-base-content link-neutral"
                            href="/">
                            <img alt="{{ $settings->brand_name }} logo" src="{{ Storage::url($settings->brand_logo) }}"
                                style="height: 6rem;" class="flex fi-logo">
                        </a>
                    </div>

                    <!-- Középső menü: Csak nagyobb képernyőkön látható -->
                    <div class="hidden navbar-center md:flex">
                        <ul class="p-0 font-medium menu menu-horizontal">
                            @foreach ($menuItems as $menuItem)
                                @if (count($menuItem->subs))
                                    <li
                                        class="dropdown relative inline-flex [--auto-close:inside] [--offset:9] [--placement:bottom-end]">
                                        <button id="dropdown-nav" type="button"
                                            class="dropdown-toggle dropdown-open:bg-base-content/10 dropdown-open:text-base-content"
                                            aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">
                                            {{ $menuItem->name }}
                                            <span
                                                class="icon-[tabler--chevron-down] dropdown-open:rotate-180 size-4"></span>
                                        </button>
                                        <ul class="hidden dropdown-menu dropdown-open:opacity-100" role="menu"
                                            aria-orientation="vertical" aria-labelledby="dropdown-nav">
                                            @foreach ($menuItem->subs as $subMenuItem)
                                                <li><a class="dropdown-item"
                                                        href="{{ route($subMenuItem->route, $subMenuItem->params) }}">{{ $subMenuItem->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    <li><a
                                            href="{{ route($menuItem->route, $menuItem->params) }}">{{ $menuItem->name }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>


                    <div class="items-center hidden gap-4 navbar-end md:flex">

                        @guest
                            <a href="{{ route('filament.event.auth.login') }}"
                                class="block p-2 mt-6 text-center text-white rounded bg-primary">{{ __('menu.login') }}</a>
                        @endguest
                    </div>

                    @auth
                        <div
                            class=" hidden dropdown relative md:inline-flex [--auto-close:inside] [--offset:8] [--placement:bottom-end]">
                            <button id="dropdown-scrollable" type="button" class="flex items-center dropdown-toggle"
                                aria-haspopup="menu" aria-expanded="false" aria-label="Dropdown">

                                <div class="avatar placeholder">
                                    <div class="size-9.5 rounded-full bg-neutral text-neutral-content">

                                        <img src="{{ Auth::user()->getFilamentAvatarUrl() }}"
                                            alt="{{ Auth::user()->name }} logója">
                                    </div>
                                </div>
                            </button>
                            <ul class="hidden dropdown-menu dropdown-open:opacity-100 min-w-60" role="menu"
                                aria-orientation="vertical" aria-labelledby="dropdown-avatar">
                                <li class="gap-2 dropdown-header">
                                    <div class="avatar placeholder">
                                        <div class="w-10 rounded-full bg-neutral text-neutral-content">
                                            <img src="{{ Auth::user()->getFilamentAvatarUrl() }}"
                                                alt="{{ Auth::user()->name }} logója">
                                            <!--
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <span class="icon-[tabler--user] size-4"></span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        //-->
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="text-base font-semibold text-base-content">{{ Auth::user()->name }}
                                        </h6>
                                        <small
                                            class="text-base-content/50">{{ Auth::user()->roles?->pluck('name')->join(', ') }}</small>
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('filament.event.pages.my-profile') }}">
                                        <span class="icon-[tabler--user]"></span>
                                        Profilom
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="/event">
                                        <span class="icon-[tabler--settings]"></span>
                                        Rendezvényeim
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('filament.admin.pages.dashboard') }}">
                                        <span class="icon-[tabler--receipt-rupee]"></span>
                                        Admin
                                    </a>
                                </li>
                                <li class="gap-2 dropdown-footer">
                                    <button class="btn btn-error btn-soft btn-block" form="logoutForm">
                                        <span class="icon-[tabler--logout]"></span>
                                        {{ __('menu.logout') }}
                                        </buttom>
                                        <form id="logoutForm" method="POST"
                                            action="{{ route('filament.event.auth.logout') }}" hidden>
                                            @csrf
                                            @method('POST')
                                        </form>
                                </li>
                            </ul>
                        </div>

                    @endauth


                    <!-- Mobilos menü gomb -->
                    <div class="navbar-end md:hidden">
                        <button @click="open = !open" class="btn btn-square btn-secondary">
                            <span x-show="!open" class="icon-[tabler--menu-2] size-5"></span>
                            <span x-show="open" class="icon-[tabler--x] size-5"></span>
                        </button>
                    </div>
                </nav>

                <!-- Oldalsáv (Sidebar) -->
                <div x-show="open" class="fixed inset-0 z-50 flex bg-gray-800 bg-opacity-75 md:hidden">
                    <!-- Oldalsáv tartalom -->
                    <aside @click.away="open = false" class="flex flex-col w-64 h-full p-6 bg-white shadow-lg">
                        <h2 class="mb-4 text-xl font-semibold">Menü</h2>
                        <div class="px-2 pt-4 drawer-body">
                            <ul
                                class="menu space-y-0.5 [&_.nested-collapse-wrapper]:space-y-0.5 [&_ul]:space-y-0.5 p-0">
                                @foreach ($menuItems as $menuItem)
                                    @if (count($menuItem->subs))
                                        <li class="space-y-0.5">
                                            <a class="collapse-toggle collapse-open:bg-base-content/10" id="menu-app"
                                                data-collapse="#menu-app-collapse">
                                                <span class="icon-[tabler--apps] size-5"></span>
                                                {{ $menuItem->name }}
                                                <span
                                                    class="icon-[tabler--chevron-down] collapse-open:rotate-180 size-4 transition-all duration-300"></span>
                                            </a>
                                            <ul id="menu-app-collapse"
                                                class="collapse hidden w-auto overflow-hidden transition-[height] duration-300"
                                                aria-labelledby="menu-app">
                                                @foreach ($menuItem->subs as $subMenuItem)
                                                    <li>
                                                        <a
                                                            href="{{ route($subMenuItem->route, $subMenuItem->params) }}">
                                                            <span class="icon-[tabler--message] size-5"></span>
                                                            {{ $subMenuItem->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ route($menuItem->route, $subMenuItem->params) }}">
                                                <span class="icon-[tabler--home] size-5"></span>
                                                {{ $menuItem->name }}
                                            </a>
                                        </li>
                                    @endif
                                @endforeach

                                <div class="py-6 divider text-base-content/50 after:border-0">Fiókom</div>
                                @guest
                                    <li>
                                        <a href="#">
                                            <span class="icon-[tabler--login] size-5"></span>
                                            Bejelentkezés
                                        </a>
                                    </li>
                                @endguest
                                @auth
                                    @foreach ($userMenuItems as $userMenuItem)
                                        <li>
                                            <a href="{{ route($userMenuItem->route) }}">
                                                <span class="icon-[tabler--logout-2] size-5"></span>
                                                {{ $userMenuItem->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                    <li>
                                        <a href="{{ route('filament.admin.pages.dashboard') }}">
                                            <span class="icon-[tabler--logout-2] size-5"></span>
                                            Admin
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('filament.event.auth.logout') }}">
                                            <span class="icon-[tabler--logout-2] size-5"></span>
                                            Kijelentkezés
                                        </a>
                                    </li>
                                @endauth
                            </ul>
                        </div>

                    </aside>
                    <!-- Üres terület a kattintás lezárására -->
                    <div @click="open = false" class="flex-1"></div>
                </div>
            </div>
            <!-- Menü sáv END-->

            <!-- Tartalom START-->
            <main class="w-full h-full px-4 mx-auto fi-main md:px-6 lg:px-8 max-w-7xl">
                <section class="class">
                    <header class="items-center gap-4 fi-header">
                        <h1
                            class="mb-10 text-2xl font-bold tracking-tight text-left mt-9 fi-header-heading text-gray-950 sm:text-center sm:text-3xl">
                            @yield('title')
                        </h1>
                    </header>

                    <div class="my-6">
                        @yield('content')
                    </div>
                </section>
            </main>
            <!-- Tartalom END-->



            <footer class="p-10 footer bg-base-200/60">
                <aside class="gap-6">
                    <div class="flex items-center gap-2 text-xl font-bold">
                        <img alt="{{ $settings->brand_name }} logo" src="{{ Storage::url($settings->brand_logo) }}"
                            style="height: 3rem;" class="flex fi-logo">
                        <span>{{ $settings->brand_name }}</span>
                    </div>
                    <p class="text-base-content">Közösség a tudományért
                    </p>
                </aside>

                <nav class="text-base-content">
                    <h6 class="footer-title">Rólunk</h6>
                    <a href="#" class="link link-hover">Impresszum</a>
                    <a href="#" class="link link-hover">Bemutatkozás</a>
                </nav>

                @php
                    $menuItems = \App\Models\Menu\PublicMenu::getTree('dosz-footer-menu');
                @endphp
                <nav class="text-base-content">
                    <h6 class="footer-title">{{ __('public.title.documents') }}</h6>
                    @foreach ($menuItems as $menuItem)
                        @if (count($menuItem->subs))
                            <a href="#" class="link link-hover">About us</a>
                        @else
                            @if ($menuItem->type === 'external')
                                <a href="{{ $menuItem->route }}">{{ $menuItem->name }}</a>
                            @else
                                <a href="{{ route($menuItem->route, $menuItem->params) }}">{{ $menuItem->name }}</a>
                            @endif
                        @endif
                    @endforeach
                </nav>
            </footer>

        </div>

    </div>


    @livewireScripts
    @filamentScripts
    @vite('resources/js/app.js')

</body>

</html>
