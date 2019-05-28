<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;
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
    function getNguoidung(){
    	$user = Auth::user();
    	return view('pages.nguoidung');
    }
    function postNguoidung(Request $request){
    	$this->validate($request,[
    		'name'=>'required|min:3',
    	],[
    		'name.required'=>'Bạn chưa nhập tên người dùng',
    		'name.min'=>'Tên người dùng phải có ít nhất 3 kí tự'
    	]);
    	$user = Auth::user();
    	$user->name = $request->name;

    	if ($request->checkpassword == "on") {
    		$this->validate($request,[	    		
	    		'password'=>'required|min:3|max:32',
	    		'passwordAgain'=>'required|same:password'
	    	],[	    		
	    		'password.required'=>'Bạn chưa nhập password',
	    		'password.min'=>'Mật khẩu phải có ít nhất 3 kí tự',
	    		'password.max'=>'Mật khẩu chỉ được tối đa 32 kí tự',
	    		'passwordAgain.same'=>'Mật khẩu nhập lại chưa khớp'
	    	]);
    		$user->password = bcrypt($request->password);
    	}
    	
    	$user->save();
    	return redirect('nguoidung')->with('thongbao','Bạn đã sửa thành công');
    }

    function getDangky(){
    	return view('pages.dangky');
    }
    function postDangky(Request $request){
    	$this->validate($request,[
    		'name'=>'required|min:3',
    		'email'=>'required|email|unique:users,email',
    		'password'=>'required|min:3|max:32',
    		'passwordAgain'=>'required|same:password'
    	],[
    		'name.required'=>'Bạn chưa nhập tên người dùng',
    		'name.min'=>'Tên người dùng phải có ít nhất 3 kí tự',
    		'email.required'=>'Bạn chưa nhập email',
    		'email.email'=>'Bạn chưa nhập đúng định dạng email',
    		'email.unique'=>'Email đã tồn tại',
    		'password.required'=>'Bạn chưa nhập password',
    		'password.min'=>'Mật khẩu phải có ít nhất 3 kí tự',
    		'password.max'=>'Mật khẩu chỉ được tối đa 32 kí tự',
    		'passwordAgain.same'=>'Mật khẩu nhập lại chưa khớp'
    	]);
    	$user = new User;
    	$user->name = $request->name;
    	$user->email = $request->email;
    	$user->password = bcrypt($request->password);
    	$user->quyen = 0;
    	$user->save();
    	return redirect('dangnhap')->with('thongbao','Chúc mừng bạn đăng ký thành công');
    }
}
