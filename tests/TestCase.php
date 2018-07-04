<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public  $baseUrl = 'http://billboard';

//    public function artisan($command, $parameters = [])
//    {
//        Artisan::call('migrate:fresh',['--database'=>'sqlite']);
//        Artisan::call('db:seed',['--database'=>'sqlite']);
//    }
}
