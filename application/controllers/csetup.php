<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
header('Access-Control-Allow-Origin: *');
    
class csetup extends CI_Controller {
    /**
     * Developer Name : Sujeet Singh and Pradeep
     *    
     * Comapny Name : HigrooveSystems 
     * 
     * Create Date : 13-09-2016
     **/
function __construct()
{
    parent::__construct();
    ob_start();
    error_reporting(0);
    $this->load->library('session');
    $this->load->model('EncriptDecrtipt_model', 'EncodeDecode');
    $this->load->model('masters_model', '', true); 
    $this->load->model('vessel_master_model', '', true);
        
} 
    
public function index()
{	
	$dd='';
	echo $this->EncodeDecode->str_encode($dd).'<br>';
	echo $this->EncodeDecode->str_decode($this->EncodeDecode->str_encode($dd));die;
    $datamenu['active']=1;
    $this->load->view('include/header');
    $this->load->view('include/topheader');
    $this->load->view('include/leftmenu2', $datamenu);
    $this->load->view('setup/setup__1role');
    $this->load->view('include/footer');
}

    
public function get__Random__Id($arg=null)
{
    $rnd= mt_rand(10000000, 99999999);
    $first = chr(mt_rand(65, 90));
    if ($first=='O') {$first='R' ;
    }
    if ($first=='I') {$first='E' ;
    }
    $posn1 = mt_rand(0, 3);
    $second = chr(mt_rand(65, 90));
    if ($second=='O') {$second='F' ;
    }
    if ($second=='I') {$second='P' ;
    }
    $posn2 = mt_rand(0, 3);
    $third = chr(mt_rand(65, 90));
    if ($third=='O') {$third='C' ;
    }
    if ($third=='I') {$third='S' ;
    }
    $auctionid = $first . substr($rnd, 0, $posn1) . $second . substr($rnd, $posn1, 4-$posn1) . substr($rnd, 4, $posn2) . $third . substr($rnd, 4+$posn2, 8-$posn2);
    $auctionid = substr($auctionid, 0, 3) . '-' . substr($auctionid, 3, 3) . '-' . substr($auctionid, 6, 3); 
    //print_r(substr($rnd,3,2));
    $data['auctionid']=$auctionid;
    if($arg=='clone') {
        return $auctionid;
    } else {
        echo json_encode($data);
    }
}
    
public function getStaticTerms()
{
    $data=$this->masters_model->getStaticTerms();
    //$html='<option></option>';
    foreach($data as $row) {
        $html .='<option>'.$row->Code.'</option>';
    }
    echo $html;
}
    
    
    
public function getDateDef10D()
{
    $date=$this->input->post('date');
    //echo date('m-d-Y',strtotime($date)); die;
    if($date) {
        $date = strtotime($date);
        $date = strtotime("+9 day", $date);
        echo date('d-m-Y', $date);
    } else {
        echo '';
    }
}
    
    
public function getFreightRate()
{
    $data=$this->masters_model->getFreightRate();
    $html='<option>Select</option>';
    foreach($data as $row) {
        $html .='<option value="'.$row->ID.'">UnitCode : '.$row->UnitCode.' || Description : '.$row->Description.'</option>';
    }
    echo $html;
}
    
    
public function get_vessel_data()
{
    $data=$this->vessel_master_model->getVesselData();
    $i=1;
    $html='';
    $inhtml='';
    $html ='{ "aaData": [';
    foreach($data as $row) {
        if($row->ActiveFlag=='1') {
            $flag='Active';
        } else {
            $flag='Inactive';
        }
        $SizeGroup='';
        if($row->SizeGroup=='Small_cape') {
            $SizeGroup='Cape - small';
        }else if($row->SizeGroup=='Mini_cape') {
            $SizeGroup='Cape - mini';
        }else if($row->SizeGroup=='Cape') {
                $SizeGroup='Cape - standard';
        }else if($row->SizeGroup=='Big_cape') {
            $SizeGroup='Cape - big';
        }else{
            $SizeGroup=$row->SizeGroup;
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='vessel[]' value='".$row->VesselID."'>";
            $edit="<a href='javascript: void(0);' onclick='editVessel1(".$row->VesselID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='cloneVessel1(".$row->VesselID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteVessel1(".$row->VesselID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->VesselSize.'","'.$SizeGroup.'","'.$row->CargoRangePercentage.'","'.$row->CargoRangeFrom.'","'.$row->CargoRangeTo.'","'.$flag.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function get_vessel_table_data()
{
    $search = array();
    $this->load->model('vessel_master_model', 'vessel');;
    $sEcho                =    $this->input->post('sEcho');
    $iDisplayStart        =    $this->input->post('iDisplayStart');
    $iDisplayLength        =    $this->input->post('iDisplayLength');
    $output        =    $this->vessel->get_vessel_table_data($iDisplayStart, $iDisplayLength, $search, $sEcho);
    $output        =    json_encode($output);
    echo $output;
    exit;
}
    
public function search_vessel_data()
{
    //echo 'test'; die;
    $data=$this->vessel_master_model->searchVesselData();
    //print_r($data);die;
    $html='';
    foreach($data as $row) {
        if($row->ActiveFlag=='1') {
            $flag='Active';
        } else {
            $flag='Inactive';
        } 
        $html .='<tr>
				<td ><input type="checkbox" class="chkNumber" value="'.$row->VesselID.'" /> </td>
				<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>
				<td>'.$row->VesselSize.'</td>
				<td>'.$row->SizeGroup.'</td>
				<td>'.$row->CargoRangePercentage.'</td>
				<td>'.$row->CargoRangeFrom.'</td>
				<td>'.$row->CargoRangeTo.'</td>
				<td>'.$row->EntityName.'</td>
				<td>'.$flag.'</td>
				</tr>';
    }
    echo $html;
}
    
public function search_document_type_data()
{
    //echo 'test'; die;
    $data=$this->vessel_master_model->searchDocumentDataData();
    //print_r($data);die;
    $html='';
    foreach($data as $row) {
        if($row->ActiveFlag=='1') {
            $flag='Active';
        } else {
            $flag='Inactive';
        } 
        $html .='<tr>
				<td ><input type="checkbox" class="chkNumber" value="'.$row->DocumentTypeID.'" /> </td>
				<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>
				<td>'.$row->DocumentType.'</td>
				<td>'.$row->DocumentTitle.'</td>
				<td>'.$row->EntityName.'</td>
				<td>'.$flag.'</td>
				<td>'.$row->OwnerName.'</td>
				</tr>';
    }
    echo $html;
}
    
public function search_invitee_data()
{
    //echo 'test'; die;
    $data=$this->vessel_master_model->searchInviteeData();
    //print_r($data); die;
    $html='';
    foreach($data as $row) {
        if($row->InviteeStatus=='1') {
            $flag='Active';
        } else {
            $flag='Deactive';
        }
        if($row->InviteePeriod=='0') {
            $period1='Infinite';
            $period2='Infinite';
        } else {
            $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
            $period2=date('d-m-Y', strtotime($row->DateRangeTo));
        }
            
            $html .='<tr>
				<td ><input type="checkbox" class="chkNumber" value="'.$row->ID.'" /> </td>
				<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>
				<td>'.$row->EntityName.'</td>
				<td>'.$flag.'</td>
				<td>'.$period1.'</td>
				<td>'.$period2.'</td>
				<td>'.$row->UserGroup.'</td>
				<td>'.$row->PriorityStatus.'</td>
				
				</tr>';
    }
    echo $html;
}
 
public function save_vessel_data()
{
        
    $savenew=$this->vessel_master_model->addVesselDetails();
    //print_r($savenew); die;
    if($savenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function check_vessel()
{
    $flag=$this->vessel_master_model->check_vessel();
    echo $flag;
}
    
public function update_vessel_data()
{
    $updatenew=$this->vessel_master_model->updateVesselDetails();
    if($updatenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function update_document_data()
{
    //echo 'test'; die;
    $updatenew=$this->vessel_master_model->updateDocumentDetails();
    if($updatenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
     
public function vesselEdit()
{
    $id=$this->input->post('id');
        
    $data['Details']=$this->vessel_master_model->get_vessel_dataByIds($id);
    print_r($data); die;
    //echo json_encode($data);
}
    
    
    
public function document_Active()
{
    $id=$this->input->post('id');
    $status=$this->input->post('status');
    //echo $id; die;
    $activenew=$this->vessel_master_model->change_document_status($id, $status);
    if($activenew) {
        echo 1;
    } else {
        echo 2;
    }  
        
}
     
     
public function vesselDelete()
{
    $ids=$this->input->post('id');
    $deletenew=$this->vessel_master_model->deleteVesselByIds($ids);
    if($deletenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function vesselClone()
{
    $id=$this->input->post('id');
    $clonenew=$this->vessel_master_model->cloneVesselByIds($id);
    if($clonenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function documentClone()
{
    $id=$this->input->post('id');
    $clonenew=$this->vessel_master_model->cloneDocumentByIds($id);
    if($clonenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function fill_vessel_data()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->all_vessel_ById($id);
    echo json_encode($data);
        
}
    
public function fill_invitee_data()
{
    $id=$this->input->post('id');
    $data['record']=$this->vessel_master_model->all_invitee_ById($id);
    $data['priority_reason']=$this->vessel_master_model->getPriorityReasonsByInviteeID($id);
    echo json_encode($data);
        
}
    
public function fill_Document_data()
{
    $id=$this->input->post('id');
    $data['DocTypeDetails']=$this->vessel_master_model->all_document_ById($id);
    $data['DocTitle']=$this->masters_model->getDocumentTypeTitleByEntityid();
    $data['DocType']=$this->masters_model->getDocumentTitleByDocumentType1($data['DocTypeDetails']->DocumentType, $data['DocTypeDetails']->EntityMasterID);
    echo json_encode($data);
        
}
    
public function save_document_data()
{
    $savenew=$this->vessel_master_model->add_document_Details();
    if($savenew) {
        $row=$this->vessel_master_model->get_DocumentType_Master();
        echo $row->DocumentTypeID;
    } else {
        echo 0;
    }
}
    
public function get_document_data()
{
    $data=$this->vessel_master_model->getDocumentData();
    $i=1;
    $html='';
    $inhtml='';
    $html ='{ "aaData": [';
    foreach($data as $row) {
        $documet_type=$this->masters_model->getDocumentTypeDataByID($row->DocumentTitle);
        if($row->ActiveFlag=='1') {
            $flag='Active';
        } else {
            $flag='Inactive';
        } 
        $charterPartyEditableFlag='No';
        if($row->charterPartyEditableFlag==1) {
            $charterPartyEditableFlag='Yes';
        }
            $CharterPartyPdf='No';
        if($row->CharterPartyPdf) {
            $CharterPartyPdf='Yes';
        }
            
            $Logo='No';
        if($row->Logo) {
            $Logo='Yes';
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='vessel[]' value='".."'>";
            $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->DocumentTypeID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteDocument(".$row->DocumentTypeID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->DocumentType.'","'.$documet_type->DocName.'","'.$charterPartyEditableFlag.'","'.$CharterPartyPdf.'","'.$Logo.'","'.$flag.'","'.$row->OwnerName.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function get_invitee_data()
{
    $data=$this->vessel_master_model->getInviteeData();
        
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row) {
        if($row->InviteeStatus=='1') {
            $flag='Active';
        } else {
            $flag='Deactive';
        }
        if($row->InviteePeriod=='0') {
            $period1='Infinite';
            $period2='Infinite';
        } else {
            $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
            $period2=date('d-m-Y', strtotime($row->DateRangeTo));
        }
            
        if($row->PrimeRole) {
                $PrimeRole='Yes';
        } else {
                $PrimeRole='No';
        }
            
        if($row->QuoteLimitFlag==1) {
            $QuoteLimitFlag='Single';
        } else if($row->QuoteLimitFlag==2) {
            $QuoteLimitFlag='Multiple';
        } else {
            $QuoteLimitFlag='';
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='vessel[]' value='".$row->InviteeID."'>";    
            $edit="<a href='javascript: void(0);' onclick='editInvitee(".$row->InviteeID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteInvitee(".$row->InviteeID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->EntityName.'","'.$row->FirstName.' '.$row->LastName.'","'.$period1.'","'.$period2.'","'.$row->UserGroup.'","'.$row->RoleDescription.'","'.$PrimeRole.'","'.$row->PriorityStatus.'","'.$QuoteLimitFlag.'","'.$flag.'","'.$row->OwnerName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function all_entity_data()
{
    $this->load->model('cargo_model', '', true); 
    $res=$this->vessel_master_model->all_entity_Data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
        $data_arr['label']='EntityName: '.$row->EntityName.' ( '.$Entity->EntityName.' )';
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function all_shipbroker_entity()
{
    $res=$this->vessel_master_model->all_shipbroker_entity();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='EntityName: '.$row->EntityName.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function all_shipowner_entity()
{
    $res=$this->vessel_master_model->all_shipowner_entity();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='EntityName: '.$row->EntityName.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function all_my_entity_data()
{
    $this->load->model('cargo_model', '', true); 
    $entity=$this->input->post('entity');
    $res=$this->vessel_master_model->all_my_entity_data();
         
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        if($entity==1 && $row->EntityOwner !=1) {
            continue;
        }
        $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
        $data_arr['label']='EntityName : '.$row->EntityName.' ( '.$Entity->EntityName.' )';
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    
    $res1=$this->vessel_master_model->my_entity_data();
        
    foreach($res1 as $row){
        $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
        $data_arr['label']='EntityName: '.$row->EntityName.' ( '.$Entity->EntityName.' )';
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0 || count($res1)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function all_entity_data_invitee()
{
    $this->load->model('cargo_model', '', true); 
    $res=$this->vessel_master_model->all_entity_data_invitee();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row) {
        $type_flg=$this->masters_model->checkEntityType($row->ID);
        if($type_flg) {
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            $data_arr['value']=$row->ID;
            $data_arr['label']='EntityName: '.$row->EntityName.' ( '.$Entity->EntityName.' )';
            array_push($return_arr, $data_arr);
        }
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function get_all_document_type()
{
    $res=$this->vessel_master_model->get_all_document_type();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentType: '.$row->DocumentType;
        $data_arr['value']=$row->DocumentType;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_all_document_master_type()
{
    $res=$this->vessel_master_model->get_all_document_master_type();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentType: '.$row->DocType;
        $data_arr['value']=$row->DocType;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_all_document_title()
{
    $res=$this->vessel_master_model->get_all_document_title();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentTitle: '.$row->DocumentTitle;
        $data_arr['value']=$row->DocumentTitle;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_all_document_master_title()
{
    $res=$this->vessel_master_model->get_all_document_master_title();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentTitle: '.$row->DocName;
        $data_arr['value']=$row->DocName;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
    
    
public function get_parent_data()
{
    $data=$this->vessel_master_model->getParentData();
    echo $data->GroupName;
}
    
public function get_entity_data()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->getEntityData();
    $html='<option value="">Select</option>';
    foreach($data as $row) {
        $html .='<option value="'.$row->ID.'">'.$row->EntityName.'</option>';
    }
    echo $html; 
}
    
public function get_user_data()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->getUserData($id);
    $html='';
    foreach($data as $row) {
        $firstname=trim($row->FirstName, " ");
        $firstname = str_replace(' ', '&nbsp;', $firstname);
        $lastname=trim($row->LastName, " ");
        $lastname = str_replace(' ', '&nbsp;', $lastname);
        $html .='<tr style="text-align: left;">
				<td ><input  type="checkbox" class="chkNumber" name="userid[]" value="'.$row->ID.'" onClick=getUserValue("'.$firstname.'&nbsp;'.$lastname.'") /></td>
				<td>'.$firstname.' '.$lastname.'</td>
				<td>'.$row->Email.'</td>
				<td>'.$row->Telephone1.'</td>
				<td>'.$row->City.'</td>
				</tr>';
    }
    echo $html;
}
    
public function get_user_edit()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->get_selected_UserData($id);
    $firstname=trim($data->FirstName, " ");
    $lastname=trim($data->LastName, " ");
    $html ='<tr style="text-align: center;" readonly>
				<td ><input type="hidden" value="'.$data->ID.'" ID="userselectID" name="userselectID" ><input  type="checkbox" class="chkNumber" name="userid" value="'.$data->ID.'" checked disabled/> </td>
				<td>'.$firstname.' '.$lastname.'</td>
				<td>'.$data->Email.'</td>
				<td>'.$data->Telephone1.'</td>
				<td>'.$data->City.'</td>
				</tr>';
        
    echo $html;
}
    
public function get_invitee_documentData()
{
    $data['record']=$this->vessel_master_model->get_invitee_documentData();
    echo json_encode($data);
}
    
public function delete_invitee_document()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->delete_invitee_document($id);
    if($data) {
        echo 1;
    }else{
        echo 2;
    }
}
    
    
    
public function view_invitee_document()
{ 
    $this->load->model('cargo_quote_model', '', true); 
    $filename=$this->cargo_quote_model->download_invitee_document();
        
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
    $nar=explode("?", $url);
    $data=current($nar);
    $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
    echo $html;
}
    
    
    
public function documentDelete()
{
    $ids=$this->input->post('id');
    $deletenew=$this->vessel_master_model->deleteDocumentByIds($ids);
    if($deletenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function delete_invitee()
{
    $ids=$this->input->post('id');
    $deletenew=$this->vessel_master_model->deleteInviteeByIds($ids);
    if($deletenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function save_invitee()
{
    $inv_arr=$this->vessel_master_model->save_invitee_Details();
    $data1=$this->vessel_master_model->uploadImage_invitee($inv_arr);
    if(count($inv_arr) > 0) {
        echo 1;
    } else {
        echo 2;
    } 
}
    
public function update_invitee()
{
    $updatenew=$this->vessel_master_model->update_invitee();
    $data1=$this->vessel_master_model->uploadImage_invitee_1();
    if($updatenew) {
        echo 1;
    } else {
        echo 2;
    } 
}
    
    
public function all_vesselsize_data()
{
    $res=$this->vessel_master_model->all_vesselsize_data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='EntityName: '.$row->EntityName.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
    
    
    
    
public function getAttachedInviteeDocument()
{
    $data['doc_type']=$this->masters_model->getAttachedInviteeDocument();
    echo json_encode($data);
}
    
public function getLaycanStartDate()
{
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='alerts') {
            $this->load->model('alerts_model');
            $data=$this->alerts_model->getLaycanStartDate();                
                            
        }else{
            $data['error']='Invalid callback.';
        }
            
    }else{
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data));
}
    
public function getDisportStartDate()
{
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='alerts') {
            $this->load->model('alerts_model');
            $data=$this->alerts_model->getDisportStartDate();                
                            
        }else{
            $data['error']='Invalid callback.';
        }
            
    } else {
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data));
}
    
    
public function saveAlertsData()
{
    $this->output->set_output(json_encode($_FILES));
    $this->output->set_output(json_encode($_POST));
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='alerts') {
            $this->load->model('alerts_model');
            $this->load->model('vessel_model');
            $this->alerts_model->saveAlertsData();    
                
            $this->vessel_model->upload_image();    
            $data['success']='Record save successfully.';                
        } else {
            $data['error']='Invalid callback.';
        }
    }else{
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data)); 
}
    
public function get_all_user()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->getUserData($id);
    $html='';
    foreach($data as $row) {
        $html .=$row->FirstName.' '.$row->LastName.',';
    }
    echo $html; 
}
    
        
    
    
    
    
    
    
    
public function getVesselData()
{
    $type=$this->input->get('type');
    $this->load->model('vessel_model');
    $res=$this->vessel_model->getVesselData();
    $data_arr=array();
    $return_arr = array();
    if($type=='vessel') {
        foreach($res as $row){
            $data_arr['label']=$row->VesselName.' || '.$row->DWT.' || '.$row->Description;
            $data_arr['value']=$row->IMONumber;
            $data_arr['DWT']=$row->DWT;                
            $data_arr['Draught']=$row->Draught;                
            $data_arr['Displacement']=$row->Displacement;                
            $data_arr['Length']=$row->Length;                
            $data_arr['Breadth']=$row->Breadth;    
            array_push($return_arr, $data_arr);
        }
        foreach($res as $row){
            if($row->VesselExName) {
                  $data_arr['label']=$row->VesselExName.' || '.$row->DWT.' || '.$row->Description;
                  $data_arr['value']=$row->IMONumber;
                  $data_arr['DWT']=$row->DWT;                
                  $data_arr['Draught']=$row->Draught;                
                  $data_arr['Displacement']=$row->Displacement;                
                  $data_arr['Length']=$row->Length;                
                  $data_arr['Breadth']=$row->Breadth;    
                  array_push($return_arr, $data_arr);
            }
        }
    }else{
        foreach($res as $row){
            $data_arr['label']=$row->IMONumber;
            $data_arr['value']=$row->VesselName;                
            $data_arr['DWT']=$row->DWT;                
            $data_arr['Draught']=$row->Draught;                
            $data_arr['Displacement']=$row->Displacement;                
            $data_arr['Length']=$row->Length;                
            $data_arr['Breadth']=$row->Breadth;                
            array_push($return_arr, $data_arr);
        }
    }
        
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data_arr['label']='No Record found.';
        $data_arr['value']='0';
        array_push($return_arr, $data_arr);
        $this->output->set_output(json_encode($return_arr));
    }
}
    
public function checkDuplicateImo()
{
    $imono=$this->input->get('imono');
    if($imono) {    
        $this->load->model('vessel_model');
        $res=$this->vessel_model->checkDuplicateImo();    
        if($res->IMONumber) {
            $data['error']='IMO no. - '.$imono.' already added!';
        }else{
            $data['success']='ok';
        }    
                
    }else{
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
public function saveVesselData()
{
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='vessel') {
            $this->load->model('vessel_model');
            $res=$this->vessel_model->saveVesselData();    
                
            $this->vessel_model->upload_image();
            $data['success']='Data added successfully.';
        } else {
            $data['error']='Invalid callback.';
        }
            
    }else{
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data));   
}
    
public function getVesselDataByAuction()
{
    $this->load->model('vessel_model');
    $data=$this->vessel_model->getVesselDataByAuction();
    $this->output->set_output(json_encode($data));  
}
    
public function getVesselDataByResponse()
{
    $this->load->model('vessel_model');
    $data=$this->vessel_model->getVesselDataByResponse();
    $this->output->set_output(json_encode($data));  
}
    
    
public function updateVesselData()
{
    $this->load->model('Vessel_model');
    $oldData=$this->Vessel_model->getVessel();
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='vessel') {
            if($oldData) {
                $res=$this->Vessel_model->updateVesselData();
                $newData=$this->Vessel_model->getVessel();
                $this->Vessel_model->saveVessel($oldData, $newData);
                //$this->sendMail();
            }else{
                $res=$this->Vessel_model->saveVesselData();    
            }
            $this->Vessel_model->upload_image();
                
            $data['success']='Record updated.';
        } else {
            $data['error']='Invalid callback.';
        }
    } else {
        $data['error']='Invalid request.';
    } 
    $data['success']='Record updated.';
    $this->output->set_output(json_encode($data));
}
    
public function updateVesselData1()
{
    $this->load->model('cargo_quote_model', '', true);
    $this->load->model('Vessel_model');
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='vessel') {
            $olddata=$this->Vessel_model->getResponseVesselLatestOpen();
            $res=$this->Vessel_model->updateVesselData1();
            $this->cargo_quote_model->sendInprogressMessage();
            $newdata=$this->Vessel_model->getResponseVesselLatestOpen();
            $ret=$this->Vessel_model->updateResponseVesselContentChange($olddata, $newdata);
            $this->Vessel_model->upload_image1();
            $data['success']='Record updated.';
        } else {
            $data['error']='Invalid callback';
        }
    } else {
        $data['error']='Invalid request.';
    } 
    $data['success']='Record updated.';
    $this->output->set_output(json_encode($data));
}
    
public function getInvitees()
{
    $this->load->model('vessel_model');
    $oldData=$this->vessel_model->getInvitee();
    if($this->input->get()) {            
            
        $priority=$this->input->get('priority');
        if($priority!='P4') {
            $this->vessel_model->addInviteesData();    
            $newData=$this->vessel_model->getInvitee();
            $this->vessel_model->saveInvitee($oldData, $newData);
        }
        $data['details']=$this->vessel_model->getInvitees();
            
    } else {
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
public function getInvitees1()
{
    $this->load->model('vessel_model');
    $oldData=$this->vessel_model->getInvitee();
    if($this->input->get()) {
        $priority=$this->input->get('priority');
        if($priority!='P4') {
            $this->vessel_model->updateInviteesData();    
            $newData=$this->vessel_model->getInvitee();
            $this->vessel_model->saveInvitee($oldData, $newData);
        }            
        $data['details']=$this->vessel_model->getInvitees();        
    } else {
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
    
    
public function deleteInvitees()
{
    if($this->input->get()) {            
        $this->load->model('vessel_model');                    
        $this->vessel_model->deleteInvitees();    
        $data['success']='Data deleted successfully.';            
    }else{
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
public function saveInviteesData()
{
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='Invitees') {
            $this->load->model('vessel_model');
            $this->vessel_model->updateComments();
            $this->vessel_model->upload_image();
            $data['success']='Data added successfully.';
        } else {
            $data['error']='Invalid callback.';
        }
    } else {
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data)); 
}
    
    
public function updateInviteesData()
{
    $this->load->model('vessel_model');    
    $oldData=$this->vessel_model->getInvitee_1();
    if($this->input->post()) {
        $type=$this->input->post('type');
        if($type=='Invitees') {
            $this->vessel_model->updateComments();
            $newData=$this->vessel_model->getInvitee_1();
            $this->vessel_model->saveInvitee_1($oldData, $newData);
            $this->vessel_model->upload_image();
                
            $data['success']='Data updated successfully.';
        } else {
            $data['error']='Invalid callback.';
        }
    } else {
        $data['error']='Invalid request.';
    }
    $this->output->set_output(json_encode($data));  
}
    
    
public function getAlertsData()
{
    $this->load->model('alerts_model');        
    $data['details']=$this->alerts_model->getAlertsData();        
    $this->output->set_output(json_encode($data)); 
}
    
public function getAlertsDataByAuction()
{
    if($this->input->get()) {            
        $this->load->model('alerts_model');        
        $data=$this->alerts_model->getAlertsDataByAuction();        
    }else{
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
    
public function updateAlertsData()
{
    $this->load->model('alerts_model');    
    $this->load->model('Vessel_model');
    $oldData=$this->alerts_model->getAlert();
    if($this->input->post()) {
        if($oldData) {
            $this->alerts_model->updateAlertsData();
            $newData=$this->alerts_model->getAlert();
            $this->alerts_model->saveAlert($oldData, $newData);
        } else {
            $this->alerts_model->saveAlertsData();    
        }
        $this->Vessel_model->upload_image();
        $data['success']='Record updated';            
    } else {
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data));
}
    
    
public function deleteAlertsData()
{
    if($this->input->get()) {            
        $this->load->model('alerts_model');                    
        $this->alerts_model->deleteAlertsData();    
        $data['success']='Data deleted successfully.';            
    }else{
        $data['error']='Invaid callback!';
    }
    $this->output->set_output(json_encode($data)); 
}
    
    
    
public function checkInviteeEntityPrimeRole()
{
    $data['record']=$this->masters_model->checkInviteeEntityPrimeRole();
    $this->output->set_output(json_encode($data)); 
}
    
    

    
    
    
public function deleteInvitee()
{
    $this->load->model('vessel_model');                    
    $data=$this->vessel_model->deleteInvitee();
    echo $data;
}
    
public function entity_user_data()
{
    $res=$this->vessel_master_model->entity_user_data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='User Name : '.$row->FirstName.' '.$row->LastName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function entity_user_data_messages()
{
    $res=$this->vessel_master_model->entity_user_data_messages();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='User Name : '.$row->FirstName.' '.$row->LastName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function save_message_data()
{
    $data=$this->vessel_master_model->save_message_data();
    if($data) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function get_dtable_message_data()
{
    $data=$this->vessel_master_model->get_dtable_message_data();
    echo json_encode($data);
        
}
public function messageClone()
{
    $id=$this->input->post('id');
    $clonenew=$this->vessel_master_model->cloneMessageByIds($id);
    if($clonenew) {
        echo 1;
    } else {
        echo 2;
    }
}
public function delete_message()
{
    $ids=$this->input->post('id');
    $deletenew=$this->vessel_master_model->deleteMessageByIds($ids);
    if($deletenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function get_message()
{
    $data=$this->vessel_master_model->getMessage();
    echo json_encode($data);
}
public function update_message_data()
{
    $updatenew=$this->vessel_master_model->update_message_data();
    if($updatenew) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function check_msg_exist()
{
    $flag=$this->vessel_master_model->check_msg_exist();
    echo $flag;
}
    
public function checkUserMessageExist()
{
    $flag=$this->vessel_master_model->checkUserMessageExist();
    echo $flag;
}
    
public function checkInviteeMessageExist()
{
    $flag=$this->vessel_master_model->checkInviteeMessageExist();
    echo $flag;
}
    
public function cloneUserMessages()
{
    $flg=$this->vessel_master_model->cloneUserMessages();
    echo $flg; 
}
    
public function checkInviteeEntity()
{
    $flg=$this->vessel_master_model->checkInviteeEntity();
    echo $flg;
}
    
public function getOwnerInviteeEntity()
{
    $data=$this->masters_model->getOwnerInviteeEntity();
    $eid=array();
    $html='';
    $i=1;
    foreach($data as $row){
        if(!in_array($row->EntityID, $eid)) {
            $html .='<tr>';
            $html .='<td>'.($i++).'</td>';
            $html .='<td>'.$row->EntityName.'</td>';
            $html .='<td>'.$row->Email.'</td>';
            $html .='<td>'.$row->Telephone1.'</td>';
            $html .='</tr>';
            $eid[] = $row->EntityID;
        }
    }
    echo $html;
}
    
public function fill_message_data()
{
    $data=$this->vessel_master_model->messageById();
    echo json_encode($data);
        
}
public function get_user_login()
{
    $id=$this->input->post('id');
    $data=$this->vessel_master_model->get_selected_UserData($id);
    echo json_encode($data);
        
} 
    
public function get_dtable_vessel()
{
    $data=$this->vessel_master_model->get_dtable_vessel();
    echo json_encode($data);
        
}
    
    
public function get_dtable_document()
{
    $data=$this->vessel_master_model->get_dtable_document();
    echo json_encode($data);
        
}
    
public function get_dtable_invitee()
{
    $data=$this->vessel_master_model->get_dtable_invitee();
    echo json_encode($data);
        
}
    
public function getMessageData()
{
    $data=$this->vessel_master_model->getMessageData();
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row){
        $view='';
        $msgtype='';
        $event='';
        $page='';
        
        if($row->MessageType=='sys_msg') {
            $msgtype='System Message';
        }else if($row->MessageType=='alert_msg') {
            $msgtype='Alert Message';
        }else if($row->MessageType=='proc_msg') {
            $msgtype='Process Message';
        }else if($row->MessageType=='admin') {
            $msgtype='Admin';
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
            }else if($row->Events=='complete') {
                $event='Complete';
            }else if($row->Events=='status_change') {
                $event='Status Change';
            }
                
        } else if($row->MessageType=='proc_msg') {
            if($row->Events=='1') {
                $event='Bid commencement';
            }else if($row->Events=='2') {
                $event='Cargo Set Up (Quotes) (in progress)';
            }else if($row->Events=='3') {
                $event='Reminder';
            }else if($row->Events=='4') {
                $event='Cargo withdrawn in set up';
            }else if($row->Events=='5') {
                $event='Cargo withdrawn in main';
            }else if($row->Events=='6') {
                $event='Cargo decline by invitee';
            }else if($row->Events=='7') {
                $event='Cargo invitee short listed';
            }else if($row->Events=='8') {
                $event='Tentative bid acceptance';
            }else if($row->Events=='9') {
                $event='Cargo bid approval';
            }else if($row->Events=='10') {
                $event='Cargo Set Up (Quotes) (closed)';
            }else if($row->Events=='11') {
                $event='Fixture note completed';
            }else if($row->Events=='12') {
                $event='Charter documentation';
            }else if($row->Events=='13') {
                $event='Cargo Set Up (Quotes) (submitted)';
            } else if($row->Events=='14') {
                $event='Cargo Set Up (Quotes) (invitee comment)';
            }else if($row->Events=='15') {
                $event='Fixture note tentative';
            }else if($row->Events=='16') {
                $event='Fixture note final';
            }else if($row->Events=='17') {
                $event='Charter party final';
            }else if($row->Events=='18') {
                $event='Charter party tentative';
            }else if($row->Events=='19') {
                $event='Charter party complete';
            }else if($row->Events=='20') {
                $event='CP subject notification';
            }else if($row->Events=='21') {
                $event='CP subject lifted';
            }else if($row->Events=='22') {
                $event='C/P on subjects (Shipowner/Broker)';
            }else if($row->Events=='23') {
                $event='Cargo Set Up (Quotes) (TA)';
            }else if($row->Events=='24') {
                $event='Technical vetting approve';
            }else if($row->Events=='25') {
                $event='Counter party approve';
            }else if($row->Events=='26') {
                $event='Compliance risk approve';
            }else if($row->Events=='27') {
                $event='Business vetting approve';
            }else if($row->Events=='28') {
                $event='Sign fixture note';
            }else if($row->Events=='29') {
                $event='Sign charter party document';
            }else if($row->Events=='30') {
                $event='CP no subjects';
            }
        }else if($row->MessageType=='alert_msg') {
            if($row->Events=='commencement') {
                   $event='Commencement of bid';
            }else if($row->Events=='prior_commencement') {
                $event='Prior to commencement of bid';
            }else if($row->Events=='prior_closing') {
                $event='Prior to closing of bid';
            }else if($row->Events=='closing') {
                $event='Cargo closing';
            }else if($row->Events=='reminder') {
                $event='Reminder of Cargo';
            }
                
        }else if($row->MessageType=='admin') {
            if($row->Events=='unlock_user') {
                    $event='Unlock user';
            } else if($row->Events=='new_user_existing_entity') {
                $event='New User Existing Entity';
            }
        }
            
        if($row->OnPage=='page_1') {
            $page='Cargo Set Up';
        }else if($row->OnPage=='page_2') {
            $page='Charter Parties (+FN)';
        }else if($row->OnPage=='page_3') {
            $page='Cargo Set Up (Quotes)';
        }else if($row->OnPage=='page_4') {
            $page='Fixture Notes';
        }else if($row->OnPage=='page_5') {
            $page='Charter Documentation';
        }else if($row->OnPage=='login') {
            $page='Login Form';
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='vessel[]' value='".$row->MessageID."'>";
            $view="<a onclick='getMsg(".$row->MessageID.")'>view</a>";
            $edit="<a href='javascript: void(0);' onclick='editMessage(".$row->MessageID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='cloneMessage(".$row->MessageID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteMessage(".$row->MessageID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$event.'","'.$msgtype.'","'.$page.'","'.$row->FirstName.' '.$row->LastName.'","'.$view.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
    
public function getMessageAuctionDetailsData()
{
    $this->load->model('cargo_model', '', true); 
    $data=$this->cargo_model->getMessageAuctionDetailsData();
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row){
        $Statusflag='';
        if($row->StatusFlag=='1') {
            $Statusflag='Unread';
        }else if($row->StatusFlag=='0') {
            $Statusflag='Read';
        }
        $to_entityname=$this->cargo_model->getEntityById($row->ToEntityID);
        $ToEntityName=$to_entityname->EntityName;
        if($row->ToEntityID==$row->FromEntityID) {
            $FromEntityName='';
        } else {
            $from_entityname=$this->cargo_model->getEntityById($row->FromEntityID);
            $FromEntityName=$from_entityname->EntityName;
        }
            
            $msg="<a href='javascript: void(0);' onclick='getMsg(".$row->MessageDetailID.")'>".$Statusflag."</a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->Event.'","'.$row->Page.'","'.$row->Section.'","'.$row->subSection.'","'.$ToEntityName.'","'.$FromEntityName.'","'.$msg.'"],';
            $i++;
    }
         
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
    
}
    
public function all_entity_user_data()
{
    $res=$this->vessel_master_model->entity_user_data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='User Name : '.$row->FirstName.' '.$row->LastName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function get_security_section()
{
    $UserData=$this->vessel_master_model->getUserSection();
        
    $html='';
    $i=1;
    $allActivity='';
    $full_html='';
    $data=$this->vessel_master_model->get_security_section();
    $total=count($data);
        
    $addrightcount=0;        
    $editrightcount=0;        
    $deleterightcount=0;        
    $viewrightcount=0;
        
    $flag=1;
    $allActivityID="'";
    $allActivityName="";
    foreach($data as $row) {
        $flag=1;
        $name="'".$row->ActivityName."'";
        $id="'".$row->ActivityID."'";
        $allActivityID .="".$row->ActivityID."_";
        $allActivityName .="".$row->ActivityName."_";
        foreach($UserData as $userrow){
            if($row->ActivityName==$userrow->PageSection) {
                if($userrow->AddFlag=='1') {
                    $imgadd='<img src="img/right2.png" width="15px" id="RightAdd'.$row->ActivityID.'"  title="Do have add permission "></img><img src="img/cross1.png" width="15px" id="CancelAdd'.$row->ActivityID.'"  title="Do not have add permission " style="display: none"></img>';
                    $addrightcount++;
                }
                if($userrow->AddFlag=='0') {
                    $imgadd='<img src="img/right2.png" width="15px" id="RightAdd'.$row->ActivityID.'"  title="Do have add permission " style="display: none"><img src="img/cross1.png" id="CancelAdd'.$row->ActivityID.'" width="15px"  title="Do not have add permission "></img>';
                        
                }
                if($userrow->EditFlag=='1') {
                    $imgedit='<img src="img/right2.png" width="15px"  id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission "></img><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'" width="15px"  title="Do not have edit permission " style="display: none"></img>';
                    $editrightcount++;
                }else{
                    $imgedit='<img src="img/right2.png" width="15px"  id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission " style="display: none"><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'" width="15px"  title="Do not have edit permission "></img>';
                        
                }
                if($userrow->DeleteFlag=='1') {
                    $imgdelete='<img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission "></img><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'" width="15px"  title="Do not have delete permission " style="display: none"></img>';
                    $deleterightcount++;
                }else{
                            $imgdelete='<img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission " style="display: none"><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'" width="15px"  title="Do not have delete permission "></img>';
                }
                    
                if($userrow->ViewFlag=='1') {
                        $imgview='<img src="img/right2.png" width="15px"  id="RightView'.$row->ActivityID.'"  title="Do have read permission "></img><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have read permission " style="display: none"></img>';
                        $viewrightcount++;
                } else {
                    $imgview='<img src="img/right2.png" width="15px"  id="RightView'.$row->ActivityID.'"  title="Do have read permission " style="display: none"><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have read permission "></img>';
                }
                    $html .='<tr>
					<td>'.$i.'</td>
					<td>'.$row->ActivityName.'</td>
					<td style="text-align: center;"><a onclick="updateRoleAdd('.$name.','.$id.')">'.$imgadd.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleEdit('.$name.','.$id.')">'.$imgedit.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleDelete('.$name.','.$id.')">'.$imgdelete.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleView('.$name.','.$id.')">'.$imgview.'</a></td>
					
					</tr>';
                    $i++;
                    $flag=0;
            }
        }
        if($flag) {
            $html .='<tr>
				<td>'.$i.'</td>
				<td>'.$row->ActivityName.'</td>
				<td style="text-align: center;"><a onclick="updateRoleAdd('.$name.','.$id.')"><img src="img/right2.png"width="15px"  id="RightAdd'.$row->ActivityID.'"  title="Do have add permission " style="display: none"></img><img src="img/cross1.png" id="CancelAdd'.$row->ActivityID.'"  title="Do not have add permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleEdit('.$name.','.$id.')"><img src="img/right2.png" width="15px" id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission " style="display: none"></img><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'"  title="Do not have edit permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleDelete('.$name.','.$id.')"><img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission " style="display: none"></img><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'"  title="Do not have delete permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleView('.$name.','.$id.')"><img src="img/right2.png" width="15px" id="RightView'.$row->ActivityID.'"  title="Do have read permission " style="display: none"></img><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have read permission "></img></a></td>
				
				</tr>';
            $i++;
        }
            
    }
    $allActivityID .="'";
    $allActivityName .="";
    if($total==$addrightcount) {
        $alladd='<a onclick="allUpdateRoleAdd('.$allActivityID.',1)"><input type="hidden" ID="allRoleName" value="'.$allActivityName.'"><img src="img/cross1.png" width="20px" id="AllCancelAdd"  title="Do not have add permission " style="display: none" ></img></a><a onclick="allUpdateRoleAdd('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightAdd" width="20px" title="Do have add permission "  ></img></a>';
    } else {
        $alladd='<a onclick="allUpdateRoleAdd('.$allActivityID.',1)"><input type="hidden" ID="allRoleName" value="'.$allActivityName.'"><img src="img/cross1.png" width="20px"  id="AllCancelAdd"  title="Do not have add permission " ></img></a><a onclick="allUpdateRoleAdd('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightAdd" width="20px"  title="Do have add permission "  style="display: none" ></img></a>';
    }
    if($total==$editrightcount) {
        $alledit='<a onclick="allUpdateRoleEdit('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelEdit" width="20px"  title="Do not have edit permission " style="display: none" ></img></a><a onclick="allUpdateRoleEdit('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightEdit" title="Do have edit permission " width="20px"  ></img></a>';
    } else {
        $alledit='<a onclick="allUpdateRoleEdit('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelEdit" title="Do not have edit permission " width="20px" ></img></a><a onclick="allUpdateRoleEdit('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightEdit"  title="Do have edit permission " width="20px" style="display: none" ></img></a>';
    }
    if($total==$deleterightcount) {
        $alldelete='<a onclick="allUpdateRoleDelete('.$allActivityID.',1)"><img src="img/cross1.png" id="AllCancelDelete"  title="Do not have delete permission " style="display: none" width="20px" ></img></a><a onclick="allUpdateRoleDelete('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightDelete"  title="Do have delete permission " width="20px"  ></img></a>';
    } else {
        $alldelete='<a onclick="allUpdateRoleDelete('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelDelete"  title="Do not have delete permission " width="20px" ></img></a><a onclick="allUpdateRoleDelete('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightDelete"  title="Do have delete permission " width="20px" style="display: none" ></img></a>';
    }
    if($total==$viewrightcount) {
        $allview='<a onclick="allUpdateRoleView('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelView" width="20px" title="Do not have read permission " style="display: none" ></img></a><a onclick="allUpdateRoleView('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightView"  title="Do have read permission " width="20px" ></img></a>';
    } else {
        $allview='<a onclick="allUpdateRoleView('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelView"  title="Do not have read permission " width="20px" ></img></a><a onclick="allUpdateRoleView('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightView"  title="Do have read permission " width="20px" style="display: none" ></img></a>';
    }
        
    $full_html .='<tr>
				<td>-</td>
				<td>Select All</td>
				<td style="text-align: center;">'.$alladd.'</td>
				<td style="text-align: center;">'.$alledit.'</td>
				<td style="text-align: center;">'.$alldelete.'</td>
				<td style="text-align: center;">'.$allview.'</td>
				</tr>';
    $full_html .=$html;
    echo $full_html;
}
    
public function save_user_add_role()
{
    $flag=$this->vessel_master_model->save_user_add_role();
    echo $flag;
}
    
public function save_user_edit_role()
{
    $flag=$this->vessel_master_model->save_user_edit_role();
    echo $flag;
    
}
    
public function save_user_delete_role()
{
    $flag=$this->vessel_master_model->save_user_delete_role();
    echo $flag;
    
}
    
public function save_user_clone_role()
{
    $flag=$this->vessel_master_model->save_user_clone_role();
    echo $flag;
    
}
    
public function save_user_view_role()
{
    $flag=$this->vessel_master_model->save_user_view_role();
    echo $flag;
    
}
public function save_user_search_role()
{
    $flag=$this->vessel_master_model->save_user_search_role();
    echo $flag;
    
}
    
    
public function save_user_all_add_role()
{
    $flag=$this->vessel_master_model->save_user_all_add_role();
    echo $flag;
    
}
    
public function save_user_all_edit_role()
{
    $flag=$this->vessel_master_model->save_user_all_edit_role();
    echo $flag;
    
}
    
public function save_user_all_delete_role()
{
    $flag=$this->vessel_master_model->save_user_all_delete_role();
    echo $flag;
    
}
    
public function save_user_all_view_role()
{
    $flag=$this->vessel_master_model->save_user_all_view_role();
    echo $flag;
    
}
    
public function save_user_all_search_role()
{
    $flag=$this->vessel_master_model->save_user_all_search_role();
    echo $flag;
    
}
    
public function get_entity_users()
{
    $data=$this->vessel_master_model->get_entity_users();
    $html='';
    $i=1;
    foreach($data as $row){
        $html .='<tr><td>'.$i.'</td><td>'.$row->FirstName.'</td><td>'.$row->LastName.'</td><td>'.$row->LoginID.'</td></tr>';
        $i++;
    }
    echo $html;
}
    
public function clone_user_security()
{
    $ret=$this->vessel_master_model->clone_user_security();
    echo $ret;
}
    
public function getSecurityData()
{
    $data=$this->vessel_master_model->getSecurityData();
    //print_r($data); die;
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row) {
        if($row->UserID==0) {
            continue;
        }
        if($row->PageSectionFlag==1) {
            $PageSectionFlag='Page & Section';
        }else{
            $PageSectionFlag='Page';
        }
            $user_row=$this->vessel_master_model->getSecurityDataByUser($row->UserID, $row->PageSectionFlag);
            
            //$check="<input class='chkNumber' type='checkbox' name='user[]' value='".$row->UserID."_".$row->PageSectionFlag."'>";
            $edit="<a href='javascript: void(0);' onclick=editSecurity('".$row->UserID."_".$row->PageSectionFlag."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick=cloneSecurity('".$row->UserID."_".$row->PageSectionFlag."') title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $view="<a onclick='viewSecurity(".$row->UserID.",".$row->PageSectionFlag.")' value='".$row->UserID."_".$row->PageSectionFlag."'>View</a>";            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($user_row->CreatedDate)).'","'.$row->EntityName.'","'.$row->FirstName.' '.$row->LastName.'","'.$PageSectionFlag.'","'.$view.'","'.$user_row->FirstName.' '.$user_row->LastName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
    
}
    
    
public function get_security_page()
{
    $UserData=$this->vessel_master_model->getUserSection();
    //print_r($UserData); die;
    $data=$this->vessel_master_model->get_security_page();
    $total=count($data);
    $i=1;
    $html='';
    $flag=1;
    $addrightcount=0;
    $editrightcount=0;
    $deleterightcount=0;
    $viewrightcount=0;
    $searchrightcount=0;
    $allActivityID="'";
    $allActivityName="";
    $full_html='';
    foreach($data as $row) {
        $flag=1;
        $name="'".$row->ActivityName."'";
        $id="'".$row->ActivityID."'";
        $allActivityID .="".$row->ActivityID."_";
        $allActivityName .="".$row->ActivityName."_";
        foreach($UserData as $userrow){
            if($row->ActivityName==$userrow->PageSection) {
                if($userrow->AddFlag=='1') {
                    $imgadd='<img src="img/right2.png" width="15px" id="RightAdd'.$row->ActivityID.'"  title="Do have add permission "></img><img src="img/cross1.png" width="15px" id="CancelAdd'.$row->ActivityID.'"  title="Do not have add permission " style="display: none"></img>';
                    $addrightcount++;
                }
                if($userrow->AddFlag=='0') {
                    $imgadd='<img src="img/right2.png" width="15px" id="RightAdd'.$row->ActivityID.'"  title="Do have add permission " style="display: none"><img src="img/cross1.png" id="CancelAdd'.$row->ActivityID.'" width="15px"  title="Do not have add permission "></img>';
                        
                }
                if($userrow->EditFlag=='1') {
                    $imgedit='<img src="img/right2.png" width="15px"  id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission "></img><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'" width="15px"  title="Do not have edit permission " style="display: none"></img>';
                    $editrightcount++;
                }else{
                    $imgedit='<img src="img/right2.png" width="15px"  id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission " style="display: none"><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'" width="15px"  title="Do not have edit permission "></img>';
                        
                }
                if($userrow->DeleteFlag=='1') {
                    $imgdelete='<img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission "></img><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'" width="15px"  title="Do not have delete permission " style="display: none"></img>';
                    $deleterightcount++;
                }else{
                            $imgdelete='<img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission " style="display: none"><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'" width="15px"  title="Do not have delete permission "></img>';
                        
                }
                    
                if($userrow->ViewFlag=='1') {
                        $imgview='<img src="img/right2.png" width="15px"  id="RightView'.$row->ActivityID.'"  title="Do have read permission "></img><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have read permission " style="display: none"></img>';
                        $viewrightcount++;
                }else{
                    $imgview='<img src="img/right2.png" width="15px"  id="RightView'.$row->ActivityID.'"  title="Do have delete permission " style="display: none"><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have delete permission "></img>';
                    
                }
                if($userrow->SearchFlag=='1') {
                    $imgsearch='<img src="img/right2.png" width="15px"  id="RightSearch'.$row->ActivityID.'"  title="Do have advance search permission "></img><img src="img/cross1.png" id="CancelSearch'.$row->ActivityID.'" width="15px"  title="Do not have advance search permission " style="display: none"></img>';
                    $searchrightcount++;
                }else{
                    $imgsearch='<img src="img/right2.png" width="15px"  id="RightSearch'.$row->ActivityID.'"  title="Do have advance search permission " style="display: none"><img src="img/cross1.png" id="CancelSearch'.$row->ActivityID.'" width="15px"  title="Do not have advance search permission "></img>';
                    
                }
                    
                    $html .='<tr>
					<td>'.$i.'</td>
					<td>'.$row->ActivityName.'</td>
					<td style="text-align: center;"><a onclick="updateRoleAdd('.$name.','.$id.')">'.$imgadd.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleEdit('.$name.','.$id.')">'.$imgedit.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleDelete('.$name.','.$id.')">'.$imgdelete.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleView('.$name.','.$id.')">'.$imgview.'</a></td>
					<td style="text-align: center;"><a onclick="updateRoleSearch('.$name.','.$id.')">'.$imgsearch.'</a></td>
					
					</tr>';
                    $i++;
                    $flag=0;
            }
        }
            
        if($flag==1) {
            $html .='<tr>
				<td>'.$i.'</td>
				<td>'.$row->ActivityName.'</td>
				<td style="text-align: center;"><a onclick="updateRoleAdd('.$name.','.$id.')"><img src="img/right2.png"width="15px"  id="RightAdd'.$row->ActivityID.'"  title="Do have add permission " style="display: none"></img><img src="img/cross1.png" id="CancelAdd'.$row->ActivityID.'"  title="Do not have add permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleEdit('.$name.','.$id.')"><img src="img/right2.png" width="15px" id="RightEdit'.$row->ActivityID.'"  title="Do have edit permission " style="display: none"></img><img src="img/cross1.png" id="CancelEdit'.$row->ActivityID.'"  title="Do not have edit permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleDelete('.$name.','.$id.')"><img src="img/right2.png" width="15px"  id="RightDelete'.$row->ActivityID.'"  title="Do have delete permission " style="display: none"></img><img src="img/cross1.png" id="CancelDelete'.$row->ActivityID.'"  title="Do not have delete permission " width="15px"  ></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleView('.$name.','.$id.')"><img src="img/right2.png" width="15px" id="RightView'.$row->ActivityID.'"  title="Do have read permission " style="display: none"></img><img src="img/cross1.png" id="CancelView'.$row->ActivityID.'" width="15px"  title="Do not have read permission "></img></a></td>
				<td style="text-align: center;"><a onclick="updateRoleSearch('.$name.','.$id.')"><img src="img/right2.png" width="15px" id="RightSearch'.$row->ActivityID.'"  title="Do have advance search permission " style="display: none"></img><img src="img/cross1.png" id="CancelSearch'.$row->ActivityID.'" width="15px"  title="Do not have advance search permission "></img></a></td>
				</tr>';
            $i++;    
        }
    }
    $allActivityID .="'";
    $allActivityName .="";
    //print_r($addrightcount); die;
    if($total==$addrightcount) {
        $alladd='<a onclick="allUpdateRoleAdd('.$allActivityID.',1)"><input type="hidden" ID="allRoleName" value="'.$allActivityName.'"><img src="img/cross1.png" width="20px" id="AllCancelAdd"  title="Do not have add permission " style="display: none" ></img></a><a onclick="allUpdateRoleAdd('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightAdd" width="20px" title="Do have add permission "  ></img></a>';
    } else {
        $alladd='<a onclick="allUpdateRoleAdd('.$allActivityID.',1)"><input type="hidden" ID="allRoleName" value="'.$allActivityName.'"><img src="img/cross1.png" width="20px"  id="AllCancelAdd"  title="Do not have add permission " ></img></a><a onclick="allUpdateRoleAdd('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightAdd" width="20px"  title="Do have add permission "  style="display: none" ></img></a>';
    }
    if($total==$editrightcount) {
        $alledit='<a onclick="allUpdateRoleEdit('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelEdit" width="20px"  title="Do not have edit permission " style="display: none" ></img></a><a onclick="allUpdateRoleEdit('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightEdit"  title="Do have edit permission " width="20px"  ></img></a>';
    }else{
        $alledit='<a onclick="allUpdateRoleEdit('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelEdit"  title="Do not have edit permission " width="20px" ></img></a><a onclick="allUpdateRoleEdit('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightEdit"  title="Do have edit permission " width="20px" style="display: none" ></img></a>';
    }
    if($total==$deleterightcount) {
        $alldelete='<a onclick="allUpdateRoleDelete('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelDelete"  title="Do not have delete permission " style="display: none" width="20px" ></img></a><a onclick="allUpdateRoleDelete('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightDelete"  title="Do have delete permission " width="20px"  ></img></a>';
    }else{
        $alldelete='<a onclick="allUpdateRoleDelete('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelDelete"  title="Do not have delete permission " width="20px" ></img></a><a onclick="allUpdateRoleDelete('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightDelete"  title="Do have delete permission " width="20px" style="display: none" ></img></a>';
    }
    if($total==$viewrightcount) {
        $allview='<a onclick="allUpdateRoleView('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelView" width="20px" title="Do not have read permission " style="display: none" ></img></a><a onclick="allUpdateRoleView('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightView"  title="Do have read permission " width="20px" ></img></a>';
    }else{
        $allview='<a onclick="allUpdateRoleView('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelView"  title="Do not have read permission " width="20px" ></img></a><a onclick="allUpdateRoleView('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightView"  title="Do have read permission " width="20px" style="display: none" ></img></a>';
    }
    if($total==$searchrightcount) {
        $allsearch='<a onclick="allUpdateRoleSearch('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelSearch" width="20px" title="Do not have search permission " style="display: none" ></img></a><a onclick="allUpdateRoleSearch('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightSearch"  title="Do have search permission " width="20px" ></img></a>';
    }else{
        $allsearch='<a onclick="allUpdateRoleSearch('.$allActivityID.',1)"><img src="img/cross1.png"  id="AllCancelSearch"  title="Do not have search permission " width="20px" ></img></a><a onclick="allUpdateRoleSearch('.$allActivityID.',0)"><img src="img/right2.png" id="AllRightSearch"  title="Do have search permission " width="20px" style="display: none" ></img></a>';
    }
        
    $full_html .='<tr>
				<td>-</td>
				<td>Select All</td>
				<td style="text-align: center;">'.$alladd.'</td>
				<td style="text-align: center;">'.$alledit.'</td>
				<td style="text-align: center;">'.$alldelete.'</td>
				<td style="text-align: center;">'.$allview.'</td>
				<td style="text-align: center;">'.$allsearch.'</td>
				</tr>';
    $full_html .=$html;
    echo $full_html;
}
    
public function get_user_entity()
{
    $data=$this->vessel_master_model->get_user_entity();
    echo json_encode($data);
}
    
public function get_user_security()
{
    $security=$this->input->post('security');
    $data=$this->vessel_master_model->get_user_security();
    //print_r($data); die;
    $html='';
    $addflag='';
    $editflag='';
    $deleteflag='';
    $viewflag='';
    $i=1;
    if($security==1) {
        //print_r('1'); die;
        foreach($data as $row){
            if($row->AddFlag) {
                $addflag='<img src="img/right2.png" width="15px" title="Do have add permission "  ></img>';
            }else{
                $addflag='<img src="img/cross1.png"  width="15px" title="Do not have add permission "  ></img>';
            }
            if($row->EditFlag) {
                $editflag='<img src="img/right2.png" width="15px" title="Do have edit permission "></img>';
            }else{
                $editflag='<img src="img/cross1.png"  width="15px" title="Do not have edit permission "  ></img>';
            }
            if($row->DeleteFlag) {
                $deleteflag='<img src="img/right2.png" width="15px" title="Do have delete permission "></img>';
            }else{
                $deleteflag='<img src="img/cross1.png" width="15px" title="Do not have delete permission "  ></img>';
            }
            if($row->ViewFlag) {
                $viewflag='<img src="img/right2.png" width="15px" title="Do have read permission "></img>';
            }else{
                $viewflag='<img src="img/cross1.png" width="15px" title="Do not have read permission "  ></img>';
            }
                $html .='<tr>
							<td>'.$i.'</td>
							<td>'.$row->PageSection.'</td>
							<td style="text-align: center;">'.$addflag.'</td>
							<td style="text-align: center;">'.$editflag.'</td>
							<td style="text-align: center;">'.$deleteflag.'</td>
							<td style="text-align: center;">'.$viewflag.'</td>
						</tr>';
                        $i++;
        }
    } else if($security==0) {
        foreach($data as $row){
            if($row->AddFlag) {
                $addflag='<img src="img/right2.png" width="15px" title="Do have add permission "  ></img>';
            }else{
                $addflag='<img src="img/cross1.png"  width="15px" title="Do have add permission "  ></img>';
            }
            if($row->EditFlag) {
                $editflag='<img src="img/right2.png" width="15px" title="Do have edit permission "></img>';
            }else{
                $editflag='<img src="img/cross1.png"  width="15px" title="Do have edit permission "  ></img>';
            }
            if($row->DeleteFlag) {
                $deleteflag='<img src="img/right2.png" width="15px" title="Do have delete permission "></img>';
            }else{
                $deleteflag='<img src="img/cross1.png" width="15px" title="Do have delete permission "  ></img>';
            }
            if($row->ViewFlag) {
                $viewflag='<img src="img/right2.png" width="15px" title="Do have read permission "></img>';
            }else{
                $viewflag='<img src="img/cross1.png" width="15px" title="Do not have read permission "  ></img>';
            }
            if($row->SearchFlag) {
                $searchflag='<img src="img/right2.png" width="15px" title="Do have advance search permission "></img>';
            }else{
                $searchflag='<img src="img/cross1.png" width="15px" title="Do not have advance search permission "  ></img>';
            }
                $html .='<tr>
							<td>'.$i.'</td>
							<td>'.$row->PageSection.'</td>
							<td style="text-align: center;">'.$addflag.'</td>
							<td style="text-align: center;">'.$editflag.'</td>
							<td style="text-align: center;">'.$deleteflag.'</td>
							<td style="text-align: center;">'.$viewflag.'</td>
							<td style="text-align: center;">'.$searchflag.'</td>
						</tr>';
                        $i++;
        }
    }
        
    echo $html;
}
    
public function get_auction_page_secutity($userid)
{
        
    $data=$this->vessel_master_model->get_auction_page_secutity($userid);
    $totalPermission='';
    if($data) {
        foreach($data as $row){
            switch ($row->PageSection) {
            case "Dashboard":
                if($row->AddFlag==1) { $totalPermission .='DA,';
                }
                if($row->EditFlag==1) { $totalPermission .='DE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='DD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='DV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='DS,';
                }
                    
                break;
            case "Dashboard | Cargo Setup":    
                if($row->AddFlag==1) { $totalPermission .='DCSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='DCSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='DCSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='DCSV,';
                }
                    
                break;
            case "Dashboard | Vessel Tracker":    
                if($row->AddFlag==1) { $totalPermission .='DVTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='DVTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='DVTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='DVTV,';
                }
                    
                break;
            case "Dashboard | Voyage Cal":
                if($row->AddFlag==1) { $totalPermission .='DVCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='DVCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='DVCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='DVCV,';
                }
            
                break;
            case "Cargo Set Up":
                if($row->AddFlag==1) { $totalPermission .='ASA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ASS,';
                }
                    
                break;
            case "Role Selection":
                if($row->AddFlag==1) { $totalPermission .='ASRSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASRSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASRSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASRSV,';
                }
                    
                break;
            case "Charter Details >> Charter Details":
                if($row->AddFlag==1) { $totalPermission .='ASCDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCDV,';
                }
                    
                break;
            case "Charter Details >> Business Process":
                if($row->AddFlag==1) { $totalPermission .='ASBPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASBPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASBPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASBPV,';
                }
                    
                break;
            case "Charter Details >> Bank Details":
                if($row->AddFlag==1) { $totalPermission .='ASCBDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCBDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCBDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCBDV,';
                }
                    
                break;
            case "Cargo":
                if($row->AddFlag==1) { $totalPermission .='ASCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCV,';
                }
                    
                break;
            case "Cargo >> Cargo":
                if($row->AddFlag==1) { $totalPermission .='ASCCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCCV,';
                }
                    
                break;
            case "Cargo >> LoadPort":
                if($row->AddFlag==1) { $totalPermission .='ASCLA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCLE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCLD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCLV,';
                }
                    
                break;
            case "Cargo >> DisPort":
                if($row->AddFlag==1) { $totalPermission .='ASCDPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCDPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCDPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCDPV,';
                }
                    
                break;
            case "Cargo >> BAC":
                if($row->AddFlag==1) { $totalPermission .='ASCBACA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCBACE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCBACD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCBACV,';
                }
                    
                break;
            case "Cargo >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASCMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCMV,';
                }
                    
                break;
            case "Cargo >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASCAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASCAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCAV,';
                }
                    
                break;
            case "Freight Quote":
                if($row->AddFlag==1) { $totalPermission .='ASFQA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQV,';
                }
                    
                break;
            case "Freight Quote >> Freight":
                if($row->AddFlag==1) { $totalPermission .='ASFQFA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQFE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQFD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQFV,';
                }
                    
                break;
            case "Freight Quote >> Differential":
                if($row->AddFlag==1) { $totalPermission .='ASFQDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQDV,';
                }
                    
                break;
            case "Freight Quote >> Demurrage - Despatch":
                if($row->AddFlag==1) { $totalPermission .='ASFQDDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQDDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQDDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQDDV,';
                }
                    
                break;
            case "Freight Quote >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASFQMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQMV,';
                }
                    
                break;
            case "Freight Quote >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASFQAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFQAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFQAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFQAV,';
                }
                    
                break;
            case "Freight Estimate":
                if($row->AddFlag==1) { $totalPermission .='ASFEA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEV,';
                }
                    
                break;
            case "Freight Estimate":
                if($row->AddFlag==1) { $totalPermission .='ASFEA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEV,';
                }
                    
                break;
            case "Freight Estimate >> Freight Estimate":
                if($row->AddFlag==1) { $totalPermission .='ASFEFEA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEFEE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFEFED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEFEV,';
                }
                    
                break;
            case "Freight Estimate >> Freight Index":
                if($row->AddFlag==1) { $totalPermission .='ASFEFIA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEFIE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFEFID,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEFIV,';
                }
                    
                break;
            case "Freight Estimate >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASFEMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFEMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEMV,';
                }
                    
                break;
            case "Freight Estimate >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASFEAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASFEAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASFEAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASFEAV,';
                }
                    
                break;
            case "Performing Vessel":
                if($row->AddFlag==1) { $totalPermission .='ASPVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVV,';
                }
                    
                break;
            case "Performing Vessel >> Vessel Selection":
                if($row->AddFlag==1) { $totalPermission .='ASPVVSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVVSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVVSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVVSV,';
                }
                    
                break;
            case "Performing Vessel >> Ship (disponent) owner":
                if($row->AddFlag==1) { $totalPermission .='ASPVSDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVSDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVSDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVSDV,';
                }
                    
                break;
            case "Performing Vessel >> Vessel Particular":
                if($row->AddFlag==1) { $totalPermission .='ASPVVPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVVPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVVPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVVPV,';
                }
                    
                break;
            case "Performing Vessel >> Vessel Risk":
                if($row->AddFlag==1) { $totalPermission .='ASPVVRA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVVRE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVVRD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVVRV,';
                }
                    
                break;
            case "Performing Vessel >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASPVMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVMV,';
                }
                    
                break;
            case "Performing Vessel >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASPVAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASPVAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASPVAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASPVAV,';
                }
                    
                break;
            case "Vessel Traded History":
                if($row->AddFlag==1) { $totalPermission .='ASTHA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASTHE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASTHD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASTHV,';
                }
                    
                break;
            case "Select Invitees":
                if($row->AddFlag==1) { $totalPermission .='ASSIA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASSIE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSID,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASSIV,';
                }
                    
                break;
            case "Select Invitees >> Invitees":
                if($row->AddFlag==1) { $totalPermission .='ASSIIA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASSIIE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSIID,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASSIIV,';
                }
                    
                break;
            case "Select Invitees >> Invitee Business Process":
                if($row->AddFlag==1) { $totalPermission .='ASSIIBPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASSIIBPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSIIBPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASSIIBPV,';
                }
                    
                break;
            case "Select Invitees >> Bank Details":
                if($row->AddFlag==1) { $totalPermission .='ASSIBDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASSIBDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSIBDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASSIBDV,';
                }
                    
                break;
            case "Select Invitees >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASSIMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASCIME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSIMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASCIMV,';
                }
                    
                break;
            case "Select Invitees >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASSIAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASSIAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASSIAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASSIAV,';
                }
                    
                break;
            case "Setup Alerts":
                if($row->AddFlag==1) { $totalPermission .='ASAAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASAAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASAAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASAAV,';
                }
                    
                break;
            case "Setup Alerts >> Commencement":
                if($row->AddFlag==1) { $totalPermission .='ASAACA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASAACE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASAACD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASAACV,';
                }
                    
                break;
            case "Setup Alerts >> Alerts":
                if($row->AddFlag==1) { $totalPermission .='ASAATA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASAATE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASAATD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASAATV,';
                }

                break;
            case "Setup Alerts >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ASAAMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASAAME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASAAMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASAAMV,';
                }
                    
                break;
            case "Setup Alerts >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ASAAAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASAAAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASAAAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASAAAV,';
                }
                    
                break;
            case "Approve Setup":
                if($row->AddFlag==1) { $totalPermission .='ASASA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASASE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASASD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASASV,';
                }
                    
                break;
            case "Status":
                if($row->AddFlag==1) { $totalPermission .='ASASTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ASASTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ASASTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ASASTV,';
                }
                    
                break;
            case "Cargo Set Up (Quotes)":
                if($row->AddFlag==1) { $totalPermission .='ARA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ARS,';
                }
                    
                break;
            case "Response | Bid Status":
                if($row->AddFlag==1) { $totalPermission .='ARBSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARBSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARBSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARBSV,';
                }
                    
                break;
            case "Response | Instructions":
                if($row->AddFlag==1) { $totalPermission .='ARIA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARIE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARID,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARIV,';
                }
                    
                break;
            case "Response | Charter Details":
                if($row->AddFlag==1) { $totalPermission .='ARCDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDV,';
                }
                    
                break;
            case "Response | Charter Details >> Cargo":
                if($row->AddFlag==1) { $totalPermission .='ARCDCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDCV,';
                }
                    
                break;
            case "Response | Charter Details >> LoadPort":
                if($row->AddFlag==1) { $totalPermission .='ARCDLA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDLE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDLD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDLV,';
                }

                break;
            case "Response | Charter Details >> DisPort":
                if($row->AddFlag==1) { $totalPermission .='ARCDDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDDV,';
                }
                    
                break;
            case "Response | Charter Details >> BAC":
                if($row->AddFlag==1) { $totalPermission .='ARCDBA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDBE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDBD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDBV,';
                }
                    
                break;
            case "Response | Charter Details >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ARCDMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDMV,';
                }

                break;
            case "Response | Charter Details >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ARCDAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCDAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCDAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCDAV,';
                }

                break;
            case "Response | Performing Vessel":
                if($row->AddFlag==1) { $totalPermission .='ARPVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVV,';
                }
                    
                break;
            case "Response | Performing Vessel >> Vessel Selection":
                if($row->AddFlag==1) { $totalPermission .='ARPVVSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVVSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVVSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVVSV,';
                }

                break;
            case "Response | Performing Vessel >> Ship (disponent) owner":
                if($row->AddFlag==1) { $totalPermission .='ARPVSDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVSDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVSDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVSDV,';
                }

                break;
            case "Response | Performing Vessel >> Signatories":
                if($row->AddFlag==1) { $totalPermission .='ARPVSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVSV,';
                }
                    
                break;
            case "Response | Performing Vessel >> Vessel Particulars":
                if($row->AddFlag==1) { $totalPermission .='ARPVVPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVVPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVVPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVVPV,';
                }
                    
                break;
            case "Response | Performing Vessel >> Vessel Risk":
                if($row->AddFlag==1) { $totalPermission .='ARPVVRA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVVRE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVVRD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVVRV,';
                }

                break;
            case "Response | Performing Vessel >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ARPVMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVMV,';
                }

                break;
            case "Response | Performing Vessel >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ARPVAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARPVAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARPVAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARPVAV,';
                }

                break;
            case "Response | Vessel Traded History":
                if($row->AddFlag==1) { $totalPermission .='ARVTHA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARVTHE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARVTHD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARVTHV,';
                }
                    
                break;
            case "Response | Freight Quote":
                if($row->AddFlag==1) { $totalPermission .='ARFQA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQV,';
                }
                    
                break;
            case "Response | Freight Quote >> Freight":
                if($row->AddFlag==1) { $totalPermission .='ARFQFA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQFE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQFD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQFV,';
                }
                    
                break;
            case "Response | Freight Quote >> Differential":
                if($row->AddFlag==1) { $totalPermission .='ARFQDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQDV,';
                }

                break;
            case "Response | Freight Quote >> Demmurrage - Despatch":
                if($row->AddFlag==1) { $totalPermission .='ARDQDDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQDDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQDDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQDDV,';
                }
    
                break;
            case "Response | Freight Quote >> Comments":
                if($row->AddFlag==1) { $totalPermission .='ARFQMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQMV,';
                }
                    
                break;
            case "Response | Freight Quote >> Attachments":
                if($row->AddFlag==1) { $totalPermission .='ARFQAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARFQAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARFQAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARFQAV,';
                }

                break;
            case "Response | Business Process":
                if($row->AddFlag==1) { $totalPermission .='ARBPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARBPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARBPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARBPV,';
                }
                    
                break;
            case "Response | Business Process >> Authorization for quotes":
                if($row->AddFlag==1) { $totalPermission .='ARBPAQA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARBPAQE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARBPAQD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARBPAQV,';
                }
                    
                break;
            case "Response | Invitee Comments":
                if($row->AddFlag==1) { $totalPermission .='ARICA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARICE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARICD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARICV,';
                }
                    
                break;
            case "Response | Invitee Comments >> Vessel":
                if($row->AddFlag==1) { $totalPermission .='ARICVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARICVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARICVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARICVV,';
                }
                    
                break;
            case "Response | Invitee Comments >> Freight":
                if($row->AddFlag==1) { $totalPermission .='ARICFA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARICFE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARICFD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARICFV,';
                }

                break;
            case "Response | Invitee Comments >> Cargo n Ports":
                if($row->AddFlag==1) { $totalPermission .='ARICCPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARICCPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARICCPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARICCPV,';
                }

                break;
            case "Response | Invitee Comments >> Terms":
                if($row->AddFlag==1) { $totalPermission .='ARICTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARICTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARICTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARICTV,';
                }
                    
                break;
                    
            case "Response | Confirmations":
                if($row->AddFlag==1) { $totalPermission .='ARCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARCV,';
                }
                break;
                    
            case "Response | Status":
                if($row->AddFlag==1) { $totalPermission .='ARASA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ARASE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ARASD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ARASV,';
                }
                break;
                    
            case "Charter Party (FN)":
                if($row->AddFlag==1) { $totalPermission .='AMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AMS,';
                }
                break;
                    
            case 'Charter Parties | Business Process':
                if($row->AddFlag==1) { $totalPermission .='AMBPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPV,';
                }
                break;
                
            case 'Charter Parties | Business Process >> Technical Vetting':
                if($row->AddFlag==1) { $totalPermission .='AMBPTVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPTVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPTVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPTVV,';
                }
                break;
                    
            case 'Charter Parties | Business Process >> Business Vetting Approval':
                if($row->AddFlag==1) { $totalPermission .='AMBPBVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPBVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPBVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPBVV,';
                }
                break;
                    
            case 'Charter Parties | Business Process >> Counter Party Risk Assessment':
                if($row->AddFlag==1) { $totalPermission .='AMBPCPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPCPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPCPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPCPV,';
                }
                break;
                    
            case 'Charter Parties | Business Process >> C/P on Subjects (Charterer)':
                if($row->AddFlag==1) { $totalPermission .='AMBPSCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPSCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPSCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPSCV,';
                }
                break;
                    
            case 'Charter Parties | Business Process >> Compliance risk assessment':
                if($row->AddFlag==1) { $totalPermission .='AMBPCRA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMBPCRE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMBPCRD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMBPCRV,';
                }
                break;
                    
            case 'Charter Parties | Invitee Business Process':
                if($row->AddFlag==1) { $totalPermission .='AMIBPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMIBPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMIBPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMIBPV,';
                }
                break;
                    
            case 'Charter Parties | Invitee Business Process >> C/P on Subjects (ShipOwner/Broker)':
                if($row->AddFlag==1) { $totalPermission .='AMIBPSBA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMIBPSBE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMIBPSBD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMIBPSBV,';
                }
                break;
                    
            case 'Charter Parties | Fixture Note':
                if($row->AddFlag==1) { $totalPermission .='AMFNA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMFNE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMFND,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMFNV,';
                }
                break;
                    
            case 'Charter Parties | Charter Party':
                if($row->AddFlag==1) { $totalPermission .='AMDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMDV,';
                }
                break;
                    
            case 'Charter Parties | Signed Documentation':
                if($row->AddFlag==1) { $totalPermission .='AMSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMSV,';
                }
                break;
                    
            case 'Charter Parties | Verify':
                if($row->AddFlag==1) { $totalPermission .='AMVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AMVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMVV,';
                }
                break;
                    
            case 'Services >> Messages':
                if($row->AddFlag==1) { $totalPermission .='MA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='MD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='MV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='MS,';
                }
                break;
                    
            case 'Notifications':
                if($row->AddFlag==1) { $totalPermission .='NA,';
                }
                if($row->EditFlag==1) { $totalPermission .='NE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ND,';
                }
                if($row->ViewFlag==1) { $totalPermission .='NV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='NS,';
                }
                break;
                    
            case 'System Chat':
                if($row->AddFlag==1) { $totalPermission .='SCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='SCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='SCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='SCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='SCS,';
                }
                break;
                    
            case 'General Chat':
                if($row->AddFlag==1) { $totalPermission .='GCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='GCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='GCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='GCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='GCS,';
                }
                break;
                    
            case 'Services >> Email (FN And C/P)':
                if($row->AddFlag==1) {  $totalPermission .='EMLA,';
                }  
                if($row->EditFlag==1) {  $totalPermission .='EMLE,';
                }  
                if($row->DeleteFlag==1) {  $totalPermission .='EMLD,';
                }  
                if($row->ViewFlag==1) {  $totalPermission .='EMLV,';
                } 
                if($row->SearchFlag==1) {  $totalPermission .='EMLS,';
                } 
                break;
                    
            case 'Services >> Import CSV':
                if($row->AddFlag==1) { $totalPermission .='ICSVA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ICSVE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ICSVD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ICSVV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ICSVS,';
                }
                break;
                    
            case 'Services >> Cargo Set Up (Audit)':
                if($row->AddFlag==1) { $totalPermission .='ACSUA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ACSUE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ACSUD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ACSUV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ACSUS,';
                }
                break;
                    
            case 'Services >> Terms and Conditions (Audit)':
                if($row->AddFlag==1) { $totalPermission .='ATNCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ATNCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ATNCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ATNCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ATNCS,';
                }
                break;
                    
            case 'Admin >> Vessel Grouping Frt Diff':
                if($row->AddFlag==1) { $totalPermission .='AVGA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AVGE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVGD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AVGV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AVGS,';
                }
                break;
                    
            case 'Admin >> Document Type':
                if($row->AddFlag==1) { $totalPermission .='ADTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ADTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ADTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ADTV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ADTS,';
                } 
                break;
                    
            case 'Admin >> Chat Users List':
                if($row->AddFlag==1) { $totalPermission .='ACULA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ACULE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ACULD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ACULV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ACULS,';
                } 
                break;
                    
            case 'Admin >> Document Store':
                if($row->AddFlag==1) { $totalPermission .='ADSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ADSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ADSD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ADSV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ADSS,';
                } 
                break;
                    
            case 'Admin >> Notification':
                if($row->AddFlag==1) { $totalPermission .='ANA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='ANE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AND,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='ANV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ANS,';
                } 
                break;
                    
            case 'Admin >> Invitee Master':
                if($row->AddFlag==1) { $totalPermission .='AIMA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AIME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AIMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AIMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AIMS,';
                }
                break;
                    
            case 'Admin >> Message Master':
                if($row->AddFlag==1) { $totalPermission .='AMMA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AMME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AMMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AMMV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AMMS,';
                }
                break;
                    
            case 'Admin >> Rating Setup':
                if($row->AddFlag==1) { $totalPermission .='ARSA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='ARSE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='ARSD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='ARSV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ARSS,';
                }
                break;
                    
            case 'Admin >> Fix note template':
                if($row->AddFlag==1) { $totalPermission .='AFNTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AFNTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AFNTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AFNTV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AFNTS,';
                }
                break;
                    
            case 'Admin >> Version Control':
                if($row->AddFlag==1) { $totalPermission .='AVCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AVCS,';
                }
                break;
                    
            case 'Admin >> Terms and Conditions':
                if($row->AddFlag==1) { $totalPermission .='ATACA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ATACE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ATACD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ATACV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ATACS,';
                }
                break;
                    
            case 'Admin >> Business Process':
                if($row->AddFlag==1) { $totalPermission .='ABPA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ABPE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ABPD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ABPV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ABPS,';
                } 
                break;
                    
            case 'Admin >> Business Process Rules':
                if($row->AddFlag==1) { $totalPermission .='ABPRA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='ABPRE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ABPRD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='ABPRV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='ABPRS,';
                }
                break;
                    
            case 'Admin >> Master Data >> Associated Entity':
                if($row->AddFlag==1) { $totalPermission .='AMDAEA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AMDAEE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AMDAED,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AMDAEV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AMDAES,';
                } 
                break;
                    
            case 'Admin >> Master Data >> Parent Entity':
                if($row->AddFlag==1) { $totalPermission .='AMDPEA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AMDPEE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AMDPED,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AMDPEV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AMDPES,';
                } 
                break;
                    
            case 'Admin >> Master Data >> Entity Users':
                if($row->AddFlag==1) { $totalPermission .='AMDEUA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AMDEUE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AMDEUD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AMDEUV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AMDEUS,';
                } 
                break;
                    
            case 'Admin >> Master Data >> New Users':
                if($row->AddFlag==1) { $totalPermission .='AMDNUA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AMDNUE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AMDNUD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AMDNUV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AMDNUS,';
                } 
                break;
                    
            case 'Admin >> Broker Signing Authority':
                if($row->AddFlag==1) { $totalPermission .='ABSAA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='ABSAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ABSAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='ABSAV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ABSAS,';
                } 
                break;
                    
            case 'Admin >> Help Text':
                if($row->AddFlag==1) { $totalPermission .='AHTA,';
                } 
                if($row->EditFlag==1) { $totalPermission .='AHTE,';
                } 
                if($row->DeleteFlag==1) { $totalPermission .='AHTD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='AHTV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='AHTS,';
                } 
                break;
                    
            case 'Admin >> Custom Templates':
                if($row->AddFlag==1) { $totalPermission .='ACTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='ACTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='ACTD,';
                } 
                if($row->ViewFlag==1) { $totalPermission .='ACTV,';
                } 
                if($row->SearchFlag==1) { $totalPermission .='ACTS,';
                }
                break;
                    
            case 'Security Setup':
                if($row->AddFlag==1) { $totalPermission .='SSA,';
                }
                if($row->EditFlag==1) { $totalPermission .='SSE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='EED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='SSV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='SSS,';
                }
                break;
                    
            case 'Admin(G) >> Banking Detail':
                if($row->AddFlag==1) { $totalPermission .='AGBDA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGBDE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGBDD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGBDV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGBDS,';
                }
                break;
                    
            case 'Admin(G) >> Country Master':
                if($row->AddFlag==1) { $totalPermission .='AGCTMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGCTME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGCTMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGCTMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGCTMS,';
                }
                break;
                    
            case 'Admin(G) >> Currency Master':
                if($row->AddFlag==1) { $totalPermission .='AGCRMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGCRME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGCRMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGCRMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGCRMS,';
                }
                break;
                    
            case 'Admin(G) >> Port Master':
                if($row->AddFlag==1) { $totalPermission .='AGPMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGPME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGPMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGPMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGPMS,';
                }
                break;
                    
            case 'Admin(G) >> Secret Question':
                if($row->AddFlag==1) { $totalPermission .='AGSQA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGSQE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGSQD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGSQV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGSQS,';
                }
                break;
                    
            case 'Admin(G) >> State Master':
                if($row->AddFlag==1) { $totalPermission .='AGSMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGSME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGSMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGSMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGSMS,';
                }
                break;
                    
            case 'Admin(G) >> Terminals Master':
                if($row->AddFlag==1) { $totalPermission .='AGTLMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGTLME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGTLMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGTLMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGTLMS,';
                }
                break;
                    
            case 'Admin(G) >> Time-Zone Master':
                if($row->AddFlag==1) { $totalPermission .='AGTZMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGTZME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGTZMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGTZMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGTZMS,';
                }
                break;
                    
            case 'Admin(G) >> Title Master':
                if($row->AddFlag==1) { $totalPermission .='AGTMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGTME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGTMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGTMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGTMS,';
                }
                break;
                    
            case 'Admin(G) >> Vessel Type':
                if($row->AddFlag==1) { $totalPermission .='AGVTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGVTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGVTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGVTV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGVTS,';
                }
                break;
                    
            case 'Admin(G) >> Unit Of Measurement':
                if($row->AddFlag==1) { $totalPermission .='AGUOMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGUOME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGUOMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGUOMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGUOMS,';
                }
                break;
                    
            case 'Admin(G) >> Excepted Period Events':
                if($row->AddFlag==1) { $totalPermission .='AGEPEA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AGEPEE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AGEPED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AGEPEV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AGEPES,';
                }
                break;
                    
            case 'Admin(VCP) >> Business Area':
                if($row->AddFlag==1) { $totalPermission .='AVCPBAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPBAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPBAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPBAV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPBAS,';
                }
                break;
                    
            case 'Admin(VCP) >> Cargo Servicing Basis':
                if($row->AddFlag==1) { $totalPermission .='AVCPCSBA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPCSBE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPCSBD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPCSBV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPCSBS,';
                }
                break;
                    
            case 'Admin(VCP) >> Charter Party Form':
                if($row->AddFlag==1) { $totalPermission .='AVCPCPFA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPCPFE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPCPFD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPCPFV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPCPFS,';
                }
                break;
                    
            case 'Admin(VCP) >> Free(turn) Time Cond':
                if($row->AddFlag==1) { $totalPermission .='AVCPFTCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPFTCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPFTCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPFTCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPFTCS,';
                }
                break;
                    
            case 'Admin(VCP) >> Freight Payment Invoice':
                if($row->AddFlag==1) { $totalPermission .='AVCPFPIA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPFPIE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPFPID,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPFPIV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPFPIS,';
                }
                break;
                    
            case 'Admin(VCP) >> Freight Payment Event':
                if($row->AddFlag==1) { $totalPermission .='AVCPFPEA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPFPEE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPFPED,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPFPEV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPFPES,';
                }
                break;
                
            case "Admin(VCP) >> L/D Rate Measure":
                if($row->AddFlag==1) { $totalPermission .='AVCPLRMA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLRME,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLRMD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLRMV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLRMS,';
                }
                break;    
                    
            case "Admin(VCP) >> L/Discharge Terms":
                if($row->AddFlag==1) { $totalPermission .='AVCPLDTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLDTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLDTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLDTV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLDTS,';
                }
                break;    
                    
            case "Admin(VCP) >> L/D Loading Rate Cond":
                if($row->AddFlag==1) { $totalPermission .='AVCPLRCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLRCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLRCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLRCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLRCS,';
                }
                break;    
                    
            case "Admin(VCP) >> Laytime Ceases Cond":
                if($row->AddFlag==1) { $totalPermission .='AVCPLCCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLCCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLCCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLCCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLCCS,';
                }
                break;    
                    
            case "Admin(VCP) >> Laytime Comm Cond":
                if($row->AddFlag==1) { $totalPermission .='AVCPLTCCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLTCCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLTCCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLTCCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLTCCS,';
                }
                break;    
                    
            case "Admin(VCP) >> NOR Accept PreCond":
                if($row->AddFlag==1) { $totalPermission .='AVCPNAPCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPNAPCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPNAPCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPNAPCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPNAPCS,';
                }
                break;    
                    
            case "Admin(VCP) >> NOR Tender PreCond":
                if($row->AddFlag==1) { $totalPermission .='AVCPNTPCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPNTPCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPNTPCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPNTPCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPNTPCS,';
                }
                break;    
                    
            case "Admin(VCP) >> NOR Tendering":
                if($row->AddFlag==1) { $totalPermission .='AVCPNTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPNTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPNTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPNTV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPNTS,';
                }
                break;    
                    
            case "Admin(VCP) >> Stevedoring Terms":
                if($row->AddFlag==1) { $totalPermission .='AVCPSDTA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPSDTE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPSDTD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPSDTV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPSDTS,';
                }
                break;    
                    
            case "Admin(VCP) >> Trade Area":
                if($row->AddFlag==1) { $totalPermission .='AVCPTAA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPTAE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPTAD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPTAV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPTAS,';
                }
                break;    
                    
            case "Admin(VCP) >> Laytime Counting":
                if($row->AddFlag==1) { $totalPermission .='AVCPLCA,';
                }
                if($row->EditFlag==1) { $totalPermission .='AVCPLCE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='AVCPLCD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='AVCPLCV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='AVCPLCS,';
                }
                break;    
                    
            case "Sales/COA >> BAF Platts Regions":
                if($row->AddFlag==1) { $totalPermission .='COABAFA,';
                }
                if($row->EditFlag==1) { $totalPermission .='COABAFE,';
                }
                if($row->DeleteFlag==1) { $totalPermission .='COABAFD,';
                }
                if($row->ViewFlag==1) { $totalPermission .='COABAFV,';
                }
                if($row->SearchFlag==1) { $totalPermission .='COABAFS,';
                }
                break;    
                    
            }
            
        }
    
        $permission=trim($totalPermission, ',');
        return $permission;
    } else {
        return 0;
    }
}
    
public function userLogin()
{
    extract($this->input->post());
    $this->masters_model->sendReminderMessages();
    $data['record']=$this->masters_model->userLogin();
    //print_r($data); die;
    if($data) {
        $ip=$this->getUserIP();
        $this->masters_model->LoginLog($ip, $data['record']->UID);
        $this->masters_model->checkResponseClose($data['record']->EID);
        $data['cargo_quote']=$this->masters_model->get_cargo_quote_form_layout($data['record']->EID);
        if($data['record']->UserType != 'A') {
            $data['security']=$this->get_auction_page_secutity($data['record']->UID);
        }
        echo json_encode($data);
    } else {
        echo 0;
    }
}
    
public function getUserName()
{
    $data=$this->vessel_master_model->getUserName();
    echo json_encode($data);
}
    
public function get_entity_name()
{
    $data=$this->vessel_master_model->get_entity_name();
    echo json_encode($data);
}
    
public function login()
{
    $userID=$this->input->post('userID');
    $passowrd=$this->input->post('passowrd');
    if($userID=="admin" && $passowrd=='12345') {
        echo 'admin'; die;
    }
    $data=$this->masters_model->verifyLogin();
    if(count($data)>0) {
        echo json_encode($data);
        //echo $data->EntityID;
    } else {
        echo '0';
    }
}
    
    
    
    
    
public function modelSubmit()
{
    $data=$this->masters_model->modelSubmit();
    echo $data;
}
    
public function getModel()
{
    $data=$this->masters_model->getModel();
    $html='';
    $inhtml='';
    $html='{ "aaData": [';
        
    $i=1;
    foreach($data as $row) {
        $mdlf='';
        if($row->ModelStatus==1) {
            $sts='Active';
        }
        if($row->ModelStatus==0) {
            $sts='Inactive';
        }
        if($row->ModelFunction==1) {
            $mdlf='Default (all charters)';
        }
        if($row->ModelFunction==2) {
            $mdlf='User selected (individual charters)';
        }
            
            $edit="<a href='javascript: void(0);' onclick='editModel(".$row->mid.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='clone_model(".$row->mid.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteModel(".$row->mid.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->add_date)).'","'.$row->ModelNumber.'","'.$mdlf.'","'.$sts.'","'.$row->Description.'","'.$row->EntityName.'","'.$row->FirstName.' '.$row->LastName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getModelMyId()
{
    $data=$this->masters_model->getModelMyId();
    echo json_encode($data);
}
    
public function modelUpdate()
{
    $data=$this->masters_model->modelUpdate();
    echo $data;
}
    
public function deleteModelById()
{
    $data=$this->masters_model->deleteModelById();
    echo $data;
}
    
    
public function getOpenClosedData()
{
        
    $this->load->model('vessel_model');
    $data=$this->vessel_model->getOpenClosedData();
    echo json_encode($data);
}
    
    
public function getOpenGraphData()
{
        
    $this->load->model('vessel_model');
    $data=$this->vessel_model->getOpenGraphData();
    echo json_encode($data);
}

public function getModelSetupByRecoredOwner()
{
    $this->load->model('cargo_quote_model', '', true); 
    $RecordOwner=$this->input->post('RecordOwner');
    $AuctionID=$this->input->post('AuctionID');
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    $quote=$this->cargo_quote_model->getQuoteByAuctionID($AuctionID);
    $c=count($quote);
    //echo $c;die;
    $low=$quote[0]->FreightRate;
    $high=$quote[$c-1]->FreightRate;
    $range=$high-$low;
    if($range==0) {
        $ratio=0;
    } else {
        $ratio=$low/$range;
    }
    echo $ratio;
}
    
public function uploadCsv()
{
    //open uploaded csv file with read only mode
    if($_FILES['csvfile']['tmp_name']) {
         $csvFile = fopen($_FILES['csvfile']['tmp_name'], 'r');
         //skip first line
         fgetcsv($csvFile);
        $r=0;
        while(($line = fgetcsv($csvFile)) !== false){
            $AuctionID=$this->get__Random__Id('clone');
            $data=array(
            'CoCode'=>$line[0],
            'AuctionID'=>$AuctionID,
            'ActiveFlag_A'=>$line[1],
            'StatusFlag'=>$line[2],
            'OwnerEntityID'=>$line[3],
            'auctionStatus'=>'P',
            'AuctionersRole'=>$line[4],
            'SelectFrom_A'=>$line[5],
            'ContractType'=>$line[6],
            'COAReference'=>$line[7],
            'SalesAgreementReference'=>$line[8],
            'ShipmentReferenceId'=>$line[9],
            'LineNum_C'=>$line[10],
            'ActiveFlag_C'=>$line[11],
            'SelectFrom_C'=>$line[12],
            'CargoQtyMT'=>$line[13],
            'CargoLoadedBasis'=>$line[14],
            'CargoLimitBasis'=>$line[15],
            'ToleranceLimit'=>$line[16],
            'UpperLimit'=>$line[17],
            'LowerLimit'=>$line[18],
            'LoadPort'=>$line[19],
            'LpLaycanStartDate'=>date('Y-m-d', strtotime($line[20])),
            'LpLaycanEndDate'=>date('Y-m-d', strtotime($line[21])),
            'LpPreferDate'=>date('Y-m-d', strtotime($line[22])),
            'LoadingTerms'=>$line[23],
            'LoadingRateMT'=>$line[24],
            'LoadingRateUOM'=>$line[25],
            'LpLaytimeType'=>$line[26],
            'LpCalculationBasedOn'=>$line[27],
            'LpTurnTime'=>$line[28],
            'LpPriorUseTerms'=>$line[29],
            'MaxCargoMT'=>(int)$line[30],
            'MinCargoMT'=>(int)$line[31],
            'LpMaxTime'=>(int)$line[32],
            'DischargingRateMT'=>(int)$line[33],
            'DpMaxTime'=>(int)$line[34],
            'LpLaytimeBasedOn'=>$line[35],
            'LpCharterType'=>$line[36],
            'LpNorTendering'=>$line[37],
            'DisPort'=>$line[38],
            'DpArrivalStartDate'=>$line[39],
            'DpArrivalEndDate'=>$line[40],
            'DpPreferDate'=>$line[41],
            'DischargingTerms'=>$line[42],
            'DischargingRateUOM'=>$line[43],
            'DpLaytimeType'=>$line[44],
            'DpCalculationBasedOn'=>$line[45],
            'DpLaytimeBasedOn'=>$line[46],
            'DpCharterType'=>$line[47],
            'DpNorTendering'=>$line[48],
            'CargoInternalComments'=>$line[49],
            'CargoDisplayComments'=>$line[50],
            'Freight_Estimate'=>$line[51],
            'Estimate_By'=>$line[52],
            'Estimate_mt'=>$line[53],
            'Estimate_from'=>$line[54],
            'Estimate_to'=>$line[55],
            'Freight_Index'=>$line[56],
            'Estimate_Index_By'=>$line[57],
            'Estimate_Index_mt'=>$line[58],
            'Estimate_Index_from'=>$line[59],
            'Estimate_Index_to'=>$line[60],
            'estimate_comment'=>$line[61],
            'LineNum_D'=>$line[62],
            'DifferentialVesselSizeGroup'=>$line[63],
            'DifferentialLoadport'=>$line[64],
            'CommenceAlertFlag'=>$line[65],
            'AuctionCommences'=>$line[66],
            'OnlyDisplay'=>$line[67],
            'CommenceDaysBefore'=>$line[68],
            'CommenceDate'=>$line[69],
            'AuctionCommenceDefinedDate'=>$line[70],
            'AuctionValidity'=>$line[71],
            'AuctionCeases'=>$line[72],
            'AlertBeforeCommence'=>$line[73],
            'AlertBeforeClosing'=>$line[74],
            'AlertNotificationCommence'=>$line[75],
            'AlertNotificationClosing'=>$line[76],
            'IncludeClosing'=>$line[77],
            'AuctionerComments'=>$line[78],
            'InviteesComments'=>$line[79],
            'BACFlag'=>0,
            'DpTurnTime'=>$line[80],
            'DpPriorUseTerms'=>$line[81],
            'LpStevedoringTerms'=>$line[82],
            'DpStevedoringTerms'=>$line[83],
            'add_date'=>date('Y-m-d H:i:s'),
            'ImportedStatus'=>0
            );
            $r=$this->masters_model->importCSV($data);
        }
        echo $r; 
    } else {
        echo 2;
    }
}
    
public function getCsvImportData()
{
    $data=$this->masters_model->getCsvImportData(); 
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        if($row->ImportedStatus==0) {
            $ImportedStatus='Not exported';
        } else {
            $ImportedStatus='Exported';
        }
            
        $ckbx="<input type='checkbox' name='csvid[]' class='chkNumber' value='".$row->CSV_ID."' style='margin-bottom: 6px;' >";
        $inhtml .='["'.$ckbx.'","'.date('d-m-Y', strtotime($row->add_date)).'","'.$ImportedStatus.'","Pending","'.$row->AuctionID.'","'.$row->PortName.'","'.date('d-m-Y', strtotime($row->LpLaycanStartDate)).'","'.date('d-m-Y', strtotime($row->LpLaycanEndDate)).'","'.$row->Estimate_mt.'","'.$row->Estimate_Index_mt.'"],';
        $i++;
    
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html; 
}
    
public function sendAuctionSetup()
{
    $data=$this->masters_model->sendAuctionSetup(); 
    $csvid=trim($this->input->post('csvid'), '_');
    $ids=explode('_', $csvid);
    $i=0;
    if(in_array("1", $data['au'])) {
        $i++;
    } 
    if(in_array("1", $data['ca'])) {
        $i++;
    }
    if(in_array("1", $data['di'])) {
        $i++;
    }
    if(in_array("1", $data['al'])) {
        $i++;
    }
    if($i==4) {
        $this->masters_model->changeStatusCsvData($ids); 
        echo 'Record(s) exported successfully';
    } else {
        echo 'The following record with Master ID ........ has been exported. Delete record in cargo set up to re-export record(s).';
    }
}
    
function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP)) {
        $ip = $client;
    } else if(filter_var($forward, FILTER_VALIDATE_IP)) {
        $ip = $forward;
    } else {
        $ip = $remote;
    }
    return $ip;
}
    
public function getAuditData()
{
    $data=$this->masters_model->getAuditData(); 
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        $UpdatedDate=$this->masters_model->getUpdatedDate($row->AuctionID); 
        $status=$this->masters_model->getStatus($row->AuctionID); 
            
        if($row->RecordStatus==2) {
            $RecordStatus='Manually';
        } else {
            $RecordStatus='Imported';
        }
        if($status=="P") {
            $AuctionStatus="Pending";
        } else if($status=="C") {
            $AuctionStatus="Complete";
        } else if($status=="PNR") {
                $AuctionStatus="Pending Release";
        } else if($status=="A") {
            $AuctionStatus="Activated";
        } else if($status=="W") {
            $AuctionStatus="Withdrawn";
        }
        
            $form_view="<a href='javascript: void(0);' onclick=showAuditLog('".$row->AuctionID."') title='Click here to Audit View'><i class='fa fa-share-square fa_form_view'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->AuctionID.'","'.date('d-m-Y H:i:s', strtotime($UpdatedDate)).'","'.$row->FirstName.' '.$row->LastName.'","'.$row->EntityName.'","'.$RecordStatus.'","'.$AuctionStatus.'","'.$form_view.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html; 
                
}
public function getAuditLogData()
{
    $Auction=$this->masters_model->getAuction();
    $Cargo=$this->masters_model->getCargo();
    $Differential=$this->masters_model->getDifferential();
    $FreightEstimate=$this->masters_model->getFreightEstimateLog();
    $Invitees=$this->masters_model->getInvitees();
    $Alerts=$this->masters_model->getAlerts();
    $bus_pro=$this->masters_model->getBusinessProcess_h();
    $bac_brokerage=$this->masters_model->getBacBrokerage_h();
    $bac_addCom=$this->masters_model->getBacAddCom_h();
    $bac_others=$this->masters_model->getOthers_h();
    //print_r($bac_brokerage);die;
        
    $Invitees1=array();
    $archk=array();
    for($ii=0;$ii<count($Invitees);$ii++) {
        if(!in_array($Invitees[$ii]->RowCounter, $archk)) {
            $Invitees1[]=$Invitees[$ii];
            $archk[]=$Invitees[$ii]->RowCounter;
        }
            
    }
    $EntityName=$Auction[0]->EntityName;
    $RowStatus='';
    $h=0;
    foreach($Auction as $a) {
        if($a->RowStatus==1) {
            $RowStatus='Add';
        }
        if($a->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($a->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($a->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($a->RowStatus==2) {
            if($Auction[$h-1]->AuctionersRole==$Auction[$h]->AuctionersRole) {
                //--do nothing---
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Role Selection</td>';
                $html .='<td>-</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
            $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Role Selection</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
            
        if($a->RowStatus==2) {
            if($Auction[$h-1]->SelectFrom==$Auction[$h]->SelectFrom && $Auction[$h-1]->ContractType==$Auction[$h]->ContractType && $Auction[$h-1]->COAReference==$Auction[$h]->COAReference && $Auction[$h-1]->SalesAgreementReference==$Auction[$h]->SalesAgreementReference && $Auction[$h-1]->auctionStatus==$Auction[$h]->auctionStatus && $Auction[$h-1]->auctionExtendedStatus==$Auction[$h]->auctionExtendedStatus && $Auction[$h-1]->ShipmentReferenceID==$Auction[$h]->ShipmentReferenceID && $Auction[$h-1]->ModelFunction==$Auction[$h]->ModelFunction && $Auction[$h-1]->ModelNumber==$Auction[$h]->ModelNumber) {
                //--do nothing---
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
        } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
        }
            
        $h++;
    }
    $RowStatus='';
    $bus=0;
    foreach($bus_pro as $bp) {
            
        if($bp->RowStatus==1) {
            $RowStatus='Add';
        }
        if($bp->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($bp->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($bp->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($bp->RowStatus==2) {
            if($bus_pro[$bus-1]->UserList==$bus_pro[$bus]->UserList && $bus_pro[$bus-1]->Status==$bus_pro[$bus]->Status) {
                //--do nothing---
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bp->UserDate)).'</td>';
                $html .='<td>'.$bp->FirstName.' '.$bp->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Business Process</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($bp->UserDate)).'</td>';
            $html .='<td>'.$bp->FirstName.' '.$bp->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Charter Details</td>';
            $html .'<td>Business Process</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $bus++;
    }
        
        
    $RowStatus='';
    $i=0;
    foreach($Cargo as $c) {
        if($c->RowStatus==1) {
            $RowStatus='Add';
        }
        if($c->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($c->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($c->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($c->RowStatus==2) {
            if($Cargo[$i-1]->SelectFrom==$Cargo[$i]->SelectFrom && $Cargo[$i-1]->CargoQtyMT==$Cargo[$i]->CargoQtyMT && $Cargo[$i-1]->CargoLoadedBasis==$Cargo[$i]->CargoLoadedBasis && $Cargo[$i-1]->CargoLimitBasis==$Cargo[$i]->CargoLimitBasis && $Cargo[$i-1]->MinCargoMT==$Cargo[$i]->MinCargoMT && $Cargo[$i-1]->MaxCargoMT==$Cargo[$i]->MaxCargoMT && $Cargo[$i-1]->ToleranceLimit==$Cargo[$i]->ToleranceLimit && $Cargo[$i-1]->LowerLimit==$Cargo[$i]->LowerLimit && $Cargo[$i-1]->UpperLimit==$Cargo[$i]->UpperLimit) { 
                //---do nothing------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Port</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LoadPort==$Cargo[$i]->LoadPort && $Cargo[$i-1]->LpLaycanStartDate==$Cargo[$i]->LpLaycanStartDate && $Cargo[$i-1]->LpLaycanEndDate==$Cargo[$i]->LpLaycanEndDate && $Cargo[$i-1]->LpPreferDate==$Cargo[$i]->LpPreferDate && $Cargo[$i-1]->LoadingTerms==$Cargo[$i]->LoadingTerms && $Cargo[$i-1]->LoadingRateMT==$Cargo[$i]->LoadingRateMT && $Cargo[$i-1]->LoadingRateUOM==$Cargo[$i]->LoadingRateUOM && $Cargo[$i-1]->LpMaxTime==$Cargo[$i]->LpMaxTime && $Cargo[$i-1]->LpLaytimeType==$Cargo[$i]->LpLaytimeType && $Cargo[$i-1]->LpCalculationBasedOn==$Cargo[$i]->LpCalculationBasedOn && $Cargo[$i-1]->LpTurnTime==$Cargo[$i]->LpTurnTime && $Cargo[$i-1]->LpPriorUseTerms==$Cargo[$i]->LpPriorUseTerms && $Cargo[$i-1]->LpLaytimeBasedOn==$Cargo[$i]->LpLaytimeBasedOn && $Cargo[$i-1]->LpCharterType==$Cargo[$i]->LpCharterType && $Cargo[$i-1]->LpNorTendering==$Cargo[$i]->LpNorTendering && $Cargo[$i-1]->ExpectedLpDelayDay==$Cargo[$i]->ExpectedLpDelayDay && $Cargo[$i-1]->ExpectedLpDelayHour==$Cargo[$i]->ExpectedLpDelayHour && $Cargo[$i-1]->LpStevedoringTerms==$Cargo[$i]->LpStevedoringTerms) {
                //-----do nothing-------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Port</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DisPort==$Cargo[$i]->DisPort && $Cargo[$i-1]->DpArrivalStartDate==$Cargo[$i]->DpArrivalStartDate && $Cargo[$i-1]->DpArrivalEndDate==$Cargo[$i]->DpArrivalEndDate && $Cargo[$i-1]->DpPreferDate==$Cargo[$i]->DpPreferDate && $Cargo[$i-1]->DischargingTerms==$Cargo[$i]->DischargingTerms && $Cargo[$i-1]->DischargingRateMT==$Cargo[$i]->DischargingRateMT && $Cargo[$i-1]->DischargingRateUOM==$Cargo[$i]->DischargingRateUOM && $Cargo[$i-1]->DpMaxTime==$Cargo[$i]->DpMaxTime && $Cargo[$i-1]->DpLaytimeType==$Cargo[$i]->DpLaytimeType && $Cargo[$i-1]->DpCalculationBasedOn==$Cargo[$i]->DpCalculationBasedOn && $Cargo[$i-1]->DpLaytimeBasedOn==$Cargo[$i]->DpLaytimeBasedOn && $Cargo[$i-1]->DpCharterType==$Cargo[$i]->DpCharterType && $Cargo[$i-1]->DpNorTendering==$Cargo[$i]->DpNorTendering && $Cargo[$i-1]->ExpectedDpDelayDay==$Cargo[$i]->ExpectedDpDelayDay && $Cargo[$i-1]->ExpectedDpDelayHour==$Cargo[$i]->ExpectedDpDelayHour && $Cargo[$i-1]->DpStevedoringTerms==$Cargo[$i]->DpStevedoringTerms) {
                //----do nothing-----------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Port</td>';
                $html .='<td>DisPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->CargoInternalComments==$Cargo[$i]->CargoInternalComments && $Cargo[$i-1]->CargoDisplayComments==$Cargo[$i]->CargoDisplayComments) {
                //------do nothing---------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Port</td>';
                $html .='<td>Comment</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
            $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Cargo & Port</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $i++;
    }
        
    $bbk=0;
    $RowStatus='';
    foreach($bac_brokerage as $bb) {
        if($bb->RowStatus==1) {
            $RowStatus='Add';
        }
        if($bb->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($bb->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($bb->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($bb->RowStatus==2) { 
            if($bac_brokerage[$bbk-1]->PayingEntityType==$bac_brokerage[$bbk]->PayingEntityType && $bac_brokerage[$bbk-1]->PayingEntityName==$bac_brokerage[$bbk]->PayingEntityName && $bac_brokerage[$bbk-1]->ReceivingEntityType==$bac_brokerage[$bbk]->ReceivingEntityType && $bac_brokerage[$bbk-1]->ReceivingEntityName==$bac_brokerage[$bbk]->ReceivingEntityName && $bac_brokerage[$bbk-1]->BrokerName==$bac_brokerage[$bbk]->BrokerName && $bac_brokerage[$bbk-1]->PayableAs==$bac_brokerage[$bbk]->PayableAs && $bac_brokerage[$bbk-1]->PercentageOnFreight==$bac_brokerage[$bbk]->PercentageOnFreight && $bac_brokerage[$bbk-1]->PercentageOnDeadFreight==$bac_brokerage[$bbk]->PercentageOnDeadFreight && $bac_brokerage[$bbk-1]->PercentageOnDemmurage==$bac_brokerage[$bbk]->PercentageOnDemmurage && $bac_brokerage[$bbk-1]->PercentageOnOverage==$bac_brokerage[$bbk]->PercentageOnOverage && $bac_brokerage[$bbk-1]->LumpsumPayable==$bac_brokerage[$bbk]->LumpsumPayable && $bac_brokerage[$bbk-1]->RatePerTonnePayable==$bac_brokerage[$bbk]->RatePerTonnePayable && $bac_brokerage[$bbk-1]->BACComment==$bac_brokerage[$bbk]->BACComment && $bac_brokerage[$bbk-1]->CargoLineNum==$bac_brokerage[$bbk]->CargoLineNum) { 
                //---do nothing------
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            } 
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
            $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Cargo & Ports</td>';
            $html .='<td>BAC >> Brokerage</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $bbk++;
    } 
        
        
    $bbk=0;
    $RowStatus='';
    foreach($bac_addCom as $bb) {
        if($bb->RowStatus==1) {
            $RowStatus='Add';
        }
        if($bb->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($bb->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($bb->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($bb->RowStatus==2) { 
            if($bac_addCom[$bbk-1]->PayingEntityType==$bac_addCom[$bbk]->PayingEntityType && $bac_addCom[$bbk-1]->PayingEntityName==$bac_addCom[$bbk]->PayingEntityName && $bac_addCom[$bbk-1]->ReceivingEntityType==$bac_addCom[$bbk]->ReceivingEntityType && $bac_addCom[$bbk-1]->ReceivingEntityName==$bac_addCom[$bbk]->ReceivingEntityName && $bac_addCom[$bbk-1]->BrokerName==$bac_addCom[$bbk]->BrokerName && $bac_addCom[$bbk-1]->PayableAs==$bac_addCom[$bbk]->PayableAs && $bac_addCom[$bbk-1]->PercentageOnFreight==$bac_addCom[$bbk]->PercentageOnFreight && $bac_addCom[$bbk-1]->PercentageOnDeadFreight==$bac_addCom[$bbk]->PercentageOnDeadFreight && $bac_addCom[$bbk-1]->PercentageOnDemmurage==$bac_addCom[$bbk]->PercentageOnDemmurage && $bac_addCom[$bbk-1]->PercentageOnOverage==$bac_addCom[$bbk]->PercentageOnOverage && $bac_addCom[$bbk-1]->LumpsumPayable==$bac_addCom[$bbk]->LumpsumPayable && $bac_addCom[$bbk-1]->RatePerTonnePayable==$bac_addCom[$bbk]->RatePerTonnePayable && $bac_addCom[$bbk-1]->BACComment==$bac_addCom[$bbk]->BACComment && $bac_addCom[$bbk-1]->CargoLineNum==$bac_addCom[$bbk]->CargoLineNum) { 
                //---do nothing------
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            } 
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
            $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Cargo & Ports</td>';
            $html .='<td>BAC >> Add Comm</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $bbk++;
    } 
        
        
    $bbk=0;
    $RowStatus='';
    foreach($bac_others as $bb) {
        if($bb->RowStatus==1) {
            $RowStatus='Add';
        }
        if($bb->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($bb->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($bb->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($bb->RowStatus==2) { 
            if($bac_others[$bbk-1]->PayingEntityType==$bac_others[$bbk]->PayingEntityType && $bac_others[$bbk-1]->PayingEntityName==$bac_others[$bbk]->PayingEntityName && $bac_others[$bbk-1]->ReceivingEntityType==$bac_others[$bbk]->ReceivingEntityType && $bac_others[$bbk-1]->ReceivingEntityName==$bac_others[$bbk]->ReceivingEntityName && $bac_others[$bbk-1]->BrokerName==$bac_others[$bbk]->BrokerName && $bac_others[$bbk-1]->PayableAs==$bac_others[$bbk]->PayableAs && $bac_others[$bbk-1]->PercentageOnFreight==$bac_others[$bbk]->PercentageOnFreight && $bac_others[$bbk-1]->PercentageOnDeadFreight==$bac_others[$bbk]->PercentageOnDeadFreight && $bac_others[$bbk-1]->PercentageOnDemmurage==$bac_others[$bbk]->PercentageOnDemmurage && $bac_others[$bbk-1]->PercentageOnOverage==$bac_others[$bbk]->PercentageOnOverage && $bac_others[$bbk-1]->LumpsumPayable==$bac_others[$bbk]->LumpsumPayable && $bac_others[$bbk-1]->RatePerTonnePayable==$bac_others[$bbk]->RatePerTonnePayable && $bac_others[$bbk-1]->BACComment==$bac_others[$bbk]->BACComment && $bac_others[$bbk-1]->CargoLineNum==$bac_others[$bbk]->CargoLineNum) { 
                //---do nothing------
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            } 
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
            $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Cargo & Ports</td>';
            $html .='<td>BAC >> Others</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $bbk++;
    }
        
        
    $j=0;
    $RowStatus='';
    foreach($Differential as $d) {
        if($d->RowStatus==1) {
            $RowStatus='Add';
        }
        if($d->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($d->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($d->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($d->RowStatus==2) { 
            if($Differential[$j-1]->DifferentialVesselSizeGroup==$Differential[$j]->DifferentialVesselSizeGroup && $Differential[$j-1]->DifferentialLoadport==$Differential[$j]->DifferentialLoadport && $Differential[$j-1]->ReferencePort==$Differential[$j]->ReferencePort && $Differential[$j-1]->DifferentialDisport==$Differential[$j]->DifferentialDisport && $Differential[$j-1]->DifferentialAmount==$Differential[$j]->DifferentialAmount) { 
                //---do nothing------
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td>Differential</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            } 
                
            if($Differential[$j-1]->DifferentialComments==$Differential[$j]->DifferentialComments && $Differential[$j-1]->InviteeComment==$Differential[$j]->InviteeComment) { 
                //---do nothing------
                    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td>Comment</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
            $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Freight Quote</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
        $j++;
    } 
    $Estimate_RowStatus='';
    $k=0;
    foreach($FreightEstimate as $fe) {
        if($fe->Estimate_RowStatus==1) {
            $Estimate_RowStatus='Add';
        }
        if($fe->Estimate_RowStatus==2) {
            $Estimate_RowStatus='Edit';
        }
        if($fe->Estimate_RowStatus==3) {
            $Estimate_RowStatus='Delete';
        }
        if($fe->Estimate_RowStatus==4) {
            $Estimate_RowStatus='Clone';
        }
        if($fe->Estimate_RowStatus==2) {
            if($FreightEstimate[$k-1]->Freight_Estimate==$FreightEstimate[$k]->Freight_Estimate && $FreightEstimate[$k-1]->Estimate_By==$FreightEstimate[$k]->Estimate_By && $FreightEstimate[$k-1]->Estimate_mt==$FreightEstimate[$k]->Estimate_mt && $FreightEstimate[$k-1]->Estimate_from==$FreightEstimate[$k]->Estimate_from && $FreightEstimate[$k-1]->Estimate_to==$FreightEstimate[$k]->Estimate_to) {
                //---do nothing-------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('Y-m-d: H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Freight_Index==$FreightEstimate[$k]->Freight_Index && $FreightEstimate[$k-1]->Estimate_Index_By==$FreightEstimate[$k]->Estimate_Index_By && $FreightEstimate[$k-1]->Estimate_Index_mt==$FreightEstimate[$k]->Estimate_Index_mt && $FreightEstimate[$k-1]->Estimate_Index_from==$FreightEstimate[$k]->Estimate_Index_from && $FreightEstimate[$k-1]->Estimate_Index_to==$FreightEstimate[$k]->Estimate_Index_to) {
                //---do nothing---
            } else {
                $html .='<tr>';
                $html .='<td>'.date('Y-m-d: H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='</tr>';
            }
                
                
            if($FreightEstimate[$k-1]->estimate_comment==$FreightEstimate[$k]->estimate_comment) {
                //--do nothing
            } else {
                $html .='<tr>';
                $html .='<td>'.date('Y-m-d: H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Comments </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='</tr>';
            }
                
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('Y-m-d: H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
            $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Freight Estimate</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$Estimate_RowStatus.'</td>';
            $html .='</tr>';
        }
            $k++;
    }
    $RowStatus='';
    $l=0;
    foreach($Invitees1 as $inv) {
        if($inv->RowStatus==1) {
            $RowStatus='Add';
        }
        if($inv->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($inv->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($inv->RowStatus==4) {
            $RowStatus='Clone';
        }
        if($inv->RowStatus==2) {
            if($Invitees1[$l-1]->InvPriorityStatus==$Invitees1[$l]->InvPriorityStatus && $Invitees1[$l-1]->Company==$Invitees1[$l]->Company && $Invitees1[$l-1]->EntityID==$Invitees1[$l]->EntityID && $Invitees1[$l-1]->UserMasterID==$Invitees1[$l]->UserMasterID) {
                //-----do nothing
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
                $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Invitees</td>';
                $html .='<td>Add Invitees</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Invitees1[$l-1]->AdverseComments==$Invitees1[$l]->AdverseComments && $Invitees1[$l-1]->Comments==$Invitees1[$l]->Comments) {
                //-----do nothing
                
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
                $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Invitees</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
            $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Invitees</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
            $l++;
    }
    $RowStatus='';
    $m=0;
    foreach($Alerts as $alrt) {
        if($alrt->RowStatus==1) {
            $RowStatus='Add';
        }
        if($alrt->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($alrt->RowStatus==3) {
            $RowStatus='Delete';
        }
        if($alrt->RowStatus==4) {
            $RowStatus='Clone';
        }
            
        if($alrt->RowStatus==2) {
            if($Alerts[$m-1]->CommenceAlertFlag==$Alerts[$m]->CommenceAlertFlag && $Alerts[$m-1]->AuctionCommences==$Alerts[$m]->AuctionCommences && $Alerts[$m-1]->OnlyDisplay==$Alerts[$m]->OnlyDisplay && $Alerts[$m-1]->CommenceDaysBefore==$Alerts[$m]->CommenceDaysBefore && $Alerts[$m-1]->CommenceDate==$Alerts[$m]->CommenceDate && $Alerts[$m-1]->AuctionValidity==$Alerts[$m]->AuctionValidity && $Alerts[$m-1]->AuctionCeases==$Alerts[$m]->AuctionCeases && $Alerts[$m-1]->LayCanStartDate==$Alerts[$m]->LayCanStartDate && $Alerts[$m-1]->AuctionCommenceDefinedDate==$Alerts[$m]->AuctionCommenceDefinedDate) {
                //-----do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Setup Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AlertBeforeCommence==$Alerts[$m]->AlertBeforeCommence && $Alerts[$m-1]->AlertBeforeClosing==$Alerts[$m]->AlertBeforeClosing && $Alerts[$m-1]->AlertNotificationCommence==$Alerts[$m]->AlertNotificationCommence && $Alerts[$m-1]->AlertNotificationClosing==$Alerts[$m]->AlertNotificationClosing && $Alerts[$m-1]->IncludeClosing==$Alerts[$m]->IncludeClosing) {
                //---do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionerComments==$Alerts[$m]->AuctionerComments && $Alerts[$m-1]->InviteesComments==$Alerts[$m]->InviteesComments) {
                //-----do nothing------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='</tr>';
            }
                
        } else {
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
            $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
            $html .='<td>'.$EntityName.'</td>';
            $html .='<td>Cargo Alerts</td>';
            $html .='<td>-</td>';
            $html .='<td>'.$RowStatus.'</td>';
            $html .='</tr>';
        }
            
            $m++;
    }
    $n=0;
    foreach($Auction as $ac) {
        if($ac->RowStatus==2) {
            if($Auction[$n-1]->auctionStatus==$Auction[$n]->auctionStatus && $Auction[$n-1]->auctionExtendedStatus==$Auction[$n]->auctionExtendedStatus) {
                //----do nothing---
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Status</td>';
                $html .='<td>-</td>';
                $html .='<td>Changed</td>';
                $html .='</tr>';
            }
        }
            
        $n++;
    }
    echo $html;
}
    
public function getAuditChangeHistoryData()
{
    $this->load->model('cp_fn_model', '', true);
    $this->load->model('cargo_model', '', true);
    $Auction=$this->masters_model->getAuction();
    $Cargo=$this->masters_model->getCargo();
    $Differential=$this->masters_model->getDifferential();
    $FreightEstimate=$this->masters_model->getFreightEstimateLog();
        
    $Invitees=$this->masters_model->getInvitees();
    $Alerts=$this->masters_model->getAlerts();
    $bus_pro=$this->masters_model->getBusinessProcess_h();
    $bac_brokerage=$this->masters_model->getBacBrokerage_h();
    $bac_addCom=$this->masters_model->getBacAddCom_h();
    $bac_others=$this->masters_model->getOthers_h();
        
    $Invitees1 = array();
    $archk = array();
        
    for($ii=0;$ii<count($Invitees);$ii++) {
        if(!in_array($Invitees[$ii]->RowCounter, $archk)) {
            $Invitees1[]=$Invitees[$ii];
            $archk[]=$Invitees[$ii]->RowCounter;
        }
    }
        
    $EntityName=$Auction[0]->EntityName;
    $html='';
    $RowStatus='';
    $h=0;
    foreach($Auction as $a) {
            
        if($a->RowStatus==2) {
            $RowStatus='Edit';
        }
            
        if($a->RowStatus==2) {
            if($Auction[$h-1]->AuctionersRole==$Auction[$h]->AuctionersRole) {
                  //---do nothing--
            } else {
                $AuctionRolfrom=$this->masters_model->getAuctionRol($Auction[$h-1]->AuctionersRole);
                $AuctionRolto=$this->masters_model->getAuctionRol($Auction[$h]->AuctionersRole);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Role Selection</td>';
                $html .='<td>-</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Role</td>';
                $html .='<td>'.$AuctionRolfrom.'</td>';
                $html .='<td>'.$AuctionRolto.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->SelectFrom==$Auction[$h]->SelectFrom) {
                //---do nothing--
            } else {
                if($Auction[$h-1]->SelectFrom==1) {
                     $selectFrom='Manual';
                }
                if($Auction[$h-1]->SelectFrom==2) {
                    $selectFrom='Import from Topmarx';
                }
                if($Auction[$h]->SelectFrom==1) {
                    $selectTo='Manual';
                }
                if($Auction[$h]->SelectFrom==2) {
                    $selectTo='Import from Topmarx';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Select Charter details from</td>';
                $html .='<td>'.$selectFrom.'</td>';
                $html .='<td>'.$selectTo.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->ContractType==$Auction[$h]->ContractType) {
                //---do nothing--
            } else {
                if($Auction[$h-1]->ContractType==1) {
                    $ContractTypeFrom='Spot';
                }
                if($Auction[$h-1]->ContractType==2) {
                    $ContractTypeFrom='Contract';
                }
                if($Auction[$h]->ContractType==1) {
                    $ContractTypeTo='Spot';
                }
                if($Auction[$h]->ContractType==2) {
                    $ContractTypeTo='Contract';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Contract type</td>';
                $html .='<td>'.$ContractTypeFrom.'</td>';
                $html .='<td>'.$ContractTypeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->COAReference==$Auction[$h]->COAReference) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Contract (COA) reference</td>';
                $html .='<td>'.$Auction[$h-1]->COAReference.'</td>';
                $html .='<td>'.$Auction[$h]->COAReference.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->SalesAgreementReference==$Auction[$h]->SalesAgreementReference) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Sales agreement reference</td>';
                $html .='<td>'.$Auction[$h-1]->SalesAgreementReference.'</td>';
                $html .='<td>'.$Auction[$h]->SalesAgreementReference.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->auctionStatus==$Auction[$h]->auctionStatus) {
                //---do nothing--
            } else {
                if($Auction[$h-1]->auctionStatus=='P') {
                    $StatusFrom='Pending';
                }
                if($Auction[$h-1]->auctionStatus=='C') {
                    $StatusFrom='Complete';
                }
                if($Auction[$h]->auctionStatus=='P') {
                    $StatusTo='Pending';
                }
                if($Auction[$h]->auctionStatus=='C') {
                    $StatusTo='Complete';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Sales agreement reference</td>';
                $html .='<td>'.$StatusFrom.'</td>';
                $html .='<td>'.$StatusTo.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->auctionExtendedStatus==$Auction[$h]->auctionExtendedStatus) {
                //---do nothing--
            } else {
                    
                if($Auction[$h]->auctionExtendedStatus=='PNR') {
                    $ExtendedStatusTo='Pending Release';
                }
                if($Auction[$h]->auctionExtendedStatus=='A') {
                    $ExtendedStatusTo='Activated';
                }
                if($Auction[$h]->auctionExtendedStatus=='W') {
                    $ExtendedStatusTo='Widthdrawn';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Cargo Release Status</td>';
                $html .='<td>'.$Auction[$h-1]->auctionExtendedStatus.'</td>';
                $html .='<td>'.$ExtendedStatusTo.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$h-1]->ShipmentReferenceID==$Auction[$h]->ShipmentReferenceID) {
                //---do nothing--
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($a->UserDate)).'</td>';
                $html .='<td>'.$a->FirstName.' '.$a->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>Charter Details</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Shipment Reference ID</td>';
                $html .='<td>'.$Auction[$h-1]->ShipmentReferenceID.'</td>';
                $html .='<td>'.$Auction[$h]->ShipmentReferenceID.'</td>';
                $html .='</tr>';
            }
        }
        $h++;
    }
        
        
    $RowStatus='';
    $bus=-1;
    foreach($bus_pro as $bp) {
        $bus++;
        if($bus==0) {
            continue;
        }
        if($bp->RowStatus==2) {
            $RowStatus='Edit';
        }
            
        if($bp->RowStatus==2) {
            if($bus_pro[$bus-1]->UserList==$bus_pro[$bus]->UserList) {
                //--do nothing---
                    
            } else {
                $userids=explode(',', $bus_pro[$bus-1]->UserList);
                $userids1=explode(',', $bus_pro[$bus]->UserList);
                $usernames='';
                $usernames1='';
                foreach($userids as $uid) {
                    $users=$this->cp_fn_model->getUserByID($uid);
                    $usernames .=$users->FirstName.' '.$users->LastName.', ';
                }
                foreach($userids1 as $uid) {
                    $users=$this->cp_fn_model->getUserByID($uid);
                    $usernames1 .=$users->FirstName.' '.$users->LastName.', ';
                }
                  $usernames=trim($usernames, ", ");
                  $usernames1=trim($usernames1, ", ");
                  $html .='<tr>';
                  $html .='<td>'.date('d-m-Y H:i:s', strtotime($bp->UserDate)).'</td>';
                  $html .='<td>'.$bp->FirstName.' '.$bp->LastName.'</td>';
                  $html .='<td>'.$EntityName.'</td>';
                  $html .='<td>Charter Details</td>';
                  $html .='<td>Business Process</td>';
                  $html .='<td>'.$RowStatus.'</td>';
                  $html .='<td>User list</td>';
                  $html .='<td>'.$usernames.'</td>';
                  $html .='<td>'.$usernames1.'</td>';
                  $html .='</tr>';
            }
        }
            
        if($bp->RowStatus==2) {
            if($bus_pro[$bus-1]->Status==$bus_pro[$bus]->Status) {
                //--do nothing---
                    
            } else {
                if($bus_pro[$bus-1]->Status==1) {
                    $status='Active';
                } else {
                    $status='Inactive';    
                }
                if($bus_pro[$bus]->Status==1) {
                    $status1='Active';
                } else {
                    $status1='Inactive';    
                }
                    $html .='<tr>';
                    $html .='<td>'.date('d-m-Y H:i:s', strtotime($bp->UserDate)).'</td>';
                    $html .='<td>'.$bp->FirstName.' '.$bp->LastName.'</td>';
                    $html .='<td>'.$EntityName.'</td>';
                    $html .='<td>Charter Details</td>';
                    $html .='<td>Business Process</td>';
                    $html .='<td>'.$RowStatus.'</td>';
                    $html .='<td>Status</td>';
                    $html .='<td>'.$status.'</td>';
                    $html .='<td>'.$status1.'</td>';
                    $html .='</tr>';
            }
        }
            
        
    }
        
        
        
    $RowStatus='';
    $i=0;
    foreach($Cargo as $c) {
        if($c->RowStatus==2) {
            $RowStatus='Edit';
        }
            
        if($c->RowStatus==2) {
            if($Cargo[$i-1]->SelectFrom==$Cargo[$i]->SelectFrom) {
                //-----do nothing ----
            } else {
                $selectFrom=$this->masters_model->getCargoMaster1($Cargo[$i-1]->SelectFrom);
                $selectTo=$this->masters_model->getCargoMaster1($Cargo[$i]->SelectFrom);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Select cargo</td>';
                $html .='<td>'.$selectFrom.'</td>';
                $html .='<td>'.$selectTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->CargoQtyMT==$Cargo[$i]->CargoQtyMT) {
                //-----do nothing ----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Cargo quantity to load</td>';
                $html .='<td>'.number_format($Cargo[$i-1]->CargoQtyMT).'</td>';
                $html .='<td>'.number_format($Cargo[$i]->CargoQtyMT).'</td>';
                $html .='</tr>';
            }
            if($Cargo[$i-1]->CargoLoadedBasis==$Cargo[$i]->CargoLoadedBasis) {
                //-----do nothing ----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Cargo quantity loaded option basis</td>';
                $html .='<td>'.$Cargo[$i-1]->CargoLoadedBasis.'</td>';
                $html .='<td>'.$Cargo[$i]->CargoLoadedBasis.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->CargoLimitBasis==$Cargo[$i]->CargoLimitBasis) {
                //-----do nothing----
            } else {
                if($Cargo[$i-1]->CargoLimitBasis==1) {
                    $CargoLimitBasisFrom='Max and Min';
                }
                if($Cargo[$i-1]->CargoLimitBasis==2) {
                    $CargoLimitBasisFrom='% Tolerance limit';
                }
                if($Cargo[$i]->CargoLimitBasis==1) {
                    $CargoLimitBasisTo='Max and Min';
                }
                if($Cargo[$i]->CargoLimitBasis==2) {
                    $CargoLimitBasisTo='% Tolerance limit';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Cargo quantity limit basis</td>';
                $html .='<td>'.$CargoLimitBasisFrom.'</td>';
                $html .='<td>'.$CargoLimitBasisTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->MinCargoMT==$Cargo[$i]->MinCargoMT) {
                //-----do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Min cargo is</td>';
                $html .='<td>'.$Cargo[$i-1]->MinCargoMT.'</td>';
                $html .='<td>'.$Cargo[$i]->MinCargoMT.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->MaxCargoMT==$Cargo[$i]->MaxCargoMT) {
                //-----do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Max cargo is</td>';
                $html .='<td>'.$Cargo[$i-1]->MaxCargoMT.'</td>';
                $html .='<td>'.$Cargo[$i]->MaxCargoMT.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ToleranceLimit==$Cargo[$i]->ToleranceLimit) {
                //-----do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Tolerance limit (%)</td>';
                $html .='<td>'.$Cargo[$i-1]->ToleranceLimit.'</td>';
                $html .='<td>'.$Cargo[$i]->ToleranceLimit.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LowerLimit==$Cargo[$i]->LowerLimit) {
                //-----do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Lower cargo limit is</td>';
                $html .='<td>'.number_format($Cargo[$i-1]->LowerLimit).'</td>';
                $html .='<td>'.number_format($Cargo[$i]->LowerLimit).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->UpperLimit==$Cargo[$i]->UpperLimit) {
                //-----do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Cargo</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Upper cargo limit is</td>';
                $html .='<td>'.number_format($Cargo[$i-1]->UpperLimit).'</td>';
                $html .='<td>'.number_format($Cargo[$i]->UpperLimit).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LoadPort==$Cargo[$i]->LoadPort) {
                //---do nothing--
            } else {
                $LoadPortFrom=$this->masters_model->getLoadPortDisport($Cargo[$i-1]->LoadPort);
                $LoadPortTo=$this->masters_model->getLoadPortDisport($Cargo[$i]->LoadPort);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Load Port</td>';
                $html .='<td>'.$LoadPortFrom.'</td>';
                $html .='<td>'.$LoadPortTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpLaycanStartDate==$Cargo[$i]->LpLaycanStartDate) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpLaycanStartDate !='') {
                    $LpLaycanStartDate=date('d-m-Y', strtotime($Cargo[$i-1]->LpLaycanStartDate));
                } else{
                    $LpLaycanStartDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Load port laycan start date</td>';
                $html .='<td>'.$LpLaycanStartDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->LpLaycanStartDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpLaycanEndDate==$Cargo[$i]->LpLaycanEndDate) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpLaycanEndDate !='') {
                    $LpLaycanEndDate=date('d-m-Y', strtotime($Cargo[$i-1]->LpLaycanEndDate));
                } else{
                    $LpLaycanEndDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Load port laycan finish date</td>';
                $html .='<td>'.$LpLaycanEndDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->LpLaycanEndDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpPreferDate==$Cargo[$i]->LpPreferDate) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpPreferDate !='') {
                    $LpPreferDate=date('d-m-Y', strtotime($Cargo[$i-1]->LpPreferDate));
                } else{
                    $LpPreferDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Loadport preferred arrival date</td>';
                $html .='<td>'.$LpPreferDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->LpPreferDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LoadingTerms==$Cargo[$i]->LoadingTerms) {
                //---do nothing--
            } else {
                $loadingTermFrom=$this->masters_model->getLoadingTerm($Cargo[$i-1]->LoadingTerms);
                $loadingTermTo=$this->masters_model->getLoadingTerm($Cargo[$i]->LoadingTerms);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Loading Terms</td>';
                $html .='<td>'.$loadingTermFrom.'</td>';
                $html .='<td>'.$loadingTermTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedLpDelayDay==$Cargo[$i]->ExpectedLpDelayDay && $Cargo[$i-1]->ExpectedLpDelayHour==$Cargo[$i]->ExpectedLpDelayHour) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->ExpectedLpDelayDay == '' & $Cargo[$i-1]->ExpectedLpDelayHour == '') {
                    $ExpLpDlyDay='';
                } else {
                    $ExpLpDlyDay=$Cargo[$i-1]->ExpectedLpDelayDay.' days '.$Cargo[$i-1]->ExpectedLpDelayHour.' hours';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected loadport delay</td>';
                $html .='<td>'.$ExpLpDlyDay.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedLpDelayDay.' days '.$Cargo[$i]->ExpectedLpDelayHour.'hours</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LoadingRateMT==$Cargo[$i]->LoadingRateMT) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Loading rate (mt)</td>';
                $html .='<td>'.number_format($Cargo[$i-1]->LoadingRateMT).'</td>';
                $html .='<td>'.number_format($Cargo[$i]->LoadingRateMT).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LoadingRateUOM==$Cargo[$i]->LoadingRateUOM) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LoadingRateUOM==1) {
                    $LoadingRateUOMFrom='Per hour';
                }
                if($Cargo[$i-1]->LoadingRateUOM==2) {
                    $LoadingRateUOMFrom='Per weather working day';
                }
                if($Cargo[$i-1]->LoadingRateUOM==3) {
                    $LoadingRateUOMFrom='Max time limit';
                }
                if($Cargo[$i]->LoadingRateUOM==1) {
                    $LoadingRateUOMTo='Per hour';
                }
                if($Cargo[$i]->LoadingRateUOM==2) {
                    $LoadingRateUOMTo='Per weather working day';
                }
                if($Cargo[$i]->LoadingRateUOM==3) {
                    $LoadingRateUOMTo='Max time limit';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Loading rate based on (uom)</td>';
                $html .='<td>'.$LoadingRateUOMFrom.'</td>';
                $html .='<td>'.$LoadingRateUOMTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpMaxTime==$Cargo[$i]->LpMaxTime) {
                //---do nothing--
            } else {
                    
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Max time to load cargo (hrs)</td>';
                $html .='<td>'.$Cargo[$i-1]->LpMaxTime.'</td>';
                $html .='<td>'.$Cargo[$i]->LpMaxTime.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpLaytimeType==$Cargo[$i]->LpLaytimeType) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpLaytimeType==1) {
                    $LpMaxTimeFrom='Reversible';
                }
                if($Cargo[$i-1]->LpLaytimeType==2) {
                    $LpMaxTimeFrom='Non Reversible';
                }
                if($Cargo[$i-1]->LpLaytimeType==3) {
                    $LpMaxTimeFrom='Average';
                }
                if($Cargo[$i]->LpLaytimeType==1) {
                    $LpMaxTimeTo='Reversible';
                }
                if($Cargo[$i]->LpLaytimeType==2) {
                    $LpMaxTimeTo='Non Reversible';
                }
                if($Cargo[$i]->LpLaytimeType==3) {
                    $LpMaxTimeTo='Average';
                }
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime</td>';
                $html .='<td>'.$LpMaxTimeFrom.'</td>';
                $html .='<td>'.$LpMaxTimeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpCalculationBasedOn==$Cargo[$i]->LpCalculationBasedOn) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpCalculationBasedOn==108) {
                    $LpCalculationBasedOnFrom='Bill of Loading Quantity';
                }
                if($Cargo[$i-1]->LpCalculationBasedOn==109) {
                    $LpCalculationBasedOnFrom='Outturn or Discharge Quantity';
                }
                    
                if($Cargo[$i]->LpCalculationBasedOn==108) {
                    $LpCalculationBasedOnTo='Bill of Loading Quantity';
                }
                if($Cargo[$i]->LpCalculationBasedOn==109) {
                    $LpCalculationBasedOnTo='Outturn or Discharge Quantity';
                }
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime tonnage calc. based on</td>';
                $html .='<td>'.$LpCalculationBasedOnFrom.'</td>';
                $html .='<td>'.$LpCalculationBasedOnTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpTurnTime==$Cargo[$i]->LpTurnTime) {
                //---do nothing--
            } else {
                $freeTimeFrom=$this->masters_model->getTurnFreeTime($Cargo[$i-1]->LpTurnTime);
                $freeTimeTo=$this->masters_model->getTurnFreeTime($Cargo[$i]->LpTurnTime);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Turn (free) time (hours)</td>';
                $html .='<td>'.$freeTimeFrom.'</td>';
                $html .='<td>'.$freeTimeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpPriorUseTerms==$Cargo[$i]->LpPriorUseTerms) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpPriorUseTerms==102) {
                    $LpPriorUseTermsFrom='IUATUTC';
                }
                if($Cargo[$i-1]->LpPriorUseTerms==10) {
                    $LpPriorUseTermsFrom='IUHTUTC';
                }
                    
                if($Cargo[$i]->LpPriorUseTerms==102) {
                    $LpPriorUseTermsTo='IUATUTC';
                }
                if($Cargo[$i]->LpPriorUseTerms==10) {
                    $LpPriorUseTermsTo='IUHTUTC';
                }
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Prior use terms</td>';
                $html .='<td>'.$LpPriorUseTermsFrom.'</td>';
                $html .='<td>'.$LpPriorUseTermsTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpLaytimeBasedOn==$Cargo[$i]->LpLaytimeBasedOn) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpLaytimeBasedOn==1) {
                    $LpLaytimeBasedOnFrom='ATS';
                }
                if($Cargo[$i-1]->LpLaytimeBasedOn==2) {
                    $LpLaytimeBasedOnFrom='WTS';
                }
                    
                if($Cargo[$i]->LpLaytimeBasedOn==1) {
                    $LpLaytimeBasedOnTo='ATS';
                }
                if($Cargo[$i]->LpLaytimeBasedOn==2) {
                    $LpLaytimeBasedOnTo='WTS';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime based on</td>';
                $html .='<td>'.$LpLaytimeBasedOnFrom.'</td>';
                $html .='<td>'.$LpLaytimeBasedOnTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpCharterType==$Cargo[$i]->LpCharterType) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->LpCharterType==1) {
                    $LpCharterTypeFrom='1 Safe Port 1 Safe Berth (1SP1SB)';
                } else if($Cargo[$i-1]->LpCharterType==2) {
                    $LpCharterTypeFrom='1 Safe Port 2 Safe Berth (1SP2SB)';
                } else if($Cargo[$i-1]->LpCharterType==3) {
                    $LpCharterTypeFrom='2 Safe Port 1 Safe Berth (2SP1SB)';
                } else if($Cargo[$i-1]->LpCharterType==4) {
                    $LpCharterTypeFrom='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                if($Cargo[$i]->LpCharterType==1) {
                    $LpCharterTypeTo='1 Safe Port 1 Safe Berth (1SP1SB)';
                } else if($Cargo[$i]->LpCharterType==2) {
                    $LpCharterTypeTo='1 Safe Port 2 Safe Berth (1SP2SB)';
                } else if($Cargo[$i]->LpCharterType==3) {
                    $LpCharterTypeTo='2 Safe Port 1 Safe Berth (2SP1SB)';
                } else if($Cargo[$i]->LpCharterType==4) {
                    $LpCharterTypeTo='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Type of charter</td>';
                $html .='<td>'.$LpCharterTypeFrom.'</td>';
                $html .='<td>'.$LpCharterTypeTo.'</td>';
                $html .='</tr>';
            }
            if($Cargo[$i-1]->LpNorTendering==$Cargo[$i]->LpNorTendering) {
                //---do nothing--
            } else {
                $LpNorTenderingFrom=$this->masters_model->getNorTendring($Cargo[$i-1]->LpNorTendering);
                $LpNorTenderingTo=$this->masters_model->getNorTendring($Cargo[$i]->LpNorTendering);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>NOR tender</td>';
                $html .='<td>'.$LpNorTenderingFrom.'</td>';
                $html .='<td>'.$LpNorTenderingTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedLpDelayDay==$Cargo[$i]->ExpectedLpDelayDay) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected loadport delay days</td>';
                $html .='<td>'.$Cargo[$i-1]->ExpectedLpDelayDay.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedLpDelayDay.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedLpDelayHour==$Cargo[$i]->ExpectedLpDelayHour) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected loadport delay hours</td>';
                $html .='<td>'.$Cargo[$i-1]->ExpectedLpDelayHour.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedLpDelayHour.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->LpStevedoringTerms==$Cargo[$i]->LpStevedoringTerms) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->LpStevedoringTerms) {
                    $LpStevedoringTermsFrom=$this->cargo_model->getSteveDoringTermsByID($Cargo[$i-1]->LpStevedoringTerms);
                    $LpSteveTerms=$LpStevedoringTermsFrom->Code.' || Description: '.$LpStevedoringTermsFrom->Description;
                } else {
                    $LpSteveTerms='';
                }
                    
                $LpStevedoringTermsTo=$this->cargo_model->getSteveDoringTermsByID($Cargo[$i]->LpStevedoringTerms);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>LoadPort Stevedoring terms</td>';
                $html .='<td>'.$LpSteveTerms.'</td>';
                $html .='<td> Code: '.$LpStevedoringTermsTo->Code.' || Description: '.$LpStevedoringTermsTo->Description.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DisPort==$Cargo[$i]->DisPort) {
                //----do nothing----
            } else {
                $DisportFrom=$this->masters_model->getLoadPortDisport($Cargo[$i-1]->DisPort);
                $DisportTo=$this->masters_model->getLoadPortDisport($Cargo[$i]->DisPort);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$DisportFrom.'</td>';
                $html .='<td>'.$DisportTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DisPort==$Cargo[$i]->DisPort) {
                //----do nothing----
            } else {
                $DisportFrom=$this->masters_model->getLoadPortDisport($Cargo[$i-1]->DisPort);
                $DisportTo=$this->masters_model->getLoadPortDisport($Cargo[$i]->DisPort);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$DisportFrom.'</td>';
                $html .='<td>'.$DisportTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpArrivalStartDate==$Cargo[$i]->DpArrivalStartDate) {
                //----do nothing----
                    
            } else {
                if($Cargo[$i-1]->DpArrivalStartDate !='') {
                    $DpArrivalStartDate=date('d-m-Y', strtotime($Cargo[$i-1]->DpArrivalStartDate));
                } else{
                    $DpArrivalStartDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Arrival window start date</td>';
                $html .='<td>'.$DpArrivalStartDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->DpArrivalStartDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpArrivalEndDate==$Cargo[$i]->DpArrivalEndDate) {
                //----do nothing----
                    
            } else {
                if($Cargo[$i-1]->DpArrivalEndDate !='') {
                    $DpArrivalEndDate=date('d-m-Y', strtotime($Cargo[$i-1]->DpArrivalEndDate));
                } else {
                    $DpArrivalEndDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Disport preferred arrival date</td>';
                $html .='<td>'.$DpArrivalEndDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->DpArrivalEndDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpPreferDate==$Cargo[$i]->DpPreferDate) {
                //----do nothing----
                    
            } else {
                if($Cargo[$i-1]->DpPreferDate !='') {
                    $DpPreferDate=date('d-m-Y', strtotime($Cargo[$i-1]->DpPreferDate));
                } else {
                    $DpPreferDate='';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Arrival preferred date, if any</td>';
                $html .='<td>'.$DpPreferDate.'</td>';
                $html .='<td>'.date('d-m-Y', strtotime($Cargo[$i]->DpPreferDate)).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedDpDelayDay==$Cargo[$i]->ExpectedDpDelayDay && $Cargo[$i-1]->ExpectedDpDelayHour==$Cargo[$i]->ExpectedDpDelayHour) {
                //---do nothing--
            } else {
                if($Cargo[$i-1]->ExpectedDpDelayDay == '' & $Cargo[$i-1]->ExpectedDpDelayHour == '') {
                    $ExpDpDlyDay='';
                } else {
                    $ExpDpDlyDay=$Cargo[$i-1]->ExpectedDpDelayDay.' days '.$Cargo[$i-1]->ExpectedDpDelayHour.' hours';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>LoadPort</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected disport delay</td>';
                $html .='<td>'.$ExpDpDlyDay.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedDpDelayDay.' days '.$Cargo[$i]->ExpectedDpDelayHour.' hours</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DischargingTerms==$Cargo[$i]->DischargingTerms) {
                //----do nothing----
                    
            } else {
                $DischargingTermsFrom=$this->masters_model->getLoadingTerm($Cargo[$i-1]->DischargingTerms);
                $DischargingTermsTo=$this->masters_model->getLoadingTerm($Cargo[$i]->DischargingTerms);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Discharging Terms</td>';
                $html .='<td>'.$DischargingTermsFrom.'</td>';
                $html .='<td>'.$DischargingTermsTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DischargingRateMT==$Cargo[$i]->DischargingRateMT) {
                //----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Discharing rate (mt)</td>';
                $html .='<td>'.number_format($Cargo[$i-1]->DischargingRateMT).'</td>';
                $html .='<td>'.number_format($Cargo[$i]->DischargingRateMT).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DischargingRateUOM==$Cargo[$i]->DischargingRateUOM) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->DischargingRateUOM==1) {
                    $DischargingRateUOMFrom='Per hour';
                }
                if($Cargo[$i-1]->DischargingRateUOM==2) {
                    $DischargingRateUOMFrom='Per weather working day';
                }
                if($Cargo[$i-1]->DischargingRateUOM==3) {
                    $DischargingRateUOMFrom='Max time limit';
                }
                if($Cargo[$i]->DischargingRateUOM==1) {
                    $DischargingRateUOMTo='Per hour';
                }
                if($Cargo[$i]->DischargingRateUOM==2) {
                    $DischargingRateUOMTo='Per weather working day';
                }
                if($Cargo[$i]->DischargingRateUOM==3) {
                    $DischargingRateUOMTo='Max time limit';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Discharging rate based on (uom)</td>';
                $html .='<td>'.$DischargingRateUOMFrom.'</td>';
                $html .='<td>'.$DischargingRateUOMTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpMaxTime==$Cargo[$i]->DpMaxTime) {
                //----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Max time to discharge (hrs)</td>';
                $html .='<td>'.$Cargo[$i-1]->DpMaxTime.'</td>';
                $html .='<td>'.$Cargo[$i]->DpMaxTime.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpLaytimeType==$Cargo[$i]->DpLaytimeType) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->DpLaytimeType==1) {
                    $DpLaytimeTypeFrom='Reversible';
                }
                if($Cargo[$i-1]->DpLaytimeType==2) {
                    $DpLaytimeTypeFrom='Non Reversible';
                }
                if($Cargo[$i-1]->DpLaytimeType==3) {
                    $DpLaytimeTypeFrom='Average';
                }
                if($Cargo[$i]->DpLaytimeType==1) {
                    $DpLaytimeTypeTo='Reversible';
                }
                if($Cargo[$i]->DpLaytimeType==2) {
                    $DpLaytimeTypeTo='Non Reversible';
                }
                if($Cargo[$i]->DpLaytimeType==3) {
                    $DpLaytimeTypeTo='Average';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime type</td>';
                $html .='<td>'.$DpLaytimeTypeFrom.'</td>';
                $html .='<td>'.$DpLaytimeTypeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpCalculationBasedOn==$Cargo[$i]->DpCalculationBasedOn) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->DpCalculationBasedOn=108) {
                    $DpCalculationBasedOnFrom='Bill of Loading Quantity';
                }
                if($Cargo[$i-1]->DpCalculationBasedOn=109) {
                    $DpCalculationBasedOnFrom='Outturn or Discharge Quantity';
                }
                if($Cargo[$i]->DpCalculationBasedOn=108) {
                    $DpCalculationBasedOnTo='Bill of Loading Quantity';
                }
                if($Cargo[$i]->DpCalculationBasedOn=109) {
                    $DpCalculationBasedOnTo='Outturn or Discharge Quantity';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime tonnage calc. based on</td>';
                $html .='<td>'.$DpCalculationBasedOnFrom.'</td>';
                $html .='<td>'.$DpCalculationBasedOnTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpLaytimeBasedOn==$Cargo[$i]->DpLaytimeBasedOn) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->DpLaytimeBasedOn==1) {
                    $DpLaytimeBasedOnFrom='ATS';
                }
                if($Cargo[$i-1]->DpLaytimeBasedOn==2) {
                    $DpLaytimeBasedOnFrom='WTS';
                }
                if($Cargo[$i]->DpLaytimeBasedOn==1) {
                    $DpLaytimeBasedOnTo='ATS';
                }
                if($Cargo[$i]->DpLaytimeBasedOn==2) {
                    $DpLaytimeBasedOnTo='WTS';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Laytime based on</td>';
                $html .='<td>'.$DpLaytimeBasedOnFrom.'</td>';
                $html .='<td>'.$DpLaytimeBasedOnTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpCharterType==$Cargo[$i]->DpCharterType) {
                //----do nothing----
            } else {
                if($Cargo[$i-1]->DpCharterType==1) {
                    $DpCharterTypeFrom='1 Safe Port 1 Safe Berth (1SP1SB)';
                } else if($Cargo[$i-1]->DpCharterType==2) {
                    $DpCharterTypeFrom='1 Safe Port 2 Safe Berth (1SP2SB)';
                } else if($Cargo[$i-1]->DpCharterType==3) {
                    $DpCharterTypeFrom='2 Safe Port 1 Safe Berth (2SP1SB)';
                } else if($Cargo[$i-1]->DpCharterType==4) {
                    $DpCharterTypeFrom='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                if($Cargo[$i]->DpCharterType==1) {
                    $DpCharterTypeTo='1 Safe Port 1 Safe Berth (1SP1SB)';
                } else if($Cargo[$i]->DpCharterType==2) {
                    $DpCharterTypeTo='1 Safe Port 2 Safe Berth (1SP2SB)';
                } else if($Cargo[$i]->DpCharterType==3) {
                    $DpCharterTypeTo='2 Safe Port 1 Safe Berth (2SP1SB)';
                } else if($Cargo[$i]->DpCharterType==4) {
                    $DpCharterTypeTo='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Type of charter</td>';
                $html .='<td>'.$DpCharterTypeFrom.'</td>';
                $html .='<td>'.$DpCharterTypeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpNorTendering==$Cargo[$i]->DpNorTendering) {
                //----do nothing----
            } else {
                $DpNorTenderingFrom=$this->masters_model->getNorTendring($Cargo[$i-1]->DpNorTendering);
                $DpNorTenderingTo=$this->masters_model->getNorTendring($Cargo[$i]->DpNorTendering);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>NOR tender</td>';
                $html .='<td>'.$DpNorTenderingFrom.'</td>';
                $html .='<td>'.$DpNorTenderingTo.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedDpDelayDay==$Cargo[$i]->ExpectedDpDelayDay) {
                //----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected disport delay Days</td>';
                $html .='<td>'.$Cargo[$i-1]->ExpectedDpDelayDay.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedDpDelayDay.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->ExpectedDpDelayHour==$Cargo[$i]->ExpectedDpDelayHour) {
                //----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Expected disport delay Hours</td>';
                $html .='<td>'.$Cargo[$i-1]->ExpectedDpDelayHour.'</td>';
                $html .='<td>'.$Cargo[$i]->ExpectedDpDelayHour.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->DpStevedoringTerms==$Cargo[$i]->DpStevedoringTerms) {
                //----do nothing----
            } else {
                $DpStevedoringTermsFrom=$this->cargo_model->getStevedoringTermsByID($Cargo[$i-1]->DpStevedoringTerms);
                $DpStevedoringTermsTo=$this->cargo_model->getStevedoringTermsByID($Cargo[$i]->DpStevedoringTerms);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Disport</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>DisPort Stevedoring terms</td>';
                $html .='<td>'.$DpStevedoringTermsFrom->Code.' || Description: '.$DpStevedoringTermsFrom->Description.'</td>';
                $html .='<td> Code: '.$DpStevedoringTermsTo->Code.' || Description: '.$DpStevedoringTermsTo->Description.'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->CargoInternalComments==$Cargo[$i]->CargoInternalComments) {
                //-----do nothing--------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Comment</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by cargo owner</td>';
                $html .='<td>'.substr($Cargo[$i-1]->CargoInternalComments, 0, 20).'</td>';
                $html .='<td>'.substr($Cargo[$i]->CargoInternalComments, 0, 20).'</td>';
                $html .='</tr>';
            }
                
            if($Cargo[$i-1]->CargoDisplayComments==$Cargo[$i]->CargoDisplayComments) {
                //-----do nothing--------
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($c->UserDate)).'</td>';
                $html .='<td>'.$c->FirstName.' '.$c->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>Comment</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by Invitees</td>';
                $html .='<td>'.substr($Cargo[$i-1]->CargoDisplayComments, 0, 20).'</td>';
                $html .='<td>'.substr($Cargo[$i]->CargoDisplayComments, 0, 20).'</td>';
                $html .='</tr>';
            }
        } 
        $i++;
    }
        
    $j=0;
    $RowStatus='';
    foreach($Differential as $d) {
            
        if($d->RowStatus==2) {
            $RowStatus='Edit';
        }
            
        if($d->RowStatus==2) {
            if($Differential[$j-1]->DifferentialVesselSizeGroup==$Differential[$j]->DifferentialVesselSizeGroup) {
                //------do nothing----
            } else {
                $vesselSizeFrom=$this->masters_model->getVesselSizeByID($Differential[$j-1]->DifferentialVesselSizeGroup);
                $vesselSizeTo=$this->masters_model->getVesselSizeByID($Differential[$j]->DifferentialVesselSizeGroup);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Differential </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Vessel size group</td>';
                $html .='<td>'.$vesselSizeFrom.'</td>';
                $html .='<td>'.$vesselSizeTo.'</td>';
                $html .='</tr>';
            }
                
            if($Differential[$j-1]->DifferentialLoadport==$Differential[$j]->DifferentialLoadport) {
                //------do nothing----
            } else {
                $baseLoadPortFrom=$this->masters_model->getLoadPortDisport($Differential[$j-1]->DifferentialLoadport);
                $baseLoadPortTo=$this->masters_model->getLoadPortDisport($Differential[$j]->DifferentialLoadport);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Differential </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Base (load) port</td>';
                $html .='<td>'.$baseLoadPortFrom.'</td>';
                $html .='<td>'.$baseLoadPortTo.'</td>';
                $html .='</tr>';
            }
                
                
            if($Differential[$j-1]->ReferencePort==$Differential[$j]->ReferencePort) {
                //------do nothing----
            } else {
                $ReferencePortFrom=$this->masters_model->getLoadPortDisport($Differential[$j-1]->ReferencePort);
                $ReferencePortTo=$this->masters_model->getLoadPortDisport($Differential[$j]->ReferencePort);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Differential </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Reference Port</td>';
                $html .='<td>'.$ReferencePortFrom.'</td>';
                $html .='<td>'.$ReferencePortTo.'</td>';
                $html .='</tr>';
            }
                
            if($Differential[$j-1]->DifferentialDisport==$Differential[$j]->DifferentialDisport) {
                //------do nothing----
            } else {
                $DifferentialDisportFrom=$this->masters_model->getLoadPortDisport($Differential[$j-1]->DifferentialDisport);
                $DifferentialDisporttTo=$this->masters_model->getLoadPortDisport($Differential[$j]->DifferentialDisport);
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Differential </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Differential disport</td>';
                $html .='<td>'.$DifferentialDisportFrom.'</td>';
                $html .='<td>'.$DifferentialDisporttTo.'</td>';
                $html .='</tr>';
            }
                
            if($Differential[$j-1]->DifferentialAmount==$Differential[$j]->DifferentialAmount) {
                //------do nothing----
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Differential </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Differential (cargo owner)</td>';
                $html .='<td>'.$Differential[$j-1]->DifferentialAmount.'</td>';
                $html .='<td>'.$Differential[$j]->DifferentialAmount.'</td>';
                $html .='</tr>';
            }
                
            if($Differential[$j-1]->DifferentialComments==$Differential[$j]->DifferentialComments) {
                //------do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Comment </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by cargo owner</td>';
                $html .='<td>'.substr($Differential[$j-1]->DifferentialComments, 0, 20).'</td>';
                $html .='<td>'.substr($Differential[$j]->DifferentialComments, 0, 20).'</td>';
                $html .='</tr>';
            }
                
            if($Differential[$j-1]->InviteeComment==$Differential[$j]->InviteeComment) {
                //------do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($d->UserDate)).'</td>';
                $html .='<td>'.$d->FirstName.' '.$d->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Quote</td>';
                $html .='<td> Comment </td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by Invitees</td>';
                $html .='<td>'.substr($Differential[$j-1]->InviteeComment, 0, 20).'</td>';
                $html .='<td>'.substr($Differential[$j]->InviteeComment, 0, 20).'</td>';
                $html .='</tr>';
            }
        } 
        $j++;
    }
        
    $Estimate_RowStatus='';
    $k=0;
    foreach($FreightEstimate as $fe) {
            
        if($fe->Estimate_RowStatus==2) {
            $Estimate_RowStatus='Edit';
        }
            
        if($fe->Estimate_RowStatus==2) {
            if($FreightEstimate[$k-1]->Freight_Estimate==$FreightEstimate[$k]->Freight_Estimate) {
                  //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Enter freight estimate</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Freight_Estimate.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Freight_Estimate.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_By==$FreightEstimate[$k]->Estimate_By) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight estimate by</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_By.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_By.'</td>';
                $html .='</tr>';
            }
                
                
            if($FreightEstimate[$k-1]->Estimate_mt==$FreightEstimate[$k]->Estimate_mt) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight estimate($/mt)</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_mt.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_mt.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_from==$FreightEstimate[$k]->Estimate_from) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight ($/mt) range from</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_from.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_from.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_to==$FreightEstimate[$k]->Estimate_to) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td> Freight Estimate (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight ($/mt) range to</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_to.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_to.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Freight_Index==$FreightEstimate[$k]->Freight_Index) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>  Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Enter freight based on index</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Freight_Index.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Freight_Index.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_Index_By==$FreightEstimate[$k]->Estimate_Index_By) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>  Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight by index ($/mt OR range)</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_Index_By.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_Index_By.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_Index_mt==$FreightEstimate[$k]->Estimate_Index_mt) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>  Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight by index($/mt)</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_Index_mt.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_Index_mt.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->Estimate_Index_from==$FreightEstimate[$k]->Estimate_Index_from) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>  Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight by index range from</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_Index_from.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_Index_from.'</td>';
                $html .='</tr>';
            }
                
                
            if($FreightEstimate[$k-1]->Estimate_Index_to==$FreightEstimate[$k]->Estimate_Index_to) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>  Freight by index (cargo owner) </td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Freight by index range to</td>';
                $html .='<td>'.$FreightEstimate[$k-1]->Estimate_Index_to.'</td>';
                $html .='<td>'.$FreightEstimate[$k]->Estimate_Index_to.'</td>';
                $html .='</tr>';
            }
                
            if($FreightEstimate[$k-1]->estimate_comment==$FreightEstimate[$k]->estimate_comment) {
                //------do nothing-----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($fe->Estimate_UserDate)).'</td>';
                $html .='<td>'.$fe->FirstName.' '.$fe->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Freight Estimate</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$Estimate_RowStatus.'</td>';
                $html .='<td>Comments if any ( by cargo owner )</td>';
                $html .='<td>'.substr($FreightEstimate[$k-1]->estimate_comment, 0, 20).'</td>';
                $html .='<td>'.substr($FreightEstimate[$k]->estimate_comment, 0, 20).'</td>';
                $html .='</tr>';
            }
        } 
        $k++;
    }
        
    $RowStatus='';
    $l=0;
        
    foreach($Invitees1 as $inv) {
        if($inv->RowStatus==2) {
            $RowStatus='Edit';
        }
        if($inv->RowStatus==2) {
                
            if($Invitees1[$l-1]->InvPriorityStatus==$Invitees1[$l]->InvPriorityStatus) {
                   //-----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
                $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Invitees</td>';
                $html .='<td>Add Invitees</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Select invitees</td>';
                $html .='<td>'.$Invitees1[$l-1]->InvPriorityStatus.'</td>';
                $html .='<td>'.$Invitees1[$l]->InvPriorityStatus.'</td>';
                $html .='</tr>';
            }
                
                
            if($Invitees1[$l-1]->AdverseComments==$Invitees1[$l]->AdverseComments) {
                //-----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
                $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Invitees</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by cargo owner</td>';
                $html .='<td>'.substr($Invitees1[$l-1]->AdverseComments, 0, 20).'</td>';
                $html .='<td>'.substr($Invitees1[$l]->AdverseComments, 0, 20).'</td>';
                $html .='</tr>';
            }
                
            if($Invitees1[$l-1]->Comments==$Invitees1[$l]->Comments) {
                //-----do nothing----
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($inv->UserDate)).'</td>';
                $html .='<td>'.$inv->FirstName.' '.$inv->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Invitees</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by Invitees</td>';
                $html .='<td>'.substr($Invitees1[$l-1]->Comments, 0, 20).'</td>';
                $html .='<td>'.substr($Invitees1[$l]->Comments, 0, 20).'</td>';
                $html .='</tr>';
            }
        }
        $l++;
    }
        
    $RowStatus='';
    $m=0;
    foreach($Alerts as $alrt) {
            
        if($alrt->RowStatus==2) {
            $RowStatus='Edit';
        }
            
        if($alrt->RowStatus==2) {
            if($Alerts[$m-1]->CommenceAlertFlag==$Alerts[$m]->CommenceAlertFlag) {
                  //---do nothing--
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Set commencement date</td>';
                $html .='<td>'.$Alerts[$m-1]->CommenceAlertFlag.'</td>';
                $html .='<td>'.$Alerts[$m]->CommenceAlertFlag.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionCommences==$Alerts[$m]->AuctionCommences) {
                //---do nothing--
            } else {
                if($Alerts[$m-1]->AuctionCommences==1) {
                     $AuctionCommencesFrom='Days before laycan start date';
                }
                if($Alerts[$m-1]->AuctionCommences==2) {
                    $AuctionCommencesFrom='Defined date';
                }
                if($Alerts[$m]->AuctionCommences==1) {
                    $AuctionCommencesTo='Days before laycan start date';
                }
                if($Alerts[$m]->AuctionCommences==2) {
                    $AuctionCommencesTo='Defined date';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Bid commences on</td>';
                $html .='<td>'.$AuctionCommencesFrom.'</td>';
                $html .='<td>'.$AuctionCommencesTo.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->CommenceDaysBefore==$Alerts[$m]->CommenceDaysBefore) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Days before bid commences</td>';
                $html .='<td>'.$Alerts[$m-1]->CommenceDaysBefore.'</td>';
                $html .='<td>'.$Alerts[$m]->CommenceDaysBefore.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->CommenceDate==$Alerts[$m]->CommenceDate) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Bid commencement date is</td>';
                $html .='<td>'.$Alerts[$m-1]->CommenceDate.'</td>';
                $html .='<td>'.$Alerts[$m]->CommenceDate.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionValidity==$Alerts[$m]->AuctionValidity) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Bid validity (days)</td>';
                $html .='<td>'.$Alerts[$m-1]->AuctionValidity.'</td>';
                $html .='<td>'.$Alerts[$m]->AuctionValidity.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionCeases==$Alerts[$m]->AuctionCeases) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Bid ceases on (date)</td>';
                $html .='<td>'.$Alerts[$m-1]->AuctionCeases.'</td>';
                $html .='<td>'.$Alerts[$m]->AuctionCeases.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->LayCanStartDate==$Alerts[$m]->LayCanStartDate) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>First loadport laycan start date</td>';
                $html .='<td>'.$Alerts[$m-1]->LayCanStartDate.'</td>';
                $html .='<td>'.$Alerts[$m]->LayCanStartDate.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionCommenceDefinedDate==$Alerts[$m]->AuctionCommenceDefinedDate) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Commencement</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Bid commencement date</td>';
                $html .='<td>'.$Alerts[$m-1]->AuctionCommenceDefinedDate.'</td>';
                $html .='<td>'.$Alerts[$m]->AuctionCommenceDefinedDate.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AlertBeforeCommence==$Alerts[$m]->AlertBeforeCommence) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>alert schedule (days commencement)</td>';
                $html .='<td>'.$Alerts[$m-1]->AlertBeforeCommence.'</td>';
                $html .='<td>'.$Alerts[$m]->AlertBeforeCommence.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AlertBeforeClosing==$Alerts[$m]->AlertBeforeClosing) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>alert schedule (before closing)</td>';
                $html .='<td>'.$Alerts[$m-1]->AlertBeforeClosing.'</td>';
                $html .='<td>'.$Alerts[$m]->AlertBeforeClosing.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AlertNotificationCommence==$Alerts[$m]->AlertNotificationCommence) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Alert notification for commencement</td>';
                $html .='<td>'.$Alerts[$m-1]->AlertNotificationCommence.'</td>';
                $html .='<td>'.$Alerts[$m]->AlertNotificationCommence.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AlertNotificationClosing==$Alerts[$m]->AlertNotificationClosing) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Alert notification for closing</td>';
                $html .='<td>'.$Alerts[$m-1]->AlertNotificationClosing.'</td>';
                $html .='<td>'.$Alerts[$m]->AlertNotificationClosing.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->IncludeClosing==$Alerts[$m]->IncludeClosing) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Alerts</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Closing dates in invitation</td>';
                $html .='<td>'.$Alerts[$m-1]->IncludeClosing.'</td>';
                $html .='<td>'.$Alerts[$m]->IncludeClosing.'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->AuctionerComments==$Alerts[$m]->AuctionerComments) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by cargo owner</td>';
                $html .='<td>'.substr($Alerts[$m-1]->AuctionerComments, 0, 20).'</td>';
                $html .='<td>'.substr($Alerts[$m]->AuctionerComments, 0, 20).'</td>';
                $html .='</tr>';
            }
                
            if($Alerts[$m-1]->InviteesComments==$Alerts[$m]->InviteesComments) {
                //---do nothing--
            } else {
                    
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Alerts</td>';
                $html .='<td>Comments</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Comments by Invitees</td>';
                $html .='<td>'.substr($Alerts[$m-1]->InviteesComments, 0, 20).'</td>';
                $html .='<td>'.substr($Alerts[$m]->InviteesComments, 0, 20).'</td>';
                $html .='</tr>';
            }
        }
            
        $m++;
    }
    $bbk=0;
    foreach($bac_brokerage as $bb) {
            
        $RowStatus='Edit';    
            
        if($bb->RowStatus==2) { 
            if($bac_brokerage[$bbk-1]->PayingEntityType==$bac_brokerage[$bbk]->PayingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Transaction type</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PayingEntityType.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PayingEntityType.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_brokerage[$bbk-1]->PayingEntityName==$bac_brokerage[$bbk]->PayingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Name</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PayingEntityName.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PayingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_brokerage[$bbk-1]->ReceivingEntityType==$bac_brokerage[$bbk]->ReceivingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Type</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->ReceivingEntityType.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->ReceivingEntityType.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_brokerage[$bbk-1]->ReceivingEntityName==$bac_brokerage[$bbk]->ReceivingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Receiving Entity Name</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->ReceivingEntityName.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->ReceivingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_brokerage[$bbk-1]->BrokerName==$bac_brokerage[$bbk]->BrokerName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Broker Name</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->BrokerName.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->BrokerName.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->PayableAs==$bac_brokerage[$bbk]->PayableAs) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage payable as</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PayableAs.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PayableAs.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->PercentageOnFreight==$bac_brokerage[$bbk]->PercentageOnFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage % on freight</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PercentageOnFreight.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PercentageOnFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->PercentageOnDeadFreight==$bac_brokerage[$bbk]->PercentageOnDeadFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage % on dead freight</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PercentageOnDeadFreight.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PercentageOnDeadFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->PercentageOnDemmurage==$bac_brokerage[$bbk]->PercentageOnDemmurage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage % on demurrage</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PercentageOnDemmurage.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PercentageOnDemmurage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->PercentageOnOverage==$bac_brokerage[$bbk]->PercentageOnOverage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage % on overage qty</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->PercentageOnOverage.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->PercentageOnOverage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->LumpsumPayable==$bac_brokerage[$bbk]->LumpsumPayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Lump sum Payable</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->LumpsumPayable.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->LumpsumPayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->RatePerTonnePayable==$bac_brokerage[$bbk]->RatePerTonnePayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Rate Per Tonne Payable</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->RatePerTonnePayable.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->RatePerTonnePayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_brokerage[$bbk-1]->BACComment==$bac_brokerage[$bbk]->BACComment) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Brokerage</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Brokerage BAC Comment</td>';
                $html .='<td>'.$bac_brokerage[$bbk-1]->BACComment.'</td>';
                $html .='<td>'.$bac_brokerage[$bbk]->BACComment.'</td>';
                $html .='</tr>';
            }
                    
        } 
        $bbk++;
    } 
        
        
    $bbk=0;
    foreach($bac_addCom as $bb) {
        $RowStatus='Edit';
        if($bb->RowStatus==2) { 
            if($bac_addCom[$bbk-1]->PayingEntityType==$bac_addCom[$bbk]->PayingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Transaction type</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PayingEntityType.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PayingEntityType.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PayingEntityName==$bac_addCom[$bbk]->PayingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Name</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PayingEntityName.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PayingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_addCom[$bbk-1]->ReceivingEntityType==$bac_addCom[$bbk]->ReceivingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Type</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->ReceivingEntityType.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->ReceivingEntityType.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_addCom[$bbk-1]->ReceivingEntityName==$bac_addCom[$bbk]->ReceivingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Receiving Entity Name</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->ReceivingEntityName.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->ReceivingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_addCom[$bbk-1]->BrokerName==$bac_addCom[$bbk]->BrokerName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Broker Name</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->BrokerName.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->BrokerName.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PayableAs==$bac_addCom[$bbk]->PayableAs) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm payable as</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PayableAs.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PayableAs.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PercentageOnFreight==$bac_addCom[$bbk]->PercentageOnFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm % on freight</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PercentageOnFreight.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PercentageOnFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PercentageOnDeadFreight==$bac_addCom[$bbk]->PercentageOnDeadFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm % on dead freight</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PercentageOnDeadFreight.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PercentageOnDeadFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PercentageOnDemmurage==$bac_addCom[$bbk]->PercentageOnDemmurage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm % on demurrage</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PercentageOnDemmurage.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PercentageOnDemmurage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->PercentageOnOverage==$bac_addCom[$bbk]->PercentageOnOverage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm % on overage qty</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->PercentageOnOverage.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->PercentageOnOverage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->LumpsumPayable==$bac_addCom[$bbk]->LumpsumPayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Lump sum Payable</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->LumpsumPayable.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->LumpsumPayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->RatePerTonnePayable==$bac_addCom[$bbk]->RatePerTonnePayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Rate Per Tonne Payable</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->RatePerTonnePayable.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->RatePerTonnePayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_addCom[$bbk-1]->BACComment==$bac_addCom[$bbk]->BACComment) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Add Comm</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Add Comm BAC Comment</td>';
                $html .='<td>'.$bac_addCom[$bbk-1]->BACComment.'</td>';
                $html .='<td>'.$bac_addCom[$bbk]->BACComment.'</td>';
                $html .='</tr>';
            }        
        } 
        $bbk++;
    } 
        
    $bbk=0;
    foreach($bac_others as $bb) {
        $RowStatus='Edit';
        if($bb->RowStatus==2) { 
            if($bac_others[$bbk-1]->PayingEntityType==$bac_others[$bbk]->PayingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Transaction type</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PayingEntityType.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PayingEntityType.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PayingEntityName==$bac_others[$bbk]->PayingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Name</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PayingEntityName.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PayingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_others[$bbk-1]->ReceivingEntityType==$bac_others[$bbk]->ReceivingEntityType) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Paying Entity Type</td>';
                $html .='<td>'.$bac_others[$bbk-1]->ReceivingEntityType.'</td>';
                $html .='<td>'.$bac_others[$bbk]->ReceivingEntityType.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_others[$bbk-1]->ReceivingEntityName==$bac_others[$bbk]->ReceivingEntityName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Receiving Entity Name</td>';
                $html .='<td>'.$bac_others[$bbk-1]->ReceivingEntityName.'</td>';
                $html .='<td>'.$bac_others[$bbk]->ReceivingEntityName.'</td>';
                $html .='</tr>';
                    
            }
                
            if($bac_others[$bbk-1]->BrokerName==$bac_others[$bbk]->BrokerName) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Broker Name</td>';
                $html .='<td>'.$bac_others[$bbk-1]->BrokerName.'</td>';
                $html .='<td>'.$bac_others[$bbk]->BrokerName.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PayableAs==$bac_others[$bbk]->PayableAs) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others payable as</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PayableAs.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PayableAs.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PercentageOnFreight==$bac_others[$bbk]->PercentageOnFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others % on freight</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PercentageOnFreight.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PercentageOnFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PercentageOnDeadFreight==$bac_others[$bbk]->PercentageOnDeadFreight) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others % on dead freight</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PercentageOnDeadFreight.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PercentageOnDeadFreight.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PercentageOnDemmurage==$bac_others[$bbk]->PercentageOnDemmurage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others % on demurrage</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PercentageOnDemmurage.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PercentageOnDemmurage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->PercentageOnOverage==$bac_others[$bbk]->PercentageOnOverage) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others % on overage qty</td>';
                $html .='<td>'.$bac_others[$bbk-1]->PercentageOnOverage.'</td>';
                $html .='<td>'.$bac_others[$bbk]->PercentageOnOverage.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->LumpsumPayable==$bac_others[$bbk]->LumpsumPayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Lump sum Payable</td>';
                $html .='<td>'.$bac_others[$bbk-1]->LumpsumPayable.'</td>';
                $html .='<td>'.$bac_others[$bbk]->LumpsumPayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->RatePerTonnePayable==$bac_others[$bbk]->RatePerTonnePayable) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Rate Per Tonne Payable</td>';
                $html .='<td>'.$bac_others[$bbk-1]->RatePerTonnePayable.'</td>';
                $html .='<td>'.$bac_others[$bbk]->RatePerTonnePayable.'</td>';
                $html .='</tr>';
            }
                
            if($bac_others[$bbk-1]->BACComment==$bac_others[$bbk]->BACComment) { 
                //---do nothing------    
            } else {
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($bb->UserDate)).'</td>';
                $html .='<td>'.$bb->FirstName.' '.$bb->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo & Ports</td>';
                $html .='<td>BAC >> Others</td>';
                $html .='<td>'.$RowStatus.'</td>';
                $html .='<td>Others BAC Comment</td>';
                $html .='<td>'.$bac_others[$bbk-1]->BACComment.'</td>';
                $html .='<td>'.$bac_others[$bbk]->BACComment.'</td>';
                $html .='</tr>';
            }        
        } 
        $bbk++;
    }
        
    $n=0;
    foreach($Auction as $ac) {
        if($ac->RowStatus==2) {
            if($Auction[$n-1]->auctionStatus==$Auction[$n]->auctionStatus) {
                //---do nothing---
            } else {
                if($Auction[$n-1]->auctionStatus=='P') {
                    $auctionStatusFrom='Pending';
                }
                if($Auction[$n-1]->auctionStatus=='C') {
                    $auctionStatusFrom='Complete';
                }
                if($Auction[$n]->auctionStatus=='P') {
                    $auctionStatusTo='Pending';
                }
                if($Auction[$n]->auctionStatus=='C') {
                    $auctionStatusTo='Complete';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Status</td>';
                $html .='<td>-</td>';
                $html .='<td>Edit</td>';
                $html .='<td>Cargo Status</td>';
                $html .='<td>'.$auctionStatusFrom.'</td>';
                $html .='<td>'.$auctionStatusTo.'</td>';
                $html .='</tr>';
            }
                
            if($Auction[$n-1]->auctionExtendedStatus==$Auction[$n]->auctionExtendedStatus) {
                //---do nothing---
            } else {
                if($Auction[$n-1]->auctionExtendedStatus=='PNR') {
                    $auctionExtendedStatusFrom='Pending release';
                }
                if($Auction[$n-1]->auctionExtendedStatus=='A') {
                    $auctionExtendedStatusFrom='Activated';
                }
                if($Auction[$n-1]->auctionExtendedStatus=='W') {
                    $auctionExtendedStatusFrom='Withdrawn';
                }
                if($Auction[$n]->auctionExtendedStatus=='PNR') {
                    $auctionExtendedStatusTo='Pending release';
                }
                if($Auction[$n]->auctionExtendedStatus=='A') {
                    $auctionExtendedStatusTo='Activated';
                }
                if($Auction[$n]->auctionExtendedStatus=='W') {
                    $auctionExtendedStatusTo='Withdrawn';
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($alrt->UserDate)).'</td>';
                $html .='<td>'.$alrt->FirstName.' '.$alrt->LastName.'</td>';
                $html .='<td>'.$EntityName.'</td>';
                $html .='<td>Cargo Status</td>';
                $html .='<td>-</td>';
                $html .='<td>Edit</td>';
                $html .='<td>Set cargo release status</td>';
                $html .='<td>'.$auctionExtendedStatusFrom.'</td>';
                $html .='<td>'.$auctionExtendedStatusTo.'</td>';
                $html .='</tr>';
            }
                
        }
            
        $n++;
    }
    echo $html;
}
    
public function getAuctionStatus()
{
    $data=$this->masters_model->getAuctionStatus();
    echo json_encode($data);
}
    
    
public function cloneModelById()
{
    $data=$this->masters_model->cloneModelById();
    echo $data;
}
    
public function saveClauseData()
{
    $data=$this->masters_model->saveClauseData();
    echo $data;
}
    
public function getDocumentClauses()
{
    $data=$this->masters_model->getDocumentClauses();
    //print_r($data); die;
        
    $i=1;
    $html='';
    $inhtml='';
    $status='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        //$chekbox="<input type='checkbox' class='chkNumber' value='".$row->ClauseID."' />";
        //$img="<img src='img/view-icon.png' style='width: 20px;'  ></img>";     
        $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->ClauseID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
        $delete="<a href='javascript: void(0);' onclick='deleteDocument(".$row->ClauseID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        $html_view="<a href='javascript: void(0);' onclick='getClauseText(".$row->ClauseID.")' title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
        if($row->SubClauseFlag==0) {
            $SubClauseFlag='No';
        }
        if($row->SubClauseFlag==1) {
            $SubClauseFlag='Yes';
        }
        if($row->Editable==1) {
            $Editable='Yes';
        } else {
            $Editable='No';
        }
            
            $last_clause='No';
            $completed_by='-';
            $completed_datetime='-';
        if($row->last_clause==1) {
                $last_clause='Yes';
                $completed_by=$row->completed_by;
                $completed_datetime=date('d-m-Y H:i:s', strtotime($row->completed_date));
        }
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->SerialNo.'","'.$row->ClauseNo.'","'.$row->CaluseName.'","'.$SubClauseFlag.'","'.$row->SubClauseNo.'","'.$Editable.'","'.$last_clause.'","'.$completed_by.'","'.$completed_datetime.'","'.$row->FirstName.' '.$row->LastName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'&nbsp;&nbsp;'.$html_view.'"],';
            
            $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getDocumentClausesById()
{
    $data['res']=$this->masters_model->getDocumentClausesById();
    $data['txt']=$this->masters_model->getClausesText();
    echo json_encode($data);
}
    
public function updateClauseData()
{
    $data=$this->masters_model->updateClauseData();
}
    
public function clauseDelete()
{
    $data=$this->masters_model->clauseDelete();
    echo $data;
}
    
public function getClausesText()
{
    $data=$this->masters_model->getClausesText();
    //$html='<h6><b>CLAUSE '.$data->ClauseNo.' - '.$data->CaluseName.'</b></h6>';
    $html=$data;
    //print_r($data);die;
    echo $html;
}
    
public function getRecordOwnerEntity()
{
    //echo 'test'; die;
    $res=$this->masters_model->getRecordOwnerEntity();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->FirstName.' '.$row->LastName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function deleteAttactedFile()
{
    echo $this->masters_model->deleteAttactedFile();
}
    
public function deleteAttactedLogo()
{
    echo $this->masters_model->deleteAttactedLogo();
}
    
public function getFixNotTemplate()
{
    $data=$this->masters_model->getFixNotTemplate();
        
    $i=1;
    $html='';
    $inhtml='';
    $status='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        if($row->Status==1) {
            $status="Active";
        } else {
            $status="Inactive";
        }
        if($row->TemplateID==1) {
            //$check="<input class='chk' type='checkbox' name='TempID[]' value='".$row->TemplateID."' disabled>";
            $edit="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $config="<a href='javascript: void(0);' title='Click here to configure record'><i class='fa fa-cogs fa_congig'></i></a>";
        } else {
            //$check="<input class='chk' type='checkbox' name='TempID[]' value='".."'>";
            $edit="<a href='javascript: void(0);' onclick='editModel(".$row->TemplateID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteModel(".$row->TemplateID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $config="<a href='javascript: void(0);' onclick='configuration(".$row->TemplateID.")' title='Click here to configure record'><i class='fa fa-cogs fa_congig'></i></a>";
        }
            $html_view="<a href='javascript: void(0);' onclick='HtmlView(".$row->TemplateID.")' title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'","'.$row->TName.'","'.$status.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$config.'&nbsp;&nbsp;'.$delete.'&nbsp;&nbsp;'.$html_view.'"],';
            
            $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function saveFixNoteTemplate()
{
    $data=$this->masters_model->saveFixNoteTemplate();
    echo $data;
}    
    
public function getFixNoteTemplate()
{
    $data=$this->masters_model->getFixNoteTemplate();
    echo json_encode($data);
}
    
public function updateFixNoteTemplate()
{
    $data=$this->masters_model->updateFixNoteTemplate();
}
    
public function deleteFixTemplateNoteById()
{
    $data=$this->masters_model->deleteFixTemplateNoteById();
}
    
public function getFixNotTemplateConfiguration()
{
    $data['configure']=$this->masters_model->getFixNotTemplateConfiguration();
    $data['all_cp_text']=$this->masters_model->getFixNotTemplateCpText();
    $data['cp_type']=$this->masters_model->getCpType();
    echo json_encode($data);
}
    
public function saveFixNoteTemplateConfigration()
{
    $data=$this->masters_model->saveFixNoteTemplateConfigration();
}
    
public function getFixReportTemplate()
{
    $data=$this->masters_model->getFixReportTemplate();
    echo json_encode($data);
}
    
    
public function getFixTemplateNoteHtmlById()
{
    $this->load->model('cargo_model', '', true); 
    $data['Document']=$this->masters_model->getFixTemplateNoteHtmlById();
    $data['cp_type']=$this->masters_model->getCpType();
    $data['cp_text']=$this->masters_model->getCpText();
    $temp=$this->masters_model->getDocumentLogo();
    $owner=$this->masters_model->getFixTemplateOwner();
        
    $Entity=$this->cargo_model->getEntityById($owner->EntityID);
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
        
    if($temp->Logo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$temp->Logo, 3600);
    } else {
        $url='';
    }
    $data['DocumentLogo']=array('Title'=>$temp->DocName,'Logo'=>$url,'EntityName'=>$Entity->EntityName);
    //print_r($data['DocumentLogo']); die;
    echo json_encode($data);
}
    
public function fixNotePdfDownload()
{
    include_once APPPATH.'third_party/mpdf.php';
        
    $temp=$this->masters_model->getDocumentLogo();
    $data=$this->masters_model->getFixTemplateNoteDownload();
    $cp_type=$this->masters_model->getCpType();
    $cp_text=$this->masters_model->getCpText();
    //print_r($data);die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$temp->Logo, 3600);
        
    //$html1='<span style="font-size: 15px;">'.$temp->DocName.'</span>';
    //$html1 .='<img src="'.$url.'" style="width: 15%; margin-left: 80%; margin-top: -3%;"></img>';
    $html='';
    //$html .=$html1;
    $html .='<p style="text-align: center; font-size:20px;" >FIXTURE NOTE</p>';
    if($cp_type->Type==1) {
        $html .='<table style="width: 100%; border-collapse: collapse;"><tr><th style="border: 1px solid; width: 15%; text-align:left; ">CpCode</th><th style="border: 1px solid; width: 40%; text-align:left; " >Field name(label)</th><th style="border: 1px solid; width: 45%; text-align:left; " >Field value</th></tr>';
        foreach($data as $row) {
            if($row->Included==1) {
                $html .='<tr><td style="border: 1px solid; width: 15%; ">'.$row->CpCode.'</td><td style="border: 1px solid; width: 45%;">'.$row->NewDisplayName.'</td><td style="border: 1px solid; width: 40%;">values comes here, if data exist.</td></tr>';
            }
        }
        $html .='</table>';
    } else if($cp_type->Type==2) {
        foreach($data as $row) {
            if($row->Included==1) {
                $html .='<p>'.$row->NewDisplayName.' :values comes here, if data exist.</p>';
            }
        }
    }
        
    $html .='<br><p>'.$cp_text->CpText.'</p>';
    //echo $html; die;
    $pdfFilePath = "htmlfixturedocument.pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D"); 
}
    
public function view_documenttype_attached()
{
    $row=$this->masters_model->getDocumentTypeFileName();
    //echo $filename;
    $filename=$row->CharterPartyPdf;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
    //print_r($url); die;
    $nar=explode("?", $url);
    $data=current($nar);
    $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
    echo $html;
}
    
public function view_entity_attached_logo()
{
    $this->load->model('cargo_model', '', true);
    $EntityID=$this->input->post('EntityID');
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($EntityID);
    //echo $filename;
    $filename=$entity_detail->AttachedLogo;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$filename, 3600);
        
    $html='<img src='.$url.'&embedded=true" style="max-height: 50px;" />';
    echo $html;
}
    
public function view_documenttype_logo()
{
    $row=$this->masters_model->getDocumentTypeFileName();
    //echo $filename;
    $filename=$row->Logo;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$filename, 3600);
        
    $html='<img src="'.$url.'" style="max-height: 50px;" >';
    echo $html;
}
    
public function getAttachedPdf()
{
    $data=$this->masters_model->getAttachedPdf();
    //print_r($data); die;
    echo json_encode($data);
}
    
public function saveServerContent()
{
    $flag=$this->masters_model->saveServerContent();
    echo $flag;
}
    
public function getProjectVersion()
{
    $flag=$this->masters_model->getProjectVersion();
    echo $flag;
}
    
public function get_all_updatecontent()
{
    $data=$this->masters_model->get_all_updatecontent();
    //$this->output->enable_profiler();
    //print_r($data);die;
    $html='';
    $inhtml='';
    $status='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        $TestedOnServer1='';
        $TestedOnServer2='';
        if($row->TestedOnServer1=='1') {
            $TestedOnServer1='Yes';
        } else {
            $TestedOnServer1='No';
        }
        if($row->TestedOnServer2=='1') {
            $TestedOnServer2='Yes';
        } else {
            $TestedOnServer2='No';
        }
            //$check="<input class='chkNumber' type='checkbox' name='testVersion[]' value='".$row->TestTableID."'>";
            $view="<a href='#' onclick='getChangeContent(".$row->TestTableID.")'>view</a>";
            
            $edit="<a href='javascript: void(0);' onclick='editServerContent(".$row->TestTableID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteServerContent(".$row->TestTableID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ChangesDateTime)).'","'.$row->TestVersion.'","'.$TestedOnServer1.'","'.$TestedOnServer2.'","'.$row->ByUser.'","'.$view.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
    
}
    
public function getContentByTestTableId()
{
    $data=$this->masters_model->getContentByTestTableId();
    echo $data->ServerChanges;
}
    
public function deleteServerContentById()
{
    $flag=$this->masters_model->deleteServerContentById();
    echo $flag;
    
}
    
public function getContentDataById()
{
    $data['Record']=$this->masters_model->getContentDataById();
    echo json_encode($data);
}
    
public function updateServerContent()
{
    $flag=$this->masters_model->updateServerContent();
    echo $flag;
}
    
    
    
public function getNotification()
{
    $UserType=$this->input->get('UserType');
    $data=$this->masters_model->getNotification();
    $i=1;
    $inhtml='';
    $html='{ "aaData": [';
    //print_r($data); die;
    foreach($data as $row) {
        if($row->Status==1) {
            $status='Active';
        } else {
            $status='Inactive';
        }
        if($row->SelectType==1) {
            $type='Holiday';
        }
        if($row->SelectType==2) {
            $type='System maintenance';
        }
        if($row->SelectType==3) {
            $type='Software update';
        }
        if($row->SelectType==4) {
            $type='Other';
        }
        if($row->MessageType==1) {
            $MessageType='Generic (visible to all)';
        }
        if($row->MessageType==2) {
            $MessageType='Company specific';
        }
        if($row->MessageType==3) {
            $MessageType='other parent group entity';
        }
        
        $time1=strtotime($row->MessageDisplayFrom);
        $time2=strtotime($row->MessageDisplayTo);
        $time=strtotime(date('Y-m-d'));
        $flag=0;
        if($time1 < $time && $time < $time2) {
            $flag=1;
        } 
        
        if($UserType=='A') {
            //$check="<input class='chkNumber' type='checkbox' name='nid[]' value='".$row->NID.'_'.$flag."' >";
            $edit="<a href='javascript: void(0);' onclick=editNotification('".$row->NID.'_'.$flag."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick=deleteNotification('".$row->NID.'_'.$flag."') title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        } else if(date('Y-m-d', strtotime($row->MessageDisplayTo)) < date('Y-m-d')) {
            //$check="<input class='chkNumber' type='checkbox' name='nid[]' value='".$row->NID.'_'.$flag."' disabled>";
            $edit="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        } else {
            //$check="<input class='chkNumber' type='checkbox' name='nid[]' value='".$row->NID.'_'.$flag."' >";
            $edit="<a href='javascript: void(0);' onclick=editNotification('".$row->NID.'_'.$flag."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick=deleteNotification('".$row->NID.'_'.$flag."') title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        }
        
        
        $view="<a href='#' onclick='getNotificationDetails(".$row->NID.")' >view</a>";
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'","'.$status.'","'.date('d-m-Y H:i:s', strtotime($row->MessageDisplayFrom)).'","'.date('d-m-Y H:i:s', strtotime($row->MessageDisplayTo)).'","'.$type.'","'.$MessageType.'","'.$view.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
        $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
    
public function getNotificationDetailsById()
{
    $data=$this->masters_model->getNotificationDetailsById();
        
    echo json_encode($data);
}
    
public function getUsersByEntityID()
{
    //echo 'test'; die;
    $res=$this->masters_model->getUsersByEntityID();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->FirstName.' '.$row->LastName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function addNotification()
{
    $data=$this->masters_model->addNotification();
    echo $data;
}
    
public function getGenericNotification()
{
    $data=$this->masters_model->getGenericNotification();
    if($data) {
        echo json_encode($data);
    } else {
        echo 1;
    }
        
}
    
public function getEntityWiseNotification()
{
    $data=$this->masters_model->getEntityWiseNotification();
    if($data) {
        echo json_encode($data);
    } else {
        echo 1;
    }
        
}
    
public function getUserWiseNotification()
{
    $data=$this->masters_model->getUserWiseNotification();
    if($data) {
        echo json_encode($data);
    } else {
        echo 1;
    }
        
}
    
public function deleteNotificationById()
{
    $data=$this->masters_model->deleteNotificationById();
    echo $data;
}
    
    
public function getMessagesByMessageDetail()
{
    $data['MsgDetails']=$this->masters_model->getMessagesByMessageDetail();
    echo json_encode($data);
}
    
public function markAsReadAndUnread()
{
    $data=$this->masters_model->markAsReadAndUnread();
    echo $data;
}
    
    
    
public function downloadTermCondition()
{
    $data=$this->masters_model->downloadTermCondition();
    $TermCondition=$this->masters_model->getTermConditionByID($data->EntityID);
    if($TermCondition->FileIn==1) {
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $html=$TermCondition->TermText;
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdfFilePath = "TermsConditions.pdf";
        $pdf->Output($pdfFilePath, "D");
        //$TermCondition->TermText;
    } else if($TermCondition->FileIn==2) {
        $this->load->helper('download');
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $filename=$TermCondition->TermPdf;
        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
        $filedata = file_get_contents($url); // Read the file's contents 
        force_download('TermsConditions.pdf', $filedata);
    }
}
    
public function getAllIndividualCp()
{
    $data=$this->masters_model->getAllIndividualCp();
    $data1=$this->masters_model->getAllIndividualCpc();
    echo $data->ClauseType;
}
public function getAllIndividualCp1()
{
    $data=$this->masters_model->getAllIndividualCp();
    $data1=$this->masters_model->getAllIndividualCpc();
    echo $data->ClauseType.'_____'.count($data1);
}
    
public function getDocumentByEntityid()
{
    $data=$this->masters_model->getDocumentByEntityid();
    //print_r($data); die;
    $i=1;
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $check="<input class='chkNumber' type='checkbox' name='DMID[]' value='".$row->DMID."'>";
        if($row->Status==1) {
            $status='Active';
        } else {
            $status='Inactive';
        }
        $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->DMID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
        $delete="<a href='javascript: void(0);' onclick='deleteDocument(".$row->DMID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'","'.$row->DocType.'","'.$row->DocName.'","'.$status.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
        $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
        
}
    
public function saveDocumentMaster()
{
    $data=$this->masters_model->saveDocumentMaster();
    echo $data;
}
    
public function deleteDocumentMaster()
{
    $this->masters_model->deleteDocumentMaster();
}
    
public function getDocumentByDmid()
{
    $data=$this->masters_model->getDocumentByDmid();
    echo json_encode($data);
}
    
public function updateDocumentMaster()
{
    $data=$this->masters_model->updateDocumentMaster();
    echo $data;
}
    
public function getDocumentTypeTitleByEntityid()
{
    $data=$this->masters_model->getDocumentTypeTitleByEntityid();
    echo json_encode($data);
}
    
public function getDocumentTitleByDocumentType()
{
    $data=$this->masters_model->getDocumentTitleByDocumentType();
    echo json_encode($data);
}
    
public function checkEntityLogoExists()
{
    $this->load->model('cargo_model', '', true);
    $EntityID=$this->input->post('EntityID');
    $data['EntityDetails']=$this->cargo_model->getOwnerEntityDetailsByID($EntityID);
    echo json_encode($data);
}
    
public function getAllCountryRecord()
{
    $res=$this->masters_model->getAllCountryRecord();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Code: '.$row->Code.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getAllStateRecord()
{
    $res=$this->masters_model->getAllStateRecord();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Code: '.$row->Code.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function get_ParentUnique_Id($arg=null)
{
        
    $data['id']=mt_rand(1000, 9999).'-'.mt_rand(1000, 9999).'-'.mt_rand(1000, 9999);
    $flag=$this->masters_model->checkParentUniqueID($data['id']);
    if($flag) {
        //echo 1;
        $this->get_ParentUnique_Id();
    } else {
        echo json_encode($data);
    }
        
}
    
public function saveMyParentMaster()
{
    $res=$this->masters_model->saveMyParentMaster();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function getParentMasterData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $data3=$this->masters_model->getParentMasterMyCustomData();
        
    $html='';
    $inhtml='';
    $status='';
    $i=1;
    $len1=count($data3);
    //echo $len1; die;
    $html='{ "aaData": [';
        
    foreach($data3 as $row) {
        //$check="<input class='chkNumber' type='checkbox' name='parent_ids[]' value='".$row->PID."' >";
        $name="<span >".$row->GroupName."</span>";
        if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
            $datetime='-';
        }else{
            $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
        }
        $address=$row->Address1.' '.$row->Address2.' '.$row->Address3.' '.$row->Address4;
        $edit="<a href='javascript: void(0);' onclick='editParentEntity(".$row->PID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
        $clone="<a href='javascript: void(0);' onclick='cloneParentEntity(".$row->PID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
        $delete="<a href='javascript: void(0);' onclick='deleteMyParentEntity(".$row->PID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        $inhtml .='["'.$i.'","'.$datetime.'","'.$row->GroupName.'","'.$address.'","'.$row->CountryDescription.'","'.$row->Email.'","'.$row->Telephone1.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'"],';
            
        $i++; 
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
        
}
    
    
    
public function getParentMasterAppendData()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
        
    $html='';
    $inhtml='';
    $status='';
    $i=1;
        
    $data3=$this->masters_model->getParentMasterMyCustomAppendData();
    foreach($data3 as $row) {
        if($i<=50) {
            $i++;
            continue;
        }
        $check="<input class='chkNumber' type='checkbox' name='parent_ids[]' value='".$row->PID."' >";
        $name="<span >".$row->GroupName."</span>";
        if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
            $datetime='-';
        }else{
            $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
        }
            $edit="<a href='javascript: void(0);' onclick='editParentEntity(".$row->PID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='cloneParentEntity(".$row->PID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteMyParentEntity(".$row->PID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $address=$row->Address1.' '.$row->Address2.' '.$row->Address3.' '.$row->Address4;
            $inhtml .=$i.'_____'.$datetime.'_____'.$row->GroupName.'_____'.$address.'_____'.$row->CountryDescription.'_____'.$row->Email.'_____'.$row->Telephone1.'_____'.$row->EntityName.'_____'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'],';
            $i++;
    }
        
    $html .=trim($inhtml, "],");    
    echo $html;
        
}
    
    
    
public function getMyParentMasterData()
{
        
    $data['Parent']=$this->masters_model->getMyParentMasterData();
        
    $data['OtherIDs']=$this->masters_model->getMyParentOtherIDs($data['Parent']->CompanyID);
    $data['Email']=$this->masters_model->getMyParentEmail($data['Parent']->CompanyID);
    $data['Address']=$this->masters_model->getMyParentAddress($data['Parent']->CompanyID);
    echo json_encode($data);
}
    
public function get_ParentUniqueId($arg=null)
{
        
    $data['id']=mt_rand(1000, 9999).'-'.mt_rand(1000, 9999).'-'.mt_rand(1000, 9999);
    $flag=$this->masters_model->checkParentUniqueID($data['id']);
    if($flag) {
        //echo 1;
        $this->get_ParentUniqueId();
    } else {
        return $data['id'];
    }
        
}
        
public function updateParentMasterData()
{
    $entityID=$this->input->post('entityID');
    $data_row=$this->masters_model->getParentMasterRow();
    $res='';
    $flg=1;
    if($entityID==$data_row->EntityID || $entityID==1 ) {
        $res=$this->masters_model->updateParentMasterData();
    } else {
        $Company_ID=$this->get_ParentUniqueId();
        $res=$this->masters_model->addParentMasterData($Company_ID, $flg);
    }
        
        
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteOtherComIDs()
{
    $res=$this->masters_model->deleteOtherComIDs();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteCompanyEmail()
{
    $res=$this->masters_model->deleteCompanyEmail();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteCompanyTelephone()
{
    $res=$this->masters_model->deleteCompanyTelephone();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function view_parentcompany_logo()
{
    $row=$this->masters_model->getParentCompanyLogoName();
    //echo $filename;
    $filename=$row->AttachedLogo;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$filename, 3600);
        
    $html='<img src="'.$url.'" >';
    echo $html;
}
    
public function deleteCompanyLogo()
{
    echo $this->masters_model->deleteCompanyLogo();
}
    
public function deleteParentEntity()
{
    $entityID=$this->input->post('EntityID');
    $data_row=$this->masters_model->getParentMasterRow();
    $res='';
    if($entityID==$data_row->EntityID || $entityID==1) {
        echo $this->masters_model->deleteParentEntity();
    } else {
        echo 2;
    }
        
}
    
public function getAllParentCompanyRecord()
{
        
    $res=$this->masters_model->getAllParentCompanyRecord();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
            
        $data_arr['label']='Parent: '.$row->GroupName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function get_EntityUnique_Id($arg=null)
{
        
    $data['id']=mt_rand(1000, 9999).'-'.mt_rand(1000, 9999).'-'.mt_rand(1000, 9999);
    $flag=$this->masters_model->checkEntityUniqueID($data['id']);
    if($flag) {
        $this->get_EntityUnique_Id();
    } else {
        echo json_encode($data);
    }
        
}
    
public function get_EntityUniqueId()
{
        
    $data['id']=mt_rand(1000, 9999).'-'.mt_rand(1000, 9999).'-'.mt_rand(1000, 9999);
    $flag=$this->masters_model->checkEntityUniqueID($data['id']);
    if($flag) {
        $this->get_EntityUniqueId();
    } else {
        return $data['id'];
    }
        
}
    
public function fillParentCompanyAddress()
{
    $data=$this->masters_model->fillParentCompanyAddress();
    echo json_encode($data);
}
    
public function saveTermsConditions()
{
    $data=$this->masters_model->saveTermsConditions();
    echo $data;
}
    
public function getTermsConditions()
{
    $this->load->model('cargo_model', '', true);
    $this->load->model('cp_fn_model', '', true); 
    $data=$this->masters_model->getTermsConditions();
    //print_r($data); die;
    $i=1;
    $html='';
    $inhtml='';
    $html ='{ "aaData": [';
    foreach($data as $row) {
        $Entity=$this->cargo_model->getEntityById($row->EntityID);
        $user=$this->cp_fn_model->getUserByID($row->CreatedBy);
        $updated=$this->cp_fn_model->getUserByID($row->UpdateBy);
        if($row->Application==1) {
            $application='User log in';
        }else if($row->Application==2) {
            $application='Quote submission';
        }
            
        if($row->Status==1) {
            $status='Pending';
        }else if($row->Status==2) {
            $status='Complete';
        }else if($row->Status==3) {
                $status='Active';
        }else if($row->Status==4) {
            $status='Inactive';
        }
            
        if($row->link==1) {
            $link='Yes';
        }else{
            $link='No';
        }
            
            //$check="<input class='chkNumber' type='checkbox' value='".$row->TCID."'>";
            $view="<a href='#' onclick='getClauseText(".$row->TCID.",".$row->FileIn.")' >view</a>";
            $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->TCID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteDocument(".$row->TCID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'","'.$application.'","'.$row->UniqueID.'","'.$row->Version.'","'.$link.'","'.$status.'","'.$view.'","'.$user->FirstName.' '.$user->LastName.'","'.$updated->FirstName.' '.$updated->LastName.'","'.$Entity->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
        
}
    
public function getTermsConditions1()
{
    $data=$this->masters_model->getTermsConditions();
    $d['cnt']=count($data);
    echo json_encode($d);
}
    
public function deleteTermsConditions()
{
    $data=$this->masters_model->deleteTermsConditions();
    echo $data;
}
    
public function getTermsConditionsById()
{
    $TCID=$this->input->post('TCID');
    $data['data']=$this->masters_model->getTermsConditionsById();
    $data['txt']=$this->masters_model->getTermsConditionsTextById($TCID);
    echo json_encode($data);
}
    
public function updateTermsConditions()
{
    $data=$this->masters_model->updateTermsConditions();
    echo $data;
}
    
public function getUserTermCondtion()
{
    $data=$this->masters_model->getUserTermCondtion();
    if($data=='yes' || $data==0) {
        echo $data;
    } else {
        if($data->FileIn==1) {
            $txt=$this->masters_model->getTermsConditionsTextById($data->TCID);
            //$txt=$data->TermText;
            $d['txturl']=$txt;
            $d['FileIn']=1;
            $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
            echo json_encode($d);
        } else if($data->FileIn==2) {
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);
            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$data->TermPdf, 3600);
            $u='http://docs.google.com/gview?url='.$url.'&embedded=true';
            $d['txturl']=$u;
            $d['FileIn']=2;
            $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
            echo json_encode($d);
            //echo json_encode($data);
        }
    }
}
    
public function get_all_document_type1()
{
    //echo 'test'; die;
    $res=$this->vessel_master_model->get_all_document_type_id();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentType: '.$row->DocumentType;
        $data_arr['value']=$row->DocumentTypeID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function SaveMyEntityMaster()
{
    $res=$this->masters_model->SaveMyEntityMaster();
    if($res) {
        echo $res;
    }else{
        echo 0;
    }
}
     
public function getEntityMasterData()
{
    $this->load->model('cargo_model', '', true); 
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $html='';
    $inhtml='';
    $status='';
    $html='{ "aaData": [';
    $i=1;
    $data3=$this->masters_model->getEntityMasterMyCustomData();
    //print_r($data3); die;
    foreach($data3 as $row) {
        if($row->InviteeEntityFlg==1) {
            $InviteeEntityFlg='Yes';
        } else {
            $InviteeEntityFlg='No';
        }
        //$check="<input class='chkNumber' type='checkbox' name='entity_ids[]' value='".$row->EID."' >";
        if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
            $datetime='-';
        }else{
            $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
        }
            
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            
        if($row->CountryID) {
                $Country=$this->masters_model->getCountryById($row->CountryID);
                $ctry=$Country->CountryDescription;
        } else {
                $ctry='';
        }
            $addresss='';
        if($row->Address1) {
            $addresss .=$row->Address1.' ';
        }
        if($row->Address2) {
            $addresss .=$row->Address2.' ';
        }
        if($row->Address3) {
            $addresss .=$row->Address3.' ';
        }
        if($row->Address4) {
            $addresss .=$row->Address4;
        }
            $edit="<a href='javascript: void(0);' onclick='editAssociatedEntity(".$row->EID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='cloneAssociatedEntity(".$row->EID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteMyAssociatedEntity(".$row->EID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            $inhtml .='["'.$i.'","'.$datetime.'","'.$row->GroupName.'","'.$row->EntityName.'","'.$InviteeEntityFlg.'","'.$ctry.'","'.$row->Email.'","'.$row->Telephone1.'","'.$addresss.'","'.$Entity->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
} 
    
public function getEntityMasterAppendData()
{
    $this->load->model('cargo_model', '', true); 
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $html='';
    $inhtml='';
    $status='';
    $i=1;
        
    $data3=$this->masters_model->getEntityMasterMyCustomAppendData();
    //print_r(count($data3)); die;
    foreach($data3 as $row) {
        if($i<=50) {
            $i++;
            continue;
        }
        if($row->InviteeEntityFlg==1) {
            $InviteeEntityFlg='Yes';
        } else {
            $InviteeEntityFlg='No';
        }
            //$check="<input class='chkNumber' type='checkbox' name='entity_ids[]' value='".$row->EID."' >";
        if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
            $datetime='-';
        }else{
            $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
        }
            
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            
        if($row->CountryID) {
                $Country=$this->masters_model->getCountryById($row->CountryID);
                $ctry=$Country->CountryDescription;
        } else {
                $ctry='';
        }
            $addresss='';
        if($row->Address1) {
            $addresss .=$row->Address1.' ';
        }
        if($row->Address2) {
            $addresss .=$row->Address2.' ';
        }
        if($row->Address3) {
            $addresss .=$row->Address3.' ';
        }
        if($row->Address4) {
            $addresss .=$row->Address4.' ';
        }
            
            $edit="<a href='javascript: void(0);' onclick='editAssociatedEntity(".$row->EID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick='cloneAssociatedEntity(".$row->EID.")' title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteMyAssociatedEntity(".$row->EID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .=$i.'_____'.$datetime.'_____'.$row->GroupName.'_____'.$row->EntityName.'_____'.$InviteeEntityFlg.'_____'.$ctry.'_____'.$row->Email.'_____'.$row->Telephone1.'_____'.$addresss.'_____'.$Entity->EntityName.'_____'.$edit.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$delete.'],';
            $i++;
    }
    $html .=trim($inhtml, "],");    
    echo $html;
}
    
     
public function getMyEntityMaster()
{
    $data['Entity']=$this->masters_model->getMyEntityMasterData();
    $data['EntityType']=$this->masters_model->getMyEntityType();
        
    $data['OtherIDs']=$this->masters_model->getMyEntityOtherIDs($data['Entity']->AssociateCompanyID);
    $data['Email']=$this->masters_model->getMyEntityEmail($data['Entity']->AssociateCompanyID);
    $data['Address']=$this->masters_model->getMyEntityTelephone($data['Entity']->AssociateCompanyID);
    $data['Bussiness']=$this->masters_model->getMyBussinessUnit();
    echo json_encode($data);
    
}
    
public function deleteOtherEntityComIDs()
{
    $res=$this->masters_model->deleteOtherEntityComIDs();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteAssociateComEmail()
{
    $res=$this->masters_model->deleteAssociateComEmail();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteAssociateComTelephone()
{
    $res=$this->masters_model->deleteAssociateComTelephone();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteAssociateEntityType()
{
    $res=$this->masters_model->deleteAssociateEntityType();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteAssociateComBusiness()
{
    $res=$this->masters_model->deleteAssociateComBusiness();
    if($res) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteEntityLogo()
{
    echo $this->masters_model->deleteEntityLogo();
}
    
public function view_entitycompany_logo()
{
    $row=$this->masters_model->getEntityCompanyLogoName();
    //echo $filename;
    $filename=$row->AttachedLogo;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$filename, 3600);
        
    $html='<img src="'.$url.'" >';
    echo $html;
}
    
public function updateMyEntityMaster()
{
    $EntityID=$this->input->post('EntityID');
    $EID=$this->input->post('EID');
    $data_row=$this->masters_model->getEntityMasterRow();
    $res='';
    $flg=1;
    if($EntityID==$data_row->EntityOwner || $EntityID==1 || $EntityID==$EID ) {
        $res=$this->masters_model->updateMyEntityMaster();
    } else {
        $Company_ID=$this->get_EntityUniqueId();
        $res=$this->masters_model->addEntityMasterData($Company_ID, $flg);
    }
    
    if($res) {
        echo $res;
    }else{
        echo 0;
    }
}
    
public function cloneMyEntityMaster()
{
    $Company_ID=$this->get_EntityUniqueId();
    $flg=2;
    $res=$this->masters_model->addEntityMasterData($Company_ID, $flg);
    if($res) {
        echo $res;
    }else{
        echo 0;
    }
}
    
public function deleteAssociateEntity()
{
    $EntityID=$this->input->post('EntityID');
    $data_row=$this->masters_model->getEntityMasterRow();
    $res='';
    if($EntityID==$data_row->EntityOwner || $EntityID==1 ) {
        $ret=$this->masters_model->deleteAssociateEntity();
        if($ret) {
            echo 1;
        } else {
            echo 2;
        }
    } else {
        echo 2;
    }
}
    
public function deleteMyUserMaster()
{
    $ret=$this->masters_model->deleteMyUserMaster();
    if($ret) {
        echo 1;
    } else {
        echo 2;
    }
        
}
    
public function getTermConditionAudit()
{
    $data=$this->masters_model->getTermConditionAudit();
    $inhtml ='';
    $html ='{ "aaData": [';
    $i=1;
    $j=0;
    foreach($data as $row){
        if($row->Application==1) {
            $app='User log in';
        }
        if($row->Application==2) {
            $app='Quote submission';
        }
        $a1="<a href='#' onclick='getClauseText(".$data[$j]->TCID.",".$data[$j]->FileIn.")'><b>".$data[$j]->Version."</b></a>";
        $a2="<a href='#' onclick='getClauseText(".$data[$j+1]->TCID.",".$data[$j+1]->FileIn.")'><b>".$data[$j+1]->Version."</b></a>";
        if(count($data)==$i) {
            $view=$a1;
        } else {
            $view=$a1.' - '.$a2;
        }
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ReadDate)).'","'.$row->FirstName.' '.$row->LastName.'","'.$row->DocumentType.'","'.$app.'","'.$row->Version.'","'.$view.'"],';
        $i++;
        $j++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getTermsConditionsTextPdfById()
{
    $FileIn=$this->input->post('FileIn');
    $TCID=$this->input->post('TCID');
    $Details=$this->masters_model->getTermsConditionsById();
    $d['header']=$Details->EntityName.' ( '.date('d-m-Y H:i:s', strtotime($Details->UserDate)).' ) ';
    if($FileIn==1) {
        $txt=$this->masters_model->getTermsConditionsTextPdfById($TCID, $FileIn);
        $d['txturl']=$txt;
        $d['FileIn']=1;
        $d['btn123']='<button type="button" class="btn btn-sm btn-primary" style="margin-top: -30px; margin-left: 94%; " data-dismiss="modal">Close</button><button type="button" class="btn btn-success btn-sm btn-grad" onclick="downloadTermsConditionsById('.$TCID.','.$FileIn.');" id="Download" style="margin-top: -30px; margin-left: 84%; " >Download</button><button type="button" class="btn btn-success btn-sm btn-grad" onclick="printTermsConditionsById('.$TCID.','.$FileIn.');" style="margin-top: -30px; margin-left: 78%; ">Print</button>';
        echo json_encode($d);
    } else if($FileIn==2) {
        $data=$this->masters_model->getTermsConditionsTextPdfById($TCID, $FileIn);
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$data->TermPdf, 3600);
        $u='http://docs.google.com/gview?url='.$url.'&embedded=true';
        $d['txturl']=$u;
        $d['FileIn']=2;
        $d['btn123']='<button type="button" class="btn btn-success btn-sm btn-grad" onclick="downloadTermsConditionsById('.$TCID.','.$FileIn.');" id="Download" style="margin-top: -30px; margin-left: 84%;">Download</button><button type="button" class="btn btn-sm btn-primary"  data-dismiss="modal" style="margin-top: -30px; margin-left: 94%;" >Close</button>';
        echo json_encode($d);
    }
}
    
public function downloadTermsConditionsById($TCID,$FileIn)
{
    if($FileIn==1) {
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $html=$this->masters_model->getTermsConditionsTextPdfById($TCID, $FileIn);
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdfFilePath = "TermsConditions.pdf";
        $pdf->Output($pdfFilePath, "D");
    } else if($FileIn==2) {
        $this->load->helper('download');
        $data=$this->masters_model->getTermsConditionsTextPdfById($TCID, $FileIn);
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$data->TermPdf, 3600);
        $filedata = file_get_contents($url); // Read the file's contents 
        force_download('TermsConditions.pdf', $filedata);
    }
}
    
public function getBotificationById()
{
    $NID=$this->input->post('NID');
    $data=$this->masters_model->getBotificationById($NID);
    echo json_encode($data);
}
    
public function updateNotification()
{
    $data=$this->masters_model->updateNotification();
    echo $data;
}
    
public function get_all_document_type_id()
{
    //echo 'test'; die;
    $res=$this->vessel_master_model->get_all_document_type_id();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='DocumentType: '.$row->DocumentType;
        $data_arr['value']=$row->DocumentTypeID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
    
    
public function view_document_type_file()
{ 
    $this->load->model('cargo_quote_model', '', true); 
    $filename=$this->cargo_quote_model->download_invitee_document();
        
    $DocRow=$this->cargo_quote_model->get_DocumentTypeID_ByFileName($filename);
    if($DocRow->charterPartyEditableFlag=='1') {
        $data=$this->cargo_quote_model->get_Content_ByDocumentTypeID($DocRow->DocumentTypeID);
            
        $html='';
            
        if($DocRow->ClauseType !=1 ) {
            $html .='<h6><b>INDEX TO CLAUSES</b></h6>';
            foreach($data as $row) {
                $html .='<p>'.$row->ClauseNo.'.  '.$row->CaluseName.'</p>';    
            }
            $html .='<hr><div class="page-break"></div>';
        }
        $html .='<table>';
        $html .='<tbody>';
        foreach($data as $row) {
            $clause_text=$this->masters_model->getClausesTextByID($row->ClauseID);
            $html .='<tr><td>';
            $html .=$clause_text;
            $html .='</td></tr>';
            $html .='<tr><td></td><tr>';
            $html .='<tr><td></td><tr>';
            $html .='<tr><td></td><tr>';
        }
        $html .=' </tbody>';
        $html .=' </table>';
    } else {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
        //print_r($url); die;
        $nar=explode("?", $url);
        $data=current($nar);
        $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
    }
    $AllData['flag']=$DocRow->ClauseType;
    $AllData['data']=$html;
    $filearray=explode('_____', $filename);
    $AllData['filename']=$filearray[1];
    echo json_encode($AllData);
}
    
    
    
public function download_document_type_file()
{ 
    $this->load->model('cargo_quote_model', '', true); 
    $filename=$this->cargo_quote_model->download_invitee_document();
        
    $DocRow=$this->cargo_quote_model->get_DocumentTypeID_ByFileName($filename);
    if($DocRow->charterPartyEditableFlag=='1') {
        
        $data=$this->cargo_quote_model->get_Content_ByDocumentTypeID($DocRow->DocumentTypeID);
        include_once APPPATH.'third_party/mpdf.php';        
            
        $html='';
            
        if($DocRow->ClauseType !=1) {
            $html .='<div style="page-break-after: always"><h6><b>INDEX TO CLAUSES</b></h6>';
            foreach($data as $row) {
                $html .='<p>'.$row->ClauseNo.'.  '.$row->CaluseName.'</p>';    
            }
            $html .='<hr /></div>';
        }
        foreach($data as $row) {
            $clause_text=$this->masters_model->getClausesTextByID($row->ClauseID);
            $html .='<p>'.$clause_text.'</p>';    
        }
        //print_r($html); die;
        $pdfFilePath = "Allclauses.pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
            
    } else {
        $this->load->helper('download');
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
        $data = file_get_contents($url); // Read the file's contents 
        //print_r($data);die;
        force_download($filename, $data);
    }
}
    
public function getQoteSubmitionByEntityid()
{
    $d=$this->masters_model->getQoteSubmitionByEntityid();
    $data['TermText']=$d->TermText;
    $data['TCID']=$d->TCID;
    echo json_encode($data);
}
    
public function downloadMessages()
{
    $this->load->model('cargo_model', '', true); 
    extract($this->input->get());
    $data1=$this->cargo_model->getAuctionID();
    if($data1) {
        //print_r('data'); die;
        $auction_arr=array();
        foreach($data1 as $row1){
            array_push($auction_arr, $row1->AuctionID);
        }
            
        $data=$this->cargo_model->getMessageAuctionData($auction_arr);
        $html ='';
        
        if($Outputformat=='PDF') {
            if($EID) {
                $entityname=$this->cargo_model->getEntityById($EID);
                $html .='Record owner : '.$entityname->EntityName;
            } else {
                $html .='Record owner : Auomni';
            }
            
            $html .='<br>DateTime generated : '.date('d-m-Y');
            $html .='<br><table style="width: 100%; border-collapse: collapse;">';
            $html .='<tr>';
            $html .='<th style="border: 1px solid;">Created date/time</th>';
            $html .='<th style="border: 1px solid;">MasterID</th>';
            $html .='<th style="border: 1px solid;">Message status</th>';
            $html .='<th style="border: 1px solid;">Record owner</th>';
            $html .='</tr>';
            foreach($data as $row){
                $check='';
                $msgdate='';
                if($row->MessageFlag==1) {
                     $MessageFlag='Unread';
                }else{
                    $MessageFlag='Read';
                }
                $html .='<tr>';
                $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->MsgDate)).'</td>';
                $html .='<td style="border: 1px solid;">'.$row->AuctionID.'</td>';
                $html .='<td style="border: 1px solid;">'.$MessageFlag.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
                $html .='</tr>';
            }
            $html .='</table>';
            //echo $html;die;
            $pdfFilePath='messages.pdf';
            include_once APPPATH.'third_party/mpdf.php';
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "D");
        } 
        
        if($Outputformat=='EXCEL') {
            $Content = "Created date/time,MasterID,Message status, Record owner \n";
            foreach($data as $row){
                $check='';
                $msgdate='';
                if($row->MessageFlag==1) {
                      $MessageFlag='Unread';
                }else{
                    $MessageFlag='Read';
                }
                $Content .= date('d-m-Y H:i:s', strtotime($row->MsgDate)).",".$row->AuctionID.",".$MessageFlag.",".$row->EntityName."\n";
            }
            header('Content-Type: application/csv'); 
            $FileName = 'messages.csv';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $Content;
            exit(); 
        } 
        if($Outputformat=='XML') {
            header('Content-type: text/xml');
            $xmloutput="<?xml version=\"1.0\" ?>\n";
            $xmloutput .="<MessagesDetails>\n";
            foreach($data as $row){
                $check='';
                $msgdate='';
                if($row->MessageFlag==1) {
                    $MessageFlag='Unread';
                }else{
                    $MessageFlag='Read';
                }
                $xmloutput .="\t<MessageDetail>\n";
                $xmloutput .="\t\t<DateTime>".date('d-m-Y H:i:s', strtotime($row->MsgDate))."</DateTime>\n";
                $xmloutput .="\t\t<MasterID>".$row->AuctionID."</MasterID>\n";
                $xmloutput .="\t\t<Status>".$MessageFlag."</Status>\n";
                $xmloutput .="\t\t<RecordOwner>".$row->EntityName."</RecordOwner>\n";
                $xmloutput .="</MessageDetail>\n";
            }
            $xmloutput .="</MessagesDetails>\n";    
        
            header('Content-Type: application/xml'); 
            $FileName = 'messages.xml';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $xmloutput;
            exit();
        }
    }
}
    
public function getEntityBussinessGroup()
{
    $data['records']=$this->masters_model->getEntityBussinessGroup();
    echo json_encode($data);
}
    
    
public function downloadNotification()
{
    $this->load->model('cargo_model', '', true); 
    $data=$this->masters_model->getNotification();
    $html='';
    extract($this->input->get());
    if($Outputformat=='PDF') {
        $entityname=$this->cargo_model->getEntityById($RecordOwner);
        $html .='Record owner : '.$entityname->EntityName;
        $html .='<br>DateTime generated : '.date('d-M-Y');
        $html .='<br>';
        $html .='<br>';
        
        $html .='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">DateTime</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Valid from (date)</th>';
        $html .='<th style="border: 1px solid;">Valid to (date)</th>';
        $html .='<th style="border: 1px solid;">Type</th>';
        $html .='<th style="border: 1px solid;">Msg type</th>';
        $html .='<th style="border: 1px solid;">Rec owner</th>';
        $html .='</tr>';    
        foreach($data as $row) {
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->SelectType==1) {
                $type='Holiday';
            }
            if($row->SelectType==2) {
                $type='System maintenance';
            }
            if($row->SelectType==3) {
                $type='Software update';
            }
            if($row->SelectType==4) {
                $type='Other';
            }
            if($row->MessageType==1) {
                $MessageType='Generic (visible to all)';
            }
            if($row->MessageType==2) {
                $MessageType='Company specific';
            }
            if($row->MessageType==3) {
                $MessageType='other parent group entity';
            }
        
            $time1=strtotime($row->MessageDisplayFrom);
            $time2=strtotime($row->MessageDisplayTo);
            $time=strtotime(date('Y-m-d'));
            $flag=0;
            if($time1 < $time && $time < $time2) {
                $flag=1;
            } 
        
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'</td>';
            $html .='<td style="border: 1px solid;">'.$status.'</td>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y', strtotime($row->MessageDisplayFrom)).'</td>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y', strtotime($row->MessageDisplayTo)).'</td>';
            $html .='<td style="border: 1px solid;">'.$type.'</td>';
            $html .='<td style="border: 1px solid;">'.$MessageType.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';
        
        }
        $html .='</table>';
        $pdfFilePath='notification.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
        
    if($Outputformat=='EXCEL') {
        $Content="DateTime,Status,Valid from (date),Valid to (date),Type,Msg type,Rec owner \n";
        foreach($data as $row) {
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->SelectType==1) {
                $type='Holiday';
            }
            if($row->SelectType==2) {
                $type='System maintenance';
            }
            if($row->SelectType==3) {
                $type='Software update';
            }
            if($row->SelectType==4) {
                $type='Other';
            }
            if($row->MessageType==1) {
                $MessageType='Generic (visible to all)';
            }
            if($row->MessageType==2) {
                $MessageType='Company specific';
            }
            if($row->MessageType==3) {
                $MessageType='other parent group entity';
            }
        
            $time1=strtotime($row->MessageDisplayFrom);
            $time2=strtotime($row->MessageDisplayTo);
            $time=strtotime(date('Y-m-d'));
            $flag=0;
            if($time1 < $time && $time < $time2) {
                $flag=1;
            } 
            $Content .=date('d-m-Y H:i:s', strtotime($row->CreatedDate)).",".$status.",".date('d-m-Y', strtotime($row->MessageDisplayFrom)).",".date('d-m-Y', strtotime($row->MessageDisplayTo)).",".$type.",".$MessageType.",".$row->EntityName."\n";
        
        }
        header('Content-Type: application/csv'); 
        $FileName = 'messages.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit(); 
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<NotificationDetails>\n";
        foreach($data as $row) {
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->SelectType==1) {
                $type='Holiday';
            }
            if($row->SelectType==2) {
                $type='System maintenance';
            }
            if($row->SelectType==3) {
                $type='Software update';
            }
            if($row->SelectType==4) {
                $type='Other';
            }
            if($row->MessageType==1) {
                $MessageType='Generic (visible to all)';
            }
            if($row->MessageType==2) {
                $MessageType='Company specific';
            }
            if($row->MessageType==3) {
                $MessageType='other parent group entity';
            }
        
            $time1=strtotime($row->MessageDisplayFrom);
            $time2=strtotime($row->MessageDisplayTo);
            $time=strtotime(date('Y-m-d'));
            $flag=0;
            if($time1 < $time && $time < $time2) {
                $flag=1;
            } 
        
            $xmloutput .="\t<Notifications>\n";
            $xmloutput .="\t\t<CreatedDate>".date('d-m-Y H:i:s', strtotime($row->CreatedDate))."</CreatedDate>\n";
            $xmloutput .="\t\t<status>".$row->status."</status>\n";
            $xmloutput .="\t\t<MessageDisplayFrom>".date('d-m-Y', strtotime($row->MessageDisplayFrom))."</MessageDisplayFrom>\n";
            $xmloutput .="\t\t<MessageDisplayTo>".date('d-m-Y', strtotime($row->MessageDisplayTo))."</MessageDisplayTo>\n";
            $xmloutput .="\t\t<Type>".$type."</Type>\n";
            $xmloutput .="\t\t<MessageType>".$MessageType."</MessageType>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="</Notifications>\n";
        
        }
        $xmloutput .="</NotificationDetails>\n";
        header('Content-Type: application/xml'); 
        $FileName = 'notification.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();    
    }
        
}
    
public function allEntityRoles()
{
    $res=$this->masters_model->allEntityRoles();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Name: '.$row->Name;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
    
}
    
public function fillEntityAddress()
{
    $data=$this->masters_model->fillEntityAddress();
    echo json_encode($data);
    
}
public function downloadTermsConditions()
{
    $this->load->model('cargo_model', '', true); 
    $this->load->model('cp_fn_model', '', true);
    $data=$this->masters_model->getTermsConditions();
    //print_r($data); die;
    $html='';
    extract($this->input->get());
    if($Outputformat=='PDF') {
        $entityname=$this->cargo_model->getEntityById($EntityID);
        $html .='Record owner : '.$entityname->EntityName;
        $html .='<br>DateTime generated : '.date('d-M-Y');
        $html .='<br>';
        $html .='<br>';
        
        $html .='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">DateTime (last)</th>';
        $html .='<th style="border: 1px solid;">Application</th>';
        $html .='<th style="border: 1px solid;">ID</th>';
        $html .='<th style="border: 1px solid;">Version</th>';
        $html .='<th style="border: 1px solid;">Linked</th>';
        $html .='<th style="border: 1px solid;">Created by</th>';
        $html .='<th style="border: 1px solid;">Updated by</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Rec Owner</th>';
        $html .='</tr>';
        foreach($data as $row) {
            $Entity=$this->cargo_model->getEntityById($row->EntityID);
            $user=$this->cp_fn_model->getUserByID($row->CreatedBy);
            $updated=$this->cp_fn_model->getUserByID($row->UpdateBy);
            if($row->Application==1) {
                $application='User log in';
            }else if($row->Application==2) {
                $application='Quote submission';
            }
            
            if($row->Status==1) {
                $status='Pending';
            }else if($row->Status==2) {
                $status='Complete';
            }else if($row->Status==3) {
                $status='Active';
            }else if($row->Status==4) {
                $status='Inactive';
            }
            
            if($row->link==1) {
                $link='Yes';
            }else{
                $link='No';
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'</td>';
            $html .='<td style="border: 1px solid;">'.$application.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->UniqueID.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Version.'</td>';
            $html .='<td style="border: 1px solid;">'.$link.'</td>';
            $html .='<td style="border: 1px solid;">'.$user->FirstName.' '.$user->LastName.'</td>';
            $html .='<td style="border: 1px solid;">'.$updated->FirstName.' '.$updated->LastName.'</td>';
            $html .='<td style="border: 1px solid;">'.$status.'</td>';
            $html .='<td style="border: 1px solid;">'.$Entity->EntityName.'</td>';
            $html .='</tr>';
        }
        
        $html .='</table>';
        //echo $html;die;
        $pdfFilePath='TermsConditions.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }    
        
    if($Outputformat=='EXCEL') {
        $Content="DateTime (last),Application,ID,Version,Linked,Created by,Updated by,Status,Rec Owner\n";
        
        foreach($data as $row) {
            $Entity=$this->cargo_model->getEntityById($row->EntityID);
            $user=$this->cp_fn_model->getUserByID($row->CreatedBy);
            $updated=$this->cp_fn_model->getUserByID($row->UpdateBy);
            if($row->Application==1) {
                $application='User log in';
            }else if($row->Application==2) {
                $application='Quote submission';
            }
            
            if($row->Status==1) {
                $status='Pending';
            }else if($row->Status==2) {
                $status='Complete';
            }else if($row->Status==3) {
                $status='Active';
            }else if($row->Status==4) {
                $status='Inactive';
            }
            
            if($row->link==1) {
                $link='Yes';
            }else{
                $link='No';
            }
            
            $Content .=date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).",".$application.",".$row->UniqueID.",".$row->Version.",".$link.",".$user->FirstName." ".$user->LastName.",".$updated->FirstName." ".$updated->LastName.",".$status.",".$Entity->EntityName."\n";
        
        }
        header('Content-Type: application/csv'); 
        $FileName = 'TermsConditions.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<TermsConditionsDetails>\n";
        foreach($data as $row) {
            $Entity=$this->cargo_model->getEntityById($row->EntityID);
            $user=$this->cp_fn_model->getUserByID($row->CreatedBy);
            $updated=$this->cp_fn_model->getUserByID($row->UpdateBy);
            if($row->Application==1) {
                $application='User log in';
            }else if($row->Application==2) {
                $application='Quote submission';
            }
            
            if($row->Status==1) {
                $status='Pending';
            }else if($row->Status==2) {
                $status='Complete';
            }else if($row->Status==3) {
                $status='Active';
            }else if($row->Status==4) {
                $status='Inactive';
            }
            
            if($row->link==1) {
                $link='Yes';
            }else{
                $link='No';
            }
            
            $Content .=date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).",".$application.",".$row->UniqueID.",".$row->Version.",".$link.",".$user->FirstName." ".$user->LastName.",".$updated->FirstName." ".$updated->LastName.",".$status.",".$Entity->EntityName."\n";
        
            $xmloutput .="\t<TermsConditions>\n";
            $xmloutput .="\t\t<CreatedDateTime>".date('d-m-Y H:i:s', strtotime($row->CreatedDateTime))."</CreatedDateTime>\n";
            $xmloutput .="\t\t<Application>".$application."</Application>\n";
            $xmloutput .="\t\t<UniqueID>".$row->UniqueID."</UniqueID>\n";
            $xmloutput .="\t\t<Version>".$row->Version."</Version>\n";
            $xmloutput .="\t\t<link>".$link."</link>\n";
            $xmloutput .="\t\t<CreatedBy>".$user->FirstName." ".$user->LastName."</CreatedBy>\n";
            $xmloutput .="\t\t<UpdatedBy>".$updated->FirstName." ".$updated->LastName."</UpdatedBy>\n";
            $xmloutput .="\t\t<Status>".$status."</Status>\n";
            $xmloutput .="\t\t<EntityName>".$Entity->EntityName."</EntityName>\n";
            $xmloutput .="</TermsConditions>\n";
        
        }
        $xmloutput .="</TermsConditionsDetails>\n";
        header('Content-Type: application/xml'); 
        $FileName = 'notification.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();    
    }
}
    
public function saveMyUserMaster()
{
    //$flg=$this->masters_model->testmail();
    $flg=$this->masters_model->saveMyUserMaster();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
}
    
    
public function getUserMasterData()
{
    $this->load->model('cargo_model', '', true); 
    $EntityID=$this->input->get('EntityID');
    $AssociateEntityID=$this->input->get('AssociateEntityID');
    $html='';
    $inhtml='';
    $status='';
    $html='{ "aaData": [';
    $i=1;
    $data2=$this->masters_model->getUserMasterData();
    //print_r($data2); die;
    foreach($data2 as $row) {
        //$check="<input class='chkNumber' type='checkbox' name='user_ids[]' value='".$row->UID."'>";
        if(date('d-m-Y', strtotime($row->DateTime))=='01-01-1970') {
            $datetime='-';
        }else{
            $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
        }
            
        if($row->InviteeEntityFlg==1) {
            $InviteeEntityFlg='Yes';
        } else {
            $InviteeEntityFlg='No';
        }
            
        if($row->EntityOwner != 1) {
                $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
                $EntityName=$Entity->EntityName;
        } else {
                $EntityName=$row->EntityName;
        }
            
            //$inhtml .='["'.$check.'","'.$datetime.'","'.$row->GroupName.'","'.$row->EntityName.'","'.$InviteeEntityFlg.'","'.$row->FirstName.' '.$row->LastName.'","'.$row->Email.'","'.$row->Telephone1.'","'.$EntityName.'"],';
            
            $viewReciept="<a href='javascript:void(0)' onclick=getUserTransactionReciept('".$row->UID."'); >view</a>";
            $viewHistory="<a href='javascript:void(0)' onclick=getUserTransactionRecieptHistory('".$row->UID."'); >view</a>";
            
            $edit="<a href='javascript: void(0);' onclick='editEntityUsers(".$row->UID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteEntityUsers(".$row->UID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .='["'.$i.'","'.$datetime.'","'.$row->GroupName.'","'.$row->EntityName.'","'.$InviteeEntityFlg.'","'.$row->FirstName.' '.$row->LastName.'","'.$row->Email.'","'.$row->Telephone1.'","'.$EntityName.'","'.$viewReciept.'","'.$viewHistory.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
    
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getAllDesignationRoles()
{
    $data['records']=$this->masters_model->getAllDesignationRoles();
    echo json_encode($data);
}
    
public function getEntityUserDetails()
{
    $data['Details']=$this->masters_model->getEntityUserDetails();
    $data['Businessgroup']=$this->masters_model->getUserBusinessGroup();
    $data['Email']=$this->masters_model->getUserEmailIDs();
    $data['Telephone']=$this->masters_model->getUserTelephones();
    $data['SignatureBlock']=$this->masters_model->getUserSignatureBlock();
    $data['Address']=$this->masters_model->getUserAddressDetail($data['Details']->OfficialAddressID);
    if($data['Details']->DesignationFrom==1) {
        $DesignationRow=$this->masters_model->getDesignationRole($data['Details']->DesignationRoleID);
        $data['DesignationRole']=$DesignationRow->Name;
    }
        
    //print_r($data); die;
    echo json_encode($data);
}
    
public function deleteAttactedPdfScan()
{
    echo $this->masters_model->deleteAttactedPdfScan();
}
    
    
public function view_scan_file_attached()
{
    $row=$this->masters_model->view_scan_file_attached();
    $flag=$this->input->post('flag');
    if($flag==1) {
        $filename=$row->SignatureImage;
    } else if($flag==2) {
        $filename=$row->AttachPhoto;
    }
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    include_once APPPATH.'third_party/image_check.php'; 
    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$filename, 3600);
    //print_r($url); die;

    if($flag==1) {
        $nar=explode("?", $url);
        $data=current($nar);
        $ext1=getExtension($filename);
        //print_r($ext1); die;
        $lowerext=strtolower($ext1);
        if($lowerext=='pdf') {
            $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
        } else {
            $html='<img src="'.$url.'" >';
        }
            
    } else if($flag==2) {
        $html='<img src="'.$url.'" >';
    }
        
        
    echo $html;
}
    
public function deleteUserEmailIds()
{
    $flg=$this->masters_model->deleteUserEmailIds();
    if($flg) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function deleteUserTelephones()
{
    $flg=$this->masters_model->deleteUserTelephones();
    if($flg) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function updateMyUserMaster()
{
    $flg=$this->masters_model->updateMyUserMaster();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
    
}
    
public function all_parent_entity_data()
{
    $res=$this->masters_model->all_parent_entity_data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='ParentName: '.$row->GroupName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}

public function all_associated_entity_data()
{
    $res=$this->masters_model->all_associated_entity_data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='EntityName: '.$row->EntityName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function downloadUserMasterData()
{
    $this->load->model('cargo_model', '', true); 
    extract($this->input->get());
    $data2=$this->masters_model->getUserMasterData();
    if($data2) {
            
        $html ='';
        
        if($Outputformat=='PDF') {
            if($EntityID) {
                $entityname=$this->cargo_model->getEntityById($EntityID);
                $html .='Record owner : '.$entityname->EntityName;
            } else {
                $html .='Record owner : Auomni';
            }
            
            
            $html .='<br>DateTime generated : '.date('d-m-Y');
            $html .='<br><table style="width: 100%; border-collapse: collapse;">';
            $html .='<tr>';
            $html .='<th style="border: 1px solid;">Date/time</th>';
            $html .='<th style="border: 1px solid;">Parent Entity</th>';
            $html .='<th style="border: 1px solid;">Associated Entity</th>';
            $html .='<th style="border: 1px solid;">User Name</th>';
            $html .='<th style="border: 1px solid;">EmailID</th>';
            $html .='<th style="border: 1px solid;">Telephone</th>';
            $html .='<th style="border: 1px solid;">Record Owner</th>';
            $html .='</tr>';
            foreach($data2 as $row){
                $datetime='';
                if(date('d-m-Y', strtotime($row->DateTime))=='01-01-1970') {
                    $datetime='-';
                }else{
                    $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
                }
            
                $html .='<tr>';
                $html .='<td style="border: 1px solid;">'.$datetime.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->GroupName.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->FirstName.' '.$row->LastName.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->Email.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->Telephone1.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
                $html .='</tr>';
            }
            $html .='</table>';
            //echo $html;die;
            $pdfFilePath='user_master_data.pdf';
            include_once APPPATH.'third_party/mpdf.php';
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "D");
        } 
        
        if($Outputformat=='EXCEL') {
            $Content = "DateTime,Parent Entity,Associated Entity,User Name,EmailID,Telephone,Record Owner \n";
            foreach($data2 as $row){
                $datetime='';
                if(date('d-m-Y', strtotime($row->DateTime))=='01-01-1970') {
                    $datetime='-';
                }else{
                    $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
                }
                $Content .= $datetime.",".$row->GroupName.",".$row->EntityName.",".$row->FirstName." ".$row->LastName.",".$row->Email.",".$row->Telephone1.",".$row->EntityName." \n";
            }
            header('Content-Type: application/csv'); 
            $FileName = 'user_master_data.csv';
            header('Content-Disposition: attachment; filename="'.$FileName.'"'); 
            echo $Content;
            exit(); 
        } 
        if($Outputformat=='XML') {
            header('Content-type: text/xml');
            $xmloutput="<?xml version=\"1.0\" ?>\n";
            $xmloutput .="<UserDetails>\n";
            foreach($data2 as $row){
                $datetime='';
                if(date('d-m-Y', strtotime($row->DateTime))=='01-01-1970') {
                      $datetime='-';
                }else{
                    $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
                }    
                $xmloutput .="\t<UserDetail>\n";
                $xmloutput .="\t\t<DateTime>".$datetime."</DateTime>\n";
                $xmloutput .="\t\t<ParentEntity>".$row->GroupName."</ParentEntity>\n";
                $xmloutput .="\t\t<AssociatedEntity>".$row->EntityName."</AssociatedEntity>\n";
                $xmloutput .="\t\t<UserName>".$row->FirstName." ".$row->LastName."</UserName>\n";
                $xmloutput .="\t\t<EmailID>".$row->Email."</EmailID>\n";
                $xmloutput .="\t\t<Telephone>".$row->Telephone1."</Telephone>\n";
                $xmloutput .="\t\t<RecordOwner>".$row->EntityName."</RecordOwner>\n";
                $xmloutput .="</UserDetail>\n";
            }
            $xmloutput .="</UserDetails>\n";    
        
            header('Content-Type: application/xml'); 
            $FileName = 'user_master_data.xml';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $xmloutput;
            exit();
        }
    }
}
    
public function getInviteeTermCondtion()
{
    $flg=$this->masters_model->checkUserLogin();
    if($flg==1) {
        $data=$this->masters_model->getInviteeTermCondtion();
        if($data=='yes') {
            //echo $data;
            $data=$this->masters_model->getUserTermCondtion();
                
            if(count($data)==0) {
                echo 8;
            } else if($data=='yes' || $data==0) {
                echo $data;
            } else {
                if($data->FileIn==1) {
                      $txt=$this->masters_model->getTermsConditionsTextById($data->TCID);
                      //$txt=$data->TermText;
                      $d['inviteecht']=2;
                      $d['txturl']=$txt;
                      $d['FileIn']=1;
                      $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
                      echo json_encode($d);
                } else if($data->FileIn==2) {
                    $bucket="hig-sam";
                    include_once APPPATH.'third_party/S3.php';
                    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
                    }
                    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
                    }
                    $s3 = new S3(awsAccessKey, awsSecretKey);
                    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$data->TermPdf, 3600);
                    $u='http://docs.google.com/gview?url='.$url.'&embedded=true';
                    $d['txturl']=$u;
                    $d['FileIn']=2;
                    $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
                    echo json_encode($d);
                    //echo json_encode($data);
                }
            }
        } else {
            if($data->FileIn==1) {
                $txt=$this->masters_model->getTermsConditionsTextById($data->TCID);
                //$txt=$data->TermText;
                $d['inviteecht']=1;
                $d['txturl']=$txt;
                $d['FileIn']=1;
                $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
                echo json_encode($d);
            } else if($data->FileIn==2) {
                $bucket="hig-sam";
                include_once APPPATH.'third_party/S3.php';
                if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
                }
                if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
                }
                $s3 = new S3(awsAccessKey, awsSecretKey);
                $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$data->TermPdf, 3600);
                $u='http://docs.google.com/gview?url='.$url.'&embedded=true';
                $d['txturl']=$u;
                $d['FileIn']=2;
                $d['heading']=$this->masters_model->getHeadingById($data->EntityID);
                echo json_encode($d);
                //echo json_encode($data);
            }
        }
    } else if($flg==2) {
        echo 2;
    } else if($flg==3) {
        $chkflg=$this->masters_model->updateWrongPasswordCount();
        echo $chkflg;
    } else if($flg==5) {
        echo 5;
    } else if($flg==6) {
        echo 6;
    } else if($flg==7) {
        echo 7;
    }
} 
    
public function disableTermCondtion()
{
    $data=$this->masters_model->disableTermCondtion();
}
    
    
    
public function DownloadVesselData()
{
    extract($this->input->get());
    $data=$this->vessel_master_model->getVesselData();
        
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">DateTime (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Vessel Size (dwt)</th>';
        $html .='<th style="border: 1px solid;">Size Group</th>';
        $html .='<th style="border: 1px solid;">Cargo Tolerance %</th>';
        $html .='<th style="border: 1px solid;">DWT Range From</th>';
        $html .='<th style="border: 1px solid;">DWT Range To</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Record Owner</th>';
        $html .='</tr>';
        foreach($data as $row){
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            }
            $SizeGroup='';
            if($row->SizeGroup=='Small_cape') {
                $SizeGroup='Cape - small';
            }else if($row->SizeGroup=='Mini_cape') {
                $SizeGroup='Cape - mini';
            }else if($row->SizeGroup=='Cape') {
                $SizeGroup='Cape - standard';
            }else if($row->SizeGroup=='Big_cape') {
                $SizeGroup='Cape - big';
            }else{
                $SizeGroup=$row->SizeGroup;
            }
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td style="border: 1px solid;">'.$row->VesselSize.'</td>';
            $html .='<td style="border: 1px solid;">'.$SizeGroup.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->CargoRangePercentage.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->CargoRangeFrom.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->CargoRangeTo.'</td>';
            $html .='<td style="border: 1px solid;">'.$flag.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        //echo $html;die;
        $pdfFilePath='VesselGrouping.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }

    if($Outputformat=='EXCEL') {
        $Content = "DateTime (dd-mm-yyyy),Vessel Size (dwt),Size Group, Cargo Tolerance %,DWT Range From,DWT Range To,Status,Record Owner \n";
        foreach($data as $row){
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            }
            $SizeGroup='';
            if($row->SizeGroup=='Small_cape') {
                $SizeGroup='Cape - small';
            }else if($row->SizeGroup=='Mini_cape') {
                $SizeGroup='Cape - mini';
            }else if($row->SizeGroup=='Cape') {
                $SizeGroup='Cape - standard';
            }else if($row->SizeGroup=='Big_cape') {
                $SizeGroup='Cape - big';
            }else{
                $SizeGroup=$row->SizeGroup;
            }
            $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$row->VesselSize.",".$SizeGroup.",".$row->CargoRangePercentage.",".$row->CargoRangeFrom.",".$row->CargoRangeTo.",".$flag.",".$row->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'VesselGrouping.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit(); 
    } 
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<VesselGroups>\n";
        foreach($data as $row){
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            }
            $SizeGroup='';
            if($row->SizeGroup=='Small_cape') {
                $SizeGroup='Cape - small';
            }else if($row->SizeGroup=='Mini_cape') {
                $SizeGroup='Cape - mini';
            }else if($row->SizeGroup=='Cape') {
                $SizeGroup='Cape - standard';
            }else if($row->SizeGroup=='Big_cape') {
                $SizeGroup='Cape - big';
            }else{
                $SizeGroup=$row->SizeGroup;
            }
            $xmloutput .="\t<VesselGroup>\n";
            $xmloutput .="\t\t<UserDate>".date('d-m-Y H:i:s', strtotime($row->UserDate))."</UserDate>\n";
            $xmloutput .="\t\t<VesselSize>".$row->VesselSize."</VesselSize>\n";
            $xmloutput .="\t\t<SizeGroup>".$SizeGroup."</SizeGroup>\n";
            $xmloutput .="\t\t<CargoRangePercentage>".$row->CargoRangePercentage."</CargoRangePercentage>\n";
            $xmloutput .="\t\t<CargoRangeFrom>".$row->CargoRangeFrom."</CargoRangeFrom>\n";
            $xmloutput .="\t\t<CargoRangeTo>".$row->CargoRangeTo."</CargoRangeTo>\n";
            $xmloutput .="\t\t<ActiveFlag>".$flag."</ActiveFlag>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t</VesselGroup>\n";
        }
        $xmloutput .="</VesselGroups>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'VesselGrouping.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();
    }
}
    
public function downloadDocumentData()
{
    extract($this->input->get());
    $data=$this->vessel_master_model->getDocumentData();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Document Type</th>';
        $html .='<th style="border: 1px solid;">Document Title</th>';
        $html .='<th style="border: 1px solid;">Entity</th>';
        $html .='<th style="border: 1px solid;">Editable</th>';
        $html .='<th style="border: 1px solid;">Attach. (CP)</th>';
        $html .='<th style="border: 1px solid;">Attach. Logo</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Created By</th>';
        $html .='</tr>';
        foreach($data as $row) {
            $documet_type=$this->masters_model->getDocumentTypeDataByID($row->DocumentTitle);
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            } 
            $charterPartyEditableFlag='No';
            if($row->charterPartyEditableFlag) {
                $charterPartyEditableFlag='Yes';
            }
            $CharterPartyPdf='No';
            if($row->CharterPartyPdf) {
                $CharterPartyPdf='Yes';
            }
            
            $Logo='No';
            if($row->Logo) {
                $Logo='Yes';
            }

                $html .='<tr>';
                $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
                $html .='<td style="border: 1px solid;">'.$row->DocumentType.'</td>';
                $html .='<td style="border: 1px solid;">'.$documet_type->DocName.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
                $html .='<td style="border: 1px solid;">'.$charterPartyEditableFlag.'</td>';
                $html .='<td style="border: 1px solid;">'.$CharterPartyPdf.'</td>';
                $html .='<td style="border: 1px solid;">'.$Logo.'</td>';
                $html .='<td style="border: 1px solid;">'.$flag.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->OwnerName.'</td>';
                $html .='</tr>';
        }
        $html .='</table>';
        //echo $html;die;
        $pdfFilePath='DocumentStore.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Document Type,Document Title, Entity,Editable,Attach. (CP),Attach. Logo,Status,Created By \n";
        foreach($data as $row) {
            $documet_type=$this->masters_model->getDocumentTypeDataByID($row->DocumentTitle);
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            } 
            $charterPartyEditableFlag='No';
            if($row->charterPartyEditableFlag) {
                $charterPartyEditableFlag='Yes';
            }
            $CharterPartyPdf='No';
            if($row->CharterPartyPdf) {
                $CharterPartyPdf='Yes';
            }
            
            $Logo='No';
            if($row->Logo) {
                $Logo='Yes';
            }
            $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$row->DocumentType.",".$documet_type->DocName.",".$row->EntityName.",".$charterPartyEditableFlag.",".$CharterPartyPdf.",".$Logo.",".$flag.",".$row->OwnerName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'DocumentStore.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();     
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<DocumentStores>\n";
        foreach($data as $row) {
            $documet_type=$this->masters_model->getDocumentTypeDataByID($row->DocumentTitle);
            if($row->ActiveFlag=='1') {
                $flag='Active';
            } else {
                $flag='Inactive';
            } 
            $charterPartyEditableFlag='No';
            if($row->charterPartyEditableFlag) {
                $charterPartyEditableFlag='Yes';
            }
            $CharterPartyPdf='No';
            if($row->CharterPartyPdf) {
                $CharterPartyPdf='Yes';
            }
            $Logo='No';
            if($row->Logo) {
                $Logo='Yes';
            }
            $xmloutput .="\t<DocumentStore>\n";
            $xmloutput .="\t\t<UserDate>".date('d-m-Y H:i:s', strtotime($row->UserDate))."</UserDate>\n";
            $xmloutput .="\t\t<DocumentType>".$row->DocumentType."</DocumentType>\n";
            $xmloutput .="\t\t<DocName>".$documet_type->DocName."</DocName>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t\t<EntityName>".$charterPartyEditableFlag."</EntityName>\n";
            $xmloutput .="\t\t<EntityName>".$CharterPartyPdf."</EntityName>\n";
            $xmloutput .="\t\t<EntityName>".$Logo."</EntityName>\n";
            $xmloutput .="\t\t<EntityName>".$flag."</EntityName>\n";
            $xmloutput .="\t\t<EntityName>".$row->OwnerName."</EntityName>\n";
            $xmloutput .="\t</DocumentStore>\n";
        }
        $xmloutput .="</DocumentStores>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'DocumentStore.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();    
    }
}
    
public function downloadDocumentByEntityId()
{
    extract($this->input->get());
    $data=$this->masters_model->getDocumentByEntityid();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Document Type</th>';
        $html .='<th style="border: 1px solid;">Document Title</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Created By</th>';
        $html .='</tr>';
        foreach($data as $row) {
            
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'</td>';
            $html .='<td style="border: 1px solid;">'.$row->DocType.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->DocName.'</td>';
            $html .='<td style="border: 1px solid;">'.$status.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        $pdfFilePath='DocumentType.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Document Type,Document Title, Status,Created By \n";
        foreach($data as $row) {
            
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            
            $Content .= date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).",".$row->DocType.",".$row->DocName.",".$status.",".$row->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'DocumentType.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<DocumentTypes>\n";
        foreach($data as $row) {
            if($row->Status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            $xmloutput .="\t<DocumentType>\n";
            $xmloutput .="\t\t<CreatedDateTime>".date('d-m-Y H:i:s', strtotime($row->CreatedDateTime))."</CreatedDateTime>\n";
            $xmloutput .="\t\t<DocType>".$row->DocType."</DocType>\n";
            $xmloutput .="\t\t<DocName>".$row->DocName."</DocName>\n";
            $xmloutput .="\t\t<Status>".$status."</Status>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t</DocumentType>\n";
        }
        $xmloutput .="</DocumentTypes>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'DocumentType.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();    
    }
}
    
public function downloadInviteeData()
{
    extract($this->input->get());
    $data=$this->vessel_master_model->getInviteeData();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Invitee</th>';
        $html .='<th style="border: 1px solid;">User</th>';
        $html .='<th style="border: 1px solid;">Period from</th>';
        $html .='<th style="border: 1px solid;">Period to</th>';
        $html .='<th style="border: 1px solid;">Group</th>';
        $html .='<th style="border: 1px solid;">Priority</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">RecordOwner</th>';
        $html .='</tr>';
        foreach($data as $row) {
            if($row->InviteeStatus=='1') {
                $flag='Active';
            } else {
                $flag='Deactive';
            }
            if($row->InviteePeriod=='0') {
                $period1='Infinite';
                $period2='Infinite';
            } else {
                $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
                $period2=date('d-m-Y', strtotime($row->DateRangeTo));
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->FirstName.' '.$row->LastName.'</td>';
            $html .='<td style="border: 1px solid;">'.$period1.'</td>';
            $html .='<td style="border: 1px solid;">'.$period2.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->UserGroup.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->PriorityStatus.'</td>';
            $html .='<td style="border: 1px solid;">'.$flag.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->OwnerName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        $pdfFilePath='Invitee.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Invitee,User,Period from,Period to,Group,Priority,Status,Record Owner \n";
        foreach($data as $row) {
            if($row->InviteeStatus=='1') {
                $flag='Active';
            } else {
                $flag='Deactive';
            }
            if($row->InviteePeriod=='0') {
                $period1='Infinite';
                $period2='Infinite';
            } else {
                $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
                $period2=date('d-m-Y', strtotime($row->DateRangeTo));
            }

            $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$row->EntityName.",".$row->FirstName.' '.$row->LastName.",".$period1.",".$period2.",".$row->UserGroup.",".$row->PriorityStatus.",".$flag.",".$row->OwnerName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'Invitee.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();        
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<Invitees>\n";
        foreach($data as $row) {
            if($row->InviteeStatus=='1') {
                $flag='Active';
            } else {
                $flag='Deactive';
            }
            if($row->InviteePeriod=='0') {
                $period1='Infinite';
                $period2='Infinite';
            } else {
                $period1=date('d-m-Y', strtotime($row->DateRangeFrom));
                $period2=date('d-m-Y', strtotime($row->DateRangeTo));
            }
            $xmloutput .="\t<Invitee>\n";
            $xmloutput .="\t\t<UserDate>".date('d-m-Y H:i:s', strtotime($row->UserDate))."</UserDate>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t\t<UserName>".$row->FirstName.' '.$row->LastName."</UserName>\n";
            $xmloutput .="\t\t<PeriodFrom>".$period1."</PeriodFrom>\n";
            $xmloutput .="\t\t<PeriodTo>".$period2."</PeriodTo>\n";
            $xmloutput .="\t\t<UserGroup>".$row->UserGroup."</UserGroup>\n";
            $xmloutput .="\t\t<PriorityStatus>".$row->PriorityStatus."</PriorityStatus>\n";
            $xmloutput .="\t\t<InviteeStatus>".$flag."</InviteeStatus>\n";
            $xmloutput .="\t\t<OwnerName>".$row->OwnerName."</OwnerName>\n";
            $xmloutput .="\t</Invitee>\n";
        }
        $xmloutput .="</Invitees>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'Invitee.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();        
    }
}
public function downloadMessageData()
{
    extract($this->input->get());
    $data=$this->vessel_master_model->getMessageData();
    if($Outputformat=='DOC') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Events</th>';
        $html .='<th style="border: 1px solid;">Message Type</th>';
        $html .='<th style="border: 1px solid;">Page</th>';
        $html .='<th style="border: 1px solid;">User</th>';
        $html .='<th style="border: 1px solid;">Record Owner</th>';
        $html .='</tr>';
        foreach($data as $row){
            $view='';
            $msgtype='';
            $event='';
            $page='';
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
                }else if($row->Events=='complete') {
                    $event='Complete';
                }else if($row->Events=='status_change') {
                    $event='Status Change';
                }
                
            } else if($row->MessageType=='proc_msg') {
                if($row->Events=='1') {
                    $event='Bid commencement';
                }else if($row->Events=='2') {
                    $event='Cargo Set Up (Quotes) (in progress)';
                }else if($row->Events=='3') {
                    $event='Reminder';
                }else if($row->Events=='4') {
                    $event='Cargo withdrawn in set up';
                }else if($row->Events=='5') {
                    $event='Cargo withdrawn in main';
                }else if($row->Events=='6') {
                    $event='Cargo decline by invitee';
                }else if($row->Events=='7') {
                    $event='Cargo invitee short listed';
                }else if($row->Events=='8') {
                    $event='Tentative bid acceptance';
                }else if($row->Events=='9') {
                    $event='Cargo bid approval';
                }else if($row->Events=='10') {
                    $event='Cargo Set Up (Quotes) (closed)';
                }else if($row->Events=='11') {
                    $event='Fixture note completed';
                }else if($row->Events=='12') {
                    $event='Charter documentation';
                }else if($row->Events=='13') {
                    $event='Cargo Set Up (Quotes) (submitted)';
                } else if($row->Events=='14') {
                    $event='Cargo Set Up (Quotes) (invitee comment)';
                }else if($row->Events=='15') {
                    $event='Fixture note tentative';
                }else if($row->Events=='16') {
                    $event='Fixture note final';
                }else if($row->Events=='17') {
                    $event='Charter party final';
                }else if($row->Events=='18') {
                    $event='Charter party tentative';
                }else if($row->Events=='19') {
                    $event='Charter party complete';
                }else if($row->Events=='20') {
                    $event='CP subject notification';
                }else if($row->Events=='21') {
                    $event='CP subject lifted';
                }else if($row->Events=='22') {
                    $event='C/P on subjects (Shipowner/Broker)';
                }else if($row->Events=='23') {
                    $event='Cargo Set Up (Quotes) (TA)';
                }else if($row->Events=='24') {
                    $event='Technical vetting approve';
                }else if($row->Events=='25') {
                    $event='Counter party approve';
                }else if($row->Events=='26') {
                    $event='Compliance risk approve';
                }else if($row->Events=='27') {
                    $event='Business vetting approve';
                }else if($row->Events=='28') {
                    $event='Sign fixture note';
                }else if($row->Events=='29') {
                    $event='Sign charter party document';
                }else if($row->Events=='30') {
                    $event='CP no subjects';
                }
                
            } else if($row->MessageType=='alert_msg') {
                if($row->Events=='commencement') {
                    $event='Commencement of bid';
                }else if($row->Events=='prior_commencement') {
                          $event='Prior to commencement of bid';
                }else if($row->Events=='prior_closing') {
                           $event='Prior to closing of bid';
                }else if($row->Events=='closing') {
                    $event='Cargo closing';
                }else if($row->Events=='reminder') {
                    $event='Reminder of Cargo';
                }
                
            } else if($row->MessageType=='admin') {
                if($row->Events=='unlock_user') {
                    $event='Unlock user';
                } else if($row->Events=='new_user_existing_entity') {
                           $event='New User Existing Entity';
                }
            }
            
            if($row->OnPage=='page_1') {
                $page='Cargo Set Up';
            }else if($row->OnPage=='page_2') {
                $page='Charter Parties (+FN)';
            }else if($row->OnPage=='page_3') {
                $page='Cargo Set Up (Quotes)';
            }else if($row->OnPage=='page_4') {
                $page='Fixture Notes';
            }else if($row->OnPage=='page_5') {
                $page='Charter Documentation';
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td style="border: 1px solid;">'.$event.'</td>';
            $html .='<td style="border: 1px solid;">'.$msgtype.'</td>';
            $html .='<td style="border: 1px solid;">'.$page.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->FirstName.' '.$row->LastName.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        //echo $html;
            
        header('Content-Type: application/doc'); 
        $FileName = 'MessageMaster.doc';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $html;
        exit();    
            
    }
        
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Events,Message Type,Page,User,Record Owner \n";
        foreach($data as $row){
            $view='';
            $msgtype='';
            $event='';
            $page='';
            if($row->MessageType=='sys_msg') {
                $msgtype='System Message';
            }else if($row->MessageType=='alert_msg') {
                $msgtype='Alert Message';
            }else if($row->MessageType=='proc_msg') {
                $msgtype='Process Message';
            }else if($row->MessageType=='admin') {
                $msgtype='Admin';
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
                }else if($row->Events=='complete') {
                    $event='Complete';
                }else if($row->Events=='status_change') {
                    $event='Status Change';
                }
                
            } else if($row->MessageType=='proc_msg') {
                if($row->Events=='1') {
                    $event='Bid commencement';
                }else if($row->Events=='2') {
                          $event='Cargo Set Up (Quotes) (in progress)';
                }else if($row->Events=='3') {
                               $event='Reminder';
                }else if($row->Events=='4') {
                    $event='Cargo withdrawn in set up';
                }else if($row->Events=='5') {
                    $event='Cargo withdrawn in main';
                }else if($row->Events=='6') {
                    $event='Cargo decline by invitee';
                }else if($row->Events=='7') {
                    $event='Cargo invitee short listed';
                }else if($row->Events=='8') {
                    $event='Tentative bid acceptance';
                }else if($row->Events=='9') {
                    $event='Cargo bid approval';
                }else if($row->Events=='10') {
                    $event='Cargo Set Up (Quotes) (closed)';
                }else if($row->Events=='11') {
                    $event='Fixture note completed';
                }else if($row->Events=='12') {
                    $event='Charter documentation';
                }else if($row->Events=='13') {
                    $event='Cargo Set Up (Quotes) (submitted)';
                } else if($row->Events=='14') {
                    $event='Cargo Set Up (Quotes) (invitee comment)';
                }else if($row->Events=='15') {
                    $event='Fixture note tentative';
                }else if($row->Events=='16') {
                    $event='Fixture note final';
                }else if($row->Events=='17') {
                    $event='Charter party final';
                }else if($row->Events=='18') {
                    $event='Charter party tentative';
                }else if($row->Events=='19') {
                    $event='Charter party complete';
                }else if($row->Events=='20') {
                    $event='CP subject notification';
                }else if($row->Events=='21') {
                    $event='CP subject lifted';
                }else if($row->Events=='22') {
                    $event='C/P on subjects (Shipowner/Broker)';
                }else if($row->Events=='23') {
                    $event='Cargo Set Up (Quotes) (TA)';
                }else if($row->Events=='24') {
                    $event='Technical vetting approve';
                }else if($row->Events=='25') {
                    $event='Counter party approve';
                }else if($row->Events=='26') {
                    $event='Compliance risk approve';
                }else if($row->Events=='27') {
                    $event='Business vetting approve';
                }else if($row->Events=='28') {
                    $event='Sign fixture note';
                }else if($row->Events=='29') {
                    $event='Sign charter party document';
                }else if($row->Events=='30') {
                    $event='CP no subjects';
                }
                
            }else if($row->MessageType=='alert_msg') {
                if($row->Events=='commencement') {
                    $event='Commencement of bid';
                }else if($row->Events=='prior_commencement') {
                          $event='Prior to commencement of bid';
                }else if($row->Events=='prior_closing') {
                           $event='Prior to closing of bid';
                }else if($row->Events=='closing') {
                                                      $event='Cargo closing';
                }else if($row->Events=='reminder') {
                    $event='Reminder of Cargo';
                }
                
            }else if($row->MessageType=='admin') {
                if($row->Events=='unlock_user') {
                    $event='Unlock user';
                } else if($row->Events=='new_user_existing_entity') {
                               $event='New User Existing Entity';
                }
            }
            
            if($row->OnPage=='page_1') {
                $page='Cargo Set Up';
            }else if($row->OnPage=='page_2') {
                $page='Charter Parties (+FN)';
            }else if($row->OnPage=='page_3') {
                $page='Cargo Set Up (Quotes)';
            }else if($row->OnPage=='page_4') {
                $page='Fixture Notes';
            }else if($row->OnPage=='page_5') {
                $page='Charter Documentation';
            }else if($row->OnPage=='login') {
                $page='Login Form';
            }
            $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$event.",".$msgtype.",".$page.",".$row->FirstName.' '.$row->LastName.",".$row->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'MessageMaster.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<Messages>\n";
        foreach($data as $row){
            $view='';
            $msgtype='';
            $event='';
            $page='';
            if($row->MessageType=='sys_msg') {
                $msgtype='System Message';
            }else if($row->MessageType=='alert_msg') {
                $msgtype='Alert Message';
            }else if($row->MessageType=='proc_msg') {
                $msgtype='Process Message';
            }else if($row->MessageType=='admin') {
                $msgtype='Admin';
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
                }else if($row->Events=='complete') {
                    $event='Complete';
                }else if($row->Events=='status_change') {
                    $event='Status Change';
                }
                
            } else if($row->MessageType=='proc_msg') {
                if($row->Events=='1') {
                    $event='Bid commencement';
                }else if($row->Events=='2') {
                          $event='Cargo Set Up (Quotes) (in progress)';
                }else if($row->Events=='3') {
                               $event='Reminder';
                }else if($row->Events=='4') {
                    $event='Cargo withdrawn in set up';
                }else if($row->Events=='5') {
                    $event='Cargo withdrawn in main';
                }else if($row->Events=='6') {
                    $event='Cargo decline by invitee';
                }else if($row->Events=='7') {
                    $event='Cargo invitee short listed';
                }else if($row->Events=='8') {
                    $event='Tentative bid acceptance';
                }else if($row->Events=='9') {
                    $event='Cargo bid approval';
                }else if($row->Events=='10') {
                    $event='Cargo Set Up (Quotes) (closed)';
                }else if($row->Events=='11') {
                    $event='Fixture note completed';
                }else if($row->Events=='12') {
                    $event='Charter documentation';
                }else if($row->Events=='13') {
                    $event='Cargo Set Up (Quotes) (submitted)';
                } else if($row->Events=='14') {
                    $event='Cargo Set Up (Quotes) (invitee comment)';
                }else if($row->Events=='15') {
                    $event='Fixture note tentative';
                }else if($row->Events=='16') {
                    $event='Fixture note final';
                }else if($row->Events=='17') {
                    $event='Charter party final';
                }else if($row->Events=='18') {
                    $event='Charter party tentative';
                }else if($row->Events=='19') {
                    $event='Charter party complete';
                }else if($row->Events=='20') {
                    $event='CP subject notification';
                }else if($row->Events=='21') {
                    $event='CP subject lifted';
                }else if($row->Events=='22') {
                    $event='C/P on subjects (Shipowner/Broker)';
                }else if($row->Events=='23') {
                    $event='Cargo Set Up (Quotes) (TA)';
                }else if($row->Events=='24') {
                    $event='Technical vetting approve';
                }else if($row->Events=='25') {
                    $event='Counter party approve';
                }else if($row->Events=='26') {
                    $event='Compliance risk approve';
                }else if($row->Events=='27') {
                    $event='Business vetting approve';
                }else if($row->Events=='28') {
                    $event='Sign fixture note';
                }else if($row->Events=='29') {
                    $event='Sign charter party document';
                }else if($row->Events=='30') {
                    $event='CP no subjects';
                }
                
            }else if($row->MessageType=='alert_msg') {
                if($row->Events=='commencement') {
                    $event='Commencement of bid';
                }else if($row->Events=='prior_commencement') {
                          $event='Prior to commencement of bid';
                }else if($row->Events=='prior_closing') {
                           $event='Prior to closing of bid';
                }else if($row->Events=='closing') {
                                                      $event='Cargo closing';
                }else if($row->Events=='reminder') {
                    $event='Reminder of Cargo';
                }
                
            }else if($row->MessageType=='admin') {
                if($row->Events=='unlock_user') {
                    $event='Unlock user';
                } else if($row->Events=='new_user_existing_entity') {
                               $event='New User Existing Entity';
                }
            }
            
            if($row->OnPage=='page_1') {
                $page='Cargo Set Up';
            }else if($row->OnPage=='page_2') {
                $page='Charter Parties (+FN)';
            }else if($row->OnPage=='page_3') {
                $page='Cargo Set Up (Quotes)';
            }else if($row->OnPage=='page_4') {
                $page='Fixture Notes';
            }else if($row->OnPage=='page_5') {
                $page='Charter Documentation';
            }else if($row->OnPage=='login') {
                $page='Login Form';
            }
            $xmloutput .="\t<Invitee>\n";
            $xmloutput .="\t\t<UserDate>".date('d-m-Y H:i:s', strtotime($row->UserDate))."</UserDate>\n";
            $xmloutput .="\t\t<EntityName>".$event."</EntityName>\n";
            $xmloutput .="\t\t<UserName>".$msgtype."</UserName>\n";
            $xmloutput .="\t\t<PeriodFrom>".$page."</PeriodFrom>\n";
            $xmloutput .="\t\t<PeriodTo>".$row->FirstName.' '.$row->LastName."</PeriodTo>\n";
            $xmloutput .="\t\t<UserGroup>".$row->EntityName."</UserGroup>\n";
            $xmloutput .="\t</Invitee>\n";
        }
        $xmloutput .="</Messages>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'MessageMaster.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();        
    }
}
    
public function downloadEntityMasterData()
{
    $this->load->model('cargo_model', '', true); 
    extract($this->input->get());
    $data3=$this->masters_model->getEntityMasterMyCustomAppendData();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Associated Entity</th>';
        $html .='<th style="border: 1px solid;">Country</th>';
        $html .='<th style="border: 1px solid;">EmailID</th>';
        $html .='<th style="border: 1px solid;">Telephone</th>';
        $html .='<th style="border: 1px solid;">Parent Entity</th>';
        $html .='<th style="border: 1px solid;">Record Owner</th>';
        $html .='</tr>';
        foreach($data3 as $row){
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
            
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            
            if($row->CountryID) {
                $Country=$this->masters_model->getCountryById($row->CountryID);
                $ctry=$Country->CountryDescription;
            } else {
                $ctry='';
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.$datetime.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='<td style="border: 1px solid;">'.$ctry.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Email.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Telephone1.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->GroupName.'</td>';
            $html .='<td style="border: 1px solid;">'.$Entity->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        $pdfFilePath='AssociateEntityMaster.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Associated Entity,Country,EmailID,Telephone,Parent Entity,Record Owner \n";
        foreach($data3 as $row){
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
            
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            
            if($row->CountryID) {
                $Country=$this->masters_model->getCountryById($row->CountryID);
                $ctry=$Country->CountryDescription;
            } else {
                $ctry='';
            }
            $Content .= $datetime.",".$row->EntityName.",".$ctry.",".$row->Email.",".$row->Telephone1.",".$row->GroupName.",".$Entity->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'AssociateEntityMaster.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<AssociateEntity>\n";
        foreach($data3 as $row){
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
            
            $Entity=$this->cargo_model->getEntityById($row->EntityOwner);
            
            if($row->CountryID) {
                $Country=$this->masters_model->getCountryById($row->CountryID);
                $ctry=$Country->CountryDescription;
            } else {
                $ctry='';
            }
            $xmloutput .="\t<Entity>\n";
            $xmloutput .="\t\t<DateTime>".$datetime."</DateTime>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t\t<Country>".$ctry."</Country>\n";
            $xmloutput .="\t\t<Email>".$row->Email."</Email>\n";
            $xmloutput .="\t\t<Telephone>".$row->Telephone1."</Telephone>\n";
            $xmloutput .="\t\t<ParentGroup>".$row->GroupName."</ParentGroup>\n";
            $xmloutput .="\t\t<RecordOwner>".$Entity->EntityName."</RecordOwner>\n";
            $xmloutput .="\t</Entity>\n";
        }
        $xmloutput .="</AssociateEntity>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'AssociateEntityMaster.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();        
    }
}
public function downloadModelSetup()
{
    extract($this->input->get());
    $data=$this->masters_model->getModel();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Model</th>';
        $html .='<th style="border: 1px solid;">Entity Role</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Record Owner</th>';
        $html .='</tr>';
        foreach($data as $row) {
            if($row->ModelStatus==1) {
                $sts='Active';
            }
            if($row->ModelStatus==0) {
                $sts='Inactive';
            }
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->add_date)).'</td>';
            $html .='<td style="border: 1px solid;">'.$row->ModelNumber.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Description.'</td>';
            $html .='<td style="border: 1px solid;">'.$sts.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        $pdfFilePath='ModelSetup.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "Date Time (dd-mm-yyyy),Model,Entity Role,Status,Record Owner \n";
        foreach($data as $row) {
            if($row->ModelStatus==1) {
                $sts='Active';
            }
            if($row->ModelStatus==0) {
                $sts='Inactive';
            }
            
            $Content .= date('d-m-Y H:i:s', strtotime($row->add_date)).",".$row->ModelNumber.",".$row->Description.",".$sts.",".$row->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'ModelSetup.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<ModelSetup>\n";
        foreach($data as $row) {
            if($row->ModelStatus==1) {
                $sts='Active';
            }
            if($row->ModelStatus==0) {
                $sts='Inactive';
            }
            
            $Content .= date('d-m-Y H:i:s', strtotime($row->add_date)).",".$row->ModelNumber.",".$row->Description.",".$sts.",".$row->EntityName."\n";
            $xmloutput .="\t<Invitee>\n";
            $xmloutput .="\t\t<add_date>".date('d-m-Y H:i:s', strtotime($row->add_date))."</add_date>\n";
            $xmloutput .="\t\t<ModelNumber>".$row->ModelNumber."</ModelNumber>\n";
            $xmloutput .="\t\t<Description>".$row->Description."</Description>\n";
            $xmloutput .="\t\t<ModelStatus>".$sts."</ModelStatus>\n";
            $xmloutput .="\t\t<PeriodTo>".$row->FirstName.' '.$row->LastName."</PeriodTo>\n";
            $xmloutput .="\t\t<EntityName>".$row->EntityName."</EntityName>\n";
            $xmloutput .="\t</Invitee>\n";
        }
        $xmloutput .="</ModelSetup>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'ModelSetup.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();            
    }
}
    
public function downloadParentMasterData()
{
    extract($this->input->get());
    $data3=$this->masters_model->getParentMasterMyCustomAppendData();
        
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date Time (dd-mm-yyyy)</th>';
        $html .='<th style="border: 1px solid;">Parent Entity</th>';
        $html .='<th style="border: 1px solid;">Country</th>';
        $html .='<th style="border: 1px solid;">EmailID</th>';
        $html .='<th style="border: 1px solid;">Telephone</th>';
        $html .='<th style="border: 1px solid;">Record Owner</th>';
        $html .='</tr>';
        foreach($data3 as $row) {
            
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
                
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.$datetime.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->GroupName.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->CountryDescription.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Email.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->Telephone1.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->EntityName.'</td>';
            $html .='</tr>';

        }
        
        $html .='</table>';
        $pdfFilePath='ParentEntityMaster.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
        
    if($Outputformat=='EXCEL') {
        $Content = "DateTime (dd-mm-yyyy),Parent Entity,Country,EmailID,Telephone,Record Owner \n";
        foreach($data3 as $row){
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
            $Content .= $datetime.",".$row->GroupName.",".$row->CountryDescription.",".$row->Email.",".$row->Telephone1.",".$row->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName = 'ParentEntityMaster.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<ParentEntity>\n";
        foreach($data3 as $row){
            if(date('d-m-Y H:i:s', strtotime($row->DateTime))=='01-01-1970 00:00:00') {
                $datetime='-';
            }else{
                $datetime=date('d-m-Y H:i:s', strtotime($row->DateTime));
            }
            $xmloutput .="\t<Entity>\n";
            $xmloutput .="\t\t<DateTime>".$datetime."</DateTime>\n";
            $xmloutput .="\t\t<ParentGroup>".$row->GroupName."</ParentGroup>\n";
            $xmloutput .="\t\t<Country>".$row->CountryDescription."</Country>\n";
            $xmloutput .="\t\t<Email>".$row->Email."</Email>\n";
            $xmloutput .="\t\t<Telephone>".$row->Telephone1."</Telephone>\n";
            $xmloutput .="\t\t<RecordOwner>".$row->EntityName."</RecordOwner>\n";
            $xmloutput .="\t</Entity>\n";
        }
        $xmloutput .="</ParentEntity>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'ParentEntityMaster.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();        
    }
}
    
public function getModalName()
{
    $data=$this->masters_model->getModalName();
    echo json_encode($data);
}
    
public function get_associated_entity_name()
{
    //echo 'test'; die;
    $res=$this->masters_model->get_associated_entity_name();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='EntityName: '.$row->EntityName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
         
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_entity_country()
{
    //echo 'test'; die;
    $res=$this->masters_model->get_entity_country();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Code: '.$row->Code.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_entity_state()
{
    //echo 'test'; die;
    $res=$this->masters_model->get_entity_state();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Code: '.$row->Code.' || Description: '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    } else {    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function get_assoc_entity_details()
{
        
    $data['details']=$this->masters_model->get_assoc_entity_details();
    //print_r($data); die;
    $this->output->set_output(json_encode($data));
}
    
public function saveBusinessProcess()
{
    $data=$this->masters_model->saveBusinessProcess();
    echo $data;
}
    
public function getBusinessProcessByEntityid()
{
    $this->load->model('cargo_model', '', true); 
    $data=$this->masters_model->getBusinessProcessByEntityid();
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row) {
        if($row->status==1) {
            $status='Active';
        } else {
            $status='Inactive';
        }
        if($row->finalization_completed_by==1) {
            $finalization_completed_by='Record Owner';
        } else if($row->finalization_completed_by==2) {
            $finalization_completed_by='Invitee Only';
        } else if($row->finalization_completed_by==3) {
                $finalization_completed_by='Record Owner and Invitee jointly';
        } else if($row->finalization_completed_by==4) {
            $finalization_completed_by='Record owner technical vetting team';
        } else if($row->finalization_completed_by==5) {
            $finalization_completed_by='Record owner compliance team';
        } else if($row->finalization_completed_by==6) {
            $finalization_completed_by='Record owner counter party risk assessment team';
        } 
            
        if($row->validity==1) {
            $validity_from='Infinite';
            $validity_to='Infinite';
        }
            
        if($row->validity==2) {
            $validity_from=date('d-M-Y', strtotime($row->date_from));
            $validity_to=date('d-M-Y', strtotime($row->date_to));
        }
            
        if($row->name_of_process==1) {
            $name_of_process='Technical Vetting';
        } else if($row->name_of_process==2) {
            $name_of_process='Business vetting approval';
        } else if($row->name_of_process==3) {
            $name_of_process='Counter party risk assessment';
        } else if($row->name_of_process==4) {
            $name_of_process='Compliance risk assessment';
        } else if($row->name_of_process==5) {
            $name_of_process='Authorization for quotes (by broker)';
        } else if($row->name_of_process==6) {
            $name_of_process='Charter party final signature';
        } else if($row->name_of_process==7) {
            $name_of_process='Fixture note final signature';
        } else if($row->name_of_process==8) {
            $name_of_process='Approval for quotes authorization (by record owner)';
        } else if($row->name_of_process==9) {
            $name_of_process='C/P on subjects (charterer)';
        } else if($row->name_of_process==10) {
            $name_of_process='C/P on subjects (Shipowner/Broker)';
        }
            
            $entity=$this->cargo_model->getEntityById($row->RecordOwner);
            
            //$check="<input type='checkbox' name='BPID[]' class='chkNumber' value='".$row->BPID."'>";
            $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->BPID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteDocument(".$row->BPID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$name_of_process.'","'.$row->process_flow_sequence.'","'.$finalization_completed_by.'","'.$validity_from.'","'.$validity_to.'","'.$status.'","'.$entity->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getBusinessProcessById()
{
    $data=$this->masters_model->getBusinessProcessById();
    echo json_encode($data);
}
    
public function updateBusinessProcess()
{
    $data=$this->masters_model->updateBusinessProcess();
    echo $data;
}
    
public function deleteBusinessProcessById()
{
    $data=$this->masters_model->deleteBusinessProcessById();
}
    
    
public function getBusinessProcessMessageById()
{
    $data=$this->masters_model->getBusinessProcessMessageById();
    echo $data->message_text;
}
    
public function getUserByEntityId()
{
    $data=$this->masters_model->getUserByEntityId();
    echo json_encode($data);
}
    
    
    
public function downloadBusinessProcess()
{
    $this->load->model('cargo_model', '', true); 
    extract($this->input->get());
    $data=$this->masters_model->getBusinessProcessByEntityid();
    if($Outputformat=='PDF') {
        $html='<table style="width: 100%; border-collapse: collapse;">';
        $html .='<tr>';
        $html .='<th style="border: 1px solid;">Date</th>';
        $html .='<th style="border: 1px solid;">Process Name</th>';
        $html .='<th style="border: 1px solid;">Sequence ID</th>';
        $html .='<th style="border: 1px solid;">Approval by</th>';
        $html .='<th style="border: 1px solid;">Validity From</th>';
        $html .='<th style="border: 1px solid;">Validity To</th>';
        $html .='<th style="border: 1px solid;">Status</th>';
        $html .='<th style="border: 1px solid;">Record owner</th>';
        $html .='</tr>';
        foreach($data as $row) {
            if($row->status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->finalization_completed_by==1) {
                $finalization_completed_by='Record Owner';
            } else if($row->finalization_completed_by==2) {
                $finalization_completed_by='Invitee Only';
            } else if($row->finalization_completed_by==3) {
                $finalization_completed_by='Record Owner and Invitee jointly';
            } else if($row->finalization_completed_by==4) {
                $finalization_completed_by='Record owner technical vetting team';
            } else if($row->finalization_completed_by==5) {
                $finalization_completed_by='Record owner compliance team';
            } else if($row->finalization_completed_by==6) {
                $finalization_completed_by='Record owner counter party risk assessment team';
            } 
            
            if($row->validity==1) {
                $validity_from='Infinite';
                $validity_to='Infinite';
            }
            
            if($row->validity==2) {
                $validity_from=date('d-M-Y', strtotime($row->date_from));
                $validity_to=date('d-M-Y', strtotime($row->date_to));
            }
            
            if($row->name_of_process==1) {
                $name_of_process='Technical Vetting';
            } else if($row->name_of_process==2) {
                $name_of_process='Business vetting approval';
            } else if($row->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
            } else if($row->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
            } else if($row->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($row->name_of_process==6) {
                $name_of_process='Charter party final signature (after C/P complete)';
            } else if($row->name_of_process==7) {
                $name_of_process='Fixture note final signature (after FN complete)';
            } else if($row->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($row->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
            } else if($row->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
            }
            
            $entity=$this->cargo_model->getEntityById($row->RecordOwner);
            
            $html .='<tr>';
            $html .='<td style="border: 1px solid;">'.date('d-m-Y', strtotime($row->UserDate)).'</td>';
            $html .='<td style="border: 1px solid;">'.$name_of_process.'</td>';
            $html .='<td style="border: 1px solid;">'.$row->process_flow_sequence.'</td>';
            $html .='<td style="border: 1px solid;">'.$finalization_completed_by.'</td>';
            $html .='<td style="border: 1px solid;">'.$validity_from.'</td>';
            $html .='<td style="border: 1px solid;">'.$validity_to.'</td>';
            $html .='<td style="border: 1px solid;">'.$status.'</td>';
            $html .='<td style="border: 1px solid;">'.$entity->EntityName.'</td>';
            $html .='</tr>';
        }
        $html .='</table>';
        $pdfFilePath='BussinessProcess.pdf';
        include_once APPPATH.'third_party/mpdf.php';
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }

    if($Outputformat=='EXCEL') {
        $Content = "Date,Process Name,Sequence ID,Approval by,Validity From,Validity To,Status,Record owner \n";
        foreach($data as $row) {
            if($row->status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->finalization_completed_by==1) {
                $finalization_completed_by='Record Owner';
            } else if($row->finalization_completed_by==2) {
                $finalization_completed_by='Invitee Only';
            } else if($row->finalization_completed_by==3) {
                $finalization_completed_by='Record Owner and Invitee jointly';
            } else if($row->finalization_completed_by==4) {
                $finalization_completed_by='Record owner technical vetting team';
            } else if($row->finalization_completed_by==5) {
                $finalization_completed_by='Record owner compliance team';
            } else if($row->finalization_completed_by==6) {
                $finalization_completed_by='Record owner counter party risk assessment team';
            } 
            
            if($row->validity==1) {
                $validity_from='Infinite';
                $validity_to='Infinite';
            }
            
            if($row->validity==2) {
                $validity_from=date('d-M-Y', strtotime($row->date_from));
                $validity_to=date('d-M-Y', strtotime($row->date_to));
            }
            
            if($row->name_of_process==1) {
                $name_of_process='Technical Vetting';
            } else if($row->name_of_process==2) {
                $name_of_process='Business vetting approval';
            } else if($row->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
            } else if($row->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
            } else if($row->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($row->name_of_process==6) {
                $name_of_process='Charter party final signature';
            } else if($row->name_of_process==7) {
                $name_of_process='Fixture note final signature';
            } else if($row->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($row->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
            } else if($row->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
            }
            $entity=$this->cargo_model->getEntityById($row->RecordOwner);
        
            $Content .= date('d-m-Y', strtotime($row->UserDate)).",".$name_of_process.",".$row->process_flow_sequence.",".$finalization_completed_by.",".$validity_from.",".$validity_to.",".$status.",".$entity->EntityName."\n";
        }
        header('Content-Type: application/csv'); 
        $FileName='BussinessProcess.csv';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $Content;
        exit();    
    }
        
    if($Outputformat=='XML') {
        header('Content-type: text/xml');
        $xmloutput="<?xml version=\"1.0\" ?>\n";
        $xmloutput .="<BussinessProcess>\n";
        foreach($data as $row) {
            if($row->status==1) {
                $status='Active';
            } else {
                $status='Inactive';
            }
            if($row->finalization_completed_by==1) {
                $finalization_completed_by='Record Owner';
            } else if($row->finalization_completed_by==2) {
                $finalization_completed_by='Invitee Only';
            } else if($row->finalization_completed_by==3) {
                $finalization_completed_by='Record Owner and Invitee jointly';
            } else if($row->finalization_completed_by==4) {
                $finalization_completed_by='Record owner technical vetting team';
            } else if($row->finalization_completed_by==5) {
                $finalization_completed_by='Record owner compliance team';
            } else if($row->finalization_completed_by==6) {
                $finalization_completed_by='Record owner counter party risk assessment team';
            } 
            
            if($row->validity==1) {
                $validity_from='Infinite';
                $validity_to='Infinite';
            }
            
            if($row->validity==2) {
                $validity_from=date('d-M-Y', strtotime($row->date_from));
                $validity_to=date('d-M-Y', strtotime($row->date_to));
            }
            
            if($row->name_of_process==1) {
                $name_of_process='Technical Vetting';
            } else if($row->name_of_process==2) {
                $name_of_process='Business vetting approval';
            } else if($row->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
            } else if($row->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
            } else if($row->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($row->name_of_process==6) {
                $name_of_process='Charter party final signature';
            } else if($row->name_of_process==7) {
                $name_of_process='Fixture note final signature';
            } else if($row->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($row->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
            } else if($row->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
            }
            
            $entity=$this->cargo_model->getEntityById($row->RecordOwner);
        
            $xmloutput .="\t<Process>\n";
            $xmloutput .="\t\t<UserDate>".date('d-m-Y', strtotime($row->UserDate))."</UserDate>\n";
            $xmloutput .="\t\t<name_of_process>".$name_of_process."</name_of_process>\n";
            $xmloutput .="\t\t<process_flow_sequence>".$row->process_flow_sequence."</process_flow_sequence>\n";
            $xmloutput .="\t\t<finalization_completed_by>".$finalization_completed_by."</finalization_completed_by>\n";
            $xmloutput .="\t\t<validity_from>".$validity_from."</validity_from>\n";
            $xmloutput .="\t\t<validity_to>".$validity_to."</validity_to>\n";
            $xmloutput .="\t\t<status>".$status."</status>\n";
            $xmloutput .="\t\t<EntityName>".$entity->EntityName."</EntityName>\n";
            $xmloutput .="\t</process>\n";
        }
        $xmloutput .="</BussinessProcess>\n";    
        
        header('Content-Type: application/xml'); 
        $FileName = 'BussinessProcess.xml';
        header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
        echo $xmloutput;
        exit();            
    }
}
    
public function updateEntityRolePermission()
{
    $flg=$this->masters_model->updateEntityRolePermission();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
        
}
    
public function getEntityRolePermission()
{
    $data['details']=$this->masters_model->getEntityRolePermission();
    //print_r($data); die;
    echo json_encode($data);
}
    
public function deleteRoleAttachedFile()
{
    $flg=$this->masters_model->deleteRoleAttachedFile();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
    
}
    
public function view_priority_file_attached()
{
        
    $row=$this->masters_model->getEntityRolePermission();
    //echo $filename;
    $filename=$row->AttachedFile;
    //print_r($filename); die;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
    //print_r($url); die;
    $nar=explode("?", $url);
    $data=current($nar);
    $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
    echo $html;
}
    
public function getEntityTypeRole()
{
    $data['records']=$this->masters_model->getEntityTypeRole();
    echo json_encode($data);
        
}
    
public function checkInviteePrimeRole()
{
    $data['records']=$this->masters_model->getInviteeRecordByEntity();
    echo json_encode($data);
}
    
public function saveBusinessProcessRule()
{
    $flg=$this->masters_model->saveBusinessProcessRule();
    echo $flg;
}
    
public function getBusinessProcessRule()
{
    $this->load->model('cargo_model', '', true);
    $this->load->model('cp_fn_model', '', true); 
    $data=$this->masters_model->getBusinessProcessRule();
    //print_r($data); die;
    $html='';
    $inhtml='';
    $i=1;
    $html ='{ "aaData": [';
    foreach($data as $row) {
        $entityname=$this->cargo_model->getEntityById($row->RecordOwner);
        $username=$this->cp_fn_model->getUserByID($row->CreatedBy);
        $UpdatedBy='';
        if($row->UpdatedBy) {
            $updateduser=$this->cp_fn_model->getUserByID($row->UpdatedBy);
            $UpdatedBy=$updateduser->FirstName.' '.$updateduser->LastName;
        }
            
        if($row->BP_Status=='1') {
            $flag='Active';
        } else {
            $flag='Inactive';
        }
            
            $BP_Name='';
        if($row->BP_Name==1) {
            $BP_Name='Technical vetting';
        } else if($row->BP_Name==2) {
            $BP_Name='Business vetting approval';
        } else if($row->BP_Name==3) {
                $BP_Name='Counter party risk assessment';
        } else if($row->BP_Name==4) {
            $BP_Name='Compliance risk assessment';
        } else if($row->BP_Name==5) {
            $BP_Name='Authorization for quotes (by broker)';
        } else if($row->BP_Name==6) {
            $BP_Name='Charter party final signature (after C/P complete)';
        } else if($row->BP_Name==7) {
            $BP_Name='Fixture note final signature (after FN complete)';
        } else if($row->BP_Name==8) {
            $BP_Name='Approval for quotes authorization (by record owner)';
        } else if($row->BP_Name==9) {
            $BP_Name='C/P on subjects (charterer)';
        } else if($row->BP_Name==10) {
            $BP_Name='C/P on subjects (Shipowner/Broker)';
        }
            $BP_CompleteBy='';
        if($row->BP_CompleteBy==1) {
            $BP_CompleteBy='Record owner';
        } else if($row->BP_CompleteBy==2) {
            $BP_CompleteBy='Invitee only';
        } else if($row->BP_CompleteBy==3) {
            $BP_CompleteBy='Record owner and invitee jointly';
        } else if($row->BP_CompleteBy==4) {
            $BP_CompleteBy='Record owner technical vetting team';
        } else if($row->BP_CompleteBy==5) {
            $BP_CompleteBy='Record owner compliance team';
        } else if($row->BP_CompleteBy==6) {
            $BP_CompleteBy='Record owner counter party risk assessment team';
        }
            $link='No';
        if($row->link==1) {
            $link='Yes';
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='BP_RuleID[]' value='".$row->BP_RuleID."'>";
            $edit="<a href='javascript: void(0);' onclick='editDocument(".$row->BP_RuleID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->BP_version.'","'.$BP_Name.'","'.$BP_CompleteBy.'","'.$username->FirstName.' '.$username->LastName.'","'.$UpdatedBy.'","'.$link.'","'.$flag.'","'.$entityname->EntityName.'","'.$edit.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getBusinessProcessRuleById()
{
    $data=$this->masters_model->getBusinessProcessRuleById();
    echo json_encode($data);
}
    
public function getBusinessCompletedBy()
{
    $data=$this->masters_model->getBusinessCompletedBy();
    if($data) {
        echo json_encode($data);
    } else {
        echo 1;
    }
}
    
public function updateBusinessProcessRule()
{
    $flg=$this->masters_model->updateBusinessProcessRule();
    echo $flg;
}
    
public function cloneMyParentMaster()
{
    $Company_ID=$this->get_ParentUniqueId();
    $flg=2;
    $res=$this->masters_model->addParentMasterData($Company_ID, $flg);
    //$res=$this->masters_model->cloneMyParentMaster();
    echo $res;
}
    
    // 27-12-2017
    
public function getDocumentStoreByEntityID()
{
    $stype=$this->input->post('stype');
    $stitle=$this->input->post('stitle');
    $flag=0;
    if($stype) {
        if(!$stitle) {
            $flag=1;
        }
    }
        
    $stitle_autocomplete=$this->input->post('stitle_autocomplete');
    $data=$this->masters_model->getDocumentClausesByDocumentTypeID($flag);
    $sclause=$this->input->post('sclause');
    $stext=$this->input->post('stext');
    $ClauseIDs=array();
    $DocumentTypeIDs=array();
        
    foreach($data as $row) {
        $csubstr='';
        $tsubstr='';
        if($sclause && $stext) {
            $csubstr = stripos($row->CaluseName, $sclause); 
            $txt=$this->masters_model->getClauseTextByID($row->ClauseID);
            $tsubstr = stripos($txt, $stext);
            if($csubstr && $tsubstr) {
                $ClauseIDs[]=$row->ClauseID;
                $DocumentTypeIDs[]=$row->DocumentTypeID;
            }
        } else if($sclause) { 
            
            $csubstr = stripos($row->CaluseName, $sclause); 
            
            if($csubstr) {
                    $ClauseIDs[]=$row->ClauseID;
                    $DocumentTypeIDs[]=$row->DocumentTypeID;
            }
        } else if($stext) { 
            $txt=$this->masters_model->getClauseTextByID($row->ClauseID);
            $tsubstr = stripos($txt, $stext);
            if($tsubstr) {
                $ClauseIDs[]=$row->ClauseID;
                $DocumentTypeIDs[]=$row->DocumentTypeID;
            }
        }
        if($sclause=='' && $stext=='') {
            $ClauseIDs[]=$row->ClauseID;
            $DocumentTypeIDs[]=$row->DocumentTypeID;
        }
    } 
        
    $html='';
    for($i=0;$i<count($ClauseIDs);$i++) {
        $txt=$this->masters_model->getClauseTextByID($ClauseIDs[$i]);
        $title=$this->masters_model->getTitleByID($DocumentTypeIDs[$i]);
        $html .='<b>Document title:- '.$title->DocName.'</b>';
        $html .=$txt;
    }
    $html1='';
    if($html != '') {
        if($sclause) {
            $uhtml=strtoupper($html);
            $usclause=strtoupper($sclause);
            $html1='<center><span style="color: red"><b>Total occurrences = '.substr_count($uhtml, $usclause).'</b></span></center>'.$html;
        } else if($stext) {
            $uhtml=strtoupper($html);
            $ustext=strtoupper($stext);
            $html1='<center><span style="color: red"><b>Total occurrences = '.substr_count($uhtml, $ustext).'</b></span></center>'.$html;
        } else {
            $html1=$html;
        }
    }
    if($html != '') {
        echo '<p style="color: blue;"><b>Select text. drag and drop into box, OR , CTRL+C then CTRL+V to copy and paste <br> Use CTRL+F to search multiple occurrences</b></p>'.$html1;
    }
        
}
    
public function getDocumentClausesDocumentTypeID()
{
    $data=$this->masters_model->getDocumentClauses12();
    $html='';
    foreach($data as $row) {
        $txt=$this->masters_model->getClauseTextByID($row->ClauseID);
        $html .=$txt;
    }
    echo $html;
}
    
public function getTitleEntityID()
{
    $res=$this->masters_model->getTitleEntityID();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->DocName;
        $data_arr['value']=$row->DocumentTypeID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getTypeEntityID()
{
    $res=$this->masters_model->getTypeEntityID();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->DocType;
        $data_arr['value']=$row->DocType;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getEntitySigningUsers()
{
    $EID=$this->input->post('EID');
    $data=$this->masters_model->getEntitySigningUsers($EID);
    //print_r($data); die;
    echo json_encode($data);
}
    
public function getEntitySigningUsers1()
{
    $EID=$this->input->post('EID');
    $EID1=$this->input->post('EID1');
    $data['record']=$this->masters_model->getEntitySigningUsers($EID);
    $data['ship']=$this->masters_model->getEntitySigningUsers($EID1);
    //print_r($data); die;
    echo json_encode($data);
}
    
public function saveShipOwnerEntityData()
{
    $ret=$this->masters_model->saveShipOwnerEntityData();
    echo $ret;
}
    
public function get_shipowner_entity_data()
{
    //echo 'test'; die;
    $data=$this->masters_model->get_shipowner_entity_data();
    //print_r($data);die;
    $html='';
    $inhtml='';
    $html ='{ "aaData": [';
    $i=1;
    foreach($data as $row) {
        $Status='';
        if($row->Status=='1') {
            $Status='Active';
        } else {
            $Status='Inactive';
        }
        $FixtureDigitallySignBy='';
        if($row->FixtureDigitallySignBy=='1') {
            $FixtureDigitallySignBy='Shipbroker invitee only';
        } else if($row->FixtureDigitallySignBy=='2') {
            $FixtureDigitallySignBy='Shipowner entity only';
        } else if($row->FixtureDigitallySignBy=='3') {
                $FixtureDigitallySignBy='Shipowner or Shipbroker (either)';
        }
            $CpDigitallySignBy='';
        if($row->CpDigitallySignBy=='1') {
            $CpDigitallySignBy='Shipbroker invitee only';
        } else if($row->CpDigitallySignBy=='2') {
            $CpDigitallySignBy='Shipowner entity only';
        } else if($row->CpDigitallySignBy=='3') {
            $CpDigitallySignBy='Shipowner or Shipbroker (either)';
        }
            
            //$check="<input class='chkNumber' type='checkbox' name='BSA_ID[]' value='".$row->BSA_ID."'>";
            $viewUsers="<a onclick='getAuthorisedUsers(".$row->BSA_ID.")'>view</a>";
            $edit="<a href='javascript: void(0);' onclick='editShipOwner1(".$row->BSA_ID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $delete="<a href='javascript: void(0);' onclick='deleteShipOwner1(".$row->BSA_ID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'","'.$row->ShipOwnerName.'","'.$FixtureDigitallySignBy.'","'.$CpDigitallySignBy.'","'.$viewUsers.'","'.$Status.'","'.$row->OwnerName.'","'.$edit.'&nbsp;&nbsp;'.$delete.'"],';
            $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function view_authorised_shipowner_users()
{
    $data['fixture']=$this->masters_model->get_authorised_shipowner_users(1); // 1-fixture , 2-cp
    $data['cp']=$this->masters_model->get_authorised_shipowner_users(2);
    //print_r($data);die;
    echo json_encode($data);
}
    
public function deleteShipOwnerEntityData()
{
    $ret=$this->masters_model->deleteShipOwnerEntityData();
    if($ret) {
        echo 1;
    }else {
        echo 0;
    }
}
    
public function getEntityShipOwnerById()
{
    $data['authority']=$this->masters_model->getEntityShipOwnerAuthorityById();
    $data['users']=$this->masters_model->getEntityShipOwnerUsersById();
    $data['attach']=$this->masters_model->getEntityShipOwnerAttachmentById();
    //print_r($data['authority']); die;
    echo json_encode($data); 
}
    
public function viewEntityShipownerAttachment()
{
    $fileData=$this->masters_model->getEntityShipownerAttachment();
    //print_r($fileData); die;
        
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);

    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$fileData->UploadFileName, 3600);
    //print_r($url); die;
    $nar=explode("?", $url);
    $data=current($nar);
    $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
    echo $html;
}
    
public function deleteEntityShipownerAttachment()
{
    $ret=$this->masters_model->deleteEntityShipownerAttachment();
    if($ret) {
        echo 1;
    }else {
        echo 0;
    }
}
    
public function deleteEntityShipownerUsers()
{
    $ret=$this->masters_model->deleteEntityShipownerUsers();
    if($ret) {
        echo 1;
    }else {
        echo 0;
    } 
    //echo 1;
}
    
public function updateShipownerEntityData()
{
    $ret=$this->masters_model->updateShipownerEntityData();
    if($ret) {
        echo 1;
    }else {
        echo 0;
    } 
    //echo 1;
}
    
public function getDditableDocumentTypeByEntityId()
{
    $data=$this->masters_model->getDditableDocumentTypeByEntityId();
    echo json_encode($data);
}
    
public function getEditableDocumentTitleByEntityId()
{
    $data=$this->masters_model->getEditableDocumentTitleByEntityId();
    echo json_encode($data);
}
    
public function getFixNotTemplateByEntityID()
{
    $data=$this->masters_model->getFixNotTemplateByEntityID();
    echo json_encode($data);
}
    
public function getFixNotTemplateConfigureByTemplateID()
{
    $data=$this->masters_model->getFixNotTemplateConfigureByTemplateID();
    echo json_encode($data);
}
    
    // end sujeet
    
public function checkShipownerPermission()
{
    $data['records']=$this->masters_model->checkShipownerPermission();
    $data['user_permission']=$this->masters_model->getUserPermission();
    echo json_encode($data);
    
}
public function checkChartererPermission()
{
    $data=$this->masters_model->checkChartererPermission();
    if($data) {
        echo 1;
    } else {
        echo 0;
    }
    
}
public function getLeftHeading()
{
    $data=$this->masters_model->getLeftHeading();
    echo json_encode($data);
}
    
public function getLeftSubHeading()
{
    $data=$this->masters_model->getLeftSubHeading();
    echo json_encode($data);
}
    
public function getHelpTextBySubHeading()
{
    $data=$this->masters_model->getHelpTextBySubHeading();
    echo json_encode($data);
}
    
public function updateHelpText()
{
    $data=$this->masters_model->updateHelpText();
    echo $data;
}
    
public function getBACHistory()
{
    $data['record']=$this->masters_model->getBACHistory();
    echo json_encode($data);
        
}
    
public function get_email()
{
    $res=$this->masters_model->get_email();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->Email;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
    
    
public function getTidMid()
{

    $res=$this->masters_model->getTidMid();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='MasterID: '.$row->AuctionID.' || Invitee: '.$row->EntityName.' || TID: '.$row->ResponseID.' || Quote($/mt): '.$row->FreightRate;
        $data_arr['value']=$row->ResponseID.'_'.$row->AuctionID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getDocumentByTid()
{
    $val=$this->input->post('val');
    if($val==1) {
        $data=$this->masters_model->getFixtureDocumentByTid();
        print_r($data);
    } else if($val==2) {
        $data1=$this->masters_model->getChaterPartyDocumentByTid();
        if($data1[1]==1) {
            $nar=explode("?", $data1[0]);
            $data=current($nar);
            $html='<iframe src="http://docs.google.com/gview?url='.$data.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
            print_r($html);
        } else {
            print_r($data1[0]);
        }
            
    } else {
        echo 0;
    }
        
}
    
public function sendEmailFnCp()
{
    $val=$this->input->post('document_type');
    $data='';
    $EditableFlag=1;
    $url='';
    if($val==1) {
        $data=$this->masters_model->getFixtureDocumentByTid();
    } else if($val==2) {
        $data2=$this->masters_model->getChaterPartyDocumentByTid();
        if($data2[1]==1) {
            $nar=explode("?", $data2[0]);
            $data1=current($nar);
            $data='<a href="'.$data1.'"><img src="http://higroove.com/pdf.jpg"></img></a>';
            $EditableFlag=0;
            $url=$data1;
        } else {
            $data=$data2[0];
        }
            
    }
    $r=$this->masters_model->sendEmailFnCp($data, $EditableFlag, $url);
    echo $r;
}
    
    
public function getSentEmail()
{
    $data=$this->masters_model->getSentEmail();
    $i=1;
    $html='';
    $inhtml='';
    $html='{ "aaData": [';    
    foreach($data as $row) {
        if($row->DocumentType==1) {
            $DocumentType="Fixture Note";
        } else if($row->DocumentType==2) {
            $DocumentType="Charter Party";
        }
            
        if($row->SendCount==1) {
            $sent_flag="<b style='color: green'>Sent (original)</b>";
        } else if($row->SendCount > 1) {
            $sent_flag="<b style='color: green'>Sent (duplicate)</b>";
        } else {
                $sent_flag="<b style='color: red'>Not sent</b>";
        }
            
        if(($row->InvID == $row->ShipOwnerID) && ($row->InvID == $row->ToEntityID)) {
            $RecordType="Ship Owner";
        } else if(($row->InvID != $row->ShipOwnerID) && ($row->InvID == $row->ToEntityID)) {
            $RecordType="Broker";
        } else if(($row->InvID != $row->ShipOwnerID) && ($row->ShipOwnerID == $row->ToEntityID)) {
            $RecordType="Ship Owner";
        } else if(($row->InvID != $row->ToEntityID)) {
            $RecordType="Record Owner";
        }
            
            //$ckbx="<input type='checkbox' name='SEID[]' value= style='margin-bottom: 6px;' class='chk'>";
        if(strlen($row->Comment)>30) {
            $comment="<span title='".$row->Comment."'>".substr($row->Comment, 0, 30)."...</span>";
        } else {
            $comment="<span>".$row->Comment."</span>";    
        }
            $mail="<a href='javascript: void(0);' onclick=send_email('".$row->SEID.'_'.$row->EditableFlag."') title='Click here to send message'><i class='fa fa-envelope fa_mail'></i></a>";
            $html_view="<a href='javascript: void(0);' onclick=HtmlView('".$row->SEID.'_'.$row->EditableFlag."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->SentDate)).'","'.$row->FromEmail.'","'.$row->ToEmail.'","'.$RecordType.'","'.$row->MIDTID.'","'.$DocumentType.'","'.$comment.'","'.$sent_flag.'","'.$mail.'&nbsp;&nbsp;'.$html_view.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getfncp()
{
    $res=$this->masters_model->getTidMid();
    $html='';
    foreach($res as $row){
        $html .='<tr>';
        $html .='<td>'.$row->AuctionID.'</td>';
        $html .='<td>'.$row->EntityName.'</td>';
        $html .='<td>'.$row->ResponseID.'</td>';
        $html .='<td>'.$row->FreightRate.'</td>';
        $html .='</tr>';
            
    }
    echo $html;
}
    
public function resendEmail()
{
    $data=$this->masters_model->resendEmail();
    echo $data;
}
public function getEmailBySEID()
{
    $data=$this->masters_model->getEmailBySEID();
    echo $data;
}
    
public function emailDownload()
{
    $data=$this->masters_model->getEmailBySEID();
    include_once APPPATH.'third_party/mpdf.php';
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $filename = "Document.pdf";
    $pdf->WriteHTML($data);
    $pdf->Output($filename, "D");
}
    
public function getUserEmailById()
{
    $data=$this->masters_model->getUserEmailById();
    echo $data->Email;
}
    
public function getEmailsByResponseId()
{
        
    $id=$this->input->post('ids');
    $trimids=trim($id, ",");
    $ids=explode(",", $trimids);
    $AllEmails=array();
    if (in_array(1, $ids)) {
        $ro_email=$this->masters_model->getRecordOwnerEmail();
        $AllEmails['email'][]=$ro_email->Email;
        $AllEmails['emailid'][]=$ro_email->ID;
            
    }
        
    if (in_array(2, $ids)) {
        $ship_owner_email=$this->masters_model->getShipOwnerEmail();
        foreach($ship_owner_email as $row) {
            $AllEmails['email'][]=$row->Email;
            $AllEmails['emailid'][]=$row->ID;
        }
            
    }
        
    if (in_array(3, $ids)) {
        $ship_broker_email=$this->masters_model->getShipBrokerEmail();
        if($ship_broker_email !=0) {
            
            foreach($ship_broker_email as $row1) {
                $AllEmails['email'][]=$row1->Email;
                $AllEmails['emailid'][]=$row1->ID;
            }
        }
            
    }
    echo json_encode($AllEmails);
}
    
public function sendForForgetPassword()
{
    $ret=$this->masters_model->checkUserRecordWithEmail();
    echo json_encode($ret);
}
    
public function verifyOtpForgetPassword()
{
    $OTP=$this->input->post('OTP');
    $Session_OTP=$this->session->userdata('otp');
    $expire_time=$this->session->userdata('expire');
    $current=time();
    if($current <= $expire_time) {
        if($OTP==$Session_OTP) {
            echo 1;
        } else {
            echo 3;
        }
    } else {
        echo 2;
    }
}
    
public function updateUserForgetPassword()
{
    $flg=$this->masters_model->updateUserForgetPassword();
    if($flg) {
        echo 1;
    }else{
        echo 2;
    }
        
}
    
public function verifySecretAnswer()
{
    $flg=$this->masters_model->verifySecretAnswer();
    echo $flg;
}
    
public function getCustomerAdmin()
{
    $cnt=$this->masters_model->getCustomerAdmin();
    echo $cnt;
}
    
public function sendForUnlockAccount()
{
    $usr_record=$this->masters_model->checkUserUnlockAccount();
    echo json_encode($usr_record);
}
    
public function verifyOtpUnlockAccount()
{
    $OTP=$this->input->post('OTP');
    $login_id=$this->input->post('login_id');
    $Session_OTP=$this->session->userdata('otp');
    $expire_time=$this->session->userdata('expire');
    $userID=$this->session->userdata('userID');
    $current=time();
    if($current <= $expire_time && $userID==$login_id) {
        if($OTP==$Session_OTP) {
            echo 1;
        } else {
            echo 3;
        }
    } else {
        echo 2;
    }
}
    
public function getUserContentByLoginID()
{
    $userID=$this->session->userdata('userID');
    $login_id=$this->input->post('login_id');
    if($login_id==$userID) {
        $data['record']=$this->masters_model->getUserContentByLoginID();
        echo json_encode($data);
    } else {
        echo 2;
    }
        
}
    
public function saveRecordForUnlockUser()
{
    $flg=$this->masters_model->saveRecordForUnlockUser();
    $this->masters_model->sendMessageToCustomerAdmin();
    if($flg) {
        echo 1;
    } else {
        echo 2;
    }
        
}
    
    
    // sujeet 24-03-2018
    
public function saveNewUser()
{
    $data=$this->masters_model->saveNewUser();
    echo $data;
}
    
public function getNewUsers()
{
    $EntityID=$this->input->get('EntityID');
    $i=1;
    $data=$this->masters_model->getNewUsers();
    $inhtml='';
    $status='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        if($row->status==0) {
            $status='Pending';
        } else if($row->status==1) {
            $status='Approved';
        } else if($row->status==2) {
            $status='Deny';
        } else {
            $status='';
        }
        if($row->RegisterFor==1) {
                $RegisterFor='New User';
        } else if($row->RegisterFor==2) {
                $RegisterFor='Unlock User';
        } else {
            $RegisterFor='';
        }
        if($row->status==1) {
            //$check="<input class='chkNumber' type='checkbox' name='ids[]' disabled value='".$row->NUID."'>";
            $edit="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
        } else {
            //$check="<input class='chkNumber' type='checkbox' name='ids[]' value='".$row->NUID."'>";    
            $edit="<a href='javascript: void(0);' onclick='editEntityUsers(".$row->NUID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
        }
            $datetime=date('d-m-Y H:s:i', strtotime($row->add_date));
            $inhtml .='["'.$i.'","'.$datetime.'","'.$RegisterFor.'","'.$status.'","'.$row->fname.' '.$row->lname.'","'.$row->email_id.'","'.$row->mobile_no.'","'.$row->username.'","'.$row->pwd.'","'.$row->EntityName.'","'.$edit.'"],';
            $i++;
    }
    
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
        
}
    
public function getNewUserById()
{
    $data['NewUser']=$this->masters_model->getNewUserById();
    $data['NewUserEmail']=$this->masters_model->getNewUserEmailById();
    $data['NewUserPhone']=$this->masters_model->getNewUserPhoneById();
    echo json_encode($data);
}
    
public function updateNewUser()
{
    $approve=$this->input->post('approve');
    $data=$this->masters_model->updateNewUser();
    if($approve==1) {
        $data1=$this->masters_model->addNewUser();
    }
    echo $data;
}
    
public function unlockEntityUser()
{
    $approve=$this->input->post('approve');
    $data=$this->masters_model->updateNewUser();
    if($approve==1) {
        $data1=$this->masters_model->unlockEntityUser();
    }
    echo $data;
}
    
public function getCustomerAdminUsers()
{
    $data['record']=$this->masters_model->getCustomerAdminUsers();
    if($data['record']) {
        echo json_encode($data);
    } else {
        echo 1;
    }
}
    
public function updateUserPassword()
{
    $flg=$this->masters_model->updateUserPassword();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
        
}
    
public function checkEntityShipOwner()
{
    $flg=$this->masters_model->checkEntityShipOwner();
    echo $flg;
}
    
public function all_entity_data_for_fixture()
{
    //echo 'test'; die;
    $res=$this->vessel_master_model->all_entity_Data();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->EntityName;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
    
    
    
    
    
    
    
    
public function getVesselForFixture()
{
    $res=$this->masters_model->getVesselMasters();
    $data_arr=array();
    $return_arr = array();
        
    foreach($res as $row){
        $data_arr['label']=$row->VesselName.' || '.$row->DWT.' || '.$row->Description;
        $data_arr['value']=$row->IMONumber;
        $data_arr['DWT']=$row->DWT;                
        $data_arr['Draught']=$row->Draught;                
        $data_arr['Displacement']=$row->Displacement;                
        $data_arr['Length']=$row->Length;                
        $data_arr['Breadth']=$row->Breadth;    
        array_push($return_arr, $data_arr);
    }
    foreach($res as $row){
        if($row->VesselExName) {
            $data_arr['label']=$row->VesselExName.' || '.$row->DWT.' || '.$row->Description;
            $data_arr['value']=$row->IMONumber;
            $data_arr['DWT']=$row->DWT;                
            $data_arr['Draught']=$row->Draught;                
            $data_arr['Displacement']=$row->Displacement;                
            $data_arr['Length']=$row->Length;                
            $data_arr['Breadth']=$row->Breadth;    
            array_push($return_arr, $data_arr);
        }
            
    }
        
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getVoyageCalResult()
{
    $cal_type=$this->input->post('cal_type');
    $cal_val=$this->input->post('cal_val');
    $data=$this->masters_model->getVoyageCalculation();
    //print_r(count($data)); die;
    $i=1;
    $j=1;
        
    $data_arr=array();
    $return_arr=array();
    if($cal_type==1) {
        foreach($data as $row){
            $TotalRev=$cal_val*(int)$row->VesselDWT;
            $VoyagePortCost=(int)$row->VoyagePortCost;
            $VoyageFualCost=(float)$row->VoyageFualCost;
            $Rev_B4_Exp_CapExp=$TotalRev-($VoyagePortCost+$VoyageFualCost);
            $VoyageOpExp=(float)$row->VoyageOpExp;
            $VoyageCapExp=(float)$row->VoyageCapExp;
            $VoyageTime=(float)$row->VoyageTime;
                
            $Rev_After_Exp=$TotalRev-($VoyagePortCost+$VoyageFualCost+$VoyageOpExp+$VoyageCapExp);
                
            $output=number_format($Rev_After_Exp/$VoyageTime, 2); // return TCE value
                
            if($i%4==0) {
                $data_arr[$row->VesselName]=$output;
                $data_arr['name']=$row->VoyageName;
                if($j==1) {
                    $data_arr['voyage_name']="Hed - Qing";
                } else if($j==2) {
                    $data_arr['voyage_name']="Hed - Qing - Dal";
                } else if($j==3) {
                    $data_arr['voyage_name']="Hed - Para";
                } else if($j==4) {
                    $data_arr['voyage_name']="New - Para";
                }
                    
                    array_push($return_arr, $data_arr);
                    $data_arr=array();
                    $j++;
            } else {
                $data_arr[$row->VesselName]=$output;
            }
            $i++;
        }
    } else if($cal_type==2) {
        foreach($data as $row){
            $VoyageTime=(float)$row->VoyageTime;
            $Rev_After_Exp=$cal_val*$VoyageTime;
            $VoyageCapExp=(float)$row->VoyageCapExp;
            $VoyageOpExp=(float)$row->VoyageOpExp;
            $VoyageFualCost=(float)$row->VoyageFualCost;
            $VoyagePortCost=(int)$row->VoyagePortCost;
            $VesselDWT=(int)$row->VesselDWT;
            //print_r($VesselDWT); die;
            $TotalRev=$Rev_After_Exp+($VoyagePortCost+$VoyageFualCost+$VoyageOpExp+$VoyageCapExp);
            $output=number_format($TotalRev/$VesselDWT, 3); // return freight value
                
            if($i%4==0) {
                $data_arr[$row->VesselName]=$output;
                $data_arr['name']=$row->VoyageName;
                if($j==1) {
                    $data_arr['voyage_name']="Hed - Qing";
                } else if($j==2) {
                    $data_arr['voyage_name']="Hed - Qing - Dal";
                } else if($j==3) {
                    $data_arr['voyage_name']="Hed - Para";
                } else if($j==4) {
                    $data_arr['voyage_name']="New - Para";
                }
                    array_push($return_arr, $data_arr);
                    $data_arr=array();
                    $j++;
            } else {
                $data_arr[$row->VesselName]=$output;
            }
            $i++;
        }
            
    }
        
    echo json_encode($return_arr);
        
}
    
    
public function getCargoByStatus()
{
    $data=$this->masters_model->getCargoByStatus();
    $datap=$this->masters_model->getCargoByDateP();
    $datac=$this->masters_model->getCargoByDateC();
    $dataa=$this->masters_model->getCargoByDateA();
    $datapr=$this->masters_model->getCargoByDatePR();
    $dataw=$this->masters_model->getCargoByDateW();
    $laycan_from=$this->input->post('laycan_from');
    $laycan_to=$this->input->post('laycan_to');
    if($laycan_from) {
        $sd[0]=$laycan_from;
    } else {
        $sd[0]=date('d-m-Y');
    }
    if($laycan_to) {
        $sd[1]=$laycan_to;
    } else {
        $sd[1]=date('d-m-Y', strtotime("+5 months"));
    }
        
    $p=0;$c=0;$pr=0;$a=0;$w=0;
    foreach($data as $row) {
        if($row->auctionStatus=="P") {
            $p++;
        }
        if($row->auctionStatus=="C") {
            $c++;
        }
        if($row->auctionExtendedStatus=="PNR") {
            $pr++;
        }
        if($row->auctionExtendedStatus=="A") {
            $a++;
        }
        if($row->auctionExtendedStatus=="W") {
            $w++;
        }
    }
    $arrdata[0]=array("label"=>"Pending","y"=>$p);
    $arrdata[1]=array("label"=>"Completed","y"=>$c);
    $arrdata[2]=array("label"=>"Pending Release","y"=>$pr);
    $arrdata[3]=array("label"=>"Activated","y"=>$a);
    $arrdata[4]=array("label"=>"Withdrawn","y"=>$w);
    $ad[0]=$arrdata;
    $ad[1]=$datap;
    $ad[2]=$datac;
    $ad[3]=$dataa;
    $ad[4]=$datapr;
    $ad[5]=$dataw;
        
    $ad[6]=$sd;
    echo json_encode($ad);
}
    
public function getLatLong()
{
    $loadport=$this->input->post('loadport');
    $disport=$this->input->post('disport');
    $direction=$this->input->post('direction');
    $data1=$this->masters_model->getLatLongAsc();
    $data2=$this->masters_model->getLatLongDesc();
    $temp_arr=array();
    $ret_arr=array();
    $ascflg=0;
    foreach($data1 as $row1) {
        if($row1->PortID==$loadport) {
            $ascflg=1;    
        }
        if($ascflg==1 ) {
            $temp_arr=array(
            'Lat'=>$row1->Lat,
            'Long'=>$row1->Long,
            'pflag'=>$row1->pflag,
            'PortID'=>$row1->PortID,
            'FormToFlag'=>$row1->FormToFlag
            );
            array_push($ret_arr, $temp_arr);
        }
        if($row1->PortID==$disport && $ascflg==1) {
            break;
        }
    }
    if($direction==2) {
        $ascflg=0;
        $ret_arr1=array();
        $ret_arr2=array();
        $ret_arr3=array();
        foreach($data2 as $row2) {
            if($row2->PortID==$loadport) {
                $ascflg=1;    
            }
            if($ascflg==1) {
                $temp_arr=array(
                'Lat'=>$row2->Lat,
                'Long'=>$row2->Long,
                'pflag'=>$row2->pflag,
                'PortID'=>$row2->PortID,
                'FormToFlag'=>$row2->FormToFlag
                );
                array_push($ret_arr1, $temp_arr);
            }
            if($row2->PortID==$disport && $ascflg==1) {
                break;
            }
        }
        $ret_arr2=array_reverse($ret_arr1);
        $ret_arr3=array_merge($ret_arr, $ret_arr2);
        echo json_encode($ret_arr3);
    } else {
        echo json_encode($ret_arr);    
    }
        
}
    
public function getVesselForDashboard()
{
    $res=$this->masters_model->getVesselMasters();
    $data_arr=array();
    $return_arr = array();
        
    foreach($res as $row){
        $data_arr['label']=$row->VesselName.' || '.$row->DWT.' || '.$row->Description;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    foreach($res as $row){
        if($row->VesselExName) {
            $data_arr['label']=$row->VesselExName.' || '.$row->DWT.' || '.$row->Description;
            $data_arr['value']=$row->ID;
            array_push($return_arr, $data_arr);
        }
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function getActiveEntityType()
{
    $data=$this->masters_model->getActiveEntityType();
    echo json_encode($data);    
}
    
    //-----------------country master--------------------
    
public function getCountry()
{
    //$this->load->library('encrypt');
    $data=$this->masters_model->getCountry();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        
        $encodedid=$this->EncodeDecode->str_encode($row->ID);
        //$key = EncriptDecriptKey;
        //$encodedid = $this->encrypt->encode($row->ID , $key);

        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$encodedid."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}

    
public function saveCountryMaster()
{
    $data=$this->masters_model->saveCountryMaster();
    echo $data;
}
    
public function getCountryById()
{
    $data=$this->masters_model->getCountryEdityById();
    echo json_encode($data);
}
    
public function updateCountryMaster()
{
    $data=$this->masters_model->updateCountryMaster();
    echo $data;
}
    
public function deleteCountryMaster()
{
    $data=$this->masters_model->deleteCountryMaster();
    echo $data;
}
    //---- end country ----------
    
public function getCargoTemplateData()
{
    $data=$this->masters_model->getCargoTemplateData();
    //print_r($data); die;
    $i=1;
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->Status) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        if($row->TemplateLinkFlg==1) {
            $TemplateLinkFlg='Yes';
        } else {
            $TemplateLinkFlg='No';
        }
            $edit="<a href='javascript: void(0);' onclick=editDocument('".$row->CT_ID.'_'.$row->TemplateLinkFlg."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $clone="<a href='javascript: void(0);' onclick=cloneTemplate('".$row->CT_ID.'_'.$row->TemplateLinkFlg."') title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>";
            $config="<a href='javascript: void(0);' onclick=configuration('".$row->CT_ID.'_'.$row->TemplateLinkFlg."') title='Click here to configure record'><i class='fa fa-cogs fa_congig'></i></a>";
            $html_view="<a href='javascript: void(0);' onclick=HtmlView('".$row->CT_ID.'_'.$row->TemplateLinkFlg."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $form_view="<a href='javascript: void(0);' onclick=cargoFormView('".$row->CT_ID.'_'.$row->TemplateLinkFlg."') title='Click here to Form View'><i class='fa fa-share-square fa_form_view'></i></a>";
            
            
            $version='Version '.$row->Version;
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'","'.$version.'","'.$row->CT_Name.'","'.$TemplateLinkFlg.'","'.$status.'","'.$row->EntityName.'","'.$edit.'&nbsp;&nbsp;'.$config.'&nbsp;&nbsp;'.$clone.'&nbsp;&nbsp;'.$html_view.'&nbsp;&nbsp;'.$form_view.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
        
}
    
public function saveCargoTemplate()
{
    $data=$this->masters_model->saveCargoTemplate();
    echo $data;
}
    
public function getCargoTemplateById()
{
    $data=$this->masters_model->getCargoTemplateById();
    echo json_encode($data);
        
}
    
public function updateCargoTemplate()
{
    $data=$this->masters_model->updateCargoTemplate();
    echo $data;
        
}
    
public function cloneCargoTemplate()
{
    $data=$this->masters_model->cloneCargoTemplate();
    echo $data;
        
}
    
public function deleteCargoTemplate()
{
    $data=$this->masters_model->deleteCargoTemplate();
    echo $data;
        
}
    
public function saveTemplateSection()
{
    $data=$this->masters_model->saveTemplateSection();
    echo $data;
        
}
    
public function deleteTemplateSection()
{
    $flg=$this->masters_model->deleteTemplateSection();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
}
    
public function saveTemplateSubsection()
{
    $flg=$this->masters_model->saveTemplateSubsection();
    if($flg) {
        $data['flg']=1;
    } else {
        $data['flg']=0;
    }
    $data['record']=$this->masters_model->getTemplateSubsections();
    echo json_encode($data);
}
    
public function getCargoTemplateSectionData()
{
    $data=$this->masters_model->getCargoTemplateSectionData();
        
    $i=1;
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        if($row->SectionStatus==1) {
            $SectionStatus="Active";
        } else if($row->SectionStatus==2) {
            $SectionStatus="Inactive";
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->CTS_ID."'>";
            
        $action="<a href='javascript: void(0);' onclick='editDocument(".$row->CTS_ID.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick='deleteSection(".$row->CTS_ID.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
        $a="<a onclick='getsubsections(".$row->CTS_ID.");'> View</a>";
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDate)).'","'.$row->SectionSeqNo.'","'.$row->SectionName.'","'.$a.'","'.$SectionStatus.'","'.$action.'"],';
        $i++;
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
        
}
    
public function deleteTemplateSubsection()
{
    $flg=$this->masters_model->deleteTemplateSubsection();
    if($flg) {
        echo 1;
    } else {
        echo 0;
    }
}
    
public function getTemplateSubsection()
{
    $data['record']=$this->masters_model->getTemplateSubsection();
    $data['Penalty_applies_on']=$this->masters_model->getTemplateSubsectionPenaltyAppliesOn($data['record']->PenalityAppliesOnCTSS_ID);
    $data['penality']=$this->masters_model->getTemplateSubsectionPenality();
    echo json_encode($data);
        
}
    
public function updateTemplateSubsection()
{
    $flg=$this->masters_model->updateTemplateSubsection();
    if($flg) {
        $data['flg']=1;
    } else {
        $data['flg']=0;
    }
    $data['record']=$this->masters_model->getTemplateSubsections();
    echo json_encode($data);
        
}
    
public function getCargoTemplateView()
{
    $cargo_template=$this->masters_model->getCargoTemplateRecord();
    $cargo_sections=$this->masters_model->getCargoTemplateSectionsRecord();
        
    $html='<table class="table table-bordered table-striped" >
				<tr>
					<td colspan="3" style="background-color: #1b75bc; color: white;"><b>'.$cargo_template->CT_Name.'</b></td>
				</tr>
				<tr>
					<td colspan="3" ><b>'.date('d-m-Y', strtotime($cargo_template->CreatedDate)).'</b></td>
				</tr>
				<tr>
					<td colspan="3" ><b>Version '.$cargo_template->Version.'</b></td>
				</tr>';
                
    foreach($cargo_sections as $cr){
        $cargo_subsections=$this->masters_model->getCargoTemplateSubsectionsRecords($cr->CTS_ID);
        $html .='<tr>
					<td width="120px;">'.$cr->SectionSeqNo.'</td>
					<td style="background-color: #7b6767; color: white;" colspan="2">'.$cr->SectionName.'</td>
				</tr>';
        foreach($cargo_subsections as $crs){
            $html .='<tr><td width="120px;">&nbsp;&nbsp;&nbsp;&nbsp;'.$cr->SectionSeqNo.'.'.$crs->SubSectionSeqNo.'</td>
					<td>'.$crs->SubSectionLabelName.'</td><td width="40%;">&nbsp;</td></tr>';
            if($crs->PenalityApplies==1) {
                $penalities=$this->masters_model->getCargoTemplateSubsectionsPenalities($crs->CTSS_ID);
                foreach($penalities as $p){
                    $html .='<tr><td width="120px;"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$cr->SectionSeqNo.'.'.$crs->SubSectionSeqNo.'.'.$p->PenalitySeqNo.'</td>
					<td>'.$p->PenalityLabelName.'</td><td width="40%;">&nbsp;</td></tr>';
                }
            }    
        }            
    }
    $html .='</table>';
    echo $html;
}
    
public function getTemplateSubsections()
{
    $CTS_ID=$this->input->post('CTS_ID');
    $cargo_subsections=$this->masters_model->getTemplateSubsectionsByTemplateID($CTS_ID);
    //print_r($cargo_subsections); die;
    $html='';
    if($cargo_subsections) {
        foreach($cargo_subsections as $sub){
            if($sub->SubSectionFieldType==1) {
                $SubSectionFieldType='Textbox';
            } else if($sub->SubSectionFieldType==2) {
                $SubSectionFieldType='DropDown';
            }
            if($sub->PenalityApplies==1) {
                $PenalityApplies='Yes';
            } else if($sub->PenalityApplies==2) {
                $PenalityApplies='No';
            }
                $html .='<tr>';
                $html .='<td>'.$sub->SubSectionSeqNo.'</td>';
                $html .='<td>'.$sub->SubSectionLabelName.'</td>';
                $html .='<td>'.$SubSectionFieldType.'</td>';
                $html .='<td>'.$PenalityApplies.'</td>';
                $html .='</tr>';
        }
    } else {
        $html='<tr><td colspan="4">No record exist</td></tr>';
    }
    echo $html;
}
    
function getTemplateSectionData()
{
    $data['section']=$this->masters_model->getTemplateSectionById();
    $data['subsection']=$this->masters_model->getTemplateSubSectionById();
    echo json_encode($data); 
}
    
function updateTemplateSection()
{
    $flg=$this->masters_model->updateTemplateSection();
    if($flg) {
        echo 1;
    } else {
        echo 0;    
    }
}
    
public function getTemplateFormLayoutById()
{
    $data['template']=$this->masters_model->getCargoTemplateByCT_ID();
    $data['sections']=$this->masters_model->getCargoTemplateSectionByCT_ID();
    $data['subsections']=$this->masters_model->getCargoTemplateSubSectionByCT_ID();
    $data['penalitys']=$this->masters_model->getCargoTemplatePenalityByCT_ID();
    echo json_encode($data);
}
    
function getAllSubsectionForSection()
{
    $data=$this->masters_model->getCargoTemplateSubSectionByCTS_ID();
    $html='<option value="">Select</option>';
    $html .='<option value="0">No Penalty applies</option>';
    foreach($data as $d){
        $html .='<option value="'.$d->CTSS_ID.'">'.$d->SubSectionLabelName.'</option>';
    }
    echo $html;
}
    
function getAllSubsectionForSectionPenaltyAppliesOn()
{
    $data=$this->masters_model->getCargoTemplateSubSectionForPenaltyAppliesOnByCTS_ID();
    $html='<option value="">Select</option>';
    $html .='<option value="0">No Penalty applies</option>';
    foreach($data as $d){
        $html .='<option value="'.$d->CTSS_ID.'">'.$d->SubSectionLabelName.'</option>';
    }
    echo $html;
}
    
    //-----------------state master--------------------
    
public function getState()
{
    $data=$this->masters_model->getState();
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->Description.'","'.$row->country.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function getCountryAutocomplete()
{
    $res=$this->masters_model->getCountryAutocomplete();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Description: '.$row->Description.' || Code: '.$row->Code;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function saveStateMaster()
{
    $data=$this->masters_model->saveStateMaster();
    echo $data;
}
    
public function getStateById()
{
    $data=$this->masters_model->getStateById();
    echo json_encode($data);
}
    
public function updateStateMaster()
{
    $data=$this->masters_model->updateStateMaster();
    echo $data;
}
    
public function deleteStateMaster()
{
    $data=$this->masters_model->deleteStateMaster();
    echo $data;
}
    
    //-----------------Currency master--------------------
    
public function getCurrencyMaster()
{
    $data=$this->masters_model->getCurrencyMaster();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveCurrencyMaster()
{
    $data=$this->masters_model->saveCurrencyMaster();
    echo $data;
}
    
public function getCurrencyById()
{
    $data=$this->masters_model->getCurrencyEdityById();
    echo json_encode($data);
}
    
public function updateCurrencyMaster()
{
    $data=$this->masters_model->updateCurrencyMaster();
    echo $data;
}
    
public function deleteCurrencyMaster()
{
    $data=$this->masters_model->deleteCurrencyMaster();
    echo $data;
}
    
    //-----------------port master--------------------
    
public function getPortMasterMain()
{
    $data=$this->masters_model->getPortMasterMain();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $Code=str_replace(',', ' ', $row->Code);
        $Code=str_replace('"', ' ', $Code);
        $Code=str_replace("'", ' ', $Code);
        $PortName=str_replace(',', ' ', $row->PortName);
        $PortName=str_replace('"', ' ', $PortName);
        $PortName=str_replace("'", ' ', $PortName);
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$Code.'","'.$PortName.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function getStateAutocomplete()
{
    $res=$this->masters_model->getStateAutocomplete();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']='Description: '.$row->Description.' || Code: '.$row->Code;
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
        
}
    
public function savePortmaster()
{
    $data=$this->masters_model->savePortmaster();
    echo $data;
}
    
public function getPortById()
{
    $data=$this->masters_model->getPortById();
    echo json_encode($data);
}
    
public function updatePortMaster()
{
    $data=$this->masters_model->updatePortMaster();
    echo $data;
}
    
public function deletePortMaster()
{
    $data=$this->masters_model->deletePortMaster();
    echo $data;
}
        
public function getImageCall()
{
    $dta1['url']='C:\Users\admin\Downloads\suj1.jpg';
    $dta['image']=$dta1;
    $data['inputs']=array(
    'data'=>$dta
    );
        
    $encode_data=json_encode($data);
    $authKey = "a577dc59caa8448893ac920a1d1907e5";  //Your authentication key
        
    $url = "https://api.clarifai.com/v2/models/aaa03c23b3724a16a56b629203edc62c/outputs";
        
    $ch = curl_init(); // init the resource
            
    $headr = array();
        
    $headr[] = 'Authorization: Key '.$authKey;
    $headr[] = 'Content-type: application/json'; 
     
    curl_setopt_array(
        $ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER,$headr,
        CURLOPT_POSTFIELDS => $data
        //,CURLOPT_FOLLOWLOCATION => true
        )
    );
        
    $rest = curl_exec($ch); 
        
    $output = curl_exec($ch);
    print_r($output); die;
    //Print error if any
    if (curl_errno($ch)) {
        echo 'error:' . curl_error($ch);
    }
    curl_close($ch);
        
}
    //-----------------unit-of-measurement--------------------
    
public function unitOfMeasurement()
{
    $data=$this->masters_model->unitOfMeasurement();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->UnitCode.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveunitOfMeasurement()
{
    $data=$this->masters_model->saveunitOfMeasurement();
    echo $data;
}
    
public function getUnitOfMeasurementById()
{
    $data=$this->masters_model->getUnitOfMeasurementById();
    echo json_encode($data);
}
    
public function updateUnitOfMeasurement()
{
    $data=$this->masters_model->updateUnitOfMeasurement();
    echo $data;
}
    
public function deleteUnitOfMeasurement()
{
    $data=$this->masters_model->deleteUnitOfMeasurement();
    echo $data;
}
    
    //-------------title master-----------------
public function titleMaster()
{
    $data=$this->masters_model->titleMaster();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveTitleMaseter()
{
    $data=$this->masters_model->saveTitleMaseter();
    echo $data;
}
    
public function getTitleMasterById()
{
    $data=$this->masters_model->getTitleMasterById();
    echo json_encode($data);
}
    
public function updateTitleMaseter()
{
    $data=$this->masters_model->updateTitleMaseter();
    echo $data;
}
    
public function deleteTitleMaster()
{
    $data=$this->masters_model->deleteTitleMaster();
    echo $data;
}
    //-------------------------secret-question-master----------------------
public function getSecretQuestionMaster()
{
    $data=$this->masters_model->getSecretQuestionMaster();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function getSecretQuestionMasterById()
{
    $data=$this->masters_model->getSecretQuestionMasterById();
    echo json_encode($data);
}
    
    
public function saveSecretQuestionMaster()
{
    $data=$this->masters_model->saveSecretQuestionMaster();
    echo $data;
}
    
public  function updateSecretQuestionMaseter()
{
    $data=$this->masters_model->updateSecretQuestionMaseter();
    echo $data;
}
    
public function deleteSecretQuestionMaster()
{
    $data=$this->masters_model->deleteSecretQuestionMaster();
    echo $data;
}
    //-------------------------terminals-master----------------------

public    function getTerminalsMaster()
{
    $data=$this->masters_model->getTerminalsMaster();
    //print_r($data); die;
            
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Name.'","'.$row->Description.'","'.$status.'"],';
                
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveTerminalMaseter()
{
    $data=$this->masters_model->saveTerminalMaseter();
    echo $data;
}
    
public function getTerminalMasterById()
{
    $data=$this->masters_model->getTerminalMasterById();
    echo json_encode($data);
}
    
public function updateTerminalMaseter()
{
    $data=$this->masters_model->updateTerminalMaseter();
    echo $data;
}
    
public function deleteTerminalMaseter()
{
    $data=$this->masters_model->deleteTerminalMaseter();
    echo $data;
}
    //-------------------------time-zone-master----------------------
public function getTimeZoneMaster()
{
    $data=$this->masters_model->getTimeZoneMaster();
                //print_r($data); die;
                
                $inhtml='';
                $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $DaytimeSaving='';
        if($row->ST_DaytimeSaving==1) {
            $DaytimeSaving='Yes';    
        } else {
            $DaytimeSaving='No';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->GMTDiff.'","'.$DaytimeSaving.'","'.$row->Description.'","'.$status.'"],';
                    
    }
                $html .=trim($inhtml, ",");    
                $html .='] }';
                echo $html;    
}

public function saveTimeZoneMaseter()
{
    $data=$this->masters_model->saveTimeZoneMaseter();
    echo $data;
}

public function getTimeZoneMasterMyId()
{
    $data=$this->masters_model->getTimeZoneMasterMyId();
    echo json_encode($data);
}

public function updateTimeZoneMasterMyId()
{
    $data=$this->masters_model->updateTimeZoneMasterMyId();
    echo $data;
}

public function deleteTimeZoneMaster()
{
    $data=$this->masters_model->deleteTimeZoneMaster();
    echo $data;
}


    //--------------vessel Type Controller------------
    
public function getVesseltypeMaster()
{
    $data=$this->masters_model->getVesseltypeMaster();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->Code.'","'.$row->Description.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveVesseltypeMaster()
{
    $data=$this->masters_model->saveVesseltypeMaster();
    echo $data;
}
    
public function getVesseltypeById()
{
    $data=$this->masters_model->getVesseltypeEdityById();
    echo json_encode($data);
}
    
public function updateVesseltypeMaster()
{
    $data=$this->masters_model->updateVesseltypeMaster();
    echo $data;
}
    
public function deleteVesseltypeMaster()
{
    $data=$this->masters_model->deleteVesseltypeMaster();
    echo $data;
}
//------Vessel Masters Controller---------------
    
    
public function get_VesselMasters()
{
    $data=$this->masters_model->get_VesselMasters();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $status='';
        if($row->ActiveFlag) {
            $status='Active';    
        } else {
            $status='Inactive';    
        }
        $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
        $inhtml .='["'.$check.'","'.$row->DateTime.'","'.$row->VesselName.'","'.$row->IMONumber.'","'.$row->VesselTypeID.'","'.$row->DWT.'","'.$row->Length.'","'.$row->Draught.'","'.$row->Displacement.'","'.$status.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function saveVesselmasters()
{
    $data=$this->masters_model->saveVesselMasters();
    echo $data;
}
    
public function updateVesselmasters()
{
    $data=$this->masters_model->updateVesselMasters();
    echo $data;
}
        
public function getVesseleditById()
{
    $data=$this->masters_model->getVesseleditById();
    echo json_encode($data);
}
public function deleteVesselmasters()
{
    $data=$this->masters_model->deleteVesselMasters();
    echo $data;
}
    
//------------End Vessel Masters--------------

public function sendUserTemporaryDetails()
{
    $this->masters_model->sendTemporaryDetails();
    echo  "<script type='text/javascript'>";
    echo "window.close();";
    echo "</script>";
    exit(); 
}
    
public function getSelectedFixContentById()
{
    $data['template']=$this->masters_model->getSelectedFixTemplateById();
    $data['cp_text']=$this->masters_model->getCpTextByTemplateID();
    $data['records']=$this->masters_model->getSelectedFixContentById();
    echo json_encode($data);
}
    
public function checkPreviousPasswords()
{
    $ret=$this->masters_model->checkPreviousPasswords();
    echo $ret;    
}
    
public function getAllEntityType()
{
    $data['entitytype']=$this->masters_model->getAllEntityType();
    echo json_encode($data);
}
    
public function getSecretQuestions()
{
    $data['secret_question']=$this->masters_model->getSecretQuestions();
    echo json_encode($data);
}
    
    //-------------------blockchain-------------------

public function getCallBlockchain()
{
    $url=BLOCK_CHAIN_URL.'connectivity/';
    $ch = curl_init($url); 
    $ch = curl_init($url);     
    $result = curl_exec($ch);
    print_r($result);
}
    
public function setUserDataBlockChain()
{
    // not in use static testing
    $this->db->select('udt_UserMaster.*,udt_AddressMaster.Email');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_AddressMaster.ID=udt_UserMaster.OfficialAddressID', 'left');
    $this->db->where('udt_UserMaster.ID', 130);
    $query=$this->db->get();
    $rslt=$query->result();
    //print_r($rslt);die;
    foreach($rslt as $row) {    
        if($row->Email=='') {
            $email='';
        } else {
            $email=$row->Email;
        }
        $data = array("auomniId" =>$row->ID,"addedByComp"=>'Auomni',"addedByUsr"=>'Admin',"email" =>$email,'entityId'=>$row->EntityID,"cargoInvitationFlag"=>$row->CargoInvitationFlag,"approveFixtureFinalFlg"=>$row->ApproveFixtureFinalFlg,"signFixtureFinalFlg"=>$row->SignFixtureFinalFlg,"signCPFinalFlg"=>$row->SignCPFinalFlg,"approveCPFinalFlg"=>$row->ApproveCPFinalFlg,"approveTechVettingFlg"=>$row->ApproveTechVettingFlg,"approveBusVettingFinalFlg"=>$row->ApproveBusVettingFinalFlg,"approveCounterPartyFlg"=>$row->ApproveCounterPartyFlg,"approveComplianceFlg"=>$row->ApproveComplianceFlg,"approveQuoteAuthFlg"=>$row->ApproveQuoteAuthFlg,"liftCharterSubjectFlg"=>$row->LiftCharterSubjectFlg,"createInvSubjectFlg"=>$row->CreateInvSubjectFlg,"liftInvSubjectFlg"=>$row->LiftInvSubjectFlg,"liftInvSubjectFlgByCharter"=>$row->LiftInvSubjectFlgByCharter,"signDigitallyFixtureFlg"=>$row->SignDigitallyFixtureFlg,"signDigitallyCPFlg"=>$row->SignDigitallyCPFlg); 
        //print_r($data);die;
    
        $data_string = json_encode($data); 
        //echo $data_string;die;
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
        //print_r($result);die;
        $data=json_decode($result);
        $insArr=array('UID'=>$row->ID,'PrivKey'=>$data->privKey,'PubKey'=>$data->pubKey,'Address'=>$data->address,'BlockchainIndex'=>$data->blockchainIndex,'CreationTx'=>$data->creationTx,'EntityId'=>1,'CreationDate'=>date('Y-m-d H:i:s'));
        $this->db->insert('Udt_AU_UserBlockchainRecord', $insArr);
        $this->db->insert('Udt_AU_UserBlockchainRecord_H', $insArr);
    }
    print_r($data);
}
    
    
    
public function getUserDataBloackChain()
{
    $url=BLOCK_CHAIN_URL.'getUser/1';
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    $data=json_decode($result);
    print_r($data);
        
}
    
public function getUserCountBloackChain()
{
    $url=BLOCK_CHAIN_URL.'getUserCount/';
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    //$data=json_decode($result);
    print_r($result);
}
    
    
public function updateUserCountBloackChain()
{
    // not in use
    $UID=165;
    $EntityMasterID=1;
    $this->db->select('*');
    $this->db->from('Udt_AU_UserBlockchainRecord');
    $this->db->where('UID', $UID);
    $bquery=$this->db->get();
    $rslt=$bquery->row();
        
        
        
    $data = array("blockchainIndex" =>$rslt->BlockchainIndex,"entityId" =>$EntityMasterID,"cargoInvitationFlag"=>1,"approveFixtureFinalFlg"=>1,"signFixtureFinalFlg"=>0,"signCPFinalFlg"=>0,"approveCPFinalFlg"=>1,"approveTechVettingFlg"=>0,"approveBusVettingFinalFlg"=>0,"approveCounterPartyFlg"=>1,"approveComplianceFlg"=>1,"approveQuoteAuthFlg"=>1,"liftCharterSubjectFlg"=>1,"createInvSubjectFlg"=>1,"liftInvSubjectFlg"=>1,"liftInvSubjectFlgByCharter"=>0,"signDigitallyFixtureFlg"=>1,"signDigitallyCPFlg"=>1); 
        
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
    $this->db->insert('Udt_AU_UserBlockchainRecord', $insArr);
    $this->db->insert('Udt_AU_UserBlockchainRecord_H', $insArr);
        
}
    
public function getUserTransactionReceiptBlockchain()
{
    $url=BLOCK_CHAIN_URL.'getTransactionDetails/0x0987915e0553e803b281e6930c3cf0a6b725ea35cec177b70f2d37a101917e9d';
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    $data=json_decode($result);
    print_r($data);
}
    
public function getUserTransactionDetails()
{
    $data1=$this->masters_model->getUserTransactionHashByUID();
    $data2=$this->masters_model->getUserTransactionHashHistory();
    $transactionHas=$data1->CreationTx;
        
    $data['transactions'] = array($transactionHas); 
        
    $data_string = json_encode($data); 
    //print_r($data_string);die;
    $url=BLOCK_CHAIN_URL.'getUserTransactionDetails/';
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
    $data[0]=$transactionHas;
    $data[1]=json_decode($result);
    $data[2]=count($data2);
        
    echo json_encode($data);
        
        
}
    
public function addFixtureNoteBlockchain()
{
    $string = 'This is Fixture note template1';
    $ipfsHash=$this->ipfsDocument($string);
        
    $data = array("fixId" =>1,"version" => "1.0",'entityId'=>2,"aucId"=>'S2A-193-0Y2',"tId"=>10011,"recordId"=>189,"dStatus"=>1,"invConf"=>1,"ownConf"=>1,"uId"=>147,"fixhash"=>"aywesh3r8qy3dyr7wq7ydwedyhdr8jydoq4ysj81","ipfsHash"=>$ipfsHash); 
        
    $data_string = json_encode($data); 
    $url=BLOCK_CHAIN_URL.'addDocument/';
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
    print_r($data);
}
    
public function getFixtuteNoteBlockchain()
{
    $url=BLOCK_CHAIN_URL.'getDocumentByIndex/1';
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    $data=json_decode($result);
    print_r($data);
}
    
    
    
public function getDocTransactionDetails()
{
    $TransactionHash=$this->input->post('TransactionHash');
    $data['transactions'] = array($TransactionHash); 
        
    $data_string = json_encode($data); 
    //print_r($data_string);die;
    $url=BLOCK_CHAIN_URL.'getDocTransactionDetails/';
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
    //$data=json_decode($result);
    print_r($result);
}
    
    
    
public function ipfsDocument($string)
{
    //Save string into temp file
    $file = tempnam(sys_get_temp_dir(), 'POST');
    file_put_contents($file, $string);

    //Post file
    $data = array(
    "uploadedFile"=>'@'.$file,
    );
    //print_r($data);die;
    //$data_string = json_encode($data); 
    $url=BLOCK_CHAIN_URL.'ipfsDocument/';
    $ch = curl_init($url);      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    $result = curl_exec($ch);
    //$data=json_decode($result);
    unlink($file);
    return $result;
        
}
    
public function getFixtureByIdHtmlIpfs()
{
    $FixtureID=$this->input->post('FixtureID');
    $data=$this->masters_model->getIpfsHashByFixtureID($FixtureID);
    $ipfsHash=$data->ipfsHash;
        
    $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    $ReturnData['Data']=$data->HeaderContent.'<br>'.$result;
    $ReturnData['FixtureVersion']=$data->FixtureVersion;
    echo json_encode($ReturnData);
    //print_r($url);
}
    
public function getUserTransactionRecieptHistory()
{
    $data1=$this->masters_model->getUserTransactionHashHistory();
    $hashArray=array();
    foreach($data1 as $row) {
        array_push($hashArray, $row->CreationTx);
    }
        
    $data['transactions'] = $hashArray; 
        
    $data_string = json_encode($data); 
    //print_r($data_string);die;
    $url=BLOCK_CHAIN_URL.'getUserTransactionDetails/';
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
    $data1[0]=$hashArray;
    $data1[1]=json_decode($result);
    //print_r($data1);die;
    echo json_encode($data1);
}
    
    
public function getUserBlockchainByUserId()
{
    $UserID=$this->input->post('UserID');
    $BlockchainIndex=$this->masters_model->getBlockchainIndexByUserID($UserID);
    $url=BLOCK_CHAIN_URL.'getUser/'.$BlockchainIndex;
    $ch = curl_init($url);     
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    $result = curl_exec($ch);
    //$data=json_decode($result);
    echo $result;
        
}
    
public function checkUserLoginExist()
{
    $data=$this->masters_model->checkUserLoginExist();
    if($data) {
        echo 1;
    } else{
        echo 0;
    }
}
    
    
    
    
//------------charter party-----------------------------------------
    
    //----------------banking detail-------------------------------------------

public function getEntityByParentEntity()
{
    $data=$this->masters_model->getEntityByParentEntity();
    echo json_encode($data);
}
    
public function getEntityByParentEntityAutocomplete()
{
    //echo 'test'; die;
    $res=$this->masters_model->getEntityByParentEntityAutocomplete();
    $data_arr=array();
    $return_arr = array();
    foreach($res as $row){
        $data_arr['label']=$row->EntityName.' ('.$row->OwnerEntityName.')';
        $data_arr['value']=$row->ID;
        array_push($return_arr, $data_arr);
    }
    $this->output->set_header('Content-type: application/json');
    if(count($res)>0) {    
        $this->output->set_output(json_encode($return_arr));    
    }else{    
        $data1=array('-1');
        $this->output->set_output(json_encode($data1));
    }
}
    
public function addBankDetails()
{
    $data=$this->masters_model->addBankDetails();
    echo $data;
}
    
public function getBankingDetail()
{
    //$this->load->library('encrypt');
    $data=$this->masters_model->getBankingDetail();
    //print_r($data); die;
        
    $inhtml='';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $apply_to='';
        if($row->ApplyTo==1) {
            $apply_to='All offices';
        } else if($row->ApplyTo==2) {
            $apply_to='Individual Office';
        }
        if($row->DetailsAppliesTo==1) {
            $DetailsAppliesTo='Owner Only';
        } else if($row->DetailsAppliesTo==2) {
            $DetailsAppliesTo='Invitee Only';
        } else if($row->DetailsAppliesTo==3) {
                $DetailsAppliesTo='Owner and Invitee Both';
        }
        if($row->ActiveFlag==1) {
            $ActiveFlag='Active';
        } else if($row->ActiveFlag==2) {
            $ActiveFlag='Inactive';
        }
            
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.date('d-m-Y H:i:s', strtotime($row->CreationDate)).'","'.$row->EntityName.'","'.$apply_to.'","'.$row->AccountName.'","'.$row->BankName.'","'.$row->cdesc.'","'.$row->sdesc.'","'.$DetailsAppliesTo.'","'.$ActiveFlag.'"],';
            
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;    
}
    
public function getBankingDetailById()
{
    $data['bankingDetail']=$this->masters_model->getBankingDetailById();
    $OfficeEntityID=$data['bankingDetail']->OfficeEntityID;
    $OfficeEntityID=explode(',', $OfficeEntityID);
    $data['OfficeEntity']=$this->masters_model->getOfficeEntityById($OfficeEntityID);
    echo json_encode($data);
}
    
public function updateBankDetails()
{
    $flg=$this->masters_model->updateBankDetails();
    echo $flg;
}
    
public function deleteBankingDetail()
{
    $data=$this->masters_model->deleteBankingDetail();
    echo $data;
}
    
public function checkLastClause()
{
    $data=$this->masters_model->checkLastClause();
    echo $data;
}
    
    //----------save email-----------------------    
public function saveEmailsByResponseId()
{
    $document_type=$this->input->post('document_type');
    $TID=$this->input->post('TID');
    $UserID=$this->input->post('UserID');
    $EntityID=$this->input->post('EntityID');
    $data=$this->masters_model->getUserEmailById();
    $FromEmail=$data->Email;
    $tm=$this->masters_model->getTidMidByAuctionID();
    $tid_mid='MasterID: '.$tm->AuctionID.' || Invitee: '.$tm->EntityName.' || TID: '.$tm->ResponseID.' || Quote($/mt): '.$tm->FreightRate;
    $id=$this->input->post('ids');
    $trimids=trim($id, ",");
    $ids=explode(",", $trimids);
    $AllEmails=array();
    if (in_array(1, $ids)) {
        $ro_email=$this->masters_model->getRecordOwnerEmail();
        $AllEmails['email'][]=$ro_email->Email;
        $AllEmails['emailid'][]=$ro_email->ID;
        $this->saveEmailFnCp($FromEmail, $ro_email->Email, $ro_email->ID, $tid_mid, $document_type, $TID, $UserID, $EntityID);
    }
        
    if (in_array(2, $ids)) {
        $ship_owner_email=$this->masters_model->getShipOwnerEmail();
        foreach($ship_owner_email as $row) {
            $AllEmails['email'][]=$row->Email;
            $AllEmails['emailid'][]=$row->ID;
            $this->saveEmailFnCp($FromEmail, $row->Email, $row->ID, $tid_mid, $document_type, $TID, $UserID, $EntityID);
        }
            
    }
        
    if (in_array(3, $ids)) {
        $ship_broker_email=$this->masters_model->getShipBrokerEmail();
        if($ship_broker_email !=0) {
            
            foreach($ship_broker_email as $row1) {
                $AllEmails['email'][]=$row1->Email;
                $AllEmails['emailid'][]=$row1->ID;
                $this->saveEmailFnCp($FromEmail, $row1->Email, $row1->ID, $tid_mid, $document_type, $TID, $UserID, $EntityID);
            }
        }
            
    }
        
}
    
public function saveEmailFnCp($FromEmail,$ToEmail,$ToEmailID,$mid_tid,$document_type,$tid,$Comment,$UserID,$EntityID)
{
        
    $val=$this->input->post('document_type'); 
    $data='';
    $EditableFlag=1;
    $url='';
    if($val==1) {
        $data=$this->masters_model->getFixtureDocumentByTid();
    } else if($val==2) {
        $data2=$this->masters_model->getChaterPartyDocumentByTid();
        if($data2[1]==1) {
            $nar=explode("?", $data2[0]);
            $data1=current($nar);
            $data='<a href="'.$data1.'"><img src="http://higroove.com/pdf.jpg"></img></a>';
            $EditableFlag=0;
            $url=$data1;
        } else {
            $data=$data2[0];
        }
            
    }
        
    $r=$this->masters_model->saveEmailFnCp($data, $EditableFlag, $url, $FromEmail, $ToEmail, $ToEmailID, $mid_tid, $document_type, $tid, $UserID, $EntityID);
    //echo $r;
}
    
    
    
    
    
public function getUnseenMessageByRecordOwner()
{
    $this->load->model('cargo_quote_model', '', true);
    $data=$this->masters_model->getUnseenMessageByRecordOwner();
    $UserID=$this->input->post('UserID');
    $cnt_arr=array();
    foreach($data as $row) {
        $cnt=$this->cargo_quote_model->count_unseen_message($row->ResponseID, $UserID);
        if($cnt>0) {
            $cnt_arr[$row->ResponseID]=$cnt;
        } else {
            $cnt_arr[$row->ResponseID]='';
        }
    }
    echo json_encode($cnt_arr);
}
    
    //-------------------for general chat------------------------------
public function getChatUsers()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $data=$this->masters_model->getUserPreferences($EntityID);
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        if($UserID==$row->UserID) {
            continue;
        }
        $validity='';
        if($row->Validity==1) {
            $validity='Date range';
        } else if($row->Validity==2) {
            $validity='infinite';
        }
        $date_for_comencement=date('d-m-Y', strtotime($row->date_for_comencement));
        $validity_from=date('d-m-Y', strtotime($row->validity_from));
        $validity_to=date('d-m-Y', strtotime($row->validity_to));
        if($date_for_comencement=='01-01-1970') {
            $date_for_comencement='-';
        }
        if($validity_from=='01-01-1970') {
            $validity_from='-';
        }
        if($validity_to=='01-01-1970') {
            $validity_to='-';
        }
        $msg_count=$this->masters_model->getUserMessageCount($UserID, $row->UserID);
        $to_name=str_replace(" ", "_", $row->FirstName.' '.$row->LastName);
        $to_entity=str_replace(" ", "_", $row->EntityName);
        $btn="<span class='btn btn-default' onclick=make_chat_dialog_box(".$row->UserID.",'".$to_name."','".$to_entity."')>Start Chat</span>";
        if($msg_count > 0) {
            $btn .="<span class='label label-success' style='margin: 7px;' id='".$row->UserID."'>".$msg_count."</span>";
        } else {
            $btn .="<span class='label label-success' style='margin: 7px;' id='".$row->UserID."'></span>";    
        }
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'","'.$row->RecordOwner.'","'.$row->FirstName.' '.$row->LastName.'","'.$row->EntityName.'","'.$row->Email.'","'.$row->Telephone1.'","'.$validity.'","'.$validity_from.'","'.$validity_to.'","'.$date_for_comencement.'","'.$btn.'"],';
        $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getGeneralChatUserData()
{
    $id=$this->input->post('id');
    $data=$this->masters_model->getGeneralChatUserData($id);
    $html='';
    foreach($data as $row) {
        $firstname=trim($row->FirstName, " ");
        $firstname = str_replace(' ', '&nbsp;', $firstname);
        $lastname=trim($row->LastName, " ");
        $lastname = str_replace(' ', '&nbsp;', $lastname);
        $html .='<tr style="text-align: left;">
				<td ><input  type="checkbox" class="chkNumber" name="userid[]" value="'.$row->ID.'" /></td>
				<td>'.$firstname.' '.$lastname.'</td>
				<td>'.$row->Email.'</td>
				<td>'.$row->Telephone1.'</td>
				<td>'.$row->City.'</td>
				</tr>';
    }
    echo $html;
}
    
public function saveUserPreference()
{
    $data=$this->masters_model->saveUserPreference();
    echo $data;
}
    
public function getUserPreferences()
{
    $EntityID=$this->input->get('EntityID');
    $data=$this->masters_model->getUserPreferences($EntityID);
        
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        $validity='';
        if($row->Validity==1) {
            $validity='Date range';
        } else if($row->Validity==2) {
            $validity='infinite';
        }
        $date_for_comencement=date('d-m-Y', strtotime($row->date_for_comencement));
        $validity_from=date('d-m-Y', strtotime($row->validity_from));
        $validity_to=date('d-m-Y', strtotime($row->validity_to));
        if($date_for_comencement=='01-01-1970') {
            $date_for_comencement='-';
        }
        if($validity_from=='01-01-1970') {
            $validity_from='-';
        }
        if($validity_to=='01-01-1970') {
            $validity_to='-';
        }
        $action="<a href='javascript: void(0);' onclick=delete_user_preference(".$row->ID.") title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
        
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->CreatedDateTime)).'","'.$row->RecordOwner.'","'.$row->FirstName.' '.$row->LastName.'","'.$row->EntityName.'","'.$row->Email.'","'.$row->Telephone1.'","'.$validity.'","'.$date_for_comencement.'","'.$validity_from.'","'.$validity_to.'","'.$action.'"],';
        $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function deleteUserPreference()
{
    $ID=$this->input->post('ID');
    $this->masters_model->deleteUserPreference($ID);
}
    
public function getGeneralChatMessage()
{
    $data=$this->masters_model->getGeneralChatMessage();
    echo json_encode($data);
}
    
public function saveGeneralChatMessage()
{
    $data=$this->masters_model->saveGeneralChatMessage();
    echo $data;
}
    
public function getUnseenGeneralMessageByRecordOwner()
{
    $data=$this->masters_model->getUnseenGeneralMessageByRecordOwner();
        
    $UserID=$this->input->post('UserID');
    $cnt_arr=array();
    foreach($data as $row) {
        $cnt=$this->masters_model->getUserMessageCount($UserID, $row->UserID);
        if($cnt>0) {
            $cnt_arr[$row->UserID]=$cnt;
        } else {
            $cnt_arr[$row->UserID]='';
        }
    }
    echo json_encode($cnt_arr); 
}
    
public function userLoginMobile()
{
    $data['record']=$this->masters_model->userLoginMobile();
    echo json_encode($data);
}
    
public function deleteLastMessage()
{
    $data=$this->masters_model->deleteLastMessage();
    echo $data;
}
    
    //-------------------for mobile general chat------------------------------
public function getChatUsersMobile()
{
    $UserID=$this->input->get('UserID');
    $EntityID=$this->input->get('EntityID');
    $data=$this->masters_model->getUserPreferences($EntityID);
    $html='';
    $i=1;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    foreach($data as $row) {
        if($UserID==$row->UserID) {
            continue;
        }
        
        $msg_count=$this->masters_model->getUserMessageCount($UserID, $row->UserID);
        $last_msg=$this->masters_model->getGeneralLastMessage($UserID, $row->UserID);
        $img="";
        $img_name=$this->masters_model->getAttachPhotoByUserID($row->UserID);
        if($img_name) {
            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$img_name, 3600);
            $img="<img class='img-sm rounded-circle' src='".$url."'>";
        } else {
            $img="<img class='img-sm rounded-circle' src='img/no_pic.jpg'>";
        }
        if(count($last_msg)>0) {
            if(date('d-m-Y', strtotime($last_msg->Timestamp))==date('d-m-Y')) {
                $ldt='<em style="font-size: 10px; float: right;" id="DTS'.$row->UserID.'">'.date('h:i a', strtotime($last_msg->Timestamp)).'</em>';
            } else {
                $ldt='<em style="font-size: 10px; float: right;" id="DTS'.$row->UserID.'">'.date('d-M-Y', strtotime($last_msg->Timestamp)).'</em>';    
            }
            $lstmst=substr($last_msg->ChatText, 0, 40);
        } else {
            $ldt='';    
            $lstmst='';    
        }
        $to_name=str_replace(" ", "_", $row->FirstName.' '.$row->LastName);
        $to_entity=str_replace(" ", "_", $row->EntityName);
        if($msg_count > 0) {
            $msg_cnt="<span class='label label-success' id='".$row->UserID."'>".$msg_count."</span>";
        } else {
            $msg_cnt="<span class='label label-success' id='".$row->UserID."'></span>";    
        }
        if($last_msg->FromUserID==$UserID && $last_msg->Status==1) {
            $lstmst="<img src='img/double-tick-marks.png' style='width: 15px;'></img>".$lstmst;
        }
        if($last_msg->FromUserID==$UserID && $last_msg->Status==0) {
            $lstmst="<img src='img/double-tick-marks-read.png' style='width: 15px;'></img>".$lstmst;
        }
        $lstmst="<span id='UMSG".$row->UserID."'>".$lstmst."</span>";
        $html .="<tr><td style='width: 20px;'>".$img."</td><td onclick=make_chat_dialog_box(".$row->UserID.",'".$to_name."','".$to_entity."','".$row->EntityID."')><b>".$row->FirstName." ".$row->LastName."</b>".$ldt."<br>".$lstmst.$msg_cnt."</td></tr>";
        $i++; 
    }
    echo $html;
}
    
public function getUnseenGeneralMessageByRecordOwnerMobile()
{
    $data=$this->masters_model->getUnseenGeneralMessageByRecordOwner();
        
    $UserID=$this->input->post('UserID');
    $cnt_arr=array();
    $lstmsg_arr=array();
    $lstdt_arr=array();
    $data_arr=array();
    foreach($data as $row) {
        $cnt=$this->masters_model->getUserMessageCount($UserID, $row->UserID);
        $last_msg=$this->masters_model->getGeneralLastMessage($UserID, $row->UserID);
        if($cnt>0) {
            $cnt_arr[$row->UserID]=$cnt;
        } else {
            $cnt_arr[$row->UserID]='';
        }
        $lstmst="";
        if($last_msg->FromUserID==$UserID && $last_msg->Status==1) {
            $lstmst="<img src='img/double-tick-marks.png' style='width: 15px;'></img>";
        }
        if($last_msg->FromUserID==$UserID && $last_msg->Status==0) {
            $lstmst="<img src='img/double-tick-marks-read.png' style='width: 15px;'></img>";
        }
        if($last_msg->ChatText) {
            $lstmsg_arr[$row->UserID]=$lstmst.substr($last_msg->ChatText, 0, 40);
            if(date('d-m-Y', strtotime($last_msg->Timestamp))==date('d-m-Y')) {
                $lstdt_arr[$row->UserID]=date('h:i a', strtotime($last_msg->Timestamp));
            } else {
                $lstdt_arr[$row->UserID]=date('d-M-Y', strtotime($last_msg->Timestamp));
            }
                
        }
    }
    $data_arr[0]=$cnt_arr;
    $data_arr[1]=$lstmsg_arr;
    $data_arr[2]=$lstdt_arr;
    echo json_encode($data_arr); 
}
    
public function deleteGeneralMessageByUcid()
{
    $UCID=$this->input->post('UCID');
    $this->masters_model->deleteGeneralMessageByUcid($UCID);
}
    
    //-------------------------------- dashboard new-------------------
    
public function getDashboardNewDetails()
{
    $this->load->model('cargo_quote_model', '', true);
    $UserID=$this->input->post('UserID');
    $gnotifications=$this->masters_model->getGenericNotification();
    $entity_notifications=$this->masters_model->getEntityWiseNotification();
    $u_notifications=$this->masters_model->getUserWiseNotification();
        
    $data['notification']=count($gnotifications)+count($entity_notifications)+count($u_notifications);
        
    $messages=$this->masters_model->getTotalUnReadMessages();
    $data['messages']=count($messages);
        
    $chat_data=$this->masters_model->getChatRecordForCount();
    $count=0;
    foreach($chat_data as $row) {
        $msg_cnt=$this->cargo_quote_model->count_unseen_message($row->ResponseID, $UserID);
        if($msg_cnt>0) {
            $count =$count+$msg_cnt;
        }
    }
    $data['system_chat']=$count;
        
    $general_msg_cnt=$this->masters_model->getUserGeneralChatCount();
    $data['general_chat']=$general_msg_cnt;
    echo json_encode($data);
}
	
}

