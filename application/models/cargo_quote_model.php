<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class cargo_quote_model extends CI_Model {
function __construct()
{
    parent::__construct();        
        
} 
    
public function getDocumentForResponseByAction($auctionID)
{
    $type=$this->input->get('type');
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('udt_AUM_Documents.AuctionID', $auctionID);
    $this->db->where('udt_AUM_Documents.AuctionSection', $type);
    $this->db->where('udt_AUM_Documents.ToDisplayInvitee', '1');
    return $this->db->get()->result();
} 
    
public function getDocumentForInvitee($auctionID)
{
    $type=$this->input->get('type');
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('udt_AUM_Documents.AuctionID', $auctionID);
    $this->db->where('udt_AUM_Documents.AuctionSection', $type);
    return $this->db->get()->result();
} 
    
public function get_last_vessel_chat()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('udt_AU_UserChat.InviteeID', $InviteeID);
    $this->db->where('udt_AU_UserChat.Type', 'Vessel');
    if($LineNum) {
        $this->db->where('udt_AU_UserChat.LineNum', $LineNum);
    }
        
    $this->db->order_by('Chat_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_last_freight_chat()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('udt_AU_UserChat.InviteeID', $InviteeID);
    if($LineNum) {
        $this->db->where('udt_AU_UserChat.LineNum', $LineNum);
    }
    $this->db->where('udt_AU_UserChat.Type', 'Freight');
    $this->db->order_by('Chat_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_last_cargo_chat()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('udt_AU_UserChat.InviteeID', $InviteeID);
    if($LineNum) {
        $this->db->where('udt_AU_UserChat.LineNum', $LineNum);
    }
    $this->db->where('udt_AU_UserChat.Type', 'CargoPort');
    $this->db->order_by('Chat_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_last_term_chat()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('udt_AU_UserChat.InviteeID', $InviteeID);
    if($LineNum) {
        $this->db->where('udt_AU_UserChat.LineNum', $LineNum);
    }
    $this->db->where('udt_AU_UserChat.Type', 'Terms');
    $this->db->order_by('Chat_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_vessel_html_details1()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AU_ResponseVessel.*, udt_EntityMaster.EntityName, udt_EntityMaster.AssociateCompanyID, udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description, udt_StateMaster.Code as S_Code, udt_StateMaster.Description as S_Description');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_ResponseVessel.DisponentOwnerID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_ResponseVessel.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AU_ResponseVessel.StateID', 'left');
    $this->db->where('udt_AU_ResponseVessel.ResponseID', $InviteeID);
    $this->db->order_by('udt_AU_ResponseVessel.ResponseVesselID', 'DESC');
    $query=$this->db->get();
    return $query->row();
        
}
    
public function get_invitee_document_filename()
{
    $InvDocID=$this->input->get('InvDocID');
    $this->db->select('FileAttachName');
    $this->db->from('udt_AUM_InviteeDocuments');
    $this->db->where('InvDocID', $InvDocID);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->FileAttachName;
}
    
public function download_invitee_document()
{
    $id=$this->input->get('id');
    $this->db->select('FileName');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('DocumentID', $id);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->FileName;
}
    
public function getResponse()
{
    /* $id=$this->input->get('id'); */
    $UserID=$this->input->get('UserID');
    $RecordOwner=$this->input->get('RecordOwner');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $masterID=$this->input->get('masterID');
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
    $this->db->select('udt_AUM_Freight.*, InvEntity.EntityName, udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour, udt_AU_Auctions.OwnerEntityID as OwnerID,OwnerEntity.EntityName as OwnerEntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_EntityMaster as InvEntity', 'InvEntity.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as OwnerEntity', 'OwnerEntity.ID=udt_AU_Auctions.OwnerEntityID', 'Left');
        
    $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
    $this->db->where($where);
        
    if($masterID) {
        $this->db->where('udt_AUM_Freight.AuctionID', $masterID);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate >=', date('Y-m-d', strtotime($dateFrom)));
    }
    if($dateTo) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }
    $this->db->order_by('udt_AU_Auctions.AuctionReleaseDate', 'DESC');
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponse1()
{
    /* $id=$this->input->get('id'); */
    $UserID=$this->input->get('UserID');
    $RecordOwner=$this->input->get('RecordOwner');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $masterID=$this->input->get('masterID');
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
    $this->db->select('udt_AUM_Freight.*, InvEntity.EntityName, udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour,  udt_AU_Auctions.OwnerEntityID as OwnerID,OwnerEntity.EntityName as OwnerEntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_EntityMaster as OwnerEntity', 'OwnerEntity.ID=udt_AU_Auctions.OwnerEntityID', 'Left');
    $this->db->join('udt_EntityMaster as InvEntity', 'InvEntity.ID=udt_AUM_Freight.EntityID', 'Left');
        
    $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
    $this->db->where($where);
        
    if($masterID) {
        $this->db->where('udt_AUM_Freight.AuctionID', $masterID);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate >=', date('Y-m-d', strtotime($dateFrom)));
    }
    if($dateTo) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }
    $this->db->order_by('udt_AU_Auctions.AuctionReleaseDate', 'DESC');
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
        
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseById()
{
    $respoanse=$this->input->post('respoanse');
    $this->db->select('udt_AUM_Freight.AuctionID,udt_AUM_Freight.ResponseID,udt_AUM_Freight.EntityID,udt_AUM_Alerts.AuctionCeases,udt_AUM_Alerts.auctionceaseshour,udt_AUM_Alerts.ExtendTime1,udt_AUM_Alerts.ExtendTime2,udt_AUM_Alerts.ExtendTime3');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'left');
    $this->db->where('ResponseID', $respoanse);
    $query=$this->db->get();
    return $query->row();
}
    
public function getAuctionInviteePrimeRole($AuctionID,$EntityID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $EntityID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getQuoteInviteeBusinessProcess($ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('TID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getVesselLatestRecordByResponseID($ResponseID)
{
    $this->db->select('VesselConfirmFlg');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('ResponseVesselID', 'DESC');
    $query1=$this->db->get();
    return $query1->row();
}
    
public function saveChat()
{
    $EntityID=$this->input->post('ResponseID');
    $AdUs=$this->input->post('AdUs');
    $Invname=$this->input->post('Invname');
    $Chat=$this->input->post('Chat');
    $Type=$this->input->post('Type');
    $userName=$this->input->post('userName');
    $responsid=$this->input->post('responsid');
    $AuctionID=$this->input->post('AuctionID');
    $EntityName=$this->input->post('EntityName');
    $LineNum=$this->input->post('LineNum');
    $UserID=$this->input->post('UserID');
    $chk_flag=$this->input->post('chk_flag');
    $ConfirmationFlg=$this->input->post('ConfirmationFlg');
    $InvFlg=$this->input->post('InvFlg');
        
    $data=array(
    'ResponseID'=>$EntityID,
    'LineNum'=>$LineNum,
    'InviteeID'=>$responsid,
    'AdUs'=>$AdUs,
    'Invname'=>$Invname,
    'Chat'=>$Chat,
    'Chat_time'=>date('Y-m-d H:i:s'),
    'Type'=>$Type,
    'chk_flag'=>$chk_flag,
    'UserID'=>$UserID
                );
                
    $this->db->insert('udt_AU_UserChat', $data);
        
    $msg_flag=0;
    if($Type=='Vessel') {
        $msg_flag=1;
    } else if($Type=='Freight') {
        $msg_flag=2;
    } else if($Type=='CargoPort') {
        $msg_flag=3;
    } else if($Type=='Terms') {
        $msg_flag=4;
    }
        
    $data=array('MasterID'=>$AuctionID,'TID'=>$responsid,'EntityName'=>$EntityName,'EntityID'=>$EntityID,'Chat_text'=>$Chat,'UserName'=>$userName,'UserID'=>$UserID,'Timestamp'=>date('Y-m-d H:i:s'),'Status'=>1,'msg_flag'=>$msg_flag,'LineNum'=>$LineNum,'include_fn'=>$chk_flag);
    $this->db->insert('chat_message', $data);
        
    $VesselOwnerFlag=0;
    $VesselInviteeFlag=0;
    $FreightOwnerFlag=0;
    $FreightInviteeFlag=0;
    $CargoPortOwnerFlag=0;
    $CargoPortInviteeFlag=0;
    $TermOwnerFlag=0;
    $TermInviteeFlag=0;
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserChatMessageAlert');
    $this->db->where('ResponseID', $responsid);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    $qryRow=$qry->row();
    if(count($qryRow)> 0) {
        if($Type=='Vessel') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('VesselOwnerFlag'=>1,'VesselInviteeFlag'=>1));
        } else if($Type=='Freight') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('FreightOwnerFlag'=>1,'FreightInviteeFlag'=>1));
        } else if($Type=='CargoPort') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('CargoPortOwnerFlag'=>1,'CargoPortInviteeFlag'=>1));
        } else if($Type=='Terms') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('TermOwnerFlag'=>1,'TermInviteeFlag'=>1));
        }
    } else {
        if($Type=='Vessel') {
            $VesselOwnerFlag=1;
            $VesselInviteeFlag=1;
        } else if($Type=='Freight') {
            $FreightOwnerFlag=1;
            $FreightInviteeFlag=1;
        } else if($Type=='CargoPort') {
            $CargoPortOwnerFlag=1;
            $CargoPortInviteeFlag=1;
        } else if($Type=='Terms') {
            $TermOwnerFlag=1;
            $TermInviteeFlag=1;
        }
            $data_alert=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$responsid,
                        'LineNum'=>$LineNum,
                        'VesselOwnerFlag'=>$VesselOwnerFlag,
                        'VesselInviteeFlag'=>$VesselInviteeFlag,
                        'FreightOwnerFlag'=>$FreightOwnerFlag,
                        'FreightInviteeFlag'=>$FreightInviteeFlag,
                        'CargoPortOwnerFlag'=>$CargoPortOwnerFlag,
                        'CargoPortInviteeFlag'=>$CargoPortInviteeFlag,
                        'TermOwnerFlag'=>$TermOwnerFlag,
                        'TermInviteeFlag'=>$TermInviteeFlag
                    );
            $this->db->insert('udt_AU_UserChatMessageAlert', $data_alert);
    }
    if($InvFlg==1 && $ConfirmationFlg==1) {
        $f_data=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'',
        'conf2'=>'',
        'conf3'=>'',
        'conf4'=>'',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else if($InvFlg==1 && $ConfirmationFlg==0) {
        $f_data=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'on',
        'conf2'=>'on',
        'conf3'=>'on',
        'conf4'=>'on',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else {
        $f_data=array(
        'ResponseStatus'=>'Inprogress',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    }
        
                    
     $this->db->where('ResponseID', $responsid);
     $this->db->update('udt_AUM_Freight', $f_data);
        
     $this->db->select('Status,EntityID');
     $this->db->from('udt_AUM_Freight');
     $this->db->where('udt_AUM_Freight.AuctionID', $AuctionID);
     $this->db->where('udt_AUM_Freight.ResponseID', $responsid);
     $Freightquery=$this->db->get();
     $FreightRecord=$Freightquery->row();
        
    if($FreightRecord->Status == 3 ) { 
        $this->db->select('OwnerEntityID,udt_EntityMaster.EntityName');
        $this->db->from('udt_AU_Auctions');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
        $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
        $query=$this->db->get();
        $Owner=$query->row();
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '14');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $where=' ( cops_admin.udt_AUM_MESSAGE_MASTER.EntityID='.$Owner->OwnerEntityID.' or ( cops_admin.udt_AUM_MESSAGE_MASTER.EntityID='.$FreightRecord->EntityID.' and cops_admin.udt_AUM_MESSAGE_MASTER.RecordOwner='.$Owner->OwnerEntityID.' ) ) ';
        $this->db->where($where);
        $query1=$this->db->get();
        $msgRecord=$query1->result();
            
        foreach($msgRecord as $row){
            $messageContent=" <br> New comment received in TID : ".$responsid;
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$responsid,    
            'Event'=>'Cargo Set Up (Quotes) (invitee comment)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'Invitee Comments',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$messageContent,    
            'MessageMasterID'=>$row->MessageID,    
            'UserID'=>$row->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }    
    } 
        
     $this->db->where('AuctionID', $AuctionID);
     $ret=$this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        
     return $ret;
}
    
public function getChat()
{ 
    if($this->input->post()) {
        $ResponseID=$this->input->post('ResponseID');
        $Type=$this->input->post('Type');
        $LineNum=$this->input->post('LineNum');
    }

    if($this->input->get()) {
        $ResponseID=$this->input->get('ResponseID');
        $Type=$this->input->get('Type');
        $LineNum=$this->input->get('LineNum');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('Type', $Type);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('Chat_id', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function confirmation()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query_fright=$this->db->get();
    $freight=$query_fright->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $querycounter=$this->db->get();
    $resultcounter=$querycounter->row()->FreightCounter+1;
        
    $data_h= array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$freight->AuctionID,
                'LineNum'=>$freight->LineNum,
                'FreightBasis'=>$freight->FreightBasis,
                'FreightRate'=>$freight->FreightRate,
                'FreightCurrrency'=>$freight->FreightCurrrency,
                'FreightRateUOM'=>$freight->FreightRateUOM,
                'FreightTce'=>$freight->FreightTce,
                'FreightTceDifferential'=>$freight->FreightTceDifferential,
                'FreightLumpsumMax'=>$freight->FreightLumpsumMax,
                'FreightLow'=>$freight->FreightLow,
                'FreightHigh'=>$freight->FreightHigh,
                'Demurrage'=>$freight->Demurrage,
                'DespatchDemurrageFlag'=>$freight->DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$freight->DespatchHalfDemurrage,
                'DifferentialInvitee'=>$freight->DifferentialInvitee,
                'CommentsByInvitee'=>$freight->CommentsByInvitee,
                'ResponseStatus'=>'Inprogress',
                'Status'=>'2',
                'UserID'=>$freight->UserID,
                'EntityID'=>$freight->EntityID,
                'ReleaseDate'=>$freight->ReleaseDate,
                'conf1'=>$conf1,
                'conf2'=>$conf2,
                'conf3'=>$conf3,
                'conf4'=>$conf4,
                'UserName'=>$UserName,
                'UserID1'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'status_UserName'=>$freight->status_UserName,
                'status_UserID'=>$freight->status_UserID,
                'status_UserDate'=>$freight->status_UserDate,
                'ReadyToSubmit'=>$freight->ReadyToSubmit,
                'TentativeStatus'=>$freight->TentativeStatus,
                'ResponseID'=>$ResponseID,
                'FreightCounter'=>$resultcounter,
                'RowStatus'=>'2'
                );
    $this->db->insert('udt_AUM_Freight_H', $data_h); 
        
    $this->db->update('udt_AU_Counter', array('FreightCounter'=>$resultcounter));
        
    $data=array(
    'conf1'=>$conf1,
    'conf2'=>$conf2,
    'conf3'=>$conf3,
    'conf4'=>$conf4,
    'UserID1'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    'ResponseStatus'=>'Inprogress',
    'Status'=>'2',
    'UserName'=>$UserName,
    'change_status'=>1
                );
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AUM_Freight', $data);
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AU_FreightResponseAssessment', $data);

    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $reslt=$query->row();
        
    $data1=array('link'=>1);
    $this->db->where('EntityID', $reslt->OwnerEntityID);
    $this->db->where('Status', 3);
    $this->db->where('Application', 2);
    return $this->db->update('AUM_TermCondition', $data1); 
        
}
    
public function getUser()
{
    $ResponseID=$this->input->post('ResponseID');
        
    $this->db->select('UserID1, UserName, UserDate, conf1, conf2, conf3, conf4, status_UserName, status_UserID, status_UserDate, ReadyToSubmit, Status, FinalConfirm, udt_AUM_Freight.EntityID as InviteeEntity');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function finalSubmit()
{
    extract($this->input->post());
        
    if($rdtytosubmit=='yes' && ($status== 3 || $status== 4)) {
        $this->db->select('OwnerEntityID,udt_EntityMaster.EntityName');
        $this->db->from('udt_AU_Auctions');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
        $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
        $query=$this->db->get();
        $Owner=$query->row();
        
        // owner messages
        $this->db->select('udt_AUM_MESSAGE_MASTER.*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '13');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query1=$this->db->get();
        $msgResult=$query1->result();
        
        $msgContent=''; 
        $event='';
        if($status== 3) {
            $event='Cargo Set Up (Quotes) submitted';
        } else if($status== 4) {
            $event='Cargo Set Up (Quotes) submitted (on subs)';
        }
        foreach($msgResult as $r){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>$event,    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'Quote Status',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgContent,    
            'MessageMasterID'=>$r->MessageID,    
            'UserID'=>$r->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
        
        if(count($msgResult) > 0) {
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
        
        //invitee messages
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID');    
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '13');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query=$this->db->get();
        $msgResult1=$query->result();
        
        $msgContent1=''; 
        foreach($msgResult1 as $inv){
            $msg1=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (submitted)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'Quote Status',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgContent1,    
            'MessageMasterID'=>$inv->MessageID,    
            'UserID'=>$inv->ForUserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg1);
        }
        if(count($msgResult1) > 0) {
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }    
        
                
        $this->db->select('*');
        $this->db->from('udt_AUM_Freight');
        $this->db->where('ResponseID', $ResponseID);
        $query_fright=$this->db->get();
        $freight=$query_fright->row();
        
        $this->db->select('*');
        $this->db->from('udt_AU_Counter');
        $querycounter=$this->db->get();
        $resultcounter=$querycounter->row()->FreightCounter+1;
        
        $data_h= array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$freight->AuctionID,
                'LineNum'=>$freight->LineNum,
                'FreightBasis'=>$freight->FreightBasis,
                'FreightRate'=>$freight->FreightRate,
                'FreightCurrrency'=>$freight->FreightCurrrency,
                'FreightRateUOM'=>$freight->FreightRateUOM,
                'FreightTce'=>$freight->FreightTce,
                'FreightTceDifferential'=>$freight->FreightTceDifferential,
                'FreightLumpsumMax'=>$freight->FreightLumpsumMax,
                'FreightLow'=>$freight->FreightLow,
                'FreightHigh'=>$freight->FreightHigh,
                'Demurrage'=>$freight->Demurrage,
                'DespatchDemurrageFlag'=>$freight->DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$freight->DespatchHalfDemurrage,
                'DifferentialInvitee'=>$freight->DifferentialInvitee,
                'CommentsByInvitee'=>$freight->CommentsByInvitee,
                'UserID'=>$freight->UserID,
                'EntityID'=>$freight->EntityID,
                'ReleaseDate'=>$freight->ReleaseDate,
                'conf1'=>$freight->conf1,
                'conf2'=>$freight->conf2,
                'conf3'=>$freight->conf3,
                'UserName'=>$freight->UserName,
                'UserID1'=>$freight->UserID1,
                'UserDate'=>$freight->UserDate,
                'ResponseStatus'=>'Submitted',
                'Status'=>'3',
                'status_UserName'=>$UserName,
                'status_UserID'=>$UserID,
                'status_UserDate'=>date('Y-m-d H:i:s'),
                'ReadyToSubmit'=>'yes',
                'TentativeStatus'=>$freight->TentativeStatus,
                'ResponseID'=>$ResponseID,
                'FreightCounter'=>$resultcounter,
                'FinalConfirm'=>$FinalConfirm,
                'RowStatus'=>'2'
        );
        
        $this->db->insert('udt_AUM_Freight_H', $data_h); 
        
        $this->db->update('udt_AU_Counter', array('FreightCounter'=>$resultcounter));
        
        $data=array(
                    'status_UserID'=>$UserID,
                    'ResponseStatus'=>'Submitted',
                    'status_UserDate'=>date('Y-m-d H:i:s'),
                    'status_UserName'=>$UserName,
                    'ReadyToSubmit'=>'yes',
                    'FinalConfirm'=>$FinalConfirm,
                    'Status'=>$status,
                    'change_status'=>1
                );
                    
        $this->db->where('ResponseID', $ResponseID);
        $ret=$this->db->update('udt_AUM_Freight', $data);
        
        $this->db->where('ResponseID', $ResponseID);
        $this->db->update('udt_AU_FreightResponseAssessment', $data);

        return $ret;
        
    } else if($status==2) {
        $data=array(
        'status_UserID'=>$UserID,
        'ResponseStatus'=>'Inprogress',
        'status_UserDate'=>date('Y-m-d H:i:s'),
        'status_UserName'=>$UserName,
        'ReadyToSubmit'=>'no',
        'FinalConfirm'=>$FinalConfirm,
        'Status'=>$status,
        'change_status'=>1
        );    
        $this->db->where('ResponseID', $ResponseID);
        return $this->db->update('udt_AUM_Freight', $data);
    }  else {
        return 0;
    }
}
    
public function checkChat($invID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $invID);
    $query=$this->db->get();
    return $query->num_rows();
}
    
public function getResponseCommentHtml()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $chatSection=$this->input->post('chatSection');
    }

    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $chatSection=$this->input->get('chatSection');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $InviteeID);
    if($chatSection != 'TimeLine') {
        $this->db->order_by('LineNum', 'asc');
    }
    $this->db->order_by('Chat_id', 'desc');
    if($chatSection == '1') {
        $this->db->where('Type', 'Vessel');
    } else if($chatSection == '2') {
        $this->db->where('Type', 'Freight');
    } else if($chatSection == '3') {
        $this->db->where('Type', 'CargoPort');
    } else if($chatSection == '4') {
        $this->db->where('Type', 'Terms');
    }
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseAssessment()
{
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
        
    $this->db->select('udt_AUM_Freight.*,udt_EntityMaster.EntityName,udt_AU_Cargo.LpPreferDate,udt_AU_Cargo.CargoQtyMT,udt_AU_Cargo.Estimate_Index_mt,udt_AU_Cargo.Estimate_mt');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID', 'left');
    $this->db->join('udt_AU_Cargo', 'udt_AU_Cargo.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->where('udt_AUM_Freight.AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getQuoteByAuctionID($AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('FreightRate >', 0);
    $this->db->order_by('FreightRate', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function deleteChat()
{
    
    $ResponseID=$this->input->post('ResponseID');
    $EntityID=$this->input->post('EntityID');;
    $LineNum=$this->input->post('LineNum');;
    $Type=$this->input->post('Type');
    $this->db->select('Chat_id');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('ResponseID', $EntityID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Type', $Type);
    $this->db->order_by('Chat_id', 'DESC');
    $query=$this->db->get();
    $chatID=$query->row()->Chat_id;
        
    $this->db->where('Chat_id', $chatID);
    return $this->db->delete('udt_AU_UserChat'); 
        
}
    
public function getResponseAssessment1()
{
    if($this->input->post()) {
        $RecordOwner=$this->input->post('RecordOwner');
    }
        
    if($this->input->get()) {
        $RecordOwner=$this->input->get('RecordOwner');
    }
        
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
    $this->db->order_by('udt_AUM_Freight.UserDateTentative', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function updateTentetive()
{
    $ResponseID=$this->input->post('InviteeID');
    $Rating=$this->input->post('Rating');
    $AuctionID=$this->input->post('AuctionId');
    $acceptance_resion=$this->input->post('acceptance_resion');
    $confirm1=$this->input->post('confirm1');
    $UserName=$this->input->post('UserName');
        
    $data1=array('TentativeStatus'=>1,'TotalRating'=>$Rating,'acceptance_resion'=>$acceptance_resion,'confirm1'=>$confirm1,'UserNameTentative'=>$UserName,'UserDateTentative'=>Date('Y-m-d H:i:s'));
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query_fright=$this->db->get();
    $frow=$query_fright->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $querycounter=$this->db->get();
    $resultcounter=$querycounter->row()->FreightCounter+1;
        
    $data_h= array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$frow->AuctionID,
    'LineNum'=>$frow->LineNum,
    'FreightBasis'=>$frow->FreightBasis,
    'FreightRate'=>$frow->FreightRate,
    'FreightCurrrency'=>$frow->FreightCurrrency,
    'FreightRateUOM'=>$frow->FreightRateUOM,
    'FreightTce'=>$frow->FreightTce,
    'FreightTceDifferential'=>$frow->FreightTceDifferential,
    'FreightLumpsumMax'=>$frow->FreightLumpsumMax,
    'FreightLow'=>$frow->FreightLow,
    'FreightHigh'=>$frow->FreightHigh,
    'Demurrage'=>$frow->Demurrage,
    'DespatchDemurrageFlag'=>$frow->DespatchDemurrageFlag,
    'DespatchHalfDemurrage'=>$frow->DespatchHalfDemurrage,
    'DifferentialInvitee'=>$frow->DifferentialInvitee,
    'CommentsByInvitee'=>$frow->CommentsByInvitee,
    'UserID'=>$frow->UserID,
    'EntityID'=>$frow->EntityID,
    'ReleaseDate'=>$frow->ReleaseDate,
    'conf1'=>$frow->conf1,
    'conf2'=>$frow->conf2,
    'conf3'=>$frow->conf3,
    'UserName'=>$frow->UserName,
    'UserID1'=>$frow->UserID1,
    'UserDate'=>$frow->UserDate,
    'ResponseStatus'=>$frow->ResponseStatus,
    'Status'=>$frow->Status,
    'status_UserName'=>$frow->status_UserName,
    'status_UserID'=>$frow->status_UserID,
    'status_UserDate'=>$frow->status_UserDate,
    'ReadyToSubmit'=>$frow->ReadyToSubmit,
    'TentativeStatus'=>1,
    'TotalRating'=>$Rating,
    'acceptance_resion'=>$acceptance_resion,
    'confirm1'=>$confirm1,
    'UserNameTentative'=>$UserName,
    'UserDateTentative'=>Date('Y-m-d H:i:s'),
    'ResponseID'=>$ResponseID,
    'FreightCounter'=>$resultcounter,
    'RowStatus'=>'2'
    );
            
    $this->db->insert('udt_AUM_Freight_H', $data_h);
        
    $this->db->update('udt_AU_Counter', array('FreightCounter'=>$resultcounter));
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AUM_Freight', $data1);
}
    
public function sendUpdateTentetiveMessages()
{
    $ResponseID=$this->input->post('InviteeID');
    $AuctionID=$this->input->post('AuctionId');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query_fright=$this->db->get();
    $frow=$query_fright->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $auction_fright=$this->db->get();
    $auctionrow=$auction_fright->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID', 'left');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $auctionrow->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '23');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $frow->UserID);
    $query1=$this->db->get();
    $msgRecords=$query1->result();
        
    $msgDetails='<br>Cargo Set Up (Quotes) (TA) on : '.date('d-m-Y'); 
    foreach($msgRecords as $mr){
        $msg=array(
        'CoCode'=>C_COCODE,    
        'AuctionID'=>$AuctionID,    
        'ResponseID'=>$ResponseID,    
        'Event'=>'Cargo Set Up (Quotes) (TA)',    
        'Page'=>'Cargo Set Up (Quotes)',    
        'Section'=>'',    
        'subSection'=>'',    
        'StatusFlag'=>'1',    
        'MessageDetail'=>$msgDetails,    
        'MessageMasterID'=>$mr->MessageID,    
        'UserID'=>$mr->ForUserID,    
        'FromUserID'=>$UserID,    
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_Messsage_Details', $msg);
    }
        
    $ownerarr[] =$frow->UserID;
        
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.Status', 1);
    $bus_query=$this->db->get();
    $bus_Record=$bus_query->result();
        
    foreach($bus_Record as $bus){
        if($bus->UserList) {
            $busUserIds=explode(",", $bus->UserList);
            for($i=0;$i<count($busUserIds); $i++ ) {
                if(!in_array($busUserIds[$i], $ownerarr)) {
                    $ownerarr[] =$busUserIds[$i];
                    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
                    $this->db->from('udt_AUM_MESSAGE_MASTER');
                    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID', 'left');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
                    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $auctionrow->OwnerEntityID);
                    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '23');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $busUserIds[$i]);
                    $query1=$this->db->get();
                    $msgRecord=$query1->row();
                        
                    if($msgRecord) {
                        $msgDetails='<br>Cargo Set Up (Quotes) (TA) on : '.date('d-m-Y'); 
                        $msg=array(
                         'CoCode'=>C_COCODE,    
                         'AuctionID'=>$AuctionID,    
                         'ResponseID'=>$ResponseID,    
                         'Event'=>'Cargo Set Up (Quotes) (TA)',    
                         'Page'=>'Cargo Set Up (Quotes)',    
                         'Section'=>'',    
                         'subSection'=>'',    
                         'StatusFlag'=>'1',    
                         'MessageDetail'=>$msgDetails,    
                         'MessageMasterID'=>$msgRecord->MessageID,    
                         'UserID'=>$msgRecord->ForUserID,    
                         'FromUserID'=>$UserID,        
                         'UserDate'=>date('Y-m-d H:i:s')    
                        );
                        $this->db->insert('udt_AU_Messsage_Details', $msg);
                    }
                }
            }
        }
    }
    $invIds=explode(",", $frow->InvUserID);
        
    for($i=0; $i<count($invIds); $i++){
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '23');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $invIds[$i]);
        $query1=$this->db->get();
        $msgRecord=$query1->row();
            
        if($msgRecord) {
            $msgDetails='<br>Cargo Set Up (Quotes) (TA) on : '.date('d-m-Y'); 
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (TA)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$msgRecord->MessageID,    
            'UserID'=>$msgRecord->ForUserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                
        }
    }
        
    $bUsersArr =array();
        
    if($frow->EntityID != $frow->ShipOwnerID) {
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseBrokerUsers');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('SigningUserEntity', $frow->ShipOwnerID);
        $this->db->where('Status', 1);
        $auction_fright=$this->db->get();
        $BrokerUsers=$auction_fright->result();
            
        foreach($BrokerUsers as $bu){
            if(!in_array($bu->SigningUserID, $bUsersArr)) {
                $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
                $this->db->from('udt_AUM_MESSAGE_MASTER');
                $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
                $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
                $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '23');
                $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
                $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $bu->SigningUserID);
                $query1=$this->db->get();
                $msgRecord=$query1->row();
                    
                if($msgRecord) {
                    $msgDetails='<br>Cargo Set Up (Quotes) (TA) on : '.date('d-m-Y'); 
                    $msg=array(
                    'CoCode'=>C_COCODE,    
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,    
                    'Event'=>'Cargo Set Up (Quotes) (TA)',    
                    'Page'=>'Cargo Set Up (Quotes)',    
                    'Section'=>'',    
                    'subSection'=>'',    
                    'StatusFlag'=>'1',    
                    'MessageDetail'=>$msgDetails,    
                    'MessageMasterID'=>$msgRecord->MessageID,    
                    'UserID'=>$msgRecord->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')    
                    );
                    $this->db->insert('udt_AU_Messsage_Details', $msg);
                }
                $bUsersArr[] =$bu->SigningUserID;
            }
        }
            
    }
        
}
    
