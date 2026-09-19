<style>
    :root {
        --fast-primary: #0D1B8C;
        --fast-primary-dark: #091267;
        --fast-primary-light: #E8EBFF;
        --fast-surface: #F5F7FF;
    }

    /* Warna utama seluruh antarmuka FASTON 360. */
    body { background-color: var(--fast-surface) !important; }
    .bg-\[\#0D1B8C\], .bg-blue-500, .bg-blue-600, .bg-blue-700, .bg-sky-500,
    .bg-violet-500, .bg-violet-600, .bg-violet-700, .bg-purple-500, .bg-purple-600,
    .bg-amber-500, .bg-amber-600, .bg-amber-700 {
        background-color: var(--fast-primary) !important;
    }
    .hover\:bg-blue-600:hover, .hover\:bg-blue-700:hover, .hover\:bg-blue-800:hover,
    .hover\:bg-sky-400:hover, .hover\:bg-sky-500:hover, .hover\:bg-violet-600:hover,
    .hover\:bg-violet-700:hover, .hover\:bg-amber-600:hover, .hover\:bg-amber-700:hover {
        background-color: var(--fast-primary-dark) !important;
    }
    .from-\[\#0D1B8C\], .to-\[\#0D1B8C\], .to-\[\#2B73FE\] {
        --tw-gradient-from: var(--fast-primary) var(--tw-gradient-from-position) !important;
        --tw-gradient-to: var(--fast-primary) var(--tw-gradient-to-position) !important;
    }
    .text-blue-500, .text-blue-600, .text-blue-700, .text-blue-800, .text-blue-900,
    .text-sky-500, .text-sky-600, .text-sky-700, .text-violet-600, .text-violet-700,
    .text-violet-800, .text-violet-900, .text-purple-600, .text-purple-700,
    .text-amber-600, .text-amber-700, .text-amber-800, .text-amber-900 {
        color: var(--fast-primary) !important;
    }
    .border-blue-100, .border-blue-200, .border-blue-300, .border-blue-600, .border-blue-700,
    .border-sky-200, .border-violet-200, .border-violet-300, .border-purple-200,
    .border-amber-100, .border-amber-200 {
        border-color: #B8C0F4 !important;
    }
    .bg-blue-50, .bg-blue-100, .bg-sky-50, .bg-sky-100, .bg-violet-50, .bg-violet-100,
    .bg-purple-50, .bg-purple-100, .bg-amber-50, .bg-amber-100 {
        background-color: var(--fast-primary-light) !important;
    }

    /* Merah dan hijau sengaja tidak diubah karena menandakan error dan berhasil. */
    .focus\:ring-blue-400:focus, .focus\:ring-blue-500:focus { --tw-ring-color: var(--fast-primary) !important; }
</style>
