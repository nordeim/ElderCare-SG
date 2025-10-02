# Documentation Alignment ToDo

## Environment & Tooling Updates
- [ ] Update `Dockerfile` NodeSource install script to `setup_22.x` and confirm accompanying commentary reflects Node 22.
- [ ] Propagate Node 22 + PHP 8.3 requirements across documentation where toolchain versions are cited.

## `README.md`
- [ ] Refresh technology stack table and badges to show Node 22 alongside PHP 8.3.
- [ ] Update setup instructions and any shell snippets to match Node 22 tooling.
- [ ] Revise roadmap/status sections so Guided Needs Assessment is marked as delivered and upcoming phases reflect current priorities.
- [ ] Correct QA automation description to mirror `.github/workflows/qa-ci.yml` (PHPUnit phase6 group, axe, Lighthouse) and note Playwright as future work if applicable.
- [ ] Ensure narrative around homepage flows references existing assessment components where relevant.

## `AGENT.md`
- [ ] Adjust stack table and workflow notes to Node 22 and Docker-based tooling (Makefile), removing outdated Sail emphasis.
- [ ] Mark Guided Needs Assessment and AssessmentService as implemented; update roadmap milestones accordingly.
- [ ] Reconcile testing strategy section with active CI steps and planned coverage (e.g., Playwright pending).
- [ ] Highlight availability and analytics services now present in codebase.

## `Project_Architecture_Document.md`
- [ ] Update stack/tooling section to remove Sail-specific references and align with Docker Compose + Makefile workflow.
- [ ] Replace legacy `temp-laravel` paths and `initialize_database.sql` notes with current repo structures (seeders, config paths).
- [ ] Document `AssessmentService`, `AvailabilityService`, and analytics logging channel in services/integrations section.
- [ ] Refresh QA pipeline narrative to reference `.github/workflows/qa-ci.yml` and npm scripts instead of `launch_laravel_dev_server.sh` orchestration.
- [ ] Adjust future enhancements to reflect completed assessment feature and emphasize upcoming CMS/performance/localization work.

## Verification & Follow-up
- [ ] After edits, proofread for consistency and run `npm run lint:accessibility` or other relevant checks if documentation references change scripts.
- [ ] Communicate summary of changes and any residual gaps to maintainers once updates land.
