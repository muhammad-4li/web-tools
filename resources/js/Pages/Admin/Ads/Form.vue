<script setup>
import { ref } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    settings: { type: Object, required: true },
});

const form   = ref({ ...props.settings });
const errors = ref({});
const saving = ref(false);

const slots = [
    { key: 'slot_topbar',    label: 'Top Bar',       description: 'Horizontal banner above navigation' },
    { key: 'slot_secondbar', label: 'Second Bar',     description: 'Horizontal banner below navigation' },
    { key: 'slot_left',      label: 'Left Sidebar',   description: 'Vertical 160×600 — xl screens only' },
    { key: 'slot_right',     label: 'Right Sidebar',  description: 'Vertical 160×600 — xl screens only' },
    { key: 'slot_bottom',    label: 'Bottom Bar',     description: 'Horizontal banner at footer' },
    { key: 'slot_popup',     label: 'Popup',          description: 'Modal popup triggered after tool action' },
];

function submit() {
    saving.value  = true;
    errors.value  = {};
    router.put('/admin/ads', form.value, {
        onError:  (e) => { errors.value = e; saving.value = false; },
        onFinish: ()  => { saving.value = false; },
    });
}
</script>

<template>
    <AdminLayout title="Ads Settings">
        <div class="max-w-2xl">

            <form @submit.prevent="submit" class="space-y-6">

                <!-- Enable / Disable toggle -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-black text-gray-800">Enable Google AdSense</p>
                            <p class="text-sm text-gray-500 mt-0.5">When off, all ad slots are hidden and the download popup is bypassed.</p>
                        </div>
                        <button
                            type="button"
                            class="relative inline-flex h-7 w-13 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2"
                            :class="form.enabled ? 'bg-violet-600' : 'bg-gray-200'"
                            @click="form.enabled = !form.enabled"
                        >
                            <span
                                class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                :class="form.enabled ? 'translate-x-6' : 'translate-x-0'"
                            ></span>
                        </button>
                    </div>
                    <p v-if="form.enabled" class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-green-700 bg-green-50 px-3 py-1 rounded-full">
                        ✅ Ads are enabled — ad slots will render on all public pages
                    </p>
                    <p v-else class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        ⏸ Ads are disabled — no ad slots will render
                    </p>
                </div>

                <!-- Publisher ID -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="font-black text-gray-800">Publisher ID</h2>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            AdSense Publisher ID
                            <span class="text-xs font-normal text-gray-400 ml-1">(data-ad-client)</span>
                        </label>
                        <input
                            v-model="form.publisher_id"
                            type="text"
                            placeholder="ca-pub-XXXXXXXXXXXXXXXXXX"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-violet-400"
                        >
                        <p v-if="errors.publisher_id" class="text-red-500 text-xs mt-1">{{ errors.publisher_id }}</p>
                        <p class="text-xs text-gray-400 mt-1">Found in your AdSense dashboard — starts with <code class="bg-gray-100 px-1 rounded">ca-pub-</code></p>
                    </div>
                </div>

                <!-- Slot IDs -->
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                    <h2 class="font-black text-gray-800">Ad Slot IDs</h2>
                    <p class="text-sm text-gray-500 -mt-2">Each slot corresponds to a separate ad unit in your AdSense account.</p>

                    <div v-for="slot in slots" :key="slot.key">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ slot.label }}
                            <span class="text-xs font-normal text-gray-400 ml-1">— {{ slot.description }}</span>
                        </label>
                        <input
                            v-model="form[slot.key]"
                            type="text"
                            placeholder="e.g. 1234567890"
                            class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-violet-400"
                        >
                        <p v-if="errors[slot.key]" class="text-red-500 text-xs mt-1">{{ errors[slot.key] }}</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        :disabled="saving"
                        class="px-7 py-2.5 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-xl font-semibold text-sm hover:shadow-lg hover:scale-105 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="saving" class="animate-pulse">Saving…</span>
                        <span v-else>💾 Save Settings</span>
                    </button>
                </div>

            </form>
        </div>
    </AdminLayout>
</template>
