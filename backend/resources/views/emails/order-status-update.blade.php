<!DOCTYPE html>
<html lang="{{ Session::get('locale', 'en') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('emails.order_status_update_subject', ['order_id' => $order->id, 'status' => $newStatus]) }}</title>
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
            background-color: #28a745;
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
        .status-box {
            background-color: white;
            padding: 15px;
            margin: 15px 0;
            border-radius: 5px;
            border-left: 4px solid #28a745;
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
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('emails.order_status_update_title') }}</h1>
    </div>
    
    <div class="content">
        <p>{{ __('emails.hello') }} {{ $order->costumer_name }},</p>
        
        <p>{{ __('emails.order_status_update_message', ['order_id' => $order->id]) }}</p>
        
        <div class="status-box">
            <p><strong>{{ __('emails.previous_status') }}:</strong> {{ $oldStatus }}</p>
            <p><strong>{{ __('emails.current_status') }}:</strong> <strong style="color: #28a745;">{{ $newStatus }}</strong></p>
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
