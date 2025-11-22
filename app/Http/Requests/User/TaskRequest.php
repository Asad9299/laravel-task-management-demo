<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class TaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'title' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:5',
            'status' => 'required|in:pending,in-progress,completed',
            'due_date' => ['required', 'date'],
        ];

        // Create: must be today or future
        if ($this->isMethod('post')) {
            $rules['due_date'][] = 'after_or_equal:today';
            return $rules;
        }

        // Update: allow existing past date only if user keeps the same date;
        // otherwise require date >= today.
        if ($this->isMethod('put') || $this->isMethod('patch')) {

            // closure rule to enforce: value must be >= today OR equal to existing stored date
            $rules['due_date'][] = function ($attribute, $value, $fail) {
                $today = Carbon::today();

                // parse submitted and existing (route model binding 'task')
                try {
                    $submitted = Carbon::parse($value)->startOfDay();
                } catch (\Exception $e) {
                    return $fail('The '.$attribute.' is not a valid date.');
                }

                $task = $this->route('task');
                $existing = null;
                if ($task && $task->due_date) {
                    try {
                        $existing = Carbon::parse($task->due_date)->startOfDay();
                    } catch (\Exception $e) {
                        $existing = null;
                    }
                }

                // valid if submitted >= today
                if ($submitted->greaterThanOrEqualTo($today)) {
                    return;
                }

                // OR valid if submitted equals existing stored date
                if ($existing && $submitted->equalTo($existing)) {
                    return;
                }

                // otherwise fail
                return $fail('The due date must be today or a future date, or unchanged if it was already in the past.');
            };
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Title is required.',
            'title.min' => 'Title must be at least :min characters.',
            'description.required' => 'Description is required.',
            'description.min' => 'Description must be at least :min characters.',
            'status.required' => 'Please select a status.',
            'status.in' => 'Invalid status selected.',
            'due_date.required' => 'Please select a due date.',
            'due_date.date' => 'Please enter a valid date.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
        ];
    }
}
