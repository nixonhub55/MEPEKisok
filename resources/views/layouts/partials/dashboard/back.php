@extends('layouts.admin')

@section('content')

<?php

    /*
    |--------------------------------------------------------------------------
    | TIMELINE SETTINGS
    |--------------------------------------------------------------------------
    |
    | $px = width of ONE 30-minute column.
    |
    | Therefore:
    |
    | 30 minutes = 30px
    | 1 hour     = 60px
    |
    */

    $px = 30;

    $currentDate = "";
    $currentTime = "";


    /*
    |--------------------------------------------------------------------------
    | DATE PERIOD
    |--------------------------------------------------------------------------
    */

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
    |
    | ALWAYS start at 05:00 AM.
    |
    */

    $timelineStart = new DateTime('05:00 AM'); 
    $timelineEnd = new DateTime('10:00 PM');


    /*
    |--------------------------------------------------------------------------
    | 30-MINUTE INTERVAL
    |--------------------------------------------------------------------------
    */

    $interval = new DateInterval('PT30M');


    /*
    |--------------------------------------------------------------------------
    | HEADER / TIMELINE PERIOD
    |--------------------------------------------------------------------------
    */

    $period_time = new DatePeriod(
        clone $timelineStart,
        $interval,
        (clone $timelineEnd)->modify('+30 minutes')
    );


    /*
    |--------------------------------------------------------------------------
    | FIRST TIMELINE
    |--------------------------------------------------------------------------
    */

    $firsttimeline = $timelineStart->format('H:i:s');


    /*
    |--------------------------------------------------------------------------
    | COUNT TIMELINE COLUMNS
    |--------------------------------------------------------------------------
    */

    $timelineColumns = 0;

    foreach ($period_time as $time) {
        $timelineColumns++;
    }

?>

