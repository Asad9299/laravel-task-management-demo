<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Task Reminder</title>
</head>
<body>
    <p>Hi {{ $task->user->name }},</p>

    <p>This is a reminder for your task scheduled for tomorrow:</p>

    <ul>
        <li><strong>Title:</strong> {{ $task->title }}</li>
        <li><strong>Description:</strong> {{ $task->description }}</li>
        <li><strong>Due Date:</strong> {{ $task->due_date }}</li>
    </ul>

    <p>Please make sure to complete it on time.</p>
</body>
</html>
