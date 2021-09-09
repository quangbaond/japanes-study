<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('includes.student.panel','App\Http\ViewComposers\StudentPanelBarComposer');
        view()->composer(['includes.admin.sidebar','includes.admin.panel'],'App\Http\ViewComposers\SidebarComposer');
        view()->composer('includes.student.headers','App\Http\ViewComposers\StudentHeaderComposer');
        view()->composer('includes.admin.panel','App\Http\ViewComposers\AdminPanelComposer');
        view()->composer('includes.admin.headers','App\Http\ViewComposers\AdminHeaderComposer');
        view()->composer('includes.student.panel_expired_premium','App\Http\ViewComposers\StudentExpiredPremiumBarComposer');
        view()->composer('includes.admin.headers', 'App\Http\ViewComposers\AdminHeaderComposer');
    }
}
