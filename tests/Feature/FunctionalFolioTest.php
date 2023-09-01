<?php

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Laravel\Folio\Folio;
use Livewire\Volt\Volt;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\User;

beforeEach(function () {
    Volt::mount([
        __DIR__.'/resources/views/functional-api-pages',
        __DIR__.'/resources/views/functional-api',
    ]);
});

test('page definition after template and component', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('page-definition-after-component');

    $response->assertSee('Folio 2 page definition after component.');
    $response->assertSee('Volt 2 page definition after component.');
});

test('route binding using lazy state', function () {
    Route::get('/route-binding/{user}/using-lazy-state', function (User $user) {
        $contents = File::get(__DIR__.'/resources/views/functional-api-pages/route-binding/[.Tests.Fixtures.User]/using-lazy-state.blade.php');

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
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

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
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

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
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    User::create([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $response = $this->get('route-binding/1/using-mount');

    $response->assertSee('Folio 1 using mount.');
    $response->assertSee('Volt 1 using mount.');
});

test('authorization with folio middleware', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('authorization-with-folio-middleware');

    $response->assertStatus(401);
});

test('authorization with folio php tags', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('authorization-with-folio-php-tags');

    $response->assertStatus(402);
});

test('folio imports dont conflict with fragments imports', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('folio-imports-dont-conflict-with-fragments-imports');

    $response->assertStatus(200)
        ->assertSee('Taylor');
});

test('authorization with mount', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('authorization-with-mount');

    $response->assertStatus(403);
});

test('`assertSeeVolt` testing method', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $this->get('/page-with-fragment')
        ->assertOk()
        ->assertSeeVolt('fragment-component')
        ->assertOk();
});

test('`assertDontSeeVolt` testing method', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $this->get('/page-with-fragment')
        ->assertSeeVolt('fragment-component')
        ->assertDontSeeVolt('second-component')
        ->assertOk();
});

test('`assertDontSeeVolt` testing method can fail', function () {
    $this->expectException(ExpectationFailedException::class);

    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $this->get('/page-with-fragment')
        ->assertDontSeeVolt('fragment-component')
        ->assertOk();
});

test('page with view content', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('page-with-view-content');

    $response->assertStatus(200)
        ->assertSee('Hello from view.');
});

test('page with authorization on render', function () {
    Folio::route(__DIR__.'/resources/views/functional-api-pages');

    $response = $this->get('authorization-on-render');

    $response->assertStatus(403);
});
