<!DOCTYPE html>
<html lang="{{ Session::get('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.order_confirmation_subject', ['order_id' => $order->id]) }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }
        .order-info {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .order-items {
            margin: 20px 0;
        }
        .order-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #007bff;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('emails.order_confirmation_title') }}</h1>
    </div>
    
    <div class="content">
        <p>{{ __('emails.hello') }} {{ $order->costumer_name }},</p>
        
        <p>{{ __('emails.order_confirmation_message', ['order_id' => $order->id]) }}</p>
        
        <div class="order-info">
            <h3>{{ __('emails.order_information') }}</h3>
            <p><strong>{{ __('emails.order_number') }}:</strong> #{{ $order->id }}</p>
            <p><strong>{{ __('emails.order_date') }}:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
            <p><strong>{{ __('emails.order_status') }}:</strong> {{ \App\Constants\OrderStatus::label($order->order_status) }}</p>
        </div>
        
        <div class="order-items">
            <h3>{{ __('emails.order_items') }}</h3>
            @foreach($order->orderitems as $item)
            <div class="order-item">
                <p><strong>{{ Session::get('locale') === 'ar' ? $item->product->name_ar : $item->product->name_en }}</strong></p>
                <p>{{ __('emails.quantity') }}: {{ $item->quantity }} × {{ $item->product->price }} KWD = {{ $item->quantity * $item->product->price }} KWD</p>
            </div>
            @endforeach
        </div>
        
        <div class="total">
            <p>{{ __('emails.total_amount') }}: {{ $order->total }} KWD</p>
        </div>
        
        <div class="order-info">
            <h3>{{ __('emails.delivery_information') }}</h3>
            <p><strong>{{ __('emails.name') }}:</strong> {{ $order->costumer_name }}</p>
            <p><strong>{{ __('emails.phone') }}:</strong> {{ $order->costumer_number }}</p>
            <p><strong>{{ __('emails.address') }}:</strong> {{ $order->address }}</p>
            @if($order->note)
            <p><strong>{{ __('emails.notes') }}:</strong> {{ $order->note }}</p>
            @endif
        </div>
        
        <div style="text-align: center;">
            <a href="{{ url('/account/orders/' . $order->id) }}" class="button">{{ __('emails.view_order') }}</a>
        </div>
        
        <p>{{ __('emails.thank_you_message') }}</p>
    </div>
    
    <div class="footer">
        <p>{{ __('emails.footer_message') }}</p>
        <p>&copy; {{ date('Y') }} Rain Technology. {{ __('emails.all_rights_reserved') }}</p>
    </div>
</body>
</html>
