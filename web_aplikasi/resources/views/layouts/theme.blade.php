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

    :root {
        --sidebar-bg: #0D1B8C;
        --sidebar-hover: #1727a6;
        --sidebar-active-bg: #ffffff;
        --sidebar-active-text: #0D1B8C;
        --main-bg: #f0f4f8;
    }
    body { font-family: 'Inter', sans-serif; background-color: var(--main-bg); }
    h1, h2, h3, h4, h5, h6 { font-family: 'Inter', sans-serif; }

    .sidebar-link {
        color: #d1d5db;
        font-size: 0.85rem;
        font-weight: 600;
        letter-spacing: 0.02em;
        transition: all 0.2s ease;
    }
    .sidebar-link:hover:not(.active-link) {
        background-color: var(--sidebar-hover);
        color: #ffffff;
    }
    .sidebar-link.active-link {
        background-color: var(--sidebar-active-bg);
        color: var(--sidebar-active-text);
        border-radius: 9999px;
        font-weight: 700;
    }
    .sidebar-icon { color: inherit; }

    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    .glass-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        border: 1px solid #e2e8f0;
        position: relative;
        overflow: hidden;
    }
    .wave-bg {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 40px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1440 320'%3E%3Cpath fill='%23e0e7ff' fill-opacity='0.5' d='M0,224L48,213.3C96,203,192,181,288,181.3C384,181,480,203,576,197.3C672,192,768,160,864,160C960,160,1056,192,1152,197.3C1248,203,1344,181,1392,170.7L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z'%3E%3C/path%3E%3Cpath fill='none' stroke='%233b82f6' stroke-width='3' stroke-opacity='0.4' d='M0,192L48,181.3C96,171,192,149,288,154.7C384,160,480,192,576,197.3C672,203,768,181,864,165.3C960,149,1056,139,1152,144C1248,149,1344,171,1392,181.3L1440,192'%3E%3C/path%3E%3C/svg%3E");
        background-size: cover;
        background-position: bottom;
        background-repeat: no-repeat;
        z-index: 1;
    }
</style>
