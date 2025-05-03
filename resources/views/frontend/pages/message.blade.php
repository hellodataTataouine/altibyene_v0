
    <style>
        /* Style de la bulle de conversation */
        .chat-container {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: #f1f1f1;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: none; /* Masqué au départ */
            flex-direction: column;
            padding: 10px;
            z-index: 9999;
        }

        .chat-header {
            background-color: #25D366;
            color: white;
            padding: 10px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }

        .chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            margin-top: 10px;
            background-color: #fff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .chat-box .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 15px;
            background-color: #f1f1f1;
            max-width: 80%;
            clear: both;
        }

        .chat-box .message.user {
            background-color: #DCF8C6;
            float: right;
        }

        .chat-box .message.bot {
            background-color: #e6e6e6;
            float: left;
        }

        .chat-footer {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
        }

        .chat-footer input {
            width: 80%;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .chat-footer button {
            background-color: #25D366;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            cursor: pointer;
        }

        .chat-footer button:hover {
            background-color: #128C7E;
        }

        /* Style pour la bulle fixe */
        #chat-bubble {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
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

        #chat-bubble:hover {
            transform: scale(1.1);
            background-color: #128C7E;
        }

        #chat-bubble img {
            width: 30px;
            height: 30px;
            filter: brightness(0) invert(1); /* Icône blanche */
        }
    </style>

<!-- Bulle de discussion -->
<div id="chat-bubble" style="margin-right:80px">
    <img src="https://cdn-icons-png.flaticon.com/512/134/134937.png" alt="Chat">
</div>

<!-- Fenêtre de discussion-->
<div class="chat-container" id="chat-container">
    <div class="chat-header">
        Chat en direct
    </div>

    <div class="chat-box" id="chat-box">
        <div class="message bot">Bonjour, comment puis-je vous aider ?</div>
    </div>

    <div class="chat-footer">
        <input type="text" id="message-input" placeholder="Tapez votre message...">
        <button onclick="sendMessage()">Envoyer</button>
    </div>
</div>

<script>

    // Fonction pour afficher/masquer la fenêtre de chat
    const chatBubble = document.getElementById('chat-bubble');
    const chatContainer = document.getElementById('chat-container');

    chatBubble.addEventListener('click', function() {
        chatContainer.style.display = chatContainer.style.display === "none" ? "flex" : "none";
    });

    // Fonction pour envoyer un message vers WhatsApp
    async function sendMessage() {
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();

        if (!message) return;

        const chatBox = document.getElementById('chat-box');

        // Ajouter le message de l'utilisateur
        const userMessage = document.createElement('div');
        userMessage.classList.add('message', 'user');
        userMessage.textContent = message;
        chatBox.appendChild(userMessage);

        // Envoyer le message à WhatsApp
        try {
            const response = await sendToWhatsApp(message);
            console.log('Message envoyé à WhatsApp:', response);

            // Réponse automatique
            const botMessage = document.createElement('div');
            botMessage.classList.add('message', 'bot');
            botMessage.textContent = "Votre message a été envoyé à notre équipe WhatsApp. Nous vous répondrons rapidement!";
            chatBox.appendChild(botMessage);

        } catch (error) {
            console.error('Erreur:', error);
            const errorMessage = document.createElement('div');
            errorMessage.classList.add('message', 'bot', 'error');
            errorMessage.textContent = "Désolé, une erreur s'est produite. Veuillez réessayer.";
            chatBox.appendChild(errorMessage);
        }

        // Reset et scroll
        messageInput.value = "";
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Fonction pour envoyer à l'API WhatsApp
    async function sendToWhatsApp(message) {
        const phoneNumber = "21658038510"; // Remplacez par votre numéro
        const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

        // Option 1: Ouvrir WhatsApp directement
        window.open(whatsappUrl, '_blank');

        // Option 2: Envoyer via une API Laravel (recommandé pour le suivi)

        const response = await fetch('/api/send-whatsapp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                message: message,
                phone: phoneNumber
            })
        });
        return await response.json();
       
    }

    // Gestion de la touche Entrée
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            sendMessage();
        }
    });


</script>

