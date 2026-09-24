<?php
   
    //echo json_encode($schedDetails);
    $currentDate = "";
    $currentTime = "";

    $dateStart = new DateTime($df);
    $dateEnd   = new DateTime($dt);

    $period = new DatePeriod(
        $dateStart,
        new DateInterval('P1D'),
        (clone $dateEnd)->modify('+1 day')
    );


    /*
    |--------------------------------------------------------------------------
    | TIMELINE START / END
    |--------------------------------------------------------------------------
    */

    $timelineStart = new DateTime('05:00 AM');
    $timelineEnd   = new DateTime('10:00 PM');

    $interval = new DateInterval('PT30M');

    $period_time = new DatePeriod(
        clone $timelineStart,
        $interval,
        (clone $timelineEnd)->modify('+30 minutes')
    );


    /*
    |--------------------------------------------------------------------------
    | TIMELINE INFORMATION
    |--------------------------------------------------------------------------
    */

    $firsttimeline = $timelineStart->format('H:i:s');

    $timelineColumns = 0;

    foreach ($period_time as $time) {
        $timelineColumns++;
    }


?>


<style>
        #divTblSchedDash {
        width: 100%;
        height: 450px; 
        overflow: auto; 
        position: relative; 
    }

    #divContSched{
        background-color: #fff;
        padding: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    |
    | Separate borders are required for reliable sticky columns.
    |
    */

    #divTblSchedDash table {
        border-collapse: separate;
        border-spacing: 0;
        border: 1px solid #e2dddd;
        background-color: #fff;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE CELLS
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash table th,
    #divTblSchedDash table td {

        font-size: 12px;

        border-right: 1px solid #e9e5e5;
        border-bottom: 1px solid #e9e5e5;

        padding: 0;

        text-align: center;

        box-sizing: border-box;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash table th {

        background-color: #392264;

        color: #fff;
    }


    /*
    |--------------------------------------------------------------------------
    | FREEZE HEADER
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash thead th {

        position: sticky;

        top: 0;

        z-index: 30;

        background-color: #392264;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE COLUMN
    |--------------------------------------------------------------------------
    */

    .schedule-column { 
        width: 110px; 
        min-width: 110px;
        vertical-align: top;
        padding-top: 5px;
    }


    /*
    |--------------------------------------------------------------------------
    | FREEZE SCHEDULE COLUMN
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash .schedule-column {

        position: sticky;

        left: 0;

        z-index: 40;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE BODY BACKGROUND
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash tbody .schedule-column {

        background-color: #fff;
    }


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE COLUMN
    |--------------------------------------------------------------------------
    */

    .employee-column {

        width: 200px;

        min-width: 200px;

        text-align: left !important;

        padding-left: 5px !important;
    }


    /*
    |--------------------------------------------------------------------------
    | FREEZE EMPLOYEE COLUMN
    |--------------------------------------------------------------------------
    |
    | Schedule column = 110px
    |
    | Therefore Employee Name starts at 110px.
    |
    */

    #divTblSchedDash .employee-column {

        position: sticky;

        left: 110px;

        z-index: 40;

        background-color: #fff;

        box-shadow: 2px 0 3px rgba(0, 0, 0, 0.08);
    }


    /*
    |--------------------------------------------------------------------------
    | FROZEN HEADER COLUMNS
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash thead .schedule-column,
    #divTblSchedDash thead .employee-column {

        background-color: #392264;

        z-index: 50;
    }


    /*
    |--------------------------------------------------------------------------
    | HEADER SHADOW
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash thead .employee-column {

        box-shadow: 2px 0 3px rgba(0, 0, 0, 0.15);
    }


    /*
    |--------------------------------------------------------------------------
    | EMPLOYEE NAME
    |--------------------------------------------------------------------------
    */

    .employee-name {

        width: 200px;

        min-width: 200px;

        white-space: nowrap;

        overflow: hidden;

        text-align: left;

        padding-left: 0;
    }


    /*
    |--------------------------------------------------------------------------
    | HOUR HEADER
    |--------------------------------------------------------------------------
    */

    .hour-header {

        width: <?= $px * 2 ?>px;

        min-width: <?= $px * 2 ?>px;

        height: 35px;

        white-space: nowrap;

        font-size: 12px;

        font-weight: bold;
    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE CELL CONTAINER
    |--------------------------------------------------------------------------
    */

    .timeline-cell-container {

        position: relative;

        padding: 0 !important;

        overflow: visible !important;
    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE CONTAINER
    |--------------------------------------------------------------------------
    */

    .timeline-container {

        position: relative;

        width: <?= $timelineColumns * $px ?>px;

        min-width: <?= $timelineColumns * $px ?>px;

        height: 40px;

        overflow: visible;
    }


    /*
    |--------------------------------------------------------------------------
    | TIMELINE GRID
    |--------------------------------------------------------------------------
    */

    .timeline-grid {

        position: absolute;

        left: 0;

        top: 0;

        width: <?= $timelineColumns * $px ?>px;

        height: 40px;

        display: flex;
    }


    /*
    |--------------------------------------------------------------------------
    | 30 MINUTE GRID CELL
    |--------------------------------------------------------------------------
    */

    .timeline-grid-cell {

        width: <?= $px ?>px;

        min-width: <?= $px ?>px;

        height: 40px;

        box-sizing: border-box;

        border-right: 1px solid #cac7c7;
    }


    /*
    |--------------------------------------------------------------------------
    | FLOATING SHIFT
    |--------------------------------------------------------------------------
    */

    .floating-shift {

        position: absolute;

        top: 7px;

        height: 25px;

        background: #c8e6c9;

        border: 1px solid #9ccc9c;

        border-radius: 3px;

        display: flex;

        align-items: center;

        justify-content: center;

        font-weight: bold;

        font-size: 12px;

        white-space: nowrap;

        box-sizing: border-box;

        z-index: 10;

        overflow: hidden;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE DATE
    |--------------------------------------------------------------------------
    */

    .schedule-date h5 {

        margin: 0;

        padding: 0;

        font-size: 16px;
    }


    /*
    |--------------------------------------------------------------------------
    | LAST ROW OF EACH SCHEDULE GROUP
    |--------------------------------------------------------------------------
    |
    | This replaces:
    |
    | border-bottom: 2px solid #969191
    |
    | which was previously applied to <tr>.
    |
    */

    #divTblSchedDash tbody tr.schedule-last-row > td {

        border-bottom: 2px solid #969191 !important;
    }


    /*
    |--------------------------------------------------------------------------
    | ROWSPAN SCHEDULE CELL
    |--------------------------------------------------------------------------
    |
    | The Schedule cell uses rowspan, so it is NOT part of the
    | last <tr>. Give it its own bottom border.
    |
    */

    #divTblSchedDash tbody td.schedule-date-cell {

        border-bottom: 2px solid #969191 !important;
    }


    /*
    |--------------------------------------------------------------------------
    | LAST ROW EMPLOYEE COLUMN
    |--------------------------------------------------------------------------
    |
    | Keep the sticky column shadow while also showing the
    | schedule separator.
    |
    */

    #divTblSchedDash tbody tr.schedule-last-row > td.employee-column {

        box-shadow:
            2px 0 3px rgba(0, 0, 0, 0.08);
    }


    /*
    |--------------------------------------------------------------------------
    | LAST ROW TIMELINE
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash tbody tr.schedule-last-row > td.timeline-cell-container {

        border-bottom: 2px solid #969191 !important;
    }


</style>


@if(count($schedDetails['rows'])==0)
 <hr>
<div class="m-1">
    <center>
        <div id="myAlert" class="alert alert-WARNING" role="alert"  style="height: 300px;">
            <i class="fas fa-box-open fs-1"></i> <br>
            No schedule found based on your filter!
        </div>
    </center>
</div>

@else

    <table> 
        <thead> 
            <tr>
                <th colspan="<?= ($timelineColumns+3) ?>" style="text-align:left; background-color: #0b94ca" >
                    <div style="margin:2px">
                        <input type="checkbox" id="chkInclds" onclick="return showSchedItems(this)">
                        <label for="chkInclds">don't include items without schedule?</label>

                    <!--     <button
                            type="button"
                            class="btn btn-success text-white"
                            onclick="exportShiftScheduleToExcel()">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </button> -->

                        <i class="btn btn-success fas fa-file-excel" title="Export into excel" onclick="exportShiftScheduleToExcel()"></i>

                    </div>
                </th>
            </tr>
            <tr> 
                <th class="schedule-column">Schedule</th> 
                <th class="employee-column">Department</th>  
                <th class="employee-column">Employee Name </th> 
                <?php $currentHeaderHour = "";  ?>


                @foreach($period_time as $time) 
                    <?php     $headerHour = $time->format('h A');  ?> 
                    @if($currentHeaderHour !== $headerHour) 
                        <th  class="hour-header"   colspan="2"  >{{ $time->format('h:i A') }}</th> 
                    @endif 
                    <?php  $currentHeaderHour = $headerHour;  ?>  
                @endforeach 

            </tr>

        </thead>

        <tbody>


            @foreach($schedDetails['rows'] as $row)


                <?php

                    $schedDetails2 = json_decode(  $row->schedDetails,  true  ); 
                    $scheduleDate = $row->dt; 
                    $date = new DateTime($scheduleDate);

                ?>


                @if(is_array($schedDetails2))


                    <?php 
                        $num = 0;  
                        $scheduleRowCount = count($schedDetails2); 
                    ?>


                    @foreach($schedDetails2 as $schd)


                        <?php 
                            $shiftFrom = !empty($schd['shiftFrom'])   ? $schd['shiftFrom']   : ''; 
                            $shiftName = $schd['shiftName'] ?? ''; 
                            $shiftTo = !empty($schd['shiftTo'])   ? $schd['shiftTo']  : ''; 
                            $isLastRow =   (($num + 1) == $scheduleRowCount);
                            //$data_hasno_schedule = (($shiftName=='') && (!$isLastRow)) ? 'data-sched="1"' : '';
                            $data_hasno_schedule = ($shiftName=='') ? 'data-sched="1"' : '';

                        ?>


                        <tr  class="{{ $isLastRow ? 'schedule-last-row' : '' }}"  <?= $data_hasno_schedule ?>  > 

                            @if($currentDate !== $row->dt)

                                <td class="schedule-column  schedule-date schedule-date-cell" rowspan="{{ $scheduleRowCount }}" >

                                    <center> 
                                        <h5> {{ $date->format('l') }} </h5> 
                                        {{ $scheduleDate }} 
                                    </center> 
                                </td>

                            @endif


                            <?php   $currentDate = $row->dt;   ?> 
                            
                            <td class="employee-column"> 
                                <div class="employee-name"> {{ $schd['departmentName'] ?? '' }} </div> 
                            </td>
                            <td class="employee-column"> 
                                <div class="employee-name"> {{ $schd['empName'] ?? '' }} </div> 
                            </td>

                            <td class="timeline-cell-container"   colspan="{{ $timelineColumns }}" > 
                                <div class="timeline-container"  data-timeline-start="{{ $firsttimeline }}" > 
                                    <div class="timeline-grid">


                                        <?php 
                                            $period_time_row = new DatePeriod(
                                                clone $timelineStart,
                                                new DateInterval('PT30M'),
                                                (clone $timelineEnd)->modify('+30 minutes')
                                            ); 
                                        ?> 

                                        @foreach($period_time_row as $time) 
                                            <div class="timeline-grid-cell"></div> 
                                        @endforeach


                                    </div>


                                    @if($shiftFrom && $shiftTo)

                                        <div class="span-timeline"   data-sfrom="{{ $shiftFrom }}"  data-sto="{{ $shiftTo }}"  data-sfnam="{{ $shiftName }}"></div>

                                    @endif 

                                </div> 
                            </td> 
                        </tr>


                        <?php  $num++;  ?>


                    @endforeach 
                @endif 
            @endforeach  

        </tbody> 
    </table>
@endif

<script>
    
    /*
    |--------------------------------------------------------------------------
    | TIMELINE VARIABLES
    |--------------------------------------------------------------------------
    */

    var spanTimeline =
        document.querySelectorAll('.span-timeline');


    var firsttimeline =
        '{{ $firsttimeline }}';


    var px =
        {{ $px }};


    var pxPerHour =
        px * 2;


    /*
    |--------------------------------------------------------------------------
    | CREATE SHIFT TIMELINES
    |--------------------------------------------------------------------------
    */

    spanTimeline.forEach(element => {


        var shiftFrom =
            element.dataset.sfrom;


        var shiftTo =
            element.dataset.sto;


        var shiftName =
            element.dataset.sfnam;


        /*
        |--------------------------------------------------------------------------
        | CALCULATE LEFT POSITION
        |--------------------------------------------------------------------------
        */

        let leftHours =
            getHoursGap(
                firsttimeline,
                shiftFrom
            );


        /*
        |--------------------------------------------------------------------------
        | CALCULATE SHIFT DURATION
        |--------------------------------------------------------------------------
        */

        let shiftHours =
            getHoursGap(
                shiftFrom,
                shiftTo
            );


        /*
        |--------------------------------------------------------------------------
        | OVERNIGHT SHIFT
        |--------------------------------------------------------------------------
        */

        if (shiftHours < 0) {

            shiftHours += 24;

        }


        /*
        |--------------------------------------------------------------------------
        | CONVERT HOURS TO PIXELS
        |--------------------------------------------------------------------------
        */

        var leftPx =
            (leftHours * pxPerHour) + px;


        var widthPx =
            shiftHours * pxPerHour;


        /*
        |--------------------------------------------------------------------------
        | FORMAT TIME
        |--------------------------------------------------------------------------
        */

        var to12HourFrom =
            to12Hour(shiftFrom);


        var to12HourTo =
            to12Hour(shiftTo);


        /*
        |--------------------------------------------------------------------------
        | DEFAULT SHIFT COLOR
        |--------------------------------------------------------------------------
        */

        let clr =
            '#a4cec9';


        let shiftLable =
            `${to12HourFrom} to ${to12HourTo} (${shiftName})`;


        /*
        |--------------------------------------------------------------------------
        | MORNING SHIFT
        |--------------------------------------------------------------------------
        */

        if (
            to12HourFrom.slice(-2) == "AM" &&
            to12HourTo.slice(-2) == "PM"
        ) {

            clr =
                '<?= $morningShits ?>';

        }


        /*
        |--------------------------------------------------------------------------
        | AFTERNOON SHIFT
        |--------------------------------------------------------------------------
        */

        if (
            to12HourFrom.slice(-2) == "PM" &&
            to12HourTo.slice(-2) == "PM"
        ) {

            clr =
                '<?= $afternoonShits ?>';

        }

 
        /*
        |--------------------------------------------------------------------------
        | OVERNIGHT SHIFT
        |--------------------------------------------------------------------------
        */

        if (
            to12HourFrom.slice(-2) == "PM" &&
            to12HourTo.slice(-2) == "AM"
        ) {

            clr =
                '<?= $nightShits ?>';

        }


        /*
        |--------------------------------------------------------------------------
        | REST DAY
        |--------------------------------------------------------------------------
        */

        if (shiftName == "RESTDAY") {

            clr =
                '<?= $dayoff ?>';

            shiftLable =
                shiftName;

        }


        /*
        |--------------------------------------------------------------------------
        | CREATE SHIFT ELEMENT
        |--------------------------------------------------------------------------
        */

        element.innerHTML = `

            <div
                class="floating-shift"
                style="
                    left: ${leftPx}px;
                    width: ${widthPx}px;
                    background-color: ${clr};
                    font-size: 10px;
                "
            >

                ${shiftLable}

            </div>

        `;

    });


    /*
    |--------------------------------------------------------------------------
    | CONVERT 24-HOUR TIME TO 12-HOUR TIME
    |--------------------------------------------------------------------------
    */

    function to12Hour(time) {


        var [hour, minute, second] =
            time.split(':').map(Number);


        var period =
            hour >= 12
                ? 'PM'
                : 'AM';


        var h =
            hour % 12 || 12;


        return `${h}:${String(minute).padStart(2, '0')} ${period}`;

    }


    /*
    |--------------------------------------------------------------------------
    | GET HOURS GAP
    |--------------------------------------------------------------------------
    */

    function getHoursGap(start, end) {


        var startParts =
            start.split(':').map(Number);


        var endParts =
            end.split(':').map(Number);


        var startSeconds =
            (startParts[0] * 3600) +
            (startParts[1] * 60) +
            (startParts[2] || 0);


        var endSeconds =
            (endParts[0] * 3600) +
            (endParts[1] * 60) +
            (endParts[2] || 0);


        return (
            (endSeconds - startSeconds) / 3600
        );

    }


</script>
