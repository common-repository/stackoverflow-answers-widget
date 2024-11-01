<p>
    <label for="<?php echo $this->get_field_id('title'); ?>">
        <?php echo $this->translate('Title'); ?>:
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
    </label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('userid'); ?>">
        <?php echo $this->translate('StackOverflow User Number'); ?>:
        <input class="widefat" id="<?php echo $this->get_field_id('userid'); ?>" name="<?php echo $this->get_field_name('userid'); ?>" type="text" value="<?php echo $userId; ?>" />
    </label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('totalAnswers'); ?>">
        <?php echo $this->translate('Number of answers to show'); ?>:
        <input  size="3"  id="<?php echo $this->get_field_id('totalAnswers'); ?>" name="<?php echo $this->get_field_name('totalAnswers'); ?>" type="text" value="<?php echo $total; ?>" />
    </label>
</p>
<p>
    <label for="<?php echo $this->get_field_id('sort'); ?>">
        <?php echo $this->translate('Sort by'); ?>:
        <select class="widefat" id="<?php echo $this->get_field_id('sort'); ?>" name="<?php echo $this->get_field_name('sort'); ?>">
            <?php foreach ($this->sortOptions as $sortValue => $sortName): ?>
                <option value="<?php echo $sortValue; ?>" <?php echo $sortValue == $sort ? 'selected="selected"' : ''; ?>><?php echo $sortName; ?></option>
            <?php endforeach; ?>
        </select>
    </label>
</p>