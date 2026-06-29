<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>419 - Sesi Habis - {{ config('app.name') }}</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #1e293b; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
    .container { text-align: center; padding: 2rem; }
    .code { font-size: 8rem; font-weight: 800; line-height: 1; background: linear-gradient(135deg, #4f46e5, #7c3aed); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    .message { margin-top: 1rem; font-size: 1.25rem; color: #64748b; }
    .action { margin-top: 2rem; }
    .action a { display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.75rem 1.5rem; background: #4f46e5; color: white; text-decoration: none; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; transition: background 0.2s; }
    .action a:hover { background: #4338ca; }
  </style>
</head>
<body>
  <div class="container">
    <div class="code">419</div>
    <div class="message">Sesi Anda telah berakhir. Silakan muat ulang halaman.</div>
    <div class="action"><a href="{{ url('/') }}">← Kembali ke Beranda</a></div>
  </div>
</body>
</html>
