<script setup>
import { ref, watch, onMounted, onBeforeUnmount } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import 'jodit/es2021/jodit.min.css';
import { Jodit } from 'jodit';

const props  = defineProps({ post: Object });
const isEdit = !!props.post;

const form = ref({
    title:            props.post?.title            ?? '',
    slug:             props.post?.slug             ?? '',
    excerpt:          props.post?.excerpt          ?? '',
    content:          props.post?.content          ?? '',
    meta_title:       props.post?.meta_title       ?? '',
    meta_description: props.post?.meta_description ?? '',
    meta_keywords:    props.post?.meta_keywords    ?? '',
    og_image:         props.post?.og_image         ?? '',
    is_published:     props.post?.is_published     ?? false,
});

const errors     = ref({});
const loading    = ref(false);
const tab        = ref('content');  // 'content' | 'seo'
const mobileView = ref('edit');     // 'edit' | 'preview'

// ── Jodit editor ───────────────────────────────────────────────────────────
const editorEl      = ref(null);
let   joditInstance = null;

onMounted(() => {
    if (!editorEl.value) return;
    // Set content on the raw textarea BEFORE Jodit.make() reads it
    editorEl.value.value = form.value.content;
    joditInstance = Jodit.make(editorEl.value, {
        height: 520,
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
        uploader:                  { insertImageAsBase64URI: true },
        showCharsCounter:          false,
        showWordsCounter:          false,
        showXPathInStatusbar:      false,
        askBeforePasteHTML:        false,
        askBeforePasteFromWord:    false,
        defaultActionOnPaste:      'insert_clear_html',
    });
    joditInstance.events.on('change', (html) => {
        form.value.content = html;
    });
});

onBeforeUnmount(() => {
    joditInstance?.destruct();
    joditInstance = null;
});

