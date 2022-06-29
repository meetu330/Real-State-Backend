<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Properties;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Response;
use App\User;
use Auth;

class PropertiesController extends Controller
{
    public function create(Request $req, $id){

        // Start merge array Prepayment panelaty & date
        // for penalty
            $userId = $id;
            
            if($req->PrePaymentSelect == 1){

                $PrePaymentpanaltyarray = array();

                $PrePaymentarraypenalty = array();

                array_push($PrePaymentpanaltyarray,$req->PrePaymentPenlty);

                if($req->PrePaymentDates != null || $req->PrePaymentPenltys != null){

                $PrePaymentarraypenalty = array_merge($PrePaymentpanaltyarray,$req->PrePaymentPenltys);

                }else{
                    array_push($PrePaymentarraypenalty, $req->PrePaymentPenlty);
                }

                // end penalty

                // for date
                $PrePaymentDatearray = array();

                $PrePaymentDatesarray = array();

                $PrePaymentDatesarrays = array();

                $Prepaymentyear = array();
                // end date

                array_push($PrePaymentDatearray, $req->PrePaymentDate);

                if($req->PrePaymentDates != null || $req->PrePaymentPenltys != null){

                $PrePaymentDatesarray = array_merge($PrePaymentDatearray,$req->PrePaymentDates);

                }else{
                    array_push($PrePaymentDatesarray, $req->PrePaymentDate);
                }

                foreach($PrePaymentDatesarray as $key=>$Prepaymentarray)
                {

                    array_push($PrePaymentDatesarrays,$Prepaymentarray.' = '.$PrePaymentarraypenalty[$key]);

                    $date = $req->MaturityDate;
                    array_push($Prepaymentyear, date('Y-m-d', strtotime($date. ' - '.$Prepaymentarray)));
                    
                }
                $mergeprepeymentdate = implode(",\n ",$PrePaymentDatesarrays);

            }else{
                $mergeprepeymentdate = '';
            }

        // End merge array Prepayment panelaty & date

        if($req->InterestTermsEndDateSelect == 1){
            $interestonlyterm = Carbon::parse($req->InterestTermsEndDate)->format('m/d/Y');
        }else{
            $interestonlyterm = '';
        }

        // for store renewal,rate change data in property table
        if($req->RenewalSelect == 1){
            $renewalterm = $req->RenewalTerms;
            $renewaldate = Carbon::parse($req->RenewalDate)->format('m/d/Y');
        }else{
            $renewalterm = '';
            $renewaldate = '';
        }
        if($req->RateSelect == 1){
            $ratedate = Carbon::parse($req->RateDate)->format('m/d/Y');
            $ratecomment = $req->RateComment;
        }else{
            $ratedate = '';
            $ratecomment = '';
        }
        if($req->PrePaymentSelect == 1){
            $prepaymentdate = $req->PrePaymentDate;
            $prepaymentpenalty = $req->PrePaymentPenlty;
            $prepaymentenddate = Carbon::parse($req->PrePeymentPenaltyEndDate)->format('m/d/Y');
        }else{
            $prepaymentdate = '';
            $prepaymentpenalty = '';
            $prepaymentenddate = '';
        }

        $properties = new Properties;
        $properties->owner = $req->Owner;
        $properties->address = $req->Address;
        $properties->appraised_value = $req->AppraisedValue;
        $properties->appraised_date = Carbon::parse($req->AppraisedDate)->format('m/d/Y');
        $properties->bank = $req->Bank;
        $properties->starting_principle = $req->StartingPrinciple;
        $properties->loan = Carbon::parse($req->Loan)->format('m/d/Y');
        $properties->monthly_amount_due = $req->MonthlyAmount;
        $properties->current_bal = $req->CurrentBalance;
        $properties->escrow_bal = $req->EscrowBalance;
        $properties->term_leng = $req->TermLength;
        $properties->rate = $req->Rate;
        $properties->interest_only_term = $interestonlyterm;
        $properties->pre_paypment_penalties_end_date = $prepaymentenddate;
        $properties->yield_maintenance = $req->YieldMantenance;
        $properties->prior_notice = $req->PriorNotice;
        $properties->renewal_term = $renewalterm;
        $properties->renewal_date = $renewaldate;
        $properties->rate_date = $ratedate;
        $properties->rate_comment = $ratecomment;
        $properties->prepayment_date_penalty = $mergeprepeymentdate;
        $properties->maturity_date = Carbon::parse($req->MaturityDate)->format('m/d/Y');
        $properties->userid = $userId;
        $properties->save();
       
        if($req->MaturityDate != null){
            $calendar = new Calendar;
            $calendar->title = 'Maturity';
            $calendar->date = $req->MaturityDate;
            $calendar->backgroundColor = '#d4cfc3'; 
            $calendar->userid = $userId;
            $calendar->save();

            $calenarevent = new CalendarEvent;
            $calenarevent->title = 'Maturity';
            $calenarevent->date = $req->MaturityDate;
            $calenarevent->backgroundColor = 'd4cfc3';
            $calenarevent->description = '';
            $calenarevent->property = $req->Address;
            $calenarevent->bank = $req->Bank;
            $calenarevent->label = 'Maturity';
            $calenarevent->userid = $userId;
            $calenarevent->save();
        }

        if($req->RenewalSelect == 1){
            if($req->RenewalDate != null){
                $calendar = new Calendar;
                $calendar->title = 'Renewal';
                $calendar->date = $req->RenewalDate;
                $calendar->backgroundColor = '#4cb5db'; 
                $calendar->userid = $userId;
                $calendar->save();
    
                $calenarevent = new CalendarEvent;
                $calenarevent->title = 'Renewal';
                $calenarevent->date = $req->RenewalDate;
                $calenarevent->backgroundColor = '4cb5db';
                $calenarevent->description = $req->RenewalTerms;
                $calenarevent->property = $req->Address;
                $calenarevent->bank = $req->Bank;
                $calenarevent->label = 'Renewal';
                $calenarevent->userid = $userId;
                $calenarevent->save();
            }
        }

        if($req->RateSelect == 1){
            if($req->RateDate != null){
                $calendar = new Calendar;
                $calendar->title = 'Rate Change';
                $calendar->date = $req->RateDate;
                $calendar->backgroundColor = '#4b4b4b'; 
                $calendar->userid = $userId;
                $calendar->save();
                
                $calenarevent = new CalendarEvent;
                $calenarevent->title = 'Rate Change';
                $calenarevent->date = $req->RateDate;
                $calenarevent->backgroundColor = '4b4b4b';
                $calenarevent->description = $req->RateComment;
                $calenarevent->property = $req->Address;
                $calenarevent->bank = $req->Bank;
                $calenarevent->label = 'Rate Change';
                $calenarevent->userid = $userId;
                $calenarevent->save();
                
            }
        }
        
        if($req->PrePaymentSelect == 1){
            if($PrePaymentDatesarray != null){
                    foreach($Prepaymentyear as $PrePaymentDate){
                        $calendar = new Calendar;
                        $calendar->title = 'Pre-Pay Penalty';
                        $calendar->date = $PrePaymentDate;
                        $calendar->backgroundColor = '#017058'; 
                        $calendar->userid = $userId;
                        $calendar->save();
                    }
                    foreach($Prepaymentyear as $key=>$PrePaymentPenlty){
                        $calenarevent = new CalendarEvent;
                        $calenarevent->title = 'Pre-Pay Penalty';
                        $calenarevent->date = $PrePaymentPenlty;
                        $calenarevent->backgroundColor = '017058';
                        $calenarevent->description = $PrePaymentarraypenalty[$key];
                        $calenarevent->property = $req->Address;
                        $calenarevent->bank = $req->Bank;
                        $calenarevent->label = 'Pre-Pay Penalty';
                        $calenarevent->userid = $userId;
                        $calenarevent->save();
                    }
                // }
            }
        }

        return Response::json(['message' => 'Properties created sucessfully!','Status'=>'1']);
    
    }
    public function getproperty($id){
        $property = Properties::where('userid',$id)->orderBy('id', 'DESC')->get();
        return $property;
    }
    public function sorting($name, $slug, $id){

        if($name == 'owner'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('owner','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('owner','DESC')->get();
                return $property;
            }
        }else if($name == 'appr_val'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('appraised_value','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('appraised_value','DESC')->get();
                return $property;
            }
        }else if($name == 'property'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('address','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('address','DESC')->get();
                return $property;
            }
        }else if($name == 'appr_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('appraised_date','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('appraised_date','DESC')->get();
                return $property;
            }
        }else if($name == 'lender'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('bank','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('bank','DESC')->get();
                return $property;
            }
        }else if($name == 'starting_principle'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('starting_principle','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('starting_principle','DESC')->get();
                return $property;
            }
        }else if($name == 'loan_origination_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('loan','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('loan','DESC')->get();
                return $property;
            }
        }else if($name == 'monthly_amount_due'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('monthly_amount_due','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('monthly_amount_due','DESC')->get();
                return $property;
            }
        }else if($name == 'current_bal'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('current_bal','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('current_bal','DESC')->get();
                return $property;
            }
        }else if($name == 'escrow_bal'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('escrow_bal','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('escrow_bal','DESC')->get();
                return $property;
            }
        }else if($name == 'maturity_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('maturity_date','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('maturity_date','DESC')->get();
                return $property;
            }
        }else if($name == 'term_leng'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('term_leng','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('term_leng','DESC')->get();
                return $property;
            }
        }else if($name == 'rate'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('rate','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('rate','DESC')->get();
                return $property;
            }
        }else if($name == 'interest_only_term'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('interest_only_term','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('interest_only_term','DESC')->get();
                return $property;
            }
        }else if($name == 'renewal_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('renewal_date','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('renewal_date','DESC')->get();
                return $property;
            }
        }else if($name == 'renewal_term'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('renewal_term','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('renewal_term','DESC')->get();
                return $property;
            }
        }else if($name == 'ratechange_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('rate_date','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('rate_date','DESC')->get();
                return $property;
            }
        }else if($name == 'ratechange_comment'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('rate_comment','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('rate_comment','DESC')->get();
                return $property;
            }
        }else if($name == 'pre_paypment_penalties_end_date'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('pre_paypment_penalties_end_date','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('pre_paypment_penalties_end_date','DESC')->get();
                return $property;
            }
        }else if($name == 'yield_maintenance'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('yield_maintenance','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('yield_maintenance','DESC')->get();
                return $property;
            }
        }else if($name == 'prior_notice'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('prior_notice','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('prior_notice','DESC')->get();
                return $property;
            }
        }else if($name == 'prepaymentdate_penalty'){
            if($slug == 'true'){
                $property = Properties::where('userid',$id)->orderBy('prepayment_date_penalty','ASC')->get();
                return $property;
            }else{
                $property = Properties::where('userid',$id)->orderBy('prepayment_date_penalty','DESC')->get();
                return $property;
            }
        }

    }
}
