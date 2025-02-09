<div class="space-y-4">
    @forelse ($getApprovedDepartments() as $membership)
        <div class="p-4 border rounded-lg bg-gray-50">
            <p class="font-medium text-gray-900">{{ $membership['department_name'] }}</p>
            <p class="text-sm ">
                Státusz: <span class="font-semibold text-green-500">Elfogadva</span>

            </p>
        </div>
    @empty
        <p>Nincs meglévő tagság.</p>
    @endforelse
</div>
