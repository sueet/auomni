<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
header('Access-Control-Allow-Origin: *');
    
class fn_cp_ctrl extends CI_Controller
{
     
    function __construct()
    {
        parent::__construct();
        ob_start();
        error_reporting(0);
        $this->load->model('EncriptDecrtipt_model', 'EncodeDecode');
        $this->load->model('cp_fn_model', '', true);
        
        //sendsms(); // send sms function.
        
    } 
    
    public function getFixtureData()
    {
        $data=$this->cp_fn_model->getFixtureData();
        $html='';
        $inhtml='';
        $tempMasterID='';
        $i=1;
        $html='{ "aaData": [';
        foreach($data as $row) {
            if($row->Status==1) {
                $status="Discussion";
            }else if($row->Status==2) {
                $status="Fixture Complete";
            }else{
                $status="Closed";
            }
            if($row->InviteeConfirmation=='1') {
                $InviteeConfirmation="Tentative";
            }else if($row->InviteeConfirmation=='2') {
                $InviteeConfirmation="Final";
            }else if($row->InviteeConfirmation=='3') {
                $InviteeConfirmation="Save for now";
            }else{
                $InviteeConfirmation="-";
            }
            
            if($row->OwnerConfirmation == 1) {
                $OwnerConfirmation="Tentative";
            }else if($row->OwnerConfirmation == 2) {
                $OwnerConfirmation="Final";
            }else if($row->OwnerConfirmation == 3) {
                $OwnerConfirmation="Save for now";
            }else{
                $OwnerConfirmation="-";
            }
            if($row->FixtureNoteChanges=='') {
                $view='-';
            }else{
                $view="<a href='javascript:void(0)' onclick=viewFixtureChanges('".$row->FixtureID."'); >view</a>";
            }
            $viewReciept="<a href='javascript:void(0)' onclick=getFixtureTransactionReciept('".$row->transactionHash."'); >view</a>";
            $viewipfs="<a href='javascript:void(0)' onclick=ipfsView('".$row->FixtureID."'); >view</a>";
            $ckbx="<input type='checkbox' name='FixtureVersion[]' class='chkNumber' value='".$row->FixtureID.'_'.$row->ID."_".$i."' style='margin-bottom: 6px;' >";
            
            $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->FixtureID.'_'.$row->ID."_".$i."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->FixtureID.'_'.$row->ID."_".$i."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $ViewAll='-';
            if($row->FixtureVersion!='Version 1.0') {
                $ViewAll="<a href='javascript:void(0)' onclick=viewFixtureAllChanges('".$row->FixtureID."'); >view all</a>";
            }
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->ResponseID.'","'.$row->FixtureVersion.'","'.$row->EntityName.'","'.$InviteeConfirmation.'","'.$OwnerConfirmation.'","'.$row->FirstName.' '.$row->LastName.'","'.$view.'","'.$ViewAll.'","'.$viewReciept.'","'.$viewipfs.'","'.$status.'","'.$link.'"],';
            $i++;
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html; 
    }
    
    public function getFixtureChangesById()
    {
        $data['fixture']=$this->cp_fn_model->getFixtureChangesById();
        //print_r($data); die;
        $data['prev_fixture']=$this->cp_fn_model->getPrevFixtureData();
        $html='';
        $dateTime='';
        $ver1=explode(' ', $data['fixture']->FixtureVersion);
        $ver2=explode(' ', $data['prev_fixture']->FixtureVersion);
        $data['header_content']='Changes from V '.$ver2[1].' to V '.$ver1[1];
        if($data['fixture']->InviteeConfirmation != $data['prev_fixture']->InviteeConfirmation) {
            $newInviteeConfirmation='-';
            $oldInviteeConfirmation='-';
            if($data['fixture']->InviteeConfirmation==1) {
                $newInviteeConfirmation='Tentative';
            } else if($data['fixture']->InviteeConfirmation==2) {
                $newInviteeConfirmation='Final';
            } else if($data['fixture']->InviteeConfirmation==3) {
                $newInviteeConfirmation='Not right now';
            }
            if($data['prev_fixture']->InviteeConfirmation==1) {
                $oldInviteeConfirmation='Tentative';
            } else if($data['prev_fixture']->InviteeConfirmation==2) {
                $oldInviteeConfirmation='Final';
            } else if($data['prev_fixture']->InviteeConfirmation==3) {
                $oldInviteeConfirmation='Not right now';
            }
            $html='<p>Invitee status change from '.$oldInviteeConfirmation.' to '.$newInviteeConfirmation.'<p>';
            $dateTime='<p>By : '.$data['fixture']->FirstName.' '.$data['fixture']->LastName.'</p>';
            $dateTime .='<p>DateTime : '.date('d-m-Y H:i:s', strtotime($data['fixture']->UserDate)).'</p>';
        }
        if($data['fixture']->OwnerConfirmation != $data['prev_fixture']->OwnerConfirmation) {
            $newOwnerConfirmation='-';
            $oldOwnerConfirmation='-';
            if($data['fixture']->OwnerConfirmation==1) {
                $newOwnerConfirmation='Tentative';
            } else if($data['fixture']->OwnerConfirmation==2) {
                $newOwnerConfirmation='Final';
            } else if($data['fixture']->OwnerConfirmation==3) {
                $newOwnerConfirmation='Not right now';
            }
            if($data['prev_fixture']->OwnerConfirmation==1) {
                $oldOwnerConfirmation='Tentative';
            } else if($data['prev_fixture']->OwnerConfirmation==2) {
                $oldOwnerConfirmation='Final';
            } else if($data['prev_fixture']->OwnerConfirmation==3) {
                $oldOwnerConfirmation='Not right now';
            }
            $html='<p>Record owner status change from '.$oldOwnerConfirmation.' to '.$newOwnerConfirmation.'</p>';
            $dateTime='<p>By : '.$data['fixture']->FirstName.' '.$data['fixture']->LastName.'</p>';
            $dateTime .='<p>DateTime : '.date('d-m-Y H:i:s', strtotime($data['fixture']->UserDate)).'</p>';
        }
        if($data['fixture']->Status != $data['prev_fixture']->Status) {
            $newStatus='Discussion';
            $oldStatus='Discussion';
            if($data['fixture']->Status==1) {
                $newStatus='Discussion';
            } else if($data['fixture']->Status==2) {
                $newStatus='Complete';
            }
            if($data['prev_fixture']->Status==1) {
                $oldStatus='Discussion';
            } else if($data['prev_fixture']->Status==2) {
                $oldStatus='Complete';
            }
            $html .='<p>Fixture note status change from '.$oldStatus.' to '.$newStatus.'</p>';
            $dateTime='<p>By : '.$data['fixture']->FirstName.' '.$data['fixture']->LastName.'</p>';
            $dateTime .='<p>DateTime : '.date('d-m-Y H:i:s', strtotime($data['fixture']->UserDate)).'</p>';
        }
        $html .=$dateTime;
        $data['change_status']=$html;
        
        echo json_encode($data);
    }
    
    public function getDocumentationChangesById()
    {
        $data1=$this->cp_fn_model->getDocumentationChangesById();
        $var='';
        $changes='';
        foreach($data1 as $row){
            $var=$row->ClauseVersion;
            if($row->DeletedClauseNote =='' && $row->AddedClauseNote =='' && $row->ChangeClauseStatus =='') {
                continue;
            }
            $changes .=$row->ClauseName.'<br><br>';
            
            if($row->DeletedClauseNote !='') {
                $changes .='Deleted content';
                $changes .=$row->DeletedClauseNote;
                $changes .='<br>';
            }
            if($row->AddedClauseNote !='') {
                $changes .='Added content';
                $changes .=$row->AddedClauseNote;
                $changes .='<br>';
            }
            if($row->ChangeClauseStatus !='') {
                $changes .=$row->ChangeClauseStatus;
                $changes .='<br>';
            }
            $changes .='<hr>';
        }
        $varArr=explode(' ', $var);
        $data['var1']=$varArr[1];
        $data['var2']=$varArr[1]-0.1;
        $data['changes']=$changes;
        echo json_encode($data);
    }
    
    public function getFixtureById()
    {
        $row=$this->cp_fn_model->getFixtureById();
        $row1=$this->cp_fn_model->getFixtureNoteById();
        $row2=$this->cp_fn_model->getAuctionDetailsByAuctionID($row->AuctionID);
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->FixtureVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['EntityName1']=$row->EntityName1;
        $data['EntityNameOwner']=$row->EntityName;
        $data['Status']=$row->Status;
        $data['FixtureHash']=$row->FixtureHash;
        $data['FixtureFormatType']=$row->FixtureFormatType;
        $data['FixtureCompleteProcess']=$row->FixtureCompleteProcess;
        $data['data1']=$row1;
        $data['data2']=$row2;
        //print_r($data);  die;
        echo json_encode($data);
        
    }
    
    public function getFixtureByIdHtml()
    {
        $this->load->model('cargo_model', '', true);
        //$decode_html=html_entity_decode($encode_html);
        //echo 'text'; die;
        $row=$this->cp_fn_model->getFixtureById();
        $row1=$this->cp_fn_model->getFixtureNoteById();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($row->RecordOwner);
        $logo='';
        $AlignLogo='';
        if($document->Logo) {
            $logo=$document->Logo;
            $AlignLogo=$document->LogoAlign;
        } else if($entity_detail->AttachedLogo) {
            $logo=$entity_detail->AttachedLogo;
            $AlignLogo=$entity_detail->AlignLogo;
        }
        if($logo != '') {
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);
            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$logo, 3600);
            $data['Logo']=$url;
            $data['AlignLogo']=$AlignLogo;
        } else {
            $data['Logo']='';
            $data['AlignLogo']='';
        }
        

