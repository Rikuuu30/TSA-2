<section class="schedule-hero">
    <div class="schedule-hero-copy">
        <p class="eyebrow dark-eyebrow"><span class="eyebrow-line"></span> Your complete schedule</p>
        <h1>Every task.<br><em>Right on time.</em></h1>
        <p>Look beyond today. Your work is grouped by date so it’s easy to see what’s done, what’s in front of you, and what’s coming up.</p>
        <a class="text-link" href="<?= site_url('/') ?>">Back to today <span aria-hidden="true">↗</span></a>
    </div>
    <div class="schedule-feature" aria-label="Schedule summary">
        <span class="schedule-feature-kicker">The big picture</span>
        <strong><?= $total ?></strong>
        <span class="schedule-feature-label">tasks on your schedule</span>
        <div class="feature-rule"></div>
        <span class="schedule-feature-footer"><?= count($schedule) ?> <?= count($schedule) === 1 ? 'scheduled date' : 'scheduled dates' ?> <span aria-hidden="true">↗</span></span>
    </div>
</section>

<section class="schedule-statbar" aria-label="Schedule overview">
    <div><span class="statbar-icon today-icon" aria-hidden="true">●</span><span><strong><?= $todayCount ?></strong><small>Due today</small></span></div>
    <div><span class="statbar-icon coming-icon" aria-hidden="true">↗</span><span><strong><?= $upcomingCount ?></strong><small>Coming up</small></span></div>
    <div><span class="statbar-icon done-icon" aria-hidden="true">✓</span><span><strong><?= $doneCount ?></strong><small>Completed overall</small></span></div>
</section>

<section class="task-workspace" id="add-task" aria-labelledby="add-task-title">
    <div class="workspace-heading">
        <div><p class="eyebrow dark-eyebrow">Manage your plans</p><h2 id="add-task-title">Keep your schedule up to date</h2><p>Task management is available after signing in. The schedule remains public to view.</p></div>
        <span class="workspace-mark" aria-hidden="true">＋</span>
    </div>
    <?php if (session()->has('user_id')): ?>
        <a class="button button-dark" href="<?= site_url('tasks/new') ?>">Add a task <span aria-hidden="true">↗</span></a>
    <?php else: ?>
        <a class="button button-dark" href="<?= site_url('login') ?>">Sign in to manage tasks <span aria-hidden="true">↗</span></a>
    <?php endif; ?>
</section>