public function getVesselDataByAuctionID($AuctionID,$responseids)
{
    $where="AuctionID='$AuctionID' and SelectVesselBy is not null";
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where($where);
    $this->db->where_in('ResponseID', $responseids);
    $this->db->order_by('ResponseID', 'DESC');
    $this->db->order_by('ResponseVesselID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getAdminResponse()
{
    $RecordOwner=$this->input->get('RecordOwner');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $masterID=$this->input->get('masterID');
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
        
    $this->db->select('udt_AUM_Freight.*, InvEntity.EntityName, udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour, udt_AU_Auctions.OwnerEntityID as OwnerID, OwnerEntity.EntityName as OwnerEntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_EntityMaster as InvEntity', 'InvEntity.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as OwnerEntity', 'OwnerEntity.ID=udt_AU_Auctions.OwnerEntityID', 'Left');
        
    if($RecordOwner) {
        $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AUM_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
        $this->db->where($where);
    } else {
        $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A'";
        $this->db->where($where);
    }
        
    if($masterID) {
        $this->db->where('udt_AUM_Freight.AuctionID', $masterID);
    }
        
    if($vesselAutocomplete) {
        $this->db->where('udt_AU_Vessel.VesselName', $vesselAutocomplete);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate >=', date('Y-m-d', strtotime($dateFrom)));
    }
    if($dateTo) {
        $this->db->where('udt_AU_Auctions.AuctionReleaseDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }
    $this->db->order_by('udt_AU_Auctions.AuctionReleaseDate', 'DESC');
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getDemmurageByAuctionID($AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('Demurrage >', 0);
    $this->db->order_by('Demurrage', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getAcceptionReason()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('TentativeStatus', '1');
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getAcceptationData()
{
    $AuctionId=$this->input->post('AuctionId');
    $this->db->select('ResponseID,acceptance_resion,confirm1,UserNameTentative,UserDateTentative');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionId', $AuctionId);
    $this->db->where('TentativeStatus', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function removeTentetive()
{
    $ResponseID=$this->input->post('ResponseID');
    $data=array('TentativeStatus'=>0,'acceptance_resion'=>'','confirm1'=>'');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AUM_Freight', $data);
}
    
public function getAcceptationViewData()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('acceptance_resion');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row()->acceptance_resion;
}
    
public function getCargoResponseByResponseID($ResponseID,$LineNum)
{
    $this->db->select('ExpectedLpDelayDay,ExpectedLpDelayHour');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('ResponseCargoID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getAcceptanceReason()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('UserNameTentative,UserDateTentative,acceptance_resion,confirm1');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
    
    
     
public function getResponseCargoData()
{
    $auctionID=$this->input->post('auctionID');
    $ResponseID=$this->input->post('ResponseID');
        
    $this->db->where('udt_AU_ResponseCargoDisports.AuctionID', $auctionID);
    $this->db->where('udt_AU_ResponseCargoDisports.ConfirmFlg', '0');
    $this->db->delete('udt_AU_ResponseCargoDisports');
        
    $this->db->select('udt_AU_ResponseCargo.*, cm.Code as CargoCode, lp.PortName as LpPortName, um.FirstName,um.LastName');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_UserMaster as um', 'um.ID=udt_AU_ResponseCargo.RecordAddBy', 'left');
    $this->db->join('udt_CargoMaster as cm', 'cm.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->where('udt_AU_ResponseCargo.AuctionID', $auctionID);
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_ResponseCargo.LineNum', 'ASC');
    $this->db->order_by('udt_AU_ResponseCargo.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_response_cargo_html_details()
{
    if($this->input->post()) {
        $ResponseCargoID=$this->input->post('ResponseCargoID');
    }
    if($this->input->get()) {
        $ResponseCargoID=$this->input->get('ResponseCargoID');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AU_ResponseCargo.*, udt_CargoMaster.Code, udt_CargoMaster.Description, lp.PortName as lpPortName, ldt1.code as ldtCode, lft.Code as ftCode, lft.Description as ftDescription, cnr.Code as cnrCode');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_ResponseCargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_ResponseCargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_ResponseCargo.LpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargo.ResponseCargoID', $ResponseCargoID);
    $query=$this->db->get();
    return $query->row();
}
    
public function get_response_bac_details()
{
    if($this->input->post()) {
        $ResponseCargoID=$this->input->post('ResponseCargoID');
    }
    if($this->input->get()) {
        $ResponseCargoID=$this->input->get('ResponseCargoID');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('udt_AU_BACResponse_H.ResponseCargoID', $ResponseCargoID);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_response_bac_alldetails($ResponseCargoID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('udt_AU_BACResponse_H.ResponseCargoID', $ResponseCargoID);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_response_allBAC_html_details()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
        $AuctionId=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
        $AuctionId=$this->input->get('AuctionId');
    }
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('udt_AU_BACResponse_H.ResponseID', $ResponseID);
    $this->db->order_by('ResponseCargoID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseCargoDataById()
{ 
    $id=$this->input->post('id');
    $this->db->select('udt_AU_ResponseCargo.*,udt_CargoMaster.Code as cmcode, udt_CargoMaster.Description as cmDescription,udt_PortMaster.Code as pmCode,udt_PortMaster.PortName as pmDescription,udt_CP_LoadingDischargeTermsMaster.code as ldtCode,udt_CP_LoadingDischargeTermsMaster.Description as ldtDescription');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_AU_ResponseCargo.SelectFrom=udt_CargoMaster.ID', 'left');
    $this->db->join('udt_PortMaster', 'udt_AU_ResponseCargo.LoadPort=udt_PortMaster.ID', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_AU_ResponseCargo.LoadingTerms=udt_CP_LoadingDischargeTermsMaster.ID', 'left');
    $this->db->where('ResponseCargoID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getResponseCargoBACById()
{ 
    $UserID=$this->input->post('UserID');
        
    $this->db->where('ConfirmFlg', '2');
    $this->db->where('UserID', $UserID);
    $this->db->update('udt_AU_BACResponse_H', array('ConfirmFlg'=>'0'));
    
    $id=$this->input->post('id');
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseCargoID', $id);
    $this->db->where('ConfirmFlg', '1');
    $query=$this->db->get();
    return $query->result();
         
}
        
public function getResponseCargoLatestVersion()
{
    $linenum=$this->input->post('linenum');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_ResponseCargo.*,udt_CargoMaster.Code as cmcode, udt_CargoMaster.Description as cmDescription,udt_PortMaster.Code as pmCode,udt_PortMaster.PortName as pmDescription,udt_CP_LoadingDischargeTermsMaster.code as ldtCode,udt_CP_LoadingDischargeTermsMaster.Description as ldtDescription');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_AU_ResponseCargo.SelectFrom=udt_CargoMaster.ID', 'left');
    $this->db->join('udt_PortMaster', 'udt_AU_ResponseCargo.LoadPort=udt_PortMaster.ID', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_AU_ResponseCargo.LoadingTerms=udt_CP_LoadingDischargeTermsMaster.ID', 'left');
    $this->db->where('udt_AU_ResponseCargo.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->order_by('ResponseCargoID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}    
    
public function AddNewResponseVersion()
{
    extract($this->input->post());
        
    $ExpectedLpDelayDay1=0;
    $ExpectedLpDelayHour1=0;
        
    if($ExpectedLpDelayDay) {
        $ExpectedLpDelayDay1=$ExpectedLpDelayDay;
    }
    if($ExpectedLpDelayHour) {
        $ExpectedLpDelayHour1=$ExpectedLpDelayHour;
    } 
        
    if(!$CargoQtyMT) {
        $CargoQtyMT=0;
    }
    if(!$ToleranceLimit) {
        $ToleranceLimit=0;
    }
    if(!$UpperLimit) {
        $UpperLimit=0;
    }
    if(!$LowerLimit) {
        $LowerLimit=0;
    }
    if(!$MaxCargoMT) {
        $MaxCargoMT=0;
    }
    if(!$MinCargoMT) {
        $MinCargoMT=0;
    }
    if(!$LoadingRateMT) {
        $LoadingRateMT=0;
    }
    if(!$LpMaxTime) {
        $LpMaxTime=0;
    }
        
    if($InvFlg==1 && $ConfirmationFlg==1) {
        $data_freight=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'',
        'conf2'=>'',
        'conf3'=>'',
        'conf4'=>'',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else if($InvFlg==1 && $ConfirmationFlg==0) {
        $data_freight=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'on',
        'conf2'=>'on',
        'conf3'=>'on',
        'conf4'=>'on',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else {
        $data_freight=array(
        'ResponseStatus'=>'Inprogress',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    }
                    
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AUM_Freight', $data_freight);
                    
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->update('udt_AU_FreightResponseAssessment', $data_freight);
        
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('ResponseCargoID', 'DESC');
    $cargoquery=$this->db->get();
    $CargoResponse=$cargoquery->row()->CargoVersion;
        
    $Version=explode(' ', $CargoResponse);
    $nextVersion=$Version[1]+0.01;
    $newVersion='Version '.$nextVersion;
        
    $data=array(
                'CargoVersion'=>$newVersion,
                'ResponseID'=>$ResponseID,
                'CoCode'=>C_COCODE,
                'AuctionID'=>$AuctionID,
                'LineNum'=>$LineNum,
                'ActiveFlag'=>'1',
                'SelectFrom'=>$SelectFrom,
                'CargoQtyMT'=>$CargoQtyMT,
                'CargoLoadedBasis'=>$CargoLoadedBasis,
                'CargoLimitBasis'=>$CargoLimitBasis,
                'ToleranceLimit'=>$ToleranceLimit,
                'UpperLimit'=>$UpperLimit,
                'LowerLimit'=>$LowerLimit,
                'LoadPort'=>$LoadPort,
                'LpLaycanStartDate'=>date('Y-m-d H:i:s', strtotime($LpLaycanStartDate)),
                'LpLaycanEndDate'=>date('Y-m-d H:i:s', strtotime($LpLaycanEndDate)),
                'LpPreferDate'=>date('Y-m-d H:i:s', strtotime($LpPreferDate)),
                'LoadingTerms'=>$LoadingTerms,
                'LoadingRateMT'=>$LoadingRateMT,
                'LoadingRateUOM'=>$LoadingRateUOM,
                'LpLaytimeType'=>$LpLaytimeType,
                'LpCalculationBasedOn'=>$LpCalculationBasedOn,
                'LpTurnTime'=>$LpTurnTime,
                'LpPriorUseTerms'=>$LpPriorUseTerms,
                'MaxCargoMT'=>$MaxCargoMT,
                'MinCargoMT'=>$MinCargoMT,
                'LpMaxTime'=>$LpMaxTime,
                'LpLaytimeBasedOn'=>$LpLaytimeBasedOn,
                'LpCharterType'=>$LpCharterType,
                'LpNorTendering'=>$LpNorTendering,
                'CargoInternalComments'=>$CargoInternalComments,
                'CargoDisplayComments'=>$CargoDisplayComments,
                'ExpectedLpDelayDay'=>$ExpectedLpDelayDay1,
                'ExpectedLpDelayHour'=>$ExpectedLpDelayHour1,
                'BACFlag'=>$BACFlag,
                'LpStevedoringTerms'=>$LpStevedoringTerms,
                'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,
                'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,
                'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,
                'OfficeHoursFlg'=>$OfficeHoursFlg,
                'LaytimeCommencementFlg'=>$LayTimeCommence,
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
            
    $ret=$this->db->insert('udt_AU_ResponseCargo', $data);

    $data_ass=array(
                'SelectFrom'=>$SelectFrom,
                'CargoQtyMT'=>$CargoQtyMT,
                'CargoLoadedBasis'=>$CargoLoadedBasis,
                'CargoLimitBasis'=>$CargoLimitBasis,
                'ToleranceLimit'=>$ToleranceLimit,
                'UpperLimit'=>$UpperLimit,
                'LowerLimit'=>$LowerLimit,
                'MaxCargoMT'=>$MaxCargoMT,
                'MinCargoMT'=>$MinCargoMT,
                'LoadPort'=>$LoadPort,
                'LpLaycanStartDate'=>date('Y-m-d H:i:s', strtotime($LpLaycanStartDate)),
                'LpLaycanEndDate'=>date('Y-m-d H:i:s', strtotime($LpLaycanEndDate)),
                'LpPreferDate'=>date('Y-m-d H:i:s', strtotime($LpPreferDate)),
                'LoadingTerms'=>$LoadingTerms,
                'LoadingRateMT'=>$LoadingRateMT,
                'LoadingRateUOM'=>$LoadingRateUOM,
                'LpMaxTime'=>$LpMaxTime,
                'LpLaytimeType'=>$LpLaytimeType,
                'LpCalculationBasedOn'=>$LpCalculationBasedOn,
                'LpTurnTime'=>$LpTurnTime,
                'LpPriorUseTerms'=>$LpPriorUseTerms,
                'LpLaytimeBasedOn'=>$LpLaytimeBasedOn,
                'LpCharterType'=>$LpCharterType,
                'LpNorTendering'=>$LpNorTendering,
                'CargoInternalComments'=>$CargoInternalComments,
                'CargoDisplayComments'=>$CargoDisplayComments,
                'ExpectedLpDelayDay'=>$ExpectedLpDelayDay1,
                'ExpectedLpDelayHour'=>$ExpectedLpDelayHour1,
                'LpStevedoringTerms'=>$LpStevedoringTerms,
                'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,
                'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,
                'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,
                'OfficeHoursFlg'=>$OfficeHoursFlg,
                'LaytimeCommencementFlg'=>$LayTimeCommence,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'BACFlag'=>$BACFlag
                );
                
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->update('udt_AU_CargoResponseAssessment', $data_ass);
    
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseCargo');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->order_by('ResponseCargoID', 'DESC');
        $cargonewquery=$this->db->get();
        $NewResponseCargoID=$cargonewquery->row()->ResponseCargoID;
            
        if($ExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($ExceptedPeriodEvent); $i++){
                $excepted_data=array(
                'AuctionID'=>$AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$ResponseID,
                'EventID'=>$ExceptedPeriodEvent[$i],
                'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountOnDemurrage[$i],
                'LaytimeCountsFlg'=>$LaytimeCountUsedFlg[$i],
                'TimeCountingFlg'=>$TimeCounting[$i],
                'ExceptedPeriodComment'=>$ExceptedPeriodComment[$i],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseExceptedPeriods', $excepted_data);
            }
        }
            
        if($NORTenderingPreConditionFlg==1) {
            for($j=0; $j<count($NewSelectTenderingFlg); $j++){
                  $NORTenderingPreConditionID=0;
                  $NewNORTenderingPreCondition='';
                if($NewSelectTenderingFlg[$j]==1) {
                    $NewNORTenderingPreCondition=$TenderingNameOfCondition[$j];
                } else if($NewSelectTenderingFlg[$j]==2) {
                    $NORTenderingPreConditionID=$TenderingNameOfCondition[$j];
                }
                    $tendering_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseID'=>$ResponseID,
                        'CreateNewOrSelectListFlg'=>$NewSelectTenderingFlg[$j],
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$TenderingActiveFlg[$j],
                        'TenderingPreConditionComment'=>$NORTenderingPreConditionComment[$j],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseNORTenderingPreConditions', $tendering_data);
            }
        }
            
        if($NORAcceptancePreConditionFlg==1) {
            for($k=0; $k<count($NewSelectAcceptanceFlg); $k++){
                $NORAcceptancePreConditionID=0;
                $NewNORAcceptancePreCondition='';
                if($NewSelectAcceptanceFlg[$k]==1) {
                    $NewNORAcceptancePreCondition=$AcceptanceNameOfCondition[$k];
                } else if($NewSelectAcceptanceFlg[$k]==2) {
                     $NORAcceptancePreConditionID=$AcceptanceNameOfCondition[$k];
                }
                    $acceptance_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseID'=>$ResponseID,
                        'CreateNewOrSelectListFlg'=>$NewSelectAcceptanceFlg[$k],
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$AcceptanceActiveFlg[$k],
                        'AcceptancePreConditionComment'=>$NORAcceptancePreConditionComment[$k],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseNORAcceptancePreConditions', $acceptance_data);
            }
        }
            
        if($OfficeHoursFlg==1) {
            for($l=0; $l<count($DayFrom); $l++){
                $office_data=array(
                'AuctionID'=>$AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$ResponseID,
                'DateFrom'=>$DayFrom[$l],
                'DateTo'=>$DayTo[$l],
                'TimeFrom'=>$TimeFrom[$l],
                'TimeTo'=>$TimeTo[$l],
                'IsLastEntry'=>$IsLastEntry[$l],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseOfficeHours', $office_data);
            }
        }
            
        if($LayTimeCommence==1) {
            for($m=0; $m<count($LayTiimeDayFrom); $m++){
                $commence_data=array(
                'AuctionID'=>$AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$ResponseID,
                'DayFrom'=>$LayTiimeDayFrom[$m],
                'DayTo'=>$LayTiimeDayTo[$m],
                'TimeFrom'=>$LaytimeTimeFrom[$m],
                'TimeTo'=>$LaytimeTimeTo[$m],
                'TurnTime'=>$TurnTimeApplies[$m],
                'TurnTimeExpire'=>$TurnTimeExpires[$m],
                'LaytimeCommenceAt'=>$LaytimeCommencesAt[$m],
                'LaytimeCommenceAtHour'=>$LaytimeCommencesAtHours[$m],
                'SelectDay'=>$SelectDay[$m],
                'TimeCountsIfOnDemurrage'=>$TimeCountsIfOnDemurrage[$m],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseLaytimeCommencement', $commence_data);
            }
        }
            
            $this->db->select('udt_AU_ResponseCargoDisports_H.*');
            $this->db->from('udt_AU_ResponseCargoDisports_H');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('ConfirmFlg !=  0');
            $this->db->order_by('DisportNo', 'asc');
            $this->db->order_by('RCD_ID_H', 'desc');
            $qry=$this->db->get();
            $disResult=$qry->result();
            
            $DisportNo='';
            
        foreach($disResult as $dis){
            if($DisportNo==$dis->DisportNo || $dis->RowStatus==3) {
                $DisportNo=$dis->DisportNo;
                continue;
            } else {
                $DisportNo=$dis->DisportNo;
            }
                
            $dis_data=array(
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'AuctionID'=>$dis->AuctionID,
                        'ResponseID'=>$dis->ResponseID,
                        'DisportNo'=>$dis->DisportNo,
                        'DisPort'=>$dis->DisPort,
                        'DpArrivalStartDate'=>$dis->DpArrivalStartDate,
                        'DpArrivalEndDate'=>$dis->DpArrivalEndDate,
                        'DpPreferDate'=>$dis->DpPreferDate,
                        'DischargingTerms'=>$dis->DischargingTerms,
                        'DischargingRateMT'=>$dis->DischargingRateMT,
                        'DischargingRateUOM'=>$dis->DischargingRateUOM,
                        'DpMaxTime'=>$dis->DpMaxTime,
                        'DpLaytimeType'=>$dis->DpLaytimeType,
                        'DpCalculationBasedOn'=>$dis->DpCalculationBasedOn,
                        'DpTurnTime'=>$dis->DpTurnTime,
                        'DpPriorUseTerms'=>$dis->DpPriorUseTerms,
                        'DpLaytimeBasedOn'=>$dis->DpLaytimeBasedOn,
                        'DpCharterType'=>$dis->DpCharterType,
                        'DpNorTendering'=>$dis->DpNorTendering,
                        'DpStevedoringTerms'=>$dis->DpStevedoringTerms,
                        'ExpectedDpDelayDay'=>$dis->ExpectedDpDelayDay,
                        'ExpectedDpDelayHour'=>$dis->ExpectedDpDelayHour,
                        'DpExceptedPeriodFlg'=>$dis->DpExceptedPeriodFlg,
                        'DpNORTenderingPreConditionFlg'=>$dis->DpNORTenderingPreConditionFlg,
                        'DpNORAcceptancePreConditionFlg'=>$dis->DpNORAcceptancePreConditionFlg,
                        'DpOfficeHoursFlg'=>$dis->DpOfficeHoursFlg,
                        'DpLaytimeCommencementFlg'=>$dis->DpLaytimeCommencementFlg,
                        'ConfirmFlg'=>1,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $ret1=$this->db->insert('udt_AU_ResponseCargoDisports', $dis_data);
            if($ret1) {
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $NewResponseCargoID);
                $this->db->order_by('RCD_ID', 'desc');
                $qry1=$this->db->get();
                $newDisRow1=$qry1->row();
                    
                $New_RCD_ID=$newDisRow1->RCD_ID;
                    
                $dis_data_h=array(
                'RCD_ID'=>$New_RCD_ID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'AuctionID'=>$dis->AuctionID,
                'ResponseID'=>$dis->ResponseID,
                'DisportNo'=>$dis->DisportNo,
                'DisPort'=>$dis->DisPort,
                'DpArrivalStartDate'=>$dis->DpArrivalStartDate,
                'DpArrivalEndDate'=>$dis->DpArrivalEndDate,
                'DpPreferDate'=>$dis->DpPreferDate,
                'DischargingTerms'=>$dis->DischargingTerms,
                'DischargingRateMT'=>$dis->DischargingRateMT,
                'DischargingRateUOM'=>$dis->DischargingRateUOM,
                'DpMaxTime'=>$dis->DpMaxTime,
                'DpLaytimeType'=>$dis->DpLaytimeType,
                'DpCalculationBasedOn'=>$dis->DpCalculationBasedOn,
                'DpTurnTime'=>$dis->DpTurnTime,
                'DpPriorUseTerms'=>$dis->DpPriorUseTerms,
                'DpLaytimeBasedOn'=>$dis->DpLaytimeBasedOn,
                'DpCharterType'=>$dis->DpCharterType,
                'DpNorTendering'=>$dis->DpNorTendering,
                'DpStevedoringTerms'=>$dis->DpStevedoringTerms,
                'ExpectedDpDelayDay'=>$dis->ExpectedDpDelayDay,
                'ExpectedDpDelayHour'=>$dis->ExpectedDpDelayHour,
                'DpExceptedPeriodFlg'=>$dis->DpExceptedPeriodFlg,
                'DpNORTenderingPreConditionFlg'=>$dis->DpNORTenderingPreConditionFlg,
                'DpNORAcceptancePreConditionFlg'=>$dis->DpNORAcceptancePreConditionFlg,
                'DpOfficeHoursFlg'=>$dis->DpOfficeHoursFlg,
                'DpLaytimeCommencementFlg'=>$dis->DpLaytimeCommencementFlg,
                'RowStatus'=>1,
                'ConfirmFlg'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseCargoDisports_H', $dis_data_h);
                    
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports_H');
                $this->db->where('ResponseCargoID', $NewResponseCargoID);
                $this->db->order_by('RCD_ID_H', 'desc');
                $qry12=$this->db->get();
                $newDisRow12=$qry12->row();
                    
                $New_RCD_ID_H=$newDisRow12->RCD_ID_H;
                    
                    
                if($dis->DpExceptedPeriodFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseDpExceptedPeriods_H');
                    $this->db->where('ResponseDisportID_H', $dis->RCD_ID_H);
                    $this->db->order_by('EPID', 'asc');
                    $qry_period=$this->db->get();
                    $period_result=$qry_period->result();
                    foreach($period_result as $pr){
                         $period_data=array(
                          'AuctionID'=>$pr->AuctionID,
                          'ResponseID'=>$pr->ResponseID,
                          'ResponseCargoID'=>$NewResponseCargoID,
                          'ResponseDisportID'=>$New_RCD_ID,
                          'EventID'=>$pr->EventID,
                          'LaytimeCountsOnDemurrageFlg'=>$pr->LaytimeCountsOnDemurrageFlg,
                          'LaytimeCountsFlg'=>$pr->LaytimeCountsFlg,
                          'TimeCountingFlg'=>$pr->TimeCountingFlg,
                          'ExceptedPeriodComment'=>$pr->ExceptedPeriodComment,
                          'UserID'=>$UserID,
                          'CreatedDate'=>date('Y-m-d H:i:s')
                         );
                         $this->db->insert('udt_AU_ResponseDpExceptedPeriods', $period_data);
                            
                         $period_data_h=array(
                          'AuctionID'=>$pr->AuctionID,
                          'ResponseID'=>$pr->ResponseID,
                          'ResponseCargoID'=>$NewResponseCargoID,
                          'ResponseDisportID_H'=>$New_RCD_ID_H,
                          'EventID'=>$pr->EventID,
                          'LaytimeCountsOnDemurrageFlg'=>$pr->LaytimeCountsOnDemurrageFlg,
                          'LaytimeCountsFlg'=>$pr->LaytimeCountsFlg,
                          'TimeCountingFlg'=>$pr->TimeCountingFlg,
                          'ExceptedPeriodComment'=>$pr->ExceptedPeriodComment,
                          'UserID'=>$UserID,
                          'CreatedDate'=>date('Y-m-d H:i:s')
                         );
                         $this->db->insert('udt_AU_ResponseDpExceptedPeriods_H', $period_data_h);
                    }
                }
                    
                if($dis->DpNORTenderingPreConditionFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions_H');
                    $this->db->where('ResponseDisportID_H', $dis->RCD_ID_H);
                    $this->db->order_by('TPCID', 'asc');
                    $qry_tendering=$this->db->get();
                    $tendering_result=$qry_tendering->result();
                        
                    foreach($tendering_result as $tr){
                        $tendering_data=array(
                        'AuctionID'=>$tr->AuctionID,
                        'ResponseID'=>$tr->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID'=>$New_RCD_ID,
                        'CreateNewOrSelectListFlg'=>$tr->CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$tr->NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$tr->NewNORTenderingPreCondition,
                        'StatusFlag'=>$tr->StatusFlag,
                        'TenderingPreConditionComment'=>$tr->TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpNORTenderingPreConditions', $tendering_data);
                            
                        $tendering_data_h=array(
                        'AuctionID'=>$tr->AuctionID,
                        'ResponseID'=>$tr->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID_H'=>$New_RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$tr->CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$tr->NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$tr->NewNORTenderingPreCondition,
                        'StatusFlag'=>$tr->StatusFlag,
                        'TenderingPreConditionComment'=>$tr->TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpNORTenderingPreConditions_H', $tendering_data_h);
                    }
                }
                    
                if($dis->DpNORAcceptancePreConditionFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions_H');
                    $this->db->where('ResponseDisportID_H', $dis->RCD_ID_H);
                    $this->db->order_by('APCID', 'asc');
                    $qry_acceptance=$this->db->get();
                    $acceptance_result=$qry_acceptance->result();
                        
                    foreach($acceptance_result as $ar){
                        $acceptance_data=array(
                        'AuctionID'=>$ar->AuctionID,
                        'ResponseID'=>$ar->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID'=>$New_RCD_ID,
                        'CreateNewOrSelectListFlg'=>$ar->CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$ar->NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$ar->NewNORAcceptancePreCondition,
                        'StatusFlag'=>$ar->StatusFlag,
                        'AcceptancePreConditionComment'=>$ar->AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpNORAcceptancePreConditions', $acceptance_data);
                        $acceptance_data_h=array(
                        'AuctionID'=>$ar->AuctionID,
                        'ResponseID'=>$ar->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID_H'=>$New_RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$ar->CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$ar->NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$ar->NewNORAcceptancePreCondition,
                        'StatusFlag'=>$ar->StatusFlag,
                        'AcceptancePreConditionComment'=>$ar->AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpNORAcceptancePreConditions_H', $acceptance_data_h);
                    }
                }
                    
                if($dis->DpOfficeHoursFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseDpOfficeHours_H');
                    $this->db->where('ResponseDisportID_H', $dis->RCD_ID_H);
                    $this->db->order_by('OHID', 'asc');
                    $qry_office=$this->db->get();
                    $office_result=$qry_office->result();
                        
                    foreach($office_result as $or){
                        $office_data=array(
                        'AuctionID'=>$or->AuctionID,
                        'ResponseID'=>$or->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID'=>$New_RCD_ID,
                        'DateFrom'=>$or->DateFrom,
                        'DateTo'=>$or->DateTo,
                        'TimeFrom'=>$or->TimeFrom,
                        'TimeTo'=>$or->TimeTo,
                        'IsLastEntry'=>$or->IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpOfficeHours', $office_data);
                        $office_data_h=array(
                        'AuctionID'=>$or->AuctionID,
                        'ResponseID'=>$or->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID_H'=>$New_RCD_ID_H,
                        'DateFrom'=>$or->DateFrom,
                        'DateTo'=>$or->DateTo,
                        'TimeFrom'=>$or->TimeFrom,
                        'TimeTo'=>$or->TimeTo,
                        'IsLastEntry'=>$or->IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpOfficeHours_H', $office_data_h);
                    }
                }
                    
                if($dis->DpLaytimeCommencementFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseDpLaytimeCommencement_H');
                    $this->db->where('ResponseDisportID_H', $dis->RCD_ID_H);
                    $this->db->order_by('LCID', 'asc');
                    $qry_laytime=$this->db->get();
                    $laytime_result=$qry_laytime->result();
                        
                    foreach($laytime_result as $lr){
                        $laytime_data=array(
                        'AuctionID'=>$lr->AuctionID,
                        'ResponseID'=>$lr->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID'=>$New_RCD_ID,
                        'DayFrom'=>$lr->DayFrom,
                        'DayTo'=>$lr->DayTo,
                        'TimeFrom'=>$lr->TimeFrom,
                        'TimeTo'=>$lr->TimeTo,
                        'TurnTime'=>$lr->TurnTime,
                        'TurnTimeExpire'=>$lr->TurnTimeExpire,
                        'LaytimeCommenceAt'=>$lr->LaytimeCommenceAt,
                        'LaytimeCommenceAtHour'=>$lr->LaytimeCommenceAtHour,
                        'SelectDay'=>$lr->SelectDay,
                        'TimeCountsIfOnDemurrage'=>$lr->TimeCountsIfOnDemurrage,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpLaytimeCommencement', $laytime_data);
                            
                        $laytime_data_h=array(
                        'AuctionID'=>$lr->AuctionID,
                        'ResponseID'=>$lr->ResponseID,
                        'ResponseCargoID'=>$NewResponseCargoID,
                        'ResponseDisportID_H'=>$New_RCD_ID_H,
                        'DayFrom'=>$lr->DayFrom,
                        'DayTo'=>$lr->DayTo,
                        'TimeFrom'=>$lr->TimeFrom,
                        'TimeTo'=>$lr->TimeTo,
                        'TurnTime'=>$lr->TurnTime,
                        'TurnTimeExpire'=>$lr->TurnTimeExpire,
                        'LaytimeCommenceAt'=>$lr->LaytimeCommenceAt,
                        'LaytimeCommenceAtHour'=>$lr->LaytimeCommenceAtHour,
                        'SelectDay'=>$lr->SelectDay,
                        'TimeCountsIfOnDemurrage'=>$lr->TimeCountsIfOnDemurrage,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_ResponseDpLaytimeCommencement_H', $laytime_data_h);
                    }
                }
                    
            }
        }
            
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('UserID', $UserID);
            $this->db->where('ConfirmFlg', 2);
            $this->db->update('udt_AU_ResponseCargoDisports_H', array('ConfirmFlg'=>0));
            
            
            $this->db->select('*');
            $this->db->from('udt_AU_BACResponse_H');
            $this->db->where('ResponseID', $ResponseID);
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('TransactionType', 'Brokerage');
            $this->db->where('ConfirmFlg !=  0');
            $this->db->order_by('SeqNo', 'desc');
            $this->db->order_by('BACResponse_HID', 'desc');
            $query=$this->db->get();
            $bac_row=$query->row();
            
        if($bac_row) {
            if($bac_row->RowStatus !=3) {
                $bacdata=array(
                'SeqNo'=>$bac_row->SeqNo,
                'AuctionID'=>$bac_row->AuctionID,
                'ResponseID'=>$bac_row->ResponseID,
                'TransactionType'=>$bac_row->TransactionType,
                'PayingEntityType'=>$bac_row->PayingEntityType,
                'PayingEntityName'=>$bac_row->PayingEntityName,
                'ReceivingEntityType'=>$bac_row->ReceivingEntityType,
                'ReceivingEntityName'=>$bac_row->ReceivingEntityName,
                'BrokerName'=>$bac_row->BrokerName,
                'PayableAs'=>$bac_row->PayableAs,
                'PercentageOnFreight'=>$bac_row->PercentageOnFreight,
                'PercentageOnDeadFreight'=>$bac_row->PercentageOnDeadFreight,
                'PercentageOnDemmurage'=>$bac_row->PercentageOnDemmurage,
                'PercentageOnOverage'=>$bac_row->PercentageOnOverage,
                'LumpsumPayable'=>$bac_row->LumpsumPayable,
                'RatePerTonnePayable'=>$bac_row->RatePerTonnePayable,
                'BACComment'=>$bac_row->BACComment,
                'ResponseCargoID'=>$NewResponseCargoID,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_BACResponse', $bacdata);
                    
            }
        }
            
            $this->db->select('*');
            $this->db->from('udt_AU_BACResponse_H');
            $this->db->where('ResponseID', $ResponseID);
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('TransactionType', 'Commision');
            $this->db->where('ConfirmFlg !=  0');
            $this->db->order_by('SeqNo', 'desc');
            $this->db->order_by('BACResponse_HID', 'desc');
            $query1=$this->db->get();
            $bac_row1=$query1->row();
            
        if($bac_row1) {
            if($bac_row1->RowStatus !=3) {
                $bacdata=array(
                'SeqNo'=>$bac_row1->SeqNo,
                'AuctionID'=>$bac_row1->AuctionID,
                'ResponseID'=>$bac_row1->ResponseID,
                'TransactionType'=>$bac_row1->TransactionType,
                'PayingEntityType'=>$bac_row1->PayingEntityType,
                'PayingEntityName'=>$bac_row1->PayingEntityName,
                'ReceivingEntityType'=>$bac_row1->ReceivingEntityType,
                'ReceivingEntityName'=>$bac_row1->ReceivingEntityName,
                'BrokerName'=>$bac_row1->BrokerName,
                'PayableAs'=>$bac_row1->PayableAs,
                'PercentageOnFreight'=>$bac_row1->PercentageOnFreight,
                'PercentageOnDeadFreight'=>$bac_row1->PercentageOnDeadFreight,
                'PercentageOnDemmurage'=>$bac_row1->PercentageOnDemmurage,
                'PercentageOnOverage'=>$bac_row1->PercentageOnOverage,
                'LumpsumPayable'=>$bac_row1->LumpsumPayable,
                'RatePerTonnePayable'=>$bac_row1->RatePerTonnePayable,
                'BACComment'=>$bac_row1->BACComment,
                'ResponseCargoID'=>$NewResponseCargoID,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_BACResponse', $bacdata);
            }
        }
            
            $this->db->select('*');
            $this->db->from('udt_AU_BACResponse_H');
            $this->db->where('ResponseID', $ResponseID);
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('TransactionType', 'Others');
            $this->db->where('ConfirmFlg !=  0');
            $this->db->order_by('SeqNo', 'asc');
            $this->db->order_by('BACResponse_HID', 'desc');
            $query2=$this->db->get();
            $bac_rsult=$query2->result();
            
            $seq_no='';
        foreach($bac_rsult as $bac){
            if($seq_no==$bac->SeqNo || $bac->ConfirmFlg==0) {
                continue;
            } else {
                $seq_no=$bac->SeqNo;
            }
            if($bac->RowStatus != 3) {
                $this->db->select('*');
                $this->db->from('udt_AU_BACResponse_H');
                $this->db->where('BACResponse_HID', $bac->BACResponse_HID);
                $query11=$this->db->get();
                $bacRow1=$query11->row();
                $bacdata=array(
                'SeqNo'=>$bacRow1->SeqNo,
                'AuctionID'=>$bacRow1->AuctionID,
                'ResponseID'=>$bacRow1->ResponseID,
                'TransactionType'=>$bacRow1->TransactionType,
                'PayingEntityType'=>$bacRow1->PayingEntityType,
                'PayingEntityName'=>$bacRow1->PayingEntityName,
                'ReceivingEntityType'=>$bacRow1->ReceivingEntityType,
                'ReceivingEntityName'=>$bacRow1->ReceivingEntityName,
                'BrokerName'=>$bacRow1->BrokerName,
                'PayableAs'=>$bacRow1->PayableAs,
                'PercentageOnFreight'=>$bacRow1->PercentageOnFreight,
                'PercentageOnDeadFreight'=>$bacRow1->PercentageOnDeadFreight,
                'PercentageOnDemmurage'=>$bacRow1->PercentageOnDemmurage,
                'PercentageOnOverage'=>$bacRow1->PercentageOnOverage,
                'LumpsumPayable'=>$bacRow1->LumpsumPayable,
                'RatePerTonnePayable'=>$bacRow1->RatePerTonnePayable,
                'BACComment'=>$bacRow1->BACComment,
                'ResponseCargoID'=>$NewResponseCargoID,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_BACResponse', $bacdata);
            }
        }
                    
            $query1 = $this->db->query(
                "insert into cops_admin.udt_AU_BACResponse_H (BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, ConfirmFlg, SeqNo, RowStatus, UserID,UserDate )
			select BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, 1, SeqNo, 1, '".$UserID."','".date('Y-m-d H:i:s')."'
			from cops_admin.udt_AU_BACResponse where ResponseID='".$ResponseID."' and ResponseCargoID='".$NewResponseCargoID."' order by BACResponse_ID asc"
            );
            
            
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('UserID', $UserID);
        $this->db->where('ConfirmFlg', 2);
        $this->db->update('udt_AU_BACResponse_H', array('ConfirmFlg'=>0));
        
        return $ret;
    } else{
        return 0;
    }
        
}
    
public function get_response_allcargo_html_details()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
        $AuctionId=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
        $AuctionId=$this->input->get('AuctionId');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AU_ResponseCargo.*, udt_CargoMaster.Code, udt_CargoMaster.Description, lp.PortName as lpPortName, ldt1.code as ldtCode, lft.Code as ftCode, lft.Description as ftDescription, cnr.Code as cnrCode');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_ResponseCargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_ResponseCargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_ResponseCargo.LpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseCargo.AuctionID', $AuctionId);
    $this->db->order_by('udt_AU_ResponseCargo.LineNum', 'ASC');
    $this->db->order_by('udt_AU_ResponseCargo.ResponseCargoID', 'Desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_response_allcargo_html_details1()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
        $AuctionId=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
        $AuctionId=$this->input->get('AuctionId');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AU_ResponseCargo.*, udt_CargoMaster.Code, udt_CargoMaster.Description, lp.PortName as lpPortName, ldt1.code as ldtCode, lft.Code as ftCode, lft.Description as ftDescription, cnr.Code as cnrCode');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_ResponseCargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_ResponseCargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_ResponseCargo.LpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseCargo.AuctionID', $AuctionId);
    $this->db->order_by('udt_AU_ResponseCargo.LineNum', 'ASC');
    $this->db->order_by('udt_AU_ResponseCargo.ResponseCargoID', 'Desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseDisportDetails($ResponseCargoID)
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
        $AuctionId=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
        $AuctionId=$this->input->get('AuctionId');
    }
        
    $this->db->select('udt_AU_ResponseCargoDisports.*, dp.PortName as dpPortName, ldt2.code as ddtCode, dft.Code as dftCode, dft.Description as dftDescription, cnr1.Code as cnrDCode');
    $this->db->from('udt_AU_ResponseCargoDisports');
    $this->db->join('udt_PortMaster as dp', 'dp.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt2', 'ldt2.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as dft', 'dft.ID=udt_AU_ResponseCargoDisports.DpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr1', 'cnr1.ID=udt_AU_ResponseCargoDisports.DpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargoDisports.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseCargoDisports.AuctionID', $AuctionId);
    $this->db->where('udt_AU_ResponseCargoDisports.ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('RCD_ID', 'asc');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getResponseDisportDetailsByResponseCargoID($ResponseCargoID)
{
        
    $this->db->select('udt_AU_ResponseCargoDisports.*, dp.PortName as dpPortName, ldt2.code as ddtCode, dft.Code as dftCode, dft.Description as dftDescription, cnr1.Code as cnrDCode');
    $this->db->from('udt_AU_ResponseCargoDisports');
    $this->db->join('udt_PortMaster as dp', 'dp.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt2', 'ldt2.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as dft', 'dft.ID=udt_AU_ResponseCargoDisports.DpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr1', 'cnr1.ID=udt_AU_ResponseCargoDisports.DpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargoDisports.ConfirmFlg', 1);
    $this->db->where('udt_AU_ResponseCargoDisports.ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('RCD_ID', 'asc');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function get_bac_by_responsecargoID($ResponseCargoID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseCargoLatestOpen()
{
    extract($this->input->post());
    $this->db->select('udt_AU_ResponseCargo.*, udt_CargoMaster.Code, udt_CargoMaster.Description, lp.PortName as lpPortName, ldt1.code as ldtCode, lft.Code as ftCode, lft.Description as ftDescription, cnr.Code as cnrCode, sdt1.Description as sdt1Description ');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_ResponseCargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_ResponseCargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_ResponseCargo.LpNorTendering', 'left');
    $this->db->join('udt_CP_SteveDoringTerms as sdt1', 'sdt1.ID=udt_AU_ResponseCargo.LpStevedoringTerms', 'left');
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseCargo.LineNum', $LineNum);
    $this->db->order_by('udt_AU_ResponseCargo.ResponseCargoID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function updateResponseContentChange($olddata,$newdata)
{
    $html='';
    $section='';
    $totalhtml='';
    if($olddata->SelectFrom != $newdata->SelectFrom) {
        $section='<br><br><B>Cargo</B><br>';
        $html .='<br> Old Cargo : '.$olddata->Code.' <span class="diff">||</span> New Cargo : '.$newdata->Code;
    }
    if($olddata->CargoQtyMT != $newdata->CargoQtyMT) {
        $section='<br><br><B>Cargo</B><br>';
        $html .='<br> Old Cargo Qty to load : '.number_format($olddata->CargoQtyMT).' <span class="diff">||</span> New Cargo Qty to load : '.number_format($newdata->CargoQtyMT);
    }
    if($olddata->CargoLoadedBasis != $newdata->CargoLoadedBasis) {
        $section='<br><br><B>Cargo</B><br>';
        $html .='<br>Old Cargo Loaded Basis : '.$olddata->CargoLoadedBasis.' <span class="diff">||</span> New Cargo Loaded Basis : '.$newdata->CargoLoadedBasis;
    }
    if($olddata->CargoLimitBasis != $newdata->CargoLimitBasis) {
        $section='<br><br><B>Cargo</B><br>';
        if($olddata->CargoLimitBasis=='1') {
            $OldCargoLimitBasis='Max and Min';
        }else if($olddata->CargoLimitBasis=='2') {
            $OldCargoLimitBasis='% Tolerence Limit';
        }
        if($newdata->CargoLimitBasis=='1') {
            $NewCargoLimitBasis='Max and Min';
        }else if($newdata->CargoLimitBasis=='2') {
            $NewCargoLimitBasis='% Tolerence Limit';
        }
            
            $html .='<br>Old Cargo quantity limit basis : '.$OldCargoLimitBasis.' <span class="diff">||</span> New Cargo quantity limit basis : '.$NewCargoLimitBasis;
            
        if($olddata->CargoLimitBasis=='1' && $newdata->CargoLimitBasis=='2') {
                $html .='Old Max Cargo Amount : '.number_format($olddata->MaxCargoMT).' Old Min Cargo Amount : '.number_format($olddata->MinCargoMT).' <span class="diff">||</span> New Tolerence Limit : '.(int) $newdata->ToleranceLimit.' Upper Limit : '.number_format($newdata->UpperLimit).' LowerLimit : '.number_format($newdata->LowerLimit);
        }
            
        if($olddata->CargoLimitBasis=='2' && $newdata->CargoLimitBasis=='1') {
                $html .='<br>Old Tolerence Limit : '.(int)$olddata->ToleranceLimit.' Upper Limit : '.number_format($olddata->UpperLimit).' LowerLimit : '.number_format($olddata->LowerLimit).' <span class="diff">||</span> New Max Cargo Amount : '.number_format($newdata->MaxCargoMT).'New Min Cargo Amount : '.number_format($newdata->MinCargoMT);
        }
            
    }else{
        if($olddata->CargoLimitBasis=='2' && $newdata->CargoLimitBasis=='2') {
            if($olddata->ToleranceLimit != $newdata->ToleranceLimit) {
                 $section='<br><br><B>Cargo</B><br>';
                 $html .='<br>Old Tolerence Limit : '.(int)$olddata->ToleranceLimit.' Upper Limit : '.number_format($olddata->UpperLimit).' LowerLimit : '.number_format($olddata->LowerLimit).' <span class="diff">||</span> New Tolerence Limit : '.(int)$newdata->ToleranceLimit.' Upper Limit : '.number_format($newdata->UpperLimit).' LowerLimit : '.number_format($newdata->LowerLimit);
            }
        }
        if($olddata->CargoLimitBasis=='1' && $newdata->CargoLimitBasis=='1') {
            if($olddata->MaxCargoMT != $newdata->MaxCargoMT) {
                $section='<br><br><B>Cargo</B><br>';
                $html .='<br>Old Max Cargo Limit : '.number_format($olddata->MaxCargoMT).' <span class="diff">||</span> New Max Cargo Limit : '.number_format($newdata->MaxCargoMT);
            }
            if($olddata->MinCargoMT != $newdata->MinCargoMT) {
                $section='<br><br><B>Cargo</B><br>';
                $html .='<br>Old Min Cargo Limit : '.number_format($olddata->MinCargoMT).' <span class="diff">||</span> New Min Cargo Limit : '.number_format($newdata->MinCargoMT);
            }
        }
    }
        
     $totalhtml .=$section;
     $totalhtml .=$html;
     $section='';
     $html='';
        
    if($olddata->LoadPort != $newdata->LoadPort) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old LoadPort : '.$olddata->lpPortName.' <span class="diff">||</span> New LoadPort : '.$newdata->lpPortName;
    }
        
     $oldLayCanStartDate=date('Y-m-d', strtotime($olddata->LpLaycanStartDate));
     $newLayCanStartDate=date('Y-m-d', strtotime($newdata->LpLaycanStartDate));
        
    if($oldLayCanStartDate != $newLayCanStartDate) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Laycan Start Date : '.date('d-m-Y', strtotime($oldLayCanStartDate)).' <span class="diff">||</span> New Laycan Start Date : '.date('d-m-Y', strtotime($newLayCanStartDate));
    }
        
     $oldLaycanEndDate=date('Y-m-d', strtotime($olddata->LpLaycanEndDate));
     $newLaycanEndDate=date('Y-m-d', strtotime($newdata->LpLaycanEndDate));
        
    if($oldLaycanEndDate != $newLaycanEndDate) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Laycan End Date : '.date('d-m-Y', strtotime($oldLaycanEndDate)).' <span class="diff">||</span> New Laycan End Date : '.date('d-m-Y', strtotime($newLaycanEndDate));
    }
        
     $oldLpPreferDate=date('Y-m-d', strtotime($olddata->LpPreferDate));
     $newLpPreferDate=date('Y-m-d', strtotime($newdata->LpPreferDate));
        
    if($oldLpPreferDate != $newLpPreferDate) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Laycan Prefered Date : '.date('d-m-Y', strtotime($oldLpPreferDate)).' <span class="diff">||</span> New Laycan Prefered Date : '.date('d-m-Y', strtotime($newLpPreferDate));
    }
        
    if($olddata->ExpectedLpDelayDay != $newdata->ExpectedLpDelayDay) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Expected Loadport Delay : '.$olddata->ExpectedLpDelayDay.' Days '.$olddata->ExpectedLpDelayHour.' Hours <span class="diff">||</span> New Expected Loadport Delay : '.$newdata->ExpectedLpDelayDay.' Days '.$newdata->ExpectedLpDelayHour.' Hours';
    }else if($olddata->ExpectedLpDelayHour != $newdata->ExpectedLpDelayHour) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Expected Loadport Delay : '.$olddata->ExpectedLpDelayDay.' Days '.$olddata->ExpectedLpDelayHour.' Hours <span class="diff">||</span> New Expected Loadport Delay : '.$newdata->ExpectedLpDelayDay.' Days '.$newdata->ExpectedLpDelayHour.' Hours';
    }
        
    if($olddata->LoadingTerms != $newdata->LoadingTerms) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Loading Term : '.$olddata->ldtCode.' <span class="diff">||</span> New Loading Term : '.$newdata->ldtCode;
    }
        
     $oldLoadingRateMT=(int)$olddata->LoadingRateMT;
     $newLoadingRateMT=(int)$newdata->LoadingRateMT;
    if($oldLoadingRateMT != $newLoadingRateMT) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Loading Rate : '.number_format($oldLoadingRateMT).' <span class="diff">||</span> New Loading Rate : '.number_format($newLoadingRateMT);
    }
        
    if($olddata->LoadingRateUOM != $newdata->LoadingRateUOM) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LoadingRateUOM=='1') {
            $oldLoadingRateUOM='Per Hour';
        }else if($olddata->LoadingRateUOM=='2') {
            $oldLoadingRateUOM='Per Weater Working Day';
        }else if($olddata->LoadingRateUOM=='3') {
            $oldLoadingRateUOM='Max Time Limit';
        }
        if($newdata->LoadingRateUOM=='1') {
            $newLoadingRateUOM='Per Hour';
        }else if($newdata->LoadingRateUOM=='2') {
            $newLoadingRateUOM='Per Weater Working Day';
        }else if($newdata->LoadingRateUOM=='3') {
            $newLoadingRateUOM='Max Time Limit';
        }
        $html .='<br>Old Loading Rate UOM: '.$oldLoadingRateUOM.' <span class="diff">||</span> New Loading Rate UOM: '.$newLoadingRateUOM;
    }
        
    if($olddata->LpLaytimeType != $newdata->LpLaytimeType) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpLaytimeType=='1') {
            $oldLpLaytimeType='Reversible';
        }else if($olddata->LpLaytimeType=='2') {
            $oldLpLaytimeType='Non Reversible';
        }else if($olddata->LpLaytimeType=='3') {
            $oldLpLaytimeType='Average';
        }
        if($newdata->LpLaytimeType=='1') {
            $newLpLaytimeType='Reversible';
        }else if($newdata->LpLaytimeType=='2') {
            $newLpLaytimeType='Non Reversible';
        }else if($newdata->LpLaytimeType=='3') {
            $newLpLaytimeType='Average';
        }
        $html .='<br>Old Loading Laytime type: '.$oldLpLaytimeType.' <span class="diff">||</span> New Loading Laytime type: '.$newLpLaytimeType;
    }
        
    if($olddata->LpCalculationBasedOn != $newdata->LpCalculationBasedOn) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpCalculationBasedOn=='108') {
            $oldLpCalculationBasedOn='Bill of Loading Quantity';
        }else if($olddata->LpCalculationBasedOn=='109') {
            $oldLpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
        if($newdata->LpCalculationBasedOn=='108') {
            $newLpCalculationBasedOn='Bill of Loading Quantity';
        }else if($newdata->LpCalculationBasedOn=='109') {
            $newLpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
        $html .='<br>Old Loading Calculation Based on: '.$oldLpCalculationBasedOn.' <span class="diff">||</span> New Loading Calculation Based on: '.$newLpCalculationBasedOn;
    }
        
    if($olddata->LpTurnTime != $newdata->LpTurnTime) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpTurnTime=='1') {
            $oldLpTurnTime='LT freetime';
        }else if($olddata->LpTurnTime=='2') {
            $oldLpTurnTime='LayTime Free test';
        } else if($olddata->LpTurnTime=='3') {
            $oldLpTurnTime='12HAA';
        } else if($olddata->LpTurnTime=='4') {
            $oldLpTurnTime='24HAA';
        } else if($olddata->LpTurnTime=='5') {
            $oldLpTurnTime='4HAA';
        } else if($olddata->LpTurnTime=='6') {
            $oldLpTurnTime='6HAA';
        } else if($olddata->LpTurnTime=='7') {
            $oldLpTurnTime='8HAA';
        } else if($olddata->LpTurnTime=='8') {
            $oldLpTurnTime='16HAA ';
        } else if($olddata->LpTurnTime=='9') {
            $oldLpTurnTime='20HAA';
        } else if($olddata->LpTurnTime=='10') {
            $oldLpTurnTime='18HAA';
        } 
            
        if($newdata->LpTurnTime=='1') {
            $newLpTurnTime='LT freetime';
        }else if($newdata->LpTurnTime=='2') {
            $newLpTurnTime='LayTime Free test';
        } else if($newdata->LpTurnTime=='3') {
            $newLpTurnTime='12HAA';
        } else if($newdata->LpTurnTime=='4') {
            $newLpTurnTime='24HAA';
        } else if($newdata->LpTurnTime=='5') {
            $newLpTurnTime='4HAA';
        } else if($newdata->LpTurnTime=='6') {
            $newLpTurnTime='6HAA';
        } else if($newdata->LpTurnTime=='7') {
            $newLpTurnTime='8HAA';
        } else if($newdata->LpTurnTime=='8') {
            $newLpTurnTime='16HAA ';
        } else if($newdata->LpTurnTime=='9') {
            $newLpTurnTime='20HAA';
        } else if($newdata->LpTurnTime=='10') {
            $newLpTurnTime='18HAA';
        } 
            
        $html .='<br>Old Loading Turn Time: '.$oldLpTurnTime.' <span class="diff">||</span> New Loading Turn Time: '.$newLpTurnTime;
    }
        
    if($olddata->LpPriorUseTerms != $newdata->LpPriorUseTerms) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpPriorUseTerms=='102') {
            $oldLpPriorUseTerms='IUATUTC';
        }else if($olddata->LpPriorUseTerms=='10') {
            $oldLpPriorUseTerms='IUHTUTC ';
        } 
        if($newdata->LpPriorUseTerms=='102') {
            $newLpPriorUseTerms='IUATUTC';
        }else if($newdata->LpPriorUseTerms=='10') {
            $newLpPriorUseTerms='IUHTUTC ';
        } 
        $html .='<br>Old Loading Prior Use Term: '.$oldLpPriorUseTerms.' <span class="diff">||</span> New Loading Prior Use Term: '.$newLpPriorUseTerms;
    }
    if($olddata->LpLaytimeBasedOn != $newdata->LpLaytimeBasedOn) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpLaytimeBasedOn=='1') {
            $oldLpLaytimeBasedOn='ATS';
        }else if($olddata->LpLaytimeBasedOn=='2') {
            $oldLpLaytimeBasedOn='WTS ';
        } 
        if($newdata->LpLaytimeBasedOn=='1') {
            $newLpLaytimeBasedOn='ATS';
        }else if($newdata->LpLaytimeBasedOn=='2') {
            $newLpLaytimeBasedOn='WTS ';
        } 
        $html .='<br>Old Loading laytime Based on: '.$oldLpLaytimeBasedOn.' <span class="diff">||</span> New Loading laytime Based on: '.$newLpLaytimeBasedOn;
    }
    if($olddata->LpCharterType != $newdata->LpCharterType) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpCharterType=='1') {
            $oldLpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($olddata->LpCharterType=='2') {
            $oldLpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        }else if($olddata->LpCharterType=='3') {
            $oldLpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        }else if($olddata->LpCharterType=='4') {
            $oldLpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
        if($newdata->LpCharterType=='1') {
            $newLpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($newdata->LpCharterType=='2') {
            $newLpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        } else if($newdata->LpCharterType=='3') {
            $newLpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        } else if($newdata->LpCharterType=='3') {
            $newLpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
        $html .='<br>Old Loading Charter Type: '.$oldLpCharterType.' <span class="diff">||</span> New Loading Charter Type: '.$newLpCharterType;
    }
        
    if($olddata->LpStevedoringTerms != $newdata->LpStevedoringTerms) {
        $section='<br><br><B>Loadport</B><br>';
        $html .='<br>Old Loading Stevedoring terms: '.$olddata->sdt1Description.' <span class="diff">||</span> New Loading Stevedoring terms: '.$newdata->sdt1Description;
    }
        
    if($olddata->LpNorTendering != $newdata->LpNorTendering) {
        $section='<br><br><B>Loadport</B><br>';
        if($olddata->LpNorTendering=='1') {
            $oldLpNorTendering='ATDNSHINC';
        }else if($olddata->LpNorTendering=='2') {
            $oldLpNorTendering='ATDNFHINC';
        }else if($olddata->LpNorTendering=='3') {
            $oldLpNorTendering='OFFICE HOURS';
        }else if($olddata->LpNorTendering=='4') {
            $oldLpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
        if($newdata->LpNorTendering=='1') {
            $newLpNorTendering='ATDNSHINC';
        }else if($newdata->LpNorTendering=='2') {
            $newLpNorTendering='ATDNFHINC';
        }else if($newdata->LpNorTendering=='3') {
            $newLpNorTendering='OFFICE HOURS';
        } else if($newdata->LpNorTendering=='4') {
            $newLpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
        $html .='<br>Old Loading NorTendering: '.$oldLpNorTendering.' <span class="diff">||</span> New Loading NorTendering: '.$newLpNorTendering;
    }
        
    if($olddata->ExceptedPeriodFlg != $newdata->ExceptedPeriodFlg) {
        $section='<br><br><B>Loadport</B><br>';
        $oldExceptedPeriodFlg='No';
        if($olddata->ExceptedPeriodFlg==1) {
            $oldExceptedPeriodFlg='Yes';
        }
        $newExceptedPeriodFlg='No';
        if($newdata->ExceptedPeriodFlg==1) {
            $newExceptedPeriodFlg='Yes';
        }
        $html .='<br>Old Excepted periods for events : '.$oldExceptedPeriodFlg.' <span class="diff">||</span> New Excepted periods for events : '.$newExceptedPeriodFlg;
    }
        
    if($olddata->NORTenderingPreConditionFlg != $newdata->NORTenderingPreConditionFlg) {
        $section='<br><br><B>Loadport</B><br>';
        $oldNORTenderingPreConditionFlg='No';
        if($olddata->NORTenderingPreConditionFlg==1) {
            $oldNORTenderingPreConditionFlg='Yes';
        }
        $newNORTenderingPreConditionFlg='No';
        if($newdata->NORTenderingPreConditionFlg==1) {
            $newNORTenderingPreConditionFlg='Yes';
        }
        $html .='<br>Old NOR tendering pre conditions apply: '.$oldNORTenderingPreConditionFlg.' <span class="diff">||</span> New NOR tendering pre conditions apply: '.$newNORTenderingPreConditionFlg;
    }
        
    if($olddata->NORAcceptancePreConditionFlg != $newdata->NORAcceptancePreConditionFlg) {
        $section='<br><br><B>Loadport</B><br>';
        $oldNORAcceptancePreConditionFlg='No';
        if($olddata->NORAcceptancePreConditionFlg==1) {
            $oldNORAcceptancePreConditionFlg='Yes';
        }
        $newNORAcceptancePreConditionFlg='No';
        if($newdata->NORAcceptancePreConditionFlg==1) {
            $newNORAcceptancePreConditionFlg='Yes';
        }
        $html .='<br>Old NOR acceptance pre conditions apply: '.$oldNORAcceptancePreConditionFlg.' <span class="diff">||</span> New NOR acceptance pre conditions apply: '.$newNORAcceptancePreConditionFlg;
    }
        
    if($olddata->OfficeHoursFlg != $newdata->OfficeHoursFlg) {
        $section='<br><br><B>Loadport</B><br>';
        $oldOfficeHoursFlg='No';
        if($olddata->OfficeHoursFlg==1) {
            $oldOfficeHoursFlg='Yes';
        }
        $newOfficeHoursFlg='No';
        if($newdata->OfficeHoursFlg==1) {
            $newOfficeHoursFlg='Yes';
        }
        $html .='<br>Old Enter Office hours: '.$oldOfficeHoursFlg.' <span class="diff">||</span> New Enter Office hours: '.$newOfficeHoursFlg;
    }
    if($olddata->LaytimeCommencementFlg != $newdata->LaytimeCommencementFlg) {
        $section='<br><br><B>Loadport</B><br>';
        $oldLaytimeCommencementFlg='No';
        if($olddata->LaytimeCommencementFlg==1) {
            $oldLaytimeCommencementFlg='Yes';
        }
        $newLaytimeCommencementFlg='No';
        if($newdata->LaytimeCommencementFlg==1) {
            $newLaytimeCommencementFlg='Yes';
        }
        $html .='<br>Old Enter laytime commencement: '.$oldLaytimeCommencementFlg.' <span class="diff">||</span> New Enter laytime commencement: '.$newLaytimeCommencementFlg;
    }
        
     $this->db->select('udt_AU_ResponseExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
     $this->db->from('udt_AU_ResponseExceptedPeriods');
     $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseExceptedPeriods.EventID', 'left');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->order_by('EPID', 'asc');
     $qry=$this->db->get();
     $expDataOld=$qry->result_array();
        
     $this->db->select('udt_AU_ResponseExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
     $this->db->from('udt_AU_ResponseExceptedPeriods');
     $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseExceptedPeriods.EventID', 'left');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->order_by('EPID', 'asc');
     $qry1=$this->db->get();
     $expDataNew=$qry1->result_array();
        
    for($exp=0; $exp < count($expDataOld) && $exp < count($expDataNew); $exp++){
        $OldLaytimeCountsOnDemurrageFlg='-';
        $OldLaytimeCountsFlg='-';
        $OldTimeCountingFlg='-';
        $NewLaytimeCountsOnDemurrageFlg='-';
        $NewLaytimeCountsFlg='-';
        $NewTimeCountingFlg='-';
        if($expDataOld[$exp]['EventID'] != $expDataNew[$exp]['EventID']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Event name : '.$expDataOld[$exp]['ExceptedDescription'].' <span class="diff">||</span> New Event name : '.$expDataNew[$exp]['ExceptedDescription'];
        }
        if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg'] != $expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']) {
            if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                $OldLaytimeCountsOnDemurrageFlg='Yes';
            }else if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                $OldLaytimeCountsOnDemurrageFlg='No';
            }
                
            if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                $NewLaytimeCountsOnDemurrageFlg='Yes';
            } else if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                $NewLaytimeCountsOnDemurrageFlg='No';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Laytime Counts on demurrage  : '.$OldLaytimeCountsOnDemurrageFlg.' <span class="diff">||</span> New Laytime Counts on demurrage  : '.$NewLaytimeCountsOnDemurrageFlg;
        }
        if($expDataOld[$exp]['LaytimeCountsFlg'] != $expDataNew[$exp]['LaytimeCountsFlg']) {
            if($expDataOld[$exp]['LaytimeCountsFlg']==1) {
                $OldLaytimeCountsFlg='Yes';
            } else if($expDataOld[$exp]['LaytimeCountsFlg']==2) {
                $OldLaytimeCountsFlg='No';
            }
            if($expDataNew[$exp]['LaytimeCountsFlg']==1) {
                $NewLaytimeCountsFlg='Yes';
            }else if($expDataNew[$exp]['LaytimeCountsFlg']==2) {
                $NewLaytimeCountsFlg='No';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Laytime counts, if used : '.$OldLaytimeCountsFlg.' <span class="diff">||</span> New Laytime counts, if used  : '.$NewLaytimeCountsFlg;
        }
        if($expDataOld[$exp]['TimeCountingFlg'] != $expDataNew[$exp]['TimeCountingFlg']) {
            if($expDataOld[$exp]['TimeCountingFlg']==102) {
                $OldTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
            }else if($expDataOld[$exp]['TimeCountingFlg']==10) {
                $OldTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
            }
                
            if($expDataNew[$exp]['TimeCountingFlg']==102) {
                $NewTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
            }else if($expDataNew[$exp]['TimeCountingFlg']==10) {
                $NewTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time counting, if used : '.$OldTimeCountingFlg.' <span class="diff">||</span> New Time counting, if used : '.$NewTimeCountingFlg;
        }
    }
        
     $this->db->select('udt_AU_ResponseNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
     $this->db->from('udt_AU_ResponseNORTenderingPreConditions');
     $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->order_by('TPCID', 'asc');
     $qry2=$this->db->get();
     $tenderingOldData=$qry2->result_array();
        
     $this->db->select('udt_AU_ResponseNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
     $this->db->from('udt_AU_ResponseNORTenderingPreConditions');
     $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->order_by('TPCID', 'asc');
     $qry21=$this->db->get();
     $tenderingNewData=$qry21->result_array();
        
    for($tend=0; $tend < count($tenderingOldData) && $tend < count($tenderingNewData); $tend++){
        if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] != $tenderingNewData[$tend]['CreateNewOrSelectListFlg']) {
            $CreateNewOrSelectListFlgOld='';
            if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] == 1) {
                 $CreateNewOrSelectListFlgOld='create new';
            } else if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] == 2) {
                $CreateNewOrSelectListFlgOld='select from pre defined list';
            }
                
            $CreateNewOrSelectListFlgNew='';
            if($tenderingNewData[$tend]['CreateNewOrSelectListFlg'] == 1) {
                $CreateNewOrSelectListFlgNew='create new';
            } else if($tenderingNewData[$tend]['CreateNewOrSelectListFlg'] == 2) {
                $CreateNewOrSelectListFlgNew='select from pre defined list';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' <span class="diff">||</span> New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
        }
        if($tenderingOldData[$tend]['NORTenderingPreConditionID'] != $tenderingNewData[$tend]['NORTenderingPreConditionID']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Name of condition : '.$tenderingOldData[$tend]['TenderingCode'].' <span class="diff">||</span> New Name of condition : '.$tenderingNewData[$tend]['TenderingCode'];
        }
        if($tenderingOldData[$tend]['NewNORTenderingPreCondition'] != $tenderingNewData[$tend]['NewNORTenderingPreCondition']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Name of condition : '.$tenderingOldData[$tend]['NewNORTenderingPreCondition'].' <span class="diff">||</span> New Name of condition : '.$tenderingNewData[$tend]['NewNORTenderingPreCondition'];
        }
        if($tenderingOldData[$tend]['StatusFlag'] != $tenderingNewData[$tend]['StatusFlag']) {
            $StatusFlagOld='In Active';
            if($tenderingOldData[$tend]['StatusFlag']==1) {
                $StatusFlagOld='Active';
            }
            $StatusFlagNew='In Active';
            if($tenderingNewData[$tend]['StatusFlag']==1) {
                $StatusFlagNew='Active';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Activate : '.$StatusFlagOld.' <span class="diff">||</span> New Activate : '.$StatusFlagNew;
        }
    }
        
     $this->db->select('udt_AU_ResponseNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
     $this->db->from('udt_AU_ResponseNORAcceptancePreConditions');
     $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->order_by('APCID', 'asc');
     $qry3=$this->db->get();
     $acceptOldData=$qry3->result_array();
        
     $this->db->select('udt_AU_ResponseNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
     $this->db->from('udt_AU_ResponseNORAcceptancePreConditions');
     $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->order_by('APCID', 'asc');
     $qry31=$this->db->get();
     $acceptNewData=$qry31->result_array();
        
    for($accept=0; $accept < count($acceptOldData) && $accept < count($acceptNewData); $accept++){
        if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] != $acceptNewData[$accept]['CreateNewOrSelectListFlg']) {
            $CreateNewOrSelectListFlgOld='';
            if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] == 1) {
                 $CreateNewOrSelectListFlgOld='create new';
            } else if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] == 2) {
                $CreateNewOrSelectListFlgOld='select from pre defined list';
            }
                
            $CreateNewOrSelectListFlgNew='';
            if($acceptNewData[$accept]['CreateNewOrSelectListFlg'] == 1) {
                $CreateNewOrSelectListFlgNew='create new';
            } else if($acceptNewData[$accept]['CreateNewOrSelectListFlg'] == 2) {
                $CreateNewOrSelectListFlgNew='select from pre defined list';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' <span class="diff">||</span> New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
        }
        if($acceptOldData[$accept]['NORAcceptancePreConditionID'] != $acceptNewData[$accept]['NORAcceptancePreConditionID']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Name of condition : '.$acceptOldData[$accept]['AcceptanceCode'].' <span class="diff">||</span> New Name of condition : '.$acceptNewData[$accept]['AcceptanceCode'];
        }
        if($acceptOldData[$accept]['NewNORAcceptancePreCondition'] != $acceptNewData[$accept]['NewNORAcceptancePreCondition']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Name of condition : '.$acceptOldData[$accept]['NewNORAcceptancePreCondition'].' <span class="diff">||</span> New Name of condition : '.$acceptNewData[$accept]['NewNORAcceptancePreCondition'];
        }
        if($acceptOldData[$accept]['StatusFlag'] != $acceptNewData[$accept]['StatusFlag']) {
            $StatusFlagOld='In Active';
            if($acceptOldData[$accept]['StatusFlag']==1) {
                $StatusFlagOld='Active';
            }
            $StatusFlagNew='In Active';
            if($acceptNewData[$accept]['StatusFlag']==1) {
                $StatusFlagNew='Active';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Activate : '.$StatusFlagOld.' <span class="diff">||</span> New Activate : '.$StatusFlagNew;
        }
    }
        
     $this->db->select('*');
     $this->db->from('udt_AU_ResponseOfficeHours');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->order_by('OHID', 'asc');
     $qry4=$this->db->get();
     $officeOldData=$qry4->result_array();
        
     $this->db->select('*');
     $this->db->from('udt_AU_ResponseOfficeHours');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->order_by('OHID', 'asc');
     $qry4=$this->db->get();
     $officeNewData=$qry4->result_array();
        
    for($office=0; $office < count($officeOldData) && $office < count($officeNewData); $office++){
        if($officeOldData[$office]['DateFrom'] != $officeNewData[$office]['DateFrom']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Day (From) : '.$officeOldData[$office]['DateFrom'].' <span class="diff">||</span> New Day (From) : '.$officeNewData[$office]['DateFrom'];
        }
        if($officeOldData[$office]['DateTo'] != $officeNewData[$office]['DateTo']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Day (To) : '.$officeOldData[$office]['DateTo'].' <span class="diff">||</span> New Day (To) : '.$officeNewData[$office]['DateTo'];
        }
        if($officeOldData[$office]['TimeFrom'] != $officeNewData[$office]['TimeFrom']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time (From) : '.$officeOldData[$office]['TimeFrom'].' <span class="diff">||</span> New Time (From) : '.$officeNewData[$office]['TimeFrom'];
        }
        if($officeOldData[$office]['TimeTo'] != $officeNewData[$office]['TimeTo']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time (To) : '.$officeOldData[$office]['TimeTo'].' <span class="diff">||</span> New Time (To) : '.$officeNewData[$office]['TimeTo'];
        }
        if($officeOldData[$office]['IsLastEntry'] != $officeNewData[$office]['IsLastEntry']) {
            $IsLastEntryOld='No';
            if($officeOldData[$office]['IsLastEntry']==1) {
                $IsLastEntryOld='Yes';
            }
            $IsLastEntryNew='No';
            if($officeNewData[$office]['IsLastEntry']==1) {
                $IsLastEntryNew='Yes';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Is last entry : '.$IsLastEntryOld.' <span class="diff">||</span> New Is last entry : '.$IsLastEntryNew;
        }
    }
        
     $this->db->select('udt_AU_ResponseLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
     $this->db->from('udt_AU_ResponseLaytimeCommencement');
     $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseLaytimeCommencement.TurnTime', 'left');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->order_by('LCID', 'asc');
     $qry5=$this->db->get();
     $laytimeOldData=$qry5->result_array();
        
     $this->db->select('udt_AU_ResponseLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
     $this->db->from('udt_AU_ResponseLaytimeCommencement');
     $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseLaytimeCommencement.TurnTime', 'left');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->order_by('LCID', 'asc');
     $qry51=$this->db->get();
     $laytimeNewData=$qry51->result_array();
        
    for($lay=0; $lay < count($laytimeOldData) && $lay < count($laytimeNewData); $lay++){
        if($laytimeOldData[$lay]['DayFrom'] != $laytimeNewData[$lay]['DayFrom']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Day (From) : '.$laytimeOldData[$lay]['DayFrom'].' <span class="diff">||</span> New Day (From) : '.$laytimeNewData[$lay]['DayFrom'];
        }
        if($laytimeOldData[$lay]['DayTo'] != $laytimeNewData[$lay]['DayTo']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Day (To) : '.$laytimeOldData[$lay]['DayTo'].' <span class="diff">||</span> New Day (To) : '.$laytimeNewData[$lay]['DayTo'];
        }
        if($laytimeOldData[$lay]['TimeFrom'] != $laytimeNewData[$lay]['TimeFrom']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time (From) : '.$laytimeOldData[$lay]['TimeFrom'].' <span class="diff">||</span> New Time (From) : '.$laytimeNewData[$lay]['TimeFrom'];
        }
        if($laytimeOldData[$lay]['TimeTo'] != $laytimeNewData[$lay]['TimeTo']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time (To) : '.$laytimeOldData[$lay]['TimeTo'].' <span class="diff">||</span> New Time (To) : '.$laytimeNewData[$lay]['TimeTo'];
        }
        if($laytimeOldData[$lay]['TurnTime'] != $laytimeNewData[$lay]['TurnTime']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Turn time applies : '.$laytimeOldData[$lay]['LaytimeCode'].' <span class="diff">||</span> New Turn time applies : '.$laytimeNewData[$lay]['LaytimeCode'];
        }
        if($laytimeOldData[$lay]['TurnTimeExpire'] != $laytimeNewData[$lay]['TurnTimeExpire']) {
            $TurnTimeExpireOld='';
            if($laytimeOldData[$lay]['TurnTimeExpire'] == 1) {
                $TurnTimeExpireOld='During office hours';
            } else if($laytimeOldData[$lay]['TurnTimeExpire'] == 2) {
                $TurnTimeExpireOld='After office hours';
            }
            $TurnTimeExpireNew='';
            if($laytimeNewData[$lay]['TurnTimeExpire'] == 1) {
                $TurnTimeExpireNew='During office hours';
            } else if($laytimeNewData[$lay]['TurnTimeExpire'] == 2) {
                $TurnTimeExpireNew='After office hours';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Turn time expires : '.$TurnTimeExpireOld.' <span class="diff">||</span> New Turn time expires : '.$TurnTimeExpireNew;
        }
        if($laytimeOldData[$lay]['LaytimeCommenceAt'] != $laytimeNewData[$lay]['LaytimeCommenceAt']) {
            $LaytimeCommenceAtOld='';
            if($laytimeOldData[$lay]['LaytimeCommenceAt'] == 1) {
                $LaytimeCommenceAtOld='At expiry of turn time';
            } else if($laytimeOldData[$lay]['LaytimeCommenceAt'] == 2) {
                $LaytimeCommenceAtOld='At specified hour';
            }
            $LaytimeCommenceAtNew='';
            if($laytimeNewData[$lay]['LaytimeCommenceAt'] == 1) {
                $LaytimeCommenceAtNew='At expiry of turn time';
            } else if($laytimeNewData[$lay]['LaytimeCommenceAt'] == 2) {
                $LaytimeCommenceAtNew='At specified hour';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old laytime commences at : '.$LaytimeCommenceAtOld.' <span class="diff">||</span> New laytime commences at : '.$LaytimeCommenceAtNew;
        }
        if($laytimeOldData[$lay]['LaytimeCommenceAtHour'] != $laytimeNewData[$lay]['LaytimeCommenceAtHour']) {
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Turn time expires : '.$laytimeOldData[$lay]['LaytimeCommenceAtHour'].' <span class="diff">||</span> New Turn time expires : '.$laytimeNewData[$lay]['LaytimeCommenceAtHour'];
        }
        if($laytimeOldData[$lay]['SelectDay'] != $laytimeNewData[$lay]['SelectDay']) {
            $OldSelectDay='';
            if($laytimeOldData[$lay]['SelectDay']==1) {
                $OldSelectDay='Same Day';
            } else if($laytimeOldData[$lay]['SelectDay']==2) {
                $OldSelectDay='New Working Day';
            }
            $NewSelectDay='';
            if($laytimeNewData[$lay]['SelectDay']==1) {
                $NewSelectDay='Same Day';
            } else if($laytimeNewData[$lay]['SelectDay']==2) {
                $NewSelectDay='New Working Day';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Select day : '.$OldSelectDay.' <span class="diff">||</span> New Select day : '.$NewSelectDay;
        }
        if($laytimeOldData[$lay]['TimeCountsIfOnDemurrage'] != $laytimeNewData[$lay]['TimeCountsIfOnDemurrage']) {
            $OldTimeCountsIfOnDemurrage='No';
            if($laytimeOldData[$lay]['TimeCountsIfOnDemurrage']==1) {
                $OldTimeCountsIfOnDemurrage='Yes';
            }
            $NewTimeCountsIfOnDemurrage='No';
            if($laytimeNewData[$lay]['TimeCountsIfOnDemurrage']==1) {
                $NewTimeCountsIfOnDemurrage='Yes';
            }
            $section='<br><br><B>Loadport</B><br>';
            $html .='<br>Old Time counts if on Demurrage : '.$OldTimeCountsIfOnDemurrage.' <span class="diff">||</span> New Time counts if on Demurrage : '.$NewTimeCountsIfOnDemurrage;
        }
    }
        
        
     $totalhtml .=$section;
     $totalhtml .=$html;
     $section='';
     $html='';
        
     $this->db->select('udt_AU_ResponseCargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, ddt1.code as ddtTermCode, sdt.Description as sdtDescription');
     $this->db->from('udt_AU_ResponseCargoDisports');
     $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
     $this->db->join('udt_CP_LoadingDischargeTermsMaster as ddt1', 'ddt1.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
     $this->db->join('udt_CP_SteveDoringTerms as sdt', 'sdt.ID=udt_AU_ResponseCargoDisports.DpStevedoringTerms', 'left');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $query12=$this->db->get();
     $CargoDisports=$query12->result();
        
    foreach($CargoDisports as $cd){
        $this->db->select('udt_AU_ResponseCargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, ddt1.code as ddtTermCode, sdt.Description as sdtDescription');
        $this->db->from('udt_AU_ResponseCargoDisports');
        $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
        $this->db->join('udt_CP_LoadingDischargeTermsMaster as ddt1', 'ddt1.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
        $this->db->join('udt_CP_SteveDoringTerms as sdt', 'sdt.ID=udt_AU_ResponseCargoDisports.DpStevedoringTerms', 'left');
        $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
        $this->db->where('DisportNo', $cd->DisportNo);
        $query12=$this->db->get();
        $newDisports=$query12->row();
            
        if($newDisports) {
            if($cd->DisPort != $newDisports->DisPort) {
                 $section='<br><br><B>Disport</B><br>';
                 $html .='<br>Old Disport : '.$cd->dspPortName.' <span class="diff">||</span> New Disport : '.$newDisports->dspPortName;
            }
            $oldDpArrivalStartDate=date('Y-m-d H:i:s', strtotime($cd->DpArrivalStartDate));
            $newDpArrivalStartDate=date('Y-m-d H:i:s', strtotime($newDisports->DpArrivalStartDate));
            if($oldDpArrivalStartDate != $newDpArrivalStartDate) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Disport Arrival Start Date : '.date('d-m-Y H:i:s', strtotime($oldDpArrivalStartDate)).' <span class="diff">||</span> New Disport Arrival Start Date : '.date('d-m-Y H:i:s', strtotime($newDpArrivalStartDate));
            }
            $oldDpArrivalEndDate=date('Y-m-d H:i:s', strtotime($cd->DpArrivalEndDate));
            $newDpArrivalEndDate=date('Y-m-d H:i:s', strtotime($newDisports->DpArrivalEndDate));
            if($oldDpArrivalEndDate != $newDpArrivalEndDate) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Disport Arrival End Date : '.date('d-m-Y H:i:s', strtotime($oldDpArrivalEndDate)).' <span class="diff">||</span> New Disport Arrival End Date : '.date('d-m-Y H:i:s', strtotime($newDpArrivalEndDate));
            }
            $oldDpPreferDate=date('Y-m-d H:i:s', strtotime($cd->DpPreferDate));
            $newDpPreferDate=date('Y-m-d H:i:s', strtotime($newDisports->DpPreferDate));
            if($oldDpPreferDate != $newDpPreferDate) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Disport Preferred Date : '.date('d-m-Y H:i:s', strtotime($oldDpPreferDate)).' <span class="diff">||</span> New Disport Preferred Date : '.date('d-m-Y H:i:s', strtotime($newDpPreferDate));
            }
            if($cd->ExpectedDpDelayDay != $newDisports->ExpectedDpDelayDay) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Expected Disport Delay : '.$cd->ExpectedDpDelayDay.' Days '.$cd->ExpectedDpDelayHour.' Hours <span class="diff">||</span> New Expected Disport Delay : '.$newDisports->ExpectedDpDelayDay.' Days '.$newDisports->ExpectedDpDelayHour.' Hours';
            }else if($cd->ExpectedDpDelayHour != $newDisports->ExpectedDpDelayHour) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Expected Disport Delay : '.$cd->ExpectedDpDelayDay.' Days '.$cd->ExpectedDpDelayHour.' Hours <span class="diff">||</span> New Expected Disport Delay : '.$newDisports->ExpectedDpDelayDay.' Days '.$newDisports->ExpectedDpDelayHour.' Hours';
            }
                
            if($cd->DischargingTerms != $newDisports->DischargingTerms) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Discharging Term : '.$cd->ddtTermCode.' <span class="diff">||</span> New Discharging Term : '.$newDisports->ddtTermCode;
            }
                
            $oldDischargingRateMT=(int)$cd->DischargingRateMT;
            $newDischargingRateMT=(int)$newDisports->DischargingRateMT;
            if($oldDischargingRateMT != $newDischargingRateMT) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Discharging Rate : '.number_format($oldDischargingRateMT).' <span class="diff">||</span> New Discharging Rate : '.number_format($newDischargingRateMT);
            }
                
            if($cd->DischargingRateUOM != $newDisports->DischargingRateUOM) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DischargingRateUOM=='1') {
                    $oldDischargingRateUOM='Per Hour';
                }else if($cd->DischargingRateUOM=='2') {
                    $oldDischargingRateUOM='Per Weater Working Day';
                }else if($cd->DischargingRateUOM=='3') {
                    $oldDischargingRateUOM='Max Time Limit';
                }
                if($newDisports->DischargingRateUOM=='1') {
                    $newDischargingRateUOM='Per Hour';
                }else if($newDisports->DischargingRateUOM=='2') {
                    $newDischargingRateUOM='Per Weater Working Day';
                }else if($newDisports->DischargingRateUOM=='3') {
                    $newDischargingRateUOM='Max Time Limit';
                }
                $html .='<br>Old Loading Rate UOM: '.$oldDischargingRateUOM.' <span class="diff">||</span> New Loading Rate UOM: '.$newDischargingRateUOM;
            }
                
            if($cd->DpLaytimeType != $newDisports->DpLaytimeType) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpLaytimeType=='1') {
                    $oldDpLaytimeType='Reversible';
                }else if($cd->DpLaytimeType=='2') {
                    $oldDpLaytimeType='Non Reversible';
                }else if($cd->DpLaytimeType=='3') {
                    $oldDpLaytimeType='Average';
                }
                if($newDisports->DpLaytimeType=='1') {
                    $newDpLaytimeType='Reversible';
                }else if($newDisports->DpLaytimeType=='2') {
                    $newDpLaytimeType='Non Reversible';
                }else if($newDisports->DpLaytimeType=='3') {
                    $newDpLaytimeType='Average';
                }
                $html .='<br>Old Discharging Laytime type: '.$oldDpLaytimeType.' <span class="diff">||</span> New Discharging Laytime type: '.$newDpLaytimeType;
            }
                
            if($cd->DpCalculationBasedOn != $newDisports->DpCalculationBasedOn) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpCalculationBasedOn=='108') {
                    $oldDpCalculationBasedOn='Bill of Loading Quantity';
                }else if($cd->DpCalculationBasedOn=='109') {
                    $oldDpCalculationBasedOn='Outturn or Discharge Quantity';
                } 
                if($newDisports->DpCalculationBasedOn=='108') {
                    $newDpCalculationBasedOn='Bill of Loading Quantity';
                }else if($newDisports->DpCalculationBasedOn=='109') {
                    $newDpCalculationBasedOn='Outturn or Discharge Quantity';
                } 
                $html .='<br>Old Discharging Calculation Based on: '.$oldDpCalculationBasedOn.' <span class="diff">||</span> New Discharging Calculation Based on: '.$newDpCalculationBasedOn;
            }
            if($cd->DpTurnTime != $newDisports->DpTurnTime) {    
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpTurnTime=='1') {
                    $oldDpTurnTime='LT freetime';
                }else if($cd->DpTurnTime=='2') {
                    $oldDpTurnTime='LayTime Free test';
                } else if($cd->DpTurnTime=='3') {
                    $oldDpTurnTime='12HAA';
                } else if($cd->DpTurnTime=='4') {
                    $oldDpTurnTime='24HAA';
                } else if($cd->DpTurnTime=='5') {
                    $oldDpTurnTime='4HAA';
                } else if($cd->DpTurnTime=='6') {
                    $oldDpTurnTime='6HAA';
                } else if($cd->DpTurnTime=='7') {
                    $oldDpTurnTime='8HAA';
                } else if($cd->DpTurnTime=='8') {
                    $oldDpTurnTime='16HAA ';
                } else if($cd->DpTurnTime=='9') {
                    $oldDpTurnTime='20HAA';
                } else if($cd->DpTurnTime=='10') {
                    $oldDpTurnTime='18HAA';
                } 
                    
                if($newDisports->DpTurnTime=='1') {
                    $newDpTurnTime='LT freetime';
                }else if($newDisports->DpTurnTime=='2') {
                    $newDpTurnTime='LayTime Free test';
                } else if($newDisports->DpTurnTime=='3') {
                    $newDpTurnTime='12HAA';
                } else if($newDisports->DpTurnTime=='4') {
                    $newDpTurnTime='24HAA';
                } else if($newDisports->DpTurnTime=='5') {
                    $newDpTurnTime='4HAA';
                } else if($newDisports->DpTurnTime=='6') {
                    $newDpTurnTime='6HAA';
                } else if($newDisports->DpTurnTime=='7') {
                    $newDpTurnTime='8HAA';
                } else if($newDisports->DpTurnTime=='8') {
                    $newDpTurnTime='16HAA ';
                } else if($newDisports->DpTurnTime=='9') {
                    $newDpTurnTime='20HAA';
                } else if($newDisports->DpTurnTime=='10') {
                    $newDpTurnTime='18HAA';
                } 
                    
                $html .='<br>Old Disport Turn Time: '.$oldDpTurnTime.' <span class="diff">||</span> New Disport Turn Time: '.$newDpTurnTime;
            }
                
            if($cd->DpPriorUseTerms != $newDisports->DpPriorUseTerms) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpPriorUseTerms=='102') {
                    $oldDpPriorUseTerms='IUATUTC';
                }else if($cd->DpPriorUseTerms=='10') {
                    $oldDpPriorUseTerms='IUHTUTC ';
                }else{
                    $oldDpPriorUseTerms='N/A ';
                }
                if($newDisports->DpPriorUseTerms=='102') {
                    $newDpPriorUseTerms='IUATUTC';
                }else if($newDisports->DpPriorUseTerms=='10') {
                    $newDpPriorUseTerms='IUHTUTC ';
                } else{
                    $newDpPriorUseTerms='N/A';
                }
                $html .='<br>Old Disport Prior Use Term: '.$oldDpPriorUseTerms.' <span class="diff">||</span> New Disport Prior Use Term: '.$newDpPriorUseTerms;
            }
            if($cd->DpLaytimeBasedOn != $newDisports->DpLaytimeBasedOn) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpLaytimeBasedOn=='1') {
                    $oldDpLaytimeBasedOn='ATS';
                }else if($cd->DpLaytimeBasedOn=='2') {
                    $oldDpLaytimeBasedOn='WTS ';
                } 
                if($newDisports->DpLaytimeBasedOn=='1') {
                    $newDpLaytimeBasedOn='ATS';
                }else if($newDisports->DpLaytimeBasedOn=='2') {
                    $newDpLaytimeBasedOn='WTS ';
                } 
                $html .='<br>Old Discharging laytime Based on: '.$oldDpLaytimeBasedOn.' <span class="diff">||</span> New Discharging laytime Based on: '.$newDpLaytimeBasedOn;
            }
            if($cd->DpCharterType != $newDisports->DpCharterType) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpCharterType=='1') {
                    $oldDpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                }else if($cd->DpCharterType=='2') {
                    $oldDpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
                }else if($cd->DpCharterType=='2') {
                    $oldDpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
                }else if($cd->DpCharterType=='2') {
                    $oldDpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
                } 
                if($newDisports->DpCharterType=='1') {
                    $newDpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                }else if($newDisports->DpCharterType=='2') {
                    $newDpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
                } else if($newDisports->DpCharterType=='3') {
                    $newDpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
                } else if($newDisports->DpCharterType=='4') {
                    $newDpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
                } 
                $html .='<br>Old Discharging Charter Type: '.$oldDpCharterType.' <span class="diff">||</span> New Discharging Charter Type: '.$newDpCharterType;
            }
            if($cd->DpNorTendering != $newDisports->DpNorTendering) {
                $section='<br><br><B>Disport</B><br>';
                if($cd->DpNorTendering=='1') {
                    $oldDpNorTendering='ATDNSHINC';
                }else if($cd->DpNorTendering=='2') {
                    $oldDpNorTendering='ATDNFHINC';
                }else if($cd->DpNorTendering=='3') {
                    $oldDpNorTendering='OFFICE HOURS';
                }else if($cd->DpNorTendering=='4') {
                    $oldDpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
                } 
                if($newDisports->LpNorTendering=='1') {
                    $newDpNorTendering='ATDNSHINC';
                }else if($newDisports->LpNorTendering=='2') {
                    $newDpNorTendering='ATDNFHINC';
                }else if($newDisports->LpNorTendering=='3') {
                    $newDpNorTendering='OFFICE HOURS';
                }else if($newDisports->LpNorTendering=='4') {
                    $newDpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
                } 
                $html .='<br>Old Discharging NorTendering: '.$oldLpNorTendering.' <span class="diff">||</span> New Discharging NorTendering: '.$newLpNorTendering;
            }
                
            if($cd->DpStevedoringTerms != $newDisports->DpStevedoringTerms) {
                $section='<br><br><B>Disport</B><br>';
                $html .='<br>Old Discharging Stevedoring terms: '.$cd->sdtDescription.' <span class="diff">||</span> New Discharging Stevedoring terms: '.$newDisports->sdtDescription;
            }
                
            if($cd->DpExceptedPeriodFlg != $newDisports->DpExceptedPeriodFlg) {
                $section='<br><br><B>Disport</B><br>';
                $oldExceptedPeriodFlg='No';
                if($cd->DpExceptedPeriodFlg==1) {
                    $oldExceptedPeriodFlg='Yes';
                }
                $newExceptedPeriodFlg='No';
                if($newDisports->DpExceptedPeriodFlg==1) {
                    $newExceptedPeriodFlg='Yes';
                }
                $html .='<br>Old Excepted periods for events: '.$oldExceptedPeriodFlg.' <span class="diff">||</span> New Excepted periods for events: '.$newExceptedPeriodFlg;
            }
                
            if($cd->DpNORTenderingPreConditionFlg != $newDisports->DpNORTenderingPreConditionFlg) {
                $section='<br><br><B>Disport</B><br>';
                $oldNORTenderingPreConditionFlg='No';
                if($cd->DpNORTenderingPreConditionFlg==1) {
                    $oldNORTenderingPreConditionFlg='Yes';
                }
                $newNORTenderingPreConditionFlg='No';
                if($newDisports->DpNORTenderingPreConditionFlg==1) {
                    $newNORTenderingPreConditionFlg='Yes';
                }
                $html .='<br>Old NOR tendering pre conditions apply: '.$oldNORTenderingPreConditionFlg.' <span class="diff">||</span> New NOR tendering pre conditions apply: '.$newNORTenderingPreConditionFlg;
            }
                
            if($cd->DpNORAcceptancePreConditionFlg != $newDisports->DpNORAcceptancePreConditionFlg) {
                $section='<br><br><B>Disport</B><br>';
                $oldNORAcceptancePreConditionFlg='No';
                if($cd->DpNORAcceptancePreConditionFlg==1) {
                    $oldNORAcceptancePreConditionFlg='Yes';
                }
                $newNORAcceptancePreConditionFlg='No';
                if($newDisports->DpNORAcceptancePreConditionFlg==1) {
                    $newNORAcceptancePreConditionFlg='Yes';
                }
                $html .='<br>Old NOR acceptance pre conditions apply: '.$oldNORAcceptancePreConditionFlg.' <span class="diff">||</span> New NOR acceptance pre conditions apply: '.$newNORAcceptancePreConditionFlg;
            }
                
            if($cd->DpOfficeHoursFlg != $newDisports->DpOfficeHoursFlg) {
                $section='<br><br><B>Disport</B><br>';
                $oldOfficeHoursFlg='No';
                if($cd->DpOfficeHoursFlg==1) {
                    $oldOfficeHoursFlg='Yes';
                }
                $newOfficeHoursFlg='No';
                if($newDisports->DpOfficeHoursFlg==1) {
                    $newOfficeHoursFlg='Yes';
                }
                $html .='<br>Old Enter Office hours: '.$oldOfficeHoursFlg.' <span class="diff">||</span> New Enter Office hours: '.$newOfficeHoursFlg;
            }
            if($cd->DpLaytimeCommencementFlg != $newDisports->DpLaytimeCommencementFlg) {
                $section='<br><br><B>Disport</B><br>';
                $oldLaytimeCommencementFlg='No';
                if($cd->DpLaytimeCommencementFlg==1) {
                    $oldLaytimeCommencementFlg='Yes';
                }
                $newLaytimeCommencementFlg='No';
                if($newDisports->DpLaytimeCommencementFlg==1) {
                    $newLaytimeCommencementFlg='Yes';
                }
                $html .='<br>Old Enter laytime commencement: '.$oldLaytimeCommencementFlg.' <span class="diff">||</span> New Enter laytime commencement: '.$newLaytimeCommencementFlg;
            }
                
            $this->db->select('udt_AU_ResponseDpExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
            $this->db->from('udt_AU_ResponseDpExceptedPeriods');
            $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseDpExceptedPeriods.EventID', 'left');
            $this->db->where('ResponseDisportID', $cd->RCD_ID);
            $this->db->order_by('EPID', 'asc');
            $qry=$this->db->get();
            $expDataOld=$qry->result_array();
                
            $this->db->select('udt_AU_ResponseDpExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
            $this->db->from('udt_AU_ResponseDpExceptedPeriods');
            $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseDpExceptedPeriods.EventID', 'left');
            $this->db->where('ResponseDisportID', $newDisports->RCD_ID);
            $this->db->order_by('EPID', 'asc');
            $qry1=$this->db->get();
            $expDataNew=$qry1->result_array();
                
            for($exp=0; $exp < count($expDataOld) && $exp < count($expDataNew); $exp++){
                $OldLaytimeCountsOnDemurrageFlg='-';
                $NewLaytimeCountsOnDemurrageFlg='-';
                $OldLaytimeCountsFlg='-';
                $NewLaytimeCountsFlg='-';
                $OldTimeCountingFlg='-';
                $NewTimeCountingFlg='-';
                if($expDataOld[$exp]['EventID'] != $expDataNew[$exp]['EventID']) {
                    $section='<br><br><B>Disport</B><br>';
                    $html .='<br>Old Event name : '.$expDataOld[$exp]['ExceptedDescription'].' <span class="diff">||</span> New Event name : '.$expDataNew[$exp]['ExceptedDescription'];
                }
                if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg'] != $expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']) {
                    if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                        $OldLaytimeCountsOnDemurrageFlg='Yes';
                    }else if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                        $OldLaytimeCountsOnDemurrageFlg='No';
                    }
                    if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                        $NewLaytimeCountsOnDemurrageFlg='Yes';
                    }else if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                        $NewLaytimeCountsOnDemurrageFlg='No';
                    }
                    $section='<br><br><B>Disport</B><br>';
                    $html .='<br>Old Laytime Counts on demurrage  : '.$OldLaytimeCountsOnDemurrageFlg.' <span class="diff">||</span> New Laytime Counts on demurrage  : '.$NewLaytimeCountsOnDemurrageFlg;
                }
                if($expDataOld[$exp]['LaytimeCountsFlg'] != $expDataNew[$exp]['LaytimeCountsFlg']) {
                    if($expDataOld[$exp]['LaytimeCountsFlg']==1) {
                        $OldLaytimeCountsFlg='Yes';
                    }else if($expDataOld[$exp]['LaytimeCountsFlg']==2) {
                        $OldLaytimeCountsFlg='No';
                    }
                    if($expDataNew[$exp]['LaytimeCountsFlg']==1) {
                        $NewLaytimeCountsFlg='Yes';
                    }else if($expDataNew[$exp]['LaytimeCountsFlg']==2) {
                        $NewLaytimeCountsFlg='No';
                    }
                    $section='<br><br><B>Disport</B><br>';
                    $html .='<br>Old Laytime counts, if used : '.$OldLaytimeCountsFlg.' <span class="diff">||</span> New Laytime counts, if used  : '.$NewLaytimeCountsFlg;
                }
                if($expDataOld[$exp]['TimeCountingFlg'] != $expDataNew[$exp]['TimeCountingFlg']) {
                    if($expDataOld[$exp]['TimeCountingFlg']==102) {
                        $OldTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                    }else if($expDataOld[$exp]['TimeCountingFlg']==10) {
                        $OldTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                    }
                    if($expDataNew[$exp]['TimeCountingFlg']==102) {
                        $NewTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                    }else if($expDataNew[$exp]['TimeCountingFlg']==10) {
                        $NewTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                    }
                    $section='<br><br><B>Disport</B><br>';
                    $html .='<br>Old Time counting, if used : '.$OldTimeCountingFlg.' <span class="diff">||</span> New Time counting, if used : '.$NewTimeCountingFlg;
                }
            }
                        
            $this->db->select('udt_AU_ResponseDpNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
            $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions');
            $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseDpNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
            $this->db->where('ResponseDisportID', $cd->RCD_ID);
            $this->db->order_by('TPCID', 'asc');
            $qry2=$this->db->get();
            $tenderingOldData=$qry2->result_array();
                
            $this->db->select('udt_AU_ResponseDpNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
            $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions');
            $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseDpNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
            $this->db->where('ResponseDisportID', $newDisports->RCD_ID);
            $this->db->order_by('TPCID', 'asc');
            $qry21=$this->db->get();
            $tenderingNewData=$qry21->result_array();
                
            for($tend=0; $tend < count($tenderingOldData) && $tend < count($tenderingNewData); $tend++){
                if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] != $tenderingNewData[$tend]['CreateNewOrSelectListFlg']) {
                    $CreateNewOrSelectListFlgOld='';
                    if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] == 1) {
                        $CreateNewOrSelectListFlgOld='create new';
                    } else if($tenderingOldData[$tend]['CreateNewOrSelectListFlg'] == 2) {
                        $CreateNewOrSelectListFlgOld='select from pre defined list';
                    }
                        
                    $CreateNewOrSelectListFlgNew='';
                    if($tenderingNewData[$tend]['CreateNewOrSelectListFlg'] == 1) {
                        $CreateNewOrSelectListFlgNew='create new';
                    } else if($tenderingNewData[$tend]['CreateNewOrSelectListFlg'] == 2) {
                        $CreateNewOrSelectListFlgNew='select from pre defined list';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' <span class="diff">||</span> New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
                }
                if($tenderingOldData[$tend]['NORTenderingPreConditionID'] != $tenderingNewData[$tend]['NORTenderingPreConditionID']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Name of condition : '.$tenderingOldData[$tend]['TenderingCode'].' <span class="diff">||</span> New Name of condition : '.$tenderingNewData[$tend]['TenderingCode'];
                }
                if($tenderingOldData[$tend]['NewNORTenderingPreCondition'] != $tenderingNewData[$tend]['NewNORTenderingPreCondition']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Name of condition : '.$tenderingOldData[$tend]['NewNORTenderingPreCondition'].' <span class="diff">||</span> New Name of condition : '.$tenderingNewData[$tend]['NewNORTenderingPreCondition'];
                }
                if($tenderingOldData[$tend]['StatusFlag'] != $tenderingNewData[$tend]['StatusFlag']) {
                    $StatusFlagOld='In Active';
                    if($tenderingOldData[$tend]['StatusFlag']==1) {
                        $StatusFlagOld='Active';
                    }
                    $StatusFlagNew='In Active';
                    if($tenderingNewData[$tend]['StatusFlag']==1) {
                        $StatusFlagNew='Active';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Activate : '.$StatusFlagOld.' <span class="diff">||</span> New Activate : '.$StatusFlagNew;
                }
            }
                
            $this->db->select('udt_AU_ResponseDpNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
            $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions');
            $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseDpNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
            $this->db->where('ResponseDisportID', $cd->RCD_ID);
            $this->db->order_by('APCID', 'asc');
            $qry3=$this->db->get();
            $acceptOldData=$qry3->result_array();
                
            $this->db->select('udt_AU_ResponseDpNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
            $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions');
            $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseDpNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
            $this->db->where('ResponseDisportID', $newDisports->RCD_ID);
            $this->db->order_by('APCID', 'asc');
            $qry31=$this->db->get();
            $acceptNewData=$qry31->result_array();
                
            for($accept=0; $accept < count($acceptOldData) && $accept < count($acceptNewData); $accept++){
                if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] != $acceptNewData[$accept]['CreateNewOrSelectListFlg']) {
                    $CreateNewOrSelectListFlgOld='';
                    if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] == 1) {
                        $CreateNewOrSelectListFlgOld='create new';
                    } else if($acceptOldData[$accept]['CreateNewOrSelectListFlg'] == 2) {
                        $CreateNewOrSelectListFlgOld='select from pre defined list';
                    }
                        
                    $CreateNewOrSelectListFlgNew='';
                    if($acceptNewData[$accept]['CreateNewOrSelectListFlg'] == 1) {
                        $CreateNewOrSelectListFlgNew='create new';
                    } else if($acceptNewData[$accept]['CreateNewOrSelectListFlg'] == 2) {
                        $CreateNewOrSelectListFlgNew='select from pre defined list';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' <span class="diff">||</span> New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
                }
                if($acceptOldData[$accept]['NORAcceptancePreConditionID'] != $acceptNewData[$accept]['NORAcceptancePreConditionID']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Name of condition : '.$acceptOldData[$accept]['AcceptanceCode'].' <span class="diff">||</span> New Name of condition : '.$acceptNewData[$accept]['AcceptanceCode'];
                }
                if($acceptOldData[$accept]['NewNORAcceptancePreCondition'] != $acceptNewData[$accept]['NewNORAcceptancePreCondition']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Name of condition : '.$acceptOldData[$accept]['NewNORAcceptancePreCondition'].' <span class="diff">||</span> New Name of condition : '.$acceptNewData[$accept]['NewNORAcceptancePreCondition'];
                }
                if($acceptOldData[$accept]['StatusFlag'] != $acceptNewData[$accept]['StatusFlag']) {
                    $StatusFlagOld='In Active';
                    if($acceptOldData[$accept]['StatusFlag']==1) {
                        $StatusFlagOld='Active';
                    }
                    $StatusFlagNew='In Active';
                    if($acceptNewData[$accept]['StatusFlag']==1) {
                        $StatusFlagNew='Active';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Activate : '.$StatusFlagOld.' <span class="diff">||</span> New Activate : '.$StatusFlagNew;
                }
            }
                    
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpOfficeHours');
            $this->db->where('ResponseDisportID', $cd->RCD_ID);
            $this->db->order_by('OHID', 'asc');
            $qry4=$this->db->get();
            $officeOldData=$qry4->result_array();
                
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpOfficeHours');
            $this->db->where('ResponseDisportID', $newDisports->RCD_ID);
            $this->db->order_by('OHID', 'asc');
            $qry4=$this->db->get();
            $officeNewData=$qry4->result_array();
                
            for($office=0; $office < count($officeOldData) && $office < count($officeNewData); $office++){
                if($officeOldData[$office]['DateFrom'] != $officeNewData[$office]['DateFrom']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Day (From) : '.$officeOldData[$office]['DateFrom'].' <span class="diff">||</span> New Day (From) : '.$officeNewData[$office]['DateFrom'];
                }
                if($officeOldData[$office]['DateTo'] != $officeNewData[$office]['DateTo']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Day (To) : '.$officeOldData[$office]['DateTo'].' <span class="diff">||</span> New Day (To) : '.$officeNewData[$office]['DateTo'];
                }
                if($officeOldData[$office]['TimeFrom'] != $officeNewData[$office]['TimeFrom']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Time (From) : '.$officeOldData[$office]['TimeFrom'].' <span class="diff">||</span> New Time (From) : '.$officeNewData[$office]['TimeFrom'];
                }
                if($officeOldData[$office]['TimeTo'] != $officeNewData[$office]['TimeTo']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Time (To) : '.$officeOldData[$office]['TimeTo'].' <span class="diff">||</span> New Time (To) : '.$officeNewData[$office]['TimeTo'];
                }
                if($officeOldData[$office]['IsLastEntry'] != $officeNewData[$office]['IsLastEntry']) {
                    $IsLastEntryOld='No';
                    if($officeOldData[$office]['IsLastEntry']==1) {
                        $IsLastEntryOld='Yes';
                    }
                    $IsLastEntryNew='No';
                    if($officeNewData[$office]['IsLastEntry']==1) {
                        $IsLastEntryNew='Yes';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Is last entry : '.$IsLastEntryOld.' <span class="diff">||</span> New Is last entry : '.$IsLastEntryNew;
                }
            }
                
            $this->db->select('udt_AU_ResponseDpLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
            $this->db->from('udt_AU_ResponseDpLaytimeCommencement');
            $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseDpLaytimeCommencement.TurnTime', 'left');
            $this->db->where('ResponseDisportID', $cd->RCD_ID);
            $this->db->order_by('LCID', 'asc');
            $qry5=$this->db->get();
            $laytimeOldData=$qry5->result_array();
                
            $this->db->select('udt_AU_ResponseDpLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
            $this->db->from('udt_AU_ResponseDpLaytimeCommencement');
            $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseDpLaytimeCommencement.TurnTime', 'left');
            $this->db->where('ResponseDisportID', $newDisports->RCD_ID);
            $this->db->order_by('LCID', 'asc');
            $qry51=$this->db->get();
            $laytimeNewData=$qry51->result_array();
                
            for($lay=0; $lay < count($laytimeOldData) && $lay < count($laytimeNewData); $lay++){
                if($laytimeOldData[$lay]['DayFrom'] != $laytimeNewData[$lay]['DayFrom']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Day (From) : '.$laytimeOldData[$lay]['DayFrom'].' <span class="diff">||</span> New Day (From) : '.$laytimeNewData[$lay]['DayFrom'];
                }
                if($laytimeOldData[$lay]['DayTo'] != $laytimeNewData[$lay]['DayTo']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Day (To) : '.$laytimeOldData[$lay]['DayTo'].' <span class="diff">||</span> New Day (To) : '.$laytimeNewData[$lay]['DayTo'];
                }
                if($laytimeOldData[$lay]['TimeFrom'] != $laytimeNewData[$lay]['TimeFrom']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Time (From) : '.$laytimeOldData[$lay]['TimeFrom'].' <span class="diff">||</span> New Time (From) : '.$laytimeNewData[$lay]['TimeFrom'];
                }
                if($laytimeOldData[$lay]['TimeTo'] != $laytimeNewData[$lay]['TimeTo']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Time (To) : '.$laytimeOldData[$lay]['TimeTo'].' <span class="diff">||</span> New Time (To) : '.$laytimeNewData[$lay]['TimeTo'];
                }
                if($laytimeOldData[$lay]['TurnTime'] != $laytimeNewData[$lay]['TurnTime']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Turn time applies : '.$laytimeOldData[$lay]['LaytimeCode'].' <span class="diff">||</span> New Turn time applies : '.$laytimeNewData[$lay]['LaytimeCode'];
                }
                if($laytimeOldData[$lay]['TurnTimeExpire'] != $laytimeNewData[$lay]['TurnTimeExpire']) {
                    $TurnTimeExpireOld='';
                    if($laytimeOldData[$lay]['TurnTimeExpire'] == 1) {
                        $TurnTimeExpireOld='During office hours';
                    } else if($laytimeOldData[$lay]['TurnTimeExpire'] == 2) {
                        $TurnTimeExpireOld='After office hours';
                    }
                    $TurnTimeExpireNew='';
                    if($laytimeNewData[$lay]['TurnTimeExpire'] == 1) {
                        $TurnTimeExpireNew='During office hours';
                    } else if($laytimeNewData[$lay]['TurnTimeExpire'] == 2) {
                        $TurnTimeExpireNew='After office hours';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Turn time expires : '.$TurnTimeExpireOld.' <span class="diff">||</span> New Turn time expires : '.$TurnTimeExpireNew;
                }
                if($laytimeOldData[$lay]['LaytimeCommenceAt'] != $laytimeNewData[$lay]['LaytimeCommenceAt']) {
                    $LaytimeCommenceAtOld='';
                    if($laytimeOldData[$lay]['LaytimeCommenceAt'] == 1) {
                        $LaytimeCommenceAtOld='At expiry of turn time';
                    } else if($laytimeOldData[$lay]['LaytimeCommenceAt'] == 2) {
                        $LaytimeCommenceAtOld='At specified hour';
                    }
                    $LaytimeCommenceAtNew='';
                    if($laytimeNewData[$lay]['LaytimeCommenceAt'] == 1) {
                        $LaytimeCommenceAtNew='At expiry of turn time';
                    } else if($laytimeNewData[$lay]['LaytimeCommenceAt'] == 2) {
                        $LaytimeCommenceAtNew='At specified hour';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old laytime commences at : '.$LaytimeCommenceAtOld.' <span class="diff">||</span> New laytime commences at : '.$LaytimeCommenceAtNew;
                }
                if($laytimeOldData[$lay]['LaytimeCommenceAtHour'] != $laytimeNewData[$lay]['LaytimeCommenceAtHour']) {
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Turn time expires : '.$laytimeOldData[$lay]['LaytimeCommenceAtHour'].' <span class="diff">||</span> New Turn time expires : '.$laytimeNewData[$lay]['LaytimeCommenceAtHour'];
                }
                if($laytimeOldData[$lay]['SelectDay'] != $laytimeNewData[$lay]['SelectDay']) {
                    $OldSelectDay='';
                    if($laytimeOldData[$lay]['SelectDay']==1) {
                        $OldSelectDay='Same Day';
                    } else if($laytimeOldData[$lay]['SelectDay']==2) {
                        $OldSelectDay='New Working Day';
                    }
                    $NewSelectDay='';
                    if($laytimeNewData[$lay]['SelectDay']==1) {
                        $NewSelectDay='Same Day';
                    } else if($laytimeNewData[$lay]['SelectDay']==2) {
                        $NewSelectDay='New Working Day';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Select day : '.$OldSelectDay.' <span class="diff">||</span> New Select day : '.$NewSelectDay;
                }
                if($laytimeOldData[$lay]['TimeCountsIfOnDemurrage'] != $laytimeNewData[$lay]['TimeCountsIfOnDemurrage']) {
                    $OldTimeCountsIfOnDemurrage='No';
                    if($laytimeOldData[$lay]['TimeCountsIfOnDemurrage']==1) {
                        $OldTimeCountsIfOnDemurrage='Yes';
                    }
                    $NewTimeCountsIfOnDemurrage='No';
                    if($laytimeNewData[$lay]['TimeCountsIfOnDemurrage']==1) {
                        $NewTimeCountsIfOnDemurrage='Yes';
                    }
                    $section='<br><br><B>Loadport</B><br>';
                    $html .='<br>Old Time counts if on Demurrage : '.$OldTimeCountsIfOnDemurrage.' <span class="diff">||</span> New Time counts if on Demurrage : '.$NewTimeCountsIfOnDemurrage;
                }
            }
                
                
                
                
                
                
                
                
                
        }
    }
        
     $totalhtml .=$section;
     $totalhtml .=$html;
     $section='';
     $html='';
        
    if($olddata->BACFlag != $newdata->BACFlag) {
        $section='<br><br><b>BAC</b><br>';
        if($olddata->BACFlag=='1') {
            $oldBACFlag='Yes';
        }else if($olddata->BACFlag=='0') {
            $oldBACFlag='No';
        }
        if($newdata->BACFlag=='1') {
            $newBACFlag='Yes';
        }else if($newdata->BACFlag=='0') {
            $newBACFlag='No';
        } 
        $html .='<br>Old Brokerage / Add Comm: '.$oldBACFlag.' <span class="diff">||</span> New Brokerage / Add Comm: '.$newBACFlag;
    }
        
     $this->db->select('*');
     $this->db->from('udt_AU_BACResponse');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->where('TransactionType', 'Brokerage');
     $query1=$this->db->get();
     $oldbroker=$query1->row();
        
     $this->db->select('*');
     $this->db->from('udt_AU_BACResponse');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->where('TransactionType', 'Brokerage');
     $query2=$this->db->get();
     $newbroker=$query2->row();

    if($oldbroker->PayingEntityType != $newbroker->PayingEntityType) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Brokerage Paying Entity Type: '.$oldbroker->PayingEntityType.' <span class="diff">||</span> New Brokerage Paying Entity Type: '.$newbroker->PayingEntityType;
    }
        
    if($oldbroker->ReceivingEntityType != $newbroker->ReceivingEntityType) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Brokerage Receiving Entity Type: '.$oldbroker->ReceivingEntityType.' <span class="diff">||</span> New Brokerage Receiving Entity Type: '.$newbroker->ReceivingEntityType;
    }
        
    if($oldbroker->ReceivingEntityName != $newbroker->ReceivingEntityName) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Brokerage Receiving Entity: '.$oldbroker->ReceivingEntityName.' <span class="diff">||</span> New Brokerage Receiving Entity: '.$newbroker->ReceivingEntityName;
    }
        
    if($oldbroker->PayableAs != $newbroker->PayableAs) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Brokerage payable as: '.$oldbroker->PayableAs.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PayableAs;
        if($newbroker->PayableAs=='Percentage') {
            if($oldbroker->PercentageOnFreight != $newbroker->PercentageOnFreight) {
                 $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnFreight.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnFreight;
            }
                
            if($oldbroker->PercentageOnDeadFreight != $newbroker->PercentageOnDeadFreight) {
                $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnDeadFreight.'  New Brokerage payable as: '.$newbroker->PercentageOnDeadFreight;
            }
                
            if($oldbroker->PercentageOnDemmurage != $newbroker->PercentageOnDemmurage) {
                $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnDemmurage.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnDemmurage;
            }
                
            if($oldbroker->PercentageOnOverage != $newbroker->PercentageOnOverage) {
                $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnOverage.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnOverage;
            }
        }
        if($newbroker->PayableAs=='LumpSum') {
            if($oldbroker->LumpsumPayable != $newbroker->LumpsumPayable) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Brokerage payable as: '.number_format($oldbroker->LumpsumPayable).' <span class="diff">||</span> New Brokerage payable as: '.number_format($newbroker->LumpsumPayable);
            }
        }
        if($newbroker->PayableAs=='RatePerTonne') {
            if($oldbroker->RatePerTonnePayable != $newbroker->RatePerTonnePayable) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Brokerage payable as: '.$oldbroker->RatePerTonnePayable.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->RatePerTonnePayable;
            }
        }
    } else {
        if($oldbroker->PercentageOnFreight != $newbroker->PercentageOnFreight) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnFreight.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnFreight;
        }
            
        if($oldbroker->PercentageOnDeadFreight != $newbroker->PercentageOnDeadFreight) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnDeadFreight.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnDeadFreight;
        }
            
        if($oldbroker->PercentageOnDemmurage != $newbroker->PercentageOnDemmurage) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnDemmurage.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnDemmurage;
        }
            
        if($oldbroker->PercentageOnOverage != $newbroker->PercentageOnOverage) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.$oldbroker->PercentageOnOverage.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->PercentageOnOverage;
        }
            
        if($oldbroker->LumpsumPayable != $newbroker->LumpsumPayable) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.number_format($oldbroker->LumpsumPayable).' <span class="diff">||</span> New Brokerage payable as: '.number_format($newbroker->LumpsumPayable);
        }
            
        if($oldbroker->RatePerTonnePayable != $newbroker->RatePerTonnePayable) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Brokerage payable as: '.$oldbroker->RatePerTonnePayable.' <span class="diff">||</span> New Brokerage payable as: '.$newbroker->RatePerTonnePayable;
        }
    }
        
     $this->db->select('*');
     $this->db->from('udt_AU_BACResponse');
     $this->db->where('ResponseCargoID', $olddata->ResponseCargoID);
     $this->db->where('TransactionType', 'Commision');
     $query3=$this->db->get();
     $oldaddcom=$query3->row();
        
     $this->db->select('*');
     $this->db->from('udt_AU_BACResponse');
     $this->db->where('ResponseCargoID', $newdata->ResponseCargoID);
     $this->db->where('TransactionType', 'Commision');
     $query4=$this->db->get();
     $newaddcom=$query4->row();
        
    if($oldaddcom->PayingEntityType != $newaddcom->PayingEntityType) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Add Comm Paying Entity Type: '.$oldaddcom->PayingEntityType.' <span class="diff">||</span> New Add Comm Paying Entity Type: '.$newaddcom->PayingEntityType;
    }
        
    if($oldaddcom->ReceivingEntityType != $newaddcom->ReceivingEntityType) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Add Comm Receiving Entity Type: '.$oldaddcom->ReceivingEntityType.' <span class="diff">||</span> New Add Comm Receiving Entity Type: '.$newaddcom->ReceivingEntityType;
    }
        
    if($oldaddcom->ReceivingEntityName != $newaddcom->ReceivingEntityName) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Add Comm Receiving Entity: '.$oldaddcom->ReceivingEntityName.' <span class="diff">||</span> New Add Comm Receiving Entity: '.$newaddcom->ReceivingEntityName;
    }
        
    if($oldaddcom->PayableAs != $newaddcom->PayableAs) {
        $section='<br><br><B>BAC</B><br>';
        $html .='<br>Old Add Comm payable as: '.$oldaddcom->PayableAs.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PayableAs;
        if($newaddcom->PayableAs=='Percentage') {
            if($oldaddcom->PercentageOnFreight != $newaddcom->PercentageOnFreight) {
                $section='<br><br><B>BAC</B><br>';
                 $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnFreight.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnFreight;
            }
                
            if($oldaddcom->PercentageOnDeadFreight != $newaddcom->PercentageOnDeadFreight) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnDeadFreight.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnDeadFreight;
            }
                
            if($oldaddcom->PercentageOnDemmurage != $newaddcom->PercentageOnDemmurage) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnDemmurage.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnDemmurage;
            }
                
            if($oldbrooldaddcomker->PercentageOnOverage != $newaddcom->PercentageOnOverage) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnOverage.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnOverage;
            }
        }
        if($newaddcom->PayableAs=='LumpSum') {
            if($oldaddcom->LumpsumPayable != $newaddcom->LumpsumPayable) {
                $section='<br><br><B>BAC</B><br>';
                $html .='<br>Old Add Comm payable as: '.number_format($oldaddcom->LumpsumPayable).' <span class="diff">||</span> New Add Comm payable as: '.number_format($newaddcom->LumpsumPayable);
            }
        }
        if($newaddcom->PayableAs=='RatePerTonne') {
            $section='<br><br><B>BAC</B><br>';
            if($oldaddcom->RatePerTonnePayable != $newaddcom->RatePerTonnePayable) {
                $html .='<br>Old Add Comm payable as: '.$oldaddcom->RatePerTonnePayable.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->RatePerTonnePayable;
            }
        }
    } else {
        if($oldaddcom->PercentageOnFreight != $newaddcom->PercentageOnFreight) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnFreight.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnFreight;
        }
            
        if($oldaddcom->PercentageOnDeadFreight != $newaddcom->PercentageOnDeadFreight) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnDeadFreight.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnDeadFreight;
        }
            
        if($oldaddcom->PercentageOnDemmurage != $newaddcom->PercentageOnDemmurage) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnDemmurage.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnDemmurage;
        }
            
        if($oldaddcom->PercentageOnOverage != $newaddcom->PercentageOnOverage) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.$oldaddcom->PercentageOnOverage.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->PercentageOnOverage;
        }
            
        if($oldaddcom->LumpsumPayable != $newaddcom->LumpsumPayable) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.number_format($oldaddcom->LumpsumPayable).' <span class="diff">||</span> New Add Comm payable as: '.number_format($newaddcom->LumpsumPayable);
        }
            
        if($oldaddcom->RatePerTonnePayable != $newaddcom->RatePerTonnePayable) {
            $section='<br><br><B>BAC</B><br>';
            $html .='<br>Old Add Comm payable as: '.$oldaddcom->RatePerTonnePayable.' <span class="diff">||</span> New Add Comm payable as: '.$newaddcom->RatePerTonnePayable;
        }
    }
        
     $totalhtml .=$section;
     $totalhtml .=$html;
     $section='';
     $html='';
    if($totalhtml !='') {
        $totalhtml=substr($totalhtml, 8);
    }
             
     $this->db->where('udt_AU_ResponseCargo.ResponseCargoID', $newdata->ResponseCargoID);
     return $this->db->update('udt_AU_ResponseCargo', array('ContentChange'=>$totalhtml));
}
    
