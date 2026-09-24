@foreach($payrollPeriodDetails['rows'] as $row)
    <div id="payrollDateRange" class="searchable-div"
        onclick="return setPayrollDateRange(
            this,
            '{{ $row->code }}',
            '{{ $row->lineId }}',
            '{{ $row->payrollPeriodFrom }}',
            '{{ $row->payrollPeriodTo }}', 
            '{{ $row->payrollPeriodScheduleTaggingLocked }}'
        )">

        @if($row->payrollPeriodFrom == $payrollPeriodFrom && $row->payrollPeriodTo == $payrollPeriodTo)
            <script>
                    setPayrollDateRange(
                    document.getElementById('payrollDateRange'),
                    '{{ $row->code }}',
                    '{{ $row->lineId }}',
                    '{{ $row->payrollPeriodFrom }}',
                    '{{ $row->payrollPeriodTo }}',
                    '{{ $row->payrollPeriodScheduleTaggingLocked }}'
                );
            </script>
        @endif

      <div><u>{{$row->payrollPeriodMonths}}</u></div>  
      <b>From:</b> {{ $row->payrollPeriodFrom }}
        <b>To:</b> {{ $row->payrollPeriodTo }}
    </div>

@endforeach

