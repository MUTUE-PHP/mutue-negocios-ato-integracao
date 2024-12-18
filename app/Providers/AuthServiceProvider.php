<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\empresa\Permission;
use App\Models\empresa\User;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\Facades\Request;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        // \App\Models\empresa\Produto::class => \App\Policies\empresa\ProdutoPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(Gate $gate)
    {
       $this->registerPolicies();
        if (str_contains(Request::url(), 'admin')) {
            $permissions = \App\Models\admin\Permission::get();
        }else{
            $permissions = Permission::get();
        }
        foreach ($permissions as $permission) {
           $gate->define($permission->name, function () use ($permission) {
               if (str_contains(Request::url(), 'admin')) {
                   $user = new \App\Models\admin\User();
                   return $user->hasPermission($permission);
               }
               $user = new User();
               return $user->hasPermission($permission);
           });
       }

      $gate->before(function ($user, $ability) {
          if ($user->isSuperAdmin()) {
              return true;
          }
      });

    }
}
