@extends('layouts.guest')

@section('title')
Iniciar Sessão | {{ config('app.name') }}
@endsection

@section('content')
<div id="login" class="login-container">

    <!-- ========== INÍCIO: LOGO NETPOS ========== -->
    <div class="login-logo">
        <a href="{{ route('login') }}" title="NetPos - Ponto de Venda e Gestão">
            <!-- SVG Inline do NetPos (Cores originais sólidas, sem gradientes, largura ~220px) -->
            <img src="{{ asset('assets/images/logo-icon.svg') }}" alt="{{ config('app.name') }}">
        </a>
    </div>
    <!-- ========== FIM: LOGO NETPOS ========== -->

    <!-- ========== INÍCIO: AVISOS (NOTICES ESTILO WORDPRESS) ========== -->
    @if (flash()->has('error'))
        <div class="notice notice-error login-notice login-notice-error" role="alert">
            <p id="login-error-message"><strong>Erro:</strong> {{ flash()->get('error') }}</p>
        </div>
    @endif
    <!-- ========== FIM: AVISOS ========== -->

    <!-- ========== INÍCIO: FORMULÁRIO (CARTÃO BRANCO WP-LOGIN) ========== -->
    <form action="{{ route('sig-in') }}" name="loginform" id="loginform" method="POST" class="login-form-card" autocomplete="off" novalidate>

        @csrf 
        <!-- Campo 1: Nome de utilizador ou endereço de email -->
        <div class="form-field-group">
            <label for="user_login">Endereço de email</label>
            <input 
                type="text"
                name="email"
                id="user_login"
                class="input @error_class('email')"
                value="{{ old('email') }}"
                size="20"
                autocapitalize="off"
                required
            />
            @error('email')
                <span class="form-error" id="username-help">{{ $message }}</span>
            @enderror
        </div>

        <!-- Campo 2: Palavra-passe com botão do olho integrado -->
        <div class="form-field-group">
            <label for="user_pass">Palavra-passe</label>
            <div class="password-input-wrapper user-pass-wrap">
                <input
                    type="password"
                    name="password"
                    id="user_pass"
                    class="input @error_class('password')"
                    value="{{ old('password') }}"
                    size="20"
                    autocomplete="current-password"
                    spellcheck="false"
                    required 
                />
                <!-- Botão de alternar visibilidade (olho / olho riscado) -->
                <button type="button" class="button-secondary wp-hide-pw btn-toggle-password" data-action="toggle-password" data-target="user_pass" aria-label="Mostrar palavra-passe">
                    <i class="fa-regular fa-eye" aria-hidden="true"></i>
                </button>
            </div>
            @error('password')
                <span class="form-error" id="password-help">{{ $message }}</span>
            @enderror
        </div>

        <!-- Linha Inferior: Lembrar-me à esquerda e Botão Iniciar Sessão à direita -->
        <div class="login-actions-row forgetmenot-and-submit">
            <p class="forgetmenot">
                <input
                    name="rememberme"
                    type="checkbox"
                    id="rememberme"
                    value="forever"
                    class="wp-checkbox"
                />
                <label for="rememberme" class="rememberme-dis">
                    Lembrar-me
                </label>
            </p>

            <p class="submit" style="margin: 0;">
                <input
                    type="submit"
                    name="wp-submit"
                    id="wp-submit"
                    class="button button-primary btn-wp-primary"
                    value="Iniciar sessão"
                />
            </p>
        </div>

    </form>
    <!-- ========== FIM: FORMULÁRIO ========== -->

    <!-- ========== INÍCIO: LINKS (FORA DO CARTÃO, SOBRE O FUNDO CINZENTO) ========== -->
    <p id="nav" class="login-nav-links">
        <a href="{{ route('register') }}">Registar</a> | 
        <a href="#" >Perdeu a palavra-passe?</a>
    </p>
    <!-- ========== FIM: LINKS ========== -->

</div>
@endsection