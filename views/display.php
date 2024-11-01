
<div id="osanswerswidget">
    <?php if ($total > 0): ?>
        <div class="os-answer-total"><?php echo $this->translate('Total Answered'); ?>: <?php echo $total; ?></div>
        <div class="os-answer-list">
            <?php foreach ($answers as $key => $answer): ?>
                <div class="os-answer <?php echo (($key + 1) == $maxAnswers ? 'os-last-answer' : ''); ?>">
                    <div class="os-answer-votes <?php echo ($answer['is_accepted'] != 0 ? 'answered-accepted' : ''); ?>"><?php echo (int) $answer['score']; ?></div>
                    <div class="os-answer-link"><a class="answer-hyperlink" href="<?php echo $answer['link']; ?>"><?php echo $answer['title']; ?></a></div>
                    <div class="os-answer-date"><?php echo date('jS M y', $answer['creation_date']); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p><?php echo $this->translate('Just started! Have not answered any questions.'); ?></p>
    <?php endif; ?>
</div>
