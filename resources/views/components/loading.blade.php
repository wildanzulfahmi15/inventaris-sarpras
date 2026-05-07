<div id="global-loading" class="loading-overlay d-none">
    <div class="logo-loader">
        <div class="big-i">I</div>
        <div class="text-wrap">
            <div class="line inventaris">nventaris</div>
            <div class="line sarpras">arpras</div>
        </div>
    </div>
</div>

<style>
/* ================= LOADING OVERLAY ================= */
.loading-overlay{
    position:fixed;
    inset:0;
    background:rgba(255,255,255,.9);
    z-index:9999;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ================= LOGO ================= */
.logo-loader{
    display:flex;
    align-items:center;
    font-family:"Segoe UI",sans-serif;
    color:#1e3a8a;
}

/* BIG I */
.big-i{
    font-size:96px;
    font-weight:900;
    line-height:0.9;
    margin-right:6px;
}

/* TEXT */
.text-wrap{
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.line{
    font-size:32px;
    font-weight:700;
    letter-spacing:1px;
    opacity:0;
    transform:translateY(6px);
    animation: textAnim 1.8s infinite;
}

.sarpras{
    animation-delay:.9s;
}

/* ================= ANIMATION ================= */
@keyframes textAnim{
    0%{
        opacity:0;
        transform:translateY(8px);
    }
    30%{
        opacity:1;
        transform:translateY(0);
    }
    70%{
        opacity:1;
    }
    100%{
        opacity:0;
        transform:translateY(-6px);
    }
}

/* MOBILE */
@media(max-width:600px){
    .big-i{ font-size:72px; }
    .line{ font-size:24px; }
}
</style>
    <script>
function showLoading(){
    document.getElementById('global-loading')?.classList.remove('d-none');
}
function hideLoading(){
    document.getElementById('global-loading')?.classList.add('d-none');
}
</script>
