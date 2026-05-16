<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const ads         = computed(() => usePage().props.ads ?? {});
const publisherId = computed(() => ads.value.publisher_id ?? '');
const slotId      = computed(() => ads.value.slot_secondbar ?? '');
</script>

<template>
    <div class="w-full bg-gradient-to-r from-amber-50 to-orange-50 border-b border-orange-100 flex items-center justify-center py-2 min-h-[60px]">
        <ins class="adsbygoogle block w-full"
             style="display:block"
             :data-ad-client="publisherId"
             :data-ad-slot="slotId"
             data-ad-format="horizontal"
             data-full-width-responsive="true"></ins>
    </div>
</template>
