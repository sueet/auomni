<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
header('Access-Control-Allow-Origin: *');
    
class quote_ctrl extends CI_Controller {
     
function __construct()
{
    parent::__construct();
    ob_start();
    error_reporting(0);
    $this->load->model('EncriptDecrtipt_model', 'EncodeDecode');
    $this->load->model('cargo_quote_model', '', true);
    //sendsms(); // send sms function.
        
} 
    
public function getDocument()
{
    $auctionID=$this->input->get('AuctionId');
    $data['details']=$this->cargo_quote_model->getDocumentForResponseByAction($auctionID);
        
    $this->output->set_output(json_encode($data)); 
}
    
public function getDocumentForAuction()
{
    $auctionID=$this->input->get('AuctionId');
    $data['details']=$this->cargo_quote_model->getDocumentForInvitee($auctionID);
    $this->output->set_output(json_encode($data)); 
}
    
public function get_last_chat_comments()
{
    $data1=$this->cargo_quote_model->get_last_vessel_chat(); 
    $data2=$this->cargo_quote_model->get_last_freight_chat(); 
    $data3=$this->cargo_quote_model->get_last_cargo_chat(); 
    $data4=$this->cargo_quote_model->get_last_term_chat(); 
    $html='';
    $len=strlen($data1->Chat);
    $len1=strlen($data2->Chat);
    $len2=strlen($data3->Chat);
    $len3=strlen($data4->Chat);
    if($data1 || $data2 || $data3 || $data4 ) {
        $html .='<h4><B>Invitee Comments </B></h4>';
        if($data1) {
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 600;">Vessel </label>
					<label class="control-label col-lg-6" style=" font-weight: 600;">&nbsp; </label>
					</div>';
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 100;">'.$data1->Invname.' </label>
					<label class="control-label col-lg-6" style=" font-weight: 100;">'.date('d-m-Y H:i:s', strtotime($data1->Chat_time)).' </label>
					</div>
					<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;">'.$data1->Chat.'</label>
					</div>';
        }
        if($data2) {
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 600;">Freight </label>
					<label class="control-label col-lg-6" style=" font-weight: 600;">&nbsp; </label>
					</div>';
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 100;">'.$data2->Invname.' </label>
					<label class="control-label col-lg-6" style=" font-weight: 100;">'.date('d-m-Y H:i:s', strtotime($data2->Chat_time)).' </label>
					</div>
					<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;">'.$data2->Chat.'</label>
					</div>';
                
        }
        if($data3) {
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 600;">Cargo & Ports </label>
					<label class="control-label col-lg-6" style=" font-weight: 600;">&nbsp; </label>
					</div>';
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 100;">'.$data3->Invname.' </label>
					<label class="control-label col-lg-6" style=" font-weight: 100;">'.date('d-m-Y H:i:s', strtotime($data3->Chat_time)).' </label>
					</div>
					<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;">'.$data3->Chat.'</label>
					</div>';
                
        }
        if($data4) {
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 600;">Terms </label>
					<label class="control-label col-lg-6" style=" font-weight: 600;">&nbsp; </label>
					</div>';
            $html .='<div style="margin-top: 5px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-6" style="text-align: left; font-weight: 100;">'.$data4->Invname.' </label>
					<label class="control-label col-lg-6" style=" font-weight: 100;">'.date('d-m-Y H:i:s', strtotime($data4->Chat_time)).' </label>
					</div>
					<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;">'.$data4->Chat.'</label>
					</div>';
                
        }
            $html .='<br/><hr style="background-color: black; height:2px;" ><br/>';
    }
    echo $html;
}
    
    
    
public function get_vessel_html_details1()
{
    $this->load->model('cargo_model', '', true); 
    $data=$this->cargo_quote_model->get_vessel_html_details1();
    $type='vessel';
    $data1=$this->cargo_model->get_cargo_document_details($type);
    $html='';
    if($data) {
        if($data->VesselName) {
            $html .='<h4><B>Vessel </B></h4>
				<div class="form-group">
				<label class="control-label col-lg-5">Vessel Version : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->VesselVersion.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Vessel name : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->VesselName.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">IMO number : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->IMO.'</label>
				</div>';
                
            if($data->VesselCurrentName ) {    
                $html .='<div class="form-group">
					<label class="control-label col-lg-5">Vessel current name, if different : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->VesselCurrentName.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Vessel change name date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->VesselChangeNameDate)).'</label>
				</div>';
            }
            $html .='<div class="form-group">
				<label class="control-label col-lg-5">Expected first loadport arrival date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->FirstLoadPortDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Expected first disport arrival date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->LastDisPortDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Length overall(LOA)(m) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->LOA.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Beam(m) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.(int)$data->Beam.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Draft(mt) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->Draft.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Deadweight(mt) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.number_format($data->DeadWeight).'</label>
				</div>';
            if((int)$data->Displacement) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Lightweight displacement(mt) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.(int)$data->Displacement.'</label>
				</div>';
            }
            $html .='<div class="form-group">
				<label class="control-label col-lg-5">Vetting risk source : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->Source.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Vetting risk rating : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->Rating.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Vetting rating date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->RatingDate)).'</label>
				</div>';
            if($data->Source !='Rightship') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">source inhouse or third party : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->SourceType.'</label>
				</div>';
                if($data->SourceType =='Third party') {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Source of vetting is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data->VettingSource.'</label>
					</div>';
                }
            }
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">PSC deficiency : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->Deficiency.'</label>
				</div>';
            if($data->Deficiency == 'Outstanding' ) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">PSC deficiency completion date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->DeficiencyCompDate)).'</label>
				</div>';
            }
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">PSC detention in last 12 months : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->DetentionFlag.'</label>
				</div>';
            if($data->DetentionFlag == 'Yes') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">PSC detention date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">PSC detention lifted : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->DetentionLiftedFlag.'</label>
				</div>';
                if($data->DetentionLiftedFlag == 'Yes' ) {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-5">PSC detention lifted on : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionLiftedDate)).'</label>
				</div>';
                }
                if($data->DetentionLiftedFlag == 'No' ) {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-5">Expected PSC detention lifted on : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionLiftExpectedDate)).'</label>
				</div>';
                }
            }
                
            if($data->CommentInvitee !='null' && $data->CommentInvitee !='' ) {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$data->CommentInvitee.'</label></label>
					</div>';
            }
            if($data->CommentByInvitee !='null' && $data->CommentByInvitee !='') {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments by Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$data->CommentByInvitee.'</label></label>
					</div>';
            }
            if($data1[0]->DocumentType) {
                foreach($data1 as $doc){
                    if($doc->ToDisplayInvitee==0) {
                        continue;
                    }
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Document Title : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$doc->DocumentTitle.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">File name : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
						</div>';
                }
            }
            $html .='<br/><hr style="background-color: black; height:2px;" ><br/>';            
        }
    }
    echo $html;
}
    
public function view_invitee_document_file()
{
    $filename=$this->cargo_quote_model->get_invitee_document_filename();
        
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
            $clause_text=$this->cargo_quote_model->getClausesTextByID($row->ClauseID);
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
    
    
public function download_invitee_document()
{ 
    $this->load->helper('download');
    $filename=$this->cargo_quote_model->download_invitee_document();
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
    $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
    $data = file_get_contents($url); // Read the file's contents 
    force_download($filename, $data);
}
    
public function getResponse()
{
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $RecordOwner=$this->input->get('RecordOwner');
    $UserID=$this->input->get('UserID');
    $LoginEntityID=$this->input->get('LoginEntityID');
    $data=$this->cargo_quote_model->getResponse();
        
    $html='';
    $inhtml='';
    $tempMasterID='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        $VesselName='';
        $VesselName=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
        if($vesselAutocomplete) {
            if($vesselAutocomplete != $VesselName->VesselName) {
                continue;
            }
        }
        if($InviteeEntity) {
            if($InviteeEntity != $row->EntityID) {
                  continue;
            }
        }
            
        if($row->ResponseStatus=='Inprogress') {
            $ResponseStatus='In Progress';
        } else {
            $ResponseStatus=$row->ResponseStatus;
        }
            $flag=$this->cargo_quote_model->checkChat($row->ResponseID);
            $FreightRate='';
            $FreightRecords=$this->cargo_quote_model->getLatestFreightQuotes($row->ResponseID);
            $QUOTE='';
            $cnt=1;
        foreach($FreightRecords as $fr){
                $QUOTE .="<a href='javascript: void(0);' onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."','".$fr->LineNum."') title='view quote details'>QT$cnt</a>&nbsp;";
            if($fr->FreightRate) {
                $FreightRate =$FreightRate+$fr->FreightRate;
            }
                $cnt++;
        }
            
            
        if($flag) {
                $NewChatsResult=$this->cargo_quote_model->getNewChatsFlagByResponseID1($row->ResponseID);
                $new_flg=0;
            foreach($NewChatsResult as $nw){
                if($RecordOwner == $row->OwnerID) {
                    if($nw->VesselOwnerFlag==1 || $nw->FreightOwnerFlag==1 || $nw->CargoPortOwnerFlag==1 || $nw->TermOwnerFlag==1) {
                        $new_flg=1;
                    }
                } else {
                    if($nw->VesselInviteeFlag==1 || $nw->FreightInviteeFlag==1 || $nw->CargoPortInviteeFlag==1 || $nw->TermInviteeFlag==1) {
                        $new_flg=1;
                    }
                }
                    
            }
            if($new_flg==1) {
                if($RecordOwner == $row->OwnerID) {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',1) title='view chat details'>View &nbsp;".$newbadge."</a>";
                } else {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',2) title='view chat details'>View &nbsp;".$newbadge."</a>";
                }
                    
            } else {
                $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',0) title='view chat details'>View</a>";
            }
                
        } else {
                $view="No";
        }
            
            $bp_flg=0;
        if($LoginEntityID==$row->EntityID) {
            $InviteeRecord=$this->cargo_quote_model->getAuctionInviteePrimeRole($row->AuctionID, $row->EntityID);
            $QuoteBP=$this->cargo_quote_model->getQuoteInviteeBusinessProcess($row->ResponseID);
            if($InviteeRecord->InviteeRole==6 && count($QuoteBP) > 0) {
                $bp_flg=1;
            }
        }
            
            
        if($row->ResponseStatus == 'Closed') {
            $a=$row->ResponseID;
        } else if($RecordOwner == $row->OwnerID) {
            $a="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
        } else {
            $InvUsers=explode(",", $row->InvUserID);
            if(in_array($UserID, $InvUsers)) {
                $a="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
            } else {
                $a=$row->ResponseID;
            }
        }
            
            $CHTR="<a href='javascript: void(0);' onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR TERMS</a>";
            
            
        if($tempMasterID != $row->AuctionID) {
            if($RecordOwner == $row->OwnerID) {
                if($row->ResponseStatus == 'Closed') {
                    $MasterID=$row->AuctionID.' ('.$row->OwnerEntityName.')';    
                } else {
                    $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."' >".$row->AuctionID."</a> (".$row->OwnerEntityName.")";
                }
            } else {
                $MasterID=$row->AuctionID.' ('.$row->OwnerEntityName.')';
            }
            $tempMasterID = $row->AuctionID;
        } else {
            $MasterID='';
        }
            
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ReleaseDate)).'","'.$ResponseStatus.'","'.$MasterID.'","'.$a.'","'.$row->EntityName.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'", "'.$FreightRate.'", "'.$VesselName->VesselName.'", "'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'", "'.$view.'","'.$CHTR.'","'.$QUOTE.'"],';
        $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getResponseCargoByID()
{
    $this->load->model('cargo_model', '', true);
    $ResponseCargoID=$this->input->post('id');
    $AuctionID=$this->input->post('auctionId');
    $data['data1']=$this->cargo_quote_model->getResponseCargoDataById();
        
    $data['Disports']=$this->cargo_quote_model->getResponseDisportsByResponseCargoID_H($ResponseCargoID);
    $data['ExceptedPeriod']=$this->cargo_quote_model->getLpExpectedPeriodByResponseCargoID($ResponseCargoID);
    $data['NORTendering']=$this->cargo_quote_model->getLpNORTenderingPreByResponseCargoID($ResponseCargoID);
    $data['NORAcceptance']=$this->cargo_quote_model->getLpNORAcceptancePreByResponseCargoID($ResponseCargoID);
    $data['OfficeHours']=$this->cargo_quote_model->getLpOfficeHoursByResponseCargoID($ResponseCargoID);
    $data['LaytimeCommence']=$this->cargo_quote_model->getLpLaytimeCommenceByResponseCargoID($ResponseCargoID);
        
    $data['PeriodEvents']=$this->cargo_model->getAllExceptedPeriodEvents($AuctionID);
    $data['TenderingPreCond']=$this->cargo_model->getAllNORTenderingPreConditions($AuctionID);
    $data['AcceptancePreCond']=$this->cargo_model->getAllNORAcceptancePreConditions($AuctionID);
        
    $data['data4']=$this->cargo_quote_model->getResponseCargoBACById();
        
    $data['NorTend']=$this->cargo_model->getNorTending();
    $data['FreeTime']=$this->cargo_model->getFreeTime();
    $data['SteveDoringTerms']=$this->cargo_model->getSteveDoringTerms();
        
    $data['EdutableField']=$this->cargo_model->getEditableField();
        
    echo json_encode($data); 
    
}
    
public function getResponseFreightByID()
{
    $this->load->model('cargo_model', '', true);
    $AuctionID=$this->input->post('auctionID');
    $data['data2']=$this->cargo_quote_model->getResponseFreightByID();
    $data['data3']=$this->cargo_quote_model->getResponseFreightInviteeIDByID($data['data2']->ResponseID);
    $data['data1']=$this->cargo_quote_model->getDifferentialDataForResponse($data['data2']->LineNum, $data['data2']->AuctionID);
    $data['def_ref']=$this->cargo_quote_model->getDifferentialReferenceResponse($data['data1'][0]->DifferentialID);
    $data['EdutableField']=$this->cargo_model->getEditableFieldQuote($AuctionID);
    $data['AU_Differential']=$this->cargo_quote_model->getAuctionDifferentialRecord($data['data2']->AuctionID, $data['data2']->LineNum);
    //print_r($data['data1']); die;
    $data['FreightRate']=$this->cargo_quote_model->getCargoUnits();
    echo json_encode($data);

}
    
public function getCharterDetail1()
{ 
    $this->load->model('cargo_model', '', true);
    $data2=$this->cargo_model->getCharterDetail();
    $data3=$this->cargo_model->getReferenceDetail();
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data2->OwnerEntityID);
    $html ='';
    $header_html='';
    if($entity_detail->AttachedLogo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            
        if($entity_detail->AlignLogo==1) {
            $header_html .='<div id="header_content" ><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span>';
            $header_html .='<span style="font-size: 15px; float: right;"><b>'.$entity_detail->EntityName.'</b></span></div>';
        } else if($entity_detail->AlignLogo==2) {
            $header_html .='<div id="header_content" ><center><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span><br/>';
            $header_html .='<span style="font-size: 15px;"><b>'.$entity_detail->EntityName.'</b></span></center></div>';
        } else if($entity_detail->AlignLogo==3) {
            $header_html .='<div id="header_content" style="height: 45px;" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span>';
            $header_html .='<span style="font-size: 15px; float: right;" ><img src="'.$url.'" style="max-width: 50px;" /></span></div>';
        }
    } else {
        $header_html .='<div id="header_content" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span></div>';
    }
    $contracttype='';
    if($data2->ContractType==1) {
        $contracttype='Spot';
    }
    if($data2->ContractType==2) {
        $contracttype='Contract';
    }
    $header_html .='<br/><hr style="background-color:black; height: 2px;"><br/>';
    $html .=$header_html;
    $html .='<h4><B>Charter Details</B></h4>';
    $html .='<div class="form-group">
			<label class="control-label col-lg-4">Contract type : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$contracttype.'</label>
			</div>';
    if($data2->COAReference) {
        $html .='<div class="form-group">
			<label class="control-label col-lg-4">Contract (COA) reference : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data2->COAReference.'</label>
			</div>';
    }
    if($data2->SalesAgreementReference) {
        $html .='<div class="form-group">
			<label class="control-label col-lg-4">Sales agreement reference : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data2->SalesAgreementReference.'</label>
			</div>';
    }
    if($data2->ShipmentReferenceID) {
        $html .='<div class="form-group">
			<label class="control-label col-lg-4">Shipment Reference ID : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data2->ShipmentReferenceID.'</label>
			</div>';
    }
    if($data3) {
        $html .='<div class="form-group">
			<label class="control-label col-lg-4"><b>Other Reference ID </b> </label>
			<label class="control-label col-lg-8" style="text-align: left;">&nbsp;</label>
			</div>';
    }    
    foreach($data3 as $row) {
        $html .='<div class="form-group">
			<label class="control-label col-lg-4">'.$row->SourceName.' :</label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$row->SourceID.'</label>
			</div>';
    }
    //$data=$this->cargo_model->get_cargo_html_details();
    $data=$this->cargo_quote_model->get_response_allcargo_html_details1();
    //print_r($data); die;
    $type='cp';
    //$data1=$this->cargo_model->get_cargo_document_details($type);
    //print_r($data1); die;
    $i=1;
    $templinenum='';
    if($data) {
        foreach($data as $row) {
            $temp='';
            $temp2='';
            $temp3='';
            if($templinenum==$row->LineNum) {
                continue;
            }
            $templinenum=$row->LineNum;
            
            $bacdata=$this->cargo_quote_model->get_response_bac_alldetails($row->ResponseCargoID);
            $bachtml='';
            foreach($bacdata as $bac){
                $TransactionType='';
                $textcontent='';
                if($bac->TransactionType=='Commision') {
                      $TransactionType='AddComm';
                      $textcontent='AddComm';
                }else{
                    $TransactionType=$bac->TransactionType;
                    $textcontent=$bac->TransactionType;
                }
                $bachtml .='<div class="form-group">
				<label class="control-label col-lg-4">Transaction type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$TransactionType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Paying Entity Type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PayingEntityType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Receiving Entity Type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$bac->ReceivingEntityType.'</label>
				</div>';
                if($bac->ReceivingEntityType=='Charterer') {
                     $bachtml .='<div class="form-group">
					<label class="control-label col-lg-4">Receiving Entity Name : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$bac->ReceivingEntityName.'</label>
					</div>';
                }
                $bachtml .='<div class="form-group">
				<label class="control-label col-lg-4">'.$textcontent.' payable : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PayableAs.'</label>
				</div>';
                if($bac->PayableAs=='Percentage') {
                    if($bac->PercentageOnFreight) {
                        $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on freight : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PercentageOnFreight.'</label>
						</div>';
                    }
                    if($bac->PercentageOnDeadFreight) {
                        $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on deadfreight : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PercentageOnDeadFreight.'</label>
						</div>';
                    
                    }
                    if($bac->PercentageOnDemmurage) {
                        $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on demmurage : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PercentageOnDemmurage.'</label>
						</div>';
                    }
                    if($bac->PercentageOnOverage) {
                        $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on overage : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$bac->PercentageOnOverage.'</label>
						</div>';
                    }
                        
                } else if($bac->PayableAs=='LumpSum') {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">Lumpsum amount payable : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.number_format($bac->LumpsumPayable).'</label>
						</div>';
                } else if($bac->RatePerTonnePayable=='RatePerTonne') {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' rate/tonne : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$bac->RatePerTonnePayable.'</label>
						</div>';
                }     
                
            }
            if($row->CargoLimitBasis==1) {
                $CargoLimitBasis='Max and Min';
                $temp='<div class="form-group">
				<label class="control-label col-lg-4">Max cargo is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->MaxCargoMT).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Min cargo is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->MinCargoMT).'</label>
				</div>';
            }else if($row->CargoLimitBasis==2) {
                $CargoLimitBasis='% Tolerance limit';
                $temp='<div class="form-group">
				<label class="control-label col-lg-4">Tolerance limit (%) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$row->ToleranceLimit.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Upper cargo limit is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->UpperLimit).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Lower cargo limit is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->LowerLimit).'</label>
				</div>';
            }
            
            if($row->LoadingRateUOM==1) {
                $LoadingRateUOM='Per hour';
            }else if($row->LoadingRateUOM==2) {
                $LoadingRateUOM='Per weather working day';
            }else if($row->LoadingRateUOM==3) {
                $LoadingRateUOM='Max time limit';
                $temp2='<div class="form-group">
				<label class="control-label col-lg-4">Max time to load cargo (hrs) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.(int)$row->LpMaxTime.'</label>
				</div>';
            }
            
            if($row->LpLaytimeType==1) {
                $LpLaytimeType='Reversible';
            }else if($row->LpLaytimeType==2) {
                $LpLaytimeType='Non Reversible';
            }else if($row->LpLaytimeType==3) {
                $LpLaytimeType='Average';
            }
            
            if($row->LpCalculationBasedOn==108) {
                $LpCalculationBasedOn='Bill of Loading Quantity';
            }else if($row->LpCalculationBasedOn==109) {
                $LpCalculationBasedOn='Outturn or Discharge Quantity';
            }
            
            if($row->LpPriorUseTerms==102) {
                $LpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
            }else if($row->LpPriorUseTerms==10) {
                $LpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
            }else{
                $LpPriorUseTerms='N/A';
            }
            
            if($row->LpLaytimeBasedOn==1) {
                $LpLaytimeBasedOn='ATS || All Time Saved';
            }else if($row->LpLaytimeBasedOn==2) {
                $LpLaytimeBasedOn='WTS || Working Time Saved';
            }else{
                $LpLaytimeBasedOn='N/A';
            }
            
            if($row->LpCharterType==1) {
                $LpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
            }else if($row->LpCharterType==2) {
                $LpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
            }else if($row->LpCharterType==3) {
                $LpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
            }else if($row->LpCharterType==4) {
                $LpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
            }
            
            $html .='<hr style="background-color: black; height: 2px;" ><br/><h4><B>Cargo and port details</B></h4>
			
			<div class="form-group">
			<label class="control-label col-lg-4">Cargo '.$i.' : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$row->Code.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Version : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$row->CargoVersion.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Cargo quantity to load (in MT) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->CargoQtyMT).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Cargo quantity option basis : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$row->CargoLoadedBasis.'</label>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-4">Cargo quantity limit basis : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$CargoLimitBasis.'</label>
				</div>';
                $html .=$temp;
                $html .='<hr style="background-color:black; height: 2px;"><div class="form-group">
					<label class="control-label col-lg-4">Load Port '.$i.' : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->lpPortName.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Load port laycan start date : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpLaycanStartDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Load port laycan finish date : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpLaycanEndDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Loadport  preferred arrival date : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpPreferDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Expected loadport delay : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->ExpectedLpDelayDay.' days '.$row->ExpectedLpDelayHour.' hours</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Loading Terms : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->ldtCode.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Loading rate (mt) : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->LoadingRateMT).'</label>
					</div>';
                $html .='
					<div class="form-group">
					<label class="control-label col-lg-4">Loading rate based on (uom) : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LoadingRateUOM.'</label>
					</div>
				';
                $html .=$temp2;
                $html .='
					<div class="form-group">
					<label class="control-label col-lg-4">Laytime : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LpLaytimeType.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Laytime tonnage calc. based on : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LpCalculationBasedOn.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4"> Turn (free) time (hours) : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->ftCode.' || '.$row->ftDescription.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4"> Prior use terms : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LpPriorUseTerms.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Laytime based on : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LpLaytimeBasedOn.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4"> Type of charter : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$LpCharterType.'</label>
					</div>';
                    $StevedoringTermsLp=$this->cargo_model->getStevedoringTermsByID($row->LpStevedoringTerms);
                    $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Stevedoring terms : </label>
					<label class="control-label col-lg-8" style="text-align: left;"> Code : '.$StevedoringTermsLp->Code.' || Description : '.$StevedoringTermsLp->Description.'</label>
					</div>';
                    $html .='<div class="form-group">
					<label class="control-label col-lg-4"> NOR tender : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->cnrCode.'</label>
					</div>';
                    
                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
            if($row->ExceptedPeriodFlg==1) {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> Excepted periods for events : </label>
						<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
                $ExceptedPeriod=$this->cargo_quote_model->getLpExpectedPeriodByResponseCargoID($row->ResponseCargoID);
                foreach($ExceptedPeriod as $ep){
                            $LaytimeCountsOnDemurrageFlg='-';
                            $LaytimeCountsFlg='-';
                            $TimeCountingFlg='-';
                            
                    if($ep->LaytimeCountsOnDemurrageFlg==1) {
                            $LaytimeCountsOnDemurrageFlg='Yes';
                    } else if($ep->LaytimeCountsOnDemurrageFlg==2) {
                                  $LaytimeCountsOnDemurrageFlg='No';
                    }
                            
                    if($ep->LaytimeCountsFlg==1) {
                            $LaytimeCountsFlg='Yes';
                    } else if($ep->LaytimeCountsFlg==2) {
                        $LaytimeCountsFlg='No';
                    }
                            
                    if($ep->TimeCountingFlg==102) {
                        $TimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                    } else if($ep->TimeCountingFlg==10) {
                        $TimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                    }
                            $html .='<tr>';
                            $html .='<td>'.$ep->ExceptedCode.' || '.$ep->ExceptedDescription.'</td>';
                            $html .='<td>'.$LaytimeCountsOnDemurrageFlg.'</td>';
                            $html .='<td>'.$LaytimeCountsFlg.'</td>';
                            $html .='<td>'.$TimeCountingFlg.'</td>';
                            $html .='</tr>';
                }
                $html .='</table>';
            } else {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> Excepted periods for events : </label>
						<label class="control-label col-lg-8" style="text-align: left;">No</label>
						</div>';
            }
                    
                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
            if($row->NORTenderingPreConditionFlg==1) {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
						<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORTendering=$this->cargo_quote_model->getLpNORTenderingPreByResponseCargoID($row->ResponseCargoID);
                foreach($NORTendering as $tr){
                            $CreateNewOrSelectListFlg='-';
                            $NewNORTenderingPreCondition='-';
                            
                    if($tr->CreateNewOrSelectListFlg==1) {
                                $CreateNewOrSelectListFlg='create new';
                                $NewNORTenderingPreCondition=$tr->NewNORTenderingPreCondition;
                    } else if($tr->CreateNewOrSelectListFlg==2) {
                        $CreateNewOrSelectListFlg='select from pre defined list';
                        $NewNORTenderingPreCondition=$tr->TenderingCode;
                    }
                            $StatusFlag='In Active';
                    if($tr->StatusFlag==1) {
                        $StatusFlag='Active';
                    }
                            
                            $html .='<tr>';
                            $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                            $html .='<td>'.$NewNORTenderingPreCondition.'</td>';
                            $html .='<td>'.$StatusFlag.'</td>';
                            $html .='</tr>';
                }
                $html .='</table>';
            } else {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
						<label class="control-label col-lg-8" style="text-align: left;">No</label>
						</div>';
            }
                    
                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
            if($row->NORAcceptancePreConditionFlg==1) {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
						<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORAcceptance=$this->cargo_quote_model->getLpNORAcceptancePreByResponseCargoID($row->ResponseCargoID);
                foreach($NORAcceptance as $ar){
                            $CreateNewOrSelectListFlg='-';
                            $NewNORAcceptancePreCondition='-';
                            
                    if($ar->CreateNewOrSelectListFlg==1) {
                                $CreateNewOrSelectListFlg='create new';
                                $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                    } else if($ar->CreateNewOrSelectListFlg==2) {
                        $CreateNewOrSelectListFlg='select from pre defined list';
                        $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                    }
                            $StatusFlag='In Active';
                    if($ar->StatusFlag==1) {
                        $StatusFlag='Active';
                    }
                            
                            $html .='<tr>';
                            $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                            $html .='<td>'.$NewNORAcceptancePreCondition.'</td>';
                            $html .='<td>'.$StatusFlag.'</td>';
                            $html .='</tr>';
                }
                $html .='</table>';
            } else {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
						<label class="control-label col-lg-8" style="text-align: left;">No</label>
						</div>';
            }
            if($row->LpNorTendering==3) {
                $html .='<br/><hr style="background-color:black;     height: 1px;" >';
                if($row->OfficeHoursFlg==1) {
                            $html .='<div class="form-group">
							<label class="control-label col-lg-4"> Enter Office hours : </label>
							<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
							</div>';
                            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
                            $OfficeHours=$this->cargo_quote_model->getLpOfficeHoursByResponseCargoID($row->ResponseCargoID);
                    foreach($OfficeHours as $or){
                                $IsLastEntry='No';
                        if($or->IsLastEntry==1) {
                            $IsLastEntry='Yes';
                        }
                                   $html .='<tr>';
                                   $html .='<td>'.$or->DateFrom.'</td>';
                                   $html .='<td>'.$or->DateTo.'</td>';
                                   $html .='<td>'.$or->TimeFrom.'</td>';
                                   $html .='<td>'.$or->TimeTo.'</td>';
                                   $html .='<td>'.$IsLastEntry.'</td>';
                                   $html .='</tr>';
                    }
                            $html .='</table>';
                            $html .='<br/><hr style="background-color:black;     height: 1px;" >';
                    if($row->LaytimeCommencementFlg==1) {
                        $html .='<div class="form-group">
								<label class="control-label col-lg-4"> Enter laytime commencement : </label>
								<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
								</div>';
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
                        $LaytimeCommencement=$this->cargo_quote_model->getLpLaytimeCommenceByResponseCargoID($row->ResponseCargoID);
                        foreach($LaytimeCommencement as $lr){
                                $TurnTimeExpire='-';
                                $LaytimeCommenceAt='-';
                                $LaytimeCommenceAtHour='-';
                                $SelectDay='-';
                                $TimeCountsIfOnDemurrage='-';
                                    
                            if($lr->TurnTimeExpire==1) {
                                     $TurnTimeExpire='During office hours';
                                if($lr->LaytimeCommenceAt==1) {
                                    $LaytimeCommenceAt='At expiry of turn time';
                                } else if($lr->LaytimeCommenceAt==2) {
                                    $LaytimeCommenceAt='At specified hour';
                                    $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                                            
                                    if($lr->SelectDay==1) {
                                        $SelectDay='Same Day';
                                    } else if($lr->SelectDay==2) {
                                        $SelectDay='New Working Day';
                                    }
                                            
                                    if($lr->TimeCountsIfOnDemurrage==1) {
                                          $TimeCountsIfOnDemurrage='Yes';
                                    }else if($lr->TimeCountsIfOnDemurrage==2) {
                                        $TimeCountsIfOnDemurrage='No';
                                    }
                                }
                            } else {
                                $TurnTimeExpire='After office hours';
                                $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                                        
                                if($lr->SelectDay==1) {
                                        $SelectDay='Same Day';
                                } else if($lr->SelectDay==2) {
                                    $SelectDay='New Working Day';
                                }
                                if($lr->TimeCountsIfOnDemurrage==1) {
                                    $TimeCountsIfOnDemurrage='Yes';
                                }else if($lr->TimeCountsIfOnDemurrage==2) {
                                    $TimeCountsIfOnDemurrage='No';
                                }
                            }
                                $html .='<tr>';
                                $html .='<td>'.$lr->DayFrom.'</td>';
                                $html .='<td>'.$lr->DayTo.'</td>';
                                $html .='<td>'.$lr->TimeFrom.'</td>';
                                $html .='<td>'.$lr->TimeTo.'</td>';
                                $html .='<td>'.$lr->LaytimeCode.'</td>';
                                $html .='<td>'.$TurnTimeExpire.'</td>';
                                $html .='<td>'.$LaytimeCommenceAt.'</td>';
                                $html .='<td>'.$LaytimeCommenceAtHour.'</td>';
                                $html .='<td>'.$SelectDay.'</td>';
                                $html .='<td>'.$TimeCountsIfOnDemurrage.'</td>';
                                $html .='</tr>';
                        }
                               $html .='</table>';
                    } else {
                            $html .='<div class="form-group">
								<label class="control-label col-lg-4"> Enter laytime commencement : </label>
								<label class="control-label col-lg-8" style="text-align: left;">No</label>
								</div>';
                    }
                } else {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4"> Enter Office hours : </label>
							<label class="control-label col-lg-8" style="text-align: left;">No</label>
							</div>';
                }
            }
                    
                    $disportData=$this->cargo_quote_model->getResponseDisportDetails($row->ResponseCargoID);
                    
                    $j=1;
            foreach($disportData as $dis){
                $temp3='';
                $html .='<hr style="background-color:black; height: 2px;"><div class="form-group">
						<label class="control-label col-lg-4"> Disport '.$j.' : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$dis->dpPortName.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Disport arrival start date : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalStartDate)).'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Disport arrival finish date  : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalEndDate)).'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Disport  arrival preferred date : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpPreferDate)).'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Expected disport delay : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$dis->ExpectedDpDelayDay.' days '.$dis->ExpectedDpDelayHour.' hours</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Discharging Terms : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$dis->ddtCode.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Discharing rate (mt)  : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.number_format($dis->DischargingRateMT).'</label>
						</div>';
                if($dis->DischargingRateUOM==1) {
                            $DischargingRateUOM='Per hour';
                }else if($dis->DischargingRateUOM==2) {
                    $DischargingRateUOM='Per weather working day';
                }else if($dis->DischargingRateUOM==3) {
                    $DischargingRateUOM='Max time limit';
                    $temp3='<div class="form-group">
							<label class="control-label col-lg-4">Max time to discharge (hrs) : </label>
							<label class="control-label col-lg-8" style="text-align: left;">'.(int)$dis->DpMaxTime.'</label>
							</div>';
                }
                      $html .='<div class="form-group">
						<label class="control-label col-lg-4">Discharging rate based on (uom)  : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DischargingRateUOM.'</label>
						</div>';
                      $html .=$temp3;
                                    
                if($dis->DpLaytimeType==1) {
                    $DpLaytimeType='Reversible';
                }else if($dis->DpLaytimeType==2) {
                    $DpLaytimeType='Non Reversible';
                }else if($dis->DpLaytimeType==3) {
                       $DpLaytimeType='Average';
                }
                                    
                if($dis->DpCalculationBasedOn==108) {
                    $DpCalculationBasedOn='Bill of Loading Quantity';
                }else if($dis->DpCalculationBasedOn==109) {
                    $DpCalculationBasedOn='Outturn or Discharge Quantity';
                }
                        
                                    $html .='<div class="form-group">
						<label class="control-label col-lg-4"> Laytime type : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DpLaytimeType.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Laytime tonnage calc. based on : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DpCalculationBasedOn.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4"> Turn (free) time (hours) : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$dis->dftCode.' || '.$dis->dftDescription.'</label>
						</div>';
                                    
                if($dis->DpPriorUseTerms==102) {
                    $DpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
                }else if($dis->DpPriorUseTerms==10) {
                    $DpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
                }else{
                    $DpPriorUseTerms='N/A';
                }
                                    
                if($dis->DpLaytimeBasedOn==1) {
                    $DpLaytimeBasedOn='ATS || All Time Saved';
                }else if($dis->DpLaytimeBasedOn==2) {
                    $DpLaytimeBasedOn='WTS || Working Time Saved';
                }else{
                    $DpLaytimeBasedOn='N/A';
                }
                                    
                if($dis->DpCharterType==1) {
                    $DpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                }else if($dis->DpCharterType==2) {
                    $DpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
                }else if($dis->DpCharterType==3) {
                    $DpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
                }else if($dis->DpCharterType==4) {
                    $DpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                        
                                    $html .='<div class="form-group">
						<label class="control-label col-lg-4"> Prior use terms : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DpPriorUseTerms.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Laytime based on : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DpLaytimeBasedOn.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-4">Type of charter : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$DpCharterType.'</label>
						</div>';
                                    $StevedoringTermsDp=$this->cargo_model->getStevedoringTermsByID($dis->DpStevedoringTerms);
                                    $html .='<div class="form-group">
						<label class="control-label col-lg-4"> Stevedoring terms : </label>
						<label class="control-label col-lg-8" style="text-align: left;"> Code : '.$StevedoringTermsDp->Code.' || Description : '.$StevedoringTermsDp->Description.'</label>
						</div>';
                                    $html .='<div class="form-group">
						<label class="control-label col-lg-4">NOR tender : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$dis->cnrDCode.'</label>
						</div>';
                        
                                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
                if($dis->DpExceptedPeriodFlg==1) {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">Excepted periods for events : </label>
							<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
							</div>';
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
                    $exceptedData=$this->cargo_quote_model->getDpExceptedPeriodByResponseDisportID($dis->RCD_ID);
                    foreach($exceptedData as $ep){
                        $LaytimeCountsOnDemurrageFlg='-';
                        $LaytimeCountsFlg='-';
                        $TimeCountingFlg='-';
                                
                        if($ep->LaytimeCountsOnDemurrageFlg==1) {
                                                    $LaytimeCountsOnDemurrageFlg='Yes';
                        } else if($ep->LaytimeCountsOnDemurrageFlg==2) {
                                              $LaytimeCountsOnDemurrageFlg='No';
                        }
                                
                        if($ep->LaytimeCountsFlg==1) {
                                        $LaytimeCountsFlg='Yes';
                        } else if($ep->LaytimeCountsFlg==2) {
                                  $LaytimeCountsFlg='No';
                        }
                                
                        if($ep->TimeCountingFlg==102) {
                            $TimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                        } else if($ep->TimeCountingFlg==10) {
                                      $TimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                        }
                        $html .='<tr>';
                        $html .='<td>'.$ep->ExceptedCode.' || '.$ep->ExceptedDescription.'</td>';
                        $html .='<td>'.$LaytimeCountsOnDemurrageFlg.'</td>';
                        $html .='<td>'.$LaytimeCountsFlg.'</td>';
                        $html .='<td>'.$TimeCountingFlg.'</td>';
                        $html .='</tr>';
                    }
                                        $html .='</table>';
                } else {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">Excepted periods for events : </label>
							<label class="control-label col-lg-8" style="text-align: left;">No</label>
							</div>';
                }
                        
                                    $html .='<br/><hr style="background-color:black; height: 1px;" >';
                if($dis->DpNORTenderingPreConditionFlg==1) {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">NOR tendering pre conditions apply : </label>
							<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
							</div>';
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                    $NORTenderingData=$this->cargo_quote_model->getDpTenderingPreConditionsByResponseDisportID($dis->RCD_ID);
                    foreach($NORTenderingData as $tr){
                        $CreateNewOrSelectListFlg='-';
                        $NewNORTenderingPreCondition='-';
                        if($tr->CreateNewOrSelectListFlg==1) {
                                        $CreateNewOrSelectListFlg='create new';
                                        $NewNORTenderingPreCondition=$tr->NewNORTenderingPreCondition;
                        } else if($tr->CreateNewOrSelectListFlg==2) {
                                  $CreateNewOrSelectListFlg='select from pre defined list';
                                  $NewNORTenderingPreCondition=$tr->TenderingCode;
                        }
                        $StatusFlag='In Active';
                        if($tr->StatusFlag==1) {
                            $StatusFlag='Active';
                        }
                        $html .='<tr>';
                        $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                        $html .='<td>'.$NewNORTenderingPreCondition.'</td>';
                        $html .='<td>'.$StatusFlag.'</td>';
                        $html .='</tr>';
                    }
                                        $html .='</table>';
                } else {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">NOR tendering pre conditions apply : </label>
							<label class="control-label col-lg-8" style="text-align: left;">No</label>
							</div>';
                }
                        
                                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
                if($dis->DpNORAcceptancePreConditionFlg==1) {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">NOR acceptance pre condition apply : </label>
							<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
							</div>';
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                    $NORAcceptanceData=$this->cargo_quote_model->getDpAcceptancePreConditionByResponseDisportID($dis->RCD_ID);
                    foreach($NORAcceptanceData as $ar){
                        $CreateNewOrSelectListFlg='-';
                        $NewNORAcceptancePreCondition='-';
                        if($ar->CreateNewOrSelectListFlg==1) {
                            $CreateNewOrSelectListFlg='create new';
                            $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                        } else if($ar->CreateNewOrSelectListFlg==2) {
                                      $CreateNewOrSelectListFlg='select from pre defined list';
                                      $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                        }
                        $StatusFlag='In Active';
                        if($ar->StatusFlag==1) {
                            $StatusFlag='Active';
                        }
                                
                        $html .='<tr>';
                        $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                        $html .='<td>'.$NewNORAcceptancePreCondition.'</td>';
                        $html .='<td>'.$StatusFlag.'</td>';
                        $html .='</tr>';
                    }
                                        $html .='</table>';
                } else {
                    $html .='<div class="form-group">
							<label class="control-label col-lg-4">NOR acceptance pre condition apply : </label>
							<label class="control-label col-lg-8" style="text-align: left;">No</label>
							</div>';
                }
                        
                if($dis->DpNorTendering==3) {
                            
                    $html .='<br/><hr style="background-color:black;     height: 1px;" >';
                    if($dis->DpOfficeHoursFlg==1) {
                        $html .='<div class="form-group">
								<label class="control-label col-lg-4">Enter Office hours : </label>
								<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
								</div>';
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
                        $OfficeHoursData=$this->cargo_quote_model->getDpOfficeHoursByResponseDisportID($dis->RCD_ID);
                        foreach($OfficeHoursData as $or){
                                    $IsLastEntry='No';
                            if($or->IsLastEntry==1) {
                                $IsLastEntry='Yes';
                            }
                                    $html .='<tr>';
                                    $html .='<td>'.$or->DateFrom.'</td>';
                                    $html .='<td>'.$or->DateTo.'</td>';
                                    $html .='<td>'.$or->TimeFrom.'</td>';
                                    $html .='<td>'.$or->TimeTo.'</td>';
                                    $html .='<td>'.$IsLastEntry.'</td>';
                                    $html .='</tr>';
                        }
                        $html .='</table>';
                                
                        $html .='<br/><hr style="background-color:black; height: 1px;" >';
                        if($dis->DpLaytimeCommencementFlg==1) {
                                          $html .='<div class="form-group">
									<label class="control-label col-lg-4">Enter laytime commencement : </label>
									<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
									</div>';
                                          $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
                                          $LaytimeCommencementData=$this->cargo_quote_model->getDpLaytimeCommencementByResponseDisportID($dis->RCD_ID);
                            foreach($LaytimeCommencementData as $lr){
                                $TurnTimeExpire='-';
                                $LaytimeCommenceAt='-';
                                $LaytimeCommenceAtHour='-';
                                $SelectDay='-';
                                $TimeCountsIfOnDemurrage='-';
                                if($lr->TurnTimeExpire==1) {
                                    $TurnTimeExpire='During office hours';
                                    if($lr->LaytimeCommenceAt==1) {
                                        $LaytimeCommenceAt='At expiry of turn time';
                                    } else if($lr->LaytimeCommenceAt==2) {
                                        $LaytimeCommenceAt='At specified hour';
                                        $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                                                
                                        if($lr->SelectDay==1) {
                                                                $SelectDay='Same Day';
                                        } else if($lr->SelectDay==2) {
                                                                     $SelectDay='New Working Day';
                                        }
                                        if($lr->TimeCountsIfOnDemurrage==1) {
                                            $TimeCountsIfOnDemurrage='Yes';
                                        }else if($lr->TimeCountsIfOnDemurrage==2) {
                                            $TimeCountsIfOnDemurrage='No';
                                        }
                                    }
                                } else {
                                                         $TurnTimeExpire='After office hours';
                                                         $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                                            
                                    if($lr->SelectDay==1) {
                                        $SelectDay='Same Day';
                                    } else if($lr->SelectDay==2) {
                                        $SelectDay='New Working Day';
                                    }
                                    if($lr->TimeCountsIfOnDemurrage==1) {
                                        $TimeCountsIfOnDemurrage='Yes';
                                    }else if($lr->TimeCountsIfOnDemurrage==2) {
                                        $TimeCountsIfOnDemurrage='No';
                                    }
                                }
                                $html .='<tr>';
                                $html .='<td>'.$lr->DayFrom.'</td>';
                                $html .='<td>'.$lr->DayTo.'</td>';
                                $html .='<td>'.$lr->TimeFrom.'</td>';
                                $html .='<td>'.$lr->TimeTo.'</td>';
                                $html .='<td>'.$lr->LaytimeCode.'</td>';
                                $html .='<td>'.$TurnTimeExpire.'</td>';
                                $html .='<td>'.$LaytimeCommenceAt.'</td>';
                                $html .='<td>'.$LaytimeCommenceAtHour.'</td>';
                                $html .='<td>'.$SelectDay.'</td>';
                                $html .='<td>'.$TimeCountsIfOnDemurrage.'</td>';
                                $html .='</tr>';
                            }
                            $html .='</table>';
                        } else {
                            $html .='<div class="form-group">
									<label class="control-label col-lg-4">Enter laytime commencement : </label>
									<label class="control-label col-lg-8" style="text-align: left;">No</label>
									</div>';
                        }
                    } else {
                                                                                                             $html .='<div class="form-group">
								<label class="control-label col-lg-4">Enter Office hours : </label>
								<label class="control-label col-lg-8" style="text-align: left;">No</label>
								</div>';
                    }
                }
                        
                        
                        
                                    $j++;
            }
                    
                
                    $html .='<hr style="background-color:black;     height: 2px;" >';
            if($row->BACFlag) {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4">Brokerage / Add Comm : </label>
						<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
						</div>';
            } else {
                $html .='<div class="form-group">
						<label class="control-label col-lg-4">Brokerage / Add Comm : </label>
						<label class="control-label col-lg-8" style="text-align: left;">No</label>
						</div>';
            }
                    
                    $html .=$bachtml;
                    $CargoInternalComment=trim($row->CargoInternalComments, ' ');
            if($CargoInternalComment) {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="col-lg-12" style="text-align: left;"><label class="control-label col-lg-4" style="text-align: right; font-weight: 100;">Comments by Cargo owner : </label><label class="control-label col-lg-8" style="text-align: left;">'.$row->CargoInternalComments.'</label></label>
					</div>';
            }
                    $CargoDisplayComments=trim($row->CargoDisplayComments, ' ');
            if($CargoDisplayComments) {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="col-lg-12" style="text-align: left;"><label class="control-label col-lg-4" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-8" style="text-align: left;">'.$CargoDisplayComments.'</label></label>
					</div>';
                    
            }
                $i++;
        }
    }
    $html .='<br/><hr style="background-color:black; height: 2px;" ><br/>';
    echo $html;
}
    
public function htmlDownloadResponse()
{
    $this->load->model('cargo_model', '', true);
    include_once APPPATH.'third_party/mpdf.php';
    $data['data']=$this->cargo_model->getCharterDetail();
    $data['data3']=$this->cargo_model->getReferenceDetail();
    $data['data1']=$this->cargo_quote_model->get_response_cargo_html_details();
    $data['bacdata']=$this->cargo_quote_model->get_response_bac_details();
    $type='cp';
    //$data['data2']=$this->cargo_model->get_cargo_document_details($type);
        
    $html=$this->load->view('setup/pdfdownloadresponse', $data, true);
    //echo $html;die;
    $pdfFilePath = $data['data']->EntityName."(".$data['data']->AuctionID.").pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
}
    
public function htmlDownloadResponseCharter()
{
    $this->load->model('cargo_model', '', true);
    include_once APPPATH.'third_party/mpdf.php';
        
    //$html=$this->load->view('idcardpdf',$data,true);
    $data['data']=$this->cargo_model->getCharterDetail();
    $data['data3']=$this->cargo_model->getReferenceDetail();
    $data['data4']=$this->cargo_quote_model->get_response_allcargo_html_details1();
    $data['data5']=$this->cargo_quote_model->get_response_allBAC_html_details();
    $type='cp';
    //$data['data2']=$this->cargo_model->get_cargo_document_details($type);
        
    //$this->load->view('include/header');
    $html=$this->load->view('setup/pdfdownloadresponsecharter', $data, true);
    //$html='test';
    //echo $html;die;
    $pdfFilePath = $data['data']->EntityName."(".$data['data']->AuctionID.").pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
}
    
public function htmlDownloadResponseQuote()
{
    $this->load->model('cargo_model', '', true);
    include_once APPPATH.'third_party/mpdf.php';
    $auction=$this->input->get('AuctionId');
    $data['data1']=$this->cargo_quote_model->get_quote_html_details();
    //$data['data2']=$this->cargo_quote_model->get_quote_html_all_linenum();
        
    $type='quote';
    $data['data3']=$this->cargo_model->get_cargo_document_details($type);
        
    $data['data4']=$this->cargo_quote_model->get_vessel_html_details1();
    $type='vessel';
    $data['data5']=$this->cargo_model->get_cargo_document_details($type);
    $data['data6']=$this->cargo_quote_model->get_last_vessel_chat();
    $data['data7']=$this->cargo_quote_model->get_last_freight_chat(); 
    $data['data8']=$this->cargo_quote_model->get_last_cargo_chat(); 
    $data['data9']=$this->cargo_quote_model->get_last_term_chat(); 
    //print_r($data); die;
    $html=$this->load->view('setup/pdfdownloadresponsequote', $data, true);
    //$html='test';
    //echo $html;die;
    $pdfFilePath = $auction.".pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
}
    
public function getResponseById()
{
    $ResponseID=$this->input->post('respoanse');
    $EntityID=$this->input->post('EntityID');
    $UserID=$this->input->post('UserID');
    $data['res']=$this->cargo_quote_model->getResponseById();
    $data['BidStatusFlg']=0;
    $data['CharterDetailsFlg']=0;
    $data['InviteeCommentsFlg']=0;
    $data['ConfirmationFlg']=0;
    $data['VesselConfirmFlg']=0;
    $result=$this->cargo_quote_model->getAuctionRecordsByFreight($data['res']->AuctionID);
    if($data['res']->EntityID == $EntityID) {
        $inv_permission=$this->cargo_quote_model->getInviteePagePermissions($data['res']->AuctionID, $UserID);
        $vesselRecord=$this->cargo_quote_model->getVesselLatestRecordByResponseID($ResponseID);
        //print_r($inv_permission); die;
        if($inv_permission) {
            $data['BidStatusFlg']=$inv_permission->BidStatusFlg;
            $data['CharterDetailsFlg']=$inv_permission->CharterDetailsFlg;
            $data['InviteeCommentsFlg']=$inv_permission->InviteeCommentsFlg;
            $data['ConfirmationFlg']=$inv_permission->ConfirmationFlg;
        } 
            
        if($vesselRecord) {
            $data['VesselConfirmFlg']=$vesselRecord->VesselConfirmFlg;
        }
    }
        
    $i=0;
    $resp_id=0;
    $FreightArr=array();
    foreach($result as $rs){
        if($resp_id !=$rs->ResponseID) {
            $FreightArr[$i]=$rs->FreightRate;
            $resp_id=$rs->ResponseID;
            $j=$i;
            $i++;
        } else {
            $FreightArr[$j]=$FreightArr[$j]+$rs->FreightRate;
        }
    } 
    $max=0;
    $min=0;
    if($FreightArr) {
        $max = max($FreightArr);
        $min = min($FreightArr);
    }
        
    $e_time=0;
    if($data['res']->ExtendTime1) {
        $e_time=$e_time + $data['res']->ExtendTime1;
    }
    if($data['res']->ExtendTime2) {
        $e_time=$e_time + $data['res']->ExtendTime2;
    }
    if($data['res']->ExtendTime3) {
        $e_time=$e_time + $data['res']->ExtendTime3;
    }
    $new_time=strtotime($data['res']->AuctionCeases) + ($e_time*60);
    //print_r($new_time); die;
    $AuctionCeases=date('Y-m-d H:i:s', $new_time);
        
    $data['Auction_Ceases']=$AuctionCeases;
    $data['remdate']=$this->dateDiff($AuctionCeases);
        
    if($min) {
        $data['low']=$min;
    }else{
        $data['low']='0';
    }
    if($max) {
        $data['high']=$max;
    }else{
        $data['high']='0';
    }
    echo json_encode($data);
}
    
public function getResponse1()
{
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $LoginEntityID=$this->input->get('LoginEntityID');
    $RecordOwner=$this->input->get('RecordOwner');
    $UserID=$this->input->get('UserID');
        
    $data=$this->cargo_quote_model->getResponse1();
    $html='';
    $tempMasterID='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    $html='{ "aaData": [';
    foreach($data as $row) {
        $VesselName='';
        $VesselName=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
        if($vesselAutocomplete) {
            if($vesselAutocomplete != $VesselName->VesselName) {
                continue;
            }
        }
        if($InviteeEntity) {
            if($InviteeEntity != $row->EntityID) {
                  continue;
            }
        }
            
        if($row->ResponseStatus=='Inprogress') {
            $ResponseStatus='In Progress';
        } else {
            $ResponseStatus=$row->ResponseStatus;
        }
            
            $flag=$this->cargo_quote_model->checkChat($row->ResponseID);
            $FreightRate='';
            $FreightRecords=$this->cargo_quote_model->getLatestFreightQuotes($row->ResponseID);
            $QUOTE='';
            $cnt=1;
        foreach($FreightRecords as $fr){
                $QUOTE .="<a onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."','".$fr->LineNum."') title='view quote details'>QT$cnt</a>&nbsp;";
            if($fr->FreightRate) {
                $FreightRate =$FreightRate+$fr->FreightRate;
            }
                $cnt++;
        }
        if($flag) {
                $NewChatsResult=$this->cargo_quote_model->getNewChatsFlagByResponseID1($row->ResponseID);
                $new_flg=0;
            foreach($NewChatsResult as $nw){
                if($RecordOwner == $row->OwnerID) {
                    if($nw->VesselOwnerFlag==1 || $nw->FreightOwnerFlag==1 || $nw->CargoPortOwnerFlag==1 || $nw->TermOwnerFlag==1) {
                        $new_flg=1;
                    }
                } else {
                    if($nw->VesselInviteeFlag==1 || $nw->FreightInviteeFlag==1 || $nw->CargoPortInviteeFlag==1 || $nw->TermInviteeFlag==1) {
                        $new_flg=1;
                    }
                }
            }
            if($new_flg==1) {
                if($RecordOwner == $row->OwnerID) {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',1) title='view chat details'>View ".$newbadge."</a>";
                } else {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',2) title='view chat details'>View ".$newbadge."</a>";
                }
            } else {
                $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',0) title='view chat details'>View</a>";
            }
        } else {
                $view="No";
        }
            
        if($row->ResponseStatus == 'Closed') {
            $a=$row->ResponseID;
        } else if($RecordOwner == $row->OwnerID) {
            $a="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
        } else {
            $InvUsers=explode(",", $row->InvUserID);
            if(in_array($UserID, $InvUsers)) {
                $a="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
            } else {
                $a=$row->ResponseID;
            }
        }
            
            $CHTR="<a onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR TERMS</a>";
            
            $bp_flg=0;
        if($LoginEntityID==$row->EntityID) {
            $InviteeRecord=$this->cargo_quote_model->getAuctionInviteePrimeRole($row->AuctionID, $row->EntityID);
            $QuoteBP=$this->cargo_quote_model->getQuoteInviteeBusinessProcess($row->ResponseID);
            if($InviteeRecord->InviteeRole==6 && count($QuoteBP) > 0) {
                $bp_flg=1;
            }
        }
            
        if($tempMasterID != $row->AuctionID) {
            if($RecordOwner == $row->OwnerID) {
                if($row->ResponseStatus == 'Closed') {
                    $MasterID=$row->AuctionID.' ('.$row->OwnerEntityName.')';    
                } else {
                    $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."' >".$row->AuctionID."</a> (".$row->OwnerEntityName.")";
                }
            } else {
                $MasterID=$row->AuctionID.' ('.$row->OwnerEntityName.')';
            }
            $tempMasterID = $row->AuctionID;
        } else {
            $MasterID='';
        }
            
        $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ReleaseDate)).'","'.$ResponseStatus.'","'.$MasterID.'","'.$a.'","'.$row->EntityName.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'", "'.$FreightRate.'", "'.$VesselName->VesselName.'", "'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'", "'.$view.'","'.$CHTR.'","'.$QUOTE.'"],';
        $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getCargoDocument()
{
    $this->load->model('cargo_model', '', true);
    $type='cp';
    $data1=$this->cargo_model->get_cargo_document_details($type);
    //print_r($data1); die;
        
    if($data1) {
        $html .='<hr><h4><B>Available Documents</B></h4>';
        
        foreach($data1 as $doc){
            $html .='<div class="form-group">
				<label class="control-label col-lg-4" style="text-align: right;">'.$doc->FileName.'</label>
				<div class="col-xs-2 fieldinfo"><span><a href="'.base_url().'index.php/csetup/download_invitee_document?id='.$doc->DocumentID.'"><img src="img/doc_download.png" title="download"></img></a></span>&nbsp;&nbsp;<span onclick="getDocs('.$doc->DocumentID.')"><img src="img/doc_view.png" title="view"></img></span></div>
				</div>';
        }
        $html .='<hr>';
    }
                
    echo $html;
}
    
public function getEntityName()
{
    $data=$this->cargo_quote_model->getEntityName();
    echo $data->EntityName;
}
    
public function saveChat()
{
    $AuctionID=$this->input->post('AuctionID');
    $responsid=$this->input->post('responsid');
    $UserID=$this->input->post('UserID');
        
    $data=$this->cargo_quote_model->saveChat();
    if($data) {
        $this->cargo_quote_model->sendInprogressMessage1($AuctionID, $responsid, $UserID);
    }
        
    echo $data;
}
    
public function getChat()
{
    $data=$this->cargo_quote_model->getChat();
    $ResponseID=$this->input->post('ResponseID');
    $Type=$this->input->post('Type');
    $LineNum=$this->input->post('LineNum');
    $EntityID=$this->input->post('EntityID');
        
    $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
    $InviteeID=$RecordArr->EntityID;
        
    $NewAlertRow=$this->cargo_quote_model->getNewChatsFlgByResponseID($ResponseID, $LineNum, $Type);
    $NewFlg=0;
    if($Type=='Vessel') {
        if($EntityID==$RecordArr->RecordOwner && $NewAlertRow->VesselOwnerFlag==1) {
            $NewFlg=1;
        } else if($EntityID==$RecordArr->EntityID && $NewAlertRow->VesselInviteeFlag==1) {
            $NewFlg=1;
        }
    } else if($Type=='Freight') {
        if($EntityID==$RecordArr->RecordOwner && $NewAlertRow->FreightOwnerFlag==1) {
            $NewFlg=1;
        } else if($EntityID==$RecordArr->EntityID && $NewAlertRow->FreightInviteeFlag==1) {
            $NewFlg=1;
        }
    } else if($Type=='CargoPort') {
        if($EntityID==$RecordArr->RecordOwner && $NewAlertRow->CargoPortOwnerFlag==1) {
            $NewFlg=1;
        } else if($EntityID==$RecordArr->EntityID && $NewAlertRow->CargoPortInviteeFlag==1) {
            $NewFlg=1;
        }
    } else if($Type=='Terms') {
        if($EntityID==$RecordArr->RecordOwner && $NewAlertRow->TermOwnerFlag==1) {
            $NewFlg=1;
        } else if($EntityID==$RecordArr->EntityID && $NewAlertRow->TermInviteeFlag==1) {
            $NewFlg=1;
        }
    }
        
    $num=count($data);
    $html='';
    foreach($data as $row) {
        if($row->AdUs=='admin') {
            $html.='<b><span style="color: #103e67;">'.$row->Invname.'</span></b> <span style="float: right;">'.date('d-M-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span>';
            if($row->chk_flag==1) {
                $html.='&nbsp;&nbsp;<span style="color: red;">*</span>';
            }
            $html.='<br><br>';
        } else {
            $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
            if($InviteeID==$EntityID) {
                  $html.='<b><span style="color: red;">'.$row->Invname.'</span></b> <span style="float: right;">'.date('d-M-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span>';    
                if($row->chk_flag==1) {
                    $html.='&nbsp;&nbsp;<span style="color: red;">*</span>';
                }
                $html.='<br><br>';
            } else {
                $html.='<b><span style="color: blue;">'.$row->Invname.'</span></b> <span style="float: right;">'.date('d-M-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span>';
                if($row->chk_flag==1) {
                    $html.='&nbsp;&nbsp;<span style="color: red;">*</span>';
                }
                $html.='<br><br>';
            }
        }
    }
    echo $html.'____'.$num.'____'.$NewFlg;
}
    
public function confirmation()
{
    $data=$this->cargo_quote_model->confirmation();
    echo $data;
}
    
public function getUser()
{
    $data['record']=$this->cargo_quote_model->getUser();
    $data['permission_type']=$this->cargo_quote_model->getEntityAutoPermission();
    echo json_encode($data);
}
    
public function finalSubmit()
{
    $data=$this->cargo_quote_model->finalSubmit();
    echo $data;
}
    
    
public function dateDiff($date)
{
    $date1=date('Y-m-d H:i:s');
    $date2=date('Y-m-d H:i:s', strtotime($date));
    $date1_time = strtotime($date1);  
    $date2_time = strtotime($date2);
    if($date2_time > $date1_time) {
        $diff = abs($date2_time - $date1_time);
        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
        $days = floor(($diff - $years * 365*60*60*24 -  $months*30*60*60*24)/ (60*60*24));    
        $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
        $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
        //$seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
        if($years > 0) {
            $data=$years." years, ".$months." months, ".$days." days, ".$hours." hours, ".$minutes." minutes remaining.";
        } else if($months > 0) {
            $data=$months." months, ".$days." days, ".$hours." hours, ".$minutes." minutes remaining.";
        } else if($days > 0) {
            $data=$days." days, ".$hours." hours, ".$minutes." minutes remaining.";
        } else if($hours > 0) {
            $data=$hours." hours, ".$minutes." minutes remaining.";
        } else if($minutes > 0) {
                $data=$minutes." minutes remaining.";
        }
    } else {
         $data="Quote time closed.";
    }
        
     return $data;
}
    
public function downloadChat()
{
    include_once APPPATH.'third_party/mpdf.php';
    $data=$this->cargo_quote_model->getChat();
    foreach($data as $row)
    {
        if($row->Invname=='Admin') {
            $html.='<b><span style="color: #103e67;">'.$row->Invname.'</span></b> <span style="float: right;">'.date('d-M-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
        } else {
            $html.='<b><span style="color: red;">'.$row->Invname.'</span></b> <span style="float: right;">'.date('d-M-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
        }
    }
    $pdfFilePath = "Chat.pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
    //echo $html;
}
    
public function allVesselData()
{
    $this->load->model('vessel_model');
    $res=$this->vessel_model->getVesselData1();
    $data_arr=array();
    $return_arr = array();
        
    foreach($res as $row){
        $data_arr['label']=$row->VesselName.' || '.$row->DWT.' || '.$row->Description;
        $data_arr['value']=$row->VesselName.' || '.$row->DWT.' || '.$row->Description;
        array_push($return_arr, $data_arr);
    }
    foreach($res as $row){
        if($row->VesselExName) {
            $data_arr['label']=$row->VesselExName.' || '.$row->DWT.' || '.$row->Description;
            $data_arr['value']=$row->VesselExName.' || '.$row->DWT.' || '.$row->Description;
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
    
public function getResponseCommentHtml()
{
    $chatSection=$this->input->post('chatSection');
    $ResponseID=$this->input->post('InviteeID');
    $OwnerInvFlg=$this->input->post('OwnerInvFlg');
    $data=$this->cargo_quote_model->getResponseCommentHtml();
    if($OwnerInvFlg==1 || $OwnerInvFlg==2) {
        $this->cargo_quote_model->changeChatNewFlg1($ResponseID, $OwnerInvFlg);
    }
    //print_r($ResponseID); die;
    $html='';
    $linenum=0;
    $cntr=1;
    if(count($data) > 0) {
        $html1='';
        $html2='';
        $html3='';
        $html4='';
        $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
        $InviteeID=$RecordArr->EntityID;
        if($chatSection=='TimeLine') {
            $html .='<table class="table table-striped table-hover table-bordered" style="font-size: 14px;">
				<thead class="dark"><tr><th>Datetime</th><th>Section</th><th>From</th><th>Comments</th></tr></thead><tbody>';
            foreach($data as $row) {
                $invname='';
                if($row->AdUs=='admin') {
                    $invname='<span style="color: #1b75bc;">'.$row->Invname.'</span>';
                } else {
                    $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                    if($InviteeID==$EntityID) {
                        $invname='<span style="color: red;">'.$row->Invname.'</span>';
                    }else {
                          $invname='<span style="color: blue;">'.$row->Invname.'</span>';
                    }
                }
                $html .='<tr>';
                $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'</td>';
                $html .='<td>'.$row->Type.'</td>';
                $html .='<td>'.$invname.'</td>';
                $html .='<td>'.$row->Chat.'</td>';
                $html .='</tr>';
            }
            $html .='</tbody></table><br/>';
        } else{
            foreach($data as $row) {
                if($row->Type=='Vessel') {
                    if($row->AdUs=='admin') {
                               $html1.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    } else {
                         $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                        if($InviteeID==$EntityID) {
                            $html1.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                            //invitee
                        } else {
                            $html1.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                            //record owner
                        }
                    }
                    $html1 .='<hr style="background-color: black;"><br/>';
                }
                if($row->Type=='Freight') {
                    if($row->AdUs=='admin') {
                        $html2.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    } else {
                        $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                        if($InviteeID==$EntityID) {
                            $html2.='<span style="color: red;">From: '.$row->Invname.'</span><span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                        } else {
                            $html2.='<span style="color: blue;">From: '.$row->Invname.'</span><span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                        }
                    }
                    $html2 .='<hr style="background-color: black;"><br/>';
                }
                if($row->Type=='CargoPort') {
                    if($row->AdUs=='admin') {
                        $html3.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    } else {
                        $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                        if($InviteeID==$EntityID) {
                            $html3.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                        } else {
                            $html3.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                        }
                    }
                    $html3 .='<hr style="background-color: black;"><br/>';
                }
                if($row->Type=='Terms') {
                    if($row->AdUs=='admin') {
                        $html4.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    } else {
                        $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                        if($InviteeID==$EntityID) {
                            $html4.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                        } else {
                            $html4.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                        }
                    }
                    $html4 .='<hr style="background-color: black;"><br/>';
                }
                    
            }
            if($html1) {
                $html .='<h3><b>Vessel</b></h3><br>';
                $html .='<hr style="background-color: black; height: 2px;"><br>';
                $html .=$html1;
            }
            if($html2) {
                $html .='<h3><b>Freight</b></h3><br>';
                $html .='<hr style="background-color: black; height: 2px;"><br>';
                $html .=$html2;
            }
            if($html3) {
                $html .='<h3><b>Cargo n Ports</b></h3><br>';
                $html .='<hr style="background-color: black; height: 2px;"><br>';
                $html .=$html3;
            }
            if($html4) {
                $html .='<h3><b>Terms</b></h3><br>';
                $html .='<hr style="background-color: black; height: 2px;"><br>';
                $html .=$html4;
            }
        }
            
            
    } 
    echo $html;
    
}
    
public function htmlDownloadResponseChat()
{
    include_once APPPATH.'third_party/mpdf.php';
    $ResponseID=$this->input->get('InviteeID');
    $chatSection=$this->input->get('chatSection');
    $data['data1']=$this->cargo_quote_model->getResponseCommentHtml();
    $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
    $data['InviteeID']=$RecordArr->EntityID;
    $data['chatSection']=$chatSection;
    //print_r($data); die;
    $html=$this->load->view('setup/pdfdownloadresponsechat', $data, true);
    //$html='test';
    //echo $html;die;
    $chatName='';
    if($chatSection==1) {
        $chatName='Vessel';
    } else if($chatSection==2) {
        $chatName='Freight';
    } else if($chatSection==3) {
        $chatName='CargoAndPorts';
    } else if($chatSection==4) {
        $chatName='Terms';
    } else{
        $chatName=$chatSection;
    }
        
    $pdfFilePath = $ResponseID."(".$chatName.").pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
}
    
public function getResponseAssessment()
{
    $AuctionID=$this->input->get('AuctionID');
    //$AuctionID='V17-L69-F06';
    $RecordOwner=$this->input->get('RecordOwner');
    //$RecordOwner=9295;
    $data=$this->cargo_quote_model->getResponseAssessment();
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    //print_r($responseids); die;
        
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
    $crgo=$this->cargo_quote_model->getCargoDataByAuctionID($AuctionID);
    //print_r($vesl); die;
    $lppd=date('Y-m-d', strtotime($crgo->LpPreferDate));
    if($lppd=='1970-01-01') {
        $lppd=date('Y-m-d', strtotime($crgo->LpLaycanStartDate));
    }
    //echo $lppd; die;
    $vesseldata= array();
    $responseid='';
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
        
    foreach($vesseldata as $v) {
            
        $lpsd=date('Y-m-d', strtotime($v->FirstLoadPortDate));
        $lppd1=date_create($lppd);
        $lpsd1=date_create($lpsd);
        $diff=date_diff($lppd1, $lpsd1);
        $ddef[]=$diff->format("%R%a");

    } 
        
    $ccnn=count($ddef);
    $prang=$ddef[$ccnn-1]-$ddef[0];
        
        
    $html='';
    $inhtml='';
    $pscdef='';
    $proxpref='';
    $pm=0;
    $rating=0;
    
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    $quote=$this->cargo_quote_model->getQuoteByAuctionID($AuctionID);
    $Demurrage=$this->cargo_quote_model->getDemmurageByAuctionID($AuctionID);
    foreach($data as $row) {
        $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID);
        $DemLP=($row->FreightRate*$row->CargoQtyMT);
        $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
        $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
        $DemCostLP=$row->Demurrage*$DelayLP;
        $DemCostDP=$row->Demurrage*$DelayDP;
        $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
        $DemCost=(($DemCostLP+$DemCostDP)/$row->CargoQtyMT);
        $FreightInclDemDelays=($TotalFrtInclDemDelays/$row->CargoQtyMT);
        $fidd[]=round($FreightInclDemDelays, 2);
        //print_r($DemLP); 
    }
    //die;
    sort($fidd);
    //print_r($fidd);die;
    $c=count($quote);
    $d=count($Demurrage);
    $f=count($fidd);
    //echo $c;die;
    $low=$quote[0]->FreightRate;
    $high=$quote[$c-1]->FreightRate;
    $range=$high-$low;
        
    $fiddlow=$fidd[0];
    $fiddhigh=$fidd[$f-1];
    $fiddrange1=$fiddhigh-$fiddlow;
    $fiddrange=round($fiddrange1, 2);
        
        
    $dlow=$Demurrage[0]->Demurrage;
    $dhigh=$Demurrage[$d-1]->Demurrage;
    $drange=$dhigh-$dlow;
        
    $difarray=array();
    //print_r($data); die;
    foreach($data as $r) {
        foreach($vesseldata as $v) {
            if($r->ResponseID==$v->ResponseID) {
                $d1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                $d2=date_create(date('Y-m-d', strtotime($r->LpPreferDate)));
                $d=date_diff($d2, $d1);
                $difarray[]=$d->format("%a");
            }
        }
    }
    rsort($difarray);
    $MaxDiff=$difarray[0];
    //echo $MaxDiff;die;
        
    $html='{ "aaData": [';
        
    foreach($data as $row) {
        
        $imo=$this->cargo_quote_model->getVesselImo($AuctionID, $row->ResponseID);
        
        if($row->TentativeStatus==1) {
            $status='Tentative Acceptance';
        } else {
            if($row->ResponseStatus=='Submitted') {
                  $status=$row->ResponseStatus;
            }else{
                $status='In progress';
                continue;
            }
        }
        
        foreach($vesseldata as $v) {
            if($row->ResponseID==$v->ResponseID) {
                $rating=0;
                //$ratingp='';
                if($row->EntityID==$v->DisponentOwnerID) {
                     $priority=$this->cargo_quote_model->getProrityByAuctionID($row->EntityID, $AuctionID);
                } else {
                      $priority=$this->cargo_quote_model->getProrityForShipBroker($v->DisponentOwnerID);
                }
            
                $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID);
            
                $DemLP=($row->FreightRate*$row->CargoQtyMT);
                $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                $DemCostLP=$row->Demurrage*$DelayLP;
                $DemCostDP=$row->Demurrage*$DelayDP;
                $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                $DemCost=(($DemCostLP+$DemCostDP)/$row->CargoQtyMT);
                $FreightInclDemDelays=round(($TotalFrtInclDemDelays/$row->CargoQtyMT), 2);
            
                $DatePer=0;
                $fiddper=0;
                $per=0;
                if($range==0) {
                           $ratio=0;
                } else {
                    $ratio=($row->FreightRate-$low)/$range;
                }
            
                if($drange==0) {
                     $dratio=0;
                } else {
                                 $dratio=($row->Demurrage-$dlow)/$drange;
                }
            
                if($fiddrange==0) {
                               $fidratio=0;
                } else {
                             $fidratio=($FreightInclDemDelays-$fiddlow)/$fiddrange;
                }
            
                if($model->FreightCriteriaStatus==1) {
                    if($row->FreightRate>0) {
                        $per=$ratio*100;
                        $perrange1=trim($model->FpercentageRange1, '%');
                        $perrange2=trim($model->FpercentageRange2, '%');
                        $perrange3=trim($model->FpercentageRange3, '%');
                        $perrange4=trim($model->FpercentageRange4, '%');
                        $perrange5=trim($model->FpercentageRange5, '%');
                        $pr1=$model->FpercentageRange1;
                        $pr2=$model->FpercentageRange2;
                        $pr3=$model->FpercentageRange3;
                        $pr4=$model->FpercentageRange4;
                        $pr5=$model->FpercentageRange5;
                        if($per<=$pr1) {
                            $rating=$model->FpercentageValue1;
                        } else  if($per>$pr1 && $per<=$pr2) {
                             $rating=$model->FpercentageValue2;
                        } else  if($per>$pr2 && $per<=$pr3) {
                            $rating=$model->FpercentageValue3;
                        } else  if($per>$pr3 && $per<=$pr4) {
                            $rating=$model->FpercentageValue4;
                        } else  if($per>$pr4) {
                            $rating=$model->FpercentageValue5;
                        }
                    } 
                    //$ratingp .=$rating.'->';
                }
            
                //echo $fiddper;die;
                if($model->FIDDCriteriaStatus==1) {
                         $fiddper=$fidratio*100;
                         $fiddpr1=$model->FIDDpercentageRange1;
                         $fiddpr2=$model->FIDDpercentageRange2;
                         $fiddpr3=$model->FIDDpercentageRange3;
                         $fiddpr4=$model->FIDDpercentageRange4;
                         $fiddpr5=$model->FIDDpercentageRange5;
                    if($fiddper <= $fiddpr1) {
                        $rating=$rating+$model->FIDDpercentageValue1;
                    } else  if($fiddper > $fiddpr1 && $fiddper<=$fiddpr2) {
                        $rating=$rating+$model->FIDDpercentageValue2;
                    } else  if($fiddper > $fiddpr2 && $fiddper<=$fiddpr3) {
                        $rating=$rating+$model->FIDDpercentageValue3;
                    } else  if($fiddper > $fiddpr3 && $fiddper<=$fiddpr4) {
                        $rating=$rating+$model->FIDDpercentageValue4;
                    } else  if($fiddper > $fiddpr4) {
                        $rating=$rating+$model->FIDDpercentageValue5;
                    } 
                    //$ratingp .=$rating.'->';                
                }
            
                if($model->DemurrageCriteriaStatus==1) {
                    if($row->Demurrage>0) {
                        $dper=$dratio*100;
                        $dpr1=$model->DpercentageRange1;
                        $dpr2=$model->DpercentageRange2;
                        $dpr3=$model->DpercentageRange3;
                        $dpr4=$model->DpercentageRange4;
                        $dpr5=$model->DpercentageRange5;
                        if($dper<=$dpr1) {
                            $rating=$rating+$model->DpercentageValue1;
                        } else  if($dper>$dpr1 && $dper<=$dpr2) {
                            $rating=$rating+$model->DpercentageValue2;
                        } else  if($dper>$dpr2 && $dper<=$dpr3) {
                              $rating=$rating+$model->DpercentageValue3;
                        } else  if($dper>$dpr3 && $dper<=$dpr4) {
                            $rating=$rating+$model->DpercentageValue4;
                        } else  if($dper>$dpr4) {
                             $rating=$rating+$model->DpercentageValue5;
                        }
                    }
                    //$ratingp .=$rating.'->';            
                }
            
                if($model->InviteeCriteriaStatus==1) {
                    if($priority=='P1') {
                        $rating=$rating+$model->InviteePriorityValue1;
                    } else if($priority=='P2') {
                        $rating=$rating+$model->InviteePriorityValue2;
                    } else if($priority=='P3') {
                        $rating=$rating+$model->InviteePriorityValue3;
                    } else {
                        $rating=$rating+$model->InviteePriorityValue4;
                    }
                    //$ratingp .=$rating.'-> ....'.$priority.'....';
                }
            
                if($model->PSCDLYCS==1) {    
                    if($v->DetentionFlag=='Yes') {
                        $rating=$rating+$model->PSCDLYAPC;
                    } else {
                        $rating=$rating+$model->PSCDLYMP;
                    }
                    //$ratingp .=$rating.'->';
                }

                if($model->PSCDPCS==1) {
                    if($v->Deficiency=='Outstanding') {
                        $rating=$rating+$model->PSCDPAPC;
                    } else {
                        $rating=$rating+$model->PSCDPMPC;
                    }
                    //$ratingp .=$rating.'->';
                }
        
                $compdate=date('Y-m-d', strtotime($v->DeficiencyCompDate));
                $lparivaldate=date('Y-m-d', strtotime($v->FirstLoadPortDate));
                if($model->PSCDRPFLPACS==1) {
                    if($v->Deficiency=='Outstanding' && ($compdate>=$lparivaldate)) {
                        $rating=$rating+$model->PSCDRPFLPAAPC;
                    } else {
                        $rating=$rating+$model->PSCDRPFLPAMPC;
                    }
                    //$ratingp .=$rating.'->';
                }
                $lpsd1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {    
                    $diff=date_diff($lppd1, $lpsd1);
                    $ddflpd=$diff->format("%R%a");
                    $ppeerr=($ddflpd/$prang)*100;
                }else{
                    $ppeerr=0;
                }
            
                $date1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                $date2=date_create(date('Y-m-d', strtotime($row->LpPreferDate)));
                $date3=date_create(date('Y-m-d', strtotime($v->DeficiencyCompDate)));
                if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {
                    if(date('Y-m-d', strtotime($row->LpPreferDate))=='1970-01-01') {
                        $pscdef='-';
                    }else{
                        $diff=date_diff($date2, $date1);
                        $pscdef=$diff->format("%R%a days");
                        $pscdefcal=$diff->format("%a days");
                        $DatePer=($pscdefcal/$MaxDiff)*100; 
                    }
                }else{
                    $pscdef='-';
                }
            
                if($model->PFLPPADCS==1) {
                    $ppad1=$model->PFLPPADMPC1;
                    $ppad2=$model->PFLPPADMPC2;
                    $ppad3=$model->PFLPPADMPC3;
                    $ppad4=$model->PFLPPADMPC4;
                    $ppad5=$model->PFLPPADMPC5;
                    if($DatePer<=$ppad1) {
                        $rating=$rating+$model->PFLPPADMPV1;
                    } else  if($DatePer>$ppad1 && $DatePer<=$ppad2) {
                        $rating=$rating+$model->PFLPPADMPV2;
                    } else  if($DatePer>$ppad2 && $DatePer<=$ppad3) {
                        $rating=$rating+$model->PFLPPADMPV3;
                    } else  if($DatePer>$ppad3 && $DatePer<=$ppad4) {
                        $rating=$rating+$model->PFLPPADMPV4;
                    } else {
                        $rating=$rating+$model->PFLPPADMPV5;
                    }
                    //$ratingp .=$rating.'->';
                }
            
                if($model->RatingStatus==1) {
                    if($v->Rating==1) {
                        $rating=$rating+$model->PrcentRangeValue1;
                    } else if($v->Rating==2) {
                        $rating=$rating+$model->PrcentRangeValue2;
                    } else if($v->Rating==3) {
                        $rating=$rating+$model->PrcentRangeValue3;
                    } else if($v->Rating==4) {
                        $rating=$rating+$model->PrcentRangeValue4;
                    } else if($v->Rating==5) {
                        $rating=$rating+$model->PrcentRangeValue5;
                    }
                    //$ratingp .=$rating.'->';
                }
        
                $diff1=date_diff($date1, $date3);
                $pm=$diff1->format("%R%a");
            
                if(date('Y-m-d', strtotime($v->DeficiencyCompDate))=='1970-01-01') {
                    $proxpref="<span title='PSC Def. rectify b4 arrival'>N/A</span>";
                } else {
                    if($pm>0) {
                        $proxpref="<span  title='PSC Def. rectify b4 arrival'>Yes</span>";
                    } else {
                        $proxpref="<span style='color: red;' title='PSC Def. rectify b4 arrival'>No</span>";
                    }
                }
        
        
                if($v->Rating==1) {
                    $risk_rating="<span style='color: red;' title='Risk Rating'>".$v->Rating."</span>";
                }else if($v->Rating==2) {
                    $risk_rating="<span style='color: orange;' title='Risk Rating'>".$v->Rating."</span>";
                }else{
                    $risk_rating="<span title='Risk Rating'>".$v->Rating."</span>";
                }
        
                if($v->DetentionFlag=='Yes') {
                    $DetentionFlag="<span style='color: red;' title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                }else{
                    $DetentionFlag="<span title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                }
                if($imo==0) {
                    $check="<input type='checkbox' disabled>";
                } else {
                    $check="<input type='checkbox' name='AuctionID[]' class='chk' value='".$row->ResponseID."'>";    
                }
                $CHTR="<a onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR </a>";
                $QUOTE="<a onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."') title='view quote details'>QUOTE</a>";
                if($imo==0) {
                    $inhtml .='["'.$check.'","<b>'.$status.'</b>","<b>'.$row->EntityName.'</b>';
                } else {
                    $inhtml .='["'.$check.'","'.$status.'","'.$row->EntityName;
                }
                if($model->InviteeCriteriaStatus==1) {
                    if($imo==0) {
                        $inhtml .='","<b>'.$priority.'</b>';
                    } else {
                        $inhtml .='","'.$priority;
                    }
                }
                if($imo==0) {
                    $inhtml .='","<b>'.$row->ResponseID.'</b>","<b>'.$v->VesselName.'</b>';
                } else {
                    $inhtml .='","'.$row->ResponseID.'","'.$v->VesselName;    
                }
                if($model->RatingStatus==1) {
                    if($imo==0) {
                        $inhtml .='", "-';
                    } else {
                        $inhtml .='", "'.$risk_rating;
                    }
                }
                if($model->FreightCriteriaStatus==1) {
                    if($imo==0) {
                        $inhtml .='","<b>'.$row->FreightRate.'</b>';
                    } else {
                        $inhtml .='","'.$row->FreightRate;
                    }
                }
        
                if($model->FIDDCriteriaStatus==1) {
                    if($imo==0) {
                        $inhtml .='","<b>'.number_format($FreightInclDemDelays, 3).'</b>';
                    } else {
                        $inhtml .='","'.number_format($FreightInclDemDelays, 3);
                    }
                }
        
                if($model->DemurrageCriteriaStatus==1) {
                    if($imo==0) {
                        $inhtml .='","<b>'.$row->Demurrage.'</b>';
                    } else {
                        $inhtml .='","'.$row->Demurrage;
                    }
                }
                if($imo==0) {
                    $inhtml .='", "<b>'.$row->Estimate_mt.'</b>","<b>'.$row->Estimate_Index_mt.'</b>';
                } else {
                    $inhtml .='", "'.$row->Estimate_mt.'","'.$row->Estimate_Index_mt;    
                }
                if($model->PSCDLYCS==1) {
                    if($imo==0) {
                        $inhtml .='", "-';
                    } else {
                        $inhtml .='","'.$DetentionFlag;
                    }
                }
                if($model->PSCDPCS==1) {
                    if($imo==0) {
                        $inhtml .='", "-';
                    } else {
                        $inhtml .='","'.$v->Deficiency;
                    }
                }
                if($model->PFLPPADCS==1) {
                    $inhtml .='","'.$pscdef;
                }
                if($model->PSCDRPFLPACS==1) {
                    if($imo==0) {
                        $inhtml .='", "-';
                    } else {
                        $inhtml .='","'.$proxpref;
                    }
                }
                if($model->IPOLYCS==1) {
                    $inhtml .='","NA';
                }
                if($model->VLTCS==1) {
                    $inhtml .='","NA';
                }
        
                $rating1="<input type='hidden' name='rating[]' class='rating' value='".$rating."'>".$rating."";
                //$rating1 .='=>'.$ratingp;
                if($imo==0) {
                    $inhtml .='", "-';
                } else {
                    $inhtml .='", "'.$rating1;
                }
                $inhtml .='","'.$CHTR.'&nbsp'.$QUOTE.'"],';
            }
        }
         
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
        
    echo $html; 
} 
    
public function deleteChat()
{
    $ret=$this->cargo_quote_model->deleteChat();
    echo ret;
}
    
public function getTentative()
{
    $this->load->model('cargo_model', '', true);
    $RecordOwner=$this->input->get('RecordOwner');
    //$RecordOwner='9295';
    $data=$this->cargo_quote_model->getResponseAssessment1();
    //print_r($data); die;
    $html='';
    $pscdef='';
    $proxpref='';
    $pm=0;
    $rating=0;
    
    $inhtml='';
    $html='{ "aaData": [';
        
    $i=1;
    $TID_CHK='';
    foreach($data as $row) {
        if($TID_CHK==$row->ResponseID) {
            continue;
        } else {
            $TID_CHK=$row->ResponseID;
        }
        $VesselRow=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
        if($VesselRow) {
            $VesselName=$VesselRow->VesselName;
        }else{
            $VesselName='';
        }
            $Entity=$this->cargo_model->getEntityById($row->ShipOwnerID);
            $FixtureStatus=$this->cargo_quote_model->getFixtureByAuctionID($row->AuctionID, $row->ResponseID);
            $DocumentationStatus=$this->cargo_quote_model->getDocumentationByAuctionID($row->AuctionID, $row->ResponseID);
            $auctOwnerRow=$this->cargo_quote_model->getAuctionRecordOwner($row->AuctionID);
        
            $status='';

        if($auctOwnerRow->OwnerEntityID==$RecordOwner) {
                $flg=1;
        } else if($RecordOwner==$row->EntityID) {
                $flg=0;
        } else {
            $flg=2;
        }
            
        if($DocumentationStatus==2) {
            $status='CharterParty Complete';
        } else if($FixtureStatus==2) {
            $status='Fixture Complete';
        } else if($row->TentativeStatus==1) {
            $status='Tentative Acceptance';
        } else {
            $status=$row->ResponseStatus;
        }
            
            $action="<a href='javascript: void(0);' onclick=editAuctionMain('".$row->ResponseID."_".$row->AuctionID."_".$flg."')  title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            $ckbx="<input type='checkbox' name='ResponseID[]' class='chkNumber' value='".$row->ResponseID."_".$row->AuctionID."_".$flg."' style='margin-bottom: 6px;' >";
            //$inhtml .='["'.$i.'","'.date('d-m-Y H:i:s',strtotime($row->UserDateTentative)).'","'.$auctOwnerRow->EntityName.'","'.$row->AuctionID.'","'.$status.'","'.$row->EntityName.'","'.$Entity->EntityName.'","'.$row->ResponseID.'","'.$VesselName.'","'.$row->FreightRate.'","'.$row->TotalRating.'","'.$row->confirm1.'","'.$action.'"],';
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDateTentative)).'","'.$auctOwnerRow->EntityName.'","'.$row->AuctionID.'","'.$status.'","'.$row->EntityName.'","'.$Entity->EntityName.'","'.$row->ResponseID.'","'.$VesselName.'","'.$row->confirm1.'","'.$action.'"],';
            $i++;
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getAdminResponse()
{
    $vesselAutocomplete=$this->input->get('vesselAutocomplete');
    $InviteeEntity=$this->input->get('InviteeEntity');
    $RecordOwner=$this->input->get('RecordOwner');
    $LoginEntityID=$this->input->get('LoginEntityID');
    //echo 'asdas'; die;
    $data=$this->cargo_quote_model->getAdminResponse();
    //print_r($data); die;
    $html='';
    $inhtml='';
    $tempMasterID='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
        $VesselName='';
        $VesselName=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
        if($vesselAutocomplete) {
            if($vesselAutocomplete != $VesselName->VesselName) {
                continue;
            }
        }
        if($InviteeEntity) {
            if($InviteeEntity != $row->EntityID) {
                  continue;
            }
        }
            
        if($row->ResponseStatus=='Inprogress') {
            $ResponseStatus='In Progress';
        }else{
            $ResponseStatus=$row->ResponseStatus;
        }
            
            $flag=$this->cargo_quote_model->checkChat($row->ResponseID);
            $FreightRate='';
            $FreightRecords=$this->cargo_quote_model->getLatestFreightQuotes($row->ResponseID);
            $QUOTE='';
            $cnt=1;
        foreach($FreightRecords as $fr){
                $QUOTE .="<a href='javascript: void(0);' onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."','".$fr->LineNum."') title='view quote details'>QT$cnt</a>&nbsp;";
            if($fr->FreightRate) {
                $FreightRate =$FreightRate+$fr->FreightRate;
            }
                $cnt++;
        }
            
        if($flag) {
                $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."') title='view chat details'>View</a>";
        } else {
                $view="No";
        }
            
            $bp_flg=0;
        if($LoginEntityID==$row->EntityID) {
            $InviteeRecord=$this->cargo_quote_model->getAuctionInviteePrimeRole($row->AuctionID, $row->EntityID);
            $QuoteBP=$this->cargo_quote_model->getQuoteInviteeBusinessProcess($row->ResponseID);
            if($InviteeRecord->InviteeRole==6 && count($QuoteBP) > 0) {
                $bp_flg=1;
            }
        }
            
        if($row->ResponseStatus == 'Closed') {
            $a=$row->ResponseID;
        } else {
            $a="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
        }
            
            $CHTR="<a href='javascript: void(0);' onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR TERMS</a>";
            
        if($tempMasterID != $row->AuctionID) {
            if($row->ResponseStatus == 'Closed') {
                $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."'>".$row->AuctionID."</a>(".$row->OwnerEntityName.")";    
            }else{
                $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."' >".$row->AuctionID."</a>(".$row->OwnerEntityName.")";
            }
            $tempMasterID = $row->AuctionID;
        } else {
            $MasterID='';
        }
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ReleaseDate)).'","'.$ResponseStatus.'","'.$MasterID.'","'.$a.'","'.$row->EntityName.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'", "'.$FreightRate.'", "'.$VesselName->VesselName.'", "'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'", "'.$view.'","'.$CHTR.'","'.$QUOTE.'"],';
            
            $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html; 
}
	
	
public function updateTentetive() 
{
	$this->load->model('cargo_model','',true); 
	$this->load->model('cp_fn_model','',true); 
	if($this->input->post()) {
		extract($this->input->post());
	}
	if($this->input->get()) {
		extract($this->input->get());
	}
	$this->cp_fn_model->CreateVersionBusinessProcess();
	$this->cp_fn_model->PlaceBusinessProcess();
	$this->cargo_quote_model->updateTentetive();
	$this->cargo_quote_model->sendUpdateTentetiveMessages();
	
	$this->cp_fn_model->deleteInviteeFixture();
	
	$data1=$this->cargo_model->getCharterDetail();
	$data2=$this->cargo_model->getReferenceDetail();
	$data3=$this->cargo_quote_model->get_response_allcargo_html_details();
	$data4=$this->cargo_quote_model->get_quote_html_details_new();
	$data5=$this->cargo_quote_model->get_quote_html_details1();
	$data6=$this->cargo_quote_model->get_vessel_html_details1();
	
	$bankDetails=$this->cargo_quote_model->getBankDetailByAuctionID($AuctionId);
	
	$BankEntityID=$data4[0]->EntityID;
	$bankDetailsInvitee=$this->cargo_quote_model->getBankDetailByAuctionIDInvitee($AuctionId,$BankEntityID);
	
	$tptfields=$this->cp_fn_model->getFieldsFromTemplate();
	
	$cpText=$this->cp_fn_model->getCptextFromTemplate();
	$EditField=$this->cargo_model->getEditableField();
	$data_arr=array();
	$fix_data_arr=array();
	$html='';
	$nonEditAble='';
	$FreightRecord=$this->cargo_quote_model->getResponseFreightRecord();
	$EntityRow=$this->cp_fn_model->getEntityFixtureCompleteProcess();
	if($EntityRow->FixtureCompleteProcess==1){
		$nonEditAble='style="cursor: not-allowed; -webkit-user-select: none; -moz-user-select: -moz-none; -ms-user-select: none; user-select: none; background-color: #efeaead6" contenteditable="false" ';
	}
	if($cpText->Type==1) {
		$html ='<table id="tb1" border="1" cellpadding="5" cellspacing="0" width="100%" '.$nonEditAble.'><tbody><tr contenteditable="false" ><td  >CpCode</td><td  >Field name(label)</td><td >Field value</td></tr>';
		
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Charter Details</td><td></td></tr>';
		
		if($tptfields[0]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Record_Owner_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[0]->CpCode.'</td><td>'.$tptfields[0]->NewDisplayName.'</td><td>'.$data1->EntityName.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[0]->FieldName.'1>'.$tptfields[0]->CpCode.'</'.$tptfields[0]->FieldName.'1></td><td><'.$tptfields[0]->FieldName.'2>'.$tptfields[0]->NewDisplayName.'</'.$tptfields[0]->FieldName.'2></td><td><'.$tptfields[0]->FieldName.'3>'.$data1->EntityName.'</'.$tptfields[0]->FieldName.'3></td></tr>';
			}
		
		}
		if($tptfields[1]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Role_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[1]->CpCode.'</td><td>'.$tptfields[1]->NewDisplayName.'</td><td>'.$data1->RoleDescription.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[1]->FieldName.'1>'.$tptfields[1]->CpCode.'</'.$tptfields[1]->FieldName.'1></td><td><'.$tptfields[1]->FieldName.'2>'.$tptfields[1]->NewDisplayName.'</'.$tptfields[1]->FieldName.'2></td><td><'.$tptfields[1]->FieldName.'3>'.$data1->RoleDescription.'</'.$tptfields[1]->FieldName.'3></td></tr>';
			}
		
		}
		if($tptfields[2]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='MasterID_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[2]->CpCode.'</td><td>'.$tptfields[2]->NewDisplayName.'</td><td>'.$data1->AuctionID.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[2]->FieldName.'1>'.$tptfields[2]->CpCode.'</'.$tptfields[2]->FieldName.'1></td><td><'.$tptfields[2]->FieldName.'2>'.$tptfields[2]->NewDisplayName.'</'.$tptfields[2]->FieldName.'2></td><td><'.$tptfields[2]->FieldName.'3>'.$data1->AuctionID.'</'.$tptfields[2]->FieldName.'3></td></tr>';
			}
		
		}
		if($tptfields[3]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='select_place_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[3]->CpCode.'</td><td>'.$tptfields[3]->NewDisplayName.'</td><td>'.$data1->cCode.' ( '.$data1->cDescription.' ) </td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[3]->FieldName.'1>'.$tptfields[3]->CpCode.'</'.$tptfields[3]->FieldName.'1></td><td><'.$tptfields[3]->FieldName.'2>'.$tptfields[3]->NewDisplayName.'</'.$tptfields[3]->FieldName.'2></td><td><'.$tptfields[3]->FieldName.'3>'.$data1->cCode.' ( '.$data1->cDescription.' ) </'.$tptfields[3]->FieldName.'3></td></tr>';
			}
		
		}
		
		$UserSignDate='';	
		$SignFlag='';	
		if($data1->SignDateFlg==2) {
			$UserSignDate=date('d-m-Y',strtotime($data1->UserSignDate));
			$SignFlag='User specified date';
		} else {
			$UserSignDate='-';
			$SignFlag='As per system date';	
		}
		
		if($tptfields[4]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='signing_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[4]->CpCode.'</td><td>'.$tptfields[4]->NewDisplayName.'</td><td>'.$SignFlag.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[4]->FieldName.'1>'.$tptfields[4]->CpCode.'</'.$tptfields[4]->FieldName.'1></td><td><'.$tptfields[4]->FieldName.'2>'.$tptfields[4]->NewDisplayName.'</'.$tptfields[4]->FieldName.'2></td><td><'.$tptfields[4]->FieldName.'3>'.$SignFlag.'</'.$tptfields[4]->FieldName.'3></td></tr>';
			}
		
		}	
		
		if($tptfields[5]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='user_signing_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[5]->CpCode.'</td><td>'.$tptfields[5]->NewDisplayName.'</td><td>'.$UserSignDate.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[5]->FieldName.'1>'.$tptfields[5]->CpCode.'</'.$tptfields[5]->FieldName.'1></td><td><'.$tptfields[5]->FieldName.'2>'.$tptfields[5]->NewDisplayName.'</'.$tptfields[5]->FieldName.'2></td><td><'.$tptfields[5]->FieldName.'3>'.$UserSignDate.'</'.$tptfields[5]->FieldName.'3></td></tr>';
			}
		
		}
		
		$SelectFrom='';
		if($data1->SelectFrom==1) {
			$SelectFrom='Manual';
		} else if($data1->SelectFrom==2) {
			$SelectFrom='Import from Topmarx';
		} else if($data1->SelectFrom==3) {
			$SelectFrom='Import from Customer(BHP Billiton Freight) System';
		}
		
		if($tptfields[6]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_charter_from_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[6]->CpCode.'</td><td>'.$tptfields[6]->NewDisplayName.'</td><td>'.$SelectFrom.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[6]->FieldName.'1>'.$tptfields[6]->CpCode.'</'.$tptfields[6]->FieldName.'1></td><td><'.$tptfields[6]->FieldName.'2>'.$tptfields[6]->NewDisplayName.'</'.$tptfields[6]->FieldName.'2></td><td><'.$tptfields[6]->FieldName.'3>'.$SelectFrom.'</'.$tptfields[6]->FieldName.'3></td></tr>';
			}
		
		}
		
		
		$contracttype='';
		if($data1->ContractType==1) {
			$contracttype='Spot';
		}
		if($data1->ContractType==2) {
			$contracttype='Contract';
		}
		
		if($tptfields[7]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Contract_type_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[7]->CpCode.'</td><td>'.$tptfields[7]->NewDisplayName.'</td><td>'.$contracttype.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[7]->FieldName.'1>'.$tptfields[7]->CpCode.'</'.$tptfields[7]->FieldName.'1></td><td><'.$tptfields[7]->FieldName.'2>'.$tptfields[7]->NewDisplayName.'</'.$tptfields[7]->FieldName.'2></td><td><'.$tptfields[7]->FieldName.'3>'.$contracttype.'</'.$tptfields[7]->FieldName.'3></td></tr>';
			}
		
		}
		
		if($data1->COAReference){
		if($tptfields[8]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Contract_COA_reference_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[8]->CpCode.'</td><td>'.$tptfields[8]->NewDisplayName.'</td><td>'.$data1->COAReference.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[8]->FieldName.'1>'.$tptfields[8]->CpCode.'</'.$tptfields[8]->FieldName.'1></td><td><'.$tptfields[8]->FieldName.'2>'.$tptfields[8]->NewDisplayName.'</'.$tptfields[8]->FieldName.'2></td><td><'.$tptfields[8]->FieldName.'3>'.$data1->COAReference.'</'.$tptfields[8]->FieldName.'3></td></tr>';
			}
		
		}
		} 
		
		if($data1->SalesAgreementReference !=''){
		if($tptfields[9]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Sales_agreement_reference_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[9]->CpCode.'</td><td>'.$tptfields[9]->NewDisplayName.'</td><td>'.$data1->SalesAgreementReference.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[9]->FieldName.'1>'.$tptfields[9]->CpCode.'</'.$tptfields[9]->FieldName.'1></td><td><'.$tptfields[9]->FieldName.'2>'.$tptfields[9]->NewDisplayName.'</'.$tptfields[9]->FieldName.'2></td><td><'.$tptfields[9]->FieldName.'3>'.$data1->SalesAgreementReference.'</'.$tptfields[9]->FieldName.'3></td></tr>';
			}
		
		}
		} 
		
		$ModelFunction='';
		if($data1->ModelFunction==1){
			$ModelFunction='Default (all charters)';
		} else if($data1->ModelFunction==2){
			$ModelFunction='User selected (individual charters)';
		}
		
		if($tptfields[10]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_model_type_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[10]->CpCode.'</td><td>'.$tptfields[10]->NewDisplayName.'</td><td>'.$ModelFunction.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[10]->FieldName.'1>'.$tptfields[10]->CpCode.'</'.$tptfields[10]->FieldName.'1></td><td><'.$tptfields[10]->FieldName.'2>'.$tptfields[10]->NewDisplayName.'</'.$tptfields[10]->FieldName.'2></td><td><'.$tptfields[10]->FieldName.'3>'.$ModelFunction.'</'.$tptfields[10]->FieldName.'3></td></tr>';
			}
		
		}
		
		$mdlRow=$this->cargo_quote_model->getModelName($data1->ModelNumber);
		
		if($mdlRow){
		if($tptfields[11]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_model_name_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[11]->CpCode.'</td><td>'.$tptfields[11]->NewDisplayName.'</td><td>'.$mdlRow->ModelNumber.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[11]->FieldName.'1>'.$tptfields[11]->CpCode.'</'.$tptfields[11]->FieldName.'1></td><td><'.$tptfields[11]->FieldName.'2>'.$tptfields[11]->NewDisplayName.'</'.$tptfields[11]->FieldName.'2></td><td><'.$tptfields[11]->FieldName.'3>'.$mdlRow->ModelNumber.'</'.$tptfields[11]->FieldName.'3></td></tr>';
			}
		
		}
		}
		
		if($data1->ShipmentReferenceID !=''){
			if($tptfields[12]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Shipment_Reference_ID_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[12]->CpCode.'</td><td><'.$tptfields[12]->FieldName.'2>'.$tptfields[12]->NewDisplayName.'</td><td>'.$data1->ShipmentReferenceID.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[12]->FieldName.'1>'.$tptfields[12]->CpCode.'</'.$tptfields[12]->FieldName.'1></td><td><'.$tptfields[12]->FieldName.'2>'.$tptfields[12]->NewDisplayName.'</'.$tptfields[12]->FieldName.'2></td><td><'.$tptfields[12]->FieldName.'3>'.$data1->ShipmentReferenceID.'</'.$tptfields[12]->FieldName.'3></td></tr>';
				}
			
			}
		}
		
		
		if($data2) {
			$html .='<tr><td >Other Reference ID</td><td></td><td></td></tr>';
		}
		
		$bank_name='';
		$bank_address1='';
		$bank_address2='';
		$bank_address3='';
		$bank_address4='';
		$bank_country='';
		$bank_state='';
		$bank_city='';
		$bank_pincode='';
		$account_name='';
		$account_number='';
		$currencty_of_payment='';
		$correspondent_bank1='';
		$correspondent_bank2='';
		$bank_code='';
		$bank_branch_code='';
		$swift_bic_code='';
		$ifsc_code='';
		$bank_iban='';
		$sort_code='';
		$aba_number='';
		$bank_detail_applies_to='';
		foreach($bankDetails as $row) {
			$bank_name .=  $row->BankName.' || ';
			$bank_address1 .=  $row->BankAddress1.' || ';
			$bank_address2 .=  $row->BankAddress2.' || ';
			$bank_address3 .=  $row->BankAddress3.' || ';
			$bank_address4 .=  $row->BankAddress4.' || ';
			$bank_country .=  $row->country.' || ';
			$bank_state .=  $row->state.' || ';
			$bank_city .=  $row->City.' || ';
			$bank_pincode .=  $row->ZipCode.' || ';
			$account_name .=  $row->AccountName.' || ';
			$account_number .=  $row->AccountNumber.' || ';
			$currencty_of_payment .=  $row->currency.' || ';
			$correspondent_bank1 .=  $row->CorrespondentBank1.' || ';
			$correspondent_bank2 .=  $row->CorrespondentBank2.' || ';
			$bank_code .=  $row->BankCode.' || ';
			$bank_branch_code .=  $row->BankBranchCode.' || ';
			$swift_bic_code .=  $row->SwiftCode.' || ';
			$ifsc_code .=  $row->IfscCode.' || ';
			$bank_iban .=  $row->IbanCode.' || ';
			$sort_code .=  $row->SortCode.' || ';
			$aba_number .=  $row->AbaNumber.' || ';
			$apl='';
			$AppliesTo=explode(',',$row->AppliesTo);
			for($i=0;$i<count($AppliesTo);$i++) {
				if($AppliesTo[$i]==1) {
					$apl .='Freight payment,';
				} else if($AppliesTo[$i]==2) {
					$apl .='Miscellaneous payment,';
				} else if($AppliesTo[$i]==3) {
					$apl .='Hire payment,';
				} else if($AppliesTo[$i]==4) {
					$apl .='Freight invoice,';
				} else if($AppliesTo[$i]==5) {
					$apl .='Miscellaneous invoice,';
				} else if($AppliesTo[$i]==6) {
					$apl .='Hire invoice,';
				}
			}
			$apl=trim($apl,',');
			$bank_detail_applies_to .=  $apl.' || ';
		}
		
		$bank_name=rtrim($bank_name,' || ');
		$bank_address1=rtrim($bank_address1,' || ');
		$bank_address2=rtrim($bank_address2,' || ');
		$bank_address3=rtrim($bank_address3,' || ');
		$bank_address4=rtrim($bank_address4,' || ');
		$bank_country=rtrim($bank_country,' || ');
		$bank_state=rtrim($bank_state,' || ');
		$bank_city=rtrim($bank_city,' || ');
		$bank_pincode=rtrim($bank_pincode,' || ');
		$account_name=rtrim($account_name,' || ');
		$account_number=rtrim($account_number,' || ');
		$currencty_of_payment=rtrim($currencty_of_payment,' || ');
		$correspondent_bank1=rtrim($correspondent_bank1,' || ');
		$correspondent_bank2=rtrim($correspondent_bank2,' || ');
		$bank_code=rtrim($bank_code,' || ');
		$bank_branch_code=rtrim($bank_branch_code,' || ');
		$swift_bic_code=rtrim($swift_bic_code,' || ');
		$ifsc_code=rtrim($ifsc_code,' || ');
		$bank_iban=rtrim($bank_iban,' || ');
		$sort_code=rtrim($sort_code,' || ');
		$aba_number=rtrim($aba_number,' || ');
		$bank_detail_applies_to=rtrim($bank_detail_applies_to,' || ');
			
			//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Charter Bank Details</td><td></td></tr>';
			
			if($tptfields[13]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[13]->FieldName.'1>'.$tptfields[13]->CpCode.'</'.$tptfields[13]->FieldName.'1></td><td><'.$tptfields[13]->FieldName.'2>'.$tptfields[13]->NewDisplayName.'</'.$tptfields[13]->FieldName.'2></td><td><'.$tptfields[13]->FieldName.'3>'.$bank_name.'</'.$tptfields[13]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[14]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[14]->FieldName.'1>'.$tptfields[14]->CpCode.'</'.$tptfields[14]->FieldName.'1></td><td><'.$tptfields[14]->FieldName.'2>'.$tptfields[14]->NewDisplayName.'</'.$tptfields[14]->FieldName.'2></td><td><'.$tptfields[14]->FieldName.'3>'.$bank_address1.'</'.$tptfields[14]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[15]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[15]->FieldName.'1>'.$tptfields[15]->CpCode.'</'.$tptfields[15]->FieldName.'1></td><td><'.$tptfields[15]->FieldName.'2>'.$tptfields[15]->NewDisplayName.'</'.$tptfields[15]->FieldName.'2></td><td><'.$tptfields[15]->FieldName.'3>'.$bank_address2.'</'.$tptfields[15]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[16]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[16]->FieldName.'1>'.$tptfields[16]->CpCode.'</'.$tptfields[16]->FieldName.'1></td><td><'.$tptfields[16]->FieldName.'2>'.$tptfields[16]->NewDisplayName.'</'.$tptfields[16]->FieldName.'2></td><td><'.$tptfields[16]->FieldName.'3>'.$bank_address3.'</'.$tptfields[16]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[17]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[17]->FieldName.'1>'.$tptfields[17]->CpCode.'</'.$tptfields[17]->FieldName.'1></td><td><'.$tptfields[17]->FieldName.'2>'.$tptfields[17]->NewDisplayName.'</'.$tptfields[17]->FieldName.'2></td><td><'.$tptfields[17]->FieldName.'3>'.$bank_address4.'</'.$tptfields[17]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[18]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[18]->FieldName.'1>'.$tptfields[18]->CpCode.'</'.$tptfields[18]->FieldName.'1></td><td><'.$tptfields[18]->FieldName.'2>'.$tptfields[18]->NewDisplayName.'</'.$tptfields[18]->FieldName.'2></td><td><'.$tptfields[18]->FieldName.'3>'.$bank_country.'</'.$tptfields[18]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[19]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[19]->FieldName.'1>'.$tptfields[19]->CpCode.'</'.$tptfields[19]->FieldName.'1></td><td><'.$tptfields[19]->FieldName.'2>'.$tptfields[19]->NewDisplayName.'</'.$tptfields[19]->FieldName.'2></td><td><'.$tptfields[19]->FieldName.'3>'.$bank_state.'</'.$tptfields[19]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[20]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[20]->FieldName.'1>'.$tptfields[20]->CpCode.'</'.$tptfields[20]->FieldName.'1></td><td><'.$tptfields[20]->FieldName.'2>'.$tptfields[20]->NewDisplayName.'</'.$tptfields[20]->FieldName.'2></td><td><'.$tptfields[20]->FieldName.'3>'.$bank_city.'</'.$tptfields[20]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[21]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[21]->FieldName.'1>'.$tptfields[21]->CpCode.'</'.$tptfields[21]->FieldName.'1></td><td><'.$tptfields[21]->FieldName.'2>'.$tptfields[21]->NewDisplayName.'</'.$tptfields[21]->FieldName.'2></td><td><'.$tptfields[21]->FieldName.'3>'.$bank_pincode.'</'.$tptfields[21]->FieldName.'3></td></tr>';
			}
	
	
			if($tptfields[22]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[22]->FieldName.'1>'.$tptfields[22]->CpCode.'</'.$tptfields[22]->FieldName.'1></td><td><'.$tptfields[22]->FieldName.'2>'.$tptfields[22]->NewDisplayName.'</'.$tptfields[22]->FieldName.'2></td><td><'.$tptfields[22]->FieldName.'3>'.$account_name.'</'.$tptfields[22]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[23]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[23]->FieldName.'1>'.$tptfields[23]->CpCode.'</'.$tptfields[23]->FieldName.'1></td><td><'.$tptfields[23]->FieldName.'2>'.$tptfields[23]->NewDisplayName.'</'.$tptfields[23]->FieldName.'2></td><td><'.$tptfields[23]->FieldName.'3>'.$account_number.'</'.$tptfields[23]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[24]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[24]->FieldName.'1>'.$tptfields[24]->CpCode.'</'.$tptfields[24]->FieldName.'1></td><td><'.$tptfields[24]->FieldName.'2>'.$tptfields[24]->NewDisplayName.'</'.$tptfields[24]->FieldName.'2></td><td><'.$tptfields[24]->FieldName.'3>'.$currencty_of_payment.'</'.$tptfields[24]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[25]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[25]->FieldName.'1>'.$tptfields[25]->CpCode.'</'.$tptfields[25]->FieldName.'1></td><td><'.$tptfields[25]->FieldName.'2>'.$tptfields[25]->NewDisplayName.'</'.$tptfields[25]->FieldName.'2></td><td><'.$tptfields[25]->FieldName.'3>'.$correspondent_bank1.'</'.$tptfields[25]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[26]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[26]->FieldName.'1>'.$tptfields[26]->CpCode.'</'.$tptfields[26]->FieldName.'1></td><td><'.$tptfields[26]->FieldName.'2>'.$tptfields[26]->NewDisplayName.'</'.$tptfields[26]->FieldName.'2></td><td><'.$tptfields[26]->FieldName.'3>'.$correspondent_bank2.'</'.$tptfields[26]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[27]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[27]->FieldName.'1>'.$tptfields[27]->CpCode.'</'.$tptfields[27]->FieldName.'1></td><td><'.$tptfields[27]->FieldName.'2>'.$tptfields[27]->NewDisplayName.'</'.$tptfields[27]->FieldName.'2></td><td><'.$tptfields[27]->FieldName.'3>'.$bank_code.'</'.$tptfields[27]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[28]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[28]->FieldName.'1>'.$tptfields[28]->CpCode.'</'.$tptfields[28]->FieldName.'1></td><td><'.$tptfields[28]->FieldName.'2>'.$tptfields[28]->NewDisplayName.'</'.$tptfields[28]->FieldName.'2></td><td><'.$tptfields[28]->FieldName.'3>'.$bank_branch_code.'</'.$tptfields[28]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[29]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[29]->FieldName.'1>'.$tptfields[29]->CpCode.'</'.$tptfields[29]->FieldName.'1></td><td><'.$tptfields[29]->FieldName.'2>'.$tptfields[29]->NewDisplayName.'</'.$tptfields[29]->FieldName.'2></td><td><'.$tptfields[29]->FieldName.'3>'.$swift_bic_code.'</'.$tptfields[29]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[30]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[30]->FieldName.'1>'.$tptfields[30]->CpCode.'</'.$tptfields[30]->FieldName.'1></td><td><'.$tptfields[30]->FieldName.'2>'.$tptfields[30]->NewDisplayName.'</'.$tptfields[30]->FieldName.'2></td><td><'.$tptfields[30]->FieldName.'3>'.$ifsc_code.'</'.$tptfields[30]->FieldName.'3></td></tr>';
			}
		
		
			if($tptfields[31]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[31]->FieldName.'1>'.$tptfields[31]->CpCode.'</'.$tptfields[31]->FieldName.'1></td><td><'.$tptfields[31]->FieldName.'2>'.$tptfields[31]->NewDisplayName.'</'.$tptfields[31]->FieldName.'2></td><td><'.$tptfields[31]->FieldName.'3>'.$bank_iban.'</'.$tptfields[31]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[32]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[32]->FieldName.'1>'.$tptfields[32]->CpCode.'</'.$tptfields[32]->FieldName.'1></td><td><'.$tptfields[32]->FieldName.'2>'.$tptfields[32]->NewDisplayName.'</'.$tptfields[32]->FieldName.'2></td><td><'.$tptfields[32]->FieldName.'3>'.$sort_code.'</'.$tptfields[32]->FieldName.'3></td></tr>';
			}
		
			if($tptfields[33]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[33]->FieldName.'1>'.$tptfields[33]->CpCode.'</'.$tptfields[33]->FieldName.'1></td><td><'.$tptfields[33]->FieldName.'2>'.$tptfields[33]->NewDisplayName.'</'.$tptfields[33]->FieldName.'2></td><td><'.$tptfields[33]->FieldName.'3>'.$aba_number.'</'.$tptfields[33]->FieldName.'3></td></tr>';
			}
		
			if($tptfields[34]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[34]->FieldName.'1>'.$tptfields[34]->CpCode.'</'.$tptfields[34]->FieldName.'1></td><td><'.$tptfields[34]->FieldName.'2>'.$tptfields[34]->NewDisplayName.'</'.$tptfields[34]->FieldName.'2></td><td><'.$tptfields[34]->FieldName.'3>'.$bank_detail_applies_to.'</'.$tptfields[34]->FieldName.'3></td></tr>';
			}
		
		$cargo='';
		$CargoQtyMT='';
		$CargoLoadedBasis='';
		$CargoLimitBasis='';
		$CargoLimitBasisFlag1=0;
		$CargoLimitBasisFlag2=0;
		$MaxCargoMT='';
		$MinCargoMT='';
		$ToleranceLimit='';
		$UpperLimit='';
		$LowerLimit='';
		$lpPortName='';
		$LpLaycanStartDate='';
		$LpLaycanEndDate='';
		$LpPreferDate='';
		$ExpectedLpDelayDay='';
		$ldtCode='';
		$LoadingRateMT='';
		$LoadingRateUOM='';
		$LoadingRateUOMFlag=0;
		$LpMaxTime='';
		$LpLaytimeType='';
		$LpCalculationBasedOn='';
		$ftCode='';
		$LpPriorUseTerms='';
		$LpLaytimeBasedOn='';
		$LpCharterType='';
		$cnrCode='';
		$StevedoringTermsLp='';
		$LoadPortEventName='';
		$LoadPortLaytimeCountsOnDemurrage='';
		$LoadPortLaytimeCounts='';
		$LoadPortTimeCounting='';
		$LoadPortCreateNewOrSelectListTendering='';
		$LoadPortNORTenderingPreCondition='';
		$LoadPortTenderingStatus='';
		$LoadPortCreateNewOrSelectListAcceptance='';
		$LoadPortNORAcceptancePreCondition='';
		$LoadPortAcceptanceStatus='';
		$LoadPortOfficeDateFrom='';
		$LoadPortOfficeDateTo='';
		$LoadPortOfficeTimeFrom='';
		$LoadPortOfficeTimeTo='';
		$LoadPortLaytimeDayFrom='';
		$LoadPortLaytimeDayTo='';
		$LoadPortLaytimeTimeFrom='';
		$LoadPortLaytimeTimeTo='';
		$LoadPortLaytimeTurnTime='';
		$LoadPortLaytimeTurnTimeExpire='';
		$LoadPortLaytimeCommenceAt='';
		$LoadPortLaytimeCommenceAtHour='';
		$LoadPortLaytimeSelectDay='';
		$LoadPortLaytimeTimeCountsIfOnDemurrage='';
		$DisPort='';
		$DpArrivalStartDate='';
		$DpArrivalEndDate='';
		$DpPreferDate='';
		$ExpectedDpDelayDay='';
		$DischargingTerms='';
		$DischargingRateMT='';
		$DischargingRateUOM='';
		$DpMaxTime='';
		$DpLaytimeType='';
		$DpCalculationBasedOn='';
		$DpTurnTime='';
		$DpPriorUseTerms='';
		$DpLaytimeBasedOn='';
		$DpCharterType='';
		$DpNorTendering='';
		$DpStevedoringTerms='';
		$DisportEventName='';
		$DisportLaytimeCountsOnDemurrage='';
		$DisportLaytimeCounts='';
		$DisportTimeCounting='';
		$DisportCreateNewOrSelectListTendering='';
		$DisportNORTenderingPreCondition='';
		$DisportTenderingStatus='';
		$DisportCreateNewOrSelectListAcceptance='';
		$DisportNORAcceptancePreCondition='';
		$DisportAcceptanceStatus='';
		$DisportOfficeDateFrom='';
		$DisportOfficeDateTo='';
		$DisportOfficeTimeFrom='';
		$DisportOfficeTimeTo='';
		$DisportLaytimeDayFrom='';
		$DisportLaytimeDayTo='';
		$DisportLaytimeTimeFrom='';
		$DisportLaytimeTimeTo='';
		$DisportLaytimeTurnTime='';
		$DisportLaytimeTurnTimeExpire='';
		$DisportLaytimeCommenceAt='';
		$DisportLaytimeCommenceAtHour='';
		$DisportLaytimeSelectDay='';
		$DisportLaytimeTimeCountsIfOnDemurrage='';
		
		$BrokeragePayingEntityType='';
		$BrokeragePayingEntityName='';
		$BrokerageReceivingEntityType='';
		$BrokerageReceivingEntityName='';
		$BrokerageBrokerName='';
		$BrokeragePayableAs='';
		$BrokeragePercentageOnFreight='';
		$BrokeragePercentageOnDeadFreight='';
		$BrokeragePercentageOnDemmurage='';
		$BrokeragePercentageOnOverage='';
		$BrokerageLumpsumPayable='';
		$BrokerageRatePerTonnePayable='';
		
		$AddCommPayingEntityType='';
		$AddCommPayingEntityName='';
		$AddCommReceivingEntityType='';
		$AddCommReceivingEntityName='';
		$AddCommBrokerName='';
		$AddCommPayableAs='';
		$AddCommPercentageOnFreight='';
		$AddCommPercentageOnDeadFreight='';
		$AddCommPercentageOnDemmurage='';
		$AddCommPercentageOnOverage='';
		$AddCommLumpsumPayable='';
		$AddCommRatePerTonnePayable='';
		
		$OtherPayingEntityType='';
		$OtherPayingEntityName='';
		$OtherReceivingEntityType='';
		$OtherReceivingEntityName='';
		$OtherBrokerName='';
		$OtherPayableAs='';
		$OtherPercentageOnFreight='';
		$OtherPercentageOnDeadFreight='';
		$OtherPercentageOnDemmurage='';
		$OtherPercentageOnOverage='';
		$OtherLumpsumPayable='';
		$OtherRatePerTonnePayable='';
		
		
		if($data3){
			$templinenum='';
		foreach($data3 as $row) {
			if($templinenum==$row->LineNum){
				continue;
			}
			$templinenum=$row->LineNum;
			
			$cargo .=$row->Code.' || ';
			$CargoQtyMT .=number_format($row->CargoQtyMT,2).' || ';
			$CargoLoadedBasis .=$row->CargoLoadedBasis.' || ';
			if($row->CargoLimitBasis==1){
			$CargoLimitBasis1='Max and Min';
			} else if($row->CargoLimitBasis==2){
			$CargoLimitBasis1='% Tolerance limit';	
			}
			$CargoLimitBasis .=$CargoLimitBasis1.' || ';
			
			if($row->CargoLimitBasis==1){
				$CargoLimitBasisFlag1=1;
			} else if($row->CargoLimitBasis==2){
				$CargoLimitBasisFlag2=1;
			}
			$MaxCargoMT .=$row->MaxCargoMT.' || ';
			$MinCargoMT .=$row->MinCargoMT.' || ';
			$ToleranceLimit .=$row->ToleranceLimit.' || ';
			$UpperLimit .=number_format($row->UpperLimit,2).' || ';
			$LowerLimit .=number_format($row->LowerLimit,2).' || ';
			$lpPortName .=$row->lpPortName.' || ';
			$LpLaycanStartDate .=date('d-m-Y H:i:s',strtotime($row->LpLaycanStartDate)).' || ';
			$LpLaycanEndDate .=date('d-m-Y H:i:s',strtotime($row->LpLaycanEndDate)).' || ';
			$LpPreferDate .=date('d-m-Y H:i:s',strtotime($row->LpPreferDate)).' || ';
			$ExpectedLpDelayDay .=$row->ExpectedLpDelayDay.' days '.$row->ExpectedLpDelayHour.' hours'.' || ';
			$ldtCode .=$row->ldtCode.' || ';
			$LoadingRateMT .=number_format($row->LoadingRateMT,2).' || ';
			if($row->LoadingRateUOM==1){
				$LoadingRateUOM1='Per hour';
			}else if($row->LoadingRateUOM==2){
				$LoadingRateUOM1='Per weather working day';
			}else if($row->LoadingRateUOM==3){
				$LoadingRateUOM1='Max time limit';
			}
			$LoadingRateUOM .=$LoadingRateUOM1.' || ';
			
			if($row->LoadingRateUOM==3){
				$LoadingRateUOMFlag=1;
			}
			$LpMaxTime .=$row->LpMaxTime.' || ';
			
			if($row->LpLaytimeType==1){
				$LpLaytimeType1='Reversible';
			}else if($row->LpLaytimeType==2){
				$LpLaytimeType1='Non Reversible';
			}else if($row->LpLaytimeType==3){
				$LpLaytimeType1='Average';
			}
			$LpLaytimeType .=$LpLaytimeType1.' || ';
			if($row->LpCalculationBasedOn==108){
				$LpCalculationBasedOn1='Bill of Loading Quantity';
			}else if($row->LpCalculationBasedOn==109){
				$LpCalculationBasedOn1='Outturn or Discharge Quantity';
			}
			$LpCalculationBasedOn .=$LpCalculationBasedOn1.' || ';
			$ftCode .=$row->ftCode.' || ';
			if($row->LpPriorUseTerms==102){
				$LpPriorUseTerms1='IUATUTC (If Used Actual Time To Count)';
			}else if($row->LpPriorUseTerms==10){
				$LpPriorUseTerms1='IUHTUTC (If Used Half Time To Count)';
			}else{
				$LpPriorUseTerms1='N/A';
			}
			$LpPriorUseTerms .=$LpPriorUseTerms1.' || ';
			
			if($row->LpLaytimeBasedOn==1){
				$LpLaytimeBasedOn1='ATS (All Time Saved)';
			}else if($row->LpLaytimeBasedOn==2){
				$LpLaytimeBasedOn1='WTS (Working Time Saved)';
			}else{
				$LpLaytimeBasedOn1='N/A';
			}
			$LpLaytimeBasedOn .=$LpLaytimeBasedOn1.' || ';
			if($row->LpCharterType==1){
				$LpCharterType1='1 Safe Port 1 Safe Berth (1SP1SB)';
			}else if($row->LpCharterType==2){
				$LpCharterType1='1 Safe Port 2 Safe Berth (1SP2SB)';
			}else if($row->LpCharterType==3){
				$LpCharterType1='2 Safe Port 1 Safe Berth (2SP1SB)';
			}else if($row->LpCharterType==4){
				$LpCharterType1='2 Safe Port 2 Safe Berth (2SP2SB)';
			}
			$LpCharterType .=$LpCharterType1.' || ';
			$cnrCode .=$row->cnrCode.' || ';
			
			$StevedoringTermsLp1=$this->cargo_model->getStevedoringTermsByID($row->LpStevedoringTerms);
			$StevedoringTermsLp .=$StevedoringTermsLp1->Code.' || ';
			
			if($row->ExceptedPeriodFlg==1) {
			$ResponseExceptedPeriods1=$this->cargo_quote_model->ResponseExceptedPeriodsByID($row->ResponseCargoID);
			$evname='';
			$laycountdem='';
			$laycount='';
			$timecount='';
			foreach($ResponseExceptedPeriods1 as $rep) {
				$evname .=$rep->Code.' ('.$rep->Description.' ) | ';
				if($rep->LaytimeCountsOnDemurrageFlg==1) {
					$laycountdem .='Yes | ';
				} else if($rep->LaytimeCountsOnDemurrageFlg==2) {
					$laycountdem .='No | ';
				}
				if($rep->LaytimeCountsFlg==1) {
					$laycount .='Yes | ';
				} else if($rep->LaytimeCountsFlg==2) {
					$laycount .='No | ';
				}
				if($rep->TimeCountingFlg==102) {
					$timecount .='IUATUTC (If Used Actual Time To Count) | ';
				} else if($rep->TimeCountingFlg==10) {
					$timecount .='IUHTUTC (If Used Half Time To Count) | ';
				}
			}
			$evname=rtrim($evname,' | ');
			$laycountdem=rtrim($laycountdem,' | ');
			$laycount=rtrim($laycount,' | ');
			$timecount=rtrim($timecount,' | ');
			}
			$LoadPortEventName .=$evname.' || ';
			$LoadPortLaytimeCountsOnDemurrage .=$laycountdem.' || ';
			$LoadPortLaytimeCounts .=$laycount.' || ';
			$LoadPortTimeCounting .=$timecount.' || ';
			if($row->NORTenderingPreConditionFlg==1) {
				$NORTenderingPreConditions=$this->cargo_quote_model->ResponseNORTenderingPreConditionsByID($row->ResponseCargoID);
				$CreateNoreTend='';
				$ConditionNoreTend='';
				$ActiveNoreTend='';
				foreach($NORTenderingPreConditions as $nrtp) {
					if($nrtp->CreateNewOrSelectListFlg==1) {
						$CreateNoreTend .='create new | ';
						$ConditionNoreTend .=$nrtp->NewNORTenderingPreCondition.' | ';
					} else if($nrtp->CreateNewOrSelectListFlg==2) {
						$CreateNoreTend .='select from pre defined list | ';
						$ConditionNoreTend .=$nrtp->TenderingCode.' | ';
					}
					if($nrtp->StatusFlag==1) {
						$ActiveNoreTend .='Active | ';
					} else if($nrtp->StatusFlag==1) {
						$ActiveNoreTend .='In active | ';
					}
				}
				$CreateNoreTend=rtrim($CreateNoreTend,' | ');
				$ConditionNoreTend=rtrim($ConditionNoreTend,' | ');
				$ActiveNoreTend=rtrim($ActiveNoreTend,' | ');
			}
			
			$LoadPortCreateNewOrSelectListTendering .=$CreateNoreTend.' || ';
			$LoadPortNORTenderingPreCondition .=$ConditionNoreTend.' || ';
			$LoadPortTenderingStatus .=$ActiveNoreTend.' || ';
			
			if($row->NORAcceptancePreConditionFlg==1) {
				$NORAcceptancePreConditions=$this->cargo_quote_model->ResponseNORAcceptancePreConditionsByID($row->ResponseCargoID);
				$CreateNorAccept='';
				$ConditionNorAccept='';
				$ActiveNorAccept='';
				foreach($NORAcceptancePreConditions as $nrapc) {
					if($nrapc->CreateNewOrSelectListFlg==1) {
						$CreateNorAccept .='create new | ';
						$ConditionNorAccept .=$nrapc->NewNORAcceptancePreCondition.' | ';
					} else if($nrapc->CreateNewOrSelectListFlg==2) {
						$CreateNorAccept .='select from pre defined list | ';
						$ConditionNorAccept .=$nrapc->AcceptanceCode.' | ';
					}
					if($nrapc->StatusFlag==1) {
						$ActiveNorAccept .='Active | ';
					} else if($nrapc->StatusFlag==1) {
						$ActiveNorAccept .='In active | ';
					}
				}
				$CreateNorAccept=rtrim($CreateNorAccept,' | ');
				$ConditionNorAccept=rtrim($ConditionNorAccept,' | ');
				$ActiveNorAccept=rtrim($ActiveNorAccept,' | ');
			}
			$LoadPortCreateNewOrSelectListAcceptance .=$CreateNorAccept.' || ';
			$LoadPortNORAcceptancePreCondition .=$ConditionNorAccept.' || ';
			$LoadPortAcceptanceStatus .=$ActiveNorAccept.' || ';
			
			if($row->OfficeHoursFlg ==1) {
				$OfficeHours=$this->cargo_quote_model->ResponseOfficeHoursByID($row->ResponseCargoID);
				$OfficeDateFrom='';
				$OfficeDateTo='';
				$OfficeTimeFrom='';
				$OfficeTimeTo='';
				foreach($OfficeHours as $ofh) {
					$OfficeDateFrom .=$ofh->DateFrom.' | ';
					$OfficeDateTo .=$ofh->DateTo.' | ';
					$OfficeTimeFrom .=$ofh->TimeFrom.' | ';
					$OfficeTimeTo .=$ofh->TimeTo.' | ';
				}
				$OfficeDateFrom=rtrim($OfficeDateFrom,' | ');
				$OfficeDateTo=rtrim($OfficeDateTo,' | ');
				$OfficeTimeFrom=rtrim($OfficeTimeFrom,' | ');
				$OfficeTimeTo=rtrim($OfficeTimeTo,' | ');
			}
			$LoadPortOfficeDateFrom .=$OfficeDateFrom.' || ';
			$LoadPortOfficeDateTo .=$OfficeDateTo.' || ';
			$LoadPortOfficeTimeFrom .=$OfficeTimeFrom.' || ';
			$LoadPortOfficeTimeTo .=$OfficeTimeTo.' || ';
			
			if($row->LaytimeCommencementFlg==1) {
				$LaytimeCommencements=$this->cargo_quote_model->ResponseLaytimeCommencementsByID($row->ResponseCargoID);
				$LaytimeDayFrom='';
				$LaytimeDayTo='';
				$LaytimeTimeFrom='';
				$LaytimeTimeTo='';
				$LaytimeTurnTime='';
				$LaytimeTurnTimeExpire='';
				$LaytimeCommenceAt='';
				$LaytimeCommenceAtHour='';
				$LaytimeSelectDay='';
				$LaytimeTimeCountsIfOnDemurrage='';
				foreach($LaytimeCommencements as $ltcm) {
					$LaytimeDayFrom .=$ltcm->DayFrom.' | ';
					$LaytimeDayTo .=$ltcm->DayTo.' | ';
					$LaytimeTimeFrom .=$ltcm->TimeFrom.' | ';
					$LaytimeTimeTo .=$ltcm->TimeTo.' | ';
					$LaytimeTurnTime .=$ltcm->LaytimeCode.' | ';
					if($ltcm->TurnTimeExpire==1) {
					$LaytimeTurnTimeExpire .='During office hours | ';
					} else if($ltcm->TurnTimeExpire==2) {
					$LaytimeTurnTimeExpire .='After office hours | ';
					} else {
						$LaytimeTurnTimeExpire .=' | ';
					}
					if($ltcm->LaytimeCommenceAt==1) {
						$LaytimeCommenceAt .='At expiry of turn time | ';
					} else if($ltcm->LaytimeCommenceAt==2) {
						$LaytimeCommenceAt .='At specified hour | ';
					} else {
						$LaytimeCommenceAt .=' | ';
					}
					
					$LaytimeCommenceAtHour .=$ltcm->LaytimeCommenceAtHour.' | ';
					if($ltcm->SelectDay==1) {
						$LaytimeSelectDay .='Same Day | ';
					} else if($ltcm->SelectDay==2) {
						$LaytimeSelectDay .='New Working Day | ';
					} else {
						$LaytimeSelectDay .=' | ';
					}
					
					if($ltcm->TimeCountsIfOnDemurrage==1) {
					$LaytimeTimeCountsIfOnDemurrage .='Yes | ';
					} else if($ltcm->TimeCountsIfOnDemurrage==2) {
					$LaytimeTimeCountsIfOnDemurrage .='No | ';	
					} else {
					$LaytimeTimeCountsIfOnDemurrage .=' | ';	
					}
				}
				$LaytimeDayFrom=rtrim($LaytimeDayFrom,' | ');
				$LaytimeDayTo=rtrim($LaytimeDayTo,' | ');
				$LaytimeTimeFrom=rtrim($LaytimeTimeFrom,' | ');
				$LaytimeTimeTo=rtrim($LaytimeTimeTo,' | ');
				$LaytimeTurnTime=rtrim($LaytimeTurnTime,' | ');
				$LaytimeTurnTimeExpire=rtrim($LaytimeTurnTimeExpire,' | ');
				$LaytimeCommenceAt=rtrim($LaytimeCommenceAt,' | ');
				$LaytimeCommenceAtHour=rtrim($LaytimeCommenceAtHour,' | ');
				$LaytimeSelectDay=rtrim($LaytimeSelectDay,' | ');
				$LaytimeTimeCountsIfOnDemurrage=rtrim($LaytimeTimeCountsIfOnDemurrage,' | ');
			}
			$LoadPortLaytimeDayFrom .=$LaytimeDayFrom.' || ';
			$LoadPortLaytimeDayTo .=$LaytimeDayTo.' || ';
			$LoadPortLaytimeTimeFrom .=$LaytimeTimeFrom.' || ';
			$LoadPortLaytimeTimeTo .=$LaytimeTimeTo.' || ';
			$LoadPortLaytimeTurnTime .=$LaytimeTurnTime.' || ';
			$LoadPortLaytimeTurnTimeExpire .=$LaytimeTurnTimeExpire.' || ';
			$LoadPortLaytimeCommenceAt .=$LaytimeCommenceAt.' || ';
			$LoadPortLaytimeCommenceAtHour .=$LaytimeCommenceAtHour.' || ';
			$LoadPortLaytimeSelectDay .=$LaytimeSelectDay.' || ';
			$LoadPortLaytimeTimeCountsIfOnDemurrage .=$LaytimeTimeCountsIfOnDemurrage.' || ';
			
			$diport_data=$this->cargo_quote_model->ResponseCargoDisportsByID($row->ResponseCargoID);
			$disport_coma='';
			$ArrivalStartDate='';
			$ArrivalEndDate='';
			$PreferDate='';
			$ExpectedDelayDay='';
			$DischTerm='';
			$DischRateMt='';
			$DischRateUOM='';
			$MaxTime='';
			$LaytimeType='';
			$CalculationBasedOn='';
			$TurnTime='';
			$PriorUseTerms='';
			$LaytimeBasedOn='';
			$CharterType='';
			$NorTendering='';
			$StevedoringTerms='';
			$evnamedpall='';
			$laycountdemdpall='';
			$laycountdpall='';
			$timecountdpall='';
			$CreateDpNoreTendall='';
			$ConditionDpNoreTendall='';
			$ActiveDpNoreTendall='';
			$CreateDpNorAcceptall='';
			$ConditionDpNorAcceptall='';
			$ActiveDpNorAcceptall='';
			$DpOfficeDateFromall='';
			$DpOfficeDateToall='';
			$DpOfficeTimeFromall='';
			$DpOfficeTimeToall='';
			$DpLaytimeDayFromall='';
			$DpLaytimeDayToall='';
			$DpLaytimeTimeFromall='';
			$DpLaytimeTimeToall='';
			$DpLaytimeTurnTimeall='';
			$DpLaytimeTurnTimeExpireall='';
			$DpLaytimeCommenceAtall='';
			$DpLaytimeCommenceAtHourall='';
			$DpLaytimeSelectDayall='';
			$DpLaytimeTimeCountsIfOnDemurrageall='';
			foreach($diport_data as $disd) {
				$disport_coma .=$disd->dpPortName.' | ';
				$ArrivalStartDate .=date('d-m-Y H:i:s',strtotime($disd->DpArrivalStartDate)).' | ';
				$ArrivalEndDate .=date('d-m-Y H:i:s',strtotime($disd->DpArrivalEndDate)).' | ';
				$PreferDate .=date('d-m-Y H:i:s',strtotime($disd->DpPreferDate)).' | ';
				$ExpectedDelayDay .=$disd->ExpectedDpDelayDay.' days '.$disd->ExpectedDpDelayHour.' hours'.' | ';
				$DischTerm .=$disd->ddtCode.' | ';
				$DischRateMt .=number_format($disd->DischargingRateMT,2).' | ';
				if($disd->DischargingRateUOM==1) {
					$DischRateUOM .='Per hour | ';
				} else if($disd->DischargingRateUOM==2) {
					$DischRateUOM .='Per weather working day | ';
				} else if($disd->DischargingRateUOM==3) {
					$DischRateUOM .='Max time limit | ';
				} else {
					$DischRateUOM .=' | ';
				}
				$MaxTime .=$disd->DpMaxTime.' | ';
				if($disd->DpLaytimeType==1) {
					$LaytimeType .='Reversible | ';
				} else if($disd->DpLaytimeType==2) {
					$LaytimeType .='Non Reversible | ';
				} else if($disd->DpLaytimeType==3) {
					$LaytimeType .='Average | ';
				} else {
					$LaytimeType .=' | ';
				}
				if($disd->DpCalculationBasedOn==108) {
				$CalculationBasedOn .='Bill of Loading Quantity | ';	
				} else if($disd->DpCalculationBasedOn==109) {
				$CalculationBasedOn .='Outturn or Discharge Quantity | ';	
				} else {
					$CalculationBasedOn .=' | ';
				}
				$TurnTime .=$disd->dftCode.' | ';
				
				if($disd->DpPriorUseTerms=102) {
					$PriorUseTerms .='IUATUTC (If Used Actual Time To Count) | ';
				} else if($disd->DpPriorUseTerms=10) {
					$PriorUseTerms .='IUHTUTC (If Used Half Time To Count) | ';
				} else {
					$PriorUseTerms .=' | ';
				}
				if($disd->DpLaytimeBasedOn==1) {
				$LaytimeBasedOn .='ATS (All Time Saved) | ';
				} else if($disd->DpLaytimeBasedOn==2) {
				$LaytimeBasedOn .='ATS (All Time Saved) | ';
				} else {
				$LaytimeBasedOn .=' | ';
				}
				if($disd->DpCharterType==1) {
				$CharterType .='1 Safe Port 1 Safe Berth (1SP1SB) | ';
				} else if($disd->DpCharterType==2) {
				$CharterType .='1 Safe Port 2 Safe Berth (1SP2SB) | ';
				} else if($disd->DpCharterType==3) {
				$CharterType .='2 Safe Port 1 Safe Berth (2SP1SB) | ';
				} else if($disd->DpCharterType==4) {
				$CharterType .='2 Safe Port 2 Safe Berth (2SP2SB) | ';
				} else {
				$CharterType .=' | ';
				}
				$NorTendering .=$disd->cnrDCode.' | ';
				
				$StevedoringTermsDp1=$this->cargo_model->getStevedoringTermsByID($disd->DpStevedoringTerms);
				$StevedoringTerms .=$StevedoringTermsDp1->Code.' | ';
			
			if($disd->DpExceptedPeriodFlg==1) {
			$ResponseExceptedPeriodsDp=$this->cargo_quote_model->ResponseExceptedPeriodsDpByID($disd->ResponseCargoID,$disd->RCD_ID);
			$evnamedp='';
			$laycountdemdp='';
			$laycountdp='';
			$timecountdp='';
			foreach($ResponseExceptedPeriodsDp as $repdp) {
				$evnamedp .=$repdp->Code.' , ';
				if($repdp->LaytimeCountsOnDemurrageFlg==1) {
					$laycountdemdp .='Yes , ';
				} else if($repdp->LaytimeCountsOnDemurrageFlg==2) {
					$laycountdemdp .='No , ';
				} else {
					$laycountdemdp .=' , ';
				}
				if($repdp->LaytimeCountsFlg==1) {
					$laycountdp .='Yes , ';
				} else if($repdp->LaytimeCountsFlg==2) {
					$laycountdp .='No , ';
				} else {
					$laycountdp .=' , ';
				}
				if($repdp->TimeCountingFlg==102) {
					$timecountdp .='IUATUTC , ';
				} else if($repdp->TimeCountingFlg==10) {
					$timecountdp .='IUHTUTC , ';
				} else {
					$timecountdp .=' , ';
				}
			}
			
			$evnamedp=rtrim($evnamedp,' , ');
			$laycountdemdp=rtrim($laycountdemdp,' , ');
			$laycountdp=rtrim($laycountdp,' , ');
			$timecountdp=rtrim($timecountdp,' , ');
			$evnamedpall .='( '.$evnamedp.' ) | ';
			$laycountdemdpall .='( '.$laycountdemdp.' ) | ';
			$laycountdpall .='( '.$laycountdp.' ) | ';
			$timecountdpall .='( '.$timecountdp.' ) | ';
			}
			
			if($disd->DpNORTenderingPreConditionFlg==1) {
				$DpNORTenderingPreConditions=$this->cargo_quote_model->ResponseDpNORTenderingPreConditionsByID($disd->ResponseCargoID,$disd->RCD_ID);
				$CreateDpNoreTend='';
				$ConditionDpNoreTend='';
				$ActiveDpNoreTend='';
				foreach($DpNORTenderingPreConditions as $dnrtp) {
					 if($dnrtp->CreateNewOrSelectListFlg==1) {
						$CreateDpNoreTend .='create new , ';
						$ConditionDpNoreTend .=$dnrtp->NewNORTenderingPreCondition.' , ';
					} else if($dnrtp->CreateNewOrSelectListFlg==2) {
						$CreateDpNoreTend .='select from pre defined list , ';
						$ConditionDpNoreTend .=$dnrtp->TenderingCode.' , ';
					} else {
						$CreateDpNoreTend .=' , ';
						$ConditionDpNoreTend .=' , ';
					}
					if($dnrtp->StatusFlag==1) {
						$ActiveDpNoreTend .='Active , ';
					} else if($dnrtp->StatusFlag==1) {
						$ActiveDpNoreTend .='In active , ';
					} else {
						$ActiveDpNoreTend .=' , ';
					}
				}
				$CreateDpNoreTend=rtrim($CreateDpNoreTend,' , ');
				$ConditionDpNoreTend=rtrim($ConditionDpNoreTend,' , ');
				$ActiveDpNoreTend=rtrim($ActiveDpNoreTend,' , ');
				$CreateDpNoreTendall .='( '.$CreateDpNoreTend.' ) | ';
				$ConditionDpNoreTendall .='( '.$ConditionDpNoreTend.' ) | ';
				$ActiveDpNoreTendall .='( '.$ActiveDpNoreTend.' ) | ';
			}
			
			if($disd->DpNORAcceptancePreConditionFlg==1) {
				$DpNORAcceptancePreConditions=$this->cargo_quote_model->ResponseDpNORAcceptancePreConditionsByID($disd->ResponseCargoID,$disd->RCD_ID);
				
				$CreateDpNorAccept='';
				$ConditionDpNorAccept='';
				$ActiveDpNorAccept='';
				foreach($DpNORAcceptancePreConditions as $dpnrapc) {
					if($dpnrapc->CreateNewOrSelectListFlg==1) {
						$CreateDpNorAccept .='create new , ';
						$ConditionDpNorAccept .=$dpnrapc->NewNORAcceptancePreCondition.' , ';
					} else if($dpnrapc->CreateNewOrSelectListFlg==2) {
						$CreateDpNorAccept .='select from pre defined list , ';
						$ConditionDpNorAccept .=$dpnrapc->AcceptanceCode.' , ';
					} else {
						$CreateDpNorAccept .=' , ';
						$ConditionDpNorAccept .=' , ';
					}
					if($dpnrapc->StatusFlag==1) {
						$ActiveDpNorAccept .='Active , ';
					} else if($dpnrapc->StatusFlag==1) {
						$ActiveDpNorAccept .='In active , ';
					} else {
						$ActiveDpNorAccept .=' , ';
					}
				}
				$CreateDpNorAccept=rtrim($CreateDpNorAccept,' , ');
				$ConditionDpNorAccept=rtrim($ConditionDpNorAccept,' , ');
				$ActiveDpNorAccept=rtrim($ActiveDpNorAccept,' , ');
				$CreateDpNorAcceptall .='( '.$CreateDpNorAccept.' ) | ';
				$ConditionDpNorAcceptall .='( '.$ConditionDpNorAccept.' ) | ';
				$ActiveDpNorAcceptall .='( '.$ActiveDpNorAccept.' ) | ';
			}
			
			if($disd->DpOfficeHoursFlg==1) {
				$DpOfficeHours=$this->cargo_quote_model->ResponseDpOfficeHoursByID($disd->ResponseCargoID,$disd->RCD_ID);
				$DpOfficeDateFrom='';
				$DpOfficeDateTo='';
				$DpOfficeTimeFrom='';
				$DpOfficeTimeTo='';
				foreach($DpOfficeHours as $dpofh) {
					$DpOfficeDateFrom .=$dpofh->DateFrom.' , ';
					$DpOfficeDateTo .=$dpofh->DateTo.' , ';
					$DpOfficeTimeFrom .=$dpofh->TimeFrom.' , ';
					$DpOfficeTimeTo .=$dpofh->TimeTo.' , ';
				}
				$DpOfficeDateFrom=rtrim($DpOfficeDateFrom,' , ');
				$DpOfficeDateTo=rtrim($DpOfficeDateTo,' , ');
				$DpOfficeTimeFrom=rtrim($DpOfficeTimeFrom,' , ');
				$DpOfficeTimeTo=rtrim($DpOfficeTimeTo,' , ');
				$DpOfficeDateFromall .='( '.$DpOfficeDateFrom.' ) | ';
				$DpOfficeDateToall .='( '.$DpOfficeDateTo.' ) | ';
				$DpOfficeTimeFromall .='( '.$DpOfficeTimeFrom.' ) | ';
				$DpOfficeTimeToall .='( '.$DpOfficeTimeTo.' ) | ';
			}
			
			if($disd->DpLaytimeCommencementFlg==1) {
				$DpLaytimeCommencements=$this->cargo_quote_model->ResponseDpLaytimeCommencementsByID($disd->ResponseCargoID,$disd->RCD_ID);
				$DpLaytimeDayFrom='';
				$DpLaytimeDayTo='';
				$DpLaytimeTimeFrom='';
				$DpLaytimeTimeTo='';
				$DpLaytimeTurnTime='';
				$DpLaytimeTurnTimeExpire='';
				$DpLaytimeCommenceAt='';
				$DpLaytimeCommenceAtHour='';
				$DpLaytimeSelectDay='';
				$DpLaytimeTimeCountsIfOnDemurrage='';
				foreach($DpLaytimeCommencements as $dpltcm) {
					$DpLaytimeDayFrom .=$dpltcm->DayFrom.' , ';
					$DpLaytimeDayTo .=$dpltcm->DayTo.' , ';
					$DpLaytimeTimeFrom .=$dpltcm->TimeFrom.' , ';
					$DpLaytimeTimeTo .=$dpltcm->TimeTo.' , ';
					$DpLaytimeTurnTime .=$dpltcm->LaytimeCode.' , ';
					if($dpltcm->TurnTimeExpire==1) {
					$DpLaytimeTurnTimeExpire .='During office hours , ';
					} else if($dpltcm->TurnTimeExpire==2) {
					$DpLaytimeTurnTimeExpire .='After office hours , ';
					} else {
					$DpLaytimeTurnTimeExpire .=' , ';	
					}
					if($dpltcm->LaytimeCommenceAt==1) {
						$DpLaytimeCommenceAt .='At expiry of turn time , ';
					} else if($dpltcm->LaytimeCommenceAt==2) {
						$DpLaytimeCommenceAt .='At specified hour , ';
					} else {
						$DpLaytimeCommenceAt .=' , ';
					}
					
					$DpLaytimeCommenceAtHour .=$dpltcm->LaytimeCommenceAtHour.' , ';
					if($dpltcm->SelectDay==1) {
						$DpLaytimeSelectDay .='Same Day , ';
					} else if($dpltcm->SelectDay==2) {
						$DpLaytimeSelectDay .='New Working Day , ';
					} else {
						$DpLaytimeSelectDay .=' , ';
					}
					
					if($dpltcm->TimeCountsIfOnDemurrage==1) {
					$DpLaytimeTimeCountsIfOnDemurrage .='Yes , ';
					} else if($dpltcm->TimeCountsIfOnDemurrage==2) {
					$DpLaytimeTimeCountsIfOnDemurrage .='No , ';	
					} else {
					$DpLaytimeTimeCountsIfOnDemurrage .=' , ';		
					}
				}
				$DpLaytimeDayFrom=rtrim($DpLaytimeDayFrom,' , ');
				$DpLaytimeDayTo=rtrim($DpLaytimeDayTo,' , ');
				$DpLaytimeTimeFrom=rtrim($DpLaytimeTimeFrom,' , ');
				$DpLaytimeTimeTo=rtrim($DpLaytimeTimeTo,' , ');
				$DpLaytimeTurnTime=rtrim($DpLaytimeTurnTime,' , ');
				$DpLaytimeTurnTimeExpire=rtrim($DpLaytimeTurnTimeExpire,' , ');
				$DpLaytimeCommenceAt=rtrim($DpLaytimeCommenceAt,' , ');
				$DpLaytimeCommenceAtHour=rtrim($DpLaytimeCommenceAtHour,' , ');
				$DpLaytimeSelectDay=rtrim($DpLaytimeSelectDay,' , ');
				$DpLaytimeTimeCountsIfOnDemurrage=rtrim($DpLaytimeTimeCountsIfOnDemurrage,' , ');
				$DpLaytimeDayFromall .='( '.$DpLaytimeDayFrom.' ) | ';
				$DpLaytimeDayToall .='( '.$DpLaytimeDayTo.' ) | ';
				$DpLaytimeTimeFromall .='( '.$DpLaytimeTimeFrom.' ) | ';
				$DpLaytimeTimeToall .='( '.$DpLaytimeTimeTo.' ) | ';
				$DpLaytimeTurnTimeall .='( '.$DpLaytimeTurnTime.' ) | ';
				$DpLaytimeTurnTimeExpireall .='( '.$DpLaytimeTurnTimeExpire.' ) | ';
				$DpLaytimeCommenceAtall .='( '.$DpLaytimeCommenceAt.' ) | ';
				$DpLaytimeCommenceAtHourall .='( '.$DpLaytimeCommenceAtHour.' ) | ';
				$DpLaytimeSelectDayall .='( '.$DpLaytimeSelectDay.' ) | ';
				$DpLaytimeTimeCountsIfOnDemurrageall .='( '.$DpLaytimeTimeCountsIfOnDemurrage.' ) | ';
			}
			
			}
			
			$disport_coma=trim($disport_coma,' | ');
			$ArrivalStartDate=trim($ArrivalStartDate,' | ');
			$ArrivalEndDate=trim($ArrivalEndDate,' | ');
			$PreferDate=trim($PreferDate,' | ');
			$ExpectedDelayDay=trim($ExpectedDelayDay,' | ');
			$DischTerm=trim($DischTerm,' | ');
			$DischRateMt=trim($DischRateMt,' | ');
			$DischRateUOM=trim($DischRateUOM,' | ');
			$MaxTime=trim($MaxTime,' | ');
			$LaytimeType=trim($LaytimeType,' | ');
			$CalculationBasedOn=trim($CalculationBasedOn,' | ');
			$TurnTime=trim($TurnTime,' | ');
			$PriorUseTerms=trim($PriorUseTerms,' | ');
			$LaytimeBasedOn=trim($LaytimeBasedOn,' | ');
			$CharterType=trim($CharterType,' | ');
			$NorTendering=trim($NorTendering,' | ');
			$StevedoringTerms=trim($StevedoringTerms,' | ');
			$evnamedpall=trim($evnamedpall,' | ');
			$laycountdemdpall=trim($laycountdemdpall,' | ');
			$laycountdpall=trim($laycountdpall,' | ');
			$timecountdpall=trim($timecountdpall,' | ');
			$CreateDpNoreTendall=trim($CreateDpNoreTendall,' | ');
			$ConditionDpNoreTendall=trim($ConditionDpNoreTendall,' | ');
			$ActiveDpNoreTendall=trim($ActiveDpNoreTendall,' | ');
			$CreateDpNorAcceptall=trim($CreateDpNorAcceptall,' | ');
			$ConditionDpNorAcceptall=trim($ConditionDpNorAcceptall,' | ');
			$ActiveDpNorAcceptall=trim($ActiveDpNorAcceptall,' | ');
			$DpOfficeDateFromall=trim($DpOfficeDateFromall,' | ');
			$DpOfficeDateToall=trim($DpOfficeDateToall,' | ');
			$DpOfficeTimeFromall=trim($DpOfficeTimeFromall,' | ');
			$DpOfficeTimeToall=trim($DpOfficeTimeToall,' | ');
			$DpLaytimeDayFromall=trim($DpLaytimeDayFromall,' | ');
			$DpLaytimeDayToall=trim($DpLaytimeDayToall,' | ');
			$DpLaytimeTimeFromall=trim($DpLaytimeTimeFromall,' | ');
			$DpLaytimeTimeToall=trim($DpLaytimeTimeToall,' | ');
			$DpLaytimeTurnTimeall=trim($DpLaytimeTurnTimeall,' | ');
			$DpLaytimeTurnTimeExpireall=trim($DpLaytimeTurnTimeExpireall,' | ');
			$DpLaytimeCommenceAtall=trim($DpLaytimeCommenceAtall,' | ');
			$DpLaytimeCommenceAtHourall=trim($DpLaytimeCommenceAtHourall,' | ');
			$DpLaytimeSelectDayall=trim($DpLaytimeSelectDayall,' | ');
			$DpLaytimeTimeCountsIfOnDemurrageall=trim($DpLaytimeTimeCountsIfOnDemurrageall,' | ');
			
			$DisPort .=$disport_coma.' || ';
			$DpArrivalStartDate .=$ArrivalStartDate.' || ';
			$DpArrivalEndDate .=$ArrivalEndDate.' || ';
			$DpPreferDate .=$PreferDate.' || ';
			$ExpectedDpDelayDay .=$ExpectedDelayDay.' || ';
			$DischargingTerms .=$DischTerm.' || ';
			$DischargingRateMT .=$DischRateMt.' || ';
			$DischargingRateUOM .=$DischRateUOM.' || ';
			$DpMaxTime .=$MaxTime.' || ';
			$DpLaytimeType .=$LaytimeType.' || ';
			$DpCalculationBasedOn .=$CalculationBasedOn.' || ';
			$DpTurnTime .=$TurnTime.' || ';
			$DpPriorUseTerms .=$PriorUseTerms.' || ';
			$DpLaytimeBasedOn .=$LaytimeBasedOn.' || ';
			$DpCharterType .=$CharterType.' || ';
			$DpNorTendering .=$NorTendering.' || ';
			$DpStevedoringTerms .=$StevedoringTerms.' || ';
			$DisportEventName .=$evnamedpall.' || ';
			$DisportLaytimeCountsOnDemurrage .=$laycountdemdpall.' || ';
			$DisportLaytimeCounts .=$laycountdpall.' || ';
			$DisportTimeCounting .=$timecountdpall.' || ';
			$DisportCreateNewOrSelectListTendering .=$CreateDpNoreTendall.' || ';
			$DisportNORTenderingPreCondition .=$ConditionDpNoreTendall.' || ';
			$DisportTenderingStatus .=$ActiveDpNoreTendall.' || ';
			$DisportCreateNewOrSelectListAcceptance .=$CreateDpNorAcceptall.' || ';
			$DisportNORAcceptancePreCondition .=$ConditionDpNorAcceptall.' || ';
			$DisportAcceptanceStatus .=$ActiveDpNorAcceptall.' || ';
			$DisportOfficeDateFrom .=$DpOfficeDateFromall.' || ';
			$DisportOfficeDateTo .=$DpOfficeDateToall.' || ';
			$DisportOfficeTimeFrom .=$DpOfficeTimeFromall.' || ';
			$DisportOfficeTimeTo .=$DpOfficeTimeToall.' || ';
			
			$DisportLaytimeDayFrom .=$DpLaytimeDayFromall.' || ';
			$DisportLaytimeDayTo .=$DpLaytimeDayToall.' || ';
			$DisportLaytimeTimeFrom .=$DpLaytimeTimeFromall.' || ';
			$DisportLaytimeTimeTo .=$DpLaytimeTimeToall.' || ';
			$DisportLaytimeTurnTime .=$DpLaytimeTurnTimeall.' || ';
			$DisportLaytimeTurnTimeExpire .=$DpLaytimeTurnTimeExpireall.' || ';
			$DisportLaytimeCommenceAt .=$DpLaytimeCommenceAtall.' || ';
			$DisportLaytimeCommenceAtHour .=$DpLaytimeCommenceAtHourall.' || ';
			$DisportLaytimeSelectDay .=$DpLaytimeSelectDayall.' || ';
			$DisportLaytimeTimeCountsIfOnDemurrage .=$DpLaytimeTimeCountsIfOnDemurrageall.' || ';
			
			
		$Bacdata=$this->cargo_quote_model->get_bac_by_responsecargoID($row->ResponseCargoID);
		$Other1PayingEntityType='';
		$Other1PayingEntityName='';
		$Other1ReceivingEntityType='';
		$Other1ReceivingEntityName='';
		$Other1BrokerName='';
		$Other1PayableAs='';
		$Other1PercentageOnFreight='';
		$Other1PercentageOnDeadFreight='';
		$Other1PercentageOnDemmurage='';
		$Other1PercentageOnOverage='';
		$Other1LumpsumPayable='';
		$Other1RatePerTonnePayable='';
		foreach($Bacdata as $bac) {
			if($bac->TransactionType=='Brokerage'){
				$BrokeragePayingEntityType .=$bac->PayingEntityType.' || ';
				$BrokeragePayingEntityName .=$bac->PayingEntityName.' || ';
				$BrokerageReceivingEntityType .=$bac->ReceivingEntityType.' || ';
				$BrokerageReceivingEntityName .=$bac->ReceivingEntityName.' || ';
				$BrokerageBrokerName .=$bac->BrokerName.' || ';
				$BrokeragePayableAs .=$bac->PayableAs.' || ';
				$BrokeragePercentageOnFreight .=$bac->PercentageOnFreight.' || ';
				$BrokeragePercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' || ';
				$BrokeragePercentageOnDemmurage .=$bac->PercentageOnDemmurage.' || ';
				$BrokeragePercentageOnOverage .=$bac->PercentageOnOverage.' || ';
				$BrokerageLumpsumPayable .=$bac->LumpsumPayable.' || ';
				$BrokerageRatePerTonnePayable .=$bac->RatePerTonnePayable.' || ';
			} else if($bac->TransactionType=='Commision'){
				$AddCommPayingEntityType .=$bac->PayingEntityType.' || ';
				$AddCommPayingEntityName .=$bac->PayingEntityName.' || ';
				$AddCommReceivingEntityType .=$bac->ReceivingEntityType.' || ';
				$AddCommReceivingEntityName .=$bac->ReceivingEntityName.' || ';
				$AddCommBrokerName .=$bac->BrokerName.' || ';
				$AddCommPayableAs .=$bac->PayableAs.' || ';
				$AddCommPercentageOnFreight .=$bac->PercentageOnFreight.' || ';
				$AddCommPercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' || ';
				$AddCommPercentageOnDemmurage .=$bac->PercentageOnDemmurage.' || ';
				$AddCommPercentageOnOverage .=$bac->PercentageOnOverage.' || ';
				$AddCommLumpsumPayable .=$bac->LumpsumPayable.' || ';
				$AddCommRatePerTonnePayable .=$bac->RatePerTonnePayable.' || ';
			} else if($bac->TransactionType=='Others') {
				$Other1PayingEntityType .=$bac->PayingEntityType.' , ';
				$Other1PayingEntityName .=$bac->PayingEntityName.' , ';
				$Other1ReceivingEntityType .=$bac->ReceivingEntityType.' , ';
				$Other1ReceivingEntityName .=$bac->ReceivingEntityName.' , ';
				$Other1BrokerName .=$bac->BrokerName.' , ';
				$Other1PayableAs .=$bac->PayableAs.' , ';
				$Other1PercentageOnFreight .=$bac->PercentageOnFreight.' , ';
				$Other1PercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' , ';
				$Other1PercentageOnDemmurage .=$bac->PercentageOnDemmurage.' , ';
				$Other1PercentageOnOverage .=$bac->PercentageOnOverage.' , ';
				$Other1LumpsumPayable .=$bac->LumpsumPayable.' , ';
				$Other1RatePerTonnePayable .=$bac->RatePerTonnePayable.' , ';
			}
		}
		$Other1PayingEntityType=trim($Other1PayingEntityType,' , ');	
		$Other1PayingEntityName=trim($Other1PayingEntityName,' , ');	
		$Other1ReceivingEntityType=trim($Other1ReceivingEntityType,' , ');	
		$Other1ReceivingEntityName=trim($Other1ReceivingEntityName,' , ');	
		$Other1BrokerName=trim($Other1BrokerName,' , ');	
		$Other1PayableAs=trim($Other1PayableAs,' , ');	
		$Other1PercentageOnFreight=trim($Other1PercentageOnFreight,' , ');	
		$Other1PercentageOnDeadFreight=trim($Other1PercentageOnDeadFreight,' , ');	
		$Other1PercentageOnDemmurage=trim($Other1PercentageOnDemmurage,' , ');	
		$Other1PercentageOnOverage=trim($Other1PercentageOnOverage,' , ');	
		$Other1LumpsumPayable=trim($Other1LumpsumPayable,' , ');	
		$Other1RatePerTonnePayable=trim($Other1RatePerTonnePayable,' , ');

		$OtherPayingEntityType .=$Other1PayingEntityType.' || ';
		$OtherPayingEntityName .=$Other1PayingEntityName.' || ';
		$OtherReceivingEntityType .=$Other1ReceivingEntityType.' || ';
		$OtherReceivingEntityName .=$Other1ReceivingEntityName.' || ';
		$OtherBrokerName .=$Other1BrokerName.' || ';
		$OtherPayableAs .=$Other1PayableAs.' || ';
		$OtherPercentageOnFreight .=$Other1PercentageOnFreight.' || ';
		$OtherPercentageOnDeadFreight .=$Other1PercentageOnDeadFreight.' || ';
		$OtherPercentageOnDemmurage .=$Other1PercentageOnDemmurage.' || ';
		$OtherPercentageOnOverage .=$Other1PercentageOnOverage.' || ';
		$OtherLumpsumPayable .=$Other1LumpsumPayable.' || ';
		$OtherRatePerTonnePayable .=$Other1RatePerTonnePayable.' || ';
		}
		}
		
		$cargo=rtrim($cargo,' || ');
		$CargoQtyMT=rtrim($CargoQtyMT,' || ');
		$CargoLoadedBasis=rtrim($CargoLoadedBasis,' || ');
		$CargoLimitBasis=rtrim($CargoLimitBasis,' || ');
		$MaxCargoMT=rtrim($MaxCargoMT,' || ');
		$MinCargoMT=rtrim($MinCargoMT,' || ');
		$ToleranceLimit=rtrim($ToleranceLimit,' || ');
		$UpperLimit=rtrim($UpperLimit,' || ');
		$LowerLimit=rtrim($LowerLimit,' || ');
		$lpPortName=rtrim($lpPortName,' || ');
		$LpLaycanStartDate=rtrim($LpLaycanStartDate,' || ');
		$LpLaycanEndDate=rtrim($LpLaycanEndDate,' || ');
		$LpPreferDate=rtrim($LpPreferDate,' || ');
		$ExpectedLpDelayDay=rtrim($ExpectedLpDelayDay,' || ');
		$ldtCode=rtrim($ldtCode,' || ');
		$LoadingRateMT=rtrim($LoadingRateMT,' || ');
		$LoadingRateUOM=rtrim($LoadingRateUOM,' || ');
		$LpMaxTime=rtrim($LpMaxTime,' || ');
		$LpLaytimeType=rtrim($LpLaytimeType,' || ');
		$LpCalculationBasedOn=rtrim($LpCalculationBasedOn,' || ');
		$ftCode=rtrim($ftCode,' || ');
		$LpPriorUseTerms=rtrim($LpPriorUseTerms,' || ');
		$LpLaytimeBasedOn=rtrim($LpLaytimeBasedOn,' || ');
		$LpCharterType=rtrim($LpCharterType,' || ');
		$cnrCode=rtrim($cnrCode,' || ');
		$StevedoringTermsLp=rtrim($StevedoringTermsLp,' || ');
		$LoadPortEventName=rtrim($LoadPortEventName,' || ');
		$LoadPortLaytimeCountsOnDemurrage=rtrim($LoadPortLaytimeCountsOnDemurrage,' || ');
		$LoadPortLaytimeCounts=rtrim($LoadPortLaytimeCounts,' || ');
		$LoadPortTimeCounting=rtrim($LoadPortTimeCounting,' || ');
		$LoadPortCreateNewOrSelectListTendering=rtrim($LoadPortCreateNewOrSelectListTendering,' || ');
		$LoadPortNORTenderingPreCondition=rtrim($LoadPortNORTenderingPreCondition,' || ');
		$LoadPortTenderingStatus=rtrim($LoadPortTenderingStatus,' || ');
		$LoadPortCreateNewOrSelectListAcceptance=rtrim($LoadPortCreateNewOrSelectListAcceptance,' || ');
		$LoadPortNORAcceptancePreCondition=rtrim($LoadPortNORAcceptancePreCondition,' || ');
		$LoadPortAcceptanceStatus=rtrim($LoadPortAcceptanceStatus,' || ');
		$LoadPortOfficeDateFrom=rtrim($LoadPortOfficeDateFrom,' || ');
		$LoadPortOfficeDateTo=rtrim($LoadPortOfficeDateTo,' || ');
		$LoadPortOfficeTimeFrom=rtrim($LoadPortOfficeTimeFrom,' || ');
		$LoadPortOfficeTimeTo=rtrim($LoadPortOfficeTimeTo,' || ');
		
		$LoadPortLaytimeDayFrom=rtrim($LoadPortLaytimeDayFrom,' || ');
		$LoadPortLaytimeDayTo=rtrim($LoadPortLaytimeDayTo,' || ');
		$LoadPortLaytimeTimeFrom=rtrim($LoadPortLaytimeTimeFrom,' || ');
		$LoadPortLaytimeTimeTo=rtrim($LoadPortLaytimeTimeTo,' || ');
		$LoadPortLaytimeTurnTime=rtrim($LoadPortLaytimeTurnTime,' || ');
		$LoadPortLaytimeTurnTimeExpire=rtrim($LoadPortLaytimeTurnTimeExpire,' || ');
		$LoadPortLaytimeCommenceAt=rtrim($LoadPortLaytimeCommenceAt,' || ');
		$LoadPortLaytimeCommenceAtHour=rtrim($LoadPortLaytimeCommenceAtHour,' || ');
		$LoadPortLaytimeSelectDay=rtrim($LoadPortLaytimeSelectDay,' || ');
		$LoadPortLaytimeTimeCountsIfOnDemurrage=rtrim($LoadPortLaytimeTimeCountsIfOnDemurrage,' || ');
		
		$DisPort=rtrim($DisPort,' || ');
		$DpArrivalStartDate=rtrim($DpArrivalStartDate,' || ');
		$DpArrivalEndDate=rtrim($DpArrivalEndDate,' || ');
		$DpPreferDate=rtrim($DpPreferDate,' || ');
		$ExpectedDpDelayDay=rtrim($ExpectedDpDelayDay,' || ');
		$DischargingTerms=rtrim($DischargingTerms,' || ');
		$DischargingRateMT=rtrim($DischargingRateMT,' || ');
		$DischargingRateUOM=rtrim($DischargingRateUOM,' || ');
		$DpMaxTime=rtrim($DpMaxTime,' || ');
		$DpLaytimeType=rtrim($DpLaytimeType,' || ');
		$DpCalculationBasedOn=rtrim($DpCalculationBasedOn,' || ');
		$DpTurnTime=rtrim($DpTurnTime,' || ');
		$DpPriorUseTerms=rtrim($DpPriorUseTerms,' || ');
		$DpLaytimeBasedOn=rtrim($DpLaytimeBasedOn,' || ');
		$DpCharterType=rtrim($DpCharterType,' || ');
		$DpNorTendering=rtrim($DpNorTendering,' || ');
		$DpStevedoringTerms=rtrim($DpStevedoringTerms,' || ');
		$DisportEventName=rtrim($DisportEventName,' || ');
		$DisportLaytimeCountsOnDemurrage=rtrim($DisportLaytimeCountsOnDemurrage,' || ');
		$DisportLaytimeCounts=rtrim($DisportLaytimeCounts,' || ');
		$DisportTimeCounting=rtrim($DisportTimeCounting,' || ');
		$DisportCreateNewOrSelectListTendering=rtrim($DisportCreateNewOrSelectListTendering,' || ');
		$DisportNORTenderingPreCondition=rtrim($DisportNORTenderingPreCondition,' || ');
		$DisportTenderingStatus=rtrim($DisportTenderingStatus,' || ');
		$DisportCreateNewOrSelectListAcceptance=rtrim($DisportCreateNewOrSelectListAcceptance,' || ');
		$DisportNORAcceptancePreCondition=rtrim($DisportNORAcceptancePreCondition,' || ');
		$DisportAcceptanceStatus=rtrim($DisportAcceptanceStatus,' || ');
		$DisportOfficeDateFrom=rtrim($DisportOfficeDateFrom,' || ');
		$DisportOfficeDateTo=rtrim($DisportOfficeDateTo,' || ');
		$DisportOfficeTimeFrom=rtrim($DisportOfficeTimeFrom,' || ');
		$DisportOfficeTimeTo=rtrim($DisportOfficeTimeTo,' || ');
		
		$DisportLaytimeDayFrom=rtrim($DisportLaytimeDayFrom,' || ');
		$DisportLaytimeDayTo=rtrim($DisportLaytimeDayTo,' || ');
		$DisportLaytimeTimeFrom=rtrim($DisportLaytimeTimeFrom,' || ');
		$DisportLaytimeTimeTo=rtrim($DisportLaytimeTimeTo,' || ');
		$DisportLaytimeTurnTime=rtrim($DisportLaytimeTurnTime,' || ');
		$DisportLaytimeTurnTimeExpire=rtrim($DisportLaytimeTurnTimeExpire,' || ');
		$DisportLaytimeCommenceAt=rtrim($DisportLaytimeCommenceAt,' || ');
		$DisportLaytimeCommenceAtHour=rtrim($DisportLaytimeCommenceAtHour,' || ');
		$DisportLaytimeSelectDay=rtrim($DisportLaytimeSelectDay,' || ');
		$DisportLaytimeTimeCountsIfOnDemurrage=rtrim($DisportLaytimeTimeCountsIfOnDemurrage,' || ');
		
		$BrokeragePayingEntityType=rtrim($BrokeragePayingEntityType,' || ');
		$BrokeragePayingEntityName=rtrim($BrokeragePayingEntityName,' || ');
		$BrokerageReceivingEntityType=rtrim($BrokerageReceivingEntityType,' || ');
		$BrokerageReceivingEntityName=rtrim($BrokerageReceivingEntityName,' || ');
		$BrokerageBrokerName=rtrim($BrokerageBrokerName,' || ');
		$BrokeragePayableAs=rtrim($BrokeragePayableAs,' || ');
		$BrokeragePercentageOnFreight=rtrim($BrokeragePercentageOnFreight,' || ');
		$BrokeragePercentageOnDeadFreight=rtrim($BrokeragePercentageOnDeadFreight,' || ');
		$BrokeragePercentageOnDemmurage=rtrim($BrokeragePercentageOnDemmurage,' || ');
		$BrokeragePercentageOnOverage=rtrim($BrokeragePercentageOnOverage,' || ');
		$BrokerageLumpsumPayable=rtrim($BrokerageLumpsumPayable,' || ');
		$BrokerageRatePerTonnePayable=rtrim($BrokerageRatePerTonnePayable,' || ');
		
		$AddCommPayingEntityType=rtrim($AddCommPayingEntityType,' || ');
		$AddCommPayingEntityName=rtrim($AddCommPayingEntityName,' || ');
		$AddCommReceivingEntityType=rtrim($AddCommReceivingEntityType,' || ');
		$AddCommReceivingEntityName=rtrim($AddCommReceivingEntityName,' || ');
		$AddCommBrokerName=rtrim($AddCommBrokerName,' || ');
		$AddCommPayableAs=rtrim($AddCommPayableAs,' || ');
		$AddCommPercentageOnFreight=rtrim($AddCommPercentageOnFreight,' || ');
		$AddCommPercentageOnDeadFreight=rtrim($AddCommPercentageOnDeadFreight,' || ');
		$AddCommPercentageOnDemmurage=rtrim($AddCommPercentageOnDemmurage,' || ');
		$AddCommPercentageOnOverage=rtrim($AddCommPercentageOnOverage,' || ');
		$AddCommLumpsumPayable=rtrim($AddCommLumpsumPayable,' || ');
		$AddCommRatePerTonnePayable=rtrim($AddCommRatePerTonnePayable,' || ');
		
		$OtherPayingEntityType=rtrim($OtherPayingEntityType,' || ');
		$OtherPayingEntityName=rtrim($OtherPayingEntityName,' || ');
		$OtherReceivingEntityType=rtrim($OtherReceivingEntityType,' || ');
		$OtherReceivingEntityName=rtrim($OtherReceivingEntityName,' || ');
		$OtherBrokerName=rtrim($OtherBrokerName,' || ');
		$OtherPayableAs=rtrim($OtherPayableAs,' || ');
		$OtherPercentageOnFreight=rtrim($OtherPercentageOnFreight,' || ');
		$OtherPercentageOnDeadFreight=rtrim($OtherPercentageOnDeadFreight,' || ');
		$OtherPercentageOnDemmurage=rtrim($OtherPercentageOnDemmurage,' || ');
		$OtherPercentageOnOverage=rtrim($OtherPercentageOnOverage,' || ');
		$OtherLumpsumPayable=rtrim($OtherLumpsumPayable,' || ');
		$OtherRatePerTonnePayable=rtrim($OtherRatePerTonnePayable,' || ');
		
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Cargo</td><td></td></tr>';
		
		if($tptfields[36]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='select_cargo_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				//echo $ef_flag;die;
				if($ef_flag==1) {
					$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[36]->CpCode.'</td><td>'.$tptfields[36]->NewDisplayName.'</td><td>'.$cargo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[36]->FieldName.'1>'.$tptfields[36]->CpCode.'</'.$tptfields[36]->FieldName.'1></td><td><'.$tptfields[36]->FieldName.'2>'.$tptfields[36]->NewDisplayName.'</'.$tptfields[36]->FieldName.'2></td><td><'.$tptfields[36]->FieldName.'3>'.$cargo.'</'.$tptfields[36]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[36]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[36]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[36]->FieldName;	
					$data_arr['FieldValue']=$cargo;		
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
			}
			//$html .='<p ><span >Version : &nbsp;</span>'.$row->CargoVersion.'</p>';
			if($tptfields[37]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_load_in_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[37]->CpCode.'</td><td>'.$tptfields[37]->NewDisplayName.'</td><td>'.$CargoQtyMT.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[37]->FieldName.'1>'.$tptfields[37]->CpCode.'</'.$tptfields[37]->FieldName.'1></td><td><'.$tptfields[37]->FieldName.'2>'.$tptfields[37]->NewDisplayName.'</'.$tptfields[37]->FieldName.'2></td><td><'.$tptfields[37]->FieldName.'3>'.$CargoQtyMT.'</'.$tptfields[37]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[37]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[37]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[37]->FieldName;	
					$data_arr['FieldValue']=$CargoQtyMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			if($tptfields[38]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_loaded_option_basis_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
				$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[38]->CpCode.'</td><td>'.$tptfields[38]->NewDisplayName.'</td><td>'.$CargoLoadedBasis.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[38]->FieldName.'1>'.$tptfields[38]->CpCode.'</'.$tptfields[38]->FieldName.'1></td><td><'.$tptfields[38]->FieldName.'2>'.$tptfields[38]->NewDisplayName.'</'.$tptfields[38]->FieldName.'2></td><td><'.$tptfields[38]->FieldName.'3>'.$CargoLoadedBasis.'</'.$tptfields[38]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[38]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[38]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[38]->FieldName;	
					$data_arr['FieldValue']=$CargoLoadedBasis;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[39]->Included){
				
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_limit_basis_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[39]->CpCode.'</td><td>'.$tptfields[39]->NewDisplayName.'</td><td>'.$CargoLimitBasis.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[39]->FieldName.'1>'.$tptfields[39]->CpCode.'</'.$tptfields[39]->FieldName.'1></td><td><'.$tptfields[39]->FieldName.'2>'.$tptfields[39]->NewDisplayName.'</'.$tptfields[39]->FieldName.'2></td><td><'.$tptfields[39]->FieldName.'3>'.$CargoLimitBasis.'</'.$tptfields[39]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[39]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[39]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[39]->FieldName;	
					$data_arr['FieldValue']=$CargoLimitBasis;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($CargoLimitBasisFlag1==1){
				if($tptfields[43]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[43]->FieldName.'1>'.$tptfields[43]->CpCode.'</'.$tptfields[43]->FieldName.'1></td><td><'.$tptfields[43]->FieldName.'2>'.$tptfields[43]->NewDisplayName.'</'.$tptfields[43]->FieldName.'2></td><td><'.$tptfields[43]->FieldName.'3>'.$MaxCargoMT.'</'.$tptfields[43]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[43]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[43]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[43]->FieldName;	
					$data_arr['FieldValue']=$MaxCargoMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[44]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[44]->FieldName.'1>'.$tptfields[44]->CpCode.'</'.$tptfields[44]->FieldName.'1></td><td><'.$tptfields[44]->FieldName.'2>'.$tptfields[44]->NewDisplayName.'</'.$tptfields[44]->FieldName.'2></td><td><'.$tptfields[44]->FieldName.'3>'.$MinCargoMT.'</'.$tptfields[44]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[44]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[44]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[44]->FieldName;	
					$data_arr['FieldValue']=$MinCargoMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}else if($CargoLimitBasisFlag2==1){
				if($tptfields[40]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='cargo_tolerance_limit_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[40]->CpCode.'</td><td>'.$tptfields[40]->NewDisplayName.' </td><td>'.$ToleranceLimit.'</td></tr>';	
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[40]->FieldName.'1>'.$tptfields[40]->CpCode.'</'.$tptfields[40]->FieldName.'1></td><td><'.$tptfields[40]->FieldName.'2>'.$tptfields[40]->NewDisplayName.'</'.$tptfields[40]->FieldName.'2></td><td><'.$tptfields[40]->FieldName.'3>'.$ToleranceLimit.'</'.$tptfields[40]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[40]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[40]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[40]->FieldName;	
					$data_arr['FieldValue']=$ToleranceLimit;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
				}
				if($tptfields[41]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[41]->FieldName.'1>'.$tptfields[41]->CpCode.'</'.$tptfields[41]->FieldName.'1></td><td><'.$tptfields[41]->FieldName.'2>'.$tptfields[41]->NewDisplayName.'</'.$tptfields[41]->FieldName.'2></td><td><'.$tptfields[41]->FieldName.'3>'.$UpperLimit.'</'.$tptfields[41]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[41]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[41]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[41]->FieldName;	
					$data_arr['FieldValue']=$UpperLimit;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[42]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[42]->FieldName.'1>'.$tptfields[42]->CpCode.'</'.$tptfields[42]->FieldName.'1></td><td><'.$tptfields[42]->FieldName.'2>'.$tptfields[42]->NewDisplayName.'</'.$tptfields[42]->FieldName.'2></td><td><'.$tptfields[42]->FieldName.'3>'.$LowerLimit.'</'.$tptfields[42]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[42]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[42]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[42]->FieldName;	
					$data_arr['FieldValue']=$LowerLimit;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>LoadPort</td><td></td></tr>';
			
			if($tptfields[45]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[45]->CpCode.'</td><td>'.$tptfields[45]->NewDisplayName.'</td><td>'.$lpPortName.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[45]->FieldName.'1>'.$tptfields[45]->CpCode.'</'.$tptfields[45]->FieldName.'1></td><td><'.$tptfields[45]->FieldName.'2>'.$tptfields[45]->NewDisplayName.'</'.$tptfields[45]->FieldName.'2></td><td><'.$tptfields[45]->FieldName.'3>'.$lpPortName.'</'.$tptfields[45]->FieldName.'3></td></tr>';	
				$data_arr['CpCode']=$tptfields[45]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[45]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[45]->FieldName;	
				$data_arr['FieldValue']=$lpPortName;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[46]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_laycan_start_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[46]->CpCode.'</td><td>'.$tptfields[46]->NewDisplayName.'</td><td>'.$LpLaycanStartDate.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[46]->FieldName.'1>'.$tptfields[46]->CpCode.'</'.$tptfields[46]->FieldName.'1></td><td><'.$tptfields[46]->FieldName.'2>'.$tptfields[46]->NewDisplayName.'</'.$tptfields[46]->FieldName.'2></td><td><'.$tptfields[46]->FieldName.'3>'.$LpLaycanStartDate.'</'.$tptfields[46]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[46]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[46]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[46]->FieldName;	
				$data_arr['FieldValue']=$LpLaycanStartDate;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);	
			}
			}
			if($tptfields[47]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='load_port_laycan_finish_date_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false"  style="background-color: #efeaead6"><td>'.$tptfields[47]->CpCode.'</td><td>'.$tptfields[47]->NewDisplayName.'</td><td>'.$LpLaycanEndDate.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[47]->FieldName.'1>'.$tptfields[47]->CpCode.'</'.$tptfields[47]->FieldName.'1></td><td><'.$tptfields[47]->FieldName.'2>'.$tptfields[47]->NewDisplayName.'</'.$tptfields[47]->FieldName.'2></td><td><'.$tptfields[47]->FieldName.'3>'.$LpLaycanEndDate.'</'.$tptfields[47]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[47]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[47]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[47]->FieldName;	
					$data_arr['FieldValue']=$LpLaycanEndDate;		
					$data_arr['GroupNumber']=1;					
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[48]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_prefered_arrival_date_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr  contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[48]->CpCode.'</td><td>'.$tptfields[48]->NewDisplayName.'</td><td>'.$LpPreferDate.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[48]->FieldName.'1>'.$tptfields[48]->CpCode.'</'.$tptfields[48]->FieldName.'1></td><td><'.$tptfields[48]->FieldName.'2>'.$tptfields[48]->NewDisplayName.'</'.$tptfields[48]->FieldName.'2></td><td><'.$tptfields[48]->FieldName.'3>'.$LpPreferDate.'</'.$tptfields[48]->FieldName.'3></td></tr>';	
				$data_arr['CpCode']=$tptfields[48]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[48]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[48]->FieldName;	
				$data_arr['FieldValue']=$LpPreferDate;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[49]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='expected_load_port_delay_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[49]->CpCode.'</td><td>'.$tptfields[49]->NewDisplayName.'</td><td>'.$ExpectedLpDelayDay.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[49]->FieldName.'1>'.$tptfields[49]->CpCode.'</'.$tptfields[49]->FieldName.'1></td><td><'.$tptfields[49]->FieldName.'2>'.$tptfields[49]->NewDisplayName.'</'.$tptfields[49]->FieldName.'2></td><td><'.$tptfields[49]->FieldName.'3>'.$ExpectedLpDelayDay.'</'.$tptfields[49]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[49]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[49]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[49]->FieldName;	
				$data_arr['FieldValue']=$ExpectedLpDelayDay;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);	
			}
			}
			if($tptfields[50]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loadding_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[50]->CpCode.'</td><td>'.$tptfields[50]->NewDisplayName.'</td><td>'.$ldtCode.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[50]->FieldName.'1>'.$tptfields[50]->CpCode.'</'.$tptfields[50]->FieldName.'1></td><td><'.$tptfields[50]->FieldName.'2>'.$tptfields[50]->NewDisplayName.'</'.$tptfields[50]->FieldName.'2></td><td><'.$tptfields[50]->FieldName.'3>'.$ldtCode.'</'.$tptfields[50]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[50]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[50]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[50]->FieldName;	
					$data_arr['FieldValue']=$ldtCode;			
					$data_arr['GroupNumber']=1;		
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[51]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loading_rate_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[51]->CpCode.'</td><td>'.$tptfields[51]->NewDisplayName.'</td><td>'.$LoadingRateMT.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[51]->FieldName.'1>'.$tptfields[51]->CpCode.'</'.$tptfields[51]->FieldName.'1></td><td><'.$tptfields[51]->FieldName.'2>'.$tptfields[51]->NewDisplayName.'</'.$tptfields[51]->FieldName.'2></td><td><'.$tptfields[51]->FieldName.'3>'.$LoadingRateMT.'</'.$tptfields[51]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[51]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[51]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[51]->FieldName;	
					$data_arr['FieldValue']=$LoadingRateMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[52]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loading_rate_uom_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<tr  contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[52]->CpCode.'</td><td>'.$tptfields[52]->NewDisplayName.'</td><td>'.$LoadingRateUOM.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[52]->FieldName.'1>'.$tptfields[52]->CpCode.'</'.$tptfields[52]->FieldName.'1></td><td><'.$tptfields[52]->FieldName.'2>'.$tptfields[52]->NewDisplayName.'</'.$tptfields[52]->FieldName.'2></td><td><'.$tptfields[52]->FieldName.'3>'.$LoadingRateUOM.'</'.$tptfields[52]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[52]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[52]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[52]->FieldName;	
					$data_arr['FieldValue']=$LoadingRateUOM;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($LoadingRateUOMFlag==1){
				if($tptfields[53]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[53]->FieldName.'1>'.$tptfields[53]->CpCode.'</'.$tptfields[53]->FieldName.'1></td><td><'.$tptfields[53]->FieldName.'2>'.$tptfields[53]->NewDisplayName.'</'.$tptfields[53]->FieldName.'2></td><td><'.$tptfields[53]->FieldName.'3>'.$LpMaxTime.'</'.$tptfields[53]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[53]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[53]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[53]->FieldName;	
					$data_arr['FieldValue']=$LpMaxTime;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			if($tptfields[54]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='max_time_to_load_cargo_hrs_editable_fields' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[54]->CpCode.'</td><td>'.$tptfields[54]->NewDisplayName.'</td><td>'.$LpLaytimeType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[54]->FieldName.'1>'.$tptfields[54]->CpCode.'</'.$tptfields[54]->FieldName.'1></td><td><'.$tptfields[54]->FieldName.'2>'.$tptfields[54]->NewDisplayName.'</'.$tptfields[54]->FieldName.'2></td><td><'.$tptfields[54]->FieldName.'3>'.$LpLaytimeType.'</'.$tptfields[54]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[54]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[54]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[54]->FieldName;	
					$data_arr['FieldValue']=$LpLaytimeType;			
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}				
			}
			if($tptfields[55]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_tonnage_calc_based_on_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[55]->CpCode.'</td><td>'.$tptfields[55]->NewDisplayName.'</td><td>'.$LpCalculationBasedOn.'</td></tr>';	
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[55]->FieldName.'1>'.$tptfields[55]->CpCode.'</'.$tptfields[55]->FieldName.'1></td><td><'.$tptfields[55]->FieldName.'2>'.$tptfields[55]->NewDisplayName.'</'.$tptfields[55]->FieldName.'2></td><td><'.$tptfields[55]->FieldName.'3>'.$LpCalculationBasedOn.'</'.$tptfields[55]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[55]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[55]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[55]->FieldName;	
					$data_arr['FieldValue']=$LpCalculationBasedOn;		
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[56]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='turn_free_time_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[56]->CpCode.'</td><td>'.$tptfields[56]->NewDisplayName.'</td><td>'.$ftCode.'</td></tr>';	
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[56]->FieldName.'1>'.$tptfields[56]->CpCode.'</'.$tptfields[56]->FieldName.'1></td><td><'.$tptfields[56]->FieldName.'2>'.$tptfields[56]->NewDisplayName.'</'.$tptfields[56]->FieldName.'2></td><td><'.$tptfields[56]->FieldName.'3>'.$ftCode.'</'.$tptfields[56]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[56]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[56]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[56]->FieldName;	
					$data_arr['FieldValue']=$ftCode;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);		
				}
			}
			
			if($tptfields[57]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='prior_use_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[57]->CpCode.'</td><td>'.$tptfields[57]->NewDisplayName.'</td><td>'.$LpPriorUseTerms.'</td></tr>';	
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[57]->FieldName.'1>'.$tptfields[57]->CpCode.'</'.$tptfields[57]->FieldName.'1></td><td><'.$tptfields[57]->FieldName.'2>'.$tptfields[57]->NewDisplayName.'</'.$tptfields[57]->FieldName.'2></td><td><'.$tptfields[57]->FieldName.'3>'.$LpPriorUseTerms.'</'.$tptfields[57]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[57]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[57]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[57]->FieldName;	
					$data_arr['FieldValue']=$LpPriorUseTerms;		
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[58]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_based_on_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {	
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[58]->CpCode.'</td><td>'.$tptfields[58]->NewDisplayName.'</td><td>'.$LpLaytimeBasedOn.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[58]->FieldName.'1>'.$tptfields[58]->CpCode.'</'.$tptfields[58]->FieldName.'1></td><td><'.$tptfields[58]->FieldName.'2>'.$tptfields[58]->NewDisplayName.'</'.$tptfields[58]->FieldName.'2></td><td><'.$tptfields[58]->FieldName.'3>'.$LpLaytimeBasedOn.'</'.$tptfields[58]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[58]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[58]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[58]->FieldName;	
					$data_arr['FieldValue']=$LpLaytimeBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[59]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='type_of_charter_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[59]->CpCode.'</td><td>'.$tptfields[59]->NewDisplayName.'</td><td>'.$LpCharterType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[59]->FieldName.'1>'.$tptfields[59]->CpCode.'</'.$tptfields[59]->FieldName.'1></td><td><'.$tptfields[59]->FieldName.'2>'.$tptfields[59]->NewDisplayName.'</'.$tptfields[59]->FieldName.'2></td><td><'.$tptfields[59]->FieldName.'3>'.$LpCharterType.'</'.$tptfields[59]->FieldName.'3></td></tr>';	
					$data_arr['CpCode']=$tptfields[59]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[59]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[59]->FieldName;	
					$data_arr['FieldValue']=$LpCharterType;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($tptfields[60]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tender_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[60]->CpCode.'</td><td>'.$tptfields[60]->NewDisplayName.'</td><td>'.$cnrCode.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[60]->FieldName.'1>'.$tptfields[60]->CpCode.'</'.$tptfields[60]->FieldName.'1></td><td><'.$tptfields[60]->FieldName.'2>'.$tptfields[60]->NewDisplayName.'</'.$tptfields[60]->FieldName.'2></td><td><'.$tptfields[60]->FieldName.'3>'.$cnrCode.'</'.$tptfields[60]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[60]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[60]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[60]->FieldName;	
					$data_arr['FieldValue']=$cnrCode;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[61]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='LpStevedoringTerms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[61]->CpCode.'</td><td>'.$tptfields[61]->NewDisplayName.'</td><td>'.$StevedoringTermsLp.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[61]->FieldName.'1>'.$tptfields[61]->CpCode.'</'.$tptfields[61]->FieldName.'1></td><td><'.$tptfields[61]->FieldName.'2>'.$tptfields[61]->NewDisplayName.'</'.$tptfields[61]->FieldName.'2></td><td><'.$tptfields[61]->FieldName.'3>'.$StevedoringTermsLp.'</'.$tptfields[61]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[61]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[61]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[61]->FieldName;	
					$data_arr['FieldValue']=$StevedoringTermsLp;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[63]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[63]->CpCode.'</td><td>'.$tptfields[63]->NewDisplayName.'</td><td>'.$LoadPortEventName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[63]->FieldName.'1>'.$tptfields[63]->CpCode.'</'.$tptfields[63]->FieldName.'1></td><td><'.$tptfields[63]->FieldName.'2>'.$tptfields[63]->NewDisplayName.'</'.$tptfields[63]->FieldName.'2></td><td><'.$tptfields[63]->FieldName.'3>'.$LoadPortEventName.'</'.$tptfields[63]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[64]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[64]->CpCode.'</td><td>'.$tptfields[64]->NewDisplayName.'</td><td>'.$LoadPortLaytimeCountsOnDemurrage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[64]->FieldName.'1>'.$tptfields[64]->CpCode.'</'.$tptfields[64]->FieldName.'1></td><td><'.$tptfields[64]->FieldName.'2>'.$tptfields[64]->NewDisplayName.'</'.$tptfields[64]->FieldName.'2></td><td><'.$tptfields[64]->FieldName.'3>'.$LoadPortLaytimeCountsOnDemurrage.'</'.$tptfields[64]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[65]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[65]->CpCode.'</td><td>'.$tptfields[65]->NewDisplayName.'</td><td>'.$LoadPortLaytimeCounts.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[65]->FieldName.'1>'.$tptfields[65]->CpCode.'</'.$tptfields[65]->FieldName.'1></td><td><'.$tptfields[65]->FieldName.'2>'.$tptfields[65]->NewDisplayName.'</'.$tptfields[65]->FieldName.'2></td><td><'.$tptfields[65]->FieldName.'3>'.$LoadPortLaytimeCounts.'</'.$tptfields[65]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[66]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[66]->CpCode.'</td><td>'.$tptfields[66]->NewDisplayName.'</td><td>'.$LoadPortTimeCounting.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[66]->FieldName.'1>'.$tptfields[66]->CpCode.'</'.$tptfields[66]->FieldName.'1></td><td><'.$tptfields[66]->FieldName.'2>'.$tptfields[66]->NewDisplayName.'</'.$tptfields[66]->FieldName.'2></td><td><'.$tptfields[66]->FieldName.'3>'.$LoadPortTimeCounting.'</'.$tptfields[66]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[68]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[68]->CpCode.'</td><td>'.$tptfields[68]->NewDisplayName.'</td><td>'.$LoadPortCreateNewOrSelectListTendering.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[68]->FieldName.'1>'.$tptfields[68]->CpCode.'</'.$tptfields[68]->FieldName.'1></td><td><'.$tptfields[68]->FieldName.'2>'.$tptfields[68]->NewDisplayName.'</'.$tptfields[68]->FieldName.'2></td><td><'.$tptfields[68]->FieldName.'3>'.$LoadPortCreateNewOrSelectListTendering.'</'.$tptfields[68]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[69]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[69]->CpCode.'</td><td>'.$tptfields[69]->NewDisplayName.'</td><td>'.$LoadPortNORTenderingPreCondition.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[69]->FieldName.'1>'.$tptfields[69]->CpCode.'</'.$tptfields[69]->FieldName.'1></td><td><'.$tptfields[69]->FieldName.'2>'.$tptfields[69]->NewDisplayName.'</'.$tptfields[69]->FieldName.'2></td><td><'.$tptfields[69]->FieldName.'3>'.$LoadPortNORTenderingPreCondition.'</'.$tptfields[69]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[70]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[70]->CpCode.'</td><td>'.$tptfields[70]->NewDisplayName.'</td><td>'.$LoadPortTenderingStatus.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[70]->FieldName.'1>'.$tptfields[70]->CpCode.'</'.$tptfields[70]->FieldName.'1></td><td><'.$tptfields[70]->FieldName.'2>'.$tptfields[70]->NewDisplayName.'</'.$tptfields[70]->FieldName.'2></td><td><'.$tptfields[70]->FieldName.'3>'.$LoadPortTenderingStatus.'</'.$tptfields[70]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[72]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[72]->CpCode.'</td><td>'.$tptfields[72]->NewDisplayName.'</td><td>'.$LoadPortCreateNewOrSelectListAcceptance.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[72]->FieldName.'1>'.$tptfields[72]->CpCode.'</'.$tptfields[72]->FieldName.'1></td><td><'.$tptfields[72]->FieldName.'2>'.$tptfields[72]->NewDisplayName.'</'.$tptfields[72]->FieldName.'2></td><td><'.$tptfields[72]->FieldName.'3>'.$LoadPortCreateNewOrSelectListAcceptance.'</'.$tptfields[72]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[73]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[73]->CpCode.'</td><td>'.$tptfields[73]->NewDisplayName.'</td><td>'.$LoadPortNORAcceptancePreCondition.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[73]->FieldName.'1>'.$tptfields[73]->CpCode.'</'.$tptfields[73]->FieldName.'1></td><td><'.$tptfields[73]->FieldName.'2>'.$tptfields[73]->NewDisplayName.'</'.$tptfields[73]->FieldName.'2></td><td><'.$tptfields[73]->FieldName.'3>'.$LoadPortNORAcceptancePreCondition.'</'.$tptfields[73]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[74]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[74]->CpCode.'</td><td>'.$tptfields[74]->NewDisplayName.'</td><td>'.$LoadPortAcceptanceStatus.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[74]->FieldName.'1>'.$tptfields[74]->CpCode.'</'.$tptfields[74]->FieldName.'1></td><td><'.$tptfields[74]->FieldName.'2>'.$tptfields[74]->NewDisplayName.'</'.$tptfields[74]->FieldName.'2></td><td><'.$tptfields[74]->FieldName.'3>'.$LoadPortAcceptanceStatus.'</'.$tptfields[74]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[76]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[76]->CpCode.'</td><td>'.$tptfields[76]->NewDisplayName.'</td><td>'.$LoadPortOfficeDateFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[76]->FieldName.'1>'.$tptfields[76]->CpCode.'</'.$tptfields[76]->FieldName.'1></td><td><'.$tptfields[76]->FieldName.'2>'.$tptfields[76]->NewDisplayName.'</'.$tptfields[76]->FieldName.'2></td><td><'.$tptfields[76]->FieldName.'3>'.$LoadPortOfficeDateFrom.'</'.$tptfields[76]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[77]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[77]->CpCode.'</td><td>'.$tptfields[77]->NewDisplayName.'</td><td>'.$LoadPortOfficeDateTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[77]->FieldName.'1>'.$tptfields[77]->CpCode.'</'.$tptfields[77]->FieldName.'1></td><td><'.$tptfields[77]->FieldName.'2>'.$tptfields[77]->NewDisplayName.'</'.$tptfields[77]->FieldName.'2></td><td><'.$tptfields[77]->FieldName.'3>'.$LoadPortOfficeDateTo.'</'.$tptfields[77]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[78]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[78]->CpCode.'</td><td>'.$tptfields[78]->NewDisplayName.'</td><td>'.$LoadPortOfficeTimeFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[78]->FieldName.'1>'.$tptfields[78]->CpCode.'</'.$tptfields[78]->FieldName.'1></td><td><'.$tptfields[78]->FieldName.'2>'.$tptfields[78]->NewDisplayName.'</'.$tptfields[78]->FieldName.'2></td><td><'.$tptfields[78]->FieldName.'3>'.$LoadPortOfficeTimeFrom.'</'.$tptfields[78]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[79]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[79]->CpCode.'</td><td>'.$tptfields[79]->NewDisplayName.'</td><td>'.$LoadPortOfficeTimeTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[79]->FieldName.'1>'.$tptfields[79]->CpCode.'</'.$tptfields[79]->FieldName.'1></td><td><'.$tptfields[79]->FieldName.'2>'.$tptfields[79]->NewDisplayName.'</'.$tptfields[79]->FieldName.'2></td><td><'.$tptfields[79]->FieldName.'3>'.$LoadPortOfficeTimeTo.'</'.$tptfields[79]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[82]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[82]->CpCode.'</td><td>'.$tptfields[82]->NewDisplayName.'</td><td>'.$LoadPortLaytimeDayFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[82]->FieldName.'1>'.$tptfields[82]->CpCode.'</'.$tptfields[82]->FieldName.'1></td><td><'.$tptfields[82]->FieldName.'2>'.$tptfields[82]->NewDisplayName.'</'.$tptfields[82]->FieldName.'2></td><td><'.$tptfields[82]->FieldName.'3>'.$LoadPortLaytimeDayFrom.'</'.$tptfields[82]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[83]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[83]->CpCode.'</td><td>'.$tptfields[83]->NewDisplayName.'</td><td>'.$LoadPortLaytimeDayTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[83]->FieldName.'1>'.$tptfields[83]->CpCode.'</'.$tptfields[83]->FieldName.'1></td><td><'.$tptfields[83]->FieldName.'2>'.$tptfields[83]->NewDisplayName.'</'.$tptfields[83]->FieldName.'2></td><td><'.$tptfields[83]->FieldName.'3>'.$LoadPortLaytimeDayTo.'</'.$tptfields[83]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[84]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[84]->CpCode.'</td><td>'.$tptfields[84]->NewDisplayName.'</td><td>'.$LoadPortLaytimeTimeFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[84]->FieldName.'1>'.$tptfields[84]->CpCode.'</'.$tptfields[84]->FieldName.'1></td><td><'.$tptfields[84]->FieldName.'2>'.$tptfields[84]->NewDisplayName.'</'.$tptfields[84]->FieldName.'2></td><td><'.$tptfields[84]->FieldName.'3>'.$LoadPortLaytimeTimeFrom.'</'.$tptfields[84]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[85]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[85]->CpCode.'</td><td>'.$tptfields[85]->NewDisplayName.'</td><td>'.$LoadPortLaytimeTimeTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[85]->FieldName.'1>'.$tptfields[85]->CpCode.'</'.$tptfields[85]->FieldName.'1></td><td><'.$tptfields[85]->FieldName.'2>'.$tptfields[85]->NewDisplayName.'</'.$tptfields[85]->FieldName.'2></td><td><'.$tptfields[85]->FieldName.'3>'.$LoadPortLaytimeTimeTo.'</'.$tptfields[85]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[87]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[87]->CpCode.'</td><td>'.$tptfields[87]->NewDisplayName.'</td><td>'.$LoadPortLaytimeTurnTimeExpire.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[87]->FieldName.'1>'.$tptfields[87]->CpCode.'</'.$tptfields[87]->FieldName.'1></td><td><'.$tptfields[87]->FieldName.'2>'.$tptfields[87]->NewDisplayName.'</'.$tptfields[87]->FieldName.'2></td><td><'.$tptfields[87]->FieldName.'3>'.$LoadPortLaytimeTurnTimeExpire.'</'.$tptfields[87]->FieldName.'3></td></tr>';
				}
			}
			
			
			if($tptfields[88]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[88]->CpCode.'</td><td>'.$tptfields[88]->NewDisplayName.'</td><td>'.$LoadPortLaytimeCommenceAt.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[88]->FieldName.'1>'.$tptfields[88]->CpCode.'</'.$tptfields[88]->FieldName.'1></td><td><'.$tptfields[88]->FieldName.'2>'.$tptfields[88]->NewDisplayName.'</'.$tptfields[88]->FieldName.'2></td><td><'.$tptfields[88]->FieldName.'3>'.$LoadPortLaytimeCommenceAt.'</'.$tptfields[88]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[89]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[89]->CpCode.'</td><td>'.$tptfields[89]->NewDisplayName.'</td><td>'.$LoadPortLaytimeCommenceAtHour.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[89]->FieldName.'1>'.$tptfields[89]->CpCode.'</'.$tptfields[89]->FieldName.'1></td><td><'.$tptfields[89]->FieldName.'2>'.$tptfields[89]->NewDisplayName.'</'.$tptfields[89]->FieldName.'2></td><td><'.$tptfields[89]->FieldName.'3>'.$LoadPortLaytimeCommenceAtHour.'</'.$tptfields[89]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[90]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[90]->CpCode.'</td><td>'.$tptfields[90]->NewDisplayName.'</td><td>'.$LoadPortLaytimeSelectDay.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[90]->FieldName.'1>'.$tptfields[90]->CpCode.'</'.$tptfields[90]->FieldName.'1></td><td><'.$tptfields[90]->FieldName.'2>'.$tptfields[90]->NewDisplayName.'</'.$tptfields[90]->FieldName.'2></td><td><'.$tptfields[90]->FieldName.'3>'.$LoadPortLaytimeSelectDay.'</'.$tptfields[90]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[91]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[91]->CpCode.'</td><td>'.$tptfields[91]->NewDisplayName.'</td><td>'.$LoadPortLaytimeTimeCountsIfOnDemurrage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[91]->FieldName.'1>'.$tptfields[91]->CpCode.'</'.$tptfields[91]->FieldName.'1></td><td><'.$tptfields[91]->FieldName.'2>'.$tptfields[91]->NewDisplayName.'</'.$tptfields[91]->FieldName.'2></td><td><'.$tptfields[91]->FieldName.'3>'.$LoadPortLaytimeTimeCountsIfOnDemurrage.'</'.$tptfields[91]->FieldName.'3></td></tr>';
				}
			}
		
			//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Disport</td><td></td></tr>';
			
			if($tptfields[92]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[92]->CpCode.'</td><td>'.$tptfields[92]->NewDisplayName.'</td><td>'.$DisPort.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[92]->FieldName.'1>'.$tptfields[92]->CpCode.'</'.$tptfields[92]->FieldName.'1></td><td><'.$tptfields[92]->FieldName.'2>'.$tptfields[92]->NewDisplayName.'</'.$tptfields[92]->FieldName.'2></td><td><'.$tptfields[92]->FieldName.'3>'.$DisPort.'</'.$tptfields[92]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[92]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[92]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[92]->FieldName;	
					$data_arr['FieldValue']=$DisPort;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[93]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_laycan_from_date_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[93]->CpCode.'</td><td>'.$tptfields[93]->NewDisplayName.'</td><td>'.$DpArrivalStartDate.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[93]->FieldName.'1>'.$tptfields[93]->CpCode.'</'.$tptfields[93]->FieldName.'1></td><td><'.$tptfields[93]->FieldName.'2>'.$tptfields[93]->NewDisplayName.'</'.$tptfields[93]->FieldName.'2></td><td><'.$tptfields[93]->FieldName.'3>'.$DpArrivalStartDate.'</'.$tptfields[93]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[93]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[93]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[93]->FieldName;	
					$data_arr['FieldValue']=$DpArrivalStartDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[94]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_laycan_to_date_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[94]->CpCode.'</td><td>'.$tptfields[94]->NewDisplayName.'</td><td>'.$DpArrivalEndDate.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[94]->FieldName.'1>'.$tptfields[94]->CpCode.'</'.$tptfields[94]->FieldName.'1></td><td><'.$tptfields[94]->FieldName.'2>'.$tptfields[94]->NewDisplayName.'</'.$tptfields[94]->FieldName.'2></td><td><'.$tptfields[94]->FieldName.'3>'.$DpArrivalEndDate.'</'.$tptfields[94]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[94]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[94]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[94]->FieldName;	
					$data_arr['FieldValue']=$DpArrivalEndDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[95]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_prefered_arrival_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[95]->CpCode.'</td><td>'.$tptfields[95]->NewDisplayName.'</td><td>'.$DpPreferDate.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[95]->FieldName.'1>'.$tptfields[95]->CpCode.'</'.$tptfields[95]->FieldName.'1></td><td><'.$tptfields[95]->FieldName.'2>'.$tptfields[95]->NewDisplayName.'</'.$tptfields[95]->FieldName.'2></td><td><'.$tptfields[95]->FieldName.'3>'.$DpPreferDate.'</'.$tptfields[95]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[95]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[95]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[95]->FieldName;	
					$data_arr['FieldValue']=$DpPreferDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[96]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_prefered_arrival_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[96]->CpCode.'</td><td>'.$tptfields[96]->NewDisplayName.'</td><td>'.$ExpectedDpDelayDay.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[96]->FieldName.'1>'.$tptfields[96]->CpCode.'</'.$tptfields[96]->FieldName.'1></td><td><'.$tptfields[96]->FieldName.'2>'.$tptfields[96]->NewDisplayName.'</'.$tptfields[96]->FieldName.'2></td><td><'.$tptfields[96]->FieldName.'3>'.$ExpectedDpDelayDay.'</'.$tptfields[96]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[96]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[96]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[96]->FieldName;	
					$data_arr['FieldValue']=$ExpectedDpDelayDay;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[97]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[97]->CpCode.'</td><td>'.$tptfields[97]->NewDisplayName.'</td><td>'.$DischargingTerms.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[97]->FieldName.'1>'.$tptfields[97]->CpCode.'</'.$tptfields[97]->FieldName.'1></td><td><'.$tptfields[97]->FieldName.'2>'.$tptfields[97]->NewDisplayName.'</'.$tptfields[97]->FieldName.'2></td><td><'.$tptfields[97]->FieldName.'3>'.$DischargingTerms.'</'.$tptfields[97]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[97]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[97]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[97]->FieldName;	
					$data_arr['FieldValue']=$DischargingTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[98]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_rate_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[98]->CpCode.'</td><td>'.$tptfields[98]->NewDisplayName.'</td><td>'.$DischargingRateMT.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[98]->FieldName.'1>'.$tptfields[98]->CpCode.'</'.$tptfields[98]->FieldName.'1></td><td><'.$tptfields[98]->FieldName.'2>'.$tptfields[98]->NewDisplayName.'</'.$tptfields[98]->FieldName.'2></td><td><'.$tptfields[98]->FieldName.'3>'.$DischargingRateMT.'</'.$tptfields[98]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[98]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[98]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[98]->FieldName;	
					$data_arr['FieldValue']=$DischargingRateMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[99]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_rage_uom_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[99]->CpCode.'</td><td>'.$tptfields[99]->NewDisplayName.'</td><td>'.$DischargingRateUOM.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[99]->FieldName.'1>'.$tptfields[99]->CpCode.'</'.$tptfields[99]->FieldName.'1></td><td><'.$tptfields[99]->FieldName.'2>'.$tptfields[99]->NewDisplayName.'</'.$tptfields[99]->FieldName.'2></td><td><'.$tptfields[99]->FieldName.'3>'.$DischargingRateUOM.'</'.$tptfields[99]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[99]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[99]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[99]->FieldName;	
					$data_arr['FieldValue']=$DischargingRateUOM;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[100]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='max_time_to_discharge_hrs_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[100]->CpCode.'</td><td>'.$tptfields[100]->NewDisplayName.'</td><td>'.$DpMaxTime.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[100]->FieldName.'1>'.$tptfields[100]->CpCode.'</'.$tptfields[100]->FieldName.'1></td><td><'.$tptfields[100]->FieldName.'2>'.$tptfields[100]->NewDisplayName.'</'.$tptfields[100]->FieldName.'2></td><td><'.$tptfields[100]->FieldName.'3>'.$DpMaxTime.'</'.$tptfields[100]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[100]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[100]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[100]->FieldName;	
					$data_arr['FieldValue']=$DpMaxTime;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[101]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_type_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[101]->CpCode.'</td><td>'.$tptfields[101]->NewDisplayName.'</td><td>'.$DpLaytimeType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[101]->FieldName.'1>'.$tptfields[101]->CpCode.'</'.$tptfields[101]->FieldName.'1></td><td><'.$tptfields[101]->FieldName.'2>'.$tptfields[101]->NewDisplayName.'</'.$tptfields[101]->FieldName.'2></td><td><'.$tptfields[101]->FieldName.'3>'.$DpLaytimeType.'</'.$tptfields[101]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[101]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[101]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[101]->FieldName;	
					$data_arr['FieldValue']=$DpLaytimeType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[102]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_tonnage_calc_based_on_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[102]->CpCode.'</td><td>'.$tptfields[102]->NewDisplayName.'</td><td>'.$DpCalculationBasedOn.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[102]->FieldName.'1>'.$tptfields[102]->CpCode.'</'.$tptfields[102]->FieldName.'1></td><td><'.$tptfields[102]->FieldName.'2>'.$tptfields[102]->NewDisplayName.'</'.$tptfields[102]->FieldName.'2></td><td><'.$tptfields[102]->FieldName.'3>'.$DpCalculationBasedOn.'</'.$tptfields[102]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[102]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[102]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[102]->FieldName;	
					$data_arr['FieldValue']=$DpCalculationBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[103]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='turn_free_time_hours_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[103]->CpCode.'</td><td>'.$tptfields[103]->NewDisplayName.'</td><td>'.$DpTurnTime.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[103]->FieldName.'1>'.$tptfields[103]->CpCode.'</'.$tptfields[103]->FieldName.'1></td><td><'.$tptfields[103]->FieldName.'2>'.$tptfields[103]->NewDisplayName.'</'.$tptfields[103]->FieldName.'2></td><td><'.$tptfields[103]->FieldName.'3>'.$DpTurnTime.'</'.$tptfields[103]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[103]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[103]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[103]->FieldName;	
					$data_arr['FieldValue']=$DpTurnTime;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[104]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='prior_use_terms_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[104]->CpCode.'</td><td>'.$tptfields[104]->NewDisplayName.'</td><td>'.$DpPriorUseTerms.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[104]->FieldName.'1>'.$tptfields[104]->CpCode.'</'.$tptfields[104]->FieldName.'1></td><td><'.$tptfields[104]->FieldName.'2>'.$tptfields[104]->NewDisplayName.'</'.$tptfields[104]->FieldName.'2></td><td><'.$tptfields[104]->FieldName.'3>'.$DpPriorUseTerms.'</'.$tptfields[104]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[104]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[104]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[104]->FieldName;	
					$data_arr['FieldValue']=$DpPriorUseTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			
			if($tptfields[105]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_based_on_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[105]->CpCode.'</td><td>'.$tptfields[105]->NewDisplayName.'</td><td>'.$DpLaytimeBasedOn.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[105]->FieldName.'1>'.$tptfields[105]->CpCode.'</'.$tptfields[105]->FieldName.'1></td><td><'.$tptfields[105]->FieldName.'2>'.$tptfields[105]->NewDisplayName.'</'.$tptfields[105]->FieldName.'2></td><td><'.$tptfields[105]->FieldName.'3>'.$DpLaytimeBasedOn.'</'.$tptfields[105]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[105]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[105]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[105]->FieldName;	
					$data_arr['FieldValue']=$DpLaytimeBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[106]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='type_of_charter_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[106]->CpCode.'</td><td>'.$tptfields[106]->NewDisplayName.'</td><td>'.$DpCharterType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[106]->FieldName.'1>'.$tptfields[106]->CpCode.'</'.$tptfields[106]->FieldName.'1></td><td><'.$tptfields[106]->FieldName.'2>'.$tptfields[106]->NewDisplayName.'</'.$tptfields[106]->FieldName.'2></td><td><'.$tptfields[106]->FieldName.'3>'.$DpCharterType.'</'.$tptfields[106]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[106]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[106]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[106]->FieldName;	
					$data_arr['FieldValue']=$DpCharterType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			
			if($tptfields[107]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tender_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[107]->CpCode.'</td><td>'.$tptfields[107]->NewDisplayName.'</td><td>'.$DpNorTendering.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[107]->FieldName.'1>'.$tptfields[107]->CpCode.'</'.$tptfields[107]->FieldName.'1></td><td><'.$tptfields[107]->FieldName.'2>'.$tptfields[107]->NewDisplayName.'</'.$tptfields[107]->FieldName.'2></td><td><'.$tptfields[107]->FieldName.'3>'.$DpNorTendering.'</'.$tptfields[107]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[107]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[107]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[107]->FieldName;	
					$data_arr['FieldValue']=$DpNorTendering;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[108]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='DpStevedoringTerms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[108]->CpCode.'</td><td>'.$tptfields[108]->NewDisplayName.'</td><td>'.$DpStevedoringTerms.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[108]->FieldName.'1>'.$tptfields[108]->CpCode.'</'.$tptfields[108]->FieldName.'1></td><td><'.$tptfields[108]->FieldName.'2>'.$tptfields[108]->NewDisplayName.'</'.$tptfields[108]->FieldName.'2></td><td><'.$tptfields[108]->FieldName.'3>'.$DpStevedoringTerms.'</'.$tptfields[108]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[108]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[108]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[108]->FieldName;	
					$data_arr['FieldValue']=$DpStevedoringTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[110]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[110]->CpCode.'</td><td>'.$tptfields[110]->NewDisplayName.'</td><td>'.$DisportEventName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[110]->FieldName.'1>'.$tptfields[110]->CpCode.'</'.$tptfields[110]->FieldName.'1></td><td><'.$tptfields[110]->FieldName.'2>'.$tptfields[110]->NewDisplayName.'</'.$tptfields[110]->FieldName.'2></td><td><'.$tptfields[110]->FieldName.'3>'.$DisportEventName.'</'.$tptfields[110]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[111]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[111]->CpCode.'</td><td>'.$tptfields[111]->NewDisplayName.'</td><td>'.$DisportLaytimeCountsOnDemurrage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[111]->FieldName.'1>'.$tptfields[111]->CpCode.'</'.$tptfields[111]->FieldName.'1></td><td><'.$tptfields[111]->FieldName.'2>'.$tptfields[111]->NewDisplayName.'</'.$tptfields[111]->FieldName.'2></td><td><'.$tptfields[111]->FieldName.'3>'.$DisportLaytimeCountsOnDemurrage.'</'.$tptfields[111]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[112]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[112]->CpCode.'</td><td>'.$tptfields[112]->NewDisplayName.'</td><td>'.$DisportLaytimeCounts.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[112]->FieldName.'1>'.$tptfields[112]->CpCode.'</'.$tptfields[112]->FieldName.'1></td><td><'.$tptfields[112]->FieldName.'2>'.$tptfields[112]->NewDisplayName.'</'.$tptfields[112]->FieldName.'2></td><td><'.$tptfields[112]->FieldName.'3>'.$DisportLaytimeCounts.'</'.$tptfields[112]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[113]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[113]->CpCode.'</td><td>'.$tptfields[113]->NewDisplayName.'</td><td>'.$DisportTimeCounting.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[113]->FieldName.'1>'.$tptfields[113]->CpCode.'</'.$tptfields[113]->FieldName.'1></td><td><'.$tptfields[113]->FieldName.'2>'.$tptfields[113]->NewDisplayName.'</'.$tptfields[113]->FieldName.'2></td><td><'.$tptfields[113]->FieldName.'3>'.$DisportTimeCounting.'</'.$tptfields[113]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[115]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[115]->CpCode.'</td><td>'.$tptfields[115]->NewDisplayName.'</td><td>'.$DisportCreateNewOrSelectListTendering.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[115]->FieldName.'1>'.$tptfields[115]->CpCode.'</'.$tptfields[115]->FieldName.'1></td><td><'.$tptfields[115]->FieldName.'2>'.$tptfields[115]->NewDisplayName.'</'.$tptfields[115]->FieldName.'2></td><td><'.$tptfields[115]->FieldName.'3>'.$DisportCreateNewOrSelectListTendering.'</'.$tptfields[115]->FieldName.'3></td></tr>';
				}
			}
			
			
			if($tptfields[116]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[116]->CpCode.'</td><td>'.$tptfields[116]->NewDisplayName.'</td><td>'.$DisportNORTenderingPreCondition.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[116]->FieldName.'1>'.$tptfields[116]->CpCode.'</'.$tptfields[116]->FieldName.'1></td><td><'.$tptfields[116]->FieldName.'2>'.$tptfields[116]->NewDisplayName.'</'.$tptfields[116]->FieldName.'2></td><td><'.$tptfields[116]->FieldName.'3>'.$DisportNORTenderingPreCondition.'</'.$tptfields[116]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[117]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[117]->CpCode.'</td><td>'.$tptfields[117]->NewDisplayName.'</td><td>'.$DisportTenderingStatus.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[117]->FieldName.'1>'.$tptfields[117]->CpCode.'</'.$tptfields[117]->FieldName.'1></td><td><'.$tptfields[117]->FieldName.'2>'.$tptfields[117]->NewDisplayName.'</'.$tptfields[117]->FieldName.'2></td><td><'.$tptfields[117]->FieldName.'3>'.$DisportTenderingStatus.'</'.$tptfields[117]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[119]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[119]->CpCode.'</td><td>'.$tptfields[119]->NewDisplayName.'</td><td>'.$DisportCreateNewOrSelectListAcceptance.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[119]->FieldName.'1>'.$tptfields[119]->CpCode.'</'.$tptfields[119]->FieldName.'1></td><td><'.$tptfields[119]->FieldName.'2>'.$tptfields[119]->NewDisplayName.'</'.$tptfields[119]->FieldName.'2></td><td><'.$tptfields[119]->FieldName.'3>'.$DisportCreateNewOrSelectListAcceptance.'</'.$tptfields[119]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[120]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[120]->CpCode.'</td><td>'.$tptfields[120]->NewDisplayName.'</td><td>'.$DisportNORAcceptancePreCondition.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[120]->FieldName.'1>'.$tptfields[120]->CpCode.'</'.$tptfields[120]->FieldName.'1></td><td><'.$tptfields[120]->FieldName.'2>'.$tptfields[120]->NewDisplayName.'</'.$tptfields[120]->FieldName.'2></td><td><'.$tptfields[120]->FieldName.'3>'.$DisportNORAcceptancePreCondition.'</'.$tptfields[120]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[121]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[121]->CpCode.'</td><td>'.$tptfields[121]->NewDisplayName.'</td><td>'.$DisportAcceptanceStatus.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[121]->FieldName.'1>'.$tptfields[121]->CpCode.'</'.$tptfields[121]->FieldName.'1></td><td><'.$tptfields[121]->FieldName.'2>'.$tptfields[121]->NewDisplayName.'</'.$tptfields[121]->FieldName.'2></td><td><'.$tptfields[121]->FieldName.'3>'.$DisportAcceptanceStatus.'</'.$tptfields[121]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[123]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[123]->CpCode.'</td><td>'.$tptfields[123]->NewDisplayName.'</td><td>'.$DisportOfficeDateFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[123]->FieldName.'1>'.$tptfields[123]->CpCode.'</'.$tptfields[123]->FieldName.'1></td><td><'.$tptfields[123]->FieldName.'2>'.$tptfields[123]->NewDisplayName.'</'.$tptfields[123]->FieldName.'2></td><td><'.$tptfields[123]->FieldName.'3>'.$DisportOfficeDateFrom.'</'.$tptfields[123]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[124]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[124]->CpCode.'</td><td>'.$tptfields[124]->NewDisplayName.'</td><td>'.$DisportOfficeDateTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[124]->FieldName.'1>'.$tptfields[124]->CpCode.'</'.$tptfields[124]->FieldName.'1></td><td><'.$tptfields[124]->FieldName.'2>'.$tptfields[124]->NewDisplayName.'</'.$tptfields[124]->FieldName.'2></td><td><'.$tptfields[124]->FieldName.'3>'.$DisportOfficeDateTo.'</'.$tptfields[124]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[125]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[125]->CpCode.'</td><td>'.$tptfields[125]->NewDisplayName.'</td><td>'.$DisportOfficeTimeFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[125]->FieldName.'1>'.$tptfields[125]->CpCode.'</'.$tptfields[125]->FieldName.'1></td><td><'.$tptfields[125]->FieldName.'2>'.$tptfields[125]->NewDisplayName.'</'.$tptfields[125]->FieldName.'2></td><td><'.$tptfields[125]->FieldName.'3>'.$DisportOfficeTimeFrom.'</'.$tptfields[125]->FieldName.'3></td></tr>';
				}
			}
			
			
			if($tptfields[126]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[126]->CpCode.'</td><td>'.$tptfields[126]->NewDisplayName.'</td><td>'.$DisportOfficeTimeTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[126]->FieldName.'1>'.$tptfields[126]->CpCode.'</'.$tptfields[126]->FieldName.'1></td><td><'.$tptfields[126]->FieldName.'2>'.$tptfields[126]->NewDisplayName.'</'.$tptfields[126]->FieldName.'2></td><td><'.$tptfields[126]->FieldName.'3>'.$DisportOfficeTimeTo.'</'.$tptfields[126]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[129]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[129]->CpCode.'</td><td>'.$tptfields[129]->NewDisplayName.'</td><td>'.$DisportLaytimeDayFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[129]->FieldName.'1>'.$tptfields[129]->CpCode.'</'.$tptfields[129]->FieldName.'1></td><td><'.$tptfields[129]->FieldName.'2>'.$tptfields[129]->NewDisplayName.'</'.$tptfields[129]->FieldName.'2></td><td><'.$tptfields[129]->FieldName.'3>'.$DisportLaytimeDayFrom.'</'.$tptfields[129]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[130]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[130]->CpCode.'</td><td>'.$tptfields[130]->NewDisplayName.'</td><td>'.$DisportLaytimeDayTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[130]->FieldName.'1>'.$tptfields[130]->CpCode.'</'.$tptfields[130]->FieldName.'1></td><td><'.$tptfields[130]->FieldName.'2>'.$tptfields[130]->NewDisplayName.'</'.$tptfields[130]->FieldName.'2></td><td><'.$tptfields[130]->FieldName.'3>'.$DisportLaytimeDayTo.'</'.$tptfields[130]->FieldName.'3></td></tr>';	
				}
			}
			
			if($tptfields[131]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[131]->CpCode.'</td><td>'.$tptfields[131]->NewDisplayName.'</td><td>'.$DisportLaytimeTimeFrom.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[131]->FieldName.'1>'.$tptfields[131]->CpCode.'</'.$tptfields[131]->FieldName.'1></td><td><'.$tptfields[131]->FieldName.'2>'.$tptfields[131]->NewDisplayName.'</'.$tptfields[131]->FieldName.'2></td><td><'.$tptfields[131]->FieldName.'3>'.$DisportLaytimeTimeFrom.'</'.$tptfields[131]->FieldName.'3></td></tr>';
				}
			}
			
			
			
			if($tptfields[132]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[132]->CpCode.'</td><td>'.$tptfields[132]->NewDisplayName.'</td><td>'.$DisportLaytimeTimeTo.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[132]->FieldName.'1>'.$tptfields[132]->CpCode.'</'.$tptfields[132]->FieldName.'1></td><td><'.$tptfields[132]->FieldName.'2>'.$tptfields[132]->NewDisplayName.'</'.$tptfields[132]->FieldName.'2></td><td><'.$tptfields[132]->FieldName.'3>'.$DisportLaytimeTimeTo.'</'.$tptfields[132]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[133]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[133]->CpCode.'</td><td>'.$tptfields[133]->NewDisplayName.'</td><td>'.$DisportLaytimeTurnTime.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[133]->FieldName.'1>'.$tptfields[133]->CpCode.'</'.$tptfields[133]->FieldName.'1></td><td><'.$tptfields[133]->FieldName.'2>'.$tptfields[133]->NewDisplayName.'</'.$tptfields[133]->FieldName.'2></td><td><'.$tptfields[133]->FieldName.'3>'.$DisportLaytimeTurnTime.'</'.$tptfields[133]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[134]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[134]->CpCode.'</td><td>'.$tptfields[134]->NewDisplayName.'</td><td>'.$DisportLaytimeTurnTimeExpire.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[134]->FieldName.'1>'.$tptfields[134]->CpCode.'</'.$tptfields[134]->FieldName.'1></td><td><'.$tptfields[134]->FieldName.'2>'.$tptfields[134]->NewDisplayName.'</'.$tptfields[134]->FieldName.'2></td><td><'.$tptfields[134]->FieldName.'3>'.$DisportLaytimeTurnTimeExpire.'</'.$tptfields[134]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[135]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[135]->CpCode.'</td><td>'.$tptfields[135]->NewDisplayName.'</td><td>'.$DisportLaytimeCommenceAt.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[135]->FieldName.'1>'.$tptfields[135]->CpCode.'</'.$tptfields[135]->FieldName.'1></td><td><'.$tptfields[135]->FieldName.'2>'.$tptfields[135]->NewDisplayName.'</'.$tptfields[135]->FieldName.'2></td><td><'.$tptfields[135]->FieldName.'3>'.$DisportLaytimeCommenceAt.'</'.$tptfields[135]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[136]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[136]->CpCode.'</td><td>'.$tptfields[136]->NewDisplayName.'</td><td>'.$DisportLaytimeCommenceAtHour.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[136]->FieldName.'1>'.$tptfields[136]->CpCode.'</'.$tptfields[136]->FieldName.'1></td><td><'.$tptfields[136]->FieldName.'2>'.$tptfields[136]->NewDisplayName.'</'.$tptfields[136]->FieldName.'2></td><td><'.$tptfields[136]->FieldName.'3>'.$DisportLaytimeCommenceAtHour.'</'.$tptfields[136]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[137]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[137]->CpCode.'</td><td>'.$tptfields[137]->NewDisplayName.'</td><td>'.$DisportLaytimeSelectDay.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[137]->FieldName.'1>'.$tptfields[137]->CpCode.'</'.$tptfields[137]->FieldName.'1></td><td><'.$tptfields[137]->FieldName.'2>'.$tptfields[137]->NewDisplayName.'</'.$tptfields[137]->FieldName.'2></td><td><'.$tptfields[137]->FieldName.'3>'.$DisportLaytimeSelectDay.'</'.$tptfields[137]->FieldName.'3></td></tr>';
				}
			}
			
			if($tptfields[138]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[138]->CpCode.'</td><td>'.$tptfields[138]->NewDisplayName.'</td><td>'.$DisportLaytimeTimeCountsIfOnDemurrage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[138]->FieldName.'1>'.$tptfields[138]->CpCode.'</'.$tptfields[138]->FieldName.'1></td><td><'.$tptfields[138]->FieldName.'2>'.$tptfields[138]->NewDisplayName.'</'.$tptfields[138]->FieldName.'2></td><td><'.$tptfields[138]->FieldName.'3>'.$DisportLaytimeTimeCountsIfOnDemurrage.'</'.$tptfields[138]->FieldName.'3></td></tr>';
				}
			}
			
			
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Brokerage</td><td></td></tr>';
		if($tptfields[141]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity_Type1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[141]->CpCode.'</td><td>'.$tptfields[141]->NewDisplayName.'</td><td>'.$BrokeragePayingEntityType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[141]->FieldName.'1>'.$tptfields[141]->CpCode.'</Brokerage'.$tptfields[141]->FieldName.'1></td><td><Brokerage'.$tptfields[141]->FieldName.'2>'.$tptfields[141]->NewDisplayName.'</Brokerage'.$tptfields[141]->FieldName.'2></td><td><Brokerage'.$tptfields[141]->FieldName.'3>'.$BrokeragePayingEntityType.'</Brokerage'.$tptfields[141]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[141]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[141]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[141]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
						
				
			}
			
			if($tptfields[142]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[142]->CpCode.'</td><td>'.$tptfields[142]->NewDisplayName.'</td><td>'.$BrokeragePayingEntityName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[142]->FieldName.'1>'.$tptfields[142]->CpCode.'</Brokerage'.$tptfields[142]->FieldName.'1></td><td><Brokerage'.$tptfields[142]->FieldName.'2>'.$tptfields[142]->NewDisplayName.'</Brokerage'.$tptfields[142]->FieldName.'2></td><td><Brokerage'.$tptfields[142]->FieldName.'3>'.$BrokeragePayingEntityName.'</Brokerage'.$tptfields[142]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[142]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[142]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[142]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
					
			}
			
			if($tptfields[143]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity_Type1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[143]->CpCode.'</td><td>'.$tptfields[143]->NewDisplayName.'</td><td>'.$BrokerageReceivingEntityType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[143]->FieldName.'1>'.$tptfields[143]->CpCode.'</Brokerage'.$tptfields[143]->FieldName.'1></td><td><Brokerage'.$tptfields[143]->FieldName.'2>'.$tptfields[143]->NewDisplayName.'</Brokerage'.$tptfields[143]->FieldName.'2></td><td><Brokerage'.$tptfields[143]->FieldName.'3>'.$BrokerageReceivingEntityType.'</Brokerage'.$tptfields[143]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[143]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[143]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[143]->FieldName;	
					$data_arr['FieldValue']=$BrokerageReceivingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
					
			}
			
			if($tptfields[144]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[144]->CpCode.'</td><td>'.$tptfields[144]->NewDisplayName.'</td><td>'.$BrokerageReceivingEntityName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[144]->FieldName.'1>'.$tptfields[144]->CpCode.'</Brokerage'.$tptfields[144]->FieldName.'1></td><td><Brokerage'.$tptfields[144]->FieldName.'2>'.$tptfields[144]->NewDisplayName.'</Brokerage'.$tptfields[144]->FieldName.'2></td><td><Brokerage'.$tptfields[144]->FieldName.'3>'.$BrokerageReceivingEntityName.'</Brokerage'.$tptfields[144]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[144]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[144]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[144]->FieldName;	
					$data_arr['FieldValue']=$BrokerageReceivingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[145]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokers_name1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[145]->CpCode.'</td><td>'.$tptfields[145]->NewDisplayName.'</td><td>'.$BrokerageBrokerName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[145]->FieldName.'1>'.$tptfields[145]->CpCode.'</Brokerage'.$tptfields[145]->FieldName.'1></td><td><Brokerage'.$tptfields[145]->FieldName.'2>'.$tptfields[145]->NewDisplayName.'</Brokerage'.$tptfields[145]->FieldName.'2></td><td><Brokerage'.$tptfields[145]->FieldName.'3>'.$BrokerageBrokerName.'</Brokerage'.$tptfields[145]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[145]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[145]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[145]->FieldName;	
					$data_arr['FieldValue']=$BrokerageBrokerName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[146]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_payable_as1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[146]->CpCode.'</td><td>'.$tptfields[146]->NewDisplayName.'</td><td>'.$BrokeragePayableAs.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[146]->FieldName.'1>'.$tptfields[146]->CpCode.'</Brokerage'.$tptfields[146]->FieldName.'1></td><td><Brokerage'.$tptfields[146]->FieldName.'2>'.$tptfields[146]->NewDisplayName.'</Brokerage'.$tptfields[146]->FieldName.'2></td><td><Brokerage'.$tptfields[146]->FieldName.'3>'.$BrokeragePayableAs.'</Brokerage'.$tptfields[146]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[146]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[146]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[146]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayableAs;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[147]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_freight1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[147]->CpCode.'</td><td>'.$tptfields[147]->NewDisplayName.'</td><td>'.$BrokeragePercentageOnFreight.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[147]->FieldName.'1>'.$tptfields[147]->CpCode.'</Brokerage'.$tptfields[147]->FieldName.'1></td><td><Brokerage'.$tptfields[147]->FieldName.'2>'.$tptfields[147]->NewDisplayName.'</Brokerage'.$tptfields[147]->FieldName.'2></td><td><Brokerage'.$tptfields[147]->FieldName.'3>'.$BrokeragePercentageOnFreight.'</Brokerage'.$tptfields[147]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[147]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[147]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[147]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[148]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_dead_freight1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[148]->CpCode.'</td><td>'.$tptfields[148]->NewDisplayName.'</td><td>'.$BrokeragePercentageOnDeadFreight.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[148]->FieldName.'1>'.$tptfields[148]->CpCode.'</Brokerage'.$tptfields[148]->FieldName.'1></td><td><Brokerage'.$tptfields[148]->FieldName.'2>'.$tptfields[148]->NewDisplayName.'</Brokerage'.$tptfields[148]->FieldName.'2></td><td><Brokerage'.$tptfields[148]->FieldName.'3>'.$BrokeragePercentageOnDeadFreight.'</Brokerage'.$tptfields[148]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[148]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[148]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[148]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnDeadFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
		
		if($tptfields[149]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Brokerage_on_demurrage1_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[149]->CpCode.'</td><td>'.$tptfields[149]->NewDisplayName.'</td><td>'.$BrokeragePercentageOnDemmurage.'</td></tr>';
			} else {
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[149]->FieldName.'1>'.$tptfields[149]->CpCode.'</Brokerage'.$tptfields[149]->FieldName.'1></td><td><Brokerage'.$tptfields[149]->FieldName.'2>'.$tptfields[149]->NewDisplayName.'</Brokerage'.$tptfields[149]->FieldName.'2></td><td><Brokerage'.$tptfields[149]->FieldName.'3>'.$BrokeragePercentageOnDemmurage.'</Brokerage'.$tptfields[149]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[149]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[149]->NewDisplayName;	
				$data_arr['FieldColumnName']='Brokerage'.$tptfields[149]->FieldName;	
				$data_arr['FieldValue']=$BrokeragePercentageOnDemmurage;		
				$data_arr['GroupNumber']=1;				
				array_push($fix_data_arr,$data_arr);
			}
					
		}
			
			if($tptfields[150]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_overage_qty1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[150]->CpCode.'</td><td>'.$tptfields[150]->NewDisplayName.'</td><td>'.$BrokeragePercentageOnOverage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[150]->FieldName.'1>'.$tptfields[150]->CpCode.'</Brokerage'.$tptfields[150]->FieldName.'1></td><td><Brokerage'.$tptfields[150]->FieldName.'2>'.$tptfields[150]->NewDisplayName.'</Brokerage'.$tptfields[150]->FieldName.'2></td><td><Brokerage'.$tptfields[150]->FieldName.'3>'.$BrokeragePercentageOnOverage.'</Brokerage'.$tptfields[150]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[150]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[150]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[150]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnOverage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[151]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Lumpsum_amount_payable1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[151]->CpCode.'</td><td>'.$tptfields[151]->NewDisplayName.'</td><td>'.$BrokerageLumpsumPayable.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[151]->FieldName.'1>'.$tptfields[151]->CpCode.'</Brokerage'.$tptfields[151]->FieldName.'1></td><td><Brokerage'.$tptfields[151]->FieldName.'2>'.$tptfields[151]->NewDisplayName.'</Brokerage'.$tptfields[151]->FieldName.'2></td><td><Brokerage'.$tptfields[151]->FieldName.'3>'.$BrokerageLumpsumPayable.'</Brokerage'.$tptfields[151]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[151]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[151]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[151]->FieldName;	
					$data_arr['FieldValue']=$BrokerageLumpsumPayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[152]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Enter_rate_tonne1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[152]->CpCode.'</td><td>'.$tptfields[152]->NewDisplayName.'</td><td>'.$BrokerageRatePerTonnePayable.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><Brokerage'.$tptfields[152]->FieldName.'1>'.$tptfields[152]->CpCode.'</Brokerage'.$tptfields[152]->FieldName.'1></td><td><Brokerage'.$tptfields[152]->FieldName.'2>'.$tptfields[152]->NewDisplayName.'</Brokerage'.$tptfields[152]->FieldName.'2></td><td><Brokerage'.$tptfields[152]->FieldName.'3>'.$BrokerageRatePerTonnePayable.'</Brokerage'.$tptfields[152]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[152]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[152]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[152]->FieldName;	
					$data_arr['FieldValue']=$BrokerageRatePerTonnePayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Add Com</td><td></td></tr>';
		
			if($tptfields[154]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity_Type2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[154]->CpCode.'</td><td>'.$tptfields[154]->NewDisplayName.'</td><td>'.$AddCommPayingEntityType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[154]->FieldName.'1>'.$tptfields[154]->CpCode.'</AddComm'.$tptfields[154]->FieldName.'1></td><td><AddComm'.$tptfields[154]->FieldName.'2>'.$tptfields[154]->NewDisplayName.'</AddComm'.$tptfields[154]->FieldName.'2></td><td><AddComm'.$tptfields[154]->FieldName.'3>'.$AddCommPayingEntityType.'</AddComm'.$tptfields[154]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[154]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[154]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[154]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[155]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[155]->CpCode.'</td><td>'.$tptfields[155]->NewDisplayName.'</td><td>'.$AddCommPayingEntityName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[155]->FieldName.'1>'.$tptfields[155]->CpCode.'</AddComm'.$tptfields[155]->FieldName.'1></td><td><AddComm'.$tptfields[155]->FieldName.'2>'.$tptfields[155]->NewDisplayName.'</AddComm'.$tptfields[155]->FieldName.'2></td><td><AddComm'.$tptfields[155]->FieldName.'3>'.$AddCommPayingEntityName.'</AddComm'.$tptfields[155]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[155]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[155]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[155]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[156]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity_Type2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[156]->CpCode.'</td><td>'.$tptfields[156]->NewDisplayName.'</td><td>'.$AddCommReceivingEntityType.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[156]->FieldName.'1>'.$tptfields[156]->CpCode.'</AddComm'.$tptfields[156]->FieldName.'1></td><td><AddComm'.$tptfields[156]->FieldName.'2>'.$tptfields[156]->NewDisplayName.'</AddComm'.$tptfields[156]->FieldName.'2></td><td><AddComm'.$tptfields[156]->FieldName.'3>'.$AddCommReceivingEntityType.'</AddComm'.$tptfields[156]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[156]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[156]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[156]->FieldName;	
					$data_arr['FieldValue']=$AddCommReceivingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[157]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[157]->CpCode.'</td><td>'.$tptfields[157]->NewDisplayName.'</td><td>'.$AddCommReceivingEntityName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[157]->FieldName.'1>'.$tptfields[157]->CpCode.'</AddComm'.$tptfields[157]->FieldName.'1></td><td><AddComm'.$tptfields[157]->FieldName.'2>'.$tptfields[157]->NewDisplayName.'</AddComm'.$tptfields[157]->FieldName.'2></td><td><AddComm'.$tptfields[157]->FieldName.'3>'.$AddCommReceivingEntityName.'</AddComm'.$tptfields[157]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[157]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[157]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[157]->FieldName;	
					$data_arr['FieldValue']=$AddCommReceivingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
		
		
			if($tptfields[158]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokers_name2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[158]->CpCode.'</td><td>'.$tptfields[158]->NewDisplayName.'</td><td>'.$AddCommBrokerName.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[158]->FieldName.'1>'.$tptfields[158]->CpCode.'</AddComm'.$tptfields[158]->FieldName.'1></td><td><AddComm'.$tptfields[158]->FieldName.'2>'.$tptfields[158]->NewDisplayName.'</AddComm'.$tptfields[158]->FieldName.'2></td><td><AddComm'.$tptfields[158]->FieldName.'3>'.$AddCommBrokerName.'</AddComm'.$tptfields[158]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[158]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[158]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[158]->FieldName;	
					$data_arr['FieldValue']=$AddCommBrokerName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[159]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_payable_as2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[159]->CpCode.'</td><td>'.$tptfields[159]->NewDisplayName.'</td><td>'.$AddCommPayableAs.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[159]->FieldName.'1>'.$tptfields[159]->CpCode.'</AddComm'.$tptfields[159]->FieldName.'1></td><td><AddComm'.$tptfields[159]->FieldName.'2>'.$tptfields[159]->NewDisplayName.'</AddComm'.$tptfields[159]->FieldName.'2></td><td><AddComm'.$tptfields[159]->FieldName.'3>'.$AddCommPayableAs.'</AddComm'.$tptfields[159]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[159]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[159]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[159]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayableAs;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[160]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_freight2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[160]->CpCode.'</td><td>'.$tptfields[160]->NewDisplayName.'</td><td>'.$AddCommPercentageOnFreight.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[160]->FieldName.'1>'.$tptfields[160]->CpCode.'</AddComm'.$tptfields[160]->FieldName.'1></td><td><AddComm'.$tptfields[160]->FieldName.'2>'.$tptfields[160]->NewDisplayName.'</AddComm'.$tptfields[160]->FieldName.'2></td><td><AddComm'.$tptfields[160]->FieldName.'3>'.$AddCommPercentageOnFreight.'</AddComm'.$tptfields[160]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[160]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[160]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[160]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[161]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_dead_freight2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[161]->CpCode.'</td><td>'.$tptfields[161]->NewDisplayName.'</td><td>'.$AddCommPercentageOnDeadFreight.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[161]->FieldName.'1>'.$tptfields[161]->CpCode.'</AddComm'.$tptfields[161]->FieldName.'1></td><td><AddComm'.$tptfields[161]->FieldName.'2>'.$tptfields[161]->NewDisplayName.'</AddComm'.$tptfields[161]->FieldName.'2></td><td><AddComm'.$tptfields[161]->FieldName.'3>'.$AddCommPercentageOnDeadFreight.'</AddComm'.$tptfields[161]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[161]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[161]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[161]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnDeadFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[162]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_demurrage2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[162]->CpCode.'</td><td>'.$tptfields[162]->NewDisplayName.'</td><td>'.$AddCommPercentageOnDemmurage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[162]->FieldName.'1>'.$tptfields[162]->CpCode.'</AddComm'.$tptfields[162]->FieldName.'1></td><td><AddComm'.$tptfields[162]->FieldName.'2>'.$tptfields[162]->NewDisplayName.'</AddComm'.$tptfields[162]->FieldName.'2></td><td><AddComm'.$tptfields[162]->FieldName.'3>'.$AddCommPercentageOnDemmurage.'</AddComm'.$tptfields[162]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[162]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[162]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[162]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnDemmurage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[163]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_overage_qty2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[163]->CpCode.'</td><td>'.$tptfields[163]->NewDisplayName.'</td><td>'.$AddCommPercentageOnOverage.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[163]->FieldName.'1>'.$tptfields[163]->CpCode.'</AddComm'.$tptfields[163]->FieldName.'1></td><td><AddComm'.$tptfields[163]->FieldName.'2>'.$tptfields[163]->NewDisplayName.'</AddComm'.$tptfields[163]->FieldName.'2></td><td><AddComm'.$tptfields[163]->FieldName.'3>'.$AddCommPercentageOnOverage.'</AddComm'.$tptfields[163]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[163]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[163]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[163]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnOverage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
						
			}
			
			if($tptfields[164]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Lumpsum_amount_payable2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[164]->CpCode.'</td><td>'.$tptfields[164]->NewDisplayName.'</td><td>'.$AddCommLumpsumPayable.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[164]->FieldName.'1>'.$tptfields[164]->CpCode.'</AddComm'.$tptfields[164]->FieldName.'1></td><td><AddComm'.$tptfields[164]->FieldName.'2>'.$tptfields[164]->NewDisplayName.'</AddComm'.$tptfields[164]->FieldName.'2></td><td><AddComm'.$tptfields[164]->FieldName.'3>'.$AddCommLumpsumPayable.'</AddComm'.$tptfields[164]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[164]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[164]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[164]->FieldName;	
					$data_arr['FieldValue']=$AddCommLumpsumPayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
					
			}
			
			if($tptfields[165]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Enter_rate_tonne2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td >'.$tptfields[165]->CpCode.'</td><td>'.$tptfields[165]->NewDisplayName.'</td><td>'.$AddCommRatePerTonnePayable.'</td></tr>';
				} else {
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><AddComm'.$tptfields[165]->FieldName.'1>'.$tptfields[165]->CpCode.'</AddComm'.$tptfields[165]->FieldName.'1></td><td><AddComm'.$tptfields[165]->FieldName.'2>'.$tptfields[165]->NewDisplayName.'</AddComm'.$tptfields[165]->FieldName.'2></td><td><AddComm'.$tptfields[165]->FieldName.'3>'.$AddCommRatePerTonnePayable.'</AddComm'.$tptfields[165]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[165]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[165]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[165]->FieldName;	
					$data_arr['FieldValue']=$AddCommRatePerTonnePayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Others</td><td></td></tr>';
			
			if($tptfields[167]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[167]->FieldName.'1>'.$tptfields[167]->CpCode.'</'.$tptfields[167]->FieldName.'1></td><td><'.$tptfields[167]->FieldName.'2>'.$tptfields[167]->NewDisplayName.'</'.$tptfields[167]->FieldName.'2></td><td><'.$tptfields[167]->FieldName.'3>'.$OtherPayingEntityType.'</'.$tptfields[167]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[168]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[168]->FieldName.'1>'.$tptfields[168]->CpCode.'</'.$tptfields[168]->FieldName.'1></td><td><'.$tptfields[168]->FieldName.'2>'.$tptfields[168]->NewDisplayName.'</'.$tptfields[168]->FieldName.'2></td><td><'.$tptfields[168]->FieldName.'3>'.$OtherPayingEntityName.'</'.$tptfields[168]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[169]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[169]->FieldName.'1>'.$tptfields[169]->CpCode.'</'.$tptfields[169]->FieldName.'1></td><td><'.$tptfields[169]->FieldName.'2>'.$tptfields[169]->NewDisplayName.'</'.$tptfields[169]->FieldName.'2></td><td><'.$tptfields[169]->FieldName.'3>'.$OtherReceivingEntityType.'</'.$tptfields[169]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[170]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[170]->FieldName.'1>'.$tptfields[170]->CpCode.'</'.$tptfields[170]->FieldName.'1></td><td><'.$tptfields[170]->FieldName.'2>'.$tptfields[170]->NewDisplayName.'</'.$tptfields[170]->FieldName.'2></td><td><'.$tptfields[170]->FieldName.'3>'.$OtherReceivingEntityName.'</'.$tptfields[170]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[171]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[171]->FieldName.'1>'.$tptfields[171]->CpCode.'</'.$tptfields[171]->FieldName.'1></td><td><'.$tptfields[171]->FieldName.'2>'.$tptfields[171]->NewDisplayName.'</'.$tptfields[171]->FieldName.'2></td><td><'.$tptfields[171]->FieldName.'3>'.$OtherBrokerName.'</'.$tptfields[171]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[172]->Included){ 
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[172]->FieldName.'1>'.$tptfields[172]->CpCode.'</'.$tptfields[172]->FieldName.'1></td><td><'.$tptfields[172]->FieldName.'2>'.$tptfields[172]->NewDisplayName.'</'.$tptfields[172]->FieldName.'2></td><td><'.$tptfields[172]->FieldName.'3>'.$OtherPayableAs.'</'.$tptfields[172]->FieldName.'3></td></tr>';
			}
		
			if($tptfields[173]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[173]->FieldName.'1>'.$tptfields[173]->CpCode.'</'.$tptfields[173]->FieldName.'1></td><td><'.$tptfields[173]->FieldName.'2>'.$tptfields[173]->NewDisplayName.'</'.$tptfields[173]->FieldName.'2></td><td><'.$tptfields[173]->FieldName.'3>'.$OtherPercentageOnFreight.'</'.$tptfields[173]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[174]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[174]->FieldName.'1>'.$tptfields[174]->CpCode.'</'.$tptfields[174]->FieldName.'1></td><td><'.$tptfields[174]->FieldName.'2>'.$tptfields[174]->NewDisplayName.'</'.$tptfields[174]->FieldName.'2></td><td><'.$tptfields[174]->FieldName.'3>'.$OtherPercentageOnDeadFreight.'</'.$tptfields[174]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[175]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[175]->FieldName.'1>'.$tptfields[175]->CpCode.'</'.$tptfields[175]->FieldName.'1></td><td><'.$tptfields[175]->FieldName.'2>'.$tptfields[175]->NewDisplayName.'</'.$tptfields[175]->FieldName.'2></td><td><'.$tptfields[175]->FieldName.'3>'.$OtherPercentageOnDemmurage.'</'.$tptfields[175]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[176]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[176]->FieldName.'1>'.$tptfields[176]->CpCode.'</'.$tptfields[176]->FieldName.'1></td><td><'.$tptfields[176]->FieldName.'2>'.$tptfields[176]->NewDisplayName.'</'.$tptfields[176]->FieldName.'2></td><td><'.$tptfields[176]->FieldName.'3>'.$OtherPercentageOnOverage.'</'.$tptfields[176]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[177]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[177]->FieldName.'1>'.$tptfields[177]->CpCode.'</'.$tptfields[177]->FieldName.'1></td><td><'.$tptfields[177]->FieldName.'2>'.$tptfields[177]->NewDisplayName.'</'.$tptfields[177]->FieldName.'2></td><td><'.$tptfields[177]->FieldName.'3>'.$OtherLumpsumPayable.'</'.$tptfields[177]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[178]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[178]->FieldName.'1>'.$tptfields[178]->CpCode.'</'.$tptfields[178]->FieldName.'1></td><td><'.$tptfields[178]->FieldName.'2>'.$tptfields[178]->NewDisplayName.'</'.$tptfields[178]->FieldName.'2></td><td><'.$tptfields[178]->FieldName.'3>'.$OtherRatePerTonnePayable.'</'.$tptfields[178]->FieldName.'3></td></tr>';
			}
			
		
		
		$ResponseID='';
		$FreightBasis='';
		$FreightRate='';
		$FreightCurrency='';
		$FreightRateUOM='';
		$FreightTce='';
		$FreightTceDifferential='';
		$FreightLumpsumMax='';
		$FreightLow='';
		$FreightHigh='';
		$FreightBasisFlag1=0;
		$FreightBasisFlag2=0;
		$FreightBasisFlag3=0;
		$Demurrage='';
		$DespatchDemurrageFlag='';
		$DespatchHalfDemurrage='';
		$DespatchDemurrageFlag1=0;
		$DespatchDemurrageFlag2=0;
		if($data4){
		foreach($data4 as $row) {
			if($row->FreightBasis==1){
				$FreightBasis .='$/mt || ';
				$FreightBasisFlag1=1;
			}
			if($row->FreightBasis==2){
				$FreightBasis .='Lumpsum || ';
				$FreightBasisFlag2=1;
			}
			if($row->FreightBasis==3){
				$FreightBasis .='High - Low ($/mt) || ';
				$FreightBasisFlag3=1;
			}
			
			$ResponseID .=$row->ResponseID.' || ';
			$FreightRate .=$row->FreightRate.' || ';
			$FreightCurrency .=$row->curCode.' || ';
			
			if($row->FreightRateUOM==1){
				$FreightRUOM='MT(Metric Tonnes)';
			}else if($row->FreightRateUOM==2){
				$FreightRUOM='LT(Long Tonnes)';
			}else if($row->FreightRateUOM==3){
				$FreightRUOM='PMT(Per metric tonne)';
			}else if($row->FreightRateUOM==4){
				$FreightRUOM='PLT(Per long ton)';
			}else if($row->FreightRateUOM==5){
				$FreightRUOM='WWD(Weather Working Day)';
			}
			
			$FreightRateUOM .=$FreightRUOM.' || ';
			$FreightTce .=number_format($row->FreightTce,2).' || ';
			$FreightTceDifferential .=number_format($row->FreightTceDifferential,2).' || ';
			$FreightLumpsumMax .=number_format($row->FreightLumpsumMax,2).' || ';
			$FreightLow .=number_format($row->FreightLow,2).' || ';
			$FreightHigh .=number_format($row->FreightHigh,2).' || ';
			$Demurrage .=number_format($row->Demurrage)	.' || ';
			if($row->DespatchDemurrageFlag==1){
				$DespatchDFlag='Yes';
				$DespatchDemurrageFlag1=1;
			}
			if($row->DespatchDemurrageFlag==2){
				$DespatchDFlag='No';
				$DespatchDemurrageFlag2=1;
			}
			$DespatchDemurrageFlag .=$DespatchDFlag.' || ';
			$DespatchHalfDemurrage .=number_format($row->DespatchHalfDemurrage).' || ';
		}
		}
		
		$ResponseID=rtrim($ResponseID,' || ');
		$FreightBasis=rtrim($FreightBasis,' || ');
		$FreightRate=rtrim($FreightRate,' || ');
		$FreightCurrency=rtrim($FreightCurrency,' || ');
		$FreightRateUOM=rtrim($FreightRateUOM,' || ');
		$FreightTce=rtrim($FreightTce,' || ');
		$FreightTceDifferential=rtrim($FreightTceDifferential,' || ');
		$FreightLumpsumMax=rtrim($FreightLumpsumMax,' || ');
		$FreightLow=rtrim($FreightLow,' || ');
		$FreightHigh=rtrim($FreightHigh,' || ');
		$Demurrage=rtrim($Demurrage,' || ');
		$DespatchDemurrageFlag=rtrim($DespatchDemurrageFlag,' || ');
		$DespatchHalfDemurrage=rtrim($DespatchHalfDemurrage,' || ');
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Freight Quote</td><td></td></tr>';
		
		
		if($tptfields[179]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[179]->FieldName.'1>'.$tptfields[179]->CpCode.'</'.$tptfields[179]->FieldName.'1></td><td><'.$tptfields[179]->FieldName.'2>'.$tptfields[179]->NewDisplayName.'</'.$tptfields[179]->FieldName.'2></td><td><'.$tptfields[179]->FieldName.'3>'.$ResponseID.'</'.$tptfields[179]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[179]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[179]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[179]->FieldName;		
			$data_arr['FieldValue']=$ResponseID;						
			$data_arr['GroupNumber']=2;		
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[180]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[180]->FieldName.'1>'.$tptfields[180]->CpCode.'</'.$tptfields[180]->FieldName.'1></td><td><'.$tptfields[180]->FieldName.'2>'.$tptfields[180]->NewDisplayName.'</'.$tptfields[180]->FieldName.'2></td><td><'.$tptfields[180]->FieldName.'3>'.$FreightBasis.'</'.$tptfields[180]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[180]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[180]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[180]->FieldName;		
			$data_arr['FieldValue']=$FreightBasis;						
			$data_arr['GroupNumber']=2;		
			array_push($fix_data_arr,$data_arr);
		}
		
		if($FreightBasisFlag1==1){
				
				if($tptfields[181]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[181]->FieldName.'1>'.$tptfields[181]->CpCode.'</'.$tptfields[181]->FieldName.'1></td><td><'.$tptfields[181]->FieldName.'2>'.$tptfields[181]->NewDisplayName.'</'.$tptfields[181]->FieldName.'2></td><td><'.$tptfields[181]->FieldName.'3>'.$FreightRate.'</'.$tptfields[181]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[181]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[181]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[181]->FieldName;		
					$data_arr['FieldValue']=$FreightRate;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[182]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[182]->FieldName.'1>'.$tptfields[182]->CpCode.'</'.$tptfields[182]->FieldName.'1></td><td><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2></td><td><'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[183]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[183]->FieldName.'1>'.$tptfields[183]->CpCode.'</'.$tptfields[183]->FieldName.'1></td><td><'.$tptfields[183]->FieldName.'2>'.$tptfields[183]->NewDisplayName.'</'.$tptfields[183]->FieldName.'2></td><td><'.$tptfields[183]->FieldName.'3>'.$FreightRateUOM.'</'.$tptfields[183]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[183]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[183]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[183]->FieldName;		
					$data_arr['FieldValue']=$FreightRateUOM;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[184]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[184]->FieldName.'1>'.$tptfields[184]->CpCode.'</'.$tptfields[184]->FieldName.'1></td><td><'.$tptfields[184]->FieldName.'2>'.$tptfields[184]->NewDisplayName.'</'.$tptfields[184]->FieldName.'2></td><td><'.$tptfields[184]->FieldName.'3>'.$FreightTce.'</'.$tptfields[184]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[184]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[184]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[184]->FieldName;		
					$data_arr['FieldValue']=$FreightTce;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				
				if($tptfields[185]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[185]->FieldName.'1>'.$tptfields[185]->CpCode.'</'.$tptfields[185]->FieldName.'1></td><td><'.$tptfields[185]->FieldName.'2>'.$tptfields[185]->NewDisplayName.'</'.$tptfields[185]->FieldName.'2></td><td><'.$tptfields[185]->FieldName.'3>'.$FreightTceDifferential.'</'.$tptfields[185]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[185]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[185]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[185]->FieldName;		
					$data_arr['FieldValue']=$FreightTceDifferential;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				
			} 
			
			if($FreightBasisFlag2==1){
				if($tptfields[186]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[186]->FieldName.'1>'.$tptfields[186]->CpCode.'</'.$tptfields[186]->FieldName.'1></td><td><'.$tptfields[186]->FieldName.'2>'.$tptfields[186]->NewDisplayName.'</'.$tptfields[186]->FieldName.'2></td><td><'.$tptfields[186]->FieldName.'3>'.$FreightLumpsumMax.'</'.$tptfields[186]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[186]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[186]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[186]->FieldName;		
					$data_arr['FieldValue']=$FreightLumpsumMax;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[182]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[182]->FieldName.'1>'.$tptfields[182]->CpCode.'</'.$tptfields[182]->FieldName.'1></td><td><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2></td><td><'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;							
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
			} 
			
			if($FreightBasisFlag3==1){
				if($tptfields[187]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[187]->FieldName.'1>'.$tptfields[187]->CpCode.'</'.$tptfields[187]->FieldName.'1></td><td><'.$tptfields[187]->FieldName.'2>'.$tptfields[187]->NewDisplayName.'</'.$tptfields[187]->FieldName.'2></td><td><'.$tptfields[187]->FieldName.'3>'.$FreightLow.'</'.$tptfields[187]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[187]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[187]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[187]->FieldName;		
					$data_arr['FieldValue']=$FreightLow;					
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[188]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[188]->FieldName.'1>'.$tptfields[188]->CpCode.'</'.$tptfields[188]->FieldName.'1></td><td><'.$tptfields[188]->FieldName.'2>'.$tptfields[188]->NewDisplayName.'</'.$tptfields[188]->FieldName.'2></td><td><'.$tptfields[188]->FieldName.'3>'.$FreightHigh.'</'.$tptfields[188]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[188]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[188]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[188]->FieldName;		
					$data_arr['FieldValue']=$FreightHigh;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				
				if($tptfields[182]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[182]->FieldName.'1>'.$tptfields[182]->CpCode.'</'.$tptfields[182]->FieldName.'1></td><td><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2></td><td><'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;						
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[183]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[183]->FieldName.'1>'.$tptfields[183]->CpCode.'</'.$tptfields[183]->FieldName.'1></td><td><'.$tptfields[183]->FieldName.'2>'.$tptfields[183]->NewDisplayName.'</'.$tptfields[183]->FieldName.'2></td><td><'.$tptfields[183]->FieldName.'3>'.$FreightRateUOM.'</'.$tptfields[183]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[183]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[183]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[183]->FieldName;		
					$data_arr['FieldValue']=$FreightRateUOM;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				if($$tptfields[184]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$$tptfields[184]->FieldName.'1>'.$$tptfields[184]->CpCode.'</'.$$tptfields[184]->FieldName.'1></td><td><'.$$tptfields[184]->FieldName.'2>'.$$tptfields[184]->NewDisplayName.'</'.$$tptfields[184]->FieldName.'2></td><td><'.$$tptfields[184]->FieldName.'3>'.$FreightTce.'</'.$$tptfields[184]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$$tptfields[184]->CpCode;				
					$data_arr['FieldLblName']=$$tptfields[184]->NewDisplayName;	
					$data_arr['FieldColumnName']=$$tptfields[184]->FieldName;		
					$data_arr['FieldValue']=$FreightTce;					
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[185]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[185]->FieldName.'1>'.$tptfields[185]->CpCode.'</'.$tptfields[185]->FieldName.'1></td><td><'.$tptfields[185]->FieldName.'2>'.$tptfields[185]->NewDisplayName.'</'.$tptfields[185]->FieldName.'2></td><td><'.$tptfields[185]->FieldName.'3>'.$FreightTceDifferential.'</'.$tptfields[185]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[185]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[185]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[185]->FieldName;		
					$data_arr['FieldValue']=$FreightTceDifferential;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
			} 
			
			if($tptfields[202]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[202]->FieldName.'1>'.$tptfields[202]->CpCode.'</'.$tptfields[202]->FieldName.'1></td><td><'.$tptfields[202]->FieldName.'2>'.$tptfields[202]->NewDisplayName.'</'.$tptfields[202]->FieldName.'2></td><td><'.$tptfields[202]->FieldName.'3>'.$Demurrage.'</'.$tptfields[202]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[202]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[202]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[202]->FieldName;		
				$data_arr['FieldValue']=$Demurrage;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[203]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[203]->FieldName.'1>'.$tptfields[203]->CpCode.'</'.$tptfields[203]->FieldName.'1></td><td><'.$tptfields[203]->FieldName.'2>'.$tptfields[203]->NewDisplayName.'</'.$tptfields[203]->FieldName.'2></td><td><'.$tptfields[203]->FieldName.'3>'.$DespatchDemurrageFlag.'</'.$tptfields[203]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[203]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[203]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[203]->FieldName;		
				$data_arr['FieldValue']=$DespatchDemurrageFlag;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[204]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[204]->FieldName.'1>'.$tptfields[204]->CpCode.'</'.$tptfields[204]->FieldName.'1></td><td><'.$tptfields[204]->FieldName.'2>'.$tptfields[204]->NewDisplayName.'</'.$tptfields[204]->FieldName.'2></td><td><'.$tptfields[204]->FieldName.'3>'.$DespatchHalfDemurrage.'</'.$tptfields[204]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[204]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[204]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[204]->FieldName;		
				$data_arr['FieldValue']=$DespatchHalfDemurrage;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
		
		
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Differential</td><td></td></tr>';
		
		
		$DifferentialDisport1='';
		$LpDpFlg1='';
		$LoadingDischargingRate1='';
		$LoadDischargeUnit1='';
		$DifferentailInviteeAmt1='';
		if($data5){
			$templinenum='';
		
		foreach($data5 as $row) {
			if($templinenum==$row->LineNum){
				continue;
			}
			$templinenum=$row->LineNum;
			$GropNoFlg='';
			$DifferentialDisport='';
			$LpDpFlg='';
			$LoadingDischargingRate='';
			$LoadDischargeUnit='';
			$DifferentailInviteeAmt='';
			$DRDResponse=$this->cargo_quote_model->getDifferentialRefDisportsResponse($row->DifferentialID);
			foreach($DRDResponse as $drdr) {
				if($drdr->LpDpFlg==1) {
				$LpDp='Lp';	
				}
				if($drdr->LpDpFlg==2) {
				$LpDp='Dp';	
				}
				if($drdr->LoadDischargeUnit==1) {
				$LDUnit='$ mt/hr';	
				}
				if($drdr->LoadDischargeUnit==2) {
				$LDUnit='$ mt/day';	
				}
				if($GropNoFlg==$drdr->GroupNo) {
					$DifferentialDisport .=$drdr->PortName.' , ';
					$LoadingDischargingRate .=$drdr->LoadDischargeRate.' , ';
					$LpDpFlg .=$LpDp.' , ';
					$LoadDischargeUnit .=$LDUnit.' , ';
					$DifferentailInviteeAmt .=$drdr->DifferentialInviteeAmt.' , ';
				} else {
					$DifferentialDisport=trim($DifferentialDisport,' , ');
					$LoadingDischargingRate=trim($LoadingDischargingRate,' , ');
					$LpDpFlg=trim($LpDpFlg,' , ');
					$LoadDischargeUnit=trim($LoadDischargeUnit,' , ');
					$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,' , ');
					$DifferentialDisport .=') | ('.$drdr->PortName.' , ';
					$LoadingDischargingRate .=') | ('.$drdr->LoadDischargeRate.' , ';
					$LpDpFlg .=') | ('.$LpDp.' , ';
					$LoadDischargeUnit .=') | ('.$LDUnit.' , ';
					$DifferentailInviteeAmt .=') | ('.$drdr->DifferentialInviteeAmt.' , ';
				}
				$GropNoFlg=$drdr->GroupNo;
				
			}
			$DifferentialDisport=trim($DifferentialDisport,' , ');
			$LoadingDischargingRate=trim($LoadingDischargingRate,' , ');
			$LpDpFlg=trim($LpDpFlg,' , ');
			$LoadDischargeUnit=trim($LoadDischargeUnit,' , ');
			$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,' , ');
			$DifferentialDisport=trim($DifferentialDisport,') | ');
			$LoadingDischargingRate=trim($LoadingDischargingRate,') | ');
			$LpDpFlg=trim($LpDpFlg,') | ');
			$LoadDischargeUnit=trim($LoadDischargeUnit,') | ');
			$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,') | ');
			$DifferentialDisport .=')';
			$LoadingDischargingRate .=')';
			$LpDpFlg .=' )';
			$LoadDischargeUnit .=' )';
			$DifferentailInviteeAmt .=' )';
			$DifferentialDisport1 .=$DifferentialDisport.' || ';
			$LoadingDischargingRate1 .=$LoadingDischargingRate.' || ';
			$LpDpFlg1 .=$LpDpFlg.' || ';
			$LoadDischargeUnit1 .=$LoadDischargeUnit.' || ';
			$DifferentailInviteeAmt1 .=$DifferentailInviteeAmt.' || ';
		}
		}
		$DifferentialDisport1=rtrim($DifferentialDisport1,' || ');
		$LpDpFlg1=rtrim($LpDpFlg1,' || ');
		$LoadDischargeUnit1=rtrim($LoadDischargeUnit1,' || ');
		$LoadingDischargingRate1=rtrim($LoadingDischargingRate1,' || ');
		$DifferentailInviteeAmt1=rtrim($DifferentailInviteeAmt1,' || ');
		
		if($tptfields[195]->Included){
			$html .='<tr><td contenteditable="false" ><'.$tptfields[195]->FieldName.'1>'.$tptfields[195]->CpCode.'</'.$tptfields[195]->FieldName.'1></td><td><'.$tptfields[195]->FieldName.'2>'.$tptfields[195]->NewDisplayName.'</'.$tptfields[195]->FieldName.'2></td><td><'.$tptfields[195]->FieldName.'3>'.$DifferentialDisport1.'</'.$tptfields[195]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[195]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[195]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[195]->FieldName;		
			$data_arr['FieldValue']=$DifferentialDisport1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[196]->Included){
			$html .='<tr><td contenteditable="false" ><'.$tptfields[196]->FieldName.'1>'.$tptfields[196]->CpCode.'</'.$tptfields[196]->FieldName.'1></td><td><'.$tptfields[196]->FieldName.'2>'.$tptfields[196]->NewDisplayName.'</'.$tptfields[196]->FieldName.'2></td><td><'.$tptfields[196]->FieldName.'3>'.$LpDpFlg1.'</'.$tptfields[196]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[196]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[196]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[196]->FieldName;		
			$data_arr['FieldValue']=$LpDpFlg1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[197]->Included){
			$html .='<tr><td contenteditable="false" ><'.$tptfields[197]->FieldName.'1>'.$tptfields[197]->CpCode.'</'.$tptfields[197]->FieldName.'1></td><td><'.$tptfields[197]->FieldName.'2>'.$tptfields[197]->NewDisplayName.'</'.$tptfields[197]->FieldName.'2></td><td><'.$tptfields[197]->FieldName.'3>'.$LoadingDischargingRate1.'</'.$tptfields[197]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[197]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[197]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[197]->FieldName;		
			$data_arr['FieldValue']=$LoadingDischargingRate1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[198]->Included){
			$html .='<tr><td contenteditable="false" ><'.$tptfields[198]->FieldName.'1>'.$tptfields[198]->CpCode.'</'.$tptfields[198]->FieldName.'1></td><td><'.$tptfields[198]->FieldName.'2>'.$tptfields[198]->NewDisplayName.'</'.$tptfields[198]->FieldName.'2></td><td><'.$tptfields[198]->FieldName.'3>'.$LoadDischargeUnit1.'</'.$tptfields[198]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[198]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[198]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[198]->FieldName;		
			$data_arr['FieldValue']=$LoadDischargeUnit1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[201]->Included){
			$html .='<tr><td contenteditable="false" ><'.$tptfields[201]->FieldName.'1>'.$tptfields[201]->CpCode.'</'.$tptfields[201]->FieldName.'1></td><td><'.$tptfields[201]->FieldName.'2>'.$tptfields[201]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2></td><td><'.$tptfields[201]->FieldName.'3>'.$DifferentailInviteeAmt1.'</'.$tptfields[201]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[201]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[201]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[201]->FieldName;		
			$data_arr['FieldValue']=$DifferentailInviteeAmt1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
	//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Performing vessel</td><td></td></tr>';	
					
		if($data6){
			if($data6->SelectVesselBy==1){
				$SelectVesselBy='Vessel name incl ex_name';
			}else if($data6->SelectVesselBy==2){
				$SelectVesselBy='IMO number';
			}else if($data6->SelectVesselBy==3){
				$SelectVesselBy='Vessel not found';
			}
			if($tptfields[205]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[205]->FieldName.'1>'.$tptfields[205]->CpCode.'</'.$tptfields[205]->FieldName.'1></td><td><'.$tptfields[205]->FieldName.'2>'.$tptfields[205]->NewDisplayName.'</'.$tptfields[205]->FieldName.'2></td><td><'.$tptfields[205]->FieldName.'3>'.$SelectVesselBy.'</'.$tptfields[205]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[205]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[205]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[205]->FieldName;		
				$data_arr['FieldValue']=$SelectVesselBy;					
				$data_arr['GroupNumber']=3;		
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[206]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[206]->FieldName.'1>'.$tptfields[206]->CpCode.'</'.$tptfields[206]->FieldName.'1></td><td><'.$tptfields[206]->FieldName.'2>'.$tptfields[206]->NewDisplayName.'</'.$tptfields[206]->FieldName.'2></td><td><'.$tptfields[206]->FieldName.'3>'.$data6->VesselName.'</'.$tptfields[206]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[206]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[206]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[206]->FieldName;		
				$data_arr['FieldValue']=$data6->VesselName;						
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[207]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[207]->FieldName.'1>'.$tptfields[207]->CpCode.'</'.$tptfields[207]->FieldName.'1></td><td><'.$tptfields[207]->FieldName.'2>'.$tptfields[207]->NewDisplayName.'</'.$tptfields[207]->FieldName.'2></td><td><'.$tptfields[207]->FieldName.'3>'.$data6->IMO.'</'.$tptfields[207]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[207]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[207]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[207]->FieldName;		
				$data_arr['FieldValue']=$data6->IMO;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($data6->VesselCurrentName){	
			if($tptfields[209]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[209]->FieldName.'1>'.$tptfields[209]->CpCode.'</'.$tptfields[209]->FieldName.'1></td><td><'.$tptfields[209]->FieldName.'2>'.$tptfields[209]->NewDisplayName.'</'.$tptfields[209]->FieldName.'2></td><td><'.$tptfields[209]->FieldName.'3>'.$data6->VesselCurrentName.'</'.$tptfields[209]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[209]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[209]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[209]->FieldName;		
				$data_arr['FieldValue']=$data6->VesselCurrentName;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[210]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[210]->FieldName.'1>'.$tptfields[210]->CpCode.'</'.$tptfields[210]->FieldName.'1></td><td><'.$tptfields[210]->FieldName.'2>'.$tptfields[210]->NewDisplayName.'</'.$tptfields[210]->FieldName.'2></td><td><'.$tptfields[210]->FieldName.'3>'.date('d-m-Y',strtotime($data6->VesselChangeNameDate)).'</'.$tptfields[210]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[210]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[210]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[210]->FieldName;		
				$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->VesselChangeNameDate));					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[211]->Included){
				$FirstLoadPortDate=date('d-m-Y',strtotime($data6->FirstLoadPortDate));
				if($FirstLoadPortDate=='01-01-1970'){
					$FirstLoadPortDate='';
				}
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[211]->FieldName.'1>'.$tptfields[211]->CpCode.'</'.$tptfields[211]->FieldName.'1></td><td><'.$tptfields[211]->FieldName.'2>'.$tptfields[211]->NewDisplayName.'</'.$tptfields[211]->FieldName.'2></td><td><'.$tptfields[211]->FieldName.'3>'.$FirstLoadPortDate.'</'.$tptfields[211]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[211]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[211]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[211]->FieldName;		
				$data_arr['FieldValue']=$FirstLoadPortDate;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[212]->Included){
				$LastDisPortDate=date('d-m-Y',strtotime($data6->LastDisPortDate));
				if($LastDisPortDate=='01-01-1970'){
					$LastDisPortDate='';
				}
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[212]->FieldName.'1>'.$tptfields[212]->CpCode.'</'.$tptfields[212]->FieldName.'1></td><td><'.$tptfields[212]->FieldName.'2>'.$tptfields[212]->NewDisplayName.'</'.$tptfields[212]->FieldName.'2></td><td><'.$tptfields[212]->FieldName.'3>'.$LastDisPortDate.'</'.$tptfields[212]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[212]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[212]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[212]->FieldName;		
				$data_arr['FieldValue']=$LastDisPortDate;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[213]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[213]->FieldName.'1>'.$tptfields[213]->CpCode.'</'.$tptfields[213]->FieldName.'1></td><td><'.$tptfields[213]->FieldName.'2>'.$tptfields[213]->NewDisplayName.'</'.$tptfields[213]->FieldName.'2></td><td><'.$tptfields[213]->FieldName.'3>'.$data6->EntityName.'</'.$tptfields[213]->FieldName.'3></td></tr>';
			}
			if($tptfields[214]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6">'.$tptfields[214]->CpCode.'</td><td>'.$tptfields[214]->NewDisplayName.'</td><td>'.$data6->AssociateCompanyID.'</td></tr>';
			}
			if($tptfields[215]->Included){
			if($data6->Address1){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[215]->FieldName.'1>'.$tptfields[215]->CpCode.'</'.$tptfields[215]->FieldName.'1></td><td><'.$tptfields[215]->FieldName.'2>'.$tptfields[215]->NewDisplayName.'</'.$tptfields[215]->FieldName.'2></td><td><'.$tptfields[215]->FieldName.'3>'.$data6->Address1.'</'.$tptfields[215]->FieldName.'3></td></tr>';
			}
			}
		if($tptfields[216]->Included){
			if($data6->Address2){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[216]->FieldName.'1>'.$tptfields[216]->CpCode.'</'.$tptfields[216]->FieldName.'1></td><td><'.$tptfields[216]->FieldName.'2>'.$tptfields[216]->NewDisplayName.'</'.$tptfields[216]->FieldName.'2></td><td><'.$tptfields[216]->FieldName.'3>'.$data6->Address2.'</'.$tptfields[216]->FieldName.'3></td></tr>';
			}
		}
		if($tptfields[217]->Included){
			if($data6->Address3){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[217]->FieldName.'1>'.$tptfields[217]->CpCode.'</'.$tptfields[217]->FieldName.'1></td><td><'.$tptfields[217]->FieldName.'2>'.$tptfields[217]->NewDisplayName.'</'.$tptfields[217]->FieldName.'2></td><td><'.$tptfields[217]->FieldName.'3>'.$data6->Address3.'</'.$tptfields[217]->FieldName.'3></td></tr>';
			}
		}
		if($tptfields[218]->Included){
			if($data6->Address3){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[218]->FieldName.'1>'.$tptfields[218]->CpCode.'</'.$tptfields[218]->FieldName.'1></td><td><'.$tptfields[218]->FieldName.'2>'.$tptfields[218]->NewDisplayName.'</'.$tptfields[218]->FieldName.'2></td><td><'.$tptfields[218]->FieldName.'3>'.$data6->Address4.'</'.$tptfields[218]->FieldName.'3></td></tr>';
			}
		}
		if($tptfields[219]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[219]->FieldName.'1>'.$tptfields[219]->CpCode.'</'.$tptfields[219]->FieldName.'1></td><td><'.$tptfields[219]->FieldName.'2>'.$tptfields[219]->NewDisplayName.'</'.$tptfields[219]->FieldName.'2></td><td><'.$tptfields[219]->FieldName.'3>'.$data6->C_Code.' || '.$data6->C_Description.'</'.$tptfields[219]->FieldName.'3></td></tr>';
		}
		if($tptfields[220]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[220]->FieldName.'1>'.$tptfields[220]->CpCode.'</'.$tptfields[220]->FieldName.'1></td><td><'.$tptfields[220]->FieldName.'2>'.$tptfields[220]->NewDisplayName.'</'.$tptfields[220]->FieldName.'2></td><td><'.$tptfields[220]->FieldName.'3>'.$data6->S_Code.' || '.$data6->S_Description.'</'.$tptfields[220]->FieldName.'3></td></tr>';
		}
		
		if($tptfields[229]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[229]->FieldName.'1>'.$tptfields[229]->CpCode.'</'.$tptfields[229]->FieldName.'1></td><td><'.$tptfields[229]->FieldName.'2>'.$tptfields[229]->NewDisplayName.'</'.$tptfields[229]->FieldName.'2></td><td><'.$tptfields[229]->FieldName.'3>'.$data6->LOA.'</'.$tptfields[229]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[229]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[229]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[229]->FieldName;		
			$data_arr['FieldValue']=$data6->LOA;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[230]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[230]->FieldName.'1>'.$tptfields[230]->CpCode.'</'.$tptfields[230]->FieldName.'1></td><td><'.$tptfields[230]->FieldName.'2>'.$tptfields[230]->NewDisplayName.'</'.$tptfields[230]->FieldName.'2></td><td><'.$tptfields[230]->FieldName.'3>'.$data6->Beam.'</'.$tptfields[230]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[230]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[230]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[230]->FieldName;		
			$data_arr['FieldValue']=$data6->Beam;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[231]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[231]->FieldName.'1>'.$tptfields[231]->CpCode.'</'.$tptfields[231]->FieldName.'1></td><td><'.$tptfields[231]->FieldName.'2>'.$tptfields[231]->NewDisplayName.'</'.$tptfields[231]->FieldName.'2></td><td><'.$tptfields[231]->FieldName.'3>'.$data6->Draft.'</'.$tptfields[231]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[231]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[231]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[231]->FieldName;		
			$data_arr['FieldValue']=$data6->Draft;				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[232]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[232]->FieldName.'1>'.$tptfields[232]->CpCode.'</'.$tptfields[232]->FieldName.'1></td><td><'.$tptfields[232]->FieldName.'2>'.$tptfields[232]->NewDisplayName.'</'.$tptfields[232]->FieldName.'2></td><td><'.$tptfields[232]->FieldName.'3>'.number_format($data6->DeadWeight).'</'.$tptfields[232]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[232]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[232]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[232]->FieldName;		
			$data_arr['FieldValue']=number_format($data6->DeadWeight);					
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[233]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[233]->FieldName.'1>'.$tptfields[233]->CpCode.'</'.$tptfields[233]->FieldName.'1></td><td><'.$tptfields[233]->FieldName.'2>'.$tptfields[233]->NewDisplayName.'</'.$tptfields[233]->FieldName.'2></td><td><'.$tptfields[233]->FieldName.'3>'.number_format($data6->Dispalcement,2).'</'.$tptfields[233]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[233]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[233]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[233]->FieldName;		
			$data_arr['FieldValue']=number_format($data6->Dispalcement,2);				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[234]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6">'.$tptfields[234]->CpCode.'</td><td> '.$tptfields[234]->NewDisplayName.'</td><td>'.$data6->Source.'</td></tr>';
			$data_arr['CpCode']=$tptfields[234]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[234]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[234]->FieldName;		
			$data_arr['FieldValue']=$data6->Source;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($data6->Source=='Rightship'){
		if($tptfields[235]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[235]->FieldName.'1>'.$tptfields[235]->CpCode.'</'.$tptfields[235]->FieldName.'1></td><td><'.$tptfields[235]->FieldName.'2>'.$tptfields[235]->NewDisplayName.'</'.$tptfields[235]->FieldName.'2></td><td><'.$tptfields[235]->FieldName.'3>'.$data6->Rating.'</'.$tptfields[235]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[235]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[235]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[235]->FieldName;		
			$data_arr['FieldValue']=$data6->Rating;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[236]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[236]->FieldName.'1>'.$tptfields[236]->CpCode.'</'.$tptfields[236]->FieldName.'1></td><td><'.$tptfields[236]->FieldName.'2>'.$tptfields[236]->NewDisplayName.'</'.$tptfields[236]->FieldName.'2></td><td><'.$tptfields[236]->FieldName.'3>'.date('d-m-Y',strtotime($data6->RatingDate)).'</'.$tptfields[236]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[236]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[236]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[236]->FieldName;		
			$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->RatingDate));				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
		} else if($data6->Source=='Other source'){
			if($tptfields[237]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[237]->FieldName.'1>'.$tptfields[237]->CpCode.'</'.$tptfields[237]->FieldName.'1></td><td><'.$tptfields[237]->FieldName.'2>'.$tptfields[237]->NewDisplayName.'</'.$tptfields[237]->FieldName.'2></td><td><'.$tptfields[237]->FieldName.'3>'.$data6->SourceType.'</'.$tptfields[237]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[237]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[237]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[237]->FieldName;		
				$data_arr['FieldValue']=$data6->SourceType;				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			
			if($data6->SourceType=='Third party'){
				if($tptfields[238]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[238]->FieldName.'1>'.$tptfields[238]->CpCode.'</'.$tptfields[238]->FieldName.'1></td><td><'.$tptfields[238]->FieldName.'2>'.$tptfields[238]->NewDisplayName.'</'.$tptfields[238]->FieldName.'2></td><td><'.$tptfields[238]->FieldName.'3>'.$data6->VettingSource.'</'.$tptfields[238]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[238]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[238]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[238]->FieldName;		
					$data_arr['FieldValue']=$data6->VettingSource;					
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
		}
		if($tptfields[239]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[239]->FieldName.'1>'.$tptfields[239]->CpCode.'</'.$tptfields[239]->FieldName.'1></td><td><'.$tptfields[239]->FieldName.'2>'.$tptfields[239]->NewDisplayName.'</'.$tptfields[239]->FieldName.'2></td><td><'.$tptfields[239]->FieldName.'3>'.$data6->Deficiency.'</'.$tptfields[239]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[239]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[239]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[239]->FieldName;		
			$data_arr['FieldValue']=$data6->Deficiency;				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
						
		if($data6->Deficiency == 'Outstanding' ){
			if($tptfields[240]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[240]->FieldName.'1>'.$tptfields[240]->CpCode.'</'.$tptfields[240]->FieldName.'1></td><td><'.$tptfields[240]->FieldName.'2>'.$tptfields[240]->NewDisplayName.'</'.$tptfields[240]->FieldName.'2></td><td><'.$tptfields[240]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DeficiencyCompDate)).'</'.$tptfields[240]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[240]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[240]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[240]->FieldName;		
				$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DeficiencyCompDate));					
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			
		}
		if($tptfields[241]->Included){
			$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[241]->FieldName.'1>'.$tptfields[241]->CpCode.'</'.$tptfields[241]->FieldName.'1></td><td><'.$tptfields[241]->FieldName.'2>'.$tptfields[241]->NewDisplayName.'</'.$tptfields[241]->FieldName.'2></td><td><'.$tptfields[241]->FieldName.'3>'.$data6->DetentionFlag.'</'.$tptfields[241]->FieldName.'3></td></tr>';
			$data_arr['CpCode']=$tptfields[241]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[241]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[241]->FieldName;		
			$data_arr['FieldValue']=$data6->DetentionFlag;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
				
		if($data6->DetentionFlag == 'Yes'){
			if($tptfields[242]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[242]->FieldName.'1>'.$tptfields[242]->CpCode.'</'.$tptfields[242]->FieldName.'1></td><td><'.$tptfields[242]->FieldName.'2>'.$tptfields[242]->NewDisplayName.'</'.$tptfields[242]->FieldName.'2></td><td><'.$tptfields[242]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionDate)).'</'.$tptfields[242]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[242]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[242]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[242]->FieldName;		
				$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionDate));				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[243]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[243]->FieldName.'1>'.$tptfields[243]->CpCode.'</'.$tptfields[243]->FieldName.'1></td><td><'.$tptfields[243]->FieldName.'2>'.$tptfields[243]->NewDisplayName.'</'.$tptfields[243]->FieldName.'2></td><td><'.$tptfields[243]->FieldName.'3>'.$data6->DetentionLiftedFlag.'</'.$tptfields[243]->FieldName.'3></td></tr>';
				$data_arr['CpCode']=$tptfields[243]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[243]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[243]->FieldName;		
				$data_arr['FieldValue']=$data6->DetentionLiftedFlag;				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			if($data6->DetentionLiftedFlag == 'Yes' ) {
				if($tptfields[244]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[244]->FieldName.'1>'.$tptfields[244]->CpCode.'</'.$tptfields[244]->FieldName.'1></td><td><'.$tptfields[244]->FieldName.'2>'.$tptfields[244]->NewDisplayName.'</'.$tptfields[244]->FieldName.'2></td><td><'.$tptfields[244]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionLiftedDate)).'</'.$tptfields[244]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[244]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[244]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[244]->FieldName;		
					$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionLiftedDate));				
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($data6->DetentionLiftedFlag == 'No' ) {
				if($tptfields[245]->Included){
					$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[245]->FieldName.'1>'.$tptfields[245]->CpCode.'</'.$tptfields[245]->FieldName.'1></td><td><'.$tptfields[245]->FieldName.'2>'.$tptfields[245]->NewDisplayName.'</'.$tptfields[245]->FieldName.'2></td><td><'.$tptfields[245]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionLiftExpectedDate)).'</'.$tptfields[245]->FieldName.'3></td></tr>';
					$data_arr['CpCode']=$tptfields[245]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[245]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[245]->FieldName;		
					$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionLiftExpectedDate));
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
		}
					
		}
		
		if(count($bankDetailsInvitee)>0) {
		$bank_name='';
		$bank_address1='';
		$bank_address2='';
		$bank_address3='';
		$bank_address4='';
		$bank_country='';
		$bank_state='';
		$bank_city='';
		$bank_pincode='';
		$account_name='';
		$account_number='';
		$currencty_of_payment='';
		$correspondent_bank1='';
		$correspondent_bank2='';
		$bank_code='';
		$bank_branch_code='';
		$swift_bic_code='';
		$ifsc_code='';
		$bank_iban='';
		$sort_code='';
		$aba_number='';
		$bank_detail_applies_to='';
		foreach($bankDetailsInvitee as $row) {
			$bank_name .=  $row->BankName.' || ';
			$bank_address1 .=  $row->BankAddress1.' || ';
			$bank_address2 .=  $row->BankAddress2.' || ';
			$bank_address3 .=  $row->BankAddress3.' || ';
			$bank_address4 .=  $row->BankAddress4.' || ';
			$bank_country .=  $row->country.' || ';
			$bank_state .=  $row->state.' || ';
			$bank_city .=  $row->City.' || ';
			$bank_pincode .=  $row->ZipCode.' || ';
			$account_name .=  $row->AccountName.' || ';
			$account_number .=  $row->AccountNumber.' || ';
			$currencty_of_payment .=  $row->currency.' || ';
			$correspondent_bank1 .=  $row->CorrespondentBank1.' || ';
			$correspondent_bank2 .=  $row->CorrespondentBank2.' || ';
			$bank_code .=  $row->BankCode.' || ';
			$bank_branch_code .=  $row->BankBranchCode.' || ';
			$swift_bic_code .=  $row->SwiftCode.' || ';
			$ifsc_code .=  $row->IfscCode.' || ';
			$bank_iban .=  $row->IbanCode.' || ';
			$sort_code .=  $row->SortCode.' || ';
			$aba_number .=  $row->AbaNumber.' || ';
			$apl='';
			$AppliesTo=explode(',',$row->AppliesTo);
			for($i=0;$i<count($AppliesTo);$i++) {
				if($AppliesTo[$i]==1) {
					$apl .='Freight payment,';
				} else if($AppliesTo[$i]==2) {
					$apl .='Miscellaneous payment,';
				} else if($AppliesTo[$i]==3) {
					$apl .='Hire payment,';
				} else if($AppliesTo[$i]==4) {
					$apl .='Freight invoice,';
				} else if($AppliesTo[$i]==5) {
					$apl .='Miscellaneous invoice,';
				} else if($AppliesTo[$i]==6) {
					$apl .='Hire invoice,';
				}
			}
			$apl=trim($apl,',');
			$bank_detail_applies_to .=  $apl.' || ';
		}
		
		$bank_name=rtrim($bank_name,' || ');
		$bank_address1=rtrim($bank_address1,' || ');
		$bank_address2=rtrim($bank_address2,' || ');
		$bank_address3=rtrim($bank_address3,' || ');
		$bank_address4=rtrim($bank_address4,' || ');
		$bank_country=rtrim($bank_country,' || ');
		$bank_state=rtrim($bank_state,' || ');
		$bank_city=rtrim($bank_city,' || ');
		$bank_pincode=rtrim($bank_pincode,' || ');
		$account_name=rtrim($account_name,' || ');
		$account_number=rtrim($account_number,' || ');
		$currencty_of_payment=rtrim($currencty_of_payment,' || ');
		$correspondent_bank1=rtrim($correspondent_bank1,' || ');
		$correspondent_bank2=rtrim($correspondent_bank2,' || ');
		$bank_code=rtrim($bank_code,' || ');
		$bank_branch_code=rtrim($bank_branch_code,' || ');
		$swift_bic_code=rtrim($swift_bic_code,' || ');
		$ifsc_code=rtrim($ifsc_code,' || ');
		$bank_iban=rtrim($bank_iban,' || ');
		$sort_code=rtrim($sort_code,' || ');
		$aba_number=rtrim($aba_number,' || ');
		$bank_detail_applies_to=rtrim($bank_detail_applies_to,' || ');
		
		//$html .='<tr contenteditable="false" style="background-color: #efeaead6"><td></td><td>Invitee Bank Details</td><td></td></tr>';	
		
			if($tptfields[247]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[247]->FieldName.'1>'.$tptfields[247]->CpCode.'</'.$tptfields[247]->FieldName.'1></td><td><'.$tptfields[247]->FieldName.'2>'.$tptfields[247]->NewDisplayName.'</'.$tptfields[247]->FieldName.'2></td><td><'.$tptfields[247]->FieldName.'3>'.$bank_name.'</'.$tptfields[247]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[248]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[248]->FieldName.'1>'.$tptfields[248]->CpCode.'</'.$tptfields[248]->FieldName.'1></td><td><'.$tptfields[248]->FieldName.'2>'.$tptfields[248]->NewDisplayName.'</'.$tptfields[248]->FieldName.'2></td><td><'.$tptfields[248]->FieldName.'3>'.$bank_address1.'</'.$tptfields[248]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[249]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[249]->FieldName.'1>'.$tptfields[249]->CpCode.'</'.$tptfields[249]->FieldName.'1></td><td><'.$tptfields[249]->FieldName.'2>'.$tptfields[249]->NewDisplayName.'</'.$tptfields[249]->FieldName.'2></td><td><'.$tptfields[249]->FieldName.'3>'.$bank_address2.'</'.$tptfields[249]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[250]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[250]->FieldName.'1>'.$tptfields[250]->CpCode.'</'.$tptfields[250]->FieldName.'1></td><td><'.$tptfields[250]->FieldName.'2>'.$tptfields[250]->NewDisplayName.'</'.$tptfields[250]->FieldName.'2></td><td><'.$tptfields[250]->FieldName.'3>'.$bank_address3.'</'.$tptfields[250]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[251]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[251]->FieldName.'1>'.$tptfields[251]->CpCode.'</'.$tptfields[251]->FieldName.'1></td><td><'.$tptfields[251]->FieldName.'2>'.$tptfields[251]->NewDisplayName.'</'.$tptfields[251]->FieldName.'2></td><td><'.$tptfields[251]->FieldName.'3>'.$bank_address4.'</'.$tptfields[251]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[252]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[252]->FieldName.'1>'.$tptfields[252]->CpCode.'</'.$tptfields[252]->FieldName.'1></td><td><'.$tptfields[252]->FieldName.'2>'.$tptfields[252]->NewDisplayName.'</'.$tptfields[252]->FieldName.'2></td><td><'.$tptfields[252]->FieldName.'3>'.$bank_country.'</'.$tptfields[252]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[253]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[253]->FieldName.'1>'.$tptfields[253]->CpCode.'</'.$tptfields[253]->FieldName.'1></td><td><'.$tptfields[253]->FieldName.'2>'.$tptfields[253]->NewDisplayName.'</'.$tptfields[253]->FieldName.'2></td><td><'.$tptfields[253]->FieldName.'3>'.$bank_state.'</'.$tptfields[253]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[254]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[254]->FieldName.'1>'.$tptfields[254]->CpCode.'</'.$tptfields[254]->FieldName.'1></td><td><'.$tptfields[254]->FieldName.'2>'.$tptfields[254]->NewDisplayName.'</'.$tptfields[254]->FieldName.'2></td><td><'.$tptfields[254]->FieldName.'3>'.$bank_city.'</'.$tptfields[254]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[255]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[255]->FieldName.'1>'.$tptfields[255]->CpCode.'</'.$tptfields[255]->FieldName.'1></td><td><'.$tptfields[255]->FieldName.'2>'.$tptfields[255]->NewDisplayName.'</'.$tptfields[255]->FieldName.'2></td><td><'.$tptfields[255]->FieldName.'3>'.$bank_pincode.'</'.$tptfields[255]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[256]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[256]->FieldName.'1>'.$tptfields[256]->CpCode.'</'.$tptfields[256]->FieldName.'1></td><td><'.$tptfields[256]->FieldName.'2>'.$tptfields[256]->NewDisplayName.'</'.$tptfields[256]->FieldName.'2></td><td><'.$tptfields[256]->FieldName.'3>'.$account_name.'</'.$tptfields[256]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[257]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[257]->FieldName.'1>'.$tptfields[257]->CpCode.'</'.$tptfields[257]->FieldName.'1></td><td><'.$tptfields[257]->FieldName.'2>'.$tptfields[257]->NewDisplayName.'</'.$tptfields[257]->FieldName.'2></td><td><'.$tptfields[257]->FieldName.'3>'.$account_number.'</'.$tptfields[257]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[258]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[258]->FieldName.'1>'.$tptfields[258]->CpCode.'</'.$tptfields[258]->FieldName.'1></td><td><'.$tptfields[258]->FieldName.'2>'.$tptfields[258]->NewDisplayName.'</'.$tptfields[258]->FieldName.'2></td><td><'.$tptfields[258]->FieldName.'3>'.$currencty_of_payment.'</'.$tptfields[258]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[259]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[259]->FieldName.'1>'.$tptfields[259]->CpCode.'</'.$tptfields[259]->FieldName.'1></td><td><'.$tptfields[259]->FieldName.'2>'.$tptfields[259]->NewDisplayName.'</'.$tptfields[259]->FieldName.'2></td><td><'.$tptfields[259]->FieldName.'3>'.$correspondent_bank1.'</'.$tptfields[259]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[260]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[260]->FieldName.'1>'.$tptfields[260]->CpCode.'</'.$tptfields[260]->FieldName.'1></td><td><'.$tptfields[260]->FieldName.'2>'.$tptfields[260]->NewDisplayName.'</'.$tptfields[260]->FieldName.'2></td><td><'.$tptfields[260]->FieldName.'3>'.$correspondent_bank2.'</'.$tptfields[260]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[261]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[261]->FieldName.'1>'.$tptfields[261]->CpCode.'</'.$tptfields[261]->FieldName.'1></td><td><'.$tptfields[261]->FieldName.'2>'.$tptfields[261]->NewDisplayName.'</'.$tptfields[261]->FieldName.'2></td><td><'.$tptfields[261]->FieldName.'3>'.$bank_code.'</'.$tptfields[261]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[262]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[262]->FieldName.'1>'.$tptfields[262]->CpCode.'</'.$tptfields[262]->FieldName.'1></td><td><'.$tptfields[262]->FieldName.'2>'.$tptfields[262]->NewDisplayName.'</'.$tptfields[262]->FieldName.'2></td><td><'.$tptfields[262]->FieldName.'3>'.$bank_branch_code.'</'.$tptfields[262]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[263]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[263]->FieldName.'1>'.$tptfields[263]->CpCode.'</'.$tptfields[263]->FieldName.'1></td><td><'.$tptfields[263]->FieldName.'2>'.$tptfields[263]->NewDisplayName.'</'.$tptfields[263]->FieldName.'2></td><td><'.$tptfields[263]->FieldName.'3>'.$swift_bic_code.'</'.$tptfields[263]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[264]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[264]->FieldName.'1>'.$tptfields[264]->CpCode.'</'.$tptfields[264]->FieldName.'1></td><td><'.$tptfields[264]->FieldName.'2>'.$tptfields[264]->NewDisplayName.'</'.$tptfields[264]->FieldName.'2></td><td><'.$tptfields[264]->FieldName.'3>'.$ifsc_code.'</'.$tptfields[264]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[265]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[265]->FieldName.'1>'.$tptfields[265]->CpCode.'</'.$tptfields[265]->FieldName.'1></td><td><'.$tptfields[265]->FieldName.'2>'.$tptfields[265]->NewDisplayName.'</'.$tptfields[265]->FieldName.'2></td><td><'.$tptfields[265]->FieldName.'3>'.$bank_iban.'</'.$tptfields[265]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[266]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[266]->FieldName.'1>'.$tptfields[266]->CpCode.'</'.$tptfields[266]->FieldName.'1></td><td><'.$tptfields[266]->FieldName.'2>'.$tptfields[266]->NewDisplayName.'</'.$tptfields[266]->FieldName.'2></td><td><'.$tptfields[266]->FieldName.'3>'.$sort_code.'</'.$tptfields[266]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[267]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[267]->FieldName.'1>'.$tptfields[267]->CpCode.'</'.$tptfields[267]->FieldName.'1></td><td><'.$tptfields[267]->FieldName.'2>'.$tptfields[267]->NewDisplayName.'</'.$tptfields[267]->FieldName.'2></td><td><'.$tptfields[267]->FieldName.'3>'.$aba_number.'</'.$tptfields[267]->FieldName.'3></td></tr>';
			}
			
			if($tptfields[268]->Included){
				$html .='<tr><td contenteditable="false" style="background-color: #efeaead6"><'.$tptfields[268]->FieldName.'1>'.$tptfields[268]->CpCode.'</'.$tptfields[268]->FieldName.'1></td><td><'.$tptfields[268]->FieldName.'2>'.$tptfields[268]->NewDisplayName.'</'.$tptfields[268]->FieldName.'2></td><td><'.$tptfields[268]->FieldName.'3>'.$bank_detail_applies_to.'</'.$tptfields[268]->FieldName.'3></td></tr>';
			}
			
			
		}
		
		$html .='</tbody></table>';
		 
	} else {
		if($tptfields[0]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Record_Owner_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[0]->NewDisplayName.' : '.$data1->EntityName.'</p>';
			} else {
				$html .='<p><'.$tptfields[0]->FieldName.'2>'.$tptfields[0]->NewDisplayName.'</'.$tptfields[0]->FieldName.'2> : <'.$tptfields[0]->FieldName.'3>'.$data1->EntityName.'</'.$tptfields[0]->FieldName.'3></p>';
			}
		
		}
		
		if($tptfields[1]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Role_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[1]->NewDisplayName.' : '.$data1->RoleDescription.'</p>';
			} else {
				$html .='<p><'.$tptfields[1]->FieldName.'2>'.$tptfields[1]->NewDisplayName.'</'.$tptfields[1]->FieldName.'2> : <'.$tptfields[1]->FieldName.'3>'.$data1->RoleDescription.'</'.$tptfields[1]->FieldName.'3></p>';
			}
		
		}
		
		if($tptfields[2]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='MasterID_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[2]->NewDisplayName.' : '.$data1->AuctionID.'</p>';
			} else {
				$html .='<p><'.$tptfields[2]->FieldName.'2>'.$tptfields[2]->NewDisplayName.'</'.$tptfields[2]->FieldName.'2> : <'.$tptfields[2]->FieldName.'3>'.$data1->AuctionID.'</'.$tptfields[2]->FieldName.'3></p>';
			}
		
		}
		if($tptfields[3]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='select_place_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[3]->NewDisplayName.' : Code : '.$data1->cCode.' || Description : '.$data1->cDescription.'</p>';
			} else {
				$html .='<p><'.$tptfields[3]->FieldName.'2>'.$tptfields[3]->NewDisplayName.'</'.$tptfields[3]->FieldName.'2> : <'.$tptfields[3]->FieldName.'3>Code : '.$data1->cCode.' || Description : '.$data1->cDescription.'</'.$tptfields[3]->FieldName.'3></p>';
			}
		
		}
		
		$UserSignDate='';	
		$SignFlag='';	
		if($data1->SignDateFlg==2) {
		$UserSignDate=date('d-m-Y',strtotime($data1->UserSignDate));
		$SignFlag='User specified date';
		} else {
		$UserSignDate='-';
		$SignFlag='As per system date';	
		}
		
		if($tptfields[4]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='signing_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[4]->NewDisplayName.' : '.$SignFlag.'</p>';
			} else {
				$html .='<p><'.$tptfields[4]->FieldName.'2>'.$tptfields[4]->NewDisplayName.'</'.$tptfields[4]->FieldName.'2> : <'.$tptfields[4]->FieldName.'3>'.$SignFlag.'</'.$tptfields[4]->FieldName.'3></p>';
			}
		
		}	
		
		if($tptfields[5]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='user_signing_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[5]->NewDisplayName.' : '.$UserSignDate.'</p>';
			} else {
				$html .='<p><'.$tptfields[5]->FieldName.'2>'.$tptfields[5]->NewDisplayName.'</'.$tptfields[5]->FieldName.'2> : <'.$tptfields[5]->FieldName.'3>'.$UserSignDate.'</'.$tptfields[5]->FieldName.'3></p>';
			}
		}
		
		$SelectFrom='';
		if($data1->SelectFrom==1) {
			$SelectFrom='Manual';
		} else if($data1->SelectFrom==2) {
			$SelectFrom='Import from Topmarx';
		} else if($data1->SelectFrom==3) {
			$SelectFrom='Import from Customer(BHP Billiton Freight) System';
		}
		
		if($tptfields[6]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_charter_from_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[6]->NewDisplayName.' : '.$SelectFrom.'</p>';
			} else {
				$html .='<p><'.$tptfields[6]->FieldName.'2>'.$tptfields[6]->NewDisplayName.'</'.$tptfields[6]->FieldName.'2> : <'.$tptfields[6]->FieldName.'3>'.$SelectFrom.'</'.$tptfields[6]->FieldName.'3></p>';
			}
		
		}
		
		
		$contracttype='';
		if($data1->ContractType==1) {
			$contracttype='Spot';
		}
		if($data1->ContractType==2) {
			$contracttype='Contract';
		}
		
		if($tptfields[7]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Contract_type_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[7]->NewDisplayName.' : '.$contracttype.'</p>';
			} else {
				$html .='<p><'.$tptfields[7]->FieldName.'2>'.$tptfields[7]->NewDisplayName.'</'.$tptfields[7]->FieldName.'2> : <'.$tptfields[7]->FieldName.'3>'.$contracttype.'</'.$tptfields[7]->FieldName.'3></p>';
			}
		
		}
		
		if($data1->COAReference){
		if($tptfields[8]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Contract_COA_reference_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[8]->NewDisplayName.' : '.$data1->COAReference.'</p>';
			} else {
				$html .='<p><'.$tptfields[8]->FieldName.'2>'.$tptfields[8]->NewDisplayName.'</'.$tptfields[8]->FieldName.'2> : <'.$tptfields[8]->FieldName.'3>'.$data1->COAReference.'</'.$tptfields[8]->FieldName.'3></p>';
			}
		
		}
		} 
		
		if($data1->SalesAgreementReference !=''){
		if($tptfields[9]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Sales_agreement_reference_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[9]->NewDisplayName.' : '.$data1->SalesAgreementReference.'</p>';
			} else {
				$html .='<p><'.$tptfields[9]->FieldName.'2>'.$tptfields[9]->NewDisplayName.'</'.$tptfields[9]->FieldName.'2> : <'.$tptfields[9]->FieldName.'3>'.$data1->SalesAgreementReference.'</'.$tptfields[9]->FieldName.'3></p>';
			}
		
		}
		} 
		
		$ModelFunction='';
		if($data1->ModelFunction==1){
			$ModelFunction='Default (all charters)';
		} else if($data1->ModelFunction==2){
			$ModelFunction='User selected (individual charters)';
		}
		
		if($tptfields[10]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_model_type_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[10]->NewDisplayName.' : '.$ModelFunction.'</p>';
			} else {
				$html .='<p><'.$tptfields[10]->FieldName.'2>'.$tptfields[10]->NewDisplayName.'</'.$tptfields[10]->FieldName.'2> : <'.$tptfields[10]->FieldName.'3>'.$ModelFunction.'</'.$tptfields[10]->FieldName.'3></p>';
			}
		
		}
		
		$mdlRow=$this->cargo_quote_model->getModelName($data1->ModelNumber);
		
		if($mdlRow){
		if($tptfields[11]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Select_model_name_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[11]->NewDisplayName.' : '.$mdlRow->ModelNumber.'</p>';
			} else {
				$html .='<p><'.$tptfields[11]->FieldName.'2>'.$tptfields[11]->NewDisplayName.'</'.$tptfields[11]->FieldName.'2> : <'.$tptfields[11]->FieldName.'3>'.$mdlRow->ModelNumber.'</'.$tptfields[11]->FieldName.'3></p>';
			}
		
		}
		}
		
		if($data1->ShipmentReferenceID !=''){
			if($tptfields[12]->Included){
				$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='Shipment_Reference_ID_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[12]->NewDisplayName.' : '.$data1->ShipmentReferenceID.'</p>';
			} else {
				$html .='<p><'.$tptfields[12]->FieldName.'2>'.$tptfields[12]->NewDisplayName.'</'.$tptfields[12]->FieldName.'2> : <'.$tptfields[12]->FieldName.'3>'.$data1->ShipmentReferenceID.'</'.$tptfields[12]->FieldName.'3></p>';
			}
			
			}
		}
		
		if($data2) {
			$html .='<p>Other Reference ID</p>';
		}
		
		$bank_name='';
		$bank_address1='';
		$bank_address2='';
		$bank_address3='';
		$bank_address4='';
		$bank_country='';
		$bank_state='';
		$bank_city='';
		$bank_pincode='';
		$account_name='';
		$account_number='';
		$currencty_of_payment='';
		$correspondent_bank1='';
		$correspondent_bank2='';
		$bank_code='';
		$bank_branch_code='';
		$swift_bic_code='';
		$ifsc_code='';
		$bank_iban='';
		$sort_code='';
		$aba_number='';
		$bank_detail_applies_to='';
		foreach($bankDetails as $row) {
			$bank_name .=  $row->BankName.' || ';
			$bank_address1 .=  $row->BankAddress1.' || ';
			$bank_address2 .=  $row->BankAddress2.' || ';
			$bank_address3 .=  $row->BankAddress3.' || ';
			$bank_address4 .=  $row->BankAddress4.' || ';
			$bank_country .=  $row->country.' || ';
			$bank_state .=  $row->state.' || ';
			$bank_city .=  $row->City.' || ';
			$bank_pincode .=  $row->ZipCode.' || ';
			$account_name .=  $row->AccountName.' || ';
			$account_number .=  $row->AccountNumber.' || ';
			$currencty_of_payment .=  $row->currency.' || ';
			$correspondent_bank1 .=  $row->CorrespondentBank1.' || ';
			$correspondent_bank2 .=  $row->CorrespondentBank2.' || ';
			$bank_code .=  $row->BankCode.' || ';
			$bank_branch_code .=  $row->BankBranchCode.' || ';
			$swift_bic_code .=  $row->SwiftCode.' || ';
			$ifsc_code .=  $row->IfscCode.' || ';
			$bank_iban .=  $row->IbanCode.' || ';
			$sort_code .=  $row->SortCode.' || ';
			$aba_number .=  $row->AbaNumber.' || ';
			$apl='';
			$AppliesTo=explode(',',$row->AppliesTo);
			for($i=0;$i<count($AppliesTo);$i++) {
				if($AppliesTo[$i]==1) {
					$apl .='Freight payment,';
				} else if($AppliesTo[$i]==2) {
					$apl .='Miscellaneous payment,';
				} else if($AppliesTo[$i]==3) {
					$apl .='Hire payment,';
				} else if($AppliesTo[$i]==4) {
					$apl .='Freight invoice,';
				} else if($AppliesTo[$i]==5) {
					$apl .='Miscellaneous invoice,';
				} else if($AppliesTo[$i]==6) {
					$apl .='Hire invoice,';
				}
			}
			$apl=trim($apl,',');
			$bank_detail_applies_to .=  $apl.' || ';
		}
		
		$bank_name=rtrim($bank_name,' || ');
		$bank_address1=rtrim($bank_address1,' || ');
		$bank_address2=rtrim($bank_address2,' || ');
		$bank_address3=rtrim($bank_address3,' || ');
		$bank_address4=rtrim($bank_address4,' || ');
		$bank_country=rtrim($bank_country,' || ');
		$bank_state=rtrim($bank_state,' || ');
		$bank_city=rtrim($bank_city,' || ');
		$bank_pincode=rtrim($bank_pincode,' || ');
		$account_name=rtrim($account_name,' || ');
		$account_number=rtrim($account_number,' || ');
		$currencty_of_payment=rtrim($currencty_of_payment,' || ');
		$correspondent_bank1=rtrim($correspondent_bank1,' || ');
		$correspondent_bank2=rtrim($correspondent_bank2,' || ');
		$bank_code=rtrim($bank_code,' || ');
		$bank_branch_code=rtrim($bank_branch_code,' || ');
		$swift_bic_code=rtrim($swift_bic_code,' || ');
		$ifsc_code=rtrim($ifsc_code,' || ');
		$bank_iban=rtrim($bank_iban,' || ');
		$sort_code=rtrim($sort_code,' || ');
		$aba_number=rtrim($aba_number,' || ');
		$bank_detail_applies_to=rtrim($bank_detail_applies_to,' || ');
			
			//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Charter Bank Details</b></p>';
			
			if($tptfields[13]->Included){
			$html .='<p><'.$tptfields[13]->FieldName.'2>'.$tptfields[13]->NewDisplayName.'</'.$tptfields[13]->FieldName.'2> : <'.$tptfields[13]->FieldName.'3>'.$bank_name.'</'.$tptfields[13]->FieldName.'3></p>';
			}
		
		
			if($tptfields[14]->Included){
			$html .='<p><'.$tptfields[14]->FieldName.'2>'.$tptfields[14]->NewDisplayName.'</'.$tptfields[14]->FieldName.'2> : <'.$tptfields[14]->FieldName.'3>'.$bank_address1.'</'.$tptfields[14]->FieldName.'3></p>';
			}
		
		
			if($tptfields[15]->Included){
			$html .='<p><'.$tptfields[15]->FieldName.'2>'.$tptfields[15]->NewDisplayName.'</'.$tptfields[15]->FieldName.'2> : <'.$tptfields[15]->FieldName.'3>'.$bank_address2.'</'.$tptfields[15]->FieldName.'3></p>';
			}
		
		
			if($tptfields[16]->Included){
			$html .='<p><'.$tptfields[16]->FieldName.'2>'.$tptfields[16]->NewDisplayName.'</'.$tptfields[16]->FieldName.'2> : <'.$tptfields[16]->FieldName.'3>'.$bank_address3.'</'.$tptfields[16]->FieldName.'3></p>';
			}
		
		
			if($tptfields[17]->Included){
			$html .='<p><'.$tptfields[17]->FieldName.'2>'.$tptfields[17]->NewDisplayName.'</'.$tptfields[17]->FieldName.'2> : <'.$tptfields[17]->FieldName.'3>'.$bank_address4.'</'.$tptfields[17]->FieldName.'3></p>';
			}
		
		
			if($tptfields[18]->Included){
			$html .='<p><'.$tptfields[18]->FieldName.'2>'.$tptfields[18]->NewDisplayName.'</'.$tptfields[18]->FieldName.'2> : <'.$tptfields[18]->FieldName.'3>'.$bank_country.'</'.$tptfields[18]->FieldName.'3></p>';
			}
		
		
			if($tptfields[19]->Included){
			$html .='<p><'.$tptfields[19]->FieldName.'2>'.$tptfields[19]->NewDisplayName.'</'.$tptfields[19]->FieldName.'2> : <'.$tptfields[19]->FieldName.'3>'.$bank_state.'</'.$tptfields[19]->FieldName.'3></p>';
			}
		
		
			if($tptfields[20]->Included){
			$html .='<p><'.$tptfields[20]->FieldName.'2>'.$tptfields[20]->NewDisplayName.'</'.$tptfields[20]->FieldName.'2> : <'.$tptfields[20]->FieldName.'3>'.$bank_city.'</'.$tptfields[20]->FieldName.'3></p>';
			}
		
		
			if($tptfields[21]->Included){
			$html .='<p><'.$tptfields[21]->FieldName.'2>'.$tptfields[21]->NewDisplayName.'</'.$tptfields[21]->FieldName.'2> : <'.$tptfields[21]->FieldName.'3>'.$bank_pincode.'</'.$tptfields[21]->FieldName.'3></p>';
			}
	
	
			if($tptfields[22]->Included){
			$html .='<p><'.$tptfields[22]->FieldName.'2>'.$tptfields[22]->NewDisplayName.'</'.$tptfields[22]->FieldName.'2> : <'.$tptfields[22]->FieldName.'3>'.$account_name.'</'.$tptfields[22]->FieldName.'3></p>';
			}
		
		
			if($tptfields[23]->Included){
			$html .='<p><'.$tptfields[23]->FieldName.'2>'.$tptfields[23]->NewDisplayName.'</'.$tptfields[23]->FieldName.'2> : <'.$tptfields[23]->FieldName.'3>'.$account_number.'</'.$tptfields[23]->FieldName.'3></p>';
			}
		
		
			if($tptfields[24]->Included){
			$html .='<p><'.$tptfields[24]->FieldName.'2>'.$tptfields[24]->NewDisplayName.'</'.$tptfields[24]->FieldName.'2> : <'.$tptfields[24]->FieldName.'3>'.$currencty_of_payment.'</'.$tptfields[24]->FieldName.'3></p>';
			}
		
		
			if($tptfields[25]->Included){
			$html .='<p><'.$tptfields[25]->FieldName.'2>'.$tptfields[25]->NewDisplayName.'</'.$tptfields[25]->FieldName.'2> : <'.$tptfields[25]->FieldName.'3>'.$correspondent_bank1.'</'.$tptfields[25]->FieldName.'3></p>';
			}
		
		
			if($tptfields[26]->Included){
			$html .='<p><'.$tptfields[26]->FieldName.'2>'.$tptfields[26]->NewDisplayName.'</'.$tptfields[26]->FieldName.'2> : <'.$tptfields[26]->FieldName.'3>'.$correspondent_bank2.'</'.$tptfields[26]->FieldName.'3></p>';
			}
		
		
			if($tptfields[27]->Included){
			$html .='<p><'.$tptfields[27]->FieldName.'2>'.$tptfields[27]->NewDisplayName.'</'.$tptfields[27]->FieldName.'2> : <'.$tptfields[27]->FieldName.'3>'.$bank_code.'</'.$tptfields[27]->FieldName.'3></p>';
			}
		
		
			if($tptfields[28]->Included){
			$html .='<p><'.$tptfields[28]->FieldName.'2>'.$tptfields[28]->NewDisplayName.'</'.$tptfields[28]->FieldName.'2> : <'.$tptfields[28]->FieldName.'3>'.$bank_branch_code.'</'.$tptfields[28]->FieldName.'3></p>';
			}
		
		
			if($tptfields[29]->Included){
			$html .='<p><'.$tptfields[29]->FieldName.'2>'.$tptfields[29]->NewDisplayName.'</'.$tptfields[29]->FieldName.'2> : <'.$tptfields[29]->FieldName.'3>'.$swift_bic_code.'</'.$tptfields[29]->FieldName.'3></p>';
			}
		
		
			if($tptfields[30]->Included){
			$html .='<p><'.$tptfields[30]->FieldName.'2>'.$tptfields[30]->NewDisplayName.'</'.$tptfields[30]->FieldName.'2> : <'.$tptfields[30]->FieldName.'3>'.$ifsc_code.'</'.$tptfields[30]->FieldName.'3></p>';
			}
		
		
			if($tptfields[31]->Included){
			$html .='<p><'.$tptfields[31]->FieldName.'2>'.$tptfields[31]->NewDisplayName.'</'.$tptfields[31]->FieldName.'2> : <'.$tptfields[31]->FieldName.'3>'.$bank_iban.'</'.$tptfields[31]->FieldName.'3></p>';
			}
			
			if($tptfields[32]->Included){
			$html .='<p><'.$tptfields[32]->FieldName.'2>'.$tptfields[32]->NewDisplayName.'</'.$tptfields[32]->FieldName.'2> : <'.$tptfields[32]->FieldName.'3>'.$sort_code.'</'.$tptfields[32]->FieldName.'3></p>';
			}
		
			if($tptfields[33]->Included){
			$html .='<p><'.$tptfields[33]->FieldName.'2>'.$tptfields[33]->NewDisplayName.'</'.$tptfields[33]->FieldName.'2> : <'.$tptfields[33]->FieldName.'3>'.$aba_number.'</'.$tptfields[33]->FieldName.'3></p>';
			}
		
			if($tptfields[34]->Included){
			$html .='<p><'.$tptfields[34]->FieldName.'2>'.$tptfields[34]->NewDisplayName.'</'.$tptfields[34]->FieldName.'2> : <'.$tptfields[34]->FieldName.'3>'.$bank_detail_applies_to.'</'.$tptfields[34]->FieldName.'3></p>';
			}
		
		$cargo='';
		$CargoQtyMT='';
		$CargoLoadedBasis='';
		$CargoLimitBasis='';
		$CargoLimitBasisFlag1=0;
		$CargoLimitBasisFlag2=0;
		$MaxCargoMT='';
		$MinCargoMT='';
		$ToleranceLimit='';
		$UpperLimit='';
		$LowerLimit='';
		$lpPortName='';
		$LpLaycanStartDate='';
		$LpLaycanEndDate='';
		$LpPreferDate='';
		$ExpectedLpDelayDay='';
		$ldtCode='';
		$LoadingRateMT='';
		$LoadingRateUOM='';
		$LoadingRateUOMFlag=0;
		$LpMaxTime='';
		$LpLaytimeType='';
		$LpCalculationBasedOn='';
		$ftCode='';
		$LpPriorUseTerms='';
		$LpLaytimeBasedOn='';
		$LpCharterType='';
		$cnrCode='';
		$StevedoringTermsLp='';
		$LoadPortEventName='';
		$LoadPortLaytimeCountsOnDemurrage='';
		$LoadPortLaytimeCounts='';
		$LoadPortTimeCounting='';
		$LoadPortCreateNewOrSelectListTendering='';
		$LoadPortNORTenderingPreCondition='';
		$LoadPortTenderingStatus='';
		$LoadPortCreateNewOrSelectListAcceptance='';
		$LoadPortNORAcceptancePreCondition='';
		$LoadPortAcceptanceStatus='';
		$LoadPortOfficeDateFrom='';
		$LoadPortOfficeDateTo='';
		$LoadPortOfficeTimeFrom='';
		$LoadPortOfficeTimeTo='';
		$LoadPortLaytimeDayFrom='';
		$LoadPortLaytimeDayTo='';
		$LoadPortLaytimeTimeFrom='';
		$LoadPortLaytimeTimeTo='';
		$LoadPortLaytimeTurnTime='';
		$LoadPortLaytimeTurnTimeExpire='';
		$LoadPortLaytimeCommenceAt='';
		$LoadPortLaytimeCommenceAtHour='';
		$LoadPortLaytimeSelectDay='';
		$LoadPortLaytimeTimeCountsIfOnDemurrage='';
		$DisPort='';
		$DpArrivalStartDate='';
		$DpArrivalEndDate='';
		$DpPreferDate='';
		$ExpectedDpDelayDay='';
		$DischargingTerms='';
		$DischargingRateMT='';
		$DischargingRateUOM='';
		$DpMaxTime='';
		$DpLaytimeType='';
		$DpCalculationBasedOn='';
		$DpTurnTime='';
		$DpPriorUseTerms='';
		$DpLaytimeBasedOn='';
		$DpCharterType='';
		$DpNorTendering='';
		$DpStevedoringTerms='';
		$DisportEventName='';
		$DisportLaytimeCountsOnDemurrage='';
		$DisportLaytimeCounts='';
		$DisportTimeCounting='';
		$DisportCreateNewOrSelectListTendering='';
		$DisportNORTenderingPreCondition='';
		$DisportTenderingStatus='';
		$DisportCreateNewOrSelectListAcceptance='';
		$DisportNORAcceptancePreCondition='';
		$DisportAcceptanceStatus='';
		$DisportOfficeDateFrom='';
		$DisportOfficeDateTo='';
		$DisportOfficeTimeFrom='';
		$DisportOfficeTimeTo='';
		$DisportLaytimeDayFrom='';
		$DisportLaytimeDayTo='';
		$DisportLaytimeTimeFrom='';
		$DisportLaytimeTimeTo='';
		$DisportLaytimeTurnTime='';
		$DisportLaytimeTurnTimeExpire='';
		$DisportLaytimeCommenceAt='';
		$DisportLaytimeCommenceAtHour='';
		$DisportLaytimeSelectDay='';
		$DisportLaytimeTimeCountsIfOnDemurrage='';
		
		$BrokeragePayingEntityType='';
		$BrokeragePayingEntityName='';
		$BrokerageReceivingEntityType='';
		$BrokerageReceivingEntityName='';
		$BrokerageBrokerName='';
		$BrokeragePayableAs='';
		$BrokeragePercentageOnFreight='';
		$BrokeragePercentageOnDeadFreight='';
		$BrokeragePercentageOnDemmurage='';
		$BrokeragePercentageOnOverage='';
		$BrokerageLumpsumPayable='';
		$BrokerageRatePerTonnePayable='';
		
		$AddCommPayingEntityType='';
		$AddCommPayingEntityName='';
		$AddCommReceivingEntityType='';
		$AddCommReceivingEntityName='';
		$AddCommBrokerName='';
		$AddCommPayableAs='';
		$AddCommPercentageOnFreight='';
		$AddCommPercentageOnDeadFreight='';
		$AddCommPercentageOnDemmurage='';
		$AddCommPercentageOnOverage='';
		$AddCommLumpsumPayable='';
		$AddCommRatePerTonnePayable='';
		
		$OtherPayingEntityType='';
		$OtherPayingEntityName='';
		$OtherReceivingEntityType='';
		$OtherReceivingEntityName='';
		$OtherBrokerName='';
		$OtherPayableAs='';
		$OtherPercentageOnFreight='';
		$OtherPercentageOnDeadFreight='';
		$OtherPercentageOnDemmurage='';
		$OtherPercentageOnOverage='';
		$OtherLumpsumPayable='';
		$OtherRatePerTonnePayable='';
		
		
		if($data3){
			$templinenum='';
		foreach($data3 as $row) {
			if($templinenum==$row->LineNum){
				continue;
			}
			$templinenum=$row->LineNum;
			
			$cargo .=$row->Code.' || ';
			$CargoQtyMT .=number_format($row->CargoQtyMT,2).' || ';
			$CargoLoadedBasis .=$row->CargoLoadedBasis.' || ';
			if($row->CargoLimitBasis==1){
			$CargoLimitBasis1='Max and Min';
			} else if($row->CargoLimitBasis==2){
			$CargoLimitBasis1='% Tolerance limit';	
			}
			$CargoLimitBasis .=$CargoLimitBasis1.' || ';
			
			if($row->CargoLimitBasis==1){
				$CargoLimitBasisFlag1=1;
			} else if($row->CargoLimitBasis==2){
				$CargoLimitBasisFlag2=1;
			}
			$MaxCargoMT .=number_format($row->MaxCargoMT,2).' || ';
			$MinCargoMT .=number_format($row->MinCargoMT,2).' || ';
			$ToleranceLimit .=number_format($row->ToleranceLimit,2).' || ';
			$UpperLimit .=number_format($row->UpperLimit,2).' || ';
			$LowerLimit .=number_format($row->LowerLimit,2).' || ';
			$lpPortName .=$row->lpPortName.' || ';
			$LpLaycanStartDate .=date('d-m-Y H:i:s',strtotime($row->LpLaycanStartDate)).' || ';
			$LpLaycanEndDate .=date('d-m-Y H:i:s',strtotime($row->LpLaycanEndDate)).' || ';
			$LpPreferDate .=date('d-m-Y H:i:s',strtotime($row->LpPreferDate)).' || ';
			$ExpectedLpDelayDay .=$row->ExpectedLpDelayDay.' days '.$row->ExpectedLpDelayHour.' hours'.' || ';
			$ldtCode .=$row->ldtCode.' || ';
			$LoadingRateMT .=number_format($row->LoadingRateMT,2).' || ';
			if($row->LoadingRateUOM==1){
				$LoadingRateUOM1='Per hour';
			}else if($row->LoadingRateUOM==2){
				$LoadingRateUOM1='Per weather working day';
			}else if($row->LoadingRateUOM==3){
				$LoadingRateUOM1='Max time limit';
			}
			$LoadingRateUOM .=$LoadingRateUOM1.' || ';
			
			if($row->LoadingRateUOM==3){
				$LoadingRateUOMFlag=1;
			}
			$LpMaxTime .=$row->LpMaxTime.' || ';
			
			if($row->LpLaytimeType==1){
				$LpLaytimeType1='Reversible';
			}else if($row->LpLaytimeType==2){
				$LpLaytimeType1='Non Reversible';
			}else if($row->LpLaytimeType==3){
				$LpLaytimeType1='Average';
			}
			$LpLaytimeType .=$LpLaytimeType1.' || ';
			if($row->LpCalculationBasedOn==108){
				$LpCalculationBasedOn1='Bill of Loading Quantity';
			}else if($row->LpCalculationBasedOn==109){
				$LpCalculationBasedOn1='Outturn or Discharge Quantity';
			}
			$LpCalculationBasedOn .=$LpCalculationBasedOn1.' || ';
			$ftCode .=$row->ftCode.' || ';
			if($row->LpPriorUseTerms==102){
				$LpPriorUseTerms1='IUATUTC (If Used Actual Time To Count)';
			}else if($row->LpPriorUseTerms==10){
				$LpPriorUseTerms1='IUHTUTC (If Used Half Time To Count)';
			}else{
				$LpPriorUseTerms1='N/A';
			}
			$LpPriorUseTerms .=$LpPriorUseTerms1.' || ';
			
			if($row->LpLaytimeBasedOn==1){
				$LpLaytimeBasedOn1='ATS (All Time Saved)';
			}else if($row->LpLaytimeBasedOn==2){
				$LpLaytimeBasedOn1='WTS (Working Time Saved)';
			}else{
				$LpLaytimeBasedOn1='N/A';
			}
			$LpLaytimeBasedOn .=$LpLaytimeBasedOn1.' || ';
			if($row->LpCharterType==1){
				$LpCharterType1='1 Safe Port 1 Safe Berth (1SP1SB)';
			}else if($row->LpCharterType==2){
				$LpCharterType1='1 Safe Port 2 Safe Berth (1SP2SB)';
			}else if($row->LpCharterType==3){
				$LpCharterType1='2 Safe Port 1 Safe Berth (2SP1SB)';
			}else if($row->LpCharterType==4){
				$LpCharterType1='2 Safe Port 2 Safe Berth (2SP2SB)';
			}
			$LpCharterType .=$LpCharterType1.' || ';
			$cnrCode .=$row->cnrCode.' || ';
			
			$StevedoringTermsLp1=$this->cargo_model->getStevedoringTermsByID($row->LpStevedoringTerms);
			$StevedoringTermsLp .=$StevedoringTermsLp1->Code.' || ';
			
			if($row->ExceptedPeriodFlg==1) {
			$ResponseExceptedPeriods1=$this->cargo_quote_model->ResponseExceptedPeriodsByID($row->ResponseCargoID);
			$evname='';
			$laycountdem='';
			$laycount='';
			$timecount='';
			foreach($ResponseExceptedPeriods1 as $rep) {
				$evname .=$rep->Code.' ('.$rep->Description.' ) , ';
				if($rep->LaytimeCountsOnDemurrageFlg==1) {
					$laycountdem .='Yes , ';
				} else if($rep->LaytimeCountsOnDemurrageFlg==2) {
					$laycountdem .='No , ';
				}
				if($rep->LaytimeCountsFlg==1) {
					$laycount .='Yes , ';
				} else if($rep->LaytimeCountsFlg==2) {
					$laycount .='No , ';
				}
				if($rep->TimeCountingFlg==102) {
					$timecount .='IUATUTC (If Used Actual Time To Count) , ';
				} else if($rep->TimeCountingFlg==10) {
					$timecount .='IUHTUTC (If Used Half Time To Count) , ';
				}
			}
			$evname=rtrim($evname,' , ');
			$laycountdem=rtrim($laycountdem,' , ');
			$laycount=rtrim($laycount,' , ');
			$timecount=rtrim($timecount,' , ');
			}
			$LoadPortEventName .=$evname.' || ';
			$LoadPortLaytimeCountsOnDemurrage .=$laycountdem.' || ';
			$LoadPortLaytimeCounts .=$laycount.' || ';
			$LoadPortTimeCounting .=$timecount.' || ';
			if($row->NORTenderingPreConditionFlg==1) {
				$NORTenderingPreConditions=$this->cargo_quote_model->ResponseNORTenderingPreConditionsByID($row->ResponseCargoID);
				$CreateNoreTend='';
				$ConditionNoreTend='';
				$ActiveNoreTend='';
				foreach($NORTenderingPreConditions as $nrtp) {
					if($nrtp->CreateNewOrSelectListFlg==1) {
						$CreateNoreTend .='create new , ';
						$ConditionNoreTend .=$nrtp->NewNORTenderingPreCondition.' , ';
					} else if($nrtp->CreateNewOrSelectListFlg==2) {
						$CreateNoreTend .='select from pre defined list , ';
						$ConditionNoreTend .=$nrtp->TenderingCode.' , ';
					} else {
						$CreateNoreTend .=' , ';
						$ConditionNoreTend .=' , ';
					}
					if($nrtp->StatusFlag==1) {
						$ActiveNoreTend .='Active , ';
					} else if($nrtp->StatusFlag==1) {
						$ActiveNoreTend .='In active , ';
					} else {
						$ActiveNoreTend .=' , ';
					}
				}
				$CreateNoreTend=rtrim($CreateNoreTend,' , ');
				$ConditionNoreTend=rtrim($ConditionNoreTend,' , ');
				$ActiveNoreTend=rtrim($ActiveNoreTend,' , ');
			}
			$LoadPortCreateNewOrSelectListTendering .=$CreateNoreTend.' || ';
			$LoadPortNORTenderingPreCondition .=$ConditionNoreTend.' || ';
			$LoadPortTenderingStatus .=$ActiveNoreTend.' || ';
			
			if($row->NORAcceptancePreConditionFlg==1) {
				$NORAcceptancePreConditions=$this->cargo_quote_model->ResponseNORAcceptancePreConditionsByID($row->ResponseCargoID);
				$CreateNorAccept='';
				$ConditionNorAccept='';
				$ActiveNorAccept='';
				foreach($NORAcceptancePreConditions as $nrapc) {
					if($nrapc->CreateNewOrSelectListFlg==1) {
						$CreateNorAccept .='create new , ';
						$ConditionNorAccept .=$nrapc->NewNORAcceptancePreCondition.' , ';
					} else if($nrapc->CreateNewOrSelectListFlg==2) {
						$CreateNorAccept .='select from pre defined list , ';
						$ConditionNorAccept .=$nrapc->AcceptanceCode.' , ';
					} else {
						$CreateNorAccept .=' , ';
						$ConditionNorAccept .=' , ';
					}
					if($nrapc->StatusFlag==1) {
						$ActiveNorAccept .='Active , ';
					} else if($nrapc->StatusFlag==1) {
						$ActiveNorAccept .='In active , ';
					} else {
						$ActiveNorAccept .=' , ';
					}
				}
				$CreateNorAccept=rtrim($CreateNorAccept,' , ');
				$ConditionNorAccept=rtrim($ConditionNorAccept,' , ');
				$ActiveNorAccept=rtrim($ActiveNorAccept,' , ');
			}
			$LoadPortCreateNewOrSelectListAcceptance .=$CreateNorAccept.' || ';
			$LoadPortNORAcceptancePreCondition .=$ConditionNorAccept.' || ';
			$LoadPortAcceptanceStatus .=$ActiveNorAccept.' || ';
			
			if($row->OfficeHoursFlg ==1) {
				$OfficeHours=$this->cargo_quote_model->ResponseOfficeHoursByID($row->ResponseCargoID);
				$OfficeDateFrom='';
				$OfficeDateTo='';
				$OfficeTimeFrom='';
				$OfficeTimeTo='';
				foreach($OfficeHours as $ofh) {
					$OfficeDateFrom .=$ofh->DateFrom.' , ';
					$OfficeDateTo .=$ofh->DateTo.' , ';
					$OfficeTimeFrom .=$ofh->TimeFrom.' , ';
					$OfficeTimeTo .=$ofh->TimeTo.' , ';
				}
				$OfficeDateFrom=rtrim($OfficeDateFrom,' , ');
				$OfficeDateTo=rtrim($OfficeDateTo,' , ');
				$OfficeTimeFrom=rtrim($OfficeTimeFrom,' , ');
				$OfficeTimeTo=rtrim($OfficeTimeTo,' , ');
			}
			$LoadPortOfficeDateFrom .=$OfficeDateFrom.' || ';
			$LoadPortOfficeDateTo .=$OfficeDateTo.' || ';
			$LoadPortOfficeTimeFrom .=$OfficeTimeFrom.' || ';
			$LoadPortOfficeTimeTo .=$OfficeTimeTo.' || ';
			
			if($row->LaytimeCommencementFlg==1) {
				$LaytimeCommencements=$this->cargo_quote_model->ResponseLaytimeCommencementsByID($row->ResponseCargoID);
				$LaytimeDayFrom='';
				$LaytimeDayTo='';
				$LaytimeTimeFrom='';
				$LaytimeTimeTo='';
				$LaytimeTurnTime='';
				$LaytimeTurnTimeExpire='';
				$LaytimeCommenceAt='';
				$LaytimeCommenceAtHour='';
				$LaytimeSelectDay='';
				$LaytimeTimeCountsIfOnDemurrage='';
				foreach($LaytimeCommencements as $ltcm) {
					$LaytimeDayFrom .=$ltcm->DayFrom.' , ';
					$LaytimeDayTo .=$ltcm->DayTo.' , ';
					$LaytimeTimeFrom .=$ltcm->TimeFrom.' , ';
					$LaytimeTimeTo .=$ltcm->TimeTo.' , ';
					$LaytimeTurnTime .=$ltcm->LaytimeCode.' , ';
					if($ltcm->TurnTimeExpire==1) {
					$LaytimeTurnTimeExpire .='During office hours , ';
					} else if($ltcm->TurnTimeExpire==2) {
					$LaytimeTurnTimeExpire .='After office hours , ';
					} else {
					$LaytimeTurnTimeExpire .=' , ';	
					}
					if($ltcm->LaytimeCommenceAt==1) {
						$LaytimeCommenceAt .='At expiry of turn time , ';
					} else if($ltcm->LaytimeCommenceAt==2) {
						$LaytimeCommenceAt .='At specified hour , ';
					} else {
						$LaytimeCommenceAt .=' , ';
					}
					
					$LaytimeCommenceAtHour .=$ltcm->LaytimeCommenceAtHour.' , ';
					if($ltcm->SelectDay==1) {
						$LaytimeSelectDay .='Same Day , ';
					} else if($ltcm->SelectDay==2) {
						$LaytimeSelectDay .='New Working Day , ';
					} else {
						$LaytimeSelectDay .=' , ';
					}
					
					if($ltcm->TimeCountsIfOnDemurrage==1) {
					$LaytimeTimeCountsIfOnDemurrage .='Yes , ';
					} else if($ltcm->TimeCountsIfOnDemurrage==2) {
					$LaytimeTimeCountsIfOnDemurrage .='No , ';	
					} else {
						$LaytimeTimeCountsIfOnDemurrage .=' , ';	
					}
				}
				$LaytimeDayFrom=rtrim($LaytimeDayFrom,' , ');
				$LaytimeDayTo=rtrim($LaytimeDayTo,' , ');
				$LaytimeTimeFrom=rtrim($LaytimeTimeFrom,' , ');
				$LaytimeTimeTo=rtrim($LaytimeTimeTo,' , ');
				$LaytimeTurnTime=rtrim($LaytimeTurnTime,' , ');
				$LaytimeTurnTimeExpire=rtrim($LaytimeTurnTimeExpire,' , ');
				$LaytimeCommenceAt=rtrim($LaytimeCommenceAt,' , ');
				$LaytimeCommenceAtHour=rtrim($LaytimeCommenceAtHour,' , ');
				$LaytimeSelectDay=rtrim($LaytimeSelectDay,' , ');
				$LaytimeTimeCountsIfOnDemurrage=rtrim($LaytimeTimeCountsIfOnDemurrage,' , ');
			}
			$LoadPortLaytimeDayFrom .=$LaytimeDayFrom.' || ';
			$LoadPortLaytimeDayTo .=$LaytimeDayTo.' || ';
			$LoadPortLaytimeTimeFrom .=$LaytimeTimeFrom.' || ';
			$LoadPortLaytimeTimeTo .=$LaytimeTimeTo.' || ';
			$LoadPortLaytimeTurnTime .=$LaytimeTurnTime.' || ';
			$LoadPortLaytimeTurnTimeExpire .=$LaytimeTurnTimeExpire.' || ';
			$LoadPortLaytimeCommenceAt .=$LaytimeCommenceAt.' || ';
			$LoadPortLaytimeCommenceAtHour .=$LaytimeCommenceAtHour.' || ';
			$LoadPortLaytimeSelectDay .=$LaytimeSelectDay.' || ';
			$LoadPortLaytimeTimeCountsIfOnDemurrage .=$LaytimeTimeCountsIfOnDemurrage.' || ';
			
			$diport_data=$this->cargo_quote_model->ResponseCargoDisportsByID($row->ResponseCargoID);
			$disport_coma='';
			$ArrivalStartDate='';
			$ArrivalEndDate='';
			$PreferDate='';
			$ExpectedDelayDay='';
			$DischTerm='';
			$DischRateMt='';
			$DischRateUOM='';
			$MaxTime='';
			$LaytimeType='';
			$CalculationBasedOn='';
			$TurnTime='';
			$PriorUseTerms='';
			$LaytimeBasedOn='';
			$CharterType='';
			$NorTendering='';
			$StevedoringTerms='';
			$evnamedpall='';
			$laycountdemdpall='';
			$laycountdpall='';
			$timecountdpall='';
			$CreateDpNoreTendall='';
			$ConditionDpNoreTendall='';
			$ActiveDpNoreTendall='';
			$CreateDpNorAcceptall='';
			$ConditionDpNorAcceptall='';
			$ActiveDpNorAcceptall='';
			$DpOfficeDateFromall='';
			$DpOfficeDateToall='';
			$DpOfficeTimeFromall='';
			$DpOfficeTimeToall='';
			$DpLaytimeDayFromall='';
			$DpLaytimeDayToall='';
			$DpLaytimeTimeFromall='';
			$DpLaytimeTimeToall='';
			$DpLaytimeTurnTimeall='';
			$DpLaytimeTurnTimeExpireall='';
			$DpLaytimeCommenceAtall='';
			$DpLaytimeCommenceAtHourall='';
			$DpLaytimeSelectDayall='';
			$DpLaytimeTimeCountsIfOnDemurrageall='';
			foreach($diport_data as $disd) {
				$disport_coma .=$disd->dpPortName.' , ';
				$ArrivalStartDate .=date('d-m-Y H:i:s',strtotime($disd->DpArrivalStartDate)).' , ';
				$ArrivalEndDate .=date('d-m-Y H:i:s',strtotime($disd->DpArrivalEndDate)).' , ';
				$PreferDate .=date('d-m-Y H:i:s',strtotime($disd->DpPreferDate)).' , ';
				$ExpectedDelayDay .=$disd->ExpectedDpDelayDay.' days '.$disd->ExpectedDpDelayHour.' hours'.' , ';
				$DischTerm .=$disd->ddtCode.' , ';
				$DischRateMt .=$disd->DischargingRateMT.' , ';
				if($disd->DischargingRateUOM==1) {
					$DischRateUOM .='Per hour , ';
				} else if($disd->DischargingRateUOM==2) {
					$DischRateUOM .='Per weather working day , ';
				} else if($disd->DischargingRateUOM==3) {
					$DischRateUOM .='Max time limit , ';
				} else {
					$DischRateUOM .=' , ';
				}
				$MaxTime .=$disd->DpMaxTime.' , ';
				if($disd->DpLaytimeType==1) {
					$LaytimeType .='Reversible , ';
				} else if($disd->DpLaytimeType==2) {
					$LaytimeType .='Non Reversible , ';
				} else if($disd->DpLaytimeType==3) {
					$LaytimeType .='Average , ';
				} else {
					$LaytimeType .=' , ';
				}
				if($disd->DpCalculationBasedOn==108) {
				$CalculationBasedOn .='Bill of Loading Quantity , ';	
				} else if($disd->DpCalculationBasedOn==109) {
				$CalculationBasedOn .='Outturn or Discharge Quantity , ';	
				} else {
				$CalculationBasedOn .=' , ';	
				}
				$TurnTime .=$disd->dftCode.' , ';
				if($disd->DpPriorUseTerms=102) {
					$PriorUseTerms .='IUATUTC (If Used Actual Time To Count) , ';
				} else if($disd->DpPriorUseTerms=10) {
					$PriorUseTerms .='IUHTUTC (If Used Half Time To Count) , ';
				} else {
					$PriorUseTerms .=' , ';
				}
				if($disd->DpLaytimeBasedOn==1) {
				$LaytimeBasedOn .='ATS (All Time Saved) , ';
				} else if($disd->DpLaytimeBasedOn==2) {
				$LaytimeBasedOn .='ATS (All Time Saved) , ';
				} else {
				$LaytimeBasedOn .=' , ';	
				}
				if($disd->DpCharterType==1) {
				$CharterType .='1 Safe Port 1 Safe Berth (1SP1SB) , ';
				} else if($disd->DpCharterType==2) {
				$CharterType .='1 Safe Port 2 Safe Berth (1SP2SB) , ';
				} else if($disd->DpCharterType==3) {
				$CharterType .='2 Safe Port 1 Safe Berth (2SP1SB) , ';
				} else if($disd->DpCharterType==4) {
				$CharterType .='2 Safe Port 2 Safe Berth (2SP2SB) , ';
				} else {
				$CharterType .=' , ';	
				}
				$NorTendering .=$disd->cnrDCode.' , ';
				
				$StevedoringTermsDp1=$this->cargo_model->getStevedoringTermsByID($disd->DpStevedoringTerms);
				$StevedoringTerms .=$StevedoringTermsDp1->Code.' , ';
			
			if($disd->DpExceptedPeriodFlg==1) {
			$ResponseExceptedPeriodsDp=$this->cargo_quote_model->ResponseExceptedPeriodsDpByID($disd->ResponseCargoID,$disd->RCD_ID);
			$evnamedp='';
			$laycountdemdp='';
			$laycountdp='';
			$timecountdp='';
			foreach($ResponseExceptedPeriodsDp as $repdp) {
				$evnamedp .=$repdp->Code.' , ';
				if($repdp->LaytimeCountsOnDemurrageFlg==1) {
					$laycountdemdp .='Yes , ';
				} else if($repdp->LaytimeCountsOnDemurrageFlg==2) {
					$laycountdemdp .='No , ';
				} else {
					$laycountdemdp .=' , ';
				}
				if($repdp->LaytimeCountsFlg==1) {
					$laycountdp .='Yes , ';
				} else if($repdp->LaytimeCountsFlg==2) {
					$laycountdp .='No , ';
				} else {
					$laycountdp .=' , ';
				}
				if($repdp->TimeCountingFlg==102) {
					$timecountdp .='IUATUTC , ';
				} else if($repdp->TimeCountingFlg==10) {
					$timecountdp .='IUHTUTC , ';
				} else {
					$timecountdp .=' , ';
				}
			}
			
			$evnamedp=rtrim($evnamedp,' , ');
			$laycountdemdp=rtrim($laycountdemdp,' , ');
			$laycountdp=rtrim($laycountdp,' , ');
			$timecountdp=rtrim($timecountdp,' , ');
			$evnamedpall .='( '.$evnamedp.' ) , ';
			$laycountdemdpall .='( '.$laycountdemdp.' ) , ';
			$laycountdpall .='( '.$laycountdp.' ) , ';
			$timecountdpall .='( '.$timecountdp.' ) , ';
			}
			
			if($disd->DpNORTenderingPreConditionFlg==1) {
				$DpNORTenderingPreConditions=$this->cargo_quote_model->ResponseDpNORTenderingPreConditionsByID($disd->ResponseCargoID,$disd->RCD_ID);
				$CreateDpNoreTend='';
				$ConditionDpNoreTend='';
				$ActiveDpNoreTend='';
				foreach($DpNORTenderingPreConditions as $dnrtp) {
					 if($dnrtp->CreateNewOrSelectListFlg==1) {
						$CreateDpNoreTend .='create new , ';
						$ConditionDpNoreTend .=$dnrtp->NewNORTenderingPreCondition.' , ';
					} else if($dnrtp->CreateNewOrSelectListFlg==2) {
						$CreateDpNoreTend .='select from pre defined list , ';
						$ConditionDpNoreTend .=$dnrtp->TenderingCode.' , ';
					} else {
						$CreateDpNoreTend .=' , ';
						$ConditionDpNoreTend .=' , ';
					}
					if($dnrtp->StatusFlag==1) {
						$ActiveDpNoreTend .='Active , ';
					} else if($dnrtp->StatusFlag==1) {
						$ActiveDpNoreTend .='In active , ';
					} else {
						$ActiveDpNoreTend .=' , ';
					}
				}
				$CreateDpNoreTend=rtrim($CreateDpNoreTend,' , ');
				$ConditionDpNoreTend=rtrim($ConditionDpNoreTend,' , ');
				$ActiveDpNoreTend=rtrim($ActiveDpNoreTend,' , ');
				$CreateDpNoreTendall .='( '.$CreateDpNoreTend.' ) , ';
				$ConditionDpNoreTendall .='( '.$ConditionDpNoreTend.' ) , ';
				$ActiveDpNoreTendall .='( '.$ActiveDpNoreTend.' ) , ';
			}
			
			if($disd->DpNORAcceptancePreConditionFlg==1) {
				$DpNORAcceptancePreConditions=$this->cargo_quote_model->ResponseDpNORAcceptancePreConditionsByID($disd->ResponseCargoID,$disd->RCD_ID);
				
				$CreateDpNorAccept='';
				$ConditionDpNorAccept='';
				$ActiveDpNorAccept='';
				foreach($DpNORAcceptancePreConditions as $dpnrapc) {
					if($dpnrapc->CreateNewOrSelectListFlg==1) {
						$CreateDpNorAccept .='create new , ';
						$ConditionDpNorAccept .=$dpnrapc->NewNORAcceptancePreCondition.' , ';
					} else if($dpnrapc->CreateNewOrSelectListFlg==2) {
						$CreateDpNorAccept .='select from pre defined list , ';
						$ConditionDpNorAccept .=$dpnrapc->AcceptanceCode.' , ';
					} else {
						$CreateDpNorAccept .=' , ';
						$ConditionDpNorAccept .=' , ';
					}
					if($dpnrapc->StatusFlag==1) {
						$ActiveDpNorAccept .='Active , ';
					} else if($dpnrapc->StatusFlag==1) {
						$ActiveDpNorAccept .='In active , ';
					} else {
						$ActiveDpNorAccept .=' , ';
					}
				}
				$CreateDpNorAccept=rtrim($CreateDpNorAccept,' , ');
				$ConditionDpNorAccept=rtrim($ConditionDpNorAccept,' , ');
				$ActiveDpNorAccept=rtrim($ActiveDpNorAccept,' , ');
				$CreateDpNorAcceptall .='( '.$CreateDpNorAccept.' ) , ';
				$ConditionDpNorAcceptall .='( '.$ConditionDpNorAccept.' ) , ';
				$ActiveDpNorAcceptall .='( '.$ActiveDpNorAccept.' ) , ';
			}
			
			if($disd->DpOfficeHoursFlg==1) {
				$DpOfficeHours=$this->cargo_quote_model->ResponseDpOfficeHoursByID($disd->ResponseCargoID,$disd->RCD_ID);
				$DpOfficeDateFrom='';
				$DpOfficeDateTo='';
				$DpOfficeTimeFrom='';
				$DpOfficeTimeTo='';
				foreach($DpOfficeHours as $dpofh) {
					$DpOfficeDateFrom .=$dpofh->DateFrom.' , ';
					$DpOfficeDateTo .=$dpofh->DateTo.' , ';
					$DpOfficeTimeFrom .=$dpofh->TimeFrom.' , ';
					$DpOfficeTimeTo .=$dpofh->TimeTo.' , ';
				}
				$DpOfficeDateFrom=rtrim($DpOfficeDateFrom,' , ');
				$DpOfficeDateTo=rtrim($DpOfficeDateTo,' , ');
				$DpOfficeTimeFrom=rtrim($DpOfficeTimeFrom,' , ');
				$DpOfficeTimeTo=rtrim($DpOfficeTimeTo,' , ');
				$DpOfficeDateFromall .='( '.$DpOfficeDateFrom.' ) , ';
				$DpOfficeDateToall .='( '.$DpOfficeDateTo.' ) , ';
				$DpOfficeTimeFromall .='( '.$DpOfficeTimeFrom.' ) , ';
				$DpOfficeTimeToall .='( '.$DpOfficeTimeTo.' ) , ';
			}
			
			if($disd->DpLaytimeCommencementFlg==1) {
				$DpLaytimeCommencements=$this->cargo_quote_model->ResponseDpLaytimeCommencementsByID($disd->ResponseCargoID,$disd->RCD_ID);
				$DpLaytimeDayFrom='';
				$DpLaytimeDayTo='';
				$DpLaytimeTimeFrom='';
				$DpLaytimeTimeTo='';
				$DpLaytimeTurnTime='';
				$DpLaytimeTurnTimeExpire='';
				$DpLaytimeCommenceAt='';
				$DpLaytimeCommenceAtHour='';
				$DpLaytimeSelectDay='';
				$DpLaytimeTimeCountsIfOnDemurrage='';
				foreach($DpLaytimeCommencements as $dpltcm) {
					$DpLaytimeDayFrom .=$dpltcm->DayFrom.' , ';
					$DpLaytimeDayTo .=$dpltcm->DayTo.' , ';
					$DpLaytimeTimeFrom .=$dpltcm->TimeFrom.' , ';
					$DpLaytimeTimeTo .=$dpltcm->TimeTo.' , ';
					$DpLaytimeTurnTime .=$dpltcm->LaytimeCode.' , ';
					if($dpltcm->TurnTimeExpire==1) {
					$DpLaytimeTurnTimeExpire .='During office hours , ';
					} else if($dpltcm->TurnTimeExpire==2) {
					$DpLaytimeTurnTimeExpire .='After office hours , ';
					} else {
					$DpLaytimeTurnTimeExpire .=' , ';	
					}
					if($dpltcm->LaytimeCommenceAt==1) {
						$DpLaytimeCommenceAt .='At expiry of turn time , ';
					} else if($dpltcm->LaytimeCommenceAt==2) {
						$DpLaytimeCommenceAt .='At specified hour , ';
					} else {
						$DpLaytimeCommenceAt .=' , ';
					}
					
					$DpLaytimeCommenceAtHour .=$dpltcm->LaytimeCommenceAtHour.' , ';
					if($dpltcm->SelectDay==1) {
						$DpLaytimeSelectDay .='Same Day , ';
					} else if($dpltcm->SelectDay==2) {
						$DpLaytimeSelectDay .='New Working Day , ';
					} else {
						$DpLaytimeSelectDay .=' , ';
					}
					
					if($dpltcm->TimeCountsIfOnDemurrage==1) {
					$DpLaytimeTimeCountsIfOnDemurrage .='Yes , ';
					} else if($dpltcm->TimeCountsIfOnDemurrage==2) {
					$DpLaytimeTimeCountsIfOnDemurrage .='No , ';	
					} else {
					$DpLaytimeTimeCountsIfOnDemurrage .=' , ';	
					}
				}
				$DpLaytimeDayFrom=rtrim($DpLaytimeDayFrom,' , ');
				$DpLaytimeDayTo=rtrim($DpLaytimeDayTo,' , ');
				$DpLaytimeTimeFrom=rtrim($DpLaytimeTimeFrom,' , ');
				$DpLaytimeTimeTo=rtrim($DpLaytimeTimeTo,' , ');
				$DpLaytimeTurnTime=rtrim($DpLaytimeTurnTime,' , ');
				$DpLaytimeTurnTimeExpire=rtrim($DpLaytimeTurnTimeExpire,' , ');
				$DpLaytimeCommenceAt=rtrim($DpLaytimeCommenceAt,' , ');
				$DpLaytimeCommenceAtHour=rtrim($DpLaytimeCommenceAtHour,' , ');
				$DpLaytimeSelectDay=rtrim($DpLaytimeSelectDay,' , ');
				$DpLaytimeTimeCountsIfOnDemurrage=rtrim($DpLaytimeTimeCountsIfOnDemurrage,' , ');
				$DpLaytimeDayFromall .='( '.$DpLaytimeDayFrom.' ) , ';
				$DpLaytimeDayToall .='( '.$DpLaytimeDayTo.' ) , ';
				$DpLaytimeTimeFromall .='( '.$DpLaytimeTimeFrom.' ) , ';
				$DpLaytimeTimeToall .='( '.$DpLaytimeTimeTo.' ) , ';
				$DpLaytimeTurnTimeall .='( '.$DpLaytimeTurnTime.' ) , ';
				$DpLaytimeTurnTimeExpireall .='( '.$DpLaytimeTurnTimeExpire.' ) , ';
				$DpLaytimeCommenceAtall .='( '.$DpLaytimeCommenceAt.' ) , ';
				$DpLaytimeCommenceAtHourall .='( '.$DpLaytimeCommenceAtHour.' ) , ';
				$DpLaytimeSelectDayall .='( '.$DpLaytimeSelectDay.' ) , ';
				$DpLaytimeTimeCountsIfOnDemurrageall .='( '.$DpLaytimeTimeCountsIfOnDemurrage.' ) , ';
			}
			
			}
			
			$disport_coma=trim($disport_coma,' , ');
			$ArrivalStartDate=trim($ArrivalStartDate,' , ');
			$ArrivalEndDate=trim($ArrivalEndDate,' , ');
			$PreferDate=trim($PreferDate,' , ');
			$ExpectedDelayDay=trim($ExpectedDelayDay,' , ');
			$DischTerm=trim($DischTerm,' , ');
			$DischRateMt=trim($DischRateMt,' , ');
			$DischRateUOM=trim($DischRateUOM,' , ');
			$MaxTime=trim($MaxTime,' , ');
			$LaytimeType=trim($LaytimeType,' , ');
			$CalculationBasedOn=trim($CalculationBasedOn,' , ');
			$TurnTime=trim($TurnTime,' , ');
			$PriorUseTerms=trim($PriorUseTerms,' , ');
			$LaytimeBasedOn=trim($LaytimeBasedOn,' , ');
			$CharterType=trim($CharterType,' , ');
			$NorTendering=trim($NorTendering,' , ');
			$StevedoringTerms=trim($StevedoringTerms,' , ');
			$evnamedpall=trim($evnamedpall,' , ');
			$laycountdemdpall=trim($laycountdemdpall,' , ');
			$laycountdpall=trim($laycountdpall,' , ');
			$timecountdpall=trim($timecountdpall,' , ');
			$CreateDpNoreTendall=trim($CreateDpNoreTendall,' , ');
			$ConditionDpNoreTendall=trim($ConditionDpNoreTendall,' , ');
			$ActiveDpNoreTendall=trim($ActiveDpNoreTendall,' , ');
			$CreateDpNorAcceptall=trim($CreateDpNorAcceptall,' , ');
			$ConditionDpNorAcceptall=trim($ConditionDpNorAcceptall,' , ');
			$ActiveDpNorAcceptall=trim($ActiveDpNorAcceptall,' , ');
			$DpOfficeDateFromall=trim($DpOfficeDateFromall,' , ');
			$DpOfficeDateToall=trim($DpOfficeDateToall,' , ');
			$DpOfficeTimeFromall=trim($DpOfficeTimeFromall,' , ');
			$DpOfficeTimeToall=trim($DpOfficeTimeToall,' , ');
			$DpLaytimeDayFromall=trim($DpLaytimeDayFromall,' , ');
			$DpLaytimeDayToall=trim($DpLaytimeDayToall,' , ');
			$DpLaytimeTimeFromall=trim($DpLaytimeTimeFromall,' , ');
			$DpLaytimeTimeToall=trim($DpLaytimeTimeToall,' , ');
			$DpLaytimeTurnTimeall=trim($DpLaytimeTurnTimeall,' , ');
			$DpLaytimeTurnTimeExpireall=trim($DpLaytimeTurnTimeExpireall,' , ');
			$DpLaytimeCommenceAtall=trim($DpLaytimeCommenceAtall,' , ');
			$DpLaytimeCommenceAtHourall=trim($DpLaytimeCommenceAtHourall,' , ');
			$DpLaytimeSelectDayall=trim($DpLaytimeSelectDayall,' , ');
			$DpLaytimeTimeCountsIfOnDemurrageall=trim($DpLaytimeTimeCountsIfOnDemurrageall,' , ');
			
			$DisPort .=$disport_coma.' || ';
			$DpArrivalStartDate .=$ArrivalStartDate.' || ';
			$DpArrivalEndDate .=$ArrivalEndDate.' || ';
			$DpPreferDate .=$PreferDate.' || ';
			$ExpectedDpDelayDay .=$ExpectedDelayDay.' || ';
			$DischargingTerms .=$DischTerm.' || ';
			$DischargingRateMT .=$DischRateMt.' || ';
			$DischargingRateUOM .=$DischRateUOM.' || ';
			$DpMaxTime .=$MaxTime.' || ';
			$DpLaytimeType .=$LaytimeType.' || ';
			$DpCalculationBasedOn .=$CalculationBasedOn.' || ';
			$DpTurnTime .=$TurnTime.' || ';
			$DpPriorUseTerms .=$PriorUseTerms.' || ';
			$DpLaytimeBasedOn .=$LaytimeBasedOn.' || ';
			$DpCharterType .=$CharterType.' || ';
			$DpNorTendering .=$NorTendering.' || ';
			$DpStevedoringTerms .=$StevedoringTerms.' || ';
			$DisportEventName .=$evnamedpall.' || ';
			$DisportLaytimeCountsOnDemurrage .=$laycountdemdpall.' || ';
			$DisportLaytimeCounts .=$laycountdpall.' || ';
			$DisportTimeCounting .=$timecountdpall.' || ';
			$DisportCreateNewOrSelectListTendering .=$CreateDpNoreTendall.' || ';
			$DisportNORTenderingPreCondition .=$ConditionDpNoreTendall.' || ';
			$DisportTenderingStatus .=$ActiveDpNoreTendall.' || ';
			$DisportCreateNewOrSelectListAcceptance .=$CreateDpNorAcceptall.' || ';
			$DisportNORAcceptancePreCondition .=$ConditionDpNorAcceptall.' || ';
			$DisportAcceptanceStatus .=$ActiveDpNorAcceptall.' || ';
			$DisportOfficeDateFrom .=$DpOfficeDateFromall.' || ';
			$DisportOfficeDateTo .=$DpOfficeDateToall.' || ';
			$DisportOfficeTimeFrom .=$DpOfficeTimeFromall.' || ';
			$DisportOfficeTimeTo .=$DpOfficeTimeToall.' || ';
			
			$DisportLaytimeDayFrom .=$DpLaytimeDayFromall.' || ';
			$DisportLaytimeDayTo .=$DpLaytimeDayToall.' || ';
			$DisportLaytimeTimeFrom .=$DpLaytimeTimeFromall.' || ';
			$DisportLaytimeTimeTo .=$DpLaytimeTimeToall.' || ';
			$DisportLaytimeTurnTime .=$DpLaytimeTurnTimeall.' || ';
			$DisportLaytimeTurnTimeExpire .=$DpLaytimeTurnTimeExpireall.' || ';
			$DisportLaytimeCommenceAt .=$DpLaytimeCommenceAtall.' || ';
			$DisportLaytimeCommenceAtHour .=$DpLaytimeCommenceAtHourall.' || ';
			$DisportLaytimeSelectDay .=$DpLaytimeSelectDayall.' || ';
			$DisportLaytimeTimeCountsIfOnDemurrage .=$DpLaytimeTimeCountsIfOnDemurrageall.' || ';
			
			
		$Bacdata=$this->cargo_quote_model->get_bac_by_responsecargoID($row->ResponseCargoID);
		$Other1PayingEntityType='';
		$Other1PayingEntityName='';
		$Other1ReceivingEntityType='';
		$Other1ReceivingEntityName='';
		$Other1BrokerName='';
		$Other1PayableAs='';
		$Other1PercentageOnFreight='';
		$Other1PercentageOnDeadFreight='';
		$Other1PercentageOnDemmurage='';
		$Other1PercentageOnOverage='';
		$Other1LumpsumPayable='';
		$Other1RatePerTonnePayable='';
		foreach($Bacdata as $bac) {
			if($bac->TransactionType=='Brokerage'){
				$BrokeragePayingEntityType .=$bac->PayingEntityType.' || ';
				$BrokeragePayingEntityName .=$bac->PayingEntityName.' || ';
				$BrokerageReceivingEntityType .=$bac->ReceivingEntityType.' || ';
				$BrokerageReceivingEntityName .=$bac->ReceivingEntityName.' || ';
				$BrokerageBrokerName .=$bac->BrokerName.' || ';
				$BrokeragePayableAs .=$bac->PayableAs.' || ';
				$BrokeragePercentageOnFreight .=$bac->PercentageOnFreight.' || ';
				$BrokeragePercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' || ';
				$BrokeragePercentageOnDemmurage .=$bac->PercentageOnDemmurage.' || ';
				$BrokeragePercentageOnOverage .=$bac->PercentageOnOverage.' || ';
				$BrokerageLumpsumPayable .=$bac->LumpsumPayable.' || ';
				$BrokerageRatePerTonnePayable .=$bac->RatePerTonnePayable.' || ';
			} else if($bac->TransactionType=='Commision'){
				$AddCommPayingEntityType .=$bac->PayingEntityType.' || ';
				$AddCommPayingEntityName .=$bac->PayingEntityName.' || ';
				$AddCommReceivingEntityType .=$bac->ReceivingEntityType.' || ';
				$AddCommReceivingEntityName .=$bac->ReceivingEntityName.' || ';
				$AddCommBrokerName .=$bac->BrokerName.' || ';
				$AddCommPayableAs .=$bac->PayableAs.' || ';
				$AddCommPercentageOnFreight .=$bac->PercentageOnFreight.' || ';
				$AddCommPercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' || ';
				$AddCommPercentageOnDemmurage .=$bac->PercentageOnDemmurage.' || ';
				$AddCommPercentageOnOverage .=$bac->PercentageOnOverage.' || ';
				$AddCommLumpsumPayable .=$bac->LumpsumPayable.' || ';
				$AddCommRatePerTonnePayable .=$bac->RatePerTonnePayable.' || ';
			} else if($bac->TransactionType=='Others') {
				$Other1PayingEntityType .=$bac->PayingEntityType.' , ';
				$Other1PayingEntityName .=$bac->PayingEntityName.' , ';
				$Other1ReceivingEntityType .=$bac->ReceivingEntityType.' , ';
				$Other1ReceivingEntityName .=$bac->ReceivingEntityName.' , ';
				$Other1BrokerName .=$bac->BrokerName.' , ';
				$Other1PayableAs .=$bac->PayableAs.' , ';
				$Other1PercentageOnFreight .=$bac->PercentageOnFreight.' , ';
				$Other1PercentageOnDeadFreight .=$bac->PercentageOnDeadFreight.' , ';
				$Other1PercentageOnDemmurage .=$bac->PercentageOnDemmurage.' , ';
				$Other1PercentageOnOverage .=$bac->PercentageOnOverage.' , ';
				$Other1LumpsumPayable .=$bac->LumpsumPayable.' , ';
				$Other1RatePerTonnePayable .=$bac->RatePerTonnePayable.' , ';
			}
		}
		$Other1PayingEntityType=trim($Other1PayingEntityType,' , ');	
		$Other1PayingEntityName=trim($Other1PayingEntityName,' , ');	
		$Other1ReceivingEntityType=trim($Other1ReceivingEntityType,' , ');	
		$Other1ReceivingEntityName=trim($Other1ReceivingEntityName,' , ');	
		$Other1BrokerName=trim($Other1BrokerName,' , ');	
		$Other1PayableAs=trim($Other1PayableAs,' , ');	
		$Other1PercentageOnFreight=trim($Other1PercentageOnFreight,' , ');	
		$Other1PercentageOnDeadFreight=trim($Other1PercentageOnDeadFreight,' , ');	
		$Other1PercentageOnDemmurage=trim($Other1PercentageOnDemmurage,' , ');	
		$Other1PercentageOnOverage=trim($Other1PercentageOnOverage,' , ');	
		$Other1LumpsumPayable=trim($Other1LumpsumPayable,' , ');	
		$Other1RatePerTonnePayable=trim($Other1RatePerTonnePayable,' , ');

		$OtherPayingEntityType .=$Other1PayingEntityType.' || ';
		$OtherPayingEntityName .=$Other1PayingEntityName.' || ';
		$OtherReceivingEntityType .=$Other1ReceivingEntityType.' || ';
		$OtherReceivingEntityName .=$Other1ReceivingEntityName.' || ';
		$OtherBrokerName .=$Other1BrokerName.' || ';
		$OtherPayableAs .=$Other1PayableAs.' || ';
		$OtherPercentageOnFreight .=$Other1PercentageOnFreight.' || ';
		$OtherPercentageOnDeadFreight .=$Other1PercentageOnDeadFreight.' || ';
		$OtherPercentageOnDemmurage .=$Other1PercentageOnDemmurage.' || ';
		$OtherPercentageOnOverage .=$Other1PercentageOnOverage.' || ';
		$OtherLumpsumPayable .=$Other1LumpsumPayable.' || ';
		$OtherRatePerTonnePayable .=$Other1RatePerTonnePayable.' || ';
		}
		}
		
		$cargo=rtrim($cargo,' || ');
		$CargoQtyMT=rtrim($CargoQtyMT,' || ');
		$CargoLoadedBasis=rtrim($CargoLoadedBasis,' || ');
		$CargoLimitBasis=rtrim($CargoLimitBasis,' || ');
		$MaxCargoMT=rtrim($MaxCargoMT,' || ');
		$MinCargoMT=rtrim($MinCargoMT,' || ');
		$ToleranceLimit=rtrim($ToleranceLimit,' || ');
		$UpperLimit=rtrim($UpperLimit,' || ');
		$LowerLimit=rtrim($LowerLimit,' || ');
		$lpPortName=rtrim($lpPortName,' || ');
		$LpLaycanStartDate=rtrim($LpLaycanStartDate,' || ');
		$LpLaycanEndDate=rtrim($LpLaycanEndDate,' || ');
		$LpPreferDate=rtrim($LpPreferDate,' || ');
		$ExpectedLpDelayDay=rtrim($ExpectedLpDelayDay,' || ');
		$ldtCode=rtrim($ldtCode,' || ');
		$LoadingRateMT=rtrim($LoadingRateMT,' || ');
		$LoadingRateUOM=rtrim($LoadingRateUOM,' || ');
		$LpMaxTime=rtrim($LpMaxTime,' || ');
		$LpLaytimeType=rtrim($LpLaytimeType,' || ');
		$LpCalculationBasedOn=rtrim($LpCalculationBasedOn,' || ');
		$ftCode=rtrim($ftCode,' || ');
		$LpPriorUseTerms=rtrim($LpPriorUseTerms,' || ');
		$LpLaytimeBasedOn=rtrim($LpLaytimeBasedOn,' || ');
		$LpCharterType=rtrim($LpCharterType,' || ');
		$cnrCode=rtrim($cnrCode,' || ');
		$StevedoringTermsLp=rtrim($StevedoringTermsLp,' || ');
		$LoadPortEventName=rtrim($LoadPortEventName,' || ');
		$LoadPortLaytimeCountsOnDemurrage=rtrim($LoadPortLaytimeCountsOnDemurrage,' || ');
		$LoadPortLaytimeCounts=rtrim($LoadPortLaytimeCounts,' || ');
		$LoadPortTimeCounting=rtrim($LoadPortTimeCounting,' || ');
		$LoadPortCreateNewOrSelectListTendering=rtrim($LoadPortCreateNewOrSelectListTendering,' || ');
		$LoadPortNORTenderingPreCondition=rtrim($LoadPortNORTenderingPreCondition,' || ');
		$LoadPortTenderingStatus=rtrim($LoadPortTenderingStatus,' || ');
		$LoadPortCreateNewOrSelectListAcceptance=rtrim($LoadPortCreateNewOrSelectListAcceptance,' || ');
		$LoadPortNORAcceptancePreCondition=rtrim($LoadPortNORAcceptancePreCondition,' || ');
		$LoadPortAcceptanceStatus=rtrim($LoadPortAcceptanceStatus,' || ');
		$LoadPortOfficeDateFrom=rtrim($LoadPortOfficeDateFrom,' || ');
		$LoadPortOfficeDateTo=rtrim($LoadPortOfficeDateTo,' || ');
		$LoadPortOfficeTimeFrom=rtrim($LoadPortOfficeTimeFrom,' || ');
		$LoadPortOfficeTimeTo=rtrim($LoadPortOfficeTimeTo,' || ');
		
		$LoadPortLaytimeDayFrom=rtrim($LoadPortLaytimeDayFrom,' || ');
		$LoadPortLaytimeDayTo=rtrim($LoadPortLaytimeDayTo,' || ');
		$LoadPortLaytimeTimeFrom=rtrim($LoadPortLaytimeTimeFrom,' || ');
		$LoadPortLaytimeTimeTo=rtrim($LoadPortLaytimeTimeTo,' || ');
		$LoadPortLaytimeTurnTime=rtrim($LoadPortLaytimeTurnTime,' || ');
		$LoadPortLaytimeTurnTimeExpire=rtrim($LoadPortLaytimeTurnTimeExpire,' || ');
		$LoadPortLaytimeCommenceAt=rtrim($LoadPortLaytimeCommenceAt,' || ');
		$LoadPortLaytimeCommenceAtHour=rtrim($LoadPortLaytimeCommenceAtHour,' || ');
		$LoadPortLaytimeSelectDay=rtrim($LoadPortLaytimeSelectDay,' || ');
		$LoadPortLaytimeTimeCountsIfOnDemurrage=rtrim($LoadPortLaytimeTimeCountsIfOnDemurrage,' || ');
		
		$DisPort=rtrim($DisPort,' || ');
		$DpArrivalStartDate=rtrim($DpArrivalStartDate,' || ');
		$DpArrivalEndDate=rtrim($DpArrivalEndDate,' || ');
		$DpPreferDate=rtrim($DpPreferDate,' || ');
		$ExpectedDpDelayDay=rtrim($ExpectedDpDelayDay,' || ');
		$DischargingTerms=rtrim($DischargingTerms,' || ');
		$DischargingRateMT=rtrim($DischargingRateMT,' || ');
		$DischargingRateUOM=rtrim($DischargingRateUOM,' || ');
		$DpMaxTime=rtrim($DpMaxTime,' || ');
		$DpLaytimeType=rtrim($DpLaytimeType,' || ');
		$DpCalculationBasedOn=rtrim($DpCalculationBasedOn,' || ');
		$DpTurnTime=rtrim($DpTurnTime,' || ');
		$DpPriorUseTerms=rtrim($DpPriorUseTerms,' || ');
		$DpLaytimeBasedOn=rtrim($DpLaytimeBasedOn,' || ');
		$DpCharterType=rtrim($DpCharterType,' || ');
		$DpNorTendering=rtrim($DpNorTendering,' || ');
		$DpStevedoringTerms=rtrim($DpStevedoringTerms,' || ');
		$DisportEventName=rtrim($DisportEventName,' || ');
		$DisportLaytimeCountsOnDemurrage=rtrim($DisportLaytimeCountsOnDemurrage,' || ');
		$DisportLaytimeCounts=rtrim($DisportLaytimeCounts,' || ');
		$DisportTimeCounting=rtrim($DisportTimeCounting,' || ');
		$DisportCreateNewOrSelectListTendering=rtrim($DisportCreateNewOrSelectListTendering,' || ');
		$DisportNORTenderingPreCondition=rtrim($DisportNORTenderingPreCondition,' || ');
		$DisportTenderingStatus=rtrim($DisportTenderingStatus,' || ');
		$DisportCreateNewOrSelectListAcceptance=rtrim($DisportCreateNewOrSelectListAcceptance,' || ');
		$DisportNORAcceptancePreCondition=rtrim($DisportNORAcceptancePreCondition,' || ');
		$DisportAcceptanceStatus=rtrim($DisportAcceptanceStatus,' || ');
		$DisportOfficeDateFrom=rtrim($DisportOfficeDateFrom,' || ');
		$DisportOfficeDateTo=rtrim($DisportOfficeDateTo,' || ');
		$DisportOfficeTimeFrom=rtrim($DisportOfficeTimeFrom,' || ');
		$DisportOfficeTimeTo=rtrim($DisportOfficeTimeTo,' || ');
		
		$DisportLaytimeDayFrom=rtrim($DisportLaytimeDayFrom,' || ');
		$DisportLaytimeDayTo=rtrim($DisportLaytimeDayTo,' || ');
		$DisportLaytimeTimeFrom=rtrim($DisportLaytimeTimeFrom,' || ');
		$DisportLaytimeTimeTo=rtrim($DisportLaytimeTimeTo,' || ');
		$DisportLaytimeTurnTime=rtrim($DisportLaytimeTurnTime,' || ');
		$DisportLaytimeTurnTimeExpire=rtrim($DisportLaytimeTurnTimeExpire,' || ');
		$DisportLaytimeCommenceAt=rtrim($DisportLaytimeCommenceAt,' || ');
		$DisportLaytimeCommenceAtHour=rtrim($DisportLaytimeCommenceAtHour,' || ');
		$DisportLaytimeSelectDay=rtrim($DisportLaytimeSelectDay,' || ');
		$DisportLaytimeTimeCountsIfOnDemurrage=rtrim($DisportLaytimeTimeCountsIfOnDemurrage,' || ');
		
		$BrokeragePayingEntityType=rtrim($BrokeragePayingEntityType,' || ');
		$BrokeragePayingEntityName=rtrim($BrokeragePayingEntityName,' || ');
		$BrokerageReceivingEntityType=rtrim($BrokerageReceivingEntityType,' || ');
		$BrokerageReceivingEntityName=rtrim($BrokerageReceivingEntityName,' || ');
		$BrokerageBrokerName=rtrim($BrokerageBrokerName,' || ');
		$BrokeragePayableAs=rtrim($BrokeragePayableAs,' || ');
		$BrokeragePercentageOnFreight=rtrim($BrokeragePercentageOnFreight,' || ');
		$BrokeragePercentageOnDeadFreight=rtrim($BrokeragePercentageOnDeadFreight,' || ');
		$BrokeragePercentageOnDemmurage=rtrim($BrokeragePercentageOnDemmurage,' || ');
		$BrokeragePercentageOnOverage=rtrim($BrokeragePercentageOnOverage,' || ');
		$BrokerageLumpsumPayable=rtrim($BrokerageLumpsumPayable,' || ');
		$BrokerageRatePerTonnePayable=rtrim($BrokerageRatePerTonnePayable,' || ');
		
		$AddCommPayingEntityType=rtrim($AddCommPayingEntityType,' || ');
		$AddCommPayingEntityName=rtrim($AddCommPayingEntityName,' || ');
		$AddCommReceivingEntityType=rtrim($AddCommReceivingEntityType,' || ');
		$AddCommReceivingEntityName=rtrim($AddCommReceivingEntityName,' || ');
		$AddCommBrokerName=rtrim($AddCommBrokerName,' || ');
		$AddCommPayableAs=rtrim($AddCommPayableAs,' || ');
		$AddCommPercentageOnFreight=rtrim($AddCommPercentageOnFreight,' || ');
		$AddCommPercentageOnDeadFreight=rtrim($AddCommPercentageOnDeadFreight,' || ');
		$AddCommPercentageOnDemmurage=rtrim($AddCommPercentageOnDemmurage,' || ');
		$AddCommPercentageOnOverage=rtrim($AddCommPercentageOnOverage,' || ');
		$AddCommLumpsumPayable=rtrim($AddCommLumpsumPayable,' || ');
		$AddCommRatePerTonnePayable=rtrim($AddCommRatePerTonnePayable,' || ');
		
		$OtherPayingEntityType=rtrim($OtherPayingEntityType,' || ');
		$OtherPayingEntityName=rtrim($OtherPayingEntityName,' || ');
		$OtherReceivingEntityType=rtrim($OtherReceivingEntityType,' || ');
		$OtherReceivingEntityName=rtrim($OtherReceivingEntityName,' || ');
		$OtherBrokerName=rtrim($OtherBrokerName,' || ');
		$OtherPayableAs=rtrim($OtherPayableAs,' || ');
		$OtherPercentageOnFreight=rtrim($OtherPercentageOnFreight,' || ');
		$OtherPercentageOnDeadFreight=rtrim($OtherPercentageOnDeadFreight,' || ');
		$OtherPercentageOnDemmurage=rtrim($OtherPercentageOnDemmurage,' || ');
		$OtherPercentageOnOverage=rtrim($OtherPercentageOnOverage,' || ');
		$OtherLumpsumPayable=rtrim($OtherLumpsumPayable,' || ');
		$OtherRatePerTonnePayable=rtrim($OtherRatePerTonnePayable,' || ');
		
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Carog</b></p>';
		
		if($tptfields[36]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='select_cargo_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				//echo $ef_flag;die;
				if($ef_flag==1) {
					$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[36]->NewDisplayName.' : '.$cargo.'</p>';
				} else {
					$html .='<p><'.$tptfields[36]->FieldName.'2>'.$tptfields[36]->NewDisplayName.'</'.$tptfields[36]->FieldName.'2> : <'.$tptfields[36]->FieldName.'3>'.$cargo.'</'.$tptfields[36]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[36]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[36]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[36]->FieldName;	
					$data_arr['FieldValue']=$cargo;		
					$data_arr['GroupNumber']=1;		
					array_push($fix_data_arr,$data_arr);
				}
			}
			//$html .='<p ><span >Version : &nbsp;</span>'.$row->CargoVersion.'</p>';
			if($tptfields[37]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_load_in_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[37]->NewDisplayName.' : '.$CargoQtyMT.'</p>';
				} else {
					$html .='<p><'.$tptfields[37]->FieldName.'2>'.$tptfields[37]->NewDisplayName.'</'.$tptfields[37]->FieldName.'2> : <'.$tptfields[37]->FieldName.'3>'.$CargoQtyMT.'</'.$tptfields[37]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[37]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[37]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[37]->FieldName;	
					$data_arr['FieldValue']=$CargoQtyMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			if($tptfields[38]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_loaded_option_basis_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[38]->NewDisplayName.' : '.$CargoLoadedBasis.'</p>';
				} else {
					$html .='<p><'.$tptfields[38]->FieldName.'2>'.$tptfields[38]->NewDisplayName.'</'.$tptfields[38]->FieldName.'2> : <'.$tptfields[38]->FieldName.'3>'.$CargoLoadedBasis.'</'.$tptfields[38]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[38]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[38]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[38]->FieldName;	
					$data_arr['FieldValue']=$CargoLoadedBasis;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[39]->Included){
				
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='cargo_qty_limit_basis_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[39]->NewDisplayName.' : '.$CargoLimitBasis.'</p>';
				} else {
					$html .='<p><'.$tptfields[39]->FieldName.'2>'.$tptfields[39]->NewDisplayName.'</'.$tptfields[39]->FieldName.'2> : <'.$tptfields[39]->FieldName.'3>'.$CargoLimitBasis.'</'.$tptfields[39]->FieldName.'3></p>';	
					$data_arr['CpCode']=$tptfields[39]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[39]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[39]->FieldName;	
					$data_arr['FieldValue']=$CargoLimitBasis;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($CargoLimitBasisFlag1==1){
				if($tptfields[43]->Included){
					$html .='<p><'.$tptfields[43]->FieldName.'2>'.$tptfields[43]->NewDisplayName.'</'.$tptfields[43]->FieldName.'2> : <'.$tptfields[43]->FieldName.'3>'.$MaxCargoMT.'</'.$tptfields[43]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[43]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[43]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[43]->FieldName;	
					$data_arr['FieldValue']=$MaxCargoMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[44]->Included){
					$html .='<p><'.$tptfields[44]->FieldName.'2>'.$tptfields[44]->NewDisplayName.'</'.$tptfields[44]->FieldName.'2> : <'.$tptfields[44]->FieldName.'3>'.$MinCargoMT.'</'.$tptfields[44]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[44]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[44]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[44]->FieldName;	
					$data_arr['FieldValue']=$MinCargoMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}else if($CargoLimitBasisFlag2==1){
				if($tptfields[40]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='cargo_tolerance_limit_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[40]->NewDisplayName.' : '.$ToleranceLimit.'</p>';	
				} else {
					$html .='<p><'.$tptfields[40]->FieldName.'2>'.$tptfields[40]->NewDisplayName.'</'.$tptfields[40]->FieldName.'2> : <'.$tptfields[40]->FieldName.'3>'.$ToleranceLimit.'</'.$tptfields[40]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[40]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[40]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[40]->FieldName;	
					$data_arr['FieldValue']=$ToleranceLimit;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
				}
				if($tptfields[41]->Included){
					$html .='<p><'.$tptfields[41]->FieldName.'2>'.$tptfields[41]->NewDisplayName.'</'.$tptfields[41]->FieldName.'2> : <'.$tptfields[41]->FieldName.'3>'.$UpperLimit.'</'.$tptfields[41]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[41]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[41]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[41]->FieldName;	
					$data_arr['FieldValue']=$UpperLimit;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[42]->Included){
					$html .='<p><'.$tptfields[42]->FieldName.'2>'.$tptfields[42]->NewDisplayName.'</'.$tptfields[42]->FieldName.'2> : <'.$tptfields[42]->FieldName.'3>'.$LowerLimit.'</'.$tptfields[42]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[42]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[42]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[42]->FieldName;	
					$data_arr['FieldValue']=$LowerLimit;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Load Port</b></p>';
			
			if($tptfields[45]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[45]->NewDisplayName.' : '.$lpPortName.'</p>';
			} else {
				$html .='<p><'.$tptfields[45]->FieldName.'2>'.$tptfields[45]->NewDisplayName.'</'.$tptfields[45]->FieldName.'2> : <'.$tptfields[45]->FieldName.'3>'.$lpPortName.'</'.$tptfields[45]->FieldName.'3></p>';	
				$data_arr['CpCode']=$tptfields[45]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[45]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[45]->FieldName;	
				$data_arr['FieldValue']=$lpPortName;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[46]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_laycan_start_date_editable_filed' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[46]->NewDisplayName.' : '.$LpLaycanStartDate.'</p>';
			} else {
				$html .='<p><'.$tptfields[46]->FieldName.'2>'.$tptfields[46]->NewDisplayName.'</'.$tptfields[46]->FieldName.'2> : <'.$tptfields[46]->FieldName.'3>'.$LpLaycanStartDate.'</'.$tptfields[46]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[46]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[46]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[46]->FieldName;	
				$data_arr['FieldValue']=$LpLaycanStartDate;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);	
			}
			}
			if($tptfields[47]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='load_port_laycan_finish_date_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false"  style="background-color: #efeaead6">'.$tptfields[47]->NewDisplayName.' : '.$LpLaycanEndDate.'</p>';
				} else {
					$html .='<p><'.$tptfields[47]->FieldName.'2>'.$tptfields[47]->NewDisplayName.'</'.$tptfields[47]->FieldName.'2> : <'.$tptfields[47]->FieldName.'3>'.$LpLaycanEndDate.'</'.$tptfields[47]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[47]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[47]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[47]->FieldName;	
					$data_arr['FieldValue']=$LpLaycanEndDate;		
					$data_arr['GroupNumber']=1;					
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[48]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='load_port_prefered_arrival_date_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p  contenteditable="false" style="background-color: #efeaead6">'.$tptfields[48]->NewDisplayName.' : '.$LpPreferDate.'</p>';
			} else {
				$html .='<p><'.$tptfields[48]->FieldName.'2>'.$tptfields[48]->NewDisplayName.'</'.$tptfields[48]->FieldName.'2> : <'.$tptfields[48]->FieldName.'3>'.$LpPreferDate.'</'.$tptfields[48]->FieldName.'3></p>';	
				$data_arr['CpCode']=$tptfields[48]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[48]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[48]->FieldName;	
				$data_arr['FieldValue']=$LpPreferDate;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[49]->Included){
			$ef_flag=0;
			foreach($EditField as $ed_row) {
				if($ed_row->ChkLabel=='expected_load_port_delay_editable_field' && $ed_row->ChkFlag=='true') {
					$ef_flag=1;	
				}
			}
			if($ef_flag==1) {
				$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[49]->NewDisplayName.' : '.$ExpectedLpDelayDay.'</p>';
			} else {
				$html .='<p><'.$tptfields[49]->FieldName.'2>'.$tptfields[49]->NewDisplayName.'</'.$tptfields[49]->FieldName.'2> : <'.$tptfields[49]->FieldName.'3>'.$ExpectedLpDelayDay.'</'.$tptfields[49]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[49]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[49]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[49]->FieldName;	
				$data_arr['FieldValue']=$ExpectedLpDelayDay;		
				$data_arr['GroupNumber']=1;	
				array_push($fix_data_arr,$data_arr);	
			}
			}
			if($tptfields[50]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loadding_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6"><td>'.$tptfields[50]->CpCode.' : '.$tptfields[50]->NewDisplayName.'</td><td>'.$ldtCode.'</p>';
				} else {
					$html .='<p><'.$tptfields[50]->FieldName.'2>'.$tptfields[50]->NewDisplayName.'</'.$tptfields[50]->FieldName.'2> : <'.$tptfields[50]->FieldName.'3>'.$ldtCode.'</'.$tptfields[50]->FieldName.'3></p>';	
					$data_arr['CpCode']=$tptfields[50]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[50]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[50]->FieldName;	
					$data_arr['FieldValue']=$ldtCode;			
					$data_arr['GroupNumber']=1;		
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[51]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loading_rate_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[51]->NewDisplayName.' : '.$LoadingRateMT.'</p>';
				} else {
					$html .='<p><'.$tptfields[51]->FieldName.'2>'.$tptfields[51]->NewDisplayName.'</'.$tptfields[51]->FieldName.'2> : <'.$tptfields[51]->FieldName.'3>'.$LoadingRateMT.'</'.$tptfields[51]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[51]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[51]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[51]->FieldName;	
					$data_arr['FieldValue']=$LoadingRateMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[52]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='loading_rate_uom_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<p  contenteditable="false" style="background-color: #efeaead6">'.$tptfields[52]->NewDisplayName.' : '.$LoadingRateUOM.'</p>';
				} else {
					$html .='<p><'.$tptfields[52]->FieldName.'2>'.$tptfields[52]->NewDisplayName.'</'.$tptfields[52]->FieldName.'2> : <'.$tptfields[52]->FieldName.'3>'.$LoadingRateUOM.'</'.$tptfields[52]->FieldName.'3></p>';	
					$data_arr['CpCode']=$tptfields[52]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[52]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[52]->FieldName;	
					$data_arr['FieldValue']=$LoadingRateUOM;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($LoadingRateUOMFlag==1){
				if($tptfields[53]->Included){
					$html .='<p><'.$tptfields[53]->FieldName.'2>'.$tptfields[53]->NewDisplayName.'</'.$tptfields[53]->FieldName.'2> : <'.$tptfields[53]->FieldName.'3>'.$LpMaxTime.'</'.$tptfields[53]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[53]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[53]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[53]->FieldName;	
					$data_arr['FieldValue']=$LpMaxTime;			
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}
			}
			
			if($tptfields[54]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='max_time_to_load_cargo_hrs_editable_fields' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[54]->NewDisplayName.' : '.$LpLaytimeType.'</p>';
				} else {
					$html .='<p><'.$tptfields[54]->FieldName.'2>'.$tptfields[54]->NewDisplayName.'</'.$tptfields[54]->FieldName.'2> : <'.$tptfields[54]->FieldName.'3>'.$LpLaytimeType.'</'.$tptfields[54]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[54]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[54]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[54]->FieldName;	
					$data_arr['FieldValue']=$LpLaytimeType;			
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);
				}				
			}
			if($tptfields[55]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_tonnage_calc_based_on_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[55]->NewDisplayName.' : '.$LpCalculationBasedOn.'</p>';	
				} else {
					$html .='<p><'.$tptfields[55]->FieldName.'2>'.$tptfields[55]->NewDisplayName.'</'.$tptfields[55]->FieldName.'2> : <'.$tptfields[55]->FieldName.'3>'.$LpCalculationBasedOn.'</'.$tptfields[55]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[55]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[55]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[55]->FieldName;	
					$data_arr['FieldValue']=$LpCalculationBasedOn;		
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[56]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='turn_free_time_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[56]->NewDisplayName.' : '.$ftCode.'</p>';	
				} else {
					$html .='<p><'.$tptfields[56]->FieldName.'2>'.$tptfields[56]->NewDisplayName.'</'.$tptfields[56]->FieldName.'2> : <'.$tptfields[56]->FieldName.'3>'.$ftCode.'</'.$tptfields[56]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[56]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[56]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[56]->FieldName;	
					$data_arr['FieldValue']=$ftCode;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);		
				}
			}
			
			if($tptfields[57]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='prior_use_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[57]->NewDisplayName.' : '.$LpPriorUseTerms.'</p>';	
				} else {
					$html .='<p><'.$tptfields[57]->FieldName.'2>'.$tptfields[57]->NewDisplayName.'</'.$tptfields[57]->FieldName.'2> : <'.$tptfields[57]->FieldName.'3>'.$LpPriorUseTerms.'</'.$tptfields[57]->FieldName.'3></p>';	
					$data_arr['CpCode']=$tptfields[57]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[57]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[57]->FieldName;	
					$data_arr['FieldValue']=$LpPriorUseTerms;		
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[58]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_based_on_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {	
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[58]->NewDisplayName.' : '.$LpLaytimeBasedOn.'</p>';
				} else {
					$html .='<p><'.$tptfields[58]->FieldName.'2>'.$tptfields[58]->NewDisplayName.'</'.$tptfields[58]->FieldName.'2> : <'.$tptfields[58]->FieldName.'3>'.$LpLaytimeBasedOn.'</'.$tptfields[58]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[58]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[58]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[58]->FieldName;	
					$data_arr['FieldValue']=$LpLaytimeBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[59]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='type_of_charter_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[59]->NewDisplayName.' : '.$LpCharterType.'</p>';
				} else {
					$html .='<p><'.$tptfields[59]->FieldName.'2>'.$tptfields[59]->NewDisplayName.'</'.$tptfields[59]->FieldName.'2> : <'.$tptfields[59]->FieldName.'3>'.$LpCharterType.'</'.$tptfields[59]->FieldName.'3></p>';	
					$data_arr['CpCode']=$tptfields[59]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[59]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[59]->FieldName;	
					$data_arr['FieldValue']=$LpCharterType;			
					$data_arr['GroupNumber']=1;			
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($tptfields[60]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tender_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[60]->NewDisplayName.' : '.$cnrCode.'</p>';
				} else {
					$html .='<p><'.$tptfields[60]->FieldName.'2>'.$tptfields[60]->NewDisplayName.'</'.$tptfields[60]->FieldName.'2> : <'.$tptfields[60]->FieldName.'3>'.$cnrCode.'</'.$tptfields[60]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[60]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[60]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[60]->FieldName;	
					$data_arr['FieldValue']=$cnrCode;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			if($tptfields[61]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='LpStevedoringTerms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[61]->NewDisplayName.' : '.$StevedoringTermsLp.'</p>';
				} else {
					$html .='<p><'.$tptfields[61]->FieldName.'2>'.$tptfields[61]->NewDisplayName.'</'.$tptfields[61]->FieldName.'2> : <'.$tptfields[61]->FieldName.'3>'.$StevedoringTermsLp.'</'.$tptfields[61]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[61]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[61]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[61]->FieldName;	
					$data_arr['FieldValue']=$StevedoringTermsLp;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[63]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[63]->NewDisplayName.' : '.$LoadPortEventName.'</p>';
				} else {
					$html .='<p><'.$tptfields[63]->FieldName.'2>'.$tptfields[63]->NewDisplayName.'</'.$tptfields[63]->FieldName.'2> : <'.$tptfields[63]->FieldName.'3>'.$LoadPortEventName.'</'.$tptfields[63]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[64]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[64]->NewDisplayName.' : '.$LoadPortLaytimeCountsOnDemurrage.'</p>';
				} else {
					$html .='<p><'.$tptfields[64]->FieldName.'2>'.$tptfields[64]->NewDisplayName.'</'.$tptfields[64]->FieldName.'2> : <'.$tptfields[64]->FieldName.'3>'.$LoadPortLaytimeCountsOnDemurrage.'</'.$tptfields[64]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[65]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[65]->NewDisplayName.' : '.$LoadPortLaytimeCounts.'</p>';
				} else {
					$html .='<p><'.$tptfields[65]->FieldName.'2>'.$tptfields[65]->NewDisplayName.'</'.$tptfields[65]->FieldName.'2> : <'.$tptfields[65]->FieldName.'3>'.$LoadPortLaytimeCounts.'</'.$tptfields[65]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[66]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[66]->NewDisplayName.' : '.$LoadPortTimeCounting.'</p>';
				} else {
					$html .='<p><'.$tptfields[66]->FieldName.'2>'.$tptfields[66]->NewDisplayName.'</'.$tptfields[66]->FieldName.'2> : <'.$tptfields[66]->FieldName.'3>'.$LoadPortTimeCounting.'</'.$tptfields[66]->FieldName.'3></p>';	
				}
			}
			
			
			
			if($tptfields[68]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[68]->NewDisplayName.' : '.$LoadPortCreateNewOrSelectListTendering.'</p>';
				} else {
					$html .='<p><'.$tptfields[68]->FieldName.'2>'.$tptfields[68]->NewDisplayName.'</'.$tptfields[68]->FieldName.'2> : <'.$tptfields[68]->FieldName.'3>'.$LoadPortCreateNewOrSelectListTendering.'</'.$tptfields[68]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[69]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[69]->NewDisplayName.' : '.$LoadPortNORTenderingPreCondition.'</p>';
				} else {
					$html .='<p><'.$tptfields[69]->FieldName.'2>'.$tptfields[69]->NewDisplayName.'</'.$tptfields[69]->FieldName.'2> : <'.$tptfields[69]->FieldName.'3>'.$LoadPortNORTenderingPreCondition.'</'.$tptfields[69]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[70]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[70]->NewDisplayName.' : '.$LoadPortTenderingStatus.'</p>';
				} else {
					$html .='<p><'.$tptfields[70]->FieldName.'2>'.$tptfields[70]->NewDisplayName.'</'.$tptfields[70]->FieldName.'2> : <'.$tptfields[70]->FieldName.'3>'.$LoadPortTenderingStatus.'</'.$tptfields[70]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[72]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[72]->NewDisplayName.' : '.$LoadPortCreateNewOrSelectListAcceptance.'</p>';
				} else {
					$html .='<p><'.$tptfields[72]->FieldName.'2>'.$tptfields[72]->NewDisplayName.'</'.$tptfields[72]->FieldName.'2> : <'.$tptfields[72]->FieldName.'3>'.$LoadPortCreateNewOrSelectListAcceptance.'</'.$tptfields[72]->FieldName.'3></td></p>';	
				}
			}
			
			if($tptfields[73]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[73]->NewDisplayName.' : '.$LoadPortNORAcceptancePreCondition.'</p>';
				} else {
					$html .='<p><'.$tptfields[73]->FieldName.'2>'.$tptfields[73]->NewDisplayName.'</'.$tptfields[73]->FieldName.'2> : <'.$tptfields[73]->FieldName.'3>'.$LoadPortNORAcceptancePreCondition.'</'.$tptfields[73]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[74]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_conditions_apply' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[74]->NewDisplayName.' : '.$LoadPortAcceptanceStatus.'</p>';
				} else {
					$html .='<p><'.$tptfields[74]->FieldName.'2>'.$tptfields[74]->NewDisplayName.'</'.$tptfields[74]->FieldName.'2> : <'.$tptfields[74]->FieldName.'3>'.$LoadPortAcceptanceStatus.'</'.$tptfields[74]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[76]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[76]->NewDisplayName.' : '.$LoadPortOfficeDateFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[76]->FieldName.'2>'.$tptfields[76]->NewDisplayName.'</'.$tptfields[76]->FieldName.'2> : <'.$tptfields[76]->FieldName.'3>'.$LoadPortOfficeDateFrom.'</'.$tptfields[76]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[77]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[77]->NewDisplayName.' : '.$LoadPortOfficeDateTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[77]->FieldName.'2>'.$tptfields[77]->NewDisplayName.'</'.$tptfields[77]->FieldName.'2> : <'.$tptfields[77]->FieldName.'3>'.$LoadPortOfficeDateTo.'</'.$tptfields[77]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[78]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[78]->NewDisplayName.' : '.$LoadPortOfficeTimeFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[78]->FieldName.'2>'.$tptfields[78]->NewDisplayName.'</'.$tptfields[78]->FieldName.'2> : <'.$tptfields[78]->FieldName.'3>'.$LoadPortOfficeTimeFrom.'</'.$tptfields[78]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[79]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[79]->NewDisplayName.' : '.$LoadPortOfficeTimeTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[79]->FieldName.'2>'.$tptfields[79]->NewDisplayName.'</'.$tptfields[79]->FieldName.'2> : <'.$tptfields[79]->FieldName.'3>'.$LoadPortOfficeTimeTo.'</'.$tptfields[79]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[82]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[82]->NewDisplayName.' : '.$LoadPortLaytimeDayFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[82]->FieldName.'2>'.$tptfields[82]->NewDisplayName.'</'.$tptfields[82]->FieldName.'2> : <'.$tptfields[82]->FieldName.'3>'.$LoadPortLaytimeDayFrom.'</'.$tptfields[82]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[83]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[83]->NewDisplayName.' : '.$LoadPortLaytimeDayTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[83]->FieldName.'2>'.$tptfields[83]->NewDisplayName.'</'.$tptfields[83]->FieldName.'2> : <'.$tptfields[83]->FieldName.'3>'.$LoadPortLaytimeDayTo.'</'.$tptfields[83]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[84]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[84]->NewDisplayName.' : '.$LoadPortLaytimeTimeFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[84]->FieldName.'2>'.$tptfields[84]->NewDisplayName.'</'.$tptfields[84]->FieldName.'2> : <'.$tptfields[84]->FieldName.'3>'.$LoadPortLaytimeTimeFrom.'</'.$tptfields[84]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[85]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[85]->NewDisplayName.' : '.$LoadPortLaytimeTimeTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[85]->FieldName.'2>'.$tptfields[85]->NewDisplayName.'</'.$tptfields[85]->FieldName.'2> : <'.$tptfields[85]->FieldName.'3>'.$LoadPortLaytimeTimeTo.'</'.$tptfields[85]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[87]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[87]->NewDisplayName.' : '.$LoadPortLaytimeTurnTimeExpire.'</p>';
				} else {
					$html .='<p><'.$tptfields[87]->FieldName.'2>'.$tptfields[87]->NewDisplayName.'</'.$tptfields[87]->FieldName.'2> : <'.$tptfields[87]->FieldName.'3>'.$LoadPortLaytimeTurnTimeExpire.'</'.$tptfields[87]->FieldName.'3></p>';	
				}
			}
			
			
			if($tptfields[88]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[88]->NewDisplayName.' : '.$LoadPortLaytimeCommenceAt.'</p>';
				} else {
					$html .='<p><'.$tptfields[88]->FieldName.'2>'.$tptfields[88]->NewDisplayName.'</'.$tptfields[88]->FieldName.'2> : <'.$tptfields[88]->FieldName.'3>'.$LoadPortLaytimeCommenceAt.'</'.$tptfields[88]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[89]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[89]->NewDisplayName.' : '.$LoadPortLaytimeCommenceAtHour.'</p>';
				} else {
					$html .='<p><'.$tptfields[89]->FieldName.'2>'.$tptfields[89]->NewDisplayName.'</'.$tptfields[89]->FieldName.'2> : <'.$tptfields[89]->FieldName.'3>'.$LoadPortLaytimeCommenceAtHour.'</'.$tptfields[89]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[90]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[90]->NewDisplayName.' : '.$LoadPortLaytimeSelectDay.'</p>';
				} else {
					$html .='<p><'.$tptfields[90]->FieldName.'2>'.$tptfields[90]->NewDisplayName.'</'.$tptfields[90]->FieldName.'2> : <'.$tptfields[90]->FieldName.'3>'.$LoadPortLaytimeSelectDay.'</'.$tptfields[90]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[91]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[91]->NewDisplayName.' : '.$LoadPortLaytimeTimeCountsIfOnDemurrage.'</p>';
				} else {
					$html .='<p><'.$tptfields[91]->FieldName.'2>'.$tptfields[91]->NewDisplayName.'</'.$tptfields[91]->FieldName.'2> : <'.$tptfields[91]->FieldName.'3>'.$LoadPortLaytimeTimeCountsIfOnDemurrage.'</'.$tptfields[91]->FieldName.'3></p>';	
				}
			}
		
			//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Disport</b></p>';
			
			if($tptfields[92]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[92]->NewDisplayName.' : '.$DisPort.'</p>';
				} else {
					$html .='<p><'.$tptfields[92]->FieldName.'2>'.$tptfields[92]->NewDisplayName.'</'.$tptfields[92]->FieldName.'2> : <'.$tptfields[92]->FieldName.'3>'.$DisPort.'</'.$tptfields[92]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[92]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[92]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[92]->FieldName;	
					$data_arr['FieldValue']=$DisPort;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[93]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_laycan_from_date_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[93]->NewDisplayName.' : '.$DpArrivalStartDate.'</p>';
				} else {
					$html .='<p><'.$tptfields[93]->FieldName.'2>'.$tptfields[93]->NewDisplayName.'</'.$tptfields[93]->FieldName.'2> : <'.$tptfields[93]->FieldName.'3>'.$DpArrivalStartDate.'</'.$tptfields[93]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[93]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[93]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[93]->FieldName;	
					$data_arr['FieldValue']=$DpArrivalStartDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[94]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_laycan_to_date_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[94]->NewDisplayName.' : '.$DpArrivalEndDate.'</p>';
				} else {
					$html .='<p><'.$tptfields[94]->FieldName.'2>'.$tptfields[94]->NewDisplayName.'</'.$tptfields[94]->FieldName.'2> : <'.$tptfields[94]->FieldName.'3>'.$DpArrivalEndDate.'</'.$tptfields[94]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[94]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[94]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[94]->FieldName;	
					$data_arr['FieldValue']=$DpArrivalEndDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[95]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_prefered_arrival_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[95]->NewDisplayName.' : '.$DpPreferDate.'</p>';
				} else {
					$html .='<p><'.$tptfields[95]->FieldName.'2>'.$tptfields[95]->NewDisplayName.'</'.$tptfields[95]->FieldName.'2> : <'.$tptfields[95]->FieldName.'3>'.$DpPreferDate.'</'.$tptfields[95]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[95]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[95]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[95]->FieldName;	
					$data_arr['FieldValue']=$DpPreferDate;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[96]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='disport_prefered_arrival_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[96]->NewDisplayName.' : '.$ExpectedDpDelayDay.'</p>';
				} else {
					$html .='<p><'.$tptfields[96]->FieldName.'2>'.$tptfields[96]->NewDisplayName.'</'.$tptfields[96]->FieldName.'2> : <'.$tptfields[96]->FieldName.'3>'.$ExpectedDpDelayDay.'</'.$tptfields[96]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[96]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[96]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[96]->FieldName;	
					$data_arr['FieldValue']=$ExpectedDpDelayDay;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[97]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_terms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[97]->NewDisplayName.' : '.$DischargingTerms.'</p>';
				} else {
					$html .='<p><'.$tptfields[97]->FieldName.'2>'.$tptfields[97]->NewDisplayName.'</'.$tptfields[97]->FieldName.'2> : <'.$tptfields[97]->FieldName.'3>'.$DischargingTerms.'</'.$tptfields[97]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[97]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[97]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[97]->FieldName;	
					$data_arr['FieldValue']=$DischargingTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[98]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_rate_mt_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[98]->NewDisplayName.' : '.$DischargingRateMT.'</p>';
				} else {
					$html .='<p><'.$tptfields[98]->FieldName.'2>'.$tptfields[98]->NewDisplayName.'</'.$tptfields[98]->FieldName.'2> : <'.$tptfields[98]->FieldName.'3>'.$DischargingRateMT.'</'.$tptfields[98]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[98]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[98]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[98]->FieldName;	
					$data_arr['FieldValue']=$DischargingRateMT;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[99]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='discharging_rage_uom_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[99]->NewDisplayName.' : '.$DischargingRateUOM.'</p>';
				} else {
					$html .='<p><'.$tptfields[99]->FieldName.'2>'.$tptfields[99]->NewDisplayName.'</'.$tptfields[99]->FieldName.'2> : <'.$tptfields[99]->FieldName.'3>'.$DischargingRateUOM.'</'.$tptfields[99]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[99]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[99]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[99]->FieldName;	
					$data_arr['FieldValue']=$DischargingRateUOM;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[100]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='max_time_to_discharge_hrs_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[100]->NewDisplayName.' : '.$DpMaxTime.'</p>';
				} else {
					$html .='<p><'.$tptfields[100]->FieldName.'2>'.$tptfields[100]->NewDisplayName.'</'.$tptfields[100]->FieldName.'2> : <'.$tptfields[100]->FieldName.'3>'.$DpMaxTime.'</'.$tptfields[100]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[100]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[100]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[100]->FieldName;	
					$data_arr['FieldValue']=$DpMaxTime;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[101]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_type_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[101]->NewDisplayName.' : '.$DpLaytimeType.'</p>';
				} else {
					$html .='<p><'.$tptfields[101]->FieldName.'2>'.$tptfields[101]->NewDisplayName.'</'.$tptfields[101]->FieldName.'2> : <'.$tptfields[101]->FieldName.'3>'.$DpLaytimeType.'</'.$tptfields[101]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[101]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[101]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[101]->FieldName;	
					$data_arr['FieldValue']=$DpLaytimeType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[102]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_tonnage_calc_based_on_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[102]->NewDisplayName.' : '.$DpCalculationBasedOn.'</p>';
				} else {
					$html .='<p><'.$tptfields[102]->FieldName.'2>'.$tptfields[102]->NewDisplayName.'</'.$tptfields[102]->FieldName.'2> : <'.$tptfields[102]->FieldName.'3>'.$DpCalculationBasedOn.'</'.$tptfields[102]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[102]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[102]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[102]->FieldName;	
					$data_arr['FieldValue']=$DpCalculationBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[103]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='turn_free_time_hours_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[103]->NewDisplayName.' : '.$DpTurnTime.'</p>';
				} else {
					$html .='<p><'.$tptfields[103]->FieldName.'2>'.$tptfields[103]->NewDisplayName.'</'.$tptfields[103]->FieldName.'2> : <'.$tptfields[103]->FieldName.'3>'.$DpTurnTime.'</'.$tptfields[103]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[103]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[103]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[103]->FieldName;	
					$data_arr['FieldValue']=$DpTurnTime;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[104]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='prior_use_terms_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[104]->NewDisplayName.' : '.$DpPriorUseTerms.'</p>';
				} else {
					$html .='<p><'.$tptfields[104]->FieldName.'2>'.$tptfields[104]->NewDisplayName.'</'.$tptfields[104]->FieldName.'2> : <'.$tptfields[104]->FieldName.'3>'.$DpPriorUseTerms.'</'.$tptfields[104]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[104]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[104]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[104]->FieldName;	
					$data_arr['FieldValue']=$DpPriorUseTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			
			if($tptfields[105]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='lay_time_based_on_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[105]->NewDisplayName.' : '.$DpLaytimeBasedOn.'</p>';
				} else {
					$html .='<p><'.$tptfields[105]->FieldName.'2>'.$tptfields[105]->NewDisplayName.'</'.$tptfields[105]->FieldName.'2> : <'.$tptfields[105]->FieldName.'3>'.$DpLaytimeBasedOn.'</'.$tptfields[105]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[105]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[105]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[105]->FieldName;	
					$data_arr['FieldValue']=$DpLaytimeBasedOn;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[106]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='type_of_charter_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[106]->NewDisplayName.' : '.$DpCharterType.'</p>';
				} else {
					$html .='<p><'.$tptfields[106]->FieldName.'2>'.$tptfields[106]->NewDisplayName.'</'.$tptfields[106]->FieldName.'2> : <'.$tptfields[106]->FieldName.'3>'.$DpCharterType.'</'.$tptfields[106]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[106]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[106]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[106]->FieldName;	
					$data_arr['FieldValue']=$DpCharterType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			
			if($tptfields[107]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tender_disport_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[107]->NewDisplayName.' : '.$DpNorTendering.'</p>';
				} else {
					$html .='<p><'.$tptfields[107]->FieldName.'2>'.$tptfields[107]->NewDisplayName.'</'.$tptfields[107]->FieldName.'2> : <'.$tptfields[107]->FieldName.'3>'.$DpNorTendering.'</'.$tptfields[107]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[107]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[107]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[107]->FieldName;	
					$data_arr['FieldValue']=$DpNorTendering;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[108]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='DpStevedoringTerms_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[108]->NewDisplayName.' : '.$DpStevedoringTerms.'</p>';
				} else {
					$html .='<p><'.$tptfields[108]->FieldName.'2>'.$tptfields[108]->NewDisplayName.'</'.$tptfields[108]->FieldName.'2> : <'.$tptfields[108]->FieldName.'3>'.$DpStevedoringTerms.'</'.$tptfields[108]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[108]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[108]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[108]->FieldName;	
					$data_arr['FieldValue']=$DpStevedoringTerms;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
			}
			
			if($tptfields[110]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[110]->NewDisplayName.' : '.$DisportEventName.'</p>';
				} else {
					$html .='<p><'.$tptfields[110]->FieldName.'2>'.$tptfields[110]->NewDisplayName.'</'.$tptfields[110]->FieldName.'2> : <'.$tptfields[110]->FieldName.'3>'.$DisportEventName.'</'.$tptfields[110]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[111]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[111]->NewDisplayName.' : '.$DisportLaytimeCountsOnDemurrage.'</p>';
				} else {
					$html .='<p><'.$tptfields[111]->FieldName.'2>'.$tptfields[111]->NewDisplayName.'</'.$tptfields[111]->FieldName.'2> : <'.$tptfields[111]->FieldName.'3>'.$DisportLaytimeCountsOnDemurrage.'</'.$tptfields[111]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[112]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[112]->NewDisplayName.' : '.$DisportLaytimeCounts.'</p>';
				} else {
					$html .='<p><'.$tptfields[112]->FieldName.'2>'.$tptfields[112]->NewDisplayName.'</'.$tptfields[112]->FieldName.'2> : <'.$tptfields[112]->FieldName.'3>'.$DisportLaytimeCounts.'</'.$tptfields[112]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[113]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='excepted_periods_for_events_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[113]->NewDisplayName.' : '.$DisportTimeCounting.'</p>';
				} else {
					$html .='<p><'.$tptfields[113]->FieldName.'2>'.$tptfields[113]->NewDisplayName.'</'.$tptfields[113]->FieldName.'2> : <'.$tptfields[113]->FieldName.'3>'.$DisportTimeCounting.'</'.$tptfields[113]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[115]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[115]->NewDisplayName.' : '.$DisportCreateNewOrSelectListTendering.'</p>';
				} else {
					$html .='<p><'.$tptfields[115]->FieldName.'2>'.$tptfields[115]->NewDisplayName.'</'.$tptfields[115]->FieldName.'2> : <'.$tptfields[115]->FieldName.'3>'.$DisportCreateNewOrSelectListTendering.'</'.$tptfields[115]->FieldName.'3></p>';
				}
			}
			
			
			if($tptfields[116]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[116]->NewDisplayName.' : '.$DisportNORTenderingPreCondition.'</p>';
				} else {
					$html .='<p><'.$tptfields[116]->FieldName.'2>'.$tptfields[116]->NewDisplayName.'</'.$tptfields[116]->FieldName.'2> : <'.$tptfields[116]->FieldName.'3>'.$DisportNORTenderingPreCondition.'</'.$tptfields[116]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[117]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_tendering_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[117]->NewDisplayName.' : '.$DisportTenderingStatus.'</p>';
				} else {
					$html .='<p><'.$tptfields[117]->FieldName.'2>'.$tptfields[117]->NewDisplayName.'</'.$tptfields[117]->FieldName.'2> : <'.$tptfields[117]->FieldName.'3>'.$DisportTenderingStatus.'</'.$tptfields[117]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[119]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[119]->NewDisplayName.' : '.$DisportCreateNewOrSelectListAcceptance.'</p>';
				} else {
					$html .='<p><'.$tptfields[119]->FieldName.'2>'.$tptfields[119]->NewDisplayName.'</'.$tptfields[119]->FieldName.'2> : <'.$tptfields[119]->FieldName.'3>'.$DisportCreateNewOrSelectListAcceptance.'</'.$tptfields[119]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[120]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[120]->NewDisplayName.' : '.$DisportNORAcceptancePreCondition.'</p>';
				} else {
					$html .='<p><'.$tptfields[120]->FieldName.'2>'.$tptfields[120]->NewDisplayName.'</'.$tptfields[120]->FieldName.'2> : <'.$tptfields[120]->FieldName.'3>'.$DisportNORAcceptancePreCondition.'</'.$tptfields[120]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[121]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='nor_acceptance_pre_condition_apply_disport' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[121]->NewDisplayName.' : '.$DisportAcceptanceStatus.'</p>';
				} else {
					$html .='<p><'.$tptfields[121]->FieldName.'2>'.$tptfields[121]->NewDisplayName.'</'.$tptfields[121]->FieldName.'2> : <'.$tptfields[121]->FieldName.'3>'.$DisportAcceptanceStatus.'</'.$tptfields[121]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[123]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[123]->NewDisplayName.' : '.$DisportOfficeDateFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[123]->FieldName.'2>'.$tptfields[123]->NewDisplayName.'</'.$tptfields[123]->FieldName.'2> : <'.$tptfields[123]->FieldName.'3>'.$DisportOfficeDateFrom.'</'.$tptfields[123]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[124]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[124]->NewDisplayName.' : '.$DisportOfficeDateTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[124]->FieldName.'2>'.$tptfields[124]->NewDisplayName.'</'.$tptfields[124]->FieldName.'2> : <'.$tptfields[124]->FieldName.'3>'.$DisportOfficeDateTo.'</'.$tptfields[124]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[125]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[125]->NewDisplayName.' : '.$DisportOfficeTimeFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[125]->FieldName.'2>'.$tptfields[125]->NewDisplayName.'</'.$tptfields[125]->FieldName.'2> : <'.$tptfields[125]->FieldName.'3>'.$DisportOfficeTimeFrom.'</'.$tptfields[125]->FieldName.'3></p>';	
				}
			}
			
			
			if($tptfields[126]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='office_hours_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[126]->NewDisplayName.' : '.$DisportOfficeTimeTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[126]->FieldName.'2>'.$tptfields[126]->NewDisplayName.'</'.$tptfields[126]->FieldName.'2> : <'.$tptfields[126]->FieldName.'3>'.$DisportOfficeTimeTo.'</'.$tptfields[126]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[129]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[129]->NewDisplayName.' : '.$DisportLaytimeDayFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[129]->FieldName.'2>'.$tptfields[129]->NewDisplayName.'</'.$tptfields[129]->FieldName.'2> : <'.$tptfields[129]->FieldName.'3>'.$DisportLaytimeDayFrom.'</'.$tptfields[129]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[130]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[130]->NewDisplayName.' : '.$DisportLaytimeDayTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[130]->FieldName.'2>'.$tptfields[130]->NewDisplayName.'</'.$tptfields[130]->FieldName.'2> : <'.$tptfields[130]->FieldName.'3>'.$DisportLaytimeDayTo.'</'.$tptfields[130]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[131]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[131]->NewDisplayName.' : '.$DisportLaytimeTimeFrom.'</p>';
				} else {
					$html .='<p><'.$tptfields[131]->FieldName.'2>'.$tptfields[131]->NewDisplayName.'</'.$tptfields[131]->FieldName.'2> : <'.$tptfields[131]->FieldName.'3>'.$DisportLaytimeTimeFrom.'</'.$tptfields[131]->FieldName.'3></p>';	
				}
			}
			
			
			
			if($tptfields[132]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[132]->NewDisplayName.' : '.$DisportLaytimeTimeTo.'</p>';
				} else {
					$html .='<p><'.$tptfields[132]->FieldName.'2>'.$tptfields[132]->NewDisplayName.'</'.$tptfields[132]->FieldName.'2> : <'.$tptfields[132]->FieldName.'3>'.$DisportLaytimeTimeTo.'</'.$tptfields[132]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[133]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[133]->NewDisplayName.' : '.$DisportLaytimeTurnTime.'</p>';
				} else {
					$html .='<p><'.$tptfields[133]->FieldName.'2>'.$tptfields[133]->NewDisplayName.'</'.$tptfields[133]->FieldName.'2> : <'.$tptfields[133]->FieldName.'3>'.$DisportLaytimeTurnTime.'</'.$tptfields[133]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[134]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[134]->NewDisplayName.' : '.$DisportLaytimeTurnTimeExpire.'</p>';
				} else {
					$html .='<p><'.$tptfields[134]->FieldName.'2>'.$tptfields[134]->NewDisplayName.'</'.$tptfields[134]->FieldName.'2> : <'.$tptfields[134]->FieldName.'3>'.$DisportLaytimeTurnTimeExpire.'</'.$tptfields[134]->FieldName.'3></p>';	
				}
			}
			
			if($tptfields[135]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[135]->NewDisplayName.' : '.$DisportLaytimeCommenceAt.'</p>';
				} else {
					$html .='<p><'.$tptfields[135]->FieldName.'2>'.$tptfields[135]->NewDisplayName.'</'.$tptfields[135]->FieldName.'2> : <'.$tptfields[135]->FieldName.'3>'.$DisportLaytimeCommenceAt.'</'.$tptfields[135]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[136]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[136]->NewDisplayName.' : '.$DisportLaytimeCommenceAtHour.'</p>';
				} else {
					$html .='<p><'.$tptfields[136]->FieldName.'2>'.$tptfields[136]->NewDisplayName.'</'.$tptfields[136]->FieldName.'2> : <'.$tptfields[136]->FieldName.'3>'.$DisportLaytimeCommenceAtHour.'</'.$tptfields[136]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[137]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[137]->NewDisplayName.' : '.$DisportLaytimeSelectDay.'</p>';
				} else {
					$html .='<p><'.$tptfields[137]->FieldName.'2>'.$tptfields[137]->NewDisplayName.'</'.$tptfields[137]->FieldName.'2> : <'.$tptfields[137]->FieldName.'3>'.$DisportLaytimeSelectDay.'</'.$tptfields[137]->FieldName.'3></p>';
				}
			}
			
			if($tptfields[138]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='laytime_commencement_disports_editable_field' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[138]->NewDisplayName.' : '.$DisportLaytimeTimeCountsIfOnDemurrage.'</p>';
				} else {
					$html .='<p><'.$tptfields[138]->FieldName.'2>'.$tptfields[138]->NewDisplayName.'</'.$tptfields[138]->FieldName.'2> : <'.$tptfields[138]->FieldName.'3>'.$DisportLaytimeTimeCountsIfOnDemurrage.'</'.$tptfields[138]->FieldName.'3></p>';	
				}
			}
			
			
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Brokerage</b></p>';
		
		if($tptfields[141]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity_Type1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[141]->NewDisplayName.' : '.$BrokeragePayingEntityType.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[141]->FieldName.'2>'.$tptfields[141]->NewDisplayName.'</Brokerage'.$tptfields[141]->FieldName.'2> : <Brokerage'.$tptfields[141]->FieldName.'3>'.$BrokeragePayingEntityType.'</Brokerage'.$tptfields[141]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[141]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[141]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[141]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
				
			}
			
			if($tptfields[142]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[142]->NewDisplayName.' : '.$BrokeragePayingEntityName.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[142]->FieldName.'2>'.$tptfields[142]->NewDisplayName.'</Brokerage'.$tptfields[142]->FieldName.'2> : <Brokerage'.$tptfields[142]->FieldName.'3>'.$BrokeragePayingEntityName.'</Brokerage'.$tptfields[142]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[142]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[142]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[142]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[143]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity_Type1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[143]->NewDisplayName.' : '.$BrokerageReceivingEntityType.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[143]->FieldName.'2>'.$tptfields[143]->NewDisplayName.'</Brokerage'.$tptfields[143]->FieldName.'2> : <Brokerage'.$tptfields[143]->FieldName.'3>'.$BrokerageReceivingEntityType.'</Brokerage'.$tptfields[143]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[143]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[143]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[143]->FieldName;	
					$data_arr['FieldValue']=$BrokerageReceivingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[144]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[144]->NewDisplayName.' : '.$BrokerageReceivingEntityName.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[144]->FieldName.'2>'.$tptfields[144]->NewDisplayName.'</Brokerage'.$tptfields[144]->FieldName.'2> : <Brokerage'.$tptfields[144]->FieldName.'3>'.$BrokerageReceivingEntityName.'</Brokerage'.$tptfields[144]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[144]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[144]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[144]->FieldName;	
					$data_arr['FieldValue']=$BrokerageReceivingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[145]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokers_name1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[145]->NewDisplayName.' : '.$BrokerageBrokerName.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[145]->FieldName.'2>'.$tptfields[145]->NewDisplayName.'</Brokerage'.$tptfields[145]->FieldName.'2> : <Brokerage'.$tptfields[145]->FieldName.'3>'.$BrokerageBrokerName.'</Brokerage'.$tptfields[145]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[145]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[145]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[145]->FieldName;	
					$data_arr['FieldValue']=$BrokerageBrokerName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[146]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_payable_as1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[146]->NewDisplayName.' : '.$BrokeragePayableAs.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[146]->FieldName.'2>'.$tptfields[146]->NewDisplayName.'</Brokerage'.$tptfields[146]->FieldName.'2> : <Brokerage'.$tptfields[146]->FieldName.'3>'.$BrokeragePayableAs.'</Brokerage'.$tptfields[146]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[146]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[146]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[146]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePayableAs;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[147]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_freight1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[147]->NewDisplayName.' : '.$BrokeragePercentageOnFreight.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[147]->FieldName.'2>'.$tptfields[147]->NewDisplayName.'</Brokerage'.$tptfields[147]->FieldName.'2> : <Brokerage'.$tptfields[147]->FieldName.'3>'.$BrokeragePercentageOnFreight.'</Brokerage'.$tptfields[147]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[147]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[147]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[147]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[148]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_dead_freight1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[148]->NewDisplayName.' : '.$BrokeragePercentageOnDeadFreight.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[148]->FieldName.'2>'.$tptfields[148]->NewDisplayName.'</Brokerage'.$tptfields[148]->FieldName.'2> : <Brokerage'.$tptfields[148]->FieldName.'3>'.$BrokeragePercentageOnDeadFreight.'</Brokerage'.$tptfields[148]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[148]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[148]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[148]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnDeadFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
		
			if($tptfields[149]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_demurrage1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[149]->NewDisplayName.' : '.$BrokeragePercentageOnDemmurage.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[149]->FieldName.'2>'.$tptfields[149]->NewDisplayName.'</Brokerage'.$tptfields[149]->FieldName.'2> : <Brokerage'.$tptfields[149]->FieldName.'3>'.$BrokeragePercentageOnDemmurage.'</Brokerage'.$tptfields[149]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[149]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[149]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[149]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnDemmurage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[150]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokerage_on_overage_qty1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[150]->NewDisplayName.' : '.$BrokeragePercentageOnOverage.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[150]->FieldName.'2>'.$tptfields[150]->NewDisplayName.'</Brokerage'.$tptfields[150]->FieldName.'2> : <Brokerage'.$tptfields[150]->FieldName.'3>'.$BrokeragePercentageOnOverage.'</Brokerage'.$tptfields[150]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[150]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[150]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[150]->FieldName;	
					$data_arr['FieldValue']=$BrokeragePercentageOnOverage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[151]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Lumpsum_amount_payable1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[151]->NewDisplayName.' : '.$BrokerageLumpsumPayable.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[151]->FieldName.'2>'.$tptfields[151]->NewDisplayName.'</Brokerage'.$tptfields[151]->FieldName.'2> : <Brokerage'.$tptfields[151]->FieldName.'3>'.$BrokerageLumpsumPayable.'</Brokerage'.$tptfields[151]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[151]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[151]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[151]->FieldName;	
					$data_arr['FieldValue']=$BrokerageLumpsumPayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[152]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Enter_rate_tonne1_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[152]->NewDisplayName.' : '.$BrokerageRatePerTonnePayable.'</p>';
				} else {
					$html .='<p><Brokerage'.$tptfields[152]->FieldName.'2>'.$tptfields[152]->NewDisplayName.'</Brokerage'.$tptfields[152]->FieldName.'2> : <Brokerage'.$tptfields[152]->FieldName.'3>'.$BrokerageRatePerTonnePayable.'</Brokerage'.$tptfields[152]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[152]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[152]->NewDisplayName;	
					$data_arr['FieldColumnName']='Brokerage'.$tptfields[152]->FieldName;	
					$data_arr['FieldValue']=$BrokerageRatePerTonnePayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Add Com</b></p>';
		
			if($tptfields[154]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity_Type2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[154]->NewDisplayName.' : '.$AddCommPayingEntityType.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[154]->FieldName.'2>'.$tptfields[154]->NewDisplayName.'</AddComm'.$tptfields[154]->FieldName.'2> : <AddComm'.$tptfields[154]->FieldName.'3>'.$AddCommPayingEntityType.'</AddComm'.$tptfields[154]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[154]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[154]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[154]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[155]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Paying_Entity2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[155]->NewDisplayName.' : '.$AddCommPayingEntityName.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[155]->FieldName.'2>'.$tptfields[155]->NewDisplayName.'</AddComm'.$tptfields[155]->FieldName.'2> : <AddComm'.$tptfields[155]->FieldName.'3>'.$AddCommPayingEntityName.'</AddComm'.$tptfields[155]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[155]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[155]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[155]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[156]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity_Type2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[156]->NewDisplayName.' : '.$AddCommReceivingEntityType.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[156]->FieldName.'2>'.$tptfields[156]->NewDisplayName.'<AddComm/'.$tptfields[156]->FieldName.'2> : <AddComm'.$tptfields[156]->FieldName.'3>'.$AddCommReceivingEntityType.'</AddComm'.$tptfields[156]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[156]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[156]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[156]->FieldName;	
					$data_arr['FieldValue']=$AddCommReceivingEntityType;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[157]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Receiving_Entity2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[157]->NewDisplayName.' : '.$AddCommReceivingEntityName.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[157]->FieldName.'2>'.$tptfields[157]->NewDisplayName.'</AddComm'.$tptfields[157]->FieldName.'2> : <AddComm'.$tptfields[157]->FieldName.'3>'.$AddCommReceivingEntityName.'</AddComm'.$tptfields[157]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[157]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[157]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[157]->FieldName;	
					$data_arr['FieldValue']=$AddCommReceivingEntityName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
		
		
			if($tptfields[158]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Brokers_name2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[158]->NewDisplayName.' : '.$AddCommBrokerName.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[158]->FieldName.'2>'.$tptfields[158]->NewDisplayName.'</AddComm'.$tptfields[158]->FieldName.'2> : <AddComm'.$tptfields[158]->FieldName.'3>'.$AddCommBrokerName.'</AddComm'.$tptfields[158]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[158]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[158]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[158]->FieldName;	
					$data_arr['FieldValue']=$AddCommBrokerName;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[159]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_payable_as2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[159]->NewDisplayName.' : '.$AddCommPayableAs.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[159]->FieldName.'2>'.$tptfields[159]->NewDisplayName.'</AddComm'.$tptfields[159]->FieldName.'2> : <AddComm'.$tptfields[159]->FieldName.'3>'.$AddCommPayableAs.'</AddComm'.$tptfields[159]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[159]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[159]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[159]->FieldName;	
					$data_arr['FieldValue']=$AddCommPayableAs;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[160]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_freight2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[160]->NewDisplayName.' : '.$AddCommPercentageOnFreight.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[160]->FieldName.'2>'.$tptfields[160]->NewDisplayName.'</AddComm'.$tptfields[160]->FieldName.'2> : <AddComm'.$tptfields[160]->FieldName.'3>'.$AddCommPercentageOnFreight.'</AddComm'.$tptfields[160]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[160]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[160]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[160]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[161]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_dead_freight2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[161]->NewDisplayName.' : '.$AddCommPercentageOnDeadFreight.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[161]->FieldName.'2>'.$tptfields[161]->NewDisplayName.'</AddComm'.$tptfields[161]->FieldName.'2> : <AddComm'.$tptfields[161]->FieldName.'3>'.$AddCommPercentageOnDeadFreight.'</AddComm'.$tptfields[161]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[161]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[161]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[161]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnDeadFreight;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[162]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_demurrage2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[162]->NewDisplayName.' : '.$AddCommPercentageOnDemmurage.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[162]->FieldName.'2>'.$tptfields[162]->NewDisplayName.'</AddComm'.$tptfields[162]->FieldName.'2> : <AddComm'.$tptfields[162]->FieldName.'3>'.$AddCommPercentageOnDemmurage.'</AddComm'.$tptfields[162]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[162]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[162]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[162]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnDemmurage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[163]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Add_Comm_on_overage_qty2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[163]->NewDisplayName.' : '.$AddCommPercentageOnOverage.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[163]->FieldName.'2>'.$tptfields[163]->NewDisplayName.'</AddComm'.$tptfields[163]->FieldName.'2> : <AddComm'.$tptfields[163]->FieldName.'3>'.$AddCommPercentageOnOverage.'</AddComm'.$tptfields[163]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[163]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[163]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[163]->FieldName;	
					$data_arr['FieldValue']=$AddCommPercentageOnOverage;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
						
			}
			
			if($tptfields[164]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Lumpsum_amount_payable2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[164]->NewDisplayName.' : '.$AddCommLumpsumPayable.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[164]->FieldName.'2>'.$tptfields[164]->NewDisplayName.'</AddComm'.$tptfields[164]->FieldName.'2> : <AddComm'.$tptfields[164]->FieldName.'3>'.$AddCommLumpsumPayable.'</AddComm'.$tptfields[164]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[164]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[164]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[164]->FieldName;	
					$data_arr['FieldValue']=$AddCommLumpsumPayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
					
			}
			
			if($tptfields[165]->Included){
				$ef_flag=0;
				foreach($EditField as $ed_row) {
					if($ed_row->ChkLabel=='Enter_rate_tonne2_editable_filed' && $ed_row->ChkFlag=='true') {
						$ef_flag=1;	
					}
				}	
				
				if($ef_flag==1) {
					$html .='<p contenteditable="false" style="background-color: #efeaead6">'.$tptfields[165]->NewDisplayName.' : '.$AddCommRatePerTonnePayable.'</p>';
				} else {
					$html .='<p><AddComm'.$tptfields[165]->FieldName.'2>'.$tptfields[165]->NewDisplayName.'</AddComm'.$tptfields[165]->FieldName.'2> : <AddComm'.$tptfields[165]->FieldName.'3>'.$AddCommRatePerTonnePayable.'</AddComm'.$tptfields[165]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[165]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[165]->NewDisplayName;	
					$data_arr['FieldColumnName']='AddComm'.$tptfields[165]->FieldName;	
					$data_arr['FieldValue']=$AddCommRatePerTonnePayable;		
					$data_arr['GroupNumber']=1;				
					array_push($fix_data_arr,$data_arr);	
				}
						
			}
			
			//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Others</b></p>';
			
			if($tptfields[167]->Included){
					$html .='<p><'.$tptfields[167]->FieldName.'2>'.$tptfields[167]->NewDisplayName.'</'.$tptfields[167]->FieldName.'2> : <'.$tptfields[167]->FieldName.'3>'.$OtherPayingEntityType.'</'.$tptfields[167]->FieldName.'3></p>';
			}
			
			if($tptfields[168]->Included){
					$html .='<p><'.$tptfields[168]->FieldName.'2>'.$tptfields[168]->NewDisplayName.'</'.$tptfields[168]->FieldName.'2> : <'.$tptfields[168]->FieldName.'3>'.$OtherPayingEntityName.'</'.$tptfields[168]->FieldName.'3></p>';
			}
			
			if($tptfields[169]->Included){
					$html .='<p><'.$tptfields[169]->FieldName.'2>'.$tptfields[169]->NewDisplayName.'</'.$tptfields[169]->FieldName.'2> : <'.$tptfields[169]->FieldName.'3>'.$OtherReceivingEntityType.'</'.$tptfields[169]->FieldName.'3></p>';
			}
			
			if($tptfields[170]->Included){
					$html .='<p><'.$tptfields[170]->FieldName.'2>'.$tptfields[170]->NewDisplayName.'</'.$tptfields[170]->FieldName.'2> : <'.$tptfields[170]->FieldName.'3>'.$OtherReceivingEntityName.'</'.$tptfields[170]->FieldName.'3></p>';
			}
			
			if($tptfields[171]->Included){
					$html .='<p><'.$tptfields[171]->FieldName.'2>'.$tptfields[171]->NewDisplayName.'</'.$tptfields[171]->FieldName.'2> : <'.$tptfields[171]->FieldName.'3>'.$OtherBrokerName.'</'.$tptfields[171]->FieldName.'3></p>';
			}
			
			if($tptfields[172]->Included){ 
					$html .='<p><'.$tptfields[172]->FieldName.'2>'.$tptfields[172]->NewDisplayName.'</'.$tptfields[172]->FieldName.'2> : <'.$tptfields[172]->FieldName.'3>'.$OtherPayableAs.'</'.$tptfields[172]->FieldName.'3></p>';
			}
		
			if($tptfields[173]->Included){
					$html .='<p><'.$tptfields[173]->FieldName.'2>'.$tptfields[173]->NewDisplayName.'</'.$tptfields[173]->FieldName.'2> : <'.$tptfields[173]->FieldName.'3>'.$OtherPercentageOnFreight.'</'.$tptfields[173]->FieldName.'3></p>';
			}
			
			if($tptfields[174]->Included){
					$html .='<p><'.$tptfields[174]->FieldName.'2>'.$tptfields[174]->NewDisplayName.'</'.$tptfields[174]->FieldName.'2> : <'.$tptfields[174]->FieldName.'3>'.$OtherPercentageOnDeadFreight.'</'.$tptfields[174]->FieldName.'3></p>';
			}
			
			if($tptfields[175]->Included){
					$html .='<p><'.$tptfields[175]->FieldName.'2>'.$tptfields[175]->NewDisplayName.'</'.$tptfields[175]->FieldName.'2> : <'.$tptfields[175]->FieldName.'3>'.$OtherPercentageOnDemmurage.'</'.$tptfields[175]->FieldName.'3></p>';
			}
			
			if($tptfields[176]->Included){
					$html .='<p><'.$tptfields[176]->FieldName.'2>'.$tptfields[176]->NewDisplayName.'</'.$tptfields[176]->FieldName.'2> : <'.$tptfields[176]->FieldName.'3>'.$OtherPercentageOnOverage.'</'.$tptfields[176]->FieldName.'3></p>';
			}
			
			if($tptfields[177]->Included){
					$html .='<p><'.$tptfields[177]->FieldName.'2>'.$tptfields[177]->NewDisplayName.'</'.$tptfields[177]->FieldName.'2> : <'.$tptfields[177]->FieldName.'3>'.$OtherLumpsumPayable.'</'.$tptfields[177]->FieldName.'3></p>';
			}
			
			if($tptfields[178]->Included){
					$html .='<p><'.$tptfields[178]->FieldName.'2>'.$tptfields[178]->NewDisplayName.'</'.$tptfields[178]->FieldName.'2> : <'.$tptfields[178]->FieldName.'3>'.$OtherRatePerTonnePayable.'</'.$tptfields[178]->FieldName.'3></p>';
			}
			
		
		
		$ResponseID='';
		$FreightBasis='';
		$FreightRate='';
		$FreightCurrency='';
		$FreightRateUOM='';
		$FreightTce='';
		$FreightTceDifferential='';
		$FreightLumpsumMax='';
		$FreightLow='';
		$FreightHigh='';
		$FreightBasisFlag1=0;
		$FreightBasisFlag2=0;
		$FreightBasisFlag3=0;
		$Demurrage='';
		$DespatchDemurrageFlag='';
		$DespatchHalfDemurrage='';
		$DespatchDemurrageFlag1=0;
		$DespatchDemurrageFlag2=0;
		if($data4){
		foreach($data4 as $row) {
			if($row->FreightBasis==1){
				$FreightBasis .='$/mt || ';
				$FreightBasisFlag1=1;
			}
			if($row->FreightBasis==2){
				$FreightBasis .='Lumpsum || ';
				$FreightBasisFlag2=1;
			}
			if($row->FreightBasis==3){
				$FreightBasis .='High - Low ($/mt) || ';
				$FreightBasisFlag3=1;
			}
			$ResponseID .=$row->ResponseID.' || ';
			$FreightRate .=$row->FreightRate.' || ';
			$FreightCurrency .=$row->curCode.' || ';
			
			if($row->FreightRateUOM==1){
				$FreightRUOM='MT(Metric Tonnes)';
			}else if($row->FreightRateUOM==2){
				$FreightRUOM='LT(Long Tonnes)';
			}else if($row->FreightRateUOM==3){
				$FreightRUOM='PMT(Per metric tonne)';
			}else if($row->FreightRateUOM==4){
				$FreightRUOM='PLT(Per long ton)';
			}else if($row->FreightRateUOM==5){
				$FreightRUOM='WWD(Weather Working Day)';
			}
			
			$FreightRateUOM .=$FreightRUOM.' || ';
			$FreightTce .=$row->FreightTce.' || ';
			$FreightTceDifferential .=number_format($row->FreightTceDifferential).' || ';
			$FreightLumpsumMax .=$row->FreightLumpsumMax.' || ';
			$FreightLow .=$row->FreightLow.' || ';
			$FreightHigh .=$row->FreightHigh.' || ';
			$Demurrage .=number_format($row->Demurrage).' || ';
			if($row->DespatchDemurrageFlag==1){
				$DespatchDFlag='Yes';
				$DespatchDemurrageFlag1=1;
			}
			if($row->DespatchDemurrageFlag==2){
				$DespatchDFlag='No';
				$DespatchDemurrageFlag2=1;
			}
			$DespatchDemurrageFlag .=$DespatchDFlag.' || ';
			$DespatchHalfDemurrage .=number_format($row->DespatchHalfDemurrage).' || ';
		}
		}
		
		$ResponseID=rtrim($ResponseID,' || ');
		$FreightBasis=rtrim($FreightBasis,' || ');
		$FreightRate=rtrim($FreightRate,' || ');
		$FreightCurrency=rtrim($FreightCurrency,' || ');
		$FreightRateUOM=rtrim($FreightRateUOM,' || ');
		$FreightTce=rtrim($FreightTce,' || ');
		$FreightTceDifferential=rtrim($FreightTceDifferential,' || ');
		$FreightLumpsumMax=rtrim($FreightLumpsumMax,' || ');
		$FreightLow=rtrim($FreightLow,' || ');
		$FreightHigh=rtrim($FreightHigh,' || ');
		$Demurrage=rtrim($Demurrage,' || ');
		$DespatchDemurrageFlag=rtrim($DespatchDemurrageFlag,' || ');
		$DespatchHalfDemurrage=rtrim($DespatchHalfDemurrage,' || ');
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Freight Quote</b></p>';
		
		
		if($tptfields[179]->Included){
			$html .='<p><'.$tptfields[179]->FieldName.'2>'.$tptfields[179]->NewDisplayName.'</'.$tptfields[179]->FieldName.'2> : <'.$tptfields[179]->FieldName.'3>'.$ResponseID.'</'.$tptfields[179]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[179]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[179]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[179]->FieldName;		
			$data_arr['FieldValue']=$ResponseID;						
			$data_arr['GroupNumber']=2;		
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[180]->Included){
			$html .='<p><'.$tptfields[180]->FieldName.'2>'.$tptfields[180]->NewDisplayName.'</'.$tptfields[180]->FieldName.'2> : <'.$tptfields[180]->FieldName.'3>'.$FreightBasis.'</'.$tptfields[180]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[180]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[180]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[180]->FieldName;		
			$data_arr['FieldValue']=$FreightBasis;						
			$data_arr['GroupNumber']=2;		
			array_push($fix_data_arr,$data_arr);
		}
		
		if($FreightBasisFlag1==1){
				
				if($tptfields[181]->Included){
					$html .='<p><'.$tptfields[181]->FieldName.'2>'.$tptfields[181]->NewDisplayName.'</'.$tptfields[181]->FieldName.'2> : <'.$tptfields[181]->FieldName.'3>'.$FreightRate.'</'.$tptfields[181]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[181]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[181]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[181]->FieldName;		
					$data_arr['FieldValue']=$FreightRate;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[182]->Included){
					$html .='<p><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2> : <'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[183]->Included){
					$html .='<p><'.$tptfields[183]->FieldName.'2>'.$tptfields[183]->NewDisplayName.'</'.$tptfields[183]->FieldName.'2> : <'.$tptfields[183]->FieldName.'3>'.$FreightRateUOM.'</'.$tptfields[183]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[183]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[183]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[183]->FieldName;		
					$data_arr['FieldValue']=$FreightRateUOM;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[184]->Included){
					$html .='<p><'.$tptfields[184]->FieldName.'2>'.$tptfields[184]->NewDisplayName.'</'.$tptfields[184]->FieldName.'2> : <'.$tptfields[184]->FieldName.'3>'.$FreightTce.'</'.$tptfields[184]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[184]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[184]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[184]->FieldName;		
					$data_arr['FieldValue']=$FreightTce;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				
				if($tptfields[185]->Included){
					$html .='<p><'.$tptfields[185]->FieldName.'2>'.$tptfields[185]->NewDisplayName.'</'.$tptfields[185]->FieldName.'2> : <'.$tptfields[185]->FieldName.'3>'.$FreightTceDifferential.'</'.$tptfields[185]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[185]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[185]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[185]->FieldName;		
					$data_arr['FieldValue']=$FreightTceDifferential;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				
			} 
			
			if($FreightBasisFlag2==1){
				if($tptfields[186]->Included){
					$html .='<p><'.$tptfields[186]->FieldName.'2>'.$tptfields[186]->NewDisplayName.'</'.$tptfields[186]->FieldName.'2> : <'.$tptfields[186]->FieldName.'3>'.$FreightLumpsumMax.'</'.$tptfields[186]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[186]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[186]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[186]->FieldName;		
					$data_arr['FieldValue']=$FreightLumpsumMax;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[182]->Included){
					$html .='<p><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2> : <'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;							
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
			} 
			
			if($FreightBasisFlag3==1){
				if($tptfields[187]->Included){
					$html .='<p><'.$tptfields[187]->FieldName.'2>'.$tptfields[187]->NewDisplayName.'</'.$tptfields[187]->FieldName.'2> : <'.$tptfields[187]->FieldName.'3>'.$FreightLow.'</'.$tptfields[187]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[187]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[187]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[187]->FieldName;		
					$data_arr['FieldValue']=$FreightLow;					
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[188]->Included){
					$html .='<p><'.$tptfields[188]->FieldName.'2>'.$tptfields[188]->NewDisplayName.'</'.$tptfields[188]->FieldName.'2> : <'.$tptfields[188]->FieldName.'3>'.$FreightHigh.'</'.$tptfields[188]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[188]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[188]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[188]->FieldName;		
					$data_arr['FieldValue']=$FreightHigh;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				
				if($tptfields[182]->Included){
					$html .='<p><'.$tptfields[182]->FieldName.'2>'.$tptfields[182]->NewDisplayName.'</'.$tptfields[182]->FieldName.'2> : <'.$tptfields[182]->FieldName.'3>'.$FreightCurrency.'</'.$tptfields[182]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[182]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[182]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[182]->FieldName;		
					$data_arr['FieldValue']=$FreightCurrency;						
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[183]->Included){
					$html .='<p><'.$tptfields[183]->FieldName.'2>'.$tptfields[183]->NewDisplayName.'</'.$tptfields[183]->FieldName.'2> : <'.$tptfields[183]->FieldName.'3>'.$FreightRateUOM.'</'.$tptfields[183]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[183]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[183]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[183]->FieldName;		
					$data_arr['FieldValue']=$FreightRateUOM;					
					$data_arr['GroupNumber']=2;				
					array_push($fix_data_arr,$data_arr);
				}
				if($$tptfields[184]->Included){
					$html .='<p><'.$$tptfields[184]->FieldName.'2>'.$$tptfields[184]->NewDisplayName.'</'.$$tptfields[184]->FieldName.'2> : <'.$$tptfields[184]->FieldName.'3>'.$FreightTce.'</'.$$tptfields[184]->FieldName.'3></p>';
					$data_arr['CpCode']=$$tptfields[184]->CpCode;				
					$data_arr['FieldLblName']=$$tptfields[184]->NewDisplayName;	
					$data_arr['FieldColumnName']=$$tptfields[184]->FieldName;		
					$data_arr['FieldValue']=$FreightTce;					
					$data_arr['GroupNumber']=2;		
					array_push($fix_data_arr,$data_arr);
				}
				if($tptfields[185]->Included){
					$html .='<p><'.$tptfields[185]->FieldName.'2>'.$tptfields[185]->NewDisplayName.'</'.$tptfields[185]->FieldName.'2> : <'.$tptfields[185]->FieldName.'3>'.$FreightTceDifferential.'</'.$tptfields[185]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[185]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[185]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[185]->FieldName;		
					$data_arr['FieldValue']=$FreightTceDifferential;					
					$data_arr['GroupNumber']=2;			
					array_push($fix_data_arr,$data_arr);
				}
			} 
			
			if($tptfields[202]->Included){
				$html .='<p><'.$tptfields[202]->FieldName.'2>'.$tptfields[202]->NewDisplayName.'</'.$tptfields[202]->FieldName.'2> : <'.$tptfields[202]->FieldName.'3>'.$Demurrage.'</'.$tptfields[202]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[202]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[202]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[202]->FieldName;		
				$data_arr['FieldValue']=$Demurrage;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[203]->Included){
				$html .='<p><'.$tptfields[203]->FieldName.'2>'.$tptfields[203]->NewDisplayName.'</'.$tptfields[203]->FieldName.'2> : <'.$tptfields[203]->FieldName.'3>'.$DespatchDemurrageFlag.'</'.$tptfields[203]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[203]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[203]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[203]->FieldName;		
				$data_arr['FieldValue']=$DespatchDemurrageFlag;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[204]->Included){
				$html .='<p><'.$tptfields[204]->FieldName.'2>'.$tptfields[204]->NewDisplayName.'</'.$tptfields[204]->FieldName.'2> : <'.$tptfields[204]->FieldName.'3>'.$DespatchHalfDemurrage.'</'.$tptfields[204]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[204]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[204]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[204]->FieldName;		
				$data_arr['FieldValue']=$DespatchHalfDemurrage;					
				$data_arr['GroupNumber']=2;			
				array_push($fix_data_arr,$data_arr);
			}
		
		
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Differential</b></p>';
		
		
		$DifferentialDisport1='';
		$LpDpFlg1='';
		$LoadingDischargingRate1='';
		$LoadDischargeUnit1='';
		$DifferentailInviteeAmt1='';
		if($data5){
			$templinenum='';
		
		foreach($data5 as $row) {
			if($templinenum==$row->LineNum){
				continue;
			}
			$templinenum=$row->LineNum;
			$GropNoFlg='';
			$DifferentialDisport='';
			$LpDpFlg='';
			$LoadingDischargingRate='';
			$LoadDischargeUnit='';
			$DifferentailInviteeAmt='';
			$DRDResponse=$this->cargo_quote_model->getDifferentialRefDisportsResponse($row->DifferentialID);
			foreach($DRDResponse as $drdr) {
				if($drdr->LpDpFlg==1) {
				$LpDp='Lp';	
				}
				if($drdr->LpDpFlg==2) {
				$LpDp='Dp';	
				}
				if($drdr->LoadDischargeUnit==1) {
				$LDUnit='$ mt/hr';	
				}
				if($drdr->LoadDischargeUnit==2) {
				$LDUnit='$ mt/day';	
				}
				if($GropNoFlg==$drdr->GroupNo) {
					$DifferentialDisport .=$drdr->PortName.' , ';
					$LoadingDischargingRate .=$drdr->LoadDischargeRate.' , ';
					$LpDpFlg .=$LpDp.' , ';
					$LoadDischargeUnit .=$LDUnit.' , ';
					$DifferentailInviteeAmt .=$drdr->DifferentialInviteeAmt.' , ';
				} else {
					$DifferentialDisport=trim($DifferentialDisport,' , ');
					$LoadingDischargingRate=trim($LoadingDischargingRate,' , ');
					$LpDpFlg=trim($LpDpFlg,' , ');
					$LoadDischargeUnit=trim($LoadDischargeUnit,' , ');
					$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,' , ');
					$DifferentialDisport .='),('.$drdr->PortName.' , ';
					$LoadingDischargingRate .='),('.$drdr->LoadDischargeRate.' , ';
					$LpDpFlg .='),('.$LpDp.' , ';
					$LoadDischargeUnit .='),('.$LDUnit.' , ';
					$DifferentailInviteeAmt .='),('.$drdr->DifferentialInviteeAmt.' , ';
				}
				$GropNoFlg=$drdr->GroupNo;
				
			}
			$DifferentialDisport=trim($DifferentialDisport,' , ');
			$LoadingDischargingRate=trim($LoadingDischargingRate,' , ');
			$LpDpFlg=trim($LpDpFlg,' , ');
			$LoadDischargeUnit=trim($LoadDischargeUnit,' , ');
			$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,' , ');
			$DifferentialDisport=trim($DifferentialDisport,'),');
			$LoadingDischargingRate=trim($LoadingDischargingRate,'),');
			$LpDpFlg=trim($LpDpFlg,'),');
			$LoadDischargeUnit=trim($LoadDischargeUnit,'),');
			$DifferentailInviteeAmt=trim($DifferentailInviteeAmt,'),');
			$DifferentialDisport .=')';
			$LoadingDischargingRate .=')';
			$LpDpFlg .=')';
			$LoadDischargeUnit .=')';
			$DifferentailInviteeAmt .=')';
			$DifferentialDisport1 .=$DifferentialDisport.' || ';
			$LoadingDischargingRate1 .=$LoadingDischargingRate.' || ';
			$LpDpFlg1 .=$LpDpFlg.' || ';
			$LoadDischargeUnit1 .=$LoadDischargeUnit.' || ';
			$DifferentailInviteeAmt1 .=$DifferentailInviteeAmt.' || ';
		}
		}
		$DifferentialDisport1=rtrim($DifferentialDisport1,' || ');
		$LpDpFlg1=rtrim($LpDpFlg1,' || ');
		$LoadDischargeUnit1=rtrim($LoadDischargeUnit1,' || ');
		$LoadingDischargingRate1=rtrim($LoadingDischargingRate1,' || ');
		$DifferentailInviteeAmt1=rtrim($DifferentailInviteeAmt1,' || ');
		
		if($tptfields[195]->Included){
			$html .='<p><'.$tptfields[195]->FieldName.'2>'.$tptfields[195]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2> : <'.$tptfields[201]->FieldName.'3>'.$DifferentialDisport1.'</'.$tptfields[201]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[195]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[195]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[195]->FieldName;		
			$data_arr['FieldValue']=$DifferentialDisport1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[196]->Included){
			$html .='<p><'.$tptfields[201]->FieldName.'2>'.$tptfields[196]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2> : <'.$tptfields[201]->FieldName.'3>'.$LpDpFlg1.'</'.$tptfields[201]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[196]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[196]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[196]->FieldName;		
			$data_arr['FieldValue']=$LpDpFlg1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[197]->Included){
			$html .='<p><'.$tptfields[201]->FieldName.'2>'.$tptfields[197]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2> : <'.$tptfields[201]->FieldName.'3>'.$LoadingDischargingRate1.'</'.$tptfields[201]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[197]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[197]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[197]->FieldName;		
			$data_arr['FieldValue']=$LoadingDischargingRate1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[198]->Included){
			$html .='<p><'.$tptfields[201]->FieldName.'2>'.$tptfields[198]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2> : <'.$tptfields[201]->FieldName.'3>'.$LoadDischargeUnit1.'</'.$tptfields[201]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[198]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[198]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[198]->FieldName;		
			$data_arr['FieldValue']=$LoadDischargeUnit1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		if($tptfields[201]->Included){
			$html .='<p><'.$tptfields[201]->FieldName.'2>'.$tptfields[201]->NewDisplayName.'</'.$tptfields[201]->FieldName.'2> : <'.$tptfields[201]->FieldName.'3>'.$DifferentailInviteeAmt1.'</'.$tptfields[201]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[201]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[201]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[201]->FieldName;		
			$data_arr['FieldValue']=$DifferentailInviteeAmt1;						
			$data_arr['GroupNumber']=2;	
			array_push($fix_data_arr,$data_arr);
		}
		
		
		
	//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Performing vessel</b></p>';	
		
		
		
		
		if($data6){
			if($data6->SelectVesselBy==1){
				$SelectVesselBy='Vessel name incl ex_name';
			}else if($data6->SelectVesselBy==2){
				$SelectVesselBy='IMO number';
			}else if($data6->SelectVesselBy==3){
				$SelectVesselBy='Vessel not found';
			}
			if($tptfields[205]->Included){
				$html .='<p><'.$tptfields[205]->FieldName.'2>'.$tptfields[205]->NewDisplayName.'</'.$tptfields[205]->FieldName.'2> : <'.$tptfields[205]->FieldName.'3>'.$SelectVesselBy.'</'.$tptfields[205]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[205]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[205]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[205]->FieldName;		
				$data_arr['FieldValue']=$SelectVesselBy;					
				$data_arr['GroupNumber']=3;		
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[206]->Included){
				$html .='<p><'.$tptfields[206]->FieldName.'2>'.$tptfields[206]->NewDisplayName.'</'.$tptfields[206]->FieldName.'2> : <'.$tptfields[206]->FieldName.'3>'.$data6->VesselName.'</'.$tptfields[206]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[206]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[206]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[206]->FieldName;		
				$data_arr['FieldValue']=$data6->VesselName;						
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[207]->Included){
				$html .='<p><'.$tptfields[207]->FieldName.'2>'.$tptfields[207]->NewDisplayName.'</'.$tptfields[207]->FieldName.'2> : <'.$tptfields[207]->FieldName.'3>'.$data6->IMO.'</'.$tptfields[207]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[207]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[207]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[207]->FieldName;		
				$data_arr['FieldValue']=$data6->IMO;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($data6->VesselCurrentName){	
			if($tptfields[209]->Included){
				$html .='<p><'.$tptfields[209]->FieldName.'2>'.$tptfields[209]->NewDisplayName.'</'.$tptfields[209]->FieldName.'2> : <'.$tptfields[209]->FieldName.'3>'.$data6->VesselCurrentName.'</'.$tptfields[209]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[209]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[209]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[209]->FieldName;		
				$data_arr['FieldValue']=$data6->VesselCurrentName;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[210]->Included){
				$html .='<p><'.$tptfields[210]->FieldName.'2>'.$tptfields[210]->NewDisplayName.'</'.$tptfields[210]->FieldName.'2> : <'.$tptfields[210]->FieldName.'3>'.date('d-m-Y',strtotime($data6->VesselChangeNameDate)).'</'.$tptfields[210]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[210]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[210]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[210]->FieldName;		
				$data_arr['FieldValue']=$data6->VesselChangeNameDate;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			}
			if($tptfields[211]->Included){
				$FirstLoadPortDate=date('d-m-Y',strtotime($data6->FirstLoadPortDate));
				if($FirstLoadPortDate=='01-01-1970'){
					$FirstLoadPortDate='';
				}
				$html .='<p><'.$tptfields[211]->FieldName.'2>'.$tptfields[211]->NewDisplayName.'</'.$tptfields[211]->FieldName.'2> : <'.$tptfields[211]->FieldName.'3>'.$FirstLoadPortDate.'</'.$tptfields[211]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[211]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[211]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[211]->FieldName;		
				$data_arr['FieldValue']=$FirstLoadPortDate;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[212]->Included){
				$LastDisPortDate=date('d-m-Y',strtotime($data6->LastDisPortDate));
				if($LastDisPortDate=='01-01-1970'){
					$LastDisPortDate='';
				}
				$html .='<p><'.$tptfields[212]->FieldName.'2>'.$tptfields[212]->NewDisplayName.'</'.$tptfields[212]->FieldName.'2> : <'.$tptfields[212]->FieldName.'3>'.$LastDisPortDate.'</'.$tptfields[212]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[212]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[212]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[212]->FieldName;		
				$data_arr['FieldValue']=$LastDisPortDate;					
				$data_arr['GroupNumber']=3;	
				array_push($fix_data_arr,$data_arr);
			}
			
			if($tptfields[213]->Included){
				$html .='<p><'.$tptfields[213]->FieldName.'2>'.$tptfields[213]->NewDisplayName.'</'.$tptfields[213]->FieldName.'2> : <'.$tptfields[213]->FieldName.'3>'.$data6->EntityName.'</'.$tptfields[213]->FieldName.'3></p>';
			}
			if($tptfields[214]->Included){
				$html .='<p>'.$tptfields[214]->NewDisplayName.' : '.$data6->AssociateCompanyID.'</p>';
				
			}
			if($tptfields[215]->Included){
			if($data6->Address1){
				$html .='<p><'.$tptfields[215]->FieldName.'2>'.$tptfields[215]->NewDisplayName.'</'.$tptfields[215]->FieldName.'2> : <'.$tptfields[215]->FieldName.'3>'.$data6->Address1.'</'.$tptfields[215]->FieldName.'3></p>';					
			}
			}
		if($tptfields[216]->Included){
			if($data6->Address2){
				$html .='<p><'.$tptfields[216]->FieldName.'2>'.$tptfields[216]->NewDisplayName.'</'.$tptfields[216]->FieldName.'2> : <'.$tptfields[216]->FieldName.'3>'.$data6->Address2.'</'.$tptfields[216]->FieldName.'3></p>';					
			}
		}
		if($tptfields[217]->Included){
			if($data6->Address3){
				$html .='<p><'.$tptfields[217]->FieldName.'2>'.$tptfields[217]->NewDisplayName.'</'.$tptfields[217]->FieldName.'2> : <'.$tptfields[217]->FieldName.'3>'.$data6->Address3.'</'.$tptfields[217]->FieldName.'3></p>';					
			}
		}
		if($tptfields[218]->Included){
			if($data6->Address3){
				$html .='<p><'.$tptfields[218]->FieldName.'2>'.$tptfields[218]->NewDisplayName.'</'.$tptfields[218]->FieldName.'2> : <'.$tptfields[218]->FieldName.'3>'.$data6->Address4.'</'.$tptfields[218]->FieldName.'3></p>';					
			}
		}
		if($tptfields[219]->Included){
			$html .='<p><'.$tptfields[219]->FieldName.'2>'.$tptfields[219]->NewDisplayName.'</'.$tptfields[219]->FieldName.'2> : <'.$tptfields[219]->FieldName.'3>'.$data6->C_Code.' || '.$data6->C_Description.'</'.$tptfields[219]->FieldName.'3></p>';				
		}
		if($tptfields[220]->Included){
			$html .='<p><'.$tptfields[220]->FieldName.'2>'.$tptfields[220]->NewDisplayName.'</'.$tptfields[220]->FieldName.'2> : <'.$tptfields[220]->FieldName.'3>'.$data6->S_Code.' || '.$data6->S_Description.'</'.$tptfields[220]->FieldName.'3></p>';				
		}
		
		if($tptfields[229]->Included){
			$html .='<p><'.$tptfields[229]->FieldName.'2>'.$tptfields[229]->NewDisplayName.'</'.$tptfields[229]->FieldName.'2> : <'.$tptfields[229]->FieldName.'3>'.$data6->LOA.'</'.$tptfields[229]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[229]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[229]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[229]->FieldName;		
			$data_arr['FieldValue']=$data6->LOA;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[230]->Included){
			$html .='<p><'.$tptfields[230]->FieldName.'2>'.$tptfields[230]->NewDisplayName.'</'.$tptfields[230]->FieldName.'2> : <'.$tptfields[230]->FieldName.'3>'.$data6->Beam.'</'.$tptfields[230]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[230]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[230]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[230]->FieldName;		
			$data_arr['FieldValue']=$data6->Beam;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[231]->Included){
			$html .='<p><'.$tptfields[231]->FieldName.'2>'.$tptfields[231]->NewDisplayName.'</'.$tptfields[231]->FieldName.'2> : <'.$tptfields[231]->FieldName.'3>'.$data6->Draft.'</'.$tptfields[231]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[231]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[231]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[231]->FieldName;		
			$data_arr['FieldValue']=$data6->Draft;				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[232]->Included){
			$html .='<p><'.$tptfields[232]->FieldName.'2>'.$tptfields[232]->NewDisplayName.'</'.$tptfields[232]->FieldName.'2> : <'.$tptfields[232]->FieldName.'3>'.number_format($data6->DeadWeight).'</'.$tptfields[232]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[232]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[232]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[232]->FieldName;		
			$data_arr['FieldValue']=number_format($data6->DeadWeight);					
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		
			if($tptfields[233]->Included){
				$html .='<p><'.$tptfields[233]->FieldName.'2>'.$tptfields[233]->NewDisplayName.'</'.$tptfields[233]->FieldName.'2> : <'.$tptfields[233]->FieldName.'3>'.number_format($data6->Dispalcement,2).'</'.$tptfields[233]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[233]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[233]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[233]->FieldName;		
				$data_arr['FieldValue']=number_format($data6->Dispalcement,2);				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
		if($tptfields[234]->Included){
			$html .='<p>'.$tptfields[234]->NewDisplayName.' : '.$data6->Source.'</p>';
			$data_arr['CpCode']=$tptfields[234]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[234]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[234]->FieldName;		
			$data_arr['FieldValue']=$data6->Source;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($data6->Source=='Rightship'){
		if($tptfields[235]->Included){
			$html .='<p><'.$tptfields[235]->FieldName.'2>'.$tptfields[235]->NewDisplayName.'</'.$tptfields[235]->FieldName.'2> : <'.$tptfields[235]->FieldName.'3>'.$data6->Rating.'</'.$tptfields[235]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[235]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[235]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[235]->FieldName;		
			$data_arr['FieldValue']=$data6->Rating;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
		if($tptfields[236]->Included){
			$html .='<p><'.$tptfields[236]->FieldName.'2>'.$tptfields[236]->NewDisplayName.'</'.$tptfields[236]->FieldName.'2> : <'.$tptfields[236]->FieldName.'3>'.date('d-m-Y',strtotime($data6->RatingDate)).'</'.$tptfields[236]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[236]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[236]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[236]->FieldName;		
			$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->RatingDate));				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
		} else if($data6->Source=='Other source'){
			if($tptfields[237]->Included){
				$html .='<p><'.$tptfields[237]->FieldName.'2>'.$tptfields[237]->NewDisplayName.'</'.$tptfields[237]->FieldName.'2> : <'.$tptfields[237]->FieldName.'3>'.$data6->SourceType.'</'.$tptfields[237]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[237]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[237]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[237]->FieldName;		
				$data_arr['FieldValue']=$data6->SourceType;				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			
			if($data6->SourceType=='Third party'){
				if($tptfields[238]->Included){
					$html .='<p><'.$tptfields[238]->FieldName.'2>'.$tptfields[238]->NewDisplayName.'</'.$tptfields[238]->FieldName.'2> : <'.$tptfields[238]->FieldName.'3>'.$data6->VettingSource.'</'.$tptfields[238]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[238]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[238]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[238]->FieldName;		
					$data_arr['FieldValue']=$data6->VettingSource;					
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
		}
		if($tptfields[239]->Included){
			$html .='<p><'.$tptfields[239]->FieldName.'2>'.$tptfields[239]->NewDisplayName.'</'.$tptfields[239]->FieldName.'2> : <'.$tptfields[239]->FieldName.'3>'.$data6->Deficiency.'</'.$tptfields[239]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[239]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[239]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[239]->FieldName;		
			$data_arr['FieldValue']=$data6->Deficiency;				
			$data_arr['GroupNumber']=3;	
			array_push($fix_data_arr,$data_arr);
		}
						
		if($data6->Deficiency == 'Outstanding' ){
			if($tptfields[240]->Included){
				$html .='<p><'.$tptfields[240]->FieldName.'2>'.$tptfields[240]->NewDisplayName.'</'.$tptfields[240]->FieldName.'2> : <'.$tptfields[240]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DeficiencyCompDate)).'</'.$tptfields[240]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[240]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[240]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[240]->FieldName;		
				$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DeficiencyCompDate));					
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			
		}
		if($tptfields[241]->Included){
			$html .='<p><'.$tptfields[241]->FieldName.'2>'.$tptfields[241]->NewDisplayName.'</'.$tptfields[241]->FieldName.'2> : <'.$tptfields[241]->FieldName.'3>'.$data6->DetentionFlag.'</'.$tptfields[241]->FieldName.'3></p>';
			$data_arr['CpCode']=$tptfields[241]->CpCode;				
			$data_arr['FieldLblName']=$tptfields[241]->NewDisplayName;	
			$data_arr['FieldColumnName']=$tptfields[241]->FieldName;		
			$data_arr['FieldValue']=$data6->DetentionFlag;				
			$data_arr['GroupNumber']=3;
			array_push($fix_data_arr,$data_arr);
		}
				
		if($data6->DetentionFlag == 'Yes'){
			if($tptfields[242]->Included){
				$html .='<p><'.$tptfields[242]->FieldName.'2>'.$tptfields[242]->NewDisplayName.'</'.$tptfields[242]->FieldName.'2> : <'.$tptfields[242]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionDate)).'</'.$tptfields[242]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[242]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[242]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[242]->FieldName;		
				$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionDate));				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			if($tptfields[243]->Included){
				$html .='<p><'.$tptfields[243]->FieldName.'2>'.$tptfields[243]->NewDisplayName.'</'.$tptfields[243]->FieldName.'2> : <'.$tptfields[243]->FieldName.'3>'.$data6->DetentionLiftedFlag.'</'.$tptfields[243]->FieldName.'3></p>';
				$data_arr['CpCode']=$tptfields[243]->CpCode;				
				$data_arr['FieldLblName']=$tptfields[243]->NewDisplayName;	
				$data_arr['FieldColumnName']=$tptfields[243]->FieldName;		
				$data_arr['FieldValue']=$data6->DetentionLiftedFlag;				
				$data_arr['GroupNumber']=3;
				array_push($fix_data_arr,$data_arr);
			}
			if($data6->DetentionLiftedFlag == 'Yes' ) {
				if($tptfields[244]->Included){
					$html .='<p><'.$tptfields[244]->FieldName.'2>'.$tptfields[244]->NewDisplayName.'</'.$tptfields[244]->FieldName.'2> : <'.$tptfields[244]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionLiftedDate)).'</'.$tptfields[244]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[244]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[244]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[244]->FieldName;		
					$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionLiftedDate));				
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
			if($data6->DetentionLiftedFlag == 'No' ) {
				if($tptfields[245]->Included){
					$html .='<p><'.$tptfields[245]->FieldName.'2>'.$tptfields[245]->NewDisplayName.'</'.$tptfields[245]->FieldName.'2> : <'.$tptfields[245]->FieldName.'3>'.date('d-m-Y',strtotime($data6->DetentionLiftExpectedDate)).'</'.$tptfields[245]->FieldName.'3></p>';
					$data_arr['CpCode']=$tptfields[245]->CpCode;				
					$data_arr['FieldLblName']=$tptfields[245]->NewDisplayName;	
					$data_arr['FieldColumnName']=$tptfields[245]->FieldName;		
					$data_arr['FieldValue']=date('d-m-Y',strtotime($data6->DetentionLiftExpectedDate));
					$data_arr['GroupNumber']=3;
					array_push($fix_data_arr,$data_arr);
				}
			}
		}
					
		}
		
		if(count($bankDetailsInvitee)>0) {
		$bank_name='';
		$bank_address1='';
		$bank_address2='';
		$bank_address3='';
		$bank_address4='';
		$bank_country='';
		$bank_state='';
		$bank_city='';
		$bank_pincode='';
		$account_name='';
		$account_number='';
		$currencty_of_payment='';
		$correspondent_bank1='';
		$correspondent_bank2='';
		$bank_code='';
		$bank_branch_code='';
		$swift_bic_code='';
		$ifsc_code='';
		$bank_iban='';
		$sort_code='';
		$aba_number='';
		$bank_detail_applies_to='';
		foreach($bankDetailsInvitee as $row) {
			$bank_name .=  $row->BankName.' || ';
			$bank_address1 .=  $row->BankAddress1.' || ';
			$bank_address2 .=  $row->BankAddress2.' || ';
			$bank_address3 .=  $row->BankAddress3.' || ';
			$bank_address4 .=  $row->BankAddress4.' || ';
			$bank_country .=  $row->country.' || ';
			$bank_state .=  $row->state.' || ';
			$bank_city .=  $row->City.' || ';
			$bank_pincode .=  $row->ZipCode.' || ';
			$account_name .=  $row->AccountName.' || ';
			$account_number .=  $row->AccountNumber.' || ';
			$currencty_of_payment .=  $row->currency.' || ';
			$correspondent_bank1 .=  $row->CorrespondentBank1.' || ';
			$correspondent_bank2 .=  $row->CorrespondentBank2.' || ';
			$bank_code .=  $row->BankCode.' || ';
			$bank_branch_code .=  $row->BankBranchCode.' || ';
			$swift_bic_code .=  $row->SwiftCode.' || ';
			$ifsc_code .=  $row->IfscCode.' || ';
			$bank_iban .=  $row->IbanCode.' || ';
			$sort_code .=  $row->SortCode.' || ';
			$aba_number .=  $row->AbaNumber.' || ';
			$apl='';
			$AppliesTo=explode(',',$row->AppliesTo);
			for($i=0;$i<count($AppliesTo);$i++) {
				if($AppliesTo[$i]==1) {
					$apl .='Freight payment,';
				} else if($AppliesTo[$i]==2) {
					$apl .='Miscellaneous payment,';
				} else if($AppliesTo[$i]==3) {
					$apl .='Hire payment,';
				} else if($AppliesTo[$i]==4) {
					$apl .='Freight invoice,';
				} else if($AppliesTo[$i]==5) {
					$apl .='Miscellaneous invoice,';
				} else if($AppliesTo[$i]==6) {
					$apl .='Hire invoice,';
				}
			}
			$apl=trim($apl,',');
			$bank_detail_applies_to .=  $apl.' || ';
		}
		
		$bank_name=rtrim($bank_name,' || ');
		$bank_address1=rtrim($bank_address1,' || ');
		$bank_address2=rtrim($bank_address2,' || ');
		$bank_address3=rtrim($bank_address3,' || ');
		$bank_address4=rtrim($bank_address4,' || ');
		$bank_country=rtrim($bank_country,' || ');
		$bank_state=rtrim($bank_state,' || ');
		$bank_city=rtrim($bank_city,' || ');
		$bank_pincode=rtrim($bank_pincode,' || ');
		$account_name=rtrim($account_name,' || ');
		$account_number=rtrim($account_number,' || ');
		$currencty_of_payment=rtrim($currencty_of_payment,' || ');
		$correspondent_bank1=rtrim($correspondent_bank1,' || ');
		$correspondent_bank2=rtrim($correspondent_bank2,' || ');
		$bank_code=rtrim($bank_code,' || ');
		$bank_branch_code=rtrim($bank_branch_code,' || ');
		$swift_bic_code=rtrim($swift_bic_code,' || ');
		$ifsc_code=rtrim($ifsc_code,' || ');
		$bank_iban=rtrim($bank_iban,' || ');
		$sort_code=rtrim($sort_code,' || ');
		$aba_number=rtrim($aba_number,' || ');
		$bank_detail_applies_to=rtrim($bank_detail_applies_to,' || ');
		
		//$html .='<p contenteditable="false" style="background-color: #efeaead6"><b>Invitee Bank Details</b></p>';	
		
			if($tptfields[247]->Included){
				$html .='<p><'.$tptfields[247]->FieldName.'2>'.$tptfields[247]->NewDisplayName.'</'.$tptfields[247]->FieldName.'2> : <'.$tptfields[247]->FieldName.'3>'.$bank_name.'</'.$tptfields[247]->FieldName.'3></p>';
			}
			
			if($tptfields[248]->Included){
				$html .='<p><'.$tptfields[248]->FieldName.'2>'.$tptfields[248]->NewDisplayName.'</'.$tptfields[248]->FieldName.'2> : <'.$tptfields[248]->FieldName.'3>'.$bank_address1.'</'.$tptfields[248]->FieldName.'3></p>';
			}
			
			if($tptfields[249]->Included){
				$html .='<p><'.$tptfields[249]->FieldName.'2>'.$tptfields[249]->NewDisplayName.'</'.$tptfields[249]->FieldName.'2> : <'.$tptfields[249]->FieldName.'3>'.$bank_address2.'</'.$tptfields[249]->FieldName.'3></p>';
			}
			
			if($tptfields[250]->Included){
				$html .='<p><'.$tptfields[250]->FieldName.'2>'.$tptfields[250]->NewDisplayName.'</'.$tptfields[250]->FieldName.'2> : <'.$tptfields[250]->FieldName.'3>'.$bank_address3.'</'.$tptfields[250]->FieldName.'3></p>';
			}
			
			if($tptfields[251]->Included){
				$html .='<p><'.$tptfields[251]->FieldName.'2>'.$tptfields[251]->NewDisplayName.'</'.$tptfields[251]->FieldName.'2> : <'.$tptfields[251]->FieldName.'3>'.$bank_address4.'</'.$tptfields[251]->FieldName.'3></p>';
			}
			
			if($tptfields[252]->Included){
				$html .='<p><'.$tptfields[252]->FieldName.'2>'.$tptfields[252]->NewDisplayName.'</'.$tptfields[252]->FieldName.'2> : <'.$tptfields[252]->FieldName.'3>'.$bank_country.'</'.$tptfields[252]->FieldName.'3></p>';
			}
			
			if($tptfields[253]->Included){
				$html .='<p><'.$tptfields[253]->FieldName.'2>'.$tptfields[253]->NewDisplayName.'</'.$tptfields[253]->FieldName.'2> : <'.$tptfields[253]->FieldName.'3>'.$bank_state.'</'.$tptfields[253]->FieldName.'3></p>';
			}
			
			if($tptfields[254]->Included){
				$html .='<p><'.$tptfields[254]->FieldName.'2>'.$tptfields[254]->NewDisplayName.'</'.$tptfields[254]->FieldName.'2> : <'.$tptfields[254]->FieldName.'3>'.$bank_city.'</'.$tptfields[254]->FieldName.'3></p>';
			}
			
			if($tptfields[255]->Included){
				$html .='<p><'.$tptfields[255]->FieldName.'2>'.$tptfields[255]->NewDisplayName.'</'.$tptfields[255]->FieldName.'2> : <'.$tptfields[255]->FieldName.'3>'.$bank_pincode.'</'.$tptfields[255]->FieldName.'3></p>';
			}
			
			if($tptfields[256]->Included){
				$html .='<p><'.$tptfields[256]->FieldName.'2>'.$tptfields[256]->NewDisplayName.'</'.$tptfields[256]->FieldName.'2> : <'.$tptfields[256]->FieldName.'3>'.$account_name.'</'.$tptfields[256]->FieldName.'3></p>';
			}
			
			if($tptfields[257]->Included){
				$html .='<p><'.$tptfields[257]->FieldName.'2>'.$tptfields[257]->NewDisplayName.'</'.$tptfields[257]->FieldName.'2> : <'.$tptfields[257]->FieldName.'3>'.$account_number.'</'.$tptfields[257]->FieldName.'3></p>';
			}
			
			if($tptfields[258]->Included){
				$html .='<p><'.$tptfields[258]->FieldName.'2>'.$tptfields[258]->NewDisplayName.'</'.$tptfields[258]->FieldName.'2> : <'.$tptfields[258]->FieldName.'3>'.$currencty_of_payment.'</'.$tptfields[258]->FieldName.'3></p>';
			}
			
			if($tptfields[259]->Included){
				$html .='<p><'.$tptfields[259]->FieldName.'2>'.$tptfields[259]->NewDisplayName.'</'.$tptfields[259]->FieldName.'2> : <'.$tptfields[259]->FieldName.'3>'.$correspondent_bank1.'</'.$tptfields[259]->FieldName.'3></p>';
			}
			
			if($tptfields[260]->Included){
				$html .='<p><'.$tptfields[260]->FieldName.'2>'.$tptfields[260]->NewDisplayName.'</'.$tptfields[260]->FieldName.'2> : <'.$tptfields[260]->FieldName.'3>'.$correspondent_bank2.'</'.$tptfields[260]->FieldName.'3></p>';
			}
			
			if($tptfields[261]->Included){
				$html .='<p><'.$tptfields[261]->FieldName.'2>'.$tptfields[261]->NewDisplayName.'</'.$tptfields[261]->FieldName.'2> : <'.$tptfields[261]->FieldName.'3>'.$bank_code.'</'.$tptfields[261]->FieldName.'3></p>';
			}
			
			if($tptfields[262]->Included){
				$html .='<p><'.$tptfields[262]->FieldName.'2>'.$tptfields[262]->NewDisplayName.'</'.$tptfields[262]->FieldName.'2> : <'.$tptfields[262]->FieldName.'3>'.$bank_branch_code.'</'.$tptfields[262]->FieldName.'3></p>';
			}
			
			if($tptfields[263]->Included){
				$html .='<p><'.$tptfields[263]->FieldName.'2>'.$tptfields[263]->NewDisplayName.'</'.$tptfields[263]->FieldName.'2> : <'.$tptfields[263]->FieldName.'3>'.$swift_bic_code.'</'.$tptfields[263]->FieldName.'3></p>';
			}
			
			if($tptfields[264]->Included){
				$html .='<p><'.$tptfields[264]->FieldName.'2>'.$tptfields[264]->NewDisplayName.'</'.$tptfields[264]->FieldName.'2> : <'.$tptfields[264]->FieldName.'3>'.$ifsc_code.'</'.$tptfields[264]->FieldName.'3></p>';
			}
			
			if($tptfields[265]->Included){
				$html .='<p><'.$tptfields[265]->FieldName.'2>'.$tptfields[265]->NewDisplayName.'</'.$tptfields[265]->FieldName.'2> : <'.$tptfields[265]->FieldName.'3>'.$bank_iban.'</'.$tptfields[265]->FieldName.'3></p>';
			}
			
			if($tptfields[266]->Included){
				$html .='<p><'.$tptfields[266]->FieldName.'2>'.$tptfields[266]->NewDisplayName.'</'.$tptfields[266]->FieldName.'2> : <'.$tptfields[266]->FieldName.'3>'.$sort_code.'</'.$tptfields[266]->FieldName.'3></p>';
			}
			
			if($tptfields[267]->Included){
				$html .='<p><'.$tptfields[267]->FieldName.'2>'.$tptfields[267]->NewDisplayName.'</'.$tptfields[267]->FieldName.'2> : <'.$tptfields[267]->FieldName.'3>'.$aba_number.'</'.$tptfields[267]->FieldName.'3></p>';
			}
			
			if($tptfields[268]->Included){
				$html .='<p><'.$tptfields[268]->FieldName.'2>'.$tptfields[268]->NewDisplayName.'</'.$tptfields[268]->FieldName.'2> : <'.$tptfields[268]->FieldName.'3>'.$bank_detail_applies_to.'</'.$tptfields[268]->FieldName.'3></p>';
			}
		}
	}
	
	if($cpText){
		$html .='<p ><span >'.$cpText->CpText.'</span></p>';
	}
	
	$encode_html=$html;
	$ResponseID=$this->input->post('InviteeID');
	$LineNum=$this->input->post('LineNum');
	$chats=$this->getChatByResponseidByArguments($ResponseID,$LineNum);
	$encode_html .=$chats;
	
	$this->cp_fn_model->createDefaultFixture($encode_html,$data1->OwnerEntityID,$EntityRow->FixtureCompleteProcess,$cpText->Type,$fix_data_arr);
	$this->cp_fn_model->createDefaultDocumentation($data1->OwnerEntityID,$tptfields,$data1,$data2,$data3,$data4,$data5,$data6,$mdlRow);
}

public function getResponseTableHeading()
{
    $RecordOwner=$this->input->post('RecordOwner');
    $AuctionID=$this->input->post('AuctionID');
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    
    $html='<tr>
			<th class="padd_th" id="head1">Select</th>
			<th class="padd_th">Status</th>
			<th class="padd_th">Invitee(s)</th>';
    if($model->InviteeCriteriaStatus==1) {        
        $html .='<th class="padd_th">Priority</th>';
    }        
    $html .='<th class="padd_th">TID</th>
			<th class="padd_th">Vessel</th>';
    if($model->RatingStatus==1) {
        $html .='<th class="padd_th">Risk rating</th>';
    }
    if($model->FreightCriteriaStatus==1) {
        $html .='<th class="padd_th">FRT quote ($/mt)</th>';
    }
    
    if($model->FIDDCriteriaStatus==1) {
        $html .='<th class="padd_th">Frt incl Dem delays ($/mt)</th>';
    }
    if($model->DemurrageCriteriaStatus==1) {
        $html .='<th class="padd_th">Dem</th>';
    }
    $html .='<th class="padd_th">FRT Est. ($/mt)</th>';
    $html .='<th class="padd_th">FRT (index) ($/mt)</th>';
    if($model->PSCDLYCS==1) {
        $html .='<th class="padd_th">PSC Det. last 12 mths</th>';
    }
    if($model->PSCDPCS==1) {
        $html .='<th class="padd_th">PSC Def. pending </th>';
    }
    if($model->PFLPPADCS==1) {
        $html .='<th class="padd_th">Prox. pref. first LP Arr. date</th>';
    }
    if($model->PSCDRPFLPACS==1) {
        $html .='<th class="padd_th">PSC Def. rectify b4 Arr.</th>';
    }
    if($model->IPOLYCS==1) {
        $html .='<th class="padd_th">Inci. port ops. < 12 mths</th>';
    }
    if($model->VLTCS==1) {
        $html .='<th class="padd_th">Voy. last 12 months</th>';
    }
    $html .='<th class="padd_th">Rating score</th>
			<th class="padd_th">View</th>
		    </tr>';
            
    echo $html;
}
    
    
public function getPointTable()
{
    $AuctionID=$this->input->post('AuctionID');
    //$AuctionID='V17-L69-F06';
    $RecordOwner=$this->input->post('RecordOwner');
    //$RecordOwner=9295; 
    $data=$this->cargo_quote_model->getResponseAssessmentNew();
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
    $vesseldata= array();
    $responseid='';
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
        
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    $crgocnt=$this->cargo_quote_model->getCargoCountByAuctionID($AuctionID);
    $CargoCount=count($crgocnt);
        
    $EntityName_arr=array();
    $ResponseID_arr=array();
    $rating1_arr=array();
    $rating2_arr=array();
    $rating3_arr=array();
    $rating4_arr=array();
    $rating5_arr=array();
    $rating6_arr=array();
    $rating7_arr=array();
    $rating8_arr=array();
    $rating9_arr=array();
    $TotalRating_arr=array();
    $html_arr=array();
        
    foreach($crgocnt as $crgcnt) {
        $crgo=$this->cargo_quote_model->getCargoDataByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $quote=$this->cargo_quote_model->getQuoteByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $Demurrage=$this->cargo_quote_model->getDemmurageByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        //print_r($data);die;
        $lppd=date('Y-m-d', strtotime($crgo->LpPreferDate));
        if($lppd=='1970-01-01') {
            $lppd=date('Y-m-d', strtotime($crgo->LpLaycanStartDate));
        }
        
        foreach($vesseldata as $v) {
            $lpsd=date('Y-m-d', strtotime($v->FirstLoadPortDate));
            $lppd1=date_create($lppd);
            $lpsd1=date_create($lpsd);
            $diff=date_diff($lppd1, $lpsd1);
            $ddef[]=$diff->format("%R%a");

        } 
        $ccnn=count($ddef);
        $prang=$ddef[$ccnn-1]-$ddef[0];
        
        foreach($data as $row) {
            $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
            $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
            $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
            $DemCostLP=$frt->Demurrage*$DelayLP;
            $DemCostDP=$frt->Demurrage*$DelayDP;
            $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
            $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
            $FreightInclDemDelays=($TotalFrtInclDemDelays/$crgo->CargoQtyMT);
            $fidd[]=round($FreightInclDemDelays, 2);
        }
        sort($fidd);
        
        $html='';
        $html_head='';
        $pscdef='';
        $proxpref='';
        $pm=0;
        $rating=0;
        
        $c=count($quote);
        $d=count($Demurrage);
        $f=count($fidd);
        //echo $c;die;
        $low=$quote[0]->FreightRate;
        $high=$quote[$c-1]->FreightRate;
        $range=$high-$low;
        
        $fiddlow=$fidd[0];
        $fiddhigh=$fidd[$f-1];
        $fiddrange1=$fiddhigh-$fiddlow;
        $fiddrange=round($fiddrange1, 2);
        
        $dlow=$Demurrage[0]->Demurrage;
        $dhigh=$Demurrage[$d-1]->Demurrage;
        $drange=$dhigh-$dlow;
         $html_head='<tr>
				<th>Invitee(s)</th>
				<th>TID</th>';    
        if($model->FreightCriteriaStatus==1) {
            $html_head .='<th>FRT quote</th>';
        }
        if($model->FIDDCriteriaStatus==1) {
            $html_head .='<th>Frt incl Dem delays</th>';
        }        
        if($model->DemurrageCriteriaStatus==1) {
            $html_head .='<th>Dem</th>';
        }
        if($model->InviteeCriteriaStatus==1) {        
            $html_head .='<th>Priority</th>';
        }
        if($model->PSCDLYCS==1) {
            $html_head .='<th>PSC Det. last 12 mths</th>';
        }
        if($model->PSCDPCS==1) {
            $html_head .='<th>PSC Def. pending </th>';
        }
        if($model->PSCDRPFLPACS==1) {
            $html_head .='<th>PSC Def. rectify b4 Arr.</th>';
        }
        if($model->PFLPPADCS==1) {
            $html_head .='<th>Prox. pref. first LP Arr. date</th>';
        }
        if($model->RatingStatus==1) {
            $html_head .='<th>Risk rating</th>';
        }
        if($model->IPOLYCS==1) {
            $html_head .='<th>Inci. port ops. < 12 mths</th>';
        }
        if($model->VLTCS==1) {
            $html_head .='<th>Voy. last 12 months</th>';
        }
        $html_head .='<th>Total points</th>';
        $html_head .='</tr>';
        
        $difarray=array();
        foreach($data as $r) {
            foreach($vesseldata as $v) {
                if($r->ResponseID==$v->ResponseID) {
                    $d1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    $d2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                    $d=date_diff($d2, $d1);
                    $difarray[]=$d->format("%a");
                }
            }
        }
        rsort($difarray);
        $MaxDiff=$difarray[0];
        
        foreach($data as $row) {
            if($row->ResponseStatus=='Inprogress' || $row->ResponseStatus=='Released') {
                continue;
            }
            
            foreach($vesseldata as $v) {
                if($row->ResponseID==$v->ResponseID) {
                    $html .='<tr>';
                    $html .='<td>'.$row->EntityName.'</td>';
                    $html .='<td>'.$row->ResponseID.'</td>';
                    $EntityName_arr[]=$row->EntityName;
                    $ResponseID_arr[]=$row->ResponseID;
                    $rating=0;
                    $TotalRating=0;
                    //echo $row->EntityID.'---'.$AuctionID;die;
                    if($row->EntityID==$v->DisponentOwnerID) {
                         $priority=$this->cargo_quote_model->getProrityByAuctionID($row->EntityID, $AuctionID);
                         //print_r($priority);die;
                    } else {
                        $priority=$this->cargo_quote_model->getProrityForShipBroker($v->DisponentOwnerID);
                    }
            
            
                    $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
                    $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                    $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                    $DemCostLP=$frt->Demurrage*$DelayLP;
                    $DemCostDP=$frt->Demurrage*$DelayDP;
                    $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                    $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
                    $FreightInclDemDelays=($TotalFrtInclDemDelays/$crgo->CargoQtyMT);
            
                    $DatePer=0;
                    $per=0;
                    if($range==0) {
                        $ratio=0;
                    } else {
                        $ratio=($frt->FreightRate-$low)/$range;
                    }
                    if($fiddrange==0) {
                        $fidratio=0;
                    } else {
                        $fidratio=($FreightInclDemDelays-$fiddlow)/$fiddrange;
                    }
            
                    if($drange==0) {
                        $dratio=0;
                    } else {
                        $dratio=($frt->Demurrage-$dlow)/$drange;
                    }
                    //echo $frt->Demurrage.'->'.$dlow.',';
                    if($model->FreightCriteriaStatus==1) {
                        if($frt->FreightRate>0) {
                                $per=$ratio*100;
                                $perrange1=trim($model->FpercentageRange1, '%');
                                $perrange2=trim($model->FpercentageRange2, '%');
                                $perrange3=trim($model->FpercentageRange3, '%');
                                $perrange4=trim($model->FpercentageRange4, '%');
                                $perrange5=trim($model->FpercentageRange5, '%');
                                $pr1=$model->FpercentageRange1;
                                $pr2=$model->FpercentageRange2;
                                $pr3=$model->FpercentageRange3;
                                $pr4=$model->FpercentageRange4;
                                $pr5=$model->FpercentageRange5;
                            if($per<=$pr1) {
                                $rating=$model->FpercentageValue1;
                            } else  if($per>$pr1 && $per<=$pr2) {
                                $rating=$model->FpercentageValue2;
                            }
                            else  if($per>$pr2 && $per<=$pr3) {
                                 $rating=$model->FpercentageValue3;
                            } else  if($per>$pr3 && $per<=$pr4) {
                                $rating=$model->FpercentageValue4;
                            } else  if($per>$pr4) {
                                $rating=$model->FpercentageValue5;
                            }
                        } 
                        $html .='<td>'.$rating.'</td>';
                        $rating1_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
            
                    if($model->FIDDCriteriaStatus==1) {
                        $fiddper=$fidratio*100;
                        $fiddpr1=$model->FIDDpercentageRange1;
                        $fiddpr2=$model->FIDDpercentageRange2;
                        $fiddpr3=$model->FIDDpercentageRange3;
                        $fiddpr4=$model->FIDDpercentageRange4;
                        $fiddpr5=$model->FIDDpercentageRange5;
                        if($fiddper<=$fiddpr1) {
                                $rating=$rating+$model->FIDDpercentageValue1;
                        } else  if($fiddper>$fiddpr1 && $fiddper<=$fiddpr2) {
                            $rating=$rating+$model->FIDDpercentageValue2;
                        }
                        else  if($fiddper>$fiddpr2 && $fiddper<=$fiddpr3) {
                            $rating=$rating+$model->FIDDpercentageValue3;
                        } else  if($fiddper>$fiddpr3 && $fiddper<=$fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue4;
                        } else  if($fiddper>$fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue5;
                        }
                    
                        $html .='<td>'.$rating.'</td>';
                        $rating2_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
            
                    if($model->DemurrageCriteriaStatus==1) {
                        if($frt->Demurrage>0) {
                                $dper=$dratio*100;
                                $dpr1=$model->DpercentageRange1;
                                $dpr2=$model->DpercentageRange2;
                                $dpr3=$model->DpercentageRange3;
                                $dpr4=$model->DpercentageRange4;
                                $dpr5=$model->DpercentageRange5;
                            if($dper<=$dpr1) {
                                $rating=$rating+$model->DpercentageValue1;
                            } else  if($dper>$dpr1 && $dper<=$dpr2) {
                                $rating=$rating+$model->DpercentageValue2;
                            }
                            else  if($dper>$dpr2 && $dper<=$dpr3) {
                                 $rating=$rating+$model->DpercentageValue3;
                            } else  if($dper>$dpr3 && $dper<=$dpr4) {
                                $rating=$rating+$model->DpercentageValue4;
                            } else  if($dper>$dpr4) {
                                $rating=$rating+$model->DpercentageValue5;
                            }
                        } 
                        $html .='<td>'.$rating.'</td>';
                        $rating3_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
            
                    if($model->InviteeCriteriaStatus==1) {
                        if($priority=='P1') {
                                $rating=$rating+$model->InviteePriorityValue1;
                        } else if($priority=='P2') {
                    
                            $rating=$rating+$model->InviteePriorityValue2;
                    
                        } else if($priority=='P3') {
                            $rating=$rating+$model->InviteePriorityValue3;
                        } else {
                            $rating=$rating+$model->InviteePriorityValue4;
                        }
                
                        $html .='<td>'.$rating.'</td>';
                        $rating4_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }

                    if($model->PSCDLYCS==1) {    
                        if($v->DetentionFlag=='Yes') {
                                $rating=$rating+$model->PSCDLYAPC;
                        } else {
                            $rating=$rating+$model->PSCDLYMP;
                        }
                        $html .='<td>'.$rating.'</td>';
                        $rating5_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
        
                    if($model->PSCDPCS==1) {
                        if($v->Deficiency=='Outstanding') {
                            $rating=$rating+$model->PSCDPAPC;
                        } else {
                            $rating=$rating+$model->PSCDPMPC;
                        }
                        $html .='<td>'.$rating.'</td>';
                        $rating6_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
        
                    $compdate=date('Y-m-d', strtotime($v->DeficiencyCompDate));
                    $lparivaldate=date('Y-m-d', strtotime($v->FirstLoadPortDate));
                    if($model->PSCDRPFLPACS==1) {
                        if($v->Deficiency=='Outstanding' && ($compdate>=$lparivaldate)) {
                            $rating=$rating+$model->PSCDRPFLPAAPC;
                        } else {
                            $rating=$rating+$model->PSCDRPFLPAMPC;
                        }
                        $html .='<td>'.$rating.'</td>';
                        $rating7_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                    }
                    //echo date('Y-m-d',strtotime($row->FirstLoadPortDate));
                    $lpsd1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {    
                        $diff=date_diff($lppd1, $lpsd1);
                        $ddflpd=$diff->format("%R%a");
                        $ppeerr=($ddflpd/$prang)*100;
                    }else{
                        $ppeerr=0;
                    }
            
                    $date1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    $date2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                    $date3=date_create(date('Y-m-d', strtotime($v->DeficiencyCompDate)));
                    //print_r($date3);die;
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {
                        if(date('Y-m-d', strtotime($crgo->LpPreferDate))=='1970-01-01') {
                            $pscdef='-';
                        }else{
                            $diff=date_diff($date2, $date1);
                            $pscdef=$diff->format("%R%a days");
                            $pscdefcal=$diff->format("%a days");
                            $DatePer=($pscdefcal/$MaxDiff)*100; 
                        }
                    }else{
                        $pscdef='-';
                    }
                    if($model->PFLPPADCS==1) {
                        $ppad1=$model->PFLPPADMPC1;
                        $ppad2=$model->PFLPPADMPC2;
                        $ppad3=$model->PFLPPADMPC3;
                        $ppad4=$model->PFLPPADMPC4;
                        $ppad5=$model->PFLPPADMPC5;
                        if($DatePer<=$ppad1) {
                            $rating=$rating+$model->PFLPPADMPV1;
                        } else  if($DatePer>$ppad1 && $DatePer<=$ppad2) {
                            $rating=$rating+$model->PFLPPADMPV2;
                        }
                        else  if($DatePer>$ppad2 && $DatePer<=$ppad3) {
                            $rating=$rating+$model->PFLPPADMPV3;
                        } else  if($DatePer>$ppad3 && $DatePer<=$ppad4) {
                            $rating=$rating+$model->PFLPPADMPV4;
                        } else {
                            $rating=$rating+$model->PFLPPADMPV5;
                        }
                        $html .='<td>'.$rating.'</td>';
                        $rating8_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
                        //echo $DatePer.' '.$ppad1.' '.$ppad2.' '.$ppad3.' '.$ppad4;die;
                    }
            
                    if($model->RatingStatus==1) {
                        if($v->Rating==1) {
                            $rating=$rating+$model->PrcentRangeValue1;
                        } else if($v->Rating==2) {
                            $rating=$rating+$model->PrcentRangeValue2;
                        } else if($v->Rating==3) {
                            $rating=$rating+$model->PrcentRangeValue3;
                        } else if($v->Rating==4) {
                            $rating=$rating+$model->PrcentRangeValue4;
                        } else if($v->Rating==5) {
                            $rating=$rating+$model->PrcentRangeValue5;
                        }
                        $html .='<td>'.$rating.'</td>';
                        $rating9_arr[]=$rating;
                        $TotalRating=$TotalRating+$rating;
                        $rating=0;
            
                    }
                    if($model->IPOLYCS==1) {
                        $html .='<td>NA</td>';
                    }
                    if($model->VLTCS==1) {
                        $html .='<td>NA</td>';
                    }    
                    $html .='<th>'.$TotalRating.'</th>';
                    $TotalRating_arr[]=$TotalRating;
                    $html .='</tr>';
                    $html_arr[]=$html;
                    $html='';
                }    
            }    
        }
    }
        
    $inrhtml='';
    $response_id_arr_uniq=array();
    for($k=0;$k<count($ResponseID_arr);$k++) {
        if (in_array($ResponseID_arr[$k], $response_id_arr_uniq)) {
        } else {
            $response_id_arr_uniq[]=$ResponseID_arr[$k];
        }
    }
    //print_r($response_id_arr_uniq);die;
    for($i=0;$i<count($response_id_arr_uniq);$i++) {
        $rating1_tot=0;
        $rating2_tot=0;
        $rating3_tot=0;
        $rating4_tot=0;
        $rating5_tot=0;
        $rating6_tot=0;
        $rating7_tot=0;
        $rating8_tot=0;
        $rating9_tot=0;
        $TotalRating_tot=0;        
        for($j=0;$j<count($ResponseID_arr);$j++) {
            if($ResponseID_arr[$i]==$ResponseID_arr[$j]) {
                $rating1_tot=$rating1_tot+$rating1_arr[$j];
                $rating2_tot=$rating2_tot+$rating2_arr[$j];
                $rating3_tot=$rating3_tot+$rating3_arr[$j];
                $rating4_tot=$rating4_tot+$rating4_arr[$j];
                $rating5_tot=$rating5_tot+$rating5_arr[$j];
                $rating6_tot=$rating6_tot+$rating6_arr[$j];
                $rating7_tot=$rating7_tot+$rating7_arr[$j];
                $rating8_tot=$rating8_tot+$rating8_arr[$j];
                $rating9_tot=$rating9_tot+$rating9_arr[$j];
                $TotalRating_tot=$TotalRating_tot+$TotalRating_arr[$j];
            }
        }
        
        $inrhtml .='<tr>';
        $inrhtml .='<td>'.$EntityName_arr[$i].'</td>';
        $inrhtml .='<td>'.$ResponseID_arr[$i].'</td>';
        if($model->FreightCriteriaStatus==1) {
            $inrhtml .='<td>'.($rating1_tot/$CargoCount).'</td>';
        }
        if($model->FIDDCriteriaStatus==1) {
            $inrhtml .='<td>'.($rating2_tot/$CargoCount).'</td>';    
        }        
        if($model->DemurrageCriteriaStatus==1) {
            $inrhtml .='<td>'.($rating3_tot/$CargoCount).'</td>';
        }
        if($model->InviteeCriteriaStatus==1) {        
            $inrhtml .='<td>'.($rating4_tot/$CargoCount).'</td>';
        }
        if($model->PSCDLYCS==1) {
            $inrhtml .='<td>'.($rating5_tot/$CargoCount).'</td>';
        }
        if($model->PSCDPCS==1) {
            $inrhtml .='<td>'.($rating6_tot/$CargoCount).'</td>';
        }
        if($model->PSCDRPFLPACS==1) {
            $inrhtml .='<td>'.($rating7_tot/$CargoCount).'</td>';
        }
        if($model->PFLPPADCS==1) {
            $inrhtml .='<td>'.($rating8_tot/$CargoCount).'</td>';
        }
        if($model->RatingStatus==1) {
            $inrhtml .='<td>'.($rating9_tot/$CargoCount).'</td>';
        }
        if($model->IPOLYCS==1) {
            $inrhtml .='<td>NA</td>';
        }
        if($model->VLTCS==1) {
            $inrhtml .='<td>NA</td>';
        }
        $inrhtml .='<th>'.($TotalRating_tot/$CargoCount).'</th>';
        
    }
        
    $nhtml=array();
    for($i=0;$i<count($response_id_arr_uniq);$i++) {
        for($j=0;$j<count($ResponseID_arr);$j++) {
            if($ResponseID_arr[$i]==$ResponseID_arr[$j]) {
                $nhtml[]=$html_arr[$j];
            }
        }
    }
    $daraArray=array('inhtmlLine'=>$nhtml,'CargoCount'=>$CargoCount,'html_head'=>$html_head,'inrhtml'=>$inrhtml);
    echo json_encode($daraArray); 
        
}
    
public function getAcceptionReason()
{
        
    $data=$this->cargo_quote_model->getAcceptionReason();
    if($data) {
        echo json_encode($data);
    }else{
        echo 0;
    }
        
        
}
    
    
    
public function getAcceptationData()
{
    $accdata=$this->cargo_quote_model->getAcceptationData();
    $html='';
    if($accdata) {
        foreach($accdata as $row) {
            $html .='<tr>';
            $html .='<td>'.$row->ResponseID.'</td>';
            $html .='<td>'.substr($row->acceptance_resion, 0, 40).'</td>';
            $html .='<td>'.$row->confirm1.'</td>';
            $html .='<td>'.$row->UserNameTentative.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDateTentative)).'</td>';
            $html .='<td><img src="img/view-icon.png" onclick="viewAcceptanceReason('.$row->ResponseID.')" title="Acceptance reason view" style="width: 25px;"></td>';
            $html .='</tr>';
        }
        echo $html;
    } else { 
        echo 0;
    }
}
    
public function removeTentetive()
{
    $this->cargo_quote_model->removeTentetive();
}
    
public function getAcceptationViewData()
{
    $data=$this->cargo_quote_model->getAcceptationViewData();
    print_r($data);
}
    
public function getCommercialData()
{
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    //$AuctionID='V17-L69-F06';
    $data=$this->cargo_quote_model->getResponseAssessmentNew();
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
    $vesseldata= array();
    $responseid='';
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
    $crgocnt=$this->cargo_quote_model->getCargoCountByAuctionID($AuctionID);
    $CargoCount=count($crgocnt);
    $status_arr=array();
    $EntityNameArr=array();;
    $VesselNameArr=array();;
    $ResponseIDArr=array();
    $Estimate_mt_arr=array();
    $Estimate_Index_mt_arr=array();
    $FreightRate_arr=array();
    $CargoQtyMT_arr=array();
    $DemLP_arr=array();
    $Demurrage_arr=array();
    $DelayLP_arr=array();
    $DelayDP_arr=array();
    $DemCostLP_arr=array();
    $DemCostDP_arr=array();
    $TotalFrtInclDemDelays_arr=array();
    $DemCost_arr=array();
    $FreightInclDemDelays_arr=array();
        
    foreach($crgocnt as $crgcnt) {
        $crgo=$this->cargo_quote_model->getCargoDataByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        
        foreach($data as $row) {
            if($row->TentativeStatus==1) {
                $status='Tentative Acceptance';
            } else {
                if($row->ResponseStatus=='Submitted') {
                      $status=$row->ResponseStatus;
                    
                }else{
                    $status='In progress';
                    continue;
                }
            }
            foreach($vesseldata as $v) {
                if($row->ResponseID==$v->ResponseID) {
                    $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    
                    $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
                    $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                    $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                    $DemCostLP=$frt->Demurrage*$DelayLP;
                    $DemCostDP=$frt->Demurrage*$DelayDP;
                    $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                    $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
                    $FreightInclDemDelays=round(($TotalFrtInclDemDelays/$crgo->CargoQtyMT), 2);
                    
                    $status_arr[]=$status;
                    $EntityNameArr[]=$frt->EntityName;
                    $ResponseIDArr[]=$row->ResponseID;
                    $VesselNameArr[]=$v->VesselName;
                    $Estimate_mt_arr[]=$crgo->Estimate_mt;
                    $Estimate_Index_mt_arr[]=$crgo->Estimate_Index_mt;
                    $FreightRate_arr[]=$frt->FreightRate;
                    $CargoQtyMT_arr[]=$crgo->CargoQtyMT;
                    $DemLP_arr[]=$DemLP;
                    $Demurrage_arr[]=$frt->Demurrage;
                    $DelayLP_arr[]=$DelayLP;
                    $DelayDP_arr[]=$DelayDP;
                    $DemCostLP_arr[]=$DemCostLP;
                    $DemCostDP_arr[]=$DemCostDP;
                    $TotalFrtInclDemDelays_arr[]=$TotalFrtInclDemDelays;
                    $DemCost_arr[]=$DemCost;
                    $FreightInclDemDelays_arr[]=$FreightInclDemDelays;
                     
                    //$inhtml .='["'.$status.'","'.$row->EntityName.'","'.$row->ResponseID.'","'.$v->VesselName.'","'.number_format($crgo->Estimate_mt,4).'","'.number_format($crgo->Estimate_Index_mt,4).'","'.number_format($row->FreightRate,4).'", "'.number_format($crgo->CargoQtyMT).'", "'.number_format($DemLP).'", "'.number_format($row->Demurrage,2).'", "'.number_format($DelayLP,4).'","'.number_format($DelayDP,4).'","'.number_format($DemCostLP,2).'","'.number_format($DemCostDP,2).'","'.number_format($TotalFrtInclDemDelays).'","'.number_format($DemCost,4).'","'.number_format($FreightInclDemDelays,4).'"],';
                }
            }
        }
    }
    $ResponseIDUniq=array();
        
    for($k=0;$k<count($ResponseIDArr);$k++) {
        if (in_array($ResponseIDArr[$k], $ResponseIDUniq)) {
        } else {
            $ResponseIDUniq[]=$ResponseIDArr[$k];
        }
    }
        
    for($i=0;$i<count($ResponseIDUniq);$i++) {
        $Estimate_mt_tot=0;
        $Estimate_Index_mt_tot=0;
        $FreightRate_tot=0;
        $CargoQtyMT_tot=0;
        $DemLP_tot=0;
        $Demurrage_tot=0;
        $DelayLP_tot=0;
        $DelayDP_tot=0;
        $DemCostLP_tot=0;
        $DemCostDP_tot=0;
        $TotalFrtInclDemDelays_tot=0;
        $DemCost_tot=0;
        $FreightInclDemDelays_tot=0;
        for($j=0;$j<count($ResponseIDArr);$j++) {
            if($ResponseIDArr[$i]==$ResponseIDArr[$j]) {
                $Estimate_mt_tot=$Estimate_mt_tot+$Estimate_mt_arr[$j];
                $Estimate_Index_mt_tot=$Estimate_Index_mt_tot+$Estimate_Index_mt_arr[$j];
                $FreightRate_tot=$FreightRate_tot+$FreightRate_arr[$j];
                $CargoQtyMT_tot=$CargoQtyMT_tot+$CargoQtyMT_arr[$j];
                $DemLP_tot=$DemLP_tot+$DemLP_arr[$j];
                $Demurrage_tot=$Demurrage_tot+$Demurrage_arr[$j];
                $DelayLP_tot=$DelayLP_tot+$DelayLP_arr[$j];
                $DelayDP_tot=$DelayDP_tot+$DelayDP_arr[$j];
                $DemCostLP_tot=$DemCostLP_tot+$DemCostLP_arr[$j];
                $DemCostDP_tot=$DemCostDP_tot+$DemCostDP_arr[$j];
                $TotalFrtInclDemDelays_tot=$TotalFrtInclDemDelays_tot+$TotalFrtInclDemDelays_arr[$j];
                $DemCost_tot=$DemCost_tot+$DemCost_arr[$j];
                $FreightInclDemDelays_tot=$FreightInclDemDelays_tot+$FreightInclDemDelays_arr[$j];
            }
        }
        $inhtml .='["'.$status_arr[$i].'","'.$EntityNameArr[$i].'","'.$ResponseIDArr[$i].'","'.$VesselNameArr[$i].'","'.number_format(($Estimate_mt_tot/$CargoCount), 4).'","'.number_format(($Estimate_Index_mt_tot/$CargoCount), 4).'","'.number_format(($FreightRate_tot/$CargoCount), 4).'", "'.number_format(($CargoQtyMT_tot/$CargoCount)).'", "'.number_format(($DemLP_tot/$CargoCount)).'", "'.number_format(($Demurrage_tot/$CargoCount), 2).'", "'.number_format(($DelayLP_tot/$CargoCount), 4).'","'.number_format(($DelayDP_tot/$CargoCount), 4).'","'.number_format(($DemCostLP_tot/$CargoCount), 2).'","'.number_format(($DemCostDP_tot/$CargoCount), 2).'","'.number_format(($TotalFrtInclDemDelays_tot/$CargoCount)).'","'.number_format(($DemCost_tot/$CargoCount), 4).'","'.number_format(($FreightInclDemDelays_tot/$CargoCount), 4).'"],';
    }
        
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getAcceptanceReason()
{
    $data=$this->cargo_quote_model->getAcceptanceReason();
    echo json_encode($data);
}
    
    
function getResponseCargoData()
{
    $records=$this->cargo_quote_model->getResponseCargoData();
        
    $html ='';
    $Line_Num='';
    $ii=1;
    $i=1;
    $j=1;
    $lineNum=array();
    foreach($records as $row) {
        $tbl='';
        if($Line_Num !=$row->LineNum) {
            $j=1;
            $tbl='</table><br><header id="view_header" ><div class="icons" ><a id="plus'.$ii.'" onclick="hideadv(0,'.$ii.');" style="display: none;" ><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$ii.'" onclick="hideadv(1,'.$ii.');" style="display: inline;" ><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$row->AuctionID.'( Cargo '.$ii.')</b></h5></header><table class="table table-striped table-hover table-bordered" id="datatable-ajax'.$ii.'" style="font-size: 14px;" ><thead class="dark"><tr><th class="padd_th">#</th><th class="padd_th">Created Datetime</th><th class="padd_th">Cargo Version</th><th class="padd_th">Cargo</th><th class="padd_th">Quantity (mt)</th><th class="padd_th">LoadPort</th><th class="padd_th">Disport</th><th class="padd_th">LP Laycan date from</th><th class="padd_th">LP Laycan date to</th><th class="padd_th">Add by (User)</th><th class="padd_th">Changes</th><th class="padd_th">Documents</th><th class="padd_th set_action">Action</th></tr></thead>';
            $ii++;
        }
        $html .=$tbl;
            
        $expversion='';
        $version='';
        if($row->ContentChange=='') {
            $view='-';
        }else {
            $view='<a onclick="getChangeData('.$row->ResponseCargoID.')" >View</a>';
        }
            $check=$this->cargo_quote_model->checkResponseCargoDocuments($row->LineNum, $row->AuctionID);
            $version=explode(" ", $row->CargoVersion);
            
            $controws=$this->cargo_quote_model->countResponseDocumentUser($row->LineNum, $row->ResponseID, $version[1], $row->RecordAddBy);
            
        if($check || $controws ) {
            $docs='<a onclick=getCargoDocumentData('.$row->LineNum.',"'.$row->AuctionID.'","'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') > View Docs </a>';
        }else{
            $docs='-';
        }
            
        if($controws) {
            $name='<a onclick=getResponseDocumentData('.$row->LineNum.',"'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') >'.$row->FirstName.' '.$row->LastName.' </a>';
        } else {
                $name=$row->FirstName.' '.$row->LastName;    
        }
            
        if(in_array($row->LineNum, $lineNum)) {
            $i=0;
        } else {
            $i=1;
            array_push($lineNum, $row->LineNum);
        }
            
            $DisportRslt=$this->cargo_quote_model->getResponseDisportsByResponseCargoID($row->ResponseCargoID);
            $Disports='';
        if(count($DisportRslt)> 0) {
            foreach($DisportRslt as $dr){
                $Disports .=$dr->dspPortName.', ';
            }
        } else {
            $Disports=$row->DpPortName;
        }
            $Disports=trim($Disports, ", ");
            
            $action="<a href='javascript: void(0);' onclick=editCargo('".$row->ResponseCargoID."_".$i."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->ResponseCargoID."_".$i."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            
            $html .='<tr>';
            //$html .='<td><input type="checkbox" class="chkNumber" value="'.$row->ResponseCargoID.'_'.$i.'" /> </td>';
            $html .='<td>'.$j.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td>'.$row->CargoVersion.'</td>';
            $html .='<td>'.$row->CargoCode.'</td>';
            $html .='<td>'.number_format($row->CargoQtyMT).'</td>';
            $html .='<td>'.$row->LpPortName.'</td>';
            $html .='<td>'.$Disports.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->LpLaycanStartDate)).'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->LpLaycanEndDate)).'</td>';
            $html .='<td>'.$name.'</td>';
            $html .='<td>'.$view.'</td>';
            $html .='<td>'.$docs.'</td>';
            $html .='<td>'.$action.'</td>';
            $html .='</tr>';
            
            $Line_Num =$row->LineNum;
            $j++;
    }
        
    //$html=trim($html,"</table>");
    if($html !='') {
        $html=substr($html, 8);
        $html .='</table>';
    }
    echo $html;
}
    
    
    
public function getResponseCargoDatails()
{
    $this->load->model('cargo_model', '', true); 
    $ResponseCargoID=$this->input->post('ResponseCargoID');
    $data2=$this->cargo_model->getCharterDetail();
    $data3=$this->cargo_model->getReferenceDetail();
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data2->OwnerEntityID);
    $html ='';
    $header_html='';
    if($entity_detail->AttachedLogo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            
        if($entity_detail->AlignLogo==1) {
            $header_html .='<div id="header_content" ><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span>';
            $header_html .='<span style="font-size: 15px; float: right;"><b>'.$entity_detail->EntityName.'</b></span></div>';
        } else if($entity_detail->AlignLogo==2) {
            $header_html .='<div id="header_content" ><center><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span><br/>';
            $header_html .='<span style="font-size: 15px;"><b>'.$entity_detail->EntityName.'</b></span></center></div>';
        } else if($entity_detail->AlignLogo==3) {
            $header_html .='<div id="header_content" style="height: 45px;" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span>';
            $header_html .='<span style="font-size: 15px; float: right;" ><img src="'.$url.'" style="max-width: 50px;" /></span></div>';
        }
    } else {
        $header_html .='<div id="header_content" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span></div>';
    }
    $contracttype='';
        
    $header_html .='<br/><hr style="background-color:black; height: 2px;"><br/>';
    $html .=$header_html;
    if($data2) {
        if($data2->ContractType==1) {
            $contracttype='Spot';
        }
        if($data2->ContractType==2) {
            $contracttype='Contract';
        }
        $html .='<h4><B>Charter Details</B></h4>';
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Contract type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$contracttype.'</label>
				</div>';
        if($data2->COAReference) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Contract (COA) reference : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data2->COAReference.'</label>
				</div>';
        }
        if($data2->SalesAgreementReference) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Sales agreement reference : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data2->SalesAgreementReference.'</label>
				</div>';
        }
        if($data2->ShipmentReferenceID) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Shipment Reference ID : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data2->ShipmentReferenceID.'</label>
				</div>';
        }
            $html .='<hr style="background-color:black; height: 2px;">';
    }
    if($data3) {
        $html .='<div class="form-group">
				<label class="control-label col-lg-4"><b>Other Reference ID </b> </label>
				<label class="control-label col-lg-8" style="text-align: left;">&nbsp;</label>
				</div>';
                
        foreach($data3 as $row) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">'.$row->SourceName.' :</label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$row->SourceID.'</label>
				</div>';
        }
        $html .='<hr style="background-color:black; height: 2px;">';
    }
            
    $data=$this->cargo_quote_model->get_response_cargo_html_details();
    $StevedoringTermsLp=$this->cargo_model->getStevedoringTermsByID($data->LpStevedoringTerms);
    $bacdata=$this->cargo_quote_model->get_response_bac_details();
    //print_r($data1); die;
    $bachtml='';
    if($data) {
        $temp='';
        $temp2='';
        $temp3='';
        
        foreach($bacdata as $row){
            $TransactionType='';
            $textcontent='';
            if($row->TransactionType=='Commision') {
                $TransactionType='AddComm';
                $textcontent='AddComm';
            }else{
                $TransactionType=$row->TransactionType;
                $textcontent=$row->TransactionType;
            }
            $bachtml .='<div class="form-group">
				<label class="control-label col-lg-4">Transaction type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$TransactionType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Paying Entity Type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$row->PayingEntityType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Receiving Entity Type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$row->ReceivingEntityType.'</label>
				</div>';
            if($row->ReceivingEntityType=='Charterer') {
                $bachtml .='<div class="form-group">
					<label class="control-label col-lg-4">Receiving Entity Name : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$row->ReceivingEntityName.'</label>
					</div>';
            }
                $bachtml .='<div class="form-group">
				<label class="control-label col-lg-4">'.$textcontent.' payable : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$row->PayableAs.'</label>
				</div>';
            if($row->PayableAs=='Percentage') {
                if($row->PercentageOnFreight) {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on freight : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$row->PercentageOnFreight.'</label>
						</div>';
                }
                if($row->PercentageOnDeadFreight) {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on deadfreight : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$row->PercentageOnDeadFreight.'</label>
						</div>';
                    
                }
                if($row->PercentageOnDemmurage) {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on demmurage : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$row->PercentageOnDemmurage.'</label>
						</div>';
                }
                if($row->PercentageOnOverage) {
                    $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' % on overage : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$row->PercentageOnOverage.'</label>
						</div>';
                }
                        
            } else if($row->PayableAs=='LumpSum') {
                $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">Lumpsum amount payable : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.number_format($row->LumpsumPayable).'</label>
						</div>';
            } else if($row->RatePerTonnePayable=='RatePerTonne') {
                $bachtml .='<div class="form-group">
						<label class="control-label col-lg-4">'.$textcontent.' rate/tonne : </label>
						<label class="control-label col-lg-8" style="text-align: left;">'.$row->RatePerTonnePayable.'</label>
						</div>';
                    
            } 
        }
            
        
        if($data->CargoLimitBasis==1) {
            $CargoLimitBasis='Max and Min';
            $temp='<div class="form-group">
				<label class="control-label col-lg-4">Max cargo is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->MaxCargoMT).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Min cargo is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->MinCargoMT).'</label>
				</div>';
        }else if($data->CargoLimitBasis==2) {
            $CargoLimitBasis='% Tolerance limit';
            $temp='<div class="form-group">
				<label class="control-label col-lg-4">Tolerance limit (%) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->ToleranceLimit.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Upper cargo limit is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->UpperLimit).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Lower cargo limit is : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->LowerLimit).'</label>
				</div>';
        }
            
        if($data->LoadingRateUOM==1) {
            $LoadingRateUOM='Per hour';
        }else if($data->LoadingRateUOM==2) {
            $LoadingRateUOM='Per weather working day';
        }else if($data->LoadingRateUOM==3) {
                $LoadingRateUOM='Max time limit';
                $temp2='<div class="form-group">
				<label class="control-label col-lg-4">Max time to load cargo (hrs) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.(int)$data->LpMaxTime.'</label>
				</div>';
        }
        
        if($data->LpLaytimeType==1) {
            $LpLaytimeType='Reversible';
        }else if($data->LpLaytimeType==2) {
            $LpLaytimeType='Non Reversible';
        }else if($data->LpLaytimeType==3) {
            $LpLaytimeType='Average';
        }
            
        if($data->LpCalculationBasedOn==108) {
            $LpCalculationBasedOn='Bill of Loading Quantity';
        }else if($data->LpCalculationBasedOn==109) {
            $LpCalculationBasedOn='Outturn or Discharge Quantity';
        }
            
        if($data->LpPriorUseTerms==102) {
            $LpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
        }else if($data->LpPriorUseTerms==10) {
            $LpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
        }else{
            $LpPriorUseTerms='N/A';
        }
            
        if($data->LpLaytimeBasedOn==1) {
            $LpLaytimeBasedOn='ATS || All Time Saved';
        }else if($data->LpLaytimeBasedOn==2) {
            $LpLaytimeBasedOn='WTS || Working Time Saved';
        }else{
            $LpLaytimeBasedOn='N/A';
        }
        
        if($data->LpCharterType==1) {
            $LpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($data->LpCharterType==2) {
            $LpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
        }else if($data->LpCharterType==3) {
            $LpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
        }else if($data->LpCharterType==4) {
            $LpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
        }
                
            $html .='<br/><h4><B>Cargo and port details</B></h4>
				<div class="form-group">
				<label class="control-label col-lg-4">Version : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->CargoVersion.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Cargo : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->Code.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Cargo quantity to load (in MT) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->CargoQtyMT).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Cargo quantity option basis : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->CargoLoadedBasis.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Cargo quantity limit basis : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$CargoLimitBasis.'</label>
				</div>';
            $html .=$temp;
            $html .='<hr style="background-color:black; height: 2px;"><div class="form-group">
				<label class="control-label col-lg-4">Load Port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->lpPortName.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Load port laycan start date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpLaycanStartDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Load port laycan finish date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpLaycanEndDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Loadport  preferred arrival date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpPreferDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Expected loadport delay : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->ExpectedLpDelayDay.' days '.$data->ExpectedLpDelayHour.' hours</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Loading Terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->ldtCode.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Loading rate (mt) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->LoadingRateMT).'</label>
				</div>';
            $html .='
				<div class="form-group">
				<label class="control-label col-lg-4">Loading rate based on (uom) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LoadingRateUOM.'</label>
				</div>';
            $html .=$temp2;
            $html .='
				<div class="form-group">
				<label class="control-label col-lg-4">Laytime : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LpLaytimeType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Laytime tonnage calc. based on : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LpCalculationBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> Turn (free) time (hours) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->ftCode.' || '.$data->ftDescription.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> Prior use terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LpPriorUseTerms.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Laytime based on : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LpLaytimeBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> Type of charter : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$LpCharterType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> Stevedoring terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;"> Code : '.$StevedoringTermsLp->Code.' || Description : '.$StevedoringTermsLp->Description.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> NOR tender : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->cnrCode.'</label>
				</div>';
            //print_r($ResponseCargoID); die;
            $html .='<hr style="background-color: black;  height: 1px;">';
        if($data->ExceptedPeriodFlg==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Excepted periods for events : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
            $exceptedData=$this->cargo_quote_model->getLpExpectedPeriodByResponseCargoID($ResponseCargoID);
            foreach($exceptedData as $ep){
                $LaytimeCountsOnDemurrageFlg='-';
                $LaytimeCountsFlg='-';
                $TimeCountingFlg='-';
                    
                if($ep->LaytimeCountsOnDemurrageFlg==1) {
                    $LaytimeCountsOnDemurrageFlg='Yes';
                } else if($ep->LaytimeCountsOnDemurrageFlg==2) {
                    $LaytimeCountsOnDemurrageFlg='No';
                } 
                    
                if($ep->LaytimeCountsFlg==1) {
                         $LaytimeCountsFlg='Yes';
                } else if($ep->LaytimeCountsFlg==2) {
                    $LaytimeCountsFlg='No';
                }
                    
                if($ep->TimeCountingFlg==102) {
                    $TimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                } else if($ep->TimeCountingFlg==10) {
                    $TimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                }
                    $html .='<tr>';
                    $html .='<td>'.$ep->ExceptedCode.' || '.$ep->ExceptedDescription.'</td>';
                    $html .='<td>'.$LaytimeCountsOnDemurrageFlg.'</td>';
                    $html .='<td>'.$LaytimeCountsFlg.'</td>';
                    $html .='<td>'.$TimeCountingFlg.'</td>';
                    $html .='</tr>';
            }
            $html .='</table>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Excepted periods for events : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            $html .='<hr style="background-color: black;  height: 1px;">';
        if($data->NORTenderingPreConditionFlg==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
            $NORTenderingData=$this->cargo_quote_model->getLpNORTenderingPreByResponseCargoID($ResponseCargoID);
            foreach($NORTenderingData as $tr){
                    $CreateNewOrSelectListFlg='-';
                    $NewNORTenderingPreCondition='-';
                    
                if($tr->CreateNewOrSelectListFlg==1) {
                    $CreateNewOrSelectListFlg='create new';
                    $NewNORTenderingPreCondition=$tr->NewNORTenderingPreCondition;
                } else if($tr->CreateNewOrSelectListFlg==2) {
                    $CreateNewOrSelectListFlg='select from pre defined list';
                    $NewNORTenderingPreCondition=$tr->TenderingCode;
                }
                    $StatusFlag='No';
                if($tr->StatusFlag) {
                    $StatusFlag='Yes';
                }
                    $html .='<tr>';
                    $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                    $html .='<td>'.$NewNORTenderingPreCondition.'</td>';
                    $html .='<td>'.$StatusFlag.'</td>';
                    $html .='</tr>';
            }
            $html .='</table>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            $html .='<hr style="background-color: black;  height: 1px;">';
        if($data->NORAcceptancePreConditionFlg==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
            $NORAcceptanceData=$this->cargo_quote_model->getLpNORAcceptancePreByResponseCargoID($ResponseCargoID);
            foreach($NORAcceptanceData as $ar){
                    $CreateNewOrSelectListFlg='-';
                    $NewNORAcceptancePreCondition='-';
                if($ar->CreateNewOrSelectListFlg==1) {
                        $CreateNewOrSelectListFlg='create new';
                        $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                } else if($ar->CreateNewOrSelectListFlg==2) {
                    $CreateNewOrSelectListFlg='select from pre defined list';
                    $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                }
                    $StatusFlag='No';
                if($tr->StatusFlag) {
                    $StatusFlag='Yes';
                }
                    $html .='<tr>';
                    $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                    $html .='<td>'.$NewNORAcceptancePreCondition.'</td>';
                    $html .='<td>'.$StatusFlag.'</td>';
                    $html .='</tr>';
            }
            $html .='</table>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            
            $html .='<hr style="background-color: black;  height: 1px;">';
        if($data->OfficeHoursFlg==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Enter Office hours : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
            $OfficeHoursData=$this->cargo_quote_model->getLpOfficeHoursByResponseCargoID($ResponseCargoID);
                
            foreach($OfficeHoursData as $or){
                    $IsLastEntry='No';
                if($or->IsLastEntry==1) {
                     $IsLastEntry='Yes';
                }
                    $html .='<tr>';
                    $html .='<td>'.$or->DateFrom.'</td>';
                    $html .='<td>'.$or->DateTo.'</td>';
                    $html .='<td>'.$or->TimeFrom.'</td>';
                    $html .='<td>'.$or->TimeTo.'</td>';
                    $html .='<td>'.$IsLastEntry.'</td>';
                    $html .='</tr>';
            }
            $html .='</table>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Enter Office hours : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            
            $html .='<hr style="background-color: black;  height: 1px;">';
        if($data->LaytimeCommencementFlg==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Enter laytime commencement : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
            $LaytimeCommencementData=$this->cargo_quote_model->getLpLaytimeCommenceByResponseCargoID($ResponseCargoID);
            foreach($LaytimeCommencementData as $lr){
                    $TurnTimeExpire='-';
                    $LaytimeCommenceAt='-';
                    $LaytimeCommenceAtHour='-';
                    $SelectDay='-';
                    $TimeCountsIfOnDemurrage='-';
                if($lr->TurnTimeExpire==1) {
                    $TurnTimeExpire='During office hours';
                    if($lr->LaytimeCommenceAt==1) {
                            $LaytimeCommenceAt='At expiry of turn time';
                    } else if($lr->LaytimeCommenceAt==2) {
                        $LaytimeCommenceAt='At specified hour';
                        $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                            
                        if($lr->SelectDay==1) {
                            $SelectDay='Same Day';
                        } else if($lr->SelectDay==2) {
                            $SelectDay='New Working Day';
                        }
                        if($lr->TimeCountsIfOnDemurrage==1) {
                            $TimeCountsIfOnDemurrage='Yes';
                        }else if($lr->TimeCountsIfOnDemurrage==2) {
                            $TimeCountsIfOnDemurrage='No';
                        }
                    }
                } else {
                                $TurnTimeExpire='After office hours';
                                $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                        
                    if($lr->SelectDay==1) {
                        $SelectDay='Same Day';
                    } else if($lr->SelectDay==2) {
                        $SelectDay='New Working Day';
                    }
                    if($lr->TimeCountsIfOnDemurrage==1) {
                        $TimeCountsIfOnDemurrage='Yes';
                    }else if($lr->TimeCountsIfOnDemurrage==2) {
                        $TimeCountsIfOnDemurrage='No';
                    }
                }
                    $html .='<tr>';
                    $html .='<td>'.$lr->DayFrom.'</td>';
                    $html .='<td>'.$lr->DayTo.'</td>';
                    $html .='<td>'.$lr->TimeFrom.'</td>';
                    $html .='<td>'.$lr->TimeTo.'</td>';
                    $html .='<td>'.$lr->LaytimeCode.'</td>';
                    $html .='<td>'.$TurnTimeExpire.'</td>';
                    $html .='<td>'.$LaytimeCommenceAt.'</td>';
                    $html .='<td>'.$LaytimeCommenceAtHour.'</td>';
                    $html .='<td>'.$SelectDay.'</td>';
                    $html .='<td>'.$TimeCountsIfOnDemurrage.'</td>';
                    $html .='</tr>';
            }
            $html .='</table>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Enter laytime commencement : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            
            $disportData=$this->cargo_quote_model->getResponseDisportDetailsByResponseCargoID($ResponseCargoID);
            
            $j=1;
        foreach($disportData as $dis){
            $temp3='';
            $html .='<hr style="background-color:black; height: 2px;">';
            $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Disport '.$j.' : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$dis->dpPortName.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Disport arrival start date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalStartDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Disport arrival finish date  : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalEndDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Disport  arrival preferred date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpPreferDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Expected disport delay : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$dis->ExpectedDpDelayDay.' days '.$dis->ExpectedDpDelayHour.' hours</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Discharging Terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$dis->ddtCode.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Discharing rate (mt)  : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($dis->DischargingRateMT).'</label>
				</div>';
            if($dis->DischargingRateUOM==1) {
                    $DischargingRateUOM='Per hour';
            }else if($dis->DischargingRateUOM==2) {
                   $DischargingRateUOM='Per weather working day';
            }else if($dis->DischargingRateUOM==3) {
                $DischargingRateUOM='Max time limit';
                $temp3='<div class="form-group">
					<label class="control-label col-lg-4">Max time to discharge (hrs) : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.(int)$dis->DpMaxTime.'</label>
					</div>';
            }
                      $html .='<div class="form-group">
				<label class="control-label col-lg-4">Discharging rate based on (uom)  : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DischargingRateUOM.'</label>
				</div>';
                      $html .=$temp3;
                            
            if($dis->DpLaytimeType==1) {
                $DpLaytimeType='Reversible';
            }else if($dis->DpLaytimeType==2) {
                $DpLaytimeType='Non Reversible';
            }else if($dis->DpLaytimeType==3) {
                $DpLaytimeType='Average';
            }
                            
            if($dis->DpCalculationBasedOn==108) {
                $DpCalculationBasedOn='Bill of Loading Quantity';
            }else if($dis->DpCalculationBasedOn==109) {
                $DpCalculationBasedOn='Outturn or Discharge Quantity';
            }
                
                      $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Laytime type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DpLaytimeType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Laytime tonnage calc. based on : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DpCalculationBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4"> Turn (free) time (hours) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$dis->dftCode.' || '.$dis->dftDescription.'</label>
				</div>';
                            
            if($dis->DpPriorUseTerms==102) {
                $DpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
            }else if($dis->DpPriorUseTerms==10) {
                $DpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
            }else{
                $DpPriorUseTerms='N/A';
            }
                            
            if($dis->DpLaytimeBasedOn==1) {
                $DpLaytimeBasedOn='ATS || All Time Saved';
            }else if($dis->DpLaytimeBasedOn==2) {
                $DpLaytimeBasedOn='WTS || Working Time Saved';
            }else{
                $DpLaytimeBasedOn='N/A';
            }
                            
            if($dis->DpCharterType==1) {
                $DpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
            }else if($dis->DpCharterType==2) {
                $DpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
            }else if($dis->DpCharterType==3) {
                $DpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
            }else if($dis->DpCharterType==4) {
                $DpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
            }
                
                      $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Prior use terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DpPriorUseTerms.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Laytime based on : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DpLaytimeBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Type of charter : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$DpCharterType.'</label>
				</div>';
                      $StevedoringTermsDp=$this->cargo_model->getStevedoringTermsByID($dis->DpStevedoringTerms);
                      $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Stevedoring terms : </label>
				<label class="control-label col-lg-8" style="text-align: left;"> Code : '.$StevedoringTermsDp->Code.' || Description : '.$StevedoringTermsDp->Description.'</label>
				</div>';
                      $html .='<div class="form-group">
				<label class="control-label col-lg-4">NOR tender : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$dis->cnrDCode.'</label>
				</div>';
                      $html .='<hr style="background-color: black;  height: 1px;">';
            if($dis->DpExceptedPeriodFlg==1) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Excepted periods for events : </label>
					<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
					</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
                $exceptedData=$this->cargo_quote_model->getDpExceptedPeriodByResponseDisportID($dis->RCD_ID);
                foreach($exceptedData as $ep){
                    $LaytimeCountsOnDemurrageFlg='-';
                    $LaytimeCountsFlg='-';
                    $TimeCountingFlg='-';
                        
                    if($ep->LaytimeCountsOnDemurrageFlg==1) {
                                   $LaytimeCountsOnDemurrageFlg='Yes';
                    } else if($ep->LaytimeCountsOnDemurrageFlg==2) {
                            $LaytimeCountsOnDemurrageFlg='No';
                    } 
                        
                    if($ep->LaytimeCountsFlg==1) {
                         $LaytimeCountsFlg='Yes';
                    } else if($ep->LaytimeCountsFlg==2) {
                          $LaytimeCountsFlg='No';
                    }
                        
                    if($ep->TimeCountingFlg==102) {
                           $TimeCountingFlg='IUATUTC || If Used Actual Time To Count';
                    } else if($ep->TimeCountingFlg==10) {
                        $TimeCountingFlg='IUHTUTC || If Used Half Time To Count';
                    }
                    $html .='<tr>';
                    $html .='<td>'.$ep->ExceptedCode.' || '.$ep->ExceptedDescription.'</td>';
                    $html .='<td>'.$LaytimeCountsOnDemurrageFlg.'</td>';
                    $html .='<td>'.$LaytimeCountsFlg.'</td>';
                    $html .='<td>'.$TimeCountingFlg.'</td>';
                    $html .='</tr>';
                }
                          $html .='</table>';
            } else {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Excepted periods for events : </label>
					<label class="control-label col-lg-8" style="text-align: left;">No</label>
					</div>';
            }
                      $html .='<hr style="background-color: black;  height: 1px;">';
            if($dis->DpNORTenderingPreConditionFlg==1) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
					<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
					</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORTenderingData=$this->cargo_quote_model->getDpTenderingPreConditionsByResponseDisportID($dis->RCD_ID);
                foreach($NORTenderingData as $tr){
                    $CreateNewOrSelectListFlg='-';
                    $NewNORTenderingPreCondition='-';
                        
                    if($tr->CreateNewOrSelectListFlg==1) {
                               $CreateNewOrSelectListFlg='create new';
                               $NewNORTenderingPreCondition=$tr->NewNORTenderingPreCondition;
                    } else if($tr->CreateNewOrSelectListFlg==2) {
                        $CreateNewOrSelectListFlg='select from pre defined list';
                        $NewNORTenderingPreCondition=$tr->TenderingCode;
                    }
                    $StatusFlag='In Active';
                    if($tr->StatusFlag==1) {
                             $StatusFlag='Active';
                    }
                    $html .='<tr>';
                    $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                    $html .='<td>'.$NewNORTenderingPreCondition.'</td>';
                    $html .='<td>'.$StatusFlag.'</td>';
                    $html .='</tr>';
                }
                          $html .='</table>';
            } else {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> NOR tendering pre conditions apply : </label>
					<label class="control-label col-lg-8" style="text-align: left;">No</label>
					</div>';
            }
                      $html .='<hr style="background-color: black;  height: 1px;">';
            if($dis->DpNORAcceptancePreConditionFlg==1) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
					<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
					</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORAcceptanceData=$this->cargo_quote_model->getDpAcceptancePreConditionByResponseDisportID($dis->RCD_ID);
                foreach($NORAcceptanceData as $ar){
                    $CreateNewOrSelectListFlg='';
                    $NewNORAcceptancePreCondition='';
                    if($ar->CreateNewOrSelectListFlg==1) {
                                   $CreateNewOrSelectListFlg='create new';
                                   $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                    } else if($ar->CreateNewOrSelectListFlg==2) {
                        $CreateNewOrSelectListFlg='select from pre defined list';
                        $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                    }
                    $StatusFlag='In Active';
                    if($ar->StatusFlag==1) {
                         $StatusFlag='Active';
                    }
                    $html .='<tr>';
                    $html .='<td>'.$CreateNewOrSelectListFlg.'</td>';
                    $html .='<td>'.$NewNORAcceptancePreCondition.'</td>';
                    $html .='<td>'.$StatusFlag.'</td>';
                    $html .='</tr>';
                }
                          $html .='</table>';
            } else {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> NOR acceptance pre condition apply : </label>
					<label class="control-label col-lg-8" style="text-align: left;">No</label>
					</div>';
            }
                
                      $html .='<hr style="background-color: black;  height: 1px;">';
            if($dis->DpOfficeHoursFlg==1) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Enter Office hours : </label>
					<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
					</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
                $OfficeHoursData=$this->cargo_quote_model->getDpOfficeHoursByResponseDisportID($dis->RCD_ID);
                    
                foreach($OfficeHoursData as $or){
                    $IsLastEntry='No';
                    if($or->IsLastEntry==1) {
                            $IsLastEntry='Yes';
                    }
                    $html .='<tr>';
                    $html .='<td>'.$or->DateFrom.'</td>';
                    $html .='<td>'.$or->DateTo.'</td>';
                    $html .='<td>'.$or->TimeFrom.'</td>';
                    $html .='<td>'.$or->TimeTo.'</td>';
                    $html .='<td>'.$IsLastEntry.'</td>';
                    $html .='</tr>';
                }
                          $html .='</table>';
            } else {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Enter Office hours : </label>
					<label class="control-label col-lg-8" style="text-align: left;">No</label>
					</div>';
            }
                
                      $html .='<hr style="background-color: black;  height: 1px;">';
            if($dis->DpLaytimeCommencementFlg==1) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Enter laytime commencement : </label>
					<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
					</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
                $LaytimeCommencementData=$this->cargo_quote_model->getDpLaytimeCommencementByResponseDisportID($dis->RCD_ID);
                foreach($LaytimeCommencementData as $lr){
                    $TurnTimeExpire='-';
                    $LaytimeCommenceAt='-';
                    $LaytimeCommenceAtHour='-';
                    $SelectDay='-';
                    $TimeCountsIfOnDemurrage='-';
                    if($lr->TurnTimeExpire==1) {
                            $TurnTimeExpire='During office hours';
                        if($lr->LaytimeCommenceAt==1) {
                            $LaytimeCommenceAt='At expiry of turn time';
                        } else if($lr->LaytimeCommenceAt==2) {
                            $LaytimeCommenceAt='At specified hour';
                            $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                                
                            if($lr->SelectDay==1) {
                                 $SelectDay='Same Day';
                            } else if($lr->SelectDay==2) {
                                     $SelectDay='New Working Day';
                            }
                            if($lr->TimeCountsIfOnDemurrage==1) {
                                $TimeCountsIfOnDemurrage='Yes';
                            }else if($lr->TimeCountsIfOnDemurrage==2) {
                                $TimeCountsIfOnDemurrage='No';
                            }
                        }
                    } else {
                        $TurnTimeExpire='After office hours';
                        $LaytimeCommenceAtHour=$lr->LaytimeCommenceAtHour;
                            
                        if($lr->SelectDay==1) {
                               $SelectDay='Same Day';
                        } else if($lr->SelectDay==2) {
                                                                              $SelectDay='New Working Day';
                        }
                        if($lr->TimeCountsIfOnDemurrage==1) {
                            $TimeCountsIfOnDemurrage='Yes';
                        }else if($lr->TimeCountsIfOnDemurrage==2) {
                            $TimeCountsIfOnDemurrage='No';
                        }
                    }
                    $html .='<tr>';
                    $html .='<td>'.$lr->DayFrom.'</td>';
                    $html .='<td>'.$lr->DayTo.'</td>';
                    $html .='<td>'.$lr->TimeFrom.'</td>';
                    $html .='<td>'.$lr->TimeTo.'</td>';
                    $html .='<td>'.$lr->LaytimeCode.'</td>';
                    $html .='<td>'.$TurnTimeExpire.'</td>';
                    $html .='<td>'.$LaytimeCommenceAt.'</td>';
                    $html .='<td>'.$LaytimeCommenceAtHour.'</td>';
                    $html .='<td>'.$SelectDay.'</td>';
                    $html .='<td>'.$TimeCountsIfOnDemurrage.'</td>';
                    $html .='</tr>';
                }
                          $html .='</table>';
            } else {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4"> Enter laytime commencement : </label>
					<label class="control-label col-lg-8" style="text-align: left;">No</label>
					</div>';
            }
                
                      $j++;
        }
            
            $html .='<hr style="background-color:black; height: 2px;">';
            
        if($data->BACFlag==1) {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Brokerage / Add Comm : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Yes</label>
				</div>';
            $html .=$bachtml;
            $html .='<br/>';
        } else {
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Brokerage / Add Comm : </label>
				<label class="control-label col-lg-8" style="text-align: left;">No</label>
				</div>';
        }
            
            $html .='<br/><hr style="background-color:black; height: 2px;">';
    }
    echo $html;
}
    
public function getResponseCargoLatestVersion()
{
    $data=$this->cargo_quote_model->getResponseCargoLatestVersion();
    echo json_encode($data);
}
    
public function AddNewResponseVersion()
{
    $olddata=$this->cargo_quote_model->getResponseCargoLatestOpen();
    $flag=$this->cargo_quote_model->AddNewResponseVersion();
    if($flag) {
        $this->cargo_quote_model->sendInprogressMessage();
        $newdata=$this->cargo_quote_model->getResponseCargoLatestOpen();
        $ret=$this->cargo_quote_model->updateResponseContentChange($olddata, $newdata);
        $flg=$this->cargo_quote_model->uploadResponseImage();
    }
    if($flag) {
        echo 1;
    } else {
        echo 0;
    }
}
    
public function getCargoChangeContent()
{
    $data=$this->cargo_quote_model->get_response_cargo_html_details();
    echo json_encode($data);
}
    
public function getResponseDocument()
{
    $data=$this->cargo_quote_model->getResponseDocument();
    echo json_encode($data);
}
    
public function deleteResponseDocument()
{
    $flag=$this->cargo_quote_model->deleteResponseDocument();
    if($flag) {
        echo 1;
    }else{
        echo 0;
    }
}
    
public function getResponseDocumentUser()
{
    $data=$this->cargo_quote_model->getResponseDocumentUser();
    echo json_encode($data);
    
}
    
public function view_response_document()
{
    $filename=$this->cargo_quote_model->view_response_document();
    //echo $filename;
    
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
    
public function download_response_document()
{ 
    $this->load->helper('download');
    $filename=$this->cargo_quote_model->view_response_document();
    //$filename='123.docx';
    //echo $filename;die;
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
    
public function getResponseVesselData()
{
    $records=$this->cargo_quote_model->getResponseVesselData();
        
    $html ='';
    $i=1;
    foreach($records as $row) {
        $expversion='';
        $version='';
        if($row->ContentChange=='') {
            $view='-';
        }else {
            $view='<a onclick="getChangeData('.$row->ResponseVesselID.')" >View</a>';
        }
        $check=$this->cargo_quote_model->checkResponseVesselDocuments($row->AuctionID);
        $version=explode(" ", $row->VesselVersion);
            
        $controws=$this->cargo_quote_model->countResponseDocumentVessel($row->ResponseID, $version[1], $row->RecordAddBy);
        if($check || $controws) {
            $docs='<a onclick=getVesselDocumentData("'.$row->AuctionID.'","'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') > View Docs </a>';
        }else{
            $docs='-';
        }
            
        if($controws) {
            $name='<a onclick=getResponseDocumentData("'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') >'.$row->FirstName.' '.$row->LastName.' </a>';
        }else{
                $name=$row->FirstName.' '.$row->LastName;    
        }
            
        if($i==1) {
            $action="<a href='javascript: void(0);' onclick=editVessel('".$row->ResponseVesselID."','1') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->ResponseVesselID."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
        } else {
            $action="<a href='javascript: void(0);' onclick=editVessel('".$row->ResponseVesselID."','0') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->ResponseVesselID."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";            
        }
            
            $html .='<tr>';
            //$html .='<td><input type="checkbox" class="chkNumber" value="'.$row->ResponseVesselID.'" /> </td>';
            $html .='<td>'.$i.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td>'.$row->VesselVersion.'</td>';
            $html .='<td>'.$row->AuctionID.'</td>';
            $html .='<td>'.$row->ResponseID.'</td>';
            $html .='<td>'.$name.'</td>';
            $html .='<td>'.$view.'</td>';
            $html .='<td>'.$docs.'</td>';
            $html .='<td>'.$action.'</td>';
            $html .='</tr>';
            $i++;
    }
        
    echo $html;
    
}
    
public function getResponseFreightData()
{
    $ResponseID=$this->input->post('ResponseID');
    $bp_flg=$this->input->post('bp_flg');
    $records=$this->cargo_quote_model->getResponseFreightData();
    $html ='';
    $i=1;
    $ii=1;
    $Line_Num='';
    $edit_flg=1;
    foreach($records as $row) {
        $tbl='';
        if($Line_Num !=$row->LineNum) {
            $i=1;
            $tbl='</table><br><header id="view_header" ><div class="icons" ><a id="plus'.$ii.'" onclick="hideadv(0,'.$ii.');" style="display: none;" ><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$ii.'" onclick="hideadv(1,'.$ii.');" style="display: inline;" ><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$row->AuctionID.'( Freight Quote '.$ii.')</b></h5></header><table class="table table-striped table-hover table-bordered" id="datatable-ajax'.$ii.'" style="font-size: 14px;" ><thead class="dark"><tr><th class="padd_th setwidth">#</th><th class="padd_th">Created Datetime</th><th class="padd_th">Quote Version</th><th class="padd_th">MasterID (MID)</th><th class="padd_th">ResponseID (TID)</th><th class="padd_th">Doc add by (User)</th><th class="padd_th">Changes</th><th>Documents</th><th class="padd_th set_action">Action</th></thead></tr>';
            $ii++;
                
            $edit_flg=$this->cargo_quote_model->getFreightQuoteBusinessAuthorisationEqual($ResponseID, $row->LineNum);
                
        }
        $html .=$tbl;
        $expversion='';
        $version='';
        if($row->ContentChange=='') {
            $view='-';
        }else {
            $view='<a href="javascript: void(0);" onclick="getChangeData('.$row->FreightResponseID.')" >View</a>';
        }
            $check=$this->cargo_quote_model->checkResponseFreightDocuments($row->AuctionID);
            $version=explode(" ", $row->FreightVersion);
            
            $controws=$this->cargo_quote_model->countResponseDocumentFreight($row->ResponseID, $version[1], $row->RecordAddBy);
        if($check || $controws) {
            $docs='<a onclick=getFreightDocumentData("'.$row->LineNum.'","'.$row->AuctionID.'","'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') > View Docs </a>';
        }else{
            $docs='-';
        }
            
        if($controws) {
                $name='<a onclick=getResponseDocumentData("'.$row->LineNum.'","'.$row->ResponseID.'","'.$version[1].'",'.$row->RecordAddBy.') >'.$row->FirstName.' '.$row->LastName.' </a>';
        } else {
                $name=$row->FirstName.' '.$row->LastName;    
        }
            
            $action="<a href='javascript: void(0);' onclick=editFreight('".$row->FreightResponseID."_".$edit_flg."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->FreightResponseID."_".$edit_flg."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            
            $html .='<tr>';
            //$html .='<td><input type="checkbox" class="chkNumber" value="'.$row->FreightResponseID.'_'.$edit_flg.'" /> </td>';
            $html .='<td>'.$i.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td>'.$row->FreightVersion.'</td>';
            $html .='<td>'.$row->AuctionID.'</td>';
            $html .='<td>'.$row->ResponseID.'</td>';
            $html .='<td>'.$name.'</td>';
            $html .='<td>'.$view.'</td>';
            $html .='<td>'.$docs.'</td>';
            $html .='<td>'.$action.'</td>';
            $html .='</tr>';
            $i++;
            $Line_Num =$row->LineNum;
    }
    if($html !='') {
        $html=substr($html, 8);
        $html .='</table>';
    }
    echo $html;
    
}
    
public function getResponseInviteeCommentTable()
{
    $EntityID=$this->input->post('EntityID');
    $records=$this->cargo_quote_model->getFreightQuoteRecords();
    //print_r($records); die;
    $html ='';
    $i=1;
    foreach($records as $row) {
        $chats=$this->cargo_quote_model->getChateByResponseID($row->ResponseID, $row->LineNum);
        $vchats=$this->cargo_quote_model->getVesselChatsByResponseID($row->ResponseID, $row->LineNum);
        $fchats=$this->cargo_quote_model->getFreightChatsByResponseID($row->ResponseID, $row->LineNum);
        $cchats=$this->cargo_quote_model->getCargoChatsByResponseID($row->ResponseID, $row->LineNum);
        $tchats=$this->cargo_quote_model->getTermChatsByResponseID($row->ResponseID, $row->LineNum);
        $chatFlagRow=$this->cargo_quote_model->getNewChatsFlgByResponseID($row->ResponseID, $row->LineNum);
        $cargoRecord=$this->cargo_quote_model->getResponseCargoRecord($row->AuctionID, $row->LineNum);
        $chat_count=count($chats);
        //print_r($vnew); die;
        $html .='<tr>';
        $html .='<td>'.$i.'</td>';
        //$html .='<td><input type="checkbox" class="chkNumber" value="'.$row->ResponseID.'_'.$row->AuctionID.'_'.$row->LineNum.'" /> </td>';
            
        $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
        $html .='<td>'.$row->AuctionID.'</td>';
        $html .='<td>'.$row->ResponseID.'</td>';
        $html .='<td>'.$cargoRecord->Cargo_Code.'</td>';
        $html .='<td>'.$row->OwnerName.'</td>';
        $html .='<td>'.$row->InviteeName.'</td>';
        if(count($vchats)>0) {
            if($EntityID==$row->RecordOwner && $chatFlagRow->VesselOwnerFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',1,1)">View &nbsp;<span class="badge" style="background-color: orangered; " id="VesselMsg'.$row->LineNum.'">N</span></a></td>';
            } else if($EntityID==$row->EntityID  && $chatFlagRow->VesselInviteeFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',1,2)">View &nbsp;<span class="badge" style="background-color: orangered; " id="VesselMsg'.$row->LineNum.'">N</span></a></td>';
            } else {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',1,0)">View</a></td>';
            }
        } else {
            $html .='<td>-</td>';
        }
        if(count($fchats)>0) {
            if($EntityID==$row->RecordOwner && $chatFlagRow->FreightOwnerFlag==1) {
                  $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',2,1)">View &nbsp;<span class="badge" style="background-color: orangered; " id="FreightMsg'.$row->LineNum.'">N</span></a></td>';
            } else if($EntityID==$row->EntityID  && $chatFlagRow->FreightInviteeFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',2,2)">View &nbsp;<span class="badge" style="background-color: orangered; " id="FreightMsg'.$row->LineNum.'">N</span></a></td>';
            } else {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',2,0)">View</a></td>';
            }
        } else {
            $html .='<td>-</td>';
        }
        if(count($cchats)>0) {
            if($EntityID==$row->RecordOwner && $chatFlagRow->CargoPortOwnerFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',3,1)">View &nbsp;<span class="badge" style="background-color: orangered; " id="CargoPortMsg'.$row->LineNum.'">N</span></a></td>';
            } else if($EntityID==$row->EntityID  && $chatFlagRow->CargoPortInviteeFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',3,2)">View &nbsp;<span class="badge" style="background-color: orangered; " id="CargoPortMsg'.$row->LineNum.'">N</span></a></td>';
            } else {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',3,0)">View</a></td>';
            }
        } else {
            $html .='<td>-</td>';
        }
        if(count($tchats)>0) {
            if($EntityID==$row->RecordOwner && $chatFlagRow->TermOwnerFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',4,1)">View &nbsp;<span class="badge" style="background-color: orangered; " id="TermsMsg'.$row->LineNum.'">N</span></a></td>';
            } else if($EntityID==$row->EntityID  && $chatFlagRow->TermInviteeFlag==1) {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',4,2)">View &nbsp;<span class="badge" style="background-color: orangered; " id="TermsMsg'.$row->LineNum.'">N</span></a></td>';
            } else {
                $html .='<td><a href="javascript:void(0)" onclick="getSectionCommentsByResponseID('.$row->ResponseID.','.$row->LineNum.',4,0)">View </a></td>';
            }
                
        } else {
                $html .='<td>-</td>';
        }
        if($chat_count>0) {
            $html .='<td><a href="javascript:void(0)" onclick="getChateByResponseID('.$row->ResponseID.','.$row->LineNum.',0)">View</a></td>';
        } else {
            $html .='<td>-</td>';
        }
            
            $action="<a href='javascript: void(0);' onclick=editCargo('".$row->ResponseID.'_'.$row->AuctionID.'_'.$row->LineNum."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            
            $html .='<td>'.$action.'</td>';
            $html .='</tr>';
            $i++;
            
    }
        
    echo $html;
    
}
    
public function checkResponseFreightMultiple()
{
    //echo 1; die;
    $records=$this->cargo_quote_model->getResponseFreightFirstVersion();
    $cnt=count($records);
        
    if($cnt > 1) {
        echo 1;    
    } else {
        echo 0;
    }
    
}
    
public function getResponseVesselDocument()
{
        
    $data['details']=$this->cargo_quote_model->getResponseVesselDocument();
    $this->output->set_output(json_encode($data)); 
}
    
    
public function getResponseVesselDatails()
{
    $this->load->model('cargo_model', '', true); 
    $data=$this->cargo_quote_model->getResponseVesselDatails();
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data->RecordOwner);
    $html='';
    $header_html='';
    if($entity_detail->AttachedLogo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            
        if($entity_detail->AlignLogo==1) {
            $header_html .='<div id="header_content" ><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span>';
            $header_html .='<span style="font-size: 15px; float: right;"><b>'.$entity_detail->EntityName.'</b></span></div>';
        } else if($entity_detail->AlignLogo==2) {
            $header_html .='<div id="header_content" ><center><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span><br/>';
            $header_html .='<span style="font-size: 15px;"><b>'.$entity_detail->EntityName.'</b></span></center></div>';
        } else if($entity_detail->AlignLogo==3) {
            $header_html .='<div id="header_content" style="height: 45px;" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span>';
            $header_html .='<span style="font-size: 15px; float: right;" ><img src="'.$url.'" style="max-width: 50px;" /></span></div>';
        }
    } else {
        $header_html .='<div id="header_content" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span></div>';
    }
    $header_html .='<br/><hr style="background-color: black; height: 2px;" ><br/>';    
    if($data) {
        $html .=$header_html;
        if($data->VesselName) {
            $html .='<h4><B>Performing Vessel</B></h4>
			<div class="form-group">
			<label class="control-label col-lg-4">Version : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->VesselVersion.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">MasterID : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->AuctionID.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">ResponseID : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->ResponseID.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Vessel Name : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->VesselName.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">IMO : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->IMO.'</label>
			</div>';
            if($data->VesselCurrentName) {
                $html .='<div class="form-group">
					<label class="control-label col-lg-4">Vessel Current Name : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$data->VesselCurrentName.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-4">Vessel Current Name Date : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->VesselChangeNameDate)).'</label>
					</div>';
            }
            $html .='<div class="form-group">
			<label class="control-label col-lg-4">Expected first loadport arrival date : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->FirstLoadPortDate)).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Expected first disport arrival date : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->LastDisPortDate)).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Length overall (LOA) (m) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->LOA.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Beam (m) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->Beam).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Draft (m) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->Draft).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Deadweight (mt) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->DeadWeight).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Lightweight displacement (mt) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data->Displacement).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Vetting risk source : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->Source.'</label>
			</div>';
            if($data->Source=='Rightship') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Vetting risk rating : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->Rating.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-4">Vetting rating date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->RatingDate)).'</label>
				</div>';
            }
            
            if($data->Source=='Other source') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Source type : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->SourceType.'</label>
				</div>';
                if($data->SourceType=='Third party') {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-4">Source of vetting : </label>
					<label class="control-label col-lg-8" style="text-align: left;">'.$data->VettingSource.'</label>
					</div>';
                }
            }
            $html .='<div class="form-group">
			<label class="control-label col-lg-4">PSC deficiency existing : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->Deficiency.'</label>
			</div>';
            if($data->Deficiency=='Outstanding') {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4">PSC deficiency completion date : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->DeficiencyCompDate)).'</label>
			</div>';
            }
            
            $html .='<div class="form-group">
			<label class="control-label col-lg-4">PSC detention (last 12 months) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data->DetentionFlag.'</label>
			</div>';
            if($data->DetentionFlag=='Yes') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">PSC detention date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionDate)).'</label>
				</div>';
                
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">PSC detention lifted : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.$data->DetentionLiftedFlag.'</label>
				</div>';
                if($data->DetentionFlag=='Yes') {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-4">PSC detention lifted date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionLiftedDate)).'</label>
				</div>';
                }
                if($data->DetentionFlag=='No') {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-4">Expected PSC detention lifted date : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.date('d-m-Y', strtotime($data->DetentionLiftExpectedDate)).'</label>
				</div>';
                }
            }
        }    
        $html .='<br/>';
    }
    echo $html;
}
    
public function getVesselChangeContent()
{
    $data=$this->cargo_quote_model->getResponseVesselDatails();
    echo json_encode($data);
}
    
    
public function htmlDownloadResponseVessel()
{
        
    include_once APPPATH.'third_party/mpdf.php';
        
    $data['data']=$this->cargo_quote_model->getResponseVesselDatails();
        
    $html=$this->load->view('setup/pdfdownloadresponsevessel', $data, true);
    //$html='test';
    //echo $html;die;
    $pdfFilePath = $data['data']->EntityName."(".$data['data']->ResponseID.").pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
}
    
public function getVesselDocuments()
{
    $data=$this->cargo_quote_model->getVesselDocuments();
    echo json_encode($data);
}
    
public function getVesselResponseDocumentUser()
{
    $data=$this->cargo_quote_model->getVesselResponseDocumentUser();
    echo json_encode($data);
}
    
public function addNewFreightResponse()
{
    $applicable=$this->input->post('applicable');
        
    if($applicable==1) {
        $AuctionID=$this->input->post('AuctionID');
        $ResponseID=$this->input->post('ResponseID');
        
        $records=$this->cargo_quote_model->getResponseFreightRecords($AuctionID, $ResponseID);
            
        foreach($records as $r){
            $olddata=$this->cargo_quote_model->getResponseFreightLatestData($r->LineNum);
            $data['FreightResponseID']=$this->cargo_quote_model->addNewFreightResponse($r->LineNum);
            if($data['FreightResponseID']) {
                $newdata=$this->cargo_quote_model->getResponseFreightLatestData($r->LineNum);
                    
                $this->cargo_quote_model->sendInprogressMessage();
                    
                $this->cargo_quote_model->getResponseFreightChangeContent($olddata, $newdata);
                $flg=$this->cargo_quote_model->uploadResponseQuoteImage($r->LineNum);
            }
        }
            
    } else {
        $LineNum=$this->input->post('LineNum');
        $olddata=$this->cargo_quote_model->getResponseFreightLatestData($LineNum);
        $data['FreightResponseID']=$this->cargo_quote_model->addNewFreightResponse($LineNum);
        if($data['FreightResponseID']) {
            $newdata=$this->cargo_quote_model->getResponseFreightLatestData($LineNum);
                
            $this->cargo_quote_model->sendInprogressMessage();
                
            $this->cargo_quote_model->getResponseFreightChangeContent($olddata, $newdata);
            $flg=$this->cargo_quote_model->uploadResponseQuoteImage($LineNum);
        }
    }
        
    echo json_encode($data);
}
    
public function getResponseQuoteDocument()
{
    $data=$this->cargo_quote_model->getResponseQuoteDocument();
    echo json_encode($data);
}
    
    
public function getResponseQuoteDetails()
{
    $this->load->model('cargo_model', '', true);
    $data1=$this->cargo_quote_model->getResponseFreightDatails();
    $data2=$this->cargo_quote_model->getResponseQuoteDatails($data1->LineNum, $data1->ResponseID, $data1->FreightVersion, $data1->AuctionID);
    $data3=$this->cargo_quote_model->getDifferentialReferenceResponse($data2->DifferentialID);
        
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data1->RecordOwner);
    $html='';
    $header_html='';
    if($entity_detail->AttachedLogo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            
        if($entity_detail->AlignLogo==1) {
            $header_html .='<div id="header_content" ><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span>';
            $header_html .='<span style="font-size: 15px; float: right;"><b>'.$entity_detail->EntityName.'</b></span></div>';
        } else if($entity_detail->AlignLogo==2) {
            $header_html .='<div id="header_content" ><center><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span><br/>';
            $header_html .='<span style="font-size: 15px;"><b>'.$entity_detail->EntityName.'</b></span></center></div>';
        } else if($entity_detail->AlignLogo==3) {
            $header_html .='<div id="header_content" style="height: 45px;" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span>';
            $header_html .='<span style="font-size: 15px; float: right;" ><img src="'.$url.'" style="max-width: 50px;" /></span></div>';
        }
    } else {
        $header_html .='<div id="header_content" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span></div>';
    }
    $header_html .='<br/><hr style="background-color: black; height: 2px;" ><br/>';    
        
        
    if($data1) {
        $html .=$header_html;
        if($data1->FreightBasis) {
            $html .='<h4><B>Freight Quote</B></h4>
			<div class="form-group">
			<label class="control-label col-lg-4">Version : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->FreightVersion.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">MasterID : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->AuctionID.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">ResponseID : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->ResponseID.'</label>
			</div>';
            if($data1->FreightBasis==1) {
                $FreightBasis='Rate $/mt';
            }else if($data1->FreightBasis==2) {
                $FreightBasis='Lumpsum';
            }else if($data1->FreightBasis==3) {
                $FreightBasis='High - Low ($/mt)';
            }
            $html .='<div class="form-group">
			<label class="control-label col-lg-4">Freight basis : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$FreightBasis.'</label>
			</div>';
            if($data1->FreightRate) {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4"> Freight rate : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->FreightRate.'</label>
			</div>';
            }
            if((int)$data1->FreightLumpsumMax) {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4"> Freight (lumpsum - max) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->FreightLumpsumMax.'</label>
			</div>';
            }
            if((int)$data1->FreightLow) {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4"> Freight rate from (Low) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->FreightLow.'</label>
			</div>';
            }
            if((int)$data1->FreightHigh) {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4"> Freight rate to (High) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->FreightHigh.'</label>
			</div>';
            }    
            if($data1->Code) {
                $html .='<div class="form-group">
			<label class="control-label col-lg-4">Freight currrency : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$data1->Code.'</label>
			</div>';
            }
            if($data1->FreightRateUOM==1) {
                $FreightRateUOM='UnitCode : MT || Description : Metric Tonnes';
            }else if($data1->FreightRateUOM==2) {
                $FreightRateUOM='UnitCode : LT || Description : Long Tonnes';
            }else if($data1->FreightRateUOM==3) {
                $FreightRateUOM='UnitCode : PMT || Description : Per metric tonne';
            }else if($data1->FreightRateUOM==4) {
                $FreightRateUOM='UnitCode : PLT || Description : Per long ton';
            }else if($data1->FreightRateUOM==5) {
                $FreightRateUOM='UnitCode : WWD || Description : Weather Working Day';
            }
            $html .='<div class="form-group">
			<label class="control-label col-lg-4">Freight rate (UOM) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$FreightRateUOM.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4"> TCE (usd/day) for quoted freight : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data1->FreightTce).'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">TCE (usd/day) for freight differential : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data1->FreightTceDifferential).'</label>
			</div>';
            
            //------------------------differential---------------------------
            $html .='<hr style="height: 1px; background-color: black;"><br/><h4><B> Differential </B></h4>';
            if($data2->VesselGroupSizeID) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Vessel size : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data2->VesselSize).' || '.$data2->SizeGroup.'</label>
				</div>';
            }
            
            if($data2->BaseLoadPort) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Base (load) port  : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->BsDescription.' || Code: '.$data2->BsCode.'</label>
				</div>';
            }
            
            
            
            if($data2->FreightReferenceFlg) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4"> Disport(s) for freight reference  : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Fright reference port '.$data2->FreightReferenceFlg.'</label>
				</div>';
            }
            
            if($data2->FreightReferenceFlg==1) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 1 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp1Description.' || Code : '.$data2->Rp1Code.'</label>
				</div>';
            }
            
            if($data2->FreightReferenceFlg==2) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 1 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp1Description.' || Code : '.$data2->Rp1Code.'</label>
				</div>';
                
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 2 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp2Description.' || Code : '.$data2->Rp2Code.'</label>
				</div>';
            }
            
            if($data2->FreightReferenceFlg==3) {
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 1 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp1Description.' || Code : '.$data2->Rp1Code.'</label>
				</div>';
                
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 2 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp2Description.' || Code : '.$data2->Rp2Code.'</label>
				</div>';
                
                $html .='<div class="form-group">
				<label class="control-label col-lg-4">Disport 3 reference port : </label>
				<label class="control-label col-lg-8" style="text-align: left;">Port name : '.$data2->Rp3Description.' || Code : '.$data2->Rp3Code.'</label>
				</div>';
            }
            
            $html .='<table class="table table-striped table-hover table-bordered" style="font-size:12px;"><thead class="dark"><tr><th> Differential disport(s)  </th><th> Lp/Dp</th><th> Load/Dis Rate</th><th> Unit</th><th> Diff (Y/N)</th><th> Differential (owner) ($)</th><th> Differential (invitee) </th></tr></thead><tbody>';
            $PostGroupNo=0;
            $i=0;
            foreach($data3 as $row) {
                if($row->GroupNo!=$PostGroupNo && $i!=0) {
                    $html .='<tr><td colspan="7"><hr style="height: 1px; background-color: black;"></td></tr>';
                }
                $PostGroupNo=$row->GroupNo;    
                $html .='<tr>';
                $html .='<td>'.$row->PortName.'</td>';
                if($row->LpDpFlg==1) {
                    $lp_dp='Lp';
                }
                if($row->LpDpFlg==2) {
                    $lp_dp='Dp';
                }
                $html .='<td>'.$lp_dp.'</td>';
                $html .='<td>'.$row->LoadDischargeRate.'</td>';
                if($row->LoadDischargeUnit==1) {
                    $LoadDischargeUnit='$ mt/hr';
                }
                    
                if($row->LoadDischargeUnit==2) {
                    $LoadDischargeUnit='$ mt/day';
                }
                $html .='<td>'.$LoadDischargeUnit.'</td>';
                if($row->DifferentialFlg==1) {
                    $DifferentialFlg='Yes';
                }
                    
                if($row->DifferentialFlg==2) {
                    $DifferentialFlg='No';
                }
                $html .='<td>'.$DifferentialFlg.'</td>';
                $html .='<td>'.$row->DifferentialOwnerAmt.'</td>';
                $html .='<td>'.$row->DifferentialInviteeAmt.'</td>';
                $html .='</tr>';
                $i++;
            }
            
            $html .='</tbody></table>';
            
            
            
            //------------------------/differential---------------------------
            $html .='<br/><hr style="height: 1px; background-color: black;"><br/><h4><B> Demurrage - Despatch </B></h4>';
            $html .='<div class="form-group">
				<label class="control-label col-lg-4">Demurrage (US $/day) : </label>
				<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data1->Demurrage).'</label>
				</div>';
            
            if($data1->DespatchDemurrageFlag==1) {
                $DespatchDemurrageFlag='Yes';
            }else if($data1->DespatchDemurrageFlag==2) {
                $DespatchDemurrageFlag='No';
            }
            $html .='<div class="form-group">
			<label class="control-label col-lg-4"> Despatch - ( half Demurrage) ? : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.$DespatchDemurrageFlag.'</label>
			</div>
			<div class="form-group">
			<label class="control-label col-lg-4">Despatch - ( half Demurrage) : </label>
			<label class="control-label col-lg-8" style="text-align: left;">'.number_format($data1->DespatchHalfDemurrage).'</label>
			</div>';
            
        }
        //$html .='<hr>';
    }
    echo $html;
    
}
    
public function getQuoteChangeContent()
{
    $data=$this->cargo_quote_model->getResponseFreightDatails();
    echo json_encode($data);
}
    
public function getResponseFreightDocuments()
{
    $data=$this->cargo_quote_model->getResponseFreightDocuments();
    echo json_encode($data);
    
    
}
    
    
public function htmlDownloadResponseFreight()
{
    include_once APPPATH.'third_party/mpdf.php';
        
    $data['data1']=$this->cargo_quote_model->getResponseFreightDatails();
        
    $data['data2']=$this->cargo_quote_model->getResponseQuoteDatails($data['data1']->LineNum, $data['data1']->ResponseID, $data['data1']->FreightVersion, $data['data1']->AuctionID);
    $data['data3']=$this->cargo_quote_model->getDifferentialReferenceResponse($data['data2']->DifferentialID);
        
    // $data['data2']=$this->cargo_quote_model->getResponseQuoteDatails($data['data1']->LineNum);
        
    $html=$this->load->view('setup/pdfdownloadresponsefreight', $data, true);
    //$html='test';
    //echo $html;die;
    $pdfFilePath = $data['data1']->EntityName."(".$data['data1']->ResponseID.").pdf";
    $this->load->library('m_pdf');
    $pdf = $this->m_pdf->load();
    $pdf->WriteHTML($html);
    $pdf->Output($pdfFilePath, "D");
     
}
        
    
public function changeResponseSigningUserStatus()
{
    //print_r($_POST); die;
    $ret=$this->cargo_quote_model->changeResponseSigningUserStatus();
    if($ret) {
        echo 1;
    } else {
        echo 2;
    }
}
    
public function checkComfirmationDone()
{
    $flg=$this->cargo_quote_model->checkQuoteBusinessProcessComplete();
    if($flg==1) {
        $data=$this->cargo_quote_model->checkComfirmationDone();
        if($data->conf1 && $data->conf2 && $data->conf3) {
            echo 1;
        } else {
            echo 0;
        }
    } else {
        echo 2;
    }
        
    
}
    
public function getChatByResponseid()
{
    $ResponseID=$this->input->post('ResponseID');
    $LineNum=$this->input->post('LineNum');
    $Section=$this->input->post('Section');
    $OwnerInvFlg=$this->input->post('OwnerInvFlg');
        
    $data=$this->cargo_quote_model->getSectionCommentsByResponseID($ResponseID, $LineNum, $Section);
    $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
    $InviteeID=$RecordArr->EntityID;
    if($OwnerInvFlg==1 || $OwnerInvFlg==2) {
        $this->cargo_quote_model->changeChatNewFlg($ResponseID, $LineNum, $Section, $OwnerInvFlg);
    }
        
    $html1='';
    $html2='';
    $html3='';
    $html4='';
    $html='';
    if($Section =='TimeLine') {
        $html .='<table class="table table-striped table-hover table-bordered" style="font-size: 14px;">
				<thead class="dark"><tr><th>Datetime</th><th>Section</th><th>From</th><th>Comments</th></tr></thead><tbody>';
        foreach($data as $row) {
            $invname='';
            if($row->AdUs=='admin') {
                $invname='<span style="color: #1b75bc;">'.$row->Invname.'</span>';
            } else {
                $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                if($InviteeID==$EntityID) {
                    $invname='<span style="color: red;">'.$row->Invname.'</span>';
                }else {
                    $invname='<span style="color: blue;">'.$row->Invname.'</span>';
                }
            }
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'</td>';
            $html .='<td>'.$row->Type.'</td>';
            $html .='<td>'.$invname.'</td>';
            $html .='<td>'.$row->Chat.'</td>';
            $html .='</tr>';
        }
        $html .='</tbody></table><br/>';
    } else {
        foreach($data as $row) {
            if($row->Type=='Vessel') {
                if($row->AdUs=='admin') {
                    $html1.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                } else {
                    $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                    if($InviteeID==$EntityID) {
                              $html1.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                              //invitee
                    } else {
                         $html1.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                         //record owner
                    }
                }
                $html1 .='<hr style="background-color: black;"><br/>';
            }
            if($row->Type=='Freight') {
                if($row->AdUs=='admin') {
                    $html2.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                } else {
                    $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                    if($InviteeID==$EntityID) {
                        $html2.='<span style="color: red;">From: '.$row->Invname.'</span><span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                    } else {
                        $html2.='<span style="color: blue;">From: '.$row->Invname.'</span><span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    }
                }
                $html2 .='<hr style="background-color: black;"><br/>';
            }
            if($row->Type=='CargoPort') {
                if($row->AdUs=='admin') {
                    $html3.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                } else {
                    $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                    if($InviteeID==$EntityID) {
                        $html3.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                    } else {
                        $html3.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    }
                }
                $html3 .='<hr style="background-color: black;"><br/>';
            }
            if($row->Type=='Terms') {
                if($row->AdUs=='admin') {
                    $html4.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                } else {
                    $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                    if($InviteeID==$EntityID) {
                        $html4.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                    } else {
                        $html4.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';    
                    }
                }
                $html4 .='<hr style="background-color: black;"><br/>';
            }
                
        }
        if($html1) {
            $html .='<h3><b>Vessel</b></h3><br>';
            $html .='<hr style="background-color: black; height: 2px;"><br>';
            $html .=$html1;
        }
        if($html2) {
            $html .='<h3><b>Freight</b></h3><br>';
            $html .='<hr style="background-color: black; height: 2px;"><br>';
            $html .=$html2;
        }
        if($html3) {
            $html .='<h3><b>Cargo n Ports</b></h3><br>';
            $html .='<hr style="background-color: black; height: 2px;"><br>';
            $html .=$html3;
        }
        if($html4) {
            $html .='<h3><b>Terms</b></h3><br>';
            $html .='<hr style="background-color: black; height: 2px;"><br>';
            $html .=$html4;
        }
    }
        
    echo $html;
}
    
public function getSectionCommentsByResponseID()
{
    $ResponseID=$this->input->post('ResponseID');
    $LineNum=$this->input->post('LineNum');
    $Type=$this->input->post('Type');
    $OwnerInvFlg=$this->input->post('OwnerInvFlg');
    $data=$this->cargo_quote_model->getSectionCommentsByResponseID($ResponseID, $LineNum, $Type);
    $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
    $InviteeID=$RecordArr->EntityID;
    //print_r($OwnerInvFlg); die;
    if($OwnerInvFlg==1 || $OwnerInvFlg==2) {
        $this->cargo_quote_model->changeChatNewFlg($ResponseID, $LineNum, $Type, $OwnerInvFlg);
    }
    $html='';
    if($Type==1) {
        $html='<h3><b>Vessel Comments</b></h3><br/><hr style="background-color: black; height:2px;"><br/>';
    } else if($Type==2) {
        $html='<h3><b>Freight Comments</b></h3><br/><hr style="background-color: black; height:2px;"><br/>';
    } else if($Type==3) {
        $html='<h3><b>Cargo & Ports Comments</b></h3><br/><hr style="background-color: black; height:2px;"><br/>';
    } else if($Type==4) {
        $html='<h3><b>Terms Comments</b></h3><br/><hr style="background-color: black; height:2px;"><br/>';
    }
    foreach($data as $row){
        if($row->AdUs=='admin') {
            $html.='<span style="color: #1b75bc;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
        } else {
            $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
            if($InviteeID==$EntityID) {
                 $html.='<span style="color: red;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                 //invitee
            } else {
                $html.='<span style="color: blue;">From: '.$row->Invname.'</span> <span style="float: right;">'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br><br>';
                //record owner
            }
        }
        $html .='<hr style="background-color: black;"><br/>';
    }
        
    echo $html;
}
    
public function saveResponseCargoDisports()
{
    $ResponseCargoID=$this->input->post('ResponseCargoID');
    $flg=$this->cargo_quote_model->saveResponseCargoDisports();
    if($flg) {
        $data['Disports']=$this->cargo_quote_model->getResponseDisportsByResponseCargoIDChangable($ResponseCargoID);
        $data['flg']=1;
    } else{
        $data['flg']=0;
    }
    echo json_encode($data);
}
    
public function deleteResponseCargoDisports()
{
    $ResponseCargoID=$this->input->post('ResponseCargoID');
    $flg=$this->cargo_quote_model->deleteResponseCargoDisports();
    if($flg) {
        $data['Disports']=$this->cargo_quote_model->getResponseDisportsByResponseCargoIDChangable($ResponseCargoID);
        $data['flg']=1;
    } else{
        $data['flg']=0;
    }
    echo json_encode($data);
}
    
public function getResponseCargoDisportsById()
{
    $data['disport']=$this->cargo_quote_model->getResponseCargoDisportsById();
    $data['excepted_periods']=$this->cargo_quote_model->getExceptedPeriodEventsByResponseDisportId();
    $data['tendering_pre_conditions']=$this->cargo_quote_model->getTenderingPreConditionsByResponseDisportId();
    $data['acceptance_pre_conditions']=$this->cargo_quote_model->getAcceptancePreConditionByResponseDisportId();
    $data['office_hours']=$this->cargo_quote_model->getOfficeHoursByResponseDisportId();
    $data['laytime_commencement']=$this->cargo_quote_model->getLaytimeCommencementByResponseDisportId();
    echo json_encode($data);
}
    
public function updateResponseCargoDisports()
{
    $ResponseCargoID=$this->input->post('ResponseCargoID');
    $flg=$this->cargo_quote_model->updateResponseCargoDisports();
    if($flg) {
        $data['Disports']=$this->cargo_quote_model->getResponseDisportsByResponseCargoIDChangable($ResponseCargoID);
        $data['flg']=1;
    } else{
        $data['flg']=0;
    }
    echo json_encode($data);
}
    
public function cloneResponseCargoDisports()
{
    $ResponseCargoID=$this->input->post('ResponseCargoID');
    $flg=$this->cargo_quote_model->cloneResponseCargoDisports();
    if($flg) {
        $data['Disports']=$this->cargo_quote_model->getResponseDisportsByResponseCargoIDChangable($ResponseCargoID);
        $data['flg']=1;
    } else{
        $data['flg']=0;
    }
    echo json_encode($data);
}
    
    // sujeet integrate 31-10-2018
    
    
public function getResponseAssessmentNew()
{
    $AuctionID=$this->input->get('AuctionID');
    //$AuctionID='V17-L69-F06';
    $RecordOwner=$this->input->get('RecordOwner');
    //$RecordOwner=9295;
    $data=$this->cargo_quote_model->getResponseAssessmentNew();
    $this->cargo_quote_model->setChangeStatusZero($AuctionID);
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    //print_r($responseids); die;
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
    $vesseldata= array();
    $responseid='';
        
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
        
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    $html='';
    $inhtml='';
    $pscdef='';
    $proxpref='';
    $pm=0;
    $rating=0;
    $response_id_arr=array();
    $risk_rating_arr=array();
    $FreightRate_arr=array();
    $FreightInclDemDelays_arr=array();
    $Demurrage_arr=array();
    $Estimate_mt_arr=array();
    $Estimate_Index_mt_arr=array();
    $rating_arr=array();
    $in_html=array();
    $nchange_html=array();
    $DetentionFlag_arr=array();
    $Deficiency_arr=array();
    $pscdef_arr=array();
    $proxpref_arr=array();
    $CHTR_QUOTE_arr=array();
    $CHTR_QUOTE_arr=array();
    $imo_arr=array();


    $crgocnt=$this->cargo_quote_model->getCargoCountByAuctionID($AuctionID);
        
    $CargoCount=count($crgocnt);
    foreach($crgocnt as $crgcnt) {
        $crgo=$this->cargo_quote_model->getCargoDataByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $quote=$this->cargo_quote_model->getQuoteByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $Demurrage=$this->cargo_quote_model->getDemmurageByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $lppd=date('Y-m-d', strtotime($crgo->LpPreferDate));
        if($lppd=='1970-01-01') {
            $lppd=date('Y-m-d', strtotime($crgo->LpLaycanStartDate));
        }
        
        foreach($vesseldata as $v) {
            
            $lpsd=date('Y-m-d', strtotime($v->FirstLoadPortDate));
            $lppd1=date_create($lppd);
            $lpsd1=date_create($lpsd);
            $diff=date_diff($lppd1, $lpsd1);
            $ddef[]=$diff->format("%R%a");

        } 
        $ccnn=count($ddef);
        $prang=$ddef[$ccnn-1]-$ddef[0];
        foreach($data as $row) {
            $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
            $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
            $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
            $DemCostLP=$frt->Demurrage*$DelayLP;
            $DemCostDP=$frt->Demurrage*$DelayDP;
            $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
            $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
            $FreightInclDemDelays=($TotalFrtInclDemDelays/$crgo->CargoQtyMT);
            $fidd[]=round($FreightInclDemDelays, 2);
            //print_r($DemLP); 
        }
        //die;
        sort($fidd);
        //print_r($fidd);die;
        $c=count($quote);
        $d=count($Demurrage);
        $f=count($fidd);
        //echo $c;die;
        $low=$quote[0]->FreightRate;
        $high=$quote[$c-1]->FreightRate;
        $range=$high-$low;
        
        $fiddlow=$fidd[0];
        $fiddhigh=$fidd[$f-1];
        $fiddrange1=$fiddhigh-$fiddlow;
        $fiddrange=round($fiddrange1, 2);
        
        
        $dlow=$Demurrage[0]->Demurrage;
        $dhigh=$Demurrage[$d-1]->Demurrage;
        $drange=$dhigh-$dlow;
        
        $difarray=array();
        //print_r($data); die;
        foreach($data as $r) {
            foreach($vesseldata as $v) {
                if($r->ResponseID==$v->ResponseID) {
                      $d1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                      $d2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                      $d=date_diff($d2, $d1);
                      $difarray[]=$d->format("%a");
                }
            }
        }
        rsort($difarray);
        $MaxDiff=$difarray[0];
        //echo $MaxDiff;die;
        
        $html='{ "aaData": [';
        
        foreach($data as $row) {
        
            if($row->TentativeStatus==1) {
                $status='Tentative Acceptance';
            } else {
                if($row->ResponseStatus=='Submitted') {
                    $status=$row->ResponseStatus;
                }else{
                    $status='In progress';
                    continue;
                }
            }
            
            foreach($vesseldata as $v) {
                if($row->ResponseID==$v->ResponseID) {
                    $response_id_arr[]=$row->ResponseID;
                    $imo=$this->cargo_quote_model->getVesselImo($AuctionID, $row->ResponseID);
                    $imo_arr[]=$imo;
                    $rating=0;
                    //$ratingp='';
                    if($row->EntityID==$v->DisponentOwnerID) {
                        $priority=$this->cargo_quote_model->getProrityByAuctionID($row->EntityID, $AuctionID);
                    } else {
                        $priority=$this->cargo_quote_model->getProrityForShipBroker($v->DisponentOwnerID);
                    }
            
                    $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
                    $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                    $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                    $DemCostLP=$frt->Demurrage*$DelayLP;
                    $DemCostDP=$frt->Demurrage*$DelayDP;
                    $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                    $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
                    $FreightInclDemDelays=round(($TotalFrtInclDemDelays/$crgo->CargoQtyMT), 2);
            
                    $DatePer=0;
                    $fiddper=0;
                    $per=0;
                    if($range==0) {
                        $ratio=0;
                    } else {
                        $ratio=($frt->FreightRate-$low)/$range;
                    }
            
                    if($drange==0) {
                        $dratio=0;
                    } else {
                        $dratio=($frt->Demurrage-$dlow)/$drange;
                    }
            
                    if($fiddrange==0) {
                        $fidratio=0;
                    } else {
                        $fidratio=($FreightInclDemDelays-$fiddlow)/$fiddrange;
                    }
            
                    if($model->FreightCriteriaStatus==1) {
                        if($frt->FreightRate>0) {
                            $per=$ratio*100;
                            $perrange1=trim($model->FpercentageRange1, '%');
                            $perrange2=trim($model->FpercentageRange2, '%');
                            $perrange3=trim($model->FpercentageRange3, '%');
                            $perrange4=trim($model->FpercentageRange4, '%');
                            $perrange5=trim($model->FpercentageRange5, '%');
                            $pr1=$model->FpercentageRange1;
                            $pr2=$model->FpercentageRange2;
                            $pr3=$model->FpercentageRange3;
                            $pr4=$model->FpercentageRange4;
                            $pr5=$model->FpercentageRange5;
                            if($per<=$pr1) {
                                     $rating=$model->FpercentageValue1;
                            } else  if($per>$pr1 && $per<=$pr2) {
                                    $rating=$model->FpercentageValue2;
                            } else  if($per>$pr2 && $per<=$pr3) {
                                $rating=$model->FpercentageValue3;
                            } else  if($per>$pr3 && $per<=$pr4) {
                                    $rating=$model->FpercentageValue4;
                            } else  if($per>$pr4) {
                                $rating=$model->FpercentageValue5;
                            }
                        } 
                        //$ratingp .=$rating.'->'; row
                    }
            
            
                    if($model->FIDDCriteriaStatus==1) {
                        $fiddper=$fidratio*100;
                        $fiddpr1=$model->FIDDpercentageRange1;
                        $fiddpr2=$model->FIDDpercentageRange2;
                        $fiddpr3=$model->FIDDpercentageRange3;
                        $fiddpr4=$model->FIDDpercentageRange4;
                        $fiddpr5=$model->FIDDpercentageRange5;
                        if($fiddper <= $fiddpr1) {
                            $rating=$rating+$model->FIDDpercentageValue1;
                        } else  if($fiddper > $fiddpr1 && $fiddper<=$fiddpr2) {
                            $rating=$rating+$model->FIDDpercentageValue2;
                        } else  if($fiddper > $fiddpr2 && $fiddper<=$fiddpr3) {
                            $rating=$rating+$model->FIDDpercentageValue3;
                        } else  if($fiddper > $fiddpr3 && $fiddper<=$fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue4;
                        } else  if($fiddper > $fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue5;
                        } 
                        //$ratingp .=$rating.'->';    row            
                    }
            
                    if($model->DemurrageCriteriaStatus==1) {
                
                        if($frt->Demurrage>0) {
                            $dper=$dratio*100;
                            $dpr1=$model->DpercentageRange1;
                            $dpr2=$model->DpercentageRange2;
                            $dpr3=$model->DpercentageRange3;
                            $dpr4=$model->DpercentageRange4;
                            $dpr5=$model->DpercentageRange5;
                            //echo $frt->Demurrage.'->'.$dlow.',';
                            if($dper<=$dpr1) {
                                $rating=$rating+$model->DpercentageValue1;
                            } else  if($dper>$dpr1 && $dper<=$dpr2) {
                                 $rating=$rating+$model->DpercentageValue2;
                            } else  if($dper>$dpr2 && $dper<=$dpr3) {
                                    $rating=$rating+$model->DpercentageValue3;
                            } else  if($dper>$dpr3 && $dper<=$dpr4) {
                                $rating=$rating+$model->DpercentageValue4;
                            } else  if($dper>$dpr4) {
                                        $rating=$rating+$model->DpercentageValue5;
                            }
                        }
                        //$ratingp .=$rating.'->';            
                    }
                    //echo $row->ResponseID.'->'.$rating.',';
                    if($model->InviteeCriteriaStatus==1) {
                        if($priority=='P1') {
                            $rating=$rating+$model->InviteePriorityValue1;
                        } else if($priority=='P2') {
                            $rating=$rating+$model->InviteePriorityValue2;
                        } else if($priority=='P3') {
                            $rating=$rating+$model->InviteePriorityValue3;
                        } else {
                            $rating=$rating+$model->InviteePriorityValue4;
                        }
                        //$ratingp .=$rating.'-> ....'.$priority.'....';
                    }
            
                    if($model->PSCDLYCS==1) {    
                        if($v->DetentionFlag=='Yes') {
                            $rating=$rating+$model->PSCDLYAPC;
                        } else {
                            $rating=$rating+$model->PSCDLYMP;
                        }
                        //$ratingp .=$rating.'->';
                    }

                    if($model->PSCDPCS==1) {
                        if($v->Deficiency=='Outstanding') {
                            $rating=$rating+$model->PSCDPAPC;
                        } else {
                            $rating=$rating+$model->PSCDPMPC;
                        }
                        //$ratingp .=$rating.'->';
                    }
        
                    $compdate=date('Y-m-d', strtotime($v->DeficiencyCompDate));
                    $lparivaldate=date('Y-m-d', strtotime($v->FirstLoadPortDate));
                    if($model->PSCDRPFLPACS==1) {
                        if($v->Deficiency=='Outstanding' && ($compdate>=$lparivaldate)) {
                            $rating=$rating+$model->PSCDRPFLPAAPC;
                        } else {
                            $rating=$rating+$model->PSCDRPFLPAMPC;
                        }
                        //$ratingp .=$rating.'->';
                    }
                    $lpsd1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {    
                        $diff=date_diff($lppd1, $lpsd1);
                        $ddflpd=$diff->format("%R%a");
                        $ppeerr=($ddflpd/$prang)*100;
                    }else{
                        $ppeerr=0;
                    }
            
                    $date1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    $date2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                    $date3=date_create(date('Y-m-d', strtotime($v->DeficiencyCompDate)));
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {
                        if(date('Y-m-d', strtotime($crgo->LpPreferDate))=='1970-01-01') {
                            $pscdef='-';
                        }else{
                            $diff=date_diff($date2, $date1);
                            $pscdef=$diff->format("%R%a days");
                            $pscdefcal=$diff->format("%a days");
                            $DatePer=($pscdefcal/$MaxDiff)*100; 
                        }
                    }else{
                        $pscdef='-';
                    }
            
                    if($model->PFLPPADCS==1) {
                        $ppad1=$model->PFLPPADMPC1;
                        $ppad2=$model->PFLPPADMPC2;
                        $ppad3=$model->PFLPPADMPC3;
                        $ppad4=$model->PFLPPADMPC4;
                        $ppad5=$model->PFLPPADMPC5;
                        if($DatePer<=$ppad1) {
                            $rating=$rating+$model->PFLPPADMPV1;
                        } else  if($DatePer>$ppad1 && $DatePer<=$ppad2) {
                            $rating=$rating+$model->PFLPPADMPV2;
                        } else  if($DatePer>$ppad2 && $DatePer<=$ppad3) {
                            $rating=$rating+$model->PFLPPADMPV3;
                        } else  if($DatePer>$ppad3 && $DatePer<=$ppad4) {
                            $rating=$rating+$model->PFLPPADMPV4;
                        } else {
                            $rating=$rating+$model->PFLPPADMPV5;
                        }
                        //$ratingp .=$rating.'->';
                    }
            
                    if($model->RatingStatus==1) {
                        if($v->Rating==1) {
                            $rating=$rating+$model->PrcentRangeValue1;
                        } else if($v->Rating==2) {
                            $rating=$rating+$model->PrcentRangeValue2;
                        } else if($v->Rating==3) {
                            $rating=$rating+$model->PrcentRangeValue3;
                        } else if($v->Rating==4) {
                            $rating=$rating+$model->PrcentRangeValue4;
                        } else if($v->Rating==5) {
                            $rating=$rating+$model->PrcentRangeValue5;
                        }
                        //$ratingp .=$rating.'->';
                    }
        
                    $diff1=date_diff($date1, $date3);
                    $pm=$diff1->format("%R%a");
            
                    if(date('Y-m-d', strtotime($v->DeficiencyCompDate))=='1970-01-01') {
                        $proxpref="<span title='PSC Def. rectify b4 arrival'>N/A</span>";
                    } else {
                        if($pm>0) {
                            $proxpref="<span  title='PSC Def. rectify b4 arrival'>Yes</span>";
                        } else {
                            $proxpref="<span style='color: red;' title='PSC Def. rectify b4 arrival'>No</span>";
                        }
                    }
        
        
                    if($v->Rating==1) {
                        $risk_rating="<span style='color: red;' title='Risk Rating'>".$v->Rating."</span>";
                        $risk_rating_arr[]=$v->Rating;
                    }else if($v->Rating==2) {
                        $risk_rating="<span style='color: orange;' title='Risk Rating'>".$v->Rating."</span>";
                        $risk_rating_arr[]=$v->Rating;
                    }else{
                        $risk_rating="<span title='Risk Rating'>".$v->Rating."</span>";
                        $risk_rating_arr[]=$v->Rating;
                    }
        
                    if($v->DetentionFlag=='Yes') {
                        $DetentionFlag="<span style='color: red;' title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                    }else{
                        $DetentionFlag="<span title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                    }
                    if($imo==0) {
                        $check="<input type='checkbox' disabled>";
                    } else {
                        $check="<input type='checkbox' name='AuctionID[]' class='chk' value='".$row->ResponseID.",".$crgcnt->LineNum."'>";    
                    }
                    $CHTR="<a onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR </a>";
                    $QUOTE="<a onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."') title='view quote details'>QUOTE</a>";
                    if($imo==0) {
                        $inhtml .='["'.$check.'","<b>'.$status.'</b>","<b>'.$row->EntityName.'</b>';
                    } else {
                        $inhtml .='["'.$check.'","'.$status.'","'.$row->EntityName;
                    }
                    if($model->InviteeCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='","<b>'.$priority.'</b>';
                        } else {
                            $inhtml .='","'.$priority;
                        }
                    }
                    if($imo==0) {
                        $inhtml .='","<b>'.$row->ResponseID.'</b>","<b>'.$v->VesselName.'</b>';
                    } else {
                        $inhtml .='","'.$row->ResponseID.'","'.$v->VesselName;    
                    }
                    $nchange_html[]=$inhtml;
        
                    if($model->RatingStatus==1) {
                        if($imo==0) {
                            $inhtml .='", "-';
                        } else {
                            $inhtml .='", "'.$risk_rating;
                        }
                    }
                    if($model->FreightCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='","<b>'.$frt->FreightRate.'</b>';
                        } else {
                            $inhtml .='","'.$frt->FreightRate;
                        }
                    }
                    $FreightRate_arr[]=$frt->FreightRate;
                    if($model->FIDDCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='","<b>'.number_format($FreightInclDemDelays, 3).'</b>';
                        } else {
                            $inhtml .='","'.number_format($FreightInclDemDelays, 3);
                        }
                    }
                    $FreightInclDemDelays_arr[]=$FreightInclDemDelays;
                    if($model->DemurrageCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='","<b>'.$frt->Demurrage.'</b>';
                        } else {
                            $inhtml .='","'.$frt->Demurrage;
                        }
                    }
                    $Demurrage_arr[]=$frt->Demurrage;
                    if($imo==0) {
                        $inhtml .='", "<b>'.$crgo->Estimate_mt.'</b>","<b>'.$crgo->Estimate_Index_mt.'</b>';
                    } else {
                        $inhtml .='", "'.$crgo->Estimate_mt.'","'.$crgo->Estimate_Index_mt;    
                    }
                    $Estimate_mt_arr[]=$crgo->Estimate_mt;
                    $Estimate_Index_mt_arr[]=$crgo->Estimate_Index_mt;
                    if($model->PSCDLYCS==1) {
                        if($imo==0) {
                            $inhtml .='", "-';
                            $DetentionFlag_arr[]='-';
                        } else {
                            $inhtml .='","'.$DetentionFlag;
                            $DetentionFlag_arr[]=$DetentionFlag;
                        }
                    }
                    if($model->PSCDPCS==1) {
                        if($imo==0) {
                            $inhtml .='", "-';
                            $Deficiency_arr[]='-';
                        } else {
                            $inhtml .='","'.$v->Deficiency;
                            $Deficiency_arr[]=$v->Deficiency;
                        }
                    }
                    if($model->PFLPPADCS==1) {
                        $inhtml .='","'.$pscdef;
                    }
                    $pscdef_arr[]=$pscdef;
                    if($model->PSCDRPFLPACS==1) {
                        if($imo==0) {
                            $inhtml .='", "-';
                            $proxpref_arr[]='-';
                        } else {
                            $inhtml .='","'.$proxpref;
                            $proxpref_arr[]=$proxpref;
                        }
                    }
                    if($model->IPOLYCS==1) {
                        $inhtml .='","NA';
                    }
                    if($model->VLTCS==1) {
                        $inhtml .='","NA';
                    }
        
                    $rating1="<input type='hidden' name='rating[]' class='rating' value='".$rating."'>".$rating."";
                    //$rating1 .='=>'.$ratingp; row
                    if($imo==0) {
                        $inhtml .='", "-';
                        $rating_arr[]=0;
                    } else {
                        $inhtml .='", "'.$rating1;
                        $rating_arr[]=$rating;
                    }
        
                    $inhtml .='","'.$CHTR.'&nbsp'.$QUOTE.'"],';
                    $CHTR_QUOTE_arr[]='","'.$CHTR.'&nbsp'.$QUOTE.'"],';
                    $in_html[]=$inhtml;
                    $inhtml='';
                }
            }
        }
    }
    //print_r($response_id_arr);
    //print_r($rating_arr);die;
        
    $inrhtml='';
    $response_id_arr_uniq=array();
    for($k=0;$k<count($response_id_arr);$k++) {
        if (in_array($response_id_arr[$k], $response_id_arr_uniq)) {
        } else {
            $response_id_arr_uniq[]=$response_id_arr[$k];
        }
    }
    for($i=0;$i<count($response_id_arr_uniq);$i++) {
        $risk_rating_tot=0;
        $FreightRate_tot=0;
        $FreightInclDemDelays_tot=0;
        $Demurrage_tot=0;
        $Estimate_mt_tot=0;
        $Estimate_Index_mt_tot=0;
        $rating_tot=0;    
        for($j=0;$j<count($response_id_arr);$j++) {
            if($response_id_arr[$i]==$response_id_arr[$j]) {
                $risk_rating_tot=$risk_rating_tot+$risk_rating_arr[$j];
                $FreightRate_tot=$FreightRate_tot+$FreightRate_arr[$j];
                $FreightInclDemDelays_tot=$FreightInclDemDelays_tot+$FreightInclDemDelays_arr[$j];
                $Demurrage_tot=$Demurrage_tot+$Demurrage_arr[$j];
                $Estimate_mt_tot=$Estimate_mt_tot+$Estimate_mt_arr[$j];
                $Estimate_Index_mt_tot=$Estimate_Index_mt_tot+$Estimate_Index_mt_arr[$j];
                $rating_tot=$rating_tot+$rating_arr[$j];
                //echo $response_id_arr[$j].'->'.$rating_arr[$j].'<br>';
            }
        }
        $inrhtml .=$nchange_html[$i];
        if($model->RatingStatus==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='", "-';
            } else {
                $inrhtml .='", "'.($risk_rating_tot/$CargoCount);
            }
        }
        if($model->FreightCriteriaStatus==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='","<b>'.($FreightRate_tot/$CargoCount).'</b>';
            } else {
                $inrhtml .='","'.($FreightRate_tot/$CargoCount);
            }
        }
        if($model->FIDDCriteriaStatus==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='","<b>'.number_format(($FreightInclDemDelays_tot/$CargoCount), 3).'</b>';
            } else {
                $inrhtml .='","'.number_format(($FreightInclDemDelays_tot/$CargoCount), 3);
            }
        }
        if($model->DemurrageCriteriaStatus==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='","<b>'.($Demurrage_tot/$CargoCount).'</b>';
            } else {
                $inrhtml .='","'.($Demurrage_tot/$CargoCount);
            }
        }
        if($imo_arr[$i]==0) {
            $inrhtml .='", "<b>'.($Estimate_mt_tot/$CargoCount).'</b>","<b>'.($Estimate_Index_mt_tot/$CargoCount).'</b>';
        } else {
            $inrhtml .='", "'.($Estimate_mt_tot/$CargoCount).'","'.($Estimate_Index_mt_tot/$CargoCount);    
        }
        if($model->PSCDLYCS==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='", "-';
            } else {
                $inrhtml .='","'.$DetentionFlag_arr[$i];
            }
        }
        if($model->PSCDPCS==1) { 
            if($imo_arr[$i]==0) {
                $inrhtml .='", "-';
            } else {
                $inrhtml .='","'.$Deficiency_arr[$i];
            }
        }
        if($model->PFLPPADCS==1) {
            $inrhtml .='","'.$pscdef_arr[$i];
        }
        if($model->PSCDRPFLPACS==1) {
            if($imo_arr[$i]==0) {
                $inrhtml .='", "-';
            } else {
                $inrhtml .='","'.$proxpref_arr[$i];
            }
        }
        if($model->IPOLYCS==1) {
            $inrhtml .='","NA';
        }
        if($model->VLTCS==1) {
            $inrhtml .='","NA';
        }
        
        $rating1="<input type='hidden' name='rating[]' class='rating' value='".($rating_tot/$CargoCount)."'>".($rating_tot/$CargoCount)."";
        //$rating1 .='=>'.$ratingp;
        if($imo_arr[$i]==0) {
            $inrhtml .='", "-';
        } else {
            $inrhtml .='", "'.$rating1;
        }
        $inrhtml .=$CHTR_QUOTE_arr[$i];
    } 
        
        
    $html .=trim($inrhtml, ",");    
    $html .='] }';
    echo $html; 
} 
    
public function getResponseAssessmentNewLineWise()
{
    $AuctionID=$this->input->get('AuctionID');
    //$AuctionID='V17-L69-F06';
    $RecordOwner=$this->input->get('RecordOwner');
    //$RecordOwner=9295;
    $data=$this->cargo_quote_model->getResponseAssessmentNew();
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    //print_r($responseids); die;
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
        
    $vesseldata= array();
    $responseid='';
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
    //print_r($vesseldata);die;
        
    $model=$this->cargo_quote_model->getModelSetupByRecoredOwner($RecordOwner, $AuctionID);
    $inhtml='';
    $inhtmlLine=array();
    $pscdef='';
    $proxpref='';
    $pm=0;
    $rating=0;
    $crgocnt=$this->cargo_quote_model->getCargoCountByAuctionID($AuctionID);
    foreach($crgocnt as $crgcnt) {
        $crgo=$this->cargo_quote_model->getCargoDataByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $quote=$this->cargo_quote_model->getQuoteByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $Demurrage=$this->cargo_quote_model->getDemmurageByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        $lppd=date('Y-m-d', strtotime($crgo->LpPreferDate));
        if($lppd=='1970-01-01') {
            $lppd=date('Y-m-d', strtotime($crgo->LpLaycanStartDate));
        }
        
        foreach($vesseldata as $v) {
            
            $lpsd=date('Y-m-d', strtotime($v->FirstLoadPortDate));
            $lppd1=date_create($lppd);
            $lpsd1=date_create($lpsd);
            $diff=date_diff($lppd1, $lpsd1);
            $ddef[]=$diff->format("%R%a");

        } 
        $ccnn=count($ddef);
        $prang=$ddef[$ccnn-1]-$ddef[0];
        foreach($data as $row) {
            $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
            $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
            $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
            $DemCostLP=$frt->Demurrage*$DelayLP;
            $DemCostDP=$frt->Demurrage*$DelayDP;
            $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
            $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
            $FreightInclDemDelays=($TotalFrtInclDemDelays/$crgo->CargoQtyMT);
            $fidd[]=round($FreightInclDemDelays, 2);
            //print_r($DemLP); 
        }
        //die;
        sort($fidd);
        //print_r($fidd);die;
        $c=count($quote);
        $d=count($Demurrage);
        $f=count($fidd);
        //echo $c;die;
        $low=$quote[0]->FreightRate;
        $high=$quote[$c-1]->FreightRate;
        $range=$high-$low;
        
        $fiddlow=$fidd[0];
        $fiddhigh=$fidd[$f-1];
        $fiddrange1=$fiddhigh-$fiddlow;
        $fiddrange=round($fiddrange1, 2);
        
        
        $dlow=$Demurrage[0]->Demurrage;
        $dhigh=$Demurrage[$d-1]->Demurrage;
        $drange=$dhigh-$dlow;
        
        $difarray=array();
        //print_r($data); die;
        foreach($data as $r) {
            foreach($vesseldata as $v) {
                if($r->ResponseID==$v->ResponseID) {
                      $d1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                      $d2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                      $d=date_diff($d2, $d1);
                      $difarray[]=$d->format("%a");
                }
            }
        }
        rsort($difarray);
        $MaxDiff=$difarray[0];
        //echo $MaxDiff;die;
        foreach($data as $row) {
            $imo=$this->cargo_quote_model->getVesselImo($AuctionID, $row->ResponseID);
            $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            $imo_arr[]=$imo;
            if($row->TentativeStatus==1) {
                $status='Tentative Acceptance';
            } else {
                if($row->ResponseStatus=='Submitted') {
                    $status=$row->ResponseStatus;
                }else{
                    $status='In progress';
                    continue;
                }
            }
            
            foreach($vesseldata as $v) {
                if($row->ResponseID==$v->ResponseID) {
                    $rating=0;
                    //$ratingp='';
                    if($row->EntityID==$v->DisponentOwnerID) {
                        $priority=$this->cargo_quote_model->getProrityByAuctionID($row->EntityID, $AuctionID);
                    } else {
                        $priority=$this->cargo_quote_model->getProrityForShipBroker($v->DisponentOwnerID);
                    }
            
                    $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
            
                    $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
                    $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                    $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                    $DemCostLP=$frt->Demurrage*$DelayLP;
                    $DemCostDP=$frt->Demurrage*$DelayDP;
                    $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                    $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
                    $FreightInclDemDelays=round(($TotalFrtInclDemDelays/$crgo->CargoQtyMT), 2);
            
                    $DatePer=0;
                    $fiddper=0;
                    $per=0;
                    if($range==0) {
                        $ratio=0;
                    } else {
                        $ratio=($frt->FreightRate-$low)/$range;
                    }
            
                    if($drange==0) {
                        $dratio=0;
                    } else {
                        $dratio=($frt->Demurrage-$dlow)/$drange;
                    }
            
                    if($fiddrange==0) {
                        $fidratio=0;
                    } else {
                        $fidratio=($FreightInclDemDelays-$fiddlow)/$fiddrange;
                    }
            
                    if($model->FreightCriteriaStatus==1) {
                        if($frt->FreightRate>0) {
                            $per=$ratio*100;
                            $perrange1=trim($model->FpercentageRange1, '%');
                            $perrange2=trim($model->FpercentageRange2, '%');
                            $perrange3=trim($model->FpercentageRange3, '%');
                            $perrange4=trim($model->FpercentageRange4, '%');
                            $perrange5=trim($model->FpercentageRange5, '%');
                            $pr1=$model->FpercentageRange1;
                            $pr2=$model->FpercentageRange2;
                            $pr3=$model->FpercentageRange3;
                            $pr4=$model->FpercentageRange4;
                            $pr5=$model->FpercentageRange5;
                            if($per<=$pr1) {
                                     $rating=$model->FpercentageValue1;
                            } else  if($per>$pr1 && $per<=$pr2) {
                                    $rating=$model->FpercentageValue2;
                            } else  if($per>$pr2 && $per<=$pr3) {
                                $rating=$model->FpercentageValue3;
                            } else  if($per>$pr3 && $per<=$pr4) {
                                        $rating=$model->FpercentageValue4;
                            } else  if($per>$pr4) {
                                $rating=$model->FpercentageValue5;
                            }
                        } 
        
                    }
            
            
                    if($model->FIDDCriteriaStatus==1) {
                        $fiddper=$fidratio*100;
                        $fiddpr1=$model->FIDDpercentageRange1;
                        $fiddpr2=$model->FIDDpercentageRange2;
                        $fiddpr3=$model->FIDDpercentageRange3;
                        $fiddpr4=$model->FIDDpercentageRange4;
                        $fiddpr5=$model->FIDDpercentageRange5;
                        if($fiddper <= $fiddpr1) {
                            $rating=$rating+$model->FIDDpercentageValue1;
                        } else  if($fiddper > $fiddpr1 && $fiddper<=$fiddpr2) {
                            $rating=$rating+$model->FIDDpercentageValue2;
                        } else  if($fiddper > $fiddpr2 && $fiddper<=$fiddpr3) {
                            $rating=$rating+$model->FIDDpercentageValue3;
                        } else  if($fiddper > $fiddpr3 && $fiddper<=$fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue4;
                        } else  if($fiddper > $fiddpr4) {
                            $rating=$rating+$model->FIDDpercentageValue5;
                        } 
                        //$ratingp .=$rating.'->';    row            
                    }
            
                    if($model->DemurrageCriteriaStatus==1) {
                        if($frt->Demurrage>0) {
                            $dper=$dratio*100;
                            $dpr1=$model->DpercentageRange1;
                            $dpr2=$model->DpercentageRange2;
                            $dpr3=$model->DpercentageRange3;
                            $dpr4=$model->DpercentageRange4;
                            $dpr5=$model->DpercentageRange5;
                            if($dper<=$dpr1) {
                                $rating=$rating+$model->DpercentageValue1;
                            } else  if($dper>$dpr1 && $dper<=$dpr2) {
                                 $rating=$rating+$model->DpercentageValue2;
                            } else  if($dper>$dpr2 && $dper<=$dpr3) {
                                    $rating=$rating+$model->DpercentageValue3;
                            } else  if($dper>$dpr3 && $dper<=$dpr4) {
                                $rating=$rating+$model->DpercentageValue4;
                            } else  if($dper>$dpr4) {
                                        $rating=$rating+$model->DpercentageValue5;
                            }
                        }
                        //$ratingp .=$rating.'->';            
                    }
            
                    if($model->InviteeCriteriaStatus==1) {
                        if($priority=='P1') {
                            $rating=$rating+$model->InviteePriorityValue1;
                        } else if($priority=='P2') {
                            $rating=$rating+$model->InviteePriorityValue2;
                        } else if($priority=='P3') {
                            $rating=$rating+$model->InviteePriorityValue3;
                        } else {
                            $rating=$rating+$model->InviteePriorityValue4;
                        }
                        //$ratingp .=$rating.'-> ....'.$priority.'....';
                    }
            
                    if($model->PSCDLYCS==1) {    
                        if($v->DetentionFlag=='Yes') {
                            $rating=$rating+$model->PSCDLYAPC;
                        } else {
                            $rating=$rating+$model->PSCDLYMP;
                        }
                        //$ratingp .=$rating.'->';
                    }

                    if($model->PSCDPCS==1) {
                        if($v->Deficiency=='Outstanding') {
                            $rating=$rating+$model->PSCDPAPC;
                        } else {
                            $rating=$rating+$model->PSCDPMPC;
                        }
        
                    }
        
                    $compdate=date('Y-m-d', strtotime($v->DeficiencyCompDate));
                    $lparivaldate=date('Y-m-d', strtotime($v->FirstLoadPortDate));
                    if($model->PSCDRPFLPACS==1) {
                        if($v->Deficiency=='Outstanding' && ($compdate>=$lparivaldate)) {
                            $rating=$rating+$model->PSCDRPFLPAAPC;
                        } else {
                            $rating=$rating+$model->PSCDRPFLPAMPC;
                        }

                    }
                    $lpsd1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {    
                        $diff=date_diff($lppd1, $lpsd1);
                        $ddflpd=$diff->format("%R%a");
                        $ppeerr=($ddflpd/$prang)*100;
                    }else{
                        $ppeerr=0;
                    }
            
                    $date1=date_create(date('Y-m-d', strtotime($v->FirstLoadPortDate)));
                    $date2=date_create(date('Y-m-d', strtotime($crgo->LpPreferDate)));
                    $date3=date_create(date('Y-m-d', strtotime($v->DeficiencyCompDate)));
                    if(date('Y-m-d', strtotime($v->FirstLoadPortDate)) != '1970-01-01') {
                        if(date('Y-m-d', strtotime($crgo->LpPreferDate))=='1970-01-01') {
                            $pscdef='-';
                        }else{
                            $diff=date_diff($date2, $date1);
                            $pscdef=$diff->format("%R%a days");
                            $pscdefcal=$diff->format("%a days");
                            $DatePer=($pscdefcal/$MaxDiff)*100; 
                        }
                    }else{
                        $pscdef='-';
                    }
                    //print_r($pscdef);die;
                    if($model->PFLPPADCS==1) {
                        $ppad1=$model->PFLPPADMPC1;
                        $ppad2=$model->PFLPPADMPC2;
                        $ppad3=$model->PFLPPADMPC3;
                        $ppad4=$model->PFLPPADMPC4;
                        $ppad5=$model->PFLPPADMPC5;
                        if($DatePer<=$ppad1) {
                            $rating=$rating+$model->PFLPPADMPV1;
                        } else  if($DatePer>$ppad1 && $DatePer<=$ppad2) {
                            $rating=$rating+$model->PFLPPADMPV2;
                        } else  if($DatePer>$ppad2 && $DatePer<=$ppad3) {
                            $rating=$rating+$model->PFLPPADMPV3;
                        } else  if($DatePer>$ppad3 && $DatePer<=$ppad4) {
                            $rating=$rating+$model->PFLPPADMPV4;
                        } else {
                            $rating=$rating+$model->PFLPPADMPV5;
                        }
                        //echo $DatePer.' '.$ppad1.' '.$ppad2.' '.$ppad3.' '.$ppad4;die;
                    }
            
                    if($model->RatingStatus==1) {
                        if($v->Rating==1) {
                            $rating=$rating+$model->PrcentRangeValue1;
                        } else if($v->Rating==2) {
                            $rating=$rating+$model->PrcentRangeValue2;
                        } else if($v->Rating==3) {
                            $rating=$rating+$model->PrcentRangeValue3;
                        } else if($v->Rating==4) {
                            $rating=$rating+$model->PrcentRangeValue4;
                        } else if($v->Rating==5) {
                            $rating=$rating+$model->PrcentRangeValue5;
                        }
                        //$ratingp .=$rating.'->';
                    }
        
                    $diff1=date_diff($date1, $date3);
                    $pm=$diff1->format("%R%a");
            
                    if(date('Y-m-d', strtotime($v->DeficiencyCompDate))=='1970-01-01') {
                        $proxpref="<span title='PSC Def. rectify b4 arrival'>N/A</span>";
                    } else {
                        if($pm>0) {
                            $proxpref="<span  title='PSC Def. rectify b4 arrival'>Yes</span>";
                        } else {
                            $proxpref="<span style='color: red;' title='PSC Def. rectify b4 arrival'>No</span>";
                        }
                    }
        
        
                    if($v->Rating==1) {
                        $risk_rating="<span style='color: red;' title='Risk Rating'>".$v->Rating."</span>";
                    }else if($v->Rating==2) {
                        $risk_rating="<span style='color: orange;' title='Risk Rating'>".$v->Rating."</span>";
                    } else {
                        $risk_rating="<span title='Risk Rating'>".$v->Rating."</span>";
                    }
        
                    if($v->DetentionFlag=='Yes') {
                        $DetentionFlag="<span style='color: red;' title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                    }else{
                        $DetentionFlag="<span title='PSC detention last 12 months'>".$v->DetentionFlag."</span>";
                    }
                    if($imo==0) {
                        $check="<input type='checkbox' disabled>";
                    } else {
                        $check="<input type='checkbox' name='AuctionID[]' class='chk' value='".$row->ResponseID."'>";    
                    }
                    $CHTR="<a onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR </a>";
                    $QUOTE="<a onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."') title='view quote details'>QUOTE</a>";
                    $inhtml .='<tr>';
                    $inhtml .='<td>#</td>';
                    if($imo==0) {
                        $inhtml .='<td><b>'.$status.'</b></td>';
                        $inhtml .='<td><b>'.$row->EntityName.'</b></td>';
                    } else {
                        $inhtml .='<td>'.$status.'</td>';
                        $inhtml .='<td>'.$row->EntityName.'</td>';
                    }
                    if($model->InviteeCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='<td><b>'.$priority.'</b></td>';
                        } else {
                            $inhtml .='<td>'.$priority.'</td>';
                        }
                    }
                    if($imo==0) {
                        $inhtml .='<td><b>'.$row->ResponseID.'</b></td>';
                        $inhtml .='<td><b>'.$v->VesselName.'</b></td>';
                    } else {
                        $inhtml .='<td>'.$row->ResponseID.'</td>';
                        $inhtml .='<td>'.$v->VesselName.'</td>';
                    }
        
                    if($model->RatingStatus==1) {
                        if($imo==0) {
                            $inhtml .='<td>-</td>';
                        } else {
                            $inhtml .='<td>'.$risk_rating.'</td>';
                        }
                    }
                    if($model->FreightCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='<td><b>'.$frt->FreightRate.'</b></td>';
                        } else {
                            $inhtml .='<td>'.$frt->FreightRate.'</td>';
                        }
                    }
                    if($model->FIDDCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='<td><b>'.number_format($FreightInclDemDelays, 3).'</b></td>';
                        } else {
                            $inhtml .='<td>'.number_format($FreightInclDemDelays, 3).'</td>';
                        }
                    }
                    if($model->DemurrageCriteriaStatus==1) {
                        if($imo==0) {
                            $inhtml .='<td><b>'.$frt->Demurrage.'</b></td>';
                        } else {
                            $inhtml .='<td>'.$frt->Demurrage.'</td>';
                        }
                    }
                    if($imo==0) {
                        $inhtml .='<td><b>'.$crgo->Estimate_mt.'</b></td>';
                        $inhtml .='<td><b>'.$crgo->Estimate_Index_mt.'</b></td>';
                    } else {
                        $inhtml .='<td>'.$crgo->Estimate_mt.'</td>';
                        $inhtml .='<td>'.$crgo->Estimate_Index_mt.'</td>';
                    }
                    if($model->PSCDLYCS==1) {
                        if($imo==0) {
                            $inhtml .='<td>-</td>';
                        } else {
                            $inhtml .='<td>'.$DetentionFlag.'</td>';
                        }
                    }
                    if($model->PSCDPCS==1) {
                        if($imo==0) {
                            $inhtml .='<td>-</td>';
                        } else {
                            $inhtml .='<td>'.$v->Deficiency.'</td>';
                        }
                    }
                    if($model->PFLPPADCS==1) {
                        $inhtml .='<td>'.$pscdef.'</td>';
                    }
                    if($model->PSCDRPFLPACS==1) {
                        if($imo==0) {
                            $inhtml .='<td>-</td>';
                        } else {
                            $inhtml .='<td>'.$proxpref.'</td>';
                        }
                    }
                    if($model->IPOLYCS==1) {
                        $inhtml .='<td>NA</td>';
                    }
                    if($model->VLTCS==1) {
                        $inhtml .='<td>NA</td>';
                    }
        
                    if($imo==0) {
                        $inhtml .='", "-';
                        $inhtml .='<td>-</td>';
                    } else {
                        $inhtml .='<td>'.$rating.'</td>';
                    }
                    $inhtml .='<td>'.$CHTR.'&nbsp'.$QUOTE.'</td>';
                    $inhtml .='</tr>';
                    $RespID[]=$row->ResponseID;
                    $inhtmlLine[]=$inhtml;
                    $inhtml='';
                }
            }
        }
    }
    $RespIDUniq=array();
    $inhtmlLineNew=array();
    for($k=0;$k<count($RespID);$k++) {
        if (in_array($RespID[$k], $RespIDUniq)) {
        } else {
            $RespIDUniq[]=$RespID[$k];
        }
    }
    for($i=0;$i<count($RespIDUniq);$i++) {
        for($j=0;$j<count($RespID);$j++) {
            if($RespIDUniq[$i]==$RespID[$j]) {
                $inhtmlLineNew[]=$inhtmlLine[$j];
            }
        }
    }
    //print_r($inhtmlLineNew);die;
    $CargoCount=count($crgocnt);
    $daraArray=array('inhtmlLine'=>$inhtmlLineNew,'CargoCount'=>$CargoCount);
    echo json_encode($daraArray); 
}
    
    
public function getCommercialDataNew()
{
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    //$AuctionID='V17-L69-F06';
    $data=$this->cargo_quote_model->getResponseAssessmentNew();
        
    $responseids= array();
    foreach($data as $r) {
        array_push($responseids, $r->ResponseID);
    }
        
    $vesl=$this->cargo_quote_model->getVesselDataByAuctionID($AuctionID, $responseids);
    $vesseldata= array();
    $responseid='';
    foreach($vesl as $v) {
        if($responseid != $v->ResponseID) {
            $responseid=$v->ResponseID;
            array_push($vesseldata, $v);
        }else{
            continue;
        }
    } 
        
    $i=1;
    $crgocnt=$this->cargo_quote_model->getCargoCountByAuctionID($AuctionID);
    $CargoCount=count($crgocnt);
        
    foreach($crgocnt as $crgcnt) {
        $crgo=$this->cargo_quote_model->getCargoDataByAuctionIDNew($AuctionID, $crgcnt->LineNum);
        
        foreach($data as $row) {
            if($row->TentativeStatus==1) {
                $status='Tentative Acceptance';
            } else {
                if($row->ResponseStatus=='Submitted') {
                      $status=$row->ResponseStatus;
                    
                }else{
                    $status='In progress';
                    continue;
                }
            }
            foreach($vesseldata as $v) {
                if($row->ResponseID==$v->ResponseID) {
                    $cr=$this->cargo_quote_model->getCargoResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    $frt=$this->cargo_quote_model->getFreightResponseByResponseID($row->ResponseID, $crgcnt->LineNum);
                    
                    $DemLP=($frt->FreightRate*$crgo->CargoQtyMT);
                    $DelayLP=$cr->ExpectedLpDelayDay+($cr->ExpectedLpDelayHour/24);
                    $DelayDP=$cr->ExpectedDpDelayDay+($cr->ExpectedDpDelayHour/24);
                    $DemCostLP=$frt->Demurrage*$DelayLP;
                    $DemCostDP=$frt->Demurrage*$DelayDP;
                    $TotalFrtInclDemDelays=$DemLP+$DemCostLP+$DemCostDP;
                    $DemCost=(($DemCostLP+$DemCostDP)/$crgo->CargoQtyMT);
                    $FreightInclDemDelays=round(($TotalFrtInclDemDelays/$crgo->CargoQtyMT), 2);
                    $html='';
                    $html .='<tr>';
                    $html .='<td>'.$status.'</td>';
                    $html .='<td>'.$row->EntityName.'</td>';
                    $html .='<td>'.$row->ResponseID.'</td>';
                    $html .='<td>'.$v->VesselName.'</td>';
                    $html .='<td>'.number_format($crgo->Estimate_mt, 4).'</td>';
                    $html .='<td>'.number_format($crgo->Estimate_Index_mt, 4).'</td>';
                    $html .='<td>'.number_format($frt->FreightRate, 4).'</td>';
                    $html .='<td>'.number_format($crgo->CargoQtyMT).'</td>';
                    $html .='<td>'.number_format($DemLP).'</td>';
                    $html .='<td>'.number_format($frt->Demurrage, 2).'</td>';
                    $html .='<td>'.number_format($DelayLP, 4).'</td>';
                    $html .='<td>'.number_format($DelayDP, 4).'</td>';
                    $html .='<td>'.number_format($DemCostLP, 2).'</td>';
                    $html .='<td>'.number_format($DemCostDP, 2).'</td>';
                    $html .='<td>'.number_format($TotalFrtInclDemDelays).'</td>';
                    $html .='<td>'.number_format($DemCost, 4).'</td>';
                    $html .='<td>'.number_format($FreightInclDemDelays, 4).'</td>';
                    $html .='</tr>';
                    $inhtmlLine[]=$html;
                    $RespID[]=$row->ResponseID;
                }
            }
        }
    }
        
    $RespIDUniq=array();
    $inhtmlLineNew=array();
    for($k=0;$k<count($RespID);$k++) {
        if (in_array($RespID[$k], $RespIDUniq)) {
        } else {
            $RespIDUniq[]=$RespID[$k];
        }
    }
    for($i=0;$i<count($RespIDUniq);$i++) {
        for($j=0;$j<count($RespID);$j++) {
            if($RespIDUniq[$i]==$RespID[$j]) {
                $inhtmlLineNew[]=$inhtmlLine[$j];
            }
        }
    }
    $daraArray=array('inhtmlLine'=>$inhtmlLineNew,'CargoCount'=>$CargoCount);
    echo json_encode($daraArray);
}
    
public function getChatByResponseidByArguments($ResponseID,$LineNum)
{
    $data=$this->cargo_quote_model->getChateByResponseIDByArguments($ResponseID, $LineNum);
    $RecordArr=$this->cargo_quote_model->getInviteeIDByResponseID($ResponseID);
    $InviteeID=$RecordArr->EntityID;
    $html1='';
    $html2='';
    $html3='';
    $html4='';
    $html='';
    foreach($data as $row) {
        if($row->Type=='Vessel') { 
            if($row->AdUs=='admin') {
                $html1.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
            } else {
                $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                if($InviteeID==$EntityID) {
                     $html1.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
                     //invitee
                } else {
                    $html1.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
                    //record owner
                }
            }
        }
    }
    if($html1) {
        $html .='<br><h4><b>Vessel</b></h4><br>';
        $html .=$html1;
    }
        
    foreach($data as $row){
        if($row->Type=='Freight') {
            if($row->AdUs=='admin') {
                $html2.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
            } else {
                $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                if($InviteeID==$EntityID) {
                     $html2.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';    
                } else {
                    $html2.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
                }
            }
        }
    }
    if($html2) {
        $html .='<hr><br>';
        $html .='<h4><b>Freight</b></h4><br>';
        $html .=$html2;
    }
        
    foreach($data as $row){
        if($row->Type=='CargoPort') {
            if($row->AdUs=='admin') {
                $html3.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
            } else {
                $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                if($InviteeID==$EntityID) {
                     $html3.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';    
                } else {
                    $html3.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp; <span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
                }
            }
        }
    }
    if($html3) {
        $html .='<hr><br>';
        $html .='<h4><b>Cargo n Ports</b></h4><br>';
        $html .=$html3;
    }
        
    foreach($data as $row)    {
        if($row->Type=='Terms') {
            if($row->AdUs=='admin') {
                $html4.='<span>'.$row->Invname.'</span>&nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
            } else {
                $EntityID=$this->cargo_quote_model->getEntityIDByUserID($row->UserID);
                if($InviteeID==$EntityID) {
                     $html4.='<span>'.$row->Invname.'</span> &nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';
                } else {
                    $html4.='<span>'.$row->Invname.'</span> &nbsp;&nbsp;&nbsp;<span>'.date('d-m-Y H:i:s', strtotime($row->Chat_time)).'h</span> <br><span>'.$row->Chat.'</span><br>';    
                }
            }
        }
    }
    if($html4) {
        $html .='<hr><br>';
        $html .='<h4><b>Terms</b></h4><br>';
        $html .=$html4;
    }
        
    return $html;
}
    
public function getQuoteBusinessProcess()
{
    $ResponseID=$this->input->post('ResponseID');
    $UBusinesses=$this->cargo_quote_model->getQuoteBusinessProcess($ResponseID);
    //print_r($UBusinesses); die;
    $i=1;
    $ii=1;
    $html='';
    $business_flg=0;
    foreach($UBusinesses as $row){
        $data=$this->cargo_quote_model->getQuoteBusinessProcessByBPID($ResponseID, $row->BPID, $row->LineNum);
        //print_r($data); die;
        $name_of_process='';
        $LineNum=1;
        $i=1;
        if($data[0]->name_of_process==1) {
            $name_of_process='Technical Vetting';
        } else if($data[0]->name_of_process==2) {
            $name_of_process='Business vetting approval';
        } else if($data[0]->name_of_process==3) {
            $name_of_process='Counter party risk assessment';
        } else if($data[0]->name_of_process==4) {
            $name_of_process='Compliance risk assessment';
        } else if($data[0]->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
                $freightDetails=$this->cargo_quote_model->getLatestFreightQuoteDetailsByFreightResponseID($data[0]->FreightResponseID);
                $business_flg=$this->cargo_quote_model->checkBusinessAuthorisationEqualFreightQuote($ResponseID, $row->LineNum);
                $LineNum=$freightDetails->LineNum;
                $latestFreightResponseID=$freightDetails->FreightResponseID;
        } else if($data[0]->name_of_process==6) {
            $name_of_process='Charter party final signature';
        } else if($data[0]->name_of_process==7) {
            $name_of_process='Fixture note final signature';
        } else if($data[0]->name_of_process==8) {
            $name_of_process='Approval for quotes authorization (by record owner)';
        } else if($data[0]->name_of_process==9) {
            $name_of_process='C/P on subjects (charterer)';
        } else if($data[0]->name_of_process==10) {
            $name_of_process='C/P on subjects (Shipowner/Broker)';
        }
            
            $html .='<br/><div class="row"><div class="col-lg-12"><header><div class="icons"><a id="plus'.$ii.'" class="cls_plus" onclick="expand_collaps_business(0,'.$ii.');" title="'.$name_of_process.'" style="display: inline;"><i class="fa fa-plus fafa_cls"></i></a><a id="minus'.$ii.'" class="cls_minus"  onclick="expand_collaps_business(1,'.$ii.');" style="display: none;" title="'.$name_of_process.'"><i class="fa fa-minus fafa_cls"></i></a></div><h5>'.$name_of_process.' (Seq '.$row->process_flow_sequence.') Quote '.$LineNum.'</h5></header><div id="business_main_div'.$ii.'" class="cls_business" style="display: none;"><table class="table table-striped table-hover table-bordered" style="font-size: 14px;"><thead class="dark"><tr><th class="padd_th">#</th><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">Master ID</th class="padd_th"><th class="padd_th">TID</th><th class="padd_th">Record Owner</th><th class="padd_th">Invitee</th><th class="padd_th">Freight Type</th><th class="padd_th">Freight Value</th><th class="padd_th">View Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved By</th><th class="padd_th">View Docs</th><th class="padd_th">Action</th></tr></thead><tbody>';
            
        foreach($data as $bp){
            $doc_view='-';
            $row_flg=$this->cargo_quote_model->checkQuoteAuthorizationAttachedFiles($bp->QBPID);
            if($row_flg) {
                    $doc_view='<a href="javascript: void(0);" onclick="getDocumentFileData('.$bp->QBPID.')" >View</a>';
            }
            $ApprovedBy='No';
            $ApprovedByUser='';
            if($bp->ApproveStatus==1) {
                $ApprovedBy='Yes';
                $ApprovedByUser=$bp->FirstName.' '.$bp->LastName;
            }
            if($business_flg==1) {
                $action="<a href='javascript: void(0);' onclick=editBusinessProcess('".$latestFreightResponseID.'_'.$bp->name_of_process."',1) title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            } else {
                $action="<a href='javascript: void(0);' onclick=editBusinessProcess('".$latestFreightResponseID.'_'.$bp->name_of_process."',0) title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
            }
            $EstimateTotalValue='-';
            $EstimateType='-';
            if($bp->EstimateLumpsumFlg==1) {
                $EstimateType='Rate $/mt';
                $EstimateTotalValue=number_format($bp->EstimateTotalValue, 2).' '.$bp->curCode;
            } else if($bp->EstimateLumpsumFlg==2) {
                $EstimateType='Lumpsum';
                $EstimateTotalValue=number_format($bp->FreightLumpsum, 2).' '.$bp->curCode;
            }
            if($bp->ViewChanges=='') {
                $view='-';
            }else {
                $view='<a href="javascript: void(0);" onclick="getChangeData('.$bp->QBPID.')" >View</a>';
            }
            $html .='<tr>';
            $html .='<td>'.$i.'</td>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($bp->UserDate)).'</td>';
            $html .='<td>'.$bp->Version.'</td>';
            $html .='<td>'.$bp->MasterID.'</td>';
            $html .='<td>'.$bp->TID.'</td>';
            $html .='<td>'.$bp->OwnerEntityName.'</td>';
            $html .='<td>'.$bp->InviteeEntityName.'</td>';
            $html .='<td>'.$EstimateType.'</td>';
            $html .='<td>'.$EstimateTotalValue.'</td>';
            $html .='<td>'.$view.'</td>';
            $html .='<td>'.$ApprovedBy.'</td>';
            $html .='<td>'.$ApprovedByUser.'</td>';
            $html .='<td>'.$doc_view.'</td>';
            $html .='<td>'.$action.'</td>';
            $html .='</tr>';
            $i++;
        }
            
            $html .='</tbody></table></div></div></div>';
            $ii++;
            
    }
    echo $html;
        
}
    
public function getQuoteQuthorizationBrokerByFreightResponseID()
{
    $AuctionID=$this->input->post('AuctionID');
    $FreightResponseID=$this->input->post('FreightResponseID');
    $data['FreightDetails']=$this->cargo_quote_model->getFreightByFreightResponseID($FreightResponseID);
    $data['CargoDetails']=$this->cargo_quote_model->getFreightEstimateByAuctionID($AuctionID, $data['FreightDetails']->LineNum);
    $data['QuoteBusiness']=$this->cargo_quote_model->getQuoteAuthonticationBusinessProcessDetails($data['FreightDetails']->ResponseID, $data['FreightDetails']->LineNum);
    //print_r($data); die;
    echo json_encode($data);
}
    
public function getUserQuoteAuthorizationDetails()
{
    $UserID=$this->input->post('UserID');
    $AuctionID=$this->input->post('AuctionID');
    $authFlg=$this->cargo_quote_model->getUserQuoteAuthorizationDetails($AuctionID, $UserID);
    if($authFlg) {
        $data['auth_flg']=1;
        $data['authorize_level']=$this->cargo_quote_model->getUserQuoteAuthorizationPermissionLevel($UserID);
    } else {
        $data['auth_flg']=0;
    }
    echo json_encode($data);
}
    
public function saveQuoteAuthorizationDetails()
{
    $QBPID=$this->cargo_quote_model->saveQuoteAuthorizationDetails();
    //echo $QBPID; die;
    if($QBPID) {
        $this->cargo_quote_model->saveQuoteAuthorizationAttachedFiles($QBPID);
        echo 1;
    } else {
        echo 0;
    }
}
    
public function getViewChangesByQBPID()
{
    $QBPID=$this->input->post('QBPID');
    $data['AuthData']=$this->cargo_quote_model->getViewChangesByQBPID($QBPID);
    echo json_encode($data); 
        
}
    
public function getQuoteAuthorizeAttachedFileByQBPID()
{
    $QBPID=$this->input->post('QBPID');
    $data['FileData']=$this->cargo_quote_model->getQuoteAuthorizeAttachedFileByQBPID($QBPID);
    echo json_encode($data); 
        
}
    
public function viewQuoteAuthorizeAttachedFileByQAFID()
{
    $file_row=$this->cargo_quote_model->viewQuoteAuthorizeAttachedFileByQAFID();
        
    $filename=$file_row->FileName;
        
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
    
public function getResponseIDRecordStatus()
{
    $ResponseID=$this->input->post('ResponseID');
    $data['record']=$this->cargo_quote_model->getResponseIDRecordStatus($ResponseID);
    echo json_encode($data); 
}
    
    //--------------------------for chat---------------------------------    
public function getChatRecord()
{
    $RecordOwner=$this->input->get('RecordOwner');
    $UserID=$this->input->get('UserID');
    $data=$this->cargo_quote_model->getChatRecord();
        
    $html='';
    $inhtml='';
    $tempMasterID='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
            
        $FreightRate='';
        $FreightRecords=$this->cargo_quote_model->getLatestFreightQuotes($row->ResponseID);
        foreach($FreightRecords as $fr){
            if($fr->FreightRate) {
                $FreightRate =$FreightRate+$fr->FreightRate;
            }
        }
            
        $VesselName='';
        $VesselName=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
            
        if($tempMasterID != $row->AuctionID) {
            if($RecordOwner == $row->OwnerID) {
                if($row->ResponseStatus == 'Closed') {
                    $MasterID=$row->AuctionID;    
                } else {
                    $MasterID=$row->AuctionID;
                }
            } else {
                         $MasterID=$row->AuctionID;
            }
            $tempMasterID = $row->AuctionID;
        } else {
            $MasterID='';
        }
            
        $cargo_data=$this->cargo_quote_model->getResponseCargoDataResponseIDWise($row->ResponseID);
        $Line_Num='';
        $LoadPort='';
        $Dis_Port='';
        $LaycanFrom='';
        $LaycanTo='';
        $btn='';
        foreach($cargo_data as $row1) {
            if($Line_Num !=$row1->LineNum) {
                $LoadPort .=$row1->LpPortName.' || ';
                $LaycanFrom .=date('d-m-Y H:i:s', strtotime($row1->LpLaycanStartDate)).' || ';
                $LaycanTo .=date('d-m-Y H:i:s', strtotime($row1->LpLaycanEndDate)).' || ';
                $DisportRslt=$this->cargo_quote_model->getResponseDisportsByResponseCargoID($row1->ResponseCargoID);
                $Disports='';
                if(count($DisportRslt)> 0) {
                    foreach($DisportRslt as $dr){
                             $Disports .=$dr->dspPortName.', ';
                    }
                } else {
                    $Disports=$row->DpPortName;
                }
                $Disports=trim($Disports, ", ");
                $Dis_Port .=$Disports.' || ';
                $record_owner=str_replace(" ", "_", $row->OwnerEntityName);
                $invitee_name=str_replace(" ", "_", $row->EntityName);
                $LpPortName=str_replace(" ", "_", $row1->LpPortName);
                $LpDisports=str_replace(" ", "_", $Disports);
                $LCFrom=str_replace(" ", "_", date('d-m-Y H:i:s', strtotime($row1->LpLaycanStartDate)));
                $LCTo=str_replace(" ", "_", date('d-m-Y H:i:s', strtotime($row1->LpLaycanEndDate)));
                $btn .="<button type='button' class='btn btn-default' onclick=make_chat_dialog_box(".$row->ResponseID.",'".$row->AuctionID."','".$record_owner."','".$invitee_name."','".$row1->LineNum."','".$LpPortName."','".$LpDisports."','".$LCFrom."','".$LCTo."')>Start Chat</button>";
            }
            $Line_Num =$row1->LineNum;
        }
        
        $msg_cnt=$this->cargo_quote_model->count_unseen_message($row->ResponseID, $UserID);
        if($msg_cnt>0) {
            $msg_cnt_show="<span class='label label-success' style='margin: 7px;' id='".$row->ResponseID."'>".$msg_cnt."</span>";
        } else {
            $msg_cnt_show="<span class='label label-success' style='margin: 7px;' id='".$row->ResponseID."'></span>";    
        }
        
        $record_owner=str_replace(" ", "_", $row->OwnerEntityName);
        $invitee_name=str_replace(" ", "_", $row->EntityName);
        //$btn="<button type='button' class='btn btn-default' onclick=make_chat_dialog_box(".$row->ResponseID.",'".$row->AuctionID."','".$record_owner."','".$invitee_name."')>Start Chat</button>";
        
        $LoadPort=trim($LoadPort, ' || ');
        $Dis_Port=trim($Dis_Port, ' || ');
        $LaycanFrom=trim($LaycanFrom, ' || ');
        $LaycanTo=trim($LaycanTo, ' || ');
        $inhtml .='["'.$i.'","'.$MasterID.'","'.$row->OwnerEntityName.'","'.$row->ResponseID.$msg_cnt_show.'","'.$row->EntityName.'","'.$LoadPort.'","'.$Dis_Port.'","'.$LaycanFrom.'","'.$LaycanTo.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'", "'.$FreightRate.'","'.$VesselName->VesselName.'","'.$btn.'"],';
        $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function saveChatMessage()
{
    $data=$this->cargo_quote_model->saveChatMessage();
    echo $data;
}
    
public function getChatMessage()
{
    $data=$this->cargo_quote_model->getChatMessage();
    echo json_encode($data);
}
    
    //--------------------------for mobile chat---------------------------------    
public function getMobileChatRecord()
{
    $RecordOwner=$this->input->get('RecordOwner');
    $UserID=$this->input->get('UserID');
    $data=$this->cargo_quote_model->getChatRecord();
    //print_r($data);die;
    $html='';
    $inhtml='';
    $tempMasterID='';
    $i=1;
    $row_count=0;
    $bucket="hig-sam";
    include_once APPPATH.'third_party/S3.php';
    if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
    }
    if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
    }
    $s3 = new S3(awsAccessKey, awsSecretKey);
            
    foreach($data as $row) {
        if($row_count==10) {
            break;
        }
            
        $cargo_data=$this->cargo_quote_model->getResponseCargoDataResponseIDWise($row->ResponseID);
        $Line_Num='';
        $LoadPort='';
        $Dis_Port='';
        $LaycanFrom='';
        $LaycanTo='';
        foreach($cargo_data as $row1) {
            if($Line_Num !=$row1->LineNum) {
                 $msg_cnt_show="";
                 $row_count++;
                 $LoadPort .=$row1->LpPortName.' || ';
                 $LaycanFrom .=date('d-m-Y H:i:s', strtotime($row1->LpLaycanStartDate)).' || ';
                 $LaycanTo .=date('d-m-Y H:i:s', strtotime($row1->LpLaycanEndDate)).' || ';
                 $DisportRslt=$this->cargo_quote_model->getResponseDisportsByResponseCargoID($row1->ResponseCargoID);
                 $Disports='';
                if(count($DisportRslt)> 0) {
                    foreach($DisportRslt as $dr){
                              $Disports .=$dr->dspPortName.', ';
                    }
                } else {
                    $Disports=$row->DpPortName;
                }
                $Disports=trim($Disports, ", ");
                $Dis_Port .=$Disports.' || ';
                $record_owner=str_replace(" ", "_", $row->OwnerEntityName);
                $invitee_name=str_replace(" ", "_", $row->EntityName);
                $LpPortName=str_replace(" ", "_", $row1->LpPortName);
                $LpDisports=str_replace(" ", "_", $Disports);
                $LCFrom=str_replace(" ", "_", date('d-m-Y', strtotime($row1->LpLaycanStartDate)));
                $LCTo=str_replace(" ", "_", date('d-m-Y', strtotime($row1->LpLaycanEndDate)));
                $msg_cnt=$this->cargo_quote_model->count_unseen_message_mobile($row->ResponseID, $UserID, $row1->LineNum);
                $last_message=$this->cargo_quote_model->getLasteDateTime($row->ResponseID, $row1->LineNum);
                $ucnt=$this->cargo_quote_model->getMessageUserCount($row->ResponseID, $UserID, $row1->LineNum);
                $ldt='';
                $UName='';
                if($last_message) {
                    $ldt=date('d-m-Y H:i:s', strtotime($last_message->Timestamp));
                    $ldt="<em style='float: right;' class='UTS".$row->ResponseID.$row1->LineNum."'>".$ldt."</em>";
                
                    $UName="<span style='float: right;' class='UN".$row->ResponseID.$row1->LineNum."'>".$last_message->FirstName." ".$last_message->LastName."</span>";
                    //if($last_message->UserID!=$UserID) { }
                } else {
                    $ldt="<em style='float: right;' class='UTS".$row->ResponseID.$row1->LineNum."'></em>";
                    $UName="<span style='float: right;' class='UN".$row->ResponseID.$row1->LineNum."'></span>";
                }
                $FreightRecord=$this->cargo_quote_model->getLatestFreightQuotesMobile($row->ResponseID, $row1->LineNum);
                $SIRFlg=0;
                if($RecordOwner == $row->OwnerID && $FreightRecord->SRFlg==1) {
                    $msg_cnt_show="<span class='badge-round ".$row->ResponseID.$row1->LineNum."'>N</span>";
                    $SIRFlg=1;
                } else if($RecordOwner != $row->OwnerID && $FreightRecord->SIFlg==1) {
                    $msg_cnt_show="<span class='badge-round ".$row->ResponseID.$row1->LineNum."'>N</span>";
                    $SIRFlg=2;
                }
            
                if($msg_cnt>0) {
                    $msg_cnt_show .="<span class='label label-success' id='".$row->ResponseID.$row1->LineNum."'>".$msg_cnt."</span>";
                } else {
                    $msg_cnt_show .="<span class='label label-success' id='".$row->ResponseID.$row1->LineNum."'></span>";    
                }
            
            
                $record_owner=str_replace(" ", "_", $row->OwnerEntityName);
                $invitee_name=str_replace(" ", "_", $row->EntityName);
                if($ucnt==1) {
                    $uimg=$this->cargo_quote_model->getUserImage($row->ResponseID, $UserID, $row1->LineNum);
                    if($uimg) {
                        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$uimg, 3600);
                        $img="<img class='img-sm rounded-circle' src='".$url."'>";
                    } else {
                        $img="<img class='img-sm rounded-circle' src='img/no_pic.jpg'>";    
                    }
                } else if($ucnt>1) {
                    $img="<img class='img-sm rounded-circle' src='img/multiple.png'>";
                } else {
                    $img="<img class='img-sm rounded-circle' src='img/no_pic.jpg'>";
                }
                $html .="<tr><td style='width: 20px;'>".$img."</td><td onclick=make_chat_dialog_box(".$row->ResponseID.",'".$row->AuctionID."','".$record_owner."','".$invitee_name."','".$row1->LineNum."','".$LpPortName."','".$LpDisports."','".$LCFrom."','".$LCTo."','".$SIRFlg."')>".$row->EntityName."<em style='float: right;'>".$ldt."</em> <br> ".$row->ResponseID." || Cargo ".$row1->LineNum.$UName."<br>L/C : ".date('d-m-Y', strtotime($row1->LpLaycanStartDate))." - ".date('d-m-Y', strtotime($row1->LpLaycanEndDate))."<br>LP : ".$row1->LpPortName."<br>DP : ".$Disports.$msg_cnt_show."</td></tr>";
            }
            $Line_Num =$row1->LineNum;
        }
        
        
        $i++; 
    }
    //$html .=trim($inhtml,",");    
    //$html .='] }';
    echo $html;
}
    
public function getUnseenMessageByRecordOwnerMobile()
{
    $data=$this->cargo_quote_model->getUnseenMessageByRecordOwnerMobile();
    $UserID=$this->input->post('UserID');
    $cnt_arr=array();
    $dt_arr=array();
    $un_arr=array();
    foreach($data as $row) {
        $cnt=$this->cargo_quote_model->count_unseen_message_mobile($row->ResponseID, $UserID, $row->LineNum);
        $last_message=$this->cargo_quote_model->getLasteDateTime($row->ResponseID, $row->LineNum);
        if($cnt>0) {
            $cnt_arr[$row->ResponseID.$row->LineNum]=$cnt;
        } else {
            $cnt_arr[$row->ResponseID.$row->LineNum]='';
        }
            
        if($last_message) {
            $ldt=date('d-m-Y H:i:s', strtotime($last_message->Timestamp));
            $dt_arr[$row->ResponseID.$row->LineNum]=$ldt;
            $un_arr[$row->ResponseID.$row->LineNum]=$last_message->FirstName.' '.$last_message->LastName;
        } else {
            $dt_arr[$row->ResponseID.$row->LineNum]='';
            $un_arr[$row->ResponseID.$row->LineNum]='';
        }
    }
    $edata[0]=$cnt_arr;
    $edata[1]=$dt_arr;
    $edata[2]=$un_arr;
    echo json_encode($edata);
}
    
public function getResponseNew()
{
    $this->load->model('cargo_model', '', true);
    $UserID=$this->input->get('UserID');
    $RecordOwner=$this->input->get('RecordOwner');
    $QuoteType=$this->input->get('QuoteType');
    $CargoID=$this->input->get('CargoID');
    $data=$this->cargo_quote_model->getResponseNew();
    //print_r($RecordOwner); die;
    $html='';
    $inhtml='';
    $i=1;
    $html='{ "aaData": [';
        
    foreach($data as $row){
        if($QuoteType==3 && $RecordOwner == $row->OwnerEntityID) {
            continue;
        }
        if($UserID == 1) {
            if($QuoteType == 3) {
                  $freight_rows=$this->cargo_quote_model->getEntityIsInviteeOrNot($row->AuctionID, $RecordOwner);
                if(count($freight_rows) > 0) {
                    // nothing
                } else {
                    continue;
                }
            }
        } else {
            if($RecordOwner != $row->OwnerEntityID && $QuoteType != 2) {
                $freight_rows=$this->cargo_quote_model->getEntityIsInviteeOrNot($row->AuctionID, $RecordOwner);
                if(count($freight_rows) > 0) {
                    // nothing
                } else {
                     continue;
                }
            }
        }
            
            $QuoteDetails=$this->cargo_quote_model->getResposeQuoteDetails($row->AuctionID);
            $CargoResult=$this->cargo_quote_model->getAuctionCargoDetails($row->AuctionID);
                        
        if($QuoteDetails->ResponseStatus=='Closed') {
            $ResponseStatus='Closed';
        } else {
            $ResponseStatus='In Progress';
        }
        if($UserID==1) {
                $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."' >".$row->AuctionID."</a> (".$row->EntityName.")";
        } else {
            if($RecordOwner == $row->OwnerEntityID) {
                if($QuoteDetails->ResponseStatus == 'Closed') {
                    $MasterID=$row->AuctionID.' ('.$row->EntityName.')';    
                } else {
                    $MasterID="<a href='responseAssesment.html?AuctionID=".$row->AuctionID."' >".$row->AuctionID."</a> (".$row->EntityName.")";
                }
            } else {
                $MasterID=$row->AuctionID.' ('.$row->EntityName.')';
            }
        }
            $cargo='';
            $loadport='';
            $start_date='';
            $end_date='';
            $disports='';
            $cargoArr=array();
            $quote_graph='';
            $ijk=1;
        foreach($CargoResult as $c){
            array_push($cargoArr, $c->SelectFrom);
            $DisportResult=$this->cargo_model->getDisportRecordsDataByCargoID($c->CargoID);
            $cargo .= ($cargo =='' ? $c->CargoCode : ', '.$c->CargoCode);
            $loadport .= ($loadport =='' ? $c->PortName : ', '.$c->PortName);
            $start_date .= ($start_date =='' ? date('d-m-Y H:i:s', strtotime($c->LpLaycanStartDate)) : ', '.date('d-m-Y H:i:s', strtotime($c->LpLaycanStartDate)));
            $end_date .= ($end_date =='' ? date('d-m-Y H:i:s', strtotime($c->LpLaycanEndDate)) : ', '.date('d-m-Y H:i:s', strtotime($c->LpLaycanEndDate)));
            foreach($DisportResult as $d){
                $disports .= ($disports =='' ? $d->DisportDescription : ', '.$d->DisportDescription);
            }
            if($RecordOwner == $row->OwnerEntityID) {
                $quote_graph .="<a href='view_quote_graph.html?AuctionID=".$row->AuctionID."&LineNum=".$c->LineNum."' title='View quote graph cargo ".$ijk."'><i class='fa fa-rss-square fa_clone'></i></a>&nbsp;&nbsp;";
                $ijk++;
            }
        }
        if($CargoID) {
            if(! in_array($CargoID, $cargoArr)) {
                continue;
            }
        }
            
            $form_view="<a href='InviteeResponseTable.html?MasterID=".$row->AuctionID."' title='Click here to view invitee form'><i class='fa fa-share-square fa_edit'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($QuoteDetails->ReleaseDate)).'","'.$ResponseStatus.'","'.$MasterID.'", "'.$cargo.'", "'.$loadport.'", "'.$start_date.'", "'.$end_date.'", "'.$disports.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'","'.$form_view.'&nbsp;&nbsp;'.$quote_graph.'"],';
            $i++; 
    }
        
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getResponseNewByAuctioniD()
{
    $UserID=$this->input->get('UserID');
    $RecordOwner=$this->input->get('RecordOwner');
    $MasterID=$this->input->get('MasterID');
    $data=$this->cargo_quote_model->getResponseNewByAuctioniD($MasterID);
        
    $html='';
    $inhtml='';
    $tempMasterID='';
    $i=1;
    $html='{ "aaData": [';
    foreach($data as $row) {
            
        $VesselName='';
        $VesselName=$this->cargo_quote_model->getLatestVesselName($row->ResponseID);
        if($vesselAutocomplete) {
            if($vesselAutocomplete != $VesselName->VesselName) {
                continue;
            }
        }
            
        if($row->ResponseStatus=='Inprogress') {
            $ResponseStatus='In Progress';
        } else {
            $ResponseStatus=$row->ResponseStatus;
        }
            $bp_flg=0;
        if($RecordOwner==$row->EntityID) {
            $InviteeRecord=$this->cargo_quote_model->getAuctionInviteePrimeRole($row->AuctionID, $row->EntityID);
            $QuoteBP=$this->cargo_quote_model->getQuoteInviteeBusinessProcess($row->ResponseID);
            if($InviteeRecord->InviteeRole==6 && count($QuoteBP) > 0) {
                $bp_flg=1;
            }
        }
        if($row->ResponseStatus == 'Closed') {
            if($RecordOwner==1) {
                $invitee_id="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
            } else if($RecordOwner == $row->OwnerID || $RecordOwner == $row->EntityID) {
                $invitee_id=$row->ResponseID;
            } else {
                continue;
            }
        } else if($RecordOwner == $row->OwnerID || $RecordOwner==1) {
            $invitee_id="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
        } else if($RecordOwner == $row->EntityID) {
                $InvUsers=explode(",", $row->InvUserID);
            if(in_array($UserID, $InvUsers)) {
                $invitee_id="<a href='auctionResponse.html?respoanse=".$row->ResponseID."&bp_flg=".$bp_flg."' >".$row->ResponseID."</a>";
            } else {
                $invitee_id=$row->ResponseID;
            }
        } else {
            continue;
        }
            
            $FreightRate='';
            $FreightRecords=$this->cargo_quote_model->getLatestFreightQuotes($row->ResponseID);
            $QUOTE='';
            $cnt=1;
        foreach($FreightRecords as $fr){
            $QUOTE .="<a href='javascript: void(0);' onclick=getQuoteDetails(".$row->ResponseID.",'".$row->AuctionID."','".$fr->LineNum."') title='view quote details'>QT$cnt</a>&nbsp;";
            if($fr->FreightRate) {
                    $FreightRate =$FreightRate+$fr->FreightRate;
            }
            $cnt++;
        }
            
            $flag=$this->cargo_quote_model->checkChat($row->ResponseID);
        if($flag) {
            $NewChatsResult=$this->cargo_quote_model->getNewChatsFlagByResponseID1($row->ResponseID);
            $new_flg=0;
            foreach($NewChatsResult as $nw){
                if($RecordOwner == $row->OwnerID) {
                    if($nw->VesselOwnerFlag==1 || $nw->FreightOwnerFlag==1 || $nw->CargoPortOwnerFlag==1 || $nw->TermOwnerFlag==1) {
                        $new_flg=1;
                    }
                } else {
                    if($nw->VesselInviteeFlag==1 || $nw->FreightInviteeFlag==1 || $nw->CargoPortInviteeFlag==1 || $nw->TermInviteeFlag==1) {
                        $new_flg=1;
                    }
                }
                    
            }
            if($new_flg==1) {
                if($RecordOwner == $row->OwnerID) {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',1) title='view chat details'>View &nbsp;".$newbadge."</a>";
                } else {
                    $newbadge="<span class='badge' style='background-color: orangered;' id='AllMsg".$row->ResponseID."'>N</span>";
                    $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',2) title='view chat details'>View &nbsp;".$newbadge."</a>";
                }
                    
            } else {
                $view="<a href='javascript: void(0);' onclick=getChatDetails(".$row->ResponseID.",'".$row->AuctionID."',0) title='view chat details'>View</a>";
            }
                
        } else {
            $view="No";
        }
            
            
            
            $CHTR="<a href='javascript: void(0);' onclick=getcharterDetails(".$row->ResponseID.",'".$row->AuctionID."') title='View charter details'>CHTR TERMS</a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->ReleaseDate)).'","'.$ResponseStatus.'","'.$invitee_id.'","'.$row->EntityName.'","'.date('d-m-Y H:i:s', strtotime($row->AuctionCeases)).'", "'.$FreightRate.'", "'.$VesselName->VesselName.'", "'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'", "'.$view.'","'.$CHTR.'","'.$QUOTE.'"],';
            $i++; 
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
    //---------------------response quote graph-------------
    
public function getResponseQuoteForGraph()
{
    $RespID=$this->cargo_quote_model->getResponseIDByAuctionID();
    $html='{ "aaData": [';
    $inhtml ='';
    $i=1;
    foreach($RespID as $RID) {
        $data=$this->cargo_quote_model->getResponseQuoteForGraph($RID->ResponseID);
        $VesselName=$this->cargo_quote_model->getLatestVesselName($RID->ResponseID);
        $EntityName=$RID->EntityName;
        $VesselNameShow=$VesselName->VesselName;
        $chk_cnt=0;
        foreach($data as $row){
            $chk_cnt++;
            if($chk_cnt==1) {
                continue;                
            }
            $FVersion=$this->cargo_quote_model->gerFirstVersion($row->ResponseID);
            $date1 = strtotime($FVersion->UserDate);  
            $date2 = strtotime($row->UserDate);
            $diff = abs($date2 - $date1); 
            $years = floor($diff / (365*60*60*24));
            $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
            $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24)); 
            $hours = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24) / (60*60));
            $minutes = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60);
            $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minutes*60));
            $eff='';
            if($years>0) {
                $eff  .=$years.' Y ';
            }
            if($months>0) {
                $eff  .=$months.' M ';
            }
            if($days>0) {
                $eff  .=$days.' D ';
            }
            if($hours>0) {
                $eff  .=$hours.' Hr ';
            }
            if($minutes>0) {
                $eff  .=$minutes.' Min ';
            }
            if($seconds>0) {
                $eff  .=$seconds.' Sec ';
            }
            if($eff=='') {
                $eff='-';
            }
            //$eff=$years.' years '.$months.' months '.$days.' days '.$hours.' hours '.$minutes.' minutes '.$seconds.' seconds';
            $inhtml .='["'.$i.'","'.$EntityName.'","'.$VesselNameShow.'","'.$row->ResponseID.'","'.$row->FreightRate.'","'.date('d-M-Y', strtotime($row->UserDate)).'","'.date('d-M-Y H:i:s', strtotime($FVersion->UserDate)).'","'.$eff.'"],';
            $i++;
            $EntityName='';
            $VesselNameShow='';
        }
    }
    $html .=trim($inhtml, ",");    
    $html .='] }';
    echo $html;
}
    
public function getResponseQuoteForGraphDisplay()
{
    $RespID=$this->cargo_quote_model->getResponseIDByAuctionID();
    $arr=array();
    $arr_res=array();
    $arr_frt=array();
    foreach($RespID as $RID) {
        $data=$this->cargo_quote_model->getResponseQuoteForGraphDisplay($RID->ResponseID);
        if(count($data)>0) {
            $arr[]=$data;
        }
    }
    echo json_encode($arr);
}
    
    
public function getChangeStatus()
{
    $data=$this->cargo_quote_model->getChangeStatus();
    echo count($data);
}
    
public function get_quote_html_details1()
{
    $this->load->model('cargo_model', '', true);
    $ResponseID=$this->input->post('InviteeID');
    $data1=$this->cargo_quote_model->get_quote_html_details();
    $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data1[0]->RecordOwner);
        
    $header_html='';
    if($entity_detail->AttachedLogo) {
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            
        if($entity_detail->AlignLogo==1) {
            $header_html .='<div id="header_content" ><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span>';
            $header_html .='<span style="font-size: 15px; float: right;"><b>'.$entity_detail->EntityName.'</b></span></div>';
        } else if($entity_detail->AlignLogo==2) {
            $header_html .='<div id="header_content" ><center><span style="font-size: 15px; " ><img src="'.$url.'" style="max-width: 50px;" /></span><br/>';
            $header_html .='<span style="font-size: 15px;"><b>'.$entity_detail->EntityName.'</b></span></center></div>';
        } else if($entity_detail->AlignLogo==3) {
            $header_html .='<div id="header_content" style="height: 45px;" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span>';
            $header_html .='<span style="font-size: 15px; float: right;" ><img src="'.$url.'" style="max-width: 50px;" /></span></div>';
        }
    } else {
        $header_html .='<div id="header_content" ><span style="font-size: 15px; "><b>'.$entity_detail->EntityName.'</b></span></div>';
    }
    $header_html .='<br/><hr style="background-color: black; height: 2px;" ><br/>';    
    $html='';
    $type='quote';
    $fr_flg=0;
    if($data1) {
        foreach($data1 as $row) {
            
            $data2=$this->cargo_quote_model->getResponseQuoteDatailsLatest($row->LineNum, $row->ResponseID, $row->AuctionID);
            
            $data3=$this->cargo_quote_model->getDifferentialReferenceResponse($data2->DifferentialID);
            $temp1='';
            $temp2='';
             
            if($row->FreightBasis !='') {
                $fr_flg=1;
                $html .='<h4><B>Freight Quote </B></h4>';
                if($row->FreightRateUOM==1) {
                       $FreightRateUOM='UnitCode : MT || Description : Metric Tonnes';
                }else if($row->FreightRateUOM==2) {
                     $FreightRateUOM='UnitCode : LT || Description : Long Tonnes';
                }else if($row->FreightRateUOM==3) {
                      $FreightRateUOM='UnitCode : PMT || Description : Per metric tonne';
                }else if($row->FreightRateUOM==4) {
                    $FreightRateUOM='UnitCode : PLT || Description : Per long ton';
                }else if($row->FreightRateUOM==5) {
                    $FreightRateUOM='UnitCode : WWD || Description : Weather Working Day';
                }
                $html .='<div class="form-group">
						<label class="control-label col-lg-5">MasterID : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->AuctionID.'</label>
						</div>	
						<div class="form-group">
						<label class="control-label col-lg-5">Response ID : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->ResponseID.'</label>
						</div>';
                 
                if($row->FreightBasis==1) {
                                 $FreightBasis='$/mt';
                                 $temp1='<div class="form-group">
						<label class="control-label col-lg-5">Freight rate : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->FreightRate.'</label>
						</div>	
						<div class="form-group">
						<label class="control-label col-lg-5">Freight currrency : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->curCode.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight rate (UOM) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$FreightRateUOM.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">TCE (usd/day) for quoted freight : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->FreightTce).'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">TCE (usd/day) for freight differential : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->FreightTceDifferential).'</label>
						</div>';
                    
                }else if($row->FreightBasis==2) {
                               $FreightBasis='Lumpsum';
                               $temp1='<div class="form-group">
						<label class="control-label col-lg-5">Freight (lumpsum - max) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->FreightLumpsumMax.'</label>
						</div>	
						<div class="form-group">
						<label class="control-label col-lg-5">Freight currrency : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->curCode.'</label>
						</div>';
                }else if($row->FreightBasis==3) {
                             $FreightBasis='High - Low ($/mt)';
                             $temp1='<div class="form-group">
						<label class="control-label col-lg-5">Freight rate from (Low) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->FreightLow.'</label>
						</div>	
						<div class="form-group">
						<label class="control-label col-lg-5">Freight rate to (High) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->FreightHigh.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight currrency : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->curCode.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight rate (UOM) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$FreightRateUOM.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">TCE (usd/day) for quoted freight : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->FreightTce.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">TCE (usd/day) for freight differential : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->FreightTceDifferential.'</label>
						</div>';
                } 
                    
                $html .=$temp1;
                $html .='<hr style="border-top: solid 1px black;"><br/><h4><B> Differential </B></h4>';
                if($data2->VesselGroupSizeID) {
                           $html .='<div class="form-group">
					<label class="control-label col-lg-5">Vessel size : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($data2->VesselSize).' || '.$data2->SizeGroup.'</label>
					</div>';
                }
                if($data2->BaseLoadPort) {
                         $html .='<div class="form-group">
					<label class="control-label col-lg-5">Base (load) port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->BsDescription.' || '.$data2->BsCode.'</label>
					</div>';
                }
                if($data2->FreightReferenceFlg) {
                       $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport(s) for freight reference : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->FreightReferenceFlg.'</label>
					</div>';
                }
                if($data2->FreightReferenceFlg==1) {
                     $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 1 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp1Description.' || '.$data2->Rp1Code.'</label>
					</div>';
                }
                if($data2->FreightReferenceFlg==2) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 1 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp1Description.' || '.$data2->Rp1Code.'</label>
					</div>';
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 2 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp2Description.' || '.$data2->Rp2Code.'</label>
					</div>';
                }
                if($data2->FreightReferenceFlg==3) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 1 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp1Description.' || '.$data2->Rp1Code.'</label>
					</div>';
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 2 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp2Description.' || '.$data2->Rp2Code.'</label>
					</div>';
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Disport 2 reference port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data2->Rp3Description.' || '.$data2->Rp3Code.'</label>
					</div>';
                }
                
                if(count($data3) > 0) {
                    $html .='<table class="table table-striped table-hover table-bordered" style="font-size: 12px;" ><tr><th> Differential disport(s)  </th><th> Lp/Dp</th><th> Load/Dis Rate</th><th> Unit</th><th> Diff (Y/N)</th><th> Differential (owner) ($)</th><th> Differential (invitee) </th></tr>';
                    
                    $PostGroupNo=0;
                    $i=0;
                    foreach($data3 as $d3) {
                        if($d3->GroupNo!=$PostGroupNo && $i!=0) {
                               $html .='<tr><td colspan="7"><hr style="border-top: solid 1px black;"></td></tr>';
                        }
                        $PostGroupNo=$d3->GroupNo;    
                        if($d3->LpDpFlg==1) {
                            $lp_dp='Lp';
                        }else if($d3->LpDpFlg==2) {
                            $lp_dp='Dp';
                        }
                        if($d3->LoadDischargeUnit==1) {
                            $LoadDischargeUnit='$ mt/hr';
                        } else if($d3->LoadDischargeUnit==2) {
                            $LoadDischargeUnit='$ mt/day';
                        }
                        if($d3->DifferentialFlg==1) {
                            $DifferentialFlg='Yes';
                        } else if($d3->DifferentialFlg==2) {
                            $DifferentialFlg='No';
                        }
                        $html .='<tr><td>'.$d3->PortName.'</td><td>'.$lp_dp.'</td><td>'.$d3->LoadDischargeRate.'</td><td>'.$LoadDischargeUnit.'</td><td>'.$DifferentialFlg.'</td><td>'.$d3->DifferentialOwnerAmt.'</td><td>'.$d3->DifferentialInviteeAmt.'</td></tr>';
                        
                        $i++;
                    }
                    $html .='</table>';
                }
                $html .='<br/><hr style="border-top: solid 1px black;"><br/><h4><B>  Demurrage - Despatch  </B></h4>';
                            
                if($row->Demurrage) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Demurrage ($/day) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->Demurrage).'</label>
						</div>';
                }
                if($row->DespatchDemurrageFlag==1) {
                    $DespatchDemurrageFlag='Yes';
                    $temp2='<div class="form-group">
						<label class="control-label col-lg-5">Despatch - ( half of Demurrage) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$DespatchDemurrageFlag.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Despatch( half of Demurrage) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->DespatchHalfDemurrage).'</label>
						</div>';
                }else if($row->DespatchDemurrageFlag==2) {
                    $DespatchDemurrageFlag='No';
                    $temp2='<div class="form-group">
						<label class="control-label col-lg-5">Despatch - ( half of Demurrage) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$DespatchDemurrageFlag.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Despatch( half of Demurrage) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->DespatchHalfDemurrage).'</label>
						</div>';
                }
                $html .=$temp2;
                    
                if($row->CommentsByInvitee) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Comment by invitee : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->CommentsByInvitee.'</label>
						</div>';
                }
            }
        }
        $html .='<br/><hr style="background-color: black; height: 2px;" ><br/>';    
    }
        
    if($fr_flg==1) {
        $data['content']=$html;
        $data['header']=$header_html;
        echo json_encode($data);
    } else {
        $data['content']=2;
        $data['header']=$header_html;
        echo json_encode($data);
    }
        
}
    
public function getShipOwnerUsers()
{
    $ship_id=$this->input->get('shipID');
    if($ship_id !='') {
        $data['details']=$this->cargo_quote_model->get_assoc_entity_details1($ship_id);
        $data['broker_users']=$this->cargo_quote_model->get_response_broker_users();
        $data['flag']=1;
    } else {
        $data['flag']=2;
    }
        
    $this->output->set_output(json_encode($data));
}
    
public function getInviteeVesselByResponseId()
{
    $data=$this->cargo_quote_model->getInviteeVesselByResponseId();
    echo json_encode($data);
}
    
public function get_shipowner_user_details()
{
    $ship_id=$this->cargo_quote_model->get_shipowner_user_details();
    if($ship_id !='') {
        $data['details']=$this->cargo_quote_model->get_assoc_entity_details1($ship_id);
        $data['broker_users']=$this->cargo_quote_model->get_response_broker_users();
        $data['flag']=1;
    } else {
        $data['flag']=2;
    }
    //print_r($data); die;
    $this->output->set_output(json_encode($data));
}
	
}

