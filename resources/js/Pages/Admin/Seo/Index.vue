<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    pages: { type: Array, default: () => [] },
});

const robotsBadge = (robots) => {
    if (!robots || robots.includes('noindex')) return { label: 'noindex', cls: 'bg-red-100 text-red-700' };
    return { label: 'indexed', cls: 'bg-green-100 text-green-700' };
};
</script>

<template>
    <AdminLayout title="Page SEO">
        <div class="max-w-4xl">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-black text-gray-900">Page SEO</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Override title, description, keywords and directives per page.</p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Page</th>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Current Title</th>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Robots</th>
                            <th class="text-left px-6 py-3 font-semibold text-gray-600">Override</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <tr v-for="page in pages" :key="page.key" class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-gray-900">{{ page.label }}</div>
                                <div class="text-xs text-gray-400 font-mono mt-0.5">{{ page.key }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 max-w-xs">
                                <span class="line-clamp-1">{{ page.title }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium"
                                    :class="robotsBadge(page.robots).cls"
                                >{{ robotsBadge(page.robots).label }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="page.has_override" class="inline-flex items-center gap-1 text-xs font-medium text-violet-700 bg-violet-50 px-2 py-0.5 rounded-full">
                                    ✏️ Custom
                                </span>
                                <span v-else class="text-xs text-gray-400">config default</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Link
                                    :href="`/admin/seo/${page.key}/edit`"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-violet-600 text-white text-xs font-semibold hover:bg-violet-700 transition-colors"
                                >Edit SEO</Link>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <p class="text-xs text-gray-400 mt-4">
                Leaving a field blank keeps the config/seo.php default value active.
            </p>
        </div>
    </AdminLayout>
</template>
