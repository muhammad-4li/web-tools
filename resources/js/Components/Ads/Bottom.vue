<script setup>
import { computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
const ads         = computed(() => usePage().props.ads ?? {});
const publisherId = computed(() => ads.value.publisher_id ?? '');
const slotId      = computed(() => ads.value.slot_bottom ?? '');
onMounted(() => {
    try { (window.adsbygoogle = window.adsbygoogle || []).push({}); } catch {}
});
</script>

<template>
    <div class="w-full bg-gradient-to-r from-teal-50 to-cyan-50 border-t border-teal-100 flex items-center justify-center py-2 min-h-[60px]">
        <ins class="adsbygoogle block w-full"
             style="display:block"
             :data-ad-client="publisherId"
             :data-ad-slot="slotId"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
    </div>
</template>