<section class="content-section schedule-section" aria-labelledby="schedule-title">
    <div class="section-heading">
        <div><p class="eyebrow dark-eyebrow">Date by date</p><h2 id="schedule-title">Your schedule</h2><p class="section-description"><?= $filtersActive ? 'Showing ' . $visibleCount . ' of ' . $total . ' tasks.' : 'All ' . $total . ' tasks, shown in chronological order.' ?></p></div>
        <div class="section-heading-actions"><span class="section-count"><?= count($schedule) ?> <?= count($schedule) === 1 ? 'day' : 'days' ?></span><button class="view-mode-button" type="button" id="density-toggle" aria-pressed="false"><span aria-hidden="true">☷</span> Compact view</button></div>
    </div>

    <form class="filter-bar" action="<?= site_url('tasks') ?>" method="get" aria-label="Filter tasks">
        <label class="filter-field filter-search"><span>Search tasks <kbd class="shortcut-key">/</kbd></span><input type="search" name="q" maxlength="100" placeholder="Search by title" value="<?= esc($search, 'attr') ?>"></label>
        <label class="filter-field"><span>Status</span><select name="status"><option value="">All statuses</option><option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option><option value="in progress" <?= $statusFilter === 'in progress' ? 'selected' : '' ?>>In progress</option><option value="done" <?= $statusFilter === 'done' ? 'selected' : '' ?>>Done</option></select></label>
        <label class="filter-field"><span>Date</span><input type="date" name="date" value="<?= esc($dateFilter, 'attr') ?>"></label>
        <button class="filter-button" type="submit">Apply filters</button>
        <?php if ($filtersActive): ?><a class="clear-filter" href="<?= site_url('tasks') ?>#schedule-title">Clear</a><?php endif; ?>
    </form>

    <?php
        $quickFilterBase = array_filter(['q' => $search, 'date' => $dateFilter], static fn ($value) => $value !== '');
        $quickFilters = [
            'All tasks' => ['', $quickTotalCount],
            'To do' => ['pending', $pendingCount],
            'In progress' => ['in progress', $progressCount],
            'Completed' => ['done', $doneQuickCount],
        ];
    ?>
    <nav class="quick-filters" aria-label="Quick task status filters">
        <?php foreach ($quickFilters as $label => [$filterStatus, $filterCount]): ?>
            <?php
                $filterParams = $quickFilterBase;
                if ($filterStatus !== '') {
                    $filterParams['status'] = $filterStatus;
                }
                $filterUrl = site_url('tasks') . ($filterParams === [] ? '' : '?' . http_build_query($filterParams)) . '#schedule-title';
            ?>
            <a class="quick-filter<?= $statusFilter === $filterStatus ? ' is-active' : '' ?>" href="<?= esc($filterUrl, 'attr') ?>"<?= $statusFilter === $filterStatus ? ' aria-current="page"' : '' ?>><?= esc($label) ?><span><?= $filterCount ?></span></a>
        <?php endforeach; ?>
    </nav>

    <?php if ($schedule !== []): ?>
        <nav class="schedule-date-jumps" aria-label="Jump to a scheduled day">
            <span class="date-jumps-label">Jump to a day</span>
            <?php foreach ($schedule as $jumpDate => $jumpTasks): ?>
                <?php
                    $jumpTimestamp = strtotime($jumpDate);
                    $jumpName = $jumpDate === $today ? 'Today' : ($jumpDate === date('Y-m-d', strtotime($today . ' +1 day')) ? 'Tomorrow' : date('D', $jumpTimestamp));
                    $jumpId = 'day-' . str_replace('-', '', $jumpDate);
                ?>
                <a class="date-jump<?= $jumpDate === $today ? ' is-today' : '' ?>" href="#<?= esc($jumpId, 'attr') ?>"><strong><?= esc($jumpName) ?></strong><span><?= esc(date('M j', $jumpTimestamp)) ?></span><small><?= count($jumpTasks) ?></small></a>
            <?php endforeach; ?>
        </nav>
    <?php endif; ?>

    <?php if ($schedule === []): ?>
        <div class="empty-state"><strong><?= $filtersActive ? 'No tasks match those filters.' : 'No tasks in the schedule yet.' ?></strong><p><?= $filtersActive ? 'Try another search, status, or date.' : 'Sign in to add the first task to this schedule.' ?></p><?php if ($filtersActive): ?><a class="text-link" href="<?= site_url('tasks') ?>#schedule-title">Show all tasks →</a><?php endif; ?></div>
    <?php else: ?>
        <div class="schedule-timeline">
            <?php foreach ($schedule as $date => $dayTasks): ?>
                <?php
                    $timestamp = strtotime($date);
                    $relativeDay = $date === $today ? 'Today' : ($date === date('Y-m-d', strtotime($today . ' -1 day')) ? 'Yesterday' : ($date === date('Y-m-d', strtotime($today . ' +1 day')) ? 'Tomorrow' : date('l', $timestamp)));
                    $isToday = $date === $today;
                ?>
                <?php $dayId = 'day-' . str_replace('-', '', $date); ?>
                <section class="schedule-day<?= $isToday ? ' schedule-day-today' : '' ?>" id="<?= esc($dayId, 'attr') ?>" aria-label="<?= esc(date('F j, Y', $timestamp), 'attr') ?>">
                    <div class="schedule-date">
                        <div class="date-tile"><span><?= esc(date('M', $timestamp)) ?></span><strong><?= esc(date('d', $timestamp)) ?></strong></div>
                        <div class="date-meta"><span><?= esc($relativeDay) ?></span><strong><?= esc(date('l, F j', $timestamp)) ?></strong><small><?= count($dayTasks) ?> <?= count($dayTasks) === 1 ? 'task' : 'tasks' ?> scheduled</small></div>
                    </div>
                    <div class="schedule-day-tasks">
                        <?php foreach ($dayTasks as $task): ?>
                            <?php $statusClass = match ($task['status']) { 'done' => 'done', 'in progress' => 'progress', default => 'pending' }; ?>
                            <article class="schedule-task">
                                <span class="schedule-task-marker marker-<?= $statusClass ?>" aria-hidden="true"></span>
                                <div class="schedule-task-body">
                                    <div class="schedule-task-heading"><span class="schedule-task-title"><?= esc($task['title']) ?></span><span class="status status-<?= $statusClass ?>"><?= esc(ucfirst($task['status'])) ?></span></div>
                                    <div class="schedule-task-actions">
                                        <?php if (session()->has('user_id')): ?>
                                        <?php if ($task['status'] === 'pending'): ?><form action="<?= site_url('tasks/' . $task['id'] . '/start') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="return_to" value="tasks"><button class="small-action" type="submit">Start</button></form><?php endif; ?>
                                        <form action="<?= site_url('tasks/' . $task['id'] . '/toggle') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="return_to" value="tasks"><button class="small-action" type="submit"><?= $task['status'] === 'done' ? 'Reopen' : 'Mark complete' ?></button></form>
                                        <a class="small-action" href="<?= site_url('tasks/' . $task['id'] . '/edit') ?>">Edit</a>
                                        <details class="delete-control"><summary>Archive</summary><form action="<?= site_url('tasks/' . $task['id'] . '/delete') ?>" method="post"><?= csrf_field() ?><input type="hidden" name="confirm_delete" value="yes"><span>Move this task out of the active schedule?</span><button type="submit">Yes, archive</button></form></details>
                                        <?php else: ?>
                                        <a class="small-action" href="<?= site_url('login') ?>">Sign in to manage</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
