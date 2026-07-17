<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelp | Dive into Local Reviews</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700;1,900&family=DM+Sans:wght@300;400;500&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --orange: #F26A2E;
            --orange-deep: #D4531A;
            --orange-pale: #FDF0E8;
            --ink: #0F0D0A;
            --warm-white: #FAF8F5;
            --warm-mid: #EDE9E3;
            --muted: #8A8278;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: var(--warm-white);
            font-family: 'DM Sans', sans-serif;
            color: var(--ink);
            overflow-x: hidden;
        }

        /* ── NAV ── */
      /* ── UPDATED NAV STYLING ── */
nav {
    position: fixed; /* Changed to fixed for a premium floating feel */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    background: #F26A2E; /* Semi-transparent Ink */
    backdrop-filter: blur(12px); /* Frosted glass effect */
    -webkit-backdrop-filter: blur(12px);
}

.nav-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 0 3.5rem;
    height: 90px; /* Slightly taller for airiness */
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Logo Styling */
.nav-logo-link {
    text-decoration: none;
    display: flex;
    align-items: center;
}

.nav-logo-img {
    height: 50px;
    width: auto;
    filter: brightness(0) invert(1); /* Forces logo to white if it's dark */
}

/* Grouping links for better balance */
.nav-main-links {
    display: flex;
    align-items: center;
    gap: 3rem;
}

.nav-link {
    color: rgba(255, 255, 255, 0.7);
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    text-decoration: none;
    transition: all 0.3s ease;
    font-family: 'DM Mono', monospace;
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -4px;
    left: 0;
    width: 0;
    height: 1px;
    background: var(--orange);
    transition: width 0.3s ease;
}

.nav-link:hover {
    color: #fff;
}

.nav-link:hover::after {
    width: 100%;
}

.nav-divider {
    width: 1px;
    height: 20px;
    background: rgba(255, 255, 255, 0.15);
    margin: 0 0.5rem;
}

