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
            <h5 class="p-2 text-center">{{ __('lang.Buyer.by.invoice') }}</h5>
            <ul class="list-group" id="userList">
                @if($users->count() > 0)
                    @foreach($users as $u)
                        <button type="button"
                                class="list-group-item list-group-item-action d-flex justify-content-between align-items-center user-item"
                                data-id="{{ $u->id }}"
                                data-user-id="{{ $u->id }}"
                                data-name="{{ $u->name }}"
                                data-bill="{{ $u->bill_id }}"
                                onclick="selectUserBtn(this)">
                            <div class="text-left">
                                <strong>{{ $u->name }}</strong><br>
                                <small>ĐH: {{ $u->ma_don_hang }}</small><br>
                                <small>SP: {{ $u->product_name }}</small>
                            </div>
                            @if((int)$u->unread_count > 0)
                                <span class="badge badge-primary badge-pill unread-badge"
                                      data-bill="{{ $u->bill_id }}">
                                      {{ (int)$u->unread_count }}
                                </span>
                            @endif
                        </button>
                    @endforeach
                @else
                    <li class="list-group-item text-muted">{{__('lang.No.visitors.have.messaged.yet')}}</li>
                @endif
            </ul>
        </div>

        <!-- Chat box -->
        <div class="col-md-9 d-flex flex-column" style="height:80vh;">
            <div class="p-2 border-bottom d-flex align-items-center">
                <strong id="chatTitle">{{ $selectedName ?? __('lang.Choose.someone.to.chat.with') }}</strong>
            </div>

            <div id="chatMessages"
                 class="flex-grow-1 p-3"
                 style="overflow-y:auto; background:#f5f5f5; min-height:0; max-height: calc(80vh - 100px);">
                <p class="text-muted">{{__('lang.No.messages.yet')}}...</p>
            </div>

            <!-- Form nhập -->
            <div class="p-2 border-top d-flex align-items-center" style="flex-shrink:0;">
                <!-- Input file ẩn đi -->
                <input type="file" id="fileInput" style="display: none;" />

                <!-- Label để giả lập nút upload -->
                <label for="fileInput" class="file-upload-icon">
                    <i class="fas fa-file-upload"></i> <!-- icon đẹp -->
                </label>
                <input type="text" id="messageInput" class="form-control"
                       placeholder="{{ __('lang.Enter.a.message') }}">
                <button id="sendBtn" class="btn btn-primary ml-2">{{__('lang.send')}}</button>
            </div>
        </div>
    </div>
@endsection
<script>
    let currentBillId = null;
    let lastMessageId = 0;
    let pollingInterval = null;
    let shopId = {{ Auth::guard('admin')->id() }};
</script>

