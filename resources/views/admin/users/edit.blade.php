@extends('admin.layout')
@section('content')
<h3>Edit User: {{ $user->name }}</h3>
<div class="card p-4 mt-3" style="max-width: 500px">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="mb-3"><label>Nama</label><input type="text" name="name" value="{{ $user->name }}" class="form-control" required></div>
        <div class="mb-3"><label>Email</label><input type="email" name="email" value="{{ $user->email }}" class="form-control" required></div>
        <div class="mb-3">
            <label>Password Baru (Kosongkan jika tidak ingin ganti)</label>
            <input type="text" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User Biasa</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Master Admin</option>
            </select>
        </div>
        <button class="btn btn-warning">Update</button>
    </form>
</div>
@endsection
