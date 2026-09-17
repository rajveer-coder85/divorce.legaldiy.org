import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/styles';

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

const journeyFlow = document.querySelector('[data-journey-flow]');
const inquiryForm = document.querySelector('[data-inquiry-form]');
const inquiryModal = document.querySelector('[data-inquiry-modal]');

document.querySelector('[data-open-inquiry-modal]')?.addEventListener('click', () => inquiryModal?.showModal());
document.querySelectorAll('[data-close-inquiry-modal]').forEach((button) => button.addEventListener('click', () => inquiryModal?.close()));
inquiryModal?.addEventListener('click', (event) => { if (event.target === inquiryModal) inquiryModal.close(); });

if (inquiryForm) {
    const stages = [...inquiryForm.querySelectorAll('[data-inquiry-stage]')];
    const digits = [...inquiryForm.querySelectorAll('[data-inquiry-digit]')];
    const messageBox = inquiryForm.querySelector('[data-inquiry-message]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const showStage = (name) => stages.forEach((stage) => { stage.hidden = stage.dataset.inquiryStage !== name; });
    const showInquiryMessage = (message = '', success = false) => {
        messageBox.textContent = message; messageBox.hidden = !message;
        messageBox.className = `journey-form-message${success ? ' success' : ''}`;
    };
    const postInquiry = async (url, payload) => {
        const response = await fetch(url, { method: 'POST', headers: { Accept: 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify(payload) });
        const data = await response.json().catch(() => ({}));
        if (!response.ok) throw new Error(Object.values(data.errors || {}).flat()[0] || data.message || 'Something went wrong. Please try again.');
        return data;
    };
    const setInquiryBusy = (button, busy, label) => {
        if (busy) button.dataset.label = button.innerHTML;
        button.disabled = busy; button.innerHTML = busy ? label : button.dataset.label;
    };
    const contact = () => ({ full_name: inquiryForm.elements.full_name.value.trim(), email: inquiryForm.elements.email.value.trim() });
    const inquiryPayload = () => ({ ...contact(), topic: inquiryForm.elements.topic.value, message: inquiryForm.elements.message.value.trim(), privacy_consent: inquiryForm.elements.privacy_consent.checked ? '1' : '' });
    const detailsValid = () => {
        const fields = [inquiryForm.elements.full_name, inquiryForm.elements.email, inquiryForm.elements.topic, inquiryForm.elements.message, inquiryForm.elements.privacy_consent];
        const invalid = fields.find((field) => !field.checkValidity()); invalid?.reportValidity(); return !invalid;
    };

    digits.forEach((input, index) => {
        input.addEventListener('input', () => { input.value = input.value.replace(/\D/g, '').slice(-1); if (input.value) digits[index + 1]?.focus(); });
        input.addEventListener('keydown', (event) => { if (event.key === 'Backspace' && !input.value) digits[index - 1]?.focus(); });
    });
    inquiryForm.querySelector('[data-inquiry-tac]')?.addEventListener('paste', (event) => {
        const value = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6); if (!value) return;
        event.preventDefault(); digits.forEach((digit, index) => { digit.value = value[index] || ''; }); digits[Math.min(value.length, 6) - 1]?.focus();
    });

    const sendInquiryTac = async (button) => {
        if (!detailsValid()) return;
        setInquiryBusy(button, true, 'Sending…'); showInquiryMessage();
        try { const data = await postInquiry(inquiryForm.dataset.sendUrl, contact()); inquiryForm.querySelector('[data-inquiry-email]').textContent = contact().email; digits.forEach((digit) => { digit.value = ''; }); showStage('verify'); showInquiryMessage(data.message, true); digits[0]?.focus(); }
        catch (error) { showInquiryMessage(error.message); }
        finally { setInquiryBusy(button, false); }
    };
    inquiryForm.querySelector('[data-inquiry-send]').addEventListener('click', (event) => sendInquiryTac(event.currentTarget));
    inquiryForm.querySelector('[data-inquiry-resend]').addEventListener('click', (event) => sendInquiryTac(event.currentTarget));
    inquiryForm.querySelector('[data-inquiry-change]').addEventListener('click', () => { showInquiryMessage(); showStage('details'); });
    inquiryForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        const code = digits.map((digit) => digit.value).join(''); if (!/^\d{6}$/.test(code)) return showInquiryMessage('Enter all six digits.');
        const button = inquiryForm.querySelector('[type="submit"]'); setInquiryBusy(button, true, 'Submitting…'); showInquiryMessage();
        try {
            await postInquiry(inquiryForm.dataset.verifyUrl, { email: contact().email, code });
            const data = await postInquiry(inquiryForm.dataset.submitUrl, inquiryPayload());
            inquiryForm.querySelector('[data-inquiry-reference]').textContent = data.reference; showStage('success'); showInquiryMessage();
        }
        catch (error) { showInquiryMessage(error.message); }
        finally { setInquiryBusy(button, false); }
    });
}

