<?php

namespace TotyaDev\TotyaDevMediaManager;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Nwidart\Modules\Module;
use TomatoPHP\FilamentArtisan\Pages\Artisan;
use TotyaDev\TotyaDevMediaManager\Resources\{FolderResource, MediaResource};


class TotyaDevMediaManagerPlugin implements Plugin
{
    private bool $isActive = false;


    public ?bool $allowSubFolders = false;
    public ?bool $allowUserAccess = false;

    public function getId(): string
    {
        return 'totyadev-media-manager';
    }

    public function allowSubFolders(bool $condation = true): static
    {
        $this->allowSubFolders = $condation;
        return $this;
    }

    public function allowUserAccess(bool $condation = true): static
    {
        $this->allowUserAccess = $condation;
        return $this;
    }

    public function register(Panel $panel): void
    {
        if (class_exists(Module::class) && \Nwidart\Modules\Facades\Module::find('TotyaDevMediaManager')?->isEnabled()) {
            $this->isActive = true;
        } else {
            $this->isActive = true;
        }

        if ($this->isActive) {
            $panel->resources([
                FolderResource::class,
                MediaResource::class
            ]);
        }
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return new static();
    }
}
