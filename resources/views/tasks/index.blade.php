<x-app-layout>

    {{-- Flash Message --}}
    @if (session('success'))
    <div id="flash-message" class="max-w-4xl mx-auto mb-6">
        <div class="flex items-center justify-between bg-green-100 text-green-800 px-4 py-3 rounded shadow">
            <span>{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-message').remove()" class="text-green-900 hover:text-green-700 font-bold text-xl">×</button>
        </button>
        </div>
    </div>
    @endif

    <div class="max-w-4xl mx-auto mt-10">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold">My Tasks</h2>

           <a href="{{ route('tasks.create') }}"
            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
            + Add Task
            </a>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-4">
            <table class="w-full border-collapse rounded overflow-hidden">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left border">Title</th>
                        <th class="p-3 text-left border">Status</th>
                        <th class="p-3 text-left border">Due Date</th>
                        <th class="p-3 text-left border">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($tasks as $task)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border">{{ $task->title }}</td>
                            <td class="p-3 border capitalize">{{ $task->status }}</td>
                            <td class="p-3 border">{{ $task->due_date }}</td>

                            <td class="p-3 border flex gap-3">
                                <a href="{{ route('tasks.edit', $task->id) }}"
                                   class="text-blue-600 hover:underline">Edit</a>

                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600 hover:underline">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-5 text-center text-gray-500">
                                No tasks available.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-6">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
