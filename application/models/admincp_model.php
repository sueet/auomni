<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class Admincp_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();        
        $this->load->library('session');
    } 
    
    //---------------laytime-freetime-condition-------------------------------
    
    public function getLaytimeFreetimeCondition()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_LayTimeFreeTimeConditionMaster');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLayTimeFreeTimeConditionMaster()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LayTimeFreeTimeConditionMaster', $data);
    }
    
    public function getLaytimeFreetimeConditionById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_LayTimeFreeTimeConditionMaster');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLayTimeFreeTimeConditionMaster()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LayTimeFreeTimeConditionMaster', $data);
    }
    
    public function deleteLayTimeFreeTimeConditionMaster()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LayTimeFreeTimeConditionMaster');
    }
    
    
    //---------------loading-discharge-rate-measure-------------------------------
    public function getLoadingDischargeRateMeasure()
    {
        $this->db->select('udt_CP_LoadingDischargeRateMeasureUnitsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargeRateMeasureUnitsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargeRateMeasureUnitsMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_LoadingDischargeRateMeasureUnitsMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLoadingDischargeRateMeasure()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LoadingDischargeRateMeasureUnitsMaster', $data);
    }
    
    public function getLoadingDischargeRateMeasureById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_LoadingDischargeRateMeasureUnitsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargeRateMeasureUnitsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargeRateMeasureUnitsMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_LoadingDischargeRateMeasureUnitsMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLoadingDischargeRateMeasure()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LoadingDischargeRateMeasureUnitsMaster', $data);
    }
    
    public function deleteLoadingDischargeRateMeasure()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LoadingDischargeRateMeasureUnitsMaster');
    }
    
    //---------------loading-discharge-term-------------------------------
    public function getLoadingDischargeTerm()
    {
        $this->db->select('udt_CP_LoadingDischargeTermsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargeTermsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargeTermsMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_LoadingDischargeTermsMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLoadingDischargeTerm()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LoadingDischargeTermsMaster', $data);
    }
    
    public function getLoadingDischargeTermById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->select('udt_CP_LoadingDischargeTermsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargeTermsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargeTermsMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_LoadingDischargeTermsMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLoadingDischargeTerm()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LoadingDischargeTermsMaster', $data);
    }
    
    public function deleteLoadingDischargeTerm()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LoadingDischargeTermsMaster');
    }
    
    //---------------loading-discharge-period-rate-condition-------------------------------
    
    public function getLoadingDischargePeriodRateCondition()
    {
        $this->db->select('udt_CP_LoadingDischargePeriodRateConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargePeriodRateConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargePeriodRateConditionMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_LoadingDischargePeriodRateConditionMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLoadinDischargeRateCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LoadingDischargePeriodRateConditionMaster', $data);
    }
    
    public function getLoadinDischargeRateCondById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_LoadingDischargePeriodRateConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_LoadingDischargePeriodRateConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_LoadingDischargePeriodRateConditionMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_LoadingDischargePeriodRateConditionMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLoadinDischargeRateCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LoadingDischargePeriodRateConditionMaster', $data);
    }
    
    public function deleteLoadinDischargeRateCond()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LoadingDischargePeriodRateConditionMaster');
    }
    
    //---------------laytime-ceases-condition-------------------------------
    
    public function getLaytimeCeasesCondition()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_LaytimeCeasesConditionMaster');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLaytimeCeasesCondition()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LaytimeCeasesConditionMaster', $data);
    }
    
    public function getLaytimeCeasesConditionById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_LaytimeCeasesConditionMaster');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLaytimeCeasesCondition()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LaytimeCeasesConditionMaster', $data);
    }
    
    public function deleteLaytimeCeasesCondition()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LaytimeCeasesConditionMaster');
    }
    
    //---------------laytime-comm-condition-------------------------------
    public function getLaytimeCommCondition()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_LaytimeCommencesConditionMaster');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLaytimeCommCondition()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_LaytimeCommencesConditionMaster', $data);
    }
    
    public function getLaytimeCommConditionById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_LaytimeCommencesConditionMaster');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLaytimeCommCondition()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_LaytimeCommencesConditionMaster', $data);
    }
    
    public function deleteLaytimeCommCondition()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_LaytimeCommencesConditionMaster');
    }
    
    //---------------nor-accept-preCond-------------------------------
    public function getNorAcceptPreCond()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_NORPreConditionAcceptMaster');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    
    public function saveNorAcceptPreCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_NORPreConditionAcceptMaster', $data);
    }
    
    
    public function getNorAcceptPreCondById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_NORPreConditionAcceptMaster');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateNorAcceptPreCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_NORPreConditionAcceptMaster', $data);
    }
    
    public function deleteNorAcceptPreCond()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_NORPreConditionAcceptMaster');
    }
    
    //---------------nor-tender-preCond-------------------------------
    
    public function getNorTenderPreCond()
    {
        $this->db->select('udt_CP_NORPreTenderingConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_NORPreTenderingConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_NORPreTenderingConditionMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_NORPreTenderingConditionMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveNorTenderPreCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_NORPreTenderingConditionMaster', $data);
    }
    
    
    public function getNorTenderPreCondById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_NORPreTenderingConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_NORPreTenderingConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_NORPreTenderingConditionMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_NORPreTenderingConditionMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateNorTenderPreCond()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_NORPreTenderingConditionMaster', $data);
    }
    
    public function deleteNorTenderPreCond()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_NORPreTenderingConditionMaster');
    }
    
    //---------------nor-tender-preCond-------------------------------
    public function getTendering()
    {
        $this->db->select('udt_CP_NORTenderingConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_NORTenderingConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_NORTenderingConditionMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_NORTenderingConditionMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveNorTendering()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_NORTenderingConditionMaster', $data);
    }
    
    public function getNorTenderingById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_NORTenderingConditionMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_NORTenderingConditionMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_NORTenderingConditionMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_NORTenderingConditionMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateNorTendering()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_NORTenderingConditionMaster', $data);
    }
    
    public function deleteNorTendering()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_NORTenderingConditionMaster');
    }
    
    
    //---------------steve-doring-terms-------------------------------
    public function getSteveDoringTerms()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_SteveDoringTerms');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveSteveDoringTerms()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_SteveDoringTerms', $data);
    }
    
    public function getSteveDoringTermsById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_SteveDoringTerms');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateSteveDoringTerms()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_SteveDoringTerms', $data);
    }
    
    public function deleteSteveDoringTerms()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_SteveDoringTerms');
    }
    
    //---------------baf-platts-regions-------------------------------
    public function getBafPlattsRegions()
    {
        $this->db->select('udt_CP_BAF_PlattsRegions.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_BAF_PlattsRegions');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_BAF_PlattsRegions.Entity_ID', 'left');
        $this->db->order_by('udt_CP_BAF_PlattsRegions.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveBafPlattsRegions()
    {
        extract($this->input->post());
        $data=array(
         'RegionName'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_BAF_PlattsRegions', $data);
    }
    
    
    public function getBafPlattsRegionsById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_BAF_PlattsRegions.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_BAF_PlattsRegions');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_BAF_PlattsRegions.Entity_ID', 'left');
        $this->db->where('udt_CP_BAF_PlattsRegions.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateBafPlattsRegions()
    {
        extract($this->input->post());
        $data=array(
         'RegionName'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_BAF_PlattsRegions', $data);
    }
    
    public function deleteBafPlattsRegions()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_BAF_PlattsRegions');
    }
    
    //---------------business-area-------------------------------
    public function getBusinessArea()
    {
        $this->db->select('udt_CP_BusinessAreasMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_BusinessAreasMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_BusinessAreasMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_BusinessAreasMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveBusinessArea()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_BusinessAreasMaster', $data);
    }
    
    public function getBusinessAreaById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_BusinessAreasMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_BusinessAreasMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_BusinessAreasMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_BusinessAreasMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateBusinessArea()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_BusinessAreasMaster', $data);
    }
    
    public function deleteBusinessArea()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_BusinessAreasMaster');
    }
    
    //---------------cargo-servicing-basis-------------------------------
    
    public function getCargoServicingBasis()
    {
        $this->db->select('udt_CP_CargoServicingBasisMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_CargoServicingBasisMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_CargoServicingBasisMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_CargoServicingBasisMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveCargoServicingBasis()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_CargoServicingBasisMaster', $data);
    }
    
    public function getCargoServicingBasisById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_CargoServicingBasisMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_CargoServicingBasisMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_CargoServicingBasisMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_CargoServicingBasisMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateCargoServicingBasis()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_CargoServicingBasisMaster', $data);
    }
    
    public function deleteCargoServicingBasis()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_CargoServicingBasisMaster');
    }
    
    //---------------charter-party-form-------------------------------
    
    public function getcharterPartyForm()
    {
        $this->db->select('udt_CP_CharterPartyFormMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_CharterPartyFormMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_CharterPartyFormMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_CharterPartyFormMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveCharterPartyForm()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_CharterPartyFormMaster', $data);
    }
    
    public function getcharterPartyFormById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_CharterPartyFormMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_CharterPartyFormMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_CharterPartyFormMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_CharterPartyFormMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateCharterPartyForm()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_CharterPartyFormMaster', $data);
    }
    
    public function deleteCharterPartyForm()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_CharterPartyFormMaster');
    }
    
    //---------------freight-payment-invoice-------------------------------
    
    public function getFreightPaymentInvoice()
    {
        $this->db->select('*');
        $this->db->from('udt_CP_FreightPaymentInvoiceEvents');
        $this->db->order_by('DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveFreightPaymentInvoice()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_FreightPaymentInvoiceEvents', $data);
    }
    
    public function getFreightPaymentInvoiceById()
    {
        $id=$this->input->post('id');
        $this->db->select('*');
        $this->db->from('udt_CP_FreightPaymentInvoiceEvents');
        $this->db->where('ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateFreightPaymentInvoice()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_FreightPaymentInvoiceEvents', $data);
    }
    
    public function deleteFreightPaymentInvoice()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_FreightPaymentInvoiceEvents');
    }
    
    //---------------freight-payment-event-------------------------------
    public function getFreightPaymentEvent()
    {
        $this->db->select('udt_CP_FreightPaymentInvoiceEvent.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_FreightPaymentInvoiceEvent');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_FreightPaymentInvoiceEvent.Entity_ID', 'left');
        $this->db->order_by('udt_CP_FreightPaymentInvoiceEvent.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveFreightPaymentEvent()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'ST_FreightEventType'=>$event_type,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_FreightPaymentInvoiceEvent', $data);
    }
    
    public function getFreightPaymentEventById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_FreightPaymentInvoiceEvent.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_FreightPaymentInvoiceEvent');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_FreightPaymentInvoiceEvent.Entity_ID', 'left');
        $this->db->where('udt_CP_FreightPaymentInvoiceEvent.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateFreightPaymentEvent()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'ST_FreightEventType'=>$event_type,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_FreightPaymentInvoiceEvent', $data);
    }
    
    public function deleteFreightPaymentEvent()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_FreightPaymentInvoiceEvent');
    }
    
    //---------------trade-area-------------------------------
    public function getTradeArea()
    {
        $this->db->select('udt_CP_TradeAreasMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_TradeAreasMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_TradeAreasMaster.Entity_ID', 'left');
        $this->db->order_by('udt_CP_TradeAreasMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveTradeArea()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_TradeAreasMaster', $data);
    }
    
    public function getTradeAreaById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_TradeAreasMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_TradeAreasMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_TradeAreasMaster.Entity_ID', 'left');
        $this->db->where('udt_CP_TradeAreasMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateTradeArea()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_TradeAreasMaster', $data);
    }
    
    public function deleteTradeArea()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_TradeAreasMaster');
    }
    
    //---------------laytime-counting-------------------------------
    public function getLaytimeCounting()
    {
        $this->db->select('udt_CP_AT_LaytimeCounting.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_AT_LaytimeCounting');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_AT_LaytimeCounting.Entity_ID', 'left');
        $this->db->order_by('udt_CP_AT_LaytimeCounting.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveLaytimeCounting()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'ST_DisplayAllCP'=>$std_dis_to_all_cp,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_CP_AT_LaytimeCounting', $data);
    }
    
    public function getLaytimeCountingById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_CP_AT_LaytimeCounting.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_CP_AT_LaytimeCounting');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_CP_AT_LaytimeCounting.Entity_ID', 'left');
        $this->db->where('udt_CP_AT_LaytimeCounting.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateLaytimeCounting()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'ST_DisplayAllCP'=>$std_dis_to_all_cp,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
                    $this->db->where('ID', $ID);
        return $this->db->update('udt_CP_AT_LaytimeCounting', $data);
    }
    
    public function deleteLaytimeCounting()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_CP_AT_LaytimeCounting');
    }
    //---------------excepted-period-events-------------------------------
    public function getExceptedPeriodEvents()
    {
        $this->db->select('udt_AUM_ExceptedPeriodEventsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_ExceptedPeriodEventsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_ExceptedPeriodEventsMaster.Entity_ID', 'left');
        $this->db->order_by('udt_AUM_ExceptedPeriodEventsMaster.DateTime', 'DESC');
        $query=$this->db->get();
        return $query->result();
    }
    
    public function saveExceptedPeriodEvents()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
        return $this->db->insert('udt_AUM_ExceptedPeriodEventsMaster', $data);
    }
    
    public function getExceptedPeriodEventsById()
    {
        $id=$this->input->post('id');
        $this->db->select('udt_AUM_ExceptedPeriodEventsMaster.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_ExceptedPeriodEventsMaster');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_ExceptedPeriodEventsMaster.Entity_ID', 'left');
        $this->db->where('udt_AUM_ExceptedPeriodEventsMaster.ID', $id);
        $query=$this->db->get();
        return $query->row();
    }
    
    public function updateExceptedPeriodEvents()
    {
        extract($this->input->post());
        $data=array(
         'Code'=>$code,
         'Description'=>$description,
         'ActiveFlag'=>$status,
         'Entity_ID'=>$EntityMasterID,
         'DateTime'=>date('Y-m-d H:i:s')
                        );
                    $this->db->where('ID', $ID);
        return $this->db->update('udt_AUM_ExceptedPeriodEventsMaster', $data);
    }
    
    public function deleteExceptedPeriodEvents()
    {
        extract($this->input->post());
        $this->db->where('ID', $id);
        return $this->db->delete('udt_AUM_ExceptedPeriodEventsMaster');
    }
}


