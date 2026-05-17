<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    content: { type: String, default: '' },
    seo:     { type: Object, default: () => ({}) },
});
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-3xl mx-auto">

            <!-- Page header -->
            <div class="mb-8">
                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-violet-500 to-purple-600 text-white text-xs font-bold rounded-full uppercase tracking-wider mb-4">About</span>
                <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight">About KhanTools</h1>
            </div>

            <!-- Content -->
            <div
                class="prose prose-lg max-w-none prose-headings:font-black prose-a:text-violet-600 prose-strong:text-gray-900 prose-li:text-gray-700"
                v-html="content"
            ></div>
        </div>
    </AppLayout>
</template>
