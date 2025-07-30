<!DOCTYPE html>
<html lang="en">
<head>
    {!! $HEAD !!}
</head>
<body>
    {!! $LOADING !!}
    {!! $SHOW_DETAIL !!}
    {!! $HEADER !!}
    <div class="box-header-empty"></div>
    @if ($USE_BANNER)
        {!! $BANNER !!}
    @endif
    {!! $PAGE !!}
    {!! $FOOTER !!}
</body>
</html>