public function uploadResponseImage()
{
    $res=0;
    extract($this->input->post());
    if(isset($_FILES['upload_file'])) {
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
            
            
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseCargo');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->order_by('ResponseCargoID', 'DESC');
        $cargoquery=$this->db->get();
        $CargoResponse=$cargoquery->row()->CargoVersion;
            
        $Version=explode(' ', $CargoResponse);
        $nextVersion=$Version[1];
            
        for($i=0;$i<count($document['name']);$i++){
            $ext=getExtension($document['name'][$i]);
            if($document['name'][$i]) {
                if($ext=='pdf' || $ext=='PDF') {
                    $nar=explode(".", $document['type'][$i]);
                    $type=end($nar);
                    $file=rand(1, 999999).$document['name'][$i];
                    $tmp=$document['tmp_name'][$i];
                    $filesize=$document['size'][$i];
                    
                    $actual_image_name = 'TopMarx/'.$file;
                    $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    //$qids=explode("_",$ids);
            
                    $file_data = array(
                    'CoCode'=>C_COCODE,
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'LineNum'=>$LineNum,
                    'ResponseSection'=>'cargo',
                    'FileName'=> $file,
                    'DocumentType'=>$document_type[$i],
                    'DocumentTitle'=>$document_title[$i],
                    'FileType'=>$type,
                    'FileSizeKB'=>round($filesize/1024),
                    'UserID'=>$UserID, 
                    'UserDate'=>Date('Y-m-d H:i:s'), 
                    'FlagBit'=>'1',
                    'CargoVersion'=>$nextVersion
                    );
                    $res=$this->db->insert('udt_AU_ResponseDocuments', $file_data);
                }
            }
        }
    }
        
    return $res;
}
    
