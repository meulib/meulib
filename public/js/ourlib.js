var $j = jQuery.noConflict();
var shownDiv = "";

function showDiv(divid)
{
	if (shownDiv != "")
		document.getElementById(shownDiv).style.display = "none";

	document.getElementById(divid).style.display = "inline-block";
	shownDiv = divid;
}

// to show divs where content will come from a post
// ajax call
function showPostDiv(id,callURL)
{
  // alert('in showPostDiv '+id+' '+callURL);
  if (shownDiv != "")
    document.getElementById(shownDiv).style.display = "none";
  $j.ajax({
         url:callURL,
         data: {idVal: id},
         type: "POST",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "postDiv"+id;  
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
  //          div.innerHTML = 'abc';
            div.style.display = "inline-block";
            event.preventDefault();
         },
         error: function( xhr, status )
         {
            alert( "Sorry, there was a problem! " +status );
         },
         async:   false
  });
}

function hideDiv(id)
{
    divid = 'postDiv'+id;
    document.getElementById(divid).style.display = "none";
}

// makes db query for pending requests
// and generates lendBookForm
function showLendForm(id,giveAway,callURL)
{
  if (shownDiv != "")
    document.getElementById(shownDiv).style.display = "none";
  //alert(callURL);

  $j.ajax({
         url:callURL,
         data: {bookCopyID: id,forGiveAway:giveAway},
         type: "POST",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "showLendDiv"+id;    //even this div2 needs to be named better!
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
  //			    div.innerHTML = 'abc';
            div.style.display = "inline-block";
            event.preventDefault();
         },
         error: function( xhr, status )
         {
            alert( "Sorry, there was a problem! " +status );
         },
         async:   false
  });
}

function showLendOtherForm(divid)
{
    document.getElementById(divid).style.display = "block";
}

function hideLendOtherForm(divid)
{
    document.getElementById(divid).style.display = "none";
}

function validateDirectLendingForm(id, shouldHaveSomething)
{
    var bName = document.getElementsByName('bName'+id)[0].value;
    var bEmail = document.getElementsByName('bEmail'+id)[0].value;
    var bPhone = document.getElementsByName('bPhone'+id)[0].value;
    var reEmail = /^.+@.+\..+$/;
    var rePhone = /^[0-9]+$/;
    var msg = "";
    var success = true;
    if (!bName)
    {       
        msg = "Borrower's name required. ";
        success = false;
    }
    if ((!bEmail)&&(!bPhone))
    {
        msg += "Borrower's email or phone required.";
        success = false;   
    }
    if ((bEmail) && (!reEmail.test(bEmail)))
    {
        msg += "Please enter a valid email address. ";
        success = false;
    }
    if ((bPhone) && (!rePhone.test(bPhone)))
    {
        msg += "Please enter only digits in phone number.";
        success = false;
    }
    if (success)
        return [success,bName,bEmail,bPhone];
    else
        return [success,msg];
}

function lendFormSubmit(id,callURL) 
{
    var pendingRequests = document.getElementsByName('existsRequests'+id)[0].value;
    var success = false;
    var msg = "";
    var msgHolder = document.getElementById('lendFormMsg'+id);
    var bName, bEmail, bPhone;
    var giveAway = document.getElementsByName('giveAway'+id)[0].value;
    if (pendingRequests)
    {
        var userToID = $j("input[name=userToID"+id+"]:checked").val();
        if (!userToID)
            msg = "Please specify who you are lending to.";
        else
            if (userToID == -1)
            {
                okDirectLendingForm = validateDirectLendingForm(id,true);
                if (okDirectLendingForm[0])
                    success = true;
                else
                    msg = okDirectLendingForm[1];
            }
            else
                success = true;
    }       
    else
    {
        okDirectLendingForm = validateDirectLendingForm(id,true);
        if (okDirectLendingForm[0])
        {
            success = true;
            bName = okDirectLendingForm[1];
            bEmail = okDirectLendingForm[2];
            bPhone = okDirectLendingForm[3];
        }
        else
            msg = okDirectLendingForm[1];
    }
    if (!success)
    {
        msgHolder.innerHTML = msg;
        msgHolder.style.display = "inline";
    }
    else // validation success - post form
    {
        console.log('success');
        if (giveAway == 1)
            msgHolder.innerHTML = "Recording give-away ... ";
        else
            msgHolder.innerHTML = "Saving lend details ... ";
        msgHolder.style.display = "inline";
        document.forms["lendForm"+id].submit();

        /*$j.ajax({
             url:callURL,
             data: {bookCopyID: id,
                    lendToID: lendToID,
                    bName: bName,
                    bEmail: bEmail,
                    bPhone: bPhone
                    },
             type: "POST",
             dataType: "html",
             success: function(result) 
             {
                msgHolder.innerHTML = "Lending recorded.";
             },
             error: function( xhr, status )
             {
                alert( "Sorry, there was a problem! " +status );
             },
             async:   false
        });*/
    }
    
}

function showHideDiv(showId, hideId, showMode)
{
    document.getElementById(hideId).style.display = "none";
    document.getElementById(showId).style.display = showMode;
}

function showPopup(popupId,showMode)
{
    // transparent layer div
    document.getElementById('transparentHiderDiv').style.display = "block";
    // popup element
    document.getElementById(popupId).style.display = showMode;
}

function closePopup(popupId)
{
    // transparent layer div
    document.getElementById('transparentHiderDiv').style.display = "none";
    // popup element
    document.getElementById(popupId).style.display = "none";
}