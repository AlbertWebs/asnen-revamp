/**
 * AJAX public forms with honeypot + arithmetic captcha popup.
 */

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

function challengeUrl() {
    return document.body?.dataset?.mathChallengeUrl || '/forms/math-challenge';
}

function createModal() {
    const root = document.getElementById('math-captcha-modal');
    if (!root) {
        throw new Error('Math captcha modal is missing from the page.');
    }
    return root;
}

function promptMathCaptcha() {
    const modal = createModal();
    const questionEl = modal.querySelector('[data-math-question]');
    const answerEl = modal.querySelector('[data-math-answer]');
    const errorEl = modal.querySelector('[data-math-error]');
    const confirmBtn = modal.querySelector('[data-math-confirm]');

    return new Promise(async (resolve) => {
        let settled = false;
        let token = '';

        const finish = (value) => {
            if (settled) return;
            settled = true;
            modal.hidden = true;
            document.documentElement.classList.remove('overflow-hidden');
            document.removeEventListener('keydown', onKey);
            resolve(value);
        };

        const onKey = (event) => {
            if (event.key === 'Escape') finish(null);
            if (event.key === 'Enter') {
                event.preventDefault();
                confirmBtn.click();
            }
        };

        errorEl.hidden = true;
        errorEl.textContent = '';
        answerEl.value = '';
        questionEl.textContent = 'Loading…';
        confirmBtn.disabled = true;
        modal.hidden = false;
        document.documentElement.classList.add('overflow-hidden');
        document.addEventListener('keydown', onKey);

        modal.querySelectorAll('[data-math-cancel]').forEach((el) => {
            el.onclick = () => finish(null);
        });

        confirmBtn.onclick = () => {
            const answer = answerEl.value.trim();
            if (answer === '' || Number.isNaN(Number(answer))) {
                errorEl.textContent = 'Enter a whole number to continue.';
                errorEl.hidden = false;
                answerEl.focus();
                return;
            }
            finish({ token, answer });
        };

        try {
            const response = await fetch(challengeUrl(), {
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'same-origin',
            });
            if (!response.ok) throw new Error('challenge failed');
            const data = await response.json();
            token = data.token;
            questionEl.textContent = `What is ${data.question}?`;
            confirmBtn.disabled = false;
            answerEl.focus();
        } catch (error) {
            errorEl.textContent = 'Could not load the security check. Please try again.';
            errorEl.hidden = false;
            confirmBtn.disabled = true;
        }
    });
}

function clearFieldErrors(form) {
    form.querySelectorAll('.site-form__input--error, .newsletter-form__input--error').forEach((el) => {
        el.classList.remove('site-form__input--error', 'newsletter-form__input--error');
        el.removeAttribute('aria-invalid');
    });
    form.querySelectorAll('[data-ajax-error]').forEach((el) => el.remove());
    const summary = form.querySelector('[data-ajax-summary]');
    if (summary) {
        summary.hidden = true;
        summary.innerHTML = '';
    }
}

function showErrors(form, errors, message) {
    let summary = form.querySelector('[data-ajax-summary]');
    if (!summary) {
        summary = document.createElement('div');
        summary.className = 'site-form__errors a11y-form-errors';
        summary.setAttribute('role', 'alert');
        summary.setAttribute('tabindex', '-1');
        summary.dataset.ajaxSummary = '';
        form.prepend(summary);
    }

    const list = Object.values(errors || {}).flat();
    const items = list.length
        ? `<ul class="mt-2 list-disc space-y-1 pl-5">${list.map((item) => `<li>${escapeHtml(item)}</li>`).join('')}</ul>`
        : '';
    summary.innerHTML = `<p class="font-semibold">${escapeHtml(message || 'Please fix the errors and try again.')}</p>${items}`;
    summary.hidden = false;
    summary.focus();

    Object.keys(errors || {}).forEach((field) => {
        const input = form.querySelector(`[name="${CSS.escape(field)}"]`);
        if (!input) return;
        input.classList.add(form.classList.contains('newsletter-form') ? 'newsletter-form__input--error' : 'site-form__input--error');
        input.setAttribute('aria-invalid', 'true');
    });
}

function showInlineSuccess(form, message) {
    let box = form.querySelector('[data-ajax-success]');
    if (!box) {
        box = document.createElement('div');
        box.className = 'site-form__success';
        box.setAttribute('role', 'status');
        box.dataset.ajaxSuccess = '';
        form.prepend(box);
    }
    box.textContent = message;
    box.hidden = false;
}

function escapeHtml(value) {
    return String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#39;');
}

function setSubmitting(form, busy) {
    const button = form.querySelector('[type="submit"]');
    if (!button) return;
    if (busy) {
        button.dataset.originalLabel = button.textContent;
        button.disabled = true;
        button.textContent = 'Sending…';
    } else {
        button.disabled = false;
        if (button.dataset.originalLabel) {
            button.textContent = button.dataset.originalLabel;
        }
    }
}

async function submitAjaxForm(form) {
    clearFieldErrors(form);
    form.querySelector('[data-ajax-success]')?.setAttribute('hidden', 'hidden');

    const captcha = await promptMathCaptcha();
    if (!captcha) return;

    const formData = new FormData(form);
    formData.set('math_token', captcha.token);
    formData.set('math_answer', captcha.answer);

    setSubmitting(form, true);

    try {
        const response = await fetch(form.action, {
            method: (form.getAttribute('method') || 'POST').toUpperCase(),
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken(),
            },
            body: formData,
            credentials: 'same-origin',
        });

        const payload = await response.json().catch(() => ({}));

        if (response.status === 422) {
            showErrors(form, payload.errors || {}, payload.message);
            return;
        }

        if (!response.ok) {
            showErrors(form, {}, payload.message || 'Something went wrong. Please try again.');
            return;
        }

        if (payload.redirect && !form.hasAttribute('data-ajax-stay')) {
            window.location.href = payload.redirect;
            return;
        }

        const message = payload.message || 'Thank you. Your submission was received.';
        const stay = form.hasAttribute('data-ajax-stay') || !payload.redirect;
        if (stay) {
            const body = form.querySelector('[data-ajax-body]');
            const done = form.querySelector('[data-ajax-done]');
            if (body) body.hidden = true;
            if (done) {
                done.hidden = false;
                const title = form.dataset.ajaxStay === 'register' ? 'You are registered' : 'Thank you';
                done.innerHTML = `<p class="site-form__done-title">${escapeHtml(title)}</p><p>${escapeHtml(message)}</p>`;
            } else {
                showInlineSuccess(form, message);
            }
            form.reset();
            return;
        }
        showInlineSuccess(form, message);
        form.reset();
    } catch (error) {
        showErrors(form, {}, 'Network error. Check your connection and try again.');
    } finally {
        setSubmitting(form, false);
    }
}

export function initSiteForms() {
    const forms = document.querySelectorAll('form.site-form, form.newsletter-form, form[data-ajax-form]');
    forms.forEach((form) => {
        if (form.dataset.ajaxBound === '1') return;
        form.dataset.ajaxBound = '1';
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            submitAjaxForm(form);
        });
    });
}
