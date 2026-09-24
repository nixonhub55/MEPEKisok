@extends('layouts.admin') <!-- main layout file -->

@section('content')  
<style>
      .btn-add {
            background: #28a745;
            color: #fff;
            font-size: 14px;
            border-radius: 20px;
            padding: 8px 20px;
            text-decoration: none;
      }

      .btn-export {
            background: #6c757d;
            color: #fff;
            font-size: 14px;
            border-radius: 20px;
            padding: 8px 20px;
            text-decoration: none;
      }
      .modal-body {
            padding: 2em;
      }

      .modal-dialog{
        max-width: 1100px !important;
      }
      .bg-primary {
            background-color: black;
      }
</style> 

<?php  
      $df = date('Y-m-d', strtotime("-1 Month")); 
      $dt = date('Y-m-d');
      $btnId = 0; 
      //echo json_encode($rows);
?>

<div id="divExec"></div>
<div class="container-fluid mt-4 card-container">
      <!-- <h1>Overtime Approval</h1> -->
      <div class="d-flex justify-content-between align-items-center mb-3">
            <!-- <h4>Overtime List For Approval</h4> -->
            <h5>Your Applications</h5>
            <div>
                  <button  class="btn btn-add" id="btnAdd">+ Add Application</button> 
            </div> 
      </div>

      <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                  <a class="nav-link active" id="pending-tab" data-bs-toggle="tab" href="#pending" role="tab"
                        aria-controls="pending" aria-selected="true">Pending</a>
            </li>
            <li class="nav-item" role="presentation">
                  <a class="nav-link" id="history-tab" data-bs-toggle="tab" href="#history" role="tab"
                        aria-controls="history" aria-selected="false">History</a>
            </li>
      </ul>

      <!-- Modal Structure --> 
      <div class="modal fade" id="scheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel"  aria-hidden="true">
            <div class="modal-dialog modal-xl">
                  <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                              <h5 class="modal-title" id="scheduleModalLabel">Schedule Tagging Application</h5>
                              <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal_body"></div> 
                        <div class="modal-footer"  id="btns">
                              <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>
                              <button type="button" class="btn btn-success" id="btn1" onclick="return SubmitRequest(0,this.id)">Save changes</button>
                        </div>
                  </div>
            </div>
      </div>

      <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                  <div class="table-container">
                        <table class="table data-table" id="datatablesPending">
                              <thead>
                                    <tr> 
                                          <th>App #</th>
                                          <th>App Date</th>     
                                          <th>Reason</th> 
                                          <th>Status</th> 
                                          <th>Actions</th>
                                    </tr>
                              </thead>
                              <tbody>
                               @foreach($pending_list['rows'] as $list)
                                    <tr>
                                          <td>{{$list->r_appNo}}</td>
                                          <td>{{$list->stAppDate}}</td>
                                          <td>{{$list->stReason}}</td> 
                                          <td>{{$list->stStatus}}</td> 
                                          <td>
                                                <div class="btn-container">
                                                      @if(session()->get('allowmanpowerlist')==1)
                                                            <button class="btn btn-action btn-edit" title="Edit" id="btn1{{$btnId}}" onclick="show_scheduletaggingform('{{$list->enc_id}}')"><i
                                                                        class="fas fa-edit"></i></button>
                                                            <button class="btn btn-action btn-delete" title="Delete"  onclick="show_scheduletaggingform('{{$list->enc_id}}')"><i
                                                                        class="fas fa-trash"></i>
                                                            </button> 
                                                      @else
                                                            <button class="btn btn-primary text-white" title="View" onclick="EditOT('{{$list->enc_id}}')"><i
                                                                        class="fas fa-eye"></i>
                                                            </button>
                                                      @endif
                                                </div> 
                                          </td>
                                    </tr>
                                    <?php  $btnId+=1;?>
                                @endforeach
                              </tbody>
                        </table>
                  </div>
            </div>
            <div class="tab-pane fade" id="history" role="tabpanel" aria-labelledby="history-tab">
                  <div class="table-container">  
                        <div class="container">
                              <div class="row bg-light rounded p-2"> 
                                    <div class="col-md-2 col-lg-2">
                                          <b>Application Date From</b>
                                          <input type="date" id="txtdf" class="form-control" value="<?=$df?>">
                                    </div> 

                                    <div class="col-md-2 col-lg-2">
                                          <b>Application Date To</b>
                                          <input type="date" id="txtdt" class="form-control" value="<?=$dt?>">
                                    </div>  

                                    <div class="col-md-2 col-lg-2">
                                    <b>Status</b>
                                          <select id="ddlStatus" class="form-select">
                                                @foreach($status['rows'] as $rows)
                                                <option value="{{$rows->val}}" >{{$rows->txt}}</option>
                                                @endforeach
                                          </select>
                                    </div> 
                                    <div class="col-md-6 d-flex justify-content-end align-items-center"> 
                                    <button class="btn btn-primary" id="btnFilter" onclick="Filter_HistoryRequester(0,this.id,3)">
                                          <i class="fas fa-filter"></i><b> Filter</b></button>
                                    </div> 
                              </div> 
                        </div> 
                        <br> 
                        <div id="divTbl"></div> 
                  </div>
            </div>
      </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>

    var this_mode = 0;
    var selectedID="",selectedDB = ""; 
    var day_list = [], scheduleDetails = []; 
    var currentCode = null; currentLineId = null;


      window.addEventListener('DOMContentLoaded', event => { 

            const datatablesPending = document.getElementById('datatablesPending');
            if (datatablesPending) {
                  new simpleDatatables.DataTable(datatablesPending);

            }

            const datatablesHistory = document.getElementById('datatablesHistory');
            if (datatablesHistory) {
                  new simpleDatatables.DataTable(datatablesHistory);
            }
      }); 


      $(document).ready(function(){ 
            $('#btnAdd').click(function(){   
                  day_list.length = 0;
                  show_scheduletaggingform(0);
            });
      }); 
      

      function show_scheduletaggingform(id){   
            this.selectedID=id;
            day_list = [];  
            var formData = new FormData();
            formData.append('app_id', id); 
            formData.append('r_opt',JSON.stringify({"srcDB":selectedDB}));
              
            var myModal = new bootstrap.Modal(document.getElementById('scheduleModal'));   
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            $.ajax({
                  url: '{{url("/scheduletaggingform")}}',  
                  type: 'POST', 
                  data: formData,    
                  processData: false,  
                  contentType: false,
                  headers: {
                  'X-CSRF-TOKEN': csrfToken 
                  },             
                  success: function(response) {  
                        $('#modal_body').html(response);   
                        myModal.show();
                  },
                  error: function(msg) {  
                  //alert(JSON.parse(msg.responseText).error.msg.msg);
                  console.log('Error:'+JSON.stringify(msg));
                  } 
                 
            });  
            ResetModalButtons();
      }
      
      function Delete_ST(num,txt){
            this.selectedID = num;
            window.scrollTo(0, 0);
            fbconfirm('Cancel Confirmation','Are you sure you want to cancel application #:'+txt+'?', 'Yes','Cancel', 'CancelConfirmttion()'); 
      }

      async function CancelConfirmttion(){ 
            var formData = new FormData();
            formData.append('mode', '13');  
            formData.append('switchNo',8);      
            formData.append('appNo',selectedID);      
            const response = await exec_XMLHttpRequest(formData,'{{url("/call_ajax")}}'); 

            if (response.num!==0){
                        var id = JSON.parse(response.msg).id;
                        if (id!== undefined){  
                              console.log(response.msg);
                              var alert = '<div id="myAlert" class="alert alert-danger" role="alert">'+JSON.parse(response.msg).msg+'</div>';
                              document.getElementById('div_validation').innerHTML = alert;
                              document.getElementById(id).focus();
                        }
                        else{
                              var alert = '<div id="myAlert" class="alert alert-danger" role="alert">'+JSON.parse(response.msg).msg+'</div>';
                              document.getElementById('div_validation').innerHTML = alert; 
                        }
                  }else{ 
                        window.scrollTo(0, 0); 
                        fbconfirm(JsonMessges.sucess_hdr, JsonMessges.app_delete, 'OK','', 'window.location.href="{{url('/schedule_tagging')}}"');  
                  } 
      }

      async function SubmitRequest(pint_mode,objID) {

            day_list = [];
            scheduleDetails = [];
            var alldllwithschedules =  document.querySelectorAll('.days'); 
            var schedID = document.getElementById('ddlPayrol').value;
            alldllwithschedules.forEach(el => { 
                        day_list.push({
                              empId : (el.id).split("_")[0],
                              col   : (el.id).split("_").slice(1).join("_"),
                              day   : el.dataset.date,
                              sched : el.value
                        });
            }); 
      
            scheduleDetails.push({ schedID : schedID, code : currentCode,  lineId : currentLineId, daylist : day_list,});

           /*  console.log(scheduleDetails);
            return false; */


            var formData = new FormData();
            formData.append('mode', '29');    
            formData.append('pint_mode', pint_mode);    
            formData.append('stAppNo', selectedID);    
            formData.append('payrollPeriodID',document.getElementById('ddlPayrol').value);     
            formData.append('stReason',document.getElementById('txtReason').value);     
            formData.append('stSchedule',JSON.stringify(scheduleDetails[0])); 

            const response = await exec_XMLHttpRequest(formData,'{{url("/call_ajax")}}');   
            
            setTimeout(() => {
                  addAuditTrails(window.location.pathname.split('/').pop(),JSON.stringify(response));
                  GlovalHTMLObjLoading(0,objID); 
            }, maxMinExec);
            
            var vfbox = this_should_be_verified('verification_checkbox');  

            if (pint_mode==0){
                  if (response.num!==0){
                        var id = JSON.parse(response.msg).id;
                        if (id!== undefined){  
                              console.log(id); 
                              show_error_message(id,JSON.parse(response.msg).msg);  
                        }
                        else{
                              var alert = '<div id="myAlert" class="alert alert-danger" role="alert">'+JSON.parse(response.msg).msg+'</div>';
                              document.getElementById('div_validation').innerHTML = alert;   
                        }
                  }
                  else if (vfbox.num==1){ 
                        GlovalHTMLObjLoading(0,objID);  
                        show_error_message(vfbox.id+'_lbl',vfbox.msg); 
                        return false;
                  }else{  
                  confirm_submit("Are you sure, you want to submit this?");
                  } 
                  GlovalHTMLObjLoading(0,objID); 
            }else{ 
                 //console.log(response);  
                  window.location.href='{{url("/schedule_tagging")}}'; 
            }

          //console.log(scheduleDetails);
      }


      function confirm_submit(msg){  
            window.scrollTo(0, 0);
            fbconfirm('Submit Confirmation', msg, 'Yes','Cancel', 'ConfirmApplication()'); 
      }

      function ConfirmApplication(){ 
            SubmitRequest(1,'btn1');
      } 
       
</script>
@endsection