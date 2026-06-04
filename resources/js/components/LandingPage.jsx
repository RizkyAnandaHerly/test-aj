import React, { useState, useEffect } from 'react';

const LandingPage = () => {
    const [trackingNumber, setTrackingNumber] = useState('');
    const [isScrolled, setIsScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            setIsScrolled(window.scrollY > 50);
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const handleTrack = (e) => {
        e.preventDefault();
        if (trackingNumber.trim()) {
            window.location.href = `/track?order_id=${encodeURIComponent(trackingNumber.trim())}`;
        }
    };

    return (
        <div style={{ fontFamily: "'Inter', sans-serif", backgroundColor: '#f8f9fa' }}>
            {/* Navbar */}
            <nav className={`navbar navbar-expand-lg fixed-top transition-all duration-300 ${isScrolled ? 'bg-white shadow-sm py-2' : 'bg-transparent py-4'}`} style={{ transition: 'all 0.3s ease' }}>
                <div className="container">
                    <a className={`navbar-brand fw-bold fs-3 d-flex align-items-center ${isScrolled ? 'text-dark' : 'text-white'}`} href="/">
                        <i className="bi bi-buildings-fill text-primary me-2"></i>
                        <span>Warehouse<span className={isScrolled ? 'text-primary' : 'text-light'}>Track</span></span>
                    </a>
                    
                    <div className="d-flex ms-auto">
                        <a href="/login" className={`btn rounded-pill px-4 fw-bold shadow-sm d-flex align-items-center ${isScrolled ? 'btn-primary' : 'btn-light text-primary'}`}>
                            <i className="bi bi-box-arrow-in-right me-2"></i> Login Karyawan
                        </a>
                    </div>
                </div>
            </nav>

            {/* Hero Section */}
            <section className="position-relative d-flex align-items-center justify-content-center" style={{ minHeight: '100vh', overflow: 'hidden' }}>
                <div 
                    className="position-absolute top-0 start-0 w-100 h-100"
                    style={{
                        backgroundImage: "url('https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=2070&auto=format&fit=crop')",
                        backgroundSize: 'cover',
                        backgroundPosition: 'center',
                        filter: 'brightness(0.6)'
                    }}
                ></div>
                
                {/* Gradient overlay for modern look */}
                <div className="position-absolute top-0 start-0 w-100 h-100" style={{ background: 'linear-gradient(135deg, rgba(13, 110, 253, 0.4) 0%, rgba(0, 0, 0, 0.6) 100%)' }}></div>

                <div className="container position-relative z-index-2 text-center" style={{ zIndex: 2, marginTop: '80px' }}>
                    <div className="row justify-content-center">
                        <div className="col-lg-8">
                            <span className="badge bg-primary px-3 py-2 rounded-pill mb-3 fs-6 fw-normal shadow-sm" style={{ letterSpacing: '1px' }}>
                                <i className="bi bi-truck me-2"></i>Sistem Pelacakan Terpadu
                            </span>
                            <h1 className="display-3 fw-bolder text-white mb-4" style={{ textShadow: '0 4px 12px rgba(0,0,0,0.3)' }}>
                                Lacak Posisi Barang Anda
                            </h1>
                            <p className="lead text-light mb-5 fs-4" style={{ opacity: 0.9 }}>
                                Masukkan Order ID atau Nomor Resi untuk melihat status dan lokasi barang di gudang secara real-time.
                            </p>

                            {/* Tracking Card */}
                            <div className="card border-0 shadow-lg rounded-4 p-2 p-md-3 bg-white" style={{ transform: 'translateY(20px)' }}>
                                <div className="card-body p-2">
                                    <form onSubmit={handleTrack} className="d-flex flex-column flex-md-row gap-2">
                                        <div className="input-group input-group-lg flex-grow-1 border rounded-pill overflow-hidden bg-light">
                                            <span className="input-group-text bg-transparent border-0 ps-4">
                                                <i className="bi bi-search text-muted"></i>
                                            </span>
                                            <input 
                                                type="text" 
                                                className="form-control border-0 bg-transparent fs-5" 
                                                placeholder="Contoh: WH-884920" 
                                                value={trackingNumber}
                                                onChange={(e) => setTrackingNumber(e.target.value)}
                                                required
                                                style={{ boxShadow: 'none' }}
                                            />
                                        </div>
                                        <button type="submit" className="btn btn-primary rounded-pill px-5 fw-bold fs-5 shadow-sm" style={{ minWidth: '160px' }}>
                                            Lacak <i className="bi bi-arrow-right ms-2"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Decorative Wave */}
                <div className="position-absolute bottom-0 start-0 w-100 overflow-hidden" style={{ lineHeight: 0, transform: 'translateY(2px)' }}>
                    <svg viewBox="0 0 1200 120" preserveAspectRatio="none" style={{ display: 'block', width: 'calc(100% + 1.3px)', height: '60px' }}>
                        <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,120.4,188.45,107.5,235.27,97,280.4,75.88,321.39,56.44Z" fill="#f8f9fa"></path>
                    </svg>
                </div>
            </section>

            {/* Stats Section */}
            <section className="py-5 bg-light" style={{ marginTop: '-20px' }}>
                <div className="container">
                    <div className="row g-4 justify-content-center text-center">
                        {[
                            { icon: 'box-seam', count: '1.2M+', label: 'Paket Diproses' },
                            { icon: 'buildings', count: '50+', label: 'Gudang Aktif' },
                            { icon: 'people', count: '10k+', label: 'Klien Percaya' },
                            { icon: 'shield-check', count: '99.9%', label: 'Akurasi Data' }
                        ].map((stat, idx) => (
                            <div key={idx} className="col-6 col-md-3">
                                <div className="p-4 rounded-4 bg-white shadow-sm h-100" style={{ transition: 'transform 0.3s ease', cursor: 'pointer' }} onMouseOver={(e) => e.currentTarget.style.transform = 'translateY(-5px)'} onMouseOut={(e) => e.currentTarget.style.transform = 'translateY(0)'}>
                                    <div className="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle mb-3" style={{ width: '64px', height: '64px' }}>
                                        <i className={`bi bi-${stat.icon} fs-2`}></i>
                                    </div>
                                    <h2 className="fw-bolder text-dark mb-1">{stat.count}</h2>
                                    <p className="text-muted fw-medium mb-0">{stat.label}</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* Services Section */}
            <section className="py-5 my-4">
                <div className="container">
                    <div className="text-center mb-5">
                        <span className="text-primary fw-bold text-uppercase tracking-wider">Layanan Kami</span>
                        <h2 className="display-6 fw-bold mt-2">Solusi Logistik & Pergudangan</h2>
                    </div>
                    
                    <div className="row g-4">
                        {[
                            { title: 'Real-time Tracking', desc: 'Pantau pergerakan barang Anda dari penerimaan hingga pengeluaran dengan akurasi tinggi.', icon: 'geo-alt-fill', color: 'primary' },
                            { title: 'Manajemen Inventaris', desc: 'Sistem cerdas untuk mengelola stok, mencegah kekurangan, dan mengoptimalkan ruang gudang.', icon: 'boxes', color: 'success' },
                            { title: 'Keamanan Ekstra', desc: 'Fasilitas dengan pengawasan 24/7 dan Quality Control ketat untuk setiap barang masuk.', icon: 'shield-lock-fill', color: 'warning' }
                        ].map((srv, idx) => (
                            <div key={idx} className="col-md-4">
                                <div className="card border-0 bg-white shadow-sm rounded-4 h-100 overflow-hidden group">
                                    <div className={`bg-${srv.color} p-1`}></div>
                                    <div className="card-body p-4 p-xl-5">
                                        <div className={`d-inline-flex align-items-center justify-content-center bg-${srv.color} bg-opacity-10 text-${srv.color} rounded-3 mb-4`} style={{ width: '56px', height: '56px' }}>
                                            <i className={`bi bi-${srv.icon} fs-3`}></i>
                                        </div>
                                        <h4 className="fw-bold mb-3">{srv.title}</h4>
                                        <p className="text-muted mb-0 lh-lg">{srv.desc}</p>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* About / Company Profile */}
            <section className="py-5 bg-white border-top">
                <div className="container py-4">
                    <div className="row align-items-center">
                        <div className="col-lg-6 mb-4 mb-lg-0 pe-lg-5">
                            <h2 className="display-6 fw-bold mb-4">Mendukung Rantai Pasok Global Anda</h2>
                            <p className="text-muted fs-5 lh-lg mb-4">
                                WarehouseTrack adalah penyedia layanan manajemen gudang terkemuka yang berdedikasi untuk menyederhanakan rantai pasok Anda. 
                                Dengan teknologi terkini dan tim profesional, kami memastikan setiap barang dikelola dengan efisien dan aman.
                            </p>
                            <ul className="list-unstyled mb-4">
                                {['Integrasi API untuk E-Commerce', 'Laporan Analitik Komprehensif', 'Dukungan Pelanggan 24/7'].map((item, i) => (
                                    <li key={i} className="mb-3 d-flex align-items-center text-dark fw-medium">
                                        <i className="bi bi-check-circle-fill text-success me-3 fs-5"></i> {item}
                                    </li>
                                ))}
                            </ul>
                            <a href="#" className="btn btn-outline-dark rounded-pill px-4 py-2 fw-bold">Pelajari Lebih Lanjut</a>
                        </div>
                        <div className="col-lg-6">
                            <div className="position-relative rounded-4 overflow-hidden shadow-lg">
                                <img src="https://images.unsplash.com/photo-1553413077-190dd305871c?q=80&w=1035&auto=format&fit=crop" alt="Warehouse Operations" className="img-fluid w-100" style={{ objectFit: 'cover', height: '450px' }} />
                                <div className="position-absolute bottom-0 start-0 w-100 p-4 bg-dark bg-opacity-75 text-white backdrop-blur">
                                    <div className="d-flex align-items-center">
                                        <i className="bi bi-award fs-1 text-warning me-3"></i>
                                        <div>
                                            <h5 className="fw-bold mb-0">Sertifikasi ISO 9001:2015</h5>
                                            <small className="opacity-75">Standar Manajemen Mutu Internasional</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* Footer */}
            <footer className="bg-dark text-white pt-5 pb-3">
                <div className="container">
                    <div className="row mb-4">
                        <div className="col-lg-4 mb-4 mb-lg-0">
                            <a className="navbar-brand fw-bold fs-4 d-flex align-items-center text-white mb-3" href="/">
                                <i className="bi bi-buildings-fill text-primary me-2"></i>
                                <span>Warehouse<span className="text-primary">Track</span></span>
                            </a>
                            <p className="text-secondary mb-4 pe-lg-4">
                                Solusi pintar untuk manajemen pergudangan modern. Tingkatkan efisiensi dan transparansi operasional logistik Anda bersama kami.
                            </p>
                            <div className="d-flex gap-3">
                                <a href="#" className="text-white opacity-75 hover-opacity-100"><i className="bi bi-linkedin fs-5"></i></a>
                                <a href="#" className="text-white opacity-75 hover-opacity-100"><i className="bi bi-twitter-x fs-5"></i></a>
                                <a href="#" className="text-white opacity-75 hover-opacity-100"><i className="bi bi-instagram fs-5"></i></a>
                            </div>
                        </div>
                        <div className="col-6 col-lg-2 offset-lg-1 mb-4 mb-lg-0">
                            <h6 className="fw-bold text-uppercase mb-3">Layanan</h6>
                            <ul className="list-unstyled">
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Lacak Kiriman</a></li>
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Cek Tarif Sewa</a></li>
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Integrasi B2B</a></li>
                            </ul>
                        </div>
                        <div className="col-6 col-lg-2 mb-4 mb-lg-0">
                            <h6 className="fw-bold text-uppercase mb-3">Perusahaan</h6>
                            <ul className="list-unstyled">
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Tentang Kami</a></li>
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Karir</a></li>
                                <li className="mb-2"><a href="#" className="text-secondary text-decoration-none hover-white">Hubungi Kami</a></li>
                            </ul>
                        </div>
                        <div className="col-lg-3">
                            <h6 className="fw-bold text-uppercase mb-3">Kantor Pusat</h6>
                            <ul className="list-unstyled text-secondary">
                                <li className="mb-2 d-flex"><i className="bi bi-geo-alt-fill me-2 mt-1 text-primary"></i> Gedung Logistik No. 88, Kawasan Industri Sudirman, Jakarta</li>
                                <li className="mb-2 d-flex"><i className="bi bi-telephone-fill me-2 mt-1 text-primary"></i> (021) 555-0192</li>
                                <li className="mb-2 d-flex"><i className="bi bi-envelope-fill me-2 mt-1 text-primary"></i> info@warehousetrack.id</li>
                            </ul>
                        </div>
                    </div>
                    <hr className="border-secondary opacity-50 my-4" />
                    <div className="text-center text-secondary small">
                        &copy; {new Date().getFullYear()} Warehouse Monitoring System. All rights reserved.
                    </div>
                </div>
            </footer>
        </div>
    );
};

export default LandingPage;
