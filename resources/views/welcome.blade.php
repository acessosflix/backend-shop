<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>edfy IT</title>
  <style>
    :root {
      --bg: #0a0a0f;
      --fg: #e7e7ea;
      --muted: #b7b6c2;
      --violet: #8a2be2;
      --purple: #6a00ff;
      --accent: #a66bff;
    }

    * { box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      margin: 0;
      color: var(--fg);
      background: var(--bg);
      font: 400 16px/1.6 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, "Helvetica Neue", Arial, "Noto Sans", "Apple Color Emoji", "Segoe UI Emoji";
      overflow: hidden; /* evita scroll por causa dos blobs */
    }

    .stage {
      position: relative;
      height: 100dvh;
      display: grid;
      place-items: center;
      isolation: isolate; /* garante blur correto sobre o fundo */
    }

    /* Blobs com blur e animação sutil */
    .blob {
      position: absolute;
      width: 52vmax;
      height: 52vmax;
      filter: blur(80px);
      opacity: 0.45;
      border-radius: 50%;
      will-change: transform, opacity;
      mix-blend-mode: screen;
      pointer-events: none;
      animation: float 16s ease-in-out infinite;
    }

    .blob.violet {
      background: radial-gradient(35% 35% at 50% 50%, rgba(138,43,226,0.9), rgba(138,43,226,0.05) 70%);
      top: -10vmax; left: -10vmax;
      animation-delay: -4s;
    }

    .blob.purple {
      background: radial-gradient(40% 40% at 50% 50%, rgba(106,0,255,0.9), rgba(106,0,255,0.05) 70%);
      bottom: -12vmax; right: -8vmax;
      animation-duration: 18s;
    }

    @keyframes float {
      0%,100% { transform: translate3d(0,0,0) scale(1); }
      50%     { transform: translate3d(2vmax, -1vmax, 0) scale(1.06); }
    }

    .card {
      position: relative;
      z-index: 2;
      text-align: center;
      padding: clamp(24px, 4vw, 40px);
      background: linear-gradient(
        180deg,
        rgba(255,255,255,0.06),
        rgba(255,255,255,0.02)
      );
      border: 1px solid rgba(255,255,255,0.08);
      border-radius: 20px;
      backdrop-filter: blur(10px) saturate(120%);
      -webkit-backdrop-filter: blur(10px) saturate(120%);
      box-shadow: 0 10px 40px rgba(0,0,0,0.45);
      max-width: min(92vw, 720px);
    }

    .title {
      font-size: clamp(36px, 7vw, 64px);
      letter-spacing: 0.02em;
      line-height: 1.1;
      margin: 0 0 10px;
    }

    .subtitle {
      margin: 0 0 28px;
      color: var(--muted);
      font-size: clamp(14px, 2.6vw, 16px);
    }

    .btn {
      display: inline-block;
      text-decoration: none;
      color: #0b0b10;
      background: linear-gradient(135deg, var(--accent), #d2b0ff);
      padding: 12px 22px;
      border-radius: 999px;
      font-weight: 700;
      letter-spacing: 0.02em;
      transition: transform .15s ease, filter .2s ease, box-shadow .2s ease;
      box-shadow: 0 8px 24px rgba(166,107,255,0.35);
    }

    .btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .btn:active { transform: translateY(0); filter: brightness(0.98); }

    /* Acessibilidade de foco */
    .btn:focus-visible {
      outline: 2px solid #fff;
      outline-offset: 3px;
    }

    footer {
      position: fixed; inset: auto 0 14px 0;
      place-items: center;
      z-index: 2;
      color: #9a99a8;
      font-size: 12px;
      opacity: .9;
      user-select: none;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="stage" role="main">
    <div class="blob violet" aria-hidden="true"></div>
    <div class="blob purple" aria-hidden="true"></div>

    <section class="card" aria-label="edfy IT landing">
      <h1 class="title">Edfy IT<span aria-hidden="true"></span></h1>
      <p class="subtitle">Sistema em operação. Clique para acessar o painel.</p>
      <a class="btn" href="/admin" rel="nofollow">Acesse o sistema</a>
    </section>
  </div>

  <footer><span id="y"></span> © Edfy IT</footer>

  <script>
    // Atualiza ano automaticamente sem depender de servidor
    document.getElementById('y').textContent = new Date().getFullYear();
  </script>
</body>
</html>