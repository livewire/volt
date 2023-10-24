# Release Notes

## [Unreleased](https://github.com/livewire/volt/compare/v1.4.0...main)

## [v1.4.0](https://github.com/livewire/volt/compare/v1.3.3...v1.4.0) - 2023-10-19

- [1.x] Adds tests around php blocks by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/58
- [1.x] Adds `actingAs` to Volt facade by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/61
- [1.x] Fixes imports used on fragments by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/63
- [1.x] Removes custom `rendering` call by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/65

## [v1.3.3](https://github.com/livewire/volt/compare/v1.3.2...v1.3.3) - 2023-09-25

- Add docblock for `Volt::withQueryParams()` by [@robsontenorio](https://github.com/robsontenorio) in https://github.com/livewire/volt/pull/55

## [v1.3.2](https://github.com/livewire/volt/compare/v1.3.1...v1.3.2) - 2023-09-19

- [1.x] Adds `withQueryParams` by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/51
- [1.x] Adds `rendering` callback by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/52

## [v1.3.1](https://github.com/livewire/volt/compare/v1.3.0...v1.3.1) - 2023-09-11

- Fixing Bug in Precompiler when Blade::getPath() is null by [@joaopalopes24](https://github.com/joaopalopes24) in https://github.com/livewire/volt/pull/48

## [v1.3.0](https://github.com/livewire/volt/compare/v1.2.0...v1.3.0) - 2023-09-01

- Add `assertDontSeeVolt` Test Expectation by [@devajmeireles](https://github.com/devajmeireles) in https://github.com/livewire/volt/pull/44

## [v1.2.0](https://github.com/livewire/volt/compare/v1.1.1...v1.2.0) - 2023-08-31

- Adds `--class` option to `make:volt` command ([#43](https://github.com/livewire/volt/pull/43))

## [v1.1.1](https://github.com/livewire/volt/compare/v1.1.0...v1.1.1) - 2023-08-30

- Fixes `Segmentation fault` on `route:cache` ([#42](https://github.com/livewire/volt/pull/42))

## [v1.1.0](https://github.com/livewire/volt/compare/v1.0.0...v1.1.0) - 2023-08-28

- [1.x] Adds `title` function by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/34
- [1.x] Fixes sending "name" as property hook argument by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/37
- [1.x] Fixes already defined `with` by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/35
- [1.x] Fixes bottom script being included as template by [@nunomaduro](https://github.com/nunomaduro) in https://github.com/livewire/volt/pull/38

## [v1.0.0](https://github.com/livewire/volt/compare/v1.0.0-beta.7...v1.0.0) - 2023-08-24

- Stable release of Volt. For more information, please consult the [Volt documentation](https://livewire.laravel.com/docs/volt).

## [v1.0.0-beta.7](https://github.com/livewire/volt/compare/v1.0.0-beta.6...v1.0.0-beta.7) - 2023-08-22

- Adds `with` method to the class API ([#31](https://github.com/livewire/volt/pull/31))

## [v1.0.0-beta.6](https://github.com/livewire/volt/compare/v1.0.0-beta.5...v1.0.0-beta.6) - 2023-08-17

- Fixes `call to undefined function "opcache_invalidate"` when opcache is not installed ([#28](https://github.com/livewire/volt/pull/28))

## [v1.0.0-beta.5](https://github.com/livewire/volt/compare/v1.0.0-beta.4...v1.0.0-beta.5) - 2023-08-15

- Improves testbench development workflow ([#24](https://github.com/livewire/volt/pull/24))
- Fixes Class API when used with multiple fragments ([#26](https://github.com/livewire/volt/pull/26))

## [v1.0.0-beta.4](https://github.com/livewire/volt/compare/v1.0.0-beta.3...v1.0.0-beta.4) - 2023-08-08

- Adds `Volt::route` for full page components ([#22](https://github.com/livewire/volt/pull/22))
- Adds `assertSeeVolt` testing method ([#23](https://github.com/livewire/volt/pull/23))

## v1.0.0-beta.3 - 2023-08-03

- `uses` interface support ([#11](https://github.com/livewire/volt/pull/11))
- Example to `make:volt` prompt ([#12](https://github.com/livewire/volt/pull/12))
- SFC with Class API ([#15](https://github.com/livewire/volt/pull/15))
- UX when using Folio's pages with Volt's anonymous components ([#17](https://github.com/livewire/volt/pull/17))

## v1.0.0-beta.2 - 2023-07-31

- Windows support ([#6](https://github.com/livewire/volt/pull/6))

## v1.0.0-beta.1 - 2023-07-26

First pre-release.
