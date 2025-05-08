<div class="flex">
    @foreach($project->columns as $column)
        <div>
            <p>{{$column->name}}</p>
            @foreach($column->tasks as $task)
                <div>
                    <p>{{$task->title}}</p>
                </div>
            @endforeach
            <a href="">
                Add Task
            </a>
        </div>
    @endforeach
    <form action="{{route('columns.store', ['project' => $project->id])}}" method="post">
        @csrf
        <p>New Column</p>
        <input type="text" placeholder="Column Name" name="name">
        <button type="submit">Add</button>
    </form>
</div>




