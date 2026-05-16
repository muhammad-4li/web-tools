<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const ads         = computed(() => usePage().props.ads ?? {});
const publisherId = computed(() => ads.value.publisher_id ?? '');
const slotId      = computed(() => ads.value.slot_topbar ?? '');
</script>

<template>
    <div class="w-full bg-gradient-to-r from-violet-600 via-purple-600 to-pink-600 flex items-center justify-center py-1.5 px-4 min-h-[56px]">
        <ins class="adsbygoogle block w-full"
             style="display:block"
             :data-ad-client="publisherId"
             :data-ad-slot="slotId"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <span class="text-white/60 text-xs font-medium select-none pointer-events-none absolute">
            Advertisement
        </span>
    </div>
</template>
