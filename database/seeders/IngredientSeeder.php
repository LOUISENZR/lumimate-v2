<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            [
                'ingredient_name' => 'Retinol',
                'slug' => 'retinol',
                'category' => 'actives',
                'function' => 'Anti-aging, anti-acne, menstimulasi pergantian sel (cell turnover) dan produksi kolagen.',
                'usage_time' => 'night',
                'max_frequency' => 2, // Pemula 1-2x/minggu
                'irritation_level' => 'high',
                'safe_pregnancy' => false,
                'reference_source' => 'American Academy of Dermatology (AAD) & NCBI (2023)',
            ],
            [
                'ingredient_name' => 'Niacinamide',
                'slug' => 'niacinamide',
                'category' => 'antioxidant',
                'function' => 'Mencerahkan kulit, mengontrol produksi sebum, menyamarkan pori, dan memperkuat skin barrier.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD',
            ],
            [
                'ingredient_name' => 'AHA (Glycolic/Lactic Acid)',
                'slug' => 'aha-glycolic-lactic-acid',
                'category' => 'exfoliant',
                'function' => 'Eksfoliasi sel kulit mati di permukaan, meratakan tekstur kulit, dan mencerahkan kulit kusam.',
                'usage_time' => 'night',
                'max_frequency' => 3,
                'irritation_level' => 'medium',
                'safe_pregnancy' => true,
                'reference_source' => 'American Academy of Dermatology (AAD) & Cleveland Clinic',
            ],
            [
                'ingredient_name' => 'BHA (Salicylic Acid)',
                'slug' => 'bha-salicylic-acid',
                'category' => 'exfoliant',
                'function' => 'Eksfoliasi ke dalam pori-pori yang tersumbat, mengatasi komedo, mengontrol minyak, dan meredakan jerawat aktif.',
                'usage_time' => 'both',
                'max_frequency' => 3,
                'irritation_level' => 'medium',
                'safe_pregnancy' => false, // Salicylic acid dosis tinggi dihindari saat hamil
                'reference_source' => 'American Academy of Dermatology (AAD)',
            ],
            [
                'ingredient_name' => 'Vitamin C (L-Ascorbic Acid)',
                'slug' => 'vitamin-c-l-ascorbic-acid',
                'category' => 'antioxidant',
                'function' => 'Antioksidan poten melawan radikal bebas UV, mencerahkan hiperpigmentasi/flek, dan merangsang kolagen.',
                'usage_time' => 'morning',
                'max_frequency' => 7,
                'irritation_level' => 'medium',
                'safe_pregnancy' => true,
                'reference_source' => 'Pinnell, S.R., et al. (Dermatologic Surgery)',
            ],
            [
                'ingredient_name' => 'Hyaluronic Acid',
                'slug' => 'hyaluronic-acid',
                'category' => 'moisturizer',
                'function' => 'Humektan kuat yang mengikat dan menahan kadar air di dalam jaringan kulit agar kenyal dan terhidrasi.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'NCBI & Cleveland Clinic',
            ],
            [
                'ingredient_name' => 'Ceramide',
                'slug' => 'ceramide',
                'category' => 'moisturizer',
                'function' => 'Memperbaiki dan memperkuat lipid bilayer skin barrier, mengunci kelembapan, serta mencegah iritasi.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'American Academy of Dermatology (AAD)',
            ],
            [
                'ingredient_name' => 'Benzoyl Peroxide (BPO)',
                'slug' => 'benzoyl-peroxide',
                'category' => 'actives',
                'function' => 'Antibakteri poten pembunuh kuman C. acnes dan meredakan peradangan jerawat aktif dengan cepat.',
                'usage_time' => 'night',
                'max_frequency' => 7,
                'irritation_level' => 'high',
                'safe_pregnancy' => true,
                'reference_source' => 'American Academy of Dermatology (AAD)',
            ],
            [
                'ingredient_name' => 'Azelaic Acid',
                'slug' => 'azelaic-acid',
                'category' => 'actives',
                'function' => 'Anti-inflamasi gentle, meredakan kemerahan, mengatasi jerawat, dan memudarkan bekas jerawat PIE/PIH.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'Journal of Cosmetic Dermatology & AAD',
            ],
            [
                'ingredient_name' => 'Peptides',
                'slug' => 'peptides',
                'category' => 'actives',
                'function' => 'Rantai asam amino yang memberi sinyal produksi kolagen & elastin, meningkatkan elastisitas kulit.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'NCBI (2023)',
            ],
            [
                'ingredient_name' => 'Centella Asiatica (Cica)',
                'slug' => 'centella-asiatica-cica',
                'category' => 'soothing',
                'function' => 'Menenangkan kulit kemerahan, meredakan iritasi, mempercepat penyembuhan luka jerawat, dan merawat skin barrier.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'NCBI & Journal of Cosmetic Dermatology',
            ],
            [
                'ingredient_name' => 'Sunscreen (UV Filters)',
                'slug' => 'sunscreen-uv-filters',
                'category' => 'sunscreen',
                'function' => 'Perlindungan spektrum luas terhadap sinar UVA & UVB, mencegah penuaan dini, sunburn, dan hiperpigmentasi.',
                'usage_time' => 'morning',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'American Academy of Dermatology (AAD) - Mandatory AM Step',
            ],
            [
                'ingredient_name' => 'Bakuchiol',
                'slug' => 'bakuchiol',
                'category' => 'actives',
                'function' => 'Alternatif retinol alami berbasis tanaman yang gentle, anti-aging, aman untuk kulit sensitif dan ibu hamil.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'British Journal of Dermatology',
            ],
            [
                'ingredient_name' => 'Vitamin E (Tocopherol)',
                'slug' => 'vitamin-e-tocopherol',
                'category' => 'antioxidant',
                'function' => 'Antioksidan larut lemak yang bersinergi meningkatkan fotoproteksi Vitamin C hingga 8x lipat.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'Pinnell, S.R., et al. (Dermatologic Surgery)',
            ],
            [
                'ingredient_name' => 'Squalane',
                'slug' => 'squalane',
                'category' => 'moisturizer',
                'function' => 'Emollient ringan non-komedogenik yang menyerupai sebum alami kulit, melembapkan tanpa rasa lengket.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'NCBI',
            ],
            [
                'ingredient_name' => 'Panthenol (Pro-Vitamin B5)',
                'slug' => 'panthenol-pro-vitamin-b5',
                'category' => 'soothing',
                'function' => 'Menenangkan iritasi, memperbaiki regenerasi jaringan kulit, dan memberikan hidrasi mendalam.',
                'usage_time' => 'both',
                'max_frequency' => 7,
                'irritation_level' => 'low',
                'safe_pregnancy' => true,
                'reference_source' => 'NCBI & J. Cosm. Derm.',
            ],
        ];

        foreach ($ingredients as $data) {
            Ingredient::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
