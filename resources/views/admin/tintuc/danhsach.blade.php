@extends('admin.layout.index')
@section('content')
<!-- Page Content -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Tin tức
                    <small>Danh sách</small>
                </h1>
            </div>
            <!-- /.col-lg-12 -->
             @if(session('thongbao'))
                 <div class="alert alert-success">
                    {{ session('thongbao') }}
                 </div>
            @endif
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                    <tr align="center">
                        <th>ID</th>
                        <th>Tiêu đề</th>
                        <th>Tóm tắt</th>
                        <!-- <th>Tiêu đề không dấu</th> -->
                        <th>Loại tin</th>
                        <th>Thể loại</th>
                        <th>Lượt xem</th>
                        <!-- <th>Nội dung</th> -->
                        <!-- <th>Hình ảnh</th> -->
                        <th>Nổi bật</th>
                        <th>Delete</th>
                        <th>Edit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tintuc as $value)
                    <tr class="odd gradeX" align="center">
                        <td>{{ $value->id }}</td>
                        <td>{{ $value->TieuDe }}
                            <p>
                            <img src="upload/tintuc/{{ $value->Hinh }}" width="100px">
                            </p>
                        </td>
                        <td>{!! $value->TomTat !!}</td>
                        @foreach($loaitin as $lt)
                            @if($value->idLoaiTin == $lt->id)
                                <td>{{ $lt->Ten }}</td>
                                @foreach($theloai as $tl)
                                    @if($lt->idTheLoai == $tl->id)
                                         <td>{{ $tl->Ten }}</td>
                                         @break
                                    @endif
                                @endforeach
                                @break
                            @endif
                        @endforeach
                        <!-- <td>Hiện</td> -->
                        <td>{{ $value->SoLuotXem}}</td>
                        <td>
                            @if($value->NoiBat == 0)
                            {{ "Không" }}
                            @else {{ "Có" }}
                            @endif
                        </td>
                        <td class="center"><i class="fa fa-trash-o fa-fw"></i><a href="admin/tintuc/xoa/{{ $value->id }}">Xóa</a></td>
                        <td class="center"><i class="fa fa-pencil fa-fw"></i> <a href="admin/tintuc/sua/{{ $value->id }}">Sửa</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</div>
<!-- /#page-wrapper -->
@endsection