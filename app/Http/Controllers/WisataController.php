<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WisataController extends Controller
{
    public function dashboard()
    {
        // Total destinasi wisata
        $totalWisata = DB::table('destinasi_wisata')->count();

        // Rata-rata rating
        $avgRating = DB::table('destinasi_wisata')->avg('rating') ?? 0;

        // Wisata dengan rating tertinggi
        $ratingTertinggi = DB::table('destinasi_wisata')
            ->orderBy('rating', 'desc')
            ->select('nama_wisata', 'rating')
            ->first();

        // Kategori wisata
        $kategoris = $this->getKategoriWisata();

        // Data wisata terbaru
        $recentWisata = DB::table('destinasi_wisata')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        // Tambahkan field 'no' dan 'kategori'
        $recentWisata = $recentWisata->map(function ($item, $index) {
            $item->no = $index + 1;
            $item->kategori = $this->determineKategori($item->nama_wisata);
            return $item;
        });

        // Ambil SEMUA data wisata untuk peta (karena kolom latitude/longitude belum ada)
        $wisataForMap = DB::table('destinasi_wisata')->get();

        // Tambahkan kategori dan koordinat default ke setiap wisata untuk peta
        $wisataForMap = $wisataForMap->map(function ($item) {
            $item->kategori = $this->determineKategori($item->nama_wisata);

            // Koordinat default berdasarkan nama wisata
            $defaultCoordinates = [
                'Candi Borobudur' => ['lat' => -7.6079, 'lng' => 110.2038],
                'Candi Prambanan' => ['lat' => -7.7520, 'lng' => 110.4914],
                'Keraton Yogyakarta' => ['lat' => -7.8053, 'lng' => 110.3643],
                'Pantai Parangtritis' => ['lat' => -8.0197, 'lng' => 110.3223],
                'Taman Sari Water Castle' => ['lat' => -7.8100, 'lng' => 110.3583],
                'Malioboro' => ['lat' => -7.7928, 'lng' => 110.3667],
                'Gunung Merapi' => ['lat' => -7.5405, 'lng' => 110.4460],
                'Pantai Indrayanti' => ['lat' => -8.1742, 'lng' => 110.6903],
                'Kebun Binatang Gembira Loka' => ['lat' => -7.8586, 'lng' => 110.3911],
                'Bukit Bintang Yogyakarta' => ['lat' => -7.9550, 'lng' => 110.5386],
                'Candi Ratu Boko' => ['lat' => -7.7704, 'lng' => 110.4890],
                'Pantai Baron' => ['lat' => -8.1625, 'lng' => 110.6281],
                'Museum Ullen Sentalu' => ['lat' => -7.6243, 'lng' => 110.4099],
                'Hutan Pinus Mangunan' => ['lat' => -7.8764, 'lng' => 110.4228],
                'Kalibiru National Park' => ['lat' => -7.8929, 'lng' => 110.1624],
            ];

            // Cari koordinat berdasarkan nama wisata, jika tidak ada pakai koordinat pusat Yogyakarta
            $coord = $defaultCoordinates[$item->nama_wisata] ?? ['lat' => -7.7956, 'lng' => 110.3695];

            $item->latitude = $coord['lat'];
            $item->longitude = $coord['lng'];

            return $item;
        });

        $colors = [
            'Candi & Sejarah' => '#e67e22',
            'Budaya & Sejarah' => '#8e44ad',
            'Pantai & Alam' => '#2980b9',
            'Alam & Petualangan' => '#27ae60',
            'Belanja & Kuliner' => '#e74c3c',
            'Hiburan & Keluarga' => '#f39c12',
        ];

        return view('dashboard', compact(
            'totalWisata',
            'avgRating',
            'ratingTertinggi',
            'kategoris',
            'recentWisata',
            'colors',
            'wisataForMap'
        ));
    }
    public function index(Request $request)
    {
        $wisatas = DB::table('destinasi_wisata')
            ->orderBy('id', 'asc')
            ->paginate(10);

        // Tambahkan kategori dan nomor urut
        $wisatas->getCollection()->transform(function ($item, $index) use ($wisatas) {
            $item->kategori = $this->determineKategori($item->nama_wisata);
            // Hitung nomor urut berdasarkan halaman
            $item->no = ($wisatas->currentPage() - 1) * $wisatas->perPage() + ($index + 1);
            return $item;
        });

        $kategoris = [
            'Candi & Sejarah',
            'Budaya & Sejarah',
            'Pantai & Alam',
            'Alam & Petualangan',
            'Belanja & Kuliner',
            'Hiburan & Keluarga'
        ];

        $colors = [
            'Candi & Sejarah' => '#e67e22',
            'Budaya & Sejarah' => '#8e44ad',
            'Pantai & Alam' => '#2980b9',
            'Alam & Petualangan' => '#27ae60',
            'Belanja & Kuliner' => '#e74c3c',
            'Hiburan & Keluarga' => '#f39c12',
        ];

        return view('wisata.index', compact('wisatas', 'kategoris', 'colors'));
    }
    /**
     * Get all kategori list for filter dropdown
     */
    private function getAllKategoriList()
    {
        $wisata = DB::table('destinasi_wisata')->get();
        $kategoriList = [];

        foreach ($wisata as $item) {
            $kategori = $this->determineKategori($item->nama_wisata);
            if (!in_array($kategori, $kategoriList)) {
                $kategoriList[] = $kategori;
            }
        }

        sort($kategoriList);
        return $kategoriList;
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil nomor urut terakhir
        $lastNo = DB::table('destinasi_wisata')->orderBy('id', 'desc')->value('no_urut') ?? 0;
        $nextNo = $lastNo + 1;

        // Daftar kategori untuk dropdown
        $kategoris = [
            'Candi & Sejarah',
            'Budaya & Sejarah',
            'Pantai & Alam',
            'Alam & Petualangan',
            'Belanja & Kuliner',
            'Hiburan & Keluarga'
        ];

        return view('wisata.create', compact('nextNo', 'kategoris'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'jam_operasional' => 'nullable|string',
            'harga_tiket' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        DB::table('destinasi_wisata')->insert($data);

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $wisata = DB::table('destinasi_wisata')->where('id', $id)->first();

        if (!$wisata) {
            return redirect()->route('wisata.index')
                ->with('error', 'Data wisata tidak ditemukan!');
        }

        // Tambahkan kategori (ini yang penting!)
        $wisata->kategori = $this->determineKategori($wisata->nama_wisata);

        return view('wisata.show', compact('wisata'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $wisata = DB::table('destinasi_wisata')->where('id', $id)->first();

        if (!$wisata) {
            return redirect()->route('wisata.index')
                ->with('error', 'Data wisata tidak ditemukan!');
        }

        // Tambahkan kategori
        $wisata->kategori = $this->determineKategori($wisata->nama_wisata);

        // Pastikan latitude dan longitude ada (set default null jika tidak ada)
        $wisata->latitude = $wisata->latitude ?? null;
        $wisata->longitude = $wisata->longitude ?? null;

        return view('wisata.edit', compact('wisata'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nama_wisata' => 'required|string|max:255',
            'jam_operasional' => 'nullable|string',
            'harga_tiket' => 'nullable|string',
            'fasilitas' => 'nullable|string',
            'lokasi' => 'nullable|string',
            'deskripsi' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
        ]);

        DB::table('destinasi_wisata')->where('id', $id)->update($data);

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('destinasi_wisata')->where('id', $id)->delete();

        return redirect()->route('wisata.index')
            ->with('success', 'Data wisata berhasil dihapus!');
    }

    /**
     * Display map view
     */
    public function map()
    {
        $wisatas = DB::table('destinasi_wisata')->get();

        // Tambahkan kategori dan pastikan data lengkap
        $wisatas = $wisatas->map(function ($item) {
            $item->kategori = $this->determineKategori($item->nama_wisata);
            $item->no_urut = $item->id; // atau bisa dari kolom no_urut jika ada

            // Jika latitude/longitude null, beri default (tengah Yogyakarta)
            if (empty($item->latitude) || empty($item->longitude)) {
                // Assign coordinate based on location name (fallback)
                $coordinate = $this->getCoordinateFromLocation($item->lokasi);
                $item->latitude = $coordinate['lat'];
                $item->longitude = $coordinate['lng'];
            }

            return $item;
        });

        return view('wisata.map', compact('wisatas'));
    }

    /**
     * Get coordinate from location string (fallback)
     */
    private function getCoordinateFromLocation($lokasi)
    {
        // Default koordinat Yogyakarta
        $default = ['lat' => -7.7956, 'lng' => 110.3695];

        if (empty($lokasi)) {
            return $default;
        }

        // Mapping lokasi ke koordinat (rough estimates)
        $coordinates = [
            'Borobudur' => ['lat' => -7.6079, 'lng' => 110.2038],
            'Prambanan' => ['lat' => -7.7520, 'lng' => 110.4914],
            'Keraton' => ['lat' => -7.8053, 'lng' => 110.3643],
            'Parangtritis' => ['lat' => -8.0197, 'lng' => 110.3223],
            'Taman Sari' => ['lat' => -7.8100, 'lng' => 110.3583],
            'Malioboro' => ['lat' => -7.7928, 'lng' => 110.3667],
            'Merapi' => ['lat' => -7.5405, 'lng' => 110.4460],
            'Indrayanti' => ['lat' => -8.1742, 'lng' => 110.6903],
            'Gembira Loka' => ['lat' => -7.8586, 'lng' => 110.3911],
            'Bukit Bintang' => ['lat' => -7.9550, 'lng' => 110.5386],
            'Ratu Boko' => ['lat' => -7.7704, 'lng' => 110.4890],
            'Baron' => ['lat' => -8.1625, 'lng' => 110.6281],
            'Ullen Sentalu' => ['lat' => -7.6243, 'lng' => 110.4099],
            'Hutan Pinus' => ['lat' => -7.8764, 'lng' => 110.4228],
            'Kalibiru' => ['lat' => -7.8929, 'lng' => 110.1624],
        ];

        foreach ($coordinates as $key => $coord) {
            if (str_contains($lokasi, $key)) {
                return $coord;
            }
        }

        return $default;
    }

    /**
     * Menentukan kategori berdasarkan nama wisata
     */
    private function determineKategori($namaWisata)
    {
        $mapping = [
            'Candi' => 'Candi & Sejarah',
            'Keraton' => 'Budaya & Sejarah',
            'Taman Sari' => 'Budaya & Sejarah',
            'Pantai' => 'Pantai & Alam',
            'Parangtritis' => 'Pantai & Alam',
            'Indrayanti' => 'Pantai & Alam',
            'Baron' => 'Pantai & Alam',
            'Merapi' => 'Alam & Petualangan',
            'Hutan Pinus' => 'Alam & Petualangan',
            'Kalibiru' => 'Alam & Petualangan',
            'Bukit Bintang' => 'Alam & Petualangan',
            'Malioboro' => 'Belanja & Kuliner',
            'Gembira Loka' => 'Hiburan & Keluarga',
            'Museum' => 'Budaya & Sejarah',
            'Ratu Boko' => 'Candi & Sejarah',
        ];

        foreach ($mapping as $keyword => $kategori) {
            if (str_contains($namaWisata, $keyword)) {
                return $kategori;
            }
        }

        return 'Wisata Lainnya';
    }

    /**
     * Menghitung jumlah wisata per kategori
     */
    private function getKategoriWisata()
    {
        $wisata = DB::table('destinasi_wisata')->get();
        $kategoriCount = [];

        foreach ($wisata as $item) {
            $kategori = $this->determineKategori($item->nama_wisata);
            if (!isset($kategoriCount[$kategori])) {
                $kategoriCount[$kategori] = 0;
            }
            $kategoriCount[$kategori]++;
        }

        $result = [];
        foreach ($kategoriCount as $kategori => $total) {
            $result[] = (object) [
                'kategori' => $kategori,
                'total' => $total
            ];
        }

        return collect($result);
    }
}
