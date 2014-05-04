var $j = jQuery.noConflict();

var shownDiv = "";
function lendDiv(id)
{
  if (shownDiv != "")
    document.getElementById(shownDiv).style.display = "none";

  $j.ajax({
         url:"ui_includes/getPendingRequests.php",
         data: {bookCopyID: id},
         type: "GET",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "lendBook"+id
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
            div.style.display = "block";
         },
         error: function( xhr, status )
         {
            alert( "Sorry, there was a problem!" );
          },
         async:   false
    });
}

function returnDiv(id)
{
    if (shownDiv != "")
        document.getElementById(shownDiv).style.display = "none";

    $j.ajax({
        url:"ui_includes/formAcceptReturn.php",
         data: {bookCopyID: id},
         type: "GET",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "returnBook"+id
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
            div.style.display = "block";
         },
         error: function( xhr, status )
         {
            alert( "Sorry, there was a problem!" );
          },
         async:   false
    });
}