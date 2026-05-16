<script setup>
const publisherId = import.meta.env.VITE_ADSENSE_PUBLISHER_ID;
const slotId      = import.meta.env.VITE_ADSENSE_SLOT_LEFT;
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
