<form class="form form-vertical" action="/admin/users/{{ $user->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $user->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="first-name-vertical">الإسم</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                        placeholder="الإسم" value="{{ old('name', $user->name) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">البريد الألكتروني</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        placeholder="البريد الألكتروني" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">رقم الهاتف</label>
                    <input type="number" class="form-control @error('phone') is-invalid @enderror" name="phone"
                        placeholder="رقم الهاتف" value="{{ old('phone', $user->phone) }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">كلمة السر</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                        placeholder="كلمة السر" value="{{ old('password') }}" required>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">الصورة</label>
                    <input type="file" class="form-control @error('profile_pic') is-invalid @enderror"
                        name="profile_pic">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">حالة الحساب</label>
                    <select name="is_active" class="form-control" required>
                        <option value="1" @selected(old('is_active', $user->is_active) == 1)>نشط</option>
                        <option value="0" @selected(old('is_active', $user->is_active) == 0)>غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">نوع المستخدم</label>
                    <select name="user_type_id" class="form-control" required>
                        @foreach ($user_types as $user_type)
                            <option value="{{ $user_type->id }}" @selected(old('user_type_id', $user->user_type_id) == $user_type->id)>
                                {{ $user_type->user_type_desc }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
