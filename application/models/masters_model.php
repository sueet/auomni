<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class masters_model extends CI_Model {
function __construct()
{
    parent::__construct();        
    $this->load->library('session');
} 
    
public function getStaticTerms()
{
    $this->db->select('*');
    $this->db->from('udt_cp_staticterms');
    $this->db->where('type', 'Charter_References');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFreightRate()
{
    $this->db->select('*');
    $this->db->from('udt_CargoUnitMaster');
    $this->db->where('ActiveFlag', '1');
    $query=$this->db->get();
    return $query->result();
}
    
public function userLogin()
{
    extract($this->input->post());
    $this->db->select('udt_UserMaster.ID as UID,udt_UserMaster.FirstName,udt_UserMaster.LastName, udt_UserMaster.LoginID, udt_UserMaster.UserType, udt_UserMaster.SystemChatFlag, udt_UserMaster.GeneralChatFlag, udt_EntityMaster.ID as EID, udt_EntityMaster.EntityName,udt_EntityMaster.DisablePrompt');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->where('udt_UserMaster.LoginID', $userID);
    $this->db->where('udt_UserMaster.Password', $passowrd);
    $query=$this->db->get();
    $result= $query->row();
        
    if($chk==0) {
        $data=array('TermCheckFlag'=>$AFlag);
        $this->db->where('LoginID', $userID);
        $this->db->where('Password', $passowrd);
        $this->db->update('udt_UserMaster', $data);
            
        $this->db->select('*');
        $this->db->from('AUM_TermCondition');
        $this->db->where('EntityID', $result->EID);
        $this->db->where('Status', 3);
        $query1=$this->db->get();
        $rslt1= $query1->row();
            
        $tcrdata=array(
        'UserID'=>$result->UID,
        'TCID'=>$rslt1->TCID,
        'ReadDate'=>date('Y-m-d H:i:s'),
        'EntityID'=>$result->EID
        );
        $this->db->insert('AUM_TermConditionReadDetail', $tcrdata);
    }
        
    $data1=array('link'=>1);
    $this->db->where('EntityID', $result->EID);
    $this->db->where('Status', 3);
    $this->db->where('Application', 1);
    $this->db->update('AUM_TermCondition', $data1);
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->where('ForUserID', $result->UID);
    $query2=$this->db->get();
    $rslt2= $query2->result();
        
    foreach($rslt2 as $row) {
        $data1=array('link'=>1);
        $this->db->where('EntityID', $row->RecordOwner);
        $this->db->where('Status', 3);
        $this->db->where('Application', 1);
        $this->db->update('AUM_TermCondition', $data1);
    }
    return $result;
}
    
public function verifyLogin()
{
    extract($this->input->post());
    $this->db->select('ID,EntityID,FirstName,LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $query=$this->db->get();
    return $query->row(); 
}
    
public function allVesselData()
{ 
    $key=$this->input->post('key');
    $entity=$this->input->post('entity');
    $this->db->select('ID,VesselName,DWT');
    $this->db->from('udt_VesselMaster', 'after');
    $this->db->like('udt_VesselMaster', $key);
    if($entity) {
        $this->db->where('ID', $entity);
    }
    $query=$this->db->get();
    return $query->result();
        
}
    
public function modelSubmit()
{
    extract($this->input->post());
    $this->db->select('mid');
    $this->db->from('udt_AU_Model');
    $this->db->where('ModelNumber', $ModelNumber);
    $this->db->where('ModelFunction', $ModelFunction);
    $this->db->where('RecordOwner', $RecordOwner);
    $query=$this->db->get();
    $rslt=$query->result();
    if(count($rslt)>0) {
        return 0;
    } else {
        if($ModelFunction==1) {    
            $this->db->select('mid');
            $this->db->from('udt_AU_Model');
            $this->db->where('ModelFunction', 1);
            $this->db->where('RecordOwner', $RecordOwner);
            $query=$this->db->get();
            $rslt1=$query->result();
            if(count($rslt1)>0) {
                return 2;
            }
        }
        
        $data=array(
        'RecordOwner'=>$RecordOwner,
        'EntityTypeRole'=>$EntityTypeRole,
        'ModelNumber'=>$ModelNumber,
        'ModelFunction'=>$ModelFunction,
        'MaxPoint'=>$MaxPoint,
        'ModelStatus'=>$ModelStatus,
        'FreightCriteriaStatus'=>$FreightCriteriaStatus,
        'FMaxPoint'=>$FMaxPoint,
        'FpercentageRange1'=>$FpercentageRange1,
        'FpercentageRange2'=>$FpercentageRange2,
        'FpercentageRange3'=>$FpercentageRange3,
        'FpercentageRange4'=>$FpercentageRange4,
        'FpercentageRange5'=>$FpercentageRange5,
        'FpercentageValue1'=>$FpercentageValue1,
        'FpercentageValue2'=>$FpercentageValue2,
        'FpercentageValue3'=>$FpercentageValue3,
        'FpercentageValue4'=>$FpercentageValue4,
        'FpercentageValue5'=>$FpercentageValue5,
        'FIDDCriteriaStatus'=>$FIDDCriteriaStatus,
        'FIDDMaxPoint'=>$FIDDMaxPoint,
        'FIDDpercentageRange1'=>$FIDDpercentageRange1,
        'FIDDpercentageRange2'=>$FIDDpercentageRange2,
        'FIDDpercentageRange3'=>$FIDDpercentageRange3,
        'FIDDpercentageRange4'=>$FIDDpercentageRange4,
        'FIDDpercentageRange5'=>$FIDDpercentageRange5,
        'FIDDpercentageValue1'=>$FIDDpercentageValue1,
        'FIDDpercentageValue2'=>$FIDDpercentageValue2,
        'FIDDpercentageValue3'=>$FIDDpercentageValue3,
        'FIDDpercentageValue4'=>$FIDDpercentageValue4,
        'FIDDpercentageValue5'=>$FIDDpercentageValue5,
        'InviteeCriteriaStatus'=>$InviteeCriteriaStatus,
        'IMaxPointForCriteria'=>$IMaxPointForCriteria,
        'InviteePriorityValue1'=>$InviteePriorityValue1,
        'InviteePriorityValue2'=>$InviteePriorityValue2,
        'InviteePriorityValue3'=>$InviteePriorityValue3,
        'InviteePriorityValue4'=>$InviteePriorityValue4,
        'PSCDLYCS'=>$PSCDLYCS,
        'PSCDLYMP'=>$PSCDLYMP,
        'PSCDLYCFMP'=>$PSCDLYCFMP,
        'PSCDLYAPC'=>$PSCDLYAPC,
        'PSCDPCS'=>$PSCDPCS,
        'PSCDPMPC'=>$PSCDPMPC,
        'PSCDPCMP'=>$PSCDPCMP,
        'PSCDPAPC'=>$PSCDPAPC,
        'PSCDRPFLPACS'=>$PSCDRPFLPACS,
        'PSCDRPFLPAMPC'=>$PSCDRPFLPAMPC,
        'PSCDRPFLPACMP'=>$PSCDRPFLPACMP,
        'PSCDRPFLPAAPC'=>$PSCDRPFLPAAPC,
        'PFLPPADCS'=>$PFLPPADCS,
        'PFLPPADMPC'=>$PFLPPADMPC,
        'PFLPPADMPC1'=>$PFLPPADMPC1,
        'PFLPPADMPC2'=>$PFLPPADMPC2,
        'PFLPPADMPC3'=>$PFLPPADMPC3,
        'PFLPPADMPC4'=>$PFLPPADMPC4,
        'PFLPPADMPC5'=>$PFLPPADMPC5,
        'PFLPPADMPV1'=>$PFLPPADMPV1,
        'PFLPPADMPV2'=>$PFLPPADMPV2,
        'PFLPPADMPV3'=>$PFLPPADMPV3,
        'PFLPPADMPV4'=>$PFLPPADMPV4,
        'PFLPPADMPV5'=>$PFLPPADMPV5,
        'IPOLYCS'=>$IPOLYCS,
        'IPOLYMPC'=>$IPOLYMPC,
        'IPOLYCMP'=>$IPOLYCMP,
        'IPOLYAPC'=>$IPOLYAPC,
        'VLTCS'=>$VLTCS,
        'VLTMPC'=>$VLTMPC,
        'VLTCMP'=>$VLTCMP,
        'VLTAPC'=>$VLTAPC,
        'RatingStatus'=>$RatingStatus,
        'RatingMaxPoints'=>$RatingMaxPoints,
        'PrcentRangeValue1'=>$PrcentRangeValue1,
        'PrcentRangeValue2'=>$PrcentRangeValue2,
        'PrcentRangeValue3'=>$PrcentRangeValue3,
        'PrcentRangeValue4'=>$PrcentRangeValue4,
        'PrcentRangeValue5'=>$PrcentRangeValue5,
        'DemurrageCriteriaStatus'=>$DemurrageCriteriaStatus,
        'DMaxPoint'=>$DMaxPoint,
        'DpercentageRange1'=>$DpercentageRange1,
        'DpercentageRange2'=>$DpercentageRange2,
        'DpercentageRange3'=>$DpercentageRange3,
        'DpercentageRange4'=>$DpercentageRange4,
        'DpercentageRange5'=>$DpercentageRange5,
        'DpercentageValue1'=>$DpercentageValue1,
        'DpercentageValue2'=>$DpercentageValue2,
        'DpercentageValue3'=>$DpercentageValue3,
        'DpercentageValue4'=>$DpercentageValue4,
        'DpercentageValue5'=>$DpercentageValue5,
        'CreatedBy'=>$UserID,
        'add_date'=>date('Y-m-d H:i:s')
                );
        return $this->db->insert('udt_AU_Model', $data);            
    }            
}
    
public function getModel()
{
    $OwnerID=$this->input->get('OwnerID');
    $EntityTypeRole=$this->input->get('EntityTypeRole');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
    $this->db->select('udt_AU_Model.*,udt_EntityType.Description,udt_EntityMaster.EntityName,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_Model');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Model.EntityTypeRole', 'left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Model.RecordOwner', 'left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Model.CreatedBy', 'left');
    if($OwnerID) {
        $this->db->where('udt_AU_Model.RecordOwner', $OwnerID);
    }
    if($EntityTypeRole) {
        $this->db->where('udt_AU_Model.EntityTypeRole', $EntityTypeRole);
    }
    if($dateFrom) {
        $this->db->where('udt_AU_Model.add_date >=', date('Y-m-d', strtotime($dateFrom)));
    }
    if($dateTo) {
        $this->db->where('udt_AU_Model.add_date <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }
        
    $this->db->order_by('udt_AU_Model.add_date', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getModelMyId()
{
    $mid=$this->input->post('mid');
    $this->db->select('udt_AU_Model.*,udt_EntityType.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Model');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Model.EntityTypeRole');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Model.RecordOwner');
    $this->db->where('mid', $mid);
    $query=$this->db->get();
    return $query->row();
}
    
public function modelUpdate()
{
    extract($this->input->post());
    $where="mid != ".$mid;
    $this->db->select('mid');
    $this->db->from('udt_AU_Model');
    $this->db->where('ModelNumber', $ModelNumber);
    $this->db->where('ModelFunction', $ModelFunction);
    $this->db->where('RecordOwner', $RecordOwner);
    $this->db->where($where);
    $query=$this->db->get();
    $rslt=$query->result();
    if(count($rslt)>0) {
        return 0;
    } else {
        if($ModelFunction==1) {    
            $where="mid != ".$mid;
            $this->db->select('mid');
            $this->db->from('udt_AU_Model');
            $this->db->where('ModelFunction', 1);
            $this->db->where('RecordOwner', $RecordOwner);
            $this->db->where($where);$query=$this->db->get();
            $rslt1=$query->result();
            if(count($rslt1)>0) {
                return 2;
            }
        }
            
        $data=array(
        'RecordOwner'=>$RecordOwner,
        'EntityTypeRole'=>$EntityTypeRole,
        'ModelNumber'=>$ModelNumber,
        'ModelFunction'=>$ModelFunction,
        'MaxPoint'=>$MaxPoint,
        'ModelStatus'=>$ModelStatus,
        'FreightCriteriaStatus'=>$FreightCriteriaStatus,
        'FMaxPoint'=>$FMaxPoint,
        'FpercentageRange1'=>$FpercentageRange1,
        'FpercentageRange2'=>$FpercentageRange2,
        'FpercentageRange3'=>$FpercentageRange3,
        'FpercentageRange4'=>$FpercentageRange4,
        'FpercentageRange5'=>$FpercentageRange5,
        'FpercentageValue1'=>$FpercentageValue1,
        'FpercentageValue2'=>$FpercentageValue2,
        'FpercentageValue3'=>$FpercentageValue3,
        'FpercentageValue4'=>$FpercentageValue4,
        'FpercentageValue5'=>$FpercentageValue5,
        'FIDDCriteriaStatus'=>$FIDDCriteriaStatus,
        'FIDDMaxPoint'=>$FIDDMaxPoint,
        'FIDDpercentageRange1'=>$FIDDpercentageRange1,
        'FIDDpercentageRange2'=>$FIDDpercentageRange2,
        'FIDDpercentageRange3'=>$FIDDpercentageRange3,
        'FIDDpercentageRange4'=>$FIDDpercentageRange4,
        'FIDDpercentageRange5'=>$FIDDpercentageRange5,
        'FIDDpercentageValue1'=>$FIDDpercentageValue1,
        'FIDDpercentageValue2'=>$FIDDpercentageValue2,
        'FIDDpercentageValue3'=>$FIDDpercentageValue3,
        'FIDDpercentageValue4'=>$FIDDpercentageValue4,
        'FIDDpercentageValue5'=>$FIDDpercentageValue5,
        'InviteeCriteriaStatus'=>$InviteeCriteriaStatus,
        'IMaxPointForCriteria'=>$IMaxPointForCriteria,
        'InviteePriorityValue1'=>$InviteePriorityValue1,
        'InviteePriorityValue2'=>$InviteePriorityValue2,
        'InviteePriorityValue3'=>$InviteePriorityValue3,
        'InviteePriorityValue4'=>$InviteePriorityValue4,
        'PSCDLYCS'=>$PSCDLYCS,
        'PSCDLYMP'=>$PSCDLYMP,
        'PSCDLYCFMP'=>$PSCDLYCFMP,
        'PSCDLYAPC'=>$PSCDLYAPC,
        'PSCDPCS'=>$PSCDPCS,
        'PSCDPMPC'=>$PSCDPMPC,
        'PSCDPCMP'=>$PSCDPCMP,
        'PSCDPAPC'=>$PSCDPAPC,
        'PSCDRPFLPACS'=>$PSCDRPFLPACS,
        'PSCDRPFLPAMPC'=>$PSCDRPFLPAMPC,
        'PSCDRPFLPACMP'=>$PSCDRPFLPACMP,
        'PSCDRPFLPAAPC'=>$PSCDRPFLPAAPC,
        'PFLPPADCS'=>$PFLPPADCS,
        'PFLPPADMPC'=>$PFLPPADMPC,
        'PFLPPADMPC1'=>$PFLPPADMPC1,
        'PFLPPADMPC2'=>$PFLPPADMPC2,
        'PFLPPADMPC3'=>$PFLPPADMPC3,
        'PFLPPADMPC4'=>$PFLPPADMPC4,
        'PFLPPADMPC5'=>$PFLPPADMPC5,
        'PFLPPADMPV1'=>$PFLPPADMPV1,
        'PFLPPADMPV2'=>$PFLPPADMPV2,
        'PFLPPADMPV3'=>$PFLPPADMPV3,
        'PFLPPADMPV4'=>$PFLPPADMPV4,
        'PFLPPADMPV5'=>$PFLPPADMPV5,
        'IPOLYCS'=>$IPOLYCS,
        'IPOLYMPC'=>$IPOLYMPC,
        'IPOLYCMP'=>$IPOLYCMP,
        'IPOLYAPC'=>$IPOLYAPC,
        'VLTCS'=>$VLTCS,
        'VLTMPC'=>$VLTMPC,
        'VLTCMP'=>$VLTCMP,
        'VLTAPC'=>$VLTAPC,
        'RatingStatus'=>$RatingStatus,
        'RatingMaxPoints'=>$RatingMaxPoints,
        'PrcentRangeValue1'=>$PrcentRangeValue1,
        'PrcentRangeValue2'=>$PrcentRangeValue2,
        'PrcentRangeValue3'=>$PrcentRangeValue3,
        'PrcentRangeValue4'=>$PrcentRangeValue4,
        'PrcentRangeValue5'=>$PrcentRangeValue5,
        'DemurrageCriteriaStatus'=>$DemurrageCriteriaStatus,
        'DMaxPoint'=>$DMaxPoint,
        'DpercentageRange1'=>$DpercentageRange1,
        'DpercentageRange2'=>$DpercentageRange2,
        'DpercentageRange3'=>$DpercentageRange3,
        'DpercentageRange4'=>$DpercentageRange4,
        'DpercentageRange5'=>$DpercentageRange5,
        'DpercentageValue1'=>$DpercentageValue1,
        'DpercentageValue2'=>$DpercentageValue2,
        'DpercentageValue3'=>$DpercentageValue3,
        'DpercentageValue4'=>$DpercentageValue4,
        'DpercentageValue5'=>$DpercentageValue5,
        'add_date'=>date('Y-m-d H:i:s')
        );
                    
        $this->db->where('mid', $mid);            
        $ret=$this->db->update('udt_AU_Model', $data);    
        return $ret;
    }            
}
public function deleteModelById()
{
    $mid=$this->input->post('mid');
    $this->db->where('mid', $mid);
    return $this->db->delete('udt_AU_Model');
}
    
public function checkResponseClose($entityID)
{
    $this->db->select('udt_AUM_Alerts.AuctionCeases,udt_AUM_Alerts.AuctionID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Alerts');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Alerts.AuctionID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
    $this->db->where('udt_AU_Auctions.OwnerEntityID', $entityID);
    $query=$this->db->get();
    $result=$query->result();
    foreach($result as $row){
        $AuctionCeasesDate=$row->AuctionCeases;
        $date1=date_create(date('Y-m-d H:i:s', strtotime($AuctionCeasesDate)));
        $date2=date_create(date('Y-m-d H:i:s'));
        $diff=date_diff($date2, $date1);
        $day_diff=$diff->format("%R%a");
        if($day_diff < 0) {
            $this->db->where('AuctionID', $row->AuctionID);
            $this->db->update('udt_AUM_Freight', array('ResponseStatus'=>'Closed'));
                
            $this->db->select('*');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $entityID);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '10');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
            $query1=$this->db->get();
            $msgRecord=$query1->Result();
            $msgDetails='<br>Cargo Set Up (Quotes) (closed) on : '.date('d-m-Y', strtotime($row->AuctionCeases));
            foreach($msgRecord as $mr){
                $this->db->select('*');
                $this->db->from('udt_AU_Messsage_Details');
                $this->db->where('udt_AU_Messsage_Details.AuctionID', $row->AuctionID);
                $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $mr->MessageID);
                $this->db->where('udt_AU_Messsage_Details.UserID', $mr->ForUserID);
                $querycheck=$this->db->get();
                $msgcheck=$querycheck->num_rows();
                    
                if($msgcheck <= 0) {
                    $msg=array(
                     'CoCode'=>C_COCODE,    
                     'AuctionID'=>$row->AuctionID,    
                     'Event'=>'Cargo Set Up (Quotes) (closed)',    
                     'Page'=>'Cargo Set Up (Quotes)',    
                     'Section'=>'',    
                     'subSection'=>'',    
                     'StatusFlag'=>'1',    
                     'MessageDetail'=>$msgDetails,    
                     'MessageMasterID'=>$mr->MessageID,    
                     'UserID'=>$mr->ForUserID,    
                     'FromUserID'=>$mr->ForUserID,    
                     'UserDate'=>date('Y-m-d H:i:s')    
                    );
                    //$this->db->insert('udt_AU_Messsage_Details',$msg);
                    //$this->db->where('AuctionID',$row->AuctionID);
                    //$this->db->update('udt_AU_Auctions',array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                }    
                    
            }
                        
                
            $this->db->select('udt_AUM_Freight.*');
            $this->db->from('udt_AUM_Freight');
            $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID');
            $this->db->where('udt_AU_Auctions.OwnerEntityID', $entityID);
            $this->db->where('udt_AU_Auctions.AuctionID', $row->AuctionID);
            $query2=$this->db->get();
            $inviteeRecord=$query2->result();
            //print_r($inviteeRecord); die;
            foreach($inviteeRecord as $invitee){
                $this->db->select('*');
                $this->db->from('udt_AUM_MESSAGE_MASTER');
                $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $invitee->EntityID);
                $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '10');
                $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
                $invquery=$this->db->get();
                $invMsg=$invquery->row();
                //print_r($invMsg); die;
                $msgDetails1='<br>Cargo Set Up (Quotes) (closed) on : '.date('d-m-Y', strtotime($row->AuctionCeases));
                if($invMsg) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_Messsage_Details');
                    $this->db->where('udt_AU_Messsage_Details.AuctionID', $row->AuctionID);
                    $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $invMsg->MessageID);
                    $this->db->where('udt_AU_Messsage_Details.UserID', $invMsg->ForUserID);
                    $querycheck1=$this->db->get();
                    $msgcheck1=$querycheck1->num_rows();
                        
                    if($msgcheck1 <= 0) {
                        $msg1=array(
                        'CoCode'=>C_COCODE,    
                        'AuctionID'=>$row->AuctionID,    
                        'Event'=>'Cargo Set Up (Quotes) (closed)',    
                        'Page'=>'Cargo Set Up (Quotes)',    
                        'Section'=>'',    
                        'subSection'=>'',    
                        'StatusFlag'=>'1',    
                        'MessageDetail'=>$msgDetails1,    
                        'MessageMasterID'=>$invMsg->MessageID,    
                        'UserID'=>$invMsg->ForUserID,    
                        'FromUserID'=>$invMsg->ForUserID,        
                        'UserDate'=>date('Y-m-d H:i:s')    
                        );
                        //$this->db->insert('udt_AU_Messsage_Details',$msg1);
                        //$this->db->where('AuctionID',$row->AuctionID);
                        //$this->db->update('udt_AU_Auctions',array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                    }
                }
                                        
                
            }
                
        }
    }
        
}
    
public function importCSV($data)
{
    return $this->db->insert('udt_AU_CSVData', $data);
}
    
public function getCsvImportData()
{
    $Owner=$this->input->get('OwnerID');
    $this->db->select('udt_AU_CSVData.*,udt_PortMaster.PortName');
    $this->db->from('udt_AU_CSVData');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CSVData.LoadPort');
    if($Owner) {
        $this->db->where('udt_AU_CSVData.OwnerEntityID', $Owner);
    }
    $this->db->order_by('udt_AU_CSVData.CSV_ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function sendAuctionSetup()
{
    $csvid=trim($this->input->post('csvid'), '_');
    $UserID=$this->input->post('UserID');
    $ids=explode('_', $csvid);
        
    $this->db->select('*');
    $this->db->from('udt_AU_CSVData');
    $this->db->where_in('CSV_ID', $ids);
    $query=$this->db->get();
    $rslt=$query->result();
        
    $ExistingAuctionID= array();
        
    foreach($rslt as $row) {
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $row->AuctionID);
        $query=$this->db->get();
        $cnt=$query->num_rows();
        if($cnt>0) {
            $ExistingAuctionID[]=$row->AuctionID;
            continue;
        }
            
        $auctions=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'ActiveFlag'=>$row->ActiveFlag_A,
        'OwnerEntityID'=>$row->OwnerEntityID,
        'AuctionersRole'=>$row->AuctionersRole,    
        'StatusFlag'=>$row->StatusFlag,    
        'SelectFrom'=>$row->SelectFrom_A,    
        'ContractType'=>$row->ContractType,    
        'COAReference'=>$row->COAReference,    
        'SalesAgreementReference'=>$row->SalesAgreementReference,    
        'ShipmentReferenceID'=>$row->ShipmentReferenceId,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s'),    
        'auctionStatus'=>'P',
        'auctionExtendedStatus'=>'',
        'AuctionStatusDate'=>date('Y-m-d H:i:s'),
        'MessageFlag'=>'0',
        'RecordStatus'=>'1',
        'CountryID'=>'0'
                );
            
        $d['au'][]=$this->db->insert('udt_AU_Auctions', $auctions);
            
        $this->db->query(
            "insert into cops_admin.udt_AU_Auctions_H 
			(CoCode, AuctionID, ActiveFlag, OwnerEntityID, AuctionersRole, StatusFlag, SelectFrom, ContractType, COAReference, SalesAgreementReference, ShipmentReferenceID, CharterComments, UserID, UserDate, auctionStatus, auctionExtendedStatus, AuctionStatusDate, AuctionReleaseDate, MessageFlag, MsgDate, RowStatus, RecordStatus, ModelFunction, ModelNumber, CountryID, SignDateFlg, UserSignDate) 
			select CoCode, AuctionID, ActiveFlag, OwnerEntityID, AuctionersRole, StatusFlag, SelectFrom, ContractType, COAReference, SalesAgreementReference, ShipmentReferenceID, CharterComments, UserID, UserDate, auctionStatus, auctionExtendedStatus, AuctionStatusDate, AuctionReleaseDate, MessageFlag, MsgDate, '1', RecordStatus, ModelFunction, ModelNumber, CountryID, SignDateFlg, UserSignDate 
			from cops_admin.udt_AU_Auctions where AuctionID='".$row->AuctionID."' and OwnerEntityID='".$row->OwnerEntityID."' "
        );
            
        $cargo=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'LineNum'=>$row->LineNum_C,    
        'ActiveFlag'=>$row->ActiveFlag_C,
        'SelectFrom'=>$row->SelectFrom_C,
        'CargoQtyMT'=>$row->CargoQtyMT,
        'CargoLoadedBasis'=>$row->CargoLoadedBasis,
        'CargoLimitBasis'=>$row->CargoLimitBasis,
        'ToleranceLimit'=>$row->ToleranceLimit,
        'UpperLimit'=>$row->UpperLimit,
        'LowerLimit'=>$row->LowerLimit,
        'MaxCargoMT'=>(int)$row->MaxCargoMT,
        'MinCargoMT'=>(int)$row->MinCargoMT,
        'LoadPort'=>$row->LoadPort,
        'LpLaycanStartDate'=>$row->LpLaycanStartDate,
        'LpLaycanEndDate'=>$row->LpLaycanEndDate,
        'LpPreferDate'=>$row->LpPreferDate,
        'LoadingTerms'=>$row->LoadingTerms,
        'LoadingRateMT'=>$row->LoadingRateMT,
        'LoadingRateUOM'=>$row->LoadingRateUOM,
        'LpMaxTime'=>(int)$row->LpMaxTime,
        'LpLaytimeType'=>$row->LpLaytimeType,
        'LpCalculationBasedOn'=>$row->LpCalculationBasedOn,
        'LpTurnTime'=>$row->LpTurnTime,
        'LpPriorUseTerms'=>$row->LpPriorUseTerms,
        'LpLaytimeBasedOn'=>$row->LpLaytimeBasedOn,
        'LpCharterType'=>$row->LpCharterType,
        'LpNorTendering'=>$row->LpNorTendering,
        'LpStevedoringTerms'=>$row->LpStevedoringTerms,
        'ExceptedPeriodFlg'=>'2',
        'NORTenderingPreConditionFlg'=>'2',
        'NORAcceptancePreConditionFlg'=>'2',
        'OfficeHoursFlg'=>'2',
        'LaytimeCommencementFlg'=>'2',
        'ExpectedLpDelayDay'=>0,
        'ExpectedLpDelayHour'=>0,
        'CargoInternalComments'=>$row->CargoInternalComments,
        'CargoDisplayComments'=>$row->CargoDisplayComments,
        'BACFlag'=>$row->BACFlag,
        'Freight_Estimate'=>$row->Freight_Estimate,
        'Estimate_By'=>$row->Estimate_By,
        'Estimate_mt'=>$row->Estimate_mt,
        'Estimate_from'=>$row->Estimate_from,
        'Estimate_to'=>$row->Estimate_to,
        'Freight_Index'=>$row->Freight_Index,
        'Estimate_Index_By'=>$row->Estimate_Index_By,
        'Estimate_Index_mt'=>$row->Estimate_Index_mt,
        'Estimate_Index_from'=>$row->Estimate_Index_from,
        'Estimate_Index_to'=>$row->Estimate_Index_to,
        'estimate_comment'=>$row->estimate_comment,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        ); 
            
        $d['ca'][]=$this->db->insert('udt_AU_Cargo', $cargo);
            
        $this->db->select('*');
        $this->db->from('udt_AU_Cargo');
        $this->db->where('AuctionID', $row->AuctionID);
        $this->db->order_by('CargoID', 'desc');
        $qry1=$this->db->get();
        $cargo_row=$qry1->row();
            
        $d_data=array(
        'CargoID'=>$cargo_row->CargoID,
        'AuctionID'=>$row->AuctionID,
        'DisPort'=>$row->DisPort,
        'DpArrivalStartDate'=>$row->DpArrivalStartDate,
        'DpArrivalEndDate'=>$row->DpArrivalEndDate,
        'DpPreferDate'=>$row->DpPreferDate,
        'DischargingTerms'=>$row->DischargingTerms,
        'DischargingRateMT'=>(int)$row->DischargingRateMT,
        'DischargingRateUOM'=>$row->DischargingRateUOM,
        'DpMaxTime'=>(int)$row->DpMaxTime,
        'DpLaytimeType'=>$row->DpLaytimeType,
        'DpCalculationBasedOn'=>$row->DpCalculationBasedOn,
        'DpTurnTime'=>$row->DpTurnTime,
        'DpPriorUseTerms'=>$row->DpPriorUseTerms,
        'DpLaytimeBasedOn'=>$row->DpLaytimeBasedOn,
        'DpCharterType'=>$row->DpCharterType,
        'DpNorTendering'=>$row->DpNorTendering,
        'DpStevedoringTerms'=>$row->DpStevedoringTerms,
        'ExpectedDpDelayDay'=>0,
        'ExpectedDpDelayHour'=>0,
        'DpExceptedPeriodFlg'=>'2',
        'DpNORTenderingPreConditionFlg'=>'2',
        'DpNORAcceptancePreConditionFlg'=>'2',
        'DpOfficeHoursFlg'=>'2',
        'DpLaytimeCommencementFlg'=>'2',
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $ret_dis=$this->db->insert('udt_AU_CargoDisports', $d_data);
            
        if($ret_dis) {
            $this->db->select('*');
            $this->db->from('udt_AU_CargoDisports');
            $this->db->where('CargoID', $cargo_row->CargoID);
            $this->db->order_by('CD_ID', 'desc');
            $qry=$this->db->get();
            $dis_row=$qry->row();
                
            $d_data_h=array(
            'CD_ID'=>$dis_row->CD_ID,
            'CargoID'=>$cargo_row->CargoID,
            'AuctionID'=>$row->AuctionID,
            'DisPort'=>$row->DisPort,
            'DpArrivalStartDate'=>$row->DpArrivalStartDate,
            'DpArrivalEndDate'=>$row->DpArrivalEndDate,
            'DpPreferDate'=>$row->DpPreferDate,
            'DischargingTerms'=>$row->DischargingTerms,
            'DischargingRateMT'=>(int)$row->DischargingRateMT,
            'DischargingRateUOM'=>$row->DischargingRateUOM,
            'DpMaxTime'=>(int)$row->DpMaxTime,
            'DpLaytimeType'=>$row->DpLaytimeType,
            'DpCalculationBasedOn'=>$row->DpCalculationBasedOn,
            'DpTurnTime'=>$row->DpTurnTime,
            'DpPriorUseTerms'=>$row->DpPriorUseTerms,
            'DpLaytimeBasedOn'=>$row->DpLaytimeBasedOn,
            'DpCharterType'=>$row->DpCharterType,
            'DpNorTendering'=>$row->DpNorTendering,
            'DpStevedoringTerms'=>$row->DpStevedoringTerms,
            'ExpectedDpDelayDay'=>0,
            'ExpectedDpDelayHour'=>0,
            'DpExceptedPeriodFlg'=>'2',
            'DpNORTenderingPreConditionFlg'=>'2',
            'DpNORAcceptancePreConditionFlg'=>'2',
            'DpOfficeHoursFlg'=>'2',
            'DpLaytimeCommencementFlg'=>'2',
            'RowStatus'=>1,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports_H', $d_data_h);
        }
            
        $cargo_h=array(
                'CoCode'=>$row->CoCode,    
                'AuctionID'=>$row->AuctionID,    
                'LineNum'=>$row->LineNum_C,    
                'ActiveFlag'=>$row->ActiveFlag_C,
                'SelectFrom'=>$row->SelectFrom_C,
                'CargoQtyMT'=>$row->CargoQtyMT,
                'CargoLoadedBasis'=>$row->CargoLoadedBasis,
                'CargoLimitBasis'=>$row->CargoLimitBasis,
                'ToleranceLimit'=>$row->ToleranceLimit,
                'UpperLimit'=>$row->UpperLimit,
                'LowerLimit'=>$row->LowerLimit,
                'MaxCargoMT'=>(int)$row->MaxCargoMT,
                'MinCargoMT'=>(int)$row->MinCargoMT,
                'LoadPort'=>$row->LoadPort,
                'LpLaycanStartDate'=>$row->LpLaycanStartDate,
                'LpLaycanEndDate'=>$row->LpLaycanEndDate,
                'LpPreferDate'=>$row->LpPreferDate,
                'LoadingTerms'=>$row->LoadingTerms,
                'LoadingRateMT'=>$row->LoadingRateMT,
                'LoadingRateUOM'=>$row->LoadingRateUOM,
                'LpMaxTime'=>(int)$row->LpMaxTime,
                'LpLaytimeType'=>$row->LpLaytimeType,
                'LpCalculationBasedOn'=>$row->LpCalculationBasedOn,
                'LpTurnTime'=>$row->LpTurnTime,
                'LpPriorUseTerms'=>$row->LpPriorUseTerms,
                'LpLaytimeBasedOn'=>$row->LpLaytimeBasedOn,
                'LpCharterType'=>$row->LpCharterType,
                'LpNorTendering'=>$row->LpNorTendering,
                'LpStevedoringTerms'=>$row->LpStevedoringTerms,
                'CargoInternalComments'=>$row->CargoInternalComments,
                'CargoDisplayComments'=>$row->CargoDisplayComments,
                'ExceptedPeriodFlg'=>'2',
                'NORTenderingPreConditionFlg'=>'2',
                'NORAcceptancePreConditionFlg'=>'2',
                'OfficeHoursFlg'=>'2',
                'LaytimeCommencementFlg'=>'2',
                'ExpectedLpDelayDay'=>0,
                'ExpectedLpDelayHour'=>0,
                'BACFlag'=>$row->BACFlag,
                'UserID'=>$UserID,
                'RowStatus'=>'1',
                'UserDate'=>date('Y-m-d H:i:s')
        ); 
                
        $this->db->insert('udt_AU_Cargo_H', $cargo_h);
                
                
        $estimate_h=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'LineNum'=>$row->LineNum_C,    
        'ActiveFlag'=>$row->ActiveFlag_C,
        'Freight_Estimate'=>$row->Freight_Estimate,
        'Estimate_By'=>$row->Estimate_By,
        'Estimate_mt'=>$row->Estimate_mt,
        'Estimate_from'=>$row->Estimate_from,
        'Estimate_to'=>$row->Estimate_to,
        'Freight_Index'=>$row->Freight_Index,
        'Estimate_Index_By'=>$row->Estimate_Index_By,
        'Estimate_Index_mt'=>$row->Estimate_Index_mt,
        'Estimate_Index_from'=>$row->Estimate_Index_from,
        'Estimate_Index_to'=>$row->Estimate_Index_to,
        'estimate_comment'=>$row->estimate_comment,
        'UserID'=>$UserID,
        'Estimate_RowStatus'=>'1',
        'Estimate_UserDate'=>date('Y-m-d H:i:s')
        ); 
                
        $this->db->insert('udt_AU_Cargo_H', $estimate_h);
                
        $differentials=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'LineNum'=>$row->LineNum_D,
        'SNum'=>1,
        'DifferentialVesselSizeGroup'=>$row->DifferentialVesselSizeGroup,
        'DifferentialLoadport'=>$row->DifferentialLoadport,
        'ReferencePort'=>$row->ReferencePort,
        'DifferentialDisport'=>$row->DifferentialDisport,
        'DifferentialAmount'=>$row->DifferentialAmount,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
            
        $d['di'][]=$this->db->insert('udt_AUM_Differentials', $differentials);    
            
        $diffData=array(
        'CoCode'=>$row->CoCode,
        'AuctionID'=>$row->AuctionID,    
        'LineNum'=>$row->LineNum_D,
        'VesselGroupSizeID'=>$row->DifferentialVesselSizeGroup,
        'BaseLoadPort'=>$row->DifferentialLoadport,
        'FreightReferenceFlg'=>1,
        'DisportRefPort1'=>$row->DisPort,
        'DisportRefPort2'=>0,
        'DisportRefPort3'=>0,
        'CargoOwnerComment'=>'',
        'InviteeComment'=>'',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
            
        $d['di'][]=$this->db->insert('udt_AU_Differentials', $diffData);
                
        $alerts=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'CommenceAlertFlag'=>$row->CommenceAlertFlag,
        'AuctionCommences'=>$row->AuctionCommences,
        'LayCanStartDate'=>$row->LpLaycanStartDate,
        'OnlyDisplay'=>$row->OnlyDisplay,
        'CommenceDaysBefore'=>$row->CommenceDaysBefore,
        'CommenceDate'=>$row->CommenceDate,
        'AuctionCommenceDefinedDate'=>$row->AuctionCommenceDefinedDate,
        'AuctionValidity'=>$row->AuctionValidity,
        'AuctionCeases'=>$row->AuctionCeases,
        'AlertBeforeCommence'=>$row->AlertBeforeCommence,
        'AlertBeforeClosing'=>$row->AlertBeforeClosing,
        'AlertNotificationCommence'=>$row->AlertNotificationCommence,
        'AlertNotificationClosing'=>$row->AlertNotificationClosing,
        'IncludeClosing'=>$row->IncludeClosing,    
        'AuctionerComments'=>$row->AuctionerComments,
        'InviteesComments'=>$row->InviteesComments,
        'auctionvalidityhour'=>0,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
                
        $d['al'][]=$this->db->insert('udt_AUM_Alerts', $alerts);
            
        $alerts_h=array(
        'CoCode'=>$row->CoCode,    
        'AuctionID'=>$row->AuctionID,    
        'CommenceAlertFlag'=>$row->CommenceAlertFlag,
        'AuctionCommences'=>$row->AuctionCommences,
        'LayCanStartDate'=>$row->LpLaycanStartDate,
        'OnlyDisplay'=>$row->OnlyDisplay,
        'CommenceDaysBefore'=>$row->CommenceDaysBefore,
        'CommenceDate'=>$row->CommenceDate,
        'AuctionCommenceDefinedDate'=>$row->AuctionCommenceDefinedDate,
        'AuctionValidity'=>$row->AuctionValidity,
        'AuctionCeases'=>$row->AuctionCeases,
        'AlertBeforeCommence'=>$row->AlertBeforeCommence,
        'AlertBeforeClosing'=>$row->AlertBeforeClosing,
        'AlertNotificationCommence'=>$row->AlertNotificationCommence,
        'AlertNotificationClosing'=>$row->AlertNotificationClosing,
        'IncludeClosing'=>$row->IncludeClosing,    
        'AuctionerComments'=>$row->AuctionerComments,
        'InviteesComments'=>$row->InviteesComments,
        'auctionvalidityhour'=>0,
        'UserID'=>$UserID,
        'RowStatus'=>'1',
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AUM_Alerts_h', $alerts_h);
                
            
    }
    return $d; 
}
    
public function changeStatusCsvData($ids)
{
    $data=array('ImportedStatus'=>1);
    $this->db->where_in('CSV_ID', $ids);
    $this->db->update('udt_AU_CSVData', $data);
}
    
public function LoginLog($ip,$UID)
{
    include_once APPPATH.'third_party/location/geoipcity.inc';
    include_once APPPATH.'third_party/location/geoipregionvars.php';
    $path = APPPATH.'third_party/location/GeoLiteCity.dat';
    $giCity = geoip_open($path, GEOIP_STANDARD);
    $record = geoip_record_by_addr($giCity, $ip);
    /*
    echo "Country Code: " . $record->country_code .  "<br />" .
    "Country Code3: " . $record->country_code . "<br />" .
    "Country Name: " . $record->country_name . "<br />" .
    "Region Code: " . $record->region . "<br />" .
    "Region Name: " . $GEOIP_REGION_NAME[$record->country_code][$record->region] . "<br />" .
    "City: " . $record->city . "<br />" .
    "Postal Code: " . $record->postal_code . "<br />" .
    "Latitude: " . $record->latitude . "<br />" .
    "Longitude: " . $record->longitude . "<br />" .
    "Metro Code: " . $record->metro_code . "<br />" .
    "Area Code: " . $record->area_code . "<br />" ; 
    */
        
    $data=array(
    'CurrDate'=>date('Y-m-d H:i:s'),
    'UserID'=>$UID,
    'IPAddress'=>$ip,
    'CountryCode'=>$record->country_code,
    'CountryName'=>$record->country_name,
    'RegionCode'=>$record->region,
    'RegionName'=>$GEOIP_REGION_NAME[$record->country_code][$record->region],
    'CityName'=>$record->city,
    'Latitude'=>$record->latitude,
    'Longitude'=>$record->longitude
    );
    $this->db->insert('udt_AU_LoginLog', $data);
}
    
public function getAuditData()
{
    if($this->input->post()) {
        $EntityID=$this->input->post('EntityID');
        $AuctionID=$this->input->post('AuctionID');
        $UserMasterID=$this->input->post('UserMasterID');
        $dateFrom=$this->input->post('dateFrom');
        $dateTo=$this->input->post('dateTo');
        $Created=$this->input->post('Created');
        $Status=$this->input->post('Status');
    } else if($this->input->get()) {
        $EntityID=$this->input->get('EntityID');
        $AuctionID=$this->input->get('AuctionID');
        $UserMasterID=$this->input->get('UserMasterID');
        $dateFrom=$this->input->get('dateFrom');
        $dateTo=$this->input->get('dateTo');
        $Created=$this->input->get('Created');
        $Status=$this->input->get('Status');
    }
        
    $this->db->select('udt_AU_Auctions_H.*, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_EntityMaster.EntityName ');
    $this->db->from('udt_AU_Auctions_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Auctions_H.UserID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions_H.OwnerEntityID');
        
    if($EntityID) {
        $this->db->where('udt_AU_Auctions_H.OwnerEntityID', $EntityID);
    }
        
    if($AuctionID) {
        $this->db->where('udt_AU_Auctions_H.AuctionID', $AuctionID);
    }
        
    if($UserMasterID) {
        $this->db->where('udt_AU_Auctions_H.UserID', $UserMasterID);
    }
        
    if($Created) {
        $this->db->where('udt_AU_Auctions_H.RecordStatus', $Created);
    }
        
    if($Status) {
        $this->db->where('udt_AU_Auctions_H.auctionStatus', $Status);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AU_Auctions_H.UserDate >=', date('Y-m-d', strtotime($dateFrom)));
    } 
        
    if($dateTo) {
        $this->db->where('udt_AU_Auctions_H.UserDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }  
    $where= " (RowStatus=1 OR RowStatus=4) ";
    $this->db->where($where);
    $this->db->order_by('udt_AU_Auctions_H.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getUpdatedDate($AuctionID)
{
    $UpdatedDates=array();
    $this->db->select('UserDate');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $UpdatedDates[]=$query->row()->UserDate;
        
    $this->db->select('UserDate');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $crgo=$query->result();
        
    foreach($crgo as $c) {
        $UpdatedDates[]=$c->UserDate;
        $UpdatedDates[]=$c->Estimate_UserDate;
    }
        
    $this->db->select('UserDate');
    $this->db->from('udt_AUM_Differentials');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $dfrtl=$query->result();
        
    foreach($dfrtl as $d) {
        $UpdatedDates[]=$d->UserDate;
    }
        
    $this->db->select('UserDate');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $UpdatedDates[]=$query->row()->UserDate;
        
    $this->db->select('UserDate');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $UpdatedDates[]=$query->row()->UserDate;
        
    return max($UpdatedDates);die;
}
    
public function getAuction()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_Auctions_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Auctions_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Auctions_H.UserID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions_H.OwnerEntityID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_Auctions_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargo()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_Cargo_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_Cargo_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Cargo_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('Estimate_RowStatus', null);
    $this->db->order_by('udt_AU_Cargo_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDifferential()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AUM_Differentials_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AUM_Differentials_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Differentials_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('udt_AUM_Differentials_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFreightEstimateLog()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_Cargo_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_Cargo_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Cargo_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $where ='Estimate_RowStatus is not null';
    $this->db->where($where);
    $this->db->order_by('udt_AU_Cargo_H.Estimate_UserDate', 'ASC');
        
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getInvitees()
{
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AUM_Invitees_H.*, udt_UserMaster.FirstName, udt_UserMaster.LastName');
    $this->db->from('udt_AUM_Invitees_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitees_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('udt_AUM_Invitees_H.UserDate', 'ASC');
    $this->db->order_by('udt_AUM_Invitees_H.InvPriorityStatus', 'DESC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getAlerts()
{
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AUM_Alerts_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AUM_Alerts_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Alerts_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('udt_AUM_Alerts_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getAuctionRol($id)
{
    $this->db->select('*');
    $this->db->from('udt_EntityType');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Description;
}
    
public function getCargoMaster1($id)
{
    $this->db->select('*');
    $this->db->from('udt_CargoMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Code;
}
    
public function getLoadPortDisport($id)
{
    $this->db->select('*');
    $this->db->from('udt_PortMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Description;
}
    
public function getLoadingTerm($id)
{
    $this->db->select('*');
    $this->db->from('udt_CP_LoadingDischargeTermsMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Code;
}
    
public function getTurnFreeTime($id)
{
    $this->db->select('*');
    $this->db->from('udt_CP_LayTimeFreeTimeConditionMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Code;
}
    
public function getNorTendring($id)
{
    $this->db->select('*');
    $this->db->from('udt_CP_NORTenderingConditionMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row()->Code;
}
    
public function getVesselSizeByID($id)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Vessel_Master');
    $this->db->where('VesselID', $id);
    $query=$this->db->get();
    return $query->row()->VesselSize;
}
    
public function getStatus($AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row()->auctionStatus;
}
    
public function getAuctionStatus()
{
    $AuctionId=$this->input->get('AuctionId');
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionId);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function cloneModelById()
{
    $mid=$this->input->post('mid');
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_AU_Model');
    $this->db->where('mid', $mid);
    $query=$this->db->get();
    $data=$query->row();
        
    $data1=array(
    'RecordOwner'=>$data->RecordOwner,
    'EntityTypeRole'=>$data->EntityTypeRole,
    'ModelNumber'=>$data->ModelNumber,
    'MaxPoint'=>$data->MaxPoint,
    'ModelStatus'=>0,
    'FreightCriteriaStatus'=>$data->FreightCriteriaStatus,
    'FMaxPoint'=>$data->FMaxPoint,
    'FpercentageRange1'=>$data->FpercentageRange1,
    'FpercentageRange2'=>$data->FpercentageRange2,
    'FpercentageRange3'=>$data->FpercentageRange3,
    'FpercentageRange4'=>$data->FpercentageRange4,
    'FpercentageRange5'=>$data->FpercentageRange5,
    'FpercentageValue1'=>$data->FpercentageValue1,
    'FpercentageValue2'=>$data->FpercentageValue2,
    'FpercentageValue3'=>$data->FpercentageValue3,
    'FpercentageValue4'=>$data->FpercentageValue4,
    'FpercentageValue5'=>$data->FpercentageValue5,
                    
    'FIDDCriteriaStatus'=>$data->FIDDCriteriaStatus,
    'FIDDMaxPoint'=>$data->FIDDMaxPoint,
    'FIDDpercentageRange1'=>$data->FIDDpercentageRange1,
    'FIDDpercentageRange2'=>$data->FIDDpercentageRange2,
    'FIDDpercentageRange3'=>$data->FIDDpercentageRange3,
    'FIDDpercentageRange4'=>$data->FIDDpercentageRange4,
    'FIDDpercentageRange5'=>$data->FIDDpercentageRange5,
    'FIDDpercentageValue1'=>$data->FIDDpercentageValue1,
    'FIDDpercentageValue2'=>$data->FIDDpercentageValue2,
    'FIDDpercentageValue3'=>$data->FIDDpercentageValue3,
    'FIDDpercentageValue4'=>$data->FIDDpercentageValue4,
    'FIDDpercentageValue5'=>$data->FIDDpercentageValue5,
                    
    'InviteeCriteriaStatus'=>$data->InviteeCriteriaStatus,
    'IMaxPointForCriteria'=>$data->IMaxPointForCriteria,
    'InviteePriorityValue1'=>$data->InviteePriorityValue1,
    'InviteePriorityValue2'=>$data->InviteePriorityValue2,
    'InviteePriorityValue3'=>$data->InviteePriorityValue3,
    'InviteePriorityValue4'=>$data->InviteePriorityValue4,
    'PSCDLYCS'=>$data->PSCDLYCS,
    'PSCDLYMP'=>$data->PSCDLYMP,
    'PSCDLYCFMP'=>$data->PSCDLYCFMP,
    'PSCDLYAPC'=>$data->PSCDLYAPC,
    'PSCDPCS'=>$data->PSCDPCS,
    'PSCDPMPC'=>$data->PSCDPMPC,
    'PSCDPCMP'=>$data->PSCDPCMP,
    'PSCDPAPC'=>$data->PSCDPAPC,
    'PSCDRPFLPACS'=>$data->PSCDRPFLPACS,
    'PSCDRPFLPAMPC'=>$data->PSCDRPFLPAMPC,
    'PSCDRPFLPACMP'=>$data->PSCDRPFLPACMP,
    'PSCDRPFLPAAPC'=>$data->PSCDRPFLPAAPC,
    'PFLPPADCS'=>$data->PFLPPADCS,
    'PFLPPADMPC'=>$data->PFLPPADMPC,
    'PFLPPADMPC1'=>$data->PFLPPADMPC1,
    'PFLPPADMPC2'=>$data->PFLPPADMPC2,
    'PFLPPADMPC3'=>$data->PFLPPADMPC3,
    'PFLPPADMPC4'=>$data->PFLPPADMPC4,
    'PFLPPADMPC5'=>$data->PFLPPADMPC5,
    'PFLPPADMPV1'=>$data->PFLPPADMPV1,
    'PFLPPADMPV2'=>$data->PFLPPADMPV2,
    'PFLPPADMPV3'=>$data->PFLPPADMPV3,
    'PFLPPADMPV4'=>$data->PFLPPADMPV4,
    'PFLPPADMPV5'=>$data->PFLPPADMPV5,
    'IPOLYCS'=>$data->IPOLYCS,
    'IPOLYMPC'=>$data->IPOLYMPC,
    'IPOLYCMP'=>$data->IPOLYCMP,
    'IPOLYAPC'=>$data->IPOLYAPC,
    'VLTCS'=>$data->VLTCS,
    'VLTMPC'=>$data->VLTMPC,
    'VLTCMP'=>$data->VLTCMP,
    'VLTAPC'=>$data->VLTAPC,
    'RatingStatus'=>$data->RatingStatus,
    'RatingMaxPoints'=>$data->RatingMaxPoints,
    'PrcentRangeValue1'=>$data->PrcentRangeValue1,
    'PrcentRangeValue2'=>$data->PrcentRangeValue2,
    'PrcentRangeValue3'=>$data->PrcentRangeValue3,
    'PrcentRangeValue4'=>$data->PrcentRangeValue4,
    'PrcentRangeValue5'=>$data->PrcentRangeValue5,
    'DemurrageCriteriaStatus'=>$data->DemurrageCriteriaStatus,
    'DMaxPoint'=>$data->DMaxPoint,
    'DpercentageRange1'=>$data->DpercentageRange1,
    'DpercentageRange2'=>$data->DpercentageRange2,
    'DpercentageRange3'=>$data->DpercentageRange3,
    'DpercentageRange4'=>$data->DpercentageRange4,
    'DpercentageRange5'=>$data->DpercentageRange5,
    'DpercentageValue1'=>$data->DpercentageValue1,
    'DpercentageValue2'=>$data->DpercentageValue2,
    'DpercentageValue3'=>$data->DpercentageValue3,
    'DpercentageValue4'=>$data->DpercentageValue4,
    'DpercentageValue5'=>$data->DpercentageValue5,
    'CreatedBy'=>$UserID,
    'add_date'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AU_Model', $data1);
}
    
    
public function saveClauseData()
{
    extract($this->input->post());
    $newclause_name=str_replace("'", "''", $clause_name);
    $clause_text=str_replace("'", "''", $clause_text);
    $clause_text=str_replace("&#39;", "", $clause_text);
    $data=array(
                'SerialNo'=>$ser_no,
                'RescordOwner'=>$EntityID,
                'ClauseNo'=>$clause_no,
                'CaluseName'=>$newclause_name,
                'SubClauseFlag'=>$sub_clause_flage,
                'SubClauseNo'=>$sub_clause_no,
                'Editable'=>$editable,
                'ClauseText'=>$clause_text,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'DocumentTypeID'=>$DocumentTypeID,
                'last_clause'=>$last_clause,
                'allClausesStatus'=>$allClausesStatus,
                'completed_by'=>$completed_by,
                'completed_date'=>$completed_date
                );
    return $this->db->insert('udt_AUM_DocumentClause', $data);
}
    
public function getDocumentClauses()
{
    if($this->input->post()) {
        $DocumentTypeID=$this->input->post('DocumentTypeID');
    }
    if($this->input->get()) {
        $DocumentTypeID=$this->input->get('DocumentTypeID');
    }
        
    $this->db->select('udt_AUM_DocumentClause.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_DocumentClause.UserID');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $this->db->order_by('SerialNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentClausesById()
{
    $ClauseID=$this->input->post('ClauseID');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('ClauseID', $ClauseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateClauseData()
{
    extract($this->input->post());
    $newclause_name=str_replace("'", "", $clause_name);
    $clause_text=str_replace("'", "''", $clause_text);
    $clause_text=str_replace("&#39;", "", $clause_text);
        
    $data=array(
                'SerialNo'=>$ser_no,
                'ClauseNo'=>$clause_no,
                'CaluseName'=>$newclause_name,
                'SubClauseFlag'=>$sub_clause_flage,
                'SubClauseNo'=>$sub_clause_no,
                'Editable'=>$editable,
                'ClauseText'=>$clause_text,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'last_clause'=>$last_clause,
                'allClausesStatus'=>$allClausesStatus,
                'completed_by'=>$completed_by,
                'completed_date'=>$completed_date
    );
    $this->db->where('ClauseID', $ClauseID);
    $this->db->update('udt_AUM_DocumentClause', $data);
}
    
public function clauseDelete()
{
    $id=trim($this->input->post('id'), ",");
    $ids=explode(",", $id);
    $this->db->where_in('ClauseID', $ids);
    return $this->db->delete('udt_AUM_DocumentClause');
}
    
public function getClausesText()
{
    $ClauseID=$this->input->post('ClauseID');
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(ClauseText, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AUM_DocumentClause');
        $this->db->where('ClauseID', $ClauseID);
        $query=$this->db->get();
        $result=$query->row();
        if($result->PTR) {
            $content .=$result->PTR;
            $strlen = $strlen + strlen($result->PTR);
        }else{
            $temp=0;
        }
    }
    return $content;
        
        
}
    
    
    
public function getRecordOwnerEntity()
{ 
    $key=$this->input->post('key');
    $EntityID=$this->input->post('EntityID');
        
    $this->db->select('ID,FirstName,LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where('EntityID', $EntityID);
    $this->db->where('ApproveDocStoreFlg', '1');
    $this->db->like('FirstName', $key, 'after');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function deleteAttactedFile()
{
    $id=$this->input->post('id');
    $data=array('CharterPartyPdf'=>'');
    $this->db->where('DocumentTypeID', $id);
    return $this->db->update('udt_AUM_DocumentType_Master', $data);
}
    
public function deleteAttactedLogo()
{
    $id=$this->input->post('id');
    $data=array('Logo'=>'');
    $this->db->where('DocumentTypeID', $id);
    return $this->db->update('udt_AUM_DocumentType_Master', $data);
}
    
public function getFixNotTemplate()
{
    $EntityID=$this->input->get('EntityID');
    $this->db->select('udt_AUM_ReportTemplate.*,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_ReportTemplate.UserID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_ReportTemplate.EntityID');
    if($EntityID) {
        $this->db->where('udt_AUM_ReportTemplate.EntityID', $EntityID);
    }
    $this->db->order_by('udt_AUM_ReportTemplate.CreatedDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveFixNoteTemplate()
{
    extract($this->input->post());
        
    $data=array(
                'EntityID'=>$RecordOwner,
                'TName'=>$teplate_name,
                'Status'=>$Status,
                'UserID'=>$user,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AUM_ReportTemplate', $data);
}
    
public function getFixNoteTemplate()
{
    $TempID=$this->input->post('TempID');
    $this->db->select('udt_AUM_ReportTemplate.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_ReportTemplate.EntityID');
    $this->db->where('TemplateID', $TempID);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateFixNoteTemplate()
{
    extract($this->input->post());
        
    $data=array(
                'EntityID'=>$RecordOwner,
                'TName'=>$teplate_name,
                'Status'=>$Status,
                'UserID'=>$user,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('TemplateID', $TempID);            
    $this->db->update('udt_AUM_ReportTemplate', $data);
}
    
public function deleteFixTemplateNoteById()
{
    $TempID=$this->input->post('TempID');
    $this->db->where('TemplateID', $TempID);            
    $this->db->delete('udt_AUM_ReportTemplate');
}
    
public function getFixNotTemplateConfiguration()
{
    $TempID=$this->input->post('TempID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Template');
    $this->db->where('TemplateID', $TempID);
    $query=$this->db->get();
    $cnt=$query->num_rows();
    if($cnt==0) {
        $this->db->select('udt_AU_Template.*,Udt_AUM_HelpText.HelpText');
        $this->db->from('udt_AU_Template');
        $this->db->join('Udt_AUM_HelpText', 'Udt_AUM_HelpText.LINK_HTID=udt_AU_Template.HTID', 'left');
        $this->db->where('TemplateID', 1);
        $this->db->order_by('FieldGroupNo', 'ASC');
        $this->db->order_by('DisplaySno', 'ASC');
        $this->db->order_by('TID', 'ASC');
        $query=$this->db->get();
        return $query->result();
    } else {
        $this->db->select('udt_AU_Template.*,Udt_AUM_HelpText.HelpText');
        $this->db->from('udt_AU_Template');
        $this->db->join('Udt_AUM_HelpText', 'Udt_AUM_HelpText.LINK_HTID=udt_AU_Template.HTID', 'left');
        $this->db->where('TemplateID', $TempID);
        $this->db->order_by('FieldGroupNo', 'ASC');
        $this->db->order_by('DisplaySno', 'ASC');
        $this->db->order_by('TID', 'ASC');
        $query=$this->db->get();
        return $query->result();
    }
}
    
public function saveFixNoteTemplateConfigration()
{
    extract($this->input->post());
        
    $this->db->where('TemplateID', $TempID);
    $this->db->delete('FixtureNoteTemplateClauses');
        
    if(isset($sno)) {
        for($ii=0; $ii<count($sno); $ii++){
            $c_data=array(
            'TemplateID'=>$TempID,
            'SNO'=>$sno[$ii],
            'ClauseText'=>$cp_text[$ii],
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('FixtureNoteTemplateClauses', $c_data);
        }
    }
        
    $this->db->where('TemplateID', $TempID);
    $this->db->update('udt_AUM_ReportTemplate', array('Type'=>$type,'CreatedDate'=>date('Y-m-d H:i:s')));
        
    $AllInclude=trim($AllInclude, "~__~");
    $AllNewFieldName=trim($AllNewFieldName, "~__~");
    $AllCpCode=trim($AllCpCode, "~__~");
    $IncludeArr=explode('~__~', $AllInclude);
    $NewFieldNameArr=explode('~__~', $AllNewFieldName);
    $CpCodeArr=explode('~__~', $AllCpCode);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Template');
    $this->db->where('TemplateID', 1);
    $this->db->order_by('FieldGroupNo', 'ASC');
    $this->db->order_by('DisplaySno', 'ASC');
    $query=$this->db->get();
    $rslt=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Template');
    $this->db->where('TemplateID', $TempID);
    $query=$this->db->get();
    $cnt=$query->num_rows();
        
    if($cnt==0) { 
    } else {
         $this->db->where('TemplateID', $TempID);
         $this->db->delete('udt_AU_Template');
            
    }
    for($i=0;$i<count($IncludeArr);$i++) {
        $cp_code='';
        $include_code='';
        $filed_name_code='';
        if($CpCodeArr[$i] != 'ABLANK') {
            $cp_code=$CpCodeArr[$i];
        }
        if($IncludeArr[$i] != 'ABLANK') {
            $include_code=$IncludeArr[$i];
        }
        if($NewFieldNameArr[$i] != 'ABLANK') {
            $filed_name_code=$NewFieldNameArr[$i];
        }
            $data=array(
                'SeqNo'=>$rslt[$i]->SeqNo,
                'NewSeqNo'=>$rslt[$i]->NewSeqNo,
                'FieldName'=>$rslt[$i]->FieldName,
                'DisplayName'=>$rslt[$i]->DisplayName,
                'Included'=>$include_code,
                'NewDisplayName'=>$filed_name_code,
                'CpCode'=>$cp_code,
                'FieldGroupNo'=>$rslt[$i]->FieldGroupNo,
                'DisplaySno'=>$rslt[$i]->DisplaySno,
                'HTID'=>$rslt[$i]->HTID,
                'Active'=>1,
                'TemplateID'=>$TempID,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
            );
            
            $this->db->insert('udt_AU_Template', $data);
    }
}
    
public function getFixReportTemplate()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('udt_AUM_ReportTemplate');
    if($EntityID) {
        $this->db->where('EntityID', $EntityID);
    }
    $this->db->where('Status', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixNotTemplateCpText()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    } 
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('*');
    $this->db->from('FixtureNoteTemplateClauses');
    $this->db->where('FixtureNoteTemplateClauses.TemplateID', $TempID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getCpType()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    } 
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('Type');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->where('udt_AUM_ReportTemplate.TemplateID', $TempID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCpText()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    } 
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('ClauseText');
    $this->db->from('FixtureNoteTemplateClauses');
    $this->db->where('TemplateID', $TempID);
    $this->db->order_by('SNO', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixTemplateNoteHtmlById()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    }
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('udt_AU_Template.*');
    $this->db->from('udt_AU_Template');
    $this->db->join('udt_AUM_ReportTemplate', 'udt_AUM_ReportTemplate.TemplateID=udt_AU_Template.TemplateID');
    $this->db->where('udt_AU_Template.TemplateID', $TempID);
    $this->db->where('udt_AU_Template.Included', '1');
    $this->db->order_by('NewSeqNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixTemplateOwner()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    }
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('*');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->where('udt_AUM_ReportTemplate.TemplateID', $TempID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentLogo()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    } 
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('DocumentTitle,Logo,OwnerEntityID,udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('FixNoteTemplate', $TempID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixTemplateNoteDownload()
{
    if($this->input->post()) {
        $TempID=$this->input->post('TempID');
    }
    if($this->input->get()) {
        $TempID=$this->input->get('TempID');
    }
    $this->db->select('udt_AU_Template.*');
    $this->db->from('udt_AU_Template');
    $this->db->join('udt_AUM_ReportTemplate', 'udt_AUM_ReportTemplate.TemplateID=udt_AU_Template.TemplateID');
    $this->db->where('udt_AU_Template.TemplateID', $TempID);
    $this->db->where('udt_AU_Template.Included', 1);
    $this->db->order_by('SeqNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentTypeFileName()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentTypeID', $id);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getAttachedPdf()
{
    $DocumentTypeID=$this->input->post('docID');
    $this->db->select('udt_AUM_DocumentType_Master.*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function saveServerContent()
{
    extract($this->input->post());
        
    $data=array(
    'TestVersion'=>$projectVersion,
    'TestedOnServer1'=>$onserver1,
    'TestedOnServer2'=>$onserver2,
    'ServerChanges'=>$UpdateContent,
    'ByUser'=>$user,
    'ChangesDateTime'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AU_TestServerTable', $data);
    
}

public function getProjectVersion()
{
    $this->db->select('*');
    $this->db->from('udt_AU_TestServerTable');
    $query=$this->db->get();
    if($query->num_rows()) {
        $result=$query->row();
        return $result->TestVersion;
    }else{
        return 1;
    }
}
    
public function get_all_updatecontent()
{
    $this->db->select('*');
    $this->db->from('udt_AU_TestServerTable');
    $query=$this->db->get();
    return $query->result();
}
    
public function getContentByTestTableId()
{
    $TestTableID=$this->input->post('TestTableID');
    $this->db->select('*');
    $this->db->from('udt_AU_TestServerTable');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function deleteServerContentById()
{
    $TestTableID=$this->input->post('id');
    $this->db->where('TestTableID', $TestTableID);
    return $this->db->delete('udt_AU_TestServerTable');
}
    
public function getContentDataById()
{
    $TestTableID=$this->input->post('TestTableID');
    $this->db->select('*');
    $this->db->from('udt_AU_TestServerTable');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function updateServerContent()
{
    extract($this->input->post());
        
    $data=array(
                'TestVersion'=>$projectVersion,
                'TestedOnServer1'=>$onserver1,
                'TestedOnServer2'=>$onserver2,
                'ServerChanges'=>$UpdateContent,
                'ByUser'=>$user,
                'ChangesDateTime'=>date('Y-m-d H:i:s')
    );
    $this->db->where('TestTableID', $TestTableID);
    return $this->db->update('udt_AU_TestServerTable', $data);
    
}
    
public function getUserTermCondtion()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $query=$this->db->get();
    $rslt=$query->row();
    if(count($rslt)>0) {
        if($rslt->TermCheckFlag==1 || $rslt->ID==1) {
            return 'yes';
        } else {
            $this->db->select('*');
            $this->db->from('AUM_TermCondition');
            $this->db->where('EntityID', $rslt->EntityID);
            $this->db->where('Status', 3);
            $this->db->where('Application', 1);
            $query=$this->db->get();
            return $query->row();
        }
    } else {
        return 0;
    }
        
}
    
public function getNotification()
{
    $RecordOwner=$this->input->get('RecordOwner');
    $MessageStatus=$this->input->get('MessageStatus');
    $MessageType=$this->input->get('MessageType');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
        
    $data=array('Status'=>0);
    $this->db->where('RecordOwner', $RecordOwner);
    $this->db->where('MessageDisplayTo <=', date('Y-m-d'));
    $this->db->update('udt_AUM_NotificationMaster', $data);
        
    $this->db->select('udt_AUM_NotificationMaster.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_NotificationMaster.RecordOwner');
        
    if($RecordOwner) {
        $this->db->where('RecordOwner', $RecordOwner);
    }
        
    if($MessageStatus=='1') {
        $this->db->where('udt_AUM_NotificationMaster.Status', $MessageStatus);
    }
        
    if($MessageStatus=='0') {
        $this->db->where('udt_AUM_NotificationMaster.Status', $MessageStatus);
    }
        
    if($MessageType) {
        $this->db->where('udt_AUM_NotificationMaster.MessageType', $MessageType);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AUM_NotificationMaster.MessageDisplayFrom >=', date('Y-m-d', strtotime($dateFrom)));
    } 
        
    if($dateTo) {
        $this->db->where('udt_AUM_NotificationMaster.MessageDisplayTo <=', date('Y-m-d H:i:s', strtotime($dateTo)));
    }
        
    $query=$this->db->get();
    return $query->result();
}
    
public function getUsersByEntityID()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('EntityID', $EntityID);
    $this->db->like('FirstName', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function addNotification()
{
    extract($this->input->post());
    if($MessageType==2 && $NotificationType==2) {
        for($i=0;$i<count($user_id);$i++) {
            $data=array(
            'RecordOwner'=>$entityID,
            'MessageType'=>$MessageType,
            'NotificationType'=>$NotificationType,
            'UserID'=>$user_id[$i],
            'SelectType'=>$type,
            'Describe'=>$discribe,
            'NotificationText'=>$notification_text,
            'MessageDisplayFrom'=>date('Y-m-d', strtotime($dateFrom)),
            'MessageDisplayTo'=>date('Y-m-d', strtotime($dateTo)),
            'Status'=>$status,
            'ByUser'=>$ByUser,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $rsl=$this->db->insert('udt_AUM_NotificationMaster', $data);
        }
        return $rsl;
    } else {
        $data1=array(
        'RecordOwner'=>$entityID,
        'MessageType'=>$MessageType,
        'NotificationType'=>$NotificationType,
        'SelectType'=>$type,
        'Describe'=>$discribe,
        'NotificationText'=>$notification_text,
        'MessageDisplayFrom'=>date('Y-m-d', strtotime($dateFrom)),
        'MessageDisplayTo'=>date('Y-m-d', strtotime($dateTo)),
        'Status'=>$status,
        'ByUser'=>$ByUser,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        return $this->db->insert('udt_AUM_NotificationMaster', $data1);
    }
        
}
    
public function deleteNotificationById()
{
    $id=$this->input->post('id');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->where('NID', $id);
    $query=$this->db->get();
    $notif_row=$query->row();
        
    $time1=date('Y-m-d', strtotime($notif_row->MessageDisplayFrom));
    $time2=date('Y-m-d', strtotime($notif_row->MessageDisplayTo));
    $time=date('Y-m-d');
        
    if(($time1 <= $time && $time <= $time2) || $time2<$time) {
        $this->db->where('NID', $id);
        $this->db->update('udt_AUM_NotificationMaster', array('Status'=>0));
        return 2;
    } else {
        $this->db->where('NID', $id);
        $this->db->delete('udt_AUM_NotificationMaster');
        return 1;
    }
        
}
    
    
public function getGenericNotification()
{
    $curdate=date('Y-m-d');
    $this->db->select('*,CONVERT(VARCHAR(10),MessageDisplayFrom,105) as msgdf,CONVERT(VARCHAR(10),MessageDisplayTo,105) as msgdt');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->where('MessageType', 1);
    $this->db->where('MessageDisplayFrom <=', $curdate);
    $this->db->where('MessageDisplayTo >=', $curdate);
    $this->db->where('Status', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getEntityWiseNotification()
{
    $curdate=date('Y-m-d');
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*,CONVERT(VARCHAR(10),MessageDisplayFrom,105) as msgdf,CONVERT(VARCHAR(10),MessageDisplayTo,105) as msgdt');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->where('MessageType', 2);
    $this->db->where('NotificationType', 1);
    $this->db->where('MessageDisplayFrom <=', $curdate);
    $this->db->where('MessageDisplayTo >=', $curdate);
    $this->db->where('Status', 1);
    $this->db->where('RecordOwner', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getUserWiseNotification()
{
    $curdate=date('Y-m-d');
    $UserID=$this->input->post('UserID');
    $this->db->select('*,CONVERT(VARCHAR(10),MessageDisplayFrom,105) as msgdf,CONVERT(VARCHAR(10),MessageDisplayTo,105) as msgdt');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->where('MessageType', 2);
    $this->db->where('NotificationType', 2);
    $this->db->where('MessageDisplayFrom <=', $curdate);
    $this->db->where('MessageDisplayTo >=', $curdate);
    $this->db->where('Status', 1);
    $this->db->where('UserID', $UserID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getMessagesByMessageDetail()
{
    $MessageDetailID=$this->input->post('MessageDetailID');
        
    $this->db->select('udt_AU_Messsage_Details.*,toUser.FirstName,toUser.LastName,toEntity.EntityName,fromUser.FirstName as FromFirstName,fromUser.LastName as FromLastName,fromEntity.EntityName as FromEntityName,udt_AUM_MESSAGE_MASTER.Message');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_AUM_MESSAGE_MASTER', 'udt_AUM_MESSAGE_MASTER.MessageID=udt_AU_Messsage_Details.MessageMasterID');
    $this->db->join('udt_UserMaster as toUser', 'toUser.ID=udt_AU_Messsage_Details.UserID', 'left');
    $this->db->join('udt_EntityMaster as toEntity', 'toEntity.ID=toUser.EntityID', 'left');
    $this->db->join('udt_UserMaster as fromUser', 'fromUser.ID=udt_AU_Messsage_Details.FromUserID', 'left');
    $this->db->join('udt_EntityMaster as fromEntity', 'fromEntity.ID=fromUser.EntityID', 'left');
    $this->db->where('udt_AU_Messsage_Details.MessageDetailID', $MessageDetailID);
    $this->db->where('udt_AU_Messsage_Details.StatusFlag', 1);
    $this->db->order_by('udt_AU_Messsage_Details.UserDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function markAsReadAndUnread()
{
    $MessageDetailID=$this->input->post('MessageDetailID');
    $read=$this->input->post('read');
    $data=array('StatusFlag'=>$read);
    $this->db->where('MessageDetailID', $MessageDetailID);
    $this->db->update('udt_AU_Messsage_Details', $data);
}
    
public function getAllIndividualCp()
{
    $DocumentTypeID=$this->input->post('DocumentTypeID');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getAllIndividualCpc()
{
    $DocumentTypeID=$this->input->post('DocumentTypeID');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentByEntityid()
{
    if($this->input->post()) {
        $EntityID=$this->input->post('EntityID');
    }
    if($this->input->get()) {
        $EntityID=$this->input->get('EntityID');
        $document_type=$this->input->get('document_type');
        $document_title=$this->input->get('document_title');
    }
    $this->db->select('udt_AUM_Document_master.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Document_master');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Document_master.RecoredOwner');
    if($EntityID) {
        $this->db->where('udt_AUM_Document_master.RecoredOwner', $EntityID);
    }
    if($document_type) {
        $this->db->where('udt_AUM_Document_master.DocType', $document_type);
    }
    if($document_title) {
        $this->db->where('udt_AUM_Document_master.DocName', $document_title);
    }
        
    $this->db->order_by('udt_AUM_Document_master.CreatedDateTime', 'desc');
        
    $query=$this->db->get();
    return $query->result();
}
    
    
public function saveDocumentMaster()
{
    extract($this->input->post());
    $data=array(
    'DocType'=>$DocumentType,
    'DocName'=>$DocumentTitle,
    'Status'=>$ActiveFlag,
    'Comment'=>$Comment,
    'CreatedDateTime'=>date('Y-M-d H:i:s'),
    'RecoredOwner'=>$EntityMasterID,
    'Comment'=>$comment,
    'UserID'=>$UserID,
    );
    return $this->db->insert('udt_AUM_Document_master', $data);
}
    
public function getDocumentByDmid()
{
    $id=$this->input->post('id');
    $this->db->select('udt_AUM_Document_master.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Document_master');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Document_master.RecoredOwner');
    $this->db->where('DMID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateDocumentMaster()
{
    extract($this->input->post());
    $data=array(
    'DocType'=>$DocumentType,
    'DocName'=>$DocumentTitle,
    'Status'=>$ActiveFlag,
    'Comment'=>$Comment,
    'CreatedDateTime'=>date('Y-M-d H:i:s'),
    'RecoredOwner'=>$EntityMasterID,
    'Comment'=>$comment,
    'UserID'=>$UserID,
    );
                $this->db->where('DMID', $DMID);    
    return $this->db->update('udt_AUM_Document_master', $data);
}
    
public function deleteDocumentMaster()
{
    $id=$this->input->post('id');
    $id=trim($id, ',');
    $ids=explode(',', $id);
    $this->db->where_in('DMID', $ids);
    $this->db->delete('udt_AUM_Document_master');
}
    
public function getDocumentTypeTitleByEntityid()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('distinct DocType');
    $this->db->from('udt_AUM_Document_master');
    if($EntityID) {
        $this->db->where('RecoredOwner', $EntityID);
    }
    $this->db->where('Status', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentTitleByDocumentType($DocumentType)
{
    
    $EntityID=$this->input->post('EntityID');
    if(!$DocumentType) {
        $DocumentType=$this->input->post('DocumentType');
    }
        
    $this->db->select('DMID,DocName');
    $this->db->from('udt_AUM_Document_master');
    $this->db->where('RecoredOwner', $EntityID);
    $this->db->where('DocType', $DocumentType);
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentTitleByDocumentType1($DocumentType,$EntityMasterID)
{
    
    $this->db->select('DMID,DocName');
    $this->db->from('udt_AUM_Document_master');
    $this->db->where('RecoredOwner', $EntityMasterID);
    $this->db->where('DocType', $DocumentType);
    $query=$this->db->get();
    return $query->result();
}
    
public function getAllCountryRecord()
{ 
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CountryMaster', 'after');
    $this->db->like('Description', $key);
    $query=$this->db->get();
    return $query->result();
        
}

public function getAllParentCompanyRecord()
{ 
    $EntityID=$this->input->post('EntityID');
    $ParentFlag=$this->input->post('ParentFlag');
    $key=$this->input->post('key');
    $this->db->select('ID,GroupName');
    $this->db->from('udt_ParentGroupMaster', 'after');
        
    if($ParentFlag==1) {
        $this->db->where('EntityID', $EntityID);
    } else if($ParentFlag==2) {
        $this->db->where('EntityID', '1');
    }
    $this->db->like('GroupName', $key);
        
    $query=$this->db->get();
    return $query->result();
        
}

public function getAllStateRecord()
{ 
    $key=$this->input->post('key');
    $CountryID=$this->input->post('countryid');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_StateMaster', 'after');
    $this->db->like('Description', $key);
    $this->db->where('CountryID', $CountryID);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function saveMyParentMaster()
{
    extract($this->input->post());
        
    $document=$_FILES['Logo'];
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo='';
    }
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$CompanyID
        );
        $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
    }
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
                        'EmailID'=>$emailid[$i],
                        'EmailDescription'=>$email_desc[$i],
                        'CompanyID'=>$CompanyID
                    );
        $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
    }
        
    $cntel=count($type);
        
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                        'TelephoneType'=>$type[$i],
                        'TeleCountryCode'=>$contrycode[$i],
                        'TeleAreaCode'=>$areacode[$i],
                        'TeleNumber'=>$tele_number[$i],
                        'CompanyID'=>$CompanyID
                    );
        $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
    }
        
        
    $addr_data=array(
    'Address1'=>$address1,
    'Address2'=>$address2,
    'Address3'=>$address3,
    'Address4'=>$address4,
    'City'=>$city,
    'Telephone1'=>$telephone1,
    'Telephone2'=>$telephone2,
    'Fax1'=>'',
    'Fax2'=>'',
    'Email'=>$Email,
    'WebAddress'=>$web_address,
    'CountryID'=>$countryid,
    'ActiveFlag'=>'1',
    'ZipCode'=>$pin_code,
    'ParentCompanyID'=>$CompanyID
                );
    if($stateid) {
        $addr_data['StateID']=$stateid;
    }
            
    $this->db->insert('udt_AddressMaster', $addr_data);
        
    $this->db->select('*');
    $this->db->from('udt_AddressMaster');
    $this->db->where('ParentCompanyID', $CompanyID);
    $query=$this->db->get();
    $addressrow=$query->row();
        
    $AddressID=$addressrow->ID;
        
    $parent_grup_data=array(
    'GroupName'=>$parent_company_name,
    'Description'=>$parent_company_desc,
    'ActiveFlag'=>'1',
    'AddressID'=>$AddressID,
    'DateTime'=>date('Y-m-d H:i:s'),
    'IsConversionRecord'=>'',
    'CompanyID'=>$CompanyID,
    'AttachedLogo'=>$Logo,
    'CreatedBy'=>$UserID,
    'AlignLogo'=>$LogoAlign,
    'EntityID'=>$EntityMasterID,
    'MyParentComment'=>$comment
                );
        
    $ret=$this->db->insert('udt_ParentGroupMaster', $parent_grup_data);
        
    $RowStatus=1;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyParentMaster_H (ParentGroupID,ParentEntityName,ParentDescription,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCode,WebAddress,AttachedLogo,MyParentComment,ActiveFlag,RowStatus,UserID,EntityID,CreatedDate)
		select ID,GroupName,Description,CompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,MyParentComment,ActiveFlag,'".$RowStatus."',CreatedBy, EntityID, DateTime
		from cops_admin.udt_ParentGroupMaster where CompanyID='".$CompanyID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where CompanyID='".$CompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where CompanyID='".$CompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where CompanyID='".$CompanyID."'"
    );
        
    return $ret;
        
}
    
public function checkParentUniqueID($CompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->where('CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getParentMasterFixedData($rem)
{
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $this->db->select('TOP '.$rem.' P.ID as PID, P.GroupName, P.EntityID, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Telephone1');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->where('P.CreatedBy', '1');
    if($parententityname !='') {
        $this->db->where('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}

public function getParentMasterFixedAppendData()
{
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $this->db->select('P.ID as PID, P.GroupName, P.EntityID, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Telephone1');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->where('P.CreatedBy', '1');
    if($parententityname !='') {
        $this->db->where('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}

public function getParentMasterOtherCustomData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $where=' P.CreatedBy !=1 and P.CreatedBy !='.$UserID;
    $this->db->select('TOP 50 P.ID as PID, P.GroupName, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_EntityMaster.EntityName');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=P.EntityID', 'Left');
    $this->db->where($where);
    if($EntityID !='') {
        $this->db->where('P.EntityID', $EntityID);
    }
    if($parententityname !='') {
        $this->db->where('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
    
}

public function getParentMasterOtherCustomAppendData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $where=' P.CreatedBy !=1 and P.CreatedBy !='.$UserID;
    $this->db->select('P.ID as PID, P.GroupName, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_EntityMaster.EntityName');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=P.EntityID', 'Left');
    $this->db->where($where);
    if($EntityID !='') {
        $this->db->where('P.EntityID', $EntityID);
    }
    if($parententityname !='') {
        $this->db->where('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
    
}

    
public function getParentMasterMyCustomData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $ids=array('1',$EntityID);
    $this->db->select('TOP 50 P.ID as PID, P.GroupName, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.Telephone1, udt_EntityMaster.EntityName');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=P.EntityID', 'Left');
        
    if($EntityID !=1 && $EntityID !='') {
        $this->db->where_in('P.EntityID', $ids);
    }
    if($parententityname) {
        $this->db->like('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.DateTime', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getParentMasterMyCustomAppendData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $parententityname=$this->input->get('parententityname');
    $countryid=$this->input->get('countryid');
    $ids=array('1',$EntityID);
    $this->db->select('P.ID as PID, P.GroupName, P.Description as ParentDescription,P.DateTime, udt_CountryMaster.Description as CountryDescription, udt_AddressMaster.Email, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.Telephone1, udt_EntityMaster.EntityName');
    $this->db->from('udt_ParentGroupMaster as P');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=P.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=P.EntityID', 'Left');
        
    if($EntityID !=1 && $EntityID !='') {
        $this->db->where_in('P.EntityID', $ids);
    }
    if($parententityname) {
        $this->db->like('P.GroupName', $parententityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('P.DateTime', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}

    
public function getMyParentMasterData()
{
    $PID=$this->input->post('PID');
    $this->db->select('udt_ParentGroupMaster.GroupName, udt_ParentGroupMaster.EntityID, udt_ParentGroupMaster.AlignLogo, udt_EntityMaster.EntityName, udt_ParentGroupMaster.Description as ParentDescription, udt_ParentGroupMaster.CompanyID, udt_ParentGroupMaster.AddressID, udt_ParentGroupMaster.AttachedLogo, udt_CountryMaster.Description as CountryDescription, udt_CountryMaster.Code as CountryCode, udt_StateMaster.Description as StateDescription, udt_StateMaster.Code as StateCode, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.CountryID, udt_AddressMaster.StateID, udt_AddressMaster.City, udt_AddressMaster.ZipCode, udt_AddressMaster.WebAddress, udt_ParentGroupMaster.MyParentComment');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_ParentGroupMaster.EntityID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_ParentGroupMaster.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'Left');
    $this->db->where('udt_ParentGroupMaster.ID', $PID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getMyParentOtherIDs($CompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_OtherParentCompanyID');
    $this->db->where('udt_AU_OtherParentCompanyID.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}

public function getMyParentEmail($CompanyID)
{
    //print_r($CompanyID); die;
    $this->db->select('*');
    $this->db->from('udt_AU_ParentEmailDetails');
    $this->db->where('udt_AU_ParentEmailDetails.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}

public function getMyParentAddress($CompanyID)
{
    //print_r($CompanyID); die;
    $this->db->select('*');
    $this->db->from('udt_AU_ParentTelephoneDetails');
    $this->db->where('udt_AU_ParentTelephoneDetails.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}
    
public function updateParentMasterData()
{
    extract($this->input->post());
        
    $document=$_FILES['Logo'];
    //print_r($_FILES); die;
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo=$oldfile;
    }
        
    if(isset($OthParentCompID)) {
        $cnt1=count($OthParentCompID);
        for($i=0; $i<$cnt1; $i++){
            $oth_data=array(
             'IDNumber'=>$old_oth_comid[$i],
             'Location'=>$old_com_location[$i],
             'OtherDescription'=>$old_comp_desc[$i]
            );
            $this->db->where('OthParentCompID', $OthParentCompID[$i]);
            $this->db->update('udt_AU_OtherParentCompanyID', $oth_data);
        }
    }
    if(isset($other_companyid)) {
        $cnt=count($other_companyid);
        for($i=0; $i<$cnt; $i++){
            $oth_data=array(
             'IDNumber'=>$other_companyid[$i],
             'Location'=>$company_location[$i],
             'OtherDescription'=>$company_desc[$i],
             'CompanyID'=>$CompanyID
            );
            $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
        }
    }
        
    $Email='';
    if(isset($emailid)) {
        $cntem=count($emailid);
        for($i=0; $i<$cntem; $i++){
            if($i==0) {
                $Email=$emailid[$i];
            }
            $eml_data=array(
            'EmailID'=>$emailid[$i],
            'EmailDescription'=>$email_desc[$i],
            'CompanyID'=>$CompanyID
                        );
            $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
        }
    }
    if(isset($ParentEmailID)) {
        $cntem1=count($ParentEmailID);
        for($i=0; $i<$cntem1; $i++){
            if($i==0) {
                $Email=$old_emailid[$i];
            }
            $eml_data=array(
            'EmailID'=>$old_emailid[$i],
            'EmailDescription'=>$old_email_desc[$i]
                        );
            $this->db->where('ParentEmailID', $ParentEmailID[$i]);
            $this->db->update('udt_AU_ParentEmailDetails', $eml_data);
        }
            
    }
        
        
        
    $telephone1='';
    $telephone2='';
    if(isset($type)) {
        $cntel=count($type);
        for($i=0; $i<$cntel; $i++){
            if($i==0 ) {
                $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
            } else if($i==1 ) {
                $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
            } 
                
            $tel_data=array(
                            'TelephoneType'=>$type[$i],
                            'TeleCountryCode'=>$contrycode[$i],
                            'TeleAreaCode'=>$areacode[$i],
                            'TeleNumber'=>$tele_number[$i],
                            'CompanyID'=>$CompanyID
            );
            $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
        }
    }
        
    if(isset($ParentTelephoneID)) {
        $cntel1=count($ParentTelephoneID);
        for($i=0; $i<$cntel1; $i++){
            if($i==0 ) {
                $telephone1=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
            } else if($i==1 ) {
                $telephone2=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
            } 
                
            $tel_data=array(
                            'TelephoneType'=>$old_type[$i],
                            'TeleCountryCode'=>$old_contrycode[$i],
                            'TeleAreaCode'=>$old_areacode[$i],
                            'TeleNumber'=>$old_tele_number[$i]
            );
            $this->db->where('ParentTelephoneID', $ParentTelephoneID[$i]);
            $this->db->update('udt_AU_ParentTelephoneDetails', $tel_data);
        }
            
    }
        
    if($AddressID) {
        $addr_data=array(
        'Address1'=>$address1,
        'Address2'=>$address2,
        'Address3'=>$address3,
        'Address4'=>$address4,
        'Telephone1'=>$telephone1,
        'Telephone2'=>$telephone2,
        'Email'=>$Email,
        'City'=>$city,
        'ZipCode'=>$pin_code,
        'WebAddress'=>$web_address,
        'ParentCompanyID'=>$CompanyID
        );
        if($countryid) {
            $addr_data['CountryID']=$countryid;
        } else {
            $addr_data['CountryID']=null;
        }
        if($stateid) {
            $addr_data['StateID']=$stateid;
        } else {
            $addr_data['StateID']=null;
        }    
            
            $this->db->where('ID', $AddressID);        
            $this->db->update('udt_AddressMaster', $addr_data);
            
            $adress_id=$AddressID;
            
    } else {
         $addr_data=array(
        'Address1'=>$address1,
        'Address2'=>$address2,
        'Address3'=>$address3,
        'Address4'=>$address4,
        'Telephone1'=>$telephone1,
        'Telephone2'=>$telephone2,
        'Email'=>$Email,
        'City'=>$city,
        'ZipCode'=>$pin_code,
        'ActiveFlag'=>'1',
        'ParentCompanyID'=>$CompanyID
          );    
         if($stateid) {
             $addr_data['StateID']=$stateid;
         }
         if($countryid) {
              $addr_data['CountryID']=$countryid;
         }            
            $this->db->insert('udt_AddressMaster', $addr_data);
            
            $this->db->select('*');
            $this->db->from('udt_AddressMaster');
            $this->db->where('ParentCompanyID', $CompanyID);
            $query=$this->db->get();
            $addressrow=$query->row();
            
            $adress_id=$addressrow->ID;
            
    }
        
     $parent_grup_data=array(
                    'GroupName'=>$parent_company_name,
                    'Description'=>$parent_company_desc,
                    'CompanyID'=>$CompanyID,
                    'AddressID'=>$adress_id,
                    'DateTime'=>date('Y-m-d H:i:s'),
                    'AttachedLogo'=>$Logo,
                    'AlignLogo'=>$LogoAlign,
                    'EntityID'=>$EntityMasterID,
                    'MyParentComment'=>$comment
     );
     $this->db->where('ID', $PID);
     $ret=$this->db->update('udt_ParentGroupMaster', $parent_grup_data);
        
     $RowStatus=2;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyParentMaster_H (ParentGroupID,ParentEntityName,ParentDescription,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCode,WebAddress,AttachedLogo,MyParentComment,ActiveFlag,RowStatus,UserID,EntityID,CreatedDate)
		select ID,GroupName,Description,CompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,MyParentComment,ActiveFlag,'".$RowStatus."',CreatedBy, EntityID, DateTime
		from cops_admin.udt_ParentGroupMaster where CompanyID='".$CompanyID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where CompanyID='".$CompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where CompanyID='".$CompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where CompanyID='".$CompanyID."'"
    );
        
    return $ret;
        
}
public function addParentMasterData($Company_ID,$flg)
{
    extract($this->input->post());
        
    $document=$_FILES['Logo'];
        
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo=$oldfile;
    }
        
        
    $cnt1=count($OthParentCompID);
        
    for($i=0; $i<$cnt1; $i++){
        $oth_data=array(
        'IDNumber'=>$old_oth_comid[$i],
        'Location'=>$old_com_location[$i],
        'OtherDescription'=>$old_comp_desc[$i],
        'CompanyID'=>$Company_ID
        );
        $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
    }
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$Company_ID
        );
        $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
    }
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
        'EmailID'=>$emailid[$i],
        'EmailDescription'=>$email_desc[$i],
        'CompanyID'=>$Company_ID
        );
        $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
    }
        
    $cntem1=count($ParentEmailID);
    for($i=0; $i<$cntem1; $i++){
        if($i==0) {
            $Email=$old_emailid[$i];
        }
        $eml_data=array(
                        'EmailID'=>$old_emailid[$i],
                        'EmailDescription'=>$old_email_desc[$i],
                        'CompanyID'=>$Company_ID
                    );
        $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
    }
        
    $cntel=count($type);
        
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                        'TelephoneType'=>$type[$i],
                        'TeleCountryCode'=>$contrycode[$i],
                        'TeleAreaCode'=>$areacode[$i],
                        'TeleNumber'=>$tele_number[$i],
                        'CompanyID'=>$Company_ID
                    );
        $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
    }
        
    $cntel1=count($ParentTelephoneID);
        
    for($i=0; $i<$cntel1; $i++){
        if($i==0 ) {
            $telephone1=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } 
            
        $tel_data=array(
                        'TelephoneType'=>$old_type[$i],
                        'TeleCountryCode'=>$old_contrycode[$i],
                        'TeleAreaCode'=>$old_areacode[$i],
                        'TeleNumber'=>$old_tele_number[$i],
                        'CompanyID'=>$Company_ID
                    );
        $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
    }
        
    $addr_data=array(
                'Address1'=>$address1,
                'Address2'=>$address2,
                'Address3'=>$address3,
                'Address4'=>$address4,
                'Telephone1'=>$telephone1,
                'Telephone2'=>$telephone2,
                'Email'=>$Email,
                'City'=>$city,
                'ZipCode'=>$pin_code,
                'WebAddress'=>$web_address,
                'CountryID'=>$countryid,
                'ActiveFlag'=>'1',
                'ParentCompanyID'=>$Company_ID
    );
    if($stateid) {
        $addr_data['StateID']=$stateid;
    }            
    $this->db->insert('udt_AddressMaster', $addr_data);
        
    $this->db->select('*');
    $this->db->from('udt_AddressMaster');
    $this->db->where('ParentCompanyID', $Company_ID);
    $query=$this->db->get();
    $addressrow=$query->row();
        
    $adress_id=$addressrow->ID;
        
    if($flg==1) {
        $EMID=$EntityID;
    } else if($flg==2) {
        $EMID=$EntityMasterID;
    } 
        
    $parent_grup_data=array(
    'GroupName'=>$parent_company_name,
    'Description'=>$parent_company_desc,
    'ActiveFlag'=>'1',
    'AddressID'=>$adress_id,
    'DateTime'=>date('Y-m-d H:i:s'),
    'IsConversionRecord'=>'',
    'CompanyID'=>$Company_ID,
    'AttachedLogo'=>$Logo,
    'CreatedBy'=>$UserID,
    'EntityID'=>$EMID,
    'MyParentComment'=>$comment
                );    
    $ret=$this->db->insert('udt_ParentGroupMaster', $parent_grup_data);
        
    $RowStatus=4;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyParentMaster_H (ParentGroupID,ParentEntityName,ParentDescription,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCode,WebAddress,AttachedLogo,MyParentComment,ActiveFlag,RowStatus,UserID,EntityID,CreatedDate)
		select ID,GroupName,Description,CompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,MyParentComment,ActiveFlag,'".$RowStatus."',CreatedBy, EntityID, DateTime
		from cops_admin.udt_ParentGroupMaster where CompanyID='".$Company_ID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where CompanyID='".$Company_ID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where CompanyID='".$Company_ID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where CompanyID='".$Company_ID."'"
    );
    return $ret;
}
    
public function getParentMasterRow()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->where('udt_ParentGroupMaster.ID', $PID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getEntityMasterRow()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('udt_EntityMaster.ID', $EID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function deleteOtherComIDs()
{
    $OthParentCompID=$this->input->post('OthParentCompID');
    $RowStatus=3;
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where OthParentCompID='".$OthParentCompID."'"
    );
        
    $this->db->where('OthParentCompID', $OthParentCompID);
    return $this->db->delete('udt_AU_OtherParentCompanyID');
        
}

public function deleteCompanyEmail()
{
    $ParentEmailID=$this->input->post('ParentEmailID');
    $RowStatus=3;
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus) 
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where ParentEmailID='".$ParentEmailID."'"
    );
            
    $this->db->where('ParentEmailID', $ParentEmailID);
    return $this->db->delete('udt_AU_ParentEmailDetails');
        
}

public function deleteCompanyTelephone()
{
    $ParentTelephoneID=$this->input->post('ParentTelephoneID');
    $RowStatus=3;
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where ParentTelephoneID='".$ParentTelephoneID."'"
    );
        
    $this->db->where('ParentTelephoneID', $ParentTelephoneID);
    return $this->db->delete('udt_AU_ParentTelephoneDetails');
        
}
    
public function getParentCompanyLogoName()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->where('udt_ParentGroupMaster.ID', $id);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function deleteCompanyLogo()
{
    $id=$this->input->post('id');
    $data=array('AttachedLogo'=>'');
    $this->db->where('udt_ParentGroupMaster.ID', $id);
    return $this->db->update('udt_ParentGroupMaster', $data);
}
    
public function deleteParentEntity()
{
    $PID=$this->input->post('PID');
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('udt_EntityMaster.ParentGroupID', $PID);
    $query1=$this->db->get();
    $rslt1=$query1->row();
    if($rslt1) {
        return 2;
    }
        
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_ParentGroupMaster.AddressID');
    $this->db->where('udt_ParentGroupMaster.ID', $PID);
    $query=$this->db->get();
    $rslt=$query->row();
    $CompanyID=$rslt->CompanyID;
    $address1=$rslt->Address1;
    $address2=$rslt->Address2;
    $address3=$rslt->Address3;
    $address4=$rslt->Address4;
    $countryid=$rslt->CountryID;
    $stateid=$rslt->StateID;
        
    $RowStatus=3;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyParentMaster_H (ParentGroupID,ParentEntityName,ParentDescription,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,WebAddress,AttachedLogo,MyParentComment,ActiveFlag,RowStatus,UserID,EntityID,CreatedDate)
		select ID,GroupName,Description,CompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."',WebAddress,AttachedLogo,MyParentComment,ActiveFlag,'".$RowStatus."',CreatedBy, EntityID, DateTime
		from cops_admin.udt_ParentGroupMaster where CompanyID='".$CompanyID."'"
    );
        
    $this->db->where('ID', $PID);
    $ret=$this->db->delete('udt_ParentGroupMaster');
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where CompanyID='".$CompanyID."'"
    );
        
    $this->db->where('CompanyID', $CompanyID);
    $this->db->delete('udt_AU_OtherParentCompanyID');
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where CompanyID='".$CompanyID."'"
    );
        
    $this->db->where('CompanyID', $CompanyID);
    $this->db->delete('udt_AU_ParentEmailDetails');
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where CompanyID='".$CompanyID."'"
    );
        
        
    $this->db->where('CompanyID', $CompanyID);
    $this->db->delete('udt_AU_ParentTelephoneDetails');
    if($ret) {
        return 1;
    } else {
        return 0;
    }
        
        
}
    
public function checkEntityUniqueID($AssociateCompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('AssociateCompanyID', $AssociateCompanyID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function fillParentCompanyAddress()
{
    $parent_company_id=$this->input->post('parent_company_id');
        
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->where('ID', $parent_company_id);
    $query=$this->db->get();
    $ParentGroupRow= $query->row();
        
    $AddressID=$ParentGroupRow->AddressID;
        
    $this->db->select('udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.CountryID, udt_AddressMaster.City, udt_AddressMaster.ZipCode, udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description, udt_AddressMaster.StateID, udt_StateMaster.Code as S_Code, udt_StateMaster.Description as S_Description');
    $this->db->from('udt_AddressMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_AddressMaster.ID', $AddressID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function saveTermsConditions()
{
    extract($this->input->post());
    $term_condition_pdf=$_FILES['term_condition_pdf'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
    $ext=getExtension($term_condition_pdf['name']);
    $pdf_name='';
        
    if($ext=='pdf' || $ext=='PDF') {
        $pdf_name=rand(100000, 999999).$term_condition_pdf['name'];
        $tmp=$term_condition_pdf['tmp_name'];
        $actual_image_name = 'TopMarx/'.$pdf_name;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    }
        
    $term_condition_text=str_replace("'", "''", $term_condition_text);
        
    $data=array(
    'EntityID'=>$EntityMasterID,
    'CreatedBy'=>$UserID,
    'CreatedDateTime'=>date('Y-m-d H:i:s'),
    'Application'=>$application,
    'UniqueID'=>rand(100, 999).rand(100, 999).rand(100, 999),
    'Version'=>'1.0',
    'Status'=>$status,
    'Comments'=>$comment,
    'FileIn'=>$file_in,
    'TermText'=>$term_condition_text,
    'TermPdf'=>$pdf_name,
    'update_for'=>1,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    );
    return $this->db->insert('AUM_TermCondition', $data);
        
}
    
public function getTermsConditions()
{
    
    if($this->input->get()) {
        $EntityID=$this->input->get('EntityID');
        $application=$this->input->get('application');
        $status=$this->input->get('status');
    } else if($this->input->post()) {
        $EntityID=$this->input->post('EntityID');
    }
        
    $this->db->select('*');
    $this->db->from('AUM_TermCondition');
        
    if($EntityID) {
        $this->db->where('EntityID', $EntityID);
    }
        
    if($application) {
        $this->db->where('Application', $application);
    }
    if($status) {
        $this->db->where('Status', $status);
    }
        
    $this->db->order_by('TCID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocTypeById($DocumentType)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_master');
    $this->db->where('DocumentTypeID', $DocumentType);
    $query=$this->db->get();
    return $query->row();
}
    
public function deleteTermsConditions()
{
    extract($this->input->post());
    $id1=trim($id, ",");
        
    $this->db->select('*');
    $this->db->from('AUM_TermCondition');
    $this->db->where('TCID', $id1);
    $query=$this->db->get();
    $rslt=$query->row();
        
    if($rslt->link==1) {
        return 1;
    } else {
        $this->db->where('TCID', $id1);
        $this->db->delete('AUM_TermCondition');
        return 2;
    }
}    

public function getTermsConditionsById()
{
    $TCID=$this->input->post('TCID');
    $this->db->select('AUM_TermCondition.*, udt_EntityMaster.EntityName, ');
    $this->db->from('AUM_TermCondition');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=AUM_TermCondition.EntityID');
    $this->db->where('TCID', $TCID);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateTermsConditions()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('AUM_TermCondition');
    $this->db->where('TCID', $TCID);
    $query=$this->db->get();
    $rslt=$query->row();
        
    if($status==3) {
        $sts=array('status'=>4);
        $this->db->where('EntityID', $EntityMasterID);
        $this->db->where('Application', $rslt->Application);
        if($rslt->Application==$application) {
            $this->db->update('AUM_TermCondition', $sts);
        }
        if($update_for==1) {
            $udata=array('TermCheckFlag'=>0,'PdfDownloadFlag'=>0);
            $this->db->where('EntityID', $EntityMasterID);
            $this->db->update('udt_UserMaster', $udata);
            
            $idata=array('TermStatus'=>0);
            $this->db->where('RecordOwner', $EntityMasterID);
            $this->db->update('udt_AUM_Invitee_Master', $idata);
        } else if($update_for==2) {
            $idata=array('TermStatus'=>0);
            $this->db->where('RecordOwner', $EntityMasterID);
            $this->db->update('udt_AUM_Invitee_Master', $idata);    
        }  else if($update_for==3) {
            $udata=array('TermCheckFlag'=>0,'PdfDownloadFlag'=>0);
            $this->db->where('EntityID', $EntityMasterID);
            $this->db->update('udt_UserMaster', $udata);    
        }
    }
        
    $term_condition_pdf=$_FILES['term_condition_pdf'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
    $ext=getExtension($term_condition_pdf['name']);
    $pdf_name='';
        
    if($ext=='pdf' || $ext=='PDF') {
        $pdf_name=rand(100000, 999999).$term_condition_pdf['name'];
        $tmp=$term_condition_pdf['tmp_name'];
        $actual_image_name = 'TopMarx/'.$pdf_name;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    }
        
    $data['EntityID']=$EntityMasterID;
    $data['UpdateBy']=$UserID;
    $data['CreatedBy']=$UserID;
    $data['UpdateDateTime']=date('Y-m-d H:i:s');
    $data['CreatedDateTime']=date('Y-m-d H:i:s');
            
    $data['Application']=$application;
    $data['UniqueID']=rand(100, 999).rand(100, 999).rand(100, 999);
    if($rslt->link==1) {
        $data['Version']=$rslt->Version+0.1;
    } else {
        $data['Version']=$rslt->Version;    
    }
    $data['Status']=$status;
    $data['Comments']=$comment;
    $data['FileIn']=$file_in;
    $data['update_for']=$update_for;
            
    $term_condition_text=str_replace("'", "''", $term_condition_text);
            
    if($file_in==1) {
        $data['TermText']=$term_condition_text;
    }
    if($file_in==2 && $ext=='pdf') {
        $data['TermPdf']=$pdf_name;
    }
    $data['UserID']=$UserID;
    $data['UserDate']=date('Y-m-d H:i:s');
    if($rslt->link==1 || $rslt->Application!=$application) {    
        return $this->db->insert('AUM_TermCondition', $data);
    } else {
        $this->db->where('TCID', $TCID);
        return $this->db->update('AUM_TermCondition', $data);
    }
}
    
public function downloadTermCondition()
{
    extract($this->input->get());
    $data=array('PdfDownloadFlag'=>1);
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $this->db->update('udt_UserMaster', $data);
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $query=$this->db->get();
    return $query->row();
}
    
public function getTermConditionByID($EntityID)
{
    $this->db->select('*');
    $this->db->from('AUM_TermCondition');
    $this->db->where('EntityID', $EntityID);
    $this->db->where('Status', 3);
    $query=$this->db->get();
    return $query->row();
}
    
public function SaveMyEntityMaster()
{ 	
    extract($this->input->post());
    $document=$_FILES['Logo'];
        
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo='';
    }
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$AssociateCompanyID
        );
        $this->db->insert('udt_AU_OtherEntityCompanyID', $oth_data);
    }
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
                        'EmailID'=>$emailid[$i],
                        'EmailDescription'=>$email_desc[$i],
                        'CompanyID'=>$AssociateCompanyID
                    );
        $this->db->insert('udt_AU_EntityEmailDetails', $eml_data);
    }
        
    $cntel=count($type);
        
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                    'TelephoneType'=>$type[$i],
                    'TeleCountryCode'=>$contrycode[$i],
                    'TeleAreaCode'=>$areacode[$i],
                    'TeleNumber'=>$tele_number[$i],
                    'CompanyID'=>$AssociateCompanyID
        );
        $this->db->insert('udt_AU_EntityTelephoneDetails', $tel_data);
    }
        
    $addr_data=array(
                'Address1'=>$address1,
                'Address2'=>$address2,
                'Address3'=>$address3,
                'Address4'=>$address4,
                'City'=>$city,
                'Telephone1'=>$telephone1,
                'Telephone2'=>$telephone2,
                'Fax1'=>'',
                'Fax2'=>'',
                'Email'=>$Email,
                'WebAddress'=>$web_address,
                'ActiveFlag'=>'1',
                'ZipCode'=>$pin_code,
                'EntityCompanyID'=>$AssociateCompanyID
    );
            
    if($countryid) {
        $addr_data['CountryID']=$countryid;
    }    
    if($stateid) {
        $addr_data['StateID']=$stateid;
    }
    $this->db->insert('udt_AddressMaster', $addr_data);
        
    $this->db->select('*');
    $this->db->from('udt_AddressMaster');
    $this->db->where('EntityCompanyID', $AssociateCompanyID);
    $query=$this->db->get();
    $addressrow=$query->row();
        
    $AddressID=$addressrow->ID;
        
    $entity_data=array(
                'EntityName'=>$associated_company_name,
                'Description'=>$associate_company_desc,
                'ParentFlag'=>$ParentFlag,
                'ParentGroupID'=>$parent_company_id,
                'IsParentAddress'=>$ParentAddressFlag,
                'AddressID'=>$AddressID,
                'DateTime'=>date('Y-m-d H:i:s'),
                'ActiveFlag'=>'1',
                'IsConversionRecord'=>'',
                'AssociateCompanyID'=>$AssociateCompanyID,
                'AttachedLogo'=>$Logo,
                'AlignLogo'=>$LogoAlign,
                'Comment'=>$comment,
                'CreatedBy'=>$UserID,
                'InviteeEntityFlg'=>$InviteeEntityFlg,
                'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                'CargoQuoteFormFlg'=>$CargoQuoteFormFlg,
                'DisablePrompt'=>$DisablePrompt,
                'ParentEntityID'=>1,
                'EntityOwner'=>$EntityMasterID
    );
        
    $ret=$this->db->insert('udt_EntityMaster', $entity_data);
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('AssociateCompanyID', $AssociateCompanyID);
    $query=$this->db->get();
    $entityrow=$query->row();
    $EntityID=$entityrow->ID;
        
    $cnentitytype=count($entity_type);
    for($i=0; $i<$cnentitytype; $i++){
        if($entity_type[$i]=='5' || $entity_type[$i]=='6') {
            if($ret) {
                $ret=$EntityID;
            }
        }
        $en_data=array(
        'EntityMasterID'=>$EntityID,
        'EntityTypeID'=>$entity_type[$i],
        'ActiveFlag'=>'1'
                );
        $this->db->insert('udt_Mapping_EntityTypes', $en_data);
    }
        
    $cngroup_name=count($group_name);
    for($i=0; $i<$cngroup_name; $i++){
        $gr_data=array(
        'BussinessGroupName'=>$group_name[$i],
        'BussinessGroupEmail'=>$email[$i],
        'EntityID'=>$EntityID
        );
        $this->db->insert('udt_AU_BussinessGroup', $gr_data);
    }
        
    $RowStatus=1;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyEntityMaster_H (EntityID,EntityName,EntityDescription,IsParentAddress,ParentGroupID,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCode,WebAddress,AttachedLogo,MyEntityComment,ActiveFlag,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,RowStatus,UserID,CreatedDate)
		select ID,EntityName,Description,IsParentAddress,ParentGroupID,AssociateCompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,Comment,ActiveFlag,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,'".$RowStatus."',CreatedBy,DateTime
		from cops_admin.udt_EntityMaster where AssociateCompanyID='".$AssociateCompanyID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherEntityCompanyID_H (OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherEntityCompanyID where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityEmailDetails_H (EntityEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select EntityEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityEmailDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityTelephoneDetails_H (EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus) select EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityTelephoneDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query5 = $this->db->query(
        "insert into cops_admin.udt_AU_BussinessGroup_H (BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,RowStatus) 
		select BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,'".$RowStatus."'
		from cops_admin.udt_AU_BussinessGroup where EntityID='".$EntityID."'"
    );
        
    $query6 = $this->db->query(
        "insert into cops_admin.udt_AU_Mapping_EntityTypes_H (EntityMasterID,EntityTypeID,ActiveFlag,RowStatus) 
		select EntityMasterID,EntityTypeID,ActiveFlag,'".$RowStatus."'
		from cops_admin.udt_Mapping_EntityTypes where EntityMasterID='".$EntityID."'"
    );
        
    return $ret;
        
}
    
public function getEntityMasterFixedData($rem)
{
    $associatedentityname=$this->input->get('associatedentityname');
    $countryid=$this->input->get('countryid');
        
    $this->db->select('TOP '.$rem.' E.ID as EID, udt_ParentGroupMaster.GroupName, E.EntityName, E.Description as EntityDescription,E.DateTime, E.EntityOwner, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.CountryID');
    $this->db->from('udt_EntityMaster as E');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=E.ParentGroupID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=E.AddressID', 'Left');
    $this->db->where('E.CreatedBy', '1');
    if($associatedentityname !='') {
        $this->db->where('E.EntityName', $associatedentityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('E.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}

public function getEntityMasterFixedAppendData()
{
    $associatedentityname=$this->input->get('associatedentityname');
    $countryid=$this->input->get('countryid');
        
    $this->db->select(' E.ID as EID, udt_ParentGroupMaster.GroupName, E.EntityName, E.Description as EntityDescription,E.DateTime, E.EntityOwner, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.CountryID');
    $this->db->from('udt_EntityMaster as E');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=E.ParentGroupID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=E.AddressID', 'Left');
    $this->db->where('E.CreatedBy', '1');
    if($associatedentityname !='') {
        $this->db->where('E.EntityName', $associatedentityname);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('E.ID', 'DESC');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getCountryById($CountryID)
{
    $this->db->select('udt_CountryMaster.Description as CountryDescription');
    $this->db->from('udt_CountryMaster');
    $this->db->where('udt_CountryMaster.ID', $CountryID);
    $query=$this->db->get();
    return $query->row();
        
}

public function getEntityMasterMyCustomData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $associatedentityname=$this->input->get('associatedentityname');
    $countryid=$this->input->get('countryid');
    $ByEnvitee=$this->input->get('ByEnvitee');
    $ids=array('1',$EntityID);
    $this->db->select('TOP 50 E.ID as EID, udt_ParentGroupMaster.GroupName, E.EntityName, E.InviteeEntityFlg, E.Description as EntityDescription,E.DateTime, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, E.EntityOwner, udt_AddressMaster.CountryID, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4');
    $this->db->from('udt_EntityMaster as E');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=E.ParentGroupID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=E.AddressID', 'Left');
        
    if($UserID !='1' && $EntityID !='') {
        $this->db->where_in('E.EntityOwner', $ids);
    } else if($UserID =='1' && $EntityID !='') {
        $this->db->where_in('E.EntityOwner', $EntityID);
    }
    if($associatedentityname !='') {
        $this->db->like('E.EntityName', $associatedentityname);
    }
    if($ByEnvitee==1 || $ByEnvitee==0) {
        $this->db->where('E.InviteeEntityFlg', $ByEnvitee);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('E.DateTime', 'desc');
    $query=$this->db->get();
        
    return $query->result();
        
}
    
public function getEntityMasterMyCustomAppendData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $associatedentityname=$this->input->get('associatedentityname');
    $countryid=$this->input->get('countryid');
    $ByEnvitee=$this->input->get('ByEnvitee');
    $ids=array('1',$EntityID);
    $this->db->select('E.ID as EID, udt_ParentGroupMaster.GroupName, E.EntityName, E.InviteeEntityFlg, E.Description as EntityDescription,E.DateTime, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, E.EntityOwner, udt_AddressMaster.CountryID, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4');
    $this->db->from('udt_EntityMaster as E');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=E.ParentGroupID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=E.AddressID', 'Left');
        
    if($UserID !='1' && $EntityID !='') {
        $this->db->where_in('E.EntityOwner', $ids);
    } else if($UserID =='1' && $EntityID !='') {
        $this->db->where_in('E.EntityOwner', $EntityID);
    }
    if($associatedentityname !='') {
        $this->db->like('E.EntityName', $associatedentityname);
    }
    if($ByEnvitee==1 || $ByEnvitee==0) {
        $this->db->where('E.InviteeEntityFlg', $ByEnvitee);
    }
    if($countryid) {
        $this->db->where('udt_AddressMaster.CountryID', $countryid);
    }
    $this->db->order_by('E.DateTime', 'desc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getMyEntityMasterData()
{
    $EID=$this->input->post('EID');
    $this->db->select('E.EntityName as AssociateEntityName,E.DisablePrompt, E.EntityOwner, E.InviteeEntityFlg, E2.EntityName, E.ParentFlag, E.AlignLogo, E.Description as EntityDescription,E.AddressID, E.IsParentAddress, E.ParentGroupID, udt_ParentGroupMaster.GroupName, E.AssociateCompanyID, E.AttachedLogo, udt_CountryMaster.Description as CountryDescription, udt_CountryMaster.Code as CountryCode, udt_StateMaster.Description as StateDescription, udt_StateMaster.Code as StateCode, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.CountryID, udt_AddressMaster.StateID, udt_AddressMaster.City, udt_AddressMaster.ZipCode, udt_AddressMaster.WebAddress, E.Comment, E.FixtureCompleteProcess, E.CargoQuoteFormFlg');
    $this->db->from('udt_EntityMaster as E');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=E.ParentGroupID', 'Left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=E.AddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'Left');
    $this->db->join('udt_EntityMaster as E2', 'E2.ID=E.EntityOwner', 'Left');
    $this->db->where('E.ID', $EID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getMyEntityOtherIDs($CompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_OtherEntityCompanyID');
    $this->db->where('udt_AU_OtherEntityCompanyID.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}

public function getMyEntityEmail($CompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_EntityEmailDetails');
    $this->db->where('udt_AU_EntityEmailDetails.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}

public function getMyEntityTelephone($CompanyID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_EntityTelephoneDetails');
    $this->db->where('udt_AU_EntityTelephoneDetails.CompanyID', $CompanyID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getMyBussinessUnit()
{
    $EID=$this->input->post('EID');
    $this->db->select('*');
    $this->db->from('udt_AU_BussinessGroup');
    $this->db->where('udt_AU_BussinessGroup.EntityID', $EID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getMyEntityType()
{
    $EID=$this->input->post('EID');
    $this->db->select('udt_EntityType.Code, udt_EntityType.Description, udt_Mapping_EntityTypes.EntityMasterID,udt_Mapping_EntityTypes.EntityTypeID');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_Mapping_EntityTypes.EntityTypeID');
    $this->db->where('udt_Mapping_EntityTypes.EntityMasterID', $EID);
    $query=$this->db->get();
    return $query->result();
}
    
public function deleteOtherEntityComIDs()
{
    $OthEntityCompID=$this->input->post('OthEntityCompID');
    $RowStatus=3;
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherEntityCompanyID_H (OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherEntityCompanyID where OthEntityCompID='".$OthEntityCompID."'"
    );
        
    $this->db->where('OthEntityCompID', $OthEntityCompID);
    return $this->db->delete('udt_AU_OtherEntityCompanyID');
        
}

public function deleteAssociateComEmail()
{
    $EntityEmailID=$this->input->post('EntityEmailID');
    $RowStatus=3;
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityEmailDetails_H (EntityEmailID,EmailID,EmailDescription,CompanyID,RowStatus) 
		select EntityEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityEmailDetails where EntityEmailID='".$EntityEmailID."'"
    );
            
    $this->db->where('EntityEmailID', $EntityEmailID);
    return $this->db->delete('udt_AU_EntityEmailDetails');
        
}

public function deleteAssociateComTelephone()
{
    $EntityTelephoneID=$this->input->post('EntityTelephoneID');
    $RowStatus=3;
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityTelephoneDetails_H (EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityTelephoneDetails where EntityTelephoneID='".$EntityTelephoneID."'"
    );
        
    $this->db->where('EntityTelephoneID', $EntityTelephoneID);
    return $this->db->delete('udt_AU_EntityTelephoneDetails');
        
}
    
public function deleteAssociateEntityType()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
    $EntityTypeID=$this->input->post('EntityTypeID');
    $RowStatus=3;
        
    $query6 = $this->db->query(
        "insert into cops_admin.udt_AU_Mapping_EntityTypes_H (EntityMasterID,EntityTypeID,ActiveFlag,RowStatus) 
		select EntityMasterID,EntityTypeID,ActiveFlag,'".$RowStatus."'
		from cops_admin.udt_Mapping_EntityTypes where EntityMasterID='".$EntityMasterID."' and EntityTypeID='".$EntityTypeID."'"
    );
        
    $this->db->where('EntityMasterID', $EntityMasterID);
    $this->db->where('EntityTypeID', $EntityTypeID);
    return $this->db->delete('udt_Mapping_EntityTypes');
        
}
    
    
public function deleteAssociateComBusiness()
{
    $BussinessGroupID=$this->input->post('BussinessGroupID');
    $RowStatus=3;
        
    $query5 = $this->db->query(
        "insert into cops_admin.udt_AU_BussinessGroup_H (BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,RowStatus) 
		select BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,'".$RowStatus."'
		from cops_admin.udt_AU_BussinessGroup where BussinessGroupID='".$BussinessGroupID."'"
    );
        
    $this->db->where('BussinessGroupID', $BussinessGroupID);
    return $this->db->delete('udt_AU_BussinessGroup');
        
}
    
    
public function getEntityCompanyLogoName()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('udt_EntityMaster.ID', $id);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function deleteEntityLogo()
{
    $id=$this->input->post('id');
    $data=array('AttachedLogo'=>'');
    $this->db->where('udt_EntityMaster.ID', $id);
    return $this->db->update('udt_EntityMaster', $data);
}
    
public function updateMyEntityMaster()
{
    extract($this->input->post());
    $document=$_FILES['Logo'];
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo=$oldfile;
    }
        
    $this->db->where('CompanyID', $AssociateCompanyID);
    $this->db->delete('udt_AU_OtherEntityCompanyID');
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$AssociateCompanyID
        );
        $this->db->insert('udt_AU_OtherEntityCompanyID', $oth_data);
    }
        
    $this->db->where('CompanyID', $AssociateCompanyID);
    $this->db->delete('udt_AU_EntityEmailDetails');
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
                        'EmailID'=>$emailid[$i],
                        'EmailDescription'=>$email_desc[$i],
                        'CompanyID'=>$AssociateCompanyID
                    );
        $this->db->insert('udt_AU_EntityEmailDetails', $eml_data);
    }
        
    $this->db->where('CompanyID', $AssociateCompanyID);
    $this->db->delete('udt_AU_EntityTelephoneDetails');
        
    $cntel=count($type);
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                        'TelephoneType'=>$type[$i],
                        'TeleCountryCode'=>$contrycode[$i],
                        'TeleAreaCode'=>$areacode[$i],
                        'TeleNumber'=>$tele_number[$i],
                        'CompanyID'=>$AssociateCompanyID
                    );
        $this->db->insert('udt_AU_EntityTelephoneDetails', $tel_data);
    }
        
    if($AddressID) {
        $addr_data=array(
        'Address1'=>$address1,
        'Address2'=>$address2,
        'Address3'=>$address3,
        'Address4'=>$address4,
        'City'=>$city,
        'ZipCode'=>$pin_code,
        'Telephone1'=>$telephone1,
        'Telephone2'=>$telephone2,
        'Email'=>$Email,
        'WebAddress'=>$web_address,
        'EntityCompanyID'=>$AssociateCompanyID
        );
        if($countryid) {
            $addr_data['CountryID']=$countryid;
        } else {
            $addr_data['CountryID']=null;
        } 
            
        if($stateid) {
            $addr_data['StateID']=$stateid;
        } else {
            $addr_data['StateID']=null;
        }                
            $this->db->where('ID', $AddressID);    
            $this->db->update('udt_AddressMaster', $addr_data);
            $adresID=$AddressID;
    } else {
         $addr_data=array(
        'Address1'=>$address1,
        'Address2'=>$address2,
        'Address3'=>$address3,
        'Address4'=>$address4,
        'City'=>$city,
        'Telephone1'=>$telephone1,
        'Telephone2'=>$telephone2,
        'Fax1'=>'',
        'Fax2'=>'',
        'Email'=>$Email,
        'WebAddress'=>$web_address,
        'ActiveFlag'=>'1',
        'ZipCode'=>$pin_code,
        'EntityCompanyID'=>$AssociateCompanyID
          );
         if($countryid) {
             $addr_data['CountryID']=$countryid;
         } else {
             $addr_data['CountryID']=null;
         } 
                
         if($stateid) {
             $addr_data['StateID']=$stateid;
         }
            $this->db->insert('udt_AddressMaster', $addr_data);
        
            $this->db->select('*');
            $this->db->from('udt_AddressMaster');
            $this->db->where('EntityCompanyID', $AssociateCompanyID);
            $query=$this->db->get();
            $addressrow=$query->row();
            
            $adresID=$addressrow->ID;
    }
        
     $entity_data=array(
                    'EntityName'=>$associated_company_name,
                    'Description'=>$associate_company_desc,
                    'ParentGroupID'=>$parent_company_id,
                    'IsParentAddress'=>$ParentAddressFlag,
                    'AddressID'=>$adresID,
                    'DateTime'=>date('Y-m-d H:i:s'),
                    'AssociateCompanyID'=>$AssociateCompanyID,
                    'ParentFlag'=>$ParentFlag,
                    'AttachedLogo'=>$Logo,
                    'AlignLogo'=>$LogoAlign,
                    'Comment'=>$comment,
                    'CreatedBy'=>$UserID,
                    'InviteeEntityFlg'=>$InviteeEntityFlg,
                    'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                    'CargoQuoteFormFlg'=>$CargoQuoteFormFlg,
                    'DisablePrompt'=>$DisablePrompt,
                    'EntityOwner'=>$EntityMasterID
     );
     $this->db->where('udt_EntityMaster.ID', $EID);
     $ret=$this->db->update('udt_EntityMaster', $entity_data);
        
        
     $this->db->where('EntityMasterID', $EID);
     $this->db->delete('udt_Mapping_EntityTypes');
        
     $cnentitytype=count($entity_type);
     for($i=0; $i<$cnentitytype; $i++){
         $en_data['EntityMasterID']=$EID;
         $en_data['EntityTypeID']=$entity_type[$i];
         $en_data['ActiveFlag']=1;
            
         $this->db->insert('udt_Mapping_EntityTypes', $en_data);
     }
        
     $this->db->where('EntityID', $EID);
     $this->db->delete('udt_AU_BussinessGroup');
        
     $cngroup_name=count($group_name);
     for($i=0; $i<$cngroup_name; $i++){
         $gr_data=array(
         'BussinessGroupName'=>$group_name[$i],
         'BussinessGroupEmail'=>$email[$i],
         'EntityID'=>$EID
         );
         $this->db->insert('udt_AU_BussinessGroup', $gr_data);
     }
        
     $RowStatus=2;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyEntityMaster_H (EntityID,EntityName,EntityDescription,IsParentAddress,ParentGroupID,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCodeWebAddress,AttachedLogo,MyEntityComment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,ActiveFlag,RowStatus,UserID,CreatedDate)
		select ID,EntityName,Description,IsParentAddress,ParentGroupID,AssociateCompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,Comment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,ActiveFlag,'".$RowStatus."',CreatedBy,DateTime
		from cops_admin.udt_EntityMaster where AssociateCompanyID='".$AssociateCompanyID."'"
    );
        
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherEntityCompanyID_H (OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherEntityCompanyID where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityEmailDetails_H (EntityEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select EntityEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityEmailDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityTelephoneDetails_H (EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus) select EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityTelephoneDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query5 = $this->db->query(
        "insert into cops_admin.udt_AU_BussinessGroup_H (BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,RowStatus) 
		select BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,'".$RowStatus."'
		from cops_admin.udt_AU_BussinessGroup where EntityID='".$EID."'"
    );
        
    $query6 = $this->db->query(
        "insert into cops_admin.udt_AU_Mapping_EntityTypes_H (EntityMasterID,EntityTypeID,ActiveFlag,RowStatus) 
		select EntityMasterID,EntityTypeID,ActiveFlag,'".$RowStatus."'
		from cops_admin.udt_Mapping_EntityTypes where EntityMasterID='".$EID."'"
    );
        
    return $ret;
        
}
    
public function addEntityMasterData($Company_ID,$flg)
{
    extract($this->input->post());
        
    $document=$_FILES['Logo'];
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo=$oldfile;
    }
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$Company_ID
        );
        $this->db->insert('udt_AU_OtherEntityCompanyID', $oth_data);
    }
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
                        'EmailID'=>$emailid[$i],
                        'EmailDescription'=>$email_desc[$i],
                        'CompanyID'=>$Company_ID
                    );
        $this->db->insert('udt_AU_EntityEmailDetails', $eml_data);
    }
        
    $cntel=count($type);
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                        'TelephoneType'=>$type[$i],
                        'TeleCountryCode'=>$contrycode[$i],
                        'TeleAreaCode'=>$areacode[$i],
                        'TeleNumber'=>$tele_number[$i],
                        'CompanyID'=>$Company_ID
                    );
        $this->db->insert('udt_AU_EntityTelephoneDetails', $tel_data);
    }
        
    $addr_data=array(
                'Address1'=>$address1,
                'Address2'=>$address2,
                'Address3'=>$address3,
                'Address4'=>$address4,
                'City'=>$city,
                'Telephone1'=>$telephone1,
                'Telephone2'=>$telephone2,
                'Fax1'=>'',
                'Fax2'=>'',
                'Email'=>$Email,
                'WebAddress'=>$web_address,
                'ActiveFlag'=>'1',
                'StateID'=>$stateid,
                'ZipCode'=>$pin_code,
                'EntityCompanyID'=>$Company_ID
    );
            
    if($countryid) {
        $addr_data['CountryID']=$countryid;
    } else {
        $addr_data['CountryID']=null;
    }
    if($stateid) {
        $addr_data['StateID']=$stateid;
    } else {
        $addr_data['StateID']=null;
    }
        
    $this->db->insert('udt_AddressMaster', $addr_data);
    
    $this->db->select('*');
    $this->db->from('udt_AddressMaster');
    $this->db->where('EntityCompanyID', $Company_ID);
    $query=$this->db->get();
    $addressrow=$query->row();
        
    $adresID=$addressrow->ID;
        
    if($flg==1) {
        $EMID=$EntityID;
        $ParentEntityID=$EID;
    } else if($flg==2) {
        $EMID=$EntityMasterID;
        $ParentEntityID=1;
    } 
        
    $entity_data=array(
    'EntityName'=>$associated_company_name,
    'Description'=>$associate_company_desc,
    'ParentGroupID'=>$parent_company_id,
    'IsParentAddress'=>$ParentAddressFlag,
    'AddressID'=>$adresID,
    'DateTime'=>date('Y-m-d H:i:s'),
    'ActiveFlag'=>'1',
    'IsConversionRecord'=>'',
    'ParentFlag'=>$ParentFlag,
    'AssociateCompanyID'=>$Company_ID,
    'AttachedLogo'=>$Logo,
    'Comment'=>$comment,
    'CreatedBy'=>$UserID,
    'InviteeEntityFlg'=>$InviteeEntityFlg,
    'FixtureCompleteProcess'=>$FixtureCompleteProcess,
    'CargoQuoteFormFlg'=>$CargoQuoteFormFlg,
    'ParentEntityID'=>$ParentEntityID,
    'EntityOwner'=>$EMID
                );
        
    $ret=$this->db->insert('udt_EntityMaster', $entity_data);
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('AssociateCompanyID', $Company_ID);
    $query=$this->db->get();
    $entityrow=$query->row();
        
    if($entityrow) {
        $Entity_ID=$entityrow->ID;
    } else {
        $Entity_ID=0;
    }
                
    $cnentitytype=count($entity_type);
    for($i=0; $i<$cnentitytype; $i++) {
        $en_data=array(
        'EntityMasterID'=>$Entity_ID,
        'EntityTypeID'=>$entity_type[$i],
        'ActiveFlag'=>'1'
        );
        $this->db->insert('udt_Mapping_EntityTypes', $en_data);
    }
        
    $cngroup_name=count($group_name);
    for($i=0; $i<$cngroup_name; $i++){
        $gr_data=array(
        'BussinessGroupName'=>$group_name[$i],
        'BussinessGroupEmail'=>$email[$i],
        'EntityID'=>$Entity_ID
        );
        $this->db->insert('udt_AU_BussinessGroup', $gr_data);
    }
        
    $RowStatus=4;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyEntityMaster_H (EntityID,EntityName,EntityDescription,IsParentAddress,ParentGroupID,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,City,ZipCode,WebAddress,AttachedLogo,MyEntityComment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,ActiveFlag,RowStatus,UserID,CreatedDate)
		select ID,EntityName,Description,IsParentAddress,ParentGroupID,AssociateCompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$city."','".$pin_code."','".$web_address."',AttachedLogo,Comment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,CargoQuoteFormFlg,ActiveFlag,'".$RowStatus."',CreatedBy,DateTime
		from cops_admin.udt_EntityMaster where AssociateCompanyID='".$AssociateCompanyID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherEntityCompanyID_H (OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherEntityCompanyID where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityEmailDetails_H (EntityEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select EntityEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityEmailDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_EntityTelephoneDetails_H (EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus) select EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_EntityTelephoneDetails where CompanyID='".$AssociateCompanyID."'"
    );
        
    $query5 = $this->db->query(
        "insert into cops_admin.udt_AU_BussinessGroup_H (BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,RowStatus) 
		select BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,'".$RowStatus."'
		from cops_admin.udt_AU_BussinessGroup where EntityID='".$EID."'"
    );
        
    $query6 = $this->db->query(
        "insert into cops_admin.udt_AU_Mapping_EntityTypes_H (EntityMasterID,EntityTypeID,ActiveFlag,RowStatus) 
		select EntityMasterID,EntityTypeID,ActiveFlag,'".$RowStatus."'
		from cops_admin.udt_Mapping_EntityTypes where EntityMasterID='".$EID."'"
    );
        
    return $Entity_ID;
        
}
    
public function deleteAssociateEntity()
{
    $EID=$this->input->post('EID');
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_EntityMaster.AddressID');
    $this->db->where('udt_EntityMaster.ID', $EID);
    $query=$this->db->get();
    $rslt=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('udt_UserMaster.EntityID', $EID);
    $query=$this->db->get();
    $usr_row=$query->row();
    if($usr_row) {
        return 0;
    } else {
        $AssociateCompanyID=$rslt->AssociateCompanyID;
        $address1=$rslt->Address1;
        $address2=$rslt->Address2;
        $address3=$rslt->Address3;
        $address4=$rslt->Address4;
        $countryid=$rslt->CountryID;
        $stateid=$rslt->StateID;
            
        $RowStatus=3;
            
        $query1 = $this->db->query(
            "insert into cops_admin.udt_AU_MyEntityMaster_H (EntityID,EntityName,EntityDescription,IsParentAddress,ParentGroupID,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,WebAddress,AttachedLogo,MyEntityComment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,ActiveFlag,RowStatus,UserID,CreatedDate)
			select ID,EntityName,Description,IsParentAddress,ParentGroupID,AssociateCompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."',WebAddress,AttachedLogo,Comment,ParentFlag,InviteeEntityFlg,FixtureCompleteProcess,ActiveFlag,'".$RowStatus."',CreatedBy,DateTime
			from cops_admin.udt_EntityMaster where AssociateCompanyID='".$AssociateCompanyID."'"
        );
            
        $this->db->where('udt_EntityMaster.ID', $EID);
        $ret=$this->db->delete('udt_EntityMaster');
            
        $query2 = $this->db->query(
            "insert into cops_admin.udt_AU_OtherEntityCompanyID_H (OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
			select OthEntityCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
			from cops_admin.udt_AU_OtherEntityCompanyID where CompanyID='".$AssociateCompanyID."'"
        );
            
        $this->db->where('CompanyID', $AssociateCompanyID);
        $this->db->delete('udt_AU_OtherEntityCompanyID');
            
        $query3 = $this->db->query(
            "insert into cops_admin.udt_AU_EntityEmailDetails_H (EntityEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
			select EntityEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
			from cops_admin.udt_AU_EntityEmailDetails where CompanyID='".$AssociateCompanyID."'"
        );
            
        $this->db->where('CompanyID', $AssociateCompanyID);
        $this->db->delete('udt_AU_EntityEmailDetails');
            
        $query4 = $this->db->query(
            "insert into cops_admin.udt_AU_EntityTelephoneDetails_H (EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus) select EntityTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
			from cops_admin.udt_AU_EntityTelephoneDetails where CompanyID='".$AssociateCompanyID."'"
        );
            
        $this->db->where('CompanyID', $AssociateCompanyID);
        $this->db->delete('udt_AU_EntityTelephoneDetails');
            
        $query5 = $this->db->query(
            "insert into cops_admin.udt_AU_BussinessGroup_H (BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,RowStatus) 
			select BussinessGroupID,BussinessGroupName,BussinessGroupEmail,EntityID,'".$RowStatus."'
			from cops_admin.udt_AU_BussinessGroup where EntityID='".$EID."'"
        );
            
        $this->db->where('EntityID', $EID);
        $this->db->delete('udt_AU_BussinessGroup');
            
        $query6 = $this->db->query(
            "insert into cops_admin.udt_AU_Mapping_EntityTypes_H (EntityMasterID, EntityTypeID, ActiveFlag, RowStatus) 
			select EntityMasterID, EntityTypeID, ActiveFlag,'".$RowStatus."'
			from cops_admin.udt_Mapping_EntityTypes where EntityMasterID='".$EID."'"
        );
            
        $this->db->where('EntityMasterID', $EID);
        $this->db->delete('udt_Mapping_EntityTypes');
            
        return $ret;
    }
}
    
public function deleteMyUserMaster()
{
    $UID=$this->input->post('UID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('udt_UserMaster.ID', $UID);
    $query=$this->db->get();
    $rslt=$query->row();
            
    $this->db->where('ID', $UID);
    $ret=$this->db->delete('udt_UserMaster');
            
    if($ret) {
        $this->db->where('ID', $rslt->OfficialAddressID);
        $this->db->delete('udt_AddressMaster');
                
        $this->db->where('UserID', $UID);
        $this->db->delete('udt_AU_UserBussinessGroup');
                
        $this->db->where('UserID', $UID);
        $this->db->delete('udt_AU_UserEmails');
                
        $this->db->where('UserID', $UID);
        $this->db->delete('udt_AU_UserTelephones');
                
        $this->db->where('UserID', $UID);
        $this->db->delete('udt_AU_SignatureBlock');
    }
    return $ret;
}

    
public function getTermConditionAudit()
{
    $EntityID=$this->input->get('EntityID');
    $this->db->select('AUM_TermConditionReadDetail.*,AUM_TermCondition.FileIn,AUM_TermCondition.Application,AUM_TermCondition.Version,AUM_TermCondition.TCID,udt_AUM_DocumentType_Master.DocumentType,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('AUM_TermConditionReadDetail');
    $this->db->join('AUM_TermCondition', 'AUM_TermCondition.TCID=AUM_TermConditionReadDetail.TCID');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeId=AUM_TermCondition.DocumentType');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=AUM_TermConditionReadDetail.UserID');
    $this->db->order_by('AUM_TermConditionReadDetail.TCRDID', 'DESC');
    $this->db->where('AUM_TermConditionReadDetail.EntityID', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getTermsConditionsTextById($TCID)
{
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(TermText, '.$strlen.', 1000) as PTR');
        $this->db->from('AUM_TermCondition');
        $this->db->where('AUM_TermCondition.TCID', $TCID);
        $query=$this->db->get();
        $result=$query->row();
        if($result->PTR) {
            $content .=$result->PTR;
            $strlen = $strlen + strlen($result->PTR);
        }else{
            $temp=0;
        }
    }
    return $content;
}
    
public function getTermsConditionsTextPdfById($TCID,$FileIn)
{
    if($FileIn==1) {
        $content='';
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(TermText, '.$strlen.', 1000) as PTR');
            $this->db->from('AUM_TermCondition');
            $this->db->where('AUM_TermCondition.TCID', $TCID);
            $query=$this->db->get();
            $result=$query->row();
            if($result->PTR) {
                $content .=$result->PTR;
                $strlen = $strlen + strlen($result->PTR);
            }else{
                $temp=0;
            }
        }
        return $content;
    } else if($FileIn==2) {
        $this->db->select('*');
        $this->db->from('AUM_TermCondition');
        $this->db->where('AUM_TermCondition.TCID', $TCID);
        $query=$this->db->get();
        return $query->row();
    }
}
    
public function getBotificationById($NID)
{
    $this->db->select('udt_AUM_NotificationMaster.*,udt_UserMaster.ID,udt_UserMaster.FirstName,udt_UserMaster.LastName,CONVERT(VARCHAR(10),MessageDisplayFrom,105) as msgdf,CONVERT(VARCHAR(10),MessageDisplayTo,105) as msgdt, udt_UserMaster.EntityID, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_NotificationMaster.UserID', 'left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_NotificationMaster.RecordOwner', 'left');
    $this->db->where('NID', $NID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getNotificationDetailsById()
{
    $NID=$this->input->post('id');
    $this->db->select('udt_AUM_NotificationMaster.NotificationText,udt_AUM_NotificationMaster.ByUser,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_NotificationMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_NotificationMaster.RecordOwner');
    $this->db->where('NID', $NID);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateNotification()
{
    extract($this->input->post());
    $data=array(
                'MessageType'=>$MessageType,
                'NotificationType'=>$NotificationType,
                'SelectType'=>$type,
                'Describe'=>$discribe,
                'NotificationText'=>$notification_text,
                'MessageDisplayFrom'=>date('Y-m-d', strtotime($dateFrom)),
                'MessageDisplayTo'=>date('Y-m-d', strtotime($dateTo)),
                'Status'=>$status,
                'ByUser'=>$ByUser,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('NID', $NID);
    return $this->db->update('udt_AUM_NotificationMaster', $data);
}
    
public function getQoteSubmitionByEntityid()
{
    $AuctionId=$this->input->post('AuctionId');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionId', $AuctionId);
    $query1=$this->db->get();
    $rslt=$query1->row();
        
    $this->db->select('*');
    $this->db->from('AUM_TermCondition');
    $this->db->where('EntityID', $rslt->OwnerEntityID);
    $this->db->where('Status', 3);
    $this->db->where('Application', 2);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentTypeDataByID($DocumentTitle)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Document_master');
    $this->db->where('DMID', $DocumentTitle);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityBussinessGroup()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('udt_AU_BussinessGroup');
    $this->db->where('EntityID', $EntityID);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function allEntityRoles()
{
    $key=$this->input->post('key');
        
    $this->db->select('ID,Name,Description');
    $this->db->from('udt_RoleMaster');
    $this->db->like('Name', $key, 'after');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function fillEntityAddress()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $EntityMasterID);
    $query=$this->db->get();
    $EntityRow= $query->row();
        
    $AddressID=$EntityRow->AddressID;
        
    $this->db->select('udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3, udt_AddressMaster.Address4, udt_AddressMaster.CountryID, udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description, udt_AddressMaster.StateID, udt_StateMaster.Code as S_Code, udt_StateMaster.Description as S_Description, udt_AddressMaster.City, udt_AddressMaster.ZipCode');
    $this->db->from('udt_AddressMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_AddressMaster.ID', $AddressID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function saveMyUserMaster()
{
    extract($this->input->post());
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php'; 
            
    $document=$_FILES['scanned_signature'];
        
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
            
    // getExtension Method
    $ext=getExtension($document['name']);
    $SignatureImage='';
    if($tmp) {
        $lowerext=strtolower($ext);
        if($lowerext=='pdf' || $lowerext=='jpeg' || $lowerext=='jpg') {
            $actual_image_name = 'TopMarx/Logo/'.$file;
            $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
            $SignatureImage=$file;    
        }
    } 
        
    $document1=$_FILES['attached_photo'];
    $file1=rand(1, 999999).'_____'.$document1['name'];
    $tmp1=$document1['tmp_name'];
            
    $ext1=getExtension($document1['name']);
    $lowerext1=strtolower($ext1);
    $AttachPhoto='';
    if($tmp1) {
        if($lowerext1=='jpg' || $lowerext1=='jpeg' || $lowerext1=='gif' || $lowerext1=='png') {
            $actual_image_name1 = 'TopMarx/Logo/'.$file1;
            $s3->putObjectFile($tmp1, $bucket, $actual_image_name1, S3::ACL_PUBLIC_READ);
            $AttachPhoto=$file1;    
        }
    }
        
    $DesignationRoleID1='';
    $DesignationRoleOther='';
    $InclueDesignationFuture=0;
        
    if($designation_from==1) {
        $DesignationRoleID1=$DesignationRoleID;
        $DesignationRoleOther='';
        $InclueDesignationFuture=0;
    } else if($designation_from==2) {
        $DesignationRoleID1=0;
        $DesignationRoleOther=$CustomDesignationRole;
        $InclueDesignationFuture=$include_designation;
    }
    $this->db->trans_start();
        
    $addr_data=array(
    'Address1'=>$address1,
    'Address2'=>$address2,
    'Address3'=>$address3,
    'Address4'=>$address4,
    'City'=>$city,
    'Telephone1'=>'',
    'Telephone2'=>'',
    'Fax1'=>'',
    'Fax2'=>'',
    'Email'=>'',
    'ActiveFlag'=>'1',
    'ZipCode'=>$zipcode
                );
    if($countryid) {
        $addr_data['CountryID']=$countryid;
    }
    if($stateid) {
        $addr_data['StateID']=$stateid;
    }
        
    $this->db->insert('udt_AddressMaster', $addr_data);
        
    $query=$this->db->query('select Max(ID) as ID from cops_admin.udt_AddressMaster');
    $IDArray=$query->row();
    $AddressID=$IDArray->ID;
        
    if($RemainderInterval) {
        $PasswordExpiryDate=date('Y-m-d', strtotime("+$RemainderInterval months", strtotime($ValidRangeFrom)));
    } else {
        $PasswordExpiryDate=date('Y-m-d', strtotime("+3 months", strtotime($ValidRangeFrom)));
    }
    $data=array(
                'FirstName'=>trim($fname, " "),
                'LastName'=>trim($lname, " "),
                'MiddleName'=>$mname,
                'TitleID'=>$title,
                'UserType'=>$user_type,
                'DesignationFrom'=>$designation_from,
                'DesignationRoleID'=>$DesignationRoleID1,
                'DesignationRoleOther'=>$DesignationRoleOther,
                'InclueDesignationFuture'=>$InclueDesignationFuture,
                'IsEnitityAddress'=>$EntityAddressFlag,
                'LoginID'=>$userid,
                'Password'=>$pswd,
                'ValidAccessFromDate'=>date('Y-m-d', strtotime($ValidRangeFrom)),
                'ValidAccessToDate'=>date('Y-m-d', strtotime($ValidRangeTo)),
                'PasswordExpiryDate'=>$PasswordExpiryDate,
                'PwdChangeInterval'=>$RemainderInterval,
                'SendRecordDetailFlag'=>$SendRecordDetail,
                'CargoInvitationFlag'=>$CargoInvitationFlg,
                'ApproveFixtureFinalFlg'=>$ApproveFixtureFinalFlg,
                'SignFixtureFinalFlg'=>$SignFixtureFinalFlg,
                'SignDigitallyFixtureFlg'=>$SignDigitallyFixtureFlg,
                'ApproveCPFinalFlg'=>$ApproveCPFinalFlg,
                'SignCPFinalFlg'=>$SignCPFinalFlg,
                'SignDigitallyCPFlg'=>$SignDigitallyCPFlg,
                'ApproveTechVettingFlg'=>$ApproveTechVettingFlg,
                'ApproveBusVettingFinalFlg'=>$ApproveBusVettingFinalFlg,
                'ApproveCounterPartyFlg'=>$ApproveCounterPartyFlg,
                'ApproveComplianceFlg'=>$ApproveComplianceFlg,
                'ApproveQuoteAuthFlg'=>$ApproveQuoteAuthFlg,
                'LiftCharterSubjectFlg'=>$LiftCharterSubjectFlg,
                'CreateInvSubjectFlg'=>$CreateInvSubjectFlg,
                'LiftInvSubjectFlg'=>$LiftInvSubjectFlg,
                'LiftInvSubjectFlgByCharter'=>$LiftInvSubjectFlgByCharter,
                'ApproveDocStoreFlg'=>$ApproveDocStoreFlg,
                'SystemChatFlag'=>$SystemChat,
                'GeneralChatFlag'=>$GeneralChat,
                'EntityID'=>$EntityMasterID,
                'PasswordExpires'=>1,
                'ActiveFlag'=>0,
                'DateTime'=>date('Y-m-d H:i:s'),
                'UserLevel'=>3,
                'TermCheckFlag'=>0,
                'PdfDownloadFlag'=>0,
                'ChangePasswordFlag'=>1,
                "addedByComp"=>$OwnerEntityID,
                "addedByUsr"=>$userName1,
                'EmailSendFlag'=>0,
                'CreatedBy'=>$UserID
    );
        
    if($AddressID) {
        $data['OfficialAddressID']=$AddressID;
    }
        
    if($SecurityQues) {
        $data['SecretQuestionID']=$SecurityQues;
        $data['SecretAnswer']=$SecurityQuesAnswer;
    } else {
        $data['SecretQuestionID']=null;
        $data['SecretAnswer']='';
    }
        
    $rett=$this->db->insert('udt_UserMaster', $data);
        
    if($rett) {
        $query1=$this->db->query('select Max(ID) as ID from cops_admin.udt_UserMaster');
        $IDArray1=$query1->row();
        $NewUserID=$IDArray1->ID;
        
        $this->db->insert('udt_AU_UserPreviousPasswords', array('UserID'=>$NewUserID,'UserPassword'=>$pswd,'CreatedDate'=>date('Y-m-d H:i:s')));
        
        $len=count($BussinessGroupID);
        
        for($i=0; $i<$len; $i++) {
            if($business_units[$i]==1) {
                $this->db->insert('udt_AU_UserBussinessGroup', array('BussinessGroupID'=>$BussinessGroupID[$i], 'UserID'=>$NewUserID));
            }
        }
        
        $Telephone1='';
        $Telephone2='';
        $Email='';
        
        $len1=count($emailid1);
        for($i=0; $i<$len1; $i++){
            $eml1=array(
            'UserEmail'=>$emailid1[$i],
            'EmailDescription'=>$email_desc1[$i],
            'UseDefaultFlag'=>$email_flag1[$i],
            'AddEmailInFlg'=>1,
            'UserID'=>$NewUserID
            );
            if($email_flag1[$i]==1 && $Email=='') {
                $Email= trim($emailid1[$i], " "); 
            }
            $this->db->insert('udt_AU_UserEmails', $eml1);
        }
        
        if($Email != '' && $SendRecordDetail==1) {
            
            $subj='New user registration';
            $message ='hello,<br/>';
            $message .='Your are regisered in auomni system.<br/>';
            $message .='Please click here to get your temporary login details : ';
            $message .='<a href="'.base_url().'index.php/send-user-temporary-details?id='.$NewUserID.'">Click here.</a>';
            $this->load->library('email');    
            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'higroove.com';
            $config['smtp_port']    = '25';
            $config['smtp_timeout'] = '7';
            $config['smtp_user']    = 'admin@iaeglobalnetwork.com';
            $config['smtp_pass']    = 'a_WkTq{L2~=p';
            $config['charset']    = 'utf-8';
            $config['newline']    = "\r\n";
            $config['mailtype'] = 'html'; // or html
            
            $this->email->initialize($config);
            
            $this->email->clear();
            $this->email->from('admin@iaeglobalnetwork.com');
            $this->email->to($Email);
            $this->email->subject($subj);
            $this->email->message($message);
            $sent_flag=$this->email->send();
            
        }
        
        $tel_len=count($type);
        $j=1;
        for($i=0; $i<$tel_len; $i++){
            $tels=array(
            'TeleType'=>$type[$i],
            'CountryCode'=>$contrycode[$i],
            'AreaCode'=>$areacode[$i],
            'TeleNumber'=>$tele_number[$i],
            'UserID'=>$NewUserID
            );
            $this->db->insert('udt_AU_UserTelephones', $tels);
            if($j==1) {
                $Telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
            } else if($j==2) {
                $Telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
            } 
            $j++;
        }
        
        $addr_update=array(
                    'Telephone1'=>$Telephone1,
                    'Telephone2'=>$Telephone2,
                    'Email'=>$Email
                );
        $this->db->where('ID', $AddressID);    
        $this->db->update('udt_AddressMaster', $addr_update);
        
        $sign_data=array(
                    'SignName'=>$username,
                    'Title'=>$usertitle,
                    'Designation'=>$user_designation,
                    'CompanyID'=>$CompanyID,
                    'BussinessUnit'=>$business_unit,
                    'SignatureImage'=>$SignatureImage,
                    'AttachPhoto'=>$AttachPhoto,
                    'PhotoCPFinalFlg'=>$DisplayPicSignFinal,
                    'Comment'=>$comment,
                    'UserID'=>$NewUserID
        );
        
        $this->db->insert('udt_AU_SignatureBlock', $sign_data);
        
        if($designation_from==2) {
            if($include_designation==1) {
                $this->db->select('*');
                $this->db->from('udt_RoleMaster');
                $this->db->where('Name', $CustomDesignationRole);
                $query=$this->db->get();
                $rslt=$query->row();
                
                if($rslt) {
                      $usr_data= array(
                     'DesignationRoleOther'=>'',
                     'InclueDesignationFuture'=>0
                      );
                      $this->db->where('ID', $NewUserID);
                      $this->db->update('udt_UserMaster', $usr_data);
                } else {
                    $rolearray= array(
                    'Name'=>$CustomDesignationRole,
                    'Description'=>$CustomDesignationRole,
                    'ActiveFlag'=>1,
                    'DateTime'=>date('Y-m-d')
                    );
                    $ret=$this->db->insert('udt_RoleMaster', $rolearray);
                }
            }
        }
            
    }
        
    $this->db->trans_complete();
    return $rett;
        
}
    
public function getUserMasterData()
{
    $EntityID=$this->input->get('EntityID');
    $ParentID=$this->input->get('ParentID');
    $AssociateEntityID=$this->input->get('AssociateEntityID');
    $UserName=$this->input->get('UserName');
    $this->db->select('udt_UserMaster.ID as UID, udt_UserMaster.EntityID, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_UserMaster.DateTime, udt_EntityMaster.EntityName, udt_EntityMaster.EntityOwner, udt_EntityMaster.InviteeEntityFlg, udt_ParentGroupMaster.GroupName, udt_AddressMaster.Email, udt_AddressMaster.Telephone1');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID', 'left');
    $this->db->join('udt_ParentGroupMaster', 'udt_ParentGroupMaster.ID=udt_EntityMaster.ParentGroupID', 'left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $where=" ( cops_admin.udt_EntityMaster.ID=".$EntityID." OR cops_admin.udt_EntityMaster.EntityOwner=".$EntityID." ) ";
    if($EntityID && $AssociateEntityID=='') {
        $this->db->where($where);
    }
    if($ParentID) {
        $this->db->where('udt_EntityMaster.ParentGroupID', $ParentID);
    }
    if($AssociateEntityID) {
        $this->db->where('udt_UserMaster.EntityID', $AssociateEntityID);
    }
    if($UserName) {
        $this->db->where("(FirstName+' '+LastName) like '%$UserName%'");
    }
    $this->db->order_by('udt_UserMaster.DateTime', 'desc');
    $query=$this->db->get();
        
    return $query->result();
    
}
    
public function getAllDesignationRoles()
{
    $this->db->select('ID,Name');
    $this->db->from('udt_RoleMaster');
    $this->db->where('ActiveFlag', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getEntityUserDetails()
{
    $UID=$this->input->post('UID');
    $this->db->select('udt_UserMaster.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID', 'left');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_UserMaster.ID', $UID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getUserBusinessGroup()
{
    $UID=$this->input->post('UID');
    $this->db->select('udt_AU_UserBussinessGroup.*');
    $this->db->from('udt_AU_UserBussinessGroup');
    $this->db->where('udt_AU_UserBussinessGroup.UserID', $UID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUserEmailIDs()
{
    $UID=$this->input->post('UID');
    $this->db->select('udt_AU_UserEmails.*');
    $this->db->from('udt_AU_UserEmails');
    $this->db->where('udt_AU_UserEmails.UserID', $UID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getUserTelephones()
{
    $UID=$this->input->post('UID');
    $this->db->select('udt_AU_UserTelephones.*');
    $this->db->from('udt_AU_UserTelephones');
    $this->db->where('udt_AU_UserTelephones.UserID', $UID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getUserSignatureBlock()
{
    $UID=$this->input->post('UID');
    $this->db->select('udt_AU_SignatureBlock.*');
    $this->db->from('udt_AU_SignatureBlock');
    $this->db->where('udt_AU_SignatureBlock.UserID', $UID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDesignationRole($DesignationRoleID)
{
    $this->db->select('udt_RoleMaster.*');
    $this->db->from('udt_RoleMaster');
    $this->db->where('udt_RoleMaster.ID', $DesignationRoleID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUserAddressDetail($OfficialAddressID)
{
    $this->db->select('udt_AddressMaster.*,udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description, udt_StateMaster.Code as S_Code, udt_StateMaster.Description as S_Description ');
    $this->db->from('udt_AddressMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_AddressMaster.ID', $OfficialAddressID);
    $query=$this->db->get();
    return $query->row();
}
    
public function deleteAttactedPdfScan()
{
    $UID=$this->input->post('UID');
    $flag=$this->input->post('flag');
    if($flag==1) {
        $data=array('SignatureImage'=>'');
    } else if($flag==2) {
        $data=array('AttachPhoto'=>'');
    }
    $this->db->where('UserID', $UID);
    return $this->db->update('udt_AU_SignatureBlock', $data);
}
    
public function view_scan_file_attached()
{
    $UID=$this->input->post('UID');
    $this->db->select('*');
    $this->db->from('udt_AU_SignatureBlock');
    $this->db->where('UserID', $UID);
    $query=$this->db->get();
    return $query->row();
}
    
public function deleteUserEmailIds()
{
    $UserEmailID=$this->input->post('UserEmailID');
    $this->db->where('UserEmailID', $UserEmailID);
    return $this->db->delete('udt_AU_UserEmails');
}
    
public function deleteUserTelephones()
{
    $UserTeleID=$this->input->post('UserTeleID');
    $this->db->where('UserTeleID', $UserTeleID);
    return $this->db->delete('udt_AU_UserTelephones');
}
    
public function updateMyUserMaster()
{
    extract($this->input->post());
    $this->db->trans_start();
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php'; 
            
            
    $document=$_FILES['scanned_signature'];
        
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
            
    $ext=getExtension($document['name']);
    $SignatureImage=$old_scanned_signature;
    if($tmp) {
        $lowerext=strtolower($ext);
        if($lowerext=='pdf' || $lowerext=='jpeg' || $lowerext=='jpg') {
            $actual_image_name = 'TopMarx/Logo/'.$file;
            $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
            $SignatureImage=$file;    
        }
    } 
        
    $document1=$_FILES['attached_photo'];
        
    $file1=rand(1, 999999).'_____'.$document1['name'];
    $tmp1=$document1['tmp_name'];
        
    $ext1=getExtension($document1['name']);
    $lowerext1=strtolower($ext1);
    $AttachPhoto=$old_attached_photo;
    if($tmp1) {
        if($lowerext1=='jpg' || $lowerext1=='jpeg' || $lowerext1=='gif' || $lowerext1=='png') {
            $actual_image_name1 = 'TopMarx/Logo/'.$file1;
            $s3->putObjectFile($tmp1, $bucket, $actual_image_name1, S3::ACL_PUBLIC_READ);
            $AttachPhoto=$file1;    
        }
    }
        
    $DesignationRoleID1='';
    $DesignationRoleOther='';
    $InclueDesignationFuture=0;
    if($designation_from==1) {
        $DesignationRoleID1=$DesignationRoleID;
        $DesignationRoleOther='';
        $InclueDesignationFuture=0;
    } else if($designation_from==2) {
        $DesignationRoleID1=0;
        $DesignationRoleOther=$CustomDesignationRole;
        $InclueDesignationFuture=$include_designation;
    }
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UID);
    $query=$this->db->get();
    $usr_row=$query->row();
        
    $addressid=$usr_row->OfficialAddressID;
        
    $data=array(
                'FirstName'=>trim($fname, " "),
                'LastName'=>trim($lname, " "),
                'MiddleName'=>$mname,
                'TitleID'=>$title,
                'UserType'=>$user_type,
                'DesignationFrom'=>$designation_from,
                'DesignationRoleID'=>$DesignationRoleID1,
                'DesignationRoleOther'=>$DesignationRoleOther,
                'InclueDesignationFuture'=>$InclueDesignationFuture,
                'IsEnitityAddress'=>$EntityAddressFlag,
                'LoginID'=>$userid,
                'Password'=>$pswd,
                'ValidAccessFromDate'=>date('Y-m-d', strtotime($ValidRangeFrom)),
                'ValidAccessToDate'=>date('Y-m-d', strtotime($ValidRangeTo)),
                'PwdChangeInterval'=>$RemainderInterval,
                'SendRecordDetailFlag'=>$SendRecordDetail,
                'CargoInvitationFlag'=>$CargoInvitationFlg,
                'ApproveFixtureFinalFlg'=>$ApproveFixtureFinalFlg,
                'SignFixtureFinalFlg'=>$SignFixtureFinalFlg,
                'SignDigitallyFixtureFlg'=>$SignDigitallyFixtureFlg,
                'ApproveCPFinalFlg'=>$ApproveCPFinalFlg,
                'SignCPFinalFlg'=>$SignCPFinalFlg,
                'SignDigitallyCPFlg'=>$SignDigitallyCPFlg,
                'ApproveTechVettingFlg'=>$ApproveTechVettingFlg,
                'ApproveBusVettingFinalFlg'=>$ApproveBusVettingFinalFlg,
                'ApproveCounterPartyFlg'=>$ApproveCounterPartyFlg,
                'ApproveComplianceFlg'=>$ApproveComplianceFlg,
                'ApproveQuoteAuthFlg'=>$ApproveQuoteAuthFlg,
                'LiftCharterSubjectFlg'=>$LiftCharterSubjectFlg,
                'CreateInvSubjectFlg'=>$CreateInvSubjectFlg,
                'LiftInvSubjectFlg'=>$LiftInvSubjectFlg,
                'LiftInvSubjectFlgByCharter'=>$LiftInvSubjectFlgByCharter,
                'ApproveDocStoreFlg'=>$ApproveDocStoreFlg,
                'SystemChatFlag'=>$SystemChat,
                'GeneralChatFlag'=>$GeneralChat,
                'EntityID'=>$EntityMasterID,
                "addedByComp"=>$OwnerEntityID,
                "addedByUsr"=>$userName1,
                'DateTime'=>date('Y-m-d H:i:s')
    );
            
    if($SecurityQues) {
        $data['SecretQuestionID']=$SecurityQues;
        $data['SecretAnswer']=$SecurityQuesAnswer;
    } else {
        $data['SecretQuestionID']=0;
        $data['SecretAnswer']='';
    }    
        
    $this->db->where('ID', $UID);
    $this->db->update('udt_UserMaster', $data);
        
    if($usr_row->Password !=$pswd) {
            
        $effectiveDate = date('Y-m-d', strtotime("+$usr_row->PwdChangeInterval months"));
        
        $this->db->insert('udt_AU_UserPreviousPasswords', array('UserID'=>$usr_row->ID,'UserPassword'=>$pswd,'CreatedDate'=>date('Y-m-d H:i:s')));
            
        $this->db->where('ID', $UID);
        $this->db->update('Udt_UserMaster', array('PasswordExpiryDate'=>$effectiveDate,'ChangePasswordFlag'=>0,'EmailSendFlag'=>0));
    }
        
    $len=count($BussinessGroupID);
    for($i=0; $i<$len; $i++){
        if($business_units[$i]==1) {
            $this->db->where('UserID', $UID);
            $this->db->update('udt_AU_UserBussinessGroup', array('BussinessGroupID'=>$BussinessGroupID[$i]));
        }
    }
    $Telephone1='';
    $Telephone2='';
    $Email='';
        
    $len2=count($oldemailid1);
    for($i=0; $i<$len2; $i++){
        $eml2=array(
        'UserEmail'=>$oldemailid1[$i],
        'EmailDescription'=>$oldemail_desc1[$i],
        'UseDefaultFlag'=>$oldemail_flag1[$i]
        );
        if($oldemail_flag1[$i]==1 && $Email=='') {
            $Email=$oldemailid1[$i];
        }
        $this->db->where('UserEmailID', $UserEmailID[$i]);
        $this->db->update('udt_AU_UserEmails', $eml2);
    }
        
    $len1=count($emailid1);
    for($i=0; $i<$len1; $i++){
        $eml1=array(
        'UserEmail'=>$emailid1[$i],
        'EmailDescription'=>$email_desc1[$i],
        'UseDefaultFlag'=>$email_flag1[$i],
        'AddEmailInFlg'=>1,
        'UserID'=>$UID
        );
        if($email_flag1[$i]==1 && $Email=='') {
            $Email=$emailid1[$i];
        }
        $this->db->insert('udt_AU_UserEmails', $eml1);
    }
        
    $tel_len1=count($old_type);
        
    $j=1;
    for($i=0; $i<$tel_len1; $i++){
        $tels1=array(
        'TeleType'=>$old_type[$i],
        'CountryCode'=>$old_contrycode[$i],
        'AreaCode'=>$old_areacode[$i],
        'TeleNumber'=>$old_tele_number[$i]
        );
        $this->db->where('UserTeleID', $UserTeleID[$i]);
        $this->db->update('udt_AU_UserTelephones', $tels1);
        if($j==1) {
            $Telephone1=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } else if($j==2) {
            $Telephone2=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } 
            $j++;
    }
        
    $tel_len=count($type);
    for($i=0; $i<$tel_len; $i++){
        $tels=array(
        'TeleType'=>$type[$i],
        'CountryCode'=>$contrycode[$i],
        'AreaCode'=>$areacode[$i],
        'TeleNumber'=>$tele_number[$i],
        'UserID'=>$UID
        );
        $this->db->insert('udt_AU_UserTelephones', $tels);
        if($j==1) {
            $Telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($j==2) {
            $Telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            $j++;
    }
        
    $addr_data=array(
    'Address1'=>$address1,
    'Address2'=>$address2,
    'Address3'=>$address3,
    'Address4'=>$address4,
    'City'=>$city,
    'Telephone1'=>$Telephone1,
    'Telephone2'=>$Telephone2,
    'Email'=>$Email,
    'ZipCode'=>$zipcode
                );
    if($countryid) {
        $addr_data['CountryID']=$countryid;
    } else {
        $addr_data['CountryID']=null;
    }
    if($stateid) {
        $addr_data['StateID']=$stateid;
    } else {
        $addr_data['StateID']=null;
    }
            
    $this->db->where('ID', $addressid);    
    $this->db->update('udt_AddressMaster', $addr_data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_SignatureBlock');
    $this->db->where('UserID', $UID);
    $qry=$this->db->get();
    $row=$qry->row();
    if($row) {
        $sign_data=array(
        'SignName'=>$username,
        'Title'=>$usertitle,
        'Designation'=>$user_designation,
        'CompanyID'=>$CompanyID,
        'BussinessUnit'=>$business_unit,
        'SignatureImage'=>$SignatureImage,
        'AttachPhoto'=>$AttachPhoto,
        'PhotoCPFinalFlg'=>$DisplayPicSignFinal,
        'Comment'=>$comment
        );
        $this->db->where('UserID', $UID);
        $this->db->update('udt_AU_SignatureBlock', $sign_data);
    } else {
        $sign_data=array(
        'UserID'=>$UID,
        'SignName'=>$username,
        'Title'=>$usertitle,
        'Designation'=>$user_designation,
        'CompanyID'=>$CompanyID,
        'BussinessUnit'=>$business_unit,
        'SignatureImage'=>$SignatureImage,
        'AttachPhoto'=>$AttachPhoto,
        'PhotoCPFinalFlg'=>$DisplayPicSignFinal,
        'Comment'=>$comment
        );
        $this->db->insert('udt_AU_SignatureBlock', $sign_data);
    }
        
    $this->db->select('*');
    $this->db->from('udt_RoleMaster');
    $this->db->where('Name', $CustomDesignationRole);
    $query=$this->db->get();
    $rslt=$query->row();
        
    $old_DesignationFrom=$usr_row->DesignationFrom;
    $old_InclueDesignFuture=$usr_row->InclueDesignationFuture;
    $old_DesignRoleOther=$usr_row->DesignationRoleOther;
        
    if($old_DesignationFrom == $designation_from) {
        if($designation_from==2) {
            if($old_InclueDesignFuture==$include_designation) {
                if($include_designation==1) {
                    $rolearray= array(
                    'Name'=>$CustomDesignationRole,
                    'Description'=>$CustomDesignationRole
                     );
                    $this->db->where('Name', $old_DesignRoleOther);
                    $this->db->update('udt_RoleMaster', $rolearray);
                }
            } else {
                if($include_designation==1) {
                    if($rslt) {
                        $usr_data= array(
                        'DesignationRoleOther'=>'',
                        'InclueDesignationFuture'=>0
                        );
                        $this->db->where('ID', $UID);
                        $this->db->update('udt_UserMaster', $usr_data);
                    } else {
                        $rolearray= array(
                        'Name'=>$CustomDesignationRole,
                        'Description'=>$CustomDesignationRole,
                        'ActiveFlag'=>1,
                        'DateTime'=>date('Y-m-d')
                        );
                        $this->db->insert('udt_RoleMaster', $rolearray);
                    }
                }
                
            }
        }
    } else {
        if($designation_from==2) {
            if($include_designation==1) {
                if($rslt) {
                    $usr_data= array(
                     'DesignationRoleOther'=>'',
                     'InclueDesignationFuture'=>0
                    );
                    $this->db->where('ID', $UID);
                    $this->db->update('udt_UserMaster', $usr_data);
                } else {
                    $rolearray= array(
                    'Name'=>$CustomDesignationRole,
                    'Description'=>$CustomDesignationRole,
                    'ActiveFlag'=>1,
                    'DateTime'=>date('Y-m-d')
                    );
                    $this->db->insert('udt_RoleMaster', $rolearray);
                }
            }
        }
    }
        
    if($designation_from==2) {
        if($include_designation==1) {
            $rolearray= array(
            'Name'=>$CustomDesignationRole,
            'Description'=>$CustomDesignationRole,
            'ActiveFlag'=>1,
            'DateTime'=>date('Y-m-d')
            );
            $this->db->insert('udt_RoleMaster', $rolearray);
        }
    }
        
    //------------------------------blockchain----------------------------
        
    $this->db->select('*');
    $this->db->from('Udt_AU_UserBlockchainRecord');
    $this->db->where('UID', $UID);
    $bquery=$this->db->get();
    $rslt=$bquery->row();
    if($rslt) {
        $data = array("blockchainIndex" =>$rslt->BlockchainIndex,"addedByComp"=>$OwnerEntityID,"addedByUsr"=>$userName1,"entityId" =>$EntityMasterID,"cargoInvitationFlag"=>$CargoInvitationFlg,"approveFixtureFinalFlg"=>$ApproveFixtureFinalFlg,"signFixtureFinalFlg"=>$SignFixtureFinalFlg,"signCPFinalFlg"=>$SignCPFinalFlg,"approveCPFinalFlg"=>$ApproveCPFinalFlg,"approveTechVettingFlg"=>$ApproveTechVettingFlg,"approveBusVettingFinalFlg"=>$ApproveBusVettingFinalFlg,"approveCounterPartyFlg"=>$ApproveCounterPartyFlg,"approveComplianceFlg"=>$ApproveComplianceFlg,"approveQuoteAuthFlg"=>$ApproveQuoteAuthFlg,"liftCharterSubjectFlg"=>$LiftCharterSubjectFlg,"createInvSubjectFlg"=>$CreateInvSubjectFlg,"liftInvSubjectFlg"=>$LiftInvSubjectFlg,"liftInvSubjectFlgByCharter"=>$LiftInvSubjectFlgByCharter,"signDigitallyFixtureFlg"=>$SignDigitallyFixtureFlg,"signDigitallyCPFlg"=>$SignDigitallyCPFlg); 
            
        $data_string = json_encode($data); 
        $url=BLOCK_CHAIN_URL.'updateUserRole/';
        $ch = curl_init($url); 
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(       
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))   
        );
            
        $result = curl_exec($ch);
        $creationTx=$result;
        $insArr=array('UID'=>$UID,'PrivKey'=>$rslt->PrivKey,'PubKey'=>$rslt->PubKey,'Address'=>$rslt->Address,'BlockchainIndex'=>$rslt->BlockchainIndex,'CreationTx'=>$creationTx,'EntityId'=>$EntityMasterID,'CreationDate'=>date('Y-m-d H:i:s'));
            
        $this->db->where('UID', $UID);
        $this->db->update('Udt_AU_UserBlockchainRecord', $insArr);
            
        $this->db->insert('Udt_AU_UserBlockchainRecord_H', $insArr);
    }
        
    $this->db->trans_complete();
    return 1;
}
    
public function all_parent_entity_data()
{ 
    $key=$this->input->post('key');
    $entity=$this->input->post('entity');
    $ids=array('1',$entity);
    $this->db->select('ID,GroupName,Description');
    $this->db->from('udt_ParentGroupMaster', 'after');
    $this->db->like('GroupName', $key);
    if($entity) {
        $this->db->where_in('EntityID', $ids);
    }
    $query=$this->db->get();
    return $query->result();
        
}
    
public function all_associated_entity_data()
{ 
    $key=$this->input->post('key');
    $entity=$this->input->post('entity');
    $ids=array('1',$entity);
    $this->db->select('ID,EntityName,Description');
    $this->db->from('udt_EntityMaster', 'after');
    $this->db->like('EntityName', $key);
    if($entity) {
        $this->db->where_in('EntityOwner', $ids);
    }
    $query=$this->db->get();
    return $query->result();
}
    
public function getInviteeTermCondtion()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $query=$this->db->get();
    $rslt=$query->row();
    $id=$rslt->ID;
        
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->where('ForUserID', $id);
    $this->db->where('TermStatus', 0);
    $query=$this->db->get();
    $rslt1=$query->row();
        
    if(count($rslt1)>0) {
        $UserID=$rslt1->UserID;
        $entity_id=$rslt1->RecordOwner;
        
        $this->db->select('*');
        $this->db->from('AUM_TermCondition');
        $this->db->where('EntityID', $entity_id);
        $this->db->where('Status', 3);
        $this->db->where('Application', 1);
        $query=$this->db->get();
        return $query->row();
        
    } else {
        return 'yes';
    }
}
    
public function checkUserLogin()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $this->db->where('Password', $passowrd);
    $query=$this->db->get();
    $rslt=$query->row();
        
    if($rslt) {
        if($rslt->WrongPasswordCount < 3) {
            if($rslt->UserType=='A') {
                $this->db->where('LoginID', $userID);
                $this->db->update('udt_UserMaster', array('WrongPasswordCount'=>0,'LastLoggedIn'=>date('Y-m-d H:i:s')));
                if($rslt->ChangePasswordFlag == 1) {
                    return 6;
                } else {
                    return 1;
                }
            } else if(strtotime($rslt->ValidAccessFromDate) <= strtotime(date('Y-m-d')) && strtotime($rslt->ValidAccessToDate) >= strtotime(date('Y-m-d'))) {
                if(strtotime($rslt->PasswordExpiryDate) >= strtotime(date('Y-m-d'))) {
                    $this->db->where('LoginID', $userID);
                    $this->db->update('udt_UserMaster', array('WrongPasswordCount'=>0,'LastLoggedIn'=>date('Y-m-d H:i:s')));
                    if($rslt->ChangePasswordFlag == 1) {
                        return 6;
                    } else {
                        return 1;
                    }
                } else {
                     return 5;
                }
                    
            } else {
                return 7;
            }
        } else {
            return 2;
        }
    } else {
        return 3;
    }
}
    
public function sendReminderMessages()
{
    $this->load->library('email');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ActiveFlag', 1);
    $this->db->where('EmailSendFlag', '0');
    $query=$this->db->get();
    $rslt=$query->result();
        
    foreach($rslt as $row){
        $date1=date_create(date('Y-m-d'));
        $date2=date_create(date('Y-m-d', strtotime($row->PasswordExpiryDate)));
        $diff=date_diff($date1, $date2);
            
        if($diff->format("%R%a") > 0) {
            if($diff->format("%a") < 7) {
                $this->db->select('*');
                $this->db->from('udt_AddressMaster');
                $this->db->where('ID', $row->OfficialAddressID);
                $query1=$this->db->get();
                $rw=$query1->row();
                if(count($rw) > 0) {
                    if($rw->Email) {
                        /*
                        $config['protocol']    = 'smtp';
                        $config['smtp_host']   = 'higroove.com';
                        $config['smtp_port']   = '25';
                        $config['smtp_timeout']= '7';
                        $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
                        $config['smtp_pass']   = 'a_WkTq{L2~=p';
                        $config['charset']     = 'utf-8';
                        $config['newline']     = "\r\n";
                        $config['mailtype']    = 'html'; // or html
                            
                        $this->email->initialize($config);
                            
                        $content1 = 'Hello sir,<br><br>';
                        $content1 .= 'Your auomni login account change password required. Please update your password as soon as possible. Last date of changeing your password is '.date('d-m-Y',strtotime($row->PasswordExpiryDate)).'.';
                        $subject = 'Change password reminder';
                        $this->email->clear();
                        $this->email->from('admin@iaeglobalnetwork.com');
                        $this->email->to($rw->Email);
                        $this->email->subject($subject);
                        $this->email->message($content1);
                        $sent_flag=$this->email->send();
                            
                        $this->db->where('ID',$row->ID);
                        $this->db->update('udt_UserMaster',array('EmailSendFlag'=>1));
                            
                        */
                    }
                        
                }
            }
        } else {
            //echo '-'; die;
        }
    }
        
        
}
    
public function updateWrongPasswordCount()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $userID);
    $query=$this->db->get();
    $rslt=$query->row();
    if($rslt) {
        $WrongPasswordCount=$rslt->WrongPasswordCount+1;
                
        $this->db->where('LoginID', $userID);
        $this->db->update('udt_UserMaster', array('WrongPasswordCount'=>$WrongPasswordCount));
        if($WrongPasswordCount < 3) {
            return 3;
        } else {
            return 2;
        }
    } else {
        return 4;
    }
        
}
    
public function getHeadingById($EntityID)
{
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $EntityID);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->EntityName;
}
    
public function disableTermCondtion()
{
    extract($this->input->post());
    if($inviteecht==1) {
        $this->db->select('*');
        $this->db->from('udt_UserMaster');
        $this->db->where('LoginID', $userID);
        $this->db->where('Password', $passowrd);
        $query=$this->db->get();
        $rslt=$query->row();
        $id=$rslt->ID;
            
            
        $this->db->select('*');
        $this->db->from('udt_AUM_Invitee_Master');
        $this->db->where('ForUserID', $id);
        $this->db->where('TermStatus', 0);
        $query=$this->db->get();
        $rslt1=$query->row();
        $InviteeID=$rslt1->InviteeID;
            
        $data=array('TermStatus'=>1);
        $this->db->where('InviteeID', $InviteeID);
        $this->db->update('udt_AUM_Invitee_Master', $data);
    } 
        
    if($inviteecht==2) {
        $data=array('TermCheckFlag'=>1);
        $this->db->where('LoginID', $userID);
        $this->db->where('Password', $passowrd);
        $this->db->update('udt_UserMaster', $data);
    }
}
    
public function getModalName()
{
    $EntityID=$this->input->post('EntityID');
    $ModelFunction=$this->input->post('ModelFunction');
    $this->db->select('mid,ModelNumber,ModelFunction');
    $this->db->from('udt_AU_Model');
    $this->db->where('RecordOwner', $EntityID);
    $this->db->where('ModelFunction', $ModelFunction);
    $this->db->where('ModelStatus', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_associated_entity_name()
{ 
    if($this->input->get()) {
        $key=$this->input->get('key');
        $OwnerID=$this->input->get('OwnerID');
    }else{
        $key=$this->input->post('key');
        $OwnerID=$this->input->post('OwnerID');
    }
        
    $ids=array('1',$OwnerID);
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->like('EntityName', $key, 'after');
    $this->db->where('EntityOwner', $OwnerID);
    $query=$this->db->get();
    $result=$query->result();
    if(count($result)>0) {
        return $result;
    } else {
        $this->db->select('*');
        $this->db->from('udt_EntityMaster');
        $this->db->like('EntityName', $key, 'after');
        $this->db->where('EntityOwner', 1);
        $query=$this->db->get();
        return $query->result();
    }
}
    
public function get_entity_country()
{ 
    if($this->input->get()) {
        $key=$this->input->get('key');
    }else{
        $key=$this->input->post('key');
    }
        
    $this->db->select('*');
    $this->db->from('udt_CountryMaster');
    $this->db->like('Description', $key, 'after');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function get_entity_state()
{ 
    if($this->input->get()) {
        $key=$this->input->get('key');
        $CountryID=$this->input->get('CountryID');
    }else{
        $key=$this->input->post('key');
        $CountryID=$this->input->post('CountryID');
    }
        
    $this->db->select('*');
    $this->db->from('udt_StateMaster');
    $this->db->where('CountryID', $CountryID);
    $this->db->like('Description', $key, 'after');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function get_assoc_entity_details()
{
    $entityid=$this->input->get('entityid');
    $this->db->select('udt_EntityMaster.*,udt_AddressMaster.Address1,udt_AddressMaster.Address2,udt_AddressMaster.Address3,udt_AddressMaster.Address4,udt_AddressMaster.CountryID,udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description,udt_AddressMaster.StateID,udt_StateMaster.Code as S_Code, udt_StateMaster.Description as S_Description');
    $this->db->from('udt_EntityMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_EntityMaster.AddressID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_EntityMaster.ID', $entityid);
    $query=$this->db->get();
    return $query->row();
}
    
public function saveBusinessProcess()
{
    extract($this->input->post());
    if($date_from) {
        $dfrom=date('Y-m-d', strtotime($date_from));
    } else {
        $dfrom='';
    }
    if($date_to) {
        $dto=date('Y-m-d', strtotime($date_to));
    } else {
        $dto='';
    }
        
    $this->db->where('RecordOwner', $EntityMasterID);
    $this->db->where('BP_Name', $name_of_process);
    $this->db->update('udt_AUM_BP_Rules', array('link'=>1));
        
    $data=array(
                'RecordOwner'=>$EntityMasterID,
                'name_of_process'=>$name_of_process,
                'process_applies'=>$process_applies,
                'process_flow_sequence'=>$process_flow_sequence,
                'putting_freight_quote'=>$putting_freight_quote,
                'submitting_freight_quote'=>$submitting_freight_quote,
                'fixture_not_finalization'=>$fixture_not_finalization,
                'charter_party_finalization'=>$charter_party_finalization,
                'finalization_completed_by'=>$finalization_completed_by,
                'message_text'=>$message_text,
                'show_in_process'=>$show_in_process,
                'show_in_fixture'=>$show_in_fixture,
                'show_in_charter_party'=>$show_in_charter_party,
                'validity'=>$validity,
                'date_from'=>$dfrom,
                'date_to'=>$dto,
                'status'=>$status,
                'comments'=>$comments,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AUM_BusinessProcess', $data);
}
    
public function getBusinessProcessByEntityid()
{
    $EntityID=$this->input->get('EntityID');
    $pfs=$this->input->get('pfs');
    $df=$this->input->get('df');
    $dt=$this->input->get('dt');
    $status=$this->input->get('status');
    $this->db->select('*');
    $this->db->from('udt_AUM_BusinessProcess');
    if($EntityID) {
        $this->db->where('RecordOwner', $EntityID);
    }
    if($pfs) {
        $this->db->where('process_flow_sequence', $pfs);
    }
    if($df) {
        $date_from=date('Y-m-d', strtotime($df));
        $this->db->where('date_from >=', $date_from);
        $this->db->where('validity', 2);
    }
    if($dt) {
        $date_to=date('Y-m-d', strtotime($dt));
        $this->db->where('date_to <=', $date_to);
        $this->db->where('validity', 2);
    }
    if($status!='no') {
        $this->db->where('status', $status);
    }
    $this->db->order_by('UserDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getBusinessProcessById()
{
    $id=$this->input->post('id');
    $this->db->select('*,CONVERT(VARCHAR(10),date_from,105) as df,CONVERT(VARCHAR(10),date_to,105) as dt');
    $this->db->from('udt_AUM_BusinessProcess');
    $this->db->where('BPID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateBusinessProcess()
{
    extract($this->input->post());
    if($date_from) {
        $dfrom=date('Y-m-d', strtotime($date_from));
    } else {
        $dfrom='';
    }
    if($date_to) {
        $dto=date('Y-m-d', strtotime($date_to));
    } else {
        $dto='';
    }
        
    if($ChangeFlag==1) {
        $this->db->where('RecordOwner', $EntityMasterID);
        $this->db->where('BP_Name', $name_of_process);
        $this->db->update('udt_AUM_BP_Rules', array('link'=>1));
    }
        
    $data=array(
                'name_of_process'=>$name_of_process,
                'process_applies'=>$process_applies,
                'process_flow_sequence'=>$process_flow_sequence,
                'putting_freight_quote'=>$putting_freight_quote,
                'submitting_freight_quote'=>$submitting_freight_quote,
                'fixture_not_finalization'=>$fixture_not_finalization,
                'charter_party_finalization'=>$charter_party_finalization,
                'finalization_completed_by'=>$finalization_completed_by,
                'message_text'=>$message_text,
                'show_in_process'=>$show_in_process,
                'show_in_fixture'=>$show_in_fixture,
                'show_in_charter_party'=>$show_in_charter_party,
                'validity'=>$validity,
                'date_from'=>$dfrom,
                'date_to'=>$dto,
                'status'=>$status,
                'comments'=>$comments,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->where('BPID', $BPID);
    return $this->db->update('udt_AUM_BusinessProcess', $data);
        
}
    
public function deleteBusinessProcessById()
{
    $id=$this->input->post('id');
    $tid=trim($id, ',');
    $ids=explode(',', $tid);
    $this->db->where_in('BPID', $ids);
    $this->db->delete('udt_AUM_BusinessProcess');
}
    
public function getBusinessProcessMessageById()
{
    $bpid=$this->input->post('bpid');
    $this->db->select('*');
    $this->db->from('udt_AUM_BusinessProcess');
    $this->db->where('BPID', $bpid);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUserByEntityId()
{
    $EntityID=$this->input->post('EntityID');
    $bpname=$this->input->post('bpname');
    $this->db->select('udt_UserMaster.ID,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->where('EntityID', $EntityID);
        
    if($bpname==1) {
        $this->db->where('ApproveTechVettingFlg', 1);
    } else if($bpname==2) {
        $this->db->where('ApproveBusVettingFinalFlg', 1);
    } else if($bpname==3) {
        $this->db->where('ApproveCounterPartyFlg', 1);
    } else if($bpname==4) {
        $this->db->where('ApproveComplianceFlg', 1);
    } else if($bpname==5) {
        //$this->db->where('ApproveComplianceFlg',1);
    } else if($bpname==6) {
        $this->db->where('ApproveCPFinalFlg', 1);
    } else if($bpname==7) {
        $this->db->where('ApproveFixtureFinalFlg', 1);
    } else if($bpname==9) {
        $this->db->where('LiftCharterSubjectFlg', 1);
    }
        
    $query=$this->db->get();
    return $query->result();
}
    
public function updateEntityRolePermission()
{
    extract($this->input->post());
    $file='';
    $document=$_FILES['role_file'];
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
        
    $ext=getExtension($document['name']);
        
    if($ext=='pdf' || $ext=='PDF') {
        $nar=explode(".", $document['type']);
        $type=end($nar);
        $file=rand(1, 999999).'_____'.$document['name'];
        $tmp=$document['tmp_name'];
        $filesize=$document['size'];
        $actual_image_name = 'TopMarx/'.$file;
            
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
            
    }
        
    $data=array(
                'EntityTypeStatus'=>$InviteeStatus,
                'InviteePeriod'=>$InviteePeriod,
                'UserGroup'=>$UserGroup,
                'DateRangeFrom'=>date('Y-m-d', strtotime($DateRangeFrom)),
                'DateRangeTo'=>date('Y-m-d', strtotime($DateRangeTo)),
                'PriorityStatus'=>$PriorityStatus,
                'PriorityComment'=>$role_comment,
                'AttachedFile'=>$file,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d')
    );
    $this->db->where('EntityMasterID', $EntityMasterID);
    $this->db->where('EntityTypeID', $InvRole);
    return $this->db->update('udt_Mapping_EntityTypes', $data);
    
}
    
public function getEntityRolePermission()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
    $EntityTypeID=$this->input->post('EntityTypeID');
        
    $this->db->select('*');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->where('EntityMasterID', $EntityMasterID);
    $this->db->where('EntityTypeID', $EntityTypeID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function deleteRoleAttachedFile()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
    $EntityTypeID=$this->input->post('EntityTypeID');
        
    $this->db->where('EntityMasterID', $EntityMasterID);
    $this->db->where('EntityTypeID', $EntityTypeID);
    return $this->db->delete('udt_Mapping_EntityTypes');
        
}
    
public function getEntityTypeRole()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
    $this->db->select('udt_Mapping_EntityTypes.*,udt_EntityType.Code, udt_EntityType.Description');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_Mapping_EntityTypes.EntityTypeID');
    $this->db->where('EntityMasterID', $EntityMasterID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getInviteeRecordByEntity()
{
    $EntityMasterID=$this->input->post('EntityMasterID');
    $this->db->select('udt_AUM_Invitee_Master.*');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
    $this->db->where('udt_UserMaster.EntityID', $EntityMasterID);
    $query=$this->db->get();
    return $query->result();
}
    
public function checkInviteeEntityPrimeRole()
{
    
    $EntityID=$this->input->get('EntityID');
    $AuctionID=$this->input->get('AuctionId');
    $this->db->select('udt_AUM_Invitees.*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('udt_AUM_Invitees.EntityID', $EntityID);
    $this->db->where('udt_AUM_Invitees.AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function saveBusinessProcessRule()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AUM_BP_Rules');
    $this->db->where('BP_Name', $name_of_process);
    $this->db->where('RecordOwner', $EntityMasterID);
    $query=$this->db->get();
    $cnt=$query->num_rows();
    
    if($cnt == 0) {
        $data=array(
        'BP_version'=>'1.0',
        'BP_Name'=>$name_of_process,
        'BP_CompleteBy'=>$finalization_completed_by,
        'BP_Applies'=>$process_applies,
        'BP_Comment'=>$comment,
        'BP_Status'=>$ActiveFlag,
        'CreatedBy'=>$UserID,
        'RecordOwner'=>$EntityMasterID,
        'UpdatedBy'=>0,
        'link'=>0,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $ret=$this->db->insert('udt_AUM_BP_Rules', $data);
        if($ret) {
            return 1;
        } else {
            return 0;
        }
    } else {
        return 2;
    }
}
    
public function getBusinessProcessRule()
{
    $EntityID=$this->input->get('EntityID');
    $BP_Name=$this->input->get('name_of_process');
    $BP_CompleteBy=$this->input->get('completed_by');
    $this->db->select('*');
    $this->db->from('udt_AUM_BP_Rules');
    if($EntityID) {
        $this->db->where('RecordOwner', $EntityID);
    }
    if($BP_Name) {
        $this->db->where('BP_Name', $BP_Name);
    }
    if($BP_CompleteBy) {
        $this->db->where('BP_CompleteBy', $BP_CompleteBy);
    }
    $this->db->order_by('UserDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getBusinessProcessRuleById()
{
    $BP_RuleID=$this->input->post('id');
    $this->db->select('udt_AUM_BP_Rules.*, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_BP_Rules');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_BP_Rules.RecordOwner');
    $this->db->where('BP_RuleID', $BP_RuleID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getBusinessCompletedBy()
{
    $BP_Name=$this->input->post('val');
    $RecordOwner=$this->input->post('EntityID');
    $this->db->select('udt_AUM_BP_Rules.*');
    $this->db->from('udt_AUM_BP_Rules');
    $this->db->where('BP_Name', $BP_Name);
    $this->db->where('RecordOwner', $RecordOwner);
    $this->db->where('BP_Status', 1);
    $this->db->order_by('BP_RuleID', 'DESC');
    $query=$this->db->get();
    return $query->row();
        
}
    
public function updateBusinessProcessRule()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AUM_BP_Rules');
    $this->db->where('BP_Name', $name_of_process);
    $this->db->where('RecordOwner', $EntityMasterID);
    $this->db->order_by('BP_RuleID', 'desc');
    $query=$this->db->get();
        
    $cnt=$query->num_rows();
            
    $data['BP_Name']=$name_of_process;
    $data['BP_CompleteBy']=$finalization_completed_by;
    $data['BP_Applies']=$process_applies;
    $data['BP_Comment']=$comment;
    $data['BP_Status']=$ActiveFlag;
    $data['RecordOwner']=$EntityMasterID;
    $data['link']=0;
    $data['UserDate']=date('Y-m-d H:i:s');
        
    if($cnt == 0) {
        $data['BP_version']='1.0';
        $data['CreatedBy']=$UserID;
        $data['UpdatedBy']=0;
        $ret=$this->db->insert('udt_AUM_BP_Rules', $data);
    } else {
        $rslt=$query->row();
        if($rslt->link == 1) {
            $data['BP_version']=(float)$rslt->BP_version+0.1;
            $data['CreatedBy']=$UserID;
            $data['UpdatedBy']=0;
            $ret=$this->db->insert('udt_AUM_BP_Rules', $data);
                
            $this->db->where('BP_RuleID', $rslt->BP_RuleID);
            $this->db->update('udt_AUM_BP_Rules', array('BP_Status'=>0));
                
        } else {
            $data['UpdatedBy']=$UserID;
            $this->db->where('BP_RuleID', $rslt->BP_RuleID);
            $ret=$this->db->update('udt_AUM_BP_Rules', $data);
        }
    }
    if($ret) {
        return 1;
    } else {
        return 0;
    }
}
    
public function cloneMyParentMaster()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_ParentGroupMaster');
    $this->db->where('EntityID', $EntityMasterID);
    $this->db->where('GroupName', $parent_company_name);
    $query=$this->db->get();
    $cnt=$query->num_rows();
    if($cnt > 0) {
        return 2;
    }
        
    $document=$_FILES['Logo'];
        
    $file=rand(1, 999999).'_____'.$document['name'];
    $tmp=$document['tmp_name'];
        
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';         // getExtension Method
    if($tmp) {
        $actual_image_name = 'TopMarx/Logo/'.$file;
        $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
    } 
        
    if($tmp) {
        $Logo=$file;    
    } else {
        $Logo=$oldfile;
    }
        
    $cnt1=count($OthParentCompID);
        
    for($i=0; $i<$cnt1; $i++){
        $oth_data=array(
        'IDNumber'=>$old_oth_comid[$i],
        'Location'=>$old_com_location[$i],
        'OtherDescription'=>$old_comp_desc[$i],
        'CompanyID'=>$CompanyID
        );
        $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
    }
        
    $cnt=count($other_companyid);
    for($i=0; $i<$cnt; $i++){
        $oth_data=array(
        'IDNumber'=>$other_companyid[$i],
        'Location'=>$company_location[$i],
        'OtherDescription'=>$company_desc[$i],
        'CompanyID'=>$CompanyID
        );
        $this->db->insert('udt_AU_OtherParentCompanyID', $oth_data);
    }
        
    $cntem=count($emailid);
    $Email='';
    for($i=0; $i<$cntem; $i++){
        if($i==0) {
            $Email=$emailid[$i];
        }
        $eml_data=array(
        'EmailID'=>$emailid[$i],
        'EmailDescription'=>$email_desc[$i],
        'CompanyID'=>$CompanyID
                );
        $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
    }
        
    $cntem1=count($ParentEmailID);
    for($i=0; $i<$cntem1; $i++){
        if($i==0) {
            $Email=$old_emailid[$i];
        }
        $eml_data=array(
        'EmailID'=>$old_emailid[$i],
        'EmailDescription'=>$old_email_desc[$i],
        'CompanyID'=>$CompanyID
                );
        $this->db->insert('udt_AU_ParentEmailDetails', $eml_data);
    }
        
    $cntel=count($type);
        
    $telephone1='';
    $telephone2='';
    for($i=0; $i<$cntel; $i++){
        if($i==0 ) {
            $telephone1=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$contrycode[$i].' '.$areacode[$i].' '.$tele_number[$i];
        } 
            
        $tel_data=array(
                    'TelephoneType'=>$type[$i],
                    'TeleCountryCode'=>$contrycode[$i],
                    'TeleAreaCode'=>$areacode[$i],
                    'TeleNumber'=>$tele_number[$i],
                    'CompanyID'=>$CompanyID
        );
        $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
    }
        
    $cntel1=count($ParentTelephoneID);
        
    for($i=0; $i<$cntel1; $i++){
        if($i==0 ) {
            $telephone1=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } else if($i==1 ) {
            $telephone2=$old_contrycode[$i].' '.$old_areacode[$i].' '.$old_tele_number[$i];
        } 
            
        $tel_data=array(
                    'TelephoneType'=>$old_type[$i],
                    'TeleCountryCode'=>$old_contrycode[$i],
                    'TeleAreaCode'=>$old_areacode[$i],
                    'TeleNumber'=>$old_tele_number[$i],
                    'CompanyID'=>$CompanyID
        );
        $this->db->insert('udt_AU_ParentTelephoneDetails', $tel_data);
    }
    $addr_data=array(
                'Address1'=>$address1,
                'Address2'=>$address2,
                'Address3'=>$address3,
                'Address4'=>$address4,
                'Telephone1'=>$telephone1,
                'Telephone2'=>$telephone2,
                'Email'=>$Email,
                'WebAddress'=>$web_address,
                'CountryID'=>$countryid,
                'ActiveFlag'=>'1',
                'ParentCompanyID'=>$CompanyID
    );
        
    if($stateid) {
        $addr_data['StateID']=$stateid;
    }
    $this->db->insert('udt_AddressMaster', $addr_data);
        
    $this->db->select('*');
    $this->db->from('udt_AddressMaster');
    $this->db->where('ParentCompanyID', $CompanyID);
    $query=$this->db->get();
    $addressrow=$query->row();
        
    $adress_id=$addressrow->ID;
        
    $parent_grup_data=array(
    'GroupName'=>$parent_company_name,
    'Description'=>$parent_company_desc,
    'ActiveFlag'=>'1',
    'CompanyID'=>$CompanyID,
    'AddressID'=>$adress_id,
    'DateTime'=>date('Y-m-d H:i:s'),
    'IsConversionRecord'=>'',
    'AttachedLogo'=>$Logo,
    'AlignLogo'=>$LogoAlign,
    'EntityID'=>$EntityMasterID,
    'MyParentComment'=>$comment,
    'CreatedBy'=>$UserID
                );
    $ret=$this->db->insert('udt_ParentGroupMaster', $parent_grup_data);
        
    $RowStatus=4;
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_MyParentMaster_H (ParentGroupID,ParentEntityName,ParentDescription,CompanyID,Address1,Address2,Address3,Address4,CountryID,StateID,WebAddress,AttachedLogo,MyParentComment,ActiveFlag,RowStatus,UserID,EntityID,CreatedDate)
		select ID,GroupName,Description,CompanyID,'".$address1."','".$address2."','".$address3."','".$address4."','".$countryid."','".$stateid."','".$web_address."',AttachedLogo,MyParentComment,ActiveFlag,'".$RowStatus."',CreatedBy, EntityID, DateTime
		from cops_admin.udt_ParentGroupMaster where CompanyID='".$CompanyID."'"
    );
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AU_OtherParentCompany_HID (OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,RowStatus)
		select OthParentCompID,IDNumber,Location,OtherDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_OtherParentCompanyID where CompanyID='".$CompanyID."'"
    );
        
    $query3 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentEmailDetails_H (ParentEmailID,EmailID,EmailDescription,CompanyID,RowStatus)
		select ParentEmailID,EmailID,EmailDescription,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentEmailDetails where CompanyID='".$CompanyID."'"
    );
        
    $query4 = $this->db->query(
        "insert into cops_admin.udt_AU_ParentTelephoneDetails_H (ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,RowStatus)
		select ParentTelephoneID,TelephoneType,TeleCountryCode,TeleAreaCode,TeleNumber,CompanyID,'".$RowStatus."'
		from cops_admin.udt_AU_ParentTelephoneDetails where CompanyID='".$CompanyID."'"
    );
        
    if($ret) {
        return 1;
    } else {
        return 0;
    }
}    
    
public function checkEntityType($EntityID)
{
    $type=array('5','6');
    $this->db->select('*');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->where('EntityMasterID', $EntityID);
    $this->db->where_in('EntityTypeID', $type);
    $query=$this->db->get();
    $cnt=$query->num_rows();
    if($cnt > 0) {
        return 1;
    } else {
        return 0;
    }
}
    
public function checkUserApprovalStatus()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query1=$this->db->get();
    return $query1->row();
}
    
public function getDocumentStoreByEntityID()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('udt_AUM_DocumentType_Master.DocumentTypeID,udt_AUM_DocumentType_Master.charterPartyEditableFlag,udt_AUM_DocumentType_Master.DocumentType,udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->where('udt_AUM_DocumentType_Master.OwnerEntityID', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getAttachedInviteeDocument()
{
    $DocumentTypeID=$this->input->post('DocumentTypeID');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getDocumentClauses12()
{
    if($this->input->post()) {
        $DocumentTypeID=$this->input->post('DocumentTypeID');
    }
    if($this->input->get()) {
        $DocumentTypeID=$this->input->get('DocumentTypeID');
    }
    $ids=explode("_", $DocumentTypeID);
        
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where_in('DocumentTypeID', $ids);
    $this->db->order_by('SerialNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getClauseTextByID($ClauseID)
{
    $temp=1;
    $strlen=1;
    $content='';
    while($temp !=0){
        $this->db->select('SUBSTRING(ClauseText, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AUM_DocumentClause');
        $this->db->where('ClauseID', $ClauseID);
        $query1=$this->db->get();
        $result1=$query1->row();
        if($result1->PTR) {
            $content .=$result1->PTR;
            $strlen = $strlen + strlen($result1->PTR);
        }else{
            $temp=0;
        }
    }
    return $content;
}
    
public function getTitleEntityID()
{
    $key=$this->input->post('key');
    $EntityID=$this->input->post('EntityID');
    $stype=$this->input->post('stype');
    $this->db->select('udt_AUM_Document_master.DMID,udt_AUM_Document_master.DocName,udt_AUM_DocumentType_Master.DocumentTypeID');
    $this->db->from('udt_AUM_Document_master');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTitle=udt_AUM_Document_master.DMID');
    $this->db->where('udt_AUM_DocumentType_Master.charterPartyEditableFlag', 1);
    $this->db->where('udt_AUM_Document_master.DocType', $stype);
    $this->db->where('udt_AUM_Document_master.RecoredOwner', $EntityID);
    $this->db->like('udt_AUM_Document_master.DocName', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function getTypeEntityID()
{
    $key=$this->input->post('key');
    $EntityID=$this->input->post('EntityID');
    $this->db->select('Distinct DocType');
    $this->db->from('udt_AUM_Document_master');
    $this->db->where('RecoredOwner', $EntityID);
    $this->db->like('DocType', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentClausesByDocumentTypeID($flag)
{
    $stype=$this->input->post('stype');
    $DocumentTypeID=$this->input->post('stitle');
    $EntityID=$this->input->post('EntityID');
    $DocumentTypeIDs=array();
    if($flag==1) {
        $this->db->select('*');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->where('OwnerEntityID', $EntityID);
        $this->db->where('DocumentType', $stype);
        $query=$this->db->get();
        $rslt=$query->result();
        foreach($rslt as $row) {
            $DocumentTypeIDs[]=$row->DocumentTypeID;
        }
    }
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    if($DocumentTypeID) {
        $this->db->where('DocumentTypeID', $DocumentTypeID);
    } else if($flag==1) {
        $this->db->where_in('DocumentTypeID', $DocumentTypeIDs);
    }
    $this->db->where('RescordOwner', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getTitleByID($DocumentTypeID)
{
    $this->db->select('udt_AUM_Document_master.DocName,udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->where('udt_AUM_DocumentType_Master.DocumentTypeID', $DocumentTypeID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntitySigningUsers($EID)
{
    $Type=$this->input->post('Type');
    $this->db->select('udt_UserMaster.ID,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.EntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    if($Type==1) {
        $this->db->where('udt_UserMaster.SignDigitallyFixtureFlg', 1);
    } else if($Type==2) {
        $this->db->where('udt_UserMaster.SignDigitallyCPFlg', 1);
    }
    $this->db->where('udt_UserMaster.EntityID', $EID);
    $query=$this->db->get();
    return $query->result();
}
    
public function saveShipOwnerEntityData()
{
    $this->db->trans_start();
    extract($this->input->post());
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
        
    $datetime=date('Y-m-d H:i:s');
        
    $data=array(
                'OwnerEntity'=>$EntityMasterID,
                'ShipOwnerEntity'=>$ShipOwnerID,
                'FixtureDigitallySignBy'=>$FixtureNoteSignBy,
                'CpDigitallySignBy'=>$CharterPartySignBy,
                'Comment'=>$ShipOwnerComments,
                'Status'=>$ActiveFlag,
                'CreatedBy'=>$UserID,
                'CreatedDate'=>$datetime
    );
        
    $res=$this->db->insert('udt_AUM_BrokerSigningAuthority', $data);
    $BSA_ID='';
    if($res) {
        $this->db->select('*');
        $this->db->from('udt_AUM_BrokerSigningAuthority');
        $this->db->where('CreatedBy', $UserID);
        $this->db->where('CreatedDate', $datetime);
        $this->db->order_by('BSA_ID', 'desc');
        $query=$this->db->get();
        $br_row=$query->row();
        $BSA_ID=$br_row->BSA_ID;
    }
    $fixtureUsers=array();
    for($i=0; $i<count($fix_users);$i++){
        if($fix_entity[$i]==$EntityMasterID) {
            $fixtureUsers[]=$fix_users[$i];
        }
        $fxdata=array(
        'BSA_ID'=>$BSA_ID,
        'BrokerSigningType'=>1,
        'SigningUserEntity'=>$fix_entity[$i],
        'SigningUserID'=>$fix_users[$i]
                );
        
        $this->db->insert('udt_AUM_BrokerSigningUsers', $fxdata);
    }
    $CpUsers=array();
    for($i=0; $i<count($cp_users);$i++){
        if($cp_entity[$i]==$EntityMasterID) {
            $CpUsers[]=$cp_users[$i];
        }
        $cpdata=array(
        'BSA_ID'=>$BSA_ID,
        'BrokerSigningType'=>2,
        'SigningUserEntity'=>$cp_entity[$i],
        'SigningUserID'=>$cp_users[$i]
                );
        
        $this->db->insert('udt_AUM_BrokerSigningUsers', $cpdata);
    }
        
    if($FixtureNoteSignBy==1 || $FixtureNoteSignBy==3) {
        $document=$_FILES['upload_file'];
                
        for($i=0;$i<count($document['name']);$i++){
            $ext=getExtension($document['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {    
                $nar=explode(".", $document['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document['name'][$i];
                $tmp=$document['tmp_name'][$i];
                $filesize=$document['size'][$i];
                    
                $actual_image_name = 'TopMarx/'.$file;
                    
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                if($AuthenticateFor==1) {
                    for($j=0;$j<count($fixtureUsers);$j++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>1,
                        'AuthenticateFor'=>$AuthenticateFor,
                        'AuthenticateUser'=>$fixtureUsers[$j],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                             
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                } else if($AuthenticateFor==2) {
                    $file_data = array(
                    'BSA_ID'=>$BSA_ID,
                    'AttachmentFor'=>1,
                    'AuthenticateFor'=>$AuthenticateFor,
                    'AuthenticateUser'=>$AuthenticateUser[$i],
                    'UploadFileName'=>$file,
                    'FileSize'=>$filesize,
                    'FileType'=>$ext,
                    'CreatedBy'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                         
                    $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                }
            }
        }
        
    }
        
    if($CharterPartySignBy==1 || $CharterPartySignBy==3) {
        $document1=$_FILES['upload_file_cp'];
                
        for($i=0;$i<count($document1['name']);$i++){
            $ext=getExtension($document1['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {    
                $nar=explode(".", $document1['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document1['name'][$i];
                $tmp=$document1['tmp_name'][$i];
                $filesize=$document1['size'][$i];
                    
                $actual_image_name = 'TopMarx/'.$file;
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                if($AuthenticateForCp==1) {
                    for($j=0;$j<count($CpUsers);$j++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>2,
                        'AuthenticateFor'=>$AuthenticateForCp,
                        'AuthenticateUser'=>$CpUsers[$j],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                } else if($AuthenticateForCp==2) {
                    $file_data = array(
                    'BSA_ID'=>$BSA_ID,
                    'AttachmentFor'=>2,
                    'AuthenticateFor'=>$AuthenticateForCp,
                    'AuthenticateUser'=>$AuthenticateUserCp[$i],
                    'UploadFileName'=>$file,
                    'FileSize'=>$filesize,
                    'FileType'=>$ext,
                    'CreatedBy'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                         
                    $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                }
            }
        }
    }
        
    $this->db->trans_complete();
    return $res;
}
    
public function updateShipownerEntityData()
{
    $this->db->trans_start();
    extract($this->input->post());
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
        
    $data=array(
                'OwnerEntity'=>$EntityMasterID,
                'ShipOwnerEntity'=>$ShipOwnerID,
                'FixtureDigitallySignBy'=>$FixtureNoteSignBy,
                'CpDigitallySignBy'=>$CharterPartySignBy,
                'Comment'=>$ShipOwnerComments,
                'Status'=>$ActiveFlag,
                'CreatedBy'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $this->db->where('BSA_ID', $BSA_ID);
    $res=$this->db->update('udt_AUM_BrokerSigningAuthority', $data);
        
    $fixtureUsers=array();
    $f_cnt=count($fix_users);
    if($f_cnt > 0) {
        $this->db->where('BSA_ID', $BSA_ID);
        $this->db->where('BrokerSigningType', 1);
        $this->db->delete('udt_AUM_BrokerSigningUsers');
    }
    for($i=0; $i<$f_cnt;$i++){
        if($fix_entity[$i]==$EntityMasterID) {
            $fixtureUsers[]=$fix_users[$i];
        }
        $fxdata=array(
        'BSA_ID'=>$BSA_ID,
        'BrokerSigningType'=>1,
        'SigningUserEntity'=>$fix_entity[$i],
        'SigningUserID'=>$fix_users[$i]
                );
        $this->db->insert('udt_AUM_BrokerSigningUsers', $fxdata);
    }
    $CpUsers=array();
    $c_cnt=count($cp_users);
    if($c_cnt >0) {
        $this->db->where('BSA_ID', $BSA_ID);
        $this->db->where('BrokerSigningType', 2);
        $this->db->delete('udt_AUM_BrokerSigningUsers');
    }
        
    for($i=0; $i<$c_cnt;$i++){
        if($cp_entity[$i]==$EntityMasterID) {
            $CpUsers[]=$cp_users[$i];
        }
        $cpdata=array(
        'BSA_ID'=>$BSA_ID,
        'BrokerSigningType'=>2,
        'SigningUserEntity'=>$cp_entity[$i],
        'SigningUserID'=>$cp_users[$i]
                );
        $this->db->insert('udt_AUM_BrokerSigningUsers', $cpdata);
    }
        
    if($OldOwnerEntity != $EntityMasterID) {
        $this->db->where('BSA_ID', $BSA_ID);
        $this->db->delete('udt_AUM_BrokerSigningAttachments');
    }
        
    if($FixtureNoteSignBy==1 || $FixtureNoteSignBy==3) {
        $document=$_FILES['upload_file'];
        for($i=0;$i<count($document['name']);$i++){
            $ext=getExtension($document['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {    
                $nar=explode(".", $document['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document['name'][$i];
                $tmp=$document['tmp_name'][$i];
                $filesize=$document['size'][$i];
                    
                $actual_image_name = 'TopMarx/'.$file;
                    
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                if($AuthenticateFor==1) {
                    for($j=0;$j<count($fixtureUsers);$j++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>1,
                        'AuthenticateFor'=>$AuthenticateFor,
                        'AuthenticateUser'=>$fixtureUsers[$j],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                             
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                    for($k=0;$k<count($fix_users_old);$k++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>1,
                        'AuthenticateFor'=>$AuthenticateFor,
                        'AuthenticateUser'=>$fix_users_old[$k],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                             
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                } else if($AuthenticateFor==2) {
                    $file_data = array(
                    'BSA_ID'=>$BSA_ID,
                    'AttachmentFor'=>1,
                    'AuthenticateFor'=>$AuthenticateFor,
                    'AuthenticateUser'=>$AuthenticateUser[$i],
                    'UploadFileName'=>$file,
                    'FileSize'=>$filesize,
                    'FileType'=>$ext,
                    'CreatedBy'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                }
            }
        }
    }
        
    if($CharterPartySignBy==1 || $CharterPartySignBy==3) {
        $document1=$_FILES['upload_file_cp'];
        for($i=0;$i<count($document1['name']);$i++){
            $ext=getExtension($document1['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {    
                $nar=explode(".", $document1['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document1['name'][$i];
                $tmp=$document1['tmp_name'][$i];
                $filesize=$document1['size'][$i];
                    
                $actual_image_name = 'TopMarx/'.$file;
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                if($AuthenticateForCp==1) {
                    for($j=0;$j<count($CpUsers);$j++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>2,
                        'AuthenticateFor'=>$AuthenticateForCp,
                        'AuthenticateUser'=>$CpUsers[$j],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                    for($k=0;$k<count($cp_users_old);$k++){
                        $file_data = array(
                        'BSA_ID'=>$BSA_ID,
                        'AttachmentFor'=>2,
                        'AuthenticateFor'=>$AuthenticateForCp,
                        'AuthenticateUser'=>$cp_users_old[$k],
                        'UploadFileName'=>$file,
                        'FileSize'=>$filesize,
                        'FileType'=>$ext,
                        'CreatedBy'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                    }
                } else if($AuthenticateForCp==2) {
                    $file_data = array(
                    'BSA_ID'=>$BSA_ID,
                    'AttachmentFor'=>2,
                    'AuthenticateFor'=>$AuthenticateForCp,
                    'AuthenticateUser'=>$AuthenticateUserCp[$i],
                    'UploadFileName'=>$file,
                    'FileSize'=>$filesize,
                    'FileType'=>$ext,
                    'CreatedBy'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                         
                    $this->db->insert('udt_AUM_BrokerSigningAttachments', $file_data);
                }
            }
        }
    }
        
    $this->db->trans_complete();
    return $res;
}
    
public function get_shipowner_entity_data()
{
    $OwnerEntity=$this->input->get('EID');
    $this->db->select('udt_AUM_BrokerSigningAuthority.*,E1.EntityName as OwnerName,E2.EntityName as ShipOwnerName,');
    $this->db->from('udt_AUM_BrokerSigningAuthority');
    $this->db->join('udt_EntityMaster as E1', 'E1.ID=udt_AUM_BrokerSigningAuthority.OwnerEntity');
    $this->db->join('udt_EntityMaster as E2', 'E2.ID=udt_AUM_BrokerSigningAuthority.ShipOwnerEntity');
    if($OwnerEntity) {
        $this->db->where('OwnerEntity', $OwnerEntity);
    }
    $this->db->order_by('udt_AUM_BrokerSigningAuthority.CreatedDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_authorised_shipowner_users($type)
{
    $BSA_ID=$this->input->get('id');
    $this->db->select('udt_AUM_BrokerSigningUsers.*,udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_BrokerSigningUsers');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_BrokerSigningUsers.SigningUserID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_BrokerSigningUsers.SigningUserEntity');
    $this->db->where('BSA_ID', $BSA_ID);
    $this->db->where('BrokerSigningType', $type);
    $qr=$this->db->get();
    return $qr->result();
}
    
public function deleteShipOwnerEntityData()
{
    $BSA_ID=$this->input->post('id');
        
    $this->db->where('BSA_ID', $BSA_ID);
    $ret=$this->db->delete('udt_AUM_BrokerSigningAuthority');
    if($ret) {
        $this->db->where('BSA_ID', $BSA_ID);
        $this->db->delete('udt_AUM_BrokerSigningUsers');
            
        $this->db->where('BSA_ID', $BSA_ID);
        $this->db->delete('udt_AUM_BrokerSigningAttachments');
    }
    return $ret;
}
    
public function getEntityShipOwnerAuthorityById()
{
    $BSA_ID=$this->input->post('BSA_ID');
    $this->db->select('udt_AUM_BrokerSigningAuthority.*,E1.EntityName as OwnerName,E2.EntityName as ShipOwnerName');
    $this->db->from('udt_AUM_BrokerSigningAuthority');
    $this->db->join('udt_EntityMaster as E1', 'E1.ID=udt_AUM_BrokerSigningAuthority.OwnerEntity');
    $this->db->join('udt_EntityMaster as E2', 'E2.ID=udt_AUM_BrokerSigningAuthority.ShipOwnerEntity');
    $this->db->where('BSA_ID', $BSA_ID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getEntityShipOwnerUsersById()
{
    $BSA_ID=$this->input->post('BSA_ID');
    $this->db->select('udt_AUM_BrokerSigningUsers.*, udt_EntityMaster.EntityName, udt_UserMaster.FirstName, udt_UserMaster.LastName');
    $this->db->from('udt_AUM_BrokerSigningUsers');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_BrokerSigningUsers.SigningUserEntity');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_BrokerSigningUsers.SigningUserID');
    $this->db->where('BSA_ID', $BSA_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getEntityShipOwnerAttachmentById()
{
    $BSA_ID=$this->input->post('BSA_ID');
    $this->db->select('udt_AUM_BrokerSigningAttachments.*, udt_UserMaster.FirstName, udt_UserMaster.LastName');
    $this->db->from('udt_AUM_BrokerSigningAttachments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_BrokerSigningAttachments.AuthenticateUser');
    $this->db->where('BSA_ID', $BSA_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getEntityShipownerAttachment()
{
    $BSAttachID=$this->input->post('BSAttachID');
    $this->db->select('*');
    $this->db->from('udt_AUM_BrokerSigningAttachments');
    $this->db->where('BSAttachID', $BSAttachID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function deleteEntityShipownerAttachment()
{
    $BSAttachID=$this->input->post('BSAttachID');
        
    $this->db->where('BSAttachID', $BSAttachID);
    return $this->db->delete('udt_AUM_BrokerSigningAttachments');
}
    
public function deleteEntityShipownerUsers()
{
    $BSU_ID=$this->input->post('BSU_ID');
        
    $this->db->where('BSU_ID', $BSU_ID);
    return $this->db->delete('udt_AUM_BrokerSigningUsers');
}
    
public function getDditableDocumentTypeByEntityId()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('Distinct DocType');
    $this->db->from('udt_AUM_Document_master');
    $this->db->where('RecoredOwner', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getEditableDocumentTitleByEntityId()
{
    $EntityID=$this->input->post('EntityID');
    $stype=$this->input->post('stype');
    $this->db->select('udt_AUM_Document_master.DMID,udt_AUM_Document_master.DocName,udt_AUM_DocumentType_Master.DocumentTypeID');
    $this->db->from('udt_AUM_Document_master');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTitle=udt_AUM_Document_master.DMID');
    $this->db->where('udt_AUM_DocumentType_Master.charterPartyEditableFlag', 1);
    if($stype) {
        $this->db->where('udt_AUM_Document_master.DocType', $stype);
    }
    $this->db->where('udt_AUM_Document_master.RecoredOwner', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixNotTemplateByEntityID()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->where('udt_AUM_ReportTemplate.EntityID', $EntityID);
    $this->db->order_by('udt_AUM_ReportTemplate.CreatedDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixNotTemplateConfigureByTemplateID()
{
    $TempID=$this->input->post('TemplateID');
    $ful_cp=$this->input->post('ful_cp');
    $this->db->select('*');
    $this->db->from('udt_AU_Template');
    $this->db->where('TemplateID', $TempID);
    if($ful_cp==1) {
        $this->db->where('CpCode !=', '');    
    }
    $this->db->order_by('SeqNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function checkShipownerPermission()
{
    $TID=$this->input->post('TID');
    $BrokerSigningType=$this->input->post('SigningType');
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseBrokerUsers');
    $this->db->where('ResponseID', $TID);
    $this->db->where('BrokerSigningType', $BrokerSigningType);
    $this->db->where('Status', 1);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function checkChartererPermission()
{
    $UserID=$this->input->post('UserID');
    $SigningType=$this->input->post('SigningType');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    if($SigningType==1) {
        $this->db->where('SignDigitallyFixtureFlg', 1);
    } else if($SigningType==2) {
        $this->db->where('SignDigitallyCPFlg', 1);
    }
        
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getLeftHeading()
{
    $this->db->select('*');
    $this->db->from('Udt_AUM_Heading');
    $query=$this->db->get();
    return $query->result();
}
    
public function getLeftSubHeading()
{
    $HID=$this->input->post('HID');
    $this->db->select('*');
    $this->db->from('Udt_AUM_SubHeading');
    $this->db->where('HID', $HID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getHelpTextBySubHeading()
{
    $SHID=$this->input->post('SHID');
    $this->db->select('*');
    $this->db->from('Udt_AUM_HelpText');
    $this->db->where('SHID', $SHID);
    $this->db->order_by('HelpTextOrder', 'ASC');
    $this->db->order_by('HTID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function updateHelpText()
{
    $HTID=$this->input->post('HTID');
    $HelpText=$this->input->post('HelpText');
    $data=array('HelpText'=>$HelpText);
    $this->db->where('HTID', $HTID);
    return $this->db->update('Udt_AUM_HelpText', $data);
}
    
public function getBACHistory()
{
    $type=$this->input->post('type');
    $AuctionID=$this->input->post('AuctionID');
    $id=$this->input->post('id');
    $ids=array(0,$id);
    $this->db->select('*');
    $this->db->from('udt_AU_BAC_H');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where_in('CargoLineNum', $ids);
    if($type==1) {
        $this->db->where('TransactionType', 'Brokerage');
    } else if($type==2) {
        $this->db->where('TransactionType', 'Commision');
    } else if($type==3) {
        $this->db->where('TransactionType', 'Others');
    }
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getTidMid()
{
    $RecordOwner=$this->input->post('EntityID');
    $key=$this->input->post('key');
        
    $this->db->select('udt_AUM_Freight.*,udt_EntityMaster.EntityName,udt_AU_Cargo.LpPreferDate,udt_AU_Cargo.Estimate_mt,udt_AU_Cargo.Estimate_Index_mt,udt_UserMaster.EntityID as Owner');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->join('udt_AU_Cargo', 'udt_AU_Cargo.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    if($RecordOwner) {
        $where=" cops_admin.udt_AUM_Freight.TentativeStatus=1 and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_UserMaster.EntityID=".$RecordOwner." or cops_admin.udt_AUM_Freight.ShipOwnerID=".$RecordOwner." ) ";
    }else{
        $where=" cops_admin.udt_AUM_Freight.TentativeStatus=1 ";
    }
    $this->db->where($where);
    if($key) {
        $this->db->like('udt_AUM_Freight.AuctionID', $key, 'after');
    }
    $this->db->order_by('udt_AUM_Freight.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_email()
{
    $key=$this->input->post('key');
    $EntityID=$this->input->post('EntityID');
    $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
    $this->db->from('Udt_UserMaster');
    $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
    $this->db->where('Udt_UserMaster.EntityID', $EntityID);
    $this->db->like('Udt_AddressMaster.Email', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixtureDocumentByTid()
{
    $tid=$this->input->post('tid');
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $tid);
    $this->db->order_by('FixtureID', 'DESC');
    $query=$this->db->get();
    $rslt=$query->row();
    $FixtureID=$rslt->FixtureID;
        
        
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(FixtureNote, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('udt_AU_AuctionFixture.FixtureID', $FixtureID);
        $query=$this->db->get();
        $result=$query->row();
        if($result->PTR) {
            $content .=$result->PTR;
            $strlen = $strlen + strlen($result->PTR);
        }else{
            $temp=0;
        }
    }
    return $content;
}
    
public function getChaterPartyDocumentByTid()
{
    $tid=$this->input->post('tid');
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('ResponseID', $tid);
    $this->db->order_by('DocumentationID', 'DESC');
    $query=$this->db->get();
    $rslt=$query->row();
        
    if($rslt->EditableFlag==0) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$rslt->CharterPartyPdf, 3600);
        $data[]=$url;
        $data[]=1;
        return $data;
    }
        
    $DocumentationID=$rslt->DocumentationID;
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
    $query=$this->db->get();
    $result=$query->result();
        
    $i=0;
    foreach($result as $row){
            
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(AllClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('udt_AuctionMainClauses.AuctionMainClauseID', $row->AuctionMainClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    $data[]=$content;
    $data[]=2;
    return $data;
        
}
    
public function sendEmailFnCp($content,$EditableFlag,$url)
{
    extract($this->input->post());
    $this->load->library('email');
    $re=0;
    $sent_flag=0;
    $status='';
    $fix_row=$this->getFitureRowByResponseID($tid);
    $FixtureVersion=$fix_row->FixtureVersion;
    if($fix_row->Status==1) {
        $status='Discussion';
    }else if($fix_row->Status==2) {
        $status='Fixture Complete';
    }else{
        $status='Closed';
    }
        
    for($i=0;$i<count($ToEmail);$i++) {
            
        $DocumentType='';    
        if($document_type==1) {
            $DocumentType="Fixture Note";
        } else if($document_type==2) {
            $DocumentType="Charter Party";
        }
            
        $content1='';
        $content1 .='<b>From: '.$FromEmail.'</b><br>';
        $content1 .='<b>To: '.$ToEmail[$i].'</b><br>';
        $content1 .='<b>DateTime : '.date('Y-m-d H:i:s').'</b><br>';
        $content1 .='<b>Subject : '.$mid_tid.'</b><br>';
        $content1 .='<b>Version : '.$FixtureVersion.'</b><br>';
        $content1 .='<b>Status : '.$status.'</b><br>';
        $content1 .='<br>';
            
        $content2=$content1.$content;
            
        $ToEmail1 = trim($ToEmail[$i]);
        $config['protocol']    = 'smtp';
        $config['smtp_host']   = 'higroove.com';
        $config['smtp_port']   = '25';
        $config['smtp_timeout']= '7';
        $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
        $config['smtp_pass']   = 'a_WkTq{L2~=p';
        $config['charset']     = 'utf-8';
        $config['newline']     = "\r\n";
        $config['mailtype']    = 'html'; // or html
        if($cnflag==1) {
            $this->email->initialize($config);
            
            $this->email->clear();
            $this->email->from('admin@iaeglobalnetwork.com');
            $this->email->to($ToEmail1);
            $this->email->subject($DocumentType);
            $this->email->message($content2);
            $sent_flag=$this->email->send();
        }
            
            $emailcnt=0;
        if($sent_flag==1) {
            $emailcnt=1;
        }
            
            $data=array(
                    'FromEmail'=>$FromEmail,
                    'ToEmail'=>$ToEmail[$i],
                    'ToEmailID'=>$ToEmailID[$i],
                    'MIDTID'=>$mid_tid,
                    'DocumentType'=>$document_type,
                    'TID'=>$tid,
                    'Comment'=>$Comment,
                    'UserID'=>$UserID,
                    'EntityID'=>$EntityID,
                    'SentDate'=>date('Y-m-d H:i:s'),
                    'Content'=>$content2,
                    'sent_flag'=>$sent_flag,
                    'version'=>$FixtureVersion,
                    'status'=>$status,
                    'EditableFlag'=>$EditableFlag,
                    'url'=>$url,
                    'SendCount'=>$emailcnt,
                    );
                    
            $re=$this->db->insert('Udt_AUM_SentEmail', $data);
    }
    return $sent_flag;
}
    
public function getFitureRowByResponseID($ResponseID)
{
    $this->db->select('Status,FixtureVersion');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->Order_by('FixtureID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getSentEmail()
{
    $EntityID=$this->input->get('EntityID');
    $UserID=$this->input->get('UserID');
    $this->db->select('Udt_AUM_SentEmail.*,udt_AUM_Freight.EntityID as InvID,udt_AUM_Freight.ShipOwnerID,udt_UserMaster.EntityID as ToEntityID');
    $this->db->from('Udt_AUM_SentEmail');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=Udt_AUM_SentEmail.TID');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=Udt_AUM_SentEmail.ToEmailID', 'left');
    if($EntityID) {
        $this->db->where('Udt_AUM_SentEmail.EntityID', $EntityID);
    }
    if($UserID) {
        $this->db->where('Udt_AUM_SentEmail.UserID', $UserID);
    }
    $this->db->order_by('Udt_AUM_SentEmail.SentDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function resendEmail()
{
    $SEID=$this->input->post('SEID');
    $temp=1;
    $strlen=1;
    $content='';
    $this->db->select('SEID,FromEmail,ToEmail,ToEmailID,MIDTID,DocumentType,TID,Comment,UserID,EntityID,SentDate,sent_flag,version,status,EditableFlag,url,SendCount');
    $this->db->from('Udt_AUM_SentEmail');
    $this->db->where('Udt_AUM_SentEmail.SEID', $SEID);
    $query=$this->db->get();
    $result=$query->row();
    while($temp !=0){
        $this->db->select('SUBSTRING(Content, '.$strlen.', 1000) as PTR');
        $this->db->from('Udt_AUM_SentEmail');
        $this->db->where('Udt_AUM_SentEmail.SEID', $SEID);
        $query1=$this->db->get();
        $result1=$query1->row();
        if($result1->PTR) {
            $content .=$result1->PTR;
            $strlen = $strlen + strlen($result1->PTR);
        }else{
            $temp=0;
        }
    }
            
    $this->load->library('email');
    $config['protocol']    = 'smtp';
    $config['smtp_host']   = 'higroove.com';
    $config['smtp_port']   = '25';
    $config['smtp_timeout']= '7';
    $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
    $config['smtp_pass']   = 'a_WkTq{L2~=p';
    $config['charset']     = 'utf-8';
    $config['newline']     = "\r\n";
    $config['mailtype']    = 'html'; // or html
    $this->email->initialize($config);
            
    $DocumentType='';    
    if($result->DocumentType==1) {
        $DocumentType="Fixture Note";
    } else if($result->DocumentType==2) {
        $DocumentType="Charter Party";
    }
            
    $cntn='';
            
    if($result->SendCount >= 1) {
        $cntn='<h3><b style="color: blue;">Duplicate copy of previous email</b></h3><br>';
    } 
    $content1=$cntn.$content;
    $this->email->clear();
    $this->email->from('admin@iaeglobalnetwork.com');
    $this->email->to($result->ToEmail);
    $this->email->subject($DocumentType);
    $this->email->message($content1);
    $sent_flag=$this->email->send();
            
            
    if($sent_flag==1) {
        if($result->SendCount==0) {
            $data=array('sent_flag'=>1,'SendCount'=>1);
            $this->db->where('SEID', $result->SEID);
            $this->db->update('Udt_AUM_SentEmail', $data);
        } else {
            $emailcnt=($result->SendCount+1);
            $data=array('FromEmail'=>$result->FromEmail,
             'ToEmail'=>$result->ToEmail,
             'ToEmailID'=>$result->ToEmailID,
             'MIDTID'=>$result->MIDTID,
             'DocumentType'=>$result->DocumentType,
             'TID'=>$result->TID,
             'Comment'=>$result->Comment,
             'UserID'=>$result->UserID,
             'EntityID'=>$result->EntityID,
             'version'=>$result->version,
             'status'=>$result->status,
             'EditableFlag'=>$result->EditableFlag,
             'url'=>$result->url,
             'Content'=>$content1,
             'SentDate'=>date('Y-m-d H:i:s'),
             'sent_flag'=>$sent_flag,
             'SendCount'=>$emailcnt
            );
            $this->db->insert('Udt_AUM_SentEmail', $data);
        }
    }
    return $sent_flag;
        
}
public function getEmailBySEID()
{
    if($this->input->post()) {
        $SEID=$this->input->post('SEID');
    } else {
        $SEID=$this->input->get('SEID');
    }
    $temp=1;
    $strlen=1;
    $content='';
    $this->db->select('SEID,FromEmail,ToEmail,MIDTID,DocumentType,TID,Comment,UserID,EntityID,SentDate,sent_flag,version,status');
    $this->db->from('Udt_AUM_SentEmail');
    $this->db->where('Udt_AUM_SentEmail.SEID', $SEID);
    $query=$this->db->get();
    $result=$query->row();
        
    while($temp !=0){
        $this->db->select('SUBSTRING(Content, '.$strlen.', 1000) as PTR');
        $this->db->from('Udt_AUM_SentEmail');
        $this->db->where('Udt_AUM_SentEmail.SEID', $SEID);
        $query1=$this->db->get();
        $result1=$query1->row();
        if($result1->PTR) {
            $content .=$result1->PTR;
            $strlen = $strlen + strlen($result1->PTR);
        }else{
            $temp=0;
        }
    }
        
    return $content;
        
}
    
public function getUserEmailById()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
    $this->db->from('Udt_UserMaster');
    $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
    $this->db->where('Udt_UserMaster.ID', $UserID);
    $query=$this->db->get();
    return $query->row();
}
    
    
public function getRecordOwnerEmail()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('UserID');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $reslt=$query->row();
        
    $UserID=$reslt->UserID;
    $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
    $this->db->from('Udt_UserMaster');
    $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
    $this->db->where('Udt_UserMaster.ID', $UserID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getShipOwnerEmail()
{
    $ResponseID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $document_type=$this->input->post('document_type');
    $this->db->select('EntityID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $reslt=$query->row();
        
    $this->db->select('UserMasterID');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $reslt->EntityID);
    $this->db->where('InviteeRole', 5);
    $query=$this->db->get();
    $rslt=$query->result();
        
    if(count($rslt)<1) {
        $this->db->select('SigningUserID');
        $this->db->from('udt_AU_ResponseBrokerUsers');
        $this->db->where('SigningUserEntity !=', $reslt->EntityID);
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('BrokerSigningType', $document_type);
        $query=$this->db->get();
        $rslt1=$query->result();
        $UserID=array();
        foreach($rslt1 as $row1) {
            $UserID[]=$row1->SigningUserID;
        }
            
        $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
        $this->db->from('Udt_UserMaster');
        $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
        $this->db->where_in('Udt_UserMaster.ID', $UserID);
        $query=$this->db->get();
        return $query->result();
        
    } else {
        $UserID=array();
        foreach($rslt as $row) {
            $UserID[]=$row->UserMasterID;
        }
        $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
        $this->db->from('Udt_UserMaster');
        $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
        $this->db->where_in('Udt_UserMaster.ID', $UserID);
        $query=$this->db->get();
        return $query->result();
    }
}
    
public function getShipBrokerEmail()
{
    $ResponseID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $document_type=$this->input->post('document_type');
    $this->db->select('EntityID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $reslt=$query->row();
        
    $this->db->select('UserMasterID');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $reslt->EntityID);
    $this->db->where('InviteeRole', 6);
    $query=$this->db->get();
    $rslt=$query->result();
        
    $UserID=array();
    foreach($rslt as $row) {
        $UserID[]=$row->UserMasterID;
    }
    if(count($UserID)>0) {
        $this->db->select('Udt_UserMaster.ID,Udt_AddressMaster.Email');
        $this->db->from('Udt_UserMaster');
        $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id');
        $this->db->where_in('Udt_UserMaster.ID', $UserID);
        $query=$this->db->get();
        return $query->result();
    } else {
        return 0;
    }
}
    
public function checkUserRecordWithEmail()
{
    $this->load->library('email');
    $userID=$this->input->post('userID');
    $this->db->select('udt_UserMaster.ID, udt_UserMaster.SecretQuestionID, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.Telephone2');
    $this->db->from('Udt_UserMaster');
    $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id', 'left');
    $this->db->where('Udt_UserMaster.LoginID', $userID);
    $query=$this->db->get();
    $usr_row=$query->row();
    if($usr_row) {
        if($usr_row->Email) {
            $config['protocol']    = 'smtp';
            $config['smtp_host']   = 'higroove.com';
            $config['smtp_port']   = '25';
            $config['smtp_timeout']= '7';
            $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
            $config['smtp_pass']   = 'a_WkTq{L2~=p';
            $config['charset']     = 'utf-8';
            $config['newline']     = "\r\n";
            $config['mailtype']    = 'html'; // or html
            $this->email->initialize($config);
            $six_digit_random_number = mt_rand(100000, 999999);
            $content1='OTP : '.$six_digit_random_number;
            $subject='OTP to password reset.';
            $this->email->clear();
            $this->email->from('admin@iaeglobalnetwork.com');
            $this->email->to($usr_row->ToEmail);
            $this->email->subject($subject);
            $this->email->message($content1);
            $sent_flag=$this->email->send();
                
            $email=$usr_row->ToEmail;
            $mobile=$usr_row->Telephone1;
            $data['SecretQuestionID']=$usr_row->SecretQuestionID;
                
            $this->db->select('*');
            $this->db->from('udt_SecretQuestion');
            $this->db->where('ID', $usr_row->SecretQuestionID);
            $qry=$this->db->get();
            $rw=$qry->row();
            if($rw) {
                $data['SecretQuestion']=$rw->Description;
            } else {
                $data['SecretQuestion']='';
            }
                
            $sent_flag=1;
            if($sent_flag==1) {
                $this->session->set_userdata('start', time());
                $this->session->set_userdata('expire', ($this->session->userdata('start')+(1 * 60)));
                $this->session->set_userdata('otp', $six_digit_random_number);
                $data['flg']=1;
                $len=strlen($email);
                if($len > 15) {
                    $first_part = substr($email, 0, 5);
                    $last_part = substr($email, -9);
                    $data['email']=$first_part.'*****'.$last_part;
                } else {
                    $first_part = substr($email, 0, 1);
                    $last_part = substr($email, -4);
                    $data['email']=$first_part.'***'.$last_part;
                }
                $len1=strlen($mobile);
                if($len1 > 6) {
                    $first_part1 = substr($mobile, 0, 3);
                    $last_part1 = substr($mobile, -3);
                    $data['mobile']=$first_part1.'*****'.$last_part1;
                } else {
                    $data['mobile']='';
                }
            } else {
                $data['flg']=4;
            }
                
                
        } else {
            $data['flg']=3;
        }
            
    } else {
        $data['flg']=2;
    }
        
    return $data;
}
    
public function updateUserForgetPassword()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('Udt_UserMaster');
    $this->db->where('LoginID', $login_id);
    $query=$this->db->get();
    $usr_row=$query->row();
        
    $effectiveDate = date('Y-m-d', strtotime("+$usr_row->PwdChangeInterval months"));
        
    $this->db->insert('udt_AU_UserPreviousPasswords', array('UserID'=>$usr_row->ID,'UserPassword'=>$pswd,'CreatedDate'=>date('Y-m-d H:i:s')));
        
    $this->db->where('LoginID', $login_id);
    return $this->db->update('Udt_UserMaster', array('Password'=>$pwd,'PasswordExpiryDate'=>$effectiveDate,'ChangePasswordFlag'=>0,'EmailSendFlag'=>0));
        
}
    
public function verifySecretAnswer()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('Udt_UserMaster');
    $this->db->where('Udt_UserMaster.LoginID', $login_id);
    $query=$this->db->get();
    $usr_row=$query->row();
    if($usr_row) {
        if($usr_row->SecretAnswer ==$SecretAnswer) {
            return 1;
        } else {
            return 2;
        }
    } else {
        return 2;
    }
}
    
public function getCustomerAdmin()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('Udt_UserMaster');
    $this->db->where('Udt_UserMaster.EntityID', $EntityID);
    $this->db->where('Udt_UserMaster.UserType', 'CA');
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
    
public function checkUserUnlockAccount()
{
    $this->load->library('email');
    $userID=$this->input->post('userID');
        
    $where=" convert(binary,LoginID)=convert(binary,'$userID')";
    $this->db->select('udt_UserMaster.ID,udt_UserMaster.WrongPasswordCount, udt_UserMaster.SecretQuestionID, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.Telephone2');
    $this->db->from('Udt_UserMaster');
    $this->db->join('Udt_AddressMaster', 'Udt_UserMaster.OfficialAddressID=Udt_AddressMaster.id', 'left');
    $this->db->where($where);
    $query=$this->db->get();
    $usr_row=$query->row();
    if(count($usr_row)) {
        if($usr_row->WrongPasswordCount > 2) {
            $email='';
            if($usr_row->Email) {
                $email=$usr_row->Email;
            } else {
                $this->db->select('*');
                $this->db->from('udt_AU_UserEmails');
                $this->db->where('UserEmailID', $usr_row->ID);
                $query1=$this->db->get();
                $email_row=$query1->row();
                if($email_row) {
                    $email=$email_row->UserEmail;
                }
            }
                
            if($email) {
                $config['protocol']    = 'smtp';
                $config['smtp_host']   = 'higroove.com';
                $config['smtp_port']   = '25';
                $config['smtp_timeout']= '7';
                $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
                $config['smtp_pass']   = 'a_WkTq{L2~=p';
                $config['charset']     = 'utf-8';
                $config['newline']     = "\r\n";
                $config['mailtype']    = 'html'; // or html
                $this->email->initialize($config);
                $six_digit_random_number = mt_rand(100000, 999999);
                $content1='OTP : '.$six_digit_random_number;
                $subject='OTP to password reset.';
                $this->email->clear();
                $this->email->from('admin@iaeglobalnetwork.com');
                    
                $this->email->to($email);
                $this->email->subject($subject);
                $this->email->message($content1);
                $sent_flag=$this->email->send();
                    
                $mobile=$usr_row->Telephone1;
                $data['SecretQuestionID']=$usr_row->SecretQuestionID;
                $sent_flag=1;
                if($sent_flag==1) {
                    $this->session->set_userdata('start', time());
                    $this->session->set_userdata('expire', ($this->session->userdata('start')+(1 * 60)));
                    $this->session->set_userdata('otp', $six_digit_random_number);
                    $this->session->set_userdata('userID', $userID);
                    $data['flg']=1;
                    $len=strlen($email);
                    if($len > 15) {
                        $first_part = substr($email, 0, 5);
                        $last_part = substr($email, -9);
                        $data['email']=$first_part.'*****'.$last_part;
                    } else {
                        $first_part = substr($email, 0, 1);
                        $last_part = substr($email, -4);
                        $data['email']=$first_part.'***'.$last_part;
                    }
                        $len1=strlen($mobile);
                    if($len1 > 6) {
                        $first_part1 = substr($mobile, 0, 3);
                        $last_part1 = substr($mobile, -3);
                        $data['mobile']=$first_part1.'*****'.$last_part1;
                    } else {
                        $data['mobile']='';
                    }
                        $data['flg']=1;
                } else {
                        $data['flg']=5;
                }
            } else {
                $data['flg']=4;
            }
        } else {
            $data['flg']=3;
        }
    } else {
        $data['flg']=2;
    }
        
    return $data;
}
    
public function getUserContentByLoginID()
{
    $login_id=$this->input->post('login_id');
    $this->db->select('udt_UserMaster.*, udt_EntityMaster.EntityName, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3,udt_AddressMaster.Address4, udt_AddressMaster.City, udt_AddressMaster.Telephone1,udt_AddressMaster.CountryID,udt_CountryMaster.Description as C_Description, udt_AddressMaster.StateID, udt_StateMaster.Description as S_Description, udt_AddressMaster.ZipCode, udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'Left');
    $this->db->where('LoginID', $login_id);
    $qury=$this->db->get();
    return $qury->row();
}
    
public function saveRecordForUnlockUser()
{
    extract($this->input->post());
    $this->db->select('udt_UserMaster.*, udt_EntityMaster.EntityName, udt_AddressMaster.Address1, udt_AddressMaster.Address2, udt_AddressMaster.Address3,udt_AddressMaster.Address4, udt_AddressMaster.City, udt_AddressMaster.Telephone1,udt_AddressMaster.CountryID,udt_CountryMaster.Description as C_Description, udt_AddressMaster.StateID, udt_StateMaster.Description as S_Description, udt_AddressMaster.ZipCode, udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'Left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'Left');
    $this->db->where('udt_UserMaster.ID', $UserID);
    $qury=$this->db->get();
    $usr_row=$qury->row();
        
    $listAlpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $listNonAlpha = ',;:!?.$/*-+&@_+;./*&?$-!,';
    $numAlpha=8;
    $numNonAlpha=5;
    $pwd=str_shuffle(
        substr(str_shuffle($listAlpha), 0, $numAlpha) .
          substr(str_shuffle($listNonAlpha), 0, $numNonAlpha)
    );     
        
    $data=array(
    'EntityID'=>$usr_row->EntityID,
    'username'=>$usr_row->LoginID,
    'pwd'=>$usr_row->Password,
    'title'=>$usr_row->TitleID,
    'fname'=>$usr_row->FirstName,
    'mname'=>$usr_row->MiddleName,
    'lname'=>$usr_row->LastName,
    'address1'=>$usr_row->Address1,
    'address2'=>$usr_row->Address2,
    'address3'=>$usr_row->Address3,
    'address4'=>$usr_row->Address4,
    'country'=>$usr_row->C_Description,
    'countryid'=>$usr_row->CountryID,
    'state'=>$usr_row->S_Description,
    'stateid'=>$usr_row->StateID,
    'city'=>$usr_row->City,
    'zipcode'=>$usr_row->ZipCode,
    'email_id'=>$usr_row->Email,
    'mobile_no'=>$usr_row->Telephone1,
    'add_date'=>date('Y-m-d H:i:s'),
    'comment'=>$comment,
    'RegisterFor'=>2,
    'status'=>0
                );
    $ret=$this->db->insert('Udt_AUM_NewUser', $data);
        
    $this->db->select('NUID');
    $this->db->from('Udt_AUM_NewUser');
    $this->db->order_by('NUID', 'DESC');
    $query=$this->db->get();
    $rslt=$query->row();
    $NUID=$rslt->NUID;
        
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserEmails');
    $this->db->where('UserID', $UserID);
    $qury=$this->db->get();
    $emailRecord=$qury->result();
    foreach($emailRecord as $er){
        if($er->UseDefaultFlag==0) {
            $edata=array('NUID'=>$NUID,'Email'=>$er->UserEmail);
            $this->db->insert('Udt_AUM_NewUserEmail', $edata);
        }
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserTelephones');
    $this->db->where('UserID', $UserID);
    $qury=$this->db->get();
    $teleRecord=$qury->result();
    foreach($teleRecord as $tr){
        $telephone1=$tr->CountryCode.' '.$tr->AreaCode.' '.$tr->TeleNumber;
        if($usr_row->Telephone1 !=$telephone1) {
            $edata=array('NUID'=>$NUID,'Telephone'=>$telephone1);
            $this->db->insert('Udt_AUM_NewUserTelephone', $edata);
        }
    }
        
    return $ret;
}
    
    
public function saveNewUser()
{
    extract($this->input->post());
    $listAlpha = 'abcdefghijklmnopqrstuvwxyz';
    $listAlpha1 = 'abcdefghijklmnopqrstuvwxyz';
    $listNonAlpha = '0123456789';
    $numAlpha=8;
    $numNonAlpha=5;
    $pwd=str_shuffle(
        substr(str_shuffle($listAlpha), 0, $numAlpha) .
          substr(str_shuffle($listNonAlpha), 0, $numNonAlpha)
    );     
    $username=$fname.'.'.$lname.'.'.substr($mobile_no, -5);
        
    $data=array(
    'EntityID'=>$EntityMasterID,
    'username'=>$username,
    'pwd'=>$pwd,
    'title'=>$title,
    'fname'=>$fname,
    'mname'=>$mname,
    'lname'=>$lname,
    'address1'=>$address1,
    'address2'=>$address2,
    'address3'=>$address3,
    'address4'=>$address4,
    'country'=>$autocompleteCountry,
    'countryid'=>$countryid,
    'state'=>$autocompleteState,
    'stateid'=>$stateid,
    'city'=>$city,
    'zipcode'=>$zipcode,
    'email_id'=>$email_id,
    'mobile_no'=>$mobile_no,
    'add_date'=>date('Y-m-d H:i:s'),
    'comment'=>$comment,
    'RegisterFor'=>1,
    'status'=>0
                );
    $ret=$this->db->insert('Udt_AUM_NewUser', $data);
        
    $this->db->select('NUID');
    $this->db->from('Udt_AUM_NewUser');
    $this->db->order_by('NUID', 'DESC');
    $query=$this->db->get();
    $rslt=$query->row();
    $NUID=$rslt->NUID;    
        
    for($i=0;$i<count($secondary_emal);$i++) {
        $edata=array('NUID'=>$NUID,'Email'=>$secondary_emal[$i]);
        $this->db->insert('Udt_AUM_NewUserEmail', $edata);
    }
        
    for($i=0;$i<count($secondary_tel);$i++) {
        $edata=array('NUID'=>$NUID,'Telephone'=>$secondary_tel[$i]);
        $this->db->insert('Udt_AUM_NewUserTelephone', $edata);
    }
        
    if($NUID) {
        $this->load->library('email');
            
        $this->db->select('*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('EntityID', $EntityMasterID);
        $this->db->where('MessageType', 'admin');
        $this->db->where('Events', 'new_user_existing_entity');
        $this->db->where('OnPage', 'login');
        $query=$this->db->get();
        $result=$query->result();
        $msgDetails='';
        foreach($result as $r) {
            $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email,udt_AddressMaster.Telephone1');
            $this->db->from('udt_UserMaster');
            $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
            $this->db->where('udt_UserMaster.ID', $r->ForUserID);
            $query=$this->db->get();
            $u_row=$query->row();
            if($u_row->Email) {
                $config['protocol']    = 'smtp';
                $config['smtp_host']   = 'higroove.com';
                $config['smtp_port']   = '25';
                $config['smtp_timeout']= '7';
                $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
                $config['smtp_pass']   = 'a_WkTq{L2~=p';
                $config['charset']     = 'utf-8';
                $config['newline']     = "\r\n";
                $config['mailtype']    = 'html'; // or html
                    
                $this->email->initialize($config);
                    
                $content1 = $r->Message.'<br>';
                $content1 .= 'New user request received from user : '.$fname.' '.$lname;
                $subject = 'New user request';
                $this->email->clear();
                $this->email->from('admin@iaeglobalnetwork.com');
                $this->email->to($u_row->ToEmail);
                $this->email->subject($subject);
                $this->email->message($content1);
                $sent_flag=$this->email->send();
            }
        }
    }
    return $ret;
}
    
public function getNewUsers()
{
    $EntityID=$this->input->get('EntityID');
    $this->db->select('Udt_AUM_NewUser.*,udt_EntityMaster.EntityName');
    $this->db->from('Udt_AUM_NewUser');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=Udt_AUM_NewUser.EntityID', 'left');
    if($EntityID) {
        $this->db->where('EntityID', $EntityID);
    }
    $this->db->order_by('NUID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getNewUserById()
{
    $nuid=$this->input->post('nuid');
    $this->db->select('Udt_AUM_NewUser.*,udt_EntityMaster.EntityName');
    $this->db->from('Udt_AUM_NewUser');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=Udt_AUM_NewUser.EntityID');
    $this->db->where('NUID', $nuid);
    $query=$this->db->get();
    return $query->row();
}
    
public function getNewUserEmailById()
{
    $nuid=$this->input->post('nuid');
    $this->db->select('*');
    $this->db->from('Udt_AUM_NewUserEmail');
    $this->db->where('NUID', $nuid);
    $query=$this->db->get();
    return $query->result();
}
    
public function getNewUserPhoneById()
{
    $nuid=$this->input->post('nuid');
    $this->db->select('*');
    $this->db->from('Udt_AUM_NewUserTelephone');
    $this->db->where('NUID', $nuid);
    $query=$this->db->get();
    return $query->result();
}
    
public function updateNewUser()
{
    extract($this->input->post());
    $data=array(
    'EntityID'=>$EntityMasterID,
    'username'=>$username,
    'pwd'=>$pwd,
    'title'=>$title,
    'fname'=>$fname,
    'mname'=>$mname,
    'lname'=>$lname,
    'address1'=>$address1,
    'address2'=>$address2,
    'address3'=>$address3,
    'address4'=>$address4,
    'country'=>$autocompleteCountry,
    'countryid'=>$countryid,
    'state'=>$autocompleteState,
    'stateid'=>$stateid,
    'city'=>$city,
    'zipcode'=>$zipcode,
    'email_id'=>$email_id,
    'mobile_no'=>$mobile_no,
    'add_date'=>date('Y-m-d H:i:s'),
    'comment'=>$comment,
    'status'=>$approve
                );
                
    $this->db->where('NUID', $nuid);
    $ret=$this->db->update('Udt_AUM_NewUser', $data);
        
    $NUID=$nuid;    
        
    for($i=0;$i<count($secondary_emal);$i++) {
        $edata=array('NUID'=>$NUID,'Email'=>$secondary_emal[$i]);
        $this->db->where('NUEID', $NUEID[$i]);
        $this->db->update('Udt_AUM_NewUserEmail', $edata);
    }
        
    for($i=0;$i<count($secondary_tel);$i++) {
        $edata=array('NUID'=>$NUID,'Telephone'=>$secondary_tel[$i]);
        $this->db->where('NUTID', $NUTID[$i]);
        $this->db->update('Udt_AUM_NewUserTelephone', $edata);
    }
    return $ret;
}
    
public function addNewUser()
{
    extract($this->input->post());
        
    $adata=array(
    'Address1'=>$address1,
    'Address2'=>$address2,
    'Address3'=>$address3,
    'Address4'=>$address4,
    'CountryID'=>$countryid,
    'StateID'=>$stateid,
    'City'=>$city,
    'ZipCode'=>$zipcode,
    'Email'=>$email_id,
    'Telephone1'=>$mobile_no,
    'ActiveFlag'=>1
                );
    $this->db->insert('Udt_AddressMaster', $adata);
        
    $this->db->select('ID');
    $this->db->from('Udt_AddressMaster');
    $this->db->where('Email', $email_id);
    $this->db->order_by('ID', 'DESC');
    $query=$this->db->get();
    $add_id=$query->row()->ID;
        
    $udata=array(
    'EntityID'=>$EntityMasterID,
    'TitleID'=>$title,
    'FirstName'=>trim($fname, " "),
    'MiddleName'=>$mname,
    'LastName'=>trim($lname, " "),
    'LoginID'=>$username,
    'Password'=>$pwd,
    'ActiveFlag'=>0,
    'ValidAccessFromDate'=>date('Y-m-d'),
    'ValidAccessToDate'=>date('Y-m-d', strtotime('+3 months')),
    'PwdChangeInterval'=>3,
    'IsEnitityAddress'=>0,
    'SendRecordDetailFlag'=>1,
    'CargoInvitationFlag'=>0,
    'ApproveFixtureFinalFlg'=>0,
    'SignFixtureFinalFlg'=>0,
    'SignDigitallyFixtureFlg'=>0,
    'ApproveCPFinalFlg'=>0,
    'SignCPFinalFlg'=>0,
    'SignDigitallyCPFlg'=>0,
    'ApproveTechVettingFlg'=>0,
    'ApproveBusVettingFinalFlg'=>0,
    'ApproveCounterPartyFlg'=>0,
    'ApproveComplianceFlg'=>0,
    'ApproveQuoteAuthFlg'=>0,
    'LiftCharterSubjectFlg'=>0,
    'CreateInvSubjectFlg'=>0,
    'LiftInvSubjectFlg'=>0,
    'LiftInvSubjectFlgByCharter'=>0,
    'ApproveDocStoreFlg'=>0,
    'CreatedBy'=>$UserID,
    'DateTime'=>date('Y-m-d H:i:s'),
    'OfficialAddressID'=>$add_id,
    'SecretQuestionID'=>1,
    'PasswordExpires'=>1,
    'ChangePasswordFlag'=>1,
    'EmailSendFlag'=>0,
    "addedByComp"=>$OwnerEntityID,
    "addedByUsr"=>$userName1,
    'UserType'=>'U',
    'SecretAnswer'=>'Ans by user'
                );
    $this->db->insert('udt_UserMaster', $udata);
        
    $this->db->select('ID');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $username);
    $this->db->order_by('ID', 'DESC');
    $query=$this->db->get();
    $UserID1=$query->row()->ID;

    $this->db->insert('udt_AU_UserPreviousPasswords', array('UserID'=>$UserID1,'UserPassword'=>$pwd,'CreatedDate'=>date('Y-m-d H:i:s')));
        
    $edata=array('UserID'=>$UserID1,'UserEmail'=>$email_id,'UseDefaultFlag'=>1,'AddEmailInFlg'=>1);
    $this->db->insert('udt_AU_UserEmails', $edata);
        
    for($i=0;$i<count($secondary_emal);$i++) {
        $edata=array('UserID'=>$UserID1,'UserEmail'=>$secondary_emal[$i],'EmailDescription'=>'','UseDefaultFlag'=>0,'AddEmailInFlg'=>0);
        $this->db->insert('udt_AU_UserEmails', $edata);
    }
        
    $tdata=array('UserID'=>$UserID1,'TeleNumber'=>$mobile_no);
    $this->db->insert('udt_AU_UserTelephones', $tdata);
        
    for($i=0;$i<count($secondary_tel);$i++) {
        $tdata=array('UserID'=>$UserID1,'TeleNumber'=>$secondary_tel[$i],'TeleType'=>'','CountryCode'=>'','AreaCode'=>'');
        $this->db->insert('udt_AU_UserTelephones', $tdata);
    }
        
    if($email_id) {
        $this->load->library('email');
        $subj='New user request approved';
        $message ='hello,<br/>';
        $message .='Your are regisered in auomni system.<br/>';
        $message .='Please click here to get your temporary login details : ';
        $message .='<a href="'.base_url().'index.php/send-user-temporary-details?id='.$UserID1.'">Click here.</a>';
            
        $config['protocol']    = 'smtp';
        $config['smtp_host']   = 'higroove.com';
        $config['smtp_port']   = '25';
        $config['smtp_timeout']= '7';
        $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
        $config['smtp_pass']   = 'a_WkTq{L2~=p';
        $config['charset']     = 'utf-8';
        $config['newline']     = "\r\n";
        $config['mailtype']    = 'html'; // or html
            
        $this->email->initialize($config);
            
        $this->email->clear();
        $this->email->from('admin@iaeglobalnetwork.com');
        $this->email->to($email_id);
        $this->email->subject($subj);
        $this->email->message($message);
        $sent_flag=$this->email->send();
    } 
            
}
    
public function unlockEntityUser()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $username);
    $this->db->order_by('ID', 'DESC');
    $query=$this->db->get();
    $user_row=$query->row();
        
    $UserID1=$user_row->ID;
    $OfficialAddressID=$user_row->OfficialAddressID;
        
    $udata=array(
                'EntityID'=>$EntityMasterID,
                'TitleID'=>$title,
                'FirstName'=>trim($fname, " "),
                'MiddleName'=>$mname,
                'LastName'=>trim($lname, " "),
                'Password'=>$pwd,
                'ActiveFlag'=>1,
                'CreatedBy'=>$UserID,
                'DateTime'=>date('Y-m-d H:i:s'),
                'PasswordExpires'=>1,
                'ChangePasswordFlag'=>1,
                'WrongPasswordCount'=>0
    );
    $this->db->where('ID', $UserID1);
    $this->db->update('udt_UserMaster', $udata);
        
    $CountryID=0;
    if($countryid) {
        $CountryID=$countryid;
    }
        
    $StateID=0;
        
    if($stateid) {
        $StateID=$stateid;
    }
        
    if($OfficialAddressID) {
        $add_data=array(
        'Address1'=>$address1,
        'Address2'=>$address2,
        'Address3'=>$address3,
        'Address4'=>$address4,
        'CountryID'=>$CountryID,
        'StateID'=>$StateID,
        'City'=>$city,
        'ZipCode'=>$zipcode,
        'Email'=>$email_id,
        'Telephone1'=>$mobile_no,
        'ActiveFlag'=>1
        );
        $this->db->where('ID', $OfficialAddressID);
        $this->db->update('udt_AddressMaster', $add_data);
            
        $this->load->library('email');
        if($email_id) {
            $config['protocol']    = 'smtp';
            $config['smtp_host']   = 'higroove.com';
            $config['smtp_port']   = '25';
            $config['smtp_timeout']= '7';
            $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
            $config['smtp_pass']   = 'a_WkTq{L2~=p';
            $config['charset']     = 'utf-8';
            $config['newline']     = "\r\n";
            $config['mailtype']    = 'html'; // or html
            $this->email->initialize($config);
            $content1='Unlock user request accepted by customer administrator.<br>';
            $content1 .='New temporary password : '.$pwd;
            $content1 .='<br>Please change password when log in.';
            $subject='Unlock user request';
            $this->email->clear();
            $this->email->from('admin@iaeglobalnetwork.com');
            $this->email->to($email_id);
            $this->email->subject($subject);
            $this->email->message($content1);
            $sent_flag=$this->email->send();
        }
                
    }
        
}
    
public function getCustomerAdminUsers()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('EntityID', $EntityID);
    $this->db->where('UserType', 'CA');
    $qury=$this->db->get();
    return $qury->result();
        
}
    
public function sendMessageToCustomerAdmin()
{
    $this->load->library('email');
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('udt_UserMaster.ID', $UserID);
    $qury=$this->db->get();
    $usr_row=$qury->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->where('EntityID', $usr_row->EntityID);
    $this->db->where('MessageType', 'admin');
    $this->db->where('Events', 'unlock_user');
    $this->db->where('OnPage', 'login');
    $query=$this->db->get();
    $result=$query->result();
    $msgDetails='';
    foreach($result as $r){
        $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email,udt_AddressMaster.Telephone1');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
        $this->db->where('ID', $r->ForUserID);
        $query=$this->db->get();
        $u_row=$query->row();
        if($u_row->Email) {
            $config['protocol']    = 'smtp';
            $config['smtp_host']   = 'higroove.com';
            $config['smtp_port']   = '25';
            $config['smtp_timeout']= '7';
            $config['smtp_user']   = 'admin@iaeglobalnetwork.com';
            $config['smtp_pass']   = 'a_WkTq{L2~=p';
            $config['charset']     = 'utf-8';
            $config['newline']     = "\r\n";
            $config['mailtype']    = 'html'; // or html
            $this->email->initialize($config);
            $content1 = $r->Message.'<br>';
            $content1 .= 'Unlock user request received from user : '.$usr_row->FirstName.' '.$usr_row->LastName;
            $subject = 'Unlock user request';
            $this->email->clear();
            $this->email->from('admin@iaeglobalnetwork.com');
            $this->email->to($u_row->ToEmail);
            $this->email->subject($subject);
            $this->email->message($content1);
            $sent_flag=$this->email->send();
        }
    }
}
    
public function updateUserPassword()
{
    extract($this->input->post());
        
    $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->where('LoginID', $login_user);
    $this->db->where('Password', $login_pwd);
    $qry=$this->db->get();
    $usr_row=$qry->row();
    $blockchin_flag=0;
    if($usr_row->ActiveFlag=='0') {
        $blockchin_flag=1;
    }
        
    $data=array('Password'=>$pwd,'ChangePasswordFlag'=>0,'ActiveFlag'=>1,'EmailSendFlag'=>0);
    $this->db->where('ID', $usr_row->ID);
    $ret=$this->db->update('udt_UserMaster', $data);
        
    if($ret) {
        $newData=array('UserID'=>$usr_row->ID,'UserPassword'=>$pwd,'CreatedDate'=>date('Y-m-d H:i:s'));
        $this->db->insert('udt_AU_UserPreviousPasswords', $newData);
    }
        
    if($ret > 0  && $blockchin_flag==1) {
        //------------------------blockchain----------------------------
        
        $UserData = array("auomniId" =>$usr_row->ID,"addedByComp"=>$usr_row->addedByComp,"addedByUsr"=>$usr_row->addedByUsr,"email" =>$usr_row->Email,'entityId'=>$usr_row->EntityID,"cargoInvitationFlag"=>$usr_row->CargoInvitationFlag,"approveFixtureFinalFlg"=>$usr_row->ApproveFixtureFinalFlg,"signFixtureFinalFlg"=>$usr_row->SignFixtureFinalFlg,"signCPFinalFlg"=>$usr_row->SignCPFinalFlg,"approveCPFinalFlg"=>$usr_row->ApproveCPFinalFlg,"approveTechVettingFlg"=>$usr_row->ApproveTechVettingFlg,"approveBusVettingFinalFlg"=>$usr_row->ApproveBusVettingFinalFlg,"approveCounterPartyFlg"=>$usr_row->ApproveCounterPartyFlg,"approveComplianceFlg"=>$usr_row->ApproveComplianceFlg,"approveQuoteAuthFlg"=>$usr_row->ApproveQuoteAuthFlg,"liftCharterSubjectFlg"=>$usr_row->LiftCharterSubjectFlg,"createInvSubjectFlg"=>$usr_row->CreateInvSubjectFlg,"liftInvSubjectFlg"=>$usr_row->LiftInvSubjectFlg,"liftInvSubjectFlgByCharter"=>$usr_row->LiftInvSubjectFlgByCharter,"signDigitallyFixtureFlg"=>$usr_row->SignDigitallyFixtureFlg,"signDigitallyCPFlg"=>$usr_row->SignDigitallyCPFlg);
            
        $data_string = json_encode($UserData); 
            
        $url=BLOCK_CHAIN_URL.'createUser/';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(       
            'Content-Type: application/json',        
            'Content-Length: ' . strlen($data_string))   
        );
            
        $result = curl_exec($ch);
        $data=json_decode($result);
        $insArr=array('UID'=>$usr_row->ID,'PrivKey'=>$data->privKey,'PubKey'=>$data->pubKey,'Address'=>$data->address,'BlockchainIndex'=>$data->blockchainIndex,'CreationTx'=>$data->creationTx,'EntityId'=>$usr_row->EntityID,'CreationDate'=>date('Y-m-d H:i:s'));
        $this->db->insert('Udt_AU_UserBlockchainRecord', $insArr);
        $this->db->insert('Udt_AU_UserBlockchainRecord_H', $insArr);
            
    }
    return  $ret;
}
    
public function getOwnerInviteeEntity()
{
    $OwnerID=$this->input->post('OwnerID');
    $this->db->select('udt_AUM_Invitee_Master.*, udt_UserMaster.EntityID, udt_EntityMaster.EntityName, udt_AddressMaster.Email, udt_AddressMaster.Telephone1');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_EntityMaster.AddressID', 'left');
    $this->db->where('udt_AUM_Invitee_Master.RecordOwner', $OwnerID);
    $qry=$this->db->get();
    return $qry->result();
        
}
    
public function getBusinessProcess_h()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BusinessProcessAuctionWise_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_BusinessProcessAuctionWise_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BusinessProcessAuctionWise_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_BusinessProcessAuctionWise_H.BPID', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getBacBrokerage_h()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BAC_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_BAC_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BAC_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('TransactionType', 'Brokerage');
    $this->db->order_by('udt_AU_BAC_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getBacAddCom_h()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BAC_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_BAC_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BAC_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('TransactionType', 'Commision');
    $this->db->order_by('udt_AU_BAC_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getOthers_h()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BAC_H.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_BAC_H');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BAC_H.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('TransactionType', 'Others');
    $this->db->order_by('udt_AU_BAC_H.UserDate', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function checkEntityShipOwner()
{
    $EID=$this->input->post('EID');
        
    $this->db->select('*');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->where('EntityMasterID', $EID);
    $this->db->where('EntityTypeID', 5);
    $this->db->where('ActiveFlag', 1);
    $qry=$this->db->get();
    $rw=$qry->row();
        
    if($rw) {
        return 1;
    } else {
        return 2;
    }
        
}
    
    
public function getVesselMasters()
{
    $key=$this->input->post('key');
    $this->db->select('udt_VesselMaster.*,udt_VesselType.Description');
    $this->db->from('udt_VesselMaster');
    $this->db->join('udt_VesselType', 'udt_VesselType.ID=udt_VesselMaster.VesselTypeID');
    $this->db->like('VesselName', $key, 'after');        
    $this->db->or_like('VesselExName', $key, 'after');
    $q=$this->db->get();
    return $q->result();
}
    
public function getVoyageCalculation()
{
    $this->db->select('*');
    $this->db->from('udt_AUM_VoyageDetails');
    $this->db->order_by('udt_AUM_VoyageDetails.VoyageName', 'asc');
    $q=$this->db->get();
    return $q->result();
        
}
    
public function getCargoByStatus()
{
        
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('udt_AU_Auctions.auctionStatus,udt_AU_Auctions.auctionExtendedStatus');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCargoByDateP()
{
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('count(*) as Total, month(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as mth, Year(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as yr');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    $this->db->where('udt_AU_Auctions.auctionStatus', 'P');
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    }else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $this->db->group_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->group_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->order_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $this->db->order_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCargoByDateC()
{
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('count(*) as Total, month(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as mth, Year(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as yr');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    $this->db->where('udt_AU_Auctions.auctionStatus', 'C');
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $this->db->group_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->group_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->order_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $this->db->order_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCargoByDateA()
{
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('count(*) as Total, month(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as mth, Year(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as yr');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    $this->db->where('udt_AU_Auctions.auctionExtendedStatus', 'A');
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $this->db->group_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->group_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->order_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $this->db->order_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCargoByDatePR()
{
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('count(*) as Total, month(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as mth, Year(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as yr');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    $this->db->where('udt_AU_Auctions.auctionExtendedStatus', 'PNR');
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $this->db->group_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->group_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->order_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $this->db->order_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCargoByDateW()
{
    $sd=date('Y-m-d');
    extract($this->input->post());
    $this->db->select('count(*) as Total, month(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as mth, Year(cops_admin.udt_AU_Cargo.LpLaycanStartDate) as yr');
    $this->db->from('udt_AU_Auctions');    
    $this->db->join('udt_AU_Cargo', 'udt_AU_Auctions.AuctionID=udt_AU_Cargo.AuctionID', 'left');    
    $this->db->where('udt_AU_Auctions.ActiveFlag', 1);
    $this->db->where('udt_AU_Auctions.auctionExtendedStatus', 'W');
    if($loadport) {
        $this->db->where('udt_AU_Cargo.LoadPort', $loadport);    
    }
    if($laycan_from) {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >= ', date('Y-m-d', strtotime($laycan_from)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanStartDate >=', $sd);
    }
    if($laycan_to) {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime($laycan_to)));    
    } else {
        $this->db->where('udt_AU_Cargo.LpLaycanEndDate <= ', date('Y-m-d', strtotime("+5 months")));    
    }
        
    if($cargo) {
        $this->db->where('udt_AU_Cargo.SelectFrom', $cargo);    
    }
    if($est_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_mt >= ', $est_frt_mt_from);    
    }
    if($est_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_mt <= ', $est_frt_mt_to);    
    }
    if($index_frt_mt_from) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt >= ', $index_frt_mt_from);    
    }
    if($index_frt_mt_to) {
        $this->db->where('udt_AU_Cargo.Estimate_Index_mt <= ', $index_frt_mt_to);    
    }
    if($Ower) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $Ower);    
    }
    $this->db->group_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->group_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)');
    $this->db->order_by('year(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $this->db->order_by('month(cops_admin.udt_AU_Cargo.LpLaycanStartDate)', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getLatLong()
{
    $this->db->select('*');
    $this->db->from('Udt_AUM_LatLong');
    $this->db->order_by('LID', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}    
    
public function getLatLongAsc()
{
    $date_from=$this->input->post('date_from');
    $date_to=$this->input->post('date_to');
    $this->db->select('*');
    $this->db->from('Udt_AUM_LatLong');
    if($date_from) {
        $this->db->where('daterange >=', date('Y-m-d', strtotime($date_from)));
    }
    if($date_to) {
        $this->db->where('daterange <=', date('Y-m-d', strtotime($date_to)));
    }
    $this->db->order_by('LID', 'ASC');
    $query=$this->db->get();
    return $query->result(); 
}    
    
public function getLatLongDesc()
{
    $date_from=$this->input->post('date_from');
    $date_to=$this->input->post('date_to');
    $this->db->select('*');
    $this->db->from('Udt_AUM_LatLong');
    if($date_from) {
        $this->db->where('daterange >=', date('Y-m-d', strtotime($date_from)));
    }
    if($date_to) {
        $this->db->where('daterange <=', date('Y-m-d', strtotime($date_to)));
    }
    $this->db->order_by('LID', 'Desc');
    $query=$this->db->get();
    return $query->result(); 
}    
    
public function getActiveEntityType()
{
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_EntityType');
    $this->db->where('ActiveFlag', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getCountry()
{
    $this->db->select('*');
    $this->db->from('udt_CountryMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveCountryMaster()
{
    extract($this->input->post());
    $description=str_replace("'", "''", $description);
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_CountryMaster', $data);
}
    
public function getCountryEdityById()
{
    $id=$this->input->post('id');
    $decodedid=$this->EncodeDecode->str_decode($id);
    $this->db->select('*');
    $this->db->from('udt_CountryMaster');
    $this->db->where('ID', $decodedid);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateCountryMaster()
{
    extract($this->input->post());
    $decodedid=$this->EncodeDecode->str_decode($ID);
    $description=str_replace("'", "''", $description);
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $decodedid);
    return $this->db->update('udt_CountryMaster', $data);
}
    
public function deleteCountryMaster()
{
    extract($this->input->post());
	$decodedid=$this->EncodeDecode->str_decode($id);
    $this->db->where('CountryID', $decodedid);
    $this->db->delete('udt_StateMaster');
        
    $this->db->where('ID', $decodedid);
    return $this->db->delete('udt_CountryMaster');
}
    
public function getCargoTemplateData()
{
    $EntityID=$this->input->get('EntityID');
    $this->db->select('udt_AUM_CargoTemplate.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_CargoTemplate.RecordOwner');
    if($EntityID) {
        $this->db->where('udt_AUM_CargoTemplate.RecordOwner', $EntityID);
    }
    $this->db->order_by('CT_ID', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoTemplateSectionData()
{
    $CT_ID=$this->input->get('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSection');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->order_by('SectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveCargoTemplate()
{
    extract($this->input->post());
    $cargo_template_name=str_replace("'", "''", $cargo_template_name);
        
    $data=array(
    'CT_Name'=>$cargo_template_name,
    'Version'=>$ver,
    'RecordOwner'=>$EntityMasterID,
    'Comment'=>$general_comment,
    'Status'=>$status,
    'TemplateLinkFlg'=>0,
    'CloneFromCT_ID'=>0,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AUM_CargoTemplate', $data);
}
    
public function getCargoTemplateById()
{
    $CT_ID=$this->input->post('id');
    $this->db->select('udt_AUM_CargoTemplate.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_CargoTemplate.RecordOwner', 'left');
    $this->db->where('CT_ID', $CT_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateCargoTemplate()
{
    extract($this->input->post());
    $cargo_template_name=str_replace("'", "''", $cargo_template_name);
    $this->db->trans_start();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->where('CT_ID', $CT_ID);
    $query=$this->db->get();
    $row=$query->row();
    if($row->TemplateLinkFlg==0) {
        $data=array(
        'CT_Name'=>$cargo_template_name,
        'RecordOwner'=>$EntityMasterID,
        'Comment'=>$general_comment,
        'Status'=>$status,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->where('CT_ID', $CT_ID);
        $ret=$this->db->update('udt_AUM_CargoTemplate', $data);
    } else {
        $ret=-1;
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function cloneCargoTemplate()
{
    $CT_ID=$this->input->post('CT_ID');
    $UserID=$this->input->post('UserID');
        
    $this->db->trans_start();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->where('CloneFromCT_ID', $CT_ID);
    $this->db->order_by('CT_ID', 'desc');
    $query=$this->db->get();
    $clone_row=$query->row();
        
    if($clone_row) {
        $ver=$clone_row->Version+0.1;
        $data=array(
        'CT_Name'=>$clone_row->CT_Name,
        'Version'=>$ver,
        'RecordOwner'=>$clone_row->RecordOwner,
        'Comment'=>$clone_row->Comment,
        'Status'=>$clone_row->Status,
        'TemplateLinkFlg'=>0,
        'UserID'=>$UserID,
        'CloneFromCT_ID'=>$CT_ID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $ret=$this->db->insert('udt_AUM_CargoTemplate', $data);
    } else {
        $this->db->select('*');
        $this->db->from('udt_AUM_CargoTemplate');
        $this->db->where('CT_ID', $CT_ID);
        $query=$this->db->get();
        $row=$query->row();
            
        $ver=$row->Version+0.1;
            
        $data=array(
        'CT_Name'=>$row->CT_Name,
        'Version'=>$ver,
        'RecordOwner'=>$row->RecordOwner,
        'Comment'=>$row->Comment,
        'Status'=>$row->Status,
        'TemplateLinkFlg'=>0,
        'CloneFromCT_ID'=>$CT_ID,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $ret=$this->db->insert('udt_AUM_CargoTemplate', $data);
    }
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AUM_CargoTemplate');
        $this->db->order_by('CT_ID', 'desc');
        $query=$this->db->get();
        $CT_row=$query->row();
            
        $this->db->select('*');
        $this->db->from('udt_AUM_TemplateSection');
        $this->db->where('CT_ID', $CT_ID);
        $query=$this->db->get();
        $CTS_data=$query->result();
        foreach($CTS_data as $cts){
            $ctsData=array(
            'CT_ID'=>$CT_row->CT_ID,
            'SectionSeqNo'=>$cts->SectionSeqNo,
            'SectionName'=>$cts->SectionName,
            'SectionStatus'=>$cts->SectionStatus,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_TemplateSection', $ctsData);
                
            $this->db->select('*');
            $this->db->from('udt_AUM_TemplateSection');
            $this->db->order_by('CTS_ID', 'desc');
            $query=$this->db->get();
            $CTS_row=$query->row();
                
            $this->db->select('*');
            $this->db->from('udt_AUM_TemplateSubSections');
            $this->db->where('CTS_ID', $cts->CTS_ID);
            $this->db->where('CT_ID', $CT_ID);
            $query=$this->db->get();
            $CTSS_data=$query->result();
                
            foreach($CTSS_data as $ctss) {
                if($ctss->PenalityApplies==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AUM_TemplateSubSections');
                    $this->db->where('CTSS_ID', $ctss->PenalityAppliesOnCTSS_ID);
                    $query=$this->db->get();
                    $PenalityApplyrow=$query->row();
                        
                    $this->db->select('*');
                    $this->db->from('udt_AUM_TemplateSubSections');
                    $this->db->where('SubSectionLabelName', $PenalityApplyrow->SubSectionLabelName);
                    $this->db->where('CTS_ID', $CTS_row->CTS_ID);
                    $query=$this->db->get();
                    $PenalityCTSS_ID_row=$query->row();
                    $PenalityAppliesOnCTSS_ID=$PenalityCTSS_ID_row->CTSS_ID;
                } else {
                    $PenalityAppliesOnCTSS_ID=$ctss->PenalityAppliesOnCTSS_ID;
                }
                    
                $ctssData=array(
                'CTS_ID'=>$CTS_row->CTS_ID,
                'CT_ID'=>$CT_row->CT_ID,
                'SubSectionSeqNo'=>$ctss->SubSectionSeqNo,
                'SubSectionLabelName'=>$ctss->SubSectionLabelName,
                'SubSectionFieldType'=>$ctss->SubSectionFieldType,
                'SubSectionFieldTextFlg'=>$ctss->SubSectionFieldTextFlg,
                'SubSectionFieldDDOptions'=>$ctss->SubSectionFieldDDOptions,
                'PenalityApplies'=>$ctss->PenalityApplies,
                'PenalityAppliesOnCTSS_ID'=>$ctss->PenalityAppliesOnCTSS_ID,
                'SubSectionHelpContent'=>$ctss->SubSectionHelpContent,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AUM_TemplateSubSections', $ctssData);
                        
                $this->db->select('*');
                $this->db->from('udt_AUM_TemplateSubSections');
                $this->db->order_by('CTSS_ID', 'desc');
                $query=$this->db->get();
                $CTSS_row=$query->row();
                if($ctss->SubSectionFieldType==2) {
                    $this->db->select('*');
                    $this->db->from('udt_AUM_SubSectionDropDownOptions');
                    $this->db->where('CTS_ID', $cts->CTS_ID);
                    $this->db->where('CTSS_ID', $ctss->CTSS_ID);
                    $query=$this->db->get();
                    $DDoption_data=$query->result();
                    foreach($DDoption_data as $dd){
                        $ddData=array(
                        'CTS_ID'=>$CTS_row->CTS_ID,
                        'CTSS_ID'=>$CTSS_row->CTSS_ID,
                        'DD_Option'=>$dd->DD_Option
                        );
                        $this->db->insert('udt_AUM_SubSectionDropDownOptions', $ddData);
                    }
                }
                    
                if($ctss->PenalityApplies==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AUM_TemplateSubSectionPenality');
                    $this->db->where('CTS_ID', $cts->CTS_ID);
                    $this->db->where('CTSS_ID', $ctss->CTSS_ID);
                    $query=$this->db->get();
                    $penality_data=$query->result();
                    foreach($penality_data as $p){
                        $pData=array(
                        'CT_ID'=>$CT_row->CT_ID,
                        'CTSS_ID'=>$CTSS_row->CTSS_ID,
                        'CTS_ID'=>$CTS_row->CTS_ID,
                        'PenalitySeqNo'=>$p->PenalitySeqNo,
                        'PenalityLabelName'=>$p->PenalityLabelName,
                        'PenalityType'=>$p->PenalityType,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AUM_TemplateSubSectionPenality', $pData);
                    }
                }
            }
        }
    }
        
    $this->db->trans_complete();
    return $ret;
}
    
public function deleteCargoTemplate()
{
    $CT_ID=$this->input->post('id');
        
    $this->db->where('CT_ID', $CT_ID);
    return $this->db->delete('udt_AUM_CargoTemplate');
}
    
public function saveTemplateSection()
{
    extract($this->input->post());
    $this->db->trans_start();
    $data=array(
                'CT_ID'=>$CT_ID,
                'SectionSeqNo'=>$SectionSeqNo,
                'SectionName'=>$SectionName,
                'SectionStatus'=>$SectionStatus,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $ret=$this->db->insert('udt_AUM_TemplateSection', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSection');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->order_by('CTS_ID', 'desc');
    $query=$this->db->get();
    $TS_row=$query->row();
        
    $this->db->trans_complete();
    if($ret) {
        return $TS_row->CTS_ID;
    } else {
        return -1;
    }
        
}
    
public function saveTemplateSubsection()
{
    extract($this->input->post());
    $this->db->trans_start();
    $subsection_field_dropdwn=trim($subsection_field_dropdwn, " ");
    $subsection_field_dropdwn=trim($subsection_field_dropdwn, ",");
            
    $data=array(
                'CTS_ID'=>$CTS_ID,
                'CT_ID'=>$CT_ID,
                'SubSectionSeqNo'=>$SubSectionSeqNo,
                'SubSectionLabelName'=>$SubSectionLabelName,
                'SubSectionHelpContent'=>$SubSectionHelpContent,
                'SubSectionFieldType'=>$SubSectionFieldType,
                'SubSectionFieldTextFlg'=>$SubSectionFieldTextFlg,
                'SubSectionFieldDDOptions'=>$subsection_field_dropdwn,
                'PenalityApplies'=>$PenalityApplies,
                'PenalityAppliesOnCTSS_ID'=>$PenalityAppliesOnCTSS_ID,
                'SubSectionHelpContent'=>$SubSectionHelpContent,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
                
    $ret=$this->db->insert('udt_AUM_TemplateSubSections', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('CTSS_ID', 'desc');
    $query=$this->db->get();
    $TSS_row=$query->row();
        
        
    if($SubSectionFieldType==2) {
        $dp_arr=explode(',', $subsection_field_dropdwn);
        for($j=0; $j<count($dp_arr);$j++){
            $DD_Option=trim($dp_arr[$j], " ");
            if($DD_Option !='') {
                $dd_data=array(
                'CTSS_ID'=>$TSS_row->CTSS_ID,
                'CTS_ID'=>$CTS_ID,
                'DD_Option'=>$DD_Option
                );
                $this->db->insert('udt_AUM_SubSectionDropDownOptions', $dd_data);
            }    
        }
    }
        
    if($PenalityApplies==1) {
        $penSeqArr=explode(",", $pen_seq_no);
        $penLblArr=explode(",", $pen_lbl_name);
        $penTypeArr=explode(",", $penality_type);
            
        for($j=0;$j<count($penSeqArr);$j++){
            $penData=array(
            'CTSS_ID'=>$TSS_row->CTSS_ID,
            'CTS_ID'=>$CTS_ID,
            'CT_ID'=>$CT_ID,
            'PenalitySeqNo'=>$penSeqArr[$j],
            'PenalityLabelName'=>$penLblArr[$j],
            'PenalityType'=>$penTypeArr[$j],
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_TemplateSubSectionPenality', $penData);
        }
    }
        
    $this->db->trans_complete();
    return $ret;
        
}
    
public function getTemplateSubsections()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('SubSectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function deleteTemplateSection()
{
    $CTS_ID=$this->input->post('id');
        
    $this->db->where('CTS_ID', $CTS_ID);
    $ret=$this->db->delete('udt_AUM_TemplateSection');
        
    if($ret) {
        $this->db->where('CTS_ID', $CTS_ID);
        $this->db->delete('udt_AUM_TemplateSubSections');
            
        $this->db->where('CTS_ID', $CTS_ID);
        $this->db->delete('udt_AUM_SubSectionDropDownOptions');
            
        $this->db->where('CTS_ID', $CTS_ID);
        $this->db->delete('udt_AUM_TemplateSubSectionPenality');
    }
    return $ret;
}
    
public function deleteTemplateSubsection()
{
    $CTSS_ID=$this->input->post('CTSS_ID');
        
    $this->db->where('CTSS_ID', $CTSS_ID);
    $ret=$this->db->delete('udt_AUM_TemplateSubSections');
        
    if($ret) {
        $this->db->where('CTSS_ID', $CTSS_ID);
        $this->db->delete('udt_AUM_SubSectionDropDownOptions');
            
        $this->db->where('CTSS_ID', $CTSS_ID);
        $this->db->delete('udt_AUM_TemplateSubSectionPenality');
    }
    return $ret;
}
    
public function getTemplateSubsection()
{
    $CTSS_ID=$this->input->post('CTSS_ID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CTSS_ID', $CTSS_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getTemplateSubsectionPenaltyAppliesOn($PenalityAppliesOnCTSS_ID)
{
        
    $this->db->select('SubSectionLabelName');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CTSS_ID', $PenalityAppliesOnCTSS_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getTemplateSubsectionPenality()
{
    $CTSS_ID=$this->input->post('CTSS_ID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSectionPenality');
    $this->db->where('CTSS_ID', $CTSS_ID);
    $query=$this->db->get();
    return $query->result();
}
    
public function updateTemplateSubsection()
{
    extract($this->input->post());
    $this->db->trans_start();
    $subsection_field_dropdwn=trim($subsection_field_dropdwn, " ");
    $subsection_field_dropdwn=trim($subsection_field_dropdwn, ",");
            
    $data=array(
                'CTS_ID'=>$CTS_ID,
                'CT_ID'=>$CT_ID,
                'SubSectionSeqNo'=>$SubSectionSeqNo,
                'SubSectionLabelName'=>$SubSectionLabelName,
                'SubSectionHelpContent'=>$SubSectionHelpContent,
                'SubSectionFieldType'=>$SubSectionFieldType,
                'SubSectionFieldTextFlg'=>$SubSectionFieldTextFlg,
                'SubSectionFieldDDOptions'=>$subsection_field_dropdwn,
                'PenalityApplies'=>$PenalityApplies,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    if($PenalityAppliesOnCTSS_ID != '') {
        $data['PenalityAppliesOnCTSS_ID']=$PenalityAppliesOnCTSS_ID;
    }
        
    $this->db->where('CTSS_ID', $CTSS_ID);
    $ret=$this->db->update('udt_AUM_TemplateSubSections', $data);
        
        
    if($SubSectionFieldType==2) {
        $this->db->where('CTSS_ID', $CTSS_ID);
        $this->db->delete('udt_AUM_SubSectionDropDownOptions');
            
        $dp_arr=explode(',', $subsection_field_dropdwn);
        for($j=0; $j<count($dp_arr);$j++){
            $DD_Option=trim($dp_arr[$j], " ");
            if($DD_Option !='') {
                $dd_data=array(
                'CTSS_ID'=>$CTSS_ID,
                'CTS_ID'=>$CTS_ID,
                'DD_Option'=>$DD_Option
                );
                $this->db->insert('udt_AUM_SubSectionDropDownOptions', $dd_data);
            }    
        }
    }
        
    $this->db->where('CTSS_ID', $CTSS_ID);
    $this->db->delete('udt_AUM_TemplateSubSectionPenality');
            
    if($PenalityApplies==1) {
            
        $penSeqArr=explode(",", $pen_seq_no);
        $penLblArr=explode(",", $pen_lbl_name);
        $penTypeArr=explode(",", $penality_type);
            
        for($j=0;$j<count($penSeqArr);$j++){
            $penData=array(
            'CTSS_ID'=>$CTSS_ID,
            'CTS_ID'=>$CTS_ID,
            'CT_ID'=>$CT_ID,
            'PenalitySeqNo'=>$penSeqArr[$j],
            'PenalityLabelName'=>$penLblArr[$j],
            'PenalityType'=>$penTypeArr[$j],
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_TemplateSubSectionPenality', $penData);
        }
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function getCargoTemplateRecord()
{
    $CT_ID=$this->input->post('CT_ID');
        
    $this->db->select('udt_AUM_CargoTemplate.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_CargoTemplate.RecordOwner', 'left');
    $this->db->where('CT_ID', $CT_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCargoTemplateSectionsRecord()
{
    $CT_ID=$this->input->post('CT_ID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSection');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('SectionStatus', 1);
    $this->db->order_by('SectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoTemplateSubsectionsRecords($CTS_ID)
{
    $CT_ID=$this->input->post('CT_ID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('SubSectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getTemplateSubsectionsByTemplateID($CTS_ID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('SubSectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoTemplateSubsectionsPenalities($CTSS_ID)
{
    $CT_ID=$this->input->post('CT_ID');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSectionPenality');
    $this->db->where('CTSS_ID', $CTSS_ID);
    $this->db->order_by('PenalitySeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getTemplateSectionById()
{
    $CT_ID=$this->input->post('CT_ID');
    $CTS_ID=$this->input->post('CTS_ID');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSection');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('CTS_ID', $CTS_ID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getTemplateSubSectionById()
{
    $CT_ID=$this->input->post('CT_ID');
    $CTS_ID=$this->input->post('CTS_ID');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('SubSectionSeqNo', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function updateTemplateSection()
{
    extract($this->input->post());
        
    $data=array(
                'SectionSeqNo'=>$SectionSeqNo,
                'SectionName'=>$SectionName,
                'SectionStatus'=>$SectionStatus,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $this->db->where('CTS_ID', $CTS_ID);
    $ret=$this->db->update('udt_AUM_TemplateSection', $data);
        
    return $ret;
}
    
public function getCargoTemplateByCT_ID()
{
    $CT_ID=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_CargoTemplate');
    $this->db->where('CT_ID', $CT_ID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getCargoTemplateSectionByCT_ID()
{
    $CT_ID=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSection');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->where('SectionStatus', 1);
    $this->db->order_by('SectionSeqNo', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoTemplateSubSectionByCT_ID()
{
    $CT_ID=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->order_by('SubSectionSeqNo', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoTemplateSubSectionByCTS_ID()
{
    $CTS_ID=$this->input->post('CTS_ID');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('PenalityAppliesOnCTSS_ID', '0');
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('CTSS_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoTemplateSubSectionForPenaltyAppliesOnByCTS_ID()
{
    $CTS_ID=$this->input->post('CTS_ID');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSections');
    $this->db->where('PenalityAppliesOnCTSS_ID', '0');
    $this->db->where('PenalityApplies', '2');
    $this->db->where('CTS_ID', $CTS_ID);
    $this->db->order_by('CTSS_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoTemplatePenalityByCT_ID()
{
    $CT_ID=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AUM_TemplateSubSectionPenality');
    $this->db->where('CT_ID', $CT_ID);
    $this->db->order_by('PenalitySeqNo', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
    
public function getState()
{
    $this->db->select('udt_StateMaster.ID,udt_StateMaster.Code,udt_StateMaster.Description,udt_StateMaster.ActiveFlag,udt_CountryMaster.Description as country');
    $this->db->from('udt_StateMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_StateMaster.CountryID');
    $query=$this->db->get();
    return $query->result();
}
     
public function getCountryAutocomplete()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CountryMaster');
    $this->db->like('Description', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
     
public function saveStateMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'CountryID'=>$countryid,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_StateMaster', $data);
}
     
public function getStateById()
{
    $id=$this->input->post('id');
    $this->db->select('udt_StateMaster.ID,udt_StateMaster.Code,udt_StateMaster.Description,udt_StateMaster.ActiveFlag,udt_CountryMaster.ID as cID,udt_CountryMaster.Code as cCode,udt_CountryMaster.Description as cDescription');
    $this->db->from('udt_StateMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_StateMaster.CountryID');
    $this->db->where('udt_StateMaster.ID', $id);
    $query=$this->db->get();
    return $query->row();
}
     
public function updateStateMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'CountryID'=>$countryid,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $ID);
    return $this->db->update('udt_StateMaster', $data);
}
     
public function deleteStateMaster()
{
    extract($this->input->post());
    $this->db->where('ID', $id);
    return $this->db->delete('udt_StateMaster');
}
    
    
public function getCurrencyMaster()
{
    $this->db->select('*');
    $this->db->from('udt_CurrencyMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveCurrencyMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_CurrencyMaster', $data);
}
    
public function getCurrencyEdityById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_CurrencyMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateCurrencyMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $ID);
    return $this->db->update('udt_CurrencyMaster', $data);
}
    
public function deleteCurrencyMaster()
{
    extract($this->input->post());
        
    $this->db->where('ID', $id);
    return $this->db->delete('udt_CurrencyMaster');
}
    
public function getPortMasterMain()
{
    $this->db->select('*');
    $this->db->from('udt_PortMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function getStateAutocomplete()
{
    $key=$this->input->post('key');
    $countryid=$this->input->post('countryid');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_StateMaster');
    $this->db->where('CountryID', $countryid);
    $this->db->like('Description', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function savePortmaster()
{
    extract($this->input->post());
    $data=array('Code'=>$port_code,
    'PortName'=>$port_name,
    'EntityID'=>$EntityMasterID,
    'CountryID'=>$countryid,
    'StateID'=>$stateid,
    'Latitude'=>$latitude,
    'Longitude'=>$longitude,
    'Description'=>$description,
    'ActiveFlag'=>$status,
    'TimeStamp'=>date('Y-m-d H:i:s')
                );
    return $this->db->insert('udt_PortMaster', $data); 
}
    
public function getPortById()
{
    $id=$this->input->post('id');
    $this->db->select('udt_PortMaster.*,udt_EntityMaster.EntityName,udt_EntityMaster.Description as edesc,udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as cdesc,udt_StateMaster.Code as scode,udt_StateMaster.Description as sdesc');
    $this->db->from('udt_PortMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_PortMaster.EntityID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_PortMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_PortMaster.StateID', 'left');
    $this->db->where('udt_PortMaster.ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updatePortMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$port_code,
    'PortName'=>$port_name,
    'EntityID'=>$EntityMasterID,
    'CountryID'=>$countryid,
    'StateID'=>$stateid,
    'Latitude'=>$latitude,
    'Longitude'=>$longitude,
    'Description'=>$description,
    'ActiveFlag'=>$status,
    'TimeStamp'=>date('Y-m-d H:i:s')
                );
    $this->db->where('ID', $PortID); 
    return $this->db->update('udt_PortMaster', $data); 
}
    
public function deletePortMaster()
{
    $PortID=$this->input->post('id');
    $this->db->where('ID', $PortID);
    return $this->db->delete('udt_PortMaster');         
}
    
public function unitOfMeasurement()
{
    $this->db->select('*');
    $this->db->from('udt_CargoUnitMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveunitOfMeasurement()
{
    extract($this->input->post());
    $data=array('UnitCode'=>$unit_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_CargoUnitMaster', $data);
}
    
public function getUnitOfMeasurementById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_CargoUnitMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateUnitOfMeasurement()
{
    extract($this->input->post());
    $data=array('UnitCode'=>$unit_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $unit_id);
    return $this->db->update('udt_CargoUnitMaster', $data);
}
    
public function deleteUnitOfMeasurement()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_CargoUnitMaster');
}
    
public function titleMaster()
{
    $this->db->select('*');
    $this->db->from('udt_Titlemaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveTitleMaseter()
{
    extract($this->input->post());
    $data=array('Code'=>$title_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_Titlemaster', $data);
}
    
public function getTitleMasterById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_Titlemaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateTitleMaseter()
{
    extract($this->input->post());
    $data=array('Code'=>$title_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $title_id);
    return $this->db->update('udt_Titlemaster', $data);
}
    
public function deleteTitleMaster()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_Titlemaster');
}
    
public function getSecretQuestionMaster()
{
    $this->db->select('*');
    $this->db->from('udt_SecretQuestion');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveSecretQuestionMaster()
{
    extract($this->input->post());
    $data=array('Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_SecretQuestion', $data);
}
    
public function getSecretQuestionMasterById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_SecretQuestion');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateSecretQuestionMaseter()
{
    extract($this->input->post());
    $data=array('Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $secret_id);
    return $this->db->update('udt_SecretQuestion', $data);
}
    
public function deleteSecretQuestionMaster()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_SecretQuestion');
}
        
public function getTerminalsMaster()
{
    $this->db->select('*');
    $this->db->from('udt_TerminalsMaster');
    $query=$this->db->get();
    return $query->result();
}    
    
public function saveTerminalMaseter()
{
    extract($this->input->post());
    $data=array('Name'=>$terminal_name,'Description'=>$terminal_description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_TerminalsMaster', $data);
}
    
public function getTerminalMasterById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_TerminalsMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateTerminalMaseter()
{
    extract($this->input->post());
    $data=array('Name'=>$terminal_name,'Description'=>$terminal_description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $terminal_id);
    return $this->db->update('udt_TerminalsMaster', $data);
}
    
public function deleteTerminalMaseter()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_TerminalsMaster');
}
        
public function getTimeZoneMaster()
{
    $this->db->select('*');
    $this->db->from('udt_TimeZoneMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveTimeZoneMaseter()
{
    extract($this->input->post());
    $data=array('Code'=>$time_zone_name,'Description'=>$time_zone_description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'),'GMTDiff'=>$gmt_time_diff,'ST_DaytimeSaving'=>$does_daylight_saving_apply,'FromDaytime'=>date('Y-m-d', strtotime($daylight_saving_apply_from)),'ToDaytime'=>date('Y-m-d', strtotime($daylight_saving_apply_to)),'DaytimeValue'=>$daylight_saving_value);
    return $this->db->insert('udt_TimeZoneMaster', $data);
}
    
public function getTimeZoneMasterMyId()
{
    $id=$this->input->post('id');
    $this->db->select('ID,Code,Description,ActiveFlag,DateTIme,GMTDiff,ST_DaytimeSaving,convert(char(10),FromDaytime, 105) as FromDaytime1,convert(char(10),ToDaytime, 105) as ToDaytime1,DaytimeValue');
    $this->db->from('udt_TimeZoneMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateTimeZoneMasterMyId()
{
    extract($this->input->post());
    $data=array('Code'=>$time_zone_name,'Description'=>$time_zone_description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'),'GMTDiff'=>$gmt_time_diff,'ST_DaytimeSaving'=>$does_daylight_saving_apply,'FromDaytime'=>date('Y-m-d', strtotime($daylight_saving_apply_from)),'ToDaytime'=>date('Y-m-d', strtotime($daylight_saving_apply_to)),'DaytimeValue'=>$daylight_saving_value);
    $this->db->where('ID', $time_zone_id);
    return $this->db->update('udt_TimeZoneMaster', $data);
}
    
public function deleteTimeZoneMaster()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_TimeZoneMaster');
}
    
public function getVesseltypeMaster()
{
    $this->db->select('*');
    $this->db->from('udt_Vesseltype');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveVesseltypeMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    return $this->db->insert('udt_Vesseltype', $data);
}
    
public function getVesseltypeEdityById()
{
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_Vesseltype');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateVesseltypeMaster()
{
    extract($this->input->post());
    $data=array('Code'=>$country_code,'Description'=>$description,'ActiveFlag'=>$status,'DateTime'=>date('Y-m-d H:i:s'));
    $this->db->where('ID', $ID);
    return $this->db->update('udt_Vesseltype', $data);
}

public function deleteVesseltypeMaster()
{
    extract($this->input->post());
        
    $this->db->where('ID', $id);
    return $this->db->delete('udt_Vesseltype');
}

public function get_VesselMasters()
{
    $this->db->select('*');    
    $this->db->from('udt_VesselMaster');    
       $query = $this->db->get();
    return $query->result();
}
    
public function saveVesselmasters()
{
    extract($this->input->post());
    $data=array(
                'IMONumber'=>$VesselIMONumber,
                'Flag'=>$Flag,
                'ActiveFlag'=>$status,
                'VesselName'=>$VesselName,
                'VesselExName'=>$VesselExName,
                'CurrRegisteredEntityID'=>$CRE,
                'Displacement'=>$Displacement,
                'VesselTypeID'=>$VTC,
                'TradingStatus'=>$TS,
                'YearBuilt'=>$YB,
                'Age'=>$Age,
                'DWT'=>$DWT,
                'GRT'=>$GRT,
                'NRT'=>$NRT,
                'Breadth'=>$Breadth,
                'DepthMoulded'=>$DM,
                'Speed'=>$Speed,
                'Draught'=>$Draught,
                'Length'=>$Lengthoverall,
                'FuelCons'=>$FuelCons,
                'ClassSociety'=>$ClassSociety,
                'OfficialNumber'=>$OFN,
                'NoClassChanges'=>$NoCC,
                'PortofRegistry'=>$POfR,
                'DateTime'=>date('Y-m-d H:i:s')
    );
        
    return $this->db->insert('udt_VesselMaster', $data);
}    

public function getVesseleditById()
{
    $id=$this->input->post('id');
    $this->db->from('udt_VesselMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateVesselmasters()
{
    extract($this->input->post());
        
    $data=array(
                'IMONumber'=>$VesselIMONumber,
                'Flag'=>$Flag,
                'ActiveFlag'=>$status,
                'VesselName'=>$VesselName,
                'VesselExName'=>$VesselExName,
                'CurrRegisteredEntityID'=>$CRE,
                'Displacement'=>$Displacement,
                'VesselTypeID'=>$VTC,
                'TradingStatus'=>$TS,
                'YearBuilt'=>$YB,
                'Age'=>$Age,
                'DWT'=>$DWT,
                'GRT'=>$GRT,
                'NRT'=>$NRT,
                'Breadth'=>$Breadth,
                'DepthMoulded'=>$DM,
                'Speed'=>$Speed,
                'Draught'=>$Draught,
                'Length'=>$Lengthoverall,
                'FuelCons'=>$FuelCons,
                'ClassSociety'=>$ClassSociety,
                'OfficialNumber'=>$OFN,
                'NoClassChanges'=>$NoCC,
                'PortofRegistry'=>$POfR,
                'DateTime'=>date('Y-m-d H:i:s')
    );

    $this->db->where('ID', $ID);
    
    return $this->db->update('udt_VesselMaster', $data);
}
    
public function deleteVesselmasters()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    return $this->db->delete('udt_VesselMaster');
}
    
public function testmail()
{
    $Email='pradeep026kumar@gmail.com';
    $subj='New user registration';
    $message ='hello,<br/>';
    $message .='Your are regisered in auomni system.<br/>';
    $message .='Please click here to get your temporary login details : ';
    $message .='<a href="'.base_url().'index.php/send-user-temporary-details?id=147">Click here.</a>';
    $this->load->library('email');    
    $config['protocol']    = 'smtp';
    $config['smtp_host']    = 'higroove.com';
    $config['smtp_port']    = '25';
    $config['smtp_timeout'] = '7';
    $config['smtp_user']    = 'admin@iaeglobalnetwork.com';
    $config['smtp_pass']    = 'a_WkTq{L2~=p';
    $config['charset']    = 'utf-8';
    $config['newline']    = "\r\n";
    $config['mailtype'] = 'html'; // or html
    $this->email->initialize($config);
        
    $this->email->clear();
    $this->email->from('admin@iaeglobalnetwork.com');
    $this->email->to($Email);
    $this->email->subject($subj);
    $this->email->message($message);
    $sent_flag=$this->email->send();
            
    return 1;
}
    
public function sendTemporaryDetails()
{
    $id=$this->input->get('id');
        
    $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->where('udt_UserMaster.ID', $id);
    $qry=$this->db->get();
    $usr_row=$qry->row();
        
    $subj='New user login details';
    $message ='hello,<br/>';
    $message .='Your are regisered in auomni system.<br/>';
    $message .='Your temporary login details are : <br/>';
    $message .='LoginID : '.$usr_row->LoginID.'<br/>';
    $message .='Password : '.$usr_row->Password.'<br/>';
    $message .='Please login to auomni system and update your password.<br/>';
    $this->load->library('email');    
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = 'higroove.com';
    $config['smtp_port'] = '25';
    $config['smtp_timeout'] = '7';
    $config['smtp_user'] = 'admin@iaeglobalnetwork.com';
    $config['smtp_pass'] = 'a_WkTq{L2~=p';
    $config['charset'] = 'utf-8';
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'html'; // or html
    $this->email->initialize($config);
        
    $this->email->clear();
    $this->email->from('admin@iaeglobalnetwork.com');
    $this->email->to($usr_row->Email);
    $this->email->subject($subj);
    $this->email->message($message);
    $sent_flag=$this->email->send();
        
}
    
public function getSelectedFixContentById()
{
    $TemplateID=$this->input->post('TemplateID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Template');
    $this->db->where('TemplateID', $TemplateID);
    $this->db->where('Included', 1);
    $this->db->order_by('SeqNo', 'asc');
    $this->db->order_by('TID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
        
}
    
public function getSelectedFixTemplateById()
{
    $TemplateID=$this->input->post('TemplateID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->where('TemplateID', $TemplateID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function checkPreviousPasswords()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $login_user);
    $this->db->where('Password', $login_pwd);
    $qry=$this->db->get();
    $usr_row=$qry->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserPreviousPasswords');
    $this->db->where('UserID', $usr_row->ID);
    $this->db->where('UserPassword', $new_pwd);
    $qry1=$this->db->get();
    $prev_row=$qry1->row();
    if($prev_row) {
        return 0;
    } else {
        return 1;
    }
}
    
public function getAllEntityType()
{
    $query=$this->db->get_where('udt_EntityType', array('ActiveFlag'=>1));
    return $query->result();
}
    
public function getSecretQuestions()
{
    $query=$this->db->get_where('udt_SecretQuestion', array('ActiveFlag'=>1));
    return $query->result();
}
    
    //------------------------blockchain----------------------------

public function getIpfsHashByFixtureID($FixtureID)
{
    $this->db->select('FixtureVersion,ipfsHash,HeaderContent');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('FixtureID', $FixtureID);
    $query=$this->db->get();
    return $query->row();
}    

public function getUserTransactionHashByUID()
{
    $UID=$this->input->post('UID');
    $this->db->select('CreationTx');
    $this->db->from('Udt_AU_UserBlockchainRecord');
    $this->db->where('UID', $UID);
    $query=$this->db->get();
    return $query->row();
        
}

public function getUserTransactionHashHistory()
{
    $UID=$this->input->post('UID');
    $this->db->select('CreationTx');
    $this->db->from('Udt_AU_UserBlockchainRecord_H');
    $this->db->order_by('ID', 'DESC');
    $this->db->where('UID', $UID);
    $query=$this->db->get();
    return $query->result();
        
}

public function getBlockchainIndexByUserID($UserID)
{
    $this->db->select('BlockchainIndex');
    $this->db->from('Udt_AU_UserBlockchainRecord');
    $this->db->where('UID', $UserID);
    $query=$this->db->get();
    return $query->row()->BlockchainIndex;
}
        
public function checkUserLoginExist()
{
    $LoginID=$this->input->post('LoginID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('LoginID', $LoginID);
    $qry1=$this->db->get();
    return $qry1->row();
}
    
public function getUserPermission()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $qry1=$this->db->get();
    return $qry1->row();
        
}
    
    
public function getEntityByParentEntity()
{
    $ID=$this->input->post('entityID');
    $this->db->select('ParentGroupID');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $ID);
    $query=$this->db->get();
    $ParentGroupID=$query->row()->ParentGroupID;
        
    $this->db->select('ID,EntityName');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ParentGroupID', $ParentGroupID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getEntityByParentEntityAutocomplete()
{
    extract($this->input->post());
        
    $this->db->select('ParentGroupID');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $EntityID);
    $query=$this->db->get();
    $ParentGroupID=$query->row()->ParentGroupID;
        
    $this->db->select('e1.ID,e1.EntityName,e2.EntityName as OwnerEntityName');
    $this->db->from('udt_EntityMaster as e1');
    $this->db->join('udt_EntityMaster as e2', 'e2.ID=e1.EntityOwner', 'left');
    $this->db->where('e1.ParentGroupID', $ParentGroupID);
    $this->db->like('e1.EntityName', $key, 'after');
    $query=$this->db->get();
    return $query->result();
        
        
}
    
public function addBankDetails()
{
    extract($this->input->post());
    $this->db->trans_start();
    $OfficeEntityID='';
    $AppliesTo='';
    if($apply_to==1) {
        $this->db->select('ParentGroupID');
        $this->db->from('udt_EntityMaster');
        $this->db->where('ID', $entityID);
        $query=$this->db->get();
        $ParentGroupID=$query->row()->ParentGroupID;
            
        $this->db->select('ID,EntityName');
        $this->db->from('udt_EntityMaster');
        $this->db->where('ParentGroupID', $ParentGroupID);
        $query=$this->db->get();
        $rslt=$query->result();
        foreach($rslt as $row) {
            $OfficeEntityID .=$row->ID.',';
        }
    } else {
        for($i=0;$i<count($entity_id);$i++) {
            $OfficeEntityID .=$entity_id[$i].',';
        }
    }

    for($i=0;$i<count($bank_detail_applies_to);$i++) {
        $AppliesTo .=$bank_detail_applies_to[$i].',';
    }
    $OfficeEntityID=rtrim($OfficeEntityID, ',');
    $AppliesTo=rtrim($AppliesTo, ',');
    $data=array(
                'UserID'=>$ByUser,
                'EntityID'=>$entityID,
                'ApplyTo'=>$apply_to,
                'BankName'=>$bank_name,
                'BankAddress1'=>$bank_address1,
                'BankAddress2'=>$bank_address2,
                'BankAddress3'=>$bank_address3,
                'BankAddress4'=>$bank_address4,
                'Country'=>$bank_country,
                'State'=>$bank_state,
                'City'=>$bank_city,
                'ZipCode'=>$bank_pincode,
                'BankComments'=>$bank_comments,
                'AccountName'=>$account_name,
                'AccountNumber'=>$account_number,
                'CurrencyID'=>$currencty_of_payment_id,
                'CorrespondentBank1'=>$correspondent_bank1,
                'CorrespondentBank2'=>$correspondent_bank2,
                'BankCode'=>$bank_code,
                'BankBranchCode'=>$bank_branch_code,
                'SwiftCode'=>$swift_bic_code,
                'IfscCode'=>$ifsc_code,
                'IbanCode'=>$bank_iban,
                'SortCode'=>$sort_code,
                'AbaNumber'=>$aba_number,
                'BankInstructionComments'=>$bank_instruction_comments,
                'CreationDate'=>date('Y-m-d H:i:s'),
                'OfficeEntityID'=>$OfficeEntityID,
                'AppliesTo'=>$AppliesTo,
                'DetailsAppliesTo'=>$DetailsAppliesTo,
                'ActiveFlag'=>$ActiveFlag
    );
    $ret=$this->db->insert('Udt_AU_BankingDetail', $data);
    $this->db->trans_complete();
    return $ret;
}
    
    
public function getBankingDetail()
{
    $this->db->select('Udt_AU_BankingDetail.*,Udt_EntityMaster.EntityName,udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as cdesc,udt_StateMaster.Code as scode,udt_StateMaster.Description as sdesc');
    $this->db->from('Udt_AU_BankingDetail');
    $this->db->join('Udt_EntityMaster', 'Udt_EntityMaster.ID=Udt_AU_BankingDetail.EntityID');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=Udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=Udt_AU_BankingDetail.State', 'left');
    $this->db->order_by('Udt_AU_BankingDetail.CreationDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getBankingDetailById()
{
    $id=$this->input->post('id');
    $this->db->select('Udt_AU_BankingDetail.*,Udt_EntityMaster.EntityName,udt_CurrencyMaster.Code,udt_CurrencyMaster.Description,udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as cdesc,udt_StateMaster.Code as scode,udt_StateMaster.Description as sdesc');
    $this->db->from('Udt_AU_BankingDetail');
    $this->db->join('Udt_EntityMaster', 'Udt_EntityMaster.ID=Udt_AU_BankingDetail.EntityID', 'left');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=Udt_AU_BankingDetail.CurrencyID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=Udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=Udt_AU_BankingDetail.State', 'left');
    $this->db->where('Udt_AU_BankingDetail.ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getOfficeEntityById($OfficeEntityID)
{
    $this->db->select('ID,EntityName');
    $this->db->from('Udt_EntityMaster');
    $this->db->where_in('ID', $OfficeEntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function updateBankDetails()
{
    extract($this->input->post());
    $this->db->trans_start();
    $OfficeEntityID='';
    $AppliesTo='';
        
    if($apply_to==1) {
        $this->db->select('ParentGroupID');
        $this->db->from('udt_EntityMaster');
        $this->db->where('ID', $entityID);
        $query=$this->db->get();
        $ParentGroupID=$query->row()->ParentGroupID;
            
        $this->db->select('ID,EntityName');
        $this->db->from('udt_EntityMaster');
        $this->db->where('ParentGroupID', $ParentGroupID);
        $query=$this->db->get();
        $rslt=$query->result();
        foreach($rslt as $row) {
            $OfficeEntityID .=$row->ID.',';
        }
    } else {
        for($i=0;$i<count($entity_id);$i++) {
            $OfficeEntityID .=$entity_id[$i].',';
        }
    }
            
    for($i=0;$i<count($bank_detail_applies_to);$i++) {
        $AppliesTo .=$bank_detail_applies_to[$i].',';
    }
    $OfficeEntityID=rtrim($OfficeEntityID, ',');
    $AppliesTo=rtrim($AppliesTo, ',');
    $data=array(
                'UserID'=>$ByUser,
                'EntityID'=>$entityID,
                'ApplyTo'=>$apply_to,
                'BankName'=>$bank_name,
                'BankAddress1'=>$bank_address1,
                'BankAddress2'=>$bank_address2,
                'BankAddress3'=>$bank_address3,
                'BankAddress4'=>$bank_address4,
                'Country'=>$bank_country,
                'State'=>$bank_state,
                'City'=>$bank_city,
                'ZipCode'=>$bank_pincode,
                'BankComments'=>$bank_comments,
                'AccountName'=>$account_name,
                'AccountNumber'=>$account_number,
                'CurrencyID'=>$currencty_of_payment_id,
                'CorrespondentBank1'=>$correspondent_bank1,
                'CorrespondentBank2'=>$correspondent_bank2,
                'BankCode'=>$bank_code,
                'BankBranchCode'=>$bank_branch_code,
                'SwiftCode'=>$swift_bic_code,
                'IfscCode'=>$ifsc_code,
                'IbanCode'=>$bank_iban,
                'SortCode'=>$sort_code,
                'AbaNumber'=>$aba_number,
                'BankInstructionComments'=>$bank_instruction_comments,
                'CreationDate'=>date('Y-m-d H:i:s'),
                'OfficeEntityID'=>$OfficeEntityID,
                'AppliesTo'=>$AppliesTo,
                'DetailsAppliesTo'=>$DetailsAppliesTo,
                'ActiveFlag'=>$ActiveFlag
    );
    $this->db->where('ID', $BankingDetailID);
    $ret=$this->db->update('Udt_AU_BankingDetail', $data);
    $this->db->trans_complete();
    return $ret;
}
    
public function deleteBankingDetail()
{
    $id=$this->input->post('id');
    $this->db->where('ID', $id);
    $ret=$this->db->delete('Udt_AU_BankingDetail');
    return $ret;
}
    
public function checkLastClause()
{
    $DocumentTypeID=$this->input->post('DocumentTypeID');
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $this->db->where('last_clause', 1);
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
    //----------save email-----------------------
public function getTidMidByAuctionID()
{
    $RecordOwner=$this->input->post('EntityID');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AUM_Freight.*,udt_EntityMaster.EntityName,udt_AU_Cargo.LpPreferDate,udt_AU_Cargo.Estimate_mt,udt_AU_Cargo.Estimate_Index_mt,udt_UserMaster.EntityID as Owner');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->join('udt_AU_Cargo', 'udt_AU_Cargo.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    if($RecordOwner) {
        $where=" cops_admin.udt_AUM_Freight.TentativeStatus=1 and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_UserMaster.EntityID=".$RecordOwner." or cops_admin.udt_AUM_Freight.ShipOwnerID=".$RecordOwner." ) ";
    }else{
        $where=" cops_admin.udt_AUM_Freight.TentativeStatus=1 ";
    }
    $this->db->where($where);
    $this->db->where('udt_AUM_Freight.AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
    
public function saveEmailFnCp($content,$EditableFlag,$url,$FromEmail,$ToEmail,$ToEmailID,$mid_tid,$document_type,$tid,$UserID,$EntityID)
{
    $Comment='';
    extract($this->input->post());
    $this->load->library('email');
    $re=0;
    $sent_flag=0;
    $status='';
    $fix_row=$this->getFitureRowByResponseID($tid);
    $FixtureVersion=$fix_row->FixtureVersion;
    if($fix_row->Status==1) {
        $status='Discussion';
    }else if($fix_row->Status==2) {
        $status='Fixture Complete';
    }else{
        $status='Closed';
    }
            
    $DocumentType='';    
    if($document_type==1) {
        $DocumentType="Fixture Note";
        $Comment='Auto generated after fixture note completion';
    } else if($document_type==2) {
        $DocumentType="Charter Party";
        $Comment='Auto generated after Charter Party completion';
    }
            
    $content1='';
    $content1 .='<b>From: '.$FromEmail.'</b><br>';
    $content1 .='<b>To: '.$ToEmail.'</b><br>';
    $content1 .='<b>DateTime : '.date('Y-m-d H:i:s').'</b><br>';
    $content1 .='<b>Subject : '.$mid_tid.'</b><br>';
    $content1 .='<b>Version : '.$FixtureVersion.'</b><br>';
    $content1 .='<b>Status : '.$status.'</b><br>';
    $content1 .='<br>';
            
    $content2=$content1.$content;
            
            
    $data=array(
    'FromEmail'=>$FromEmail,
    'ToEmail'=>$ToEmail,
    'ToEmailID'=>$ToEmailID,
    'MIDTID'=>$mid_tid,
    'DocumentType'=>$document_type,
    'TID'=>$tid,
    'Comment'=>$Comment,
    'UserID'=>$UserID,
    'EntityID'=>$EntityID,
    'SentDate'=>date('Y-m-d H:i:s'),
    'Content'=>$content2,
    'sent_flag'=>0,
    'version'=>$FixtureVersion,
    'status'=>$status,
    'EditableFlag'=>$EditableFlag,
    'url'=>$url
                );    
    $re=$this->db->insert('Udt_AUM_SentEmail', $data);
    return $re;
}
    
    //---------- multi quote differential --------------------
    
public function getCpTextByTemplateID()
{
    $TemplateID=$this->input->post('TemplateID');
    $this->db->select('ClauseText');
    $this->db->from('FixtureNoteTemplateClauses');
    $this->db->where('TemplateID', $TemplateID);
    $qry=$this->db->get();
    return $qry->result();
}
    
    //-------- dashboard view ----------------
    
public function getTotalUnReadMessages()
{
    $EntityID=$this->input->post('EntityID');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AU_Messsage_Details.*');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID = udt_AU_Messsage_Details.UserID', 'left');
    $this->db->where('udt_AU_Messsage_Details.StatusFlag', 1);
    if($EntityID != 1) {
        $this->db->where('udt_UserMaster.EntityID', $EntityID);
        $this->db->where('udt_AU_Messsage_Details.UserID', $UserID);
    }    
    $query=$this->db->get();
    return $query->result();
}
    
public function getChatRecordForCount()
{
    $RecordOwner=$this->input->post('EntityID');
    $this->db->select('udt_AUM_Freight.*, InvEntity.EntityName, udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour, udt_AU_Auctions.OwnerEntityID as OwnerID,OwnerEntity.EntityName as OwnerEntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_EntityMaster as InvEntity', 'InvEntity.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as OwnerEntity', 'OwnerEntity.ID=udt_AU_Auctions.OwnerEntityID', 'Left');
        
    $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
    $this->db->where($where);
        
    $this->db->order_by('udt_AU_Auctions.UserDate', 'DESC');
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getUserGeneralChatCount()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('UserGeneralChat');
    $this->db->where('ToUserID', $UserID);
    $this->db->where('del_status', 1);
    $this->db->where('Status', 1);
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function get_cargo_quote_form_layout($EID)
{
    $this->db->select('CargoQuoteFormFlg');
    $this->db->from('udt_EntityMaster');
    $this->db->where('udt_EntityMaster.ID', $EID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getQuoteDataById()
{ 
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
        
    $this->db->select('udt_AUM_Differentials.*,udt_AUM_Vessel_Master.*,lp.PortName as lpDescription, lp.Code as lpCode, lp.ID as lpID, rp.PortName as rpDescription, rp.Code as rpCode, rp.ID as rpID, dp.PortName as dpDescription, dp.Code as dpCode, dp.ID as dpID');
    $this->db->from('udt_AUM_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AUM_Differentials.DifferentialVesselSizeGroup');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AUM_Differentials.DifferentialLoadport', 'left');
    $this->db->join('udt_PortMaster as rp', 'rp.ID=udt_AUM_Differentials.ReferencePort', 'left');
    $this->db->join('udt_PortMaster as dp', 'dp.ID=udt_AUM_Differentials.DifferentialDisport', 'left'); 
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->result();
    return $data;
}
    
    
public function getInviteesData()
{
    $AuctionId=$this->input->get('AuctionId');
    $q=$this->db->get_where('udt_AUM_Invitees', array('AuctionID'=>$AuctionId));
    return $q->row();
}
    
public function get_quote_html_details_by_line($linenum)
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AUM_Differentials.*,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,lp.PortName as basePort,rp.PortName as refPort,dp.PortName as defPort');
    $this->db->from('udt_AUM_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AUM_Differentials.DifferentialVesselSizeGroup', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AUM_Differentials.DifferentialLoadport', 'left');
    $this->db->join('udt_PortMaster as rp', 'rp.ID=udt_AUM_Differentials.ReferencePort', 'left');
    $this->db->join('udt_PortMaster as dp', 'dp.ID=udt_AUM_Differentials.DifferentialDisport', 'left');
    $this->db->where('udt_AUM_Differentials.AuctionID', $auction);
    $this->db->where('udt_AUM_Differentials.CoCode', $cocode);
    $this->db->where('udt_AUM_Differentials.LineNum', $linenum);
    $query=$this->db->get();
    return $query->result();
}
    
public function sendMail($msg)
{
    $this->load->library('sendemail');  
    $this->load->library('parser'); 
        
    $from = "info@skybullz.com";
    $to="pradeep026kumar@gmail.com";
    $bcc="";
    $subject = "TOPMARX";
    $this->sendemail->sendCustomMail($to, $from, $cc = null, $bcc, $reply_to = null, $subject, $msg);
}
    
public function getAuctionByRecordOwner($OwnerID)
{
    $this->db->select('ModelNumber');
    $this->db->from('udt_AU_Auctions');
    if($OwnerID) {
        $this->db->where('OwnerEntityID', $OwnerID);
    }
    $query=$this->db->get();
    return $query->result();
}
    
public function uploadImageResponse()
{
    extract($this->input->post());
    $document=$_FILES['upload_file'];
    $bucket="hig-sam";
    if (!class_exists('S3')) { include_once APPPATH.'third_party/S3.php';
    }
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php';// getExtension Method 
            
    for($i=0;$i<count($document['name']);$i++){
        $ext=getExtension($document['name'][$i]);
        if($ext=='pdf' || $ext=='PDF') {
            $nar=explode(".", $document['type'][$i]);
            $type=end($nar);
            $file=rand(1, 999999).$document['name'][$i];
            $tmp=$document['tmp_name'][$i];
            $filesize=$document['size'][$i];
            
            $actual_image_name = 'TopMarx/'.$file;
            $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
            $qids=explode("_", $ids);
        
            foreach($qids as $id) {
                
                $file_data = array(
                 'CoCode'=>C_COCODE,
                 'AuctionID'=>$AuctionID,
                 'LineNum'=>$id,
                 'AuctionSection'=>'quote',
                 'FileName'=> $file,
                 'Title'=>'1',
                 'FileSizeKB'=>round($filesize/1024),
                 'FileType'=>$type,
                 'ToDisplay'=>'1', 
                 'ToDisplayInvitee'=>'1', 
                 'DocumentType'=>'charter' 
                );
                
                $res=$this->db->insert('udt_AUM_Documents', $file_data);
                
            }
        }
    }
    return $res;
}
    
public function getLatestFreightQuote($ResponseID)
{
    $this->db->select('udt_AU_FreightResponse.*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('udt_AU_FreightResponse.ResponseID', $ResponseID);
    $this->db->order_by('FreightResponseID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getQuoteQuthorizationBrokerByQBPID()
{
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('FreightResponseID', $FreightResponseID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function get_quote_html_details_by_TID()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
    }
        
    $this->db->select('udt_AU_Freight.*,udt_CurrencyMaster.Code as curCode');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_Freight.FreightCurrency', 'left');
    $this->db->where('udt_AU_Freight.ResponseID', $InviteeID);
    $this->db->order_by('udt_AU_Freight.FreightID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getUnseenMessageByRecordOwner()
{
    $RecordOwner=$this->input->post('RecordOwner');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AUM_Freight.ResponseID');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
    $this->db->where($where);
    $this->db->order_by('udt_AU_Auctions.UserDate', 'DESC');
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getGeneralChatUserData($id)
{ 
    $cocode=C_COCODE;
    $this->db->select('udt_UserMaster.*, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.City');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
    $this->db->where('udt_UserMaster.EntityID', $id);
    $this->db->where('udt_UserMaster.GeneralChatFlag', '1');
    $this->db->order_by("udt_UserMaster.LoginID", "asc");
    $query=$this->db->get();
    $UserData=$query->result();
    return $UserData;
}
    
public function saveUserPreference()
{
    extract($this->input->post());
        
    $ret=0;
    for($i=0;$i<count($userid);$i++) {
        $doc='';
        $validfrom='';
        $validto='';
        if($date_for_comencement) {
            $doc=date('Y-m-d', strtotime($date_for_comencement));
        }
        if($validity_from) {
            $validfrom=date('Y-m-d', strtotime($validity_from));
        }
        if($validity_to) {
            $validto=date('Y-m-d', strtotime($validity_to));
        }
            $data=array('CreateUserID'=>$UserID,'RecordOwner'=>$OwnerEntityID,'OwnerID'=>$OwnerID,'EntityID'=>$entityID,'Validity'=>$validity,'date_for_comencement'=>$doc,'validity_from'=>$validfrom,'validity_to'=>$validto,'UserID'=>$userid[$i],'CreatedDateTime'=>date('Y-m-d H:i:s'));
            $ret=$this->db->insert('UserPreference', $data);
    }
    return $ret;
}
    
public function deleteUserPreference($ID)
{
    $this->db->where('ID', $ID);
    $this->db->delete('UserPreference');
}
    
public function getGeneralChatMessage()
{
    $ToUserID=$this->input->post('ToUserID');
    $FromUserID=$this->input->post('FromUserID');
    $where="((FromUserID=$FromUserID and ToUserID=$ToUserID) or (FromUserID=$ToUserID and ToUserID=$FromUserID))";
        
    $this->db->select('*');
    $this->db->from('UserGeneralChat');
    $this->db->where($where);
    $query=$this->db->get();
    $rslt=$query->result();
        
    $data=array('Status'=>0);
    $this->db->where('ToUserID', $FromUserID);
    $this->db->where('FromUserID', $ToUserID);
    $this->db->update('UserGeneralChat', $data);
    return $rslt;
}
    
public function getUserMessageCount($FromUserID,$ToUserID)
{
    $this->db->select('*');
    $this->db->from('UserGeneralChat');
    $this->db->where('ToUserID', $FromUserID);
    $this->db->where('FromUserID', $ToUserID);
    $this->db->where('del_status', 1);
    $this->db->where('Status', 1);
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function getUnseenGeneralMessageByRecordOwner()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('UserPreference');
    $this->db->where('OwnerID', $EntityID);
    $query=$this->db->get();
    return $query->result(); 
}
    
public function userLoginMobile()
{
    extract($this->input);
        
    $this->db->select('udt_UserMaster.ID as UID,udt_UserMaster.FirstName,udt_UserMaster.LastName, udt_UserMaster.LoginID, udt_UserMaster.UserType, udt_EntityMaster.ID as EID, udt_EntityMaster.EntityName');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->where('udt_UserMaster.LoginID', $userID);
    $this->db->where('udt_UserMaster.Password', $passowrd);
    $query=$this->db->get();
    return $query->row();
}
    
public function deleteLastMessage()
{
    $chat_message_id=$this->input->post('chat_message_id');
    $data=array('del_flag'=>0);
    $this->db->where('chat_message_id', $chat_message_id);
    return $this->db->update('chat_message', $data);
}
    
public function getUserPreferences($EntityID)
{
    $this->db->select('UserPreference.*,udt_UserMaster.ID,udt_UserMaster.FirstName,udt_UserMaster.MiddleName,udt_UserMaster.LastName, udt_EntityMaster.EntityName,udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.City');
    $this->db->from('UserPreference');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=UserPreference.UserID');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=UserPreference.EntityID', 'Left');
    $this->db->where('UserPreference.OwnerID', $EntityID);
    $this->db->order_by('UserPreference.LastMsgTime', 'DESC');
    $query=$this->db->get();
    $UserData=$query->result();
    return $UserData;
}
    
public function getGeneralLastMessage($FromUserID,$ToUserID)
{
    $where="((ToUserID=".$FromUserID." and FromUserID=".$ToUserID." ) or (ToUserID=".$ToUserID." and FromUserID=".$FromUserID.")) and (del_status=1)";
    $this->db->select('*');
    $this->db->from('UserGeneralChat');
    $this->db->where($where);
    $this->db->order_by('UCID', 'DESC');
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt;
}
    
public function saveGeneralChatMessage()
{
    extract($this->input->post());
    $udata=array('LastMsgTime'=>date('Y-m-d H:i:s'));
    $this->db->where('OwnerID', $to_entity_id);
    $this->db->where('UserID', $FromUserID);
    $this->db->update('UserPreference', $udata);
    $data=array('EntityName'=>$EntityName,'EntityID'=>$EntityID,'ChatText'=>$chat_text,'UserName'=>$UserName,'FromUserID'=>$FromUserID,'ToUserID'=>$ToUserID,'Timestamp'=>date('Y-m-d H:i:s'),'Status'=>1);
    return $this->db->insert('UserGeneralChat', $data);
}
    
public function getAttachPhotoByUserID($UserID)
{
    $this->db->select('AttachPhoto');
    $this->db->from('udt_AU_SignatureBlock');
    $this->db->where('UserID', $UserID);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->AttachPhoto;
}
    
public function deleteGeneralMessageByUcid($UCID)
{
    $data=array('del_status'=>0);
    $this->db->where('UCID', $UCID);
    $this->db->update('UserGeneralChat', $data);
}
    
public function editResponseBrokerageCargo()
{
    $BACResponse_ID=$this->input->post('BACResponse_ID');
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse');
    $this->db->where('BACResponse_ID', $BACResponse_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getVettingStatusByTid()
{
    $TID=$this->input->post('TID');
    $this->db->select('avid');
    $this->db->from('Udt_AU_ApproveVetting');
    $this->db->where('TID', $TID);
    $query=$this->db->get();
    return $query->row();
}

	
}


