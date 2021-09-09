@for($i=0; $i<7; $i++)
    <tr>
        <th hidden></th>
        <td style="width: 130px">
            <div class="mt-2">
                <span class="
                    @if($date[$i]['name'] == "Sun")
                    text-danger
                    @elseif($date[$i]['name'] == "Sat")
                    text-primary
                    @endif">
                    {{$date[$i]['month'].'/'.$date[$i]['day'].' ('.$date[$i]['name'].')'}}
                </span>
            </div>
        </td>
        <td>
            <div class="d-flex justify-content-between">
                <div class="row ml-2" id="row{{$i}}">
                    @foreach($schedules as $schedule)
                        @if($schedule->start_date==$date[$i]['full'])
{{--                            <div class="d-flex">--}}
{{--                                <div class="mb-2 mr-2">--}}
{{--                                </div>--}}
                            @if(($schedule->start_date == Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d') && !\Carbon\Carbon::parse($schedule->start_date.' '.$schedule->start_hour)->gte(\Carbon\Carbon::parse(Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d H:i:00')))) || $schedule->deleted_at != null)
                                    <button name="{{$schedule->start_date}}"
                                            class="bs-timepicker btn btn-secondary mr-lg-5 mb-2 mt-1 text-center "
                                            style="width: 65px; height: 40px"
                                            value=""
                                            id="{{$schedule->id}}"
                                            disabled>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                @else
                                    @if($schedule->status == 3)
                                        <button name="{{$schedule->start_date}}"
                                                class="bs-timepicker btn btn-success mr-lg-5 mb-2 mt-1 text-center"
                                                style="width: 65px; height: 40px"
                                                value="{{$schedule->id}}"
                                                id="{{$schedule->id}}"
                                                readonly>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                    @else
                                        <button name="{{$schedule->start_date}}"
                                                class="bs-timepicker btn btn-secondary mr-lg-5 mb-2 mt-1 text-center"
                                                style="width: 65px; height: 40px"
                                                value="{{$schedule->id}}"
                                                id="{{$schedule->id}}"
                                                disabled>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                    @endif
                                @endif
{{--                            </div>--}}
                        @endif
                    @endforeach
                </div>
            </div>
        </td>
    </tr>
@endfor