/* Actions Group */
.nav-auth-actions {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.btn-login {
    color: #fff;
    font-size: 10px;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    background: none;
    border: none;
    cursor: pointer;
    transition: opacity 0.2s;
}

.btn-signup {
    padding: 0.7rem 1.8rem;
    background: var(--orange);
    color: #fff;
    font-size: 10px;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    border: none;
    cursor: pointer;
    clip-path: polygon(10% 0%, 100% 0%, 90% 100%, 0% 100%);
    transition: transform 0.3s ease, background 0.3s ease;
}

.btn-signup:hover {
    background: var(--orange-deep);
    transform: translateY(-2px);
}

        /* ── HERO ── */
        .hero {
            position: relative;
            height: 100vh;
            min-height: 640px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
        }

        /* Left editorial panel */
        /* Update these specific lines in your existing .hero-editorial class */
.hero-editorial {
    background: var(--ink);
    position: relative;
    display: flex;
    flex-direction: column;
    /* Changed from flex-end to center to pull the text up */
    justify-content: center;
    /* Added padding-top to push it slightly down from the dead center if needed,
       or use padding-bottom to create space at the bottom */
    padding: 3.5rem 3.5rem 8rem;
    z-index: 2;
}

        .hero-editorial::after {
            content: '';
            position: absolute;
            top: 0; right: -60px; bottom: 0;
            width: 120px;
            background: var(--ink);
            clip-path: polygon(0 0, 50% 0, 100% 100%, 0 100%);
            z-index: 1;
        }

        .hero-kicker {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .hero-kicker-line {
            width: 32px;
            height: 2px;
            background: var(--orange);
        }

        .hero-kicker-text {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--orange);
        }

        .hero-headline {
            font-family: 'Playfair Display', serif;
            font-size: clamp(4rem, 7vw, 7rem);
            font-weight: 900;
            font-style: italic;
            line-height: 0.92;
            color: #fff;
            margin-bottom: 1.75rem;
            letter-spacing: -0.02em;
            position: relative;
            z-index: 2;
        }

        .hero-headline span {
            display: block;
            color: var(--orange);
        }

        .hero-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.45);
            line-height: 1.6;
            max-width: 300px;
            margin-bottom: 2.5rem;
            font-weight: 300;
            position: relative;
            z-index: 2;
        }

        /* Search bar */
        .search-block {
            position: relative;
            z-index: 2;
        }

        .search-bar {
            display: flex;
            background: rgba(255,255,255,0.06);
            border: 1px solid rgba(255,255,255,0.12);
            border-radius: 0;
        }

        .search-field {
            flex: 1;
            padding: 0.9rem 1.25rem;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 12px;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            letter-spacing: 0.03em;
        }

        .search-field::placeholder {
            color: rgba(255,255,255,0.3);
            font-style: italic;
        }

        .search-sep {
            width: 1px;
            background: rgba(255,255,255,0.12);
            margin: 8px 0;
        }

        .search-btn {
            padding: 0 1.5rem;
            background: var(--orange);
            border: none;
            color: #fff;
            font-size: 10px;
            font-family: 'DM Mono', monospace;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            cursor: pointer;
            transition: background 0.2s;
        }

        .search-btn:hover { background: var(--orange-deep); }

        /* Issue number decoration */
        .hero-issue {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.2em;
            color: rgba(255,255,255,0.2);
            text-transform: uppercase;
            writing-mode: vertical-rl;
            transform: rotate(180deg);
        }

        /* Right image panel */
        .hero-image {
            position: relative;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            inset: 0;
            opacity: 0;
            transition: opacity 1.2s ease;
        }

        .slide.active { opacity: 1; }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            animation: zoom 18s ease infinite alternate;
        }

        @keyframes zoom {
            from { transform: scale(1); }
            to { transform: scale(1.1); }
        }

        .hero-image-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(15,13,10,0.3) 0%, transparent 40%);
            z-index: 1;
        }

        /* Floating stat badge */
        .hero-badge {
            position: absolute;
            bottom: 3rem;
            right: 2.5rem;
            z-index: 5;
            background: var(--orange);
            color: #fff;
            padding: 1.25rem 1.5rem;
            clip-path: polygon(0 0, calc(100% - 8px) 0, 100% 8px, 100% 100%, 8px 100%, 0 calc(100% - 8px));
        }

        .hero-badge-num {
            font-family: 'Playfair Display', serif;
            font-size: 2.25rem;
            font-weight: 900;
            line-height: 1;
        }

        .hero-badge-label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            opacity: 0.85;
            margin-top: 4px;
        }

        /* Scroll cue */
        .scroll-cue {
            position: absolute;
            bottom: 2rem;
            left: 3.5rem;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }

        .scroll-cue span {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
        }

        .scroll-line {
            width: 1px;
            height: 40px;
            background: linear-gradient(to bottom, var(--orange), transparent);
        }

        /* ── SECTION: RECENT ACTIVITIES ── */
        .section-recent {
            background: var(--warm-white);
            padding: 6rem 2.5rem;
        }

        .section-inner { max-width: 1300px; margin: 0 auto; }

        .section-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 3.5rem;
            border-bottom: 1px solid var(--warm-mid);
            padding-bottom: 1.5rem;
        }

        .section-label {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-label-tag {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: var(--orange);
            background: var(--orange-pale);
            padding: 4px 10px;
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            font-style: italic;
            color: var(--ink);
        }

        .section-count {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--muted);
            letter-spacing: 0.1em;
        }

        /* Review cards — editorial style */
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0;
            border: 1px solid var(--warm-mid);
        }

        .review-card {
            padding: 2.25rem;
            border-right: 1px solid var(--warm-mid);
            position: relative;
            transition: background 0.3s;
        }

        .review-card:last-child { border-right: none; }
        .review-card:hover { background: var(--orange-pale); }

        .review-card::before {
            content: '';
            position: absolute;
            top: 0; left: 2.25rem;
            width: 32px;
            height: 3px;
            background: var(--orange);
            transition: width 0.3s;
        }

        .review-card:hover::before { width: calc(100% - 4.5rem); }

        .review-meta {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        .review-avatar {
            width: 32px;
            height: 32px;
            background: var(--orange);
            color: #fff;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            text-transform: uppercase;
        }

        .review-user-name {
            font-size: 12px;
            font-weight: 500;
            color: var(--ink);
        }

        .review-time {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            color: var(--muted);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .review-biz-link {
            display: block;
            font-family: 'Playfair Display', serif;
            font-size: 1.15rem;
            font-weight: 700;
            font-style: italic;
            color: var(--ink);
            text-decoration: none;
            margin-bottom: 0.75rem;
            line-height: 1.2;
            transition: color 0.2s;
        }

        .review-biz-link:hover { color: var(--orange); }

        .stars {
            display: flex;
            gap: 2px;
            margin-bottom: 1rem;
        }

        .star { font-size: 12px; }
        .star-filled { color: var(--orange); }
        .star-empty { color: var(--warm-mid); }

        .review-quote {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.65;
            font-style: italic;
            font-weight: 300;
            border-left: 2px solid var(--warm-mid);
            padding-left: 1rem;
        }

        /* ── SECTION: CATEGORIES — magazine editorial grid ── */
        .section-categories {
            background: var(--ink);
            padding: 6rem 2.5rem;
        }

        .section-categories .section-title { color: #fff; }
        .section-categories .section-label-tag {
            background: rgba(242,106,46,0.2);
        }
        .section-categories .section-header {
            border-bottom-color: rgba(255,255,255,0.1);
        }
        .section-categories .section-count { color: rgba(255,255,255,0.3); }

        /* ── EQUAL BOX CATEGORY GRID ── */
.categories-masthead {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    gap: 1.5rem;
}

.cat-card {
    position: relative;
    aspect-ratio: 1 / 1; /* Makes boxes perfectly square */
    background: #1a1714;
    border: 1px solid rgba(255, 255, 255, 0.1);
    text-decoration: none;
    display: flex;
    align-items: flex-end;
    padding: 2rem;
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

/* Remove featured sizing to keep everything equal */
.cat-card:first-child, .cat-card:nth-child(2) {
    grid-column: auto;
    grid-row: auto;
    min-height: auto;
}

.cat-card:hover {
    background: var(--orange);
    border-color: var(--orange);
    transform: translateY(-8px);
}

.cat-content {
    position: relative;
    z-index: 2;
}

.cat-index {
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    color: var(--orange);
    margin-bottom: 0.5rem;
    transition: color 0.3s ease;
}

.cat-name {
    font-family: 'Playfair Display', serif;
    font-weight: 900;
    font-style: italic;
    color: white;
    font-size: 1.75rem;
    line-height: 1.1;
    letter-spacing: -0.02em;
}

.cat-arrow {
    margin-top: 1rem;
    display: flex;
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.5);
    opacity: 0;
    transform: translateX(-10px);
    transition: all 0.3s ease;
}

.cat-card:hover .cat-index,
.cat-card:hover .cat-arrow {
    color: white;
    opacity: 1;
    transform: translateX(0);
}

/* Responsive adjustments */
@media (max-width: 600px) {
    .categories-masthead {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    .cat-name { font-size: 1.2rem; }
}



  /* ── PREMIUM FOOTER ── */

.footer-editorial {
    background: #0b0907;
    position: relative;
    overflow: hidden;
}

.footer-accent {
    height: 4px;
    width: 100%;
    background: linear-gradient(
        to right,
        var(--orange),
        #ffb088,
        var(--orange)
    );
}

.footer-main {
    max-width: 1300px;
    margin: 0 auto;
    padding: 5rem 2.5rem;
    display: grid;
    grid-template-columns: 1.5fr 1fr 1fr 1.3fr;
    gap: 4rem;
}

.footer-logo {
    width: 110px;
    margin-bottom: 1.5rem;
}

.footer-description {
    color: rgba(255,255,255,0.45);
    font-size: 13px;
    line-height: 1.9;
    max-width: 320px;
    font-weight: 300;
}

.footer-heading {
    color: #fff;
    font-size: 11px;
    font-family: 'DM Mono', monospace;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    margin-bottom: 1.5rem;
}

.footer-column {
    display: flex;
    flex-direction: column;
    gap: 0.9rem;
}

.footer-link {
    color: rgba(255,255,255,0.55);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.25s ease;
    width: fit-content;
}

.footer-link:hover {
    color: var(--orange);
    transform: translateX(4px);
}

.footer-news-text {
    color: rgba(255,255,255,0.45);
    font-size: 13px;
    line-height: 1.7;
    margin-bottom: 1.2rem;
}

.footer-newsletter {
    display: flex;
    flex-direction: column;
    gap: 0.8rem;
}

.footer-input {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.08);
    padding: 0.95rem 1rem;
    color: white;
    outline: none;
    font-size: 13px;
}

.footer-input:focus {
    border-color: var(--orange);
}

.footer-btn {
    background: var(--orange);
    border: none;
    color: white;
    padding: 0.95rem 1rem;
    font-family: 'DM Mono', monospace;
    font-size: 10px;
    letter-spacing: 0.18em;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.25s;
}

.footer-btn:hover {
    background: var(--orange-deep);
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.06);
    padding: 1.5rem 2.5rem;
    max-width: 1300px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.footer-copy {
    font-family: 'DM Mono', monospace;
    font-size: 9px;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.25);
}

.footer-tagline {
    font-family: 'Playfair Display', serif;
    font-size: 13px;
    font-style: italic;
    color: rgba(255,255,255,0.2);
}

/* ── DROPDOWN STYLING ── */
.nav-cat-wrapper {
    position: relative;
    display: inline-block;
}

.dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background: var(--ink);
    border: 1px solid rgba(255, 255, 255, 0.1);
    min-width: 200px;
    display: none;
    flex-direction: column;
    padding: 1rem 0;
    z-index: 1000;
}

.dropdown-item {
    padding: 0.75rem 1.5rem;
    color: rgba(255, 255, 255, 0.7);
    font-size: 11px;
    font-family: 'DM Mono', monospace;
    text-transform: uppercase;
    text-decoration: none;
    letter-spacing: 0.08em;
    transition: all 0.2s;
}

.dropdown-item:hover {
    background: rgba(255, 255, 255, 0.05);
    color: var(--orange);
}

.nav-cat-wrapper:hover .dropdown-menu {
    display: flex;
}

/* Responsive */
@media (max-width: 900px) {

    .footer-main {
        grid-template-columns: 1fr;
        gap: 3rem;
    }

    .footer-bottom {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

}

        /* Responsive */
        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .hero-editorial::after { display: none; }
            .hero-image { display: none; }
            .hero-editorial { height: 100vh; }
            .reviews-grid { grid-template-columns: 1fr; }
            .review-card { border-right: none; border-bottom: 1px solid var(--warm-mid); }
            .categories-masthead {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 280px 200px 200px;
            }
            .cat-card:nth-child(1) { grid-column: 1 / 3; grid-row: 1; }
            .nav-links.desktop { display: none; }
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <!-- NAV -->
    <nav>
        <div class="nav-inner">
            <!-- Brand -->
            <a href="/" class="nav-logo-link">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Kelp" class="nav-logo-img">
            </a>

            <!-- Desktop Navigation -->
            <div class="nav-main-links desktop">
                <a href="#" class="nav-link">Write a Review</a>
                <a href="#" class="nav-link">For Business</a>

                <div class="nav-divider"></div>

                @foreach($categories->take(4) as $category)
                    <a href="{{ route('categories.show', $category->id) }}" class="nav-link">
                        {{ $category->name }}
                    </a>
                @endforeach

                @if($categories->count() > 4)
                <div class="nav-cat-wrapper">
                    <span class="nav-link" style="cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        Others
                        <svg width="8" height="5" fill="none" viewBox="0 0 8 5">
                            <path d="M1 1l3 3 3-3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </span>

                    <div class="dropdown-menu">
                        @foreach($categories->slice(4) as $extra)
                            <a href="{{ route('categories.show', $extra->id) }}" class="dropdown-item">
                                {{ $extra->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Side Actions -->
            <div class="nav-auth-actions">
                <button class="btn-login">Login</button>
                <button class="btn-signup">Join Kelp</button>
            </div>
        </div>
    </nav>

    <!-- Spacer to prevent content from hiding under fixed nav -->
    <div style="height: 90px;"></div>

    <!-- HERO -->
    <section class="hero">
        <!-- Left editorial column -->
        <div class="hero-editorial">

    <h1 class="hero-headline">
        <span>Kelp.</span>
        Know Before You Go<br>
            </h1>

            <p class="hero-sub">Discover the best restaurants, services, and experiences in your neighbourhood. Curated by people like you.</p>



            <div class="scroll-cue">
                <span>scroll</span>
                <div class="scroll-line"></div>
            </div>
        </div>

        <!-- Right image panel -->
        <div class="hero-image">
            <div id="slideshow-container">
                <div class="slide active">
                    <img src="{{ asset('assets/images/hero1.jpeg') }}" alt="Hero image 1">
                </div>
                <div class="slide">
                    <img src="{{ asset('assets/images/hero2.jpeg') }}" alt="Hero image 2">
                </div>
                <div class="slide">
                    <img src="{{ asset('assets/images/Hero3.jpeg') }}" alt="Hero image 2">
                </div>
                <div class="slide">
                    <img src="{{ asset('assets/images/Hero4.jpeg') }}" alt="Hero image 2">
                </div>
            </div>
            <div class="hero-image-overlay"></div>

            <div class="hero-badge">
                <div class="hero-badge-num">4.8k</div>
                <div class="hero-badge-label">Reviews this month</div>
            </div>
        </div>
    </section>

    <!-- RECENT ACTIVITIES -->
    <section class="section-recent">
        <div class="section-inner">
            <div class="section-header">
                <div class="section-label">
                    <span class="section-label-tag">Latest</span>
                    <h2 class="section-title">Recent activities</h2>
                </div>
                <span class="section-count">Fresh reviews</span>
            </div>

            <div class="reviews-grid">
                @foreach($recentReviews as $review)

<div class="review-card">

    <!-- Reviewer -->
    <div class="review-meta">

        <div class="review-avatar">
            {{ Str::substr($review->user_name, 0, 1) }}
        </div>

        <div>
            <div class="review-user-name">
                {{ $review->user_name }}
            </div>

            <div class="review-time">
                {{ $review->created_at->diffForHumans() }}
            </div>
        </div>

    </div>

    <!-- Business -->
    <a href="{{ route('details.show', $review->business->id) }}"
       class="review-biz-link">

        {{ $review->business->name }}

    </a>

    <!-- Stars -->
    <div class="stars">
        @for($i = 1; $i <= 5; $i++)
            <span class="star {{ $i <= $review->rating ? 'star-filled' : 'star-empty' }}">
                ★
            </span>
        @endfor
    </div>

    <!-- Comment -->
    <p class="review-quote">
        "{{ Str::limit($review->comment, 120) }}"
    </p>

    <!-- Images -->
    @if($review->images && $review->images->count())

        <div class="grid grid-cols-3 gap-2 mt-4">

            @foreach($review->images->take(3) as $image)

                <a href="{{ asset('storage/' . $image->image) }}"
                   target="_blank"
                   class="block overflow-hidden rounded-xl">

                    <img src="{{ asset('storage/' . $image->image) }}"
                         alt="Review Image"
                         class="w-full h-24 object-cover hover:scale-105 transition duration-300">

                </a>

            @endforeach

        </div>

    @endif

</div>

@endforeach
            </div>
        </div>
    </section>

    <!-- CATEGORIES -->
<section class="section-categories">
    <div class="section-inner">
        <div class="section-header">
            <div class="section-label">
                <span class="section-label-tag">Browse</span>
                <h2 class="section-title">Categories</h2>
            </div>
            <span class="section-count">{{ count($categories) }} categories</span>
        </div>

        <div class="categories-masthead">
            @foreach($categories as $index => $category)
            <a href="{{ route('categories.show', $category->id) }}" class="cat-card">
                <div class="cat-content">
                    <div class="cat-index">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</div>
                    <div class="cat-name">{{ $category->name }}</div>
                    <div class="cat-arrow">View Category →</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

    <!-- FOOTER -->
    <footer class="footer-editorial">

        <!-- Top Border Accent -->
        <div class="footer-accent"></div>

        <div class="footer-main">

            <!-- Brand -->
            <div class="footer-brand">
                <img src="{{ asset('assets/images/logo.png') }}"
                     alt="Kelp Logo"
                     class="footer-logo">

                <p class="footer-description">
                    Kelp is your modern local review magazine —
                    helping people discover authentic places,
                    unforgettable food, and trusted experiences.
                </p>
            </div>

            <!-- Dynamic Navigation (Categories) -->
            <div class="footer-column">
                <h4 class="footer-heading">Explore</h4>

                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories->take(6) as $category)
                        <a href="{{ route('categories.show', $category->id) }}" class="footer-link">
                            {{ $category->name }}
                        </a>
                    @endforeach
                @else
                    <a href="#" class="footer-link">Restaurants</a>
                    <a href="#" class="footer-link">Travel</a>
                    <a href="#" class="footer-link">Health & Beauty</a>
                @endif
            </div>

            <!-- Company -->
            <div class="footer-column">
                <h4 class="footer-heading">Company</h4>
                <a href="#" class="footer-link">About</a>
                <a href="#" class="footer-link">Careers</a>
                <a href="#" class="footer-link">For Businesses</a>
                <a href="#" class="footer-link">Write a Review</a>
                <a href="#" class="footer-link">Contact</a>
            </div>

            <!-- Newsletter -->
            <div class="footer-column">
                <h4 class="footer-heading">Stay Updated</h4>
                <p class="footer-news-text">
                    Get the latest local discoveries directly to your inbox.
                </p>

                <form class="footer-newsletter">
                    <input type="email"
                           placeholder="Enter your email"
                           class="footer-input">
                    <button type="submit" class="footer-btn">
                        Subscribe
                    </button>
                </form>
            </div>

        </div>

        <!-- Bottom -->
        <div class="footer-bottom">
            <span class="footer-copy">
                © {{ date('Y') }} Kelp Inc. — All Rights Reserved
            </span>
            <span class="footer-tagline">
                Discover your next favorite place.
            </span>
        </div>

    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const slides = document.querySelectorAll('.slide');
            let current = 0;
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 6000);
        });
    </script>
</body>
</html>
