<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
header('Access-Control-Allow-Origin: *');
    
class Admincp extends CI_Controller
{
    /**
     * Developer Name : harmeet singh
     *    
     * Comapny Name : HigrooveSystems 
     * 
     *
     * Create Date : 13-09-2016
     */
    function __construct()
    {
        parent::__construct();
        ob_start();
        error_reporting(0);
        $this->load->library('session');
        $this->load->model('admincp_model', '', true); 
    } 
    /**
     * ---------------laytime-freetime-condition-------------------------------
     */
    public function getLaytimeFreetimeCondition()
    {
        $data=$this->admincp_model->getLaytimeFreetimeCondition();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLayTimeFreeTimeConditionMaster()
    {
        $data=$this->admincp_model->saveLayTimeFreeTimeConditionMaster();
        echo $data;
    }
    public function getLaytimeFreetimeConditionById()
    {
        $data=$this->admincp_model->getLaytimeFreetimeConditionById();
        echo json_encode($data);
    }
    public function updateLayTimeFreeTimeConditionMaster()
    {
        $data=$this->admincp_model->updateLayTimeFreeTimeConditionMaster();
        echo $data;
    }
    public function deleteLayTimeFreeTimeConditionMaster()
    {
        $data=$this->admincp_model->deleteLayTimeFreeTimeConditionMaster();
        echo $data;
    }
    /*---------------loading-discharge-rate-measure-------------------------------*/
    public function getLoadingDischargeRateMeasure()
    {
        $data=$this->admincp_model->getLoadingDischargeRateMeasure();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLoadingDischargeRateMeasure()
    {
        $data=$this->admincp_model->saveLoadingDischargeRateMeasure();
        echo $data;
    }
    public function getLoadingDischargeRateMeasureById()
    {
        $data=$this->admincp_model->getLoadingDischargeRateMeasureById();
        echo json_encode($data);
    }
    public function updateLoadingDischargeRateMeasure()
    {
        $data=$this->admincp_model->updateLoadingDischargeRateMeasure();
        echo $data;
    }
    public function deleteLoadingDischargeRateMeasure()
    {
        $data=$this->admincp_model->deleteLoadingDischargeRateMeasure();
        echo $data;
    }
    /*---------------loading-discharge-term-------------------------------*/
    public function getLoadingDischargeTerm()
    {
        $data=$this->admincp_model->getLoadingDischargeTerm();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLoadingDischargeTerm()
    {
        $data=$this->admincp_model->saveLoadingDischargeTerm();
        echo $data;
    }
    public function getLoadingDischargeTermById()
    {
        $data=$this->admincp_model->getLoadingDischargeTermById();
        echo json_encode($data);
    }
    public function updateLoadingDischargeTerm()
    {
        $data=$this->admincp_model->updateLoadingDischargeTerm();
        echo $data;
    }
    public function deleteLoadingDischargeTerm()
    {
        $data=$this->admincp_model->deleteLoadingDischargeTerm();
        echo $data;
    }
    /*---------------loading-discharge-period-rate-condition-------------------------------*/
    public function getLoadingDischargePeriodRateCondition()
    {
        $data=$this->admincp_model->getLoadingDischargePeriodRateCondition();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLoadinDischargeRateCond()
    {
        $data=$this->admincp_model->saveLoadinDischargeRateCond();
        echo $data;
    }
    public function getLoadinDischargeRateCondById()
    {
        $data=$this->admincp_model->getLoadinDischargeRateCondById();
        echo json_encode($data);
    }
    public function updateLoadinDischargeRateCond()
    {
        $data=$this->admincp_model->updateLoadinDischargeRateCond();
        echo $data;
    }
    public function deleteLoadinDischargeRateCond()
    {
        $data=$this->admincp_model->deleteLoadinDischargeRateCond();
        echo $data;
    }
    /*---------------laytime-ceases-condition-------------------------------*/
    public function getLaytimeCeasesCondition()
    {
        $data=$this->admincp_model->getLaytimeCeasesCondition();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLaytimeCeasesCondition()
    {
        $data=$this->admincp_model->saveLaytimeCeasesCondition();
        echo $data;
    }
    public function getLaytimeCeasesConditionById()
    {
        $data=$this->admincp_model->getLaytimeCeasesConditionById();
        echo json_encode($data);
    }
    public function updateLaytimeCeasesCondition()
    {
        $data=$this->admincp_model->updateLaytimeCeasesCondition();
        echo $data;
    }
    public function deleteLaytimeCeasesCondition()
    {
        $data=$this->admincp_model->deleteLaytimeCeasesCondition();
        echo $data;
    }
    /*---------------laytime-comm-condition-------------------------------*/
    public function getLaytimeCommCondition()
    {
        $data=$this->admincp_model->getLaytimeCommCondition();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLaytimeCommCondition()
    {
        $data=$this->admincp_model->saveLaytimeCommCondition();
        echo $data;
    }
    public function getLaytimeCommConditionById()
    {
        $data=$this->admincp_model->getLaytimeCommConditionById();
        echo json_encode($data);
    }
    public function updateLaytimeCommCondition()
    {
        $data=$this->admincp_model->updateLaytimeCommCondition();
        echo $data;
    }
    public function deleteLaytimeCommCondition()
    {
        $data=$this->admincp_model->deleteLaytimeCommCondition();
        echo $data;
    }
    /*---------------nor-accept-preCond-------------------------------*/
    public function getNorAcceptPreCond()
    {
        $data=$this->admincp_model->getNorAcceptPreCond();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveNorAcceptPreCond()
    {
        $data=$this->admincp_model->saveNorAcceptPreCond();
        echo $data;
    }
    public function getNorAcceptPreCondById()
    {
        $data=$this->admincp_model->getNorAcceptPreCondById();
        echo json_encode($data);
    }    
    public function updateNorAcceptPreCond()
    {
        $data=$this->admincp_model->updateNorAcceptPreCond();
        echo $data;
    }
    public function deleteNorAcceptPreCond()
    {
        $data=$this->admincp_model->deleteNorAcceptPreCond();
        echo $data;
    }
    /*---------------nor-tender-preCond-------------------------------*/
    public function getNorTenderPreCond()
    {
        $data=$this->admincp_model->getNorTenderPreCond();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveNorTenderPreCond()
    {
        $data=$this->admincp_model->saveNorTenderPreCond();
        echo $data;
    }
    public function getNorTenderPreCondById()
    {
        $data=$this->admincp_model->getNorTenderPreCondById();
        echo json_encode($data);
    }    
    public function updateNorTenderPreCond()
    {
        $data=$this->admincp_model->updateNorTenderPreCond();
        echo $data;
    }
    public function deleteNorTenderPreCond()
    {
        $data=$this->admincp_model->deleteNorTenderPreCond();
        echo $data;
    }
    /*---------------nor-tender-preCond-------------------------------*/
    public function getTendering()
    {
        $data=$this->admincp_model->getTendering();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveNorTendering()
    {
        $data=$this->admincp_model->saveNorTendering();
        echo $data;
    }
    public function getNorTenderingById()
    {
        $data=$this->admincp_model->getNorTenderingById();
        echo json_encode($data);
    }
    public function updateNorTendering()
    {
        $data=$this->admincp_model->updateNorTendering();
        echo $data;
    }
    public function deleteNorTendering()
    {
        $data=$this->admincp_model->deleteNorTendering();
        echo $data;
    }
    /*---------------steve-doring-terms-------------------------------*/
    public function getSteveDoringTerms()
    {
        $data=$this->admincp_model->getSteveDoringTerms();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveSteveDoringTerms()
    {
        $data=$this->admincp_model->saveSteveDoringTerms();
        echo $data;
    }
    public function getSteveDoringTermsById()
    {
        $data=$this->admincp_model->getSteveDoringTermsById();
        echo json_encode($data);
    }
    public function updateSteveDoringTerms()
    {
        $data=$this->admincp_model->updateSteveDoringTerms();
        echo $data;
    }
    public function deleteSteveDoringTerms()
    {
        $data=$this->admincp_model->deleteSteveDoringTerms();
        echo $data;
    }
    /*---------------baf-platts-regions-------------------------------*/
    public function getBafPlattsRegions()
    {
        $data=$this->admincp_model->getBafPlattsRegions();
        $inhtml='';
        $html='{ "aaData": [';
        foreach($data as $row) {
            $status='';
            if($row->ActiveFlag) {
                $status='Active';    
            } else {
                $status='Inactive';    
            }
            $Code=str_replace(',', ' ', $row->RegionName);
            $Code=str_replace('"', ' ', $Code);
            $Code=str_replace("'", ' ', $Code);
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveBafPlattsRegions()
    {
        $data=$this->admincp_model->saveBafPlattsRegions();
        echo $data;
    }
    public function getBafPlattsRegionsById()
    {
        $data=$this->admincp_model->getBafPlattsRegionsById();
        echo json_encode($data);
    }
    public function updateBafPlattsRegions()
    {
        $data=$this->admincp_model->updateBafPlattsRegions();
        echo $data;
    }
    public function deleteBafPlattsRegions()
    {
        $data=$this->admincp_model->deleteBafPlattsRegions();
        echo $data;
    }
    /*---------------business-area-------------------------------*/
    public function getBusinessArea()
    {
        $data=$this->admincp_model->getBusinessArea();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveBusinessArea()
    {
        $data=$this->admincp_model->saveBusinessArea();
        echo $data;
    }
    public function getBusinessAreaById()
    {
        $data=$this->admincp_model->getBusinessAreaById();
        echo json_encode($data);
    }
    public function updateBusinessArea()
    {
        $data=$this->admincp_model->updateBusinessArea();
        echo $data;
    }
    public function deleteBusinessArea()
    {
        $data=$this->admincp_model->deleteBusinessArea();
        echo $data;
    }
    /*---------------cargo-servicing-basis-------------------------------*/
    public function getCargoServicingBasis()
    {
        $data=$this->admincp_model->getCargoServicingBasis();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveCargoServicingBasis()
    {
        $data=$this->admincp_model->saveCargoServicingBasis();
        echo $data;
    }
    public function getCargoServicingBasisById()
    {
        $data=$this->admincp_model->getCargoServicingBasisById();
        echo json_encode($data);
    }
    public function updateCargoServicingBasis()
    {
        $data=$this->admincp_model->updateCargoServicingBasis();
        echo $data;
    }
    public function deleteCargoServicingBasis()
    {
        $data=$this->admincp_model->deleteCargoServicingBasis();
        echo $data;
    }
    /*---------------charter-party-form-------------------------------*/
    public function getcharterPartyForm()
    {
        $data=$this->admincp_model->getcharterPartyForm();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveCharterPartyForm()
    {
        $data=$this->admincp_model->saveCharterPartyForm();
        echo $data;
    }
    public function getcharterPartyFormById()
    {
        $data=$this->admincp_model->getcharterPartyFormById();
        echo json_encode($data);
    }
    public function updateCharterPartyForm()
    {
        $data=$this->admincp_model->updateCharterPartyForm();
        echo $data;
    }
    public function deleteCharterPartyForm()
    {
        $data=$this->admincp_model->deleteCharterPartyForm();
        echo $data;
    }    
    /*---------------freight-payment-invoice-------------------------------*/
    public function getFreightPaymentInvoice()
    {
        $data=$this->admincp_model->getFreightPaymentInvoice();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveFreightPaymentInvoice()
    {
        $data=$this->admincp_model->saveFreightPaymentInvoice();
        echo $data;
    }
    public function getFreightPaymentInvoiceById()
    {
        $data=$this->admincp_model->getFreightPaymentInvoiceById();
        echo json_encode($data);
    }
    public function updateFreightPaymentInvoice()
    {
        $data=$this->admincp_model->updateFreightPaymentInvoice();
        echo $data;
    }
    public function deleteFreightPaymentInvoice()
    {
        $data=$this->admincp_model->deleteFreightPaymentInvoice();
        echo $data;
    }
    /*---------------freight-payment-event-------------------------------*/
    public function getFreightPaymentEvent()
    {
        $data=$this->admincp_model->getFreightPaymentEvent();
        $inhtml='';
        $html='{ "aaData": [';
        foreach($data as $row) {
            $status='';
            $ST_FreightEventType='';
            if($row->ActiveFlag) {
                $status='Active';    
            } else {
                $status='Inactive';    
            }
            
            if($row->ST_FreightEventType==645) {
                $ST_FreightEventType='Initial Payment/Invoice event';
            } else if($row->ST_FreightEventType==646) {
                $ST_FreightEventType='Balance Payment/Invoice event';
            } else if($row->ST_FreightEventType==647) {
                $ST_FreightEventType='Payment/Invoice Event';
            }
            
            $Code=str_replace(',', ' ', $row->Code);
            $Code=str_replace('"', ' ', $Code);
            $Code=str_replace("'", ' ', $Code);
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$ST_FreightEventType.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveFreightPaymentEvent()
    {
        $data=$this->admincp_model->saveFreightPaymentEvent();
        echo $data;
    }
    public function getFreightPaymentEventById()
    {
        $data=$this->admincp_model->getFreightPaymentEventById();
        echo json_encode($data);
    }
    public function updateFreightPaymentEvent()
    {
        $data=$this->admincp_model->updateFreightPaymentEvent();
        echo $data;
    }
    public function deleteFreightPaymentEvent()
    {
        $data=$this->admincp_model->deleteFreightPaymentEvent();
        echo $data;
    }
    /*---------------trade-area-------------------------------*/
    public function getTradeArea()
    {
        $data=$this->admincp_model->getTradeArea();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveTradeArea()
    {
        $data=$this->admincp_model->saveTradeArea();
        echo $data;
    }
    public function getTradeAreaById()
    {
        $data=$this->admincp_model->getTradeAreaById();
        echo json_encode($data);
    }
    public function updateTradeArea()
    {
        $data=$this->admincp_model->updateTradeArea();
        echo $data;
    }
    public function deleteTradeArea()
    {
        $data=$this->admincp_model->deleteTradeArea();
        echo $data;
    }
    /*---------------laytime-counting-------------------------------*/
    public function getLaytimeCounting()
    {
        $data=$this->admincp_model->getLaytimeCounting();
        $inhtml='';
        $html='{ "aaData": [';
        foreach($data as $row) {
            $status='';
            if($row->ActiveFlag) {
                $status='Active';    
            } else {
                $status='Inactive';    
            }
            $dis_all='';
            if($row->ST_DisplayAllCP==1) {
                $dis_all='Yes';    
            } else {
                $dis_all='No';    
            }
            $Code=str_replace(',', ' ', $row->Code);
            $Code=str_replace('"', ' ', $Code);
            $Code=str_replace("'", ' ', $Code);
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$dis_all.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveLaytimeCounting()
    {
        $data=$this->admincp_model->saveLaytimeCounting();
        echo $data;
    }
    public function getLaytimeCountingById()
    {
        $data=$this->admincp_model->getLaytimeCountingById();
        echo json_encode($data);
    }
    public function updateLaytimeCounting()
    {
        $data=$this->admincp_model->updateLaytimeCounting();
        echo $data;
    }
    public function deleteLaytimeCounting()
    {
        $data=$this->admincp_model->deleteLaytimeCounting();
        echo $data;
    }
    /*---------------excepted-period-events-------------------------------*/
    public function getExceptedPeriodEvents()
    {
        $data=$this->admincp_model->getExceptedPeriodEvents();
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
            $Description=str_replace(',', ' ', $row->Description);
            $Description=str_replace('"', ' ', $Description);
            $Description=str_replace("'", ' ', $Description);
            $Description = str_replace(array("\n","\r\n","\r"), ' ', $Description);
            $check="<input class='chkNumber' type='checkbox' name='ID[]' value='".$row->ID."'>";
            $inhtml .='["'.$check.'","'.$Code.'","'.$Description.'","'.$row->EntityName.'","'.$status.'"],';
            
        }
        $html .=trim($inhtml, ",");    
        $html .='] }';
        echo $html;    
    }
    public function saveExceptedPeriodEvents()
    {
        $data=$this->admincp_model->saveExceptedPeriodEvents();
        echo $data;
    }
    public function getExceptedPeriodEventsById()
    {
        $data=$this->admincp_model->getExceptedPeriodEventsById();
        echo json_encode($data);
    }
    public function updateExceptedPeriodEvents()
    {
        $data=$this->admincp_model->updateExceptedPeriodEvents();
        echo $data;
    }
    public function deleteExceptedPeriodEvents()
    {
        $data=$this->admincp_model->deleteExceptedPeriodEvents();
        echo $data;
    }
}

