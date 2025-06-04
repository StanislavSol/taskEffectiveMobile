<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;


    public function test_can_create_a_task()
    {
        
        $response = $this->postJson('/api/tasks', [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending'
        ]);
        
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Tasks create successfully.',
                     'task' => [
                         'title' => 'Test Task',
                         'status' => 'pending'
                     ]
                 ]);
        
        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task',
            'status' => 'pending'
        ]);
    }

    public function test_title_is_required_for_creating_task()
    {
        $response = $this->postJson('/api/tasks', [
            'description' => 'Test Description',
            'status' => 'pending'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    public function test_can_get_all_tasks()
    {
        Task::create(['title' => 'Task 1', 'status' => 'pending']);
        Task::create(['title' => 'Task 2', 'status' => 'in_progress']);
        Task::create(['title' => 'Task 3', 'status' => 'completed']);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'tasks' => [
                    '*' => ['id', 'title', 'description', 'status', 'created_at', 'updated_at']
                ]
            ]);
    }

    public function test_can_get_single_task()
    {
        $task = Task::create(['title' => 'Test Task', 'status' => 'pending']);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Task retrieved successfully.',
                'task' => [
                    'id' => $task->id,
                    'title' => 'Test Task',
                    'status' => 'pending'
                ]
            ]);
    }

    public function test_returns_404_if_task_not_found()
    {
        $response = $this->getJson('/api/tasks/999');

        $response->assertStatus(404);
    }

    public function test_can_update_task()
    {
        $task = Task::create(['title' => 'Original Title', 'status' => 'pending']);

        $response = $this->putJson("/api/tasks/{$task->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'completed'
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'Tasks update successfully.',
                'task' => [
                    'title' => 'Updated Title',
                    'status' => 'completed'
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed'
        ]);
    }

    public function test_can_delete_task()
    {
        $task = Task::create(['title' => 'Task to delete', 'status' => 'pending']);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(201)
            ->assertJson(['message' => 'Tasks delete successfully.']);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
