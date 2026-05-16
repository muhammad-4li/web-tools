<script setup>
const publisherId = import.meta.env.VITE_ADSENSE_PUBLISHER_ID;
const slotId      = import.meta.env.VITE_ADSENSE_SLOT_TOPBAR;
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
