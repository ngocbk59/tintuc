<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
	function __construct(){
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);

		if (Auth::check()) {
			view()->share('nguoidung',Auth::user());
		}
	}

    function trangchu(){
    	
    	return view('pages.trangchu');
    }
    function lienhe(){
    	return view('pages.lienhe');
    }
    function loaitin($id){
    	$loaitin = LoaiTin::find($id);
    	$tintuc = TinTuc::where('idLoaiTin', $id)->paginate(5);
    	return view('pages.loaitin',['loaitin'=>$loaitin,'tintuc'=>$tintuc]);
    }
    function tintuc($id){
    	$tintuc = TinTuc::find($id);
    	$tinnoibat = TinTuc::where('NoiBat',1)->take(4)->get();
    	$tinlienquan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
    	return view('pages.tintuc',['tintuc'=>$tintuc,'tinnoibat'=>$tinnoibat,'tinlienquan'=>$tinlienquan]);
    }
    function getDangnhap(){
    	return view('pages.dangnhap');
    }
    function postDangnhap(Request $request){
    	$this->validate($request,[
    		
    		'email'=>'required|email',
    		'password'=>'required|min:3|max:32',
    		
    	],[
    		
    		'email.required'=>'Bạn chưa nhập email',
    		'email.email'=>'Bạn chưa nhập đúng định dạng email',
    		'password.required'=>'Bạn chưa nhập password',
    		'password.min'=>'Mật khẩu phải có ít nhất 3 kí tự',
    		'password.max'=>'Mật khẩu chỉ được tối đa 32 kí tự',
    		
    	]);
    	if (Auth::attempt(['email'=>$request->email, 'password'=>$request->password])) {
    		return redirect('trangchu');
    	}
    	else{
    		return redirect('dangnhap')->with('thongbao', 'Đăng nhập không thành công');
    	}

    }
    function getDangxuat(){
    	Auth::logout();
    	return redirect('trangchu');
    }
}
