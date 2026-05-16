<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
const ads         = computed(() => usePage().props.ads ?? {});
const publisherId = computed(() => ads.value.publisher_id ?? '');
const slotId      = computed(() => ads.value.slot_right ?? '');
</script>

<template>
    <div class="sticky top-20 flex flex-col gap-4">
        <ins class="adsbygoogle block"
             style="display:block;width:160px;height:600px"
             :data-ad-client="publisherId"
             :data-ad-slot="slotId"
             data-ad-format="vertical"></ins>
    </div>
</template>