<style>

    /*
    |--------------------------------------------------------------------------
    | MAIN CONTAINER
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash {
        width: 100%;
        height: 500px;
        overflow: auto;
    }


    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    #divTblSchedDash table {
        border-collapse: collapse;
    }


    #divTblSchedDash table th,
    #divTblSchedDash table td {
        font-size: 12px;
        border: 1px solid #cac7c7;
        padding: 0;
        text-align: center;
    }


    /*
    |--------------------------------------------------------------------------
    | SCHEDULE COLUMN
    |--------------------------------------------------------------------------
    */

    .schedule-column {
        width: 110px;
        min-width: 110px;
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


    .employee-name {
        width: 200px;
        min-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-align: left;
    }


    /*
    |--------------------------------------------------------------------------
    | HOUR HEADER
    |--------------------------------------------------------------------------
    |
    | Each hour contains TWO 30-minute columns.
    |
    | 30px + 30px = 60px
    |
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
    | TIMELINE CELL
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
    | EACH 30-MINUTE CELL
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
    | SHIFT BAR
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
    | DATE
    |--------------------------------------------------------------------------
    */

    .schedule-date h5 {
        margin: 0;
        padding: 0;
    }

</style>


<div class="m-2">

    <div id="divTblSchedDash">

        <table>

            <!-- ========================================================= -->
            <!-- HEADER -->
            <!-- ========================================================= -->

            <thead>

                <tr>

                    <th class="schedule-column">
                        Schedule
                    </th>


                    <th class="employee-column">
                        Employee Name
                    </th>


                    <?php

                        /*
                        |--------------------------------------------------------------------------
                        | Prevent duplicate hour headers
                        |--------------------------------------------------------------------------
                        */

                        $currentHeaderHour = "";

                    ?>


                    @foreach($period_time as $time)

                        <?php

                            $headerHour = $time->format('h A');

                        ?>


                        @if($currentHeaderHour !== $headerHour)

                            <th
                                class="hour-header"
                                colspan="2"
                            >
                                {{ $time->format('h:i A') }}
                            </th>

                        @endif


                        <?php

                            $currentHeaderHour = $headerHour;

                        ?>

                    @endforeach

                </tr>

            </thead>


            <!-- ========================================================= -->
            <!-- BODY -->
            <!-- ========================================================= -->

            <tbody>


                @foreach($schedDetails['rows'] as $row)

                    <?php

                        $schedDetails2 = json_decode(
                            $row->schedDetails,
                            true
                        );


                        $scheduleDate = $row->dt;

                        $date = new DateTime($scheduleDate);

                    ?>


                    @if(is_array($schedDetails2))


                        @foreach($schedDetails2 as $schd)


                            <?php

                                /*
                                |--------------------------------------------------------------------------
                                | SHIFT TIMES
                                |--------------------------------------------------------------------------
                                */

                                $shiftFrom = !empty($schd['shiftFrom'])
                                    ? $schd['shiftFrom']
                                    : '';


                                $shiftTo = !empty($schd['shiftTo'])
                                    ? $schd['shiftTo']
                                    : '';

                            ?>


                            <tr>


                                <!-- ========================================= -->
                                <!-- DATE -->
                                <!-- ========================================= -->


                                @if($currentDate !== $row->dt)

                                    <td
                                        class="schedule-column schedule-date"
                                        rowspan="{{ count($schedDetails2) }}"
                                    >

                                        <center>

                                            <h5>
                                                {{ $date->format('l') }}
                                            </h5>

                                            {{ $scheduleDate }}

                                        </center>

                                    </td>

                                @endif


                                <?php

                                    $currentDate = $row->dt;

                                ?>


                                <!-- ========================================= -->
                                <!-- EMPLOYEE -->
                                <!-- ========================================= -->


                                <td class="employee-column">

                                    <div class="employee-name">

                                        {{ $schd['empName'] ?? '' }}

                                    </div>

                                </td>


                                <!-- ========================================= -->
                                <!-- TIMELINE -->
                                <!-- ========================================= -->


                                <td
                                    class="timeline-cell-container"
                                    colspan="{{ $timelineColumns }}"
                                >


                                    <div
                                        class="timeline-container"
                                        data-timeline-start="{{ $firsttimeline }}"
                                    >


                                        <!-- ================================= -->
                                        <!-- GRID -->
                                        <!-- ================================= -->


                                        <div class="timeline-grid">


                                            <?php

                                                /*
                                                |--------------------------------------------------------------------------
                                                | Create the 30-minute grid
                                                |--------------------------------------------------------------------------
                                                */

                                                $period_time_row = new DatePeriod(
                                                    clone $timelineStart,
                                                    new DateInterval('PT30M'),
                                                    (clone $timelineEnd)->modify('+30 minutes')
                                                );

                                            ?>


                                            @foreach($period_time_row as $time)

                                                <div
                                                    class="timeline-grid-cell"
                                                ></div>

                                            @endforeach


                                        </div>


                                        <!-- ================================= -->
                                        <!-- SHIFT -->
                                        <!-- ================================= -->


                                        @if($shiftFrom && $shiftTo)

                                            <div
                                                class="span-timeline"
                                                data-sfrom="{{ $shiftFrom }}"
                                                data-sto="{{ $shiftTo }}"
                                            ></div>

                                        @endif


                                    </div>

                                </td>


                            </tr>


                        @endforeach

                    @endif


                @endforeach


            </tbody>

        </table>

    </div>

</div>


<script>


    /*
    |--------------------------------------------------------------------------
    | TIMELINE SETTINGS
    |--------------------------------------------------------------------------
    */


    const spanTimeline =
        document.querySelectorAll('.span-timeline');


    /*
    |--------------------------------------------------------------------------
    | Timeline starts at 05:00 AM
    |--------------------------------------------------------------------------
    */


    const firsttimeline =
        '{{ $firsttimeline }}';


    /*
    |--------------------------------------------------------------------------
    | Width of ONE 30-minute column
    |--------------------------------------------------------------------------
    */


    const px =
        {{ $px }};


    /*
    |--------------------------------------------------------------------------
    | Width of ONE HOUR
    |--------------------------------------------------------------------------
    |
    | Two 30-minute columns:
    |
    | 30px + 30px = 60px
    |
    */


    const pxPerHour =
        px * 2;


    /*
    |--------------------------------------------------------------------------
    | CREATE SHIFT BARS
    |--------------------------------------------------------------------------
    */


    spanTimeline.forEach(element => {


        const shiftFrom =
            element.dataset.sfrom;


        const shiftTo =
            element.dataset.sto;


        /*
        |--------------------------------------------------------------------------
        | Calculate position
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Timeline start = 05:00
        | Shift start     = 13:00
        |
        | Difference      = 8 hours
        |
        | 8 × 60px = 480px
        |
        */


        let leftHours =
            getHoursGap(
                firsttimeline,
                shiftFrom
            );


        /*
        |--------------------------------------------------------------------------
        | Calculate duration
        |--------------------------------------------------------------------------
        */


        let shiftHours =
            getHoursGap(
                shiftFrom,
                shiftTo
            );


        /*
        |--------------------------------------------------------------------------
        | Overnight shift
        |--------------------------------------------------------------------------
        */


        if (shiftHours < 0) {

            shiftHours += 24;

        }


        /*
        |--------------------------------------------------------------------------
        | Convert hours to pixels
        |--------------------------------------------------------------------------
        */


        const leftPx =
            leftHours * pxPerHour;


        const widthPx =
            shiftHours * pxPerHour;


        /*
        |--------------------------------------------------------------------------
        | CREATE SHIFT
        |--------------------------------------------------------------------------
        */


        element.innerHTML = `

            <div
                class="floating-shift"
                style="
                    left: ${leftPx}px;
                    width: ${widthPx}px;
                "
            >
                ${shiftFrom}
            </div>

        `;


    });


    /*
    |--------------------------------------------------------------------------
    | GET HOURS GAP
    |--------------------------------------------------------------------------
    */


    function getHoursGap(start, end) {


        const startParts =
            start.split(':').map(Number);


        const endParts =
            end.split(':').map(Number);


        const startSeconds =
            (startParts[0] * 3600) +
            (startParts[1] * 60) +
            (startParts[2] || 0);


        const endSeconds =
            (endParts[0] * 3600) +
            (endParts[1] * 60) +
            (endParts[2] || 0);


        return (
            (endSeconds - startSeconds) / 3600
        );


    }


</script>

@endsection