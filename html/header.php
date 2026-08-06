<style>
    .topo {
        background: #fff;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 60px;
    }

    .topo .logo {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .topo .logo .circulo {
        width: 40px;
        height: 40px;
        background: #1B2A6B;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .topo .logo span {
        font-weight: bold;
        font-size: 18px;
        color: #1f2430;
    }

    .topo .busca {
        flex: 1;
        max-width: 500px;
        margin: 0 40px;
    }

    .topo .busca input {
        width: 100%;
        padding: 10px 16px;
        border-radius: 20px;
        border: 1px solid #d9dcE3;
        background: #f4f5f8;
        font-size: 14px;
    }

    .topo .menu {
        display: flex;
        gap: 30px;
    }

    .topo .menu a {
        display: flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        color: #1f2430;
        font-size: 14px;
    }

    .topo .menu a svg {
        width: 18px;
        height: 18px;
    }
</style>

<div class="topo">
    <a href="home.php" class="logo">
        <div class="circulo">Achô</div>
    </a>

    <form class="busca" action="produtos.php" method="GET">
        <input type="text" name="busca" placeholder=" Buscar produtos...">
    </form>

    <div class="menu">
        <a href="favoritos.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 21c-4-3-9-7-9-12a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5-5 9-9 12z"/>
            </svg>
            Favoritos
        </a>

        <a href="usuarioConta.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="4"/>
                <path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>
            </svg>
            Minha Conta
        </a>

        <a href="home.php">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 11l9-8 9 8"/>
                <path d="M5 10v10h14V10"/>
            </svg>
            Página Inicial
        </a>
    </div>
</div>
