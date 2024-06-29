<?php
session_start();
include "login/ceksession.php";
require '../vendor/autoload.php';
include '../koneksi/koneksi.php';

use Sastrawi\Stemmer\StemmerFactory;
use Sastrawi\StopWordRemover\StopWordRemoverFactory;

$stemmerFactory = new StemmerFactory();
$stemmer = $stemmerFactory->createStemmer();

$stopWordRemoverFactory = new StopWordRemoverFactory();
$stopWordRemover = $stopWordRemoverFactory->createStopWordRemover();

function preprocessText($text, $stemmer, $stopWordRemover)
{
    $text = strtolower($text);
    $text = preg_replace('/[^\w\s]/', '', $text);
    $text = $stopWordRemover->remove($text);
    $words = explode(' ', $text);
    foreach ($words as &$word) {
        $word = $stemmer->stem($word);
    }
    return array_filter($words);
}

function computeTF($document)
{
    $tf = array();
    $wordCount = array_count_values($document);
    $totalWords = count($document);

    foreach ($wordCount as $word => $count) {
        $tf[$word] = $count / $totalWords;
    }
    return $tf;
}

function computeIDF($documents)
{
    $idf = array();
    $totalDocuments = count($documents);
    $wordDocuments = array();

    foreach ($documents as $document) {
        $uniqueWords = array_unique($document);
        foreach ($uniqueWords as $word) {
            if (!isset($wordDocuments[$word])) {
                $wordDocuments[$word] = 0;
            }
            $wordDocuments[$word]++;
        }
    }

    foreach ($wordDocuments as $word => $docCount) {
        $idf[$word] = log($totalDocuments / $docCount);
    }
    return $idf;
}

function computeTFIDF($documents, $idf)
{
    $tfidf = array();

    foreach ($documents as $index => $document) {
        $tf = computeTF($document);
        foreach ($tf as $word => $value) {
            $tfidf[$index][$word] = $value * (isset($idf[$word]) ? $idf[$word] : 0);
        }
    }
    return $tfidf;
}

function cosineSimilarity($vectorA, $vectorB)
{
    $dotProduct = 0;
    $magnitudeA = 0;
    $magnitudeB = 0;

    foreach ($vectorA as $word => $value) {
        $dotProduct += $value * (isset($vectorB[$word]) ? $vectorB[$word] : 0);
        $magnitudeA += pow($value, 2);
    }

    foreach ($vectorB as $value) {
        $magnitudeB += pow($value, 2);
    }

    $magnitudeA = sqrt($magnitudeA);
    $magnitudeB = sqrt($magnitudeB);

    if ($magnitudeA == 0 || $magnitudeB == 0) {
        return 0;
    }

    return $dotProduct / ($magnitudeA * $magnitudeB);
}

if (isset($_POST['katakunci'])) {
    $katakunci = $_POST['katakunci'];
    $jenissurat = $_POST['jenissurat'];
    $tglawal = $_POST['tglawal'];
    $tglakhir = $_POST['tglakhir'];
    $user = $_SESSION['nama'];

    $sql = "";
    if ($jenissurat == "1") {
        $sql = "SELECT * FROM tb_suratmasuk WHERE tanggalmasuk_suratmasuk BETWEEN '$tglawal' AND '$tglakhir' AND (disposisi1='$user' OR disposisi2='$user' OR disposisi3='$user')";
        $perihalsurat = "perihal_suratmasuk";
        $file_surat = "file_suratmasuk";
    } else if ($jenissurat == "2") {
        $sql = "SELECT * FROM tb_suratkeluar WHERE tanggalkeluar_suratkeluar BETWEEN '$tglawal' AND '$tglakhir' AND (disposisi1='$user' OR disposisi2='$user' OR disposisi3='$user')";
        $perihalsurat = "perihal_suratkeluar";
        $file_surat = "file_suratkeluar";
    }
    $query = mysqli_query($db, $sql);

    $documents = [];
    $perihal = [];

    while ($row = mysqli_fetch_assoc($query)) {
        $perihal[] = $row[$perihalsurat];
        $files[] = $row[$file_surat];
        $documents[] = preprocessText($row[$perihalsurat], $stemmer, $stopWordRemover);
    }

    $idf = computeIDF($documents);
    $tfidf = computeTFIDF($documents, $idf);

    $keywordVector = preprocessText($katakunci, $stemmer, $stopWordRemover);
    $tfidfKeyword = computeTF($keywordVector);

    foreach ($keywordVector as $word) {
        $tfidfKeyword[$word] = (isset($tfidfKeyword[$word]) ? $tfidfKeyword[$word] : 0) * (isset($idf[$word]) ? $idf[$word] : 0);
    }

    $similarities = array();
    foreach ($tfidf as $index => $docVector) {
        $similarities[$index] = cosineSimilarity($tfidfKeyword, $docVector);
    }

    arsort($similarities);
    $results = array();

    foreach ($similarities as $index => $similarity) {
        $results[] = array('perihal' => $perihal[$index], 'file' => $files[$index], 'similarity' => $similarity, 'jenis_surat' => $jenissurat);
    }

    echo json_encode($results);
}
