<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        // 1. Akun Demo (Admin, Operator, Customer)
        $users = [
            [
                'name'       => 'Farrel Admin ShopEase',
                'email'      => 'admin@shopease.local',
                'password'   => password_hash('Admin123!', PASSWORD_BCRYPT),
                'role'       => 'admin',
                'phone'      => '0812-5025-1219',
                'address'    => 'HQ ShopEase, Sukolilo, Surabaya',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Budi Operator Katalog',
                'email'      => 'operator@shopease.local',
                'password'   => password_hash('Operator123!', PASSWORD_BCRYPT),
                'role'       => 'operator',
                'phone'      => '0813-4411-7788',
                'address'    => 'Gudang Pemenuhan ShopEase, Rungkut, Surabaya',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Farrel Prastita Ramadhan',
                'email'      => 'customer@shopease.local',
                'password'   => password_hash('Customer123!', PASSWORD_BCRYPT),
                'role'       => 'customer',
                'phone'      => '0812-3456-1219',
                'address'    => 'Jl. Teknik Informatika No. 19, Surabaya',
                'is_active'  => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $userTable = $this->db->table('users');
        foreach ($users as $u) {
            if ($userTable->where('email', $u['email'])->countAllResults() === 0) {
                $userTable->insert($u);
            }
        }

        // 2. Kategori Produk (4 Kategori Dwibahasa)
        $categories = [
            ['name' => 'Electronics', 'name_id' => 'Elektronik', 'slug' => 'electronics', 'description' => 'Audio, periferal kerja, dan perangkat pintar bergaransi resmi.'],
            ['name' => 'Fashion', 'name_id' => 'Fesyen & Sepatu', 'slug' => 'fashion', 'description' => 'Sepatu kasual, jaket harian, dan perlengkapan gaya hidup modern.'],
            ['name' => 'Accessories', 'name_id' => 'Aksesoris', 'slug' => 'accessories', 'description' => 'Jam tangan pintar, tas laptop anti air, dan aksesoris esensial.'],
            ['name' => 'Home & Workspace', 'name_id' => 'Rumah & Ruang Kerja', 'slug' => 'home-workspace', 'description' => 'Lampu meja LED, dudukan laptop ergonomis, dan perlengkapan produktivitas.'],
        ];

        $catTable = $this->db->table('categories');
        $catIds   = [];
        foreach ($categories as $c) {
            $existing = $catTable->where('slug', $c['slug'])->get()->getRowArray();
            if ($existing) {
                $catIds[$c['slug']] = (int) $existing['id'];
            } else {
                $catTable->insert($c + ['is_active' => 1, 'created_at' => $now, 'updated_at' => $now]);
                $catIds[$c['slug']] = (int) $this->db->insertID();
            }
        }

        // 3. Minimal 15 Produk + Foto Produk Nyata (Generated PNG via GD)
        $uploadDir = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'products';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $products = [
            ['cat' => 'electronics', 'slug' => 'wireless-headphone-anc', 'name' => 'Wireless Headphone ANC Pro', 'name_id' => 'Headphone Nirkabel ANC Pro', 'price' => 499000, 'stock' => 22, 'status' => 'active', 'color' => [109, 40, 217], 'desc' => 'Headphone Bluetooth 5.3 dengan Active Noise Cancellation 40dB, baterai 38 jam, dan bantalan memory foam.'],
            ['cat' => 'electronics', 'slug' => 'mechanical-keyboard-75', 'name' => 'Mechanical Keyboard 75% Tactile', 'name_id' => 'Keyboard Mekanikal 75% Tactile', 'price' => 675000, 'stock' => 4, 'status' => 'active', 'color' => [79, 70, 229], 'desc' => 'Layout ringkas 84 tombol hot-swappable, keycap PBT double-shot, serta koneksi tri-mode USB-C dan 2.4GHz.'],
            ['cat' => 'electronics', 'slug' => 'ergonomic-wireless-mouse', 'name' => 'Ergonomic Wireless Mouse 4K', 'name_id' => 'Mouse Nirkabel Ergonomis 4K', 'price' => 285000, 'stock' => 18, 'status' => 'active', 'color' => [37, 99, 235], 'desc' => 'Sensor presisi tinggi hingga 16.000 DPI, bobot ringan 62 gram, nyaman digunakan seharian.'],
            ['cat' => 'electronics', 'slug' => 'usb-c-hub-8in1', 'name' => 'USB-C Hub 8-in-1 100W PD', 'name_id' => 'Hub USB-C 8-in-1 100W PD', 'price' => 349000, 'stock' => 3, 'status' => 'active', 'color' => [14, 116, 144], 'desc' => 'Dilengkapi HDMI 4K 60Hz, Gigabit Ethernet, pembaca kartu SD/TF, serta pengisian daya Power Delivery 100W.'],
            ['cat' => 'electronics', 'slug' => 'portable-bluetooth-speaker', 'name' => 'Portable Bluetooth Speaker IPX7', 'name_id' => 'Speaker Bluetooth Portabel IPX7', 'price' => 395000, 'stock' => 0, 'status' => 'active', 'color' => [15, 118, 110], 'desc' => 'Suara bass bertenaga 20W dengan ketahanan air IPX7, ideal untuk kegiatan luar ruangan.'],
            ['cat' => 'fashion', 'slug' => 'urban-casual-sneakers', 'name' => 'Urban Casual Sneakers', 'name_id' => 'Sepatu Sneakers Kasual Urban', 'price' => 389000, 'stock' => 16, 'status' => 'active', 'color' => [217, 119, 6], 'desc' => 'Sol empuk berbahan phylon ringan dengan sirkulasi udara maksimal untuk mobilitas harian.'],
            ['cat' => 'fashion', 'slug' => 'waterproof-commuter-jacket', 'name' => 'Waterproof Commuter Jacket', 'name_id' => 'Jaket Harian Anti Air', 'price' => 425000, 'stock' => 5, 'status' => 'active', 'color' => [180, 83, 9], 'desc' => 'Lapisan luar tahan angin dan gerimis dengan furing breathable serta 4 saku resleting aman.'],
            ['cat' => 'fashion', 'slug' => 'cotton-oversize-hoodie', 'name' => 'Heavyweight Cotton Hoodie', 'name_id' => 'Hoodie Katun Premium 330gsm', 'price' => 315000, 'stock' => 24, 'status' => 'active', 'color' => [190, 24, 93], 'desc' => 'Bahan katun fleece lembut 330gsm yang hangat tanpa gerah dengan potongan modern.'],
            ['cat' => 'fashion', 'slug' => 'slim-chino-trousers', 'name' => 'Stretch Slim Chino Trousers', 'name_id' => 'Celana Chino Stretch Slim', 'price' => 259000, 'stock' => 14, 'status' => 'inactive', 'color' => [71, 85, 105], 'desc' => 'Katun twill elastis yang rapi untuk kuliah, kerja, maupun acara santai (nonaktif sementara untuk restock).'],
            ['cat' => 'accessories', 'slug' => 'smartwatch-amoled-s3', 'name' => 'Smartwatch AMOLED Series S3', 'name_id' => 'Jam Tangan Pintar AMOLED S3', 'price' => 589000, 'stock' => 11, 'status' => 'active', 'color' => [5, 150, 105], 'desc' => 'Layar AMOLED 1.43 inci selalu aktif, pemantau detak jantung 24 jam, GPS bawaan, dan tahan air 5ATM.'],
            ['cat' => 'accessories', 'slug' => 'tech-laptop-backpack', 'name' => 'Water-Resistant Laptop Backpack', 'name_id' => 'Ransel Laptop Anti Air 16"', 'price' => 365000, 'stock' => 19, 'status' => 'active', 'color' => [29, 78, 216], 'desc' => 'Kompartemen laptop berlapis busa tebal hingga 16 inci, port USB eksternal, dan bahan nilon balistik.'],
            ['cat' => 'accessories', 'slug' => 'minimalist-rfid-wallet', 'name' => 'Minimalist RFID Leather Wallet', 'name_id' => 'Dompet Kulit Minimalis RFID', 'price' => 179000, 'stock' => 2, 'status' => 'active', 'color' => [120, 53, 15], 'desc' => 'Kulit sapi asli dengan lapisan pelindung kartu RFID dan slot akses cepat untuk 8 kartu.'],
            ['cat' => 'home-workspace', 'slug' => 'aluminum-laptop-stand', 'name' => 'Foldable Aluminum Laptop Stand', 'name_id' => 'Dudukan Laptop Aluminium Lipat', 'price' => 229000, 'stock' => 28, 'status' => 'active', 'color' => [67, 56, 202], 'desc' => 'Sudut ergonomis dapat diatur 6 tingkat, bahan aluminium kokoh dengan bantalan silikon anti selip.'],
            ['cat' => 'home-workspace', 'slug' => 'led-monitor-lightbar', 'name' => 'LED Monitor Light Bar Pro', 'name_id' => 'Lampu Monitor LED Asimetris', 'price' => 319000, 'stock' => 9, 'status' => 'active', 'color' => [124, 58, 237], 'desc' => 'Pencahayaan meja bebas silau layar dengan pengaturan temperatur warna 2700K–6500K.'],
            ['cat' => 'home-workspace', 'slug' => 'wool-desk-mat-xl', 'name' => 'Merino Wool Felt Desk Mat XL', 'name_id' => 'Alas Meja Kerja Felt Wool XL', 'price' => 165000, 'stock' => 15, 'status' => 'active', 'color' => [51, 65, 85], 'desc' => 'Ukuran 90×40 cm memberi tekstur hangat, meredam suara ketikan keyboard, dan menjaga meja tetap rapi.'],
        ];

        $prodTable = $this->db->table('products');
        $imgTable  = $this->db->table('product_images');

        foreach ($products as $idx => $p) {
            $existing = $prodTable->where('slug', $p['slug'])->get()->getRowArray();
            if ($existing) {
                $productId = (int) $existing['id'];
            } else {
                $prodTable->insert([
                    'category_id' => $catIds[$p['cat']],
                    'name'        => $p['name'],
                    'name_id'     => $p['name_id'],
                    'slug'        => $p['slug'],
                    'description' => $p['desc'],
                    'price'       => $p['price'],
                    'stock'       => $p['stock'],
                    'status'      => $p['status'],
                    'created_at'  => date('Y-m-d H:i:s', time() - (15 - $idx) * 3600),
                    'updated_at'  => $now,
                ]);
                $productId = (int) $this->db->insertID();
            }

            if ($imgTable->where('product_id', $productId)->countAllResults() === 0) {
                $primaryFile = 'demo_' . $p['slug'] . '_1.png';
                $this->generateProductPng($uploadDir . DIRECTORY_SEPARATOR . $primaryFile, $p['name'], strtoupper($p['cat']), $p['color'], 'PRIMARY VIEW');
                $imgTable->insert([
                    'product_id' => $productId,
                    'filename'   => $primaryFile,
                    'is_primary' => 1,
                    'sort_order' => 1,
                    'created_at' => $now,
                ]);

                // Tambah foto kedua untuk 5 produk pertama agar galeri multi-foto langsung terlihat
                if ($idx < 5) {
                    $altColor = [max(20, $p['color'][0] - 35), max(20, $p['color'][1] + 25), min(230, $p['color'][2] + 20)];
                    $secondFile = 'demo_' . $p['slug'] . '_2.png';
                    $this->generateProductPng($uploadDir . DIRECTORY_SEPARATOR . $secondFile, $p['name'], 'DETAIL ANGLE', $altColor, 'GALLERY #2');
                    $imgTable->insert([
                        'product_id' => $productId,
                        'filename'   => $secondFile,
                        'is_primary' => 0,
                        'sort_order' => 2,
                        'created_at' => $now,
                    ]);
                }
            }
        }

        // 4. Data Direktori Karyawan (6 Karyawan Contoh)
        $employees = [
            ['employee_code' => 'EMP-001', 'full_name' => 'Farrel Prastita Ramadhan', 'work_email' => 'farrel.ramadhan@shopease.id', 'phone' => '0812-5025-1219', 'position' => 'Lead Store Administrator', 'department' => 'Operations', 'joined_at' => '2025-01-15', 'status' => 'active'],
            ['employee_code' => 'EMP-002', 'full_name' => 'Budi Santoso', 'work_email' => 'budi.santoso@shopease.id', 'phone' => '0813-4411-7788', 'position' => 'Catalog & Inventory Operator', 'department' => 'Warehouse', 'joined_at' => '2025-03-10', 'status' => 'active'],
            ['employee_code' => 'EMP-003', 'full_name' => 'Nadia Kusuma Wardani', 'work_email' => 'nadia.wardani@shopease.id', 'phone' => '0857-2231-9901', 'position' => 'Product Merchandiser', 'department' => 'Merchandising', 'joined_at' => '2025-05-02', 'status' => 'active'],
            ['employee_code' => 'EMP-004', 'full_name' => 'Raka Aditya Pratama', 'work_email' => 'raka.pratama@shopease.id', 'phone' => '0821-8899-4422', 'position' => 'Customer Support Specialist', 'department' => 'Customer Care', 'joined_at' => '2025-07-18', 'status' => 'on_leave'],
            ['employee_code' => 'EMP-005', 'full_name' => 'Sinta Dewi Maharani', 'work_email' => 'sinta.maharani@shopease.id', 'phone' => '0819-6655-1200', 'position' => 'Quality Control Analyst', 'department' => 'Warehouse', 'joined_at' => '2025-09-01', 'status' => 'active'],
            ['employee_code' => 'EMP-006', 'full_name' => 'Dimas Nugroho', 'work_email' => 'dimas.nugroho@shopease.id', 'phone' => null, 'position' => 'Junior Logistics Staff', 'department' => 'Operations', 'joined_at' => '2024-11-12', 'status' => 'inactive'],
        ];

        $empTable = $this->db->table('employees');
        foreach ($employees as $emp) {
            if ($empTable->where('employee_code', $emp['employee_code'])->countAllResults() === 0) {
                $empTable->insert($emp + ['created_at' => $now, 'updated_at' => $now]);
            }
        }
    }

    private function generateProductPng(string $path, string $title, string $badge, array $rgb, string $tag): void
    {
        if (is_file($path) || !function_exists('imagecreatetruecolor')) {
            return;
        }

        $w = 640;
        $h = 440;
        $im = imagecreatetruecolor($w, $h);
        imageantialias($im, true);

        // Background gradient vertikal
        for ($y = 0; $y < $h; $y++) {
            $ratio = $y / $h;
            $r = (int) max(15, min(255, $rgb[0] * (1 - $ratio * 0.55) + 15 * $ratio));
            $g = (int) max(15, min(255, $rgb[1] * (1 - $ratio * 0.55) + 23 * $ratio));
            $b = (int) max(25, min(255, $rgb[2] * (1 - $ratio * 0.55) + 42 * $ratio));
            $col = imagecolorallocate($im, $r, $g, $b);
            imageline($im, 0, $y, $w, $y, $col);
        }

        // Elemen dekoratif lingkaran halus
        $circle1 = imagecolorallocatealpha($im, 255, 255, 255, 112);
        $circle2 = imagecolorallocatealpha($im, 255, 255, 255, 102);
        imagefilledellipse($im, 530, 95, 240, 240, $circle1);
        imagefilledellipse($im, 110, 360, 200, 200, $circle2);

        // Kartu produk ikon geometris di tengah
        $cardBg = imagecolorallocatealpha($im, 255, 255, 255, 98);
        imagefilledrectangle($im, 210, 125, 430, 285, $cardBg);
        $white = imagecolorallocate($im, 255, 255, 255);
        $soft  = imagecolorallocate($im, 226, 232, 240);
        imagerectangle($im, 210, 125, 430, 285, $white);

        // Emblem tas belanja / kode produk
        $initials = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $title), 0, 2));
        imagestring($im, 5, 36, 32, 'SHOPEASE CATALOG  |  ' . $badge, $soft);
        imagestring($im, 5, 268, 195, '[ ' . $initials . ' ]', $white);
        imagestring($im, 4, 242, 225, $tag, $soft);

        // Judul produk di bawah
        $shortTitle = strlen($title) > 36 ? substr($title, 0, 33) . '...' : $title;
        imagestring($im, 5, 36, 368, $shortTitle, $white);
        imagestring($im, 3, 36, 396, 'Verified Product Asset - CodeIgniter 4 CRUD Demo', $soft);

        imagepng($im, $path);
        imagedestroy($im);
    }
}

