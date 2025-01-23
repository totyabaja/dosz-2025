<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\EmailVerification;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\RequestPasswordReset;
use App\Filament\Pages\Backups;
use App\Filament\Pages\HealthCheckResults;
use App\Filament\Pages\Registration;
use App\Filament\Resources\MenuResource;
use App\Livewire\MyProfileExtended;
use App\Livewire\MyProfileExtendedUniversity;
use App\Settings\GeneralSettings;
use Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation;
use Filament\Navigation\UserMenuItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
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
            //->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearch(false)
            ->unsavedChangesAlerts()
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                Navigation\NavigationGroup::make()
                    ->label('Content') // !! To-Do: lang
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.access'))
                    ->collapsible(false),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.settings'))
                    ->collapsed(),
                Navigation\NavigationGroup::make()
                    ->label(__('menu.nav_group.activities'))
                    ->collapsed(),
            ])
            ->navigationItems([
                Navigation\NavigationItem::make('Log Viewer') // !! To-Do: lang
                    ->visible(fn(): bool => auth()->user()->can('access_log_viewer'))
                    ->url(config('app.url') . '/' . config('log-viewer.route_path'), shouldOpenInNewTab: true)
                    ->icon('fluentui-document-bullet-list-multiple-20-o')
                    ->group(__('menu.nav_group.activities'))
                    ->sort(99),
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\FilamentInfoWidget::class,
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
                \TomatoPHP\FilamentMediaManager\FilamentMediaManagerPlugin::make()
                    ->allowSubFolders(),
                \BezhanSalleh\FilamentExceptions\FilamentExceptionsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                    ->gridColumns([
                        'default' => 2,
                        'sm' => 1
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
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
                \A21ns1g4ts\FilamentShortUrl\FilamentShortUrlPlugin::make(),
                \ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin::make()
                    ->usingPage(HealthCheckResults::class)
                    ->authorize(fn(): bool => Auth::user()?->hasRole('super_admin')),
                \RickDBCN\FilamentEmail\FilamentEmail::make(),
                // TODO: https://filamentphp.com/plugins/visual-builder-email-templates
                \Croustibat\FilamentJobsMonitor\FilamentJobsMonitorPlugin::make()
                /*->enableNavigation(
                        fn() => auth()->user()->can('view_queue_job') || auth()->user()->can('view_any_queue_job)'),
                    )*/,
                \Visualbuilder\EmailTemplates\EmailTemplatesPlugin::make()
                /*->enableNavigation(
                        fn() => auth()->user()->can('view_email_templates') || auth()->user()->can('view_any_email_templates)'),
                    )*/,

            ]);
    }
}
