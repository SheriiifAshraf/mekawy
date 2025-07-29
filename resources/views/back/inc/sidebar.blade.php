<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ url('back/assets/images/مستر أحمد مكاوي -01.png') }}" class="logo-icon" alt="logo icon" style="width: 50px">
        </div>
        <div>
            <h4 class="logo-text">جيو مكاوي</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">

        <li class="nav-item">
            <a href="{{ url('dashboard') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/home.png') }}" style="width: 29px;">
                </div>
                <div class="menu-title">الصفحة الرئيسية</div>
            </a>
        </li>

        <hr>

        <li class="nav-item ">
            <a href="{{ url('courses/index') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/classroom.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">الكورسات</div>
            </a>

        </li>

        <li class="nav-item ">

            <a href="{{ url('questions/index') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/qa.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">بنك الاسئلة</div>
            </a>
        </li>

        <li class="nav-item ">

            <a href="{{ url('codes/index') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/binary.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">الأكواد</div>
            </a>
        </li>


        <li class="nav-item ">

            <a href="{{ url('subscriptions/index') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/subscription.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">إشتراكات الكورسات</div>
            </a>
        </li>

        <li class="nav-item ">

            <a href="{{ url('students/index') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/education.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">جميع الطلاب</div>
            </a>
        </li>

        <hr>

        <li class="nav-item ">

            <a href="{{ url('settings') }}" class="">
                <div class="parent-icon"><img src="{{ url('back/assets/images/social-media.png') }}"
                        style="width: 45px;">
                    <div class="menu-title">
                        وسائل التواصل الإجتماعي
                    </div>
            </a>
        </li>

    </ul>
    <!--end navigation-->
</div>
<!--end sidebar wrapper -->
