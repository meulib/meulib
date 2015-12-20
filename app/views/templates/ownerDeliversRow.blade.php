<tr>
	<td>
	Owner delivers within {{$bCopy->Owner->City}} <span style="font-size:75%"><a href="#" data-toggle="modal" data-target="#modalOwnerDelivers">(more info)</a></span></td>
	<td>Rs. {{$bCopy->BorrowingFee+$bCopy->Owner->WithinCityDeliveryRate}}</td>
	<td>
	Rs. {{$bCopy->MarketRate - $bCopy->BorrowingFee-$bCopy->Owner->WithinCityDeliveryRate}}
	</td>
</tr>