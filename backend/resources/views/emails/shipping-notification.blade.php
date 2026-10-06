<!DOCTYPE html>
<html lang="{{ Session::get('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.shipping_notification_subject', ['order_id' => $order->id]) }}</title>
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
            background-color: #17a2b8;
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
        .shipping-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #17a2b8;
        }
        .tracking-number {
            font-size: 18px;
            font-weight: bold;
            color: #17a2b8;
            padding: 10px;
            background-color: #e7f3f5;
            border-radius: 5px;
            text-align: center;
            margin: 15px 0;
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
            background-color: #17a2b8;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('emails.shipping_notification_title') }}</h1>
    </div>
    
    <div class="content">
        <p>{{ __('emails.hello') }} {{ $order->costumer_name }},</p>
        
        <p>{{ __('emails.shipping_notification_message', ['order_id' => $order->id]) }}</p>
        
        <div class="shipping-box">
            <h3>{{ __('emails.shipping_details') }}</h3>
            <p><strong>{{ __('emails.order_number') }}:</strong> #{{ $order->id }}</p>
            <p><strong>{{ __('emails.delivery_address') }}:</strong> {{ $order->address }}</p>
            @if($trackingNumber)
            <div class="tracking-number">
                <p>{{ __('emails.tracking_number') }}: {{ $trackingNumber }}</p>
            </div>
            @endif
        </div>
        
        <p>{{ __('emails.shipping_estimated_delivery') }}</p>
        
        <div style="text-align: center;">
            <a href="{{ url('/account/orders/' . $order->id) }}" class="button">{{ __('emails.track_order') }}</a>
        </div>
        
        <p>{{ __('emails.thank_you_message') }}</p>
    </div>
    
    <div class="footer">
        <p>{{ __('emails.footer_message') }}</p>
        <p>&copy; {{ date('Y') }} Rain Technology. {{ __('emails.all_rights_reserved') }}</p>
    </div>
</body>
</html>
