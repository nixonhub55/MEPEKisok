@extends('layouts.admin')

@section('content')
<!-- <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script> -->
 <script src="https://cdn.jsdelivr.net/npm/exceljs@4.4.0/dist/exceljs.min.js"></script>
<script>
    var departments = [];
    var payrollGroups = [];
    var shifts = [];
    var staffStatus = []; 
</script>

<?php

    $morningShits = "#8fcad4";
    $afternoonShits = "#e2babf"; 
    $nightShits = "#ebd835";
    $dayoff = "#dee3e4";  
    $px = 30; 
    //echo json_encode($departments);
?>



<style>


    /*
    |--------------------------------------------------------------------------
    | MAIN SCROLL CONTAINER
    |--------------------------------------------------------------------------
    */

    .shift-legend{
        width: 30px;
        height: 30px;
        border: 1px solid #c0bcbc;
    }


</style>


<div id="divContSched" class="m-2">

    <!-- REPORT HEADER -->
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <h1>Shift Schedule Report</h1>   
                </div>
                <div class="col-md-7">
                    <div class="form-control container">
                        <div class="row">
                            <div class="col-md-4">
                                <i class="fas fa-building"></i> Department 
                                <div class="custom-dropdown" id="ddlDepartment"> 
                                    <button type="button" class="dropdown-button"> No selected </button> 
                                    <div class="dropdown-content">  
                                        <label class="dropdown-item select-all-row">  <input type="checkbox" class="select-all">  Select All  </label> 
                                        @foreach($departments['rows'] as $row)  
                                            <label class="dropdown-item">
                                                <input type="checkbox"  class="item-checkbox"    value="{{$row->departmentCode}}" data-txt="{{$row->departmentName}}">  {{$row->departmentName}}
                                            </label> 
                                        @endforeach 
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <i class="fas fa-users"></i> Payroll Group 
                                <div class="custom-dropdown" id="ddlPayrollGroup"> 
                                    <button type="button" class="dropdown-button"> No selected </button> 
                                    <div class="dropdown-content">  
                                        <label class="dropdown-item select-all-row">  <input type="checkbox" class="select-all">  Select All  </label> 
                                        @foreach($payrollPeriod['rows'] as $row)  
                                            <label class="dropdown-item">
                                                <input type="checkbox"  class="item-checkbox"    value="{{$row->payrollPeriodId}}" data-txt="{{$row->payrollPeriodId}}">  {{$row->payrollPeriodId}} - {{$row->payrollPeriodType}}
                                            </label> 
                                        @endforeach 
                                    </div>
                                </div>
                            </div> 
                            <div class="col-md-4">
                                <i class="fas fa-sync"></i> Shits
                                <div class="custom-dropdown" id="ddlShits"> 
                                    <button type="button" class="dropdown-button"> No selected </button> 
                                    <div class="dropdown-content">  
                                        <label class="dropdown-item select-all-row">  <input type="checkbox" class="select-all">  Select All  </label> 
                                        <label class="dropdown-item">
                                            <input type="checkbox"  class="item-checkbox"    value="-1"  data-txt="Rest Day">Rest Day
                                        </label> 
                                        @foreach($shifts['rows'] as $row)  
                                            <label class="dropdown-item">
                                                <input type="checkbox"  class="item-checkbox"    value="{{$row->code}}"  data-txt="{{$row->shiftName}}">{{$row->shiftName}}
                                            </label> 
                                        @endforeach 
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-4 mt-3">
                                <i class="fas fa-check"></i> Status
                                <div class="custom-dropdown" id="ddlStaffStatus"> 
                                    <button type="button" class="dropdown-button"> No selected </button> 
                                    <div class="dropdown-content">  
                                        <label class="dropdown-item select-all-row">  <input type="checkbox" class="select-all">  Select All  </label> 
                                        <label class="dropdown-item">
                                            <input type="checkbox"  class="item-checkbox"    value="1"  data-txt="Active">Active
                                        </label>  
                                        <label class="dropdown-item">
                                            <input type="checkbox"  class="item-checkbox"    value="0"  data-txt="In-Active">In-Active
                                        </label>  
                                    </div>
                                </div> 
                            </div>
                            <div class="col-md-3 mt-3">
                                <i class="fas fa-arrow-left"></i> Date From 
                                <input id="txtDF" type="date" class="form-control">
                            </div>
                            <div class="col-md-3 mt-3">
                                <i class="fas fa-arrow-right"></i> Date To
                                <input id="txtDT" type="date" class="form-control">
                            </div>
                            <div class="col-md-2" style="margin-top: 32px;">
                                <button class="btn btn-info text-white" onclick="return loadStaffSchedules()">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- REPORT HEADER -->

    <!-- SHITS LEGENDS --> 
        <div class="d-flex justify-content-start"> 
            <div class="d-flex justify-content-start">
                <div class="m-2 shift-legend" style="background-color: {{$morningShits}};"></div><div style="margin-top: 15px;">Morning Shift</div>
            </div> 
            <div class="d-flex justify-content-start">
                <div class="m-2 shift-legend" style="background-color: {{$afternoonShits}};"></div><div style="margin-top: 15px;">Afternoon Shift</div>
            </div>
            <div class="d-flex justify-content-start">
                <div class="m-2 shift-legend" style="background-color: {{$nightShits}};"></div><div style="margin-top: 15px;">Night Shift</div>
            </div>
            <div class="d-flex justify-content-start">
                <div class="m-2 shift-legend" style="background-color: {{$dayoff}};"></div><div style="margin-top: 15px;">Day off / Rest Day</div>
            </div>
            <div class="d-flex justify-content-start">
                <div class="m-2 shift-legend" style="background-color: #fff;"></div><div style="margin-top: 15px;">No Schedule</div>
            </div> 
        </div>
    <!-- SHITS LEGENDS -->

    <div id="divTblSchedDash"></div>

