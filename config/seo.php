<?php

return [
    'site_name'  => 'KhanTools',
    'base_url'   => env('APP_URL', 'https://khantools.com'),
    'default_og' => '/images/og-default.png',

    'pages' => [
        'home' => [
            'title'       => 'KhanTools — Free Online Web Tools',
            'description' => 'Free online tools for images and PDFs. Crop images, remove backgrounds, merge PDFs, add signatures, text editor and more — all processed in your browser.',
            'keywords'    => 'free online tools, image crop, remove background, pdf merge, pdf sign, text editor, web tools',
            'og_image'    => '/images/og-home.png',
        ],
        'image-crop' => [
            'title'       => 'Image Crop Tool — KhanTools Free Online Image Cropper',
            'description' => 'Crop images online for free. Supports JPEG, PNG, WebP. Choose aspect ratio or freeform. Download instantly. No uploads, 100% private.',
            'keywords'    => 'image crop online, free image cropper, crop photo online, crop picture, image editor',
            'og_image'    => '/images/og-image-crop.png',
        ],
        'image-bg-remover' => [
            'title'       => 'Remove Image Background — KhanTools Free AI Background Remover',
            'description' => 'Remove image backgrounds instantly with AI. Works 100% in your browser — no uploads, completely private. Download transparent PNG.',
            'keywords'    => 'remove background, background remover, transparent background, remove image background free, AI background remover',
            'og_image'    => '/images/og-bg-remover.png',
        ],
        'pdf-merge' => [
            'title'       => 'Merge PDF Files Online — KhanTools Free PDF Merger',
            'description' => 'Merge multiple PDF files into one. Drag, drop and reorder pages. Processed entirely in your browser — nothing uploaded to our servers.',
            'keywords'    => 'merge pdf, combine pdf, pdf merger online, join pdf files, merge pdf free',
            'og_image'    => '/images/og-pdf-merge.png',
        ],
        'pdf-sign' => [
            'title'       => 'Add Signature to PDF — KhanTools Free PDF Signature Tool',
            'description' => 'Sign PDF documents online for free. Draw your signature and place it anywhere on the document. Download signed PDF instantly.',
            'keywords'    => 'sign pdf online, pdf signature, add signature to pdf, electronic signature, digital signature pdf',
            'og_image'    => '/images/og-pdf-sign.png',
        ],
        'pdf-text' => [
            'title'       => 'Add Text to PDF Online — KhanTools Free PDF Text Editor',
            'description' => 'Add text to any PDF online. Choose font, size, color and position. No registration required. Download edited PDF immediately.',
            'keywords'    => 'add text to pdf, pdf text editor, write on pdf, edit pdf text online, pdf annotation',
            'og_image'    => '/images/og-pdf-text.png',
        ],
        'text-editor' => [
            'title'       => 'Online Text Editor — KhanTools Free Rich Text Editor',
            'description' => 'Feature-rich online text editor. Format text, add headings, lists, links and images. Export as HTML, plain text. Completely free.',
            'keywords'    => 'online text editor, rich text editor, free text editor, html editor, word processor online',
            'og_image'    => '/images/og-text-editor.png',
        ],
        'about' => [
            'title'       => 'About KhanTools — Free Browser-Based Online Tools',
            'description' => 'Learn about KhanTools, a free suite of browser-based tools for images and PDFs. No uploads, no registration, 100% private.',
            'keywords'    => 'about khantools, free online tools, browser tools, image tools, pdf tools',
        ],
        'contact' => [
            'title'       => 'Contact KhanTools — Get in Touch',
            'description' => 'Have a question, found a bug, or want to suggest a new tool? Reach out to the KhanTools team.',
            'keywords'    => 'contact khantools, support, feedback, report bug, suggest tool',
        ],
        'privacy' => [
            'title'       => 'Privacy Policy — KhanTools',
            'description' => 'Read the KhanTools privacy policy. Learn what data we collect, how we use cookies, and your rights under GDPR.',
            'keywords'    => 'privacy policy, cookies, gdpr, data collection, khantools',
        ],
    ],
];
