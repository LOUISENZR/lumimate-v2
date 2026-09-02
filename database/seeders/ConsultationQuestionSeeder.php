<?php

namespace Database\Seeders;

use App\Models\ConsultationOption;
use App\Models\ConsultationQuestion;
use Illuminate\Database\Seeder;

class ConsultationQuestionSeeder extends Seeder
{
    /**
     * Getter soal + opsi konsultasi. Idempotent: updateOrCreate per question_key / value.
     */
    public function run(): void
    {
        // ========== MODUL A — Identifikasi Jenis Kulit (BSTI Dimensi 1) ==========

        $this->upsertQuestion(
            key: 'a1_sebum_condition',
            module: 'A',
            title: 'Bagaimana kondisi wajah Anda sekitar 2-3 jam setelah mencuci muka, tanpa menggunakan produk apapun?',
            description: 'Metode ini adalah standar klinis BSTI untuk menilai aktivitas sebaceous gland tanpa interferensi produk.',
            categoryLabel: 'KONDISI SAAT INI',
            inputType: 'radio',
            imagePath: 'images/consultation/a1-sebum.jpg',
            order: 1,
            options: [
                ['label' => 'Berminyak di seluruh wajah (T-zone dan pipi)', 'description' => 'Produksi sebum tinggi di semua zona; termasuk tipe yang paling umum pada remaja Asia', 'value' => 'oily'],
                ['label' => 'Berminyak di dahi, hidung, dagu; pipi normal atau kering', 'description' => 'T-zone oily + U-zone normal/dry; paling umum secara global', 'value' => 'combination'],
                ['label' => 'Wajah terasa kencang, tertarik, kadang mengelupas', 'description' => 'Produksi lipid kurang; skin barrier cenderung lemah', 'value' => 'dry'],
                ['label' => 'Tidak ada keluhan; wajah nyaman dan seimbang', 'description' => 'Keseimbangan sebum dan hidrasi optimal', 'value' => 'normal'],
                ['label' => 'Cepat merah, gatal, atau bereaksi terhadap produk baru', 'description' => 'Flag tambahan; diproses lebih lanjut di Modul C', 'value' => 'sensitive'],
            ]
        );

        $this->upsertQuestion(
            key: 'a2_pore_size',
            module: 'A',
            title: 'Bagaimana tampilan pori-pori Anda, terutama di area hidung dan pipi?',
            description: 'Ukuran pori berkorelasi langsung dengan tingkat produksi sebum.',
            categoryLabel: 'TAMPILAN PORI-PORI',
            inputType: 'radio',
            imagePath: 'images/consultation/a2-pores.jpg',
            order: 2,
            options: [
                ['label' => 'Pori-pori terlihat jelas dan besar', 'description' => 'Perkuat rekomendasi BHA/Salicylic Acid; kemungkinan oily/combination', 'value' => 'large'],
                ['label' => 'Pori-pori sedang, terlihat jika dilihat dekat', 'description' => 'Netral; tidak mempengaruhi klasifikasi utama', 'value' => 'medium'],
                ['label' => 'Pori-pori hampir tidak terlihat', 'description' => 'Kemungkinan normal atau dry skin', 'value' => 'small'],
            ]
        );

        $this->upsertQuestion(
            key: 'a3_reaction_history',
            module: 'A',
            title: 'Apakah Anda pernah mengalami reaksi berikut setelah menggunakan produk skincare baru?',
            description: 'Pilih semua reaksi yang pernah Anda alami.',
            categoryLabel: 'RIWAYAT REAKSI',
            inputType: 'multi_select',
            imagePath: 'images/consultation/a3-reaction.jpg',
            order: 3,
            options: [
                ['label' => 'Kemerahan yang berlangsung lebih dari 1 jam', 'description' => 'Menandakan kulit mudah bereaksi', 'value' => 'frequent_redness'],
                ['label' => 'Rasa perih, terbakar, atau gatal segera setelah aplikasi', 'description' => 'Reaksi langsung terhadap kandungan produk', 'value' => 'burning_sensation'],
                ['label' => 'Jerawat atau breakout baru setelah ganti produk', 'description' => 'Produk mungkin menyumbat pori atau memicu iritasi', 'value' => 'occasional_breakout'],
                ['label' => 'Tidak pernah mengalami reaksi buruk', 'description' => 'Kulit umumnya toleran terhadap produk baru', 'value' => 'none'],
            ]
        );

        // ========== MODUL B — Identifikasi Masalah Kulit ==========

        $this->upsertQuestion(
            key: 'concerns',
            module: 'B',
            title: 'Pilih masalah kulit utama yang ingin Anda atasi. Urutkan dari yang paling mengganggu:',
            description: 'Pilih semua masalah kulit yang sedang Anda hadapi.',
            categoryLabel: 'MASALAH KULIT',
            inputType: 'multi_select',
            imagePath: 'images/consultation/b-concerns.jpg',
            order: 4,
            options: [
                ['label' => 'Bekas jerawat / flek hitam', 'description' => 'Hiperpigmentasi pasca-inflamasi', 'value' => 'hyperpigmentation'],
                ['label' => 'Kulit kusam / tidak cerah', 'description' => 'Tone kulit tidak merata', 'value' => 'dullness'],
                ['label' => 'Tanda penuaan dini / kerutan', 'description' => 'Garis halus dan kerutan awal', 'value' => 'aging'],
                ['label' => 'Jerawat aktif (acne)', 'description' => 'Breakout aktif dan meradang', 'value' => 'acne'],
                ['label' => 'Kulit kering & dehidrasi', 'description' => 'Kurang kelembaban', 'value' => 'dehydration'],
                ['label' => 'Pori-pori besar', 'description' => 'Pori-pori membesar', 'value' => 'enlarged_pores'],
                ['label' => 'Kulit sensitif / mudah iritasi', 'description' => 'Reaktivitas kulit tinggi', 'value' => 'sensitivity'],
                ['label' => 'Tekstur kulit tidak rata', 'description' => 'Permukaan kulit kasar', 'value' => 'texture'],
            ]
        );

        // ========== MODUL C — Sensitivitas & Toleransi (BSTI Dimensi 2 + Perluasan) ==========

        $this->upsertQuestion(
            key: 'c1_reactivity',
            module: 'C',
            title: 'Bagaimana reaksi kulit Anda ketika pertama kali mencoba produk skincare baru?',
            description: 'Menentukan tingkat reaktivitas dan toleransi kulit Anda terhadap bahan aktif.',
            categoryLabel: 'REAKTIVITAS KULIT',
            inputType: 'radio',
            imagePath: 'images/consultation/c1-reactivity.jpg',
            order: 5,
            options: [
                ['label' => 'Hampir tidak pernah bereaksi; kulit mudah menerima produk baru', 'description' => 'Toleransi tinggi; bisa mulai dari konsentrasi bahan aktif menengah', 'value' => 'resistant'],
                ['label' => 'Sesekali bereaksi; biasanya hilang dalam 1-2 hari', 'description' => 'Mulai dari konsentrasi rendah; pantau 2 minggu sebelum menaikkan', 'value' => 'mildly_sensitive'],
                ['label' => 'Sering bereaksi; kemerahan atau gatal yang cukup mengganggu', 'description' => 'Hindari bahan dengan iritasi_level = high; rekomendasikan gentle actives', 'value' => 'sensitive'],
                ['label' => 'Sangat reaktif; hampir setiap produk baru menimbulkan masalah', 'description' => 'Hanya bahan dengan iritasi_level = low; pertimbangkan saran konsultasi dokter', 'value' => 'very_sensitive'],
            ]
        );

        $this->upsertQuestion(
            key: 'c2_experience_level',
            module: 'C',
            title: 'Seberapa lama Anda sudah menggunakan bahan aktif skincare seperti Retinol, AHA, BHA, atau Vitamin C secara rutin?',
            description: 'Frekuensi dan konsentrasi bahan aktif harus disesuaikan dengan lamanya pengguna terpapar.',
            categoryLabel: 'TINGKAT PENGALAMAN',
            inputType: 'radio',
            imagePath: 'images/consultation/c2-experience.jpg',
            order: 6,
            options: [
                ['label' => 'Belum pernah menggunakan bahan aktif', 'description' => 'Retinol: mulai 1x/minggu; AHA: mulai 1x/minggu; prioritaskan toleransi', 'value' => 'beginner'],
                ['label' => 'Sudah mencoba, < 6 bulan, sesekali iritasi', 'description' => 'Retinol: 2x/minggu; AHA: hingga 2x/minggu', 'value' => 'intermediate'],
                ['label' => 'Sudah rutin > 6 bulan, kulit terbiasa', 'description' => 'Retinol: 3-4x/minggu; AHA: hingga 3x/minggu sesuai toleransi', 'value' => 'advanced'],
            ]
        );

        $this->upsertQuestion(
            key: 'c3_retinol_tolerance',
            module: 'C',
            title: 'Jika Anda pernah menggunakan Retinol atau produk Vitamin A, bagaimana reaksi kulit Anda?',
            description: 'Populasi Asia memiliki reaktivitas lebih tinggi terhadap retinol; hasil ini menentukan frekuensi retinol Anda.',
            categoryLabel: 'TOLERANSI RETINOL',
            inputType: 'radio',
            imagePath: 'images/consultation/c3-retinol.jpg',
            order: 7,
            options: [
                ['label' => 'Tidak ada reaksi; kulit baik-baik saja', 'description' => 'Sistem dapat merekomendasikan frekuensi normal', 'value' => 'tolerant'],
                ['label' => 'Ada sedikit pengelupasan awal, sudah hilang', 'description' => 'Mulai dari 1x/minggu, naik bertahap', 'value' => 'mild_sensitive'],
                ['label' => 'Iritasi cukup parah; kemerahan dan perih berkepanjangan', 'description' => 'Rekomendasikan Bakuchiol sebagai alternatif retinol', 'value' => 'high_sensitive'],
                ['label' => 'Belum pernah mencoba', 'description' => 'Paksa mulai dari frekuensi terkecil (1x/minggu)', 'value' => 'unknown'],
            ]
        );

        $this->upsertQuestion(
            key: 'c4_special_conditions',
            module: 'C',
            title: 'Apakah Anda memiliki salah satu dari kondisi berikut ini?',
            description: 'Beberapa kondisi akan menjadi filter yang tidak bisa dilewati sistem.',
            categoryLabel: 'KONDISI KHUSUS',
            inputType: 'multi_select',
            imagePath: 'images/consultation/c4-conditions.jpg',
            order: 8,
            options: [
                ['label' => 'Sedang hamil atau menyusui', 'description' => 'Blokir total Retinol dan Salicylic Acid dosis tinggi', 'value' => 'pregnant_or_nursing'],
                ['label' => 'Memiliki riwayat alergi fragrance', 'description' => 'Tampilkan warning pada produk mengandung fragrance', 'value' => 'fragrance_allergy'],
                ['label' => 'Sedang dalam perawatan dokter kulit', 'description' => 'Tampilkan disclaimer konsultasi dengan dokter', 'value' => 'dermatologist_treatment'],
                ['label' => 'Tidak ada kondisi khusus', 'description' => 'Tidak ada filter tambahan; sistem berjalan normal', 'value' => 'none'],
            ]
        );
    }

    protected function upsertQuestion(
        string $key,
        string $module,
        string $title,
        string $description,
        string $categoryLabel,
        string $inputType,
        string $imagePath,
        int $order,
        array $options
    ): void {
        $question = ConsultationQuestion::updateOrCreate(
            ['question_key' => $key],
            [
                'module' => $module,
                'title' => $title,
                'description' => $description,
                'category_label' => $categoryLabel,
                'input_type' => $inputType,
                'image_path' => $imagePath,
                'order_column' => $order,
                'is_active' => true,
            ]
        );

        foreach ($options as $index => $option) {
            ConsultationOption::updateOrCreate(
                [
                    'question_id' => $question->id,
                    'value' => $option['value'],
                ],
                [
                    'label' => $option['label'],
                    'description' => $option['description'],
                    'order_column' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}