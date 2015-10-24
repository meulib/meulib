<div class="modal" id="modalBorrowerCollects" style="display:none">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">You Collect From Owner</h3>
      </div>
      <div class="modal-body">
        <b>Collect from {{$bCopy->Owner->FullName}} in {{$bCopy->Owner->City}} and return to her</b>.<br/>
        Lending period: 1 month. Can be extended maximum to 2 months.<br/>
        <br/>
        <b>Lending Fee charged by {{$bCopy->Owner->FullName}}: Rs. {{$bCopy->BorrowingFee}}</b>.<br/>
        Payment in cash when borrowing.<br/>
        You save approximately Rs. {{1500-$bCopy->BorrowingFee}} compared to purchasing (as per prices on Flipkart).
        <br/>
        <br/>
        {{$bCopy->Owner->FullName}} will give the address to collect the book after approving your request.<br/>
        <br/>
        <button data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
        <br/>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->