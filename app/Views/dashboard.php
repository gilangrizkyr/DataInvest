<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Statistik - DPMPTSP Tanah Bumbu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-pl5ugin-datalabels"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js"
        integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/dashboard.css') ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.31/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <style>
        /* ============================================================
   DPMPTSP Tanah Bumbu — Dashboard Statistik
   Modern Dashboard CSS  v3.0
   Aesthetic: Refined Gov-Tech — clean authority meets soft depth
   ============================================================ */

        /* ─── Google Fonts ─────────────────────────────────────────── */
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=Playfair+Display:wght@600;700&display=swap');

        /* ─── CSS Variables ────────────────────────────────────────── */
        :root {
            /* Primary palette */
            --clr-primary: #2563eb;
            --clr-primary-light: #3b82f6;
            --clr-primary-dark: #1d4ed8;
            --clr-primary-50: #eff6ff;
            --clr-primary-100: #dbeafe;

            /* Accent / status */
            --clr-success: #10b981;
            --clr-success-bg: #d1fae5;
            --clr-warning: #f59e0b;
            --clr-warning-bg: #fef3c7;
            --clr-danger: #ef4444;
            --clr-danger-bg: #fee2e2;
            --clr-info: #6366f1;
            --clr-info-bg: #eef2ff;
            --clr-orange: #f97316;
            --clr-orange-bg: #ffedd5;
            --clr-purple: #8b5cf6;
            --clr-purple-bg: #ede9fe;

            /* Neutrals */
            --clr-gray-50: #f8fafc;
            --clr-gray-100: #f1f5f9;
            --clr-gray-200: #e2e8f0;
            --clr-gray-300: #cbd5e1;
            --clr-gray-400: #94a3b8;
            --clr-gray-500: #64748b;
            --clr-gray-600: #475569;
            --clr-gray-700: #334155;
            --clr-gray-800: #1e293b;
            --clr-gray-900: #0f172a;

            /* Backgrounds */
            --bg-page: #eef2f7;
            --bg-card: #ffffff;
            --bg-card-hover: #fafbff;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06), 0 1px 2px rgba(0, 0, 0, .04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, .07), 0 2px 4px -2px rgba(0, 0, 0, .05);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, .08), 0 4px 6px -4px rgba(0, 0, 0, .05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, .08), 0 8px 10px -6px rgba(0, 0, 0, .04);
            --shadow-card: 0 2px 12px rgba(30, 41, 59, .08);
            --shadow-card-hover: 0 6px 24px rgba(30, 41, 59, .13);
            --shadow-navbar: 0 4px 24px rgba(37, 99, 235, .25);

            /* Radii */
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
            --radius-xl: 18px;
            --radius-2xl: 24px;

            /* Typography */
            --font-body: 'DM Sans', system-ui, sans-serif;
            --font-display: 'Playfair Display', Georgia, serif;

            /* Transitions */
            --ease: cubic-bezier(.4, 0, .2, 1);
            --dur-fast: 150ms;
            --dur-md: 250ms;
            --dur-slow: 400ms;
        }

        /* ─── Reset & Base ─────────────────────────────────────────── */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            font-size: 16px;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: var(--font-body);
            background: var(--bg-page);
            color: var(--clr-gray-700);
            line-height: 1.6;
            min-height: 100vh;
            /* subtle grain overlay */
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.018'/%3E%3C/svg%3E");
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        img {
            max-width: 100%;
            display: block;
        }

        /* ─── Page-level gradient background ──────────────────────── */
        .gradient-bg {
            background: linear-gradient(135deg, #eef2f7 0%, #e0e7f3 50%, #ece5f5 100%);
            min-height: 100vh;
            position: relative;
        }

        /* large decorative blobs — purely decorative, pointer-events off */
        .gradient-bg::before,
        .gradient-bg::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: .18;
            pointer-events: none;
            z-index: 0;
        }

        .gradient-bg::before {
            width: 520px;
            height: 520px;
            background: radial-gradient(circle, #3b82f6, transparent 70%);
            top: -180px;
            right: -140px;
        }

        .gradient-bg::after {
            width: 420px;
            height: 420px;
            background: radial-gradient(circle, #8b5cf6, transparent 70%);
            bottom: -120px;
            left: -100px;
        }

        /* keep content above blobs */
        .min-h-screen>* {
            position: relative;
            z-index: 1;
        }

        /* ─── Navbar ───────────────────────────────────────────────── */
        nav.bg-gradient-to-r {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 50%, #3b82f6 100%) !important;
            box-shadow: var(--shadow-navbar);
            border-radius: var(--radius-xl);
            border: 1px solid rgba(255, 255, 255, .12);
            backdrop-filter: saturate(1.4);
            position: relative;
            overflow: hidden;
        }

        /* subtle shimmer bar across navbar */
        nav.bg-gradient-to-r::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 60%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .08), transparent);
            animation: shimmer 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes shimmer {
            0% {
                left: -60%;
            }

            100% {
                left: 140%;
            }
        }

        /* nav icon accent */
        nav .fa-chart-pie {
            drop-shadow: 0 0 8px rgba(147, 197, 253, .5);
        }

        /* nav title font */
        nav h1 {
            font-family: var(--font-display);
            letter-spacing: -.5px;
        }

        nav p {
            opacity: .82;
            letter-spacing: .3px;
        }

        /* ─── Navbar Buttons ───────────────────────────────────────── */
        nav button,
        nav a.px-5 {
            font-family: var(--font-body);
            font-weight: 600;
            letter-spacing: .3px;
            border: none;
            cursor: pointer;
            transition: transform var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease),
                filter var(--dur-fast) var(--ease);
            position: relative;
            overflow: hidden;
        }

        nav button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, .22);
            filter: brightness(1.08);
        }

        nav button:active {
            transform: translateY(0);
        }

        /* language select */
        nav select#language-switcher {
            background: linear-gradient(135deg, rgba(59, 130, 246, .7), rgba(37, 99, 235, .9));
            border: 1px solid rgba(255, 255, 255, .25);
            color: #fff;
            font-family: var(--font-body);
            font-weight: 600;
            cursor: pointer;
            transition: background var(--dur-fast) var(--ease);
        }

        nav select#language-switcher:hover {
            background: linear-gradient(135deg, rgba(59, 130, 246, .85), rgba(37, 99, 235, 1));
        }

        /* ─── Glass Card ───────────────────────────────────────────── */
        .glass-card {
            background: rgba(255, 255, 255, .88);
            backdrop-filter: blur(12px) saturate(1.3);
            -webkit-backdrop-filter: blur(12px) saturate(1.3);
            border: 1px solid rgba(255, 255, 255, .7);
            box-shadow: var(--shadow-card);
            border-radius: var(--radius-xl);
            transition: box-shadow var(--dur-md) var(--ease),
                transform var(--dur-md) var(--ease),
                border-color var(--dur-md) var(--ease);
        }

        .glass-card:hover {
            box-shadow: var(--shadow-card-hover);
            border-color: rgba(37, 99, 235, .18);
            transform: translateY(-1px);
        }

        /* card headings */
        .glass-card h2,
        .glass-card h3 {
            font-family: var(--font-body);
            color: var(--clr-gray-800);
            letter-spacing: -.3px;
        }

        /* ─── Chart Container (canvas wrapper) ─────────────────────── */
        .chart-container {
            position: relative;
        }

        .chart-container canvas {
            max-width: 100%;
            transition: opacity var(--dur-md) var(--ease);
        }

        /* ─── Stat Cards ───────────────────────────────────────────── */
        .modern-card {
            border-radius: var(--radius-xl);
            position: relative;
            overflow: hidden;
            transition: box-shadow var(--dur-md) var(--ease),
                transform var(--dur-md) var(--ease);
        }

        .modern-card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        /* animated fade-in for stat cards */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp var(--dur-slow) var(--ease) both;
        }

        /* staggered delay helpers */
        .animate-fade-in:nth-child(1) {
            animation-delay: 0ms;
        }

        .animate-fade-in:nth-child(2) {
            animation-delay: 60ms;
        }

        .animate-fade-in:nth-child(3) {
            animation-delay: 120ms;
        }

        .animate-fade-in:nth-child(4) {
            animation-delay: 180ms;
        }

        .animate-fade-in:nth-child(5) {
            animation-delay: 240ms;
        }

        .animate-fade-in:nth-child(6) {
            animation-delay: 300ms;
        }

        .animate-fade-in:nth-child(7) {
            animation-delay: 360ms;
        }

        .animate-fade-in:nth-child(8) {
            animation-delay: 420ms;
        }

        .animate-fade-in:nth-child(9) {
            animation-delay: 480ms;
        }

        /* left border glow on hover */
        .modern-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: currentColor;
            border-radius: var(--radius-xl) 0 0 var(--radius-xl);
            transition: opacity var(--dur-md) var(--ease);
        }

        /* stat card icon circle */
        .modern-card .bg-blue-100,
        .modern-card .bg-green-100,
        .modern-card .bg-teal-100,
        .modern-card .bg-purple-100,
        .modern-card .bg-orange-100,
        .modern-card .bg-indigo-100,
        .modern-card .bg-cyan-100,
        .modern-card .bg-pink-100,
        .modern-card .bg-lime-100 {
            transition: transform var(--dur-md) var(--ease);
        }

        .modern-card:hover .bg-blue-100,
        .modern-card:hover .bg-green-100,
        .modern-card:hover .bg-teal-100,
        .modern-card:hover .bg-purple-100,
        .modern-card:hover .bg-orange-100,
        .modern-card:hover .bg-indigo-100,
        .modern-card:hover .bg-cyan-100,
        .modern-card:hover .bg-pink-100,
        .modern-card:hover .bg-lime-100 {
            transform: scale(1.1) rotate(3deg);
        }

        /* ─── Upload Box — Drag & Drop Zone ────────────────────────── */
        #drop-zone {
            border-radius: var(--radius-lg);
            border: 2px dashed var(--clr-primary-light) !important;
            background: linear-gradient(135deg, var(--clr-primary-50), rgba(255, 255, 255, .9));
            transition: border-color var(--dur-md) var(--ease),
                background var(--dur-md) var(--ease),
                box-shadow var(--dur-md) var(--ease);
        }

        #drop-zone:hover,
        #drop-zone.border-blue-600 {
            border-color: var(--clr-primary-dark) !important;
            background: linear-gradient(135deg, var(--clr-primary-100), rgba(219, 234, 254, .95));
            box-shadow: 0 0 0 4px rgba(37, 99, 235, .1);
        }

        #drop-zone .fa-cloud-upload-alt {
            transition: transform var(--dur-md) var(--ease);
        }

        #drop-zone:hover .fa-cloud-upload-alt {
            transform: translateY(-4px);
        }

        /* upload button */
        form button[type="submit"] {
            font-family: var(--font-body);
            font-weight: 700;
            letter-spacing: .4px;
            border: none;
            border-radius: var(--radius-lg);
            transition: transform var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease),
                filter var(--dur-fast) var(--ease);
        }

        form button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, .35);
            filter: brightness(1.06);
        }

        /* ─── Upload Management Table ──────────────────────────────── */
        .glass-card table {
            border-collapse: collapse;
            width: 100%;
        }

        .glass-card thead tr {
            background: var(--clr-gray-50);
            border-bottom: 2px solid var(--clr-gray-200);
        }

        .glass-card thead th {
            font-family: var(--font-body);
            font-weight: 600;
            color: var(--clr-gray-500);
            text-transform: uppercase;
            font-size: .72rem;
            letter-spacing: .8px;
            padding: 12px 16px;
            text-align: left;
            white-space: nowrap;
        }

        .glass-card tbody tr {
            border-bottom: 1px solid var(--clr-gray-100);
            transition: background var(--dur-fast) var(--ease);
        }

        .glass-card tbody tr:last-child {
            border-bottom: none;
        }

        .glass-card tbody tr:hover {
            background: var(--clr-primary-50);
        }

        .glass-card tbody td {
            padding: 14px 16px;
            font-size: .875rem;
            color: var(--clr-gray-600);
            vertical-align: middle;
        }

        .glass-card tbody td:first-child {
            font-weight: 600;
            color: var(--clr-gray-800);
        }

        /* status badges */
        .glass-card .inline-flex.rounded-full {
            font-size: .75rem;
            font-weight: 600;
            letter-spacing: .3px;
            padding: 3px 10px;
            border-radius: 999px;
        }

        /* action icons */
        .glass-card .fa-eye,
        .glass-card .fa-edit,
        .glass-card .fa-trash {
            font-size: 1rem;
            transition: transform var(--dur-fast) var(--ease),
                color var(--dur-fast) var(--ease);
        }

        .glass-card .fa-eye:hover {
            transform: scale(1.2);
        }

        .glass-card .fa-edit:hover {
            transform: scale(1.2);
        }

        .glass-card .fa-trash:hover {
            transform: scale(1.2);
        }

        /* currency select in upload management */
        #filter-currency {
            font-family: var(--font-body);
            font-weight: 500;
            border: 1px solid var(--clr-gray-200);
            border-radius: var(--radius-md);
            padding: 6px 12px;
            background: #fff;
            color: var(--clr-gray-700);
            cursor: pointer;
            transition: border-color var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease);
        }

        #filter-currency:focus {
            outline: none;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        /* ─── Chart Options Panel ──────────────────────────────────── */
        #search-charts {
            font-family: var(--font-body);
            border: 1px solid var(--clr-gray-200);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            background: var(--clr-gray-50);
            color: var(--clr-gray-700);
            transition: border-color var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease),
                background var(--dur-fast) var(--ease);
            width: 100%;
        }

        #search-charts:focus {
            outline: none;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
            background: #fff;
        }

        /* chart-item rows */
        .chart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            border-radius: var(--radius-md);
            background: var(--clr-gray-50);
            border: 1px solid transparent;
            transition: background var(--dur-fast) var(--ease),
                border-color var(--dur-fast) var(--ease);
            cursor: pointer;
        }

        .chart-item:hover {
            background: var(--clr-primary-50);
            border-color: var(--clr-primary-100);
        }

        .chart-item span {
            font-weight: 500;
            color: var(--clr-gray-700);
            font-size: .88rem;
        }

        /* ─── Toggle Switch ────────────────────────────────────────── */
        input.toggle {
            appearance: none;
            -webkit-appearance: none;
            width: 42px;
            height: 24px;
            border-radius: 12px;
            background: var(--clr-gray-300);
            position: relative;
            cursor: pointer;
            transition: background var(--dur-md) var(--ease);
            flex-shrink: 0;
        }

        input.toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
            transition: left var(--dur-md) var(--ease),
                box-shadow var(--dur-md) var(--ease);
        }

        input.toggle:checked::after {
            left: 21px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        /* colour variants */
        input.toggle.toggle-blue:checked {
            background: var(--clr-primary);
        }

        input.toggle.toggle-green:checked {
            background: var(--clr-success);
        }

        input.toggle.toggle-yellow:checked {
            background: var(--clr-warning);
        }

        input.toggle.toggle-purple:checked {
            background: var(--clr-purple);
        }

        /* ─── Chart Type Selects (inside each chart card) ──────────── */
        .glass-card select {
            font-family: var(--font-body);
            font-size: .82rem;
            font-weight: 500;
            border: 1px solid var(--clr-gray-200);
            border-radius: var(--radius-sm);
            padding: 5px 10px;
            background: #fff;
            color: var(--clr-gray-600);
            cursor: pointer;
            transition: border-color var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease);
        }

        .glass-card select:focus {
            outline: none;
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
        }

        /* ─── PMA / PMDN Company Tables ────────────────────────────── */
        #sector-count-tables .glass-card {
            transition: box-shadow var(--dur-md) var(--ease),
                transform var(--dur-md) var(--ease);
        }

        /* download buttons */
        #sector-count-tables button {
            font-family: var(--font-body);
            font-weight: 600;
            letter-spacing: .3px;
            border: none;
            border-radius: var(--radius-md);
            cursor: pointer;
            transition: transform var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease),
                filter var(--dur-fast) var(--ease);
        }

        #sector-count-tables button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .2);
            filter: brightness(1.08);
        }

        /* search inputs */
        #search-pma,
        #search-pmdn {
            font-family: var(--font-body);
            border: 1px solid var(--clr-gray-200);
            border-radius: var(--radius-md);
            padding: 10px 14px;
            background: var(--clr-gray-50);
            transition: border-color var(--dur-fast) var(--ease),
                box-shadow var(--dur-fast) var(--ease),
                background var(--dur-fast) var(--ease);
        }

        #search-pma:focus,
        #search-pmdn:focus {
            outline: none;
            background: #fff;
        }

        #search-pma:focus {
            border-color: var(--clr-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
        }

        #search-pmdn:focus {
            border-color: var(--clr-orange);
            box-shadow: 0 0 0 3px rgba(249, 115, 22, .15);
        }

        /* scrollable table wrapper */
        #sector-count-tables .overflow-x-auto {
            border-radius: var(--radius-md);
            border: 1px solid var(--clr-gray-200);
            overflow: hidden;
        }

        /* sticky header — PMA blue / PMDN orange */
        #sector-count-tables thead.bg-blue-50 {
            background: linear-gradient(180deg, #eff6ff, #dbeafe);
        }

        #sector-count-tables thead.bg-orange-50 {
            background: linear-gradient(180deg, #fff7ed, #ffedd5);
        }

        #sector-count-tables thead th {
            font-size: .73rem;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: var(--clr-gray-600);
            padding: 12px 16px;
            border-bottom: 2px solid var(--clr-gray-200);
            white-space: nowrap;
            position: sticky;
            top: 0;
            background: inherit;
            z-index: 10;
        }

        #sector-count-tables tbody tr {
            border-bottom: 1px solid var(--clr-gray-100);
            transition: background var(--dur-fast) var(--ease);
        }

        #sector-count-tables tbody tr:last-child {
            border-bottom: none;
        }

        .pma-row:hover {
            background: #eff6ff;
        }

        .pmdn-row:hover {
            background: #fff7ed;
        }

        #sector-count-tables tbody td {
            padding: 11px 16px;
            font-size: .87rem;
            color: var(--clr-gray-600);
        }

        #sector-count-tables tbody td:first-child {
            font-weight: 600;
            color: var(--clr-gray-800);
        }

        /* summary bar */
        #sector-count-tables .bg-blue-50 {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
        }

        #sector-count-tables .bg-orange-50 {
            background: linear-gradient(135deg, #fff7ed, #ffedd5);
            border: 1px solid #fed7aa;
        }

        /* ─── Custom Scrollbar ─────────────────────────────────────── */
        #sector-count-tables .overflow-x-auto::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        #sector-count-tables .overflow-x-auto::-webkit-scrollbar-track {
            background: var(--clr-gray-100);
            border-radius: 999px;
        }

        #sector-count-tables .overflow-x-auto::-webkit-scrollbar-thumb {
            background: var(--clr-gray-300);
            border-radius: 999px;
            transition: background var(--dur-fast) var(--ease);
        }

        #sector-count-tables .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: var(--clr-gray-400);
        }

        /* ─── Additional Investment % Tables ───────────────────────── */
        .glass-card .bg-gray-50 thead tr {
            border-bottom: 2px solid var(--clr-gray-200);
        }

        .glass-card .bg-gray-100.font-bold td {
            font-weight: 700;
            color: var(--clr-gray-800);
            background: var(--clr-gray-100);
        }

        /* ─── Ranking List ─────────────────────────────────────────── */
        #ranking-list li {
            border: 1px solid var(--clr-gray-200);
            border-radius: var(--radius-md);
            background: #fff;
            box-shadow: var(--shadow-sm);
            padding: 10px 14px;
            transition: box-shadow var(--dur-fast) var(--ease),
                transform var(--dur-fast) var(--ease);
        }

        #ranking-list li:hover {
            box-shadow: var(--shadow-md);
            transform: translateX(4px);
        }

        #ranking-list li:first-child {
            border-color: #fbbf24;
            background: linear-gradient(135deg, #fffbeb, #fff);
        }

        #ranking-list li:nth-child(2) {
            border-color: #d1d5db;
            background: linear-gradient(135deg, #f9fafb, #fff);
        }

        #ranking-list li:nth-child(3) {
            border-color: #d97706;
            background: linear-gradient(135deg, #fffbeb, #fff);
        }

        /* ─── Sweetalert2 Tweaks ───────────────────────────────────── */
        .swal2-popup {
            border-radius: var(--radius-xl) !important;
            font-family: var(--font-body) !important;
            box-shadow: var(--shadow-xl) !important;
        }

        .swal2-confirm {
            border-radius: var(--radius-md) !important;
            font-weight: 600 !important;
        }

        .swal2-cancel {
            border-radius: var(--radius-md) !important;
            font-weight: 600 !important;
        }

        /* ─── Footer ───────────────────────────────────────────────── */
        footer {
            background: linear-gradient(135deg, var(--clr-gray-800), var(--clr-gray-900));
            border-top: 1px solid rgba(255, 255, 255, .06);
            position: relative;
            z-index: 1;
        }

        footer span,
        footer div {
            color: rgba(255, 255, 255, .7);
            font-size: .82rem;
            letter-spacing: .3px;
        }

        /* ─── Responsive Helpers ───────────────────────────────────── */
        @media (max-width: 1024px) {
            .glass-card {
                border-radius: var(--radius-lg);
            }
        }

        @media (max-width: 768px) {
            nav .flex.justify-between {
                flex-direction: column;
                gap: 16px;
            }

            nav .flex.items-center.space-x-3 {
                flex-wrap: wrap;
                justify-content: center;
                gap: 8px;
            }

            nav h1 {
                font-size: 1.35rem;
            }

            .glass-card {
                border-radius: var(--radius-lg);
                padding: 16px !important;
            }

            #sector-count-tables {
                grid-template-columns: 1fr !important;
            }
        }

        @media (max-width: 480px) {
            nav h1 {
                font-size: 1.15rem;
            }

            nav p {
                font-size: .78rem;
            }

            .modern-card {
                border-radius: var(--radius-lg);
            }

            .glass-card h2,
            .glass-card h3 {
                font-size: 1rem !important;
            }
        }

        /* ─── Print Styles ─────────────────────────────────────────── */
        @media print {

            .gradient-bg::before,
            .gradient-bg::after {
                display: none;
            }

            nav,
            #drop-zone,
            form button,
            #search-charts,
            .chart-item input,
            #filter-currency,
            #search-pma,
            #search-pmdn,
            #sector-count-tables button,
            footer {
                display: none !important;
            }

            .glass-card {
                background: #fff;
                box-shadow: none;
                border: 1px solid #e2e8f0;
                break-inside: avoid;
                margin-bottom: 24px;
            }

            body {
                background: #fff;
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <div class="min-h-screen">
        <!-- Content -->
        <div class="p-4 md:p-6">
            <div class="container mx-auto max-w-7xl">
                <!-- Navbar -->
                <nav
                    class="bg-gradient-to-r from-blue-600 to-blue-700 shadow-lg mb-8  border border-blue-700/40 rounded-xl">
                    <div class="container mx-auto px-6 py-4">
                        <div class="flex justify-between items-center">
                            <!-- Logo & Title Section -->
                            <div class="flex items-center space-x-4">
                                <i class="fas fa-chart-pie text-3xl text-blue-300"></i>
                                <div>
                                    <h1 class="text-2xl font-bold text-white">
                                        <?= lang('Dashboard.dashboard_title') ?>
                                    </h1>
                                    <p class="text-blue-100 text-sm"><?= lang('Dashboard.dashboard_subtitle') ?></p>
                                </div>
                            </div>

                            <!-- Navigation Actions -->
                            <div class="flex items-center space-x-3">
                                <!-- Language Switcher -->
                                <div class="flex items-center space-x-2">
                                    <span class="text-blue-100 text-sm font-medium">
                                        <?= lang('Dashboard.language') ?>:
                                    </span>
                                    <select id="language-switcher"
                                        class="px-5 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold 
                                 rounded-lg shadow-md hover:from-blue-500 hover:to-blue-700 hover:scale-105 
                                     transform transition duration-300 focus:ring-2 focus:ring-blue-300 cursor-pointer">
                                        <option class="bg-white text-black" value="id"
                                            <?= service('request')->getLocale() === 'id' ? 'selected' : '' ?>>
                                            <?= lang('Dashboard.indonesian') ?>
                                        </option>
                                        <option class="bg-white text-black" value="en"
                                            <?= service('request')->getLocale() === 'en' ? 'selected' : '' ?>>
                                            <?= lang('Dashboard.english') ?>
                                        </option>
                                    </select>
                                </div>
                                <button type="button" onclick="window.location.href='<?= base_url('/faq') ?>'" class="px-5 py-2 bg-gradient-to-r from-purple-500 to-indigo-600 text-white font-semibold 
                                             rounded-lg shadow-md hover:from-purple-600 hover:to-indigo-700 hover:scale-105 
                                       transform transition duration-300">
                                    <i class="fas fa-question-circle mr-2"></i>
                                    FAQ
                                </button>
                                <?php if (session()->get('role') === 'superadmin'): ?>

                                    <!-- Security Check Button -->

                                    <button type="button"
                                        onclick="window.location.href='<?= base_url('security-monitoring') ?>'" class="px-5 py-2 bg-gradient-to-r from-blue-400 to-blue-600 text-white font-semibold 
                                          rounded-lg shadow-md hover:from-blue-500 hover:to-blue-700 hover:scale-105 
                                              transform transition duration-300">
                                        <i class="fas fa-shield-alt mr-2"></i>
                                        Cek Keamanan
                                    </button>

                                    <!-- User Management Button -->
                                    <button type="button"
                                        onclick="window.location.href='<?= base_url('/user-management') ?>'" class="px-5 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold 
                           rounded-lg shadow hover:from-green-600 hover:to-green-700 
                           transition duration-200">
                                        <i class="fas fa-users mr-2"></i>
                                        <?= lang('Dashboard.user_management') ?>
                                    </button>
                                    <!-- FAQ Button -->


                                <?php endif; ?>
                                <!-- Logout Button -->
                                <button type="button" onclick="window.location.href='<?= base_url('auth/logout') ?>'"
                                    class="px-5 py-2 bg-red-600 text-white font-semibold 
                           rounded-lg shadow hover:bg-red-700 
                           transition duration-200">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </nav>


                <!-- Control Panels -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Upload Box -->
                    <?php if (session()->get('role') !== 'user'): ?>
                        <div class="glass-card shadow-2xl rounded-xl p-6 chart-container lg:col-span-1">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-upload text-blue-600 mr-3 text-xl"></i>
                                <h2 class="text-xl font-semibold text-gray-800"><?= lang('Dashboard.upload_file_excel') ?>
                                </h2>
                            </div>
                            <form action="<?= base_url('dashboard/upload') ?>" method="post" enctype="multipart/form-data"
                                class="space-y-4">
                                <div class="relative">
                                    <input type="file" name="excel_file" accept=".xlsx,.xls" id="excel-file-input"
                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                    <div id="drop-zone"
                                        class="border-2 border-dashed border-blue-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-4xl text-blue-400 mb-2"></i>
                                        <p id="upload-text" class="text-gray-600 mb-1">
                                            <?= lang('Dashboard.drag_drop_file') ?>
                                        </p>
                                        <p class="text-sm text-gray-500"><?= lang('Dashboard.supported_formats') ?></p>
                                        <p id="file-name" class="text-sm text-blue-600 font-medium mt-2 hidden"></p>
                                    </div>
                                </div>
                                <button type="submit"
                                    class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i><?= lang('Dashboard.upload_and_process') ?>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Manajemen Upload -->
                    <div class="glass-card shadow-xl rounded-xl p-6 chart-container lg:col-span-2">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-folder-open text-blue-600 mr-3 text-xl"></i>
                                <h2 class="text-xl font-semibold text-gray-800">
                                    <?= lang('Dashboard.upload_management') ?>
                                </h2>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i
                                        class="fas fa-dollar-sign mr-1 text-yellow-600"></i><?= lang('Dashboard.currency') ?></label>
                                <select id="filter-currency"
                                    class="rounded-lg border-gray-300 shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                                    <option value="IDR">IDR (Rp)</option>
                                    <option value="USD">$ USD</option>
                                </select>
                            </div>
                        </div>

                        <?php if (!empty($data['uploads'])): ?>
                            <div class="overflow-x-auto">
                                <table class="min-w-full table-auto">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?= lang('Dashboard.upload_name') ?>
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?= lang('Dashboard.quarter') ?>
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?= lang('Dashboard.year') ?>
                                            </th>
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?= lang('Dashboard.status') ?>
                                            </th>
                                            <!-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.total_records') ?></th> -->
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Nilai USD</th>
                                            <!-- <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= lang('Dashboard.upload_date') ?></th> -->
                                            <th
                                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                <?= lang('Dashboard.actions') ?>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['uploads'] as $upload): ?>
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($upload['upload_name'] ?? 'N/A'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($upload['quarter'] ?? '-'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($upload['year'] ?? '-'); ?>
                                                </td>
                                                <td class="px-4 py-4 whitespace-nowrap">
                                                    <?php
                                                    $status = $upload['status'] ?? 'unknown';
                                                    $statusClasses = [
                                                        'completed' => 'bg-green-100 text-green-800',
                                                        'failed' => 'bg-red-100 text-red-800',
                                                        'processing' => 'bg-yellow-100 text-yellow-800',
                                                        'uploaded' => 'bg-blue-100 text-blue-800'
                                                    ];
                                                    $statusLabels = [
                                                        'completed' => lang('Dashboard.status_completed'),
                                                        'failed' => lang('Dashboard.status_failed'),
                                                        'processing' => lang('Dashboard.status_processing'),
                                                        'uploaded' => lang('Dashboard.status_uploaded')
                                                    ];
                                                    ?>
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $statusClasses[$status] ?? 'bg-gray-100 text-gray-800'; ?>">
                                                        <?php echo $statusLabels[$status] ?? ucfirst($status); ?>
                                                    </span>
                                                </td>
                                                <!-- <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($upload['total_records'] ?? 0); ?>
                                                </td> -->
                                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($upload['usd_value'] ?? 0, 2, ',', '.'); ?>
                                                </td>
                                                <!-- <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo date('d/m/Y H:i', strtotime($upload['created_at'] ?? 'upload_date')); ?>
                                                </td> -->
                                                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="<?= base_url('dashboard?upload=' . $upload['id']) ?>"
                                                            class="text-green-600 hover:text-green-900 transition-colors"
                                                            title="View Chart">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <?php if (session()->get('role') !== 'user'): ?>
                                                            <?php if ($upload['status'] === 'completed'): ?>
                                                                <a href="<?= base_url('dashboard/edit-metadata/' . $upload['id']) ?>"
                                                                    class="text-blue-600 hover:text-blue-900 transition-colors"
                                                                    title="Edit Metadata">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <form action="<?= base_url('dashboard/deleteUpload') ?>" method="post"
                                                                class="inline-block" id="delete-form-<?php echo $upload['id']; ?>">
                                                                <input type="hidden" name="upload_id"
                                                                    value="<?php echo $upload['id']; ?>">
                                                                <button type="button"
                                                                    onclick="confirmDelete(<?php echo $upload['id']; ?>)"
                                                                    class="text-red-600 hover:text-red-900 transition-colors"
                                                                    title="<?= lang('Dashboard.delete') ?>">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <i class="fas fa-folder-open text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500"><?= lang('Dashboard.no_uploads') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Chart Options -->
                </div>
                <!-- 
                <div class="glass-card shadow-xl rounded-xl p-6 mb-12 chart-container lg:col-span-1">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i><?= lang('Dashboard.chart_options') ?>
                    </h3>

                    <input type="text" id="search-charts" placeholder="Search charts..."
                        class="w-full mb-4 px-3 py-2 rounded border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-400">

                    <div class="space-y-3" id="chart-list">
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.pma_vs_pmdn') ?></span>
                            <input type="checkbox" id="show-pma-pmdn" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.projects_by_district') ?></span>
                            <input type="checkbox" id="show-district" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.investment_by_location') ?></span>
                            <input type="checkbox" id="show-investment" checked class="toggle toggle-yellow">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.projects_by_sector') ?></span>
                            <input type="checkbox" id="show-sector" checked class="toggle toggle-green">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.workforce_pma') ?></span>
                            <input type="checkbox" id="show-workforce-pma" checked class="toggle toggle-green">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.workforce_pmdn') ?></span>
                            <input type="checkbox" id="show-workforce-pmdn" checked class="toggle toggle-green">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.ranking_projects_district') ?></span>
                            <input type="checkbox" id="show-ranking-district" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.projects_pma_district') ?></span>
                            <input type="checkbox" id="show-projects-pma" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.projects_pmdn_district') ?></span>
                            <input type="checkbox" id="show-projects-pmdn" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span><?= lang('Dashboard.projects_by_country') ?></span>
                            <input type="checkbox" id="show-country" checked class="toggle toggle-blue">
                        </label>
                        <label class="flex items-center justify-between chart-item">
                            <span>Quarterly Additional Investment <?= lang('') ?></span>
                            <input type="checkbox" id="show-quarterly-additional-investment" checked
                                class="toggle toggle-yellow">
                        </label>
                    </div>
                </div>
                -->

                <!-- STAT CARDS -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8" id="stats-cards"></div>

                <!-- CHARTS -->
                <div id="charts-container">
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-1">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="pma-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-chart-pie mr-3 text-blue-600"></i><?= lang('Dashboard.pma_pmdn_ratio') ?>
                                </h3>
                                <select id="pma-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="pie">Pie</option>
                                    <option value="doughnut">Doughnut</option>
                                    <option value="bar">Bar</option>
                                </select>
                            </div>
                            <canvas id="pma-pmdn-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="district-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-map-marker-alt mr-3 text-green-600"></i><?= lang('Dashboard.projects_per_district') ?>
                                </h3>
                                <select id="district-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="district-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-2">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="investment-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-money-bill-wave mr-3 text-emerald-600"></i><?= lang('Dashboard.investment_per_district_top10') ?>
                                </h3>
                                <select id="investment-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="area">Area</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="investment-location-chart" height="250"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="sector-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-industry mr-3 text-purple-600"></i><?= lang('Dashboard.projects_per_sector') ?>
                                </h3>
                                <select id="sector-type" class="text-sm border rounded px-2 py-1">
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="bar">Vertical Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="sector-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-3">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="workforce-pma-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-users mr-3 text-red-600"></i><?= lang('Dashboard.workforce_pma_district') ?>
                                </h3>
                                <select id="workforce-pma-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="stacked">Stacked</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="workforce-pma-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="workforce-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-user-friends mr-3 text-orange-600"></i><?= lang('Dashboard.workforce_pmdn_district') ?>
                                </h3>
                                <select id="workforce-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="stacked">Stacked</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="workforce-pmdn-chart" height="200"></canvas>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-4">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container"
                            id="ranking-district-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-trophy mr-3 text-yellow-600"></i><?= lang('Dashboard.ranking_projects_district_full') ?>
                                </h3>
                                <select id="ranking-district-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <div class="flex flex-col lg:flex-row gap-6">
                                <div class="flex-1">
                                    <canvas id="ranking-district-chart" height="200"></canvas>
                                </div>
                                <div class="lg:w-80 bg-gray-50 rounded-lg p-4 border">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <i
                                            class="fas fa-medal mr-2 text-yellow-500"></i><?= lang('Dashboard.ranking_per_district') ?>
                                    </h4>
                                    <div id="ranking-list" class="space-y-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4" id="chart-row-5">
                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="projects-pma-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-building mr-3 text-blue-600"></i><?= lang('Dashboard.projects_pma_district') ?>
                                </h3>
                                <select id="projects-pma-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="projects-pma-chart" height="200"></canvas>
                        </div>

                        <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="projects-pmdn-container">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                    <i
                                        class="fas fa-home mr-3 text-orange-600"></i><?= lang('Dashboard.projects_pmdn_district') ?>
                                </h3>
                                <select id="projects-pmdn-type" class="text-sm border rounded px-2 py-1">
                                    <option value="bar">Bar</option>
                                    <option value="line">Line</option>
                                    <option value="horizontalBar">Horizontal Bar</option>
                                    <option value="horizontalLine">Horizontal Line</option>
                                    <option value="pie">Pie</option>
                                </select>
                            </div>
                            <canvas id="projects-pmdn-chart" height="300"></canvas>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-2" id="chart-row-5">
                        <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-6">
                            <div class="glass-card shadow-xl rounded-xl p-4 chart-container" id="country-container">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <i
                                            class="fas fa-globe mr-3 text-emerald-600"></i><?= lang('Dashboard.projects_per_country') ?>
                                    </h3>
                                    <select id="country-type" class="text-sm border rounded px-2 py-1">
                                        <option value="bar">Bar</option>
                                        <option value="horizontalBar">Horizontal Bar</option>
                                        <option value="pie">Pie</option>
                                        <option value="doughnut">Doughnut</option>
                                    </select>
                                </div>
                                <canvas id="country-chart" height="200"></canvas>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-4 mb-4" id="chart-row-7">
                            <div class="glass-card shadow-xl rounded-xl p-4 chart-container"
                                id="quarterly-additional-investment-container" style="display: block;">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                        <i
                                            class="fas fa-calendar-alt mr-3 text-indigo-600"></i><?= lang('Dashboard.quarterly_additional_investment') ?>
                                    </h3>
                                    <div class="flex items-center space-x-4">

                                        <div class="flex items-center space-x-2">
                                            <label
                                                class="text-sm font-medium text-gray-700"><?= lang('Dashboard.year') ?></label>
                                            <select id="quarterly-additional-investment-year"
                                                class="text-sm border rounded px-2 py-1">
                                                <option value="all"><?= lang('Dashboard.all_years') ?></option>
                                                <?php
                                                $availableYears = array_keys($data['charts']['quarterly_additional_investment_all_years'] ?? []);
                                                sort($availableYears);
                                                foreach ($availableYears as $year) {
                                                    $selected = (isset($data['filters']['quarterly_year']) && $data['filters']['quarterly_year'] == $year) ? 'selected' : '';
                                                    echo "<option value=\"$year\" $selected>$year</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <select id="quarterly-additional-investment-type"
                                            class="text-sm border rounded px-2 py-1">
                                            <option value="bar">Bar</option>
                                            <option value="line">Line</option>
                                            <option value="area">Area</option>
                                            <option value="pie">Pie</option>
                                        </select>
                                    </div>
                                </div>
                                <canvas id="quarterly-additional-investment-chart" height="200"></canvas>
                            </div>
                        </div>

                    </div>
                </div>

		  <div class="grid grid-cols-1 gap-6 mb-8" id="sector-count-tables">
                    <!-- Tabel PMA -->
                    <div class="glass-card shadow-xl rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-industry mr-3 text-blue-600"></i>
                                Perusahaan PMA (Lapor LKPM)
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="downloadSectorPDF('PMA')"
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center transition">
                                    <i class="fas fa-file-pdf mr-2"></i>PDF
                                </button>
                                <button onclick="downloadSectorExcel('PMA')"
                                    class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg flex items-center transition">
                                    <i class="fas fa-file-excel mr-2"></i>Excel
                                </button>
                            </div>
                        </div>

                        <!-- Search -->
                        <div class="mb-4">
                            <input type="text" id="search-pma" placeholder="Cari perusahaan PMA..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                            <table class="min-w-full bg-white border rounded-lg">
                                <thead class="bg-blue-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 border-b">
                                            Nama Perusahaan</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 border-b">
                                            Tambahan Realisasi</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah TKA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah TKI</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah Proyek</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="pma-table-body">
                                    <?php if (!empty($data['sector_count_by_company']['PMA']['data'])): ?>
                                        <?php foreach ($data['sector_count_by_company']['PMA']['data'] as $row): ?>
                                            <tr class="hover:bg-blue-50 transition-colors pma-row">
                                                <td class="px-4 py-3 text-sm text-gray-800 font-medium">
                                                    <?= esc($row['nama_perusahaan']) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right font-bold text-blue-600">
                                                    <?= number_format($row['tambahan_realisasi'] ?? 0, 0, ',', '.') ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_tka'] ?? 0) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_tki'] ?? 0) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_proyek'] ?? 0) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data PMA
                                                yang tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Summary -->
                        <div class="mt-4 bg-blue-50 rounded-lg p-4 border border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Total Record:</span>
                                <span class="text-lg font-bold text-blue-600"
                                    id="pma-count"><?= count($data['sector_count_by_company']['PMA']['data'] ?? []) ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel PMDN -->
                    <div class="glass-card shadow-xl rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-industry mr-3 text-orange-600"></i>
                                Perusahaan PMDN (Lapor LKPM)
                            </h3>
                            <div class="flex items-center space-x-2">
                                <button onclick="downloadSectorPDF('PMDN')"
                                    class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg flex items-center transition">
                                    <i class="fas fa-file-pdf mr-2"></i>PDF
                                </button>
                                <button onclick="downloadSectorExcel('PMDN')"
                                    class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg flex items-center transition">
                                    <i class="fas fa-file-excel mr-2"></i>Excel
                                </button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <input type="text" id="search-pmdn" placeholder="Cari perusahaan PMDN..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500">
                        </div>

                        <div class="overflow-x-auto" style="max-height: 600px; overflow-y: auto;">
                            <table class="min-w-full bg-white border rounded-lg">
                                <thead class="bg-orange-50 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 border-b">
                                            Nama Perusahaan</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 border-b">
                                            Tambahan Realisasi</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah TKA</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah TKI</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 border-b">
                                            Jumlah Proyek</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200" id="pmdn-table-body">
                                    <?php if (!empty($data['sector_count_by_company']['PMDN']['data'])): ?>
                                        <?php foreach ($data['sector_count_by_company']['PMDN']['data'] as $row): ?>
                                            <tr class="hover:bg-orange-50 transition-colors pmdn-row">
                                                <td class="px-4 py-3 text-sm text-gray-800 font-medium">
                                                    <?= esc($row['nama_perusahaan']) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-right font-bold text-orange-600">
                                                    <?= number_format($row['tambahan_realisasi'] ?? 0, 0, ',', '.') ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_tka'] ?? 0) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_tki'] ?? 0) ?>
                                                </td>
                                                <td class="px-4 py-3 text-sm text-center">
                                                    <?= number_format($row['jumlah_proyek'] ?? 0) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada data PMDN
                                                yang tersedia</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4 bg-orange-50 rounded-lg p-4 border border-orange-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Total Record:</span>
                                <span class="text-lg font-bold text-orange-600"
                                    id="pmdn-count"><?= count($data['sector_count_by_company']['PMDN']['data'] ?? []) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search JS -->
                <script>
                    document.getElementById('search-pma').addEventListener('keyup', function () {
                        const searchValue = this.value.toLowerCase();
                        const rows = document.querySelectorAll('.pma-row');
                        let visibleCount = 0;
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchValue) ? '' : 'none';
                            if (text.includes(searchValue)) visibleCount++;
                        });
                        document.getElementById('pma-count').textContent = visibleCount;
                    });

                    document.getElementById('search-pmdn').addEventListener('keyup', function () {
                        const searchValue = this.value.toLowerCase();
                        const rows = document.querySelectorAll('.pmdn-row');
                        let visibleCount = 0;
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchValue) ? '' : 'none';
                            if (text.includes(searchValue)) visibleCount++;
                        });
                        document.getElementById('pmdn-count').textContent = visibleCount;
                    });
                </script>



                <!-- ADDITIONAL INVESTMENT PERCENTAGES -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4 mb-4">
                    <div class="glass-card shadow-xl rounded-xl p-4 chart-container">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i
                                    class="fas fa-percentage mr-3 text-purple-600"></i><?= lang('Dashboard.additional_pma_investment_district') ?>
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.district') ?>
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.percentage') ?>
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.investment_amount') ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($data['additional_investment_percentages']['PMA'])): ?>
                                        <?php
                                        $additional_investment_percentages_pma = $data['additional_investment_percentages']['PMA'];
                                        uasort($additional_investment_percentages_pma, function ($a, $b) {
                                            return $b['percentage'] <=> $a['percentage'];
                                        });
                                        ?>
                                        <?php foreach ($additional_investment_percentages_pma as $district => $info): ?>
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($district); ?>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($info['percentage'], 1); ?>%
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($info['amount'], 0, ',', '.'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Total
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">100.0%</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo number_format(array_sum(array_column($additional_investment_percentages_pma, 'amount')), 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">
                                                <?= lang('Dashboard.no_data') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="glass-card shadow-xl rounded-xl p-4 chart-container">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                                <i
                                    class="fas fa-percentage mr-3 text-orange-600"></i><?= lang('Dashboard.additional_pmdn_investment_district') ?>
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-50">
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.district') ?>
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.percentage') ?>
                                        </th>
                                        <th
                                            class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= lang('Dashboard.investment_amount') ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (!empty($data['additional_investment_percentages']['PMDN'])): ?>
                                        <?php
                                        $additional_investment_percentages_pmdn = $data['additional_investment_percentages']['PMDN'];
                                        uasort($additional_investment_percentages_pmdn, function ($a, $b) {
                                            return $b['percentage'] <=> $a['percentage'];
                                        });
                                        ?>
                                        <?php foreach ($additional_investment_percentages_pmdn as $district => $info): ?>
                                            <tr>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($district); ?>
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($info['percentage'], 1); ?>%
                                                </td>
                                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                    <?php echo number_format($info['amount'], 0, ',', '.'); ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <tr class="bg-gray-100 font-bold">
                                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-900">Total
                                            </td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">100.0%</td>
                                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                                <?php echo number_format(array_sum(array_column($additional_investment_percentages_pmdn, 'amount')), 0, ',', '.'); ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="px-4 py-2 text-center text-sm text-gray-500">
                                                <?= lang('Dashboard.no_data') ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- DOWNLOAD DATA -->
                <!-- <div class="glass-card shadow-xl rounded-xl p-6 chart-container">
                    <div class="flex items-center mb-4">
                        <i class="fas fa-download text-green-600 mr-3 text-xl"></i>
                        <h2 class="text-xl font-semibold text-gray-800"><?= lang('Dashboard.download_analysis_results') ?></h2>
                    </div>
                    <a href="/dashboard/download"
                        class="inline-flex items-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-lg transition-all transform hover:scale-105 shadow-lg">
                        <i class="fas fa-file-excel mr-2"></i><?= lang('Dashboard.download_excel') ?>
                    </a>
                </div> -->
            </div>
        </div>
    </div>
    <script>
        const data = <?= json_encode($data, JSON_HEX_TAG) ?>;
        const currentFilters = <?= json_encode($data['filters'] ?? []) ?>;
        const APP_CONFIG = {
            baseUrl: '<?= base_url() ?>',
            currentUrl: '<?= current_url() ?>'
        };



        function downloadSectorPDF(type) {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            // Get data
            const sectorData = data.sector_count_by_company[type].data;

            if (!sectorData || sectorData.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data',
                    text: `Tidak ada data ${type} untuk diunduh.`
                });
                return;
            }

            // Calculate totals
            let totalTambahanRealisasi = 0;
            let totalTKA = 0;
            let totalTKI = 0;
            let totalProyek = 0;

            sectorData.forEach(row => {
                totalTambahanRealisasi += row.tambahan_realisasi || 0;
                totalTKA += row.jumlah_tka || 0;
                totalTKI += row.jumlah_tki || 0;
                totalProyek += row.jumlah_proyek || 0;
            });

            // Header
            const today = new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Title
            doc.setFontSize(16);
            doc.setFont(undefined, 'bold');
            doc.text('DPMPTSP Kabupaten Tanah Bumbu', 105, 15, {
                align: 'center'
            });

            doc.setFontSize(14);
            doc.text(`Laporan LKPM - ${type}`, 105, 23, {
                align: 'center'
            });

            doc.setFontSize(12);
            doc.text('Data Perusahaan', 105, 30, {
                align: 'center'
            });

            // Info
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            doc.text(`Tanggal: ${today}`, 14, 40);
            doc.text(`Total Record: ${sectorData.length}`, 14, 45);

            // Table data
            const tableData = sectorData.map((row, index) => [
                index + 1,
                row.nama_perusahaan,
                row.tambahan_realisasi ? row.tambahan_realisasi.toLocaleString('id-ID') : '0',
                row.jumlah_tka ? row.jumlah_tka.toLocaleString('id-ID') : '0',
                row.jumlah_tki ? row.jumlah_tki.toLocaleString('id-ID') : '0',
                row.jumlah_proyek ? row.jumlah_proyek.toLocaleString('id-ID') : '0'
            ]);

            // Add total row
            tableData.push([
                '',
                {
                    content: 'TOTAL',
                    styles: {
                        fontStyle: 'bold',
                        halign: 'right'
                    }
                },
                {
                    content: totalTambahanRealisasi.toLocaleString('id-ID'),
                    styles: {
                        fontStyle: 'bold',
                        halign: 'right'
                    }
                },
                {
                    content: totalTKA.toLocaleString('id-ID'),
                    styles: {
                        fontStyle: 'bold',
                        halign: 'center'
                    }
                },
                {
                    content: totalTKI.toLocaleString('id-ID'),
                    styles: {
                        fontStyle: 'bold',
                        halign: 'center'
                    }
                },
                {
                    content: totalProyek.toLocaleString('id-ID'),
                    styles: {
                        fontStyle: 'bold',
                        halign: 'center'
                    }
                }
            ]);

            // Generate table
            doc.autoTable({
                startY: 50,
                head: [
                    ['No', 'Nama Perusahaan', 'Tambahan Realisasi', 'Jumlah TKA', 'Jumlah TKI', 'Jumlah Proyek']
                ],
                body: tableData,
                theme: 'grid',
                headStyles: {
                    fillColor: type === 'PMA' ? [37, 99, 235] : [249, 115, 22],
                    textColor: 255,
                    fontStyle: 'bold',
                    halign: 'center',
                    fontSize: 8
                },
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                    overflow: 'linebreak',
                    cellWidth: 'wrap'
                },
                columnStyles: {
                    0: {
                        cellWidth: 10,
                        halign: 'center'
                    },
                    1: {
                        cellWidth: 50
                    },
                    2: {
                        cellWidth: 30,
                        halign: 'right'
                    },
                    3: {
                        cellWidth: 20,
                        halign: 'center'
                    },
                    4: {
                        cellWidth: 20,
                        halign: 'center'
                    },
                    5: {
                        cellWidth: 20,
                        halign: 'center'
                    }
                },
                didDrawPage: function (data) {
                    // Footer
                    const pageCount = doc.internal.getNumberOfPages();
                    const pageSize = doc.internal.pageSize;
                    const pageHeight = pageSize.height ? pageSize.height : pageSize.getHeight();

                    doc.setFontSize(8);
                    doc.text(
                        `Halaman ${data.pageNumber} dari ${pageCount}`,
                        pageSize.width / 2,
                        pageHeight - 10, {
                        align: 'center'
                    }
                    );
                }
            });

            // Save
            const filename = `LKPM_${type}_${new Date().getTime()}.pdf`;
            doc.save(filename);

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `File PDF ${type} berhasil diunduh.`,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Function untuk download Excel
        function downloadSectorExcel(type) {
            const sectorData = data.sector_count_by_company[type].data;

            if (!sectorData || sectorData.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Data',
                    text: `Tidak ada data ${type} untuk diunduh.`
                });
                return;
            }

            // Calculate totals
            let totalTambahanRealisasi = 0;
            let totalTKA = 0;
            let totalTKI = 0;
            let totalProyek = 0;

            sectorData.forEach(row => {
                totalTambahanRealisasi += row.tambahan_realisasi || 0;
                totalTKA += row.jumlah_tka || 0;
                totalTKI += row.jumlah_tki || 0;
                totalProyek += row.jumlah_proyek || 0;
            });

            // Prepare data for Excel
            const today = new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            // Create worksheet data
            const wsData = [
                ['DPMPTSP KABUPATEN TANAH BUMBU'],
                [`LAPORAN LKPM - ${type}`],
                ['Data Perusahaan'],
                [],
                [`Tanggal: ${today}`],
                [`Total Record: ${sectorData.length}`],
                [],
                ['No', 'Nama Perusahaan', 'Tambahan Realisasi', 'Jumlah TKA', 'Jumlah TKI', 'Jumlah Proyek']
            ];

            // Add data rows
            sectorData.forEach((row, index) => {
                wsData.push([
                    index + 1,
                    row.nama_perusahaan,
                    row.tambahan_realisasi || 0,
                    row.jumlah_tka || 0,
                    row.jumlah_tki || 0,
                    row.jumlah_proyek || 0
                ]);
            });

            // Add total row
            wsData.push([
                '',
                'TOTAL',
                totalTambahanRealisasi,
                totalTKA,
                totalTKI,
                totalProyek
            ]);

            // Create workbook
            const wb = XLSX.utils.book_new();
            const ws = XLSX.utils.aoa_to_sheet(wsData);

            // Set column widths
            ws['!cols'] = [{
                wch: 5
            }, // No
            {
                wch: 40
            }, // Nama Perusahaan
            {
                wch: 20
            }, // Tambahan Realisasi
            {
                wch: 15
            }, // Jumlah TKA
            {
                wch: 15
            }, // Jumlah TKI
            {
                wch: 15
            } // Jumlah Proyek
            ];

            // Merge cells for title
            ws['!merges'] = [{
                s: {
                    r: 0,
                    c: 0
                },
                e: {
                    r: 0,
                    c: 5
                }
            }, // Title row 1
            {
                s: {
                    r: 1,
                    c: 0
                },
                e: {
                    r: 1,
                    c: 5
                }
            }, // Title row 2
            {
                s: {
                    r: 2,
                    c: 0
                },
                e: {
                    r: 2,
                    c: 5
                }
            } // Title row 3
            ];

            // Style header row (bold, centered)
            const headerRow = 7; // Row index for header (0-based)
            ['A', 'B', 'C', 'D', 'E', 'F'].forEach(col => {
                const cell = ws[`${col}${headerRow + 1}`];
                if (cell) {
                    cell.s = {
                        font: {
                            bold: true
                        },
                        alignment: {
                            horizontal: 'center',
                            vertical: 'center'
                        },
                        fill: {
                            fgColor: {
                                rgb: type === 'PMA' ? '2563EB' : 'F97316'
                            }
                        }
                    };
                }
            });

            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(wb, ws, `LKPM ${type}`);

            // Save file
            const filename = `LKPM_${type}_${new Date().getTime()}.xlsx`;
            XLSX.writeFile(wb, filename);

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: `File Excel ${type} berhasil diunduh.`,
                timer: 2000,
                showConfirmButton: false
            });
        }

        // Function untuk download gabungan PMA & PMDN
        function downloadAllSectorPDF() {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF('p', 'mm', 'a4');

            const today = new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });

            let startY = 15;

            // Process both PMA and PMDN
            ['PMA', 'PMDN'].forEach((type, typeIndex) => {
                const sectorData = data.sector_count_by_company[type].data;

                if (sectorData && sectorData.length > 0) {
                    if (typeIndex > 0) {
                        doc.addPage();
                        startY = 15;
                    }

                    // Calculate totals
                    let totalTambahanRealisasi = 0;
                    let totalTKA = 0;
                    let totalTKI = 0;
                    let totalProyek = 0;

                    sectorData.forEach(row => {
                        totalTambahanRealisasi += row.tambahan_realisasi || 0;
                        totalTKA += row.jumlah_tka || 0;
                        totalTKI += row.jumlah_tki || 0;
                        totalProyek += row.jumlah_proyek || 0;
                    });

                    // Title
                    doc.setFontSize(16);
                    doc.setFont(undefined, 'bold');
                    doc.text('DPMPTSP Kabupaten Tanah Bumbu', 105, startY, {
                        align: 'center'
                    });

                    doc.setFontSize(14);
                    doc.text(`Laporan LKPM - ${type}`, 105, startY + 8, {
                        align: 'center'
                    });

                    doc.setFontSize(12);
                    doc.text('Data Perusahaan', 105, startY + 15, {
                        align: 'center'
                    });

                    // Info
                    doc.setFontSize(10);
                    doc.setFont(undefined, 'normal');
                    doc.text(`Tanggal: ${today}`, 14, startY + 25);
                    doc.text(`Total Record: ${sectorData.length}`, 14, startY + 30);

                    // Table data
                    const tableData = sectorData.map((row, index) => [
                        index + 1,
                        row.nama_perusahaan,
                        row.tambahan_realisasi ? row.tambahan_realisasi.toLocaleString('id-ID') : '0',
                        row.jumlah_tka ? row.jumlah_tka.toLocaleString('id-ID') : '0',
                        row.jumlah_tki ? row.jumlah_tki.toLocaleString('id-ID') : '0',
                        row.jumlah_proyek ? row.jumlah_proyek.toLocaleString('id-ID') : '0'
                    ]);

                    // Add total row
                    tableData.push([
                        '',
                        {
                            content: 'TOTAL',
                            styles: {
                                fontStyle: 'bold',
                                halign: 'right'
                            }
                        },
                        {
                            content: totalTambahanRealisasi.toLocaleString('id-ID'),
                            styles: {
                                fontStyle: 'bold',
                                halign: 'right'
                            }
                        },
                        {
                            content: totalTKA.toLocaleString('id-ID'),
                            styles: {
                                fontStyle: 'bold',
                                halign: 'center'
                            }
                        },
                        {
                            content: totalTKI.toLocaleString('id-ID'),
                            styles: {
                                fontStyle: 'bold',
                                halign: 'center'
                            }
                        },
                        {
                            content: totalProyek.toLocaleString('id-ID'),
                            styles: {
                                fontStyle: 'bold',
                                halign: 'center'
                            }
                        }
                    ]);

                    // Generate table
                    doc.autoTable({
                        startY: startY + 35,
                        head: [
                            ['No', 'Nama Perusahaan', 'Tambahan Realisasi', 'TKA', 'TKI', 'Proyek']
                        ],
                        body: tableData,
                        theme: 'grid',
                        headStyles: {
                            fillColor: type === 'PMA' ? [37, 99, 235] : [249, 115, 22],
                            textColor: 255,
                            fontStyle: 'bold',
                            halign: 'center',
                            fontSize: 8
                        },
                        styles: {
                            fontSize: 8,
                            cellPadding: 2
                        },
                        columnStyles: {
                            0: {
                                cellWidth: 10,
                                halign: 'center'
                            },
                            1: {
                                cellWidth: 50
                            },
                            2: {
                                cellWidth: 30,
                                halign: 'right'
                            },
                            3: {
                                cellWidth: 20,
                                halign: 'center'
                            },
                            4: {
                                cellWidth: 20,
                                halign: 'center'
                            },
                            5: {
                                cellWidth: 20,
                                halign: 'center'
                            }
                        }
                    });
                }
            });

            // Save
            const filename = `LKPM_Lengkap_${new Date().getTime()}.pdf`;
            doc.save(filename);

            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'File PDF lengkap (PMA & PMDN) berhasil diunduh.',
                timer: 2000,
                showConfirmButton: false
            });



        }












        // Dashboard JavaScript - Extracted from dashboard.php



    </script>
    <script src="<?= base_url('assets/js/dashboard.js') ?>"></script>
    <script src="<?= base_url('assets/js/charts.js') ?>"></script>

    <!-- Show flashdata messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('success') ?>',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        </script>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonText: 'OK'
            });
        </script>
    <?php endif; ?>

    <footer class="bg-gray-800 border-t border-gray-700 shadow-inner mt-12">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-center items-center text-sm text-white/80">
            © <?= date('Y') ?> DPMPTSP - Kabupaten Tanah Bumbu
        </div>
    </footer>




</body>

</html>
