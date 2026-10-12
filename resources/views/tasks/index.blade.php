<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-900 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col font-sans antialiased bg-slate-900">

    <!-- Top Header Navigation -->
    <header className="border-b border-slate-800 bg-slate-950/70 backdrop-blur sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-600/20 border border-indigo-500/30 rounded-lg text-indigo-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                </div>
                <h1 class="text-lg font-bold tracking-tight text-white">Task Manager</h1>
            </div>
            <span class="text-xs px-2.5 py-1 rounded-full bg-slate-800 text-slate-400 border border-slate-700">Priority Ordering Active</span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- LEFT SIDE: New Task Form Card (4 cols) -->
            <div class="lg:col-span-4 bg-slate-800/80 border border-slate-700/80 backdrop-blur-md rounded-2xl p-6 shadow-xl sticky top-24">
                <div class="flex items-center gap-2 mb-6">
                    <span class="p-1.5 bg-emerald-500/10 text-emerald-400 rounded-md border border-emerald-500/20">➕</span>
                    <h3 class="text-base font-semibold text-white">Add New Task</h3>
                </div>
                
                <form action="{{ route('tasks.newtask') }}" method="POST" class="space-y-4">
                    @csrf 
                    
                    <div>
                        <label for="task" class="block text-xs font-medium text-slate-300 uppercase tracking-wider mb-2">Task</label>
                        <input 
                            type="text" 
                            id="task" 
                            name="task" 
                            required 
                            placeholder="e.g. Prepare client deck"
                            class="w-full bg-slate-900 border border-slate-700 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-100 placeholder-slate-500 text-sm rounded-xl px-4 py-3 outline-none transition"
                        >
                    </div>

                    <button 
                        type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-medium text-sm py-3 px-4 rounded-xl transition shadow-lg shadow-emerald-900/20 flex items-center justify-center gap-2"
                    >
                        Save Task
                    </button>
                </form>
            </div>

            <!-- RIGHT SIDE: Tasks Table Card (8 cols) -->
            <div class="lg:col-span-8 bg-slate-800/80 border border-slate-700/80 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-700/80 flex items-center justify-between bg-slate-800/50">
                    <h2 class="text-base font-semibold text-white">Task Backlog</h2>
                    <span class="text-xs text-slate-400">Drag <span class="text-slate-200">🔀</span> to reorder priority</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-700/60 bg-slate-900/40 text-slate-400 text-xs uppercase tracking-wider">
                                <th class="py-3 px-4 text-center w-12">Move</th>
                                <th class="py-3 px-4 w-16">ID</th>
                                <th class="py-3 px-4 w-20">Priority</th>
                                <th class="py-3 px-6">Task</th>
                                <th class="py-3 px-4 w-36">Created At</th>
                                <th class="py-3 px-4 text-right w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="task-list" class="divide-y divide-slate-700/40 text-sm">
                            @foreach ($tasks as $task)
                                <tr data-id="{{ $task->id }}" class="hover:bg-slate-700/30 transition group">
                                    <!-- Drag Handle -->
                                    <td class="drag-handle cursor-grab active:cursor-grabbing py-3.5 px-4 text-center select-none opacity-60 group-hover:opacity-100 transition" title="Drag to reorder">
                                        <span class="text-slate-400 hover:text-indigo-400 text-base">🔀</span>
                                    </td>

                                    <!-- ID -->
                                    <td class="py-3.5 px-4 font-mono text-xs text-slate-400">#{{ $task->id }}</td>

                                    <!-- Rank / Priority -->
                                    <td class="py-3.5 px-4">
                                        <span class="rank-cell inline-flex items-center justify-center px-2.5 py-1 rounded-full bg-slate-900 text-indigo-400 font-mono text-xs border border-slate-700 font-semibold">
                                            {{ $task->priority }}
                                        </span>
                                    </td>                 
                                    
                                    <!-- Task Title & Inline Edit -->
                                    <td class="py-3.5 px-6 font-medium text-slate-200">
                                        @if(isset($editingTask) && $editingTask->id == $task->id)
                                            <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="flex items-center gap-2">
                                                @csrf
                                                <input 
                                                    type="text" 
                                                    name="task" 
                                                    value="{{ $task->task }}" 
                                                    required 
                                                    class="bg-slate-900 border border-indigo-500/80 text-white text-sm rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 w-full"
                                                >
                                                <button type="submit" class="p-1.5 bg-emerald-600/20 hover:bg-emerald-600/30 text-emerald-400 rounded-md border border-emerald-500/30 transition" title="Save">💾</button>
                                                <a href="{{ route('tasks.index') }}" class="p-1.5 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-md transition" title="Cancel">❌</a>
                                            </form>
                                        @else
                                            {{ $task->task }}
                                        @endif
                                    </td>   
                                    
                                    <!-- Date -->
                                    <td class="py-3.5 px-4 text-xs text-slate-400 font-mono">
                                        {{ $task->created_at ? $task->created_at->format('Y-m-d H:i') : 'N/A' }}
                                    </td>
                                    
                                    <!-- Action Buttons -->
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tasks.edit', $task->id) }}" class="p-1.5 hover:bg-slate-700 rounded-lg text-slate-300 transition" title="Edit Task">✏️</a>
                                            <a href="{{ route('tasks.delete', $task->id) }}" class="p-1.5 hover:bg-red-950/50 hover:text-red-400 rounded-lg text-slate-400 transition" title="Delete Task">🗑️</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> 
                    </table>
                </div>
            </div>

        </div>
    </main>

    <!-- Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
    $(function () {
        const $list = $('#task-list');

        if ($list.length && window.Sortable) {
            Sortable.create($list[0], {
                animation: 150,
                handle: '.drag-handle',
                onEnd: function () {
                    const orderedIds = $list.find('tr[data-id]').map(function () {
                        return $(this).data('id');
                    }).get();

                    $.ajax({
                        url: "{{ route('tasks.reorder') }}",
                        method: 'POST',
                        contentType: 'application/json',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: JSON.stringify({ order: orderedIds }),
                        success: function () {
                            $list.find('tr[data-id]').each(function (i) {
                                $(this).find('.rank-cell').text(i + 1);
                            });
                        },
                        error: function (xhr) {
                            console.error('Saving order failed:', xhr.responseText);
                            alert('Could not save the new order. Reloading to restore it.');
                            window.location.reload();
                        }
                    });
                }
            });
        }
    });
    </script> 

</body>
</html>