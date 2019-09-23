<?php
	$cls_str = "class='clickable-row' data-href='"
		.$this->getLink('HK01', 'visit/edit', 'visit/view', array('index'=>$this->record['id']))
		."'";
?>
<tr>
	<td width=3%>
	<?php
		$rw = Yii::app()->user->validRWFunction('HK01'); 
		$icon = $this->record['cust_vip']=='Y' ? 'fa fa-star' : 'fa fa-star-o';
		$lnk = $rw ? "javascript:star(".$this->record['id'].");" : "#";
		$aid = 'star_'.$this->record['id'];
		echo "<a href=\"$lnk\" id=\"$aid\"><span class=\"$icon\"></span></a>";
		echo TbHtml::hiddenField('vip_'.$this->record['id'],$this->record['cust_vip']);
	?>
	</td>
<?php if (!Yii::app()->user->isSingleCity()) : ?>
	<td <?php echo $cls_str;?>><?php echo $this->record['city_name']; ?></td>
<?php endif ?>
	<td width='12.5%' <?php echo $cls_str;?>><?php echo $this->record['visit_dt']; ?></td>
<!--
	<td width='12.5%'><?php // echo $this->record['status_dt']; ?></td>
-->
	<td <?php echo $cls_str;?>><?php echo $this->record['cust_type']; ?></td>
	<td <?php echo $cls_str;?> id='<?php echo 'name_'.$this->record['id'];?>'><?php echo $this->record['cust_name']; ?></td>
	<td <?php echo $cls_str;?>><?php echo $this->record['visit_type']; ?></td>
	<td <?php echo $cls_str;?>><?php echo $this->record['visit_obj']; ?></td>
	<td <?php echo $cls_str;?>><?php echo $this->record['district']; ?></td>
	<td <?php echo $cls_str;?> ><?php for ($i=0;$i<count($this->record['quote']);$i++) {if($i==3||$i==6){echo "<br/>";} echo $this->record['quote'][$i]; } ?>  </td>
<?php if (VisitForm::isReadAll()) : ?>
	<td <?php echo $cls_str;?>><?php echo $this->record['staff']; if($this->record['shift']=='Y'){echo "(旧)";}?></td>
<?php endif ?>
</tr>
