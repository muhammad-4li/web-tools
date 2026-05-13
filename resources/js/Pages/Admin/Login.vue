<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const form     = ref({ email: '', password: '', remember: false });
const errors   = ref({});
const loading  = ref(false);

function login() {
    loading.value = true;
    errors.value  = {};
    router.post('/admin/login', form.value, {
        onError:  (e) => { errors.value = e; },
        onFinish: ()  => { loading.value = false; },
    });
}
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-violet-900 via-purple-900 to-pink-900 flex items-center justify-center p-4">
        <div class="w-full max-w-md">

            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-white/10 backdrop-blur text-white font-black text-2xl mb-4 border border-white/20">MA</div>
                <h1 class="text-3xl font-black text-white">Admin Login</h1>
                <p class="text-violet-300 mt-1">ma-tools.com</p>
            </div>

            <!-- Form -->
            <div class="bg-white/10 backdrop-blur-xl rounded-2xl p-8 border border-white/20 shadow-2xl">
                <form @submit.prevent="login" class="space-y-5">

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            required
                            autocomplete="email"
                            class="w-full bg-white/10 border rounded-xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-violet-400 transition-colors"
                            :class="errors.email ? 'border-red-400' : 'border-white/20'"
                            placeholder="admin@ma-tools.com"
                        >
                        <p v-if="errors.email" class="mt-1 text-red-300 text-xs">{{ errors.email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-white mb-1">Password</label>
                        <input
                            v-model="form.password"
                            type="password"
                            required
                            autocomplete="current-password"
                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder-white/50 focus:outline-none focus:border-violet-400 transition-colors"
                            placeholder="••••••••"
                        >
                    </div>

                    <button
                        type="submit"
                        :disabled="loading"
                        class="w-full py-3 bg-gradient-to-r from-violet-500 to-pink-500 text-white font-bold rounded-xl hover:shadow-xl hover:scale-[1.02] transition-all disabled:opacity-60 disabled:cursor-not-allowed"
                    >
                        <span v-if="loading" class="animate-pulse">Signing in…</span>
                        <span v-else>Sign In →</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>
