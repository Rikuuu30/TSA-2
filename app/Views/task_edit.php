<?php
$editing = is_array($task);
$formData = session()->getFlashdata('formData') ?? ($formData ?? $task ?? ['title' => '', 'task_date' => date('Y-m-d'), 'status' => 'pending']);
$formErrors = session()->getFlashdata('errors') ?? ($formErrors ?? []);
$formAction = $editing ? site_url('tasks/' . $task['id'] . '/update') : site_url('tasks');
?>
<section class="page-intro edit-intro">
    <div><p class="eyebrow dark-eyebrow"><?= $editing ? 'Fine-tune your plan' : 'Make a plan' ?></p><h1><?= $editing ? 'Edit' : 'New' ?> <em>task.</em></h1><p><?= $editing ? 'Update the details below and save your changes to the schedule.' : 'Add a clear task title and scheduled date to your plan.' ?></p></div>
</section>
<section class="content-section edit-section">
    <div class="edit-card">
        <div class="edit-card-heading"><p class="eyebrow dark-eyebrow"><?= $editing ? 'Task #' . esc((string) $task['id']) : 'New entry' ?></p><h2>Task details</h2></div>
        <?php if ($formErrors !== []): ?>
            <div class="form-errors" role="alert"><strong>Please check your task:</strong><ul><?php foreach ($formErrors as $error): ?><li><?= esc($error) ?></li><?php endforeach; ?></ul></div>
        <?php endif; ?>
        <form class="edit-form" action="<?= $formAction ?>" method="post">
            <?= csrf_field() ?>
            <label class="form-field"><span>Task title</span><input name="title" type="text" maxlength="150" minlength="3" required value="<?= esc($formData['title'] ?? '', 'attr') ?>"></label>
            <div class="edit-form-grid">
                <label class="form-field"><span>Scheduled date</span><input name="task_date" type="date" required value="<?= esc($formData['task_date'] ?? date('Y-m-d'), 'attr') ?>"></label>
                <?php if ($editing): ?>
                    <label class="form-field"><span>Status</span><select name="status"><option value="pending" <?= ($formData['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option><option value="in progress" <?= ($formData['status'] ?? '') === 'in progress' ? 'selected' : '' ?>>In progress</option><option value="done" <?= ($formData['status'] ?? '') === 'done' ? 'selected' : '' ?>>Done</option></select></label>
                <?php else: ?>
                    <p class="form-field">New tasks start with Pending status.</p>
                <?php endif; ?>
            </div>
            <div class="edit-form-actions"><button class="button button-dark" type="submit"><?= $editing ? 'Save changes' : 'Add task' ?> <span aria-hidden="true">↗</span></button><a class="text-link" href="<?= site_url('tasks') ?>#schedule-title">Cancel</a></div>
        </form>
    </div>
</section>
