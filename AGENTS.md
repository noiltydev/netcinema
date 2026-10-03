<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to enhance the user's satisfaction building Laravel applications.

## Foundational Context

This is a Laravel application. Read `composer.json` and `package.json` for the exact package versions in use, and abide by them.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

=== boost rules ===

## Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before any other approaches when dealing with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- The `search-docs` tool is perfect for all Laravel-related packages, including Laravel, Inertia, Livewire, Filament, Tailwind, Pest, Nova, Nightwatch, etc.
- You must use this tool to search for Laravel ecosystem documentation before falling back to other approaches.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries to start. For example: `['rate limiting', 'routing rate limiting', 'routing']`.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

=== php rules ===

## PHP

- Always use curly braces for control structures, even if it has one line.

### Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - <code-snippet>public function __construct(public GitHub $github) { }</code-snippet>
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

### Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless there is something very complex going on.

## PHPDoc Blocks

- Add useful array shape type definitions for arrays when appropriate.

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

=== tests rules ===

## Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== laravel/core rules ===

## Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

### Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

### Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

### Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

### URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

### Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

### Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

=== laravel/v12 rules ===

## Laravel 12

- Use the `search-docs` tool to get version-specific documentation.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

### Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

### Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

## Code Organization

- Traits must be placed in a `Concerns` subfolder (namespace) relative to their consumers (e.g. `App\Ai\Tools\Concerns\PlaysMusic`).
- Interfaces must be placed in a `Contracts` subfolder (namespace) relative to their consumers (e.g. `App\Ai\Tools\Contracts\SomeInterface`).

## Spelling

- Use US English spelling for all identifiers (PHP method/class/property names, TS/Vue variables and components), comments, docstrings, doc pages, and user-visible strings: `serialize` / `serializer` (not `serialise`), `color` (not `colour`), `initialize` (not `initialise`), `behavior` (not `behaviour`), `organize` / `organization`, `favorite`, `analyze`. Codebase — and PHP's SPL (`JsonSerializable`) — is uniformly American; don't drift British by reflex.

## Self-Explanatory Code

- Code should read on its own. If a piece of code needs a comment to be understood, that's a signal the code is wrong, not that the comment is needed — refactor it: extract a named helper, rename a variable to encode intent, lift a condition into a named flag, pull a block into a small function. Use a comment only when refactoring genuinely can't carry the intent (a hidden invariant, a workaround tied to a specific external bug, behaviour a reader would otherwise misjudge). Never write comments that narrate the next line, summarise the surrounding block, or restate what well-named identifiers already say.
- **The "hidden invariant" carve-out is not a license — it is a last resort.** If you catch yourself writing a comment to explain that two things are different, that an order matters, or that a value means something specific, the fix is a better identifier, not a sentence. `$recordingMbid = Arr::get($track, 'recording.id')` already says the link targets the recording; a line above it explaining that a release track and a recording are distinct entities is noise. Encode the distinction in the name, then delete the comment.
- **Never write comments in tests.** A test's method name plus its assertions are the documentation. A comment above an assertion restating what the assertion checks is always noise — rename the test instead.
- **A method name says what it acts on.** Never end a name on a bare preposition that stands in for the object — `registerFor()`, `resolveFor()`, `getFor()`, `handleWith()` make the reader ask "for what? for whom?". Name the thing: `registerOrganizationProvider($organization, …)`, not `registerFor($organization, …)`. A preposition is fine once the object is named (`resolveHomeUrlForUser()`), because the name already says what is resolved. Applies to PHP and TS/JS alike.
- Don't use single-letter variable names. The only allowed ones are `i` / `j` for loop counters, `h` for the test harness, and `$e` for the exception variable in `catch (Throwable|Exception|Error $e)` blocks (PHP's universal idiom — analogous to `e` for events in JS/TS event handlers). For everything else (callback params, destructured fields, lambda args, etc.) pick a name that says what it is.
- Never combine assignment with return. Always `$x = expr;` then `return $x;` on a separate line — `return $x = expr;` cramming two effects into one statement is forbidden in PHP, TS, and JS.

## PHP Conventions

