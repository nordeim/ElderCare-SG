# Cost Estimator UX & Pricing Assumptions

_Last updated: 2025-10-01_

## Objectives
- Provide caregivers with an approachable way to understand monthly costs before contacting the team.
- Highlight how subsidies, transport, and add-ons influence the total so families can prioritise.
- Encourage deeper engagement with contextual prompts (assessment+estimator pairing).

## Inputs & Controls
- **Days per week slider (1–6)**: Maps to average daily rate `pricing.dailyRate` and `pricing.weeksPerMonth` (currently 4.3 weeks).
- **Transport toggle**: Adds `pricing.transportFee` per active day when true.
- **Add-ons checklist**: Pulls from `pricing.addOns` array (meals, therapy, concierge). Multiple add-ons compound cost.
- **Subsidy selector**: References `subsidies` collection with `rate` percentage applied to subtotal (base + transport + add-ons).

## Calculation Flow
1. **Base monthly** = `daysPerWeek * pricing.weeksPerMonth * pricing.dailyRate`.
2. **Transport monthly** = `includeTransport ? daysPerWeek * pricing.weeksPerMonth * pricing.transportFee : 0`.
3. **Add-ons monthly** = `sum(selectedAddOns.amount) * pricing.weeksPerMonth` (treated as weekly add-ons).
4. **Subtotal** = Base + Transport + Add-ons.
5. **Subsidy savings** = `subtotal * subsidy.rate` (default `0` when no subsidy).
6. **Total monthly** = `subtotal - subsidySavings`.
7. **Effective daily rate** = `totalMonthly / (daysPerWeek * pricing.weeksPerMonth)` (guard against divide-by-zero when slider moves to 0).

## Edge Cases & Safeguards
- Slider never hits 0; prevents zero-day divide issues.
- If add-on list empty, component renders empty state text and equals zero.
- Subsidy dropdown fallback ensures `none` is always available.
- CTA disabled state handled upstream if estimator section hidden.

## Analytics
- `estimator.open`: Fired when section initialises in viewport (`costEstimatorComponent.init()`).
- `estimator.update`: Debounced event carrying `{ daysPerWeek, transport, addOns, subsidyKey }` whenever a control changes.
- `assessment.prompt_click`: Signals estimator CTA pressed from contextual prompts, enabling funnel attribution.

## Future Enhancements
- Pull pricing configuration from `app/Services/CostEstimatorService.php` once finance finalises subsidy matrices.
- Introduce slider labels for weekday vs. weekend weighting when data available.
- Allow scenario saving by storing estimator state in localStorage for returning visitors.
- Localise copy for Chinese (`resources/lang/zh/`).
