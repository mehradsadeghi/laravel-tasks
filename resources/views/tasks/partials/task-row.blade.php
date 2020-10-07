<tr>

    <td class="table-text">{{ $task->id }}</td>

    <td class="table-text">{{ $task->name }}</td>

    <td>{{ $task->description }}</td>

    <td>
            <span class="label label-default">
                {!! optional(tempTags($task)->getActiveTag('state'))->getPayload('value') ?? 'not_started' !!}
            </span>
    </td>

    <!-- Task Status Checkbox -->
    <td></td>

    <!-- Task Edit Icon -->
    <td>
        <a href="{{ route('tasks.edit', $task->id) }}" class="pull-right">
            <span class="fa fa-pencil fa-fw" aria-hidden="true"></span>
            <span class="sr-only">Edit Task</span>
        </a>
    </td>
</tr>
