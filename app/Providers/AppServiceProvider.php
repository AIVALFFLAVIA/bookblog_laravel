<?php

namespace App\Providers;

use App\Services\MailchimpNewsletter;
use App\Services\Newsletter;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use MailchimpMarketing\ApiClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        app()->bind(Newsletter::class, function (){


            $client = (new ApiClient)->setConfig([
                'apiKey' => config('services.mailchimp.key'),
                'server' => 'us17'
            ]);

            return new MailchimpNewsletter($client);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
     Model::unguard();

     Gate::define('admin',function (User $user){
        return $user->username =='FlaviaRira';
     } );

     Blade::if('admin', function (){
        return request()->user()?->can('admin');
     });
    }
}
