<script setup>
import { ref, inject, onUnmounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({ seo: Object });

const triggerAdPopup = inject('triggerAdPopup');

const imageUrl    = ref(null);
const cropperRef  = ref(null);
const filename    = ref('cropped');
const format      = ref('image/png');
const aspectRatio = ref(NaN);   // NaN = free

const aspects = [
    { label: 'Free',  value: NaN },
    { label: '1:1',   value: 1 },
    { label: '4:3',   value: 4 / 3 },
    { label: '16:9',  value: 16 / 9 },
    { label: '3:2',   value: 3 / 2 },
    { label: '2:3',   value: 2 / 3 },
];

function onFileChange(e) {
    const file = e.target.files[0];
    if (!file) return;
    filename.value = file.name.replace(/\.[^.]+$/, '');
    const reader  = new FileReader();
    reader.onload = (ev) => { imageUrl.value = ev.target.result; };
    reader.readAsDataURL(file);
}

function downloadCrop() {
    const { canvas } = cropperRef.value.getResult();
    if (!canvas) return;
    const ext  = format.value === 'image/jpeg' ? 'jpg' : 'png';
    const link = document.createElement('a');
    link.download = `${filename.value}-cropped.${ext}`;
    link.href     = canvas.toDataURL(format.value, 0.92);
    link.click();
    triggerAdPopup();
}

function resetTool() { imageUrl.value = null; }
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-4xl mx-auto">

            <!-- Page header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-500 to-purple-600 text-3xl mb-4 shadow-lg">✂️</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Image Crop</h1>
                <p class="text-gray-500">Crop your image to any size or aspect ratio — processed entirely in your browser.</p>
            </div>

            <!-- Upload zone -->
            <div v-if="!imageUrl"
                class="border-2 border-dashed border-violet-300 rounded-2xl p-12 text-center hover:border-violet-500 hover:bg-violet-50 transition-all cursor-pointer group"
                @click="$refs.fileInput.click()"
            >
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">🖼️</div>
                <p class="text-lg font-semibold text-gray-700 mb-1">Click to upload an image</p>
                <p class="text-gray-400 text-sm">PNG, JPG, WebP, GIF supported</p>
                <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange">
            </div>

            <!-- Cropper tool -->
            <div v-else class="space-y-4">

                <!-- Controls bar -->
                <div class="bg-white rounded-2xl shadow-sm border border-violet-100 p-4 flex flex-wrap items-center gap-4">

                    <!-- Aspect ratio buttons -->
                    <div class="flex items-center gap-1 flex-wrap">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider mr-1">Ratio:</span>
                        <button
                            v-for="a in aspects" :key="a.label"
                            class="px-3 py-1 rounded-lg text-sm font-medium border transition-all"
                            :class="String(aspectRatio) === String(a.value)
                                ? 'bg-violet-600 text-white border-violet-600'
                                : 'bg-white text-gray-600 border-gray-200 hover:border-violet-400'"
                            @click="aspectRatio = a.value"
                        >{{ a.label }}</button>
                    </div>

                    <!-- Format -->
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Format:</span>
                        <select v-model="format" class="border border-gray-200 rounded-lg px-2 py-1 text-sm text-gray-700 focus:border-violet-400 outline-none">
                            <option value="image/png">PNG</option>
                            <option value="image/jpeg">JPEG</option>
                            <option value="image/webp">WebP</option>
                        </select>
                    </div>

                    <button class="text-sm text-red-500 hover:text-red-700 ml-2 transition-colors" @click="resetTool">✕ Reset</button>
                </div>

                <!-- The actual cropper -->
                <div class="rounded-2xl overflow-hidden border border-violet-100 shadow-sm bg-gray-900" style="height: 480px;">
                    <Cropper
                        ref="cropperRef"
                        :src="imageUrl"
                        :stencil-props="{ aspectRatio: isNaN(aspectRatio) ? undefined : aspectRatio }"
                        class="h-full"
                    />
                </div>

                <!-- Download -->
                <div class="flex justify-center">
                    <button
                        class="px-8 py-3 bg-gradient-to-r from-violet-600 to-purple-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                        @click="downloadCrop"
                    >
                        ⬇️ Download Cropped Image
                    </button>
                </div>
            </div>

        </div>
    </AppLayout>
</template>
