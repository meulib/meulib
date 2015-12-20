<tr><td>
	Owner posts within {{$bCopy->Owner->Country}} 
	<span style="font-size:75%">
		<a href="#" data-toggle="modal" data-target="#modalOwnerPosts">(more info)</a>
	</span>
</td>
<td>
	Rs. {{$bCopy->BorrowingFee+$bCopy->PostingRate+$bCopy->PostingRate}} approx.
</td>
<td>
	Rs. {{$bCopy->MarketRate - $bCopy->BorrowingFee-$bCopy->PostingRate-$bCopy->PostingRate}}
</td>
</tr>