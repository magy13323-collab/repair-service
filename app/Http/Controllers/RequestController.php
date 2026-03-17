<?php

namespace App\Http\Controllers;

use App\Models\Request as RepairRequest;
use App\Models\User;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user(); 
        
        $query = RepairRequest::latest();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($user->role === 'dispatcher') {
            $requests = $query->get();
        } else {
            $requests = $query->where('assignedTo', $user->id)->get();
        }
        
        $masters = User::where('role', 'master')->get();
        
        return view('dashboard', compact('requests', 'masters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'clientName' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'problemText' => 'required|string',
        ]);

        RepairRequest::create($validated);

        return back()->with('success', 'Заявка успешно отправлена!');
    }

    // РЕШЕНИЕ ПРОБЛЕМЫ ГОНКИ ЗДЕСЬ
    public function updateStatus(Request $request, $id)
    {
        $newStatus = $request->input('status');

        if ($newStatus === 'in_progress') {
            // Атомарное обновление: обновляем ТОЛЬКО если статус еще не изменен кем-то другим
            $updatedCount = RepairRequest::where('id', $id)
                ->whereIn('status', ['new', 'assigned'])
                ->update(['status' => 'in_progress']);

            if ($updatedCount === 0) {
                // Если обновилось 0 строк, значит другой запрос нас опередил
                return back()->with('error', 'Ошибка 409 Conflict: Эта заявка уже взята в работу другим мастером!');
            }

            return back()->with('success', 'Вы успешно взяли заявку в работу!');
        }

        // Для остальных статусов обычное сохранение
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->status = $newStatus;
        $repairRequest->save();

        return back()->with('success', 'Статус заявки успешно обновлен!');
    }

    public function assign(Request $request, $id)
    {
        $repairRequest = RepairRequest::findOrFail($id);
        $repairRequest->assignedTo = $request->input('master_id');
        
        if($request->input('master_id')) {
            $repairRequest->status = 'assigned';
        } else {
            $repairRequest->status = 'new';
        }
        
        $repairRequest->save();

        return back()->with('success', 'Мастер назначен, статус обновлен!');
    }
}