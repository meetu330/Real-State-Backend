<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Calendar;
use App\Models\CalendarEvent;
use Carbon\Carbon;
use Response;
use Auth;

class CalendarController extends Controller
{
// ======================================================= Get all events ======================================================
    public function index($id){
        $calendar = Calendar::where('userid',$id)->select('title','date','backgroundColor')->get();
        return $calendar;
    }
// ======================================================== Create new event ====================================================
    // public function create(Request $req){
    //     $calendar = new Calendar;
    //     $calendar->title = $req->title;
    //     $calendar->date = $req->date;
    //     $calendar->backgroundColor = $req->backgroundColor;
    //     $calendar->save();
    //     $calendars = new CalendarEvent;
    //     $calendars->title = $req->eventtitle;
    //     $calendars->date = $req->date;
    //     $calendars->backgroundColor = $req->backgroundColor;
    //     $calendars->description = $req->description;
    //     $calendars->save();
    //     return Response::json(['message' => 'Event created sucessfully !'],201);
    // }
// ================================================== Get event color wise ===============================================
    public function getevent($id){

        $color1 = $_GET['color'];
        if($color1 == 'all'){
            $getevent = CalendarEvent::where('userid',$id)->orderBy('date', 'ASC')->get();
            return $getevent;
        }elseif($color1 == 'lightblue'){
            $color = '4cb5db';
            $getevent = CalendarEvent::where('userid',$id)->where('backgroundColor',$color)->orderBy('date', 'ASC')->get();
            return $getevent;
        }elseif($color1 == 'grey'){
            $color = '4b4b4b';
            $getevent = CalendarEvent::where('userid',$id)->where('backgroundColor',$color)->orderBy('date', 'ASC')->get();
            return $getevent;
        }elseif($color1 == 'green'){
            $color = '017058';
            $getevent = CalendarEvent::where('userid',$id)->where('backgroundColor',$color)->orderBy('date', 'ASC')->get();
            return $getevent;
        }elseif($color1 == 'white'){
            $color = 'd4cfc3';
            $getevent = CalendarEvent::where('userid',$id)->where('backgroundColor',$color)->orderBy('date', 'ASC')->get();
            return $getevent;
        }
            
        if(count($getevent) > 0)
		{
			$data['Status']     = '1';
			$data['message']  	= 'Event Data Get Successfully';
			$data['data']       = $getevent;
		}
		else
		{
			$data['Status']     = '0';
			$data['message']  	= 'Data Not Found';
			$data['data']     	= array();
		}
		
 		echo json_encode($data);
        
    }
// ================================================ Get event date wise =============================================
    public function geteventdate(){
        $date = $_GET['date'];

        $getevent = CalendarEvent::where('date',$date)->get();
        return $getevent;
            
        if(count($getevent) > 0)
		{
			$data['Status']     = '1';
			$data['message']  	= 'Event Data Get Successfully';
			$data['data']       = $getevent;
		}
		else
		{
			$data['Status']     = '0';
			$data['message']  	= 'Data Not Found';
			$data['data']     	= array();
		}
		
 		echo json_encode($data);
    }
// ========================================================= Get Today's event =================================================
    public function gettodaysevent($id){
        $date = Carbon::now();
        $date1 = $date->toDateString();

        $getevent = CalendarEvent::where('date',$date1)->where('userid',$id)->get();
        return $getevent;

        if(count($getevent) > 0)
		{
			$data['Status']     = '1';
			$data['message']  	= 'Event Data Get Successfully';
			$data['data']       = $getevent;
		}
		else
		{
			$data['Status']     = '0';
			$data['message']  	= 'Data Not Found';
			$data['data']     	= array();
		}
 		echo json_encode($data);
    }

    public function getallcalendarevent($id){

        $getevent = CalendarEvent::where('userid',$id)->orderBy('date', 'ASC')->get();
        return $getevent;

        if(count($getevent) > 0)
		{
			$data['Status']     = '1';
			$data['message']  	= 'Event Data Get Successfully';
			$data['data']       = $getevent;
		}
		else
		{
			$data['Status']     = '0';
			$data['message']  	= 'Data Not Found';
			$data['data']     	= array();
		}
 		echo json_encode($data);
    }
    
    // ======================================================== Get Current date =======================================================
    public function getcurrentdate(){

        $currentdat = Carbon::now();
        $currentdate = Carbon::parse($currentdat)->format('Y-m-d');
        return json_encode($currentdate);
        
    }
}
