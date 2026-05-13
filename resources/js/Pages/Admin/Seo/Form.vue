<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    pageKey:   { type: String,  required: true },
    pageLabel: { type: String,  required: true },
    seo:       { type: Object,  required: true },
    defaults:  { type: Object,  default: () => ({}) },
});

const form = ref({ ...props.seo });
const errors = ref({});
const saving = ref(false);

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

function resetField(field) {
    form.value[field] = props.defaults[field] || '';
}

function submit() {
    saving.value = true;
    errors.value = {};
    router.put(`/admin/seo/${props.pageKey}`, form.value, {
        onError:  (e) => { errors.value = e; saving.value = false; },
        onFinish: ()  => { saving.value = false; },
    });
}
</script>

<template>
    <AdminLayout :title="`SEO — ${pageLabel}`">
        <div class="max-w-2xl">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
                <Link href="/admin/seo" class="hover:text-violet-600 transition-colors">Page SEO</Link>
                <span class="text-gray-300">/</span>
                <span class="text-gray-900 font-medium">{{ pageLabel }}</span>
            </div>

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Title -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700">Meta Title</label>
                        <span :class="charCount(form.title, 60).cls" class="text-xs">
                            {{ charCount(form.title, 60).len }}/60
                        </span>
                    </div>
                    <div class="flex gap-2">
                        <input
                            v-model="form.title"
                            type="text" maxlength="255"
                            class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                            :placeholder="defaults.title || 'Leave blank to use config default'"
                        >
                        <button type="button" @click="resetField('title')" title="Reset to default"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-gray-400 hover:text-violet-600 hover:border-violet-300 text-xs transition-colors">↺</button>
                    </div>
                    <p v-if="errors.title" class="text-red-500 text-xs mt-1">{{ errors.title }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ideal: 50–60 characters. Shown in browser tabs and search results.</p>
                </div>

                <!-- Description -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700">Meta Description</label>
                        <span :class="charCount(form.description, 160).cls" class="text-xs">
                            {{ charCount(form.description, 160).len }}/160
                        </span>
                    </div>
                    <div class="flex gap-2 items-start">
                        <textarea
                            v-model="form.description"
                            rows="3" maxlength="320"
                            class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 resize-none"
                            :placeholder="defaults.description || 'Leave blank to use config default'"
                        ></textarea>
                        <button type="button" @click="resetField('description')" title="Reset to default"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-gray-400 hover:text-violet-600 hover:border-violet-300 text-xs transition-colors mt-0.5">↺</button>
                    </div>
                    <p v-if="errors.description" class="text-red-500 text-xs mt-1">{{ errors.description }}</p>
                    <p class="text-xs text-gray-400 mt-1">Ideal: 120–160 characters. Shown in search snippets.</p>
                </div>

                <!-- Keywords -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-sm font-semibold text-gray-700">Keywords</label>
                        <span class="text-xs text-gray-400">{{ charCount(form.keywords, 500).len }}/500</span>
                    </div>
                    <div class="flex gap-2">
                        <input
                            v-model="form.keywords"
                            type="text" maxlength="500"
                            class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400"
                            :placeholder="defaults.keywords || 'Comma-separated keywords'"
                        >
                        <button type="button" @click="resetField('keywords')" title="Reset to default"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-gray-400 hover:text-violet-600 hover:border-violet-300 text-xs transition-colors">↺</button>
                    </div>
                    <p v-if="errors.keywords" class="text-red-500 text-xs mt-1">{{ errors.keywords }}</p>
                </div>

                <!-- OG Image -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">OG Image Path</label>
                    <div class="flex gap-2">
                        <input
                            v-model="form.og_image"
                            type="text" maxlength="255"
                            class="flex-1 rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 font-mono"
                            placeholder="/images/og-example.png"
                        >
                        <button type="button" @click="resetField('og_image')" title="Reset to default"
                            class="px-3 py-2 rounded-xl border border-gray-200 text-gray-400 hover:text-violet-600 hover:border-violet-300 text-xs transition-colors">↺</button>
                    </div>
                    <p v-if="errors.og_image" class="text-red-500 text-xs mt-1">{{ errors.og_image }}</p>
                    <p class="text-xs text-gray-400 mt-1">Recommended: 1200×630 px PNG/JPG. Shown in social media previews.</p>
                </div>

                <!-- Canonical URL -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Canonical URL <span class="font-normal text-gray-400">(optional)</span></label>
                    <input
                        v-model="form.canonical_url"
                        type="url" maxlength="255"
                        class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-violet-400 font-mono"
                        placeholder="https://ma-tools.com/tools/image-crop"
                    >
                    <p v-if="errors.canonical_url" class="text-red-500 text-xs mt-1">{{ errors.canonical_url }}</p>
                    <p class="text-xs text-gray-400 mt-1">Leave blank to auto-generate from the current page URL.</p>
                </div>

                <!-- Robots -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Robots Directive</label>
                    <div class="flex flex-wrap gap-2">
                        <label
                            v-for="opt in robotsOptions" :key="opt"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl border cursor-pointer text-sm transition-colors"
                            :class="form.robots === opt
                                ? 'border-violet-500 bg-violet-50 text-violet-800 font-semibold'
                                : 'border-gray-200 text-gray-600 hover:border-violet-300'"
                        >
                            <input type="radio" :value="opt" v-model="form.robots" class="sr-only">
                            {{ opt }}
                        </label>
                    </div>
                    <p v-if="errors.robots" class="text-red-500 text-xs mt-1">{{ errors.robots }}</p>
                </div>

                <!-- SERP Preview -->
                <div class="rounded-2xl bg-gray-50 border border-gray-200 p-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Search Result Preview</p>
                    <div class="text-xs text-green-700 mb-0.5">https://ma-tools.com › ...</div>
                    <div class="text-blue-700 text-base font-medium leading-snug hover:underline cursor-pointer line-clamp-1">
                        {{ form.title || defaults.title || '(no title)' }}
                    </div>
                    <div class="text-gray-600 text-sm mt-1 line-clamp-2">
                        {{ form.description || defaults.description || '(no description)' }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-6 py-2.5 rounded-xl bg-violet-600 text-white text-sm font-semibold hover:bg-violet-700 disabled:opacity-50 transition-colors"
                    >{{ saving ? 'Saving…' : 'Save SEO' }}</button>
                    <Link href="/admin/seo" class="px-6 py-2.5 rounded-xl border border-gray-200 text-sm text-gray-600 hover:bg-gray-50 transition-colors">
                        Cancel
                    </Link>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
