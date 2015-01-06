var $j = jQuery.noConflict();
var shownDiv = "";

function showDiv(divid)
{
	if (shownDiv != "")
		document.getElementById(shownDiv).style.display = "none";

	document.getElementById(divid).style.display = "block";
	shownDiv = divid;
}

// makes db query for pending requests
// and generates lendBookForm
function showLendForm(id,callURL)
{
  if (shownDiv != "")
    document.getElementById(shownDiv).style.display = "none";
  //alert(callURL);

  $j.ajax({
         url:callURL,
         data: {bookCopyID: id},
         type: "POST",
         dataType: "html",
         success: function(result) 
         {
            shownDiv = "showDiv2"+id;    //even this div2 needs to be named better!
            var div = document.getElementById(shownDiv);
            div.innerHTML = result;
  //			    div.innerHTML = 'abc';
            div.style.display = "inline-block";
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
    if (pendingRequests)
    {
        var lendToID = $j("input[name=lendToID"+id+"]:checked").val();
        if (!lendToID)
            msg = "Please specify who you are lending to.";
        else
            if (lendToID == -1)
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
