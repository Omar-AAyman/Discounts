<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>QR Code PDF</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap');

        body {
            text-align: center;
            font-family: 'Tajawal', Arial, sans-serif;
            direction: rtl;
            position: relative;
        }

        .header {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 120px; /* Adjust size as needed */
        }

        .qr-container {
            margin-top: 80px; /* Adjusted to prevent overlap with logo */
        }

        .qr-img {
            width: 250px;
            height: 250px;
            object-fit: contain;
        }

        .store-name {
            margin-top: 10px;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>

<!-- Logo at the top-left corner -->
<img src="{{ public_path('images/logo.png') }}" class="header" alt="Company Logo">

<h2>Store QR Code</h2>

<div class="qr-container">
    <img src="{{ $qrCodeBase64 }}" class="qr-img" alt="QR Code">
</div>

</body>
</html>
