<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Widgets\{UserStatWidget, UserActivityStatWidget};
use App\Filament\Event\Resources\EventRegistrationResource\Widgets\EventRegistrationTable;
use App\Filament\Event\Resources\EventRegistrationResource\Widgets\EventRegistrationWidget;
use App\Filament\Pages\Auth\{EmailVerification, Login, RequestPasswordReset};
use App\Filament\Pages\{HealthCheckResults, Registration};
use App\Filament\Pages\Setting\{ManageGeneral, ManageMail};
use App\Livewire\{MyProfileExtended, MyProfileExtendedUniversity};
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use App\Filament\ToAdmin\Widgets\DepartmentInfoWidget;
use App\Settings\GeneralSettings;
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\UserMenuItem;
use Filament\{Pages, Panel, PanelProvider, Widgets};
use Filament\View\PanelsRenderHook;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
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
                    ->url(fn(): string => url('/event'))
                    ->icon('far-calendar'),
                UserMenuItem::make()
                    ->label('Admin')
                    ->url('/admin')
                    ->icon('heroicon-o-user')
                    ->visible(fn(): bool => Auth::user()?->hasAnyRole(['super_admin', 'dosz_admin', 'jogsegelyes', 'dosz_rendezvenyes'])),
                UserMenuItem::make()
                    ->label('TO Admin')
                    ->url('/to-admin')
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
