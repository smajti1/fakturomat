<?php declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (isset($_SERVER['HTTP_X_FORWARDED_PORT']) && $_SERVER['HTTP_X_FORWARDED_PORT'] === '443')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
        ) {
            URL::forceScheme('https');
        }

        Validator::extend('tax_id_number', static function ($attribute, $value, $parameters, $validator) {
            $parametersNumber = count($parameters);
            if ($parametersNumber === 1) {
                $allowedChars = $parameters[0] ?? '';
            } else if ($parametersNumber === 2) {
                $allowedChars = $parameters[0] ?? '';
            } else {
                throw new InvalidArgumentException("Invalid number/content of \$parameters variable");
            }
            $taxIdParts = str_replace(str_split($allowedChars), '', $value);
            $taxIdParts = array_map('intval', str_split($taxIdParts));
            if (count($taxIdParts) < 9) {
                return false;
            }

            switch (App::getLocale()) {
                case 'pl':
                    $controlSumNumberIndex = 9;
                    $checksum = $taxIdParts[0] * 6 + $taxIdParts[1] * 5 + $taxIdParts[2] * 7 + $taxIdParts[3] * 2
                        + $taxIdParts[4] * 3 + $taxIdParts[5] * 4 + $taxIdParts[6] * 5 + $taxIdParts[7] * 6 + $taxIdParts[8] * 7;

                    $result = $checksum % 11 === $taxIdParts[$controlSumNumberIndex];
                    break;
                default:
                    $result = false;
                    break;
            }

            return $result;
        });

        Validator::replacer('tax_id_number', static function ($message, $attribute, $rule, $parameters) {
            $text = '';
            if (isset($parameters[0])) {
                $allowedChars = implode('|', str_split($parameters[0]));
                $text = trans('validation.tax_id_number_allowed_chars', ['attribute' => "[$allowedChars]"]);
            }

            return str_replace(':allowedChars', $text, $message);
        });
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment() === 'local') {
            if (class_exists('Barryvdh\Debugbar\ServiceProvider')) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }
            if (class_exists('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider')) {
                $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
            }
        }
    }
}
