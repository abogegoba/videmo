<?php

namespace App\Providers;

use App\Adapters\Gateways\Validators\MemberCsvIlluminateValidator;
use App\Adapters\Http\Validation\Validator;
use App\Business\Interfaces\Gateways\Validators\MemberCsvValidator;
use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
            return new Validator($translator, $data, $rules, $messages, $customAttributes);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // 会員CSVバリデーター
        $this->app->singleton(
            MemberCsvValidator::class,
            MemberCsvIlluminateValidator::class
        );
    }
}
