import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const SKIP_SELECTOR = [
    'textarea.admin-textarea--plain',
    'textarea[data-plain]',
    'textarea[data-rich-editor="false"]',
    'textarea.font-mono',
].join(', ');

const TOOLBAR = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link'],
    ['clean'],
];

function isSkippable(textarea) {
    return (
        textarea.dataset.richEditorReady === '1' ||
        textarea.disabled ||
        textarea.readOnly ||
        textarea.matches(SKIP_SELECTOR)
    );
}

function looksLikeHtml(value) {
    return /<\/?[a-z][\s\S]*>/i.test(value);
}

function syncToTextarea(quill, textarea) {
    const html = quill.root.innerHTML;
    const empty = quill.getText().trim() === '';
    textarea.value = empty ? '' : html;
    textarea.dispatchEvent(new Event('input', { bubbles: true }));
    textarea.dispatchEvent(new Event('change', { bubbles: true }));
}

function mountEditor(textarea) {
    if (!(textarea instanceof HTMLTextAreaElement) || isSkippable(textarea)) {
        return;
    }

    textarea.dataset.richEditorReady = '1';

    const wrap = document.createElement('div');
    wrap.className = 'admin-rich-editor';

    const host = document.createElement('div');
    host.className = 'admin-rich-editor__host';

    textarea.insertAdjacentElement('afterend', wrap);
    wrap.appendChild(host);

    textarea.classList.add('admin-rich-editor__source');
    textarea.setAttribute('aria-hidden', 'true');
    textarea.tabIndex = -1;

    const quill = new Quill(host, {
        theme: 'snow',
        modules: {
            toolbar: TOOLBAR,
        },
        placeholder: textarea.getAttribute('placeholder') || 'Write content…',
    });

    const initial = textarea.value.trim();
    if (initial) {
        if (looksLikeHtml(initial)) {
            quill.clipboard.dangerouslyPasteHTML(initial);
        } else {
            quill.setText(initial);
        }
    }

    quill.on('text-change', () => syncToTextarea(quill, textarea));

    const form = textarea.closest('form');
    if (form && !form.dataset.richEditorBound) {
        form.dataset.richEditorBound = '1';
        form.addEventListener('submit', () => {
            form.querySelectorAll('textarea[data-rich-editor-ready="1"]').forEach((el) => {
                const editorRoot = el._quill?.root;
                if (!editorRoot) return;
                const empty = (editorRoot.textContent || '').trim() === '';
                el.value = empty ? '' : editorRoot.innerHTML;
            });
        });
    }

    textarea._quill = quill;
}

function mountAll(root = document) {
    root.querySelectorAll('textarea.admin-textarea').forEach(mountEditor);
}

export function initAdminRichEditors() {
    if (!document.body?.dataset?.adminApp) {
        return;
    }

    mountAll();

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (!(node instanceof HTMLElement)) return;
                if (node.matches?.('textarea.admin-textarea')) {
                    mountEditor(node);
                }
                node.querySelectorAll?.('textarea.admin-textarea').forEach(mountEditor);
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
}
