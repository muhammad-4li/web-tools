<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ stats: Object });

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
    <AdminLayout title="Dashboard">

        <!-- Stats cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-sm font-semibold text-gray-500 mb-1">Total Posts</p>
                <p class="text-4xl font-black text-violet-600">{{ stats.total_posts }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-sm font-semibold text-gray-500 mb-1">Published</p>
                <p class="text-4xl font-black text-green-600">{{ stats.published_posts }}</p>
            </div>
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <p class="text-sm font-semibold text-gray-500 mb-1">Drafts</p>
                <p class="text-4xl font-black text-orange-500">{{ stats.draft_posts }}</p>
            </div>
        </div>

        <!-- Quick links -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <h2 class="font-black text-gray-800 mb-4">Quick Actions</h2>
            <div class="flex flex-wrap gap-3">
                <Link href="/admin/blog/create" class="px-4 py-2 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all">
                    + New Blog Post
                </Link>
                <Link href="/admin/blog" class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-all">
                    Manage Posts
                </Link>
                <a href="/" target="_blank" class="px-4 py-2 border border-gray-200 rounded-xl text-gray-700 font-semibold text-sm hover:bg-gray-50 transition-all">
                    🌐 View Site
                </a>
            </div>
        </div>

        <!-- Tools overview -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <h2 class="font-black text-gray-800 mb-4">Live Tools</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a
                    v-for="tool in tools" :key="tool.href"
                    :href="tool.href" target="_blank"
                    class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 hover:border-violet-200 hover:bg-violet-50/50 transition-all group"
                >
                    <div class="w-9 h-9 rounded-lg bg-gradient-to-br flex items-center justify-center text-lg shrink-0" :class="`bg-gradient-to-br ${tool.color}`">
                        {{ tool.icon }}
                    </div>
                    <span class="text-sm font-semibold text-gray-700 group-hover:text-violet-700 transition-colors">{{ tool.name }}</span>
                </a>
            </div>
        </div>
    </AdminLayout>
</template>
