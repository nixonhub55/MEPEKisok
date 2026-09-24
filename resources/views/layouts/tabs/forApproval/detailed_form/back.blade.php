 
<style>
      .btn-primary {
            background-color: #007bff;
            border-radius: 4px;
            border: none;
      }

      .btn-success {
            background-color: #28a745;
            border-radius: 4px;
            border: none;
            transition: background-color 0.3s ease;
      }

      .btn-success:hover {
            background-color: #218838;
      }

      .form-label {
            font-weight: bold;
      }

      .input-group-text {
            background-color: #f8f9fa;
      }

      .form-control {
            border-radius: 4px;
      }

      .red a {
            color: red !important;
            pointer-events: none;
            /* Disable clicking */
      }



      @media (max-width: 767px) {
            .custom-container {
                  width: 100%;
                  margin-top: 20px;
            }
      }

      .input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
      }

      .form-check-label {
            font-size: 0.9rem;
      }

      .modal-footer .btn {
            padding: 0.5rem 2rem;
      }

      /* Custom Datepicker Style */
      .ui-datepicker-calendar {
            border-radius: 4px;
      }
</style>
 <?php
  
 // echo json_encode($schedules);
 //echo json_encode($details);

   // echo json_encode($schedules['rows'][0]->schedules);

   $schedFirstRow = json_decode($schedules['rows'][0]->schedules,true);
   //echo count($schedFirstRow);

 ?> 
<div id="div_validation"></div>
<div class="container custom-container">
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
</div>  