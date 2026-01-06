import { Head, Link } from '@inertiajs/react';
import { motion, useScroll, useTransform, AnimatePresence } from 'framer-motion';
import { useRef, useState, useEffect } from 'react';

interface Stats {
    totalPesantren: number;
    totalSantri: number;
    totalUsers: number;
}

interface FeatureItem {
    name: string;
    included: boolean;
}

interface Package {
    id: number;
    name: string;
    price: number;
    duration_months: number;
    max_users: number;
    max_santri: number;
    features: FeatureItem[] | string;
}

interface WelcomeProps {
    stats: Stats;
    packages: Package[];
}

// Smooth easing for animations
const ease = [0.25, 0.1, 0.25, 1];

// Animation variants
const fadeUp = {
    hidden: { opacity: 0, y: 30 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.6, ease } }
};

const fadeIn = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { duration: 0.5, ease } }
};

const stagger = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { staggerChildren: 0.1 } }
};

const scaleUp = {
    hidden: { opacity: 0, scale: 0.95 },
    visible: { opacity: 1, scale: 1, transition: { duration: 0.5, ease } }
};

// Section IDs for scroll spy
const sections = ['hero', 'features', 'how-it-works', 'pricing', 'security', 'cta'];

// Icon Components
const Icons = {
    menu: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>,
    close: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>,
    arrow: <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>,
    check: <svg className="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" /></svg>,
    users: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>,
    money: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>,
    book: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>,
    clipboard: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>,
    shield: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>,
    lock: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>,
    database: <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={1.5} d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>,
    star: <svg className="w-5 h-5 text-cyan-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>,
};

// Feature cards data
const features = [
    {
        icon: Icons.clipboard,
        title: 'Sekretaris',
        desc: 'Kelola data santri, asrama, mutasi, perizinan, dan administrasi lengkap.',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: Icons.book,
        title: 'Pendidikan',
        desc: 'Nilai, absensi, hafalan Al-Quran, rapor digital, dan jadwal pelajaran.',
        color: 'from-cyan-500 to-teal-500',
    },
    {
        icon: Icons.money,
        title: 'Bendahara',
        desc: 'Pembayaran SPP, pemasukan, pengeluaran, gaji, dan laporan keuangan.',
        color: 'from-teal-500 to-emerald-500',
    },
];

// How it works steps
const steps = [
    { num: '01', title: 'Daftar', desc: 'Buat akun pesantren dalam hitungan menit.' },
    { num: '02', title: 'Setup', desc: 'Konfigurasi data pesantren dan user.' },
    { num: '03', title: 'Kelola', desc: 'Mulai kelola santri, keuangan, dan pendidikan.' },
    { num: '04', title: 'Laporan', desc: 'Dapatkan laporan dan insight otomatis.' },
];

// Security features
const securityFeatures = [
    { icon: Icons.database, title: 'Data Terisolasi', desc: 'Setiap pesantren memiliki database terpisah.' },
    { icon: Icons.lock, title: 'Enkripsi', desc: 'Data dienkripsi dan aman dari akses ilegal.' },
    { icon: Icons.shield, title: 'Backup Otomatis', desc: 'Data di-backup secara berkala.' },
];

// Testimonials
const testimonials = [
    { name: 'Ust. Ahmad Fauzi', role: 'Mudir PP. Riyadlul Huda', text: 'Santrix sangat membantu mengelola data santri dan keuangan. Semuanya jadi lebih rapi.', avatar: 'AF' },
    { name: 'Ust. Muhammad Rizki', role: 'Kepala TU PP. Darul Ulum', text: 'Fitur laporan otomatis menghemat waktu kami. Tidak perlu input manual setiap bulan.', avatar: 'MR' },
    { name: 'Ustadzah Siti Aminah', role: 'Bendahara PP. Nurul Hikmah', text: 'Tracking pembayaran SPP jadi mudah. Wali santri bisa cek tagihan via WhatsApp.', avatar: 'SA' },
];

