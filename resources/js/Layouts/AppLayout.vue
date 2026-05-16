<script setup>
import { ref, inject, computed, onMounted, onBeforeUnmount, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import Navigation from '@/Components/Navigation.vue';
import AdsTopBar from '@/Components/Ads/TopBar.vue';
import AdsSecondBar from '@/Components/Ads/SecondBar.vue';
import AdsLeftSidebar from '@/Components/Ads/LeftSidebar.vue';
import AdsRightSidebar from '@/Components/Ads/RightSidebar.vue';
import AdsBottom from '@/Components/Ads/Bottom.vue';
import AdsPopup from '@/Components/Ads/Popup.vue';

const props = defineProps({
    seo: { type: Object, default: () => ({}) },
});

const popupState    = inject('adPopupState', { showPopup: ref(false), pendingAction: ref(null) });
const showPopup     = popupState.showPopup;
const pendingAction = popupState.pendingAction;

// Ads enable/disable — read from Inertia shared prop
const adsEnabled = computed(() => page.props.ads?.enabled ?? false);

// When ads are disabled, bypass the popup and run the callback immediately
watch(showPopup, (val) => {
    if (val && !adsEnabled.value) {
        showPopup.value     = false;
        pendingAction.value?.();
        pendingAction.value = null;
    }
});

function onPopupClose() {
    showPopup.value     = false;
    pendingAction.value?.();
    pendingAction.value = null;
}

const siteBase   = 'https://ma-tools.com';
const siteName   = 'MA Tools';

const pageTitle       = computed(() => props.seo?.title       || 'MA Tools — Free Online Web Tools');
const pageDescription = computed(() => props.seo?.description || 'Free online tools for images and PDFs. 100% browser-based, nothing uploaded.');
const pageKeywords    = computed(() => props.seo?.keywords    || '');
const ogImage         = computed(() => props.seo?.og_image    || '/images/og-default.png');
const robotsMeta      = computed(() => props.seo?.robots      || 'index, follow');

// Canonical: explicit override > current full URL
const page       = usePage();
const canonical  = computed(() => {
    if (props.seo?.canonical_url) return props.seo.canonical_url;
    return siteBase + page.url;
});

// JSON-LD — WebSite + Organisation
const jsonLd = computed(() => JSON.stringify({
    '@context': 'https://schema.org',
    '@graph': [
        {
            '@type':            'WebSite',
            '@id':              siteBase + '/#website',
            url:                siteBase + '/',
            name:               siteName,
            description:        'Free online browser-based tools for images and PDFs.',
            potentialAction:    {
                '@type':        'SearchAction',
                target:         { '@type': 'EntryPoint', urlTemplate: siteBase + '/blog?q={search_term_string}' },
                'query-input':  'required name=search_term_string',
            },
        },
        {
            '@type':    'Organization',
            '@id':      siteBase + '/#organization',
            name:       siteName,
            url:        siteBase + '/',
            logo:       {
                '@type': 'ImageObject',
                url:     siteBase + '/favicon.svg',
            },
        },
        {
            '@type':        'WebPage',
            '@id':          canonical.value + '#webpage',
            url:            canonical.value,
            name:           pageTitle.value,
            description:    pageDescription.value,
            isPartOf:       { '@id': siteBase + '/#website' },
            inLanguage:     'en-US',
        },
    ],
}));

let jsonLdScript = null;
onMounted(() => {
    jsonLdScript = document.createElement('script');
    jsonLdScript.type = 'application/ld+json';
    jsonLdScript.textContent = jsonLd.value;
    document.head.appendChild(jsonLdScript);
});
watch(jsonLd, (val) => {
    if (jsonLdScript) jsonLdScript.textContent = val;
});
onBeforeUnmount(() => {
    jsonLdScript?.remove();
    jsonLdScript = null;
});
</script>

<template>
    <Head>
        <title>{{ pageTitle }}</title>

        <!-- Core -->
        <meta name="description"         :content="pageDescription">
        <meta name="keywords"            :content="pageKeywords">
        <meta name="robots"              :content="robotsMeta">
        <link rel="canonical"            :href="canonical">

        <!-- Open Graph -->
        <meta property="og:type"         content="website">
        <meta property="og:site_name"    :content="siteName">
        <meta property="og:locale"       content="en_US">
        <meta property="og:url"          :content="canonical">
        <meta property="og:title"        :content="pageTitle">
        <meta property="og:description"  :content="pageDescription">
        <meta property="og:image"        :content="siteBase + ogImage">
        <meta property="og:image:width"  content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:image:alt"    :content="pageTitle">

        <!-- Twitter / X -->
        <meta name="twitter:card"        content="summary_large_image">
        <meta name="twitter:site"        content="@matools">
        <meta name="twitter:title"       :content="pageTitle">
        <meta name="twitter:description" :content="pageDescription">
        <meta name="twitter:image"       :content="siteBase + ogImage">
        <meta name="twitter:image:alt"   :content="pageTitle">

    </Head>

    <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-violet-50/30 to-pink-50/30">

        <!-- TOP AD BAR -->
        <AdsTopBar v-if="adsEnabled" />

        <!-- NAVIGATION -->
        <Navigation />

        <!-- SECOND AD BAR -->
        <AdsSecondBar v-if="adsEnabled" />

        <!-- MAIN 3-COLUMN LAYOUT -->
        <main class="flex-1 w-full max-w-screen-2xl mx-auto px-2 lg:px-4 py-6 flex gap-4">

            <!-- LEFT SIDEBAR AD -->
            <aside v-if="adsEnabled" class="hidden xl:flex flex-col items-center gap-4 w-40 shrink-0">
                <AdsLeftSidebar />
            </aside>

            <!-- TOOL CONTENT CENTER -->
            <div class="flex-1 min-w-0">
                <slot />
            </div>

            <!-- RIGHT SIDEBAR AD -->
            <aside v-if="adsEnabled" class="hidden xl:flex flex-col items-center gap-4 w-40 shrink-0">
                <AdsRightSidebar />
            </aside>

        </main>

        <!-- BOTTOM AD BAR -->
        <AdsBottom v-if="adsEnabled" />

        <!-- FOOTER -->
        <footer class="bg-gradient-to-r from-violet-900 via-purple-900 to-pink-900 text-white py-10 mt-4">
            <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="text-2xl font-black tracking-tight mb-3">
                        <span class="text-yellow-300">MA</span>
                        <span class="text-white">Tools</span>
                    </div>
                    <p class="text-violet-200 text-sm leading-relaxed">Free online tools that run entirely in your browser. No uploads. 100% private.</p>
                </div>
                <div>
                    <h4 class="font-bold text-yellow-300 mb-3 uppercase tracking-wider text-xs">Tools</h4>
                    <ul class="space-y-1 text-sm text-violet-200">
                        <li><a href="/tools/image-crop"       class="hover:text-white transition-colors">Image Crop</a></li>
                        <li><a href="/tools/image-bg-remover" class="hover:text-white transition-colors">BG Remover</a></li>
                        <li><a href="/tools/pdf-merge"        class="hover:text-white transition-colors">PDF Merge</a></li>
                        <li><a href="/tools/pdf-sign"         class="hover:text-white transition-colors">PDF Sign</a></li>
                        <li><a href="/tools/pdf-text"         class="hover:text-white transition-colors">PDF Text</a></li>
                        <li><a href="/tools/text-editor"      class="hover:text-white transition-colors">Text Editor</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-yellow-300 mb-3 uppercase tracking-wider text-xs">Info</h4>
                    <ul class="space-y-1 text-sm text-violet-200">
                        <li><a href="/blog" class="hover:text-white transition-colors">Blog</a></li>
                        <li><a href="/sitemap.xml" class="hover:text-white transition-colors">Sitemap</a></li>
                    </ul>
                    <p class="text-violet-400 text-xs mt-4">&copy; {{ new Date().getFullYear() }} ma-tools.com</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- POPUP AD (shown after tool action) -->
    <AdsPopup v-if="showPopup && adsEnabled" @close="onPopupClose" />
</template>
