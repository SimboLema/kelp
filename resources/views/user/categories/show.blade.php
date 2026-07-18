<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>best {{ $category->name }} near you — kelp</title>
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
            font-size: 16px; /* Base size increase */
        }

        /* ━━━━━━━━━━━━━━━━━━ NAV ━━━━━━━━━━━━━━━━━━ */
        .site-nav {
            position: sticky;
            top: 0; z-index: 300;
            background: var(--white);
            border-bottom: 1px solid var(--cream-mid);
        }

        .nav-inner {
            max-width: 1400px; margin: 0 auto;
            padding: 0 2rem;
            height: 68px;
            display: flex; align-items: center; justify-content: space-between; gap: 2rem;
        }

        .nav-logo img { height: 56px; width: auto; object-fit: contain; }

        .nav-search {
            display: flex; align-items: center;
            background: var(--cream);
            border: 1px solid var(--cream-mid);
            border-radius: 100px;
            overflow: hidden;
            height: 40px;
            flex: 1; max-width: 520px;
        }

        .nav-search-field-wrap {
            flex: 1 1 0; min-width: 0;
            display: flex; align-items: center;
            padding: 0 0.9rem; gap: 0.5rem; overflow: hidden;
        }

        .nav-search-field-wrap + .nav-search-field-wrap {
            border-left: 1px solid var(--cream-mid);
        }

        .nav-search-field-wrap svg { flex-shrink: 0; color: var(--muted); }

        .nav-search input {
            flex: 1; min-width: 0; width: 0;
            background: transparent; border: none; outline: none;
            font-family: 'Outfit', sans-serif;
            font-size: 14px; font-weight: 400; color: var(--ink); /* Increased from 12px */
        }

        .nav-search input::placeholder { color: var(--stone); font-style: italic; }

        .nav-search-btn {
            flex-shrink: 0;
            width: 36px; height: 36px;
            background: var(--orange); border: none; border-radius: 100px;
            display: flex; align-items: center; justify-content: center;
            margin: 2px; cursor: pointer; transition: background 0.2s;
        }

        .nav-search-btn:hover { background: var(--orange-deep); }
        .nav-search-btn svg { color: #fff; }

        .nav-back {
            display: flex; align-items: center; gap: 0.5rem;
            font-family: 'Space Mono', monospace;
            font-size: 11px; letter-spacing: 0.18em; text-transform: uppercase; /* Increased from 9px */
            color: var(--muted); text-decoration: none; transition: color 0.2s;
            flex-shrink: 0;
        }
        .nav-back:hover { color: var(--orange); }

        /* ━━━━━━━━━━━━━━━━━━ LAYOUT SHELL ━━━━━━━━━━━━━━━━━━ */
        .page-shell {
            display: grid;
            grid-template-columns: 1fr 42%;
            height: calc(100vh - 68px);
            overflow: hidden;
        }

        .listing-col {
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: var(--cream-mid) transparent;
            border-right: 1px solid var(--cream-mid);
        }

        .listing-col::-webkit-scrollbar { width: 4px; }
        .listing-col::-webkit-scrollbar-track { background: transparent; }
        .listing-col::-webkit-scrollbar-thumb { background: var(--cream-mid); border-radius: 4px; }

        .listing-header {
            padding: 2.5rem 2.5rem 0;
            position: relative;
        }

        .listing-breadcrumb {
            display: flex; align-items: center; gap: 0.5rem;
            margin-bottom: 1.25rem;
        }

        .breadcrumb-item {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; /* Increased from 9px */
            color: var(--muted); text-decoration: none; transition: color 0.2s;
        }

        .breadcrumb-item:hover { color: var(--orange); }

        .breadcrumb-sep { color: var(--stone); font-size: 12px; }

        .breadcrumb-current {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; /* Increased from 9px */
            color: var(--orange);
        }

        .listing-title-row {
            display: flex; align-items: flex-end; justify-content: space-between;
            gap: 1rem; padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--cream-mid);
            margin-bottom: 0;
        }

        .listing-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.2rem, 4.5vw, 3.5rem); /* Increased scale */
            font-weight: 700; font-style: italic;
            line-height: 0.95; color: var(--ink);
        }

        .listing-title span { color: var(--orange); }

        .listing-count {
            font-family: 'Space Mono', monospace;
            font-size: 11px; letter-spacing: 0.2em; text-transform: uppercase; /* Increased from 9px */
            color: var(--muted); white-space: nowrap; flex-shrink: 0;
            padding-bottom: 4px;
        }

        /* Sort/filter bar */
        .filter-bar {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 2.5rem;
            border-bottom: 1px solid var(--cream-mid);
            overflow-x: auto;
            scrollbar-width: none;
        }
        .filter-bar::-webkit-scrollbar { display: none; }

        .filter-label {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; /* Increased from 8.5px */
            color: var(--muted); white-space: nowrap; margin-right: 0.25rem;
        }

        .filter-pill {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.1em; text-transform: uppercase; /* Increased from 8.5px */
            color: var(--muted);
            border: 1px solid var(--cream-mid);
            padding: 6px 16px; border-radius: 100px;
            cursor: pointer; white-space: nowrap;
            transition: all 0.2s; background: transparent;
        }

        .filter-pill:hover, .filter-pill.active {
            color: var(--orange); border-color: var(--orange);
            background: var(--orange-pale);
        }

        /* Business list */
        .business-list { padding: 0 2.5rem 3rem; }

        .biz-card {
            display: grid;
            grid-template-columns: 3rem 1fr auto; /* widened rank col for larger font */
            gap: 0 1.5rem;
            padding: 1.75rem 0;
            border-bottom: 1px solid var(--cream-mid);
            position: relative;
            transition: background 0.25s;
            align-items: start;
            text-decoration: none;
            color: inherit;
        }

        .biz-card::before {
            content: '';
            position: absolute;
            left: -2.5rem; right: -2.5rem; top: 0; bottom: 0;
            background: transparent;
            transition: background 0.25s;
            z-index: 0;
            pointer-events: none;
        }

        .biz-card:hover::before { background: var(--orange-pale); }

        .biz-card > * { position: relative; z-index: 1; }

        .biz-rank {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.5rem; font-weight: 700; font-style: italic; /* Increased from 2.25rem */
            color: var(--cream-mid);
            line-height: 1;
            transition: color 0.25s;
            padding-top: 2px;
            user-select: none;
        }

        .biz-card:hover .biz-rank { color: var(--orange); }

        .biz-body { display: flex; flex-direction: column; gap: 0.5rem; }

        .biz-name-link {
            display: block; text-decoration: none;
        }

        .biz-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem; font-weight: 700; font-style: italic; /* Increased from 1.4rem */
            color: var(--ink); line-height: 1.1;
            transition: color 0.2s;
        }

        .biz-card:hover .biz-name { color: var(--orange); }

        .biz-meta {
            display: flex; align-items: center; gap: 0.75rem; flex-wrap: wrap;
        }

        .biz-stars { display: flex; gap: 2px; }
        .biz-star { font-size: 13px; } /* Increased from 11px */
        .biz-star-on  { color: var(--orange); }
        .biz-star-off { color: var(--stone); }

        .biz-review-count {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; /* Increased from 8.5px */
            color: var(--muted);
        }

        .biz-dot { width: 3px; height: 3px; background: var(--stone); border-radius: 50%; }

        .biz-address {
            font-size: 13px; color: var(--muted); font-style: italic; font-weight: 400; /* Increased from 11.5px */
        }

        .biz-description {
            font-size: 14px; color: var(--muted); line-height: 1.65; /* Increased from 12px */
            font-style: italic; font-weight: 300;
            display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .biz-actions {
            display: flex; gap: 0.5rem; margin-top: 0.25rem; flex-wrap: wrap;
        }

        .biz-action-btn {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase; /* Increased from 8px */
            color: var(--muted);
            border: 1px solid var(--cream-mid);
            padding: 6px 14px; border-radius: 100px;
            cursor: pointer; background: transparent;
            transition: all 0.2s;
        }

        .biz-action-btn:hover {
            color: var(--orange); border-color: var(--orange);
        }

        .biz-action-btn.primary {
            background: var(--ink); color: #fff; border-color: var(--ink);
        }

        .biz-action-btn.primary:hover {
            background: var(--orange); border-color: var(--orange);
        }

        .biz-thumb-wrap {
            width: 130px; height: 100px; /* Scaled thumb slightly to match text */
            border-radius: 4px; overflow: hidden;
            flex-shrink: 0; align-self: start;
            position: relative;
        }

        .biz-thumb {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center;
            transition: transform 0.6s cubic-bezier(0.25,0.46,0.45,0.94);
        }

        .biz-card:hover .biz-thumb { transform: scale(1.08); }

        .empty-state {
            padding: 5rem 2.5rem;
            text-align: center;
        }

        .empty-state-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 6rem; font-weight: 700; font-style: italic;
            color: var(--cream-mid); line-height: 1;
            margin-bottom: 1rem;
        }

        .empty-state-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.75rem; font-style: italic; color: var(--muted); /* Increased from 1.5rem */
        }

        /* ━━━━━━━━━━━━━━━━━━ RIGHT: MAP ━━━━━━━━━━━━━━━━━━ */
        .map-col {
            position: relative;
            overflow: hidden;
            background: var(--ink-mid);
        }

        .map-header {
            position: absolute;
            top: 0; left: 0; right: 0;
            z-index: 10;
            padding: 1.75rem 2rem 3.5rem;
            background: linear-gradient(to bottom,
                rgba(16,14,11,0.95) 0%,
                rgba(16,14,11,0.7) 60%,
                transparent 100%);
            pointer-events: none;
        }

        .map-header-eyebrow {
            display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem;
        }

        .map-header-dot {
            width: 6px; height: 6px; background: var(--orange);
            border-radius: 50%; animation: pulse 2.2s ease infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.65); }
        }

        .map-header-label {
            font-family: 'Space Mono', monospace;
            font-size: 11px; letter-spacing: 0.28em; text-transform: uppercase; /* Increased from 9px */
            color: var(--orange);
        }

        .map-header-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.25rem; font-weight: 700; font-style: italic; /* Increased from 2rem */
            color: #fff; line-height: 1;
        }

        .map-header-title span { color: var(--orange); }

        .map-footer {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            z-index: 10;
            padding: 3.5rem 2rem 1.5rem;
            background: linear-gradient(to top,
                rgba(16,14,11,0.9) 0%,
                rgba(16,14,11,0.5) 60%,
                transparent 100%);
            display: flex; align-items: center; justify-content: space-between;
            pointer-events: none;
        }

        .map-footer-stat {
            display: flex; flex-direction: column;
        }

        .map-footer-num {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700; color: #fff; line-height: 1; /* Increased from 1.75rem */
        }

        .map-footer-label {
            font-family: 'Space Mono', monospace;
            font-size: 10px; letter-spacing: 0.2em; text-transform: uppercase; /* Increased from 8px */
            color: var(--orange); margin-top: 3px;
        }

        .map-iframe {
            position: absolute;
            inset: 0;
            width: 100%; height: 100%;
            border: none;
            display: block;
        }

        /* ━━━━━━━━━━━━━━━━━━ RESPONSIVE ━━━━━━━━━━━━━━━━━━ */
        @media (max-width: 960px) {
            .page-shell {
                grid-template-columns: 1fr;
                height: auto;
                overflow: visible;
            }
            .listing-col {
                overflow-y: visible;
                height: auto;
                border-right: none;
            }
            .map-col {
                height: 55vw; min-height: 320px; max-height: 480px;
                position: relative;
            }
            .map-iframe { position: absolute; inset: 0; }
        }

        @media (max-width: 640px) {
            .listing-header { padding: 1.5rem 1.25rem 0; }
            .filter-bar { padding: 0.75rem 1.25rem; }
            .business-list { padding: 0 1.25rem 2.5rem; }
            .biz-card { grid-template-columns: 2.5rem 1fr; }
            .biz-thumb-wrap { display: none; }
            .nav-inner { padding: 0 1rem; }
            .nav-search { display: none; }
        }
    </style>
