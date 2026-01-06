import { Head, Link } from '@inertiajs/react';
import { motion, useScroll, useTransform } from 'framer-motion';
import { useRef } from 'react';

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

// Animation variants
const fadeInUp = {
    hidden: { opacity: 0, y: 60 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.6, ease: 'easeOut' } }
};

const fadeIn = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { duration: 0.8 } }
};

const staggerContainer = {
    hidden: { opacity: 0 },
    visible: {
        opacity: 1,
        transition: { staggerChildren: 0.15 }
    }
};

const scaleIn = {
    hidden: { opacity: 0, scale: 0.8 },
    visible: { opacity: 1, scale: 1, transition: { duration: 0.5, ease: 'easeOut' } }
};

// Islamic geometric pattern SVG
const IslamicPattern = () => (
    <svg className="absolute inset-0 w-full h-full opacity-[0.03]" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="islamic-pattern" x="0" y="0" width="80" height="80" patternUnits="userSpaceOnUse">
                <path d="M40 0L80 40L40 80L0 40Z" fill="none" stroke="currentColor" strokeWidth="0.5" />
                <circle cx="40" cy="40" r="15" fill="none" stroke="currentColor" strokeWidth="0.5" />
                <path d="M40 25L55 40L40 55L25 40Z" fill="none" stroke="currentColor" strokeWidth="0.5" />
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#islamic-pattern)" className="text-indigo-600" />
    </svg>
);

// Mosque silhouette decoration
const MosqueSilhouette = () => (
    <svg viewBox="0 0 200 100" className="w-full h-auto opacity-10" fill="currentColor">
        <path d="M100 10C100 10 90 20 90 30V50H60V70H40V100H160V70H140V50H110V30C110 20 100 10 100 10Z" />
        <path d="M20 60V100H30V60C30 50 20 50 20 60Z" />
        <path d="M170 60V100H180V60C180 50 170 50 170 60Z" />
        <circle cx="100" cy="35" r="8" />
    </svg>
);

const dashboardFeatures = [
    {
        icon: '👥',
        title: 'Sekretaris & TU',
        subtitle: 'Administrasi & Data Santri',
        description: 'Kelola data lengkap santri, asrama, mutasi, perizinan, dan alumni dalam satu sistem terintegrasi.',
        tags: ['Data Santri', 'Asrama', 'Mutasi', 'Perizinan', 'Alumni'],
        color: 'from-violet-500 to-indigo-600',
        lightColor: 'from-violet-50 to-indigo-50',
        href: '/demo-start/sekretaris',
    },
    {
        icon: '💰',
        title: 'Bendahara',
        subtitle: 'Keuangan & Syahriah',
        description: 'Tracking pembayaran SPP real-time, laporan keuangan otomatis, dan integrasi notifikasi WhatsApp.',
        tags: ['SPP', 'Pemasukan', 'Pengeluaran', 'Tabungan', 'Gaji'],
        color: 'from-emerald-500 to-teal-600',
        lightColor: 'from-emerald-50 to-teal-50',
        href: '/demo-start/bendahara',
    },
    {
        icon: '📚',
        title: 'Pendidikan',
        subtitle: 'Akademik & Tahfidz',
        description: 'Sistem akademik lengkap dari input nilai, absensi, hafalan Al-Quran, hingga cetak rapor otomatis.',
        tags: ['Nilai', 'Absensi', 'Hafalan', 'Jurnal KBM', 'Rapor'],
        color: 'from-sky-500 to-blue-600',
        lightColor: 'from-sky-50 to-blue-50',
        href: '/demo-start/pendidikan',
    },
    {
        icon: '🛡️',
        title: 'Admin & Yayasan',
        subtitle: 'Kontrol & Pengaturan',
        description: 'Kelola pengguna, backup data, kustomisasi branding, dan integrasi WhatsApp Business API.',
        tags: ['User', 'Backup', 'Branding', 'WhatsApp', 'Laporan'],
        color: 'from-slate-600 to-slate-800',
        lightColor: 'from-slate-50 to-gray-50',
        href: '/demo-start/admin',
    },
];

