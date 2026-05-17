<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user — change password after first deploy!
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@admin.com')],
            [
                'name'     => 'Admin',
                'password' => bcrypt(env('ADMIN_PASSWORD', '123456')),
                'is_admin' => true,
            ]
        );

        // Static pages — About, Contact, Privacy Policy
        $this->seedStaticPages();
    }

    private function seedStaticPages(): void
    {
        $pages = [
            'about' => [
                'title'            => 'About KhanTools',
                'meta_title'       => 'About KhanTools — Free Browser-Based Online Tools',
                'meta_description' => 'Learn about KhanTools, a free suite of browser-based tools for images and PDFs. No uploads, no registration, 100% private.',
                'meta_keywords'    => 'about khantools, free online tools, browser tools, image tools, pdf tools',
                'robots'           => 'index, follow',
                'content'          => <<<HTML
<h2>What is KhanTools?</h2>
<p>KhanTools is a free collection of browser-based productivity tools for everyday tasks. Whether you need to crop an image, remove a background, merge PDF files, add a signature, annotate text, or just edit a document — we have you covered, and we do it all directly inside your browser.</p>

<p>That means <strong>no files ever leave your device</strong>. Nothing is uploaded to our servers. Everything happens locally, using modern JavaScript APIs and WebAssembly libraries running inside your browser tab.</p>

<h2>Our Tools</h2>
<ul>
  <li><strong>Image Crop</strong> — Crop any photo to a custom size or aspect ratio. Supports JPEG, PNG, and WebP.</li>
  <li><strong>Background Remover</strong> — Remove image backgrounds instantly using AI, entirely in your browser.</li>
  <li><strong>PDF Merge</strong> — Combine multiple PDF files into a single document. Drag, drop, reorder, and download.</li>
  <li><strong>PDF Sign</strong> — Draw your signature and embed it into any PDF. No printing or scanning required.</li>
  <li><strong>PDF Text</strong> — Add custom text overlays to any page of a PDF document.</li>
  <li><strong>Text Editor</strong> — A full-featured rich text editor for formatting, writing, and exporting documents.</li>
</ul>

<h2>Why We Built This</h2>
<p>Most online tools either require you to create an account, upload your files to a third-party server, or pay for basic features. We think that is wrong. Simple tools should be free, fast, and private.</p>

<p>KhanTools was built on the belief that <strong>your files are yours</strong>. We never see them, store them, or transmit them. Our tools are open, ad-supported (to keep the lights on), and always free to use.</p>

<h2>Who Is Behind KhanTools?</h2>
<p>KhanTools is built and maintained by a small independent team of developers who are passionate about building practical, privacy-first web applications. We are constantly adding new tools and improving the ones we have.</p>

<p>If you have a suggestion, found a bug, or just want to say hello — we would love to hear from you. Head over to our <a href="/contact">Contact page</a>.</p>
HTML,
            ],
            'contact' => [
                'title'            => 'Contact KhanTools',
                'meta_title'       => 'Contact KhanTools — Get in Touch',
                'meta_description' => 'Have a question, found a bug, or want to suggest a new tool? Reach out to the KhanTools team.',
                'meta_keywords'    => 'contact khantools, support, feedback, report bug, suggest tool',
                'robots'           => 'index, follow',
                'content'          => <<<HTML
<h2>Get in Touch</h2>
<p>We would love to hear from you. Whether you have a question about one of our tools, spotted a bug, or just want to share feedback — feel free to reach out.</p>

<h2>Email</h2>
<p>The fastest way to reach us is by email:</p>
<p><strong><a href="mailto:hello@khantools.com">hello@khantools.com</a></strong></p>

<p>We typically respond within 1–2 business days.</p>

<h2>What You Can Contact Us About</h2>
<ul>
  <li>Bug reports or unexpected tool behaviour</li>
  <li>Suggestions for new tools or features</li>
  <li>Business inquiries or partnership proposals</li>
  <li>Privacy-related requests (data, cookies, etc.)</li>
  <li>General feedback or just to say hello</li>
</ul>

<h2>Social</h2>
<p>You can also follow our updates on social media. We occasionally post tips, new tool announcements, and blog articles.</p>
<ul>
  <li>Twitter / X: <a href="https://twitter.com/khantools" target="_blank" rel="noopener">@khantools</a></li>
</ul>

<p>For privacy-related requests, please also review our <a href="/privacy">Privacy Policy</a>.</p>
HTML,
            ],
            'privacy' => [
                'title'            => 'Privacy Policy',
                'meta_title'       => 'Privacy Policy — KhanTools',
                'meta_description' => 'Read the KhanTools privacy policy. Learn what data we collect, how we use cookies, and your rights under GDPR.',
                'meta_keywords'    => 'privacy policy, cookies, gdpr, data collection, khantools',
                'robots'           => 'index, follow',
                'content'          => <<<HTML
<p><em>Last updated: May 18, 2026</em></p>

<p>KhanTools operates the website located at <strong>khantools.com</strong>. This Privacy Policy explains what information we collect when you use our website, why we collect it, and how it is used. Please read it carefully.</p>

<h2>1. Information We Collect</h2>
<p>We do <strong>not</strong> collect personal information such as your name, email address, or payment details unless you voluntarily provide them (for example, by contacting us via email).</p>

<p>When you visit our website, our web server automatically records standard access log data, including:</p>
<ul>
  <li>Your IP address (anonymised after 24 hours)</li>
  <li>Browser type and version</li>
  <li>Operating system</li>
  <li>Pages visited and time of visit</li>
  <li>Referring URL</li>
</ul>

<p>This data is used solely for security monitoring and aggregate traffic analysis. It is never sold or shared with third parties for marketing purposes.</p>

<h2>2. Files You Process With Our Tools</h2>
<p>All file processing on KhanTools (image cropping, background removal, PDF operations, etc.) happens <strong>entirely inside your browser</strong>. Your files are never uploaded to our servers, stored, or transmitted anywhere. We have no access to any files you process using our tools.</p>

<h2>3. Cookies</h2>
<p>We use the following types of cookies:</p>

<h3>a) Strictly Necessary Cookies</h3>
<p>These are session cookies required for the website to function correctly. They do not collect personal data and are deleted when you close your browser.</p>

<h3>b) Third-Party Advertising Cookies (Google AdSense)</h3>
<p>We use Google AdSense to display advertisements on our website. Google, as a third-party vendor, uses cookies to serve ads based on your prior visits to this and other websites. Google&#39;s use of advertising cookies enables it and its partners to serve ads to you based on your visit to our site and/or other sites on the Internet.</p>

<p>You may opt out of personalised advertising by visiting <a href="https://www.google.com/settings/ads" target="_blank" rel="noopener noreferrer">Google&#39;s Ads Settings</a>. Alternatively, you can opt out of a third-party vendor&#39;s use of cookies for personalised advertising by visiting <a href="http://www.aboutads.info/choices/" target="_blank" rel="noopener noreferrer">www.aboutads.info/choices</a>.</p>

<p>For more information on how Google collects and uses data when you use our website, visit <a href="https://policies.google.com/technologies/partner-sites" target="_blank" rel="noopener noreferrer">Google&#39;s Privacy &amp; Terms</a>.</p>

<h2>4. Third-Party Links</h2>
<p>Our website may contain links to third-party websites. We are not responsible for the privacy practices or the content of those sites. We encourage you to review the privacy policies of any third-party sites you visit.</p>

<h2>5. Children&#39;s Privacy</h2>
<p>Our website is not directed at children under the age of 13. We do not knowingly collect personal information from children. If you believe a child has provided us with personal information, please contact us and we will delete it.</p>

<h2>6. Your Rights (GDPR)</h2>
<p>If you are located in the European Economic Area (EEA), you have the following rights regarding your personal data:</p>
<ul>
  <li><strong>Right to access</strong> — You can request a copy of any personal data we hold about you.</li>
  <li><strong>Right to rectification</strong> — You can ask us to correct inaccurate data.</li>
  <li><strong>Right to erasure</strong> — You can request deletion of your personal data.</li>
  <li><strong>Right to restrict processing</strong> — You can ask us to limit how we use your data.</li>
  <li><strong>Right to object</strong> — You can object to our processing of your data.</li>
  <li><strong>Right to data portability</strong> — You can request your data in a portable format.</li>
</ul>
<p>To exercise any of these rights, please contact us at <a href="mailto:hello@khantools.com">hello@khantools.com</a>.</p>

<h2>7. Data Security</h2>
<p>We take reasonable technical and organisational measures to protect the information we hold. However, no method of transmission over the internet or electronic storage is completely secure, and we cannot guarantee absolute security.</p>

<h2>8. Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.</p>

<h2>9. Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at:</p>
<p><strong><a href="mailto:hello@khantools.com">hello@khantools.com</a></strong><br>KhanTools — khantools.com</p>
HTML,
            ],
        ];

        foreach ($pages as $key => $data) {
            StaticPage::updateOrCreate(['key' => $key], $data);
        }
    }
}
