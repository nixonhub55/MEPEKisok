 
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

      .tblSchedule td,th{
            padding: 5px !important;
      }
</style>
 <?php
 
  
 // echo json_encode($schedules);
  // echo json_encode($details); 
   // echo json_encode($schedules['rows'][0]->schedules);

   $schedFirstRow = json_decode($schedules['rows'][0]->schedules,true);
   //echo json_encode($schedules['rows'][0]->payrollPeriodFrom);

 ?> 
<div id="div_validation"></div>
<div class="container custom-container">
      
      <div class="row mb-3"> 
        <div class="container">
            <div class="row"> 
                <div class="col-md-6">
                    <div class="border border-dotted p-3 mt-2">
                        <div class="m-2"><det style="font-weight:bolder; font-size:14px"> Application Details</det>  </div>
                        <div class="container">
                              <div class="row">
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-list fs-5 text-info"></i><b style="margin-left: 5px;"> Application No.:</b> {{$details['rows'][0]->r_appNo}}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-landmark fs-5 text-info"></i><b style="margin-left: 5px;"> Center:</b> {{$details['rows'][0]->stCostCenter}}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-calendar fs-5 text-info"></i><b style="margin-left: 5px;"> Date Submitted :</b> {{$details['rows'][0]->stAppDate}}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-building fs-5 text-info"></i><b style="margin-left: 5px;"> Department :</b> {{$details['rows'][0]->department}}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-user fs-5 text-info"></i><b style="margin-left: 5px;"> Employee No.:</b> {{$details['rows'][0]->stID}}
                                    </div>
                                    <div class="col-md-6 mb-2">
                                          <i class="fas fa-location fs-5 text-info"></i><b style="margin-left: 5px;"> Location :</b> {{$details['rows'][0]->location}}
                                    </div>
                                    <div class="col-md-12 mb-3">
                                          <i class="fas fa-user fs-5 text-info"></i><b style="margin-left: 5px;"> Employee Name :</b> {{$details['rows'][0]->stName}}
                                    </div> 
                              </div> 
                        </div>  
                          
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="container"> 

                        <div class="row"> 
                                <div class="col-md-12 mb-3">
                                    <label for="ddlPayrol" id="lbl_ddlPayrol">Payroll ID</label>
                                    <input type="text" class="form-control" value="{{$details['rows'][0]->payrollPeriodID}}">
                                </div>

                                <div class="col-md-12 mb-3"> 
                                    <label for="ddlPayrol2" id="lbl_ddlPayrol2">Payroll Period</label>
                                    <input type="text" id="ddlPayrol2" class="form-control" value="From: {{$schedules['rows'][0]->payrollPeriodFrom}} To: {{$schedules['rows'][0]->payrollPeriodTo}}" readonly>
                                </div> 

                                <div class="col-md-12 mb-3"> 
                                    <label for="ddl_bulk_scheduler">Remarks</label>
                                    <textarea id="ddl_bulk_scheduler" class="form-control" readonly>{{$details['rows'][0]->stReason}}</textarea> 
                                </div>
 
                        </div> 

                         

                    </div>
                </div>
                        
            </div>
        </div>
      </div>

      <div class="row mb-3">
            <div class="tbl_sched_container">
                  <table class="tblSchedule">
                        <head> 
                              <th>Employee Id</th>
                              <th>Employee Name</th>
                              <th>Center</th>
                              <th>Department</th> 
                              @for ($i = 0; $i < count($schedFirstRow); $i++) <!-- DAYS COLUMNS -->
                              <th>Day {{$i}}: {{$schedFirstRow[$i]['day']}}</th>
                              @endfor  
                        </head>
                        <tbody>
                              @foreach($schedules['rows'] as $row)
                              <tr>
                                    <td>{{$row->employeeId}}</td>
                                    <td>{{$row->employeeName}}</td>
                                    <td>{{$row->costCenter}}</td>
                                    <td>{{$row->department}}</td>
                                    @for ($i = 0; $i < count($schedFirstRow); $i++) <!-- DAYS COLUMNS -->
                                    <?php
                                          $sched = json_decode($row->schedules,true); 
                                    ?>
                                          <td>{{$sched[$i]['scheduleName']}}</td>
                                    @endfor  
                              </tr>
                              @endforeach
                        </tbody>
                  </table>
            </div>
      </div>

      <div class="row">
            <div class="col-12 mb-3" id="divFormsAttachmentsFiles"></div>

            <div class="col-md-12">
                  <div class="row">
                        <div class="col-md-12">
                              <label id="lbltxtReject" for="txtReject" class="form-label">Rejection Remarks</label> 
                              <textarea id="txtReject" class="form-control" maxlength="200"></textarea>
                              <div class="counter">
                                    <span id="current">0</span> / 200
                              </div>
                        </div> 
                  </div> 
            </div>
      </div>
</div>  

<script>
      function check_if_payroll_locked(){ 
            var enc_pay = '<?=$enc_pay?>';
            if (enc_pay==1){
                  document.getElementById("btns").innerHTML = lockApproverMessage;
            }else{
                  document.getElementById("btns").innerHTML = original_btns;
            }
      } 
      //check_if_payroll_locked();
      
      var textarea = document.getElementById('txtReject');
      var current = document.getElementById('current');
      var maxLength = textarea.getAttribute('maxlength');
      
      loadAttachmentsAssets(0,'<?= json_encode($attachedFiles['rows'][0]->files ?? []) ?>');
      textarea.addEventListener('input', () => {
      current.textContent = textarea.value.length;
      });
</script>