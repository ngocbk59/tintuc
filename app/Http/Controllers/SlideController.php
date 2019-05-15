<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slide;

class SlideController extends Controller
{
    public function getDanhSach(){
    	$slide = Slide::all();
    	return view('admin.slide.danhsach',['slide'=>$slide]);
    }
    public function getThem(){
    	return view('admin/slide/them');
    }
    public function postThem(Request $request){
    	$this->validate($request,[
    		'Ten'=>'required',
    		'NoiDung'=>'required',
    	],[
    		'Ten.required'=>'Bạn chưa nhập tên',
    		'NoiDung.required'=>'Bạn chưa nhập nội dung',
    	]);
    	$slide = new Slide;
    	$slide->Ten = $request->Ten;
    	$slide->NoiDung = $request->NoiDung;
    	if ($request->has('link')) {
    		$slide->link = $request->link;
    	}
    	
    	if ($request->hasFile('Hinh')) {
    		$file = $request->file('Hinh');
    		$duoi = $file->getClientOriginalExtension();
    		if ($duoi != 'jpg' && $duoi != 'png' && $duoi != 'jpeg') {
    			return redirect('admin/slide/them')->with('loi','Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = $file->getClientOriginalName();
    		$hinh = str_random(4) ."_". $name;
    		while (file_exists("upload/slide/".$hinh)) {
    			$hinh = str_random(4) ."_". $name;
    		}
    		$file->move("upload/slide",$hinh);
    		$slide->Hinh = $hinh;
    	} else{
    		$slide->Hinh='';
    	}
    	$slide->save();
    	return redirect('admin/slide/them')->with('thongbao','Thêm thành công');
    }
    public function getSua($id){
    	
    }
    public function postSua(Request $request, $id){
    	
    } 
    public function getXoa($id){
    	
    }
}
