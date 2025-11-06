<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Redirecionando...</title>

  <style>
    body {
      background: #0f172a;
      color: #f8fafc;
      font-family: Arial, sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
      text-align: center;
    }

    h1 {
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }

    p {
      font-size: 1.2rem;
      color: #cbd5e1;
    }

    .loader {
      margin: 2rem 0;
      border: 6px solid rgba(255, 255, 255, 0.2);
      border-top: 6px solid #38bdf8;
      border-radius: 50%;
      width: 60px;
      height: 60px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      to {
        transform: rotate(360deg);
      }
    }

    .countdown {
      margin-top: 1rem;
      font-weight: bold;
      color: #38bdf8;
    }
  </style>
</head>

<body>
  <h1>Redirecionando para CNFit Performance...</h1>
  <div class="loader"></div>
  <p>Você será redirecionado em <span id="timer" class="countdown">5</span> segundos.</p>

  <script>
    let seconds = 5;
    const timer = document.getElementById("timer");

    const countdown = setInterval(() => {
      seconds--;
      timer.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(countdown);
        window.location.href = "https://cnfitperformance.com/";
      }
    }, 1000);
  </script>
</body>
</html>