<script>
    // Render 1 tin nhắn ra khung chat
    function renderMessage(m) {
        const chatBox = document.getElementById('chatMessages');
        const wrapper = document.createElement('div');
        wrapper.classList.add('mb-3', 'd-flex', m.sender_id == shopId ? 'justify-content-end' : 'justify-content-start');

        wrapper.innerHTML = `
            <div>
                <div style="
                    background:${m.sender_id == shopId ? '#007bff' : '#e5e5ea'};
                    color:${m.sender_id == shopId ? '#fff' : '#000'};
                    padding:10px 14px;
                    border-radius:12px;
                    max-width:350px;
                    word-wrap:break-word;
                    font-size:14px;
                    line-height:1.4;
                ">
                    ${m.content ? m.content : ''}
                    ${m.file_url ? `<br><a href="${m.file_url}" target="_blank">📎 File</a>` : ''}
                </div>
                <div style="font-size:11px; color:#666; margin-top:4px; ${m.sender_id == shopId ? 'text-align:right' : 'text-align:left'}">
                    ⏰ ${m.time}
                </div>
            </div>
        `;
        chatBox.appendChild(wrapper);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // Cập nhật badge tin chưa đọc trong sidebar
    function refreshSidebarUsers() {
        fetch("{{ route('admin.chat.usersSidebar') }}")
            .then(res => res.json())
            .then(data => {
                data.users.forEach(u => {
                    const btn = document.querySelector(`.user-item[data-bill='${u.bill_id}']`);
                    if (!btn) return;

                    let badge = btn.querySelector('.unread-badge');
                    if (badge) badge.remove();

                    if (u.unread_count > 0) {
                        let span = document.createElement('span');
                        span.classList.add('badge', 'badge-primary', 'badge-pill', 'unread-badge');
                        span.innerText = u.unread_count;
                        btn.appendChild(span);
                    }
                });
            })
            .catch(err => console.error("Lỗi refresh sidebar:", err));
    }
    setInterval(refreshSidebarUsers, 5000);

    // Polling lấy tin nhắn mới
    function startPollingMessages() {
        if (pollingInterval) clearInterval(pollingInterval);

        pollingInterval = setInterval(() => {
            if (!currentBillId) return;

            fetch(`/admin/chat/messages/${currentBillId}?last_id=${lastMessageId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.messages && data.messages.length > 0) {
                        data.messages.forEach(m => {
                            renderMessage(m);
                            lastMessageId = m.id;
                        });
                    }
                })
                .catch(err => console.error("Lỗi polling:", err));
        }, 3000);
    }

    // Khi chọn 1 bill để chat
    function selectUserBtn(el) {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
        }

        document.querySelectorAll('.user-item').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');

        const billId   = el.getAttribute('data-bill');
        const userId   = el.getAttribute('data-id');
        const userName = el.getAttribute('data-name');
        const chatBox  = document.getElementById('chatMessages');

        currentBillId = billId;
        lastMessageId = 0;

        const badge = el.querySelector('.badge');
        if (badge) badge.remove();

        document.getElementById('chatTitle').textContent = userName;
        chatBox.innerHTML = '<p class="text-muted">{{__('lang.No.messages.yet')}}...</p>';

        fetch("{{ url('admin/chat/messages') }}/" + billId)
            .then(res => res.json())
            .then(data => {
                chatBox.innerHTML = '';
                if (data.messages && data.messages.length > 0) {
                    data.messages.forEach(m => {
                        renderMessage(m);
                        lastMessageId = m.id;
                    });
                } else {
                    chatBox.innerHTML = '<p class="text-muted text-center">{{__('lang.dang_tai_tin_nhan')}}...</p>';
                }
                startPollingMessages();
            });

        fetch(`/admin/chat/mark-read/${billId}/${userId}/${shopId}`, {
            method: "POST",
            headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
        }).then(res => res.json()).then(data => {
            if (data.status === 'ok') {
                refreshSidebarUsers();
            }
        });
    }

    // Gửi tin nhắn từ admin
    document.addEventListener("DOMContentLoaded", function () {
        function sendShopMessage(billId) {
            const input = document.getElementById('messageInput');
            const file = document.getElementById('fileInput').files[0];
            const content = input.value.trim();
            if (!content && !file) {
                alert("Vui lòng nhập tin nhắn hoặc chọn file");
                return;
            }

            const formData = new FormData();
            formData.append('bill_id', billId);
            formData.append('content', input.value);
            if (file) formData.append('file', file);

            fetch("{{ route('admin.chat.send') }}", {
                method: "POST",
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'ok') {
                        input.value = "";
                        document.getElementById('fileInput').value = "";
                        renderMessage(data.message);
                        lastMessageId = data.message.id;
                    } else {
                        alert(data.error || 'Gửi tin nhắn thất bại');
                    }
                })
                .catch(err => {
                    console.error("Lỗi fetch:", err);
                    alert("Có lỗi khi gửi tin nhắn");
                });
        }

        const btn = document.getElementById('sendBtn');
        if (btn) {
            btn.addEventListener('click', function () {
                if (!currentBillId) {
                    alert("Chưa chọn bill để chat!");
                    return;
                }
                sendShopMessage(currentBillId);
            });
        }

        const input = document.getElementById('messageInput');
        if (input) {
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    if (!currentBillId) {
                        alert("Chưa chọn bill để chat!");
                        return;
                    }
                    sendShopMessage(currentBillId);
                }
            });
        }
    });

    // Load lại danh sách sidebar (khi cần)
    function loadSidebarUsers() {
        fetch("{{ route('admin.chat.usersSidebar') }}")
            .then(res => res.json())
            .then(data => {
                const list = document.getElementById('userList');
                list.innerHTML = '';

                if (data.users.length > 0) {
                    data.users.forEach(u => {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'list-group-item list-group-item-action d-flex justify-content-between align-items-center user-item';
                        btn.setAttribute('data-id', u.id);
                        btn.setAttribute('data-user-id', u.id);
                        btn.setAttribute('data-name', u.name);
                        btn.setAttribute('data-bill', u.bill_id);
                        btn.onclick = function () { selectUserBtn(btn); };

                        btn.innerHTML = `
                            <div class="text-left">
                                <strong>${u.name}</strong><br>
                                <small>ĐH: ${u.ma_don_hang}</small><br>
                                <small>SP: ${u.product_name}</small>
                            </div>
                            ${u.unread_count > 0 ? `<span class="badge badge-primary badge-pill">${u.unread_count}</span>` : ''}
                        `;
                        list.appendChild(btn);
                    });
                } else {
                    list.innerHTML = '<li class="list-group-item text-muted">Chưa có khách nào nhắn tin</li>';
                }
            })
            .catch(err => console.error("Lỗi tải danh sách sidebar:", err));
    }
</script>
