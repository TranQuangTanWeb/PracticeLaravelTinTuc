<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;

class PageController extends Controller
{
	function __construct(){
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai', $theloai);
		view()->share('slide', $slide);
	}

    public function trangchu(){
    	// $theloai = TheLoai::all();
    	// return view('pages.trangchu',['theloai'=>$theloai]);
    	return view('pages.trangchu');
    }

    public function lienhe(){
    	return view('pages.lienhe');
    }

    public function loaitin($id){
    	$loaitin = LoaiTin::find($id);
    	$tintuc = TinTuc::where('idLoaiTin', '=', $id)->paginate(10);
    	return view('pages.loaitin', ['loaitin'=>$loaitin, 'tintuc'=>$tintuc]);
    }

    public function tintuc($id){
    	$tintuc = TinTuc::find($id);
    	$tinnoibat = TinTuc::where('NoiBat', 1)->take(4)->get();
    	$tinlienquan = TinTuc::where('idLoaiTin', $tintuc->idLoaiTin)->take(4)->get();
    	return view('pages.tintuc', ['tintuc'=>$tintuc, 'tinnoibat'=>$tinnoibat, 'tinlienquan'=>$tinlienquan]);
    }

    function getdangnhap(){
    	return view('pages.dangnhap');
    }

     function postdangnhap(Request $request){
     	 $this->validate($request,
            [
                'email'=>'required',
                'password'=>'required|min:3|max:32'
            ],
            [
                'email.required'=>'Bạn chưa nhập email',
                'password.required'=>'Bạn chưa nhập password',
                'password.min'=>'Password không được nhỏ hơn 3 ký tự',
                'password.max'=>'Password không được lớn hơn 32 ký tự',
            ]);
     	 if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
     	 	return redirect('trangchu');
			}
		else{
			return redirect('dangnhap')->with('thongbao', 'Đăng nhập không thành công');
		}
    }

    public function dangxuat(){
    	Auth::logout();
    	return redirect('trangchu');
    }

    public function getNguoiDung(){
    	return view('pages.nguoidung');
    }

    public function postNguoiDung(Request $request){
    	$this->validate($request,
    		[
    			'name'=>'required|min:3'
    		],
    		[
    			'name.required'=>'Bạn chưa nhập name',
    			'name.min'=>'name phải có ít nhất 3 ký tự'
    		]);

    	$user = Auth::User();
    	$user->name = $request->name;
    	if($request->changePassword == 'on'){
    		$this->validate($request,
    		[
    			'password'=>'required|min:3|max:32',
    			'passwordAgain'=>'required|same:password'
    		],
    		[
    			'password.required'=>'Bạn chưa nhập password',
    			'password.min'=>'password phải có ít nhất 3 ký tự',
    			'password.max'=>'password phải chỉ được phép tối đa 100 ký tự',
    			'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
    			'passwordAgain.same'=>'Mật khẩu nhập lại chưa khớp'
    		]);
	    	$user->password = bcrypt($request->password);
    	}
 		$user->save();
 		return redirect('nguoidung')->with('thongbao','Sửa thành công');
    }

    function getDangKy(){
    	return view('pages.dangky');
    }

    function postDangKy(Request $request){
    	$this->validate($request,
    		[
    			'name'=>'required|min:3',
    			'email'=>'required|email|unique:users,email',
    			'password'=>'required|min:3|max:32',
    			'passwordAgain'=>'required|same:password'
    		],
    		[
    			'name.required'=>'Bạn chưa nhập name',
    			'name.min'=>'name phải có ít nhất 3 ký tự',
    			'email.required'=>'Bạn chưa nhập email',
    			'email.email'=>'Bạn chưa nhập đúng định dạng email',
    			'email.unique'=>'Email đã tồn tại',
    			'password.required'=>'Bạn chưa nhập password',
    			'password.min'=>'password phải có ít nhất 3 ký tự',
    			'password.max'=>'password phải chỉ được phép tối đa 100 ký tự',
    			'passwordAgain.required'=>'Bạn chưa nhập lại mật khẩu',
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

    function timkiem(Request $request){
    	$tukhoa = $request->tukhoa;
    	$tintuc = TinTuc::where('TieuDe', 'like', "%$tukhoa%")->orWhere('TomTat', 'like', "%$tukhoa%")->orWhere('NoiDung', 'like', "%$tukhoa%")->take(50)->paginate(5);
    	return view('pages.timkiem', ['tintuc'=>$tintuc, 'tukhoa'=>$tukhoa]);
    }
}
