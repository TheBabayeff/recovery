@foreach($state as $item)
    <div style="border-bottom: 1px solid #ddd; padding: 8px 0;">
        <strong>{{ $item['service'] }}</strong><br>
        Qiymət: ₼{{ number_format($item['price'], 2) }}<br>
        Miqdar: {{ $item['qty'] }}<br>
        Toplam: ₼{{ number_format($item['total'], 2) }}
    </div>
@endforeach
