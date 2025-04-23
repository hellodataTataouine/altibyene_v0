<style>


    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@500;700&display=swap');

    .adhkar-container {
      position: fixed;
      bottom: 580px;
      left: 50%;
      transform: translateX(-50%);
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border-radius: 25px;
      padding: 18px 30px;
      box-shadow: 0 0 25px  #ffffff;
      border: 2px solid  #F7C815;
      text-align: center;
      transition: all 0.5s ease;
      opacity: 0;
      transform: translate(-50%, 30px);
      min-width: 260px;
      max-width: 400px;
      z-index: 999;
      margin-left:-43%;

    }

    .adhkar-container.show {
      opacity: 1;
      transform: translate(-50%, 0);
    }

    .adhkar-text {
      font-size: 22px;
      color: #333;
      font-weight: 600;
      letter-spacing: 1px;
      text-shadow: 0 0 4px #000000;
    }

    .adhkar-decor {
      position: absolute;
      top: -10px;
      right: -10px;
      width: 20px;
      height: 20px;
      background:#F7C815;
      border-radius: 50%;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0% {
        transform: scale(0.9);
        opacity: 0.7;
      }
      50% {
        transform: scale(1.2);
        opacity: 1;
      }
      100% {
        transform: scale(0.9);
        opacity: 0.7;
      }
    }
  </style>

  <div class="adhkar-container" id="adhkarBox"  >
    <div class="adhkar-decor"></div>
    <div class="adhkar-text" id="adhkarText"></div>
  </div>

  <script>
    const adhkarList = [
      " ٱللَّهِ  ﷽",
      "سُبْحَانَ ٱللَّٰه",
      "ٱلْـحَمْدُ لِلَّٰه",
      "اللّٰهُ أَكْبَر",
      "لَا إِلَٰهَ إِلَّا ٱللَّٰه",
      "ٱسْتَغْفِرُ ٱللَّٰه",
      "اللَّهُمَّ صَلِّ عَلَىٰ مُحَمَّد",
      "سُبْحَانَ ٱللَّهِ وَبِحَمْدِهِ",
      "سُبْحَانَ ٱللَّهِ ٱلْعَظِيم"
    ];

    const box = document.getElementById('adhkarBox');
    const text = document.getElementById('adhkarText');
    let index = 0;

    function showAdhkar() {
      box.classList.remove('show');
      setTimeout(() => {
        text.innerText = adhkarList[index];
        box.classList.add('show');
        index = (index + 1) % adhkarList.length;
      }, 300);
    }

    showAdhkar();
    setInterval(showAdhkar, 5000); // toutes les 5 secondes
  </script>
