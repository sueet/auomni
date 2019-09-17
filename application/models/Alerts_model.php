<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}

    
class Alerts_model extends CI_Model
{
    /**
     * Developer Name : Harmeet Singh
     *
     * Comapny Name : HigrooveSystems 
     *
     * Create Date : 13-09-2016
     */
    
    private $userid;
    private $cocode;
    function __construct()
    {
        parent::__construct();        
        $this->load->library('session');
        $this->userid=$this->session->userdata('USERID');
    } 
    
    
    public function getLaycanStartDate()
    {
        if($this->input->post()) {
            $AuctionId=$this->input->post('AuctionId');
        }
        if($this->input->get()) {
            $AuctionId=$this->input->get('AuctionId');
        }
        
        $this->db->select('CONVERT(datetime, LpLaycanStartDate) as sdate, CONVERT(datetime, LpLaycanEndDate) as edate');
        $this->db->from('udt_AU_Cargo WITH (NOLOCK)');
        $this->db->where('AuctionID', $AuctionId);
        $q=$this->db->get();
        return $q->row();
    }
    
    public function getDisportStartDate()
    {
        if($this->input->post()) {
            $AuctionId=$this->input->post('AuctionId');
        }
        if($this->input->get()) {
            $AuctionId=$this->input->get('AuctionId');
        }
        /*
        $this->db->select('CONVERT(datetime, DpArrivalStartDate) as sdate, CONVERT(datetime, DpArrivalEndDate) as edate');
        $this->db->from('udt_AU_Cargo WITH (NOLOCK)');
        $this->db->where('AuctionID',$AuctionId);
        $q=$this->db->get();
        return $q->row();
        */
        $this->db->select('CONVERT(datetime, DpArrivalStartDate) as sdate, CONVERT(datetime, DpArrivalEndDate) as edate');
        $this->db->from('udt_AU_CargoDisports WITH (NOLOCK)');
        $this->db->where('AuctionID', $AuctionId);
        $this->db->order_by('CargoID', 'asc');
        $q=$this->db->get();
        return $q->row();
    }
    
