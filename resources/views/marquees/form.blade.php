<script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
<link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
<div class="form-group">
    <label for="name">標題*(50字內)</label>
    <input type="text" name="title" value="{{ $title }}" class="form-control" required maxlength="50">
</div>
<div class="form-group">
    <label for="url">開始日期*</label>
    <input id="start_date" name="start_date" value="{{ $start_date }}" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" width="250">
    <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
    <script>
        $('#start_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            locale: 'zh-TW',
        });
    </script>
</div>
<div class="form-group">
    <label for="url">結束日期*</label>
    <input id="stop_date" name="stop_date" value="{{ $stop_date }}" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" width="250">
    <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
    <script>
        $('#stop_date').datepicker({
            uiLibrary: 'bootstrap4',
            format: 'yyyy-mm-dd',
            locale: 'zh-TW',
        });
    </script>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-primary btn-sm" onclick="return confirm('確定儲存嗎？')">
        <i class="fas fa-save"></i> 儲存設定
    </button>
</div>
