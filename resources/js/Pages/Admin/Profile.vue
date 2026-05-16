<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({ user: Object });

const profileForm = ref({
    name:  props.user.name,
    email: props.user.email,
});

const passwordForm = ref({
    current_password:      '',
    password:              '',
    password_confirmation: '',
});

const profileErrors  = ref({});
const passwordErrors = ref({});
const loadingProfile = ref(false);
const loadingPwd     = ref(false);

function updateProfile() {
    loadingProfile.value = true;
    profileErrors.value  = {};
    router.put('/admin/profile', profileForm.value, {
        onError:  (e) => { profileErrors.value = e; },
        onFinish: ()  => { loadingProfile.value = false; },
    });
}

function updatePassword() {
    loadingPwd.value     = true;
    passwordErrors.value = {};
    router.put('/admin/profile/password', passwordForm.value, {
        onSuccess: () => { passwordForm.value = { current_password: '', password: '', password_confirmation: '' }; },
        onError:   (e) => { passwordErrors.value = e; },
        onFinish:  ()  => { loadingPwd.value = false; },
    });
}
</script>

<template>
    <AdminLayout title="My Profile">
        <div class="max-w-2xl space-y-6">

            <!-- Profile info card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center text-2xl font-black text-white shadow-lg">
                        {{ user.name?.[0]?.toUpperCase() ?? 'A' }}
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-900">{{ user.name }}</h2>
                        <p class="text-sm text-gray-400">{{ user.email }}</p>
                    </div>
                </div>

                <form @submit.prevent="updateProfile" class="space-y-4">
                    <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b border-gray-100 pb-2">Profile Details</h3>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Name</label>
                        <input v-model="profileForm.name" type="text" required
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors"
                            :class="profileErrors.name ? 'border-red-400' : 'border-gray-200'">
                        <p v-if="profileErrors.name" class="mt-1 text-red-500 text-xs">{{ profileErrors.name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input v-model="profileForm.email" type="email" required
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors"
                            :class="profileErrors.email ? 'border-red-400' : 'border-gray-200'">
                        <p v-if="profileErrors.email" class="mt-1 text-red-500 text-xs">{{ profileErrors.email }}</p>
                    </div>

                    <button type="submit" :disabled="loadingProfile"
                        class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:scale-105 transition-all disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100">
                        {{ loadingProfile ? 'Saving…' : 'Save Changes' }}
                    </button>
                </form>
            </div>

            <!-- Change password card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <form @submit.prevent="updatePassword" class="space-y-4">
                    <h3 class="font-bold text-gray-700 text-sm uppercase tracking-wider border-b border-gray-100 pb-2">Change Password</h3>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Current Password</label>
                        <input v-model="passwordForm.current_password" type="password" required autocomplete="current-password"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors"
                            :class="passwordErrors.current_password ? 'border-red-400' : 'border-gray-200'">
                        <p v-if="passwordErrors.current_password" class="mt-1 text-red-500 text-xs">{{ passwordErrors.current_password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">New Password <span class="text-gray-400 font-normal text-xs">(min 8 chars)</span></label>
                        <input v-model="passwordForm.password" type="password" required autocomplete="new-password"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors"
                            :class="passwordErrors.password ? 'border-red-400' : 'border-gray-200'">
                        <p v-if="passwordErrors.password" class="mt-1 text-red-500 text-xs">{{ passwordErrors.password }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Confirm New Password</label>
                        <input v-model="passwordForm.password_confirmation" type="password" required autocomplete="new-password"
                            class="w-full border rounded-xl px-4 py-2.5 text-sm focus:border-violet-400 outline-none transition-colors"
                            :class="passwordErrors.password_confirmation ? 'border-red-400' : 'border-gray-200'">
                        <p v-if="passwordErrors.password_confirmation" class="mt-1 text-red-500 text-xs">{{ passwordErrors.password_confirmation }}</p>
                    </div>

                    <button type="submit" :disabled="loadingPwd"
                        class="px-6 py-2.5 bg-gradient-to-r from-orange-500 to-pink-600 text-white rounded-xl font-bold text-sm hover:shadow-lg hover:scale-105 transition-all disabled:opacity-60 disabled:cursor-not-allowed disabled:scale-100">
                        {{ loadingPwd ? 'Updating…' : 'Change Password' }}
                    </button>
                </form>
            </div>

        </div>
    </AdminLayout>
</template>
