@php

use Tests\Fixtures\User;

$user = User::create([
    'name' => 'Taylor Otwell',
    'email' => 'taylor@laravel.com',
    'password' => 'secret',
]);

@endphp

<div>
    <span>{{ $user->name }}</span>
</div>
