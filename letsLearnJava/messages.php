<?php if ($info != "" || $error != "") { ?>
    <div class="message">
        <div class="container">
            <?php if ($info != "") { ?>
                <div class="alert alert-primary" role="alert">
                    <?php echo $info; ?>
                </div>
            <?php } ?>
            <?php if ($error != "") { ?>
                <div class="alert alert-danger" role="alert">
                    Παρουσιάστηκε κάποιο πρόβλημα, παρακαλω προσπαθήστε ξανά
                    <br/>
                    <?php echo $error; ?>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>