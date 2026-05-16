<script setup>
import { ref, inject, onMounted, onUnmounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { PDFDocument, rgb } from 'pdf-lib';
import SignaturePad from 'signature_pad';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const pdfFile   = ref(null);
const pdfName   = ref('');
const sigPad    = ref(null);
const canvasEl  = ref(null);
const placing   = ref(false);
const signed    = ref(null);
const loading   = ref(false);

const sig = ref({ x: 60, y: 60, width: 200, height: 60, page: 1, totalPages: 1 });

onMounted(() => {
    sigPad.value = new SignaturePad(canvasEl.value, {
        penColor: '#1e40af',
        minWidth: 1.5,
        maxWidth: 3,
    });
});

onUnmounted(() => { sigPad.value?.off(); });

function onPdfChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    pdfName.value = file.name;
    pdfFile.value = file;
    signed.value  = null;
}

function clearSig() { sigPad.value.clear(); }

async function embedSignature() {
    if (!pdfFile.value || sigPad.value.isEmpty()) return;
    loading.value = true;
    try {
        const pdfBytes = await pdfFile.value.arrayBuffer();
        const doc      = await PDFDocument.load(pdfBytes);
        const pages    = doc.getPages();
        const page     = pages[Math.min(sig.value.page - 1, pages.length - 1)];

        // Convert signature canvas to PNG bytes
        const sigDataUrl = sigPad.value.toDataURL('image/png');
        const sigRes     = await fetch(sigDataUrl);
        const sigBytes   = await sigRes.arrayBuffer();
        const sigImage   = await doc.embedPng(sigBytes);

        const { height } = page.getSize();
        page.drawImage(sigImage, {
            x:      sig.value.x,
            y:      height - sig.value.y - sig.value.height,
            width:  sig.value.width,
            height: sig.value.height,
        });

        const finalBytes = await doc.save();
        const blob       = new Blob([finalBytes], { type: 'application/pdf' });
        signed.value     = URL.createObjectURL(blob);
    } finally {
        loading.value = false;
    }
}

function download() {
    const a    = document.createElement('a');
    a.href     = signed.value;
    a.download = pdfName.value.replace('.pdf', '-signed.pdf');
    a.click();
}

function resetTool() { pdfFile.value = null; signed.value = null; sigPad.value?.clear(); }
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-3xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-cyan-600 text-3xl mb-4 shadow-lg">✍️</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">PDF Sign</h1>
                <p class="text-gray-500">Draw your signature and embed it into any PDF — all in your browser.</p>
            </div>

            <!-- Step 1: Upload PDF -->
            <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><span class="w-6 h-6 bg-teal-500 text-white rounded-full text-xs flex items-center justify-center font-black">1</span> Upload PDF</h2>
                <div class="flex items-center gap-4">
                    <button class="px-5 py-2 border-2 border-teal-300 rounded-xl text-teal-700 font-semibold hover:bg-teal-50 transition-all text-sm" @click="$refs.pdfInput.click()">
                        📂 Choose PDF
                    </button>
                    <span v-if="pdfName" class="text-gray-700 font-medium text-sm">{{ pdfName }}</span>
                    <span v-else class="text-gray-400 text-sm">No file selected</span>
                    <input ref="pdfInput" type="file" accept="application/pdf" class="hidden" @change="onPdfChange">
                </div>
            </div>

            <!-- Step 2: Draw signature -->
            <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><span class="w-6 h-6 bg-teal-500 text-white rounded-full text-xs flex items-center justify-center font-black">2</span> Draw Your Signature</h2>
                <div class="border-2 border-gray-200 rounded-xl overflow-hidden bg-gray-50" style="height: 160px;">
                    <canvas ref="canvasEl" class="w-full h-full touch-none" width="680" height="160"></canvas>
                </div>
                <button class="mt-2 text-xs text-red-500 hover:text-red-700 transition-colors" @click="clearSig">Clear signature</button>
            </div>

            <!-- Step 3: Position -->
            <div class="bg-white rounded-2xl border border-teal-100 shadow-sm p-6 mb-4">
                <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2"><span class="w-6 h-6 bg-teal-500 text-white rounded-full text-xs flex items-center justify-center font-black">3</span> Position on PDF</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    <label class="text-sm">
                        <span class="text-gray-600 font-medium block mb-1">Page</span>
                        <input v-model.number="sig.page" type="number" min="1" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-teal-400 outline-none">
                    </label>
                    <label class="text-sm">
                        <span class="text-gray-600 font-medium block mb-1">X (px from left)</span>
                        <input v-model.number="sig.x" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-teal-400 outline-none">
                    </label>
                    <label class="text-sm">
                        <span class="text-gray-600 font-medium block mb-1">Y (px from top)</span>
                        <input v-model.number="sig.y" type="number" min="0" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-teal-400 outline-none">
                    </label>
                    <label class="text-sm">
                        <span class="text-gray-600 font-medium block mb-1">Width (px)</span>
                        <input v-model.number="sig.width" type="number" min="50" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-teal-400 outline-none">
                    </label>
                    <label class="text-sm">
                        <span class="text-gray-600 font-medium block mb-1">Height (px)</span>
                        <input v-model.number="sig.height" type="number" min="20" class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-teal-400 outline-none">
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-center gap-3 flex-wrap">
                <button v-if="!signed"
                    class="px-8 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="!pdfFile || loading"
                    @click="embedSignature"
                >
                    <span v-if="loading" class="animate-pulse">⚙️ Signing…</span>
                    <span v-else>✍️ Embed Signature</span>
                </button>

                <button v-if="signed"
                    class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                    @click="triggerAdPopup(() => download())"
                >⬇️ Download Signed PDF</button>

                <button class="px-5 py-3 border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50 transition-all text-sm" @click="resetTool">✕ Reset</button>
            </div>
        </div>
    </AppLayout>
</template>
