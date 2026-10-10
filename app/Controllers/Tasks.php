<?php

namespace App\Controllers;

use App\Models\TaskModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;

class Tasks extends BaseController
{
    public function index(): string
    {
        $allTasks = (new TaskModel())
            ->where('is_archived', 0)
            ->orderBy('task_date', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        $today = date('Y-m-d');
        $searchInput = $this->request->getGet('q');
        $statusInput = $this->request->getGet('status');
        $dateInput = $this->request->getGet('date');
        $search = is_string($searchInput) ? mb_substr(trim($searchInput), 0, 100) : '';
        $status = is_string($statusInput) && in_array($statusInput, ['pending', 'in progress', 'done'], true) ? $statusInput : '';
        $date = is_string($dateInput) && $this->isValidDate($dateInput) ? $dateInput : '';

        $schedule = [];
        $todayCount = 0;
        $pendingCount = 0;
        $progressCount = 0;
        $doneCount = 0;
        $doneQuickCount = 0;
        $upcomingCount = 0;
        $quickTotalCount = 0;
        $visibleCount = 0;
        foreach ($allTasks as $task) {
            $todayCount += $task['task_date'] === $today ? 1 : 0;
            $doneCount += $task['status'] === 'done' ? 1 : 0;
            $upcomingCount += $task['task_date'] > $today ? 1 : 0;

            if ($search !== '' && mb_stripos($task['title'], $search) === false) {
                continue;
            }
            if ($status !== '' && $task['status'] !== $status) {
                continue;
            }
            if ($date !== '' && $task['task_date'] !== $date) {
                continue;
            }

            $quickTotalCount++;
            $pendingCount += $task['status'] === 'pending' ? 1 : 0;
            $progressCount += $task['status'] === 'in progress' ? 1 : 0;
            $doneQuickCount += $task['status'] === 'done' ? 1 : 0;

            $schedule[$task['task_date']][] = $task;
            $visibleCount++;
        }

        return view('partials/header', ['pageTitle' => 'All tasks', 'activePage' => 'tasks'])
            . view('tasks', [
                'schedule' => $schedule,
                'total' => count($allTasks),
                'visibleCount' => $visibleCount,
                'todayCount' => $todayCount,
                'pendingCount' => $pendingCount,
                'progressCount' => $progressCount,
                'doneCount' => $doneCount,
                'doneQuickCount' => $doneQuickCount,
                'quickTotalCount' => $quickTotalCount,
                'upcomingCount' => $upcomingCount,
                'today' => $today,
                'search' => $search,
                'statusFilter' => $status,
                'dateFilter' => $date,
                'filtersActive' => $search !== '' || $status !== '' || $date !== '',
            ])
            . view('partials/footer');
    }

    public function new(): string
    {
        return view('partials/header', ['pageTitle' => 'New task', 'activePage' => 'tasks'])
            . view('task_edit', [
                'task' => null,
                'formData' => ['title' => '', 'task_date' => date('Y-m-d'), 'status' => 'pending'],
                'formErrors' => [],
            ])
            . view('partials/footer');
    }

    public function create(): RedirectResponse
    {
        [$data, $errors] = $this->taskInput(false);
        if ($errors !== []) {
            return redirect()->to(site_url('tasks/new'))
                ->with('errors', $errors)
                ->with('formData', $data);
        }

        $data['created_at'] = date('Y-m-d H:i:s');
        (new TaskModel())->insert($data);

        return redirect()->to(site_url('tasks') . '#schedule-title')
            ->with('success', 'Task added to your schedule.');
    }

    public function edit(int $id): string
    {
        $task = $this->findTaskOrFail($id);

        return view('partials/header', ['pageTitle' => 'Edit task', 'activePage' => 'tasks'])
            . view('task_edit', ['task' => $task])
            . view('partials/footer');
    }

    public function update(int $id): RedirectResponse
    {
        $this->findTaskOrFail($id);
        [$data, $errors] = $this->taskInput();
        if ($errors !== []) {
            return redirect()->to(site_url('tasks/' . $id . '/edit'))
                ->with('errors', $errors)
                ->with('formData', $data);
        }

        (new TaskModel())->update($id, $data);

        return redirect()->to(site_url('tasks') . '#schedule-title')
            ->with('success', 'Task updated successfully.');
    }

    public function toggle(int $id): RedirectResponse
    {
        $task = $this->findTaskOrFail($id);
        $newStatus = $task['status'] === 'done' ? 'pending' : 'done';
        (new TaskModel())->update($id, ['status' => $newStatus]);

        return redirect()->to($this->returnUrl())
            ->with('success', $newStatus === 'done' ? 'Task marked complete.' : 'Task reopened.');
    }

    public function start(int $id): RedirectResponse
    {
        $task = $this->findTaskOrFail($id);
        if ($task['status'] !== 'pending') {
            return redirect()->to($this->returnUrl())
                ->with('error', 'Only pending tasks can be started.');
        }

        (new TaskModel())->update($id, ['status' => 'in progress']);

        return redirect()->to($this->returnUrl())
            ->with('success', 'Task marked in progress.');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->findTaskOrFail($id);
        if ($this->request->getPost('confirm_delete') !== 'yes') {
            return redirect()->to(site_url('tasks'))
                ->with('error', 'Please confirm before deleting a task.');
        }

        (new TaskModel())->update($id, ['is_archived' => 1]);

        return redirect()->to(site_url('tasks'))
            ->with('success', 'Task archived from your schedule.');
    }

    private function findTaskOrFail(int $id): array
    {
        $task = (new TaskModel())->where('is_archived', 0)->find($id);
        if ($task === null) {
            throw PageNotFoundException::forPageNotFound('Task not found.');
        }

        return $task;
    }

    private function taskInput(bool $allowStatus = true): array
    {
        $titleInput = $this->request->getPost('title');
        $dateInput = $this->request->getPost('task_date');
        $statusInput = $this->request->getPost('status');
        $title = is_string($titleInput) ? trim(preg_replace('/\s+/u', ' ', $titleInput) ?? '') : '';
        $date = is_string($dateInput) ? trim($dateInput) : '';
        $status = $allowStatus && is_string($statusInput) ? $statusInput : 'pending';
        $errors = [];

        if ($title === '' || mb_strlen($title) < 3 || mb_strlen($title) > 150 || preg_match('/[\p{L}\p{N}]/u', $title) !== 1) {
            $errors[] = 'Enter a task title between 3 and 150 characters that includes at least one letter or number.';
        }
        if (!$this->isValidDate($date)) {
            $errors[] = 'Choose a valid scheduled date.';
        }
        if ($allowStatus && !in_array($status, ['pending', 'in progress', 'done'], true)) {
            $errors[] = 'Choose a valid task status.';
        }

        return [['title' => $title, 'task_date' => $date, 'status' => $status], $errors];
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);

        return $parsed !== false && $parsed->format('Y-m-d') === $date;
    }

    private function returnUrl(): string
    {
        return $this->request->getPost('return_to') === 'today'
            ? site_url('/') . '#today-list'
            : site_url('tasks') . '#schedule-title';
    }
}
