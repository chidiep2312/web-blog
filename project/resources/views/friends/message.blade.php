@extends('layout.blog')

@section('content')

<div class="main-panel">
    <div class="content-wrapper">
        <div class="container">
            <h3 class="text-center">
                @if($receiver)
                You're texting to {{ $receiver->name }}
                @else
                Receiver not found!
                @endif
            </h3>

            <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll;">
                @if(!empty($messages) && $messages->count() > 0)
                @foreach($messages as $message)
                @if($message->sender_id ==$sender->id)

                <div style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                    <div style="max-width: 60%; background:#e0f7fa; padding: 10px; border-radius: 10px; text-align: right;">
                        <strong>{{ $message->sender->name }}</strong>
                        <p style="margin: 0;">{{ $message->content }}</p>
                        <small style="color: #aaa;">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <img src="{{ asset('storage/' . $message->sender->avatar) }}"
                        alt="Avatar"
                        style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                </div>
                @else

                <div style="margin-bottom: 15px; display: flex; justify-content: flex-start;">
                    <img src="{{ asset('storage/' . $message->sender->avatar) }}"
                        alt="Avatar"
                        style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                    <div style="max-width: 60%; background:#fff3e0; padding: 10px; border-radius: 10px;">
                        <strong>{{ $message->sender->name }}</strong>
                        <p style="margin: 0;">{{ $message->content }}</p>
                        <small style="color: #aaa;">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                </div>
                @endif
                @endforeach
                @else
                <p class="text-center text-muted">No messages yet!</p>
                @endif
            </div>

            <!-- Form gửi tin nhắn -->
            <form id="chat" style="margin-top: 20px;">
                @csrf
                <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
                <textarea id="message" name="message" rows="3" style="width: 100%;" placeholder="Write your message..." required></textarea>
                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Send</button>
            </form>
        </div>
    </div>
    <!-- content-wrapper ends -->

    @include('components.footer')
</div>
<script>
    document.getElementById('chat').addEventListener('submit', function(e) {
        e.preventDefault();
        const senderId = localStorage.getItem('user_id');
        let authToken = localStorage.getItem('auth_token');
        const receiverId = document.getElementById('receiver_id').value;
        const content = document.getElementById('message').value;
        fetch('/api/send-message/' + senderId + '/' + receiverId, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    "Content-Type": "application/json",
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify({
                    content: content
                })
            }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    const chatBox = document.getElementById('chat-box');
                    const avatarUrl = `/storage/${data.sender.avatar}`;

                    const messHTML = ` <div style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                                <div style="max-width: 60%; background:#e0f7fa; padding: 10px; border-radius: 10px; text-align: right;">
                                    <strong>${ data.sender.name}</strong>
                                    <p style="margin: 0;">${ data.message.content }</p>
                                    <small style="color: #aaa;">${new Date(data.message.created_at).toLocaleString()}</small>
                                </div>
                                <img src="${avatarUrl}" 
                                     alt="Avatar" 
                                     style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                            </div>`;
                    chatBox.innerHTML += messHTML;
                    chatBox.scrollTop = chatBox.scrollHeight;
                    document.getElementById('message').value = '';
                } else {
                    alert(data.message);
                }
            }).catch(error => console.error("Error:", error));
    });
</script>
@endsection