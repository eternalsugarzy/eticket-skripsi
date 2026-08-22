<style>
    /* Chatbot Widget Styles */
    :root {
        --chat-width: 380px;
        --chat-height: 520px;
    }

    #chatbot-widget {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 1050;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    #chatbot-fab {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: var(--forest);
        color: white;
        border: none;
        box-shadow: 0 4px 12px rgba(26, 61, 43, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        cursor: pointer;
        transition: transform 0.3s ease, background-color 0.3s ease;
        position: relative;
    }

    #chatbot-fab:hover {
        transform: scale(1.05);
        background-color: var(--forest-mid);
    }

    .chatbot-badge {
        position: absolute;
        top: 0;
        right: 0;
        width: 16px;
        height: 16px;
        background-color: #ef4444;
        border-radius: 50%;
        border: 2px solid white;
        animation: pulse 2s infinite;
        display: none;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    #chatbot-window {
        width: var(--chat-width);
        height: var(--chat-height);
        max-height: calc(100vh - 100px);
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(15, 28, 20, 0.15);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        margin-bottom: 16px;
        opacity: 0;
        transform: translateY(20px) scale(0.95);
        pointer-events: none;
        transition: opacity 0.3s ease, transform 0.3s ease;
        transform-origin: bottom right;
        border: 1px solid rgba(26, 61, 43, 0.1);
    }

    #chatbot-window.open {
        opacity: 1;
        transform: translateY(0) scale(1);
        pointer-events: auto;
    }

    .chatbot-header {
        background: linear-gradient(135deg, var(--forest), var(--forest-mid));
        color: white;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 3px solid var(--gold);
    }

    .chatbot-header-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .chatbot-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .chatbot-header-info h5 {
        margin: 0;
        font-size: 16px;
        font-weight: 600;
        font-family: var(--font-body);
    }

    .chatbot-header-info small {
        font-size: 12px;
        opacity: 0.8;
    }

    .chatbot-close {
        background: none;
        border: none;
        color: white;
        font-size: 24px;
        cursor: pointer;
        opacity: 0.7;
        transition: opacity 0.2s;
        padding: 0;
        line-height: 1;
    }

    .chatbot-close:hover {
        opacity: 1;
    }

    .chatbot-messages {
        flex: 1;
        padding: 20px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 16px;
        background-color: var(--cream);
    }

    .chat-bubble {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 16px;
        font-size: 14px;
        line-height: 1.5;
        position: relative;
        word-wrap: break-word;
    }

    .chat-bot {
        align-self: flex-start;
        background-color: #E8F0EB;
        color: var(--text-dark);
        border-bottom-left-radius: 4px;
    }

    .chat-user {
        align-self: flex-end;
        background-color: var(--forest);
        color: white;
        border-bottom-right-radius: 4px;
    }

    .typing-indicator {
        display: flex;
        gap: 4px;
        padding: 6px 4px;
    }

    .typing-dot {
        width: 6px;
        height: 6px;
        background-color: #888;
        border-radius: 50%;
        animation: typing 1.4s infinite ease-in-out both;
    }

    .typing-dot:nth-child(1) { animation-delay: -0.32s; }
    .typing-dot:nth-child(2) { animation-delay: -0.16s; }

    @keyframes typing {
        0%, 80%, 100% { transform: scale(0); }
        40% { transform: scale(1); }
    }

    .chatbot-input-area {
        padding: 16px;
        background: white;
        border-top: 1px solid rgba(26, 61, 43, 0.1);
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .chatbot-input {
        flex: 1;
        border: 1px solid rgba(26, 61, 43, 0.2);
        border-radius: 24px;
        padding: 10px 16px;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s;
        font-family: var(--font-body);
    }

    .chatbot-input:focus {
        border-color: var(--forest);
    }

    .chatbot-send {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: var(--forest);
        color: white;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background-color 0.2s;
        flex-shrink: 0;
    }

    .chatbot-send:hover {
        background-color: var(--forest-mid);
    }
    
    .chatbot-send:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }

    @media (max-width: 576px) {
        :root {
            --chat-width: calc(100vw - 32px);
        }
        #chatbot-widget {
            bottom: 16px;
            right: 16px;
        }
    }
    
    /* Format table for bot messages */
    .bot-table {
        width: 100%;
        margin-top: 8px;
        border-collapse: collapse;
        font-size: 13px;
    }
    .bot-table th, .bot-table td {
        border: 1px solid rgba(26, 61, 43, 0.2);
        padding: 6px 8px;
        text-align: left;
    }
    .bot-table th {
        background-color: rgba(26, 61, 43, 0.05);
        font-weight: 600;
    }
