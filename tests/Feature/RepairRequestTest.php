<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Request as RepairRequest;

class RepairRequestTest extends TestCase
{
    use RefreshDatabase; // Эта штука очищает тестовую БД перед каждым тестом

    // ТЕСТ 1: Может ли обычный клиент создать заявку?
    public function test_guest_can_create_repair_request()
    {
        $response = $this->post('/requests', [
            'clientName' => 'Тест Клиент',
            'phone' => '+79990001122',
            'address' => 'ул. Тестовая, 1',
            'problemText' => 'Сломался телевизор',
        ]);

        // Проверяем, что нас вернуло назад с зеленой плашкой успеха
        $response->assertSessionHas('success', 'Заявка успешно отправлена!');
        
        // Проверяем, что запись реально появилась в базе данных
        $this->assertDatabaseHas('requests', [
            'clientName' => 'Тест Клиент',
            'phone' => '+79990001122',
        ]);
    }

    // ТЕСТ 2: Видит ли мастер только свои заявки?
    public function test_master_sees_only_assigned_requests()
    {
        // Создаем двух мастеров (тестовых)
        $master1 = User::factory()->create(['role' => 'master', 'name' => 'Мастер 1']);
        $master2 = User::factory()->create(['role' => 'master', 'name' => 'Мастер 2']);

        // Создаем заявку для Мастера 1
        RepairRequest::create([
            'clientName' => 'Заявка Петра',
            'phone' => '111',
            'address' => 'Адрес 1',
            'problemText' => 'Проблема 1',
            'status' => 'assigned',
            'assignedTo' => $master1->id,
        ]);

        // Создаем заявку для Мастера 2
        RepairRequest::create([
            'clientName' => 'Заявка Андрея',
            'phone' => '222',
            'address' => 'Адрес 2',
            'problemText' => 'Проблема 2',
            'status' => 'assigned',
            'assignedTo' => $master2->id,
        ]);

        // Логинимся под Мастером 1 и заходим на дашборд
        $response = $this->actingAs($master1)->get('/dashboard');

        // Мастер 1 ДОЛЖЕН видеть свою заявку и НЕ ДОЛЖЕН видеть заявку Андрея
        $response->assertSee('Заявка Петра');
        $response->assertDontSee('Заявка Андрея');
    }
}