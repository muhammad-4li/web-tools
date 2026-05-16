<script setup>
const publisherId = import.meta.env.VITE_ADSENSE_PUBLISHER_ID;
const slotId      = import.meta.env.VITE_ADSENSE_SLOT_BOTTOM;
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