const childrenLearning = document.querySelector('[data-legal-learning], [data-children-learning]');

if (childrenLearning) {
    const panels = [...childrenLearning.querySelectorAll('[data-learning-panel]')];
    const tabs = [...childrenLearning.querySelectorAll('[data-learning-tab]')];
    const previous = childrenLearning.querySelector('[data-learning-previous]');
    const next = childrenLearning.querySelector('[data-learning-next]');
    const scenarios = childrenLearning.querySelector('[data-children-scenarios]');
    const result = childrenLearning.querySelector('[data-scenario-result]');
    let activeStep = 1;

    const showLearningStep = (step, scroll = true) => {
        activeStep = Math.max(1, Math.min(panels.length, Number(step)));
        panels.forEach((panel) => { panel.hidden = Number(panel.dataset.learningPanel) !== activeStep; });
        tabs.forEach((tab) => tab.setAttribute('aria-current', Number(tab.dataset.learningTab) === activeStep ? 'step' : 'false'));
        childrenLearning.querySelector('[data-learning-label]').textContent = `Topic ${activeStep} of ${panels.length}`;
        childrenLearning.querySelector('[data-learning-progress]').style.width = `${(activeStep / panels.length) * 100}%`;
        previous.hidden = activeStep === 1;
        next.innerHTML = activeStep === panels.length ? 'Explore scenarios <span>→</span>' : 'Next topic <span>→</span>';
        if (scroll) panels[activeStep - 1].scrollIntoView({ behavior: 'smooth', block: 'start' });
    };

    tabs.forEach((tab) => tab.addEventListener('click', () => showLearningStep(tab.dataset.learningTab)));
    previous?.addEventListener('click', () => showLearningStep(activeStep - 1));
    next?.addEventListener('click', () => {
        if (activeStep < panels.length) return showLearningStep(activeStep + 1);
        window.location.href = childrenLearning.dataset.examplesUrl;
    });

    const scenarioGuidance = {
        shared: { title: 'Emily, David, and one familiar home', situation: 'Their nine-year-old daughter, Sophie, has always lived close to school and her grandparents. Emily and David both want joint responsibility for important decisions, but David’s work makes an equal day-to-day schedule difficult.', journey: 'They separate custody from daily care. Sophie keeps one principal home during the school week. Both parents agree to discuss education and healthcare, share school information, and plan David’s time around a predictable routine.', takeaway: 'Ask which decisions should remain shared, where the child’s principal home should be, and how both parents will receive information and stay involved.' },
        access: { title: 'Mei Ling and Daniel build a routine', situation: 'Their son lives mainly with Mei Ling. Both parents support regular contact with Daniel, but vague promises such as “reasonable access” keep causing misunderstandings.', journey: 'They work through ordinary weekdays first, then weekends, school holidays, calls, collection, delays, and returns. They choose arrangements that fit travel time, school, sleep, and their son’s activities.', takeaway: 'Turn broad intentions into practical details. A useful plan explains when contact happens, who handles transport, and how changes will be communicated.' },
        maintenance: { title: 'Priya and Arvind list the real costs', situation: 'They agree that both children must be supported, but each parent has a different idea of what a fair monthly figure looks like.', journey: 'Instead of starting with a salary percentage, they list food, housing, school, transport, medical, childcare, and occasional costs. They record what each already pays and distinguish a regular contribution from expenses paid directly.', takeaway: 'Start with the children’s reasonable needs, then consider how those needs can be met in light of each parent’s means and circumstances.' },
        complex: { title: 'Rachel and Thomas face a major change', situation: 'Rachel is considering work abroad with their child. Thomas objects because the move would change school, regular contact, travel costs, and the child’s relationship with him.', journey: 'They realise this is not simply a question of adding more holiday access. The proposed relocation affects custody, stability, international travel, and the practical ability to maintain the parent-child relationship.', takeaway: 'Relocation, safety concerns, or serious disagreement may not fit a straightforward agreed journey. Pause and consider individual legal advice.' },
    };

    const accessExamples = {
        weekdays: ['A short visit after school on an agreed weekday.', 'Dinner together before returning to the principal home.', 'A regular activity or homework period with the other parent.', 'No weekday visit where travel or school routines would make it disruptive.'],
        weekends: ['Alternate weekends from Friday evening to Sunday evening.', 'One full day each weekend without an overnight stay.', 'A longer weekend on a repeating schedule.', 'Flexible weekends agreed in advance around the child’s activities.'],
        overnights: ['A regular overnight every week or fortnight.', 'Overnights introduced gradually as the child becomes comfortable.', 'Overnights only during weekends or school holidays.', 'Daytime access without overnights where that better suits the child’s present needs.'],
        'school-holidays': ['Divide each school holiday into agreed blocks.', 'Alternate particular school breaks from year to year.', 'Keep the usual weekly routine with additional holiday days.', 'Agree travel dates, itinerary, contact details, and documents in advance.'],
        'public-holidays': ['Alternate named public holidays each year.', 'Divide the day where distance and routine permit.', 'Assign particular celebrations consistently to each parent.', 'Celebrate separately on nearby dates to reduce disruption.'],
        birthdays: ['Alternate the child’s birthday from year to year.', 'Share separate time periods on the birthday.', 'Hold one joint celebration where appropriate and comfortable.', 'Celebrate with the other parent on a nearby date.'],
        calls: ['A brief call at a regular time on selected days.', 'Video calls on days without physical contact.', 'Reasonable child-led contact without a rigid schedule.', 'Flexible contact that avoids school, sleep, meals, and activities.'],
        collection: ['The receiving parent collects from the principal home.', 'Collection takes place directly from school or childcare.', 'Parents meet at a neutral, convenient location.', 'A trusted adult assists where parents cannot attend or direct contact is unsuitable.'],
        returns: ['Return to the principal home at a fixed time.', 'Return to school or childcare the next morning.', 'Use a neutral handover location.', 'Confirm delays promptly and agree who handles transport.'],
    };
    const accessTitles = { weekdays: 'Weekdays', weekends: 'Weekends', overnights: 'Overnight stays', 'school-holidays': 'School holidays', 'public-holidays': 'Public holidays', birthdays: 'Birthdays', calls: 'Calls & video calls', collection: 'Collection', returns: 'Return arrangements' };
    const accessModal = childrenLearning.querySelector('[data-access-modal]');
    const closeAccessModal = () => accessModal?.close();
    const openInformationModal = ({ kicker, title, intro, examples, note }) => {
        accessModal.querySelector('[data-modal-kicker]').textContent = kicker;
        accessModal.querySelector('[data-access-modal-title]').textContent = title;
        accessModal.querySelector('[data-modal-intro]').textContent = intro;
        accessModal.querySelector('[data-modal-note]').textContent = note;
        accessModal.querySelector('[data-access-modal-list]').replaceChildren(...examples.map((example) => {
            const item = document.createElement('li');
            item.textContent = example;
            return item;
        }));
        accessModal.showModal();
    };

    childrenLearning.querySelectorAll('[data-access-example]').forEach((button) => {
        button.addEventListener('click', () => {
            const key = button.dataset.accessExample;
            openInformationModal({ kicker: 'Possible arrangements', title: accessTitles[key], intro: 'Families may arrange this in different ways. Examples include:', examples: accessExamples[key], note: 'These are discussion examples, not a standard entitlement or required arrangement. What works should reflect the child’s welfare, age, routine, and family circumstances.' });
        });
    });

    const expenseExamples = {
        accommodation: ['A reasonable share of housing costs connected with the child’s home.', 'Utilities and household costs relating to the child’s day-to-day living.', 'A bed, suitable sleeping space, and necessary household items.', 'Costs may need to reflect the actual living arrangement rather than an automatic percentage of all housing expenses.'],
        food: ['Everyday meals and groceries.', 'School meals or meal plans.', 'Food required during access periods.', 'Reasonable dietary needs, including medically required diets where applicable.'],
        clothing: ['Everyday clothing appropriate for the child’s age.', 'School uniforms, shoes, and required accessories.', 'Clothing for sports or activities.', 'Replacement clothing as the child grows, rather than treating every purchase as a fixed monthly cost.'],
        education: ['School or course fees.', 'Books, devices, and learning materials.', 'Tuition or additional learning support where reasonably needed.', 'Further, higher, tertiary, vocational education, or training where applicable.'],
        medical: ['Routine consultations, medicine, dental care, and optical needs.', 'Health insurance or medical-card costs.', 'Therapy, specialist care, or disability-related support.', 'How parents will approve and pay unexpected or emergency treatment.'],
        transport: ['Travel between home and school.', 'Transport for access and handovers.', 'Travel to medical appointments or regular activities.', 'Public transport, school transport, fuel, tolls, or other reasonable travel costs.'],
        childcare: ['Nursery, daycare, babysitting, or after-school care.', 'Care required because of a parent’s working hours.', 'Holiday childcare arrangements.', 'How changes in childcare needs or providers will be discussed.'],
        'school-expenses': ['Uniforms, books, stationery, and required technology.', 'School trips, examinations, activities, and compulsory charges.', 'Sports equipment or co-curricular costs.', 'How one-off expenses will be notified, approved, and paid.'],
        'other-needs': ['Reasonable activities connected with the child’s development.', 'Religious, cultural, or community activities.', 'Disability-related equipment or support.', 'Other child-specific costs supported by the child’s actual needs and the parents’ circumstances.'],
    };
    const expenseTitles = { accommodation: 'Accommodation', food: 'Food', clothing: 'Clothing', education: 'Education', medical: 'Medical needs', transport: 'Transport', childcare: 'Childcare', 'school-expenses': 'School expenses', 'other-needs': 'Other reasonable needs' };

    childrenLearning.querySelectorAll('[data-expense-example]').forEach((button) => {
        button.addEventListener('click', () => {
            const key = button.dataset.expenseExample;
            openInformationModal({ kicker: 'Possible child expenses', title: expenseTitles[key], intro: 'This category may include items such as:', examples: expenseExamples[key], note: 'These examples do not make every expense automatically payable. Parents should identify the child’s actual reasonable needs, what each parent already provides, and how costs will be met.' });
        });
    });
    accessModal?.querySelector('[data-access-modal-close]')?.addEventListener('click', closeAccessModal);
    accessModal?.querySelector('[data-access-modal-done]')?.addEventListener('click', closeAccessModal);
    accessModal?.addEventListener('click', (event) => { if (event.target === accessModal) closeAccessModal(); });

    childrenLearning.querySelectorAll('[data-child-scenario]').forEach((button) => {
        button.addEventListener('click', () => {
            childrenLearning.querySelectorAll('[data-child-scenario]').forEach((item) => item.classList.toggle('selected', item === button));
            const story = scenarioGuidance[button.dataset.childScenario];
            result.replaceChildren();
            const heading = document.createElement('h3');
            const situationTitle = document.createElement('h4');
            const situation = document.createElement('p');
            const journeyTitle = document.createElement('h4');
            const journey = document.createElement('p');
            const takeawayTitle = document.createElement('h4');
            const takeaway = document.createElement('p');
            const link = document.createElement('a');
            heading.textContent = story.title;
            situationTitle.textContent = 'Their situation'; situation.textContent = story.situation;
            journeyTitle.textContent = 'How they work through it'; journey.textContent = story.journey;
            takeawayTitle.textContent = 'Questions to take into your story'; takeaway.textContent = story.takeaway;
            link.href = childrenLearning.dataset.journeyUrl || '/journey';
            link.className = 'button';
            link.textContent = 'Start your guided journey →';
            result.append(heading, situationTitle, situation, journeyTitle, journey, takeawayTitle, takeaway, link);
            result.hidden = false;
            result.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
    });

    showLearningStep(1, false);
}

if (journeyFlow) {
    const steps = [...journeyFlow.querySelectorAll('[data-journey-step]')];
    const topics = new Set();
    let currentStep = '1';
    let agreementStatus = '';
    let resendTimer;
    const draftFieldNames = ['education_level', 'employment_status', 'monthly_income_range', 'preferred_language', 'court_experience', 'separation_status', 'separation_duration', 'divorce_stage', 'papers_filed', 'legal_document_confidence'];
    const readDraft = () => {
        const match = document.cookie.split('; ').find((item) => item.startsWith('legaldiy_journey_draft='));
        if (!match) return {};
        try { return JSON.parse(decodeURIComponent(match.slice(match.indexOf('=') + 1))); } catch { return {}; }
    };
    const savedDraft = readDraft();
    const saveDraft = () => {
        const draft = {};
        draftFieldNames.forEach((name) => {
            const field = journeyFlow.querySelector(`[name="${name}"]:checked`) || journeyFlow.querySelector(`[name="${name}"]`);
            if (field?.value) draft[name] = field.value;
        });
        draft.selected_topics = [...topics];
        document.cookie = `legaldiy_journey_draft=${encodeURIComponent(JSON.stringify(draft))}; Max-Age=2592000; Path=/; SameSite=Lax`;
    };
    draftFieldNames.forEach((name) => {
        if (!savedDraft[name]) return;
        const field = journeyFlow.querySelector(`[name="${name}"][value="${savedDraft[name]}"]`) || journeyFlow.querySelector(`[name="${name}"]`);
        if (field) { field.value = savedDraft[name]; if (field.type === 'radio') field.checked = true; }
    });
    journeyFlow.addEventListener('change', (event) => { if (draftFieldNames.includes(event.target.name)) saveDraft(); });

    const phoneInput = journeyFlow.querySelector('[data-phone-input]');
    const phoneE164 = journeyFlow.querySelector('[data-phone-e164]');
    const phonePicker = phoneInput ? intlTelInput(phoneInput, {
        initialCountry: 'my',
        separateDialCode: true,
        nationalMode: true,
        autoPlaceholder: 'polite',
        countryOrder: ['my', 'sg', 'id', 'th', 'bn', 'ph', 'in', 'cn', 'au', 'gb', 'us'],
        loadUtils: () => import('intl-tel-input/utils'),
    }) : null;

    const syncAndValidatePhone = (showMessage = false) => {
        if (!phoneInput || !phonePicker) return true;
        const entered = phoneInput.value.trim();
        let number = '';
        let valid = false;

        try {
            number = phonePicker.getNumber();
            valid = entered !== '' && phonePicker.isValidNumber();
        } catch {
            valid = /^\+[1-9]\d{6,14}$/.test(number);
        }

        phoneE164.value = valid ? number : '';
        phoneInput.setCustomValidity(valid || (!entered && !showMessage) ? '' : 'Please enter a valid mobile number.');
        if (!entered && showMessage) phoneInput.setCustomValidity('Please enter your mobile number.');
        return valid;
    };

    phoneInput?.addEventListener('input', () => syncAndValidatePhone(false));
    phoneInput?.addEventListener('countrychange', () => syncAndValidatePhone(false));
    phoneInput?.addEventListener('blur', () => syncAndValidatePhone(true));

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

    const tacDigits = [...journeyFlow.querySelectorAll('[data-tac-digit]')];
    const resetTacDigits = () => {
        tacDigits.forEach((input) => { input.value = ''; });
        window.setTimeout(() => tacDigits[0]?.focus(), 50);
    };

    tacDigits.forEach((input, index) => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/\D/g, '').slice(-1);
            if (input.value && tacDigits[index + 1]) tacDigits[index + 1].focus();
        });
        input.addEventListener('keydown', (event) => {
            if (event.key === 'Backspace' && !input.value && tacDigits[index - 1]) {
                tacDigits[index - 1].focus();
            }
            if (event.key === 'ArrowLeft' && tacDigits[index - 1]) tacDigits[index - 1].focus();
            if (event.key === 'ArrowRight' && tacDigits[index + 1]) tacDigits[index + 1].focus();
        });
    });

    journeyFlow.querySelector('[data-tac-boxes]')?.addEventListener('paste', (event) => {
        const pastedDigits = event.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
        if (!pastedDigits) return;
        event.preventDefault();
        tacDigits.forEach((input, index) => { input.value = pastedDigits[index] || ''; });
        tacDigits[Math.min(pastedDigits.length, 6) - 1]?.focus();
    });

    const sendTac = async (button) => {
        syncAndValidatePhone(true);
        if (!validateStep(2)) return;
        const email = journeyFlow.querySelector('[name="email"]').value.trim();
        const fullName = journeyFlow.querySelector('[name="full_name"]').value.trim();
        let countdownSeconds = null;
        setBusy(button, true, 'Sending…');
        showMessage();
        try {
            const data = await request(journeyFlow.dataset.tacSendUrl, { email, full_name: fullName });
            journeyFlow.querySelector('[data-tac-email]').textContent = email;
            showStep(3);
            resetTacDigits();
            showMessage(data.message, 'success');
            countdownSeconds = data.resend_after || 60;
        } catch (error) {
            showMessage(error.message);
            if (error.retryAfter) countdownSeconds = error.retryAfter;
        } finally {
            setBusy(button, false);
        }
        if (countdownSeconds !== null) startResendCountdown(countdownSeconds);
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
                code: tacDigits.map((input) => input.value).join(''),
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
            saveDraft();
        });
    });

    (savedDraft.selected_topics || []).forEach((topic) => {
        const button = journeyFlow.querySelector(`[data-journey-topic="${topic}"]`);
        if (!button) return;
        topics.add(topic); button.classList.add('selected'); button.setAttribute('aria-pressed', 'true');
    });
    if (topics.size) nextButton.disabled = false;

    nextButton?.addEventListener('click', () => {
        const identityType = journeyFlow.querySelector('[name="identity_type"]:checked').value;
        const identityValue = journeyFlow.querySelector('[name="identity_number"]').value;
        const maskedIdentity = identityValue.length > 4 ? `${'•'.repeat(6)}${identityValue.slice(-4)}` : 'Provided';
        const rows = [
            ['Full legal name', journeyFlow.querySelector('[name="full_name"]').value],
            ['Verified email', journeyFlow.querySelector('[name="email"]').value],
            ['Mobile number', phoneE164?.value || phoneInput?.value || ''],
            [identityType === 'nric' ? 'NRIC' : 'Passport', maskedIdentity],
            ['Agreement to divorce', agreementStatus === 'agree' ? 'Both agree' : 'Not yet agreed'],
            ['Monthly income', journeyFlow.querySelector('[name="monthly_income_range"] option:checked').textContent],
            ['Separation', journeyFlow.querySelector('[name="separation_status"] option:checked').textContent],
            ['Current stage', journeyFlow.querySelector('[name="divorce_stage"] option:checked').textContent],
            ['Papers filed', journeyFlow.querySelector('[name="papers_filed"] option:checked').textContent],
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
                monthly_income_range: value('monthly_income_range'),
                preferred_language: value('preferred_language'),
                court_experience: value('court_experience'),
                separation_status: value('separation_status'),
                separation_duration: value('separation_duration'),
                divorce_stage: value('divorce_stage'),
                papers_filed: value('papers_filed'),
                legal_document_confidence: Number(journeyFlow.querySelector('[name="legal_document_confidence"]:checked').value),
                support_needs: value('support_needs'),
                agreement_status: agreementStatus,
                selected_topics: [...topics],
                privacy_consent: journeyFlow.querySelector('[name="privacy_consent"]').checked,
            });
            journeyFlow.querySelector('[data-submission-reference]').textContent = data.reference;
            document.cookie = 'legaldiy_journey_draft=; Max-Age=0; Path=/; SameSite=Lax';
            showStep('success');
        } catch (error) {
            showMessage(error.message);
        } finally {
            setBusy(button, false);
        }
    });
}
