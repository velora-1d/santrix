<?php

return [
    'plans' => [
        [
            'id' => 'basic', // Matches DB logic (1.5jt / 6 months ~= 250k/mo, but let's stick to marketing pricing or match billing?)
            // Wait, BillingController says 1.500.000 for 6 months. That is 250.000 / month.
            // Let's use the REAL logic from BillingController.
            // Basic = 1.500.000 per 6 bulan.
            'name' => 'Basic',
            'price' => 1500000,
            'formatted_price' => 'Rp 1.500.000',
            'period' => 'per 6 bulan',
            'is_featured' => false,
            'description' => 'Fitur esensial untuk manajemen pesantren.',
            'features' => [
                ['name' => 'Data Santri (Unlimited)', 'included' => true],
                ['name' => 'Keuangan SPP & Tabungan', 'included' => true],
                ['name' => 'Laporan Keuangan', 'included' => true],
                ['name' => 'WhatsApp Gateway', 'included' => false],
            ],
            'button_text' => 'Pilih Paket',
            'button_class' => 'btn-outline',
        ],
        [
            'id' => 'advance', // Matches DB logic (3.0jt / 6 months)
            'name' => 'Advance',
            'price' => 3000000,
            'formatted_price' => 'Rp 3.000.000',
            'period' => 'per 6 bulan',
            'is_featured' => true,
            'description' => 'Solusi lengkap dengan fitur prioritas.',
            'features' => [
                ['name' => 'Semua Fitur Basic', 'included' => true],
                ['name' => 'WhatsApp Gateway (Unlimited)', 'included' => true],
                ['name' => 'Billing Blast (Tagihan WA)', 'included' => true],
                ['name' => 'Kartu Santri Digital', 'included' => true],
                ['name' => 'Prioritas Support', 'included' => true],
            ],
            'button_text' => 'Pilih Advance',
            'button_class' => 'btn-primary',
        ]
    ]
];
