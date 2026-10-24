@extends('layouts.base')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-xl-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4 p-lg-5">
                <span class="badge text-bg-success mb-3">Universal Tea · Quản lý sản phẩm</span>
                <h1 class="h2 fw-bold mb-2">Đăng nhập hệ thống</h1>
                <p class="text-secondary mb-4">
                    Admin và khách hàng dùng chung trang đăng nhập.
                    Hệ thống tự xác định quyền truy cập theo tài khoản.
                </p>

                <form method="POST" action="{{ route('login') }}" class="vstack gap-3">
                    @csrf
                    <div>
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror"
                               id="email" type="email" name="email" value="{{ old('email') }}"
                               autocomplete="username" required autofocus>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="password" class="form-label fw-semibold">Mật khẩu</label>
                        <input class="form-control @error('password') is-invalid @enderror"
                               id="password" type="password" name="password"
                               autocomplete="current-password" required>
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <label class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" value="1" @checked(old('remember'))>
                        <span class="form-check-label">Ghi nhớ đăng nhập</span>
                    </label>
                    <button class="btn btn-dark btn-lg w-100" type="submit">Đăng nhập</button>
                </form>

                <div class="d-flex justify-content-between flex-wrap gap-2 mt-4 small">
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                    <a href="{{ route('register') }}">Chưa có tài khoản?</a>
                </div>
                <div class="bg-body-tertiary border rounded-3 p-3 mt-4 small text-secondary">
                    <strong>Phân quyền tự động:</strong> Admin được chuyển đến quản lý sản phẩm;
                    khách hàng được chuyển đến danh sách sản phẩm và không có quyền thêm, sửa, xóa.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
