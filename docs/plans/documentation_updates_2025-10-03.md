# Documentation Updates — 2025-10-03

## Summary
- **Scope**: Align public-facing documentation with current tooling (PHP 8.3, Node.js 22), shipped guided needs assessment features, QA automation coverage, and architecture diagrams.
- **Files Updated**:
  - `Dockerfile`
  - `README.md`
  - `AGENT.md`
  - `Project_Architecture_Document.md`
  - `docs/todo/documentation_alignment_todo.md`

## Change Details
### Dockerfile
- Updated NodeSource setup script from `setup_20.x` to `setup_22.x` ensuring container builds use Node.js 22 (`Dockerfile`).

### README.md
- Refreshed technology stack badge and table to state Node.js 22 and PHP 8.3.
- Clarified local environment steps requiring PHP 8.3 and Node.js 22.
- Documented QA automation accurately (PHPUnit phase6 group, axe, Lighthouse) and noted Playwright as an ad-hoc/manual script.
- Highlighted delivered guided needs assessment in phase progress and roadmap sections, adjusting Phase 8 focus to enhancements.

### AGENT.md
- Updated stack table to reference Node.js 22 and Docker-based workflow.
- Marked `AssessmentService` and guided assessment UX as implemented throughout service table, feature breakdown, and roadmap.
- Expanded frontend component list to include assessment modal/prompt surfaces.
- Refined testing strategy to mirror current automation and manual steps (PHPUnit, Vitest, optional Playwright, axe, Lighthouse).
- Reordered upcoming milestones to reflect present priorities (Docs, Data Hardening, Guided Assessment Enhancements, CMS).

### Project_Architecture_Document.md
- Modernized stack description (Docker Compose workflow, Node.js 22 requirement, Redis caching).
- Expanded HTTP layer overview to include `LocaleController`, `AssessmentController`, and `AvailabilityController`.
- Documented additional models (`Staff`, `Faq`, `Resource`) and seeders supporting UI components.
- Added sections for `AvailabilityService`, `AssessmentService`, and analytics configuration.
- Detailed new data flows for assessment submission and availability polling.
- Updated QA pipeline to reference GitHub Actions workflow and local QA scripts.
- Refined future enhancements to emphasize assessment extensions, performance work, documentation, and CMS/internationalization goals.
- Restored and validated architecture mermaid diagram syntax.

### docs/todo/documentation_alignment_todo.md
- Captured execution plan listing required edits across documentation and Dockerfile prior to implementation.

## Verification & Follow-up
- Verified mermaid diagrams renderable on GitHub and accurate to the current codebase (`README.md`, `Project_Architecture_Document.md`).
- Confirmed no remaining Node.js 20 references in updated documents.
- Future work: monitor QA workflow to add Playwright smoke when automated coverage is extended.