const testimonials = [
    {
        name: 'Ust. Ahmad Fauzi',
        role: 'Mudir PP. Riyadlul Huda',
        text: 'Santrix sangat membantu kami mengelola data santri dan keuangan pesantren. Semuanya jadi lebih rapi dan terorganisir. Alhamdulillah!',
        avatar: 'A',
    },
    {
        name: 'Ust. Muhammad Rizki',
        role: 'Kepala TU PP. Darul Ulum',
        text: 'Fitur laporan otomatis sangat menghemat waktu kami. Sekarang tidak perlu lagi input manual ke Excel setiap bulan.',
        avatar: 'M',
    },
    {
        name: 'Ustadzah Siti Aminah',
        role: 'Bendahara PP. Nurul Hikmah',
        text: 'Tracking pembayaran SPP jadi mudah. Wali santri juga bisa cek tagihan langsung via WhatsApp. Barakallah!',
        avatar: 'S',
    },
];

export default function Welcome({ stats, packages }: WelcomeProps) {
    const heroRef = useRef(null);
    const { scrollYProgress } = useScroll({
        target: heroRef,
        offset: ['start start', 'end start']
    });

    const heroY = useTransform(scrollYProgress, [0, 1], ['0%', '50%']);
    const heroOpacity = useTransform(scrollYProgress, [0, 0.5], [1, 0]);

    const safeStats = {
        totalPesantren: stats?.totalPesantren ?? 0,
        totalSantri: stats?.totalSantri ?? 0,
        totalUsers: stats?.totalUsers ?? 0,
    };

    const formatNumber = (num: number) => new Intl.NumberFormat('id-ID').format(num);

    const formatPrice = (price: number) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(price);

    const getFeatures = (features: unknown): string[] => {
        if (!features) return [];
        if (typeof features === 'string') {
            try {
                const parsed = JSON.parse(features);
                if (Array.isArray(parsed)) {
                    return parsed.map((f: unknown) =>
                        typeof f === 'object' && f !== null && 'name' in f
                            ? String((f as { name: unknown }).name)
                            : String(f)
                    );
                }
                return [];
            } catch {
                return [];
            }
        }
        if (Array.isArray(features)) {
            return features.map((f: unknown) =>
                typeof f === 'object' && f !== null && 'name' in f
                    ? String((f as { name: unknown }).name)
                    : String(f)
            );
        }
        return [];
    };

    return (
        <>
            <Head title="Sistem Manajemen Pesantren Modern" />

            {/* Floating Navbar with Glassmorphism */}
            <motion.nav
                className="fixed top-0 left-0 right-0 z-50"
                initial={{ y: -100 }}
                animate={{ y: 0 }}
                transition={{ duration: 0.6, ease: 'easeOut' }}
            >
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
                    <div className="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-lg shadow-indigo-500/5 px-6 py-3">
                        <div className="flex justify-between items-center">
                            <motion.div
                                className="flex items-center gap-3"
                                whileHover={{ scale: 1.02 }}
                            >
                                <div className="relative">
                                    <div className="w-11 h-11 bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                        <span className="text-white font-bold text-xl">S</span>
                                    </div>
                                    <div className="absolute -inset-1 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl blur opacity-30 -z-10"></div>
                                </div>
                                <span className="text-xl font-extrabold bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent">
                                    Santrix
                                </span>
                            </motion.div>

                            <div className="hidden md:flex items-center gap-8">
                                {['Fitur', 'Harga', 'Testimoni', 'Kontak'].map((item) => (
                                    <motion.a
                                        key={item}
                                        href={`#${item.toLowerCase()}`}
                                        className="text-slate-600 hover:text-indigo-600 font-medium transition-colors relative group"
                                        whileHover={{ y: -2 }}
                                    >
                                        {item}
                                        <span className="absolute -bottom-1 left-0 w-0 h-0.5 bg-gradient-to-r from-indigo-600 to-violet-600 group-hover:w-full transition-all duration-300"></span>
                                    </motion.a>
                                ))}
                            </div>

                            <div className="flex items-center gap-3">
                                <Link
                                    href="/login"
                                    className="px-4 py-2 text-slate-600 hover:text-indigo-600 font-medium transition-colors"
                                >
                                    Masuk
                                </Link>
                                <motion.div whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.95 }}>
                                    <Link
                                        href="/register-pesantren"
                                        className="relative px-6 py-2.5 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 text-white font-semibold rounded-xl overflow-hidden group"
                                    >
                                        <span className="relative z-10">Daftar Gratis</span>
                                        <div className="absolute inset-0 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </Link>
                                </motion.div>
                            </div>
                        </div>
                    </div>
                </div>
            </motion.nav>

            {/* Hero Section with Parallax */}
            <section ref={heroRef} className="relative min-h-screen flex items-center pt-24 overflow-hidden">
                {/* Animated Background */}
                <div className="absolute inset-0 bg-gradient-to-br from-slate-50 via-white to-indigo-50">
                    <IslamicPattern />
                </div>

                {/* Floating Orbs */}
                <motion.div
                    className="absolute top-20 left-10 w-72 h-72 bg-gradient-to-br from-violet-400 to-indigo-400 rounded-full blur-3xl"
                    animate={{
                        y: [0, -30, 0],
                        opacity: [0.2, 0.3, 0.2]
                    }}
                    transition={{ duration: 6, repeat: Infinity, ease: 'easeInOut' }}
                />
                <motion.div
                    className="absolute bottom-20 right-10 w-96 h-96 bg-gradient-to-br from-indigo-400 to-purple-400 rounded-full blur-3xl"
                    animate={{
                        y: [0, 30, 0],
                        opacity: [0.2, 0.35, 0.2]
                    }}
                    transition={{ duration: 8, repeat: Infinity, ease: 'easeInOut' }}
                />
                <motion.div
                    className="absolute top-1/2 left-1/3 w-64 h-64 bg-gradient-to-br from-emerald-300 to-teal-300 rounded-full blur-3xl"
                    animate={{
                        x: [0, 20, 0],
                        opacity: [0.15, 0.25, 0.15]
                    }}
                    transition={{ duration: 7, repeat: Infinity, ease: 'easeInOut' }}
                />

                <motion.div
                    className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16"
                    style={{ y: heroY, opacity: heroOpacity }}
                >
                    <motion.div
                        className="text-center max-w-4xl mx-auto"
                        variants={staggerContainer}
                        initial="hidden"
                        animate="visible"
                    >
                        {/* Animated Badge */}
                        <motion.div
                            variants={fadeInUp}
                            className="inline-flex items-center gap-2 px-5 py-2.5 bg-white/80 backdrop-blur-sm rounded-full text-indigo-700 font-medium text-sm mb-8 border border-indigo-100 shadow-lg shadow-indigo-500/10"
                        >
                            <span className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            ☪️ Digunakan oleh {formatNumber(safeStats.totalPesantren)}+ Pesantren di Indonesia
                        </motion.div>

                        {/* Main Headline with Gradient */}
                        <motion.h1
                            variants={fadeInUp}
                            className="text-4xl sm:text-5xl lg:text-7xl font-black text-slate-900 leading-tight mb-6"
                        >
                            Kelola Pesantren
                            <span className="block mt-2 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 bg-clip-text text-transparent animate-gradient-x">
                                Lebih Modern & Berkah
                            </span>
                        </motion.h1>

                        <motion.p
                            variants={fadeInUp}
                            className="text-lg sm:text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed"
                        >
                            Platform manajemen pesantren terlengkap dengan sentuhan Islami.
                            Dari administrasi santri, keuangan, hingga pendidikan — <strong>semua terintegrasi</strong>.
                        </motion.p>

                        {/* CTA Buttons */}
                        <motion.div
                            variants={fadeInUp}
                            className="flex flex-col sm:flex-row items-center justify-center gap-4 mb-16"
                        >
                            <motion.div whileHover={{ scale: 1.05, y: -3 }} whileTap={{ scale: 0.95 }}>
                                <Link
                                    href="/register-pesantren"
                                    className="relative w-full sm:w-auto px-10 py-4 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 text-white font-bold rounded-2xl overflow-hidden group shadow-xl shadow-indigo-500/30"
                                >
                                    <span className="relative z-10 flex items-center gap-2">
                                        🚀 Mulai Gratis Sekarang
                                    </span>
                                    <motion.div
                                        className="absolute inset-0 bg-gradient-to-r from-purple-600 via-violet-600 to-indigo-600"
                                        initial={{ x: '100%' }}
                                        whileHover={{ x: 0 }}
                                        transition={{ duration: 0.3 }}
                                    />
                                </Link>
                            </motion.div>
                            <motion.div whileHover={{ scale: 1.05, y: -3 }} whileTap={{ scale: 0.95 }}>
                                <Link
                                    href="/demo-start/sekretaris"
                                    className="w-full sm:w-auto px-10 py-4 bg-white/80 backdrop-blur-sm text-slate-700 font-bold rounded-2xl border-2 border-slate-200 hover:border-indigo-300 shadow-lg transition-all flex items-center gap-2"
                                >
                                    🎯 Coba Demo Interaktif
                                </Link>
                            </motion.div>
                        </motion.div>

                        {/* Animated Stats */}
                        <motion.div
                            variants={fadeInUp}
                            className="grid grid-cols-3 gap-4 sm:gap-8 max-w-xl mx-auto"
                        >
                            {[
                                { value: safeStats.totalPesantren, label: 'Pesantren', color: 'from-indigo-500 to-violet-500', icon: '🕌' },
                                { value: safeStats.totalSantri, label: 'Santri', color: 'from-violet-500 to-purple-500', icon: '👤' },
                                { value: safeStats.totalUsers, label: 'Pengguna', color: 'from-purple-500 to-pink-500', icon: '👥' },
                            ].map((stat, index) => (
                                <motion.div
                                    key={index}
                                    className="text-center p-4 bg-white/60 backdrop-blur-sm rounded-2xl border border-white/50 shadow-lg"
                                    whileHover={{ y: -5, scale: 1.05 }}
                                    transition={{ type: 'spring', stiffness: 300 }}
                                >
                                    <div className="text-2xl mb-1">{stat.icon}</div>
                                    <div className={`text-2xl sm:text-3xl font-black bg-gradient-to-r ${stat.color} bg-clip-text text-transparent`}>
                                        {formatNumber(stat.value)}+
                                    </div>
                                    <div className="text-xs sm:text-sm text-slate-500 font-medium">{stat.label}</div>
                                </motion.div>
                            ))}
                        </motion.div>
                    </motion.div>
                </motion.div>

                {/* Scroll Indicator */}
                <motion.div
                    className="absolute bottom-8 left-1/2 -translate-x-1/2"
                    animate={{ y: [0, 10, 0] }}
                    transition={{ duration: 2, repeat: Infinity }}
                >
                    <div className="w-6 h-10 border-2 border-slate-300 rounded-full flex items-start justify-center p-1">
                        <motion.div
                            className="w-1.5 h-3 bg-gradient-to-b from-indigo-500 to-violet-500 rounded-full"
                            animate={{ y: [0, 12, 0] }}
                            transition={{ duration: 2, repeat: Infinity }}
                        />
                    </div>
                </motion.div>
            </section>

            {/* Dashboard Preview Section */}
            <section className="py-24 bg-gradient-to-b from-slate-50 via-white to-indigo-50/30 relative overflow-hidden">
                <IslamicPattern />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 text-sm font-semibold rounded-full mb-4">
                            ✨ Preview Dashboard
                        </span>
                        <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4">
                            Interface Modern &{' '}
                            <span className="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">
                                Intuitif
                            </span>
                        </h2>
                        <p className="text-lg text-slate-600">
                            Dashboard yang bersih, mudah dipahami, dan powerful untuk mengelola seluruh aspek pesantren Anda.
                        </p>
                    </motion.div>

                    {/* Dashboard Mockup with 3D Effect */}
                    <motion.div
                        className="relative max-w-5xl mx-auto perspective-1000"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                        variants={scaleIn}
                    >
                        <motion.div
                            className="relative"
                            whileHover={{ rotateX: -2, rotateY: 2, scale: 1.02 }}
                            transition={{ type: 'spring', stiffness: 200 }}
                        >
                            {/* Glow Effect */}
                            <div className="absolute -inset-4 bg-gradient-to-r from-indigo-500 via-violet-500 to-purple-500 rounded-3xl blur-2xl opacity-20"></div>

                            {/* Browser Frame */}
                            <div className="relative bg-gradient-to-br from-indigo-600 via-violet-600 to-purple-600 rounded-2xl p-1 shadow-2xl">
                                <div className="bg-slate-900 rounded-xl overflow-hidden">
                                    {/* Browser Top Bar */}
                                    <div className="bg-slate-800 px-4 py-3 flex items-center gap-3">
                                        <div className="flex gap-2">
                                            <div className="w-3 h-3 rounded-full bg-red-500 shadow-lg shadow-red-500/50"></div>
                                            <div className="w-3 h-3 rounded-full bg-yellow-500 shadow-lg shadow-yellow-500/50"></div>
                                            <div className="w-3 h-3 rounded-full bg-green-500 shadow-lg shadow-green-500/50"></div>
                                        </div>
                                        <div className="flex-1 flex items-center gap-2 bg-slate-700/50 rounded-lg px-4 py-2">
                                            <svg className="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            <span className="text-slate-400 text-sm">santrix.my.id/dashboard</span>
                                        </div>
                                    </div>

                                    {/* Dashboard Content */}
                                    <div className="bg-gradient-to-br from-slate-100 to-slate-50 p-6">
                                        {/* Header */}
                                        <div className="flex items-center justify-between mb-6">
                                            <div>
                                                <h3 className="text-slate-900 font-bold text-lg">Dashboard Admin</h3>
                                                <p className="text-slate-500 text-sm">PP. Riyadlul Huda</p>
                                            </div>
                                            <div className="flex items-center gap-3">
                                                <div className="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">🔔</div>
                                                <div className="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-violet-500"></div>
                                            </div>
                                        </div>

                                        {/* Stats Grid */}
                                        <div className="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                                            {[
                                                { label: 'Total Santri', value: '245', icon: '👥', change: '+12%', color: 'from-indigo-500 to-violet-500' },
                                                { label: 'Pemasukan', value: 'Rp 45.2jt', icon: '💰', change: '+8%', color: 'from-emerald-500 to-teal-500' },
                                                { label: 'Kehadiran', value: '98.5%', icon: '📊', change: '+2%', color: 'from-violet-500 to-purple-500' },
                                                { label: 'Kelas Aktif', value: '15', icon: '📚', change: '0%', color: 'from-orange-500 to-amber-500' },
                                            ].map((stat, index) => (
                                                <motion.div
                                                    key={index}
                                                    className="bg-white rounded-xl p-4 shadow-sm border border-slate-100"
                                                    initial={{ opacity: 0, y: 20 }}
                                                    whileInView={{ opacity: 1, y: 0 }}
                                                    transition={{ delay: index * 0.1 }}
                                                    viewport={{ once: true }}
                                                >
                                                    <div className="flex items-center justify-between mb-2">
                                                        <span className="text-2xl">{stat.icon}</span>
                                                        <span className="text-xs font-medium text-green-600 bg-green-50 px-2 py-0.5 rounded-full">{stat.change}</span>
                                                    </div>
                                                    <div className={`text-xl font-bold bg-gradient-to-r ${stat.color} bg-clip-text text-transparent`}>{stat.value}</div>
                                                    <div className="text-xs text-slate-500">{stat.label}</div>
                                                </motion.div>
                                            ))}
                                        </div>

                                        {/* Chart Placeholder */}
                                        <div className="grid grid-cols-3 gap-4">
                                            <div className="col-span-2 bg-white rounded-xl p-4 shadow-sm border border-slate-100 h-40">
                                                <div className="text-sm font-semibold text-slate-700 mb-3">Grafik Pembayaran SPP</div>
                                                <div className="flex items-end gap-2 h-24">
                                                    {[40, 65, 45, 80, 55, 90, 70, 85, 60, 95, 75, 88].map((h, i) => (
                                                        <motion.div
                                                            key={i}
                                                            className="flex-1 bg-gradient-to-t from-indigo-500 to-violet-400 rounded-t-sm"
                                                            style={{ height: `${h}%` }}
                                                            initial={{ height: 0 }}
                                                            whileInView={{ height: `${h}%` }}
                                                            transition={{ delay: i * 0.05, duration: 0.5 }}
                                                            viewport={{ once: true }}
                                                        />
                                                    ))}
                                                </div>
                                            </div>
                                            <div className="bg-white rounded-xl p-4 shadow-sm border border-slate-100">
                                                <div className="text-sm font-semibold text-slate-700 mb-3">Aktivitas Terbaru</div>
                                                <div className="space-y-2">
                                                    {['Pembayaran SPP', 'Santri Baru', 'Absensi'].map((item, i) => (
                                                        <div key={i} className="flex items-center gap-2 text-xs text-slate-500">
                                                            <div className="w-1.5 h-1.5 rounded-full bg-indigo-500"></div>
                                                            {item}
                                                        </div>
                                                    ))}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </motion.div>
                    </motion.div>
                </div>
            </section>

            {/* Features Section */}
            <section id="fitur" className="py-24 bg-white relative overflow-hidden">
                <IslamicPattern />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-emerald-100 text-emerald-700 text-sm font-semibold rounded-full mb-4">
                            🏛️ Fitur Lengkap
                        </span>
                        <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4">
                            4 Dashboard{' '}
                            <span className="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">
                                Profesional
                            </span>
                        </h2>
                        <p className="text-lg text-slate-600">
                            Akses terpisah untuk setiap divisi. Lebih fokus, lebih aman, lebih produktif.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-2 gap-6 lg:gap-8"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {dashboardFeatures.map((feature, index) => (
                            <motion.a
                                key={feature.title}
                                href={feature.href}
                                className="group block"
                                variants={fadeInUp}
                                whileHover={{ y: -8, scale: 1.02 }}
                                transition={{ type: 'spring', stiffness: 300 }}
                            >
                                <div className={`relative bg-gradient-to-br ${feature.lightColor} rounded-3xl border border-white/50 shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden p-8`}>
                                    {/* Decorative Corner */}
                                    <div className={`absolute top-0 right-0 w-32 h-32 bg-gradient-to-br ${feature.color} opacity-10 rounded-bl-full`}></div>

                                    <div className="flex items-start gap-5">
                                        <motion.div
                                            className={`w-16 h-16 rounded-2xl bg-gradient-to-br ${feature.color} flex items-center justify-center shadow-lg`}
                                            whileHover={{ rotate: [0, -10, 10, 0], scale: 1.1 }}
                                            transition={{ duration: 0.5 }}
                                        >
                                            <span className="text-3xl">{feature.icon}</span>
                                        </motion.div>
                                        <div className="flex-1">
                                            <h3 className="text-xl font-bold text-slate-800 mb-1 group-hover:text-indigo-700 transition-colors">
                                                {feature.title}
                                            </h3>
                                            <p className="text-slate-500 text-sm font-medium mb-3">
                                                {feature.subtitle}
                                            </p>
                                            <p className="text-slate-600 text-sm leading-relaxed mb-4">
                                                {feature.description}
                                            </p>
                                            <div className="flex flex-wrap gap-2">
                                                {feature.tags.map((tag) => (
                                                    <span
                                                        key={tag}
                                                        className="px-3 py-1 bg-white/80 text-xs font-medium text-slate-600 rounded-full border border-slate-200"
                                                    >
                                                        {tag}
                                                    </span>
                                                ))}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mt-6 pt-5 border-t border-slate-200/50 flex items-center justify-between">
                                        <span className="text-sm text-slate-500">Klik untuk mencoba demo</span>
                                        <motion.div
                                            className="flex items-center gap-2 text-indigo-600 font-semibold"
                                            whileHover={{ x: 5 }}
                                        >
                                            Masuk <span className="text-lg">→</span>
                                        </motion.div>
                                    </div>
                                </div>
                            </motion.a>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* Pricing Section */}
            <section id="harga" className="py-24 bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 relative overflow-hidden">
                {/* Background Decorations */}
                <div className="absolute inset-0 opacity-20">
                    <IslamicPattern />
                </div>
                <motion.div
                    className="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500 rounded-full blur-3xl opacity-10"
                    animate={{ y: [0, 50, 0] }}
                    transition={{ duration: 10, repeat: Infinity }}
                />
                <motion.div
                    className="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500 rounded-full blur-3xl opacity-10"
                    animate={{ y: [0, -50, 0] }}
                    transition={{ duration: 12, repeat: Infinity }}
                />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-indigo-500/20 text-indigo-300 text-sm font-semibold rounded-full mb-4 border border-indigo-500/30">
                            💎 Harga Terjangkau
                        </span>
                        <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-4">
                            Pilih Paket{' '}
                            <span className="bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                                Terbaik
                            </span>
                        </h2>
                        <p className="text-lg text-slate-400">
                            Investasi kecil untuk kemudahan besar dalam mengelola pesantren Anda.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-2 lg:grid-cols-4 gap-6"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {packages.map((pkg, index) => (
                            <motion.div
                                key={pkg.id}
                                className={`relative bg-white/5 backdrop-blur-sm rounded-3xl border ${index === 1 ? 'border-indigo-500 ring-2 ring-indigo-500/50' : 'border-white/10'} overflow-hidden`}
                                variants={fadeInUp}
                                whileHover={{ y: -10, scale: 1.03 }}
                                transition={{ type: 'spring', stiffness: 300 }}
                            >
                                {index === 1 && (
                                    <div className="absolute top-0 left-0 right-0 bg-gradient-to-r from-indigo-500 to-violet-500 text-white text-xs font-bold text-center py-1.5">
                                        ⭐ PALING POPULER
                                    </div>
                                )}
                                <div className={`p-6 ${index === 1 ? 'pt-10' : ''}`}>
                                    <div className="text-center mb-6">
                                        <h3 className="text-xl font-bold text-white mb-2">{pkg.name}</h3>
                                        <div className="text-3xl font-black bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent">
                                            {formatPrice(pkg.price)}
                                        </div>
                                        <div className="text-sm text-slate-400">/ {pkg.duration_months} bulan</div>
                                    </div>
                                    <ul className="space-y-3 mb-6">
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            <span className="text-emerald-400">✓</span>
                                            Maks {pkg.max_santri.toLocaleString()} Santri
                                        </li>
                                        <li className="flex items-center gap-2 text-slate-300 text-sm">
                                            <span className="text-emerald-400">✓</span>
                                            Maks {pkg.max_users} User
                                        </li>
                                        {getFeatures(pkg.features).slice(0, 3).map((feature) => (
                                            <li key={feature} className="flex items-center gap-2 text-slate-300 text-sm">
                                                <span className="text-emerald-400">✓</span>
                                                {feature}
                                            </li>
                                        ))}
                                    </ul>
                                    <motion.div whileHover={{ scale: 1.05 }} whileTap={{ scale: 0.95 }}>
                                        <Link
                                            href="/register-pesantren"
                                            className={`block w-full py-3 text-center font-bold rounded-xl transition-all ${index === 1
                                                    ? 'bg-gradient-to-r from-indigo-500 to-violet-500 text-white shadow-lg shadow-indigo-500/30'
                                                    : 'bg-white/10 text-white hover:bg-white/20'
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

            {/* Testimonials Section */}
            <section id="testimoni" className="py-24 bg-gradient-to-b from-white to-slate-50 relative overflow-hidden">
                <IslamicPattern />

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <motion.div
                        className="text-center max-w-3xl mx-auto mb-16"
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-100px' }}
                        variants={fadeInUp}
                    >
                        <span className="inline-block px-4 py-1.5 bg-amber-100 text-amber-700 text-sm font-semibold rounded-full mb-4">
                            ⭐ Testimoni
                        </span>
                        <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4">
                            Dipercaya{' '}
                            <span className="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                                Pesantren Nusantara
                            </span>
                        </h2>
                        <p className="text-lg text-slate-600">
                            Cerita sukses dari pesantren yang telah menggunakan Santrix.
                        </p>
                    </motion.div>

                    <motion.div
                        className="grid md:grid-cols-3 gap-8"
                        variants={staggerContainer}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true, margin: '-50px' }}
                    >
                        {testimonials.map((testimonial, index) => (
                            <motion.div
                                key={index}
                                className="relative bg-white rounded-3xl p-8 border border-slate-100 shadow-lg hover:shadow-xl transition-shadow overflow-hidden"
                                variants={fadeInUp}
                                whileHover={{ y: -5 }}
                            >
                                {/* Decorative Quote */}
                                <div className="absolute top-4 right-4 text-6xl text-slate-100 font-serif">"</div>

                                <div className="flex items-center gap-1 mb-4">
                                    {[...Array(5)].map((_, i) => (
                                        <motion.span
                                            key={i}
                                            className="text-amber-400 text-lg"
                                            initial={{ opacity: 0, scale: 0 }}
                                            whileInView={{ opacity: 1, scale: 1 }}
                                            transition={{ delay: i * 0.1 }}
                                            viewport={{ once: true }}
                                        >
                                            ★
                                        </motion.span>
                                    ))}
                                </div>
                                <p className="text-slate-600 mb-6 leading-relaxed relative z-10">
                                    "{testimonial.text}"
                                </p>
                                <div className="flex items-center gap-4">
                                    <div className="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-500/30">
                                        {testimonial.avatar}
                                    </div>
                                    <div>
                                        <div className="font-bold text-slate-900">{testimonial.name}</div>
                                        <div className="text-sm text-slate-500">{testimonial.role}</div>
                                    </div>
                                </div>
                            </motion.div>
                        ))}
                    </motion.div>
                </div>
            </section>

            {/* CTA Section */}
            <section className="py-24 bg-gradient-to-r from-indigo-600 via-violet-600 to-purple-600 relative overflow-hidden">
                <div className="absolute inset-0 opacity-10">
                    <IslamicPattern />
                </div>

                {/* Floating Elements */}
                <motion.div
                    className="absolute top-10 left-10 w-20 h-20 border-2 border-white/20 rounded-2xl"
                    animate={{ rotate: 360 }}
                    transition={{ duration: 20, repeat: Infinity, ease: 'linear' }}
                />
                <motion.div
                    className="absolute bottom-10 right-10 w-16 h-16 border-2 border-white/20 rounded-full"
                    animate={{ rotate: -360 }}
                    transition={{ duration: 15, repeat: Infinity, ease: 'linear' }}
                />

                <motion.div
                    className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10"
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    variants={fadeInUp}
                >
                    <h2 className="text-3xl sm:text-4xl lg:text-5xl font-black text-white mb-6">
                        Siap Modernisasi Pesantren Anda?
                    </h2>
                    <p className="text-xl text-white/80 mb-10 max-w-2xl mx-auto">
                        Bergabung dengan ratusan pesantren yang telah merasakan kemudahan mengelola administrasi dengan Santrix.
                    </p>
                    <motion.div
                        className="flex flex-col sm:flex-row items-center justify-center gap-4"
                        variants={fadeInUp}
                    >
                        <motion.div whileHover={{ scale: 1.05, y: -3 }} whileTap={{ scale: 0.95 }}>
                            <Link
                                href="/register-pesantren"
                                className="px-10 py-4 bg-white text-indigo-600 font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all flex items-center gap-2"
                            >
                                🚀 Mulai Gratis Sekarang
                            </Link>
                        </motion.div>
                        <motion.div whileHover={{ scale: 1.05, y: -3 }} whileTap={{ scale: 0.95 }}>
                            <a
                                href="https://wa.me/6281234567890"
                                className="px-10 py-4 bg-white/10 backdrop-blur-sm text-white font-bold rounded-2xl border-2 border-white/30 hover:bg-white/20 transition-all flex items-center gap-2"
                            >
                                💬 Hubungi Kami
                            </a>
                        </motion.div>
                    </motion.div>
                </motion.div>
            </section>

            {/* Footer */}
            <footer id="kontak" className="bg-slate-900 text-white pt-20 pb-8 relative overflow-hidden">
                <div className="absolute inset-0 opacity-5">
                    <IslamicPattern />
                </div>

                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                    <div className="grid md:grid-cols-4 gap-12 mb-12">
                        <div className="col-span-2">
                            <div className="flex items-center gap-3 mb-6">
                                <div className="w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-500/30">
                                    <span className="text-white font-bold text-xl">S</span>
                                </div>
                                <span className="text-2xl font-bold">Santrix</span>
                            </div>
                            <p className="text-slate-400 max-w-md mb-6 leading-relaxed">
                                Sistem manajemen pesantren modern dengan sentuhan Islami.
                                Membantu ribuan pesantren di Indonesia mengelola administrasi dengan lebih efisien dan berkah.
                            </p>
                            <div className="flex items-center gap-2 text-slate-400">
                                <span className="text-2xl">☪️</span>
                                <span className="italic">"Barakallahu fiikum"</span>
                            </div>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Produk</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li><a href="#fitur" className="hover:text-white transition-colors">Fitur</a></li>
                                <li><a href="#harga" className="hover:text-white transition-colors">Harga</a></li>
                                <li><a href="/demo-start/sekretaris" className="hover:text-white transition-colors">Demo</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 className="font-bold mb-6 text-lg">Kontak</h4>
                            <ul className="space-y-3 text-slate-400">
                                <li className="flex items-center gap-2">
                                    <span>📧</span> support@santrix.my.id
                                </li>
                                <li className="flex items-center gap-2">
                                    <span>📱</span> +62 812-3456-7890
                                </li>
                                <li className="flex items-center gap-2">
                                    <span>📍</span> Indonesia
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-slate-800 pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                        <p className="text-slate-500">© 2025 Santrix. All rights reserved.</p>
                        <p className="text-slate-500 text-sm">Made with ❤️ for Pesantren Indonesia</p>
                    </div>
                </div>
            </footer>

            {/* Custom Styles */}
            <style>{`
                @keyframes gradient-x {
                    0%, 100% { background-position: 0% 50%; }
                    50% { background-position: 100% 50%; }
                }
                .animate-gradient-x {
                    background-size: 200% 200%;
                    animation: gradient-x 3s ease infinite;
                }
                .perspective-1000 {
                    perspective: 1000px;
                }
            `}</style>
        </>
    );
}
