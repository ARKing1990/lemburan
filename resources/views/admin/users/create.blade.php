@extends('admin.layout')
@section('content')
    <h3>Buat User Baru</h3>
    <div class="card p-4 mt-3" style="max-width: 500px">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="mb-3"><label>Nama</label><input type="text" name="name" class="form-control" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label>Password</label><input type="text" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Role</label>
                <select name="role" class="form-control">
                    <option value="user">User Biasa</option>
                    <option value="admin">Master Admin</option>
                </select>
            </div>
            <button class="btn btn-primary">Simpan</button>
        </form>
    </div>
@endsection
