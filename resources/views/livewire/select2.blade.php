<div>

    <div wire:ignore.self>
        <select class="form-control" id="select2" wire:model="serie">
            <option value="">Select Option</option>
            @foreach($series as $item)
                <option value="{{ $item }}">{{ $item }}</option>
            @endforeach
        </select>
    </div>

</div>

@push('scripts')

    <script>
        $(document).ready(function () {
            $('#select2').select2();
            $('#select2').on('change', function (e) {
                var data = $('#select2').select2("val");
           // @this.set('selected', data);
            });
        });
    </script>

@endpush
