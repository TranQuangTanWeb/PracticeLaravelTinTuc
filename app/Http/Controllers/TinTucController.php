<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\TinTuc;
use App\TheLoai;
use App\LoaiTin;
use App\Comment;

class TinTucController extends Controller
{
    public function getDanhSach(){
    	// $tintuc = TinTuc::orderBy('id','DESC')->get();
    	$tintuc = TinTuc::all();
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	return view('admin.tintuc.danhsach', ['tintuc'=>$tintuc, 'theloai'=>$theloai, 'loaitin'=>$loaitin]);
    }

    public function getThem(){
    	$theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	return view('admin.tintuc.them', ['theloai'=>$theloai, 'loaitin'=>$loaitin]);
    }

     public function postThem(Request $request){
    	$this->validate($request,
    		[
    			'LoaiTin'=>'required',
    			'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
    			'TomTat'=>'required',
    			'NoiDung'=>'required'
    		],
    		[
    			'LoaiTin.required'=>'Bạn chưa chọn loại tin',
    			'TieuDe.required'=>'Bạn chưa nhập tiêu đề',
    			'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 ký tự',
    			'TieuDe.unique'=>'Tiêu đề đã tồn tại',
    			'TomTat.required'=>'Bạn chưa nhập tóm tắt',
    			'NoiDung.required'=>'Bạn chưa nhập Nội dung'

    		]);

    	$tintuc = new TinTuc;
    	$tintuc->TieuDe = $request->TieuDe;
    	$tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
    	$tintuc->idLoaiTin = $request->LoaiTin;
    	$tintuc->TomTat = $request->TomTat;
    	$tintuc->NoiDung = $request->NoiDung;
    	$tintuc->SoLuotXem = 0;

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
    			return redirect('admin/tintuc/them')->with('loi', 'Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = time().$file->getClientOriginalName();
    		$file->move('upload/tintuc', $name);
    		$tintuc->Hinh = $name;

    	}
    	else{
    		$tintuc->Hinh = "";
    	}

    	$tintuc->save();

    	return redirect('admin/tintuc/them')->with('thongbao', 'Bạn đã thêm thành công');
    }

    public function getSua($id){
        $tintuc = TinTuc::find($id);
        $theloai = TheLoai::all();
    	$loaitin = LoaiTin::all();
    	$tentl = DB::table('theloai')
    	->join('loaitin', 'theloai.id', '=', 'loaitin.idTheLoai')
    	->join('tintuc', 'loaitin.id','=', 'tintuc.idLoaiTin')
    	->select('theloai.Ten')
    	->where('tintuc.id', '=', $id)
    	->get()->toArray();
    	// echo "<pre>";
    	// var_dump($tentl);
    	$array = json_decode(json_encode($tentl), True);
    	// var_dump($array);
    	// echo $array[0]['Ten'];
    	$tentheloai = $array[0]["Ten"];
    	// $tenlt = DB::table('loaitin')
    	// ->join('tintuc', 'tintuc.idLoaiTin', '=', 'loaitin.id')
    	// ->select('loaitin.Ten')
    	// ->where('tintuc.id','=', $id)
    	// ->get()->toArray();
    	$tenlt = DB::table('tintuc')
    	->join('loaitin', 'tintuc.idLoaiTin', '=', 'loaitin.id')
    	->select('loaitin.Ten')
    	->where('tintuc.id','=', $id)
    	->get()->toArray();
		// echo "<pre>";
  //   	var_dump($tenlt);
    	$arr = json_decode(json_encode($tenlt), True);
    	$tenloaitin = $arr[0]["Ten"];
        return view('admin.tintuc.sua',['tintuc'=>$tintuc, 'theloai'=>$theloai, 'loaitin'=>$loaitin, 'tentheloai'=>$tentheloai, 'tenloaitin'=>$tenloaitin]);
    }

    public function postSua(Request $request, $id){
        $tintuc = TinTuc::find($id);
        $this->validate($request,
    		[
    			'LoaiTin'=>'required',
    			'TieuDe'=>'required|min:3|unique:TinTuc,TieuDe',
    			'TomTat'=>'required',
    			'NoiDung'=>'required'
    		],
    		[
    			'LoaiTin.required'=>'Bạn chưa chọn loại tin',
    			'TieuDe.required'=>'Bạn chưa nhập tiêu đề',
    			'TieuDe.min'=>'Tiêu đề phải có ít nhất 3 ký tự',
    			'TieuDe.unique'=>'Tiêu đề đã tồn tại',
    			'TomTat.required'=>'Bạn chưa nhập tóm tắt',
    			'NoiDung.required'=>'Bạn chưa nhập Nội dung'

    		]);

        $tintuc->TieuDe = $request->TieuDe;
    	$tintuc->TieuDeKhongDau = changeTitle($request->TieuDe);
    	$tintuc->idLoaiTin = $request->LoaiTin;
    	$tintuc->TomTat = $request->TomTat;
    	$tintuc->NoiDung = $request->NoiDung;

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
    			return redirect('admin/tintuc/them')->with('loi', 'Bạn chỉ được chọn file có đuôi jpg, png, jpeg');
    		}
    		$name = time().$file->getClientOriginalName();
    		unlink("upload/tintuc/".$tintuc->Hinh);
    		$file->move('upload/tintuc', $name);
    		$tintuc->Hinh = $name;

    	}

    	$tintuc->save();
    	return redirect('admin/tintuc/sua/'.$id)->with('thongbao', 'Bạn đã sửa thành công');
    }

    public function getXoa($id){
        $tintuc = TinTuc::find($id);
        $tintuc->delete();
        unlink('upload/tintuc/'.$tintuc->Hinh);
        return redirect('admin/tintuc/danhsach')->with('thongbao', 'Bạn đã xóa thành công');
    }
}
