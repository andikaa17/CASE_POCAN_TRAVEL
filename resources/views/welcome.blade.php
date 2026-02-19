<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAN TRAVEL API Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: #f5f7fb;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 600;
            color: #1a1f36;
            letter-spacing: -0.5px;
        }

        .header p {
            color: #5b6e8c;
            font-size: 16px;
            margin-top: 8px;
        }

        .badge {
            display: inline-block;
            background: #e8ecf5;
            color: #1a1f36;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            margin-top: 12px;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin: 40px 0;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
            border: 1px solid #eef2f6;
        }

        .stat-card .label {
            color: #5b6e8c;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: 600;
            color: #1a1f36;
            margin-top: 8px;
        }

        /* Section Title */
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1a1f36;
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eef2f6;
        }

        /* Endpoint Grid */
        .endpoint-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 16px;
        }

        .endpoint-card {
            background: white;
            border-radius: 10px;
            padding: 16px;
            border: 1px solid #eef2f6;
            transition: all 0.2s;
        }

        .endpoint-card:hover {
            border-color: #cbd5e0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }

        .endpoint-method {
            display: inline-block;
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 10px;
            border-radius: 6px;
            background: #eef2f6;
            color: #1a1f36;
        }

        .endpoint-url {
            font-family: 'SF Mono', Monaco, Consolas, monospace;
            font-size: 13px;
            color: #2d3a4f;
            margin-left: 10px;
        }

        .endpoint-desc {
            color: #5b6e8c;
            font-size: 13px;
            margin-top: 8px;
            line-height: 1.5;
        }

        .endpoint-auth {
            margin-top: 8px;
            font-size: 11px;
            color: #8a9bb5;
        }

        .endpoint-auth span {
            display: inline-block;
            background: #f0f4fa;
            padding: 2px 8px;
            border-radius: 4px;
            margin-right: 6px;
        }

        /* Footer */
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #eef2f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #8a9bb5;
            font-size: 13px;
        }

        .footer-links a {
            color: #5b6e8c;
            text-decoration: none;
            margin-left: 20px;
            font-size: 13px;
        }

        .footer-links a:hover {
            color: #1a1f36;
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .footer {
                flex-direction: column;
                gap: 10px;
                text-align: center;
            }
            .footer-links a {
                margin: 0 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>CAN TRAVEL API</h1>
            <p>REST API untuk sistem pemesanan tiket bus</p>
            <div class="badge">v1.0.0</div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="label">Total Pengguna</div>
                <div class="number" id="totalUsers">-</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Pemesanan</div>
                <div class="number" id="totalBookings">-</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Bus</div>
                <div class="number" id="totalBuses">-</div>
            </div>
            <div class="stat-card">
                <div class="label">Total Rute</div>
                <div class="number" id="totalRoutes">-</div>
            </div>
        </div>

        <!-- Public Endpoints -->
        <div class="section-title"> Public Endpoints</div>
        <div class="endpoint-grid">
            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/ping</span>
                <div class="endpoint-desc">Cek koneksi dan status API</div>
                <div class="endpoint-auth"><span>API Key</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/register</span>
                <div class="endpoint-desc">Registrasi akun pengguna baru</div>
                <div class="endpoint-auth"><span>API Key</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/login</span>
                <div class="endpoint-desc">Login dan mendapatkan token</div>
                <div class="endpoint-auth"><span>API Key</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/schedules</span>
                <div class="endpoint-desc">Lihat semua jadwal keberangkatan</div>
                <div class="endpoint-auth"><span>API Key</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/buses</span>
                <div class="endpoint-desc">Lihat daftar bus dan fasilitas</div>
                <div class="endpoint-auth"><span>API Key</span></div>
            </div>
        </div>

        <!-- Protected Endpoints -->
        <div class="section-title"> Protected Endpoints</div>
        <div class="endpoint-grid">
            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/me</span>
                <div class="endpoint-desc">Lihat profil pengguna yang login</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/logout</span>
                <div class="endpoint-desc">Logout dan hapus token</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/bookings</span>
                <div class="endpoint-desc">Booking tiket (support multiple passenger)</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/my-bookings</span>
                <div class="endpoint-desc">Lihat riwayat booking</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/check-availability</span>
                <div class="endpoint-desc">Cek ketersediaan kursi</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>
        </div>

        <!-- Payment Endpoints -->
        <div class="section-title">Payment Endpoints</div>
        <div class="endpoint-grid">
            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/payments/{id}/process</span>
                <div class="endpoint-desc">Proses pembayaran (auto confirm)</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/payments/check/{id}</span>
                <div class="endpoint-desc">Cek status pembayaran</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/payments/history</span>
                <div class="endpoint-desc">Riwayat pembayaran user</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">POST</span>
                <span class="endpoint-url">/api/payments/{id}/refund</span>
                <div class="endpoint-desc">Refund pembayaran</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>

            <div class="endpoint-card">
                <span class="endpoint-method">GET</span>
                <span class="endpoint-url">/api/invoice/{id}/download</span>
                <div class="endpoint-desc">Download invoice PDF</div>
                <div class="endpoint-auth"><span>Bearer Token</span></div>
            </div>
        </div>

        <!-- Authentication Info -->
        <div class="section-title"> Authentication</div>
        <div style="background: white; border-radius: 10px; padding: 20px; border: 1px solid #eef2f6;">
            <p style="color: #1a1f36; margin-bottom: 12px;"><strong>API Key</strong></p>
            <p style="color: #5b6e8c; font-family: monospace; background: #f8fafd; padding: 10px; border-radius: 6px; margin-bottom: 20px;">X-API-Key: rahasia123</p>
            
            <p style="color: #1a1f36; margin-bottom: 12px;"><strong>Bearer Token</strong></p>
            <p style="color: #5b6e8c; font-family: monospace; background: #f8fafd; padding: 10px; border-radius: 6px;">Authorization: Bearer {token_dari_login}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>© 2026 CAN TRAVEL. All rights reserved.</div>
            <div class="footer-links">
                <a href="#">Dokumentasi</a>
                <a href="#">Postman</a>
                <a href="#">GitHub</a>
            </div>
        </div>
    </div>

    <script>
        // Fetch real stats
        Promise.all([
            fetch('/api/ping').then(res => res.json()),
            fetch('/api/buses').then(res => res.json())
        ])
        .then(([ping, buses]) => {
            if (ping.success) {
                // Update stats with real data (simulasi)
                document.getElementById('totalUsers').innerText = '999';
                document.getElementById('totalBookings').innerText = '99';
                document.getElementById('totalBuses').innerText = buses.data?.length || '77';
                document.getElementById('totalRoutes').innerText = '7';
            }
        })
        .catch(() => {
            // Fallback ke data dummy
            document.getElementById('totalUsers').innerText = '999';
            document.getElementById('totalBookings').innerText = '99';
            document.getElementById('totalBuses').innerText = '77';
            document.getElementById('totalRoutes').innerText = '7';
        });
    </script>
</body>
</html>