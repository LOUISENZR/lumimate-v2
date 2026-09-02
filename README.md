<div align="center">

# LumiMate

**Smart Skincare Routine Planner & Tracker**

An expert system that helps users understand their skin, avoid harmful ingredient combinations, and follow an evidence-based skincare routine — powered by Forward Chaining inference and Certainty Factor scoring.

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=flat-square&logo=chart.js&logoColor=white)](https://www.chartjs.org)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=flat-square)](LICENSE)

</div>

---

## Table of Contents

- [Overview](#overview)
- [Problem Statement](#problem-statement)
- [Core Features](#core-features)
- [How It Works](#how-it-works)
- [Tech Stack](#tech-stack)
- [Database Schema](#database-schema)
- [Getting Started](#getting-started)
- [Roadmap](#roadmap)
- [Scientific Foundation](#scientific-foundation)
- [License](#license)

---

## Overview

Many skincare users — particularly teenagers and young adults in Indonesia — struggle with applying products in the correct order, combining active ingredients that irritate the skin, or using actives at a frequency that damages the skin barrier.

**LumiMate** addresses this with a web-based expert system that simulates the reasoning of a dermatologist. Users go through a structured consultation, input the products they use, and receive a personalized routine — complete with ingredient conflict warnings and a schedule that accounts for safe frequency limits.

Every recommendation and warning the system produces is backed by a cited scientific or clinical source, rather than general assumptions.

## Problem Statement

| # | Problem | Consequence |
|---|---|---|
| 1 | Incorrect product application order | Reduced absorption of active ingredients; lower product effectiveness |
| 2 | Limited understanding of active ingredients | Users purchase products that don't address their actual needs |
| 3 | Conflicting ingredient combinations | Irritation, redness, flaking, or a damaged skin barrier (14–28 day recovery) |
| 4 | Overuse of active ingredients | Over-exfoliation syndrome; increased skin sensitivity |
| 5 | Inconsistent routine adherence | Suboptimal long-term results |
| 6 | Difficulty tracking skin progress | No way to evaluate whether a product is actually working |

## Core Features

**Skin Consultation** — A multi-step questionnaire (adapted from the Baumann Skin Type Indicator) determines skin type, primary concerns, sensitivity level, and product experience level.

**Product Management** — Add products from a curated database or input custom products manually, including their active ingredients.

**Ingredient Conflict Checker** — Automatically flags ingredient pairs as *risky*, *caution*, *safe*, or *recommended*, based on a scientifically-referenced conflict matrix (e.g. Retinol + AHA/BHA, Vitamin C + Retinol).

**Routine Generator** — Builds a correctly layered AM/PM routine (thinnest to thickest texture) and schedules conflicting actives on alternating days using a skin cycling pattern.

**Daily Tracker & Streaks** — Lets users check off completed routine steps and maintain a consistency streak.

**Progress Dashboard** — Visualizes hydration consistency and routine adherence over time using Chart.js.

**Safety Guardrails** — Enforces mandatory filters for pregnancy/breastfeeding (blocking Retinol and high-dose Salicylic Acid), missing sunscreen, and over-complex routines for beginners.

## How It Works

LumiMate is built as a rule-based expert system with four components:

```
Knowledge Base    → Skin types, ingredients, conflicts, and routine rules
Rule Base         → IF–THEN rules encoding dermatological knowledge
Inference Engine  → Forward Chaining: matches user facts against rules
User Interface    → Consultation form, dashboard, recommendation views
```

**Forward Chaining** is a data-driven inference method:

```
User input (skin type, concerns, sensitivity, products)
        ↓
Facts loaded into working memory
        ↓
Inference engine matches facts against the rule base
        ↓
Matched rules fire, producing new conclusions
        ↓
Recommendations, conflict warnings, and schedules are generated
```

**Certainty Factor (CF)** expresses how confident the system is in a given recommendation, from `-1.0` (avoid) to `+1.0` (strongly recommended), combined across rules using:

```
CF_combined = CF1 + CF2 × (1 − CF1)
```

The consultation questionnaire adapts the four dimensions of the **Baumann Skin Type Indicator (BSTI)** — Oily/Dry, Sensitive/Resistant, Pigmented/Non-Pigmented, and Wrinkle-Prone/Tight — extended with additional modules for ingredient conflict detection, experience level, and special conditions such as pregnancy or allergies.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel (PHP) |
| Database | MySQL |
| Templating | Blade |
| Styling | Tailwind CSS |
| Data Visualization | Chart.js |

## Database Schema

| Table | Purpose |
|---|---|
| `users` | Account and authentication data |
| `consultations` | Skin type, concerns, sensitivity, experience level, and special conditions per user |
| `ingredients` | Master ingredient data: category, usage time, max frequency, irritation level, pregnancy safety, source reference |
| `ingredient_conflicts` | Pairwise ingredient risk level, explanation, recommended solution, and source reference |

Full column-level schema is documented in [`docs/DATABASE.md`](docs/DATABASE.md).

## Getting Started

```bash
git clone https://github.com/<username>/lumimate.git
cd lumimate

composer install
cp .env.example .env
php artisan key:generate

# configure DB credentials in .env, then:
php artisan migrate --seed
php artisan serve
```

## Roadmap

**Version 1 (MVP)**
- Authentication, skin consultation, product management
- Ingredient conflict checker and routine generator
- Daily checklist tracker and progress dashboard

**Version 2**
- Daily reminders (push notification / email digest)
- Weekly and monthly consistency charts
- Adaptive recommendations based on usage history and feedback
- Ingredient scanner from product label photos

**Version 3**
- AI-based skin analysis from photos (computer vision)
- Conversational skincare consultation chatbot (NLP)
- OCR ingredient extraction from packaging
- Machine learning-based personalized recommendations

## Scientific Foundation

Every rule, ingredient entry, and conflict pairing in LumiMate is required to cite a verifiable source. Key references include:

- Baumann, L. (2016). *Validation of a Questionnaire to Diagnose the Baumann Skin Type in All Ethnicities and in Various Geographic Locations.* Journal of Cosmetic Dermatology and Skin Aesthetics.
- Kaminska et al. (2024–2025). *Baumann Skin Type Questionnaire (BSTQ): creation and validation of the Polish language version* (Parts I & II). PubMed Central.
- American Academy of Dermatology (AAD) — ingredient safety and routine order guidelines.
- Pinnell, S.R., et al. *Topical L-ascorbic acid: percutaneous absorption studies.* Dermatologic Surgery.
- Cleveland Clinic Health — *How to Order Your Skin Care Routine.*
- NCBI/PMC (2023). *Improvement of mild photoaged facial skin by supramolecular retinol.*

The full reference list, including the ingredient conflict matrix and rule base citations, is documented in [`docs/METHODOLOGY.md`](docs/METHODOLOGY.md).

## License

This project is licensed under the [MIT License](LICENSE).

---

<div align="center">
<sub>LumiMate — Ritual Adalah Segalanya.</sub>
</div>