<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TheLoai extends Model
{
	protected $table = 'theloai';

	//lay ra tat ca loai tin
	public function loaitin(){
		return $this->hasMany('App\LoaiTin', 'idTheloai', 'id');
	}

	public function tintuc(){
		return $this->hasManyThrough('App\TinTuc', 'App\LoaiTin','idTheLoai', 'idLoaiTin', 'id');
	}
}
