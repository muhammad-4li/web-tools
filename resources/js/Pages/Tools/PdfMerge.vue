<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { PDFDocument } from 'pdf-lib';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const files    = ref([]);
const merging  = ref(false);
const merged   = ref(null);

function addFiles(e) {
    const newFiles = Array.from(e.target.files || e.dataTransfer?.files || [])
        .filter(f => f.type === 'application/pdf');
    files.value.push(...newFiles.map(f => ({ file: f, name: f.name, size: f.size })));
}

function removeFile(i) { files.value.splice(i, 1); }

function moveUp(i)   { if (i > 0)                   [files.value[i], files.value[i - 1]] = [files.value[i - 1], files.value[i]]; }
function moveDown(i) { if (i < files.value.length - 1) [files.value[i], files.value[i + 1]] = [files.value[i + 1], files.value[i]]; }

function formatSize(bytes) {
    return bytes < 1024 * 1024
        ? (bytes / 1024).toFixed(1) + ' KB'
        : (bytes / (1024 * 1024)).toFixed(2) + ' MB';
}

async function mergePDFs() {
    if (files.value.length < 2) return;
    merging.value = true;
    try {
        const merged_doc = await PDFDocument.create();
        for (const item of files.value) {
            const bytes  = await item.file.arrayBuffer();
            const srcDoc = await PDFDocument.load(bytes);
            const pages  = await merged_doc.copyPages(srcDoc, srcDoc.getPageIndices());
            pages.forEach(p => merged_doc.addPage(p));
        }
        const bytes = await merged_doc.save();
        const blob  = new Blob([bytes], { type: 'application/pdf' });
        merged.value = URL.createObjectURL(blob);
        triggerAdPopup();
    } finally {
        merging.value = false;
    }
}

function download() {
    const a    = document.createElement('a');
    a.href     = merged.value;
    a.download = 'merged.pdf';
    a.click();
}

function resetTool() { files.value = []; merged.value = null; }

// Drag-over highlight
const dragOver = ref(false);
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 text-3xl mb-4 shadow-lg">📄</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">PDF Merge</h1>
                <p class="text-gray-500">Combine multiple PDF files into one. Drag to reorder pages, then merge and download.</p>
            </div>

            <!-- Drop zone -->
            <div
                class="border-2 border-dashed rounded-2xl p-8 text-center transition-all"
                :class="dragOver ? 'border-orange-500 bg-orange-50' : 'border-orange-300 hover:border-orange-400 hover:bg-orange-50'"
                @dragover.prevent="dragOver = true"
                @dragleave="dragOver = false"
                @drop.prevent="e => { dragOver = false; addFiles(e); }"
                @click="$refs.fileInput.click()"
            >
                <div class="text-4xl mb-3">📁</div>
                <p class="font-semibold text-gray-700">Drop PDF files here or click to browse</p>
                <p class="text-gray-400 text-sm mt-1">Select multiple files at once</p>
                <input ref="fileInput" type="file" accept="application/pdf" multiple class="hidden" @change="addFiles">
            </div>

            <!-- File list -->
            <div v-if="files.length" class="mt-4 space-y-2">
                <div
                    v-for="(item, i) in files" :key="i"
                    class="flex items-center gap-3 bg-white rounded-xl border border-orange-100 px-4 py-3 shadow-sm"
                >
                    <span class="text-2xl">📄</span>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 truncate">{{ item.name }}</p>
                        <p class="text-xs text-gray-400">{{ formatSize(item.size) }}</p>
                    </div>
                    <div class="flex gap-1">
                        <button class="p-1.5 rounded-lg hover:bg-orange-50 text-gray-500 text-xs" @click="moveUp(i)" :disabled="i === 0">↑</button>
                        <button class="p-1.5 rounded-lg hover:bg-orange-50 text-gray-500 text-xs" @click="moveDown(i)" :disabled="i === files.length - 1">↓</button>
                        <button class="p-1.5 rounded-lg hover:bg-red-50 text-red-400 hover:text-red-600 text-xs" @click="removeFile(i)">✕</button>
                    </div>
                </div>

                <div class="flex justify-center gap-3 pt-4 flex-wrap">
                    <button v-if="!merged"
                        class="px-8 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="files.length < 2 || merging"
                        @click="mergePDFs"
                    >
                        <span v-if="merging" class="animate-pulse">⚙️ Merging…</span>
                        <span v-else>🔗 Merge {{ files.length }} PDFs</span>
                    </button>

                    <button v-if="merged"
                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                        @click="download"
                    >⬇️ Download merged.pdf</button>

                    <button class="px-5 py-3 border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50 transition-all text-sm" @click="resetTool">✕ Reset</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
