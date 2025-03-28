<?php

use koolreport\core\Utility;

foreach($this->data as $item)
{
    $value = $item["value"];
    $text = $item["text"];
    $id = "raditem".Utility::getUniqueId();
?>
    <div class="form-check <?php echo $this->display=="horizontal"?"form-check-inline":""; ?>">
        <input class="form-check-input" id="<?php echo $id; ?>" type="radio" name="<?php echo $this->name; ?>" value="<?php echo $value ?>" <?php echo($this->value==$value)?"checked":""; ?><?php echo $this->disabled?" disabled":"";?>>
        <label class="form-check-label" for="<?php echo $id; ?>"><?php echo $text; ?></label>
    </div>
<?php
}
?>
<script type="text/javascript">
KoolReport.widget.init(<?php echo json_encode($this->getResources()); ?>,function(){
    <?php echo $this->name; ?> = new RadioList("<?php echo $this->name; ?>");
    <?php
    if($this->clientEvents)
    {
    foreach($this->clientEvents as $eventName=>$function)
    {
    ?>
        <?php echo $this->name; ?>.on('<?php echo $eventName; ?>',<?php echo $function; ?>);
    <?php
    }    
    }
    ?>
    <?php $this->clientSideReady();?>
});
</script>