<nav style="background:#fff;border-bottom:1px solid #eef2f6;">
    <div style="max-width:1100px;margin:0 auto;padding:10px 16px;display:flex;gap:12px;align-items:center;">
        <a href="{{ url('/') }}" style="text-decoration:none;color:#46555f;font-weight:700;">
            Fénix OZK
        </a>
        <div style="margin-left:auto;display:flex;gap:10px;">
            @auth
                <span style="color:#46555f;">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        style="background:#009de0;border:1px solid #009de0;color:#fff;border-radius:8px;padding:6px 10px;cursor:pointer;">
                        Terminar sessão
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="color:#46555f;">Entrar</a>
            @endauth
        </div>
    </div>
</nav>
