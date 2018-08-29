<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Comment;
use App\TinTuc;

class CommentController extends Controller
{
    public function getXoa($id, $idTinTuc){
    	$comment = Comment::find($id);
    	$comment->delete();
    	return redirect('admin/tintuc/sua/'.$idTinTuc)->with('thongbao','Xóa comment thành công');
    }

    function postComment(Request $request, $idTinTuc){
    	$tintuc = TinTuc::find($idTinTuc);
    	$comment = new Comment;
    	$comment->idTinTuc = $idTinTuc;
    	$comment->idUser = Auth::User()->id;
    	$comment->NoiDung = $request->NoiDung;
    	$comment->save();

    	return redirect('tintuc/'.$idTinTuc.'/'.$tintuc->TieuDeKhongDau.".html")->with('thongbao', 'Viết bình luận thành công');
    }
}
