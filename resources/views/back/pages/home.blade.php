@extends('back.inc.master')
@section('body')
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body p-5 d-flex flex-column flex-md-row align-items-center justify-content-between">
            <div>
                <h1 style="font-size: 30px" class="display-5 fw-bold mb-3 text-primary">مرحباً بك في لوحة التحكم الخاصة بموقع
                    <strong style="font-size: 35px!important" class="text-warning">جيو مكاوي</strong>
                </h1>
                <p class="lead mb-4 text-secondary">
                    يسعدنا انضمامك! من هنا يمكنك إدارة الكورسات، التحكم في الطلاب والإشتراكات وغيرهم
                    بكل سهولة وفعالية.
                </p>
            </div>
            <div class="mt-4 mt-md-0">
                <img src="{{ asset('back/assets/images/10991988.jpg') }}" alt="Welcome" style="max-width: 300px;">
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 text-secondary">عدد الكورسات</h6>
                            <h4 class="my-1 text-info"> {{ $courseCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                class='bx bxs-cart'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-danger">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 text-secondary">عدد الدروس</h6>
                            <h4 class="my-1 text-danger">{{ $lessonCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-burning text-white ms-auto"><i
                                class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 text-secondary">عدد الامتحانات</h6>
                            <h4 class="my-1 text-warning">{{ $examCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                class='bx bxs-bar-chart-alt-2'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h6 class="mb-0 text-secondary">عدد الطلاب</h6>
                            <h4 class="my-1 text-success">{{ $studentCount }}</h4>

                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-ohhappiness text-white ms-auto"><i
                                class='bx bxs-group'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
