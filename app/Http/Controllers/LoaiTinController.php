<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
Use App\TheLoai;
Use App\LoaiTin;

class LoaiTinController extends Controller
{
    public function getDanhSach(){
    	$loaitin  = LoaiTin::all();
    	return view('admin.loaitin.danhsach',['loaitin'=>$loaitin]);
    }
    public function getThem(){
    	$theloai = TheLoai::all();
    	return view('admin.loaitin.them',['theloai'=>$theloai]);
    }
    public function postThem(Request $request){
    	$this->validate($request,
			[
				//các yêu cầu
				'Ten' => 'required||unique:loaitin,Ten|min:1|max:100',
				'TheLoai' => 'required'
	    	],
	    	[
	    		//hiển thị lỗi ra màn hình
	    		'Ten.required' => 'Bạn chưa nhập tên loại tin',
	    		'Ten.unique' => 'Tên loại tin đã tồn tại',
	    		'Ten.min'=> 'Tên loại tin phải có độ dài từ 1 đến 100 kí tự',
	    		'Ten.max'=> 'Tên loại tin phải có độ dài từ 1 đến 100 kí tự'
	    	]);
    	$loaitin = new LoaiTin;
    	$loaitin->Ten = $request->Ten;
    	$loaitin->TenKhongDau = changeTitle($request->Ten);
    	$loaitin->idTheLoai = $request->TheLoai;
    	$loaitin->save();

    	return redirect('admin/loaitin/them')->with('thongbao','Thêm thành công');
    }
    public function getSua($id){
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::find($id);
    	return view('admin.loaitin.sua',['loaitin'=>$loaitin, 'theloai'=>$theloai]);
    }
    public function postSua(Request $request, $id){
    	$loaitin = LoaiTin::find($id);
    	$this->validate($request,
    		[
    			'Ten' => 'required|unique:loaitin,Ten|min:3|max:100'
    		],
    		[
    			'Ten.required' => 'Bạn chưa nhập tên loại tin',
    			'Ten.unique' => 'Tên loại tin đã tồn tại',
	    		'Ten.min'=> 'Tên loại tin phải có độ dài từ 1 đến 100 kí tự',
	    		'Ten.max'=> 'Tên loại tin phải có độ dài từ 1 đến 100 kí tự'
    		]);
    	$loaitin->Ten = $request->Ten;
    	$loaitin->TenKhongDau = changeTitle($request->Ten);
    	$loaitin->idTheLoai = $request->TheLoai;
    	$loaitin->save();

    	return redirect('admin/loaitin/sua/'.$id)->with('thongbao', 'Sửa thành công');
    } 
    public function getXoa($id){
    	$loaitin = LoaiTin::find($id);
    	$loaitin->delete();

    	return redirect('admin/loaitin/danhsach')->with('thongbao','Bạn đã xóa thành công');
    }
}
