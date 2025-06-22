<?php

namespace App\Providers\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Rmsramos\Activitylog\ActivitylogPlugin;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Monzer\FilamentEmailVerificationAlert\EmailVerificationAlertPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Navigation\MenuItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Filament\Http\Middleware\DisableBladeIconComponents;
use App\Filament\Resources\CourseResource\Widgets\CourseOverview;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Pages\Auth\EmailVerification\EmailVerificationPrompt;
use Filament\Pages\Auth\Login;
use Filament\Pages\Auth\PasswordReset\RequestPasswordReset;
use Filament\Pages\Auth\Register;
use Filament\Panel;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('panel')
            ->path('panel')
            ->login()
            ->registration()
            ->emailVerification()
            ->passwordReset()
            ->profile(EditProfilePage::class)
            ->colors([
                'primary' => Color::Purple,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                FilamentEditProfilePlugin::make()
                    ->slug('my-profile')
                    ->setTitle(__('profile.title'))
                    ->setNavigationLabel(fn() => __('profile.navtitle'))
                    ->setNavigationGroup(fn() => __('profile.grouptitle'))
                    ->setIcon('heroicon-o-user')
                    ->setSort(10)
                    ->shouldRegisterNavigation()
                    ->shouldShowEmailForm()
                    ->shouldShowDeleteAccountForm()
                    ->shouldShowSanctumTokens()
                    ->shouldShowBrowserSessionsForm()
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars', // image will be stored in 'storage/app/public/avatars
                        rules: 'mimes:jpeg,png|max:1024' //only accept jpeg and png files with a maximum size of 1MB
                    ),
                ActivityLogPlugin::make()
                    ->label(fn() => __('activities.singular'))
                    ->pluralLabel(fn() => __('activities.plural'))
                    ->navigationCountBadge(true)
                    ->authorize(fn() => auth()->user()->can('manage any activities')),
            ])
            ->brandName(__("LearnFlow"))
            ->brandLogo(asset("favicon.png"))
            ->favicon(asset('favicon.png'))
            ->font('Vazirmatn')
            ->emailVerification(EmailVerificationPrompt::class)
            ->spa(true)
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
            ->databaseNotifications()
            ->sidebarCollapsibleOnDesktop()
            ->navigationItems(
                [
                    NavigationItem::make('feed')
                        ->label(__('navigation.feed'))
                        ->group(fn() => __('navigation.group.general'))
                        ->icon('heroicon-o-newspaper')
                        ->url(fn() => route('dashboard'))
                        ->sort(0)
                ]
            )
            //           ->defaultAvatarProvider(
            //             \App\Filament\AvatarProviders\BoringAvatarProvider::class
            //       );
        ;
    }
}