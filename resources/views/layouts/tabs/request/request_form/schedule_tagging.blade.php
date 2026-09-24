<script>
    var selectedPeriod = "";
    var currentSearchableTxt = null;

    async function  setPayrollDateRange(elem,code,lineId,dateFrom,dateTo,schedTag) {
        const searchableOptions = elem.closest('.searchable_options');
        const ddlPayrol = elem.closest('.searchable_ddl')
                      .querySelector('.searchable_text'); 

        this.currentSearchableTxt = ddlPayrol;
        this.currentCode = code;
        this.currentLineId = lineId;
        selectedPeriod = `From: `+dateFrom+` To: `+dateTo; 
        ddlPayrol.value = selectedPeriod;
        
        var rOpt = ({
                "appNo":'{{$appNo}}',
                "code":code,
                "lineId":lineId, 
                "schedTagLocked":schedTag
            });

        var formData = new FormData();   
        formData.append('rOpt',JSON.stringify(rOpt));  
        GlovalHTMLObjLoading(1,'divDates');
        var response = await call_page_into_div(formData,'{{url("/payrollPeriodDates")}}'); 
        document.getElementById('divDates').innerHTML = response;
        searchableOptions.style.display = 'none'; 
        
    }
</script>
<?php
    $appNo = ($appNo=="") ? 0 : $appNo;

    $appDate = $details['rows'][0]->stAppDate ?? date('Y-m-d');
    $center = $user_details['rows'][0]->costName;
    $department  = $user_details['rows'][0]->departmentName;
    $location = $user_details['rows'][0]->location;

    $payrollPeriodID = "";
    $payrollPeriodFrom = "";
    $payrollPeriodTo = "";
    $reason = $details['rows'][0]->stReason ?? "";

    foreach ($appNoDetails['rows'] as $row) {
            $payrollPeriodFrom = $row->payrollPeriodFrom;
            $payrollPeriodTo = $row->payrollPeriodTo;
            $payrollPeriodID =$row->scheduleName;
    }
    
    //echo json_encode($payrollPeriod);

?> 
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
 
      .ui-datepicker-calendar {
            border-radius: 4px;
      }
    
