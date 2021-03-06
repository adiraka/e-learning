<?php

namespace App\Http\Controllers\Staff;

use App\Models\AngkatanDiklat;

use Session;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AngkatanDiklatController extends Controller
{
    public function getAngkatanDiklatPage()
    {
    	return view('staff.angkatandiklat.main');
    }

    public function getDataAngkatanDiklat()
    {
    	$angkatanDiklat = AngkatanDiklat::select(['id', 'nama_diklat', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'status']);
    	return Datatables::of($angkatanDiklat)
    		->addColumn('action', function ($angkatan) {
                $disable = 'disabled';
                $false = '';
                return '
                    <a href="angkatandiklat/ubah/'.$angkatan->id.'" class="btn btn-sm btn-green btn-icon btn-icon-mini btn-round"><i class="fa fa-edit"></i></a>
                    <a href="angkatandiklat/selesai/'.$angkatan->id.'" class="btn btn-sm btn-success btn-icon btn-icon-mini btn-round '.(($angkatan->status == 0)?$disable:$false).'"><i class="fa fa-check"></i></a>
                    <a href="angkatandiklat/hapus/'.$angkatan->id.'" class="btn btn-sm btn-danger btn-icon btn-icon-mini btn-round"><i class="fa fa-trash"></i></a>
                ';
            })
    		->editColumn('tanggal_mulai', function ($angkatan){
    			return Carbon::parse($angkatan->tanggal_mulai)->format('d-M-Y');
    		})
    		->editColumn('tanggal_selesai', function ($angkatan){
    			return Carbon::parse($angkatan->tanggal_selesai)->format('d-M-Y');
    		})->editColumn('status', function ($angkatan){
                if ($angkatan->status == '1') {
                    return 'Ongoing';
                } elseif ($angkatan->status == '0') {
                    return 'Done';
                }
            })
    		->make(true);
    }

    public function getAddAngkatanDiklatPage()
    {
    	return view('staff.angkatandiklat.add');
    }

    public function postAddAngkatanDiklat(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ],[
            'nama.required' => 'Nama Diklat tidak boleh kosong.',
            'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong.',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berbentuk format tanggal.',
            'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
            'tanggal_selesai.date' => 'Tanggal Selesai harus berbentuk format tanggal.' 
        ]);

        $angkatanDiklat = new AngkatanDiklat([
            'nama_diklat' => $request->nama,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'keterangan' => $request->keterangan,
            'status' => '1'
        ]);

        $angkatanDiklat->save();

        Session::flash('success', 'Angkatan Diklat berhasil ditambahkan.');
        return redirect()->route('getAngkatanDiklatPage');
    }

    public function getEditAngkatanDiklatPage($id)
    {
        $angkatan = AngkatanDiklat::find($id);
        return view('staff.angkatandiklat.edit', [
            'angkatan' => $angkatan
        ]);
    }

    public function putEditAngkatanDiklatPage($id, Request $request)
    {
        $this->validate($request, [
            'nama' => 'required',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
        ],[
            'nama.required' => 'Nama Diklat tidak boleh kosong.',
            'tanggal_mulai.required' => 'Tanggal Mulai tidak boleh kosong.',
            'tanggal_mulai.date' => 'Tanggal Mulai harus berbentuk format tanggal.',
            'tanggal_selesai.required' => 'Tanggal Selesai tidak boleh kosong',
            'tanggal_selesai.date' => 'Tanggal Selesai harus berbentuk format tanggal.' 
        ]);

        $angkatanDiklat = AngkatanDiklat::find($request->id);
        $angkatanDiklat->nama_diklat = $request->nama;
        $angkatanDiklat->tanggal_mulai = $request->tanggal_mulai;
        $angkatanDiklat->tanggal_selesai = $request->tanggal_selesai;
        $angkatanDiklat->keterangan = $request->keterangan;
        $angkatanDiklat->save();

        Session::flash('success', 'Angkatan Diklat berhasil diubah.');
        return redirect()->route('getAngkatanDiklatPage');
    }

    public function getHapusAngkatanDiklatPage($id)
    {
        $angkatan = AngkatanDiklat::find($id);
        return view('staff.angkatandiklat.hapus', [
            'angkatan' => $angkatan
        ]);
    }

    public function deleteHapusAngkatanDiklat($id, Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $angkatanDiklat = AngkatanDiklat::find($request->id);
        $angkatanDiklat->delete();

        Session::flash('success', 'Angkatan Diklat berhasil dihapus.');
        return redirect()->route('getAngkatanDiklatPage');
    }

    public function getSelesaiPage($id)
    {
        $angkatan = AngkatanDiklat::find($id);
        return view('staff.angkatandiklat.done', [
            'angkatan' => $angkatan
        ]);
    }

    public function putSelesaiAngkatanDiklat($id, Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $angkatan = AngkatanDiklat::find($request->id);
        $angkatan->status = '0';
        $angkatan->save();

        Session::flash('success', 'Angkatan Diklat berhasil ditutup.');
        return redirect()->route('getAngkatanDiklatPage');
    }
}
