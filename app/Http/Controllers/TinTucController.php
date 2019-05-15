<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\LoaiTin;
use App\TinTuc;

class TinTucController extends Controller
{
    public function getDanhSach(){
    	$tintuc = TinTuc::orderBy('id', 'DESC')->get();
    	return view('admin.tintuc.danhsach',['tintuc'=>$tintuc]);
    }
    public function getThem(){
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	return view('admin.tintuc.them',['theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'LoaiTin'=>'required',
    			'TieuDe' => 'required|min:3|unique:tintuc,TieuDe',
    			'TomTat'=>'required',
    			'NoiDung'=>'required'
	    	],
	    	[
	    		'LoaiTin.required'=>'Bạn chưa chọn loại tin',
	    		'TieuDe.required'=>'Bạn chưa nhập tiêu đề',
	    		'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 kí tự',
	    		'TieuDe.unique'=>'Tiêu đề đã tồn tại',
	    		'TomTat.required'=>'Bạn chưa nhập tóm tắt',
	    		'NoiDung.required'=>'Bạn chưa nhập nội dung'
	    	]);
    	$tintuc = new TinTuc;
    	$tintuc->TieuDe = $request->TieuDe;
    	$tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
    	$tintuc->idLoaiTin = $request->LoaiTin;
    	$tintuc->TomTat = $request->TomTat;
    	$tintuc->NoiDung = $request->NoiDung;
    	$tintuc->SoLuotXem = 0;

    	if ($request->hasFile('Hinh')) {
    		$file = $request->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg') {
    			return redirect('admin/tintuc/them')->with('loi','Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = $file->getClientOriginalName();
    		$hinh = str_random(4) ."_". $name;
    		while (file_exists("upload/tintuc/".$hinh)) {
    			$hinh = str_random(4) ."_". $name;
    		}
    		$file->move("upload/tintuc",$hinh);
    		$tintuc->Hinh = $hinh;
    	} else{
    		$tintuc->Hinh='';
    	}
    	$tintuc->save();

    	return redirect('admin/tintuc/them')->with('thongbao','Thêm tin thành công');
    	
    }
    public function getSua($id){
    	$tintuc = TinTuc::find($id);
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	return = view('admin.tintuc.sua',['tintuc'=>$tintuc, 'theloai'=>$theloai,'loaitin'=>$loaitin]);
    }
    public function postSua(Request $request, $id){
    	
    } 
    public function getXoa($id){
    	
    }
}
