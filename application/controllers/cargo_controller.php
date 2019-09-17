<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
header('Access-Control-Allow-Origin: *');
    
class cargo_controller extends CI_Controller
{
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
        $this->load->model('cargo_model', '', true); 
        
    } 
    
    public function setup__2__charter()
    {
        $data['query']=$this->cargo_model->get_all_vcps();
        $datamenu['active']=2;
        $this->load->view('include/header');
        $this->load->view('include/topheader');
        $this->load->view('include/leftmenu2', $datamenu);
        $this->load->view('setup/setup__2charter', $data);
        $this->load->view('include/footer');
    }
    
    public function getCharterParty()
    {
        
        $res=$this->cargo_model->get_all_vcps();
        
        $data_arr=array();
        $return_arr = array();
        foreach($res as $row){
            if ($row->VoyageCharterType==59) {$VoyageCharterType="Voyage";
            } else {$row->VoyageCharterType="Time";
            }  
            if ($row->CharterPartyType==33) {$CharterPartyType="Cost";
            } else {$CharterPartyType="Revenue";
            } 
            $data_arr['label']='Ref: '.$row->name.' || C/P Type: '.$CharterPartyType.' || CPDate: '.date('d-m-Y', strtotime($row->DateTime)).' || ID: '.$row->ID;
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
    
    public function get__Data()
    {
        $data=$this->cargo_model->get__CharterData();
        echo json_encode($data);
    }
    
    public function save__1__role()
    {    
        $data=$this->cargo_model->save__1__role();
        if($data) {
            $sendMessage=$this->cargo_model->sendMessage();
            echo 1;
        } else {
            echo 2;
        }
    }

    
    
    public function save__2__charter()
    {    
        print_r($_POST); die();
        $data['query']=$this->cargo_model->save__1__role();
        $this->setup__2__charter();
    }
    
    public function save_auction()
    {
        $inval=$this->input->post();
        $data1=$this->cargo_model->getlinenum();
        $savenew=$this->cargo_model->addauctionDetails($data1->linenum);
        $this->cargo_model->upload_image($data1->linenum);
        if($savenew) {
            redirect('index.php/csetup/setup__3__cargo');
        }else{
            $this->message->_setSuccess('Charter Insert Error!');
        } 
    }
    
    public function save_cargo()
    {
        //print_r($_POST); die;
        $data1=$this->cargo_model->getlinenum();
        if($data1->linenum=='') {    
            $linenum=1;
        } else {    
            $linenum=$data1->linenum+1;    
        }
        
        $savenew=$this->cargo_model->addauctionDetails($data1->linenum);
        if($savenew) {
            $this->cargo_model->upload_image($data1->linenum);
            $data['linenum']=$linenum;
            $data['flg']=1;
        } else {
            $data['flg']=2;
        }
        echo json_encode($data);
        
    }
    
    public function updateCargo()
    {
        $linenum=$this->input->post('id');
        $AuctionID=$this->input->post('AuctionID');
        //print_r($linenum); die;
        $oldData=$this->cargo_model->getCargoDetails();
        $expDataOld=$this->cargo_model->getLoadportExceptedPeriodDetailsByCargoID($oldData->CargoID);
        $tenderingOldData=$this->cargo_model->getLoadportNORTenderDetailsByCargoID($oldData->CargoID);
        $acceptOldData=$this->cargo_model->getLoadportNORAcceptanceDetailsByCargoID($oldData->CargoID);
        $officeOldData=$this->cargo_model->getLoadportOfficeHoursDetailsByCargoID($oldData->CargoID);
        $laytimeOldData=$this->cargo_model->getLoadportLaytimeDetailsByCargoID($oldData->CargoID);
        $savenew=$this->cargo_model->updateauctionDetails();
        //print_r($savenew); die;
        if($savenew) {
            $newData=$this->cargo_model->getCargoDetails();
            $expDataNew=$this->cargo_model->getLoadportExceptedPeriodDetailsByCargoID($newData->CargoID);
            $tenderingNewData=$this->cargo_model->getLoadportNORTenderDetailsByCargoID($newData->CargoID);
            $acceptNewData=$this->cargo_model->getLoadportNORAcceptanceDetailsByCargoID($newData->CargoID);
            $officeNewData=$this->cargo_model->getLoadportOfficeHoursDetailsByCargoID($newData->CargoID);
            $laytimeNewData=$this->cargo_model->getLoadportLaytimeDetailsByCargoID($newData->CargoID);
            $this->cargo_model->saveCargoMessage($oldData, $newData, $expDataOld, $expDataNew, $tenderingOldData, $tenderingNewData, $acceptOldData, $acceptNewData, $officeOldData, $officeNewData, $laytimeOldData, $laytimeNewData);
            $this->cargo_model->upload_image_edit($linenum, $AuctionID);
            echo 'Updated';
        } else {
            echo 'Not Updated';
        }
        
    }
    
    public function setup__3__cargo()
    {
        $data['dummy']=1;
        $datamenu['active']=3;
        $data['Details']=$this->cargo_model->getauctionData();
        
        $this->load->view('include/header');
        $this->load->view('include/topheader');
        $this->load->view('include/leftmenu2', $datamenu);
        $this->load->view('setup/setup__3cargo', $data);
        $this->load->view('include/footer');
    }
    
    public function add_auction()
    {
        $data['dummy']=1;
        $datamenu['active']=3;
        $data['Details']=$this->cargo_model->getauctionData();
        
        $this->load->view('include/header');
        $this->load->view('include/topheader');
        $this->load->view('include/leftmenu2', $datamenu);
        $this->load->view('setup/setup_5cargo', $data);
        $this->load->view('include/footer');
        
    }
    
    public function get_cargo_data()
    {
        $data=$this->cargo_model->getauctionData();
        $editmode=$this->input->post('editmode');
        $html='';
        $i=1;
        foreach($data as $row) {
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $Disports='';
            if(count($DisportRslt)> 0) {
                foreach($DisportRslt as $dr){
                    $Disports .=$dr->dspPortName.', ';
                }
            } else {
                $Disports=$row->dpDescription;
            }
            $Disports=trim($Disports, ", ");
            
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y H:i:s', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y H:i:s', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='';
            }
            $cargoQty=number_format($row->CargoQtyMT);
            if($editmode==1) {    
                $edit="<a href='javascript: void(0);' onclick='editCargo1(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";
            }
            if($editmode==0) {    
                $edit="<a href='javascript: void(0);' onclick='editCargo(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";
            }
            $clone="<a href='javascript: void(0);' onclick='cloneCargo(".$row->LineNum.")'  title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>&nbsp;&nbsp;";
            $delete="<a href='javascript: void(0);' onclick='deleteCargo(".$row->LineNum.")' title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>&nbsp;&nbsp;";
            $h_view="<a href='javascript: void(0);' onclick='getCargoDetails(".$row->LineNum.")' title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $html .='<tr>
				<td>'.$i.'</td>
				<td>'.$row->Code.'</td>
				<td>'.$cargoQty.'</td>
				<td>'.$row->lpDescription.'</td>
				<td>'.$Disports.'</td>
				<td>'.$LpLaycanStartDate.'</td>
				<td>'.$LpLaycanEndDate.'</td>
				<td>'.$edit.' '.$delete.' '.$clone.' '.$h_view.'</td>
				</tr>';
            $i++;
        }
        echo $html.'_____'.count($data).'_____'.$data[0]->LineNum;
    }
    
    public function getAuctionData()
    {
        $data=$this->cargo_model->getAuctionSetupMaster();
        //print_r($data);die;
        $html='';
        $i=1;
        //print_r($data); die;
        $LpLaycanStartDate='';
        $LpLaycanEndDate='';
        $auctionStatus='';
        foreach($data as $row) {
            $flg=0;
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $Disports='';
            if(count($DisportRslt)> 0) {
                foreach($DisportRslt as $dr){
                    $Disports .=$dr->dspPortName.', ';
                }
            } else {
                $Disports=$row->dpdescription;
            }
            
            $Disports=trim($Disports, ", ");
            
        
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='';
            }
            if($row->auctionStatus=='A') {
                $auctionStatus='Activated';
            }else if($row->auctionStatus=='PNR') {
                $auctionStatus='Pending Release';
            }else if($row->auctionStatus=='W') {
                $auctionStatus='Withdrawn';
            }else if($row->auctionStatus=='R') {
                $auctionStatus='release';
            }else {
                $auctionStatus='Pending';
            }
            $status='';
            if($row->InvPriorityStatus=='P1') {
                $status='Preferred invitee 1';
            }
            if($row->InvPriorityStatus=='P2') {
                $status='Preferred invitee 2';
            }
            if($row->InvPriorityStatus=='P3') {
                $status='Preferred invitee 3';
            }
            if($row->InvPriorityStatus=='P0') {
                $status='Global invitation';
            }
            $estfrt='';
            $indexfrt='';
            if($row->Estimate_By=='mt') {
                $estfrt=$row->Estimate_mt;
            }
            if($row->Estimate_By=='range') {
                $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
            }
            if($row->Estimate_Index_By=='mt') {
                $indexfrt=$row->Estimate_Index_mt;
            }
            if($row->Estimate_Index_By=='range') {
                $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
            }
            $html .='<tr>
			<td><input class="chkNumber" type="checkbox" name="arr_auction_ids[]" value="'.$row->AuctionID.'"> <input type="hidden" class="linenum" value="'.$row->LineNum.'"></td>
				<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>
				<td>'.$auctionStatus.'</td>
				<td>'.$status.'</td>
				<td>'.$row->AuctionID.'</td>
				<td>'.$row->pdescription.'</td>
				<td>'.$LpLaycanStartDate.'</td>
				<td>'.$LpLaycanEndDate.'</td>
				<td>'.$Disports.'</td>
				<td>'.$row->ccode.'</td>
				<td>'.$estfrt.'</td>
				<td>'.$indexfrt.'</td>
			</tr>';
            $i++;
        }
        echo $html;
    }
    
    public function getFreightQuoteData()
    {
        $data=$this->cargo_model->getauctionData();
        $editmode=$this->input->post('editmode');
        //print_r($editmode);die;
        $html='';
        $i=1;
        foreach($data as $row) {
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $Diff_Disports=$this->cargo_model->getDifferentialRecords($row->AuctionID, $row->LineNum);
            $Disports='';
            if(count($DisportRslt)> 0) {
                foreach($DisportRslt as $dr){
                    $Disports .=$dr->dspPortName.', ';
                }
            } else {
                $Disports=$row->dpDescription;
            }
            $Disports=trim($Disports, ", ");
            
            $DifferentialDisport='-';
            if(count($Diff_Disports) > 0) {
                if(count($Diff_Disports) ==1) {
                    $DifferentialDisport=$Diff_Disports[0]->refPortName;
                } else {
                    $DifferentialDisport='Multiple Disports';
                }
            }
            
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='';
            }
            $cargoQty=number_format($row->CargoQtyMT);
            
            if($editmode==1) {
                $add_edit="<a href='javascript: void(0);' onclick='cargoWiseEdit(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";                
            } else {
                $add_edit="<a href='javascript: void(0);' onclick='cargoWise(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";    
            }
            
            $h_view="<a href='javascript: void(0);' onclick='getCargoDetails(".$row->LineNum.")' title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $html .='<tr>
				<td>'.$i.'</td>
				<td>'.$row->Code.'</td>
				<td>'.$cargoQty.'</td>
				<td>'.$row->lpDescription.'</td>
				<td>'.$Disports.'</td>
				<td>'.$LpLaycanStartDate.'</td>
				<td>'.$LpLaycanEndDate.'</td>
				<td>'.$DifferentialDisport.'</td>
				<td>'.$add_edit.' '.$h_view.'</td>
				</tr>';
            $i++;
        }
        echo $html.'_____'.count($data).'_____'.$data[0]->LineNum;
    }
    
    public function getFreightEstimateData()
    {
        $data=$this->cargo_model->getauctionData();
        $editmode=$this->input->post('editmode');
        //print_r($editmode);die;
        $html='';
        $i=1;
        foreach($data as $row) {
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $Disports='';
            if(count($DisportRslt)> 0) {
                foreach($DisportRslt as $dr){
                    $Disports .=$dr->dspPortName.', ';
                }
            } else {
                $Disports=$row->dpDescription;
            }
            $Disports=trim($Disports, ", ");
            
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='';
            }
            $cargoQty=number_format($row->CargoQtyMT);
            
            if($row->Freight_Estimate=='yes') {
                $Freight_Estimate='Yes';
            } else {
                $Freight_Estimate='No';
            }
            
            if($row->Freight_Index=='yes') {
                $Freight_Index='Yes';
            } else {
                $Freight_Index='No';
            }
            
            if($editmode==1) {
                $add_edit="<a href='javascript: void(0);' onclick='cargoWiseEdit(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";                
            } else {
                $add_edit="<a href='javascript: void(0);' onclick='cargoWise(".$row->LineNum.")' title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;";    
            }
            
            $h_view="<a href='javascript: void(0);' onclick='getCargoDetails(".$row->LineNum.")' title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            $html .='<tr>
				<td>'.$i.'</td>
				<td>'.$row->Code.'</td>
				<td>'.$cargoQty.'</td>
				<td>'.$row->lpDescription.'</td>
				<td>'.$Disports.'</td>
				<td>'.$LpLaycanStartDate.'</td>
				<td>'.$LpLaycanEndDate.'</td>
				<td>'.$Freight_Estimate.'</td>
				<td>'.$Freight_Index.'</td>
				<td>'.$add_edit.' '.$h_view.'</td>
				</tr>';
            $i++;
        }
        echo $html.'_____'.count($data).'_____'.$data[0]->LineNum;
    }
    
    public function get_cargo_details()
    { 
        $AuctionID=$this->input->post('AuctionID');    
        $data=$data=$this->cargo_model->get_cargo_details();
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByAuctionID($AuctionID);
        
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
        $bac_html='';
        $BACFlag='';
        if($data->BACFlag==1) {
            $BACFlag='Yes';
            $bac_html='<hr style="background-color: black;">';
            $BAC_data=$this->cargo_model->get_bac_details();
            foreach($BAC_data as $row){
                $TransactionType='';
                if($row->TransactionType=='Commision') {
                    $TransactionType='AddComm';
                }else{
                    $TransactionType=$row->TransactionType;
                }
                
                $bac_html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Transaction type : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">'.$TransactionType.'</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Paying Entity Type : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">'.$row->PayingEntityType.'</label>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Receiving Entity Type : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">'.$row->ReceivingEntityType.'</label>
							</div>
						</div>';
                if($row->ReceivingEntityType=='Charterer') {
                    $bac_html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Receiving Entity Name : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">'.$row->ReceivingEntityName.'</label>
							</div>
						</div>';
                }
                        $bac_html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">'.$TransactionType.' payable : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">'.$row->PayableAs.'</label>
							</div>
						</div>';
                if($row->PayableAs=='Percentage') {
                    if($row->PercentageOnFreight) {
                        $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">'.$TransactionType.' % on freight : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.$row->PercentageOnFreight.'</label>
									</div>
								</div>';
                    }
                    if($row->PercentageOnDeadFreight) {
                        $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">'.$TransactionType.' % on deadfreight : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.$row->PercentageOnDeadFreight.'</label>
									</div>
								</div>';
                    }
                    if($row->PercentageOnDemmurage) {
                        $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">'.$TransactionType.' % on demmurage : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.$row->PercentageOnDemmurage.'</label>
									</div>
								</div>';
                    }
                    if($row->PercentageOnOverage) {
                        $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">'.$TransactionType.' % on overage : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.$row->PercentageOnOverage.'</label>
									</div>
								</div>';
                    }
                                
                } else if($row->PayableAs=='LumpSum') {
                    $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">Lumpsum amount payable : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.number_format($row->LumpsumPayable).'</label>
									</div>
								</div>';
                } else if($row->RatePerTonnePayable=='RatePerTonne') {
                    $bac_html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">'.$TransactionType.' rate/tonne : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">'.$row->RatePerTonnePayable.'</label>
									</div>
								</div>';
                }  
            }
            
        } else {
            $BACFlag='No';
        }
        //print_r($data); die;
        
        $header_html .='<br/><hr style="background-color:black; height: 2px;"><br/>';
        
        $html .=$header_html;
        
        $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Cargo : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->Code.'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Cargo quantity (in MT) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->CargoQtyMT).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Cargo quantity loaded option basis : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->CargoLoadedBasis.'</label>
					</div>
				</div>';
        if($data->CargoLimitBasis==2) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Cargo quantity limit basis : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">% Tolerance limit</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Tolerance limit (%) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.(int)$data->ToleranceLimit.'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Upper cargo limit is : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->UpperLimit).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Lower cargo limit is : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->LowerLimit).'</label>
					</div>
				</div>';
        }else if($data->CargoLimitBasis==1) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Cargo quantity limit basis : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Max and Min</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Max cargo is : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->MaxCargoMT).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Min cargo is : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->MinCargoMT).'</label>
					</div>
				</div>';
        }
            $html .='<hr style="background-color: black;"><div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Load Port : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->pdesc.'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Load port laycan start date : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpLaycanStartDate)).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Load port laycan finish date : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpLaycanEndDate)).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loadport preferred arrival date : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LpPreferDate)).'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Expected loadport delay  : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->ExpectedLpDelayDay.' days '.$data->ExpectedLpDelayHour.' hours</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loading Terms : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->ldtCode.'</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loading rate (mt) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.number_format($data->LoadingRateMT).'</label>
					</div>
				</div>';
        if($data->LoadingRateUOM==1) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loading rate (uom) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Per hour</label>
					</div>
				</div>';
        }else if($data->LoadingRateUOM==2) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loading rate (uom) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Per weather working day</label>
					</div>
				</div>';
        }else if($data->LoadingRateUOM==3) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Loading rate (uom) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Max time limit</label>
					</div>
				</div>
				<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Max time to load cargo (hrs) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.(int) $data->LpMaxTime.'</label>
					</div>
				</div>';
        }
        if($data->LpLaytimeType==1) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Reversible</label>
					</div>
				</div>';
        }else if($data->LpLaytimeType==2) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Non Reversible</label>
					</div>
				</div>';
        }else if($data->LpLaytimeType==3) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Average</label>
					</div>
				</div>';
        }
        if($data->LpCalculationBasedOn==108) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime tonnage calc. based on : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Bill of Loading Quantity</label>
					</div>
				</div>';
        }else if($data->LpCalculationBasedOn==109) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime tonnage calc. based on : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Outturn or Discharge Quantity</label>
					</div>
				</div>';
        }
                $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Turn (free) time (hours) : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->ftCode.' || '.$data->ftDescription.'</label>
					</div>
				</div>';
        if($data->LpPriorUseTerms==102) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Prior use terms : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">IUATUTC || If Used Actual Time To Count</label>
					</div>
				</div>';
        }else if($data->LpPriorUseTerms==10) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Prior use terms : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">IUHTUTC || If Used Half Time To Count</label>
					</div>
				</div>';
        }
        if($data->LpLaytimeBasedOn==1) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime based on : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">ATS || All Time Saved</label>
					</div>
				</div>';
        }else if($data->LpLaytimeBasedOn==2) {
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Laytime based on : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">WTS || Working Time Saved</label>
					</div>
				</div>';
        }
                $LpCharterType='';
        if($data->LpCharterType==1) {
            $LpCharterType=' 1 Safe Port 1 Safe Berth (1SP1SB)';
        }else if($data->LpCharterType==2) {
            $LpCharterType=' 1 Safe Port 2 Safe Berth (1SP2SB)';
        }else if($data->LpCharterType==3) {
            $LpCharterType=' 2 Safe Port 1 Safe Berth (2SP1SB)';
        }else if($data->LpCharterType==4) {
            $LpCharterType=' 2 Safe Port 2 Safe Berth (2SP2SB)';
        }
                $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Type of charter : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$LpCharterType.'</label>
					</div>
				</div>';
                
                $StevedoringTerms=$this->cargo_model->getStevedoringTermsByID($data->LpStevedoringTerms);
                $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Stevedoring terms : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">Code : '.$StevedoringTerms->Code.' || Description : '.$StevedoringTerms->Description.'</label>
					</div>
				</div>';
                $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">NOR tender : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$data->cnrCode.'</label>
					</div>
				</div>';
                $html .='<hr style="background-color: black;">';
        if($data->ExceptedPeriodFlg==1) {
            $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Excepted periods for events : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Yes</label>
						</div>
					</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
            $ExceptedPeriod=$this->cargo_model->getLpExpectedPeriodByCargoID($data->CargoID);
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
						<div class="col-lg-4">
							<label style=" float: right;">Excepted periods for events : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">No</label>
						</div>
					</div>';
        }
                
                $html .='<hr style="background-color: black;">';
        if($data->NORTenderingPreConditionFlg==1) {
            $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">NOR tendering pre conditions apply : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Yes</label>
						</div>
					</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
            $NORTendering=$this->cargo_model->getLpNORTenderingPreByCargoID($data->CargoID);
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
						<div class="col-lg-4">
							<label style=" float: right;">NOR tendering pre conditions apply : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">No</label>
						</div>
					</div>';
        }
                
                $html .='<hr style="background-color: black;">';
        if($data->NORAcceptancePreConditionFlg==1) {
            $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">NOR acceptance pre condition apply : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Yes</label>
						</div>
					</div>';
            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
            $NORAcceptance=$this->cargo_model->getLpNORAcceptancePreByCargoID($data->CargoID);
            foreach($NORAcceptance as $ar){
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
						<div class="col-lg-4">
							<label style=" float: right;">NOR acceptance pre condition apply : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">No</label>
						</div>
					</div>';
        }
        if($data->LpNorTendering==3) {
            $html .='<hr style="background-color: black;">';
            if($data->OfficeHoursFlg==1) {
                        $html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Enter Office hours : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">Yes</label>
							</div>
						</div>';
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
                        $OfficeHours=$this->cargo_model->getLpOfficeHoursByCargoID($data->CargoID);
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
                        $html .='<hr style="background-color: black;" >';
                if($data->LaytimeCommencementFlg==1) {
                    $html .='<div class="form-group">
								<div class="col-lg-4">
									<label style=" float: right;">Enter laytime commencement : </label>
								</div>
								<div class="col-lg-8">
									<label  style="text-align: left;">Yes</label>
								</div>
							</div>';
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
                    $LaytimeCommencement=$this->cargo_model->getLpLaytimeCommenceByCargoID($data->CargoID);
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
								<div class="col-lg-4">
									<label style=" float: right;">Enter laytime commencement : </label>
								</div>
								<div class="col-lg-8">
									<label  style="text-align: left;">No</label>
								</div>
							</div>';
                }
            } else {
                   $html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Enter Office hours : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">No</label>
							</div>
						</div>';
            }
        }
                
                            
                $DisportData=$this->cargo_model->getDisportDetailsByCargoID($data->CargoID);
                
        foreach($DisportData as $dis){
            $html .='<hr style="background-color: black;"><div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Disport : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$dis->dspPortName.'</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Disport (laycan from) date : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalStartDate)).'</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Disport (laycan to) date : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalEndDate)).'</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Disport preferred arrival date : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpPreferDate)).'</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Expected disport delay  : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$dis->ExpectedDpDelayDay.' days '.$dis->ExpectedDpDelayHour.' hours</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Discharging Terms : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$dis->trmCode.' || '.$dis->trmDescription.'</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Discharing rate (mt) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.number_format($dis->DischargingRateMT).'</label>
						</div>
					</div>';
            if($dis->DischargingRateUOM==1) {
                    $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Discharging rate (uom) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Per hour</label>
						</div>
					</div>';
            }else if($dis->LoadingRateUOM==2) {
                  $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Discharging rate (uom) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Per weather working day</label>
						</div>
					</div>';
            }else if($dis->LoadingRateUOM==3) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Discharging rate (uom) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Max time limit</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Max time to load cargo (hrs) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.(int) $dis->DpMaxTime.'</label>
						</div>
					</div>';
            }
            if($dis->DpLaytimeType==1) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime type : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Reversible</label>
						</div>
					</div>';
            }else if($dis->DpLaytimeType==2) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime type : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Non Reversible</label>
						</div>
					</div>';
            }else if($dis->DpLaytimeType==3) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime type : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Average</label>
						</div>
					</div>';
            }
            if($dis->DpCalculationBasedOn==108) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime tonnage calc. based on : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Bill of Loading Quantity</label>
						</div>
					</div>';
            } else if($dis->DpCalculationBasedOn==109) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime tonnage calc. based on : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Outturn or Discharge Quantity</label>
						</div>
					</div>';
            }
                      $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Turn (free) time (hours) : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$dis->ftCode.' || '.$dis->ftDescription.'</label>
						</div>
					</div>';
            if($dis->DpPriorUseTerms==102) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Prior use terms : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">IUATUTC || If Used Actual Time To Count</label>
						</div>
					</div>';
            }else if($dis->DpPriorUseTerms==10) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Prior use terms : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">IUHTUTC || If Used Half Time To Count</label>
						</div>
					</div>';
            }
            if($dis->DpLaytimeBasedOn==1) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime based on : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">ATS || All Time Saved</label>
						</div>
					</div>';
            } else if($dis->DpLaytimeBasedOn==2) {
                $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Laytime based on : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">WTS || Working Time Saved</label>
						</div>
					</div>';
            }
                      $DpCharterType='';
            if($dis->DpCharterType==1) {
                $DpCharterType=' 1 Safe Port 1 Safe Berth (1SP1SB)';
            }else if($dis->DpCharterType==2) {
                $DpCharterType=' 1 Safe Port 2 Safe Berth (1SP2SB)';
            }else if($dis->DpCharterType==3) {
                $DpCharterType=' 2 Safe Port 1 Safe Berth (2SP1SB)';
            }else if($dis->DpCharterType==4) {
                $DpCharterType=' 2 Safe Port 2 Safe Berth (2SP2SB)';
            }
                    
                      $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Type of charter : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$DpCharterType.'</label>
						</div>
					</div>';
                      $StevedoringTerms=$this->cargo_model->getStevedoringTermsByID($dis->DpStevedoringTerms);
                      $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">Stevedoring terms : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">Code : '.$StevedoringTerms->Code.' || Description : '.$StevedoringTerms->Description.'</label>
						</div>
					</div>';
                      $html .='<div class="form-group">
						<div class="col-lg-4">
							<label style=" float: right;">NOR tender : </label>
						</div>
						<div class="col-lg-8">
							<label  style="text-align: left;">'.$dis->cnrDCode.'</label>
						</div>
					</div>';
                      $html .='<hr style="background-color: black;">';
            if($dis->DpExceptedPeriodFlg==1) {
                $html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">Excepted periods for events : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">Yes</label>
							</div>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
                $ExceptedPeriod=$this->cargo_model->getDpExpectedPeriodByDisportID($dis->CD_ID);
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
							<div class="col-lg-4">
								<label style=" float: right;">Excepted periods for events : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">No</label>
							</div>
						</div>';
            }
                      $html .='<hr style="background-color: black;">';
            if($dis->DpNORTenderingPreConditionFlg==1) {
                $html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">NOR tendering pre conditions apply : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">Yes</label>
							</div>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORTendering=$this->cargo_model->getDpNORTenderingPreByDisportID($dis->CD_ID);
                        
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
							<div class="col-lg-4">
								<label style=" float: right;">NOR tendering pre conditions apply : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">No</label>
							</div>
						</div>';
            }
                    
                      $html .='<hr style="background-color: black;">';
            if($dis->DpNORAcceptancePreConditionFlg==1) {
                $html .='<div class="form-group">
							<div class="col-lg-4">
								<label style=" float: right;">NOR acceptance pre condition apply : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">Yes</label>
							</div>
						</div>';
                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                $NORAcceptance=$this->cargo_model->getDpNORAcceptancePreByDisportID($dis->CD_ID);
                foreach($NORAcceptance as $ar){
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
							<div class="col-lg-4">
								<label style=" float: right;">NOR acceptance pre condition apply : </label>
							</div>
							<div class="col-lg-8">
								<label  style="text-align: left;">No</label>
							</div>
						</div>';
            }
            if($dis->DpNorTendering==3) {
                $html .='<hr style="background-color: black;">';
                if($dis->DpOfficeHoursFlg==1) {
                    $html .='<div class="form-group">
								<div class="col-lg-4">
									<label style=" float: right;">Enter Office hours : </label>
								</div>
								<div class="col-lg-8">
									<label  style="text-align: left;">Yes</label>
								</div>
							</div>';
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
                    $OfficeHours=$this->cargo_model->getDpOfficeHoursByDisportID($dis->CD_ID);
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
                    $html .='<hr style="background-color: black;" >';
                    if($dis->DpLaytimeCommencementFlg==1) {
                                $html .='<div class="form-group">
									<div class="col-lg-4">
										<label style=" float: right;">Enter laytime commencement : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">Yes</label>
									</div>
								</div>';
                                $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
                                $LaytimeCommencement=$this->cargo_model->getDpLaytimeCommenceByDisportID($dis->CD_ID);
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
									<div class="col-lg-4">
										<label style=" float: right;">Enter laytime commencement : </label>
									</div>
									<div class="col-lg-8">
										<label  style="text-align: left;">No</label>
									</div>
								</div>';
                    }
                } else {
                                                          $html .='<div class="form-group">
								<div class="col-lg-4">
									<label style=" float: right;">Enter Office hours : </label>
								</div>
								<div class="col-lg-8">
									<label  style="text-align: left;">No</label>
								</div>
							</div>';
                }
            }
                    
        }
                $html .='<hr style="background-color: black;">';
                $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Brokerage / Add Comm applicable : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$BACFlag.'</label>
					</div>
				</div>';
                
                $html .=$bac_html;
        if($data->CargoInternalComments) {
            $html .='<div class="form-group" >
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-4" style="text-align: right; font-weight: 100;">Comments by cargo owner : </label><label class="control-label col-lg-8" style="text-align: left;">'.$data->CargoInternalComments.'</label></label>
					</div>';
                
        }
        if($data->CargoDisplayComments) {
                    
            $html .='<div class="form-group" >
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-4" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-8" style="text-align: left;">'.$data->CargoDisplayComments.'</label></label>
					</div>';
                
        }
                $section='cp';
                $file_records=$this->cargo_model->getCargoFileAttached($section);
        foreach($file_records as $f){
            $FileArr=explode("_____", $f->FileName);
            $html .='<div class="form-group">
					<div class="col-lg-4">
						<label style=" float: right;">Attached file name : </label>
					</div>
					<div class="col-lg-8">
						<label  style="text-align: left;">'.$FileArr[1].'</label>
					</div>
				</div>';
        }
            $html .='<br/>';
        echo $html;
    }
    
    public function get_cargo_html_details()
    {
        $data=$this->cargo_model->get_cargo_html_details();
        $type='cp';
        $data1=$this->cargo_model->get_cargo_document_details($type);
        $html='';
        $i=1;
        if($data) {
            foreach($data as $row) {
                $temp='';
                $temp2='';
                $temp3='';
                $bac_html='';
                $BACFlag='';
                if($row->BACFlag==1) {
                    $BACFlag='Yes';
                    $bac_html='<hr style="background-color: black; height: 1px;" >';
                    $BAC_data=$this->cargo_model->get_bac_html_details($row->LineNum);
                    foreach($BAC_data as $row1){
                        $TransactionType='';
                        if($row1->TransactionType=='Commision') {
                            $TransactionType='AddComm';
                        } else {
                            $TransactionType=$row1->TransactionType;
                        }
                        
                        $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">Transaction type : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$TransactionType.'</label>
									</div>
								<div class="form-group">
									<label class="control-label col-lg-5">Paying Entity Type : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PayingEntityType.'</label>
									</div>
								<div class="form-group">
									<label class="control-label col-lg-5">Receiving Entity Type : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->ReceivingEntityType.'</label>
									</div>';
                        if($row1->ReceivingEntityType=='Charterer') {
                               $bac_html .='<div class="form-group">
							<label class="control-label col-lg-5">Receiving Entity Name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$row1->ReceivingEntityName.'</label>
							</div>';
                        }
                        $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">Brokerage payable : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PayableAs.'</label>
									</div>';
                        if($row1->PayableAs=='Percentage') {
                            if($row1->PercentageOnFreight) {
                                $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">'.$TransactionType.' % on freight : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PercentageOnFreight.'</label>
									</div>';
                            }
                            if($row1->PercentageOnDeadFreight) {
                                $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">'.$TransactionType.' % on deadfreight : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PercentageOnDeadFreight.'</label>
									</div>';
                            }
                            if($row1->PercentageOnDemmurage) {
                                   $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">'.$TransactionType.' % on demmurage : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PercentageOnDemmurage.'</label>
									</div>';
                            }
                            if($row1->PercentageOnOverage) {
                                $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">'.$TransactionType.' % on overage : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->PercentageOnOverage.'</label>
									</div>';
                            }
                        } else if($row1->PayableAs=='LumpSum') {
                                              $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">Lumpsum amount payable : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row1->LumpsumPayable).'</label>
									</div>';
                        } else if($row1->RatePerTonnePayable=='RatePerTonne') {
                            $bac_html .='<div class="form-group">
									<label class="control-label col-lg-5">'.$TransactionType.' rate/tonne : </label>
									<label class="control-label col-lg-7" style="text-align: left;">'.$row1->RatePerTonnePayable.'</label>
									</div>';
                        }  
                    }
                } else {
                    $BACFlag='No';
                }
                
                if($row->CargoLimitBasis==1) {
                    $CargoLimitBasis='Max and Min';
                    $temp='<div class="form-group">
					<label class="control-label col-lg-5">Max cargo is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->MaxCargoMT).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Min cargo is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->MinCargoMT).'</label>
					</div>';
                } else if($row->CargoLimitBasis==2) {
                    $CargoLimitBasis='% Tolerance limit';
                    $temp='<div class="form-group">
					<label class="control-label col-lg-5">Tolerance limit (%) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->ToleranceLimit.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Upper cargo limit is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->UpperLimit).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Lower cargo limit is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->LowerLimit).'</label>
					</div>';
                }
                
                $LoadingRateUOM='';
                if($row->LoadingRateUOM==1) {
                    $LoadingRateUOM='Per hour';
                } else if($row->LoadingRateUOM==2) {
                    $LoadingRateUOM='Per weather working day';
                } else if($row->LoadingRateUOM==3) {
                    $LoadingRateUOM='Max time limit';
                    $temp2='<div class="form-group">
					<label class="control-label col-lg-5">Max time to load cargo (hrs) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->LpMaxTime.'</label>
					</div>';
                }
                
                $LpLaytimeType='';
                if($row->LpLaytimeType==1) {
                    $LpLaytimeType='Reversible';
                } else if($row->LpLaytimeType==2) {
                    $LpLaytimeType='Non Reversible';
                } else if($row->LpLaytimeType==3) {
                    $LpLaytimeType='Average';
                }
                
                $LpCalculationBasedOn='';
                if($row->LpCalculationBasedOn==108) {
                    $LpCalculationBasedOn='Bill of Loading Quantity';
                } else if($row->LpCalculationBasedOn==109) {
                    $LpCalculationBasedOn='Outturn or Discharge Quantity';
                }
                
                if($row->LpPriorUseTerms==102) {
                    $LpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
                } else if($row->LpPriorUseTerms==10) {
                    $LpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
                } else{
                    $LpPriorUseTerms='N/A';
                }
                
                if($row->LpLaytimeBasedOn==1) {
                    $LpLaytimeBasedOn='ATS || All Time Saved';
                } else if($row->LpLaytimeBasedOn==2) {
                    $LpLaytimeBasedOn='WTS || Working Time Saved';
                } else {
                    $LpLaytimeBasedOn='N/A';
                }
                
                $LpCharterType='';
                if($row->LpCharterType==1) {
                    $LpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                } else if($row->LpCharterType==2) {
                    $LpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
                } else if($row->LpCharterType==3) {
                    $LpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
                } else if($row->LpCharterType==4) {
                    $LpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
                }            
                
                $html .='<h4><B>Cargo and port details </B></h4>
				<div class="form-group">
				<label class="control-label col-lg-5">Cargo '.$i.' : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->Code.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Cargo quantity to load (in MT) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->CargoQtyMT).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Cargo quantity option basis : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->CargoLoadedBasis.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Cargo quantity option basis : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$CargoLimitBasis.'</label>
				</div>';
                $html .=$temp;
                
                if($row->LoadPort) {
                    $html .='<hr style="background-color: black; height: 1px;" >
					<div class="form-group">
					<label class="control-label col-lg-5">Load Port '.$i.' : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row->lpDescription.'</label>
					</div>';
                }
                
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Load port laycan start date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpLaycanStartDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Load port laycan finish date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpLaycanEndDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Loadport  preferred arrival date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($row->LpPreferDate)).'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Expected loadport delay : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->ExpectedLpDelayDay.' days '.$row->ExpectedLpDelayHour.' hours</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Loading Terms : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->ldtCode.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Loading rate (mt) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row->LoadingRateMT).'</label>
				</div>';
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Loading rate based on (uom) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LoadingRateUOM.'</label>
				</div>';
                $html .=$temp2;
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Laytime : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LpLaytimeType.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Laytime tonnage calc. based on : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LpCalculationBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5"> Turn (free) time (hours) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->ftCode.' || '.$row->ftDescription.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5"> Prior use terms : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LpPriorUseTerms.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Laytime based on : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LpLaytimeBasedOn.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5"> Type of charter : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$LpCharterType.'</label>
				</div>';
                $StevedoringTerms=$this->cargo_model->getStevedoringTermsByID($row->LpStevedoringTerms);
                $html .='<div class="form-group">
					<label class="control-label col-lg-5">Stevedoring terms : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$StevedoringTerms->Code.' || Description : '.$StevedoringTerms->Description.'</label>
					</div>';
                $html .='<div class="form-group">
				<label class="control-label col-lg-5"> NOR tender : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$row->cnrCode.'</label>
				</div>';
                $html .='<hr style="background-color: black;  height: 1px;">';
                if($row->ExceptedPeriodFlg==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5"> Excepted periods for events : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
					</div>';
                    $ExceptedPeriod=$this->cargo_model->getLpExpectedPeriodByCargoID($row->CargoID);
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
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
						<label class="control-label col-lg-5">Excepted periods for events : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
					</div>';
                }
                
                $html .='<hr style="background-color: black;  height: 1px;">';
                if($row->NORTenderingPreConditionFlg==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5"> NOR tendering pre conditions apply : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
					</div>';
                    $NORTendering=$this->cargo_model->getLpNORTenderingPreByCargoID($row->CargoID);
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                    foreach($NORTendering as $tr){
                        $CreateNewOrSelectListFlg='';
                        $NewNORTenderingPreCondition='';
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
						<label class="control-label col-lg-5"> NOR tendering pre conditions apply : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
					</div>';
                }
                
                $html .='<hr style="background-color: black;  height: 1px;">';
                if($row->NORAcceptancePreConditionFlg==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5"> NOR acceptance pre condition apply : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
					</div>';
                    $NORAcceptance=$this->cargo_model->getLpNORAcceptancePreByCargoID($row->CargoID);
                    $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                    foreach($NORAcceptance as $ar){
                        $CreateNewOrSelectListFlg='';
                        $NewNORAcceptancePreCondition='';
                        if($ar->CreateNewOrSelectListFlg==1) {
                                  $CreateNewOrSelectListFlg='create new';
                                  $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                        } else if($ar->CreateNewOrSelectListFlg==2) {
                               $CreateNewOrSelectListFlg='select from pre defined list';
                               $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                        }
                        $StatusFlag='No';
                        if($ar->StatusFlag) {
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
						<label class="control-label col-lg-5"> NOR acceptance pre condition apply : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
					</div>';
                }
                if($row->LpNorTendering==3) {
                    $html .='<hr style="background-color: black;  height: 1px;">';
                    if($row->OfficeHoursFlg==1) {
                        $html .='<div class="form-group">
							<label class="control-label col-lg-5">Enter Office hours : </label>
							<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        $OfficeHours=$this->cargo_model->getLpOfficeHoursByCargoID($row->CargoID);
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
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
                        $html .='<hr style="background-color: black;  height: 1px;" >';
                        if($row->LaytimeCommencementFlg==1) {
                               $html .='<div class="form-group">
								<label class="control-label col-lg-5">Enter laytime commencement : </label>
								<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
							</div>';
                               $LaytimeCommencement=$this->cargo_model->getLpLaytimeCommenceByCargoID($row->CargoID);
                               $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
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
                                        } else if($lr->TimeCountsIfOnDemurrage==2) {
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
                                    } else if($lr->TimeCountsIfOnDemurrage==2) {
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
								<label class="control-label col-lg-5"> Enter laytime commencement : </label>
								<label class="control-label col-lg-7" style="text-align: left;">No</label>
							</div>';
                        }
                    } else {
                        $html .='<div class="form-group">
							<label class="control-label col-lg-5">Enter Office hours : </label>
							<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                }
                    
                $DisportData=$this->cargo_model->getDisportDetailsByCargoID($row->CargoID);
                $j=1;
                foreach($DisportData as $dis){
                    $temp3='';
                    $html .='<hr style="background-color: black; height: 1px;" >
					<div class="form-group">
					<label class="control-label col-lg-5"> Disport '.$j.' : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$dis->dspPortName.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Disport (laycan from) date : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalStartDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Disport (laycan to) date  : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpArrivalEndDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Disport preferred arrival date : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($dis->DpPreferDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Expected disport delay : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$dis->ExpectedDpDelayDay.' days '.$dis->ExpectedDpDelayHour.' hours</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Discharging Terms : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$dis->trmCode.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Discharing rate (mt)  : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($dis->DischargingRateMT).'</label>
					</div>';
                    if($dis->DischargingRateUOM==1) {
                        $DischargingRateUOM='Per hour';
                    } else if($dis->DischargingRateUOM==2) {
                        $DischargingRateUOM='Per weather working day';
                    } else if($dis->DischargingRateUOM==3) {
                        $DischargingRateUOM='Max time limit';
                        $temp3='<div class="form-group">
						<label class="control-label col-lg-5">Max time to discharge (hrs) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.(int)$row->DpMaxTime.'</label>
						</div>';
                    }
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Discharging rate based on (uom)  : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DischargingRateUOM.'</label>
					</div>';
                    $html .=$temp3;
                    
                    $DpLaytimeType='';
                    if($dis->DpLaytimeType==1) {
                                    $DpLaytimeType='Reversible';
                    }else if($dis->DpLaytimeType==2) {
                              $DpLaytimeType='Non Reversible';
                    }else if($dis->DpLaytimeType==3) {
                        $DpLaytimeType='Average';
                    }
                    
                    $DpCalculationBasedOn='';
                    if($dis->DpCalculationBasedOn==108) {
                        $DpCalculationBasedOn='Bill of Loading Quantity';
                    }else if($dis->DpCalculationBasedOn==109) {
                        $DpCalculationBasedOn='Outturn or Discharge Quantity';
                    }    
                    
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
                    
                    $DpCharterType='';
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
					<label class="control-label col-lg-5"> Laytime type : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DpLaytimeType.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Laytime tonnage calc. based on : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DpCalculationBasedOn.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5"> Turn (free) time (hours) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$dis->ftCode.' || '.$dis->ftDescription.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5"> Prior use terms : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DpPriorUseTerms.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Laytime based on : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DpLaytimeBasedOn.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Type of charter : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$DpCharterType.'</label>
					</div>';
                    $StevedoringTerms=$this->cargo_model->getStevedoringTermsByID($dis->DpStevedoringTerms);
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Stevedoring terms : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$StevedoringTerms->Code.' || Description : '.$StevedoringTerms->Description.'</label>
					</div>';
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">NOR tender : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$dis->cnrDCode.'</label>
					</div>';
                    $html .='<hr style="background-color: black;  height: 1px;">';
                    if($dis->DpExceptedPeriodFlg==1) {
                        $html .='<div class="form-group">
							<label class="control-label col-lg-5"> Excepted periods for events : </label>
							<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        $ExceptedPeriod=$this->cargo_model->getDpExpectedPeriodByDisportID($dis->CD_ID);
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Event name</th><th>Laytime Counts on demurrage</th><th>Laytime counts</th><th>Time counting</th></tr>';
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
							<label class="control-label col-lg-5">Excepted periods for events : </label>
							<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    $html .='<hr style="background-color: black;  height: 1px;">';
                    if($dis->DpNORTenderingPreConditionFlg==1) {
                        $html .='<div class="form-group">
							<label class="control-label col-lg-5">NOR tendering pre conditions apply : </label>
							<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        $NORTendering=$this->cargo_model->getDpNORTenderingPreByDisportID($dis->CD_ID);
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                        foreach($NORTendering as $tr){
                            $CreateNewOrSelectListFlg='';
                            $NewNORTenderingPreCondition='';
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
							<label class="control-label col-lg-5">NOR tendering pre conditions apply : </label>
							<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    
                    $html .='<hr style="background-color: black;  height: 1px;">';
                    if($dis->DpNORAcceptancePreConditionFlg==1) {
                        $html .='<div class="form-group">
							<label class="control-label col-lg-5">NOR acceptance pre condition apply : </label>
							<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        $NORAcceptance=$this->cargo_model->getDpNORAcceptancePreByDisportID($dis->CD_ID);
                        $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>New / Select from pre defined list</th><th>Name of condition</th><th>Activate</th></tr>';
                        foreach($NORAcceptance as $ar){
                            $CreateNewOrSelectListFlg='';
                            $NewNORAcceptancePreCondition='';
                            if($ar->CreateNewOrSelectListFlg==1) {
                                         $CreateNewOrSelectListFlg='create new';
                                         $NewNORAcceptancePreCondition=$ar->NewNORAcceptancePreCondition;
                            } else if($ar->CreateNewOrSelectListFlg==2) {
                                             $CreateNewOrSelectListFlg='select from pre defined list';
                                             $NewNORAcceptancePreCondition=$ar->AcceptanceCode;
                            }
                            $StatusFlag='No';
                            if($ar->StatusFlag) {
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
							<label class="control-label col-lg-5"> NOR acceptance pre condition apply : </label>
							<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    if($dis->DpNorTendering==3) {
                        $html .='<hr style="background-color: black;  height: 1px;">';
                        if($dis->DpOfficeHoursFlg==1) {
                            $html .='<div class="form-group">
								<label class="control-label col-lg-5"> Enter Office hours : </label>
								<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
							</div>';
                            $OfficeHours=$this->cargo_model->getDpOfficeHoursByDisportID($dis->CD_ID);
                            $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Is last entry</th></tr>';
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
                            $html .='<hr style="background-color: black;  height: 1px;" >';
                            if($dis->DpLaytimeCommencementFlg==1) {
                                     $html .='<div class="form-group">
									<label class="control-label col-lg-5">Enter laytime commencement : </label>
									<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
								</div>';
                                     $LaytimeCommencement=$this->cargo_model->getDpLaytimeCommenceByDisportID($dis->CD_ID);
                                     $html .='<table class="table table-bordered table-striped" style="font-size: 12px;" ><tr><th>Day (From)</th><th>Day (To)</th><th>Time (From)</th><th>Time (To)</th><th>Turn time applies</th><th>Turn time expires</th><th>Laytime commences at</th><th>Laytime Commences at (hours)</th><th>Select day</th><th>Time counts if on Demurrage</th></tr>';
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
                                            } else if($lr->TimeCountsIfOnDemurrage==2) {
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
                                        } else if($lr->TimeCountsIfOnDemurrage==2) {
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
									<label class="control-label col-lg-5">Enter laytime commencement : </label>
									<label class="control-label col-lg-7" style="text-align: left;">No</label>
								</div>';
                            }
                        } else {
                            $html .='<div class="form-group">
								<label class="control-label col-lg-5">Enter Office hours : </label>
								<label class="control-label col-lg-7" style="text-align: left;">No</label>
							</div>';
                        }
                    }
                    
                    $j++;
                }
                $html .='<hr style="background-color: black;  height: 1px;">';    
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Brokerage / Add Comm applicable : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$BACFlag.'</label>
				</div>';
                
                $html .=$bac_html;
                
                if($row->CargoInternalComments) {
                    $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
				<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments by cargo owner : </label><label class="control-label col-lg-7" style="text-align: left;">'.$row->CargoInternalComments.'</label></label>
				</div>';
                }
                if($row->CargoDisplayComments) {
                    $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
				<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$row->CargoDisplayComments.'</label></label>
				</div>';
                }
                if(count($data1) > 0) {
                    $html .='<br/><hr style="background-color: black;  height: 1px;" ><br/><h4><B>Cargo & Ports Documents</B></h4>
						<div class="form-group">
						<label class="control-label col-lg-5">Type of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentType.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Name or Title of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentTitle.'</label>
						</div>';
                    if($data1[0]->ToDisplay==1) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                    }else if($data1[0]->ToDisplay==0) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    if($data1[0]->ToDisplayInvitee==1) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                    }else if($data1[0]->ToDisplayInvitee==0) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    foreach($data1 as $doc){
                        if($row->LineNum==$doc->LineNum) {
                            $namerr=explode("_____", $doc->FileName);
                            if($namerr[1]) {
                                $FileName=$namerr[1];
                            } else {
                                $FileName=$doc->FileName;
                            }
                            
                            $html .='<div class="form-group">
							<label class="control-label col-lg-5">File name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$FileName.'</label>
							</div>';
                        }
                    }
                }
                if($i !=count($data)) {
                    $html .='<hr style="background-color: black; height: 1px;" >';
                }
                $i++;
            }
            $html .='<br><hr style="background-color: black; height: 2px;" ><br>';
            echo $html;
        }
    }
    
    public function htmlDownload()
    {
        include_once APPPATH.'third_party/mpdf.php';
        
        $data['auction']=$this->input->get('AuctionId');        
        $data['data']=$this->cargo_model->getRoleSelectionCharterDetails();
        
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data['data']->OwnerEntityID);
        
        $data['Entity']=$entity_detail->EntityName;
        if($entity_detail->AttachedLogo) {
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);

            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            $data['Logo']=$url;
            $data['AlignLogo']=$entity_detail->AlignLogo;
        } else {
            $data['Logo']='';
            $data['AlignLogo']='';
        }
        
        $data['data1']=$this->cargo_model->get_cargo_html_details();
        $data['BAC_data']=$this->cargo_model->get_bac_html_fulldetails();

        $type='cp';
        $data['data2']=$this->cargo_model->get_cargo_document_details($type);
        
        $data['quote']=$this->cargo_model->getQuoteDifferentialDetails();
        
        $data['references']=$this->cargo_model->getQuoteDisportReferencesDetails();
        
        $type1='quote';
        $data['data5']=$this->cargo_model->get_cargo_document_details($type1);
        $type2='estimate';
        $data1['data7']=$this->cargo_model->get_cargo_document_details($type2);

        $data['data8']=$this->cargo_model->get_vessel_html_details();
        $type3='vessel';
        $data['data9']=$this->cargo_model->get_cargo_document_details($type3);
        $data['data10']=$this->cargo_model->get_invitee_html_details();
        $data['result']=$this->cargo_model->get_invitee_html_details12();
        $type4='Invitees';
        $data['data11']=$this->cargo_model->get_cargo_document_details($type4);
        
        $data['data12']=$this->cargo_model->get_alert_html_details();
        $type4='alert';
        $data['data13']=$this->cargo_model->get_cargo_document_details($type4);
        //print_r($data); die;
        $html=$this->load->view('setup/pdfdownload', $data, true);
        //echo $html; die;
        $pdfFilePath = $data['data']->EntityName."( ".$data['auction'].").pdf";
        $this->load->library('m_pdf');
        $pdf = $this->m_pdf->load();
        $pdf->WriteHTML($html);
        $pdf->Output($pdfFilePath, "D");
    }
    
    public function get_cargo_data1()
    {
        $data=$this->cargo_model->getauctionData1();
        $html='';
        foreach($data as $row) {
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $viewDisports='';
            $Disports='';
            $DisportID='';
            
            foreach($DisportRslt as $dr){
                $viewDisports .=$dr->dspPortName.', ';
                $Disports .='Port Name : '.$dr->dspPortName.' || Code : '.$dr->dspPortCode.', ';
                $DisportID .=$dr->DisPort.', ';
            }
            
            $viewDisports=trim($viewDisports, ", ");
            $Disports=trim($Disports, ", ");
            $DisportID=trim($DisportID, ", ");
            
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='';
            }
            $qty=number_format($row->CargoQtyMT);
            $html .='<tr>
				<td>'.$row->Code.'</td>
				<td >'.$qty.'</td>
				<td class="quantity" style="display: none;">'.(int)$row->CargoQtyMT.'</td>
				<td>'.$row->pdesc.'</td>
				<td>'.$LpLaycanStartDate.'</td>
				<td>'.$LpLaycanEndDate.'</td>
				<td >'.$viewDisports.'</td>
				<td class="disport" style="display: none;">'.$Disports.'</td>
				<td class="disport1" style="display: none;"> '.$DisportID.'</td>
				</tr>';
        }
        echo $html;
    }
    
    public function cargoClone()
    {
        $data=$this->cargo_model->getCargoData();
        $data1=$this->cargo_model->getlinenum();
        $savenew=$this->cargo_model->cloneauctionDetails($data, $data1->linenum);
        if($savenew) {
            echo 'Clone Created';
        } else {
            echo 'Clone Not Created';
        }
    }
    
    public function saveCharter()
    {
        $this->cargo_model->saveCharter();
        
    }
    
    public function updateAuctionData()
    {
        $oldData=$this->cargo_model->getAuctionOldData();
        $ret=$this->cargo_model->updateCharter();
        if($ret) {
            if($oldData->SelectFrom) {
                $newData=$this->cargo_model->getAuctionOldData();
                $this->cargo_model->saveCharterMessage($oldData, $newData);
            }
            echo 1;
        }
    }
    
    public function getCargoDataById()
    {
        $AuctionID=$this->input->post('AuctionId');
        $data['data1']=$this->cargo_model->getCargoDataById();
        $data['Disports']=$this->cargo_model->getDisportRecordsDataByCargoID($data['data1']->CargoID);
        $data['ExceptedPeriod']=$this->cargo_model->getLpExpectedPeriodByCargoID($data['data1']->CargoID);
        $data['NORTendering']=$this->cargo_model->getLpNORTenderingPreByCargoID($data['data1']->CargoID);
        $data['NORAcceptance']=$this->cargo_model->getLpNORAcceptancePreByCargoID($data['data1']->CargoID);
        $data['OfficeHours']=$this->cargo_model->getLpOfficeHoursByCargoID($data['data1']->CargoID);
        $data['LaytimeCommence']=$this->cargo_model->getLpLaytimeCommenceByCargoID($data['data1']->CargoID);
        
        $data['PeriodEvents']=$this->cargo_model->getAllExceptedPeriodEvents($AuctionID);
        $data['TenderingPreCond']=$this->cargo_model->getAllNORTenderingPreConditions($AuctionID);
        $data['AcceptancePreCond']=$this->cargo_model->getAllNORAcceptancePreConditions($AuctionID);
        
        //$data['data2']=$this->cargo_model->getDisPortDataById($data['data1']->DisPort);
        //$data['data3']=$this->cargo_model->getDisportTermDataById($data['data1']->DischargingTerms);
        $data['data4']=$this->cargo_model->getCargoBACById();
        $data['NorTend']=$this->cargo_model->getNorTending();
        $data['FreeTime']=$this->cargo_model->getFreeTime();
        $data['SteveDoringTerms']=$this->cargo_model->getSteveDoringTerms();
        $data['EdutableField']=$this->cargo_model->getEditableField();
        echo json_encode($data);
    }
    
    public function getQuoteDataById()
    {
        $id=$this->input->post('id');
        $AuctionID=$this->input->post('auctionId');
        $data['Differential']=$this->cargo_model->getQuoteDifferentialDataById();
        $data['refPorts']=$this->cargo_model->getQuoteRefPortsDataById($data['Differential']->DifferentialID);
        $data['AuctionRecord']=$this->cargo_model->getAuctionRecordByAuctionID($AuctionID);
        $data['CargoRow']=$this->cargo_model->getCargoRowByID($AuctionID, $id);
        $data['CargoDisports']=$this->cargo_model->getCargoDisportsByRow($AuctionID, $id);
        $data['EdutableField']=$this->cargo_model->getEditableFieldQuote($AuctionID);
        //print_r($data['data1']); die;
        echo json_encode($data);
        
    }
    
    public function cargoDelete()
    {
        $data=$this->cargo_model->cargoDelete();
        if($data) {
            echo 'Deleted';
        } else {
            echo 'Not Deleted';
        }
    }
    
    public function getPortMaster()
    {
        $res=$this->cargo_model->getPortMaster();
        //print_r($res);
        $data_arr1=array();
        $return_arr1 = array();
        foreach($res as $row){
            $data_arr1['label']='Port Name: '.$row->PortName.' || Code: '.$row->Code.' || Country: '.$row->Description;
            $data_arr1['value']=$row->ID;
            array_push($return_arr1, $data_arr1);
        }
        $this->output->set_header('Content-type: application/json');
        if(count($res)>0) {    
            $this->output->set_output(json_encode($return_arr1));    
        }else{    
            $data1=array('-1');
            $this->output->set_output(json_encode($data1));
        } 
        
    }
    
    public function getCargoMaster()
    {
        $res=$this->cargo_model->getCargoMaster();
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
    
    public function getCargoMasterForFixture()
    {
        $res=$this->cargo_model->getCargoMaster();
        $data_arr=array();
        $return_arr = array();
        foreach($res as $row){
            $data_arr['label']=$row->Code;
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
    
    public function getToadingTerm()
    {
        $res=$this->cargo_model->getToadingTerm();
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
    
    public function getEntityTypeForFixture()
    {
        $res=$this->cargo_model->getEntityType();
        $data_arr=array();
        $return_arr = array();
        foreach($res as $row){
            $data_arr['label']=$row->Description;
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
    
    public function getEntityType()
    {
        $res=$this->cargo_model->getEntityType();
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
    
    public function getLoadingTermForFixture()
    {
        $res=$this->cargo_model->getLoadingTermForFixture();
        $data_arr=array();
        $return_arr = array();
        foreach($res as $row){
            $data_arr['label']=$row->Code;
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
    
    public function getFreeTime()
    {
        $data=$this->cargo_model->getFreeTime();
        $html='<option value="">Select</option>';
        foreach($data as $row) {
            $html .='<option value="'.$row->ID.'">Code : '.$row->Code.' || Description : '.$row->Description.'</option>';
        }
        echo $html;
    }
    
    public function getFreeTime1()
    {
        $data['FreeTime']=$this->cargo_model->getFreeTime();
        
        echo json_encode($data);
    }
    
    public function getNorTending()
    {
        $data=$this->cargo_model->getNorTending();
        $html='<option value="">Select</option>';
        foreach($data as $row) {
            $html .='<option value="'.$row->ID.'">Code : '.$row->Code.' || Description : '.$row->Description.'</option>';
        }
        echo $html;
    }
    
    
    public function getCurrencyForFixture()
    {
        $res=$this->cargo_model->getCurrency();
        $data_arr=array();
        $return_arr = array();
        foreach($res as $row){
            $data_arr['label']=$row->Code;
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
    
    public function getCurrency()
    {
        $res=$this->cargo_model->getCurrency();
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
    
    public function saveQuote()
    {
        
        $data=$this->cargo_model->saveQuote();
        if($data) {
            $data1=$this->cargo_model->uploadImage();
            echo 'Saved';
        } else {
            echo 'Not Save';
        }
    }
    
    public function count_cargo()
    {
        $count=$this->cargo_model->count_cargo();
        echo $count->Total;
    }
    
    public function get_vessel_size()
    {
        $data=$this->cargo_model->get_vessel_size();
        if($data) {
            echo json_encode($data);
        }else{
            echo 1;
        }
    }
    
    public function updateQuote()
    {
        $oldData=$this->cargo_model->getQuoteDetails();
        
        $data=$this->cargo_model->updateQuote();
        
        $newData=$this->cargo_model->getQuoteDetails();
        $this->cargo_model->saveQuoteMessage($oldData, $newData);
        $data1=$this->cargo_model->uploadImage();
        echo '1';
    }
    
    public function saveNewQuote()
    {
        extract($this->input->post());
        
        $dataArr=array();
        $grpArr=array();
        for($i=0; $i<count($DiffRefDisportID); $i++){ 
            if (in_array($old_grp[$i], $grpArr)) {
                $key = array_search($old_grp[$i], $grpArr);
                $dataArr[$key] .= '-'.$old_DifferentialDisport[$i].'_'.$old_LoadDischangeRate[$i];
            } else {
                $grpArr[]=$old_grp[$i];
                $dataArr[]=$old_DifferentialDisport[$i].'_'.$old_LoadDischangeRate[$i];
            }
        }
        
        for($i=0; $i<count($DifferentialDisport); $i++){ 
            if (in_array($grp[$i], $grpArr)) {
                $key = array_search($grp[$i], $grpArr);
                $dataArr[$key] .= '-'.$DifferentialDisport[$i].'_'.$LoadDischangeRate[$i];
            } else {
                $grpArr[]=$grp[$i];
                $dataArr[]=$DifferentialDisport[$i].'_'.$LoadDischangeRate[$i];
            }
        }
        
        $dups = array();
        foreach(array_count_values($dataArr) as $val => $c){
            if($c > 1) { $dups[] = $val;
            }
        }
        if(count($dups) > 0) {
            $html="There are some duplicate differential disport(s) exist.\n\n";
            for($k=0; $k<count($dups); $k++){
                $portArr=explode('-', $dups[$k]);
                for($j=0;$j<count($portArr); $j++){
                    $portRow=explode('_', $portArr[$j]);
                    
                    $portDetail=$this->cargo_model->getPortDetailsByID($portRow[0]);
                    $html .="Port Name : ".$portDetail->PortName.": with Load/Dis Rate : ".$portRow[1]."\n";
                }
                if(count($dups) > 1) {
                    $html .='---------------------------------------------------------------------------\n';
                }
            }
            echo trim($html, '\n');
        } else {
            $flg=$this->cargo_model->saveNewQuote();
            $fileflg=$this->cargo_model->uploadImage();
            if($flg) {
                echo 1;
            } else {
                echo 2;
            }
        }
    }
    
    public function updateNewQuote()
    {
        extract($this->input->post());
        
        $dataArr=array();
        $grpArr=array();
        for($i=0; $i<count($DiffRefDisportID); $i++){ 
            if (in_array($old_grp[$i], $grpArr)) {
                $key = array_search($old_grp[$i], $grpArr);
                $dataArr[$key] .= '-'.$old_DifferentialDisport[$i].'_'.$old_LoadDischangeRate[$i];
            } else {
                $grpArr[]=$old_grp[$i];
                $dataArr[]=$old_DifferentialDisport[$i].'_'.$old_LoadDischangeRate[$i];
            }
        }
        
        for($i=0; $i<count($DifferentialDisport); $i++){ 
            if (in_array($grp[$i], $grpArr)) {
                $key = array_search($grp[$i], $grpArr);
                $dataArr[$key] .= '-'.$DifferentialDisport[$i].'_'.$LoadDischangeRate[$i];
            } else {
                $grpArr[]=$grp[$i];
                $dataArr[]=$DifferentialDisport[$i].'_'.$LoadDischangeRate[$i];
            }
        }
        
        $dups = array();
        foreach(array_count_values($dataArr) as $val => $c){
            if($c > 1) { $dups[] = $val;
            }
        }
        
        if(count($dups) > 0) {
            $html="There are some duplicate differential disport(s) exist.\n\n";
            for($k=0; $k<count($dups); $k++){
                $portArr=explode('-', $dups[$k]);
                for($j=0;$j<count($portArr); $j++){
                    $portRow=explode('_', $portArr[$j]);
                    
                    $portDetail=$this->cargo_model->getPortDetailsByID($portRow[0]);
                    $html .="Port Name : ".$portDetail->PortName.": with Load/Dis Rate : ".$portRow[1]."\n";
                }
                if(count($dups) > 1) {
                    $html .='---------------------------------------------------------------------------\n';
                }
            }
            echo trim($html, '\n');
        } else {
            $oldData=$this->cargo_model->getNewQuoteDetails();
            
            if($oldData) {
                $DifferentialID=$this->input->post('DifferentialID');
                if($DifferentialID) {
                    
                    $oldDiffRefData=$this->cargo_model->getNewQuoteDiffReferenceDetails($DifferentialID);
                    //print_r($oldDiffRefData); die;
                    $flg=$this->cargo_model->updateNewQuote();
                    
                    $newData=$this->cargo_model->getNewQuoteDetails();
                    $newDiffRefData=$this->cargo_model->getNewQuoteDiffReferenceDetails($DifferentialID);
                    $this->cargo_model->saveNewQuoteMessage($oldData, $newData, $oldDiffRefData, $newDiffRefData);
                } else {
                    $flg=0;
                }
            } else {
                $flg=$this->cargo_model->AddNewQuote();
            }
            $fileflg=$this->cargo_model->uploadImage();
            if($flg) {
                echo 1;
            } else {
                echo 2;
            }
        }
        
    }
    
    public function save_freight_estimate_data()
    {
        $savenew=$this->cargo_model->saveFreightEstimate();
        $data1=$this->cargo_model->uploadImage_estimate();
        if($savenew) {
            echo 1;
        } else {
            echo 2;
        }
    }
    
    public function update_freight_estimate_data()
    {
        $oldData=$this->cargo_model->getFreightEstimate();
        $savenew=$this->cargo_model->updateFreightEstimate();
        if($savenew) {
            $data1=$this->cargo_model->uploadImage_estimate();
            $newData=$this->cargo_model->getFreightEstimate();
            $this->cargo_model->saveEstimateMessage($oldData, $newData);
            echo 1;
        } else {
            echo 2;
        }
    }
    
    public function getAuctionSetup()
    {
        $data=$this->cargo_model->getAuctionSetup();
        echo json_encode($data);
    }
    
    public function getCharterDetail()
    {
        $data['CharterDetails']=$this->cargo_model->getCharterDetail();
        $data['modal']=$this->cargo_model->getModalFunction();
        echo json_encode($data);
    }
    
    public function getModalFunction()
    {
        $data=$this->cargo_model->getModalFunction();
        echo json_encode($data);
    }
    
    
    public function getReferenceDetail()
    {
        $data=$this->cargo_model->getReferenceDetail();
        echo json_encode($data);
    }
    
    public function deleteReference()
    {
        $data=$this->cargo_model->deleteReference();
        if($data) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function getLoadportByIds()
    {
        $data=$this->cargo_model->getLoadportByIds();
        $arr=array();
        foreach($data as $row) {
            array_push($arr, $row->LoadPort);
        }
        if(count(array_unique($arr))==1) {
            $loadport=$data[0]->LoadPort;
            $data1=$this->cargo_model->getLoadportById($loadport);
            echo json_encode($data1);
            //echo 'Port Name: '.$data1->PortName.' || Code '.$data1->PortName;
        } else {
            echo 2;
        }
        //print_r($data);
    }
    
    public function getLoadportByIdsNew()
    {
        $data=$this->cargo_model->getLoadportByIdsNew();
        if($data) {
            $data1=$this->cargo_model->getLoadportById($data->LoadPort);
            echo json_encode($data1);
        }else {
            echo 2;
        }
        
    }
    
    public function getCargoRecordOwner()
    {
        $data=$this->cargo_model->getCargoRecordOwner();
        echo json_encode($data);
    }
    
    public function getDisportByIds()
    {
        $data=$this->cargo_model->getDisportByIds();
        $arr=array();
        foreach($data as $row) {
            array_push($arr, $row->DisPort);
        }
        if(count(array_unique($arr))==1) {
            $loadport=$data[0]->DisPort;
            $data1=$this->cargo_model->getLoadportById($loadport);
            echo json_encode($data1);
            //echo 'Port Name: '.$data1->PortName.' || Code '.$data1->PortName;
        } else {
            echo 2;
        }
        //print_r($data);
    }
    
    public function getDocumentTypeMaster()
    {
        $data=$this->cargo_model->getDocumentTypeMaster();
        $html='<option value="">Select</option>';
        foreach($data as $row) {
            $html .='<option value="'.$row->DocumentTypeID.'">'.$row->DocumentType.'</option>';
        }
        echo $html;
    }
    
    public function getDocumentTitle()
    {
        $data=$this->cargo_model->getDocumentTitle();
        echo $data->DocumentTitle;
    }
    
    
    public function getVesselMaster()
    {
        $res=$this->cargo_model->getVesselMaster();
        //print_r($res);
        $data_arr1=array();
        $return_arr1 = array();
        foreach($res as $row){
            $data_arr1['label']='Size Group: '.$row->SizeGroup.' || Vessel Size: '.$row->VesselSize;
            $data_arr1['value']=$row->VesselID;
            array_push($return_arr1, $data_arr1);
        }
        $this->output->set_header('Content-type: application/json');
        if(count($res)>0) {    
            $this->output->set_output(json_encode($return_arr1));    
        }else{    
            $data1=array('-1');
            $this->output->set_output(json_encode($data1));
        } 
        
    }
    
    public function getAuctionData1()
    {
        $DisPort1=$this->input->get('DisPort1');
        $data=$this->cargo_model->getAuctionSetupMaster();
        //$this->output->enable_profiler();
        //print_r($data);die;
        $html='';
        $inhtml='';
        $status='';
        $i=1;
        $html='{ "aaData": [';
        foreach($data as $row) {
            $flg=0;
            $invRow=$this->cargo_model->getInviteeMaster($row->AuctionID);
            $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
            $Disports='';
            if(count($DisportRslt) > 0) {
                foreach($DisportRslt as $dr){
                    $Disports .=$dr->dspPortName.', ';
                    if($dr->DisPort==$DisPort1) {
                        $flg=1;
                    }
                }
            } else {
                $Disports=$row->dpdescription;
                if($row->DisPort==$DisPort1) {
                    $flg=1;
                }
            }
            if($DisPort1) {
                if($flg==0) {
                    continue;
                }
            }
            $Disports=trim($Disports, ", ");
            
            if($row->LpLaycanStartDate) {
                $LpLaycanStartDate=date('d-m-Y H:i:s', strtotime($row->LpLaycanStartDate));
            } else {
                $LpLaycanStartDate='-';
            }
            if($row->LpLaycanEndDate) {
                $LpLaycanEndDate=date('d-m-Y H:i:s', strtotime($row->LpLaycanEndDate));
            } else {
                $LpLaycanEndDate='-';
            }
            $edit_status=0;
            if($row->auctionStatus=='C') {
                $auctionStatus='Complete';
            }else {
                $auctionStatus='Pending';
            }
            if($row->auctionStatus=='C') {
                if($row->auctionExtendedStatus=='') {
                    $auctionStatus='Complete';
                }else if($row->auctionExtendedStatus=='A') {
                    $auctionStatus='Activated (M)';
                    $edit_status=1;
                }else if($row->auctionExtendedStatus=='PNR') {
                    $auctionStatus='Pending Release';
                }else if($row->auctionExtendedStatus=='W') {
                    $auctionStatus='Withdrawn';
                }
            }
            $status='';
            if($invRow) {
                if($invRow->InvPriorityStatus=='P1') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P2') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P3') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P0') {
                    $status='Global';
                }
            }
            
            $estfrt='';
            $indexfrt='';
            if($row->Estimate_By=='mt') {
                $estfrt=$row->Estimate_mt;
            }
            if($row->Estimate_By=='range') {
                $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
            }
            if($row->Estimate_Index_By=='mt') {
                $indexfrt=$row->Estimate_Index_mt;
            }
            if($row->Estimate_Index_By=='range') {
                $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
            }
            
            $check="<input class='chkNumber' type='checkbox' name='arr_auction_ids[]' value='".$row->AuctionID.'__'.$edit_status."'> <input type='hidden' class='linenum' value='".$row->LineNum."' >";
            
            $link="";
            if($edit_status==0) {
                $link="<a href='javascript: void(0);' onclick=editAuction('".$row->AuctionID."',1) title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=deleteAuction('".$row->AuctionID."',1) title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            
            } else {
                $link="<a href='javascript: void(0);' onclick=editAuction('".$row->AuctionID."',0) title='Click here to edit record'><i class='fa fa-edit fa_edit'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=deleteAuction('".$row->AuctionID."',0) title='Click here to delete record'><i class='fas fa-trash fa_delete'></i></a>";
            }
            $link .="&nbsp;&nbsp;<a href='javascript: void(0);' onclick=cloneRecord('".$row->AuctionID."') title='Click here to clone record'><i class='fa fa-copy fa_clone'></i></a>&nbsp;&nbsp;<a href='javascript: void(0);' onclick=HtmlView('".$row->AuctionID."') title='Click here to view HTML'><i class='fa fa-eye fa_html'></i></a>";
            
            $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'","'.$auctionStatus.'","'.$status.'","'.$row->AuctionID.'","'.$row->pdescription.'","'.$LpLaycanStartDate.'","'.$LpLaycanEndDate.'","'.$Disports.'","'.$row->ccode.'","'.$estfrt.'","'.$indexfrt.'","'.$link.'"],';
            $i++; 
        }
        
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;
        
    }
    
    public function DownloadAuctionData()
    {
        extract($this->input->get());
        $data=$this->cargo_model->getAuctionSetupMaster();
        //$this->output->enable_profiler();
        //print_r($data);die;
        $html='';
        $inhtml='';
        $status='';
        if($Outputformat=='PDF') {
            
            $html='<table style="width: 100%; border-collapse: collapse;">';
            $html .='<tr>';
            $html .='<th style="border: 1px solid;">DateTime</th>';
            $html .='<th style="border: 1px solid;">Status</th>';
            $html .='<th style="border: 1px solid;">Group</th>';
            $html .='<th style="border: 1px solid;">MasterID</th>';
            $html .='<th style="border: 1px solid;">Loadport</th>';
            $html .='<th style="border: 1px solid;">Laycan (From)</th>';
            $html .='<th style="border: 1px solid;">Laycan (To)</th>';
            $html .='<th style="border: 1px solid;">Disport</th>';
            $html .='<th style="border: 1px solid;">Cargo</th>';
            $html .='<th style="border: 1px solid;">Est.Frt ($/mt)</th>';
            $html .='<th style="border: 1px solid;">Index Frt ($/mt)</th>';
            $html .='</tr>';
            foreach($data as $row) {
                $flg=0;
                $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
                $Disports='';
                if(count($DisportRslt) > 0) {
                    foreach($DisportRslt as $dr){
                        $Disports .=$dr->dspPortName.', ';
                        if($dr->DisPort==$DisPort1) {
                            $flg=1;
                        }
                    }
                } else {
                    $Disports=$row->dpdescription;
                    if($row->DisPort==$DisPort1) {
                        $flg=1;
                    }
                }
                if($DisPort1) {
                    if($flg==0) {
                        continue;
                    }
                }
                $Disports=trim($Disports, ", ");
                
                $invRow='';
                $invRow=$this->cargo_model->getInviteeMaster($row->AuctionID);
                if($row->LpLaycanStartDate) {
                    $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
                } else {
                    $LpLaycanStartDate='';
                }
                if($row->LpLaycanEndDate) {
                    $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
                } else {
                    $LpLaycanEndDate='';
                }
                $edit_status=0;
                if($row->auctionStatus=='C') {
                    $auctionStatus='Complete';
                }else {
                    $auctionStatus='Pending';
                }
                if($row->auctionStatus=='C') {
                    if($row->auctionExtendedStatus=='') {
                        $auctionStatus='Complete';
                    }else if($row->auctionExtendedStatus=='A') {
                        $auctionStatus='Activated (M)';
                        $edit_status=1;
                    }else if($row->auctionExtendedStatus=='PNR') {
                        $auctionStatus='Pending Release';
                    }else if($row->auctionExtendedStatus=='W') {
                        $auctionStatus='Withdrawn';
                    }
                }
                $status='';
                if($invRow->InvPriorityStatus=='P1') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P2') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P3') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P0') {
                    $status='Global';
                }
                $estfrt='';
                $indexfrt='';
                if($row->Estimate_By=='mt') {
                    $estfrt=$row->Estimate_mt;
                }
                if($row->Estimate_By=='range') {
                    $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
                }
                if($row->Estimate_Index_By=='mt') {
                    $indexfrt=$row->Estimate_Index_mt;
                }
                if($row->Estimate_Index_By=='range') {
                    $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
                }
                
                $html .='<tr>';
                $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
                $html .='<td style="border: 1px solid;">'.$auctionStatus.'</td>';
                $html .='<td style="border: 1px solid;">'.$status.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->AuctionID.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->pdescription.'</td>';
                $html .='<td style="border: 1px solid;">'.$LpLaycanStartDate.'</td>';
                $html .='<td style="border: 1px solid;">'.$LpLaycanEndDate.'</td>';
                $html .='<td style="border: 1px solid;">'.$Disports.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->ccode.'</td>';
                $html .='<td style="border: 1px solid;">'.$estfrt.'</td>';
                $html .='<td style="border: 1px solid;">'.$indexfrt.'</td>';
                $html .='</tr>';
            }
            
            $html .='</table>';
            $pdfFilePath='cargo_setup.pdf';
            include_once APPPATH.'third_party/mpdf.php';
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "D");
        }

        if($Outputformat=='EXCEL') {
            $Content = "DateTime,Status,Group,MasterID,Loadport,Laycan (From),Laycan (To),Disport,Cargo,Est.Frt ($/mt),Index Frt ($/mt) \n";
            foreach($data as $row) {
                $flg=0;
                $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
                $Disports='';
                if(count($DisportRslt) > 0) {
                    foreach($DisportRslt as $dr){
                        $Disports .=$dr->dspPortName.', ';
                        if($dr->DisPort==$DisPort1) {
                               $flg=1;
                        }
                    }
                } else {
                    $Disports=$row->dpdescription;
                    if($row->DisPort==$DisPort1) {
                        $flg=1;
                    }
                }
                if($DisPort1) {
                    if($flg==0) {
                        continue;
                    }
                }
                $Disports=trim($Disports, ", ");
                
                $invRow='';
                $invRow=$this->cargo_model->getInviteeMaster($row->AuctionID);
                if($row->LpLaycanStartDate) {
                    $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
                } else {
                    $LpLaycanStartDate='';
                }
                if($row->LpLaycanEndDate) {
                    $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
                } else {
                    $LpLaycanEndDate='';
                }
                $edit_status=0;
                if($row->auctionStatus=='C') {
                    $auctionStatus='Complete';
                }else {
                    $auctionStatus='Pending';
                }
                if($row->auctionStatus=='C') {
                    if($row->auctionExtendedStatus=='') {
                        $auctionStatus='Complete';
                    }else if($row->auctionExtendedStatus=='A') {
                        $auctionStatus='Activated (M)';
                        $edit_status=1;
                    }else if($row->auctionExtendedStatus=='PNR') {
                        $auctionStatus='Pending Release';
                    }else if($row->auctionExtendedStatus=='W') {
                        $auctionStatus='Withdrawn';
                    }
                }
                $status='';
                if($invRow->InvPriorityStatus=='P1') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P2') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P3') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P0') {
                    $status='Global';
                }
                $estfrt='';
                $indexfrt='';
                if($row->Estimate_By=='mt') {
                    $estfrt=$row->Estimate_mt;
                }
                if($row->Estimate_By=='range') {
                    $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
                }
                if($row->Estimate_Index_By=='mt') {
                    $indexfrt=$row->Estimate_Index_mt;
                }
                if($row->Estimate_Index_By=='range') {
                    $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
                }
                
                $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$auctionStatus.",".$status.",".$row->AuctionID.",".$row->pdescription.",".$LpLaycanStartDate.",".$LpLaycanEndDate.",\"".$Disports."\",".$row->ccode.",".$estfrt.",".$indexfrt."\n";
            }
            
            header('Content-Type: application/csv'); 
            $FileName='cargo_setup.csv';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $Content;
            exit();    
        }
        
        if($Outputformat=='XML') {
            header('Content-type: text/xml');
            $xmloutput="<?xml version=\"1.0\" ?>\n";
            $xmloutput .="<CargoSetup>\n";
            foreach($data as $row) {
                $flg=0;
                $DisportRslt=$this->cargo_model->getDisportRowByCargoID($row->CargoID);
                $Disports='';
                if(count($DisportRslt) > 0) {
                    foreach($DisportRslt as $dr){
                        $Disports .=$dr->dspPortName.', ';
                        if($dr->DisPort==$DisPort1) {
                               $flg=1;
                        }
                    }
                } else {
                    $Disports=$row->dpdescription;
                    if($row->DisPort==$DisPort1) {
                        $flg=1;
                    }
                }
                if($DisPort1) {
                    if($flg==0) {
                        continue;
                    }
                }
                $Disports=trim($Disports, ", ");
                
                $invRow='';
                $invRow=$this->cargo_model->getInviteeMaster($row->AuctionID);
                if($row->LpLaycanStartDate) {
                    $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
                } else {
                    $LpLaycanStartDate='';
                }
                if($row->LpLaycanEndDate) {
                    $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
                } else {
                    $LpLaycanEndDate='';
                }
                $edit_status=0;
                if($row->auctionStatus=='C') {
                    $auctionStatus='Complete';
                }else {
                    $auctionStatus='Pending';
                }
                if($row->auctionStatus=='C') {
                    if($row->auctionExtendedStatus=='') {
                        $auctionStatus='Complete';
                    }else if($row->auctionExtendedStatus=='A') {
                        $auctionStatus='Activated (M)';
                        $edit_status=1;
                    }else if($row->auctionExtendedStatus=='PNR') {
                        $auctionStatus='Pending Release';
                    }else if($row->auctionExtendedStatus=='W') {
                        $auctionStatus='Withdrawn';
                    }
                }
                $status='';
                if($invRow->InvPriorityStatus=='P1') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P2') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P3') {
                    $status='Preferred';
                }
                if($invRow->InvPriorityStatus=='P0') {
                    $status='Global';
                }
                $estfrt='';
                $indexfrt='';
                if($row->Estimate_By=='mt') {
                    $estfrt=$row->Estimate_mt;
                }
                if($row->Estimate_By=='range') {
                    $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
                }
                if($row->Estimate_Index_By=='mt') {
                    $indexfrt=$row->Estimate_Index_mt;
                }
                if($row->Estimate_Index_By=='range') {
                    $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
                }
                
                $Content .= date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$auctionStatus.",".$status.",".$row->AuctionID.",".$row->pdescription.",".$LpLaycanStartDate.",".$LpLaycanEndDate.",".$row->dpdescription.",".$row->ccode.",".$estfrt.",".$indexfrt."\n";
                $xmloutput .="\t<Process>\n";
                $xmloutput .="\t\t<UserDate>".date('d-m-Y', strtotime($row->UserDate))."</UserDate>\n";
                $xmloutput .="\t\t<CargoStatus>".$auctionStatus."</CargoStatus>\n";
                $xmloutput .="\t\t<InviteeGroup>".$status."</InviteeGroup>\n";
                $xmloutput .="\t\t<MasterID>".$row->AuctionID."</MasterID>\n";
                $xmloutput .="\t\t<LoadPort>".$row->dpdescription."</LoadPort>\n";
                $xmloutput .="\t\t<LaycanDateFrom>".$LpLaycanStartDate."</LaycanDateFrom>\n";
                $xmloutput .="\t\t<LaycanDateTo>".$LpLaycanEndDate."</LaycanDateTo>\n";
                $xmloutput .="\t\t<DisPort>".$Disports."</DisPort>\n";
                $xmloutput .="\t\t<Cargo>".$row->ccode."</Cargo>\n";
                $xmloutput .="\t\t<EstimateFreight>".$estfrt."</EstimateFreight>\n";
                $xmloutput .="\t\t<IndexFreight>".$indexfrt."</IndexFreight>\n";
                $xmloutput .="\t</process>\n";
            }
        
            $xmloutput .="</CargoSetup>\n";    
        
            header('Content-Type: application/xml'); 
            $FileName = 'cargo_setup.xml';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $xmloutput;
            exit();            
        }
        
        
    }
    
    public function auctionClone()
    {
        $newauctionId=$this->get__Random__Id('clone');
        $oldauctionId=$this->input->post('auctionId');
        $data1=$this->cargo_model->auctionClone1($oldauctionId, $newauctionId);
        $data2=$this->cargo_model->auctionClone2($oldauctionId, $newauctionId);
        $this->cargo_model->auctionCargoClone2($oldauctionId, $newauctionId);
        $data3=$this->cargo_model->auctionClone3($oldauctionId, $newauctionId);
        $data4=$this->cargo_model->auctionClone4($oldauctionId, $newauctionId);
        $this->cargo_model->auctionBankDetailsClone4($oldauctionId, $newauctionId);
        $data5=$this->cargo_model->auctionClone5($oldauctionId, $newauctionId);
        $this->cargo_model->auctionEditableFieldsClone($oldauctionId, $newauctionId);
        if($data1 && $data2 && $data3 && $data4 && $data5) {
            $this->cargo_model->saveAuctionCloneMessage($oldauctionId, $newauctionId);
            echo '1';
        } else { 
            echo '0';
        }
        
    }    
    
    public function get_document_title()
    {
        $data=$this->cargo_model->get_document_title();
        $html='<option value="">Select</option>';
        foreach($data as $row) {
            $html .='<option value="'.$row->DocumentTypeID.'">'.$row->DocumentTitle.'</option>';
        }
        echo $html; 
    }
    public function get_cargo_document()
    {
        $linenum=$this->input->post('linenum');
        $auctionid=$this->input->post('auctionid');
        $data=$this->cargo_model->get_cargo_document($linenum, $auctionid);
        echo json_encode($data);
    }
    
    public function search_by_auction()
    {
        $data=$this->cargo_model->search_by_auction();
        if($data) {
            $html='';
            $i=1;
        
            foreach($data as $row) {
                if($row->LpLaycanStartDate) {
                    $LpLaycanStartDate=date('d-m-Y', strtotime($row->LpLaycanStartDate));
                } else {
                    $LpLaycanStartDate='';
                }
                if($row->LpLaycanEndDate) {
                    $LpLaycanEndDate=date('d-m-Y', strtotime($row->LpLaycanEndDate));
                } else {
                    $LpLaycanEndDate='';
                }
                if($row->auctionStatus=='A') {
                    $auctionStatus='Activated';
                }else if($row->auctionStatus=='PNR') {
                    $auctionStatus='Pending Release';
                }else if($row->auctionStatus=='W') {
                    $auctionStatus='Withdrawn';
                }else if($row->auctionStatus=='R') {
                    $auctionStatus='release';
                }else {
                    $auctionStatus='Pending';
                }
                $status='';
                if($row->InvPriorityStatus=='P1') {
                    $status='Preferred invitee 1';
                }
                if($row->InvPriorityStatus=='P2') {
                    $status='Preferred invitee 2';
                }
                if($row->InvPriorityStatus=='P3') {
                    $status='Preferred invitee 3';
                }
                if($row->InvPriorityStatus=='P0') {
                    $status='Global invitation';
                }
                $estfrt='';
                $indexfrt='';
                if($row->Estimate_By=='mt') {
                    $estfrt=$row->Estimate_mt;
                }
                if($row->Estimate_By=='range') {
                    $estfrt=$row->Estimate_from.'-'.$row->Estimate_to;
                }
                if($row->Estimate_Index_By=='mt') {
                    $indexfrt=$row->Estimate_Index_mt;
                }
                if($row->Estimate_Index_By=='range') {
                    $indexfrt=$row->Estimate_Index_from.'-'.$row->Estimate_Index_to;
                }
                $html .='<tr>
				<td><input class="chkNumber" type="checkbox" name="arr_auction_ids[]" value="'.$row->AuctionID.'"> <input type="hidden" class="linenum" value="'.$row->LineNum.'"></td>
					<td>'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>
					<td>'.$auctionStatus.'</td>
					<td>'.$status.'</td>
					<td>'.$row->AuctionID.'</td>
					<td>'.$row->pcode.'</td>
					<td>'.$LpLaycanStartDate.'</td>
					<td>'.$LpLaycanEndDate.'</td>
					<td>'.$row->dpcode.'</td>
					<td>'.$row->ccode.'</td>
					<td>'.$estfrt.'</td>
					<td>'.$indexfrt.'</td>
				</tr>';
                $i++;
            }
            echo $html;
        } else {
            echo 0;
        }
    }
    
    public function auction_Delete()
    {
        $AuctionID=$this->input->post('auctionId');
        $update=$this->cargo_model->deleteAuction($AuctionID);
        if($update) {
            $this->cargo_model->saveAuctionDeleteMessage($AuctionID);
        }
        echo 1;
    
    }
    
    public function updateAuctionRole()
    {
        $oldData=$this->cargo_model->getAuctionOldData();
        $this->cargo_model->updateRole();
        $newData=$this->cargo_model->getAuctionOldData();
        $this->cargo_model->saveRoleMessage($oldData, $newData);
        //$this->sendMail();
        echo 1;
    }
    
    public function get_AuctionRole()
    {
        $data=$this->cargo_model->get_AuctionRole();
        echo json_encode($data);
        //echo 1;
    }
    
    
    public function getInviteesData()
    {
        $this->load->model('vessel_model');
        $data=$this->vessel_model->getInviteesData();
        $this->output->set_output(json_encode($data));
    }
    
    public function getDocumentCargo()
    {
        $auctionID=$this->input->get('AuctionId');
        $data['details']=$this->cargo_model->getDocumentForCargoByAction($auctionID);
        //print_r($data['details']); die;
        $this->output->set_output(json_encode($data)); 
    }
    
    
    public function CharterData()
    {
        $data['chaters']=$this->cargo_model->getCharterData();
        $this->output->set_output(json_encode($data)); 
    }
    
    
    public function deleteImg()
    {
        $docid=$this->input->post('docid');
        echo $this->cargo_model->deleteImgById($docid);
    }
    
    
    public function save_auction_status()
    {
        $flg=$this->cargo_model->save_auction_status();
        $this->cargo_model->create_response_cargo();
        $this->cargo_model->create_response_vessel();
        $this->cargo_model->create_response_freight();
        $this->cargo_model->createQuoteBusinessProcesses();
        if($flg) {
            echo 1;
        }else{
            echo 0;
        }
    }
    
    public function get_auction_status()
    {
        $data=$this->cargo_model->get_auction_status();
        echo json_encode($data);
    }
    
    public function getSteveDoringTerms()
    {
        $data['SteveDoringTerms']=$this->cargo_model->getSteveDoringTerms();
        echo json_encode($data);
    }
    
    public function getPortMasterForFixture()
    {
        $res=$this->cargo_model->getPortMaster();
        //print_r($res);
        $data_arr1=array();
        $return_arr1 = array();
        foreach($res as $row){
            $data_arr1['label']=$row->PortName;
            $data_arr1['value']=$row->ID;
            array_push($return_arr1, $data_arr1);
        }
        $this->output->set_header('Content-type: application/json');
        if(count($res)>0) {    
            $this->output->set_output(json_encode($return_arr1));    
        }else{    
            $data1=array('-1');
            $this->output->set_output(json_encode($data1));
        } 
        
    }
    
    public function getRoleSelectionCharterDetails()
    {
        $data['charter']=$this->cargo_model->getRoleSelectionCharterDetails();
        $entity_detail=$this->cargo_model->getOwnerEntityDetailsByID($data['charter']->OwnerEntityID);
        
        $data['Entity']=$entity_detail->EntityName;
        if($entity_detail->AttachedLogo) {
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);

            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/Logo/'.$entity_detail->AttachedLogo, 3600);
            $data['Logo']=$url;
            $data['AlignLogo']=$entity_detail->AlignLogo;
        } else {
            $data['Logo']='';
            $data['AlignLogo']='';
        }
        
        echo json_encode($data); 
    }
    
    
    public function get_estimate_html_details()
    {
        $data=$this->cargo_model->get_cargo_html_details();
        $type='estimate';
        $data1=$this->cargo_model->get_cargo_document_details($type);
        $html='';
        $i=1;
        if($data) {
            foreach($data as $row) {
                  $temp4='';
                  $temp5='';
                  $Freight_Estimate='';
                if($row->Freight_Estimate) {
                    if($row->Freight_Estimate=='no') {
                        $Freight_Estimate='No';
                    }else if($row->Freight_Estimate=='yes') {
                         $Freight_Estimate='Yes';
                        if($row->Estimate_By=='mt') {
                            $Estimate_By='$/mt';
                            $temp4='<div class="form-group">
						<label class="control-label col-lg-5">Freight estimate : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$Estimate_By.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight estimate($/mt) - for reference : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_mt.'</label>
						</div>';
                        }else if($row->Estimate_By=='range') {
                            $Estimate_By='Range';
                            $temp4='<div class="form-group">
						<label class="control-label col-lg-5">Freight estimate : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$Estimate_By.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight ($/mt) acceptable range from : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_from.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight ($/mt) acceptable range to : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_to.'</label>
						</div>';
                        }
                    }
                    $Freight_Index='';
                    if($row->Freight_Index=='no') {
                          $Freight_Index='No';
                    }else if($row->Freight_Index=='yes') {
                        $Freight_Index='Yes';
                        if($row->Estimate_Index_By=='mt') {
                            $Estimate_Index_By='$/mt';
                            $temp5='<div class="form-group">
						<label class="control-label col-lg-5">Freight by index : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$Estimate_Index_By.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight by index($/mt)- for reference : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_Index_mt.'</label>
						</div>';
                        }else if($row->Estimate_Index_By=='range') {
                            $Estimate_Index_By='Range';
                            $temp5='<div class="form-group">
						<label class="control-label col-lg-5">Freight by index : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$Estimate_Index_By.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight by index range from : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_Index_from.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Freight by index range to : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$row->Estimate_Index_to.'</label>
						</div>';
                        }
                    }
            
                    $html .='<h4><B>Frt estimate '.$row->LineNum.'</B></h4>
					<div class="form-group">
					<label class="control-label col-lg-5">Freight estimate : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$Freight_Estimate.'</label>
					</div>';
                    if($temp4 !='') {
                            $html .=$temp4;
                    }
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Freight based on index : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$Freight_Index.'</label>
					</div>';
                    if($temp4 !='') {
                          $html .=$temp5;
                    }    
                    if($row->estimate_comment) {
                        $html .='<div class="form-group">
					<label class="control-label col-lg-5">Estimate comments : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row->estimate_comment.'</label>
					</div>';
                    }
                    if(count($data1) > 0) {
                        $html .='<h4><B>Frt Estimate Documents</B></h4>
						<div class="form-group">
						<label class="control-label col-lg-5">Type of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentType.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Name or Title of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentTitle.'</label>
						</div>';
                        if($data1[0]->ToDisplay==1) {
                            $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        }else if($data1[0]->ToDisplay==0) {
                            $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                        }
                        if($data1[0]->ToDisplayInvitee==1) {
                            $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                        }else if($data1[0]->ToDisplayInvitee==0) {
                            $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                        }
                        foreach($data1 as $doc){
                            if($row->LineNum==$doc->LineNum) {
                                $html .='<div class="form-group">
							<label class="control-label col-lg-5">File name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
							</div>';
                            }
                        }
                    }
                    $html .='<br><hr style="background-color: black; height: 2px;" ><br>';
                    $i++;
                }
            }
        }
        echo $html;
    }
    
     
    public function get_invitee_html_details()
    {
        $row=$this->cargo_model->get_invitee_html_details();
        $result=$this->cargo_model->get_invitee_html_details12();
        $type='Invitees';
        $data1=$this->cargo_model->get_cargo_document_details($type);
        $entity1='';
        $username1='';
        $fullDetails1='';
        $fullDetails2='';
        $fullDetails3='';
        foreach($result as $invrow){
            if($invrow->InvPriorityStatus=='P1') {
                 $entity1 =$invrow->EntityName;
                 $username1 =$invrow->FirstName.' '.$invrow->LastName;
                 $fullDetails1 .=$entity1.' ('.$username1.'),';
            }else if($invrow->InvPriorityStatus=='P2') {
                $entity1=$invrow->EntityName;
                $username1 =$invrow->FirstName.' '.$invrow->LastName;
                $fullDetails2 .=$entity1.' ('.$username1.'),';
            }else if($invrow->InvPriorityStatus=='P3') {
                $entity1=$invrow->EntityName;
                $username1 =$invrow->FirstName.' '.$invrow->LastName;
                $fullDetails3 .=$entity1.' ('.$username1.'),';
            }
        }
        $fullDetails1=trim($fullDetails1, ',');
        $fullDetails2=trim($fullDetails2, ',');
        $fullDetails3=trim($fullDetails3, ',');
        
        $html='';
        if($row) {
            $html .='<h4><B>Invitee </B></h4>';
            if($row->InvPriorityStatus=='P1') {
                $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 1</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails1.'</label>
						</div>';
                 
            }else if($row->InvPriorityStatus=='P2') {
                if($fullDetails1 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 1</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails1.'</label>
						</div>';
                }
                if($fullDetails2 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 2</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails2.'</label>
						</div>';
                }
            }else if($row->InvPriorityStatus=='P3') {
                if($fullDetails1 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 1</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails1.'</label>
						</div>';
                }
                if($fullDetails2 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 2</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails2.'</label>
						</div>';
                }
                if($fullDetails3 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 3</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails3.'</label>
						</div>';
                }
            }else if($row->InvPriorityStatus=='P0') {
                $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Global (Priority 1,2,3)</label>
						</div>';
                if($fullDetails1 != '') {
                         $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 1</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails1.'</label>
						</div>';
                }
                if($fullDetails2 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 2</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails2.'</label>
						</div>';
                }
                if($fullDetails3 != '') {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Invitees Priority : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Invitee Priority 3</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Invitees : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$fullDetails3.'</label>
						</div>';
                }
            }
             $AdverseComments='';
            if($row->AdverseComments=='undefined') {
                $AdverseComments='';
            } else if($row->AdverseComments !='') {
                $AdverseComments=$row->AdverseComments; 
            }
             $Comments='';
            if($row->Comments=='undefined') {
                $Comments='';
            } else if($row->Comments !='') {
                $Comments=$row->Comments; 
            }
            
                
            if($AdverseComments !='') {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments by cargo owner : </label><label class="control-label col-lg-7" style="text-align: left;">'.$AdverseComments.'</label></label>
					</div>';
                
            }
            if($Comments !='') {
                $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$Comments.'</label></label>
					</div>';
                
            }
            if(count($data1) > 0) {
                $html .='<h4><B>Invitee Documents</B></h4>
					<div class="form-group">
					<label class="control-label col-lg-5">Type of Document : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentType.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Name or Title of Document : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentTitle.'</label>
					</div>';
                if($data1[0]->ToDisplay==1) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                }else if($data1[0]->ToDisplay==0) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                }
                if($data1[0]->ToDisplayInvitee==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                }else if($data1[0]->ToDisplayInvitee==0) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                }
                foreach($data1 as $doc){
                    $html .='<div class="form-group">
							<label class="control-label col-lg-5">File name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
							</div>';
                }
            }
            $html .='<br><br/><hr style="background-color: black; height: 2px;"><br>';
        }
        echo $html;
    }
    
    
    
    
    
    public function getFreightResponseRecords()
    {
        $data['records']=$this->cargo_model->getFreightResponseRecords();
        //print_r($data);  die;
        echo json_encode($data);
        
    }
    
    
    public function get_quote_html_details()
    {
        $data1=$this->cargo_model->get_quote_diff_html_details();
        $type='quote';
        $data2=$this->cargo_model->get_cargo_document_details($type);
        
        $html='';
        if($data1) {
            foreach($data1 as $row1) {
                $html .='<h4><B>Frt differential '.$row1->LineNum.'</B></h4>';    
                 $html .='<div class="form-group">
					<label class="control-label col-lg-5">Vessel Size group : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.number_format($row1->VesselSize).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Base port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row1->basePort.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Reference Port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row1->refPort.'</label>
					</div>';
                if($row1->defPort) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Differential Port : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row1->defPort.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Differential Amount : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$row1->DifferentialAmount.'</label>
					</div>';
                }
                if($row1->DifferentialComments) {
                    $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments by cargo owner : </label><label class="control-label col-lg-7" style="text-align: left;">'.$row1->DifferentialComments.'</label></label>
					</div>';
                }
                if($row1->InviteeComment) {
                    $html .='<div  style="margin-top: 1px; margin-bottom: 1px; !important;">
					<label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$row1->InviteeComment.'</label></label>
					</div>';
                }
                    
                if($data2[0]->DocumentType) {
                    $html .='<h4><B>Frt Diff Documents</B></h4>
						<div class="form-group">
						<label class="control-label col-lg-5">Type of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data2[0]->DocumentType.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Name or Title of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data2[0]->DocumentTitle.'</label>
						</div>';
                    if($data2[0]->ToDisplay==1) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                    }else if($data2[0]->ToDisplay==0) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    if($data2[0]->ToDisplayInvitee==1) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                    }else if($data2[0]->ToDisplayInvitee==0) {
                        $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                    }
                    foreach($data2 as $doc){
                        if($row->LineNum==$doc->LineNum) {
                            $html .='<div class="form-group">
							<label class="control-label col-lg-5">File name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
							</div>';
                        }
                    }        
                }        
                    
            }
        }
        if($data1) {
            $html .='<hr style="background-color: black; height: 2px;" >';
        }
        echo $html;
        
    }
    
    
    public function get_vessel_html_details()
    {
        $data=$this->cargo_model->get_vessel_html_details();
        $type='vessel';
        $data1=$this->cargo_model->get_cargo_document_details($type);
        $html='';
        $flag=0;
        if($data) {
            if($data->CommentAuction || $data->CommentInvitee) {
                $flag=1;
                $html .='<h4><B>Vessel </B></h4>';
                if($data->CommentAuction) {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-5">Comments for cargo owner  : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->CommentAuction.'</label>
				</div>';
                }
                if($data->CommentInvitee) {
                    $html .='<div class="form-group">
				<label class="control-label col-lg-5">Comments for Invitees : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->CommentInvitee.'</label>
				</div>';
                }
            }
            if($data1[0]->DocumentType) {
                $html .='<h4><B>Vessel Documents</B></h4>
						<div class="form-group">
						<label class="control-label col-lg-5">Type of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentType.'</label>
						</div>
						<div class="form-group">
						<label class="control-label col-lg-5">Name or Title of Document : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentTitle.'</label>
						</div>';
                if($data1[0]->ToDisplay==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                }else if($data1[0]->ToDisplay==0) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (cargo owner) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                }
                if($data1[0]->ToDisplayInvitee==1) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
						</div>';
                }else if($data1[0]->ToDisplayInvitee==0) {
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">Display (invitee) : </label>
						<label class="control-label col-lg-7" style="text-align: left;">No</label>
						</div>';
                }
                foreach($data1 as $doc){
                        
                    $html .='<div class="form-group">
							<label class="control-label col-lg-5">File name : </label>
							<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
							</div>';
                        
                }
            }
            
            if($flag==1) {
                $html .='<br><hr style="background-color: black; height: 2px;"><br>';
            }
        }    
        echo $html;
    }
    
    
    public function check_status()
    {
        $status=$this->cargo_model->check_status();
        echo $status;
    }
    
    public function get_message_detail()
    {
        $data=$this->cargo_model->get_message_detail();
        echo json_encode($data);
    }
    public function updateCargoDisports()
    {
        $oldData=$this->cargo_model->getCargoDisportsById();
        $flg=$this->cargo_model->updateCargoDisports();
        if($flg) {
            $newData=$this->cargo_model->getCargoDisportsById();
            $this->cargo_model->saveCargoDisportMessage($oldData, $newData);
            $data['records']=$this->cargo_model->getCargoDisportsByCargoID();
            $data['flg']=1;
        } else{
            $data['flg']=0;
        }
        echo json_encode($data);
    }
    public function getMessageAuctionData()
    {
        $data1=$this->cargo_model->getAuctionID();
        $html='';
        $inhtml ='';
        if($data1) {
            $auction_arr=array();
            foreach($data1 as $row1){
                array_push($auction_arr, $row1->AuctionID);
            }
            
            $data=$this->cargo_model->getMessageAuctionData($auction_arr);
            
            $i=1;
            $html ='{ "aaData": [';
            foreach($data as $row){
                $cnt=$this->cargo_model->getCountUnreadMessages($row->AuctionID);
                $check='';
                $msgdate='';
                if($cnt>0) {
                    if($row->MessageFlag==1) {
                        $MessageFlag='Unread';
                    } else {
                        $MessageFlag='Read';
                    }
                    //$check="<input class='chkNumber' type='checkbox' name='arr_auction_ids[]' value='".$row->AuctionID."'>";
                    $form_view="<a href='javascript: void(0);' onclick=getMessageDetails('".$row->AuctionID."') title='Click here to view all messages'><i class='fa fa-share-square fa_form_view'></i></a>";
                    $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->MsgDate)).'","'.$row->AuctionID.'","'.$MessageFlag.'","'.$row->EntityName.'","'.$form_view.'"],';
                    $i++;
                } else {
                    continue;
                }
            }
            $html .=trim($inhtml, ",");    
            $html .='] }';
        }else{
            $html ='{ "aaData": [';
            $html .=trim($inhtml, ",");    
            $html .='] }';
        }
        echo $html;
    }
    
    public function getMessageAuctionData1()
    {
        $data1=$this->cargo_model->getAuctionID();
        $html='';
        $inhtml ='';
        if($data1) {
            $auction_arr=array();
            foreach($data1 as $row1){
                array_push($auction_arr, $row1->AuctionID);
            }
            $data=$this->cargo_model->getMessageAuctionData($auction_arr);
            //print_r($data); die;
            
            $i=1;
            $html ='{ "aaData": [';
            
            foreach($data as $row){
                $cnt=$this->cargo_model->getCountUnreadMessages($row->AuctionID);
                
                $cargorecords=$this->cargo_model->getAllCargoRowsByAuctionID($row->AuctionID);
                //echo $cnt;die;
                $LpLaycanStartDate='';
                $LpLaycanEndDate='';
                $CargoCode='';
                $PortName='';
                foreach($cargorecords as $c){
                    if($c->LpLaycanStartDate) {
                        $LpLaycanStartDate=date('d-m-Y', strtotime($c->LpLaycanStartDate));
                    }
                    if($c->LpLaycanEndDate) {
                        $LpLaycanEndDate=date('d-m-Y', strtotime($c->LpLaycanEndDate));
                    }
                    if($c->Code) {
                        $CargoCode .=$c->Code.', ';
                    }
                    if($c->PortName) {
                        $PortName .=$c->PortName.', ';
                    }
                }
                $CargoCode=trim($CargoCode, ', ');
                $PortName=trim($PortName, ', ');
                
                $check='';
                $msgdate='';
                if($cnt>0) {
                    $MessageFlag="<a onclick=readMessage('".$row->AuctionID."',".$i.")><i class='fa fa-envelope' style='width: 35px; font-size: 30px;'></i><span><sup><span class='badge msg_cnt' id='ManageMsg".$i."'>".$cnt."</span></sup></span></a>";
                }else{
                    continue;
                }
                
                $inhtml .='["'.$i.'","'.date('d-m-Y H:i:s', strtotime($row->MsgDate)).'","'.$row->AuctionID.'","'.$PortName.'","'.$CargoCode.'","'.$LpLaycanStartDate.'","'.$LpLaycanEndDate.'","'.$MessageFlag.'","'.$row->EntityName.'"],';
                $i++;
            }
            $html .=trim($inhtml, ",");    
            $html .='] }';
            
        } else {
            $html ='{ "aaData": [';
            $html .=trim($inhtml, ",");    
            $html .='] }';
        }
        echo $html;
    
    }
    
    public function downloadSubMessages()
    {
        $data=$this->cargo_model->getMessageAuctionDetailsData();
        $html='';
        $i=1;
        extract($this->input->get());
        
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
            $html .='<th style="border: 1px solid;">S.No.</th>';
            $html .='<th style="border: 1px solid;">DateTime</th>';
            $html .='<th style="border: 1px solid;">Event</th>';
            $html .='<th style="border: 1px solid;">Page</th>';
            $html .='<th style="border: 1px solid;">Section</th>';
            $html .='<th style="border: 1px solid;">SubSection</th>';
            $html .='<th style="border: 1px solid;">Status</th>';
            $html .='</tr>';
            foreach($data as $row){
                $Statusflag='';
                if($row->StatusFlag=='1') {
                    $Statusflag='Unread';
                }else if($row->StatusFlag=='0') {
                    $Statusflag='Read';
                }
            
                $html .='<tr>';
                $html .='<td style="border: 1px solid;">'.$i.'</td>';
                $html .='<td style="border: 1px solid;">'.date('d-m-Y H:i:s', strtotime($row->UserDate)).'</td>';
                $html .='<td style="border: 1px solid;">'.$row->Event.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->Page.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->Section.'</td>';
                $html .='<td style="border: 1px solid;">'.$row->subSection.'</td>';
                $html .='<td style="border: 1px solid;">'.$Statusflag.'</td>';
                $html .='</tr>';
                $i++;
            }
            $html .='</table>';
            $pdfFilePath='sub_messages.pdf';
            include_once APPPATH.'third_party/mpdf.php';
            $this->load->library('m_pdf');
            $pdf = $this->m_pdf->load();
            $pdf->WriteHTML($html);
            $pdf->Output($pdfFilePath, "D");
        }
    
        if($Outputformat=='EXCEL') {
            $Content="S.No.,DateTime,Event,Page,Section,SubSection,Status \n";
            foreach($data as $row){
                $Statusflag='';
                if($row->StatusFlag=='1') {
                    $Statusflag='Unread';
                }else if($row->StatusFlag=='0') {
                    $Statusflag='Read';
                }
                $Content .= $i.",".date('d-m-Y H:i:s', strtotime($row->UserDate)).",".$row->Event.",".$row->Page.",".$row->Section.",".$row->subSection.",".$Statusflag."\n";    
                $i++;
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
            $xmloutput .="<SubMessagesDetails>\n";
            foreach($data as $row){
                $Statusflag='';
                if($row->StatusFlag=='1') {
                      $Statusflag='Unread';
                }else if($row->StatusFlag=='0') {
                     $Statusflag='Read';
                }
                $xmloutput .="\t<SubMessageDetail>\n";
                $xmloutput .="\t\t<DateTime>".date('d-m-Y H:i:s', strtotime($row->UserDate))."</DateTime>\n";
                $xmloutput .="\t\t<Event>".$row->Event."</Event>\n";
                $xmloutput .="\t\t<Page>".$row->Page."</Page>\n";
                $xmloutput .="\t\t<Section>".$row->Section."</Section>\n";
                $xmloutput .="\t\t<SubSection>".$row->subSection."</SubSection>\n";
                $xmloutput .="\t\t<Status>".$Statusflag."</Status>\n";
                $xmloutput .="</SubMessageDetail>\n";
            }
            $xmloutput .="</SubMessagesDetails>\n";
            header('Content-Type: application/xml'); 
            $FileName = 'sub_messages.xml';
            header('Content-Disposition: attachment; filename="' . $FileName . '"'); 
            echo $xmloutput;
            exit();    
        }

    }
     
    public function getCommenceDate()
    {
        $data=$this->cargo_model->getCommenceDate();
        $date1=date_create(date('Y-m-d H:i:s', strtotime($data->CommenceDate)));
        $date2=date_create(date('Y-m-d H:i:s'));
        $diff=date_diff($date2, $date1);
        $datediff=$diff->format("%R%a days");
        echo $data->CommenceDate.'_____'.$datediff;
    }
    
    public function get_EntityID_By_AuctionID()
    {
        $EntityID=$this->cargo_model->get_EntityID_By_AuctionID();
        if($EntityID) {
            echo $EntityID;
        }
        
    }
    
    
    public function saveBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->saveBrokerageCargo();
        echo json_encode($data);
    }
    
    public function updateBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->updateBrokerageCargo();
        echo json_encode($data);
    }
    
    
    public function updateOthersBAC()
    {
        $data['others']=$this->cargo_model->updateOthersBAC();
        echo json_encode($data);
    }
    
    
    public function saveAddCommCargo()
    {
        $data['addcom']=$this->cargo_model->saveAddCommCargo();
        echo json_encode($data);
    }
    
    public function saveOthersBAC()
    {
        $data['others']=$this->cargo_model->saveOthersBAC();
        echo json_encode($data);
    }
    
    public function deleteBrokerageCargo()
    {
        $flag=$this->cargo_model->deleteBrokerageCargo();
        if($flag) {
            echo 1;
        } else {
            echo 0;
        }
        
    }
    public function editBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->editBrokerageCargo();
        echo json_encode($data);
    }
    
    public function saveResponseBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->saveResponseBrokerageCargo();
        echo json_encode($data);
    }
    public function updateResponseBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->updateResponseBrokerageCargo();
        echo json_encode($data);
    }
    public function saveResponseAddCommCargo()
    {
        $data['addcom']=$this->cargo_model->saveResponseAddCommCargo();
        echo json_encode($data);
    }
    public function updateResponseOthersBAC()
    {
        $data['others']=$this->cargo_model->updateResponseOthersBAC();
        echo json_encode($data);
    }
    public function saveResponseOthersBAC()
    {
        $data['others']=$this->cargo_model->saveResponseOthersBAC();
        echo json_encode($data);
    }
    public function deleteResponseBrokerageCargo()
    {
        $flag=$this->cargo_model->deleteResponseBrokerageCargo();
        if($flag) {
            echo 1;
        } else {
            echo 0;
        }
        
    }
    public function editResponseBrokerageCargo()
    {
        $data['brokerage']=$this->cargo_model->editResponseBrokerageCargo_H();
        echo json_encode($data);
    }
    public function getMessagesByAuctionID()
    {
        $data['MsgDetails']=$this->cargo_model->getMessagesByAuctionID();
        $data['CargoDetails']=$this->cargo_model->getCargoDetailsByAuctionID();
        echo json_encode($data);
    }
    public function deleteDifferentialById()
    {
        $flg=$this->cargo_model->deleteDifferentialById();
        if($flg) {
            echo 1;
        } else {
            echo 2;
        }
    }
    public function allocateUserInBusinessProcess()
    {
        $data=$this->cargo_model->allocateUserInBusinessProcess();
        $data1=$this->cargo_model->allocateUserInBusinessProcess_h();
    }
    public function changeBusinessProcessStatus()
    {
        $data=$this->cargo_model->changeBusinessProcessStatus();
        $data1=$this->cargo_model->allocateUserInBusinessProcess_h();
        echo $data;
    }
    public function getBusinessProcess()
    {
        $data=$this->cargo_model->getBPByAuctionID();
        //print_r($data); die;
        $html='';
        foreach($data as $row) {
            if($row->finalization_completed_by==1) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($row->finalization_completed_by==2) {
                $completed_by_owner='No';
                $completed_by_invitee='Yes';
            } else if($row->finalization_completed_by==3) {
                $completed_by_owner='Yes';
                $completed_by_invitee='Yes';
            } else if($row->finalization_completed_by==4) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($row->finalization_completed_by==5) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($row->finalization_completed_by==6) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
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
            
            $html .='<tr>';
            $html .='<td>'.$name_of_process.'</td>';
            $html .='<td>'.$row->process_flow_sequence.'</td>';
            if($row->UserList) {
                $user_list=$this->cargo_model->getUserByIds($row->UserList);
                $html .='<td><a href="javascript:void(0);" onclick="getUserByEntityId('.$row->BPAID.','.$row->name_of_process.')">'.$user_list.'</a></td>';
            } else {
                $html .='<td><a href="javascript:void(0);" onclick="getUserByEntityId('.$row->BPAID.','.$row->name_of_process.')">Select user</a></td>';    
            }
            if($row->Status==1) {
                $status='<select class="form-control" onchange="changeStatus(this.value,'.$row->BPAID.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select>';
            }else if($row->Status==0) {
                $status='<select class="form-control" onchange="changeStatus(this.value,'.$row->BPAID.')"><option value="1" selected>Active</option><option value="0" selected>Inactive</option></select>';
            }
            if($row->on_subject_status==2) {
                $on_subject='<select class="form-control" onchange="changeOnSubject(this.value,'.$row->BPAID.')"><option value="1">Manual</option><option value="2" selected>Auto</option></select>';
            } else {
                $on_subject='<select class="form-control" onchange="changeOnSubject(this.value,'.$row->BPAID.')"><option value="1">Manual</option><option value="2">Auto</option></select>';    
            }
            if($row->lift_subject_status==2) {
                $lift_subject='<select class="form-control" onchange="changeLiftSubject(this.value,'.$row->BPAID.')"><option value="1">Manual</option><option value="2" selected>Auto</option></select>';    
            } else {
                $lift_subject='<select class="form-control" onchange="changeLiftSubject(this.value,'.$row->BPAID.')"><option value="1">Manual</option><option value="2">Auto</option></select>';
            }
            $html .='<td><a href="javascript:void(0);" onclick="getBusinessProcessMessageById('.$row->BPID.')">view</a></td>';
            $html .='<td>'.$completed_by_owner.'</td>';
            $html .='<td>'.$completed_by_invitee.'</td>';
            $html .='<td>'.$status.'</td>';
            $html .='<td>'.$on_subject.'</td>';
            $html .='<td>'.$lift_subject.'</td>';
            $html .='</tr>';
        }
        if($html=='') {
            echo '<tr><td colspan="7" >No record available</td></tr>';
        } else {
            echo $html;
        }
        
    }
    public function getInviteeBusinessProcess()
    {
        $data=$this->cargo_model->getInviteeBusinessProcess();
        $i=1;
        $entityid='';
        $html='';
        //print_r($data); die;
        foreach($data as $rw){
            $tbl='';
            if($entityid !=$rw->EntityID) {
                $Entity=$this->cargo_model->getEntityById($rw->EntityID);
                $tbl='</table><br><header id="view_header" "><div class="icons" style="height: 40px;"><a id="plus'.$i.'" onclick="hideadv(0,'.$i.');" style="display: none;" ><i class="fa fa-2x fa-plus fafa_cls1"></i></a><a id="minus'.$i.'" onclick="hideadv(1,'.$i.');" style="display: inline;" ><i class="fa fa-2x fa-minus fafa_cls1"></i></a></div><h5><b>'.$Entity->EntityName.'</b></h5></header><table class="table table-bordered table-striped" id="datatable-ajax'.$i.'" style="font-size: 14px;" ><tr><th>Process name</th><th>Default Sequence </th><th>Invitee User </th><th>Message display</th><th>Complete by record owner</th><th>Complete by invitee(s)</th><th>Set status</th></tr>';
            }
            $html .=$tbl;
            if($rw->name_of_process==1) {
                $name_of_process='Technical Vetting';
            } else if($rw->name_of_process==2) {
                $name_of_process='Business vetting approval';
            } else if($rw->name_of_process==3) {
                $name_of_process='Counter party risk assessment';
            } else if($rw->name_of_process==4) {
                $name_of_process='Compliance risk assessment';
            } else if($rw->name_of_process==5) {
                $name_of_process='Authorization for quotes (by broker)';
            } else if($rw->name_of_process==6) {
                $name_of_process='Charter party authorization';
            } else if($rw->name_of_process==7) {
                $name_of_process='Fixture note authorization';
            } else if($rw->name_of_process==8) {
                $name_of_process='Approval for quotes authorization (by record owner)';
            } else if($rw->name_of_process==9) {
                $name_of_process='C/P on subjects (charterer)';
            } else if($rw->name_of_process==10) {
                $name_of_process='C/P on subjects (Shipowner/Broker)';
            }
            
            $view='<a href="javascript:void(0)" onclick="getBusinessMessage('.$rw->BPID.')">view</a>';
            
            if($rw->finalization_completed_by==1) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($rw->finalization_completed_by==2) {
                $completed_by_owner='No';
                $completed_by_invitee='Yes';
            } else if($rw->finalization_completed_by==3) {
                $completed_by_owner='Yes';
                $completed_by_invitee='Yes';
            } else if($rw->finalization_completed_by==4) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($rw->finalization_completed_by==5) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            } else if($rw->finalization_completed_by==6) {
                $completed_by_owner='Yes';
                $completed_by_invitee='No';
            }
            
            if($rw->Status==1) {
                $status='<select class="form-control" onchange="changeStatus(this.value,'.$rw->BPAID.')"><option value="1" selected>Active</option><option value="0">Inactive</option></select>';
            }else if($rw->Status==0) {
                $status='<select class="form-control" onchange="changeStatus(this.value,'.$rw->BPAID.')"><option value="1" selected>Active</option><option value="0" selected>Inactive</option></select>';
            }
            
            $html .='<tr><td>'.$name_of_process.'</td><td>'.$rw->process_flow_sequence.'</td><td>'.$rw->FirstName.' '.$rw->LastName.'</td><td>'.$view.'</td><td>'.$completed_by_owner.'</td><td>'.$completed_by_invitee.'</td><td>'.$status.'</td></tr>';
            
            $entityid=$rw->EntityID;
            $i++;
        }
        //$html=trim($html,"</table>");
        if($html !='') {
            $html=substr($html, 8);
            $html .='</table>';
        }
        echo $html;
        
    }
    public function updateEditableField()
    {
        $data=$this->cargo_model->updateEditableField();
        echo $data;
    }
    public function getEditableField()
    {
        $data=$this->cargo_model->getEditableField();
        echo json_encode($data);
    }
    public function getLpDpDates()
    {
        $data['record']=$this->cargo_model->getLpDpDates();
        $data['disports']=$this->cargo_model->getDisportsDates();
        
        echo json_encode($data);
    }
    public function changeQuoteLimit()
    {
        $ret=$this->cargo_model->changeQuoteLimit();
        if($ret) {
            echo 1;
        } else {
            echo 2;
        }
        
    }
    public function saveCargoDisports()
    {
        //print_r($_POST); die;
        $flg=$this->cargo_model->saveCargoDisports();
        if($flg) {
            $data['records']=$this->cargo_model->getCargoDisportsByCargoID();
            $data['flg']=1;
        } else{
            $data['flg']=0;
        }
        echo json_encode($data);
    }
    public function deleteCargoDisports()
    {
        $flg=$this->cargo_model->deleteCargoDisports();
        if($flg) {
            $data['records']=$this->cargo_model->getCargoDisportsByCargoID();
            $data['flg']=1;
        } else{
            $data['flg']=0;
        }
        echo json_encode($data);
    }
    
    public function cloneCargoDisports()
    {
        $flg=$this->cargo_model->cloneCargoDisports();
        if($flg) {
            $data['records']=$this->cargo_model->getCargoDisportsByCargoID();
            $data['flg']=1;
        } else{
            $data['flg']=0;
        }
        echo json_encode($data);
    }
    public function getCargoDisportsById()
    {
        $data['disport']=$this->cargo_model->getCargoDisportsById();
        $data['excepted_periods']=$this->cargo_model->getExceptedPeriodEventsByDisportId();
        $data['tendering_pre_conditions']=$this->cargo_model->getTenderingPreConditionsByDisportId();
        $data['acceptance_pre_conditions']=$this->cargo_model->getAcceptancePreConditionByDisportId();
        $data['office_hours']=$this->cargo_model->getOfficeHoursByDisportId();
        $data['laytime_commencement']=$this->cargo_model->getLaytimeCommencementByDisportId();
        echo json_encode($data);
    }
    public function get_cargo_data_for_estimate()
    {
        $AuctionID=$this->input->post('AuctionId');
        $data['data1']=$this->cargo_model->getCargoDataById();
        $data['Differential']=$this->cargo_model->getQuoteDifferentialDataById();
        echo json_encode($data);
    }
    public function get_new_quote_html_details()
    {
        $data['quote']=$this->cargo_model->getQuoteDifferentialDetails();
        $data['references']=$this->cargo_model->getQuoteDisportReferencesDetails();
        $type='quote';
        $data['docs']=$this->cargo_model->get_cargo_document_details($type);
        echo json_encode($data);
        
    }
    public function getAuctionBankDetails()
    {
        $data['records']=$this->cargo_model->getBankDetailsByAuctionID();
        echo json_encode($data);
    }
    
    public function getBankDetailsByABID()
    {
        $ABID=$this->input->post('ABID');
        $data['record']=$this->cargo_model->getBankDetailsByABID($ABID);
        echo json_encode($data);
    }
    public function changeBankStatusByABID()
    {
        $flg=$this->cargo_model->changeBankStatusByABID();
        if($flg) {
            echo 1;
        } else {
            echo 0;
        }
                
    }
    public function getInviteeBankDetailsProcess()
    {
        $data=$this->cargo_model->getInviteeBankDetailsByAuctionID();
        $invitee_data=$this->cargo_model->getInviteeDetailsByAuctionID();
        
        $i=1;
        $entityid='';
        $html='';
        $invEntityArr=array();
        //print_r($data); die;
        foreach($data as $rw){
            $tbl='';
            if($entityid !=$rw->ForEntityID) {
                array_push($invEntityArr, $rw->ForEntityID);
                $Entity=$this->cargo_model->getEntityById($rw->ForEntityID);
                $tbl='</table><br><header id="view_header"><div class="icons" style="height: 40px;"><a id="bank_plus'.$i.'" onclick="bank_hideadv(0,'.$i.');" style="display: none;" ><i class="fa fa-2x fa-plus fafa_cls1"></i></a><a id="bank_minus'.$i.'" onclick="bank_hideadv(1,'.$i.');" style="display: inline;" ><i class="fa fa-2x fa-minus fafa_cls1"></i></a></div><h5><b>'.$Entity->EntityName.'</b></h5></header><table class="table table-bordered table-striped" id="bank_datatable'.$i.'" style="font-size: 14px;" ><tr><th>Account Name</th><th>Account Number</th><th>Bank Name</th><th>Country</th><th>State</th><th>Applies to</th><th>Full View</th><th>Set Status</th></tr>';
            }
            $html .=$tbl;
            $AppliesToArr=explode(",", $rw->AppliesTo);
            $AppliesTo='';
            for($ii=0; $ii<count($AppliesToArr); $ii++){
                if($AppliesToArr[$ii]==1) {
                    $AppliesTo .='Freight payment, ';
                } else if($AppliesToArr[$ii]==2) {
                    $AppliesTo .='Miscellaneous payment, ';
                } else if($AppliesToArr[$ii]==3) {
                    $AppliesTo .='Hire payment, ';
                } else if($AppliesToArr[$ii]==4) {
                    $AppliesTo .='Freight invoice, ';
                } else if($AppliesToArr[$ii]==5) {
                    $AppliesTo .='Miscellaneous invoice, ';
                } else if($AppliesToArr[$ii]==6) {
                    $AppliesTo .='Hire invoice, ';
                }
            }
            
            $AppliesTo=trim($AppliesTo, ", ");
            
            $view='<a href="javascript:void(0)" onclick="getBankHtmlView('.$rw->ABID.')">view</a>';
            
            if($rw->BankStatus==1) {
                $status='<select class="form-control" onchange="changeBankStatus('.$rw->ABID.',this.value)"><option value="1" selected>Active</option><option value="2">Inactive</option></select>';
            }else if($rw->BankStatus==2) {
                $status='<select class="form-control" onchange="changeBankStatus('.$rw->ABID.',this.value)"><option value="1" selected>Active</option><option value="2" selected>Inactive</option></select>';
            }
            
            $html .='<tr><td>'.$rw->AccountName.'</td><td>'.$rw->AccountNumber.'</td><td>'.$rw->BankName.'</td><td>'.$rw->CountryName.'</td><td>'.$rw->StateName.'</td><td>'.$AppliesTo.'</td><td>'.$view.'</td><td>'.$status.'</td></tr>';
            
            $entityid=$rw->EntityID;
            $i++;
        }
        
        foreach($invitee_data as $inv){
            if(in_array($inv->EntityID, $invEntityArr)) {
                // do nothing.
            } else {
                array_push($invEntityArr, $inv->EntityID);
                $Entity=$this->cargo_model->getEntityById($inv->EntityID);
                $html .='</table><br><header id="view_header"><div class="icons" style="height: 40px;"><a id="bank_plus'.$i.'" onclick="bank_hideadv(0,'.$i.');" style="display: none;" ><i class="fa fa-2x fa-plus fafa_cls1"></i></a><a id="bank_minus'.$i.'" onclick="bank_hideadv(1,'.$i.');" style="display: inline;" ><i class="fa fa-2x fa-minus fafa_cls1"></i></a></div><h5><b>'.$Entity->EntityName.'</b></h5></header><table class="table table-bordered table-striped" id="bank_datatable'.$i.'" style="font-size: 14px;" ><tr><th>Account Name</th><th>Account Number</th><th>Bank Name</th><th>Country</th><th>State</th><th>Applies to</th><th>Full View</th><th>Set Status</th></tr><tr><td colspan="8">No bank details found.</td></tr>';
            }
            $i++;
        }
        
        //$html=trim($html,"</table>");
        if($html !='') {
            $html=substr($html, 8);
            $html .='</table>';
        }
        echo $html;
        //echo json_encode($data);
        
    }
    public function getPreCargoData()
    {
        $AuctionID=$this->input->post('AuctionID');
        $data['PeriodEvents']=$this->cargo_model->getAllExceptedPeriodEvents($AuctionID);
        $data['TenderingPreCond']=$this->cargo_model->getAllNORTenderingPreConditions($AuctionID);
        $data['AcceptancePreCond']=$this->cargo_model->getAllNORAcceptancePreConditions($AuctionID);
        echo json_encode($data);
    }
    
    public function get_alert_html_details()
    {
        $data=$this->cargo_model->get_alert_html_details();
        $type='alerts';
        $data1=$this->cargo_model->get_cargo_document_details($type);
        $html='';
        if($data) {
            if($data->AuctionCommences==1) {
                 $AuctionCommences='Days before laycan start date';
            }else if($data->AuctionCommences==2) {
                $AuctionCommences='Defined date';
            } 
            $html .='<h4><B>Alert </B></h4>
					<div class="form-group">
					<label class="control-label col-lg-5">Set commencement date and alerts : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data->CommenceAlertFlag.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Bidding commences on : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$AuctionCommences.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">First loadport laycan start date is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->LayCanStartDate)).'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Days before bid commences : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data->CommenceDaysBefore.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Bid commencement date is : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y H:i:s', strtotime($data->CommenceDate)).'</label>
					</div>';
            if(date('d-m-Y', strtotime($data->AuctionCommenceDefinedDate)) !='01-01-1970') {
                $html .='<div class="form-group">
				<label class="control-label col-lg-5">Bid commence defined date : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.date('d-m-Y', strtotime($data->AuctionCommenceDefinedDate)).'</label>
				</div>';
            }
            $AuctionValidity='';
            if($data->AuctionValidity) {
                $AuctionValidity=$data->AuctionValidity.' days';
            }
            if($data->auctionvalidityhour) {
                $AuctionValidity .=' '.$data->auctionvalidityhour.' hours';
            }
            if($data->AuctionValidMinutes) {
                $AuctionValidity .=' '.$data->AuctionValidMinutes.' minutes';
            }
            $html .='<div class="form-group">
				<label class="control-label col-lg-5">Bid validity : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$AuctionValidity.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Bid ceases on (date) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->AuctionCeases.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Alert schedule (days before commencement) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->AlertBeforeCommence.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Alert notification for commencement on : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->AlertNotificationCommence.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Alert schedule (days before closing) : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->AlertBeforeClosing.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Alert notification for closing : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->AlertNotificationClosing.'</label>
				</div>
				<div class="form-group">
				<label class="control-label col-lg-5">Closing dates to include in invitation : </label>
				<label class="control-label col-lg-7" style="text-align: left;">'.$data->IncludeClosing.'</label>
				</div>';
            if($data->AuctionerComments) {
                $html .='<div class="form-group" ><label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments by cargo owner : </label><label class="control-label col-lg-7" style="text-align: left;">'.$data->AuctionerComments.'</label></label></div>';
            
            }
            if($data->InviteesComments) {
                $html .='<div class="form-group"><label class="control-label col-lg-12" style="text-align: left;"><label class="control-label col-lg-5" style="text-align: right; font-weight: 100;">Comments for Invitees : </label><label class="control-label col-lg-7" style="text-align: left;">'.$data->InviteesComments.'</label></label></div>';
            }
            if(count($data1) > 0) {
                $html .='<h4><B>Alerts Documents</B></h4>
					<div class="form-group">
					<label class="control-label col-lg-5">Type of Document : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentType.'</label>
					</div>
					<div class="form-group">
					<label class="control-label col-lg-5">Name or Title of Document : </label>
					<label class="control-label col-lg-7" style="text-align: left;">'.$data1[0]->DocumentTitle.'</label>
					</div>';
                if($data1[0]->ToDisplay==1) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Display (cargo owner) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
					</div>';
                }else if($data1[0]->ToDisplay==0) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Display (cargo owner) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">No</label>
					</div>';
                }
                if($data1[0]->ToDisplayInvitee==1) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Display (invitee) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">Yes</label>
					</div>';
                }else if($data1[0]->ToDisplayInvitee==0) {
                    $html .='<div class="form-group">
					<label class="control-label col-lg-5">Display (invitee) : </label>
					<label class="control-label col-lg-7" style="text-align: left;">No</label>
					</div>';
                }
                foreach($data1 as $doc){
                    $html .='<div class="form-group">
						<label class="control-label col-lg-5">File name : </label>
						<label class="control-label col-lg-7" style="text-align: left;">'.$doc->FileName.'</label>
						</div>';
                }
            }
            $html .='<br><hr style="background-color: black; height: 2px;"><br>';
        }
        echo $html;
    }
    
    public function getCargoExtendTimeRecord()
    {
        $AuctionID=$this->input->post('AuctionID');
        $data['record']=$this->cargo_model->getCargoExtendTimeRecord($AuctionID);
        
        $e_time=0;
        if($data['record']->ExtendTime1) {
            $e_time=$e_time + $data['record']->ExtendTime1;
        }
        if($data['record']->ExtendTime2) {
            $e_time=$e_time + $data['record']->ExtendTime2;
        }
        if($data['record']->ExtendTime3) {
            $e_time=$e_time + $data['record']->ExtendTime3;
        }
        
        $new_time=strtotime($data['record']->AuctionCeases) + ($e_time*60);
        //print_r($new_time); die;
        $AuctionCeases=date('Y-m-d H:i:s', $new_time);
        //$data['CeaseDate']=$AuctionCeases;
        $data['remdate']=$this->dateDiff($AuctionCeases);
        echo json_encode($data);
    }
    
    public function saveCargoExtendTimeRecord()
    {
        $AuctionID=$this->input->post('AuctionID');
        $data['ret']=$this->cargo_model->saveCargoExtendTimeRecord();
        $data['record']=$this->cargo_model->getCargoExtendTimeRecord($AuctionID);
        
        $e_time=0;
        if($data['record']->ExtendTime1) {
            $e_time=$e_time + $data['record']->ExtendTime1;
        }
        if($data['record']->ExtendTime2) {
            $e_time=$e_time + $data['record']->ExtendTime2;
        }
        if($data['record']->ExtendTime3) {
            $e_time=$e_time + $data['record']->ExtendTime3;
        }
        $new_time=strtotime($data['record']->AuctionCeases) + ($e_time*60);
        //print_r($new_time); die;
        $AuctionCeases=date('Y-m-d H:i:s', $new_time);
        //$data['CeaseDate']=$AuctionCeases;
        $data['remdate']=$this->dateDiff($AuctionCeases);
        echo json_encode($data);
    }
    
    public function changeBusinessProcessOnSubject()
    {
        $data=$this->cargo_model->changeBusinessProcessOnSubject();
        $data1=$this->cargo_model->allocateUserInBusinessProcess_h();
        echo $data;
    }
    
    public function changeBusinessProcessLiftSubject()
    {
        $data=$this->cargo_model->changeBusinessProcessLiftSubject();
        $data1=$this->cargo_model->allocateUserInBusinessProcess_h();
        echo $data;
    }


}

