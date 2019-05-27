<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;

class PagesController extends Controller
{
	function __construct(){
		$theloai = TheLoai::all();
		view()->share('theloai',$theloai);
	}
    function trangchu(){
    	
    	return view('pages.trangchu',['theloai'=>$theloai]);
    }
    function lienhe(){
    	return view('pages.lienhe');
    }
}
