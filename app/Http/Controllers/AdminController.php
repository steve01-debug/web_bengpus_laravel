<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Berita;
use App\Models\Pimpinan;
use App\Models\VideoTerkait;
use App\Models\StrukturOrganisasi;
use App\Models\StrukturOrganisasiImage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalBerita = Berita::count();
        $totalVideo = VideoTerkait::count();
        $totalPimpinan = Pimpinan::count();

        return view('admin.dashboard', compact('totalBerita', 'totalVideo', 'totalPimpinan'));
    }

    public function berita(Request $request)
    {
        $msg = $request->query('msg', '');
        $msgType = $request->query('type', 'success');
        $editData = null;
        $showForm = $request->has('add');

        if ($request->has('edit')) {
            $editData = Berita::find($request->query('edit'));
            if ($editData) $showForm = true;
        }

        if ($request->isMethod('post')) {
            $act = $request->input('act');
            
            if ($act === 'add' || $act === 'update') {
                $judul = $request->input('judul');
                $kategori = $request->input('kategori', 'Umum');
                $tanggal = $request->input('tanggal', date('Y-m-d'));
                $isi = $request->input('isi', '');
                $gambar = $request->input('gambar_old', '');

                if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                    $file = $request->file('gambar');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                        $fname = 'berita_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        $file->move(public_path('assets/images/berita'), $fname);
                        
                        if ($gambar && str_starts_with($gambar, 'assets/images/berita/')) {
                            @unlink(public_path($gambar));
                        }
                        $gambar = 'assets/images/berita/' . $fname;
                    } else {
                        return redirect()->route('admin.berita', ['msg' => 'File tidak valid.', 'type' => 'error']);
                    }
                }

                if ($judul) {
                    if ($act === 'add') {
                        Berita::create([
                            'judul' => $judul, 'kategori' => $kategori, 
                            'tanggal' => $tanggal, 'gambar' => $gambar, 'isi' => $isi
                        ]);
                        $msg = 'Berita berhasil ditambahkan!';
                    } else {
                        $id = $request->input('id');
                        Berita::where('id', $id)->update([
                            'judul' => $judul, 'kategori' => $kategori, 
                            'tanggal' => $tanggal, 'gambar' => $gambar, 'isi' => $isi
                        ]);
                        $msg = 'Berita berhasil diupdate!';
                    }
                    return redirect()->route('admin.berita', ['msg' => $msg, 'type' => 'success']);
                }
            } elseif ($act === 'delete') {
                $id = $request->input('del_id');
                $b = Berita::find($id);
                if ($b) {
                    if ($b->gambar && str_starts_with($b->gambar, 'assets/images/berita/')) {
                        @unlink(public_path($b->gambar));
                    }
                    $b->delete();
                }
                return redirect()->route('admin.berita', ['msg' => 'Berita berhasil dihapus.', 'type' => 'success']);
            }
        }

        $beritaList = Berita::orderBy('tanggal', 'desc')->orderBy('id', 'desc')->get();
        return view('admin.berita', compact('beritaList', 'msg', 'msgType', 'showForm', 'editData'));
    }

    public function video(Request $request)
    {
        $msg = $request->query('msg', '');
        $msgType = $request->query('type', 'success');
        $editData = null;
        $showForm = $request->has('add');

        if ($request->has('edit')) {
            $editData = VideoTerkait::find($request->query('edit'));
            if ($editData) $showForm = true;
        }

        if ($request->isMethod('post')) {
            $act = $request->input('act');
            
            if ($act === 'add' || $act === 'update') {
                $judul = $request->input('judul');
                $kategori = $request->input('kategori', 'Video Terkait');
                $tanggal = $request->input('tanggal', date('Y-m-d'));
                $url_video = $request->input('url_video', '');
                $thumbnail = $request->input('thumbnail_old', '');

                if ($request->hasFile('thumbnail') && $request->file('thumbnail')->isValid()) {
                    $file = $request->file('thumbnail');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fname = 'thumb_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        $file->move(public_path('assets/images/video'), $fname);
                        
                        if ($thumbnail && str_starts_with($thumbnail, 'assets/images/video/')) {
                            @unlink(public_path($thumbnail));
                        }
                        $thumbnail = 'assets/images/video/' . $fname;
                    }
                }

                if ($request->hasFile('file_video') && $request->file('file_video')->isValid()) {
                    $file = $request->file('file_video');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['mp4', 'webm', 'ogg'])) {
                        $fname = 'vid_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        $file->move(public_path('assets/videos'), $fname);
                        
                        if ($url_video && str_starts_with($url_video, 'assets/videos/')) {
                            @unlink(public_path($url_video));
                        }
                        $url_video = 'assets/videos/' . $fname;
                    }
                }

                if ($judul) {
                    if ($act === 'add') {
                        VideoTerkait::create([
                            'judul' => $judul, 'kategori' => $kategori, 
                            'tanggal' => $tanggal, 'url_video' => $url_video, 'thumbnail' => $thumbnail
                        ]);
                        $msg = 'Video berhasil ditambahkan!';
                    } else {
                        $id = $request->input('id');
                        VideoTerkait::where('id', $id)->update([
                            'judul' => $judul, 'kategori' => $kategori, 
                            'tanggal' => $tanggal, 'url_video' => $url_video, 'thumbnail' => $thumbnail
                        ]);
                        $msg = 'Video berhasil diupdate!';
                    }
                    return redirect()->route('admin.video', ['msg' => $msg, 'type' => 'success']);
                }
            } elseif ($act === 'delete') {
                $id = $request->input('del_id');
                $v = VideoTerkait::find($id);
                if ($v) {
                    if ($v->thumbnail && str_starts_with($v->thumbnail, 'assets/images/video/')) {
                        @unlink(public_path($v->thumbnail));
                    }
                    if ($v->url_video && str_starts_with($v->url_video, 'assets/videos/')) {
                        @unlink(public_path($v->url_video));
                    }
                    $v->delete();
                }
                return redirect()->route('admin.video', ['msg' => 'Video berhasil dihapus.', 'type' => 'success']);
            }
        }

        $videoList = VideoTerkait::orderBy('id', 'desc')->get();
        return view('admin.video', compact('videoList', 'msg', 'msgType', 'showForm', 'editData'));
    }

    public function pimpinan(Request $request)
    {
        $msg = $request->query('msg', '');
        $msgType = $request->query('type', 'success');
        $editData = null;
        $showForm = $request->has('add');

        if ($request->has('edit')) {
            $editData = Pimpinan::find($request->query('edit'));
            if ($editData) $showForm = true;
        }

        if ($request->isMethod('post')) {
            $act = $request->input('act');
            
            if ($act === 'add' || $act === 'update') {
                $nama = $request->input('nama');
                $masa_jabatan = $request->input('masa_jabatan');
                $is_current = (int)$request->input('is_current', 0);
                $urutan = (int)$request->input('urutan', 0);
                $gambar = $request->input('gambar_old', '');

                if ($is_current == 1) {
                    Pimpinan::where('id', '>', 0)->update(['is_current' => 0]);
                }

                if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                    $file = $request->file('gambar');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fname = 'pimpinan_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        $file->move(public_path('assets/images/pimpinan'), $fname);
                        
                        if ($gambar && str_starts_with($gambar, 'assets/images/pimpinan/')) {
                            @unlink(public_path($gambar));
                        }
                        $gambar = 'assets/images/pimpinan/' . $fname;
                    }
                }

                if ($nama && $masa_jabatan) {
                    if ($act === 'add') {
                        Pimpinan::create([
                            'nama' => $nama, 'masa_jabatan' => $masa_jabatan, 
                            'is_current' => $is_current, 'urutan' => $urutan, 'gambar' => $gambar
                        ]);
                        $msg = 'Data pimpinan berhasil ditambahkan!';
                    } else {
                        $id = $request->input('id');
                        Pimpinan::where('id', $id)->update([
                            'nama' => $nama, 'masa_jabatan' => $masa_jabatan, 
                            'is_current' => $is_current, 'urutan' => $urutan, 'gambar' => $gambar
                        ]);
                        $msg = 'Data pimpinan berhasil diupdate!';
                    }
                    return redirect()->route('admin.pimpinan', ['msg' => $msg, 'type' => 'success']);
                }
            } elseif ($act === 'delete') {
                $id = $request->input('del_id');
                $p = Pimpinan::find($id);
                if ($p) {
                    if ($p->gambar && str_starts_with($p->gambar, 'assets/images/pimpinan/')) {
                        @unlink(public_path($p->gambar));
                    }
                    $p->delete();
                }
                return redirect()->route('admin.pimpinan', ['msg' => 'Data pimpinan berhasil dihapus.', 'type' => 'success']);
            }
        }
        $pimpinanList = Pimpinan::orderBy('is_current', 'desc')->orderBy('urutan', 'desc')->get();
        return view('admin.pimpinan', compact('pimpinanList', 'msg', 'msgType', 'showForm', 'editData'));
    }

    public function struktur(Request $request)
    {
        $msg = $request->query('msg', '');
        $msgType = $request->query('type', 'success');
        $editData = null;
        $showForm = $request->has('add');

        if ($request->has('edit')) {
            $editData = StrukturOrganisasi::find($request->query('edit'));
            if ($editData) $showForm = true;
        }

        if ($request->isMethod('post')) {
            $act = $request->input('act');
            
            if ($act === 'update_image') {
                if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                    $file = $request->file('gambar');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fname = 'struktur_organisasi_' . time() . '.' . $ext;
                        $file->move(public_path('assets/images'), $fname);
                        $gambar = 'assets/images/' . $fname;
                        
                        $si = StrukturOrganisasiImage::first();
                        if ($si) {
                            if ($si->gambar && file_exists(public_path($si->gambar))) {
                                @unlink(public_path($si->gambar));
                            }
                            $si->update(['gambar' => $gambar]);
                        } else {
                            StrukturOrganisasiImage::create(['id' => 1, 'gambar' => $gambar]);
                        }
                        $msg = 'Gambar struktur organisasi berhasil diupdate!';
                    }
                }
                return redirect()->route('admin.struktur', ['msg' => $msg, 'type' => 'success']);
            } elseif ($act === 'add' || $act === 'update') {
                $jabatan = $request->input('jabatan');
                $nama = $request->input('nama');
                $parent_id = $request->input('parent_id') ? (int)$request->input('parent_id') : null;
                $urutan = (int)$request->input('urutan', 0);
                $gambar = $request->input('gambar_old', '');

                if ($request->hasFile('gambar') && $request->file('gambar')->isValid()) {
                    $file = $request->file('gambar');
                    $ext = strtolower($file->getClientOriginalExtension());
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $fname = 'jabatan_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                        $file->move(public_path('assets/images/pimpinan'), $fname);
                        
                        if ($gambar && str_starts_with($gambar, 'assets/images/pimpinan/')) {
                            @unlink(public_path($gambar));
                        }
                        $gambar = 'assets/images/pimpinan/' . $fname;
                    }
                }

                if ($jabatan && $nama) {
                    if ($act === 'add') {
                        StrukturOrganisasi::create([
                            'jabatan' => $jabatan, 'nama' => $nama, 
                            'parent_id' => $parent_id, 'urutan' => $urutan, 'gambar' => $gambar
                        ]);
                        $msg = 'Data struktur berhasil ditambahkan!';
                    } else {
                        $id = $request->input('id');
                        StrukturOrganisasi::where('id', $id)->update([
                            'jabatan' => $jabatan, 'nama' => $nama, 
                            'parent_id' => $parent_id, 'urutan' => $urutan, 'gambar' => $gambar
                        ]);
                        $msg = 'Data struktur berhasil diupdate!';
                    }
                    return redirect()->route('admin.struktur', ['msg' => $msg, 'type' => 'success']);
                }
            } elseif ($act === 'delete') {
                $id = $request->input('del_id');
                $s = StrukturOrganisasi::find($id);
                if ($s) {
                    if ($s->gambar && str_starts_with($s->gambar, 'assets/images/pimpinan/')) {
                        @unlink(public_path($s->gambar));
                    }
                    $s->delete();
                }
                return redirect()->route('admin.struktur', ['msg' => 'Data struktur berhasil dihapus.', 'type' => 'success']);
            }
        }

        $strukturList = StrukturOrganisasi::orderBy('urutan', 'asc')->get();
        $orgImage = StrukturOrganisasiImage::first();
        return view('admin.struktur', compact('strukturList', 'orgImage', 'msg', 'msgType', 'showForm', 'editData'));
    }
}
