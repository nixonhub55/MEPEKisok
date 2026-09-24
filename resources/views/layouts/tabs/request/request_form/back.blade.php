
<?php

    //echo json_encode($userlist)
    $multi = ($switch==5); 
?>

<style>
    .profile-pic {
        display: inline-flex;
        justify-content: center; /* horizontal */
        align-items: center;     /* vertical */ 
        font-size: 20px;
        width: 50px;
        height: 50px;
        background-color: #d6d3d3;
        border-radius: 50%;
         cursor: pointer;
    }

    #datatableuserlist{
        font-size: 12px !important;
    }
    
   /*  #datatableuserlist tr{
        cursor: pointer;
    } */

    #datatableuserlist th,td{
       border: none;
    }

    #datatableuserlist th{
       background-color: #fff !important;
       color: #4d4b4b !important;
    }
</style>
 
<div class="container p-3">  
    <!-- <h5>Manpower List</h5> -->
    <div class="mb-3 text-warning"><i class="fas fa-info-circle fs-5 text-info" title="Super visor need to identity staff who need the application"></i> Choose staff you want in the list</div>
    <div class="tbl-container">
        <table id="datatableuserlist" class="staff_tbl">
                <thead>
                        @if($multi)
                            <th> 
                                <input type="checkbox" id="chekboxAllUser">
                            </th>
                        @endif
                        <th>Fullname</th>
                        <th>Employee ID</th>
                        <th>Center</th>
                        <th>Department</th>
                        <th>Position</th>
                    </tr>
                </thead>
                <tbody>  
                @foreach($userlist['rows'] as $row)
                    <?php
                        $name = $row->fullName;
                        $parts = explode(' ', trim($name)); 
                        $initials = strtoupper(
                            substr($parts[0], 0, 1) . substr(end($parts), 0, 1)
                        );
                    ?>
                    <tr>
                        @if($multi)
                            <td>
                                <input type="checkbox" id="chekbox{{$row->identityId}}">
                            </td>
                        @endif
                        <td>  
                            <div class="profile-pic"  onclick="return pickStaff(JSON.parse('{{json_encode($row)}}'))">
                                {{$initials}}</div> <label for="chekbox{{$row->identityId}}">{{$row->fullName}}</label>  
                            </div>
                        </td> 
                        <td>{{$row->identityId}}</td> 
                        <td>{{$row->costCode}}</td> 
                        <td>{{$row->departmentCode}}</td> 
                        <td>{{$row->position}}</td> 
                    </tr>
                @endforeach
                </tbody>
        </table> 
    </div>
</div>

<script> 
    
    var switchNo = '{{$switch}}';
    var div_identity = document.getElementById('div-identity');
    var div_filing = document.getElementById('div-filing');
    
    /*
        0 - overtime
        1 - leave
        2 - timeadjustment
        3 - officialbusiness
        4 - offset
        5- timeentry
        6 - schedulechange
        7 - hrdcert
        8 - scheduletagging 
    */

    async function pickStaff(staffInfo) {
        this.selectedIdentityRow = staffInfo; 

        if (switchNo==0){ // OVERTIME
            document.getElementById('appEmployeeId').value = staffInfo.identityId;
            document.getElementById('appEmployeeName').value = staffInfo.fullName;
            document.getElementById('appDepartment').value = staffInfo.departmentCode; 
            document.getElementById('appCostCenter').value = staffInfo.costCode;
        }

        if (switchNo==1){ // LEAVE  
            const leave_form = await  show_leave_form_promise(selectedID);
            if(leave_form){
                document.getElementById('appEmployeeId').value = staffInfo.identityId;
                document.getElementById('appEmployeeName').value = staffInfo.fullName;
                document.getElementById('appDepartment').value = staffInfo.departmentCode; 
                document.getElementById('appCostCenter').value = staffInfo.costCode;
            } 
        }

        if (switchNo==2){ // TIME ADJUSTMENT 

            document.getElementById('appEmployeeId').value = staffInfo.identityId;
            document.getElementById('appEmployeeName').value = staffInfo.fullName;
            document.getElementById('appDepartment').value = staffInfo.departmentCode; 
            document.getElementById('appCostCenter').value = staffInfo.costCode; 
        }

        if (switchNo==3){ // TIME ADJUSTMENT 

            document.getElementById('appEmployeeId').value = staffInfo.identityId;
            document.getElementById('appEmployeeName').value = staffInfo.fullName;
            document.getElementById('appDepartment').value = staffInfo.departmentCode; 
            document.getElementById('appCostCenter').value = staffInfo.costCode; 
        }

        if (switchNo==4){ // OFFSET
             
            document.getElementById('appEmployeeId').value = staffInfo.identityId;
            document.getElementById('appEmployeeName').value = staffInfo.fullName;
            document.getElementById('appCostCenter').value = staffInfo.costCode;
            document.getElementById('appDepartment').value = staffInfo.departmentCode;   
        }
        
        if (switchNo==5){ // TIME ENTRY
            
            document.getElementById('appEmployeeId').value = staffInfo.identityId;
            document.getElementById('appEmployeeName').value = staffInfo.fullName;
            document.getElementById('appCostCenter').value = staffInfo.costCode;
            document.getElementById('appDepartment').value = staffInfo.departmentCode;   
        }

        div_identity.hidden = true;
        div_filing.hidden = false;
    }


    function backToList(){
        div_identity.hidden = false;
        div_filing.hidden = true;
    }

    const datatableuserlist = document.getElementById('datatableuserlist');
    if (datatableuserlist) {
            new simpleDatatables.DataTable(datatableuserlist);

    }


</script>