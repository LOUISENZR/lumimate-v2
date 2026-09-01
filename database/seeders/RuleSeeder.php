<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rules = [
            // ================= 1. RECOMMENDATION RULES (R01 - R12) =================
            [
                'rule_code' => 'R01',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'oily',
                    'concern' => 'acne',
                ],
                'actions' => [
                    'recommend_ingredient' => 'BHA (Salicylic Acid)',
                    'message' => 'Gunakan Salicylic Acid (BHA) sebagai serum atau toner eksfoliasi untuk membersihkan pori yang tersumbat minyak dan mengatasi jerawat.',
                ],
                'certainty_factor' => 0.90,
                'explanation' => 'BHA bersifat oil-soluble sehingga mampu menembus ke dalam kelenjar sebum dan melarutkan sumbatan komedo penyebab jerawat.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R02',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'oily',
                    'concern' => 'acne',
                ],
                'actions' => [
                    'recommend_formula' => 'gel_water_based',
                    'message' => 'Hindari pelembap berbasis minyak berat (heavy oil-based); pilih pelembap bertekstur gel atau water-based yang non-comedogenic.',
                ],
                'certainty_factor' => 0.85,
                'explanation' => 'Minyak berat dapat memperparah penyumbatan pori pada kulit yang sudah memproduksi sebum berlebih.',
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R03',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'sensitive',
                    'concern' => 'acne',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Azelaic Acid',
                    'message' => 'Gunakan Azelaic Acid sebagai alternatif gentle untuk Salicylic Acid guna mengatasi jerawat tanpa memicu rasa perih atau kemerahan.',
                ],
                'certainty_factor' => 0.80,
                'explanation' => 'Azelaic Acid memiliki profil anti-inflamasi tinggi dan toleransi yang sangat baik untuk kulit reaktif/sensitif.',
                'reference_source' => 'Journal of Cosmetic Dermatology & Baumann (2016)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R04',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'dry',
                    'concern' => 'aging',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Retinol',
                    'message' => 'Rekomendasikan Retinol konsentrasi rendah (0.025–0.05%) dimulai 1x/minggu dan wajib didampingi pelembap Ceramide.',
                ],
                'certainty_factor' => 0.85,
                'explanation' => 'Retinol menstimulasi sintesis kolagen untuk menyamarkan garis halus, namun kulit kering membutuhkan perlindungan ceramide agar tidak mengelupas.',
                'reference_source' => 'Cleveland Clinic & Dr. Kseniya Kobets (2023)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R05',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'dry',
                    'concern' => 'dehydration',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Hyaluronic Acid & Ceramide',
                    'message' => 'Prioritaskan duet Hyaluronic Acid untuk menarik hidrasi dan Ceramide untuk mengunci kelembapan di skin barrier.',
                ],
                'certainty_factor' => 0.92,
                'explanation' => 'Kombinasi humektan dan lipid oklusif/emollient menghentikan transepidermal water loss pada kulit kering terdehidrasi.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R06',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'concern' => 'hyperpigmentation',
                    'skin_type_not' => 'sensitive',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Vitamin C (L-Ascorbic Acid)',
                    'message' => 'Rekomendasikan serum Vitamin C murni (L-Ascorbic Acid) 10–15% di pagi hari sebelum sunscreen.',
                ],
                'certainty_factor' => 0.88,
                'explanation' => 'Vitamin C menghambat enzim tirosinase untuk memudarkan flek hitam dan memproteksi kulit dari pigmentasi akibat UV.',
                'reference_source' => 'Pinnell et al. & Kaminska et al. (2025)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R07',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'concern' => 'hyperpigmentation',
                    'skin_type' => 'sensitive',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Niacinamide / Gentle Vitamin C',
                    'message' => 'Rekomendasikan turunan Vitamin C stabil (Ascorbyl Glucoside) atau Niacinamide 2-5% yang lebih lembut untuk memudarkan noda hitam.',
                ],
                'certainty_factor' => 0.82,
                'explanation' => 'L-Ascorbic murni dengan pH sangat rendah berisiko mengiritasi kulit sensitif; derivatif Vitamin C lebih ramah barrier.',
                'reference_source' => 'Journal of Cosmetic Dermatology',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R08',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'concern' => 'texture',
                    'experience_level_not' => 'beginner',
                ],
                'actions' => [
                    'recommend_ingredient' => 'AHA (Glycolic/Lactic Acid)',
                    'message' => 'Rekomendasikan AHA konsentrasi 5–10% di malam hari maksimal 2–3x/minggu untuk meratakan tekstur kulit.',
                ],
                'certainty_factor' => 0.87,
                'explanation' => 'AHA memutus ikatan desmosom pada stratum korneum untuk mengangkat sel kulit mati dan memperhalus tekstur kulit.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R09',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'concern' => 'texture',
                    'experience_level' => 'beginner',
                ],
                'actions' => [
                    'recommend_ingredient' => 'Lactic Acid (Gentle AHA)',
                    'message' => 'Mulai dengan Lactic Acid 5% (AHA berukuran molekul besar yang paling gentle) dengan frekuensi 1x/minggu.',
                ],
                'certainty_factor' => 0.80,
                'explanation' => 'Pemula membutuhkan adaptasi eksfoliasi kimiawi bertahap untuk mencegah terjadinya over-exfoliation.',
                'reference_source' => 'Cleveland Clinic Skincare Guide',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R10',
                'rule_type' => 'safety',
                'conditions' => [
                    'is_pregnant' => true,
                ],
                'actions' => [
                    'block_ingredients' => ['Retinol', 'BHA (Salicylic Acid)'],
                    'recommend_safe_alternatives' => ['Azelaic Acid', 'Bakuchiol', 'Vitamin C (L-Ascorbic Acid)'],
                    'message' => 'PERINGATAN KEHAMILAN: Retinol dan Salicylic Acid dosis tinggi otomatis dinonaktifkan. Gunakan Azelaic Acid atau Bakuchiol sebagai alternatif aman.',
                ],
                'certainty_factor' => 1.00,
                'explanation' => 'Retinoid oral dan topikal dosis tinggi memiliki risiko teratogenik menurut pedoman dermatologi kehamilan.',
                'reference_source' => 'American Academy of Dermatology (AAD) Pregnancy Safety',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R11',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'skin_type' => 'combination',
                ],
                'actions' => [
                    'recommend_strategy' => 'zone_based',
                    'message' => 'Terapkan metode zone-based: fokuskan produk pengontrol minyak/BHA di T-zone (dahi, hidung, dagu) dan hidrasi lebih tebal di U-zone (pipi).',
                ],
                'certainty_factor' => 0.78,
                'explanation' => 'Kulit kombinasi memiliki distribusi kelenjar minyak tidak merata antara area sentral dan perifer wajah.',
                'reference_source' => 'Baumann Skin Type Solution (2005, 2016)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'R12',
                'rule_type' => 'recommendation',
                'conditions' => [
                    'experience_level' => 'beginner',
                    'has_ingredient' => 'Retinol',
                ],
                'actions' => [
                    'set_frequency' => 1,
                    'message' => 'Atur frekuensi awal Retinol ke 1x/minggu selama 4 minggu pertama. Terapkan metode sandwich (Moisturizer -> Retinol -> Moisturizer) jika timbul kemerahan.',
                ],
                'certainty_factor' => 0.95,
                'explanation' => 'Fase retinization pada pemula membutuhkan waktu 4-6 minggu agar reseptor asam retinoat kulit terbiasa tanpa inflamasi.',
                'reference_source' => 'Dr. Kseniya Kobets (2023) & NCBI (2023)',
                'is_active' => true,
            ],

            // ================= 2. FREQUENCY RULES (F01 - F09) =================
            [
                'rule_code' => 'F01',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'Sunscreen (UV Filters)'],
                'actions' => [
                    'usage_time' => 'morning',
                    'max_frequency' => 7,
                    'is_mandatory' => true,
                    'warning_if_missing' => 'Sunscreen adalah langkah WAJIB di pagi hari. Tanpa sunscreen, penggunaan bahan aktif lain akan memicu flek dan kerusakan kulit.',
                ],
                'certainty_factor' => 1.00,
                'explanation' => 'Fotoproteksi harian adalah fondasi mutlak seluruh rutinitas perawatan kulit.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F02',
                'rule_type' => 'frequency',
                'conditions' => [
                    'ingredient' => 'Retinol',
                    'experience_level' => 'beginner',
                ],
                'actions' => [
                    'usage_time' => 'night',
                    'max_frequency' => 2,
                    'schedule_rule' => 'Mulai 1x/minggu pada malam hari; naikkan bertahap menjadi 2x/minggu jika tidak ada iritasi.',
                ],
                'certainty_factor' => 0.95,
                'explanation' => 'Membatasi frekuensi awal mencegah retinoid dermatitis pada pemula.',
                'reference_source' => 'AAD & Cleveland Clinic',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F03',
                'rule_type' => 'frequency',
                'conditions' => [
                    'ingredient' => 'Retinol',
                    'experience_level' => 'intermediate',
                ],
                'actions' => [
                    'usage_time' => 'night',
                    'max_frequency' => 4,
                    'schedule_rule' => 'Gunakan 3–4x/minggu selang-seling hari pada malam hari.',
                ],
                'certainty_factor' => 0.90,
                'explanation' => 'Kulit yang sudah terbiasa dapat menerima frekuensi lebih tinggi untuk memaksimalkan regenerasi sel.',
                'reference_source' => 'Dr. Kseniya Kobets (2023)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F04',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'AHA (Glycolic/Lactic Acid)'],
                'actions' => [
                    'usage_time' => 'night',
                    'max_frequency' => 3,
                    'schedule_rule' => 'Gunakan maksimal 2–3x/minggu di malam hari. TIDAK boleh digunakan di malam yang sama dengan Retinol.',
                ],
                'certainty_factor' => 0.95,
                'explanation' => 'Eksfoliasi permukaan yang berlebihan menipiskan pelindung barrier kulit.',
                'reference_source' => 'The INKEY List (2025) & AAD',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F05',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'BHA (Salicylic Acid)'],
                'actions' => [
                    'usage_time' => 'both',
                    'max_frequency' => 3,
                    'schedule_rule' => 'Gunakan 2–3x/minggu. Jika digunakan pagi hari, WAJIB diikuti dengan Sunscreen.',
                ],
                'certainty_factor' => 0.90,
                'explanation' => 'BHA larut dalam minyak dan bekerja aktif di pori; frekuensi 2-3x cukup untuk pembersihan sebum.',
                'reference_source' => 'AAD Guidelines',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F06',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'Vitamin C (L-Ascorbic Acid)'],
                'actions' => [
                    'usage_time' => 'morning',
                    'max_frequency' => 7,
                    'schedule_rule' => 'Gunakan setiap pagi hari sebelum sunscreen untuk proteksi antioksidan optimal.',
                ],
                'certainty_factor' => 0.90,
                'explanation' => 'Vitamin C memberikan efisiensi tertinggi saat berinteraksi dengan radiasi UV di siang hari.',
                'reference_source' => 'Pinnell et al. (Dermatologic Surgery)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F07',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'Niacinamide'],
                'actions' => [
                    'usage_time' => 'both',
                    'max_frequency' => 7,
                    'schedule_rule' => 'Aman digunakan setiap hari pada sesi pagi dan malam hari.',
                ],
                'certainty_factor' => 0.95,
                'explanation' => 'Niacinamide memiliki profil keamanan tinggi dan tidak menyebabkan fotosensitivitas.',
                'reference_source' => 'Journal of Cosmetic Dermatology',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F08',
                'rule_type' => 'frequency',
                'conditions' => ['ingredient' => 'Benzoyl Peroxide (BPO)'],
                'actions' => [
                    'usage_time' => 'night',
                    'max_frequency' => 7,
                    'schedule_rule' => 'Mulai dari konsentrasi terkecil (2.5%) sebagai spot treatment di area berjerawat aktif.',
                ],
                'certainty_factor' => 0.90,
                'explanation' => 'Konsentrasi 2.5% memiliki efikasi antibakteri setara dengan 10% namun dengan risiko iritasi jauh lebih minim.',
                'reference_source' => 'American Academy of Dermatology (AAD)',
                'is_active' => true,
            ],
            [
                'rule_code' => 'F09',
                'rule_type' => 'layering',
                'conditions' => [
                    'has_exfoliant' => true,
                    'has_retinol' => true,
                ],
                'actions' => [
                    'pattern' => 'skin_cycling',
                    'schedule' => [
                        'malam_1' => 'Exfoliation Night (AHA/BHA)',
                        'malam_2' => 'Retinoid Night (Retinol)',
                        'malam_3' => 'Recovery Night (Hydration & Ceramide)',
                        'malam_4' => 'Recovery Night (Hydration & Ceramide)',
                    ],
                    'message' => 'Sistem mengaktifkan metode Skin Cycling 4-malam untuk memisahkan waktu pakai Exfoliant dan Retinol agar barrier kulit tetap sehat.',
                ],
                'certainty_factor' => 0.98,
                'explanation' => 'Skin cycling memberi waktu pemulihan sel 48 jam antar bahan aktif kuat untuk mencegah over-exfoliation.',
                'reference_source' => 'Cleveland Clinic & The INKEY List (2025)',
                'is_active' => true,
            ],
        ];

        foreach ($rules as $rule) {
            Rule::updateOrCreate(
                ['rule_code' => $rule['rule_code']],
                $rule
            );
        }
    }
}
