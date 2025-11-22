<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10">

        <h2 class="text-2xl font-semibold mb-6">Edit Task</h2>

        <form id="taskForm" method="POST" action="{{ route('tasks.update', $task->id) }}" class="bg-white p-6 shadow rounded">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block mb-1 font-medium">Title</label>
                <input type="text" name="title" value="{{ old('title', $task->title) }}"
                       class="w-full border rounded p-2">
                @error('title')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description', $task->description) }}</textarea>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date', $task->due_date) }}"
                       class="w-full border rounded p-2">
                @error('due_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Update Task
            </button>

            <a href="{{ route('tasks.index') }}" class="ml-4 text-gray-600 underline">
                Cancel
            </a>
        </form>

    </div>

    <script type="text/javascript">
        $(document).ready(function () {

            // Custom rule: due date cannot be past (unless existing value is past)
            jQuery.validator.addMethod("futureDate", function(value, element) {

                let existingDate = "{{ $task->due_date }}";
                let existingPast = new Date(existingDate) < new Date().setHours(0,0,0,0);

                // If task already has past date → allow it
                if (existingPast) return true;

                let selected = new Date(value);
                let today = new Date();
                today.setHours(0,0,0,0);

                return this.optional(element) || selected >= today;

            }, "Due date cannot be in the past");

            $("#taskForm").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    description: {
                        required: true,
                        minlength: 5
                    },
                    status: {
                        required: true
                    },
                    due_date: {
                        required: true,
                        date: true,
                        futureDate: true
                    }
                },
                messages: {
                    due_date: {
                        required: "Please select a due date",
                        futureDate: "Due date cannot be in the past"
                    }
                },
                errorElement: "p",
                errorClass: "text-red-600 text-sm mt-1",
                highlight: function (element) {
                    $(element).addClass("border-red-500");
                },
                unhighlight: function (element) {
                    $(element).removeClass("border-red-500");
                }
            });

        });
    </script>
</x-app-layout>
