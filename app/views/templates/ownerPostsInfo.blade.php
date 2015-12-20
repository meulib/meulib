<div class="modal" id="modalOwnerPosts" style="display:none">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Owner Posts Within {{$bCopy->Owner->Country}}</h3>
      </div>
      <div class="modal-body">
        <b>Owner can post this book within {{$bCopy->Owner->Country}}. <u> You must post it back.</u><br/></b>
        Lending period: 1 month. Can be extended maximum to 2 months.<br/><br/>
        Posting Fee: Rs. {{$bCopy->PostingRate}}<br/>
        Lending Fee charged by {{$bCopy->Owner->FullName}}: Rs. {{$bCopy->BorrowingFee}}.<br/>
        Cost to you for posting it back: Rs. {{$bCopy->PostingRate}} approx.<br/>
        <b>Total cost to you: Rs. {{$bCopy->BorrowingFee+$bCopy->PostingRate+$bCopy->PostingRate}}</b> approx.<br/>
        <b>You pay to Owner: Rs. {{$bCopy->BorrowingFee+$bCopy->PostingRate}}</b><br/>
        <br/>
        <b>You save Rs. {{$bCopy->MarketRate - $bCopy->BorrowingFee-$bCopy->PostingRate-$bCopy->PostingRate}}</b> approximately, compared to purchasing (as per prices on Flipkart).<br/>
        <br/>
        Payment: via Netbanking.<br/>
        Owner will give her bank account details after approving your request.<br/>
        <br/>
        <br/>
        <button data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Close</span></button>
        <br/>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->