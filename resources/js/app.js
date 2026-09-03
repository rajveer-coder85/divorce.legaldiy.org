const header = document.querySelector('[data-header]');
const menuToggle = document.querySelector('[data-menu-toggle]');
const navigation = document.querySelector('[data-navigation]');

const updateHeader = () => header?.classList.toggle('scrolled', window.scrollY > 18);
updateHeader();
window.addEventListener('scroll', updateHeader, { passive: true });

const setMenuOpen = (open) => {
    menuToggle?.setAttribute('aria-expanded', String(open));
    navigation?.classList.toggle('open', open);

    const label = menuToggle?.querySelector('.sr-only');
    if (label) label.textContent = open ? 'Close navigation' : 'Open navigation';
};

menuToggle?.addEventListener('click', () => {
    const open = menuToggle.getAttribute('aria-expanded') === 'true';
    setMenuOpen(!open);
});

navigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    setMenuOpen(false);
}));

document.addEventListener('keydown', (event) => {
    if (event.key !== 'Escape' || menuToggle?.getAttribute('aria-expanded') !== 'true') return;
    setMenuOpen(false);
    menuToggle.focus();
});

document.addEventListener('click', (event) => {
    if (menuToggle?.getAttribute('aria-expanded') !== 'true') return;
    if (header?.contains(event.target)) return;
    setMenuOpen(false);
});

window.matchMedia('(min-width: 960px)').addEventListener('change', (event) => {
    if (event.matches) setMenuOpen(false);
});

const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('in-view');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.12 });

document.querySelectorAll('.reveal').forEach((element) => revealObserver.observe(element));

const topicToggles = [...document.querySelectorAll('[data-topic-toggle]')];
const simpleToggle = document.querySelector('[data-simple-toggle]');

const updateCheckpoint = () => {
    topicToggles.forEach((toggle) => {
        const topic = toggle.value;
        const card = document.querySelector(`[data-topic="${topic}"]`);
        const check = document.querySelector(`[data-conditional-check="${topic}"]`);
        card?.classList.toggle('selected', toggle.checked);
        if (check) check.hidden = !toggle.checked;
        if (!toggle.checked) {
            const checkbox = check?.querySelector('input');
            if (checkbox) checkbox.checked = false;
        }
    });

    document.querySelector('[data-topic="simple"]')?.classList.toggle('selected', simpleToggle?.checked ?? false);
    const visibleChecks = [...document.querySelectorAll('[data-check-item]')]
        .filter((checkbox) => !checkbox.closest('[hidden]'));
    const complete = visibleChecks.filter((checkbox) => checkbox.checked).length;
    const total = visibleChecks.length;
    const status = document.querySelector('[data-checkpoint-status]');
    const readyButton = document.querySelector('[data-ready-button]');

    if (status) {
        status.querySelector('span').textContent = `${complete} of ${total} complete`;
        status.querySelector('i').style.width = `${(complete / total) * 100}%`;
    }

    const ready = complete === total;
    readyButton?.classList.toggle('disabled', !ready);
    readyButton?.setAttribute('aria-disabled', String(!ready));
};

topicToggles.forEach((toggle) => toggle.addEventListener('change', () => {
    if (toggle.checked && simpleToggle) simpleToggle.checked = false;
    updateCheckpoint();
}));

simpleToggle?.addEventListener('change', () => {
    if (simpleToggle.checked) topicToggles.forEach((toggle) => { toggle.checked = false; });
    updateCheckpoint();
});

document.querySelectorAll('[data-check-item]').forEach((checkbox) => checkbox.addEventListener('change', updateCheckpoint));
updateCheckpoint();

const scenarioTabs = [...document.querySelectorAll('[data-scenario-tab]')];
const selectScenario = (name) => {
    scenarioTabs.forEach((tab) => {
        const active = tab.dataset.scenarioTab === name;
        tab.classList.toggle('active', active);
        tab.setAttribute('aria-selected', String(active));
        tab.setAttribute('tabindex', active ? '0' : '-1');
    });
    document.querySelectorAll('[data-scenario-panel]').forEach((panel) => {
        panel.hidden = panel.dataset.scenarioPanel !== name;
    });
};

