<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laravel\Folio\Folio;
use Livewire\Volt\Volt;
use Tests\Fixtures\User;

beforeEach(function () {
    Volt::mount([
        __DIR__.'/resources/views/class-api-pages',
        __DIR__.'/resources/views/class-api',
    ]);
});

test('page definition after template and component', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    $response = $this->get('page-definition-after-component');

    $response->assertSee('Folio 2 page definition after component.');
    $response->assertSee('Volt 2 page definition after component.');
});

test('route binding using lazy state', function () {
    Route::get('/route-binding/{user}/using-lazy-state', function (User $user) {
        $contents = File::get(__DIR__.'/resources/views/class-api-pages/route-binding/[.Tests.Fixtures.User]/using-lazy-state.blade.php');

        return Blade::render($contents, [
            'user' => $user,
        ]);
    })->middleware('web');

    User::create([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $response = $this->get('route-binding/1/using-lazy-state');

    $response->assertSee('Folio 1 using lazy state.');
    $response->assertSee('Volt 1 using lazy state.');
});

test('route binding using state', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    User::create([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $response = $this->get('route-binding/1/using-state');

    $response->assertSee('Folio 1 using state.');
    $response->assertSee('Volt 1 using state.');
});

test('route binding with trashed using state', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    $response = $this->get('route-binding/1/using-state-with-trashed');

    $response->assertNotFound();

    $user = User::create([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $response = $this->get('route-binding/1/using-state-with-trashed');
    $response->assertSee('Folio 1');
    $response->assertSee('Volt 1');

    $user->delete();

    $response = $this->get('route-binding/1/using-state-with-trashed');
    $response->assertSee('Folio 1 using state with trashed.');
    $response->assertSee('Volt 1 using state with trashed.');
});

test('route binding using mount', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    User::create([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $response = $this->get('route-binding/1/using-mount');

    $response->assertSee('Folio 1 using mount.');
    $response->assertSee('Volt 1 using mount.');
});

test('authorization with mount', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    $response = $this->get('authorization-with-mount');

    $response->assertStatus(403);
});

test('`@livewireStyles` and `@livewireScripts` blade directives may be used in a page with fragments', function () {
    Folio::route(__DIR__.'/resources/views/class-api-pages');

    $response = $this->get('page-livewire-styles-and-scripts');

    $response->assertStatus(200)->assertSee('Page Livewire Styles and Scripts');
});
