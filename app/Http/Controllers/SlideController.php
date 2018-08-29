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
    	return view('admin.slide.them');
    }

    public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'Ten'=>'required',
    			'NoiDung'=>'required',
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập tên slide',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung slide',
    		]);

    	$slide = new Slide;
    	$slide->Ten = $request->Ten;
    	if($request->has('link')) $slide->link = $request->link;
    	if($request->hasFile('Hinh')){
    		$file = $request->file('Hinh');
    		$duoiAnh = $file->getClientOriginalExtension();
    		$arrImg = ['jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG'];
    		$check = false;
    		for ($i=0; $i < count($arrImg); $i++) {
    			if($duoiAnh == $arrImg[$i]){
    				$check = true; break;
    			}
    		}
    		if(!$check){
    			return redirect('admin/slide/them')->with('loi', 'Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = time().$file->getClientOriginalName();
    		$file->move('upload/slide', $name);
    		$slide->Hinh = $name;

    	}
    	else{
    		$slide->Hinh = "";
    	}
    	$slide->NoiDung = $request->NoiDung;
    	$slide->save();
    	return redirect('admin/slide/them')->with('thongbao','Thêm thành công');
    }

    public function getSua($id){

    	$slide = Slide::find($id);
    	return view('admin.slide.sua',['slide'=>$slide]);
    }

    public function postSua(Request $request, $id){
    	$this->validate($request,
    		[
    			'Ten'=>'required',
    			'NoiDung'=>'required',
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập tên slide',
    			'NoiDung.required'=>'Bạn chưa nhập nội dung slide',
    		]);

    	$slide = Slide::find($id);
    	$slide->Ten = $request->Ten;
    	if($request->has('link')) $slide->link = $request->link;
    	if($request->hasFile('Hinh')){
    		$file = $request->file('Hinh');
    		$duoiAnh = $file->getClientOriginalExtension();
    		$arrImg = ['jpg', 'JPG', 'png', 'PNG', 'jpeg', 'JPEG'];
    		$check = false;
    		for ($i=0; $i < count($arrImg); $i++) {
    			if($duoiAnh == $arrImg[$i]){
    				$check = true; break;
    			}
    		}
    		if(!$check){
    			return redirect('admin/slide/them')->with('loi', 'Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = time().$file->getClientOriginalName();
    		unlink('upload/slide/'.$slide->Hinh);
    		$file->move('upload/slide', $name);
    		$slide->Hinh = $name;

    	}

    	$slide->NoiDung = $request->NoiDung;
    	$slide->save();
    	return redirect('admin/slide/sua/'.$id)->with('thongbao','Bạn đã sửa thành công');
    }

    public function getXoa($id){

    	$slide = Slide::find($id);
    	unlink('upload/slide/'.$slide->Hinh);
    	$slide->delete();
    	return redirect('admin/slide/danhsach')->with('thongbao', 'Xóa thành công');
    }
}