// ── Auto-generate slug from title (new posts only) ─────────────────────────
watch(() => form.value.title, (val) => {
    if (!isEdit) {
        form.value.slug = val
            .toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
});

// ── Submit ─────────────────────────────────────────────────────────────────
function submit() {
    if (joditInstance) form.value.content = joditInstance.value;
    loading.value = true;
    errors.value  = {};
    const url    = isEdit ? `/admin/blog/${props.post.id}` : '/admin/blog';
    const method = isEdit ? 'put' : 'post';
    router[method](url, form.value, {
        onError:  (e) => { errors.value = e; },
        onFinish: ()  => { loading.value = false; },
    });
}
</script>

<template>
    <AdminLayout :title="isEdit ? 'Edit Post' : 'New Post'">

        <!-- ── Header bar ─────────────────────────────────────────────────── -->
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <Link href="/admin/blog" class="hover:text-violet-600 transition-colors">Blog Posts</Link>
                <span>/</span>
                <span class="text-gray-900 font-semibold">{{ isEdit ? 'Edit' : 'New Post' }}</span>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                <!-- Mobile preview toggle (hidden on xl) -->
                <div class="xl:hidden flex rounded-xl border border-gray-200 overflow-hidden text-sm">
                    <button
                        type="button"
                        class="px-4 py-2 font-semibold transition-colors"
                        :class="mobileView === 'edit' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        @click="mobileView = 'edit'"
                    >✏️ Edit</button>
                    <button
                        type="button"
                        class="px-4 py-2 font-semibold transition-colors"
                        :class="mobileView === 'preview' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        @click="mobileView = 'preview'"
                    >👁 Preview</button>
                </div>

                <!-- Content / SEO tabs -->
                <div class="flex rounded-xl border border-gray-200 overflow-hidden text-sm">
                    <button
                        type="button"
                        class="px-4 py-2 font-semibold transition-colors"
                        :class="tab === 'content' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        @click="tab = 'content'"
                    >Content</button>
                    <button
                        type="button"
                        class="px-4 py-2 font-semibold transition-colors"
                        :class="tab === 'seo' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                        @click="tab = 'seo'"
                    >SEO & Meta</button>
                </div>
            </div>
        </div>

        <!-- ── Split layout ───────────────────────────────────────────────── -->
        <div class="xl:grid xl:grid-cols-2 xl:gap-6 xl:items-start">

            <!-- ══ LEFT: Form ════════════════════════════════════════════════ -->
            <div :class="{ 'hidden': mobileView === 'preview' }" class="xl:block">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- ── CONTENT TAB ─────────────────────────────────────── -->
                    <div v-show="tab === 'content'" class="space-y-5">

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input v-model="form.title" type="text" required placeholder="Blog post title…"
                                class="w-full border rounded-xl px-4 py-3 text-gray-900 text-lg font-semibold focus:border-violet-400 outline-none transition-colors"
                                :class="errors.title ? 'border-red-400' : 'border-gray-200'">
                            <p v-if="errors.title" class="mt-1 text-red-500 text-xs">{{ errors.title }}</p>
                        </div>

                        <!-- Slug -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Slug</label>
                            <div class="flex items-center border rounded-xl overflow-hidden focus-within:border-violet-400 transition-colors"
                                :class="errors.slug ? 'border-red-400' : 'border-gray-200'">
                                <span class="px-3 py-3 bg-gray-50 text-gray-400 text-sm border-r border-gray-200 shrink-0">/blog/</span>
                                <input v-model="form.slug" type="text" placeholder="auto-generated"
                                    class="flex-1 px-3 py-3 text-sm focus:outline-none">
                            </div>
                            <p v-if="errors.slug" class="mt-1 text-red-500 text-xs">{{ errors.slug }}</p>
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Excerpt</label>
                            <textarea v-model="form.excerpt" rows="2"
                                placeholder="Short description shown in blog listing…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:border-violet-400 outline-none transition-colors resize-none"
                            ></textarea>
                        </div>

                        <!-- Jodit editor -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                            <textarea ref="editorEl"></textarea>
                            <p v-if="errors.content" class="mt-1 text-red-500 text-xs">{{ errors.content }}</p>
                        </div>

                        <!-- Publish toggle -->
                        <div class="flex items-center gap-4 bg-white rounded-xl border border-gray-200 p-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative" @click="form.is_published = !form.is_published">
                                    <div class="w-12 h-6 rounded-full transition-colors"
                                        :class="form.is_published ? 'bg-green-500' : 'bg-gray-300'"></div>
                                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform"
                                        :class="form.is_published ? 'translate-x-6' : ''"></div>
                                </div>
                                <span class="font-semibold text-gray-800">
                                    {{ form.is_published ? '✅ Published' : '📄 Draft' }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- ── SEO TAB ──────────────────────────────────────────── -->
                    <div v-show="tab === 'seo'" class="space-y-5 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-black text-gray-800 text-lg mb-4">SEO & Open Graph</h2>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Meta Title
                                <span class="text-gray-400 font-normal text-xs">(leave blank to use post title)</span>
                            </label>
                            <input v-model="form.meta_title" type="text" placeholder="SEO title…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                            <p class="text-xs text-gray-400 mt-0.5">{{ form.meta_title.length }}/255 chars</p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">Meta Description</label>
                            <textarea v-model="form.meta_description" rows="3"
                                placeholder="SEO description (150–160 chars ideal)…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors resize-none"
                            ></textarea>
                            <p class="text-xs mt-0.5" :class="form.meta_description.length > 160 ? 'text-orange-500' : 'text-gray-400'">
                                {{ form.meta_description.length }}/320 chars
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">
                                Meta Keywords
                                <span class="text-gray-400 font-normal text-xs">(comma-separated)</span>
                            </label>
                            <input v-model="form.meta_keywords" type="text"
                                placeholder="keyword1, keyword2, keyword3…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">OG Image URL</label>
                            <input v-model="form.og_image" type="url" placeholder="https://…"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                            <div v-if="form.og_image" class="mt-2">
                                <img :src="form.og_image" class="h-24 rounded-lg object-cover border border-gray-200"
                                    alt="OG preview" @error="$event.target.style.display='none'">
                            </div>
                        </div>

                        <!-- Google preview -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Google Preview</p>
                            <div class="bg-white rounded-lg p-4 shadow-sm">
                                <p class="text-xs text-green-600 mb-1">khantools.com › blog › {{ form.slug || 'your-slug' }}</p>
                                <p class="text-blue-700 font-semibold text-sm hover:underline cursor-pointer">
                                    {{ form.meta_title || form.title || 'Post Title' }}
                                </p>
                                <p class="text-gray-500 text-xs mt-1 line-clamp-2">
                                    {{ form.meta_description || form.excerpt || 'Post description will appear here…' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- ── Submit bar ──────────────────────────────────────── -->
                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" :disabled="loading"
                            class="px-8 py-3 bg-gradient-to-r from-violet-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100">
                            <span v-if="loading" class="animate-pulse">Saving…</span>
                            <span v-else>{{ isEdit ? '💾 Update Post' : '🚀 Publish Post' }}</span>
                        </button>
                        <Link href="/admin/blog"
                            class="px-5 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all font-medium">
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>

            <!-- ══ RIGHT: Live preview ════════════════════════════════════════ -->
            <!-- Desktop: always visible. Mobile: shown when mobileView==='preview' -->
            <div :class="{ 'hidden': mobileView === 'edit' }" class="xl:block xl:sticky xl:top-6">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                    <!-- Preview header -->
                    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Live Preview</span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full font-bold"
                            :class="form.is_published ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'">
                            {{ form.is_published ? 'Published' : 'Draft' }}
                        </span>
                    </div>

                    <!-- Preview body -->
                    <div class="p-6 max-h-[calc(100vh-200px)] overflow-y-auto">

                        <!-- Empty state -->
                        <div v-if="!form.title && !form.content" class="text-center text-gray-300 py-12">
                            <div class="text-4xl mb-2">📄</div>
                            <p class="text-sm font-medium">Start writing to see a preview</p>
                        </div>

                        <template v-else>
                            <h1 class="text-2xl font-black text-gray-900 leading-tight mb-3">
                                {{ form.title || 'Untitled Post' }}
                            </h1>
                            <p v-if="form.excerpt" class="text-gray-500 text-sm mb-5 border-l-4 border-violet-300 pl-3 italic">
                                {{ form.excerpt }}
                            </p>
                            <div
                                class="prose prose-sm prose-violet max-w-none"
                                v-html="form.content || '<p style=\'color:#ccc;font-style:italic\'>No content yet…</p>'"
                            ></div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </AdminLayout>
</template>
