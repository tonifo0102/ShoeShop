<!DOCTYPE html>
<html lang="en">
<head>
    {!! $HEAD !!}
</head>
<body>
    {!! $LOADING !!}
    {!! $NAVBAR !!}
    <main class="main-dashboard">
        <div class="dashboard-title">{{ $TITLE }}</div>
        <main class="page">{!! $PAGE !!}</main>
    </main>
</body>
</html>