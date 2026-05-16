<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { removeBackground } from '@imgly/background-removal';

defineProps({ seo: Object });
const triggerAdPopup = inject('triggerAdPopup');

const original  = ref(null);
const resultUrl = ref(null);
const loading   = ref(false);
const progress  = ref(0);
const error     = ref(null);
const filename  = ref('image');

function onFileChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    filename.value = file.name.replace(/\.[^.]+$/, '');
    original.value = URL.createObjectURL(file);
    resultUrl.value = null;
    error.value     = null;
}

async function processRemove() {
    if (!original.value) return;
    loading.value  = true;
    progress.value = 0;
    error.value    = null;
    try {
        const response = await fetch(original.value);
        const blob     = await response.blob();
        const result   = await removeBackground(blob, {
            progress: (key, cur, total) => {
                progress.value = total > 0 ? Math.round((cur / total) * 100) : 0;
            },
        });
        resultUrl.value = URL.createObjectURL(result);
    } catch (e) {
        error.value = 'Failed to remove background. Please try a different image.';
        console.error(e);
    } finally {
        loading.value = false;
    }
}

function downloadResult() {
    triggerAdPopup(() => {
        const a    = document.createElement('a');
        a.href     = resultUrl.value;
        a.download = `${filename.value}-no-bg.png`;
        a.click();
    });
}

function resetTool() {
    original.value  = null;
    resultUrl.value = null;
    loading.value   = false;
    error.value     = null;
}
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-4xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 text-3xl mb-4 shadow-lg">🪄</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Background Remover</h1>
                <p class="text-gray-500">AI-powered background removal — runs 100% in your browser, nothing uploaded.</p>
            </div>

            <!-- Upload -->
            <div v-if="!original"
                class="border-2 border-dashed border-pink-300 rounded-2xl p-12 text-center hover:border-pink-500 hover:bg-pink-50 transition-all cursor-pointer group"
                @click="$refs.fileInput.click()"
            >
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">🖼️</div>
                <p class="text-lg font-semibold text-gray-700 mb-1">Upload an image</p>
                <p class="text-gray-400 text-sm">PNG, JPG, WebP — best results with clear subjects</p>
                <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange">
            </div>

            <!-- Image view + action -->
            <div v-else class="space-y-4">

                <!-- Before/After grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl overflow-hidden border border-pink-100 bg-gray-50">
                        <div class="bg-pink-50 px-4 py-2 text-xs font-bold text-pink-700 uppercase tracking-wider">Original</div>
                        <img :src="original" class="w-full object-contain max-h-80" alt="Original">
                    </div>
                    <div class="rounded-2xl overflow-hidden border border-emerald-100"
                         style="background-image: repeating-conic-gradient(#e5e7eb 0% 25%, #fff 0% 50%) 0 0 / 20px 20px;">
                        <div class="bg-emerald-50 px-4 py-2 text-xs font-bold text-emerald-700 uppercase tracking-wider">Result</div>
                        <div class="min-h-[200px] flex items-center justify-center">
                            <img v-if="resultUrl" :src="resultUrl" class="w-full object-contain max-h-80" alt="Result">
                            <span v-else-if="!loading" class="text-gray-400 text-sm">Result will appear here</span>
                        </div>
                    </div>
                </div>

                <!-- Progress -->
                <div v-if="loading" class="bg-white rounded-2xl border border-pink-100 p-6 text-center">
                    <div class="text-2xl mb-3 animate-pulse">🤖</div>
                    <p class="text-pink-700 font-semibold mb-3">AI is processing your image…</p>
                    <div class="w-full bg-gray-100 rounded-full h-3 overflow-hidden">
                        <div
                            class="h-3 rounded-full bg-gradient-to-r from-pink-500 to-rose-500 transition-all duration-300"
                            :style="`width: ${progress}%`"
                        ></div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">{{ progress }}% — First run downloads the AI model (~5 MB)</p>
                </div>

                <!-- Error -->
                <div v-if="error" class="bg-red-50 border border-red-200 rounded-xl p-4 text-red-700 text-sm">⚠️ {{ error }}</div>

                <!-- Actions -->
                <div class="flex justify-center gap-3 flex-wrap">
                    <button v-if="!resultUrl && !loading"
                        class="px-8 py-3 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                        @click="processRemove"
                    >🪄 Remove Background</button>

                    <button v-if="resultUrl"
                        class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                        @click="downloadResult"
                    >⬇️ Download PNG</button>

                    <button class="px-5 py-3 border border-gray-200 text-gray-600 rounded-full hover:bg-gray-50 transition-all text-sm" @click="resetTool">✕ Reset</button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
