<div class="modal hide modal-center" id="{{ $MODAL }}">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">{{ $TITLE }}</div>
            <div class="modal-close" id="close-modal"><i class="fa fa-remove"></i></div>
        </div>
        <div class="modal-body">
            {!! $BODY !!}
        </div>
        <div class="modal-footer">
            @foreach ($FOOTER as $BUTTON)
                <button class="{{ $BUTTON['class'] }}" {{ $BUTTON['attr'] }}>{{ $BUTTON['text'] }}</button>
            @endforeach
        </div>
    </div>
</div>