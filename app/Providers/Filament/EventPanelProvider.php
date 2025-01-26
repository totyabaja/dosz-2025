<?php

namespace App\Providers\Filament;

use App\Filament\Event\Resources\EventRegistrationResource\Widgets\EventRegistrationTable;
use App\Filament\Event\Resources\EventRegistrationResource\Widgets\EventRegistrationWidget;
use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Registration;
use App\Livewire\MyProfileExtended;
use App\Livewire\MyProfileExtendedUniversity;
use App\Settings\GeneralSettings;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class EventPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('event')
            ->path('event')
            ->login(Login::class)
            ->registration(Registration::class)
            ->defaultThemeMode(ThemeMode::Light)
            ->userMenuItems([
                UserMenuItem::make()
                    ->label('Kezdőoldal')
                    ->url(fn(): string => url('/'))
                    ->icon('heroicon-o-cog-6-tooth'),
                UserMenuItem::make()
                    ->label('Rendezvényeim')
                    ->url(fn(): string => url('/event')),
                UserMenuItem::make()
                    ->label('TO Admin')
                    ->url('/to')
                    ->icon('heroicon-o-user')
                    ->visible(fn(): bool => ! Auth::user()?->onlyNativeUser()),
            ])
            ->passwordReset(RequestPasswordReset::class)
            ->emailVerification(EmailVerification::class)
            ->favicon(fn(GeneralSettings $settings) => asset(Storage::url($settings->site_favicon)))
            ->brandName(fn(GeneralSettings $settings) => $settings->brand_name)
            ->brandLogo(fn(GeneralSettings $settings) => asset(Storage::url($settings->brand_logo)))
            ->brandLogoHeight(fn(GeneralSettings $settings) => $settings->brand_logoHeight)
            ->colors(fn(GeneralSettings $settings) => $settings->site_theme)
            ->databaseNotifications()->databaseNotificationsPolling('30s')
            ->globalSearch(false)
            ->unsavedChangesAlerts()
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([])
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Log Viewer') // !! To-Do: lang
                    ->visible(fn(): bool => auth()->user()->can('access_log_viewer'))
                    ->url(config('app.url') . '/' . config('log-viewer.route_path'), shouldOpenInNewTab: true)
                    ->icon('fluentui-document-bullet-list-multiple-20-o')
                    ->group(__('menu.nav_group.activities'))
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Event/Resources'), for: 'App\\Filament\\Event\\Resources')
            ->discoverPages(in: app_path('Filament/Event/Pages'), for: 'App\\Filament\\Event\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Event/Widgets'), for: 'App\\Filament\\Event\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                EventRegistrationWidget::class,
                EventRegistrationTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                \Jeffgreco13\FilamentBreezy\BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true,
                        shouldRegisterNavigation: false,
                        // navigationGroup: 'Settings', // TODO
                        hasAvatars: true,
                        slug: 'my-profile',
                    )
                    ->passwordUpdateRules(
                        rules: [Password::default()->mixedCase()->uncompromised(3)], // you may pass an array of validation rules as well. (default = ['min:8'])
                        requiresCurrentPassword: true, // when false, the user can update their password without entering their current password. (default = true)
                    )
                    ->enableTwoFactorAuthentication(
                        force: false, // force the user to enable 2FA before they can use the application (default = false)
                        // action: CustomTwoFactorPage::class // optionally, use a custom 2FA page
                    )
                    ->myProfileComponents([
                        'personal_info' => MyProfileExtended::class,
                        'university_info' => MyProfileExtendedUniversity::class,
                    ]),
                \TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin::make()
                    ->allowSubFolders()
                    ->allowUserAccess(),

            ]);
    }
}
