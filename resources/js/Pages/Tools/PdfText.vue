<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { PDFDocument, rgb, StandardFonts } from 'pdf-lib';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const pdfFile  = ref(null);
const pdfName  = ref('');
const loading  = ref(false);
const result   = ref(null);

const texts    = ref([]);
const newText  = ref({ text: '', page: 1, x: 50, y: 50, size: 14, color: '#000000' });

function onPdfChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    pdfName.value = file.name;
    pdfFile.value = file;
    result.value  = null;
    texts.value   = [];
}

function addText() {
    if (!newText.value.text.trim()) return;
    texts.value.push({ ...newText.value });
    newText.value.text = '';
}

function removeText(i) { texts.value.splice(i, 1); }

function hexToRgb(hex) {
    const r = parseInt(hex.slice(1, 3), 16) / 255;
    const g = parseInt(hex.slice(3, 5), 16) / 255;
    const b = parseInt(hex.slice(5, 7), 16) / 255;
    return rgb(r, g, b);
}

async function applyTexts() {
    if (!pdfFile.value || !texts.value.length) return;
    loading.value = true;
    try {
        const bytes = await pdfFile.value.arrayBuffer();
        const doc   = await PDFDocument.load(bytes);
        const font  = await doc.embedFont(StandardFonts.Helvetica);
        const pages = doc.getPages();

        for (const t of texts.value) {
            const page = pages[Math.min(t.page - 1, pages.length - 1)];
            const { height } = page.getSize();
            page.drawText(t.text, {
                x:    t.x,
                y:    height - t.y,
                size: t.size,
                font,
                color: hexToRgb(t.color),
            });
        }

        const finalBytes = await doc.save();
        const blob       = new Blob([finalBytes], { type: 'application/pdf' });
        result.value     = URL.createObjectURL(blob);
        triggerAdPopup();
    } finally {
        loading.value = false;
    }
}

function download() {
    const a    = document.createElement('a');
    a.href     = result.value;
    a.download = pdfName.value.replace('.pdf', '-with-text.pdf');
    a.click();
}

function resetTool() { pdfFile.value = null; result.value = null; texts.value = []; }
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-3xl mb-4 shadow-lg">📝</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Add Text to PDF</h1>
                <p class="text-gray-500">Add text overlays to any PDF page — choose font size, color and exact position.</p>
            </div>

            <!-- Upload -->
            <div class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-500 text-white rounded-full text-xs flex items-center justify-center font-black">1</span> Upload PDF
                </h2>
                <div class="flex items-center gap-4">
                    <button class="px-5 py-2 border-2 border-blue-300 rounded-xl text-blue-700 font-semibold hover:bg-blue-50 transition-all text-sm" @click="$refs.pdfInput.click()">📂 Choose PDF</button>
                    <span class="text-sm" :class="pdfName ? 'text-gray-700 font-medium' : 'text-gray-400'">{{ pdfName || 'No file selected' }}</span>
                    <input ref="pdfInput" type="file" accept="application/pdf" class="hidden" @change="onPdfChange">
                </div>
            </div>

            <!-- Add text items -->
            <div v-if="pdfFile" class="bg-white rounded-2xl border border-blue-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="w-6 h-6 bg-blue-500 text-white rounded-full text-xs flex items-center justify-center font-black">2</span> Add Text
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">
                    <input v-model="newText.text" placeholder="Enter text…" class="col-span-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-blue-400 outline-none">
                    <label class="text-xs">
                        <span class="text-gray-600 font-medium block mb-1">Page</span>
                        <input v-model.number="newText.page" type="number" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-blue-400 outline-none">
                    </label>
                    <label class="text-xs">
                        <span class="text-gray-600 font-medium block mb-1">Font Size (pt)</span>
                        <input v-model.number="newText.size" type="number" min="6" max="72" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-blue-400 outline-none">
                    </label>
                    <label class="text-xs">
                        <span class="text-gray-600 font-medium block mb-1">X (from left)</span>
                        <input v-model.number="newText.x" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-blue-400 outline-none">
                    </label>
                    <label class="text-xs">
                        <span class="text-gray-600 font-medium block mb-1">Y (from top)</span>
                        <input v-model.number="newText.y" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-blue-400 outline-none">
                    </label>
                    <label class="text-xs flex items-center gap-2">
                        <span class="text-gray-600 font-medium">Color</span>
                        <input v-model="newText.color" type="color" class="w-10 h-8 border border-gray-200 rounded cursor-pointer">
                        <span class="text-gray-500">{{ newText.color }}</span>
                    </label>
                </div>

                <button class="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-semibold hover:bg-blue-600 transition-colors disabled:opacity-50" :disabled="!newText.text.trim()" @click="addText">
                    + Add Text Layer
                </button>

                <!-- Text list -->
                <div v-if="texts.length" class="mt-4 space-y-2">
                    <div v-for="(t, i) in texts" :key="i" class="flex items-center gap-3 bg-blue-50 rounded-xl px-4 py-2">
                        <span :style="`color: ${t.color}; font-size: ${Math.min(t.size, 18)}px; font-weight: 600;`">{{ t.text }}</span>
                        <span class="text-xs text-gray-400 ml-auto">p.{{ t.page }} | x:{{ t.x }} y:{{ t.y }} | {{ t.size }}pt</span>
                        <button class="text-red-400 hover:text-red-600 text-xs ml-2" @click="removeText(i)">✕</button>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-3 flex-wrap">
                <button v-if="!result"
                    class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!pdfFile || !texts.length || loading"
                    @click="applyTexts"
                >
                    <span v-if="loading" class="animate-pulse">⚙️ Applying…</span>
                    <span v-else>📝 Apply Text</span>
                </button>

                <button v-if="result"
                    class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                    @click="download"
                >⬇️ Download PDF</button>

                <button class="px-5 py-3 border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50 transition-all text-sm" @click="resetTool">✕ Reset</button>
            </div>
        </div>
    </AppLayout>
</template>
