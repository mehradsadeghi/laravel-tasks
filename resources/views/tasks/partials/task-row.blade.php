<tr>

    <!-- Task Id -->
    <td class="table-text">
        {{ $task->id }}
    </td>

    <!-- Task Name -->
    <td class="table-text">
        {{ $task->name }}
    </td>

    <!-- Task Description -->
    <td>
        {{ $task->description }}
    </td>

    <!-- Task Status -->
    <td>
            <span class="label label-default">
                {!! tempTags($task)->getActiveTag('state')->getPayload('value') ?? '' !!}
            </span>
    </td>

    <!-- Task Status Checkbox -->
    <td>

    </td>

    <!-- Task Edit Icon -->
    <td>
        <a href="{{ route('tasks.edit', $task->id) }}" class="pull-right">
            <span class="fa fa-pencil fa-fw" aria-hidden="true"></span>
            <span class="sr-only">Edit Task</span>
        </a>
    </td>
</tr>
