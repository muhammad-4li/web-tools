<script setup>
import { ref, inject } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Cropper, CircleStencil } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

defineProps({ seo: Object });

const triggerAdPopup = inject('triggerAdPopup');

const imageUrl   = ref(null);
const cropperRef = ref(null);
const filename   = ref('circle-crop');

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

    // Draw the cropped square onto a new canvas with a circular clip
    const size   = canvas.width;
    const output = document.createElement('canvas');
    output.width  = size;
    output.height = size;
    const ctx = output.getContext('2d');
    ctx.beginPath();
    ctx.arc(size / 2, size / 2, size / 2, 0, Math.PI * 2);
    ctx.closePath();
    ctx.clip();
    ctx.drawImage(canvas, 0, 0);

    triggerAdPopup(() => {
        const link    = document.createElement('a');
        link.download = `${filename.value}-circle.png`;
        link.href     = output.toDataURL('image/png');
        link.click();
    });
}

function resetTool() { imageUrl.value = null; }
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-4xl mx-auto">

            <!-- Page header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-violet-600 text-3xl mb-4 shadow-lg">⭕</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Circle Image Crop</h1>
                <p class="text-gray-500">Crop your image into a perfect circle — outputs a transparent PNG, processed entirely in your browser.</p>
            </div>

            <!-- Upload zone -->
            <div v-if="!imageUrl"
                class="border-2 border-dashed border-fuchsia-300 rounded-2xl p-12 text-center hover:border-fuchsia-500 hover:bg-fuchsia-50 transition-all cursor-pointer group"
                @click="$refs.fileInput.click()"
            >
                <div class="text-5xl mb-4 group-hover:scale-110 transition-transform">🖼️</div>
                <p class="text-lg font-semibold text-gray-700 mb-1">Click to upload an image</p>
                <p class="text-gray-400 text-sm">PNG, JPG, WebP supported</p>
                <input ref="fileInput" type="file" accept="image/*" class="hidden" @change="onFileChange">
            </div>

            <!-- Cropper tool -->
            <div v-else class="space-y-4">

                <!-- Controls bar -->
                <div class="bg-white rounded-2xl shadow-sm border border-fuchsia-100 p-4 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <span class="text-fuchsia-500 font-semibold">⭕ Circle mode</span>
                        <span class="text-gray-300">|</span>
                        <span>Output: transparent PNG</span>
                    </div>
                    <button class="text-sm text-red-500 hover:text-red-700 transition-colors" @click="resetTool">✕ Reset</button>
                </div>

                <!-- The actual cropper with circular stencil -->
                <div class="rounded-2xl overflow-hidden border border-fuchsia-100 shadow-sm bg-gray-900" style="height: 480px;">
                    <Cropper
                        ref="cropperRef"
                        :src="imageUrl"
                        :stencil-component="CircleStencil"
                        class="h-full"
                    />
                </div>

                <!-- Download -->
                <div class="flex justify-center">
                    <button
                        class="px-8 py-3 bg-gradient-to-r from-fuchsia-600 to-violet-600 text-white font-bold rounded-full text-lg hover:shadow-xl hover:scale-105 transition-all"
                        @click="downloadCrop"
                    >
                        ⬇️ Download Circle PNG
                    </button>
                </div>
            </div>

            <!-- Info note -->
            <p class="text-center text-xs text-gray-400 mt-6">
                The downloaded PNG has a transparent background outside the circle — perfect for avatars and profile pictures.
            </p>

        </div>
    </AppLayout>
</template>
