<form class="form form-vertical" action="/admin/ad/{{ $ad->id }}" method="POST" enctype="multipart/form-data">
    @method('PATCH')
    @csrf
    <input type="hidden" value="{{ $ad->id }}" name="id">
    <div class="form-body">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الاولى 555</label>
                    <input type="file" class="form-control @error('ad_1_555') is-invalid @enderror"
                        name="ad_1_555">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الثانية 555</label>
                    <input type="file" class="form-control @error('ad_2_555') is-invalid @enderror"
                        name="ad_2_555">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الاولى 350</label>
                    <input type="file" class="form-control @error('ad_1_350') is-invalid @enderror"
                        name="ad_1_350">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الثانية 350</label>
                    <input type="file" class="form-control @error('ad_2_350') is-invalid @enderror"
                        name="ad_2_350">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الثالثة 350</label>
                    <input type="file" class="form-control @error('ad_3_350') is-invalid @enderror"
                        name="ad_3_350">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض الاولى 1110</label>
                    <input type="file" class="form-control @error('ad_1_1110') is-invalid @enderror"
                        name="ad_1_1110">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="email-id-vertical">صورة العرض صفحة المتجر</label>
                    <input type="file" class="form-control @error('ad_1_shop') is-invalid @enderror"
                        name="ad_1_shop">
                </div>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-success mr-1 mb-1">تعديل</button>
            </div>
        </div>
    </div>
</form>
