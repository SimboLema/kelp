<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $business->name }} — Kelp</title>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,600;1,700&family=Outfit:wght@300;400;500;600&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange:      #F26A2E;
            --orange-deep: #C8501B;
            --orange-pale: #FEF3EC;
            --ink:         #100E0B;
            --ink-mid:     #1E1B17;
            --ink-soft:    #2C2820;
            --cream:       #FAF7F2;
            --cream-mid:   #EDE9E2;
            --stone:       #C8C2B8;
            --muted:       #7A7368;
            --white:       #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--cream);
            font-family: 'Outfit', sans-serif;
            color: var(--ink);
            overflow-x: hidden;
        }

        [x-cloak] { display: none !important; }

        /* ━━━━━━━━━━━━━━ NAV ━━━━━━━━━━━━━━ */
        .site-nav {
            position: sticky; top: 0; z-index: 300;
            background: var(--white);
            border-bottom: 1px solid var(--cream-mid);
        }

        .nav-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 0 2rem; height: 68px;
            display: flex; align-items: center; justify-content: space-between; gap: 2rem;
        }

        .nav-logo img { height: 56px; width: auto; object-fit: contain; }

        .nav-search {
            display: flex; align-items: center;
            background: var(--cream);
            border: 1px solid var(--cream-mid);
            border-radius: 100px;
            overflow: hidden; height: 40px;
            flex: 1; max-width: 500px;
        }

        .nav-search-wrap {
            flex: 1 1 0; min-width: 0;
            display: flex; align-items: center;
            padding: 0 0.9rem; gap: 0.5rem; overflow: hidden;
        }

        .nav-search-wrap + .nav-search-wrap { border-left: 1px solid var(--cream-mid); }
        .nav-search-wrap svg { flex-shrink: 0; color: var(--muted); }

        .nav-search input {
            flex: 1; min-width: 0; width: 0;
            background: transparent; border: none; outline: none;
            font-family: 'Outfit', sans-serif;
            font-size: 12.5px; color: var(--ink);
        }

        .nav-search input::placeholder { color: var(--stone); font-style: italic; }

        .nav-search-btn {
            flex-shrink: 0; width: 36px; height: 36px;
            background: var(--orange); border: none; border-radius: 100px;
            display: flex; align-items: center; justify-content: center;
            margin: 2px; cursor: pointer; transition: background 0.2s;
        }
        .nav-search-btn:hover { background: var(--orange-deep); }
        .nav-search-btn svg { color: #fff; }

        .nav-back {
            display: flex; align-items: center; gap: 0.5rem;
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.18em; text-transform: uppercase;
            color: var(--muted); text-decoration: none; transition: color 0.2s; flex-shrink: 0;
        }
        .nav-back:hover { color: var(--orange); }

        /* ━━━━━━━━━━━━━━ HERO ━━━━━━━━━━━━━━ */
        .hero {
            position: relative;
            height: 62vh; min-height: 420px;
            overflow: hidden; background: var(--ink);
        }

        .hero-img {
            position: absolute; inset: 0;
            width: 100%; height: 100%;
            object-fit: cover; object-position: center;
            filter: brightness(0.55);
            animation: slowzoom 20s ease infinite alternate;
        }

        @keyframes slowzoom { from { transform: scale(1); } to { transform: scale(1.06); } }

        .hero-vignette {
            position: absolute; inset: 0;
            background:
                linear-gradient(to top, rgba(16,14,11,0.97) 0%, rgba(16,14,11,0.3) 55%, rgba(16,14,11,0.1) 100%),
                linear-gradient(to right, rgba(16,14,11,0.4) 0%, transparent 60%);
        }

        .hero-content {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 0 3rem 3rem;
            max-width: 1400px; margin: 0 auto;
        }

        /* breadcrumb */
        .hero-breadcrumb {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .hero-bc-item {
            font-family: 'Space Mono', monospace;
            font-size: 9.5px; letter-spacing: 0.2em; text-transform: uppercase;
            color: rgba(255,255,255,0.45); text-decoration: none; transition: color 0.2s;
        }
        .hero-bc-item:hover { color: var(--orange); }
        .hero-bc-sep { color: rgba(255,255,255,0.25); font-size: 11px; }
        .hero-bc-current {
            font-family: 'Space Mono', monospace;
            font-size: 9.5px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--orange);
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(3rem, 6vw, 6rem);
            font-weight: 700; font-style: italic;
            line-height: 0.9; color: #fff;
            letter-spacing: -0.02em;
            margin-bottom: 1.5rem;
        }

        .hero-meta {
            display: flex; flex-wrap: wrap; align-items: center; gap: 1.25rem;
        }

        .hero-stars { display: flex; gap: 3px; }
        .h-star { font-size: 18px; }
        .h-star-on  { color: var(--orange); }
        .h-star-off { color: rgba(255,255,255,0.2); }

        .hero-rating-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem; font-weight: 700; color: #fff;
        }

        .hero-review-count {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase;
            color: rgba(255,255,255,0.45);
        }

        .hero-badge {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--orange);
            border: 1px solid rgba(242,106,46,0.4);
            padding: 5px 14px;
        }

        /* Category chip */
        .hero-cat {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 5px 14px;
        }

        /* ━━━━━━━━━━━━━━ ACTION BAR ━━━━━━━━━━━━━━ */
        .action-bar {
            background: var(--white);
            border-bottom: 1px solid var(--cream-mid);
            position: sticky; top: 68px; z-index: 100;
        }

        .action-bar-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 0 3rem;
            height: 60px;
            display: flex; align-items: center; gap: 1rem;
        }

        .action-btn {
            display: inline-flex; align-items: center; gap: 0.6rem;
            font-family: 'Space Mono', monospace;
            font-size: 9.5px; letter-spacing: 0.14em; text-transform: uppercase;
            padding: 0.55rem 1.4rem;
            border-radius: 100px; cursor: pointer;
            transition: all 0.2s; border: 1px solid transparent;
            text-decoration: none;
        }

        .action-btn.primary {
            background: var(--orange); color: #fff; border-color: var(--orange);
        }
        .action-btn.primary:hover { background: var(--orange-deep); border-color: var(--orange-deep); }

        .action-btn.ghost {
            background: transparent; color: var(--muted); border-color: var(--cream-mid);
        }
        .action-btn.ghost:hover { color: var(--orange); border-color: var(--orange); }

        /* ━━━━━━━━━━━━━━ MAIN LAYOUT ━━━━━━━━━━━━━━ */
        .main-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 3.5rem 3rem;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 4rem;
            align-items: start;
        }

        /* ━━━━━━━━━━━━━━ REVIEWS SECTION ━━━━━━━━━━━━━━ */
        .reviews-section-title {
            display: flex; align-items: center; gap: 1rem;
            margin-bottom: 2.5rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid var(--cream-mid);
        }

        .rst-tag {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.22em; text-transform: uppercase;
            color: var(--orange); background: var(--orange-pale);
            padding: 4px 12px; border-radius: 100px;
        }

        .rst-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700; font-style: italic; color: var(--ink);
        }

        /* Review card */
        .review-list { display: flex; flex-direction: column; gap: 0; }

        .review-item {
            padding: 2.25rem 0;
            border-bottom: 1px solid var(--cream-mid);
            display: grid;
            grid-template-columns: 48px 1fr;
            gap: 1.5rem;
            position: relative;
        }

        .review-item::before {
            content: '';
            position: absolute; left: 0; top: 0;
            width: 2px; height: 0;
            background: var(--orange);
            transition: height 0.4s ease;
        }

        .review-item:hover::before { height: 100%; }

        /* Avatar */
        .review-avatar {
            width: 48px; height: 48px; border-radius: 50%;
            background: var(--ink);
            color: #fff;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem; font-weight: 700; font-style: italic;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; text-transform: uppercase;
            border: 2px solid var(--cream-mid);
            transition: border-color 0.3s;
        }

        .review-item:hover .review-avatar { border-color: var(--orange); }

        .review-body { display: flex; flex-direction: column; gap: 0.6rem; }

        .review-user-name {
            font-size: 15px; font-weight: 600; color: var(--ink);
        }

        .review-location {
            font-family: 'Space Mono', monospace;
            font-size: 8.5px; letter-spacing: 0.15em; text-transform: uppercase;
            color: var(--muted);
        }

        .review-meta-row {
            display: flex; align-items: center; gap: 1rem;
        }

        .review-stars { display: flex; gap: 2px; }
        .r-star { font-size: 13px; }
        .r-star-on  { color: var(--orange); }
        .r-star-off { color: var(--stone); }

        .review-date {
            font-family: 'Space Mono', monospace;
            font-size: 8.5px; letter-spacing: 0.1em; text-transform: uppercase;
            color: var(--muted);
        }

        .review-comment {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.2rem; font-weight: 600; font-style: italic;
            color: var(--ink-soft); line-height: 1.55;
        }

        /* Review images */
        .review-images {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
            margin-top: 0.5rem;
        }

        .review-img-wrap {
            aspect-ratio: 4/3;
            border-radius: 3px;
            overflow: hidden;
        }

        .review-img-wrap img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .review-img-wrap:hover img { transform: scale(1.06); }

        /* Empty state */
        .empty-reviews {
            padding: 4rem 0; text-align: center;
            border: 1px dashed var(--cream-mid);
        }

        .empty-reviews-icon {
            font-family: 'Cormorant Garamond', serif;
            font-size: 5rem; font-weight: 700; font-style: italic;
            color: var(--cream-mid); line-height: 1; margin-bottom: 0.75rem;
        }

        .empty-reviews-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem; font-style: italic; color: var(--muted);
        }

        /* ━━━━━━━━━━━━━━ SIDEBAR ━━━━━━━━━━━━━━ */
        .sidebar {
            position: sticky; top: calc(68px + 60px + 2rem);
            display: flex; flex-direction: column; gap: 0;
        }

        .sidebar-card {
            background: var(--white);
            border: 1px solid var(--cream-mid);
        }

        /* Rating summary block */
        .rating-summary {
            padding: 2rem;
            border-bottom: 1px solid var(--cream-mid);
        }

        .rating-big {
            font-family: 'Cormorant Garamond', serif;
            font-size: 4.5rem; font-weight: 700; font-style: italic;
            color: var(--ink); line-height: 1;
        }

        .rating-out-of {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--muted); margin-top: 3px;
        }

        .rating-stars-lg { display: flex; gap: 4px; margin: 0.75rem 0 0.25rem; }
        .rsl { font-size: 18px; }

        .rating-bar-list { margin-top: 1.25rem; display: flex; flex-direction: column; gap: 0.5rem; }

        .rating-bar-row {
            display: flex; align-items: center; gap: 0.6rem;
        }

        .rb-label {
            font-family: 'Space Mono', monospace;
            font-size: 8.5px; color: var(--muted); width: 14px; text-align: right; flex-shrink: 0;
        }

        .rb-track {
            flex: 1; height: 3px;
            background: var(--cream-mid); border-radius: 2px; overflow: hidden;
        }

        .rb-fill {
            height: 100%; background: var(--orange); border-radius: 2px;
            transition: width 0.6s ease;
        }

        /* Info block */
        .info-block {
            padding: 1.75rem 2rem;
            border-bottom: 1px solid var(--cream-mid);
        }

        .info-block:last-child { border-bottom: none; }

        .info-label {
            font-family: 'Space Mono', monospace;
            font-size: 8.5px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--orange); margin-bottom: 0.5rem;
        }

        .info-value {
            font-size: 14.5px; font-weight: 500; color: var(--ink); line-height: 1.5;
        }

        .info-value a { color: var(--ink); text-decoration: none; }
        .info-value a:hover { color: var(--orange); }

        /* ━━━━━━━━━━━━━━ MODAL ━━━━━━━━━━━━━━ */
        .modal-backdrop {
            position: fixed; inset: 0; z-index: 500;
            background: rgba(16,14,11,0.82);
            backdrop-filter: blur(6px);
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }

        .modal-box {
            background: var(--cream);
            width: 100%; max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--cream-mid) transparent;
            position: relative;
            border-top: 3px solid var(--orange);
        }

        .modal-box::-webkit-scrollbar { width: 4px; }
        .modal-box::-webkit-scrollbar-thumb { background: var(--cream-mid); }

        .modal-header {
            padding: 2.5rem 2.5rem 1.5rem;
            border-bottom: 1px solid var(--cream-mid);
            position: relative;
        }

        .modal-eyebrow {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.25em; text-transform: uppercase;
            color: var(--orange); margin-bottom: 0.5rem;
        }

        .modal-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700; font-style: italic;
            color: var(--ink); line-height: 1;
        }

        .modal-sub {
            font-size: 12px; color: var(--muted); margin-top: 0.4rem; font-style: italic;
        }

        .modal-close {
            position: absolute; top: 2rem; right: 2rem;
            background: transparent; border: none; cursor: pointer;
            color: var(--stone); transition: color 0.2s;
            display: flex; align-items: center; justify-content: center;
        }

        .modal-close:hover { color: var(--ink); }

        .modal-body { padding: 2rem 2.5rem 2.5rem; }

        /* Form fields */
        .field-group { margin-bottom: 1.75rem; }

        .field-label {
            display: block;
            font-family: 'Space Mono', monospace;
            font-size: 8.5px; letter-spacing: 0.2em; text-transform: uppercase;
            color: var(--muted); margin-bottom: 0.6rem;
        }

        .field-input {
            width: 100%; background: var(--white);
            border: 1px solid var(--cream-mid);
            padding: 0.85rem 1rem;
            font-family: 'Outfit', sans-serif;
            font-size: 14px; color: var(--ink); outline: none;
            transition: border-color 0.2s;
            border-radius: 0;
            -webkit-appearance: none;
        }

        .field-input:focus { border-color: var(--orange); }
        .field-input::placeholder { color: var(--stone); font-style: italic; }

        /* Star rating picker */
        .star-picker { display: flex; gap: 0.4rem; }

        .star-btn {
            width: 44px; height: 44px;
            background: var(--white);
            border: 1px solid var(--cream-mid);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.15s;
            font-size: 22px; border-radius: 0;
        }

        .star-btn.lit {
            background: var(--orange-pale);
            border-color: var(--orange);
            color: var(--orange);
        }

        /* File upload zone */
        .upload-zone {
            border: 1px dashed var(--cream-mid);
            padding: 1.5rem;
            background: var(--white);
            transition: border-color 0.2s;
            text-align: center;
        }

        .upload-zone:hover { border-color: var(--orange); }

        .upload-zone input[type="file"] {
            display: block; width: 100%;
            font-size: 12px; color: var(--muted);
            font-family: 'Outfit', sans-serif;
        }

        .upload-zone input[type="file"]::file-selector-button {
            font-family: 'Space Mono', monospace;
            font-size: 9px; letter-spacing: 0.12em; text-transform: uppercase;
            background: var(--ink); color: #fff;
            border: none; padding: 8px 18px;
            cursor: pointer; margin-right: 1rem;
            transition: background 0.2s;
        }

        .upload-zone input[type="file"]::file-selector-button:hover {
            background: var(--orange);
        }

        .upload-hint {
            font-size: 11px; color: var(--muted); margin-top: 0.6rem;
            font-style: italic;
        }

        /* Submit */
        .modal-submit {
            width: 100%; padding: 1rem;
            background: var(--orange); color: #fff; border: none; cursor: pointer;
            font-family: 'Space Mono', monospace;
            font-size: 10.5px; letter-spacing: 0.2em; text-transform: uppercase;
            transition: background 0.2s;
        }

        .modal-submit:hover { background: var(--orange-deep); }

        /* ━━━━━━━━━━━━━━ RESPONSIVE ━━━━━━━━━━━━━━ */
        @media (max-width: 960px) {
            .main-inner {
                grid-template-columns: 1fr;
                padding: 2rem 1.5rem;
            }
            .sidebar { position: static; top: auto; }
            .hero-content { padding: 0 1.5rem 2rem; }
            .hero-title { font-size: clamp(2.2rem, 8vw, 4rem); }
            .action-bar-inner { padding: 0 1.5rem; }
        }

        @media (max-width: 640px) {
            .nav-search { display: none; }
            .review-images { grid-template-columns: repeat(2, 1fr); }
            .modal-header, .modal-body { padding: 1.5rem; }
        }
    </style>
