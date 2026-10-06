{{-- Expects $order (App\Models\Order). Optional $selectIdSuffix for unique ids in tables. --}}
@php
    $suffix = $selectIdSuffix ?? $order->id;
    $statusLabels = [
        \App\Constants\OrderStatus::PENDING => 'جديد',
        \App\Constants\OrderStatus::RECEIVED => 'تحت المعالجة',
        \App\Constants\OrderStatus::DELIVERED => 'تم التوصيل',
        \App\Constants\OrderStatus::CANCELLED => 'ملغي',
    ];
    $currentStatus = (int) $order->order_status;
    $currentLabel = $statusLabels[$currentStatus] ?? 'غير معروف';
    $badgeClass = match ($currentStatus) {
        \App\Constants\OrderStatus::PENDING => 'badge-danger',
        \App\Constants\OrderStatus::RECEIVED => 'badge-warning',
        \App\Constants\OrderStatus::DELIVERED => 'badge-success',
        \App\Constants\OrderStatus::CANCELLED => 'badge-secondary',
        default => 'badge-light',
    };
    $modalId = 'orderStatusModal_' . $suffix;
@endphp
<div class="order-status-widget">
    <div class="d-flex flex-wrap align-items-center" style="gap: 8px;">
        <span class="badge {{ $badgeClass }}">{{ $currentLabel }}</span>
        <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#{{ $modalId }}">
            <i class="fa fa-exchange"></i> تغيير الحالة
        </button>
    </div>
</div>

<div class="modal fade text-left" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('order.status.update', $order) }}" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h4 class="modal-title">تغيير حالة الطلب #{{ $order->id }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        <strong>الحالة الحالية:</strong>
                        <span class="badge {{ $badgeClass }}">{{ $currentLabel }}</span>
                    </p>
                    <div class="form-group">
                        <label for="order_status_select_{{ $suffix }}">الحالة الجديدة</label>
                        <select name="order_status" id="order_status_select_{{ $suffix }}" class="form-control" required>
                            @foreach ($statusLabels as $value => $label)
                                <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">حفظ</button>
                </div>
            </form>
        </div>
    </div>
</div>
