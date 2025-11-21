<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat Widget</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .chatWidget {
            position: fixed;
            bottom: 20px;
            /*bottom: 300px;*/

            right: 20px;
            width: 350px;
            height: 500px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 9999;
            max-width: 100%;
            max-height: 100%;
        }

        .headerDet {
            height: 50px;
            background: #fff;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #ddd;
            padding: 0 10px;
            justify-content: space-between;
        }

        .headerDet .img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            overflow: hidden;
        }

        .headerDet .img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .headerDet .nameC {
            margin-left: 10px;
            font-weight: bold;
            font-size: 15px;
        }

        .chatArea {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
            background: #f5f5f5;
        }

        .message {
            display: flex;
            margin-bottom: 10px;
        }

        .message.sent {
            justify-content: flex-end;
        }

        .message.received {
            justify-content: flex-start;
        }

        .messArea {
            max-width: 70%;
            background: #007bff;
            color: white;
            padding: 8px 10px;
            border-radius: 10px 10px 0 10px;
            font-size: 14px;
            word-wrap: break-word;
        }

        .messArea.received {
            background: #e5e5ea;
            color: black;
            border-radius: 10px 10px 10px 0;
        }

        .timeId {
            font-size: 12px;
            color: #aaa;
            text-align: center;
            margin: 3px 0 10px;
            width: 100%;
            display: block;
        }

        .messageBox {
            display: flex;
            align-items: center;
            padding: 8px;
            border-top: 1px solid #ddd;
            background: #fff;
        }

        #message {
            flex: 1;
            padding: 8px 10px;
            border-radius: 20px;
            border: 1px solid #ccc;
            outline: none;
            font-size: 14px;
            margin: 0 5px;
        }

        .button-s1 {
            background: transparent;
            border: none;
            cursor: pointer;
            color: #ffffff;
        }

        .material-icons {
            font-size: 20px;
            color: black;
        }

        .material-icons.download, #openChatButton span {
            color: #ffffff;
        }

        #fileLabel {
            cursor: pointer;
        }

        #fileName {
            font-size: 11px;
            margin-left: 5px;
            color: #555;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 90px;
        }

        #fileNameWrapper {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        #openChatButton {
            position: fixed;
            bottom: 267px;
            right: 20px;
            background-color: #1b83ff;
            color: white;
            padding: 14px 14px 10px 14px;;
            border-radius: 50%;
            cursor: pointer;
            z-index: 10000;
            border: none;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(27, 131, 255, 0.7);
            }
            70% {
                transform: scale(1.1);
                box-shadow: 0 0 20px rgba(27, 131, 255, 0.7);
            }
            100% {
                transform: scale(1);
                box-shadow: 0 0 0 rgba(27, 131, 255, 0);
            }
        }

        .button-s1:hover span {
            color: #1b83ff;
            transition: color 0.2s ease;
        }

        #closeChatButton:hover, #clearFile:hover {
            color: #ff3b3b;
            transition: color 0.2s ease;
        }

        #openChatButton:hover {
            background-color: #006fe6;
            transition: background-color 0.3s ease;
        }

        #closeChatButton {
            background-color: transparent;
            color: black;
            padding: 0;
            border: none;
            font-size: 20px;
            cursor: pointer;
        }

        #clearFile {
            background: none;
            border: none;
            color: #999;
            margin-left: 5px;
            cursor: pointer;
            display: none;
        }

        @media (max-width: 500px) {
            .chatWidget {
                width: 100%;
                height: 100%;
                bottom: 0;
                right: 0;
                border-radius: 0;
            }

            .messageBox {
                padding: 5px;
            }

            #message {
                font-size: 12px;
                padding: 6px 8px;
            }

            .button-s1 {
                font-size: 18px;
            }

            .messArea {
                max-width: 80%;
                font-size: 12px;
            }

            #openChatButton {
                padding: 12px 16px;
            }
        }
    </style>
</head>
<body>

<button class="openChatButton" id="openChatButton" title="Open Chat">
    <span class="material-icons">chat</span>
</button>


{{--<button id="openChatButton" title="Open Chat">--}}
{{--    <span class="material-icons">chat</span>--}}
{{--</button>--}}

{{--<button class="openChatButton" id="openChatButton" title="Open Chat" data-product-id="{{ $product->id }}">--}}
{{--    <span class="material-icons">chat</span>--}}
{{--</button>--}}

{{--<button class="openChatButton" id="openChatButton" title="Open Chat" data-bill-id="1044">--}}
{{--    <span class="material-icons">chat</span>--}}
{{--</button>--}}
{{--<button class="openChatButton" id="openChatButton" title="Open Chat" data-bill-id="{{ $bill_detail->id }}">--}}
{{--    <span class="material-icons">chat</span>--}}
{{--</button>--}}

