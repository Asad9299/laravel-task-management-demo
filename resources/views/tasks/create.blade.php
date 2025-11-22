<x-app-layout>
    <div class="max-w-3xl mx-auto mt-10">

        <h2 class="text-2xl font-semibold mb-6">Create Task</h2>

        <form  id="taskForm" method="POST" action="{{ route('tasks.store') }}" class="bg-white p-6 shadow rounded">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-medium">Title</label>
                <input type="text" name="title" value="{{ old('title') }}"
                       class="w-full border rounded p-2">

                @error('title')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Description</label>
                <textarea name="description" class="w-full border rounded p-2">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Status</label>
                <select name="status" class="w-full border rounded p-2">
                    <option value="pending">Pending</option>
                    <option value="in-progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1 font-medium">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}"
                       class="w-full border rounded p-2">

                @error('due_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <x-primary-button class="ml-4">
                Save Task
            </x-primary-button>


            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Cancel</a>
        </form>

    </div>

<script>
$(document).ready(function () {
    // Allow only future dates while creating a task
    jQuery.validator.addMethod("futureDate", function(value, element) {
        let selected = new Date(value);
        let today = new Date();
        today.setHours(0,0,0,0); // remove time part
        return this.optional(element) || selected >= today;
    }, "Due date cannot be in the past");

    // Form validation
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
            title: {
                required: "Title is required",
                minlength: "Title must be at least 3 characters"
            },
            description: {
                required: "Description is required",
                minlength: "Description must be at least 5 characters"
            },
            due_date: {
                required: "Please select a due date",
                date: "Enter a valid date"
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