</head>
<body>

    <!-- ━━━ NAV ━━━ -->
    <nav class="site-nav">
        <div class="nav-inner">
            <a href="/" class="nav-logo">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Kelp">
            </a>

            <div class="nav-search">
                <div class="nav-search-field-wrap">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                    </svg>
                    <input type="text" placeholder="tacos, spas, plumbers…">
                </div>
                <div class="nav-search-field-wrap">
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

            <a href="/" class="nav-back">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M19 12H5M12 5l-7 7 7 7"/>
                </svg>
                Back
            </a>
        </div>
    </nav>

    <!-- ━━━ MAIN SPLIT SHELL ━━━ -->
    <div class="page-shell">

        <!-- LEFT: Listing column -->
        <div class="listing-col">

            <!-- Header -->
            <div class="listing-header">
                <div class="listing-breadcrumb">
                    <a href="/" class="breadcrumb-item">Home</a>
                    <span class="breadcrumb-sep">›</span>
                    <span class="breadcrumb-current">{{ $category->name }}</span>
                </div>

                <div class="listing-title-row">
                    <h1 class="listing-title">
                        best <span>{{ $category->name }}</span><br>in town
                    </h1>
                    <span class="listing-count">{{ $category->businesses->count() }} results</span>
                </div>
            </div>

            <!-- Filter bar -->
            <div class="filter-bar">
                <span class="filter-label">Sort:</span>
                <button class="filter-pill active">Top rated</button>
                <button class="filter-pill">Most reviewed</button>
                <button class="filter-pill">Nearest</button>
                <button class="filter-pill">Open now</button>
            </div>

            <!-- Business list -->
            <div class="business-list">
                @forelse($category->businesses as $index => $business)
                    @php
                        $avgRating = round($business->reviews->avg('rating') ?? 0);
                        $thumbUrl = $business->logo
                            ? (Str::startsWith($business->logo, ['http://', 'https://']) ? $business->logo : asset('storage/' . $business->logo))
                            : 'https://via.placeholder.com/220x180/EDE9E2/C8C2B8?text=.';
                    @endphp

                    <div class="biz-card">
                        <!-- Rank -->
                        <div class="biz-rank">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>

                        <!-- Body -->
                        <div class="biz-body">
                            <a href="{{ route('details.show', $business->id) }}" class="biz-name-link">
                                <div class="biz-name">{{ $business->name }}</div>
                            </a>

                            <div class="biz-meta">
                                <div class="biz-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <span class="biz-star {{ $i <= $avgRating ? 'biz-star-on' : 'biz-star-off' }}">★</span>
                                    @endfor
                                </div>
                                <span class="biz-review-count">{{ $business->reviews->count() }} reviews</span>
                                <div class="biz-dot"></div>
                                <span class="biz-address">{{ $business->address }}, {{ $business->city }}</span>
                            </div>

                            <p class="biz-description">"{{ Str::limit($business->description, 140) }}"</p>

                            <div class="biz-actions">
                                <a href="{{ route('details.show', $business->id) }}" class="biz-action-btn primary">View details</a>
                                <button class="biz-action-btn">Helpful</button>
                                <button class="biz-action-btn">Share</button>
                            </div>
                        </div>

                        <!-- Thumbnail -->
                        <div class="biz-thumb-wrap">
                            <img
                                src="{{ $thumbUrl }}"
                                class="biz-thumb"
                                alt="{{ $business->name }}"
                            >
                        </div>
                    </div>

                @empty
                    <div class="empty-state">
                        <div class="empty-state-num">∅</div>
                        <p class="empty-state-text">no vibes found in this category.</p>
                    </div>
                @endforelse
            </div>

        </div><!-- /listing-col -->

        <!-- RIGHT: Map column -->
        <div class="map-col">

            <!-- Editorial overlay header -->
            <div class="map-header">
                <div class="map-header-eyebrow">
                    <div class="map-header-dot"></div>
                    <span class="map-header-label">Live map</span>
                </div>
                <div class="map-header-title">
                    {{ $category->name }}<br>
                    <span>near you</span>
                </div>
            </div>

            <!-- The map -->
            <iframe
                class="map-iframe"
                loading="lazy"
                allowfullscreen
                referrerpolicy="no-referrer-when-downgrade"
                src="https://www.google.com/maps/embed/v1/search?key=AIzaSyAc8RO50ZASiIh88EakmIZ3OuBWE--PMMc&q={{ urlencode($category->name . ' in Dar es Salaam') }}"
            ></iframe>

            <!-- Bottom stat strip over map -->
            <div class="map-footer">
                <div class="map-footer-stat">
                    <div class="map-footer-num">{{ $category->businesses->count() }}</div>
                    <div class="map-footer-label">Places found</div>
                </div>
                <div class="map-footer-stat" style="text-align:right;">
                    <div class="map-footer-num">Dar es Salaam</div>
                    <div class="map-footer-label">Current area</div>
                </div>
            </div>

        </div><!-- /map-col -->

    </div><!-- /page-shell -->

    <script>
        // Filter pill active state
        document.querySelectorAll('.filter-pill').forEach(pill => {
            pill.addEventListener('click', function () {
                document.querySelectorAll('.filter-pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
            });
        });
    </script>

</body>
</html>
