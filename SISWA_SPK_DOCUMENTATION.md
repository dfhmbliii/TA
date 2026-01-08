# SPK Analysis - Siswa/Mahasiswa Interface

## Overview
Fitur SPK Analysis untuk role siswa/mahasiswa telah dirombak dengan pendekatan yang lebih sederhana dan user-friendly:

### Perubahan Utama:
1. **Dropdown untuk kriteria kualitatif**:
   - Minat (4 pilihan)
   - Bakat (4 pilihan)
   - Prospek Karir (4 pilihan)

2. **Input nilai untuk mata pelajaran relevan**:
   - Bahasa Inggris (0-100)
   - Informatika/TIK (0-100)
   - Matematika (0-100)
   - Seni Budaya (0-100)

## Files Created/Modified

### New Files:
1. **app/Http/Controllers/SiswaSpkController.php**
   - `showForm()`: Menampilkan form input SPK untuk siswa
   - `calculate()`: Menghitung rekomendasi prodi menggunakan bobot AHP dari kriteria
   - Private methods untuk scoring:
     - `getMinatScore()`: Mapping minat ke prodi (0-100)
     - `getBakatScore()`: Mapping bakat ke prodi (0-100)
     - `getNilaiAkademikScore()`: Weighted average nilai mata pelajaran
     - `getProspekScore()`: Mapping prospek karir ke prodi (0-100)

2. **resources/views/spk/siswa-form.blade.php**
   - Form dengan dropdown untuk Minat, Bakat, Prospek Karir
   - Input number (0-100) untuk 4 mata pelajaran
   - Menampilkan info kriteria AHP dan program studi tersedia
   - Validation real-time untuk input nilai

3. **resources/views/spk/siswa-result.blade.php**
   - Menampilkan ranking program studi berdasarkan score
   - Summary card untuk rekomendasi terbaik
   - Detail breakdown per kriteria (collapsible)
   - Fakultas color coding
   - Action buttons: print, view prodi detail, back to dashboard

### Modified Files:
1. **routes/web.php**
   - Added routes:
     - `GET /siswa/spk/form` → siswa-spk.form
     - `POST /siswa/spk/calculate` → siswa-spk.calculate
   - Under `check.role:siswa` middleware

2. **resources/views/layouts/app.blade.php**
   - Updated "SPK Analysis" menu untuk role siswa
   - Changed from `route('spk.index')` to `route('siswa-spk.form')`

3. **resources/views/siswa/dashboard.blade.php**
   - Updated tombol "Mulai Analisis" dan "Lihat Hasil"
   - Changed from `route('spk.index')` to `route('siswa-spk.form')`

## Algorithm Flow

### 1. Input Phase (siswa-form.blade.php)
```
User selects:
- Minat: dropdown (1 choice)
- Bakat: dropdown (1 choice)
- Prospek Karir: dropdown (1 choice)
- Nilai Mapel: 4 inputs (0-100)
```

### 2. Calculation Phase (SiswaSpkController::calculate)
```php
For each Prodi:
    score = 0
    
    // Get kriteria weights from AHP (admin configured)
    minat_weight = Kriteria['MINAT']->bobot
    bakat_weight = Kriteria['BAKAT']->bobot
    nilai_weight = Kriteria['NILAI']->bobot
    prospek_weight = Kriteria['PROSPEK']->bobot
    
    // Calculate weighted scores
    score += getMinatScore(input, prodi) * minat_weight
    score += getBakatScore(input, prodi) * bakat_weight
    score += getNilaiAkademikScore(nilai_mapel, prodi) * nilai_weight
    score += getProspekScore(input, prodi) * prospek_weight
    
Sort prodis by score (descending)
```

### 3. Scoring Logic

**Minat Mapping:**
- Proses Bisnis → Sistem Informasi Bisnis, Manajemen Informatika (100 points)
- Pemrograman → Teknik Informatika, Sistem Informasi, RPL (100 points)
- Seni Estetika → Desain Grafis, DKV, Multimedia (100 points)
- Fisika Matematika → Teknik Elektro, Teknik Komputer (100 points)
- Others → 50 points

**Bakat Mapping:**
- Problem Solving → Sistem Informasi, Manajemen Informatika (100)
- Debugging → Teknik Informatika, RPL (100)
- Elektronik → Teknik Elektro, Teknik Komputer (100)
- Kepekaan Visual → Desain Grafis, DKV (100)
- Others → 50

