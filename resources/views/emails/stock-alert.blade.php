<!DOCTYPE html>
<html>
<head>
    <title>{{ __('Stock alert') }}</title>
</head>
<body>
    <p>Produk berikut sudah mencapai atau melewati batas minimum stok:</p>
    @foreach ($listProducts as $product)
        <p>Nama produk: {{ $product->name }}</p>
        <p>Stok saat ini: {{ $product->quantity }}</p>
        <p>Batas minimum: {{ $product->quantity_alert }}</p>
        <hr>
    @endforeach

</body>
</html>
