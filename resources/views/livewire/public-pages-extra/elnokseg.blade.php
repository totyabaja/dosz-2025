<div class="grid grid-cols-2 gap-4 xl:grid-cols-5 md:grid-cols-4 not-prose">
    @forelse ($members as $member)
        <div class="card sm:max-w-sm">
            <figure>
                <img src="{{ $member->getFilamentAvatarUrl() }}" alt="{{ $member->name }} foto" style="margin: 0px;" />
            </figure>
            <div class="card-body">
                <h5 class="card-title mb-2.5">{{ $member->name }}</h5>
                <div class='italic'>
                    {{ $member->positions->first()->position_subtype->filamentName }}
                </div>
                <p class="mb-4">
                    E-mail: {{ $member->positions->first()->email }}
                </p>
            </div>
        </div>

    @empty
        Senki sincs még
    @endforelse

</div>
