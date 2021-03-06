@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">

            <!-- Display Validation Errors -->
            @include('common.status')

            @if (count($tasks) > 0)

                <div id="content">
                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li class="active"><a href="#all" data-toggle="tab"><span class="fa fa-tasks" aria-hidden="true"></span> <span class="hidden-xs">All</span></a></li>
                        <li><a href="#incomplete" data-toggle="tab"><span class="fa fa-square-o" aria-hidden="true"></span> <span class="hidden-xs">Incomplete</span></a></li>
                        <li><a href="#complete" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">Complete</span></a></li>
                        <li><a href="#failed" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">failed</span></a></li>
                        <li><a href="#doing" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">doing</span></a></li>
                        <li><a href="#wont_do" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">wont_do</span></a></li>
                    </ul>
                    <div id="my-tab-content" class="tab-content">

                        @include('tasks/partials/task-tab', ['tab' => 'all', 'tasks' => $tasks, 'title' => 'All Tasks', 'status' => 'active'])
                        @include('tasks/partials/task-tab', ['tab' => 'incomplete', 'tasks' => $tasksInComplete, 'title' => 'Incomplete Tasks'])
                        @include('tasks/partials/task-tab', ['tab' => 'complete', 'tasks' => $tasksComplete, 'title' => 'Complete Tasks'])
                        @include('tasks/partials/task-tab', ['tab' => 'doing', 'tasks' => $tasksDoing, 'title' => 'Doing Tasks'])
                        @include('tasks/partials/task-tab', ['tab' => 'failed', 'tasks' => $tasksFailed, 'title' => 'Failed'])
                        @include('tasks/partials/task-tab', ['tab' => 'wont_do', 'tasks' => $tasksWont_do, 'title' => 'wont do'])

                    </div>
                </div>
            @else

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create New Task
                    </div>
                    <div class="panel-body">

                        @include('tasks.partials.create-task')

                    </div>
                </div>

            @endif

        </div>
    </div>
@endsection
