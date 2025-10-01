# Phase 3 Sub-Plan — Immersive Virtual Tour & Media Storytelling
_Date: 2025-10-01_

## Objectives
- Replace the static tour placeholder with an interactive, accessible media experience.
- Highlight staff storytelling and hotspot exploration while meeting accessibility and analytics requirements.

## Workstreams & Tasks

### 1. Tour Data & Assets
- **Staff Data (`database/seeders/StaffSeeder.php`)**
  - [ ] Ensure bios include role, alt text, and featured flag for carousel.
  - [ ] Add hotspot assignments if specific staff guide sections.
- **Hotspot Schema (`resources/data/tour_hotspots.json`)** *(new)*
  - [ ] Define coordinates, titles, descriptions, media references, ARIA labels, and optional translations.
- **Media Transcripts (`public/media/transcripts/<tour-id>.md`)** *(new)*
  - [ ] Provide transcript per video with headings/timecodes.
  - [ ] Reference caption files and accessibility notes.

### 2. Components & Layout
- **Virtual Tour Component (`resources/views/components/virtual-tour.blade.php`)** *(new)*
  - [ ] Modal/lightbox with focus trap, close controls, and fallback image.
  - [ ] Video player with transcript toggle; integrates hotspot navigation.
- **Staff Carousel (`resources/views/components/staff-carousel.blade.php`)** *(new)*
  - [ ] Responsive cards, keyboard-accessible controls, analytics hooks.
- **Homepage Integration (`resources/views/home.blade.php`)** *(update)*
  - [ ] Replace static tour section, mount new components, ensure localized headings.

### 3. JavaScript Interactivity
- **Tour Store (`resources/js/modules/tour.js`)** *(new)*
  - [ ] Alpine-based state for modal, hotspots, playback.
  - [ ] Keyboard navigation (arrow/tab), analytics events (`tour.open`, `tour.hotspot`, `tour.complete`).
- **App Entry (`resources/js/app.js`)** *(update)*
  - [ ] Import `./modules/tour` after analytics bootstrap.

### 4. Styles & Tailwind Utilities (`resources/css/app.css`)
- [ ] Add classes for modal backdrop, hotspot markers, carousel layout.
- [ ] Respect `prefers-reduced-motion` and maintain contrast.

### 5. Analytics & Documentation
- **Analytics Reference (`docs/analytics.md`)** *(update)*
  - [ ] Document tour-related events and Plausible goals.
- **UX Specifications (`docs/ux/virtual_tour.md`)** *(new)*
  - [ ] Outline hotspot schema, media requirements, transcript workflow.
- **Roadmap Status (`docs/plans/master_todo_roadmap.md`)** *(update)*
  - [ ] Log Phase 3 progress and outstanding asset needs.

### 6. Testing & QA
- **Feature Test (`tests/Feature/VirtualTourTest.php`)** *(new)*
  - [ ] Assert tour section renders with seeded hotspots/staff data.
- **JS Unit Test (`resources/js/tests/tour.spec.js`)** *(optional)*
  - [ ] Validate store behaviors (modal toggle, hotspot focus).
- **QA Checklist (`docs/qa/scaffold-checklist.md`)** *(update)*
  - [ ] Add manual cases for modal accessibility, hotspot navigation, transcript availability, analytics verification.

## Dependencies & Risks
- Confirm availability of video assets, transcripts, and staff bios before implementation.
- Coordinate with design/content for hotspot copy and imagery.
- Monitor performance impact; lazy load media to maintain FCP budget.

## Milestones
1. Data & asset preparation completed and reviewed with content team.
2. Components/JS/styles implemented with local QA pass.
3. Analytics/tests/docs updated; Phase 3 status logged in roadmap.
