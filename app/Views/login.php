<section class="login-page">
    <div class="login-story">
        <p class="eyebrow dark-eyebrow">A little focus goes a long way</p>
        <h1>Good to have<br><em>you back.</em></h1>
        <p>Your schedule and profile are public to view. Sign in when you want to add, edit, or archive tasks.</p>
        <div class="login-tip"><span>01</span><p>Your account session is stored on the server. Sign out when you finish using a shared device.</p></div>
    </div>
    <div class="login-card">
        <p class="eyebrow dark-eyebrow">Tasks for Today</p>
        <h2>Sign in</h2>
        <p class="login-subtitle">Use the local demonstration account to continue.</p>
        <form action="<?= site_url('login') ?>" method="post" class="login-form">
            <?= csrf_field() ?>
            <label for="username">Username</label>
            <input id="username" name="username" type="text" autocomplete="username" maxlength="50" value="<?= esc(old('username', ''), 'attr') ?>" required autofocus>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button class="button button-primary login-submit" type="submit">Sign in <span aria-hidden="true">→</span></button>
        </form>
        <p class="login-demo"><strong>Demo account</strong><br>Username: <code>rikuuu</code><br>Password: <code>TodayDemo!2026</code></p>
        <p class="login-warning">For local coursework demonstration only. Change the demo password and use HTTPS before any real deployment.</p>
    </div>
</section>