    public function saveAlertsData()
    {
        extract($this->input->post());
        
        if($cargointernalcomments != 'undefined') {
            $internalcomment=$cargointernalcomments;
        }else{
            $internalcomment='';
        }
        if($cargodisplaycomments != 'undefined') {
            $displaycomment=$cargodisplaycomments;
        }else{
            $displaycomment='';
        }
        
        $data1=array('CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionId,
        'LayCanStartDate'=>date('Y-m-d H:i:s', strtotime($floadportlaycanstartdate)),
        'CommenceAlertFlag'=>$cdatealerts,
        'AuctionCommences'=>$acommenceson,
        'OnlyDisplay'=>1,
        'CommenceDaysBefore'=>$daysbeforeauction,
        'CommenceDate'=>$auctioncommencementdate,
        'AuctionCommenceDefinedDate'=>$acommencedefineddate,
        'AuctionValidity'=>$auctionvaliditydays,
        'AuctionCeases'=>$auctionceasesdate,
        'AlertBeforeCommence'=>$alertschedulebeforecommencement,
        'AlertBeforeClosing'=>$alertschedulebeforeclosing,
        'AlertNotificationCommence'=>$notificationcommencement,
        'AlertNotificationClosing'=>$closingnotification,
        'IncludeClosing'=>$closingdatestoinclude, 
        'AuctionerComments'=>$internalcomment, 
        'InviteesComments'=>$displaycomment,
        'auctionvalidityhour'=>$auctionvalidityhour, 
        'AuctionValidMinutes'=>$auctionvalidityminutes,
        //'auctionceaseshour'=>$auctionceaseshour,
        'QuoteCeasesExtendTime'=>$QuoteCeasesExtendTime,                 
        'ExtendTime1'=>0,                 
        'ExtendTime2'=>0,                 
        'ExtendTime3'=>0,             
        'RowStatus'=>'1',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AUM_Alerts_H', $data1);
        
        $data=array('CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionId,
        'LayCanStartDate'=>date('Y-m-d H:i:s', strtotime($floadportlaycanstartdate)),
        'CommenceAlertFlag'=>$cdatealerts,
        'AuctionCommences'=>$acommenceson,
        'OnlyDisplay'=>1,
        'CommenceDaysBefore'=>$daysbeforeauction,
        'CommenceDate'=>$auctioncommencementdate,
        'AuctionCommenceDefinedDate'=>$acommencedefineddate,
        'AuctionValidity'=>$auctionvaliditydays,
        'AuctionCeases'=>$auctionceasesdate,
        'AlertBeforeCommence'=>$alertschedulebeforecommencement,
        'AlertBeforeClosing'=>$alertschedulebeforeclosing,
        'AlertNotificationCommence'=>$notificationcommencement,
        'AlertNotificationClosing'=>$closingnotification,
        'IncludeClosing'=>$closingdatestoinclude, 
        'AuctionerComments'=>$internalcomment, 
        'InviteesComments'=>$displaycomment, 
        'auctionvalidityhour'=>$auctionvalidityhour, 
        //'auctionceaseshour'=>$auctionceaseshour, 
        'AuctionValidMinutes'=>$auctionvalidityminutes,
        'QuoteCeasesExtendTime'=>$QuoteCeasesExtendTime,                 
        'ExtendTime1'=>0,                 
        'ExtendTime2'=>0,                 
        'ExtendTime3'=>0,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AUM_Alerts', $data);
        
    }
    
    public function getAlertsData()
    {
        
        $query=$this->db->get('udt_AUM_Alerts');
        return $query->result();
    }
    
    public function getAlertsDataByAuction()
    {
        
        $AuctionId=$this->input->get('AuctionId');
        $this->db->where('AuctionID', $AuctionId);
        $q=$this->db->get('udt_AUM_Alerts');
        return $q->row();
    }
    
    
    public function updateAlertsData()
    {
        
        extract($this->input->post());
        $data_auction=array(
        'auctionStatus'=>'P',
        'auctionExtendedStatus'=>'',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
                    );
        $this->db->where('AuctionID', $AuctionId);
        $this->db->update('udt_AU_Auctions', $data_auction);
        
        $data_h=array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionId,
        'LayCanStartDate'=>date('Y-m-d H:i:s', strtotime($floadportlaycanstartdate)),
        'CommenceAlertFlag'=>$cdatealerts,
        'AuctionCommences'=>$acommenceson,
        'OnlyDisplay'=>1,
        'CommenceDaysBefore'=>$daysbeforeauction,
        'CommenceDate'=>$auctioncommencementdate,
        'AuctionCommenceDefinedDate'=>$acommencedefineddate,
        'AuctionValidity'=>$auctionvaliditydays,
        'AuctionCeases'=>$auctionceasesdate,
        'AlertBeforeCommence'=>$alertschedulebeforecommencement,
        'AlertBeforeClosing'=>$alertschedulebeforeclosing,
        'AlertNotificationCommence'=>$notificationcommencement,
        'AlertNotificationClosing'=>$closingnotification,
        'IncludeClosing'=>$closingdatestoinclude, 
        'AuctionerComments'=>$cargointernalcomments, 
        'InviteesComments'=>$cargodisplaycomments, 
        'auctionvalidityhour'=>$auctionvalidityhour,  
        //'auctionceaseshour'=>$auctionceaseshour, 
        'AuctionValidMinutes'=>$auctionvalidityminutes, 
        'QuoteCeasesExtendTime'=>$QuoteCeasesExtendTime,                 
        'ExtendTime1'=>0,                 
        'ExtendTime2'=>0,                 
        'ExtendTime3'=>0, 
        'RowStatus'=>'2',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        
        $this->db->insert('udt_AUM_Alerts_H', $data_h);
        
        
        $data=array(
        'LayCanStartDate'=>date('Y-m-d H:i:s', strtotime($floadportlaycanstartdate)),
        'CommenceAlertFlag'=>$cdatealerts,
        'AuctionCommences'=>$acommenceson,
        'OnlyDisplay'=>1,
        'CommenceDaysBefore'=>$daysbeforeauction,
        'CommenceDate'=>$auctioncommencementdate,
        'AuctionCommenceDefinedDate'=>$acommencedefineddate,
        'AuctionValidity'=>$auctionvaliditydays,
        'AuctionCeases'=>$auctionceasesdate,
        'AlertBeforeCommence'=>$alertschedulebeforecommencement,
        'AlertBeforeClosing'=>$alertschedulebeforeclosing,
        'AlertNotificationCommence'=>$notificationcommencement,
        'AlertNotificationClosing'=>$closingnotification,
        'IncludeClosing'=>$closingdatestoinclude, 
        'AuctionerComments'=>$cargointernalcomments, 
        'InviteesComments'=>$cargodisplaycomments, 
        'auctionvalidityhour'=>$auctionvalidityhour,  
        //'auctionceaseshour'=>$auctionceaseshour,                 
        'AuctionValidMinutes'=>$auctionvalidityminutes,                 
        'QuoteCeasesExtendTime'=>$QuoteCeasesExtendTime,                 
        'ExtendTime1'=>0,                 
        'ExtendTime2'=>0,                 
        'ExtendTime3'=>0,                 
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        
        $this->db->where('AuctionID', $AuctionId);
        $this->db->update('udt_AUM_Alerts', $data);
        
        $data1=array(
        'DocumentType'=>$typeofdocument,
        'Title'=>$NameorTitleofdocumentattached,
        'ToDisplay'=>$Documenttobedisplayinauctionprocess,
        'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee
        );
        $this->db->where('AuctionID', $AuctionId);
        $this->db->where('AuctionSection', $type);
        $this->db->update('udt_AUM_Documents', $data1); 
        
    }
    
    public function deleteAlertsData()
    {
        
        $AuctionId=$this->input->get('AuctionId');
        $AuctionId=explode(',', $AuctionId);
        $this->db->where_in('AuctionID', $AuctionId);
        $this->db->delete('udt_AUM_Alerts');
    }
    
    public function getAlert()
    {
        $AuctionId=$this->input->post('AuctionId');
        $this->db->select('*');
        $this->db->from('udt_AUM_Alerts');
        $this->db->where('AuctionID', $AuctionId);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function saveAlert($oldData,$newData)
    {
        $AuctionId=$this->input->post('AuctionId');
        $UserId=$this->input->post('UserID');
        $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
        $query=$this->db->get();
        $msgData=$query->row();
        
        $section='';
        $message='';
        
        if($oldData->AuctionCommences != $newData->AuctionCommences) {
            $section='Alert';
            $OldAuctionCommences='';
            if($oldData->AuctionCommences==1) {
                $OldAuctionCommences='Days before laycan start date';
            } else if($oldData->AuctionCommences==2) {
                $OldAuctionCommences='Defined date';
            }
            $NewAuctionCommences='';
            if($newData->AuctionCommences==1) {
                $NewAuctionCommences='Days before laycan start date';
            } else if($newData->AuctionCommences==2) {
                $NewAuctionCommences='Defined date';
            }
            $message .='<br>Old Quote commences on : '.$OldAuctionCommences.' New Quote commences on : '.$NewAuctionCommences;
        }
        $OldLayCanStartDate=date('d-m-Y H:i:s', strtotime($oldData->LayCanStartDate));
        $NewLayCanStartDate=date('d-m-Y H:i:s', strtotime($newData->LayCanStartDate));
        
        if($OldLayCanStartDate != $NewLayCanStartDate) {
            $section='Alert';
            $message .='<br>Old First loadport laycan start date : '.$OldLayCanStartDate.' New First loadport laycan start date : '.$NewLayCanStartDate;
        }
        
        if($oldData->CommenceDaysBefore != $newData->CommenceDaysBefore) {
            $section='Alert';
            $message .='<br>Old Days before commencement : '.$oldData->CommenceDaysBefore.' New Days before commencement : '.$newData->CommenceDaysBefore;
        }
        
        if($oldData->CommenceDate != $newData->CommenceDate) {
            $section='Alert';
            $message .='<br>Old Quote commencement date : '.$oldData->CommenceDate.' New Quote commencement date : '.$newData->CommenceDate;
        }
        
        if($oldData->AuctionValidity != $newData->AuctionValidity) {
            $section='Alert';
            $message .='<br>Old Quote validity (days) : '.$oldData->AuctionValidity.' New Quote validity (days) : '.$newData->AuctionValidity;
        }
        
        if($oldData->auctionvalidityhour != $newData->auctionvalidityhour) {
            $section='Alert';
            $message .='<br>Old Quote validity (hours) : '.$oldData->auctionvalidityhour.' New Quote validity (hours) : '.$newData->auctionvalidityhour;
        }
        
        if($oldData->AuctionValidMinutes != $newData->AuctionValidMinutes) {
            $section='Alert';
            $message .='<br>Old Quote validity (minutes) : '.$oldData->AuctionValidMinutes.' New Quote validity (minutes) : '.$newData->AuctionValidMinutes;
        }
        
        if($oldData->AuctionCeases != $newData->AuctionCeases) {
            $section='Alert';
            $message .='<br>Old Quote ceases on : '.$oldData->AuctionCeases.' New Quote ceases on : '.$newData->AuctionCeases;
        }
        
        if($oldData->QuoteCeasesExtendTime != $newData->QuoteCeasesExtendTime) {
            $section='Alert';
            $message .='<br>Old Quote ceases extends time : '.$oldData->QuoteCeasesExtendTime.' New Quote ceases extends time : '.$newData->QuoteCeasesExtendTime;
        }
        
        if($oldData->AuctionerComments != $newData->AuctionerComments) {
            $section='Alert';
            $message .='<br>Old cargo owner Comment : '.$oldData->AuctionerComments.' New cargo owner Comment : '.$newData->AuctionerComments;
        }
        
        if($oldData->InviteesComments != $newData->InviteesComments) {
            $section='Alert';
            $message .='<br>Old Invitee Comment : '.$oldData->InviteesComments.' New Invitee Comment : '.$newData->InviteesComments;
        }
        
        if($section=='Alert') {
            if($msgData) {
                $alertData=array( 
                'CoCode'=>'MARX',
                'AuctionID'=>$AuctionId,
                'Event'=>'Edit & Update',
                'Page'=>'Cargo Set Up',
                'Section'=>'Alert',
                'subSection'=>$section,
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$msgData->MessageID,
                'UserID'=>$UserId,
                'FromUserID'=>$UserId,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                //print_R($vesseldata); die;         
                $this->db->insert('udt_AU_Messsage_Details', $alertData);    

                $msg_data=array(
                'MessageFlag'=>'1',
                'MsgDate'=>date('Y-m-d H:i:s')
                                );
                                
                $this->db->where('AuctionID', $AuctionId);
                $this->db->update('udt_AU_Auctions', $msg_data);
            }
            
        }  
    } 
    
}


