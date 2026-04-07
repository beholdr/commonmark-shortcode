# AGENTS

- This repo is a single PHP library, not an app. The main integration entrypoint is `src/ShortcodeExtension.php`, which wires `ShortcodeParser` and `ShortcodeRenderer` into a `league/commonmark` environment.
- Runtime support is `php ^8.4` and `league/commonmark ^2.8` from `composer.json`. The devcontainer runs PHP 8.5, so do not assume older PHP compatibility when editing.

## Commands

- Install/update deps: `composer update --prefer-dist --no-interaction`
- Tests: `composer test`
- Coverage: `composer test-coverage`
- Format: `composer format`
- Focused test runs use Pest directly, for example: `vendor/bin/pest tests/ArchTest.php` or `vendor/bin/pest --filter <name>`

## Verification

- Prefer `composer format` before `composer test`. CI has a workflow that auto-commits Pint fixes on pushed PHP changes, so unformatted code causes churn.
- CI tests against both `--prefer-lowest` and `--prefer-stable` dependency sets on PHP 8.4, on both Ubuntu and Windows. Avoid changes that rely on only the locked dependency graph.

## Tests And Constraints

- PHPUnit/Pest is configured via `phpunit.xml.dist` with random test execution order and strict failure settings: warnings, risky tests, empty suites, and test output all fail the run.
- Test bootstrap is `vendor/autoload.php`; JUnit output is written to `build/report.junit.xml`.
- `tests/ArchTest.php` forbids `dd`, `dump`, and `ray` anywhere in the codebase.

## Code Layout

- `src/ShortcodeRegistry.php` stores shortcode handlers.
- `src/ShortcodeParser.php` recognizes shortcode inline syntax and builds `ShortcodeNode` instances.
- `src/ShortcodeRenderer.php` resolves the registered handler and renders the node.
