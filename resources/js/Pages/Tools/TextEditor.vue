<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useEditor, EditorContent } from '@tiptap/vue-3';
import { StarterKit } from '@tiptap/starter-kit';
import { TextAlign } from '@tiptap/extension-text-align';
import { Underline } from '@tiptap/extension-underline';
import { Link } from '@tiptap/extension-link';
import { TextStyle } from '@tiptap/extension-text-style';
import { Color } from '@tiptap/extension-color';
import { Image } from '@tiptap/extension-image';
import { CharacterCount } from '@tiptap/extension-character-count';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const editor = useEditor({
    extensions: [
        StarterKit,
        TextAlign.configure({ types: ['heading', 'paragraph'] }),
        Underline,
        Link.configure({ openOnClick: false }),
        TextStyle,
        Color,
        Image,
        CharacterCount,
    ],
    content: '<p>Start typing your document here…</p>',
    editorProps: {
        attributes: { class: 'prose prose-lg max-w-none min-h-[400px] focus:outline-none p-6' },
    },
});

function downloadHtml() {
    const blob = new Blob([editor.value.getHTML()], { type: 'text/html' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = 'document.html';
    a.click();
    triggerAdPopup();
}

function downloadTxt() {
    const blob = new Blob([editor.value.getText()], { type: 'text/plain' });
    const a    = document.createElement('a');
    a.href     = URL.createObjectURL(blob);
    a.download = 'document.txt';
    a.click();
    triggerAdPopup();
}

function copyHtml() {
    navigator.clipboard.writeText(editor.value.getHTML());
}

const toolbarGroups = [
    [
        { label: 'B',  title: 'Bold',          cmd: () => editor.value.chain().focus().toggleBold().run(),          isActive: () => editor.value?.isActive('bold') },
        { label: 'I',  title: 'Italic',         cmd: () => editor.value.chain().focus().toggleItalic().run(),        isActive: () => editor.value?.isActive('italic') },
        { label: 'U',  title: 'Underline',      cmd: () => editor.value.chain().focus().toggleUnderline().run(),     isActive: () => editor.value?.isActive('underline') },
        { label: 'S',  title: 'Strikethrough',  cmd: () => editor.value.chain().focus().toggleStrike().run(),        isActive: () => editor.value?.isActive('strike') },
    ],
    [
        { label: 'H1', title: 'Heading 1',  cmd: () => editor.value.chain().focus().toggleHeading({ level: 1 }).run(), isActive: () => editor.value?.isActive('heading', { level: 1 }) },
        { label: 'H2', title: 'Heading 2',  cmd: () => editor.value.chain().focus().toggleHeading({ level: 2 }).run(), isActive: () => editor.value?.isActive('heading', { level: 2 }) },
        { label: 'H3', title: 'Heading 3',  cmd: () => editor.value.chain().focus().toggleHeading({ level: 3 }).run(), isActive: () => editor.value?.isActive('heading', { level: 3 }) },
    ],
    [
        { label: '≡',  title: 'Left',       cmd: () => editor.value.chain().focus().setTextAlign('left').run(),   isActive: () => editor.value?.isActive({ textAlign: 'left' }) },
        { label: '≡',  title: 'Center',     cmd: () => editor.value.chain().focus().setTextAlign('center').run(), isActive: () => editor.value?.isActive({ textAlign: 'center' }) },
        { label: '≡',  title: 'Right',      cmd: () => editor.value.chain().focus().setTextAlign('right').run(),  isActive: () => editor.value?.isActive({ textAlign: 'right' }) },
    ],
    [
        { label: '• List',  title: 'Bullet list',  cmd: () => editor.value.chain().focus().toggleBulletList().run(),  isActive: () => editor.value?.isActive('bulletList') },
        { label: '1. List', title: 'Ordered list', cmd: () => editor.value.chain().focus().toggleOrderedList().run(), isActive: () => editor.value?.isActive('orderedList') },
        { label: '❝',       title: 'Blockquote',   cmd: () => editor.value.chain().focus().toggleBlockquote().run(), isActive: () => editor.value?.isActive('blockquote') },
        { label: '</>',     title: 'Code block',   cmd: () => editor.value.chain().focus().toggleCodeBlock().run(),  isActive: () => editor.value?.isActive('codeBlock') },
    ],
];
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 text-3xl mb-4 shadow-lg">🖊️</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Text Editor</h1>
                <p class="text-gray-500">Rich text editor — format your document, then export as HTML or plain text.</p>
            </div>

            <div class="bg-white rounded-2xl border border-green-100 shadow-sm overflow-hidden">

                <!-- Toolbar -->
                <div class="border-b border-gray-100 px-4 py-2 flex flex-wrap gap-2 bg-gradient-to-r from-green-50 to-emerald-50">
                    <template v-for="(group, gi) in toolbarGroups" :key="gi">
                        <div class="flex gap-1">
                            <button
                                v-for="btn in group" :key="btn.title"
                                :title="btn.title"
                                class="px-2.5 py-1 rounded text-sm font-bold border transition-all"
                                :class="btn.isActive?.()
                                    ? 'bg-green-600 text-white border-green-600'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-green-400 hover:bg-green-50'"
                                @click="btn.cmd()"
                            >{{ btn.label }}</button>
                        </div>
                        <div v-if="gi < toolbarGroups.length - 1" class="w-px bg-gray-200 mx-1"></div>
                    </template>

                    <!-- Character count -->
                    <div class="ml-auto text-xs text-gray-400 flex items-center gap-1 self-center">
                        {{ editor?.storage?.characterCount?.words?.() ?? 0 }} words
                    </div>
                </div>

                <!-- Editor content -->
                <EditorContent v-if="editor" :editor="editor" />
            </div>

            <!-- Export actions -->
            <div class="flex justify-center gap-3 mt-5 flex-wrap">
                <button class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-full hover:shadow-lg hover:scale-105 transition-all" @click="downloadHtml">
                    ⬇️ Export HTML
                </button>
                <button class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-full hover:shadow-lg hover:scale-105 transition-all" @click="downloadTxt">
                    ⬇️ Export TXT
                </button>
                <button class="px-6 py-2.5 border-2 border-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-50 transition-all" @click="copyHtml">
                    📋 Copy HTML
                </button>
            </div>
        </div>
    </AppLayout>
</template>
