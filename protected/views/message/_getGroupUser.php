<?php foreach ($user as $value) {?>
	<div class="user_id_chk">
		<input type="checkbox" name="user_id[]" value="<?php echo $value->id;?>" <?php if(in_array($value->id, $user_id)){ ?> checked="true" <?php }?>>
		<?php echo $value->name;?>	
		<?php if($_POST['id'] == 7){?>
		(<?php echo $value->supplier->name;?>	)
		<?php }?>
	</div>
<?php }?>
