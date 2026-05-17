<script setup>
import { computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
const emit        = defineEmits(['close']);
const ads         = computed(() => usePage().props.ads ?? {});
const publisherId = computed(() => ads.value.publisher_id ?? '');
const slotId      = computed(() => ads.value.slot_popup ?? '');
onMounted(() => {
    try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch {}
});
</script>

<template>
    <!-- Backdrop -->
    <div
        class="fixed inset-0 z-50 flex items-center justify-center p-4"
        style="background: rgba(0,0,0,0.65);"
        @click.self="emit('close')"
    >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden relative animate-bounce-in">

            <!-- Close button -->
            <button
                class="absolute top-3 right-3 z-10 w-8 h-8 rounded-full bg-gray-100 hover:bg-red-100 flex items-center justify-center text-gray-500 hover:text-red-600 transition-all font-bold text-lg"
                @click="emit('close')"
                aria-label="Close ad"
            >×</button>

            <!-- Header -->
            <div class="bg-gradient-to-r from-violet-600 to-pink-600 px-6 py-3">
                <span class="text-white text-xs font-semibold uppercase tracking-wider opacity-75">Sponsored</span>
            </div>

            <!-- Ad content -->
            <div class="p-6 flex flex-col items-center min-h-[250px]">
                <ins class="adsbygoogle block w-full"
                     style="display:block"
                     :data-ad-client="publisherId"
                     :data-ad-slot="slotId"
                     data-ad-format="rectangle"
                     data-full-width-responsive="true"></ins>
            </div>

            <!-- Continue button -->
            <div class="px-6 pb-5 text-center">
                <button
                    class="px-6 py-2.5 bg-gradient-to-r from-violet-600 to-pink-600 text-white rounded-full font-semibold hover:shadow-lg hover:scale-105 transition-all text-sm"
                    @click="emit('close')"
                >
                    Continue to Download ➜
                </button>
            </div>
        </div>
    </div>
</template>