</style> 
<div class="container">

    <div class="row mb-3"> 
        <div class="container">
            <div class="row"> 
                <div class="col-md-6">
                    <div class="border border-dotted p-3 mt-2">
                        <div class="m-2"><det style="font-weight:bolder; font-size:14px"> Application Details</det>  </div>
                        <div class="m-2"><i class="fas fa-file-alt fs-5 text-info"></i><b style="margin-left: 5px;"> Application :</b> {{$appNo}}</div>
                        <div class="m-2"><i class="fas fa-calendar fs-5 text-info"></i><b style="margin-left: 5px;"> Date Submitted :</b> {{$appDate}}</div>
                        <div class="m-2"><i class="fas fa-landmark fs-5 text-info"></i><b style="margin-left: 5px;"> Center :</b> {{$center}}</div>
                        <div class="m-2"><i class="fas fa-building fs-5 text-info"></i><b style="margin-left: 5px;"> Department :</b> {{$department}}</div>
                        <div class="m-2"><i class="fas fa-location fs-5 text-info"></i><b style="margin-left: 5px;"> Location :</b> {{$location}}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container"> 

                        <div class="row"> 
                                <div class="col-md-12 mb-3">
                                    <label for="ddlPayrol" id="lbl_ddlPayrol">Payroll Period</label>
                                    <select id="ddlPayrol" class="form-select" onchange="return pickSchedulePeriod(this.value)"> 
                                        <option value="" selected></option>
                                        @foreach($payrollPeriod['rows'] as $row)
                                            <option value="{{$row->payrollPeriodID}}" <?= ($payrollPeriodID==$row->payrollPeriodID) ? "selected" : "" ?>  >{{$row->payrollPeriodType}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3"> 
                                    <label for="ddlPayrol2" id="lbl_ddlPayrol2">Payroll Period</label>
                                    <div class="searchable_ddl">
                                        <input type="text" id="ddlPayrol2" class="form-control searchable_text">
                                        <div class="searchable_options" id="searchable_options"> 
                                            <div>No selected period</div>
                                        </div>
                                    </div> 
                                </div> 

                                <div class="col-md-12 mb-3"> 
                                    <label for="ddl_bulk_scheduler">Schedule</label>
                                    <select id="ddl_bulk_scheduler" class="form-select">
                                        @foreach($shifts['rows'] as $row)
                                            <option value="{{$row->code}}">{{$row->shiftName}}</option>
                                        @endforeach
                                    </select> 
                                </div>


                                <div class="col-md-12 mt-3">
                                    <div style="display: flex; justify-content: flex-start; gap: 10px;">  
                                            <div>
                                                <input type="checkbox" id="chkbx_sunday" value="7" checked>
                                                <label for="chkbx_sunday">Su</label>  
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_monday" value="1" checked>
                                                <label for="chkbx_monday">Mo</label>
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_tuesday" value="2" checked>
                                                <label for="chkbx_tuesday">Tu</label>
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_wendsday" value="3" checked>
                                                <label for="chkbx_wendsday">We</label>
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_thursday" value="4" checked>
                                                <label for="chkbx_thursday">Th</label>
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_friday" value="5" checked>
                                                <label for="chkbx_friday">Fr</label>
                                            </div>

                                            <div>
                                                <input type="checkbox" id="chkbx_saturday" value="6" checked>
                                                <label for="chkbx_saturday">Sa</label>
                                            </div>  

                                            <div style="margin-top: -10px; margin-left:50px;">
                                                    <button class="btnApply text-success" onclick="return applySchedule()"><i class="fas fa-clipboard-check"></i> Apply Schedule</button>
                                            </div>
                                    </div>
                                    
                                </div>
                        </div> 

                         

                    </div>
                </div>
                        
            </div>
        </div>
    </div>

  
    

    <hr>
    <div class="row">
        <div class="col-md-12" id="divDates">
                <div class="alert alert-warning text-center" role="alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    No Payroll period selected!
                </div> 
        </div>
    </div>
    <hr>
    <div class="col-md-12" id="divWizard"></div>


    <div class="row">

        <div class="col-md-12 mb-3">
                <label id="lbl_txtReason" for="txtReason" class="form-label">Reason</label>
                <input type="text" id="txtReason" class="form-control" value="<?=$reason?>" maxlength="200">
                <div class="counter">
                <span id="current">0</span> / 200
                </div>
        </div>  

        <div class="col-md-12"> 
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="verification_checkbox">
                <label id="verification_checkbox_lbl" class="form-check-label" for="verification_checkbox">
                        I verify that all the information above is correct.
                </label>
            </div> 
        </div> 
    </div>
</div> 

<script> 
    
    $(document).ready(function () {

        $('.searchable_ddl').on('click', function () {
            var options = $(this).find('.searchable_options')[0];
            options.style.display = "block";
        });

        $(document).on('click', function (e) {
            
            if(currentSearchableTxt){
                currentSearchableTxt.value = selectedPeriod;
            }
            if (!$(e.target).closest('.searchable_ddl').length) {
                $('.searchable_options').hide();
            }

        });


    });

    async function applySchedule() {

        var bulk_schedule = document.getElementById('ddl_bulk_scheduler').value;
        var selected_days = [];
        var days_list = [
            document.getElementById('chkbx_sunday'),
            document.getElementById('chkbx_monday'),
            document.getElementById('chkbx_tuesday'),
            document.getElementById('chkbx_wendsday'),
            document.getElementById('chkbx_thursday'),
            document.getElementById('chkbx_friday'),
            document.getElementById('chkbx_saturday') 
        ];

        days_list.forEach(el => {
           if(el.checked){
                selected_days.push(el.value);
           }
        });
       

        var alldllwithschedules =  document.querySelectorAll('.days'); 
        alldllwithschedules .forEach(el => {
            var datavalue = el.dataset.day

            if(selected_days.includes(datavalue)){
                console.log(el.value);
                el.value = bulk_schedule;
            } 
        });
    }

   /*  async function  setPayrollDateRange(elem,code,lineId,dateFrom,dateTo) {
        const searchableOptions = elem.closest('.searchable_options');
        const ddlPayrol = elem.closest('.searchable_ddl')
                      .querySelector('.searchable_text'); 

        this.currentSearchableTxt = ddlPayrol;
        this.currentCode = code;
        this.currentLineId = lineId;
        selectedPeriod = `From: `+dateFrom+` To: `+dateTo; 
        ddlPayrol.value = selectedPeriod;
        
        var rOpt = ({
                "appNo":'{{$appNo}}',
                "code":code,
                "lineId":lineId
            });

        var formData = new FormData();   
        formData.append('rOpt',JSON.stringify(rOpt));  
        GlovalHTMLObjLoading(1,'divDates');
        var response = await call_page_into_div(formData,'{{url("/payrollPeriodDates")}}'); 
        document.getElementById('divDates').innerHTML = response;
        searchableOptions.style.display = 'none'; 
        
    } */
 
    async function pickSchedulePeriod(val) {  
        var divDates = document.getElementById('divDates');  
        var ddlPayrol2 = document.getElementById('ddlPayrol2');  
        var container = document.getElementById('searchable_options');  

        divDates.innerHTML = `<div class="alert alert-warning text-center" role="alert">
                                <i class="fas fa-exclamation-triangle"></i>
                                No Payroll period selected!
                            </div> `;
 
       
 
        var formData = new FormData(); 
        formData.append('payrollPeriodID',val);     
        formData.append('payrollPeriodFrom','{{$payrollPeriodFrom}}');     
        formData.append('payrollPeriodTo','{{$payrollPeriodTo}}');     
        //formData.append('rOpt',JSON.stringify(rOpt));   
        var response = await call_page_into_div(formData,'{{url("/payrollPeriodDays")}}',container);  
    }

    document.querySelectorAll(".searchable_text").forEach(input => {
        input.addEventListener("keyup", function () {
            const searchText = this.value.toLowerCase(); 
            document.querySelectorAll(".searchable-div").forEach(div => {
                div.style.display =  div.innerHTML.toLowerCase().includes(searchText) ? "" : "none"; 
                
            });
        });
    });
 
    
    if('<?= $payrollPeriodTo ?>'!==""){
        var period = document.getElementById('ddlPayrol').value;
        pickSchedulePeriod(period);
    }


    // Text Reason
    var textarea = document.getElementById('txtReason');
    var current = document.getElementById('current');
    var maxLength = textarea.getAttribute('maxlength');
    textarea.addEventListener('input', () => {
        current.textContent = textarea.value.length;
    });

</script>