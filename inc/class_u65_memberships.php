<?php 

class U65_Memberships extends Base {

	public static $tableName = 	PLUGINS_PREFIX.'memberships';
	
	public function insert() {
		global $wpdb;
		$array = array('name' => $_POST['name'],
						'description'=> $_POST['description'],
						'date' => date('Y-m-d H:i:s')
					);		
		$succ = $wpdb->insert( self::$tableName, $array );		
		if($succ) {			
			echo parent::message( $message = 'Your <b>'.$_POST['name'].'</b> membership have been saved successfully.');
		} else {
			echo parent::message( $message = 'Error: Membership could not be inserted.');
		}
	}
	
	public function update() {
		global $wpdb;
		$array = array('name' => $_POST['name'],
						'description'=> $_POST['description'],
					);
		$succ = $wpdb->update( self::$tableName, $array, array( 'ID' => $_POST['id'] ) );
		if($succ) {			
			echo parent::message( $message = 'Your <b>'.$_POST['name'].'</b> membership have been updated successfully.');
		} else {
			echo parent::message( $message = 'Error: Membership could not be updated.');
		}		
		
	}

	public function delete() {
		global $wpdb;
		$succ = $wpdb->delete( self::$tableName, array( 'ID' =>  base64_decode( $_GET['id'] ) ), array( '%d' ) );
		if($succ) {			
			echo parent::message( $message = 'Your membership have been deleted successfully.');
		} else {
			echo parent::message( $message = 'Error: Membership could not be deleted.');
		}
	}	
	
	public function getRecords() {
		global $wpdb;
		return $wpdb->get_results( "SELECT * FROM ".self::$tableName);   
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
			<h2><?php echo __( PLUGIN_NANE.' Membership', 'memberships' ); ?> </h2>	
			
					
			<form action="<?php echo admin_url('admin.php?page=u65_memberships'); ?>" method="post">
				
				<input type="hidden" name="membership" value="1">
				<input type="hidden" name="id" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->ID : ""; ?>">
				<div class="form-container">
					<label> <?php echo _e("Name"); ?> </label>
					<input type="text" placeholder="Enter Name" name="name" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->name : ""; ?>" required>
			
					<label> <?php echo _e("Description"); ?> </label>
					<input type="text" placeholder="Enter Description" value="<?php echo (( $data = $this->editMembership() )) ? $data[0]->description : ""; ?>" name="description" required>

					<button type="submit"><?php echo (( $_GET['edit'] == "true")) ? "Update" : "Add"; ?></button>
				</div>				
			</form>
			<div class="u65-panel">			
				<table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
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
								<td>'.$value->name.'</td> 
								<td>'.$value->description.'</td> 
								<td>'.$value->date.'</td>
								<td><a href='.admin_url("admin.php?page=u65_memberships&edit=true&id=".base64_encode( $value->ID )."").' class="edit-button" >Edit</a> <a href='.admin_url("admin.php?page=u65_memberships&delete=true&id=".base64_encode( $value->ID )."").' class="delete-button" >Delete</a></td>					
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
		
		if( isset( $_POST[ 'membership'] ) and $_POST[ 'membership']==1 and $_POST[ 'id'] == "" ) { 
			$this->insert(); 
		}
		if( isset( $_POST[ 'membership'] ) and $_POST[ 'membership'] == 1 and $_POST[ 'id'] != "") { 
			$this->update(); 
		}
		if( isset( $_GET[ 'delete'] ) and $_GET[ 'delete'] == "true" and $_GET[ 'id'] != "") { 
			$this->delete(); 
		}	
		$this->makeHTML();
	}
}


?>