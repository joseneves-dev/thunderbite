@extends('backstage.templates.backstage')

@section('tools')
    @if( auth()->user()->hasLevel('admin') || auth()->user()->hasLevel('download') )
        <form method="POST" action="{{ route('backstage.games.export') }}" id="exportForm">
            @csrf
            <div class="grid grid-cols-4 gap-4 items-start pt-5">
                <div class="col-start-2 col-span-3">
                    <button type="button" class="submit-button" id="export-button">
                        Export CSV
                    </button>
                </div>
            </div>
            <input type="hidden" name="account" id="export_account">
            <input type="hidden" name="prize_id" id="export_prizeId">
            <input type="hidden" name="start_date" id="export_startDate">
            <input type="hidden" name="end_date" id="export_endDate">
        </form>
    @endif 
@endsection

@section('content')
    <div id="card" class="bg-white shadow-lg mx-auto rounded-b-lg">
        <div class="px-10 pt-4 pb-8">
            @livewire('backstage.game-table')
          
        </div>
    </div>
@endsection
@push('js')
<script>
 document.getElementById('export-button').addEventListener('click', function() {
        Livewire.dispatch('exportGamesTable')
        Livewire.on('exportData', function (filters) {

            document.getElementById('export_account').value = filters[0].account != null ? filters[0].account : '';
            document.getElementById('export_prizeId').value = filters[0].prizeId != null ? filters[0].prizeId : '';
            document.getElementById('export_startDate').value = filters[0].startDate != null ? filters[0].startDate : '';
            document.getElementById('export_endDate').value = filters[0].endDate != null ? filters[0].endDate : '';

            document.getElementById('exportForm').submit();
        });
    });
</script>
@endpush