</style>

<div id="chatbot-widget">
    <div id="chatbot-window">
        <div class="chatbot-header">
            <div class="chatbot-header-title">
                <div class="chatbot-avatar">
                    <i class="bi bi-robot"></i>
                </div>
                <div class="chatbot-header-info">
                    <h5>E-Tourism Asisten</h5>
                    <small id="bot-status">Online</small>
                </div>
            </div>
            <button class="chatbot-close" id="chatbot-close-btn" aria-label="Tutup Chat">
                <i class="bi bi-x"></i>
            </button>
        </div>
        
        <div class="chatbot-messages" id="chatbot-messages">
            <!-- Messages will be added here dynamically -->
        </div>
        
        <form class="chatbot-input-area" id="chatbot-form">
            <input type="text" class="chatbot-input" id="chatbot-input" placeholder="Ketik pertanyaan Anda..." autocomplete="off">
            <button type="submit" class="chatbot-chatbot-send chatbot-send" id="chatbot-send-btn">
                <i class="bi bi-send-fill"></i>
            </button>
        </form>
    </div>

    <button id="chatbot-fab" aria-label="Buka Chatbot FAQ">
        <i class="bi bi-chat-dots-fill" id="chatbot-fab-icon"></i>
        <div class="chatbot-badge" id="chatbot-badge"></div>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fab = document.getElementById('chatbot-fab');
        const fabIcon = document.getElementById('chatbot-fab-icon');
        const badge = document.getElementById('chatbot-badge');
        const chatWindow = document.getElementById('chatbot-window');
        const closeBtn = document.getElementById('chatbot-close-btn');
        const messageContainer = document.getElementById('chatbot-messages');
        const chatForm = document.getElementById('chatbot-form');
        const inputField = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send-btn');
        
        let isOpen = false;
        let knowledgeBase = null;
        let isFirstOpen = true;
        
        // Detect language
        const lang = document.documentElement.lang || 'id';
        const isId = lang === 'id';
        
        // UI texts based on language
        const uiTexts = {
            greeting: isId 
                ? "Halo! Saya asisten virtual E-Tourism Kalsel. Ada yang bisa saya bantu tentang tiket, jadwal, atau informasi wisata?" 
                : "Hello! I am the E-Tourism Kalsel virtual assistant. How can I help you with tickets, schedules, or tourism information?",
            placeholder: isId ? "Ketik pertanyaan Anda..." : "Type your question...",
            fallback: isId 
                ? "Maaf, saya tidak memahami pertanyaan tersebut. Coba tanyakan tentang cara pesan tiket, harga tiket, jam buka wisata, atau metode pembayaran." 
                : "Sorry, I don't understand that question. Try asking about how to book tickets, ticket prices, opening hours, or payment methods.",
            loading: isId ? "Menyiapkan data..." : "Preparing data...",
            online: "Online",
            unavailable: isId ? "Tidak tersedia" : "Not available"
        };
        
        inputField.placeholder = uiTexts.placeholder;
        
        // Indonesian stopwords
        const stopWords = ['yang', 'di', 'ke', 'dari', 'untuk', 'pada', 'dan', 'atau', 'dengan', 'ini', 'itu', 'adalah', 'saya', 'kami', 'kamu', 'anda', 'dia', 'mereka', 'sebuah', 'suatu', 'tentang', 'apa', 'bagaimana', 'kenapa', 'mengapa', 'kapan', 'siapa', 'dimana', 'apakah', 'ada', 'bisa', 'tolong', 'min', 'halo', 'hai', 'pagi', 'siang', 'sore', 'malam'];
        
        // Preprocess text
        function preprocessText(text) {
            return text.toLowerCase()
                .replace(/[^\w\s]/gi, ' ')
                .split(/\s+/)
                .filter(word => word.length > 2 && !stopWords.includes(word));
        }
        
        // Load knowledge base
        async function loadKnowledgeBase() {
            try {
                const response = await fetch('/api/chatbot/knowledge');
                if (!response.ok) throw new Error('Network response was not ok');
                knowledgeBase = await response.json();
            } catch (error) {
                console.error('Error loading chatbot knowledge base:', error);
                // Fallback empty knowledge base
                knowledgeBase = { wisata: [], faq: [] };
            }
        }
        
        // Toggle chat window
        function toggleChat() {
            isOpen = !isOpen;
            if (isOpen) {
                chatWindow.classList.add('open');
                fabIcon.classList.remove('bi-chat-dots-fill');
                fabIcon.classList.add('bi-x');
                badge.style.display = 'none';
                
                if (isFirstOpen) {
                    isFirstOpen = false;
                    addMessage(uiTexts.loading, 'bot', true);
                    loadKnowledgeBase().then(() => {
                        removeTypingIndicator();
                        addMessage(uiTexts.greeting, 'bot');
                    });
                }
                
                setTimeout(() => inputField.focus(), 300);
            } else {
                chatWindow.classList.remove('open');
                fabIcon.classList.remove('bi-x');
                fabIcon.classList.add('bi-chat-dots-fill');
            }
        }
        
        fab.addEventListener('click', toggleChat);
        closeBtn.addEventListener('click', toggleChat);
        
        // Show unread badge if closed for a while (engagement feature)
        setTimeout(() => {
            if (!isOpen && isFirstOpen) {
                badge.style.display = 'block';
            }
        }, 15000);
        
        // Scroll to bottom of messages
        function scrollToBottom() {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
        
        // Add typing indicator
        function showTypingIndicator() {
            const div = document.createElement('div');
            div.className = 'chat-bubble chat-bot';
            div.id = 'typing-indicator';
            div.innerHTML = `
                <div class="typing-indicator">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            `;
            messageContainer.appendChild(div);
            scrollToBottom();
        }
        
        function removeTypingIndicator() {
            const el = document.getElementById('typing-indicator');
            if (el) el.remove();
        }
        
        // Format IDR
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }
        
        // Add message to chat
        function addMessage(text, sender, isTemp = false) {
            const div = document.createElement('div');
            div.className = `chat-bubble chat-${sender}`;
            if (isTemp) div.id = 'temp-loading';
            div.innerHTML = text;
            messageContainer.appendChild(div);
            scrollToBottom();
        }
        
        // Process user query
        function processQuery(query) {
            if (!knowledgeBase) {
                return addMessage(isId ? "Sistem sedang memuat data. Silakan coba lagi." : "System is loading data. Please try again.", 'bot');
            }
            
            const tokens = preprocessText(query);
            const rawQuery = query.toLowerCase();
            
            if (tokens.length === 0) {
                return addMessage(uiTexts.fallback, 'bot');
            }
            
            // 1. Check if asking about a specific wisata
            let targetWisata = null;
            let highestWisataScore = 0;
            
            // Kata umum yang tidak unik untuk satu tempat wisata
            const genericWords = ['pantai', 'bukit', 'gunung', 'danau', 'air', 'terjun', 'taman', 'pulau', 'goa', 'hutan', 'kampung', 'desa', 'wisata', 'monumen', 'museum', 'pasar'];
            
            for (const w of knowledgeBase.wisata) {
                const namaUtuh = w.nama.toLowerCase();
                
                // Jika user menyebut nama utuh persis (contoh: "bukit mamake")
                if (rawQuery.includes(namaUtuh)) {
                    targetWisata = w;
                    highestWisataScore = 999;
                    break;
                }
                
                const namaTokens = namaUtuh.split(/\s+/);
                let score = 0;
                let hasUniqueMatch = false;
                
                for (const nt of namaTokens) {
                    if (nt.length > 2 && rawQuery.includes(nt)) {
                        score += 1;
                        // Jika kata yang cocok BUKAN kata umum, berarti sangat spesifik
                        if (!genericWords.includes(nt)) {
                            hasUniqueMatch = true;
                            score += 5; // Bobot tinggi untuk kata unik (misal: "sapu", "angin", "mamake")
                        }
                    }
                }
                
                // Hanya set targetWisata jika ada kata unik yang cocok dan skornya paling tinggi
                if (hasUniqueMatch && score > highestWisataScore) {
                    highestWisataScore = score;
                    targetWisata = w;
                }
            }
            
            if (targetWisata) {
                // Determine intent about this wisata
                const isAskingJam = ['jam', 'buka', 'tutup', 'operasional', 'open', 'close', 'hours', 'waktu'].some(k => tokens.includes(k));
                const isAskingHarga = ['harga', 'tiket', 'biaya', 'tarif', 'price', 'cost', 'ticket', 'fee'].some(k => tokens.includes(k));
                const isAskingLokasi = ['lokasi', 'alamat', 'dimana', 'mana', 'address', 'location', 'where', 'rute', 'jalan', 'arah', 'route', 'direction'].some(k => tokens.includes(k));
                
                if (isAskingJam) {
                    return addMessage(`Jam operasional <strong>${targetWisata.nama}</strong>: <br>${targetWisata.jam}`, 'bot');
                }
                
                if (isAskingHarga) {
                    if (!targetWisata.harga || targetWisata.harga.length === 0) {
                        return addMessage(isId ? `Belum ada informasi harga tiket untuk <strong>${targetWisata.nama}</strong>.` : `No ticket price information for <strong>${targetWisata.nama}</strong>.`, 'bot');
                    }
                    
                    let tableHTML = `<div class="mb-1">Harga tiket <strong>${targetWisata.nama}</strong>:</div>`;
                    tableHTML += `<table class="bot-table"><tr><th>Jenis</th><th>Harga</th></tr>`;
                    targetWisata.harga.forEach(h => {
                        tableHTML += `<tr><td>${h.jenis}</td><td>${formatRupiah(h.harga)}</td></tr>`;
                    });
                    tableHTML += `</table>`;
                    
                    return addMessage(tableHTML, 'bot');
                }
                
                if (isAskingLokasi) {
                    let mapQuery = encodeURIComponent(targetWisata.nama + ' ' + targetWisata.kabupaten);
                    let routeHtml = `Alamat <strong>${targetWisata.nama}</strong>:<br>${targetWisata.alamat}, ${targetWisata.kabupaten}.<br><br>`;
                    routeHtml += `<a href="https://www.google.com/maps/search/?api=1&query=${mapQuery}" target="_blank" style="display:inline-block; background:#1A3D2B; color:white; padding:4px 10px; border-radius:12px; text-decoration:none; font-size:12px;">📍 Buka Rute di Google Maps</a>`;
                    return addMessage(routeHtml, 'bot');
                }
                
                // General info about the wisata if no specific intent
                return addMessage(`<strong>${targetWisata.nama}</strong> berada di ${targetWisata.kabupaten}.<br>Jam operasional: ${targetWisata.jam}.<br>Silakan lihat Katalog Wisata untuk detail lebih lengkap.`, 'bot');
            }
            
            // 2. Check general FAQ using keyword matching
            let bestMatch = null;
            let highestScore = 0;
            
            for (const faq of knowledgeBase.faq) {
                let score = 0;
                for (const token of tokens) {
                    if (faq.keywords.includes(token)) {
                        score += 1;
                    }
                }
                
                if (score > highestScore) {
                    highestScore = score;
                    bestMatch = faq;
                }
            }
            
            if (bestMatch && highestScore > 0) {
                const answer = isId ? bestMatch.answer_id : bestMatch.answer_en;
                return addMessage(answer, 'bot');
            }
            
            // 3. Fallback
            addMessage(uiTexts.fallback, 'bot');
        }
        
        // Handle form submission
        chatForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const message = inputField.value.trim();
            
            if (message === '') return;
            
            // Add user message
            addMessage(message, 'user');
            inputField.value = '';
            
            // Simulate processing delay
            showTypingIndicator();
            
            setTimeout(() => {
                removeTypingIndicator();
                processQuery(message);
            }, 600 + Math.random() * 400); // 600-1000ms delay
        });
    });
</script>
