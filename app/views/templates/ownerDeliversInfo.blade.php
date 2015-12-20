<div class="modal" id="modalOwnerDelivers" style="display:none">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Owner Delivers Within {{$bCopy->Owner->City}}</h3>
      </div>
      <div class="modal-body">
        <b>Owner will deliver the book within {{$bCopy->Owner->City}} <u>and</u> collect it back.</b>.<br/>
        Lending period: 1 month. Can be extended maximum to 2 months.<br/>
        Delivery Service Fee: Rs. {{$bCopy->Owner->WithinCityDeliveryRate}}<br/>
        <br/>
        Lending Fee charged by {{$bCopy->Owner->FullName}}: Rs. {{$bCopy->BorrowingFee}}.<br/>
        <b>Total cost to you: Rs. {{$bCopy->Owner->WithinCityDeliveryRate+$bCopy->BorrowingFee}}</b><br/>
        Payment: Cash on delivery.<br/>
        <b>You save Rs. {{$bCopy->MarketRate - $bCopy->BorrowingFee-$bCopy->Owner->WithinCityDeliveryRate}}</b> approximately, compared to purchasing (as per prices on Flipkart).
        <br/>
        <br/>
        <button data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
        <br/>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->