export default function Welcome({ stats, packages }: WelcomeProps) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [scrolled, setScrolled] = useState(false);
    const [activeSection, setActiveSection] = useState('hero');

    const heroRef = useRef<HTMLDivElement>(null);
    const { scrollYProgress } = useScroll({ target: heroRef, offset: ['start start', 'end start'] });
    const heroY = useTransform(scrollYProgress, [0, 1], [0, 150]);
    const heroOpacity = useTransform(scrollYProgress, [0, 0.5], [1, 0]);

    // Scroll spy
    useEffect(() => {
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);

            // Find active section
            for (const section of sections) {
                const el = document.getElementById(section);
                if (el) {
                    const rect = el.getBoundingClientRect();
                    if (rect.top <= 150 && rect.bottom > 150) {
                        setActiveSection(section);
                        break;
                    }
                }
            }
        };
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    const safeStats = {
        totalPesantren: stats?.totalPesantren ?? 0,
        totalSantri: stats?.totalSantri ?? 0,
        totalUsers: stats?.totalUsers ?? 0,
    };

    const formatNumber = (num: number) => new Intl.NumberFormat('id-ID').format(num);
    const formatPrice = (price: number) => new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
    }).format(price);

    const getFeatures = (features: unknown): string[] => {
        if (!features) return [];
        if (typeof features === 'string') {
            try {
                const parsed = JSON.parse(features);
                return Array.isArray(parsed) ? parsed.map((f: { name?: string }) => f?.name || String(f)) : [];
            } catch { return []; }
        }
        if (Array.isArray(features)) {
            return features.map((f: { name?: string }) => f?.name || String(f));
        }
        return [];
    };

    const scrollTo = (id: string) => {
        setMobileMenuOpen(false);
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    };

    const navItems = [
        { label: 'Fitur', id: 'features' },
        { label: 'Cara Kerja', id: 'how-it-works' },
        { label: 'Harga', id: 'pricing' },
        { label: 'Keamanan', id: 'security' },
    ];

    return (
        <>
            <Head title="SANTRIX - Sistem Manajemen Pesantren Modern" />

            <style>{`
                @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
                * { font-family: 'Inter', -apple-system, sans-serif; }
                html { scroll-behavior: smooth; }
                .gradient-text {
                    background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                }
                .glass { 
                    background: rgba(15, 23, 42, 0.8);
                    backdrop-filter: blur(12px);
                    border: 1px solid rgba(255,255,255,0.1);
                }
            `}</style>

            {/* Navbar */}
            <motion.nav
                className={`fixed top-0 left-0 right-0 z-50 transition-all duration-300 ${scrolled ? 'glass' : 'bg-transparent'}`}
                initial={{ y: -100 }}
                animate={{ y: 0 }}
                transition={{ duration: 0.5, ease }}
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-20">
                        {/* Logo */}
                        <Link href="/" className="flex flex-col">
                            <span className="text-2xl font-black text-white tracking-tight">SANTRIX</span>
                            <span className="text-[10px] text-slate-400 tracking-widest">by Velora</span>
                        </Link>

                        {/* Desktop Nav */}
                        <div className="hidden md:flex items-center gap-8">
                            {navItems.map((item) => (
                                <button
                                    key={item.id}
                                    onClick={() => scrollTo(item.id)}
                                    className={`text-sm font-medium transition-colors ${activeSection === item.id
                                        ? 'text-cyan-400'
                                        : 'text-slate-300 hover:text-white'
                                        }`}
                                >
                                    {item.label}
                                </button>
                            ))}
                        </div>

                        {/* CTA */}
                        <div className="hidden md:flex items-center gap-4">
                            <Link href="/login" className="text-sm text-slate-300 hover:text-white font-medium px-4 py-2">
                                Login
                            </Link>
                            <motion.div whileHover={{ scale: 1.03 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/register-pesantren"
                                    className="px-5 py-2.5 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-sm font-semibold rounded-lg shadow-lg shadow-cyan-500/25"
                                >
                                    Daftar Pesantren
                                </Link>
                            </motion.div>
                        </div>

                        {/* Mobile Menu */}
                        <button className="md:hidden text-white p-2" onClick={() => setMobileMenuOpen(!mobileMenuOpen)}>
                            {mobileMenuOpen ? Icons.close : Icons.menu}
                        </button>
                    </div>
                </div>

                <AnimatePresence>
                    {mobileMenuOpen && (
                        <motion.div
                            initial={{ opacity: 0, height: 0 }}
                            animate={{ opacity: 1, height: 'auto' }}
                            exit={{ opacity: 0, height: 0 }}
                            className="md:hidden glass border-t border-white/10"
                        >
                            <div className="px-4 py-6 space-y-3">
                                {navItems.map((item) => (
                                    <button
                                        key={item.id}
                                        onClick={() => scrollTo(item.id)}
                                        className={`block w-full text-left px-4 py-3 rounded-lg font-medium ${activeSection === item.id ? 'text-cyan-400 bg-white/5' : 'text-slate-300'
                                            }`}
                                    >
                                        {item.label}
                                    </button>
                                ))}
                                <hr className="border-white/10 my-4" />
                                <Link href="/login" className="block w-full px-4 py-3 text-slate-300 font-medium">Login</Link>
                                <Link href="/register-pesantren" className="block w-full px-4 py-3 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-center font-semibold rounded-lg">
                                    Daftar Pesantren
                                </Link>
                            </div>
                        </motion.div>
                    )}
                </AnimatePresence>
            </motion.nav>

            {/* Hero Section */}
            <section id="hero" ref={heroRef} className="relative min-h-screen flex items-center pt-20 overflow-hidden bg-slate-950">
                {/* Background */}
                <div className="absolute inset-0">
                    <div className="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-950 to-slate-900"></div>
                    <div className="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[128px]"></div>
                    <div className="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500/10 rounded-full blur-[128px]"></div>
                    <div className="absolute inset-0 opacity-20" style={{
                        backgroundImage: 'radial-gradient(circle at 1px 1px, rgba(255,255,255,0.15) 1px, transparent 0)',
                        backgroundSize: '40px 40px'
                    }}></div>
                </div>

                <motion.div
                    className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20"
                    style={{ y: heroY, opacity: heroOpacity }}
                >
                    <motion.div
                        className="text-center max-w-4xl mx-auto"
                        variants={stagger}
                        initial="hidden"
                        animate="visible"
                    >
                        {/* Badge */}
                        <motion.div variants={fadeUp} className="inline-flex items-center gap-2 px-4 py-2 bg-white/5 border border-white/10 rounded-full text-sm text-slate-300 mb-8">
                            <span className="flex h-2 w-2 relative">
                                <span className="animate-ping absolute h-2 w-2 rounded-full bg-cyan-400 opacity-75"></span>
                                <span className="h-2 w-2 rounded-full bg-cyan-500"></span>
                            </span>
                            Dipercaya {formatNumber(safeStats.totalPesantren)}+ pesantren
                        </motion.div>

                        {/* Headline */}
                        <motion.h1 variants={fadeUp} className="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                            Sistem Manajemen Pesantren
                            <span className="block gradient-text">Modern & Terintegrasi</span>
                        </motion.h1>

                        <motion.p variants={fadeUp} className="text-lg sm:text-xl text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                            Platform SaaS untuk mengelola administrasi santri, keuangan, dan pendidikan pesantren dalam satu sistem yang powerful.
                        </motion.p>

                        {/* CTA Buttons */}
                        <motion.div variants={fadeUp} className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16">
                            <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/register-pesantren"
                                    className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-gradient-to-r from-cyan-500 to-blue-500 text-white font-bold rounded-xl shadow-xl shadow-cyan-500/25 hover:shadow-cyan-500/40 transition-shadow"
                                >
                                    Daftar Pesantren {Icons.arrow}
                                </Link>
                            </motion.div>
                            <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                                <Link
                                    href="/login"
                                    className="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/5 text-white font-bold rounded-xl border border-white/10 hover:bg-white/10 transition-colors"
                                >
                                    Login
                                </Link>
                            </motion.div>
                        </motion.div>

                        {/* Stats */}
                        <motion.div variants={fadeUp} className="grid grid-cols-3 gap-6 max-w-lg mx-auto">
                            {[
                                { value: safeStats.totalPesantren, label: 'Pesantren' },
                                { value: safeStats.totalSantri, label: 'Santri' },
                                { value: safeStats.totalUsers, label: 'Pengguna' },
                            ].map((stat, i) => (
                                <motion.div
                                    key={i}
                                    className="text-center p-4 bg-white/5 rounded-xl border border-white/10"
                                    whileHover={{ scale: 1.05, backgroundColor: 'rgba(255,255,255,0.08)' }}
                                >
                                    <div className="text-2xl sm:text-3xl font-black text-white">{formatNumber(stat.value)}+</div>
                                    <div className="text-sm text-slate-400">{stat.label}</div>
                                </motion.div>
                            ))}
                        </motion.div>
                    </motion.div>
                </motion.div>

                {/* Scroll Indicator */}
                <motion.div
                    className="absolute bottom-8 left-1/2 -translate-x-1/2"
                    animate={{ y: [0, 8, 0] }}
                    transition={{ duration: 2, repeat: Infinity, ease: 'easeInOut' }}
                >
                    <div className="w-6 h-10 border-2 border-white/20 rounded-full flex justify-center pt-2">
                        <div className="w-1 h-2 bg-cyan-400 rounded-full"></div>
                    </div>
                </motion.div>
            </section>

            {/* Features Section */}
            <section id="features" className="py-24 bg-slate-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-cyan-500/10 text-cyan-400 text-sm font-medium rounded-full border border-cyan-500/20 mb-4">
                            Fitur Modular
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                            Tiga Modul Terintegrasi
                        </h2>
                        <p className="text-lg text-slate-400">
                            Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman, lebih produktif.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-3 gap-6"
                        variants={stagger}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {features.map((f, i) => (
                            <motion.div
                                key={i}
                                className="group relative bg-slate-800/50 rounded-2xl p-8 border border-white/5 hover:border-cyan-500/30 transition-all"
                                variants={fadeUp}
                                whileHover={{ y: -8, transition: { duration: 0.2 } }}
                            >
                                <div className={`w-14 h-14 bg-gradient-to-br ${f.color} rounded-xl flex items-center justify-center text-white mb-6 shadow-lg`}>
                                    {f.icon}
                                </div>
                                <h3 className="text-xl font-bold text-white mb-3">{f.title}</h3>
                                <p className="text-slate-400 leading-relaxed">{f.desc}</p>
                                <Link
                                    href={`/demo-start/${f.title.toLowerCase()}`}
                                    className="inline-flex items-center gap-2 mt-6 text-cyan-400 font-medium group-hover:gap-3 transition-all"
                                >
                                    Coba Demo {Icons.arrow}
                                </Link>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* How It Works */}
            <section id="how-it-works" className="py-24 bg-slate-950">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        variants={fadeUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-blue-500/10 text-blue-400 text-sm font-medium rounded-full border border-blue-500/20 mb-4">
                            Cara Kerja
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                            Mulai dalam 4 Langkah
                        </h2>
                        <p className="text-lg text-slate-400">
                            Proses sederhana untuk memulai digitalisasi pesantren Anda.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid sm:grid-cols-2 lg:grid-cols-4 gap-6"
                        variants={stagger}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                    >
                        {steps.map((step, i) => (
                            <motion.div
                                key={i}
                                className="relative p-6 bg-slate-800/30 rounded-2xl border border-white/5"
                                variants={fadeUp}
                            >
                                <span className="text-5xl font-black text-slate-700">{step.num}</span>
                                <h3 className="text-xl font-bold text-white mt-4 mb-2">{step.title}</h3>
                                <p className="text-slate-400">{step.desc}</p>
                                {i < steps.length - 1 && (
                                    <div className="hidden lg:block absolute top-1/2 -right-3 w-6 h-[2px] bg-gradient-to-r from-cyan-500 to-transparent"></div>
                                )}
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Pricing Section */}
            <section id="pricing" className="py-24 bg-slate-900">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        variants={fadeUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-emerald-500/10 text-emerald-400 text-sm font-medium rounded-full border border-emerald-500/20 mb-4">
                            Harga Transparan
                        </span>
                        <h2 className="text-3xl sm:text-4xl font-black text-white mb-4">
                            Pilih Paket Sesuai Kebutuhan
                        </h2>
                        <p className="text-lg text-slate-400">
                            Investasi kecil untuk efisiensi besar dalam pengelolaan pesantren.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-2 lg:grid-cols-4 gap-6"
                        variants={stagger}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                    >
                        {packages.map((pkg, i) => (
                            <motion.div
                                key={pkg.id}
                                className={`relative bg-slate-800/50 rounded-2xl overflow-hidden border ${i === 1 ? 'border-cyan-500 ring-1 ring-cyan-500/50' : 'border-white/10'}`}
                                variants={fadeUp}
                                whileHover={{ y: -8, transition: { duration: 0.2 } }}
                            >
                                {i === 1 && (
                                    <div className="absolute top-0 left-0 right-0 bg-gradient-to-r from-cyan-500 to-blue-500 text-white text-xs font-bold text-center py-1.5">
                                        REKOMENDASI
                                    </div>
                                )}
                                <div className={`p-6 ${i === 1 ? 'pt-10' : ''}`}>
                                    <h3 className="text-xl font-bold text-white mb-2">{pkg.name}</h3>
                                    <div className="text-3xl font-black text-cyan-400 mb-1">
                                        {formatPrice(pkg.price)}
                                    </div>
                                    <div className="text-sm text-slate-400 mb-6">/ {pkg.duration_months} bulan</div>

                                    <ul className="space-y-3 mb-6">
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            <span className="text-cyan-400">{Icons.check}</span>
                                            Maks {pkg.max_santri.toLocaleString()} Santri
                                        </li>
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            <span className="text-cyan-400">{Icons.check}</span>
                                            Maks {pkg.max_users} User
                                        </li>
                                        {getFeatures(pkg.features).slice(0, 3).map((feat, fi) => (
                                            <li key={fi} className="flex items-center gap-2 text-slate-300 text-sm">
                                                <span className="text-cyan-400">{Icons.check}</span>
                                                {feat}
                                            </li>
                                        ))}
                                    </ul>

                                    <motion.div whileHover={{ scale: 1.02 }} whileTap={{ scale: 0.98 }}>
                                        <Link
                                            href="/register-pesantren"
                                            className={`block w-full py-3 text-center font-bold rounded-xl transition-all ${i === 1
                                                ? 'bg-gradient-to-r from-cyan-500 to-blue-500 text-white shadow-lg shadow-cyan-500/25'
                                                : 'bg-white/5 text-white hover:bg-white/10 border border-white/10'
                                                }`}
                                        >
                                            Pilih Paket
                                        </Link>
                                    </motion.div>
                                </div>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Security Section */}
            <section id="security" className="py-24 bg-slate-950">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid lg:grid-cols-2 gap-16 items-center">
                        <motion.div
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                            variants={fadeUp}
                        >
                            <span className="inline-block px-4 py-1.5 bg-emerald-500/10 text-emerald-400 text-sm font-medium rounded-full border border-emerald-500/20 mb-4">
                                Keamanan
                            </span>
                            <h2 className="text-3xl sm:text-4xl font-black text-white mb-6">
                                Data Anda Aman Bersama Kami
                            </h2>
                            <p className="text-slate-400 mb-8 leading-relaxed">
                                Sebagai platform SaaS profesional, kami memastikan setiap data pesantren terisolasi dan terlindungi dengan standar keamanan tinggi.
                            </p>

                            <div className="space-y-6">
                                {securityFeatures.map((f, i) => (
                                    <motion.div
                                        key={i}
                                        className="flex items-start gap-4"
                                        variants={fadeUp}
                                    >
                                        <div className="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-cyan-400 shrink-0">
                                            {f.icon}
                                        </div>
                                        <div>
                                            <h4 className="text-white font-bold mb-1">{f.title}</h4>
                                            <p className="text-slate-400 text-sm">{f.desc}</p>
                                        </div>
                                    </motion.div>
                                ))}
                            </div>
                        </motion.div>

                        {/* Testimonials */}
                        <motion.div
                            className="space-y-6"
                            variants={stagger}
                            initial="hidden"
                            whileInView="visible"
                            viewport={{ once: true }}
                        >
                            {testimonials.map((t, i) => (
                                <motion.div
                                    key={i}
                                    className="bg-slate-800/50 rounded-2xl p-6 border border-white/5"
                                    variants={fadeUp}
                                    whileHover={{ x: 8, transition: { duration: 0.2 } }}
                                >
                                    <div className="flex gap-1 mb-4">
                                        {[...Array(5)].map((_, si) => <span key={si}>{Icons.star}</span>)}
                                    </div>
                                    <p className="text-slate-300 mb-4">"{t.text}"</p>
                                    <div className="flex items-center gap-3">
                                        <div className="w-10 h-10 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                            {t.avatar}
                                        </div>
                                        <div>
                                            <div className="font-semibold text-white text-sm">{t.name}</div>
                                            <div className="text-xs text-slate-400">{t.role}</div>
                                        </div>
                                    </div>
                                </motion.div>
                            ))}
                        </motion.div>
                    </div>
                </div>
            </section>

            {/* CTA Section */}
            <section id="cta" className="py-24 bg-gradient-to-r from-cyan-600 to-blue-600">
                <motion.div
                    className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center"
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    variants={fadeUp}
                >
                    <h2 className="text-3xl sm:text-4xl font-black text-white mb-6">
                        Siap Modernisasi Pesantren Anda?
                    </h2>
                    <p className="text-xl text-white/90 mb-10 max-w-2xl mx-auto">
                        Bergabung dengan ratusan pesantren yang telah merasakan kemudahan mengelola administrasi dengan SANTRIX.
                    </p>
                    <div className="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                            <Link
                                href="/register-pesantren"
                                className="px-10 py-4 bg-white text-slate-900 font-bold rounded-xl shadow-xl hover:shadow-2xl transition-all inline-flex items-center gap-2"
                            >
                                Daftar Sekarang {Icons.arrow}
                            </Link>
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.03, y: -2 }} whileTap={{ scale: 0.97 }}>
                            <a
                                href="https://wa.me/6281234567890"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="px-10 py-4 bg-white/10 backdrop-blur text-white font-bold rounded-xl border-2 border-white/30 hover:bg-white/20 transition-all"
                            >
                                Hubungi Sales
                            </a>
                        </motion.div>
                    </div>
                </motion.div>
            </section>

            {/* Footer */}
            <footer className="bg-slate-950 text-white pt-20 pb-8 border-t border-white/5">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid md:grid-cols-4 gap-12 mb-12">
                        <div className="col-span-2">
                            <div className="flex flex-col mb-6">
                                <span className="text-2xl font-black text-white">SANTRIX</span>
                                <span className="text-xs text-slate-500 tracking-widest">by Velora</span>
                            </div>
                            <p className="text-slate-400 max-w-md mb-6 leading-relaxed">
                                Platform SaaS manajemen pesantren modern yang membantu ribuan pesantren di Indonesia mengelola administrasi dengan lebih efisien.
                            </p>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Produk</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li><button onClick={() => scrollTo('features')} className="hover:text-cyan-400 transition-colors">Fitur</button></li>
                                <li><button onClick={() => scrollTo('pricing')} className="hover:text-cyan-400 transition-colors">Harga</button></li>
                                <li><Link href="/demo-start/sekretaris" className="hover:text-cyan-400 transition-colors">Demo</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Kontak</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li>support@santrix.my.id</li>
                                <li>+62 812-3456-7890</li>
                                <li>Indonesia</li>
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p className="text-slate-500 text-sm">© 2025 SANTRIX by Velora. All rights reserved.</p>
                        <p className="text-slate-500 text-sm">Made with ❤️ for Pesantren Indonesia</p>
                    </div>
                </div>
            </footer>
        </>
    );
}
