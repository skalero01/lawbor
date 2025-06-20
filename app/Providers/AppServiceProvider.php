<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::macro('toUserTimezone', function () {
            $tz = auth()->check() ? auth()->user()->timezone : config('app.timezone');
            return $this->copy()->setTimezone($tz);
        });

        // Allows using Model::tableName() statically
        Builder::macro('tableName', function (): string {
            assert($this instanceof Builder);
            return $this->from;
        });

        Blade::directive('userDate', function (string $expression) {
            return "<?php echo ($expression)->tz(auth()->user()?->timezone ?? config('app.timezone'))->format('Y-m-d H:i:s'); ?>";
        });
    }
}