        $data['Title']=$document->DocumentTitle;
        
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->FixtureVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['OwnerEntityName']=$entity_detail->EntityName;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        $data['data1']=$row1;
        $data['HeaderContent']=$row->HeaderContent;
        
        echo json_encode($data);
    }
    
    
    
    public function checkFixtureComplete()
    {
        $data=$this->cp_fn_model->checkFixtureComplete();
        echo json_encode($data);
    }
    
    public function htmlFixtureDownload()
    {
        include_once APPPATH.'third_party/mpdf.php';        
        
        $row=$this->cp_fn_model->getFixtureById();
        $row1=$this->cp_fn_model->getFixtureNoteById();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$document->Logo, 3600);
        $data['Title']=$document->DocumentTitle;
        $data['Logo']=$url;
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->FixtureVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        $data['data1']=$row1;
        $data['HeaderContent']=$row->HeaderContent;
        //print_r($data); die;
        $html=$this->load->view('setup/pdf_fixturedownload', $data, true);
        //$html='test';
        //echo $html; die;
        $pdfFilePath = $row->AuctionID."(".$row->FixtureVersion.").pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
        
    }
    
    public function getFixNoteFinalData()
    {
        $row=$this->cp_fn_model->getFixNoteFinalData();
        //print_R($row); die;
        $html='';
        if($row) {
            $viewReciept="<a href='javascript:void(0);' onclick=getFixtureTransactionReciept('".$row->transactionHash."'); >view</a>";
            $viewipfs="<a href='javascript:void(0);' onclick=ipfsView('".$row->FixtureID."'); >view</a>";
            $view="<a href='javascript: void(0);' onclick='viewFixturecontent(".$row->FixtureID.");'>view</a>";
            $html .='<tr>';
            $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td>'.$row->ResponseID.'</td>';
            $html .='<td>'.$row->FixtureVersion.'</td>';
            $html .='<td>'.$row->EntityName.'</td>';
            $html .='<td>Final</td>';
            $html .='<td>Final</td>';
            $html .='<td>Fixture Complete</td>';
            $html .='<td>'.$view.'</td>';
            $html .='<td>'.$viewReciept.'</td>';
            $html .='<td>'.$viewipfs.'</td>';
            $html .='</tr>';
        }
        
        echo $html; 
    }
    
    public function getDocumentationData()
    {
        $data=$this->cp_fn_model->getDocumentationData();
        //print_R($row); die;
        $html='';
        $inhtml='';
        $tempMasterID='';
        $i=1;
        $html='{ "aaData": [';
        foreach($data as $row) {
            if($row->Status==1) {
                $status="Discussion";
            }else if($row->Status==2) {
                $status="CharterParty Complete";
            }else{
                $status="Closed";
            }
            if($row->InviteeConfirmation=='1') {
                $InviteeConfirmation="Tentative";
            }else if($row->InviteeConfirmation=='2') {
                $InviteeConfirmation="Final";
            }else if($row->InviteeConfirmation=='3') {
                $InviteeConfirmation="Not Right Now";
            }else{
                $InviteeConfirmation="-";
            }
            
            if($row->OwnerConfirmation == 1) {
                $OwnerConfirmation="Tentative";
            }else if($row->OwnerConfirmation == 2) {
                $OwnerConfirmation="Final";
            }else if($row->OwnerConfirmation == 3) {
                $OwnerConfirmation="Not Right Now";
            }else{
                $OwnerConfirmation="-";
            }
            $flag=$this->cp_fn_model->checkDocumentationChanges($row->DocumentationID);
            if($flag) {
                $view="<a href='javascript: void(0);' onclick=viewDocumentationChanges('".$row->DocumentationID."'); >view</a>";
            }else{
                $view='-';
            }
            
            if($row->DocumentationVersion !='Version 1.0') {
                $viewAll="<a href='javascript: void(0);' onclick=viewAllDocumentationChanges('".$row->DocumentationID."'); >view all</a>";
            } else {
                $viewAll="-";
            }
            
            $viewReciept="<a href='javascript:void(0)' onclick=getFixtureTransactionReciept('".$row->transactionHash."'); >view</a>";
            
            $viewipfsCharter="<a href='javascript:void(0)' onclick=ipfsViewCharterParty('".$row->DocumentationID."'); >view</a>";
            
            $ckbx="<input type='checkbox' name='Documentation[]' class='chkNumber' value='".$row->DocumentationID."' style='margin-bottom: 6px;' >";
            
            $action="<a href='javascript: void(0);' onclick=editDocumentation('".$row->DocumentationID."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->DocumentationID."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $sts=$this->cp_fn_model->getDocumentStatusCount($row->DocumentationID);
            if($sts['inv_status']==$sts['tot_count']) {
                $InviteeConfirmation .='<span style=color:#12c212;><b> ('.$sts['inv_status'].'</span>/<span style=color:#12c212;>'.$sts['tot_count'].')</b></span>';
            } else {
                $InviteeConfirmation .='<span style=color:#d85119;><b> ('.$sts['inv_status'].'</span>/<span style=color:#12c212;>'.$sts['tot_count'].')</b></span>';
            }
            if($sts['ro_status']==$sts['tot_count']) {
                $OwnerConfirmation .='<span style=color:#12c212;><b> ('.$sts['ro_status'].'</span>/<span style=color:#12c212;>'.$sts['tot_count'].')</b></span>';
            } else {
                $OwnerConfirmation .='<span style=color:#d85119;><b> ('.$sts['ro_status'].'</span>/<span style=color:#12c212;>'.$sts['tot_count'].')</b></span>';
            }
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->ResponseID.'","'.$row->DocumentationVersion.'","'.$row->EntityName.'","'.$InviteeConfirmation.'","'.$OwnerConfirmation.'","'.$row->FirstName.' '.$row->LastName.'","'.$view.'","'.$viewAll.'","'.$viewReciept.'","'.$viewipfsCharter.'","'.$status.'","'.$action.'"],';
            $i++;
        } 
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html; 
    }
    
    public function checkDocumentationComplete()
    {
        $data=$this->cp_fn_model->checkDocumentationComplete();
        echo json_encode($data);
    }
    
    
    public function getDocumentationById()
    {
        //$decode_html=html_entity_decode($encode_html);
        //echo 'text'; die;
        $row=$this->cp_fn_model->getDocumentationById();
        $row1=$this->cp_fn_model->getDocumentationNoteById();
        
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$row->CharterPartyPdf, 3600);
        $nar=explode("?", $url);
        $doc=current($nar);
        $data['docContent']='<iframe class="holds-the-iframe" src="http://docs.google.com/gview?url='.$doc.'&embedded=true" style="width:100%; height: 75%;" frameborder="0" onload="onMyFrameLoad(this)"></iframe>';
        $data['EditableFlag']=$row->EditableFlag;
        $data['ClauseType']=$row->ClauseType;
        $data['CharterPartyPdf']=$row->CharterPartyPdf;
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->DocumentationVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        $data['data1']=$row1;
        $data['CharterHash']=$row->CharterHash;
        
        echo json_encode($data);
        
    }
    
    public function getDocumentationClauseById()
    {
        
        $data['Clauses']=$this->cp_fn_model->getDocumentationClauseById();
        $data['ClausesNotes']=$this->cp_fn_model->getDocumentationClauseNoteById();
        //$data['AllClausesNotes']=$this->cp_fn_model->getDocumentationAllClauseNoteById();
        //print_r($data); die;
        echo json_encode($data);
        
    }
    
    public function getEditableDocumentationClauseById()
    {
        
        $data['Clauses']=$this->cp_fn_model->getEditableDocumentationClauseById();
        
        $data['ClausesNotes']=$this->cp_fn_model->getEditableDocumentationClauseNoteById();
        //$data['AllClausesNotes']=$this->cp_fn_model->getDocumentationAllClauseNoteById();
        
        echo json_encode($data);
        
    }
    
    
    public function getDocumentationByIdHtml()
    {
        $this->load->model('cargo_model', '', true);
        $row=$this->cp_fn_model->getDocumentationById();
        $row1=$this->cp_fn_model->getDocumentationNoteById();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($row->RecordOwner);
        
        $data['Clauses']=$this->cp_fn_model->getDocumentationClauses();
        //print_r($data); die;
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        
        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$row->CharterPartyPdf, 3600);
        $nar=explode("?", $url);
        $doc=current($nar);
        $data['docContent']='<iframe class="holds-the-iframe" src="http://docs.google.com/gview?url='.$doc.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
        
        $logo='';
        $AlignLogo='';
        if($document->Logo) {
            $logo=$document->Logo;
            $AlignLogo=$document->LogoAlign;
        } else if($entity_detail->AttachedLogo) {
            $logo=$entity_detail->AttachedLogo;
            $AlignLogo=$entity_detail->AlignLogo;
        }
        if($logo != '') {    
            $url1 = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$document->Logo, 3600);
            $data['Logo']=$url1;
            $data['AlignLogo']=$AlignLogo;
        } else {
            $data['Logo']='';
            $data['AlignLogo']='';
        }
        
        $data['Title']=$document->DocName;
        $data['Logo']=$url1;
        $data['EditableFlag']=$row->EditableFlag;
        $data['CharterPartyPdf']=$row->CharterPartyPdf;
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->DocumentationVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['OwnerEntityName']=$entity_detail->EntityName;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        
        if($row->ClauseType==1) {
            $data['data1']='';
        }else{
            $data['data1']=$row1;
        }
        echo json_encode($data);
        
    }
    
    public function htmlDocumentationDownloadNonedit()
    {
        $this->load->helper('download');
        $row=$this->cp_fn_model->getDocumentationById();
        $filename=$row->CharterPartyPdf;
        
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
    
    public function htmlDocumentationDownloadEditable()
    {
        include_once APPPATH.'third_party/mpdf.php';        
        
        $row=$this->cp_fn_model->getDocumentationById();
        $row1=$this->cp_fn_model->getDocumentationNoteById();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $data['Clauses']=$this->cp_fn_model->getDocumentationClauses();
        $filename=$row->CharterPartyPdf;
        
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);
        
        $url1 = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$document->Logo, 3600);
        $data['Title']=$document->DocumentTitle;
        $data['Logo']=$url1;
        if($row->ClauseType==1) {
            $data['data1']='';
        }else {
            $data['data1']=$row1;
        }
        //print_r($data); die;
        $html=$this->load->view('setup/pdf_documentationdownload', $data, true);
        //$html='test';
        //echo $html; die;
        $pdfFilePath = $row->AuctionID."(".$filename.").pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
    
    
    public function getLatestDocumentClauses()
    {
        $data=$this->cp_fn_model->getLatestDocumentClauses();
        echo json_encode($data);
    }
    
    public function htmlClauseDownload()
    {
        $this->load->model('cargo_quote_model', '', true);
        include_once APPPATH.'third_party/mpdf.php';        
        
        $data=$this->cp_fn_model->getAllClausesText();
        $logoData=$this->cp_fn_model->getLogo();
        $html='';
        if($logoData) {
            $fileName=$logoData->Logo;
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);

            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$fileName, 3600);
            
            if($logoData->LogoAlign==1) {
                $html1 .='<br><img src="'.$url.'" style="width: 15%; margin-top: -3%;"></img>';
            }
            if($logoData->LogoAlign==2) {
                $html1 .='<br><center><img src="'.$url.'" style="width: 15%; margin-top: -3%;" ></img></center>';
            }
            if($logoData->LogoAlign==3) {
                $html1 .='<br><p><img src="'.$url.'" style="width: 15%; margin-left: 80%; margin-top: -3%;"></img></p>';
            }
        }
        $html .=$html1;
        $html .='<br/><br/><p style="font-size: 15px;">'.$logoData->DocName.'</p>';
        
        if($logoData->ClauseType !=1) {
            $html .='<hr />';
            $html .='<div style="page-break-after: always"><h6><b>INDEX TO CLAUSES</b></h6>';
            foreach($data as $row) {
                $html .='<p>'.$row->ClauseNo.'.  '.$row->CaluseName.'</p>';    
            }
            $html .='<hr /></div>';
        }
        foreach($data as $row) {
            $clause_text=$this->cargo_quote_model->getClausesTextByID($row->ClauseID);
            $html .='<p>'.$clause_text.'</p>';    
        }
        //print_r($html); die;
        $pdfFilePath = "Allclauses.pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
    
    public function viewAllDeletedByClause()
    {
        $data['clause']=$this->cp_fn_model->viewAllDeletedByClause();
        echo json_encode($data);
    }
    
    public function getAllChausesChanges()
    {
        $data['allclause']=$this->cp_fn_model->getAllChausesChanges();
        $data['AllClausesNotes']=$this->cp_fn_model->getDocumentationAllClauseNoteChanges();
        //print_r($data['AllClausesNotes']); die;
        echo json_encode($data);
    
    }
    
    public function getUniqueBusinessProcessByAuctionId()
    {
        $p1=$this->input->post('p1');
        $p2=$this->input->post('p2');
        $p3=$this->input->post('p3');
        $p9=$this->input->post('p9');
        $p4=$this->input->post('p4');
        $p10=$this->input->post('p10');
        $udata=$this->cp_fn_model->getUniqueBusinessProcessByAuctionId();
        //print_r($udata); die;
        $latestPending=$this->cp_fn_model->getNextPendingBusinessProcessByAuctionId();
        $flg=0;
        $latestProcessNo=0;
        $prevProcess=0;
        //print_r($latestPending); die;
        if($latestPending) {
            $flg=1;
            $latestProcessNo=$latestPending->process_flow_sequence;
        }
        
        $rvessel=$this->cp_fn_model->getResponseVessel();
        $broker=$this->cp_fn_model->getBroker();
        $ShipOwner=$this->cp_fn_model->getShipOwner();
        $html='';
        $link='edit';            
        $cnt=0;        
        $i=0;    
        $tvflag=0;
        $liftflag=0;
        
        $cnt_flag=0;
        foreach($udata as $rows) {
            $dsp_flag=0;
            $data=$this->cp_fn_model->getBusinessProcessByAuctionId($rows->BPID);
            //print_r($data); die;
            if($latestProcessNo==0) {
                $latestProcessNo=$rows->process_flow_sequence;
            }
            $cnt_rw=count($data);
            //print_r($cnt_rw); die;
            $imgcnt='';
            if($cnt_rw > 0) {
                if($data[0]->ApproveStatus==1) {
                         $imgcnt='<img src="img/tick.png" title="Approved" style=" margin-top: 10px; width: 15px;" ></img>';
                         $dsp_flag=1;
                }
            }
            if($dsp_flag==0 && $cnt_flag==0) {
                //--for show--
                $dsp_none='inline'; //---none
                $dsp_tbl='table';  //---none
                $dsp_inl='none';    //---inline
                $cnt_flag=1;
            } else {
                $dsp_none='none';
                $dsp_tbl='none';
                $dsp_inl='inline';
            }
            if($flg==0 && $rows->process_flow_sequence!=$prevProcess ) {
                $prevProcess=$rows->process_flow_sequence;
            }
        
            if($data[0]->ApproveStatus != 1) {
                $flg=1;
            }
            $fixstatus=$this->cp_fn_model->getFixtureNoteByTID();
            $vsource='';
            if($rvessel->Source=='Rightship') {
                $vsource='Rightship';
            } else if($rvessel->Source=='Other source') {
                $vsource='Other source ('.$rvessel->SourceType.')';
            } 
            $line='';
            if($cnt>0) {
                $line='<br>';
            }    
        
            if($data[0]->name_of_process==1) {
                $tvflag=$data[0]->ApproveStatus;
                $name_of_process='Technical Vetting';
                if($p1==0) {    continue;
                }
            } else if($data[0]->name_of_process==2) {
                $name_of_process='Business vetting approval';
                if($p2==0) {    continue;
                }
            } else if($data[0]->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
                if($p3==0) {    continue;
                }
            } else if($data[0]->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
                if($p4==0) {    continue;
                }
            } else if($data[0]->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($data[0]->name_of_process==6) {
                $name_of_process='Charter party authorization';
            } else if($data[0]->name_of_process==7) {
                $name_of_process='Fixture note authorization';
            } else if($data[0]->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($data[0]->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
                if($p9==0) {    continue;
                }
            } else if($data[0]->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
                if($p10==0) { continue;
                }
            }
        
            if($data[0]->name_of_process==9) {
                $CPSubject=$this->cp_fn_model->checkCPSubjectLifted();
                if($CPSubject->CH_Task==2 && $CPSubject->ConfirmLift==1) {
                    $liftflag=1;
                }
                $tbl=$line.'<br><header id="view_header" ><div class="icons" style="height: 40px;"><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th class="padd_th">Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Vetting source</th><th class="padd_th">Changes</th><th class="padd_th">Subject Lifted</th><th class="padd_th">Lifted by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            } else if($data[0]->name_of_process==3 || $data[0]->name_of_process==4) {
                $tbl=$line.'<br><header id="view_header" ><div class="icons" style="height: 40px;"><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th class="padd_th">Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            } else {
                $tbl=$line.'<br><header id="view_header" ><div class="icons" style="height: 40px;"><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th class="padd_th">Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Vetting source</th><th class="padd_th">Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            }
        
            $html .=$tbl;
            
            foreach($data as $row) {
                $status='No';
                $rstatus='Discussion';
                if($row->ApproveStatus==1) {
                    $status='Yes';
                    $rstatus='Complete';
                } else {
            
                }
                      $html .='<tr>';
                if($data[0]->name_of_process==1) {
                    if(($rvessel->IMO=='0000000' || $row->process_applies!=4) || $fixstatus==2 || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";                
                    } else {
                                   $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } 
                } else if($data[0]->name_of_process==2 ) {
                     //print_r($tvflag); die;
                    if(($rvessel->IMO=='0000000' || $row->process_applies != 4) || $tvflag != 1 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."')  title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==9) {
                           //print_r($flg); die;
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $liftflag==1 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==3) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==4) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";            
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==10) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editFixtureNote('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                }
                                                                                                                $view='-';
                if($row->ViewChage) {
                    $view='<a href="javascript:void(0)" onclick="getChanges('.$row->BPVID.','.$row->Version.')">view</a>';
                }
                                                                                                                $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
                                                                                                                $html .='<td>'.$row->Version.'</td>';
                                                                                                                $html .='<td>'.$row->TID.'</td>';
                                                                                                                $html .='<td>'.$broker.'</td>';
                                                                                                                $html .='<td>'.$ShipOwner.'</td>';
                                                                                                                $html .='<td>'.$rvessel->VesselName.'</td>';
                                                                                                                $html .='<td>'.$rvessel->IMO.'</td>';
                if($data[0]->name_of_process!=3 && $data[0]->name_of_process!=4) {
                    $html .='<td>'.$vsource.'</td>';
                }
                                                                                                                $html .='<td>'.$view.'</td>';
                                                                                                                $html .='<td>'.$status.'</td>';
                                                                                                                $html .='<td>'.$row->ApprovedBy.'</td>';
                                                                                                                $html .='<td>'.$rstatus.'</td>';
                                                                                                                $html .='<td>'.$link.'</td>';
                                                                                                                $html .='</tr>';
            }
            $html .='</table>';
            $cnt++;
            $i++;
            $prevProcess=$rows->process_flow_sequence;
        }
        //echo $html;
        echo $html.'__________'.$i;
    }
        
    public function getUniqueInvBusinessProcessByAuctionId()
    {
        $p1=$this->input->post('p1');
        $p2=$this->input->post('p2');
        $p3=$this->input->post('p3');
        $p9=$this->input->post('p9');
        $p4=$this->input->post('p4');
        $p10=$this->input->post('p10');
        $udata=$this->cp_fn_model->getUniqueInvBusinessProcessByAuctionId();
        //print_r($udata); die;
        $latestPending=$this->cp_fn_model->getNextPendingBusinessProcessByAuctionId();
        $flg=0;
        $latestProcessNo=0;
        if($latestPending) {
            $flg=1;
            $latestProcessNo=$latestPending->process_flow_sequence;
        }
        
        $rvessel=$this->cp_fn_model->getResponseVessel();
        $broker=$this->cp_fn_model->getBroker();
        $ShipOwner=$this->cp_fn_model->getShipOwner();
        $html='';
        $link='edit';        
        $cnt=0;        
        $i=0;    
        $tvflag=0;
        $liftflag=0;
        
        $prevProcess=0;
        $dsp_flag=0;
        $cnt_flag=0;
        foreach($udata as $rows) {
            $data=$this->cp_fn_model->getBusinessProcessByAuctionId($rows->BPID);
            //print_r($data[0]); die;
            if($latestProcessNo==0) {
                $latestProcessNo=$rows->process_flow_sequence;
            }
            $cnt_rw=count($data);
            //print_r($cnt_rw); die;
            $imgcnt='';
            if($cnt_rw > 0) {
                if($data[0]->ApproveStatus==1) {
                         $imgcnt='<img src="img/tick.png" title="Approved" style=" margin-top: 10px; width: 15px;" ></img>';
                         $dsp_flag=1;
                }
            }
        
            if($dsp_flag==0 && $cnt_flag==0) {
                $dsp_none='inline'; //---none
                $dsp_tbl='table';  //---none
                $dsp_inl='none';    //---inline
                $cnt_flag=1;
            } else {
                $dsp_none='none';
                $dsp_tbl='none';
                $dsp_inl='inline';
            }
        
            if($flg==0 && $rows->process_flow_sequence!=$prevProcess ) {
                $prevProcess=$rows->process_flow_sequence;
            }
            if($data[0]->ApproveStatus!=1) {
                $flg=1;
            }
            $fixstatus=$this->cp_fn_model->getFixtureNoteByTID();
            $vsource='';
            if($rvessel->Source=='Rightship') {
                $vsource='Rightship';
            } else if($rvessel->Source=='Other source') {
                $vsource='Other source ('.$rvessel->SourceType.')';
            } 
            $line='';
            if($cnt>0) {
                $line='<br>';
            }    
            if($data[0]->name_of_process==1) {
                $tvflag=$data[0]->ApproveStatus;
                $name_of_process='Technical Vetting';
                if($p1==0) {    continue;
                }
            } else if($data[0]->name_of_process==2) {
                $name_of_process='Business vetting approval';
                if($p2==0) {    continue;
                }
            } else if($data[0]->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
                if($p3==0) {    continue;
                }
            } else if($data[0]->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
                if($p4==0) {    continue;
                }
            } else if($data[0]->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($data[0]->name_of_process==6) {
                $name_of_process='Charter party authorization';
            } else if($data[0]->name_of_process==7) {
                $name_of_process='Fixture note authorization';
            } else if($data[0]->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($data[0]->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
                if($p9==0) {    continue;
                }
            } else if($data[0]->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
                if($p10==0) { continue;
                }
            }
            if($data[0]->name_of_process==9) {
                $CPSubject=$this->cp_fn_model->checkCPSubjectLifted();
                if($CPSubject->CH_Task==2 && $CPSubject->ConfirmLift==1) {
                    $liftflag=1;
                }
                $tbl=$line.'<br><header id="view_header"><div class="icons" ><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display:  '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th>Ship owner</th class="padd_th"><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Vetting source</th><th class="padd_th">Changes</th><th class="padd_th">Subject Lifted</th><th class="padd_th">Lifted by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            } else if($data[0]->name_of_process==3 || $data[0]->name_of_process==4) {
                $tbl=$line.'<br><header id="view_header" ><div class="icons" ><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th>Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            } else if($data[0]->name_of_process==10) {
                $tbl=$line.'<br><header id="view_header" ><div class="icons" ><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_none.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th class="padd_th">Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Vetting source</th><th class="padd_th">Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            } else {
                $tbl=$line.'<br><header id="view_header" ><div class="icons" ><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" title="Owner vetting" style="display: '.$dsp_inl.';"><i class="fa fa-2x fa-plus fafa_cls"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: '.$dsp_inl.';" title="Owner vetting"><i class="fa fa-2x fa-minus fafa_cls"></i></a></div><h5><b>'.$name_of_process.' (Seq. '.$data[0]->process_flow_sequence.')'.'</b></h5>'.$imgcnt.'</header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px; display: '.$dsp_tbl.';" ><thead><tr><th class="padd_th">DateTime</th><th class="padd_th">Version</th><th class="padd_th">TID</th><th class="padd_th">Broker</th><th class="padd_th">Ship owner</th><th class="padd_th">Vessel</th><th class="padd_th">IMO</th><th class="padd_th">Vetting source</th><th class="padd_th">Changes</th><th class="padd_th">Approved</th><th class="padd_th">Approved by</th><th class="padd_th">Record status</th><th class="padd_th">Action</th></tr></thead>';
            }
        
            $html .=$tbl;
            
            foreach($data as $row) {
            
                $status='No';
                $rstatus='Discussion';
                if($row->ApproveStatus==1) {
                    $status='Yes';
                    $rstatus='Complete';
                } else {
            
                }
                      $html .='<tr>';
                if($data[0]->name_of_process==1) {
                    if(($rvessel->IMO=='0000000' || $row->process_applies!=4) || $fixstatus==2 || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";                
                    } else {
                                   $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } 
                } else if($data[0]->name_of_process==2 ) {
                    if(($rvessel->IMO=='0000000' || $row->process_applies!=4) || $tvflag!=1 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==9) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $liftflag==1 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==3) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==4) {
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2  || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {    
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                } else if($data[0]->name_of_process==10) {
                                             $SubjectStatus='';
                    if($row->SubjectStatus==1) {
                        $SubjectStatus='Place on subjects';
                    } else if($row->SubjectStatus==2) {
                        $SubjectStatus='Lift subject';
                    } else if($row->SubjectStatus==3) {
                        $SubjectStatus='No subject';
                    }
        
                    if($rvessel->IMO=='0000000' || $row->process_applies!=4 || $fixstatus==2 || ($flg==1 && $rows->process_flow_sequence > $latestProcessNo)) {
                        $link="<a href='javascript: void(0);' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    } else {
                        $link="<a href='javascript: void(0);' onclick=editInvBusinessProcess('".$row->BPVID.'_'.$row->name_of_process."') title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>";
                    }
                }
                                                                                                                $view='-';
                if($row->ViewChage) {
                    $view='<a href="javascript:void(0)" onclick="getChanges('.$row->BPVID.','.$row->Version.')">view</a>';
                }
                                                                                                                $html .='<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
                                                                                                                $html .='<td>'.$row->Version.'</td>';
                                                                                                                $html .='<td>'.$row->TID.'</td>';
                                                                                                                $html .='<td>'.$broker.'</td>';
                                                                                                                $html .='<td>'.$ShipOwner.'</td>';
                                                                                                                $html .='<td>'.$rvessel->VesselName.'</td>';
                                                                                                                $html .='<td>'.$rvessel->IMO.'</td>';
                if($data[0]->name_of_process!=3 || $data[0]->name_of_process!=4) {
                    $html .='<td>'.$vsource.'</td>';
                }
        
                                                                                                                $html .='<td>'.$view.'</td>';
                                                                                                                $html .='<td>'.$status.'</td>';
                                                                                                                $html .='<td>'.$row->ApprovedBy.'</td>';
                                                                                                                $html .='<td>'.$rstatus.'</td>';
                                                                                                                $html .='<td>'.$link.'</td>';
                                                                                                                $html .='</tr>';
            }
            $html .='</table>';
            $cnt++;
            $i++;
            $prevProcess=$rows->process_flow_sequence;
        }
        //echo $html;
        echo $html.'__________'.$i;
    }
    
    public function getVettingTID()
    {
        $data=$this->cp_fn_model->getVettingTID();
        echo json_encode($data);
    }
    
    public function getBusinessVettingBPTID()
    {
        $data=$this->cp_fn_model->getBusinessVettingBPTID();
        echo json_encode($data);
    }
    
    public function saveBusinessVettingSpprove()
    {
        $data=$this->cp_fn_model->saveBusinessVettingSpprove();
        echo $data;
    }
    
    public function checkBusinessApproval()
    {
        $res=$this->cp_fn_model->checkBusinessApproval();
        echo json_encode($res);
    }
    
    public function checkLiftBusinessProcess()
    {
        echo $this->cp_fn_model->checkLiftBusinessProcess();
    }
    
    public function getInviteeUsers()
    {
        $this->load->model('cargo_model', '', true);
    
        $UserIDs=$this->cp_fn_model->getInviteeUsers();
        //print_r($UserIDs); die;
        $id_arr=explode(',', $UserIDs);
        $html='';
        for($i=0; $i<count($id_arr); $i++){
            $user=$this->cp_fn_model->getUserByID($id_arr[$i]);
            $Entity=$this->cargo_model->getEntityById($user->EntityID);
            $html .='<div class="form-group">
		<label class="control-label col-xs-11 col-sm-11 col-md-11 col-lg-3">Invitee user '.($i+1).'</label>
			<div class="col-xs-1 col-lg-1 starmandatory"></div>
		<div class="col-xs-11 col-sm-11 col-md-11 col-lg-6">
			<input style="margin-top: 9px;" class="inv" type="checkbox" name="invUsers[]" id="User'.$id_arr[$i].'" value="'.$id_arr[$i].'" checked >&nbsp;'.$user->FirstName.' '.$user->LastName.' ('.$Entity->EntityName.')
			</div><div class="col-xs-1 fieldinfo">
			</div>
		</div>';
            
        }
        echo $html;
    }
    
    public function checkSubjectNotified()
    {
        $data=$this->cp_fn_model->getSubjectNotifiedByBPVID();
        if($data) {
            if($data->CH_Task==1) {
                echo 1;
            } else if($data->CH_Task==2 && $data->ConfirmLift==2 ) {
                echo 1;
            } else {
                echo 2;
            }
        } else {
            echo 2;
        }
    }
    
    public function saveCpSubjects()
    {
        //print_r($this->input->post()); die;
        $flg=$this->cp_fn_model->saveCpSubjects();
        if($flg) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    public function getSignDocumentData()
    {
        $data=$this->cp_fn_model->getSignDocumentData();
        $UserDetails=$this->cp_fn_model->getUserDetailsByID();
        $CheckOwner=$this->input->get('CheckOwner');
        $signFixtureFinalFlg=$this->input->get('signFixtureFinalFlg');
        $html='';
        $inhtml='';
        $tempMasterID='';
        $i=1;
        $html='{ "aaData": [';
        foreach($data as $row) {
            if($row->FixtureStatus=='2' || $row->CPStatus=='2') {
                $DocStatus="Complete";
            }else {
                $DocStatus="-";
            }
            
            if($row->StatusCharterer=='0') {
                $StatusCharterer="To be signed";
            } else if($row->StatusCharterer=='1') {
                $StatusCharterer="<a href='javascript: void(0);' onclick=getChartererSignatureDetails(".$row->snid.",1)>Signed</a>";
            } else if($row->StatusCharterer=='2') {
                $StatusCharterer="Rejected";
            }
            
            if($row->StatusShipowner=='0') {
                $StatusShipowner="To be signed";
            } else if($row->StatusShipowner=='1') {
                $StatusShipowner="<a href='javascript: void(0);' onclick=getChartererSignatureDetails(".$row->snid.",0)>Signed</a>";
            } else if($row->StatusShipowner=='2') {
                $StatusShipowner="Rejected";
            }
            if($row->DocumentType=='Fixture Note') {
                if($CheckOwner==1) {
                    if($row->StatusCharterer==1 || $UserDetails->SignFixtureFinalFlg==0 || $signFixtureFinalFlg != 1) {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",1' style='margin-bottom: 6px;' disabled>";
                        $action="<a href='javascript: void(0);' title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    } else {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",1' style='margin-bottom: 6px;' >";    
                        $action="<a href='javascript: void(0);' onclick=sign('".$row->snid.",1') title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    }
                } else {
                    if($row->StatusShipowner==1 || $UserDetails->SignFixtureFinalFlg==0 || $signFixtureFinalFlg !=1) {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",1' style='margin-bottom: 6px;' disabled>";
                        $action="<a href='javascript: void(0);' title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    } else {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",1' style='margin-bottom: 6px;' >";    
                        $action="<a href='javascript: void(0);' onclick=sign('".$row->snid.",1') title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    }
                }
            } else if($row->DocumentType=='Charter Party') {
                if($CheckOwner==1) {
                    if($row->StatusCharterer==1 || $UserDetails->SignCPFinalFlg==0) {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",2' style='margin-bottom: 6px;' disabled>";
                        $action="<a href='javascript: void(0);' title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    } else {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",2' style='margin-bottom: 6px;' >";
                        $action="<a href='javascript: void(0);' onclick=sign('".$row->snid.",2') title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";                        
                    }
                } else {
                    if($row->StatusShipowner==1 || $UserDetails->SignCPFinalFlg==0) {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",2' style='margin-bottom: 6px;' disabled>";
                        $action="<a href='javascript: void(0);' title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    } else {
                        $ckbx="<input type='checkbox' name='SignDoc[]' class='chkNumber' value='".$row->snid.",2' style='margin-bottom: 6px;' >";    
                        $action="<a href='javascript: void(0);' onclick=sign('".$row->snid.",2') title='Click here to sign record'><i class='fa fa-sign-in-alt fa_sign'></i></a>";
                    }
                }    
            }
            if($row->DocumentType=='Fixture Note') {
                $dct="<span style='color: red';>".$row->DocumentType."</span>";
                $viewDoc="<a href='javascript: void(0);' onclick='viewFixtureHtml()' title='Click here to view Fixture HTML' ><i class='fa fa-eye fa_html'></i></a>";
            } else {
                $dct="<span style='color: blue'>".$row->DocumentType."</span>";
                $viewDoc="<a href='javascript: void(0);' onclick='viewCharterHtml()' title='Click here to view Charter HTML' ><i class='fa fa-eye fa_html'></i></a>";
            }
            if($row->LastUpdated) {
                $LastUpdated=date('d-m-Y H:i:s', strtotime($row->LastUpdated));
            } else {
                $LastUpdated='-';
            }
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$row->MasterID.'","'.$row->TID.'","'.$row->OwnerName.'","'.$row->SpOwnerName.'","'.$dct.'","'.$DocStatus.'","'.$StatusCharterer.'","'.$StatusShipowner.'","'.$LastUpdated.'","'.$action.'&nbsp;&nbsp;'.$viewDoc.'"],';
            $i++;
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html; 
    }
    
    
    
    public function getDocumentCpDetails()
    {
        $cpdetail=$this->cp_fn_model->getDocumentCpVersion();
        $FixtureDetail=$this->cp_fn_model->getFixtureVersion();
        $ownerrole=$this->cp_fn_model->getRecordOwnerRole();
        $inviteeowner=$this->cp_fn_model->getInviteeShipOwner();
        $data['CharterHash']=$cpdetail->CharterHash;
        $data['documentationid']=$cpdetail->DocumentationID;
        $data['fixtureid']=$FixtureDetail->FixtureID;
        $data['fixture_hash']=$FixtureDetail->FixtureHash;
        $data['version']=$cpdetail->DocumentationVersion;
        $data['fixture_version']=$FixtureDetail->FixtureVersion;
        $data['role']=$ownerrole->Description;
        if($inviteeowner->E1_ID !=$inviteeowner->DisponentOwnerID) {
            $data['shipowner']=$inviteeowner->E2_EntityName;
            $data['broker']=$inviteeowner->E1_EntityName;    
        } else {
            $data['shipowner']=$inviteeowner->E2_EntityName;
            $data['broker']='';
        }
        $data['cp_date']=date('d-m-Y H:i:s', strtotime($cpdetail->UserDate));
        $data['fixture_date']=date('d-m-Y H:i:s', strtotime($FixtureDetail->UserDate));
        echo json_encode($data);
        
    }
    
    public function getUserDetailById()
    {
        $data=$this->cp_fn_model->getUserDetailById();
        echo json_encode($data);
    }
    
    public function assignOtpToDigitalSignature()
    {
        $data=$this->cp_fn_model->assignOtpToDigitalSignature();
        echo json_encode($data);
    }
    
    public function saveSignatureDetails()
    {
        $data=$this->cp_fn_model->saveSignatureDetails();
        echo $data;
    }
    
    public function getSignatureDetailById()
    {
        $data=$this->cp_fn_model->getSignatureDetailById();
        echo json_encode($data);
    }
    
    
    public function getSignatureDetailByIdVerifyHash()
    {
        $data['db']=$this->cp_fn_model->getSignatureDetailByIdVerifyHash();
        $IpfsTransaction=$this->cp_fn_model->getIPFSHashTransationByTID($data['db'][0]->TID);
        $TransactionHash=$IpfsTransaction->transactionHash;
        $ipfsHash=$IpfsTransaction->ipfsHash;
        /*-----------------ipfs----------------*/
        $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
        $ch = curl_init($url);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $mystring = curl_exec($ch);
        $chh2=strip_tags($mystring);
        $IpfsFixtureHash=hash(HASH_ALGO, $chh2);
        $data['IpfsFixtureHash']=$IpfsFixtureHash;
        /*-----------------/ipfs----------------*/
        
        /*-----------------blockchain----------------*/
        $data1['transactions'] = array($TransactionHash); 
        
        $data_string = json_encode($data1); 
        //print_r($data_string);die;
        $url=BLOCK_CHAIN_URL.'getDocTransactionDetailsDocOnly/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(       
            'Content-Type: application/json',        
            'Content-Length: ' . strlen($data_string))   
        );
        $BlocDetails = curl_exec($ch);
        //print_r($BlocDetails);die;
        $BlocDetailsArr=json_decode($BlocDetails);
        $data['BlocDetailsArr']=$BlocDetailsArr;
        $data['TransactionHash']=$TransactionHash;
        /*-----------------/blockchain----------------*/
        
        echo json_encode($data);
    }
    
    public function getFixtureHtmlAuctionID()
    {
        $this->load->model('cargo_model', '', true);
        //$decode_html=html_entity_decode($encode_html);
        //echo 'text'; die;
        $row=$this->cp_fn_model->getFixtureAuctionID();
        //print_r($row->HeaderContent);die;
        $row1=$this->cp_fn_model->getFixtureNoteByAuctionID();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($row->RecordOwner);
        
        //print_r($document); die;
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$document->Logo, 3600);
        $data['Title']=$document->DocumentTitle;
        $data['Logo']=$url;
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->FixtureVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['OwnerEntityName']=$entity_detail->EntityName;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        $data['fixtureID']=$row->FixtureID;
        $data['data1']=$row1;
        $data['HeaderContent']=$row->HeaderContent;
        
        echo json_encode($data);
    }
    
    public function getDocumentationHTMLByAuctionID()
    {
        $row=$this->cp_fn_model->getDocumentationHTMLByAuctionID();
        $row1=$this->cp_fn_model->getDocumentationNoteAuctionID();
        $document=$this->cp_fn_model->getFixtureNoteLogo();
        $data['Clauses']=$this->cp_fn_model->getDocumentationClausesByID($row->DocumentationID);
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$row->CharterPartyPdf, 3600);
        $nar=explode("?", $url);
        $doc=current($nar);
        $data['docContent']='<iframe src="http://docs.google.com/gview?url='.$doc.'&embedded=true" style="width:100%; height: 100%;" frameborder="0"></iframe>';
        
        
        $url1 = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$document->Logo, 3600);
        $data['Title']=$document->DocName;
        $data['Logo']=$url1;
        
        $data['EditableFlag']=$row->EditableFlag;
        $data['CharterPartyPdf']=$row->CharterPartyPdf;
        $data['InviteeConfirmation']=$row->InviteeConfirmation;
        $data['OwnerConfirmation']=$row->OwnerConfirmation;
        $data['version']=$row->DocumentationVersion;
        $data['AuctionID']=$row->AuctionID;
        $data['RecordOwner']=$row->RecordOwner;
        $data['UserName']=$row->UserName;
        $data['UserID1']=$row->UserID1;
        $data['UserName1']=$row->FirstName.' '.$row->LastName;
        $data['LoginID']=$row->LoginID;
        $data['Status']=$row->Status;
        $data['DocumentationID']=$row->DocumentationID;
        if($row->ClauseType==1) {
            $data['data1']='';
        }else{
            $data['data1']=$row1;
        }
        echo json_encode($data);
        
    }
    
    public function getCounterPartyRiskMessage()
    {
        $data=$this->cp_fn_model->getCounterPartyRiskMessage();
        echo json_encode($data);
    }
    
    public function saveCounterPartyRisk()
    {
        $flg=$this->cp_fn_model->saveCounterPartyRisk();
        if($flg) {
            echo 1;
        }else {
            echo 0;
        }
    }
    
    public function getEntityInviteeStatus()
    {
        $data=$this->cp_fn_model->getEntityInviteeStatus();
        echo json_encode($data);
    }
    
    public function getCounterPartyRiskContent()
    {
        $data=$this->cp_fn_model->getCounterPartyRiskContent();
        echo json_encode($data);
    }
    
    public function getCounterPartyRiskDocuments()
    {
        $data=$this->cp_fn_model->getCounterPartyRiskDocuments();
        echo json_encode($data);
    }
    
    public function view_cp_document_file()
    {
        $filename=$this->cp_fn_model->get_cp_document_file();
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
    
    public function download_cp_document_file()
    { 
        $this->load->helper('download');
        $filename=$this->cp_fn_model->get_cp_document_file();
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
        $farr=explode("_____", $filename);
        force_download($farr[1], $data);
    }
    
    public function delete_counter_party_document()
    {
        $id=$this->input->post('id');
        $flg=$this->cp_fn_model->delete_counter_party_document($id);
        if($flg) {
            echo 1;
        }else{
            echo 2;
        }
    }
    
    public function getComplianceRiskMessage()
    {
        $data=$this->cp_fn_model->getComplianceRiskMessage();
        echo json_encode($data);
    }
    
    public function getBusinessVettingMessage()
    {
        $data=$this->cp_fn_model->getBusinessVettingMessage();
        echo json_encode($data);
    }
    
    public function getTechnicalVettingMessage()
    {
        $data=$this->cp_fn_model->getTechnicalVettingMessage();
        echo json_encode($data);
    }
    
    public function getComplianceRiskContent()
    {
        $data=$this->cp_fn_model->getComplianceRiskContent();
        echo json_encode($data);
    }
    
    public function getComplianceRiskDocuments()
    {
        $data=$this->cp_fn_model->getComplianceRiskDocuments();
        echo json_encode($data);
    
    }
    
    public function saveComplianceRisk()
    {
        $flg=$this->cp_fn_model->saveComplianceRisk();
        if($flg) {
            echo 1;
        }else {
            echo 0;
        }
    }
    
    public function getNotifiedSubject()
    {
        $sub=$this->cp_fn_model->getSubjectNotifiedByBPVID();
        
        $msg=$this->cp_fn_model->checkSubjectMessage();
        //print_r($data); die;
        if($sub) {
            $data['flg']=1;
            $data['CH_Task']=$sub->CH_Task;
            $data['NotifySubject']=$sub->NotifySubject;
            $data['GeneralComment']=$sub->GeneralComment;
            $data['ConfirmLift']=$sub->ConfirmLift;
        } else {
            $data['flg']=0;
        }
        $data['show_in_process']=$msg->show_in_process;
        $data['message_text']=$msg->message_text;
        
        echo json_encode($data);
    }
    
    public function checkLiftSubjectApprove()
    {
        
        $data=$this->cp_fn_model->authenticateUser1();
        $UserID=$this->input->post('UserID');
        $user_list=explode(",", $data);
        $flag=0;
        for($i=0;$i<count($user_list);$i++) {
            if($user_list[$i]==$UserID) {
                $flag=1;
            }
        }
        echo $flag;
    }
    
    public function checkCounterPartyApprove()
    {
        
        $data=$this->cp_fn_model->authenticateUser1();
        //print_r($data); die;
        $UserID=$this->input->post('UserID');
        $user_list=explode(",", $data);
        $flag=0;
        for($i=0;$i<count($user_list);$i++) {
            if($user_list[$i]==$UserID) {
                $flag=1;
            }
        }
        echo $flag;
    }
    
    public function checkComplianceApprove()
    {
        
        $data=$this->cp_fn_model->authenticateUser1();
        $UserID=$this->input->post('UserID');
        $user_list=explode(",", $data);
        $flag=0;
        for($i=0;$i<count($user_list);$i++) {
            if($user_list[$i]==$UserID) {
                $flag=1;
            }
        }
        echo $flag;
    }
    
    public function getSignDocumentVerifyData()
    {
        $data=$this->cp_fn_model->getSignDocumentDataVerify();
        $html='';
        $inhtml='';
        $html='{ "aaData": [';
        foreach($data as $row) {
            
            if($row->FixtureStatus=='2' || $row->CPStatus=='2') {
                $DocStatus="Complete";
            }else {
                $DocStatus="-";
            }
            if($row->DocumentType=='Fixture Note') {
                $StatusCharterer="<a href='#' onclick=getChartererSignatureDetails(".$row->snid.",1)>Show</a>";    
            }
            if($row->DocumentType=='Charter Party') {
                $StatusCharterer="<a href='#' onclick=getChartererSignatureDetailsDocument(".$row->snid.",1)>Show</a>";    
            }
            
            $verify="<a href='#' onclick=verifyHash(".$row->snid.",1)>Verify document</a>";
            $verifylog="<a href='#' onclick=verifyHashLog(".$row->snid.",1)>verification log</a>";
            $IpfsVerify="<a href='#' onclick=verifyIpfsHash(".$row->snid.",1)>Verify document</a>";
            $IpfsVerifylog="<a href='#' onclick=verifyIpfsHashLog(".$row->snid.",1)>verification log</a>";
            $BcVerify="<a href='#' onclick=verifyBcHash(".$row->snid.",1)>Verify document</a>";
            $BcVerifylog="<a href='#' onclick=verifyBcHashLog(".$row->snid.",1)>verification log</a>";
            $UserVerifyRo="<a href='#' onclick=UserVerify(".$row->snid.",1)>User Verify (RO)</a>";
            $UserVerifyInvitee="<a href='#' onclick=UserVerify(".$row->snid.",0)>User Verify (Invitee)</a>";
            
            if($row->DocumentType=='Fixture Note') {
                $dct="<span style='color: red';>".$row->DocumentType."</span>";
                
            } else {
                $dct="<span style='color: blue'>".$row->DocumentType."</span>";
                
            }
            
            $inhtml .='["'.$row->MasterID.'","'.$row->TID.'","'.$row->OwnerName.'","'.$row->SpOwnerName.'","'.$dct.'","'.$StatusCharterer.'","'.$verify.'","'.$verifylog.'","'.$IpfsVerify.'","'.$IpfsVerifylog.'","'.$BcVerify.'","'.$BcVerifylog.'","'.$UserVerifyRo.'","'.$UserVerifyInvitee.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html; 
    
    }
    
    public function verifyDocument()
    {
        $data=$this->cp_fn_model->verifyDocument();
        echo $data;
    }
    
    public function verifyIpfsDocument()
    {
        $data=$this->cp_fn_model->verifyIpfsDocument();
        echo $data;
    }
    
    public function verifyBlockchainDocument()
    {
        $data=$this->cp_fn_model->verifyBlockchainDocument();
        echo $data;
    }
    
    public function userVerify()
    {
        $data=$this->cp_fn_model->userVerify();
        echo json_encode($data);
    }
    
    public function getGenetatedPublicKey()
    {
        extract($this->input->post());
        $data['sig']=array('R'=>$sdr,'S'=>$sds,'V'=>$sdv);
        $data['hash']=$DocumentHash;
        
        $data_string = json_encode($data); 
        $url=BLOCK_CHAIN_URL.'getKeyFromSig/';
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
        echo $result;
    }
    
    public function getUserDetailByUserId()
    {
        extract($this->input->post());
        $UserDetail=$this->cp_fn_model->getUserCompanyDetailById($UserIDSign);
        echo json_encode($UserDetail);
    }
    
    public function getVerifiedHash()
    {
        $this->load->model('cargo_model', '', true);
        $ShipRecord=$this->cp_fn_model->getVerifiedHash();
        
        $ro=$this->cargo_model->getEntityById($ShipRecord->RecordOwner);
        $RecordOwner=$ro->EntityName;
        $so=$this->cargo_model->getEntityById($ShipRecord->ShipOwner);
        $ShipOwner=$so->EntityName;
        $data=$this->cp_fn_model->getHashVerifiedLog();
        $html='';
        foreach($data as $row) {
            $UserDetail=$this->cp_fn_model->getUserByID($row->UserID);
            $html .='<tr>';
            $html .='<td>'.date('d-M-Y H:i:s', strtotime($row->UserDate)).'</td>';
            $html .='<td>'.$UserDetail->LoginID.'</td>';
            $html .='<td>'.$UserDetail->FirstName.' '.$UserDetail->LastName.'</td>';
            $html .='<td>'.$RecordOwner.'</td>';
            $html .='<td>'.$ShipOwner.'</td>';
            if($row->Status==1) {
                $html .='<td><img src="img/right.png" style="width: 20px;"></img></td>';
            } else {
                $html .='<td><img src="img/cancel.png" style="width: 20px;"></img></td>';    
            }
            $html .='</tr>';
        }
        echo $html;
    }
    
    
    
    public function saveCpSubjectShipowner()
    {
        $flg=$this->cp_fn_model->saveCpSubjectShipowner();
        if($flg) {
            echo 1;
        }else {
            echo 0;
        }
    
    }
    
    public function authenticateUserInv()
    {
        $data=$this->cp_fn_model->authenticateUserInv();
        if(count($data)) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    public function getCpSubjectInvContent()
    {
        $data=$this->cp_fn_model->getCpSubjectInvContent();
        echo json_encode($data);
    }
    
    public function getCpSubjectInvDocuments()
    {
        $data=$this->cp_fn_model->getCpSubjectInvDocuments();
        echo json_encode($data);
    
    }
    
    public function getCpSubjectInvMessage()
    {
        $data=$this->cp_fn_model->getCpSubjectInvMessage();
        echo json_encode($data);
    }
    
    public function checkUserInvPermission()
    {
        
        $data=$this->cp_fn_model->checkUserInvPermission();
        if(count($data)) {
            echo 1;
        } else {
            echo 0;
        }
    }
    
    public function getFixtureAllChangesById()
    {
        $data['fixture']=$this->cp_fn_model->getFixtureAllChangesById();
        echo json_encode($data);
        
    }
    
    public function getDocumentationByIdHtmlIpfs()
    {
        $DocumentationID=$this->input->post('DocumentationID');
        $data=$this->cp_fn_model->getIpfsHashByDocumentationID($DocumentationID);
        $ipfsHash=$data->ipfsHash;
        //echo $ipfsHash;die;
        if($ipfsHash) {
            if($data->EditableFlag==1) {
                $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
                $ch = curl_init($url);     
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
                $mystring = curl_exec($ch);
                echo $mystring;    
            } else {
                $url=IPFS_CHAIN_URL.'getIpfsDocumentPdf/'.$ipfsHash;
                $html='<iframe src="'.$url.'" style="width:100%; height: 100%;" frameborder="0"></iframe>';
                echo $html;    
            }
            
            
        } else {
            echo '';
        }
    }
    
    
    public function getSignatureDetailByIdVerifyHashDocument()
    {
        $data['db']=$this->cp_fn_model->getSignatureDetailByIdVerifyHash();
        $IpfsTransaction=$this->cp_fn_model->getIPFSHashTransationByTIDDocument($data['db'][0]->TID);
        $TransactionHash=$IpfsTransaction->transactionHash;
        $ipfsHash=$IpfsTransaction->ipfsHash;
        /*-----------------ipfs----------------*/
        $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
        $ch = curl_init($url);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $mystring = curl_exec($ch);
        if($IpfsTransaction->EditableFlag==1) {
            $chh2=strip_tags($mystring);
            $IpfsFixtureHash=hash(HASH_ALGO, $chh2);
        } else {
            $IpfsFixtureHash=hash(HASH_ALGO, $mystring);    
        }
        $data['IpfsFixtureHash']=$IpfsFixtureHash;
        /*-----------------/ipfs----------------*/
        
        /*-----------------blockchain----------------*/
        $data1['transactions'] = array($TransactionHash); 
        
        $data_string = json_encode($data1); 
        //print_r($data_string);die;
        $url=BLOCK_CHAIN_URL.'getDocTransactionDetailsDocOnly/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt(
            $ch, CURLOPT_HTTPHEADER, array(       
            'Content-Type: application/json',        
            'Content-Length: ' . strlen($data_string))   
        );
        $BlocDetails = curl_exec($ch);
        //print_r($BlocDetails);die;
        $BlocDetailsArr=json_decode($BlocDetails);
        $data['BlocDetailsArr']=$BlocDetailsArr;
        $data['TransactionHash']=$TransactionHash;
        /*-----------------/blockchain----------------*/
        
        echo json_encode($data);
    }
    
    
    public function createNewFixtureNote()
    {
        $invitee_confirm=$this->input->post('invitee_confirm');
        $owner_confirm=$this->input->post('owner_confirm');
        $data1=$this->cp_fn_model->createNewFixtureNote();
        $data2='';
        if($invitee_confirm==2 & $owner_confirm==2) {
            $data2=$this->cp_fn_model->getFixtureNoteByTidInvOwner();
            $this->cp_fn_model->PlaceBusinessProcessAfterFixureFinal();
        }
        echo $data1.'___________________'.$data2;  
        
    }
    
    public function saveCpcodeFieldnameFieldvalue()
    {
        $data=$this->cp_fn_model->saveCpcodeFieldnameFieldvalue();
    }
    
    public function getDelRemoveContent()
    {
        $data=$this->cp_fn_model->getDelRemoveContent();
        echo $data;
    }
    
    public function createNewDocumentationNote()
    {
        $no_of_count=$this->cp_fn_model->getNoOfCount();
        $deldata=$this->cp_fn_model->getDelRemoveContent();
        $chcnt=substr_count($deldata, "<charterpartyspan");
        //print_r($chcnt); die;
        if($chcnt == $no_of_count) {
            $data=$this->cp_fn_model->createNewDocumentationNote();
            echo $data; 
        } else {
            echo 3;
        }
    }
    
    public function checkFixNoteComplete()
    {
        $row=$this->cp_fn_model->checkFixNoteComplete();
        echo $row->Status;
    }
    
    public function getRecordownerInviteeByTid()
    {
        $data=$this->cp_fn_model->getRecordownerInviteeByTid();
        echo json_encode($data);
    }
    
    public function getInviteeStatusByClauseid()
    {
        $data=$this->cp_fn_model->getInviteeStatusByClauseid();
        //print_r($data); die;
        echo $data->InvStatus;
    }
    
    public function saveVettingApprove()
    {
        $data=$this->cp_fn_model->saveVettingApprove();
        echo $data;
    }
    
    public function getApproveVetting()
    {
        $data=$this->cp_fn_model->getApproveVetting();
        echo json_encode($data);
    }
    
    public function businessProcessTechnicalVetting()
    {
        $data=$this->cp_fn_model->businessProcessTechnicalVetting();
        echo $data;
    }
    
    public function authenticateUser()
    {
        $data=$this->cp_fn_model->authenticateUser();
        $UserID=$this->input->post('UserID');
        $user_list=explode(",", $data);
        $flag=0;
        for($i=0;$i<count($user_list);$i++) {
            if($user_list[$i]==$UserID) {
                $flag=1;
            }
        }
        echo $flag;
    }
    
    public function getBettingByBpvid()
    {
        $data=$this->cp_fn_model->getBettingByBpvid();
        echo json_encode($data);
    }
    
    public function getChangesByBpvid()
    {
        $data['chngs']=$this->cp_fn_model->getChangesByBpvid()->ViewChage;
        $dt=$this->cp_fn_model->getChangesByBpvid()->UserDate;
        $data['UserDate']=date('d-M-Y H:i:s', strtotime($dt));
        echo json_encode($data);
    }
    
    public function getAllDocumentationChangesById()
    {
        $data1=$this->cp_fn_model->getAllDocumentationChangesById();
        
        $varsion='';
        $changes='';
        foreach($data1 as $row){
            $varsion=$row->ClauseVersion;
            if($row->DeletedClauseNote =='' && $row->AddedClauseNote =='' && $row->ChangeClauseStatus =='') {
                continue;
            }
            $changes .=$row->ClauseName.'<br><br>';
            
            if($row->DeletedClauseNote !='') {
                $changes .='Deleted content';
                $changes .=$row->DeletedClauseNote;
                $changes .='<br>';
            }
            if($row->AddedClauseNote !='') {
                $changes .='Added content';
                $changes .=$row->AddedClauseNote;
                $changes .='<br>';
            }
            if($row->ChangeClauseStatus !='') {
                $changes .=$row->ChangeClauseStatus;
                $changes .='<br>';
            }
            $changes .='<hr style="background-color: black; height: 2px;"><br/>';
        }
        $varArr=explode(' ', $varsion);
        $data['var1']=$varArr[1];
        $data['changes']=$changes;
        echo json_encode($data);
    }
    
    public function checkUserPermission()
    {
        $data['user']=$this->cp_fn_model->getUserPermissions();
        $data['quote']=$this->cp_fn_model->getFreightQuoteRow();
        echo json_encode($data);
    }
    
    public function getFixtureTableByFixtureId()
    {
        $data=$this->cp_fn_model->getFixtureTableByFixtureId();
        echo json_encode($data);
    }
    
    public function getAllClausesText()
    {
        $data=$this->cp_fn_model->getAllClausesText();
        $logoData=$this->cp_fn_model->getLogo();
        $html='';
        if($logoData) {
            $fileName=$logoData->Logo;
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);

            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$fileName, 3600);
            $html1='';
            if($logoData->LogoAlign==1) {
                $html1 .='<br><img src="'.$url.'" style="width: 15%; margin-top: -3%;"></img>';
            }
            if($logoData->LogoAlign==2) {
                $html1 .='<br><center><img src="'.$url.'" style="width: 15%; margin-top: -3%;" ></img></center>';
            }
            if($logoData->LogoAlign==3) {
                $html1 .='<br><p><img src="'.$url.'" style="width: 15%; margin-left: 80%; margin-top: -3%;"></img></p>';
            }
        }
        $html .=$html1;
        $html .='<br/><br/><p style="font-size: 15px;">'.$logoData->DocName.'</p>';
        if($logoData->ClauseType !=1 ) {
            $html .='<hr><br/>';
            $html .='<h6><b>INDEX TO CLAUSES</b></h6>';
            foreach($data as $row) {
                $html .='<p>'.$row->ClauseNo.'.  '.$row->CaluseName.'</p>';    
            }
            $html .='<br/><hr><br/><div class="page-break"></div>';
        }
        $html .='<br><table>';
        $html .='<tbody>';
        foreach($data as $row) {
            $clause_text=$this->cp_fn_model->getClausesTextByID($row->ClauseID);
            $html .='<tr><td>';
            $html .=$clause_text;
            $html .='</td></tr>';
            $html .='<tr><td></td><tr>';
            $html .='<tr><td></td><tr>';
            $html .='<tr><td></td><tr>';
        }
        $html .=' </tbody>';
        $html .=' </table>';
        echo $html;
    }
    
    public function htmlDocumentationDownloadHistory()
    {
        include_once APPPATH.'third_party/mpdf.php';        
        $data=$this->cp_fn_model->getDocumentationChangesById();
        
        $html='';
        foreach($data as $row){
            if($row->DeletedClauseNote =='' && $row->AddedClauseNote =='' && $row->ChangeClauseStatus =='' ) {
                continue;
            }
            $html .=$row->ClauseName.'<br>';
            if($row->DeletedClauseNote !='') {
                $html .='<B>Deleted content</B><br>';
                $html .=$row->DeletedClauseNote.'<br>';
            }
            if($row->AddedClauseNote !='') {
                $html .='<B>Added content</B><br>';
                $html .=$row->AddedClauseNote.'<br>';
            }
            if($row->ChangeClauseStatus !='') {
                $html .=$row->ChangeClauseStatus.'<br>';
            }
            $html .='<hr style="background-color: black; height: 2px;" >';
        }
        $pdfFilePath = "CharterPartyHistory.pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
        
    }
    
    public function htmlDocumentationDownloadHistoryAll()
    {
        include_once APPPATH.'third_party/mpdf.php';        
        $data1=$this->cp_fn_model->getAllDocumentationChangesById();
        
        $changes='';
        foreach($data1 as $row){
            $varsion=$row->ClauseVersion;
            if($row->DeletedClauseNote =='' && $row->AddedClauseNote =='' && $row->ChangeClauseStatus =='') {
                continue;
            }
            $changes .=$row->ClauseName.'<br><br>';
            
            if($row->DeletedClauseNote !='') {
                $changes .='Deleted content';
                $changes .=$row->DeletedClauseNote;
                $changes .='<br>';
            }
            if($row->AddedClauseNote !='') {
                $changes .='Added content';
                $changes .=$row->AddedClauseNote;
                $changes .='<br>';
            }
            if($row->ChangeClauseStatus !='') {
                $changes .=$row->ChangeClauseStatus;
                $changes .='<br>';
            }
            $changes .='<hr style="background-color: black; height: 2px;"><br/>';
        }
        //echo $html; die;
        $pdfFilePath = "CharterPartyHistoryAll.pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($changes);
        $pdf->Output($pdfFilePath, "D");
        
    }
    
    public function getBusinessProcessByAuctionId()
    {
        $data=$this->cp_fn_model->getBusinessProcessByAuctionId();
        echo json_encode($data);
    }
    
    public function authenticateUser1()
    {
        $UserID=$this->input->post('UserID');
        $data=$this->cp_fn_model->authenticateUser1();
        $user_list=explode(",", $data);
        $flag=0;
        for($i=0;$i<count($user_list);$i++) {
            if($user_list[$i]==$UserID) {
                $flag=1;
            }
        }
        echo $flag;
    }
    
    
    
    
}

