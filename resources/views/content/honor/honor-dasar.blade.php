@extends('dashboard')

@section('content')

<div class="overflow-x-auto">
    <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
      <thead class="ltr:text-left rtl:text-right">
        <tr>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">No Rek</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama Rek</th>
          <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Honorium</th>
          <th class="px-4 py-2"></th>
        </tr>
      </thead>
  
      <tbody class="divide-y divide-gray-200">
            @foreach ($senats as $senat)
            <tr>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $senat->no_rek }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $senat->nama_rekening }}</td>
                <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ isset($honorariums[$senat->id]) ? $honorariums[$senat->id] : 'N/A' }}</td>
                <td class="whitespace-nowrap px-4 py-2">
                    <a href="#"
                        class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
  </div>
@endsection