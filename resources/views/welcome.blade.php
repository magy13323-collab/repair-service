<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сервис ремонта - Оставить заявку</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">Заявка на ремонт</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('requests.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700">Ваше имя</label>
                <input type="text" name="clientName" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Телефон</label>
                <input type="text" name="phone" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Адрес</label>
                <input type="text" name="address" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700">Описание проблемы</label>
                <textarea name="problemText" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                Отправить заявку
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="/login" class="text-sm text-gray-600 hover:underline">Вход для сотрудников</a>
        </div>
    </div>

</body>
</html>