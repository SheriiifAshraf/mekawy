@extends('back.inc.master')
@section('body')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">الفيديوهات</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a>فيديوهات {{ $lesson->name }}</a></li>
                    </ol>
                </nav>
            </div>
        </div>

        <!--end breadcrumb-->
        <div class="row row-cols-auto g-3 mb-3 justify-content-end">
            <div class="col">
                <a type="button" href="{{ route('lessons.videos.create', $lesson->id) }}" class="btn btn-primary px-5">
                    <i class="bx bx-cloud-upload mr-1"></i>إضافة فيديو جديد
                </a>
            </div>
        </div>
        @if (Session::has('message'))
            <script>
                swal("رسالة!", "{{ Session::get('message') }}", 'success', {
                    button: true,
                    button: "OK",
                    timer: 12000,
                });
            </script>
        @endif
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>إسم الفيديو</th>
                                <th>رابط الفيديو</th>
                                <th>مشاهدات الطلاب</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Remove duplicates based on 'name' and 'link'
                                $uniqueVideos = $videos->unique(function ($video) {
                                    return $video->name . $video->link;
                                });
                            @endphp
                            @foreach ($uniqueVideos as $video)
                                <tr>
                                    <td>{{ $video->name }}</td>
                                    <td><a href="{{ $video->link }}" target="_blank">{{ $video->link }}</a></td>
                                    <td>
                                        <a href="{{ route('videos.viewers', ['video' => $video->id]) }}"
                                            class="btn btn-info px-5 radius-30">
                                            <i class='lni lni-eye'></i> مشاهدات الطلاب
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('videos.edit', $video->id) }}" class="btn btn-outline-warning">
                                            <i class='bx bx-edit me-0'></i>
                                        </a>
                                        <a data-bs-toggle="modal" data-bs-target="#danger{{ $video->id }}"
                                            class="btn btn-outline-danger">
                                            <i class='bx bx-trash me-0'></i>
                                        </a>

                                        <!-- Modal -->
                                        <div class="modal fade" id="danger{{ $video->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <form action="{{ route('videos.delete', $video->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <div class="modal-dialog modal-lg modal-dialog-centered">
                                                    <div class="modal-content bg-danger">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-white">تأكيد الحذف</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-white">
                                                            <h3 class="text-white">هل أنت متأكد من حذف هذا الفيديو؟</h3>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">إلغاء</button>
                                                            <button type="submit" class="btn btn-dark">حذف</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
