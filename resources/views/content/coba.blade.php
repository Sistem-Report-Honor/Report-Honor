<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body class="bg-gray-100">
    <div class="mx-auto max-w-5xl">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Nama Senat</th>
                    <th class="py-3 px-6 text-left">Golongan</th>
                    @foreach ($rapats as $rapat)
                        <th class="py-3 px-6 text-left">{{ $rapat }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($senats as $senat)
                    <tr>
                        <td>{{ $senat->name }}</td>
                        <td>{{ $senat->golongan->golongan ?? '-' }}</td>
                        <td>{{ $senat->golongan->honor ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
