
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
    <div style="display: flex; justify-content: space-between; margin-bottom:10px">
        <div class="mb-3 text-warning"><i class="fas fa-info-circle fs-5 text-info" title="Super visor need to identity staff who need the application"></i> Choose staff you want in the list</div>
        <div>
            <!-- <button class="btn btn-info text-white"><i class="fas fa-arrow-right"></i> For me</button> -->
             <button  class="btn btn-add" onclick="return justForMe()">+ For me</button> 
        </div>
    </div>
    
    <div class="tbl-container">
        <table id="datatableuserlist" class="staff_tbl">
                <thead>
                        <!-- @if($multi)
                            <th data-sortable="false"> 
                                <input type="checkbox" id="checkAll">
                            </th>
                        @endif -->
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
                       <!--  @if($multi)
                            <td>
                               <input type="checkbox" class="user-checkbox" value="{{$row->identityId}}">
                            </td>
                        @endif -->
                        <td style="text-align:left">  
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

    async function justForMe() {
        var userdetails = JSON.parse('<?= json_encode(session()->get('userasstaff')) ?>');
        //console.log(userdetails);
        pickStaff(userdetails)
    }

    async function pickStaff(staffInfo) {
        console.log(staffInfo);
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

 
</script>

<!-- For Checkbox -->
<script>
    const datatableuserlist = document.getElementById('datatableuserlist');

    if (datatableuserlist) {

        const selectedUsers = new Set();

        const dataTable = new simpleDatatables.DataTable(datatableuserlist, {
            perPage: 5,
            perPageSelect: [5,10, 25, 50, 100, ["All", -1]]
        });

        // ==========================
        // Restore checked checkboxes
        // ==========================
        function restoreCheckboxes() {

            document
                .querySelectorAll('#datatableuserlist tbody .user-checkbox')
                .forEach(cb => {
                    cb.checked = selectedUsers.has(cb.value);
                });

            updateCheckAll();
        }


        // ==========================
        // Update header checkbox
        // ==========================
        function updateCheckAll() {

            const checkAll = document.getElementById('checkAll');

            if (!checkAll) return;

            const checkboxes = document.querySelectorAll(
                '#datatableuserlist tbody .user-checkbox'
            );

            if (checkboxes.length === 0) {
                checkAll.checked = false;
                checkAll.indeterminate = false;
                return;
            }

            const checked = [...checkboxes].filter(cb => cb.checked).length;

            checkAll.checked = checked === checkboxes.length;

            checkAll.indeterminate =
                checked > 0 &&
                checked < checkboxes.length;
        }


        // ==========================
        // Individual checkbox
        // ==========================
        document.addEventListener('change', function (e) {

            if (!e.target.matches('#datatableuserlist .user-checkbox')) {
                return;
            }

            const id = e.target.value;

            if (e.target.checked) {
                selectedUsers.add(id);
            } else {
                selectedUsers.delete(id);
            }

            updateCheckAll();

            console.log('Selected:', [...selectedUsers]);
        });


        // ==========================
        // Check ALL
        // ==========================
        document.addEventListener('change', function (e) {

            if (e.target.id !== 'checkAll') {
                return;
            }

            const checkboxes = document.querySelectorAll(
                '#datatableuserlist tbody .user-checkbox'
            );

            checkboxes.forEach(cb => {

                cb.checked = e.target.checked;

                if (cb.checked) {
                    selectedUsers.add(cb.value);
                } else {
                    selectedUsers.delete(cb.value);
                }

            });

            // Remove indeterminate after clicking
            e.target.indeterminate = false;

            console.log('Selected:', [...selectedUsers]);
        });


        // ==========================
        // DataTable pagination/search
        // ==========================
        dataTable.on('datatable.page', function () {
            restoreCheckboxes();
        });

        dataTable.on('datatable.search', function () {
            restoreCheckboxes();
        });

        dataTable.on('datatable.sort', function () {
            restoreCheckboxes();
        });

        dataTable.on('datatable.perpage', function () {
            restoreCheckboxes();
        });


        restoreCheckboxes();
    }


</script>