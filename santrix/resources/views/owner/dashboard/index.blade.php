@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('sidebar-menu')
    @include('owner.partials.sidebar-menu')
@endsection

@section('content')
    <!-- Welcome Banner -->
    <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border-radius: 16px; padding: 32px; margin-bottom: 32px; box-shadow: 0 10px 30px rgba(49, 46, 129, 0.3); position: relative; overflow: hidden;">
        <div style="position: absolute; top: -30px; right: -30px; width: 150px; height: 150px; background: rgba(255,255,255,0.1); border-radius: 50%;"></div>
        <div style="position: absolute; bottom: -40px; left: 30%; width: 100px; height: 100px; background: rgba(255,255,255,0.05); border-radius: 50%;"></div>
        
        <div class="welcome-banner-content" style="display: flex; align-items: center; gap: 24px; position: relative; z-index: 1; color: white;">
            <div style="background: rgba(255,255,255,0.2); width: 64px; height: 64px; border-radius: 16px; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.3);">
                <i data-feather="monitor" style="width: 32px; height: 32px; color: white;"></i>
            </div>
            <div>
                <h2 style="font-size: 1.875rem; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.025em; color: white !important;">Selamat beraktivitas, Owner!</h2>
                <p style="opacity: 0.9; font-size: 1.05rem; font-weight: 400; margin: 0; color: white !important;">Pantau performa bisnis dan pertumbuhan tenant Anda hari ini.</p>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 32px;">
        
        <!-- Revenue -->
        <div class="card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                 <div style="width: 48px; height: 48px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                    <i data-feather="dollar-sign" style="width: 24px; height: 24px; color: #3b82f6;"></i>
                </div>
                <div style="font-size: 0.75rem; font-weight: 600; color: #10b981; background: #ecfdf5; padding: 4px 8px; border-radius: 20px;">
                    +12% vs last month
                </div>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Monthly Revenue</p>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e2937; margin: 0;">Rp {{ number_format($revenue ?? 0, 0, ',', '.') }}</h3>
        </div>

        <!-- Total Pesantren -->
        <div class="card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
             <div style="width: 48px; height: 48px; background: #e0e7ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <i data-feather="grid" style="width: 24px; height: 24px; color: #4338ca;"></i>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Total Pesantren</p>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e2937; margin: 0;">{{ $total }}</h3>
        </div>

        <!-- Active Pesantren -->
        <div class="card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
             <div style="width: 48px; height: 48px; background: #dcfce7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <i data-feather="check-circle" style="width: 24px; height: 24px; color: #15803d;"></i>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Active Tenants</p>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e2937; margin: 0;">{{ $active }}</h3>
        </div>

        <!-- Expiring Soon -->
         <div class="card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
             <div style="width: 48px; height: 48px; background: #fef3c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-bottom: 16px;">
                <i data-feather="clock" style="width: 24px; height: 24px; color: #b45309;"></i>
            </div>
            <p style="color: #64748b; font-size: 0.875rem; font-weight: 500; margin-bottom: 4px;">Expiring Soon</p>
            <h3 style="font-size: 1.5rem; font-weight: 700; color: #1e2937; margin: 0;">{{ $expiring }}</h3>
        </div>

    </div>

    <!-- Growth Chart Section -->
    <div class="card" style="background: white; border-radius: 16px; padding: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); margin-bottom: 32px;">
        <div style="margin-bottom: 24px;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e2937; margin: 0 0 4px 0;">Pertumbuhan Pesantren Baru</h3>
            <p style="color: #64748b; font-size: 0.875rem;">Statistik pendaftaran pesantren dalam 12 bulan terakhir</p>
        </div>
        <div style="position: relative; height: 300px; width: 100%;">
            <canvas id="growthChart"></canvas>
        </div>
    </div>

    <!-- Recent Tenants Section -->
    <div class="card" style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
        <div style="padding: 24px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e2937; margin: 0;">Recent Tenants</h3>
            <a href="{{ route('owner.pesantren.index') }}" style="color: #4f46e5; font-size: 0.875rem; font-weight: 600; text-decoration: none;">View All</a>
        </div>
        <div style="padding: 48px; text-align: center;">
            <div style="width: 64px; height: 64px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px auto; color: #cbd5e1;">
                <i data-feather="inbox" style="width: 32px; height: 32px;"></i>
            </div>
            <p style="color: #64748b; font-size: 0.95rem;">No recent activity to display.</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('growthChart').getContext('2d');
            
            // Gradient for the chart area
            const gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)');
            gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($labels),
                    datasets: [{
                        label: 'Pesantren Baru',
                        data: @json($growthData),
                        borderColor: '#4f46e5',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#4f46e5',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 12,
                            titleFont: {
                                size: 13,
                                family: "'Inter', sans-serif"
                            },
                            bodyFont: {
                                size: 12,
                                family: "'Inter', sans-serif"
                            },
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Pesantren Baru';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 11
                                },
                                color: '#64748b'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: {
                                    size: 11
                                },
                                color: '#64748b',
                                maxRotation: 0
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    },
                }
            });
        });
    </script>
@endsection