</div>

<!-- dropdown checkbox -->
<script>
 
    document.querySelectorAll('.custom-dropdown').forEach(function(dropdown) { 
        const button = dropdown.querySelector('.dropdown-button');
        const menu = dropdown.querySelector('.dropdown-content'); 
        const selectAll = dropdown.querySelector('.select-all');
        const items = dropdown.querySelectorAll('.item-checkbox');
 

        button.addEventListener('click', function(event) { 
            event.stopPropagation(); 
            document.querySelectorAll('.dropdown-content').forEach(function(otherMenu) { 
                if (otherMenu !== menu) {
                    otherMenu.classList.remove('show');
                } 
            }); 
            menu.classList.toggle('show'); 
        });
 
        selectAll.addEventListener('change', function() { 
            items.forEach(function(item) { 
                item.checked = selectAll.checked; 
            }); 
            updateButtonText(); 
        });


       
        items.forEach(function(item) { 
            item.addEventListener('change', function() { 
                updateSelectAll(); 
                updateButtonText();

            });

        });


        

        function updateSelectAll() { 
            const checkedItems =  dropdown.querySelectorAll('.item-checkbox:checked'); 
            if (checkedItems.length === items.length) {   selectAll.checked = true;  } 
            else {    selectAll.checked = false;  } 
        }
 

        function updateButtonText() {

            const checkedItems = dropdown.querySelectorAll('.item-checkbox:checked'); 
            if (checkedItems.length === 0) { 
                button.textContent = 'Select Options';    return;

            }
 

            if (checkedItems.length === items.length) { 
                button.textContent = 'All Selected'; 
                return; 
            }

  

            const selectedValues =   Array.from(checkedItems).map(function(item) {  return item.dataset.txt /*  return item.value;  */  }); 
            //button.textContent = selectedValues.join(', ');
            button.textContent = `${checkedItems.length} selected`

        }

    });
 

    document.addEventListener('click', function(event) { 
        if (!event.target.closest('.custom-dropdown')) { 
            document.querySelectorAll('.dropdown-content').forEach(function(menu) { 
                menu.classList.remove('show'); 
            }); 
        } 
    });

</script>

