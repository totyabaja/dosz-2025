<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PublicMenuPage extends Pivot
{
    use HasFactory;

    public $table = 'public_menu_page';

    public function menu(): BelongsTo
    {
        return $this->belongsTo(PublicMenu::class);
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
