@extends('layouts.master')
@push('style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush
@section('menu')
@extends('sidebar.load_logs')
@endsection
@section('content')
<div id="main">
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-first">
                    <h3 class="text-white">Load Logs</h3>
                    <p class="text-subtitle text-muted">load logs information list</p>
                </div>
            </div>
        </div>
        {{-- message --}}
        {!! Toastr::message() !!}
        <section class="section">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr>
                                <th>Transaction</th>
                                <th>Amount</th>
                                <th>From</th>
                                <th>To</th>
                                <th>Current Balance</th>
                                <th>Note</th>
                                <th>Date</th>
                            </tr>    
                        </thead>
                        <tbody id="event-crud">
                            @foreach ($allTransactions as $transaction)
                            <tr id="transaction_id_{{ $transaction['id'] }}">
                                <td class="name" style="text-transform:uppercase;">{{ $transaction['transaction_type'] }}</td>
                                @if ($transaction['status'] == 1)
                                <td class="name bg-success text-white">{{ $transaction['amount'] }}</td>
                                @else
                                <td class="name bg-danger text-white">{{ $transaction['amount'] }}</td>
                                @endif
                                <td class="name">{{ $transaction['from'] }}</td>
                                <td class="name">{{ $transaction['to'] }}</td>
                                <td class="name">{{ $transaction['current_balance'] }}</td>
                                <td class="name">{{ $transaction['note'] }}</td>
                                <td class="name">{{ $transaction['date'] }}</td>
                            </tr>
                            
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- MODAL --}}
<div class="modal fade w-100" id="ajax-crud-modal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="fightModal"></h4>
        </div>
        <div class="modal-body">
            <form id="playerForm" name="playerForm" class="form-horizontal">
               <input type="hidden" name="playerId" id="playerId">
                <div class="form-group">
                    <label for="amount" class="col-sm-2 control-label">Enter Amount</label>
                    <div class="col-sm-12">
                        <input type="number" class="form-control" id="amount" name="amount" value="" required="">
                    </div>
                </div>

                <div class="form-group">
                    <label for="note" class="col-sm-2 control-label">Leave a note</label>
                    <div class="col-sm-12">
                        <input type="text" class="form-control" id="note" name="note" value="" required="">
                    </div>
                </div>

                

                <div class="col-sm-offset-2 col-sm-10">
                 <button type="submit" class="btn text-white" id="btn-save">
                 </button>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            
        </div>
    </div>
    </div>
    </div>

@push('scripts')
<script>
    // Simple Datatable
    let table1 = document.querySelector('#table1');
    let dataTable = new simpleDatatables.DataTable(table1);
</script>

<script>
    $(document).ready(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      
      $('body').on('click', '#deposit', function () {
        $('#fightModal').html("DEPOSIT");
        $('#ajax-crud-modal').modal('show');
        $('#playerId').val($(this).data('id'));
        $('#btn-save').val("deposit");
        $('#btn-save').html("Deposit");
        document.getElementById('btn-save').classList.add('bg-success');
        document.getElementById('btn-save').classList.remove('bg-danger');
     });

     $('body').on('click', '#withdraw', function () {
        $('#fightModal').html("WITHDRAW");
        $('#ajax-crud-modal').modal('show');
        $('#playerId').val($(this).data('id'));
        $('#btn-save').val("withdraw");
        $('#btn-save').html("Withdraw");
        document.getElementById('btn-save').classList.add('bg-danger');
        document.getElementById('btn-save').classList.remove('bg-success');
     });

    });
   
    if ($("#playerForm").length > 0) {
        $("#playerForm").validate({
   
       submitHandler: function(form) {
        $('#btn-save').html('Saving...');
        var playerId = document.getElementById("playerId").value;
        var amount = document.getElementById("amount").value;
        var note = document.getElementById("note").value;
        var saveValue = document.getElementById("btn-save").value;

        $.ajax({
            url: "{{ route('agentDepositWithdraw') }}",
            type: "GET",
            data: {playerId:playerId,amount:amount,note:note,saveValue:saveValue},
            success: function (response) {
                if(response.success){
                    location.reload();
                }else{
                    $('#btn-save').html("Withdraw");
                    alert('NOT ENOUGH POINT!');
                }
            },
            error: function (response) {
                console.log('Error:', response);
            }
        });
      }
    })
  }
     
    
  </script>
@endpush
@endsection