<div class="chatWidget" id="chatWidget">
    <div class="headerDet">
        <div class="img">
            <img src="{{ asset('filemanager/userfiles/' . $settings['favicon']) }}" alt="logo">
        </div>
{{--        <div class="nameC">--}}
{{--            @if(Auth::check() && Auth::user()->user_type == "Người bán")--}}
{{--                Trao đổi với khách hàng--}}
{{--            @else--}}
{{--                Trao đổi với Shop--}}
{{--            @endif--}}
{{--        </div>--}}
        <div class="nameC">
          {{__('lang.thanhToanDonHang')}}
        </div>
        <button id="closeChatButton" title="Close Chat">x</button>
    </div>

    <div class="chatArea" id="chatArea"></div>

    <div class="messageBox">
        <input type="file" id="fileUpload" style="display: none">
        <label for="fileUpload" id="fileLabel" class="button-s1" title="{{__('lang.Upload_File')}}"><span class="material-icons">attach_file</span></label>
        <span id="fileNameWrapper">
            <span id="fileName"></span>
            <button id="clearFile" title="{{__("lang.Remove_File")}}">x</button>
        </span>
        <input type="text" id="message" placeholder="{{__("lang.Type_a_message")}}...">
        <button class="button-s1" id="sendBtn" title="Send"><span class="material-icons">{{__('lang.Send')}}</span></button>
    </div>
</div>