public function getResponseDocument()
{
    $ResponseID=$this->input->post('ResponseID');
    $linenum=$this->input->post('linenum');
    $version=$this->input->post('version');
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'cargo');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function deleteResponseDocument()
{
    $ResponseDocumentID=$this->input->post('id');
    $this->db->where('ResponseDocumentID', $ResponseDocumentID);
    return $this->db->update('udt_AU_ResponseDocuments', array('FlagBit'=>'0'));
}
    
public function checkResponseCargoDocuments($linenum,$auctionid)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $auctionid);
    $this->db->where('LineNum', $linenum);
    $this->db->where('AuctionSection', 'cp');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getResponseDocumentUser()
{
    $ResponseID=$this->input->post('ResponseID');
    $linenum=$this->input->post('linenum');
    $version=$this->input->post('version');
    $userid=$this->input->post('userid');

    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'cargo');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->result();
}
    
public function view_response_document()
{
    $id=$this->input->get('id');
    $this->db->select('FileName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->where('ResponseDocumentID', $id);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->FileName;
}
    
public function countResponseDocumentUser($linenum,$ResponseID,$version,$userid)
{
        
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'cargo');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->num_rows();
}
    
public function getResponseVesselData()
{
    $auctionID=$this->input->post('auctionID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_ResponseVessel.*,um.FirstName,um.LastName');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->join('udt_UserMaster as um', 'um.ID=udt_AU_ResponseVessel.RecordAddBy');
    $this->db->where('udt_AU_ResponseVessel.AuctionID', $auctionID);
    $this->db->where('udt_AU_ResponseVessel.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_ResponseVessel.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseFreightData()
{
    $auctionID=$this->input->post('auctionID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_FreightResponse.*,um.FirstName,um.LastName');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->join('udt_UserMaster as um', 'um.ID=udt_AU_FreightResponse.RecordAddBy');
    $this->db->where('udt_AU_FreightResponse.AuctionID', $auctionID);
    $this->db->where('udt_AU_FreightResponse.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_FreightResponse.LineNum', 'ASC');
    $this->db->order_by('udt_AU_FreightResponse.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseFreightFirstVersion()
{
    $auctionID=$this->input->post('auctionID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('udt_AU_Freight.AuctionID', $auctionID);
    $this->db->where('udt_AU_Freight.ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseFreightRecords($AuctionID,$ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('udt_AU_Freight.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Freight.ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function checkResponseVesselDocuments($auctionid)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $auctionid);
    $this->db->where('LineNum', '1');
    $this->db->where('AuctionSection', 'vessel');
    $this->db->where('ToDisplayInvitee', '1');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function checkResponseFreightDocuments($auctionid)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $auctionid);
    $this->db->where('LineNum', '1');
    $this->db->where('AuctionSection', 'quote');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function countResponseDocumentVessel($ResponseID,$version,$userid)
{
        
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'vessel');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->num_rows();
}
    
public function countResponseDocumentFreight($ResponseID,$version,$userid)
{
        
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'quote');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->num_rows();
}
    
public function getResponseVesselDocument()
{
    $ResponseID=$this->input->get('ResponseID');
    $type=$this->input->get('type');
    $version1=$this->input->get('Version');
    $version=explode(' ', $version1);
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', $type);
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version[1]);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getResponseVesselDatails()
{
    if($this->input->post()) {
        $ResponseVesselID=$this->input->post('ResponseVesselID');
    }
    if($this->input->get()) {
        $ResponseVesselID=$this->input->get('ResponseVesselID');
    }
        
    $this->db->select('udt_AU_ResponseVessel.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_ResponseVessel.RecordOwner');
    $this->db->where('udt_AU_ResponseVessel.ResponseVesselID', $ResponseVesselID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getVesselDocuments()
{
    $auctionid=$this->input->post('auctionid');
    $this->db->select('udt_AUM_Documents.ToDisplay, udt_AUM_Documents.ToDisplayInvitee, udt_AUM_Documents.FileName, udt_AUM_Documents.FileSizeKB, udt_AUM_Documents.AcceptNameFlg, udt_AUM_Documents.CustomTitle, udt_AUM_Documents.DocumentType, udt_AUM_Documents.DocumentID, udt_AUM_DocumentType_Master.DocumentTypeID, udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('AuctionID', $auctionid);
    $this->db->where('LineNum', '1');
    $this->db->where('AuctionSection', 'vessel');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getVesselResponseDocumentUser()
{
    $ResponseID=$this->input->post('ResponseID');
    $version=$this->input->post('version');
    $userid=$this->input->post('userid');

    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'vessel');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseFreightByID()
{
    $FreightResponseID=$this->input->post('FreightResponseID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID= udt_AU_FreightResponse.FreightCurrency', 'left');
    $this->db->where('FreightResponseID', $FreightResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getResponseFreightInviteeIDByID($ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDifferentialDataForResponse($LineNum,$AuctionID)
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_DifferentialsResponse.*,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,bs.PortName as BsDescription, bs.Code as BsCode,rp1.PortName as Rp1Description, rp1.Code as Rp1Code,rp2.PortName as Rp2Description, rp2.Code as Rp2Code,rp3.PortName as Rp3Description, rp3.Code as Rp3Code');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_DifferentialsResponse.VesselGroupSizeID');
    $this->db->join('udt_PortMaster as bs', 'bs.ID=udt_AU_DifferentialsResponse.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as rp1', 'rp1.ID=udt_AU_DifferentialsResponse.DisportRefPort1', 'left');  
    $this->db->join('udt_PortMaster as rp2', 'rp2.ID=udt_AU_DifferentialsResponse.DisportRefPort2', 'left');  
    $this->db->join('udt_PortMaster as rp3', 'rp3.ID=udt_AU_DifferentialsResponse.DisportRefPort3', 'left');  
    $this->db->where('udt_AU_DifferentialsResponse.LineNum', $LineNum);
    $this->db->where('udt_AU_DifferentialsResponse.AuctionID', $AuctionID);
    $this->db->where('udt_AU_DifferentialsResponse.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_DifferentialsResponse.DifferentialID', 'desc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function addNewFreightResponse($line_num)
{
    extract($this->input->post());
        
    $FreightCurrrency='';
    $FreightRateUOM='';
    $FreightTce='';
    $FreightTceDifferential='';
    $FreightLumpsumMax1=0;
    $FreightLow1=0;
    $FreightHigh1=0;
        
    $this->db->trans_start();
    if($FreightBasis==1) {
        $FreightCurrrency=$FreightCurrrencyR;
        $FreightRateUOM=$FreightRateUOMR;
        $FreightTce=$FreightTceR;
        $FreightTceDifferential=$FreightTceDifferentialR;
    }else if($FreightBasis==2) {
        $FreightCurrrency=$freight_currrencyLS;
        $FreightRateUOM=0;
        $FreightTce=0;
        $FreightTceDifferential=0;
        $FreightRate=0;
        $FreightLumpsumMax1=$FreightLumpsumMax;
    }else if($FreightBasis==3) {
        $FreightCurrrency=$freight_currrencyHL;
        $FreightRateUOM=$FreightRateUOMHL;
        $FreightTce=$FreightTceHL;
        $FreightTceDifferential=$FreightTceDifferentialHL;
        $FreightLow1=$FreightLow;
        $FreightHigh1=$FreightHigh;
        $FreightRate=0;
    }
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query_fright=$this->db->get();
    $freight=$query_fright->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $querycounter=$this->db->get();
    $resultcounter=$querycounter->row()->FreightCounter+1;
        
    $data_h= array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$AuctionID,
                'FreightBasis'=>$FreightBasis,
                'FreightRate'=>$FreightRate,
                'FreightCurrrency'=>$FreightCurrrency,
                'FreightRateUOM'=>$FreightRateUOM,
                'FreightTce'=>$FreightTce,
                'FreightTceDifferential'=>$FreightTceDifferential,
                'FreightLumpsumMax'=>$FreightLumpsumMax1,
                'FreightLow'=>$FreightLow1,
                'FreightHigh'=>$FreightHigh1,
                'Demurrage'=>$Demurrage,
                'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$DespatchHalfDemurrage,
                'DifferentialInvitee'=>'',
                'CommentsByInvitee'=>$CommentsByInvitees,
                'ResponseStatus'=>'Inprogress',
                'Status'=>'2',
                'UserID'=>$freight->UserID,
                'EntityID'=>$freight->EntityID,
                'ReleaseDate'=>$freight->ReleaseDate,
                'conf1'=>$freight->conf1,
                'conf2'=>$freight->conf2,
                'conf3'=>$freight->conf3,
                'UserName'=>$UserName,
                'UserID1'=>$UserLogin,
                'status_UserName'=>$freight->status_UserName,
                'status_UserID'=>$freight->status_UserID,
                'status_UserDate'=>$freight->status_UserDate,
                'ReadyToSubmit'=>$freight->ReadyToSubmit,
                'TentativeStatus'=>$freight->TentativeStatus,
                'UserDate'=>date('Y-m-d H:i:s'),
                'ResponseID'=>$ResponseID,
                'FreightCounter'=>$resultcounter,
                'RowStatus'=>'2'
                );
        
    $r=$this->db->insert('udt_AUM_Freight_H', $data_h); 
        
    $this->db->update('udt_AU_Counter', array('FreightCounter'=>$resultcounter));
        
    $data= array(
                'FreightBasis'=>$FreightBasis,
                'FreightRate'=>$FreightRate,
                'FreightCurrrency'=>$FreightCurrrency,
                'FreightRateUOM'=>$FreightRateUOM,
                'FreightTce'=>$FreightTce,
                'FreightTceDifferential'=>$FreightTceDifferential,
                'FreightLumpsumMax'=>$FreightLumpsumMax1,
                'FreightLow'=>$FreightLow1,
                'FreightHigh'=>$FreightHigh1,
                'Demurrage'=>$Demurrage,
                'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$DespatchHalfDemurrage,
                'DifferentialInvitee'=>'',
                'CommentsByInvitee'=>$CommentsByInvitees,
                'ResponseStatus'=>'Inprogress',
                'Status'=>2,
                'change_status'=>1,
                'UserDate'=>date('Y-m-d H:i:s')
                );
    if($InvFlg==1 && $ConfirmationFlg==1) {
        $data['conf1']='';
        $data['conf2']='';
        $data['conf3']='';
        $data['conf4']='';
    } else if($InvFlg==1 && $ConfirmationFlg==0) {
        $data['conf1']='on';
        $data['conf2']='on';
        $data['conf3']='on';
        $data['conf4']='on';
    } 
        
    $this->db->where('ResponseID', $ResponseID);
    $r1=$this->db->update('udt_AUM_Freight', $data); 
        
    $dataAss= array(
                'FreightBasis'=>$FreightBasis,
                'FreightRate'=>$FreightRate,
                'FreightCurrrency'=>$FreightCurrrency,
                'FreightRateUOM'=>$FreightRateUOM,
                'FreightTce'=>$FreightTce,
                'FreightTceDifferential'=>$FreightTceDifferential,
                'FreightLumpsumMax'=>$FreightLumpsumMax1,
                'FreightLow'=>$FreightLow1,
                'FreightHigh'=>$FreightHigh1,
                'Demurrage'=>$Demurrage,
                'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$DespatchHalfDemurrage,
                'DifferentialInvitee'=>'',
                'CommentsByInvitee'=>$CommentsByInvitees,
                'ResponseStatus'=>'Inprogress',
                'Status'=>2,
                'UserDate'=>date('Y-m-d H:i:s')
                );
    if($InvFlg==1 && $ConfirmationFlg==1) {
        $dataAss['conf1']='';
        $dataAss['conf2']='';
        $dataAss['conf3']='';
        $dataAss['conf4']='';
    } else if($InvFlg==1 && $ConfirmationFlg==0) {
        $dataAss['conf1']='on';
        $dataAss['conf2']='on';
        $dataAss['conf3']='on';
        $dataAss['conf4']='on';
    }     
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $line_num);
    $r2=$this->db->update('udt_AU_FreightResponseAssessment', $dataAss); 
        
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $line_num);
    $this->db->order_by('FreightResponseID', 'Desc');
    $freightquery=$this->db->get();
    $freightrw=$freightquery->row();
    $FreightResponse=$freightrw->FreightVersion;
        
    $Version=explode(' ', $FreightResponse);
    $nextVersion=$Version[1]+0.01;
    $newVersion='Version '.$nextVersion;
        
    $data_r=array(
                'CoCode'=>C_COCODE,
                'FreightVersion'=>$newVersion,
                'LineNum'=>$line_num,
                'AuctionID'=>$AuctionID,
                'RecordOwner'=>$RecordOwner,
                'ResponseID'=>$ResponseID,
                'EntityID'=>$freightrw->EntityID,
                'FreightBasis'=>$FreightBasis,
                'FreightRate'=>$FreightRate,
                'FreightCurrency'=>$FreightCurrrency,
                'FreightRateUOM'=>$FreightRateUOM,
                'FreightTce'=>$FreightTce,
                'FreightTceDifferential'=>$FreightTceDifferential,
                'FreightLumpsumMax'=>$FreightLumpsumMax1,
                'FreightLow'=>$FreightLow1,
                'FreightHigh'=>$FreightHigh1,
                'Demurrage'=>$Demurrage,
                'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$DespatchHalfDemurrage,
                'DifferentialInvitee'=>'',
                'CommentsByInvitees'=>$CommentsByInvitees,
                'CommentsByAuctioner'=>$Commentbyauctioner, 
                'CommentForInvitees'=>$CommentsForInvitees, 
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'ContentChange'=>'',
                'UserDate'=>date('Y-m-d H:i:s')
                );
        
    $ret = $this->db->insert('udt_AU_FreightResponse', $data_r);
        
    //--------------------------------------
        
    $this->db->select('*');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $line_num);
    $this->db->order_by('DifferentialID', 'Desc');
    $query=$this->db->get();
    $dff_ress_data=$query->row();
            
    $data=array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$dff_ress_data->AuctionID,
                'ResponseID'=>$dff_ress_data->ResponseID,
                'Version'=>$newVersion,
                'InviteeID'=>$dff_ress_data->InviteeID,
                'LineNum'=>$dff_ress_data->LineNum,
                'VesselGroupSizeID'=>$dff_ress_data->VesselGroupSizeID,
                'BaseLoadPort'=>$dff_ress_data->BaseLoadPort,
                'FreightReferenceFlg'=>$dff_ress_data->FreightReferenceFlg,
                'DisportRefPort1'=>$dff_ress_data->DisportRefPort1,
                'DisportRefPort2'=>$dff_ress_data->DisportRefPort2,
                'DisportRefPort3'=>$dff_ress_data->DisportRefPort3,
                'CargoOwnerComment'=>$dff_ress_data->CargoOwnerComment,
                'InviteeComment'=>$dff_ress_data->InviteeComment,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
    $this->db->insert('udt_AU_DifferentialsResponse', $data);
            
    $this->db->select('DifferentialID');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->order_by('DifferentialID', 'Desc');
    $query=$this->db->get();
    $NewDifferentialID=$query->row()->DifferentialID;
        
        
    for($i=0;$i<count($DifferentialDisport);$i++) {
        $data=array(
        'DifferentialID'=>$NewDifferentialID,
        'AuctionID'=>$AuctionID,
        'ResponseID'=>$ResponseID,
        'RefDisportID'=>$DifferentialDisport[$i],
        'LpDpFlg'=>$disport_flg[$i],
        'LoadDischargeRate'=>$LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$LoadDischangeUnit[$i],
        'DifferentialFlg'=>$DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$DifferentialInvitee[$i],
        'GroupNo'=>$group_no[$i],
        'PrimaryPortFlg'=>$primary_port_flag[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_DifferentialRefDisportsResponse', $data);
    }
    //--------------------------------------------
    $data_frt=array(
                'FreightBasis'=>$FreightBasis,
                'FreightRate'=>$FreightRate,
                'FreightCurrency'=>$FreightCurrrency,
                'FreightRateUOM'=>$FreightRateUOM,
                'FreightTce'=>$FreightTce,
                'FreightTceDifferential'=>$FreightTceDifferential,
                'FreightLumpsumMax'=>$FreightLumpsumMax1,
                'FreightLow'=>$FreightLow1,
                'FreightHigh'=>$FreightHigh1,
                'Demurrage'=>$Demurrage,
                'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$DespatchHalfDemurrage,
                'DifferentialInvitee'=>'',
                'CommentsByInvitees'=>$CommentsByInvitees,
                'CommentsByAuctioner'=>$Commentbyauctioner, 
                'CommentForInvitees'=>$CommentsForInvitees,
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $line_num);
    $r3=$this->db->update('udt_AU_Freight', $data_frt);
        
    $this->db->trans_complete();
        
    if($ret) {
        $this->db->select('FreightResponseID');
        $this->db->from('udt_AU_FreightResponse');
        $this->db->order_by('FreightResponseID', 'desc');
        $query=$this->db->get();
        return $query->row()->FreightResponseID;
    } else {
        return 0;
    }
}
    
public function sendInprogressMessage()
{
    $AuctionID=$this->input->post('AuctionID');
    $ResponseID=$this->input->post('ResponseID');
    $UserID=$this->input->post('UserID');
    $sendFlg=0;
    $this->db->select('EntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID');
    $this->db->where('udt_AUM_Freight.ResponseID', $ResponseID);
    $iquery=$this->db->get();
    $InviteeEntity=$iquery->row();
        
    $EntityName=$InviteeEntity->EntityName;
    $EntityID=$InviteeEntity->EntityID;
        
    $this->db->select('OwnerEntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
    $query=$this->db->get();
    $Owner=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '13');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
    $this->db->order_by('MessageID', 'desc');
    $query2=$this->db->get();
    $msgData1=$query2->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
    $this->db->order_by('MessageID', 'desc');
    $query=$this->db->get();
    $msgData2=$query->row();
        
    $this->db->select('MessageDetailID');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Messsage_Details.FromUserID');
    $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $msgData1->MessageID);
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.ResponseID', $ResponseID);
    $this->db->where('udt_UserMaster.EntityID', $EntityID);
    $this->db->order_by('MessageDetailID', 'desc');
    $query=$this->db->get();
    $submitOwner1=$query->row();
        
    $this->db->select('MessageDetailID');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Messsage_Details.FromUserID');
    $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $msgData2->MessageID);
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.ResponseID', $ResponseID);
    $this->db->where('udt_UserMaster.EntityID', $EntityID);
    $this->db->order_by('MessageDetailID', 'desc');
    $query=$this->db->get();
    $inprogress1=$query->row();

    if($submitOwner1) {
        if($submitOwner1->MessageDetailID > $inprogress1->MessageDetailID) {
            $sendFlg=1;
        }
    } else {
        if(!$InprogressOwner1) {
            $sendFlg=1;
        }
    }
        
    if($sendFlg==1) {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query=$this->db->get();
        $msgData=$query->result();
            
        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (in progress)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>'',    
            'MessageMasterID'=>$md->MessageID,    
            'UserID'=>$md->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query=$this->db->get();
        $msgData=$query->result();

        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (in progress)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>'',    
            'MessageMasterID'=>$md->MessageID,    
            'UserID'=>$md->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
}
    
public function sendInprogressMessage1($AuctionID,$ResponseID,$UserID)
{
    $sendFlg=0;
    $this->db->select('EntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID');
    $this->db->where('udt_AUM_Freight.ResponseID', $ResponseID);
    $iquery=$this->db->get();
    $InviteeEntity=$iquery->row();
        
    $EntityName=$InviteeEntity->EntityName;
    $EntityID=$InviteeEntity->EntityID;
        
    $this->db->select('OwnerEntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
    $query=$this->db->get();
    $Owner=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '13');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
    $this->db->order_by('MessageID', 'desc');
    $query2=$this->db->get();
    $msgData1=$query2->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
    $this->db->order_by('MessageID', 'desc');
    $query=$this->db->get();
    $msgData2=$query->row();
        
    $this->db->select('MessageDetailID');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Messsage_Details.FromUserID');
    $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $msgData1->MessageID);
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.ResponseID', $ResponseID);
    $this->db->where('udt_UserMaster.EntityID', $EntityID);
    $this->db->order_by('MessageDetailID', 'desc');
    $query=$this->db->get();
    $submitOwner1=$query->row();
        
    $this->db->select('MessageDetailID');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Messsage_Details.FromUserID');
    $this->db->where('udt_AU_Messsage_Details.MessageMasterID', $msgData2->MessageID);
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.ResponseID', $ResponseID);
    $this->db->where('udt_UserMaster.EntityID', $EntityID);
    $this->db->order_by('MessageDetailID', 'desc');
    $query=$this->db->get();
    $inprogress1=$query->row();
        
    if($submitOwner1) {
        if($submitOwner1->MessageDetailID > $inprogress1->MessageDetailID) {
            $sendFlg=1;
        }
    } else {
        if(!$InprogressOwner1) {
            $sendFlg=1;
        }
    }
        
    if($sendFlg==1) {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query=$this->db->get();
        $msgData=$query->result();
            
        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (in progress)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>'',    
            'MessageMasterID'=>$md->MessageID,    
            'UserID'=>$md->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $Owner->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
        $query=$this->db->get();
        $msgData=$query->result();
            
        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Cargo Set Up (Quotes) (in progress)',    
            'Page'=>'Cargo Set Up (Quotes)',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>'',    
            'MessageMasterID'=>$md->MessageID,    
            'UserID'=>$md->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
}
    
public function getResponseFreightLatestData($line_num)
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID= udt_AU_FreightResponse.FreightCurrency', 'left');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $line_num);
    $this->db->order_by('FreightResponseID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getResponseFreightChangeContent($olddata,$newdata)
{
    extract($this->input->post());
    $html='';
    $section='';
    $totalhtml='';
    if($olddata->FreightBasis != $newdata->FreightBasis) {
        if($olddata->FreightBasis==1) {
            $oldFreightBasis='Rate $/mt';
        }else if($olddata->FreightBasis==2) {
            $oldFreightBasis='Lumpsum';
        }else if($olddata->FreightBasis==3) {
            $oldFreightBasis='High - Low ($/mt)';
        }else{
            $oldFreightBasis='';
        }
            
        if($newdata->FreightBasis==1) {
                $newFreightBasis='Rate $/mt';
        }else if($newdata->FreightBasis==2) {
                $newFreightBasis='Lumpsum';
        }else if($newdata->FreightBasis==3) {
            $newFreightBasis='High - Low ($/mt)';
        }else{
            $newFreightBasis='';
        }
            $section='<br><br><B>Freight</B><br>';
            $html .='<br> Old freight basis :'.$oldFreightBasis.' <span class="diff">||</span> New freight basis : '.$newFreightBasis;
    }
        
    if($olddata->FreightRate != $newdata->FreightRate) {
        $section='<br><br><B>Freight</B><br>';
        $html .='<br>Old freight rate : '.$olddata->FreightRate.' <span class="diff">||</span> New freight rate : '.$newdata->FreightRate;
    }
        
    if($olddata->FreightCurrency != $newdata->FreightCurrency) {
        $section='<br><br><B>Freight</B><br>';
        $html .='<br>Old freight currrency : '.$olddata->Code.' <span class="diff">||</span> New freight currrency : '.$newdata->Code;
    }
    if($olddata->FreightRateUOM != $newdata->FreightRateUOM) {
        $section='<br><br><B>Freight</B><br>';
        if($olddata->FreightRateUOM=='1') {
            $OldFreightRateUOM='UnitCode : MT || Description : Metric Tonnes';
        }else if($olddata->FreightRateUOM=='2') {
            $OldFreightRateUOM='UnitCode : LT || Description : Long Tonnes';
        }else if($olddata->FreightRateUOM=='3') {
            $OldFreightRateUOM='UnitCode : PMT || Description : Per metric tonne';
        }else if($olddata->FreightRateUOM=='4') {
            $OldFreightRateUOM='UnitCode : PLT || Description : Per long ton';
        }else if($olddata->FreightRateUOM=='5') {
                $OldFreightRateUOM='UnitCode : WWD || Description : Weather Working Day';
        }else{
            $OldFreightRateUOM='';
        }
        if($newdata->FreightRateUOM=='1') {
            $newFreightRateUOM='UnitCode : MT || Description : Metric Tonnes';
        }else if($newdata->FreightRateUOM=='2') {
            $newFreightRateUOM='UnitCode : LT || Description : Long Tonnes';
        }else if($newdata->FreightRateUOM=='3') {
            $newFreightRateUOM='UnitCode : PMT || Description : Per metric tonne';
        }else if($newdata->FreightRateUOM=='4') {
            $newFreightRateUOM='UnitCode : PLT || Description : Per long ton';
        }else if($newdata->FreightRateUOM=='5') {
            $newFreightRateUOM='UnitCode : WWD || Description : Weather Working Day';
        }else{
            $newFreightRateUOM='';
        }
            
            $html .='<br>Old freight rate (UOM) : '.$OldFreightRateUOM.' <span class="diff">||</span> New freight rate (UOM) : '.$newFreightRateUOM;
            
    }
    if($olddata->FreightTce != $newdata->FreightTce) {
        $section='<br><br><B>Freight</B><br>';
            
        $html .='<br>Old TCE (usd/day) for quoted freight : '.number_format($olddata->FreightTce).' <span class="diff">||</span> New TCE (usd/day) for quoted freight : '.number_format($newdata->FreightTce);
            
    }
    if($olddata->FreightTceDifferential != $newdata->FreightTceDifferential) {
        $section='<br><br><B>Freight</B><br>';
        $html .='<br>Old TCE (usd/day) for freight differential : '.number_format($olddata->FreightTce).' <span class="diff">||</span> New TCE (usd/day) for freight differential : '.number_format($newdata->FreightTce);
            
    }
    if($olddata->TceRateBasis) {
        if($olddata->TceRateBasis != $newdata->TceRateBasis) {
            $section='<br><br><B>Freight</B><br>';
            $html .='<br>Old TCE rate basis : '.$olddata->TceRateBasis.' <span class="diff">||</span> New TCE rate basis : '.$newdata->TceRateBasis;
                
        }
    }
    if($olddata->FreightLow) {
        if($olddata->FreightLow != $newdata->FreightLow) {
            $section='<br><br><B>Freight</B><br>';
            $html .='<br>Old freight rate from (Low) : '.$olddata->FreightLow.' <span class="diff">||</span> New freight rate from (Low) : '.$newdata->FreightLow;
                
        }
    }
    if($olddata->FreightHigh) {
        if($olddata->FreightHigh != $newdata->FreightHigh) {
            $section='<br><br><B>Freight</B><br>';
            $html .='<br>Old freight rate to (High) : '.$olddata->FreightHigh.' <span class="diff">||</span> New freight rate to (High) : '.$newdata->FreightHigh;
                
        }
    }
    $totalhtml .=$section;
    $totalhtml .=$html;
    $section='';
    $html='';
    /* 
    $oldDiffInvArr=explode(",",$olddata->DifferentialInvitee);
    $newDiffInvArr=explode(",",$newdata->DifferentialInvitee);
        
    $cnt=count($newDiffInvArr);
        
    for($i=0; $i<$cnt; $i++){
    if($oldDiffInvArr[$i] != $newDiffInvArr[$i]){
                $section='<br><br><B>Differential</B><br>';
                $html .='<br>Old differential (invitee) : '.$oldDiffInvArr[$i].' <span class="diff">||</span> New differential (invitee) : '.$newDiffInvArr[$i];
            
    }
    }
    */
    for($i=0; $i<count($DifferentialInvitee); $i++){
        if($DifferentialInviteeOld[$i] != $DifferentialInvitee[$i]) {
            $section='<br><br><B>Differential</B><br>';
            $html .='<br>Old differential (invitee) : '.$DifferentialInviteeOld[$i].' <span class="diff">||</span> New differential (invitee) : '.$DifferentialInvitee[$i];
        }
    } 
        
    $totalhtml .=$section;
    $totalhtml .=$html;
    $section='';
    $html='';
        
    if($olddata->Demurrage != $newdata->Demurrage) {
        $section='<br><br><B>Demurrage-Despatch</B><br>';
        $html .='<br>Old demurrage (US $/day) : '.number_format($olddata->Demurrage).' <span class="diff">||</span> New demurrage (US $/day) : '.number_format($newdata->Demurrage);
            
    }
        
    if($olddata->DespatchDemurrageFlag != $newdata->DespatchDemurrageFlag) {
        if($olddata->DespatchDemurrageFlag==1) {
            $oldDespatchDemurrageFlag='Yes';
        }else if($olddata->DespatchDemurrageFlag==2) {
            $oldDespatchDemurrageFlag='No';
        }else{
            $oldDespatchDemurrageFlag='';
        }
        if($newdata->DespatchDemurrageFlag==1) {
            $newDespatchDemurrageFlag='Yes';
        }else if($newdata->DespatchDemurrageFlag==2) {
            $newDespatchDemurrageFlag='No';
        }else{
                $newDespatchDemurrageFlag='';
        }
            $section='<br><br><B>Demurrage-Despatch</B><br>';
            $html .='<br>Old despatch - (half demurrage) ? : '.$oldDespatchDemurrageFlag.' <span class="diff">||</span> New despatch - (half demurrage) ? : '.$newDespatchDemurrageFlag;
    }
        
    if($olddata->DespatchHalfDemurrage != $newdata->DespatchHalfDemurrage) {
        $section='<br><br><B>Demurrage-Despatch</B><br>';
        $html .='<br>Old despatch - (half demurrage) : '.number_format($olddata->DespatchHalfDemurrage).' <span class="diff">||</span> New despatch - (half demurrage) : '.number_format($newdata->DespatchHalfDemurrage);
    }
        
    $html .='';
    $totalhtml .=$section;
    $totalhtml .=$html;
    $section='';
    $html='';
            
    $totalhtml=substr($totalhtml, 8);        
        
    $this->db->where('udt_AU_FreightResponse.FreightResponseID', $newdata->FreightResponseID);
    return $this->db->update('udt_AU_FreightResponse', array('ContentChange'=>$totalhtml));
    
}
    
public function uploadResponseQuoteImage($line_num)
{
    $res=0;
    extract($this->input->post());
        
    if(isset($_FILES['upload_file'])) {
        $document=$_FILES['upload_file'];
    
        $this->db->select('*');
        $this->db->from('udt_AU_FreightResponse');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->order_by('FreightResponseID', 'Desc');
        $freightquery=$this->db->get();
        $FreightResponse=$freightquery->row()->FreightVersion;
            
        $newversion=explode(' ', $FreightResponse);
            
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
            if($document['name'][$i]) {
                if($ext=='pdf' || $ext=='PDF') {
                    $nar=explode(".", $document['type'][$i]);
                    $type=end($nar);
                    $file=rand(1, 999999).$document['name'][$i];
                    $tmp=$document['tmp_name'][$i];
                    $filesize=$document['size'][$i];
                        
                    $actual_image_name = 'TopMarx/'.$file;
                    $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    $qids=explode("_", $ids);
                
                    $file_data = array(
                     'CoCode'=>C_COCODE,
                     'AuctionID'=>$AuctionID,
                     'ResponseID'=>$ResponseID,
                     'LineNum'=>$line_num,
                     'ResponseSection'=>'quote',
                     'FileName'=> $file,
                     'DocumentType'=>$document_type[$i],
                     'DocumentTitle'=>$document_title[$i],
                     'FileType'=>$type,
                     'FileSizeKB'=>round($filesize/1024),
                     'UserID'=>$UserID, 
                     'UserDate'=>Date('Y-m-d H:i:s'), 
                     'FlagBit'=>'1',
                     'CargoVersion'=>$newversion[1]
                    );
                    $res=$this->db->insert('udt_AU_ResponseDocuments', $file_data);
                }
            }
        }
    }
        
    return $res;
}
    
public function getResponseQuoteDocument()
{
    $ResponseID=$this->input->post('ResponseID');
    $linenum=$this->input->post('linenum');
    $version=$this->input->post('version');
    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'quote');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getResponseFreightDatails()
{
    if($this->input->post()) {
        $FreightResponseID=$this->input->post('FreightResponseID');
    }
    if($this->input->get()) {
        $FreightResponseID=$this->input->get('FreightResponseID');
    }
    $this->db->select('udt_AU_FreightResponse.*,udt_EntityMaster.EntityName,udt_CurrencyMaster.Code');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_FreightResponse.RecordOwner');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID= udt_AU_FreightResponse.FreightCurrency');
    $this->db->where('udt_AU_FreightResponse.FreightResponseID', $FreightResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getResponseQuoteDatails($LineNum,$ResponseID,$Version,$AuctionID)
{
    $this->db->select('udt_AU_DifferentialsResponse.*,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,bs.PortName as BsDescription, bs.Code as BsCode,rp1.PortName as Rp1Description, rp1.Code as Rp1Code,rp2.PortName as Rp2Description, rp2.Code as Rp2Code,rp3.PortName as Rp3Description, rp3.Code as Rp3Code');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_DifferentialsResponse.VesselGroupSizeID');
    $this->db->join('udt_PortMaster as bs', 'bs.ID=udt_AU_DifferentialsResponse.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as rp1', 'rp1.ID=udt_AU_DifferentialsResponse.DisportRefPort1', 'left');  
    $this->db->join('udt_PortMaster as rp2', 'rp2.ID=udt_AU_DifferentialsResponse.DisportRefPort2', 'left');  
    $this->db->join('udt_PortMaster as rp3', 'rp3.ID=udt_AU_DifferentialsResponse.DisportRefPort3', 'left');  
    $this->db->where('udt_AU_DifferentialsResponse.LineNum', $LineNum);
    $this->db->where('udt_AU_DifferentialsResponse.AuctionID', $AuctionID);
    $this->db->where('udt_AU_DifferentialsResponse.ResponseID', $ResponseID);
    $this->db->where('udt_AU_DifferentialsResponse.Version', $Version);
    $this->db->order_by('udt_AU_DifferentialsResponse.DifferentialID', 'desc');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getResponseQuoteDatailsLatest($LineNum,$ResponseID,$AuctionID)
{
    $this->db->select('udt_AU_DifferentialsResponse.*,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,bs.PortName as BsDescription, bs.Code as BsCode,rp1.PortName as Rp1Description, rp1.Code as Rp1Code,rp2.PortName as Rp2Description, rp2.Code as Rp2Code,rp3.PortName as Rp3Description, rp3.Code as Rp3Code');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_DifferentialsResponse.VesselGroupSizeID');
    $this->db->join('udt_PortMaster as bs', 'bs.ID=udt_AU_DifferentialsResponse.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as rp1', 'rp1.ID=udt_AU_DifferentialsResponse.DisportRefPort1', 'left');  
    $this->db->join('udt_PortMaster as rp2', 'rp2.ID=udt_AU_DifferentialsResponse.DisportRefPort2', 'left');  
    $this->db->join('udt_PortMaster as rp3', 'rp3.ID=udt_AU_DifferentialsResponse.DisportRefPort3', 'left');  
    $this->db->where('udt_AU_DifferentialsResponse.LineNum', $LineNum);
    $this->db->where('udt_AU_DifferentialsResponse.AuctionID', $AuctionID);
    $this->db->where('udt_AU_DifferentialsResponse.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_DifferentialsResponse.DifferentialID', 'desc');
    $query=$this->db->get();
    return $query->row();
    
}
 
   
public function getResponseFreightDocuments()
{
    $ResponseID=$this->input->post('ResponseID');
    $linenum=$this->input->post('linenum');
    $version=$this->input->post('version');
    $userid=$this->input->post('userid');

    $this->db->select('udt_AU_ResponseDocuments.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_ResponseDocuments');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseDocuments.UserID', 'left');
    $this->db->where('udt_AU_ResponseDocuments.ResponseID', $ResponseID);
    $this->db->where('udt_AU_ResponseDocuments.LineNum', $linenum);
    $this->db->where('udt_AU_ResponseDocuments.UserID', $userid);
    $this->db->where('udt_AU_ResponseDocuments.FlagBit', '1');
    $this->db->where('udt_AU_ResponseDocuments.ResponseSection', 'quote');
    $this->db->where('udt_AU_ResponseDocuments.CargoVersion <=', $version);
        
    $query=$this->db->get();
    return $query->result();
}
 
public function getLatestFreightQuotes($ResponseID)
{
    $this->db->select('udt_AU_Freight.*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('udt_AU_Freight.ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getLatestVesselName($ResponseID)
{
    $this->db->select('udt_AU_ResponseVessel.*');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('udt_AU_ResponseVessel.ResponseID', $ResponseID);
    $this->db->order_by('ResponseVesselID', 'Desc');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getVesselImo($AuctionID,$ResponseID)
{
    $this->db->select('IMO,VesselName');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('VesselVersion', 'DESC');
    $query=$this->db->get();
    return $query->row()->IMO;
}
    
public function getInviteeVesselByResponseId()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('Udt_AU_ResponseVessel.*,CONVERT(VARCHAR(10),RatingDate,105) as RatingDateDF');
    $this->db->from('Udt_AU_ResponseVessel');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('ResponseVesselID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_shipowner_user_details()
{
    $ResponseID=$this->input->get('ResponseID');
    $type=$this->input->get('type');
    $UserID=$this->input->get('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('ResponseVesselID', 'desc');
    $vessel_qry=$this->db->get();
    $vessel_row=$vessel_qry->row();
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('udt_AU_ResponseBrokerUsers');
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ResponseVesselID', $vessel_row->ResponseVesselID);
    $this->db->delete('udt_AU_ResponseBrokerUsers_H');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('udt_AUM_Freight.ResponseID', $ResponseID);
    $query=$this->db->get();
    $rslt=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_BrokerSigningAuthority');
    $this->db->where('udt_AUM_BrokerSigningAuthority.OwnerEntity', $rslt->EntityID);
    $this->db->where('udt_AUM_BrokerSigningAuthority.Status', 1);
    $query1=$this->db->get();
    $sa_row=$query1->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_BrokerSigningUsers');
    $this->db->where('udt_AUM_BrokerSigningUsers.BSA_ID', $sa_row->BSA_ID);
    $query1=$this->db->get();
    $br_result=$query1->result();
    $cnt=count($br_result);
    $ShipOwner='';
        
    if(($type==2 || $type==1) && $cnt>0 ) {
        $ShipOwner=$sa_row->ShipOwnerEntity;
        foreach($br_result as $rw){
            $br_data=array(
            'ResponseID'=>$ResponseID,
            'BSU_ID'=>$rw->BSU_ID,
            'BrokerSigningType'=>$rw->BrokerSigningType,
            'SigningUserEntity'=>$rw->SigningUserEntity,
            'SigningUserID'=>$rw->SigningUserID,
            'Status'=>1,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_ResponseBrokerUsers', $br_data);
        }
    } else if($type==1 && $cnt==0) {
        $ShipOwner=$rslt->EntityID;
        $this->db->select('udt_UserMaster.ID,udt_UserMaster.EntityID,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.SignDigitallyFixtureFlg,udt_UserMaster.SignDigitallyCPFlg');
        $this->db->from('udt_UserMaster');
        $this->db->where('udt_UserMaster.EntityID', $rslt->EntityID);
        $query2=$this->db->get();
        $user_data=$query2->result();
        foreach($user_data as $rw){
            if($rw->SignDigitallyFixtureFlg==1) {
                $br_data=array(
                'ResponseID'=>$ResponseID,
                'BSU_ID'=>0,
                'BrokerSigningType'=>1,
                'SigningUserEntity'=>$rw->EntityID,
                'SigningUserID'=>$rw->ID,
                'Status'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseBrokerUsers', $br_data);
            }  
            if($rw->SignDigitallyCPFlg==1) {
                $br_data=array(
                'ResponseID'=>$ResponseID,
                'BSU_ID'=>0,
                'BrokerSigningType'=>2,
                'SigningUserEntity'=>$rw->EntityID,
                'SigningUserID'=>$rw->ID,
                'Status'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseBrokerUsers', $br_data);
            }
        }
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseBrokerUsers');
    $this->db->where('ResponseID', $ResponseID);
    $rbu_qry1=$this->db->get();
    $new_rslt=$rbu_qry1->result();
        
    foreach($new_rslt as $nrw){
        $data=array(
        'ResponseID'=>$ResponseID,
        'ResponseVesselID'=>$vessel_row->ResponseVesselID,
        'BSU_ID'=>$nrw->BSU_ID,
        'BrokerSigningType'=>$nrw->BrokerSigningType,
        'SigningUserEntity'=>$nrw->SigningUserEntity,
        'SigningUserID'=>$nrw->SigningUserID,
        'Status'=>$nrw->Status,
        'UserID'=>$nrw->UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_ResponseBrokerUsers_H', $data);
            
    }
        
    return $ShipOwner;
}
    
public function get_response_broker_users()
{
    $ResponseID=$this->input->get('ResponseID');
    $this->db->select('udt_AU_ResponseBrokerUsers.*, udt_EntityMaster.EntityName, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_AddressMaster.Email, udt_AddressMaster.Telephone1');
    $this->db->from('udt_AU_ResponseBrokerUsers');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_ResponseBrokerUsers.SigningUserEntity');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ResponseBrokerUsers.SigningUserID');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->where('ResponseID', $ResponseID);
    $qr=$this->db->get();
    return $qr->result();
}
    
public function changeResponseSigningUserStatus()
{
    $RBU_ID=$this->input->post('RBU_ID');
    $status=$this->input->post('status');
        
    $this->db->where('RBU_ID', $RBU_ID);
    return $this->db->update('udt_AU_ResponseBrokerUsers', array('Status'=>$status));
}
    
public function checkComfirmationDone()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query1=$this->db->get();
    return $query1->row();
}
    
public function getFreightQuoteRecords()
{
    $AuctionID=$this->input->post('auctionID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_Freight.*,E1.EntityName as OwnerName,E2.EntityName as InviteeName');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_EntityMaster as E1', 'E1.ID=udt_AU_Freight.RecordOwner');
    $this->db->join('udt_EntityMaster as E2', 'E2.ID=udt_AU_Freight.EntityID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('ResponseID', $ResponseID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getResponseCargoRecord($AuctionID,$LineNum)
{
    $this->db->select('udt_AU_Cargo.*,udt_CargoMaster.Code as Cargo_Code');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    return $qry->row();
        
}
    
public function getChateByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getSectionCommentsByResponseID($ResponseID,$LineNum,$Type)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    if($Type==1) {
        $this->db->where('Type', 'Vessel');
    } else if($Type==2) {
        $this->db->where('Type', 'Freight');
    } else if($Type==3) {
        $this->db->where('Type', 'CargoPort');
    } else if($Type==4) {
        $this->db->where('Type', 'Terms');
    }
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function changeChatNewFlg($ResponseID,$LineNum,$Type,$OwnerInvFlg)
{
    if($OwnerInvFlg==1 && $Type==1) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('VesselOwnerFlag'=>0));
    } else if($OwnerInvFlg==1 && $Type==2) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('FreightOwnerFlag'=>0));
    } else if($OwnerInvFlg==1 && $Type==3) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('CargoPortOwnerFlag'=>0));
    } else if($OwnerInvFlg==1 && $Type==4) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('TermOwnerFlag'=>0));
    } else if($OwnerInvFlg==2 && $Type==1) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('VesselInviteeFlag'=>0));
    } else if($OwnerInvFlg==2 && $Type==2) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('FreightInviteeFlag'=>0));
    } else if($OwnerInvFlg==2 && $Type==3) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('CargoPortInviteeFlag'=>0));
    } else if($OwnerInvFlg==2 && $Type==4) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_UserChatMessageAlert', array('TermInviteeFlag'=>0));
    }
}
    
public function changeChatNewFlg1($ResponseID,$OwnerInvFlg)
{
    if($OwnerInvFlg==1) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->update('udt_AU_UserChatMessageAlert', array('VesselOwnerFlag'=>0,'FreightOwnerFlag'=>0,'CargoPortOwnerFlag'=>0,'TermOwnerFlag'=>0));
    } else if($OwnerInvFlg==2) {
        $this->db->where('ResponseID', $ResponseID);
        $this->db->update('udt_AU_UserChatMessageAlert', array('VesselInviteeFlag'=>0,'FreightInviteeFlag'=>0,'CargoPortInviteeFlag'=>0,'TermInviteeFlag'=>0));
    }
}
    
public function getVesselChatsByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Type', 'Vessel');
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFreightChatsByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Type', 'Freight');
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoChatsByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Type', 'CargoPort');
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getTermChatsByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Type', 'Terms');
    $this->db->order_by('Chat_id', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getNewChatsFlgByResponseID($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChatMessageAlert');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('UCMA', 'desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getNewChatsFlagByResponseID1($ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChatMessageAlert');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('UCMA', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getInviteeIDByResponseID($ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityIDByUserID($UserID)
{
    $this->db->select('EntityID');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query=$this->db->get();
    return $query->row()->EntityID;
}
    
    
public function getResponseFreightRecord()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
        $AuctionId=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
        $AuctionId=$this->input->get('AuctionId');
    }
        
    $this->db->select('udt_AUM_Freight.*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('udt_AUM_Freight.ResponseID', $ResponseID);
    $this->db->where('udt_AUM_Freight.AuctionID', $AuctionId);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityAutoPermission()
{
    $ResponseID=$this->input->post('ResponseID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $frow=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $frow->AuctionID);
    $query=$this->db->get();
    $au_row=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $au_row->OwnerEntityID);
    $query=$this->db->get();
    $en_row=$query->row();
    return $en_row->FixtureCompleteProcess;
        
}
    
public function getResponseDisportsByResponseCargoID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseCargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, ddt1.code as ddtTermCode');
    $this->db->from('udt_AU_ResponseCargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ddt1', 'ddt1.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
    $this->db->where('ConfirmFlg', 1);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('RCD_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getResponseDisportsByResponseCargoID_H($ResponseCargoID)
{
    $UserID=$this->input->post('UserID');
        
    $this->db->where('ConfirmFlg', 2);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('UserID', $UserID);
    $this->db->update('udt_AU_ResponseCargoDisports_H', array('ConfirmFlg'=>0));
        
    $this->db->select('udt_AU_ResponseCargoDisports_H.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, ddt1.code as ddtTermCode');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports_H.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ddt1', 'ddt1.ID=udt_AU_ResponseCargoDisports_H.DischargingTerms', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ConfirmFlg', 1);
    $this->db->order_by('RCD_ID_H', 'desc');
    $qry=$this->db->get();
    return $qry->result();
        
}
    
public function getResponseDisportsByResponseCargoIDChangable($ResponseCargoID)
{
        
    $this->db->select('udt_AU_ResponseCargoDisports_H.*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ConfirmFlg !=  0');
    $this->db->order_by('DisportNo', 'asc');
    $this->db->order_by('RCD_ID_H', 'desc');
    $qry=$this->db->get();
    $disResult=$qry->result();
        
    $disports=array();
    $DisportNo='';
    foreach($disResult as $dis){
        if($DisportNo==$dis->DisportNo || $dis->ConfirmFlg==0) {
            continue;
        } else {
            $DisportNo=$dis->DisportNo;
        }
            
        if($dis->RowStatus != 3) {
            $this->db->select('udt_AU_ResponseCargoDisports_H.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, ddt1.code as ddtTermCode');
            $this->db->from('udt_AU_ResponseCargoDisports_H');
            $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports_H.DisPort', 'left');
            $this->db->join('udt_CP_LoadingDischargeTermsMaster as ddt1', 'ddt1.ID=udt_AU_ResponseCargoDisports_H.DischargingTerms', 'left');
            $this->db->where('RCD_ID_H', $dis->RCD_ID_H);
            $qry1=$this->db->get();
            $disRow1=$qry1->row();
                
            array_push($disports, $disRow1);
        }
    }
    return $disports;
        
}
    
public function saveResponseCargoDisports()
{
    $this->db->trans_start();
    extract($this->input->post());
    if($DpMaxTime=='') {
        $DpMaxTime=0;
    }
        
    $this->db->select('udt_AU_ResponseCargoDisports_H.*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ConfirmFlg !=  0');
    $this->db->order_by('DisportNo', 'desc');
    $qry=$this->db->get();
    $disResult=$qry->row();
    $newDisportNo=$disResult->DisportNo+1;
        
    $datah=array(
    'RCD_ID'=>0,
    'ResponseCargoID'=>$ResponseCargoID,
    'AuctionID'=>$AuctionID,
    'ResponseID'=>$ResponseID,
    'DisportNo'=>$newDisportNo,
    'DisPort'=>$DisPort,
    'DpArrivalStartDate'=>date('Y-m-d H:i:s', strtotime($DpArrivalStartDate)),
    'DpArrivalEndDate'=>date('Y-m-d H:i:s', strtotime($DpArrivalEndDate)),
    'DpPreferDate'=>date('Y-m-d H:i:s', strtotime($DpPreferDate)),
    'DischargingTerms'=>$DischargingTerms,
    'DischargingRateMT'=>$DischargingRateMT,
    'DischargingRateUOM'=>$DischargingRateUOM,
    'DpMaxTime'=>$DpMaxTime,
    'DpLaytimeType'=>$DpLaytimeType,
    'DpCalculationBasedOn'=>$DpCalculationBasedOn,
    'DpTurnTime'=>$DpTurnTime,
    'DpPriorUseTerms'=>$DpPriorUseTerms,
    'DpLaytimeBasedOn'=>$DpLaytimeBasedOn,
    'DpCharterType'=>$DpCharterType,
    'DpNorTendering'=>$DpNorTendering,
    'DpStevedoringTerms'=>$DpStevedoringTerms,
    'ExpectedDpDelayDay'=>$ExpectedDpDelayDay,
    'ExpectedDpDelayHour'=>$ExpectedDpDelayHour,
    'DpExceptedPeriodFlg'=>$DpExceptedPeriodEventFlg,
    'DpNORTenderingPreConditionFlg'=>$DpNORTenderingPreConditionFlg,
    'DpNORAcceptancePreConditionFlg'=>$DpNORAcceptancePreConditionFlg,
    'DpOfficeHoursFlg'=>$DpOfficeHoursFlg,
    'DpLaytimeCommencementFlg'=>$DpLayTimeCommence,
    'RowStatus'=>1,
    'ConfirmFlg'=>2,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_ResponseCargoDisports_H', $datah);
        
    if($ret) {
        $this->db->select('udt_AU_ResponseCargoDisports_H.*');
        $this->db->from('udt_AU_ResponseCargoDisports_H');
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('RCD_ID_H', 'desc');
        $qry=$this->db->get();
        $disRow=$qry->row();
            
        $DpExceptedPeriodEvent=explode("__~__", $AllExceptedPeriodData);
        $DpLaytimeCountOnDemurrage=explode("__~__", $AllLaytimeCountOnDemurrageData);
        $DpLaytimeCountUsedFlg=explode("__~__", $AllLaytimeCountUsedData);
        $DpTimeCounting=explode("__~__", $AllTimeCountingData);
        $DpExceptedPeriodComment=explode("__~__", $AllExceptedPeriodCommentData);
            
        $DpNewSelectTenderingFlg=explode("__~__", $AllNewSelectTenderingData);
        $DpTenderingNameOfCondition=explode("__~__", $AllTenderingNameOfConditionData);
        $DpTenderingActiveFlg=explode("__~__", $AllTenderingActiveFlgData);
        $DpNORTenderingPreConditionComment=explode("__~__", $AllTenderingPreConditionCommentData);
            
        $DpNewSelectAcceptanceFlg=explode("__~__", $AllNewSelectAcceptanceData);
        $DpAcceptanceNameOfCondition=explode("__~__", $AllAcceptanceNameOfConditionData);
        $DpAcceptanceActiveFlg=explode("__~__", $AllAcceptanceActiveFlgData);
        $DpNORAcceptancePreConditionComment=explode("__~__", $AllAcceptancePreConditionCommentData);
            
        $DpDayFrom=explode("__~__", $AllDpDayFromData);
        $DpDayTo=explode("__~__", $AllDpDayToData);
        $DpTimeFrom=explode("__~__", $AllDpTimeFromData);
        $DpTimeTo=explode("__~__", $AllDpTimeToData);
        $IsDpLastEntry=explode("__~__", $AllDpLastEntryData);
            
        $DpLayTiimeDayFrom=explode("__~__", $AllLayTiimeDayFromData);
        $DpLayTiimeDayTo=explode("__~__", $AllLayTiimeDayToData);
        $DpLaytimeTimeFrom=explode("__~__", $AllLaytimeTimeFromData);
        $DpLaytimeTimeTo=explode("__~__", $AllLaytimeTimeToData);
        $DpTurnTimeApplies=explode("__~__", $AllTurnTimeAppliesData);
        $DpTurnTimeExpires=explode("__~__", $AllTurnTimeExpiresData);
        $DpLaytimeCommencesAt=explode("__~__", $AllLaytimeCommencesAtData);
        $DpLaytimeCommencesAtHours=explode("__~__", $AllLaytimeCommencesAtHoursData);
        $DpSelectDay=explode("__~__", $AllSelectDayData);
        $DpTimeCountsIfOnDemurrage=explode("__~__", $AllTimeCountsIfOnDemurrageData);
            
        if($DpExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($DpExceptedPeriodEvent); $i++){
                if($DpExceptedPeriodEvent[$i]=='BLANK') {
                    $EventID='';
                } else {
                    $EventID=$DpExceptedPeriodEvent[$i];
                }
                if($DpLaytimeCountOnDemurrage[$i]=='BLANK') {
                    $LaytimeCountsOnDemurrageFlg='';
                } else {
                    $LaytimeCountsOnDemurrageFlg=$DpLaytimeCountOnDemurrage[$i];
                }
                if($DpLaytimeCountUsedFlg[$i]=='BLANK') {
                    $LaytimeCountsFlg='';
                } else {
                            $LaytimeCountsFlg=$DpLaytimeCountUsedFlg[$i];
                }
                if($DpTimeCounting[$i]=='BLANK') {
                        $TimeCountingFlg='';
                } else {
                    $TimeCountingFlg=$DpTimeCounting[$i];
                }
                if($DpExceptedPeriodComment[$i]=='BLANK') {
                    $ExceptedPeriodComment='';
                } else {
                    $ExceptedPeriodComment=$DpExceptedPeriodComment[$i];
                }
                    $excepted_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'EventID'=>$EventID,
                        'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountsOnDemurrageFlg,
                        'LaytimeCountsFlg'=>$LaytimeCountsFlg,
                        'TimeCountingFlg'=>$TimeCountingFlg,
                        'ExceptedPeriodComment'=>$ExceptedPeriodComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpExceptedPeriods_H', $excepted_data);
            }
        }
            
        if($DpNORTenderingPreConditionFlg==1) {
            for($j=0; $j<count($DpNewSelectTenderingFlg); $j++){
                  $NORTenderingPreConditionID=0;
                  $NewNORTenderingPreCondition='';
                if($DpNewSelectTenderingFlg[$j]=='BLANK') {
                    $CreateNewOrSelectListFlg='';
                } else {
                    $CreateNewOrSelectListFlg=$DpNewSelectTenderingFlg[$j];
                    if($DpTenderingNameOfCondition[$j]=='BLANK') {
                        $NORTenderingPreConditionID=0;
                        $NewNORTenderingPreCondition='';
                    } else {
                        if($DpNewSelectTenderingFlg[$j]==1) {
                            $NewNORTenderingPreCondition=$DpTenderingNameOfCondition[$j];
                        } else if($DpNewSelectTenderingFlg[$j]==2) {
                            $NORTenderingPreConditionID=$DpTenderingNameOfCondition[$j];
                        }
                    }
                }
                if($DpTenderingActiveFlg[$j]=='BLANK') {
                    $StatusFlag='';
                } else {
                    $StatusFlag=$DpTenderingActiveFlg[$j];
                }
                if($DpNORTenderingPreConditionComment[$j]=='BLANK') {
                    $TenderingPreConditionComment='';
                } else {
                    $TenderingPreConditionComment=$DpNORTenderingPreConditionComment[$j];
                }
                    
                    $tendering_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'TenderingPreConditionComment'=>$TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpNORTenderingPreConditions_H', $tendering_data);
            }
        }
            
        if($DpNORAcceptancePreConditionFlg==1) {
            for($k=0; $k<count($DpNewSelectAcceptanceFlg); $k++){
                $NORAcceptancePreConditionID=0;
                $NewNORAcceptancePreCondition='';
                    
                if($DpNewSelectAcceptanceFlg[$k]=='BLANK') {
                    $CreateNewOrSelectListFlg='';
                } else {
                     $CreateNewOrSelectListFlg=$DpNewSelectAcceptanceFlg[$k];
                    if($DpAcceptanceNameOfCondition[$k]=='BLANK') {
                        $NORAcceptancePreConditionID=0;
                        $NewNORAcceptancePreCondition='';
                    } else {
                        if($DpNewSelectAcceptanceFlg[$k]==1) {
                            $NewNORAcceptancePreCondition=$DpAcceptanceNameOfCondition[$k];
                        } else if($DpNewSelectAcceptanceFlg[$k]==2) {
                            $NORAcceptancePreConditionID=$DpAcceptanceNameOfCondition[$k];
                        }
                    }
                }
                if($DpAcceptanceActiveFlg[$k]=='BLANK') {
                         $StatusFlag='';
                } else {
                    $StatusFlag=$DpAcceptanceActiveFlg[$k];
                }
                if($DpNORAcceptancePreConditionComment[$k]=='BLANK') {
                      $AcceptancePreConditionComment='';
                } else {
                    $AcceptancePreConditionComment=$DpNORAcceptancePreConditionComment[$k];
                }
                    
                    $acceptance_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'AcceptancePreConditionComment'=>$AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpNORAcceptancePreConditions_H', $acceptance_data);
            }
        }
            
        if($DpOfficeHoursFlg==1) {
            for($l=0; $l<count($DpDayFrom); $l++){
                if($DpDayFrom[$l]=='BLANK') {
                    $DateFrom='';
                } else {
                    $DateFrom=$DpDayFrom[$l];
                }
                if($DpDayTo[$l]=='BLANK') {
                    $DateTo='';
                } else {
                    $DateTo=$DpDayTo[$l];
                }
                if($DpTimeFrom[$l]=='BLANK') {
                    $TimeFrom='';
                } else {
                               $TimeFrom=$DpTimeFrom[$l];
                }
                if($DpTimeTo[$l]=='BLANK') {
                    $TimeTo='';
                } else {
                    $TimeTo=$DpTimeTo[$l];
                }
                if($IsDpLastEntry[$l]=='BLANK') {
                    $IsLastEntry='';
                } else {
                    $IsLastEntry=$IsDpLastEntry[$l];
                }
                    $office_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'DateFrom'=>$DateFrom,
                        'DateTo'=>$DateTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'IsLastEntry'=>$IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpOfficeHours_H', $office_data);
            }
        }
            
        if($DpLayTimeCommence==1) {
            for($m=0; $m<count($DpLayTiimeDayFrom); $m++){
                if($DpLayTiimeDayFrom[$m]=='BLANK') {
                    $DayFrom='';
                } else {
                    $DayFrom=$DpLayTiimeDayFrom[$m];
                }
                if($DpLayTiimeDayTo[$m]=='BLANK') {
                    $DayTo='';
                } else {
                    $DayTo=$DpLayTiimeDayTo[$m];
                }
                if($DpLaytimeTimeFrom[$m]=='BLANK') {
                    $TimeFrom='';
                } else {
                               $TimeFrom=$DpLaytimeTimeFrom[$m];
                }
                if($DpLaytimeTimeTo[$m]=='BLANK') {
                    $TimeTo='';
                } else {
                    $TimeTo=$DpLaytimeTimeTo[$m];
                }
                if($DpTurnTimeApplies[$m]=='BLANK') {
                    $TurnTime='';
                } else {
                    $TurnTime=$DpTurnTimeApplies[$m];
                }
                if($DpTurnTimeExpires[$m]=='BLANK') {
                    $TurnTimeExpire='';
                } else {
                    $TurnTimeExpire=$DpTurnTimeExpires[$m];
                }
                if($DpLaytimeCommencesAt[$m]=='BLANK') {
                    $LaytimeCommenceAt='';
                } else {
                    $LaytimeCommenceAt=$DpLaytimeCommencesAt[$m];
                }
                if($DpLaytimeCommencesAtHours[$m]=='BLANK') {
                    $LaytimeCommenceAtHour='';
                } else {
                    $LaytimeCommenceAtHour=$DpLaytimeCommencesAtHours[$m];
                }
                if($DpSelectDay[$m]=='BLANK') {
                    $SelectDay='';
                } else {
                    $SelectDay=$DpSelectDay[$m];
                }
                if($DpTimeCountsIfOnDemurrage[$m]=='BLANK') {
                    $TimeCountsIfOnDemurrage='';
                } else {
                    $TimeCountsIfOnDemurrage=$DpTimeCountsIfOnDemurrage[$m];
                }
                    $commence_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'DayFrom'=>$DayFrom,
                        'DayTo'=>$DayTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'TurnTime'=>$TurnTime,
                        'TurnTimeExpire'=>$TurnTimeExpire,
                        'LaytimeCommenceAt'=>$LaytimeCommenceAt,
                        'LaytimeCommenceAtHour'=>$LaytimeCommenceAtHour,
                        'SelectDay'=>$SelectDay,
                        'TimeCountsIfOnDemurrage'=>$TimeCountsIfOnDemurrage,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpLaytimeCommencement_H', $commence_data);
            }
        }
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function deleteResponseCargoDisports()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('RCD_ID_H', $RCD_ID_H);
    $qry=$this->db->get();
    $dis_row=$qry->row();
        
    $datah=array(
    'RCD_ID'=>$dis_row->RCD_ID,
    'ResponseCargoID'=>$dis_row->ResponseCargoID,
    'AuctionID'=>$dis_row->AuctionID,
    'ResponseID'=>$dis_row->ResponseID,
    'DisportNo'=>$dis_row->DisportNo,
    'DisPort'=>$dis_row->DisPort,
    'DpArrivalStartDate'=>$dis_row->DpArrivalStartDate,
    'DpArrivalEndDate'=>$dis_row->DpArrivalEndDate,
    'DpPreferDate'=>$dis_row->DpPreferDate,
    'DischargingTerms'=>$dis_row->DischargingTerms,
    'DischargingRateMT'=>$dis_row->DischargingRateMT,
    'DischargingRateUOM'=>$dis_row->DischargingRateUOM,
    'DpMaxTime'=>$dis_row->DpMaxTime,
    'DpLaytimeType'=>$dis_row->DpLaytimeType,
    'DpCalculationBasedOn'=>$dis_row->DpCalculationBasedOn,
    'DpTurnTime'=>$dis_row->DpTurnTime,
    'DpPriorUseTerms'=>$dis_row->DpPriorUseTerms,
    'DpLaytimeBasedOn'=>$dis_row->DpLaytimeBasedOn,
    'DpCharterType'=>$dis_row->DpCharterType,
    'DpNorTendering'=>$dis_row->DpNorTendering,
    'DpStevedoringTerms'=>$dis_row->DpStevedoringTerms,
    'ExpectedDpDelayDay'=>$dis_row->ExpectedDpDelayDay,
    'ExpectedDpDelayHour'=>$dis_row->ExpectedDpDelayHour,
    'DpExceptedPeriodFlg'=>$dis_row->DpExceptedPeriodFlg,
    'DpNORTenderingPreConditionFlg'=>$dis_row->DpNORTenderingPreConditionFlg,
    'DpNORAcceptancePreConditionFlg'=>$dis_row->DpNORAcceptancePreConditionFlg,
    'DpOfficeHoursFlg'=>$dis_row->DpOfficeHoursFlg,
    'DpLaytimeCommencementFlg'=>$dis_row->DpLaytimeCommencementFlg,
    'RowStatus'=>3,
    'ConfirmFlg'=>2,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_ResponseCargoDisports_H', $datah);
        
    return $ret;
}
    
public function getResponseCargoDisportsById()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseCargoDisports_H.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspCode, udt_CP_LoadingDischargeTermsMaster.Code as trmCode, udt_CP_LoadingDischargeTermsMaster.Description as trmDescription');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_ResponseCargoDisports_H.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_CP_LoadingDischargeTermsMaster.ID=udt_AU_ResponseCargoDisports_H.DischargingTerms', 'left');
    $this->db->where('RCD_ID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->row();
}
    
    
public function updateResponseCargoDisports()
{
    $this->db->trans_start();
    extract($this->input->post());
    if($DpMaxTime=='') {
        $DpMaxTime=0;
    }
        
    $this->db->select('udt_AU_ResponseCargoDisports_H.*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('RCD_ID_H', $RCD_ID_H);
    $qry=$this->db->get();
    $disResult=$qry->row();
        
    $datah=array(
    'RCD_ID'=>$disResult->RCD_ID,
    'ResponseCargoID'=>$ResponseCargoID,
    'AuctionID'=>$AuctionID,
    'ResponseID'=>$ResponseID,
    'DisportNo'=>$disResult->DisportNo,
    'DisPort'=>$DisPort,
    'DpArrivalStartDate'=>date('Y-m-d H:i:s', strtotime($DpArrivalStartDate)),
    'DpArrivalEndDate'=>date('Y-m-d H:i:s', strtotime($DpArrivalEndDate)),
    'DpPreferDate'=>date('Y-m-d H:i:s', strtotime($DpPreferDate)),
    'DischargingTerms'=>$DischargingTerms,
    'DischargingRateMT'=>$DischargingRateMT,
    'DischargingRateUOM'=>$DischargingRateUOM,
    'DpMaxTime'=>$DpMaxTime,
    'DpLaytimeType'=>$DpLaytimeType,
    'DpCalculationBasedOn'=>$DpCalculationBasedOn,
    'DpTurnTime'=>$DpTurnTime,
    'DpPriorUseTerms'=>$DpPriorUseTerms,
    'DpLaytimeBasedOn'=>$DpLaytimeBasedOn,
    'DpCharterType'=>$DpCharterType,
    'DpNorTendering'=>$DpNorTendering,
    'DpStevedoringTerms'=>$DpStevedoringTerms,
    'ExpectedDpDelayDay'=>$ExpectedDpDelayDay,
    'ExpectedDpDelayHour'=>$ExpectedDpDelayHour,
    'DpExceptedPeriodFlg'=>$DpExceptedPeriodEventFlg,
    'DpNORTenderingPreConditionFlg'=>$DpNORTenderingPreConditionFlg,
    'DpNORAcceptancePreConditionFlg'=>$DpNORAcceptancePreConditionFlg,
    'DpOfficeHoursFlg'=>$DpOfficeHoursFlg,
    'DpLaytimeCommencementFlg'=>$DpLayTimeCommence,
    'RowStatus'=>2,
    'ConfirmFlg'=>2,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_ResponseCargoDisports_H', $datah);
        
    if($ret) {
        $this->db->select('udt_AU_ResponseCargoDisports_H.*');
        $this->db->from('udt_AU_ResponseCargoDisports_H');
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('RCD_ID_H', 'desc');
        $qry=$this->db->get();
        $disRow=$qry->row();
            
        $DpExceptedPeriodEvent=explode("__~__", $AllExceptedPeriodData);
        $DpLaytimeCountOnDemurrage=explode("__~__", $AllLaytimeCountOnDemurrageData);
        $DpLaytimeCountUsedFlg=explode("__~__", $AllLaytimeCountUsedData);
        $DpTimeCounting=explode("__~__", $AllTimeCountingData);
        $DpExceptedPeriodComment=explode("__~__", $AllExceptedPeriodCommentData);
            
        $DpNewSelectTenderingFlg=explode("__~__", $AllNewSelectTenderingData);
        $DpTenderingNameOfCondition=explode("__~__", $AllTenderingNameOfConditionData);
        $DpTenderingActiveFlg=explode("__~__", $AllTenderingActiveFlgData);
        $DpNORTenderingPreConditionComment=explode("__~__", $AllTenderingPreConditionCommentData);
            
        $DpNewSelectAcceptanceFlg=explode("__~__", $AllNewSelectAcceptanceData);
        $DpAcceptanceNameOfCondition=explode("__~__", $AllAcceptanceNameOfConditionData);
        $DpAcceptanceActiveFlg=explode("__~__", $AllAcceptanceActiveFlgData);
        $DpNORAcceptancePreConditionComment=explode("__~__", $AllAcceptancePreConditionCommentData);
            
        $DpDayFrom=explode("__~__", $AllDpDayFromData);
        $DpDayTo=explode("__~__", $AllDpDayToData);
        $DpTimeFrom=explode("__~__", $AllDpTimeFromData);
        $DpTimeTo=explode("__~__", $AllDpTimeToData);
        $IsDpLastEntry=explode("__~__", $AllDpLastEntryData);
            
        $DpLayTiimeDayFrom=explode("__~__", $AllLayTiimeDayFromData);
        $DpLayTiimeDayTo=explode("__~__", $AllLayTiimeDayToData);
        $DpLaytimeTimeFrom=explode("__~__", $AllLaytimeTimeFromData);
        $DpLaytimeTimeTo=explode("__~__", $AllLaytimeTimeToData);
        $DpTurnTimeApplies=explode("__~__", $AllTurnTimeAppliesData);
        $DpTurnTimeExpires=explode("__~__", $AllTurnTimeExpiresData);
        $DpLaytimeCommencesAt=explode("__~__", $AllLaytimeCommencesAtData);
        $DpLaytimeCommencesAtHours=explode("__~__", $AllLaytimeCommencesAtHoursData);
        $DpSelectDay=explode("__~__", $AllSelectDayData);
        $DpTimeCountsIfOnDemurrage=explode("__~__", $AllTimeCountsIfOnDemurrageData);
            
        if($DpExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($DpExceptedPeriodEvent); $i++){
                if($DpExceptedPeriodEvent[$i]=='BLANK') {
                    $EventID='';
                } else {
                    $EventID=$DpExceptedPeriodEvent[$i];
                }
                if($DpLaytimeCountOnDemurrage[$i]=='BLANK') {
                    $LaytimeCountsOnDemurrageFlg='';
                } else {
                    $LaytimeCountsOnDemurrageFlg=$DpLaytimeCountOnDemurrage[$i];
                }
                if($DpLaytimeCountUsedFlg[$i]=='BLANK') {
                    $LaytimeCountsFlg='';
                } else {
                            $LaytimeCountsFlg=$DpLaytimeCountUsedFlg[$i];
                }
                if($DpTimeCounting[$i]=='BLANK') {
                        $TimeCountingFlg='';
                } else {
                    $TimeCountingFlg=$DpTimeCounting[$i];
                }
                if($DpExceptedPeriodComment[$i]=='BLANK') {
                    $ExceptedPeriodComment='';
                } else {
                    $ExceptedPeriodComment=$DpExceptedPeriodComment[$i];
                }
                    $excepted_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'EventID'=>$EventID,
                        'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountsOnDemurrageFlg,
                        'LaytimeCountsFlg'=>$LaytimeCountsFlg,
                        'TimeCountingFlg'=>$TimeCountingFlg,
                        'ExceptedPeriodComment'=>$ExceptedPeriodComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpExceptedPeriods_H', $excepted_data);
            }
        }
            
        if($DpNORTenderingPreConditionFlg==1) {
            for($j=0; $j<count($DpNewSelectTenderingFlg); $j++){
                  $NORTenderingPreConditionID=0;
                  $NewNORTenderingPreCondition='';
                if($DpNewSelectTenderingFlg[$j]=='BLANK') {
                    $CreateNewOrSelectListFlg='';
                } else {
                    $CreateNewOrSelectListFlg=$DpNewSelectTenderingFlg[$j];
                    if($DpTenderingNameOfCondition[$j]=='BLANK') {
                        $NORTenderingPreConditionID=0;
                        $NewNORTenderingPreCondition='';
                    } else {
                        if($DpNewSelectTenderingFlg[$j]==1) {
                            $NewNORTenderingPreCondition=$DpTenderingNameOfCondition[$j];
                        } else if($DpNewSelectTenderingFlg[$j]==2) {
                            $NORTenderingPreConditionID=$DpTenderingNameOfCondition[$j];
                        }
                    }
                }
                if($DpTenderingActiveFlg[$j]=='BLANK') {
                    $StatusFlag='';
                } else {
                    $StatusFlag=$DpTenderingActiveFlg[$j];
                }
                if($DpNORTenderingPreConditionComment[$j]=='BLANK') {
                    $TenderingPreConditionComment='';
                } else {
                    $TenderingPreConditionComment=$DpNORTenderingPreConditionComment[$j];
                }
                    
                    $tendering_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'TenderingPreConditionComment'=>$TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpNORTenderingPreConditions_H', $tendering_data);
            }
        }
            
        if($DpNORAcceptancePreConditionFlg==1) {
            for($k=0; $k<count($DpNewSelectAcceptanceFlg); $k++){
                $NORAcceptancePreConditionID=0;
                $NewNORAcceptancePreCondition='';
                    
                if($DpNewSelectAcceptanceFlg[$k]=='BLANK') {
                    $CreateNewOrSelectListFlg='';
                } else {
                     $CreateNewOrSelectListFlg=$DpNewSelectAcceptanceFlg[$k];
                    if($DpAcceptanceNameOfCondition[$k]=='BLANK') {
                        $NORAcceptancePreConditionID=0;
                        $NewNORAcceptancePreCondition='';
                    } else {
                        if($DpNewSelectAcceptanceFlg[$k]==1) {
                            $NewNORAcceptancePreCondition=$DpAcceptanceNameOfCondition[$k];
                        } else if($DpNewSelectAcceptanceFlg[$k]==2) {
                            $NORAcceptancePreConditionID=$DpAcceptanceNameOfCondition[$k];
                        }
                    }
                }
                if($DpAcceptanceActiveFlg[$k]=='BLANK') {
                         $StatusFlag='';
                } else {
                    $StatusFlag=$DpAcceptanceActiveFlg[$k];
                }
                if($DpNORAcceptancePreConditionComment[$k]=='BLANK') {
                      $AcceptancePreConditionComment='';
                } else {
                    $AcceptancePreConditionComment=$DpNORAcceptancePreConditionComment[$k];
                }
                    
                    $acceptance_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'AcceptancePreConditionComment'=>$AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpNORAcceptancePreConditions_H', $acceptance_data);
            }
        }
            
        if($DpOfficeHoursFlg==1) {
            for($l=0; $l<count($DpDayFrom); $l++){
                if($DpDayFrom[$l]=='BLANK') {
                    $DateFrom='';
                } else {
                    $DateFrom=$DpDayFrom[$l];
                }
                if($DpDayTo[$l]=='BLANK') {
                    $DateTo='';
                } else {
                    $DateTo=$DpDayTo[$l];
                }
                if($DpTimeFrom[$l]=='BLANK') {
                    $TimeFrom='';
                } else {
                               $TimeFrom=$DpTimeFrom[$l];
                }
                if($DpTimeTo[$l]=='BLANK') {
                    $TimeTo='';
                } else {
                    $TimeTo=$DpTimeTo[$l];
                }
                if($IsDpLastEntry[$l]=='BLANK') {
                    $IsLastEntry='';
                } else {
                    $IsLastEntry=$IsDpLastEntry[$l];
                }
                    $office_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'DateFrom'=>$DateFrom,
                        'DateTo'=>$DateTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'IsLastEntry'=>$IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpOfficeHours_H', $office_data);
            }
        }
            
        if($DpLayTimeCommence==1) {
            for($m=0; $m<count($DpLayTiimeDayFrom); $m++){
                if($DpLayTiimeDayFrom[$m]=='BLANK') {
                    $DayFrom='';
                } else {
                    $DayFrom=$DpLayTiimeDayFrom[$m];
                }
                if($DpLayTiimeDayTo[$m]=='BLANK') {
                    $DayTo='';
                } else {
                    $DayTo=$DpLayTiimeDayTo[$m];
                }
                if($DpLaytimeTimeFrom[$m]=='BLANK') {
                    $TimeFrom='';
                } else {
                               $TimeFrom=$DpLaytimeTimeFrom[$m];
                }
                if($DpLaytimeTimeTo[$m]=='BLANK') {
                    $TimeTo='';
                } else {
                    $TimeTo=$DpLaytimeTimeTo[$m];
                }
                if($DpTurnTimeApplies[$m]=='BLANK') {
                    $TurnTime='';
                } else {
                    $TurnTime=$DpTurnTimeApplies[$m];
                }
                if($DpTurnTimeExpires[$m]=='BLANK') {
                    $TurnTimeExpire='';
                } else {
                    $TurnTimeExpire=$DpTurnTimeExpires[$m];
                }
                if($DpLaytimeCommencesAt[$m]=='BLANK') {
                    $LaytimeCommenceAt='';
                } else {
                    $LaytimeCommenceAt=$DpLaytimeCommencesAt[$m];
                }
                if($DpLaytimeCommencesAtHours[$m]=='BLANK') {
                    $LaytimeCommenceAtHour='';
                } else {
                    $LaytimeCommenceAtHour=$DpLaytimeCommencesAtHours[$m];
                }
                if($DpSelectDay[$m]=='BLANK') {
                    $SelectDay='';
                } else {
                    $SelectDay=$DpSelectDay[$m];
                }
                if($DpTimeCountsIfOnDemurrage[$m]=='BLANK') {
                    $TimeCountsIfOnDemurrage='';
                } else {
                    $TimeCountsIfOnDemurrage=$DpTimeCountsIfOnDemurrage[$m];
                }
                    $commence_data=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                        'DayFrom'=>$DayFrom,
                        'DayTo'=>$DayTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'TurnTime'=>$TurnTime,
                        'TurnTimeExpire'=>$TurnTimeExpire,
                        'LaytimeCommenceAt'=>$LaytimeCommenceAt,
                        'LaytimeCommenceAtHour'=>$LaytimeCommenceAtHour,
                        'SelectDay'=>$SelectDay,
                        'TimeCountsIfOnDemurrage'=>$TimeCountsIfOnDemurrage,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseDpLaytimeCommencement_H', $commence_data);
            }
        }
    }
        
    $this->db->trans_complete();
        
    return $ret;
        
}
    
public function cloneResponseCargoDisports()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $UserID=$this->input->post('UserID');
    $ResponseCargoID=$this->input->post('ResponseCargoID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('RCD_ID_H', $RCD_ID_H);
    $qry=$this->db->get();
    $dis_row=$qry->row();
        
    $this->db->select('udt_AU_ResponseCargoDisports_H.*');
    $this->db->from('udt_AU_ResponseCargoDisports_H');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ConfirmFlg !=  0');
    $this->db->order_by('DisportNo', 'desc');
    $qry=$this->db->get();
    $disResult=$qry->row();
    $newDisportNo=$disResult->DisportNo+1;
        
    $datah=array(
    'RCD_ID'=>0,
    'ResponseCargoID'=>$dis_row->ResponseCargoID,
    'AuctionID'=>$dis_row->AuctionID,
    'ResponseID'=>$dis_row->ResponseID,
    'DisportNo'=>$newDisportNo,
    'DisPort'=>$dis_row->DisPort,
    'DpArrivalStartDate'=>$dis_row->DpArrivalStartDate,
    'DpArrivalEndDate'=>$dis_row->DpArrivalEndDate,
    'DpPreferDate'=>$dis_row->DpPreferDate,
    'DischargingTerms'=>$dis_row->DischargingTerms,
    'DischargingRateMT'=>$dis_row->DischargingRateMT,
    'DischargingRateUOM'=>$dis_row->DischargingRateUOM,
    'DpMaxTime'=>$dis_row->DpMaxTime,
    'DpLaytimeType'=>$dis_row->DpLaytimeType,
    'DpCalculationBasedOn'=>$dis_row->DpCalculationBasedOn,
    'DpTurnTime'=>$dis_row->DpTurnTime,
    'DpPriorUseTerms'=>$dis_row->DpPriorUseTerms,
    'DpLaytimeBasedOn'=>$dis_row->DpLaytimeBasedOn,
    'DpCharterType'=>$dis_row->DpCharterType,
    'DpNorTendering'=>$dis_row->DpNorTendering,
    'DpStevedoringTerms'=>$dis_row->DpStevedoringTerms,
    'ExpectedDpDelayDay'=>$dis_row->ExpectedDpDelayDay,
    'ExpectedDpDelayHour'=>$dis_row->ExpectedDpDelayHour,
    'DpExceptedPeriodFlg'=>$dis_row->DpExceptedPeriodFlg,
    'DpNORTenderingPreConditionFlg'=>$dis_row->DpNORTenderingPreConditionFlg,
    'DpNORAcceptancePreConditionFlg'=>$dis_row->DpNORAcceptancePreConditionFlg,
    'DpOfficeHoursFlg'=>$dis_row->DpOfficeHoursFlg,
    'DpLaytimeCommencementFlg'=>$dis_row->DpLaytimeCommencementFlg,
    'RowStatus'=>4,
    'ConfirmFlg'=>2,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_ResponseCargoDisports_H', $datah);
    if($ret) {
        $this->db->select('udt_AU_ResponseCargoDisports_H.*');
        $this->db->from('udt_AU_ResponseCargoDisports_H');
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('RCD_ID_H', 'desc');
        $qry=$this->db->get();
        $disRow=$qry->row();
            
            
        if($dis_row->DpExceptedPeriodFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpExceptedPeriods_H');
            $this->db->where('ResponseDisportID_H', $RCD_ID_H);
            $qry1=$this->db->get();
            $exceptedResult=$qry1->result();
                
            foreach($exceptedResult as $er){
                $excepted_data=array(
                'AuctionID'=>$er->AuctionID,
                'ResponseID'=>$er->ResponseID,
                'ResponseCargoID'=>$er->ResponseCargoID,
                'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                'EventID'=>$er->EventID,
                'LaytimeCountsOnDemurrageFlg'=>$er->LaytimeCountsOnDemurrageFlg,
                'LaytimeCountsFlg'=>$er->LaytimeCountsFlg,
                'TimeCountingFlg'=>$er->TimeCountingFlg,
                'ExceptedPeriodComment'=>$er->ExceptedPeriodComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseDpExceptedPeriods_H', $excepted_data);
            }
        }
        if($dis_row->DpNORTenderingPreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions_H');
            $this->db->where('ResponseDisportID_H', $RCD_ID_H);
            $qry2=$this->db->get();
            $tenderResult=$qry2->result();
                
            foreach($tenderResult as $tr){
                  $tendering_data=array(
                   'AuctionID'=>$tr->AuctionID,
                   'ResponseID'=>$tr->ResponseID,
                   'ResponseCargoID'=>$tr->ResponseCargoID,
                   'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                   'CreateNewOrSelectListFlg'=>$tr->CreateNewOrSelectListFlg,
                   'NORTenderingPreConditionID'=>$tr->NORTenderingPreConditionID,
                   'NewNORTenderingPreCondition'=>$tr->NewNORTenderingPreCondition,
                   'StatusFlag'=>$tr->StatusFlag,
                   'TenderingPreConditionComment'=>$tr->TenderingPreConditionComment,
                   'UserID'=>$UserID,
                   'CreatedDate'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('udt_AU_ResponseDpNORTenderingPreConditions_H', $tendering_data);
            }
        }
        if($dis_row->DpNORAcceptancePreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions_H');
            $this->db->where('ResponseDisportID_H', $RCD_ID_H);
            $qry3=$this->db->get();
            $acceptanceResult=$qry3->result();
                
            foreach($acceptanceResult as $ar){
                $acceptance_data=array(
                'AuctionID'=>$ar->AuctionID,
                'ResponseID'=>$ar->ResponseID,
                'ResponseCargoID'=>$ar->ResponseCargoID,
                'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                'CreateNewOrSelectListFlg'=>$ar->CreateNewOrSelectListFlg,
                'NORAcceptancePreConditionID'=>$ar->NORAcceptancePreConditionID,
                'NewNORAcceptancePreCondition'=>$ar->NewNORAcceptancePreCondition,
                'StatusFlag'=>$ar->StatusFlag,
                'AcceptancePreConditionComment'=>$ar->AcceptancePreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseDpNORAcceptancePreConditions_H', $acceptance_data);
            }
        }
        if($dis_row->DpOfficeHoursFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpOfficeHours_H');
            $this->db->where('ResponseDisportID_H', $RCD_ID_H);
            $qry4=$this->db->get();
            $officeResult=$qry4->result();
                
            foreach($officeResult as $or){
                $office_data=array(
                'AuctionID'=>$or->AuctionID,
                'ResponseID'=>$or->ResponseID,
                'ResponseCargoID'=>$or->ResponseCargoID,
                'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                'DateFrom'=>$or->DateFrom,
                'DateTo'=>$or->DateTo,
                'TimeFrom'=>$or->TimeFrom,
                'TimeTo'=>$or->TimeTo,
                'IsLastEntry'=>$or->IsLastEntry,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseDpOfficeHours_H', $office_data);
            }
        }
        if($dis_row->DpLaytimeCommencementFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseDpLaytimeCommencement_H');
            $this->db->where('ResponseDisportID_H', $RCD_ID_H);
            $qry5=$this->db->get();
            $laytimeResult=$qry5->result();
                
            foreach($laytimeResult as $lr){
                $commence_data=array(
                'AuctionID'=>$lr->AuctionID,
                'ResponseID'=>$lr->ResponseID,
                'ResponseCargoID'=>$lr->ResponseCargoID,
                'ResponseDisportID_H'=>$disRow->RCD_ID_H,
                'DayFrom'=>$lr->DayFrom,
                'DayTo'=>$lr->DayTo,
                'TimeFrom'=>$lr->TimeFrom,
                'TimeTo'=>$lr->TimeTo,
                'TurnTime'=>$lr->TurnTime,
                'TurnTimeExpire'=>$lr->TurnTimeExpire,
                'LaytimeCommenceAt'=>$lr->LaytimeCommenceAt,
                'LaytimeCommenceAtHour'=>$lr->LaytimeCommenceAtHour,
                'SelectDay'=>$lr->SelectDay,
                'TimeCountsIfOnDemurrage'=>$lr->TimeCountsIfOnDemurrage,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseDpLaytimeCommencement_H', $commence_data);
            }
        }
    }
        
    return $ret;
}
    
public function getResponseAssessmentNew()
{
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    $this->db->select('udt_AUM_Freight.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID', 'left');
    $this->db->where('udt_AUM_Freight.AuctionID', $AuctionID);
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFreightResponseByResponseID($ResponseID,$LineNum)
{
    $this->db->select('udt_AU_Freight.*, udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Freight.EntityID', 'left');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $query=$this->db->get();
    return $query->row();
}
    
public function getChateByResponseIDByArguments($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_UserChat');
    $this->db->where('InviteeID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('chk_flag', 1);
    $this->db->order_by('Chat_id', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getLpExpectedPeriodByResponseCargoID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
    $this->db->from('udt_AU_ResponseExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseExceptedPeriods.EventID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('EPID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpNORTenderingPreByResponseCargoID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_ResponseNORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('TPCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpNORAcceptancePreByResponseCargoID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_ResponseNORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('APCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpOfficeHoursByResponseCargoID($ResponseCargoID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseOfficeHours');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('OHID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpLaytimeCommenceByResponseCargoID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_ResponseLaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseLaytimeCommencement.TurnTime', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('LCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
    
public function getExceptedPeriodEventsByResponseDisportId()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseDpExceptedPeriods_H.*');
    $this->db->from('udt_AU_ResponseDpExceptedPeriods_H');
    $this->db->where('ResponseDisportID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getTenderingPreConditionsByResponseDisportId()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseDpNORTenderingPreConditions_H.*');
    $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions_H');
    $this->db->where('ResponseDisportID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getAcceptancePreConditionByResponseDisportId()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseDpNORAcceptancePreConditions_H.*');
    $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions_H');
    $this->db->where('ResponseDisportID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getOfficeHoursByResponseDisportId()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseDpOfficeHours_H.*');
    $this->db->from('udt_AU_ResponseDpOfficeHours_H');
    $this->db->where('ResponseDisportID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLaytimeCommencementByResponseDisportId()
{
    $RCD_ID_H=$this->input->post('RCD_ID_H');
    $this->db->select('udt_AU_ResponseDpLaytimeCommencement_H.*');
    $this->db->from('udt_AU_ResponseDpLaytimeCommencement_H');
    $this->db->where('ResponseDisportID_H', $RCD_ID_H);
    $qry=$this->db->get();
    return $qry->result();
}
    
    
public function getDpExceptedPeriodByResponseDisportID($RCD_ID)
{
    $this->db->select('udt_AU_ResponseDpExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
    $this->db->from('udt_AU_ResponseDpExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseDpExceptedPeriods.EventID', 'left');
    $this->db->where('ResponseDisportID', $RCD_ID);
    $this->db->order_by('EPID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
        
}
    
public function getDpTenderingPreConditionsByResponseDisportID($RCD_ID)
{
    $this->db->select('udt_AU_ResponseDpNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseDpNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('ResponseDisportID', $RCD_ID);
    $this->db->order_by('TPCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpAcceptancePreConditionByResponseDisportID($RCD_ID)
{
    $this->db->select('udt_AU_ResponseDpNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseDpNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('ResponseDisportID', $RCD_ID);
    $this->db->order_by('APCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpOfficeHoursByResponseDisportID($RCD_ID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseDpOfficeHours');
    $this->db->where('ResponseDisportID', $RCD_ID);
    $this->db->order_by('OHID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpLaytimeCommencementByResponseDisportID($RCD_ID)
{
    $this->db->select('udt_AU_ResponseDpLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_ResponseDpLaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseDpLaytimeCommencement.TurnTime', 'left');
    $this->db->where('ResponseDisportID', $RCD_ID);
    $this->db->order_by('LCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDifferentialReferenceResponse($DifferentialID)
{
    $this->db->select('udt_AU_DifferentialRefDisportsResponse.*,udt_PortMaster.PortName,udt_PortMaster.Code');
    $this->db->from('udt_AU_DifferentialRefDisportsResponse');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_DifferentialRefDisportsResponse.RefDisportID', 'left');
    $this->db->where('udt_AU_DifferentialRefDisportsResponse.DifferentialID', $DifferentialID);
    $this->db->order_by('udt_AU_DifferentialRefDisportsResponse.GroupNo', 'ASC');
    $this->db->order_by('udt_AU_DifferentialRefDisportsResponse.PrimaryPortFlg', 'DESC');
    $this->db->order_by('udt_AU_DifferentialRefDisportsResponse.DiffRefDisportID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getQuoteBusinessProcess($ResponseID)
{
    $this->db->select('DISTINCT BPID,process_flow_sequence,LineNum');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('process_flow_sequence', 'ASC');
    $this->db->order_by('LineNum', 'ASC');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getQuoteBusinessProcessByBPID($ResponseID,$BPID,$LineNum)
{
    $this->db->select('udt_AU_QuoteBusinessProcess.*, udt_UserMaster.FirstName, udt_UserMaster.LastName, Owner.EntityName as OwnerEntityName, Invitee.EntityName as InviteeEntityName, udt_AU_QuoteAuthorizationBroker.EstimateLumpsumFlg, udt_AU_QuoteAuthorizationBroker.EstimateTotalValue, udt_AU_QuoteAuthorizationBroker.FreightLumpsum, udt_CurrencyMaster.Code as curCode');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->join('udt_AU_QuoteAuthorizationBroker', 'udt_AU_QuoteAuthorizationBroker.QBPID=udt_AU_QuoteBusinessProcess.QBPID', 'left');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_QuoteAuthorizationBroker.FreightCurrency', 'left');
    $this->db->join('udt_EntityMaster as Owner', 'Owner.ID=udt_AU_QuoteBusinessProcess.RecordOwner', 'left');
    $this->db->join('udt_EntityMaster as Invitee', 'Invitee.ID=udt_AU_QuoteBusinessProcess.InvEntityID', 'left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_QuoteBusinessProcess.UserID', 'left');
    $this->db->where('LineNum', $LineNum);
    $this->db->where('udt_AU_QuoteBusinessProcess.TID', $ResponseID);
    $this->db->where('udt_AU_QuoteBusinessProcess.BPID', $BPID);
    $this->db->order_by('udt_AU_QuoteBusinessProcess.QBPID', 'DESC');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLatestFreightQuoteDetailsByFreightResponseID($FreightResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('FreightResponseID', $FreightResponseID);
    $qry=$this->db->get();
    $frtRow=$qry->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('ResponseID', $frtRow->ResponseID);
    $this->db->where('LineNum', $frtRow->LineNum);
    $this->db->order_by('FreightResponseID', 'desc');
    $qry=$this->db->get();
    return $qry->row();
        
}
    
public function getFreightQuoteBusinessAuthorisationEqual($ResponseID,$LineNum)
{
    $this->db->select('udt_AU_QuoteBusinessProcess.*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('udt_AU_QuoteBusinessProcess.TID', $ResponseID);
    $this->db->where('udt_AU_QuoteBusinessProcess.LineNum', $LineNum);
    $qry=$this->db->get();
    $total_row1=$qry->num_rows();
        
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    $total_row2=$qry->num_rows();
        
    if($total_row1 > 0) {
        if($total_row1==$total_row2) {
            return 1;
        } else {
            return 2;
        }
    } else {
        return 1;
    }
}
    
public function checkBusinessAuthorisationEqualFreightQuote($ResponseID,$LineNum)
{
    $this->db->select('udt_AU_QuoteBusinessProcess.*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->join('udt_AU_FreightResponse', 'udt_AU_FreightResponse.FreightResponseID=udt_AU_QuoteBusinessProcess.FreightResponseID', 'left');
    $this->db->where('udt_AU_QuoteBusinessProcess.TID', $ResponseID);
    $this->db->where('udt_AU_FreightResponse.LineNum', $LineNum);
    $qry=$this->db->get();
    $total_row1=$qry->num_rows();
        
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    $total_row2=$qry->num_rows();
        
    if($total_row1 > 0) {
        if($total_row1 < $total_row2) {
            return 1;
        } else {
            return 2;
        }
    } else {
        return 2;
    }
}
    
public function getFreightByFreightResponseID($FreightResponseID)
{
    $this->db->select('udt_AU_FreightResponse.LineNum, udt_AU_FreightResponse.ResponseID, udt_AU_FreightResponse.RecordOwner, udt_AU_FreightResponse.FreightBasis, udt_AU_FreightResponse.FreightRate, udt_AU_FreightResponse.FreightCurrency, udt_AU_FreightResponse.FreightLumpsumMax, udt_CurrencyMaster.Code as curCode');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_FreightResponse.FreightCurrency', 'left');
    $this->db->where('FreightResponseID', $FreightResponseID);
    $qry=$this->db->get();
    return $qry->row();
        
}
    
public function getQuoteAuthonticationBusinessProcessDetails($ResponseID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('QBPID', 'asc');
    $qry1=$this->db->get();
    return $qry1->row();
        
}
    
public function getUserQuoteAuthorizationDetails($AuctionID,$UserID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('BussinessType', 2);
    $this->db->where('UserList', $UserID);
    $this->db->where('Status', 1);
    $qry=$this->db->get();
    if($qry->num_rows() > 0) {
        return 1;
    } else {
        return 0;
    }
}
    
public function getUserQuoteAuthorizationPermissionLevel($UserID)
{
    $this->db->select('ApproveQuoteAuthFlg');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $this->db->where('ActiveFlag', 1);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function saveQuoteAuthorizationDetails()
{
    extract($this->input->post());
    $this->db->trans_start();
        
    if($InvFlg==1 && $ConfirmationFlg==1) {
        $data1=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'',
        'conf2'=>'',
        'conf3'=>'',
        'conf4'=>'',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else if($InvFlg==1 && $ConfirmationFlg==0) {
        $data1=array(
        'ResponseStatus'=>'Inprogress',
        'conf1'=>'on',
        'conf2'=>'on',
        'conf3'=>'on',
        'conf4'=>'on',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    } else {
        $data1=array(
        'ResponseStatus'=>'Inprogress',
        'UserDate'=>date('Y-m-d H:i:s'),
        'Status'=>2,
        'change_status'=>1
        );
    }
            
    $this->db->where('ResponseID', $ResponseID);
    $ret=$this->db->update('udt_AUM_Freight', $data1);
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->update('udt_AU_FreightResponseAssessment', $data1);

    $this->db->select('*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('QBPID', $QBPID);
    $this->db->order_by('QBPID', 'asc');
    $qry1=$this->db->get();
    $QuoteBusinessRow=$qry1->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('TID', $QuoteBusinessRow->TID);
    $this->db->where('LineNum', $QuoteBusinessRow->LineNum);
    $this->db->order_by('QBPID', 'desc');
    $qry11=$this->db->get();
    $lastRow=$qry11->row();
    $version=$lastRow->Version+0.1;
        
    $data=array(
                'BPID'=>$QuoteBusinessRow->BPID,
                'FreightResponseID'=>$FreightResponseID,
                'RecordOwner'=>$QuoteBusinessRow->RecordOwner,
                'InvEntityID'=>$QuoteBusinessRow->InvEntityID,
                'MasterID'=>$QuoteBusinessRow->MasterID,
                'TID'=>$QuoteBusinessRow->TID,
                'LineNum'=>$QuoteBusinessRow->LineNum,
                'name_of_process'=>$QuoteBusinessRow->name_of_process,
                'process_applies'=>$QuoteBusinessRow->process_applies,
                'process_flow_sequence'=>$QuoteBusinessRow->process_flow_sequence,
                'putting_freight_quote'=>$QuoteBusinessRow->putting_freight_quote,
                'submitting_freight_quote'=>$QuoteBusinessRow->submitting_freight_quote,
                'fixture_not_finalization'=>$QuoteBusinessRow->fixture_not_finalization,
                'charter_party_finalization'=>$QuoteBusinessRow->charter_party_finalization,
                'finalization_completed_by'=>$QuoteBusinessRow->finalization_completed_by,
                'message_text'=>$QuoteBusinessRow->message_text,
                'show_in_process'=>$QuoteBusinessRow->show_in_process,
                'show_in_fixture'=>$QuoteBusinessRow->show_in_fixture,
                'show_in_charter_party'=>$QuoteBusinessRow->show_in_charter_party,
                'validity'=>$QuoteBusinessRow->validity,
                'date_from'=>$QuoteBusinessRow->date_from,
                'date_to'=>$QuoteBusinessRow->date_to,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'ApproveStatus'=>$FreightAuthorizationIntendedVoyageFlg,
                'ApprovedBy'=>$FreightAuthorizationBy,
                'Version'=>$version,
                'ViewChanges'=>''
    );
    $ret=$this->db->insert('udt_AU_QuoteBusinessProcess', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_QuoteBusinessProcess');
        $this->db->where('FreightResponseID', $FreightResponseID);
        $this->db->order_by('QBPID', 'desc');
        $qry2=$this->db->get();
        $NewQBPID=$qry2->row()->QBPID;
            
        $this->db->select('udt_AU_QuoteAuthorizationBroker.*,udt_AU_QuoteAuthorizationBroker.FreightAuthorizationIntendedVoyageFlg as FreightAuthIntendVoyFlg, udt_CurrencyMaster.Code as curCode');
        $this->db->from('udt_AU_QuoteAuthorizationBroker');
        $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_QuoteAuthorizationBroker.FreightCurrency', 'left');
        $this->db->where('QBPID', $lastRow->QBPID);
        $qry3=$this->db->get();
        $oldAuthData=$qry3->row();
            
        $quote_data=array(
        'QBPID'=>$NewQBPID,
        'MasterID'=>$AuctionID,
        'TID'=>$ResponseID,
        'FreightQuoteAuthorizationFlg'=>$FreightQuoteAuthorizationFlg,
        'FreightEstimate'=>$FreightEstimate,
        'EstimateLumpsumFlg'=>$EstimateLumpsumFlg,
        'EstimateTotalValue'=>$EstimateTotalValue,
        'FreightLumpsum'=>$FreightLumpsum,
        'FreightCurrency'=>$FreightCurrency,
        'FreightAuthorizationIntendedVoyageFlg'=>$FreightAuthorizationIntendedVoyageFlg,
        'FreightAuthorizationBy'=>$UserID,
        'FreightAuthorizationDate'=>date('Y-m-d H:i:s', strtotime($FreightAuthorizationDate)),
        'FreightAuthorizationComment'=>$FreightAuthorizationComment,
        'ViewChanges'=>'',
        );
            
        $flg=$this->db->insert('udt_AU_QuoteAuthorizationBroker', $quote_data);
            
        if($flg) {
            $this->db->select('udt_AU_QuoteAuthorizationBroker.*,udt_AU_QuoteAuthorizationBroker.FreightAuthorizationIntendedVoyageFlg as FreightAuthIntendVoyFlg, udt_CurrencyMaster.Code as curCode');
            $this->db->from('udt_AU_QuoteAuthorizationBroker');
            $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_QuoteAuthorizationBroker.FreightCurrency', 'left');
            $this->db->where('QBPID', $NewQBPID);
            $qry4=$this->db->get();
            $newAuthData=$qry4->row();
                
            $html_changes='';
            if($oldAuthData) {
                if($oldAuthData->FreightQuoteAuthorizationFlg != $newAuthData->FreightQuoteAuthorizationFlg) {
                    $oldFreightQuoteAuthorizationFlg='No';
                    $newFreightQuoteAuthorizationFlg='No';
                    if($oldAuthData->FreightQuoteAuthorizationFlg==1) {
                               $oldFreightQuoteAuthorizationFlg='Yes';
                    }
                    if($newAuthData->FreightQuoteAuthorizationFlg==1) {
                         $newFreightQuoteAuthorizationFlg='Yes';
                    }
                    $html_changes .='<br> Old Freight quote authorization obtained :'.$oldFreightQuoteAuthorizationFlg.' <span class="diff">||</span> New Freight quote authorization obtained : '.$newFreightQuoteAuthorizationFlg;
                }
                    
                if($oldAuthData->FreightEstimate != $newAuthData->FreightEstimate) {
                    $html_changes .='<br> Old Freight estimate :'.$oldAuthData->FreightEstimate.' <span class="diff">||</span> New Freight estimate : '.$newAuthData->FreightEstimate;
                }
                    
                if($newAuthData->EstimateLumpsumFlg==1) {
                    if($oldAuthData->EstimateTotalValue != $newAuthData->EstimateTotalValue) {
                        $html_changes .='<br> Old Freight (cargo size * freight quoted) :'.$oldAuthData->EstimateTotalValue.' '.$oldAuthData->curCode.' <span class="diff">||</span> New Freight (cargo size * freight quoted) : '.$newAuthData->EstimateTotalValue.' '.$newAuthData->curCode;
                    }
                } else if($newAuthData->EstimateLumpsumFlg==2) {
                    if($oldAuthData->FreightLumpsum != $newAuthData->FreightLumpsum) {
                        $html_changes .='<br> Old Freight (Lumpsum) :'.$oldAuthData->FreightLumpsum.' '.$oldAuthData->curCode.' <span class="diff">||</span> New Freight (Lumpsum) : '.$newAuthData->FreightLumpsum.' '.$newAuthData->curCode;
                    }
                }
                    
                if($oldAuthData->FreightAuthIntendVoyFlg != $newAuthData->FreightAuthIntendVoyFlg) {
                    $oldFreightAuthorizationIntendedVoyageFlg='No';
                    $newFreightAuthorizationIntendedVoyageFlg='No';
                    if($oldAuthData->FreightAuthIntendVoyFlg==1) {
                            $oldFreightAuthorizationIntendedVoyageFlg='Yes';
                    }
                    if($newAuthData->FreightAuthIntendVoyFlg==1) {
                        $newFreightAuthorizationIntendedVoyageFlg='Yes';
                    }
                    $html_changes .='<br> Old Freight authorization valid for intended voyage :'.$oldFreightAuthorizationIntendedVoyageFlg.' <span class="diff">||</span> New Freight authorization valid for intended voyage : '.$newFreightAuthorizationIntendedVoyageFlg;
                }
            } else {
                         $newFreightQuoteAuthorizationFlg='No';
                if($newAuthData->FreightQuoteAuthorizationFlg==1) {
                    $newFreightQuoteAuthorizationFlg='Yes';
                }
                $html_changes .='<br> Old Freight quote authorization obtained : <span class="diff">||</span> New Freight quote authorization obtained : '.$newFreightQuoteAuthorizationFlg;
                    
                $html_changes .='<br> Old Freight estimate : <span class="diff">||</span> New Freight estimate : '.$newAuthData->FreightEstimate;
                    
                if($newAuthData->EstimateLumpsumFlg==1) {
                    $html_changes .='<br> Old Freight (cargo size * freight quoted) : <span class="diff">||</span> New Freight (cargo size * freight quoted) : '.$newAuthData->EstimateTotalValue.' '.$newAuthData->curCode;
                } else if($newAuthData->FreightLumpsum==2) {
                    $html_changes .='<br> Old Freight (Lumpsum) : <span class="diff">||</span> New Freight (Lumpsum) : '.$newAuthData->FreightLumpsum.' '.$newAuthData->curCode;
                }
                    
                    $newFreightAuthorizationIntendedVoyageFlg='No';
                if($newAuthData->FreightAuthIntendVoyFlg==1) {
                    $newFreightAuthorizationIntendedVoyageFlg='Yes';
                }
                    $html_changes .='<br> Old Freight authorization valid for intended voyage : <span class="diff">||</span> New Freight authorization valid for intended voyage : '.$newFreightAuthorizationIntendedVoyageFlg;
            }
                
            $this->db->where('QBPID', $NewQBPID);
            $this->db->update('udt_AU_QuoteBusinessProcess', array('ViewChanges'=>$html_changes));
        }
        $this->db->trans_complete();
        return $NewQBPID;
    } else {
        return 0;
    }
        
        
}
    
public function getViewChangesByQBPID($QBPID)
{
    $this->db->select('ViewChanges');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('QBPID', $QBPID);
    $qry2=$this->db->get();
    return $qry2->row();
}
    
public function saveQuoteAuthorizationAttachedFiles($NewQBPID)
{
    extract($this->input->post());
    if(isset($_FILES['upload_file'])) {
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
            if($document['name'][$i]) {
                if($ext=='pdf' || $ext=='PDF') {
                    $nar=explode(".", $document['type'][$i]);
                    $type=end($nar);
                    $file=rand(1, 999999).'_____'.$document['name'][$i];
                    $tmp=$document['tmp_name'][$i];
                    $filesize=$document['size'][$i];
                        
                    $actual_image_name = 'TopMarx/'.$file;
                    $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                        
                    $file_data = array(
                     'QBPID'=>$NewQBPID,
                     'ResponseID'=>$ResponseID,
                     'FileName'=>$file,
                     'FileType'=>$type,
                     'FileSizeKB'=>round($filesize/1024),
                     'DocumentName'=> $document_name[$i],
                     'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                     'UserID'=>$UserID, 
                     'DateTime'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_QuoteAuthorizationFiles', $file_data);
                }
            }
        }
    }
}
    
public function checkQuoteAuthorizationAttachedFiles($QBPID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteAuthorizationFiles');
    $this->db->where('QBPID', $QBPID);
    $qry2=$this->db->get();
    $cnt=$qry2->num_rows();
    if($cnt > 0) {
        return 1;
    } else {
        return 0;
    }
}
    
public function getQuoteAuthorizeAttachedFileByQBPID($QBPID)
{
    $this->db->select('udt_AU_QuoteAuthorizationFiles.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_QuoteAuthorizationFiles');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_QuoteAuthorizationFiles.UserID', 'left');
    $this->db->where('QBPID', $QBPID);
    $qry2=$this->db->get();
    return $qry2->result();
}
    
public function viewQuoteAuthorizeAttachedFileByQAFID()
{
    $QAFID=$this->input->post('QAFID');
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteAuthorizationFiles');
    $this->db->where('QAFID', $QAFID);
    $qry2=$this->db->get();
    return $qry2->row();
}
    
public function checkQuoteBusinessProcessComplete()
{
    $ResponseID=$this->input->post('ResponseID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_QuoteBusinessProcess');
    $this->db->where('TID', $ResponseID);
    $qry12=$this->db->get();
    $BusinessProcess=$qry12->result();
        
    $flg=0;
    if(count($BusinessProcess) > 0) {
        $this->db->select('Distinct LineNum');
        $this->db->from('udt_AU_FreightResponse');
        $this->db->where('ResponseID', $ResponseID);
        $qry2=$this->db->get();
        $rslt=$qry2->result();
            
        foreach($rslt as $r){
            $this->db->select('*');
            $this->db->from('udt_AU_FreightResponse');
            $this->db->where('ResponseID', $ResponseID);
            $this->db->where('LineNum', $r->LineNum);
            $qry3=$this->db->get();
            $cntFreight=$qry3->num_rows();
                
            $this->db->select('*');
            $this->db->from('udt_AU_QuoteBusinessProcess');
            $this->db->where('TID', $ResponseID);
            $this->db->where('LineNum', $r->LineNum);
            $qry4=$this->db->get();
            $cntBusiness=$qry4->num_rows();
                
            if($cntFreight==$cntBusiness) {
                $flg=1;
            } else {
                return 2;
            }
        }
        return $flg;
    } else {
        return 1;
    }
        
}
    
public function ResponseExceptedPeriodsByID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code,udt_AUM_ExceptedPeriodEventsMaster.Description');
    $this->db->from('udt_AU_ResponseExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseExceptedPeriods.EventID', 'left');
    $this->db->where('udt_AU_ResponseExceptedPeriods.ResponseCargoID', $ResponseCargoID);
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseNORTenderingPreConditionsByID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_ResponseNORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('TPCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseNORAcceptancePreConditionsByID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_ResponseNORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('APCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseOfficeHoursByID($ResponseCargoID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseOfficeHours');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('IsLastEntry', 'Desc');
    $this->db->order_by('OHID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseLaytimeCommencementsByID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_ResponseLaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseLaytimeCommencement.TurnTime', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('LCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseCargoDisportsByID($ResponseCargoID)
{
    $this->db->select('udt_AU_ResponseCargoDisports.*, dp.PortName as dpPortName, ldt2.code as ddtCode, dft.Code as dftCode, dft.Description as dftDescription, cnr1.Code as cnrDCode');
    $this->db->from('udt_AU_ResponseCargoDisports');
    $this->db->join('udt_PortMaster as dp', 'dp.ID=udt_AU_ResponseCargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt2', 'ldt2.ID=udt_AU_ResponseCargoDisports.DischargingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as dft', 'dft.ID=udt_AU_ResponseCargoDisports.DpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr1', 'cnr1.ID=udt_AU_ResponseCargoDisports.DpNorTendering', 'left');
    $this->db->where('udt_AU_ResponseCargoDisports.ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('RCD_ID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseExceptedPeriodsDpByID($ResponseCargoID,$ResponseDisportID)
{
    $this->db->select('udt_AU_ResponseDpExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code,udt_AUM_ExceptedPeriodEventsMaster.Description');
    $this->db->from('udt_AU_ResponseDpExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ResponseDpExceptedPeriods.EventID', 'left');
    $this->db->where('udt_AU_ResponseDpExceptedPeriods.ResponseCargoID', $ResponseCargoID);
    $this->db->where('udt_AU_ResponseDpExceptedPeriods.ResponseDisportID', $ResponseDisportID);
    $query=$this->db->get();
    return $query->result();
}
    
    
public function ResponseDpNORTenderingPreConditionsByID($ResponseCargoID,$ResponseDisportID)
{
    $this->db->select('udt_AU_ResponseDpNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_ResponseDpNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ResponseDisportID', $ResponseDisportID);
    $this->db->order_by('TPCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseDpNORAcceptancePreConditionsByID($ResponseCargoID,$ResponseDisportID)
{
    $this->db->select('udt_AU_ResponseDpNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_ResponseDpNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ResponseDisportID', $ResponseDisportID);
    $this->db->order_by('APCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseDpOfficeHoursByID($ResponseCargoID,$ResponseDisportID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseDpOfficeHours');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ResponseDisportID', $ResponseDisportID);
    $this->db->order_by('IsLastEntry', 'Desc');
    $this->db->order_by('OHID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function ResponseDpLaytimeCommencementsByID($ResponseCargoID,$ResponseDisportID)
{
    $this->db->select('udt_AU_ResponseDpLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_ResponseDpLaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_ResponseDpLaytimeCommencement.TurnTime', 'left');
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('ResponseDisportID', $ResponseDisportID);
    $this->db->order_by('LCID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_quote_html_details1()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('InviteeID');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('InviteeID');
    }
    $this->db->select('DifferentialID,LineNum');
    $this->db->from('udt_AU_DifferentialsResponse');
    $this->db->order_by('LineNum', 'asc');
    $this->db->order_by('DifferentialID', 'desc');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getDifferentialRefDisportsResponse($DifferentialID)
{
    $this->db->select('udt_AU_DifferentialRefDisportsResponse.*,udt_PortMaster.PortName');
    $this->db->from('udt_AU_DifferentialRefDisportsResponse');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_DifferentialRefDisportsResponse.RefDisportID', 'left');
    $this->db->order_by('udt_AU_DifferentialRefDisportsResponse.GroupNo', 'asc');
    $this->db->order_by('udt_AU_DifferentialRefDisportsResponse.PrimaryPortFlg', 'desc');
    $this->db->where('udt_AU_DifferentialRefDisportsResponse.DifferentialID', $DifferentialID);
    $query=$this->db->get();
    return $query->result();
} 
    
public function getBankDetailByAuctionIDInvitee($AuctionId,$BankEntityID)
{
    $this->db->select('udt_AU_BankingDetail.*,udt_CountryMaster.Description as country,udt_StateMaster.Description as state,udt_CurrencyMaster.Code as currency');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('udt_AU_BankingDetail', 'udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AU_BankingDetail.State', 'left');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_BankingDetail.CurrencyID', 'left');
    $this->db->where('udt_AU_AuctionBank.AuctionID', $AuctionId);
    $this->db->where('udt_AU_AuctionBank.ForEntityID', $BankEntityID);
    $this->db->where('udt_AU_AuctionBank.BankProcessType', 2);
    $this->db->where('udt_AU_AuctionBank.BankStatus', 1);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getResponseIDRecordStatus($ResponseID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
    //--------------------------for chat---------------------------------    
public function getChatRecord()
{
    $RecordOwner=$this->input->get('RecordOwner');
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
    
public function saveChatMessage()
{
    extract($this->input->post());
    $this->db->trans_start();
        
    $data=array('MasterID'=>$AuctionID,'TID'=>$ResponseID,'EntityName'=>$EntityName,'EntityID'=>$EntityID,'Chat_text'=>$chat_text,'UserName'=>$UserName,'UserID'=>$UserID,'Timestamp'=>date('Y-m-d H:i:s'),'Status'=>1,'msg_flag'=>$msg_flag,'LineNum'=>$LineNum,'include_fn'=>$include_fn);
    $ret=$this->db->insert('chat_message', $data);
        
    $Invname=$EntityName.' ('.$UserName.' )';
    $Type='';
    if($msg_flag==1) {
        $Type='Vessel';
    } else if($msg_flag==2) {
        $Type='Freight';
    } else if($msg_flag==3) {
        $Type='CargoPort';
    } else if($msg_flag==4) {
        $Type='Terms';
    }
        
    $data1=array(
    'ResponseID'=>$EntityID,
    'LineNum'=>$LineNum,
    'InviteeID'=>$ResponseID,
    'AdUs'=>$AdUs,
    'Invname'=>$Invname,
    'Chat'=>$chat_text,
    'Chat_time'=>date('Y-m-d H:i:s'),
    'Type'=>$Type,
    'chk_flag'=>$include_fn,
    'UserID'=>$UserID
                );
    $this->db->insert('udt_AU_UserChat', $data1);
        
        
        
    $this->db->select('*');
    $this->db->from('udt_AU_UserChatMessageAlert');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    $qryRow=$qry->row();
    if(count($qryRow)> 0) {
        if($Type=='Vessel') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('VesselOwnerFlag'=>1,'VesselInviteeFlag'=>1));
        } else if($Type=='Freight') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('FreightOwnerFlag'=>1,'FreightInviteeFlag'=>1));
        } else if($Type=='CargoPort') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('CargoPortOwnerFlag'=>1,'CargoPortInviteeFlag'=>1));
        } else if($Type=='Terms') {
            $this->db->where('UCMA', $qryRow->UCMA);
            $this->db->update('udt_AU_UserChatMessageAlert', array('TermOwnerFlag'=>1,'TermInviteeFlag'=>1));
        }
    } else {
         $VesselOwnerFlag=0;
         $VesselInviteeFlag=0;
         $FreightOwnerFlag=0;
         $FreightInviteeFlag=0;
         $CargoPortOwnerFlag=0;
         $CargoPortInviteeFlag=0;
         $TermOwnerFlag=0;
         $TermInviteeFlag=0;
        if($Type=='Vessel') {
            $VesselOwnerFlag=1;
            $VesselInviteeFlag=1;
        } else if($Type=='Freight') {
            $FreightOwnerFlag=1;
            $FreightInviteeFlag=1;
        } else if($Type=='CargoPort') {
            $CargoPortOwnerFlag=1;
            $CargoPortInviteeFlag=1;
        } else if($Type=='Terms') {
            $TermOwnerFlag=1;
            $TermInviteeFlag=1;
        }
            $data_alert=array(
                        'AuctionID'=>$AuctionID,
                        'ResponseID'=>$ResponseID,
                        'LineNum'=>$LineNum,
                        'VesselOwnerFlag'=>$VesselOwnerFlag,
                        'VesselInviteeFlag'=>$VesselInviteeFlag,
                        'FreightOwnerFlag'=>$FreightOwnerFlag,
                        'FreightInviteeFlag'=>$FreightInviteeFlag,
                        'CargoPortOwnerFlag'=>$CargoPortOwnerFlag,
                        'CargoPortInviteeFlag'=>$CargoPortInviteeFlag,
                        'TermOwnerFlag'=>$TermOwnerFlag,
                        'TermInviteeFlag'=>$TermInviteeFlag
                    );
            $this->db->insert('udt_AU_UserChatMessageAlert', $data_alert);
    }
        
        
        
     $this->db->trans_complete();
     return $ret;
}
    
public function getChatMessage()
{
    $ResponseID=$this->input->post('ResponseID');
    $UserID=$this->input->post('UserID');
    $LineNum=$this->input->post('LineNum');
    $SIRFlg=$this->input->post('SIRFlg');
    
    if($SIRFlg==1) {
        $updata=array('SRFlg'=>0);
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_Freight', $updata);
            
    } else if($SIRFlg==2) {
        $updata=array('SIFlg'=>0);
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('LineNum', $LineNum);
        $this->db->update('udt_AU_Freight', $updata);
            
    }
        
    $this->db->select('*');
    $this->db->from('chat_message');
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('del_flag', 1);
    $query=$this->db->get();
    $rslt=$query->result();
    $data=array('Status'=>0);
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('UserID!=', $UserID);
    $this->db->update('chat_message', $data);
    return $rslt;
}
    
public function count_unseen_message($ResponseID,$UserID)
{
    $this->db->select('*');
    $this->db->from('chat_message');
    $this->db->where('TID', $ResponseID);
    $this->db->where('Status', 1);
    $this->db->where('del_flag', 1);
    $this->db->where('UserID!=', $UserID);
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function getResponseCargoDataResponseIDWise($ResponseID)
{
    $this->db->select('udt_AU_ResponseCargo.*, cm.Code as CargoCode, lp.PortName as LpPortName, um.FirstName,um.LastName');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->join('udt_UserMaster as um', 'um.ID=udt_AU_ResponseCargo.RecordAddBy', 'left');
    $this->db->join('udt_CargoMaster as cm', 'cm.ID=udt_AU_ResponseCargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_ResponseCargo.LoadPort', 'left');
    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_ResponseCargo.LineNum', 'ASC');
    $this->db->order_by('udt_AU_ResponseCargo.UserDate', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
    //-------------------for general chat------------------------------
    
    
    
    //--------------------------for mobile chat---------------------------------
    
public function count_unseen_message_mobile($ResponseID,$UserID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('chat_message');
    $this->db->where('TID', $ResponseID);
    $this->db->where('Status', 1);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('UserID!=', $UserID);
    $this->db->where('del_flag', 1);
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function getLatestFreightQuotesMobile($ResponseID,$LineNum)
{
    $this->db->select('udt_AU_Freight.*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('udt_AU_Freight.ResponseID', $ResponseID);
    $this->db->where('udt_AU_Freight.LineNum', $LineNum);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUnseenMessageByRecordOwnerMobile()
{
    $RecordOwner=$this->input->post('RecordOwner');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AU_Freight.ResponseID,udt_AU_Freight.LineNum');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AU_Freight.AuctionID', 'Left');
    $where=" cops_admin.udt_AU_Auctions.auctionExtendedStatus='A' and ( cops_admin.udt_AU_Freight.EntityID=".$RecordOwner." or cops_admin.udt_AU_Auctions.OwnerEntityID=".$RecordOwner." ) ";
    $this->db->where($where);
    $this->db->order_by('udt_AU_Freight.UserDate', 'DESC');
    $this->db->order_by('udt_AU_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
        
}
    
    
public function getLasteDateTime($ResponseID,$LineNum)
{
    $this->db->select('chat_message.Timestamp,chat_message.UserID,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('chat_message');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=chat_message.UserID');
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('del_flag', 1);
    $this->db->order_by('chat_message_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getMessageUserCount($ResponseID,$UserID,$LineNum)
{
    $this->db->select('count(UserID)');
    $this->db->from('chat_message');
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('UserID!=', $UserID);
    $this->db->where('del_flag', 1);
    $this->db->group_by('UserID');
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function getUserImage($ResponseID,$UserID,$LineNum)
{
    $this->db->select('UserID');
    $this->db->from('chat_message');
    $this->db->where('TID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('UserID!=', $UserID);
    $this->db->where('del_flag', 1);
    $query=$this->db->get();
    $rslt=$query->row();
    $UserID=$rslt->UserID;
        
    $this->db->select('AttachPhoto');
    $this->db->from('udt_AU_SignatureBlock');
    $this->db->where('UserID', $UserID);
    $query=$this->db->get();
    $rslt=$query->row();
    return $rslt->AttachPhoto;
}
    
    //-------------------for mobile general chat------------------------------
    
    //-------------- Response New ----------
    
public function getResponseNew()
{
    $RecordOwner=$this->input->get('RecordOwner');
    $QuoteType=$this->input->get('QuoteType');
        
    $this->db->select('udt_AU_Auctions.AuctionID, udt_AU_Auctions.OwnerEntityID, udt_EntityMaster.EntityName,udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID', 'left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AU_Auctions.AuctionID', 'Left');
    $this->db->where('auctionExtendedStatus', 'A');
        
    if($QuoteType==2) {
        $this->db->where('udt_AU_Auctions.OwnerEntityID', $RecordOwner);
    }
        
    $this->db->order_by('udt_AU_Auctions.UserDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getResposeQuoteDetails($AuctionID)
{
    $this->db->select('ReleaseDate,ResponseStatus');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityIsInviteeOrNot($AuctionID,$EntityID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $EntityID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getResponseNewByAuctioniD($MasterID)
{
        
    $this->db->select('udt_AUM_Freight.*, InvEntity.EntityName, udt_AUM_Alerts.AuctionCeases, udt_AUM_Alerts.auctionceaseshour, udt_AU_Auctions.OwnerEntityID as OwnerID,OwnerEntity.EntityName as OwnerEntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_AU_Auctions', 'udt_AU_Auctions.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_AUM_Alerts', 'udt_AUM_Alerts.AuctionID=udt_AUM_Freight.AuctionID', 'Left');
    $this->db->join('udt_EntityMaster as InvEntity', 'InvEntity.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as OwnerEntity', 'OwnerEntity.ID=udt_AU_Auctions.OwnerEntityID', 'Left');
    $this->db->where('udt_AUM_Freight.AuctionID', $MasterID);
    $this->db->order_by('udt_AUM_Freight.ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
    //---------------------response quote graph-------------
    
public function getResponseIDByAuctionID()
{ 
    $AuctionID=$this->input->get('AuctionID');
    $this->db->select('udt_AUM_Freight.ResponseID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->result();
    return $data;
}
    
public function getResponseQuoteForGraph($ResponseID)
{
    $AuctionID=$this->input->get('AuctionID');
    $LineNum=$this->input->get('LineNum');
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('ResponseID', 'ASC');
    $this->db->order_by('FreightVersion', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function gerFirstVersion($ResponseID)
{
    $this->db->select('UserDate');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('FreightVersion', 'Version 1.0');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getResponseQuoteForGraphDisplay($ResponseID)
{
    $AuctionID=$this->input->get('AuctionID');
    $LineNum=$this->input->get('LineNum');
        
    $this->db->select('FreightResponseID');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('FreightResponseID', 'ASC');
    $query=$this->db->get();
    $rslt=$query->row();
        
    $this->db->select('ResponseID,FreightRate');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('FreightResponseID !=', $rslt->FreightResponseID);
    $this->db->where('LineNum', $LineNum);
    $this->db->order_by('FreightResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function setChangeStatusZero($AuctionID)
{
    $data=array('change_status'=>0);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AUM_Freight', $data);
}
    
public function getChangeStatus()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('ResponseID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('change_status', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_quote_html_details()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
        
    $this->db->select('udt_AU_Freight.*,udt_CurrencyMaster.Code as curCode');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_Freight.FreightCurrency', 'left');
    $this->db->where('udt_AU_Freight.ResponseID', $InviteeID);
    $this->db->where('udt_AU_Freight.LineNum', $LineNum);
    $this->db->order_by('udt_AU_Freight.FreightID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_quote_html_details_new()
{
    if($this->input->post()) {
        $InviteeID=$this->input->post('InviteeID');
        $LineNum=$this->input->post('LineNum');
    }
    if($this->input->get()) {
        $InviteeID=$this->input->get('InviteeID');
        $LineNum=$this->input->get('LineNum');
    }
        
    $this->db->select('udt_AU_Freight.*,udt_CurrencyMaster.Code as curCode');
    $this->db->from('udt_AU_Freight');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_Freight.FreightCurrency', 'left');
    $this->db->where('udt_AU_Freight.ResponseID', $InviteeID);
    //$this->db->where('udt_AU_Freight.LineNum',$LineNum);
    $this->db->order_by('udt_AU_Freight.LineNum', 'ASC');
    $this->db->order_by('udt_AU_Freight.FreightID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getAuctionRecordsByFreight($AuctionID)
{
    $this->db->select('FreightRate,ResponseID');
    $this->db->from('udt_AU_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('FreightRate >', 0);
    $this->db->order_by('ResponseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoDataByAuctionID($AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCargoCountByAuctionID($AuctionID)
{
    $this->db->select('LineNum');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('LineNum', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoDataByAuctionIDNew($AuctionID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $LineNum);
    $query=$this->db->get();
    return $query->row();
}
    
public function getQuoteByAuctionIDNew($AuctionID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('FreightRate >', 0);
    $this->db->order_by('FreightRate', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDemmurageByAuctionIDNew($AuctionID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $LineNum);
    $this->db->where('Demurrage >', 0);
    $this->db->order_by('Demurrage', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFreightEstimateByAuctionID($AuctionID,$LineNum)
{
    $this->db->select('CargoQtyMT,Freight_Estimate,Estimate_By,Estimate_mt,Estimate_from,Estimate_to');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $LineNum);
    $qry=$this->db->get();
    return $qry->row();
        
}
    
public function getBankDetailByAuctionID($AuctionId)
{
    $this->db->select('udt_AU_BankingDetail.*,udt_CountryMaster.Description as country,udt_StateMaster.Description as state,udt_CurrencyMaster.Code as currency');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('udt_AU_BankingDetail', 'udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AU_BankingDetail.State', 'left');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=udt_AU_BankingDetail.CurrencyID', 'left');
    $this->db->where('udt_AU_AuctionBank.AuctionID', $AuctionId);
    $this->db->where('udt_AU_AuctionBank.BankProcessType', 1);
    $this->db->where('udt_AU_AuctionBank.BankStatus', 1);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getAuctionCargoDetails($AuctionID)
{
    $CargoID=$this->input->get('CargoID');
    $this->db->select('udt_AU_Cargo.CargoID, udt_AU_Cargo.LineNum, udt_AU_Cargo.SelectFrom, udt_AU_Cargo.LpLaycanStartDate, udt_AU_Cargo.LpLaycanEndDate, udt_CargoMaster.Code as CargoCode, udt_PortMaster.PortName');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('LineNum', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getAuctionDifferentialRecord($AuctionID,$LineNum)
{
    $this->db->select('FreightRateFlg');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('AuctionID', $AuctionID); 
    $this->db->where('LineNum', $LineNum);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getModelSetupByRecoredOwner($RecordOwner,$AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $rslt=$query->row();
    $mid=$rslt->ModelNumber; 
        
    $this->db->select('*');
    $this->db->from('udt_AU_Model');
    $this->db->where('mid', $mid);
    $query1=$this->db->get();
    return $query1->row();
}
    
public function getProrityByAuctionID($EntityID,$AuctionID)
{
    $this->db->select('InvPriorityStatus');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $EntityID);
    $query=$this->db->get();
    return $query->row()->InvPriorityStatus; 
}
    
public function getAuctionRecordOwner($AuctionID)
{
    $this->db->select('udt_AU_Auctions.*, udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID', 'left');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function get_DocumentTypeID_ByFileName($filename)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('CharterPartyPdf', $filename);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function get_Content_ByDocumentTypeID($DocumentTypeID)
{
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $this->db->order_by('SerialNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getClausesTextByID($id)
{
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(ClauseText, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AUM_DocumentClause');
        $this->db->where('ClauseID', $id);
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
    
public function getCargoUnits()
{
    $this->db->select('*');
    $this->db->from('udt_CargoUnitMaster');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_quote_html_all_linenum()
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
    $query=$this->db->get();
    return $query->result();
}
    
public function getModelName($ModelNumber)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Model');
    $this->db->where('mid', $ModelNumber);
    $query=$this->db->get();
    return $query->row();
}
    
public function getInviteePagePermissions($AuctionID,$UserID)
{
    $this->db->select('OwnerEntityID');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $auction_row = $query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->where('RecordOwner', $auction_row->OwnerEntityID);
    $this->db->where('ForUserID', $UserID);
    $query1=$this->db->get();
    return $query1->row();
        
}
    
public function getProrityForShipBroker($DisponentOwnerID)
{
    if($this->input->get()) {
        $RecordOwner=$this->input->get('RecordOwner');
    } else {
        $RecordOwner=$this->input->post('RecordOwner');
    }
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitee_Master');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
    $this->db->where('udt_AUM_Invitee_Master.RecordOwner', $RecordOwner);
    $this->db->where('udt_UserMaster.EntityID', $DisponentOwnerID);
    $this->db->order_by('PriorityStatus', 'ASC');
    $query=$this->db->get();
    return $query->row()->PriorityStatus;
        
        
    $this->db->select('*');
    $this->db->from('udt_Mapping_EntityTypes');
    $this->db->where('udt_Mapping_EntityTypes.EntityMasterID', $DisponentOwnerID);
    $this->db->where('udt_Mapping_EntityTypes.EntityTypeID', '5');
    $query=$this->db->get();
    return $query->row()->PriorityStatus;
}
    
public function getEntityName()
{
    $user=$this->input->post('user');
    $this->db->select('EntityName');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $user);
    $query=$this->db->get();
    return $query->row();
}
    
public function get_assoc_entity_details1($entityid)
{
    $this->db->select('udt_EntityMaster.*,udt_AddressMaster.Address1,udt_AddressMaster.Address2,udt_AddressMaster.Address3, udt_AddressMaster.Address4,udt_AddressMaster.CountryID,udt_CountryMaster.Code as C_Code, udt_CountryMaster.Description as C_Description,udt_AddressMaster.StateID,udt_StateMaster.Code as S_Code,udt_StateMaster.Description as S_Description');
    $this->db->from('udt_EntityMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_EntityMaster.AddressID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AddressMaster.CountryID', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=udt_AddressMaster.StateID', 'left');
    $this->db->where('udt_EntityMaster.ID', $entityid);
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixtureByAuctionID($AuctionID,$ResponseID)
{
    $this->db->select('udt_AU_AuctionFixture.*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->where('udt_AU_AuctionFixture.AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_AuctionFixture.FixtureID', 'DESC');
    $query=$this->db->get();
    return $query->row()->Status;
    
}
    
public function getDocumentationByAuctionID($AuctionID,$ResponseID)
{
    $this->db->select('udt_AU_AuctionMainDocumentation.*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
    $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
    $query=$this->db->get();
    return $query->row()->Status;
    
}
	
	
}


