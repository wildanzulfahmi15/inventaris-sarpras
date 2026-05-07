@extends('layouts.main')

@section('title', 'Login Guru & Sarpras')

@section('content')
<style>
    /* ================= BACKGROUND ================= */
    body {
        margin: 0;
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px; /* Biar tidak nempel di pinggir Android */

        font-family: 'Inter', sans-serif;

        /* Formal soft-blue gradient */
        background: linear-gradient(135deg, #d7eaff, #a9d2ff, #78b6ff);
        background-size: 300% 300%;
        animation: gradientMove 10s ease infinite;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* ================= SMALL ELEGANT DOTS ANIMATION ================= */
    .dot {
        position: absolute;
        width: 8px;
        height: 8px;
        background: rgba(255,255,255,0.5);
        border-radius: 50%;
        animation: floatUp 6s linear infinite;
        pointer-events: none;
    }

    @keyframes floatUp {
        0% { transform: translateY(0); opacity: 1; }
        100% { transform: translateY(-120px); opacity: 0; }
    }

    .dot:nth-child(1){ left: 20%; bottom: 20px; animation-duration: 7s; }
    .dot:nth-child(2){ left: 50%; bottom: 40px; animation-duration: 9s; }
    .dot:nth-child(3){ left: 75%; bottom: 30px; animation-duration: 6s; }

    /* ================= LOGIN CARD ================= */
    .login-box {
        width: 100%;
        max-width: 380px;
        padding: 35px 30px;
        border-radius: 14px;

        background: rgba(255, 255, 255, 0.85);
        border: 1px solid rgba(255,255,255,0.55);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);

        animation: fadeIn 0.8s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-box h2 {
        text-align: center;
        margin-bottom: 18px;
        color: #003366;
        font-weight: 700;
    }

    /* ================= INPUT ================= */
    .login-box input {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 15px;
        border-radius: 10px;
        border: 1px solid #c7d7ee;
        background: #f7f9fc;
        font-size: 15px;
        outline: none;
    }

    .login-box input:focus {
        border-color: #5a9cff;
        box-shadow: 0 0 6px rgba(90,156,255,0.4);
    }

    /* ================= BUTTON ================= */
    .login-box button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 10px;
        background: #1976d2;
        color: white;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.25s;
    }

    .login-box button:hover {
        background: #0f5fb2;
    }

    /* Error Message */
    .error {
        background: #ff6b6b;
        color: white;
        padding: 10px;
        border-radius: 10px;
        margin-bottom: 15px;
        font-size: 14px;
        text-align: center;
    }

</style>

<!-- Elegant Floating Dots -->
<div class="dot"></div>
<div class="dot"></div>
<div class="dot"></div>

<!-- LOGIN BOX -->
<div class="login-box">
    <h2>Login Guru & Sarpras</h2>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('login.process') }}" method="POST">
        @csrf

        <input type="text" name="nama" placeholder="Nama" value="{{ old('nama') }}">
        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
    </form>
</div>

@endsection