<script> 

    async function loadStaffSchedules() {   
       
        var df = document.getElementById('txtDF').value;
        var dt = document.getElementById('txtDT').value;
        
        /* var ddlDept = document.getElementById('ddlDept').value;
        var ddlPayrollPeriod = document.getElementById('ddlPayrollPeriod').value;
        var ddlShifts = document.getElementById('ddlShifts').value;
        var ddlActive = document.getElementById('ddlActive').value; */

        getSelectedValues('ddlDepartment'); 
        getSelectedValues('ddlPayrollGroup'); 
        getSelectedValues('ddlShits'); 
        getSelectedValues('ddlStaffStatus'); 

        var opts =({
            "departments" : departments,
            "payrollGroups" : payrollGroups,
            "shifts" : shifts,
            "activeOnly" : staffStatus
        });
        //departments/payrollGroups/shifts/staffStatus

        var container = document.getElementById('divTblSchedDash');
        var formData = new FormData();    
        formData.append('mode',0);    

        formData.append('df',df);   
        formData.append('dt',dt);   
        formData.append('r_Opts',JSON.stringify(opts));   

        formData.append('morningShits','<?= $morningShits ?>');   
        formData.append('afternoonShits','<?= $afternoonShits ?>');   
        formData.append('dayoff','<?= $dayoff ?>');   
        formData.append('nightShits','<?= $nightShits ?>');    
        formData.append('px','<?= $px ?>');   


        GlovalHTMLObjLoading(1,'divTblSchedDash');
        var response = await call_page_into_div(formData,'{{url("/loadStaffSchedules")}}',container);   
    }

    function getSelectedValues(elemId){
        var ddlContainer = document.getElementById(elemId);
        var checkboxContainer = ddlContainer.getElementsByClassName('dropdown-content')[0];
        var checkboxImtes = checkboxContainer.getElementsByClassName('item-checkbox');
        ///departments/payrollGroups/shifts/staffStatus

        if(elemId=="ddlDepartment"){  departments = [];}
        if(elemId=="ddlPayrollGroup"){  payrollGroups = [];}
        if(elemId=="ddlShits"){ shifts = [];}
        if(elemId=="ddlStaffStatus"){ staffStatus = [];}

        Array.from(checkboxImtes).forEach(function(item) {
            if(item.checked){ 
                if(elemId=="ddlDepartment"){departments.push(item.value);   }
                if(elemId=="ddlPayrollGroup"){payrollGroups.push(item.value);   }
                if(elemId=="ddlShits"){shifts.push(item.value);   }
                if(elemId=="ddlStaffStatus"){staffStatus.push(item.value);   }
            }
        });
    }


    function showSchedItems(elem){
        var isShow = (elem.checked) ? 'none' : 'table-row';
        var  data_sched = document.querySelectorAll('tr[data-sched]');
        data_sched.forEach(tr => {
            //console.log(tr);
            tr.style.display = isShow;
        });
    }

  /*   function checkParameters(){
        getSelectedValues('ddlDepartment'); 
        getSelectedValues('ddlPayrollGroup'); 
        getSelectedValues('ddlShits'); 
        getSelectedValues('ddlStaffStatus'); 
        console.log('DEPARTMENTS:'+departments); 
        console.log('PAYROLL:'+payrollGroups);
        console.log('SHIFTS:'+shifts);
        console.log('STAFF STATUS:'+staffStatus);

    }
 */

    // CLEAN CHECKED CHECKBOX
    var listOfCheckbox = document.getElementsByClassName('item-checkbox');
    Array.from(listOfCheckbox).forEach(function(item) {
        item.checked = false;
    }); 

    loadStaffSchedules();

</script>


