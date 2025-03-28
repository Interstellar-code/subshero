<?php
use \koolreport\core\Utility;
?>
<input id="<?php echo $this->name; ?>" name="<?php echo $this->name; ?>" class="date-range-picker form-control" />
<script type="text/javascript">
KoolReport.widget.init(<?php echo json_encode($this->getResources()); ?>,function(){
    <?php echo $this->name; ?> = new DateRangePicker('<?php echo $this->name; ?>',<?php echo Utility::jsonEncode($options);?>);
    <?php
    if($this->clientEvents)
    {
        foreach($this->clientEvents as $eventName=>$function)
        {
        ?>
            <?php echo $this->name; ?>.on("<?php echo $eventName; ?>",<?php echo $function; ?>);
        <?php
        }
    }
    ?>
    <?php $this->clientSideReady();?>
});
</script>