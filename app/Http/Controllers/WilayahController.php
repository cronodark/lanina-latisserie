<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

/**
 * Proxy untuk API wilayah Indonesia (emsifa) + API kode pos (kodepos.vercel.app).
 *
 * Dipakai oleh form alamat customer agar fetch tidak tergantung
 * CORS/koneksi browser. Hasil di-cache 1 hari.
 */
class WilayahController extends Controller
{
    private const BASE_URL = 'https://emsifa.github.io/api-wilayah-indonesia/api';
    private const KODEPOS_URL = 'https://kodepos.vercel.app/search/';
    private const CACHE_TTL = 86400; // 24 jam

    public function provinces(): JsonResponse
    {
        $data = Cache::remember('wilayah.provinces', self::CACHE_TTL, function () {
            return $this->fetchJson(self::BASE_URL . '/provinces.json');
        });

        return $this->respond($data);
    }

    public function regencies(string $provinceId): JsonResponse
    {
        if (!preg_match('/^\d{1,3}$/', $provinceId)) {
            return response()->json(['error' => 'Invalid province id'], 400);
        }

        $data = Cache::remember("wilayah.regencies.{$provinceId}", self::CACHE_TTL, function () use ($provinceId) {
            return $this->fetchJson(self::BASE_URL . "/regencies/{$provinceId}.json");
        });

        return $this->respond($data);
    }

    public function districts(string $regencyId): JsonResponse
    {
        if (!preg_match('/^\d{1,5}$/', $regencyId)) {
            return response()->json(['error' => 'Invalid regency id'], 400);
        }

        $data = Cache::remember("wilayah.districts.{$regencyId}", self::CACHE_TTL, function () use ($regencyId) {
            return $this->fetchJson(self::BASE_URL . "/districts/{$regencyId}.json");
        });

        return $this->respond($data);
    }

    /**
     * Cari kode pos berdasarkan nama kecamatan + kabupaten.
     *
     * Query:
     *   ?district=Cibiru&regency=Bandung
     *
     * Mengembalikan unique list kode pos yang cocok, beserta nama kelurahan
     * agar user bisa memilih yang tepat.
     */
    public function kodepos(Request $request): JsonResponse
    {
        $district = trim((string) $request->query('district', ''));
        $regency = trim((string) $request->query('regency', ''));

        if ($district === '' || $regency === '') {
            return response()->json(['error' => 'district dan regency wajib diisi'], 400);
        }

        $cacheKey = 'wilayah.kodepos.' . md5(strtolower($district . '|' . $regency));

        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () use ($district, $regency) {
            // Query ke API kodepos pakai nama kecamatan saja + nama kabupaten
            // yang sudah dinormalisasi (tanpa prefix "KOTA"/"KABUPATEN") karena
            // API kodepos tidak mengenali prefix tersebut dalam pencarian.
            $regencyNormalized = $this->normalize($regency);
            $query = $district . ' ' . $regencyNormalized;
            $raw = $this->fetchJson(self::KODEPOS_URL . '?q=' . urlencode($query));

            // Fallback: bila pencarian gabungan kosong, coba hanya dengan district.
            if (!is_array($raw) || empty($raw['data'])) {
                $raw = $this->fetchJson(self::KODEPOS_URL . '?q=' . urlencode($district));
            }

            if (!is_array($raw) || !isset($raw['data']) || !is_array($raw['data'])) {
                return null;
            }

            // Filter hanya yang nama kecamatan & kabupaten benar-benar cocok,
            // karena API bisa mengembalikan hasil yang mirip tapi beda wilayah.
            $districtLower = $this->normalize($district);
            $regencyLower = $regencyNormalized;

            $filtered = [];
            foreach ($raw['data'] as $row) {
                if (!isset($row['district'], $row['regency'], $row['code'])) {
                    continue;
                }
                if (
                    $this->normalize($row['district']) === $districtLower &&
                    $this->normalize($row['regency']) === $regencyLower
                ) {
                    $filtered[] = [
                        'code' => str_pad((string) $row['code'], 5, '0', STR_PAD_LEFT),
                        'village' => $row['village'] ?? '',
                    ];
                }
            }

            // Unique berdasarkan kombinasi code + village, sort ascending.
            $unique = [];
            foreach ($filtered as $item) {
                $key = $item['code'] . '|' . $item['village'];
                $unique[$key] = $item;
            }
            $result = array_values($unique);
            usort($result, fn($a, $b) => strcmp($a['village'], $b['village']));

            return $result;
        });

        return $this->respond($data);
    }

    /**
     * Normalize string untuk perbandingan: lowercase, buang prefix
     * "KABUPATEN ", "KOTA ", "KAB. ", dan trim.
     */
    private function normalize(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/^(kabupaten|kota administrasi|kota|kab\.?)\s+/', '', $value);
        return trim($value);
    }

    /**
     * Fetch JSON dari URL dengan timeout pendek agar tidak hang.
     * Mengembalikan null bila gagal; caller menangani null -> error response.
     */
    private function fetchJson(string $url): ?array
    {
        try {
            $response = Http::timeout(10)->withOptions(['verify' => false])->get($url);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Throwable $e) {
            // swallow, return null
        }
        return null;
    }

    private function respond(?array $data): JsonResponse
    {
        if ($data === null) {
            return response()->json(['error' => 'Gagal mengambil data wilayah'], 502);
        }
        return response()->json($data);
    }
}

