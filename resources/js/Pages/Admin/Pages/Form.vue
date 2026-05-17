<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import 'jodit/es2021/jodit.min.css';
import { Jodit } from 'jodit';

const props = defineProps({
    pageKey:   { type: String, required: true },
    pageLabel: { type: String, required: true },
    page:      { type: Object, default: null },
});

const form = ref({
    title:            props.page?.title            ?? '',
    content:          props.page?.content          ?? '',
    meta_title:       props.page?.meta_title       ?? '',
    meta_description: props.page?.meta_description ?? '',
    meta_keywords:    props.page?.meta_keywords    ?? '',
    og_image:         props.page?.og_image         ?? '',
    robots:           props.page?.robots           ?? 'index, follow',
});

const errors  = ref({});
const saving  = ref(false);
const tab     = ref('content');

const robotsOptions = [
    'index, follow',
    'index, nofollow',
    'noindex, follow',
    'noindex, nofollow',
];

function charCount(str, max) {
    const len = (str || '').length;
    return { len, cls: len > max ? 'text-red-500' : len > max * 0.9 ? 'text-amber-500' : 'text-gray-400' };
}

// ── Jodit editor ──────────────────────────────────────────────────────────
const editorEl      = ref(null);
let   joditInstance = null;

onMounted(() => {
    if (!editorEl.value) return;
    editorEl.value.value = form.value.content;
    joditInstance = Jodit.make(editorEl.value, {
        height: 500,
        language: 'en',
        toolbarButtonSize: 'small',
        buttons: [
            'source', '|',
            'bold', 'italic', 'underline', 'strikethrough', '|',
            'ul', 'ol', '|',
            'font', 'fontsize', 'paragraph', '|',
            'brush', '|',
            'align', '|',
            'link', 'image', 'table', '|',
            'hr', 'eraser', '|',
            'undo', 'redo', '|',
            'fullsize',
        ],
        uploader:               { insertImageAsBase64URI: true },
        showCharsCounter:       false,
        showWordsCounter:       false,
        showXPathInStatusbar:   false,
        askBeforePasteHTML:     false,
        askBeforePasteFromWord: false,
        defaultActionOnPaste:   'insert_clear_html',
    });
    joditInstance.events.on('change', (html) => {
        form.value.content = html;
    });
});

onBeforeUnmount(() => {
    joditInstance?.destruct();
    joditInstance = null;
});

// ── Submit ─────────────────────────────────────────────────────────────────
function submit() {
    if (joditInstance) form.value.content = joditInstance.value;
    saving.value = true;
    errors.value = {};
    router.put(`/admin/pages/${props.pageKey}`, form.value, {
        onError:  (e) => { errors.value = e; saving.value = false; },
        onFinish: ()  => { saving.value = false; },
    });
}
</script>

<template>
    <AdminLayout :title="`Edit — ${pageLabel}`">

        <!-- Breadcrumb + tabs -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <Link href="/admin/pages" class="hover:text-violet-600 transition-colors">Static Pages</Link>
                <span>/</span>
                <span class="text-gray-900 font-semibold">{{ pageLabel }}</span>
            </div>

            <div class="flex rounded-xl border border-gray-200 overflow-hidden text-sm">
                <button type="button"
                    class="px-4 py-2 font-semibold transition-colors"
                    :class="tab === 'content' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    @click="tab = 'content'">Content</button>
                <button type="button"
                    class="px-4 py-2 font-semibold transition-colors"
                    :class="tab === 'seo' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    @click="tab = 'seo'">SEO &amp; Meta</button>
            </div>
        </div>

        <form @submit.prevent="submit" class="max-w-3xl space-y-5">

            <!-- ── Content tab ─────────────────────────────────────────────── -->
            <div v-show="tab === 'content'" class="space-y-5">

                <!-- Page title (h1) -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Page Title <span class="text-red-500">*</span></label>
                    <input
                        v-model="form.title"
                        type="text" maxlength="255" required
                        placeholder="e.g. About KhanTools"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                    >
                    <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title }}</p>
                </div>

                <!-- Rich text content -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Page Content <span class="text-red-500">*</span></label>
                    <textarea ref="editorEl"></textarea>
                    <p v-if="errors.content" class="text-red-500 text-xs mt-1">{{ errors.content }}</p>
                </div>
            </div>

            <!-- ── SEO tab ──────────────────────────────────────────────────── -->
            <div v-show="tab === 'seo'" class="space-y-5">

                <!-- Meta title -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700">Meta Title</label>
                        <span :class="charCount(form.meta_title, 60).cls" class="text-xs">
                            {{ charCount(form.meta_title, 60).len }}/60
                        </span>
                    </div>
                    <input
                        v-model="form.meta_title"
                        type="text" maxlength="255"
                        placeholder="Leave blank to use Page Title"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                    >
                    <p v-if="errors.meta_title" class="text-red-500 text-xs mt-1">{{ errors.meta_title }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ideal 50–60 characters.</p>
                </div>

                <!-- Meta description -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700">Meta Description</label>
                        <span :class="charCount(form.meta_description, 160).cls" class="text-xs">
                            {{ charCount(form.meta_description, 160).len }}/160
                        </span>
                    </div>
                    <textarea
                        v-model="form.meta_description"
                        rows="3" maxlength="320"
                        placeholder="Brief description for search engines"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 resize-none"
                    ></textarea>
                    <p v-if="errors.meta_description" class="text-red-500 text-xs mt-1">{{ errors.meta_description }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ideal 120–160 characters.</p>
                </div>

                <!-- Keywords -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Meta Keywords</label>
                    <input
                        v-model="form.meta_keywords"
                        type="text" maxlength="500"
                        placeholder="Comma-separated keywords"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                    >
                    <p v-if="errors.meta_keywords" class="text-red-500 text-xs mt-1">{{ errors.meta_keywords }}</p>
                </div>

                <!-- OG Image -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">OG Image URL</label>
                    <input
                        v-model="form.og_image"
                        type="text" maxlength="255"
                        placeholder="/images/og-about.png"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                    >
                    <p v-if="errors.og_image" class="text-red-500 text-xs mt-1">{{ errors.og_image }}</p>
                </div>

                <!-- Robots -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Robots Directive</label>
                    <select
                        v-model="form.robots"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 bg-white"
                    >
                        <option v-for="opt in robotsOptions" :key="opt" :value="opt">{{ opt }}</option>
                    </select>
                </div>
            </div>

            <!-- Submit -->
            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="saving"
                    class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all disabled:opacity-60 disabled:pointer-events-none"
                >{{ saving ? 'Saving…' : 'Save Page' }}</button>
                <Link href="/admin/pages" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                    Cancel
                </Link>
                <a :href="`/${pageKey}`" target="_blank" class="px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors ml-auto">
                    View Page ↗
                </a>
            </div>
        </form>
    </AdminLayout>
</template>
