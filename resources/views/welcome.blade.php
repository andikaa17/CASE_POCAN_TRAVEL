<!DOCTYPE html>
<html>
<head>
    <title>CAN TRAVEL API</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            text-align: center;
            padding: 40px;
        }

        h1 {
            margin-bottom: 5px;
        }

        .badge {
            background: #e9ecef;
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
        }

        #connectionStatus {
            margin-top: 15px;
            font-size: 14px;
            font-weight: 500;
            padding: 8px 18px;
            border-radius: 20px;
            display: inline-block;
            background: #fff3cd;
            color: #856404;
        }
    </style>
</head>
<body>

    <h1>CAN TRAVEL API</h1>
    <p>REST API untuk sistem pemesanan tiket bus</p>

    
    <div id="connectionStatus">
        Menghubungkan ke Backend...
    </div>

<script>
async function checkConnection() {
    const statusEl = document.getElementById('connectionStatus');

    try {
        const response = await fetch('/api/ping', {
            headers: {
                'X-API-Key': 'rahasia123'  
            }
        });
        const data = await response.json();

        if (data.success) {
            statusEl.innerText = "🟢 Backend Terhubung ke Frontend";
            statusEl.style.background = "#d4edda";
            statusEl.style.color = "#155724";
        } else {
            throw new Error();
        }

    } catch (error) {
        statusEl.innerText = "🔴 Backend Tidak Terhubung";
        statusEl.style.background = "#f8d7da";
        statusEl.style.color = "#721c24";
    }
}

checkConnection();
</script>

</body>
</html>