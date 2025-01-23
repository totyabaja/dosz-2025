<?php

namespace App\Livewire\PublicPagesExtra;

use App\Models\User;
use Livewire\Component;

class Elnokseg extends Component
{
    public function render()
    {
        $members = User::query()
            ->whereHas('positions', function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now())
                    ->whereHas('position_subtype', function ($subQuery) {
                        $subQuery->where('position_type_id', 1);
                    });
            })
            ->with(['positions' => function ($query) {
                $query->whereNull('end_date')
                    ->orWhereDate('end_date', '>=', now());
            }])
            ->get()
            ->sortBy(fn ($user) => $user->positions->first()->position_subtype->order);

        // TODO: order by posi order

        return view('livewire.public-pages-extra.elnokseg', compact('members'));
    }
}
