<div class="relative inline-flex dropdown">
    <button id="dropdown-menu-icon" type="button" class="dropdown-toggle btn btn-square btn-primary" aria-haspopup="menu"
        aria-expanded="false" aria-label="Dropdown">
        {{ $languageSwitch->getCharAvatar(session()->get('locale', 'hu')) }}
    </button>
    <ul class="hidden dropdown-menu dropdown-open:opacity-100 min-w-20" role="menu" aria-orientation="vertical"
        aria-labelledby="dropdown-menu-icon">
        @foreach ($languageSwitch->getLocales() as $locale)
            @if (session()->get('locale') !== $locale)
                <li><button type="button"
                        class="dropdown-item 'flex items-center w-full transition-colors duration-75 rounded-md outline-none fi-dropdown-list-item whitespace-nowrap disabled:pointer-events-none disabled:opacity-70 fi-dropdown-list-item-color-gray hover:bg-gray-950/5 focus:bg-gray-950/5'"
                        wire:click="changeLocale('{{ $locale }}')">
                        <span class="text-sm font-medium text-gray-600 hover:bg-transparent">
                            {{ $languageSwitch->getLabel($locale) }}
                        </span>
                    </button>
                </li>
            @endif
        @endforeach
    </ul>
</div>
