<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Dalam Pemeliharaan | SIMPONI</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: #111827;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.15);
            --success: #10b981;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 20px;
            overflow: hidden;
        }

        /* Background Glow Effects */
        .glow {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, var(--primary-glow) 0%, rgba(0,0,0,0) 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            pointer-events: none;
        }

        .container {
            position: relative;
            z-index: 2;
            text-align: center;
            max-width: 560px;
            width: 100%;
            background: var(--card-bg);
            padding: 50px 40px;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Branding / Logo Area */
        .brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-main);
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .brand span {
            color: var(--primary);
        }

        /* Custom Animated SVG Icon */
        .icon-wrapper {
            margin-bottom: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 90px;
            height: 90px;
            background: rgba(99, 102, 241, 0.1);
            border-radius: 20px;
            color: var(--primary);
            position: relative;
        }

        .icon-wrapper svg {
            width: 46px;
            height: 46px;
            animation: pulse 3s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.08); opacity: 0.8; }
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
            background: linear-gradient(to right, #ffffff, #9ca3af);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            font-size: 1.05rem;
            color: var(--text-muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        /* Live Status Indicator & Auto Refresh */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--success);
            padding: 8px 16px;
            border-radius: 30px;
            font-size: 0.875rem;
            font-weight: 600;
        }

        .dot {
            width: 8px;
            height: 8px;
            background-color: var(--success);
            border-radius: 50%;
            animation: blink 1.5s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 0.4; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.2); }
        }

        .footer {
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .footer a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="glow"></div>

    <div class="container">
        <div class="brand">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            SIMPONI<span>APPS</span>
        </div>

        <div class="icon-wrapper">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
        </div>

        <h1>Peningkatan Kualitas Sistem</h1>
        
        <p>
            {{ env('APP_MAINTENANCE_MESSAGE', 'Kami sedang melakukan pemeliharaan rutin untuk meningkatkan performa dan keamanan platform. Layanan akan segera kembali normal dalam beberapa saat.') }}
        </p>

        <div class="status-badge">
            <div class="dot"></div>
            Mengecek status aplikasi secara berkala...
        </div>
    </div>

    <script>
        // Fitur Industri: Menguji koneksi ke server di latar belakang setiap 10 detik.
        // Jika web sudah up (status 200), halaman akan otomatis reload sendiri!
        setInterval(function() {
            fetch('/')
                .then(response => {
                    if (response.status === 200) {
                        window.location.reload();
                    }
                })
                .catch(error => console.log('Server masih dalam pemeliharaan...'));
        }, 10000); // 10.000 ms = 10 detik
    </script>
</body>
</html>