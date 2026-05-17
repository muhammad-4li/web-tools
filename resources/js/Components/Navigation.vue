<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const mobileOpen = ref(false);

const tools = [
    { name: 'Image Crop',   href: '/tools/image-crop',       icon: '✂️',  color: 'from-violet-500 to-purple-600' },
    { name: 'BG Remover',   href: '/tools/image-bg-remover', icon: '🪄',  color: 'from-pink-500 to-rose-600' },
    { name: 'PDF Merge',    href: '/tools/pdf-merge',         icon: '📄',  color: 'from-orange-500 to-amber-600' },
    { name: 'PDF Sign',     href: '/tools/pdf-sign',          icon: '✍️',  color: 'from-teal-500 to-cyan-600' },
    { name: 'PDF Text',     href: '/tools/pdf-text',          icon: '📝',  color: 'from-blue-500 to-indigo-600' },
    { name: 'Text Editor',  href: '/tools/text-editor',       icon: '🖊️',  color: 'from-green-500 to-emerald-600' },
];
</script>

<template>
    <nav class="bg-white/80 backdrop-blur-md border-b border-violet-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-screen-2xl mx-auto px-4">
            <div class="flex items-center justify-between h-16">

                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2 group">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                        <span class="text-white font-black text-sm">KN</span>
                    </div>
                    <span class="font-black text-xl tracking-tight">
                        <span class="text-violet-600">Khan</span><span class="text-gray-900">Tools</span>
                    </span>
                </Link>

                <!-- Desktop Nav -->
                <div class="hidden md:flex items-center gap-1">
                    <template v-for="tool in tools" :key="tool.href">
                        <Link
                            :href="tool.href"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:text-violet-700 hover:bg-violet-50 transition-all"
                        >
                            <span>{{ tool.icon }}</span>
                            <span>{{ tool.name }}</span>
                        </Link>
                    </template>
                    <Link href="/blog" class="ml-2 px-4 py-1.5 rounded-full bg-gradient-to-r from-violet-600 to-pink-600 text-white text-sm font-semibold hover:shadow-lg hover:scale-105 transition-all">
                        Blog
                    </Link>
                    <Link href="/about" class="ml-1 px-3 py-1.5 rounded-lg text-sm font-medium text-gray-500 hover:text-violet-700 hover:bg-violet-50 transition-all">
                        About
                    </Link>
                    <Link href="/contact" class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-500 hover:text-violet-700 hover:bg-violet-50 transition-all">
                        Contact
                    </Link>
                </div>

                <!-- Mobile hamburger -->
                <button
                    class="md:hidden p-2 rounded-lg text-gray-600 hover:bg-violet-50"
                    @click="mobileOpen = !mobileOpen"
                    aria-label="Toggle menu"
                >
                    <svg v-if="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div v-if="mobileOpen" class="md:hidden pb-4 grid grid-cols-2 gap-2">
                <Link
                    v-for="tool in tools" :key="tool.href"
                    :href="tool.href"
                    class="flex items-center gap-2 px-3 py-2 rounded-lg bg-gradient-to-r text-white text-sm font-medium"
                    :class="`bg-gradient-to-r ${tool.color}`"
                    @click="mobileOpen = false"
                >
                    <span>{{ tool.icon }}</span>
                    <span>{{ tool.name }}</span>
                </Link>
                <Link href="/blog" class="col-span-2 text-center px-3 py-2 rounded-lg bg-gradient-to-r from-violet-600 to-pink-600 text-white text-sm font-semibold" @click="mobileOpen = false">
                    📰 Blog
                </Link>
                <Link href="/about" class="text-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition-colors" @click="mobileOpen = false">
                    About
                </Link>
                <Link href="/contact" class="text-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition-colors" @click="mobileOpen = false">
                    Contact
                </Link>
            </div>
        </div>
    </nav>
</template>
