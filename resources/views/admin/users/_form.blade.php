<div class="space-y-4">
<div><label class="mb-1 block text-sm font-medium">Username</label><input class="admin-input" name="username" value="{{ old('username',$user->username ?? '') }}" maxlength="50" required></div>
<div><label class="mb-1 block text-sm font-medium">Email</label><input class="admin-input" type="email" name="email" value="{{ old('email',$user->email ?? '') }}" required></div>
<div><label class="mb-1 block text-sm font-medium">Password @isset($user)<span class="font-normal text-gray-400">(kosongkan jika tidak diubah)</span>@endisset</label><input class="admin-input" type="password" name="password" @empty($user) required @endempty autocomplete="new-password"></div>
<div><label class="mb-1 block text-sm font-medium">Konfirmasi Password</label><input class="admin-input" type="password" name="password_confirmation" @empty($user) required @endempty autocomplete="new-password"></div>
<div><label class="mb-1 block text-sm font-medium">Role</label><select class="admin-input" name="role" required><option value="user" @selected(old('role',$user->role ?? 'user')==='user')>User</option><option value="admin" @selected(old('role',$user->role ?? '')==='admin')>Admin</option></select></div>
</div>
