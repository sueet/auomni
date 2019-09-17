<?php if (! defined('BASEPATH')) { exit('No direct script access allowed');
}
    
class cp_fn_model extends CI_Model {
function __construct()
{
    parent::__construct();        
        
} 
    
public function getFixtureData()
{
    $ResponseID=$this->input->get('TID');
    $this->db->select('udt_AU_AuctionFixture.*,udt_EntityMaster.EntityName,udt_EntityMaster.ID,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_AuctionFixture.RecordOwner', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_AuctionFixture.UserID', 'Left');
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionFixture.FIxtureID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getDocumentationData()
{
    $ResponseID=$this->input->get('TID');
    $this->db->select('udt_AU_AuctionMainDocumentation.*,udt_EntityMaster.EntityName,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_AuctionMainDocumentation.RecordOwner', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_AuctionMainDocumentation.UserID', 'Left');
    $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function checkDocumentationChanges($DocumentationID)
{
    $ResponseID=$this->input->get('TID');
    $this->db->select('udt_AuctionMainClauses.*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('udt_AuctionMainClauses.ResponseID', $ResponseID);
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $query=$this->db->get();
    $result= $query->result();
    $flag=0;
    foreach($result as $row){
        if($row->DeletedClauseNote != '' || $row->AddedClauseNote != '' || $row->ChangeClauseStatus != '' ) {
            $flag=1;
            break;
        }
    }
    return $flag;
}
    
public function getFixtureById()
{
    if($this->input->post()) {
        $FixtureID=$this->input->post('FixtureID');
    }
    if($this->input->get()) {
        $FixtureID=$this->input->get('FixtureID');
    }
    $this->db->select('udt_AU_AuctionFixture.*,udt_AUM_Freight.UserName,udt_AUM_Freight.UserID1,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.LoginID,EM.EntityName as EntityName,IEM.EntityName as EntityName1');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionFixture.ResponseID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->join('udt_EntityMaster as EM', 'EM.ID=udt_UserMaster.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as IEM', 'IEM.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->where('udt_AU_AuctionFixture.FixtureID', $FixtureID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function checkFixtureComplete()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_AuctionFixture.*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionFixture.FixtureID', 'DESC');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getFixtureNoteById()
{
    if($this->input->post()) {
        $FixtureID=$this->input->post('FixtureID');
    }
    if($this->input->get()) {
        $FixtureID=$this->input->get('FixtureID');
    }
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
    
public function createNewFixtureNote()
{
    extract($this->input->post());
    $allFieldNames=json_decode($allFieldNames);
    $allFieldValues=json_decode($allFieldValues);
    $this->db->trans_start();
        
    $this->db->select('udt_AU_FixtureTable.*');
    $this->db->from('udt_AU_FixtureTable');
    $this->db->where('udt_AU_FixtureTable.FixtureID', $FixtureID);
    $this->db->order_by('FTID', 'asc');
    $query=$this->db->get();
    $ftresult=$query->result();
        
    $changes='';
    $cargoflg=0;
    $quoteflg=0;
    $vesselflg=0;
    $changes_value_arr=array();
    $temp_arr=array();
    $tables_ids=array();
    $view_changes_arr=array();
    for($i=0; $i<count($ftresult); $i++){
        if($ftresult[$i]->FieldLblName != $allFieldNames[$i]) {
            $changes .='<p>Old field name : '.$ftresult[$i]->FieldLblName.'<span class="red_clr" > || </span> New field name : '.$allFieldNames[$i].'</p>';
        }
        if($ftresult[$i]->GroupNumber==1) {
            if($ftresult[$i]->FieldValue != $allFieldValues[$i]) {
                  $field_arr1=explode(' || ', $ftresult[$i]->FieldValue);
                  $field_arr2=explode(' || ', $allFieldValues[$i]);
                  $changes .='<p>Old field value : '.$ftresult[$i]->FieldValue.'<span class="red_clr" > || </span> New field value : '.$allFieldValues[$i].'</p>';
                for($ii=0; $ii<count($field_arr2); $ii++){
                    $field_arr3=explode(' | ', $field_arr1[$ii]);
                    $field_arr4=explode(' | ', $field_arr2[$ii]);
                    for($ij=0; $ij<count($field_arr4); $ij++){
                        if($field_arr3[$ij] != $field_arr4[$ij]) {
                            $d_arr=array();
                            $d_arr[0]=$ftresult[$i]->FieldColumnName;
                            $d_arr[1]=$field_arr4[$ij];
                            $l_no=$ii+1;
                            $d_arr[2]=$l_no;
                            $d_arr[3]=$ftresult[$i]->GroupNumber;
                            $d_arr[4]=$ij;
                            $d_arr[5]=$field_arr3[$ij];
                            array_push($changes_value_arr, $d_arr);
                            $temp_str=$ftresult[$i]->GroupNumber.'_'.$l_no;
                                
                            if (in_array($temp_str, $temp_arr)) {
                                $view_changes_arr[$temp_str] .='<br/><p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                            } else {
                                    $temp_arr[]=$temp_str;
                                    $view_changes_arr[$temp_str]='<p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                                    $this->db->select('*');
                                    $this->db->from('udt_AU_ResponseCargo');
                                    $this->db->where('udt_AU_ResponseCargo.ResponseID', $ResponseID);
                                    $this->db->order_by('udt_AU_ResponseCargo.LineNum', 'asc');
                                    $this->db->order_by('udt_AU_ResponseCargo.ResponseCargoID', 'desc');
                                    $query1=$this->db->get();
                                    $rescargoresult=$query1->result();
                                    $line_cnt=0;
                                    $line_num='';
                                foreach($rescargoresult as $c_row){
                                    if($c_row->LineNum != $line_num) {
                                        $line_num=$c_row->LineNum;
                                        $line_cnt++;
                                    }
                                    if($line_cnt == $l_no) {
                                        $new_ResponseCargoID=$this->insert_cargo_new_version($c_row->ResponseCargoID);
                                        $tables_ids[$temp_str]=$new_ResponseCargoID;
                                        break;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else if($ftresult[$i]->GroupNumber==2) {
            if($ftresult[$i]->FieldValue != $allFieldValues[$i]) {
                $field_arr1=explode(' || ', $ftresult[$i]->FieldValue);
                $field_arr2=explode(' || ', $allFieldValues[$i]);
                $changes .='<p>Old field value : '.$ftresult[$i]->FieldValue.'<span class="red_clr" > || </span> New field value : '.$allFieldValues[$i].'</p>';
                for($ii=0; $ii<count($field_arr2); $ii++){
                    $diffcntr=1;
                    $field_arr3=explode(' | ', $field_arr1[$ii]);
                    $field_arr4=explode(' | ', $field_arr2[$ii]);
                    for($ij=0; $ij<count($field_arr4); $ij++){
                        if($ftresult[$i]->FieldColumnName=='DifferentialDisport' || $ftresult[$i]->FieldColumnName=='LpDpFlg' || $ftresult[$i]->FieldColumnName=='LoadingDischargingRate' || $ftresult[$i]->FieldColumnName=='LoadDischargeUnit' || $ftresult[$i]->FieldColumnName=='DifferentailInviteeAmt') {
                            $field_arr31=trim($field_arr3[$ij], ' ');
                            $field_arr41=trim($field_arr4[$ij], ' ');
                                
                            $field_arr31 = substr($field_arr31, 1, -1);
                            $field_arr41 = substr($field_arr41, 1, -1);
                                
                            $field_arr311=explode(',', $field_arr31);
                            $field_arr411=explode(',', $field_arr41);
                            for($ijk=0; $ijk< count($field_arr311); $ijk++){
                                $trimfield_arr311=trim($field_arr311[$ijk], ' ');
                                $trimfield_arr411=trim($field_arr411[$ijk], ' ');
                                if($trimfield_arr311 != $trimfield_arr411) {
                                    $d_arr=array();
                                    $d_arr[0]=$ftresult[$i]->FieldColumnName;
                                    $d_arr[1]=$trimfield_arr411;
                                    $l_no=$ii+1;
                                    $d_arr[2]=$l_no;
                                    $d_arr[3]=$ftresult[$i]->GroupNumber;
                                    $d_arr[4]=$diffcntr;
                                    $d_arr[5]=$trimfield_arr311;
                                    array_push($changes_value_arr, $d_arr);
                                    $temp_str=$ftresult[$i]->GroupNumber.'_'.$l_no;
                                    if (in_array($temp_str, $temp_arr)) {
                                              $view_changes_arr[$temp_str] .='<br/><p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                                    } else {
                                        $temp_arr[]=$temp_str;
                                        $view_changes_arr[$temp_str]='<p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                                        $this->db->select('*');
                                        $this->db->from('udt_AU_FreightResponse');
                                        $this->db->where('udt_AU_FreightResponse.ResponseID', $ResponseID);
                                        $this->db->order_by('udt_AU_FreightResponse.LineNum', 'asc');
                                        $this->db->order_by('udt_AU_FreightResponse.FreightResponseID', 'desc');
                                        $query1=$this->db->get();
                                        $freightresult=$query1->result();
                                        $line_cnt=0;
                                        $line_num='';
                                        foreach($freightresult as $f_row){
                                            if($f_row->LineNum != $line_num) {
                                                $line_num=$f_row->LineNum;
                                                $line_cnt++;
                                            }
                                            if($line_cnt == $l_no) {
                                                   $NewFreightResponseID=$this->insert_freight_new_version($f_row->FreightResponseID);
                                                   $tables_ids[$temp_str]=$NewFreightResponseID;
                                                   break;
                                            }
                                        }
                                    }
                                }
                                $diffcntr++;
                            }
                        } else {
                            if($field_arr3[$ij] != $field_arr4[$ij]) {
                                $d_arr=array();
                                $d_arr[0]=$ftresult[$i]->FieldColumnName;
                                $d_arr[1]=$field_arr4[$ij];
                                $l_no=$ii+1;
                                $d_arr[2]=$l_no;
                                $d_arr[3]=$ftresult[$i]->GroupNumber;
                                $d_arr[4]=$ij;
                                $d_arr[5]=$field_arr3[$ij];
                                array_push($changes_value_arr, $d_arr);
                                $temp_str=$ftresult[$i]->GroupNumber.'_'.$l_no;
                                    
                                if (in_array($temp_str, $temp_arr)) {
                                            $view_changes_arr[$temp_str] .='<br/><p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                                } else {
                                            $temp_arr[]=$temp_str;
                                            $view_changes_arr[$temp_str]='<p>Old value : '.$field_arr3[$ij].'<span class="diff" > || </span> New value : '.$field_arr4[$ij].'</p>';
                                            $this->db->select('*');
                                            $this->db->from('udt_AU_FreightResponse');
                                            $this->db->where('udt_AU_FreightResponse.ResponseID', $ResponseID);
                                            $this->db->order_by('udt_AU_FreightResponse.LineNum', 'asc');
                                            $this->db->order_by('udt_AU_FreightResponse.FreightResponseID', 'desc');
                                            $query1=$this->db->get();
                                            $freightresult=$query1->result();
                                            $line_cnt=0;
                                            $line_num='';
                                    foreach($freightresult as $f_row){
                                        if($f_row->LineNum != $line_num) {
                                                     $line_num=$f_row->LineNum;
                                                     $line_cnt++;
                                        }
                                        if($line_cnt == $l_no) {
                                                $NewFreightResponseID=$this->insert_freight_new_version($f_row->FreightResponseID);
                                                $tables_ids[$temp_str]=$NewFreightResponseID;
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                            
                    }
                }
            }
        } else if($ftresult[$i]->GroupNumber==3) {
            if($ftresult[$i]->FieldValue != $allFieldValues[$i]) {
                $changes .='<p>Old field value : '.$ftresult[$i]->FieldValue.'<span class="red_clr" > || </span> New field value : '.$allFieldValues[$i].'</p>';
                    
                $d_arr=array();
                $d_arr[0]=$ftresult[$i]->FieldColumnName;
                $d_arr[1]=$allFieldValues[$i];
                $d_arr[2]=1;
                $d_arr[3]=$ftresult[$i]->GroupNumber;
                $d_arr[4]=1;
                $d_arr[5]=$ftresult[$i]->FieldValue;
                array_push($changes_value_arr, $d_arr);
                $temp_str=$ftresult[$i]->GroupNumber.'_1';
                if (in_array($temp_str, $temp_arr)) {
                     $view_changes_arr[$temp_str] .='<br/><p>Old value : '.$ftresult[$i]->FieldValue.'<span class="diff" > || </span> New value : '.$allFieldValues[$i].'</p>';
                } else {
                    $temp_arr[]=$temp_str;
                    $view_changes_arr[$temp_str] .='<br/><p>Old value : '.$ftresult[$i]->FieldValue.'<span class="diff" > || </span> New value : '.$allFieldValues[$i].'</p>';
                    $this->db->select('*');
                    $this->db->from('udt_AU_ResponseVessel');
                    $this->db->where('udt_AU_ResponseVessel.ResponseID', $ResponseID);
                    $this->db->order_by('udt_AU_ResponseVessel.ResponseVesselID', 'desc');
                    $query1=$this->db->get();
                    $vesselRow=$query1->row();
                    $NewResponseVesselID=$this->insert_vessel_new_version($vesselRow->ResponseVesselID);
                    $tables_ids[$temp_str]=$NewResponseVesselID;
                }
                    
            }
        }
    }
        
    for($j=0; $j < count($changes_value_arr); $j++){
        if($changes_value_arr[$j][3]==1) {
            if($changes_value_arr[$j][0]=='SelectFrom') {
                $this->db->select('*');
                $this->db->from('udt_CargoMaster');
                $this->db->where('Code', $changes_value_arr[$j][1]);
                $query=$this->db->get();
                $CargoID=$query->row()->ID;
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('SelectFrom'=>$CargoID));
            } else if($changes_value_arr[$j][0]=='CargoQtyMT' || $changes_value_arr[$j][0]=='UpperLimit' || $changes_value_arr[$j][0]=='LowerLimit' || $changes_value_arr[$j][0]=='MaxCargoMT' || $changes_value_arr[$j][0]=='MinCargoMT' || $changes_value_arr[$j][0]=='LoadingRateMT' ) {
                $valarr=explode(".", $changes_value_arr[$j][1]);
                $int_value=str_replace(',', '', $valarr[0]);
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array($changes_value_arr[$j][0]=>$int_value));
            } else if($changes_value_arr[$j][0]=='LoadPort') {
                $this->db->select('*');
                $this->db->from('udt_PortMaster');
                $this->db->where('PortName', $changes_value_arr[$j][1]);
                $query=$this->db->get();
                $PortID=$query->row()->ID;
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('LoadPort'=>$PortID));
            } else if($changes_value_arr[$j][0]=='LpLaycanStartDate' || $changes_value_arr[$j][0]=='LpLaycanEndDate' || $changes_value_arr[$j][0]=='LpPreferDate') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array($changes_value_arr[$j][0]=>date('Y-m-d H:i:s', strtotime($changes_value_arr[$j][1]))));
            } else if($changes_value_arr[$j][0]=='ExpectedLpDelayDay') {
                preg_match_all('!\d+!', $changes_value_arr[$j][1], $matches);
                $day=0;
                $hour=0;
                if($matches[0][0]) {
                        $day=$matches[0][0];
                }
                if($matches[0][1]) {
                    $hour=$matches[0][1];
                }
                    
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('ExpectedLpDelayDay'=>$day,'ExpectedLpDelayHour'=>$hour));
            } else if($changes_value_arr[$j][0]=='LoadingTerms') {
                $this->db->select('*');
                $this->db->from('udt_CP_LoadingDischargeTermsMaster');
                $this->db->where('Code', $changes_value_arr[$j][1]);
                $query=$this->db->get();
                $LoadingTermID=$query->row()->ID;
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('LoadingTerms'=>$LoadingTermID));
            } else if($changes_value_arr[$j][0]=='LoadingRateUOM') {
                $LoadingRateUOM=0;
                if($changes_value_arr[$j][1]=='Per hour') {
                        $LoadingRateUOM=1;
                } else if($changes_value_arr[$j][1]=='Per weather working day') {
                    $LoadingRateUOM=2;
                } else if($changes_value_arr[$j][1]=='Max time limit') {
                    $LoadingRateUOM=3;
                }
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('LoadingRateUOM'=>$LoadingRateUOM));
            } else if($changes_value_arr[$j][0]=='LpLaytimeType') {
                $LpLaytimeType=0;
                if($changes_value_arr[$j][1]=='Reversible') {
                        $LpLaytimeType=1;
                } else if($changes_value_arr[$j][1]=='Non Reversible') {
                    $LpLaytimeType=2;
                } else if($changes_value_arr[$j][1]=='Average') {
                    $LpLaytimeType=3;
                }
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('LpLaytimeType'=>$LpLaytimeType));
            } else if($changes_value_arr[$j][0]=='LpCalculationBasedOn') {
                $LpCalculationBasedOn=0;
                if($changes_value_arr[$j][1]=='Bill of Loading Quantity') {
                        $LpCalculationBasedOn=108;
                } else if($changes_value_arr[$j][1]=='Outturn or Discharge Quantity') {
                            $LpCalculationBasedOn=109;
                }
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array('LpCalculationBasedOn'=>$LpCalculationBasedOn));
            } else if($changes_value_arr[$j][0]=='LpTurnTime') {
                $LpTurnTime=0;
                if($changes_value_arr[$j][1]=='LT freetime') {
                        $LpTurnTime=1;
                } else if($changes_value_arr[$j][1]=='LayTime Free test') {
                    $LpTurnTime=2;
                } elseif($changes_value_arr[$j][1]=='4HAA') {
                    $LpTurnTime=5;
                } else if($changes_value_arr[$j][1]=='6HAA') {
                      $LpTurnTime=6;
                } else if($changes_value_arr[$j][1]=='8HAA') {
                          $LpTurnTime=7;
                } else if($changes_value_arr[$j][1]=='12HAA') {
                    $LpTurnTime=3;
                } else if($changes_value_arr[$j][1]=='16HAA') {
                    $LpTurnTime=8;
                } else if($changes_value_arr[$j][1]=='18HAA') {
                    $LpTurnTime=10;
                } else if($changes_value_arr[$j][1]=='20HAA') {
                    $LpTurnTime=9;
                } else if($changes_value_arr[$j][1]=='24HAA') {
                    $LpTurnTime=4;
                }
                                                  $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                  $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                  $this->db->update('udt_AU_ResponseCargo', array('LpTurnTime'=>$LpTurnTime));
            } else if($changes_value_arr[$j][0]=='LpPriorUseTerms') {
                $LpPriorUseTerms=0;
                if($changes_value_arr[$j][1]=='IUATUTC (If Used Actual Time To Count)') {
                        $LpPriorUseTerms=102;
                } else if($changes_value_arr[$j][1]=='IUHTUTC (If Used Half Time To Count)') {
                                $LpPriorUseTerms=10;
                }
                 $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                 $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                 $this->db->update('udt_AU_ResponseCargo', array('LpPriorUseTerms'=>$LpPriorUseTerms));
            } else if($changes_value_arr[$j][0]=='LpLaytimeBasedOn') {
                $LpLaytimeBasedOn=0;
                if($changes_value_arr[$j][1]=='ATS (All Time Saved)') {
                        $LpLaytimeBasedOn=1;
                } else if($changes_value_arr[$j][1]=='WTS (Working Time Saved)') {
                                     $LpLaytimeBasedOn=2;
                }
                 $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                 $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                 $this->db->update('udt_AU_ResponseCargo', array('LpLaytimeBasedOn'=>$LpLaytimeBasedOn));
            } else if($changes_value_arr[$j][0]=='LpCharterType') {
                $LpCharterType=0;
                if($changes_value_arr[$j][1]=='1 Safe Port 1 Safe Berth (1SP1SB)') {
                        $LpCharterType=1;
                } else if($changes_value_arr[$j][1]=='1 Safe Port 2 Safe Berth (1SP2SB)') {
                                        $LpCharterType=2;
                } else if($changes_value_arr[$j][1]=='2 Safe Port 1 Safe Berth (2SP1SB)') {
                              $LpCharterType=3;
                } else if($changes_value_arr[$j][1]=='2 Safe Port 2 Safe Berth (2SP2SB)') {
                    $LpCharterType=4;
                }
                                                          $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                          $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                          $this->db->update('udt_AU_ResponseCargo', array('LpCharterType'=>$LpCharterType));
            } else if($changes_value_arr[$j][0]=='LpNorTendering') {
                $LpNorTendering=0;
                if($changes_value_arr[$j][1]=='ATDNSHINC') {
                        $LpNorTendering=1;
                } else if($changes_value_arr[$j][1]=='ATDNFHINC') {
                                           $LpNorTendering=2;
                } else if($changes_value_arr[$j][1]=='OFFICE HOURS') {
                                 $LpNorTendering=3;
                } else if($changes_value_arr[$j][1]=='ATDNSHINC WIPON WIBON WIFPOC WCCCON') {
                    $LpNorTendering=4;
                }
                                                                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                $this->db->update('udt_AU_ResponseCargo', array('LpNorTendering'=>$LpNorTendering));
            } else if($changes_value_arr[$j][0]=='LpStevedoringTerms') {
                $LpStevedoringTerms=0;
                if($changes_value_arr[$j][1]=='FIO') {
                        $LpStevedoringTerms=3;
                } else if($changes_value_arr[$j][1]=='FIOST') {
                                              $LpStevedoringTerms=4;
                } else if($changes_value_arr[$j][1]=='LIFO') {
                                    $LpStevedoringTerms=5;
                } else if($changes_value_arr[$j][1]=='FIT') {
                    $LpStevedoringTerms=6;
                } else if($changes_value_arr[$j][1]=='FIOT') {
                    $LpStevedoringTerms=8;
                } else if($changes_value_arr[$j][1]=='FIOFT') {
                    $LpStevedoringTerms=9;
                } else if($changes_value_arr[$j][1]=='FIS') {
                    $LpStevedoringTerms=7;
                }
                                                                      $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                      $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                      $this->db->update('udt_AU_ResponseCargo', array('LpStevedoringTerms'=>$LpStevedoringTerms));
            } else if($changes_value_arr[$j][0]=='DisPort') {
                $this->db->select('*');
                $this->db->from('udt_PortMaster');
                $this->db->where('PortName', $changes_value_arr[$j][1]);
                $query=$this->db->get();
                $PortID=$query->row()->ID;
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                    
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DisPort'=>$PortID));

                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DisPort'=>$PortID));
                    }
                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpArrivalStartDate' || $changes_value_arr[$j][0]=='DpArrivalEndDate' || $changes_value_arr[$j][0]=='DpPreferDate') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array($changes_value_arr[$j][0]=>date('Y-m-d H:i:s', strtotime($changes_value_arr[$j][1]))));

                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array($changes_value_arr[$j][0]=>date('Y-m-d H:i:s', strtotime($changes_value_arr[$j][1]))));
                    }
                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='ExpectedDpDelayDay') {
                preg_match_all('!\d+!', $changes_value_arr[$j][1], $matches);
                $day=0;
                $hour=0;
                if($matches[0][0]) {
                        $day=$matches[0][0];
                }
                if($matches[0][1]) {
                    $hour=$matches[0][1];
                }
                    
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                    
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('ExpectedDpDelayDay'=>$day,'ExpectedDpDelayHour'=>$hour)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('ExpectedDpDelayDay'=>$day,'ExpectedDpDelayHour'=>$hour));
                    }
                    $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DischargingTerms') {
                $this->db->select('*');
                $this->db->from('udt_CP_LoadingDischargeTermsMaster');
                $this->db->where('Code', $changes_value_arr[$j][1]);
                $query=$this->db->get();
                $LoadingTermID=$query->row()->ID;
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DischargingTerms'=>$LoadingTermID)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DischargingTerms'=>$LoadingTermID));
                    }
                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DischargingRateMT' ) {
                $valarr=explode(".", $changes_value_arr[$j][1]);
                $int_value=str_replace(',', '', $valarr[0]);
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DischargingRateMT'=>$int_value)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DischargingRateMT'=>$int_value));
                    }
                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DischargingRateUOM') {
                $DischargingRateUOM=0;
                if($changes_value_arr[$j][1]=='Per hour') {
                        $DischargingRateUOM=1;
                } else if($changes_value_arr[$j][1]=='Per weather working day') {
                                                                $DischargingRateUOM=2;
                } else if($changes_value_arr[$j][1]=='Max time limit') {
                                                      $DischargingRateUOM=3;
                }
                                                                                                          $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                          $this->db->select('*');
                                                                                                          $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                          $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                          $query1=$this->db->get();
                                                                                                          $DisportsArr=$query1->result();
                                                                                                          $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DischargingRateUOM'=>$DischargingRateUOM)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DischargingRateUOM'=>$DischargingRateUOM));
                    }
                                                                                                              $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpLaytimeType') {
                $DpLaytimeType=0;
                if($changes_value_arr[$j][1]=='Reversible') {
                        $DpLaytimeType=1;
                } else if($changes_value_arr[$j][1]=='Non Reversible') {
                                                                   $DpLaytimeType=2;
                } else if($changes_value_arr[$j][1]=='Average') {
                                                         $DpLaytimeType=3;
                }
                                                                                                                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                                $this->db->select('*');
                                                                                                                $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                                $query1=$this->db->get();
                                                                                                                $DisportsArr=$query1->result();
                                                                                                                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpLaytimeType'=>$DpLaytimeType)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpLaytimeType'=>$DpLaytimeType));
                    }
                                                                                                                    $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpCalculationBasedOn') {
                $DpCalculationBasedOn=0;
                if($changes_value_arr[$j][1]=='Bill of Loading Quantity') {
                        $DpCalculationBasedOn=108;
                } else if($changes_value_arr[$j][1]=='Outturn or Discharge Quantity') {
                                                                      $DpCalculationBasedOn=109;
                }
                 $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                 $this->db->select('*');
                 $this->db->from('udt_AU_ResponseCargoDisports');
                 $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                 $query1=$this->db->get();
                 $DisportsArr=$query1->result();
                 $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpCalculationBasedOn'=>$DpCalculationBasedOn)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpCalculationBasedOn'=>$DpCalculationBasedOn));
                    }
                                                                 $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpTurnTime') {
                $DpTurnTime=0;
                if($changes_value_arr[$j][1]=='LT freetime') {
                        $DpTurnTime=1;
                } else if($changes_value_arr[$j][1]=='LayTime Free test') {
                                                                         $DpTurnTime=2;
                } elseif($changes_value_arr[$j][1]=='4HAA') {
                                                               $DpTurnTime=5;
                } else if($changes_value_arr[$j][1]=='6HAA') {
                    $DpTurnTime=6;
                } else if($changes_value_arr[$j][1]=='8HAA') {
                    $DpTurnTime=7;
                } else if($changes_value_arr[$j][1]=='12HAA') {
                    $DpTurnTime=3;
                } else if($changes_value_arr[$j][1]=='16HAA') {
                    $DpTurnTime=8;
                } else if($changes_value_arr[$j][1]=='18HAA') {
                    $DpTurnTime=10;
                } else if($changes_value_arr[$j][1]=='20HAA') {
                    $DpTurnTime=9;
                } else if($changes_value_arr[$j][1]=='24HAA') {
                    $DpTurnTime=4;
                }
                                                                                                                            $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                                            $this->db->select('*');
                                                                                                                            $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                                            $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                                            $query1=$this->db->get();
                                                                                                                            $DisportsArr=$query1->result();
                                                                                                                            $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpTurnTime'=>$DpTurnTime)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpTurnTime'=>$DpTurnTime));
                    }
                                                                                                                                $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpPriorUseTerms') {
                $DpPriorUseTerms=0;
                if($changes_value_arr[$j][1]=='IUATUTC (If Used Actual Time To Count)') {
                        $DpPriorUseTerms=102;
                } else if($changes_value_arr[$j][1]=='IUHTUTC (If Used Half Time To Count)') {
                                                                            $DpPriorUseTerms=10;
                }
                 $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                 $this->db->select('*');
                 $this->db->from('udt_AU_ResponseCargoDisports');
                 $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                 $query1=$this->db->get();
                 $DisportsArr=$query1->result();
                 $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpPriorUseTerms'=>$DpPriorUseTerms)); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpPriorUseTerms'=>$DpPriorUseTerms));
                    }
                                                                       $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpLaytimeBasedOn') {
                $DpLaytimeBasedOn=0;
                if($changes_value_arr[$j][1]=='ATS (All Time Saved)') {
                        $DpLaytimeBasedOn=1;
                } else if($changes_value_arr[$j][1]=='WTS (Working Time Saved)') {
                                                                               $DpLaytimeBasedOn=2;
                }
                 $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                 $this->db->select('*');
                 $this->db->from('udt_AU_ResponseCargoDisports');
                 $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                 $query1=$this->db->get();
                 $DisportsArr=$query1->result();
                 $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpLaytimeBasedOn'=>$DpLaytimeBasedOn));
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpLaytimeBasedOn'=>$DpLaytimeBasedOn));
                    }
                                                                          $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpCharterType') { 
                $DpCharterType=0;
                if($changes_value_arr[$j][1]=='1 Safe Port 1 Safe Berth (1SP1SB)') {
                        $DpCharterType=1;
                } else if($changes_value_arr[$j][1]=='1 Safe Port 2 Safe Berth (1SP2SB)') {
                                                                                  $DpCharterType=2;
                } else if($changes_value_arr[$j][1]=='2 Safe Port 1 Safe Berth (2SP1SB)') {
                                                                        $DpCharterType=3;
                } else if($changes_value_arr[$j][1]=='2 Safe Port 2 Safe Berth (2SP2SB)') {
                    $DpCharterType=4;
                }
                                                                                                                                              $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                                                              $this->db->select('*');
                                                                                                                                              $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                                                              $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                                                              $query1=$this->db->get();
                                                                                                                                              $DisportsArr=$query1->result();
                                                                                                                                              $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpCharterType'=>$DpCharterType));
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpCharterType'=>$DpCharterType));
                    }
                                                                                                                                                  $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpNorTendering') { 
                $DpNorTendering=0;
                if($changes_value_arr[$j][1]=='ATDNSHINC') {
                        $DpNorTendering=1;
                } else if($changes_value_arr[$j][1]=='ATDNFHINC') {
                                                                                     $DpNorTendering=2;
                } else if($changes_value_arr[$j][1]=='OFFICE HOURS') {
                                                                           $DpNorTendering=3;
                } else if($changes_value_arr[$j][1]=='ATDNSHINC WIPON WIBON WIFPOC WCCCON') {
                    $DpNorTendering=4;
                }
                                                                                                                                                    $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                                                                    $this->db->select('*');
                                                                                                                                                    $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                                                                    $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                                                                    $query1=$this->db->get();
                                                                                                                                                    $DisportsArr=$query1->result();
                                                                                                                                                    $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpNorTendering'=>$DpNorTendering));
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpNorTendering'=>$DpNorTendering));
                    }
                                                                                                                                                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpStevedoringTerms') {
                $DpStevedoringTerms=0;
                if($changes_value_arr[$j][1]=='FIO') {
                        $DpStevedoringTerms=3;
                } else if($changes_value_arr[$j][1]=='FIOST') {
                                                                                        $DpStevedoringTerms=4;
                } else if($changes_value_arr[$j][1]=='LIFO') {
                                                                              $DpStevedoringTerms=5;
                } else if($changes_value_arr[$j][1]=='FIT') {
                    $DpStevedoringTerms=6;
                } else if($changes_value_arr[$j][1]=='FIOT') {
                    $DpStevedoringTerms=8;
                } else if($changes_value_arr[$j][1]=='FIOFT') {
                    $DpStevedoringTerms=9;
                } else if($changes_value_arr[$j][1]=='FIS') {
                    $DpStevedoringTerms=7;
                }
                                                                                                                                                          $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                                                                                                                                                          $this->db->select('*');
                                                                                                                                                          $this->db->from('udt_AU_ResponseCargoDisports');
                                                                                                                                                          $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                                                                                                                                                          $query1=$this->db->get();
                                                                                                                                                          $DisportsArr=$query1->result();
                                                                                                                                                          $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array('DpStevedoringTerms'=>$DpStevedoringTerms));
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array('DpStevedoringTerms'=>$DpStevedoringTerms));
                    }
                                                                                                                                                              $disno++;
                }
            } else if($changes_value_arr[$j][0]=='DpMaxTime') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->select('*');
                $this->db->from('udt_AU_ResponseCargoDisports');
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $query1=$this->db->get();
                $DisportsArr=$query1->result();
                $disno=0;
                foreach($DisportsArr as $dis){
                    if($disno==$changes_value_arr[$j][4]) {
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports', array($changes_value_arr[$j][0]=>$changes_value_arr[$j][1])); 
                            
                        $this->db->where('RCD_ID', $dis->RCD_ID);
                        $this->db->update('udt_AU_ResponseCargoDisports_H', array($changes_value_arr[$j][0]=>$changes_value_arr[$j][1]));
                    }
                        $disno++;
                }
            } else if($changes_value_arr[$j][0]=='BrokeragePayingEntityType') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PayingEntityType'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PayingEntityType'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePayingEntityName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PayingEntityName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PayingEntityName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokerageReceivingEntityType') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('ReceivingEntityType'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('ReceivingEntityType'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokerageReceivingEntityName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('ReceivingEntityName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('ReceivingEntityName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokerageBrokerName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('BrokerName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('BrokerName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePayableAs') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PayableAs'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PayableAs'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePercentageOnFreight') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnFreight'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnFreight'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePercentageOnDeadFreight') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnDeadFreight'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnDeadFreight'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePercentageOnDemmurage') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnDemmurage'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnDemmurage'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokeragePercentageOnOverage') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnOverage'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnOverage'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokerageLumpsumPayable') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('LumpsumPayable'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('LumpsumPayable'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='BrokerageRatePerTonnePayable') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse', array('RatePerTonnePayable'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Brokerage');
                $this->db->update('udt_AU_BACResponse_H', array('RatePerTonnePayable'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPayingEntityType') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PayingEntityType'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PayingEntityType'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPayingEntityName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PayingEntityName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PayingEntityName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommReceivingEntityType') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('ReceivingEntityType'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('ReceivingEntityType'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommReceivingEntityName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('ReceivingEntityName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('ReceivingEntityName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommBrokerName') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('BrokerName'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('BrokerName'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPayableAs') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PayableAs'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PayableAs'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPercentageOnFreight') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnFreight'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnFreight'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPercentageOnDeadFreight') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnDeadFreight'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnDeadFreight'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPercentageOnDemmurage') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnDemmurage'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnDemmurage'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommPercentageOnOverage') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('PercentageOnOverage'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('PercentageOnOverage'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommLumpsumPayable') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('LumpsumPayable'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('LumpsumPayable'=>$changes_value_arr[$j][1]));
            } else if($changes_value_arr[$j][0]=='AddCommRatePerTonnePayable') {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse', array('RatePerTonnePayable'=>$changes_value_arr[$j][1]));
                    
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->where('TransactionType', 'Commision');
                $this->db->update('udt_AU_BACResponse_H', array('RatePerTonnePayable'=>$changes_value_arr[$j][1]));
            } else {
                $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
                $this->db->where('ResponseCargoID', $tables_ids[$arr_index]);
                $this->db->update('udt_AU_ResponseCargo', array($changes_value_arr[$j][0]=>$changes_value_arr[$j][1]));
            }    
        } else if($changes_value_arr[$j][3]==2) {
            $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
            $all_ids=$tables_ids[$arr_index];
            $all_id_arr=explode('_', $all_ids);
            if($changes_value_arr[$j][0]=='DifferentialDisport' || $changes_value_arr[$j][0]=='LpDpFlg' || $changes_value_arr[$j][0]=='LoadingDischargingRate' || $changes_value_arr[$j][0]=='LoadDischargeUnit' || $changes_value_arr[$j][0]=='DifferentailInviteeAmt') {
                $Diff_field_value1='';
                $Diff_field_name1='';
                if($changes_value_arr[$j][0]=='DifferentialDisport') {
                     $this->db->select('*');
                     $this->db->from('udt_PortMaster');
                     $this->db->where('PortName', $changes_value_arr[$j][1]);
                     $query=$this->db->get();
                     $PortID=$query->row()->ID;
                     $Diff_field_value1=$PortID;
                     $Diff_field_name1='RefDisportID';
                } else if($changes_value_arr[$j][0]=='LpDpFlg') {
                    if($changes_value_arr[$j][1]=='Lp') {
                         $Diff_field_value1='1';
                    } else if($changes_value_arr[$j][1]=='Dp') {
                        $Diff_field_value1='2';
                    }
                    $Diff_field_name1='LpDpFlg';
                } else if($changes_value_arr[$j][0]=='LoadingDischargingRate') {
                    $valarr=explode(".", $changes_value_arr[$j][1]);
                    $Diff_field_value1=str_replace(',', '', $valarr[0]);
                    $Diff_field_name1='LoadDischargeRate';
                } else if($changes_value_arr[$j][0]=='LoadDischargeUnit') {
                    if($changes_value_arr[$j][1]=='$ mt/hr') {
                             $Diff_field_value1='1';
                    } else if($changes_value_arr[$j][1]=='$ mt/day') {
                           $Diff_field_value1='2';
                    }
                    $Diff_field_name1='LoadDischargeUnit';
                } else if($changes_value_arr[$j][0]=='DifferentailInviteeAmt') {
                    $Diff_field_value1=$changes_value_arr[$j][1];
                    $Diff_field_name1='DifferentialInviteeAmt';
                }
                    
                $this->db->select('*');
                $this->db->from('udt_AU_DifferentialRefDisportsResponse');
                $this->db->where('DifferentialID', $all_id_arr[1]);
                $this->db->order_by('GroupNo', 'ASC');
                $this->db->order_by('PrimaryPortFlg', 'DESC');
                $this->db->order_by('DiffRefDisportID', 'ASC');
                $qry1=$this->db->get();
                $result1=$qry1->result();
                $len=1;
                foreach($result1 as $r){
                    if($len==$changes_value_arr[$j][4]) {
                        $this->db->where('DiffRefDisportID', $r->DiffRefDisportID);
                        $this->db->update('udt_AU_DifferentialRefDisportsResponse', array($Diff_field_name1=>$Diff_field_value1));
                        break;
                    }
                    $len++;
                }
            } else if($changes_value_arr[$j][0]=='FreightRate' || $changes_value_arr[$j][0]=='FreightCurrency' || $changes_value_arr[$j][0]=='FreightRateUOM' || $changes_value_arr[$j][0]=='FreightTce' || $changes_value_arr[$j][0]=='FreightTceDifferential' || $changes_value_arr[$j][0]=='FreightLumpsumMax' || $changes_value_arr[$j][0]=='FreightLow' || $changes_value_arr[$j][0]=='FreightHigh' || $changes_value_arr[$j][0]=='Demurrage' || $changes_value_arr[$j][0]=='DespatchDemurrageFlag' || $changes_value_arr[$j][0]=='DespatchHalfDemurrage') {
                $freight_field_value='';
                if($changes_value_arr[$j][0]=='FreightRate') {
                    $freight_field_value=$changes_value_arr[$j][1];
                } else if($changes_value_arr[$j][0]=='FreightCurrency') {
                    $this->db->select('*');
                    $this->db->from('udt_CurrencyMaster');
                    $this->db->where('Code', $changes_value_arr[$j][1]);
                    $query=$this->db->get();
                    $cur_row=$query->row();
                    $freight_field_value=$cur_row->ID;
                } else if($changes_value_arr[$j][0]=='FreightRateUOM') {
                    if($changes_value_arr[$j][1]=='MT(Metric Tonnes)') {
                        $freight_field_value=1;
                    }else if($changes_value_arr[$j][1]=='LT(Long Tonnes)') {
                        $freight_field_value=2;
                    }else if($changes_value_arr[$j][1]=='PMT(Per metric tonne)') {
                        $freight_field_value=3;
                    }else if($changes_value_arr[$j][1]=='PLT(Per long ton)') {
                        $freight_field_value=4;
                    }else if($changes_value_arr[$j][1]=='WWD(Weather Working Day)') {
                        $freight_field_value=5;
                    }
                } else if($changes_value_arr[$j][0]=='FreightTce' || $changes_value_arr[$j][0]=='FreightTceDifferential' || $changes_value_arr[$j][0]=='FreightLumpsumMax' || $changes_value_arr[$j][0]=='FreightLow' || $changes_value_arr[$j][0]=='FreightHigh' || $changes_value_arr[$j][0]=='Demurrage' || $changes_value_arr[$j][0]=='DespatchHalfDemurrage') {
                    $valarr=explode(".", $changes_value_arr[$j][1]);
                    $freight_field_value=str_replace(',', '', $valarr[0]);
                } else if($changes_value_arr[$j][0]=='DespatchDemurrageFlag') {
                    if($changes_value_arr[$j][1]=='Yes') {
                          $freight_field_value=1;
                    }else if($changes_value_arr[$j][1]=='No') {
                        $freight_field_value=2;
                    }
                }
                $this->db->where('FreightResponseID', $all_id_arr[0]);
                $this->db->update('udt_AU_FreightResponse', array($changes_value_arr[$j][0]=>$freight_field_value));
                    
                $this->db->select('*');
                $this->db->from('udt_AU_Freight');
                $this->db->where('ResponseID', $ResponseID);
                $this->db->order_by('LineNum', 'ASC');
                $qry1=$this->db->get();
                $result1=$qry1->result();
                $len=1;
                foreach($result1 as $r){
                    if($len==$changes_value_arr[$j][4]) {
                        $this->db->where('FreightID', $r->FreightID);
                        $this->db->update('udt_AU_Freight', array($changes_value_arr[$j][0]=>$freight_field_value));
                        break;
                    }
                    $len++;
                }
            }
        } else if($changes_value_arr[$j][3]==3) {
            $arr_index=$changes_value_arr[$j][3].'_'.$changes_value_arr[$j][2];
            $vessel_id=$tables_ids[$arr_index];
            if($changes_value_arr[$j][0]=='FirstLoadPortDate' || $changes_value_arr[$j][0]=='LastDisPortDate' || $changes_value_arr[$j][0]=='VesselChangeNameDate' || $changes_value_arr[$j][0]=='RatingDate' || $changes_value_arr[$j][0]=='DeficiencyCompDate' || $changes_value_arr[$j][0]=='DetentionDate' || $changes_value_arr[$j][0]=='DetentionLiftedDate' || $changes_value_arr[$j][0]=='DetentionLiftExpectedDate') {
                  $this->db->where('ResponseVesselID', $vessel_id);
                  $this->db->update('udt_AU_ResponseVessel', array($changes_value_arr[$j][0]=>date('Y-m-d H:i:s', strtotime($changes_value_arr[$j][1]))));
            }else if($changes_value_arr[$j][0]=='DeadWeight' || $changes_value_arr[$j][0]=='Displacement') {
                $valarr=explode(".", $changes_value_arr[$j][1]);
                $int_value=str_replace(',', '', $valarr[0]);
                $this->db->where('ResponseVesselID', $vessel_id);
                $this->db->update('udt_AU_ResponseVessel', array($changes_value_arr[$j][0]=>$int_value));
            } else {
                $this->db->where('ResponseVesselID', $vessel_id);
                $this->db->update('udt_AU_ResponseVessel', array($changes_value_arr[$j][0]=>$changes_value_arr[$j][1]));
            }
        }
    }

        
    for($ii=0; $ii < count($temp_arr); $ii++){
        $temp_arr_exp=explode('_', $temp_arr[$ii]);
        if($temp_arr_exp[0]==1) {
            $this->db->where('ResponseCargoID', $tables_ids[$temp_arr[$ii]]);
            $this->db->update('udt_AU_ResponseCargo', array('ContentChange'=>$view_changes_arr[$temp_arr[$ii]]));
        } else if($temp_arr_exp[0]==2) {
            $all_ids=$tables_ids[$temp_arr[$ii]];
            $all_id_arr=explode('_', $all_ids);
            $this->db->where('FreightResponseID', $all_id_arr[0]);
            $this->db->update('udt_AU_FreightResponse', array('ContentChange'=>$view_changes_arr[$temp_arr[$ii]]));
        } else if($temp_arr_exp[0]==3) {
            $all_ids=$tables_ids[$temp_arr[$ii]];
            $this->db->where('ResponseVesselID', $tables_ids[$temp_arr[$ii]]);
            $this->db->update('udt_AU_ResponseVessel', array('ContentChange'=>$view_changes_arr[$temp_arr[$ii]]));
        }
    }
    $fixture_change=$changes;
        
    $this->db->select('udt_AU_AuctionFixture.*,udt_AUM_Freight.EntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionFixture.ResponseID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID');    
    $this->db->where('udt_AU_AuctionFixture.FixtureID', $FixtureID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AU_AuctionFixture.*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('udt_AU_AuctionFixture.AuctionID', $AuctionID);
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionFixture.FixtureID', 'DESC');
    $Verquery=$this->db->get();
    $Fixresult=$Verquery->row();
        
    if($result->OwnerConfirmation==2 && $checkOwner=='invitee') {
        $OwnerConfirmation=1;
    } else{
        $OwnerConfirmation=$owner_confirm;
    }
        
    $textStatus='';
        
    if($checkOwner=='Owner') {
        $InviteeConfirmation=$Fixresult->InviteeConfirmation;
        if($OwnerConfirmation=='1') {
            $textStatus='Tentative';
            $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_UserMaster.LoginID, udt_EntityMaster.EntityName ');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '15');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
                
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $message='<br>Fixture Note Tentative on : '.date('d-m-Y H:i:s');
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Fixture note tentative',
                    'Page'=>'Charter party (+FN)',
                    'Section'=>'Fixture note',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        } else if($OwnerConfirmation=='2') {
            $textStatus='Final';
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID, udt_EntityMaster.EntityName ');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '16');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
                
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $message='<br>Fixture Note Final on : '.date('d-m-Y H:i:s').'<br>Master ID : '.$AuctionID.'<br>From : '.$row->EntityName;
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Fixture note final',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Fixture note',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    } else if($checkOwner=='invitee') {
        $InviteeConfirmation=$invitee_confirm;
        if($invitee_confirm=='1') {
            $textStatus='Tentative';
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '15');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
            $message='<br>Fixture Note Tentative on : '.date('d-m-Y H:i:s');
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                        
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Fixture note tentative',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Fixture note',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                     );
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        } else if($invitee_confirm=='2') {
            $textStatus='Final';
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '16');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
            $message='<br>Fixture Note Final on : '.date('d-m-Y H:i:s').'<br>Master ID : '.$AuctionID.'<br>From : '.$result->EntityName;
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Fixture note final',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Fixture note',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    }
        
    $FixtureVersionresult=$Fixresult->FixtureVersion;
        
    $version=explode(' ', $FixtureVersionresult);
    $latestversion=$version[1]+0.1;
    if($InviteeConfirmation == 2 && $OwnerConfirmation == 2) {
        $textStatus='Fixture Complete';
    }
        
    $this->db->select('udt_AU_Auctions.CountryID,udt_AU_Auctions.SignDateFlg,udt_AU_Auctions.UserSignDate, udt_CountryMaster.Description as C_Description');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
    $aucquery=$this->db->get();
    $aucRow=$aucquery->row();
        
    $html='';
    $html .='<table id="viewtable" >';
    $html .='<tr><td>From :</td><td>'.$FromName.'</td></tr>';
    $html .='<tr><td>DateTime(DD-MM-YYYY) :</td><td>'.date('d-m-Y H:i:s').'</td></tr>';
    $html .='<tr><td>To :</td><td>'.$ToName.'</td></tr>';
    $html .='<tr><td>Subject :</td><td>Version '.$latestversion.', '.$AuctionID.', '.$ResponseID.'</td></tr>';
    $html .='<tr><td>Status :</td><td>'.$textStatus.'</td></tr>';
    if($aucRow->CountryID > 0) {
        if($aucRow->SignDateFlg==1) {
            $html .='<tr><td>Place / Date :</td><td>'.$aucRow->C_Description.' / '.date('d-m-Y').'</td></tr>';
        } else if($aucRow->SignDateFlg==2) {
            $html .='<tr><td>Place / Date :</td><td>'.$aucRow->C_Description.' / '.date('d-m-Y', strtotime($aucRow->UserSignDate)).'</td></tr>';
        }
    }
    $html .='</table>';
        
        
    $findme='<del';
    $findme1='</del>';
        
    $mystring='';
    $cut='';
    $add='';
        
    $old='<div 35px="">';
    $new='<div style="line-height: 35px;">';
    $Content1=str_replace($old, $new, $Content);
    $mystring=$Content1;
    $prev_mystring=$Content1;
        
    $mystrarray=explode('<del', $mystring);
        
    $addstrarray=explode('<ins', $mystring);
    for($j=0;$j<count($mystrarray);$j++) {
        $pos=0;
        $pos1=0;
        $pos = strpos($mystrarray[$j], $findme);
        $pos1 = strpos($mystrarray[$j], $findme1);
        if($pos1==0 || $pos1=='') {    
        } else {
            $cut .='<del'.substr($mystrarray[$j], $pos, $pos1).'</del> ';
            $cut1='<del'.substr($mystrarray[$j], $pos, $pos1).'</del>';
            $mystring=str_replace($cut1, "", $mystring);
        }
    }
        
    $findnew='<ins';
    $findnew1='</ins>';
    for($j=0;$j<count($addstrarray);$j++) {
        $addpos=0;
        $addpos1=0;
        $addpos = strpos($addstrarray[$j], $findnew);
        $addpos1 = strpos($addstrarray[$j], $findnew1);
        if($addpos1==0 || $addpos1=='') {    
        } else {
            $add .='<ins'.substr($addstrarray[$j], $addpos, $addpos1).'</ins> ';
        }
    }
        
    $tempchange='<p class="delchange"';
            
    $cut=str_replace("<del", $tempchange, $cut);
    $cut=str_replace("del>", "p>", $cut);
        
    $tempchange='<p class="addchange"';
        
    $add=str_replace("<ins", $tempchange, $add);
    $add=str_replace("ins>", "p>", $add);
        
    $mystring=str_replace("ice-ins", "", $mystring);
    $mystring=str_replace("<ins", "<span", $mystring);
    $mystring=str_replace("ins>", "span>", $mystring);
        
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('udt_UserMaster.ID', $UserID);
    $Userquery=$this->db->get();
    $UserResult=$Userquery->row();
    $allcut='';
    if($cut !='') {
        $allcut='<p class="delhead"></p>'.$cut;
        $allcut .='<p >By : '.$UserResult->FirstName.' '.$UserResult->LastName.' </p>';
        $allcut .='<p >DateTime : '.date('d-m-Y H:i:s').'</p>';
    }
    $alladd='';
    if($add !='') {
            
        $alladd='<hr><p class="addhead"></p>'.$add;
        $alladd .='<p >By : '.$UserResult->FirstName.' '.$UserResult->LastName.'</p>';
        $alladd .='<p >DateTime : '.date('d-m-Y H:i:s').'</p>';
    }
            
    $changes='';
    $changes .=$fixture_change;
    $changes .=$allcut;
    $changes .=$alladd;
        
    /* 
    if($changes !='' && $InviteeConfirmation==2 && $checkOwner=='Owner'){
    $InviteeConfirmation=1;
    } else if($OwnerConfirmation==1 && $checkOwner=='Owner'){
    $InviteeConfirmation=1;
    } 
    */
        
    if($OwnerConfirmation==1 && $checkOwner=='Owner') {
        $InviteeConfirmation=1;
    }
        
    if($InviteeConfirmation == 2 && $OwnerConfirmation == 2 ) {
        $status=2;
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID,udt_Entitymaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_Entitymaster', 'udt_Entitymaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '11');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $query1=$this->db->get();
        $msgData=$query1->result();
            
        foreach($msgData as $row){
            if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                $message='<br>Fixture Note Completed on : '.date('d-m-Y H:i:s');
                $fixturedata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'Event'=>'Fixture note completed',
                'Page'=>'Charter party (+FN)',
                'Section'=>'Fixture note',
                'subSection'=>'',
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$row->MessageID,
                'UserID'=>$row->ForUserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
            }
        }
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }else{
        $status=1;
    }
        
    if($OwnerConfirmation==2 && $InviteeConfirmation==2) {

        $mystring=str_replace("addedrecentcontentpradeep", "", $mystring);
        $mystring=preg_replace('#<span deletedrecentcontentpradeep>.*?</span> &nbsp;#s', '', $mystring);
            
    } else {
            
        $tempchange='class="ice-ins ice-cts"';
        $tempchange1='<span addedrecentcontentpradeep';
        $tempchange2='data-username=""';
            
        $prev_mystring=str_replace($tempchange, " ", $prev_mystring);
        $prev_mystring=str_replace("<ins", $tempchange1, $prev_mystring);
        $prev_mystring=str_replace($tempchange2, " ", $prev_mystring);
        $prev_mystring=str_replace("ins>", "span>", $prev_mystring);
            
        $tempchange='class="ice-del ice-cts"';
        $tempchange1='<span deletedrecentcontentpradeep';
        $tempchange2='data-username=""';
            
        $prev_mystring=str_replace($tempchange, " ", $prev_mystring);
        $prev_mystring=str_replace("<del", $tempchange1, $prev_mystring);
        $prev_mystring=str_replace($tempchange2, " ", $prev_mystring);
        $prev_mystring=str_replace("del>", "span> &nbsp;", $prev_mystring);
            
        $mystring=$prev_mystring;
    }
        
    $rep_text='contenteditable="false" style="background-color: #efeaead6"';
    $mystring=str_replace("sujeeteditornoneditable", $rep_text, $mystring);
        
    $rep_text='style="cursor: not-allowed; -webkit-user-select: none; -moz-user-select: -moz-none; -ms-user-select: none; user-select: none; background-color: #efeaead6"';
    $mystring=str_replace("pradeepeditornoneditable", $rep_text, $mystring);
        
    $rep_text='contenteditable="false"';
    $mystring=str_replace("pradeepeditorcontenteditable", $rep_text, $mystring);
        
    $mystring=str_replace("&#39;", "", $mystring);
    $mystring=str_replace("'", "''", $mystring);
    if($OwnerConfirmation !== 2 && $InviteeConfirmation == 2) {
        $mystring1=str_replace("addedrecentcontentpradeep", "", $mystring);
        $mystring1=preg_replace('#<span deletedrecentcontentpradeep>.*?</span> &nbsp;#s', '', $mystring1);
            
        $chh2=strip_tags($mystring1);
        $FixtureHash=hash(HASH_ALGO, $chh2);
        
    } else {
        $chh2=strip_tags($mystring);
        $FixtureHash=hash(HASH_ALGO, $chh2);
        
    }
        
    if($OwnerConfirmation==2 && $InviteeConfirmation==2) {
        if($hash_code !== $FixtureHash) {
            return 2;
        }
    }
        
    $data_h=array(
                'FixtureVersion'=>'Version '.$latestversion,    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'RecordOwner'=>$RecordOwner,    
                'ResponseID'=>$ResponseID,    
                'FixtureNote'=>$mystring,
                'Status'=>$status,
                'InviteeConfirmation'=>$InviteeConfirmation,
                'OwnerConfirmation'=>$OwnerConfirmation,
                'UserID'=>$UserID,
                'RowStatus'=>'1',
                'HeaderContent'=>$html,
                'FixtureNoteChanges'=>$changes,
                'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                'FixtureFormatType'=>$FixtureFormatType,
                'UserDate'=>date('Y-m-d H:i:s')    
    );
    $this->db->insert('udt_AU_AuctionFixture_H', $data_h); 
        
    $data=array(
                'FixtureVersion'=>'Version '.$latestversion,    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'RecordOwner'=>$RecordOwner,    
                'ResponseID'=>$ResponseID,    
                'FixtureNote'=>$mystring,
                'Status'=>$status,
                'InviteeConfirmation'=>$InviteeConfirmation,
                'OwnerConfirmation'=>$OwnerConfirmation,
                'UserID'=>$UserID,
                'HeaderContent'=>$html,
                'FixtureNoteChanges'=>$changes,
                'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                'FixtureFormatType'=>$FixtureFormatType,
                'UserDate'=>date('Y-m-d H:i:s'),
                'FixtureHash'=>$FixtureHash                
    );
            
    $ret=$this->db->insert('udt_AU_AuctionFixture', $data); 
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('udt_AU_AuctionFixture.AuctionID', $AuctionID);
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->order_by('FixtureID', 'desc');
    $qry1=$this->db->get();
    $rw1=$qry1->row();
        
    //---------------blockchain----------------------------
    //Save string into temp file
    $ipfsContent=$mystring;
    $file = tempnam(sys_get_temp_dir(), 'POST');
    file_put_contents($file, $ipfsContent);
        
    //Post file
    $data = array(
    "uploadedFile"=>'@'.$file,
    );
         
    $url=BLOCK_CHAIN_URL.'ipfsDocument/';
    $ch = curl_init($url);      
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
    $ipfsHash = curl_exec($ch);
    curl_close($ch);
        
    unlink($file);
        
        
    $data = array("fixId" =>$rw1->FixtureID,"version" =>$latestversion,'entityId'=>0,"aucId"=>$AuctionID,"tId"=>$ResponseID,"recordId"=>$RecordOwner,"dStatus"=>$status,"invConf"=>$InviteeConfirmation,"ownConf"=>$OwnerConfirmation,"uId"=>$UserID,"fixhash"=>$FixtureHash,"ipfsHash"=>$ipfsHash); 
        
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
    curl_close($ch);
    $docDdata=json_decode($result);
    $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash);
        
    $this->db->where('FixtureID', $rw1->FixtureID);
    $this->db->update('udt_AU_AuctionFixture', $fixUpdatedData);
        
    for($ij=0; $ij<count($ftresult); $ij++){
        $ft_data=array(
        'FixtureID'=>$rw1->FixtureID,
        'ResponseID'=>$ResponseID,
        'CpCode'=>$ftresult[$ij]->CpCode,
        'FieldLblName'=>$allFieldNames[$ij],
        'FieldValue'=>$allFieldValues[$ij],
        'FieldColumnName'=>$ftresult[$ij]->FieldColumnName,
        'EditableFlag'=>$ftresult[$ij]->EditableFlag,
        'ActiveFlag'=>$ftresult[$ij]->ActiveFlag,
        'GroupNumber'=>$ftresult[$ij]->GroupNumber
        );
        $this->db->insert('udt_AU_FixtureTable', $ft_data);
    }
        
    if($status==2) {
        if($FixtureCompleteProcess==1) {
            $this->db->where('udt_AUM_Freight.ResponseID', $ResponseID);
            $this->db->update('udt_AUM_Freight', array('FinalConfirm'=>1,'Status'=>3,'ReadyToSubmit'=>'yes'));
        }
            
        $this->db->where('MasterID', $AuctionID);
        $this->db->where('TID', $ResponseID);
        $this->db->where('DocumentType', 'Fixture Note');
        $this->db->delete('Udt_AU_SinedDocument');
            
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseVessel');
        $this->db->where('udt_AU_ResponseVessel.AuctionID', $AuctionID);
        $this->db->where('udt_AU_ResponseVessel.ResponseID', $ResponseID);
        $this->db->order_by('ResponseVesselID', 'desc');
        $qry=$this->db->get();
        $rw=$qry->row();
        $sign_data=array(
        'MasterID'=>$AuctionID,    
        'TID'=>$ResponseID,    
        'RecordOwner'=>$RecordOwner,    
        'ShipOwner'=>$rw->DisponentOwnerID,    
        'FixtureStatus'=>$status,    
        'CPStatus'=>0,
        'StatusCharterer'=>0,
        'StatusShipowner'=>0,
        'DocumentType'=>'Fixture Note',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
        $this->db->insert('Udt_AU_SinedDocument', $sign_data); 
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function getFixtureNoteLogo()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('AuctionSection', 'cp');
    $query=$this->db->get();
    $title=$query->row()->Title;
        
    $this->db->select('udt_AUM_DocumentType_Master.DocumentTitle,udt_AUM_DocumentType_Master.Logo,udt_AUM_DocumentType_Master.LogoAlign,udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle', 'left');
    $this->db->where('DocumentTypeID', $title);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function checkFixNoteComplete()
{
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $TID);
    $this->db->order_by('FixtureID', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixNoteFinalData()
{
    $ResponseID=$this->input->post('TID');
    $this->db->select('udt_AU_AuctionFixture.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_AuctionFixture.RecordOwner', 'Left');
    $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
    $this->db->where('udt_AU_AuctionFixture.Status', '2');
    $query=$this->db->get();
    return $query->row();
}
    
    
public function checkDocumentationComplete()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_AuctionMainDocumentation.*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
    $query=$this->db->get();
    return $query->row();
    
}
    
    
public function getDocumentationById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
    $this->db->select('udt_AU_AuctionMainDocumentation.*,udt_AUM_Freight.UserName,udt_AUM_Freight.UserID1,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.LoginID');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionMainDocumentation.ResponseID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->where('udt_AU_AuctionMainDocumentation.DocumentationID', $DocumentationID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getDocumentationClauseById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
        
    $this->db->select('udt_AuctionMainClauses.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AuctionMainClauses.UserID');
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $this->db->where('udt_AuctionMainClauses.EditableFlag', 0);
    $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getEditableDocumentationClauseById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
    $this->db->select('udt_AuctionMainClauses.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AuctionMainClauses.UserID');
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $this->db->where('udt_AuctionMainClauses.EditableFlag', 1);
    $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getDocumentationClauseNoteById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $this->db->where('udt_AuctionMainClauses.EditableFlag', 0);
    $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
        
    $query=$this->db->get();
    $result=$query->result();
        
    $i=0;
    foreach($result as $row){
            
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('udt_AuctionMainClauses.AuctionMainClauseID', $row->AuctionMainClauseID);
            $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content[$i] .=str_replace("&#39;", "'", $result1->PTR);
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
        
}
    
public function getEditableDocumentationClauseNoteById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('udt_AuctionMainClauses.DocumentationID', $DocumentationID);
    $this->db->where('udt_AuctionMainClauses.EditableFlag', 1);
    $this->db->order_by('udt_AuctionMainClauses.Clause', 'ASC');
    $query=$this->db->get();
    $result=$query->result();
    $i=0;
    foreach($result as $row){
            
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('udt_AuctionMainClauses.AuctionMainClauseID', $row->AuctionMainClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content[$i] .=str_replace("&#39;", "'", $result1->PTR);
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
}
    
public function getDocumentationAllClauseNoteById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
        
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
                $content[$i] .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
        
}
    
    
public function getDocumentationNoteById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(DocumentationNote, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('udt_AU_AuctionMainDocumentation.DocumentationID', $DocumentationID);
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
    
public function createNewDocumentationNote()
{
    extract($this->input->post());
    $this->db->trans_start();
        
    $this->db->select('udt_AU_AuctionMainDocumentation.*,udt_AUM_Freight.EntityID,udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionMainDocumentation.ResponseID', 'Left');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_Freight.EntityID');    
    $this->db->where('udt_AU_AuctionMainDocumentation.DocumentationID', $DocumentationID);
    $query=$this->db->get();
    $result=$query->row();
        
    $this->db->select('udt_AU_AuctionMainDocumentation.*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
    $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
    $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
    $Verquery=$this->db->get();
    $Documentation=$Verquery->row();
        
    if($result->OwnerConfirmation==2 && $Documentation->InviteeConfirmation !=2 ) {
        $OwnerConfirmation=1;
    }else{
        $OwnerConfirmation=$owner_confirm;
    }
        
    if($checkOwner=='Owner') {
        $InviteeConfirmation=$Documentation->InviteeConfirmation;
        if($OwnerConfirmation=='1') {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_UserMaster.LoginID, udt_EntityMaster.EntityName ');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '18');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
                
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $message='<br>Charter party tentative on : '.date('d-m-Y H:i:s');
                    $documentdata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Charter party tentative',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Charter Party',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                     );
                    $this->db->insert('udt_AU_Messsage_Details', $documentdata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }else if($OwnerConfirmation=='2') {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID, udt_EntityMaster.EntityName ');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '17');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
                
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $message='<br>Charter party final on : '.date('d-m-Y H:i:s');
                    $documentdata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Charter party final',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Charter Party',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                            
                    $this->db->insert('udt_AU_Messsage_Details', $documentdata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    } else if($checkOwner=='invitee') {
        $InviteeConfirmation=$invitee_confirm;
        if($invitee_confirm=='1') {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '18');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
            $message='<br>Charter party tentative on : '.date('d-m-Y H:i:s');
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                        
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Charter party tentative',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Charter Party',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                     );
                            
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }else if($invitee_confirm=='2') {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');        
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '17');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $queryowner=$this->db->get();
            $msgDataowner=$queryowner->result();
            $message='<br>Charter party final on : '.date('d-m-Y H:i:s');
            foreach($msgDataowner as $row){
                if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                    $fixturedata=array(
                    'CoCode'=>'Marx',
                    'AuctionID'=>$AuctionID,
                    'ResponseID'=>$ResponseID,
                    'Event'=>'Charter party final',
                    'Page'=>'Charter Party (+FN)',
                    'Section'=>'Charter Party',
                    'subSection'=>'',
                    'StatusFlag'=>'1',
                    'MessageDetail'=>$message,
                    'MessageMasterID'=>$row->MessageID,
                    'UserID'=>$row->ForUserID,
                    'FromUserID'=>$UserID,
                    'UserDate'=>date('Y-m-d H:i:s')
                    );
                            
                    $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
                }
            }
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    }
        
        
    $DocumentationVersion=$Documentation->DocumentationVersion;
    $version=explode(' ', $DocumentationVersion);
    $newversion=$version[1]+0.1;
        
    $Version='Version '.$newversion;
        
    if($OwnerConfirmation==1 && $checkOwner=='Owner') {
        $InviteeConfirmation=1;
    }
        
    if($InviteeConfirmation == 2 && $OwnerConfirmation == 2 ) {
        $status=2;
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_UserMaster.LoginID, udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
        $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_MESSAGE_MASTER.ForUserID');    
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '19');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $query1=$this->db->get();
        $msgData=$query1->result();
            
        foreach($msgData as $row){
            if($row->EntityID==$RecordOwner || $row->EntityID==$result->EntityID) {
                $message='<br>Charter party note completed on : '.date('d-m-Y H:i:s');
                $fixturedata=array(
                'CoCode'=>'Marx',
                'AuctionID'=>$AuctionID,
                'ResponseID'=>$ResponseID,
                'Event'=>'Charter party completed',
                'Page'=>'Charter Party (+FN)',
                'Section'=>'Charter Party',
                'subSection'=>'',
                'StatusFlag'=>'1',
                'MessageDetail'=>$message,
                'MessageMasterID'=>$row->MessageID,
                'UserID'=>$row->ForUserID,
                'FromUserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
                );
                        
                $this->db->insert('udt_AU_Messsage_Details', $fixturedata); 
            }
        }
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }else{
        $status=1;
    }
        
    $data_h=array(
                'DocumentationVersion'=>$Version,    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'RecordOwner'=>$RecordOwner,    
                'ResponseID'=>$ResponseID,    
                'DocumentationNote'=>$Documentation->DocumentationNote,
                'Status'=>$status,
                'EditableFlag'=>$EditableFlag,
                'ClauseType'=>$Documentation->ClauseType,
                'CharterPartyPdf'=>$CharterPartyPdf,
                'InviteeConfirmation'=>$InviteeConfirmation,
                'OwnerConfirmation'=>$OwnerConfirmation,
                'UserID'=>$UserID,
                'RowStatus'=>'1',
                'UserDate'=>date('Y-m-d H:i:s')    
                );                
    $this->db->insert('udt_AU_AuctionMainDocumentation_H', $data_h);
        
    $data=array(
                'DocumentationVersion'=>$Version,    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'RecordOwner'=>$RecordOwner,    
                'ResponseID'=>$ResponseID,    
                'DocumentationNote'=>$Documentation->DocumentationNote,
                'Status'=>$status,
                'EditableFlag'=>$EditableFlag,
                'ClauseType'=>$Documentation->ClauseType,
                'CharterPartyPdf'=>$CharterPartyPdf,
                'InviteeConfirmation'=>$InviteeConfirmation,
                'OwnerConfirmation'=>$OwnerConfirmation,
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')    
    );
        
    if($EditableFlag==1) {
        $ret=$this->db->insert('udt_AU_AuctionMainDocumentation', $data);
                
        $this->db->select('udt_AU_AuctionMainDocumentation.*');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
        $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
        $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
        $DocIDquery=$this->db->get();
        $NewDocumentationID=$DocIDquery->row()->DocumentationID;
        $findme='<del';
        $findme1='</del>';
            
        $mystring='';
        $cut='';
        $changeFlg=0;
        $total=count($content_clause);
        $allClauseArr=array();
        $all_clauses='';
        for($i=0; $i<$total; $i++){
            $cut='';
            $add='';
            $mystring=str_replace("&#39;", "", $content_clause[$i]);
            $mystring=str_replace("'", "", $mystring);
                
            $clauseall=str_replace("&#39;", "", $content_clause[$i]);
            $clauseall=str_replace("'", "", $clauseall);
                
            $mystrarray=explode('<del', $mystring);
            $addstrarray=explode('<ins', $mystring);
            $cntr=1;
            for($j=0;$j<count($mystrarray);$j++) {
                $pos=0;
                $pos1=0;
                $pos = strpos($mystrarray[$j], $findme);
                $pos1 = strpos($mystrarray[$j], $findme1);
                if($pos1==0 || $pos1=='') {    
                } else {

                    $cut .='<br /><span onclick=getchanges("del_'.$NewDocumentationID.'_'.$cntr.'",1)' .substr($mystrarray[$j], $pos, $pos1).'</span> ';
                    $cut1='<del'.substr($mystrarray[$j], $pos, $pos1).'</del>';
                        
                    $replace='<a id="del_'.$NewDocumentationID.'_'.$cntr.'"></a><span pradeepdelchanges>'.strip_tags($cut1).'</span>&nbsp;';
                        
                    $mystring=str_replace($cut1, $replace, $mystring);
                    $cntr++;
                }
            }
            $findnew='<ins';
            $findnew1='</ins>';
                
            for($j=0;$j<count($addstrarray);$j++) {
                $addpos=0;
                $addpos1=0;
                $addpos = strpos($addstrarray[$j], $findnew);
                $addpos1 = strpos($addstrarray[$j], $findnew1);
                if($addpos1==0 || $addpos1=='') {    
                } else {
                    $cut1='<ins'.substr($addstrarray[$j], $addpos, $addpos1).'</ins>';
                    $replace='<span id="add_'.$NewDocumentationID.'_'.$cntr.'" pradeepaddchanges>'.strip_tags($cut1).'</span>&nbsp;';
                    $mystring=str_replace($cut1, $replace, $mystring);
                    //$mystring=str_replace($cut,'bb',$mystring);
                    $add .='<br /><span onclick=getchanges("add_'.$NewDocumentationID.'_'.$cntr.'",2) '.substr($addstrarray[$j], $addpos, $addpos1).'</span> ';
                    $cntr++;
                }
            }
                
            $mystring=str_replace("&#39;", "", $mystring);
            $mystring=str_replace("'", "''", $mystring);

            if($cut !='') {
                $cut .='<p>By : '.$UserName.' </p>';
                $cut .='<p>DateTime : '.date('d-m-Y H:i:s').'</p>';
            }
                
            if($add !='') {    
                $add .='<p>By : '.$UserName.'</p>';
                $add .='<p>DateTime : '.date('d-m-Y H:i:s').'</p>';
            }
                
            if($ChangeClauseFlg[$i]==1) {
                $data1['ChangeClauseFlg']=1;
            } else if($add !='' || $cut !='') {
                $data1['ChangeClauseFlg']=1;
            } else{
                $data1['ChangeClauseFlg']=0;
            }
            if($OwnerConfirmation==2 && $InviteeConfirmation==2) {
                $mystring=str_replace("pradeepaddchanges", "", $mystring);
                $mystring=preg_replace('#<span pradeepdelchanges>.*?</span>&nbsp;#s', '', $mystring);
            }
                
                $data1['ClauseVersion']=$Version;    
                $data1['AuctionID']=$AuctionID;    
                $data1['RecordOwner']=$RecordOwner;    
                $data1['ResponseID']=$ResponseID;
                //$data1['ClauseName']=$ClauseName[$i];
                $data1['ClauseName']=str_replace("'", "''", $ClauseName[$i]);
                $data1['ClauseNote']=$mystring;
                $data1['DeletedClauseNote']=$cut;
                $data1['AddedClauseNote']=$add;
                $data1['AllClauseNote']=$clauseall;
                //$all_clauses .=$mystring;
                $allClauseArr[$clause_no[$i]]=$mystring;
                $status1='';
                $status2='';
                $status3='';
            if($Status[$i]==1) {
                $status1='Under Discussion';
            } else if($Status[$i]==2) {
                $status1='Final';
            }
            if($PrevStatus[$i]==1) {
                $status2='Under Discussion';
            } else if($PrevStatus[$i]==2) {
                $status2='Final';
            }
            if($PrevInvStatus[$i]==1) {
                $status3='Under Discussion';
            } else if($PrevInvStatus[$i]==2) {
                $status3='Final';
            }
                
            if($chkOwner==1) {
                $clause_cntnt=$content_clause[$i];
                $f_del=strpos($clause_cntnt, "<del");
                $f_ins=strpos($clause_cntnt, "<ins");
                if(($f_del==0 || $f_del=='') && ($f_ins==0 || $f_ins=='')) {
                        $data1['Status']=$Status[$i];
                        $data1['InvStatus']=$PrevInvStatus[$i];
                } else {
                    $data1['Status']=1;
                    $data1['InvStatus']=1;
                    $changeFlg=1;
                }
                    
                if($PrevStatus[$i] !=$Status[$i]) {
                    $data1['ChangeClauseStatus']='<p>Status change from '.$status2.' to '.$status1.'</p><p>By : '.$UserName.'</p><p>DateTime : '.date('d-m-Y H:i:s').'</p>';
                } else {
                    $data1['ChangeClauseStatus']='';
                }
            } else {
                $data1['Status']=$PrevStatus[$i];
                $data1['InvStatus']=$Status[$i];
                if($PrevInvStatus[$i] !=$Status[$i]) {
                        $data1['ChangeClauseStatus']='<p>Status change from '.$status3.' to '.$status1.'<p><p>By : '.$UserName.'</p><p>DateTime : '.date('d-m-Y H:i:s').'</p>';
                } else {
                               $data1['ChangeClauseStatus']='';
                }
            }
                
            if($data1['Status']==2 && $data1['InvStatus']==2) {
                $data1['EditableFlag']=0;
                $data1['ChangeEditableFlag']=1;
            } else {
                $data1['EditableFlag']=$editable[$i];
                $data1['ChangeEditableFlag']=0;
            }
                
                $data1['Clause']=$clause_no[$i];
                $data1['UserID']=$UserID;
                $data1['UserDate']=date('Y-m-d H:i:s');    
                $data1['DocumentationID']=$NewDocumentationID;

                $this->db->insert('udt_AuctionMainClauses', $data1);
        }
            
        ksort($allClauseArr);
        foreach($allClauseArr as $x=>$x_value)  {
                
            $all_clauses .=$x_value;
        }
        if($OwnerConfirmation!=2 && $InviteeConfirmation==2) {
            $all_clauses=str_replace("pradeepaddchanges", "", $all_clauses);
            $all_clauses=preg_replace('#<span pradeepdelchanges>.*?</span>&nbsp;#s', '', $all_clauses);
        }
            
            $chh2=strip_tags($all_clauses);
            
            $CharterHash=hash(HASH_ALGO, $chh2);
        if($OwnerConfirmation==2) {
            if($CharterHash != $PreCharterHash) {
                return 2;
            }
        }
            
            $updata=array('CharterHash'=>$CharterHash);
            $this->db->where('DocumentationID', $NewDocumentationID);
            $this->db->update('udt_AU_AuctionMainDocumentation', $updata);
        if($changeFlg==1) {
            $this->db->where('DocumentationID', $NewDocumentationID);
            $this->db->update('udt_AU_AuctionMainDocumentation', array('InviteeConfirmation'=>1, 'OwnerConfirmation'=>1 ));
        }
            
            /* ---------------blockchain---------------------------- */
            //Save string into temp file
            $ipfsContent=$all_clauses;
            $file = tempnam(sys_get_temp_dir(), 'POST');
            file_put_contents($file, $ipfsContent);
            
            //Post file
            $data = array(
                "uploadedFile"=>'@'.$file,
            );
             
            $url=BLOCK_CHAIN_URL.'ipfsDocument/';
            $ch = curl_init($url);      
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
            curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
            $ipfsHash = curl_exec($ch);
            curl_close($ch);
            
            unlink($file);
            
            $data = array("fixId" =>$NewDocumentationID,"version" =>$newversion,'entityId'=>1,"aucId"=>$AuctionID,"tId"=>$ResponseID,"recordId"=>$RecordOwner,"dStatus"=>$status,"invConf"=>$InviteeConfirmation,"ownConf"=>$OwnerConfirmation,"uId"=>$UserID,"fixhash"=>$CharterHash,"ipfsHash"=>$ipfsHash); 
            
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
        curl_close($ch);
        $docDdata=json_decode($result);
        $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash);
            
        $this->db->where('DocumentationID', $NewDocumentationID);
        $this->db->update('udt_AU_AuctionMainDocumentation', $fixUpdatedData);
            
        /* ---------------blockchain---------------------------- */
            
    } else if($EditableFlag==0) {
        $ret=$this->db->insert('udt_AU_AuctionMainDocumentation', $data);
        $this->db->select('udt_AU_AuctionMainDocumentation.*');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
        $this->db->where('udt_AU_AuctionMainDocumentation.ResponseID', $ResponseID);
        $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
        $DocIDquery=$this->db->get();
        $NewDocumentationID=$DocIDquery->row()->DocumentationID;
        $filename=$DocIDquery->row()->CharterPartyPdf;
            
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
            
        $CharterHash=hash_file(HASH_ALGO, $url);
            
        if($OwnerConfirmation==2) {
            if($CharterHash != $PreCharterHash) {
                return 2;
            }
        }
            
        $updata=array('CharterHash'=>$CharterHash);
        $this->db->where('DocumentationID', $NewDocumentationID);
        $this->db->update('udt_AU_AuctionMainDocumentation', $updata);
            
        /* ---------------blockchain---------------------------- */
            
        /*$ipfsData=array('mediaType'=>'application/pdf','url'=>$url);
        $ipfsJsonData=json_encode($ipfsData);
        //echo $ipfsJsonData;die;
            
        $url1=BLOCK_CHAIN_URL.'IpfsDocumentFromUrl/';
        $ch = curl_init($url1);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $ipfsJsonData);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);   
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(       
        'Content-Type: application/json',        
        'Content-Length: ' . strlen($ipfsJsonData))   
        );
        $ipfsHash = curl_exec($ch); */
            
            
            
            
        $ipfsContent=file_get_contents($url);
        $file = tempnam(sys_get_temp_dir(), 'POST');
        file_put_contents($file, $ipfsContent);
        //Post file
        $data = array(
        "uploadedFile"=>'@'.$file,
        );
            
        $url=BLOCK_CHAIN_URL.'ipfsDocument/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        $ipfsHash = curl_exec($ch);
        curl_close($ch);

        $data = array("fixId" =>$NewDocumentationID,"version" =>$newversion,'entityId'=>1,"aucId"=>$AuctionID,"tId"=>$ResponseID,"recordId"=>$RecordOwner,"dStatus"=>$status,"invConf"=>$InviteeConfirmation,"ownConf"=>$OwnerConfirmation,"uId"=>$UserID,"fixhash"=>$CharterHash,"ipfsHash"=>$ipfsHash); 
            
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
        curl_close($ch);
        $docDdata=json_decode($result);
        $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash);
            
        $this->db->where('DocumentationID', $NewDocumentationID);
        $this->db->update('udt_AU_AuctionMainDocumentation', $fixUpdatedData);
            
        /* ---------------/blockchain---------------------------- */
            
    }
        
    if($status==2) {
        $this->db->where('MasterID', $AuctionID);
        $this->db->where('TID', $ResponseID);
        $this->db->where('DocumentType', 'Charter Party');
        $this->db->delete('Udt_AU_SinedDocument');
            
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseVessel');
        $this->db->where('udt_AU_ResponseVessel.AuctionID', $AuctionID);
        $this->db->where('udt_AU_ResponseVessel.ResponseID', $ResponseID);
        $this->db->order_by('ResponseVesselID', 'desc');
        $qry=$this->db->get();
        $rw=$qry->row();
        $sign_data=array(
        'MasterID'=>$AuctionID,    
        'TID'=>$ResponseID,    
        'RecordOwner'=>$RecordOwner,    
        'ShipOwner'=>$rw->DisponentOwnerID,    
        'FixtureStatus'=>0,    
        'CPStatus'=>$status,
        'StatusCharterer'=>0,
        'StatusShipowner'=>0,
        'DocumentType'=>'Charter Party',
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')    
        );
        $this->db->insert('Udt_AU_SinedDocument', $sign_data); 
    }
    $this->db->trans_complete();
    return $ret;
}
    
public function viewAllDeletedByClause()
{
    $ResponseID=$this->input->post('ResponseID');
    $ClauseName=$this->input->post('ClauseName');
    $this->db->select('udt_AuctionMainClauses.DeletedClauseNote, udt_AuctionMainClauses.AddedClauseNote, udt_AuctionMainClauses.ChangeClauseStatus, udt_AuctionMainClauses.ClauseVersion');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('ClauseName', $ClauseName);
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getAllChausesChanges()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AuctionMainClauses.ClauseVersion, udt_AuctionMainClauses.UserDate, udt_UserMaster.FirstName, udt_UserMaster.LastName');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AuctionMainClauses.UserID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('EditableFlag', '1');
    $this->db->order_by('Clause', 'ASC');
    $this->db->order_by('AuctionMainClauseID', 'ASC');
    $query=$this->db->get();
    return $query->result();
    
}
    
    
public function getDocumentationAllClauseNoteChanges()
{
    if($this->input->post()) {
        $ResponseID=$this->input->post('ResponseID');
    }
    if($this->input->get()) {
        $ResponseID=$this->input->get('ResponseID');
    }
        
    $this->db->select('udt_AuctionMainClauses.AuctionMainClauseID, udt_AuctionMainClauses.ClauseVersion, udt_AuctionMainClauses.AllClauseNote, udt_AuctionMainClauses.UserDate, udt_UserMaster.FirstName, udt_UserMaster.LastName');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AuctionMainClauses.UserID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('EditableFlag', '1');
    $this->db->order_by('Clause', 'ASC');
    $this->db->order_by('AuctionMainClauseID', 'ASC');
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
                $content[$i] .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
}
    
public function getFixtureChangesById()
{
    $FixtureID=$this->input->post('FixtureID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_AuctionFixture.FixtureVersion,udt_AU_AuctionFixture.FixtureNoteChanges,udt_AU_AuctionFixture.InviteeConfirmation,udt_AU_AuctionFixture.OwnerConfirmation,udt_AU_AuctionFixture.Status,udt_AU_AuctionFixture.UserDate,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_AuctionFixture.UserID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('FixtureID', $FixtureID);
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getPrevFixtureData()
{
    $FixtureID=$this->input->post('FixtureID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('udt_AU_AuctionFixture.FixtureVersion,udt_AU_AuctionFixture.FixtureNoteChanges,InviteeConfirmation,OwnerConfirmation,Status');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('FixtureID < ', $FixtureID);
    $this->db->order_by('FixtureID', 'desc');
    $query=$this->db->get();
    return $query->row();
    
}
    
public function getDocumentationChangesById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    } else if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
        
    $this->db->select('udt_AuctionMainClauses.*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('DocumentationID', $DocumentationID);
    $query=$this->db->get();
    return $query->result();
        
}
    
public function insert_cargo_new_version($ResponseCargoID)
{
        
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseCargo');
    $this->db->where('udt_AU_ResponseCargo.ResponseCargoID', $ResponseCargoID);
    $query1=$this->db->get();
    $rescargorow=$query1->row();
        
    $Version=explode(' ', $rescargorow->CargoVersion);
    $nextVersion=$Version[1]+0.01;
    $newVersion='Version '.$nextVersion;
        
    $data=array(
                'CargoVersion'=>$newVersion,
                'ResponseID'=>$rescargorow->ResponseID,
                'CoCode'=>$rescargorow->CoCode,
                'AuctionID'=>$rescargorow->AuctionID,
                'LineNum'=>$rescargorow->LineNum,
                'ActiveFlag'=>$rescargorow->ActiveFlag,
                'SelectFrom'=>$rescargorow->SelectFrom,
                'CargoQtyMT'=>$rescargorow->CargoQtyMT,
                'CargoLoadedBasis'=>$rescargorow->CargoLoadedBasis,
                'CargoLimitBasis'=>$rescargorow->CargoLimitBasis,
                'ToleranceLimit'=>$rescargorow->ToleranceLimit,
                'UpperLimit'=>$rescargorow->UpperLimit,
                'LowerLimit'=>$rescargorow->LowerLimit,
                'LoadPort'=>$rescargorow->LoadPort,
                'LpLaycanStartDate'=>$rescargorow->LpLaycanStartDate,
                'LpLaycanEndDate'=>$rescargorow->LpLaycanEndDate,
                'LpPreferDate'=>$rescargorow->LpPreferDate,
                'LoadingTerms'=>$rescargorow->LoadingTerms,
                'LoadingRateMT'=>$rescargorow->LoadingRateMT,
                'LoadingRateUOM'=>$rescargorow->LoadingRateUOM,
                'LpLaytimeType'=>$rescargorow->LpLaytimeType,
                'LpCalculationBasedOn'=>$rescargorow->LpCalculationBasedOn,
                'LpTurnTime'=>$rescargorow->LpTurnTime,
                'LpPriorUseTerms'=>$rescargorow->LpPriorUseTerms,
                'MaxCargoMT'=>$rescargorow->MaxCargoMT,
                'MinCargoMT'=>$rescargorow->MinCargoMT,
                'LpMaxTime'=>$rescargorow->LpMaxTime,
                'LpLaytimeBasedOn'=>$rescargorow->LpLaytimeBasedOn,
                'LpCharterType'=>$rescargorow->LpCharterType,
                'LpNorTendering'=>$rescargorow->LpNorTendering,
                'CargoInternalComments'=>$rescargorow->CargoInternalComments,
                'CargoDisplayComments'=>$rescargorow->CargoDisplayComments,
                'ExpectedLpDelayDay'=>$rescargorow->ExpectedLpDelayDay,
                'ExpectedLpDelayHour'=>$rescargorow->ExpectedLpDelayHour,
                'BACFlag'=>$rescargorow->BACFlag,
                'LpStevedoringTerms'=>$rescargorow->LpStevedoringTerms,
                'ExceptedPeriodFlg'=>$rescargorow->ExceptedPeriodFlg,
                'NORTenderingPreConditionFlg'=>$rescargorow->NORTenderingPreConditionFlg,
                'NORAcceptancePreConditionFlg'=>$rescargorow->NORAcceptancePreConditionFlg,
                'OfficeHoursFlg'=>$rescargorow->OfficeHoursFlg,
                'LaytimeCommencementFlg'=>$rescargorow->LaytimeCommencementFlg,
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
            
    $ret=$this->db->insert('udt_AU_ResponseCargo', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseCargo');
        $this->db->where('ResponseID', $rescargorow->ResponseID);
        $this->db->where('LineNum', $rescargorow->LineNum);
        $this->db->order_by('ResponseCargoID', 'DESC');
        $cargonewquery=$this->db->get();
        $NewResponseCargoID=$cargonewquery->row()->ResponseCargoID;
            
        if($rescargorow->ExceptedPeriodFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseExceptedPeriods');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('EPID', 'ASC');
            $epQry=$this->db->get();
            $ExceptedPeriods=$epQry->result();
                
            foreach($ExceptedPeriods as $ep){
                $excepted_data=array(
                'AuctionID'=>$ep->AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$ep->ResponseID,
                'EventID'=>$ep->EventID,
                'LaytimeCountsOnDemurrageFlg'=>$ep->LaytimeCountsOnDemurrageFlg,
                'LaytimeCountsFlg'=>$ep->LaytimeCountsFlg,
                'TimeCountingFlg'=>$ep->TimeCountingFlg,
                'ExceptedPeriodComment'=>$ep->ExceptedPeriodComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseExceptedPeriods', $excepted_data);
            }
        }
            
        if($rescargorow->NORTenderingPreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseNORTenderingPreConditions');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('TPCID', 'ASC');
            $tpQry=$this->db->get();
            $TenderingPreCondition=$tpQry->result();
                
            foreach($TenderingPreCondition as $tp){
                    
                  $tendering_data=array(
                   'AuctionID'=>$tp->AuctionID,
                   'ResponseCargoID'=>$NewResponseCargoID,
                   'ResponseID'=>$tp->ResponseID,
                   'CreateNewOrSelectListFlg'=>$tp->CreateNewOrSelectListFlg,
                   'NORTenderingPreConditionID'=>$tp->NORTenderingPreConditionID,
                   'NewNORTenderingPreCondition'=>$tp->NewNORTenderingPreCondition,
                   'StatusFlag'=>$tp->StatusFlag,
                   'TenderingPreConditionComment'=>$tp->TenderingPreConditionComment,
                   'UserID'=>$UserID,
                   'CreatedDate'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('udt_AU_ResponseNORTenderingPreConditions', $tendering_data);
            }
                
        }
            
        if($rescargorow->NORAcceptancePreConditionFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseNORAcceptancePreConditions');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('APCID', 'ASC');
            $apQry=$this->db->get();
            $AcceptancePreConditions=$apQry->result();
                
            foreach($AcceptancePreConditions as $ap){
                $acceptance_data=array(
                'AuctionID'=>$ap->AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$ap->ResponseID,
                'CreateNewOrSelectListFlg'=>$ap->CreateNewOrSelectListFlg,
                'NORAcceptancePreConditionID'=>$ap->NORAcceptancePreConditionID,
                'NewNORAcceptancePreCondition'=>$ap->NewNORAcceptancePreCondition,
                'StatusFlag'=>$ap->StatusFlag,
                'AcceptancePreConditionComment'=>$ap->AcceptancePreConditionComment,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseNORAcceptancePreConditions', $acceptance_data);
            }
                
        }
            
        if($rescargorow->OfficeHoursFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseOfficeHours');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('OHID', 'ASC');
            $ohQry=$this->db->get();
            $OfficeHours=$ohQry->result();
                
            foreach($OfficeHours as $oh){
                $office_data=array(
                'AuctionID'=>$oh->AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$oh->ResponseID,
                'DateFrom'=>$oh->DateFrom,
                'DateTo'=>$oh->DateTo,
                'TimeFrom'=>$oh->TimeFrom,
                'TimeTo'=>$oh->TimeTo,
                'IsLastEntry'=>$oh->IsLastEntry,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseOfficeHours', $office_data);
            }
                
        }
            
        if($rescargorow->LaytimeCommencementFlg==1) {
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseLaytimeCommencement');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('LCID', 'ASC');
            $lcQry=$this->db->get();
            $LaytimeCommencement=$lcQry->result();
                
            foreach($LaytimeCommencement as $lc){
                $commence_data=array(
                'AuctionID'=>$lc->AuctionID,
                'ResponseCargoID'=>$NewResponseCargoID,
                'ResponseID'=>$lc->ResponseID,
                'DayFrom'=>$lc->DayFrom,
                'DayTo'=>$lc->DayTo,
                'TimeFrom'=>$lc->TimeFrom,
                'TimeTo'=>$lc->TimeTo,
                'TurnTime'=>$lc->TurnTime,
                'TurnTimeExpire'=>$lc->TurnTimeExpire,
                'LaytimeCommenceAt'=>$lc->LaytimeCommenceAt,
                'LaytimeCommenceAtHour'=>$lc->LaytimeCommenceAtHour,
                'SelectDay'=>$lc->SelectDay,
                'TimeCountsIfOnDemurrage'=>$lc->TimeCountsIfOnDemurrage,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
                $this->db->insert('udt_AU_ResponseLaytimeCommencement', $commence_data);
            }
                
        }
            
            $this->db->select('*');
            $this->db->from('udt_AU_ResponseCargoDisports');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->where('ConfirmFlg', '1');
            $this->db->order_by('DisportNo', 'asc');
            $qry=$this->db->get();
            $disResult=$qry->result();
            
        foreach($disResult as $dis){
                
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
                    $this->db->from('udt_AU_ResponseDpExceptedPeriods');
                    $this->db->where('ResponseDisportID', $dis->RCD_ID);
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
                    $this->db->from('udt_AU_ResponseDpNORTenderingPreConditions');
                    $this->db->where('ResponseDisportID', $dis->RCD_ID);
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
                    $this->db->from('udt_AU_ResponseDpNORAcceptancePreConditions');
                    $this->db->where('ResponseDisportID', $dis->RCD_ID);
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
                    $this->db->from('udt_AU_ResponseDpOfficeHours');
                    $this->db->where('ResponseDisportID', $dis->RCD_ID);
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
                    $this->db->from('udt_AU_ResponseDpLaytimeCommencement');
                    $this->db->where('ResponseDisportID', $dis->RCD_ID);
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
            
            $this->db->select('*');
            $this->db->from('udt_AU_BACResponse');
            $this->db->where('ResponseCargoID', $ResponseCargoID);
            $this->db->order_by('BACResponse_ID', 'asc');
            $this->db->order_by('SeqNo', 'asc');
            $query=$this->db->get();
            $bac_result=$query->result();
            
        foreach($bac_result as $bac_row){
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
            
            $query1 = $this->db->query(
                "insert into cops_admin.udt_AU_BACResponse_H (BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, ConfirmFlg, SeqNo, RowStatus, UserID,UserDate )
			select BACResponse_ID, AuctionID, ResponseID, TransactionType, PayingEntityType, PayingEntityName, ReceivingEntityType, ReceivingEntityName, BrokerName, PayableAs, PercentageOnFreight, PercentageOnDeadFreight, PercentageOnDemmurage, PercentageOnOverage, LumpsumPayable, RatePerTonnePayable, BACComment, ResponseCargoID, 1, SeqNo, 1, '".$UserID."','".date('Y-m-d H:i:s')."'
			from cops_admin.udt_AU_BACResponse where ResponseCargoID='".$NewResponseCargoID."' order by BACResponse_ID asc"
            );
            
        return $NewResponseCargoID;
    } else {
        return 0;
    }
}
    
public function insert_freight_new_version($FreightResponseID)
{
        
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_AU_FreightResponse');
    $this->db->where('udt_AU_FreightResponse.FreightResponseID', $FreightResponseID);
    $query1=$this->db->get();
    $resfreightrow=$query1->row();
        
    $Version=explode(' ', $resfreightrow->FreightVersion);
    $nextVersion=$Version[1]+0.01;
    $newVersion='Version '.$nextVersion;
        
    $data=array(
                'CoCode'=>$resfreightrow->CoCode,
                'FreightVersion'=>$newVersion,
                'AuctionID'=>$resfreightrow->AuctionID,
                'ResponseID'=>$resfreightrow->ResponseID,
                'LineNum'=>$resfreightrow->LineNum,
                'RecordOwner'=>$resfreightrow->RecordOwner,
                'FreightBasis'=>$resfreightrow->FreightBasis,
                'FreightRate'=>$resfreightrow->FreightRate,
                'FreightCurrency'=>$resfreightrow->FreightCurrency,
                'FreightRateUOM'=>$resfreightrow->FreightRateUOM,
                'FreightTce'=>$resfreightrow->FreightTce,
                'FreightTceDifferential'=>$resfreightrow->FreightTceDifferential,
                'FreightLumpsumMax'=>$resfreightrow->FreightLumpsumMax,
                'FreightLow'=>$resfreightrow->FreightLow,
                'FreightHigh'=>$resfreightrow->FreightHigh,
                'Demurrage'=>$resfreightrow->Demurrage,
                'DespatchDemurrageFlag'=>$resfreightrow->DespatchDemurrageFlag,
                'DespatchHalfDemurrage'=>$resfreightrow->DespatchHalfDemurrage,
                'CommentsByAuctioner'=>$resfreightrow->CommentsByAuctioner,
                'CommentForInvitees'=>$resfreightrow->CommentForInvitees,
                'EntityID'=>$resfreightrow->EntityID,
                'DifferentialInvitee'=>$resfreightrow->DifferentialInvitee,
                'CommentsByInvitees'=>$resfreightrow->CommentsByInvitees,
                'ContentChange'=>'',
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
            
    $ret=$this->db->insert('udt_AU_FreightResponse', $data);
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_FreightResponse');
        $this->db->where('ResponseID', $resfreightrow->ResponseID);
        $this->db->where('LineNum', $resfreightrow->LineNum);
        $this->db->order_by('FreightResponseID', 'DESC');
        $freightnewquery=$this->db->get();
        $NewFreightResponseID=$freightnewquery->row()->FreightResponseID;
            
        $this->db->select('*');
        $this->db->from('udt_AU_DifferentialsResponse');
        $this->db->where('ResponseID', $resfreightrow->ResponseID);
        $this->db->where('LineNum', $resfreightrow->LineNum);
        $this->db->order_by('DifferentialID', 'DESC');
        $differqry=$this->db->get();
        $resdiffrow=$differqry->row();
            
        $data1=array(
        'CoCode'=>$resdiffrow->CoCode,
        'AuctionID'=>$resdiffrow->AuctionID,
        'ResponseID'=>$resdiffrow->ResponseID,
        'Version'=>$newVersion,
        'InviteeID'=>$resdiffrow->InviteeID,
        'LineNum'=>$resdiffrow->LineNum,
        'VesselGroupSizeID'=>$resdiffrow->VesselGroupSizeID,
        'BaseLoadPort'=>$resdiffrow->BaseLoadPort,
        'FreightReferenceFlg'=>$resdiffrow->FreightReferenceFlg,
        'DisportRefPort1'=>$resdiffrow->DisportRefPort1,
        'DisportRefPort2'=>$resdiffrow->DisportRefPort2,
        'DisportRefPort3'=>$resdiffrow->DisportRefPort3,
        'CargoOwnerComment'=>$resdiffrow->CargoOwnerComment,
        'InviteeComment'=>$resdiffrow->InviteeComment,
        'UserID'=>$UserID,
        'UserDate'=>date('Y-m-d H:i:s')
        );
        $ret1=$this->db->insert('udt_AU_DifferentialsResponse', $data1);
        if($ret1) {
            $this->db->select('*');
            $this->db->from('udt_AU_DifferentialsResponse');
            $this->db->where('ResponseID', $resfreightrow->ResponseID);
            $this->db->where('LineNum', $resfreightrow->LineNum);
            $this->db->order_by('DifferentialID', 'DESC');
            $differqry1=$this->db->get();
            $resdiffrow1=$differqry1->row();
                
            $NewDifferentialID=$resdiffrow1->DifferentialID;
                
            $this->db->select('*');
            $this->db->from('udt_AU_DifferentialRefDisportsResponse');
            $this->db->where('DifferentialID', $resdiffrow->DifferentialID);
            $this->db->order_by('GroupNo', 'ASC');
            $this->db->order_by('PrimaryPortFlg', 'DESC');
            $this->db->order_by('DiffRefDisportID', 'ASC');
            $qry1=$this->db->get();
            $result1=$qry1->result();
                
            foreach($result1 as $r){
                  $data2=array(
                   'DifferentialID'=>$NewDifferentialID,
                   'AuctionID'=>$r->AuctionID,
                   'ResponseID'=>$r->ResponseID,
                   'RefDisportID'=>$r->RefDisportID,
                   'LpDpFlg'=>$r->LpDpFlg,
                   'LoadDischargeRate'=>$r->LoadDischargeRate,
                   'LoadDischargeUnit'=>$r->LoadDischargeUnit,
                   'DifferentialFlg'=>$r->DifferentialFlg,
                   'DifferentialOwnerAmt'=>$r->DifferentialOwnerAmt,
                   'DifferentialInviteeAmt'=>$r->DifferentialInviteeAmt,
                   'GroupNo'=>$r->GroupNo,
                   'PrimaryPortFlg'=>$r->PrimaryPortFlg,
                   'UserID'=>$UserID,
                   'CreatedDate'=>date('Y-m-d H:i:s')
                  );
                  $this->db->insert('udt_AU_DifferentialRefDisportsResponse', $data2);
            }
        } else {
            $NewDifferentialID=0;
        }
            return $NewFreightResponseID.'_'.$NewDifferentialID;
    } else {
        return 0;
    }
}
    
public function insert_vessel_new_version($ResponseVesselID)
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('udt_AU_ResponseVessel.ResponseVesselID', $ResponseVesselID);
    $query1=$this->db->get();
    $resvesselrow=$query1->row();
        
    $Version=explode(' ', $resvesselrow->VesselVersion);
    $nextVersion=$Version[1]+0.01;
    $newVersion='Version '.$nextVersion;
        
    $data=array(
                'CoCode'=>$resvesselrow->CoCode,
                'VesselVersion'=>$newVersion,
                'ResponseID'=>$resvesselrow->ResponseID,
                'AuctionID'=>$resvesselrow->AuctionID,
                'RecordOwner'=>$resvesselrow->RecordOwner,
                'SelectVesselBy'=>$resvesselrow->SelectVesselBy,
                'VesselName'=>$resvesselrow->VesselName,
                'IMO'=>$resvesselrow->IMO,
                'VesselCurrentName'=>$resvesselrow->VesselCurrentName,
                'VesselChangeNameDate'=>$resvesselrow->VesselChangeNameDate,
                'FirstLoadPortDate'=>$resvesselrow->FirstLoadPortDate,
                'LastDisPortDate'=>$resvesselrow->LastDisPortDate,
                'DisponentOwnerID'=>$resvesselrow->DisponentOwnerID,
                'Address1'=>$resvesselrow->Address1,
                'Address2'=>$resvesselrow->Address2,
                'Address3'=>$resvesselrow->Address3,
                'Address4'=>$resvesselrow->Address4,
                'CountryID'=>$resvesselrow->CountryID,
                'StateID'=>$resvesselrow->StateID,
                'LOA'=>$resvesselrow->LOA,
                'Beam'=>$resvesselrow->Beam,
                'Draft'=>$resvesselrow->Draft,
                'DeadWeight'=>$resvesselrow->DeadWeight,
                'Displacement'=>$resvesselrow->Displacement, 
                'Source'=>$resvesselrow->Source,
                'Rating'=>$resvesselrow->Rating,
                'RatingDate'=>$resvesselrow->RatingDate,
                'SourceType'=>$resvesselrow->SourceType,
                'VettingSource'=>$resvesselrow->VettingSource,
                'Deficiency'=>$resvesselrow->Deficiency,
                'DeficiencyCompDate'=>$resvesselrow->DeficiencyCompDate, 
                'DetentionFlag'=>$resvesselrow->DetentionFlag,
                'CommentAuction'=>$resvesselrow->CommentAuction,
                'CommentInvitee'=>$resvesselrow->CommentInvitee,
                'CommentByInvitee'=>$resvesselrow->CommentByInvitee,
                'DetentionDate'=>$resvesselrow->DetentionDate,
                'DetentionLiftedFlag'=>$resvesselrow->DetentionLiftedFlag,
                'DetentionLiftedDate'=>$resvesselrow->DetentionLiftedDate, 
                'DetentionLiftExpectedDate'=>$resvesselrow->DetentionLiftExpectedDate, 
                'VesselConfirmFlg'=>$resvesselrow->VesselConfirmFlg, 
                'UserID'=>$UserID,
                'RecordAddBy'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')
    );
            
    $ret=$this->db->insert('udt_AU_ResponseVessel', $data); 
        
    if($ret) {
        $this->db->select('*');
        $this->db->from('udt_AU_ResponseVessel');
        $this->db->where('udt_AU_ResponseVessel.ResponseID', $resvesselrow->ResponseID);
        $this->db->order_by('ResponseVesselID', 'DESC');
        $vesselnewquery=$this->db->get();
        $NewResponseVesselID=$vesselnewquery->row()->ResponseVesselID;
            
        return $NewResponseVesselID;
    } else {
        return 0;
    }
}
    
public function getDocumentationClauses()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
    }
    if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
    }
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('DocumentationID', $DocumentationID);
    $this->db->order_by('Clause', 'ASC');
    $query=$this->db->get();
    $result= $query->result();
        
    $i=0;
    foreach($result as $row){
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('AuctionMainClauseID', $row->AuctionMainClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content[$i] .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
    
}
    
public function getRecordownerInviteeByTid()
{
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('ResponseID', $TID);
    $query=$this->db->get();
    $rslt1=$query->row();    
    $rslt['owner']=$rslt1->RecordOwner;
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $TID);
    $query=$this->db->get();
    $rslt2=$query->row();    
    $rslt['invitee']=$rslt2->EntityID;
    return $rslt;
}
    
public function getInviteeStatusByClauseid()
{
    $ClauseID=$this->input->post('ClauseID');
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('AuctionMainClauseID', $ClauseID);
    $query=$this->db->get();
    return $query->row();
}
    
public function saveVettingApprove()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    $row=$query->row();
    $ResponseID=$row->TID;
    $AuctionID=$row->MasterID;
    $data=array(
    'BPID'=>$row->BPID,
    'RecordOwner'=>$row->RecordOwner,
    'MasterID'=>$row->MasterID,
    'TID'=>$row->TID,
    'name_of_process'=>$row->name_of_process,
    'process_applies'=>$row->process_applies,
    'process_flow_sequence'=>$row->process_flow_sequence,
    'putting_freight_quote'=>$row->putting_freight_quote,
    'submitting_freight_quote'=>$row->submitting_freight_quote,
    'fixture_not_finalization'=>$row->fixture_not_finalization,
    'charter_party_finalization'=>$row->charter_party_finalization,
    'finalization_completed_by'=>$row->finalization_completed_by,
    'message_text'=>$row->message_text,
    'show_in_process'=>$row->show_in_process,
    'show_in_fixture'=>$row->show_in_fixture,
    'show_in_charter_party'=>$row->show_in_charter_party,
    'validity'=>$row->validity,
    'date_from'=>$row->date_from,
    'date_to'=>$row->date_to,
    'status'=>$row->status,
    'comments'=>$row->comments,
    'UserID'=>$UserID,
    'ApproveStatus'=>$AppTechVet,
    'ApprovedBy'=>$TechVetAppBy,
    'UserDate'=>date('Y-m-d H:i:s'),
    'Version'=>($row->Version+0.1),
    'on_subject_status'=>$row->on_subject_status,
    'lift_subject_status'=>$row->lift_subject_status
    );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('UserID', $UserID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $last_id=$query->row()->BPVID;
        
    $this->db->select('*,CONVERT(VARCHAR(10),VettingRatingDate,105) as fVettingRatingDate,CONVERT(VARCHAR(10),VettingApproveDate,105) as fVettingApproveDate');
    $this->db->from('Udt_AU_ApproveVetting');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    $rdata=$query->row();
        
    $html='';
    if(count($rdata)>0) {
        if($rdata->VettingRiskSource != $vrsource) {
            $html .='<br>Old Vetting risk source : '.$rdata->VettingRiskSource.' <span class="diff">||</span> New Vetting risk source : '.$vrsource;
        }
        
        if($rdata->VettingRiskRating != $vrrating) {
            $html .='<br>Old Vetting risk rating : '.$rdata->VettingRiskRating.' <span class="diff">||</span> New Vetting risk rating : '.$vrrating;
        }
        
        if($rdata->fVettingRatingDate != $vratingdate) {
            $html .='<br>Old Vetting rating date : '.$rdata->fVettingRatingDate.' <span class="diff">||</span> New Vetting rating date : '.$vratingdate;
        }
        
        if($rdata->VettingApprove != $vetting_approve) {
            $html .='<br>Old Vetting approval number : '.$rdata->VettingApprove.' <span class="diff">||</span> New Vetting approval number : '.$vetting_approve;
        }
        
        if($rdata->fVettingApproveDate != $vapprovedate) {
            $html .='<br>Old Vetting approval date : '.$rdata->fVettingApproveDate.' <span class="diff">||</span> New Vetting approval date : '.$vapprovedate;
        }
        
        if($rdata->ApproveStatus != $AppTechVet) {
            $oldstatus='No';
            $newstatus='No';
            if($rdata->ApproveStatus==1) {
                $oldstatus='Yes';
            }
            if($AppTechVet==1) {
                $newstatus='Yes';
            }
            $html .='<br>Old Approve technical vetting : '.$oldstatus.' <span class="diff">||</span> New Approve technical vetting : '.$newstatus;
        }
        
        if($rdata->VettingComment != $vetting_comment) {
            $html .='<br>Old Comments : '.$rdata->VettingComment.' <span class="diff">||</span> New Comments : '.$vetting_comment;
        }
        
        if($rdata->IsSource != $issource) {
            $html .='<br>Old Is source inhouse or third party : '.$rdata->IsSource.' <span class="diff">||</span> New Is source inhouse or third party : '.$issource;
        }
        
        if($rdata->SourceVetting != $sourceofvetting) {
            $html .='<br>Old Source of vetting is : '.$rdata->SourceVetting.' <span class="diff">||</span> New Source of vetting is : '.$sourceofvetting;
        }
    } else {
        if($vrsource) {
            $html .='<br>Old Vetting risk source :  <span class="diff">||</span> New Vetting risk source : '.$vrsource;
        }
        if($vrrating) {
            $html .='<br>Old Vetting risk rating :  <span class="diff">||</span> New Vetting risk rating : '.$vrrating;
        }
        if($vratingdate) {
            $html .='<br>Old Vetting rating date :  <span class="diff">||</span> New Vetting rating date : '.$vratingdate;
        }
        if($vetting_approve) {
            $html .='<br>Old Vetting approval number :  <span class="diff">||</span> New Vetting approval number : '.$vetting_approve;
        }
        if($vapprovedate) {
            $html .='<br>Old Vetting approval date :  <span class="diff">||</span> New Vetting approval date : '.$vapprovedate;
        }
        $newstatus='No';
        if($AppTechVet==1) {
            $newstatus='Yes';
        }
        if($AppTechVet==1) {
            $html .='<br>Old Approve technical vetting : No <span class="diff">||</span> New Approve technical vetting : '.$newstatus;
        }
        
        if($vetting_comment) {
            $html .='<br>Old Comments :  <span class="diff">||</span> New Comments : '.$vetting_comment;
        }
        if($issource) {
            $html .='<br>Old Is source inhouse or third party :  <span class="diff">||</span> New Is source inhouse or third party : '.$issource;
        }
        if($sourceofvetting) {
            $html .='<br>Old Source of vetting is :  <span class="diff">||</span> New Source of vetting is : '.$sourceofvetting;
        }
    }
    $html1=trim($html, "<br>");
    $htmldata=array('ViewChage'=>$html1);
    $this->db->where('BPVID', $last_id);
    $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
        
    $data=array('BPVID'=>$last_id,
    'TID'=>$TID,
    'RecordOwner'=>$EntityID,
    'VesselName'=>$vetting_name_charter,
    'IMO'=>$imo_number_chater,
    'VettingRiskSource'=>$vrsource,
    'VettingRiskRating'=>$vrrating,
    'VettingRatingDate'=>date('Y-m-d', strtotime($vratingdate)),
    'IsSource'=>$issource,
    'SourceVetting'=>$sourceofvetting,
    'VettingApprove'=>$vetting_approve,
    'VettingApproveDate'=>date('Y-m-d', strtotime($vapprovedate)),
    'VettingComment'=>$vetting_comment,
    'UserID'=>$UserID,
    'ApproveStatus'=>$AppTechVet,
    'ApprovedBy'=>$TechVetAppBy,
    'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('Udt_AU_ApproveVetting', $data);
        
    if($AppTechVet==1) {
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
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $auctionrow->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '24');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $frow->UserID);
        $query1=$this->db->get();
        $msgRecord=$query1->result();
            
        $msgDetails='<br>Technical business vetting approved on : '.date('d-m-Y'); 
        foreach($msgRecord as $mr){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Technical vetting approve',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$mr->MessageID,    
            'UserID'=>$mr->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
            
            
        $ownerarr[] =$frow->UserID;
            
        $this->db->select('udt_AU_BusinessProcessAuctionWise.*,udt_AUM_BusinessProcess.name_of_process');
        $this->db->from('udt_AU_BusinessProcessAuctionWise');
        $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
        $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
        $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 1);
        $bus_query=$this->db->get();
        $bus=$bus_query->row();
            
        if($bus) {
            $busUserIds=explode(",", $bus->UserList);
            for($i=0;$i<count($busUserIds); $i++ ) {
                if(!in_array($busUserIds[$i], $ownerarr)) {
                    $ownerarr[] =$busUserIds[$i];
                    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
                    $this->db->from('udt_AUM_MESSAGE_MASTER');
                    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
                    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '24');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $busUserIds[$i]);
                    $query1=$this->db->get();
                    $msgRecord=$query1->row();
                        
                    if($msgRecord) {
                               $msgDetails='<br>Technical vetting approve on : '.date('d-m-Y'); 
                               $msg=array(
                         'CoCode'=>C_COCODE,    
                         'AuctionID'=>$AuctionID,    
                         'ResponseID'=>$ResponseID,    
                         'Event'=>'Technical vetting approve',    
                         'Page'=>'Charter Party (+FN)',    
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
                            
                               $this->db->where('AuctionID', $AuctionID);
                               $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
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
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '24');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $invIds[$i]);
            $query1=$this->db->get();
            $msgRecord=$query1->row();
                
            if($msgRecord) {
                $msgDetails='<br>Technical vetting approve on : '.date('d-m-Y'); 
                $msg=array(
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'ResponseID'=>$ResponseID,    
                'Event'=>'Technical vetting approve',    
                'Page'=>'Charter Party (+FN)',    
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
                    
                $this->db->where('AuctionID', $AuctionID);
                $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
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
                    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '24');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $bu->SigningUserID);
                    $query1=$this->db->get();
                    $msgRecord=$query1->row();
                        
                    if($msgRecord) {
                         $msgDetails='<br>Technical vetting approve on : '.date('d-m-Y'); 
                         $msg=array(
                           'CoCode'=>C_COCODE,    
                           'AuctionID'=>$AuctionID,    
                           'ResponseID'=>$ResponseID,    
                           'Event'=>'Technical vetting approve',    
                           'Page'=>'Charter Party (+FN)',    
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
                            
                         $this->db->where('AuctionID', $AuctionID);
                         $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                    }
                    $bUsersArr[] =$bu->SigningUserID;
                }
            }
        }
    }
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$last_id,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++) {
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
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
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$last_id,
                   'name_of_process'=>1,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                    
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
    return $ret;
}
    
public function getApproveVetting()
{
    $TID=$this->input->post('ResponseID');
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('BPVID,fixture_not_finalization,ApproveStatus');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    $this->db->order_by('Version', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getBusinessProcessByAuctionId($BPID)
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $this->db->select('udt_AU_BusinessProcessVersionWise.*,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BusinessProcessVersionWise.UserID');
    $this->db->where('udt_AU_BusinessProcessVersionWise.MasterID', $AuctionID);
    $this->db->where('udt_AU_BusinessProcessVersionWise.TID', $TID);
    $this->db->where('udt_AU_BusinessProcessVersionWise.BPID', $BPID);
    $this->db->order_by('udt_AU_BusinessProcessVersionWise.Version', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getUniqueBusinessProcessByAuctionId()
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $BPID=$this->input->post('BPID');
        
    $complete_by=array('1','3','4','5','6');
        
    $this->db->select('DISTINCT BPID,process_flow_sequence');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where_in('finalization_completed_by', $complete_by);
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    $this->db->order_by('process_flow_sequence', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getNextPendingBusinessProcessByAuctionId()
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    $this->db->where('ApproveStatus', 1);
    $this->db->order_by('process_flow_sequence', 'DESC');
    $query=$this->db->get();
    $completeRow=$query->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    if($completeRow) {
        $this->db->where('process_flow_sequence >= ', $completeRow->process_flow_sequence);
    }
        
    $this->db->order_by('process_flow_sequence', 'ASC');
    $this->db->order_by('BPID', 'ASC');
    $query1=$this->db->get();
    $BP_result=$query1->result();
        
    $temp_bpid='';
    foreach($BP_result as $bp){
        if($temp_bpid != $bp->BPID) {
            $this->db->select('*');
            $this->db->from('udt_AU_BusinessProcessVersionWise');
            $this->db->where('MasterID', $AuctionID);
            $this->db->where('TID', $TID);
            $this->db->where('BPID', $bp->BPID);
            $this->db->order_by('BPVID', 'DESC');
            $query1=$this->db->get();
            $check_row=$query1->row();
            if($check_row->ApproveStatus != 1) {
                return $check_row;
            }
            $temp_bpid = $bp->BPID;
        }
            
    }
        
}
        
public function getUniqueInvBusinessProcessByAuctionId()
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $BPID=$this->input->post('BPID');
        
    $this->db->select('DISTINCT BPID,process_flow_sequence');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('finalization_completed_by', '2');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    $this->db->order_by('process_flow_sequence', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function businessProcessTechnicalVetting()
{
    $BPVID=$this->input->post('BPVID');
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    $row=$query->row();
        
    $data=array(
     'BPID'=>$row->BPID,
     'RecordOwner'=>$row->RecordOwner,
     'MasterID'=>$row->MasterID,
     'TID'=>$row->TID,
     'name_of_process'=>$row->name_of_process,
     'process_applies'=>$row->process_applies,
     'process_flow_sequence'=>$row->process_flow_sequence,
     'putting_freight_quote'=>$row->putting_freight_quote,
     'submitting_freight_quote'=>$row->submitting_freight_quote,
     'fixture_not_finalization'=>$row->fixture_not_finalization,
     'charter_party_finalization'=>$row->charter_party_finalization,
     'finalization_completed_by'=>$row->finalization_completed_by,
     'message_text'=>$row->message_text,
     'show_in_process'=>$row->show_in_process,
     'show_in_fixture'=>$row->show_in_fixture,
     'show_in_charter_party'=>$row->show_in_charter_party,
     'validity'=>$row->validity,
     'date_from'=>$row->date_from,
     'date_to'=>$row->date_to,
     'status'=>$row->status,
     'comments'=>$row->comments,
     'UserID'=>$UserID,
     'ApproveStatus'=>$row->ApproveStatus,
     'ApprovedBy'=>$row->ApprovedBy,
     'UserDate'=>date('Y-m-d H:i:s'),
     'Version'=>($row->Version+0.1),
     'ViewChage'=>'',
     'on_subject_status'=>$row->on_subject_status,
     'lift_subject_status'=>$row->lift_subject_status
     );
                        
    return $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
}
    
public function authenticateUser()
{
    $TID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_BusinessProcessAuctionWise.UserList');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
    $this->db->where('udt_AUM_BusinessProcess.name_of_process', 1);
    $query=$this->db->get();
    return $query->row()->UserList;
        
}
    
public function authenticateUser1()
{
    $TID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $ApprovalType=$this->input->post('ApprovalType');
    $this->db->select('udt_AU_BusinessProcessAuctionWise.UserList');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
    if($ApprovalType=='businesss') {
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 2);
    }
    if($ApprovalType=='CounterParty') {
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 3);
    }
    if($ApprovalType=='ComplianceRisk') {
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 4);
    }
    if($ApprovalType=='CpSubjectCharter') {
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 9);
    }
    $query=$this->db->get();
    return $query->row()->UserList;
        
}
    
public function getBettingByBpvid()
{
    $BPVID=$this->input->post('BPVID');
    $this->db->select('Udt_AU_ApproveVetting.*,CONVERT(VARCHAR(10),VettingRatingDate,105) as fVettingRatingDate,CONVERT(VARCHAR(10),VettingApproveDate,105) as fVettingApproveDate');
    $this->db->from('Udt_AU_ApproveVetting');
    $this->db->where('Udt_AU_ApproveVetting.BPVID', $BPVID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getChangesByBpvid()
{
    $BPVID=$this->input->post('BPVID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixtureNoteByTID()
{
    $TID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('Status');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $TID);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->order_by('FixtureID', 'DESC');
    $query=$this->db->get();
    return $query->row()->Status;
}
    
public function getVettingTID()
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 1);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $BPVID=$query->row()->BPVID;
    $this->db->select('*,CONVERT(VARCHAR(10),VettingRatingDate,105) as fVettingRatingDate,CONVERT(VARCHAR(10),VettingApproveDate,105) as fVettingApproveDate');
    $this->db->from('Udt_AU_ApproveVetting');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getBusinessVettingBPTID()
{
    $AuctionID=$this->input->post('AuctionID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('Udt_AU_BusinessVettingApproval.*');
    $this->db->from('Udt_AU_BusinessVettingApproval');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('Udt_AU_BusinessVettingApproval.BPVID', $BPVID);
    $query=$this->db->get();
    return $query->row();
}
    
public function saveBusinessVettingSpprove()
{
    extract($this->input->post());
    $this->db->trans_start();
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    $row=$query->row();
        
    $ResponseID=$row->TID;
    $AuctionID=$row->MasterID;
        
    $data=array(
     'BPID'=>$row->BPID,
     'RecordOwner'=>$row->RecordOwner,
     'MasterID'=>$row->MasterID,
     'TID'=>$row->TID,
     'name_of_process'=>$row->name_of_process,
     'process_applies'=>$row->process_applies,
     'process_flow_sequence'=>$row->process_flow_sequence,
     'putting_freight_quote'=>$row->putting_freight_quote,
     'submitting_freight_quote'=>$row->submitting_freight_quote,
     'fixture_not_finalization'=>$row->fixture_not_finalization,
     'charter_party_finalization'=>$row->charter_party_finalization,
     'finalization_completed_by'=>$row->finalization_completed_by,
     'message_text'=>$row->message_text,
     'show_in_process'=>$row->show_in_process,
     'show_in_fixture'=>$row->show_in_fixture,
     'show_in_charter_party'=>$row->show_in_charter_party,
     'validity'=>$row->validity,
     'date_from'=>$row->date_from,
     'date_to'=>$row->date_to,
     'status'=>$row->status,
     'comments'=>$row->comments,
     'UserID'=>$UserID,
     'ApproveStatus'=>$AppBusVet,
     'ApprovedBy'=>$BusVetAppBy,
     'UserDate'=>date('Y-m-d H:i:s'),
     'Version'=>($row->Version+0.1),
     'on_subject_status'=>$row->on_subject_status,
     'lift_subject_status'=>$row->lift_subject_status
     );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
            
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('UserID', $UserID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $last_id=$query->row()->BPVID;
            
    $this->db->select('*');
    $this->db->from('Udt_AU_BusinessVettingApproval');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    $rlst=$query->row();
            
    $html='';
    if(count($rlst)>0) {
        if($rlst->ApproveStatus!=$AppBusVet) {
            $oldasts='';
            $newasts='';
            if($rlst->ApproveStatus==1) {
                $oldasts='Yes';
            } else {
                $oldasts='No';
            }
        
            if($AppBusVet==1) {
                $newasts='Yes';
            } else {
                $newasts='No';
            }
            $html .='<br>Old Approve business vetting : '.$oldasts.' <span class="diff">||</span> New Approve business vetting : '.$newasts;
        }
        if($rlst->ApprovedBy!=$BusVetAppBy) {
            $html .='<br>Old Business vetting approve by : '.$rlst->ApprovedBy.' <span class="diff">||</span> New Business vetting approve by : '.$BusVetAppBy;
        }
        if($rlst->Comments!=$BusComment) {
            $html .='<br>Old Comment : '.$rlst->Comments.' <span class="diff">||</span> New Comment : '.$BusComment;
        }
        $html1=trim($html, "<br>");
        $htmldata=array('ViewChage'=>$html1);
        $this->db->where('BPVID', $last_id);
        $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
    } else  {
        if($AppBusVet==1) {
            $newasts='Yes';
        } else {
            $newasts='No';
        }
        $html .='<br>Old Approve business vetting :  <span class="diff">||</span> New Approve business vetting : '.$newasts;
        if($BusVetAppBy) {
            $html .='<br>Old Business vetting approve by :  <span class="diff">||</span> New Business vetting approve by : '.$BusVetAppBy;
        }
        if($BusComment) {
            $html .='<br>Old Comment :  <span class="diff">||</span> New Comment : '.$BusComment;
        }
            $html1=trim($html, "<br>");
            $htmldata=array('ViewChage'=>$html1);
            $this->db->where('BPVID', $last_id);
            $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
    }
        
    $data=array(
    'BPVID'=>$last_id,
    'TID'=>$TID,
    'RecordOwner'=>$EntityID,
    'AuctionID'=>$AuctionID,
    'UserID'=>$UserID,
    'ApproveStatus'=>$AppBusVet,
    'ApprovedBy'=>$BusVetAppBy,
    'Comments'=>$BusComment,
    'UserDate'=>date('Y-m-d H:i:s')
    );
    $ret=$this->db->insert('Udt_AU_BusinessVettingApproval', $data);
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$last_id,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++) {
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
            $ext=getExtension($document['name'][$i]);
            //$ext=strtoupper($ext);
            if($ext=='pdf' || $ext=='PDF') {
                  $nar=explode(".", $document['type'][$i]);
                  $type=end($nar);
                  $file=rand(1, 999999).'_____'.$document['name'][$i];
                  $tmp=$document['tmp_name'][$i];
                  $filesize=$document['size'][$i];
                    
                  $actual_image_name = 'TopMarx/'.$file;
                    
                  $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                  $file_data = array(
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$last_id,
                   'name_of_process'=>2,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                    
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
        
    if($AppBusVet==1) {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $EntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '27');
        $query12=$this->db->get();
        $msgData=$query12->result();
        $msgDetails='';
        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Business vetting approve',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
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
    $this->db->trans_complete();
    return $ret;
}
    
public function checkBusinessApproval()
{
    $ResponseID=$this->input->post('ResponseID');
    $AuctionID=$this->input->post('AuctionID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('MasterID', $AuctionID);
    $this->db->where('TID', $ResponseID);
    $this->db->where('Version', '1.0');
    $this->db->order_by('process_flow_sequence', 'ASC');
    $query=$this->db->get();
    $result=$query->result();
    $data['flg']=0;
    $data['msg']='';
        
    $data['process']='';
    foreach($result as $row){
        if($row->name_of_process==1 || $row->name_of_process==2 || $row->name_of_process==9 || $row->name_of_process==3 || $row->name_of_process==4 || $row->name_of_process==10 ) {
            if($row->fixture_not_finalization==1) {
                $this->db->select('*');
                $this->db->from('udt_AU_BusinessProcessVersionWise');
                $this->db->where('TID', $ResponseID);
                $this->db->where('name_of_process', $row->name_of_process);
                $this->db->order_by('BPVID', 'DESC');
                $query=$this->db->get();
                $rslt1=$query->row();
                if($rslt1->on_subject_status==2) {
                        
                } else {
                    if($rslt1->ApproveStatus !=1 && $rslt1->show_in_fixture==1) {
                        $data['flg']=1;
                        $data['msg']=$rslt1->message_text;
                        $data['process'] .=$row->name_of_process.', ';
                        return $data;
                    } else if($rslt1->ApproveStatus!=1) {
                          $data['flg']=1;
                          $data['msg']=0;
                          $data['process'] .=$row->name_of_process.', ';
                    }
                }
            }
        }    
    }
    return $data;
}
    
public function getSignDocumentData()
{
    $TID=$this->input->get('TID');
    $MasterID=$this->input->get('AuctionID');
        
    $this->db->select('Udt_AU_SinedDocument.*, Owner.EntityName as OwnerName, SpOwner.EntityName as SpOwnerName');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->join('udt_EntityMaster as Owner', 'Owner.ID=Udt_AU_SinedDocument.RecordOwner', 'Left');
    $this->db->join('udt_EntityMaster as SpOwner', 'SpOwner.ID=Udt_AU_SinedDocument.ShipOwner', 'Left');
        
    $this->db->where('Udt_AU_SinedDocument.MasterID', $MasterID);
    $this->db->where('Udt_AU_SinedDocument.TID', $TID);
    $this->db->order_by('Udt_AU_SinedDocument.snid', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function checkLiftBusinessProcess()
{
    $auctionID=$this->input->post('auctionID');
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('udt_AUM_BusinessProcess.name_of_process', 9);
    $query2=$this->db->get();
    $result2=$query2->row();
    $msg='';
    if($result2->Status==1) {
        return 1;
    } else {
        return 0;
    }
}
    
public function getSubjectNotifiedByBPVID()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('*');
    $this->db->from('udt_AU_ChartererSubjects');
    $this->db->where('BPVID', $BPVID);
    $query=$this->db->get();
    return $query->row();
        
}

public function saveCpSubjects()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_ChartererSubjects');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CH_Sub_id', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
    $version=$row->Version+0.1;
    $RecordOwner=$row->RecordOwner;
    
    $data=array(
    'BPID'=>$row->BPID,
    'RecordOwner'=>$row->RecordOwner,
    'MasterID'=>$row->MasterID,
    'TID'=>$row->TID,
    'name_of_process'=>$row->name_of_process,
    'process_applies'=>$row->process_applies,
    'process_flow_sequence'=>$row->process_flow_sequence,
    'putting_freight_quote'=>$row->putting_freight_quote,
    'submitting_freight_quote'=>$row->submitting_freight_quote,
    'fixture_not_finalization'=>$row->fixture_not_finalization,
    'charter_party_finalization'=>$row->charter_party_finalization,
    'finalization_completed_by'=>$row->finalization_completed_by,
    'message_text'=>$row->message_text,
    'show_in_process'=>$row->show_in_process,
    'show_in_fixture'=>$row->show_in_fixture,
    'show_in_charter_party'=>$row->show_in_charter_party,
    'validity'=>$row->validity,
    'date_from'=>$row->date_from,
    'date_to'=>$row->date_to,
    'status'=>$row->status,
    'comments'=>$row->comments,
    'UserID'=>$UserID,
    'ApproveStatus'=>$row->ApproveStatus,
    'ApprovedBy'=>$row->ApprovedBy,
    'UserDate'=>date('Y-m-d H:i:s'),
    'Version'=>($row->Version+0.1),
    'on_subject_status'=>$row->on_subject_status,
    'lift_subject_status'=>$row->lift_subject_status
    );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
        
    $New_BPVID=$row->BPVID;
    $InviteeUsers='';
        
    $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query1=$this->db->get();
    $row1=$query1->row();
        
    if($task_id==1 || ($task_id==2 && $reconfirm_lift==1) || $task_id==3) {
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        //$this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID',$UserID);
        if($task_id==1) {
            $Task_name='CP notified subject';
            $subject_text=$SubjectText;
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '20');
        } else if($task_id==2 && $reconfirm_lift==1) {
            $Task_name='CP subject lifted';
            $subject_text=$lifted_comment;
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '21');
        } else if($task_id==3) {
            $Task_name='CP no subjects';
            $subject_text='Charter party has no subjects to lift.';
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '30');
        }
            
            $query12=$this->db->get();
            $msgData=$query12->result();
            
            $msgDetails='<br>'.$Task_name.' <br>'.$subject_text; 
            
        foreach($msgData as $md){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'C/P on subjects (charterer)',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$md->MessageID,    
            'UserID'=>$UserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
    }
        
        
    for($i=0; $i<count($invUsers);$i++){
        if($task_id==1 || ($task_id==2 && $reconfirm_lift==1) || $task_id==3) {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
            $this->db->from('udt_AUM_MESSAGE_MASTER');
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $invUsers[$i]);
            if($task_id==1) {
                $Task_name='CP notified subject';
                $subject_text=$SubjectText;
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '20');
            } else if($task_id==2 && $reconfirm_lift==1) {
                $Task_name='CP subject lifted';
                $subject_text=$lifted_comment;
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '21');
            } else if($task_id==3) {
                $Task_name='CP no subjects';
                $subject_text='Charter party has no subjects to lift.';
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '30');
            }
                
                $query12=$this->db->get();
                $msgData=$query12->row();
                
            if($msgData) {
                $msgDetails='<br>'.$Task_name.' <br>'.$subject_text;
                $msg=array(
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$AuctionID,    
                'ResponseID'=>$ResponseID,    
                'Event'=>'C/P on subjects (charterer)',    
                'Page'=>'Charter Party (+FN)',    
                'Section'=>'Business Process',    
                'subSection'=>'',    
                'StatusFlag'=>'1',    
                'MessageDetail'=>$msgDetails,    
                'MessageMasterID'=>$msgData->MessageID,    
                'UserID'=>$invUsers[$i],    
                'FromUserID'=>$UserID,    
                'UserDate'=>date('Y-m-d H:i:s')    
                );
                $this->db->insert('udt_AU_Messsage_Details', $msg);
                    
                $this->db->where('AuctionID', $AuctionID);
                $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
            }
        }
        $InviteeUsers .=$invUsers[$i].',';
    }
        
    $InviteeUsers=trim($InviteeUsers, ",");
        
    $data1=array(
                'CH_Task'=>$task_id,
                'NotifySubject'=>$SubjectText,
                'GeneralComment'=>$GeneralComment,
                'ConfirmLift'=>$reconfirm_lift,
                'LiftedComment'=>$lifted_comment,
                'InviteeUsers'=>$InviteeUsers,
                'ResponseID'=>$ResponseID,
                'BPVID'=>$New_BPVID,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $ret=$this->db->insert('udt_AU_ChartererSubjects', $data1);
        
    $new_ids=explode(",", $InviteeUsers);
    $newNames='';
    $this->db->select('FirstName,LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where_in('ID', $new_ids);
    $query1=$this->db->get();
    $new_rows=$query1->result();
    foreach($new_rows as $nr){
        $newNames .=$nr->FirstName.' '.$nr->LastName.', ';
    }
    $newNames=trim($newNames, ", ");
        
    if($task_id==1) {
        $newCH_Task='Notify invitee';
    } else if($task_id==2) {
        $newCH_Task='Lift subject';
    } else if($task_id==3) {
        $newCH_Task='No subjects';
    }
    if($reconfirm_lift==1) {
        $newConfirmLift='Yes';
    } else if($reconfirm_lift==2) {
        $newConfirmLift='No';
    }
    if(count($old_row)>0) {
        if($old_row->CH_Task==1) {
            $oldCH_Task='Notify invitee';
        } else if($old_row->CH_Task==2) {
            $oldCH_Task='Lift subject';
        } else if($old_row->CH_Task==3) {
            $oldCH_Task='No subjects';
        }
            
        if($old_row->CH_Task != $task_id) {
            $html .='<br>Old Task : '.$oldCH_Task.' <span class="diff">||</span> New Task : '.$newCH_Task;
        }
            
        if($task_id==1 && ( $old_row->NotifySubject != $SubjectText)) {
            $html .='<br>Old Subject : '.$old_row->NotifySubject.' <span class="diff">||</span> New Subject : '.$SubjectText;
        }
            
        if($task_id==2 && ( $old_row->ConfirmLift != $reconfirm_lift)) {
            if($old_row->ConfirmLift==1) {
                $oldConfirmLift='Yes';
            } else if($old_row->ConfirmLift==2) {
                $oldConfirmLift='No';
            }
            $html .='<br>Old Reconfirm lift subject(s) : '.$oldConfirmLift.' <span class="diff">||</span> New Reconfirm lift subject(s) : '.$newConfirmLift;
        }
            
        if($task_id==2 && ( $old_row->LiftedComment != $lifted_comment)) {
            $html .='<br>Old Comment by charterer for subjects lifted : '.$old_row->LiftedComment.' <span class="diff">||</span> New Comment by charterer for subjects lifted : '.$lifted_comment;
        }
        if($old_row->GeneralComment != $GeneralComment) {
            $html .='<br>Old General comment : '.$old_row->GeneralComment.' <span class="diff">||</span> New General comment : '.$GeneralComment;
        }
        if($old_row->InviteeUsers != $InviteeUsers) {
            $old_ids=explode(",", $old_row->InviteeUsers);
                
            $oldNames='';
            $this->db->select('FirstName,LastName');
            $this->db->from('udt_UserMaster');
            $this->db->where_in('ID', $old_ids);
            $query1=$this->db->get();
            $old_rows=$query1->result();
            foreach($old_rows as $or){
                $oldNames .=$or->FirstName.' '.$or->LastName.', ';
            }
                
            $oldNames=trim($oldNames, " ");
            $oldNames=trim($oldNames, ",");
            $html .='<br>Old '.$oldCH_Task.' users : '.$oldNames.' <span class="diff">||</span> New '.$newCH_Task.' users : '.$newNames;
        }
        
    } else {
        if($task_id) {
            $html .='<br>Old Task :  <span class="diff">||</span> New Task : '.$newCH_Task;
        }
        if($task_id==1 && ($SubjectText !='')) {
            $html .='<br>Old Subject :  <span class="diff">||</span> New Subject : '.$SubjectText;
        }
        if($task_id==2 && ( $reconfirm_lift !='')) {
            $html .='<br>Old Reconfirm lift subject(s) :  <span class="diff">||</span> New Reconfirm lift subject(s) : '.$newConfirmLift;
        }
        if($task_id==2 && ( $lifted_comment !='')) {
            $html .='<br>Old Comment by charterer for subjects lifted :  <span class="diff">||</span> New Comment by charterer for subjects lifted : '.$lifted_comment;
        }
        if($GeneralComment !='') {
            $html .='<br>Old General comment : <span class="diff">||</span> New General comment : '.$GeneralComment;
        }
        if($InviteeUsers) {
            $html .='<br>Old notify invitee users :  <span class="diff">||</span> New '.$newCH_Task.' users : '.$newNames;
        }
    }
    $html1=trim($html, "<br>");
    $htmldata=array('ViewChage'=>$html1);
    if($task_id==2 && $reconfirm_lift==1) {
        $htmldata['ApproveStatus']=1;
        $this->db->select('FirstName,LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('ID', $UserID);
        $queryby=$this->db->get();
        $row_by=$queryby->row();
        $htmldata['ApprovedBy']=$row_by->FirstName.' '.$row_by->LastName;
    } else if($task_id==3) {
        $htmldata['ApproveStatus']=1;
        $this->db->select('FirstName,LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('ID', $UserID);
        $queryby=$this->db->get();
        $row_by=$queryby->row();
        $htmldata['ApprovedBy']=$row_by->FirstName.' '.$row_by->LastName;
    } else {
        $htmldata['ApproveStatus']=0;
        $htmldata['ApprovedBy']='';
    }
    $this->db->where('BPVID', $New_BPVID);
    $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$New_BPVID,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++) {
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
            $ext=getExtension($document['name'][$i]);
            //$ext=strtoupper($ext);
            if($ext=='pdf' || $ext=='PDF') {
                  $nar=explode(".", $document['type'][$i]);
                  $type=end($nar);
                  $file=rand(1, 999999).'_____'.$document['name'][$i];
                  $tmp=$document['tmp_name'][$i];
                  $filesize=$document['size'][$i];
                    
                  $actual_image_name = 'TopMarx/'.$file;
                    
                  $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                  $file_data = array(
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$New_BPVID,
                   'name_of_process'=>9,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                    
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
    return $ret;
        
}


public function checkCPSubjectLifted()
{
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AU_ChartererSubjects');
    $this->db->where('ResponseID', $TID);
    $this->db->order_by('CH_Sub_id', 'DESC');
    $query=$this->db->get();
    return $query->row();
}    
    
public function getDocumentCpVersion()
{
    $ResponseID=$this->input->post('ResponseID');
    $MasterID=$this->input->post('AuctionID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('AuctionID', $MasterID);
    $this->db->order_by('DocumentationID', 'desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function assignOtpToDigitalSignature()
{
    $sigid=$this->input->post('sigid');
    $CheckOwner=$this->input->post('CheckOwner');
    $globleotp=$this->input->post('globleotp');
    $HashCode=$this->input->post('HashCode');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('Udt_AU_UserBlockchainRecord');
    $this->db->where('UID', $UserID);
    $query=$this->db->get();
    $urslt=$query->row();
    $privKey=$urslt->PrivKey;
    $PubKey=$urslt->PubKey;
        
    $data = array("hash"=>$HashCode,'privKey'=>$privKey); 
        
    $data_string = json_encode($data); 
    $url=BLOCK_CHAIN_URL.'signHash/';
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
    $srv=json_decode($result);
        
    if($CheckOwner==1) {
        $data=array('StatusCharterer'=>1,'ChartererOtp'=>$globleotp,'LastUpdated'=>date('Y-m-d H:i:s'),'ChartererHash'=>$HashCode);
    } else {
        $data=array('StatusShipowner'=>1,'ShipOwnerOtp'=>$globleotp,'LastUpdated'=>date('Y-m-d H:i:s'),'ShipHash'=>$HashCode);
    }
        
    $this->db->where('snid', $sigid);
    $this->db->update('Udt_AU_SinedDocument', $data);
    $DigitalSignature=$srv->S.$srv->R.$srv->V;
    $data=array('PubKey'=>$PubKey,'DigitalSignature'=>$DigitalSignature,'sds'=>$srv->S,'sdr'=>$srv->R,'sdv'=>$srv->V);
    return $data;
}
    
public function saveSignatureDetails()
{
    $SignatureUserID=$this->input->post('SignatureUserID');
    $SignatureUserName=$this->input->post('SignatureUserName');
    $SignatureUserCompany=$this->input->post('SignatureUserCompany');
    $SignatureUserDesignation=$this->input->post('SignatureUserDesignation');
    $SignatureUserEmail=$this->input->post('SignatureUserEmail');
    $SignaturePublicKey=$this->input->post('SignaturePublicKey');
        
    $SignatureDigitalSignature=$this->input->post('SignatureDigitalSignature');
    $SignatureCharterPartyHash=$this->input->post('SignatureCharterPartyHash');
    $sigid=$this->input->post('sigid');
    $UserID=$this->input->post('UserID');
    $CheckOwner=$this->input->post('CheckOwner');
    $AuctionID=$this->input->post('AuctionID');
    $TID=$this->input->post('TID');
    $SignType=$this->input->post('SignType');
    $sds=$this->input->post('sds');
    $sdr=$this->input->post('sdr');
    $sdv=$this->input->post('sdv');
        
    $data=array(
    'sigid'=>$sigid,
    'CheckOwner'=>$CheckOwner,
    'SignatureUserID'=>$SignatureUserID,
    'SignatureUserName'=>$SignatureUserName,
    'SignatureUserCompany'=>$SignatureUserCompany,
    'SignatureUserDesignation'=>$SignatureUserDesignation,
    'SignatureUserEmail'=>$SignatureUserEmail,
    'SignaturePublicKey'=>$SignaturePublicKey,
    'SignatureDigitalSignature'=>$SignatureDigitalSignature,
    'SignatureCharterPartyHash'=>$SignatureCharterPartyHash,
    'UserID'=>$UserID,
    'sds'=>$sds,
    'sdr'=>$sdr,
    'sdv'=>$sdv,
    'UserDate'=>date('Y-m-d H:i:s')
    );
                    
    $ret=$this->db->insert('Udt_AU_SignatureDetails', $data);
        
    $this->db->select('*');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->where('snid', $sigid);
    $Sqry=$this->db->get();
    $Srow=$Sqry->row();
        
    $RecordOwner=$Srow->RecordOwner;
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseBrokerUsers');
    $this->db->where('ResponseID', $TID);
    $this->db->where('BrokerSigningType', $SignType);
    $this->db->where('Status', 1);
    $qry=$this->db->get();
    $resBrokerResult=$qry->result();
        
    foreach($resBrokerResult as $res){
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $res->SigningUserEntity);
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        if($SignType==1) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '28');
            $Event='Sign fixture note';
        } else if($SignType==2) {
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '29');
            $Event='Sign charter party document';
        }
            
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $res->SigningUserID);
        $query1=$this->db->get();
        $msgRecord=$query1->row();
            
        if($msgRecord) {
            $msgDetails='';
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$TID,    
            'Event'=>$Event,    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Sign Documentation',    
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
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    if($SignType==1) {
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '28');
        $Event='Sign fixture note';
    } else if($SignType==2) {
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '29');
        $Event='Sign charter party document';
    }
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
    $Rquery1=$this->db->get();
    $msgOwner=$Rquery1->result();
        
    foreach($msgOwner as $mr){
        $msgDetails='';
        $msg=array(
        'CoCode'=>C_COCODE,    
        'AuctionID'=>$AuctionID,    
        'ResponseID'=>$TID,    
        'Event'=>$Event,    
        'Page'=>'Charter Party (+FN)',    
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
        
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        
    return $ret;
}
    
public function getSignatureDetailById()
{
    $snid=$this->input->post('snid');
    $CheckOwner=$this->input->post('CheckOwner');
    $where='';
    if($CheckOwner==1) {
        $where="sigid=$snid and CheckOwner=1";
    } else {
        $where="sigid=$snid and ( CheckOwner=0 or CheckOwner=2 )";
    }
    $this->db->select('Udt_AU_SignatureDetails.*,Udt_AU_SinedDocument.DocumentType');
    $this->db->from('Udt_AU_SignatureDetails');
    $this->db->join('Udt_AU_SinedDocument', 'Udt_AU_SinedDocument.snid=Udt_AU_SignatureDetails.sigid');
    $this->db->where($where);
    $this->db->order_by('sdid', 'desc');
    $query=$this->db->get();
    return $query->row();
}
public function getSignatureDetailByIdVerifyHash()
{
    $snid=$this->input->post('snid');
    $CheckOwner=$this->input->post('CheckOwner');
        
    $this->db->select('Udt_AU_SignatureDetails.*,Udt_AU_SinedDocument.DocumentType, Udt_AU_SinedDocument.ShipOwner,Udt_AU_SinedDocument.TID, udt_AUM_Freight.EntityID');
    $this->db->from('Udt_AU_SignatureDetails');
    $this->db->join('Udt_AU_SinedDocument', 'Udt_AU_SinedDocument.snid=Udt_AU_SignatureDetails.sigid');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=Udt_AU_SinedDocument.TID');
    $this->db->where('sigid', $snid);
    $this->db->order_by('sigid', 'desc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getFixtureAuctionID()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    $this->db->select('udt_AU_AuctionFixture.*,udt_AUM_Freight.UserName,udt_AUM_Freight.UserID1,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.LoginID,EM.EntityName as EntityName,IEM.EntityName as EntityName1');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionFixture.ResponseID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->join('udt_EntityMaster as EM', 'EM.ID=udt_UserMaster.EntityID', 'Left');
    $this->db->join('udt_EntityMaster as IEM', 'IEM.ID=udt_AUM_Freight.EntityID', 'Left');
    $this->db->where('udt_AU_AuctionFixture.AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_AuctionFixture.FixtureID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixtureNoteByAuctionID()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(FixtureNote, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('udt_AU_AuctionFixture.AuctionID', $AuctionID);
        $this->db->order_by('udt_AU_AuctionFixture.FixtureID', 'DESC');
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
    
    
    
public function getDocumentationHTMLByAuctionID()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    $this->db->select('udt_AU_AuctionMainDocumentation.*,udt_AUM_Freight.UserName,udt_AUM_Freight.UserID1,udt_UserMaster.FirstName,udt_UserMaster.LastName,udt_UserMaster.LoginID');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->join('udt_AUM_Freight', 'udt_AUM_Freight.ResponseID=udt_AU_AuctionMainDocumentation.ResponseID', 'Left');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'Left');
    $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
    $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getDocumentationNoteAuctionID()
{
    if($this->input->post()) {
        $AuctionID=$this->input->post('AuctionID');
    }
    if($this->input->get()) {
        $AuctionID=$this->input->get('AuctionID');
    }
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(DocumentationNote, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('udt_AU_AuctionMainDocumentation.AuctionID', $AuctionID);
        $this->db->order_by('udt_AU_AuctionMainDocumentation.DocumentationID', 'DESC');
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
    
    
public function getDocumentationClausesByID($DocumentationID)
{
        
    $this->db->select('*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('DocumentationID', $DocumentationID);
    $this->db->order_by('Clause', 'ASC');
    $query=$this->db->get();
    $result= $query->result();
    $i=0;
        
    foreach($result as $row){
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('AuctionMainClauseID', $row->AuctionMainClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content[$i] .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
        $i++;
    }
    return $content;
}
    
public function getUserDetailsByID()
{
    $UserID=$this->input->get('UserID');
    $this->db->select('SignFixtureFinalFlg,SignCPFinalFlg');
    $this->db->from('Udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query=$this->db->get();
    return $query->row();
}
    
public function checkSubjectMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 9);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function getCounterPartyRiskMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 3);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function saveCounterPartyRisk()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_CounterPartyRiskAssessment');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CP_RiskID', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 3);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
        
    $AuctionID=$row->MasterID;
    
    $data=array(
                'BPID'=>$row->BPID,
                'RecordOwner'=>$row->RecordOwner,
                'MasterID'=>$row->MasterID,
                'TID'=>$row->TID,
                'name_of_process'=>$row->name_of_process,
                'process_applies'=>$row->process_applies,
                'process_flow_sequence'=>$row->process_flow_sequence,
                'putting_freight_quote'=>$row->putting_freight_quote,
                'submitting_freight_quote'=>$row->submitting_freight_quote,
                'fixture_not_finalization'=>$row->fixture_not_finalization,
                'charter_party_finalization'=>$row->charter_party_finalization,
                'finalization_completed_by'=>$row->finalization_completed_by,
                'message_text'=>$row->message_text,
                'show_in_process'=>$row->show_in_process,
                'show_in_fixture'=>$row->show_in_fixture,
                'show_in_charter_party'=>$row->show_in_charter_party,
                'validity'=>$row->validity,
                'date_from'=>$row->date_from,
                'date_to'=>$row->date_to,
                'status'=>$row->status,
                'comments'=>$row->comments,
                'UserID'=>$UserID,
                'ApproveStatus'=>$row->ApproveStatus,
                'ApprovedBy'=>$row->ApprovedBy,
                'UserDate'=>date('Y-m-d H:i:s'),
                'Version'=>($row->Version+0.1),
                'on_subject_status'=>$row->on_subject_status,
                'lift_subject_status'=>$row->lift_subject_status
                );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 3);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query1=$this->db->get();
    $row1=$query1->row();
    $New_BPVID=$row1->BPVID;
        
    $cp_data=array(
    'ResponseID'=>$ResponseID,
    'BPVID'=>$New_BPVID,
    'RiskReviewFlg'=>$RiskReviewFlg,
    'DiscussionCheckFlg'=>$DiscussionCheckFlg,
    'ApprovedFlg'=>$ApprovedFlg,
    'ReconfirmApprovedFlg'=>$ReconfirmApprovedFlg,
    'Comment'=>$Comment,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $this->db->insert('udt_AU_CounterPartyRiskAssessment', $cp_data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_CounterPartyRiskAssessment');
    $this->db->where('BPVID', $New_BPVID);
    $query1=$this->db->get();
    $row1=$query1->row();
    $CP_RiskID=$row1->CP_RiskID;
        
    if(count($old_row)>0) {
        if($old_row->RiskReviewFlg != $RiskReviewFlg) {
            if($old_row->RiskReviewFlg==1) {
                $oldRiskReviewFlg='Yes';
            } else if($old_row->RiskReviewFlg==2) {
                $oldRiskReviewFlg='No';
            }
            if($RiskReviewFlg==1) {
                $newRiskReviewFlg='Yes';
            } else if($RiskReviewFlg==2) {
                $newRiskReviewFlg='No';
            }
                $html .='<br>Old Counter party risk review report checked : '.$oldRiskReviewFlg.' <span class="diff">||</span> New Compliance risk review report checked : '.$newRiskReviewFlg;
        }
        if($old_row->DiscussionCheckFlg != $DiscussionCheckFlg) {
            if($old_row->DiscussionCheckFlg==1) {
                  $oldDiscussionCheckFlg='Yes';
            } else if($old_row->DiscussionCheckFlg==2) {
                $oldDiscussionCheckFlg='No';
            }
            if($DiscussionCheckFlg==1) {
                $newDiscussionCheckFlg='Yes';
            } else if($DiscussionCheckFlg==2) {
                $newDiscussionCheckFlg='No';
            }
            $html .='<br>Old Telephone / Email / Verbal discussions checked : '.$oldDiscussionCheckFlg.' <span class="diff">||</span> New Telephone / Email / Verbal discussions checked : '.$newDiscussionCheckFlg;
        }
        if($old_row->ApprovedFlg != $ApprovedFlg) {
            if($old_row->ApprovedFlg==1) {
                $oldApprovedFlg='Yes';
            } else if($old_row->ApprovedFlg==2) {
                $oldApprovedFlg='No';
            }
            if($ApprovedFlg==1) {
                $newApprovedFlg='Yes';
            } else if($ApprovedFlg==2) {
                $newApprovedFlg='No';
            }
            $html .='<br>Old Approved status : '.$oldApprovedFlg.' <span class="diff">||</span> New Approved status : '.$newApprovedFlg;
        }
        if($old_row->ReconfirmApprovedFlg != $ReconfirmApprovedFlg) {
            if($old_row->ReconfirmApprovedFlg==1) {
                $oldReconfirmApprovedFlg='Yes';
            } else if($old_row->ReconfirmApprovedFlg==2) {
                $oldReconfirmApprovedFlg='No';
            }
            if($ReconfirmApprovedFlg==1) {
                $newReconfirmApprovedFlg='Yes';
            } else if($ReconfirmApprovedFlg==2) {
                $newReconfirmApprovedFlg='No';
            }
            $html .='<br>Old Reconfirm approved status : '.$oldReconfirmApprovedFlg.' <span class="diff">||</span> New Reconfirm approved status : '.$newReconfirmApprovedFlg;
        }
        if($old_row->Comment != $Comment) {
            $html .='<br>Old Reconfirm approved status : '.$old_row->Comment.' <span class="diff">||</span> New Reconfirm approved status : '.$Comment;
        }
    } else {
        if($RiskReviewFlg==1) {
            $newRiskReviewFlg='Yes';
        } else if($RiskReviewFlg==2) {
            $newRiskReviewFlg='No';
        }
        if($RiskReviewFlg) {
            $html .='<br>Old Counter party risk review report checked :  <span class="diff">||</span> New Compliance risk review report checked : '.$newRiskReviewFlg;
        }
        if($DiscussionCheckFlg==1) {
            $newDiscussionCheckFlg='Yes';
        } else if($DiscussionCheckFlg==2) {
            $newDiscussionCheckFlg='No';
        }
        if($DiscussionCheckFlg) {
                $html .='<br>Old Telephone / Email / Verbal discussions checked :  <span class="diff">||</span> New Telephone / Email / Verbal discussions checked : '.$newDiscussionCheckFlg;
        }
        if($ApprovedFlg==1) {
                $newApprovedFlg='Yes';
        } else if($ApprovedFlg==2) {
                $newApprovedFlg='No';
        }
        if($ApprovedFlg) {
            $html .='<br>Old Approved status :  <span class="diff">||</span> New Approved status : '.$newApprovedFlg;
        }
        if($ReconfirmApprovedFlg==1) {
            $newReconfirmApprovedFlg='Yes';
        } else if($ReconfirmApprovedFlg==2) {
            $newReconfirmApprovedFlg='No';
        }
        if($ReconfirmApprovedFlg) {
            $html .='<br>Old Reconfirm approved status :  <span class="diff">||</span> New Reconfirm approved status : '.$newReconfirmApprovedFlg;
        }
        if($Comment !='') {
            $html .='<br>Old Comment : <span class="diff">||</span> New Comment : '.$Comment;
        }
    }
    $html1=trim($html, "<br>");
    $htmldata=array('ViewChage'=>$html1);
        
    if($ApprovedFlg==1 && $ReconfirmApprovedFlg==1) {
        $htmldata['ApproveStatus']=1;
        $this->db->select('FirstName,LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('ID', $UserID);
        $queryby=$this->db->get();
        $row_by=$queryby->row();
        $htmldata['ApprovedBy']=$row_by->FirstName.' '.$row_by->LastName;
    } else {
        $htmldata['ApproveStatus']=0;
        $htmldata['ApprovedBy']='';
    }
    $this->db->where('BPVID', $New_BPVID);
    $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$New_BPVID,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++){
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
            $ext=getExtension($document['name'][$i]);
            //$ext=strtoupper($ext);
            if($ext=='pdf' || $ext=='PDF') {
                  $nar=explode(".", $document['type'][$i]);
                  $type=end($nar);
                  $file=rand(1, 999999).'_____'.$document['name'][$i];
                  $tmp=$document['tmp_name'][$i];
                  $filesize=$document['size'][$i];
                    
                  $actual_image_name = 'TopMarx/'.$file;
                    
                  $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                  $file_data = array(
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$New_BPVID,
                   'name_of_process'=>3,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
        
    if($ApprovedFlg==1 && $ReconfirmApprovedFlg==1) {
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $AuctionID);
        $auction_fright=$this->db->get();
        $auctionrow=$auction_fright->row();
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
        $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $auctionrow->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '25');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $auctionrow->UserID);
        $query1=$this->db->get();
        $msgRecord=$query1->result();
            
        $msgDetails='<br>Counter party approved on : '.date('d-m-Y');
        foreach($msgRecord as $mr){
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Counter party approve',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
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
            
        $ownerarr[] =$auctionrow->UserID;
            
        $this->db->select('udt_AU_BusinessProcessAuctionWise.*,udt_AUM_BusinessProcess.name_of_process');
        $this->db->from('udt_AU_BusinessProcessAuctionWise');
        $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
        $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
        $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 3);
        $bus_query=$this->db->get();
        $bus=$bus_query->row();
            
        if($bus) {
            $busUserIds=explode(",", $bus->UserList);
            for($i=0;$i<count($busUserIds); $i++ ) {
                if(!in_array($busUserIds[$i], $ownerarr)) {
                    $ownerarr[] =$busUserIds[$i];
                    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
                    $this->db->from('udt_AUM_MESSAGE_MASTER');
                    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
                    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '25');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $busUserIds[$i]);
                    $query1=$this->db->get();
                    $msgRecord=$query1->row();
                        
                    if($msgRecord) {
                               $msgDetails='<br>Counter party approve on : '.date('d-m-Y'); 
                               $msg=array(
                         'CoCode'=>C_COCODE,    
                         'AuctionID'=>$AuctionID,    
                         'ResponseID'=>$ResponseID,    
                         'Event'=>'Counter party approve',    
                         'Page'=>'Charter Party (+FN)',    
                         'Section'=>'Business Process',    
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
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $CP_RiskID;
}
    
public function getCounterPartyRiskContent()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('udt_AU_CounterPartyRiskAssessment.*, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_UserMaster.LoginID');
    $this->db->from('udt_AU_CounterPartyRiskAssessment');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_CounterPartyRiskAssessment.UserID');
    $this->db->where('BPVID', $BPVID);
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
        
public function getCounterPartyRiskDocuments()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('udt_AU_BP_ChartererDocs.*');
    $this->db->from('udt_AU_BP_ChartererDocs');
    $this->db->where('BPVID', $BPVID);
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function get_cp_document_file()
{
    $Doc_ID=$this->input->get('id');
    $this->db->select('udt_AU_BP_ChartererDocs.*');
    $this->db->from('udt_AU_BP_ChartererDocs');
    $this->db->where('Doc_ID', $Doc_ID);
    $query=$this->db->get();
    return $query->row()->FileName;
}
    
public function delete_counter_party_document($id)
{
    $this->db->where('Doc_ID', $id);
    return $this->db->delete('udt_AU_BP_ChartererDocs');
}
    
public function getComplianceRiskMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 4);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function getBusinessVettingMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 2);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function getTechnicalVettingMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 1);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function getComplianceRiskContent()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('udt_AU_ComplianceRiskAssessment.*, udt_UserMaster.FirstName, udt_UserMaster.LastName, udt_UserMaster.LoginID');
    $this->db->from('udt_AU_ComplianceRiskAssessment');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_ComplianceRiskAssessment.UserID');
    $this->db->where('BPVID', $BPVID);
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row();
}
        
public function getComplianceRiskDocuments()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    $this->db->select('udt_AU_BP_ChartererDocs.*');
    $this->db->from('udt_AU_BP_ChartererDocs');
    $this->db->where('BPVID', $BPVID);
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function saveComplianceRisk()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_ComplianceRiskAssessment');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CPL_RiskID', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 4);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
    $AuctionID=$row->MasterID;
    $data=array(
                'BPID'=>$row->BPID,
                'RecordOwner'=>$row->RecordOwner,
                'MasterID'=>$row->MasterID,
                'TID'=>$row->TID,
                'name_of_process'=>$row->name_of_process,
                'process_applies'=>$row->process_applies,
                'process_flow_sequence'=>$row->process_flow_sequence,
                'putting_freight_quote'=>$row->putting_freight_quote,
                'submitting_freight_quote'=>$row->submitting_freight_quote,
                'fixture_not_finalization'=>$row->fixture_not_finalization,
                'charter_party_finalization'=>$row->charter_party_finalization,
                'finalization_completed_by'=>$row->finalization_completed_by,
                'message_text'=>$row->message_text,
                'show_in_process'=>$row->show_in_process,
                'show_in_fixture'=>$row->show_in_fixture,
                'show_in_charter_party'=>$row->show_in_charter_party,
                'validity'=>$row->validity,
                'date_from'=>$row->date_from,
                'date_to'=>$row->date_to,
                'status'=>$row->status,
                'comments'=>$row->comments,
                'UserID'=>$UserID,
                'ApproveStatus'=>$row->ApproveStatus,
                'ApprovedBy'=>$row->ApprovedBy,
                'UserDate'=>date('Y-m-d H:i:s'),
                'Version'=>($row->Version+0.1),
                'on_subject_status'=>$row->on_subject_status,
                'lift_subject_status'=>$row->lift_subject_status
                );
                
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 4);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query1=$this->db->get();
    $row1=$query1->row();
    $New_BPVID=$row1->BPVID;
        
    $cp_data=array(
    'ResponseID'=>$ResponseID,
    'BPVID'=>$New_BPVID,
    'RiskReviewFlg'=>$RiskReviewFlg,
    'DiscussionCheckFlg'=>$DiscussionCheckFlg,
    'ApprovedFlg'=>$ApprovedFlg,
    'ReconfirmApprovedFlg'=>$ReconfirmApprovedFlg,
    'Comment'=>$Comment,
    'UserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $this->db->insert('udt_AU_ComplianceRiskAssessment', $cp_data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_ComplianceRiskAssessment');
    $this->db->where('BPVID', $New_BPVID);
    $query1=$this->db->get();
    $row1=$query1->row();
    $CPL_RiskID=$row1->CPL_RiskID;
        
    if(count($old_row)>0) {
        if($old_row->RiskReviewFlg != $RiskReviewFlg) {
            if($old_row->RiskReviewFlg==1) {
                $oldRiskReviewFlg='Yes';
            } else if($old_row->RiskReviewFlg==2) {
                $oldRiskReviewFlg='No';
            }
            if($RiskReviewFlg==1) {
                $newRiskReviewFlg='Yes';
            } else if($RiskReviewFlg==2) {
                $newRiskReviewFlg='No';
            }
                $html .='<br>Old Compliance risk review report checked : '.$oldRiskReviewFlg.' <span class="diff">||</span> New Compliance risk review report checked : '.$newRiskReviewFlg;
        }
        if($old_row->DiscussionCheckFlg != $DiscussionCheckFlg) {
            if($old_row->DiscussionCheckFlg==1) {
                  $oldDiscussionCheckFlg='Yes';
            } else if($old_row->DiscussionCheckFlg==2) {
                $oldDiscussionCheckFlg='No';
            }
            if($DiscussionCheckFlg==1) {
                $newDiscussionCheckFlg='Yes';
            } else if($DiscussionCheckFlg==2) {
                $newDiscussionCheckFlg='No';
            }
            $html .='<br>Old Telephone / Email / Verbal discussions checked : '.$oldDiscussionCheckFlg.' <span class="diff">||</span> New Telephone / Email / Verbal discussions checked : '.$newDiscussionCheckFlg;
        }
        if($old_row->ApprovedFlg != $ApprovedFlg) {
            if($old_row->ApprovedFlg==1) {
                $oldApprovedFlg='Yes';
            } else if($old_row->ApprovedFlg==2) {
                $oldApprovedFlg='No';
            }
            if($ApprovedFlg==1) {
                $newApprovedFlg='Yes';
            } else if($ApprovedFlg==2) {
                $newApprovedFlg='No';
            }
            $html .='<br>Old Approved status : '.$oldApprovedFlg.' <span class="diff">||</span> New Approved status : '.$newApprovedFlg;
        }
        if($old_row->ReconfirmApprovedFlg != $ReconfirmApprovedFlg) {
            if($old_row->ReconfirmApprovedFlg==1) {
                $oldReconfirmApprovedFlg='Yes';
            } else if($old_row->ReconfirmApprovedFlg==2) {
                $oldReconfirmApprovedFlg='No';
            }
            if($ReconfirmApprovedFlg==1) {
                $newReconfirmApprovedFlg='Yes';
            } else if($ReconfirmApprovedFlg==2) {
                $newReconfirmApprovedFlg='No';
            }
            $html .='<br>Old Reconfirm approved status : '.$oldReconfirmApprovedFlg.' <span class="diff">||</span> New Reconfirm approved status : '.$newReconfirmApprovedFlg;
        }
        if($old_row->Comment != $Comment) {
            $html .='<br>Old Reconfirm approved status : '.$old_row->Comment.' <span class="diff">||</span> New Reconfirm approved status : '.$Comment;
        }
    } else {
        if($RiskReviewFlg==1) {
            $newRiskReviewFlg='Yes';
        } else if($RiskReviewFlg==2) {
            $newRiskReviewFlg='No';
        }
        if($RiskReviewFlg) {
            $html .='<br>Old Compliance risk review report checked :  <span class="diff">||</span> New Counter party risk review report checked : '.$newRiskReviewFlg;
        }
        if($DiscussionCheckFlg==1) {
            $newDiscussionCheckFlg='Yes';
        } else if($DiscussionCheckFlg==2) {
            $newDiscussionCheckFlg='No';
        }
        if($DiscussionCheckFlg) {
                $html .='<br>Old Telephone / Email / Verbal discussions checked :  <span class="diff">||</span> New Telephone / Email / Verbal discussions checked : '.$newDiscussionCheckFlg;
        }
        if($ApprovedFlg==1) {
                $newApprovedFlg='Yes';
        } else if($ApprovedFlg==2) {
                $newApprovedFlg='No';
        }
        if($ApprovedFlg) {
            $html .='<br>Old Approved status :  <span class="diff">||</span> New Approved status : '.$newApprovedFlg;
        }
        if($ReconfirmApprovedFlg==1) {
            $newReconfirmApprovedFlg='Yes';
        } else if($ReconfirmApprovedFlg==2) {
            $newReconfirmApprovedFlg='No';
        }
        if($ReconfirmApprovedFlg) {
            $html .='<br>Old Reconfirm approved status :  <span class="diff">||</span> New Reconfirm approved status : '.$newReconfirmApprovedFlg;
        }
        if($Comment !='') {
            $html .='<br>Old Comment : <span class="diff">||</span> New Comment : '.$Comment;
        }
    }
        
    $html1=trim($html, "<br>");
    $htmldata=array('ViewChage'=>$html1);
        
    if($ApprovedFlg==1 && $ReconfirmApprovedFlg==1) {
        $htmldata['ApproveStatus']=1;
        $this->db->select('FirstName,LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('ID', $UserID);
        $queryby=$this->db->get();
        $row_by=$queryby->row();
        $htmldata['ApprovedBy']=$row_by->FirstName.' '.$row_by->LastName;
    } else {
        $htmldata['ApproveStatus']=0;
        $htmldata['ApprovedBy']='';
    }
        
    $this->db->where('BPVID', $New_BPVID);
    $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
        
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$New_BPVID,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++){
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
            $ext=getExtension($document['name'][$i]);
            //$ext=strtoupper($ext);
            if($ext=='pdf' || $ext=='PDF') {
                  $nar=explode(".", $document['type'][$i]);
                  $type=end($nar);
                  $file=rand(1, 999999).'_____'.$document['name'][$i];
                  $tmp=$document['tmp_name'][$i];
                  $filesize=$document['size'][$i];
                    
                  $actual_image_name = 'TopMarx/'.$file;
                    
                  $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                  $file_data = array(
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$New_BPVID,
                   'name_of_process'=>4,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
    if($ApprovedFlg==1 && $ReconfirmApprovedFlg==1) {
            
        $this->db->select('*');
        $this->db->from('udt_AU_Auctions');
        $this->db->where('AuctionID', $AuctionID);
        $auction_fright=$this->db->get();
        $auctionrow=$auction_fright->row();
            
            
        $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '26');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $auctionrow->UserID);
        $query1=$this->db->get();
        $msgRecord=$query1->row();
            
        if($msgRecord) {
            $msgDetails='<br>Compliance risk approved on : '.date('d-m-Y'); 
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'Compliance risk approve',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
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
            
        $ownerarr[] =$auctionrow->UserID;
            
        $this->db->select('udt_AU_BusinessProcessAuctionWise.*,udt_AUM_BusinessProcess.name_of_process');
        $this->db->from('udt_AU_BusinessProcessAuctionWise');
        $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
        $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
        $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 4);
        $bus_query=$this->db->get();
        $bus=$bus_query->row();
            
        if($bus) {
            $busUserIds=explode(",", $bus->UserList);
            for($i=0;$i<count($busUserIds); $i++ ) {
                if(!in_array($busUserIds[$i], $ownerarr)) {
                    $ownerarr[] =$busUserIds[$i];
                    $this->db->select('udt_AUM_MESSAGE_MASTER.*,udt_EntityMaster.EntityName');
                    $this->db->from('udt_AUM_MESSAGE_MASTER');
                    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner', 'left');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $auctionrow->OwnerEntityID);
                    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '26');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
                    $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $busUserIds[$i]);
                    $query1=$this->db->get();
                    $msgRecord=$query1->row();
                        
                    if($msgRecord) {
                               $msgDetails='<br>Compliance risk approve on : '.date('d-m-Y'); 
                               $msg=array(
                         'CoCode'=>C_COCODE,    
                         'AuctionID'=>$AuctionID,    
                         'ResponseID'=>$ResponseID,    
                         'Event'=>'Compliance risk approve',    
                         'Page'=>'Charter Party (+FN)',    
                         'Section'=>'Business Process',    
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
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
    return $CPL_RiskID;
}
    
public function getVerifiedHash()
{
    $snid=$this->input->post('snid');
    $this->db->select('*');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->where('snid', $snid);
    $query=$this->db->get();
    return $query->row();
}
    
public function getHashVerifiedLog()
{
    $snid=$this->input->post('snid');
    $add_for=$this->input->post('add_for');
    $this->db->select('*');
    $this->db->from('Udt_AU_HashVerifyLog');
    $this->db->where('snid', $snid);
    $this->db->where('add_for', $add_for);
    $this->db->order_by('hvlid', 'DESC');
    $query=$this->db->get();
    return $query->result();
}

    
public function saveCpSubjectShipowner()
{
    extract($this->input->post());
        
    $this->db->select('*');
    $this->db->from('udt_AU_CpSubjectShipOwner');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CpSubID', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 10);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
        
    $data=array(
                'BPID'=>$row->BPID,
                'RecordOwner'=>$row->RecordOwner,
                'MasterID'=>$row->MasterID,
                'TID'=>$row->TID,
                'name_of_process'=>$row->name_of_process,
                'process_applies'=>$row->process_applies,
                'process_flow_sequence'=>$row->process_flow_sequence,
                'putting_freight_quote'=>$row->putting_freight_quote,
                'submitting_freight_quote'=>$row->submitting_freight_quote,
                'fixture_not_finalization'=>$row->fixture_not_finalization,
                'charter_party_finalization'=>$row->charter_party_finalization,
                'finalization_completed_by'=>$row->finalization_completed_by,
                'message_text'=>$row->message_text,
                'show_in_process'=>$row->show_in_process,
                'show_in_fixture'=>$row->show_in_fixture,
                'show_in_charter_party'=>$row->show_in_charter_party,
                'validity'=>$row->validity,
                'date_from'=>$row->date_from,
                'date_to'=>$row->date_to,
                'status'=>$row->status,
                'comments'=>$row->comments,
                'UserID'=>$row->UserID,
                'ApproveStatus'=>$row->ApproveStatus,
                'ApprovedBy'=>$row->ApprovedBy,
                'UserDate'=>date('Y-m-d H:i:s'),
                'Version'=>($row->Version+0.1),
                'on_subject_status'=>$row->on_subject_status,
                'lift_subject_status'=>$row->lift_subject_status
                );
                
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 10);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query1=$this->db->get();
    $row1=$query1->row();
    $New_BPVID=$row1->BPVID;
        
    $cp_data=array(
    'MasterID'=>$AuctionID,
    'ResponseID'=>$ResponseID,
    'BPVID'=>$New_BPVID,
    'SubjectStatus'=>$SubjectStatus,
    'SubjectReason'=>$SubjectReason,
    'ReasonForSubject'=>$ReasonForSubject,
    'CurrentDateTime'=>date('Y-m-d H:i:s', strtotime($CurrentDateTime)),
    'SubjectValidity'=>$SubjectValidity,
    'SubjectValidDays'=>$SubjectValidDays,
    'SubjectValidHours'=>$SubjectValidHours,
    'SubjectCommenceDateTime'=>date('Y-m-d H:i:s', strtotime($SubjectCommenceDateTime)),
    'CommentSend'=>$CommentSend,
    'InvUserID'=>$UserID,
    'CreatedDate'=>date('Y-m-d H:i:s')
    );
    $this->db->insert('udt_AU_CpSubjectShipOwner', $cp_data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_CpSubjectShipOwner');
    $this->db->where('BPVID', $New_BPVID);
    $query1=$this->db->get();
    $row1=$query1->row();
    $CpSubID=$row1->CpSubID;
    $html='';
        
    if(count($old_row)>0) {
        if($old_row->SubjectStatus != $SubjectStatus) {
            if($old_row->SubjectStatus==1) {
                $oldSubjectStatus='Place on subjects';
            } else if($old_row->SubjectStatus==2) {
                $oldSubjectStatus='Lift subjects';
            } else if($old_row->SubjectStatus==3) {
                $oldSubjectStatus='No subjects';
            }
            if($SubjectStatus==1) {
                $newSubjectStatus='Place on subjects';
            } else if($SubjectStatus==2) {
                $newSubjectStatus='Lift subjects';
            } else if($SubjectStatus==3) {
                $newSubjectStatus='No subjects';
            }
                $html .='<br>Old subject status : '.$oldSubjectStatus.' <span class="diff">||</span> New subject status : '.$newSubjectStatus;
        }
        if($old_row->SubjectReason != $SubjectReason) {
            if($old_row->SubjectReason==1) {
                  $oldSubjectReason='Management approval';
            } else if($old_row->SubjectReason==2) {
                $oldSubjectReason='Other';
            }
            if($SubjectReason==1) {
                $newSubjectReason='Management approval';
            } else if($SubjectReason==2) {
                $newSubjectReason='Other';
            }
            $html .='<br>Old subject(s) reason : '.$oldSubjectReason.' <span class="diff">||</span> New subject(s) reason : '.$newSubjectReason;
        }
        if($SubjectReason==2) {
            if($old_row->ReasonForSubject != $ReasonForSubject) {
                $html .='<br>Old  reason for subject(s) : '.$old_row->ReasonForSubject.' <span class="diff">||</span> New  reason for subject(s) : '.$ReasonForSubject;
            }
        }
            $crtdate=strtotime($CurrentDateTime);
            $oldcrtdate=strtotime($old_row->CurrentDateTime);
        if($oldcrtdate != $crtdate) {
            $html .='<br>Old Current date/time is : '.date('d-m-Y H:i:s', strtotime($old_row->CurrentDateTime)).' <span class="diff">||</span> New Current date/time is : '.date('d-m-Y H:i:s', strtotime($CurrentDateTime));
        }
        if($old_row->SubjectValidity != $SubjectValidity) {
            if($old_row->SubjectValidity==1) {
                $oldSubjectValidity='Open (till fixed)';
            } else if($old_row->SubjectValidity==2) {
                $oldSubjectValidity='Limited Days and Hours';
            }
            if($SubjectValidity==1) {
                $newSubjectValidity='Open (till fixed)';
            } else if($SubjectValidity==2) {
                $newSubjectValidity='Limited Days and Hours';
            }
            $html .='<br>Old Subject validity : '.$oldSubjectValidity.' <span class="diff">||</span> New Subject validity : '.$newSubjectValidity;
        }
        if($SubjectValidity==2) {
            if($old_row->SubjectValidDays !=$SubjectValidDays && $old_row->SubjectValidHours !=$SubjectValidHours) {
                $html .='<br>Old Subject(s) valid for : '.$old_row->SubjectValidDays.' days '.$old_row->SubjectValidHours.' hours <span class="diff">||</span> New Subject(s) valid for : '.$SubjectValidDays.' days '.$SubjectValidHours.' hours ';
            } else {
                if($old_row->SubjectValidDays !=$SubjectValidDays) {
                    $html .='<br>Old Subject(s) valid for : '.$old_row->SubjectValidDays.' days '.$old_row->SubjectValidHours.' hours <span class="diff">||</span> New Subject(s) valid for : '.$SubjectValidDays.' days '.$SubjectValidHours.' hours ';
                }
                if($old_row->SubjectValidHours !=$SubjectValidHours) {
                    $html .='<br>Old Subject(s) valid for : '.$old_row->SubjectValidDays.' days '.$old_row->SubjectValidHours.' hours <span class="diff">||</span> New Subject(s) valid for : '.$SubjectValidDays.' days '.$SubjectValidHours.' hours ';
                }
            }
        }
            
            $subcomdate=strtotime($SubjectCommenceDateTime);
            $oldsubcomdate=strtotime($old_row->SubjectCommenceDateTime);
        if($oldsubcomdate != $subcomdate) {
            $html .='<br>Old Subject(s) commence (date/time) : '.date('d-m-Y H:i:s', strtotime($old_row->SubjectCommenceDateTime)).' <span class="diff">||</span> New Subject(s) commence (date/time) : '.date('d-m-Y H:i:s', strtotime($SubjectCommenceDateTime));
        }
            
        if($old_row->CommentSend != $CommentSend) {
            $html .='<br>Old Reconfirm approved status : '.$old_row->CommentSend.' <span class="diff">||</span> New Reconfirm approved status : '.$CommentSend;
        }
    } else {
        if($SubjectStatus==1) {
            $newSubjectStatus='Place on subjects';
        } else if($SubjectStatus==2) {
            $newSubjectStatus='Lift subjects';
        } else if($SubjectStatus==3) {
            $newSubjectStatus='No subjects';
        }
            $html .='<br>Old subject status :  <span class="diff">||</span> New subject status : '.$newSubjectStatus;
    
        if($SubjectReason==1) {
            $newSubjectReason='Management approval';
        } else if($SubjectReason==2) {
            $newSubjectReason='Other';
        }
            $html .='<br>Old subject(s) reason :  <span class="diff">||</span> New subject(s) reason : '.$newSubjectReason;
        
        if($SubjectReason==2) {
                $html .='<br>Old  reason for subject(s) :  <span class="diff">||</span> New  reason for subject(s) : '.$ReasonForSubject;
        }
        if($CurrentDateTime !='') {
                $html .='<br>Old Current date/time is :  <span class="diff">||</span> New Current date/time is : '.date('d-m-Y H:i:s', strtotime($CurrentDateTime));
        }
            
        if($SubjectValidity==1) {
                $newSubjectValidity='Open (till fixed)';
        } else if($SubjectValidity==2) {
                $newSubjectValidity='Limited Days and Hours';
        }
            $html .='<br>Old Subject validity :  <span class="diff">||</span> New Subject validity : '.$newSubjectValidity;
        
        if($SubjectValidity==2) {
            $html .='<br>Old Subject(s) valid for :  <span class="diff">||</span> New Subject(s) valid for : '.$SubjectValidDays.' days '.$SubjectValidHours.' hours ';
        }
            
        if($SubjectCommenceDateTime !='') {
            $html .='<br>Old Subject(s) commence (date/time) :  <span class="diff">||</span> New Subject(s) commence (date/time) : '.date('d-m-Y H:i:s', strtotime($SubjectCommenceDateTime));
        }
        if($CommentSend=='') {
            $html .='<br>Old Reconfirm approved status :  <span class="diff">||</span> New Reconfirm approved status : '.$CommentSend;
        }
    }
        
    $html1=trim($html, "<br>");
    $htmldata=array('ViewChage'=>$html1);
        
    if($SubjectStatus==2 || $SubjectStatus==3) {
        $htmldata['ApproveStatus']=1;
        $this->db->select('FirstName,LastName');
        $this->db->from('udt_UserMaster');
        $this->db->where('ID', $UserID);
        $queryby=$this->db->get();
        $row_by=$queryby->row();
        $htmldata['ApprovedBy']=$row_by->FirstName.' '.$row_by->LastName;
    } else {
        $htmldata['ApproveStatus']=0;
        $htmldata['ApprovedBy']='';
    }
        
    $this->db->where('BPVID', $New_BPVID);
    $this->db->update('udt_AU_BusinessProcessVersionWise', $htmldata);
        
    if($Doc_ids) {
        $doc_ids=trim($Doc_ids, ",");
        $docIdArr=explode(",", $doc_ids);
        for($i=0;$i<count($docIdArr);$i++){
            $this->db->select('*');
            $this->db->from('udt_AU_BP_ChartererDocs');
            $this->db->where('Doc_ID', $docIdArr[$i]);
            $qu_d=$this->db->get();
            $d=$qu_d->row();
            $doc_data = array(
            'ResponseID'=>$d->ResponseID,
            'BPVID'=>$New_BPVID,
            'name_of_process'=>$d->name_of_process,
            'DocumentName'=>$d->DocumentName,
            'DocumentDate'=>$d->DocumentDate,
            'FileName'=> $d->FileName,
            'FileType'=>$d->FileType,
            'FileSize'=>$d->FileSize,
            'UserID'=>$UserID, 
            'CreatedDate'=>Date('Y-m-d H:i:s') 
            );
            $this->db->insert('udt_AU_BP_ChartererDocs', $doc_data);
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
        
    for($i=0;$i<count($document['name']);$i++) {
        if($document['error'][$i]==4 || $document['tmp_name'][$i]=='') {
            continue;
        } else {
            $ext=getExtension($document['name'][$i]);
            //$ext=strtoupper($ext);
            if($ext=='pdf' || $ext=='PDF') {
                  $nar=explode(".", $document['type'][$i]);
                  $type=end($nar);
                  $file=rand(1, 999999).'_____'.$document['name'][$i];
                  $tmp=$document['tmp_name'][$i];
                  $filesize=$document['size'][$i];
                    
                  $actual_image_name = 'TopMarx/'.$file;
                    
                  $s3->putObjectFile($tmp, $bucket, $actual_image_name, S3::ACL_PUBLIC_READ);
                    
                  $file_data = array(
                   'ResponseID'=>$ResponseID,
                   'BPVID'=>$New_BPVID,
                   'name_of_process'=>10,
                   'DocumentName'=>$document_name[$i],
                   'DocumentDate'=>date('Y-m-d', strtotime($document_date[$i])),
                   'FileName'=> $file,
                   'FileType'=>$type,
                   'FileSize'=>round($filesize/1024),
                   'UserID'=>$UserID, 
                   'CreatedDate'=>Date('Y-m-d H:i:s') 
                   );
                  $this->db->insert('udt_AU_BP_ChartererDocs', $file_data);
            }
        }
    }
        
    if($SubjectStatus==1) {
        $msgDetails='<br>Subjects are placed on DateTime : '.date('d-m-Y H:i:s');
    } else if($SubjectStatus==2) {
        $msgDetails='<br>Subjects are lifted on DateTime : '.date('d-m-Y H:i:s');
    } else if($SubjectStatus==3) {
        $msgDetails='<br>No subjects on DateTime : '.date('d-m-Y H:i:s');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseBrokerUsers');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('Status', 1);
    $qry=$this->db->get();
    $resBrokerResult=$qry->result();
        
    $bUsersArr =array();
        
    foreach($resBrokerResult as $bu){
        if(!in_array($bu->SigningUserID, $bUsersArr)) {
            $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName ');
            $this->db->from('udt_AUM_MESSAGE_MASTER');    
            $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
            $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $row->RecordOwner);
            $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
            $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '22');
            $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
            $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $bu->SigningUserID);
            $queryowner=$this->db->get();
            $msgData=$queryowner->row();
                
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'C/P on subjects (shipowner/broker)',    
            'Page'=>'Charter Parties (+FN)',    
            'Section'=>'Business Process (Invitee)',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$msgData->MessageID,    
            'UserID'=>$msgData->ForUserID,    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                
            $bUsersArr[] =$bu->SigningUserID;
        }
            
    }
            
    $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName ');
    $this->db->from('udt_AUM_MESSAGE_MASTER');    
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
    $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $row->RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $row->RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '22');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
    $queryowner=$this->db->get();
    $msgRecord=$queryowner->result();    
        
    foreach($msgRecord as $mr){
        
        $msg=array(
        'CoCode'=>C_COCODE,    
        'AuctionID'=>$AuctionID,    
        'ResponseID'=>$ResponseID,    
        'Event'=>'C/P on subjects (shipowner/broker)',    
        'Page'=>'Charter Parties (+FN)',    
        'Section'=>'Business Process (Invitee)',    
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
        
    $this->db->where('AuctionID', $AuctionID);
    $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        
    return $CpSubID;
}
    
public function authenticateUserInv()
{
    $TID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $ApprovalType=$this->input->post('ApprovalType');
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AU_BusinessProcessAuctionWise.UserList');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionID);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.UserList', $UserID);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.Status', 1);
    if($ApprovalType=='CpSubjectInv') {
        $this->db->where('udt_AUM_BusinessProcess.name_of_process', 10);
    }
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getCpSubjectInvContent()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post(BPVID);
        
    $this->db->select('udt_AU_CpSubjectShipOwner.*,udt_UserMaster.LoginID,udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_AU_CpSubjectShipOwner');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_CpSubjectShipOwner.InvUserID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('BPVID', $BPVID);
    $qry=$this->db->get();
    return $qry->row();
}
        
public function getCpSubjectInvDocuments()
{
    $ResponseID=$this->input->post('ResponseID');
    $BPVID=$this->input->post('BPVID');
    //$BPVID=139;
    $this->db->select('udt_AU_BP_ChartererDocs.*');
    $this->db->from('udt_AU_BP_ChartererDocs');
    $this->db->where('BPVID', $BPVID);
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->result();
}
    
public function getCpSubjectInvMessage()
{
    $TID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('TID', $TID);
    $this->db->where('name_of_process', 10);
    $this->db->where('version', '1.0');
    $query=$this->db->get();
    return $query->row();
}
    
public function checkUserInvPermission()
{
    $UserID=$this->input->post('UserID');
    $SubjectStatus=$this->input->post('SubjectStatus');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    if($SubjectStatus==1) {
        $this->db->where('CreateInvSubjectFlg', 1);
    } else if($SubjectStatus==2 || $SubjectStatus==3) {
        $this->db->where('LiftInvSubjectFlg', 1);
    }
    $query=$this->db->get();
    return $query->row();
}
    
public function getFixtureAllChangesById()
{
    $FixtureID=$this->input->post('FixtureID');
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select("udt_AU_AuctionFixture.FixtureVersion,udt_AU_AuctionFixture.FixtureNoteChanges,udt_AU_AuctionFixture.InviteeConfirmation,udt_AU_AuctionFixture.OwnerConfirmation,udt_AU_AuctionFixture.Status, (CONVERT(char(10),udt_AU_AuctionFixture.UserDate,105)+' '+CONVERT(char(12),udt_AU_AuctionFixture.UserDate,108)) as UserDate1, udt_UserMaster.FirstName, udt_UserMaster.LastName");
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_AuctionFixture.UserID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('FixtureID <= ', $FixtureID);
    $this->db->order_by('FixtureID', 'desc');
    $query=$this->db->get();
    return $query->result();
    
}
    
public function getAllDocumentationChangesById()
{
    if($this->input->post()) {
        $DocumentationID=$this->input->post('DocumentationID');
        $TID=$this->input->post('TID');
    } else if($this->input->get()) {
        $DocumentationID=$this->input->get('DocumentationID');
        $TID=$this->input->get('TID');
    }
        
    $this->db->select('udt_AuctionMainClauses.*');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('ResponseID', $TID);
    $this->db->where('DocumentationID <=', $DocumentationID);
    $query=$this->db->get();
    return $query->result();
        
}
    
    
public function getFixtureTableByFixtureId()
{
    $FixtureID=$this->input->post('FixtureID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_FixtureTable');
    $this->db->where('udt_AU_FixtureTable.FixtureID', $FixtureID);
    $this->db->order_by('FTID', 'asc');
    $query=$this->db->get();
    return $query->result();
}
    
public function getIpfsHashByDocumentationID($DocumentationID)
{
    $this->db->select('ipfsHash,EditableFlag');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('DocumentationID', $DocumentationID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getIPFSHashTransationByTIDDocument($TID)
{
    $this->db->select('transactionHash,ipfsHash,EditableFlag');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('ResponseID', $TID);
    $this->db->where('Status', 2);
    $this->db->where('InviteeConfirmation', 2);
    $this->db->where('OwnerConfirmation', 2);
    $qry1=$this->db->get();
    return $qry1->row();
}
    
public function getFixtureNoteByTidInvOwner()
{
    $ResponseID=$this->input->post('ResponseID');
        
    $content='';
    $temp=1;
    $strlen=1;
    while($temp !=0){
        $this->db->select('SUBSTRING(FixtureNote, '.$strlen.', 1000) as PTR');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('udt_AU_AuctionFixture.ResponseID', $ResponseID);
        $this->db->where('udt_AU_AuctionFixture.InviteeConfirmation', 2);
        $this->db->where('udt_AU_AuctionFixture.OwnerConfirmation', 2);
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
    
public function saveCpcodeFieldnameFieldvalue()
{
    $ResponseID=$this->input->post('ResponseID');
    $cp_code=$this->input->post('cp_code');
    $field_value=$this->input->post('field_value');
    $cp_code=trim($cp_code, "~~");
    $field_value=trim($field_value, "~~");
        
    $cp_code_arr=explode('~~', $cp_code);
    $field_value_arr=explode('~~', $field_value);
        
        
    $this->db->select('AuctionMainClauseID');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $DocClauseData=$query->result();
    $ftag='[[';
    $ltag=']]';
    $no_of_count=0;
    foreach($DocClauseData as $row) {
        $temp=1;
        $strlen=1;
        $content='';
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('AuctionMainClauseID', $row->AuctionMainClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
            
        for($i=0;$i<count($cp_code_arr);$i++) {
            if($cp_code_arr[$i] !='') {
                 $cp_code_arr1=explode('||', $cp_code_arr[$i]);
                 $field_value_arr1=explode('||', $field_value_arr[$i]);
                for($j=0;$j<count($cp_code_arr1);$j++) {
                    $cp_code_arr2=explode('|', $cp_code_arr1[$j]);
                    $field_value_arr2=explode('|', $field_value_arr1[$j]);
                    for($k=0; $k<count($cp_code_arr2); $k++) {
                            $cp_code_arr3=trim($cp_code_arr2[$k]);
                            $field_value_arr3=trim($field_value_arr2[$k]);
                        
                            $tf=$ftag.$cp_code_arr3.$ltag;
                        if($field_value_arr3 !='') {
                            $no_of_count=$no_of_count+substr_count($content, $tf);
                            $content=str_replace($tf, '<charterpartyspan contenteditable="false" style="cursor: not-allowed; -webkit-user-select: none; -moz-user-select: -moz-none; -ms-user-select: none; user-select: none; background-color: #efeaead6"><b>'.$field_value_arr3.'</b></charterpartyspan>', $content);
                        }
                    }
                }
                
            }
        }
        $this->db->where('AuctionMainClauseID', $row->AuctionMainClauseID);
        $this->db->update('udt_AuctionMainClauses', array('ClauseNote'=>$content,'AllClauseNote'=>$content));
    } 
        
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('Udt_AU_CpcodeMatchingCount');
        
    $cntdata=array(
                'ResponseID'=>$ResponseID,
                'cp_code_cnt'=>$no_of_count
                );    
    $this->db->insert('Udt_AU_CpcodeMatchingCount', $cntdata); 
         
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('Udt_AU_CpcodeValue');
        
    for($i=0;$i<count($cp_code_arr);$i++) {
        if($cp_code_arr[$i] !='') {
            $cp_code_arr1=explode('||', $cp_code_arr[$i]);
            $field_value_arr1=explode('||', $field_value_arr[$i]);
            for($j=0;$j<count($cp_code_arr1);$j++) {
                $cp_code_arr2=explode('|', $cp_code_arr1[$j]);
                $field_value_arr2=explode('|', $field_value_arr1[$j]);
                for($k=0; $k<count($cp_code_arr2); $k++) {
                    $cp_code_arr3=trim($cp_code_arr2[$k]);
                    $field_value_arr3=trim($field_value_arr2[$k]);
                    $data=array(
                     'ResponseID'=>$ResponseID,
                     'cp_code'=>$cp_code_arr3,
                     'field_value'=>$field_value_arr3
                     );    
                    $this->db->insert('Udt_AU_CpcodeValue', $data);
                }
            }
        }
    }
}
    
public function getDelRemoveContent()
{
    $all_content=$this->input->post('allcontentwithcharterpary');
    $mystring=$all_content;
        
    $findme='<del';
    $findme1='</del>';
    while(1) {
        $pos=0;
        $pos1=0;
        $pos = strpos($mystring, $findme);
        $pos1 = strpos($mystring, $findme1);
        if($pos1==0 || $pos1=='') {    
            $mystring=str_replace('<charterpartyspan charterpartynoteditablepartbysujeet><b></b></charterpartyspan>', "", $mystring);
            return $mystring;
        } else {
            $cut=substr($mystring, $pos, ($pos1-$pos)).'</del>';
            $mystring=str_replace($cut, "", $mystring);
        }
    }
        
}
    
public function getNoOfCount()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('Udt_AU_CpcodeMatchingCount');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row()->cp_code_cnt;
}
    
public function getDocumentStatusCount($DocumentationID)
{
    $where="((Status=2 and InvStatus=2) or EditableFlag=1) and DocumentationID=".$DocumentationID;
    $this->db->select('Status,InvStatus');
    $this->db->from('udt_AuctionMainClauses');
    $this->db->where($where);
    $query=$this->db->get();
    $rslt=$query->result();
    $ro_status=0;
    $inv_status=0;
    $tot_count=count($rslt);
    foreach($rslt as $row) {
        if($row->InvStatus==2) {
            $inv_status++;
        }
        if($row->Status==2) {
            $ro_status++;
        }
    }
    $arr['tot_count']=$tot_count;
    $arr['inv_status']=$inv_status;
    $arr['ro_status']=$ro_status;
    return $arr;
}
    
public function PlaceBusinessProcessAfterFixureFinal()
{
    $ResponseID=$this->input->post('ResponseID');
    $AuctionID=$this->input->post('AuctionID');
    $UserID=$this->input->post('UserID');
    $UserName=$this->input->post('UserName');
        
    $this->db->select('*');
    $this->db->from('udt_AU_ChartererSubjects');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CH_Sub_id', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
    $version=$row->Version+0.1;
    $RecordOwner=$row->RecordOwner;
        
    if($old_row->CH_Task==1 && $row->lift_subject_status==2) { 
    } else { return 0; 
    }
    
    $data=array(
    'BPID'=>$row->BPID,
    'RecordOwner'=>$row->RecordOwner,
    'MasterID'=>$row->MasterID,
    'TID'=>$row->TID,
    'name_of_process'=>$row->name_of_process,
    'process_applies'=>$row->process_applies,
    'process_flow_sequence'=>$row->process_flow_sequence,
    'putting_freight_quote'=>$row->putting_freight_quote,
    'submitting_freight_quote'=>$row->submitting_freight_quote,
    'fixture_not_finalization'=>$row->fixture_not_finalization,
    'charter_party_finalization'=>$row->charter_party_finalization,
    'finalization_completed_by'=>$row->finalization_completed_by,
    'message_text'=>$row->message_text,
    'show_in_process'=>$row->show_in_process,
    'show_in_fixture'=>$row->show_in_fixture,
    'show_in_charter_party'=>$row->show_in_charter_party,
    'validity'=>$row->validity,
    'date_from'=>$row->date_from,
    'date_to'=>$row->date_to,
    'status'=>$row->status,
    'comments'=>$row->comments,
    'UserID'=>$UserID,
    'ApproveStatus'=>1,
    'ApprovedBy'=>$UserName,
    'UserDate'=>date('Y-m-d H:i:s'),
    'Version'=>($row->Version+0.1),
    'on_subject_status'=>$row->on_subject_status,
    'lift_subject_status'=>$row->lift_subject_status
    );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
        
    $New_BPVID=$row->BPVID;
    $InviteeUsers='';
        
    $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query1=$this->db->get();
    $row1=$query1->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '20');
    //$this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID',$UserID);
    $query12=$this->db->get();
    $msgData=$query12->result();
        
    $Task_name='CP notified subject';
    $subject_text='Negotiations commence. On subject(s) till finalization.';
        
    $msgDetails='<br>'.$Task_name.' <br>'.$subject_text; 
        
    foreach($msgData as $md){
        $msg=array(
        'CoCode'=>C_COCODE,    
        'AuctionID'=>$AuctionID,    
        'ResponseID'=>$ResponseID,    
        'Event'=>'C/P on subjects (charterer)',    
        'Page'=>'Charter Party (+FN)',    
        'Section'=>'Business Process',    
        'subSection'=>'',    
        'StatusFlag'=>'1',    
        'MessageDetail'=>$msgDetails,    
        'MessageMasterID'=>$md->MessageID,    
        'UserID'=>$UserID,    
        'FromUserID'=>$UserID,    
        'UserDate'=>date('Y-m-d H:i:s')    
        );
        $this->db->insert('udt_AU_Messsage_Details', $msg);
            
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
        
    $this->db->select('udt_AUM_Freight.InvUserID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $frow=$query->row();
    $invUsers=explode(",", $frow->InvUserID);
        
    for($i=0; $i<count($invUsers);$i++){
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $invUsers[$i]);
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '21');
        $query12=$this->db->get();
        $msgData=$query12->row();
            
        $Task_name='CP notified subject';
        $subject_text='Negotiations commence. On subject(s) till finalization.';
        if($msgData) {
            $msgDetails='<br>'.$Task_name.' <br>'.$subject_text;
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'C/P on subjects (charterer)',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$msgData->MessageID,    
            'UserID'=>$invUsers[$i],    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
        $InviteeUsers .=$invUsers[$i].',';
    }
        
    $InviteeUsers=trim($InviteeUsers, ",");
        
    $data1=array(
                'CH_Task'=>2,
                'NotifySubject'=>'Negotiations commence. On subject(s) till finalization.',
                'GeneralComment'=>'',
                'ConfirmLift'=>'1',
                'LiftedComment'=>'subject lifted',
                'InviteeUsers'=>$InviteeUsers,
                'ResponseID'=>$ResponseID,
                'BPVID'=>$New_BPVID,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $ret=$this->db->insert('udt_AU_ChartererSubjects', $data1);
}
    
public function getAuctionDetailsByAuctionID($AuctionID)
{
    $this->db->select('udt_AU_Auctions.CountryID,udt_AU_Auctions.SignDateFlg,udt_AU_Auctions.UserSignDate, udt_CountryMaster.Description as C_Description');
    $this->db->from('udt_AU_Auctions');
    $this->db->join('udt_CountryMaster', 'udt_CountryMaster.ID=udt_AU_Auctions.CountryID', 'left');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getRecordOwnerRole()
{
    $MasterID=$this->input->post('AuctionID');
    $this->db->select('*');
    $this->db->from("udt_AU_Auctions");
    $this->db->join("udt_EntityType", 'udt_EntityType.ID=udt_AU_Auctions.AuctionersRole');
    $this->db->where('AuctionId', $MasterID);
    $query = $this->db->get();
    return $query->row();
}
    
public function createDefaultFixture($encode_html,$OwerID,$FixtureCompleteProcess,$Type,$fix_data_arr)
{
    $auctionID=$this->input->post('AuctionId');
    $ResponseID=$this->input->post('InviteeID');
    $UserID=$this->input->post('UserID');
    $encode_html=str_replace("&#39;", "", $encode_html);
    $encode_html=str_replace("'", "''", $encode_html);
    $data_h=array(
                'FixtureVersion'=>'Version 1.0',    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$auctionID,    
                'RecordOwner'=>$OwerID,    
                'ResponseID'=>$ResponseID,    
                'FixtureNote'=>$encode_html,
                'Status'=>'1',
                'InviteeConfirmation'=>'0',
                'OwnerConfirmation'=>'0',
                'UserID'=>$UserID,
                'RowStatus'=>'1',
                'HeaderContent'=>'',
                'FixtureNoteChanges'=>'',
                'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                'FixtureFormatType'=>$Type,
                'UserDate'=>date('Y-m-d H:i:s')    
    );
                
    $this->db->insert('udt_AU_AuctionFixture_H', $data_h);
        
    $data=array(
                'FixtureVersion'=>'Version 1.0',    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$auctionID,    
                'RecordOwner'=>$OwerID,    
                'ResponseID'=>$ResponseID,    
                'FixtureNote'=>$encode_html,
                'Status'=>'1',
                'InviteeConfirmation'=>'0',
                'OwnerConfirmation'=>'0',
                'UserID'=>$UserID,
                'HeaderContent'=>'',
                'FixtureNoteChanges'=>'',
                'FixtureCompleteProcess'=>$FixtureCompleteProcess,
                'FixtureFormatType'=>$Type,
                'UserDate'=>date('Y-m-d H:i:s')    
                );
        
    $ret=$this->db->insert('udt_AU_AuctionFixture', $data);
        
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('FixtureID', 'desc');
    $query=$this->db->get();
    $fix_row=$query->row();
        
    if($ret) {
        /* ---------------blockchain---------------------------- */
        //Save string into temp file
        $ipfsContent=$encode_html;
        $file = tempnam(sys_get_temp_dir(), 'POST');
        file_put_contents($file, $ipfsContent);
            
        //Post file
        $data = array(
        "uploadedFile"=>'@'.$file,
        );
            
            
        $url=BLOCK_CHAIN_URL.'ipfsDocument/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        $ipfsHash = curl_exec($ch);
        curl_close($ch);
            
        unlink($file);
            
        $chh2=strip_tags($encode_html);
        $FixtureHash=hash(HASH_ALGO, $chh2);
            
            
        $data = array("fixId" =>$fix_row->FixtureID,"version" =>'1.0','entityId'=>0,"aucId"=>$auctionID,"tId"=>$ResponseID,"recordId"=>$OwerID,"dStatus"=>1,"invConf"=>0,"ownConf"=>0,"uId"=>$UserID,"fixhash"=>$FixtureHash,"ipfsHash"=>$ipfsHash); 
            
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
        curl_close($ch);
        $docDdata=json_decode($result);
        $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash);
            
        $this->db->where('FixtureID', $fix_row->FixtureID);
        $this->db->update('udt_AU_AuctionFixture', $fixUpdatedData);
            
        /* ---------------/blockchain---------------------------- */
    }
        
    for($i=0;$i<count($fix_data_arr); $i++){
        $fixdata=array(
        'FixtureID'=>$fix_row->FixtureID,
        'ResponseID'=>$ResponseID,
        'CpCode'=>$fix_data_arr[$i]['CpCode'],
        'FieldLblName'=>$fix_data_arr[$i]['FieldLblName'],
        'FieldValue'=>$fix_data_arr[$i]['FieldValue'],
        'FieldColumnName'=>$fix_data_arr[$i]['FieldColumnName'],
        'GroupNumber'=>$fix_data_arr[$i]['GroupNumber'],
        'EditableFlag'=>1,
        'ActiveFlag'=>1
        );
        $this->db->insert('udt_AU_FixtureTable', $fixdata);
    }
    $length=strlen($encode_html);
    $i=0;
}
    
    
public function createDefaultDocumentation($OwerID,$tptfields,$data1,$data2,$data3,$data4,$data5,$data6,$mdlRow)
{
    $auctionID=$this->input->post('AuctionId');
    $ResponseID=$this->input->post('InviteeID');
    $UserID=$this->input->post('UserID');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('AuctionSection', 'cp');
    $this->db->order_by('LineNum', 'ASC');
    $this->db->order_by('DocumentID', 'ASC');
    $query=$this->db->get();
    $DocTitle=$query->row()->Title;
        
    $this->db->select('udt_AUM_DocumentType_Master.*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->where('DocumentTypeID', $DocTitle);
    $query1=$this->db->get();
    $DocMasterData=$query1->row();
    $editable='';
    $CharterPartyPdf='';
    $DocumentationNote='';
        
    $this->db->select('udt_AUM_DocumentClause.*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->order_by('SerialNo', 'ASC');
    $this->db->where('DocumentTypeID', $DocMasterData->DocumentTypeID);
    $query2=$this->db->get();
    $DocClauseData=$query2->result();
        
    $html ='<h6><b>INDEX TO CLAUSES</b></h6>';
    foreach($DocClauseData as $row) {
        $html .='<p>'.$row->ClauseNo.'.  '.$row->CaluseName.'</p>';    
    }
    $html .='<br>';
    $DocumentationNote=$html;
        
    $data_h=array(
                'DocumentationVersion'=>'Version 1.0',    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$auctionID,    
                'RecordOwner'=>$OwerID,    
                'ResponseID'=>$ResponseID,    
                'DocumentationNote'=>$DocumentationNote,
                'Status'=>'1',
                'EditableFlag'=>$DocMasterData->charterPartyEditableFlag,
                'ClauseType'=>$DocMasterData->ClauseType,
                'CharterPartyPdf'=>$DocMasterData->CharterPartyPdf,
                'InviteeConfirmation'=>'0',
                'OwnerConfirmation'=>'0',
                'UserID'=>$UserID,
                'RowStatus'=>'1',
                'UserDate'=>date('Y-m-d H:i:s')    
                );
        
    $this->db->insert('udt_AU_AuctionMainDocumentation_H', $data_h);
        
        
    $data=array(
                'DocumentationVersion'=>'Version 1.0',    
                'CoCode'=>C_COCODE,    
                'AuctionID'=>$auctionID,    
                'RecordOwner'=>$OwerID,    
                'ResponseID'=>$ResponseID,    
                'DocumentationNote'=>$html,
                'Status'=>'1',
                'EditableFlag'=>$DocMasterData->charterPartyEditableFlag,
                'ClauseType'=>$DocMasterData->ClauseType,
                'CharterPartyPdf'=>$DocMasterData->CharterPartyPdf,
                'InviteeConfirmation'=>'0',
                'OwnerConfirmation'=>'0',
                'UserID'=>$UserID,
                'UserDate'=>date('Y-m-d H:i:s')    
                );
    $this->db->insert('udt_AU_AuctionMainDocumentation', $data);
        
    $this->db->select('udt_AU_AuctionMainDocumentation.*');
    $this->db->from('udt_AU_AuctionMainDocumentation');
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('ResponseID', $ResponseID);
    $queryAuctionMain=$this->db->get();
    $AuctionMainData=$queryAuctionMain->row();
        
    $DocumentationID=$AuctionMainData->DocumentationID;
        
    $i=0;
    $all_clauses='';
    foreach($DocClauseData as $row) {
        $temp=1;
        $strlen=1;
        $content='';
        while($temp !=0){
            $this->db->select('SUBSTRING(ClauseText, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AUM_DocumentClause');
            $this->db->where('ClauseID', $row->ClauseID);
            $query1=$this->db->get();
            $result1=$query1->row();
            if($result1->PTR) {
                $content .=$result1->PTR;
                $strlen = $strlen + strlen($result1->PTR);
            }else{
                $temp=0;
            }
        }
            
        $content=str_replace("&#39;", "", $content);
        $content=str_replace("'", "''", $content);
            
        $ftag='[[';
        $ltag=']]';
        if($tptfields[0]->Included==1) {
            $tf=$ftag.$tptfields[0]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->AuctionID.'</b>',$content);
        }
        if($tptfields[1]->Included==1) {
            $tf=$ftag.$tptfields[1]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->EntityName.'</b>',$content);
        }
        if($tptfields[2]->Included==1) {
            $tf=$ftag.$tptfields[2]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->RoleDescription.'</b>',$content);
        }
        if($tptfields[3]->Included==1) {
            $tf=$ftag.$tptfields[3]->CpCode.$ltag;
            $contracttype='';
            if($data1->ContractType==1) {
                $contracttype='Spot';
            }
            if($data1->ContractType==2) {
                $contracttype='Contract';
            }
            //$content=str_ireplace($tf,'<b>'.$ContractType.'</b>',$content);
        }
        if($tptfields[4]->Included==1) {
            $tf=$ftag.$tptfields[4]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->COAReference.'</b>',$content);
        }
        if($tptfields[5]->Included==1) {
            $tf=$ftag.$tptfields[5]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->SalesAgreementReference.'</b>',$content);
        }
        if($tptfields[6]->Included==1) {
            $tf=$ftag.$tptfields[6]->CpCode.$ltag;
            $ModelFunction='';
            if($data1->ModelFunction==1) {
                $ModelFunction='Default (all charters)';
            } else if($data1->ModelFunction==2) {
                $ModelFunction='User selected (individual charters)';
            }
            //$content=str_ireplace($tf,'<b>'.$ModelFunction.'</b>',$content);
        }
        if($tptfields[7]->Included==1) {
            $tf=$ftag.$tptfields[7]->CpCode.$ltag;
            //$content=str_ireplace($tf,$mdlRow->ModelNumber,$content);
        }
        if($tptfields[8]->Included==1) {
            $tf=$ftag.$tptfields[8]->CpCode.$ltag;
            //$content=str_ireplace($tf,'<b>'.$data1->ShipmentReferenceID.'</b>',$content);
        }
            
            
            
        if($data3) {
            $templinenum='';
            foreach($data3 as $rw) {
                $temp='';
                $temp2='';
                $temp3='';
                if($templinenum==$rw->LineNum) {
                    continue;
                }
                $templinenum=$rw->LineNum;
            
                $Bacdata=$this->get_bac_by_responsecargoID($rw->ResponseCargoID);
            
                $bachtml='';
            
                foreach($Bacdata as $bac){
                    $TransactionType='';
                    $textcontent='';
                
                    if($bac->TransactionType=='Brokerage') {
                        if($tptfields[54]->Included) {
                            $tf=$ftag.$tptfields[54]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>Brokerage</b>',$content);
                        }
                        if($tptfields[55]->Included) {
                            $tf=$ftag.$tptfields[55]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityType.'</b>',$content);
                        }
                        if($bac->PayingEntityType=='Charterer') {
                            if($tptfields[56]->Included) {
                                $tf=$ftag.$tptfields[56]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityName.'</b>',$content);
                            }
                        }
                        if($tptfields[57]->Included) {
                                    $tf=$ftag.$tptfields[57]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityType.'</b>',$content);
                        }
                        if($bac->ReceivingEntityType=='Charterer') {
                            if($tptfields[58]->Included) {
                                $tf=$ftag.$tptfields[58]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityName.'</b>',$content);
                            }
                        }
                        if($bac->BrokerName) {
                            if($tptfields[59]->Included) {
                                $tf=$ftag.$tptfields[59]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->BrokerName.'</b>',$content);
                            }
                        }
                        if($tptfields[60]->Included) {
                                    $tf=$ftag.$tptfields[60]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PayableAs.'</b>',$content);
                        }
                        if($bac->PayableAs=='Percentage') {
                            if($bac->PercentageOnFreight) {
                                if($tptfields[61]->Included) {
                                    $tf=$ftag.$tptfields[61]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDeadFreight) {
                                if($tptfields[62]->Included) {
                                    $tf=$ftag.$tptfields[62]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDeadFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDemmurage) {
                                if($tptfields[63]->Included) {
                                    $tf=$ftag.$tptfields[63]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDemmurage.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnOverage) {
                                if($tptfields[64]->Included) {
                                    $tf=$ftag.$tptfields[64]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnOverage.'</b>',$content);
                                }
                            }    
                        } else if($bac->PayableAs=='LumpSum') {
                            if($tptfields[65]->Included) {
                                $tf=$ftag.$tptfields[65]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->LumpsumPayable.'</b>',$content);
                            }
                        } else if($bac->RatePerTonnePayable=='RatePerTonne') {
                            if($tptfields[68]->Included) {
                                        $tf=$ftag.$tptfields[68]->CpCode.$ltag;
                                        //$content=str_ireplace($tf,'<b>'.$bac->RatePerTonnePayable.'</b>',$content);
                            }
                        }
                    
                    }else if($bac->TransactionType=='Commision') {
                        if($tptfields[67]->Included) {
                            $tf=$ftag.$tptfields[67]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>AddComm</b>',$content);
                        }
                    
                        if($tptfields[68]->Included) {
                            $tf=$ftag.$tptfields[68]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityType.'</b>',$content);
                        }
                        if($bac->PayingEntityType=='Charterer') {
                            if($tptfields[69]->Included) {
                                  $tf=$ftag.$tptfields[69]->CpCode.$ltag;
                                  //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityName.'</b>',$content);
                            }
                        }
                        if($tptfields[70]->Included) {
                            $tf=$ftag.$tptfields[70]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityType.'</b>',$content);
                        }
                        if($bac->ReceivingEntityType=='Charterer') {
                            if($tptfields[71]->Included) {
                                         $tf=$ftag.$tptfields[71]->CpCode.$ltag;
                                         //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityName.'</b>',$content);
                            }
                        }
                        if($bac->BrokerName) {
                            if($tptfields[72]->Included) {
                                $tf=$ftag.$tptfields[72]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->BrokerName.'</b>',$content);
                            }
                        }
                        if($tptfields[73]->Included) {
                            $tf=$ftag.$tptfields[73]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->PayableAs.'</b>',$content);
                        }
                        if($bac->PayableAs=='Percentage') {
                            if($bac->PercentageOnFreight) {
                                if($tptfields[74]->Included) {
                                                 $tf=$ftag.$tptfields[74]->CpCode.$ltag;
                                                 //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDeadFreight) {
                                if($tptfields[75]->Included) {
                                    $tf=$ftag.$tptfields[75]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDeadFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDemmurage) {
                                if($tptfields[76]->Included) {
                                    $tf=$ftag.$tptfields[76]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDemmurage.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnOverage) {
                                if($tptfields[77]->Included) {
                                    $tf=$ftag.$tptfields[77]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnOverage.'</b>',$content);
                                }
                            }    
                        } else if($bac->PayableAs=='LumpSum') {
                            if($tptfields[78]->Included) {
                                $tf=$ftag.$tptfields[78]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.(int)$bac->LumpsumPayable.'</b>',$content);
                            }
                        } else if($bac->RatePerTonnePayable=='RatePerTonne') {
                            if($tptfields[79]->Included) {
                                                        $tf=$ftag.$tptfields[79]->CpCode.$ltag;
                                                        //$content=str_ireplace($tf,'<b>'.$bac->RatePerTonnePayable.'</b>',$content);
                            }
                        }
                    
                    }elseif($bac->TransactionType=='Others') {
                        if($tptfields[80]->Included) {
                            $tf=$ftag.$tptfields[80]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>Others</b>',$content);
                        }
                    
                        if($tptfields[81]->Included) {
                            $tf=$ftag.$tptfields[81]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityType.'</b>',$content);
                        }
                        if($bac->PayingEntityType=='Charterer') {
                            if($tptfields[82]->Included) {
                                  $tf=$ftag.$tptfields[82]->CpCode.$ltag;
                                  //$content=str_ireplace($tf,'<b>'.$bac->PayingEntityName.'</b>',$content);
                            }
                        }
                        if($tptfields[83]->Included) {
                            $tf=$ftag.$tptfields[83]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityType.'</b>',$content);
                        }
                        if($bac->ReceivingEntityType=='Charterer') {
                            if($tptfields[84]->Included) {
                                $tf=$ftag.$tptfields[84]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->ReceivingEntityName.'</b>',$content);
                            }
                        }
                        if($bac->BrokerName) {
                            if($tptfields[85]->Included) {
                                $tf=$ftag.$tptfields[85]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.$bac->BrokerName.'</b>',$content);
                            }
                        }
                        if($tptfields[86]->Included) {
                            $tf=$ftag.$tptfields[86]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$bac->PayableAs.'</b>',$content);
                        }
                        if($bac->PayableAs=='Percentage') {
                            if($bac->PercentageOnFreight) {
                                if($tptfields[87]->Included) {
                                    $tf=$ftag.$tptfields[87]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDeadFreight) {
                                if($tptfields[88]->Included) {
                                          $tf=$ftag.$tptfields[88]->CpCode.$ltag;
                                          //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDeadFreight.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnDemmurage) {
                                if($tptfields[89]->Included) {
                                    $tf=$ftag.$tptfields[89]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnDemmurage.'</b>',$content);
                                }
                            }
                            if($bac->PercentageOnOverage) {
                                if($tptfields[90]->Included) {
                                    $tf=$ftag.$tptfields[90]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$bac->PercentageOnOverage.'</b>',$content);
                                }
                            }    
                        } else if($bac->PayableAs=='LumpSum') {
                            if($tptfields[91]->Included) {
                                $tf=$ftag.$tptfields[91]->CpCode.$ltag;
                                //$content=str_ireplace($tf,'<b>'.(int)$bac->LumpsumPayable.'</b>',$content);
                            }
                        } else if($bac->RatePerTonnePayable=='RatePerTonne') {
                            if($tptfields[92]->Included) {
                                              $tf=$ftag.$tptfields[92]->CpCode.$ltag;
                                              //$content=str_ireplace($tf,'<b>'.$bac->RatePerTonnePayable.'</b>',$content);
                            }
                        }
                    }
                }
            
                if($rw->CargoLimitBasis==1) {
                    $CargoLimitBasis='Max and Min';
                    if($tptfields[20]->Included) {
                        $tf=$ftag.$tptfields[20]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.number_format($rw->MaxCargoMT).'</b>',$content);
                    }
                    if($tptfields[21]->Included) {
                        $tf=$ftag.$tptfields[21]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.number_format($rw->MinCargoMT).'</b>',$content);
                    }
                }else if($rw->CargoLimitBasis==2) {
                    $CargoLimitBasis='% Tolerance limit';
                    if($tptfields[17]->Included) {
                        $tf=$ftag.$tptfields[17]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.$rw->ToleranceLimit.'</b>',$content);
                    }
                    if($tptfields[18]->Included) {
                        $tf=$ftag.$tptfields[18]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.number_format($rw->UpperLimit).'</b>',$content);
                    }
                    if($tptfields[19]->Included) {
                        $tf=$ftag.$tptfields[19]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.number_format($rw->LowerLimit).'</b>',$content);
                    }
                }
                if($rw->LoadingRateUOM==1) {
                    $LoadingRateUOM='Per hour';
                }else if($rw->LoadingRateUOM==2) {
                    $LoadingRateUOM='Per weather working day';
                }else if($rw->LoadingRateUOM==3) {
                    $LoadingRateUOM='Max time limit';
                    if($tptfields[29]->Included) {
                        $tf=$ftag.$tptfields[29]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.(int)$rw->LpMaxTime.'</b>',$content);
                    }
                }
            
                if($rw->LpLaytimeType==1) {
                    $LpLaytimeType='Reversible';
                }else if($rw->LpLaytimeType==2) {
                    $LpLaytimeType='Non Reversible';
                }else if($rw->LpLaytimeType==3) {
                    $LpLaytimeType='Average';
                }
            
                if($rw->LpCalculationBasedOn==108) {
                    $LpCalculationBasedOn='Bill of Loading Quantity';
                }else if($rw->LpCalculationBasedOn==109) {
                    $LpCalculationBasedOn='Outturn or Discharge Quantity';
                }
            
                if($rw->LpPriorUseTerms==102) {
                    $LpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
                }else if($rw->LpPriorUseTerms==10) {
                    $LpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
                }else{
                    $LpPriorUseTerms='N/A';
                }
            
                if($rw->DpPriorUseTerms==102) {
                    $DpPriorUseTerms='IUATUTC || If Used Actual Time To Count';
                }else if($rw->DpPriorUseTerms==10) {
                    $DpPriorUseTerms='IUHTUTC || If Used Half Time To Count';
                }else{
                    $DpPriorUseTerms='N/A';
                }
            
                if($rw->LpLaytimeBasedOn==1) {
                    $LpLaytimeBasedOn='ATS || All Time Saved';
                }else if($rw->LpLaytimeBasedOn==2) {
                    $LpLaytimeBasedOn='WTS || Working Time Saved';
                }else{
                    $LpLaytimeBasedOn='N/A';
                }
            
                if($rw->LpCharterType==1) {
                    $LpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                }else if($rw->LpCharterType==2) {
                    $LpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
                }else if($rw->LpCharterType==3) {
                    $LpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
                }else if($rw->LpCharterType==4) {
                    $LpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
            
            
                if($rw->DischargingRateUOM==1) {
                    $DischargingRateUOM='Per hour';
                }else if($rw->DischargingRateUOM==2) {
                    $DischargingRateUOM='Per weather working day';
                }else if($rw->DischargingRateUOM==3) {
                    $DischargingRateUOM='Max time limit';
                    if($tptfields[42]->Included) {
                        $tf=$ftag.$tptfields[42]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.(int)$rw->DpMaxTime.'</b>',$content);
                    }
                }
            
                if($rw->DpLaytimeType==1) {
                    $DpLaytimeType='Reversible';
                }else if($rw->DpLaytimeType==2) {
                    $DpLaytimeType='Non Reversible';
                }else if($rw->DpLaytimeType==3) {
                    $DpLaytimeType='Average';
                }
            
                if($rw->DpCalculationBasedOn==108) {
                    $DpCalculationBasedOn='Bill of Loading Quantity';
                }else if($rw->DpCalculationBasedOn==109) {
                    $DpCalculationBasedOn='Outturn or Discharge Quantity';
                }
            
                if($rw->DpLaytimeBasedOn==1) {
                    $DpLaytimeBasedOn='ATS || All Time Saved';
                }else if($rw->DpLaytimeBasedOn==2) {
                    $DpLaytimeBasedOn='WTS || Working Time Saved';
                }else{
                    $DpLaytimeBasedOn='N/A';
                }
            
            
                if($rw->DpCharterType==1) {
                    $DpCharterType='1 Safe Port 1 Safe Berth (1SP1SB)';
                }else if($rw->DpCharterType==2) {
                    $DpCharterType='1 Safe Port 2 Safe Berth (1SP2SB)';
                }else if($rw->DpCharterType==3) {
                    $DpCharterType='2 Safe Port 1 Safe Berth (2SP1SB)';
                }else if($rw->DpCharterType==4) {
                    $DpCharterType='2 Safe Port 2 Safe Berth (2SP2SB)';
                }
                if($tptfields[13]->Included) {
                    $tf=$ftag.$tptfields[13]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->Code.'</b>',$content);
                }
                //$html .='<p ><span >Version : &nbsp;</span>'.$rw->CargoVersion.'</p>';
                if($tptfields[14]->Included) {
                    $tf=$ftag.$tptfields[14]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.number_format($rw->CargoQtyMT).'</b>',$content);
                
                }
                if($tptfields[15]->Included) {
                    $tf=$ftag.$tptfields[15]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->CargoLoadedBasis.'</b>',$content);
                }
                if($tptfields[16]->Included) {
                    $tf=$ftag.$tptfields[16]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$CargoLimitBasis.'</b>',$content);
                }
                if($tptfields[22]->Included) {
                    $tf=$ftag.$tptfields[22]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->lpPortName.'</b>',$content);
                }
                if($tptfields[23]->Included) {
                    $tf=$ftag.$tptfields[23]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->LpLaycanStartDate)).'</b>',$content);
                }
                if($tptfields[24]->Included) {
                    $tf=$ftag.$tptfields[24]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->LpLaycanEndDate)).'</b>',$content);
                }
                if($tptfields[25]->Included) {
                    $tf=$ftag.$tptfields[25]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->LpPreferDate)).'</b>',$content);
                }
                if($tptfields[52]->Included) {
                    $tf=$ftag.$tptfields[52]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->ExpectedLpDelayDay.' days '.$rw->ExpectedLpDelayHour.' hours</b>',$content);
                }
                if($tptfields[26]->Included) {
                    $tf=$ftag.$tptfields[26]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->ldtCode.'</b>',$content);
                }
                if($tptfields[27]->Included) {
                    $tf=$ftag.$tptfields[27]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.number_format($rw->LoadingRateMT).'</b>',$content);
                
                }
                if($tptfields[28]->Included) {
                    $tf=$ftag.$tptfields[28]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LoadingRateUOM.'</b>',$content);
                }
                if($tptfields[30]->Included) {
                    $tf=$ftag.$tptfields[30]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LpLaytimeType.'</b>',$content);
                }
                if($tptfields[31]->Included) {
                    $tf=$ftag.$tptfields[31]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LpCalculationBasedOn.'</b>',$content);
                
                }
                if($tptfields[32]->Included) {
                    $tf=$ftag.$tptfields[32]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->ftCode.'</b>',$content);
                }
                if($tptfields[33]->Included) {
                    $tf=$ftag.$tptfields[33]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LpPriorUseTerms.'</b>',$content);
                }
                if($tptfields[34]->Included) {
                    $tf=$ftag.$tptfields[34]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LpLaytimeBasedOn.'</b>',$content);
                }
                if($tptfields[35]->Included) {
                    $tf=$ftag.$tptfields[35]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$LpCharterType.'</b>',$content);
                }
                if($tptfields[36]->Included) {
                    $tf=$ftag.$tptfields[36]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->cnrCode.'</b>',$content);
                }
                if($tptfields[144]->Included) {
                    $tf=$ftag.$tptfields[144]->CpCode.$ltag;
                    $StevedoringTermsLp=$this->getStevedoringTermsBySteveID($rw->LpStevedoringTerms);
                    //$content=str_ireplace($tf,'<b>'.$StevedoringTermsLp->Code.' || Description : '.$StevedoringTermsLp->Description.'</b>',$content);
                }
                if($tptfields[37]->Included) {
                    $tf=$ftag.$tptfields[37]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->dpPortName.'</b>',$content);
                
                }
                if($tptfields[38]->Included) {
                    $tf=$ftag.$tptfields[38]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->DpArrivalStartDate)).'</b>',$content);
                }
                if($tptfields[39]->Included) {
                    $tf=$ftag.$tptfields[39]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->DpArrivalEndDate)).'</b>',$content);
                }
                if($tptfields[40]->Included) {
                    $tf=$ftag.$tptfields[40]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($rw->DpPreferDate)).'</b>',$content);
                }
                if($tptfields[53]->Included) {
                    $tf=$ftag.$tptfields[53]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->ExpectedDpDelayDay.' days '.$rw->ExpectedDpDelayHour.' hours</b>',$content);
                }
                if($tptfields[41]->Included) {
                    $tf=$ftag.$tptfields[41]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->ddtCode.'</b>',$content);
                
                }
                if($tptfields[42]->Included) {
                    $tf=$ftag.$tptfields[42]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.number_format($rw->DischargingRateMT).'</b>',$content);
                
                }
                if($tptfields[43]->Included) {
                    $tf=$ftag.$tptfields[43]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DischargingRateUOM.'</b>',$content);
                }
                if($tptfields[45]->Included) {
                    $tf=$ftag.$tptfields[45]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DpLaytimeType.'</b>',$content);
                
                }
                if($tptfields[46]->Included) {
                    $tf=$ftag.$tptfields[46]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DpCalculationBasedOn.'</b>',$content);
                
                }
                if($tptfields[47]->Included) {
                    $tf=$ftag.$tptfields[47]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->dftCode.'</b>',$content);
                    
                }
                if($tptfields[48]->Included) {
                    $tf=$ftag.$tptfields[48]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DpPriorUseTerms.'</b>',$content);
                
                }
                if($tptfields[49]->Included) {
                    $tf=$ftag.$tptfields[49]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DpLaytimeBasedOn.'</b>',$content);
                
                }
                if($tptfields[50]->Included) {
                    $tf=$ftag.$tptfields[50]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$DpCharterType.'</b>',$content);
                
                }
                if($tptfields[51]->Included) {
                    $tf=$ftag.$tptfields[51]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$rw->cnrDCode.'</b>',$content);
                
                }
            
                if($tptfields[145]->Included) {
                    $tf=$ftag.$tptfields[145]->CpCode.$ltag;
                    $StevedoringTermsDp=$this->getStevedoringTermsBySteveID($rw->DpStevedoringTerms);
                    //$content=str_ireplace($tf,'<b>'.$StevedoringTermsDp->Code.' || Description : '.$StevedoringTermsDp->Description.'</b>',$content);
                
                }
            }
        }
            
        if($data4) {
            foreach($data4 as $rw1) {
                $temp11='';
                $temp22='';
                if($rw1->FreightBasis !='') {
            
                    if($rw1->FreightRateUOM==1) {
                               $FreightRateUOM='UnitCode : MT || Description : Metric Tonnes';
                    }else if($rw1->FreightRateUOM==2) {
                              $FreightRateUOM='UnitCode : LT || Description : Long Tonnes';
                    }else if($rw1->FreightRateUOM==3) {
                        $FreightRateUOM='UnitCode : PMT || Description : Per metric tonne';
                    }else if($rw1->FreightRateUOM==4) {
                        $FreightRateUOM='UnitCode : PLT || Description : Per long ton';
                    }else if($rw1->FreightRateUOM==5) {
                        $FreightRateUOM='UnitCode : WWD || Description : Weather Working Day';
                    }
                    if($rw1->FreightBasis==1) {
                        $FreightBasis='$/mt';
                        if($tptfields[95]->Included) {
                            $tf=$ftag.$tptfields[95]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$rw1->FreightRate.'</b>',$content);
                    
                        }
                        if($tptfields[96]->Included) {
                            $tf=$ftag.$tptfields[96]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$rw1->curCode.'</b>',$content);
                    
                        }
                        if($tptfields[97]->Included) {
                            $tf=$ftag.$tptfields[97]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$FreightRateUOM.'</b>',$content);
                    
                        }
                        if($tptfields[98]->Included) {
                            $tf=$ftag.$tptfields[98]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.number_format($rw1->FreightTce).'</b>',$content);
                    
                        }
                        if($tptfields[99]->Included) {
                            $tf=$ftag.$tptfields[99]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.number_format($rw1->FreightTceDifferential).'</b>',$content);
                    
                        }
                
                    }else if($rw1->FreightBasis==2) {
                        $FreightBasis='Lumpsum';
                        if($tptfields[100]->Included) {
                             $tf=$ftag.$tptfields[100]->CpCode.$ltag;
                             //$content=str_ireplace($tf,'<b>'.(int)$rw1->FreightLumpsumMax.'</b>',$content);
                    
                        }
                        if($tptfields[96]->Included) {
                             $tf=$ftag.$tptfields[96]->CpCode.$ltag;
                             //$content=str_ireplace($tf,'<b>'.$rw1->curCode.'</b>',$content);
                    
                        }
                    }else if($rw1->FreightBasis==3) {
                        $FreightBasis='High - Low ($/mt)';
                        if($tptfields[101]->Included) {
                                      $tf=$ftag.$tptfields[101]->CpCode.$ltag;
                                      //$content=str_ireplace($tf,'<b>'.(int)$rw1->FreightLow.'</b>',$content);
                        }
                        if($tptfields[102]->Included) {
                             $tf=$ftag.$tptfields[102]->CpCode.$ltag;
                             //$content=str_ireplace($tf,'<b>'.(int)$rw1->FreightHigh.'</b>',$content);
                        }
                        if($tptfields[96]->Included) {
                            $tf=$ftag.$tptfields[96]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$rw1->curCode.'</b>',$content);
                        }
                        if($tptfields[97]->Included) {
                            $tf=$ftag.$tptfields[97]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$FreightRateUOM.'</b>',$content);
                        }
                        if($tptfields[98]->Included) {
                            $tf=$ftag.$tptfields[98]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.(int)$rw1->FreightTce.'</b>',$content);
                        }
                        if($tptfields[99]->Included) {
                            $tf=$ftag.$tptfields[99]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.(int)$rw1->FreightTceDifferential.'</b>',$content);
                        }
                    } 
                    if($tptfields[94]->Included) {
                        $tf=$ftag.$tptfields[94]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.$FreightBasis.'</b>',$content);
                    }
                    if($rw1->Demurrage) {
                        if($tptfields[103]->Included) {
                            $tf=$ftag.$tptfields[103]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.number_format($rw1->Demurrage).'</b>',$content);
                        }
                    }
                    if($rw1->DespatchDemurrageFlag==1) {
                        $DespatchDemurrageFlag='Yes';
                        if($tptfields[104]->Included) {
                            $tf=$ftag.$tptfields[104]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$DespatchDemurrageFlag.'</b>',$content);
                        }
                        if($tptfields[105]->Included) {
                            $tf=$ftag.$tptfields[105]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.number_format($rw1->DespatchHalfDemurrage).'</b>',$content);
                    
                        }
                    }else if($rw1->DespatchDemurrageFlag==2) {
                        $DespatchDemurrageFlag='No';
                        if($tptfields[104]->Included) {
                            $tf=$ftag.$tptfields[104]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.$DespatchDemurrageFlag.'</b>',$content);
                        }
                        if($tptfields[105]->Included) {
                            $tf=$ftag.$tptfields[105]->CpCode.$ltag;
                            //$content=str_ireplace($tf,'<b>'.number_format($rw1->DespatchHalfDemurrage).'</b>',$content);
                        }
                
                    }
                
                    if($data5) {
                        foreach($data5 as $row1) {
                            if($tptfields[107]->Included) {
                                            $tf=$ftag.$tptfields[107]->CpCode.$ltag;
                                            //$content=str_ireplace($tf,'<b>'.number_format($row1->VesselSize).'</b>',$content);
                            }
                            if($tptfields[108]->Included) {
                                    $tf=$ftag.$tptfields[108]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$row1->basePort.'</b>',$content);
                            }
                            if($tptfields[109]->Included) {
                                            $tf=$ftag.$tptfields[109]->CpCode.$ltag;
                                            //$content=str_ireplace($tf,'<b>'.$row1->refPort.'</b>',$content);
                            }
                    
                            if($row1->defPort) {
                                if($tptfields[110]->Included) {
                                    $tf=$ftag.$tptfields[110]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$row1->defPort.'</b>',$content);
                                }
                                if($tptfields[111]->Included) {
                                    $tf=$ftag.$tptfields[111]->CpCode.$ltag;
                                    //$content=str_ireplace($tf,'<b>'.$row1->DifferentialAmount.'</b>',$content);
                                }
                            }
                            if($rw1->DifferentialInvitee) {
                                if($tptfields[106]->Included) {
                                        $tf=$ftag.$tptfields[106]->CpCode.$ltag;
                                        //$content=str_ireplace($tf,'<b>'.$rw1->DifferentialInvitee.'</b>',$content);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if($data6) {
            if($data6->SelectVesselBy==1) {
                $SelectVesselBy='Vessel name incl ex_name';
            }else if($data6->SelectVesselBy==2) {
                $SelectVesselBy='IMO number';
            }else if($data6->SelectVesselBy==3) {
                $SelectVesselBy='Vessel not found';
            }
            if($tptfields[112]->Included) {
                $tf=$ftag.$tptfields[112]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$SelectVesselBy.'</b>',$content);
            
            }
            if($tptfields[113]->Included) {
                $tf=$ftag.$tptfields[113]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->VesselName.'</b>',$content);
            }
            if($tptfields[114]->Included) {
                $tf=$ftag.$tptfields[114]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->IMO.'</b>',$content);

            }
            if($data6->VesselCurrentName ) {    
                if($tptfields[115]->Included) {
                    $tf=$ftag.$tptfields[115]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->VesselCurrentName.'</b>',$content);
            
                }
                if($tptfields[116]->Included) {
                    $tf=$ftag.$tptfields[116]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->VesselChangeNameDate)).'</b>',$content);
            
                }
            }
            if($tptfields[117]->Included) {
                $tf=$ftag.$tptfields[117]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->FirstLoadPortDate)).'</b>',$content);
            
            }
            if($tptfields[118]->Included) {
                $tf=$ftag.$tptfields[118]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->LastDisPortDate)).'</b>',$content);
            
            }
        
            if($tptfields[136]->Included) {
                $tf=$ftag.$tptfields[136]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->EntityName.'</b>',$content);
            
            }
            if($tptfields[137]->Included) {
                $tf=$ftag.$tptfields[137]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->AssociateCompanyID.'</b>',$content);
            
            }
            if($tptfields[138]->Included) {
                if($data6->Address1) {
                    $tf=$ftag.$tptfields[138]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->Address1.'</b>',$content);
            
                }
            }
            if($tptfields[139]->Included) {
                if($data6->Address2) {
                    $tf=$ftag.$tptfields[139]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->Address2.'</b>',$content);
            
                }
            }
            if($tptfields[140]->Included) {
                if($data6->Address3) {
                    $tf=$ftag.$tptfields[140]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->Address3.'</b>',$content);
            
                }
            }
            if($tptfields[141]->Included) {
                if($data6->Address4) {
                    $tf=$ftag.$tptfields[141]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->Address4.'</b>',$content);
            
                }
            }
            if($tptfields[142]->Included) {
                $tf=$ftag.$tptfields[142]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->C_Code.' || '.$data6->C_Description.'</b>',$content);
            
            }
            if($tptfields[143]->Included) {
                $tf=$ftag.$tptfields[143]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->S_Code.' || '.$data6->S_Description.'</b>',$content);
            
            }
        
            if($tptfields[119]->Included) {
                $tf=$ftag.$tptfields[119]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.number_format($data6->LOA).'</b>',$content);
            
            }
            if($tptfields[120]->Included) {
                $tf=$ftag.$tptfields[120]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.number_format($data6->Beam).'</b>',$content);
            
            }
            if($tptfields[121]->Included) {
                $tf=$ftag.$tptfields[121]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->Draft.'</b>',$content);
            
            }
            if($tptfields[122]->Included) {
                $tf=$ftag.$tptfields[122]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.number_format($data6->DeadWeight).'</b>',$content);
            
            }
        
            if((int)$data6->Dispalcement) {
                if($tptfields[123]->Included) {
                    $tf=$ftag.$tptfields[123]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->Dispalcement.'</b>',$content);
                
                }
            }
            if($tptfields[124]->Included) {
                $tf=$ftag.$tptfields[124]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->Source.'</b>',$content);
            
            }
            if($tptfields[125]->Included) {
                $tf=$ftag.$tptfields[125]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->Rating.'</b>',$content);
            
            }
            if($tptfields[126]->Included) {
                $tf=$ftag.$tptfields[126]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->RatingDate)).'</b>',$content);
            
            }
                
            if($data6->Source !='Rightship') {
                if($tptfields[127]->Included) {
                    $tf=$ftag.$tptfields[127]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->SourceType.'</b>',$content);
                
                }
            
                if($data6->SourceType=='Third party') {
                    if($tptfields[128]->Included) {
                        $tf=$ftag.$tptfields[128]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.$data6->VettingSource.'</b>',$content);
                    
                    }
                }
            }
            if($tptfields[129]->Included) {
                $tf=$ftag.$tptfields[129]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->Deficiency.'</b>',$content);
            
            }
                        
            if($data6->Deficiency == 'Outstanding' ) {
                if($tptfields[130]->Included) {
                    $tf=$ftag.$tptfields[130]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->DeficiencyCompDate)).'</b>',$content);
                
                }
            
            }
            if($tptfields[131]->Included) {
                $tf=$ftag.$tptfields[131]->CpCode.$ltag;
                //$content=str_ireplace($tf,'<b>'.$data6->DetentionFlag.'</b>',$content);
            
            }
                
            if($data6->DetentionFlag == 'Yes') {
                if($tptfields[132]->Included) {
                    $tf=$ftag.$tptfields[132]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->DetentionDate)).'</b>',$content);
                
                }
                if($tptfields[133]->Included) {
                    $tf=$ftag.$tptfields[133]->CpCode.$ltag;
                    //$content=str_ireplace($tf,'<b>'.$data6->DetentionLiftedFlag.'</b>',$content);
                
                }
                if($data6->DetentionLiftedFlag == 'Yes' ) {
                    if($tptfields[134]->Included) {
                        $tf=$ftag.$tptfields[134]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->DetentionLiftedDate)).'</b>',$content);
                    
                    }
                }
                if($data6->DetentionLiftedFlag == 'No' ) {
                    if($tptfields[135]->Included) {
                        $tf=$ftag.$tptfields[135]->CpCode.$ltag;
                        //$content=str_ireplace($tf,'<b>'.date('d-m-Y',strtotime($data6->DetentionLiftExpectedDate)).'</b>',$content);
                    
                    }
                }
            }            
        }
            
        if($DocMasterData->ClauseType=='1') {
            $this->db->select('*');
            $this->db->from('udt_AUM_Document_master');
            $this->db->where('DMID', $DocMasterData->DocumentTitle);
            $query122=$this->db->get();
            $ClauseName=$query122->row()->DocName;
            //$ClauseName=$DocMasterData->DocumentTitle;
        }else{
            $ClauseName=$row->CaluseName;
        }
            $ClauseNo=0;
        if($row->ClauseNo) {
                $ClauseNo=$row->ClauseNo;
        }
            $data1=array(
                'ClauseVersion'=>'Version 1.0',    
                'AuctionID'=>$auctionID,    
                'RecordOwner'=>$OwerID,    
                'ResponseID'=>$ResponseID,    
                'ClauseName'=>$ClauseName,
                'ClauseNote'=>$content,
                'DeletedClauseNote'=>'',
                'AddedClauseNote'=>'',
                'AllClauseNote'=>$content,
                'Status'=>'1',
                'InvStatus'=>'1',
                'EditableFlag'=>$row->Editable,
                'UserID'=>$UserID,
                'Clause'=>$ClauseNo,
                'ChangeEditableFlag'=>0,
                'ChangeClauseFlg'=>0,
                'ChangeClauseStatus'=>'',
                'UserDate'=>date('Y-m-d H:i:s'),    
                'DocumentationID'=>$DocumentationID
                );
                
            $this->db->insert('udt_AuctionMainClauses', $data1);
            $all_clauses .=$content;
    }
        
    if($DocMasterData->charterPartyEditableFlag==1) {
        /* ---------------editable blockchain---------------------------- */
        //Save string into temp file
        $ipfsContent=$all_clauses;
        $file = tempnam(sys_get_temp_dir(), 'POST');
        file_put_contents($file, $ipfsContent);
            
        //Post file
        $data = array(
        "uploadedFile"=>'@'.$file,
        );
            
        $url=BLOCK_CHAIN_URL.'ipfsDocument/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        $ipfsHash = curl_exec($ch);
        curl_close($ch);
            
        unlink($file);
            
        $chh2=strip_tags($all_clauses);
            
        $CharterHash=hash(HASH_ALGO, $chh2);
            
        $data = array("fixId" =>$DocumentationID,"version" =>'1.0','entityId'=>1,"aucId"=>$auctionID,"tId"=>$ResponseID,"recordId"=>$OwerID,"dStatus"=>1,"invConf"=>0,"ownConf"=>0,"uId"=>$UserID,"fixhash"=>$CharterHash,"ipfsHash"=>$ipfsHash); 
            
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
        curl_close($ch);
        $docDdata=json_decode($result);
        $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash,'CharterHash'=>$CharterHash);
            
        $this->db->where('DocumentationID', $DocumentationID);
        $this->db->update('udt_AU_AuctionMainDocumentation', $fixUpdatedData);
            
        /* ---------------blockchain---------------------------- */
            
    } else if($DocMasterData->charterPartyEditableFlag==0) {
            
        /* --------------- non editable blockchain---------------------------- */
        $filename=$DocMasterData->CharterPartyPdf;
            
        $bucket="hig-sam";
        include_once APPPATH.'third_party/S3.php';
        if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
        }
        if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
        }
        $s3 = new S3(awsAccessKey, awsSecretKey);

        $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$filename, 3600);
            
        $CharterHash=hash_file(HASH_ALGO, $url);
            
        $ipfsContent=file_get_contents($url);
        $file = tempnam(sys_get_temp_dir(), 'POST');
        file_put_contents($file, $ipfsContent);
            
        $data = array(
        "uploadedFile"=>'@'.$file,
        );
            
        $url=BLOCK_CHAIN_URL.'ipfsDocument/';
        $ch = curl_init($url);      
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");  
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);    
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
        $ipfsHash = curl_exec($ch);
        curl_close($ch);
            
        $data = array("fixId" =>$DocumentationID,"version" =>'1.0','entityId'=>1,"aucId"=>$auctionID,"tId"=>$ResponseID,"recordId"=>$OwerID,"dStatus"=>1,"invConf"=>0,"ownConf"=>0,"uId"=>$UserID,"fixhash"=>$CharterHash,"ipfsHash"=>$ipfsHash);  
            
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
        curl_close($ch);
        $docDdata=json_decode($result);
        $fixUpdatedData=array('blockchainIndex'=>$docDdata->blockchainIndex,'transactionHash'=>$docDdata->transactionId,'ipfsHash'=>$ipfsHash,'CharterHash'=>$CharterHash);
            
        $this->db->where('DocumentationID', $DocumentationID);
        $this->db->update('udt_AU_AuctionMainDocumentation', $fixUpdatedData);
            
        /* ---------------/blockchain---------------------------- */
    }
}
    
