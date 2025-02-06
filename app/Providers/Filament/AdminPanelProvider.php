<?php

namespace App\Providers\Filament;

use App\Filament\Admin\Resources\TotyaMedia\Services\FilamentMediaManagerServices;
use App\Filament\Admin\Widgets\{UserStatWidget, UserActivityStatWidget};
use App\Filament\Pages\Auth\{EmailVerification, Login, RequestPasswordReset};
use App\Filament\Pages\{HealthCheckResults, Registration};
use App\Filament\Pages\Setting\{ManageGeneral, ManageMail};
use App\Livewire\{MyProfileExtended, MyProfileExtendedUniversity};
use App\Settings\GeneralSettings;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\{Authenticate, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Navigation\UserMenuItem;
use Filament\{Pages, Panel, PanelProvider, Widgets};
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\NavigationGroup;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{


    public function panel(Panel $panel): Panel
    {
        return $panel
            //->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration(Registration::class)
            ->defaultThemeMode(ThemeMode::Light)
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn(): View => view('filament.components.button-website', ['link' => config('app.url')]),
            )
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
            //->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearch(false)
            ->unsavedChangesAlerts()
            ->sidebarCollapsibleOnDesktop()
            ->navigationItems([
                \Filament\Navigation\NavigationItem::make('Log Viewer') // !! To-Do: lang
                    ->visible(fn(): bool => auth()->user()->can('access_log_viewer'))
                    ->url(config('app.url') . '/' . config('log-viewer.route_path'), shouldOpenInNewTab: true)
                    ->icon('fluentui-document-bullet-list-multiple-20-o')
                //->group(__('menu.nav_group.activities')),
            ])
            //->viteTheme('resources/css/filament/admin/theme.css')
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->resources([
                //ActivityResource::class,
                config('filament-logger.activity_resource')
            ])
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admijn\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ManageGeneral::class,
                ManageMail::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                UserStatWidget::class,
                UserActivityStatWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(__('menu.nav_group.access')),
                NavigationGroup::make()
                    ->label(__('menu.nav_group.content')),
                NavigationGroup::make()
                    ->label(__('menu.nav_group.settings')),
                NavigationGroup::make()
                    ->label(__('menu.nav_group.activities')),
                NavigationGroup::make()
                    ->label(__('menu.nav_group.legal_aid')),
                NavigationGroup::make()
                    ->label('Admin'),
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
                \TotyaDev\TotyaDevMediaManager\TotyaDevMediaManagerPlugin::make()
                    ->allowSubFolders()
                    ->allowUserAccess(),
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
                    ->navigationGroup(fn() => __('menu.nav_group.activities'))
                    ->enableNavigation(
                        fn() => auth()->user()->hasRole('super_admin'),
                    ),
                \Visualbuilder\EmailTemplates\EmailTemplatesPlugin::make()
                    ->navigationGroup(__('menu.nav_group.settings'))
                    ->enableNavigation(
                        fn() => auth()->user()->can('view_email_templates') || auth()->user()->can('view_any_email_templates)'),
                    ),


            ]);
    }
}
