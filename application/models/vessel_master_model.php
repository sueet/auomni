<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class vessel_master_model extends CI_Model
{
    /**
     * Developer Name : Harmeet Singh
     *
     * Comapny Name : HigrooveSystems 
     *
     * Create Date : 13-09-2016
     */
    
    function __construct()
    {
        parent::__construct();        
        $this->load->library('session');
    } 
    
    public function getVesselData()
    { 
        $cocode=C_COCODE;
        $SizeGroup=$this->input->get('SizeGroup');
        $VesselSizeTo=$this->input->get('VesselSizeTo');
        $VesselSizeFrom=$this->input->get('VesselSizeFrom');
        $EntityMasterID=$this->input->get('EntityMasterID');
        $EntityID=$this->input->get('EID');
        
        $this->db->select('udt_AUM_Vessel_Master.VesselID,udt_AUM_Vessel_Master.CoCode,udt_AUM_Vessel_Master.OwnerEntityID,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,udt_AUM_Vessel_Master.CargoRangePercentage,udt_AUM_Vessel_Master.CargoRangeFrom,udt_AUM_Vessel_Master.CargoRangeTo,udt_AUM_Vessel_Master.VesselComments,udt_AUM_Vessel_Master.EntityMasterID,udt_AUM_Vessel_Master.ActiveFlag,udt_AUM_Vessel_Master.UserID,udt_AUM_Vessel_Master.UserDate,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Vessel_Master.EntityMasterID');
        $this->db->where('CoCode', $cocode);
        if($SizeGroup) {
            $this->db->like('SizeGroup', $SizeGroup);
        }
        if($VesselSizeFrom) {
            $this->db->where('VesselSize >=', $VesselSizeFrom);
        }
        if($VesselSizeTo) {
            $this->db->where('VesselSize <=', $VesselSizeTo);
        }
        if($EntityMasterID) {
            $this->db->where('EntityMasterID', $EntityMasterID);
        }
        if($EntityID) {
            $this->db->where('udt_AUM_Vessel_Master.EntityMasterID', $EntityID);
        }
        $this->db->order_by("UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function searchVesselData()
    { 
        $VesselSizeFrom=$this->input->post('VesselSizeFrom');
        $VesselSizeTo=$this->input->post('VesselSizeTo');
        $SizeGroup=$this->input->post('SizeGroup');
        $EntityMasterID=$this->input->post('EntityMasterID');
        //echo $VesselSizeFrom; die;
        $cocode=C_COCODE;
        $this->db->select('udt_AUM_Vessel_Master.VesselID,udt_AUM_Vessel_Master.CoCode,udt_AUM_Vessel_Master.OwnerEntityID,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,udt_AUM_Vessel_Master.CargoRangePercentage,udt_AUM_Vessel_Master.CargoRangeFrom,udt_AUM_Vessel_Master.CargoRangeTo,udt_AUM_Vessel_Master.VesselComments,udt_AUM_Vessel_Master.EntityMasterID,udt_AUM_Vessel_Master.ActiveFlag,udt_AUM_Vessel_Master.UserID,udt_AUM_Vessel_Master.UserDate,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Vessel_Master.EntityMasterID');
        
        if($VesselSizeFrom) {
            $this->db->where('udt_AUM_Vessel_Master.VesselSize >=', $VesselSizeFrom);
        }
        if($VesselSizeTo) {
            $this->db->where('udt_AUM_Vessel_Master.VesselSize <=', $VesselSizeTo);
        }
        if($SizeGroup) {
            $this->db->where('udt_AUM_Vessel_Master.SizeGroup', $SizeGroup);
        }
        if($EntityMasterID) {
            $this->db->where('udt_AUM_Vessel_Master.EntityMasterID', $EntityMasterID);
        }
        $this->db->where('CoCode', $cocode);
        $this->db->order_by("UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function getDocumentData()
    {
        $document_type=$this->input->get('document_type');
        $document_title=$this->input->get('document_title');
        $EntityMasterID=$this->input->get('EntityMasterID');
        $EntityID=$this->input->get('EID');
        $cocode=C_COCODE;
        $entityid=$this->session->userdata('SESS_ENTITYID');
        $this->db->select("udt_AUM_DocumentType_Master.DocumentTypeID,udt_AUM_DocumentType_Master.CoCode,udt_AUM_DocumentType_Master.OwnerEntityID,udt_AUM_DocumentType_Master.DocumentType,udt_AUM_DocumentType_Master.DocumentTitle,udt_AUM_DocumentType_Master.EntityMasterID,udt_AUM_DocumentType_Master.ActiveFlag,udt_AUM_DocumentType_Master.UserID,udt_AUM_DocumentType_Master.UserDate,udt_AUM_DocumentType_Master.charterPartyEditableFlag,udt_AUM_DocumentType_Master.CharterPartyPdf,udt_AUM_DocumentType_Master.Logo,EM.EntityName as EntityName,OM.EntityName as OwnerName");
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->join('udt_EntityMaster as EM', 'EM.ID=udt_AUM_DocumentType_Master.EntityMasterID');
        $this->db->join('udt_EntityMaster as OM', 'OM.ID=udt_AUM_DocumentType_Master.OwnerEntityID');
        $this->db->where('CoCode', $cocode);
        if($document_type) {
            $this->db->where('DocumentType', $document_type);
        }
        if($document_title) {
            $this->db->where('DocumentTitle', $document_title);
        }
        if($EntityMasterID) {
            $this->db->where('EntityMasterID', $EntityMasterID);
        }
        if($EntityID) {
            $this->db->where('EntityMasterID', $EntityID);
        }
        $this->db->order_by("UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function searchDocumentDataData()
    { 
        $cocode=C_COCODE;
        $entity_name='Braemar Seascope Pty Ltd';
        $entityid=$this->session->userdata('SESS_ENTITYID');
        $document_type=$this->input->post('document_type');
        $document_title=$this->input->post('document_title');
        $EntityMasterID=$this->input->post('EntityMasterID');
        
        $this->db->select("udt_AUM_DocumentType_Master.DocumentTypeID,udt_AUM_DocumentType_Master.CoCode,udt_AUM_DocumentType_Master.OwnerEntityID,udt_AUM_DocumentType_Master.DocumentType,udt_AUM_DocumentType_Master.DocumentTitle,udt_AUM_DocumentType_Master.EntityMasterID,udt_AUM_DocumentType_Master.ActiveFlag,udt_AUM_DocumentType_Master.UserID,udt_AUM_DocumentType_Master.UserDate,udt_EntityMaster.EntityName,'".$entity_name."' as OwnerName");
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_DocumentType_Master.EntityMasterID');
        if($document_type) {
            $this->db->where('udt_AUM_DocumentType_Master.DocumentType', $document_type);
        }
        if($document_title) {
            $this->db->where('udt_AUM_DocumentType_Master.DocumentTitle', $document_title);
        }
        
        if($EntityMasterID) {
            $this->db->where('udt_AUM_DocumentType_Master.EntityMasterID', $EntityMasterID);
        }
        $this->db->where('CoCode', $cocode);
        $this->db->order_by("UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function getInviteeData()
    { 
        $cocode=C_COCODE;
        $UserGroup=$this->input->get('UserGroup');
        $PriorityStatus=$this->input->get('PriorityStatus');
        $EntityMasterID=$this->input->get('EntityMasterID');
        $EntityID=$this->input->get('EID');
        
        $this->db->select('udt_AUM_Invitee_Master.InviteeID,udt_AUM_Invitee_Master.UserGroup,udt_AUM_Invitee_Master.QuoteLimitFlag,udt_AUM_Invitee_Master.InviteeStatus,udt_AUM_Invitee_Master.InviteePeriod,udt_AUM_Invitee_Master.DateRangeFrom,udt_AUM_Invitee_Master.DateRangeTo,udt_AUM_Invitee_Master.PriorityStatus,udt_AUM_Invitee_Master.PriorityComments,udt_AUM_Invitee_Master.ForUserID,udt_AUM_Invitee_Master.UserDate,udt_AUM_Invitee_Master.PrimeRole,udt_UserMaster.FirstName,udt_UserMaster.LastName,EM.EntityName as EntityName,OWN.EntityName as OwnerName, udt_EntityType.Description as RoleDescription');
        $this->db->from('udt_AUM_Invitee_Master');
        $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AUM_Invitee_Master.InviteeRole', 'Left');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID', 'Left');
        $this->db->join('udt_EntityMaster as EM', 'EM.ID=udt_UserMaster.EntityID', 'Left');
        $this->db->join('udt_EntityMaster as OWN', 'OWN.ID=udt_AUM_Invitee_Master.RecordOwner', 'Left');
        $this->db->where('udt_AUM_Invitee_Master.CoCode', $cocode);
        if($UserGroup) {
            $this->db->where('udt_AUM_Invitee_Master.UserGroup', $UserGroup);
        }
        if($PriorityStatus) {
            $this->db->where('udt_AUM_Invitee_Master.PriorityStatus', $PriorityStatus);
        }
        if($EntityMasterID) {
            $this->db->where('EM.ID', $EntityMasterID);
        }
        if($EntityID) {
            $this->db->where('udt_AUM_Invitee_Master.RecordOwner', $EntityID);
        }
        $this->db->order_by("udt_AUM_Invitee_Master.UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function searchInviteeData()
    { 
        $cocode=C_COCODE;
        $UserGroup=$this->input->post('UserGroup');
        $PriorityStatus=$this->input->post('PriorityStatus');
        $EntityMasterID=$this->input->post('EntityMasterID');
        
        $this->db->select('udt_UserMaster.ID,udt_UserMaster.UserGroup,udt_UserMaster.InviteeStatus,udt_UserMaster.InviteePeriod,udt_UserMaster.DateRangeFrom,udt_UserMaster.DateRangeTo,udt_UserMaster.PriorityStatus,udt_UserMaster.PriorityComments,udt_UserMaster.UserID,udt_UserMaster.UserDate,udt_EntityMaster.EntityName');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
        if($UserGroup) {
            $this->db->where('udt_UserMaster.UserGroup', $UserGroup);
        }
        if($PriorityStatus) {
            $this->db->where('udt_UserMaster.PriorityStatus', $PriorityStatus);
        }
        if($EntityMasterID) {
            $this->db->where('udt_UserMaster.EntityID', $EntityMasterID);
        }
        
        $this->db->where('CoCode', $cocode);
        $this->db->order_by("UserDate", "desc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function getUserData($id)
    { 
        //echo $id; die;
        $cocode=C_COCODE;
        $this->db->select('udt_UserMaster.*, udt_AddressMaster.Email, udt_AddressMaster.Telephone1, udt_AddressMaster.City');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
        $this->db->where('EntityID', $id);
        $this->db->where('CargoInvitationFlag', '1');
        $this->db->order_by("udt_UserMaster.LoginID", "asc");
        $query=$this->db->get();
        $UserData=$query->result();
        return $UserData;
    }
    
    public function get_selected_UserData($id)
    { 
        //echo $id; die;
        $cocode=C_COCODE;
        $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email,udt_AddressMaster.Telephone1,udt_AddressMaster.City');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'Left');
        $this->db->where('udt_UserMaster.ID', $id);
        $query=$this->db->get();
        $UserData=$query->row();
        return $UserData;
    }
    
    public function all_entity_Data()
    { 
        $key=$this->input->post('key');
        $entity=$this->input->post('entity');
        $this->db->select('ID,EntityName,Description,EntityOwner');
        $this->db->from('udt_EntityMaster', 'after');
        $this->db->like('EntityName', $key);
        if($entity) {
            $this->db->where('ID', $entity);
        }
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function all_my_entity_data()
    { 
        $key=$this->input->post('key');
        $entity=$this->input->post('entity');
        $this->db->select('ID,EntityName,EntityOwner');
        $this->db->from('udt_EntityMaster', 'after');
        $this->db->like('EntityName', $key);
        if($entity !=1) {
            $this->db->where('EntityOwner', $entity);
        }
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function my_entity_data()
    { 
        $key=$this->input->post('key');
        $entity=$this->input->post('entity');
        $this->db->select('ID,EntityName,EntityOwner');
        $this->db->from('udt_EntityMaster', 'after');
        $this->db->like('EntityName', $key);
        $this->db->where('ID', $entity);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function all_entity_data_invitee()
    { 
        $key=$this->input->post('key');
        $this->db->select('ID,EntityName,ParentEntityID,EntityOwner');
        $this->db->from('udt_EntityMaster', 'after');
        $this->db->where('InviteeEntityFlg', 1);
        $this->db->like('EntityName', $key);
        
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function get_all_document_type()
    { 
        $type=$this->input->get('type');
        if($type=='vessel') {
            $key=$this->input->get('key');
            $EID=$this->input->get('EID');
            $DocType=$this->input->get('DocType');
        } else {
            $key=$this->input->post('key');
            $EID=$this->input->post('EID');
            $DocType=$this->input->post('DocType');
        }
        //print_r($EID); die;
        $this->db->select('Distinct DocumentType');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->like('DocumentType', $key, 'after');
        if($EID) {
            $this->db->where('EntityMasterID', $EID);    
        }
        if($DocType) {
            $this->db->where('FinalDocumentationFlag', $DocType);
        }
        $this->db->where('ActiveFlag', 1);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function get_all_document_type_id()
    { 
     
        $type=$this->input->get('type');
        if($type=='vessel') {
            $key=$this->input->get('key');
            $EID=$this->input->get('EID');
            $DocType=$this->input->get('DocType');
        }else{
            $key=$this->input->post('key');
            $EID=$this->input->post('EID');
            $DocType=$this->input->post('DocType');
        }
        //print_r($EID); die;
        $this->db->select('Distinct udt_AUM_DocumentType_Master.DocumentType, DocumentTypeID');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->like('DocumentType', $key, 'after');
        if($EID) {
            $this->db->where('EntityMasterID', $EID);    
        }
        if($DocType) {
            $this->db->where('FinalDocumentationFlag', $DocType);
        }
        $this->db->where('ActiveFlag', 1);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function get_all_document_title()
    { 
        $key=$this->input->post('key');
        $this->db->select('DISTINCT DocumentTitle');
        $this->db->from('udt_AUM_DocumentType_Master', 'after');
        $this->db->like('DocumentTitle', $key);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function get_all_document_master_title()
    { 
        $key=$this->input->post('key');
        $EID=$this->input->post('EID');
        $this->db->select('DISTINCT DocName');
        $this->db->from('udt_AUM_Document_master');
        $this->db->like('DocName', $key, 'after');
        if($EID) {
            $this->db->where('RecoredOwner', $EID);
        }
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function getParentData()
    { 
        $id=$this->input->post('id');
        $this->db->select('udt_ParentGroupMaster.ID,udt_ParentGroupMaster.GroupName');
        $this->db->from('udt_ParentGroupMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ParentGroupID=udt_ParentGroupMaster.ID');
        $this->db->where('udt_EntityMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    
    }
    
    public function getEntityData()
    { 
        
        $this->db->select('*');
        $this->db->from('udt_EntityMaster');
        $this->db->where('ParentGroupID', $id);
        $query=$this->db->get();
        $EntityData=$query->result();
        return $EntityData;
    }
    
    public function all_vessel_ById($id)
    { 
        $this->db->select('udt_AUM_Vessel_Master.VesselID,udt_AUM_Vessel_Master.CoCode,udt_AUM_Vessel_Master.OwnerEntityID,udt_AUM_Vessel_Master.VesselSize,udt_AUM_Vessel_Master.SizeGroup,udt_AUM_Vessel_Master.CargoRangePercentage,udt_AUM_Vessel_Master.CargoRangeFrom,udt_AUM_Vessel_Master.CargoRangeTo,udt_AUM_Vessel_Master.VesselComments,udt_AUM_Vessel_Master.EntityMasterID,udt_AUM_Vessel_Master.ActiveFlag,udt_AUM_Vessel_Master.UserID,udt_AUM_Vessel_Master.UserDate,udt_EntityMaster.EntityName,udt_EntityMaster.Description');
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Vessel_Master.EntityMasterID');
        $this->db->where('VesselID', $id);
        $query=$this->db->get();
        $VesselData=$query->row();
        return $VesselData;
    }
    
    public function all_invitee_ById($id)
    { 
        //echo $id; die;
        $this->db->select('udt_AUM_Invitee_Master.InviteeID,udt_AUM_Invitee_Master.RecordOwner,udt_AUM_Invitee_Master.UserGroup,udt_AUM_Invitee_Master.InviteeStatus,udt_AUM_Invitee_Master.InviteePeriod,udt_AUM_Invitee_Master.DateRangeFrom,udt_AUM_Invitee_Master.DateRangeTo,udt_AUM_Invitee_Master.PriorityStatus,udt_AUM_Invitee_Master.PriorityComments,udt_AUM_Invitee_Master.ForUserID,udt_AUM_Invitee_Master.InviteeRole,udt_AUM_Invitee_Master.PrimeRole,udt_AUM_Invitee_Master.QuoteLimitFlag,udt_AUM_Invitee_Master.BidStatusFlg,udt_AUM_Invitee_Master.CharterDetailsFlg,udt_AUM_Invitee_Master.InviteeCommentsFlg,udt_AUM_Invitee_Master.ConfirmationFlg,EM.EntityName as EntityName,EM.ID as EID, OWN.EntityName as OwnerName');
        $this->db->from('udt_AUM_Invitee_Master');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
        $this->db->join('udt_EntityMaster as EM', 'EM.ID=udt_UserMaster.EntityID');
        $this->db->join('udt_EntityMaster as OWN', 'OWN.ID=udt_AUM_Invitee_Master.RecordOwner');
        $this->db->where('udt_AUM_Invitee_Master.InviteeID', $id);
        $query=$this->db->get();
        $userData=$query->row();
        return $userData;
    }
    
    public function getPriorityReasonsByInviteeID($id)
    {
        $this->db->select('udt_AUM_InviteePriorityStatus.*,udt_UserMaster.FirstName, udt_UserMaster.LastName');
        $this->db->from('udt_AUM_InviteePriorityStatus');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_InviteePriorityStatus.ByUserID', 'left');
        $this->db->where('InviteeID', $id);
        $this->db->order_by('IPS', 'desc');
        $qry=$this->db->get();
        return $qry->result();
    }
    /* 
    public function all_document_ById($id){
    $this->db->select('udt_AUM_DocumentType_Master.DocumentTypeID,udt_AUM_DocumentType_Master.CoCode,udt_AUM_DocumentType_Master.OwnerEntityID,udt_AUM_DocumentType_Master.DocumentType,udt_AUM_DocumentType_Master.DocumentTitle,udt_AUM_DocumentType_Master.EntityMasterID,udt_AUM_DocumentType_Master.ActiveFlag,udt_AUM_DocumentType_Master.UserID,udt_AUM_DocumentType_Master.UserDate,udt_EntityMaster.EntityName,udt_EntityMaster.Description');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_EntityMaster','udt_EntityMaster.ID=udt_AUM_DocumentType_Master.EntityMasterID');
    $this->db->where('DocumentTypeID',$id);
    $query=$this->db->get();
    $documentData=$query->row();
    return $documentData;

    }
    */
    public function all_document_ById($id)
    {
        $this->db->select('udt_AUM_DocumentType_Master.*,udt_EntityMaster.EntityName,udt_EntityMaster.Description,udt_UserMaster.FirstName,udt_UserMaster.LastName');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_DocumentType_Master.EntityMasterID', 'left');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_DocumentType_Master.ApprovedBy', 'left');
        $this->db->where('DocumentTypeID', $id);
        $query=$this->db->get();
        $documentData=$query->row();
        return $documentData;

    }
    
    public function addVesselDetails()
    {
        $query='';
        $result='';
        extract($this->input->post());
        $this->db->select("*");
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->where('SizeGroup', $SizeGroup);
        $this->db->where('EntityMasterID', $EntityMasterID);
        $query=$this->db->get();
        $result=$query->row();
        //print_r($result); die;
        if(!$result) {
            $data=array(
            'CoCode'=>C_COCODE,
            'OwnerEntityID'=>$entityID,
            'VesselSize'=>$VesselSize,
            'SizeGroup'=>$SizeGroup,
            'CargoRangePercentage'=>$CargoRangePercentage,
            'CargoRangeFrom'=>$CargoRangeFrom,
            'CargoRangeTo'=>$CargoRangeTo,
            'VesselComments'=>$VesselComments,
            'EntityMasterID'=>$EntityMasterID,
            'UserID'=> $UserID,
            'UserDate'=> date('Y-m-d h:i:s'),
            'ActiveFlag'=>$ActiveFlag
            );
            //print_r($data); die;
            return $this->db->insert('udt_AUM_Vessel_Master', $data);
        }else{
            return 0;
        }
        
        //return $this->db->insert_id();
    }
    
    public function check_vessel()
    {
        extract($this->input->post());
        $this->db->select("*");
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->where('SizeGroup', $SizeGroup);
        $this->db->where('EntityMasterID', $EntityMasterID);
        $query=$this->db->get();
        $result=$query->row();
        if($result) {
            return 1;
        }else{
            return 0;
        }
    
    }
    
    public function updateVesselDetails()
    {
        extract($this->input->post());
        //$entityid=$this->session->userdata('SESS_ENTITYID');
        $cocode=C_COCODE;
        
        $this->db->select("*");
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->where('SizeGroup', $SizeGroup);
        $this->db->where('EntityMasterID', $EntityMasterID);
        $query=$this->db->get();
        $result=$query->row();
        
        if($result) {
            $this->db->select('*');
            $this->db->where('VesselID', $result->VesselID);
            $query=$this->db->get('udt_AUM_Vessel_Master');
            $last_data=$query->row();
            $this->db->insert('udt_AUM_Vessel_Master_H', $last_data);
            $data=array(
            'OwnerEntityID'=>$entityID,
            'VesselSize'=>$VesselSize,
            'SizeGroup'=>$SizeGroup,
            'CargoRangePercentage'=>$CargoRangePercentage,
            'CargoRangeFrom'=> $CargoRangeFrom,
            'CargoRangeTo'=> $CargoRangeTo,
            'VesselComments'=>$VesselComments,
            'EntityMasterID'=>$EntityMasterID,
            'UserID'=> $UserID,
            'UserDate'=> date('Y-m-d h:i:s'),
            'ActiveFlag'=>$ActiveFlag
            );
            $this->db->where('VesselID', $result->VesselID);
            return $this->db->update('udt_AUM_Vessel_Master', $data);
        }else{
            $this->db->select('*');
            $this->db->where('VesselID', $vesselID);
            $query=$this->db->get('udt_AUM_Vessel_Master');
            $last_data=$query->row();
            $this->db->insert('udt_AUM_Vessel_Master_H', $last_data);
            $data=array(
            'OwnerEntityID'=>$entityID,
            'VesselSize'=>$VesselSize,
            'SizeGroup'=>$SizeGroup,
            'CargoRangePercentage'=>$CargoRangePercentage,
            'CargoRangeFrom'=> $CargoRangeFrom,
            'CargoRangeTo'=> $CargoRangeTo,
            'VesselComments'=>$VesselComments,
            'EntityMasterID'=>$EntityMasterID,
            'UserID'=> $UserID,
            'UserDate'=> date('Y-m-d h:i:s'),
            'ActiveFlag'=>$ActiveFlag
            );
            $this->db->where('VesselID', $vesselID);
            return $this->db->update('udt_AUM_Vessel_Master', $data);
        }
        
    }
    /* 
    public function updateDocumentDetails(){
    
    $cocode=C_COCODE;
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentType',$DocumentType);
    $this->db->where('DocumentTitle',$DocumentTitle);
    $this->db->where('EntityMasterID',$EntityMasterID);
    $query1=$this->db->get();
    $result=$query1->row();
    if(!$result){
    $this->db->select('*');
    $this->db->where('DocumentTypeID',$documentID);
    $this->db->where('CoCode',$cocode);
    $query=$this->db->get('udt_AUM_DocumentType_Master');
    $last_data=$query->row();
    //print_r($last_data);  die;
    $this->db->insert('udt_AUM_DocumentType_Master_H',$last_data);
    //echo $id; die;
    $data=array(
                'OwnerEntityID'=>$entityID,
                'EntityMasterID'=>$EntityMasterID,
                'DocumentType'=>$DocumentType,
                'DocumentTitle'=>$DocumentTitle,
                'UserID'=>$UserID,
                'UserDate'=> date('Y-m-d h:i:sa'),
                'ActiveFlag'=>$ActiveFlag
    );
    //print_r($data); die;
                $this->db->where('DocumentTypeID',$documentID);
                $this->db->where('CoCode',$cocode);
                return $this->db->update('udt_AUM_DocumentType_Master',$data);
    }else{
    return 0;
    }    
    }
    */
    public function updateDocumentDetails()
    {
        
        $cocode=C_COCODE;
        extract($this->input->post());
        $result=array();
        if($DocumentTitle != $DocumentTitle_check) {
            $this->db->select('*');
            $this->db->from('udt_AUM_DocumentType_Master');
            $this->db->where('DocumentType', $DocumentType);
            $this->db->where('DocumentTitle', $DocumentTitle);
            $this->db->where('EntityMasterID', $EntityMasterID);
            $query1=$this->db->get();
            $result=$query1->row();
        }
        
        if(!$result || $result->DocumentTypeID==$documentID) {
            $this->db->select('*');
            $this->db->where('DocumentTypeID', $documentID);
            $this->db->where('CoCode', $cocode);
            $query=$this->db->get('udt_AUM_DocumentType_Master');
            $last_data=$query->row();
            //print_r($last_data);  die;
            //$this->db->insert('udt_AUM_DocumentType_Master_H',$last_data);
                    
            //-------------upload logo-----------
            $file='';
            $file_flg=0;
            if($AttachedLogo !='') {
                $file=$AttachedLogo;
                $file_flg=1;
            } else {
                $document=$_FILES['Logo'];
                $document1=$_FILES['CharterPartyPdf'];
                
                $file=rand(1, 999999).'_____'.$document['name'];
                $file1=rand(1, 999999).'_____'.$document1['name'];
                $tmp=$document['tmp_name'];
                $tmp1=$document1['tmp_name'];
                
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
                    $file_flg=1;    
                    $actual_image_name = 'TopMarx/Logo/'.$file;
                    $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                } 
                if($tmp1) {
                    $actual_image_name1 = 'TopMarx/'.$file1;
                    $s3->putObjectFile($tmp1, $bucket, $actual_image_name1, S3::ACL_PUBLIC_READ);
                } 
            }
            
            
            $data['OwnerEntityID']=$entityID;
            $data['EntityMasterID']=$EntityMasterID;
            $data['DocumentType']=$DocumentType;
            $data['DocumentTitle']=$DocumentTitle;
            $data['UserID']=$UserID;
            $data['UserDate']=date('Y-m-d h:i:s');
            $data['ActiveFlag']=$ActiveFlag;
            $data['LogoAlign']=$LogoAlign;
            if($file_flg==1) {
                $data['Logo']=$file;    
            }
                $data['charterPartyEditableFlag']=$charterPartyEditableFlag;
                $data['FinalDocumentationFlag']=$FinalDocumentationFlag;
                $data['CharterPartyApprovalStatus']=$CharterPartyApprovalStatus;
                $data['ApprovedBy']=$ApprovedBy;
                $data['Comment']=$Comment;
                $data['UserComment']=$UserComment;
                $data['UserBy']=$UserBy;
                $data['UserDateTime']=$UserDateTime;
                /* 
                if($charterPartyEditableFlag==1){
                    $data['CharterPartyPdf']=rand(1,999999).'_____'.$DocumentTitle.'.pdf';
                } else {
                    if($tmp1) {
                        $data['CharterPartyPdf']=$file1;
                    }
                }
                */
                 
            if($tmp1) {
                $data['CharterPartyPdf']=$file1;
            } else {
                //$data['CharterPartyPdf']=rand(1,999999).'_____'.$DocumentShow;
            }
                
                $data['AssFixNote']=$AssFixNote;
                $data['FixNoteTemplateType']=$StandardCustomize;
                $data['FixNoteTemplate']=$SelectFromFixNote;
                $data['ClauseType']=$clause_type;
                //print_r($data); die;
                $this->db->where('DocumentTypeID', $documentID);
                $this->db->where('CoCode', $cocode);
                return $this->db->update('udt_AUM_DocumentType_Master', $data);
        }else{
            return 0;
        }    
    } 
      
    public function get_vessel_dataByIds($id)
    {
        $this->db->select('*');
        $this->db->where('VesselID', $id);
        $this->db->where('CoCode', C_COCODE);
        $query=$this->db->get('udt_AUM_Vessel_Master');
     
        return $query->result();
    }
     
    public function change_vessel_status($id,$status)
    {
        $cocode=C_COCODE;
        //echo $id.' and '.$status; die;
        if($status=='active') {
            
            $data=array(
            'ActiveFlag'=>'1',
            'UserDate'=>date('Y-m-d H:i:s')
            );
        } else {
            $data=array(
            'ActiveFlag'=>'0',
            'UserDate'=>date('Y-m-d H:i:s')
            );
            
        }
        //print_r($data); die;
        $this->db->where('VesselID', $id);
        $this->db->where('CoCode', $cocode);
        return $this->db->update('udt_AUM_Vessel_Master', $data);
    }
    
    public function change_document_status($id,$status)
    {
        $cocode=C_COCODE;
        //echo $id.' and '.$status; die;
        if($status=='active') {
            
            $data=array(
            'ActiveFlag'=>'1',
            'UserDate'=>date('Y-m-d H:i:s')
            );
        } else {
            $data=array(
            'ActiveFlag'=>'0',
            'UserDate'=>date('Y-m-d H:i:s')
            );
            
        }
        //print_r($data); die;
        $this->db->where('DocumentTypeID', $id);
        $this->db->where('CoCode', $cocode);
        return $this->db->update('udt_AUM_DocumentType_Master', $data);
    }
     
    public function deleteVesselByIds($ids)
    {
        $t_id=trim($ids, ",");
        $ids=explode(",", $t_id);
        $cocode=C_COCODE;
        
        foreach($ids as $id) {
            $this->db->select('*');
            $this->db->where('VesselID', $id);
            $this->db->where('CoCode', $cocode);
            $query=$this->db->get('udt_AUM_Vessel_Master');
            $last_data=$query->row();
            
            $this->db->insert('udt_AUM_Vessel_Master_H', $last_data);
            
        }
        
        $this->db->where_in('VesselID', $ids);
        $this->db->where('CoCode', $cocode);
        return $this->db->delete('udt_AUM_Vessel_Master');
    }
    
    public function cloneVesselByIds($id)
    {
        //echo $id; die;
        return $this->db->query(
            "insert into cops_admin.udt_AUM_Vessel_Master( CoCode,OwnerEntityID,VesselSize,SizeGroup,CargoRangePercentage,CargoRangeFrom,CargoRangeTo,VesselComments,EntityMasterID,ActiveFlag,UserID,UserDate) 
		select CoCode,OwnerEntityID,VesselSize,SizeGroup,CargoRangePercentage,CargoRangeFrom,CargoRangeTo,VesselComments,EntityMasterID,ActiveFlag,UserID,'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AUM_Vessel_Master where VesselID=".$id
        );
    }
    
    public function cloneDocumentByIds($id)
    {
        //echo $id; die;
        return $this->db->query(
            "insert into cops_admin.udt_AUM_DocumentType_Master( CoCode, OwnerEntityID, DocumentType, DocumentTitle, EntityMasterID, ActiveFlag, UserID, UserDate) 
		select CoCode,OwnerEntityID,DocumentType,DocumentTitle,EntityMasterID,ActiveFlag,UserID,'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AUM_DocumentType_Master where DocumentTypeID=".$id
        );
    }
    /* 
    public function add_document_Details(){
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentType',$DocumentType);
    $this->db->where('DocumentTitle',$DocumentTitle);
    $this->db->where('EntityMasterID',$EntityMasterID);
    $query=$this->db->get();
    $result=$query->row();
        
    if(!$result){
             $data=array(
                'CoCode'=>C_COCODE,
                'OwnerEntityID'=>$entityID,
                'EntityMasterID'=>$EntityMasterID,
                'DocumentType'=>$DocumentType,
                'DocumentTitle'=>$DocumentTitle,
                'UserID'=>$UserID,
                'UserDate'=> date('Y-m-d h:i:sa'),
                'ActiveFlag'=>$ActiveFlag
    );
    return $this->db->insert('udt_AUM_DocumentType_Master',$data);
    }else{
    return 0;
    }
        
    }
    */
     
    public function add_document_Details()
    {
        extract($this->input->post());
        $this->db->select('*');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->where('DocumentType', $DocumentType);
        $this->db->where('DocumentTitle', $DocumentTitle);
        $this->db->where('EntityMasterID', $EntityMasterID);
        $query=$this->db->get();
        $result=$query->row();
        $file='';
        if(!$result) {
            if($AttachedLogo !='') {
                $file=$AttachedLogo;
            } else {
                $document=$_FILES['Logo'];
                $document1=$_FILES['CharterPartyPdf'];
            
                $file=rand(1, 999999).'_____'.$document['name'];
                $file1=rand(1, 999999).'_____'.$document1['name'];
                $tmp=$document['tmp_name'];
                $tmp1=$document1['tmp_name'];
            
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
                if($tmp1) {
                    $actual_image_name1 = 'TopMarx/'.$file1;
                    $s3->putObjectFile($tmp1, $bucket, $actual_image_name1, S3::ACL_PUBLIC_READ);
                } 
                $uploadfile='';
            
            }
            
        
        
            $data['CoCode']=C_COCODE;
            $data['OwnerEntityID']=$entityID;
            $data['EntityMasterID']=$EntityMasterID;
            $data['DocumentType']=$DocumentType;
            $data['DocumentTitle']=$DocumentTitle;
            $data['UserID']=$UserID;
            $data['UserDate']=date('Y-m-d h:i:sa');
            $data['ActiveFlag']=$ActiveFlag;
            $data['LogoAlign']=$LogoAlign;
            $data['Logo']=$file;    
            $data['charterPartyEditableFlag']=$charterPartyEditableFlag;
            $data['ClauseType']=$clause_type;
            $data['FinalDocumentationFlag']=$FinalDocumentationFlag;
            $data['CharterPartyApprovalStatus']=$CharterPartyApprovalStatus;
            $data['ApprovedBy']=$ApprovedBy;
            $data['Comment']=$Comment;
            $data['UserComment']=$UserComment;
            $data['UserBy']=$UserBy;
            $data['UserDateTime']=$UserDateTime;
            
            if($charterPartyEditableFlag==1) {
                $data['CharterPartyPdf']=rand(1, 999999).'_____'.$DocumentShow.'.pdf';
            }else{
                if($tmp1) {
                    $data['CharterPartyPdf']=$file1;
                }
            }
            $this->db->insert('udt_AUM_DocumentType_Master', $data);
            
            $this->db->select('*');
            $this->db->from('udt_AUM_DocumentType_Master');
            $this->db->order_by('DocumentTypeID', 'DESC');
            $query1=$this->db->get();
            return $query1->row()->DocumentTypeID;
        }else{
            return 0;
        }
        
    }
    
    public function deleteDocumentByIds($ids)
    {
        
        $t_id=trim($ids, ",");
        $ids=explode(",", $t_id);
        $cocode=C_COCODE;
        
        foreach($ids as $id) {
            $this->db->select('*');
            $this->db->where_in('DocumentTypeID', $id);
            $this->db->where('CoCode', $cocode);
            $query=$this->db->get('udt_AUM_DocumentType_Master');
            $last_data=$query->row();
            $this->db->insert('udt_AUM_DocumentType_Master_H', $last_data);
            
        }
        
        //print_r($ids); die;
        $this->db->where_in('DocumentTypeID', $ids);
        $this->db->where('CoCode', $cocode);
        return $this->db->delete('udt_AUM_DocumentType_Master');
    }
    
    public function deleteInviteeByIds($ids)
    {
        $t_id=trim($ids, ",");
        $ids=explode(",", $t_id);
        
        foreach($ids as $id) {
            $this->db->select('InviteeID,CoCode,UserGroup,InviteeStatus,InviteePeriod,DateRangeFrom,DateRangeTo,PriorityStatus,PriorityComments,UserID,UserDate,InviteeRole,PrimeRole,QuoteLimitFlag,QuoteLimitValue');
            $this->db->where('InviteeID', $id);
            $query=$this->db->get('udt_AUM_Invitee_Master');
            $last_data=$query->row();
            //print_r($last_data); die;
            $this->db->insert('udt_AUM_Invitee_Master_H', $last_data);
                
        }
        
        $this->db->where_in('InviteeID', $ids);
        return $this->db->delete('udt_AUM_Invitee_Master');
    }
    
    public function save_invitee_Details()
    {
        $cocode=C_COCODE;
        extract($this->input->post());
        
        if($QuoteLimitFlag==1) {
            $QuoteLimitValue=1;
        } else if($QuoteLimitFlag==2) {
            $QuoteLimitValue=2;
        }
        
        $BidStatusFlg=0;
        $CharterDetailsFlg=0;
        $InviteeCommentsFlg=0;
        $ConfirmationFlg=0;
        if($conf1) {
            $BidStatusFlg=1;
        }
        if($conf2) {
            $CharterDetailsFlg=1;
        }
        if($conf3) {
            $InviteeCommentsFlg=1;
        }
        if($conf4) {
            $ConfirmationFlg=1;
        }
        if($PrimeRole==1) {
            $this->db->select('udt_AUM_Invitee_Master.*');
            $this->db->from('udt_AUM_Invitee_Master');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
            $this->db->where('udt_AUM_Invitee_Master.RecordOwner', $OwnerID);
            $this->db->where('udt_UserMaster.EntityID', $entityID);
            $query2=$this->db->get();
            $rslt=$query2->result();
            
            foreach($rslt as $r){
                if($r->PrimeRole==1 && $EntityRole != $r->InviteeRole) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('PrimeRole'=>0));
                } else if($r->PrimeRole==0 && $EntityRole == $r->InviteeRole) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('PrimeRole'=>1));
                }
                if($r->QuoteLimitFlag!=$QuoteLimitFlag) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('QuoteLimitFlag'=>$QuoteLimitFlag,'QuoteLimitValue'=>$QuoteLimitValue));
                }
            }
        }
        
        $t_id=trim($ids, "_");
        $ids=explode("_", $t_id);
        $inv_arr=array();
        foreach($ids as $id){
            $this->db->select('*');
            $this->db->from('udt_AUM_Invitee_Master');
            $this->db->where('ForUserID', $id);
            $this->db->where('InviteeRole', $EntityRole);
            $this->db->where('RecordOwner', $OwnerID);
            $query=$this->db->get();
            $last_data=$query->row();
            if($last_data) {
                $this->db->select('InviteeID, CoCode,RecordOwner, UserGroup, InviteeStatus, InviteePeriod, DateRangeFrom, DateRangeTo, PriorityStatus,PriorityComments,ForUserID,UserID,UserDate,InviteeRole,PrimeRole,QuoteLimitFlag,QuoteLimitValue, BidStatusFlg, CharterDetailsFlg, InviteeCommentsFlg, ConfirmationFlg');
                $this->db->where('InviteeID', $last_data->InviteeID);
                $query=$this->db->get('udt_AUM_Invitee_Master');
                $last_data=$query->row();
                //print_r($last_data); die;
                $this->db->insert('udt_AUM_Invitee_Master_H', $last_data);
                
                $data=array(
                'CoCode'=>C_COCODE,
                'RecordOwner'=>$OwnerID,
                'InviteeRole'=>$EntityRole,
                'PrimeRole'=>$PrimeRole,
                'InviteeStatus'=>$InviteeStatus,
                'InviteePeriod'=>$InviteePeriod,
                'DateRangeFrom'=>date('Y-m-d', strtotime($DateRangeFrom)),
                'DateRangeTo'=>date('Y-m-d', strtotime($DateRangeTo)),
                'UserGroup'=>$UserGroup,
                'PriorityStatus'=>$PriorityStatus,
                'PriorityComments'=>'',
                'QuoteLimitFlag'=>$QuoteLimitFlag,
                'QuoteLimitValue'=>$QuoteLimitValue,
                'BidStatusFlg'=>$BidStatusFlg,
                'CharterDetailsFlg'=>$CharterDetailsFlg,
                'InviteeCommentsFlg'=>$InviteeCommentsFlg,
                'ConfirmationFlg'=>$ConfirmationFlg,
                'UserID'=>$UserID,
                'UserDate'=> date('Y-m-d H:i:s')
                );
                //print_r($data); die;
                $this->db->where('InviteeID', $last_data->InviteeID);
                $ret=$this->db->update('udt_AUM_Invitee_Master', $data);
                
                array_push($inv_arr, $last_data->InviteeID);
                
                for($k=0; $k<count($PriorityComments);$k++){
                    $pri_data=array(
                    'InviteeID'=>$last_data->InviteeID,
                    'ReasonForPriority'=>$PriorityComments[$k],
                    'ByUserID'=>$UserID,
                    'CreatedDateTime'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AUM_InviteePriorityStatus', $pri_data);
                }
            } else {
                $data=array(
                'CoCode'=>C_COCODE,
                'RecordOwner'=>$OwnerID,
                'InviteeRole'=>$EntityRole,
                'PrimeRole'=>$PrimeRole,
                'InviteeStatus'=>$InviteeStatus,
                'InviteePeriod'=>$InviteePeriod,
                'DateRangeFrom'=>date('Y-m-d', strtotime($DateRangeFrom)),
                'DateRangeTo'=>date('Y-m-d', strtotime($DateRangeTo)),
                'UserGroup'=>$UserGroup,
                'PriorityStatus'=>$PriorityStatus,
                'PriorityComments'=>'',
                'ForUserID'=>$id,
                'QuoteLimitFlag'=>$QuoteLimitFlag,
                'QuoteLimitValue'=>$QuoteLimitValue,
                'BidStatusFlg'=>$BidStatusFlg,
                'CharterDetailsFlg'=>$CharterDetailsFlg,
                'InviteeCommentsFlg'=>$InviteeCommentsFlg,
                'ConfirmationFlg'=>$ConfirmationFlg,
                'TermStatus'=>0,
                'UserID'=>$UserID,
                'UserDate'=> date('Y-m-d H:i:s')
                );
                //print_r($data); die;
                $ret=$this->db->insert('udt_AUM_Invitee_Master', $data);
                if($ret) {
                    $this->db->select('InviteeID');
                    $this->db->from('udt_AUM_Invitee_Master');
                    $this->db->order_by('InviteeID', 'desc');
                    $query1=$this->db->get();
                    $inv_row=$query1->row();
                    
                    array_push($inv_arr, $inv_row->InviteeID);
                    
                    for($k=0; $k<count($PriorityComments);$k++){
                        $pri_data=array(
                        'InviteeID'=>$inv_row->InviteeID,
                        'ReasonForPriority'=>$PriorityComments[$k],
                        'ByUserID'=>$UserID,
                        'CreatedDateTime'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AUM_InviteePriorityStatus', $pri_data);
                    }
                }
            }
        }
        return $inv_arr;
    }
    
    public function update_invitee()
    {
        $cocode=C_COCODE;
        extract($this->input->post());
        
        if($QuoteLimitFlag==1) {
            $QuoteLimitValue=1;
        } else if($QuoteLimitFlag==2) {
            $QuoteLimitValue=2;
        }
        $this->db->select('InviteeID, CoCode, RecordOwner, UserGroup, InviteeStatus, InviteePeriod, DateRangeFrom, DateRangeTo, PriorityStatus, PriorityComments, ForUserID, UserID, UserDate, InviteeRole, PrimeRole, QuoteLimitFlag, QuoteLimitValue, BidStatusFlg, CharterDetailsFlg, InviteeCommentsFlg, ConfirmationFlg');
        $this->db->where('InviteeID', $ids);
        $query=$this->db->get('udt_AUM_Invitee_Master');
        $last_data=$query->row();
        //print_r($last_data); die;
        $this->db->insert('udt_AUM_Invitee_Master_H', $last_data);
        
        if($PrimeRole==1) {
            $this->db->select('udt_AUM_Invitee_Master.*');
            $this->db->from('udt_AUM_Invitee_Master');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
            $this->db->where('udt_AUM_Invitee_Master.RecordOwner', $OwnerID);
            $this->db->where('udt_UserMaster.EntityID', $entityID);
            $query2=$this->db->get();
            $rslt=$query2->result();
            
            foreach($rslt as $r){
                if($r->PrimeRole==1 && $EntityRole != $r->InviteeRole) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('PrimeRole'=>0));
                } else if($r->PrimeRole==0 && $EntityRole == $r->InviteeRole) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('PrimeRole'=>1));
                }
                if($r->QuoteLimitFlag!=$QuoteLimitFlag) {
                    $this->db->where('InviteeID', $r->InviteeID);
                    $this->db->update('udt_AUM_Invitee_Master', array('QuoteLimitFlag'=>$QuoteLimitFlag,'QuoteLimitValue'=>$QuoteLimitValue));
                }
            }
        }
        $BidStatusFlg=0;
        $CharterDetailsFlg=0;
        $InviteeCommentsFlg=0;
        $ConfirmationFlg=0;
        if($conf1) {
            $BidStatusFlg=1;
        }
        if($conf2) {
            $CharterDetailsFlg=1;
        }
        if($conf3) {
            $InviteeCommentsFlg=1;
        }
        if($conf4) {
            $ConfirmationFlg=1;
        }
        
        $data=array(
        'CoCode'=>C_COCODE,
        'RecordOwner'=>$OwnerID,
        'InviteeRole'=>$EntityRole,
        'PrimeRole'=>$PrimeRole,
        'InviteeStatus'=>$InviteeStatus,
        'InviteePeriod'=>$InviteePeriod,
        'DateRangeFrom'=>date('Y-m-d', strtotime($DateRangeFrom)),
        'DateRangeTo'=>date('Y-m-d', strtotime($DateRangeTo)),
        'UserGroup'=>$UserGroup,
        'PriorityStatus'=>$PriorityStatus,
        'PriorityComments'=>'',
        'QuoteLimitFlag'=>$QuoteLimitFlag,
        'QuoteLimitValue'=>$QuoteLimitValue,
        'BidStatusFlg'=>$BidStatusFlg,
        'CharterDetailsFlg'=>$CharterDetailsFlg,
        'InviteeCommentsFlg'=>$InviteeCommentsFlg,
        'ConfirmationFlg'=>$ConfirmationFlg,
        'UserID'=>$UserID,
        'UserDate'=> date('Y-m-d H:i:s')
        );
            
        //print_r($data); die;
        $this->db->where('InviteeID', $ids);
        $ret=$this->db->update('udt_AUM_Invitee_Master', $data);
        
        for($k=0; $k<count($PriorityComments);$k++){
            $pri_data=array(
            'InviteeID'=>$ids,
            'ReasonForPriority'=>$PriorityComments[$k],
            'ByUserID'=>$UserID,
            'CreatedDateTime'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_InviteePriorityStatus', $pri_data);
        }
        
        return $ret;
    
    }
    
    public function uploadImage_invitee($inv_arr)
    {
        extract($this->input->post());
        
        if($charter_party_doc) {
            for($i=0; $i<count($inv_arr); $i++){
                $file_data = array(
                 'CoCode'=>C_COCODE,
                 'RecordOwner'=>$OwnerID,
                 'InviteeID'=>$inv_arr[$i],
                 'DocSection'=>'Invitee_Master',
                 'FileAttachName'=> $charter_party_doc,
                 'DocumentType'=> $typeofdocument,
                 'DocumentTypeID'=> $NameorTitleofdocumentattached,
                 'FileSizeKB'=> 0,
                 'FileType'=>'application/pdf',
                 'ToDisplayOwner'=>$Documenttobedisplayinauctionprocess,
                 'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee,
                 'FileComment'=>'', 
                 'UserID'=>$UserID, 
                 'CreatedDate'=>date('Y-m-d H:i:s') 
                  );
                //print_r($file_data); die;
                $res=$this->db->insert('udt_AUM_InviteeDocuments', $file_data);
            }
        }
        
        
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
                
        if($document['error'][0] !=4) {    
            for($i=0;$i<count($document['name']);$i++){
                $nar=explode(".", $document['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).$document['name'][$i];
                $tmp=$document['tmp_name'][$i];
                $filesize=$document['size'][$i];
                
                $actual_image_name = 'TopMarx/'.$file;
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                
                for($jk=0; $jk<count($inv_arr); $jk++){
                    $file_data = array(
                     'CoCode'=>C_COCODE,
                     'RecordOwner'=>$OwnerID,
                     'InviteeID'=>$inv_arr[$jk],
                     'DocSection'=>'Invitee_Master',
                     'FileAttachName'=> $file,
                     'DocumentType'=> $typeofdocument,
                     'DocumentTypeID'=> $NameorTitleofdocumentattached,
                     'FileSizeKB'=> 0,
                     'FileType'=>'application/pdf',
                     'ToDisplayOwner'=>$Documenttobedisplayinauctionprocess,
                     'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee,
                     'FileComment'=>$FIleComment[$i], 
                     'UserID'=>$UserID, 
                     'CreatedDate'=>date('Y-m-d H:i:s') 
                      );
                    //print_r($file_data); die;
                    $res=$this->db->insert('udt_AUM_InviteeDocuments', $file_data);
                }
            }
        }
        return $res;
    }
    
    public function uploadImage_invitee_1()
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
            
        //print_r($document);die;    
        if($document['error'][0] !=4) {    
            for($i=0;$i<count($document['name']);$i++){
                 $nar=explode(".", $document['type'][$i]);
                 $type=end($nar);
                 $file=rand(1, 999999).$document['name'][$i];
                 $tmp=$document['tmp_name'][$i];
                 $filesize=$document['size'][$i];
                    
                 $actual_image_name = 'TopMarx/'.$file;
                 //$s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                 $file_data = array(
                  'CoCode'=>C_COCODE,
                  'AuctionID'=>$OwnerID,
                  'LineNum'=>$ForUserID,
                  'AuctionSection'=>'invitee_master',
                  'FileName'=> $file,
                  'Title'=>$NameorTitleofdocumentattached,
                  'FileSizeKB'=>round($filesize/1024),
                  'FileType'=>$type,
                  'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
                  'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
                  'DocumentType'=>$typeofdocument, 
                  'FileComment'=>$FIleComment[$i], 
                  'UserID'=>$UserID, 
                  'CreatedDate'=>date('Y-m-d H:i:s') 
                 );
                  //print_r($file_data); die;
                 $res=$this->db->insert('udt_AUM_Documents', $file_data);
            }
        }
        return $res;
    }
    
    public function get_invitee_documentData()
    {
        $InviteeID=$this->input->post('InviteeID');
        
        $this->db->select('udt_AUM_InviteeDocuments.*, udt_AUM_Document_master.DocType, udt_AUM_Document_master.DocName, udt_UserMaster.FirstName, udt_UserMaster.LastName');
        $this->db->from('udt_AUM_InviteeDocuments');
        $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_InviteeDocuments.DocumentTypeID');
        $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_InviteeDocuments.UserID');
        $this->db->where('udt_AUM_InviteeDocuments.InviteeID', $InviteeID);
        $query=$this->db->get();
        return $query->result();
    }
    
    public function delete_invitee_document($id)
    {
        $this->db->where('DocumentID', $id);
        return $this->db->delete('udt_AUM_Documents');
    }
    
    function get_vessel_table_data($iDisplayStart = null,$iDisplayLength = null,$search = null ,$sEcho = null)
    {
        
        $this->db->select('count(*) as total');
        $this->db->from('udt_AUM_Vessel_Master');
        $query=$this->db->get();
        /*  echo $this->db->last_query();
        die;  */
        $result=$query->row();
        
        $iTotalRecords    =    $result->total;
        $iTotalDisplayRecords=$result->total;        
        
        $this->db->select('*');
        
        $this->db->from('udt_AUM_Vessel_Master');
        
        $this->db->limit($iDisplayLength, $iDisplayStart);
        
        $query    =    $this->db->get();
        $result =    $query->result();
        $aaData    =    array();
            
        foreach($result as $row){
            $aaData[]    =    array(
            $row->VesselID,
            date('d/m/y', strtotime($row->UserDate)),
            $row->VesselSize,
            $row->SizeGroup,
            $row->CargoRangePercentage,
            $row->CargoRangeFrom,
            $row->CargoRangeTo,
            $row->EntityMasterID,
            $row->ActiveFlag
            );            
        }
    
        // Due to a bug iTotalRecords and iTotalDisplayRecords values are swapped.
        $output    = array(
        "sEcho"    =>     $sEcho,
        "iTotalRecords"    =>    $iTotalRecords,
        "iTotalDisplayRecords"    =>    $iTotalDisplayRecords,
        "aaData"    =>    $aaData
        );
        
        return    $output;
    }
    
    public function entity_user_data()
    { 
        $key=$this->input->post('key');
        $entityID=$this->input->post('entityID');
        
        $this->db->select('udt_UserMaster.ID,udt_UserMaster.LoginID,udt_UserMaster.FirstName,udt_UserMaster.LastName');
        $this->db->from('udt_UserMaster', 'after');
        if($entityID) {
            $this->db->where('udt_UserMaster.EntityID', $entityID);
        }
        
        $this->db->like('udt_UserMaster.LoginID', $key);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function entity_user_data_messages()
    { 
        $key=$this->input->post('key');
        $entityID=$this->input->post('entityID');
        $MsgType=$this->input->post('MsgType');
        
        $this->db->select('udt_UserMaster.ID,udt_UserMaster.LoginID,udt_UserMaster.FirstName,udt_UserMaster.LastName');
        $this->db->from('udt_UserMaster', 'after');
        if($entityID) {
            $this->db->where('udt_UserMaster.EntityID', $entityID);
        }
        if($MsgType=='admin') {
            $this->db->where('udt_UserMaster.UserType', 'CA');
        }
        
        $this->db->like('udt_UserMaster.LoginID', $key);
        $query=$this->db->get();
        return $query->result();
        
    }
    
    public function save_message_data()
    {
        //print_r($this->input->post()); die;
        extract($this->input->post());
        $flg=0;
        if($MessageType=='sys_msg') {
            $events=$sysEvents;
            $onPage=$sysOnPage;
            $OnSection='';
        } else if($MessageType=='proc_msg') {
            $events=$procEvents;
            $onPage=$procOnPage;
            $OnSection='';
        } else if($MessageType=='alert_msg') {
            $events=$alertEvents;
            $onPage=$alertOnPage;
            $OnSection='';
        } else if($MessageType=='admin') {
            $events=$adminEvents;
            $onPage=$adminOnPage;
            $OnSection='';
            $flg=1;
        }
        
        if($flg==1) {
            for($i=0;$i<count($caid);$i++) {
                $this->db->select('*');
                $this->db->from('udt_AUM_MESSAGE_MASTER');
                $this->db->where('Events', $events);
                $this->db->where('MessageType', $MessageType);
                $this->db->where('OnPage', $onPage);
                $this->db->where('RecordOwner', $entityID);
                $this->db->where('ForUserID', $caid[$i]);
                $query=$this->db->get();
                $result=$query->row();
                if(!$result) {
                       $data = array(
                         'CoCode'=>C_COCODE,
                         'RecordOwner'=>$entityID,
                         'MessageType'=>$MessageType,
                         'Events'=>$events,
                         'OnPage'=>$onPage,
                         'OnSection'=>$OnSection,
                         'OutputFormat'=>$OnMode,
                         'Message'=>$Message,
                         'UserID'=>$User_ID,
                         'UserDate'=>date('Y-m-d H:i:s'), 
                         'EntityID'=>$EntityMasterID, 
                         'ForUserID'=>$caid[$i] 
                       );
                       $ret=$this->db->insert('udt_AUM_MESSAGE_MASTER', $data);
                }
            }
            return 1;
        } else {
            $this->db->select('*');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->where('Events', $events);
            $this->db->where('MessageType', $MessageType);
            $this->db->where('OnPage', $onPage);
            $this->db->where('RecordOwner', $entityID);
            $this->db->where('ForUserID', $UserID);
            $query=$this->db->get();
            $result=$query->row();
            if(!$result) {
                $data = array(
                'CoCode'=>C_COCODE,
                'RecordOwner'=>$entityID,
                'MessageType'=>$MessageType,
                'Events'=>$events,
                'OnPage'=>$onPage,
                'OnSection'=>$OnSection,
                'OutputFormat'=>$OnMode,
                'Message'=>$Message,
                'UserID'=>$User_ID,
                'UserDate'=>date('Y-m-d H:i:s'), 
                'EntityID'=>$EntityMasterID, 
                'ForUserID'=>$UserID 
                );
                $ret=$this->db->insert('udt_AUM_MESSAGE_MASTER', $data);
                
                return $ret;
            }else{
                return 0;
            }
        }
        
        //print_r($temp); die;
    }
    
    public function get_dtable_message_data()
    {
        
        $sEcho                =    $this->input->get('sEcho');
        $iDisplayStart        =    $this->input->get('iDisplayStart');
        $iDisplayLength        =    $this->input->get('iDisplayLength');
        $sSearch        =    $this->input->get('sSearch');
        $MessageType        =    $this->input->get('MessageType');
        $Events        =    $this->input->get('Events');
        $UserMasterID        =    $this->input->get('UserMasterID');
        $OnPage        =    $this->input->get('OnPage');
        
        $this->db->select('count(*) as total');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID = udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
        
        if($sSearch) {
            $this->db->or_like('udt_AUM_MESSAGE_MASTER.MessageType', $sSearch);    
            $this->db->or_like('udt_UserMaster.LoginID', $sSearch);
            $this->db->or_like('udt_EntityMaster.EntityName', $sSearch);
            $this->db->or_like('udt_AUM_MESSAGE_MASTER.Events', $sSearch);
        }

        if($MessageType) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', $MessageType);
        }
        
        if($Events) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', $Events);
        }
        
        if($UserMasterID) {
            $this->db->where('udt_UserMaster.ID', $UserMasterID);
        }
        
        if($OnPage) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', $OnPage);
        }
        
        $query        =    $this->db->get();
        $result        =    $query->row();
        $iTotalRecords            =    $result->total;
        $iTotalDisplayRecords    =    $result->total;        
        /* 
        $this->db->select("udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName,udt_UserMaster.LoginID");
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster','udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->join('udt_UserMaster','udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
        
        if($sSearch){
        $this->db->or_like('udt_AUM_MESSAGE_MASTER.MessageType',$sSearch);    
        $this->db->or_like('udt_UserMaster.LoginID',$sSearch);
        $this->db->or_like('udt_EntityMaster.EntityName',$sSearch);
        $this->db->or_like('udt_AUM_MESSAGE_MASTER.Events',$sSearch);
        }        
        
        if($MessageType){
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType',$MessageType);
        }
        
        if($Events){
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events',$Events);
        }
        
        if($UserMasterID){
        $this->db->where('udt_UserMaster.ID',$UserMasterID);
        }
        
        if($OnPage){
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage',$OnPage);
        }
        
        $this->db->order_by('udt_AUM_MESSAGE_MASTER.UserDate','DESC');
        //$this->db->where('udt_AU_MESSAGE_MASTER.MessageID >=', $iDisplayStart);
        //$this->db->where('udt_AU_MESSAGE_MASTER.MessageID <=', $iDisplayLength);
        
        $this->db->limit($iDisplayLength,$iDisplayStart);
        $query    =    $this->db->get();
        */
        $TotalRow=$iDisplayStart+$iDisplayLength;
         
        $sql="select * from ( select TOP $iDisplayLength * from (SELECT TOP $TotalRow COPS_Admin.udt_AUM_MESSAGE_MASTER.*, COPS_Admin.udt_EntityMaster.EntityName, COPS_Admin.udt_UserMaster.LoginID
		FROM COPS_Admin.udt_AUM_MESSAGE_MASTER
		JOIN COPS_Admin.udt_EntityMaster ON COPS_Admin.udt_EntityMaster.ID=COPS_Admin.udt_AUM_MESSAGE_MASTER.EntityID
		JOIN COPS_Admin.udt_UserMaster ON COPS_Admin.udt_UserMaster.ID=COPS_Admin.udt_AUM_MESSAGE_MASTER.ForUserID ";
        $subsql="";
        
        if($MessageType && $Events && $UserMasterID && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_UserMaster.ID='$UserMasterID' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($MessageType && $Events && $UserMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_UserMaster.ID='$UserMasterID'";
            
        }else if($MessageType && $Events && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($MessageType && $UserMasterID && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_UserMaster.ID='$UserMasterID' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($Events && $UserMasterID && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_UserMaster.ID='$UserMasterID' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($MessageType && $Events) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events'";
            
        }else if($MessageType && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType'and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($UserMasterID && $OnPage) {
            $subsql .=" where COPS_Admin.udt_UserMaster.ID='$UserMasterID' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($Events && $UserMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_UserMaster.ID='$UserMasterID'";
            
        }else if($MessageType && $UserMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType' and COPS_Admin.udt_UserMaster.ID='$UserMasterID'";
            
        }else if($Events && $OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events' and COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }else if($MessageType) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType='$MessageType'";
            
        }else if($Events) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.Events='$Events'";
            
        }else if($UserMasterID) {
            $subsql .=" where COPS_Admin.udt_UserMaster.ID='$UserMasterID'";
            
        }else if($OnPage) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.OnPage='$OnPage'";
            
        }
        
        if($sSearch) {
            $subsql .=" where COPS_Admin.udt_AUM_MESSAGE_MASTER.MessageType LIKE '%$sSearch%' or  COPS_Admin.udt_UserMaster.LoginID LIKE '%$sSearch%' or COPS_Admin.udt_EntityMaster.EntityName LIKE '%$sSearch%' or COPS_Admin.udt_AUM_MESSAGE_MASTER.Events LIKE '%$sSearch%'";
            
        }
        
        $subsql .=" ORDER BY COPS_Admin.udt_AUM_MESSAGE_MASTER.UserDate DESC ) as sub ORDER BY sub.UserDate ) as sub2 order by sub2.UserDate DESC ";
        $sql .=$subsql;
        $query    =    $this->db->query($sql);
        $result =    $query->result();
        //$this->output->enable_profiler();
        $aaData    =    array();
            
        foreach($result as $row){
            $html='';
            $userdate='';
            $view='';
            $msgtype='';
            $event='';
            $page='';
            $html='<input class="chkNumber" type="checkbox" name="arr_auction_ids[]" value="'.$row->MessageID.'">';
            $view='<a onclick="getMsg('.$row->MessageID.')">view</a>';
            $userdate=date('d-m-Y H:i:s', strtotime($row->UserDate));
            
            if($row->MessageType=='sys_msg') {
                $msgtype='System Message';
            }else if($row->MessageType=='alert_msg') {
                $msgtype='Alert Message';
            }else if($row->MessageType=='proc_msg') {
                $msgtype='Process Message';
            }
            if($row->MessageType=='sys_msg') {
                if($row->Events=='add') {
                    $event='Add';
                }else if($row->Events=='clone') {
                    $event='Clone';
                }else if($row->Events=='edit_update') {
                    $event='Edit or Update';
                }else if($row->Events=='delete') {
                    $event='Delete';
                }
                
            } else if($row->MessageType=='proc_msg') {
                if($row->Events=='1') {
                    $event='Auction commencement';
                }else if($row->Events=='2') {
                    $event='Auction responses';
                }else if($row->Events=='3') {
                    $event='Reminder';
                }else if($row->Events=='4') {
                    $event='Auction withdrawn in set up';
                }else if($row->Events=='5') {
                    $event='Auction withdrawn in main';
                }else if($row->Events=='6') {
                    $event='Auction decline by invitee';
                }else if($row->Events=='7') {
                    $event='Auction invitee short listed';
                }else if($row->Events=='8') {
                    $event='Tentative bid acceptance';
                }else if($row->Events=='9') {
                    $event='Auction bid approval';
                }else if($row->Events=='10') {
                    $event='Fixture note completed';
                }else if($row->Events=='10') {
                    $event='Charter documentation';
                }
                
            }else if($row->MessageType=='alert_msg') {
                if($row->Events=='commencement') {
                    $event='Commencement of auction';
                }else if($row->Events=='prior_commencement') {
                    $event='Prior to commencement of auction';
                }else if($row->Events=='prior_closing') {
                    $event='Prior to closing of auction';
                }else if($row->Events=='closing') {
                    $event='Closing of auction';
                }else if($row->Events=='reminder') {
                    $event='Reminder of auction';
                }
                
            }
            
            if($row->OnPage=='page_1') {
                $page='Setup Auction';
            }else if($row->OnPage=='page_2') {
                $page='Auction Main';
            }else if($row->OnPage=='page_3') {
                $page='Auction Response';
            }else if($row->OnPage=='page_4') {
                $page='Fixture Notes';
            }else if($row->OnPage=='page_5') {
                $page='Charter Documentation';
            }
            
            $aaData[]    =    array(
            $html,
            $userdate,
            $event,
            $msgtype,
            $page,
            $row->LoginID,
            $view,
            $row->EntityName
            );            
        }
    
        // Due to a bug iTotalRecords and iTotalDisplayRecords values are swapped.
        $output    = array(
        "sEcho"    =>     $sEcho,
        "iTotalRecords"    =>    $iTotalRecords,
        "iTotalDisplayRecords"    =>    $iTotalDisplayRecords,
        "aaData"    =>    $aaData
        );
        
        return $output;
        
    }
    
    public function cloneMessageByIds($id)
    {

        return $this->db->query(
            "insert into cops_admin.udt_AUM_MESSAGE_MASTER( CoCode,EntityID,MessageType,Events,OnPage,OnSection,OutputFormat,Subject,Message,ForUserID,UserID,UserDate) 
		select CoCode,EntityID,MessageType,Events,OnPage,OnSection,OutputFormat,Subject,Message,ForUserID,UserID,'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AUM_MESSAGE_MASTER where MessageID=".$id
        );
    }
    
    public function deleteMessageByIds($ids)
    {
        $t_id=trim($ids, ",");
        $ids=explode(",", $t_id);
        $cocode=C_COCODE;
        foreach($ids as $id) {
            $this->db->select('MessageID,CoCode,RecordOwner,MessageType,Events,OnPage,OnSection,OutputFormat,Subject,Message,ForUserID,UserID,UserDate,EntityID');
            $this->db->where('MessageID', $id);
            $query=$this->db->get('udt_AUM_MESSAGE_MASTER');
            $last_data=$query->row();
            //print_r($last_data); die;
            $this->db->insert('udt_AUM_MESSAGE_MASTER_H', $last_data);
        }
        //print_r($data); die;
        $this->db->where_in('MessageID', $ids);
        $this->db->where('CoCode', $cocode);
        return $this->db->delete('udt_AUM_MESSAGE_MASTER');
    }
    
    public function getMessage()
    { 
        $cocode=C_COCODE;
        $MessageID=$this->input->post('MessageID');
        //print_r($auctionID); die;
        $this->db->select("udt_AUM_MESSAGE_MASTER.Message, udt_AUM_MESSAGE_MASTER.UserID, udt_EntityMaster.EntityName");
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.CoCode', $cocode);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageID', $MessageID);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function check_msg_exist()
    {
        extract($this->input->post());
        if($MessageType=='sys_msg') {
            $events=$sysEvents;
            $onPage=$sysOnPage;
        } else if($MessageType=='proc_msg') {
            $events=$procEvents;
            $onPage=$procOnPage;
            $OnSection='';    
        } else if($MessageType=='alert_msg') {
            $events=$alertEvents;
            $onPage=$alertOnPage;
            $OnSection='';
        } else if($MessageType=='admin') {
            $events=$adminEvents;
            $onPage=$adminOnPage;
            $OnSection='';
        }
        //print_r($UserID); die;
        $this->db->select('*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('Events', $events);
        $this->db->where('MessageType', $MessageType);
        $this->db->where('OnPage', $onPage);
        $this->db->where('ForUserID', $UserID);
        $this->db->where('RecordOwner', $entityID);
        $query=$this->db->get();
        $result=$query->row();
        //print_r($result); die;
        if(!$result) {
            return 0;
        }else{
            return 1;
        }
    }
    
    public function checkUserMessageExist()
    {
        extract($this->input->post());
        
        $this->db->select('*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('ForUserID', $UserID);
        $this->db->where('RecordOwner', $EntityID);
        $query=$this->db->get();
        $result=$query->result();
        //print_r($result); die;
        if(count($result)) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function checkInviteeMessageExist()
    {
        extract($this->input->post());
        
        $this->db->select('*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('ForUserID', $UserID);
        $this->db->where('RecordOwner', $EntityID);
        $this->db->where('EntityID', $InviteeEntity);
        $query=$this->db->get();
        $result=$query->result();
        //print_r($result); die;
        if(count($result)) {
            return 1;
        } else {
            return 0;
        }
    }
    
    public function cloneUserMessages()
    {
        extract($this->input->post());
        $this->db->trans_start();
        if($InvMessageFlg==1) {
            $this->db->where('RecordOwner', $ToOwnerEntityID);
            $this->db->where('EntityID', $CopyToInvEntityID);
            $this->db->where('ForUserID', $CopyToInvUserID);
            $this->db->delete('udt_AUM_MESSAGE_MASTER');
            
            $this->db->select('*');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->where('RecordOwner', $OwnerEntityID);
            $this->db->where('EntityID', $CopyFromInvEntityID);
            $this->db->where('ForUserID', $CopyFromInvUserID);
            $qry=$this->db->get();
            $inv_result=$qry->result();
            
            foreach($inv_result as $inv_row){
                $data=array(
                  'CoCode'=>$inv_row->CoCode,
                  'EntityID'=>$CopyToInvEntityID,
                  'MessageType'=>$inv_row->MessageType,
                  'Events'=>$inv_row->Events,
                  'OnPage'=>$inv_row->OnPage,
                  'OnSection'=>$inv_row->OnSection,
                  'OutputFormat'=>$inv_row->OutputFormat,
                  'Message'=>$inv_row->Message,
                  'ForUserID'=>$CopyToInvUserID,
                  'UserID'=>$User_ID,
                  'UserDate'=>date('Y-m-d H:i:s'),
                  'RecordOwner'=>$ToOwnerEntityID
                  );
                $this->db->insert('udt_AUM_MESSAGE_MASTER', $data);
            }
            $ret=1;
            
        } else if($InvMessageFlg==2) {
            if($MessageType=='all') {
                $this->db->where('RecordOwner', $CopyEntityID);
                $this->db->where('EntityID', $CopyEntityID);
                $this->db->where('ForUserID', $CopyUserID);
                $this->db->delete('udt_AUM_MESSAGE_MASTER');
            } else if($MessageType=='sys_msg' || $MessageType=='proc_msg' || $MessageType=='alert_msg' || $MessageType=='admin') {
                $this->db->where('RecordOwner', $CopyEntityID);
                $this->db->where('EntityID', $CopyEntityID);
                $this->db->where('ForUserID', $CopyUserID);
                $this->db->where('MessageType', $MessageType);
                $this->db->delete('udt_AUM_MESSAGE_MASTER');
            }
            
            $this->db->select('*');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->where('RecordOwner', $OwnerEntityID);
            $this->db->where('EntityID', $OwnerEntityID);
            $this->db->where('ForUserID', $OwnerUserID);
            if($MessageType!='all') {
                $this->db->where('MessageType', $MessageType);
            }
            $uqry=$this->db->get();
            $usr_result=$uqry->result();
            
            foreach($usr_result as $usr_row){
                $data=array(
                'CoCode'=>$usr_row->CoCode,
                'EntityID'=>$CopyEntityID,
                'MessageType'=>$usr_row->MessageType,
                'Events'=>$usr_row->Events,
                'OnPage'=>$usr_row->OnPage,
                'OnSection'=>$usr_row->OnSection,
                'OutputFormat'=>$usr_row->OutputFormat,
                'Message'=>$usr_row->Message,
                'ForUserID'=>$CopyUserID,
                'UserID'=>$User_ID,
                'UserDate'=>date('Y-m-d H:i:s'),
                'RecordOwner'=>$CopyEntityID
                );
                $this->db->insert('udt_AUM_MESSAGE_MASTER', $data);
            }
            
            if($OwnerEntityID !=$CopyEntityID) {
                if($MessageType=='all') {
                    $where=" RecordOwner=$OwnerEntityID and EntityID !=$OwnerEntityID and EntityID !=$CopyEntityID";
                } else if($MessageType=='sys_msg' || $MessageType=='proc_msg' || $MessageType=='alert_msg' || $MessageType=='admin') {
                    $where=" RecordOwner=$OwnerEntityID and EntityID !=$OwnerEntityID and EntityID !=$CopyEntityID and MessageType='$MessageType'";
                }
                
                $this->db->select('*');
                $this->db->from('udt_AUM_MESSAGE_MASTER');
                $this->db->where($where);
                $inv_query=$this->db->get();
                $inv_msgs=$inv_query->result();
                
                $this->db->select('*');
                $this->db->from('udt_AUM_Invitee_Master');
                $this->db->where('RecordOwner', $CopyEntityID);
                $inv_qry=$this->db->get();
                $inv_result=$inv_qry->result();
                
                $inv_user_array = array();
                
                foreach($inv_result as $inv){
                    if(!in_array($inv->ForUserID, $res_arr_values)) {
                        $res_arr_values[] = $inv->ForUserID;
                    }
                }
                
                foreach($inv_msgs as $inv_row){
                    if(in_array($inv_row->ForUserID, $res_arr_values)) {
                        $this->db->where('RecordOwner', $CopyEntityID);
                        $this->db->where('EntityID', $inv_row->EntityID);
                        $this->db->where('ForUserID', $inv_row->ForUserID);
                        $this->db->where('MessageType', $inv_row->MessageType);
                        $this->db->where('Events', $inv_row->Events);
                        $this->db->where('OnPage', $inv_row->OnPage);
                        $this->db->delete('udt_AUM_MESSAGE_MASTER');
                        
                        $data=array(
                        'CoCode'=>$inv_row->CoCode,
                        'EntityID'=>$inv_row->EntityID,
                        'MessageType'=>$inv_row->MessageType,
                        'Events'=>$inv_row->Events,
                        'OnPage'=>$inv_row->OnPage,
                        'OnSection'=>$inv_row->OnSection,
                        'OutputFormat'=>$inv_row->OutputFormat,
                        'Message'=>$inv_row->Message,
                        'ForUserID'=>$inv_row->ForUserID,
                        'UserID'=>$User_ID,
                        'UserDate'=>date('Y-m-d H:i:s'),
                        'RecordOwner'=>$CopyEntityID
                        );
                        $this->db->insert('udt_AUM_MESSAGE_MASTER', $data);
                    }
                }
            }
            $ret=1;
        }
        
        $this->db->trans_complete();
        return $ret;
    }
    
    public function checkInviteeEntity()
    {
        extract($this->input->post());
        //print_r($this->input->post()); die;
        $this->db->select('udt_AUM_Invitee_Master.*,udt_UserMaster.EntityID');
        $this->db->from('udt_AUM_Invitee_Master');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitee_Master.ForUserID');
        $this->db->where('RecordOwner', $OwnerEntityID);
        $this->db->where('udt_UserMaster.EntityID', $InvEntity);
        $inv_qry=$this->db->get();
        $inv_row=$inv_qry->row();
        //print_r($inv_row); die;
        if($inv_row) {
            return 1;
        } else {
            return 2;
        }
        
        
    }
    
    public function update_message_data()
    {
        $cocode=C_COCODE;
        extract($this->input->post());
        if($MessageType=='sys_msg') {
            $events=$sysEvents;
            $onPage=$sysOnPage;
            //$OnSection=$sysOnSection;
        } else if($MessageType=='proc_msg') {
            $events=$procEvents;
            $onPage=$procOnPage;
            $OnSection='';    
        } else if($MessageType=='alert_msg') {
            $events=$alertEvents;
            $onPage=$alertOnPage;
            $OnSection='';
        } else if($MessageType=='admin') {
            $events=$adminEvents;
            $onPage=$adminOnPage;
            $OnSection='';
        }
        $this->db->select('*');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->where('Events', $events);
        $this->db->where('MessageType', $MessageType);
        $this->db->where('OnPage', $onPage);
        $this->db->where('ForUserID', $UserID);
        $this->db->where('RecordOwner', $entityID);
        $query=$this->db->get();
        $result=$query->row();
        //print_r($result); die;
        if($result) {
            $this->db->select('*');
            $this->db->where('MessageID', $result->MessageID);
            $query=$this->db->get('udt_AUM_MESSAGE_MASTER');
            $last_data=$query->row();
            $this->db->insert('udt_AUM_MESSAGE_MASTER_H', $last_data);
            //echo $id; die;
            $data = array(
            'RecordOwner'=>$entityID,
            'MessageType'=>$MessageType,
            'Events'=>$events,
            'OnPage'=>$onPage,
            'OnSection'=>$OnSection,
            'OutputFormat'=>$OnMode,
            'Message'=>$Message,
            'UserID'=>$User_ID,
            'UserDate'=>date('Y-m-d H:i:s'),
            'EntityID'=>$EntityMasterID, 
            'ForUserID'=>$UserID 
                      );
            //print_r($data); die;
            $this->db->where('MessageID', $result->MessageID);
            return $this->db->update('udt_AUM_MESSAGE_MASTER', $data);
        }else{
            $this->db->select('*');
            $this->db->where('MessageID', $messageID);
            $query=$this->db->get('udt_AUM_MESSAGE_MASTER');
            $last_data=$query->row();
            $this->db->insert('udt_AUM_MESSAGE_MASTER_H', $last_data);
            //echo $id; die;
            $data = array(
            'RecordOwner'=>$entityID,
            'MessageType'=>$MessageType,
            'Events'=>$events,
            'OnPage'=>$onPage,
            'OnSection'=>$OnSection,
            'OutputFormat'=>$OnMode,
            'Message'=>$Message,
            'UserID'=>$User_ID,
            'UserDate'=>date('Y-m-d H:i:s'),
            'EntityID'=>$EntityMasterID, 
            'ForUserID'=>$UserID 
                      );
            //print_r($data); die;
            $this->db->where('MessageID', $messageID);
            return $this->db->update('udt_AUM_MESSAGE_MASTER', $data);
        }
        
            
    }
    
    public function messageById()
    {
        $id=$this->input->post('id');
        //echo $id; die;
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,EM.EntityName as EntityName, OWN.EntityName as OwnEntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster as EM', 'EM.ID = udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->join('udt_EntityMaster as OWN', 'OWN.ID = udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('MessageID', $id);
        $query=$this->db->get();
        $msgData=$query->row();
        return $msgData;

    }
    
    public function get_dtable_vessel()
    {
        
        $sEcho                =    $this->input->get('sEcho');
        $iDisplayStart        =    $this->input->get('iDisplayStart');
        $iDisplayLength        =    $this->input->get('iDisplayLength');
        $sSearch        =    $this->input->get('sSearch');
        $VesselSizeFrom        =    $this->input->get('VesselSizeFrom');
        $VesselSizeTo        =    $this->input->get('VesselSizeTo');
        $SizeGroup        =    $this->input->get('SizeGroup');
        $EntityMasterID        =    $this->input->get('EntityMasterID');
        
        $this->db->select('count(*) as total');
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->join('udt_EntityMaster as owner', 'owner.ID = udt_AUM_Vessel_Master.OwnerEntityID');
        $this->db->join('udt_EntityMaster as entity', 'entity.ID = udt_AUM_Vessel_Master.EntityMasterID');
        if($sSearch=='A' || $sSearch=='a' || $sSearch=='Ac' || $sSearch=='ac') {
            $this->db->or_like('udt_AUM_Vessel_Master.ActiveFlag', '1');
        }else if($sSearch=='I' || $sSearch=='i' || $sSearch=='In' || $sSearch=='In' || $sSearch=='Ina' || $sSearch=='ina') {
            $this->db->or_like('udt_AUM_Vessel_Master.ActiveFlag', '0');
        }else{
            if($sSearch) {
                $this->db->or_like('udt_AUM_Vessel_Master.VesselSize', $sSearch);    
                $this->db->or_like('udt_AUM_Vessel_Master.SizeGroup', $sSearch);    
                $this->db->or_like('udt_AUM_Vessel_Master.CargoRangePercentage', $sSearch);    
                $this->db->or_like('entity.EntityName', $sSearch);    
            }
        }
        if($VesselSizeFrom) {
            $this->db->where('udt_AUM_Vessel_Master.VesselSize >=', $VesselSizeFrom);    
        }
        if($VesselSizeTo) {
            $this->db->where('udt_AUM_Vessel_Master.VesselSize <=', $VesselSizeTo);    
        }
        if($SizeGroup) {
            $this->db->where('udt_AUM_Vessel_Master.SizeGroup <=', $SizeGroup);    
        }
        if($EntityMasterID) {
            $this->db->where('udt_AUM_Vessel_Master.EntityMasterID', $EntityMasterID);    
        }
        
        $query        =    $this->db->get();
        
        $result        =    $query->row();
        $iTotalRecords            =    $result->total;
        $iTotalDisplayRecords    =    $result->total;        
        /* 
        $this->db->select("udt_AUM_Vessel_Master.*,owner.EntityName as ownerEntityName,entity.EntityName as entityEntityName");
        $this->db->from('udt_AUM_Vessel_Master');
        $this->db->join('udt_EntityMaster as owner','owner.ID = udt_AUM_Vessel_Master.OwnerEntityID');
        $this->db->join('udt_EntityMaster as entity','entity.ID = udt_AUM_Vessel_Master.EntityMasterID');
        if($sSearch=='A' || $sSearch=='a' || $sSearch=='Ac' || $sSearch=='ac' || $sSearch=='Act' || $sSearch=='act'){
        $this->db->or_like('udt_AUM_Vessel_Master.ActiveFlag','1');
        }else if($sSearch=='I' || $sSearch=='i' || $sSearch=='In' || $sSearch=='In' || $sSearch=='Ina' || $sSearch=='ina'){
        $this->db->or_like('udt_AUM_Vessel_Master.ActiveFlag','0');
        }else{
        if($sSearch){
        $this->db->or_like('udt_AUM_Vessel_Master.VesselSize',$sSearch);    
        $this->db->or_like('udt_AUM_Vessel_Master.SizeGroup',$sSearch);    
        $this->db->or_like('udt_AUM_Vessel_Master.CargoRangePercentage',$sSearch);    
        $this->db->or_like('entity.EntityName',$sSearch);    
        }
        }
        
        if($VesselSizeFrom){
        $this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }
        if($VesselSizeTo){
        $this->db->where('udt_AUM_Vessel_Master.VesselSize <=',$VesselSizeTo);    
        }
        if($SizeGroup){
        $this->db->where('udt_AUM_Vessel_Master.SizeGroup <=',$SizeGroup);    
        }
        if($EntityMasterID){
        $this->db->where('udt_AUM_Vessel_Master.EntityMasterID',$EntityMasterID);    
        }
        $this->db->order_by('udt_AUM_Vessel_Master.UserDate','DESC');
        //$this->db->limit($iDisplayLength,$iDisplayStart);
        $query    =    $this->db->get(); 
        */
        $TotalRow=$iDisplayStart+$iDisplayLength;
         
        $sql="select * from (select TOP $iDisplayLength * from(SELECT TOP $TotalRow COPS_Admin.udt_AUM_Vessel_Master.*, owner.EntityName as ownerEntityName, entity.EntityName as entityEntityName
		FROM COPS_Admin.udt_AUM_Vessel_Master
		JOIN COPS_Admin.udt_EntityMaster as owner ON owner.ID = COPS_Admin.udt_AUM_Vessel_Master.OwnerEntityID
		JOIN COPS_Admin.udt_EntityMaster as entity ON entity.ID = COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID ";
        $subsql="";
        if($VesselSizeFrom && $VesselSizeTo && $SizeGroup && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup' and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $VesselSizeTo && $SizeGroup) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup'";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $VesselSizeTo && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $SizeGroup && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup' and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeTo && $SizeGroup && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup' and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $VesselSizeTo) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($SizeGroup && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup' and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeTo && $EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeTo && $SizeGroup) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo and COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup'";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeFrom) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize >=$VesselSizeFrom";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($VesselSizeTo) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize <=$VesselSizeTo";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($SizeGroup) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.SizeGroup ='$SizeGroup'";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }else if($EntityMasterID) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.EntityMasterID=$EntityMasterID";
            //$this->db->where('udt_AUM_Vessel_Master.VesselSize >=',$VesselSizeFrom);    
        }
        
        if($sSearch ) {
            $subsql .=" where COPS_Admin.udt_AUM_Vessel_Master.VesselSize LIKE '%$sSearch%' or COPS_Admin.udt_AUM_Vessel_Master.SizeGroup LIKE '%$sSearch%' or COPS_Admin.udt_AUM_Vessel_Master.CargoRangePercentage LIKE '%$sSearch%' or entity.EntityName LIKE '%$sSearch%'";
                
        }
        
        $subsql .=" ORDER BY COPS_Admin.udt_AUM_Vessel_Master.UserDate DESC ) as sub ORDER BY sub.UserDate )as sub2 order by sub2.UserDate DESC ";
        $sql .=$subsql;
        $query    =    $this->db->query($sql); 
        $result =    $query->result();
        //$this->output->enable_profiler();
        $aaData    =    array();
            
        foreach($result as $row){
            $html='';
            $vesseldate='';
            if($row->ActiveFlag==1) {
                $ActiveFlag='Active';
            }else{
                $ActiveFlag='Inactive';
            }
            $html='<input class="chkNumber" type="checkbox" name="vesselID[]" value="'.$row->VesselID.'">';
            $vesseldate=date('d-m-Y H:i:s', strtotime($row->UserDate));
            
            $aaData[]    =    array(
            $html,
            $vesseldate,
            $row->VesselSize,
            $row->SizeGroup,
            $row->CargoRangePercentage,
            $row->CargoRangeFrom,
            $row->CargoRangeTo,
            $row->entityEntityName,
            $ActiveFlag,
            );            
        }
    
        // Due to a bug iTotalRecords and iTotalDisplayRecords values are swapped.
        $output    = array(
        "sEcho"    =>     $sEcho,
        "iTotalRecords"    =>    $iTotalRecords,
        "iTotalDisplayRecords"    =>    $iTotalDisplayRecords,
        "aaData"    =>    $aaData
        );
        
        return $output;
        
    }
    
    public function get_dtable_document()
    {
        
        $sEcho                =    $this->input->get('sEcho');
        $iDisplayStart        =    $this->input->get('iDisplayStart');
        $iDisplayLength        =    $this->input->get('iDisplayLength');
        $sSearch        =    $this->input->get('sSearch');
        $document_type        =    $this->input->get('document_type');
        $document_title        =    $this->input->get('document_title');
        $EntityMasterID        =    $this->input->get('EntityMasterID');
        
        $this->db->select('count(*) as total');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->join('udt_EntityMaster as owner', 'owner.ID = udt_AUM_DocumentType_Master.OwnerEntityID');
        $this->db->join('udt_EntityMaster as entity', 'entity.ID = udt_AUM_DocumentType_Master.EntityMasterID');
        
        if($sSearch) {
            $this->db->or_like('udt_AUM_DocumentType_Master.DocumentType', $sSearch);    
            $this->db->or_like('udt_AUM_DocumentType_Master.DocumentTitle', $sSearch);    
            $this->db->or_like('entity.EntityName', $sSearch);    
        }
        if($document_type) {
            $this->db->where('udt_AUM_DocumentType_Master.DocumentType', $document_type);
        }
        if($document_title) {
            $this->db->where('udt_AUM_DocumentType_Master.DocumentTitle', $document_title);
        }
        if($EntityMasterID) {
            $this->db->where('udt_AUM_DocumentType_Master.EntityMasterID', $EntityMasterID);
        }
        $query        =    $this->db->get();
        
        $result        =    $query->row();
        $iTotalRecords            =    $result->total;
        $iTotalDisplayRecords    =    $result->total;        
        
        $TotalRow=$iDisplayStart+$iDisplayLength;
         
        $sql="select * from ( select TOP $iDisplayLength * from ( SELECT TOP $TotalRow COPS_Admin.udt_AUM_DocumentType_Master.*, owner.EntityName as ownerEntityName, entity.EntityName as entityEntityName FROM COPS_Admin.udt_AUM_DocumentType_Master
		JOIN COPS_Admin.udt_EntityMaster as owner ON owner.ID = COPS_Admin.udt_AUM_DocumentType_Master.OwnerEntityID
		JOIN COPS_Admin.udt_EntityMaster as entity ON entity.ID = COPS_Admin.udt_AUM_DocumentType_Master.EntityMasterID ";
        $subsql="";
        if($document_type && $document_title && $EntityMasterID) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentType='$document_type' and cops_admin.udt_AUM_DocumentType_Master.DocumentTitle='$document_title' and cops_admin.udt_AUM_DocumentType_Master.EntityMasterID='$EntityMasterID'";
        }else if($document_type && $document_title) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentType='$document_type' and cops_admin.udt_AUM_DocumentType_Master.DocumentTitle='$document_title'";
        }else if($document_type && $EntityMasterID) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentType='$document_type' and cops_admin.udt_AUM_DocumentType_Master.EntityMasterID='$EntityMasterID'";
        }else if($document_title && $EntityMasterID) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentTitle='$document_title' and cops_admin.udt_AUM_DocumentType_Master.EntityMasterID='$EntityMasterID'";
        }else if($document_type) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentType='$document_type'";
        }else if($document_title) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentTitle='$document_title'";
        }else if($EntityMasterID) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.EntityMasterID='$EntityMasterID'";
        }
        
        if($sSearch) {
            $subsql .=" where cops_admin.udt_AUM_DocumentType_Master.DocumentType LIKE '%$sSearch%' or cops_admin.udt_AUM_DocumentType_Master.DocumentTitle LIKE '%$sSearch%' or entity.EntityName LIKE '%$sSearch%'";
            //$this->db->like('udt_AUM_DocumentType_Master.DocumentType',$sSearch);    
        }
        
        $subsql .=" ORDER BY COPS_Admin.udt_AUM_DocumentType_Master.UserDate DESC ) as sub ORDER BY sub.UserDate ) as sub2 order by sub2.UserDate DESC ";
        $sql .=$subsql;
        $query    =    $this->db->query($sql); 
        $result =    $query->result();
        //$this->output->enable_profiler();
        $aaData    =    array();
            
        foreach($result as $row){
            $html='';
            $documentdate='';
            if($row->ActiveFlag==1) {
                $ActiveFlag='Active';
            }else{
                $ActiveFlag='Inactive';
            }
            $html='<input class="chkNumber" type="checkbox" name="documentID[]" value="'.$row->DocumentTypeID.'">';
            $documentdate=date('d-m-Y H:i:s', strtotime($row->UserDate));
            
            $aaData[]    =    array(
            $html,
            $documentdate,
            $row->DocumentType,
            $row->DocumentTitle,
            $row->entityEntityName,
            $ActiveFlag,
            $row->ownerEntityName
            );            
        }
    
        // Due to a bug iTotalRecords and iTotalDisplayRecords values are swapped.
        $output    = array(
        "sEcho"    =>     $sEcho,
        "iTotalRecords"    =>    $iTotalRecords,
        "iTotalDisplayRecords"    =>    $iTotalDisplayRecords,
        "aaData"    =>    $aaData
        );
        
        return $output;
        
    }
    
    public function get_dtable_invitee()
    {
        $cocode=C_COCODE;
        $sEcho                =    $this->input->get('sEcho');
        $iDisplayStart        =    $this->input->get('iDisplayStart');
        $iDisplayLength        =    $this->input->get('iDisplayLength');
        $sSearch        =    $this->input->get('sSearch');
        $EntityMasterID        =    $this->input->get('EntityMasterID');
        $UserGroup        =    $this->input->get('UserGroup');
        $PriorityStatus        =    $this->input->get('PriorityStatus');
        
        $this->db->select('count(*) as total');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
        $this->db->where('CoCode', $cocode);
         
        if($sSearch) {
            $this->db->or_like('udt_UserMaster.UserGroup', $sSearch);    
            $this->db->or_like('udt_UserMaster.PriorityStatus', $sSearch);    
        }
        
    
        if($EntityMasterID) {
            $this->db->where('udt_UserMaster.EntityID', $EntityMasterID);
        } 
        if($UserGroup) {
            $this->db->where('udt_UserMaster.UserGroup', $UserGroup);
        } 
        if($PriorityStatus) {
            $this->db->where('udt_UserMaster.PriorityStatus', $PriorityStatus);
        } 
        $query        =    $this->db->get();
        
        $result        =    $query->row();
        $iTotalRecords            =    $result->total;
        $iTotalDisplayRecords    =    $result->total;        
        
        $sql="select * from (select TOP 10 * from ( SELECT TOP 20 COPS_Admin.udt_UserMaster.ID, COPS_Admin.udt_UserMaster.UserGroup, COPS_Admin.udt_UserMaster.InviteeStatus, COPS_Admin.udt_UserMaster.InviteePeriod, COPS_Admin.udt_UserMaster.DateRangeFrom, COPS_Admin.udt_UserMaster.DateRangeTo, COPS_Admin.udt_UserMaster.PriorityStatus, COPS_Admin.udt_UserMaster.PriorityComments, COPS_Admin.udt_UserMaster.UserID, COPS_Admin.udt_UserMaster.UserDate, COPS_Admin.udt_EntityMaster.EntityName
		FROM COPS_Admin.udt_UserMaster
		JOIN COPS_Admin.udt_EntityMaster ON COPS_Admin.udt_EntityMaster.ID=COPS_Admin.udt_UserMaster.EntityID
		WHERE CoCode =  '$cocode' ";
        
        
        $subsql="";
        if($EntityMasterID) {
            $subsql .=" and COPS_Admin.udt_UserMaster.EntityID = $EntityMasterID";
            //$this->db->where('udt_UserMaster.EntityID',$EntityMasterID);
        } 
        if($UserGroup) {
            $subsql .=" and COPS_Admin.udt_UserMaster.UserGroup = '$UserGroup'";
            //$this->db->where('udt_UserMaster.UserGroup',$UserGroup);
        } 
        if($PriorityStatus) {
            $subsql .=" and COPS_Admin.udt_UserMaster.PriorityStatus = '$PriorityStatus'";
            //$this->db->where('udt_UserMaster.PriorityStatus',$PriorityStatus);
        } 
        if($sSearch) {
            $subsql .=" and COPS_Admin.udt_UserMaster.UserGroup LIKE '%$sSearch%' or COPS_Admin.udt_UserMaster.PriorityStatus LIKE '%$sSearch%' or COPS_Admin.udt_EntityMaster.EntityName LIKE '%$sSearch%' ";
            
        }
        $subsql .=" ORDER BY COPS_Admin.udt_UserMaster.UserDate DESC ) as sub ORDER BY sub.UserDate ) as sub2 order by sub2.UserDate DESC ";
        $sql .=$subsql;
        $query    =    $this->db->query($sql);  
        $result =    $query->result();
        //$this->output->enable_profiler();
        $aaData    =    array();
            
        foreach($result as $row){
            $html='';
            $inviteedate='';
            $period1='';
            $period2='';
            if($row->InviteeStatus==1) {
                $ActiveFlag='Active';
            }else{
                $ActiveFlag='Inactive';
            }
            if($row->InviteePeriod=='0') {
                $period1='Infinite';
                $period2='Infinite';
            } else {
                $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
                $period2=date('d-m-Y', strtotime($row->DateRangeTo));
            }
            
            $html='<input class="chkNumber" type="checkbox" name="inviteeID[]" value="'.$row->ID.'">';
            $inviteedate=date('d-m-Y H:i:s', strtotime($row->UserDate));
            
            $aaData[]    =    array(
            $html,
            $inviteedate,
            $row->EntityName,
            $ActiveFlag,
            $period1,
            $period2,
            $row->UserGroup,
            $row->PriorityStatus
            );            
        }
    
        // Due to a bug iTotalRecords and iTotalDisplayRecords values are swapped.
        $output    = array(
        "sEcho"    =>     $sEcho,
        "iTotalRecords"    =>    $iTotalRecords,
        "iTotalDisplayRecords"    =>    $iTotalDisplayRecords,
        "aaData"    =>    $aaData
        );
        
        return $output;
        
    }
    
    public function getMessageData()
    {
        $cocode=C_COCODE;
        $MessageType=$this->input->get('MessageType');
        $Events=$this->input->get('Events');
        $UserMasterID=$this->input->get('UserMasterID');
        $OnPage=$this->input->get('OnPage');
        $EntityID=$this->input->get('EID');
        
        $this->db->select("udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName,udt_UserMaster.LoginID,udt_UserMaster.FirstName,udt_UserMaster.LastName");
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
        
        if($MessageType) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', $MessageType);
        }
        
        if($Events) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', $Events);
        }
        
        if($UserMasterID) {
            $this->db->where('udt_UserMaster.ID', $UserMasterID);
        }
        
        if($OnPage) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', $OnPage);
        }
        
        if($EntityID) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
        }
        
        $this->db->order_by('udt_AUM_MESSAGE_MASTER.UserDate', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    /* public function all_entity_user_data(){ 
    $key=$this->input->post('key');
    $entityID=$this->input->post('entityID');
        
    $this->db->select('udt_UserMaster.ID,udt_UserMaster.LoginID');
    $this->db->from('udt_UserMaster');
    //$this->db->join('udt_EntityMaster','udt_EntityMaster.ID=udt_UserMaster.EntityID');
    if($entityID){
    $this->db->where('udt_UserMaster.EntityID',$entityID);
    }
    $this->db->like('udt_UserMaster.LoginID',$key);
    $query=$this->db->get();
    return $query->result();
        
    } */
    
    public function get_security_section()
    { 
        $page=$this->input->post('page');
        $this->db->select('ActivityID,ActivityPage,ActivityName');
        $this->db->from('udt_AUM_Activites');
        $this->db->where('ActivityPage', $page);
        $this->db->order_by('OrderNo', 'asc');
        $query=$this->db->get();
        return $query->result(); 
    }
    
    public function get_security_page()
    { 
        $page='MainPage';
        $this->db->select('ActivityID,ActivityPage,ActivityName');
        $this->db->from('udt_AUM_Activites');
        $this->db->where('ActivityPage', $page);
        $this->db->order_by('OrderNo', 'asc');
        $query=$this->db->get();
        return $query->result(); 
    }
    
    public function getUserSection()
    {
        $userID=$this->input->post('userid');
        $MainSection=$this->input->post('MainSection');
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->result(); 
        return $result;
    }
    
    
    public function save_user_add_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->AddFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('AddFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            }
            else{
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('AddFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        }else{
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'AddFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    
    public function save_user_edit_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->EditFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('EditFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            }
            else{
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('EditFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        } else {
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'EditFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    public function save_user_delete_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->DeleteFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('DeleteFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            }
            else{
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('DeleteFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        }else{
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'DeleteFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    public function save_user_clone_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->CloneFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('CloneFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            }
            else{
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('CloneFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        } else {
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'CloneFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    public function save_user_view_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->ViewFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('ViewFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            } else {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('ViewFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        } else {
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'ViewFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    public function save_user_search_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSection', $role);
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $query=$this->db->get();
        $result=$query->row(); 
        //print_r($result);
        if($result) {
            if($result->SearchFlag=='1') {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('SearchFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=0;
            } else {
                $this->db->where('udt_AU_UserActivites.UserActivityID', $result->UserActivityID);
                $this->db->update('udt_AU_UserActivites', array('SearchFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                $flag=1;
            }
        } else {
            $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'SearchFlag'=>1,'PageSection'=>$role,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
            $flag=1;
        }
        return $flag;
    }
    
    public function save_user_all_add_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $flagstatus=$this->input->post('flag');
        $Section=$this->input->post('Section');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        $role1=trim($role, "_");
        $roles=explode("_", $role1);
        //print_r($roles); die;
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $this->db->where_in('udt_AU_UserActivites.PageSection', $roles);
        $query=$this->db->get();
        $result=$query->result(); 
        //print_r($result); die;
        foreach($roles as $newrole){
            $flag=1;
            foreach($result as $row){
                if($newrole==$row->PageSection) {
                    if($flagstatus==1) {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('AddFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    } else {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('AddFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }
                    $flag=0;
                }    
            }
            if($flag==1) {
                if($flagstatus==1) {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'AddFlag'=>1,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                } else {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'AddFlag'=>0,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }
            }
        }
        return $flag; 
    }
    
    public function save_user_all_edit_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $flagstatus=$this->input->post('flag');
        $Section=$this->input->post('Section');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        $role1=trim($role, "_");
        $roles=explode("_", $role1);
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $this->db->where_in('udt_AU_UserActivites.PageSection', $roles);
        $query=$this->db->get();
        $result=$query->result(); 
        
        foreach($roles as $newrole){
            $flag=1;
            foreach($result as $row){
                if($newrole==$row->PageSection) {
                    if($flagstatus==1) {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('EditFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }else{
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('EditFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }
                    $flag=0;
                }    
            }
            if($flag==1) {
                if($flagstatus==1) {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'EditFlag'=>1,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }else{
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'EditFlag'=>0,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }
            }
        }
        return $flag; 
    }
    
    public function save_user_all_delete_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $flagstatus=$this->input->post('flag');
        $Section=$this->input->post('Section');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        
        $role1=trim($role, "_");
        $roles=explode("_", $role1);
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $this->db->where_in('udt_AU_UserActivites.PageSection', $roles);
        $query=$this->db->get();
        $result=$query->result(); 
        
        foreach($roles as $newrole){
            $flag=1;
            foreach($result as $row){
                if($newrole==$row->PageSection) {
                    if($flagstatus==1) {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('DeleteFlag'=>1,'CreatedByUser'=>$FromUserID, 'CreatedDate'=>date('Y-m-d H:i:s')));
                    }else{
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('DeleteFlag'=>0,'CreatedByUser'=>$FromUserID, 'CreatedDate'=>date('Y-m-d H:i:s')));
                    }
                    $flag=0;
                }    
            }
            if($flag==1) {
                if($flagstatus==1) {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'DeleteFlag'=>1,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID, 'CreatedDate'=>date('Y-m-d H:i:s')));
                }else{
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'DeleteFlag'=>0,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID, 'CreatedDate'=>date('Y-m-d H:i:s')));
                }
            }
        }
        return $flag; 
    }
    
    public function save_user_all_view_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $flagstatus=$this->input->post('flag');
        $Section=$this->input->post('Section');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        $role1=trim($role, "_");
        $roles=explode("_", $role1);
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $this->db->where_in('udt_AU_UserActivites.PageSection', $roles);
        $query=$this->db->get();
        $result=$query->result(); 
        
        foreach($roles as $newrole){
            $flag=1;
            foreach($result as $row){
                if($newrole==$row->PageSection) {
                    if($flagstatus==1) {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('ViewFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }else{
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('ViewFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }
                    $flag=0;
                }    
            }
            if($flag==1) {
                if($flagstatus==1) {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'ViewFlag'=>1,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }else{
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'ViewFlag'=>0,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }
            }
        }
        return $flag; 
    }
    
    public function save_user_all_search_role()
    {
        $role=$this->input->post('role');
        $userID=$this->input->post('userID');
        $flagstatus=$this->input->post('flag');
        $Section=$this->input->post('Section');
        $MainSection=$this->input->post('MainSection');
        $FromUserID=$this->input->post('FromUserID');
        $role1=trim($role, "_");
        $roles=explode("_", $role1);
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $MainSection);
        $this->db->where('udt_AU_UserActivites.UserID', $userID);
        $this->db->where_in('udt_AU_UserActivites.PageSection', $roles);
        $query=$this->db->get();
        $result=$query->result(); 
        
        foreach($roles as $newrole){
            $flag=1;
            foreach($result as $row){
                
                if($newrole==$row->PageSection) {
                    if($flagstatus==1) {
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('SearchFlag'=>1,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }else{
                        $this->db->where('udt_AU_UserActivites.UserActivityID', $row->UserActivityID);
                        $this->db->update('udt_AU_UserActivites', array('SearchFlag'=>0,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                    }
                    $flag=0;
                }
            }
            if($flag==1) {
                if($flagstatus==1) {
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'SearchFlag'=>1,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }else{
                    $this->db->insert('udt_AU_UserActivites', array('PageSectionFlag'=>$MainSection,'SearchFlag'=>0,'PageSection'=>$newrole,'UserID'=>$userID,'CreatedByUser'=>$FromUserID,'CreatedDate'=>date('Y-m-d H:i:s')));
                }
            }
        }
        return $flag; 
    }
    
    public function get_entity_users()
    {
        $entityID=$this->input->post('entityID');
        $this->db->select('*');
        $this->db->from('udt_UserMaster');
        //$this->db->join('udt_AddressMaster','udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID',Left);
        if($entityID) {
            $this->db->where('EntityID', $entityID);
        }
        //$this->db->order_by("udt_UserMaster.LoginID", "asc");
        $query=$this->db->get();
        return $query->result();
    }
    
    public function clone_user_security()
    {
        $Section1=$this->input->post('Section');
        $UserMasterIDTo=$this->input->post('UserMasterIDTo');
        $UserMasterIDFrom=$this->input->post('UserMasterIDFrom');
        $FromUserID=$this->input->post('FromUserID');
        //print_r($_POST); die;
        $where='';
        if($Section1=='1') {
            $Section='Auction';
            $where="ActivityPage='$Section' or ActivityName='Cargo Set Up'";
        } else if($Section1=='2') {
            $Section='Response';
            $where="ActivityPage='$Section' or ActivityName='Cargo Set Up (Quotes)'";
        } else if($Section1=='3') {
            $Section='AuctionMain';
            $where="ActivityPage='$Section' or ActivityName='Charter Party (FN)'";
        } else if($Section1=='4') {
            $where="ActivityName='Messages'";
        } else if($Section1=='5') {
            $where="ActivityName='Email (FN And C/P)'";
        } else if($Section1=='6') {
            $where="ActivityName='Import CSV'";
        } else if($Section1=='7') {
            $where="ActivityName='Audit'";
        } else if($Section1=='8') {
            $where="ActivityName='Admin >> Broker Signing Authority'";
        } else if($Section1=='9') {
            $where="ActivityName='Admin >> Business Process'";
        } else if($Section1=='10') {
            $where="ActivityName='Admin >> Business Process Rules'";
        } else if($Section1=='11') {
            $where="ActivityName='Admin >> Document Store'";
        } else if($Section1=='12') {
            $where="ActivityName='Admin >> Document Type'";
        } else if($Section1=='13') {
            $where="ActivityName='Admin >> Fix note template'";
        } else if($Section1=='14') {
            $where="ActivityName='Admin >> Help Text'";
        } else if($Section1=='15') {
            $where="ActivityName='Admin >> Invitee Master'";
        } else if($Section1=='16') {
            $where="ActivityName='Admin >> Master Data >> Associated Entity'";
        } else if($Section1=='17') {
            $where="ActivityName='Admin >> Master Data >> Parent Entity'";
        } else if($Section1=='18') {
            $where="ActivityName='Admin >> Master Data >> Entity Users'";
        } else if($Section1=='19') {
            $where="ActivityName='Admin >> Master Data >> New Users'";
        } else if($Section1=='20') {
            $where="ActivityName='Admin >> Message Master'";
        } else if($Section1=='21') {
            $where="ActivityName='Admin >> Notification'";
        } else if($Section1=='22') {
            $where="ActivityName='Admin >> Rating Setup'";
        } else if($Section1=='23') {
            $where="ActivityName='Admin >> Terms and Conditions'";
        } else if($Section1=='24') {
            $where="ActivityName='Admin >> Version Control'";
        } else if($Section1=='25') {
            $where="ActivityName='Admin >> Vessel Grouping Frt Diff'";
        } else if($Section1=='26') {
            $Section='Dashboard';
            $where="ActivityPage='$Section' or ActivityName='Dashboard'";
        } else if($Section1=='27') {
            $where="ActivityName='Admin >> Custom Templates'";
        }
        
        $this->db->select('ActivityName');
        $this->db->from('udt_AUM_Activites');
        if($where) {
            $this->db->where($where);
        }
        
        $query=$this->db->get();
        $result=$query->result();
        //print_r($result); die;        
        $rolesarray= array();
        if($result) {
            $roles ='';
            foreach($result as $row){
                array_push($rolesarray, $row->ActivityName);
                $roles .="'".$row->ActivityName."',";
            }
            
            $roles1=trim($roles, ",");
            
            $this->db->where('UserID', $UserMasterIDTo);
            $this->db->where_in('PageSection', $rolesarray);
            $this->db->delete('udt_AU_UserActivites');
            
            return $query2 = $this->db->query("insert into cops_admin.udt_AU_UserActivites ( PageSectionFlag, PageSection, AddFlag, EditFlag, DeleteFlag, ViewFlag, CloneFlag, SearchFlag, UserID, CreatedByUser, CreatedDate) select PageSectionFlag, PageSection, AddFlag, EditFlag, DeleteFlag, ViewFlag, CloneFlag, SearchFlag, '".$UserMasterIDTo."','".$FromUserID."','".date('Y-m-d H:i:s')."' from cops_admin.udt_AU_UserActivites where UserID='".$UserMasterIDFrom."' and PageSection IN (".$roles1.")"); 
        } else {
            return -1;
        }  
    }
    
    public function getSecurityData()
    {
        $UserMasterID=$this->input->get('UserMasterID');
        $EntityMasterID=$this->input->get('EntityMasterID');
        $EntityID=$this->input->get('EID');
        $sql="select count(*),t.UserID,t.PageSectionFlag,t.FirstName,t.LastName,t.EntityName from ( select cops_admin.udt_AU_UserActivites.UserID, cops_admin.udt_AU_UserActivites.PageSectionFlag, cops_admin.udt_UserMaster.FirstName, cops_admin.udt_UserMaster.LastName, cops_admin.udt_EntityMaster.EntityName from cops_admin.udt_AU_UserActivites LEFT join cops_admin.udt_UserMaster on cops_admin.udt_UserMaster.ID=cops_admin.udt_AU_UserActivites.UserID LEFT join cops_admin.udt_EntityMaster on cops_admin.udt_EntityMaster.ID=cops_admin.udt_UserMaster.EntityID";
        if($EntityID) {
            if($UserMasterID) {
                $sql .=" where cops_admin.udt_AU_UserActivites.UserID='$UserMasterID' and cops_admin.udt_EntityMaster.ID='$EntityID' ";
            }else {
                $sql .=" where cops_admin.udt_EntityMaster.ID='$EntityID' ";
            }
        }else{
            if($UserMasterID && $EntityMasterID) {
                $sql .=" where cops_admin.udt_AU_UserActivites.UserID='$UserMasterID' and cops_admin.udt_EntityMaster.ID='$EntityMasterID' ";
            }else if($UserMasterID) {
                $sql .=" where cops_admin.udt_AU_UserActivites.UserID='$UserMasterID' ";
            }else if($EntityMasterID) {
                $sql .=" where cops_admin.udt_EntityMaster.ID='$EntityMasterID' ";
            }
        }
        $sql .=" ) as t group by t.UserID,t.PageSectionFlag,t.FirstName,t.LastName,t.EntityName order by t.UserID desc";
        $query2 = $this->db->query($sql);
        return $query2->result();     

    }
    
    public function getSecurityDataByUser($UserID,$PageSectionFlag)
    {
        $this->db->select('udt_AU_UserActivites.*, udt_UserMaster.FirstName, udt_UserMaster.LastName');
        $this->db->from('udt_AU_UserActivites');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_UserActivites.CreatedByUser');
        $this->db->where('udt_AU_UserActivites.UserID', $UserID);
        if($PageSectionFlag) {
            $this->db->where('udt_AU_UserActivites.PageSectionFlag', '1');
        } else {
            $this->db->where('udt_AU_UserActivites.PageSectionFlag', '0');
        }
        $this->db->order_by('udt_AU_UserActivites.CreatedDate', 'desc');
        $qry=$this->db->get();
        return $qry->row();
    }
    
    public function get_user_entity()
    {
        $userid=$this->input->post('userid');
        $this->db->select('udt_UserMaster.ID as UID,udt_UserMaster.LoginID, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_EntityMaster.ID as EID,udt_EntityMaster.EntityName');
        $this->db->from('udt_UserMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_UserMaster.EntityID');
        $this->db->where('udt_UserMaster.ID', $userid);
        $query=$this->db->get();
        return $query->row();
        
    }
    
    public function get_user_security()
    {
        $userid=$this->input->post('id');
        $security=$this->input->post('security');
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        $this->db->where('udt_AU_UserActivites.PageSectionFlag', $security);
        $this->db->where('udt_AU_UserActivites.UserID', $userid);
        $query=$this->db->get();
        $result=$query->result(); 
        return $result;
    }
    
    public function get_auction_page_secutity($userid)
    {
        
        $this->db->select('*');
        $this->db->from('udt_AU_UserActivites');
        //$this->db->where('udt_AU_UserActivites.PageSectionFlag',$security);
        //$this->db->where('udt_AU_UserActivites.PageSection',$page);
        $this->db->where('udt_AU_UserActivites.UserID', $userid);
        $query=$this->db->get();
        return $query->result(); 
         
    }
    
    public function getUserName()
    {
        $userid=$this->input->post('id');
        $this->db->select('udt_UserMaster.ID as UID,udt_UserMaster.LoginID, udt_UserMaster.FirstName, udt_UserMaster.LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('udt_UserMaster.ID', $userid);
        $query=$this->db->get();
        return $query->row();
        
    }
    
    public function get_entity_name()
    {
        $userid=$this->input->post('UserID');
        $this->db->select('udt_EntityMaster.ID as EID,udt_EntityMaster.EntityName');
        $this->db->from('udt_EntityMaster');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.EntityID=udt_EntityMaster.ID');
        $this->db->where('udt_UserMaster.ID', $userid);
        $query=$this->db->get();
        return $query->row();
    
    }
    
    public function get_DocumentType_Master()
    {
        extract($this->input->post());
        $this->db->select('*');
        $this->db->from('udt_AUM_DocumentType_Master');
        $this->db->where('DocumentType', $DocumentType);
        $this->db->where('DocumentTitle', $DocumentTitle);
        $this->db->where('EntityMasterID', $EntityMasterID);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function get_all_document_master_type()
    { 
     
        $EID=$this->input->post('EID');
        $DocType=$this->input->post('key');
        
        //print_r($EID); die;
        $this->db->select('Distinct DocType');
        $this->db->from('udt_AUM_Document_master');
        $this->db->like('DocType', $DocType, 'after');
        if($EID) {
            $this->db->where('RecoredOwner', $EID);    
        }
        $query=$this->db->get();
        return $query->result();
    }
    
    
    public function all_shipowner_entity()
    { 
        $key=$this->input->post('key');
        $this->db->select('ID,EntityName,Description,udt_Mapping_EntityTypes.EntityTypeID as etid');
        $this->db->from('udt_EntityMaster');
        $this->db->join('udt_Mapping_EntityTypes', 'udt_Mapping_EntityTypes.EntityMasterID=udt_EntityMaster.ID');
        $this->db->like('EntityName', $key);
        $this->db->where('udt_Mapping_EntityTypes.EntityTypeID', '5');
        $query=$this->db->get();
        return $query->result();
        
    }
    public function all_shipbroker_entity()
    { 
        $key=$this->input->post('key');
        $this->db->select('ID,EntityName,Description,udt_Mapping_EntityTypes.EntityTypeID as etid');
        $this->db->from('udt_EntityMaster');
        $this->db->join('udt_Mapping_EntityTypes', 'udt_Mapping_EntityTypes.EntityMasterID=udt_EntityMaster.ID');
        $this->db->like('EntityName', $key);
        $this->db->where('udt_Mapping_EntityTypes.EntityTypeID', '6');
        $query=$this->db->get();
        return $query->result();
        
    }
    
    
}


