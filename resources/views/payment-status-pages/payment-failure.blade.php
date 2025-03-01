<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Failed - Wallet Deals</title>
    <link rel="icon" href="{{ asset('assets/hero.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --redColor: #dc3545;
            --darkRedColor: #a71d2a;
            --text-primary: #1a1a1a;
            --text-secondary: #4d4d4d;
            --background: #f8f9fa;
        }

        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at 10% 20%, rgba(220, 53, 69, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 80%, rgba(220, 53, 69, 0.1) 0%, transparent 20%),
                var(--background);
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .main-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .payment-failure-container {
            max-width: 700px;
            width: 100%;
            perspective: 1000px;
        }

        .card {
            border: none;
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            transform-style: preserve-3d;
            transition: all 0.5s ease-in-out;
        }

        .card:hover {
            transform: translateY(-10px) rotateX(2deg);
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.12);
        }

        .card-header {
            background: linear-gradient(135deg, var(--redColor), var(--darkRedColor));
            height: 12px;
            border-radius: 24px 24px 0 0 !important;
        }

        .status-title {
            color: var(--text-primary);
            font-weight: 800;
            font-size: 2.5rem;
            background: linear-gradient(135deg, var(--redColor) 30%, var(--darkRedColor) 70%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .status-message {
            color: var(--text-secondary);
            font-size: 1.2rem;
            line-height: 1.6;
            max-width: 80%;
            margin: 0 auto 2rem;
        }

        .footer {
            background: white;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            padding: 2rem 0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            position: relative;
        }

        .footer-logo {
            max-width: 120px;
            height: auto;
            margin-bottom: 1rem;
        }

        .footer-text {
            color: var(--text-secondary);
            font-size: 1rem;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="payment-failure-container">
            <div class="card">
                <div class="card-header"></div>
                <div class="card-body text-center p-5">
                    <div class="failure-image-container">
                        <i class="fas fa-times-circle" style="font-size: 6rem; color: var(--redColor);"></i>
                    </div>
                    <h1 class="status-title">Payment Failed</h1>
                    <p class="status-message">There was an issue processing your payment. Please try again.</p>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <a href="https://walldeals.online/" target="_blank">
                <img src="{{ asset('assets/hero.png') }}" alt="Wallet Deals Logo" class="footer-logo">
            </a>
            <p class="footer-text mb-0">Wallet Deals - Your Ultimate Savings Companion.</p>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>
