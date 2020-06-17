<?php if ($step == "exercise") { ?>
    <div>
        <h2>Λυμένες ασκήσεις</h2>
    </div>
    <div>
        <ul class="exercises_nav">
        <?php foreach ($exercises as $key=>$exercise) { ?>
            <li><a href="#<?php echo $exercise->id ?>">
                <?php echo ($key+1) .". ".$exercise->title ?>
            </a></li>
        <?php } ?>
        </ul>
    </div>
    <div>
        <?php foreach ($exercises as $exercise) { ?>
            <h3 id="<?php echo $exercise->id ?>"><?php echo $exercise->title ?></h3>
            <div>
                <?php echo $exercise->description ?>
            </div>
            <div>
                <?php echo $exercise->solution ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>