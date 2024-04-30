<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Facades\Schema;
//use DB;
use Alert;
use Mail;

class PaymentDetailsViewContoller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {     
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
                    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //first page for student to select dues
        $var=$request->input('EnrlNo');
		$dob=$request->input('year')."-".$request->input('month')."-".$request->input('date');
        if(!empty($var))
            {                                 
                $paymentdetails=DB::table('vwWEBStudentBalancesForPayment')
                                ->where([
                                        ['EnrollmentNumber',$request->input('EnrlNo')],
                                        ['DOB',$dob]                                            
                                        ])
                                ->get();   
                                                                
                if(count($paymentdetails)>0){  

                    $send_mail = 'chimilham@rtc.bt';
  
                    dispatch(new SendEmailJob($send_mail));



					$email=DB::table('tblStudents')
							->where ('EnrollmentNumber',$request->input('EnrlNo'))
							->first();             
							
                   return view('DueDetails',['paymentdetails' =>$paymentdetails],['email'=>$email]);
					
                }else 
                {                    
                    return redirect()->back()->with('success','Student Does not exist');
                }
            }
        else{
                $paymentdetails=DB::table('tblGroundBooking')
                                ->where([
                                        ['ReferenceNumber', $request->input('ref')],
										['MobileNumber', $request->input('phone')]
                                        ])
                                ->get();
                if(count($paymentdetails)>0){
                    return view('GroundOutstanding',['paymentdetails' =>$paymentdetails]);
                }
                else{
                    return redirect()->back()->with('success','reference number Does not exist');
                }
			}
            
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
	    //echo "test";
       return view('failure');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(request $request)
    {	
        //to update students phone number
        $email=	$request->input('email');
		$eno= $request->input('enum');	
		$inemail= $request->input('inchargeemail');
		$phonenum=$request->input('phone');		
	
		   DB::table('tblStudents')
			  ->where('EnrollmentNumber',$eno)
			  ->update(['InchargeEMail' => $inemail]);
		   DB::table('tblStudents')
			  ->where('EnrollmentNumber',$eno)
			  ->update(['EmailOther' => $email]);
		    DB::table('tblStudents')
			  ->where('EnrollmentNumber',$eno)
			  ->update(['CellPhone' => $phonenum]);
        
		
		$paymentdetails=DB::table('vwWEBAPIstudentFeesDetails')
                         ->where('EnrollmentNumber',$eno)
                         ->get();
			
  
		    if(count($paymentdetails)>0){
				
					$email=DB::table('tblStudents')
							->where ('EnrollmentNumber', $eno)
							->first();

                   return view('DueDetails',['paymentdetails' =>$paymentdetails],['email'=>$email]);
					
               }else 
                {                    
                   return redirect()->back()->with('success','Student Does not exist');
                }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**$update=DB::table('tblStudents')
			->where('EnrollmentNumber',$id)
			->update(['InchargeEMail' => $request->input('mail')]);
			
			 return redirect()->route('payment.create');*/
    }
    public function cancel()
    {
        return redirect("/")->with('success','Your payment has been cancelled');;
    }

    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function accountinfo(Request $request)
    {
        //for outstanding page
     $en=$request->input('enrl');
     $ForPayments=($_POST['bal']);
     $number = "";
	    for($i=0; $i<10; $i++) {
		$min = ($i == 0) ? 1:0;
		$number .= mt_rand($min,9);} 
     $bfs_orderNo = date('Ymd').$number; 
  
     if(isset($ForPayments))
     {        
       foreach($ForPayments as $pay)
       {
         $num=(explode(" ",$pay));         
         DB::table('tblSelectedForPayments')
          ->insert(['EnrollmentNumber'=>$en, 'OrderNumber'=>$bfs_orderNo, 'Amounts'=>$num[1],'SelectedForPayment'=>$num[0]]);
       }

     }  
     $stddetail=DB::table('tblStudents')
            ->where ('EnrollmentNumber',$en)
            ->first();
     $paymentdetails=DB::table('tblSelectedForPayments')
                    ->select('tblSelectedForPayments.EnrollmentNumber','tblSelectedForPayments.Amounts','tlkpTransactionItems.TransactionItemName')
                    ->join('tlkpTransactionItems', 'tlkpTransactionItems.TransactionItemNum', '=', 'tblSelectedForPayments.SelectedForPayment')
                ->where([
                        ['EnrollmentNumber',$en],
                        ['OrderNumber', $bfs_orderNo]                                    
                        ])
                ->get();
        
         $sum=DB::table('tblSelectedForPayments')                               
                ->where([['EnrollmentNumber',$en],['OrderNumber', $bfs_orderNo]])
                ->SUM('Amounts');             
      
	return view('OutStanding',['paymentdetails'=>$paymentdetails, 'em'=>$stddetail , 'orderno'=>$bfs_orderNo, 'totaldue'=>$sum]);
    }

}
