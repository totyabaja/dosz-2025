<?php

namespace App\Livewire\PublicPagesExtra;

use App\Models\User;
use Livewire\Component;

class FelugyeloBizottsag extends Component
{
    public function render()
    {
        $position_id = 2;

        $members = User::query()
            ->whereHas('positions', function ($query) use ($position_id) {
                $query
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', now());
                    })
                    ->whereHas('position_subtype', function ($subQuery) use ($position_id) {
                        $subQuery->where('position_type_id', $position_id);
                    });
            })
            ->with(['positions' => function ($query) use ($position_id) {
                $query
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                            ->orWhereDate('end_date', '>=', now());
                    })
                    ->whereHas('position_subtype', function ($subQuery) use ($position_id) {
                        $subQuery->where('position_type_id', $position_id);
                    });
            }])
            ->get()
            ->sortBy(fn($user) => $user->positions->first()->position_subtype->order);


        // TODO: order by posi order

        return view('livewire.public-pages-extra.felugyelo-bizottsag', compact('members'));
    }
}
