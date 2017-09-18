<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('tax_id_number', function ($attribute, $value, $paramters, $validator) {
            $allowedChars = '';
            $maxChars = null;
            $parametersNumber = count($paramters);
            if ($parametersNumber === 1 && is_numeric($paramters[0])) {
                $maxChars = $paramters[0] ?? -1;
            } else if ($parametersNumber === 1) {
                $allowedChars = $paramters[0] ?? '';
            } else if ($parametersNumber == 2) {
                $allowedChars = $paramters[0] ?? '';
                $maxChars = $paramters[1] ?? -1;
            } else {
                throw new InvalidArgumentException("Invalid number/content of \$parameters variable");
            }
            $taxIdParts = str_replace(str_split($allowedChars), '', $value);
            $taxIdParts = str_split($taxIdParts);

            if (!is_null($maxChars) && count($taxIdParts) != $maxChars) {
                return false;
            }
            switch (App::getLocale()) {
                case 'pl':
                    $checksum = $taxIdParts[0] * 6 + $taxIdParts[1] * 5 + $taxIdParts[2] * 7 + $taxIdParts[3] * 2
                        + $taxIdParts[4] * 3 + $taxIdParts[5] * 4 + $taxIdParts[6] * 5 + $taxIdParts[7] * 6 + $taxIdParts[8] * 7;

                    $result = $checksum % 11 == $taxIdParts[8];
                    break;
                default:
                    $result = false;
                    break;
            }

            return $result;
        });

        Validator::replacer('tax_id_number', function ($message, $attribute, $rule, $parameters) {
            $text = '';
            if (isset($parameters[0])) {
                $allowedChars = implode('|', str_split($parameters[0]));
                $text = trans('validation.tax_id_number_allowed_chars', ['attribute' => "[$allowedChars]"]);
            }
            $message = str_replace(':allowedChars', $text, $message);
            $text = '';
            if (isset($parameters[1])) {
                $text = trans('validation.tax_id_number_max_chars', ['attribute' => $parameters[1]]);
            }
            $message = str_replace(':maxChars', $text, $message);

            return $message;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
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
