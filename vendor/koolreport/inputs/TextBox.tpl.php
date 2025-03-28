<?php if($this->frontText!==null||$this->backText!==null): ?>
    <div class="input-group">
        <?php if($this->frontText!==null): ?>
            <div class="input-group-prepend">
                <span class="input-group-text"><?php echo $this->frontText; ?></span>
            </div>
        <?php endif ?>
        <input id="<?php echo $this->name; ?>" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>" type="textbox" <?php
            foreach($this->attributes as $name=>$value)
            {
                echo "$name='$value' ";
            }
        ?> />
        <?php if($this->backText!==null): ?>
            <div class="input-group-append">
                <span class="input-group-text"><?php echo $this->backText; ?></span>
            </div>
        <?php endif ?>
    </div>
<?php else: ?>
    <input id="<?php echo $this->name; ?>" name="<?php echo $this->name; ?>" value="<?php echo $this->value; ?>" type="textbox" <?php
    foreach($this->attributes as $name=>$value)
    {
        echo "$name='$value' ";
    }
?> />
<?php endif ?>


<script type="text/javascript">
KoolReport.widget.init(<?php echo json_encode($this->getResources()); ?>,function(){
    <?php echo $this->name; ?> = $("#<?php echo $this->name; ?>");
    <?php
    foreach($this->clientEvents as $name=>$function)
    {
    ?>
        <?php echo $this->name; ?>.on('<?php echo $name; ?>',<?php echo $function; ?>);
    <?php
    }
    ?>
    var name = <?php echo $this->name; ?>;
    name.reset = function() {
        name[0].value = name[0].defaultValue;
    };
    name.disable = function(bool) {
        name.attr('disabled',bool);
    };
    <?php $this->clientSideReady();?>
});
</script>
