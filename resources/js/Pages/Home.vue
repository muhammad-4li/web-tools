<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ seo: Object });

const tools = [
    {
        name:    'Image Crop',
        desc:    'Crop images to any size or aspect ratio instantly.',
        href:    '/tools/image-crop',
        icon:    '✂️',
        gradient:'from-violet-500 to-purple-600',
        glow:    '#7c3aed',
        bg:      'from-violet-50 to-purple-50',
        border:  'border-violet-200',
    },
    {
        name:    'BG Remover',
        desc:    'Remove image backgrounds with AI — 100% in browser.',
        href:    '/tools/image-bg-remover',
        icon:    '🪄',
        gradient:'from-pink-500 to-rose-600',
        glow:    '#ec4899',
        bg:      'from-pink-50 to-rose-50',
        border:  'border-pink-200',
    },
    {
        name:    'PDF Merge',
        desc:    'Combine multiple PDFs into one file with drag & drop.',
        href:    '/tools/pdf-merge',
        icon:    '📄',
        gradient:'from-orange-500 to-amber-600',
        glow:    '#f97316',
        bg:      'from-orange-50 to-amber-50',
        border:  'border-orange-200',
    },
    {
        name:    'PDF Sign',
        desc:    'Draw your signature and embed it into any PDF.',
        href:    '/tools/pdf-sign',
        icon:    '✍️',
        gradient:'from-teal-500 to-cyan-600',
        glow:    '#14b8a6',
        bg:      'from-teal-50 to-cyan-50',
        border:  'border-teal-200',
    },
    {
        name:    'PDF Text',
        desc:    'Add text overlays to any page of a PDF document.',
        href:    '/tools/pdf-text',
        icon:    '📝',
        gradient:'from-blue-500 to-indigo-600',
        glow:    '#3b82f6',
        bg:      'from-blue-50 to-indigo-50',
        border:  'border-blue-200',
    },
    {
        name:    'Text Editor',
        desc:    'Rich text editor — format, export as HTML or plain text.',
        href:    '/tools/text-editor',
        icon:    '🖊️',
        gradient:'from-green-500 to-emerald-600',
        glow:    '#22c55e',
        bg:      'from-green-50 to-emerald-50',
        border:  'border-green-200',
    },
];
</script>

<template>
    <AppLayout :seo="seo">

        <!-- HERO -->
        <section class="text-center py-14 px-4">
            <div class="inline-flex items-center gap-2 bg-violet-100 text-violet-700 rounded-full px-4 py-1.5 text-sm font-semibold mb-6">
                🔒 100% Private — Files Never Leave Your Browser
            </div>
            <h1 class="text-5xl md:text-6xl font-black tracking-tight text-gray-900 mb-5 leading-tight">
                Free Online
                <span class="gradient-text block">Web Tools</span>
            </h1>
            <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed">
                Crop images, remove backgrounds, merge PDFs, add signatures and more — all processed directly in your browser. Nothing uploaded to our servers.
            </p>
        </section>

        <!-- TOOL CARDS GRID -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-2 pb-12">
            <Link
                v-for="tool in tools"
                :key="tool.href"
                :href="tool.href"
                class="group relative rounded-2xl border bg-gradient-to-br p-6 transition-all duration-300 cursor-pointer tool-card-glow"
                :class="[tool.bg, tool.border]"
                :style="`--glow-color: ${tool.glow}55`"
            >
                <!-- Icon -->
                <div
                    class="w-14 h-14 rounded-xl flex items-center justify-center text-2xl mb-4 shadow-md bg-gradient-to-br transition-transform group-hover:scale-110 duration-300"
                    :class="`bg-gradient-to-br ${tool.gradient}`"
                >
                    {{ tool.icon }}
                </div>

                <!-- Text -->
                <h2 class="font-bold text-gray-900 text-xl mb-2">{{ tool.name }}</h2>
                <p class="text-gray-500 text-sm leading-relaxed">{{ tool.desc }}</p>

                <!-- Arrow -->
                <div class="absolute top-5 right-5 opacity-0 group-hover:opacity-100 transition-all duration-200 translate-x-2 group-hover:translate-x-0">
                    <div
                        class="w-7 h-7 rounded-full flex items-center justify-center bg-gradient-to-br text-white text-sm"
                        :class="`bg-gradient-to-br ${tool.gradient}`"
                    >→</div>
                </div>
            </Link>
        </section>

        <!-- FEATURES STRIP -->
        <section class="bg-gradient-to-r from-violet-600 via-purple-600 to-pink-600 rounded-2xl p-8 mb-10 text-white text-center">
            <h2 class="text-2xl font-black mb-6">Why MA Tools?</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div v-for="f in features" :key="f.label" class="flex flex-col items-center gap-2">
                    <span class="text-3xl">{{ f.icon }}</span>
                    <span class="font-bold text-sm">{{ f.label }}</span>
                    <span class="text-violet-200 text-xs">{{ f.sub }}</span>
                </div>
            </div>
        </section>

    </AppLayout>
</template>

<script>
export default {
    data() {
        return {
            features: [
                { icon: '🔒', label: 'Privacy First',   sub: 'Files never uploaded' },
                { icon: '⚡', label: 'Instant Results', sub: 'Client-side processing' },
                { icon: '🆓', label: '100% Free',       sub: 'No sign-up needed' },
                { icon: '📱', label: 'Mobile Friendly', sub: 'Works on all devices' },
            ],
        };
    },
};
</script>