**Nilai Akademik:**
```
For each prodi:
    alternatives = prodi->alternatives (mata pelajaran)
    
    weighted_sum = 0
    total_weight = 0
    
    For each alternative:
        mapel_key = getMapelKey(alternative->nama_alternatif)
        nilai = user_input[mapel_key]
        weight = alternative->bobot (from AHP prodi analysis)
        
        weighted_sum += nilai * weight
        total_weight += weight
    
    score = weighted_sum / total_weight
```

**Prospek Karir Mapping:**
- System Analyst → Sistem Informasi, Manajemen Informatika (100)
- Software Developer → Teknik Informatika, RPL (100)
- Network Engineer → Teknik Komputer, Jaringan Komputer (100)
- Graphic Designer → Desain Grafis, DKV (100)
- Others → 50

### 4. Result Phase (siswa-result.blade.php)
```
Display:
- Top recommendation (card highlight)
- Full ranking table
- Score breakdown per kriteria (collapsible)
- Category badge (Sangat Sesuai/Sesuai/Cukup/Kurang)
```

## Category Thresholds
```php
score >= 80 → "Sangat Sesuai" (badge: success/green)
score >= 70 → "Sesuai" (badge: primary/blue)
score >= 60 → "Cukup Sesuai" (badge: info/cyan)
score < 60  → "Kurang Sesuai" (badge: warning/yellow)
```

## Integration with AHP System

### Kriteria (Admin Setup):
Admin mengkonfigurasi kriteria melalui `/spk/analysis`:
- Minat
- Bakat
- Nilai Akademik
- Prospek Karir

Setiap kriteria memiliki **bobot** (hasil AHP pairwise comparison).

### Prodi Alternatives (Admin Setup):
Admin mengkonfigurasi alternatif (mata pelajaran) per prodi melalui `/spk/prodi/{id}/analysis`:
- Bahasa Inggris
- Informatika
- Matematika
- Seni Budaya
- etc.

Setiap alternatif memiliki **bobot** (hasil AHP pairwise comparison).

### Siswa Calculation:
Sistem menggunakan:
1. **Bobot kriteria** dari AHP kriteria analysis
2. **Bobot alternatif** dari AHP prodi analysis
3. **Input siswa** (dropdown & nilai mapel)

Final score = Weighted sum of all criteria scores.

## Database Schema

### SpkResult Table (existing):
```php
siswa_id → foreign key to siswas.id
total_score → final calculated score
category → "Sangat Sesuai" / "Sesuai" / "Cukup Sesuai" / "Kurang Sesuai"
rekomendasi_prodi → top recommended prodi name
criteria_breakdown → JSON with detailed scores per criteria
```

## User Flow

1. Siswa login → Dashboard
2. Click "Mulai Analisis" atau menu "SPK Analysis"
3. See form with:
   - Info sidebar (kriteria weights, available prodi)
   - Dropdown: Minat, Bakat, Prospek Karir
   - Input: Nilai 4 mata pelajaran (0-100)
4. Click "Hitung Rekomendasi"
5. See results page with:
   - Top recommendation highlighted
   - Full ranking table
   - Score breakdown (collapsible detail)
   - Action buttons (back, view detail, print)
6. Click "Lihat Detail Program Studi" → prodi detail page
7. Result saved to database (SpkResult table)

## Validation

### Client-side (JavaScript):
- Nilai must be 0-100
- Real-time validation on input
- Form submission check

### Server-side (Laravel):
```php
'minat' => 'required|string'
'bakat' => 'required|string'
'prospek_karir' => 'required|string'
'nilai_mapel' => 'required|array'
'nilai_mapel.*' => 'required|numeric|min:0|max:100'
```

## Advantages

1. **Simplified UX**: No complex sliders, just dropdowns and simple inputs
2. **AHP-backed**: Uses admin-configured weights from full AHP analysis
3. **Transparent**: Shows breakdown of scores per criteria
4. **Flexible**: Easy to add more criteria or alternatives
5. **Personalized**: Each prodi has different weights for different mata pelajaran

## Future Enhancements

1. Add more criteria options (e.g., multiple selection for Minat)
2. Historical comparison (track how score changes over time)
3. Export results to PDF with detailed analysis
4. Comparison tool (compare 2-3 prodi side by side)
5. Integration with actual academic transcript (auto-fill nilai)
6. Recommendation explanation (why this prodi matches you)
