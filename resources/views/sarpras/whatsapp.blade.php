@extends('layouts.sarpras')

@section('title', 'WhatsApp Bot')

@section('content')
<div class="container py-4" style="max-width:520px">

    <h3 class="text-center mb-3">🤖 WhatsApp Bot</h3>

    <div id="status" class="alert alert-secondary text-center">
        Bot belum aktif
    </div>

    <div id="qrBox" class="text-center my-3"></div>

    <div class="d-grid gap-2">
        <button class="btn btn-danger" onclick="resetBot()">
            🔄 Reset Session
        </button>
    </div>

</div>

<script>
const API = "https://deep-rivers-join.loca.lt";

async function refresh() {
    try {
        const res = await fetch(API + "/status");
        const data = await res.json();

        const status = document.getElementById("status");
        const qrBox = document.getElementById("qrBox");

        qrBox.innerHTML = "";

        // ✅ SUDAH CONNECT
        if (data.ready === true) {
            status.className = "alert alert-success text-center";
            status.innerText = "✅ Bot sudah terhubung";
            return;
        }

        // ✅ ADA QR
        if (data.hasQR === true) {
            status.className = "alert alert-warning text-center";
            status.innerText = "Scan QR di WhatsApp";

            const qrRes = await fetch(API + "/qr");
            const qrData = await qrRes.json();

            if (qrData.qr) {
            qrBox.innerHTML = `
                <img 
                    src="${qrData.qr}" 
                    style="width:300px;border-radius:12px"
                >
                <div class="text-muted mt-2">
                    WhatsApp → Perangkat tertaut → Tautkan perangkat
                </div>
            `;
            }

            return;
        }

        status.className = "alert alert-secondary text-center";
        status.innerText = "Bot belum aktif";

    } catch (err) {
        document.getElementById("status").innerText =
            "❌ Server belum siap / belum jalan";
    }
}

async function resetBot() {
    await fetch(API + "/logout", { method: "POST" });
    setTimeout(refresh, 1200);
}

// auto refresh
refresh();
setInterval(refresh, 3000);
</script>
@endsection
