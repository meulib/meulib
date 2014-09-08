var $j = jQuery.noConflict();
var shownDiv = "";

function showDiv(divid)
{
	if (shownDiv != "")
		document.getElementById(shownDiv).style.display = "none";

	document.getElementById(divid).style.display = "block";
	shownDiv = divid;
}

function showDivBookCopy(id,callURL)
{
  if (shownDiv != "")
    document.getElementById(shownDiv).style.display = "none";

  $j.ajax({
         url:callURL,
         data: {bookCopyID: id},
         type: "POST",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "showDiv2"+id
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
  //			    div.innerHTML = 'abc';
            div.style.display = "block";
         },
         error: function( xhr, status )
         {
            alert( "Sorry, there was a problem! " +status );
         },
         async:   false
  });
}