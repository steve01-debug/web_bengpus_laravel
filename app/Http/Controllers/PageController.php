<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Pimpinan;
use App\Models\VideoTerkait;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturOrganisasiImage;

class PageController extends Controller
{
    public function index()
    {
        $beritaTerbaru = Berita::orderBy('id', 'desc')->limit(3)->get();
        $pimpinan = Pimpinan::orderBy('is_current', 'desc')->orderBy('urutan', 'desc')->get();
        $struktur = StrukturOrganisasi::orderBy('urutan', 'asc')->get();
        $strukturImage = StrukturOrganisasiImage::first();
        $videoAll = VideoTerkait::orderBy('id', 'desc')->limit(3)->get();
        
        return view('pages.index', compact('beritaTerbaru', 'pimpinan', 'struktur', 'strukturImage', 'videoAll'));
    }

    public function berita()
    {
        $beritaAll = Berita::orderBy('id', 'desc')->get();
        return view('pages.berita', compact('beritaAll'));
    }

    public function beritaDetail(Request $request)
    {
        $id = $request->query('id');
        $berita = Berita::find($id);
        
        if (!$berita) {
            return redirect()->route('berita');
        }

        $beritaLain = Berita::where('id', '!=', $id)->orderBy('id', 'desc')->limit(4)->get();
        
        return view('pages.berita-detail', compact('berita', 'beritaLain'));
    }

    public function piket()
    {
        return view('pages.piket');
    }

    public function kodePiket()
    {
        return view('pages.kode-piket');
    }

    public function video()
    {
        $videoAll = VideoTerkait::orderBy('id', 'desc')->get();
        return view('pages.video', compact('videoAll'));
    }

    public function entering()
    {
        return view('pages.entering');
    }

    public function submitFeedback(Request $request)
    {
        $nama = $request->input('nama', '');
        $email = $request->input('email', '');
        $pesan = $request->input('pesan', '');

        if (empty($nama) || empty($email) || empty($pesan)) {
            return response()->json(['success' => false, 'message' => 'Semua field wajib diisi']);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'message' => 'Format email tidak valid']);
        }

        \App\Models\Feedback::create([
            'nama' => $nama,
            'email' => $email,
            'pesan' => $pesan
        ]);

        return response()->json(['success' => true, 'message' => 'Feedback berhasil dikirim']);
    }
}
