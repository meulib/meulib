<?php 

class TransactionController extends BaseController
{
    public function request()
    {
        $loggedIn = false;
        if (!Session::has('loggedInUser'))
            return Redirect::to(URL::to('/'));

        $userID = Session::get('loggedInUser')->UserID;
        $bookCopyID = Input::get('bookCopyID');
        $msg = Input::get('requestMessage');
        $tranID = 0;
        try
        {
            $tranID = Transaction::request($userID,$bookCopyID,$msg);
        }
        catch (Exception $e)
        {
            Session::put('TransactionMessage',['RequestBook','There was some error. Request not sent.']);
        }        

        if ($tranID > 0)
            Session::put('TransactionMessage',['RequestBook','Request Sent.']);
        else
           Session::put('TransactionMessage',['RequestBook','There was some error. Request not sent.']);

        return Redirect::to(URL::previous());
    }

    public function messages($tranID = 0)
    {
        $loggedIn = false;
        if (!Session::has('loggedInUser'))
           return Redirect::to(URL::to('/'))->with('LoginMessage',array('from'=>'Messages','fromURL'=>URL::current()));
        
        $userID = Session::get('loggedInUser')->UserID;

        $msgTransactions = Transaction::openMsgTransactions($userID);
        $msgs = NULL;
        
        if ($msgTransactions)
        {
            if ($tranID > 0)
                $msgs = Transaction::tMessages($tranID,$userID);
        }

        return View::make("messages",array('msgTransactions' => $msgTransactions,'msgs' => $msgs));
        //var_dump($msgTransactions);
    }

    public function reply()
    {
        
        if (!Session::has('loggedInUser'))
            return Redirect::to(URL::to('/'));

        $userID = Session::get('loggedInUser')->UserID;
        $toUserID = Input::get('toUserID');
        $tranID = Input::get('tranID');
        $msg = Input::get('msg');
        $msgID = 0;
        $transaction = Transaction::findOrFail($tranID);
        try
        {
            $msgID = $transaction->reply($userID, $toUserID, $msg);
        }
        catch (Exception $e)
        {
            Session::put('TransactionMessage',['Reply','There was some error. Reply not sent.']);
        }        

        if ($msgID > 0)
            Session::put('TransactionMessage',['Reply','Reply Sent.']);
        else
           Session::put('TransactionMessage',['Reply','There was some error. Reply not sent.']);

        return Redirect::to(URL::previous());
    }

    public function pendingRequests()
    {
        //return "abc";

        if (!Session::has('loggedInUser'))
            return "";

        $bookCopyID = Input::get('bookCopyID');
        $userID = Session::get('loggedInUser')->UserID;
        $trans = Transaction::pendingRequests($bookCopyID,$userID);
        return View::make("templates.lendBookForm",array('bookCopyID' => $bookCopyID,'requestTransactions' => $trans));
    }

    public function lend()
    {
        if (!Session::has('loggedInUser'))
            return Redirect::to(URL::to('/'));

        $userID = Session::get('loggedInUser')->UserID;
        $bookCopyID = Input::get('bookCopyID');
        $borrowerID = Input::get('lendToID'.$bookCopyID);
        $requestsExist = Input::get('existsRequests'.$bookCopyID);
        $tranID = 0;


        if (($borrowerID == -1) || (!$requestsExist))   // Direct Lending via Name, Email, Phone
        {
            $borrowerName = Input::get('bName'.$bookCopyID);
            $borrowerEmail = Input::get('bEmail'.$bookCopyID);
            $borrowerPhone = Input::get('bPhone'.$bookCopyID);

            $result = Transaction::lendDirect($userID,$bookCopyID,$borrowerName,$borrowerEmail,$borrowerPhone);

            if (is_int($result[0])) // transaction id returned
                $tranID = $result[0];
            else
                return Redirect::to(URL::previous())->withErrors(['There was some error. Book lending not recorded. '.$result[1]]);
        }
        else  // Lend to a pending request
        {
            try
            {
                $tranID = Transaction::lend($userID,$bookCopyID,$borrowerID);
            }
            catch (Exception $e)
            {
                return Redirect::to(URL::previous())->withErrors(['There was some error. Book lending not recorded. '.$e->getMessage()]);
            }
        }              

        if ($tranID > 0)
        {
            Session::put('TransactionMessage',['LendBook','Book lending recorded.']);
            //return true;
            return Redirect::to(URL::previous());
        }
            
    }

    public function returnForm()
    {
        if (!Session::has('loggedInUser'))
            return "";

        $bookCopyID = Input::get('bookCopyID');
        $userID = Session::get('loggedInUser')->UserID;
        $tran = Transaction::borrowerByItemCopy($bookCopyID, $userID);
        return View::make("templates.acceptReturnForm",
            array('bookCopyID' => $bookCopyID,'lentRecord' => $tran));
        //return $tran;
    }

    public function acceptReturn()
    {
        if (!Session::has('loggedInUser'))
            return Redirect::to(URL::to('/'));

        // $userID = Session::get('loggedInUser')->UserID;
        // $bookCopyID = Input::get('bookCopyID');
        // $borrowerID = Input::get('returnFromID');
        $tranID = Input::get('transactionID');
        $tran = Transaction::find($tranID);
        $returnedTranID = 0;

        try
        {
            // $tranID = Transaction::returnItem($userID,$bookCopyID,$borrowerID);
            $returnedTranID = $tran->returnItem();

        }
        catch (Exception $e)
        {
            return Redirect::to(URL::previous())->withErrors(['There was some error. Book return not recorded.'.$e->getMessage()]);
            //Session::put('TransactionMessage',['ReturnBook',[false,'There was some error. Book return not recorded.'.$e->getMessage()]]);
        }        

        if ($tranID == $returnedTranID)
        {
            Session::put('TransactionMessage',['ReturnBook','Book return recorded.']);
            return Redirect::to(URL::previous());
        }          
        else
            return Redirect::to(URL::previous())->withErrors(['There was some error. Book return not recorded.']);
        
    }
}
?>