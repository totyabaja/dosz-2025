<?php

namespace App\Models\Menu;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

class PublicMenu extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'parent_id',
        'custom_id',
        'label',
        'slug',
        'external_url',
        'target',
        'order',
    ];

    protected $casts = [
        'label' => 'array',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PublicMenu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this
            ->hasMany(PublicMenu::class, 'parent_id')
            ->orderBy('order');
    }

    public function menu_page(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'public_menu_page');
    }

    // Oldal elérése a slug alapján
    // TODO: ez nem elsz jó
    public function getPageAttribute()
    {
        return Page::query()
            ->where('slug', $this->slug)
            ->orderBy('version', 'desc')
            ->first();
    }

    protected static function buildMenuTree(Collection $menuItem)
    {
        return $menuItem->map(function ($item) {
            // Meghatározzuk a route és params értékeket
            $route = null;
            $params = [];

            if ($item->slug) {
                $route = 'public.' . $item->slug;
                $params = ['slug' => null];
            } elseif ($item->external_url) {
                $route = $item->external_url; // Külső link esetén az URL-t használjuk route-ként
                $params = ['slug' => null];
            } elseif ($item->menu_page()->exists()) {
                $page = $item->menu_page()->first();
                if ($page) {
                    $route = 'public.pages';
                    $params = ['slug' => $page->slug];
                }
            }


            return (object) [
                'route' => $route,
                'params' => $params,
                'name' => __($item->label[session()->get('locale', 'hu')]),
                'subs' => $item->children->isNotEmpty() ? static::buildMenuTree($item->children) : [],
            ];
        })->toArray();
    }

    public static function getTree(string $id): array
    {
        $parent = PublicMenu::where('custom_id', $id)->first();

        if (!$parent) {
            return []; // Ha nincs ilyen menüelem, üres tömböt adunk vissza
        }

        return static::buildMenuTree($parent->children);
    }
}
