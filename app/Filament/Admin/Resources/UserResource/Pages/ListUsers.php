<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use JoseEspinal\RecordNavigation\Traits\HasRecordsList;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Str;

class ListUsers extends ListRecords
{
    use ExposesTableToWidgets;
    use HasRecordsList;

    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return static::$resource::getWidgets();
    }

    public function getTabs(): array
    {
        $user = auth()->user();
        $tabs = [
            null => Tab::make('All'),
            ...static::getUserRoles(),
            //'dosz_admin' => Tab::make()->query(fn($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'dosz_admin')),
            //'to_admin' => Tab::make()->query(fn($query) => $query->with('roles')->whereRelation('roles', 'name', '=', 'author')),
        ];

        if ($user->isSuperAdmin()) {
            $tabs['superadmin'] = Tab::make()->query(fn($query) => $query->with('roles')->whereRelation('roles', 'name', '=', config('filament-shield.super_admin.name')));
        }

        return $tabs;
    }

    protected static function getUserRoles(): array
    {
        $roleClass = (new (app(PermissionRegistrar::class)->getRoleClass()))::all();

        return $roleClass->mapWithKeys(fn($role) => [
            $role->name => Tab::make()->query(fn($query) => $query->with('roles')->whereRelation('roles', 'name', '=', $role->name)),
        ])->toArray();
    }

    protected function getTableQuery(): Builder
    {
        $user = auth()->user();
        $model = (new (static::$resource::getModel()))->with('roles')->where('id', '!=', auth()->user()->id);

        if (!$user->isSuperAdmin()) {
            $model = $model->whereDoesntHave('roles', function ($query) {
                $query->where('name', '=', config('filament-shield.super_admin.name'));
            });
        }

        return $model;
    }
}
