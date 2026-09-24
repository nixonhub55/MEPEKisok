<?php 
   // echo json_encode($payrollperiodDates);
   // echo json_encode($listofstaff);
 
  // $days =  json_decode($listOfStaffWithperiodDates['rows'][0]->dates,true) ?? [];
 //  $daysCount = $listOfStaffWithperiodDates['rows'][0]->daysCount ?? [];
   //echo json_encode($days);

    //$days =  json_decode($payrollperiodDates['rows'][0]->dates,true) ?? [];
    //$daysCount = $payrollperiodDates['rows'][0]->daysCount ?? [];
    
    
    $hdr = []; // CREATE HEADERS
    $rowNum = 0;
    foreach ($listOfStaffWithperiodDates['rows'] as $row) {
        if($rowNum<$row->rowNum){ 
            $rowNum = $row->rowNum;
            $hdr[] = [
                        "rowNum" =>$row->rowNum, 
                        "date" =>$row->date, 
                        "col" =>$row->col, 
                     ]; 
        }
    }
    $rOpt =  json_decode($rOpt);
    if ($rOpt->schedTagLocked==1){
        echo '<div class="alert alert-warning" role="alert">
                <center>
                    <i class="fas fa-lock fs-1"></i> </br>
                    Payroll period schedule tagging locked!
                </center>
            </div>';
        return;
    }
    //schedTagLocked
    //echo json_encode($hdr);

    $columns = []; // CREATE HEADERS
    $identityId = 0;
    foreach ($listOfStaffWithperiodDates['rows'] as $row) {
        if($identityId!==$row->identityId){ 
            $identityId = $row->identityId;
            $columns[] = [
                        "identityId" =>$row->identityId, 
                        "fullName" =>$row->fullName, 
                        "batchId" =>$row->batchId, 
                        "payrollPeriodID" =>$row->payrollPeriodID, 
                        "costCode" =>$row->costCode, 
                        "departmentCode" =>$row->departmentCode, 
                     ]; 
        }
    }


    $num = 0;

    
?>

 
 
<div class="tbl_sched_container">
    <table class="tblSchedule">
        <head> 
            <th>Employee Id</th>
            <th>Employee Name</th>
            <th>Center</th>
            <th>Department</th> 
            @for ($i = 0; $i < count($hdr); $i++) <!-- DAYS COLUMNS -->
                <th>Day {{$i}}: {{$hdr[$i]['date']}}</th>
            @endfor  
        </head>
        <tbody>
            @foreach($columns as $row)
                <tr>
                    <?php 
                        $identityId = $row['identityId']; 
                    ?>
                    <td>{{$row['identityId']}}</td>
                    <td><input class="input-column" type="text" value="{{$row['fullName']}}" readonly></td>
                    <td>{{$row['costCode']}}</td>
                    <td>{{$row['departmentCode']}}</td>  
                   
                    @for ($i = 0; $i < count($hdr); $i++) <!-- DAYS COLUMNS -->
                        <!-- <td>
                            {{$listOfStaffWithperiodDates['rows'][$num]->schedule}}  
                        </td> -->
                        <?php
                            $selectedValue = $listOfStaffWithperiodDates['rows'][$num]->schedule;
                            $ddlId = $identityId."_".$listOfStaffWithperiodDates['rows'][$num]->col;
                            $data_date = $hdr[$i]['date'];
                            $data_day = date('N', strtotime($hdr[$i]['date']));
                        ?>
                        <td>
                            <select class="input-column days" id="{{$ddlId}}" data-day="{{$data_day}}" data-date="{{$data_date}}">
                                @foreach($shifts['rows'] as $row)
                                    <option value="{{$row->code}}" <?=($selectedValue==$row->code) ? "selected" : "" ?> >{{$row->shiftName}}</option>
                                @endforeach
                            </select>

                        </td>
                    <?php 
                        $num++;  
                        $ddlId = $identityId;
                    ?>
                    @endfor  
                </tr>
                
                
            @endforeach
        </tbody>
    </table>
</div>


 