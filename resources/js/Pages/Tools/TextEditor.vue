<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useJoditEditor } from '@/Composables/useJoditEditor.js';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const htmlContent = ref('<p>Start typing your document here…</p>');
const { editorEl, getInstance } = useJoditEditor({ content: htmlContent, height: 500 });

function downloadHtml() {
    const inst = getInstance();
    const html = inst ? inst.value : htmlContent.value;
    const blob = new Blob([html], { type: 'text/html' });
    const url  = URL.createObjectURL(blob);
    triggerAdPopup(() => {
        const a    = document.createElement('a');
        a.href     = url;
        a.download = 'document.html';
        a.click();
    });
}

function downloadTxt() {
    const inst = getInstance();
    const text = inst ? inst.text : '';
    const blob = new Blob([text], { type: 'text/plain' });
    const url  = URL.createObjectURL(blob);
    triggerAdPopup(() => {
        const a    = document.createElement('a');
        a.href     = url;
        a.download = 'document.txt';
        a.click();
    });
}

function copyHtml() {
    const inst = getInstance();
    navigator.clipboard.writeText(inst ? inst.value : htmlContent.value);
}
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-green-500 to-emerald-600 text-3xl mb-4 shadow-lg">🖊️</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Text Editor</h1>
                <p class="text-gray-500">Rich text editor — format your document, then export as HTML or plain text.</p>
            </div>

            <!-- Editor -->
            <div class="bg-white rounded-2xl border border-green-100 shadow-sm">
                <textarea ref="editorEl"></textarea>
            </div>

            <!-- Export actions -->
            <div class="flex justify-center gap-3 mt-5 flex-wrap">
                <button
                    class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-full hover:shadow-lg hover:scale-105 transition-all"
                    @click="downloadHtml"
                >⬇️ Export HTML</button>
                <button
                    class="px-6 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-full hover:shadow-lg hover:scale-105 transition-all"
                    @click="downloadTxt"
                >⬇️ Export TXT</button>
                <button
                    class="px-6 py-2.5 border-2 border-gray-200 text-gray-700 font-semibold rounded-full hover:bg-gray-50 transition-all"
                    @click="copyHtml"
                >📋 Copy HTML</button>
            </div>
        </div>
    </AppLayout>
</template>
