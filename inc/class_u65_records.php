<?php 

class U65_Records extends Base {

	public static $tableName = 	PLUGINS_PREFIX.'plans_data';
		
	public function insert() {
		global $wpdb;
		$array = array('membershipID' => $_POST['membershipID'],
						'planID'=> $_POST['planID'],
						'age_from'=> $_POST['from'],
						'age_to'=> $_POST['to'],
						'single'=> $_POST['single'],
						'member'=> $_POST['member'],
						'family'=> $_POST['family'],
						'description'=> $_POST['description'],						
						'date' => date('Y-m-d H:i:s')
					);		
		$succ = $wpdb->insert( self::$tableName, $array );		
		if($succ) {			
			echo parent::message( $message = 'Your <b>'.$_POST['name'].'</b> data have been saved successfully.');
		} else {
			echo parent::message( $message = 'Error: data could not be inserted.');
		}
	}
	
	public function update() {
		global $wpdb;
		$array = array('membershipID' => $_POST['membershipID'],
						'planID'=> $_POST['planID'],
						'age_from'=> $_POST['from'],
						'age_to'=> $_POST['to'],
						'single'=> $_POST['single'],
						'member'=> $_POST['member'],
						'family'=> $_POST['family'],
						'description'=> $_POST['description']
					);
		$succ = $wpdb->update( self::$tableName, $array, array( 'ID' => $_POST['id'] ));
		if($succ) {			
			echo parent::message( $message = 'Your plans have been updated successfully.');
		} else {
			echo parent::message( $message = 'Error: plans could not be updated.');
		}		
		
	}

	public function delete() {
		global $wpdb;
		$succ = $wpdb->delete( self::$tableName, array( 'ID' =>  base64_decode( $_GET['id'] ) ), array( '%d' ) );
		if($succ) {			
			echo parent::message( $message = 'Your data have been deleted successfully.');
		} else {
			echo parent::message( $message = 'Error: data could not be deleted.');
		}
	}	
	
	public function getRecords() {
		global $wpdb;
		return $wpdb->get_results( "SELECT T2.name AS membershipName, T3.name AS planName, CONCAT( T1.age_from, '-', T1.age_to) AS age, T1.* FROM ". self::$tableName." AS T1 LEFT JOIN ".PLUGINS_PREFIX."memberships AS T2 ON T1.membershipID = T2.ID LEFT JOIN ".PLUGINS_PREFIX."plans AS T3 ON T1.planID = T3.ID ");   
	}
	
	public function editMembership() {		
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM ".self::$tableName." WHERE ID = '". base64_decode( $_GET['id'] ) ."'");
	}
	
	public function makeHTML() { 
		wp_enqueue_style( 'datatable' );
		wp_enqueue_style( 'custom' );
		wp_enqueue_style( 'style' );
		?>
		<div class="wrap">			
			<h2><?php echo __( PLUGIN_NANE.' Records', 'records' ); ?> </h2>	
			
					
			<form action="<?php echo admin_url('admin.php?page=u65_records'); ?>" method="post">
				
				<input type="hidden" name="data_plan" value="1">
				<input type="hidden" name="id" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->ID : ""; ?>">
				
				<select name="membershipID">
					<option> <?php _e("Select Membership");  ?> </option>
					<?php 
						foreach( parent::getMembershipsFromDB() as $value ) {
							$check = (( $data = $this->editMembership() )) ? $data[0]->membershipID : "";
							if($check == $value->ID) {
								$selected = 'selected';
							} else {
								$selected = "";
							}
							echo '<option '.$selected.' value="'. $value->ID .'">'. $value->name .'</option>';
						}					
					?>	
				</select>
				<select name="planID">
					<option> <?php _e("Select Plan");  ?> </option>
					<?php 
						foreach( parent::getPlansFromDB() as $value ) {
							$check = (( $data = $this->editMembership() )) ? $data[0]->planID : "";
							if($check == $value->ID) {
								$selected = 'selected';
							} else {
								$selected = "";
							}
							echo '<option '.$selected.' value="'. $value->ID .'">'. $value->name .'</option>';
						}					
					?>
				</select>
				
				<div id="age"> <?php _e("Age");  ?> </br>
					<?php _e("From");  ?> <input type="number" id="from" name="from" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->age_from : ""; ?>">
					<?php _e("To");  ?> <input type="number" id="to" name="to" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->age_to : ""; ?>">
				</div></br>
				<input type="text" name="single" placeholder="single" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->single : ""; ?>"></br>
				<input type="text" name="member" placeholder="Member" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->member : ""; ?>"></br>
				<input type="text" name="family" placeholder="Family" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->family : ""; ?>"></br>
				<input type="text" name="description" placeholder="Description" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->description : ""; ?>"></br> 
				<button type="submit"><?php echo (( $_GET['edit'] == "true")) ? "Update" : "Add"; ?></button>
				
				
							
			</form>
			<div class="u65-panel">			
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Memberships</th>
							<th>plans</th>
							<th>Age</th>
							<th>Single</th>
							<th>Member</th>
							<th>Family</th>
							<th>Description</th>
							<th>Date</th>
							<th style="width: 138px;">Action</th>							
						</tr>
					</thead>        
					<tbody>
					<?php 
					foreach($this->getRecords() as $value) {
						echo '<tr>
								<td>'.$value->ID.'</td>
								<td>'.$value->membershipName.'</td>
								<td>'.$value->planName.'</td>
								<td>'.$value->age.'</td>
								<td>'.$value->single.'</td>
								<td>'.$value->member.'</td>
								<td>'.$value->family.'</td> 								
								<td>'.$value->description.'</td> 
								<td>'.$value->date.'</td>
								<td><a href='.admin_url("admin.php?page=u65_records&edit=true&id=".base64_encode( $value->ID )."").' class="edit-button" >Edit</a> <a href='.admin_url("admin.php?page=u65_records&delete=true&id=".base64_encode( $value->ID )."").' class="delete-button" >Delete</a></td>					
							</tr>';
					}					
					?>
					</tbody>
				</table>
			</div>
		</div>
		<?php 
	}
	
	public function display() {
		
		if( isset( $_POST[ 'data_plan'] ) and $_POST[ 'data_plan']==1 and $_POST[ 'id'] == "" ) { 
			$this->insert();
		}
		if( isset( $_POST[ 'data_plan'] ) and $_POST[ 'data_plan'] == 1 and $_POST[ 'id'] != "") { 
			$this->update(); 
		}
		if( isset( $_GET[ 'delete'] ) and $_GET[ 'delete'] == "true" and $_GET[ 'id'] != "") { 
			$this->delete(); 
		}
		$this->makeHTML();
	}
}


?>