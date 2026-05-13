<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({ posts: Object });

function formatDate(dt) {
    if (!dt) return '—';
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function deletePost(id, title) {
    if (!confirm(`Delete "${title}"? This cannot be undone.`)) return;
    router.delete(`/admin/blog/${id}`);
}
</script>

<template>
    <AdminLayout title="Blog Posts">

        <!-- Actions bar -->
        <div class="flex items-center justify-between mb-6">
            <p class="text-gray-500 text-sm">{{ posts.total }} post{{ posts.total !== 1 ? 's' : '' }}</p>
            <Link href="/admin/blog/create" class="px-5 py-2.5 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:scale-105 transition-all">
                + New Post
            </Link>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-6 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-left px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Published</th>
                        <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-for="post in posts.data" :key="post.id" class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900 line-clamp-1">{{ post.title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ post.slug }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <span
                                class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide"
                                :class="post.is_published
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-amber-100 text-amber-700'"
                            >
                                {{ post.is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-gray-500">{{ formatDate(post.published_at) }}</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link :href="`/admin/blog/${post.id}/edit`"
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 font-medium hover:bg-violet-50 hover:border-violet-300 hover:text-violet-700 transition-all text-xs"
                                >Edit</Link>
                                <button
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-400 font-medium hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all text-xs"
                                    @click="deletePost(post.id, post.title)"
                                >Delete</button>
                                <a v-if="post.is_published" :href="`/blog/${post.slug}`" target="_blank"
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-400 font-medium hover:bg-gray-50 transition-all text-xs"
                                >View ↗</a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div v-if="!posts.data.length" class="text-center py-16 text-gray-400">
                <div class="text-4xl mb-3">📭</div>
                <p class="font-semibold">No posts yet</p>
                <Link href="/admin/blog/create" class="mt-3 inline-block text-violet-600 font-bold hover:underline">Create your first post →</Link>
            </div>
        </div>

        <!-- Pagination -->
        <div v-if="posts.last_page > 1" class="flex justify-center gap-2 mt-6">
            <Link
                v-for="link in posts.links" :key="link.label"
                :href="link.url || '#'"
                class="px-3 py-1.5 rounded-lg text-sm font-semibold border transition-all"
                :class="link.active ? 'bg-violet-600 text-white border-transparent' : 'bg-white text-gray-600 border-gray-200 hover:border-violet-300'"
                v-html="link.label"
            />
        </div>
    </AdminLayout>
</template>
