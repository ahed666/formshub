<!DOCTYPE html>
<html>
<head>
    <title>Invoice Paid</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; padding: 20px;">
    <div style="background-color: #ffffff; border: 1px solid #ddd; border-radius: 5px; padding: 20px; max-width: 600px; margin: auto;">
        <h1 style="color: #4CAF50;">Thank you for your payment!</h1>
        <p style="font-size: 16px; color: #333;">We have successfully received your payment. Here are the details:</p>
        <p><strong>Invoice ID:</strong> {{ $invoice->id }}</p>
        <p><strong>Amount Paid:</strong> {{ number_format($invoice->amount_paid / 100, 2) }} {{ strtoupper($invoice->currency) }}</p>
        
        <p style="margin-top: 20px;">
            <a href="{{ $invoice->invoice_pdf }}" 
               style="background-color: #4CAF50; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px;"
               target="_blank" 
               rel="noopener">
               Download Invoice 
            </a>
        </p>
        <p style="font-size: 12px; color: #999; margin-top: 20px;">
            If you have any questions, please contact us at info@formshub.net.
        </p>
    </div>
</body>
</html>
