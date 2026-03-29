<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Welcome to the Pride — Big Simba Safaris</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;700;900&display=swap');

        body, body *:not(html):not(style):not(br):not(tr):not(code) {
            box-sizing: border-box;
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            background-color: #050505;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-text-size-adjust: none;
        }
        .wrapper {
            background-color: #050505;
            padding: 40px 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
        }

        /* ── Header ── */
        .header {
            text-align: center;
            padding: 40px 0 32px;
            border-bottom: 1px solid #1a1a1a;
        }
        .header img {
            height: 80px;
            width: 80px;
            object-fit: contain;
        }
        .header-brand {
            margin-top: 14px;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: #d4af37;
        }
        .header-tagline {
            margin-top: 4px;
            font-size: 9px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.2);
        }

        /* ── Hero Banner ── */
        .hero {
            background: linear-gradient(135deg, #0d0d0d 0%, #111111 100%);
            border: 1px solid #1a1a1a;
            border-top: 3px solid #d4af37;
            padding: 48px 40px 40px;
            margin-top: 0;
        }
        .hero-eyebrow {
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: #d4af37;
            margin: 0 0 16px;
        }
        .hero-title {
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            line-height: 1.05;
            color: #ffffff;
            margin: 0 0 20px;
        }
        .hero-title span {
            color: rgba(212, 175, 55, 0.25);
        }
        .hero-body {
            font-size: 14px;
            font-weight: 300;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.55);
            margin: 0;
        }

        /* ── Content Body  ── */
        .content-body {
            background-color: #0d0d0d;
            border: 1px solid #1a1a1a;
            border-top: none;
            padding: 40px;
        }

        /* ── Section Label ── */
        .section-label {
            font-size: 8px;
            font-weight: 900;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: rgba(212, 175, 55, 0.6);
            margin: 0 0 16px;
        }

        /* ── Feature List ── */
        .feature-list {
            margin: 0 0 36px;
            padding: 0;
            list-style: none;
        }
        .feature-list li {
            display: flex;
            gap: 14px;
            padding: 14px 0;
            border-bottom: 1px solid #1a1a1a;
            align-items: flex-start;
        }
        .feature-list li:last-child { border-bottom: none; }
        .feature-dot {
            width: 6px;
            height: 6px;
            background: #d4af37;
            border-radius: 50%;
            margin-top: 6px;
            flex-shrink: 0;
        }
        .feature-text {
            font-size: 13px;
            font-weight: 400;
            color: rgba(255,255,255,0.65);
            line-height: 1.6;
            margin: 0;
        }
        .feature-title {
            display: block;
            font-weight: 700;
            color: #ffffff;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 3px;
        }

        /* ── CTA Button ── */
        .cta-wrap {
            text-align: center;
            margin: 36px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #d4af37;
            color: #000000 !important;
            text-decoration: none;
            font-size: 11px;
            font-weight: 900;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            padding: 18px 48px;
        }

        /* ── Support Panel ── */
        .support-panel {
            background-color: #111111;
            border-left: 3px solid #d4af37;
            padding: 24px 28px;
            margin: 32px 0 0;
        }
        .support-panel p {
            margin: 0 0 8px;
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            line-height: 1.6;
        }
        .support-panel p:last-child { margin-bottom: 0; }
        .support-link {
            color: #d4af37 !important;
            text-decoration: none;
            font-weight: 700;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px solid #1a1a1a;
            margin: 32px 0;
        }

        /* ── Footer ── */
        .footer {
            padding: 32px 0 8px;
            text-align: center;
        }
        .footer p {
            font-size: 10px;
            color: rgba(255,255,255,0.15);
            margin: 0 0 6px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
        .footer-brand {
            font-size: 9px;
            font-weight: 900;
            letter-spacing: 0.4em;
            color: rgba(212, 175, 55, 0.4);
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="email-container">

        {{-- ── Header ── --}}
        <div class="header">
            @if ($logoBase64)
                <img src="{{ $logoBase64 }}" alt="Big Simba Safaris" />
            @endif
            <div class="header-brand">Big Simba Safaris</div>
            <div class="header-tagline">Command Center // Induction Notice</div>
        </div>

        {{-- ── Hero ── --}}
        <div class="hero">
            <p class="hero-eyebrow">- Operative Inducted //</p>
            <h1 class="hero-title">
                Welcome to<br>
                the <span>Pride.</span>
            </h1>
            <p class="hero-body">
                Operative <strong style="color:#ffffff; font-weight:700;">{{ $user->name }}</strong> —
                your identity has been verified and your command access has been provisioned.
                From this moment forward, you are an authorized member of the Big Simba Safaris Pride.
            </p>
        </div>

        {{-- ── Features ── --}}
        <div class="content-body">
            <p class="section-label">— Your Access Clearances</p>

            <ul class="feature-list">
                <li>
                    <div class="feature-dot"></div>
                    <p class="feature-text">
                        <span class="feature-title">Strategic Expedition Requests</span>
                        Browse, request quotes, and book extraordinary safari experiences tailored to your mission profile.
                    </p>
                </li>
                <li>
                    <div class="feature-dot"></div>
                    <p class="feature-text">
                        <span class="feature-title">Mobility Fleet Deployments</span>
                        Commission specialized ground vehicles for every terrain. Our fleet is ready for immediate mobilization.
                    </p>
                </li>
                <li>
                    <div class="feature-dot"></div>
                    <p class="feature-text">
                        <span class="feature-title">Operations Ledger</span>
                        Monitor all your active missions, upcoming expeditions, and field records from a single Command Center.
                    </p>
                </li>
                <li>
                    <div class="feature-dot"></div>
                    <p class="feature-text">
                        <span class="feature-title">Extended Services Network</span>
                        Access our full suite of strategic support services — from guides to concierge logistics.
                    </p>
                </li>
            </ul>

            {{-- ── CTA ── --}}
            <div class="cta-wrap">
                <a href="{{ $dashboardUrl }}" class="cta-button">Access Command Center</a>
            </div>

            <hr class="divider" />

            {{-- ── Support ── --}}
            <p class="section-label">— Support Center Communications</p>
            <div class="support-panel">
                <p>Our elite support team is on standby <strong style="color:#fff;">24/7</strong> to guarantee your mission's success. Relay any tactical queries, itinerary recalibrations, or emergency extractions below:</p>
                <p>📡 &nbsp;<strong style="color:#fff;">Field Operations:</strong> <a href="mailto:support@bigsimbasafaris.com" class="support-link">support@bigsimbasafaris.com</a></p>
                <p>📞 &nbsp;<strong style="color:#fff;">Emergency Comms:</strong> <a href="tel:+254700000000" class="support-link">+254 (0) 700 000 000</a></p>
            </div>
        </div>

        {{-- ── Footer ── --}}
        <div class="footer">
            <p class="footer-brand">Big Simba Safaris &nbsp;·&nbsp; Command Center</p>
            <p>Prepare for absolute savanna dominance.</p>
            <p style="margin-top:16px; font-size:9px; opacity:0.4;">You received this message because you joined Big Simba Safaris. If this wasn't you, please disregard.</p>
        </div>

    </div>
</div>
</body>
</html>
