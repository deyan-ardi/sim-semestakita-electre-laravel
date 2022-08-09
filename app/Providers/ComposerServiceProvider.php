<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Using class based composers...
        View::composer(
            [
                'admin.layouts._navbar',
                'enduser.layouts._navbar',
                'enduser.page.profil.index',
                'admin.page.dashboard.index',
                'admin.layouts._navbar-fullscreen',
                'admin.page.profil.index',
            ],
            'App\Http\ViewComposers\ImgComposer'
        );

        View::composer(
            [
                'enduser.layouts._navbar',
            ],
            'App\Http\ViewComposers\NotifikasiEnduserComposer'
        );

        View::composer(
            [
                'admin.layouts._navbar',
                'admin.layouts._navbar-fullscreen',
            ],
            'App\Http\ViewComposers\NotifikasiAdminComposer'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