public function getStevedoringTermsBySteveID($id)
{
    $this->db->select('*');
    $this->db->from('udt_CP_SteveDoringTerms');
    $this->db->where('ID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityFixtureCompleteProcess()
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
    $this->db->from('udt_AU_Auctions');
    $this->db->where('udt_AU_Auctions.AuctionID', $AuctionId);
    $query=$this->db->get();
    $auctionrow=$query->row();
        
    $this->db->select('udt_EntityMaster.FixtureCompleteProcess');
    $this->db->from('udt_EntityMaster');
    $this->db->where('udt_EntityMaster.ID', $auctionrow->OwnerEntityID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getCptextFromTemplate()
{
    $ResponseID=$this->input->post('InviteeID');
    $AuctionID=$this->input->post('AuctionId');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('AuctionSection', 'cp');
    $query=$this->db->get();
    $DocTitle=$query->row()->Title;
        
    $this->db->select('udt_AUM_ReportTemplate.*');
    $this->db->from('udt_AUM_ReportTemplate');
    $this->db->join('udt_AUM_DocumentType_Master', 'udt_AUM_ReportTemplate.TemplateID= udt_AUM_DocumentType_Master.FixNoteTemplate');
    $this->db->where('udt_AUM_DocumentType_Master.DocumentTypeID', $DocTitle);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getFieldsFromTemplate()
{
    $ResponseID=$this->input->post('InviteeID');
    $AuctionID=$this->input->post('AuctionId');
        
    $this->db->select('*');
    $this->db->from('udt_AUM_Documents');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('AuctionSection', 'cp');
    $query1=$this->db->get();
    $DocTitle=$query1->row()->Title;
        
    $this->db->select('udt_AUM_DocumentType_Master.*');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_ReportTemplate', 'udt_AUM_ReportTemplate.TemplateID= udt_AUM_DocumentType_Master.FixNoteTemplate');
    $this->db->where('DocumentTypeID', $DocTitle);
    $query2=$this->db->get();
    $DocMasterData=$query2->row();
        
    if($DocMasterData->FixNoteTemplateType) {
        $this->db->select('*');
        $this->db->from('udt_AU_Template');
        $this->db->where('TemplateID', $DocMasterData->FixNoteTemplate);
        $this->db->order_by('udt_AU_Template.SeqNo', 'ASC');
        $query3=$this->db->get();
        $result=$query3->result();
    }
    $count=count($result);
    if($count) {
        return $result;
    } else {
        $this->db->select('*');
        $this->db->from('udt_AU_Template');
        $this->db->where('TemplateID', '1');
        $this->db->order_by('SeqNo', 'ASC');
        $query=$this->db->get();
        $default=$query->result();
        return $default;
    }
}
    
public function deleteInviteeFixture()
{
    $auctionID=$this->input->post('AuctionId');
    $ResponseID=$this->input->post('InviteeID');
        
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('udt_AU_AuctionFixture');
        
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('udt_AU_AuctionMainDocumentation');
        
    $this->db->where('AuctionID', $auctionID);
    $this->db->where('ResponseID', $ResponseID);
    $this->db->delete('udt_AuctionMainClauses');
        
    $this->db->where('MasterID', $auctionID);
    $this->db->where('TID', $ResponseID);
    $this->db->delete('Udt_AU_SinedDocument');
        
}
    
public function PlaceBusinessProcess()
{
    $ResponseID=$this->input->post('InviteeID');
    $AuctionID=$this->input->post('AuctionId');
    $UserID=$this->input->post('UserID');
    $UserName=$this->input->post('UserName');
        
    $this->db->select('*');
    $this->db->from('udt_AU_ChartererSubjects');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('CH_Sub_id', 'DESC');
    $query11=$this->db->get();
    $old_row=$query11->row();
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
    $version=$row->Version+0.1;
    $RecordOwner=$row->RecordOwner;
        
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->where('BussinessType', 1);
    $this->db->where('on_subject_status ', 2);
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('BPID', $row->BPID);
    $query1=$this->db->get();
    $bpawrow=$query1->result();
    if(count($bpawrow)==0) {
        return 0;
    }
    
    $data=array(
    'BPID'=>$row->BPID,
    'RecordOwner'=>$row->RecordOwner,
    'MasterID'=>$row->MasterID,
    'TID'=>$row->TID,
    'name_of_process'=>$row->name_of_process,
    'process_applies'=>$row->process_applies,
    'process_flow_sequence'=>$row->process_flow_sequence,
    'putting_freight_quote'=>$row->putting_freight_quote,
    'submitting_freight_quote'=>$row->submitting_freight_quote,
    'fixture_not_finalization'=>$row->fixture_not_finalization,
    'charter_party_finalization'=>$row->charter_party_finalization,
    'finalization_completed_by'=>$row->finalization_completed_by,
    'message_text'=>$row->message_text,
    'show_in_process'=>$row->show_in_process,
    'show_in_fixture'=>$row->show_in_fixture,
    'show_in_charter_party'=>$row->show_in_charter_party,
    'validity'=>$row->validity,
    'date_from'=>$row->date_from,
    'date_to'=>$row->date_to,
    'status'=>$row->status,
    'comments'=>$row->comments,
    'UserID'=>$UserID,
    'ApproveStatus'=>$row->ApproveStatus,
    'ApprovedBy'=>$UserName,
    'UserDate'=>date('Y-m-d H:i:s'),
    'Version'=>($row->Version+0.1),
    'on_subject_status'=>$row->on_subject_status,
    'lift_subject_status'=>$row->lift_subject_status
    );
    $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);
        
    $this->db->select('*');
    $this->db->from('udt_AU_BusinessProcessVersionWise');
    $this->db->where('name_of_process', 9);
    $this->db->where('TID', $ResponseID);
    $this->db->order_by('BPVID', 'DESC');
    $query=$this->db->get();
    $row=$query->row();
        
    $New_BPVID=$row->BPVID;
    $InviteeUsers='';
        
    $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query1=$this->db->get();
    $row1=$query1->row();
        
    $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
    $this->db->from('udt_AUM_MESSAGE_MASTER');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
    $this->db->where('udt_AUM_MESSAGE_MASTER.EntityID', $RecordOwner);
    $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
    $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
    $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '20');
    //$this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID',$UserID);
    $query12=$this->db->get();
    $msgData=$query12->result();
            
    $Task_name='CP notified subject';
    $subject_text='Negotiations commence. On subject(s) till finalization.';
            
    $msgDetails='<br>'.$Task_name.' <br>'.$subject_text; 
            
    foreach($msgData as $md){
        $msg=array(
        'CoCode'=>C_COCODE,    
        'AuctionID'=>$AuctionID,    
        'ResponseID'=>$ResponseID,    
        'Event'=>'C/P on subjects (charterer)',    
        'Page'=>'Charter Party (+FN)',    
        'Section'=>'Business Process',    
        'subSection'=>'',    
        'StatusFlag'=>'1',    
        'MessageDetail'=>$msgDetails,    
        'MessageMasterID'=>$md->MessageID,    
        'UserID'=>$UserID,    
        'FromUserID'=>$UserID,    
        'UserDate'=>date('Y-m-d H:i:s')    
        );
        $this->db->insert('udt_AU_Messsage_Details', $msg);
                
        $this->db->where('AuctionID', $AuctionID);
        $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
    }
        
        
    $this->db->select('udt_AUM_Freight.InvUserID');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    $frow=$query->row();
    $invUsers=explode(",", $frow->InvUserID);
        
    for($i=0; $i<count($invUsers);$i++){
        $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName');
        $this->db->from('udt_AUM_MESSAGE_MASTER');
        $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');
        $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $RecordOwner);
        $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
        $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_2');
        $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $invUsers[$i]);
        $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '20');
        $query12=$this->db->get();
        $msgData=$query12->row();
                
        $Task_name='CP notified subject';
        $subject_text='Negotiations commence. On subject(s) till finalization.';
        if($msgData) {
            $msgDetails='<br>'.$Task_name.' <br>'.$subject_text;
            $msg=array(
            'CoCode'=>C_COCODE,    
            'AuctionID'=>$AuctionID,    
            'ResponseID'=>$ResponseID,    
            'Event'=>'C/P on subjects (charterer)',    
            'Page'=>'Charter Party (+FN)',    
            'Section'=>'Business Process',    
            'subSection'=>'',    
            'StatusFlag'=>'1',    
            'MessageDetail'=>$msgDetails,    
            'MessageMasterID'=>$msgData->MessageID,    
            'UserID'=>$invUsers[$i],    
            'FromUserID'=>$UserID,    
            'UserDate'=>date('Y-m-d H:i:s')    
            );
            $this->db->insert('udt_AU_Messsage_Details', $msg);
                    
            $this->db->where('AuctionID', $AuctionID);
            $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
        }
        $InviteeUsers .=$invUsers[$i].',';
    }
        
    $InviteeUsers=trim($InviteeUsers, ",");
        
    $data1=array(
                'CH_Task'=>1,
                'NotifySubject'=>'Negotiations commence. On subject(s) till finalization.',
                'GeneralComment'=>'',
                'ConfirmLift'=>'',
                'LiftedComment'=>'',
                'InviteeUsers'=>$InviteeUsers,
                'ResponseID'=>$ResponseID,
                'BPVID'=>$New_BPVID,
                'UserID'=>$UserID,
                'CreatedDate'=>date('Y-m-d H:i:s')
                );
    $ret=$this->db->insert('udt_AU_ChartererSubjects', $data1);
}
    
