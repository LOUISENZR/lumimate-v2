<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\IngredientConflict;
use Illuminate\Database\Seeder;

class IngredientConflictSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = Ingredient::pluck('id', 'slug')->toArray();

        $conflicts = [
            // ================= 1. RISKY COMBINATIONS =================
            [
                'slug_1' => 'retinol',
                'slug_2' => 'aha-glycolic-lactic-acid',
                'risk_level' => 'risky',
                'explanation' => 'Kombinasi dual exfoliant kuat yang dapat memicu over-exfoliation syndrome, pengelupasan parah, dan kerusakan skin barrier dalam 14–28 hari.',
                'solution' => 'Gunakan Retinol di malam yang berbeda dengan AHA/BHA (terapkan metode Skin Cycling: Malam 1 = AHA/BHA, Malam 2 = Retinol, Malam 3-4 = Recovery).',
                'reference_source' => 'The INKEY List (2025) & American Academy of Dermatology (AAD)',
            ],
            [
                'slug_1' => 'retinol',
                'slug_2' => 'bha-salicylic-acid',
                'risk_level' => 'risky',
                'explanation' => 'Retinol mempercepat pergantian sel sedangkan BHA mengeksfoliasi pori secara intens. Digunakan bersamaan dalam 1 sesi memicu iritasi berat dan dehidrasi skin barrier.',
                'solution' => 'Pisahkan hari pemakaian atau gunakan BHA di pagi hari (wajib SPF) dan Retinol di malam hari.',
                'reference_source' => 'American Academy of Dermatology (AAD) & Nina O. & Hudson P. (2024)',
            ],
            [
                'slug_1' => 'vitamin-c-l-ascorbic-acid',
                'slug_2' => 'retinol',
                'risk_level' => 'risky',
                'explanation' => 'Perbedaan rentang pH yang bertolak belakang (Vitamin C optimal pada pH < 3.5, sedangkan Retinol membutuhkan pH 6.0–7.0). Penggunaan bersamaan dalam satu layer mendestabilisasi efektivitas kedua bahan aktif.',
                'solution' => 'Gunakan Vitamin C di PAGI hari untuk perlindungan antioksidan, dan gunakan Retinol di MALAM hari. TIDAK boleh dilayer bersamaan.',
                'reference_source' => 'The INKEY List (2025) & Ausmetics Compatibility Guide (2025)',
            ],
            [
                'slug_1' => 'benzoyl-peroxide',
                'slug_2' => 'retinol',
                'risk_level' => 'risky',
                'explanation' => 'Benzoyl Peroxide bersifat mengoksidasi dan dapat merusak molekul retinol sehingga kehilangan efektivitasnya, sekaligus melipatgandakan potensi kekeringan dan dermatitis kontak.',
                'solution' => 'Gunakan Benzoyl Peroxide di pagi hari sebagai spot treatment jerawat aktif, dan gunakan Retinol di malam hari.',
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD Guidelines',
            ],

            // ================= 2. CAUTION COMBINATIONS =================
            [
                'slug_1' => 'vitamin-c-l-ascorbic-acid',
                'slug_2' => 'aha-glycolic-lactic-acid',
                'risk_level' => 'caution',
                'explanation' => 'Keduanya merupakan bahan aktif asam dengan pH rendah. Menggunakannya sekaligus dapat membuat kulit sensitif menjadi kemerahan dan perih.',
                'solution' => 'Bagi kulit normal/oily, pisahkan sesi: Vitamin C di pagi hari dan AHA di malam hari. Hindari bagi pemilik kulit sensitif.',
                'reference_source' => 'Healthline Skincare Compatibility & Pinnell et al.',
            ],
            [
                'slug_1' => 'vitamin-c-l-ascorbic-acid',
                'slug_2' => 'bha-salicylic-acid',
                'risk_level' => 'caution',
                'explanation' => 'Penggunaan Vitamin C bersamaan dengan BHA dalam satu sesi dapat meningkatkan sensitivitas kulit karena keduanya memiliki efek eksfoliasi dan pH asam.',
                'solution' => 'Gunakan Vitamin C di pagi hari dan BHA di malam hari untuk hasil optimal tanpa risiko iritasi.',
                'reference_source' => 'Cleveland Clinic Skincare Guide (2024)',
            ],
            [
                'slug_1' => 'benzoyl-peroxide',
                'slug_2' => 'vitamin-c-l-ascorbic-acid',
                'risk_level' => 'caution',
                'explanation' => 'Benzoyl Peroxide dapat mengoksidasi Vitamin C hingga 70%, menetralkan fungsi antioksidannya dan menurunkan efektivitas pencerah kulit.',
                'solution' => 'Gunakan di waktu yang berbeda (Vitamin C pagi, Benzoyl Peroxide malam) atau jangan aplikasikan di area kulit yang bertumpuk.',
                'reference_source' => 'Ausmetics Compatibility Guide (2025)',
            ],
            [
                'slug_1' => 'peptides',
                'slug_2' => 'aha-glycolic-lactic-acid',
                'risk_level' => 'caution',
                'explanation' => 'Lingkungan asam kuat dari AHA dapat memecah ikatan rantai peptida melalui proses hidrolisis sebelum sempat diserap kulit.',
                'solution' => 'Tunggu 20–30 menit setelah pengaplikasian AHA hingga kulit kembali ke pH normal sebelum mengaplikasikan produk berpeptida, atau gunakan di sesi berbeda.',
                'reference_source' => 'NCBI & Journal of Dermatological Science',
            ],

            // ================= 3. SAFE & SYNERGISTIC COMBINATIONS =================
            [
                'slug_1' => 'niacinamide',
                'slug_2' => 'ceramide',
                'risk_level' => 'safe',
                'explanation' => 'Kombinasi sinergis terbaik untuk regenerasi skin barrier: Niacinamide memperkuat tight-junction antar sel dan merangsang produksi ceramide alami, sedangkan Ceramide mengisi celah lipid bilayer.',
                'solution' => 'Sangat aman dan efektif digunakan bersamaan setiap pagi dan malam hari untuk semua jenis kulit.',
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD',
            ],
            [
                'slug_1' => 'hyaluronic-acid',
                'slug_2' => 'ceramide',
                'risk_level' => 'safe',
                'explanation' => 'Duet hidrasi dan pengunci kelembapan paling optimal: Hyaluronic Acid mengikat molekul air ke sel kulit, sementara Ceramide mencegah terjadinya Transepidermal Water Loss (TEWL).',
                'solution' => 'Aplikasikan Hyaluronic Acid di atas kulit lembap, lalu segera kunci dengan pelembap yang mengandung Ceramide.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
            ],
            [
                'slug_1' => 'niacinamide',
                'slug_2' => 'hyaluronic-acid',
                'risk_level' => 'safe',
                'explanation' => 'Kombinasi non-reaktif yang memberikan hidrasi mendalam sekaligus mencerahkan dan memperbaiki tekstur pori secara aman.',
                'solution' => 'Bebas digunakan bersamaan dalam rutinitas pagi maupun malam hari.',
                'reference_source' => 'Cleveland Clinic Skincare Guide',
            ],
            [
                'slug_1' => 'retinol',
                'slug_2' => 'ceramide',
                'risk_level' => 'safe',
                'explanation' => 'Ceramide membantu menenangkan dan meredam efek samping kekeringan atau iritasi selama fase retinization (adaptasi retinol).',
                'solution' => 'Gunakan metode sandwich atau aplikasikan pelembap ceramide setelah serum retinol di malam hari.',
                'reference_source' => 'Dr. Kseniya Kobets, MD & NCBI (2023)',
            ],
            [
                'slug_1' => 'centella-asiatica-cica',
                'slug_2' => 'niacinamide',
                'risk_level' => 'safe',
                'explanation' => 'Kombinasi anti-inflamasi dan soothing yang sangat ideal untuk meredakan kemerahan, bekas jerawat kemerahan (PIE), dan mempercepat pemulihan kulit reaktif.',
                'solution' => 'Aman diaplikasikan setiap hari pada pagi dan malam hari.',
                'reference_source' => 'Journal of Cosmetic Dermatology',
            ],
            [
                'slug_1' => 'vitamin-c-l-ascorbic-acid',
                'slug_2' => 'vitamin-e-tocopherol',
                'risk_level' => 'safe',
                'explanation' => 'Kombinasi standar emas antioksidan: Vitamin E melipatgandakan stabilitas dan fotoproteksi Vitamin C hingga 8 kali lipat terhadap radiasi UV.',
                'solution' => 'Gunakan formula yang mengandung kedua vitamin ini di pagi hari sebelum sunscreen.',
                'reference_source' => 'Pinnell, S.R., et al. (Dermatologic Surgery)',
            ],

            // ================= 4. RECOMMENDED COMBINATIONS =================
            [
                'slug_1' => 'vitamin-c-l-ascorbic-acid',
                'slug_2' => 'sunscreen-uv-filters',
                'risk_level' => 'recommended',
                'explanation' => 'Perlindungan ganda terbaik di pagi hari: Vitamin C menetralisir radikal bebas yang lolos, sedangkan Sunscreen memblokir penetrasi radiasi UVA & UVB.',
                'solution' => 'Aplikasikan Vitamin C terlebih dahulu, biarkan meresap 1-2 menit, lalu akhiri rutinitas pagi dengan Sunscreen minimal SPF 30.',
                'reference_source' => 'American Academy of Dermatology (AAD) & Pinnell et al.',
            ],
            [
                'slug_1' => 'aha-glycolic-lactic-acid',
                'slug_2' => 'sunscreen-uv-filters',
                'risk_level' => 'recommended',
                'explanation' => 'Setelah eksfoliasi AHA di malam hari, lapisan kulit baru menjadi jauh lebih fotosensitif terhadap matahari. Pemakaian sunscreen di pagi berikutnya adalah WAJIB.',
                'solution' => 'Pastikan mengaplikasikan sunscreen SPF 30+ secara merata dan reapply di siang hari.',
                'reference_source' => 'American Academy of Dermatology (AAD) Safety Guideline',
            ],
            [
                'slug_1' => 'retinol',
                'slug_2' => 'sunscreen-uv-filters',
                'risk_level' => 'recommended',
                'explanation' => 'Retinol meningkatkan sensitivitas kulit terhadap sinar UV. Proteksi sunscreen di pagi dan siang hari adalah langkah mutlak untuk mencegah timbulnya flek dan sunburn.',
                'solution' => 'Selalu gunakan sunscreen di pagi hari setelah malam pemakaian retinol.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
            ],
            [
                'slug_1' => 'niacinamide',
                'slug_2' => 'bha-salicylic-acid',
                'risk_level' => 'recommended',
                'explanation' => 'Salicylic Acid membersihkan sebum di dalam pori untuk mencegah jerawat, sedangkan Niacinamide meredakan inflamasi kemerahan dan mengecilkan tampilan pori.',
                'solution' => 'Sangat dianjurkan untuk jenis kulit berminyak dan mudah berjerawat.',
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD',
            ],
        ];

        foreach ($conflicts as $item) {
            $id1 = $map[$item['slug_1']] ?? null;
            $id2 = $map[$item['slug_2']] ?? null;

            if ($id1 && $id2) {
                // Ensure canonical order to prevent duplicates (id1 < id2)
                $firstId = min($id1, $id2);
                $secondId = max($id1, $id2);

                IngredientConflict::updateOrCreate(
                    [
                        'ingredient_id_1' => $firstId,
                        'ingredient_id_2' => $secondId,
                    ],
                    [
                        'risk_level' => $item['risk_level'],
                        'explanation' => $item['explanation'],
                        'solution' => $item['solution'],
                        'reference_source' => $item['reference_source'],
                    ]
                );
            }
        }
    }
}
