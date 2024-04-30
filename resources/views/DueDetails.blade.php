<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>RTC Online Payment</title>
        <link rel="stylesheet" type="text/css" href="style.css"/>
		<link rel='icon' href='images/favicon.ico' type='image/x-icon'/> 
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
		integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="{{ asset('w3.css') }}">
		<link rel="stylesheet" type="text/css" href="new.css"/>        
        <script type='text/javascript' src="{{asset('bootstrap.min.js')}}"></script>    
		<script type='text/javascript' src="{{asset('dropdown.js')}}"></script>      
    </head>   
		
    <body>
    <div class="w3-border w3-content w3-margin-top w3-card-4">
   <img src="{{ asset('img/logo.png') }}" alt="Header Picture" class="images">
    <div class=" w3-container w3-center w3-margin">
      <img src="{{ asset('img/a.png') }}" alt="Payment Details" class="images">
    </div>

    <div class="w3-container w3-blue">  
  <h1>Outstanding Dues</h1>
</div>
<div class="w3-container w3-margin">
 <?php foreach($paymentdetails as $pd){ ?>
        Enrollment No.: {{ $pd ->EnrollmentNumber }} 
        <input type="hidden" value="{{ $pd ->EnrollmentNumber }}" id="elno"><br><br>
        Student Name: {{ $pd ->StudentName }}
         <input type="hidden" value="{{ $pd ->StudentName }}" id="sname"><br><br>
		 Student Phone Number: {{$email->CellPhone}} <br><br>
	<?php if($pd->Due>=0.00){ ?>		 
				<div>
						Email Address: {{$email->EMailRTC}}@rtc.bt<br><br>						
						No outstanding dues at the time of displaying this page.<br><br>
						<a href="{{ url()->previous() }}" type="button" class="w3-button w3-round-xlarge w3-green w3-hover-lightgray">Back</a>
				</div>	 
	<?php } else{ ?>
		
			<div>
			 <div class="card">
				<strong>A copy of the payment receipt will be emailed automatically to these email addresses.</strong>
				RTC email address:&nbsp{{$email->EMailRTC}}@rtc.bt<br>
				Student’s personal email address:&nbsp{{$email->EmailOther}}<br>
				Parent/Guardian’s email address:&nbsp{{$email->InchargeEMail}}<br></div>			
				<button type="button" data-toggle="modal" data-target="#Modal"> Update contact details</button>
				   <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					 <div class="modal-dialog modal-lg" role="document">
						<div class="modal-content">
						  <div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Update contact details</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							  <span aria-hidden="true">&times;</span>
							</button>
						  </div>
						  <div class="modal-body">
						<form action="{{url('/uemail')}}" method="post"> 
							<b>Please update only if you have changed your phone number or email.</b><br>
							Student's phone number: <input class="wid" type="text" name="phone" value="{{$email->CellPhone}}"/></br> 
							Student’s personal email address: <input class="wid" type="email" name="email" value="{{$email->EmailOther}}"/></br>
							Parent/guardian’s email address: <input class="wid" type="email" name="inchargeemail" value="{{$email->InchargeEMail}}"/>							
							<input type="hidden" name="enum" value="{{ $pd ->EnrollmentNumber }}"/>
						  </div>
						  <div class="modal-footer">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary">Save changes</button>
						</form>
						  </div>
						</div>
					  </div>
					</div>					
		 <br><br>
			<div class="w-50 p-1">						
				<form  action="{{url('/sumitems')}}" method="POST">
				<table class="table table-hover">
				@csrf
				<tr><th>Select Dues</th><th>Amount(Nu.)</th></tr>
				<input type="hidden" name="enrl" value="{{ $pd ->EnrollmentNumber }}">
				<?php if (($pd ->TuitionBalance)<0){ ?>				
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "2 ".number_format(abs($pd ->TuitionBalance),2);?>"> Tuition </td><td><?php echo number_format(abs($pd ->TuitionBalance),2); }?></td></tr>
				<?php if(($pd ->RoomQuadBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "4 ".number_format(abs($pd ->RoomQuadBalance),2);?>"> Room </td><td><?php echo number_format(abs($pd ->RoomQuadBalance),2); }?></td></tr>
				<?php if(($pd ->RoomTripleBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "5 ".number_format(abs($pd ->RoomTripleBalance),2);?>"> Room </td><td><?php echo number_format(abs($pd ->RoomTripleBalance),2); }?></td></tr>
				<?php if(($pd ->RoomDoubleBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "6 ".number_format(abs($pd ->RoomDoubleBalance),2);?>">  Room </td><td><?php echo number_format(abs($pd ->RoomDoubleBalance),2); }?></td></tr>
				<?php if(($pd ->FoodBalance)<0){?>
				 	<tr><td><input type="checkbox" name="bal[]" value="<?php echo "7 ".number_format(abs($pd ->FoodBalance),2);?>"> Food </td><td><?php echo number_format(abs($pd ->FoodBalance),2); }?></td></tr>
				<?php if(($pd ->LibraryFineBalance)<0){?>
					<tr><td><input type="checkbox" name="bal[]" value="<?php echo "8 ".number_format(abs($pd ->LibraryFineBalance),2);?>"> LibraryFineBalance </td><td><?php echo number_format(abs($pd ->LibraryFineBalance),2); }?></td></tr>
				<?php if(($pd ->VandalismBalance)<0){ ?>
					<tr><td><input type="checkbox" name="bal[]" value="<?php echo "9 ".number_format(abs($pd ->VandalismBalance),2);?>"> VandalismBalance </td><td><?php echo number_format(abs($pd ->VandalismBalance),2); }?></td></tr>
				<?php if(($pd ->TranscriptRBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "99 ".number_format(abs($pd ->TranscriptRBalance),2);?>"> TranscriptRBalance </td><td><?php echo number_format(abs($pd ->TranscriptRBalance),2); }?></td></tr>
				<?php if(($pd ->SecurityDepositBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "16 ".number_format(abs($pd ->SecurityDepositBalance),2);?>"> SecurityDepositBalance </td><td><?php echo number_format(abs($pd ->SecurityDepositBalance),2); }?></td></tr>
				<?php if(($pd ->CETuitionBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "18 ".number_format(abs($pd ->CETuitionBalance),2);?>"> CETuitionBalance </td><td><?php echo number_format(abs($pd ->CETuitionBalance),2); }?></td></tr>	
				<?php if(($pd ->LockerRentalBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "71 ".number_format(abs($pd ->LockerRentalBalance),2);?>"> LockerRentalBalance </td><td><?php echo number_format(abs($pd ->LockerRentalBalance),2); }?></td></tr>
				<?php if(($pd ->ExamRecheckBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "93 ".number_format(abs($pd ->ExamRecheckBalance),2);?>"> ExamRecheckBalance </td><td><?php echo number_format(abs($pd ->ExamRecheckBalance),2); }?></td></tr>
				<?php if(($pd ->FTMRepeatBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "29 ".number_format(abs($pd ->FTMRepeatBalance),2);?>"> FTMRepeatBalance </td><td><?php echo number_format(abs($pd ->FTMRepeatBalance),2); }?></td></tr>
				<?php if(($pd ->PTMRepeatBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "30 ".number_format(abs($pd ->PTMRepeatBalance),2);?>"> PTMRepeatBalance </td><td><?php echo number_format(abs($pd ->PTMRepeatBalance),2); }?></td></tr>
				<?php if(($pd ->WinterSessionBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "31 ".number_format(abs($pd ->WinterSessionBalance),2);?>"> WinterSessionBalance </td><td><?php echo number_format(abs($pd ->WinterSessionBalance),2); }?></td></tr>
				<?php if(($pd ->EnrollmentBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "32 ".number_format(abs($pd ->EnrollmentBalance),2);?>"> EnrollmentBalance </td><td><?php echo number_format(abs($pd ->EnrollmentBalance),2); }?></td></tr>
				<?php if(($pd ->RExamBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "35 ".number_format(abs($pd ->RExamBalance),2);?>"> RExamBalance </td><td><?php echo number_format(abs($pd ->RExamBalance),2); }?></td></tr>
				<?php if(($pd ->LateFeeBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "36 ".number_format(abs($pd ->LateFeeBalance),2);?>"> LateFeeBalance </td><td><?php echo number_format(abs($pd ->LateFeeBalance),2); }?>	</td></tr>
				<?php if(($pd ->LostIDBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "0 ".number_format(abs($pd ->LostIDBalance),2);?>"> LostIDBalance </td><td><?php echo number_format(abs($pd ->LockerRentalBalance),2); }?></td></tr>
				<?php if(($pd ->OtherFineBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "38 ".number_format(abs($pd ->OtherFineBalance),2);?>"> OtherFineBalance </td><td><?php echo number_format(abs($pd ->OtherFineBalance),2); }?></td></tr>
				<?php if(($pd ->PhotoBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "48 ".number_format(abs($pd ->PhotoBalance),2);?>"> PhotoBalance </td><td><?php echo number_format(abs($pd ->PhotoBalance),2); }?></td></tr>
				<?php if(($pd ->BridgeCourseBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "50 ".number_format(abs($pd ->BridgeCourseBalance),2);?>"> BridgeCourseBalance </td><td><?php echo number_format(abs($pd ->BridgeCourseBalance),2); }?></td></tr>
				<?php if(($pd ->SmokingViolationBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "51 ".number_format(abs($pd ->SmokingViolationBalance),2);?>"> SmokingViolationBalance </td><td><?php echo number_format(abs($pd ->SmokingViolationBalance),2); }?></td></tr>
				<?php if(($pd ->FoodSurchargeBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "7 ".number_format(abs($pd ->FoodSurchargeBalance),2);?>"> FoodSurchargeBalance </td><td><?php echo number_format(abs($pd ->FoodSurchargeBalance),2); }?></td></tr>
				<?php if(($pd ->PCFeeBalance)<0){ ?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "75 ".number_format(abs($pd ->PCFeeBalance),2);?>"> PCFeeBalance </td><td><?php echo number_format(abs($pd ->PCFeeBalance),2); }?></td></tr>
				<?php if(($pd ->RDSBalance)<0){?>
				<tr><td><input type="checkbox" name="bal[]" value="<?php echo "78 ".number_format(abs($pd ->RDSBalance),2);?>"> RDSBalance </td><td><?php echo number_format(abs($pd ->RDSBalance),2); }?></td></tr>
				</table>
				<br><input class="w3-button w3-round-xlarge w3-green w3-hover-lightgray" value="Submit" type="submit"/>
				</form>				
			  </div>	
			</div> 
		
	<?php } } ?>       
</div>	 
<br>

<footer class="w3-center w3-container w3-blue">
		
    <p>(c)<?php echo date("Y"); ?> Royal Thimphu College</p>
</footer>
</div>
	
</body>
</html>