public function CreateVersionBusinessProcess()
{
    extract($this->input->post());
        
    $this->db->where('MasterID', $AuctionId);
    $this->db->where('TID', $InviteeID);
    $this->db->delete('udt_AU_BusinessProcessVersionWise');
        
    $this->db->where('ResponseID', $InviteeID);
    $this->db->delete('udt_AU_ChartererSubjects');
        
    $this->db->select('udt_AUM_BusinessProcess.*,udt_AU_BusinessProcessAuctionWise.on_subject_status,udt_AU_BusinessProcessAuctionWise.lift_subject_status');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_AUM_BusinessProcess', 'udt_AUM_BusinessProcess.BPID=udt_AU_BusinessProcessAuctionWise.BPID');
    $this->db->where('udt_AU_BusinessProcessAuctionWise.AuctionID', $AuctionId);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.Status', 1);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 1);
    $query=$this->db->get();
    $rslt=$query->result();
        
    foreach($rslt as $row) {
        if($row->process_applies==4) {
            $data=array(
            'BPID'=>$row->BPID,
            'RecordOwner'=>$row->RecordOwner,
            'MasterID'=>$AuctionId,
            'TID'=>$InviteeID,
            'name_of_process'=>$row->name_of_process,
            'process_applies'=>$row->process_applies,
            'process_flow_sequence'=>$row->process_flow_sequence,
            'putting_freight_quote'=>$row->putting_freight_quote,
            'submitting_freight_quote'=>$row->submitting_freight_quote,
            'fixture_not_finalization'=>$row->fixture_not_finalization,
            'charter_party_finalization'=>$row->charter_party_finalization,
            'finalization_completed_by'=>$row->finalization_completed_by,
            'message_text'=>$row->message_text,
            'show_in_process'=>$row->show_in_process,
            'show_in_fixture'=>$row->show_in_fixture,
            'show_in_charter_party'=>$row->show_in_charter_party,
            'validity'=>$row->validity,
            'date_from'=>$row->date_from,
            'date_to'=>$row->date_to,
            'status'=>$row->status,
            'comments'=>$row->comments,
            'UserID'=>$UserID,
            'ApproveStatus'=>0,
            'ApprovedBy'=>'',
            'UserDate'=>date('Y-m-d H:i:s'),
            'Version'=>'1.0',
            'ViewChage'=>'',
            'on_subject_status'=>$row->on_subject_status,
            'lift_subject_status'=>$row->lift_subject_status
            );
            $r=$this->db->insert('udt_AU_BusinessProcessVersionWise', $data); //owner process
        }
    }
    // for invitee business process
    $this->db->select('udt_AUM_Freight.*,udt_UserMaster.EntityID as RecordOwner');
    $this->db->from('udt_AUM_Freight');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AUM_Freight.UserID', 'left');
    $this->db->where('ResponseID', $InviteeID);
    $fquery=$this->db->get();
    $frow=$fquery->row();
        
    $this->db->select('udt_AU_BusinessProcessAuctionWise.*');
    $this->db->from('udt_AU_BusinessProcessAuctionWise');
    $this->db->join('udt_UserMaster', 'udt_UserMaster.ID=udt_AU_BusinessProcessAuctionWise.UserList', 'left');
    $this->db->where('AuctionID', $AuctionId);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.Status', 1);
    $this->db->where('udt_AU_BusinessProcessAuctionWise.BussinessType', 2);
    $this->db->where('udt_UserMaster.EntityID', $frow->EntityID);
    $bqry=$this->db->get();
    $brst=$bqry->result();
        
    foreach($brst as $brow) {
        $this->db->select('*');
        $this->db->from('udt_AUM_BusinessProcess');
        $this->db->where('BPID', $brow->BPID);
        $querybr=$this->db->get();
        $brw=$querybr->row();
            
        if($brw->process_applies==4) {
            if($brw->name_of_process==10) {
                $this->db->select('udt_AUM_MESSAGE_MASTER.*, udt_EntityMaster.EntityName ');
                $this->db->from('udt_AUM_MESSAGE_MASTER');    
                $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AUM_MESSAGE_MASTER.RecordOwner');    
                $this->db->where('udt_AUM_MESSAGE_MASTER.RecordOwner', $frow->RecordOwner);
                $this->db->where('udt_AUM_MESSAGE_MASTER.MessageType', 'proc_msg');
                $this->db->where('udt_AUM_MESSAGE_MASTER.Events', '22');
                $this->db->where('udt_AUM_MESSAGE_MASTER.OnPage', 'page_3');
                $this->db->where('udt_AUM_MESSAGE_MASTER.ForUserID', $brow->UserList);
                $queryowner=$this->db->get();
                $msgData=$queryowner->row();
                if($msgData) {
                    $msgDetails='<br>subjects can be placed if required, Version 1.0 created.'; 
                    $msg=array(
                    'CoCode'=>C_COCODE,    
                    'AuctionID'=>$AuctionId,    
                    'ResponseID'=>$InviteeID,    
                    'Event'=>'C/P on subjects (shipowner/broker)',    
                    'Page'=>'Cargo Set Up (Quotes)',    
                    'Section'=>'',    
                    'subSection'=>'',    
                    'StatusFlag'=>'1',    
                    'MessageDetail'=>$msgDetails,    
                    'MessageMasterID'=>$msgData->MessageID,    
                    'UserID'=>$brow->UserList,    
                    'FromUserID'=>$UserID,    
                    'UserDate'=>date('Y-m-d H:i:s')    
                    );
                    $this->db->insert('udt_AU_Messsage_Details', $msg);
                        
                    $this->db->where('AuctionID', $AuctionId);
                    $this->db->update('udt_AU_Auctions', array('MessageFlag'=>'1','MsgDate'=>date('Y-m-d H:i:s')));
                }
            }
            
            $this->db->select('*');
            $this->db->from('udt_AU_BusinessProcessVersionWise');
            $this->db->where('MasterID', $AuctionId);
            $this->db->where('TID', $InviteeID);
            $this->db->where('BPID', $brow->BPID);
            $ch_qry=$this->db->get();
                
            if($ch_qry->num_rows() > 0) {
                continue; 
            } else {
                $data=array(
                'BPID'=>$brw->BPID,
                'RecordOwner'=>$brw->RecordOwner,
                'MasterID'=>$AuctionId,
                'TID'=>$InviteeID,
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
                'status'=>$brw->status,
                'comments'=>$brw->comments,
                'UserID'=>$UserID,
                'ApproveStatus'=>0,
                'ApprovedBy'=>'',
                'UserDate'=>date('Y-m-d H:i:s'),
                'Version'=>'1.0',
                'ViewChage'=>''
                );
                $this->db->insert('udt_AU_BusinessProcessVersionWise', $data);   //invitee process insert
            }
        }
    }
}
    
