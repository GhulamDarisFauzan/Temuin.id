<!DOCTYPE html>
<html>
<head>
    <title>Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite('resources/css/app.css')
</head>

<body class="h-screen flex flex-col bg-gray-100">

<!-- HEADER -->
<div class="flex items-center justify-between px-4 py-3 bg-white shadow sticky top-0 z-50">

    <div>
        <h1 class="font-bold text-base">
            Temuin<span class="text-red-500">.id</span>
        </h1>
        <p class="text-xs text-gray-500 truncate max-w-[150px]">
            {{ $laporan->nama_barang }}
        </p>
    </div>

    <button onclick="history.back()"
        class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded-full text-xs">
        ← Back
    </button>

</div>

<!-- CHAT AREA -->
<div class="flex-1 flex flex-col max-w-3xl w-full mx-auto">

    <!-- MESSAGES -->
    <div id="chatBox"
        class="flex-1 overflow-y-auto px-3 py-4 space-y-3">

    </div>

</div>

<!-- INPUT -->
<div class="sticky bottom-0 bg-white border-t px-3 py-2">

    <!-- 🔥 REVISI REPLY CHAT -->
    <!-- Preview pesan yang sedang dibalas -->
    <div id="replyPreview" class="max-w-3xl mx-auto mb-2 hidden">
        <div class="bg-gray-100 border-l-4 border-blue-500 rounded-lg px-3 py-2 flex justify-between items-start gap-2">
            <div class="min-w-0">
                <p class="text-[10px] text-blue-600 font-semibold mb-1">
                    Membalas pesan
                </p>
                <p id="replyText" class="text-xs text-gray-600 truncate"></p>
            </div>

            <button type="button"
                onclick="cancelReply()"
                class="text-gray-400 hover:text-red-500 text-xs font-bold">
                ✕
            </button>
        </div>
    </div>
    <!-- 🔥 REVISI REPLY CHAT -->

    <div class="max-w-3xl mx-auto flex items-center gap-2">

        <input type="hidden" id="convId" value="{{ $conversation->id }}">

        <!-- 🔥 REVISI REPLY CHAT -->
        <!-- Menyimpan id pesan yang sedang dibalas -->
        <input type="hidden" id="replyTo" value="">
        <!-- 🔥 REVISI REPLY CHAT -->

        <input type="text" id="messageInput"
            class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-blue-400"
            placeholder="Ketik pesan..."
            onkeypress="handleEnter(event)">

        <button onclick="sendMessage()"
            class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-full text-sm shadow transition active:scale-95">
            ➤
        </button>

    </div>
</div>

<script>
let userId = "{{ auth()->id() }}";
let convId = "{{ $conversation->id }}"; 
let ownerId = "{{ $laporan->user_id }}";

// 🔥 REVISI REPLY CHAT
let currentReplyTo = null;
// 🔥 REVISI REPLY CHAT

function handleEnter(e){
    if(e.key === 'Enter'){
        sendMessage();
    }
}

// 🔥 REVISI REPLY CHAT
// Menghindari karakter aneh / HTML masuk ke tampilan chat
function escapeHtml(text) {
    if (!text) return '';

    return text
        .toString()
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
// 🔥 REVISI REPLY CHAT

// 🔥 REVISI REPLY CHAT
// Saat tombol Reply diklik
function setReply(id, message) {
    currentReplyTo = id;

    document.getElementById('replyTo').value = id;
    document.getElementById('replyText').innerText = message;
    document.getElementById('replyPreview').classList.remove('hidden');
    document.getElementById('messageInput').focus();
}
// 🔥 REVISI REPLY CHAT

// 🔥 REVISI REPLY CHAT
// Membatalkan reply
function cancelReply() {
    currentReplyTo = null;

    document.getElementById('replyTo').value = '';
    document.getElementById('replyText').innerText = '';
    document.getElementById('replyPreview').classList.add('hidden');
}
// 🔥 REVISI REPLY CHAT

function loadMessages() {
    fetch('/chat/messages/' + convId)
    .then(res => res.json())
    .then(data => {

        let chatBox = document.getElementById('chatBox');
        chatBox.innerHTML = '';

        data.forEach(msg => {

            let isMe = msg.sender_id == userId;
            let align = isMe ? 'justify-end' : 'justify-start';

            let bubble = isMe 
                ? 'bg-blue-500 text-white rounded-br-none' 
                : 'bg-white text-gray-800 rounded-bl-none border';

            let name = msg.user?.name ?? 'User';

            let label = '';
            if (msg.sender_id == ownerId) {
                label = ' • Pemilik';
            } else if (msg.user?.role == 'admin') {
                label = ' • Admin';
            } else {
                label = '';
            }

            // 🔥 REVISI REPLY CHAT
            let replyBox = '';
            if (msg.reply) {
                let replyName = msg.reply.user?.name ?? 'User';
                let replyMessage = escapeHtml(msg.reply.message ?? '');

                replyBox = `
                    <div class="mb-2 px-3 py-2 rounded-lg text-xs border-l-4
                        ${isMe ? 'bg-blue-400 border-white/70 text-white/90' : 'bg-gray-100 border-blue-400 text-gray-600'}">
                        <div class="font-semibold text-[10px] mb-1">
                            Membalas ${escapeHtml(replyName)}
                        </div>
                        <div class="truncate">
                            ${replyMessage}
                        </div>
                    </div>
                `;
            }
            // 🔥 REVISI REPLY CHAT

            chatBox.innerHTML += `
                <div class="flex ${align}">

                    <div class="max-w-[80%]">

                        <div class="text-[10px] text-gray-400 mb-1 px-1">
                            ${escapeHtml(name)}${escapeHtml(label)}
                        </div>

                        <div class="${bubble} px-4 py-2 rounded-2xl text-sm shadow-sm break-words">

                            <!-- 🔥 REVISI REPLY CHAT -->
                            ${replyBox}
                            <!-- 🔥 REVISI REPLY CHAT -->

                            <div>
                                ${escapeHtml(msg.message)}
                            </div>
                        </div>

                        <!-- 🔥 REVISI REPLY CHAT -->
                        <div class="${isMe ? 'text-right' : 'text-left'} mt-1 px-1">
                            <button type="button"
                                onclick="setReply(${msg.id}, '${escapeHtml(msg.message).replace(/'/g, "\\'")}')"
                                class="text-[10px] text-gray-400 hover:text-blue-500">
                                Reply
                            </button>
                        </div>
                        <!-- 🔥 REVISI REPLY CHAT -->

                    </div>

                </div>
            `;
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

function sendMessage() {
    let message = document.getElementById('messageInput').value;

    // 🔥 REVISI REPLY CHAT
    let replyTo = document.getElementById('replyTo').value;
    // 🔥 REVISI REPLY CHAT

    if (!message.trim()) return;

    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            conversation_id: convId,
            message: message,

            // 🔥 REVISI REPLY CHAT
            reply_to: replyTo || null
            // 🔥 REVISI REPLY CHAT
        })
    })
    .then(() => {
        document.getElementById('messageInput').value = '';

        // 🔥 REVISI REPLY CHAT
        cancelReply();
        // 🔥 REVISI REPLY CHAT

        loadMessages();
    });
}

setInterval(loadMessages, 2000);
loadMessages();
</script>

</body>
</html>