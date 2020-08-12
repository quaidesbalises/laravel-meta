<?php

namespace Qdb\Meta;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;

class MetaServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
    public function boot()
    {	
        $this->publishes([
            __DIR__.'/../config/meta.php' => config_path('meta.php'),
        ], 'config');

    	$this->bladeDirectives();
    }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
    public function register()
    {
    	$this->app->singleton('meta', function () {
            return new Meta();
        });

        $this->mergeConfigFrom(__DIR__.'/../config/meta.php', 'meta');
    }

	/**
	 * Register blade directives
	 *
	 * @return void
	 */
    protected function bladeDirectives()
    {
    	Blade::directive('meta', function ($key) {
			return "<?php echo Meta::tag($key); ?>";
		});

		Blade::directive('metas', function ($arguments) {
			return "<?php echo Meta::tags($arguments); ?>";
		});
    }

}
