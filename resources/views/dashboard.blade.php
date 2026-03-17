<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Панель управления (Заявки)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Список всех заявок</h3>
                        
                        <form action="{{ route('dashboard') }}" method="GET" class="flex items-center space-x-2">
                            <label class="text-sm text-gray-600 font-medium">Фильтр:</label>
                            <select name="status" onchange="this.form.submit()" class="border rounded px-3 py-1 text-sm bg-gray-50 cursor-pointer">
                                <option value="">Все статусы</option>
                                <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>Новые</option>
                                <option value="assigned" {{ request('status') == 'assigned' ? 'selected' : '' }}>Назначены</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Выполнены</option>
                                <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Отменены</option>
                            </select>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 font-bold">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="p-3">№</th>
                                    <th class="p-3">Клиент</th>
                                    <th class="p-3">Телефон</th>
                                    <th class="p-3">Проблема</th>
                                    <th class="p-3">Мастер</th>
                                    <th class="p-3">Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $req)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3">{{ $req->id }}</td>
                                        <td class="p-3 font-medium">{{ $req->clientName }}</td>
                                        <td class="p-3">{{ $req->phone }}</td>
                                        <td class="p-3">{{ $req->problemText }}</td>
                                        
                                        <td class="p-3">
                                            <form action="{{ route('requests.assign', $req->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="master_id" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm bg-white w-32 cursor-pointer" {{ auth()->user()->role !== 'dispatcher' ? 'disabled' : '' }}>
                                                    <option value="">Не назначен</option>
                                                    @foreach($masters as $master)
                                                        <option value="{{ $master->id }}" {{ $req->assignedTo == $master->id ? 'selected' : '' }}>
                                                            {{ $master->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </form>
                                        </td>

                                        <td class="p-3">
                                            <form action="{{ route('requests.update-status', $req->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1 text-sm bg-white cursor-pointer">
                                                    <option value="new" {{ $req->status == 'new' ? 'selected' : '' }}>Новая</option>
                                                    <option value="assigned" {{ $req->status == 'assigned' ? 'selected' : '' }}>Назначена</option>
                                                    <option value="in_progress" {{ $req->status == 'in_progress' ? 'selected' : '' }}>В работе</option>
                                                    <option value="done" {{ $req->status == 'done' ? 'selected' : '' }}>Выполнена</option>
                                                    <option value="canceled" {{ $req->status == 'canceled' ? 'selected' : '' }}>Отменена</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($requests->isEmpty())
                        <div class="text-center py-6 text-gray-500">Заявок с таким статусом нет.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>