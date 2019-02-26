<?php 

class U65_Overview extends Base {

	public static $tableName = 	PLUGINS_PREFIX.'plans_data';
	
	
	public function query() {
		global $wpdb;
		$result = $wpdb->get_results( "SELECT CONCAT(T1.age_from, '-', T1.age_to) AS age, T2.name AS membershipName, T3.name AS planName, T1.* FROM ". self::$tableName." AS T1 LEFT JOIN ".PLUGINS_PREFIX."memberships AS T2 ON T1.membershipID = T2.ID LEFT JOIN ".PLUGINS_PREFIX."plans AS T3 ON T1.planID = T3.ID");
		
		$data = array();
		$planName = '';
		$membershipName = '';
		foreach ( $result  as $k => $v ) {
			$data[$v->membershipName][$v->planName][] = $v;
			if ( $membershipName == "" || $membershipName != $v->membershipName ) {
				$membershipName = $v->membershipName;
			}
		}	
		return $data;
	}
	
	public function get() {
		global $wpdb;
		return $data = $wpdb->get_results( "SELECT * from u65_plans_data group by membershipID ");
	}
	
	public function getPlans() {
		foreach($this->get() as $val) {
			echo $val->membershipID;
			$this->plans( $val->membershipID );
		}		
	} 
	
	public function plans($id) {
		global $wpdb;
		 $data = $wpdb->get_results( "SELECT * from u65_plans_data where membershipID = '{$id}' group by planID ");
		foreach($data as $val) {
			echo $val->planID;
			$this->plansget( $val->planID );
		}
	}
	public function plansget($id) {
		global $wpdb;
		 $data = $wpdb->get_results( "SELECT * from u65_plans_data where planID = '{$id}'  ");
		 echo '<pre>'; print_r($data);
	}
		
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
		
<style>
.member-area .list {float:left; width:25%; padding-right:15px; margin-top:10px;}	
.member-area .list h4 {background: #898B8C; text-transform: capitalize; color: #fff; padding: 8px 12px; font-size: 13px; margin:0 0 15px;}
</style>
			
			<h2><?php echo __( PLUGIN_NANE.' Records', 'records' ); ?> </h2>	
			<?php 
				foreach( $this->query() as $k => $item ) {			
			?>
			<div class="u65-panel">		
			<label> <?php echo _e("Monthly Contribution Request for ".$k); ?> </label>
			
			<div class="member-area">  <?php  
				foreach ( $item as $k => $data ):
					echo '<div class="list"> <h4>'.$k.'</h4>';
					?>
				
					
					<table width="100%">
					<tr>
					<th> Age </th>
					<th>single</th>
					<th>Member</th>
					<th>Family</th> 
						
						</th>
						</tr>
						
						
					
						<?php  
						foreach ( $data as $k => $value ):
							echo '<tr> <td>'.$value->age.'</td>';
							echo '<td>'.$value->single.'</td>';
							echo '<td>'.$value->member.'</td>';
							echo '<td>'.$value->family.'</td></tr>';
						endforeach; ?>
						
						
						

					</table>
					
					
					<?php
					echo '</div>';
				endforeach; ?>
			</div>
			
			
			
			</div>	
			
			
		
		<?php 
		
	} ?>
	</div>	 <?php 
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

