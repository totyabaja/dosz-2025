<?php

namespace App\Providers;

use App\Http\Responses\LogoutResponse;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;
use Filament\Tables\Table;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;
use Opcodes\LogViewer\Facades\LogViewer;
use Spatie\Health\Facades\Health;
use Spatie\Health\Checks\Checks\OptimizedAppCheck;
use Spatie\Health\Checks\Checks\DebugModeCheck;
use Spatie\Health\Checks\Checks\EnvironmentCheck;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse as LogoutResponseContract;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LogoutResponseContract::class, LogoutResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Health::checks([
            OptimizedAppCheck::new(),
            DebugModeCheck::new(),
            EnvironmentCheck::new(),
        ]);

        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
                ->extremePaginationLinks()
                ->defaultSort('created_at', 'desc');
        });

        // # \Opcodes\LogViewer
        // TODO: csak akkor, ha super-admin
        LogViewer::auth(function ($request) {
            $role = auth()?->user()?->roles?->first()->name;
            return $role == config('filament-shield.super_admin.name');
        });

        // # Hooks
        FilamentView::registerRenderHook(
            PanelsRenderHook::FOOTER,
            fn(): View => view('filament.components.panel-footer'),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            fn(): View => view('filament.components.button-website'),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_BEFORE,
            LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
                $switch
                    ->locales(['hu', 'en'])
                    ->displayLocale('hu')
                    ->visible(outsidePanels: true)
                    ->outsidePanelRoutes([
                        'filament.admin.auth.login',
                        'filament.admin.auth.reg',
                        // Additional custom routes where the switcher should be visible outside panels
                    ])
                    ->outsidePanelPlacement(Placement::TopRight)
                    ->labels([
                        'hu' => 'Magyar (HU)',
                        'en' => 'English (EN)',
                        // Other custom labels as needed
                    ]);
            })
        );
    }
}
