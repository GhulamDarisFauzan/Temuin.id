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
    <div class="max-w-3xl mx-auto flex items-center gap-2">

        <input type="hidden" id="convId" value="{{ $conversation->id }}">

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

function handleEnter(e){
    if(e.key === 'Enter'){
        sendMessage();
    }
}

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

            chatBox.innerHTML += `
                <div class="flex ${align}">

                    <div class="max-w-[80%]">

                        <div class="text-[10px] text-gray-400 mb-1 px-1">
                            ${name}${label}
                        </div>

                        <div class="${bubble} px-4 py-2 rounded-2xl text-sm shadow-sm break-words">
                            ${msg.message}
                        </div>

                    </div>

                </div>
            `;
        });

        chatBox.scrollTop = chatBox.scrollHeight;
    });
}

function sendMessage() {
    let message = document.getElementById('messageInput').value;

    if (!message.trim()) return;

    fetch('/chat/send', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            conversation_id: convId,
            message: message
        })
    })
    .then(() => {
        document.getElementById('messageInput').value = '';
        loadMessages();
    });
}

setInterval(loadMessages, 2000);
loadMessages();
</script>

</body>
</html>