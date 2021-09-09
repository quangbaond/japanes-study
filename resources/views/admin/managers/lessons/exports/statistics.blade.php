<style>
    table, th, td {
        border: 2px solid black;
        border-collapse: collapse;
    }
</style>

<div style="justify-content: center; display: flex"><h2>講師の統計表</h2></div>
<div style="margin-left: 10px">
        <p>時間：{{$date_from}} 〜 {{$date_to}}</p>
</div>
<br>
<table>
    <thead>
    <tr>
        <th style="font-weight: bold">講師ID</th>
        <th style="font-weight: bold">講師ニックネーム</th>
        <th style="font-weight: bold">講師メールアドレス</th>
        <th style="font-weight: bold">レッスン数</th>
        <th style="font-weight: bold">コイン数</th>
    </tr>
    </thead>
    <tbody>
    @foreach($statistics as $statistic)
        <tr>
            <td style="text-align: left">{{$statistic->teacher_id}}</td>
            <td>{{$statistic->teacher_nickname}}</td>
            <td>{{$statistic->teacher_email}}</td>
            <td style="text-align: left">{{$statistic->total_lessons}}</td>
            <td style="text-align: left">{{$statistic->total_coins}}</td>
        </tr>
    @endforeach
    </tbody>
</table>
