<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({ posts: Object });

const deleteModal = ref({ show: false, id: null, title: '' });

function formatDate(dt) {
    if (!dt) return '—';
    return new Date(dt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
}

function confirmDelete(id, title) {
    deleteModal.value = { show: true, id, title };
}

function executeDelete() {
    router.delete(`/admin/blog/${deleteModal.value.id}`, { preserveScroll: true });
    deleteModal.value.show = false;
}

function toggleStatus(id) {
    router.patch(`/admin/blog/${id}/toggle-status`, {}, { preserveScroll: true });
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
                            <button
                                @click="toggleStatus(post.id)"
                                class="px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide transition-all hover:scale-105"
                                :class="post.is_published
                                    ? 'bg-green-100 text-green-700 hover:bg-green-200'
                                    : 'bg-amber-100 text-amber-700 hover:bg-amber-200'"
                                :title="post.is_published ? 'Click to move to draft' : 'Click to publish'"
                            >
                                {{ post.is_published ? 'Published' : 'Draft' }}
                            </button>
                        </td>
                        <td class="px-4 py-4 text-gray-500">{{ formatDate(post.published_at) }}</td>
                        <td class="px-4 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <Link :href="`/admin/blog/${post.id}/edit`"
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 font-medium hover:bg-violet-50 hover:border-violet-300 hover:text-violet-700 transition-all text-xs"
                                >Edit</Link>
                                <button
                                    class="px-3 py-1.5 border border-gray-200 rounded-lg text-gray-400 font-medium hover:bg-red-50 hover:border-red-300 hover:text-red-600 transition-all text-xs"
                                    @click="confirmDelete(post.id, post.title)"
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

        <!-- Delete confirm modal -->
        <Teleport to="body">
            <Transition enter-active-class="transition duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="transition duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="deleteModal.show"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                    @click.self="deleteModal.show = false"
                >
                    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden"
                        @click.stop>
                        <div class="p-6">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-2xl shrink-0">🗑️</div>
                                <div>
                                    <h3 class="font-black text-gray-900 text-lg leading-tight">Delete Post?</h3>
                                    <p class="text-sm text-gray-400 mt-0.5">This cannot be undone.</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-xl px-4 py-3 border border-gray-100 text-sm text-gray-700">
                                &ldquo;<span class="font-semibold">{{ deleteModal.title }}</span>&rdquo;
                            </div>
                        </div>
                        <div class="px-6 pb-6 flex gap-3">
                            <button
                                class="flex-1 px-4 py-2.5 border border-gray-200 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-all"
                                @click="deleteModal.show = false"
                            >Cancel</button>
                            <button
                                class="flex-1 px-4 py-2.5 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 hover:shadow-lg transition-all"
                                @click="executeDelete"
                            >Yes, Delete</button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

    </AdminLayout>
</template>
