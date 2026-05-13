<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    posts: Object,
    seo:   Object,
});

function formatDate(dt) {
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}
</script>

<template>
    <AppLayout :seo="seo">
        <div class="max-w-5xl mx-auto">

            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-3xl mb-4 shadow-lg">📰</div>
                <h1 class="text-4xl font-black text-gray-900 mb-2">Blog</h1>
                <p class="text-gray-500">Tips, tutorials and guides for getting the most out of free web tools.</p>
            </div>

            <!-- Posts grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <Link
                    v-for="post in posts.data" :key="post.id"
                    :href="`/blog/${post.slug}`"
                    class="group bg-white rounded-2xl border border-orange-100 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300"
                >
                    <div v-if="post.og_image" class="aspect-video bg-gradient-to-br from-amber-100 to-orange-100 overflow-hidden">
                        <img :src="post.og_image" :alt="post.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div v-else class="aspect-video bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center text-4xl">📝</div>

                    <div class="p-5">
                        <p class="text-xs font-semibold text-orange-600 uppercase tracking-wider mb-2">{{ formatDate(post.published_at) }}</p>
                        <h2 class="font-black text-gray-900 text-lg mb-2 group-hover:text-orange-600 transition-colors line-clamp-2">{{ post.title }}</h2>
                        <p v-if="post.excerpt" class="text-gray-500 text-sm line-clamp-3">{{ post.excerpt }}</p>
                        <div class="mt-4 flex items-center text-orange-600 text-sm font-semibold gap-1 group-hover:gap-2 transition-all">
                            Read more <span>→</span>
                        </div>
                    </div>
                </Link>
            </div>

            <!-- Empty state -->
            <div v-if="!posts.data.length" class="text-center py-20 text-gray-400">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-lg font-semibold">No posts yet</p>
            </div>

            <!-- Pagination -->
            <div v-if="posts.last_page > 1" class="flex justify-center gap-2 mt-10">
                <Link
                    v-for="link in posts.links" :key="link.label"
                    :href="link.url || '#'"
                    class="px-4 py-2 rounded-xl text-sm font-semibold border transition-all"
                    :class="link.active
                        ? 'bg-gradient-to-r from-orange-500 to-amber-600 text-white border-transparent'
                        : 'bg-white text-gray-600 border-gray-200 hover:border-orange-400'"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