public function getResponseVessel()
{
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->where('ResponseID', $TID);
    $this->db->order_by('ResponseVesselID', 'DESC');
    $query=$this->db->get();
    return $query->row();
}
    
public function getBroker()
{
    $TID=$this->input->post('TID');
    $AuctionID=$this->input->post('AuctionID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $TID);
    $query=$this->db->get();
    $rslt=$query->row();
        
    $this->db->select('*');
    $this->db->from('Udt_AUM_Invitees');
    $this->db->where('AuctionID', $AuctionID);
    $this->db->where('EntityID', $rslt->EntityID);
    $query1=$this->db->get();
    $rslt1=$query1->row();
    if($rslt1->InviteeRole==6) {
        return $rslt1->Company;
    } else {
        return '-';
    }
}
    
public function getShipOwner()
{
    $TID=$this->input->post('TID');
    $this->db->select('udt_EntityMaster.EntityName');
    $this->db->from('udt_AU_ResponseVessel');
    $this->db->join('udt_EntityMaster', 'udt_EntityMaster.ID=udt_AU_ResponseVessel.DisponentOwnerID');
    $this->db->where('ResponseID', $TID);
    $this->db->order_by('ResponseVesselID', 'DESC');
    $query=$this->db->get();
    return $query->row()->EntityName;
}
    
public function getInviteeUsers()
{
    $ResponseID=$this->input->post('ResponseID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $ResponseID);
    $query=$this->db->get();
    return $query->row()->InvUserID;
}
    
public function getInviteeShipOwner()
{
    $ResponseID=$this->input->post('ResponseID');
    $MasterID=$this->input->post('AuctionID');
    $this->db->select('udt_AU_Responsevessel.*,udt_UserMaster.EntityID as E1_ID,E1.EntityName as E1_EntityName, E2.EntityName as E2_EntityName');
    $this->db->from("udt_AU_Responsevessel");
    $this->db->join("udt_UserMaster", 'udt_UserMaster.ID=udt_AU_Responsevessel.UserID');
    $this->db->join("udt_EntityMaster as E1", 'E1.ID=udt_UserMaster.EntityID');
    $this->db->join("udt_EntityMaster as E2", 'E2.ID=udt_AU_Responsevessel.DisponentOwnerID');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->order_by('ResponseVesselID', 'desc');
    $query = $this->db->get();
    return $query->row();
}
    
public function getFixtureVersion()
{
    $ResponseID=$this->input->post('ResponseID');
    $MasterID=$this->input->post('AuctionID');
        
    $this->db->select('*');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $ResponseID);
    $this->db->where('AuctionID', $MasterID);
    $this->db->order_by('FixtureID', 'desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getSignDocumentDataVerify()
{
    $TID=$this->input->get('TID');
    $MasterID=$this->input->get('AuctionID');
        
    $this->db->select('Udt_AU_SinedDocument.*, Owner.EntityName as OwnerName, SpOwner.EntityName as SpOwnerName');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->join('udt_EntityMaster as Owner', 'Owner.ID=Udt_AU_SinedDocument.RecordOwner', 'Left');
    $this->db->join('udt_EntityMaster as SpOwner', 'SpOwner.ID=Udt_AU_SinedDocument.ShipOwner', 'Left');
    $this->db->where('Udt_AU_SinedDocument.MasterID', $MasterID);
    $this->db->where('Udt_AU_SinedDocument.TID', $TID);
    $this->db->where('Udt_AU_SinedDocument.StatusCharterer', 1);
    $this->db->where('Udt_AU_SinedDocument.StatusShipowner', 1);
    $this->db->order_by('Udt_AU_SinedDocument.snid', 'DESC');
    $query=$this->db->get();
    return $query->result();
}
    
public function verifyDocument()
{
    $snid=$this->input->post('snid');
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->where('snid', $snid);
    $query=$this->db->get();
    $rslt=$query->row();
    if($rslt->DocumentType=='Fixture Note') {
            
        $this->db->select('*');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('AuctionID', $rslt->MasterID);
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->order_by('FixtureID', 'DESC');
        $query=$this->db->get();
        $fixres=$query->row();
            
        $content='';
        $temp=1;
        $strlen=1;
        while($temp !=0){
            $this->db->select('SUBSTRING(FixtureNote, '.$strlen.', 1000) as PTR');
            $this->db->from('udt_AU_AuctionFixture');
            $this->db->where('udt_AU_AuctionFixture.FixtureID', $fixres->FixtureID);
            $query=$this->db->get();
            $result=$query->row();
            if($result->PTR) {
                $content .=$result->PTR;
                $strlen = $strlen + strlen($result->PTR);
            }else{
                $temp=0;
            }
        }
            
        $chh2=strip_tags($content);
        $FixtureHash=hash(HASH_ALGO, $chh2);
        if($FixtureHash==$fixres->FixtureHash) {
            $r=1;
        } else {
            $r=2;
        }
    } else if($rslt->DocumentType=='Charter Party') {
            
        $this->db->select('*');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('AuctionID', $rslt->MasterID);
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->order_by('DocumentationID', 'DESC');
        $query=$this->db->get();
        $chartres=$query->row();
            
        if($chartres->EditableFlag==1) {
            $this->db->select('*');
            $this->db->from('udt_AuctionMainClauses');
            $this->db->where('DocumentationID', $chartres->DocumentationID);
            $this->db->order_by('Clause', 'asc');
            $query=$this->db->get();
            $result=$query->result();
                
            $content='';
            foreach($result as $row){
                $temp=1;
                $strlen=1;
                while($temp !=0){
                    $this->db->select('SUBSTRING(ClauseNote, '.$strlen.', 1000) as PTR');
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
            }
            $chh2=strip_tags($content);
            $CharterHash=hash(HASH_ALGO, $chh2);
            
            if($CharterHash==$chartres->CharterHash) {
                $r=1;
            } else {
                $r=2;
            }
        } else if($chartres->EditableFlag==0) {
            $bucket="hig-sam";
            include_once APPPATH.'third_party/S3.php';
            if (!defined('awsAccessKey')) { define('awsAccessKey', 'AKIAIDL7EVF4VORRXIBA');
            }
            if (!defined('awsSecretKey')) { define('awsSecretKey', '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');
            }
            $s3 = new S3(awsAccessKey, awsSecretKey);
            $url = $s3->getAuthenticatedURL($bucket, 'TopMarx/'.$chartres->CharterPartyPdf, 3600);
            $CharterHash=hash_file(HASH_ALGO, $url);
            if($CharterHash==$chartres->CharterHash) {
                $r=1;
            } else {
                $r=2;
            }
        }
    }
    $data=array('snid'=>$snid,'Status'=>$r,'UserID'=>$UserID,'add_for'=>1,'UserDate'=>date('Y-m-d H:i:s'));
    $this->db->insert('Udt_AU_HashVerifyLog', $data);
    return $r;
}
    
public function verifyIpfsDocument()
{
    $snid=$this->input->post('snid');
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->where('snid', $snid);
    $query=$this->db->get();
    $rslt=$query->row();
    if($rslt->DocumentType=='Fixture Note') {
        $this->db->select('transactionHash,ipfsHash,FixtureHash');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->where('Status', 2);
        $this->db->where('InviteeConfirmation', 2);
        $this->db->where('OwnerConfirmation', 2);
        $qry1=$this->db->get();
        $fxrdata=$qry1->row();
        $ipfsHash=$fxrdata->ipfsHash;
        
        /*-----------------ipfs----------------*/
        $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
        $ch = curl_init($url);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $mystring = curl_exec($ch);
        $chh2=strip_tags($mystring);
        $IpfsFixtureHash=hash(HASH_ALGO, $chh2);
        $FixtureHash=$fxrdata->FixtureHash;
        if($FixtureHash==$IpfsFixtureHash) {
            $r=1;
        } else {
            $r=2;
        }
        /*-----------------/ipfs----------------*/
            
    } else if($rslt->DocumentType=='Charter Party') {
            
        $this->db->select('*');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('AuctionID', $rslt->MasterID);
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->order_by('DocumentationID', 'DESC');
        $query=$this->db->get();
        $chartres=$query->row();
            
        $TransactionHash=$chartres->transactionHash;
        $ipfsHash=$chartres->ipfsHash;
        $url=BLOCK_CHAIN_URL.'getIpfsDocument/'.$ipfsHash;
        $ch = curl_init($url);     
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $mystring = curl_exec($ch);    
        if($chartres->EditableFlag==1) {
            $chh2=strip_tags($mystring);
            $IpfsFixtureHash=hash(HASH_ALGO, $chh2);    
        } else {
            $IpfsFixtureHash=hash(HASH_ALGO, $mystring);    
        }
        $CharterHash=$chartres->CharterHash;
        if($CharterHash==$IpfsFixtureHash) {
            $r=1;
        } else {
            $r=2;
        }
            
    }
    $data=array('snid'=>$snid,'Status'=>$r,'UserID'=>$UserID,'add_for'=>2,'UserDate'=>date('Y-m-d H:i:s'));
    $this->db->insert('Udt_AU_HashVerifyLog', $data);
    return $r;
}
    
public function verifyBlockchainDocument()
{
    $snid=$this->input->post('snid');
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('Udt_AU_SinedDocument');
    $this->db->where('snid', $snid);
    $query=$this->db->get();
    $rslt=$query->row();
    if($rslt->DocumentType=='Fixture Note') {
        $this->db->select('transactionHash,ipfsHash,FixtureHash');
        $this->db->from('udt_AU_AuctionFixture');
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->where('Status', 2);
        $this->db->where('InviteeConfirmation', 2);
        $this->db->where('OwnerConfirmation', 2);
        $qry1=$this->db->get();
        $fxrdata=$qry1->row();
        $ipfsHash=$fxrdata->ipfsHash;
        
        /*-----------------blockchain----------------*/
        $FixtureHash=$fxrdata->FixtureHash;
        $data1['transactions'] = array($fxrdata->transactionHash); 
        $data_string = json_encode($data1);
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
        $BlocDetailsArr=json_decode($BlocDetails);
        $BcFixtureHash=$BlocDetailsArr[0]->fixhash;
        
        if($FixtureHash==$BcFixtureHash) {
             $r=1;
        } else {
            $r=2;
        }
        /*-----------------/blockchain----------------*/
            
    } else if($rslt->DocumentType=='Charter Party') {
        $this->db->select('*');
        $this->db->from('udt_AU_AuctionMainDocumentation');
        $this->db->where('AuctionID', $rslt->MasterID);
        $this->db->where('ResponseID', $rslt->TID);
        $this->db->order_by('DocumentationID', 'DESC');
        $query=$this->db->get();
        $chartres=$query->row();
            
        /*-----------------blockchain----------------*/
        $CharterHash=$chartres->CharterHash;
        $data1['transactions'] = array($chartres->transactionHash); 
        $data_string = json_encode($data1);
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
        $BlocDetailsArr=json_decode($BlocDetails);
        $BcCharterHash=$BlocDetailsArr[0]->fixhash;
            
        if($CharterHash==$BcCharterHash) {
            $r=1;
        } else {
            $r=2;
        }
            
        /*-----------------/blockchain----------------*/
            
    }
    $data=array('snid'=>$snid,'Status'=>$r,'UserID'=>$UserID,'add_for'=>3,'UserDate'=>date('Y-m-d H:i:s'));
    $this->db->insert('Udt_AU_HashVerifyLog', $data);
    return $r;
}
    
public function userVerify()
{
    $snid=$this->input->post('snid');
    $UserID=$this->input->post('UserID');
    $CheckOwner=$this->input->post('CheckOwner');
        
    $this->db->select('UserID,SignaturePublicKey,SignatureCharterPartyHash,sds,sdr,sdv,CONVERT(VARCHAR(10),UserDate,105) as UserDate,CONVERT(VARCHAR(15),UserDate,108) as UserTime');
    $this->db->from('Udt_AU_SignatureDetails');
    $this->db->where('sigid', $snid);
    if($CheckOwner==1) {
        $this->db->where('CheckOwner=1');
    } else {
        $this->db->where('CheckOwner !=1');
    }
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getFreightQuoteRow()
{
    $TID=$this->input->post('TID');
    $this->db->select('*');
    $this->db->from('udt_AUM_Freight');
    $this->db->where('ResponseID', $TID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUserCompanyDetailById($UserID)
{
    $this->db->select('udt_UserMaster.FirstName,udt_UserMaster.LastName,Udt_EntityMaster.EntityName');
    $this->db->from('udt_UserMaster');
    $this->db->join('Udt_EntityMaster', 'Udt_EntityMaster.ID=udt_UserMaster.EntityID');
    $this->db->where('udt_UserMaster.ID', $UserID);
    $query=$this->db->get();
    return $query->row();
}
    
public function getLatestDocumentClauses()
{
    $DocumentTypeID=$this->input->post('DocumentTypeID');
    $this->db->select('udt_AUM_DocumentClause.*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('DocumentTypeID', $DocumentTypeID);
    $this->db->order_by('SerialNo', 'Desc');
    $query=$this->db->get();
    return $query->row();
}
    
public function getAllClausesText()
{
    if($this->input->post()) {
        $id=$this->input->post('id');
    }
    if($this->input->get()) {
        $id=$this->input->get('id');
    }
        
    $this->db->select('*');
    $this->db->from('udt_AUM_DocumentClause');
    $this->db->where('DocumentTypeID', $id);
    $this->db->order_by('SerialNo', 'ASC');
    $query=$this->db->get();
    return $query->result();
}
    
public function getLogo()
{
    if($this->input->post()) {
        $id=$this->input->post('id');
    }
    if($this->input->get()) {
        $id=$this->input->get('id');
    }
        
    $this->db->select('Logo,LogoAlign,DocumentTitle,ClauseType,udt_AUM_Document_master.DocName');
    $this->db->from('udt_AUM_DocumentType_Master');
    $this->db->join('udt_AUM_Document_master', 'udt_AUM_Document_master.DMID=udt_AUM_DocumentType_Master.DocumentTitle');
    $this->db->where('DocumentTypeID', $id);
    $query=$this->db->get();
    return $query->row();
}
    
public function getEntityInviteeStatus()
{
    $EntityID=$this->input->post('EntityMasterID');
    $this->db->select('*');
    $this->db->from('udt_EntityMaster');
    $this->db->where('ID', $EntityID);
    $query=$this->db->get();
    return $query->row();
        
}
    
public function getIPFSHashTransationByTID($TID)
{
    $this->db->select('transactionHash,ipfsHash');
    $this->db->from('udt_AU_AuctionFixture');
    $this->db->where('ResponseID', $TID);
    $this->db->where('Status', 2);
    $this->db->where('InviteeConfirmation', 2);
    $this->db->where('OwnerConfirmation', 2);
    $qry1=$this->db->get();
    return $qry1->row();
}
        
public function getUserDetailById()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('udt_AddressMaster.Email,udt_RoleMaster.Name');
    $this->db->from('udt_UserMaster');
    $this->db->join('udt_AddressMaster', 'udt_UserMaster.OfficialAddressID=udt_AddressMaster.ID');
    $this->db->join('udt_RoleMaster', 'udt_UserMaster.DesignationRoleID=udt_RoleMaster.ID');
    $this->db->where('udt_UserMaster.ID', $UserID);
    $query=$this->db->get();
    return $query->row();
}    
    
public function getUserByID($CreatedBy)
{
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $CreatedBy);
    $query=$this->db->get();
    return $query->row();
}
    
public function getUserPermissions()
{
    $UserID=$this->input->post('UserID');
    $this->db->select('*');
    $this->db->from('udt_UserMaster');
    $this->db->where('ID', $UserID);
    $query=$this->db->get();
    return $query->row();
}
    
	
	
}


