@extends('main/admin/layout/master')
@section('title')
    Profile
@endsection
@section('content')
<div id="profile-content" class="mx-3">
   <div class="p-2">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="font-size:11pt;" role="alert">
                {{session('success')}}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" style="font-size:11pt;" role="alert">
                {{session('warning')}}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('danger'))
            <div class="alert alert-danger alert-dismissible fade show" style="font-size:11pt;" role="alert">
                {{session('danger')}}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger alert-dismissible fade show" style="font-size:11pt;" role="alert">
                    {{ $error }}
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-lg-5 mb-5">
            <div class="profile-detail">
                <img src="{{asset('admin/image/3515103.jpg')}}" class="w-25 img-thumbnail d-block" alt="">
                <div>
                    <div>
                        <div class="d-flex align-items-center mb-1">
                            <label for="" class="me-2">Name :</label>
                            <h6 class="mt-2">{{Auth::user()->name}}</h6>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <label for="" class="me-2">Email :</label>
                            <h6 class="mt-2">{{Auth::user()->email}}</h6>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <label for="" class="me-2">Phone :</label>
                            <h6 class="mt-2">{{Auth::user()->phone}}</h6>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <label for="" class="me-2">Address :</label>
                            <h6 class="mt-2">{{Auth::user()->address}}</h6>
                        </div>
                        <div class="d-flex align-items-center mb-1">
                            <label for="" class="me-2">Position :</label>
                            <h6 class="mt-2">{{Auth::user()->position}}</h6>
                        </div>
                    </div>
                </div>
            </div>
            <div class="profile-private">
                <div class="profile-detail-content">
                    <ul>
                        <li><a href="javascript:void(0)" class="text-content text-active" onclick="detailsClick(event,'updateProfile')">Personal Information</a></li>
                        <li><a href="javascript:void(0)" class="text-content" onclick="detailsClick(event,'changePassword')">Change Password</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-7 mb-5">
            <div id="detail-show">
                <div id="update-profile">
                    <h5 class="text-center">Personal Information</h5>
                    <hr>
                    <form action="{{route('admin#profileUpdate')}}" method="POST" class="mt-4 update-btn-group">
                        @csrf
                        <div class="form-group mb-3">
                            <label class="mb-1 fw-semibold">Name</label>
                            <input id="" type="text" class="form-control" value="{{Auth::user()->name}}" name="name" placeholder="Enter name">
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1 fw-semibold">Email</label>
                            <input id="" type="email" class="form-control" value="{{Auth::user()->email}}" name="email" placeholder="Enter email">
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1 fw-semibold">Phone</label>
                            <input id="" type="text" class="form-control" value="{{Auth::user()->phone}}" name="phone" placeholder="Enter phone">
                        </div>

                        <div class="form-group mb-4">
                            <label class="mb-1 fw-semibold">Address</label>
                            <input id="" type="text" class="form-control" value="{{Auth::user()->address}}" name="address" placeholder="Enter address">
                        </div>

                        <button class="btn w-100 border-0 update-btn">Save Changes</button>
                    </form>
                </div>
                <div id="change-pass">
                    <h5 class="text-center">Change Password</h5>
                    <hr>
                    <form action="{{route('admin#changePassword')}}" method="POST" class="update-btn-group">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="control-label mb-1 fw-semibold">Current Password</label>
                            <input type="password" name="currentPassword" class="form-control" placeholder="Enter your current password">
                        </div>

                        <div class="form-group mb-4">
                            <label class="control-label mb-1 fw-semibold">New Password</label>
                            <input type="password" name="newPassword" class="form-control" placeholder="Enter your new password">
                        </div>

                        <div class="form-group mb-4">
                            <label class="control-label mb-1 fw-semibold">Confirm Password</label>
                            <input type="password" name="confirmPassword" class="form-control" placeholder="Re-enter your new password">
                        </div>

                        <button class="btn w-100 border-0 update-btn">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    // updatebtn disabled check
   const forms = document.querySelectorAll('.update-btn-group');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input');
        const button = form.querySelector('.update-btn');
        const initialValues = {};
        inputs.forEach(input => {
            initialValues[input.name] = input.value;
        });
        function checkUpdateValueForBtn() {
            let hasChanged = false;
            inputs.forEach(input => {
                if (input.value != initialValues[input.name]) {
                    hasChanged = true;
                }
            });
            button.disabled = !hasChanged;
        }
        inputs.forEach(input => {
            input.addEventListener('input', checkUpdateValueForBtn);
            input.addEventListener('change', checkUpdateValueForBtn);
        });
        checkUpdateValueForBtn();
    });

</script>
@endsection
