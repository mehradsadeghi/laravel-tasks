<tr>

    <td class="table-text">{{ $task->id }}</td>

    <td class="table-text">{{ $task->name }}</td>

    <td>{{ $task->description }}</td>

    <td>
            <span class="label label-default">
                {!! TaskRepo::getState($task) !!}
            </span>
    </td>

    <!-- Task Status Checkbox -->
    <td>{!!$payload['at'] ?? '' !!}</td>

    <!-- Task Edit Icon -->
    <td>
        {!! Form::open(array('class' => 'form-inline pull-right', 'method' => 'DELETE', 'route' => array('tasks.destroy', $task->id))) !!}
            @method('DELETE')
            {{ Form::button('<a href="#" class="pull-right"> <span class="fa fa-trash fa-fw" aria-hidden="true"></span>
               <span class="sr-only">Delete Task</span> </a>', ['type' => 'submit']) }}
        {!! Form::close() !!}
        <a href="{{ route('tasks.edit', $task->id) }}" class="pull-right">
            <span class="fa fa-pencil fa-fw" aria-hidden="true"></span>
            <span class="sr-only">Edit Task</span>
        </a>
    </td>
</tr>
