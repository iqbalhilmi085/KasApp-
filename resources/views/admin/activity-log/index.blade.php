@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
    <div class="space-y-4">
        <h2 class="text-lg font-semibold text-gray-900">Log Aktivitas</h2>

        <x-card :padding="false">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Waktu</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">User</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Aksi</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">Detail</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-600">IP Address</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-500 text-xs whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $log->user->name ?? 'System' }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs max-w-[250px] truncate">
                                @if ($log->model_type)
                                    {{ class_basename($log->model_type) }} #{{ $log->model_id }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $log->ip_address ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-400">Belum ada aktivitas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </x-card>

        <x-pagination :paginator="$logs" />
    </div>
@endsection
