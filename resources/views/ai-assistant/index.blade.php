@extends('layouts.app')

@section('title', 'Asistente IA - Panadería')

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-2xl font-semibold text-white">🤖 Asistente Inteligente</h1>
        <p class="text-sm text-gray-400">Pregunta sobre tu negocio y obtén respuestas en tiempo real</p>
    </div>

    <!-- Chat -->
    <div class="bg-[#111827] border border-gray-800 rounded-2xl overflow-hidden">
        <!-- Header del chat -->
        <div class="p-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white">💬 Chat con el Asistente</h3>
                <p class="text-sm text-gray-400">Haz una pregunta sobre tu negocio</p>
            </div>
            <button onclick="clearChat()" class="text-sm text-red-400 hover:text-red-300 transition">
                🗑️ Limpiar chat
            </button>
        </div>

        <!-- Contenedor de mensajes -->
        <div id="chatMessages" class="h-96 overflow-y-auto p-4 space-y-4 bg-[#0B1220]">
            <!-- Mensaje de bienvenida -->
            <div class="flex justify-start">
                <div class="bg-[#111827] border border-gray-800 rounded-2xl px-4 py-3 max-w-2xl">
                    <div class="flex items-start gap-3">
                        <div class="text-2xl">🤖</div>
                        <div>
                            <p class="text-sm font-semibold text-white">Asistente IA</p>
                            <p class="text-sm text-white mt-1">¡Hola! Soy tu asistente inteligente.</p>
                            <p class="text-sm text-white mt-2">¿Qué necesitas saber?</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input del chat -->
        <div class="p-4 border-t border-gray-800 bg-[#0B1220]">
            <form id="chatForm" class="flex gap-2">
                @csrf
                <input 
                    type="text" 
                    id="questionInput"
                    name="question" 
                    placeholder="Escribe tu pregunta aquí..." 
                    class="flex-1 bg-[#111827] border border-gray-800 text-white rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-indigo-500"
                    required
                    autocomplete="off"
                >
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-2 rounded-lg text-sm font-semibold transition"
                    id="sendButton"
                >
                    Enviar
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Chat inicializado correctamente');
    
    const form = document.getElementById('chatForm');
    const input = document.getElementById('questionInput');
    const messages = document.getElementById('chatMessages');
    const sendButton = document.getElementById('sendButton');

    if (!form || !input || !messages || !sendButton) {
        console.error('❌ Faltan elementos en el DOM');
        return;
    }

    function addMessage(message, isUser = false, isError = false) {
        const div = document.createElement('div');
        div.className = `flex ${isUser ? 'justify-end' : 'justify-start'} animate-fadeIn`;
        
        const bubble = document.createElement('div');
        
        if (isUser) {
            bubble.className = 'bg-indigo-600 text-white rounded-2xl px-4 py-2 max-w-2xl';
            bubble.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-indigo-200">Tú</p>
                        <p class="text-sm text-white">${escapeHtml(message)}</p>
                    </div>
                    <div class="text-xl flex-shrink-0">👤</div>
                </div>
            `;
        } else if (isError) {
            bubble.className = 'bg-red-900/30 border border-red-700 rounded-2xl px-4 py-2 max-w-2xl';
            bubble.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="text-2xl flex-shrink-0">❌</div>
                    <div>
                        <p class="text-sm font-semibold text-red-400">Error</p>
                        <p class="text-sm text-red-300">${escapeHtml(message)}</p>
                    </div>
                </div>
            `;
        } else {
            bubble.className = 'bg-[#111827] border border-gray-800 rounded-2xl px-4 py-2 max-w-2xl';
            bubble.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="text-2xl flex-shrink-0">🤖</div>
                    <div>
                        <p class="text-sm font-semibold text-white">Asistente IA</p>
                        <div class="text-sm text-white mt-1">${message}</div>
                        <!-- 👆 AHORA ES text-white (blanco fuerte) -->
                    </div>
                </div>
            `;
        }
        
        div.appendChild(bubble);
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function showTypingIndicator() {
        const div = document.createElement('div');
        div.id = 'typingIndicator';
        div.className = 'flex justify-start animate-fadeIn';
        div.innerHTML = `
            <div class="bg-[#111827] border border-gray-800 rounded-2xl px-6 py-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-400">🤖 Escribiendo</span>
                    <span class="typing-dots">
                        <span>.</span><span>.</span><span>.</span>
                    </span>
                </div>
            </div>
        `;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    function removeTypingIndicator() {
        const indicator = document.getElementById('typingIndicator');
        if (indicator) indicator.remove();
    }

    function formatResponse(response) {
        response = response.replace(/\*\*(.*?)\*\*/g, '<strong class="text-indigo-400">$1</strong>');
        response = response.replace(/\*(.*?)\*/g, '<em>$1</em>');
        response = response.replace(/\n/g, '<br>');
        return response;
    }

    function sendMessage() {
        const question = input.value.trim();
        if (!question) return;

        input.disabled = true;
        sendButton.disabled = true;
        sendButton.textContent = '⏳ Enviando...';

        addMessage(question, true);
        input.value = '';
        showTypingIndicator();

        const token = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch('{{ route("ai-assistant.ask") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                question: question,
                format: 'html'
            })
        })
        .then(response => response.json())
        .then(data => {
            removeTypingIndicator();
            
            if (data.success) {
                const response = data.data.response || 'No se recibió respuesta.';
                const formattedResponse = formatResponse(response);
                addMessage(formattedResponse);
            } else {
                const errorMsg = data.error || 'Error al procesar la pregunta.';
                addMessage(errorMsg, false, true);
            }
        })
        .catch(error => {
            removeTypingIndicator();
            addMessage('Error de conexión: ' + error.message, false, true);
        })
        .finally(() => {
            input.disabled = false;
            sendButton.disabled = false;
            sendButton.textContent = 'Enviar';
            input.focus();
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    sendButton.addEventListener('click', function(e) {
        e.preventDefault();
        sendMessage();
    });

    document.querySelectorAll('.quick-question').forEach(button => {
        button.addEventListener('click', function() {
            input.value = this.textContent.trim();
            sendMessage();
        });
    });

    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    window.clearChat = function() {
        if (confirm('¿Estás seguro de que quieres limpiar el chat?')) {
            const welcomeMessage = messages.querySelector('.flex.justify-start');
            messages.innerHTML = '';
            if (welcomeMessage) {
                messages.appendChild(welcomeMessage);
            }
        }
    };

    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }
        .typing-dots span {
            animation: typingDot 1.4s infinite;
            display: inline-block;
        }
        .typing-dots span:nth-child(2) {
            animation-delay: 0.2s;
        }
        .typing-dots span:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes typingDot {
            0%, 20% { opacity: 0; }
            40% { opacity: 1; }
            100% { opacity: 1; }
        }
        #chatMessages {
            scroll-behavior: smooth;
        }
        #chatMessages::-webkit-scrollbar {
            width: 6px;
        }
        #chatMessages::-webkit-scrollbar-track {
            background: #0B1220;
        }
        #chatMessages::-webkit-scrollbar-thumb {
            background: #1F2933;
            border-radius: 3px;
        }
        #chatMessages::-webkit-scrollbar-thumb:hover {
            background: #2D3748;
        }
    `;
    document.head.appendChild(style);
});
</script>
@endsection