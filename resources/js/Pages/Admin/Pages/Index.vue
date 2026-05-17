<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({ pages: { type: Array, default: () => [] } });
</script>

<template>
    <AdminLayout title="Static Pages">
        <div class="max-w-2xl">

            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Static Pages</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Edit the content and SEO for About, Contact and Privacy pages.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Page</th>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Title</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="page in pages" :key="page.key" class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ page.label }}</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">/{{ page.key }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs">
                                <span class="line-clamp-1">{{ page.title }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link
                                    :href="`/admin/pages/${page.key}/edit`"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-semibold hover:bg-violet-700 transition-colors"
                                >Edit Content</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
