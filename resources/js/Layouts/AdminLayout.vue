<script setup>
import { Head, Link, router, usePage } from '@inertiajs/vue3';

const page  = usePage();
const props = defineProps({ title: { type: String, default: 'Admin' } });

function logout() {
    router.post('/admin/logout');
}

const navItems = [
    { label: 'Dashboard',  href: '/admin',          icon: '📊' },
    { label: 'Blog Posts', href: '/admin/blog',     icon: '📝' },
    { label: 'Page SEO',   href: '/admin/seo',      icon: '🔍' },
    { label: 'Profile',    href: '/admin/profile',  icon: '👤' },
];
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex">
        <Head><title>{{ title }} — KhanTools Admin</title></Head>

        <!-- Sidebar -->
        <aside class="w-60 bg-gradient-to-b from-gray-900 via-violet-950 to-gray-900 text-white flex flex-col shadow-2xl">
            <div class="px-6 py-5 border-b border-white/10">
                <Link href="/admin" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center text-xs font-black">KT</div>
                    <span class="font-black text-lg"><span class="text-violet-400">Khan</span>Tools</span>
                </Link>
                <p class="text-xs text-white/40 mt-1">Admin Panel</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                <Link
                    v-for="item in navItems" :key="item.href"
                    :href="item.href"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all"
                    :class="($page.url === item.href || (item.href !== '/admin' && $page.url.startsWith(item.href)))
                        ? 'bg-violet-600 text-white shadow-lg'
                        : 'text-white/70 hover:bg-white/10 hover:text-white'"
                >
                    <span>{{ item.icon }}</span>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-3 py-2 mb-2">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-violet-400 to-pink-400 flex items-center justify-center text-xs font-black">
                        {{ page.props.auth?.user?.name?.[0] ?? 'A' }}
                    </div>
                    <span class="text-sm text-white/70 truncate">{{ page.props.auth?.user?.name ?? 'Admin' }}</span>
                </div>
                <button
                    class="w-full flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-white/60 hover:bg-red-500/20 hover:text-red-300 transition-all"
                    @click="logout"
                >🚪 Logout</button>
                <a href="/" class="flex items-center gap-2 px-3 py-2 rounded-xl text-sm text-white/60 hover:bg-white/10 hover:text-white transition-all mt-1">
                    🌐 View Site
                </a>
            </div>
        </aside>

        <!-- Main content -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top bar -->
            <div class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between shadow-sm">
                <h1 class="text-xl font-black text-gray-900">{{ title }}</h1>
                <div v-if="$page.props.flash?.success" class="px-4 py-1.5 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                    ✅ {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash?.error" class="px-4 py-1.5 bg-red-100 text-red-700 rounded-full text-sm font-medium">
                    ⚠️ {{ $page.props.flash.error }}
                </div>
            </div>

            <div class="flex-1 p-8">
                <slot />
            </div>
        </main>
    </div>
</template>
