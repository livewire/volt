<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Collection;
use Livewire\Volt\Exceptions\SignatureMismatchException;
use Livewire\Volt\Methods\ActionMethod;
use Livewire\Volt\Support\Reflection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Psr\Log\LogLevel;

it('converts a closure to a method signature', function (Closure $closure, string $expectedSignature) {
    $signature = Reflection::toMethodSignatureFromClosure('test', $closure);
    expect($signature)->toBe($expectedSignature);

    $signature = str_replace('test', '', $signature);
    expect(eval("return $signature {};"))->toBeInstanceOf(Closure::class);
})->with([
    [fn () => true, 'function test()'],
    [fn ($param1) => true, 'function test($param1)'],
    [fn ($param1, $param2) => true, 'function test($param1, $param2)'],
    [fn ($param1 = 123) => true, 'function test($param1 = 123)'],
    [fn (mixed $param1 = [1, 2, 3]) => true, 'function test(mixed $param1 = array (  0 => 1,  1 => 2,  2 => 3,))'],
    [fn (mixed $param1 = PHP_VERSION) => true, 'function test(mixed $param1 = \PHP_VERSION)'],
    [fn (string $param1 = LogLevel::ALERT) => true, 'function test(string $param1 = \Psr\Log\LogLevel::ALERT)'],
    [fn (Foo $param1 = Foo::Bar) => true, 'function test(\Foo $param1 = \Foo::Bar)'],
    [fn ($param1, mixed ...$params) => true, 'function test($param1, mixed ...$params)'],
    [fn (string $param1) => true, 'function test(string $param1)'],
    [fn (string $param1 = 'nuno') => true, 'function test(string $param1 = \'nuno\')'],
    [fn (string|int $param1) => true, 'function test(string|int $param1)'],
    [fn (string $param1, int $param2) => true, 'function test(string $param1, int $param2)'],
    [fn (object $param1) => true, 'function test(object $param1)'],
    [fn (User|Collection $param1) => true, 'function test(\Illuminate\Foundation\Auth\User|\Illuminate\Support\Collection $param1)'],
    [fn (User&Collection $param1) => true, 'function test(\Illuminate\Foundation\Auth\User&\Illuminate\Support\Collection $param1)'],
    [fn (array $param1) => true, 'function test(array $param1)'],
    [fn (callable $param1) => true, 'function test(callable $param1)'],
    [fn (?string $param1) => true, 'function test(?string $param1)'],
    [fn (mixed $param1) => true, 'function test(mixed $param1)'],
    [fn ($param1, string ...$params) => true, 'function test($param1, string ...$params)'],
    [static fn () => true, 'function test()'],
    [static function () {
        //
    }, 'function test()'],
    [fn (): bool => true, 'function test(): bool'],
    [fn (): ?string => 'foo', 'function test(): ?string'],
    [fn (): string|bool => true, 'function test(): string|bool'],
    [fn (): User|Collection => new User, 'function test(): \Illuminate\Foundation\Auth\User|\Illuminate\Support\Collection'],
    [fn (): User&Collection => new User, 'function test(): \Illuminate\Foundation\Auth\User&\Illuminate\Support\Collection'],

    // Eventually, if we bump the minimum PHP version to 8.2, we can uncomment these:
    // [fn (): (User&Collection)|string => new User, 'function test(): (\Illuminate\Foundation\Auth\User&\Illuminate\Support\Collection)|string'],
    // [fn ((User&Collection)|string $user) => new User,'function test((\Illuminate\Foundation\Auth\User&\Illuminate\Support\Collection)|string $user)'],
]);

it('allows exception messages to be overwritten', function (Throwable $throwable) {
    Reflection::setExceptionMessage($throwable, 'bar');

    expect($throwable->getMessage())->toBe('bar');
})->with([
    new Exception('foo'),
    new Error('foo'),
    new RuntimeException('foo'),
]);

enum Foo
{
    case Bar;
    case Baz;
}

it('converts multiple closures to a single method signature', function (array $closures, string $expectedSignature) {
    $signature = Reflection::toSingleMethodSignatureFromClosures('test', $closures);
    expect($signature)->toBe($expectedSignature);

    $signature = str_replace('test', '', $signature);
    expect(eval("return $signature {};"))->toBeInstanceOf(Closure::class);
})->with([
    [[], 'function test()'],
    [[fn () => null, fn () => null], 'function test()'],
    [[fn (Filesystem $files) => null, fn () => null], 'function test(\Illuminate\Filesystem\Filesystem $files)'],
    [[fn () => null, fn (Filesystem $files) => null], 'function test(\Illuminate\Filesystem\Filesystem $files)'],
    [[fn (User $user) => null, fn (Filesystem $files) => null], 'function test(\Illuminate\Foundation\Auth\User $user, \Illuminate\Filesystem\Filesystem $files)'],
    [[fn (?User $user) => null, fn (?Filesystem $files) => null], 'function test(?\Illuminate\Foundation\Auth\User $user, ?\Illuminate\Filesystem\Filesystem $files)'],
    [[fn (User $user) => null, fn (User $user, Filesystem $files) => null], 'function test(\Illuminate\Foundation\Auth\User $user, \Illuminate\Filesystem\Filesystem $files)'],
    [[fn (Filesystem $files, User $user) => null, fn (User $user, Filesystem $files) => null], 'function test(\Illuminate\Filesystem\Filesystem $files, \Illuminate\Foundation\Auth\User $user)'],
    [[fn (): bool => true, fn (): bool => true], 'function test(): bool'],
]);

it('throws signature mismatch when trying to convert multiple closures to a single method signature ', function (array $closures) {
    Reflection::toSingleMethodSignatureFromClosures('test', $closures);
})->with([
    [[fn (): string => '', fn () => null], 'function test()'],
    [[fn () => '', fn (): string => null], 'function test()'],
    [[function (): mixed {
        //
    }, fn (): string => null], 'function test()'],
    [[fn (Filesystem $files) => null, fn ($files) => null]],
    [[fn ($files) => null, fn (Filesystem $files) => null]],
    [[fn (?Filesystem $files) => null, fn (Filesystem $files) => null]],
    [[fn (Filesystem|User $files) => null, fn (Filesystem $files) => null]],
    [[fn (): ?bool => true, fn (): bool => true], 'function test(): bool'],

])->throws(SignatureMismatchException::class);

it('converts attributes to a attributes signature', function ($attributes, string $expectedSignature) {
    $method = ActionMethod::make(fn () => null);
    [$name, $parameters] = $attributes;

    $method->attribute($name, ...$parameters);

    $signature = Reflection::toAttributesSignature($method->reflection()->attributes);

    expect($signature)->toBe($expectedSignature);
})->with([
    [[Computed::class, ['persist' => true]], '    #[\Livewire\Attributes\Computed(persist: true)]'],
    [[Computed::class, ['persist' => true, 'seconds' => 30]], '    #[\Livewire\Attributes\Computed(persist: true, seconds: 30)]'],
    [[On::class, []], '    #[\Livewire\Attributes\On()]'],
    [[On::class, ['MyClass,foo', 'fromChildren' => true]], "    #[\Livewire\Attributes\On('MyClass,foo', fromChildren: true)]"],
]);
