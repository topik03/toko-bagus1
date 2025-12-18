@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-green-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-green-800">💬 Chat dengan Penjual</h1>
            <p class="text-green-600">Hubungi penjual untuk pertanyaan tentang produk atau pesanan</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Sidebar: Daftar Penjual -->
            <div class="lg:w-1/3">
                <div class="bg-white rounded-lg shadow p-4">
                    <h2 class="font-semibold text-green-700 mb-3 text-lg">Daftar Penjual</h2>

                    <div id="sellerList" class="space-y-2">
                        <!-- Penjual 1 -->
                        <div id="seller1"
                             onclick="selectSeller(1)"
                             class="p-3 border border-green-100 rounded cursor-pointer hover:bg-green-50 seller-item active-seller">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    TB
                                </div>
                                <div>
                                    <h3 class="font-medium text-green-800">Toko Bagus Official</h3>
                                    <p class="text-sm text-green-600">Online</p>
                                </div>
                            </div>
                        </div>

                        <!-- Penjual 2 -->
                        <div id="seller2"
                             onclick="selectSeller(2)"
                             class="p-3 border border-green-100 rounded cursor-pointer hover:bg-green-50 seller-item">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-300 rounded-full flex items-center justify-center text-green-800 font-bold mr-3">
                                    BS
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Beras Sejahtera</h3>
                                    <p class="text-sm text-gray-600">Pesanan dikirim</p>
                                </div>
                            </div>
                        </div>

                        <!-- Penjual 3 -->
                        <div id="seller3"
                             onclick="selectSeller(3)"
                             class="p-3 border border-green-100 rounded cursor-pointer hover:bg-green-50 seller-item">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-400 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    MG
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-800">Minyak Gemilang</h3>
                                    <p class="text-sm text-gray-600">Stok tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Chat Utama -->
            <div class="lg:w-2/3">
                <div class="bg-white rounded-lg shadow h-[500px] flex flex-col">
                    <!-- Header Chat -->
                    <div class="border-b border-green-200 p-4">
                        <div class="flex items-center">
                            <div id="currentSellerAvatar" class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                TB
                            </div>
                            <div>
                                <h2 id="currentSellerName" class="font-bold text-green-800">Toko Bagus Official</h2>
                                <p id="currentSellerStatus" class="text-sm text-green-600">Online</p>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesContainer" class="flex-1 p-4 overflow-y-auto space-y-3">
                        <!-- Pesan akan dimuat di sini oleh JavaScript -->
                        <div class="flex">
                            <div class="bg-green-100 rounded-lg p-3 max-w-[70%]">
                                <p class="text-green-800">Halo! Selamat datang di Toko Bagus. Ada yang bisa kami bantu?</p>
                                <span class="text-xs text-green-600">12:30</span>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <div class="bg-green-600 text-white rounded-lg p-3 max-w-[70%]">
                                <p>Halo, saya mau tanya tentang pesanan saya</p>
                                <span class="text-xs text-green-200">12:31</span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-green-200 p-4">
                        <div class="flex gap-2">
                            <input type="text"
                                   id="messageInput"
                                   placeholder="Ketik pesan Anda..."
                                   class="flex-1 border border-green-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <button onclick="sendMessage()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                                Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.seller-item {
    transition: all 0.2s ease;
}
.active-seller {
    background-color: #f0fdf4 !important;
    border-color: #86efac !important;
}
</style>

<script>
// Data penjual dan pesan
const sellers = {
    1: {
        name: "Toko Bagus Official",
        avatar: "TB",
        status: "Online",
        color: "bg-green-500",
        messages: [
            { text: "Halo! Selamat datang di Toko Bagus. Ada yang bisa kami bantu?", time: "12:30", fromMe: false },
            { text: "Halo, saya mau tanya tentang pesanan saya", time: "12:31", fromMe: true }
        ]
    },
    2: {
        name: "Beras Sejahtera",
        avatar: "BS",
        status: "Online",
        color: "bg-green-300",
        messages: [
            { text: "Selamat siang! Ini penjual Beras Sejahtera.", time: "10:15", fromMe: false },
            { text: "Pesanan beras Anda sudah kami siapkan.", time: "10:16", fromMe: false }
        ]
    },
    3: {
        name: "Minyak Gemilang",
        avatar: "MG",
        status: "Offline",
        color: "bg-green-400",
        messages: [
            { text: "Halo dari Minyak Gemilang! Minyak goreng premium kami sedang diskon.", time: "09:45", fromMe: false }
        ]
    }
};

let currentSellerId = 1;

// Fungsi pilih penjual
function selectSeller(sellerId) {
    console.log("Memilih penjual:", sellerId);

    // Update current seller
    currentSellerId = sellerId;

    // Update UI penjual aktif
    document.querySelectorAll('.seller-item').forEach(item => {
        item.classList.remove('active-seller');
    });
    document.getElementById('seller' + sellerId).classList.add('active-seller');

    // Update header chat
    const seller = sellers[sellerId];
    document.getElementById('currentSellerName').textContent = seller.name;
    document.getElementById('currentSellerAvatar').textContent = seller.avatar;
    document.getElementById('currentSellerAvatar').className = `w-10 h-10 ${seller.color} rounded-full flex items-center justify-center text-white font-bold mr-3`;
    document.getElementById('currentSellerStatus').textContent = seller.status;

    // Tampilkan pesan
    displayMessages(seller.messages);
}

// Fungsi tampilkan pesan
function displayMessages(messages) {
    const container = document.getElementById('messagesContainer');
    container.innerHTML = '';

    messages.forEach(msg => {
        const messageDiv = document.createElement('div');
        messageDiv.className = msg.fromMe ? 'flex justify-end' : 'flex';

        messageDiv.innerHTML = `
            <div class="${msg.fromMe ? 'bg-green-600 text-white' : 'bg-green-100'} rounded-lg p-3 max-w-[70%]">
                <p>${msg.text}</p>
                <span class="text-xs ${msg.fromMe ? 'text-green-200' : 'text-green-600'}">${msg.time}</span>
            </div>
        `;

        container.appendChild(messageDiv);
    });

    // Scroll ke bawah
    container.scrollTop = container.scrollHeight;
}

// Fungsi kirim pesan
function sendMessage() {
    const input = document.getElementById('messageInput');
    const messageText = input.value.trim();

    if (messageText === '') {
        alert('Silakan ketik pesan terlebih dahulu!');
        return;
    }

    console.log("Mengirim pesan:", messageText);

    // Tambahkan ke data
    const now = new Date();
    const timeString = `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

    sellers[currentSellerId].messages.push({
        text: messageText,
        time: timeString,
        fromMe: true
    });

    // Tampilkan pesan
    displayMessages(sellers[currentSellerId].messages);

    // Clear input
    input.value = '';
    input.focus();

    // Simulasi balasan otomatis setelah 1.5 detik
    setTimeout(() => {
        sellers[currentSellerId].messages.push({
            text: "Terima kasih pesan Anda. Kami akan membalas segera.",
            time: timeString,
            fromMe: false
        });

        displayMessages(sellers[currentSellerId].messages);
    }, 1500);
}

// Kirim pesan dengan Enter
document.getElementById('messageInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
});

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    console.log("Halaman chat dimuat");

    // Set penjual default
    selectSeller(1);

    // Test: klik penjual
    const sellerItems = document.querySelectorAll('.seller-item');
    sellerItems.forEach(item => {
        item.style.cursor = 'pointer';
        console.log("Seller item ditemukan:", item.id);
    });
});
</script>
@endsection