- Always prefer Laravel's built-in helpers over custom implementations (e.g. `str()->plural()`, `Str::slug()`, `Arr::flatten()`, etc.). Do not reimplement what Laravel already provides.
- For guard clauses that throw on a condition, always reach for `throw_if($condition, ExceptionClass::class, ...$args)` / `throw_unless($condition, ExceptionClass::class, ...$args)` before writing `if (…) { throw new …; }`. The Laravel helpers read as a single declarative line, and the extra args are forwarded to the exception constructor. Plain `if`/`throw` is only correct when the throw branch has to do additional work (logging, side effects) before throwing.
- All methods must have explicit visibility (`public`, `protected`, or `private`). Never omit the visibility keyword, even on interface methods or static methods.
- Methods that don't reference `$this` must be declared `static`, unless the class is injectable (DI service) — in that case, prefer instance methods for better testability and decoupling.
- Always use the least visibility possible. Use `private` by default; only use `protected` or `public` when required by inheritance or external access.
- Never use `empty()` to check arrays. If the variable is known to be an array, use `!$array` instead. Don't compare to `[]` either.
- When a string contains quotes, don't use escaped double quotes (e.g. `"Playlist \"$name\" created"`). Use `sprintf()` with a single-quoted format string instead (e.g. `sprintf('Playlist "%s" created', $name)`).
- Never query models directly (e.g. `Model::query()->where(...)`) outside of the corresponding Repository class. All model lookups and queries must go through the appropriate Repository (e.g. `PlaylistRepository`, `SongRepository`).
- Repositories are read-only — they must never create, update, or delete records. Write operations belong in services or on the models directly.
- **A repository read that returns a `LazyCollection` is named `lazyGet*`** — the laziness goes in front of the verb, never instead of it and never as a suffix: `lazyGetWithoutMbid()`, not `lazyWithoutMbid()` (no verb) and not `getWithoutMbidLazily()` (buried). A bare `get*` promises a hydrated `Collection`, so a caller has to be told at the call site which one it is getting. Pair it with a separate `count*` method when a total is needed, since counting a lazy read would consume it.
- **Always use dependency injection when it is possible. Reaching for `app()` / `resolve()` to pull a class out of the container is a code smell** — the only routine exception is integration tests. Laravel builds controllers, commands, jobs, listeners, middleware, rules, observers registered via `#[ObservedBy]`, and anything else it resolves itself, so those take their collaborators as constructor parameters. Container resolution is acceptable only where constructor injection genuinely cannot reach: a factory that also takes runtime arguments, a static context, or an object the framework instantiates without the container. When you think you have such a case, say so explicitly rather than reaching for `app()` quietly.
- For config values needed by services, use the `#[Config('key')]` attribute on constructor parameters (from `Illuminate\Container\Attributes\Config`) — never call `config()` inside the service.
- All closure parameters must be type-hinted. Never use untyped closure arguments (e.g. `function (Builder $query)`, not `function ($query)`).
- When parsing or manipulating URLs, use `Illuminate\Support\Uri` instead of `parse_url()`.
- Do not add return type declarations to controller methods — controller responses are too dynamic/flexible for strict return types.
- Keep controllers thin. A controller method's job is: parse input → authorize → delegate → shape the response (resources/JSON). When a method starts accumulating data-loading orchestration, eager-load bookkeeping, multi-collection merges, or any multi-step domain logic, push that work into a service. Prefer extending an existing service in the same domain (e.g. `MediaBrowser` for browse-side folder operations) over creating a new one. Services return raw domain objects (Collections, Models) — Resource/JSON wrapping stays in the controller. Authorization stays in the controller too, so unauthorized requests fail before expensive data loads.
- NEVER perform direct Eloquent writes from a controller — no `$model->update(...)`, `->save()`, `->create(...)`, `->delete()`, `->fill()->save()`, relationship `attach`/`detach`/`sync`, or `Model::query()->update/delete`. Every persistence operation goes through a service method (the service may write on the model directly). Controllers only read (via repositories), authorize, and delegate. Even a one-line `$model->update($changes)` belongs in a service — it's the seam where validation, events, and transactions later live. When the obvious service method is a full-update path that doesn't fit (e.g. a partial patch, or one with side effects like folder re-attachment or rule-wiping you don't want), add a focused service method (e.g. `PlaylistService::patchDetails`) rather than writing inline or misusing the heavy one.
- Value objects in `app/Values/` must use a `final readonly class` with a `private __construct(...)` and a `public static function make(...): self` factory. Call sites construct them via `Foo::make(...)`, never `new Foo(...)`. The reference shape is `App\Values\Radio\RadioStationCreateData`.

## Testing Assertions

- When asserting two Eloquent models are the same, use `assertTrue($modelA->is($modelB))` instead of comparing IDs.
- Never resort to `ReflectionClass` / `ReflectionProperty` / `ReflectionMethod` in tests to peek at private state, instantiate classes with private constructors, or invoke private methods. If a test "needs" reflection, the smell is the test or the code: the production class should expose what's necessary via a public factory, the dependency should be injectable, or the test should construct the dependency itself (TOTP and similar deterministic primitives need no shared instance). Refactor instead of reaching for reflection.

## Model Factories

- Use `createOne()` to create a single model and `createMany()` to create a collection. Never use `create()` directly, as its return type is ambiguous (single model or collection depending on arguments).
- Wire parent relationships with `->for($parent)` instead of passing foreign keys in the attributes array. For polymorphic relations, pass the relation name as the second argument: `->for($song, 'rateable')` (sets both `*_id` and `*_type`). Prefer `Rating::factory()->for($user)->for($song, 'rateable')->createOne(['rating' => 5])` over the equivalent `createOne(['user_id' => $user->id, 'rateable_id' => $song->id, 'rateable_type' => $song->getMorphClass(), 'rating' => 5])`.

## Test Class Namespacing

- Unit test classes must mirror the namespace of the class under test. Replace `App\` with `Tests\Unit\` and add a `Test` suffix (e.g. `App\Ai\Services\FavoriteableEntityResolver` → `Tests\Unit\Ai\Services\FavoriteableEntityResolverTest`).
- The test file path must match the namespace (e.g. `tests/Unit/Ai/Services/FavoriteableEntityResolverTest.php`).

## Linting & Static Analysis

- When running lint or static analysis (backend or frontend), fix ALL warnings and errors to ensure 100% clean output — even pre-existing issues unrelated to current changes.
- **Before creating or updating any PR that touches PHP files**, run all backend gates locally and confirm green: `composer cs` (format check), `composer lint` (mago lint), `composer analyze` (phpstan). Do NOT rely on the pre-commit hook alone — it only catches formatting. Lint and static-analysis failures must be caught locally, not by CI, so the PR isn't created/updated red.
</laravel-boost-guidelines>
