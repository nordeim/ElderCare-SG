const clone = (value) => JSON.parse(JSON.stringify(value));

const defaultAnalyticsEmitter = (namespace) => {
    const prefix = namespace ? `${namespace}` : 'assessment';

    return (event, detail = {}) => {
        const name = `${prefix}.${event}`;

        if (typeof window.dispatchEvent === 'function') {
            window.dispatchEvent(new CustomEvent(name, { detail }));
        }

        if (typeof window.plausible === 'function') {
            window.plausible(name, { props: detail });
        }
    };
};

const buildInitialAnswers = (steps) => {
    return steps.reduce((accumulator, step) => {
        accumulator[step.id] = step.type === 'multi' ? [] : null;
        return accumulator;
    }, {});
};

const rules = {
    active_day: (answers) => answers.mobility === 'independent' && answers.cognitive_support === 'engaged',
    memory_care: (answers) => answers.cognitive_support === 'dementia',
    supportive_care: (answers) =>
        answers.mobility === 'assistance' ||
        answers.mobility === 'full_support' ||
        (Array.isArray(answers.health_considerations) && answers.health_considerations.includes('mobility_aids')),
    respite_support: (answers) =>
        answers.transportation === 'yes' ||
        (Array.isArray(answers.caregiver_goals) && answers.caregiver_goals.includes('respite')),
};

const fallbackSegmentKey = 'exploration';

const determineSegmentKey = (answers) => {
    if (rules.memory_care(answers)) {
        return 'memory_care';
    }

    if (rules.supportive_care(answers)) {
        return 'supportive_care';
    }

    if (rules.respite_support(answers)) {
        return 'respite_support';
    }

    if (rules.active_day(answers)) {
        return 'active_day';
    }

    return fallbackSegmentKey;
};

const createSummary = (segmentKey, segmentsConfig, summaryConfig) => {
    const segment = segmentsConfig[segmentKey] ?? segmentsConfig[fallbackSegmentKey];

    return {
        segmentKey,
        segment,
        fallbackLabel: summaryConfig.cta_fallback,
        fallbackCta: '#booking',
    };
};

export const assessmentFlow = (config) => {
    const steps = config.steps ?? [];
    const labels = config.labels ?? {};
    const emitter = defaultAnalyticsEmitter(config.analytics?.event_namespace);

    return {
        isOpen: false,
        state: 'intro',
        currentIndex: 0,
        answers: buildInitialAnswers(steps),
        summary: null,
        submissionState: 'idle',
        submissionError: null,
        get totalSteps() {
            return steps.length;
        },
        get currentStep() {
            if (this.state !== 'questions') {
                return null;
            }

            return steps[this.currentIndex] ?? null;
        },
        get options() {
            if (!this.currentStep) {
                return [];
            }

            const options = this.currentStep.options ?? [];
            const extras = this.currentStep.options_extra ?? [];

            return [...options, ...extras];
        },
        get canProceed() {
            if (this.state !== 'questions' || !this.currentStep) {
                return false;
            }

            const answer = this.answers[this.currentStep.id];

            if (this.currentStep.type === 'multi') {
                return Array.isArray(answer) && answer.length > 0;
            }

            return Boolean(answer);
        },
        get isFinalStep() {
            return this.currentIndex === this.totalSteps - 1;
        },
        get progressLabel() {
            return `${labels.step_prefix ?? 'Step'} ${this.currentIndex + 1} ${labels.step_separator ?? 'of'} ${this.totalSteps}`;
        },
        open() {
            this.isOpen = true;
            this.state = 'intro';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('open', { segmentKey: null });
        },
        close() {
            this.isOpen = false;
            this.state = 'intro';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('close', { segmentKey: null });
        },
        start() {
            this.state = 'questions';
            this.currentIndex = 0;
            emitter('start', { step: steps[0]?.id });
        },
        skip() {
            this.state = 'summary';
            const segmentKey = determineSegmentKey(this.answers);
            this.summary = createSummary(segmentKey, config.segments, config.summary);
            emitter('skip', {
                segmentKey,
                summary: this.summary,
                segment: this.summary.segment,
                answers: clone(this.answers),
            });
        },
        restart() {
            const previousSegmentKey = this.summary?.segmentKey ?? null;
            const previousSegment = this.summary?.segment ?? null;
            this.state = 'questions';
            this.currentIndex = 0;
            this.answers = buildInitialAnswers(steps);
            this.summary = null;
            this.submissionState = 'idle';
            this.submissionError = null;
            emitter('restart', {
                step: steps[0]?.id,
                previousSegmentKey,
                previousSegment,
            });
        },
        back() {
            if (this.currentIndex === 0) {
                this.state = 'intro';
                emitter('back_to_intro');
                return;
            }

            this.currentIndex -= 1;
            emitter('step_back', { step: steps[this.currentIndex]?.id });
        },
        next() {
            if (!this.currentStep) {
                return;
            }

            emitter('step_submit', {
                step: this.currentStep.id,
                answer: clone(this.answers[this.currentStep.id]),
            });

            if (this.isFinalStep) {
                this.complete();
                return;
            }

            this.currentIndex += 1;
            emitter('step_enter', { step: steps[this.currentIndex]?.id });
        },
        complete() {
            const segmentKey = determineSegmentKey(this.answers);
            this.summary = createSummary(segmentKey, config.segments, config.summary);
            this.state = 'summary';
            this.submissionState = 'pending';
            this.submissionError = null;

            emitter('complete', {
                segmentKey,
                summary: this.summary,
                segment: this.summary.segment,
                answers: clone(this.answers),
            });

            this.submitOutcome(segmentKey).catch(() => {
                // handled within submitOutcome
            });
        },
        toggleOption(value) {
            if (!this.currentStep) {
                return;
            }

            if (this.currentStep.type === 'multi') {
                const answerSet = new Set(this.answers[this.currentStep.id] ?? []);

                if (answerSet.has(value)) {
                    answerSet.delete(value);
                } else {
                    answerSet.add(value);
                }

                this.answers[this.currentStep.id] = Array.from(answerSet);
            } else {
                this.answers[this.currentStep.id] = this.answers[this.currentStep.id] === value ? null : value;
            }
        },
        isSelected(value) {
            if (!this.currentStep) {
                return false;
            }

            const answer = this.answers[this.currentStep.id];

            if (this.currentStep.type === 'multi') {
                return Array.isArray(answer) && answer.includes(value);
            }

            return answer === value;
        },
        async submitOutcome(segmentKey) {
            if (typeof window.axios !== 'function') {
                this.submissionState = 'skipped';
                return;
            }

            try {
                await window.axios.post('/assessment-insights', {
                    answers: clone(this.answers),
                    segment_key: segmentKey,
                });

                this.submissionState = 'success';
                emitter('submitted', {
                    segmentKey,
                    summary: this.summary,
                    segment: this.summary?.segment,
                });
            } catch (error) {
                this.submissionState = 'error';
                this.submissionError = error?.message ?? 'Submission failed';
                emitter('submit_error', {
                    segmentKey,
                    error: this.submissionError,
                });
            }
        },
    };
};

if (typeof window !== 'undefined') {
    window.assessmentFlow = assessmentFlow;
}
