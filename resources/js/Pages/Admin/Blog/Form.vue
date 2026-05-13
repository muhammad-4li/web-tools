<script setup>
import { ref, watch } from 'vue';
import { router, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import { StarterKit } from '@tiptap/starter-kit';
import { TextAlign } from '@tiptap/extension-text-align';
import { Underline } from '@tiptap/extension-underline';
import { Link as TipLink } from '@tiptap/extension-link';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import { Image } from '@tiptap/extension-image';

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

const errors  = ref({});
const loading = ref(false);
const tab     = ref('content'); // 'content' | 'seo'

const editor = useEditor({
    extensions: [
        StarterKit,
        TextAlign.configure({ types: ['heading', 'paragraph'] }),
        Underline,
        TipLink.configure({ openOnClick: false }),
        TextStyle,
        Color,
        Image,
    ],
    content: form.value.content,
    onUpdate: ({ editor }) => {
        form.value.content = editor.getHTML();
    },
    editorProps: {
        attributes: { class: 'prose prose-lg max-w-none min-h-[500px] focus:outline-none p-6' },
    },
});

// Auto-generate slug from title (new posts only)
watch(() => form.value.title, (val) => {
    if (!isEdit) {
        form.value.slug = val.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
});

function submit() {
    loading.value = true;
    errors.value  = {};
    const url    = isEdit ? `/admin/blog/${props.post.id}` : '/admin/blog';
    const method = isEdit ? 'put' : 'post';
    router[method](url, form.value, {
        onError:  (e) => { errors.value = e; },
        onFinish: ()  => { loading.value = false; },
    });
}

const toolbarGroups = [
    [
        { label: 'B',  title: 'Bold',      cmd: () => editor.value.chain().focus().toggleBold().run(),    active: () => editor.value?.isActive('bold') },
        { label: 'I',  title: 'Italic',    cmd: () => editor.value.chain().focus().toggleItalic().run(),  active: () => editor.value?.isActive('italic') },
        { label: 'U',  title: 'Underline', cmd: () => editor.value.chain().focus().toggleUnderline().run(), active: () => editor.value?.isActive('underline') },
        { label: 'S',  title: 'Strike',    cmd: () => editor.value.chain().focus().toggleStrike().run(),  active: () => editor.value?.isActive('strike') },
    ],
    [
        { label: 'H1', title: 'H1', cmd: () => editor.value.chain().focus().toggleHeading({ level: 1 }).run(), active: () => editor.value?.isActive('heading', { level: 1 }) },
        { label: 'H2', title: 'H2', cmd: () => editor.value.chain().focus().toggleHeading({ level: 2 }).run(), active: () => editor.value?.isActive('heading', { level: 2 }) },
        { label: 'H3', title: 'H3', cmd: () => editor.value.chain().focus().toggleHeading({ level: 3 }).run(), active: () => editor.value?.isActive('heading', { level: 3 }) },
    ],
    [
        { label: '•', title: 'Bullet list',  cmd: () => editor.value.chain().focus().toggleBulletList().run(),  active: () => editor.value?.isActive('bulletList') },
        { label: '1.', title: 'Ordered list', cmd: () => editor.value.chain().focus().toggleOrderedList().run(), active: () => editor.value?.isActive('orderedList') },
        { label: '❝', title: 'Blockquote',   cmd: () => editor.value.chain().focus().toggleBlockquote().run(), active: () => editor.value?.isActive('blockquote') },
        { label: '<>', title: 'Code',         cmd: () => editor.value.chain().focus().toggleCode().run(),        active: () => editor.value?.isActive('code') },
    ],
    [
        { label: '←', title: 'Left',   cmd: () => editor.value.chain().focus().setTextAlign('left').run() },
        { label: '↔', title: 'Center', cmd: () => editor.value.chain().focus().setTextAlign('center').run() },
        { label: '→', title: 'Right',  cmd: () => editor.value.chain().focus().setTextAlign('right').run() },
    ],
];
</script>

<template>
    <AdminLayout :title="isEdit ? 'Edit Post' : 'New Post'">

        <!-- Breadcrumb + tabs -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <Link href="/admin/blog" class="hover:text-violet-600 transition-colors">Blog Posts</Link>
                <span>/</span>
                <span class="text-gray-900 font-semibold">{{ isEdit ? 'Edit' : 'New Post' }}</span>
            </div>
            <div class="flex rounded-xl border border-gray-200 overflow-hidden">
                <button
                    class="px-4 py-2 text-sm font-semibold transition-colors"
                    :class="tab === 'content' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    @click="tab = 'content'"
                >Content</button>
                <button
                    class="px-4 py-2 text-sm font-semibold transition-colors"
                    :class="tab === 'seo' ? 'bg-violet-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-50'"
                    @click="tab = 'seo'"
                >SEO & Meta</button>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-5">

            <!-- CONTENT TAB -->
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
                    <div class="flex items-center border rounded-xl overflow-hidden focus-within:border-violet-400 transition-colors" :class="errors.slug ? 'border-red-400' : 'border-gray-200'">
                        <span class="px-3 py-3 bg-gray-50 text-gray-400 text-sm border-r border-gray-200 shrink-0">/blog/</span>
                        <input v-model="form.slug" type="text" placeholder="auto-generated" class="flex-1 px-3 py-3 text-sm focus:outline-none">
                    </div>
                    <p v-if="errors.slug" class="mt-1 text-red-500 text-xs">{{ errors.slug }}</p>
                </div>

                <!-- Excerpt -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Excerpt</label>
                    <textarea v-model="form.excerpt" rows="2" placeholder="Short description shown in blog listing…"
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700 focus:border-violet-400 outline-none transition-colors resize-none"
                    ></textarea>
                </div>

                <!-- TipTap editor -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Content <span class="text-red-500">*</span></label>
                    <div class="border border-gray-200 rounded-2xl overflow-hidden focus-within:border-violet-400 transition-colors bg-white">
                        <!-- Toolbar -->
                        <div class="border-b border-gray-100 px-4 py-2 flex flex-wrap gap-2 bg-gray-50">
                            <template v-for="(group, gi) in toolbarGroups" :key="gi">
                                <div class="flex gap-1">
                                    <button type="button" v-for="btn in group" :key="btn.title" :title="btn.title"
                                        class="px-2 py-1 rounded text-xs font-bold border transition-all"
                                        :class="btn.active?.() ? 'bg-violet-600 text-white border-violet-600' : 'bg-white text-gray-700 border-gray-200 hover:border-violet-300'"
                                        @click="btn.cmd()"
                                    >{{ btn.label }}</button>
                                </div>
                                <div v-if="gi < toolbarGroups.length - 1" class="w-px bg-gray-200 mx-1"></div>
                            </template>
                        </div>
                        <EditorContent v-if="editor" :editor="editor" />
                    </div>
                    <p v-if="errors.content" class="mt-1 text-red-500 text-xs">{{ errors.content }}</p>
                </div>

                <!-- Publish toggle -->
                <div class="flex items-center gap-4 bg-white rounded-xl border border-gray-200 p-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input v-model="form.is_published" type="checkbox" class="sr-only">
                            <div class="w-12 h-6 rounded-full transition-colors" :class="form.is_published ? 'bg-green-500' : 'bg-gray-300'"></div>
                            <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform" :class="form.is_published ? 'translate-x-6' : ''"></div>
                        </div>
                        <span class="font-semibold text-gray-800">{{ form.is_published ? '✅ Published' : '📄 Draft' }}</span>
                    </label>
                </div>
            </div>

            <!-- SEO TAB -->
            <div v-show="tab === 'seo'" class="space-y-5 bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-black text-gray-800 text-lg mb-4">SEO & Open Graph</h2>

                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Meta Title <span class="text-gray-400 font-normal text-xs">(leave blank to use post title)</span></label>
                        <input v-model="form.meta_title" type="text" placeholder="SEO title…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                        <p class="text-xs text-gray-400 mt-0.5">{{ form.meta_title.length }}/255 chars</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Meta Description</label>
                        <textarea v-model="form.meta_description" rows="3" placeholder="SEO description (150-160 chars ideal)…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors resize-none"
                        ></textarea>
                        <p class="text-xs" :class="form.meta_description.length > 160 ? 'text-orange-500' : 'text-gray-400'">
                            {{ form.meta_description.length }}/320 chars
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Meta Keywords <span class="text-gray-400 font-normal text-xs">(comma-separated)</span></label>
                        <input v-model="form.meta_keywords" type="text" placeholder="keyword1, keyword2, keyword3…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">OG Image URL</label>
                        <input v-model="form.og_image" type="url" placeholder="https://…"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors">
                        <div v-if="form.og_image" class="mt-2">
                            <img :src="form.og_image" class="h-24 rounded-lg object-cover border border-gray-200" alt="OG image preview" @error="$event.target.style.display='none'">
                        </div>
                    </div>
                </div>

                <!-- SEO Preview -->
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Google Preview</p>
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <p class="text-xs text-green-600 mb-1">ma-tools.com › blog › {{ form.slug || 'your-slug' }}</p>
                        <p class="text-blue-700 font-semibold text-sm hover:underline cursor-pointer">
                            {{ form.meta_title || form.title || 'Post Title' }}
                        </p>
                        <p class="text-gray-500 text-xs mt-1 line-clamp-2">
                            {{ form.meta_description || form.excerpt || 'Post description will appear here…' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit bar -->
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" :disabled="loading"
                    class="px-8 py-3 bg-gradient-to-r from-violet-600 to-pink-600 text-white font-bold rounded-xl hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                >
                    <span v-if="loading" class="animate-pulse">Saving…</span>
                    <span v-else>{{ isEdit ? '💾 Update Post' : '🚀 Publish Post' }}</span>
                </button>
                <Link href="/admin/blog" class="px-5 py-3 border border-gray-200 text-gray-600 rounded-xl hover:bg-gray-50 transition-all font-medium">
                    Cancel
                </Link>
            </div>
        </form>
    </AdminLayout>
</template>
