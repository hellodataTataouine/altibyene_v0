
<style>
    /* Style personnalisé pour la bulle Tawk.to */
    #custom-tawk-bubble {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      right: 100px;
      background-color: #25D366;
      border-radius: 50%;
      display: flex;
      justify-content: center;
      align-items: center;
      cursor: pointer;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      z-index: 9999;
      transition: all 0.3s ease;
    }

    #custom-tawk-bubble:hover {
      transform: scale(1.1);
      background-color: #128C7E;
    }

    #custom-tawk-bubble img {
      width: 30px;
      height: 30px;
      filter: brightness(0) invert(1); /* Icône blanche */
    }

    /* Masquer la bulle par défaut de Tawk.to */
    .tawk-button-container {
      display: none !important;
    }

  </style>
  <!-- Bulle personnalisée pour Tawk.to -->
  <div id="custom-tawk-bubble">
    <img src="https://cdn-icons-png.flaticon.com/512/134/134937.png" alt="Chat">
  </div>

  <!-- Script Tawk.to
  <script>
    // Configuration pour masquer la bulle par défaut
    window.Tawk_API = window.Tawk_API || {};
    window.Tawk_API.onLoad = function(){
      document.querySelector('.tawk-button-container').style.display = 'none';
    };

    // Script Tawk.to (remplacez par votre code de widget)
    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
    (function(){
      var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
      s1.async=true;
      s1.src='https://embed.tawk.to/VOTRE_ID_TAWKTO/default';
      s1.charset='UTF-8';
      s1.setAttribute('crossorigin','*');
      s0.parentNode.insertBefore(s1,s0);
    })();

    // Gestion de notre bulle personnalisée
    document.getElementById('custom-tawk-bubble').addEventListener('click', function() {
      if(window.Tawk_API.toggle) {
        window.Tawk_API.toggle();
      }
    });

  window.Tawk_API = window.Tawk_API || {};
  Tawk_API.onLoad = function(){
    // Message auto après 45 sec
    setTimeout(function(){
      Tawk_API.sendMessage("Découvrez nos promotions du moment : https://exemple.com/promos");
    }, 45000);

    // Message si visiteur reste plus de 2 min
    setTimeout(function(){
      Tawk_API.sendMessage("Vous semblez intéressé(e), puis-je vous aider ?");
    }, 120000);
  };
  
  </script>-->