<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const sendBtn = document.getElementById("sendBtn");
    const messageInput = document.getElementById("message");
    const chatArea = document.getElementById("chatArea");
    const fileUpload = document.getElementById("fileUpload");
    const fileName = document.getElementById("fileName");
    const clearFile = document.getElementById("clearFile");
    const openChatButtons = document.querySelectorAll(".openChatButton");
    const closeChatButton = document.getElementById("closeChatButton");
    const chatWidget = document.getElementById("chatWidget");
    {{--// var bill_id = 0;--}}
    {{--let admin_id = {{ auth()->id() ?? 'null' }};--}}
    {{--let lastMessageTime = null;--}}

    let user_id = {{ Auth::guard('users')->id() ?? 'null' }};
    let admin_id = {{ $mainAdminId ?? 1 }}; // ID admin tổng

    // Hàm kiểm tra thiết bị mobile chính xác hơn
    function isMobileDevice() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    // Luôn ẩn chat widget khi khởi tạo trên mobile
    document.addEventListener("DOMContentLoaded", function() {
        if (isMobileDevice()) {
            chatWidget.style.display = "none";
        }

        const firstOpenChatButton = document.querySelector(".openChatButton");
        if (firstOpenChatButton) {
            bill_id = firstOpenChatButton.getAttribute("data-bill-id");

            // CHỈ tự động mở trên desktop
            if (!isMobileDevice()) {
                openChatBox(firstOpenChatButton);
            }
        }
    });

    // //Hàm mở chat box
    // function openChatBox(button) {
    //     fetch('/check-login')
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.loggedIn) {
    //                 chatWidget.style.display = "flex";
    //                 lastMessageTime = null;
    //                 loadMessages(bill_id);
    //
    //                 if (button) {
    //                     button.style.display = "none";
    //                 }
    //             } else {
    //                 alert("Vui lòng đăng nhập để sử dụng tính năng chat.");
    //             }
    //         })
    //         .catch(error => {
    //             console.error('Lỗi khi kiểm tra đăng nhập:', error);
    //         });
    // }
    function openChatBox(button) {
        chatWidget.style.display = "flex";
        lastMessageTime = null;
        // loadMessages(bill_id);

        if (button) {
            button.style.display = "none";
        }
    }
    // Các hàm khác giữ nguyên
    function formatDate(date) {
        return date.toLocaleDateString('vi-VN');
    }

    function formatTime(date) {
        return date.toLocaleTimeString('vi-VN', {hour: '2-digit', minute: '2-digit'});
    }

    function createMessageElement(message, type = "sent", timestamp = null) {
        const now = new Date(timestamp || Date.now());
        const dateStr = now.toLocaleDateString('vi-VN');
        const timeStr = now.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' });

        let showTime = false;
        if (!lastMessageTime || now - lastMessageTime > 5 * 60 * 1000) {
            showTime = true;
        }
        lastMessageTime = now;

        let contentHTML = "";
        if (message.content) {
            contentHTML += `<div>${message.content}</div>`;
        }

        if (message.file_url) {
            const fileUrl = message.file_url;
            const fileName = message.file ? message.file : fileUrl.split('/').pop();
            const isImage = /\.(jpg|jpeg|png|gif|bmp|svg|webp)$/i.test(fileName);

            if (isImage) {
                contentHTML += `
                <div class="chat-image-wrapper">
                    <img src="${fileUrl}" alt="${fileName}" class="chat-image"
                         onclick="window.open('${fileUrl}', '_blank')">
                </div>`;
            } else {
                contentHTML += `
                <div>
                    <a href="${fileUrl}" target="_blank" download="${fileName}">
                        📎 ${fileName}
                    </a>
                </div>`;
            }
        }

        let html = '';
        if (showTime) {
            html += `<div class="timeId">${dateStr} ${timeStr}</div>`;
        }

        html += `
        <div class="message ${type}">
            <div class="messArea ${type === 'received' ? 'received' : ''}" title="${dateStr} ${timeStr}">
                ${contentHTML}
            </div>
        </div>
    `;
        return html;
    }


    // function loadMessages(bill_id) {
    //     if (!bill_id) return;
    //
    //     lastMessageTime = null;
    //     fetch(`/chat/${bill_id}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             chatArea.innerHTML = '';
    //             data.forEach(message => {
    //                 const isoDateStr = message.created_at.replace(' ', 'T');
    //                 const timestamp = new Date(isoDateStr);
    //                 const messageHTML = createMessageElement(message, message.admin_id === admin_id ? 'sent' : 'received', timestamp);
    //                 chatArea.insertAdjacentHTML('beforeend', messageHTML);
    //             });
    //             chatArea.scrollTop = chatArea.scrollHeight;
    //         })
    //         .catch(error => {
    //             console.error('Lỗi khi lấy tin nhắn:', error);
    //         });
    // }
    // function loadMessages(product_id) {
    //     if (!product_id) return;
    //
    //     lastMessageTime = null;
    //     fetch(`/chat/product/${product_id}`)
    //         .then(response => response.json())
    //         .then(data => {
    //             chatArea.innerHTML = '';
    //             data.forEach(message => {
    //                 const isoDateStr = message.created_at.replace(' ', 'T');
    //                 const timestamp = new Date(isoDateStr);
    //                 const messageHTML = createMessageElement(message, message.sender_id === admin_id ? 'sent' : 'received', timestamp);
    //                 chatArea.insertAdjacentHTML('beforeend', messageHTML);
    //             });
    //             chatArea.scrollTop = chatArea.scrollHeight;
    //         })
    //         .catch(error => {
    //             console.error('Lỗi khi lấy tin nhắn:', error);
    //         });
    // }
    function loadMessages() {
        if (!user_id) return; // chưa login thì bỏ

        fetch(`/chat/messages`) // gọi tới ChatController@getMessages (cho user)
            .then(response => response.json())
            .then(data => {
                chatArea.innerHTML = '';
                data.forEach(message => {
                    const isoDateStr = message.created_at.replace(' ', 'T');
                    const timestamp = new Date(isoDateStr);

                    // phân loại gửi / nhận
                    const type = (message.sender_id == user_id) ? 'sent' : 'received';

                    const messageHTML = createMessageElement(message, type, timestamp);
                    chatArea.insertAdjacentHTML('beforeend', messageHTML);
                });
                chatArea.scrollTop = chatArea.scrollHeight;
            })
            .catch(error => {
                console.error('Lỗi khi lấy tin nhắn:', error);
            });
    }

    // Xử lý sự kiện mở chat từ nút bấm
    openChatButtons.forEach(button => {
        button.addEventListener("click", function() {
            bill_id = button.getAttribute("data-bill-id");
            openChatBox(button);
        });
    });

    // Xử lý đóng chat
    closeChatButton.addEventListener("click", function() {
        chatWidget.style.display = "none";
        openChatButtons.forEach(button => {
            button.style.display = "inline-block";
        });
    });

    // Các sự kiện khác
    sendBtn.addEventListener('click', sendMessage);

    function sendMessage() {
        const text = messageInput.value.trim();
        const file = fileUpload.files[0];
        if (text === "" && !file) return;

        let formData = new FormData();
        formData.append('admin_id', admin_id);
        // formData.append('bill_id', bill_id);
        // formData.append('product_id', product_id);
        formData.append('content', text);

        if (file) {
            formData.append('file', file);
        }

        // fetch('/chat', {
        //     method: 'POST',
        //     body: formData
        // })
        fetch('/chat', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData,
            credentials: 'same-origin' // để gửi kèm session cookie
        })

            .then(response => {
                if (response.status === 401) {
                    alert("Vui lòng đăng nhập để sử dụng tính năng chat.");
                    return null;
                }
                return response.json();
            })

            .then(data => {

                let timestamp = new Date(data.created_at);
                if (isNaN(timestamp)) {
                    timestamp = new Date();
                }
                const messageHTML = createMessageElement(data, "sent", timestamp);
                chatArea.insertAdjacentHTML("beforeend", messageHTML);
                chatArea.scrollTop = chatArea.scrollHeight;

                messageInput.value = "";
                fileUpload.value = "";
                fileName.textContent = "";
                clearFile.style.display = "none";

                lastMessageTime = new Date(data.timestamp);
            })
            .catch(error => {
                console.error('Lỗi khi gửi tin nhắn:', error);
            });
    }

    fileUpload.addEventListener('change', function() {
        if (fileUpload.files.length > 0) {
            fileName.textContent = fileUpload.files[0].name;
            clearFile.style.display = "inline-block";
        } else {
            fileName.textContent = "";
            clearFile.style.display = "none";
        }
    });

    clearFile.addEventListener('click', function() {
        fileUpload.value = "";
        fileName.textContent = "";
        clearFile.style.display = "none";
    });

    messageInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            sendMessage();
        }
    });

    // Làm mới tin nhắn mỗi 3 giây khi chat box đang mở
    setInterval(() => {
        if (chatWidget.style.display === "flex") {
            loadMessages();
        }
    }, 3000);
</script>

</body>
</html>
