<?php
/**
 * Functions Class
 *
 * @package   Functions
 * @project   IrishAppsactive8r
 * @author    Developer <developer@IrishAppsactive8r.com>
 * @copyright Copyright (c) 2015
 * @version   1.0
 **/

class Functions
{
	/**
	* Function Name : __construct
	* params        : None
	*/
	public function __construct()
	{ 
		$this->db = new MysqliDb (DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		$this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		//ini_alter('date.timezone','Asia/Kolkata');
		ini_alter('date.timezone','Europe/Dublin');
		$this->currentDatetime = date('Y-m-d H:i:s');
		$this->currentTimestampMS = round(microtime(true) * 1000);
		$this->currentDate = date('Y-m-d');
	}
	
	
	public function getServerTimings()
	{ 
		$hrTimestamp = date('Y-m-d H:i:s');
		$msTimestamp = round(microtime(true) * 1000);
		return array('result'=>TRUE,'message'=>'Dublin TimeZone','human_timestamp'=>$hrTimestamp,'timestamp'=>$msTimestamp);
	}
	
	/**
	* Method Name: testFunc
	* Param      : NONE
	* Return     : NONE
	* Use        : To check the DB Connection
	*/
	public function testFunc()
	{
		return $this->readTableData(array(),'tPri_User');
	}
	
	/**
	* Method Name: createRecordAddInfo
	* Param      : NONE
	* Return     : Array of fActive, fDeleted, fCreatedTs, fModifiedTs
	*/
	public function createRecordAddInfo($data)
	{
		$result = array();
		if(!isset($data['fActive']))
			$result['fActive'] = 1;
		if(!isset($data['fDeleted']))
			$result['fDeleted'] = 0;
		if(!isset($data['fCreatedTs']))
			$result['fCreatedTs'] = round(microtime(true) * 1000);
		if(!isset($data['fModifiedTs']))
			$result['fModifiedTs'] = round(microtime(true) * 1000);
		
		return $result;
	}
	
	/**
	* Method Name: modifyRecordAddInfo
	* Param      : NONE
	* Return     : Array of fModifiedTs
	*/
	public function modifyRecordAddInfo($data)
	{
		$result = array();
		if(!isset($data['fModifiedTs']))
			$result['fModifiedTs'] = round(microtime(true) * 1000);
		
		return $result;
	}
	
	/**
	* Method Name: deleteRecordAddInfo
	* Param      : NONE
	* Return     : Array of fActive, fDeleted, fModifiedTs
	*/
	public function deleteRecordAddInfo($data)
	{
		$result = array();
		if(!isset($data['fActive']))
			$result['fActive'] = 0;
		if(!isset($data['fDeleted']))
			$result['fDeleted'] = 1;
		if(!isset($data['fModifiedTs']))
			$result['fModifiedTs'] = round(microtime(true) * 1000);
		
		return $result;
	}
	
	
	/**
	* Method Name: insertIntoTable
	* Param      : $data ---> field values and $tbl---> $table name
	* Return     : Inserted Row Data
	* Use        : Insert a new record in '$tbl'
	*/
	public function insertIntoTable($rawData,$tbl)
	{
		$data_to_be_inserted_array= $rawData['insert_data'];
		$insert_results = array();
		$inserted_data = array();
		foreach($data_to_be_inserted_array as $data_to_be_inserted)
		{
			switch($tbl)
			{
				case 'tPri_Parent':
				case 'tPri_ParentUser':
				case 'tPri_Business' :
				case 'tPri_User' :
				case 'tPri_Team':
				case 'tPri_SessionHeader':
				case 'tPri_SessionHeader_test':
				case 'tPri_ShareAthlete':
				case 'tPri_ExerciseType':
				case 'tPub_ExerciseTypeDefault':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_TeamUser':
				case 'tPri_ConfigurationValue':
				case 'tPri_TeamAthlete':
				case 'tPri_Log':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						unset($createRecordAddInfo['fActive']);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_SessionData':
				case 'tPri_SessionData_test':
				case 'tPri_SessionRep':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						unset($createRecordAddInfo['fActive']);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_Athlete':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
						if(!isset($data_to_be_inserted['fModifiedSessionTs']))
							$data_to_be_inserted['fModifiedSessionTs'] = round(microtime(true) * 1000);
					break;
				
			}
			
			if(isset($data_to_be_inserted['fImage'])  && $this->isContains($data_to_be_inserted['fImage'],DB_PATH)==false && trim($data_to_be_inserted['fImage'])!='')
			{
				$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_inserted['fImage']));
				if($saveBaseSixtyFourImage['result'])
				{
					$data_to_be_inserted['fImage'] = $saveBaseSixtyFourImage['db_path'];
				}
				else
				{
					$data_to_be_inserted['fImage'] = '';
				}
			}
			else if(isset($data_to_be_inserted['fImageUrl'])  && $this->isContains($data_to_be_inserted['fImageUrl'],DB_PATH)==false&& trim($data_to_be_inserted['fImageUrl'])!='')
			{
				$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_inserted['fImageUrl']));
				if($saveBaseSixtyFourImage['result'])
				{
					$data_to_be_inserted['fImageUrl'] = $saveBaseSixtyFourImage['db_path'];
				}
				else
				{
					$data_to_be_inserted['fImageUrl'] = '';
				}
			}
			
			
			if(isset($data_to_be_inserted['uuid']))
			{
				$primary_key_id = $data_to_be_inserted['uuid'];
				$this->db->where('uuid',$primary_key_id);
				$existing_record = $this->db->get($tbl);
			
				if(!empty($existing_record))
				{	
					array_push($insert_results,0);
				}else{
					$inserted_id = $this->db->insert($tbl,$data_to_be_inserted);
					if($inserted_id>0)
					{
						array_push($insert_results,$inserted_id);
						$this->db->where('id',$inserted_id);
						$inserted_record = $this->db->get($tbl);
						
						if(isset($inserted_record[0]['fImage']) && $this->isContains($inserted_record[0]['fImage'],BASE_SERVER_URL)==false  && trim($inserted_record[0]['fImage'])!='')
							$inserted_record[0]['fImage'] = BASE_SERVER_URL.$inserted_record[0]['fImage'];
						else if(isset($inserted_record[0]['fImageUrl']) && $this->isContains($inserted_record[0]['fImageUrl'],BASE_SERVER_URL)==false  && trim($inserted_record[0]['fImageUrl'])!='')
							$inserted_record[0]['fImageUrl'] = BASE_SERVER_URL.$inserted_record[0]['fImageUrl'];
						array_push($inserted_data,$inserted_record[0]);
					}
					else
					{
						array_push($insert_results,0);
					}
				}
			}else
			{
				$inserted_id = $this->db->insert($tbl,$data_to_be_inserted);
				if($inserted_id>0)
				{
					array_push($insert_results,$inserted_id);
					$this->db->where('id',$inserted_id);
					$inserted_record = $this->db->get($tbl);
					
					if(isset($inserted_record[0]['fImage']) && $this->isContains($inserted_record[0]['fImage'],BASE_SERVER_URL)==false && trim($inserted_record[0]['fImage'])!='')
						$inserted_record[0]['fImage'] = BASE_SERVER_URL.$inserted_record[0]['fImage'];
					else if(isset($inserted_record[0]['fImageUrl']) && $this->isContains($inserted_record[0]['fImageUrl'],BASE_SERVER_URL)==false  && trim($inserted_record[0]['fImage'])!='')
						$inserted_record[0]['fImageUrl'] = BASE_SERVER_URL.$inserted_record[0]['fImageUrl'];
					
					array_push($inserted_data,$inserted_record[0]);
				}
				else
				{
					array_push($insert_results,0);
				}
			}
		}
		if(in_array(0,$insert_results))
		{
			$message = 'Some of the records failed to insert. You can find the inserted records here!';
		}
		else
		{
			$message = INSERT_SUCCESS;
		}
		if(!empty($inserted_data))
			return array('result'=>TRUE,'message'=>$message, 'data'=>$inserted_data);
		else
			return array('result'=>FALSE,'message'=>INSERT_FAILED,'err_code'=>EC_RECORD_FAILED_TO_INSERT);
	}
	
	
	/**
	* Method Name: saveIntoTable
	* Param      : $data ---> field values and $tbl---> $table name
	* Return     : Save API which converts duplication to update
	*/
	public function saveIntoTable($rawData,$tbl)
	{
		$data_to_be_inserted_array= $rawData['insert_data'];
		
		//print_r($data_to_be_inserted_array);exit;
		//$insert_results = array();
		//$inserted_data = array();
		
		$total_record_count = sizeof($data_to_be_inserted_array);
		$inserted_record_count = 0;
		$updated_record_count = 0;
		$insert_failed_record_count = 0;
		$update_failed_record_count = 0;
		
		foreach($data_to_be_inserted_array as $data_to_be_inserted)
		{
			switch($tbl)
			{
				case 'tPri_Parent':
				case 'tPri_ParentUser':
				case 'tPri_Business' :
				case 'tPri_User' :
				case 'tPri_Team':
				case 'tPri_SessionHeader':
				case 'tPri_SessionHeader_test':
				case 'tPri_ShareAthlete':
				case 'tPri_ExerciseType':
				case 'tPub_ExerciseTypeDefault':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_TeamUser':
				case 'tPri_ConfigurationValue':
				case 'tPri_TeamAthlete':
				case 'tPri_Log':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						unset($createRecordAddInfo['fActive']);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_SessionData':
				case 'tPri_SessionData_test':
				case 'tPri_SessionRep':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						unset($createRecordAddInfo['fActive']);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
					break;
				case 'tPri_Athlete':
						$createRecordAddInfo = $this->createRecordAddInfo($data_to_be_inserted);
						if(!empty($createRecordAddInfo))
							$data_to_be_inserted = array_merge($data_to_be_inserted, $createRecordAddInfo);
						if(!isset($data_to_be_inserted['fModifiedSessionTs']))
							$data_to_be_inserted['fModifiedSessionTs'] = round(microtime(true) * 1000);
					break;
				
			}
			
			if(isset($data_to_be_inserted['fImage'])  && $this->isContains($data_to_be_inserted['fImage'],DB_PATH)==false && trim($data_to_be_inserted['fImage'])!='')
			{
				$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_inserted['fImage']));
				if($saveBaseSixtyFourImage['result'])
				{
					$data_to_be_inserted['fImage'] = $saveBaseSixtyFourImage['db_path'];
				}
				else
				{
					$data_to_be_inserted['fImage'] = '';
				}
			}
			else if(isset($data_to_be_inserted['fImageUrl'])  && $this->isContains($data_to_be_inserted['fImageUrl'],DB_PATH)==false&& trim($data_to_be_inserted['fImageUrl'])!='')
			{
				$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_inserted['fImageUrl']));
				if($saveBaseSixtyFourImage['result'])
				{
					$data_to_be_inserted['fImageUrl'] = $saveBaseSixtyFourImage['db_path'];
				}
				else
				{
					$data_to_be_inserted['fImageUrl'] = '';
				}
			}
			
			//print_r($data_to_be_inserted);
			if(isset($data_to_be_inserted['uuid']))
			{
				$primary_key_id = $data_to_be_inserted['uuid'];
				$this->db->where('uuid',$primary_key_id);
				$existing_record = $this->db->get($tbl);
			
				if(!empty($existing_record))
				{	
					//echo "UPDATE </br>";
					$update_data = array();
					array_push($update_data,$data_to_be_inserted);
					$update_results = $this->updateTable(array('update_data'=>$update_data),$tbl);
					
					if($update_results['result'])
					{
						$updated_record_count = $updated_record_count+1;
					}else{
						$update_failed_record_count = $update_failed_record_count +1;
					}
				}else{
					$inserted_id = $this->db->insert($tbl,$data_to_be_inserted);
					if($inserted_id>0)
					{
						$inserted_record_count = $inserted_record_count +1;
					}
					else
					{
						$insert_failed_record_count = $insert_failed_record_count +1;
					}
				}
			}else
			{
				$inserted_id = $this->db->insert($tbl,$data_to_be_inserted);
				if($inserted_id>0)
				{
					$inserted_record_count = $inserted_record_count +1;
				}
				else
				{
					$insert_failed_record_count = $insert_failed_record_count +1;
				}
			}
		}
		if($insert_failed_record_count>0 || $update_failed_record_count>0)
		{
			return array('result'=>FALSE,'message'=>'Please find the processed record count', 'total_record_count'=>$total_record_count, 'inserted_record_count'=>$inserted_record_count, 'updated_record_count'=>$updated_record_count, 'insert_failed_record_count'=>$insert_failed_record_count, 'update_failed_record_count'=>$update_failed_record_count);
		}
		else
		{
			return array('result'=>TRUE,'message'=>'Please find the processed record count', 'total_record_count'=>$total_record_count, 'inserted_record_count'=>$inserted_record_count, 'updated_record_count'=>$updated_record_count, 'insert_failed_record_count'=>$insert_failed_record_count, 'update_failed_record_count'=>$update_failed_record_count);
		}
	}
	
	public function isContains($src,$part)
	{
		if (strpos($src,$part) !== false) {
			return true;
		}
		return false;
	}
	
	public function getURLSettings()
	{
		return array('type'=>'url_settings', 'app'=>'hamstring', 'urls'=> array('image'=>IMAGES_PATH, 'file'=> FILES_PATH, 'json'=>JSON_PATH));
	}
	
	
	/**
	* Method Name: updateChallange
	* Param      : $data ---> field values
	* Return     : Updated Row Data
	* Use        : Update an existing record in 'tChallenge'
	*/
	public function updateTable($rawData,$tbl)
	{
		$data_to_be_updated_array= $rawData['update_data'];
		$update_results = array();
		$updated_data = array();
		foreach($data_to_be_updated_array as $data_to_be_updated)
		{
			$primary_key_id = $data_to_be_updated['uuid'];
			$this->db->where('uuid',$primary_key_id);
			$existing_record = $this->db->get($tbl);
			
			if(!empty($existing_record))
			{					
				switch($tbl)
				{	
					case 'tPri_Parent':
					case 'tPri_ParentUser':
					case 'tPri_Business':
					case 'tPri_User':
					case 'tPri_Team':
					case 'tPri_TeamUser':
					case 'tPri_SessionHeader':
					case 'tPri_SessionHeader_test':
					case 'tPri_ConfigurationValue':
					case 'tPri_TeamAthlete':
					case 'tPri_ShareAthlete':
					case 'tPri_ExerciseType':
						$modifyRecordAddInfo = $this->modifyRecordAddInfo($data_to_be_updated);
						if(!empty($modifyRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_updated, $modifyRecordAddInfo);
					break;
					
					case 'tPri_Athlete':
						$modifyRecordAddInfo = $this->modifyRecordAddInfo($data_to_be_updated);
						if(!empty($modifyRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_updated, $modifyRecordAddInfo);
						if(!isset($data_to_be_updated['fModifiedSessionTs']))
								$data_to_be_updated['fModifiedSessionTs'] = round(microtime(true) * 1000);
					break;
				}
				//echo $data_to_be_updated['fImage']."<br>";
				
				if(isset($data_to_be_updated['fImage']) && $this->isContains($data_to_be_updated['fImage'],DB_PATH)==false
				&& trim($data_to_be_updated['fImage'])!='')
				{
					$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_updated['fImage']));
					if($saveBaseSixtyFourImage['result'])
					{
						$data_to_be_updated['fImage'] = $saveBaseSixtyFourImage['db_path'];
					}
					else
					{
						$data_to_be_updated['fImage'] = '';
					}
				}
				else if(isset($data_to_be_updated['fImageUrl']) && $this->isContains($data_to_be_updated['fImageUrl'],DB_PATH)==false && trim($data_to_be_updated['fImageUrl'])!='')
				{
					$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>round(microtime(true) * 1000).'.png','encoded_image_data'=>$data_to_be_updated['fImageUrl']));
					if($saveBaseSixtyFourImage['result'])
					{
						$data_to_be_updated['fImageUrl'] = $saveBaseSixtyFourImage['db_path'];
					}
					else
					{
						$data_to_be_updated['fImageUrl'] = '';
					}
				}
				
				//echo $data_to_be_updated['fImage']."<br>";
				//exit;
				
				$this->db->where('uuid',$primary_key_id);
				$this->db->update($tbl,$data_to_be_updated);
				
				$this->db->where('uuid',$primary_key_id);
				$updated_record = $this->db->get($tbl);
				
				
				
				if(!empty($updated_record))
				{	
			
					if(isset($updated_record[0]['fImage']) && $this->isContains($updated_record[0]['fImage'],BASE_SERVER_URL)==false  && trim($updated_record[0]['fImage'])!='')
						$updated_record[0]['fImage'] = BASE_SERVER_URL.$updated_record[0]['fImage'];
					else if(isset($updated_record[0]['fImageUrl']) && $this->isContains($updated_record[0]['fImageUrl'],BASE_SERVER_URL)==false   && trim($updated_record[0]['fImageUrl'])!='')
						$updated_record[0]['fImageUrl'] = BASE_SERVER_URL.$updated_record[0]['fImageUrl'];
					
					array_push($updated_data,$updated_record[0]);
					array_push($update_results,$updated_record[0]['id']);
				}
				else
				{
					array_push($update_results,0);
				}
			}
			else
				array_push($update_results,0);
		}
		
		
		if(in_array(0,$update_results))
		{
			$message = 'Some of the records failed to update. You can find the updated records here!';
		}
		else
		{
			$message = UPDATE_SUCCESS;
		}
		
		if(!empty($updated_data))
			return array('result'=>TRUE,'message'=>$message, 'data'=>$updated_data);
		else
			return array('result'=>FALSE,'message'=>UPDATE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_UPDATE);
	}
	
	/**
	* Method Name: updateChallange
	* Param      : $data ---> field values
	* Return     : Updated Row Data
	* Use        : Update an existing record in 'tChallenge'
	*/
	public function updateTableWithID($rawData,$tbl)
	{
		$primary_key_id = $rawData['update_data']['id'];
		$this->db->where('id',$primary_key_id);
		$existing_record = $this->db->get($tbl);
		
		if(!empty($existing_record))
		{					
			$data_to_be_updated= $rawData['update_data'];
			switch($tbl)
			{	
				case 'tPub_ExerciseTypeDefault':
					$modifyRecordAddInfo = $this->modifyRecordAddInfo($data_to_be_updated);
					if(!empty($modifyRecordAddInfo))
						$data_to_be_updated = array_merge($data_to_be_updated, $modifyRecordAddInfo);
				break;				
				case 'tPri_Log':
					$modifyRecordAddInfo = $this->modifyRecordAddInfo($data_to_be_updated);
					if(!empty($modifyRecordAddInfo))
						$data_to_be_updated = array_merge($data_to_be_updated, $modifyRecordAddInfo);
				break;				
			}
			$this->db->where('id',$primary_key_id);
			$this->db->update($tbl,$data_to_be_updated);
			
			$this->db->where('id',$primary_key_id);
			$updated_record = $this->db->get($tbl);
			
			if(!empty($updated_record))
				return array('result'=>TRUE,'message'=>UPDATE_SUCCESS, 'primary_key_id'=>$primary_key_id, 'data'=>$updated_record[0]);
			else
				return array('result'=>FALSE,'message'=>UPDATE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_UPDATE);
		}
		else
			return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
	}
	
	
	/**
	* Method Name: deleteFromTable
	* Param      : $data ---> ids and $tbl---> $table name
	* Return     : Boolean
	* Use        : Delete record from '$tbl'
	*/
	public function deleteFromTableWithID($rawData,$tbl)
	{
		$ids_to_be_deleted= $rawData['delete_data'];
		$deleted_ids = array();
		foreach($ids_to_be_deleted as $id_to_be_deleted)
		{
			$primary_key_id = $id_to_be_deleted;
			$this->db->where('id',$primary_key_id);
			$existing_record = $this->db->get($tbl);
			$data_to_be_deleted = array();
			if(!empty($existing_record))
			{
				switch($tbl)
				{
					case 'tPub_ExerciseTypeDefault':
						$deleteRecordAddInfo = $this->deleteRecordAddInfo($data_to_be_deleted);
						if(!empty($deleteRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_deleted, $deleteRecordAddInfo);
					break;
					
					case 'tPri_Log':
						$deleteRecordAddInfo = $this->deleteRecordAddInfo($data_to_be_deleted);
						unset($deleteRecordAddInfo['fActive']);
						if(!empty($deleteRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_deleted, $deleteRecordAddInfo);
					break;
					
				}
				$this->db->where('id',$primary_key_id);
				$this->db->update($tbl,$data_to_be_updated);
				array_push($deleted_ids,$primary_key_id);
			}
			
		}
		$message = DELETE_SUCCESS;
		
		if(!empty($deleted_ids))
			return array('result'=>TRUE,'message'=>$message);
		else
			return array('result'=>FALSE,'message'=>DELETE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_DELETE);
	}
	
	/**
	* Method Name: deleteFromTable
	* Param      : $data ---> ids and $tbl---> $table name
	* Return     : Boolean
	* Use        : Delete record from '$tbl'
	*/
	public function deleteFromTable($rawData,$tbl)
	{
		$ids_to_be_deleted= $rawData['delete_data'];
		$deleted_ids = array();
		foreach($ids_to_be_deleted as $id_to_be_deleted)
		{
			$primary_key_id = $id_to_be_deleted;
			$this->db->where('uuid',$primary_key_id);
			$existing_record = $this->db->get($tbl);
			$data_to_be_deleted = array();
			if(!empty($existing_record))
			{
				switch($tbl)
				{
					case 'tPri_Parent':
					case 'tPri_ParentUser':
					case 'tPri_Business' :
					case 'tPri_User' :
					case 'tPri_Team':
					case 'tPri_SessionHeader':
					case 'tPri_SessionHeader_test':
					case 'tPri_ShareAthlete':
					case 'tPri_ExerciseType':
					case 'tPub_ExerciseTypeDefault':
						$deleteRecordAddInfo = $this->deleteRecordAddInfo($data_to_be_deleted);
						if(!empty($deleteRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_deleted, $deleteRecordAddInfo);
					break;
					
					case 'tPri_SessionData':
					case 'tPri_SessionData_test':
					case 'tPri_SessionRep':
					case 'tPri_TeamUser':
					case 'tPri_ConfigurationValue':
					case 'tPri_TeamAthlete':
						$deleteRecordAddInfo = $this->deleteRecordAddInfo($data_to_be_deleted);
						unset($deleteRecordAddInfo['fActive']);
						if(!empty($deleteRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_deleted, $deleteRecordAddInfo);
					break;
					case 'tPri_Athlete':
						$deleteRecordAddInfo = $this->deleteRecordAddInfo($data_to_be_deleted);
						if(!empty($deleteRecordAddInfo))
							$data_to_be_updated = array_merge($data_to_be_deleted, $deleteRecordAddInfo);
						if(!isset($data_to_be_updated['fModifiedSessionTs']))
							$data_to_be_updated['fModifiedSessionTs'] = round(microtime(true) * 1000);
						//print_r($data_to_be_updated);exit;
					break;
				}
				$this->db->where('uuid',$primary_key_id);
				$this->db->update($tbl,$data_to_be_updated);
				array_push($deleted_ids,$primary_key_id);
			}
			
		}
		$message = DELETE_SUCCESS;
		
		if(!empty($deleted_ids))
			return array('result'=>TRUE,'message'=>$message);
		else
			return array('result'=>FALSE,'message'=>DELETE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_DELETE);
	}
	
	/**
	* Method Name: deleteRowFromTable
	* Param      : $data ---> ids and $tbl---> $table name
	* Return     : Boolean
	* Use        : Delete record from '$tbl'
	*/
	public function deleteRowFromTable($rawData,$tbl)
	{
		$ids_to_be_deleted= $rawData['delete_data'];
		$deleted_ids = array();
		foreach($ids_to_be_deleted as $id_to_be_deleted)
		{
			$primary_key_id = $id_to_be_deleted;
			$this->db->where('uuid',$primary_key_id);
			$existing_record = $this->db->get($tbl);
			$data_to_be_deleted = array();
			if(!empty($existing_record))
			{
				$this->db->where('id',$primary_key_id);
				$this->db->delete($tbl);
				array_push($deleted_ids,$primary_key_id);
			}
			
		}
		$message = DELETE_SUCCESS;
		
		if(!empty($deleted_ids))
			return array('result'=>TRUE,'message'=>$message);
		else
			return array('result'=>FALSE,'message'=>DELETE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_DELETE);
	}
	
	public function readAllData($rawData)
	{
		$uuid_tUser = $rawData['uuid_tUser'];
		
		$this->db->where('uuid',$uuid_tUser);
		$this->db->where('fActive',1);
		$this->db->where('fDeleted',0);
		$userData = $this->db->get('tPri_User');
		if(!empty($userData))
		{
			$uuid_Business = $userData[0]['uuid_tBusiness'];
			$this->db->where('uuid',$uuid_Business);
			$this->db->where('fActive',1);
			$this->db->where('fDeleted',0);
			$businessData = $this->db->get('tPri_Business');
			if(!empty($businessData))
			{
				$data = $rawData['table_data'];
				foreach($data as $currentTableReq)
				{
					$currentTableContent = array();
					switch($currentTableReq['table'])
					{
						case 'tServerOnly_DeviceUserToken':
						case 'tServerOnly_EmailEvent':
						case 'tPri_Parent':
						case 'tPri_ParentUser':
							break;
						case 'tPub_ExerciseTypeDefault':
						case 'tPub_Help':
							$currentTableContent = $this->readTableData(array(),$currentTableReq['table']);
							break;
						case 'tPri_Business':
							$currentTableContent = $this->readTableData(array('last_updated_ts'=>$currentTableReq['last_updated_ts'], 'uuid'=>$uuid_Business),$currentTableReq['table']);
							break;
						default :
							$currentTableContent = $this->readTableData(array('last_updated_ts'=>$currentTableReq['last_updated_ts'],'uuid_tBusiness'=>$uuid_Business),$currentTableReq['table']);
							break;
					}
					if(!empty($currentTableContent) && $currentTableContent['result'])
					{
						$return[$currentTableReq['table']] = $currentTableContent['data'];
					}	
					unset($currentTableContent);
				}
				if(!empty($return))
				{
					return array('result'=>TRUE,'data'=>$return,'message'=>'Values Found');
				}else{
					return array('result'=>FALSE,'message'=>'No Values Found');
				}
			}else{
			return array('result'=>FALSE,'message'=>'Invalid Business');
			}
		}else{
			return array('result'=>FALSE,'message'=>'Invalid User');
		}
	}
	
	public function getRecordsCount($data)
	{
		if(isset($data))
		{
			$key_association = array_keys($data);
			foreach($key_association as $currentKey)
			{
				if($currentKey!='current_max_id' && $currentKey!='max_items' && $currentKey!='table')
				{
					if($currentKey!='last_updated_ts')
						$this->db->where($currentKey,$data[$currentKey]);
					else
						$this->db->where('fModifiedTs',$data[$currentKey],'>=');
				}
			}
			
			if(isset($data['current_max_id']))
			{	
				$this->db->where('id',$data['current_max_id'],'>');
				$return  = $this->db->get($data['table'],isset($data['max_items'])?$data['max_items']:20);
			}
			else
			{
				$return  = $this->db->get($data['table']);
			}
		}
		else
		{
			$return  = $this->db->get($tbl);
		}
		return sizeof($return);
	}
	
	public function readDataCount($rawData)
	{
		if(isset($rawData['uuid_tUser']))
		{
			$uuid_tUser = $rawData['uuid_tUser'];
			
			$this->db->where('uuid',$uuid_tUser);
			$this->db->where('fActive',1);
			$this->db->where('fDeleted',0);
			$userData = $this->db->get('tPri_User');
			
			if(!empty($userData))
			{
				$uuid_tBusiness = $userData[0]['uuid_tBusiness'];
				$this->db->where('uuid',$uuid_tBusiness);
				$this->db->where('fActive',1);
				$this->db->where('fDeleted',0);
				$businessData = $this->db->get('tPri_Business');
				if(!empty($businessData))
				{
					$returnData = array();
					
					$tPri_AthleteData = array('table'=>'tPri_Athlete','count'=>$this->getRecordsCount(array('table'=>'tPri_Athlete','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPri_BusinessData = array('table'=>'tPri_Business','count'=>$this->getRecordsCount(array('table'=>'tPri_Business','uuid'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPri_ConfigurationValueData = array('table'=>'tPri_ConfigurationValue','count'=>$this->getRecordsCount(array('table'=>'tPri_ConfigurationValue','uuid_tBusiness'=>$uuid_tBusiness, 'fDeleted'=>0)));
					$tPri_ExerciseTypeData = array('table'=>'tPri_ExerciseType','count'=>$this->getRecordsCount(array('table'=>'tPri_ExerciseType','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPri_LogData = array('table'=>'tPri_Log','count'=>$this->getRecordsCount(array('table'=>'tPri_Log','uuid_tBusiness'=>$uuid_tBusiness, 'fDeleted'=>0)));
					//$tPri_ParentData = array('table'=>'tPri_Parent','count'=>$this->getRecordsCount(array('table'=>'tPri_Parent',)));
					//$tPri_ParentUserData = array('table'=>'tPri_ParentUser','count'=>$this->getRecordsCount(array('table'=>'tPri_ParentUser',)));
					//$tPri_SessionDataData = array('table'=>'tPri_SessionData','count'=>$this->getRecordsCount(array('table'=>'tPri_SessionData','uuid_tBusiness'=>$uuid_tBusiness)));
					$tPri_SessionHeaderData = array('table'=>'tPri_SessionHeader','count'=>$this->getRecordsCount(array('table'=>'tPri_SessionHeader','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					//$tPri_SessionRepData = array('table'=>'tPri_SessionRep','count'=>$this->getRecordsCount(array('table'=>'tPri_SessionRep','uuid_tBusiness'=>$uuid_tBusiness)));
					$tPri_ShareAthleteData = array('table'=>'tPri_ShareAthlete','count'=>$this->getRecordsCount(array('table'=>'tPri_ShareAthlete','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPri_TeamData = array('table'=>'tPri_Team','count'=>$this->getRecordsCount(array('table'=>'tPri_Team','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPri_TeamAthleteData = array('table'=>'tPri_TeamAthlete','count'=>$this->getRecordsCount(array('table'=>'tPri_TeamAthlete','uuid_tBusiness'=>$uuid_tBusiness, 'fDeleted'=>0)));
					$tPri_TeamUserData = array('table'=>'tPri_TeamUser','count'=>$this->getRecordsCount(array('table'=>'tPri_TeamUser','uuid_tBusiness'=>$uuid_tBusiness, 'fDeleted'=>0)));
					$tPri_UserData = array('table'=>'tPri_User','count'=>$this->getRecordsCount(array('table'=>'tPri_User','uuid_tBusiness'=>$uuid_tBusiness, 'fActive'=>1, 'fDeleted'=>0)));
					$tPub_ExerciseTypeDefaultData = array('table'=>'tPub_ExerciseTypeDefault','count'=>$this->getRecordsCount(array('table'=>'tPub_ExerciseTypeDefault', 'fActive'=>1, 'fDeleted'=>0)));
					$tPub_HelpData = array('table'=>'tPub_Help','count'=>$this->getRecordsCount(array('table'=>'tPub_Help')));
					//$tServerOnly_BatchLogData = array('table'=>'tServerOnly_BatchLog','count'=>$this->getRecordsCount(array('table'=>'tServerOnly_BatchLog')));
					//$tServerOnly_DeviceUserTokenData = array('table'=>'tServerOnly_DeviceUserToken','count'=>$this->getRecordsCount(array('table'=>'tServerOnly_DeviceUserToken')));
					//$tServerOnly_EmailEventData = array('table'=>'tServerOnly_EmailEvent','count'=>$this->getRecordsCount(array('table'=>'tServerOnly_EmailEvent')));
					
					array_push($returnData, $tPri_AthleteData);
					array_push($returnData, $tPri_BusinessData);
					array_push($returnData, $tPri_ConfigurationValueData);
					array_push($returnData, $tPri_ExerciseTypeData);
					array_push($returnData, $tPri_LogData);
					//array_push($returnData, $tPri_ParentData);
					//array_push($returnData, $tPri_ParentUserData);
					//array_push($returnData, $tPri_SessionDataData);
					array_push($returnData, $tPri_SessionHeaderData);
					//array_push($returnData, $tPri_SessionRepData);
					array_push($returnData, $tPri_ShareAthleteData);
					array_push($returnData, $tPri_TeamData);
					array_push($returnData, $tPri_TeamAthleteData);
					array_push($returnData, $tPri_TeamUserData);
					array_push($returnData, $tPri_UserData);
					array_push($returnData, $tPub_ExerciseTypeDefaultData);
					array_push($returnData, $tPub_HelpData);
					//array_push($returnData, $tServerOnly_BatchLogData);
					//array_push($returnData, $tServerOnly_DeviceUserTokenData);
					//array_push($returnData, $tServerOnly_EmailEventData);
					
					return array('result'=>TRUE,'data'=>$returnData,'message'=>'Values Found');
					
				}else{
				return array('result'=>FALSE,'message'=>'Invalid Business');
				}
			}else{
				return array('result'=>FALSE,'message'=>'Invalid User');
			}
		}else{
			return array('result'=>FALSE,'message'=>'Invalid Request');
		}
	}
	
	public function deleteAndReadSessionHeader($rawData)
	{
		
		if(isset($rawData['uuid_tBusiness']))
		{
			$uuid_tBusiness = $rawData['uuid_tBusiness'];
			$this->db->where('uuid',$uuid_tBusiness);
			$this->db->where('fActive',1);
			$this->db->where('fDeleted',0);
			$businessData = $this->db->get('tPri_Business');
			if(!empty($businessData))
			{
				$returnData = array();
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_Athlete');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_ConfigurationValue');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_ExerciseType');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_Log');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$tPri_SessionHeaderData = $this->db->get('tPri_SessionHeader');
				foreach($tPri_SessionHeaderData as $currentTPri_SessionHeaderData)
				{
					$tempData['table'] = 'tPri_SessionHeader';
					$tempData['fSessionBody_uuid'] = $currentTPri_SessionHeaderData['fSessionBody_uuid'];
					array_push($returnData,$tempData);
					
					$this->db->where('id',$currentTPri_SessionHeaderData['id']);
					$this->db->delete('tPri_SessionHeader');
				}
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_SessionData');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_SessionRep');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_ShareAthlete');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_Team');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_TeamAthlete');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_TeamUser');
				
				$this->db->where('uuid_tBusiness',$uuid_tBusiness);
				$this->db->delete('tPri_User');
				
				$this->db->where('uuid_tParent',$businessData[0]['uuid_tParent']);
				$this->db->delete('tPri_ParentUser');
				
				$this->db->where('uuid',$businessData[0]['uuid_tParent']);
				$this->db->delete('tPri_Parent');
				
				$this->db->where('uuid',$uuid_tBusiness);
				$this->db->delete('tPri_Business');
				
				return array('result'=>TRUE,'data'=>$returnData,'message'=>'Values Deleted');
				
			}else{
			return array('result'=>FALSE,'message'=>'Invalid Business');
			}
		}else{
			return array('result'=>FALSE,'message'=>'Invalid Request');
		}
		
	}
	
	/**
	* Method Name: readTableData
	* Param      : $data ---> MAY BE fields
	* Return     : ARRAY OF RECORDS
	* Use        : READ API for $tbl
	*/
	public function readTableDataForMultipleUUIDs($data,$tbl)
	{
		if(isset($data['uuids']))
		{
			$uuids = $data['uuids'];
			$returnData = array();
			foreach($uuids as $uuid)
			{
				$uuidData = $this->readTableData(array('uuid'=>$uuid),$tbl);
				if($uuidData['result'])
				{
					array_push($returnData,$uuidData['data'][0]);
				}
			}
			$current_max_id=-1;
			if(!empty($returnData))
			{
				return array('result'=>TRUE,'current_max_id'=>$current_max_id,'message'=>'Values Found!','data'=>$returnData);
			}
			else
			{
				return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
			}
		}else
		{
			return $this->readTableData($data,$tbl);
		}
	}
	
	/**
	* Method Name: readTableData
	* Param      : $data ---> MAY BE fields
	* Return     : ARRAY OF RECORDS
	* Use        : READ API for $tbl
	*/
	public function readBusinessDataForMultipleUUIDs($data)
	{
		$tbl = 'tPri_Business';
		if(isset($data['uuids']))
		{
			$uuids = $data['uuids'];
			$returnData = array();
			foreach($uuids as $uuid)
			{
				$uuidData = $this->readTableData(array('uuid'=>$uuid),$tbl);
				if($uuidData['result'])
				{
					if(isset($uuidData['data']) && sizeof($uuidData['data'])>0)
					{
						$tempData = $uuidData['data'][0];
						unset($tempData['id']);
						unset($tempData['fActive']);
						unset($tempData['fDeleted']);
						unset($tempData['fCreatedTs']);
						unset($tempData['fModifiedTs']);
						unset($tempData['uuid_tUser_CreatedBy']);
						unset($tempData['uuid_tUser_ModifiedBy']);
						array_push($returnData,$tempData);
					}
				}
			}
			$current_max_id=-1;
			if(!empty($returnData))
			{
				return array('result'=>TRUE,'current_max_id'=>$current_max_id,'message'=>'Values Found!','data'=>$returnData);
			}
			else
			{
				return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
			}
		}else
		{
			return $this->readTableData($data,$tbl);
		}
	}
	
	/**
	* Method Name: readTableData
	* Param      : $data ---> MAY BE fields
	* Return     : ARRAY OF RECORDS
	* Use        : READ API for $tbl
	*/
	public function readTableData($data,$tbl)
	{
		if(isset($data))
		{
			$key_association = array_keys($data);
			foreach($key_association as $currentKey)
			{
				if($currentKey!='current_max_id' && $currentKey!='max_items' && $currentKey!='tbl')
				{
					if($currentKey!='last_updated_ts')
						$this->db->where($currentKey,$data[$currentKey]);
					else
						$this->db->where('fModifiedTs',$data[$currentKey],'>=');
				}
			}
			
			if(isset($data['current_max_id']))
			{	
				$this->db->where('id',$data['current_max_id'],'>');
				$return  = $this->db->get($tbl,isset($data['max_items'])?$data['max_items']:20);
			}
			else
			{
				$return  = $this->db->get($tbl);
			}
		}
		else
		{
			$return  = $this->db->get($tbl);
		}
		$current_max_id = 0;
		//$return  = $this->db->get('tChallenge');
		if(!empty($return))
		{
			$returnDataArray = array();
			foreach($return as $currentData)
			{
				$current_max_id = $currentData['id'];
				
				if(isset($currentData['fImage']) && $this->isContains($currentData['fImage'],BASE_SERVER_URL)==false && trim($currentData['fImage'])!='')
					$currentData['fImage'] = BASE_SERVER_URL.$currentData['fImage'];
				else if(isset($currentData['fImageUrl']) && $this->isContains($currentData['fImageUrl'],BASE_SERVER_URL)==false && trim($currentData['fImageUrl'])!='')
					$currentData['fImageUrl'] = BASE_SERVER_URL.$currentData['fImageUrl'];
				
				array_push($returnDataArray,$currentData);
			}
			if(!empty($returnDataArray))
			{
				return array('result'=>TRUE,'current_max_id'=>$current_max_id,'message'=>'Values Found!','data'=>$returnDataArray);
			}
			else
			{
				return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
			}
		}
		else
			return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
	}
	
	
	/**
	* Method Name: inserttImage
	* Param      : $data ---> field values and $tbl---> $table name
	* Return     : Inserted Row Data
	* Use        : Insert a new record in '$tbl'
	*/
	public function insertImage($rawData)
	{
		$tbl = 'tImage';
		$data_to_be_inserted_array= $rawData['insert_data'];
		$insert_results = array();
		$inserted_data = array();
		foreach($data_to_be_inserted_array as $data_to_be_inserted_temp)
		{
			if(!(isset($data_to_be_inserted_temp['fCreatedTs'])))
				$data_to_be_inserted['fCreatedTs'] = round(microtime(true) * 1000);
			
			$data_to_be_inserted['id_tUser'] = $data_to_be_inserted_temp['id_tUser'];
			$data_to_be_inserted['id_tImageType'] = $data_to_be_inserted_temp['id_tImageType'];
			$image_data = $this->saveBaseSixtyFourImage($data_to_be_inserted_temp);
			if($image_data['result'])
			{
				$data_to_be_inserted['fImageUrl'] = $image_data['url'];
			}
			else
			{
				$data_to_be_inserted['fImageUrl'] = '';
			}
			$inserted_id = $this->db->insert($tbl,$data_to_be_inserted);
			if($inserted_id>0)
			{
				array_push($insert_results,$inserted_id);
				$this->db->where('id',$inserted_id);
				$inserted_record = $this->db->get($tbl);
				array_push($inserted_data,$inserted_record[0]);
			}
			else
			{
				array_push($insert_results,0);
			}
		}
		if(in_array(0,$insert_results))
		{
			$message = 'Some of the records failed to insert. You can find the inserted records here!';
		}
		else
		{
			$message = INSERT_SUCCESS;
		}
		if(!empty($inserted_data))
			return array('result'=>TRUE,'message'=>$message, 'data'=>$inserted_data);
		else
			return array('result'=>FALSE,'message'=>INSERT_FAILED,'err_code'=>EC_RECORD_FAILED_TO_INSERT);
	}
	
	/**
	* Method Name: updateChallange
	* Param      : $data ---> field values
	* Return     : Updated Row Data
	* Use        : Update an existing record in 'tChallenge'
	*/
	public function updateImage($rawData)
	{
		$tbl = 'tImage';
		$primary_key_id = $rawData['update_data']['id'];
		$this->db->where('id',$primary_key_id);
		$existing_record = $this->db->get($tbl);
		
		
		if(!empty($existing_record))
		{	
			$data_to_be_updated_temp= $existing_record[0];
			//print_r($data_to_be_updated_temp);exit;
			$data_to_be_updated['fCreatedTs'] = isset($rawData['update_data']['fCreatedTs'])?$rawData['update_data']['fCreatedTs']:$data_to_be_updated_temp['fCreatedTs'];
			
			$data_to_be_updated['id_tUser'] = isset($rawData['update_data']['id_tUser'])?$rawData['update_data']['id_tUser']:$data_to_be_updated_temp['id_tUser'];
			$data_to_be_updated['id_tImageType'] = isset($rawData['update_data']['id_tImageType'])?$rawData['update_data']['id_tImageType']:$data_to_be_updated_temp['id_tImageType'];
			
			$image_data = $this->saveBaseSixtyFourImage($rawData['update_data']);
			if($image_data['result'])
			{
				$data_to_be_updated['fImageUrl'] = $image_data['url'];
			}
			else
			{
				$data_to_be_updated['fImageUrl'] = $data_to_be_updated_temp['fImageUrl'];
			}
			
			$this->db->where('id',$primary_key_id);
			$this->db->update($tbl,$data_to_be_updated);
			//print_r($data_to_be_updated);exit;
			$this->db->where('id',$primary_key_id);
			$updated_record = $this->db->get($tbl);
			if(!empty($updated_record))
				return array('result'=>TRUE,'message'=>UPDATE_SUCCESS, 'primary_key_id'=>$primary_key_id, 'data'=>$updated_record[0]);
			else
				return array('result'=>FALSE,'message'=>UPDATE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_UPDATE);
		}
		else
			return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
	}
	
	/**
	* Method Name: deleteImage
	* Param      : $data ---> field values
	* Return     : TRUE OR FALSE
	* Use        : Delete record from 'tImage'
	*/
	public function deleteImage($rawData)
	{
		$tbl = 'tImage';
		$primary_key_id = $rawData['tImageIdToDelete'];
		$this->db->where('id',$primary_key_id);
		$existing_record = $this->db->get($tbl);
		
		
		if(!empty($existing_record))
		{
			$deleteImage = mysqli_query($this->conn,"DELETE FROM tImage WHERE id=".$primary_key_id);
			//if(!empty($updated_record))
				return array('result'=>TRUE,'message'=>"Image Deleted Successfully");
			//else
				//return array('result'=>FALSE,'message'=>UPDATE_FAILED,'err_code'=>EC_RECORD_FAILED_TO_UPDATE);
		}
		else
			return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS,'err_code'=>EC_RECORD_NOT_EXISTS);
	}
	
	
	public function saveBaseSixtyFourImage($data)
	{
		try{
			if(isset($data['file_name_with_mime_type']) && isset($data['encoded_image_data']))
			{
				$file_name_with_mime_type = $data['file_name_with_mime_type'];
				$base64_data = $data['encoded_image_data'];
				$base64_data = str_replace('data:image/png;base64,', '', $base64_data);
				$base64_data = str_replace(' ', '+', $base64_data);
				$img_file = base64_decode($base64_data);
				file_put_contents(UPLOAD_PATH.$file_name_with_mime_type, $img_file);
				//echo UPLOAD_SERVER_PATH.$file_name_with_mime_type;exit;
				return array('result'=>TRUE,'message'=>'Image uploaded successfully!','url'=>UPLOAD_SERVER_PATH.$file_name_with_mime_type,'file_name_with_mime_type'=>$file_name_with_mime_type,'db_path'=>DB_PATH.$file_name_with_mime_type);
			}else{
				return array('result'=>FALSE,'message'=>'Failed to upload image! ');
			}
		}
		catch(Exception $e)
		{
			return array('result'=>FALSE,'message'=>'Failed to upload image! '.$e->getMessage());
		}
	}
	
	
	public function sendBase64Blob($data)
	{
		if(isset($data['base64_files']))
		{
			$base64_files = $data['base64_files'];
			$images_count = 0;
			$saved_count = 0;
			$saved_data = array();
			foreach($base64_files as $current_base64_file)
			{
				$saveBaseSixtyFourImage = $this->saveBaseSixtyFourImage(array('file_name_with_mime_type'=>$current_base64_file['name'],'encoded_image_data'=>$current_base64_file['base64']));
				$saved_data[] = array('name'=>$current_base64_file['name'], 'url'=>$saveBaseSixtyFourImage['url']);
			}
			return array('result'=>TRUE,'message'=>'Image(s) saved successfully', 'images_count'=>$images_count, 'saved_count'=>$saved_count, 'data'=>$saved_data);
		}else
		{
			return array('result'=>FALSE,'message'=>'Invalid Request');
		}
	}
	
	public function getSharedAtheleteInfoMultiple($data)
	{
		if(!empty($data))
		{
			$returnDataArray = array();
			foreach($data as $currentData)
			{
				$result = $this->getSharedAtheleteInfo($currentData);
				if($result['result'])
				{
					array_push($returnDataArray,$result['data']);
				}
			}
			if(!empty($returnDataArray))
			{
				return array('result'=>true,'message'=>'Records found!','data'=>$returnDataArray);
			}else
			{
				return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS);
			}
		}
		else
		{
			return array('result'=>FALSE,'message'=>'Invalid Request');
		}
	}
	public function getSharedAtheleteInfo($data)
	{
		if(isset($data['uuid_tBusiness']) && isset($data['uuid_tBusinessExternal']) && isset($data['uuid_tAthleteExternal']))
		{
			$uuid_tBusiness = $data['uuid_tBusiness'];
			$uuid_tBusinessExternal = $data['uuid_tBusinessExternal'];
			$uuid_tAthleteExternal = $data['uuid_tAthleteExternal'];
			
			$this->db->where('uuid_tBusiness',$uuid_tBusiness);
			$this->db->where('uuid_tBusinessExternal',$uuid_tBusinessExternal);
			$this->db->where('uuid_tAthleteExternal',$uuid_tAthleteExternal);
			$tPri_ShareAthleteData = $this->db->get('tPri_ShareAthlete');
			
			
			if(!empty($tPri_ShareAthleteData))
			{
				$processedETypeIDs = array();
				$processedAtheleteIDs = array();
				$processedSHIDs = array();
				
				$tPri_ExerciseType = array();
				$tPri_ShareAthlete = array();
				foreach($tPri_ShareAthleteData as $currentTPri_ShareAthleteData)
				{
					$this->db->where('uuid',$uuid_tAthleteExternal);
					$this->db->where('uuid_tBusiness',$uuid_tBusinessExternal);
					$tPri_AthleteData = $this->db->get('tPri_Athlete');
					
					$tPri_Athlete = array();
					foreach($tPri_AthleteData as $currentTPri_AthleteData)
					{
						if(!in_array($currentTPri_AthleteData['id'],$processedAtheleteIDs))
						{
							$this->db->where('uuid_tAthlete',$uuid_tAthleteExternal);
							$this->db->where('uuid_tBusiness',$uuid_tBusinessExternal);
							$tPri_SessionHeaderData = $this->db->get('tPri_SessionHeader');
							
							$tPri_SessionHeader = array();
							foreach($tPri_SessionHeaderData as $currentTPri_SessionHeaderData)
							{
								$getValidExcerciseUUID = $this->getValidExcerciseUUID($currentTPri_SessionHeaderData['uuid_tExerciseType'],$uuid_tBusiness);
								if($getValidExcerciseUUID !='')
								{
									$currentTPri_SessionHeaderData['uuid_tExerciseType']=$getValidExcerciseUUID;
									if(!in_array($currentTPri_SessionHeaderData['id'],$processedSHIDs))
									{
										array_push($tPri_SessionHeader,$currentTPri_SessionHeaderData);
										array_push($processedSHIDs,$currentTPri_SessionHeaderData['id']);
									}
								}
							}
							$currentTPri_AthleteData['tPri_SessionHeader'] = $tPri_SessionHeader;
							
							array_push($tPri_Athlete,$currentTPri_AthleteData);
							array_push($processedAtheleteIDs,$currentTPri_AthleteData['id']);
						}
					}
					$currentTPri_ShareAthleteData['tPri_Athlete'] = $tPri_Athlete;
							
					array_push($tPri_ShareAthlete,$currentTPri_ShareAthleteData);
				
				}
				$this->db->where('uuid_tBusiness',$uuid_tBusinessExternal);
				$tPri_ExerciseTypeData = $this->db->get('tPri_ExerciseType');
				
				foreach($tPri_ExerciseTypeData as $currentTPri_ExerciseTypeData)
				{
					if(!in_array($currentTPri_ExerciseTypeData['id'],$processedETypeIDs))
					{
						array_push($tPri_ExerciseType,$currentTPri_ExerciseTypeData);
						array_push($processedETypeIDs,$currentTPri_ExerciseTypeData['id']);
					}
				}
				
				//print_r($tPri_ExerciseType);exit;
				$tPri_ExerciseTypeNew = $this->getOptExcerciseTypeArray($tPri_ExerciseType,$uuid_tBusiness);
				//print_r($tPri_ExerciseTypeNew);exit;
				$returnDataTemp['tPri_ExerciseType'] = $tPri_ExerciseTypeNew;
				$returnDataTemp['tPri_ShareAthlete'] = $tPri_ShareAthlete;
				$returnData['result'] = TRUE;
				$returnData['message'] = 'Records found!';
				$returnData['data'] = $returnDataTemp;
				//print_r($returnData);exit
				return $returnData;
			}else
			{
				return array('result'=>FALSE,'message'=>RECORD_NOT_EXISTS);
			}
		}else
		{
			return array('result'=>FALSE,'message'=>'Invalid Request');
		}
	}
	
	public function getValidExcerciseUUID($tPri_ExerciseTypeUUID,$uuid_tBusiness)
	{
		$this->db->where("uuid",$tPri_ExerciseTypeUUID);
		$tPri_ExerciseTypeEx = $this->db->get('tPri_ExerciseType');
		if(!empty($tPri_ExerciseTypeEx))
		{
			$query = "SELECT * FROM tPri_ExerciseType WHERE uuid_tBusiness='".$uuid_tBusiness."' AND LOWER(fName)='".strtolower($tPri_ExerciseTypeEx[0]['fName'])."'";
			$return_1 = mysqli_query($this->conn,$query);
			$num_rows = mysqli_num_rows($return_1);
			$current_max_id = 0;
			
			if($num_rows>0)
			{
				$currentObjectTemp = mysqli_fetch_assoc($return_1);
				
				$this->db->where("id",$currentObjectTemp['id']);
				$currentObject = $this->db->get('tPri_ExerciseType');
				if(!empty($currentObject))
				{
					return $currentObject[0]['uuid'];
				}
			}
		}
		return '';
	}
	
	public function getOptExcerciseTypeArray($tPri_ExerciseType,$uuid_tBusiness)
	{
		$returnData = array();
		foreach($tPri_ExerciseType as $currentTPri_ExerciseType)
		{
			$query = "SELECT * FROM tPri_ExerciseType WHERE uuid_tBusiness='".$uuid_tBusiness."' AND LOWER(fName)='".strtolower($currentTPri_ExerciseType['fName'])."'";
			$return_1 = mysqli_query($this->conn,$query);
			$num_rows = mysqli_num_rows($return_1);
			$current_max_id = 0;
			
			if($num_rows>0)
			{
				$currentObjectTemp = mysqli_fetch_assoc($return_1);
				
				$this->db->where("id",$currentObjectTemp['id']);
				$currentObject = $this->db->get('tPri_ExerciseType');
				if(!empty($currentObject))
				{
					array_push($returnData,$currentObject[0]);
				}
			}
			
		}
		return $returnData;
	}
} // END class 

