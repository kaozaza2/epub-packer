<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \App\Contracts\Actions\CssMaker::class,
            \App\Actions\CssMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\ContentOpfMaker::class,
            \App\Actions\ContentOpfMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\MimetypeMaker::class,
            \App\Actions\MimetypeMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\MetaContainerMaker::class,
            \App\Actions\MetaContainerMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\PageContentMaker::class,
            \App\Actions\PageContentMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\PageContentMakerFactory::class,
            \App\Actions\PageContentMakerFactory::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\TocMaker::class,
            \App\Actions\TocMaker::class,
        );

        $this->app->singleton(
            \App\Contracts\Actions\ZipPacker::class,
            \App\Actions\ZipPacker::class,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('dash', function ($expression) {
            return "<?php echo empty($expression) ? '-' : $expression; ?>";
        });
    }
}
