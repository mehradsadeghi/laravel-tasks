@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-sm-offset-1 col-sm-10">

            <!-- Display Validation Errors -->
            @include('common.status')
                <div id="content">
                    @php
                        $time = now()->diffForHumans(now()->endOfDay(), ['parts' => 2, 'join' => false, 'short' => true]);
                    @endphp
                    <h4>
                    You have {!! $time !!} the end of the day.
                    </h4>
                    <br>
                </div>
                <div id="content">
                    <ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
                        <li class="active"><a href="#all" data-toggle="tab"><span class="fa fa-tasks" aria-hidden="true"></span> <span class="hidden-xs">All</span></a></li>
                        <li><a href="#not_started" data-toggle="tab"><span class="fa fa-square-o" aria-hidden="true"></span> <span class="hidden-xs">Not started</span></a></li>
                        <li><a href="#doing" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">Doing...</span></a></li>
                        <li><a href="#done" data-toggle="tab"><span class="fa fa-check-square-o" aria-hidden="true"></span> <span class="hidden-xs">Complete</span></a></li>
                        <li><a href="#failed" data-toggle="tab"><span class="fa fa-square-o" aria-hidden="true"></span> <span class="hidden-xs">failed</span></a></li>
                        <li><a href="#skipped" data-toggle="tab"><span class="fa fa-square-o" aria-hidden="true"></span> <span class="hidden-xs">Skipped</span></a></li>
                    </ul>
                    <div id="my-tab-content" class="tab-content">

                        @widget('DefaultTaskList', ['tab' => 'not_started', 'title' => 'Not Started'])
                        @widget('TaskList', ['state' => null, 'tab' => 'all', 'title' => 'All Tasks', 'status' => 'active'])
                        @widget('TaskList', ['state' => 'done', 'tab' => 'done', 'title' => 'Done'])
                        @widget('TaskList', ['state' => 'doing', 'tab' => 'doing', 'title' => 'Doing...'])
                        @widget('TaskList', ['state' => 'failed', 'tab' => 'failed', 'title' => 'Failed'])
                        @widget('TaskList', ['state' => 'skipped', 'tab' => 'skipped', 'title' => 'Skipped'])

                    </div>
                </div>


        </div>
    </div>
@endsection