</head>
<body x-data="{ openReview: false }">

    <!-- ━━━ NAV ━━━ -->
    <nav class="site-nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Kelp">
            </a>

            <div class="nav-search">
                <div class="nav-search-wrap">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" placeholder="tacos, barbers, mechanics…">
                </div>
                <div class="nav-search-wrap">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <input type="text" placeholder="Dar es Salaam, TZ">
                </div>
                <button class="nav-search-btn" aria-label="Search">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                </button>
            </div>

            <a href="javascript:history.back()" class="nav-back">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Back
            </a>
        </div>
    </nav>

    <!-- ━━━ HERO ━━━ -->
    <div class="hero">
        <img
            src="{{ $business->logo ? asset('storage/' . $business->logo) : 'https://images.unsplash.com/photo-1517248135467-4c7ed9d42339?auto=format&fit=crop&w=1400' }}"
            class="hero-img"
            alt="{{ $business->name }}"
        >
        <div class="hero-vignette"></div>

        <div class="hero-content">
            <!-- Breadcrumb -->
            <div class="hero-breadcrumb">
                <a href="/" class="hero-bc-item">Home</a>
                <span class="hero-bc-sep">›</span>
                <a href="javascript:history.back()" class="hero-bc-item">{{ $business->category->name ?? 'Category' }}</a>
                <span class="hero-bc-sep">›</span>
                <span class="hero-bc-current">{{ $business->name }}</span>
            </div>

            <!-- Title -->
            <h1 class="hero-title">{{ $business->name }}</h1>

            <!-- Meta row -->
            @php $avgRating = round($business->reviews->avg('rating') ?? 0); @endphp
            <div class="hero-meta">
                <div class="hero-stars">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="h-star {{ $i <= $avgRating ? 'h-star-on' : 'h-star-off' }}">★</span>
                    @endfor
                </div>
                <span class="hero-rating-text">{{ number_format($business->reviews->avg('rating') ?? 0, 1) }}</span>
                <span class="hero-review-count">{{ $business->reviews->count() }} reviews</span>
                <span class="hero-badge">✓ Verified</span>
                @if($business->category)
                    <span class="hero-cat">{{ $business->category->name }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- ━━━ ACTION BAR ━━━ -->
    <div class="action-bar">
        <div class="action-bar-inner">
            <button @click="openReview = true" class="action-btn primary">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                </svg>
                Write a Review
            </button>
            <button class="action-btn ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect width="18" height="18" x="3" y="3" rx="1"/>
                    <circle cx="9" cy="9" r="2"/>
                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/>
                </svg>
                Add Photo
            </button>
            <button class="action-btn ghost">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/>
                    <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/>
                    <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/>
                </svg>
                Share
            </button>
        </div>
    </div>

    <!-- ━━━ MAIN CONTENT ━━━ -->
    <main>
        <div class="main-inner">

            <!-- Reviews column -->
            <div>
                <div class="reviews-section-title">
                    <span class="rst-tag">Reviews</span>
                    <h2 class="rst-title">What people say</h2>
                </div>

                <div class="review-list">
                    @forelse($business->reviews->sortByDesc('created_at') as $review)
                    <div class="review-item">
                        <div class="review-avatar">{{ Str::substr($review->user_name, 0, 1) }}</div>

                        <div class="review-body">
                            <div>
                                <div class="review-user-name">{{ $review->user_name }}</div>
                                <div class="review-location">Tanzania</div>
                            </div>

                            <div class="review-meta-row">
                                <div class="review-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="r-star {{ $i <= $review->rating ? 'r-star-on' : 'r-star-off' }}">★</span>
                                    @endfor
                                </div>
                                <span class="review-date">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>

                            <p class="review-comment">"{{ $review->comment }}"</p>

                            @if($review->images && $review->images->count())
                            <div class="review-images">
                                @foreach($review->images as $image)
                                <a href="{{ asset('storage/' . $image->image) }}" target="_blank" class="review-img-wrap">
                                    <img src="{{ asset('storage/' . $image->image) }}" alt="Review photo">
                                </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="empty-reviews">
                        <div class="empty-reviews-icon">∅</div>
                        <p class="empty-reviews-text">No reviews yet — be the first.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-card">

                    <!-- Rating summary -->
                    <div class="rating-summary">
                        <div class="rating-big">{{ number_format($business->reviews->avg('rating') ?? 0, 1) }}</div>
                        <div class="rating-out-of">out of 5.0</div>
                        <div class="rating-stars-lg">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="rsl {{ $i <= $avgRating ? 'r-star-on' : 'r-star-off' }}">★</span>
                            @endfor
                        </div>

                        <!-- Per-star bars -->
                        @php
                            $totalReviews = $business->reviews->count();
                        @endphp
                        <div class="rating-bar-list">
                            @for($s = 5; $s >= 1; $s--)
                                @php
                                    $cnt = $business->reviews->where('rating', $s)->count();
                                    $pct = $totalReviews > 0 ? ($cnt / $totalReviews * 100) : 0;
                                @endphp
                                <div class="rating-bar-row">
                                    <span class="rb-label">{{ $s }}</span>
                                    <div class="rb-track">
                                        <div class="rb-fill" style="width: {{ $pct }}%"></div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Business info -->
                    <div class="info-block">
                        <div class="info-label">Phone</div>
                        <div class="info-value">
                            <a href="tel:{{ $business->phone }}">{{ $business->phone ?? 'Not provided' }}</a>
                        </div>
                    </div>

                    <div class="info-block">
                        <div class="info-label">Address</div>
                        <div class="info-value">{{ $business->address ?? 'Location not provided' }}</div>
                    </div>

                    @if($business->city)
                    <div class="info-block">
                        <div class="info-label">City</div>
                        <div class="info-value">{{ $business->city }}</div>
                    </div>
                    @endif

                    @if($business->description)
                    <div class="info-block">
                        <div class="info-label">About</div>
                        <div class="info-value" style="font-size:13.5px; font-style:italic; color:var(--muted); line-height:1.6;">
                            {{ $business->description }}
                        </div>
                    </div>
                    @endif

                </div>
            </aside>

        </div>
    </main>

    <!-- ━━━ REVIEW MODAL ━━━ -->
    <div x-show="openReview" x-cloak class="modal-backdrop" @click.self="openReview = false">
        <div
            class="modal-box"
            x-show="openReview"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-data="{ hoverRating: 0, actualRating: 0 }"
        >
            <!-- Modal header -->
            <div class="modal-header">
                <div class="modal-eyebrow">Leave a review</div>
                <div class="modal-title">{{ $business->name }}</div>
                <div class="modal-sub">Reviewing as a guest · Your review helps others</div>
                <button type="button" @click="openReview = false" class="modal-close" aria-label="Close">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M18 6 6 18M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <div class="modal-body">
                <form action="{{ route('reviews.store', $business->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="rating" x-model="actualRating" required>

                    <!-- Name -->
                    <div class="field-group">
                        <label class="field-label">Your name</label>
                        <input type="text" name="user_name" required class="field-input" placeholder="What should we call you?">
                    </div>

                    <!-- Star rating -->
                    <div class="field-group">
                        <label class="field-label">Your rating</label>
                        <div class="star-picker">
                            <template x-for="i in 5" :key="i">
                                <button
                                    type="button"
                                    @mouseenter="hoverRating = i"
                                    @mouseleave="hoverRating = 0"
                                    @click="actualRating = i"
                                    :class="(hoverRating >= i || actualRating >= i) ? 'lit' : ''"
                                    class="star-btn"
                                >★</button>
                            </template>
                        </div>
                        <div style="height:0.5rem;"></div>
                        <span
                            x-show="actualRating > 0"
                            x-text="['','Poor','Fair','Good','Great','Excellent'][actualRating]"
                            style="font-family:'Space Mono',monospace;font-size:9px;letter-spacing:0.2em;text-transform:uppercase;color:var(--orange);"
                        ></span>
                    </div>

                    <!-- Comment -->
                    <div class="field-group">
                        <label class="field-label">Your experience</label>
                        <textarea name="comment" rows="5" required class="field-input" placeholder="Tell others what made it great (or not)…" style="resize:vertical;"></textarea>
                    </div>

                    <!-- Photos -->
                    <div class="field-group">
                        <label class="field-label">Add photos (optional)</label>
                        <div class="upload-zone">
                            <input type="file" name="images[]" multiple accept="image/*">
                            <p class="upload-hint">Up to 5 photos · jpg, png, webp</p>
                        </div>
                    </div>

                    <button type="submit" class="modal-submit">Post Review →</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
