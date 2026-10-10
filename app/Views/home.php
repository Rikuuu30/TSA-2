<?php
$total = count($tasks);
$completed = count(array_filter($tasks, static fn ($task) => $task['status'] === 'done'));
$remaining = $total - $completed;
$progress = $total > 0 ? (int) round(($completed / $total) * 100) : 0;
?>
<section class="hero hero-today">
    <div class="hero-content">
        <p class="eyebrow"><span class="eyebrow-line"></span> <?= esc(date('l, F j', strtotime($today))) ?></p>
        <h1>Make today <em>count.</em></h1>
        <p class="hero-copy"><?= $total ?> <?= $total === 1 ? 'task is' : 'tasks are' ?> on the calendar. Start with what matters now, then keep the rest of your schedule in sight.</p>
        <div class="hero-actions">
            <?php if (session()->has('user_id')): ?>
                <a class="button button-primary" href="<?= site_url('tasks/new') ?>">Add a task <span aria-hidden="true">＋</span></a>
            <?php else: ?>
                <a class="button button-primary" href="<?= site_url('login') ?>">Sign in to manage tasks <span aria-hidden="true">→</span></a>
            <?php endif; ?>
            <a class="text-link light-link" href="#today-list">See today’s tasks <span aria-hidden="true">↓</span></a>
        </div>
        <div class="hero-live-clock"><span class="live-clock-dot" aria-hidden="true"></span><span>Manila time</span><time data-live-clock><?= esc(date('g:i:s A')) ?></time></div>
    </div>
    <div class="hero-side" aria-label="Current date">
        <span class="hero-side-label">Today is</span>
        <strong><?= esc(date('d', strtotime($today))) ?></strong>
        <span><?= esc(date('F Y', strtotime($today))) ?></span>
        <span class="hero-side-rule"></span>
        <small><?= esc(date('l', strtotime($today))) ?></small>
    </div>
</section>

<section class="metrics" aria-label="Today’s overview">
    <div class="metric"><span class="metric-icon" aria-hidden="true">01</span><span class="metric-label">Scheduled today</span><strong><?= $total ?></strong><span class="metric-note">On your calendar</span></div>
    <div class="metric"><span class="metric-icon" aria-hidden="true">02</span><span class="metric-label">Completed</span><strong><?= $completed ?></strong><span class="metric-note">Already taken care of</span></div>
    <div class="metric"><span class="metric-icon" aria-hidden="true">03</span><span class="metric-label">Still open</span><strong><?= $remaining ?></strong><span class="metric-note">Worth your focus</span></div>
</section>

<section class="content-section" id="today-list">
    <div class="section-heading">
        <div><p class="eyebrow dark-eyebrow">Daily schedule</p><h2>Today’s lineup</h2><p class="section-description">Your plans for <?= esc(date('l, F j, Y', strtotime($today))) ?>.</p></div>
        <div class="section-heading-actions"><span class="section-count"><?= $total ?> <?= $total === 1 ? 'task' : 'tasks' ?></span><button class="view-mode-button" type="button" id="focus-mode-toggle" aria-pressed="false"><span aria-hidden="true">◉</span> Focus mode</button><?php if (session()->has('user_id')): ?><a class="text-link" href="<?= site_url('tasks/new') ?>">Add task <span aria-hidden="true">＋</span></a><?php else: ?><a class="text-link" href="<?= site_url('login') ?>">Sign in to manage <span aria-hidden="true">→</span></a><?php endif; ?></div>
    </div>
    <div class="agenda-layout">
        <div class="task-list">
            <?php if ($tasks === []): ?>
                <div class="empty-state"><strong>No tasks scheduled for today.</strong><p>Take a breath, or look through the complete task list.</p><a class="text-link" href="<?= site_url('tasks') ?>">View all tasks →</a></div>
            <?php else: ?>
                <?php foreach ($tasks as $index => $task): ?>
                    <?php $statusClass = match ($task['status']) { 'done' => 'done', 'in progress' => 'progress', default => 'pending' }; ?>
                    <article class="task-row" data-status="<?= esc($task['status'], 'attr') ?>">
                        <span class="task-number"><?= str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) ?></span>
                        <span class="task-copy"><strong><?= esc($task['title']) ?></strong><small><?= match ($task['status']) { 'done' => 'Finished for the day', 'in progress' => 'Currently underway', default => 'Ready when you are' } ?></small></span>
                        <span class="task-row-controls">
                            <span class="status status-<?= $statusClass ?>"><?= esc(ucfirst($task['status'])) ?></span>
                            <?php if (session()->has('user_id')): ?><span class="action-buttons">
                                <?php if ($task['status'] === 'pending'): ?><form action="<?= site_url('tasks/' . $task['id'] . '/start') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="return_to" value="today"><button class="small-action" type="submit">Start</button></form><?php endif; ?>
                                <form action="<?= site_url('tasks/' . $task['id'] . '/toggle') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="return_to" value="today"><button class="small-action" type="submit"><?= $task['status'] === 'done' ? 'Reopen' : 'Complete' ?></button></form>
                            </span><?php endif; ?>
                        </span>
                    </article>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <aside class="progress-card" aria-label="Today’s progress">
            <span class="progress-caption">The day in motion</span>
            <div class="progress-ring-wrap" role="img" aria-label="<?= $progress ?> percent of today’s tasks completed">
                <svg class="progress-ring" viewBox="0 0 120 120" aria-hidden="true"><circle class="progress-ring-track" cx="60" cy="60" r="48"></circle><circle class="progress-ring-value" cx="60" cy="60" r="48" style="stroke-dasharray:301.6;stroke-dashoffset:<?= number_format(301.6 * (1 - $progress / 100), 1, '.', '') ?>"></circle></svg>
                <div class="progress-ring-label"><strong><?= $progress ?></strong><span>% done</span></div>
            </div>
            <div class="progress-track"><span style="width: <?= $progress ?>%"></span></div>
            <p><?= $completed ?> of <?= $total ?> <?= $total === 1 ? 'task' : 'tasks' ?> completed today.</p>
            <div class="progress-divider"></div>
            <a href="<?= site_url('tasks') ?>">See the full task list <span aria-hidden="true">↗</span></a>
        </aside>
    </div>
</section>