scenarioTabs.forEach((tab, index) => {
    tab.addEventListener('click', () => selectScenario(tab.dataset.scenarioTab));
    tab.addEventListener('keydown', (event) => {
        if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
        event.preventDefault();
        const direction = event.key === 'ArrowRight' ? 1 : -1;
        const next = scenarioTabs[(index + direction + scenarioTabs.length) % scenarioTabs.length];
        selectScenario(next.dataset.scenarioTab);
        next.focus();
    });
});

const journeyFlow = document.querySelector('[data-journey-flow]');

if (journeyFlow) {
    const steps = [...journeyFlow.querySelectorAll('[data-journey-step]')];
    const topics = new Set();
    let currentStep = '1';
    let agreementStatus = '';
    let resendTimer;

    const topicNames = {
        children: 'Children and their arrangements',
        property: 'Property and shared assets',
        maintenance: 'Spousal maintenance',
        simple: 'A simple arrangement with no additional issues',
    };

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const formMessage = journeyFlow.querySelector('[data-form-message]');

    const showMessage = (message = '', type = 'error') => {
        if (!formMessage) return;
        formMessage.textContent = message;
        formMessage.className = `journey-form-message ${type}`;
        formMessage.hidden = !message;
    };

    const request = async (url, payload) => {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(payload),
        });
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const validationMessage = Object.values(data.errors || {}).flat()[0];
            const error = new Error(validationMessage || data.message || 'Something went wrong. Please try again.');
            error.retryAfter = data.retry_after;
            throw error;
        }

        return data;
    };

    const setBusy = (button, busy, busyLabel) => {
        if (!button) return;
        if (busy) button.dataset.originalLabel = button.innerHTML;
        button.disabled = busy;
        button.innerHTML = busy ? busyLabel : button.dataset.originalLabel;
    };

    const validateStep = (step) => {
        const panel = journeyFlow.querySelector(`[data-journey-step="${step}"]`);
        const fields = [...panel.querySelectorAll('input, select, textarea')];
        const invalid = fields.find((field) => !field.checkValidity());
        invalid?.reportValidity();
        return !invalid;
    };

    const showStep = (step) => {
        currentStep = String(step);
        showMessage();
        steps.forEach((panel) => {
            const active = panel.dataset.journeyStep === currentStep;
            panel.hidden = !active;
            panel.classList.toggle('active', active);
        });

        const numericStep = currentStep === 'unsure' ? 1 : currentStep === 'success' ? 6 : Number(currentStep);
        const percent = Math.round((numericStep / 6) * 100);
        journeyFlow.querySelector('[data-journey-step-label]').textContent = currentStep === 'success' ? 'Complete' : `Step ${numericStep} of 6`;
        journeyFlow.querySelector('[data-journey-percent]').textContent = `${percent}%`;
        journeyFlow.querySelector('[data-journey-progress]').style.width = `${percent}%`;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    journeyFlow.querySelectorAll('[data-journey-answer]').forEach((answer) => {
        answer.addEventListener('click', () => {
            agreementStatus = answer.dataset.journeyAnswer;
            journeyFlow.querySelectorAll('[data-journey-answer]').forEach((item) => {
                const selected = item === answer;
                item.classList.toggle('selected', selected);
                item.setAttribute('aria-pressed', String(selected));
            });

            window.setTimeout(() => {
                showStep(agreementStatus === 'agree' ? 2 : 'unsure');
            }, window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 0 : 420);
        });
    });

    journeyFlow.querySelector('[data-journey-continue-unsure]')?.addEventListener('click', () => showStep(2));
    journeyFlow.querySelector('[data-journey-restart]')?.addEventListener('click', () => {
        agreementStatus = '';
        journeyFlow.querySelectorAll('[data-journey-answer]').forEach((item) => {
            item.classList.remove('selected');
            item.setAttribute('aria-pressed', 'false');
        });
        showStep(1);
    });

    const identityNumber = journeyFlow.querySelector('[data-identity-number]');
    journeyFlow.querySelectorAll('input[name="identity_type"]').forEach((radio) => {
        radio.addEventListener('change', () => {
            const passport = radio.checked && radio.value === 'passport';
            if (!passport && !radio.checked) return;
            journeyFlow.querySelector('[data-identity-label]').textContent = passport ? 'Passport number' : 'NRIC number';
            journeyFlow.querySelector('[data-identity-help]').textContent = passport
                ? 'Enter the passport number using letters and numbers only. We encrypt this number when it is saved.'
                : 'Enter the 12 digits shown on your Malaysian identity card. We encrypt this number when it is saved.';
            identityNumber.placeholder = passport ? 'e.g. A12345678' : 'e.g. 900101-14-5678';
            identityNumber.inputMode = passport ? 'text' : 'numeric';
            identityNumber.value = '';
        });
    });

    identityNumber?.addEventListener('input', () => {
        if (journeyFlow.querySelector('input[name="identity_type"]:checked')?.value !== 'nric') return;
        const digits = identityNumber.value.replace(/\D/g, '').slice(0, 12);
        identityNumber.value = [digits.slice(0, 6), digits.slice(6, 8), digits.slice(8, 12)].filter(Boolean).join('-');
    });

    const startResendCountdown = (seconds = 60) => {
        const button = journeyFlow.querySelector('[data-resend-tac]');
        const label = journeyFlow.querySelector('[data-resend-countdown]');
        window.clearInterval(resendTimer);
        let remaining = seconds;
        button.disabled = true;
        label.textContent = `(${remaining}s)`;
        resendTimer = window.setInterval(() => {
            remaining -= 1;
            label.textContent = remaining > 0 ? `(${remaining}s)` : '';
            if (remaining <= 0) {
                window.clearInterval(resendTimer);
                button.disabled = false;
            }
        }, 1000);
    };

    const sendTac = async (button) => {
        if (!validateStep(2)) return;
        const email = journeyFlow.querySelector('[name="email"]').value.trim();
        const fullName = journeyFlow.querySelector('[name="full_name"]').value.trim();
        setBusy(button, true, 'Sending…');
        showMessage();
        try {
            const data = await request(journeyFlow.dataset.tacSendUrl, { email, full_name: fullName });
            journeyFlow.querySelector('[data-tac-email]').textContent = email;
            showStep(3);
            showMessage(data.message, 'success');
            startResendCountdown(data.resend_after || 60);
        } catch (error) {
            showMessage(error.message);
            if (error.retryAfter) startResendCountdown(error.retryAfter);
        } finally {
            setBusy(button, false);
        }
    };

    journeyFlow.querySelector('[data-send-tac]')?.addEventListener('click', (event) => sendTac(event.currentTarget));
    journeyFlow.querySelector('[data-resend-tac]')?.addEventListener('click', (event) => sendTac(event.currentTarget));

    journeyFlow.querySelector('[data-verify-tac]')?.addEventListener('click', async (event) => {
        if (!validateStep(3)) return;
        const button = event.currentTarget;
        setBusy(button, true, 'Verifying…');
        showMessage();
        try {
            await request(journeyFlow.dataset.tacVerifyUrl, {
                email: journeyFlow.querySelector('[name="email"]').value.trim(),
                code: journeyFlow.querySelector('[name="tac_code"]').value.trim(),
            });
            window.clearInterval(resendTimer);
            showStep(4);
        } catch (error) {
            showMessage(error.message);
        } finally {
            setBusy(button, false);
        }
    });

    journeyFlow.querySelector('[data-profile-next]')?.addEventListener('click', () => {
        if (validateStep(4)) showStep(5);
    });

    const nextButton = journeyFlow.querySelector('[data-journey-next]');
    journeyFlow.querySelectorAll('[data-journey-topic]').forEach((topicButton) => {
        topicButton.addEventListener('click', () => {
            const topic = topicButton.dataset.journeyTopic;
            const isSimple = topic === 'simple';
            const wasSelected = topics.has(topic);

            if (isSimple) {
                topics.clear();
                journeyFlow.querySelectorAll('[data-journey-topic]').forEach((item) => item.classList.remove('selected'));
            } else {
                topics.delete('simple');
                journeyFlow.querySelector('[data-journey-topic="simple"]')?.classList.remove('selected');
            }

            if (!isSimple && wasSelected) topics.delete(topic);
            else if (!wasSelected) topics.add(topic);

            journeyFlow.querySelectorAll('[data-journey-topic]').forEach((item) => {
                const selected = topics.has(item.dataset.journeyTopic);
                item.classList.toggle('selected', selected);
                item.setAttribute('aria-pressed', String(selected));
            });
            nextButton.disabled = topics.size === 0;
        });
    });

    nextButton?.addEventListener('click', () => {
        const identityType = journeyFlow.querySelector('[name="identity_type"]:checked').value;
        const identityValue = journeyFlow.querySelector('[name="identity_number"]').value;
        const maskedIdentity = identityValue.length > 4 ? `${'•'.repeat(6)}${identityValue.slice(-4)}` : 'Provided';
        const rows = [
            ['Full legal name', journeyFlow.querySelector('[name="full_name"]').value],
            ['Verified email', journeyFlow.querySelector('[name="email"]').value],
            [identityType === 'nric' ? 'NRIC' : 'Passport', maskedIdentity],
            ['Agreement to divorce', agreementStatus === 'agree' ? 'Both agree' : 'Not yet agreed'],
            ['Topics', [...topics].map((topic) => topicNames[topic]).join(', ')],
        ];
        const review = journeyFlow.querySelector('[data-journey-review]');
        review.replaceChildren(...rows.map(([label, value]) => {
            const row = document.createElement('div');
            const term = document.createElement('span');
            const detail = document.createElement('strong');
            term.textContent = label;
            detail.textContent = value;
            row.append(term, detail);
            return row;
        }));
        showStep(6);
    });

    journeyFlow.querySelectorAll('[data-journey-previous]').forEach((button) => {
        button.addEventListener('click', () => {
            const previousSteps = { 2: 1, 3: 2, 4: 2, 5: 4, 6: 5 };
            showStep(previousSteps[currentStep] || 1);
        });
    });

    journeyFlow.querySelector('[name="privacy_consent"]')?.addEventListener('change', (event) => {
        event.currentTarget.closest('.consent-check').classList.toggle('selected', event.currentTarget.checked);
    });

    journeyFlow.querySelector('[data-submit-vetting]')?.addEventListener('click', async (event) => {
        if (!validateStep(6)) return;
        const button = event.currentTarget;
        const value = (name) => journeyFlow.querySelector(`[name="${name}"]`)?.value?.trim() || '';
        setBusy(button, true, 'Submitting…');
        showMessage();
        try {
            const data = await request(journeyFlow.dataset.submitUrl, {
                full_name: value('full_name'),
                email: value('email'),
                phone: value('phone'),
                identity_type: journeyFlow.querySelector('[name="identity_type"]:checked').value,
                identity_number: value('identity_number'),
                education_level: value('education_level'),
                employment_status: value('employment_status'),
                preferred_language: value('preferred_language'),
                court_experience: value('court_experience'),
                legal_document_confidence: Number(journeyFlow.querySelector('[name="legal_document_confidence"]:checked').value),
                support_needs: value('support_needs'),
                agreement_status: agreementStatus,
                selected_topics: [...topics],
                privacy_consent: journeyFlow.querySelector('[name="privacy_consent"]').checked,
            });
            journeyFlow.querySelector('[data-submission-reference]').textContent = data.reference;
            showStep('success');
        } catch (error) {
            showMessage(error.message);
        } finally {
            setBusy(button, false);
        }
    });
}
