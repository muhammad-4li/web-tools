import { ref, watch, onMounted, onBeforeUnmount, nextTick } from 'vue';
import 'jodit/es2021/jodit.fat.min.css';
import { Jodit } from 'jodit/es2021/jodit.fat.min.js';

/**
 * Shared Jodit rich-text editor composable.
 *
 * @param {Object} options
 * @param {import('vue').WritableComputedRef<string>} options.content
 *   Writable computed ref bound to the HTML content string.
 * @param {number} [options.height=500]
 *   Editor height in px.
 * @param {import('vue').Ref<string>|null} [options.watchTab=null]
 *   When provided, fires a Jodit resize event whenever the value becomes
 *   'content' — fixes the layout-collapse bug caused by v-show toggling
 *   display:none on the editor's parent container.
 */
export function useJoditEditor({ content, height = 500, watchTab = null }) {
    const editorEl = ref(null);
    let joditInstance = null;

    onMounted(() => {
        if (!editorEl.value) return;

        // Pre-populate textarea value before Jodit reads it
        editorEl.value.value = content.value;

        joditInstance = Jodit.make(editorEl.value, {
            height,
            language: 'en',
            toolbarButtonSize: 'small',
            buttons: [
                'source', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'ul', 'ol', '|',
                'indent', 'outdent', '|',
                'font', 'fontsize', 'paragraph', '|',
                'superscript', 'subscript', '|',
                'brush', '|',
                'align', '|',
                'link', 'image', 'video', 'table', '|',
                'hr', 'eraser', 'copyformat', '|',
                'undo', 'redo', '|',
                'fullsize',
            ],
            uploader:             { insertImageAsBase64URI: true },
            showCharsCounter:     false,
            showWordsCounter:     true,
            showXPathInStatusbar: false,
            // Preserve rich formatting on paste (fixes silent format-strip bug)
            defaultActionOnPaste: 'insert_as_html',
        });

        joditInstance.events.on('change', (html) => {
            content.value = html;
        });
    });

    onBeforeUnmount(() => {
        joditInstance?.destruct();
        joditInstance = null;
    });

    // Fix: Jodit calculates dimensions at init time. When the editor's parent
    // is hidden via v-show (display:none) and then re-shown, Jodit's workspace
    // keeps the stale (collapsed) size. Fire a resize event on re-show.
    if (watchTab) {
        watch(watchTab, (val) => {
            if (val === 'content') {
                nextTick(() => joditInstance?.e.fire('resize'));
            }
        });
    }

    return {
        editorEl,
        getInstance: () => joditInstance,
    };
}
