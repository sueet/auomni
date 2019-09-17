<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class cargo_model extends CI_Model {
function __construct()
{
    parent::__construct();        
        
} 
    
    
public function get_all_vcps()
{
    $key=$this->input->post('key');
    $this->db->select('ID, name, entityid, VoyageCharterType, CharterPartyType,DateTime');
    $this->db->from("udt_CP_CharterPartyMaster WITH (NOLOCK)");
    $this->db->where('entityid', '9129');
    $this->db->like('Name', $key, 'left');
    $this->db->order_by('id');
    $query = $this->db->get();    
    return $query->result();
}
    
public function get__CharterData()
{
    $id=$this->input->post('id');
    $this->db->select('SalesCOAID, COAID');
    $this->db->from('udt_CP_CharterPartyMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
        
    $id=$this->input->post('id');
    $this->db->select('AgreementReference');
    $this->db->from('udt_COA_AgreementProfile');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function save__1__role()
{
    extract($this->input->post());
        
    $this->db->select('AuctionID');
    $this->db->from("udt_AU_Auctions");
    $this->db->where('AuctionId', $AuctionId);
    $this->db->where('OwnerEntityID', $EntityID);
    $this->db->where('CoCode', C_COCODE);
    $query = $this->db->get();
    $rr=$query->result();
        
    if (empty($rr)) { 
    } else {
          return;
    }
        
    $data=array(
                'CoCode'=>C_COCODE,
                'AuctionId'=>$AuctionId,
                'ActiveFlag'=>1,
                'StatusFlag'=>"DRAFT",
                'OwnerEntityID'=>$EntityID,
                'auctionStatus'=>'P',
                'AuctionersRole'=>$AuctionersRole,
                'MessageFlag'=>'1',
                'RecordStatus'=>'2',
                'UserID'=>$UserID,
                'CountryID'=>$CountryID,
                'SignDateFlg'=>$SignDateFlg,
                'UserSignDate'=>date('Y-m-d H:i:s', strtotime($UserSignDate)),
                'MsgDate'=>date('Y-m-d H:i:s'),
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret= $this->db->insert('udt_AU_Auctions', $data);
            
    if($ret) {
        $this->db->query(
            "insert into cops_admin.udt_AU_Auctions_H 
			(CoCode, AuctionID, ActiveFlag, OwnerEntityID, AuctionersRole, StatusFlag, SelectFrom, ContractType, COAReference, SalesAgreementReference, ShipmentReferenceID, CharterComments, UserID, UserDate, auctionStatus, auctionExtendedStatus, AuctionStatusDate, AuctionReleaseDate, MessageFlag, MsgDate, RowStatus, RecordStatus, ModelFunction, ModelNumber, CountryID, SignDateFlg, UserSignDate) 
			select CoCode, AuctionID, ActiveFlag, OwnerEntityID, AuctionersRole, StatusFlag, SelectFrom, ContractType, COAReference, SalesAgreementReference, ShipmentReferenceID, CharterComments, UserID, UserDate, auctionStatus, auctionExtendedStatus, AuctionStatusDate, AuctionReleaseDate, MessageFlag, MsgDate, '1', RecordStatus, ModelFunction, ModelNumber, CountryID, SignDateFlg, UserSignDate 
			from cops_admin.udt_AU_Auctions where AuctionID='".$AuctionId."' and OwnerEntityID='".$EntityID."' "
        );
            
        
    }
    return $ret;
}
    
public function upload_image($linenum)
{
    if($linenum=='') {    
        $linenum=1;
    } else {    
        $linenum++;    
    }
    extract($this->input->post());
        
    if($attachedpdf) {
        $attached_data = array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$auctionId,
        'LineNum'=>$linenum,
        'AuctionSection'=>'cp',
        'FileName'=> $attachedpdf,
        'Title'=>$NameorTitleofdocumentattached,
        'FileSizeKB'=>0,
        'FileType'=>'application/pdf',
        'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
        'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
        'DocumentType'=>$typeofdocument,
        'UserID'=>$UserID, 
        'CreatedDate'=>Date('Y-m-d H:i:s'),
        'FileComment'=>'' 
        );
        $this->db->insert('udt_AUM_Documents', $attached_data);
            
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
    include_once APPPATH.'third_party/image_check.php';  // getExtension Method 
        
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
                
            $file_data = array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$auctionId,
            'LineNum'=>$linenum,
            'AuctionSection'=>'cp',
            'FileName'=> $file,
            'Title'=>$NameorTitleofdocumentattached,
            'FileSizeKB'=>round($filesize/1024),
            'FileType'=>$type,
            'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
            'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
            'DocumentType'=>$typeofdocument,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s'),
            'FileComment'=>$FIleComment[$i] 
            );
                    
            $this->db->insert('udt_AUM_Documents', $file_data);
        }
    }
        
} 
        
public function upload_image_edit($linenum,$AuctionID)
{
    extract($this->input->post());
    $document=$_FILES['upload_file'];
        
    if($attachedpdf) {
        $attached_data = array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionID,
        'LineNum'=>$linenum,
        'AuctionSection'=>'cp',
        'FileName'=> $attachedpdf,
        'Title'=>$NameorTitleofdocumentattached,
        'FileSizeKB'=>0,
        'FileType'=>'application/pdf',
        'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
        'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
        'DocumentType'=>$typeofdocument,
        'UserID'=>$UserID, 
        'CreatedDate'=>date('Y-m-d H:i:s'),
        'FileComment'=>'' 
        );
                
        $this->db->insert('udt_AUM_Documents', $attached_data);
            
    }
        
        
        
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
            $ext=getExtension($document['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {
                $nar=explode(".", $document['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document['name'][$i];
                $tmp=$document['tmp_name'][$i];
                $filesize=$document['size'][$i];
                        
                $actual_image_name = 'TopMarx/'.$file;
                $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                        
                $file_data = array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$AuctionID,
                'LineNum'=>$linenum,
                'AuctionSection'=>'cp',
                'FileName'=> $file,
                'Title'=>$NameorTitleofdocumentattached,
                'FileSizeKB'=>round($filesize/1024),
                'FileType'=>$type,
                'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
                'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
                'DocumentType'=>$typeofdocument,
                'UserID'=>$UserID, 
                'CreatedDate'=>date('Y-m-d H:i:s'),
                'FileComment'=>$FIleComment[$i]
                );
                          
                $this->db->insert('udt_AUM_Documents', $file_data);
            }
        }
    }
} 

public function getauctionData()
{ 
    $AuctionID=$this->input->post('auctionID');
        
    $this->db->select('udt_AU_Cargo.*,udt_CargoMaster.*,lp.PortName as lpDescription');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->where('CoCode', C_COCODE);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_cargo_details()
{
    $AuctionID=$this->input->post('AuctionID');
    $Cargoline=$this->input->post('Cargoline');
        
    $this->db->select('udt_AU_Cargo.*, udt_CargoMaster.Code, p.PortName as pdesc, ldt1.code as ldtCode, lft.Code as ftCode,lft.Description as ftDescription, cnr.Code as cnrCode');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as p', 'p.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_Cargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_Cargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_Cargo.LpNorTendering', 'left');
    $this->db->where('udt_AU_Cargo.CoCode', C_COCODE);
    $this->db->where('udt_AU_Cargo.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Cargo.LineNum', $Cargoline);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function get_bac_details()
{
    $AuctionID=$this->input->post('AuctionID');
    $Cargoline=$this->input->post('Cargoline');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('udt_AU_BAC.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BAC.CargoLineNum', $Cargoline);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function get_bac_html_details($Cargoline)
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionId');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('udt_AU_BAC.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BAC.CargoLineNum', $Cargoline);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function get_bac_html_fulldetails()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionId');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('udt_AU_BAC.AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getauctionData1()
{ 
    $AuctionID=$this->input->post('auctionID');
    $ids=$this->input->post('ids');
    $id=explode("_", $ids);
    $this->db->select('udt_AU_Cargo.CargoID, udt_AU_Cargo.CargoQtyMT, udt_CargoMaster.Code, p.PortName as pdesc, udt_AU_Cargo.LpLaycanStartDate, udt_AU_Cargo.LpLaycanEndDate');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as p', 'p.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->where('CoCode', C_COCODE);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where_in('LineNum', $id);
    $query=$this->db->get();
    return $query->result();
}
    
    
public function getCargoData()
{ 
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->row();
    return $data;
}
    
public function getlinenum()
{
    $AuctionID=$this->input->post('auctionId');
    $this->db->select('MAX(LineNum) as linenum');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->row();
    return $data;
}
    
public function cloneauctionDetails($data,$linenum)
{
    $AuctionID=$this->input->post('auctionId');
    $UserID=$this->input->post('UserID');
    if($linenum=='') {    
        $linenum=1;
    } else {    
        $linenum++;    
    }
    $this->db->query(
        "insert into cops_admin.udt_AUM_Documents (CoCode,AuctionID,AuctionSection,LineNum,FileName,Title,FileSizeKB,FileType,ToDisplay,ToDisplayInvitee,DocumentType,UserID,CreatedDate)
		select CoCode,'".$AuctionID."',AuctionSection,'".$linenum."',FileName,Title,FileSizeKB,FileType,ToDisplay,ToDisplayInvitee,DocumentType,".$UserID.",'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AUM_Documents where LineNum='".$data->LineNum."' and AuctionID='".$AuctionID."' and AuctionSection='cp' "
    );
        
    $this->db->query(
        "insert into cops_admin.udt_AU_BAC (AuctionID,TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,CargoLineNum,UserID,UserDate)
		select '".$AuctionID."',TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,'".$linenum."',".$UserID.",'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AU_BAC where CargoLineNum='".$data->LineNum."' and AuctionID='".$AuctionID."'"
    );
    $RowStatus='4';
    $this->db->query(
        "insert into cops_admin.udt_AU_BAC_H (BAC_ID,	AuctionID,TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,CargoLineNum,RowStatus,UserID,UserDate)
		select BAC_ID,'".$AuctionID."',TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,'".$linenum."','".$RowStatus."',".$UserID.",'".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AU_BAC where CargoLineNum='".$data->LineNum."' and AuctionID='".$AuctionID."'"
    );
        
    $data_h=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$data->AuctionID,
    'LineNum'=>$linenum,
    'ActiveFlag'=>'1',
    'SelectFrom'=>$data->SelectFrom,
    'CargoQtyMT'=>$data->CargoQtyMT,
    'CargoLoadedBasis'=>$data->CargoLoadedBasis,
    'CargoLimitBasis'=>$data->CargoLimitBasis,
    'ToleranceLimit'=>$data->ToleranceLimit,
    'UpperLimit'=>$data->UpperLimit,
    'LowerLimit'=>$data->LowerLimit,
    'MaxCargoMT'=>$data->MaxCargoMT,
    'MinCargoMT'=>$data->MinCargoMT,
    'LoadPort'=>$data->LoadPort,
    'LpLaycanStartDate'=>$data->LpLaycanStartDate,
    'LpLaycanEndDate'=>$data->LpLaycanEndDate,
    'LpPreferDate'=>$data->LpPreferDate,
    'ExpectedLpDelayDay'=>$data->ExpectedLpDelayDay,
    'ExpectedLpDelayHour'=>$data->ExpectedLpDelayHour,
    'LoadingTerms'=>$data->LoadingTerms,
    'LoadingRateMT'=>$data->LoadingRateMT,
    'LoadingRateUOM'=>$data->LoadingRateUOM,
    'LpMaxTime'=>$data->LpMaxTime,
    'LpLaytimeType'=>$data->LpLaytimeType,
    'LpCalculationBasedOn'=>$data->LpCalculationBasedOn,
    'LpTurnTime'=>$data->LpTurnTime,
    'LpPriorUseTerms'=>$data->LpPriorUseTerms,
    'LpLaytimeBasedOn'=>$data->LpLaytimeBasedOn,
    'LpCharterType'=>$data->LpCharterType,
    'LpNorTendering'=>$data->LpNorTendering,
    'LpStevedoringTerms'=>$data->LpStevedoringTerms,
    'CargoInternalComments'=>$data->CargoInternalComments,
    'CargoDisplayComments'=>$data->CargoDisplayComments,
    'ExceptedPeriodFlg'=>$data->ExceptedPeriodFlg,
    'NORTenderingPreConditionFlg'=>$data->NORTenderingPreConditionFlg,
    'NORAcceptancePreConditionFlg'=>$data->NORAcceptancePreConditionFlg,
    'OfficeHoursFlg'=>$data->OfficeHoursFlg,
    'LaytimeCommencementFlg'=>$data->LaytimeCommencementFlg,
    'RowStatus'=>'4',
    'BACFlag'=>$data->BACFlag,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
    $this->db->insert('udt_AU_Cargo_H', $data_h);
     
    $data1=array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$data->AuctionID,
                'LineNum'=>$linenum,
                'ActiveFlag'=>'1',
                'SelectFrom'=>$data->SelectFrom,
                'CargoQtyMT'=>$data->CargoQtyMT,
                'CargoLoadedBasis'=>$data->CargoLoadedBasis,
                'CargoLimitBasis'=>$data->CargoLimitBasis,
                'ToleranceLimit'=>$data->ToleranceLimit,
                'UpperLimit'=>$data->UpperLimit,
                'LowerLimit'=>$data->LowerLimit,
                'MaxCargoMT'=>$data->MaxCargoMT,
                'MinCargoMT'=>$data->MinCargoMT,
                'LoadPort'=>$data->LoadPort,
                'LpLaycanStartDate'=>$data->LpLaycanStartDate,
                'LpLaycanEndDate'=>$data->LpLaycanEndDate,
                'LpPreferDate'=>$data->LpPreferDate,
                'ExpectedLpDelayDay'=>$data->ExpectedLpDelayDay,
                'ExpectedLpDelayHour'=>$data->ExpectedLpDelayHour,
                'LoadingTerms'=>$data->LoadingTerms,
                'LoadingRateMT'=>$data->LoadingRateMT,
                'LoadingRateUOM'=>$data->LoadingRateUOM,
                'LpMaxTime'=>$data->LpMaxTime,
                'LpLaytimeType'=>$data->LpLaytimeType,
                'LpCalculationBasedOn'=>$data->LpCalculationBasedOn,
                'LpTurnTime'=>$data->LpTurnTime,
                'LpPriorUseTerms'=>$data->LpPriorUseTerms,
                'LpLaytimeBasedOn'=>$data->LpLaytimeBasedOn,
                'LpCharterType'=>$data->LpCharterType,
                'LpNorTendering'=>$data->LpNorTendering,
                'LpStevedoringTerms'=>$data->LpStevedoringTerms,
                'CargoInternalComments'=>$data->CargoInternalComments,
                'CargoDisplayComments'=>$data->CargoDisplayComments,
                'ExceptedPeriodFlg'=>$data->ExceptedPeriodFlg,
                'NORTenderingPreConditionFlg'=>$data->NORTenderingPreConditionFlg,
                'NORAcceptancePreConditionFlg'=>$data->NORAcceptancePreConditionFlg,
                'OfficeHoursFlg'=>$data->OfficeHoursFlg,
                'LaytimeCommencementFlg'=>$data->LaytimeCommencementFlg,
                'BACFlag'=>$data->BACFlag,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_Cargo', $data1);
         
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $data->AuctionID);
    $this->db->order_by('CargoID', 'desc');
    $qry1=$this->db->get();
    $cargo_row=$qry1->row();
        
    if($data->ExceptedPeriodFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_ExceptedPeriods');
        $this->db->where('CargoID', $data->CargoID);
        $qry12=$this->db->get();
        $excepted_result=$qry12->result();
        foreach($excepted_result as $except_row){
            $excepted_data=array(
            'AuctionID'=>$cargo_row->AuctionID,
            'CargoID'=>$cargo_row->CargoID,
            'EventID'=>$except_row->EventID,
            'LaytimeCountsOnDemurrageFlg'=>$except_row->LaytimeCountsOnDemurrageFlg,
            'LaytimeCountsFlg'=>$except_row->LaytimeCountsFlg,
            'TimeCountingFlg'=>$except_row->TimeCountingFlg,
            'ExceptedPeriodComment'=>$except_row->ExceptedPeriodComment,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_ExceptedPeriods', $excepted_data);
        }
    }
        
    if($data->NORTenderingPreConditionFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_NORTenderingPreConditions');
        $this->db->where('CargoID', $data->CargoID);
        $qry13=$this->db->get();
        $tendering_result=$qry13->result();
        foreach($tendering_result as $tendering_row){
            $tendering_data=array(
            'AuctionID'=>$cargo_row->AuctionID,
            'CargoID'=>$cargo_row->CargoID,
            'CreateNewOrSelectListFlg'=>$tendering_row->CreateNewOrSelectListFlg,
            'NORTenderingPreConditionID'=>$tendering_row->NORTenderingPreConditionID,
            'NewNORTenderingPreCondition'=>$tendering_row->NewNORTenderingPreCondition,
            'StatusFlag'=>$tendering_row->StatusFlag,
            'TenderingPreConditionComment'=>$tendering_row->TenderingPreConditionComment,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_NORTenderingPreConditions', $tendering_data);
        }
    }
        
    if($data->NORAcceptancePreConditionFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_NORAcceptancePreConditions');
        $this->db->where('CargoID', $data->CargoID);
        $qry14=$this->db->get();
        $acceptance_result=$qry14->result();
        foreach($acceptance_result as $acceptance_row){
            $acceptance_data=array(
            'AuctionID'=>$cargo_row->AuctionID,
            'CargoID'=>$cargo_row->CargoID,
            'CreateNewOrSelectListFlg'=>$acceptance_row->CreateNewOrSelectListFlg,
            'NORAcceptancePreConditionID'=>$acceptance_row->NORAcceptancePreConditionID,
            'NewNORAcceptancePreCondition'=>$acceptance_row->NewNORAcceptancePreCondition,
            'StatusFlag'=>$acceptance_row->StatusFlag,
            'AcceptancePreConditionComment'=>$acceptance_row->AcceptancePreConditionComment,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_NORAcceptancePreConditions', $acceptance_data);
        }
    }
        
    if($data->OfficeHoursFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_OfficeHours');
        $this->db->where('CargoID', $data->CargoID);
        $qry15=$this->db->get();
        $office_hours_result=$qry15->result();
        foreach($office_hours_result as $office_rows){
            $office_data=array(
            'AuctionID'=>$cargo_row->AuctionID,
            'CargoID'=>$cargo_row->CargoID,
            'DateFrom'=>$office_rows->DateFrom,
            'DateTo'=>$office_rows->DateTo,
            'TimeFrom'=>$office_rows->TimeFrom,
            'TimeTo'=>$office_rows->TimeTo,
            'IsLastEntry'=>$office_rows->IsLastEntry,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_OfficeHours', $office_data);
        }
    }
        
    if($data->LaytimeCommencementFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_LaytimeCommencement');
        $this->db->where('CargoID', $data->CargoID);
        $qry15=$this->db->get();
        $laytime_result=$qry15->result();
        foreach($laytime_result as $laytime_row){
            $commence_data=array(
            'AuctionID'=>$cargo_row->AuctionID,
            'CargoID'=>$cargo_row->CargoID,
            'DayFrom'=>$laytime_row->DayFrom,
            'DayTo'=>$laytime_row->DayTo,
            'TimeFrom'=>$laytime_row->TimeFrom,
            'TimeTo'=>$laytime_row->TimeTo,
            'TurnTime'=>$laytime_row->TurnTime,
            'TurnTimeExpire'=>$laytime_row->TurnTimeExpire,
            'LaytimeCommenceAt'=>$laytime_row->LaytimeCommenceAt,
            'LaytimeCommenceAtHour'=>$laytime_row->LaytimeCommenceAtHour,
            'SelectDay'=>$laytime_row->SelectDay,
            'TimeCountsIfOnDemurrage'=>$laytime_row->TimeCountsIfOnDemurrage,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_LaytimeCommencement', $commence_data);
        }
    }
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_CargoDisports');
        $this->db->where('CargoID', $data->CargoID);
        $qry11=$this->db->get();
        $disportResult=$qry11->result();
            
        foreach($disportResult as $d){
            $d_data=array(
            'CargoID'=>$cargo_row->CargoID,
            'AuctionID'=>$d->AuctionID,
            'DisPort'=>$d->DisPort,
            'DpArrivalStartDate'=>$d->DpArrivalStartDate,
            'DpArrivalEndDate'=>$d->DpArrivalEndDate,
            'DpPreferDate'=>$d->DpPreferDate,
            'DischargingTerms'=>$d->DischargingTerms,
            'DischargingRateMT'=>$d->DischargingRateMT,
            'DischargingRateUOM'=>$d->DischargingRateUOM,
            'DpMaxTime'=>$d->DpMaxTime,
            'DpLaytimeType'=>$d->DpLaytimeType,
            'DpCalculationBasedOn'=>$d->DpCalculationBasedOn,
            'DpTurnTime'=>$d->DpTurnTime,
            'DpPriorUseTerms'=>$d->DpPriorUseTerms,
            'DpLaytimeBasedOn'=>$d->DpLaytimeBasedOn,
            'DpCharterType'=>$d->DpCharterType,
            'DpNorTendering'=>$d->DpNorTendering,
            'DpStevedoringTerms'=>$d->DpStevedoringTerms,
            'ExpectedDpDelayDay'=>$d->ExpectedDpDelayDay,
            'ExpectedDpDelayHour'=>$d->ExpectedDpDelayHour,
            'DpExceptedPeriodFlg'=>$d->DpExceptedPeriodFlg,
            'DpNORTenderingPreConditionFlg'=>$d->DpNORTenderingPreConditionFlg,
            'DpNORAcceptancePreConditionFlg'=>$d->DpNORAcceptancePreConditionFlg,
            'DpOfficeHoursFlg'=>$d->DpOfficeHoursFlg,
            'DpLaytimeCommencementFlg'=>$d->DpLaytimeCommencementFlg,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports', $d_data);
                
            $this->db->select('*');
            $this->db->from('udt_AU_CargoDisports');
            $this->db->where('CargoID', $cargo_row->CargoID);
            $this->db->order_by('CD_ID', 'desc');
            $qry11=$this->db->get();
            $dis_row=$qry11->row();
                
            $data=array(
            'CD_ID'=>$dis_row->CD_ID,
            'CargoID'=>$cargo_row->CargoID,
            'AuctionID'=>$d->AuctionID,
            'DisPort'=>$d->DisPort,
            'DpArrivalStartDate'=>$d->DpArrivalStartDate,
            'DpArrivalEndDate'=>$d->DpArrivalEndDate,
            'DpPreferDate'=>$d->DpPreferDate,
            'DischargingTerms'=>$d->DischargingTerms,
            'DischargingRateMT'=>$d->DischargingRateMT,
            'DischargingRateUOM'=>$d->DischargingRateUOM,
            'DpMaxTime'=>$d->DpMaxTime,
            'DpLaytimeType'=>$d->DpLaytimeType,
            'DpCalculationBasedOn'=>$d->DpCalculationBasedOn,
            'DpTurnTime'=>$d->DpTurnTime,
            'DpPriorUseTerms'=>$d->DpPriorUseTerms,
            'DpLaytimeBasedOn'=>$d->DpLaytimeBasedOn,
            'DpCharterType'=>$d->DpCharterType,
            'DpNorTendering'=>$d->DpNorTendering,
            'DpStevedoringTerms'=>$d->DpStevedoringTerms,
            'ExpectedDpDelayDay'=>$d->ExpectedDpDelayDay,
            'ExpectedDpDelayHour'=>$d->ExpectedDpDelayHour,
            'DpExceptedPeriodFlg'=>$d->DpExceptedPeriodFlg,
            'DpNORTenderingPreConditionFlg'=>$d->DpNORTenderingPreConditionFlg,
            'DpNORAcceptancePreConditionFlg'=>$d->DpNORAcceptancePreConditionFlg,
            'DpOfficeHoursFlg'=>$d->DpOfficeHoursFlg,
            'DpLaytimeCommencementFlg'=>$d->DpLaytimeCommencementFlg,
            'RowStatus'=>4,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports_H', $data);
                
            if($d->DpExceptedPeriodFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpExceptedPeriods');
                $this->db->where('DisportID', $d->CD_ID);
                $qry=$this->db->get();
                $excepted_result=$qry->result();
                foreach($excepted_result as $except_row){
                    $excepted_data=array(
                    'AuctionID'=>$cargo_row->AuctionID,
                    'CargoID'=>$cargo_row->CargoID,
                    'DisportID'=>$dis_row->CD_ID,
                    'EventID'=>$except_row->EventID,
                    'LaytimeCountsOnDemurrageFlg'=>$except_row->LaytimeCountsOnDemurrageFlg,
                    'LaytimeCountsFlg'=>$except_row->LaytimeCountsFlg,
                    'TimeCountingFlg'=>$except_row->TimeCountingFlg,
                    'ExceptedPeriodComment'=>$except_row->ExceptedPeriodComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
                }
            }
                
            if($d->DpNORTenderingPreConditionFlg==1) {
                 $this->db->select('*');
                 $this->db->from('udt_AU_DpNORTenderingPreConditions');
                 $this->db->where('DisportID', $d->CD_ID);
                 $qry1=$this->db->get();
                 $tendering_result=$qry1->result();
                foreach($tendering_result as $tendering){
                    $tendering_data=array(
                    'AuctionID'=>$cargo_row->AuctionID,
                    'CargoID'=>$cargo_row->CargoID,
                    'DisportID'=>$dis_row->CD_ID,
                    'CreateNewOrSelectListFlg'=>$tendering->CreateNewOrSelectListFlg,
                    'NORTenderingPreConditionID'=>$tendering->NORTenderingPreConditionID,
                    'NewNORTenderingPreCondition'=>$tendering->NewNORTenderingPreCondition,
                    'StatusFlag'=>$tendering->StatusFlag,
                    'TenderingPreConditionComment'=>$tendering->TenderingPreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
                }
            }
                
            if($d->DpNORAcceptancePreConditionFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpNORAcceptancePreConditions');
                $this->db->where('DisportID', $d->CD_ID);
                $qry12=$this->db->get();
                $acceptance_result=$qry12->result();
                    
                foreach($acceptance_result as $acceptance){
                    $acceptance_data=array(
                    'AuctionID'=>$cargo_row->AuctionID,
                    'CargoID'=>$cargo_row->CargoID,
                    'DisportID'=>$dis_row->CD_ID,
                    'CreateNewOrSelectListFlg'=>$acceptance->CreateNewOrSelectListFlg,
                    'NORAcceptancePreConditionID'=>$acceptance->NORAcceptancePreConditionID,
                    'NewNORAcceptancePreCondition'=>$acceptance->NewNORAcceptancePreCondition,
                    'StatusFlag'=>$acceptance->StatusFlag,
                    'AcceptancePreConditionComment'=>$acceptance->AcceptancePreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
                }
            }
                
            if($d->DpOfficeHoursFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpOfficeHours');
                $this->db->where('DisportID', $d->CD_ID);
                $qry13=$this->db->get();
                $office_disport_result=$qry13->result();
                foreach($office_disport_result as $office){
                    $office_data=array(
                    'AuctionID'=>$cargo_row->AuctionID,
                    'CargoID'=>$cargo_row->CargoID,
                    'DisportID'=>$dis_row->CD_ID,
                    'DateFrom'=>$office->DateFrom,
                    'DateTo'=>$office->DateTo,
                    'TimeFrom'=>$office->TimeFrom,
                    'TimeTo'=>$office->TimeTo,
                    'IsLastEntry'=>$office->IsLastEntry,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpOfficeHours', $office_data);
                }
            }
                
            if($d->DpLaytimeCommencementFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpLaytimeCommencement');
                $this->db->where('DisportID', $d->CD_ID);
                $qry14=$this->db->get();
                $laytime_commence_result=$qry14->result();
                    
                foreach($laytime_commence_result as $laytime){
                    $commence_data=array(
                    'AuctionID'=>$cargo_row->AuctionID,
                    'CargoID'=>$cargo_row->CargoID,
                    'DisportID'=>$dis_row->CD_ID,
                    'DayFrom'=>$laytime->DayFrom,
                    'DayTo'=>$laytime->DayTo,
                    'TimeFrom'=>$laytime->TimeFrom,
                    'TimeTo'=>$laytime->TimeTo,
                    'TurnTime'=>$laytime->TurnTime,
                    'TurnTimeExpire'=>$laytime->TurnTimeExpire,
                    'LaytimeCommenceAt'=>$laytime->LaytimeCommenceAt,
                    'LaytimeCommenceAtHour'=>$laytime->LaytimeCommenceAtHour,
                    'SelectDay'=>$laytime->SelectDay,
                    'TimeCountsIfOnDemurrage'=>$laytime->TimeCountsIfOnDemurrage,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
                }
            }
                
        }
    }
         
    return true;
}
    
public function addauctionDetails($linenum)
{
    extract($this->input->post());
    $this->db->trans_start();
    if($linenum=='') {    
        $linenum=1;
    } else {    
        $linenum++;    
    }
        
    $ExpectedLpDelayDay1=0;
    $ExpectedLpDelayHour1=0;
    $ExpectedDpDelayDay1=0;
    $ExpectedDpDelayHour1=0;
        
    if($ExpectedLpDelayDay) {
        $ExpectedLpDelayDay1=$ExpectedLpDelayDay;
    }
    if($ExpectedLpDelayHour) {
        $ExpectedLpDelayHour1=$ExpectedLpDelayHour;
    } 
    if($ExpectedDpDelayDay) {
        $ExpectedDpDelayDay1=$ExpectedDpDelayDay;
    }
    if($ExpectedDpDelayHour) {
        $ExpectedDpDelayHour1=$ExpectedDpDelayHour;
    }
        
    if($BACFlag=='1') {
        if($broker_id) {
            $this->db->where('BAC_ID', $broker_id);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $broker_id);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
        }
        if($addcom_id) {
            $this->db->where('BAC_ID', $addcom_id);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $addcom_id);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
        }
        $cnt=count($others_id);
        for($i=0; $i<$cnt; $i++){
            $this->db->where('BAC_ID', $others_id[$i]);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $others_id[$i]);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
        }
    } else {
        $this->db->where('CargoLineNum', '0');
        $this->db->where('AuctionID', $auctionId);
        $this->db->delete('udt_AU_BAC');
            
        $this->db->where('CargoLineNum', '0');
        $this->db->where('AuctionID', $auctionId);
        $this->db->delete('udt_AU_BAC_H');
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
    if(!$DischargingRateMT) {
        $DischargingRateMT=0;
    }
    if(!$DpMaxTime) {
        $DpMaxTime=0;
    }
    
    $data1=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$auctionId,
    'LineNum'=>$linenum,
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
    'RowStatus'=>'1',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    'ExpectedLpDelayDay'=>$ExpectedLpDelayDay1,
    'ExpectedLpDelayHour'=>$ExpectedLpDelayHour1,
    'BACFlag'=>$BACFlag,
    'LpStevedoringTerms'=>$LpStevedoringTerms,            
    'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,            
    'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,            
    'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,            
    'OfficeHoursFlg'=>$OfficeHoursFlg,        
    'LaytimeCommencementFlg'=>$LayTimeCommence            
    );
        
    $this->db->insert('udt_AU_Cargo_H', $data1);
         
    $data=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$auctionId,
    'LineNum'=>$linenum,
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
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    'ExpectedLpDelayDay'=>$ExpectedLpDelayDay1,
    'ExpectedLpDelayHour'=>$ExpectedLpDelayHour1,
    'BACFlag'=>$BACFlag,
    'LpStevedoringTerms'=>$LpStevedoringTerms,            
    'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,            
    'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,            
    'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,            
    'OfficeHoursFlg'=>$OfficeHoursFlg,        
    'LaytimeCommencementFlg'=>$LayTimeCommence
    );
        
    $ret=$this->db->insert('udt_AU_Cargo', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $auctionId);
    $this->db->order_by('CargoID', 'desc');
    $qry1=$this->db->get();
    $cargo_row=$qry1->row();
        
    if($ret) {
        $d_data=array(
        'CargoID'=>$cargo_row->CargoID,
        'AuctionID'=>$auctionId,
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
        'ExpectedDpDelayDay'=>$ExpectedDpDelayDay1,
        'ExpectedDpDelayHour'=>$ExpectedDpDelayHour1,
        'DpExceptedPeriodFlg'=>$DpExceptedPeriodEventFlg,
        'DpNORTenderingPreConditionFlg'=>$DpNORTenderingPreConditionFlg,
        'DpNORAcceptancePreConditionFlg'=>$DpNORAcceptancePreConditionFlg,
        'DpOfficeHoursFlg'=>$DpOfficeHoursFlg,
        'DpLaytimeCommencementFlg'=>$DpLayTimeCommence,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports', $d_data);
            
        $this->db->select('*');
        $this->db->from('udt_AU_CargoDisports');
        $this->db->where('CargoID', $cargo_row->CargoID);
        $this->db->order_by('CD_ID', 'desc');
        $qry11=$this->db->get();
        $dis_row=$qry11->row();
            
        $data=array(
        'CD_ID'=>$dis_row->CD_ID,
        'CargoID'=>$cargo_row->CargoID,
        'AuctionID'=>$auctionId,
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
        'ExpectedDpDelayDay'=>$ExpectedDpDelayDay1,
        'ExpectedDpDelayHour'=>$ExpectedDpDelayHour1,
        'DpExceptedPeriodFlg'=>$DpExceptedPeriodEventFlg,
        'DpNORTenderingPreConditionFlg'=>$DpNORTenderingPreConditionFlg,
        'DpNORAcceptancePreConditionFlg'=>$DpNORAcceptancePreConditionFlg,
        'DpOfficeHoursFlg'=>$DpOfficeHoursFlg,
        'DpLaytimeCommencementFlg'=>$DpLayTimeCommence,
        'RowStatus'=>1,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports_H', $data);
            
        if($ExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($ExceptedPeriodEvent); $i++){
                $excepted_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
                'EventID'=>$ExceptedPeriodEvent[$i],
                'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountOnDemurrage[$i],
                'LaytimeCountsFlg'=>$LaytimeCountUsedFlg[$i],
                'TimeCountingFlg'=>$TimeCounting[$i],
                'ExceptedPeriodComment'=>$ExceptedPeriodComment[$i],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ExceptedPeriods', $excepted_data);
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
                        'AuctionID'=>$auctionId,
                        'CargoID'=>$cargo_row->CargoID,
                        'CreateNewOrSelectListFlg'=>$NewSelectTenderingFlg[$j],
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$TenderingActiveFlg[$j],
                        'TenderingPreConditionComment'=>$NORTenderingPreConditionComment[$j],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_NORTenderingPreConditions', $tendering_data);
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
                        'AuctionID'=>$auctionId,
                        'CargoID'=>$cargo_row->CargoID,
                        'CreateNewOrSelectListFlg'=>$NewSelectAcceptanceFlg[$k],
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$AcceptanceActiveFlg[$k],
                        'AcceptancePreConditionComment'=>$NORAcceptancePreConditionComment[$k],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_NORAcceptancePreConditions', $acceptance_data);
            }
        }
            
        if($OfficeHoursFlg==1) {
            for($l=0; $l<count($DayFrom); $l++){
                $office_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
                'DateFrom'=>$DayFrom[$l],
                'DateTo'=>$DayTo[$l],
                'TimeFrom'=>$TimeFrom[$l],
                'TimeTo'=>$TimeTo[$l],
                'IsLastEntry'=>$IsLastEntry[$l],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_OfficeHours', $office_data);
            }
        }
            
        if($LayTimeCommence==1) {
            for($m=0; $m<count($LayTiimeDayFrom); $m++){
                $commence_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
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
                $this->db->insert('udt_AU_LaytimeCommencement', $commence_data);
            }
        }
            
        if($DpExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($DpExceptedPeriodEvent); $i++){
                $excepted_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
                'DisportID'=>$dis_row->CD_ID,
                'EventID'=>$DpExceptedPeriodEvent[$i],
                'LaytimeCountsOnDemurrageFlg'=>$DpLaytimeCountOnDemurrage[$i],
                'LaytimeCountsFlg'=>$DpLaytimeCountUsedFlg[$i],
                'TimeCountingFlg'=>$DpTimeCounting[$i],
                'ExceptedPeriodComment'=>$DpExceptedPeriodComment[$i],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
            }
        }
            
        if($DpNORTenderingPreConditionFlg==1) {
            for($j=0; $j<count($DpNewSelectTenderingFlg); $j++){
                $NORTenderingPreConditionID=0;
                $NewNORTenderingPreCondition='';
                if($DpNewSelectTenderingFlg[$j]==1) {
                    $NewNORTenderingPreCondition=$DpTenderingNameOfCondition[$j];
                } else if($DpNewSelectTenderingFlg[$j]==2) {
                    $NORTenderingPreConditionID=$DpTenderingNameOfCondition[$j];
                }
                $tendering_data=array(
                        'AuctionID'=>$auctionId,
                        'CargoID'=>$cargo_row->CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'CreateNewOrSelectListFlg'=>$DpNewSelectTenderingFlg[$j],
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$DpTenderingActiveFlg[$j],
                        'TenderingPreConditionComment'=>$DpNORTenderingPreConditionComment[$j],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
            }
        }
            
        if($DpNORAcceptancePreConditionFlg==1) {
            for($k=0; $k<count($DpNewSelectAcceptanceFlg); $k++){
                $NORAcceptancePreConditionID=0;
                $NewNORAcceptancePreCondition='';
                if($DpNewSelectAcceptanceFlg[$k]==1) {
                    $NewNORAcceptancePreCondition=$DpAcceptanceNameOfCondition[$k];
                } else if($DpNewSelectAcceptanceFlg[$k]==2) {
                    $NORAcceptancePreConditionID=$DpAcceptanceNameOfCondition[$k];
                }
                $acceptance_data=array(
                        'AuctionID'=>$auctionId,
                        'CargoID'=>$cargo_row->CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'CreateNewOrSelectListFlg'=>$DpNewSelectAcceptanceFlg[$k],
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$DpAcceptanceActiveFlg[$k],
                        'AcceptancePreConditionComment'=>$DpNORAcceptancePreConditionComment[$k],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
            }
        }
            
        if($DpOfficeHoursFlg==1) {
            for($l=0; $l<count($DpDayFrom); $l++){
                $office_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
                'DisportID'=>$dis_row->CD_ID,
                'DateFrom'=>$DpDayFrom[$l],
                'DateTo'=>$DpDayTo[$l],
                'TimeFrom'=>$DpTimeFrom[$l],
                'TimeTo'=>$DpTimeTo[$l],
                'IsLastEntry'=>$IsDpLastEntry[$l],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpOfficeHours', $office_data);
            }
        }
            
        if($DpLayTimeCommence==1) {
            for($m=0; $m<count($DpLayTiimeDayFrom); $m++){
                $commence_data=array(
                'AuctionID'=>$auctionId,
                'CargoID'=>$cargo_row->CargoID,
                'DisportID'=>$dis_row->CD_ID,
                'DayFrom'=>$DpLayTiimeDayFrom[$m],
                'DayTo'=>$DpLayTiimeDayTo[$m],
                'TimeFrom'=>$DpLaytimeTimeFrom[$m],
                'TimeTo'=>$DpLaytimeTimeTo[$m],
                'TurnTime'=>$DpTurnTimeApplies[$m],
                'TurnTimeExpire'=>$DpTurnTimeExpires[$m],
                'LaytimeCommenceAt'=>$DpLaytimeCommencesAt[$m],
                'LaytimeCommenceAtHour'=>$DpLaytimeCommencesAtHours[$m],
                'SelectDay'=>$DpSelectDay[$m],
                'TimeCountsIfOnDemurrage'=>$DpTimeCountsIfOnDemurrage[$m],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
            }
        }
    }
    $this->db->trans_complete();
    return $ret;    
}

public function updateauctionDetails()
{
    extract($this->input->post());
    $linenum=$id;
    $ExpectedLpDelayDay1=0;
    $ExpectedLpDelayHour1=0;
    $ExpectedDpDelayDay1=0;
    $ExpectedDpDelayHour1=0;
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
        
    if($query->num_rows() > 0) {
        $row=$query->row();
            
        $alertLayDate=strtotime($row->LayCanStartDate);
        $cargoLayDate=strtotime($LpLaycanStartDate);
            
        if($alertLayDate != $cargoLayDate) {
            $date_alert['LayCanStartDate']=date('Y-m-d H:i:s', strtotime($LpLaycanStartDate));
            $date_alert['CommenceDate']=date('d-m-Y H:i:s', strtotime($LpLaycanStartDate.' -'.$row->CommenceDaysBefore.' days'));
        }
            
        $date1=date_create(date('Y-m-d H:i:s', strtotime($row->LayCanStartDate)));
        $date2=date_create(date('Y-m-d H:i:s', strtotime($LpLaycanStartDate)));
        $diff=date_diff($date1, $date2);
            
        if($diff->format("%a") != 0 || $alertLayDate != $cargoLayDate) {
            $day_diff=$diff->format("%R%a").' days';
                
            $date_alert['AuctionCeases']=date('d-m-Y H:i:s', strtotime($row->AuctionCeases.' '.$day_diff));
            $alertbefore_arr=explode(',', $row->AlertBeforeCommence);
                
            $newalertbefore='';
            for($i=0; $i<count($alertbefore_arr); $i++){
                  $days=' -'.$alertbefore_arr[$i].' days';
                  $newalertbefore .=date('d-M-y', strtotime($date_alert['CommenceDate'].' '.$days)).', ';
            }
            $date_alert['AlertNotificationCommence']=trim($newalertbefore, ", ");
                
            $alertbefore1_arr=explode(',', $row->AlertBeforeClosing);
                
            $newalertbefore1='';
            for($i=0; $i<count($alertbefore1_arr); $i++){
                $days=' -'.$alertbefore1_arr[$i].' days';
                $newalertbefore1 .=date('d-M-y', strtotime($date_alert['AuctionCeases'].' '.$days)).', ';
            }
            $date_alert['AlertNotificationClosing']=trim($newalertbefore1, ", ");
                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AUM_Alerts', $date_alert);
                    
            $data1=array('CoCode'=>C_COCODE,
            'AuctionID'=>$row->AuctionID,
            'LayCanStartDate'=>$date_alert['LayCanStartDate'],
            'CommenceAlertFlag'=>$row->CommenceAlertFlag,
            'AuctionCommences'=>$row->AuctionCommences,
            'OnlyDisplay'=>1,
            'CommenceDaysBefore'=>$row->CommenceDaysBefore,
            'CommenceDate'=>$date_alert['CommenceDate'],
            'AuctionCommenceDefinedDate'=>$row->AuctionCommenceDefinedDate,
            'AuctionValidity'=>$row->AuctionValidity,
            'AuctionCeases'=>$date_alert['AuctionCeases'],
            'AlertBeforeCommence'=>$row->AlertBeforeCommence,
            'AlertBeforeClosing'=>$row->AlertBeforeClosing,
            'AlertNotificationCommence'=>$date_alert['AlertNotificationCommence'],
            'AlertNotificationClosing'=>$date_alert['AlertNotificationClosing'],
            'IncludeClosing'=>$row->IncludeClosing, 
            'AuctionerComments'=>$row->AuctionerComments, 
            'InviteesComments'=>$row->InviteesComments,
            'auctionvalidityhour'=>$row->auctionvalidityhour, 
            'QuoteCeasesExtendTime'=>$row->QuoteCeasesExtendTime,             
            'ExtendTime1'=>$row->ExtendTime1,             
            'ExtendTime2'=>$row->ExtendTime2,             
            'ExtendTime3'=>$row->ExtendTime3,             
            'RowStatus'=>'2',
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_Alerts_H', $data1);
        }
    }
        
    if($ExpectedLpDelayDay) {
        $ExpectedLpDelayDay1=$ExpectedLpDelayDay;
    }
    if($ExpectedLpDelayHour) {
        $ExpectedLpDelayHour1=$ExpectedLpDelayHour;
    }
    if($ExpectedDpDelayDay) {
        $ExpectedDpDelayDay1=$ExpectedDpDelayDay;
    }
    if($ExpectedDpDelayHour) {
        $ExpectedDpDelayHour1=$ExpectedDpDelayHour;
    }
        
    if($BACFlag=='1') {
        if($broker_id) {
            $this->db->where('BAC_ID', $broker_id);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $broker_id);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
                
        }
        if($addcom_id) {
            $this->db->where('BAC_ID', $addcom_id);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $addcom_id);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
                
        }
        $cnt=count($others_id);
        for($i=0; $i<$cnt; $i++){
            $this->db->where('BAC_ID', $others_id[$i]);
            $this->db->update('udt_AU_BAC', array('CargoLineNum'=>$linenum));
                
            $this->db->where('BAC_ID', $others_id[$i]);
            $this->db->update('udt_AU_BAC_H', array('CargoLineNum'=>$linenum));
                
        }
    } else {
        $this->db->where('CargoLineNum', '0');
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_BAC');
            
        $this->db->where('CargoLineNum', '0');
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_BAC_H');
            
        $this->db->where('CargoLineNum', $linenum);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_BAC');
    }
        
    $data=array(
                'auctionStatus'=>'P',
                'auctionExtendedStatus'=>'',
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
    $this->db->where('AuctionID', $AuctionID);
    //$this->db->update('udt_AU_Auctions',$data);
    if($CargoLimitBasis==1) {
        $MaxCargoMT1=$MaxCargoMT;
        $MinCargoMT1=$MinCargoMT;
        $ToleranceLimit1=0;
        $UpperLimit1=0;
        $LowerLimit1=0;
    }else if($CargoLimitBasis==2) {
        $MaxCargoMT1=0;
        $MinCargoMT1=0;
        $ToleranceLimit1=$ToleranceLimit;
        $UpperLimit1=$UpperLimit;
        $LowerLimit1=$LowerLimit;
    }
        
    $data1=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$AuctionID,
    'LineNum'=>$linenum,
    'ActiveFlag'=>'1',
    'SelectFrom'=>$SelectFrom,
    'CargoQtyMT'=>$CargoQtyMT,
    'CargoLoadedBasis'=>$CargoLoadedBasis,
    'CargoLimitBasis'=>$CargoLimitBasis,
    'ToleranceLimit'=>$ToleranceLimit1,
    'UpperLimit'=>$UpperLimit1,
    'LowerLimit'=>$LowerLimit1,
    'MaxCargoMT'=>$MaxCargoMT1,
    'MinCargoMT'=>$MinCargoMT1,
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
    'RowStatus'=>'2',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    'ExpectedLpDelayDay'=>$ExpectedLpDelayDay,
    'ExpectedLpDelayHour'=>$ExpectedLpDelayHour,
    'BACFlag'=>$BACFlag,
    'LpStevedoringTerms'=>$LpStevedoringTerms,            
    'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,            
    'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,            
    'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,            
    'OfficeHoursFlg'=>$OfficeHoursFlg,        
    'LaytimeCommencementFlg'=>$LayTimeCommence
    );
        
    $this->db->insert('udt_AU_Cargo_H', $data1);
        
    $data=array(
    'SelectFrom'=>$SelectFrom,
    'CargoQtyMT'=>$CargoQtyMT,
    'CargoLoadedBasis'=>$CargoLoadedBasis,
    'CargoLimitBasis'=>$CargoLimitBasis,
    'ToleranceLimit'=>$ToleranceLimit1,
    'UpperLimit'=>$UpperLimit1,
    'LowerLimit'=>$LowerLimit1,
    'MaxCargoMT'=>$MaxCargoMT1,
    'MinCargoMT'=>$MinCargoMT1,
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
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s'),
    'ExpectedLpDelayDay'=>$ExpectedLpDelayDay1,
    'ExpectedLpDelayHour'=>$ExpectedLpDelayHour1,
    'BACFlag'=>$BACFlag,
    'LpStevedoringTerms'=>$LpStevedoringTerms,            
    'ExceptedPeriodFlg'=>$ExceptedPeriodEventFlg,            
    'NORTenderingPreConditionFlg'=>$NORTenderingPreConditionFlg,            
    'NORAcceptancePreConditionFlg'=>$NORAcceptancePreConditionFlg,            
    'OfficeHoursFlg'=>$OfficeHoursFlg,        
    'LaytimeCommencementFlg'=>$LayTimeCommence
    );
        
    $this->db->where('LineNum', $linenum);
    $this->db->where('AuctionID', $AuctionID);
    $ret=$this->db->update('udt_AU_Cargo', $data);
        
    if($ret) {
        $this->db->where('AuctionID', $AuctionID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_ExceptedPeriods');
            
        if($ExceptedPeriodEventFlg==1) {
            for($i=0; $i<count($ExceptedPeriodEvent); $i++){
                $excepted_data=array(
                'AuctionID'=>$AuctionID,
                'CargoID'=>$CargoID,
                'EventID'=>$ExceptedPeriodEvent[$i],
                'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountOnDemurrage[$i],
                'LaytimeCountsFlg'=>$LaytimeCountUsedFlg[$i],
                'TimeCountingFlg'=>$TimeCounting[$i],
                'ExceptedPeriodComment'=>$ExceptedPeriodComment[$i],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ExceptedPeriods', $excepted_data);
            }
        }
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_NORTenderingPreConditions');
            
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
                        'CargoID'=>$CargoID,
                        'CreateNewOrSelectListFlg'=>$NewSelectTenderingFlg[$j],
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$TenderingActiveFlg[$j],
                        'TenderingPreConditionComment'=>$NORTenderingPreConditionComment[$j],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_NORTenderingPreConditions', $tendering_data);
            }
        }
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_NORAcceptancePreConditions');
            
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
                        'CargoID'=>$CargoID,
                        'CreateNewOrSelectListFlg'=>$NewSelectAcceptanceFlg[$k],
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$AcceptanceActiveFlg[$k],
                        'AcceptancePreConditionComment'=>$NORAcceptancePreConditionComment[$k],
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_NORAcceptancePreConditions', $acceptance_data);
            }
        }
            
            $this->db->where('AuctionID', $AuctionID);
            $this->db->where('CargoID', $CargoID);
            $this->db->delete('udt_AU_OfficeHours');
            
        if($OfficeHoursFlg==1) {
            for($l=0; $l<count($DayFrom); $l++){
                $office_data=array(
                'AuctionID'=>$AuctionID,
                'CargoID'=>$CargoID,
                'DateFrom'=>$DayFrom[$l],
                'DateTo'=>$DayTo[$l],
                'TimeFrom'=>$TimeFrom[$l],
                'TimeTo'=>$TimeTo[$l],
                'IsLastEntry'=>$IsLastEntry[$l],
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_OfficeHours', $office_data);
            }
        }
            
            $this->db->where('AuctionID', $AuctionID);
            $this->db->where('CargoID', $CargoID);
            $this->db->delete('udt_AU_LaytimeCommencement');
            
        if($LayTimeCommence==1) {
            for($m=0; $m<count($LayTiimeDayFrom); $m++){
                $commence_data=array(
                'AuctionID'=>$AuctionID,
                'CargoID'=>$CargoID,
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
                $this->db->insert('udt_AU_LaytimeCommencement', $commence_data);
            }
        }
    }
        
    return $ret;
     
}
    
public function saveCharter()
{
    if($this->input->post()) {
        extract($this->input->post());
            
        $data=array(
        'CoCode'=>C_COCODE,
        'AuctionId'=>$actionid,
        'ActiveFlag'=>1,
        'OwnerEntityID'=>$EntityID,
        'SelectFrom'=>$charterdetailsfrom,
        'StatusFlag'=>"DRAFT",
        'ContractType'=>$contracttype,
        'COAReference'=>$coareference,
        'SalesAgreementReference'=>$salesagreementreference,
        'ShipmentReferenceId'=>$shipmentreferenceid,
        'UserID'=>$UserID,
        'ModelFunction'=>$ModelFunction,
        'ModelNumber'=>$ModelNumber,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->where('AuctionId', $actionid);
        $this->db->update('udt_AU_Auctions', $data);
            
        if(count($source)>0) {
            for($i=0;$i<count($source);$i++) {
                 $data1=array(
                   'CoCode'=>C_COCODE,
                   'AuctionId'=>$actionid,
                   'LineNum'=>$i+1,
                   'SourceName'=>$source[$i],
                   'SourceID'=>$ids[$i],
                   'UserID'=>$UserId,
                   'UserDate'=>date('Y-m-d H:i:s')
                   );
                 $this->db->insert('udt_AU_CharterReferences', $data1);    
            } 
        }
        
        if(count($old_source)>0) {
            for($j=0;$j<count($old_source);$j++) {
                $data2=array(
                'SourceName'=>$old_source[$j],
                'SourceID'=>$old_ids[$j]
                );
                $this->db->where('SNO', $SNO[$j]);
                $this->db->update('udt_AU_CharterReferences', $data2);    
            } 
        }
        
    }
}
    
public function updateCharter()
{
    $UserId=$this->input->post('UserID');
    if($this->input->post()) {
        extract($this->input->post());

        $entityid=$this->input->post('EntityID');
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $actionid);
        $query=$this->db->get();
        $result=$query->row();
            
        $data_h=array(
        'CoCode'=>$result->CoCode,
        'AuctionID'=>$actionid,
        'ActiveFlag'=>$result->ActiveFlag,
        'OwnerEntityID'=>$EntityID,
        'AuctionersRole'=>$result->AuctionersRole,
        'StatusFlag'=>$result->StatusFlag,
        'CountryID'=>$result->CountryID,
        'SignDateFlg'=>$result->SignDateFlg,
        'UserSignDate'=>$result->UserSignDate,
        'SelectFrom'=>$charterdetailsfrom,
        'ContractType'=>$contracttype,
        'COAReference'=>$coareference,
        'SalesAgreementReference'=>$salesagreementreference,
        'ShipmentReferenceId'=>$shipmentreferenceid,
        'auctionStatus'=>$result->auctionStatus,
        'auctionExtendedStatus'=>$result->auctionExtendedStatus,
        'RowStatus'=>'2',
        'RecordStatus'=>$result->RecordStatus,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
                    
        $this->db->insert('udt_AU_Auctions_H', $data_h);
            
            
        $data=array(
                        'CoCode'=>C_COCODE,
                        'ActiveFlag'=>1,
                        'OwnerEntityID'=>$entityid,
                        'SelectFrom'=>$charterdetailsfrom,
                        'StatusFlag'=>"DRAFT",
                        'ContractType'=>$contracttype,
                        'COAReference'=>$coareference,
                        'SalesAgreementReference'=>$salesagreementreference,
                        'ShipmentReferenceId'=>$shipmentreferenceid,
                        'auctionStatus'=>'P',
                        'auctionExtendedStatus'=>'',
                        'UserID'=>$UserID,
                        'ModelFunction'=>$ModelFunction,
                        'ModelNumber'=>$ModelNumber,
                        'UserDate'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('AuctionId', $actionid);
        $ret=$this->db->update('udt_AU_Auctions', $data);
            
        if(count($source)>0) {
            for($i=0;$i<count($source);$i++) {
                $data1=array(
                'CoCode'=>C_COCODE,
                'AuctionId'=>$actionid,
                'LineNum'=>$i+1,
                'SourceName'=>$source[$i],
                'SourceID'=>$ids[$i],
                'UserID'=>$UserId,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_CharterReferences', $data1);    
            }  
        }
            
        if(count($old_source)>0) {
            for($j=0;$j<count($old_source);$j++) {
                $data2=array(
                'SourceName'=>$old_source[$j],
                'SourceID'=>$old_ids[$j]
                                );
                $this->db->where('SNO', $SNO[$j]);
                $this->db->update('udt_AU_CharterReferences', $data2);    
            } 
        }
            
            
            return $ret;
    }
}
    
public function getCargoDataById()
{ 
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
    $this->db->select('udt_AU_Cargo.*,udt_CargoMaster.Code as cmcode, udt_CargoMaster.Description as cmDescription,udt_PortMaster.Code as pmCode,udt_PortMaster.PortName as pmDescription,udt_CP_LoadingDischargeTermsMaster.code as ldtCode,udt_CP_LoadingDischargeTermsMaster.Description as ldtDescription');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_AU_Cargo.SelectFrom=udt_CargoMaster.ID', 'left');
    $this->db->join('udt_PortMaster', 'udt_AU_Cargo.LoadPort=udt_PortMaster.ID', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_AU_Cargo.LoadingTerms=udt_CP_LoadingDischargeTermsMaster.ID', 'left');
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->row();
    return $data;
}
    
public function getCargoBACById()
{ 
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('CargoLineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
         
}
    
    
public function getDisPortDataById($DisPort)
{
    $this->db->select('Code as dspCode,PortName as dspDescription');
    $this->db->from('udt_PortMaster');
    $this->db->where('ID', $DisPort);
    $query=$this->db->get();
    return $query->row();
}
    
public function cargoDelete()
{
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->where('CargoID', $result->CargoID);
    $query12=$this->db->get();
    $disport_result=$query12->result();
        
    $data1=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$AuctionID,
    'LineNum'=>$id,
    'ActiveFlag'=>'1',
    'SelectFrom'=>$result->SelectFrom,
    'CargoQtyMT'=>$result->CargoQtyMT,
    'CargoLoadedBasis'=>$result->CargoLoadedBasis,
    'CargoLimitBasis'=>$result->CargoLimitBasis,
    'ToleranceLimit'=>$result->ToleranceLimit,
    'UpperLimit'=>$result->UpperLimit,
    'LowerLimit'=>$result->LowerLimit,
    'MaxCargoMT'=>$result->MaxCargoMT,
    'MinCargoMT'=>$result->MinCargoMT,
    'LoadPort'=>$result->LoadPort,
    'LpLaycanStartDate'=>date('Y-m-d H:i:s', strtotime($result->LpLaycanStartDate)),
    'LpLaycanEndDate'=>date('Y-m-d H:i:s', strtotime($result->LpLaycanEndDate)),
    'LpPreferDate'=>date('Y-m-d H:i:s', strtotime($result->LpPreferDate)),
    'ExpectedLpDelayDay'=>$result->ExpectedLpDelayDay,
    'ExpectedLpDelayHour'=>$result->ExpectedLpDelayHour,
    'LoadingTerms'=>$result->LoadingTerms,
    'LoadingRateMT'=>$result->LoadingRateMT,
    'LoadingRateUOM'=>$result->LoadingRateUOM,
    'LpMaxTime'=>$result->LpMaxTime,
    'LpLaytimeType'=>$result->LpLaytimeType,
    'LpCalculationBasedOn'=>$result->LpCalculationBasedOn,
    'LpTurnTime'=>$result->LpTurnTime,
    'LpPriorUseTerms'=>$result->LpPriorUseTerms,
    'LpLaytimeBasedOn'=>$result->LpLaytimeBasedOn,
    'LpCharterType'=>$result->LpCharterType,
    'LpNorTendering'=>$result->LpNorTendering,
    'LpStevedoringTerms'=>$result->LpStevedoringTerms,
    'ExceptedPeriodFlg'=>$result->ExceptedPeriodFlg,
    'NORTenderingPreConditionFlg'=>$result->NORTenderingPreConditionFlg,
    'NORAcceptancePreConditionFlg'=>$result->NORAcceptancePreConditionFlg,
    'OfficeHoursFlg'=>$result->OfficeHoursFlg,
    'LaytimeCommencementFlg'=>$result->LaytimeCommencementFlg,
    'CargoInternalComments'=>$result->CargoInternalComments,
    'CargoDisplayComments'=>$result->CargoDisplayComments,
    'Freight_Estimate'=>$result->Freight_Estimate,
    'Estimate_By'=>$result->Estimate_By,
    'Estimate_mt'=>$result->Estimate_mt,
    'Estimate_from'=>$result->Estimate_from,
    'Estimate_to'=>$result->Estimate_to,
    'Freight_Index'=>$result->Freight_Index,
    'Estimate_Index_By'=>$result->Estimate_Index_By,
    'Estimate_Index_mt'=>$result->Estimate_Index_mt,
    'Estimate_Index_from'=>$result->Estimate_Index_from,
    'Estimate_Index_to'=>$result->Estimate_Index_to,
    'estimate_comment'=>$result->estimate_comment,
    'Estimate_UserDate'=>date('Y-m-d H:i:s'),
    'BACFlag'=>$result->BACFlag,
    'RowStatus'=>'3',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
        
    $this->db->insert('udt_AU_Cargo_H', $data1);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('CargoLineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $bacresult=$query->result();
        
    foreach($bacresult as $row){
        $bacdata=array(
        'AuctionID'=>$row->AuctionID,    
        'TransactionType'=>$row->TransactionType,
        'PayingEntityType'=>$row->PayingEntityType,
        'PayingEntityName'=>$row->PayingEntityName,
        'ReceivingEntityType'=>    $row->ReceivingEntityType,
        'ReceivingEntityName'=>    $row->ReceivingEntityName,
        'BrokerName'=>$row->BrokerName,
        'PayableAs'=>$row->PayableAs,
        'PercentageOnFreight'=>$row->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$row->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$row->PercentageOnDemmurage,
        'PercentageOnOverage'=>$row->PercentageOnOverage,
        'LumpsumPayable'=>$row->LumpsumPayable,
        'RatePerTonnePayable'=>$row->RatePerTonnePayable,
        'BACComment'=>$row->BACComment,
        'CargoLineNum'=>$row->CargoLineNum,
        'RowStatus'=>'3',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
        $this->db->insert('udt_AU_BAC_H', $bacdata);            
    }
        
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $ret=$this->db->delete('udt_AU_Cargo');
        
    if($ret) {
        $this->db->where('CargoLineNum', $id);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_BAC');
            
        $this->db->where('LineNum', $id);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AUM_Documents');
            
        foreach($disport_result as $dis_row){
            $datah=array(
            'CD_ID'=>$dis_row->CD_ID,
            'CargoID'=>$dis_row->CargoID,
            'AuctionID'=>$dis_row->AuctionID,
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
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports_H', $datah);
                
        }
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_CargoDisports');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_ExceptedPeriods');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_NORTenderingPreConditions');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_NORAcceptancePreConditions');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_OfficeHours');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_LaytimeCommencement');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_DpExceptedPeriods');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_DpNORTenderingPreConditions');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_DpNORAcceptancePreConditions');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_DpOfficeHours');
            
        $this->db->where('CargoID', $result->CargoID);
        $this->db->delete('udt_AU_DpLaytimeCommencement');
            
    }
    
    return $ret;
}
    
public function getPortMaster()
{
    $key=$this->input->post('key');
    $this->db->select('udt_PortMaster.ID,udt_PortMaster.Code,udt_PortMaster.PortName,udt_CountryMaster.Description');
    $this->db->from('udt_PortMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_PortMaster.CountryID', 'left');
    $this->db->like('udt_PortMaster.PortName', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoMaster()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CargoMaster', 'after');
    $this->db->like('Code', $key);
    $query=$this->db->get();
    return $query->result();
}
    
public function getToadingTerm()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CP_LoadingDischargeTermsMaster');
    $this->db->like('Code', $key, 'after');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getEntityType()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_EntityType');
    $this->db->where('ActiveFlag', 1);
    $this->db->like('Description', $key, 'after');
    $query=$this->db->get();
    return $query->result();
}
    
public function getLoadingTermForFixture()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CP_LoadingDischargeTermsMaster');
    $this->db->like('Code', $key, 'after');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getFreeTime()
{
    $this->db->select('*');
    $this->db->from('udt_CP_LayTimeFreeTimeConditionMaster');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getNorTending()
{
    $this->db->select('*');
    $this->db->from('udt_CP_NORTenderingConditionMaster');
    $query=$this->db->get();
    return $query->result(); 
}
    
public function getCurrency()
{
    $key=$this->input->post('key');
    $this->db->select('ID,Code,Description');
    $this->db->from('udt_CurrencyMaster');
    $this->db->like('Code', $key, 'after');
    $query=$this->db->get();
    return $query->result(); 
}
    
    
public function saveQuote()
{
    extract($this->input->post());
    if(!$FreightBasis) {
        $FreightBasis=0;
    }
    if(!$FreightRate) {
        $FreightRate=0;
    }
    if(!$FreightRateUOM) {
        $FreightRateUOM=0;
    }
    if(!$FreightLumpsumMax) {
        $FreightLumpsumMax=0;
    }
    if(!$FreightLow) {
        $FreightLow=0;
    }
    if(!$FreightHigh) {
        $FreightHigh=0;
    }
    if(!$FreightTce) {
        $FreightTce=0;
    }
    if(!$FreightTceDifferential) {
        $FreightTceDifferential=0;
    }
    if(!$Demurrage) {
        $Demurrage=0;
    }
    /* if(!$DespatchDemurrageFlag) {
    $DespatchDemurrageFlag=0;
    }
    if(!$DespatchHalfDemurrage) {
    $DespatchHalfDemurrage=0;
    }
    if(!$CommentsForAuctioner) {
    $CommentsForAuctioner='';
    }
    if(!$CommentsForInvitees) {
    $CommentsForInvitees='';
    }
    if(!$FreightCurrrency) {
    $FreightCurrrency=0;
    } */
    $qids=explode("_", $ids);
    /* 
    foreach($qids as $id) {
    $data=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$AuctionID,
    'LineNum'=>$id,
    'FreightBasis'=>$FreightBasis,
    'FreightRate'=>$FreightRate,
    'FreightCurrrency'=>$FreightCurrrency,
    'FreightRateUOM'=>$FreightRateUOM,
    'FreightLumpsumMax'=>$FreightLumpsumMax,
    'FreightLow'=>$FreightLow,
    'FreightHigh'=>$FreightHigh,
    'FreightTce'=>$FreightTce,
    'FreightTceDifferential'=>$FreightTceDifferential,
    'Demurrage'=>$Demurrage,
    'DespatchDemurrageFlag'=>$DespatchDemurrageFlag,
    'DespatchHalfDemurrage'=>$DespatchHalfDemurrage
    );
    $this->db->insert('udt_AUM_Freight',$data);
    } */
    $totalrows=count($DifferentialDisport);
    foreach($qids as $id) {
        if($totalrows != 0) {
            for($i=0;$i<count($DifferentialDisport);$i++) {
                if($DifferentialDisport[$i]=='null') {
                    $DifferentialDisport1='';
                }else{
                    $DifferentialDisport1=$DifferentialDisport[$i];
                }
                $data2=array(
                    'CoCode'=>C_COCODE,
                    'AuctionID'=>$AuctionID,
                    'LineNum'=>$id,
                    'SNum'=>1,
                    'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
                    'DifferentialLoadport'=>$DifferentialLoadport,    
                    'ReferencePort'=>$ReferencePort1,    
                    'DifferentialComments'=>$CommentsForAuctioner,    
                    'InviteeComment'=>$CommentsForInvitees,    
                    'DifferentialDisport'=>$DifferentialDisport1,    
                    'DifferentialAmount'=>$DifferentialAmount[$i],
                    'RowStatus'=>'1',
                    'UserID'=>$UserID,
                    'UserDate'=>date('Y-m-d h:i:s')
                );
                    
                $this->db->insert('udt_AUM_Differentials_H', $data2);
                
                $data1=array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$AuctionID,
                'LineNum'=>$id,
                'SNum'=>1,
                'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
                'DifferentialLoadport'=>$DifferentialLoadport,    
                'ReferencePort'=>$ReferencePort1,    
                'DifferentialComments'=>$CommentsForAuctioner,    
                'InviteeComment'=>$CommentsForInvitees,    
                'DifferentialDisport'=>$DifferentialDisport1,    
                'DifferentialAmount'=>$DifferentialAmount[$i],
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d h:i:s')
                );
                $ret = $this->db->insert('udt_AUM_Differentials', $data1);
            }
        }else{
            $data2=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$AuctionID,
            'LineNum'=>$id,
            'SNum'=>1,
            'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
            'DifferentialLoadport'=>$DifferentialLoadport,    
            'ReferencePort'=>$ReferencePort1,    
            'DifferentialComments'=>$CommentsForAuctioner,    
            'InviteeComment'=>$CommentsForInvitees,    
            'DifferentialDisport'=>$DifferentialDisport1,    
            'DifferentialAmount'=>$DifferentialAmount[$i],
            'RowStatus'=>'1',
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d h:i:s')
            );
                        
            $this->db->insert('udt_AUM_Differentials_H', $data2);
                
            $data1=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$AuctionID,
            'LineNum'=>$id,
            'SNum'=>1,
            'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
            'DifferentialLoadport'=>$DifferentialLoadport,    
            'ReferencePort'=>$ReferencePort1,    
            'DifferentialComments'=>$CommentsForAuctioner,    
            'InviteeComment'=>$CommentsForInvitees,    
            'DifferentialDisport'=>$DifferentialDisport1,    
            'DifferentialAmount'=>$DifferentialAmount[$i],
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d h:i:s')
            );
                        
            $ret = $this->db->insert('udt_AUM_Differentials', $data1);
        }
    }
    return $ret;
}
    
public function count_cargo()
{
    $AuctionId=$this->input->post('AuctionId');

    $this->db->select('count(*) as Total');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $AuctionId);
    $query=$this->db->get();
    return $query->row();
}
    
public function get_vessel_size()
{
    $EntityID=$this->input->post('EntityID');
    $quantity=$this->input->post('quantity');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Vessel_Master');
    $this->db->where('EntityMasterID', $EntityID);
    $this->db->where('CargoRangeTo >=', $quantity);
    $this->db->where('CargoRangeFrom <=', $quantity);
    $query=$this->db->get();
    return $query->row();
}
    
public function updateQuote()
{
    extract($this->input->post());

    $data_auction=array(
    'auctionStatus'=>'P',
    'auctionExtendedStatus'=>'',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
                );
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AU_Auctions', $data_auction);
        
    if(!$FreightRate) {
        $FreightRate=0;
    }
    if(!$FreightRateUOM) {
        $FreightRateUOM=0;
    }
    if(!$FreightLumpsumMax) {
        $FreightLumpsumMax=0;
    }
    if(!$FreightLow) {
        $FreightLow=0;
    }
    if(!$FreightHigh) {
        $FreightHigh=0;
    }
    if(!$FreightTce) {
        $FreightTce=0;
    }
    if(!$FreightTceDifferential) {
        $FreightTceDifferential=0;
    }
        
    $qids=explode("_", $ids);
        
    $this->db->where('LineNum', $qids[0]);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->delete('udt_AUM_Differentials');
        
    $totalrow=count($DifferentialDisport);
    foreach($qids as $id) {
        if($totalrow != 0 ) {
            for($i=0;$i<count($DifferentialDisport);$i++) {
                if($DifferentialDisport[$i]=='null') {
                      $DifferentialDisport1='';
                }else{
                    $DifferentialDisport1=$DifferentialDisport[$i];
                }
            
                $data2=array(
                    'CoCode'=>C_COCODE,
                    'AuctionID'=>$AuctionID,
                    'LineNum'=>$id,
                    'SNum'=>1,
                    'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
                    'DifferentialLoadport'=>$DifferentialLoadport,    
                    'ReferencePort'=>$ReferencePort,    
                    'DifferentialComments'=>$CommentsForAuctioner,    
                    'InviteeComment'=>$CommentsForInvitees,    
                    'DifferentialDisport'=>$DifferentialDisport1,    
                    'DifferentialAmount'=>$DifferentialAmount[$i],
                    'RowStatus'=>'2',
                    'UserID'=>$UserID,
                    'UserDate'=>date('Y-m-d h:i:s')
                );
                    
                $this->db->insert('udt_AUM_Differentials_H', $data2);

                $data1=array(
                'CoCode'=>C_COCODE,
                'AuctionID'=>$AuctionID,
                'LineNum'=>$id,
                'SNum'=>1,
                'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
                'DifferentialLoadport'=>$DifferentialLoadport,    
                'ReferencePort'=>$ReferencePort,
                'DifferentialComments'=>$CommentsForAuctioner,    
                'InviteeComment'=>$CommentsForInvitees,
                'DifferentialDisport'=>$DifferentialDisport1,    
                'DifferentialAmount'=>$DifferentialAmount[$i],
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d h:i:s')
                );
                    
                $ret=$this->db->insert('udt_AUM_Differentials', $data1);
            }
        } else {
            $data2=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$AuctionID,
            'LineNum'=>$id,
            'SNum'=>1,
            'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
            'DifferentialLoadport'=>$DifferentialLoadport,    
            'ReferencePort'=>$ReferencePort,    
            'DifferentialComments'=>$CommentsForAuctioner,    
            'InviteeComment'=>$CommentsForInvitees,    
            'RowStatus'=>'2',
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d h:i:s')
            );
                        
            $this->db->insert('udt_AUM_Differentials_H', $data2);
            
            $data1=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$AuctionID,
            'LineNum'=>$id,
            'SNum'=>1,
            'DifferentialVesselSizeGroup'=>$DifferentialVesselSizeGroup,    
            'DifferentialLoadport'=>$DifferentialLoadport,    
            'ReferencePort'=>$ReferencePort,
            'DifferentialComments'=>$CommentsForAuctioner,    
            'InviteeComment'=>$CommentsForInvitees,
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d h:i:s')
            );
                        
            $ret=$this->db->insert('udt_AUM_Differentials', $data1);
                
        }
    }
}
    
public function uploadImage()
{
    $res=0;
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
                
            $file_data = array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$AuctionID,
            'LineNum'=>$ids,
            'AuctionSection'=>'quote',
            'FileName'=> $file,
            'Title'=>$NameorTitleofdocumentattached,
            'FileSizeKB'=>round($filesize/1024),
            'FileType'=>$type,
            'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
            'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
            'DocumentType'=>$typeofdocument,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s'), 
            'FileComment'=>$FIleComment[$i] 
            );
            $res=$this->db->insert('udt_AUM_Documents', $file_data);
        }
    }
    return $res;
}
    
public function uploadImage_estimate()
{
    extract($this->input->post());
    $res=0;
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
            $ext=getExtension($document['name'][$i]);
            if($ext=='pdf' || $ext=='PDF') {    
                $nar=explode(".", $document['type'][$i]);
                $type=end($nar);
                $file=rand(1, 999999).'_____'.$document['name'][$i];
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
                        'AuctionSection'=>'estimate',
                        'FileName'=> $file,
                        'Title'=>$documenttitle,
                        'FileSizeKB'=>round($filesize/1024),
                        'FileType'=>$type,
                        'ToDisplay'=>$Documenttobedisplayinauctionprocess, 
                        'ToDisplayInvitee'=>$Documenttobedisplaytoinvitee, 
                        'DocumentType'=>$typeofdocument,
                        'AcceptNameFlg'=>$AcceptNameFlg,
                        'CustomTitle'=>$CustomTitle,
                        'UserID'=>$UserID, 
                        'CreatedDate'=>Date('Y-m-d H:i:s'),
                        'FileComment'=>$FileComment[$i] 
                        );
                     
                       $res=$this->db->insert('udt_AUM_Documents', $file_data);
                }
            }
        }
    }
    return $res;
}
    
public function getAuctionSetup()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from("udt_AU_Auctions");
    $this->db->join("udt_EntityType", 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole');
    $this->db->where('AuctionId', $AuctionId);
    $query = $this->db->get();
    return $query->row();
}
    
public function getCharterDetail()
{
    if($this->input->post()) {
        extract($this->input->post());
    }
    if($this->input->get()) {
        extract($this->input->get());
    }
    $this->db->select('udt_AU_Auctions.*,udt_EntityMaster.EntityName,udt_EntityType.Description as RoleDescription,udt_CountryMaster.Code as cCode,udt_CountryMaster.Description as cDescription');
    $this->db->from("udt_AU_Auctions");
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('AuctionID', $AuctionId);
    $query = $this->db->get();
    return $query->row();
}
    
public function getReferenceDetail()
{
    extract($this->input->post());
    $this->db->select('*');
    $this->db->from("udt_AU_CharterReferences");
    $this->db->where('AuctionID', $AuctionId);
    $query = $this->db->get();
    return $query->result();
}
    
public function deleteReference()
{
    extract($this->input->post());
    $this->db->where('SNO', $SNO);
    return $this->db->delete('udt_AU_CharterReferences');
        
}

    
public function getLoadportByIds()
{
    extract($this->input->post());
        
    $this->db->select('LoadPort');
    $this->db->from("udt_AU_Cargo");
    $this->db->where('AuctionId', $AuctionId);
    $query = $this->db->get();
    return $query->result(); 
        
}
    
public function getLoadportByIdsNew()
{
    extract($this->input->post());
        
    $this->db->select('LoadPort');
    $this->db->from("udt_AU_Cargo");
    $this->db->where('AuctionId', $AuctionId);
    $this->db->where('LineNum', $ids);
    $query = $this->db->get();
    return $query->row(); 
        
}
    
public function getAuctionRecordByAuctionID($AuctionID)
{
        
    $this->db->select('*');
    $this->db->from("udt_AU_Auctions");
    $this->db->where('AuctionId', $AuctionID);
    $query = $this->db->get();
    return $query->row(); 
        
}
    
public function getCargoRecordOwner()
{
    $auctionid=$this->input->post('auctionid');
        
    $this->db->select('*');
    $this->db->from("udt_AU_Auctions");
    $this->db->where('AuctionId', $auctionid);
    $query = $this->db->get();
    return $query->row(); 
        
}
    
public function getDisportByIds()
{
    extract($this->input->post());
    $id=explode("_", $ids);
        
    $this->db->select('DisPort');
    $this->db->from("udt_AU_Cargo");
    $this->db->where('AuctionId', $AuctionId);
    $this->db->where_in('LineNum', $id);
    $query = $this->db->get();
    return $query->result(); 
        
}
    
public function getLoadportById($id)
{
    $this->db->select('udt_PortMaster.ID, udt_PortMaster.Code, udt_PortMaster.PortName, udt_CountryMaster.Description ');
    $this->db->from('udt_PortMaster');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_PortMaster.CountryID', 'left');
    $this->db->where('udt_PortMaster.ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentTypeMaster()
{
    $this->db->select('*');
    $this->db->from('udt_DocumentType_Master');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentTitle()
{
    $id=$this->input->post('id');
    $this->db->select('DocumentTitle');
    $this->db->from('udt_DocumentType_Master');
    $this->db->where('DocumentTypeID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getVesselMaster()
{
    $key=$this->input->post('key');
    $EID=$this->input->post('EID');
    $this->db->select('VesselID,VesselSize,SizeGroup');
    $this->db->from('udt_AUM_Vessel_Master');
    $this->db->like('VesselSize', $key, 'after');
    if($EID) {
        $this->db->where('EntityMasterID', $EID);
    }
    $query=$this->db->get();
    return $query->result();
}
    
public function getAuctionSetupMaster()
{
    $Status=$this->input->get('Status');
    $AuctionersRole=$this->input->get('AuctionersRole');
    $cargoValue=$this->input->get('cargoValue');
    $loadport1=$this->input->get('loadport1');
    $estimateFrom=$this->input->get('estimateFrom');
    $estimateTo=$this->input->get('estimateTo');
    $indexFrom=$this->input->get('indexFrom');
    $indexTo=$this->input->get('indexTo');
    $EntityID=$this->input->get('EID');
    $sql ="";
        
    $sql .="select distinct cops_admin.udt_AU_Auctions.AuctionID,cops_admin.udt_AU_Auctions.auctionStatus,cops_admin.udt_AU_Auctions.auctionExtendedStatus,cops_admin.udt_AU_Auctions.UserDate,cops_admin.udt_AU_Cargo.CargoID,cops_admin.udt_AU_Cargo.LineNum,cops_admin.udt_AU_Cargo.Estimate_By,cops_admin.udt_AU_Cargo.Estimate_mt,cops_admin.udt_AU_Cargo.Estimate_from,cops_admin.udt_AU_Cargo.Estimate_to,cops_admin.udt_AU_Cargo.Estimate_Index_By,cops_admin.udt_AU_Cargo.Estimate_Index_mt,cops_admin.udt_AU_Cargo.Estimate_Index_from,cops_admin.udt_AU_Cargo.Estimate_Index_to,cops_admin.udt_AU_Cargo.Estimate_Index_mt,cops_admin.udt_AU_Cargo.LpLaycanStartDate,cops_admin.udt_AU_Cargo.LpLaycanEndDate,lp.Code as pcode,lp.PortName as pdescription, cops_admin.udt_CargoMaster.Code as ccode
		from cops_admin.udt_AU_Auctions  left join cops_admin.udt_AU_Cargo on cops_admin.udt_AU_Auctions.AuctionID=cops_admin.udt_AU_Cargo.AuctionID left join cops_admin.udt_CargoMaster on cops_admin.udt_CargoMaster.ID=cops_admin.udt_AU_Cargo.SelectFrom left join cops_admin.udt_PortMaster as lp on lp.ID=cops_admin.udt_AU_Cargo.LoadPort
		where cops_admin.udt_AU_Auctions.CoCode='".C_COCODE."' and cops_admin.udt_AU_Auctions.ActiveFlag=1 ";
        
    if($Status=='P' || $Status=='C') {
        $sql .=" and cops_admin.udt_AU_Auctions.auctionStatus='".$Status."'";
    }
        
    if($Status=='A' || $Status=='PNR' || $Status=='W') {
        $sql .=" and cops_admin.udt_AU_Auctions.auctionExtendedStatus='".$Status."'";
    }
        
    if($AuctionersRole) {
        $sql .=" and cops_admin.udt_AU_Auctions.AuctionersRole='".$AuctionersRole."'";
    }
        
    if($cargoValue) {
        $sql .=" and cops_admin.udt_CargoMaster.ID='".$cargoValue."'";
    }
        
    if($loadport1) {
        $sql .=" and cops_admin.udt_AU_Cargo.LoadPort='".$loadport1."'";
    }
        
    if($estimateFrom) {
        $sql .=" and cops_admin.udt_AU_Cargo.Estimate_mt !='NULL' and  cops_admin.udt_AU_Cargo.Estimate_mt !='' and CONVERT(numeric(10,4), cops_admin.udt_AU_Cargo.Estimate_mt) >= '".$estimateFrom."'";
    }
        
    if($estimateTo) {
        $sql .=" and cops_admin.udt_AU_Cargo.Estimate_mt !='NULL' and  cops_admin.udt_AU_Cargo.Estimate_mt !='' and CONVERT(numeric(10,4), cops_admin.udt_AU_Cargo.Estimate_mt) <= '".$estimateTo."'";
    }
        
    if($indexFrom) {
        $sql .=" and cops_admin.udt_AU_Cargo.Estimate_Index_mt !='NULL' and  cops_admin.udt_AU_Cargo.Estimate_Index_mt !='' and CONVERT(numeric(10,4), cops_admin.udt_AU_Cargo.Estimate_Index_mt) >= '".$indexFrom."'";
    }
        
    if($indexTo) {
        $sql .=" and cops_admin.udt_AU_Cargo.Estimate_Index_mt !='NULL' and  cops_admin.udt_AU_Cargo.Estimate_Index_mt !='' and CONVERT(numeric(10,4), cops_admin.udt_AU_Cargo.Estimate_Index_mt) <= '".$indexTo."'";
    }
        
    if($EntityID) {
        $sql .=" and cops_admin.udt_AU_Auctions.OwnerEntityID='".$EntityID."'";
    }
        
    $sql .=" order by cops_admin.udt_AU_Auctions.UserDate DESC";
        
    $query = $this->db->query($sql);
        
    return $query->result();
}
    
public function getInviteeMaster($AuctionID)
{
    $this->db->select('InvPriorityStatus');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('udt_AUM_Invitees.AuctionID', $AuctionID);
    $this->db->order_by('InvID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function auctionClone1($oldauctionId,$newauctionId)
{
    $status='P';
    $RowStatus='4';
    $RecordStatus='2';
    $flag='1';
        
    $UserID=$this->input->post('UserID');
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_Auctions_H (CoCode,AuctionID,ActiveFlag,OwnerEntityID,AuctionersRole,StatusFlag,SelectFrom,ContractType,COAReference,SalesAgreementReference,ShipmentReferenceID,CharterComments,UserID,UserDate,auctionStatus,auctionExtendedStatus,AuctionStatusDate,RowStatus,MessageFlag,MsgDate,RecordStatus,ModelFunction,ModelNumber,CountryID,SignDateFlg,UserSignDate)
		select CoCode,'".$newauctionId."',ActiveFlag,OwnerEntityID,AuctionersRole,StatusFlag,SelectFrom,ContractType,COAReference,SalesAgreementReference,ShipmentReferenceID,CharterComments,'".$UserID."','".date('Y-m-d H:i:s')."','".$status."','','".date('Y-m-d H:i:s')."','".$RowStatus."','".$flag."','".date('Y-m-d H:i:s')."','".$RecordStatus."',ModelFunction,ModelNumber,CountryID, SignDateFlg, UserSignDate
		from cops_admin.udt_AU_Auctions where AuctionID='".$oldauctionId."'"
    );
    
    return $query = $this->db->query(
        "insert into cops_admin.udt_AU_Auctions (CoCode,AuctionID,ActiveFlag,OwnerEntityID,AuctionersRole,StatusFlag,SelectFrom,ContractType,COAReference,SalesAgreementReference,ShipmentReferenceID,CharterComments,UserID,UserDate,auctionStatus,auctionExtendedStatus,AuctionStatusDate,MessageFlag,MsgDate,RecordStatus,ModelFunction,ModelNumber,CountryID,SignDateFlg,UserSignDate)
		select CoCode,'".$newauctionId."',ActiveFlag,OwnerEntityID,AuctionersRole,StatusFlag,SelectFrom,ContractType,COAReference,SalesAgreementReference,ShipmentReferenceID,CharterComments,'".$UserID."','".date('Y-m-d H:i:s')."','".$status."','','".date('Y-m-d H:i:s')."','".$flag."','".date('Y-m-d H:i:s')."','".$RecordStatus."',ModelFunction,ModelNumber,CountryID,SignDateFlg,UserSignDate
		from cops_admin.udt_AU_Auctions where AuctionID='".$oldauctionId."'"
    ); 

}
    
public function auctionClone2($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $RowStatus='4';
    $Estimate_RowStatus='4';
        
    $ret=$this->db->query(
        "insert into cops_admin.udt_AU_BAC (AuctionID,TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,CargoLineNum,UserID,UserDate)
		select '".$newauctionId."', TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment,CargoLineNum ,'".$UserID."','".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AU_BAC where AuctionID='".$oldauctionId."'"
    );
        
    $this->db->query(
        "insert into cops_admin.udt_AU_BAC_H (AuctionID,TransactionType,PayingEntityType,PayingEntityName,ReceivingEntityType,ReceivingEntityName,BrokerName,PayableAs,PercentageOnFreight,PercentageOnDeadFreight,PercentageOnDemmurage,PercentageOnOverage,LumpsumPayable,RatePerTonnePayable,BACComment,CargoLineNum,RowStatus,UserID,UserDate,BAC_ID)
		select '".$newauctionId."',TransactionType, PayingEntityType,PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName,PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment,CargoLineNum,'".$RowStatus."','".$UserID."','".date('Y-m-d H:i:s')."',BAC_ID
		from cops_admin.udt_AU_BAC where  AuctionID='".$oldauctionId."'"
    );
        
    return $ret;
}
    
public function auctionCargoClone2($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $RowStatus='4';
    $Estimate_RowStatus='4';
        
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $oldauctionId);
    $qry=$this->db->get();
    $cargoresult=$qry->result();
        
    foreach($cargoresult as $c){
        $datah=array(
        'CoCode'=>$c->CoCode,
        'AuctionID'=>$newauctionId,
        'LineNum'=>$c->LineNum,
        'ActiveFlag'=>$c->ActiveFlag,
        'SelectFrom'=>$c->SelectFrom,
        'CargoQtyMT'=>$c->CargoQtyMT,
        'CargoLoadedBasis'=>$c->CargoLoadedBasis,
        'CargoLimitBasis'=>$c->CargoLimitBasis,
        'ToleranceLimit'=>$c->ToleranceLimit,
        'UpperLimit'=>$c->UpperLimit,
        'LowerLimit'=>$c->LowerLimit,
        'MaxCargoMT'=>$c->MaxCargoMT,
        'MinCargoMT'=>$c->MinCargoMT,
        'LoadPort'=>$c->LoadPort,
        'LpLaycanStartDate'=>$c->LpLaycanStartDate,
        'LpLaycanEndDate'=>$c->LpLaycanEndDate,
        'LpPreferDate'=>$c->LpPreferDate,
        'ExpectedLpDelayDay'=>$c->ExpectedLpDelayDay,
        'ExpectedLpDelayHour'=>$c->ExpectedLpDelayHour,
        'LoadingTerms'=>$c->LoadingTerms,
        'LoadingRateMT'=>$c->LoadingRateMT,
        'LoadingRateUOM'=>$c->LoadingRateUOM,
        'LpMaxTime'=>$c->LpMaxTime,
        'LpLaytimeType'=>$c->LpLaytimeType,
        'LpCalculationBasedOn'=>$c->LpCalculationBasedOn,
        'LpTurnTime'=>$c->LpTurnTime,
        'LpPriorUseTerms'=>$c->LpPriorUseTerms,
        'LpLaytimeBasedOn'=>$c->LpLaytimeBasedOn,
        'LpCharterType'=>$c->LpCharterType,
        'LpNorTendering'=>$c->LpNorTendering,
        'LpStevedoringTerms'=>$c->LpStevedoringTerms,
        'ExceptedPeriodFlg'=>$c->ExceptedPeriodFlg,
        'NORTenderingPreConditionFlg'=>$c->NORTenderingPreConditionFlg,
        'NORAcceptancePreConditionFlg'=>$c->NORAcceptancePreConditionFlg,
        'OfficeHoursFlg'=>$c->OfficeHoursFlg,
        'LaytimeCommencementFlg'=>$c->LaytimeCommencementFlg,
        'CargoInternalComments'=>$c->CargoInternalComments,
        'CargoDisplayComments'=>$c->CargoDisplayComments,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s'),
        'Freight_Estimate'=>$c->Freight_Estimate,
        'Estimate_By'=>$c->Estimate_By,
        'Estimate_mt'=>$c->Estimate_mt,
        'Estimate_from'=>$c->Estimate_from,
        'Estimate_to'=>$c->Estimate_to,
        'Freight_Index'=>$c->Freight_Index,
        'Estimate_Index_By'=>$c->Estimate_Index_By,
        'Estimate_Index_mt'=>$c->Estimate_Index_mt,
        'Estimate_Index_from'=>$c->Estimate_Index_from,
        'Estimate_Index_to'=>$c->Estimate_Index_to,
        'estimate_comment'=>$c->estimate_comment,
        'Estimate_UserDate'=>date('Y-m-d H:i:s'),
        'RowStatus'=>$RowStatus,
        'Estimate_RowStatus'=>$Estimate_RowStatus,
        'BACFlag'=>$c->BACFlag
        );
        $this->db->insert('udt_AU_Cargo_H', $datah);
        $data=array(
        'CoCode'=>$c->CoCode,
        'AuctionID'=>$newauctionId,
        'LineNum'=>$c->LineNum,
        'ActiveFlag'=>$c->ActiveFlag,
        'SelectFrom'=>$c->SelectFrom,
        'CargoQtyMT'=>$c->CargoQtyMT,
        'CargoLoadedBasis'=>$c->CargoLoadedBasis,
        'CargoLimitBasis'=>$c->CargoLimitBasis,
        'ToleranceLimit'=>$c->ToleranceLimit,
        'UpperLimit'=>$c->UpperLimit,
        'LowerLimit'=>$c->LowerLimit,
        'MaxCargoMT'=>$c->MaxCargoMT,
        'MinCargoMT'=>$c->MinCargoMT,
        'LoadPort'=>$c->LoadPort,
        'LpLaycanStartDate'=>$c->LpLaycanStartDate,
        'LpLaycanEndDate'=>$c->LpLaycanEndDate,
        'LpPreferDate'=>$c->LpPreferDate,
        'ExpectedLpDelayDay'=>$c->ExpectedLpDelayDay,
        'ExpectedLpDelayHour'=>$c->ExpectedLpDelayHour,
        'LoadingTerms'=>$c->LoadingTerms,
        'LoadingRateMT'=>$c->LoadingRateMT,
        'LoadingRateUOM'=>$c->LoadingRateUOM,
        'LpMaxTime'=>$c->LpMaxTime,
        'LpLaytimeType'=>$c->LpLaytimeType,
        'LpCalculationBasedOn'=>$c->LpCalculationBasedOn,
        'LpTurnTime'=>$c->LpTurnTime,
        'LpPriorUseTerms'=>$c->LpPriorUseTerms,
        'LpLaytimeBasedOn'=>$c->LpLaytimeBasedOn,
        'LpCharterType'=>$c->LpCharterType,
        'LpNorTendering'=>$c->LpNorTendering,
        'LpStevedoringTerms'=>$c->LpStevedoringTerms,
        'ExceptedPeriodFlg'=>$c->ExceptedPeriodFlg,
        'NORTenderingPreConditionFlg'=>$c->NORTenderingPreConditionFlg,
        'NORAcceptancePreConditionFlg'=>$c->NORAcceptancePreConditionFlg,
        'OfficeHoursFlg'=>$c->OfficeHoursFlg,
        'LaytimeCommencementFlg'=>$c->LaytimeCommencementFlg,
        'CargoInternalComments'=>$c->CargoInternalComments,
        'CargoDisplayComments'=>$c->CargoDisplayComments,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s'),
        'Freight_Estimate'=>$c->Freight_Estimate,
        'Estimate_By'=>$c->Estimate_By,
        'Estimate_mt'=>$c->Estimate_mt,
        'Estimate_from'=>$c->Estimate_from,
        'Estimate_to'=>$c->Estimate_to,
        'Freight_Index'=>$c->Freight_Index,
        'Estimate_Index_By'=>$c->Estimate_Index_By,
        'Estimate_Index_mt'=>$c->Estimate_Index_mt,
        'Estimate_Index_from'=>$c->Estimate_Index_from,
        'Estimate_Index_to'=>$c->Estimate_Index_to,
        'estimate_comment'=>$c->estimate_comment,
        'Estimate_UserDate'=>date('Y-m-d H:i:s'),
        'BACFlag'=>$c->BACFlag
                );
        $this->db->insert('udt_AU_Cargo', $data);
            
        $this->db->select('*');
        $this->db->from('udt_AU_Cargo');
        $this->db->where('AuctionID', $newauctionId);
        $qry=$this->db->order_by('CargoID', 'desc');
        $qry=$this->db->get();
        $newCargoRow=$qry->row();
            
        if($c->ExceptedPeriodFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ExceptedPeriods');
            $this->db->where('CargoID', $c->CargoID);
            $qry12=$this->db->get();
            $excepted_result=$qry12->result();
            foreach($excepted_result as $except_row){
                $excepted_data=array(
                'AuctionID'=>$newauctionId,
                'CargoID'=>$newCargoRow->CargoID,
                'EventID'=>$except_row->EventID,
                'LaytimeCountsOnDemurrageFlg'=>$except_row->LaytimeCountsOnDemurrageFlg,
                'LaytimeCountsFlg'=>$except_row->LaytimeCountsFlg,
                'TimeCountingFlg'=>$except_row->TimeCountingFlg,
                'ExceptedPeriodComment'=>$except_row->ExceptedPeriodComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ExceptedPeriods', $excepted_data);
            }
        }
            
        if($c->NORTenderingPreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_NORTenderingPreConditions');
            $this->db->where('CargoID', $c->CargoID);
            $qry13=$this->db->get();
            $tendering_result=$qry13->result();
            foreach($tendering_result as $tendering_row){
                $tendering_data=array(
                'AuctionID'=>$newauctionId,
                'CargoID'=>$newCargoRow->CargoID,
                'CreateNewOrSelectListFlg'=>$tendering_row->CreateNewOrSelectListFlg,
                'NORTenderingPreConditionID'=>$tendering_row->NORTenderingPreConditionID,
                'NewNORTenderingPreCondition'=>$tendering_row->NewNORTenderingPreCondition,
                'StatusFlag'=>$tendering_row->StatusFlag,
                'TenderingPreConditionComment'=>$tendering_row->TenderingPreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_NORTenderingPreConditions', $tendering_data);
            }
        }
            
        if($c->NORAcceptancePreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_NORAcceptancePreConditions');
            $this->db->where('CargoID', $c->CargoID);
            $qry14=$this->db->get();
            $acceptance_result=$qry14->result();
            foreach($acceptance_result as $acceptance_row){
                $acceptance_data=array(
                'AuctionID'=>$newauctionId,
                'CargoID'=>$newCargoRow->CargoID,
                'CreateNewOrSelectListFlg'=>$acceptance_row->CreateNewOrSelectListFlg,
                'NORAcceptancePreConditionID'=>$acceptance_row->NORAcceptancePreConditionID,
                'NewNORAcceptancePreCondition'=>$acceptance_row->NewNORAcceptancePreCondition,
                'StatusFlag'=>$acceptance_row->StatusFlag,
                'AcceptancePreConditionComment'=>$acceptance_row->AcceptancePreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_NORAcceptancePreConditions', $acceptance_data);
            }
        }
            
        if($c->OfficeHoursFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_OfficeHours');
            $this->db->where('CargoID', $c->CargoID);
            $qry15=$this->db->get();
            $office_hours_result=$qry15->result();
            foreach($office_hours_result as $office_rows){
                $office_data=array(
                'AuctionID'=>$newauctionId,
                'CargoID'=>$newCargoRow->CargoID,
                'DateFrom'=>$office_rows->DateFrom,
                'DateTo'=>$office_rows->DateTo,
                'TimeFrom'=>$office_rows->TimeFrom,
                'TimeTo'=>$office_rows->TimeTo,
                'IsLastEntry'=>$office_rows->IsLastEntry,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_OfficeHours', $office_data);
            }
        }
            
        if($c->LaytimeCommencementFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_LaytimeCommencement');
            $this->db->where('CargoID', $c->CargoID);
            $qry15=$this->db->get();
            $laytime_result=$qry15->result();
            foreach($laytime_result as $laytime_row){
                $commence_data=array(
                'AuctionID'=>$newauctionId,
                'CargoID'=>$newCargoRow->CargoID,
                'DayFrom'=>$laytime_row->DayFrom,
                'DayTo'=>$laytime_row->DayTo,
                'TimeFrom'=>$laytime_row->TimeFrom,
                'TimeTo'=>$laytime_row->TimeTo,
                'TurnTime'=>$laytime_row->TurnTime,
                'TurnTimeExpire'=>$laytime_row->TurnTimeExpire,
                'LaytimeCommenceAt'=>$laytime_row->LaytimeCommenceAt,
                'LaytimeCommenceAtHour'=>$laytime_row->LaytimeCommenceAtHour,
                'SelectDay'=>$laytime_row->SelectDay,
                'TimeCountsIfOnDemurrage'=>$laytime_row->TimeCountsIfOnDemurrage,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_LaytimeCommencement', $commence_data);
            }
        }
                    
            $this->db->select('*');
            $this->db->from('udt_AU_CargoDisports');
            $this->db->where('CargoID', $c->CargoID);
            $qry=$this->db->get();
            $oldDisports=$qry->result();
            
        foreach($oldDisports as $d){
            $dis_data=array(
                        'CargoID'=>$newCargoRow->CargoID,
                        'AuctionID'=>$newauctionId,
                        'DisPort'=>$d->DisPort,
                        'DpArrivalStartDate'=>$d->DpArrivalStartDate,
                        'DpArrivalEndDate'=>$d->DpArrivalEndDate,
                        'DpPreferDate'=>$d->DpPreferDate,
                        'DischargingTerms'=>$d->DischargingTerms,
                        'DischargingRateMT'=>$d->DischargingRateMT,
                        'DischargingRateUOM'=>$d->DischargingRateUOM,
                        'DpMaxTime'=>$d->DpMaxTime,
                        'DpLaytimeType'=>$d->DpLaytimeType,
                        'DpCalculationBasedOn'=>$d->DpCalculationBasedOn,
                        'DpTurnTime'=>$d->DpTurnTime,
                        'DpPriorUseTerms'=>$d->DpPriorUseTerms,
                        'DpLaytimeBasedOn'=>$d->DpLaytimeBasedOn,
                        'DpCharterType'=>$d->DpCharterType,
                        'DpNorTendering'=>$d->DpNorTendering,
                        'DpStevedoringTerms'=>$d->DpStevedoringTerms,
                        'ExpectedDpDelayDay'=>$d->ExpectedDpDelayDay,
                        'ExpectedDpDelayHour'=>$d->ExpectedDpDelayHour,
                        'DpExceptedPeriodFlg'=>$d->DpExceptedPeriodFlg,
                        'DpNORTenderingPreConditionFlg'=>$d->DpNORTenderingPreConditionFlg,
                        'DpNORAcceptancePreConditionFlg'=>$d->DpNORAcceptancePreConditionFlg,
                        'DpOfficeHoursFlg'=>$d->DpOfficeHoursFlg,
                        'DpLaytimeCommencementFlg'=>$d->DpLaytimeCommencementFlg,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports', $dis_data);
                
            $this->db->select('*');
            $this->db->from('udt_AU_CargoDisports');
            $this->db->where('CargoID', $newCargoRow->CargoID);
            $qry=$this->db->get();
            $newDisportRow=$qry->row();
                
            $dis_data_h=array(
                        'CD_ID'=>$newDisportRow->CD_ID,
                        'CargoID'=>$newCargoRow->CargoID,
                        'AuctionID'=>$newauctionId,
                        'DisPort'=>$d->DisPort,
                        'DpArrivalStartDate'=>$d->DpArrivalStartDate,
                        'DpArrivalEndDate'=>$d->DpArrivalEndDate,
                        'DpPreferDate'=>$d->DpPreferDate,
                        'DischargingTerms'=>$d->DischargingTerms,
                        'DischargingRateMT'=>$d->DischargingRateMT,
                        'DischargingRateUOM'=>$d->DischargingRateUOM,
                        'DpMaxTime'=>$d->DpMaxTime,
                        'DpLaytimeType'=>$d->DpLaytimeType,
                        'DpCalculationBasedOn'=>$d->DpCalculationBasedOn,
                        'DpTurnTime'=>$d->DpTurnTime,
                        'DpPriorUseTerms'=>$d->DpPriorUseTerms,
                        'DpLaytimeBasedOn'=>$d->DpLaytimeBasedOn,
                        'DpCharterType'=>$d->DpCharterType,
                        'DpNorTendering'=>$d->DpNorTendering,
                        'DpStevedoringTerms'=>$d->DpStevedoringTerms,
                        'ExpectedDpDelayDay'=>$d->ExpectedDpDelayDay,
                        'ExpectedDpDelayHour'=>$d->ExpectedDpDelayHour,
                        'DpExceptedPeriodFlg'=>$d->DpExceptedPeriodFlg,
                        'DpNORTenderingPreConditionFlg'=>$d->DpNORTenderingPreConditionFlg,
                        'DpNORAcceptancePreConditionFlg'=>$d->DpNORAcceptancePreConditionFlg,
                        'DpOfficeHoursFlg'=>$d->DpOfficeHoursFlg,
                        'DpLaytimeCommencementFlg'=>$d->DpLaytimeCommencementFlg,
                        'RowStatus'=>'4',
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_CargoDisports_H', $dis_data_h);
                
                
            if($d->DpExceptedPeriodFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpExceptedPeriods');
                $this->db->where('DisportID', $d->CD_ID);
                $qry=$this->db->get();
                $excepted_result=$qry->result();
                foreach($excepted_result as $except_row){
                    $excepted_data=array(
                    'AuctionID'=>$newauctionId,
                    'CargoID'=>$newCargoRow->CargoID,
                    'DisportID'=>$newDisportRow->CD_ID,
                    'EventID'=>$except_row->EventID,
                    'LaytimeCountsOnDemurrageFlg'=>$except_row->LaytimeCountsOnDemurrageFlg,
                    'LaytimeCountsFlg'=>$except_row->LaytimeCountsFlg,
                    'TimeCountingFlg'=>$except_row->TimeCountingFlg,
                    'ExceptedPeriodComment'=>$except_row->ExceptedPeriodComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
                }
            }
                
            if($d->DpNORTenderingPreConditionFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpNORTenderingPreConditions');
                $this->db->where('DisportID', $d->CD_ID);
                $qry1=$this->db->get();
                $tendering_result=$qry1->result();
                foreach($tendering_result as $tendering){
                    $tendering_data=array(
                    'AuctionID'=>$newauctionId,
                    'CargoID'=>$newCargoRow->CargoID,
                    'DisportID'=>$newDisportRow->CD_ID,
                    'CreateNewOrSelectListFlg'=>$tendering->CreateNewOrSelectListFlg,
                    'NORTenderingPreConditionID'=>$tendering->NORTenderingPreConditionID,
                    'NewNORTenderingPreCondition'=>$tendering->NewNORTenderingPreCondition,
                    'StatusFlag'=>$tendering->StatusFlag,
                    'TenderingPreConditionComment'=>$tendering->TenderingPreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
                }
            }
                
            if($d->DpNORAcceptancePreConditionFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpNORAcceptancePreConditions');
                $this->db->where('DisportID', $d->CD_ID);
                $qry12=$this->db->get();
                $acceptance_result=$qry12->result();
                    
                foreach($acceptance_result as $acceptance){
                    $acceptance_data=array(
                    'AuctionID'=>$newauctionId,
                    'CargoID'=>$newCargoRow->CargoID,
                    'DisportID'=>$newDisportRow->CD_ID,
                    'CreateNewOrSelectListFlg'=>$acceptance->CreateNewOrSelectListFlg,
                    'NORAcceptancePreConditionID'=>$acceptance->NORAcceptancePreConditionID,
                    'NewNORAcceptancePreCondition'=>$acceptance->NewNORAcceptancePreCondition,
                    'StatusFlag'=>$acceptance->StatusFlag,
                    'AcceptancePreConditionComment'=>$acceptance->AcceptancePreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
                }
            }
                
            if($d->DpOfficeHoursFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpOfficeHours');
                $this->db->where('DisportID', $d->CD_ID);
                $qry13=$this->db->get();
                $office_disport_result=$qry13->result();
                foreach($office_disport_result as $office){
                    $office_data=array(
                    'AuctionID'=>$newauctionId,
                    'CargoID'=>$newCargoRow->CargoID,
                    'DisportID'=>$newDisportRow->CD_ID,
                    'DateFrom'=>$office->DateFrom,
                    'DateTo'=>$office->DateTo,
                    'TimeFrom'=>$office->TimeFrom,
                    'TimeTo'=>$office->TimeTo,
                    'IsLastEntry'=>$office->IsLastEntry,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpOfficeHours', $office_data);
                }
            }
                
            if($d->DpLaytimeCommencementFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_DpLaytimeCommencement');
                $this->db->where('DisportID', $d->CD_ID);
                $qry14=$this->db->get();
                $laytime_commence_result=$qry14->result();
                    
                foreach($laytime_commence_result as $laytime){
                    $commence_data=array(
                    'AuctionID'=>$newauctionId,
                    'CargoID'=>$newCargoRow->CargoID,
                    'DisportID'=>$newDisportRow->CD_ID,
                    'DayFrom'=>$laytime->DayFrom,
                    'DayTo'=>$laytime->DayTo,
                    'TimeFrom'=>$laytime->TimeFrom,
                    'TimeTo'=>$laytime->TimeTo,
                    'TurnTime'=>$laytime->TurnTime,
                    'TurnTimeExpire'=>$laytime->TurnTimeExpire,
                    'LaytimeCommenceAt'=>$laytime->LaytimeCommenceAt,
                    'LaytimeCommenceAtHour'=>$laytime->LaytimeCommenceAtHour,
                    'SelectDay'=>$laytime->SelectDay,
                    'TimeCountsIfOnDemurrage'=>$laytime->TimeCountsIfOnDemurrage,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
                }
            }
                
                
        }
    }
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $oldauctionId);
    $qry11=$this->db->get();
    $cargoAttachment=$qry11->result();
        
    foreach($cargoAttachment as $c_att){
        $data_attach=array(
        'CoCode'=>$c_att->CoCode,
        'AuctionID'=>$newauctionId,
        'AuctionSection'=>$c_att->AuctionSection,
        'LineNum'=>$c_att->LineNum,
        'FileName'=>$c_att->FileName,
        'Title'=>$c_att->Title,
        'FileSizeKB'=>$c_att->FileSizeKB,
        'FileType'=>$c_att->FileType,
        'ToDisplay'=>$c_att->ToDisplay,
        'ToDisplayInvitee'=>$c_att->ToDisplayInvitee,
        'DocumentType'=>$c_att->DocumentType,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s'),
        'FileComment'=>$c_att->FileComment,
        'AcceptNameFlg'=>$c_att->AcceptNameFlg,
        'CustomTitle'=>$c_att->CustomTitle
        );
        $this->db->insert('udt_AUM_Documents', $data_attach);
    }
        
        
}
    
public function auctionClone3($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('AuctionID', $oldauctionId);
    $query=$this->db->get();
    $diffResult=$query->result();
        
    foreach($diffResult as $dif){
        $diffData=array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$newauctionId,
        'LineNum'=>$dif->LineNum,
        'FreightRateFlg'=>$dif->FreightRateFlg,
        'VesselGroupSizeID'=>$dif->VesselGroupSizeID,
        'BaseLoadPort'=>$dif->BaseLoadPort,
        'FreightReferenceFlg'=>$dif->FreightReferenceFlg,
        'DisportRefPort1'=>$dif->DisportRefPort1,
        'DisportRefPort2'=>$dif->DisportRefPort2,
        'DisportRefPort3'=>$dif->DisportRefPort3,
        'CargoOwnerComment'=>$dif->CargoOwnerComment,
        'InviteeComment'=>$dif->InviteeComment,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $ret=$this->db->insert('udt_AU_Differentials', $diffData);
            
        $this->db->select('*');
        $this->db->from('udt_AU_Differentials');
        $this->db->where('LineNum', $dif->LineNum);
        $this->db->where('AuctionID', $newauctionId);
        $qry=$this->db->get();
        $diff_row=$qry->row();
            
        $DifferentialID=$diff_row->DifferentialID;
            
        $this->db->select('*');
        $this->db->from('udt_AU_DifferentialRefDisports');
        $this->db->where('DifferentialID', $dif->DifferentialID);
        $this->db->where('AuctionID', $oldauctionId);
        $qry1=$this->db->get();
        $diff_ref=$qry1->result();
            
        foreach($diff_ref as $ref){
            $refData=array(
            'DifferentialID'=>$DifferentialID,
            'AuctionID'=>$newauctionId,
            'RefDisportID'=>$ref->RefDisportID,
            'LpDpFlg'=>$ref->LpDpFlg,
            'LoadDischargeRate'=>$ref->LoadDischargeRate,
            'LoadDischargeUnit'=>$ref->LoadDischargeUnit,
            'DifferentialFlg'=>$ref->DifferentialFlg,
            'DifferentialOwnerAmt'=>$ref->DifferentialOwnerAmt,
            'DifferentialInviteeAmt'=>$ref->DifferentialInviteeAmt,
            'GroupNo'=>$ref->GroupNo,
            'PrimaryPortFlg'=>$ref->PrimaryPortFlg,
            'UserID'=>$UserID,
            'CreatedDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_DifferentialRefDisports', $refData);
        }
            
        $this->db->query(
            "insert into cops_admin.udt_AU_Differentials_H (DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,RowStatus,UserID,UserDate)
			select DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,4,UserID,UserDate
			from cops_admin.udt_AU_Differentials where DifferentialID='".$DifferentialID."' "
        );
            
        if(count($diff_ref)) {
            $this->db->select('*');
            $this->db->from('udt_AU_Counter');
            $qryCntrRow=$this->db->get();
            $NewCounterNo=$qryCntrRow->row()->CounterNo+1;
                
            $this->db->query(
                "insert into cops_admin.udt_AU_DifferentialRefDisports_H (DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,RowStatus,RowCounter,UserID,CreatedDate)
				select DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,4,'".$NewCounterNo."',UserID,CreatedDate
				from cops_admin.udt_AU_DifferentialRefDisports where DifferentialID='".$DifferentialID."' "
            );
                
            $this->db->update('udt_AU_Counter', array('CounterNo'=>$NewCounterNo));
        }
            
    }
    return $ret;
        
}
    
public function auctionClone4($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $RowStatus='4';
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('AuctionID', $oldauctionId);
    $qry=$this->db->get();
    $result=$qry->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $querycounter=$this->db->get();
    $resultcounter=$querycounter->row()->CounterNo+1;
        
    $this->db->update('udt_AU_Counter', array('CounterNo'=>$resultcounter));
        
    foreach($result as $rw){
        $data=array(
        'BPID'=>$rw->BPID,
        'AuctionID'=>$newauctionId,
        'UserList'=>$rw->UserList,
        'Status'=>$rw->Status,
        'UserID'=>$UserID,
        'BussinessType'=>$rw->BussinessType,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_BusinessProcessAuctionWise', $data);
    }
    if(count($result) > 0) {
        $query1 = $this->db->query(
            "insert into cops_admin.udt_AU_BusinessProcessAuctionWise_H(BPID, AuctionID, UserList, Status, UserID, RowStatus, BussinessType, UserDate )
			select BPID,'".$newauctionId."', UserList, Status, UserID, '".$RowStatus."', BussinessType,  '".date('Y-m-d H:i:s')."'
			from cops_admin.udt_AU_BusinessProcessAuctionWise where AuctionID='".$newauctionId."'"
        );
    }
        
        
    $query2 = $this->db->query(
        "insert into cops_admin.udt_AUM_Invitees_H(CoCode, AuctionID, Company, EntityID, Since, AdverseComments, Comments, UserID, UserDate, UserMasterID, InvPriorityStatus, InviteeRole, RowStatus, RowCounter, QuoteLimitFlag, QuoteLimitValue )
		select CoCode,'".$newauctionId."', Company, EntityID, Since, AdverseComments, Comments, '".$UserID."', '".date('Y-m-d H:i:s')."', UserMasterID, InvPriorityStatus, InviteeRole, '".$RowStatus."','".$resultcounter."', QuoteLimitFlag, QuoteLimitValue
		from cops_admin.udt_AUM_Invitees where AuctionID='".$oldauctionId."'"
    );
    
    return $query = $this->db->query(
        "insert into cops_admin.udt_AUM_Invitees(CoCode, AuctionID, Company, EntityID, Since, AdverseComments, Comments, UserID, UserDate, UserMasterID, auctionComment, inviteeComment, InvPriorityStatus, InviteeRole, QuoteLimitFlag, QuoteLimitValue )
		select CoCode,'".$newauctionId."', Company, EntityID, Since, AdverseComments, Comments, '".$UserID."', '".date('Y-m-d H:i:s')."', UserMasterID, auctionComment,inviteeComment,InvPriorityStatus, InviteeRole, QuoteLimitFlag, QuoteLimitValue
		from cops_admin.udt_AUM_Invitees where AuctionID='".$oldauctionId."'"
    );
}
    
public function auctionBankDetailsClone4($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $RowStatus='4';
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->where('AuctionID', $oldauctionId);
    $qry=$this->db->get();
    $result=$qry->result();
        
    foreach($result as $rw){
        $data=array(
        'AuctionID'=>$newauctionId,
        'RecordOwner'=>$rw->RecordOwner,
        'BankMasterID'=>$rw->BankMasterID,
        'BankProcessType'=>$rw->BankProcessType,
        'ForEntityID'=>$rw->ForEntityID,
        'BankStatus'=>$rw->BankStatus,
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_AuctionBank', $data);
    }
        
    $qry=$this->db->query(
        "insert into cops_admin.udt_AU_AuctionBank_H (ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,BankStatus,RowStatus,UserID,CreatedDate)
		select ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,BankStatus,$RowStatus,$UserID,CreatedDate
		from cops_admin.udt_AU_AuctionBank where AuctionID='".$newauctionId."' "
    );
        
}
    
public function auctionClone5($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $RowStatus='4';
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AUM_Alerts_H( CoCode, AuctionID, CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, UserID, UserDate, LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate, RowStatus,auctionvalidityhour,auctionceaseshour, AuctionValidMinutes, QuoteCeasesExtendTime, ExtendTime1, ExtendTime2, ExtendTime3)
		select CoCode, '".$newauctionId."', CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, '".$UserID."', '".date('Y-m-d H:i:s')."', LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate,'".$RowStatus."',auctionvalidityhour,auctionceaseshour, AuctionValidMinutes, QuoteCeasesExtendTime, ExtendTime1, ExtendTime2, ExtendTime3
		from cops_admin.udt_AUM_Alerts where AuctionID='".$oldauctionId."'"
    );
    
    return $query = $this->db->query(
        "insert into cops_admin.udt_AUM_Alerts( CoCode, AuctionID, CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDateIs, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, UserID, UserDate, LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate, auctionvalidityhour, auctionceaseshour, AuctionValidMinutes, QuoteCeasesExtendTime, ExtendTime1, ExtendTime2, ExtendTime3)
		select CoCode, '".$newauctionId."', CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDateIs, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, UserID, '".date('Y-m-d H:i:s')."', LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate, auctionvalidityhour, auctionceaseshour, AuctionValidMinutes, QuoteCeasesExtendTime, ExtendTime1, ExtendTime2, ExtendTime3
		from cops_admin.udt_AUM_Alerts where AuctionID='".$oldauctionId."'"
    );
}
    
public function auctionEditableFieldsClone($oldauctionId,$newauctionId)
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('Udt_AU_EditableFiledBox');
    $this->db->where('AuctionID', $oldauctionId);
    $qry=$this->db->get();
    $q_rslt=$qry->result();
        
    foreach($q_rslt as $efb){
        $data_arr=array(
        'AuctionID'=>$newauctionId,
        'UserID'=>$UserID,
        'EntityID'=>$efb->EntityID,
        'ChkLabel'=>$efb->ChkLabel,
        'ChkFlag'=>$efb->ChkFlag,
        'add_date'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('Udt_AU_EditableFiledBox', $data_arr);
    }
}
    
public function saveFreightEstimate()
{
        
    extract($this->input->post());
        
    $qids=explode("_", $ids);
        
    if($qids[0]!='false') {
        if($freight_estimate=='' || $freight_estimate_index=='') {
            return;
        }
        $data1=array(
                        'CoCode'=>C_COCODE,
                        'AuctionID'=>$AuctionID,
                        'LineNum'=>$qids[0],
                        'ActiveFlag'=>1,
                        'Freight_Estimate'=>$freight_estimate,
                        'Estimate_By'=>$estimate_by,
                        'Estimate_mt'=>$estimate_mt,
                        'Estimate_from'=>$freight_range_from,
                        'Estimate_to'=>$freight_range_to,
                        'Freight_Index'=>$freight_estimate_index,
                        'Estimate_Index_By'=>$estimate_index_by,
                        'Estimate_Index_mt'=>$freight_index_mt,
                        'Estimate_Index_from'=>$freight_index_from,
                        'Estimate_Index_to'=>$freight_index_to,
                        'estimate_comment'=>$freightcomment,
                        'Estimate_RowStatus'=>'1',
                        'UserID'=>$UserID,
                        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                    );
            
        $this->db->insert('udt_AU_Cargo_H', $data1);
            
        $data=array(
                        'CoCode'=>C_COCODE,
                        'Freight_Estimate'=>$freight_estimate,
                        'Estimate_By'=>$estimate_by,
                        'Estimate_mt'=>$estimate_mt,
                        'Estimate_from'=>$freight_range_from,
                        'Estimate_to'=>$freight_range_to,
                        'Freight_Index'=>$freight_estimate_index,
                        'Estimate_Index_By'=>$estimate_index_by,
                        'Estimate_Index_mt'=>$freight_index_mt,
                        'Estimate_Index_from'=>$freight_index_from,
                        'Estimate_Index_to'=>$freight_index_to,
                        'estimate_comment'=>$freightcomment,
                        'UserID'=>$UserID,
                        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                    );
            
        $this->db->where_in('LineNum', $qids);
        $this->db->where('AuctionID', $AuctionID);
        return    $this->db->update('udt_AU_Cargo', $data);
    } else {
        $data1=array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionID,
        'LineNum'=>1,
        'ActiveFlag'=>1,
        'Freight_Estimate'=>$freight_estimate,
        'Estimate_By'=>$estimate_by,
        'Estimate_mt'=>$estimate_mt,
        'Estimate_from'=>$freight_range_from,
        'Estimate_to'=>$freight_range_to,
        'Freight_Index'=>$freight_estimate_index,
        'Estimate_Index_By'=>$estimate_index_by,
        'Estimate_Index_mt'=>$freight_index_mt,
        'Estimate_Index_from'=>$freight_index_from,
        'Estimate_Index_to'=>$freight_index_to,
        'estimate_comment'=>$freightcomment,
        'Estimate_RowStatus'=>'1',
        'UserID'=>$UserID,
        'Estimate_UserDate'=>date('Y-m-d h:i:s')
        );
        
        $this->db->insert('udt_AU_Cargo_H', $data1); 
        
        $data=array(
        'CoCode'=>C_COCODE,
        'Freight_Estimate'=>$freight_estimate,
        'Estimate_By'=>$estimate_by,
        'Estimate_mt'=>$estimate_mt,
        'Estimate_from'=>$freight_range_from,
        'Estimate_to'=>$freight_range_to,
        'Freight_Index'=>$freight_estimate_index,
        'Estimate_Index_By'=>$estimate_index_by,
        'Estimate_Index_mt'=>$freight_index_mt,
        'Estimate_Index_from'=>$freight_index_from,
        'Estimate_Index_to'=>$freight_index_to,
        'estimate_comment'=>$freightcomment,
        'UserID'=>$UserID,
        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                );
            
        $this->db->where('AuctionID', $AuctionID);
        return    $this->db->update('udt_AU_Cargo', $data);
    }
}
    
    
public function updateFreightEstimate()
{
    extract($this->input->post());
        
    $qids=explode("_", $ids);
    $data_auction=array(
    'auctionStatus'=>'P',
    'auctionExtendedStatus'=>'',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
                );
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AU_Auctions', $data_auction);
        
        
    if($qids[0]!='false') {
        if($freight_estimate=='' || $freight_estimate_index=='') {
            return;
        }
        $data1=array(
                        'CoCode'=>C_COCODE,
                        'AuctionID'=>$AuctionID,
                        'LineNum'=>$qids[0],
                        'ActiveFlag'=>1,
                        'Freight_Estimate'=>$freight_estimate,
                        'Estimate_By'=>$estimate_by,
                        'Estimate_mt'=>$estimate_mt,
                        'Estimate_from'=>$freight_range_from,
                        'Estimate_to'=>$freight_range_to,
                        'Freight_Index'=>$freight_estimate_index,
                        'Estimate_Index_By'=>$estimate_index_by,
                        'Estimate_Index_mt'=>$freight_index_mt,
                        'Estimate_Index_from'=>$freight_index_from,
                        'Estimate_Index_to'=>$freight_index_to,
                        'estimate_comment'=>$freightcomment,
                        'Estimate_RowStatus'=>'2',
                        'UserID'=>$UserID,
                        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                    );
            
        $this->db->insert('udt_AU_Cargo_H', $data1);
            
        $data=array(
                        'CoCode'=>C_COCODE,
                        'Freight_Estimate'=>$freight_estimate,
                        'Estimate_By'=>$estimate_by,
                        'Estimate_mt'=>$estimate_mt,
                        'Estimate_from'=>$freight_range_from,
                        'Estimate_to'=>$freight_range_to,
                        'Freight_Index'=>$freight_estimate_index,
                        'Estimate_Index_By'=>$estimate_index_by,
                        'Estimate_Index_mt'=>$freight_index_mt,
                        'Estimate_Index_from'=>$freight_index_from,
                        'Estimate_Index_to'=>$freight_index_to,
                        'estimate_comment'=>$freightcomment,
                        'UserID'=>$UserID,
                        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                    );
            
        $this->db->where_in('LineNum', $qids);
        $this->db->where('AuctionID', $AuctionID);
        return    $this->db->update('udt_AU_Cargo', $data);
            
    } else {
            
        $data1=array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$AuctionID,
        'LineNum'=>1,
        'ActiveFlag'=>1,
        'Freight_Estimate'=>$freight_estimate,
        'Estimate_By'=>$estimate_by,
        'Estimate_mt'=>$estimate_mt,
        'Estimate_from'=>$freight_range_from,
        'Estimate_to'=>$freight_range_to,
        'Freight_Index'=>$freight_estimate_index,
        'Estimate_Index_By'=>$estimate_index_by,
        'Estimate_Index_mt'=>$freight_index_mt,
        'Estimate_Index_from'=>$freight_index_from,
        'Estimate_Index_to'=>$freight_index_to,
        'estimate_comment'=>$freightcomment,
        'Estimate_RowStatus'=>'2',
        'UserID'=>$UserID,
        'Estimate_UserDate'=>date('Y-m-d h:i:s')
        );
        
        $this->db->insert('udt_AU_Cargo_H', $data1); 
        
        $data=array(
        'CoCode'=>C_COCODE,
        'Freight_Estimate'=>$freight_estimate,
        'Estimate_By'=>$estimate_by,
        'Estimate_mt'=>$estimate_mt,
        'Estimate_from'=>$freight_range_from,
        'Estimate_to'=>$freight_range_to,
        'Freight_Index'=>$freight_estimate_index,
        'Estimate_Index_By'=>$estimate_index_by,
        'Estimate_Index_mt'=>$freight_index_mt,
        'Estimate_Index_from'=>$freight_index_from,
        'Estimate_Index_to'=>$freight_index_to,
        'estimate_comment'=>$freightcomment,
        'UserID'=>$UserID,
        'Estimate_UserDate'=>date('Y-m-d h:i:s')
                );
            
        $this->db->where('AuctionID', $AuctionID);
        return    $this->db->update('udt_AU_Cargo', $data);
    }
}
    
public function get_document_title()
{ 
    if($this->input->post()) {
        $name=$this->input->post('name');
        $EID=$this->input->post('EID');
        $DocType=$this->input->post('DocType');
    }
    if($this->input->get()) {
        $name=$this->input->get('name');
        $EID=$this->input->get('EID');
        $DocType=$this->input->get('DocType');
    }
    $this->db->select('DocumentTypeID, udt_AUM_Document_master.DocName as DocumentTitle');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->where('DocumentType', $name);
    $this->db->where('CharterPartyApprovalStatus', 1);
    $this->db->where('ActiveFlag', 1);
    if($EID) {
        $this->db->where('EntityMasterID', $EID);
    }
    if($DocType) {
        $this->db->where('FinalDocumentationFlag', $DocType);
    }
    $query=$this->db->get();
    return $query->result();
        
}
    
public function get_cargo_document($linenum,$auctionid)
{
    $AuctionSection=$this->input->post('AuctionSection');
    $this->db->select('udt_AUM_Documents.ToDisplay, udt_AUM_Documents.ToDisplayInvitee, udt_AUM_Documents.FileName, udt_AUM_Documents.AcceptNameFlg, udt_AUM_Documents.CustomTitle, udt_AUM_Documents.DocumentType, udt_AUM_Documents.DocumentID, udt_AUM_DocumentType_Master.DocumentTypeID, udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('udt_AUM_Documents.LineNum', $linenum);
    $this->db->where('udt_AUM_Documents.AuctionID', $auctionid);
    if($AuctionSection) {
        $this->db->where('udt_AUM_Documents.AuctionSection', $AuctionSection);
    }
    $query=$this->db->get();
    $userData=$query->result();
        
    return $userData;
}
    
public function getDisportTermDataById($id)
{
    $this->db->select('Code as dtCode,Description as dtDescription');
    $this->db->from('udt_CP_LoadingDischargeTermsMaster');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function search_by_auction()
{
    extract($this->input->post());
    $temp='';
    if($cargoValue && $cargoAutocomplete) {
        $temp.=" and cops_admin.udt_AU_Cargo.SelectFrom='".$cargoValue."'";
    }
    if($LoadPort1 && $loadport) {
        $temp.=" and cops_admin.udt_AU_Cargo.LoadPort='".$LoadPort1."'";
    }
        
    if($EntityMasterID && $recordOwnerAutocomplete) {
        $temp.=" and cops_admin.udt_AU_Auctions.OwnerEntityID='".$EntityMasterID."'";
    }
    if($AuctionersRole && $roleAutocomplete) {
        $temp.=" and cops_admin.udt_AU_Auctions.AuctionersRole='".$AuctionersRole."'";
    }
    if($status) {
        $temp.=" and cops_admin.udt_AU_Auctions.auctionStatus='".$status."'";
    }
        
    $subquery="select distinct cops_admin.udt_AU_Auctions.AuctionID, cops_admin.udt_AU_Auctions.auctionStatus,cops_admin.udt_AU_Auctions.UserDate,cops_admin.udt_AU_Cargo.LineNum,cops_admin.udt_AU_Cargo.LpLaycanStartDate,cops_admin.udt_AU_Cargo.LpLaycanEndDate,lp.Code as pcode, cops_admin.udt_CargoMaster.Code as ccode from cops_admin.udt_AU_Auctions left join cops_admin.udt_AU_Cargo on cops_admin.udt_AU_Auctions.AuctionID=cops_admin.udt_AU_Cargo.AuctionID left join cops_admin.udt_PortMaster as lp on lp.ID=cops_admin.udt_AU_Cargo.LoadPort
		left join cops_admin.udt_CargoMaster on cops_admin.udt_CargoMaster.ID=cops_admin.udt_AU_Cargo.SelectFrom where cops_admin.udt_AU_Auctions.CoCode='".C_COCODE."'".$temp." order by cops_admin.udt_AU_Auctions.UserDate DESC";
    $query = $this->db->query($subquery);
    return $query->result();
}
    
public function deleteAuction($AuctionID)
{
        
    $this->db->where('AuctionID', $AuctionID);
    return $this->db->update('udt_AU_Auctions', array('ActiveFlag'=>'0'));
        
}
    
    
    
public function updateRole()
{
    if($this->input->post()) {
        extract($this->input->post());
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $actionid);
        $query=$this->db->get();
        $result=$query->row();
            
        $data_h=array(
        'CoCode'=>$result->CoCode,
        'AuctionID'=>$actionid,
        'ActiveFlag'=>$result->ActiveFlag,
        'OwnerEntityID'=>$result->OwnerEntityID,
        'AuctionersRole'=>$AuctionersRole,
        'StatusFlag'=>$result->StatusFlag,
        'SelectFrom'=>$result->SelectFrom,
        'ContractType'=>$result->ContractType,
        'COAReference'=>$result->COAReference,
        'SalesAgreementReference'=>$result->SalesAgreementReference,
        'auctionStatus'=>'P',
        'auctionExtendedStatus'=>'',
        'RowStatus'=>'2',
        'RecordStatus'=>$result->RecordStatus,
        'UserID'=>$UserID,
        'CountryID'=>$CountryID,
        'SignDateFlg'=>$SignDateFlg,
        'UserSignDate'=>date('Y-m-d H:i:s', strtotime($UserSignDate)),
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_Auctions_H', $data_h);
            
        $data=array(
        'AuctionersRole'=>$AuctionersRole,
        'auctionStatus'=>'P',
        'auctionExtendedStatus'=>'',
        'UserID'=>$UserID,
        'CountryID'=>$CountryID,
        'SignDateFlg'=>$SignDateFlg,
        'UserSignDate'=>date('Y-m-d H:i:s', strtotime($UserSignDate)),
        'UserDate'=>date('Y-m-d H:i:s')
                    );
        $this->db->where('AuctionID', $actionid);
        $this->db->update('udt_AU_Auctions', $data);
            
    } else {
        return 0;
    }
}
    
    
    
public function get_AuctionRole()
{
    $AuctionId=$this->input->post('AuctionId');
    $this->db->select('udt_AU_Auctions.OwnerEntityID, udt_AU_Auctions.auctionExtendedStatus, udt_AU_Auctions.CountryID, udt_AU_Auctions.SignDateFlg, udt_AU_Auctions.UserSignDate, udt_EntityMaster.EntityName, udt_EntityType.*, udt_CountryMaster.Description as C_Description');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole', 'left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionId);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentForCargoByAction($auctionID)
{
    $type=$this->input->get('type');
    $this->db->select('udt_AUM_Documents.ToDisplay, udt_AUM_Documents.ToDisplayInvitee, udt_AUM_Documents.FileName, udt_AUM_Documents.AcceptNameFlg, udt_AUM_Documents.CustomTitle, udt_AUM_Documents.DocumentType, udt_AUM_Documents.DocumentID, udt_AUM_Documents.FileSizeKB, udt_AUM_DocumentType_Master.DocumentTypeID, udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_DocumentType_Master.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('udt_AUM_Documents.AuctionID', $auctionID);
    $this->db->where('udt_AUM_Documents.AuctionSection', $type);
    return $this->db->get()->result();
} 
    
public function getCharterData()
{
    $charter=$this->input->get('charter');
    $EID=$this->input->get('EID');
    $DocType=$this->input->get('DocType');
        
    $this->db->select('udt_AUM_DocumentType_Master.DocumentTypeID, udt_AUM_Document_master.DocName as DocumentTitle');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->where('udt_AUM_DocumentType_Master.DocumentType', $charter);
    if($EID) {
        $this->db->where('udt_AUM_DocumentType_Master.EntityMasterID', $EID);
    }
    if($DocType) {
        $this->db->where('udt_AUM_DocumentType_Master.FinalDocumentationFlag', $DocType);
    }
    $q=$this->db->get();
    return $q->result();
}
    
public function deleteImgById($docid)
{
    $this->db->where('DocumentID', $docid);
    return $this->db->delete('udt_AUM_Documents');
}
    
public function save_auction_status()
{
    $status=$this->input->post('status');
    $AuctionReleaseStatus=$this->input->post('AuctionReleaseStatus');
    $auctionid=$this->input->post('auctionid');
    $UserID=$this->input->post('UserID');
    $EntityID=$this->input->post('EntityID');
    $Flag=$this->input->post('Flag');
        
    $AuctionStatus='';
    if($AuctionReleaseStatus !='W') {
        if($AuctionReleaseStatus =='A') {
            $AuctionStatus='Released';
        }
        $this->db->where('AuctionID', $auctionid);
        $this->db->delete('udt_AUM_Freight');
            
        $this->db->where('AuctionID', $auctionid);
        $this->db->delete('udt_AU_Freight');
            
        $this->db->select('EntityID,UserMasterID,QuoteLimitFlag,QuoteLimitValue');
        $this->db->from('udt_AUM_Invitees');
        $this->db->where('AuctionID', $auctionid);
        $query=$this->db->get();
        $rslt=$query->result();
        $entitys=array();
        foreach($rslt as $row) {
            if(in_array($row->EntityID, $entitys)) {
                  $this->db->select('ResponseID,InvUserID');
                  $this->db->from('udt_AUM_Freight');
                  $this->db->where('AuctionID', $auctionid);
                  $this->db->where('EntityID', $row->EntityID);
                  $query12=$this->db->get();
                  $InvUser=$query12->row();
                  $InvUserID =$InvUser->InvUserID.','.$row->UserMasterID;
                    
                  //$this->db->where('ResponseID',$InvUser->ResponseID);
                  $this->db->where('AuctionID', $auctionid);
                  $this->db->where('EntityID', $row->EntityID);
                  $this->db->update('udt_AUM_Freight', array('InvUserID'=>$InvUserID));
            } else {
                for($i=0; $i<$row->QuoteLimitValue; $i++){
                    $data=array(
                    'CoCode'=>C_COCODE,
                    'AuctionID'=>$auctionid,
                    'EntityID'=>$row->EntityID,
                    'InvUserID'=>$row->UserMasterID,
                    'ResponseStatus'=>$AuctionStatus,
                    'UserID'=>$UserID,
                    'FinalConfirm'=>'2',
                    'ReleaseDate'=>date('Y-m-d H:i:s'),
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AUM_Freight', $data);
                }
                array_push($entitys, $row->EntityID);
            }
        }
                
        $this->db->select('*');
        $this->db->from('udt_AU_Counter');
        $querycounter=$this->db->get();
        $resultcounter=$querycounter->row()->FreightCounter+1;
            
        $this->db->select('*');
        $this->db->from('udt_AUM_Freight');
        $this->db->where('AuctionID', $auctionid);
        $query_freight=$this->db->get();
        $result_freight=$query_freight->result();
            
        foreach($result_freight as $freight_row){
            $data_freight=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$auctionid,
            'EntityID'=>$freight_row->EntityID,
            'InvUserID'=>$freight_row->InvUserID,
            'ResponseStatus'=>$AuctionStatus,
            'ResponseID'=>$freight_row->ResponseID,
            'TentativeStatus'=>$freight_row->TentativeStatus,
            'UserID'=>$UserID,
            'ReleaseDate'=>date('Y-m-d H:i:s'),
            'RowStatus'=>'1',
            'FinalConfirm'=>'2',
            'FreightCounter'=>$resultcounter,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AUM_Freight_H', $data_freight);
        }
            
            $this->db->update('udt_AU_Counter', array('FreightCounter'=>$resultcounter));
            
    } 
        
    if($AuctionReleaseStatus=='PNR') {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_Entitymaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_Entitymaster', 'udt_Entitymaster.ID=udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('MessageType', 'alert_msg');
        $this->db->where('Events', 'prior_commencement');
        $this->db->where('OnPage', 'page_1');
        $query=$this->db->get();
        $result=$query->result();
            
        $this->db->select('*');
        $this->db->from('udt_AUM_Alerts');
        $this->db->where('AuctionID', $auctionid);
        $query1=$this->db->get();
        $result1=$query1->row();
        if($result1->IncludeClosing=='Yes') {
            $CommenceDate='';
            if($result1->AuctionCommences=='1') {
                $CommenceDate=$result1->CommenceDate;
            } else if($result1->AuctionCommences=='2') {
                $CommenceDate=$result1->AuctionCommenceDefinedDate;
            }
            $msgDetails=$msg.'<br>Commences on : '.$CommenceDate.' Ceases on : '.$result1->AuctionCeases;    
        } else {
            $msgDetails='';
        }
        foreach($result as $r){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$auctionid,    
            'Event'=>'Prior to commencement of bid',    
            'Page'=>'Cargo Set Up',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$r->MessageID,    
            'UserID'=>$r->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
            
            
            $this->db->where('AuctionID', $auctionid);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    if($AuctionReleaseStatus=='A') {
        if($Flag=='1') {
            $this->db->select('AuctionValidity,AlertBeforeCommence,AlertBeforeClosing');
            $this->db->from('udt_AUM_Alerts');
            $this->db->where('AuctionID', $auctionid);
            $query1=$this->db->get();
            $result1=$query1->row();
                
            $alertbefore_arr=explode(',', $result1->AlertBeforeCommence);
                
            $newalertbefore='';
            for($i=0; $i<count($alertbefore_arr); $i++){
                $days=' -'.$alertbefore_arr[$i].' days';
                $newalertbefore .=date('d-M-y', strtotime($days)).', ';
            }
                
            $alertbefore1_arr=explode(',', $result1->AlertBeforeClosing);
                
            $newalertbefore1='';
            for($i=0; $i<count($alertbefore1_arr); $i++){
                $days=' -'.$alertbefore1_arr[$i].' days';
                $newalertbefore1 .=date('d-M-y', strtotime($days)).', ';
            }
                
            $alertData=array(
            'CommenceDate'=>date('d-m-Y H:i:s'),
            'AuctionCeases'=>date('d-m-Y H:i:s', strtotime(' +'.$result1->AuctionValidity.' day')),
            'AlertNotificationCommence'=>trim($newalertbefore, ", "),
            'AlertNotificationClosing'=>trim($newalertbefore1, ", ")
            );
            $this->db->where('AuctionID', $auctionid);
            $this->db->update('udt_AUM_Alerts', $alertData);
            
        } 
        $this->db->select('udt_AU_BusinessProcessAuctionWise.*');
        $this->db->from('udt_AU_BusinessProcessAuctionWise');
        $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
        $this->db->where('AuctionID', $auctionid);
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 9);
        $query2=$this->db->get();
        $result2=$query2->row();
        $msg='';
        if($result2->Status==1) {
            $msg='<br>Vessel acceptance may be subject to management approval. Advisory to this effect will be sent as required.';
        } 
                
        $this->db->select('*');
        $this->db->from('udt_AUM_Alerts');
        $this->db->where('AuctionID', $auctionid);
        $query1=$this->db->get();
        $result1=$query1->row();
                            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_Entitymaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_Entitymaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('OnPage', 'page_1');
        $this->db->where('MessageType', 'proc_msg');
        $this->db->where('Events', '1');
        $query2=$this->db->get();
        $result2=$query2->result();
                
        if($result1->IncludeClosing=='Yes') {
            $CommenceDate='';
            if($result1->AuctionCommences=='1') {
                $CommenceDate=$result1->CommenceDate;
            } else if($result1->AuctionCommences=='2') {
                $CommenceDate=$result1->AuctionCommenceDefinedDate;
            }
            $msgDetails=$msg.'<br>Commences on : '.$CommenceDate.' Ceases on : '.$result1->AuctionCeases;    
        } else {
            $msgDetails=$msg;
        }
                
        foreach($result2 as $row){                    
            $msg1=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$auctionid,    
            'Event'=>'Bid commencement',    
            'Page'=>'Cargo Set Up',    
            'Section'=>'Status',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$row->MessageID,    
            'UserID'=>$row->ForUserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg1);
        }
                
        $this->db->select('udt_AU_BusinessProcessAuctionWise.*,udt_UserMaster.EntityID');
        $this->db->from('udt_AU_BusinessProcessAuctionWise');
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BusinessProcessAuctionWise.UserList');
        $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 2);
        $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $auctionid);
        $qry=$this->db->get();
        $invRecords=$qry->result();
                
        foreach($invRecords as $inv){
            $this->db->select('udt_AUM_MESSAGE_MASTER.*');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
            $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $inv->EntityID);
            $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $inv->UserList);
            $this->db->where('OnPage', 'page_1');
            $this->db->where('MessageType', 'proc_msg');
            $this->db->where('Events', '1');
            $qury2=$this->db->get();
            $rslt2=$qury2->row();
                    
            if($rslt2) { 
                $msg2=array(
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$auctionid,    
                'Event'=>'Bid commencement',    
                'Page'=>'Cargo Set Up',    
                'Section'=>'Status',    
                'subSection'=>'',    
                'StatusFlag'=>'1',    
                'MessageDetail'=>$msgDetails,    
                'MessageMasterID'=>$rslt2->MessageID,    
                'UserID'=>$rslt2->ForUserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')    
                );
                $this->db->insert('udt_AU_Messsage_Details', $msg2);
                        
            }
                    
        }
                
                
        $this->db->where('AuctionID', $auctionid);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
    
    if($AuctionReleaseStatus=='') {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('MessageType', 'sys_msg');
        $this->db->where('Events', 'complete');
        $this->db->where('OnPage', 'page_1');
        $query=$this->db->get();
        $result=$query->result();
        $msgDetail='';
        foreach($result as $r){
            $msg=array(
            'CoCode'=>C_COCODE,
            'AuctionID'=>$auctionid,    
            'Event'=>'Cargo set up complete',    
            'Page'=>'Cargo Set Up',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetail,    
            'MessageMasterID'=>$r->MessageID,    
            'UserID'=>$r->ForUserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
            
        $data=array(
        'auctionStatus'=>$status,
        'auctionExtendedStatus'=>$AuctionReleaseStatus,
        'UserID'=>$UserID,
        'AuctionStatusDate'=>date('Y-m-d H:i:s')
                    );
            
            
    } else {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.EntityID');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('MessageType', 'sys_msg');
        $this->db->where('Events', 'status_change');
        $this->db->where('OnPage', 'page_1');
        $query=$this->db->get();
        $result=$query->result();
        $msgDetail='';
        foreach($result as $r){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$auctionid,    
            'Event'=>'Status change',    
            'Page'=>'Cargo Set Up',    
            'Section'=>'',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetail,    
            'MessageMasterID'=>$r->MessageID,    
            'UserID'=>$r->ForUserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
        }
            
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $auctionid);
        $query=$this->db->get();
        $auctionRow=$query->row();
        $data_h=array(
        'CoCode'=>C_COCODE,
        'AuctionID'=>$auctionid,
        'ActiveFlag'=>$auctionRow->ActiveFlag,
        'StatusFlag'=>$auctionRow->StatusFlag,
        'OwnerEntityID'=>$auctionRow->OwnerEntityID,
        'auctionStatus'=>$status,
        'auctionExtendedStatus'=>$AuctionReleaseStatus,
        'AuctionersRole'=>$auctionRow->AuctionersRole,
        'SelectFrom'=>$auctionRow->SelectFrom,
        'ContractType'=>$auctionRow->ContractType,
        'COAReference'=>$auctionRow->COAReference,
        'SalesAgreementReference'=>$auctionRow->SalesAgreementReference,
        'ShipmentReferenceID'=>$auctionRow->ShipmentReferenceID,
        'RowStatus'=>'2',
        'RecordStatus'=>$auctionRow->RecordStatus,
        'CountryID'=>$auctionRow->CountryID,
        'SignDateFlg'=>$auctionRow->SignDateFlg,
        'UserSignDate'=>$auctionRow->UserSignDate,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
                    );
                
        $this->db->insert('udt_AU_Auctions_H', $data_h);    
            
        $data=array(
                'auctionStatus'=>$status,
                'auctionExtendedStatus'=>$AuctionReleaseStatus,
                'AuctionStatusDate'=>date('Y-m-d H:i:s'),
                'UserID'=>$UserID,
                'AuctionReleaseDate'=>date('Y-m-d H:i:s')
        );
    }
        
    $this->db->where('AuctionID', $auctionid);
    return $this->db->update('udt_AU_Auctions', $data);    
                
}
    
public function get_auction_status()
{
    $auctionid=$this->input->post('auctionid');
    $this->db->select('auctionStatus,auctionExtendedStatus,AuctionStatusDate,AuctionReleaseDate,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_Auctions.UserID');
    $this->db->where('AuctionID', $auctionid);
    $query=$this->db->get();
    return $query->row();
}
    
public function getRoleSelectionCharterDetails()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $this->db->select('udt_AU_Auctions.*,udt_EntityType.Code,udt_EntityType.Description, udt_EntityMaster.EntityName, udt_CountryMaster.Description as C_Description');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole', 'left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_Auctions.OwnerEntityID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('udt_AU_Auctions.AuctionID', $auction);
    $query=$this->db->get();
    return $query->row();
}
    
public function get_cargo_html_details()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $cocode=C_COCODE;
    $this->db->select('udt_AU_Cargo.*, udt_CargoMaster.Code, udt_CargoMaster.Description, lp.PortName as lpDescription, ldt1.code as ldtCode, lft.Code as ftCode, lft.Description as ftDescription, cnr.Code as cnrCode');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID=udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt1', 'ldt1.ID=udt_AU_Cargo.LoadingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as lft', 'lft.ID=udt_AU_Cargo.LpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr', 'cnr.ID=udt_AU_Cargo.LpNorTendering', 'left');
    $this->db->where('udt_AU_Cargo.AuctionID', $auction);
    $this->db->where('udt_AU_Cargo.CoCode', $cocode);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_cargo_document_details($type)
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
        
    $cocode=C_COCODE;
    $this->db->select('udt_AUM_Documents.*,DM.DocName as DocumentTitle');
    $this->db->from('udt_AUM_Documents');
    $this->db->join('udt_AUM_DocumentType_Master as DTM', 'DTM.DocumentTypeID=udt_AUM_Documents.Title', 'left');
    $this->db->join('udt_AUM_Document_master as DM', 'DM.DMID=DTM.DocumentTitle', 'left');
    $this->db->where('udt_AUM_Documents.AuctionID', $auction);
    $this->db->where('udt_AUM_Documents.AuctionSection', $type);
    $this->db->where('udt_AUM_Documents.CoCode', $cocode);
    $this->db->order_by('LineNum', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function get_alert_html_details()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $cocode=C_COCODE;
    $this->db->select('udt_AUM_Alerts.*');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('udt_AUM_Alerts.AuctionID', $auction);
    $query=$this->db->get();
    return $query->row();
}
    
public function getDifferentialRecords($AuctionID,$LineNum)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('AuctionID', $AuctionID); 
    $this->db->where('LineNum', $LineNum);
    $query=$this->db->get();
    $diff_row=$query->row();
        
    $this->db->select('udt_AU_DifferentialRefDisports.*,refpt.PortName as refPortName, refpt.Code as refPortCode');
    $this->db->from('udt_AU_DifferentialRefDisports');
    $this->db->join('udt_PortMaster as refpt', 'refpt.ID=udt_AU_DifferentialRefDisports.RefDisportID', 'left');
    $this->db->where('DifferentialID', $diff_row->DifferentialID);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('GroupNo', 'asc');
    $this->db->order_by('PrimaryPortFlg', 'desc');
    $this->db->order_by('DiffRefDisportID', 'asc');
    $query1=$this->db->get();
    return $query1->result();
}
    
public function getFreightResponseRecords()
{
    $ResponseID=$this->input->post('ResponseID');
        
    $this->db->select('udt_AU_Freight.*');
    $this->db->from('udt_AU_Freight');
    $this->db->where('udt_AU_Freight.ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_quote_diff_html_details()
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
    
public function get_invitee_html_details()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $cocode=C_COCODE;
    $this->db->select('udt_AUM_Invitees.*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('udt_AUM_Invitees.AuctionID', $auction);
    $this->db->where('udt_AUM_Invitees.CoCode', $cocode);
    $this->db->order_by('InvPriorityStatus', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function get_invitee_html_details12()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $this->db->select('udt_AUM_Invitees.*,udt_UserMaster.FirstName,udt_UserMaster.LastName, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Invitees');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Invitees.UserMasterID');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Invitees.EntityID');
    $this->db->where('udt_AUM_Invitees.AuctionID', $auction);
    $query=$this->db->get();
    return $query->result();
}  
    
public function get_vessel_html_details()
{
    if($this->input->post()) {
        $auction=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $auction=$this->input->get('AuctionId');
    }
    $cocode=C_COCODE;
    $this->db->select('udt_AU_Vessel.*');
    $this->db->from('udt_AU_Vessel');
    $this->db->where('udt_AU_Vessel.AuctionID', $auction);
    //$this->db->where('udt_AUM_Invitees.CoCode',$cocode);
    $query=$this->db->get();
    return $query->row();
}
    
public function check_status()
{
    $auction=$this->input->post('auctionid');
    $cocode=C_COCODE;
    //$auction='abc';
    $auctionFlag=1;
    $cargoFlag=1;
    $freightFlag=1;
    $diffFlag=1;
    $vesselFlag=1;
    $invFlag=1;
    $alertFlag=1;
    $this->db->select('udt_AU_Auctions.*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('udt_AU_Auctions.AuctionID', $auction);
    $this->db->where('udt_AU_Auctions.CoCode', $cocode);
    $query=$this->db->get();
    $auctionData=$query->row();
     
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $auction);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.Status', 1);
    $query1=$this->db->get();
    $businessData=$query1->result();
        
    $this->db->select('udt_AU_Cargo.*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('udt_AU_Cargo.AuctionID', $auction);
    $this->db->where('udt_AU_Cargo.CoCode', $cocode);
    $query=$this->db->get();
    $cargoData=$query->result();
        
    $this->db->select('udt_AU_Differentials.*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('udt_AU_Differentials.AuctionID', $auction);
    $this->db->where('udt_AU_Differentials.CoCode', $cocode);
    $query=$this->db->get();
    $diffData=$query->result();
        
    $this->db->select('udt_AUM_Invitees.*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('udt_AUM_Invitees.AuctionID', $auction);
    $this->db->where('udt_AUM_Invitees.CoCode', $cocode);
    $query=$this->db->get();
    $invData=$query->result();
        
    $this->db->select('udt_AUM_Alerts.*');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('udt_AUM_Alerts.AuctionID', $auction);
    $this->db->where('udt_AUM_Alerts.CoCode', $cocode);
    $query=$this->db->get();
    $alertData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('udt_AUM_Documents.AuctionID', $auction);
    $this->db->where('udt_AUM_Documents.AuctionSection', 'cp');
    $query12=$this->db->get();
    $cargoDocuments=$query12->row();
        
    if($auctionData) {
        if($auctionData->CoCode && $auctionData->AuctionID && $auctionData->ActiveFlag && $auctionData->AuctionersRole && $auctionData->StatusFlag && $auctionData->SelectFrom && $auctionData->ContractType  && $auctionData->ModelFunction && $auctionData->ModelNumber && $auctionData->CountryID && $auctionData->SignDateFlg ) {
            //Nothing
        } else {
            $auctionFlag=0;
        }
    } else {
        $auctionFlag=0;
    }
        
    if(count($businessData) > 0) {
        foreach($businessData as $b){
            if($b->UserList) {
                //nothing
            } else {
                $auctionFlag=0;
                break;
            }
        }
    }
        
    if($cargoData) {
        foreach($cargoData as $row){
            $MaxCargoMT='';
            $MinCargoMT='';
            $ToleranceLimit='';
            $UpperLimit='';
            $LowerLimit='';
            $Estimate_mt='';
            $Estimate_from='';
            $Estimate_to='';
            $Estimate_Index_mt='';
            $Estimate_Index_from='';
            $Estimate_Index_to='';
            if($row->CoCode && $row->AuctionID && $row->LineNum && $row->ActiveFlag && $row->SelectFrom && $row->CargoQtyMT && $row->CargoLoadedBasis && $row->CargoLimitBasis && $row->LoadPort && $row->LpLaycanStartDate && $row->LpLaycanEndDate && $row->LoadingTerms && $row->LoadingRateMT && $row->LoadingRateUOM && $row->LpLaytimeType && $row->LpCalculationBasedOn && $row->LpTurnTime && $row->LpCharterType && $row->LpNorTendering && $row->LpStevedoringTerms && $row->ExceptedPeriodFlg && $row->NORTenderingPreConditionFlg && $row->NORAcceptancePreConditionFlg) {
                if($row->CargoLimitBasis==1) {
                    $MaxCargoMT=(int)$row->MaxCargoMT;
                    $MinCargoMT=(int)$row->MinCargoMT;
                    if($MaxCargoMT && $MinCargoMT ) {
                        if($row->LoadingRateUOM==3) {
                            $LpMaxTime=(int)$row->LpMaxTime;
                            if($LpMaxTime) {
                                //Nothing                
                            } else {
                                $cargoFlag=0;
                                break;
                            }
                        }
                    } else {
                        $cargoFlag=0;
                        break;
                    }
                }else if($row->CargoLimitBasis==2) {
                    $ToleranceLimit=(int)$row->ToleranceLimit;
                    $UpperLimit=(int)$row->UpperLimit;
                    $LowerLimit=(int)$row->LowerLimit;
                    if($ToleranceLimit && $UpperLimit && $LowerLimit ) {
                        if($row->LoadingRateUOM==3) {
                            $LpMaxTime=(int)$row->LpMaxTime;
                            if($LpMaxTime) {
                                  //Nothing
                            }else{
                                $cargoFlag=0;
                                break;
                            }
                        }
                            
                    }else{
                          $cargoFlag=0;
                          break;
                    }
                }
            } else {
                $cargoFlag=0;
                break;
            }
                
            $this->db->select('*');
            $this->db->from('udt_AU_CargoDisports');
            $this->db->where('udt_AU_CargoDisports.CargoID', $row->CargoID);
            $query=$this->db->get();
            $disportData=$query->result();
                
            if(count($disportData) > 0 ) {
                //Nothing
                foreach($disportData as $d){
                    if($d->DpArrivalStartDate && $d->DpArrivalEndDate && $d->DpPreferDate && $d->DischargingTerms && $d->DischargingRateMT && $d->DpLaytimeType && $d->DpCalculationBasedOn && $d->DpTurnTime && $d->DpPriorUseTerms && $d->DpLaytimeBasedOn && $d->DpCharterType && $d->DpNorTendering && $d->DpStevedoringTerms && $d->DpExceptedPeriodFlg && $d->DpNORTenderingPreConditionFlg && $d->DpNORAcceptancePreConditionFlg) {
                        //nothing
                    } else {
                        $cargoFlag=0;
                        break;
                    }
                }
            } else {
                $cargoFlag=0;
                break;
            }
                
            if($row->Freight_Estimate && $row->Freight_Index) {
                if($row->Freight_Estimate=='yes') {
                    if($row->Estimate_By) {
                        if($row->Estimate_By=='mt') {
                            $Estimate_mt=$row->Estimate_mt;
                            if($Estimate_mt > 0) {
                                 //Nothing
                            }else{
                                $freightFlag=0;
                                break;
                            }
                        }else if($row->Estimate_By=='range') {
                            $Estimate_from=$row->Estimate_from;
                            $Estimate_to=$row->Estimate_to;
                            if($Estimate_from > 0 && $Estimate_to > 0) {
                                //Nothing
                            }else{
                                $freightFlag=0;
                                break;
                            }
                        }
                    } else {
                        $freightFlag=0;
                        break;
                    }
                }
                if($row->Freight_Index=='yes') {
                    if($row->Estimate_Index_By) {
                        if($row->Estimate_Index_By=='mt') {
                            $Estimate_Index_mt=$row->Estimate_Index_mt;
                            if($Estimate_Index_mt > 0) {
                                //Nothing
                            }else{
                                $freightFlag=0;
                                break;
                            }
                        }else if($row->Estimate_Index_By=='range') {
                            $Estimate_Index_from=$row->Estimate_Index_from;
                            $Estimate_Index_to=$row->Estimate_Index_to;
                            if($Estimate_Index_from > 0 && $Estimate_Index_to > 0) {
                                //Nothing
                            }else{
                                $freightFlag=0;
                                break;
                            }
                        }
                    } else {
                               $freightFlag=0;
                               break;
                    }
                }
            } else {
                $freightFlag=0;
            }
        }
    } else {
        $cargoFlag=0;
    }
        
    if($cargoDocuments) {
        //---- nothing
    } else {
        $cargoFlag=0;
    }
        
    if(count($diffData) > 0) {
        $countCargo=count($cargoData);
        $countDiff=count($diffData);
        if($countCargo == $countDiff) {
            foreach($diffData as $diffRow){
                if($diffRow->VesselGroupSizeID && $diffRow->BaseLoadPort && $diffRow->FreightReferenceFlg && $diffRow->DisportRefPort1 ) {
                    //Nothing
                }else{
                    $diffFlag=0;
                    break;
                }
            }
        } else {
            $diffFlag=0;
        }
    } else {
        $diffFlag=0;
    }
        
    if($invData) {
        foreach($invData as $invRow){
            if($invRow->CoCode && $invRow->AuctionID && $invRow->Company && $invRow->EntityID && $invRow->InvPriorityStatus && $invRow->InviteeRole && $invRow->QuoteLimitFlag && $invRow->QuoteLimitValue ) {
                //nothing
            }else{
                $invFlag=0;
                break;
            }
        }
    } else {
        $invFlag=0;
    }
        
    if($alertData->CoCode && $alertData->AuctionID && $alertData->CommenceAlertFlag && $alertData->AlertBeforeCommence && $alertData->AlertBeforeClosing && $alertData->AlertNotificationCommence && $alertData->AlertNotificationClosing && $alertData->IncludeClosing) {
        if($alertData->CommenceAlertFlag=='Yes') {
            if($alertData->AuctionCommences && $alertData->AuctionValidity !='') {
                if($alertData->AuctionCommences=='1') {
                    $LayCanStartDate=date('Y-m-d', strtotime($alertData->LayCanStartDate));
                    $CommenceDate=date('Y-m-d', strtotime($alertData->CommenceDate));
                    $AuctionCeases=date('Y-m-d', strtotime($alertData->AuctionCeases));
                    if($LayCanStartDate=='1970-01-01' || $CommenceDate=='1970-01-01' || $AuctionCeases=='1970-01-01' ) {
                        $alertFlag=0;
                    }
                } else if($alertData->AuctionCommences=='2') {
                    $AuctionCommenceDefinedDate=date('Y-m-d', strtotime($alertData->AuctionCommenceDefinedDate));
                    if($AuctionCommenceDefinedDate=='1970-01-01') {
                        $alertFlag=0;
                    }
                }
            }else{
                $alertFlag=0;
            }
        }
    }else{
        if($alertData->AlertBeforeCommence != 0 && $alertData->AlertBeforeClosing != 0 ) {
            $alertFlag=0;
        }
    }
    if($auctionFlag && $cargoFlag && $diffFlag && $freightFlag && $vesselFlag && $alertFlag && $invFlag) {
        return 0;
    }else if($auctionFlag==0) {
        return 1;
    }else if($cargoFlag==0) {
        return 2;
    }else if($diffFlag==0) {
        return 3;
    }else if($freightFlag==0) {
        return 4;
    }else if($vesselFlag==0) {
        return 5;
    }else if($invFlag==0) {
        return 6;
    }else if($alertFlag==0) {
        return 7;
    }
}
    
public function get_message_detail()
{
    $MessageID=$this->input->post('MessageID');
    $Msgdata=array(
     'StatusFlag'=>'0'
     );
    $this->db->where('MessageDetailID', $MessageID);
    $this->db->update('udt_AU_Messsage_Details', $Msgdata);
        
        
    $this->db->select('udt_AU_Messsage_Details.*, u1.FirstName as ToFirstName, u1.LastName as ToLastName, e1.EntityName as ToEntityName, u2.FirstName as FromFirstName, u2.LastName as FromLastName, e2.EntityName as FromEntityName, udt_AUM_MESSAGE_MASTER.Message');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster as u1', 'u1.ID=udt_AU_Messsage_Details.UserID', 'left');
    $this->db->join('udt_EntityMaster as e1', 'e1.ID=u1.EntityID', 'left');
    $this->db->join('udt_UserMaster as u2', 'u2.ID=udt_AU_Messsage_Details.FromUserID', 'left');
    $this->db->join('udt_EntityMaster as e2', 'e2.ID=u2.EntityID', 'left');
    $this->db->join('udt_AUM_MESSAGE_MASTER', 'udt_AUM_MESSAGE_MASTER.MessageID=udt_AU_Messsage_Details.MessageMasterID', 'left');
    $this->db->where('udt_AU_Messsage_Details.MessageDetailID', $MessageID);
    $query=$this->db->get();
    $data=$query->row();
        
        
    $this->db->select('udt_AU_Messsage_Details.StatusFlag');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $data->AuctionID);
    $this->db->order_by('StatusFlag', 'DESC');
    $query1=$this->db->get();
    $flag=$query1->row();
    if($flag->StatusFlag=='0') {
        $this->db->where('udt_AU_Auctions.AuctionID', $data->AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'0','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $data;
        
}
    
public function sendMessage()
{
    $UserId=$this->input->post('UserID');
    $AuctionId=$this->input->post('AuctionId');
    $Entity_ID=$this->input->post('Entity_ID');
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $Entity_ID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $Entity_ID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'add');
    $query=$this->db->get();
    $data=$query->row();
    $msgDetais='';
    if($data) {
        $msgdata=array( 
        'CoCode'=>'MARX',
        'AuctionID'=>$AuctionId,
        'Event'=>'Add',
        'Page'=>'Cargo Set Up',
        'Section'=>'',
        'subSection'=>'',
        'StatusFlag'=>'1',
        'MessageDetail'=>$msgDetais,
        'MessageMasterID'=>$data->MessageID,
        'UserID'=>$UserId,
        'FromUserID'=>$UserId,
        'UserDate'=>date('Y-m-d H:i:s')
        );
                
        $this->db->insert('udt_AU_Messsage_Details', $msgdata); 
    }
        
        
}
    
public function getAuctionOldData()
{
    $AuctionID=$this->input->post('actionid');
            
    $entityid=$this->input->post('EntityID');
    $this->db->select('udt_AU_Auctions.*, udt_EntityType.Description, udt_CountryMaster.Description as C_Description');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityType', 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('AuctionId', $AuctionID);
    $query=$this->db->get();
    return $query->row();
        
}

public function saveRoleMessage($oldData,$newData)
{
    $AuctionID=$this->input->post('actionid');
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
        
    $Section='';
    $message='';
    if($oldData->AuctionersRole != $newData->AuctionersRole) {
        if($msgData) {
            $Section='Role Selection';
            $message .='<br>Old Role : '.$oldData->Description.' New Role : '.$newData->Description;
            $roledata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Role Selection',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserId,
            'FromUserID'=>$UserId,
            'UserDate'=>date('Y-m-d H:i:s')
            );
                
            $this->db->insert('udt_AU_Messsage_Details', $roledata); 

            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                
        }
            
    }
        
    if($oldData->CountryID != $newData->CountryID) {
        if($msgData) {
            $Section='Role Selection';
            $message .='<br>Old Place : '.$oldData->C_Description.' New Place : '.$newData->C_Description;
            $roledata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Role Selection',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserId,
            'FromUserID'=>$UserId,
            'UserDate'=>date('Y-m-d H:i:s')
            );
                
            $this->db->insert('udt_AU_Messsage_Details', $roledata); 

            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                
        }
            
    }
        
    if($oldData->SignDateFlg != $newData->SignDateFlg) {
        if($oldData->SignDateFlg==1) {
            $OldSignDateFlg ='As per system date';
        } else if($oldData->SignDateFlg==2) {
            $OldSignDateFlg ='User specified date';
        }
            
        if($newData->SignDateFlg==1) {
            $NewSignDateFlg ='As per system date';
        } else if($newData->SignDateFlg==2) {
            $NewSignDateFlg ='User specified date';
        }
            
        if($oldData->SignDateFlg==1) {
                $message .='<br>Old Signing Date : '.$OldSignDateFlg.' New Signing Date : '.$NewSignDateFlg.'<br>Old UserSignDate :  New UserSignDate : '.date('d-m-Y', strtotime($newData->UserSignDate));
        } else if($oldData->SignDateFlg==2) {
                $message .='<br>Old Signing Date : '.$OldSignDateFlg.' New Signing Date : '.$NewSignDateFlg.'<br>Old User Sign Date : '.date('d-m-Y', strtotime($newData->UserSignDate)).' New User Sign Date : ';
        }
            
        if($msgData) {
            $Section='Role Selection';
                
            $roledata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Role Selection',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserId,
            'FromUserID'=>$UserId,
            'UserDate'=>date('Y-m-d H:i:s')
            );
                
            $this->db->insert('udt_AU_Messsage_Details', $roledata); 

            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                
        }
            
    }
        
    $mailMsg='';
    $mailMsg .='User : '.$msgData->LoginID.'<br>Section : Role Selection <br>Message : '.$msgData->Message;
    //$this->sendMail($mailMsg);
}
    
public function saveCharterMessage($oldData,$newData)
{
    $AuctionID=$this->input->post('actionid');
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $Section='';
    $message='';
        
    if($oldData->SelectFrom != $newData->SelectFrom) {
        $Section='Charter Details';
        if($oldData->SelectFrom=='1') {
            $oldSelectFrom='Manual';
        }else if($oldData->SelectFrom=='2') {
            $oldSelectFrom='Import from Topmarx';
        }
            
        if($newData->SelectFrom=='1') {
            $newSelectFrom='Manual';
        }else if($newData->SelectFrom=='2') {
            $newSelectFrom='Import from Topmarx';
        }
            
            $message .='<br>Old Charter details from : '.$oldSelectFrom.' To New Charter details from : '.$newSelectFrom;
    }
        
    if($oldData->ContractType != $newData->ContractType) {
        $Section='Charter Details';
        if($oldData->ContractType=='1') {
            $oldContractType='Spot';
        }else if($oldData->ContractType=='2') {
            $oldContractType='Contract';
        }
            
        if($newData->ContractType=='1') {
            $newContractType='Spot';
        }else if($newData->ContractType=='2') {
            $newContractType='Contract';
        }
            
            $message .='<br>Old Contract type : '.$oldContractType.' New Contract type : '.$newContractType;
    }
        
    if($oldData->COAReference != $newData->COAReference) {
        $Section='Charter Details';
        $message .='<br>Old COA Reference : '.$oldData->COAReference.' New COA Reference : '.$newData->COAReference;
    }
        
    if($oldData->SalesAgreementReference != $newData->SalesAgreementReference) {
        $Section='Charter Details';
        $message .='<br>Old Sales Agreement Reference : '.$oldData->SalesAgreementReference.' New Sales Agreement Reference : '.$newData->SalesAgreementReference;
    }
        
    if($oldData->ShipmentReferenceID != $newData->ShipmentReferenceID) {
        $Section='Charter Details';
        $message .='<br>Old Shipment ReferenceID : '.$oldData->ShipmentReferenceID.' New Shipment ReferenceID : '.$newData->ShipmentReferenceID;
    }
            
    if($msgData) {
        $Charterdata=array(
        'CoCode'=>'Marx',
        'AuctionID'=>$AuctionID,
        'Event'=>'Edit & Update',
        'Page'=>'Cargo Set Up',
        'Section'=>'Charter Details',
        'subSection'=>$Section,
        'StatusFlag'=>'1',
        'MessageDetail'=>$message,
        'MessageMasterID'=>$msgData->MessageID,
        'UserID'=>$UserId,
        'FromUserID'=>$UserId,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_Messsage_Details', $Charterdata); 

        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                
    }
            
                
    /* $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_UserMaster','udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType','sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage','page_1');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events','edit_update');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OutputFormat','email');
    $query1=$this->db->get();
    $data1=$query1->result();
        
    foreach($data1 as $row){
    $mailMsg='';
    $mailMsg .='User : '.$row->LoginID.'<br>Section : Add <br>Message : '.$row->Message;
    $mailMsg .='<br>Updates are as followes : <br>'.$message
    $this->sendMail($mailMsg);
    } */
        
}
    
public function getCargoDetails()
{
    $linenum=$this->input->post('id');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AU_Cargo.*,udt_CargoMaster.Code as cmcode, udt_CargoMaster.Description as cmDescription,lp.Code as lpCode,lp.Description as lpDescription, ldt.code as ldtCode, ldt.Description as ldtDescription');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_AU_Cargo.SelectFrom=udt_CargoMaster.ID', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt', 'ldt.ID = udt_AU_Cargo.LoadingTerms', 'left');
    $this->db->where('LineNum', $linenum);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->row();
    return $data;
}
    
public function saveCargoMessage($oldData,$newData,$expDataOld,$expDataNew,$tenderingOldData,$tenderingNewData,$acceptOldData,$acceptNewData,$officeOldData,$officeNewData,$laytimeOldData,$laytimeNewData)
{
    $linenum=$this->input->post('id');
    $AuctionID=$this->input->post('AuctionID');
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $Section='';
    $message='';
    if($oldData->cmcode != $newData->cmcode) {
        $Section='Cargo';
        $message .='<br>Old Cargo : '.$oldData->cmcode.' New Cargo : '.$newData->cmcode;
    }
    if($oldData->CargoQtyMT != $newData->CargoQtyMT) {
        $Section='Cargo';
        $message .='<br>Old Cargo Qty : '.number_format($oldData->CargoQtyMT).' New Cargo Qty: '.number_format($newData->CargoQtyMT);
    }
    if($oldData->CargoLoadedBasis != $newData->CargoLoadedBasis) {
        $Section='Cargo';
        $message .='<br>Old Cargo Loaded Basis : '.$oldData->CargoLoadedBasis.' New Cargo Loaded Basis : '.$newData->CargoLoadedBasis;
    }
    if($oldData->CargoLimitBasis != $newData->CargoLimitBasis) {
        $Section='Cargo';
        if($oldData->CargoLimitBasis=='1') {
            $OldCargoLimitBasis='Max and Min';
        }else if($oldData->CargoLimitBasis=='2') {
            $OldCargoLimitBasis='% Tolerence Limit';
        }
        if($newData->CargoLimitBasis=='1') {
            $NewCargoLimitBasis='Max and Min';
        }else if($newData->CargoLimitBasis=='2') {
            $NewCargoLimitBasis='% Tolerence Limit';
        }
            
            $message .='<br>Old Cargo quantity limit basis : '.$OldCargoLimitBasis.' New Cargo quantity limit basis : '.$NewCargoLimitBasis;
            
        if($oldData->CargoLimitBasis=='1' && $newData->CargoLimitBasis=='2') {
                $message .='Old Max Cargo Amount : '.$oldData->MaxCargoMT.'Old Min Cargo Amount : '.(int) $oldData->MinCargoMT.' New Tolerence Limit : '.(int) $newData->ToleranceLimit.' Upper Limit : '.(int) $newData->UpperLimit.' LowerLimit : '.(int) $newData->LowerLimit;
        }
            
        if($oldData->CargoLimitBasis=='2' && $newData->CargoLimitBasis=='1') {
                $message .='<br>Old Tolerence Limit : '.$oldData->ToleranceLimit.' Upper Limit : '.(int) $oldData->UpperLimit.' LowerLimit : '.(int) $oldData->LowerLimit.' New Max Cargo Amount : '.(int) $newData->MaxCargoMT.'New Min Cargo Amount : '.(int) $newData->MinCargoMT;
        }
            
    }
        
    if($oldData->ToleranceLimit != $newData->ToleranceLimit) {
        $Section='Cargo';
        $message .='<br>Old Tolerence Limit : '.$oldData->ToleranceLimit.' Upper Limit : '.(int) $oldData->UpperLimit.' LowerLimit : '.(int) $oldData->LowerLimit.' New Tolerence Limit : '.$newData->ToleranceLimit.' Upper Limit : '.(int) $newData->UpperLimit.' LowerLimit : '.(int) $newData->LowerLimit;
    }
        
    if($oldData->MaxCargoMT != $newData->MaxCargoMT) {
        $Section='Cargo';
        $message .='<br>Old Max Cargo Limit : '.(int) $oldData->MaxCargoMT.' New Max Cargo Limit : '.(int) $newData->MaxCargoMT;
    }
    if($oldData->MinCargoMT != $newData->MinCargoMT) {
        $Section='Cargo';
        $message .='<br>Old Min Cargo Limit : '.(int) $oldData->MinCargoMT.' New Min Cargo Limit : '.(int) $newData->MinCargoMT;
    }
        
    if($oldData->CargoInternalComments != $newData->CargoInternalComments) {
        $Section='Cargo';
        $message .='<br>Old Cargo owner Comment : '.$oldData->CargoInternalComments.' New Cargo owner Comment : '.$newData->CargoInternalComments;
    }
    if($oldData->CargoDisplayComments != $newData->CargoDisplayComments) {
        $Section='Cargo';
        $message .='<br>Old Cargo Invitee Comment : '.$oldData->CargoDisplayComments.' New Cargo Invitee Comment : '.$newData->CargoDisplayComments;
    }
        
    if($Section == 'Cargo') {
        if($msgData) {
            $cargodata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Cargo & Ports',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserId,
            'FromUserID'=>$UserId,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $cargodata); 
                
            $msg_data=array(
            'MessageFlag'=>'1',
            'MsgDate'=>date('Y-m-d H:i:s')
                                );
                                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', $msg_data);
        }
    }
        
    $message ='';
        
    if($oldData->LoadPort != $newData->LoadPort) {
        $Section='Loadport';
        $message .='<br>Old LoadPort : '.$oldData->lpDescription.' New LoadPort : '.$newData->lpDescription;
    }
        
    $oldLayCanStartDate=date('Y-m-d H:i:s', strtotime($oldData->LpLaycanStartDate));
    $newLayCanStartDate=date('Y-m-d H:i:s', strtotime($newData->LpLaycanStartDate));
        
    if($oldLayCanStartDate != $newLayCanStartDate) {
        $Section='Loadport';
        $message .='<br>Old LayCan Start Date : '.date('d-m-Y H:i:s', strtotime($oldLayCanStartDate)).' New LayCan Start Date : '.date('d-m-Y H:i:s', strtotime($newLayCanStartDate));
    }
        
    $oldLaycanEndDate=date('Y-m-d H:i:s', strtotime($oldData->LpLaycanEndDate));
    $newLaycanEndDate=date('Y-m-d H:i:s', strtotime($newData->LpLaycanEndDate));
        
    if($oldLaycanEndDate != $newLaycanEndDate) {
        $Section='Loadport';
        $message .='<br>Old LayCan End Date : '.date('d-m-Y H:i:s', strtotime($oldLaycanEndDate)).' New LayCan End Date : '.date('d-m-Y H:i:s', strtotime($newLaycanEndDate));
    }
        
    $oldLpPreferDate=date('Y-m-d H:i:s', strtotime($oldData->LpPreferDate));
    $newLpPreferDate=date('Y-m-d H:i:s', strtotime($newData->LpPreferDate));
        
    if($oldLpPreferDate != $newLpPreferDate) {
        $Section='Loadport';
        $message .='<br>Old LayCan End Date : '.date('d-m-Y H:i:s', strtotime($oldLpPreferDate)).' New LayCan End Date : '.date('d-m-Y H:i:s', strtotime($newLpPreferDate));
    }
        
    if($oldData->ExpectedLpDelayDay != $newData->ExpectedLpDelayDay) {
        $Section='Loadport';
        $message .='<br>Old Expected Loadport Delay : '.$oldData->ExpectedLpDelayDay.' Days '.$oldData->ExpectedLpDelayHour.' Hours and New Expected Loadport Delay : '.$newData->ExpectedLpDelayDay.' Days '.$newData->ExpectedLpDelayHour.' Hours';
    }else if($oldData->ExpectedLpDelayHour != $newData->ExpectedLpDelayHour) {
        $Section='Loadport';
        $message .='<br>Old Expected Loadport Delay : '.$oldData->ExpectedLpDelayDay.' Days '.$oldData->ExpectedLpDelayHour.' Hours and New Expected Loadport Delay : '.$newData->ExpectedLpDelayDay.' Days '.$newData->ExpectedLpDelayHour.' Hours';
    }
        
    if($oldData->LoadingTerms != $newData->LoadingTerms) {
        $Section='Loadport';
        $message .='<br>Old Loading Term : '.$oldData->ldtDescription.' New Loading Term : '.$newData->ldtDescription;
    }
        
    $oldLoadingRateMT=(int)$oldData->LoadingRateMT;
    $newLoadingRateMT=(int)$newData->LoadingRateMT;
    if($oldLoadingRateMT != $newLoadingRateMT) {
        $Section='Loadport';
        $message .='<br>Old Loading Rate : '.$oldLoadingRateMT.' New Loading Rate : '.$newLoadingRateMT;
    }
        
    if($oldData->LoadingRateUOM != $newData->LoadingRateUOM) {
        $Section='Loadport';
        if($oldData->LoadingRateUOM=='1') {
            $oldLoadingRateUOM='Per Hour';
        }else if($oldData->LoadingRateUOM=='2') {
            $oldLoadingRateUOM='Per Weater Working Day';
        }else if($oldData->LoadingRateUOM=='3') {
            $oldLoadingRateUOM='Max Time Limit';
        }
        if($newData->LoadingRateUOM=='1') {
            $newLoadingRateUOM='Per Hour';
        }else if($newData->LoadingRateUOM=='2') {
            $newLoadingRateUOM='Per Weater Working Day';
        }else if($newData->LoadingRateUOM=='3') {
            $newLoadingRateUOM='Max Time Limit';
        }
            $message .='<br>Old Loading Rate UOM: '.$oldLoadingRateUOM.' New Loading Rate UOM: '.$newLoadingRateUOM;
    }
        
    if($oldData->LpLaytimeType != $newData->LpLaytimeType) {
        $Section='Loadport';
        if($oldData->LpLaytimeType=='1') {
            $oldLpLaytimeType='Reversible';
        }else if($oldData->LpLaytimeType=='2') {
            $oldLpLaytimeType='Non Reversible';
        }else if($oldData->LpLaytimeType=='3') {
            $oldLpLaytimeType='Average';
        }
        if($newData->LpLaytimeType=='1') {
            $newLpLaytimeType='Reversible';
        }else if($newData->LpLaytimeType=='2') {
            $newLpLaytimeType='Non Reversible';
        }else if($newData->LpLaytimeType=='3') {
            $newLpLaytimeType='Average';
        }
            $message .='<br>Old Loading Laytime type: '.$oldLpLaytimeType.' New Loading Laytime type: '.$newLpLaytimeType;
    }
        
    if($oldData->LpCalculationBasedOn != $newData->LpCalculationBasedOn) {
        $Section='Loadport';
        if($oldData->LpCalculationBasedOn=='108') {
            $oldLpCalculationBasedOn='Bill of Loading Quantity';
        }else if($oldData->LpCalculationBasedOn=='109') {
            $oldLpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
        if($newData->LpCalculationBasedOn=='108') {
            $newLpCalculationBasedOn='Bill of Loading Quantity';
        }else if($newData->LpCalculationBasedOn=='109') {
            $newLpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
            $message .='<br>Old Loading Calculation Based on: '.$oldLpCalculationBasedOn.' New Loading Calculation Based on: '.$newLpCalculationBasedOn;
    }
        
    if($oldData->LpTurnTime != $newData->LpTurnTime) {
        $Section='Loadport';
        if($oldData->LpTurnTime=='1') {
            $oldLpTurnTime='LT freetime';
        }else if($oldData->LpTurnTime=='2') {
            $oldLpTurnTime='LayTime Free test';
        } else if($oldData->LpTurnTime=='3') {
            $oldLpTurnTime='12HAA';
        } else if($oldData->LpTurnTime=='4') {
            $oldLpTurnTime='24HAA';
        } else if($oldData->LpTurnTime=='5') {
                $oldLpTurnTime='4HAA';
        } else if($oldData->LpTurnTime=='6') {
            $oldLpTurnTime='6HAA';
        } else if($oldData->LpTurnTime=='7') {
            $oldLpTurnTime='8HAA';
        } else if($oldData->LpTurnTime=='8') {
            $oldLpTurnTime='16HAA ';
        } else if($oldData->LpTurnTime=='9') {
            $oldLpTurnTime='20HAA';
        } else if($oldData->LpTurnTime=='10') {
            $oldLpTurnTime='18HAA';
        } 
            
        if($newData->LpTurnTime=='1') {
            $newLpTurnTime='LT freetime';
        }else if($newData->LpTurnTime=='2') {
            $newLpTurnTime='LayTime Free test';
        } else if($newData->LpTurnTime=='3') {
            $newLpTurnTime='12HAA';
        } else if($newData->LpTurnTime=='4') {
            $newLpTurnTime='24HAA';
        } else if($newData->LpTurnTime=='5') {
            $newLpTurnTime='4HAA';
        } else if($newData->LpTurnTime=='6') {
            $newLpTurnTime='6HAA';
        } else if($newData->LpTurnTime=='7') {
            $newLpTurnTime='8HAA';
        } else if($newData->LpTurnTime=='8') {
            $newLpTurnTime='16HAA ';
        } else if($newData->LpTurnTime=='9') {
            $newLpTurnTime='20HAA';
        } else if($newData->LpTurnTime=='10') {
            $newLpTurnTime='18HAA';
        } 
            
            $message .='<br>Old Loading Turn Time: '.$oldLpTurnTime.' New Loading Turn Time: '.$newLpTurnTime;
    }
        
    if($oldData->LpPriorUseTerms != $newData->LpPriorUseTerms) {
        $Section='Loadport';
        if($oldData->LpPriorUseTerms=='102') {
            $oldLpPriorUseTerms='IUATUTC';
        }else if($oldData->LpPriorUseTerms=='10') {
            $oldLpPriorUseTerms='IUHTUTC ';
        } 
        if($newData->LpPriorUseTerms=='102') {
            $newLpPriorUseTerms='IUATUTC';
        }else if($newData->LpPriorUseTerms=='10') {
            $newLpPriorUseTerms='IUHTUTC ';
        } 
            $message .='<br>Old Loading Prior Use Term: '.$oldLpPriorUseTerms.' New Loading Prior Use Term: '.$newLpPriorUseTerms;
    }
    if($oldData->LpLaytimeBasedOn != $newData->LpLaytimeBasedOn) {
        $Section='Loadport';
        if($oldData->LpLaytimeBasedOn=='1') {
            $oldLpLaytimeBasedOn='ATS';
        }else if($oldData->LpLaytimeBasedOn=='2') {
            $oldLpLaytimeBasedOn='WTS ';
        } 
        if($newData->LpLaytimeBasedOn=='1') {
            $newLpLaytimeBasedOn='ATS';
        }else if($newData->LpLaytimeBasedOn=='2') {
            $newLpLaytimeBasedOn='WTS ';
        } 
            $message .='<br>Old Loading laytime Based on: '.$oldLpLaytimeBasedOn.' New Loading laytime Based on: '.$newLpLaytimeBasedOn;
    }
    if($oldData->LpCharterType != $newData->LpCharterType) {
        $Section='Loadport';
        if($oldData->LpCharterType=='1') {
            $oldLpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        } else if($oldData->LpCharterType=='2') {
            $oldLpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        } else if($oldData->LpCharterType=='3') {
            $oldLpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        } else if($oldData->LpCharterType=='4') {
            $oldLpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
        if($newData->LpCharterType=='1') {
                $newLpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        } else if($newData->LpCharterType=='2') {
                $newLpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        } else if($newData->LpCharterType=='3') {
            $newLpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        } else if($newData->LpCharterType=='4') {
            $newLpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
            $message .='<br>Old Loading Charter Type: '.$oldLpCharterType.' New Loading Charter Type: '.$newLpCharterType;
    }
    if($oldData->LpNorTendering != $newData->LpNorTendering) {
        $Section='Loadport';
        if($oldData->LpNorTendering=='1') {
            $oldLpNorTendering='ATDNSHINC';
        }else if($oldData->LpNorTendering=='2') {
            $oldLpNorTendering='ATDNFHINC';
        }else if($oldData->LpNorTendering=='3') {
            $oldLpNorTendering='OFFICE HOURS';
        } else if($oldData->LpNorTendering=='4') {
            $oldLpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
        if($newData->LpNorTendering=='1') {
                $newLpNorTendering='ATDNSHINC';
        }else if($newData->LpNorTendering=='2') {
                $newLpNorTendering='ATDNFHINC';
        }else if($newData->LpNorTendering=='3') {
            $newLpNorTendering='OFFICE HOURS';
        }else if($newData->LpNorTendering=='4') {
            $newLpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
            $message .='<br>Old Loading NorTendering : '.$oldLpNorTendering.' New Loading NorTendering : '.$newLpNorTendering;
    }
        
    if($oldData->LpStevedoringTerms != $newData->LpStevedoringTerms) {
        $Section='Loadport';
        $message .='<br>Old Loading Stevedoring terms : '.$oldData->stvCode.' New Loading Stevedoring terms : '.$newData->stvCode;
    }
        
    if($oldData->ExceptedPeriodFlg != $newData->ExceptedPeriodFlg) {
        $oldExceptedPeriodFlg='No';
        if($oldData->ExceptedPeriodFlg==1) {
            $oldExceptedPeriodFlg='Yes';
        }
        $newExceptedPeriodFlg='No';
        if($newData->ExceptedPeriodFlg==1) {
            $newExceptedPeriodFlg='Yes';
        }
        $Section='Loadport';
        $message .='<br>Old Loading Excepted periods for events : '.$oldExceptedPeriodFlg.' New Loading Excepted periods for events : '.$newExceptedPeriodFlg;
    }
        
    if($oldData->ExceptedPeriodFlg == $newData->ExceptedPeriodFlg && $oldData->ExceptedPeriodFlg==1) {
        for($exp=0; $exp < count($expDataOld) && $exp < count($expDataNew); $exp++){
            $OldLaytimeCountsOnDemurrageFlg='-';
            $OldLaytimeCountsFlg='-';
            $OldTimeCountingFlg='-';
            $NewLaytimeCountsOnDemurrageFlg='-';
            $NewLaytimeCountsFlg='-';
            $NewTimeCountingFlg='-';
            if($expDataOld[$exp]['EventID'] != $expDataNew[$exp]['EventID']) {
                $Section='Loadport';
                $message .='<br>Old Event name : '.$expDataOld[$exp]['ExceptedDescription'].'  New Event name : '.$expDataNew[$exp]['ExceptedDescription'];
            }
            if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg'] != $expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']) {
                if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                    $OldLaytimeCountsOnDemurrageFlg='Yes';
                } else if($expDataOld[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                    $OldLaytimeCountsOnDemurrageFlg='No';
                }
                if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==1) {
                    $NewLaytimeCountsOnDemurrageFlg='Yes';
                } else if($expDataNew[$exp]['LaytimeCountsOnDemurrageFlg']==2) {
                    $NewLaytimeCountsOnDemurrageFlg='No';
                }
                $Section='Loadport';
                $message .='<br>Old Laytime Counts on demurrage  : '.$OldLaytimeCountsOnDemurrageFlg.' New Laytime Counts on demurrage  : '.$NewLaytimeCountsOnDemurrageFlg;
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
                $Section='Loadport';
                $message .='<br>Old Laytime counts, if used : '.$OldLaytimeCountsFlg.' New Laytime counts, if used  : '.$NewLaytimeCountsFlg;
            }
            if($expDataOld[$exp]['TimeCountingFlg'] != $expDataNew[$exp]['TimeCountingFlg']) {
                if($expDataOld[$exp]['TimeCountingFlg']==102) {
                    $OldTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                } else if($expDataOld[$exp]['TimeCountingFlg']==10) {
                    $OldTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                }
                    
                if($expDataNew[$exp]['TimeCountingFlg']==102) {
                    $NewTimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                } else if($expDataNew[$exp]['TimeCountingFlg']==10) {
                    $NewTimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                }
                $Section='Loadport';
                $message .='<br>Old Time counting, if used : '.$OldTimeCountingFlg.' New Time counting, if used : '.$NewTimeCountingFlg;
            }
        }
                
    }
        
    if($oldData->NORTenderingPreConditionFlg != $newData->NORTenderingPreConditionFlg) {
        $oldNORTenderingPreConditionFlg='No';
        if($oldData->NORTenderingPreConditionFlg==1) {
            $oldNORTenderingPreConditionFlg='Yes';
        }
        $newNORTenderingPreConditionFlg='No';
        if($newData->NORTenderingPreConditionFlg==1) {
            $newNORTenderingPreConditionFlg='Yes';
        }
        $Section='Loadport';
        $message .='<br>Old Loading NOR tendering pre conditions apply : '.$oldNORTenderingPreConditionFlg.' New Loading NOR tendering pre conditions apply : '.$newNORTenderingPreConditionFlg;
    }
        
    if($oldData->NORTenderingPreConditionFlg == $newData->NORTenderingPreConditionFlg && $oldData->NORTenderingPreConditionFlg==1) {
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
                    $Section='Loadport';
                    $message .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
            }
            if($tenderingOldData[$tend]['NORTenderingPreConditionID'] != $tenderingNewData[$tend]['NORTenderingPreConditionID']) {
                $Section='Loadport';
                $message .='<br>Old Name of condition : '.$tenderingOldData[$tend]['TenderingCode'].' New Name of condition : '.$tenderingNewData[$tend]['TenderingCode'];
            }
            if($tenderingOldData[$tend]['NewNORTenderingPreCondition'] != $tenderingNewData[$tend]['NewNORTenderingPreCondition']) {
                $Section='Loadport';
                $message .='<br>Old Name of condition : '.$tenderingOldData[$tend]['NewNORTenderingPreCondition'].' New Name of condition : '.$tenderingNewData[$tend]['NewNORTenderingPreCondition'];
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
                $Section='Loadport';
                $message .='<br>Old Activate : '.$StatusFlagOld.' New Activate : '.$StatusFlagNew;
            }
        }
    }
            
    if($oldData->NORAcceptancePreConditionFlg != $newData->NORAcceptancePreConditionFlg) {
        $oldNORAcceptancePreConditionFlg='No';
        if($oldData->NORAcceptancePreConditionFlg==1) {
            $oldNORAcceptancePreConditionFlg='Yes';
        }
        $newNORAcceptancePreConditionFlg='No';
        if($newData->NORAcceptancePreConditionFlg==1) {
            $newNORAcceptancePreConditionFlg='Yes';
        }
        $Section='Loadport';
        $message .='<br>Old Loading NOR acceptance pre conditions apply : '.$oldNORAcceptancePreConditionFlg.' New Loading NOR acceptance pre conditions apply : '.$newNORAcceptancePreConditionFlg;
    }
        
    if($oldData->NORAcceptancePreConditionFlg == $newData->NORAcceptancePreConditionFlg && $oldData->NORAcceptancePreConditionFlg==1) {
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
                    $Section='Loadport';
                    $message .='<br>Old Create new / select from pre defined list : '.$CreateNewOrSelectListFlgOld.' New Create new / select from pre defined list : '.$CreateNewOrSelectListFlgNew;
            }
            if($acceptOldData[$accept]['NORAcceptancePreConditionID'] != $acceptNewData[$accept]['NORAcceptancePreConditionID']) {
                $Section='Loadport';
                $message .='<br>Old Name of condition : '.$acceptOldData[$accept]['AcceptanceCode'].' New Name of condition : '.$acceptNewData[$accept]['AcceptanceCode'];
            }
            if($acceptOldData[$accept]['NewNORAcceptancePreCondition'] != $acceptNewData[$accept]['NewNORAcceptancePreCondition']) {
                $Section='Loadport';
                $message .='<br>Old Name of condition : '.$acceptOldData[$accept]['NewNORAcceptancePreCondition'].' New Name of condition : '.$acceptNewData[$accept]['NewNORAcceptancePreCondition'];
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
                $Section='Loadport';
                $message .='<br>Old Activate : '.$StatusFlagOld.' New Activate : '.$StatusFlagNew;
            }
        }
            
    }
        
    if($oldData->OfficeHoursFlg != $newData->OfficeHoursFlg) {
        $oldOfficeHoursFlg='No';
        if($oldData->OfficeHoursFlg==1) {
            $oldOfficeHoursFlg='Yes';
        }
        $newOfficeHoursFlg='No';
        if($newData->OfficeHoursFlg==1) {
            $newOfficeHoursFlg='Yes';
        }
        $Section='Loadport';
        $message .='<br>Old Loading Office hours apply : '.$oldOfficeHoursFlg.' New Loading Office hours apply : '.$newOfficeHoursFlg;
    }
        
    if($oldData->OfficeHoursFlg == $newData->OfficeHoursFlg && $oldData->OfficeHoursFlg==1) {
        for($office=0; $office < count($officeOldData) && $office < count($officeNewData); $office++){
            if($officeOldData[$office]['DateFrom'] != $officeNewData[$office]['DateFrom']) {
                $Section='Loadport';
                $message .='<br>Old Day (From) : '.$officeOldData[$office]['DateFrom'].' New Day (From) : '.$officeNewData[$office]['DateFrom'];
            }
            if($officeOldData[$office]['DateTo'] != $officeNewData[$office]['DateTo']) {
                $Section='Loadport';
                $message .='<br>Old Day (To) : '.$officeOldData[$office]['DateTo'].' New Day (To) : '.$officeNewData[$office]['DateTo'];
            }
            if($officeOldData[$office]['TimeFrom'] != $officeNewData[$office]['TimeFrom']) {
                $Section='Loadport';
                $message .='<br>Old Time (From) : '.$officeOldData[$office]['TimeFrom'].' New Time (From) : '.$officeNewData[$office]['TimeFrom'];
            }
            if($officeOldData[$office]['TimeTo'] != $officeNewData[$office]['TimeTo']) {
                $Section='Loadport';
                $message .='<br>Old Time (To) : '.$officeOldData[$office]['TimeTo'].' New Time (To) : '.$officeNewData[$office]['TimeTo'];
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
                $Section='Loadport';
                $message .='<br>Old Is last entry : '.$IsLastEntryOld.' New Is last entry : '.$IsLastEntryNew;
            }
        }
    }
        
    if($oldData->LaytimeCommencementFlg != $newData->LaytimeCommencementFlg) {
        $oldLaytimeCommencementFlg='No';
        if($oldData->LaytimeCommencementFlg==1) {
            $oldLaytimeCommencementFlg='Yes';
        }
        $newLaytimeCommencementFlg='No';
        if($newData->LaytimeCommencementFlg==1) {
            $newLaytimeCommencementFlg='Yes';
        }
        $Section='Loadport';
        $message .='<br>Old Loading laytime commencement apply : '.$oldLaytimeCommencementFlg.' New Loading laytime commencement apply : '.$newLaytimeCommencementFlg;
    }
        
    if($oldData->LaytimeCommencementFlg == $newData->LaytimeCommencementFlg && $oldData->LaytimeCommencementFlg==1) {
        for($lay=0; $lay < count($laytimeOldData) && $lay < count($laytimeNewData); $lay++){
            if($laytimeOldData[$lay]['DayFrom'] != $laytimeNewData[$lay]['DayFrom']) {
                $Section='Loadport';
                $message .='<br>Old Day (From) : '.$laytimeOldData[$lay]['DayFrom'].' New Day (From) : '.$laytimeNewData[$lay]['DayFrom'];
            }
            if($laytimeOldData[$lay]['DayTo'] != $laytimeNewData[$lay]['DayTo']) {
                $Section='Loadport';
                $message .='<br>Old Day (To) : '.$laytimeOldData[$lay]['DayTo'].' New Day (To) : '.$laytimeNewData[$lay]['DayTo'];
            }
            if($laytimeOldData[$lay]['TimeFrom'] != $laytimeNewData[$lay]['TimeFrom']) {
                $Section='Loadport';
                $message .='<br>Old Time (From) : '.$laytimeOldData[$lay]['TimeFrom'].' New Time (From) : '.$laytimeNewData[$lay]['TimeFrom'];
            }
            if($laytimeOldData[$lay]['TimeTo'] != $laytimeNewData[$lay]['TimeTo']) {
                $Section='Loadport';
                $message .='<br>Old Time (To) : '.$laytimeOldData[$lay]['TimeTo'].' New Time (To) : '.$laytimeNewData[$lay]['TimeTo'];
            }
            if($laytimeOldData[$lay]['TurnTime'] != $laytimeNewData[$lay]['TurnTime']) {
                $Section='Loadport';
                $message .='<br>Old Turn time applies : '.$laytimeOldData[$lay]['LaytimeCode'].' New Turn time applies : '.$laytimeNewData[$lay]['LaytimeCode'];
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
                $Section='Loadport';
                $message .='<br>Old Turn time expires : '.$TurnTimeExpireOld.' New Turn time expires : '.$TurnTimeExpireNew;
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
                $Section='Loadport';
                $message .='<br>Old laytime commences at : '.$LaytimeCommenceAtOld.' New laytime commences at : '.$LaytimeCommenceAtNew;
            }
            if($laytimeOldData[$lay]['LaytimeCommenceAtHour'] != $laytimeNewData[$lay]['LaytimeCommenceAtHour']) {
                $Section='Loadport';
                $message .='<br>Old Turn time expires : '.$laytimeOldData[$lay]['LaytimeCommenceAtHour'].'  New Turn time expires : '.$laytimeNewData[$lay]['LaytimeCommenceAtHour'];
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
                $Section='Loadport';
                $message .='<br>Old Select day : '.$OldSelectDay.' New Select day : '.$NewSelectDay;
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
                $Section='Loadport';
                $message .='<br>Old Time counts if on Demurrage : '.$OldTimeCountsIfOnDemurrage.' New Time counts if on Demurrage : '.$NewTimeCountsIfOnDemurrage;
            }
        }
    }
        
    if($Section == 'Loadport') {
        if($msgData) {
            $LoadPortdata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Cargo & Ports',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserId,
            'FromUserID'=>$UserId,
            'UserDate'=>date('Y-m-d H:i:s')
            );
                
            $this->db->insert('udt_AU_Messsage_Details', $LoadPortdata); 
                
            $msg_data=array(
            'MessageFlag'=>'1',
            'MsgDate'=>date('Y-m-d H:i:s')
                                );
                                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', $msg_data);
        }
        
    }
        
}
    
public function getLoadportExceptedPeriodDetailsByCargoID($CargoID)
{
    $this->db->select('udt_AU_ExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
    $this->db->from('udt_AU_ExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ExceptedPeriods.EventID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('EPID', 'asc');
    $qry=$this->db->get();
    return $qry->result_array();
}
    
public function getLoadportNORTenderDetailsByCargoID($CargoID)
{
    $this->db->select('udt_AU_NORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_NORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_NORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('TPCID', 'asc');
    $qry2=$this->db->get();
    return $qry2->result_array();
}
    
public function getLoadportNORAcceptanceDetailsByCargoID($CargoID)
{
    $this->db->select('udt_AU_NORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_NORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_NORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('APCID', 'asc');
    $qry3=$this->db->get();
    return $qry3->result_array();
}
    
public function getLoadportOfficeHoursDetailsByCargoID($CargoID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_OfficeHours');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('OHID', 'asc');
    $qry4=$this->db->get();
    return $qry4->result_array();
}
    
public function getLoadportLaytimeDetailsByCargoID($CargoID)
{
    $this->db->select('udt_AU_LaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_LaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_LaytimeCommencement.TurnTime', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('LCID', 'asc');
    $qry5=$this->db->get();
    return $qry5->result_array();
}
    
    
public function saveCargoDisportMessage($oldData,$newData)
{
    $UserID=$this->input->post('UserID');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $message ='';
        
    if($oldData->DisPort != $newData->DisPort) {
        $Section='Disport';
        $message .='<br>Old DisPort : '.$oldData->dspPortName.' New DisPort : '.$newData->dspPortName;
    }
    $oldDpArrivalStartDate=date('Y-m-d H:i:s', strtotime($oldData->DpArrivalStartDate));
    $newDpArrivalStartDate=date('Y-m-d H:i:s', strtotime($newData->DpArrivalStartDate));
    if($oldDpArrivalStartDate != $newDpArrivalStartDate) {
        $Section='Disport';
        $message .='<br>Old DisPort Arrival Start Date : '.date('d-m-Y H:i:s', strtotime($oldDpArrivalStartDate)).' New DisPort Arrival Start Date : '.date('d-m-Y H:i:s', strtotime($newDpArrivalStartDate));
    }
    $oldDpArrivalEndDate=date('Y-m-d H:i:s', strtotime($oldData->DpArrivalEndDate));
    $newDpArrivalEndDate=date('Y-m-d H:i:s', strtotime($newData->DpArrivalEndDate));
    if($oldDpArrivalEndDate != $newDpArrivalEndDate) {
        $Section='Disport';
        $message .='<br>Old DisPort Arrival End Date : '.date('d-m-Y H:i:s', strtotime($oldDpArrivalEndDate)).' New DisPort Arrival End Date : '.date('d-m-Y H:i:s', strtotime($newDpArrivalEndDate));
    }
    $oldDpPreferDate=date('Y-m-d H:i:s', strtotime($oldData->DpPreferDate));
    $newDpPreferDate=date('Y-m-d H:i:s', strtotime($newData->DpPreferDate));
    if($oldDpPreferDate != $newDpPreferDate) {
        $Section='Disport';
        $message .='<br>Old DisPort Preferred Date : '.date('d-m-Y H:i:s', strtotime($oldDpPreferDate)).' New DisPort Preferred Date : '.date('d-m-Y H:i:s', strtotime($newDpPreferDate));
    }
    if($oldData->ExpectedDpDelayDay != $newData->ExpectedDpDelayDay) {
        $Section='Disport';
        $message .='<br>Old Expected Disport Delay : '.$oldData->ExpectedDpDelayDay.' Days '.$oldData->ExpectedDpDelayHour.' Hours and New Expected Disport Delay : '.$newData->ExpectedDpDelayDay.' Days '.$newData->ExpectedDpDelayHour.' Hours';
    }else if($oldData->ExpectedDpDelayHour != $newData->ExpectedDpDelayHour) {
        $Section='Disport';
        $message .='<br>Old Expected Disport Delay : '.$oldData->ExpectedDpDelayDay.' Days '.$oldData->ExpectedDpDelayHour.' Hours and New Expected Disport Delay : '.$newData->ExpectedDpDelayDay.' Days '.$newData->ExpectedDpDelayHour.' Hours';
    }
        
    if($oldData->DischargingTerms != $newData->DischargingTerms) {
        $Section='Disport';
        $message .='<br>Old Discharging Term : '.$oldData->trmDescription.' New Discharging Term : '.$newData->trmDescription;
    }
        
    $oldDischargingRateMT=(int)$oldData->DischargingRateMT;
    $newDischargingRateMT=(int)$newData->DischargingRateMT;
    if($oldDischargingRateMT != $newDischargingRateMT) {
        $Section='Disport';
        $message .='<br>Old Discharging Rate : '.$oldDischargingRateMT.' New Discharging Rate : '.$newDischargingRateMT;
    }
        
    if($oldData->DischargingRateUOM != $newData->DischargingRateUOM) {
        $Section='Disport';
        if($oldData->DischargingRateUOM=='1') {
            $oldDischargingRateUOM='Per Hour';
        }else if($oldData->DischargingRateUOM=='2') {
            $oldDischargingRateUOM='Per Weater Working Day';
        }else if($oldData->DischargingRateUOM=='3') {
            $oldDischargingRateUOM='Max Time Limit';
        }
        if($newData->DischargingRateUOM=='1') {
            $newDischargingRateUOM='Per Hour';
        }else if($newData->DischargingRateUOM=='2') {
            $newDischargingRateUOM='Per Weater Working Day';
        }else if($newData->DischargingRateUOM=='3') {
            $newDischargingRateUOM='Max Time Limit';
        }
            $message .='<br>Old Loading Rate UOM: '.$oldDischargingRateUOM.' New Loading Rate UOM: '.$newDischargingRateUOM;
    }
        
    if($oldData->DpLaytimeType != $newData->DpLaytimeType) {
        $Section='Disport';
        if($oldData->DpLaytimeType=='1') {
            $oldDpLaytimeType='Reversible';
        }else if($oldData->DpLaytimeType=='2') {
            $oldDpLaytimeType='Non Reversible';
        }else if($oldData->DpLaytimeType=='3') {
            $oldDpLaytimeType='Average';
        }
        if($newData->DpLaytimeType=='1') {
            $newDpLaytimeType='Reversible';
        }else if($newData->DpLaytimeType=='2') {
            $newDpLaytimeType='Non Reversible';
        }else if($newData->DpLaytimeType=='3') {
            $newDpLaytimeType='Average';
        }
            $message .='<br>Old Discharging Laytime type: '.$oldDpLaytimeType.' New Discharging Laytime type: '.$newDpLaytimeType;
    }
        
    if($oldData->DpCalculationBasedOn != $newData->DpCalculationBasedOn) {
        $Section='Disport';
        if($oldData->DpCalculationBasedOn=='108') {
            $oldDpCalculationBasedOn='Bill of Loading Quantity';
        }else if($oldData->DpCalculationBasedOn=='109') {
            $oldDpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
        if($newData->DpCalculationBasedOn=='108') {
            $newDpCalculationBasedOn='Bill of Loading Quantity';
        }else if($newData->DpCalculationBasedOn=='109') {
            $newDpCalculationBasedOn='Outturn or Discharge Quantity';
        } 
            $message .='<br>Old Discharging Calculation Based on: '.$oldDpCalculationBasedOn.' New Discharging Calculation Based on: '.$newDpCalculationBasedOn;
    }
    if($oldData->DpTurnTime != $newData->DpTurnTime) {    
        $Section='Disport';
        if($oldData->DpTurnTime=='1') {
            $oldDpTurnTime='LT freetime';
        }else if($oldData->DpTurnTime=='2') {
            $oldDpTurnTime='LayTime Free test';
        } else if($oldData->DpTurnTime=='3') {
            $oldDpTurnTime='12HAA';
        } else if($oldData->DpTurnTime=='4') {
            $oldDpTurnTime='24HAA';
        } else if($oldData->DpTurnTime=='5') {
                $oldDpTurnTime='4HAA';
        } else if($oldData->DpTurnTime=='6') {
            $oldDpTurnTime='6HAA';
        } else if($oldData->DpTurnTime=='7') {
            $oldDpTurnTime='8HAA';
        } else if($oldData->DpTurnTime=='8') {
            $oldDpTurnTime='16HAA ';
        } else if($oldData->DpTurnTime=='9') {
            $oldDpTurnTime='20HAA';
        } else if($oldData->DpTurnTime=='10') {
            $oldDpTurnTime='18HAA';
        } 
            
        if($newData->DpTurnTime=='1') {
            $newDpTurnTime='LT freetime';
        }else if($newData->DpTurnTime=='2') {
            $newDpTurnTime='LayTime Free test';
        } else if($newData->DpTurnTime=='3') {
            $newDpTurnTime='12HAA';
        } else if($newData->DpTurnTime=='4') {
            $newDpTurnTime='24HAA';
        } else if($newData->DpTurnTime=='5') {
            $newDpTurnTime='4HAA';
        } else if($newData->DpTurnTime=='6') {
            $newDpTurnTime='6HAA';
        } else if($newData->DpTurnTime=='7') {
            $newDpTurnTime='8HAA';
        } else if($newData->DpTurnTime=='8') {
            $newDpTurnTime='16HAA ';
        } else if($newData->DpTurnTime=='9') {
            $newDpTurnTime='20HAA';
        } else if($newData->DpTurnTime=='10') {
            $newDpTurnTime='18HAA';
        } 
            
            $message .='<br>Old Disport Turn Time: '.$oldDpTurnTime.' New Disport Turn Time: '.$newDpTurnTime;
    }
        
    if($oldData->DpPriorUseTerms != $newData->DpPriorUseTerms) {
        $Section='Disport';
        if($oldData->DpPriorUseTerms=='102') {
            $oldDpPriorUseTerms='IUATUTC';
        }else if($oldData->DpPriorUseTerms=='10') {
            $oldDpPriorUseTerms='IUHTUTC ';
        }else{
            $oldDpPriorUseTerms='N/A ';
        }
        if($newData->DpPriorUseTerms=='102') {
            $newDpPriorUseTerms='IUATUTC';
        }else if($newData->DpPriorUseTerms=='10') {
            $newDpPriorUseTerms='IUHTUTC ';
        } else{
                $newDpPriorUseTerms='N/A';
        }
            $message .='<br>Old Disport Prior Use Term: '.$oldDpPriorUseTerms.' New Disport Prior Use Term: '.$newDpPriorUseTerms;
    }
    if($oldData->DpLaytimeBasedOn != $newData->DpLaytimeBasedOn) {
        $Section='Disport';
        if($oldData->DpLaytimeBasedOn=='1') {
            $oldDpLaytimeBasedOn='ATS';
        }else if($oldData->DpLaytimeBasedOn=='2') {
            $oldDpLaytimeBasedOn='WTS ';
        } 
        if($newData->DpLaytimeBasedOn=='1') {
            $newDpLaytimeBasedOn='ATS';
        }else if($newData->DpLaytimeBasedOn=='2') {
            $newDpLaytimeBasedOn='WTS ';
        } 
            $message .='<br>Old Discharging laytime Based on: '.$oldDpLaytimeBasedOn.' New Discharging laytime Based on: '.$newDpLaytimeBasedOn;
    }
    if($oldData->DpCharterType != $newData->DpCharterType) {
        $Section='Disport';
        if($oldData->DpCharterType=='1') {
            $oldDpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($oldData->DpCharterType=='2') {
            $oldDpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        }else if($oldData->DpCharterType=='3') {
            $oldDpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        }else if($oldData->DpCharterType=='4') {
            $oldDpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
        if($newData->DpCharterType=='1') {
                $newDpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($newData->DpCharterType=='2') {
                $newDpCharterType='1 Safe Port 2 Safe Berth (1SP2SB) ';
        }else if($newData->DpCharterType=='3') {
            $newDpCharterType='2 Safe Port 1 Safe Berth (2SP1SB) ';
        }else if($newData->DpCharterType=='4') {
            $newDpCharterType='2 Safe Port 2 Safe Berth (2SP2SB) ';
        } 
            $message .='<br>Old Discharging Charter Type: '.$oldDpCharterType.' New Discharging Charter Type: '.$newDpCharterType;
    }
        
    if($oldData->DpNorTendering != $newData->DpNorTendering) {
        $Section='Disport';
        if($oldData->DpNorTendering=='1') {
            $oldDpNorTendering='ATDNSHINC';
        }else if($oldData->DpNorTendering=='2') {
            $oldDpNorTendering='ATDNFHINC';
        }else if($oldData->DpNorTendering=='3') {
            $oldDpNorTendering='OFFICE HOURS';
        }else if($oldData->DpNorTendering=='4') {
            $oldDpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
        if($newData->LpNorTendering=='1') {
                $newDpNorTendering='ATDNSHINC';
        }else if($newData->LpNorTendering=='2') {
                $newDpNorTendering='ATDNFHINC';
        }else if($newData->LpNorTendering=='3') {
            $newDpNorTendering='OFFICE HOURS';
        }else if($newData->LpNorTendering=='4') {
            $newDpNorTendering='ATDNSHINC WIPON WIBON WIFPOC WCCCON';
        } 
            $message .='<br>Old Discharging NorTendering : '.$oldLpNorTendering.' New Discharging NorTendering : '.$newLpNorTendering;
    }
        
    if($oldData->DpStevedoringTerms != $newData->DpStevedoringTerms) {
        $Section='Disport';
        $message .='<br>Old Discharging Stevedoring terms : '.$oldData->stvCode.' New Discharging Stevedoring terms : '.$newData->stvCode;
    }
        
    if($oldData->DpExceptedPeriodFlg != $newData->DpExceptedPeriodFlg) {
        $Section='Disport';
        if($oldData->DpExceptedPeriodFlg=='1') {
            $oldDpExceptedPeriodFlg='Yes';
        }else if($oldData->DpExceptedPeriodFlg=='2') {
            $oldDpExceptedPeriodFlg='No';
        }
        if($newData->DpExceptedPeriodFlg=='1') {
            $newDpExceptedPeriodFlg='Yes';
        }else if($newData->DpExceptedPeriodFlg=='2') {
            $newDpExceptedPeriodFlg='No';
        }
            $message .='<br>Old Excepted periods for events: '.$oldDpExceptedPeriodFlg.' New Excepted periods for events: '.$newDpExceptedPeriodFlg;
    }
    if($oldData->DpNORTenderingPreConditionFlg != $newData->DpNORTenderingPreConditionFlg) {
        $Section='Disport';
        if($oldData->DpNORTenderingPreConditionFlg=='1') {
            $oldDpNORTenderingPreConditionFlg='Yes';
        }else if($oldData->DpNORTenderingPreConditionFlg=='2') {
            $oldDpNORTenderingPreConditionFlg='No';
        }
        if($newData->DpNORTenderingPreConditionFlg=='1') {
            $newDpNORTenderingPreConditionFlg='Yes';
        }else if($newData->DpNORTenderingPreConditionFlg=='2') {
            $newDpNORTenderingPreConditionFlg='No';
        }
            $message .='<br>Old NOR tendering pre conditions apply: '.$oldDpNORTenderingPreConditionFlg.' New NOR tendering pre conditions apply: '.$newDpNORTenderingPreConditionFlg;
    }
    if($oldData->DpNORAcceptancePreConditionFlg != $newData->DpNORAcceptancePreConditionFlg) {
        $Section='Disport';
        if($oldData->DpNORAcceptancePreConditionFlg=='1') {
            $oldDpNORAcceptancePreConditionFlg='Yes';
        }else if($oldData->DpNORAcceptancePreConditionFlg=='2') {
            $oldDpNORAcceptancePreConditionFlg='No';
        }
        if($newData->DpNORAcceptancePreConditionFlg=='1') {
            $newDpNORAcceptancePreConditionFlg='Yes';
        }else if($newData->DpNORAcceptancePreConditionFlg=='2') {
            $newDpNORAcceptancePreConditionFlg='No';
        }
            $message .='<br>Old NOR acceptance pre conditions apply: '.$oldDpNORAcceptancePreConditionFlg.' New NOR acceptance pre conditions apply: '.$newDpNORAcceptancePreConditionFlg;
    }
    if($oldData->DpOfficeHoursFlg != $newData->DpOfficeHoursFlg) {
        $Section='Disport';
        if($oldData->DpOfficeHoursFlg=='1') {
            $oldDpOfficeHoursFlg='Yes';
        }else if($oldData->DpOfficeHoursFlg=='2') {
            $oldDpOfficeHoursFlg='No';
        }
        if($newData->DpOfficeHoursFlg=='1') {
            $newDpOfficeHoursFlg='Yes';
        }else if($newData->DpOfficeHoursFlg=='2') {
            $newDpOfficeHoursFlg='No';
        }
            $message .='<br>Old Office hours apply: '.$oldDpOfficeHoursFlg.' New Office hours apply: '.$newDpOfficeHoursFlg;
    }
    if($oldData->DpLaytimeCommencementFlg != $newData->DpLaytimeCommencementFlg) {
        $Section='Disport';
        if($oldData->DpLaytimeCommencementFlg=='1') {
            $oldDpLaytimeCommencementFlg='Yes';
        }else if($oldData->DpLaytimeCommencementFlg=='2') {
            $oldDpLaytimeCommencementFlg='No';
        }
        if($newData->DpLaytimeCommencementFlg=='1') {
            $newDpLaytimeCommencementFlg='Yes';
        }else if($newData->DpLaytimeCommencementFlg=='2') {
            $newDpLaytimeCommencementFlg='No';
        }
            $message .='<br>Old laytime commencement: '.$oldDpLaytimeCommencementFlg.' New laytime commencement: '.$newDpLaytimeCommencementFlg;
    }
        
    if($Section == 'Disport') {
        if($msgData) {
            $DisPortdata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Cargo & Ports',
            'subSection'=>$Section,
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $DisPortdata); 
            $msg_data=array(
            'MessageFlag'=>'1',
            'MsgDate'=>date('Y-m-d H:i:s')
            );
                                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', $msg_data);
        }
    }
}
    
public function getQuoteDetails()
{ 
    $ids=$this->input->post('ids');
    $qids=explode("_", $ids);
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AUM_Differentials.LineNum,udt_AUM_Differentials.DifferentialVesselSizeGroup,udt_AUM_Vessel_Master.VesselSize,baseport.Description as baseDescription,refport.Description as refDescription');
    $this->db->from('udt_AUM_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AUM_Differentials.DifferentialVesselSizeGroup');
    $this->db->join('udt_PortMaster as baseport', 'baseport.ID=udt_AUM_Differentials.DifferentialLoadport');
    $this->db->join('udt_PortMaster as refport', 'refport.ID = udt_AUM_Differentials.ReferencePort');
    $this->db->where_in('LineNum', $qids);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $data=$query->result();
    return $data;
}
    
public function saveQuoteMessage($oldData,$newData)
{
    $AuctionID=$this->input->post('AuctionID');
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    foreach($oldData as $old){
        foreach($newData as $new){
            $Section='';
            $message='';
            if($old->LineNum == $new->LineNum) {
                if($old->DifferentialVesselSizeGroup != $new->DifferentialVesselSizeGroup ) {
                    $Section='Quote';
                    $message .='<br>Old Differential Vessel Size Group : '.$old->VesselSize.' New Differential Vessel Size Group :'.$new->VesselSize;
                }
                if($old->baseDescription != $new->baseDescription ) {
                    $Section='Quote';
                    $message .='<br>Old Base (Load) Port : '.$old->baseDescription.' New Base (Load) Port  :'.$new->baseDescription;
                }
                if($old->refDescription != $new->refDescription ) {
                    $Section='Quote';
                    $message .='<br>Old Reference Port : '.$old->refDescription.' New Reference Port  :'.$new->refDescription;
                }
                    
                if($Section=='Quote') {
                    if($msgData) {
                        $quotedata=array( 
                                  'CoCode'=>'marx',
                                  'AuctionID'=>$AuctionID,
                                  'Event'=>'Edit & Update',
                                  'Page'=>'Cargo Set Up',
                                  'Section'=>'Quote',
                                  'subSection'=>'Quote',
                                  'StatusFlag'=>'1',
                                  'MessageDetail'=>$message,
                                  'MessageMasterID'=>$msgData->MessageID,
                                  'UserID'=>$UserId,
                                  'FromUserID'=>$UserId,
                                  'UserDate'=>date('Y-m-d H:i:s')
                        );
                                    
                        $this->db->insert('udt_AU_Messsage_Details', $quotedata);
                        $msg_data=array(
                        'MessageFlag'=>'1',
                        'MsgDate'=>date('Y-m-d H:i:s')
                        );
                                            
                        $this->db->where('AuctionID', $AuctionID);
                        $this->db->update('udt_AU_Auctions', $msg_data);
                    }
                                        
                }
                    
            }
        }
            
    }
}
    
public function getFreightEstimate()
{
    extract($this->input->post());
        
    $qids=explode("_", $ids);
    $this->db->select('udt_AU_Cargo.LineNum,udt_AU_Cargo.Freight_Estimate,udt_AU_Cargo.Estimate_By,udt_AU_Cargo.Estimate_mt,udt_AU_Cargo.Estimate_from,udt_AU_Cargo.Estimate_to,udt_AU_Cargo.Freight_Index,udt_AU_Cargo.Estimate_Index_By,udt_AU_Cargo.Estimate_Index_from,udt_AU_Cargo.Estimate_Index_to,udt_AU_Cargo.Estimate_Index_mt,udt_AU_Cargo.estimate_comment');
    $this->db->from('udt_AU_Cargo');
    $this->db->where_in('LineNum', $qids);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return    $query->result();
        
}
    
public function saveEstimateMessage($oldData,$newData)
{
    $AuctionID=$this->input->post('AuctionID');
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();

    foreach($oldData as $old){
        foreach($newData as $new){
            $Section='';
            $message='';
            if($old->Freight_Estimate != '' && $old->Freight_Index !='') {
                if($old->LineNum == $new->LineNum) {
                    
                    if($old->Freight_Estimate != $new->Freight_Estimate ) {
                        $Section='Freight Estimate';
                        $message .='<br>Old Freight Estimate : '.$old->Freight_Estimate.' New Freight Estimate : '.$new->Freight_Estimate;
                        if($old->Freight_Estimate=='no') {
                            if($new->Estimate_By=='mt') {
                                $message .='<br> New Freight Estimate mt : '.$new->Estimate_mt;
                            } else {
                                $message .='<br> New Freight Estimate Range : '.$new->Estimate_from.' to '.$new->Estimate_to;
                            }
                        }else{
                            if($old->Estimate_By=='mt') {
                                    $message .='<br> Old Freight Estimate mt : '.$old->Estimate_mt;
                            } else {
                                 $message .='<br> Old Freight Estimate Range : '.$old->Estimate_from.' to '.$old->Estimate_to;
                            }
                            
                        }
                    }else if($old->Freight_Estimate=='yes' && $new->Freight_Estimate=='yes') {
                        
                        if($old->Estimate_By !=$new->Estimate_By) {
                             $Section='Freight Estimate';
                            if($new->Estimate_By=='mt') {
                                $message .='<br> Old Freight Estimate Range : '.$old->Estimate_from.' to '.$old->Estimate_to;
                                $message .='<br> New Freight Estimate mt : '.$new->Estimate_mt;
                            } else {
                                $message .='<br> Old Freight Estimate mt : '.$old->Estimate_mt;
                                $message .='<br> New Freight Estimate Range : '.$new->Estimate_from.' to '.$new->Estimate_to;
                            }
                        
                        }else if($old->Estimate_By=='mt' && $new->Estimate_By=='mt') {
                            if($old->Estimate_mt != $new->Estimate_mt) {
                                
                                $Section='Freight Estimate';
                                $message .='<br> Old Freight Estimate mt : '.$old->Estimate_mt.' New Freight Estimate mt : '.$new->Estimate_mt;
                            }    
                        }else if($old->Estimate_By=='range' && $new->Estimate_By=='range') {
                            if($old->Estimate_from != $new->Estimate_from || $old->Estimate_to != $new->Estimate_to) {
                                $Section='Freight Estimate';
                                $message .='<br> Old Freight Estimate Range : '.$old->Estimate_from.' to '.$old->Estimate_to.' New Freight Estimate Range : '.$new->Estimate_from.' to '.$new->Estimate_to;
                            }
                        }
                    } 
                    
                    if($old->estimate_comment != $new->estimate_comment) {
                            $Section='Freight Estimate';
                            $message .='<br> Old Freight Estimate Comment : '.$old->estimate_comment.' New Freight Estimate Comment : '.$new->estimate_comment;
                    }
                    if($Section=='Freight Estimate') {
                        if($msgData) {
                            $estimatedata=array( 
                                    'CoCode'=>'marx',
                                    'AuctionID'=>$AuctionID,
                                    'Event'=>'Edit & Update',
                                    'Page'=>'Cargo Set Up',
                                    'Section'=>'Freight Estimate',
                                    'subSection'=>$Section,
                                    'StatusFlag'=>'1',
                                    'MessageDetail'=>$message,
                                    'MessageMasterID'=>$msgData->MessageID,
                                    'UserID'=>$UserId,
                                    'FromUserID'=>$UserId,
                                    'UserDate'=>date('Y-m-d H:i:s')
                                    );
                            $this->db->insert('udt_AU_Messsage_Details', $estimatedata);
                            $msg_data=array(
                            'MessageFlag'=>'1',
                            'MsgDate'=>date('Y-m-d H:i:s')
                            );
                                
                            $this->db->where('AuctionID', $AuctionID);
                            $this->db->update('udt_AU_Auctions', $msg_data);
                        }
                        
                    }
                    $message='';
                    
                    if($old->Freight_Index != $new->Freight_Index ) {
                        $Section='Freight Index';
                        $message .='<br>Old Freight Index : '.$old->Freight_Estimate.' New Freight Index : '.$new->Freight_Estimate;
                        if($old->Freight_Index=='no') {
                            if($new->Estimate_Index_By=='mt') {
                                $message .='<br> New Freight Index mt : '.$new->Estimate_Index_mt;
                            } else {
                                 $message .='<br> New Freight Index Range : '.$new->Estimate_Index_from.' to '.$new->Estimate_Index_to;
                            }
                        }else{
                            if($old->Estimate_Index_By=='mt') {
                                          $message .='<br> Old Freight Index mt : '.$old->Estimate_Index_mt;
                            } else {
                                         $message .='<br> Old Freight Index Range : '.$old->Estimate_Index_from.' to '.$old->Estimate_Index_to;
                            }
                            
                        }
                    }else if($old->Freight_Index=='yes' && $new->Freight_Index=='yes') {
                        if($old->Estimate_Index_By !=$new->Estimate_Index_By) {
                            $Section='Freight Index';
                            if($new->Estimate_Index_By=='mt') {
                                $message .='<br> Old Freight Index Range : '.$old->Estimate_Index_from.' to '.$old->Estimate_Index_to;
                                $message .='<br> New Freight Index mt : '.$new->Estimate_Index_mt;
                            } else {
                                $message .='<br> Old Freight Index mt : '.$old->Estimate_Index_mt;
                                $message .='<br> New Freight Index Range : '.$new->Estimate_Index_from.' to '.$new->Estimate_Index_to;
                            }
                        
                        }else if($old->Estimate_Index_By=='mt' && $new->Estimate_Index_By=='mt') {
                            if($old->Estimate_Index_mt != $new->Estimate_Index_mt) {
                                $Section='Freight Index';
                                $message .='<br> Old Freight Index mt : '.$old->Estimate_Index_mt.' New Freight Index mt : '.$new->Estimate_Index_mt;
                            }    
                        }else if($old->Estimate_Index_By=='range' && $new->Estimate_Index_By=='range') {
                            if($old->Estimate_Index_from != $new->Estimate_Index_from || $old->Estimate_Index_to != $new->Estimate_Index_to) {
                                $Section='Freight Index';
                                $message .='<br><br> Old Freight Index Range : '.$old->Estimate_Index_from.' to '.$old->Estimate_Index_to.' New Freight Index Range : '.$new->Estimate_Index_from.' to '.$new->Estimate_Index_to;
                            }
                        }
                    } 
                    
                    if($Section=='Freight Index') {
                        if($msgData) {
                            $indexdata=array( 
                                        'CoCode'=>'marx',
                                        'AuctionID'=>$AuctionID,
                                        'Event'=>'Edit & Update',
                                        'Page'=>'Cargo Set Up',
                                        'Section'=>'Freight Estimate',
                                        'subSection'=>$Section,
                                        'StatusFlag'=>'1',
                                        'MessageDetail'=>$message,
                                        'MessageMasterID'=>$msgData->MessageID,
                                        'UserID'=>$UserId,
                                        'FromUserID'=>$UserId,
                                        'UserDate'=>date('Y-m-d H:i:s')
                                    );
                                        
                            $this->db->insert('udt_AU_Messsage_Details', $indexdata);
                            
                            $msg_data=array(
                                'MessageFlag'=>'1',
                                'MsgDate'=>date('Y-m-d H:i:s')
                                );
                                    
                            $this->db->where('AuctionID', $AuctionID);
                            $this->db->update('udt_AU_Auctions', $msg_data);
                        }
                                        
                    }
                }
            }
        }
            
    }
}
    
    
public function saveAuctionDeleteMessage($AuctionID)
{
    $UserId=$this->input->post('UserID');
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'delete');
    $query=$this->db->get();
    $msgData=$query->row();
    $Section='Cargo Set Up';
    if($msgData) {
        $auctiondata=array( 
        'CoCode'=>'MARX',
        'AuctionID'=>$AuctionID,
        'Event'=>'Delete',
        'Page'=>'Cargo Set Up',
        'Section'=>'Cargo Set Up',
        'subSection'=>$Section,
        'StatusFlag'=>'1',
        'MessageDetail'=>$message,
        'MessageMasterID'=>$msgData->MessageID,
        'UserID'=>$UserId,
        'FromUserID'=>$UserId,
        'UserDate'=>date('Y-m-d H:i:s')
        );    
        $this->db->insert('udt_AU_Messsage_Details', $auctiondata);
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
}
    
public function saveAuctionCloneMessage($oldauctionId,$newauctionId)
{
    $AuctionID=$oldauctionId;
    $UserId=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'clone');
    $query=$this->db->get();
    $msgData=$query->row();
    $Section='Cargo Set Up';
    $message='<br>Clone created for Master ID : '.$AuctionID.'. New clone Master ID : '.$newauctionId;
    if($msgData) {
        $auctiondata=array( 
        'CoCode'=>'MARX',
        'AuctionID'=>$AuctionID,
        'Event'=>'Clone',
        'Page'=>'Cargo Set Up',
        'Section'=>'Cargo Set Up',
        'subSection'=>$Section,
        'StatusFlag'=>'1',
        'MessageDetail'=>$message,
        'MessageMasterID'=>$msgData->MessageID,
        'UserID'=>$UserId,
        'FromUserID'=>$UserId,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_Messsage_Details', $auctiondata);
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        
    }    
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserId);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'add');
    $query=$this->db->get();
    $data=$query->row();
    $msgDetais='';
    if($data) {
        $msgdata=array( 
        'CoCode'=>'MARX',
        'AuctionID'=>$newauctionId,
        'Event'=>'Add',
        'Page'=>'Cargo Set Up',
        'Section'=>'',
        'subSection'=>'',
        'StatusFlag'=>'1',
        'MessageDetail'=>$msgDetais,
        'MessageMasterID'=>$data->MessageID,
        'UserID'=>$UserId,
        'FromUserID'=>$UserId,
        'UserDate'=>date('Y-m-d H:i:s')
        );
                    
        $this->db->insert('udt_AU_Messsage_Details', $msgdata);
            
        $this->db->where('AuctionID', $newauctionId);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
}
    
    
public function getMessageAuctionData($AuctionID)
{
    $MessageStatus=$this->input->get('MessageStatus');
    $dateFrom=$this->input->get('dateFrom');
    $dateTo=$this->input->get('dateTo');
    //$EntityID=$this->input->get('EID');
    $this->db->select("udt_AU_Auctions.*,udt_EntityMaster.EntityName");
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID = udt_AU_Auctions.OwnerEntityID', 'left');
    $this->db->where_in('udt_AU_Auctions.AuctionID', $AuctionID);
         
    if($MessageStatus=='1') {
        $this->db->where('udt_AU_Auctions.MessageFlag', $MessageStatus);
    }
        
    if($dateFrom) {
        $this->db->where('udt_AU_Auctions.MsgDate >=', date('Y-m-d', strtotime($dateFrom)));
    } 
        
    if($dateTo) {
        $this->db->where('udt_AU_Auctions.MsgDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    }  
        
    /* if($EntityID){
    $this->db->where('udt_AU_Auctions.OwnerEntityID',$EntityID);
    } 
    */
    $this->db->order_by('udt_AU_Auctions.MessageFlag', 'DESC');
    $this->db->order_by('udt_AU_Auctions.MsgDate', 'DESC');
        
    $query    =    $this->db->get();
    return $query->result();
    
}
    
public function getAuctionID()
{
    $EntityID=$this->input->get('EID');
    $query1 ="select Distinct cops_admin.udt_AU_Messsage_Details.AuctionID from cops_admin.udt_AU_Messsage_Details 
		join cops_admin.udt_UserMaster on cops_admin.udt_UserMaster.ID = cops_admin.udt_AU_Messsage_Details.UserID ";
    if($EntityID) {
        $query1 .=" where cops_admin.udt_UserMaster.EntityID='$EntityID'";
    }
    $query    =    $this->db->query($query1);
        
    return $query->result();
    
}
    
public function getMessageAuctionDetailsData()
{
    $AuctionID        =    $this->input->get('id');
    $MessageStatus        =    $this->input->get('MessageStatus');
    $dateFrom        =    $this->input->get('dateFrom');
    $dateTo        =    $this->input->get('dateTo');
    $Events        =    $this->input->get('Events'); 
    $EID        =    $this->input->get('EID');
    $MessageType=$this->input->get('MessageType');    
        
    $this->db->select("udt_AU_Messsage_Details.*,udt_AUM_MESSAGE_MASTER.Message,u1.EntityID as ToEntityID,u2.EntityID as FromEntityID");
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster as u1', 'u1.ID = udt_AU_Messsage_Details.UserID', 'left');
    $this->db->join('udt_UserMaster as u2', 'u2.ID = udt_AU_Messsage_Details.FromUserID', 'left');
    $this->db->join('udt_AUM_MESSAGE_MASTER', 'udt_AUM_MESSAGE_MASTER.MessageID = udt_AU_Messsage_Details.MessageMasterID', 'left');
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
        
    if($MessageStatus=='1') {
        $this->db->where('udt_AU_Messsage_Details.StatusFlag', $MessageStatus);
    }else if($MessageStatus=='0') {
        $this->db->where('udt_AU_Messsage_Details.StatusFlag', $MessageStatus);
    }
        
    if($Events) {
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', $Events);
    }
        
    if($MessageType) {
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', $MessageType);
    } 
        
    if($dateFrom) {
        $this->db->where('udt_AU_Messsage_Details.UserDate >=', date('Y-m-d', strtotime($dateFrom)));
    } 
        
    if($dateTo) {
        $this->db->where('udt_AU_Messsage_Details.UserDate <=', date('Y-m-d', strtotime("$dateTo +1 day")));
    } 
        
    if($EID) {
        $this->db->where('u1.EntityID', $EID);
    } 
        
    $this->db->order_by('udt_AU_Messsage_Details.StatusFlag', 'DESC');
    $this->db->order_by('udt_AU_Messsage_Details.UserDate', 'DESC');
    $query    =    $this->db->get();
        
    return $query->result();
    
}
    
public function getCommenceDate()
{
    $AuctionId=$this->input->post('AuctionId');
        
    $this->db->select('CommenceDate');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('AuctionID', $AuctionId);
    $query1=$this->db->get();
    return $query1->row();
}
    
public function get_EntityID_By_AuctionID()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row()->OwnerEntityID;
        
}
    
public function saveBrokerageCargo()
{
    extract($this->input->post());
    $userDate=date('Y-m-d H:i:s');
        
    $this->db->where('CargoLineNum', '0');
    $this->db->where('TransactionType', 'Brokerage');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->delete('udt_AU_BAC');
        
    $data=array(
                'AuctionID'=>$AuctionID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'CargoLineNum'=>'0',
                'BACComment'=>$comment1,
                'UserID'=>$UserID,
                'UserDate'=>$userDate
    );
        
    $ret=$this->db->insert('udt_AU_BAC', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BAC');
        $this->db->where('UserDate', $userDate);
        $this->db->where('AuctionID', $AuctionID);
        $query=$this->db->get();
        $bacrow= $query->row();
            
        $bacdata=array(
        'BAC_ID'=>$bacrow->BAC_ID,    
        'AuctionID'=>$bacrow->AuctionID,    
        'TransactionType'=>$bacrow->TransactionType,
        'PayingEntityType'=>$bacrow->PayingEntityType,
        'PayingEntityName'=>$bacrow->PayingEntityName,
        'ReceivingEntityType'=>    $bacrow->ReceivingEntityType,
        'ReceivingEntityName'=>    $bacrow->ReceivingEntityName,
        'BrokerName'=>$bacrow->BrokerName,
        'PayableAs'=>$bacrow->PayableAs,
        'PercentageOnFreight'=>$bacrow->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$bacrow->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$bacrow->PercentageOnDemmurage,
        'PercentageOnOverage'=>$bacrow->PercentageOnOverage,
        'LumpsumPayable'=>$bacrow->LumpsumPayable,
        'RatePerTonnePayable'=>$bacrow->RatePerTonnePayable,
        'BACComment'=>$bacrow->BACComment,
        'CargoLineNum'=>$bacrow->CargoLineNum,
        'RowStatus'=>'1',
        'UserID'=>$bacrow->UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_BAC_H', $bacdata);
            
        return $bacrow;
    } else {
        return 0;
    }
}
    
public function updateBrokerageCargo()
{
    extract($this->input->post());
        
    $data=array(
                'AuctionID'=>$AuctionID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'BACComment'=>$comment1,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('BAC_ID', $broker_id);
    $ret=$this->db->update('udt_AU_BAC', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BAC');
        $this->db->where('BAC_ID', $broker_id);
        $query=$this->db->get();
        $bacrow=$query->row();
            
        $bacdata=array(
        'BAC_ID'=>$bacrow->BAC_ID,    
        'AuctionID'=>$bacrow->AuctionID,    
        'TransactionType'=>$bacrow->TransactionType,
        'PayingEntityType'=>$bacrow->PayingEntityType,
        'PayingEntityName'=>$bacrow->PayingEntityName,
        'ReceivingEntityType'=>    $bacrow->ReceivingEntityType,
        'ReceivingEntityName'=>    $bacrow->ReceivingEntityName,
        'BrokerName'=>$bacrow->BrokerName,
        'PayableAs'=>$bacrow->PayableAs,
        'PercentageOnFreight'=>$bacrow->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$bacrow->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$bacrow->PercentageOnDemmurage,
        'PercentageOnOverage'=>$bacrow->PercentageOnOverage,
        'LumpsumPayable'=>$bacrow->LumpsumPayable,
        'RatePerTonnePayable'=>$bacrow->RatePerTonnePayable,
        'BACComment'=>$bacrow->BACComment,
        'CargoLineNum'=>$bacrow->CargoLineNum,
        'RowStatus'=>'2',
        'UserID'=>$bacrow->UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_BAC_H', $bacdata);    
            
        return $bacrow;
            
    } else {
        return 0;
    }
}
    
public function updateOthersBAC()
{
    extract($this->input->post());
        
    $data=array(
                'AuctionID'=>$AuctionID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount,
                'RatePerTonnePayable'=>$ratepertone,
                'BACComment'=>$comment,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('BAC_ID', $broker_id);
    $ret=$this->db->update('udt_AU_BAC', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BAC');
        $this->db->where('BAC_ID', $broker_id);
        $query=$this->db->get();
        $bacrow= $query->row();
            
        $bacdata=array(
        'BAC_ID'=>$bacrow->BAC_ID,    
        'AuctionID'=>$bacrow->AuctionID,    
        'TransactionType'=>$bacrow->TransactionType,
        'PayingEntityType'=>$bacrow->PayingEntityType,
        'PayingEntityName'=>$bacrow->PayingEntityName,
        'ReceivingEntityType'=>    $bacrow->ReceivingEntityType,
        'ReceivingEntityName'=>    $bacrow->ReceivingEntityName,
        'BrokerName'=>$bacrow->BrokerName,
        'PayableAs'=>$bacrow->PayableAs,
        'PercentageOnFreight'=>$bacrow->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$bacrow->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$bacrow->PercentageOnDemmurage,
        'PercentageOnOverage'=>$bacrow->PercentageOnOverage,
        'LumpsumPayable'=>$bacrow->LumpsumPayable,
        'RatePerTonnePayable'=>$bacrow->RatePerTonnePayable,
        'BACComment'=>$bacrow->BACComment,
        'CargoLineNum'=>$bacrow->CargoLineNum,
        'RowStatus'=>'2',
        'UserID'=>$bacrow->UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_BAC_H', $bacdata);    
            
        return $bacrow;
            
    } else {
        return 0;
    }
}
    
public function saveAddCommCargo()
{
    extract($this->input->post());
    $userDate=date('Y-m-d H:i:s');
        
    $this->db->where('CargoLineNum', '0');
    $this->db->where('TransactionType', 'Commision');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->delete('udt_AU_BAC');
        
    $data=array(
                'AuctionID'=>$AuctionID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'CargoLineNum'=>'0',
                'BACComment'=>$comment1,
                'UserID'=>$UserID,
                'UserDate'=>$userDate
    );
        
    $ret=$this->db->insert('udt_AU_BAC', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BAC');
        $this->db->where('UserDate', $userDate);
        $this->db->where('AuctionID', $AuctionID);
        $query=$this->db->get();
        $bacrow= $query->row();
            
        $bacdata=array(
        'BAC_ID'=>$bacrow->BAC_ID,    
        'AuctionID'=>$bacrow->AuctionID,    
        'TransactionType'=>$bacrow->TransactionType,
        'PayingEntityType'=>$bacrow->PayingEntityType,
        'PayingEntityName'=>$bacrow->PayingEntityName,
        'ReceivingEntityType'=>    $bacrow->ReceivingEntityType,
        'ReceivingEntityName'=>    $bacrow->ReceivingEntityName,
        'BrokerName'=>$bacrow->BrokerName,
        'PayableAs'=>$bacrow->PayableAs,
        'PercentageOnFreight'=>$bacrow->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$bacrow->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$bacrow->PercentageOnDemmurage,
        'PercentageOnOverage'=>$bacrow->PercentageOnOverage,
        'LumpsumPayable'=>$bacrow->LumpsumPayable,
        'RatePerTonnePayable'=>$bacrow->RatePerTonnePayable,
        'BACComment'=>$bacrow->BACComment,
        'CargoLineNum'=>$bacrow->CargoLineNum,
        'RowStatus'=>'1',
        'UserID'=>$bacrow->UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_BAC_H', $bacdata);    
        return $bacrow;
    } else {
        return 0;
    }
}
    
public function saveOthersBAC()
{
    extract($this->input->post());
    $userDate=date('Y-m-d H:i:s');
    if($temp_id=='1') {
        $this->db->where('CargoLineNum', '0');
        $this->db->where('TransactionType', 'Others');
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_BAC');
    }
        
    $data=array(
                'AuctionID'=>$AuctionID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'CargoLineNum'=>'0',
                'BACComment'=>$comment1,
                'UserID'=>$UserID,
                'UserDate'=>$userDate
    );
        
    $ret=$this->db->insert('udt_AU_BAC', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BAC');
        $this->db->where('TransactionType', 'Others');
        $this->db->where('UserID', $UserID);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->order_by('BAC_ID', 'desc');
        $query=$this->db->get();
        $bacrow=$query->row();
        $bacdata=array(
        'BAC_ID'=>$bacrow->BAC_ID,    
        'AuctionID'=>$bacrow->AuctionID,    
        'TransactionType'=>$bacrow->TransactionType,
        'PayingEntityType'=>$bacrow->PayingEntityType,
        'PayingEntityName'=>$bacrow->PayingEntityName,
        'ReceivingEntityType'=>    $bacrow->ReceivingEntityType,
        'ReceivingEntityName'=>    $bacrow->ReceivingEntityName,
        'BrokerName'=>$bacrow->BrokerName,
        'PayableAs'=>$bacrow->PayableAs,
        'PercentageOnFreight'=>$bacrow->PercentageOnFreight,
        'PercentageOnDeadFreight'=>$bacrow->PercentageOnDeadFreight,
        'PercentageOnDemmurage'=>$bacrow->PercentageOnDemmurage,
        'PercentageOnOverage'=>$bacrow->PercentageOnOverage,
        'LumpsumPayable'=>$bacrow->LumpsumPayable,
        'RatePerTonnePayable'=>$bacrow->RatePerTonnePayable,
        'BACComment'=>$bacrow->BACComment,
        'CargoLineNum'=>$bacrow->CargoLineNum,
        'RowStatus'=>'1',
        'UserID'=>$bacrow->UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
                    
        $this->db->insert('udt_AU_BAC_H', $bacdata);    
        return $bacrow;
    } else {
        return 0;
    }
}
    
public function deleteBrokerageCargo()
{
    $BAC_ID=$this->input->post('BAC_ID');
    $this->db->where('BAC_ID', $BAC_ID);
    return $this->db->delete('udt_AU_BAC');
}
    
    
public function editBrokerageCargo()
{
    $BAC_ID=$this->input->post('BAC_ID');
    $this->db->select('*');
    $this->db->from('udt_AU_BAC');
    $this->db->where('BAC_ID', $BAC_ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function saveResponseBrokerageCargo()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('SeqNo', 'desc');
    $query=$this->db->get();
    $SeqNoRow=$query->row();
        
    if($SeqNoRow) {
        $SeqNo=$SeqNoRow->SeqNo+1;
    } else {
        $SeqNo=1;
    }
        
        
    $data=array(
                'BACResponse_ID'=>0,
                'SeqNo'=>$SeqNo,
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'ResponseCargoID'=>$ResponseCargoID,
                'BACComment'=>$comment1,
                'RowStatus'=>1,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_BACResponse_H', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BACResponse_H');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('TransactionType', $transtype);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('BACResponse_HID', 'desc');
        $query=$this->db->get();
        return $query->row();
    } else {
        return 0;
    }
}
    
public function updateResponseBrokerageCargo()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->where('TransactionType', $transtype);
    $this->db->order_by('SeqNo', 'desc');
    $query=$this->db->get();
    $SeqNoRow=$query->row();
        
    $data=array(
                'BACResponse_ID'=>$BACResponse_ID,
                'SeqNo'=>$SeqNoRow->SeqNo,
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'ResponseCargoID'=>$ResponseCargoID,
                'BACComment'=>$comment1,
                'RowStatus'=>2,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_BACResponse_H', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BACResponse_H');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('TransactionType', $transtype);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('BACResponse_HID', 'desc');
        $query=$this->db->get();
        return $query->row();
    } else {
        return 0;
    }
}
    
public function saveResponseAddCommCargo()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('SeqNo', 'desc');
    $query=$this->db->get();
    $SeqNoRow=$query->row();
        
    if($SeqNoRow) {
        $SeqNo=$SeqNoRow->SeqNo+1;
    } else {
        $SeqNo=1;
    }
        
    $data=array(
                'BACResponse_ID'=>0,
                'SeqNo'=>$SeqNo,
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'ResponseCargoID'=>$ResponseCargoID,
                'BACComment'=>$comment1,
                'RowStatus'=>1,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_BACResponse_H', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BACResponse_H');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('TransactionType', $transtype);
        $this->db->where('UserID', $UserID);
        $this->db->order_by('BACResponse_HID', 'desc');
        $query=$this->db->get();
        return $query->row();
    } else {
        return 0;
    }
}
    
public function updateResponseOthersBAC()
{
    extract($this->input->post());
        
    $data=array(
                'BACResponse_ID'=>$BACResponse_ID,
                'SeqNo'=>$SeqNo,
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'ResponseCargoID'=>$ResponseCargoID,
                'BACComment'=>$comment1,
                'RowStatus'=>2,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_BACResponse_H', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BACResponse_H');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('TransactionType', $transtype);
        $this->db->where('ConfirmFlg !=  0');
        $this->db->order_by('SeqNo', 'asc');
        $this->db->order_by('BACResponse_HID', 'desc');
        $query=$this->db->get();
        $bac_rsult=$query->result();
            
        $bac_other=array();
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
                $query=$this->db->get();
                $bacRow1=$query->row();
                array_push($bac_other, $bacRow1);
            }
        }
            
        return $bac_other;
    } else {
        return 0;
    }
}
    
public function saveResponseOthersBAC()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ResponseCargoID', $ResponseCargoID);
    $this->db->order_by('SeqNo', 'desc');
    $query=$this->db->get();
    $SeqNoRow=$query->row();
        
    if($SeqNoRow) {
        $SeqNo=$SeqNoRow->SeqNo+1;
    } else {
        $SeqNo=1;
    }
        
    $data=array(
                'BACResponse_ID'=>0,
                'SeqNo'=>$SeqNo,
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'TransactionType'=>$transtype,
                'PayingEntityType'=>$payingentitytype,
                'PayingEntityName'=>$payingentity,
                'ReceivingEntityType'=>$receivingentitytype,
                'ReceivingEntityName'=>$receivingentity,
                'BrokerName'=>$brokername,
                'PayableAs'=>$brokeragepayable,
                'PercentageOnFreight'=>$percentamntfreight,
                'PercentageOnDeadFreight'=>$percentamntdeadfreight,
                'PercentageOnDemmurage'=>$percentamntdemurrage,
                'PercentageOnOverage'=>$percentamntoverage,
                'LumpsumPayable'=>$lumpsumamount1,
                'RatePerTonnePayable'=>$ratepertone,
                'ResponseCargoID'=>$ResponseCargoID,
                'BACComment'=>$comment1,
                'RowStatus'=>1,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_BACResponse_H', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_BACResponse_H');
        $this->db->where('ResponseID', $ResponseID);
        $this->db->where('ResponseCargoID', $ResponseCargoID);
        $this->db->where('TransactionType', $transtype);
        $this->db->where('ConfirmFlg !=  0');
        $this->db->order_by('SeqNo', 'asc');
        $this->db->order_by('BACResponse_HID', 'desc');
        $query=$this->db->get();
        $bac_rsult=$query->result();
            
        $bac_other=array();
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
                $query=$this->db->get();
                $bacRow1=$query->row();
                array_push($bac_other, $bacRow1);
            }
        }
        return $bac_other;
    } else {
        return 0;
    }
}
    
public function deleteResponseBrokerageCargo()
{
    $BACResponse_HID=$this->input->post('BACResponse_HID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('BACResponse_HID', $BACResponse_HID);
    $query=$this->db->get();
    $bac_row=$query->row();
        
    $data=array(
                'BACResponse_ID'=>$bac_row->BACResponse_ID,
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
                'ResponseCargoID'=>$bac_row->ResponseCargoID,
                'BACComment'=>$bac_row->BACComment,
                'RowStatus'=>3,
                'ConfirmFlg'=>2,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('udt_AU_BACResponse_H', $data);
        
}

public function editResponseBrokerageCargo_H()
{
    $BACResponse_HID=$this->input->post('BACResponse_HID');
    $this->db->select('*');
    $this->db->from('udt_AU_BACResponse_H');
    $this->db->where('BACResponse_HID', $BACResponse_HID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCargoDetailsByAuctionID()
{
    $AuctionID=$this->input->post('AuctionID');
    $EID=$this->input->post('EID');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AU_Cargo.AuctionID, CONVERT(VARCHAR(10),udt_AU_Cargo.LpLaycanStartDate,105) as LpLaycanStartDate, udt_AU_Cargo.LpLaycanEndDate,udt_CargoMaster.Code,udt_PortMaster.PortName, sdt1.Code as stvCode');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID = udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID = udt_AU_Cargo.LoadPort', 'left');
    $this->db->join('udt_CP_SteveDoringTerms as sdt1', 'sdt1.ID=udt_AU_Cargo.LpStevedoringTerms', 'left');
    $this->db->where('udt_AU_Cargo.AuctionID', $AuctionID);
    $this->db->order_by('linenum', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getAllCargoRowsByAuctionID($AuctionID)
{
    $this->db->select('udt_AU_Cargo.AuctionID, udt_AU_Cargo.LpLaycanStartDate, udt_AU_Cargo.LpLaycanEndDate, udt_CargoMaster.Code, udt_PortMaster.PortName');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_CargoMaster', 'udt_CargoMaster.ID = udt_AU_Cargo.SelectFrom', 'left');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID = udt_AU_Cargo.LoadPort', 'left');
    $this->db->where('udt_AU_Cargo.AuctionID', $AuctionID);
    $this->db->order_by('linenum', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function deleteDifferentialById()
{
    $DifID=$this->input->post('id');
    $this->db->where('DifID', $DifID);
    return $this->db->delete('udt_AUM_Differentials');
}
    
public function allocateUserInBusinessProcess()
{
    $bp_id=$this->input->post('bpid');
    $chkId=$this->input->post('chkId');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bp_id);
    $query=$this->db->get();
    $bpRow=$query->row();
        
    $tchkid=trim($chkId, ",");
    $data=array('UserList'=>$tchkid);
    $this->db->where('BPAID', $bp_id);
    $this->db->update('udt_AU_BusinessProcessAuctionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $bpRow->AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bp_id);
    $query=$this->db->get();
    $bpNewRow=$query->row();
        
    if($bpRow->UserList != $bpNewRow->UserList) {
        if($msgData) {
            $userListArr=explode(",", $bpRow->UserList);
            $oldUserList='';
            for($i=0; $i<count($userListArr); $i++){
                $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName');
                $this->db->from('udt_UserMaster');
                $this->db->where('ID', $userListArr[$i]);
                $query1=$this->db->get();
                $usrRow=$query1->row();
                $oldUserList .=$usrRow->FirstName.' '.$usrRow->LastName.',';
            }
            $oldUserList=trim($oldUserList, ",");
                
            $userNewListArr=explode(",", $bpNewRow->UserList);
            $newUserList='';
            for($i=0; $i<count($userNewListArr); $i++){
                $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName');
                $this->db->from('udt_UserMaster');
                $this->db->where('ID', $userNewListArr[$i]);
                $query1=$this->db->get();
                $usrRow=$query1->row();
                $newUserList .=$usrRow->FirstName.' '.$usrRow->LastName.',';
            }
            $newUserList=trim($newUserList, ",");
                
            $message .='<br>Old User List : '.$oldUserList.' New User List : '.$newUserList;
            $busdata=array(
            'CoCode'=>'Marx',
            'AuctionID'=>$bpRow->AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Charter Details',
            'subSection'=>'Business Process',
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $busdata); 

            $this->db->where('AuctionID', $bpRow->AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    }
        
}
    
public function changeBusinessProcessStatus()
{
    $status=$this->input->post('status');
    $bpid=$this->input->post('bpid');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpRow=$query->row();
        
    $data=array('Status'=>$status);
    $this->db->where('BPAID', $bpid);
    $ret=$this->db->update('udt_AU_BusinessProcessAuctionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $bpRow->AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpNewRow=$query->row();
        
    if($msgData) {
        $oldStatus='Inactive';
        if($bpRow->Status==1) {
            $oldStatus='Active';
        }
        $newStatus='Inactive';
        if($bpNewRow->Status==1) {
            $newStatus='Active';
        }
        $message .='<br>Old Status : '.$oldStatus.' New Status : '.$newStatus;
        if($bpRow->BussinessType==1) {
            $Section='Charter Details';
            $SubSection='Business Process';
        } else if($bpRow->BussinessType==2) {
            $Section='Select Invitees';
            $SubSection='Invitee Business Process';
        }
            $busdata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$bpRow->AuctionID,
                'Event'=>'Edit & Update',
                'Page'=>'Cargo Set Up',
                'Section'=>$Section,
                'subSection'=>$SubSection,
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$msgData->MessageID,
                'UserID'=>$UserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $busdata); 

            $this->db->where('AuctionID', $bpRow->AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $ret;
}
    
public function getBPByAuctionID()
{
    $EntityID=$this->input->get('EntityID');
    $AuctionID=$this->input->get('AuctionID');
    $UserID=$this->input->get('UserID');
        
    $complete_by=array('1','3','4','5','6');
    $this->db->select('*');
    $this->db->from('udt_AUM_BusinessProcess');
    $this->db->where('RecordOwner', $EntityID);
    $this->db->where_in('finalization_completed_by', $complete_by);
    $this->db->where('status', 1);
    $this->db->order_by('process_flow_sequence', 'asc');
    $query=$this->db->get();
    $rslt=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('BussinessType', 1);
    $query=$this->db->get();
    $cnddata=$query->result();
        
    foreach($rslt as $row) {
        $sts=1;
        foreach($cnddata as $rd) {
            if ($rd->BPID==$row->BPID) { 
                $sts=0;
            } 
        }
        if($sts==1) {
            $data=array(
            'BPID'=>$row->BPID,
            'AuctionID'=>$AuctionID,
            'UserList'=>'',
            'Status'=>1,
            'UserID'=>$UserID,
            'BussinessType'=>1,
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_BusinessProcessAuctionWise', $data);
        }
    }
    $this->db->select('udt_AUM_BusinessProcess.finalization_completed_by,udt_AUM_BusinessProcess.name_of_process,udt_AUM_BusinessProcess.process_flow_sequence,udt_AU_BusinessProcessAuctionWise.UserList,udt_AU_BusinessProcessAuctionWise.Status,udt_AUM_BusinessProcess.BPID,udt_AU_BusinessProcessAuctionWise.BPAID,udt_AU_BusinessProcessAuctionWise.on_subject_status,udt_AU_BusinessProcessAuctionWise.lift_subject_status');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
    $this->db->order_by('udt_AUM_BusinessProcess.process_flow_sequence', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getInviteeBusinessProcess()
{
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*,udt_AUM_BusinessProcess.RecordOwner, udt_AUM_BusinessProcess.name_of_process,udt_AUM_BusinessProcess.process_applies, udt_AUM_BusinessProcess.process_flow_sequence,udt_AUM_BusinessProcess.finalization_completed_by,udt_UserMaster.FirstName,udt_UserMaster.LastName, udt_UserMaster.EntityID');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->join('udt_userMaster', 'udt_userMaster.ID=udt_AU_BusinessProcessAuctionWise.UserList');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('finalization_completed_by', 2);
    $this->db->where('BussinessType', 2);
    $this->db->order_by('udt_userMaster.EntityID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function updateEditableField()
{
    extract($this->input->post());
        
    $this->db->where('AuctionID', $AuctionId);
    $this->db->where('ChkLabel', $ChkLabel);
    $this->db->delete('Udt_AU_EditableFiledBox');
        
    $data=array(
    'AuctionID'=>$AuctionId,
    'UserID'=>$UserID,
    'EntityID'=>$EntityID,
    'ChkLabel'=>$ChkLabel,
    'ChkFlag'=>$ChkFlag,
    'add_date'=>date('Y-m-d H:i:s')
    );
    return $this->db->insert('Udt_AU_EditableFiledBox', $data);
}
    
public function getEditableField()
{
    $AuctionID=$this->input->post('AuctionId');
        
    $this->db->select('ChkLabel,ChkFlag');
    $this->db->from('Udt_AU_EditableFiledBox');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
}
    
public function allocateUserInBusinessProcess_h()
{
    $bp_id=$this->input->post('bpid');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bp_id);
    $query=$this->db->get();
    $rslt=$query->row();
        
    $data=array(
    'BPID'=>$rslt->BPID,
    'AuctionID'=>$rslt->AuctionID,
    'UserList'=>$rslt->UserList,
    'Status'=>$rslt->Status,
    'UserID'=>$rslt->UserID,
    'RowStatus'=>2,
    'BussinessType'=>$rslt->BussinessType,
    'UserDate'=>date('Y-m-d H:i:s'),
    );
        
    $this->db->insert('udt_AU_BusinessProcessAuctionWise_H', $data);
}
    
public function getLpDpDates()
{
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AU_Cargo.*,udt_PortMaster.PortName');
    $this->db->from('udt_AU_Cargo');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_Cargo.LoadPort', 'left');
    $this->db->where('AuctionID', $AuctionID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getDisportsDates()
{
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AU_CargoDisports.*,udt_PortMaster.PortName');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('CargoID', 'asc');
    $this->db->order_by('CD_ID', 'asc');
    $qry=$this->db->get();
    return $qry->row();
}
    
public function changeQuoteLimit()
{
    $AuctionID=$this->input->post('auctionID');
    $EntityID=$this->input->post('EntityID');
    $QuoteLimit=$this->input->post('QuoteLimit');
    $UserID=$this->input->post('UserID');
    $QuoteLimitFlag=0;
    $QuoteLimitValue=0;
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $EntityID);
    $query1=$this->db->get();
    $oldInvRow=$query1->row();
        
    if($QuoteLimit==1) {
        $QuoteLimitFlag=1;
        $QuoteLimitValue=1;
    } else if($QuoteLimit > 1) {
        $QuoteLimitFlag=2;
        $QuoteLimitValue=$QuoteLimit;
    }
        
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $EntityID);
    $ret=$this->db->update('udt_AUM_Invitees', array('QuoteLimitFlag'=>$QuoteLimitFlag,'QuoteLimitValue'=>$QuoteLimitValue));
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $querycounter=$this->db->get();
    $resultcounter=$querycounter->row()->CounterNo+1;
        
    $this->db->update('udt_AU_Counter', array('CounterNo'=>$resultcounter));
        
    foreach($result as $rows){
        $data1=array(
        'CoCode'=>$rows->CoCode,
        'AuctionID'=>$AuctionID,
        'Company'=>$rows->Company,
        'EntityID'=>$rows->EntityID,
        'UserMasterID'=>$rows->UserMasterID,
        'InvPriorityStatus'=>$rows->InvPriorityStatus,
        'InviteeRole'=>$rows->InviteeRole,
        'QuoteLimitFlag'=>$rows->QuoteLimitFlag,
        'QuoteLimitValue'=>$rows->QuoteLimitValue,
        'Since'=>'',
        'AdverseComments'=>'',
        'Comments'=>'',                                        
        'RowStatus'=>'2',
        'RowCounter'=>$resultcounter,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')            
        );
        $this->db->insert('udt_AUM_Invitees_H', $data1);        
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
        
    $Section='';
    $message='';
    if($msgData) {
        $Section='Select Invitees';
        $message .='<br>Old Quote Limit : '.$oldInvRow->QuoteLimitValue.' New Quote Limit : '.$QuoteLimitValue;
        $roledata=array(
        'CoCode'=>'Marx',
        'AuctionID'=>$AuctionID,
        'Event'=>'Edit & Update',
        'Page'=>'Cargo Set Up',
        'Section'=>'Select Invitees',
        'subSection'=>$Section,
        'StatusFlag'=>'1',
        'MessageDetail'=>$message,
        'MessageMasterID'=>$msgData->MessageID,
        'UserID'=>$UserID,
        'FromUserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
            
        $this->db->insert('udt_AU_Messsage_Details', $roledata); 

        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $ret;
}
    
public function saveCargoDisports()
{
    extract($this->input->post());
    $this->db->trans_start();
    if($DpMaxTime=='') {
        $DpMaxTime=0;
    }
    $data=array(
                'CargoID'=>$CargoID,
                'AuctionID'=>$AuctionID,
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
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
        
    $ret=$this->db->insert('udt_AU_CargoDisports', $data);
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_CargoDisports');
        $this->db->where('CargoID', $CargoID);
        $this->db->order_by('CD_ID', 'desc');
        $qry=$this->db->get();
        $dis_row=$qry->row();
            
        $datah=array(
        'CD_ID'=>$dis_row->CD_ID,
        'CargoID'=>$CargoID,
        'AuctionID'=>$AuctionID,
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
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports_H', $datah);
    }
        
    if($ret) {
        $DpExceptedPeriodEvent=explode("__", $AllExceptedPeriodData);
        $DpLaytimeCountOnDemurrage=explode("__", $AllLaytimeCountOnDemurrageData);
        $DpLaytimeCountUsedFlg=explode("__", $AllLaytimeCountUsedData);
        $DpTimeCounting=explode("__", $AllTimeCountingData);
        $DpExceptedPeriodComment=explode("__", $AllExceptedPeriodCommentData);
            
        $DpNewSelectTenderingFlg=explode("__", $AllNewSelectTenderingData);
        $DpTenderingNameOfCondition=explode("__", $AllTenderingNameOfConditionData);
        $DpTenderingActiveFlg=explode("__", $AllTenderingActiveFlgData);
        $DpNORTenderingPreConditionComment=explode("__", $AllTenderingPreConditionCommentData);
            
        $DpNewSelectAcceptanceFlg=explode("__", $AllNewSelectAcceptanceData);
        $DpAcceptanceNameOfCondition=explode("__", $AllAcceptanceNameOfConditionData);
        $DpAcceptanceActiveFlg=explode("__", $AllAcceptanceActiveFlgData);
        $DpNORAcceptancePreConditionComment=explode("__", $AllAcceptancePreConditionCommentData);
            
        $DpDayFrom=explode("__", $AllDpDayFromData);
        $DpDayTo=explode("__", $AllDpDayToData);
        $DpTimeFrom=explode("__", $AllDpTimeFromData);
        $DpTimeTo=explode("__", $AllDpTimeToData);
        $IsDpLastEntry=explode("__", $AllDpLastEntryData);
            
        $DpLayTiimeDayFrom=explode("__", $AllLayTiimeDayFromData);
        $DpLayTiimeDayTo=explode("__", $AllLayTiimeDayToData);
        $DpLaytimeTimeFrom=explode("__", $AllLaytimeTimeFromData);
        $DpLaytimeTimeTo=explode("__", $AllLaytimeTimeToData);
        $DpTurnTimeApplies=explode("__", $AllTurnTimeAppliesData);
        $DpTurnTimeExpires=explode("__", $AllTurnTimeExpiresData);
        $DpLaytimeCommencesAt=explode("__", $AllLaytimeCommencesAtData);
        $DpLaytimeCommencesAtHours=explode("__", $AllLaytimeCommencesAtHoursData);
        $DpSelectDay=explode("__", $AllSelectDayData);
        $DpTimeCountsIfOnDemurrage=explode("__", $AllTimeCountsIfOnDemurrageData);
            
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'EventID'=>$EventID,
                        'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountsOnDemurrageFlg,
                        'LaytimeCountsFlg'=>$LaytimeCountsFlg,
                        'TimeCountingFlg'=>$TimeCountingFlg,
                        'ExceptedPeriodComment'=>$ExceptedPeriodComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'TenderingPreConditionComment'=>$TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'AcceptancePreConditionComment'=>$AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$dis_row->CD_ID,
                        'DateFrom'=>$DateFrom,
                        'DateTo'=>$DateTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'IsLastEntry'=>$IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpOfficeHours', $office_data);
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$dis_row->CD_ID,
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
                    $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
            }
        }
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function getDisportRecordsDataByCargoID($CargoID)
{
    $this->db->select('udt_AU_CargoDisports.*, udt_PortMaster.PortName as DisportDescription, udt_CP_LoadingDischargeTermsMaster.Code as DischargingTermsCode ');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_CP_LoadingDischargeTermsMaster.ID=udt_AU_CargoDisports.DischargingTerms', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('CD_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoDisportsByCargoID()
{
    $CargoID=$this->input->post('CargoID');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AU_CargoDisports.*, udt_PortMaster.PortName as DisportDescription, udt_CP_LoadingDischargeTermsMaster.Code as DischargingTermsCode ');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_CP_LoadingDischargeTermsMaster.ID=udt_AU_CargoDisports.DischargingTerms', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('CD_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoDisportsById()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_CargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspCode, udt_CP_LoadingDischargeTermsMaster.Code as trmCode, udt_CP_LoadingDischargeTermsMaster.Description as trmDescription, udt_CP_SteveDoringTerms.Code as stvCode');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster', 'udt_CP_LoadingDischargeTermsMaster.ID=udt_AU_CargoDisports.DischargingTerms', 'left');
    $this->db->join('udt_CP_SteveDoringTerms', 'udt_CP_SteveDoringTerms.ID=udt_AU_CargoDisports.DpStevedoringTerms', 'left');
    $this->db->where('CD_ID', $CD_ID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function updateCargoDisports()
{
    extract($this->input->post());
    $this->db->trans_start();
    if($DpMaxTime=='') {
        $DpMaxTime=0;
    }
        
    $data=array(
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
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
            
    $this->db->where('CD_ID', $CD_ID);
    $ret=$this->db->update('udt_AU_CargoDisports', $data);
    if($ret) {
        $data=array(
        'CD_ID'=>$CD_ID,
        'CargoID'=>$CargoID,
        'AuctionID'=>$AuctionID,
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
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports_H', $data);
    }
    if($ret) {
        $DpExceptedPeriodEvent=explode("__", $AllExceptedPeriodData);
        $DpLaytimeCountOnDemurrage=explode("__", $AllLaytimeCountOnDemurrageData);
        $DpLaytimeCountUsedFlg=explode("__", $AllLaytimeCountUsedData);
        $DpTimeCounting=explode("__", $AllTimeCountingData);
        $DpExceptedPeriodComment=explode("__", $AllExceptedPeriodCommentData);
            
        $DpNewSelectTenderingFlg=explode("__", $AllNewSelectTenderingData);
        $DpTenderingNameOfCondition=explode("__", $AllTenderingNameOfConditionData);
        $DpTenderingActiveFlg=explode("__", $AllTenderingActiveFlgData);
        $DpNORTenderingPreConditionComment=explode("__", $AllTenderingPreConditionCommentData);
            
        $DpNewSelectAcceptanceFlg=explode("__", $AllNewSelectAcceptanceData);
        $DpAcceptanceNameOfCondition=explode("__", $AllAcceptanceNameOfConditionData);
        $DpAcceptanceActiveFlg=explode("__", $AllAcceptanceActiveFlgData);
        $DpNORAcceptancePreConditionComment=explode("__", $AllAcceptancePreConditionCommentData);
            
        $DpDayFrom=explode("__", $AllDpDayFromData);
        $DpDayTo=explode("__", $AllDpDayToData);
        $DpTimeFrom=explode("__", $AllDpTimeFromData);
        $DpTimeTo=explode("__", $AllDpTimeToData);
        $IsDpLastEntry=explode("__", $AllDpLastEntryData);
            
        $DpLayTiimeDayFrom=explode("__", $AllLayTiimeDayFromData);
        $DpLayTiimeDayTo=explode("__", $AllLayTiimeDayToData);
        $DpLaytimeTimeFrom=explode("__", $AllLaytimeTimeFromData);
        $DpLaytimeTimeTo=explode("__", $AllLaytimeTimeToData);
        $DpTurnTimeApplies=explode("__", $AllTurnTimeAppliesData);
        $DpTurnTimeExpires=explode("__", $AllTurnTimeExpiresData);
        $DpLaytimeCommencesAt=explode("__", $AllLaytimeCommencesAtData);
        $DpLaytimeCommencesAtHours=explode("__", $AllLaytimeCommencesAtHoursData);
        $DpSelectDay=explode("__", $AllSelectDayData);
        $DpTimeCountsIfOnDemurrage=explode("__", $AllTimeCountsIfOnDemurrageData);
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_DpExceptedPeriods');
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$CD_ID,
                        'EventID'=>$EventID,
                        'LaytimeCountsOnDemurrageFlg'=>$LaytimeCountsOnDemurrageFlg,
                        'LaytimeCountsFlg'=>$LaytimeCountsFlg,
                        'TimeCountingFlg'=>$TimeCountingFlg,
                        'ExceptedPeriodComment'=>$ExceptedPeriodComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
            }
        }
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_DpNORTenderingPreConditions');
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$CD_ID,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORTenderingPreConditionID'=>$NORTenderingPreConditionID,
                        'NewNORTenderingPreCondition'=>$NewNORTenderingPreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'TenderingPreConditionComment'=>$TenderingPreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
            }
        }
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->where('CargoID', $CargoID);
        $this->db->delete('udt_AU_DpNORAcceptancePreConditions');
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$CD_ID,
                        'CreateNewOrSelectListFlg'=>$CreateNewOrSelectListFlg,
                        'NORAcceptancePreConditionID'=>$NORAcceptancePreConditionID,
                        'NewNORAcceptancePreCondition'=>$NewNORAcceptancePreCondition,
                        'StatusFlag'=>$StatusFlag,
                        'AcceptancePreConditionComment'=>$AcceptancePreConditionComment,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
            }
        }
            
            $this->db->where('DisportID', $CD_ID);
            $this->db->where('CargoID', $CargoID);
            $this->db->delete('udt_AU_DpOfficeHours');
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$CD_ID,
                        'DateFrom'=>$DateFrom,
                        'DateTo'=>$DateTo,
                        'TimeFrom'=>$TimeFrom,
                        'TimeTo'=>$TimeTo,
                        'IsLastEntry'=>$IsLastEntry,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DpOfficeHours', $office_data);
            }
        }
            
            $this->db->where('DisportID', $CD_ID);
            $this->db->where('CargoID', $CargoID);
            $this->db->delete('udt_AU_DpLaytimeCommencement');
            
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
                        'CargoID'=>$CargoID,
                        'DisportID'=>$CD_ID,
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
                    $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
            }
        }
    }
    $this->db->trans_complete();
    return $ret;
        
}
    
public function deleteCargoDisports()
{
    $CD_ID=$this->input->post('CD_ID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->where('CD_ID', $CD_ID);
    $qry=$this->db->get();
    $dis_row=$qry->row();
        
    $this->db->where('CD_ID', $CD_ID);
    $ret=$this->db->delete('udt_AU_CargoDisports');
        
    if($ret) {
        $datah=array(
        'CD_ID'=>$dis_row->CD_ID,
        'CargoID'=>$dis_row->CargoID,
        'AuctionID'=>$dis_row->AuctionID,
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
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports_H', $datah);
            
                
        $this->db->where('DisportID', $CD_ID);
        $this->db->delete('udt_AU_DpExceptedPeriods');
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->delete('udt_AU_DpNORTenderingPreConditions');
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->delete('udt_AU_DpNORAcceptancePreConditions');
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->delete('udt_AU_DpOfficeHours');
            
        $this->db->where('DisportID', $CD_ID);
        $this->db->delete('udt_AU_DpLaytimeCommencement');
    }
    return $ret;
}
    
public function cloneCargoDisports()
{
    $CD_ID=$this->input->post('CD_ID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->where('CD_ID', $CD_ID);
    $qry=$this->db->get();
    $dis_row=$qry->row();
        
    if($dis_row->DpMaxTime=='.0000' || $dis_row->DpMaxTime==null) {
        $DpMaxTime=0;
    } else {
        $DpMaxTime=$dis_row->DpMaxTime;
    }
    $data=array(
                'CargoID'=>$dis_row->CargoID,
                'AuctionID'=>$dis_row->AuctionID,
                'DisPort'=>$dis_row->DisPort,
                'DpArrivalStartDate'=>$dis_row->DpArrivalStartDate,
                'DpArrivalEndDate'=>$dis_row->DpArrivalEndDate,
                'DpPreferDate'=>$dis_row->DpPreferDate,
                'DischargingTerms'=>$dis_row->DischargingTerms,
                'DischargingRateMT'=>$dis_row->DischargingRateMT,
                'DischargingRateUOM'=>$dis_row->DischargingRateUOM,
                'DpMaxTime'=>$DpMaxTime,
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
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('udt_AU_CargoDisports', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->where('CargoID', $dis_row->CargoID);
    $this->db->order_by('CD_ID', 'desc');
    $qry=$this->db->get();
    $new_dis_row=$qry->row();
        
    if($ret) {
        $datah=array(
        'CD_ID'=>$new_dis_row->CD_ID,
        'CargoID'=>$dis_row->CargoID,
        'AuctionID'=>$dis_row->AuctionID,
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
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_CargoDisports_H', $datah);
            
        if($dis_row->DpExceptedPeriodFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DpExceptedPeriods');
            $this->db->where('CargoID', $dis_row->CargoID);
            $this->db->where('DisportID', $CD_ID);
            $qry=$this->db->get();
            $excepted_periods=$qry->result();
                
            foreach($excepted_periods as $period){
                  $excepted_data=array(
                   'AuctionID'=>$period->AuctionID,
                   'CargoID'=>$dis_row->CargoID,
                   'DisportID'=>$new_dis_row->CD_ID,
                   'EventID'=>$period->EventID,
                   'LaytimeCountsOnDemurrageFlg'=>$period->LaytimeCountsOnDemurrageFlg,
                   'LaytimeCountsFlg'=>$period->LaytimeCountsFlg,
                   'TimeCountingFlg'=>$period->TimeCountingFlg,
                   'ExceptedPeriodComment'=>$period->ExceptedPeriodComment,
                   'UserID'=>$UserID,
                   'CreatedDate'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('udt_AU_DpExceptedPeriods', $excepted_data);
            }
        }
            
        if($dis_row->DpNORTenderingPreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DpNORTenderingPreConditions');
            $this->db->where('CargoID', $dis_row->CargoID);
            $this->db->where('DisportID', $CD_ID);
            $qry1=$this->db->get();
            $tendering_result=$qry1->result();
                
            foreach($tendering_result as $tendering){
                $tendering_data=array(
                'AuctionID'=>$tendering->AuctionID,
                'CargoID'=>$dis_row->CargoID,
                'DisportID'=>$new_dis_row->CD_ID,
                'CreateNewOrSelectListFlg'=>$tendering->CreateNewOrSelectListFlg,
                'NORTenderingPreConditionID'=>$tendering->NORTenderingPreConditionID,
                'NewNORTenderingPreCondition'=>$tendering->NewNORTenderingPreCondition,
                'StatusFlag'=>$tendering->StatusFlag,
                'TenderingPreConditionComment'=>$tendering->TenderingPreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpNORTenderingPreConditions', $tendering_data);
            }
        }
            
        if($dis_row->DpNORAcceptancePreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DpNORAcceptancePreConditions');
            $this->db->where('CargoID', $dis_row->CargoID);
            $this->db->where('DisportID', $CD_ID);
            $qry2=$this->db->get();
            $acceptance_result=$qry2->result();
                
            foreach($acceptance_result as $acceptance){
                $acceptance_data=array(
                'AuctionID'=>$acceptance->AuctionID,
                'CargoID'=>$dis_row->CargoID,
                'DisportID'=>$new_dis_row->CD_ID,
                'CreateNewOrSelectListFlg'=>$acceptance->CreateNewOrSelectListFlg,
                'NORAcceptancePreConditionID'=>$acceptance->NORAcceptancePreConditionID,
                'NewNORAcceptancePreCondition'=>$acceptance->NewNORAcceptancePreCondition,
                'StatusFlag'=>$acceptance->StatusFlag,
                'AcceptancePreConditionComment'=>$acceptance->AcceptancePreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpNORAcceptancePreConditions', $acceptance_data);
            }
        }
            
        if($dis_row->DpOfficeHoursFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DpOfficeHours');
            $this->db->where('CargoID', $dis_row->CargoID);
            $this->db->where('DisportID', $CD_ID);
            $qry3=$this->db->get();
            $office_hours=$qry3->result();
                
            foreach($office_hours as $office){
                $office_data=array(
                'AuctionID'=>$office->AuctionID,
                'CargoID'=>$dis_row->CargoID,
                'DisportID'=>$new_dis_row->CD_ID,
                'DateFrom'=>$office->DateFrom,
                'DateTo'=>$office->DateTo,
                'TimeFrom'=>$office->TimeFrom,
                'TimeTo'=>$office->TimeTo,
                'IsLastEntry'=>$office->IsLastEntry,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpOfficeHours', $office_data);
            }
        }
            
        if($dis_row->DpOfficeHoursFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DpLaytimeCommencement');
            $this->db->where('CargoID', $dis_row->CargoID);
            $this->db->where('DisportID', $CD_ID);
            $qry4=$this->db->get();
            $laytime_result=$qry4->result();
                
            foreach($laytime_result as $laytime){
                $commence_data=array(
                'AuctionID'=>$laytime->AuctionID,
                'CargoID'=>$dis_row->CargoID,
                'DisportID'=>$new_dis_row->CD_ID,
                'DayFrom'=>$laytime->DayFrom,
                'DayTo'=>$laytime->DayTo,
                'TimeFrom'=>$laytime->TimeFrom,
                'TimeTo'=>$laytime->TimeTo,
                'TurnTime'=>$laytime->TurnTime,
                'TurnTimeExpire'=>$laytime->TurnTimeExpire,
                'LaytimeCommenceAt'=>$laytime->LaytimeCommenceAt,
                'LaytimeCommenceAtHour'=>$laytime->LaytimeCommenceAtHour,
                'SelectDay'=>$laytime->SelectDay,
                'TimeCountsIfOnDemurrage'=>$laytime->TimeCountsIfOnDemurrage,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_DpLaytimeCommencement', $commence_data);
            }
        }
            
    }
    return $ret;
}
    
public function getDisportRowByCargoID($CargoID)
{
    $this->db->select('udt_AU_CargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspPortCode, udt_PortMaster.Description as dspPortDescription, udt_PortMaster.Code as dspCode');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('CD_ID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDisportDetailsByCargoID($CargoID)
{
    $this->db->select('udt_AU_CargoDisports.*, udt_PortMaster.PortName as dspPortName, udt_PortMaster.Code as dspCode, ldt.Code as trmCode, ldt.Description as trmDescription, dft.Code as ftCode, dft.Description as ftDescription, cnr1.Code as cnrDCode');
    $this->db->from('udt_AU_CargoDisports');
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->join('udt_CP_LoadingDischargeTermsMaster as ldt', 'ldt.ID=udt_AU_CargoDisports.DischargingTerms', 'left');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster as dft', 'dft.ID=udt_AU_CargoDisports.DpTurnTime', 'left');
    $this->db->join('udt_CP_NORTenderingConditionMaster as cnr1', 'cnr1.ID=udt_AU_CargoDisports.DpNorTendering', 'left');
    $this->db->where('CargoID', $CargoID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getCargoDisportsByRow($AuctionID,$id)
{
    $this->db->select('*');
    $this->db->from("udt_AU_Cargo");
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $id);
    $query = $this->db->get();
    $cargoRow=$query->row(); 
        
    $this->db->select('udt_AU_CargoDisports.*,udt_PortMaster.PortName');
    $this->db->from("udt_AU_CargoDisports");
    $this->db->join('udt_PortMaster', 'udt_PortMaster.ID=udt_AU_CargoDisports.DisPort', 'left');
    $this->db->where('CargoID', $cargoRow->CargoID);
    $qry = $this->db->get();
    return $qry->result(); 
        
}
    
public function getCargoRowByID($AuctionID,$id)
{
    $this->db->select('*');
    $this->db->from("udt_AU_Cargo");
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('LineNum', $id);
    $query = $this->db->get();
    return $query->row(); 
        
}
    
    
public function getNewQuoteDetails()
{ 
    $ids=$this->input->post('ids');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('udt_AU_Differentials.*, udt_AUM_Vessel_Master.VesselSize, udt_AUM_Vessel_Master.SizeGroup, baseport.PortName as basePortName, refport1.PortName as refPortName1, refport2.PortName as refPortName2, refport3.PortName as refPortName3');
    $this->db->from('udt_AU_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_Differentials.VesselGroupSizeID', 'left');
    $this->db->join('udt_PortMaster as baseport', 'baseport.ID=udt_AU_Differentials.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as refport1', 'refport1.ID = udt_AU_Differentials.DisportRefPort1', 'left');
    $this->db->join('udt_PortMaster as refport2', 'refport2.ID = udt_AU_Differentials.DisportRefPort2', 'left');
    $this->db->join('udt_PortMaster as refport3', 'refport3.ID = udt_AU_Differentials.DisportRefPort3', 'left');
    $this->db->where('LineNum', $ids);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getNewQuoteDiffReferenceDetails($DifferentialID)
{ 
    $this->db->select('udt_AU_DifferentialRefDisports.*,refport.PortName as refPortName');
    $this->db->from('udt_AU_DifferentialRefDisports');
    $this->db->join('udt_PortMaster as refport', 'refport.ID=udt_AU_DifferentialRefDisports.RefDisportID', 'left');
    $this->db->where('DifferentialID', $DifferentialID);
    $this->db->order_by('DiffRefDisportID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function saveNewQuote()
{
    extract($this->input->post());
        
    $DifferentialID=$this->input->post('DifferentialID');
    if($DifferentialID) {
        $this->db->where('DifferentialID', $DifferentialID);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_Differentials');
            
        $this->db->where('DifferentialID', $DifferentialID);
        $this->db->where('AuctionID', $AuctionID);
        $this->db->delete('udt_AU_DifferentialRefDisports');
            
    } 
        
    $DisportRefPort1=0;
    $DisportRefPort2=0;
    $DisportRefPort3=0;
    if($FreightReferenceFlg==1) {
        $DisportRefPort1=$disports_for_reference_port1;
    } else if($FreightReferenceFlg==2) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
    } else if($FreightReferenceFlg==3) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
        $DisportRefPort3=$disports_for_reference_port3;
    }
        
    $diffData=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$AuctionID,
    'LineNum'=>$ids,
    'FreightRateFlg'=>$NewFreightBasis,
    'VesselGroupSizeID'=>$DifferentialVesselSizeGroup,
    'BaseLoadPort'=>$DifferentialLoadport,
    'FreightReferenceFlg'=>$FreightReferenceFlg,
    'DisportRefPort1'=>$DisportRefPort1,
    'DisportRefPort2'=>$DisportRefPort2,
    'DisportRefPort3'=>$DisportRefPort3,
    'CargoOwnerComment'=>$CommentsForAuctioner,
    'InviteeComment'=>$CommentsForInvitees,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
        
    $ret=$this->db->insert('udt_AU_Differentials', $diffData);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('LineNum', $ids);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $diffRow=$query->row();
        
    $DifferentialID=$diffRow->DifferentialID;
        
    for($i=0; $i<count($DiffRefDisportID); $i++){ 
        $refData=array(
        'DifferentialID'=>$DifferentialID,
        'AuctionID'=>$AuctionID,
        'RefDisportID'=>$old_DifferentialDisport[$i],
        'LpDpFlg'=>$old_DisportFlg[$i],
        'LoadDischargeRate'=>$old_LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$old_LoadDischangeUnit[$i],
        'DifferentialFlg'=>$old_DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$old_DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$old_DifferentialInvitee[$i],
        'GroupNo'=>$old_grp[$i],
        'PrimaryPortFlg'=>$old_primary_port[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_DifferentialRefDisports', $refData);
    }
        
    for($i=0; $i<count($DifferentialDisport); $i++){
        $refData=array(
        'DifferentialID'=>$DifferentialID,
        'AuctionID'=>$AuctionID,
        'RefDisportID'=>$DifferentialDisport[$i],
        'LpDpFlg'=>$DisportFlg[$i],
        'LoadDischargeRate'=>$LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$LoadDischangeUnit[$i],
        'DifferentialFlg'=>$DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$DifferentialInvitee[$i],
        'GroupNo'=>$grp[$i],
        'PrimaryPortFlg'=>$primary_port[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_DifferentialRefDisports', $refData);
    }
        
        
    $this->db->query(
        "insert into cops_admin.udt_AU_Differentials_H (DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,RowStatus,UserID,UserDate)
		select DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,1,UserID,UserDate
		from cops_admin.udt_AU_Differentials where DifferentialID='".$DifferentialID."' "
    );
        
    if(count($DifferentialDisport) > 0 || count($DiffRefDisportID) > 0) {
        $this->db->select('*');
        $this->db->from('udt_AU_Counter');
        $qryCntrRow=$this->db->get();
        $NewCounterNo=$qryCntrRow->row()->CounterNo+1;
            
        $this->db->query(
            "insert into cops_admin.udt_AU_DifferentialRefDisports_H (DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,RowStatus,RowCounter,UserID,CreatedDate)
			select DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,1,'".$NewCounterNo."',UserID,CreatedDate
			from cops_admin.udt_AU_DifferentialRefDisports where DifferentialID='".$DifferentialID."' "
        );
            
        $this->db->update('udt_AU_Counter', array('CounterNo'=>$NewCounterNo));
            
    }
        
    return $ret;
        
}
    
public function AddNewQuote()
{
    extract($this->input->post());
        
    $DisportRefPort1=0;
    $DisportRefPort2=0;
    $DisportRefPort3=0;
    if($FreightReferenceFlg==1) {
        $DisportRefPort1=$disports_for_reference_port1;
    } else if($FreightReferenceFlg==2) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
    } else if($FreightReferenceFlg==3) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
        $DisportRefPort3=$disports_for_reference_port3;
    }
        
    $diffData=array(
    'CoCode'=>C_COCODE,
    'AuctionID'=>$AuctionID,
    'LineNum'=>$ids,
    'FreightRateFlg'=>$NewFreightBasis,
    'VesselGroupSizeID'=>$DifferentialVesselSizeGroup,
    'BaseLoadPort'=>$DifferentialLoadport,
    'FreightReferenceFlg'=>$FreightReferenceFlg,
    'DisportRefPort1'=>$DisportRefPort1,
    'DisportRefPort2'=>$DisportRefPort2,
    'DisportRefPort3'=>$DisportRefPort3,
    'CargoOwnerComment'=>$CommentsForAuctioner,
    'InviteeComment'=>$CommentsForInvitees,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
        
    $ret=$this->db->insert('udt_AU_Differentials', $diffData);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('LineNum', $ids);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $diffRow=$query->row();
        
    $DifferentialID=$diffRow->DifferentialID;
        
    for($i=0; $i<count($DifferentialDisport); $i++){
        $refData=array(
        'DifferentialID'=>$DifferentialID,
        'AuctionID'=>$AuctionID,
        'RefDisportID'=>$DifferentialDisport[$i],
        'LpDpFlg'=>$DisportFlg[$i],
        'LoadDischargeRate'=>$LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$LoadDischangeUnit[$i],
        'DifferentialFlg'=>$DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$DifferentialInvitee[$i],
        'GroupNo'=>$grp[$i],
        'PrimaryPortFlg'=>$primary_port[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_DifferentialRefDisports', $refData);
    }
        
    $this->db->query(
        "insert into cops_admin.udt_AU_Differentials_H (DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,RowStatus,UserID,UserDate)
		select DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,1,UserID,UserDate
		from cops_admin.udt_AU_Differentials where DifferentialID='".$DifferentialID."' "
    );
        
    if(count($DifferentialDisport) > 0) {
        $this->db->select('*');
        $this->db->from('udt_AU_Counter');
        $qryCntrRow=$this->db->get();
        $NewCounterNo=$qryCntrRow->row()->CounterNo+1;
            
        $this->db->query(
            "insert into cops_admin.udt_AU_DifferentialRefDisports_H (DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,RowStatus,RowCounter,UserID,CreatedDate)
			select DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,1,'".$NewCounterNo."',UserID,CreatedDate
			from cops_admin.udt_AU_DifferentialRefDisports where DifferentialID='".$DifferentialID."' "
        );
            
        $this->db->update('udt_AU_Counter', array('CounterNo'=>$NewCounterNo));
            
    }
        
    return $ret;
        
}
    
public function updateNewQuote()
{
    extract($this->input->post());
        
    $aData=array(
    'auctionStatus'=>'P',
    'auctionExtendedStatus'=>'',
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
        
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AU_Auctions', $aData);
        
    $DisportRefPort1=0;
    $DisportRefPort2=0;
    $DisportRefPort3=0;
    if($FreightReferenceFlg==1) {
        $DisportRefPort1=$disports_for_reference_port1;
    } else if($FreightReferenceFlg==2) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
    } else if($FreightReferenceFlg==3) {
        $DisportRefPort1=$disports_for_reference_port1;
        $DisportRefPort2=$disports_for_reference_port2;
        $DisportRefPort3=$disports_for_reference_port3;
    }
        
    $diffData=array(
    'FreightRateFlg'=>$NewFreightBasis,
    'VesselGroupSizeID'=>$DifferentialVesselSizeGroup,
    'BaseLoadPort'=>$DifferentialLoadport,
    'FreightReferenceFlg'=>$FreightReferenceFlg,
    'DisportRefPort1'=>$DisportRefPort1,
    'DisportRefPort2'=>$DisportRefPort2,
    'DisportRefPort3'=>$DisportRefPort3,
    'CargoOwnerComment'=>$CommentsForAuctioner,
    'InviteeComment'=>$CommentsForInvitees,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
    $this->db->where('DifferentialID', $DifferentialID);
    $ret=$this->db->update('udt_AU_Differentials', $diffData);
        
    $this->db->select('*');
    $this->db->from('udt_AU_DifferentialRefDisports');
    $this->db->where('DifferentialID', $DifferentialID);
    $this->db->order_by('DiffRefDisportID', 'asc');
    $query=$this->db->get();
    $refResult=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Counter');
    $qryCntrRow=$this->db->get();
    $NewCounterNo=$qryCntrRow->row()->CounterNo+1;
            
    foreach($refResult as $ref){
        if (in_array($ref->DiffRefDisportID, $DiffRefDisportID)) {
            // will be updated in DiffRefDisportID array
        } else {
            $this->db->query(
                "insert into cops_admin.udt_AU_DifferentialRefDisports_H (DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,RowStatus,RowCounter,UserID,CreatedDate)
				select DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,3,'".$NewCounterNo."',UserID,CreatedDate
				from cops_admin.udt_AU_DifferentialRefDisports where DiffRefDisportID='".$DiffRefDisportID."' "
            );
            
            $this->db->where('DiffRefDisportID', $ref->DiffRefDisportID);
            $this->db->delete('udt_AU_DifferentialRefDisports');
        }    
    }
        
    //-------old differential reference ports--------
    for($i=0; $i<count($DiffRefDisportID); $i++){ 
        $refData=array(
        'RefDisportID'=>$old_DifferentialDisport[$i],
        'LpDpFlg'=>$old_DisportFlg[$i],
        'LoadDischargeRate'=>$old_LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$old_LoadDischangeUnit[$i],
        'DifferentialFlg'=>$old_DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$old_DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$old_DifferentialInvitee[$i],
        'GroupNo'=>$old_grp[$i],
        'PrimaryPortFlg'=>$old_primary_port[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->where('DiffRefDisportID', $DiffRefDisportID[$i]);
        $this->db->update('udt_AU_DifferentialRefDisports', $refData);
            
    }
        
    //---------new differential reference ports added --------
    for($i=0; $i<count($DifferentialDisport); $i++){
        $refData=array(
        'DifferentialID'=>$DifferentialID,
        'AuctionID'=>$AuctionID,
        'RefDisportID'=>$DifferentialDisport[$i],
        'LpDpFlg'=>$DisportFlg[$i],
        'LoadDischargeRate'=>$LoadDischangeRate[$i],
        'LoadDischargeUnit'=>$LoadDischangeUnit[$i],
        'DifferentialFlg'=>$DifferentialFlg[$i],
        'DifferentialOwnerAmt'=>$DifferentialOwnerAmount[$i],
        'DifferentialInviteeAmt'=>$DifferentialInvitee[$i],
        'GroupNo'=>$grp[$i],
        'PrimaryPortFlg'=>$primary_port[$i],
        'UserID'=>$UserID,
        'CreatedDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_DifferentialRefDisports', $refData);
    }
        
    $this->db->query(
        "insert into cops_admin.udt_AU_Differentials_H (DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,RowStatus,UserID,UserDate)
		select DifferentialID,CoCode,AuctionID,LineNum,VesselGroupSizeID,BaseLoadPort,FreightReferenceFlg,DisportRefPort1,DisportRefPort2,DisportRefPort3,CargoOwnerComment,InviteeComment,FreightRateFlg,2,UserID,UserDate
		from cops_admin.udt_AU_Differentials where DifferentialID='".$DifferentialID."' "
    );
        
    if(count($refResult)> 0 || count($DifferentialDisport) > 0) {
            
        $this->db->query(
            "insert into cops_admin.udt_AU_DifferentialRefDisports_H (DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,RowStatus,RowCounter,UserID,CreatedDate)
			select DiffRefDisportID,DifferentialID,AuctionID,RefDisportID,LpDpFlg,LoadDischargeRate,LoadDischargeUnit,DifferentialFlg,DifferentialOwnerAmt,DifferentialInviteeAmt,GroupNo,PrimaryPortFlg,2,'".$NewCounterNo."',UserID,CreatedDate
			from cops_admin.udt_AU_DifferentialRefDisports where DifferentialID='".$DifferentialID."' "
        );
            
        $this->db->update('udt_AU_Counter', array('CounterNo'=>$NewCounterNo));
    }
        
    return $ret;
}
    
public function getQuoteDifferentialDataById()
{ 
    $id=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
        
    $this->db->select('udt_AU_Differentials.*,udt_AUM_Vessel_Master.*,lp.PortName as basePortName, lp.Code as baseCode, dpref1.PortName as dpref1PortName, dpref1.Code as dpref1Code, dpref2.PortName as dpref2PortName, dpref2.Code as dpref2Code, dpref3.PortName as dpref3PortName, dpref3.Code as dpref3Code ');
    $this->db->from('udt_AU_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_Differentials.VesselGroupSizeID', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_Differentials.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as dpref1', 'dpref1.ID=udt_AU_Differentials.DisportRefPort1', 'left');
    $this->db->join('udt_PortMaster as dpref2', 'dpref2.ID=udt_AU_Differentials.DisportRefPort2', 'left'); 
    $this->db->join('udt_PortMaster as dpref3', 'dpref3.ID=udt_AU_Differentials.DisportRefPort3', 'left'); 
    $this->db->where('LineNum', $id);
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getQuoteRefPortsDataById($DifferentialID)
{ 
    $AuctionID=$this->input->post('auctionId');
        
    $this->db->select('udt_AU_DifferentialRefDisports.*,refpt.PortName as refPortName, refpt.Code as refPortCode');
    $this->db->from('udt_AU_DifferentialRefDisports');
    $this->db->join('udt_PortMaster as refpt', 'refpt.ID=udt_AU_DifferentialRefDisports.RefDisportID', 'left');
    $this->db->where('DifferentialID', $DifferentialID);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('GroupNo', 'asc');
    $this->db->order_by('PrimaryPortFlg', 'desc');
    $this->db->order_by('DiffRefDisportID', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function saveNewQuoteMessage($oldData,$newData,$oldDiffRefData,$newDiffRefData)
{
    $AuctionID=$this->input->post('AuctionID');
    $UserID=$this->input->post('UserID');

        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
    $Section='Quote';
    $message='';
        
    if($msgData) {
        if($oldData->VesselGroupSizeID != $newData->VesselGroupSizeID) {
            $message .='<br>Old Differential Vessel Size Group : '.$oldData->VesselSize.' ( '.$oldData->SizeGroup.' ) New Differential Vessel Size Group :'.$newData->VesselSize.' ( '.$newData->SizeGroup.' )';
        }
        if($oldData->BaseLoadPort != $newData->BaseLoadPort) {
            $message .='<br>Old Base (Load) Port : '.$oldData->basePortName.' New Base (Load) Port  :'.$newData->basePortName;
        }
        if($oldData->FreightReferenceFlg != $newData->FreightReferenceFlg) {
            if($oldData->FreightReferenceFlg==1) {
                $oldFreightReferenceFlg='Freight reference port 1';
            } else if($oldData->FreightReferenceFlg==2) {
                $oldFreightReferenceFlg='Freight reference port 2';
            } else if($oldData->FreightReferenceFlg==3) {
                $oldFreightReferenceFlg='Freight reference port 3';
            }
            if($newData->FreightReferenceFlg==1) {
                $newFreightReferenceFlg='Freight reference port 1';
            } else if($newData->FreightReferenceFlg==2) {
                $newFreightReferenceFlg='Freight reference port 2';
            } else if($newData->FreightReferenceFlg==3) {
                $newFreightReferenceFlg='Freight reference port 3';
            }
            $message .='<br>Old Disport(s) for freight reference : '.$oldFreightReferenceFlg.' New Disport(s) for freight reference  : '.$newFreightReferenceFlg;
        }
        if($oldData->DisportRefPort1 != $newData->DisportRefPort1) {
            $message .='<br>Old Disport 1 reference port : '.$oldData->refPortName1.' New Disport 1 reference port  : '.$newData->refPortName1;
        }
        if($oldData->DisportRefPort2 != $newData->DisportRefPort2) {
            $message .='<br>Old Disport 2 reference port : '.$oldData->refPortName2.' New Disport 2 reference port  : '.$newData->refPortName2;
        }
        if($oldData->DisportRefPort3 != $newData->DisportRefPort3) {
            $message .='<br>Old Disport 3 reference port : '.$oldData->refPortName3.' New Disport 3 reference port  : '.$newData->refPortName3;
        }
            
            
        foreach($newDiffRefData as $new){
            $newFlg=1;
            foreach($oldDiffRefData as $old){
                if($old->DiffRefDisportID==$new->DiffRefDisportID) {
                    $newFlg=0;
                    if($old->RefDisportID != $new->RefDisportID) {
                         $message .='<br>Old Differential disport(s) : '.$old->refPortName.' New Differential disport(s) : '.$new->refPortName;
                    }
                    if($old->LpDpFlg != $new->LpDpFlg) {
                        if($old->LpDpFlg==1) {
                             $oldLpDpFlg='Yes';
                        } else if($old->LpDpFlg==2) {
                            $oldLpDpFlg='No';
                        }
                        if($new->LpDpFlg==1) {
                            $newLpDpFlg='Yes';
                        } else if($new->LpDpFlg==2) {
                            $newLpDpFlg='No';
                        }
                        $message .='<br>Old Lp/Dp : '.$oldLpDpFlg.' New Lp/Dp : '.$newLpDpFlg;
                    }
                    if($old->LoadDischargeRate != $new->LoadDischargeRate) {
                        $message .='<br>Old Load/Dis Rate : '.$old->LoadDischargeRate.' New Load/Dis Rate : '.$new->LoadDischargeRate;
                    }
                    if($old->LoadDischargeUnit != $new->LoadDischargeUnit) {
                        if($old->LoadDischargeUnit==1) {
                            $oldLoadDischargeUnit='$mt/hr';
                        } else if($old->LoadDischargeUnit==2) {
                            $oldLoadDischargeUnit='$USD';
                        }
                        if($new->LoadDischargeUnit==1) {
                            $newLoadDischargeUnit='$mt/hr';
                        } else if($new->LoadDischargeUnit==2) {
                            $newLoadDischargeUnit='$USD';
                        }
                        $message .='<br>Old Load/Dis Unit : '.$oldLoadDischargeUnit.' New Load/Dis Unit : '.$newLoadDischargeUnit;
                    }
                    if($old->DifferentialFlg != $new->DifferentialFlg) {
                        if($old->DifferentialFlg==1) {
                            $oldDifferentialFlg='Yes';
                        } else if($old->DifferentialFlg==2) {
                            $oldDifferentialFlg='No';
                        }
                        if($new->DifferentialFlg==1) {
                            $newDifferentialFlg='Yes';
                        } else if($new->DifferentialFlg==2) {
                            $newDifferentialFlg='No';
                        }
                        $message .='<br>Old Load/Dis Unit : '.$oldDifferentialFlg.' New Load/Dis Unit : '.$newDifferentialFlg;
                            
                    }
                    if($old->DifferentialOwnerAmt != $new->DifferentialOwnerAmt) {
                        $message .='<br>Old Differential (owner) ($) : '.$old->DifferentialOwnerAmt.' New Differential (owner) ($) : '.$new->DifferentialOwnerAmt;
                    }
                }
                    
            }
            if($newFlg==1) {
                $message .='<br>Old Differential disport(s) :  New Differential disport(s) : '.$new->refPortName;
                if($new->LpDpFlg==1) {
                    $newLpDpFlg='Yes';
                } else if($new->LpDpFlg==2) {
                    $newLpDpFlg='No';
                }
                $message .='<br>Old Lp/Dp :  New Lp/Dp : '.$newLpDpFlg;
                $message .='<br>Old Load/Dis Rate :  New Load/Dis Rate : '.$new->LoadDischargeRate;
                if($new->LoadDischargeUnit==1) {
                    $newLoadDischargeUnit='$mt/hr';
                } else if($new->LoadDischargeUnit==2) {
                    $newLoadDischargeUnit='$USD';
                }
                    $message .='<br>Old Load/Dis Unit :  New Load/Dis Unit : '.$newLoadDischargeUnit;
                if($new->DifferentialFlg==1) {
                    $newDifferentialFlg='Yes';
                } else if($new->DifferentialFlg==2) {
                               $newDifferentialFlg='No';
                }
                    $message .='<br>Old Load/Dis Unit :  New Load/Dis Unit : '.$newDifferentialFlg;
                if($new->DifferentialFlg==1) {
                    $message .='<br>Old Differential (owner) ($) :  New Differential (owner) ($) : '.$new->DifferentialOwnerAmt;
                }
            }
        }
            
        if($message !='') {
            $quotedata=array( 
            'CoCode'=>'marx',
            'AuctionID'=>$AuctionID,
            'Event'=>'Edit & Update',
            'Page'=>'Cargo Set Up',
            'Section'=>'Quote',
            'subSection'=>'Quote',
            'StatusFlag'=>'1',
            'MessageDetail'=>$message,
            'MessageMasterID'=>$msgData->MessageID,
            'UserID'=>$UserID,
            'FromUserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s')
            );    
            $this->db->insert('udt_AU_Messsage_Details', $quotedata);
                
            $msg_data=array(
            'MessageFlag'=>'1',
            'MsgDate'=>date('Y-m-d H:i:s')
            );            
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', $msg_data);
        }
    }
        
}
    
public function getQuoteDifferentialDetails()
{ 
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionId');
    }
    $this->db->select('udt_AU_Differentials.*,udt_AUM_Vessel_Master.*,lp.PortName as basePortName, lp.Code as baseCode, dpref1.PortName as dpref1PortName, dpref1.Code as dpref1Code, dpref2.PortName as dpref2PortName, dpref2.Code as dpref2Code, dpref3.PortName as dpref3PortName, dpref3.Code as dpref3Code ');
    $this->db->from('udt_AU_Differentials');
    $this->db->join('udt_AUM_Vessel_Master', 'udt_AUM_Vessel_Master.VesselID=udt_AU_Differentials.VesselGroupSizeID', 'left');
    $this->db->join('udt_PortMaster as lp', 'lp.ID=udt_AU_Differentials.BaseLoadPort', 'left');
    $this->db->join('udt_PortMaster as dpref1', 'dpref1.ID=udt_AU_Differentials.DisportRefPort1', 'left');
    $this->db->join('udt_PortMaster as dpref2', 'dpref2.ID=udt_AU_Differentials.DisportRefPort2', 'left'); 
    $this->db->join('udt_PortMaster as dpref3', 'dpref3.ID=udt_AU_Differentials.DisportRefPort3', 'left'); 
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('LineNum', 'asc');
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getQuoteDisportReferencesDetails()
{
        
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionId');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionId');
    }
    $this->db->select('udt_AU_DifferentialRefDisports.*,refport.PortName as refPortName,refport.Code as refCode');
    $this->db->from('udt_AU_DifferentialRefDisports');
    $this->db->join('udt_PortMaster as refport', 'refport.ID=udt_AU_DifferentialRefDisports.RefDisportID', 'left');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('DifferentialID', 'asc');
    $this->db->order_by('GroupNo', 'asc');
    $this->db->order_by('PrimaryPortFlg', 'desc');
    $this->db->order_by('DiffRefDisportID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getBankDetailsByAuctionID()
{
    $EntityID=$this->input->get('EntityID');
    $AuctionID=$this->input->get('AuctionID');
    $UserID=$this->input->get('UserID');
        
    $this->db->select('*');
    $this->db->from('Udt_AU_BankingDetail');
    $this->db->where('EntityID', $EntityID);
    $this->db->where('ActiveFlag', 1);
    $query=$this->db->get();
    $rslt=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('BankProcessType', 1);
    $query1=$this->db->get();
    $cnddata=$query1->result();
        
    $BankMasterIDArr=array();
        
    foreach($rslt as $row) {
        if($row->DetailsAppliesTo==2) {
            continue;
        }
        $OfficeArr=explode(",", $row->OfficeEntityID);
        if(in_array($EntityID, $OfficeArr)) {
            $sts=1;
            array_push($BankMasterIDArr, $row->ID);
            foreach($cnddata as $rd) {
                if ($rd->BankMasterID==$row->ID) { 
                    $sts=0;
                } 
            }
            if($sts==1) {
                $data=array(
                'AuctionID'=>$AuctionID,
                'RecordOwner'=>$EntityID,
                'BankMasterID'=>$row->ID,
                'BankProcessType'=>1,
                'ForEntityID'=>$EntityID,
                'BankStatus'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_AuctionBank', $data);
                    
                $this->db->select('*');
                $this->db->from('udt_AU_AuctionBank');
                $this->db->where('AuctionID', $AuctionID);
                $this->db->where('BankMasterID', $row->ID);
                $this->db->where('BankProcessType', 1);
                $this->db->order_by('ABID', 'desc');
                $query12=$this->db->get();
                $latestRow=$query12->row();
                    
                $data_h=array(
                'ABID'=>$latestRow->ABID,
                'AuctionID'=>$AuctionID,
                'RecordOwner'=>$EntityID,
                'BankMasterID'=>$row->ID,
                'BankProcessType'=>1,
                'ForEntityID'=>$EntityID,
                'BankStatus'=>1,
                'RowStatus'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>$latestRow->CreatedDate
                );
                $this->db->insert('udt_AU_AuctionBank_H', $data_h);
            }
        }
    }
        
    foreach($cnddata as $rd) {
        if(in_array($rd->BankMasterID, $BankMasterIDArr)) {
            // do nothing.
        } else {
            $this->db->query(
                "insert into cops_admin.udt_AU_AuctionBank_H (ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,BankStatus,RowStatus,UserID,CreatedDate)
				select ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,BankStatus,3,$UserID,CreatedDate
				from cops_admin.udt_AU_AuctionBank where ABID='".$rd->ABID."' "
            );
                
            $this->db->where('ABID', $rd->ABID);
            $this->db->delete('udt_AU_AuctionBank');
        }
    }
        
    $this->db->select('udt_AU_AuctionBank.*, Udt_AU_BankingDetail.*, udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as CountryName,udt_StateMaster.Code as scode,udt_StateMaster.Description as StateName');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('Udt_AU_BankingDetail', 'Udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=Udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=Udt_AU_BankingDetail.State', 'left');
    $this->db->where('udt_AU_AuctionBank.AuctionID', $AuctionID);
    $this->db->where('udt_AU_AuctionBank.BankProcessType', 1);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function getBankDetailsByABID($ABID)
{
    $this->db->select('udt_AU_AuctionBank.*, Udt_AU_BankingDetail.*, udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as CountryName,udt_StateMaster.Code as scode,udt_StateMaster.Description as StateName, udt_CurrencyMaster.Code as crCode, udt_CurrencyMaster.Description as crDescription');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('Udt_AU_BankingDetail', 'Udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID', 'left');
    $this->db->join('udt_CurrencyMaster', 'udt_CurrencyMaster.ID=Udt_AU_BankingDetail.CurrencyID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=Udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=Udt_AU_BankingDetail.State', 'left');
    $this->db->where('udt_AU_AuctionBank.ABID', $ABID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function changeBankStatusByABID()
{
    $ABID=$this->input->post('ABID');
    $Status=$this->input->post('Status');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('udt_AU_AuctionBank.*,Udt_AU_BankingDetail.*');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('Udt_AU_BankingDetail', 'Udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID', 'left');
    $this->db->where('udt_AU_AuctionBank.ABID', $ABID);
    $query=$this->db->get();
    $ABRow=$query->row();
        
    $this->db->query(
        "insert into cops_admin.udt_AU_AuctionBank_H (ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,BankStatus,RowStatus,UserID,CreatedDate)
		select ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,ForEntityID,$Status,2,$UserID,CreatedDate
		from cops_admin.udt_AU_AuctionBank where ABID=$ABID "
    );
        
    $this->db->where('ABID', $ABID);
    $ret=$this->db->update('udt_AU_AuctionBank', array('BankStatus'=>$Status));
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $ABRow->RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->where('ABID', $ABID);
    $query=$this->db->get();
    $newABRow=$query->row();
        
    if($msgData) {
        $oldStatus='Inactive';
        if($ABRow->BankStatus==1) {
            $oldStatus='Active';
        }
        $newStatus='Inactive';
        if($newABRow->BankStatus==1) {
            $newStatus='Active';
        }
        $message ='<br>Account Number : '.$ABRow->AccountNumber.' status changes.';
        $message .='<br>Old Status : '.$oldStatus.' New Status : '.$newStatus;
        if($ABRow->BankProcessType==1) {
            $Section='Charter Details';
            $SubSection='Bank Details';
        } else if($ABRow->BankProcessType==2) {
            $Section='Select Invitees';
            $SubSection='Bank Details';
        }
            $mdata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$ABRow->AuctionID,
                'Event'=>'Edit & Update',
                'Page'=>'Cargo Set Up',
                'Section'=>$Section,
                'subSection'=>$SubSection,
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$msgData->MessageID,
                'UserID'=>$UserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $mdata); 

            $this->db->where('AuctionID', $ABRow->AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $ret;
}
    
public function getInviteeBankDetailsByAuctionID()
{
    $EntityID=$this->input->post('EntityID');
    $AuctionID=$this->input->post('AuctionID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('InvID', 'asc');
    $iquery=$this->db->get();
    $invResult=$iquery->result();
        
    $invEntityArr=array();
        
    foreach($invResult as $inv){
        if(in_array($inv->EntityID, $invEntityArr)) {
            // do nothing.
        } else {
            array_push($invEntityArr, $inv->EntityID);
                
            $this->db->select('*');
            $this->db->from('Udt_AU_BankingDetail');
            $this->db->where('ActiveFlag', 1);
            $query=$this->db->get();
            $rslt=$query->result();
                
            $this->db->select('*');
            $this->db->from('udt_AU_AuctionBank');
            $this->db->where('AuctionID', $AuctionID);
            $this->db->where('ForEntityID', $inv->EntityID);
            $this->db->where('BankProcessType', 2);
            $query1=$this->db->get();
            $cnddata=$query1->result();
                
            $BankMasterIDArr=array();
                
            foreach($rslt as $row) {
                if($row->DetailsAppliesTo==1) {
                    continue;
                }
                $OfficeArr=explode(",", $row->OfficeEntityID);
                if(in_array($inv->EntityID, $OfficeArr)) {
                    $sts=1;
                    array_push($BankMasterIDArr, $row->ID);
                    foreach($cnddata as $rd) {
                        if ($rd->BankMasterID==$row->ID) { 
                            $sts=0;
                        } 
                    }
                    if($sts==1) {
                        $data=array(
                        'AuctionID'=>$AuctionID,
                        'RecordOwner'=>$EntityID,
                        'BankMasterID'=>$row->ID,
                        'BankProcessType'=>2,
                        'ForEntityID'=>$inv->EntityID,
                        'BankStatus'=>1,
                        'UserID'=>$UserID,
                        'CreatedDate'=>date('Y-m-d H:i:s')
                        );
                        $this->db->insert('udt_AU_AuctionBank', $data);
                            
                        $this->db->select('*');
                        $this->db->from('udt_AU_AuctionBank');
                        $this->db->where('AuctionID', $AuctionID);
                        $this->db->where('BankMasterID', $row->ID);
                        $this->db->where('BankProcessType', 2);
                        $this->db->order_by('ABID', 'desc');
                        $query12=$this->db->get();
                        $latestRow=$query12->row();
                            
                        $data_h=array(
                        'ABID'=>$latestRow->ABID,
                        'AuctionID'=>$AuctionID,
                        'RecordOwner'=>$EntityID,
                        'BankMasterID'=>$row->ID,
                        'BankProcessType'=>1,
                        'ForEntityID'=>$inv->EntityID,
                        'BankStatus'=>1,
                        'RowStatus'=>1,
                        'UserID'=>$UserID,
                        'CreatedDate'=>$latestRow->CreatedDate
                        );
                        $this->db->insert('udt_AU_AuctionBank_H', $data_h);
                    }
                }
            }
            foreach($cnddata as $rd) {
                if(in_array($rd->BankMasterID, $BankMasterIDArr)) {
                    // do nothing.
                } else {
                    $this->db->query(
                        "insert into cops_admin.udt_AU_AuctionBank_H (ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,BankStatus,RowStatus,UserID,CreatedDate)
						select ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,BankStatus,3,$UserID,CreatedDate
						from cops_admin.udt_AU_AuctionBank where ABID='".$rd->ABID."' "
                    );
                        
                    $this->db->where('ABID', $rd->ABID);
                    $this->db->delete('udt_AU_AuctionBank');
                }
            }
        }
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('BankProcessType', 2);
    $qry1=$this->db->get();
    $invBnkData=$qry1->result();
        
    foreach($invBnkData as $rd1) {
        if(in_array($rd1->ForEntityID, $invEntityArr)) {
            //do nothing.
        } else {
            $this->db->query(
                "insert into cops_admin.udt_AU_AuctionBank_H (ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,BankStatus,RowStatus,UserID,CreatedDate)
				select ABID,AuctionID,RecordOwner,BankMasterID,BankProcessType,BankStatus,3,$UserID,CreatedDate
				from cops_admin.udt_AU_AuctionBank where ABID='".$rd1->ABID."' "
            );
                
            $this->db->where('ABID', $rd1->ABID);
            $this->db->delete('udt_AU_AuctionBank');
        }
    }
        
    $this->db->select('udt_AU_AuctionBank.*, Udt_AU_BankingDetail.*, udt_CountryMaster.Code as ccode,udt_CountryMaster.Description as CountryName,udt_StateMaster.Code as scode,udt_StateMaster.Description as StateName');
    $this->db->from('udt_AU_AuctionBank');
    $this->db->join('Udt_AU_BankingDetail', 'Udt_AU_BankingDetail.ID=udt_AU_AuctionBank.BankMasterID', 'left');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=Udt_AU_BankingDetail.Country', 'left');
    $this->db->join('udt_StateMaster', 'udt_StateMaster.ID=Udt_AU_BankingDetail.State', 'left');
    $this->db->where('udt_AU_AuctionBank.AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_AuctionBank.ForEntityID', 'asc');
    $this->db->where('udt_AU_AuctionBank.BankProcessType', 2);
    $query=$this->db->get();
    return $query->result();
}
    
public function getInviteeDetailsByAuctionID()
{
    $EntityID=$this->input->post('EntityID');
    $AuctionID=$this->input->post('AuctionID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('InvID', 'asc');
    $iquery=$this->db->get();
    return $iquery->result();
        
}
    
public function getAllExceptedPeriodEvents($AuctionID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $auctionRow=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AUM_ExceptedPeriodEventsMaster');
    $this->db->where('Entity_ID', $auctionRow->OwnerEntityID);
    $this->db->where('ActiveFlag', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getAllNORTenderingPreConditions($AuctionID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $auctionRow=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_CP_NORPreTenderingConditionMaster');
    $this->db->where('Entity_ID', $auctionRow->OwnerEntityID);
    $this->db->where('ActiveFlag', 1);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getAllNORAcceptancePreConditions($AuctionID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    $auctionRow=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_CP_NORPreConditionAcceptMaster');
    $this->db->where('Entity_ID', $auctionRow->OwnerEntityID);
    $this->db->where('ActiveFlag', 1);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpExpectedPeriodByCargoID($CargoID)
{
    $this->db->select('udt_AU_ExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
    $this->db->from('udt_AU_ExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_ExceptedPeriods.EventID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('EPID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpNORTenderingPreByCargoID($CargoID)
{
    $this->db->select('udt_AU_NORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_NORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_NORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('TPCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpNORAcceptancePreByCargoID($CargoID)
{
    $this->db->select('udt_AU_NORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_NORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_NORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('APCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpOfficeHoursByCargoID($CargoID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_OfficeHours');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('OHID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLpLaytimeCommenceByCargoID($CargoID)
{
    $this->db->select('udt_AU_LaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_LaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_LaytimeCommencement.TurnTime', 'left');
    $this->db->where('CargoID', $CargoID);
    $this->db->order_by('LCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getExceptedPeriodEventsByDisportId()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_DpExceptedPeriods.*');
    $this->db->from('udt_AU_DpExceptedPeriods');
    $this->db->where('DisportID', $CD_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getTenderingPreConditionsByDisportId()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_DpNORTenderingPreConditions.*');
    $this->db->from('udt_AU_DpNORTenderingPreConditions');
    $this->db->where('DisportID', $CD_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getAcceptancePreConditionByDisportId()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_DpNORAcceptancePreConditions.*');
    $this->db->from('udt_AU_DpNORAcceptancePreConditions');
    $this->db->where('DisportID', $CD_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getOfficeHoursByDisportId()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_DpOfficeHours.*');
    $this->db->from('udt_AU_DpOfficeHours');
    $this->db->where('DisportID', $CD_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getLaytimeCommencementByDisportId()
{
    $CD_ID=$this->input->post('CD_ID');
    $this->db->select('udt_AU_DpLaytimeCommencement.*');
    $this->db->from('udt_AU_DpLaytimeCommencement');
    $this->db->where('DisportID', $CD_ID);
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpExpectedPeriodByDisportID($DisportID)
{
    $this->db->select('udt_AU_DpExceptedPeriods.*,udt_AUM_ExceptedPeriodEventsMaster.Code as ExceptedCode, udt_AUM_ExceptedPeriodEventsMaster.Description as ExceptedDescription');
    $this->db->from('udt_AU_DpExceptedPeriods');
    $this->db->join('udt_AUM_ExceptedPeriodEventsMaster', 'udt_AUM_ExceptedPeriodEventsMaster.ID=udt_AU_DpExceptedPeriods.EventID', 'left');
    $this->db->where('DisportID', $DisportID);
    $this->db->order_by('EPID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpNORTenderingPreByDisportID($DisportID)
{
    $this->db->select('udt_AU_DpNORTenderingPreConditions.*,udt_CP_NORPreTenderingConditionMaster.Code as TenderingCode');
    $this->db->from('udt_AU_DpNORTenderingPreConditions');
    $this->db->join('udt_CP_NORPreTenderingConditionMaster', 'udt_CP_NORPreTenderingConditionMaster.ID=udt_AU_DpNORTenderingPreConditions.NORTenderingPreConditionID', 'left');
    $this->db->where('DisportID', $DisportID);
    $this->db->order_by('TPCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpNORAcceptancePreByDisportID($DisportID)
{
    $this->db->select('udt_AU_DpNORAcceptancePreConditions.*, udt_CP_NORPreConditionAcceptMaster.Code as AcceptanceCode');
    $this->db->from('udt_AU_DpNORAcceptancePreConditions');
    $this->db->join('udt_CP_NORPreConditionAcceptMaster', 'udt_CP_NORPreConditionAcceptMaster.ID=udt_AU_DpNORAcceptancePreConditions.NORAcceptancePreConditionID', 'left');
    $this->db->where('DisportID', $DisportID);
    $this->db->order_by('APCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpOfficeHoursByDisportID($DisportID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_DpOfficeHours');
    $this->db->where('DisportID', $DisportID);
    $this->db->order_by('OHID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getDpLaytimeCommenceByDisportID($DisportID)
{
    $this->db->select('udt_AU_DpLaytimeCommencement.*,udt_CP_LayTimeFreeTimeConditionMaster.Code as LaytimeCode');
    $this->db->from('udt_AU_DpLaytimeCommencement');
    $this->db->join('udt_CP_LayTimeFreeTimeConditionMaster', 'udt_CP_LayTimeFreeTimeConditionMaster.ID=udt_AU_DpLaytimeCommencement.TurnTime', 'left');
    $this->db->where('DisportID', $DisportID);
    $this->db->order_by('LCID', 'asc');
    $qry=$this->db->get();
    return $qry->result();
}
    
public function getEditableFieldQuote($AuctionID)
{
    $this->db->select('ChkLabel,ChkFlag');
    $this->db->from('Udt_AU_EditableFiledBox');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getCargoExtendTimeRecord($AuctionID)
{
    $this->db->select('AuctionCeases,QuoteCeasesExtendTime,ExtendTime1,ExtendTime2,ExtendTime3');
    $this->db->from('udt_AUM_Alerts');
    $this->db->where('AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function saveCargoExtendTimeRecord()
{
    $AuctionID=$this->input->post('AuctionID');
    $extend_time1=$this->input->post('extend_time1');
    $extend_time2=$this->input->post('extend_time2');
    $extend_time3=$this->input->post('extend_time3');
    $UserID=$this->input->post('UserID');
        
    $data=array(
    'ExtendTime1'=>$extend_time1,
    'ExtendTime2'=>$extend_time2,
    'ExtendTime3'=>$extend_time3,
    'UserID'=>$UserID,
    'UserDate'=>date('Y-m-d H:i:s')
    );
        
    $this->db->where('AuctionID', $AuctionID);
    $ret=$this->db->update('udt_AUM_Alerts', $data);
        
    $this->db->query(
        "insert into cops_admin.udt_AUM_Alerts_H( CoCode, AuctionID, CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, UserID, UserDate, LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate, RowStatus,auctionvalidityhour,auctionceaseshour, AuctionValidMinutes)
		select CoCode, AuctionID, CommenceAlertFlag, AuctionCommences, OnlyDisplay, CommenceDaysBefore, CommenceDate, AuctionValidity, AuctionCeases, AlertBeforeCommence, AlertBeforeClosing, AlertNotificationCommence, AlertNotificationClosing, IncludeClosing, '".$UserID."', '".date('Y-m-d H:i:s')."', LayCanStartDate, AuctionerComments, InviteesComments, AuctionCommenceDefinedDate,2,auctionvalidityhour,auctionceaseshour, AuctionValidMinutes
		from cops_admin.udt_AUM_Alerts where AuctionID='".$AuctionID."'"
    );
        
    return $ret;
        
}
    
    //----------------- business process status------------------------------
    
public function changeBusinessProcessOnSubject()
{
    $status=$this->input->post('status');
    $bpid=$this->input->post('bpid');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpRow=$query->row();
        
    $data=array('on_subject_status'=>$status);
    $this->db->where('BPAID', $bpid);
    $ret=$this->db->update('udt_AU_BusinessProcessAuctionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $bpRow->AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpNewRow=$query->row();
        
    if($msgData) {
        $oldStatus='Manual';
        if($bpRow->on_subject_status==2) {
            $oldStatus='Auto';
        }
        $newStatus='Manual';
        if($bpNewRow->on_subject_status==2) {
            $newStatus='Auto';
        }
        $message='<br>Old Status : '.$oldStatus.' New Status : '.$newStatus;
        if($bpRow->BussinessType==1) {
            $Section='Charter Details';
            $SubSection='Business Process';
        } else if($bpRow->BussinessType==2) {
            $Section='Select Invitees';
            $SubSection='Invitee Business Process';
        }
            $busdata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$bpRow->AuctionID,
                'Event'=>'Edit & Update',
                'Page'=>'Cargo Set Up',
                'Section'=>$Section,
                'subSection'=>$SubSection,
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$msgData->MessageID,
                'UserID'=>$UserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $busdata); 

            $this->db->where('AuctionID', $bpRow->AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $ret;
}
    
public function changeBusinessProcessLiftSubject()
{
    $status=$this->input->post('status');
    $bpid=$this->input->post('bpid');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpRow=$query->row();
        
    $data=array('lift_subject_status'=>$status);
    $this->db->where('BPAID', $bpid);
    $ret=$this->db->update('udt_AU_BusinessProcessAuctionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $bpRow->AuctionID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.MessageID, udt_AUM_MESSAGE_MASTER.Message, udt_UserMaster.LoginID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $result->OwnerEntityID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $UserID);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'sys_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', 'edit_update');
    $query=$this->db->get();
    $msgData=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BPAID', $bpid);
    $query=$this->db->get();
    $bpNewRow=$query->row();
        
    if($msgData) {
        $oldStatus='Manual';
        if($bpRow->lift_subject_status==2) {
            $oldStatus='Auto';
        }
        $newStatus='Manual';
        if($bpNewRow->lift_subject_status==2) {
            $newStatus='Auto';
        }
        $message='<br>Old Status : '.$oldStatus.' New Status : '.$newStatus;
        if($bpRow->BussinessType==1) {
            $Section='Charter Details';
            $SubSection='Business Process';
        } else if($bpRow->BussinessType==2) {
            $Section='Select Invitees';
            $SubSection='Invitee Business Process';
        }
            $busdata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$bpRow->AuctionID,
                'Event'=>'Edit & Update',
                'Page'=>'Cargo Set Up',
                'Section'=>$Section,
                'subSection'=>$SubSection,
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$msgData->MessageID,
                'UserID'=>$UserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_Messsage_Details', $busdata); 

            $this->db->where('AuctionID', $bpRow->AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $ret;
}
    
public function getOwnerEntityDetailsByAuctionID($AuctionID)
{
    $this->db->select('*');
    $this->db->from('udt_AU_Auctions');
    $this->db->where('AuctionID', $AuctionID);
    $qry=$this->db->get();
    $auction_row=$qry->row();
        
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $auction_row->OwnerEntityID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getCargoFileAttached($AuctionSection)
{
    $AuctionID=$this->input->post('AuctionID');
    $Cargoline=$this->input->post('Cargoline');
    $this->db->select('udt_AUM_Documents.*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('udt_AUM_Documents.AuctionID', $AuctionID);
    $this->db->where('udt_AUM_Documents.LineNum', $Cargoline);
    $this->db->where('udt_AUM_Documents.AuctionSection', $AuctionSection);
    $query=$this->db->get();
    return $query->result();
}
    
    
public function getModalFunction()
{
    $EntityID=$this->input->post('EntityID');
    $this->db->select('mid,ModelNumber,ModelFunction');
    $this->db->from('udt_AU_Model');
    $this->db->where('RecordOwner', $EntityID);
    $this->db->where('ModelStatus', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getMessagesByAuctionID()
{
    $AuctionID=$this->input->post('AuctionID');
    $EID=$this->input->post('EID');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AU_Messsage_Details.*,toUser.FirstName,toUser.LastName,toEntity.EntityName,fromUser.FirstName as FromFirstName,fromUser.LastName as FromLastName,fromEntity.EntityName as FromEntityName,udt_AUM_MESSAGE_MASTER.Message');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_AUM_MESSAGE_MASTER', 'udt_AUM_MESSAGE_MASTER.MessageID=udt_AU_Messsage_Details.MessageMasterID', 'left');
    $this->db->join('udt_UserMaster as toUser', 'toUser.ID=udt_AU_Messsage_Details.UserID', 'left');
    $this->db->join('udt_EntityMaster as toEntity', 'toEntity.ID=toUser.EntityID', 'left');
    $this->db->join('udt_UserMaster as fromUser', 'fromUser.ID=udt_AU_Messsage_Details.FromUserID', 'left');
    $this->db->join('udt_EntityMaster as fromEntity', 'fromEntity.ID=fromUser.EntityID', 'left');
    $this->db->where('udt_AU_Messsage_Details.AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.StatusFlag', 1);
    if($EID) {
        $this->db->where('toUser.EntityID', $EID);
        $this->db->where('toUser.ID', $UserID);
    }
    $this->db->order_by('udt_AU_Messsage_Details.UserDate', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getUserByIds($UserList)
{
    $ids=explode(",", $UserList);
    $this->db->select('FirstName,LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where_in('ID', $ids);
    $query=$this->db->get();
    $rslt=$query->result();
        
    $html='';
    foreach($rslt as $row) {
        $html .=$row->FirstName.' '.$row->LastName.',';
    }
    $html=trim($html, ",");
    return $html;
}
    
public function getSteveDoringTerms()
{
    $this->db->select('*');
    $this->db->from('udt_CP_SteveDoringTerms');
    $this->db->where('ActiveFlag', 1);
    $query=$this->db->get();
    return $query->result();
}
    
public function getStevedoringTermsByID($id)
{
    $this->db->select('*');
    $this->db->from('udt_CP_SteveDoringTerms');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getOwnerEntityDetailsByID($OwnerEntityID)
{
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $OwnerEntityID);
    $qry=$this->db->get();
    return $qry->row();
}
    
public function getPortDetailsByID($ID)
{
    $this->db->select('*');
    $this->db->from('udt_PortMaster');
    $this->db->where('ID', $ID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCountUnreadMessages($AuctionID)
{
    $UserID=$this->input->get('UserID');
    $EID=$this->input->get('EID');
    $this->db->select('udt_AU_Messsage_Details.*');
    $this->db->from('udt_AU_Messsage_Details');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID = udt_AU_Messsage_Details.UserID');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('udt_AU_Messsage_Details.StatusFlag', 1);
    if($EID) {
        $this->db->where('udt_UserMaster.EntityID', $EID);
        $this->db->where('udt_UserMaster.ID', $UserID);
    }
    $query=$this->db->get();
    $rslt=$query->result();
    return count($rslt);
}
    
public function getEntityById($EntityID)
{
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $EntityID);
    $query=$this->db->get();
    return $query->row();
}
    
public function create_response_cargo()
{
    $auctionid=$this->input->post('auctionid');
    $UserID=$this->input->post('UserID');
        
    $this->db->where('AuctionID', $auctionid);
    $this->db->delete('udt_AU_BACResponse');
        
    $this->db->select('ResponseID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $auctionid);
    $query=$this->db->get();
    $result=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Cargo');
    $this->db->where('AuctionID', $auctionid);
    $query1=$this->db->get();
    $result1=$query1->result();
        
    $i=1;
    foreach($result as $row){
        $i=1;
        foreach($result1 as $c){
            $this->db->select('*');
            $this->db->from('udt_AU_BAC');
            $this->db->where('AuctionID', $auctionid);
            $this->db->where('CargoLineNum', $c->LineNum);
            $querybac=$this->db->get();
            $resultbac=$querybac->result();
            $data=array(
            'CargoVersion'=>'Version '.$i.'.0',
            'ResponseID'=>$row->ResponseID,
            'CoCode'=>$c->CoCode,
            'AuctionID'=>$c->AuctionID,
            'LineNum'=>$c->LineNum,
            'ActiveFlag'=>$c->ActiveFlag,
            'SelectFrom'=>$c->SelectFrom,
            'CargoQtyMT'=>$c->CargoQtyMT,
            'CargoLoadedBasis'=>$c->CargoLoadedBasis,
            'CargoLimitBasis'=>$c->CargoLimitBasis,
            'ToleranceLimit'=>$c->ToleranceLimit,
            'UpperLimit'=>$c->UpperLimit,
            'LowerLimit'=>$c->LowerLimit,
            'MaxCargoMT'=>$c->MaxCargoMT,
            'MinCargoMT'=>$c->MinCargoMT,
            'LoadPort'=>$c->LoadPort,
            'LpLaycanStartDate'=>$c->LpLaycanStartDate,
            'LpLaycanEndDate'=>$c->LpLaycanEndDate,
            'LpPreferDate'=>$c->LpPreferDate,
            'LoadingTerms'=>$c->LoadingTerms,
            'LoadingRateMT'=>$c->LoadingRateMT,
            'LoadingRateUOM'=>$c->LoadingRateUOM,
            'LpMaxTime'=>$c->LpMaxTime,
            'LpLaytimeType'=>$c->LpLaytimeType,
            'LpCalculationBasedOn'=>$c->LpCalculationBasedOn,
            'LpTurnTime'=>$c->LpTurnTime,
            'LpPriorUseTerms'=>$c->LpPriorUseTerms,
            'LpLaytimeBasedOn'=>$c->LpLaytimeBasedOn,
            'LpCharterType'=>$c->LpCharterType,
            'LpNorTendering'=>$c->LpNorTendering,
            'CargoInternalComments'=>$c->CargoInternalComments,
            'CargoDisplayComments'=>$c->CargoDisplayComments,
            'ExpectedLpDelayDay'=>'0',
            'ExpectedLpDelayHour'=>'0',
            'BACFlag'=>$c->BACFlag,
            'LpStevedoringTerms'=>$c->LpStevedoringTerms,
            'ExceptedPeriodFlg'=>$c->ExceptedPeriodFlg,
            'NORTenderingPreConditionFlg'=>$c->NORTenderingPreConditionFlg,
            'NORAcceptancePreConditionFlg'=>$c->NORAcceptancePreConditionFlg,
            'OfficeHoursFlg'=>$c->OfficeHoursFlg,
            'LaytimeCommencementFlg'=>$c->LaytimeCommencementFlg,
            'UserID'=>$UserID,
            'RecordAddBy'=>$UserID,
            'ContentChange'=>'',
            'UserDate'=>date('Y-m-d H:i:s')
            );
            $this->db->insert('udt_AU_ResponseCargo', $data);
                
            $data_ass=array(
            'ResponseID'=>$row->ResponseID,
            'CoCode'=>$c->CoCode,
            'AuctionID'=>$c->AuctionID,
            'LineNum'=>$c->LineNum,
            'ActiveFlag'=>$c->ActiveFlag,
            'SelectFrom'=>$c->SelectFrom,
            'CargoQtyMT'=>$c->CargoQtyMT,
            'CargoLoadedBasis'=>$c->CargoLoadedBasis,
            'CargoLimitBasis'=>$c->CargoLimitBasis,
            'ToleranceLimit'=>$c->ToleranceLimit,
            'UpperLimit'=>$c->UpperLimit,
            'LowerLimit'=>$c->LowerLimit,
            'MaxCargoMT'=>$c->MaxCargoMT,
            'MinCargoMT'=>$c->MinCargoMT,
            'LoadPort'=>$c->LoadPort,
            'LpLaycanStartDate'=>$c->LpLaycanStartDate,
            'LpLaycanEndDate'=>$c->LpLaycanEndDate,
            'LpPreferDate'=>$c->LpPreferDate,
            'LoadingTerms'=>$c->LoadingTerms,
            'LoadingRateMT'=>$c->LoadingRateMT,
            'LoadingRateUOM'=>$c->LoadingRateUOM,
            'LpMaxTime'=>$c->LpMaxTime,
            'LpLaytimeType'=>$c->LpLaytimeType,
            'LpCalculationBasedOn'=>$c->LpCalculationBasedOn,
            'LpTurnTime'=>$c->LpTurnTime,
            'LpPriorUseTerms'=>$c->LpPriorUseTerms,
            'LpLaytimeBasedOn'=>$c->LpLaytimeBasedOn,
            'LpCharterType'=>$c->LpCharterType,
            'LpNorTendering'=>$c->LpNorTendering,
            'CargoInternalComments'=>$c->CargoInternalComments,
            'CargoDisplayComments'=>$c->CargoDisplayComments,
            'ExpectedLpDelayDay'=>'0',
            'ExpectedLpDelayHour'=>'0',
            'UserID'=>$UserID,
            'UserDate'=>date('Y-m-d H:i:s'),
            'RecordAddBy'=>$UserID,
            'BACFlag'=>$c->BACFlag,
            'LpStevedoringTerms'=>$c->LpStevedoringTerms,
            'ExceptedPeriodFlg'=>$c->ExceptedPeriodFlg,
            'NORTenderingPreConditionFlg'=>$c->NORTenderingPreConditionFlg,
            'NORAcceptancePreConditionFlg'=>$c->NORAcceptancePreConditionFlg,
            'OfficeHoursFlg'=>$c->OfficeHoursFlg,
            'LaytimeCommencementFlg'=>$c->LaytimeCommencementFlg
            );
            $this->db->insert('udt_AU_CargoResponseAssessment', $data_ass);
                
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseCargo');
            $this->db->where('ResponseID', $row->ResponseID);
            $this->db->order_by('ResponseCargoID', 'DESC');
            $queryres=$this->db->get();
            $responseRow=$queryres->row();
                
            $ResponseCargoID=$responseRow->ResponseCargoID;
                
            if($c->ExceptedPeriodFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_ExceptedPeriods');
                $this->db->where('CargoID', $c->CargoID);
                $this->db->order_by('EPID', 'asc');
                $qry_period=$this->db->get();
                $period_data=$qry_period->result();
                    
                foreach($period_data as $p){
                    $p_data=array(
                    'AuctionID'=>$p->AuctionID,
                    'ResponseCargoID'=>$ResponseCargoID,
                    'ResponseID'=>$row->ResponseID,
                    'EventID'=>$p->EventID,
                    'LaytimeCountsOnDemurrageFlg'=>$p->LaytimeCountsOnDemurrageFlg,
                    'LaytimeCountsFlg'=>$p->LaytimeCountsFlg,
                    'TimeCountingFlg'=>$p->TimeCountingFlg,
                    'ExceptedPeriodComment'=>$p->ExceptedPeriodComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseExceptedPeriods', $p_data);
                }
            }
                
            if($c->NORTenderingPreConditionFlg==1) {
                 $this->db->select('*');
                 $this->db->from('udt_AU_NORTenderingPreConditions');
                 $this->db->where('CargoID', $c->CargoID);
                 $this->db->order_by('TPCID', 'asc');
                 $qry_tendering=$this->db->get();
                 $tendering_data=$qry_tendering->result();
                    
                foreach($tendering_data as $t){
                    $t_data=array(
                    'AuctionID'=>$t->AuctionID,
                    'ResponseCargoID'=>$ResponseCargoID,
                    'ResponseID'=>$row->ResponseID,
                    'CreateNewOrSelectListFlg'=>$t->CreateNewOrSelectListFlg,
                    'NORTenderingPreConditionID'=>$t->NORTenderingPreConditionID,
                    'NewNORTenderingPreCondition'=>$t->NewNORTenderingPreCondition,
                    'StatusFlag'=>$t->StatusFlag,
                    'TenderingPreConditionComment'=>$t->TenderingPreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseNORTenderingPreConditions', $t_data);
                }
            }
                
            if($c->NORAcceptancePreConditionFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_NORAcceptancePreConditions');
                $this->db->where('CargoID', $c->CargoID);
                $this->db->order_by('APCID', 'asc');
                $qry_acceptance=$this->db->get();
                $acceptance_data=$qry_acceptance->result();
                    
                foreach($acceptance_data as $a){
                    $a_data=array(
                    'AuctionID'=>$a->AuctionID,
                    'ResponseCargoID'=>$ResponseCargoID,
                    'ResponseID'=>$row->ResponseID,
                    'CreateNewOrSelectListFlg'=>$a->CreateNewOrSelectListFlg,
                    'NORAcceptancePreConditionID'=>$a->NORAcceptancePreConditionID,
                    'NewNORAcceptancePreCondition'=>$a->NewNORAcceptancePreCondition,
                    'StatusFlag'=>$a->StatusFlag,
                    'AcceptancePreConditionComment'=>$a->AcceptancePreConditionComment,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseNORAcceptancePreConditions', $a_data);
                }
            }
                
            if($c->OfficeHoursFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_OfficeHours');
                $this->db->where('CargoID', $c->CargoID);
                $this->db->order_by('OHID', 'asc');
                $qry_office=$this->db->get();
                $office_data=$qry_office->result();
                    
                foreach($office_data as $o){
                    $o_data=array(
                    'AuctionID'=>$o->AuctionID,
                    'ResponseCargoID'=>$ResponseCargoID,
                    'ResponseID'=>$row->ResponseID,
                    'DateFrom'=>$o->DateFrom,
                    'DateTo'=>$o->DateTo,
                    'TimeFrom'=>$o->TimeFrom,
                    'TimeTo'=>$o->TimeTo,
                    'IsLastEntry'=>$o->IsLastEntry,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseOfficeHours', $o_data);
                }
            }
                
            if($c->LaytimeCommencementFlg==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_LaytimeCommencement');
                $this->db->where('CargoID', $c->CargoID);
                $this->db->order_by('LCID', 'asc');
                $qry_laytime=$this->db->get();
                $laytime_data=$qry_laytime->result();
                    
                foreach($laytime_data as $l){
                    $l_data=array(
                    'AuctionID'=>$l->AuctionID,
                    'ResponseCargoID'=>$ResponseCargoID,
                    'ResponseID'=>$row->ResponseID,
                    'DayFrom'=>$l->DayFrom,
                    'DayTo'=>$l->DayTo,
                    'TimeFrom'=>$l->TimeFrom,
                    'TimeTo'=>$l->TimeTo,
                    'TurnTime'=>$l->TurnTime,
                    'TurnTimeExpire'=>$l->TurnTimeExpire,
                    'LaytimeCommenceAt'=>$l->LaytimeCommenceAt,
                    'LaytimeCommenceAtHour'=>$l->LaytimeCommenceAtHour,
                    'SelectDay'=>$l->SelectDay,
                    'TimeCountsIfOnDemurrage'=>$l->TimeCountsIfOnDemurrage,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_ResponseLaytimeCommencement', $l_data);
                }
            }
                
                
                $this->db->select('*');
                $this->db->from('udt_AU_CargoDisports');
                $this->db->where('CargoID', $c->CargoID);
                $this->db->order_by('CD_ID', 'asc');
                $qry_disport=$this->db->get();
                $disportResult=$qry_disport->result();
                $i=1;
            foreach($disportResult as $dis){
                $dis_data=array(
                'ResponseCargoID'=>$ResponseCargoID,
                'AuctionID'=>$dis->AuctionID,
                'ResponseID'=>$row->ResponseID,
                'DisportNo'=>$i,
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
                'DpExceptedPeriodFlg'=>$dis->DpExceptedPeriodFlg,
                'DpNORTenderingPreConditionFlg'=>$dis->DpNORTenderingPreConditionFlg,
                'DpNORAcceptancePreConditionFlg'=>$dis->DpNORAcceptancePreConditionFlg,
                'DpOfficeHoursFlg'=>$dis->DpOfficeHoursFlg,
                'DpLaytimeCommencementFlg'=>$dis->DpLaytimeCommencementFlg,
                'ExpectedDpDelayDay'=>0,
                'ExpectedDpDelayHour'=>0,
                'ConfirmFlg'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseCargoDisports', $dis_data);
                    
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $ResponseCargoID);
                $this->db->where('ResponseID', $row->ResponseID);
                $this->db->order_by('RCD_ID', 'desc');
                $qry_disport=$this->db->get();
                $resDisport=$qry_disport->row();
                $RCD_ID=$resDisport->RCD_ID;
                    
                $dis_data_h=array(
                'RCD_ID'=>$RCD_ID,
                'ResponseCargoID'=>$ResponseCargoID,
                'AuctionID'=>$dis->AuctionID,
                'ResponseID'=>$row->ResponseID,
                'DisportNo'=>$i,
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
                'DpExceptedPeriodFlg'=>$dis->DpExceptedPeriodFlg,
                'DpNORTenderingPreConditionFlg'=>$dis->DpNORTenderingPreConditionFlg,
                'DpNORAcceptancePreConditionFlg'=>$dis->DpNORAcceptancePreConditionFlg,
                'DpOfficeHoursFlg'=>$dis->DpOfficeHoursFlg,
                'DpLaytimeCommencementFlg'=>$dis->DpLaytimeCommencementFlg,
                'ExpectedDpDelayDay'=>0,
                'ExpectedDpDelayHour'=>0,
                'RowStatus'=>1,
                'ConfirmFlg'=>1,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                    
                $this->db->insert('udt_AU_ResponseCargoDisports_H', $dis_data_h);
                    
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports_H');
                $this->db->where('ResponseCargoID', $ResponseCargoID);
                $this->db->where('ResponseID', $row->ResponseID);
                $this->db->order_by('RCD_ID_H', 'desc');
                $qry_disport_h=$this->db->get();
                $resDisport_h=$qry_disport_h->row();
                $RCD_ID_H=$resDisport_h->RCD_ID_H;
                    
                    
                if($dis->DpExceptedPeriodFlg==1) {
                    $this->db->select('*');
                    $this->db->from('udt_AU_DpExceptedPeriods');
                    $this->db->where('CargoID', $c->CargoID);
                    $this->db->where('DisportID', $dis->CD_ID);
                    $this->db->order_by('EPID', 'asc');
                    $qry_period=$this->db->get();
                    $period_result=$qry_period->result();
                    foreach($period_result as $pr){
                        $period_data=array(
                        'AuctionID'=>$pr->AuctionID,
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID'=>$RCD_ID,
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
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$RCD_ID_H,
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
                    $this->db->from('udt_AU_DpNORTenderingPreConditions');
                    $this->db->where('CargoID', $c->CargoID);
                    $this->db->where('DisportID', $dis->CD_ID);
                    $this->db->order_by('TPCID', 'asc');
                    $qry_tendering=$this->db->get();
                    $tendering_result=$qry_tendering->result();
                        
                    foreach($tendering_result as $tr){
                        $tendering_data=array(
                        'AuctionID'=>$tr->AuctionID,
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID'=>$RCD_ID,
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
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$RCD_ID_H,
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
                    $this->db->from('udt_AU_DpNORAcceptancePreConditions');
                    $this->db->where('CargoID', $c->CargoID);
                    $this->db->where('DisportID', $dis->CD_ID);
                    $this->db->order_by('APCID', 'asc');
                    $qry_acceptance=$this->db->get();
                    $acceptance_result=$qry_acceptance->result();
                        
                    foreach($acceptance_result as $ar){
                        $acceptance_data=array(
                        'AuctionID'=>$ar->AuctionID,
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID'=>$RCD_ID,
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
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$RCD_ID_H,
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
                    $this->db->from('udt_AU_DpOfficeHours');
                    $this->db->where('CargoID', $c->CargoID);
                    $this->db->where('DisportID', $dis->CD_ID);
                    $this->db->order_by('OHID', 'asc');
                    $qry_office=$this->db->get();
                    $office_result=$qry_office->result();
                        
                    foreach($office_result as $or){
                        $office_data=array(
                        'AuctionID'=>$or->AuctionID,
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID'=>$RCD_ID,
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
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$RCD_ID_H,
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
                    $this->db->from('udt_AU_DpLaytimeCommencement');
                    $this->db->where('CargoID', $c->CargoID);
                    $this->db->where('DisportID', $dis->CD_ID);
                    $this->db->order_by('LCID', 'asc');
                    $qry_laytime=$this->db->get();
                    $laytime_result=$qry_laytime->result();
                        
                    foreach($laytime_result as $lr){
                        $laytime_data=array(
                        'AuctionID'=>$lr->AuctionID,
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID'=>$RCD_ID,
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
                        'ResponseID'=>$row->ResponseID,
                        'ResponseCargoID'=>$ResponseCargoID,
                        'ResponseDisportID_H'=>$RCD_ID_H,
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
                    
                $i++;
            }
                $sno=1;
            foreach($resultbac as $bac){
                $bacdata=array(
                'SeqNo'=>$sno,
                'AuctionID'=>$bac->AuctionID,
                'ResponseID'=>$row->ResponseID,
                'TransactionType'=>$bac->TransactionType,
                'PayingEntityType'=>$bac->PayingEntityType,
                'PayingEntityName'=>$bac->PayingEntityName,
                'ReceivingEntityType'=>$bac->ReceivingEntityType,
                'ReceivingEntityName'=>$bac->ReceivingEntityName,
                'BrokerName'=>$bac->BrokerName,
                'PayableAs'=>$bac->PayableAs,
                'PercentageOnFreight'=>$bac->PercentageOnFreight,
                'PercentageOnDeadFreight'=>$bac->PercentageOnDeadFreight,
                'PercentageOnDemmurage'=>$bac->PercentageOnDemmurage,
                'PercentageOnOverage'=>$bac->PercentageOnOverage,
                'LumpsumPayable'=>$bac->LumpsumPayable,
                'RatePerTonnePayable'=>$bac->RatePerTonnePayable,
                'BACComment'=>$bac->BACComment,
                'ResponseCargoID'=>$ResponseCargoID,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_BACResponse', $bacdata);
                    
                $sno++;
            }
                $i++;
        }
    }
        
    $query1 = $this->db->query(
        "insert into cops_admin.udt_AU_BACResponse_H (BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, ConfirmFlg, SeqNo, RowStatus, UserID,UserDate )
		select BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, 1, SeqNo, 1, '".$UserID."','".date('Y-m-d H:i:s')."'
		from cops_admin.udt_AU_BACResponse where AuctionID='".$auctionid."' order by BACResponse_ID asc"
    );
        
    $this->db->select('udt_AUM_Invitees.EntityID, udt_AUM_Invitees.UserMasterID, udt_AUM_Invitees.InviteeRole, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_Invitees');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Invitees.EntityID');
    $this->db->where('AuctionID', $auctionid);
    $query=$this->db->get();
    $rslt=$query->result();
        
    foreach($rslt as $row) {
        if($row->InviteeRole==6) {
            $this->db->select('EntityID,ResponseID');
            $this->db->from('udt_AUM_Freight');
            $this->db->where('AuctionID', $auctionid);
            $this->db->where('EntityID', $row->EntityID);
            $query=$this->db->get();
            $fr_row=$query->row();
                
            $this->db->where('ResponseID', $fr_row->ResponseID);
            $this->db->update('udt_AU_BACResponse', array('BrokerName'=>$row->EntityName));
                
            $this->db->where('ResponseID', $fr_row->ResponseID);
            $this->db->update('udt_AU_BACResponse_H', array('BrokerName'=>$row->EntityName));
        }
    }
        
}
    
public function create_response_vessel()
{
    $auctionid=$this->input->post('auctionid');
    $UserID=$this->input->post('UserID');
    $EntityID=$this->input->post('EntityID');
    $this->db->select('ResponseID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $auctionid);
    $query=$this->db->get();
    $result=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Vessel');
    $this->db->where('AuctionID', $auctionid);
    $query1=$this->db->get();
    $result1=$query1->row();
    $CommentAuction='';
    if($result1->CommentAuction) {
        $CommentAuction=$result1->CommentAuction;
    }
    $CommentInvitee='';
    if($result1->CommentInvitee) {
        $CommentInvitee=$result1->CommentInvitee;
    }
    $i=1;
    foreach($result as $row){
        $data=array(
        'CoCode'=>C_COCODE,
        'VesselVersion'=>'Version '.$i.'.0',
        'AuctionID'=>$auctionid,
        'RecordOwner'=>$EntityID,
        'ResponseID'=>$row->ResponseID,
        'CommentAuction'=>$CommentAuction, 
        'CommentInvitee'=>$CommentInvitee, 
        'UserID'=>$UserID,
        'RecordAddBy'=>$UserID,
        'ContentChange'=>'',
        'VesselConfirmFlg'=>2,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $this->db->insert('udt_AU_ResponseVessel', $data);
    }
}
    
public function create_response_freight()
{
    $auctionid=$this->input->post('auctionid');
    $UserID=$this->input->post('UserID');
    $EntityID=$this->input->post('EntityID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('AuctionID', $auctionid);
    $query=$this->db->get();
    $result=$query->result();
        
    $this->db->select('*');
    $this->db->from('udt_AU_Differentials');
    $this->db->where('AuctionID', $auctionid);
    $this->db->order_by('LineNum', 'asc');
    $query1=$this->db->get();
    $result1=$query1->result();
        
    
    foreach($result as $row){
        $i=1;
        $LineNum=0;
        foreach($result1 as $rw){
            if($LineNum != $rw->LineNum) {
                $data=array(
                'CoCode'=>C_COCODE,
                'FreightVersion'=>'Version '.$i.'.0',
                'LineNum'=>$rw->LineNum,
                'AuctionID'=>$auctionid,
                'RecordOwner'=>$EntityID,
                'ResponseID'=>$row->ResponseID,
                'CommentsByAuctioner'=>$rw->CargoOwnerComment, 
                'CommentForInvitees'=>$rw->InviteeComment, 
                'EntityID'=>$row->EntityID,
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'ContentChange'=>'',
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_FreightResponse', $data);
                    
                    
                $data=array(
                'CoCode'=>$rw->CoCode,
                'AuctionID'=>$rw->AuctionID,
                'ResponseID'=>$row->ResponseID,
                'Version'=>'Version '.$i.'.0',
                'InviteeID'=>$row->EntityID,
                'LineNum'=>$rw->LineNum,
                'VesselGroupSizeID'=>$rw->VesselGroupSizeID,
                'BaseLoadPort'=>$rw->BaseLoadPort,
                'FreightReferenceFlg'=>$rw->FreightReferenceFlg,
                'DisportRefPort1'=>$rw->DisportRefPort1,
                'DisportRefPort2'=>$rw->DisportRefPort2,
                'DisportRefPort3'=>$rw->DisportRefPort3,
                'CargoOwnerComment'=>$rw->CargoOwnerComment,
                'InviteeComment'=>$rw->InviteeComment,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                        );
                $this->db->insert('udt_AU_DifferentialsResponse', $data);
                    
                $this->db->select('DifferentialID');
                $this->db->from('udt_AU_DifferentialsResponse');
                $this->db->order_by('DifferentialID', 'Desc');
                $query=$this->db->get();
                $NewDifferentialID=$query->row()->DifferentialID;
                    
                $this->db->select('*');
                $this->db->from('udt_AU_DifferentialRefDisports');
                $this->db->where('DifferentialID', $rw->DifferentialID);
                $query=$this->db->get();
                $rslt=$query->result();
                    
                foreach($rslt as $drow) {
                    $data=array(
                    'DifferentialID'=>$NewDifferentialID,
                    'AuctionID'=>$drow->AuctionID,
                    'ResponseID'=>$row->ResponseID,
                    'RefDisportID'=>$drow->RefDisportID,
                    'LpDpFlg'=>$drow->LpDpFlg,
                    'LoadDischargeRate'=>$drow->LoadDischargeRate,
                    'LoadDischargeUnit'=>$drow->LoadDischargeUnit,
                    'DifferentialFlg'=>$drow->DifferentialFlg,
                    'DifferentialOwnerAmt'=>$drow->DifferentialOwnerAmt,
                    'DifferentialInviteeAmt'=>$drow->DifferentialInviteeAmt,
                    'GroupNo'=>$drow->GroupNo,
                    'PrimaryPortFlg'=>$drow->PrimaryPortFlg,
                    'UserID'=>$UserID,
                    'CreatedDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_DifferentialRefDisportsResponse', $data);
                }
                    
                    
                    $data_ass=array(
                                'CoCode'=>C_COCODE,
                                'AuctionID'=>$auctionid,
                                'LineNum'=>$rw->LineNum,
                                'RecordOwner'=>$EntityID,
                                'CommentsForAuctioner'=>$row->CommentsForAuctioner, 
                                'CommentsForInvitees'=>$row->CommentsForInvitees, 
                                'UserID'=>$UserID,
                                'FinalConfirm'=>'2',
                                'UserDate'=>date('Y-m-d H:i:s'),
                                'ResponseStatus'=>$row->ResponseStatus,
                                'EntityID'=>$row->EntityID,
                                'ReleaseDate'=>$row->ReleaseDate,
                                'ResponseID'=>$row->ResponseID,
                                'TentativeStatus'=>$row->TentativeStatus,
                                'InvUserID'=>$row->InvUserID
                            );
                    $this->db->insert('udt_AU_FreightResponseAssessment', $data_ass);
                    
                    $data1=array(
                        'CoCode'=>C_COCODE,
                        'LineNum'=>$rw->LineNum,
                        'AuctionID'=>$auctionid,
                        'ResponseID'=>$row->ResponseID,
                        'RecordOwner'=>$EntityID,
                        'CommentsByAuctioner'=>$rw->CargoOwnerComment, 
                        'CommentForInvitees'=>$rw->InviteeComment, 
                        'EntityID'=>$row->EntityID,
                        'UserID'=>$UserID,
                        'RecordAddBy'=>$UserID,
                        'UserDate'=>date('Y-m-d H:i:s')
                        );
                        
                    $this->db->insert('udt_AU_Freight', $data1);
                    $LineNum = $rw->LineNum;
                    $i++;
            }
        }
    }
}
    
public function createQuoteBusinessProcesses()
{
    $auctionid=$this->input->post('auctionid');
    $UserID=$this->input->post('UserID');
    $EntityID=$this->input->post('EntityID');
        
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*, udt_UserMaster.EntityID as InviteeEntityID');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BusinessProcessAuctionWise.UserList', 'left');
    $this->db->where('AuctionID', $auctionid);
    $this->db->where('Status', 1);
    $this->db->where('BussinessType', 2);
    $query=$this->db->get();
    $business_result=$query->result();
        
    foreach($business_result as $brow){
        $this->db->select('*');
        $this->db->from('udt_AUM_BusinessProcess');
        $this->db->where('BPID', $brow->BPID);
        $querybr=$this->db->get();
        $brw=$querybr->row();
            
        if($brw->process_applies==2) {
            $this->db->select('ResponseID');
            $this->db->from('udt_AUM_Freight');
            $this->db->where('AuctionID', $auctionid);
            $this->db->where('EntityID', $brow->InviteeEntityID);
            $qry=$this->db->get();
            $fResult=$qry->result();
            foreach($fResult as $fRow){
                $this->db->select('*');
                $this->db->from('udt_AU_QuoteBusinessProcess');
                $this->db->where('MasterID', $auctionid);
                $this->db->where('TID', $fRow->ResponseID);
                $this->db->where('BPID', $brow->BPID);
                $ch_qry=$this->db->get();
                    
                if($ch_qry->num_rows() > 0) {
                    continue; 
                } else {
                        
                    $this->db->select('*');
                    $this->db->from('udt_AU_FreightResponse');
                    $this->db->where('AuctionID', $auctionid);
                    $this->db->where('ResponseID', $fRow->ResponseID);
                    $fr_qry=$this->db->get();
                    $fr_result=$fr_qry->result();
                    foreach($fr_result as $fr_row){
                        $data=array(
                         'BPID'=>$brw->BPID,
                         'FreightResponseID'=>$fr_row->FreightResponseID,
                         'RecordOwner'=>$brw->RecordOwner,
                         'InvEntityID'=>$brow->InviteeEntityID,
                         'MasterID'=>$auctionid,
                         'TID'=>$fRow->ResponseID,
                         'LineNum'=>$fr_row->LineNum,
                         'name_of_process'=>$brw->name_of_process,
                         'process_applies'=>$brw->process_applies,
                         'process_flow_sequence'=>$brw->process_flow_sequence,
                         'putting_freight_quote'=>$brw->putting_freight_quote,
                         'submitting_freight_quote'=>$brw->submitting_freight_quote,
                         'fixture_not_finalization'=>$brw->fixture_not_finalization,
                         'charter_party_finalization'=>$brw->charter_party_finalization,
                         'finalization_completed_by'=>$brw->finalization_completed_by,
                         'message_text'=>$brw->message_text,
                         'show_in_process'=>$brw->show_in_process,
                         'show_in_fixture'=>$brw->show_in_fixture,
                         'show_in_charter_party'=>$brw->show_in_charter_party,
                         'validity'=>$brw->validity,
                         'date_from'=>$brw->date_from,
                         'date_to'=>$brw->date_to,
                         'UserID'=>$UserID,
                         'ApproveStatus'=>0,
                         'ApprovedBy'=>'',
                         'UserDate'=>date('Y-m-d H:i:s'),
                         'Version'=>'1.0',
                         'ViewChanges'=>''
                        );
                        $this->db->insert('udt_AU_QuoteBusinessProcess', $data); 
                            
                    }
                        
                }
            }
                
        }
    }
        
        
}
	
	
	
}


