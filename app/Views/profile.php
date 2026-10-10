<section class="page-intro">
    <div><p class="eyebrow dark-eyebrow">The person behind the work</p><h1>My <em>profile.</em></h1><p>A simple home for the demo team member’s account details.</p></div>
</section>
<section class="content-section inner-content">
    <?php if ($user === null): ?>
        <div class="empty-state"><strong>No profile found.</strong><p>Import the demo user record to display it here.</p></div>
    <?php else: ?>
        <div class="profile-layout">
            <div class="profile-card">
                <div class="profile-avatar" aria-hidden="true"><?= esc(strtoupper(substr($user['full_name'], 0, 1))) ?></div>
                <p class="eyebrow dark-eyebrow">Demo account</p>
                <h2><?= esc($user['full_name']) ?></h2>
                <p class="profile-handle">@<?= esc($user['username']) ?></p>
                <span class="profile-id">User ID <?= esc((string) $user['id']) ?></span>
            </div>
            <div class="detail-card">
                <p class="eyebrow dark-eyebrow">Account details</p>
                <h2>At a glance</h2>
                <dl>
                    <div><dt>Full name</dt><dd><?= esc($user['full_name']) ?></dd></div>
                    <div><dt>Username</dt><dd><?= esc($user['username']) ?></dd></div>
                    <div><dt>Email address</dt><dd><a href="mailto:<?= esc($user['email'], 'attr') ?>"><?= esc($user['email']) ?></a></dd></div>
                    <div><dt>Account created</dt><dd><?= esc(date('F j, Y', strtotime($user['created_at']))) ?></dd></div>
                </dl>
                <p class="detail-note">This profile is loaded from the single demo record in the users table.</p>
            </div>
        </div>
    <?php endif; ?>
</section>
