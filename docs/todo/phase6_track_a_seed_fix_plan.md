# Phase 6 Track A – Resource Seeding Fix Plan
_Date: 2025-10-02_

## 1. Issue Summary
- `php artisan migrate:fresh --seed` fails during `ResourceSeeder` because `resources.file_path` is defined as NOT NULL in `database/migrations/2025_10_01_060100_create_resources_table.php`.
- New resource entries include webinar/audio assets with `file_path = null` and `external_url` populated, causing integrity violations.

## 2. Root Causes
- Migration still enforces non-null constraint on `file_path`; schema was not adjusted to allow nullable values when external assets are used.
- Seeder assumes `file_path` may be null when `external_url` is present, mismatch vs schema.

## 3. Remediation Strategy
1. **Schema Update**
   - Create follow-up migration altering `resources.file_path` to nullable and adding check (app logic) to ensure either `file_path` or `external_url` is provided.
   - Confirm `external_url` column allows null to support local assets.
2. **Model Guardrails**
   - Update `app/Models/Resource.php` accessor/mutator or validation helper to ensure at least one source is set before persistence (optional but recommended).
3. **Seeder Adjustments**
   - For external-only assets, set `file_path` to placeholder (e.g., empty string) or restructure seed data once schema change is applied.
4. **Testing**
   - Rerun `php artisan migrate:fresh --seed` after schema/seeder adjustments.
   - Add feature test verifying resource creation with external URL only.

## 4. Execution Steps
- Step A: Draft migration `2025_10_02_XXXXXX_update_resources_nullable_path.php` to modify column to nullable.
- Step B: Update `Resource` model (optional validation helper) and adjust seeder to ensure either path/URL set.
- Step C: Rerun migrations & seeds, capture logs.
- Step D: Implement automated test coverage if feasible within Phase 6 timeline.

## 5. Validation Checklist
- [ ] Migration runs without errors; schema shows `file_path` nullable.
- [ ] `php artisan migrate:fresh --seed` completes successfully.
- [ ] New feature test passes (if added).
- [ ] Documentation (`docs/todo/phase6_data_integration_subplan.md`) updated if constraints change.
