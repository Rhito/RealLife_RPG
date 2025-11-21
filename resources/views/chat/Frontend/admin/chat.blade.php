@extends(config('core.admin_theme').'.template')

@section('main')
    <style>
        .file-upload-icon {
            display: inline-block;
            cursor: pointer;
            font-size: 28px;
            color: #007bff;
            transition: color 0.3s;
            margin-right: 12px;
            margin-top: 8px;
        }
        .file-upload-icon:hover {
            color: #0056b3;
        }
    </style>

    <div class="row" style="height:80vh;">
        <!-- Sidebar -->
        <div class="col-md-3 border-right d-flex flex-column" style="overflow-y:auto;">
            <h5 class="p-2 text-center">{{__('lang.nguoi_dung')}}</h5>
            <ul class="list-group" id="userList">
                @foreach($users as $u)
                    <button type="button"
                            class="list-group-item list-group-item-action d-flex justify-content-between align-items-center user-item {{ $selectedId == $u->id ? 'active' : '' }}"
                            data-id="{{ $u->id }}"
                            data-name="{{ $u->name ?? 'Người dùng' }}"
                            onclick="selectUserBtn(this)">
                        <div class="text-left">
                            <strong>{{ $u->name ?? 'No name' }}</strong><br>
                            <small>{{ $u->email ?? '' }}</small>
                        </div>
                        @if((int)$u->unread_count > 0)
                            <span class="badge badge-primary badge-pill">{{ (int)$u->unread_count }}</span>
                        @endif
                    </button>
                @endforeach
            </ul>
        </div>

        <!-- Chat box -->
        <div class="col-md-9 d-flex flex-column" style="height:80vh;">
            <!-- Header -->
            <div class="p-2 border-bottom d-flex align-items-center">
                <strong id="chatTitle">{{ $selectedId ? $selectedName : 'Chọn người để chat' }}</strong>
            </div>

            <!-- Khung tin nhắn -->
            <div id="chatMessages"
                 class="flex-grow-1 p-3"
                 style="overflow-y:auto; background:#f5f5f5; min-height:0; max-height: calc(80vh - 100px);">
                @foreach($messages as $m)
                    @php $isMain = ($m['sender_id'] == $mainAdminId); @endphp
                    <div class="{{ $isMain ? 'text-right' : 'text-left' }}">
                        <div class="p-2 mb-1 d-inline-block"
                             style="background:{{ $isMain ? '#007bff' : '#e5e5ea' }};
                            color:{{ $isMain ? '#fff' : '#000' }};
                            border-radius:8px;">
                            {!! e($m['content']) !!}
                            @if($m['file_url'])
                                <br><a href="{{ $m['file_url'] }}" target="_blank">📎 file</a>
                            @endif
                            <div><small>{{ $m['created_at'] }}</small></div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Form nhập tin -->
            <div class="p-2 border-top d-flex align-items-center" style="flex-shrink:0;">
                <!-- Input file ẩn đi -->
                <input type="file" id="fileInput" style="display: none;" />

                <!-- Label để giả lập nút upload -->
                <label for="fileInput" class="file-upload-icon">
                    <i class="fas fa-file-upload"></i> <!-- icon đẹp -->
                </label>
                <input type="text" id="messageInput" class="form-control" placeholder="{{ __('lang.nhap_tin_nhan')}}">
                <button id="sendBtn" class="btn btn-primary ml-2">{{__('lang.gui')}}</button>
            </div>
        </div>




        <script>
        let currentUserId = {{ $selectedId ?? 'null' }};
        let mainAdminId   = {{ $mainAdminId }};
        let pollInterval  = null;

        function selectUserBtn(el) {
            let userId   = el.getAttribute('data-id');
            let userName = el.getAttribute('data-name');

            currentUserId = userId;

            // Active UI
            document.querySelectorAll('.user-item').forEach(btn => btn.classList.remove('active'));
            el.classList.add('active');

            // đổi tiêu đề
            document.getElementById('chatTitle').innerText = userName;

            // load tin nhắn ngay
            loadMessages(userId);

            // bắt đầu polling
            startPolling(userId);

            // reset badge khi click user
            let badge = el.querySelector('.badge');
            if (badge) badge.remove();

            // gọi API đánh dấu đã đọc
            fetch(`/admin/chat/api/messages/${userId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });
        }

        function loadMessages(userId) {
            let chatArea = document.getElementById('chatMessages');
            if (!chatArea) return;

            fetch(`/admin/chat/api/messages/${userId}`)
                .then(res => res.json())
                .then(messages => {
                    renderMessages(messages);
                })
                .catch(err => console.error("Load error:", err));
        }

        function renderMessages(messages) {
            let chatArea = document.getElementById('chatMessages');
            chatArea.innerHTML = '';
            messages.forEach(m => {
                let isAdmin = (m.sender_id == mainAdminId);
                let div = document.createElement('div');
                div.className = isAdmin ? 'text-right' : 'text-left';

                div.innerHTML = `
                <div class="p-2 mb-1 d-inline-block"
                     style="background:${isAdmin ? '#007bff' : '#e5e5ea'};color:${isAdmin ? '#fff' : '#000'};border-radius:8px;">
                    ${m.content ?? ''}
                    ${m.file_url ? `<br><a href="${m.file_url}" target="_blank">📎 file</a>` : ''}
                    <div><small>${m.created_at}</small></div>
                </div>
            `;
                chatArea.appendChild(div);
            });
            chatArea.scrollTop = chatArea.scrollHeight;
        }

        function startPolling(userId) {
            if (pollInterval) clearInterval(pollInterval);

            pollInterval = setInterval(() => {
                if (currentUserId) {
                    fetch(`/admin/chat/api/messages/${currentUserId}`)
                        .then(res => res.json())
                        .then(messages => {
                            renderMessages(messages);
                        })
                        .catch(err => console.error("Poll error:", err));
                }

            }, 3000); // 3s
            // danh sách user thì luôn chạy riêng
            setInterval(updateUserList, 3000);
        }

        function updateUserList() {
            fetch(`/admin/chat/api/users/list`)
                .then(res => res.json())
                .then(users => {
                    let userList = document.getElementById('userList');
                    if (!userList) return;

                    userList.innerHTML = '';

                    users.forEach(u => {
                        let btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center user-item';
                        btn.setAttribute('data-id', u.id);
                        btn.setAttribute('data-name', u.name ?? 'Người dùng');
                        btn.onclick = function () { selectUserBtn(this); };

                        btn.innerHTML = `
                    <div class="text-left">
                        <strong>${u.name ?? 'No name'}</strong><br>
                        <small>${u.email ?? ''}</small>
                    </div>
                    ${u.unread_count > 0 ? `<span class="badge badge-primary badge-pill">${u.unread_count}</span>` : ''}
                `;

                        userList.appendChild(btn);
                    });
                })
                .catch(err => console.error("Update user list error:", err));
        }



        // ✅ khi load trang lần đầu, nếu đã có user thì auto polling luôn
        @if($selectedId)
        document.addEventListener("DOMContentLoaded", function() {
            loadMessages({{ $selectedId }});
            startPolling({{ $selectedId }});
        });
        @endif
    </script>



{{--    gửi tin nhắn--}}
    <script>
        document.getElementById('sendBtn').addEventListener('click', function () {
            if (!currentUserId) {
                alert('Vui lòng chọn người để chat');
                return;
            }

            let content = document.getElementById('messageInput').value.trim();
            let file    = document.getElementById('fileInput').files[0];

            if (!content && !file) {
                alert('Vui lòng nhập tin nhắn hoặc chọn file');
                return;
            }

            let formData = new FormData();
            formData.append('content', content);
            if (file) formData.append('file', file);

            fetch(`/admin/chat/api/messages/${currentUserId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(res => res.json())
                .then(m => {
                    // render ngay tin nhắn vừa gửi vào chat box
                    let chatArea = document.getElementById('chatMessages');
                    let div = document.createElement('div');
                    div.className = 'text-right';
                    div.innerHTML = `
                <div class="p-2 mb-1 d-inline-block"
                     style="background:#007bff;color:#fff;border-radius:8px;">
                    ${m.content ?? ''}
                    ${m.file_url ? `<br><a href="${m.file_url}" target="_blank">📎 file</a>` : ''}
                    <div><small>${m.created_at}</small></div>
                </div>
            `;
                    chatArea.appendChild(div);
                    chatArea.scrollTop = chatArea.scrollHeight;

                    // reset input
                    document.getElementById('messageInput').value = '';
                    document.getElementById('fileInput').value = '';
                })
                .catch(err => {
                    alert('Gửi tin nhắn thất bại');
                    console.error(err);
                });
        });

        document.getElementById('messageInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('sendBtn').click();
            }
        });
    </script>

@endsection
