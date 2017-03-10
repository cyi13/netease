<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        //网站标题
        view()->share('netTitle','FUNCTION');
        //打印出执行的sql
        // \DB::listen(function($sql) {    
        //   foreach ($sql->bindings as $i => $binding) { 
        //          if ($binding instanceof \DateTime) {     
        //                $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\''); 
        //          } else {           
        //                if (is_string($binding)) { 
        //                              $sql->bindings[$i] = "'$binding'"; 
        //                }
        //       } 
        //  }    
        //  $query = str_replace(array('%', '?'), array('%%', '%s'), $sql->sql); 
        //  $query = vsprintf($query, $sql->bindings); 
        //  var_dump($query);
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Repositories\Interfaces\CrawlersInterface','App\Repositories\Implement\CrawlersRepository');
    }
}
