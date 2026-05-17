<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WarehouseTrack — Sistem Monitoring Gudang</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- GSAP + ScrollTrigger via CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    
    <style>
      /* Reset & Base */
      * { margin: 0; padding: 0; box-sizing: border-box; }
      body {
        font-family: 'Poppins', sans-serif;
        background: #f8fafc;
        color: #0f172a;
        overflow-x: hidden;
      }
      
      /* NAVBAR */
      #navbar {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 100;
        padding: 20px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 300ms ease;
        background: transparent;
      }
      .nav-brand {
        font-size: 20px;
        font-weight: 800;
        color: white;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 300ms;
      }
      .nav-brand svg {
        color: #3b82f6;
        width: 28px;
        height: 28px;
      }
      .nav-links {
        display: flex;
        gap: 32px;
        align-items: center;
      }
      .nav-links a {
        color: white;
        text-decoration: none;
        font-size: 15px;
        font-weight: 500;
        transition: color 200ms;
      }
      .nav-links a:hover {
        color: #93c5fd;
      }
      .nav-btn {
        background: white;
        color: #2563eb !important;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: 700 !important;
        transition: all 200ms !important;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
      }
      .nav-btn:hover {
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 6px 10px -1px rgba(0,0,0,0.15);
      }

      /* Scrolled Navbar State */
      #navbar.scrolled {
        background: rgba(255, 255, 255, 0.98);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        padding: 16px 40px;
      }
      #navbar.scrolled .nav-brand {
        color: #0f172a;
      }
      #navbar.scrolled .nav-links a:not(.nav-btn) {
        color: #475569;
      }
      #navbar.scrolled .nav-links a:not(.nav-btn):hover {
        color: #2563eb;
      }
      #navbar.scrolled .nav-btn {
        background: #2563eb;
        color: white !important;
      }
      #navbar.scrolled .nav-btn:hover {
        background: #1d4ed8;
      }
      
      /* SECTION 1: HERO */
      .sticky-hero {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow: hidden;
        background: #e2e8f0;
      }
      
      #hero-video {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
        opacity: 0.9;
      }
      
      .video-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, rgba(15,23,42,0.5) 0%, rgba(15,23,42,0.2) 50%, rgba(15,23,42,0.6) 100%);
        z-index: 2;
      }
      
      .hero-phase {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 0 60px;
        z-index: 10;
        pointer-events: none;
        opacity: 0;
        padding-bottom: 120px; /* Space for tracker widget */
      }
      
      .hero-phase.center {
        align-items: center;
        text-align: center;
        padding-left: 40px;
        padding-right: 40px;
      }
      .hero-phase.left {
        align-items: flex-start;
        text-align: left;
        max-width: 900px;
      }
      .hero-phase.right {
        align-items: flex-end;
        text-align: right;
        max-width: 900px;
        margin-left: auto;
      }
      
      #phase-1 { opacity: 1; }
      
      .hero-phase h1 {
        font-size: clamp(36px, 6vw, 76px);
        font-weight: 800;
        color: white;
        line-height: 1.1;
        margin: 16px 0;
        text-shadow: 0 4px 12px rgba(0,0,0,0.3);
      }
      .hero-phase p {
        font-size: clamp(16px, 1.5vw, 20px);
        line-height: 1.6;
        color: rgba(255,255,255,0.95);
        max-width: 700px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
      }

      /* TRACKER WIDGET (JNE Style) */
      .tracker-widget {
        position: absolute;
        bottom: 50px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(12px);
        padding: 24px 32px;
        border-radius: 20px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        z-index: 20;
        width: 90%;
        max-width: 900px;
        display: flex;
        align-items: center;
        gap: 16px;
      }
      .tracker-input-group {
        flex: 1;
        position: relative;
        display: flex;
        align-items: center;
      }
      .tracker-input-group svg {
        position: absolute;
        left: 20px;
        color: #64748b;
        width: 24px;
        height: 24px;
      }
      .tracker-input {
        width: 100%;
        padding: 18px 18px 18px 56px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        font-family: 'Poppins', sans-serif;
        outline: none;
        transition: border-color 200ms;
        color: #0f172a;
        background: white;
      }
      .tracker-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.1);
      }
      .tracker-btn {
        background: #2563eb;
        color: white;
        padding: 18px 48px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        transition: all 200ms;
        white-space: nowrap;
        border: none;
        cursor: pointer;
        font-family: 'Poppins', sans-serif;
      }
      .tracker-btn:hover {
        background: #1d4ed8;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(37,99,235,0.3);
      }

      /* FEATURES SECTION */
      #features {
        background: #ffffff;
        padding: 120px 60px;
      }
      .section-tag {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0.15em;
        color: #2563eb;
        text-transform: uppercase;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 12px;
      }
      .section-tag::before {
        content: '';
        width: 40px;
        height: 3px;
        background: #2563eb;
        border-radius: 2px;
      }
      .features-heading {
        font-size: clamp(32px, 5vw, 48px);
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-bottom: 60px;
      }
      .feature-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 32px;
      }
      .feature-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 24px;
        padding: 40px 32px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        transition: all 300ms;
        opacity: 0;
        transform: translateY(30px);
      }
      .feature-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.04);
        border-color: #e2e8f0;
      }
      .feature-icon {
        width: 64px;
        height: 64px;
        background: #eff6ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563eb;
        margin-bottom: 24px;
      }
      .feature-icon svg { width: 32px; height: 32px; }
      .feature-card h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
      }
      .feature-card p {
        font-size: 15px;
        line-height: 1.7;
        color: #475569;
      }

      /* HOW IT WORKS SECTION */
      #how-it-works {
        background: #f8fafc;
        padding: 120px 60px;
      }
      .steps-container {
        display: flex;
        align-items: flex-start;
        gap: 32px;
        margin-top: 60px;
      }
      .step {
        flex: 1;
        background: white;
        padding: 48px 40px;
        border-radius: 24px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
        opacity: 0;
        transform: translateX(-20px);
        border: 1px solid #f1f5f9;
      }
      .step-number {
        font-size: 72px;
        font-weight: 900;
        color: transparent;
        -webkit-text-stroke: 2px #dbeafe;
        line-height: 1;
        margin-bottom: 24px;
      }
      .step-content h3 {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 16px;
      }
      .step-content p {
        font-size: 15px;
        line-height: 1.7;
        color: #475569;
      }

      /* CTA SECTION */
      #cta {
        position: relative;
        padding: 120px 60px;
        background: #2563eb;
        overflow: hidden;
        text-align: center;
      }
      #cta .bg-pattern {
        position: absolute;
        inset: 0;
        opacity: 0.1;
        background-image: radial-gradient(#ffffff 2px, transparent 2px);
        background-size: 40px 40px;
      }
      #cta-content {
        position: relative;
        z-index: 10;
      }
      #cta h2 {
        font-size: clamp(36px, 5vw, 64px);
        font-weight: 800;
        color: white;
        margin-bottom: 24px;
      }
      #cta p {
        font-size: 18px;
        color: #bfdbfe;
        max-width: 600px;
        margin: 0 auto 48px;
      }
      .btn-white {
        background: white;
        color: #2563eb;
        padding: 18px 48px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 16px;
        text-decoration: none;
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        transition: transform 200ms;
        display: inline-block;
      }
      .btn-white:hover {
        transform: translateY(-4px);
      }

      /* FOOTER */
      footer {
        background: #ffffff;
        padding: 60px;
        border-top: 1px solid #e2e8f0;
      }
      .footer-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        flex-wrap: wrap;
        gap: 32px;
      }
      .footer-brand {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
      }
      .footer-brand svg { fill: none; stroke: #2563eb; width: 32px; height: 32px; }
      
      @media (max-width: 768px) {
        .feature-grid { grid-template-columns: 1fr; }
        .steps-container { flex-direction: column; }
        .nav-links a:not(.nav-btn) { display: none; }
        .tracker-widget { flex-direction: column; width: 90%; padding: 20px; }
        .tracker-btn { width: 100%; text-align: center; }
        .hero-phase { padding: 0 30px; padding-bottom: 200px; }
      }
    </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav id="navbar">
    <a href="/" class="nav-brand">
      <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="4" y="2" width="16" height="20" rx="2" ry="2" stroke="currentColor"></rect>
        <path d="M9 22v-4h6v4" stroke="currentColor"></path>
        <path d="M8 6h.01" stroke="currentColor"></path>
        <path d="M16 6h.01" stroke="currentColor"></path>
        <path d="M12 6h.01" stroke="currentColor"></path>
        <path d="M12 10h.01" stroke="currentColor"></path>
        <path d="M12 14h.01" stroke="currentColor"></path>
        <path d="M16 10h.01" stroke="currentColor"></path>
        <path d="M16 14h.01" stroke="currentColor"></path>
        <path d="M8 10h.01" stroke="currentColor"></path>
        <path d="M8 14h.01" stroke="currentColor"></path>
      </svg>
      WarehouseTrack
    </a>
    <div class="nav-links">
      <a href="#features">Tentang</a>
      <a href="#how-it-works">Fitur</a>
      <a href="{{ route('track') }}">Lacak Order</a>
      <a href="{{ route('login') }}" class="nav-btn">Masuk Sistem</a>
    </div>
  </nav>

  <!-- SECTION 1: HERO (Scrolltelling) -->
  <section id="hero" style="height: 300vh; position: relative;">
    <div class="sticky-hero">
      
      <video autoplay muted loop playsinline id="hero-video">
        <!-- Make sure to put hero.mp4 in the public/videos/ folder! -->
        <source src="/videos/hero.mp4" type="video/mp4">
      </video>
      <div class="video-overlay"></div>
      
      <!-- PHASES -->
      <div id="phase-1" class="hero-phase center">
        <h1>Menyambung Kepastian dari Gudang ke Tujuan</h1>
        <p>Sistem monitoring pergerakan barang terintegrasi dengan visibilitas penuh, akurasi tinggi, dan pembaruan real-time.</p>
      </div>
      
      <div id="phase-2" class="hero-phase left">
        <h1>Lacak Setiap<br>Pergerakan</h1>
        <p>Dari penerimaan di rak hingga pengiriman ke tangan Anda. Seluruh data terekam secara otomatis.</p>
      </div>
      
      <div id="phase-3" class="hero-phase right">
        <h1>Transparansi<br>Total</h1>
        <p>Hasil Quality Control dan lokasi penyimpanan terpantau langsung melalui satu platform terpusat.</p>
      </div>

      <!-- JNE-Style Tracker Overlay at Bottom -->
      <div class="tracker-widget">
        <div class="tracker-input-group">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <input type="text" id="track-input" class="tracker-input" placeholder="Masukkan Batch No / Order ID untuk melacak...">
        </div>
        <button class="tracker-btn" onclick="goToTrack()">Lacak Kiriman</button>
      </div>
      
    </div>
  </section>

  <!-- SECTION 2: FEATURES -->
  <section id="features">
    <div class="section-tag">KEMAMPUAN SISTEM</div>
    <h2 class="features-heading">Satu platform untuk<br>seluruh operasional gudang.</h2>
    
    <div class="feature-grid">
      <div class="feature-card">
        <div class="feature-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
        </div>
        <h3>Pantau Status Order</h3>
        <p>Lacak posisi dan status pesanan Anda secara real-time dari penerimaan hingga pengiriman dengan akurasi tinggi.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
        </div>
        <h3>Lokasi Barang Akurat</h3>
        <p>Sistem kami memetakan posisi barang Anda hingga level rak dan palet secara presisi.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <h3>Hasil QC Transparan</h3>
        <p>Lihat status kelayakan barang dan hasil inspeksi quality control secara detail sebelum pengiriman.</p>
      </div>
    </div>
  </section>

  <!-- SECTION 3: HOW IT WORKS -->
  <section id="how-it-works">
    <div class="section-tag">CARA KERJA</div>
    <h2 class="features-heading">Tiga langkah mudah.<br>Hasil maksimal.</h2>
    
    <div class="steps-container">
      <div class="step">
        <div class="step-number">01</div>
        <div class="step-content">
          <h3>Request Order</h3>
          <p>Ajukan permintaan barang melalui sistem. Sales Order dibuat otomatis dan diteruskan ke tim operasional gudang.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">02</div>
        <div class="step-content">
          <h3>Proses Gudang</h3>
          <p>Tim staf melakukan penerimaan, inspeksi QC, packing, dan pelabelan. Setiap tahap akan mengirim pembaruan real-time.</p>
        </div>
      </div>
      <div class="step">
        <div class="step-number">03</div>
        <div class="step-content">
          <h3>Pantau & Terima</h3>
          <p>Masukkan nomor batch Anda di halaman depan dan pantau statusnya hingga barang siap dikirim ke tujuan.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- SECTION 4: CTA -->
  <section id="cta">
    <div class="bg-pattern"></div>
    <div id="cta-content">
      <h2>Tingkatkan Efisiensi Rantai<br>Pasok Anda Hari Ini.</h2>
      <p>Bergabung dengan WarehouseTrack. Nikmati visibilitas penuh, akurasi stok otomatis, dan keputusan bisnis yang lebih baik.</p>
      <a href="{{ route('login') }}" class="btn-white">Masuk ke Sistem Utama</a>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div>
        <div class="footer-brand">
          <svg viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="4" y="2" width="16" height="20" rx="2" ry="2" stroke="currentColor"></rect>
            <path d="M9 22v-4h6v4" stroke="currentColor"></path>
            <path d="M8 6h.01" stroke="currentColor"></path>
            <path d="M16 6h.01" stroke="currentColor"></path>
            <path d="M12 6h.01" stroke="currentColor"></path>
            <path d="M12 10h.01" stroke="currentColor"></path>
            <path d="M12 14h.01" stroke="currentColor"></path>
            <path d="M16 10h.01" stroke="currentColor"></path>
            <path d="M16 14h.01" stroke="currentColor"></path>
            <path d="M8 10h.01" stroke="currentColor"></path>
            <path d="M8 14h.01" stroke="currentColor"></path>
          </svg>
          WarehouseTrack
        </div>
        <div style="font-size:14px; color:#64748b; margin-top:8px;">
          Sistem Monitoring Pergerakan Barang Terintegrasi
        </div>
      </div>
      <div style="text-align:right;">
        <div style="font-size:14px; font-weight:500; color:#0f172a; margin-bottom:8px;">© 2026 WarehouseTrack</div>
        <div style="font-size:13px; color:#94a3b8;">Telkom University</div>
      </div>
    </div>
  </footer>

  <!-- GSAP ANIMATIONS -->
  <script>
    gsap.registerPlugin(ScrollTrigger);

    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    window.addEventListener('scroll', () => {
      if (window.scrollY > 80) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // Hero Video Load (Handles Cache & Fallbacks)
    const heroVideo = document.getElementById('hero-video');
    if (heroVideo) {
      heroVideo.load();
      heroVideo.play().catch(() => {});
    }

    // Tracker Button Logic
    function goToTrack() {
      const input = document.getElementById('track-input').value;
      if (input) {
        window.location.href = "{{ route('track') }}?search=" + encodeURIComponent(input);
      } else {
        window.location.href = "{{ route('track') }}";
      }
    }

    // SCROLLTELLING PHASES (Hero)
    gsap.to('#phase-1', {
      opacity: 0,
      scrollTrigger: {
        trigger: '#hero',
        start: 'top top',
        end: '25% top',
        scrub: true,
      }
    });

    gsap.fromTo('#phase-2',
      { opacity: 0, x: -50 },
      {
        opacity: 1, x: 0,
        scrollTrigger: {
          trigger: '#hero',
          start: '25% top',
          end: '35% top',
          scrub: true,
        }
      }
    );
    gsap.to('#phase-2', {
      opacity: 0, x: -50,
      scrollTrigger: {
        trigger: '#hero',
        start: '55% top',
        end: '65% top',
        scrub: true,
      }
    });

    gsap.fromTo('#phase-3',
      { opacity: 0, x: 50 },
      {
        opacity: 1, x: 0,
        scrollTrigger: {
          trigger: '#hero',
          start: '60% top',
          end: '70% top',
          scrub: true,
        }
      }
    );
    gsap.to('#phase-3', {
      opacity: 0, x: 50,
      scrollTrigger: {
        trigger: '#hero',
        start: '90% top',
        end: '100% top',
        scrub: true,
      }
    });

    // Fade up animations for sections
    gsap.to('.feature-card', {
      opacity: 1, y: 0,
      stagger: 0.15,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: '.feature-grid',
        start: 'top 80%',
        once: true,
      }
    });

    gsap.to('.step', {
      opacity: 1, x: 0,
      stagger: 0.2,
      duration: 0.8,
      ease: 'power3.out',
      scrollTrigger: {
        trigger: '.steps-container',
        start: 'top 80%',
        once: true,
      }
    });

    gsap.fromTo('#cta-content',
      { opacity: 0, y: 50 },
      {
        opacity: 1, y: 0,
        duration: 1,
        ease: 'power3.out',
        scrollTrigger: {
          trigger: '#cta',
          start: 'top 75%',
          once: true,
        }
      }
    );
  </script>
</body>
</html>
