<form action="{{route('admin.calendar.createEvent')}}" method="post">
    @csrf
    <button type="submit">Submit</button>
</form>