<!-- EXTRACT TO EXCEL -->
 <script>
     async function exportShiftScheduleToExcel() {

    // =========================================================
    // FILTER PARAMETERS
    // =========================================================

    function getSelectedTexts(elemId) {

        const container = document.getElementById(elemId);

        if (!container) {
            return [];
        }

        const checked =
            container.querySelectorAll('.item-checkbox:checked');

        return Array.from(checked).map(function (item) {
            return item.dataset.txt || item.value;
        });
    }


    const departments =
        getSelectedTexts('ddlDepartment');

    const payrollGroups =
        getSelectedTexts('ddlPayrollGroup');

    const shifts =
        getSelectedTexts('ddlShits');

    const activeOnly =
        getSelectedTexts('ddlStaffStatus');

    const dateFrom =
        document.getElementById('txtDF')?.value || '';

    const dateTo =
        document.getElementById('txtDT')?.value || '';


    // =========================================================
    // COLORS
    // Same colors as your PHP report
    // =========================================================

    const COLORS = {

        morning: '8FCAD4',

        afternoon: 'E2BABF',

        night: 'EBD835',

        rest: 'DEE3E4',

        noSchedule: 'FFFFFF'

    };


    // =========================================================
    // TIMELINE
    //
    // Your PHP timeline:
    // 05:00 AM -> 10:00 PM
    // 30 minute intervals
    // =========================================================

    const timelineStartMinutes = 5 * 60;   // 05:00 AM
    const timelineEndMinutes = 22 * 60;    // 10:00 PM
    const intervalMinutes = 30;


    const timeline = [];

    for (
        let minutes = timelineStartMinutes;
        minutes <= timelineEndMinutes;
        minutes += intervalMinutes
    ) {

        timeline.push(minutes);

    }


    // =========================================================
    // TIME FORMAT
    // =========================================================

    function formatTime(minutes) {

        let hour =
            Math.floor(minutes / 60);

        let minute =
            minutes % 60;

        const period =
            hour >= 12 ? 'PM' : 'AM';

        let displayHour =
            hour % 12;

        if (displayHour === 0) {
            displayHour = 12;
        }

        return (
            displayHour +
            ':' +
            String(minute).padStart(2, '0') +
            ' ' +
            period
        );
    }


    function timeToMinutes(time) {

        if (!time) {
            return null;
        }

        const parts =
            time.split(':').map(Number);

        return (
            parts[0] * 60 +
            parts[1]
        );
    }


    // =========================================================
    // DETERMINE SHIFT COLOR
    // =========================================================

    function getShiftColor(shiftFrom, shiftTo, shiftName) {

        if (!shiftName) {
            return COLORS.noSchedule;
        }


        if (
            shiftName.toUpperCase() === 'RESTDAY'
        ) {

            return COLORS.rest;

        }


        const fromMinutes =
            timeToMinutes(shiftFrom);

        const toMinutes =
            timeToMinutes(shiftTo);


        if (
            fromMinutes === null ||
            toMinutes === null
        ) {

            return COLORS.noSchedule;

        }


        // PM -> AM = NIGHT SHIFT

        if (
            fromMinutes >= 12 * 60 &&
            toMinutes < 12 * 60
        ) {

            return COLORS.night;

        }


        // AM -> PM = MORNING SHIFT

        if (
            fromMinutes < 12 * 60 &&
            toMinutes >= 12 * 60
        ) {

            return COLORS.morning;

        }


        // PM -> PM = AFTERNOON SHIFT

        if (
            fromMinutes >= 12 * 60 &&
            toMinutes >= 12 * 60
        ) {

            return COLORS.afternoon;

        }


        // Default

        return COLORS.morning;
    }


    // =========================================================
    // GET TABLE
    // =========================================================

    const container =
        document.getElementById('divTblSchedDash');

    if (!container) {

        alert('Schedule table was not found.');

        return;

    }


    const table =
        container.querySelector('table');

    if (!table) {

        alert('No schedule found.');

        return;

    }


    const rows =
        table.querySelectorAll('tbody tr');


    if (!rows.length) {

        alert('No schedule rows found.');

        return;

    }


    // =========================================================
    // CREATE WORKBOOK
    // =========================================================

    const workbook =
        new ExcelJS.Workbook();


    const worksheet =
        workbook.addWorksheet('Shift Schedule');


    // =========================================================
    // COLUMN POSITIONS
    // =========================================================

    const scheduleColumn = 1;

    const departmentColumn = 2;

    const employeeColumn = 3;

    const timelineStartColumn = 4;


    // =========================================================
    // TITLE
    // =========================================================

    worksheet.mergeCells(
        1,
        1,
        1,
        timelineStartColumn + timeline.length - 1
    );


    const titleCell =
        worksheet.getCell(1, 1);

    titleCell.value =
        'SHIFT SCHEDULE REPORT';

    titleCell.font = {
        bold: true,
        size: 16
    };

    titleCell.alignment = {
        horizontal: 'center',
        vertical: 'middle'
    };


    // =========================================================
    // PARAMETERS
    // =========================================================

    worksheet.getCell(3, 1).value =
        'Department';

    worksheet.getCell(3, 2).value =
        departments.length
            ? departments.join(', ')
            : 'Select Options';


    worksheet.getCell(4, 1).value =
        'Payroll Group';

    worksheet.getCell(4, 2).value =
        payrollGroups.length
            ? payrollGroups.join(', ')
            : 'Select Options';


    worksheet.getCell(5, 1).value =
        'Shifts';

    worksheet.getCell(5, 2).value =
        shifts.length
            ? shifts.join(', ')
            : 'Select Options';


    worksheet.getCell(6, 1).value =
        'Active Only';

    worksheet.getCell(6, 2).value =
        activeOnly.length
            ? activeOnly.join(', ')
            : 'Select Options';


    worksheet.getCell(7, 1).value =
        'Date From';

    worksheet.getCell(7, 2).value =
        dateFrom;


    worksheet.getCell(8, 1).value =
        'Date To';

    worksheet.getCell(8, 2).value =
        dateTo;


    // Parameter formatting

    for (let row = 3; row <= 8; row++) {

        worksheet.getCell(row, 1).font = {
            bold: true
        };

    }


    // =========================================================
    // TABLE HEADER
    // =========================================================

    const headerRow =
        10;


    worksheet.getCell(
        headerRow,
        scheduleColumn
    ).value = 'Schedule';


    worksheet.getCell(
        headerRow,
        departmentColumn
    ).value = 'Department';


    worksheet.getCell(
        headerRow,
        employeeColumn
    ).value = 'Employee Name';


    // Timeline headers

    timeline.forEach(function (minutes, index) {

        const column =
            timelineStartColumn + index;

        const cell =
            worksheet.getCell(
                headerRow,
                column
            );

        cell.value =
            formatTime(minutes);

        cell.font = {
            bold: true,
            color: {
                argb: 'FFFFFFFF'
            }
        };

        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: '392264'
            }
        };

        cell.alignment = {
            horizontal: 'center',
            vertical: 'middle'
        };

    });


    // Header formatting

    for (
        let column = 1;
        column < timelineStartColumn;
        column++
    ) {

        const cell =
            worksheet.getCell(
                headerRow,
                column
            );

        cell.font = {
            bold: true,
            color: {
                argb: 'FFFFFFFF'
            }
        };

        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: {
                argb: '392264'
            }
        };

        cell.alignment = {
            horizontal: 'center',
            vertical: 'middle'
        };

    }


    // =========================================================
    // TABLE DATA
    // =========================================================

    let excelRow =
        headerRow + 1;

    let currentScheduleDate =
        '';


    rows.forEach(function (row) {

        // -----------------------------------------------------
        // DATE
        // -----------------------------------------------------

        const scheduleCell =
            row.querySelector(
                'td.schedule-column'
            );


        if (scheduleCell) {

            currentScheduleDate =
                scheduleCell.innerText
                    .replace(/\s+/g, ' ')
                    .trim();

        }


        // -----------------------------------------------------
        // DEPARTMENT + EMPLOYEE
        // -----------------------------------------------------

        const employeeCells =
            row.querySelectorAll(
                'td.employee-column'
            );


        if (employeeCells.length < 2) {
            return;
        }


        const department =
            employeeCells[0]
                .innerText
                .replace(/\s+/g, ' ')
                .trim();


        const employeeName =
            employeeCells[1]
                .innerText
                .replace(/\s+/g, ' ')
                .trim();


        // -----------------------------------------------------
        // WRITE BASIC INFORMATION
        // -----------------------------------------------------

        worksheet.getCell(
            excelRow,
            scheduleColumn
        ).value =
            currentScheduleDate;


        worksheet.getCell(
            excelRow,
            departmentColumn
        ).value =
            department;


        worksheet.getCell(
            excelRow,
            employeeColumn
        ).value =
            employeeName;


        // -----------------------------------------------------
        // SHIFT DATA
        // -----------------------------------------------------

        const spanTimeline =
            row.querySelector(
                '.span-timeline'
            );


        let shiftFrom = '';
        let shiftTo = '';
        let shiftName = '';


        if (spanTimeline) {

            shiftFrom =
                spanTimeline.dataset.sfrom || '';

            shiftTo =
                spanTimeline.dataset.sto || '';

            shiftName =
                spanTimeline.dataset.sfnam || '';

        }


        // -----------------------------------------------------
        // REST DAY
        // -----------------------------------------------------

        if (
            shiftName &&
            shiftName.toUpperCase() === 'RESTDAY'
        ) {

            timeline.forEach(function (minutes, index) {

                const column =
                    timelineStartColumn + index;

                const cell =
                    worksheet.getCell(
                        excelRow,
                        column
                    );

                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: {
                        argb: COLORS.rest
                    }
                };

            });

        }


        // -----------------------------------------------------
        // NORMAL SHIFT
        // -----------------------------------------------------

        else if (
            shiftFrom &&
            shiftTo
        ) {

            let fromMinutes =
                timeToMinutes(shiftFrom);

            let toMinutes =
                timeToMinutes(shiftTo);


            // Overnight shift
            if (toMinutes < fromMinutes) {
                toMinutes += 24 * 60;
            }


            const shiftColor =
                getShiftColor(
                    shiftFrom,
                    shiftTo,
                    shiftName
                );


            timeline.forEach(function (
                timelineMinutes,
                index
            ) {

                let checkMinutes =
                    timelineMinutes;


                /*
                 * For overnight shifts, allow
                 * the timeline after midnight.
                 */

                if (
                    checkMinutes < fromMinutes &&
                    toMinutes > 24 * 60
                ) {

                    checkMinutes +=
                        24 * 60;

                }


                if (
                    checkMinutes >= fromMinutes &&
                    checkMinutes < toMinutes
                ) {

                    const column =
                        timelineStartColumn + index;

                    const cell =
                        worksheet.getCell(
                            excelRow,
                            column
                        );


                    cell.fill = {
                        type: 'pattern',
                        pattern: 'solid',
                        fgColor: {
                            argb: shiftColor
                        }
                    };


                    cell.alignment = {
                        horizontal: 'center',
                        vertical: 'middle'
                    };

                }

            });

        }


        // -----------------------------------------------------
        // SHIFT NAME / TIME
        //
        // Put shift information in the first timeline cell
        // -----------------------------------------------------

        if (shiftName) {

            let displayText =
                shiftName;


            if (
                shiftFrom &&
                shiftTo
            ) {

                displayText =
                    formatTime(
                        timeToMinutes(shiftFrom)
                    ) +
                    ' - ' +
                    formatTime(
                        timeToMinutes(shiftTo)
                    ) +
                    ' (' +
                    shiftName +
                    ')';

            }


            // Find first colored timeline cell

            let firstColoredColumn =
                null;


            for (
                let index = 0;
                index < timeline.length;
                index++
            ) {

                const cell =
                    worksheet.getCell(
                        excelRow,
                        timelineStartColumn + index
                    );


                if (
                    cell.fill &&
                    cell.fill.fgColor &&
                    cell.fill.fgColor.argb !== 'FFFFFFFF'
                ) {

                    firstColoredColumn =
                        timelineStartColumn + index;

                    break;

                }

            }


            if (firstColoredColumn) {

                worksheet.getCell(
                    excelRow,
                    firstColoredColumn
                ).value =
                    displayText;

            }

        }


        // -----------------------------------------------------
        // ROW HEIGHT
        // -----------------------------------------------------

        worksheet.getRow(excelRow).height = 24;


        excelRow++;

    });


    // =========================================================
    // COLUMN WIDTHS
    // =========================================================

    worksheet.getColumn(1).width = 24;

    worksheet.getColumn(2).width = 25;

    worksheet.getColumn(3).width = 35;


    for (
        let column = timelineStartColumn;
        column <
        timelineStartColumn + timeline.length;
        column++
    ) {

        worksheet.getColumn(column).width = 11;

    }


    // =========================================================
    // BORDERS
    // =========================================================

    for (
        let row = headerRow;
        row < excelRow;
        row++
    ) {

        for (
            let column = 1;
            column <
            timelineStartColumn + timeline.length;
            column++
        ) {

            worksheet.getCell(
                row,
                column
            ).border = {

                top: {
                    style: 'thin',
                    color: {
                        argb: 'D9D9D9'
                    }
                },

                bottom: {
                    style: 'thin',
                    color: {
                        argb: 'D9D9D9'
                    }
                },

                left: {
                    style: 'thin',
                    color: {
                        argb: 'D9D9D9'
                    }
                },

                right: {
                    style: 'thin',
                    color: {
                        argb: 'D9D9D9'
                    }
                }

            };

        }

    }


    // =========================================================
    // FREEZE PANES
    // =========================================================

    worksheet.views = [
        {
            state: 'frozen',
            xSplit: 3,
            ySplit: headerRow
        }
    ];


    // =========================================================
    // DOWNLOAD
    // =========================================================

    let fileName =
        'Shift_Schedule_Report';


    if (dateFrom && dateTo) {

        fileName +=
            '_' +
            dateFrom +
            '_to_' +
            dateTo;

    }


    fileName += '.xlsx';


    const buffer =
        await workbook.xlsx.writeBuffer();


    const blob =
        new Blob(
            [buffer],
            {
                type:
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }
        );


    const link =
        document.createElement('a');

        link.href =
            URL.createObjectURL(blob);

        link.download =
            fileName;

        link.click();

        URL.revokeObjectURL(link.href);
    }
 </script>

@endsection
