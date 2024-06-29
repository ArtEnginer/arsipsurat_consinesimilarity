<?php
session_start();
include "login/ceksession.php";

// Fungsi untuk membersihkan teks
function clean_text($text) {
    $text = strtolower($text);
    $text = preg_replace("/\d+/", "", $text);
    $text = preg_replace("/[^A-Za-z0-9 ]/", "", $text); // Hanya mempertahankan huruf dan angka
    $text = trim($text);
    return $text;
}

// Fungsi untuk menghitung kesamaan menggunakan Cosine Similarity
function calculate_similarity($input_text, $surat_keluar_texts) {
    $input_text_clean = clean_text($input_text);
    $surat_keluar_clean = array_map('clean_text', $surat_keluar_texts);
    
    // Gabungkan teks input dan surat keluar
    $all_docs = array_merge([$input_text_clean], $surat_keluar_clean);
    
    // Hitung TF-IDF dari teks
    $tfidf_vectorizer = new \datasuratmasuk\Admin\TfIdfDocument($all_docs);
    
    // Hitung kesamaan kosinus antara input dan semua surat keluar
    $cosine_similarities = [];
    foreach ($surat_keluar_clean as $index => $text) {
        $cosine_similarities[$index] = $tfidf_vectorizer->similarity($input_text_clean, $text);
    }
    
    return $cosine_similarities;
}

// Fungsi untuk mengambil data surat keluar dari database
function get_surat_keluar() {
    include '../koneksi/koneksi.php'; // Sesuaikan dengan lokasi dan nama file koneksi
    
    $sql = "SELECT * FROM tb_suratkeluar"; // Query untuk mengambil data surat keluar
    $result = mysqli_query($db, $sql);
    $surat_keluar_data = [];
    
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $surat_keluar_data[] = $row; // Masukkan data ke dalam array
        }
    }
    
    mysqli_close($db); // Tutup koneksi database
    
    return $surat_keluar_data;
}

// Fungsi untuk menghitung precision dan recall
function evaluate_precision_recall($input_text, $surat_keluar_data, $threshold = 0.5) {
    $true_positives = 0;
    $false_positives = 0;
    $relevant_documents = 0;
    
    $cosine_similarities = calculate_similarity($input_text, array_column($surat_keluar_data, 'perihal_suratkeluar'));
    
    foreach ($cosine_similarities as $index => $similarity) {
        if ($similarity >= $threshold) {
            $relevant_documents++;
            // Misalnya, asumsikan ada label relevansi, misalnya jika similarity >= 0.5, dianggap relevan
            // Anda dapat menyesuaikan logika ini sesuai dengan data dan kebutuhan Anda
            if ($index < count($surat_keluar_data)) {
                $true_positives++;
            }
        }
    }
    
    // Precision
    $precision = $relevant_documents > 0 ? $true_positives / $relevant_documents : 0;
    
    // Recall
    $total_relevant_documents = count($surat_keluar_data);
    $recall = $total_relevant_documents > 0 ? $true_positives / $total_relevant_documents : 0;
    
    return [$precision, $recall];
}

// Contoh penggunaan:
$input_text = "Teks surat masuk yang ingin dicari kesamaannya.";
$surat_keluar_data = get_surat_keluar();
list($precision, $recall) = evaluate_precision_recall($input_text, $surat_keluar_data, 0.5);

// Tampilkan hasil
echo "Precision: " . number_format($precision, 2) . "<br>";
echo "Recall: " . number_format($recall, 2);
?>