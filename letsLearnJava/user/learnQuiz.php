<?php if ($step == "quiz") { ?>
    <div>
        <h2>Quiz</h2>
    </div>
    <div>
        <form method="post" action="learn.php?level=<?php echo $level_id ?>&step=quiz">                    
            <?php foreach ($questions as $key=>$question) { ?>
                <div class="form-group">
                    <label><?php echo $key+1 . ". " . $question->description ?> </label>
                
                    <?php if ($question->type_id == 3) { ?>
                        <input type="text" class="form-control" name="<?php echo $question->id ?>"><br>
                    <?php } ?>
                    
                    <?php if ($question->type_id == 4) { ?>
                        <div class="form-group form-check">
                            <?php foreach ($question->answers as $answer){ ?>
                                
<input type="checkbox" name="<?php echo $question->id ?>[]" value="<?php echo $answer->id ?>">                                <label class="form-check-label"><?php echo $answer->description ?></label>
                                <br>
                            <?php }?>
                        </div>
                    <?php } ?>
                                                            
                    <?php if ($question->type_id == 5) { ?>
                        <div class="form-group form-check"> 
                            <?php foreach ($question->answers as $answer){ ?>
                                <input type="radio" name="<?php echo $question->id ?>" value="<?php echo $answer->id ?>">
                                <label class="form-check-label"><?php echo $answer->description ?></label>
                                <br>
                            <?php }?>                                  
                        </div>
                    <?php } ?>
                </div>
            <?php } ?> 
            <div>
                <button type="submit" class="btn btn-primary">Υποβολή</button>
            </div>
        </form>
    </div>
<?php } ?>