<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({ post: Object, seo: Object });

function formatDate(dt) {
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

// JSON-LD structured data for the blog post
const jsonLd = JSON.stringify({
    '@context': 'https://schema.org',
    '@type':    'Article',
    headline:   props.post.meta_title || props.post.title,
    description: props.post.meta_description || props.post.excerpt,
    datePublished: props.post.published_at,
    dateModified:  props.post.updated_at,
    image:          props.post.og_image,
    publisher: {
        '@type': 'Organization',
        name:    'MA Tools',
        url:     'https://ma-tools.com',
    },
});
</script>

<template>
    <AppLayout :seo="seo">
        <Head>
            <!-- JSON-LD structured data -->
            <component :is="'script'" type="application/ld+json">{{ jsonLd }}</component>
        </Head>

        <article class="max-w-3xl mx-auto">

            <!-- Hero image -->
            <div v-if="post.og_image" class="rounded-2xl overflow-hidden mb-8 aspect-video">
                <img :src="post.og_image" :alt="post.title" class="w-full h-full object-cover">
            </div>

            <!-- Meta -->
            <div class="flex items-center gap-3 mb-5">
                <span class="px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-xs font-bold rounded-full uppercase tracking-wider">Blog</span>
                <span class="text-gray-400 text-sm">{{ formatDate(post.published_at) }}</span>
            </div>

            <!-- Title -->
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 leading-tight mb-6">{{ post.title }}</h1>

            <!-- Excerpt -->
            <p v-if="post.excerpt" class="text-xl text-gray-500 leading-relaxed mb-8 font-medium border-l-4 border-orange-400 pl-4">
                {{ post.excerpt }}
            </p>

            <!-- Content -->
            <div class="prose prose-lg max-w-none prose-headings:font-black prose-a:text-violet-600 prose-strong:text-gray-900" v-html="post.content"></div>

            <!-- Back link -->
            <div class="mt-12 pt-8 border-t border-gray-100">
                <a href="/blog" class="inline-flex items-center gap-2 text-violet-600 font-semibold hover:text-violet-800 transition-colors">
                    ← Back to Blog
                </a>
            </div>
        </article>
    </AppLayout>
</template>
